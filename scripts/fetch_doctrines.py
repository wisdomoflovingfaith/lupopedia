# lupopedia.headers:
#   when_updated: "20260324175617"
#   file_path_from_root: "scripts/fetch_doctrines.py"
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


import sys
import os

_SCRIPT_DIR = os.path.dirname(os.path.abspath(__file__))
if _SCRIPT_DIR not in sys.path:
    sys.path.insert(0, _SCRIPT_DIR)
import pymysql
import json
from lib.db_connection import get_connection_params

def get_doctrines():
    params = get_connection_params()
    conn = pymysql.connect(**params)
    try:
        with conn.cursor() as cursor:
            sql = "SELECT dialog_message_id, created_ymdhis, message_text, metadata_json FROM lupo_dialog_messages WHERE channel_id = 0 AND from_actor_id = 10000 ORDER BY created_ymdhis"
            cursor.execute(sql)
            return cursor.fetchall()
    finally:
        conn.close()

rows = get_doctrines()
summary = []
for row in rows:
    meta = json.loads(row[3])
    summary.append({
        "id": row[0],
        "ts": row[1],
        "path": meta.get('original_path'),
        "hash": meta.get('file_hash')
    })

print(json.dumps(summary, indent=2))
