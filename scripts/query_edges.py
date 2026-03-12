#!/usr/bin/env python3
"""
Query live edges from lupo_edges by namespace (Synthesized Documentation Framework).

Usage:
  python scripts/query_edges.py [namespace]
  python scripts/query_edges.py lupopedia.code.logic
  python scripts/query_edges.py   # all edges (no namespace filter)

Namespace filters by context_scope. Output: JSON array of edge rows.
DB config: scripts/db_config.py (lupopedia-config.php).
"""

from __future__ import print_function

import json
import os
import sys

# Allow running from project root or from scripts/
SCRIPT_DIR = os.path.dirname(os.path.abspath(__file__))
if SCRIPT_DIR not in sys.path:
    sys.path.insert(0, SCRIPT_DIR)

try:
    from db_config import get_connection_params
except ImportError:
    get_connection_params = None

try:
    import pymysql
    from pymysql.cursors import DictCursor
except ImportError:
    pymysql = None
    DictCursor = None


def main():
    namespace = (sys.argv[1] if len(sys.argv) > 1 else "").strip()
    table_prefix = os.environ.get("LUPO_TABLE_PREFIX", "lupo_")
    edges_table = table_prefix + "edges"

    if get_connection_params is None:
        print(json.dumps({"error": "db_config.get_connection_params not found"}), file=sys.stderr)
        sys.exit(1)
    if pymysql is None or DictCursor is None:
        print(json.dumps({"error": "pymysql required (pip install pymysql)"}), file=sys.stderr)
        sys.exit(1)

    params = get_connection_params()
    conn = pymysql.connect(cursorclass=DictCursor, **params)
    try:
        cursor = conn.cursor()
        if namespace:
            sql = (
                "SELECT edge_id, left_object_type, left_object_id, right_object_type, right_object_id, "
                "edge_type, edge_category, context_scope, channel_id, weight_score, created_ymdhis, updated_ymdhis "
                "FROM " + edges_table + " WHERE is_deleted = 0 AND context_scope = %s ORDER BY edge_id"
            )
            cursor.execute(sql, (namespace,))
        else:
            sql = (
                "SELECT edge_id, left_object_type, left_object_id, right_object_type, right_object_id, "
                "edge_type, edge_category, context_scope, channel_id, weight_score, created_ymdhis, updated_ymdhis "
                "FROM " + edges_table + " WHERE is_deleted = 0 ORDER BY edge_id"
            )
            cursor.execute(sql)
        rows = cursor.fetchall()
        # Convert decimals/dates for JSON
        out = []
        for r in rows:
            row = dict(r)
            for k, v in row.items():
                if hasattr(v, "isoformat"):
                    row[k] = v.isoformat()
                elif hasattr(v, "__float__") and not isinstance(v, (int, float)):
                    row[k] = float(v)
            out.append(row)
        print(json.dumps(out, indent=2))
    finally:
        conn.close()


if __name__ == "__main__":
    main()
