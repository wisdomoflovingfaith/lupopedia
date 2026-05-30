# lupopedia.headers:
#   when_updated: "20260324175617"
#   file_path_from_root: "scripts/import_os_fixed.py"
#   questions_toon: null
#   channel_id: 42
#   actor_id: 102
#   actor_name: "cursor"
#   delegation_chain: "cursor:root"
#   artifact_type: "tooling"
#   artifact_kind: "script"
# lupopedia.footer:
#   last_verified: "20260324175617"
#   last_verified_by: "cursor"
#   last_verified_by_actor_id: 102

import os
import sys
import mysql.connector
from datetime import datetime

_SCRIPT_DIR = os.path.dirname(os.path.abspath(__file__))
if _SCRIPT_DIR not in sys.path:
    sys.path.insert(0, _SCRIPT_DIR)
from db_config import load_db_config

from lib.header_validation import parse_front_matter_header, validate_header

# --- CONFIG ---
DOCS_DIR = "../docs"
CHANNEL_ID = 0  # root/system kernel channel
FED_NODE = 1
ACTOR_ID = 1
TIMESTAMP = int(datetime.now().strftime("%Y%m%d%H%M%S"))

# --- DB CONNECTION (lupopedia-config.php via db_config) ---
_p = load_db_config()
db = mysql.connector.connect(
    host=_p["host"],
    user=_p["user"],
    password=_p["password"],
    database=_p["database"],
    port=int(_p.get("port", 3306)),
)
cursor = db.cursor()

# --- HELPERS ---
def insert_content(slug, title, body):
    sql = """
    INSERT INTO lupo_contents
    (federation_node_id, created_by_actor_id, slug, title, body,
     status, visibility, created_ymdhis, updated_ymdhis)
    VALUES (%s, %s, %s, %s, %s, %s, %s)
    """
    vals = (FED_NODE, ACTOR_ID, slug, title, body, 'published', 'public', TIMESTAMP, TIMESTAMP)
    cursor.execute(sql, vals)
    db.commit()
    return cursor.lastrowid

def insert_edge(content_id):
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
def main():
    for filename in os.listdir(DOCS_DIR):
        if filename.endswith('.md'):
            filepath = os.path.join(DOCS_DIR, filename)
            with open(filepath, 'r', encoding='utf-8') as f:
                content = f.read()
                parsed = parse_front_matter_header(content)
                if not parsed.get("valid"):
                    print("SKIP invalid header: %s -> %s" % (filename, {
                        "valid": False,
                        "errors": parsed.get("errors", [])
                    }))
                    continue
                validation = validate_header((parsed.get("header") or {}))
                if not validation.get("valid"):
                    print("SKIP invalid header: %s -> %s" % (filename, validation))
                    continue
                slug = filename[:-3]  # remove .md
                title = filename.replace('-', ' ').replace('_', ' ').title()
                content_id = insert_content(slug, title, content)
                insert_edge(content_id)
                print(f"Imported {filename} -> content_id {content_id}")

if __name__ == "__main__":
    main()
    cursor.close()
    db.close()

print("Done.")
