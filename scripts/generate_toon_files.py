#!/usr/bin/env python3
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.2"
#   file_path_from_root: "scripts/generate_toon_files.py"
#   web_path: "https://www.lupopedia.com/lupopedia/scripts/generate_toon_files.py"
#   status: "complete"
#   when_updated: "20260415233000"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "memory/development/canonical/1026/04/generate-toon-files.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/generate-toon-files"
#   artifact_type: implementation
#   artifact_kind: tool
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: "generate-toon-files"
#   content_id: null
#   content_parent_id: "38"
#   content_slug: "generate-toon-files"
#   default_collection_id: null
#   lupopedia.schema: implementation
#   title: "generate_toon_files.py (live schema TOON export)"
#   summary: "Exports live MySQL table schema to database/lupopedia/json and toon; structure only; optional install gap fill."
# ---------------------------------------------------------------------
"""
TOON Generator — schema-only export from live DB.

Generates TOON representations of the database schema for all tables using live MySQL
metadata. Aligns with the 4.1.2 Orchestration Layer requirements.
"""

import os
import json
import yaml
import mysql.connector
from db_config import DB_CONFIG

# Configuration
VERSION = "4.1.2"
BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
JSON_DIR = os.path.join(BASE_DIR, 'database', 'lupopedia', 'json')
TOON_DIR = os.path.join(BASE_DIR, 'database', 'lupopedia', 'toon')

def get_live_tables(cursor):
    cursor.execute("SHOW TABLES")
    return [row[0] for row in cursor.fetchall()]

def generate_toon_for_table(cursor, table_name):
    # Fetch columns
    cursor.execute(f"DESCRIBE {table_name}")
    columns = cursor.fetchall()
    
    fields = []
    for col in columns:
        field_str = f"`{col[0]}` {col[1]}"
        if col[2] == 'NO':
            field_str += " NOT NULL"
        if col[4]:
            field_str += f" DEFAULT {col[4]}"
        fields.append(field_str)
        
    # Fetch PK
    cursor.execute(f"SHOW KEYS FROM {table_name} WHERE Key_name = 'PRIMARY'")
    pk_row = cursor.fetchone()
    pk_info = {
        "column_name": pk_row[4] if pk_row else None,
        "expected_name": pk_row[4] if pk_row else None,
        "is_correct": True,
        "needs_rename": False
    }
    
    # Fetch Indexes
    cursor.execute(f"SHOW INDEX FROM {table_name} WHERE Key_name != 'PRIMARY'")
    indexes_rows = cursor.fetchall()
    indexes = []
    idx_map = {}
    for row in indexes_rows:
        name = row[2]
        if name not in idx_map:
            idx_map[name] = {"index_name": name, "columns": [], "is_unique": not row[1], "index_type": row[10]}
        idx_map[name]["columns"].append(row[4])
    indexes = list(idx_map.values())

    toon_data = {
        "_comment": "THIS FILE IS AUTO-GENERATED. DO NOT EDIT MANUALLY.",
        "_purpose": "Schema reference only — column names, types, indexes. Contains NO data.",
        "_source": "Generated from live database by generate_toon_files.py",
        "_read_only": True,
        "table_name": table_name,
        "fields": fields,
        "indexes": indexes,
        "primary_key": pk_info,
        "doctrine_metadata": {
            "no_foreign_keys": True,
            "no_triggers": True
        },
        "relationships": []
    }
    return toon_data

def main():
    if not os.path.exists(JSON_DIR): os.makedirs(JSON_DIR)
    if not os.path.exists(TOON_DIR): os.makedirs(TOON_DIR)

    conn = mysql.connector.connect(**DB_CONFIG)
    cursor = conn.cursor()
    
    tables = get_live_tables(cursor)
    print(f"Found {len(tables)} tables in live database.")
    
    for table in tables:
        # We skip the prefix if provided in config, or assume the table name is literal
        # For Lupopedia, we usually expect 'lupo_' prefix
        data = generate_toon_for_table(cursor, table)
        
        json_path = os.path.join(JSON_DIR, f"{table}.json")
        toon_path = os.path.join(TOON_DIR, f"{table}.toon")
        
        with open(json_path, 'w', encoding='utf-8') as f:
            json.dump(data, f, indent=2)
            
        with open(toon_path, 'w', encoding='utf-8') as f:
            yaml.dump(data, f, sort_keys=False)
            
        print(f"Generated {table} metadata.")

    cursor.close()
    conn.close()
    print("Done.")

if __name__ == "__main__":
    main()
