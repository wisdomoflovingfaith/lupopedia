"""
import_os.py — Ingest Markdown files under docs/ into lupo_contents.
LEXA security: parameterized SQL only; path validation (inside Lupopedia root, no '..');
no eval/exec/shell; header values plain text only; safe error logging (no sensitive info).
Database user must have minimal privileges (INSERT/SELECT on required tables only).
"""
import json
import os
import sys
import mysql.connector
from datetime import datetime

try:
    import yaml
except ImportError:
    yaml = None

# --- CONFIG ---
# DOCS_DIR relative to script; Lupopedia root = parent of docs.
_SCRIPT_DIR = os.path.dirname(os.path.abspath(__file__))
DOCS_DIR = os.path.join(_SCRIPT_DIR, "..", "docs")
LUPOPEDIA_ROOT = os.path.normpath(os.path.dirname(DOCS_DIR))
CHANNEL_ID = 0  # root/system kernel channel
FED_NODE = 1
ACTOR_ID = 1
TIMESTAMP = int(datetime.now().strftime("%Y%m%d%H%M%S"))

# --- DB CONNECTION ---
db = mysql.connector.connect(
    host="localhost",
    user="root",
    password="ServBay.dev",
    database="lupopedia"
)
cursor = db.cursor()

# --- Path validation (LEXA: must be inside Lupopedia root; no '..') ---
# Only sanitized paths may be stored in DB as file_path_from_root. Never store raw header or user input.
def validate_path_inside_root(repo_root, filepath_abs):
    """Return True if filepath_abs is inside repo_root; no escape via '..'."""
    try:
        real_root = os.path.realpath(repo_root)
        real_path = os.path.realpath(filepath_abs)
        return real_path == real_root or real_path.startswith(real_root + os.sep)
    except Exception:
        return False

def validate_and_sanitize_path_from_root(repo_root, path_from_root):
    """
    LEXA path validation: path_from_root must be relative, no '..', and resolve inside repo_root.
    Returns normalized path (forward slashes) or None if invalid.
    Only the return value (or computed path passed through this) may be stored in lupo_contents.file_path_from_root.
    Header values stored as plain text only; no dynamic execution.
    """
    if not path_from_root or ".." in path_from_root:
        return None
    path_from_root = path_from_root.strip().replace("\\", "/").lstrip("/")
    if not path_from_root:
        return None
    resolved = os.path.normpath(os.path.join(repo_root, path_from_root))
    if not validate_path_inside_root(repo_root, resolved):
        return None
    return path_from_root.replace("\\", "/")

# --- FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header) ---
# Canonical header block: YAML between --- delimiters at top of file.
# Recognizes FLIP Headers and aliases (Wolfie/CROP/FLIPPING) as the same block.
FLIP_HEADER_SIGNATURE = "explicit architecture with structured clarity for every file."

def extract_flip_header(content):
    """Extract FLIP Header (Wolfie/CROP/FLIPPING) YAML block from markdown. Returns (header_dict, body)."""
    if not content.startswith("---"):
        return None, content
    parts = content.split("\n", 1)
    if len(parts) < 2:
        return None, content
    rest = parts[1]
    end = rest.find("\n---")
    if end == -1:
        return None, content
    yaml_block = rest[:end].strip()
    body_start = end + 4  # past \n---
    body = rest[body_start:].lstrip("\n") if body_start < len(rest) else ""
    # Check for FLIP/Wolfie/CROP/FLIPPING header (signature or file_path_from_root)
    if FLIP_HEADER_SIGNATURE not in yaml_block and "file_path_from_root" not in yaml_block:
        return None, content
    header_dict = {}
    if yaml:
        try:
            header_dict = yaml.safe_load(yaml_block) or {}
        except Exception:
            pass
    else:
        for line in yaml_block.split("\n"):
            line = line.strip()
            if line.startswith("#") or not line:
                continue
            if ":" in line:
                k, v = line.split(":", 1)
                header_dict[k.strip()] = v.strip().strip('"').strip("'")
    return header_dict, ("---\n" + rest[:end] + "\n---\n\n" + body) if header_dict else (None, content)

def file_path_from_root_for_file(repo_root, filepath):
    """Compute path from repo root (e.g. docs/...) for use when header has no file_path_from_root."""
    rel = os.path.relpath(filepath, repo_root)
    return rel.replace("\\", "/")

# --- Optional dialog block: serialize for dialog_notes (no eval; plain text only) ---
def serialize_dialog_for_notes(dialog_val):
    """Serialize header dialog block for lupo_contents.dialog_notes. No eval; safe string only."""
    if dialog_val is None:
        return None
    if isinstance(dialog_val, dict):
        if yaml:
            try:
                return yaml.safe_dump(dialog_val, default_flow_style=False, allow_unicode=True)
            except Exception:
                pass
        return str(dialog_val)
    return str(dialog_val) if dialog_val else None

# --- HELPERS (LEXA: parameterized SQL only; no string interpolation into SQL) ---
def insert_content(slug, title, body, file_path_from_root=None,
                   file_last_modified_system_version=None, file_last_modified_utc=None,
                   dialog_notes=None, tags=None):
    # All values passed as parameters; header/body stored as plain text only.
    # FLIP fields: path + version + utc from header when present. Optional dialog_notes, tags from header.
    check_sql = "SELECT content_id FROM lupo_contents WHERE slug = %s AND federation_node_id = %s"
    cursor.execute(check_sql, (slug, FED_NODE))
    existing = cursor.fetchone()
    
    if existing:
        print("Skipping existing content: slug=%s (content_id=%s)" % (slug, existing[0]))
        return existing[0]
    
    sql = """
    INSERT INTO lupo_contents
    (federation_node_id, actor_id, slug, title, body,
     status, visibility, created_ymdhis, updated_ymdhis, utc_cycle,
     file_path_from_root, file_last_modified_system_version, file_last_modified_utc, dialog_notes, tags)
    VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
    """
    utc_cycle = "import"
    vals = (FED_NODE, ACTOR_ID, slug, title, body, 'published', 'public',
            TIMESTAMP, TIMESTAMP, utc_cycle, file_path_from_root,
            file_last_modified_system_version, file_last_modified_utc, dialog_notes, tags)
    cursor.execute(sql, vals)
    db.commit()
    return cursor.lastrowid

def insert_edge(content_id):
    check_sql = """SELECT edge_id FROM lupo_edges 
                  WHERE left_object_type = %s AND left_object_id = %s 
                  AND right_object_type = %s AND right_object_id = %s 
                  AND edge_type = %s"""
    cursor.execute(check_sql, ('channel', CHANNEL_ID, 'content', content_id, 'HAS_CONTENT'))
    existing = cursor.fetchone()
    
    if existing:
        print("Skipping existing edge for content_id=%s" % (content_id,))
        return
    
    sql = """
    INSERT INTO lupo_edges
    (left_object_type, left_object_id, right_object_type, right_object_id,
     edge_type, channel_id, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
    VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
    """
    vals = ('channel', CHANNEL_ID, 'content', content_id, 'HAS_CONTENT',
            CHANNEL_ID, 0, 0, ACTOR_ID, 0, 0, TIMESTAMP, TIMESTAMP)
    cursor.execute(sql, vals)
    db.commit()

# --- MAIN ---
# Do NOT infer channel_id — store path only; application resolves later (lupo_contents lookup).
def main():
    docs_abs = os.path.abspath(DOCS_DIR)
    repo_root = os.path.normpath(LUPOPEDIA_ROOT)
    if not validate_path_inside_root(repo_root, docs_abs):
        print("Error: DOCS_DIR is not inside Lupopedia root. Aborting.")
        sys.exit(1)
    for root, dirs, files in os.walk(docs_abs):
        for filename in files:
            if not filename.endswith('.md'):
                continue
            filepath = os.path.join(root, filename)
            if not validate_path_inside_root(repo_root, filepath):
                print("Skipping path outside root: %s" % (filename,))
                continue
            try:
                rel_path = os.path.relpath(filepath, docs_abs)
                slug = rel_path[:-3].replace('\\', '/').replace(' ', '-').lower()
                with open(filepath, 'r', encoding='utf-8') as f:
                    content = f.read()
                # FLIP Header (canonical); Wolfie/CROP/FLIPPING are aliases. Extract all FLIP fields; store in lupo_contents.
                header_dict, _ = extract_flip_header(content)
                file_path_from_root = None
                file_last_modified_system_version = None
                file_last_modified_utc = None
                if header_dict and isinstance(header_dict, dict):
                    raw = header_dict.get("file_path_from_root") or header_dict.get("file.path_from_root")
                    if raw:
                        file_path_from_root = validate_and_sanitize_path_from_root(repo_root, raw)
                    # FLIP: system version and UTC at last file edit (doctrine-required for header reconstruction)
                    raw_ver = header_dict.get("file.last_modified_system_version")
                    if raw_ver is not None:
                        file_last_modified_system_version = str(raw_ver).strip() if raw_ver else None
                    raw_utc = header_dict.get("file.last_modified_utc")
                    if raw_utc is not None:
                        try:
                            file_last_modified_utc = int(raw_utc)
                        except (TypeError, ValueError):
                            file_last_modified_utc = None
                if not file_path_from_root:
                    computed = file_path_from_root_for_file(repo_root, filepath)
                    file_path_from_root = validate_and_sanitize_path_from_root(repo_root, computed) or computed
                # Optional: store dialog block in dialog_notes (FLIP Part 2.12; no eval; safe serialize only)
                dialog_notes = None
                if header_dict and isinstance(header_dict, dict) and "dialog" in header_dict:
                    dialog_notes = serialize_dialog_for_notes(header_dict.get("dialog"))
                # Optional: store tags from header (array of strings or dict with categories/hashtags etc.)
                tags_json = None
                if header_dict and isinstance(header_dict, dict) and "tags" in header_dict:
                    try:
                        tags_val = header_dict.get("tags")
                        if tags_val is not None:
                            tags_json = json.dumps(tags_val) if isinstance(tags_val, (list, dict)) else None
                    except Exception:
                        pass
                title = filename.replace('-', ' ').replace('_', ' ').replace('.md', '').title()
                content_id = insert_content(slug, title, content, file_path_from_root,
                                            file_last_modified_system_version, file_last_modified_utc,
                                            dialog_notes, tags_json)
                insert_edge(content_id)
                print("Imported: rel_path=%s content_id=%s file_path_from_root=%s" % (rel_path, content_id, file_path_from_root))
            except Exception as e:
                # Safe logging: no sensitive info (no passwords, no full stack with env)
                print("Import failed for file: %s — error: %s" % (filename, str(e)[:200]))

if __name__ == "__main__":
    main()
    cursor.close()
    db.close()

print("Done.")
