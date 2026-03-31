import argparse
import re
import os
import sys
from datetime import datetime, timezone

# Allow `from db_config import ...` when run as `python lupo-scripts/this_script.py`
_SCRIPTS_DIR = os.path.dirname(os.path.abspath(__file__))
if _SCRIPTS_DIR not in sys.path:
    sys.path.insert(0, _SCRIPTS_DIR)

try:
    import yaml
except ImportError:
    yaml = None  # type: ignore

# Canonical staleness cutoff (YYYYMMDD)
CUTOFF_DAY = 20260301  # 2026-03-01
CUTOFF_14 = int(f"{CUTOFF_DAY}000000")

# Core required keys under lupopedia.headers (LUPOPEDIA_HEADERS_DOCTRINE.md — Required Header Sections / fields table)
REQUIRED_HEADER_KEYS = (
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

# thread_id: lowercase, hyphens (and digits); e.g. headers-doctrine, 4.0.89-planning
THREAD_ID_PATTERN = re.compile(r"^[a-z0-9][a-z0-9-]*$")

# UTC YmdHis in headers (string or int from YAML)
YMDHIS_PATTERN = re.compile(r"^\d{14}$")

# Canonical schema taxonomy as defined by WOLFIE
VALID_SCHEMA_VALUES = [
    'doctrine', 'rule', 'philosophy', 'plan', 'todo', 'changelog',
    'directive', 'design', 'review', 'report', 'implementation',
    'script', 'class', 'index', 'thread', 'broadcast', 'alias',
]

def get_current_timestamp():
    """Get current UTC timestamp in YYYYMMDDHHIISS format"""
    now = datetime.now(timezone.utc)
    return now.strftime("%Y%m%d%H%M%S")

def validate_schema(schema, file_path):
    """Validate schema against canonical taxonomy"""
    if schema not in VALID_SCHEMA_VALUES:
        print(f"[WARN]  {file_path}: '{schema}' is not a standard schema value")
        print(f"   Expected one of: {', '.join(VALID_SCHEMA_VALUES)}")
        return False
    return True

# Cross-field dependency map as defined by LUPOPEDIA_HEADERS_DOCTRINE
DEPENDENCY_MAP = {
    'doctrine': {
        'artifact_type': ['doctrine'],
        'artifact_kind': ['database', 'documentation', 'rule']
    },
    'rule': {
        'artifact_type': ['rule'],
        'artifact_kind': ['rule']
    },
    'philosophy': {
        'artifact_type': ['manifesto'],
        'artifact_kind': ['philosophy']
    },
    'plan': {
        'artifact_type': ['plan'],
        'artifact_kind': ['plan']
    },
    'todo': {
        'artifact_type': ['todo'],
        'artifact_kind': ['task']
    },
    'changelog': {
        'artifact_type': ['changelog'],
        'artifact_kind': ['version_specific']
    },
    'directive': {
        'artifact_type': ['directive'],
        'artifact_kind': ['execution']
    },
    'design': {
        'artifact_type': ['design'],
        'artifact_kind': ['architecture']
    },
    'review': {
        'artifact_type': ['review'],
        'artifact_kind': ['audit']
    },
    'report': {
        'artifact_type': ['report'],
        'artifact_kind': ['status']
    },
    'implementation': {
        'artifact_type': ['implementation'],
        'artifact_kind': ['code']
    },
    'script': {
        'artifact_type': ['script'],
        'artifact_kind': ['utility']
    },
    'class': {
        'artifact_type': ['class'],
        'artifact_kind': ['code']
    },
    'index': {
        'artifact_type': ['index'],
        'artifact_kind': ['index']
    },
    'thread': {
        'artifact_type': ['thread'],
        'artifact_kind': ['coordination']
    },
    'broadcast': {
        'artifact_type': ['broadcast'],
        'artifact_kind': ['coordination']
    },
    'alias': {
        'artifact_type': ['documentation'],
        'artifact_kind': ['documentation']
    },
}

def validate_cross_fields(schema, artifact_type, artifact_kind, file_path):
    """Validate cross-field dependencies"""
    if schema in DEPENDENCY_MAP:
        deps = DEPENDENCY_MAP[schema]
        if artifact_type not in deps['artifact_type']:
            print(f"[WARN]  {file_path}: schema '{schema}' requires artifact_type to be one of: {', '.join(deps['artifact_type'])}")
            print(f"   Found: '{artifact_type}'")
            return False
        if artifact_kind not in deps['artifact_kind']:
            print(f"[WARN]  {file_path}: schema '{schema}' requires artifact_kind to be one of: {', '.join(deps['artifact_kind'])}")
            print(f"   Found: '{artifact_kind}'")
            return False
    return True


def parse_front_matter_yaml(content):
    """
    Parse first YAML front matter block (between --- lines).
    Returns (data_dict, None) on success, (None, error_message) on failure.
    """
    if yaml is None:
        return None, "PyYAML is not installed"
    if not content.startswith("---\n"):
        return None, "Front matter must start with ---"
    end = content.find("\n---\n", 4)
    if end < 0:
        return None, "Missing closing --- for YAML header"
    block = content[4:end]
    try:
        data = yaml.safe_load(block)
    except Exception as e:
        return None, "YAML parse failed: %s" % (e,)
    if not isinstance(data, dict):
        return None, "Front matter must be a YAML mapping"
    return data, None


def _header_value_present(val):
    if val is None:
        return False
    if isinstance(val, str) and not val.strip():
        return False
    if isinstance(val, (list, dict)) and len(val) == 0:
        return False
    return True


def validate_required_header_fields(hdr, file_path):
    """Ensure doctrine-required keys exist under lupopedia.headers."""
    if not isinstance(hdr, dict):
        print("[ERROR] %s: lupopedia.headers must be a mapping" % (file_path,))
        return False
    for key in REQUIRED_HEADER_KEYS:
        if key not in hdr or not _header_value_present(hdr.get(key)):
            print("[ERROR] %s: missing or empty required header field %r" % (file_path, key))
            return False
    tags = hdr.get("tags")
    if isinstance(tags, list) and len(tags) == 0:
        print("[ERROR] %s: tags must be a non-empty list" % (file_path,))
        return False
    return True


def validate_thread_id_format(thread_id, file_path):
    """thread_id: lowercase letters, digits, hyphens (doctrine)."""
    if thread_id is None:
        print("[ERROR] %s: thread_id missing" % (file_path,))
        return False
    tid = str(thread_id).strip()
    if not THREAD_ID_PATTERN.match(tid):
        print(
            "[ERROR] %s: thread_id must be lowercase [a-z0-9] with hyphens only, "
            "matching ^[a-z0-9][a-z0-9-]*$ (got %r)"
            % (file_path, tid)
        )
        return False
    return True


def validate_ymdhis_pair(when_updated, last_modified_utc, file_path):
    """when_updated and last_modified_utc must be 14-digit UTC strings (or int coerced)."""
    for name, val in (("when_updated", when_updated), ("last_modified_utc", last_modified_utc)):
        s = str(val).strip() if val is not None else ""
        if not YMDHIS_PATTERN.match(s):
            print(
                "[ERROR] %s: %s must be UTC YYYYMMDDHHIISS (14 digits), got %r"
                % (file_path, name, val)
            )
            return False
    return True


def check_db_sync_universal(file_path, header_data):
    """
    Warn if file declares outbound_edges or lupopedia.history but DB has no matching rows.
    Optionally warn if content_id row's file_path_from_root differs from header.
    """
    if not isinstance(header_data, dict):
        print("[WARN] %s: --check-db skipped: could not use parsed header" % (file_path,))
        return
    headers = header_data.get("lupopedia.headers")
    if not isinstance(headers, dict):
        print("[WARN] %s: --check-db skipped: no lupopedia.headers mapping" % (file_path,))
        return
    cid = headers.get("content_id")
    if cid is None or str(cid).strip() == "":
        print("[WARN] %s: --check-db skipped: no content_id (import first)" % (file_path,))
        return
    try:
        cid_int = int(cid)
    except (TypeError, ValueError):
        print("[WARN] %s: --check-db skipped: content_id not numeric (%r)" % (file_path, cid))
        return

    try:
        import pymysql
        from pymysql.cursors import DictCursor
    except ImportError:
        print("[WARN] %s: --check-db skipped: pymysql not installed" % (file_path,))
        return

    try:
        from db_config import get_connection_params
        from import_content import _load_table_prefix_from_config, _safe_sql_identifier
    except Exception as e:
        print("[WARN] %s: --check-db skipped: config import failed (%s)" % (file_path, e))
        return

    def _norm_path(p):
        if p is None:
            return ""
        return str(p).strip().replace("\\", "/").lstrip("/")

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
                "SELECT file_path_from_root, revision_history FROM `%s` WHERE content_id=%%s AND is_deleted=0 LIMIT 1"
                % contents_table,
                (cid_int,),
            )
            row = cur.fetchone()
            if not row:
                print(
                    "[WARN] %s: content_id %s not found in DB (is_deleted=0). Import may be stale."
                    % (file_path, cid_int)
                )
                return
            db_path = _norm_path(row.get("file_path_from_root"))
            header_path = _norm_path(headers.get("file_path_from_root"))
            if db_path and header_path and db_path != header_path:
                print(
                    "[WARN] %s: file_path_from_root in file (%r) != DB lupo_contents (%r) for content_id %s"
                    % (file_path, header_path, db_path, cid_int)
                )
            db_rh = row.get("revision_history")
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
            print(
                "[WARN] %s: File has outbound_edges but DB has 0 lupo_edges "
                "(edge_category=lupopedia_header). Run: python lupo-scripts/import_content.py %r"
                % (file_path, file_path)
            )
        if file_hist is not None:
            empty_db_hist = db_rh is None
            if isinstance(db_rh, str) and not str(db_rh).strip():
                empty_db_hist = True
            if isinstance(db_rh, (dict, list)) and not db_rh:
                empty_db_hist = True
            if empty_db_hist:
                print(
                    "[WARN] %s: File has lupopedia.history but lupo_contents.revision_history is empty. "
                    "Run: python lupo-scripts/import_content.py %r"
                    % (file_path, file_path)
                )
    except Exception as e:
        print("[WARN] %s: --check-db failed: %s" % (file_path, e))
    finally:
        if conn is not None:
            try:
                conn.close()
            except Exception:
                pass


def _find_lupopedia_repo_root(start_file):
    """
    Walk upward from the markdown file until a directory contains both
    lupo-scripts and lupo-includes (repo root). Edge ``to`` paths are relative to that root.
    """
    d = os.path.dirname(os.path.abspath(start_file))
    markers = ("lupo-scripts", "lupo-includes")
    for _ in range(40):
        if all(os.path.isdir(os.path.join(d, m)) for m in markers):
            return d
        parent = os.path.dirname(d)
        if parent == d:
            break
        d = parent
    return os.path.dirname(os.path.abspath(start_file))


def validate_edge_targets(file_path, edges):
    """
    Validate edge file targets on disk.
    ``edges`` may be the parsed ``lupopedia.edges`` dict (preferred) or a raw YAML
    fragment string (legacy) under ``lupopedia.edges``.
    """
    if not edges:
        return True

    if yaml is None:
        print("[WARN] %s: Skipping edge YAML parse (PyYAML unavailable)" % (file_path,))
        return True

    outbound = []
    try:
        if isinstance(edges, dict):
            ob = edges.get("outbound_edges")
            if isinstance(ob, list):
                outbound = ob
        else:
            edge_data = yaml.safe_load("lupopedia.edges:\n" + str(edges))
            if isinstance(edge_data, dict):
                root = edge_data.get("lupopedia.edges")
                if isinstance(root, dict):
                    ob = root.get("outbound_edges")
                    if isinstance(ob, list):
                        outbound = ob
    except Exception as e:
        print("[WARN] %s: Could not parse edges: %s" % (file_path, e))
        return True

    repo_root = _find_lupopedia_repo_root(file_path)
    for edge in outbound:
        if not isinstance(edge, dict):
            continue
        target_path = edge.get("to")
        if not target_path:
            continue
        tp = str(target_path).strip().replace("\\", "/")
        full_path = os.path.normpath(os.path.join(repo_root, tp))
        if not os.path.exists(full_path):
            print("[WARN] %s: Edge target not found: %s" % (file_path, tp))
            print("   Expected at: %s" % (full_path,))

    return True

def validate_history(history_block, file_path):
    """
    Validate history block structure.

    Parsed YAML from ``lupopedia.history:\\n  - event_id: ...`` is a dict with
    key ``lupopedia.history`` mapping to a list of events (not a nested ``history`` key).
    Also accept legacy shape ``{'history': [ ... ]}`` if present.
    """
    if not isinstance(history_block, dict):
        print("[ERROR] %s: lupopedia.history parse yielded non-dict" % (file_path,))
        return False

    events = history_block.get("lupopedia.history")
    if events is None:
        events = history_block.get("history", [])
    if events is None:
        events = []
    if not isinstance(events, list):
        print("[ERROR] %s: lupopedia.history must be a YAML list of events" % (file_path,))
        return False

    prev_event_id = 0

    for event in events:
        if not isinstance(event, dict):
            print("[WARN] %s: history entry is not a mapping, skipped" % (file_path,))
            continue
        required = (
            "event_id",
            "event_type",
            "event_date",
            "actor_id",
            "actor_name",
            "description",
        )
        for field in required:
            if field not in event:
                print("[ERROR] %s: History event missing '%s'" % (file_path, field))
                return False

        try:
            eid = int(event["event_id"])
        except (TypeError, ValueError):
            print("[ERROR] %s: History event_id must be integer-like" % (file_path,))
            return False

        if eid != prev_event_id + 1:
            print(
                "[WARN] %s: Event ID sequence gap: expected %s, got %s"
                % (file_path, prev_event_id + 1, eid)
            )

        prev_event_id = eid

        et = event.get("event_type")
        if et == "review":
            if "findings" not in event and "resolution" not in event:
                print("[WARN] %s: Review event should have findings or resolution" % (file_path,))

        if et == "audit":
            if "result" not in event:
                print("[WARN] %s: Audit event missing 'result'" % (file_path,))

    return True

def check_staleness(file_path, content):
    """Check if file is stale and warn but don't fail. Accepts 8 or 14 digits."""
    match = re.search(r'last_verified:\s*"?(\d{8,14})"?', content)
    if match:
        lv = match.group(1)
        if len(lv) == 8:
            lv_cmp = int(lv + "000000")
        elif len(lv) == 14:
            lv_cmp = int(lv)
        else:
            print(f"[WARN]  {file_path}: last_verified has invalid length: {lv}")
            return True
        if lv_cmp < CUTOFF_14:
            print(f"[WARN]  {file_path}: Stale header (last_verified < {CUTOFF_DAY})")
            print(f"   Run regenerate_headers_for_stale_files.py to update")
        if len(lv) == 14:
            print(f"[WARN]  {file_path}: last_verified is 14 digits, should be normalized to 8 digits (YYYYMMDD)")
    return True  # Don't fail validation, just warn

def validate_federation_node_id(file_path, fed_node):
    """Validate federation_node_id based on file location"""
    
    # Check file location rules
    if file_path.startswith('lupo-docs/versions/'):
        # Version docs should be node 0 or 1, not 2+
        if fed_node >= 2:
            print(f"[WARN]  {file_path}: Version docs should use node 0 or 1, not {fed_node}")
            # Not a hard error, but a warning
            return True
        
    if file_path.startswith('lupo-docs/doctrine/'):
        if fed_node >= 2:
            print(f"[WARN]  {file_path}: Doctrine should use node 0 or 1, not {fed_node}")
            return True
            
    if file_path.startswith('lupo-rules/root/'):
        if fed_node >= 2:
            print(f"[WARN]  {file_path}: Root rules should use node 0 or 1, not {fed_node}")
            return True
    
    # External research artifacts in lupo-content/federation_node_id/ are fine
    if file_path.startswith('lupo-content/federation_node_id/'):
        expected_node = int(file_path.split('/')[2])
        if fed_node != expected_node:
            print(f"[ERROR] {file_path}: federation_node_id {fed_node} does not match path {expected_node}")
            return False
    
    return True

def validate_web_path(web_path, fed_node, file_path):
    """Validate web_path based on federation node"""
    path_rules = file_path.replace("\\", "/")
    # For external nodes (2+), web_path must be a valid URL pointing to canonical source
    if fed_node >= 2:
        if not web_path.startswith(("http://", "https://")):
            print(
                "[ERROR] %s: External federation node %s requires web_path to be a valid URL (http:// or https://)"
                % (file_path, fed_node)
            )
            print("   Found: %s" % (web_path,))
            return False, "External web_path must be a URL"

        prefix = "lupo-content/federation_node_id/%s/" % fed_node
        if not path_rules.startswith(prefix):
            print(
                "[WARN]  %s: External federation node %s should be in lupo-content/federation_node_id/%s/"
                % (file_path, fed_node, fed_node)
            )
            return False, "Wrong federation directory"
    
    # For internal nodes (0, 1), web_path can be relative or absolute
    return True, "Valid web_path"

def validate_yaml_file(file_path, content):
    """
    Validate YAML front matter for LUPOPEDIA HEADERS.

    Returns (success, parsed_front_matter_dict_or_None).
    """
    lines = content.split("\n")
    non_empty_lines = [line for line in lines if line.strip()]
    if not non_empty_lines:
        print("[ERROR] %s: Empty file" % (file_path,))
        return False, None

    first_line = non_empty_lines[0].strip()
    if first_line != "---":
        print("[ERROR] %s: YAML file must start with '---' as first line" % (file_path,))
        return False, None

    if "lupopedia.headers:" not in content:
        print("[ERROR] %s: Missing lupopedia.headers block" % (file_path,))
        return False, None

    if content.count("\n---\n") < 1 and not content.endswith("---\n"):
        print("[ERROR] %s: Missing YAML closing delimiter '---'" % (file_path,))
        return False, None

    parsed, perr = parse_front_matter_yaml(content)
    if perr:
        print("[ERROR] %s: %s" % (file_path, perr))
        return False, None

    hdr = parsed.get("lupopedia.headers")
    if not isinstance(hdr, dict):
        print("[ERROR] %s: lupopedia.headers must be a mapping" % (file_path,))
        return False, None

    if not validate_required_header_fields(hdr, file_path):
        return False, None
    if not validate_thread_id_format(hdr.get("thread_id"), file_path):
        return False, None
    if not validate_ymdhis_pair(hdr.get("when_updated"), hdr.get("last_modified_utc"), file_path):
        return False, None

    schema = str(hdr.get("lupopedia.schema", "")).strip()
    if not validate_schema(schema, file_path):
        return False, None

    artifact_type = str(hdr.get("artifact_type", "")).strip()
    artifact_kind = str(hdr.get("artifact_kind", "")).strip()
    if not validate_cross_fields(schema, artifact_type, artifact_kind, file_path):
        return False, None

    check_staleness(file_path, content)

    web_path = hdr.get("web_path")
    if web_path is None or (isinstance(web_path, str) and not str(web_path).strip()):
        print("[ERROR] %s: Missing or empty web_path" % (file_path,))
        return False, None
    web_path = str(web_path).strip()

    try:
        fed_node = int(hdr["federation_node_id"])
    except (KeyError, TypeError, ValueError):
        print("[ERROR] %s: federation_node_id must be an integer" % (file_path,))
        return False, None

    is_external = web_path.startswith(("http://", "https://"))
    is_core_site = is_external and "lupopedia.com" in web_path

    is_internal = False
    if fed_node == 0 and is_core_site:
        is_internal = True
    elif fed_node == 1 and not is_external:
        is_internal = True
    elif fed_node >= 2:
        is_internal = False
    else:
        if is_core_site:
            is_internal = True
        elif not is_external:
            is_internal = True
        else:
            is_internal = False

    if is_internal:
        valid, error = validate_web_path(web_path, fed_node, file_path)
        if not valid:
            print("[ERROR] %s: %s" % (file_path, error))
            return False, None
    else:
        if fed_node < 2:
            print(
                "[ERROR] %s: federation_node_id must be >= 2 for external web_path (got %s)"
                % (file_path, fed_node)
            )
            return False, None
        valid, error = validate_web_path(web_path, fed_node, file_path)
        if not valid:
            print("[ERROR] %s: %s" % (file_path, error))
            return False, None
        expected_path = "lupo-content/federation_node_id/%s/" % fed_node
        path_rules = file_path.replace("\\", "/")
        if not path_rules.startswith(expected_path):
            print("[WARN] %s: External research files should be in %s" % (file_path, expected_path))

    path_rules = file_path.replace("\\", "/")
    if not validate_federation_node_id(path_rules, fed_node):
        return False, None

    deprecated_fields = ["lupopedia.version", "system_version"]
    for field in deprecated_fields:
        if re.search(re.escape(field) + r":\s*", content):
            print("[ERROR] %s: Deprecated field %r found. Remove it." % (file_path, field))
            return False, None

    init_section_end = content.find("---\n")
    if init_section_end > 0:
        content_to_check = content[init_section_end + 4 :]
    else:
        content_to_check = content
    if re.search(r'^\s*version:\s*"4\.', content_to_check, re.MULTILINE):
        print("[ERROR] %s: Hardcoded version string found. Use when_updated instead." % (file_path,))
        return False, None

    cid = hdr.get("content_id")
    if cid is not None and str(cid).strip() != "":
        cs = str(cid).strip()
        if not cs.isdigit():
            print("[ERROR] %s: content_id must be numeric. Found: %s" % (file_path, cid))
            return False, None
        print("[OK] %s: content_id found (%s)" % (file_path, cs))
    else:
        print("[INFO] %s: No content_id (optional field)" % (file_path,))

    eb = parsed.get("lupopedia.edges")
    if eb is not None:
        if not isinstance(eb, dict):
            print("[ERROR] %s: lupopedia.edges must be a mapping" % (file_path,))
            return False, None
        if not validate_edge_targets(file_path, eb):
            return False, None

    if "lupopedia.history" in parsed:
        if not validate_history(parsed, file_path):
            return False, None

    return True, parsed


if __name__ == "__main__":
    parser = argparse.ArgumentParser(
        description="Validate LUPOPEDIA HEADERS (doctrine-aligned checks, optional DB drift warnings)."
    )
    parser.add_argument("file_path", help="Path to .md file")
    parser.add_argument(
        "--check-db",
        action="store_true",
        help="After validation, warn if outbound_edges/history disagree with MySQL for content_id",
    )
    args = parser.parse_args()
    file_path = args.file_path

    if not os.path.exists(file_path):
        print("[ERROR] File not found: %s" % (file_path,))
        sys.exit(1)

    try:
        with open(file_path, "r", encoding="utf-8-sig") as f:
            content = f.read()
    except Exception as e:
        print("[ERROR] Error reading file %s: %s" % (file_path, e))
        sys.exit(1)

    content = content.replace("\r\n", "\n")

    ok, parsed = validate_yaml_file(file_path, content)
    if ok and args.check_db and parsed is not None:
        check_db_sync_universal(file_path, parsed)

    sys.exit(0 if ok else 1)
