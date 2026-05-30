#!/usr/bin/env python3
"""
LUPOPEDIA HEADERS Validation Script

Validates that markdown files with LUPOPEDIA HEADERS follow the correct format:
1. Must start with --- (line 1)
2. Must have proper header structure
3. Must have required fields
4. Must have proper footer structure if present

Optional --check-db: after YAML validation, compare edges/history on disk to MySQL
(lupo_edges with edge_category=lupopedia_header, lupo_contents.revision_history).
Uses the same table prefix and connection pattern as import_content / generate_headers_from_db.

Usage:
  python scripts/validate_lupopedia_headers.py <file_path>
  python scripts/validate_lupopedia_headers.py <file_path> --check-db
"""

import argparse
import sys
import os
import re
import yaml
from pathlib import Path

_SCRIPTS_DIR = Path(__file__).resolve().parent
if str(_SCRIPTS_DIR) not in sys.path:
    sys.path.insert(0, str(_SCRIPTS_DIR))

REQUIRED_HEADER_FIELDS_V4_1_0 = (
    "header_format_version",
    "file_path_from_root",
    "web_path",
    "status",
    "when_updated",
    "trust_tier",
    "questions_toon",  # was last_modified_utc (renamed PRD 16 v4.0.99 §4.2 field 6→7)
    "memory_toon",     # was memory_key (renamed PRD 16 v4.1.0 §4.2 field 8)
    "atoms_toon",      # was module (renamed PRD 16 v4.0.99 §4.2 field 9)
    "transcript_jsonl",  # was dialog_transcript (renamed PRD 16 v4.1.0 §4.2 field 10)
    "artifact_type",
    "artifact_kind",
    "channel_key",
    "federation_node_id",
    "thread_id",
    "content_id",
    "pk_id",
    "pk_slug",
    "parent_pk_id",
    "lupopedia.schema",
    "title",
    "summary",
)
# Legacy alias kept for any callers that reference the old name
REQUIRED_HEADER_FIELDS_V4_0_99 = REQUIRED_HEADER_FIELDS_V4_1_0
# Legacy field names accepted during migration (PRD 16 v4.1.0)
_LEGACY_FIELD_MAP = {
    "memory_key": "memory_toon",
    "dialog_transcript": "transcript_jsonl",
    "module": "atoms_toon",
}
THREAD_ID_RE = re.compile(r"^[a-z0-9][a-z0-9-]*$")
YMDHIS_RE = re.compile(r"^\d{14}$")


def _header_value_present(val):
    if val is None:
        return False
    if isinstance(val, str) and not str(val).strip():
        return False
    if isinstance(val, (list, dict)) and len(val) == 0:
        return False
    return True


class LupopediaHeaderValidator:
    def __init__(self):
        self.errors = []
        self.warnings = []
        self._pending_header_data = None

    def _warn_missing_content_id(self, file_path, headers):
        if not isinstance(headers, dict):
            return
        cid = headers.get("content_id")
        if cid is None or cid == "":
            self.warnings.append(
                "%s: No content_id - file not linked to lupo_contents. "
                "Import first: python scripts/import_content.py \"%s\""
                % (file_path, file_path)
            )
        else:
            s = str(cid).strip()
            if s and not s.isdigit():
                self.warnings.append("%s: content_id should be numeric (got %r)" % (file_path, cid))
        
    def validate_file(self, file_path):
        """Validate a single file for LUPOPEDIA HEADERS compliance"""
        try:
            with open(file_path, 'r', encoding='utf-8') as f:
                content = f.read()
                lines = content.split('\n')
            
            return self.validate_content(content, lines, file_path)
            
        except Exception as e:
            self.errors.append(f"Error reading {file_path}: {e}")
            return False
    
    def validate_content(self, content, lines, file_path):
        """Validate content for LUPOPEDIA HEADERS compliance"""
        valid = True
        
        # Check 1: Must start with ---
        if not content.startswith('---'):
            self.errors.append(f"{file_path}: File must start with --- (line 1)")
            valid = False
        
        # Check 2: parse header
        header_content = None
        header_data = None


        # v4.1.0 fixed-position envelope: 25 lines (0-24), lines 0/24 are ---
        if len(lines) >= 26 and lines[0].strip() == '---' and lines[1].strip() == 'lupopedia.headers:' and lines[24].strip() == '---':
            header_content = "\n".join(lines[1:24])
        else:
            self.errors.append(f"{file_path}: Header does not match v4.1.0 25-line envelope (see PRD 16 §4.2)")
            valid = False

        if header_content is not None:
            
            try:
                header_data = yaml.safe_load(header_content)
                self._pending_header_data = header_data if isinstance(header_data, dict) else None
                
                if 'lupopedia.headers' not in header_data:
                    self.errors.append(f"{file_path}: Missing lupopedia.headers section")
                    valid = False
                else:
                    headers = header_data['lupopedia.headers']
                    # Validate 22-key v4.1.0 schema; accept legacy field names as fallback with WARN
                    _legacy_map = {
                        "memory_key": "memory_toon",
                        "dialog_transcript": "transcript_jsonl",
                        "module": "atoms_toon",
                    }
                    for legacy_name, canonical_name in _legacy_map.items():
                        if legacy_name in headers and canonical_name not in headers:
                            self.warnings.append(
                                f"{file_path}: deprecated field {legacy_name!r} "
                                f"(HDR_{legacy_name.upper()}_RENAMED): rename to "
                                f"{canonical_name!r} per PRD 16 v4.1.0 §4.2"
                            )
                    for field in REQUIRED_HEADER_FIELDS_V4_1_0:
                        if field not in headers:
                            # Accept legacy fallback without raising an error (WARN already above)
                            legacy_present = any(
                                leg for leg, can in _legacy_map.items()
                                if can == field and leg in headers
                            )
                            if not legacy_present:
                                self.errors.append(f"{file_path}: Missing required header field '{field}'")
                                valid = False
                    # content_id and pk_id may be null; thread_id and pk_slug may be empty string
                    # atoms_toon must be null or non-empty string (empty string forbidden)
                    atoms_val = headers.get("atoms_toon", headers.get("module", None))
                    if atoms_val == "":
                        self.errors.append(
                            f"{file_path}: atoms_toon must be null or non-empty string, "
                            "not empty string (HDR_ATOMS_TOON_SUFFIX)"
                        )
                        valid = False
                    # trust_tier validation
                    tier = str(headers.get("trust_tier", "")).strip()
                    if tier not in ("seed", "canonical", "staging", "archive"):
                        self.errors.append(f"{file_path}: trust_tier must be one of seed|canonical|staging|archive")
                        valid = False
                    # web_path validation
                    web_path = headers.get("web_path", "")
                    if not (web_path.startswith("http://") or web_path.startswith("https://")):
                        self.errors.append(f"{file_path}: web_path must start with http:// or https://")
                        valid = False
                    if "/lupopedia/" not in web_path:
                        self.warnings.append(f"{file_path}: web_path should include /lupopedia/ subdirectory")
                    # pk_id validation
                    pk_id = headers.get("pk_id")
                    if pk_id is not None and pk_id != "null":
                        try:
                            if not (isinstance(pk_id, int) and pk_id > 0):
                                int_pk = int(pk_id)
                                if int_pk <= 0:
                                    raise ValueError
                        except Exception:
                            self.errors.append(f"{file_path}: pk_id must be null or positive integer")
                            valid = False
                    # parent_pk_id validation
                    parent_pk_id = headers.get("parent_pk_id")
                    if headers.get("lupopedia.schema") == "implementation" and (not parent_pk_id or not str(parent_pk_id).strip()):
                        self.errors.append(f"{file_path}: parent_pk_id required and non-empty for implementation schema")
                        valid = False
                    # transcript_jsonl format (was dialog_transcript in v4.0.x; accept legacy with WARN)
                    if "dialog_transcript" in headers and "transcript_jsonl" not in headers:
                        self.warnings.append(
                            f"{file_path}: deprecated field dialog_transcript (HDR_DIALOG_TRANSCRIPT_RENAMED): "
                            "rename to transcript_jsonl per PRD 16 v4.1.0 §4.2 field 10"
                        )
                    if "memory_key" in headers and "memory_toon" not in headers:
                        self.warnings.append(
                            f"{file_path}: deprecated field memory_key (HDR_MEMORY_KEY_RENAMED): "
                            "rename to memory_toon per PRD 16 v4.1.0 §4.2 field 8"
                        )
                    dt = headers.get("transcript_jsonl", headers.get("dialog_transcript", ""))
                    if dt and not re.match(r"^\d+/[a-zA-Z0-9_-]+/[a-zA-Z0-9_-]+$", dt):
                        self.errors.append(f"{file_path}: transcript_jsonl must be in format '{{federation_node_id}}/{{channel_key}}/{{thread_slug}}' (got {dt})")
                        valid = False
                    # Remove all footer, actor_id, actor_name, delegation_chain, purpose, tags validation (sidecar only)
                
            except yaml.YAMLError as e:
                self.errors.append(f"{file_path}: Invalid YAML in header: {e}")
                valid = False
        
        return valid

    def check_db_sync(self, file_path, header_data):
        """
        Warn if file declares outbound_edges or lupopedia.history but DB has no matching rows.
        Requires numeric content_id and live MySQL (pymysql + db_config).
        """
        if not isinstance(header_data, dict):
            return
        headers = header_data.get("lupopedia.headers")
        if not isinstance(headers, dict):
            return
        cid = headers.get("content_id")
        if cid is None or str(cid).strip() == "":
            self.warnings.append(
                "%s: --check-db skipped: no content_id (import first)" % (file_path,)
            )
            return
        try:
            cid_int = int(cid)
        except (TypeError, ValueError):
            self.warnings.append(
                "%s: --check-db skipped: content_id not numeric (%r)" % (file_path, cid)
            )
            return

        try:
            import pymysql
            from pymysql.cursors import DictCursor
        except ImportError:
            self.warnings.append("%s: --check-db skipped: pymysql not installed" % (file_path,))
            return

        try:
            from lib.db_connection import get_connection_params
            from import_content import _load_table_prefix_from_config, _safe_sql_identifier
        except Exception as e:
            self.warnings.append("%s: --check-db skipped: config import failed (%s)" % (file_path, e))
            return

        prefix = _load_table_prefix_from_config()
        edges_table = _safe_sql_identifier(prefix + "edges")
        contents_table = _safe_sql_identifier(prefix + "contents")
        p = get_connection_params()
        conn = None
        try:
            conn = pymysql.connect(
                host=p["host"],
                user=p["user"],
                password=p["password"],
                database=p["database"],
                port=int(p.get("port") or 3306),
                charset="utf8mb4",
                cursorclass=DictCursor,
                autocommit=False,
            )
            with conn.cursor() as cur:
                cur.execute(
                    "SELECT revision_history FROM `%s` WHERE content_id=%%s AND is_deleted=0 LIMIT 1"
                    % contents_table,
                    (cid_int,),
                )
                row = cur.fetchone()
                db_rh = row.get("revision_history") if row else None
                cur.execute(
                    "SELECT COUNT(*) AS c FROM `%s` WHERE left_object_type=%%s AND left_object_id=%%s "
                    "AND edge_category=%%s AND is_deleted=0" % edges_table,
                    ("content", cid_int, "lupopedia_header"),
                )
                erow = cur.fetchone()
                edge_count = int(erow["c"]) if erow and erow.get("c") is not None else 0

            file_edges = header_data.get("lupopedia.edges")
            outbound = []
            if isinstance(file_edges, dict):
                oe = file_edges.get("outbound_edges")
                if isinstance(oe, list):
                    outbound = oe
            file_hist = header_data.get("lupopedia.history")

            if len(outbound) > 0 and edge_count == 0:
                self.warnings.append(
                    "%s: File has outbound_edges but DB has 0 rows in lupo_edges "
                    "(edge_category=lupopedia_header). Run: python scripts/import_content.py \"%s\""
                    % (file_path, file_path)
                )
            if file_hist is not None:
                empty_db_hist = db_rh is None
                if isinstance(db_rh, str) and not str(db_rh).strip():
                    empty_db_hist = True
                if isinstance(db_rh, (dict, list)) and not db_rh:
                    empty_db_hist = True
                if empty_db_hist:
                    self.warnings.append(
                        "%s: File has lupopedia.history but lupo_contents.revision_history is empty. "
                        "Run: python scripts/import_content.py \"%s\""
                        % (file_path, file_path)
                    )
        except Exception as e:
            self.warnings.append("%s: --check-db failed: %s" % (file_path, e))
        finally:
            if conn is not None:
                try:
                    conn.close()
                except Exception:
                    pass
    
    def print_results(self):
        """Print validation results"""
        if self.errors:
            print("ERRORS:")
            for error in self.errors:
                print("  [ERROR] %s" % (error,))

        if self.warnings:
            print("WARNINGS:")
            for warning in self.warnings:
                print("  [WARN] %s" % (warning,))

        if not self.errors and not self.warnings:
            print("OK: All validations passed")
        
        return len(self.errors) == 0

def main():
    parser = argparse.ArgumentParser(description="Validate LUPOPEDIA HEADERS in a markdown file.")
    parser.add_argument("file_path", help="Path to .md file")
    parser.add_argument(
        "--check-db",
        action="store_true",
        help="Compare edges/history on disk to MySQL for this content_id (warnings only)",
    )
    args = parser.parse_args()
    file_path = args.file_path

    if not os.path.exists(file_path):
        print("File not found: %s" % (file_path,))
        sys.exit(1)

    validator = LupopediaHeaderValidator()
    valid = validator.validate_file(file_path)

    if args.check_db and valid and validator._pending_header_data is not None:
        validator.check_db_sync(file_path, validator._pending_header_data)

    if not validator.print_results():
        sys.exit(1)
    else:
        sys.exit(0)

if __name__ == "__main__":
    main()
