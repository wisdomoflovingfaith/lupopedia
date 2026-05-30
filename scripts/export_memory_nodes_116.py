#!/usr/bin/env python3
"""
Export all lupo_memory_nodes rows for owner_actor_id = 116 to a single JSON file.
Output: database/lupopedia/memory_nodes/116/lupo_memory_nodes.json
"""
import json
import os
import sys
from pathlib import Path

_SCRIPT_DIR = os.path.dirname(os.path.abspath(__file__))
if _SCRIPT_DIR not in sys.path:
    sys.path.insert(0, _SCRIPT_DIR)

from lib.db_connection import get_connection_params

try:
    import pymysql
    from pymysql.cursors import DictCursor
except ImportError:
    print("pymysql not installed")
    exit(1)

def main():
    conn_params = get_connection_params()
    conn = pymysql.connect(**conn_params, cursorclass=DictCursor)
    try:
        with conn.cursor() as cursor:
            cursor.execute("SELECT * FROM lupo_memory_nodes WHERE owner_actor_id = 116")
            rows = cursor.fetchall()
    finally:
        conn.close()

    out_dir = Path("database/lupopedia/memory_nodes/116")
    out_dir.mkdir(parents=True, exist_ok=True)
    out_path = out_dir / "lupo_memory_nodes.json"
    with open(out_path, "w", encoding="utf-8") as f:
        json.dump(rows, f, indent=2, ensure_ascii=False)
    print(f"Exported {len(rows)} rows to {out_path}")

if __name__ == "__main__":
    main()
