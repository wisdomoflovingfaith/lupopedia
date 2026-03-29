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
  python lupo-scripts/validate_lupopedia_headers.py <file_path>
  python lupo-scripts/validate_lupopedia_headers.py <file_path> --check-db
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

# Binding doctrine: lupo-rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md (aligned with validate_lupopedia_headers_universal.py)
REQUIRED_HEADER_FIELDS = (
    "lupopedia.schema",
    "file_path_from_root",
    "web_path",
    "federation_node_id",
    "last_modified_utc",
    "when_updated",
    "channel_id",
    "thread_id",
    "actor_id",
    "actor_name",
    "delegation_chain",
    "artifact_type",
    "artifact_kind",
    "purpose",
    "tags",
)
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
                "Import first: python lupo-scripts/import_content.py \"%s\""
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
        
        # Check 2: Must have proper YAML header structure
        if '---' not in content[3:]:
            self.errors.append(f"{file_path}: Missing closing --- for YAML header")
            valid = False
        else:
            # Extract YAML header
            header_end = content.find('---', 3)
            header_content = content[3:header_end]
            
            try:
                header_data = yaml.safe_load(header_content)
                self._pending_header_data = header_data if isinstance(header_data, dict) else None
                
                # Validate required header fields
                if 'lupopedia.headers' not in header_data:
                    self.errors.append(f"{file_path}: Missing lupopedia.headers section")
                    valid = False
                else:
                    headers = header_data['lupopedia.headers']
                    
                    # Check for deprecated fields
                    if 'version_when_written' in headers:
                        self.warnings.append(f"{file_path}: version_when_written is deprecated, use when_updated")
                    
                    if 'lupopedia.version' in headers:
                        self.warnings.append(f"{file_path}: lupopedia.version is deprecated in headers")
                    
                    # Required header keys (binding doctrine; content_id optional — linkage only)
                    for field in REQUIRED_HEADER_FIELDS:
                        if field not in headers or not _header_value_present(headers.get(field)):
                            self.errors.append(
                                "%s: Missing or empty required header field %r" % (file_path, field)
                            )
                            valid = False

                    tags = headers.get("tags")
                    if tags is not None and not isinstance(tags, list):
                        self.errors.append("%s: tags must be a YAML list" % (file_path,))
                        valid = False

                    fn = headers.get("federation_node_id")
                    if fn is not None and _header_value_present(fn):
                        fn_ok = False
                        if isinstance(fn, int) and not isinstance(fn, bool):
                            fn_ok = True
                        elif isinstance(fn, str) and str(fn).strip().isdigit():
                            fn_ok = True
                        if not fn_ok:
                            self.errors.append(
                                "%s: federation_node_id must be an integer" % (file_path,)
                            )
                            valid = False

                    tid = headers.get("thread_id")
                    if tid is not None and str(tid).strip():
                        if not THREAD_ID_RE.match(str(tid).strip()):
                            self.errors.append(
                                "%s: thread_id must match ^[a-z0-9][a-z0-9-]*$ (got %r)"
                                % (file_path, str(tid).strip())
                            )
                            valid = False

                    for ts_name in ("when_updated", "last_modified_utc"):
                        ts_val = headers.get(ts_name)
                        ts_s = str(ts_val).strip() if ts_val is not None else ""
                        if ts_s and not YMDHIS_RE.match(ts_s):
                            self.errors.append(
                                "%s: %s must be UTC YYYYMMDDHHIISS (14 digits), got %r"
                                % (file_path, ts_name, ts_val)
                            )
                            valid = False

                    # Check web_path format
                    if 'web_path' in headers:
                        web_path = headers['web_path']
                        if not web_path.startswith('http://www.lupopedia.com/lupopedia/'):
                            self.warnings.append(f"{file_path}: web_path should include /lupopedia/ subdirectory")

                    self._warn_missing_content_id(file_path, headers)
                    
                    # Check for proper footer if present
                    if 'lupopedia.footer' in header_data:
                        footer = header_data['lupopedia.footer']
                        
                        # Check for deprecated footer fields
                        if 'last_verified_by' in footer:
                            self.warnings.append(f"{file_path}: last_verified_by is deprecated, use verified_by structure")
                        
                        # Check required footer fields
                        if 'last_verified' in footer:
                            if 'verified_by' not in footer:
                                self.errors.append(f"{file_path}: footer has last_verified but missing verified_by structure")
                                valid = False
                            else:
                                verified_by = footer['verified_by']
                                required_verified_fields = ['identity_type', 'actor_id']
                                for field in required_verified_fields:
                                    if field not in verified_by:
                                        self.errors.append(f"{file_path}: missing verified_by.{field}")
                                        valid = False
                
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
            from db_config import get_connection_params
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
                    "(edge_category=lupopedia_header). Run: python lupo-scripts/import_content.py \"%s\""
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
                        "Run: python lupo-scripts/import_content.py \"%s\""
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
