#!/usr/bin/env python3
"""
Regenerate TOON files under docs/toons/ from live MySQL schema.
Output: docs/toons/{table}.toon.json
Uses INFORMATION_SCHEMA. No FK, no triggers. Schema-only (data: []).
"""
import os
import sys
import json

try:
    import mysql.connector
    from mysql.connector import Error
except ImportError:
    print("mysql.connector required: pip install mysql-connector-python", file=sys.stderr)
    sys.exit(1)

OUT_DIR = os.path.join(os.path.dirname(__file__), '..', 'docs', 'toons')


def get_db():
    host = os.getenv('DB_HOST', 'localhost')
    user = os.getenv('DB_USER', 'root')
    password = os.getenv('DB_PASS', '')
    database = os.getenv('DB_NAME', 'lupopedia')
    conn = mysql.connector.connect(
        host=host, user=user, password=password, database=database,
        charset='utf8mb4', collation='utf8mb4_unicode_ci'
    )
    return conn


def get_tables(cursor, db):
    cursor.execute("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = %s AND TABLE_TYPE = 'BASE TABLE' ORDER BY TABLE_NAME", (db,))
    return [r[0] for r in cursor.fetchall()]


def get_columns(cursor, db, table):
    cursor.execute("""
        SELECT COLUMN_NAME, COLUMN_TYPE, IS_NULLABLE, COLUMN_DEFAULT, EXTRA, COLUMN_COMMENT
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s
        ORDER BY ORDINAL_POSITION
    """, (db, table))
    return cursor.fetchall()


def get_pk(cursor, db, table):
    cursor.execute("""
        SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
        WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s AND CONSTRAINT_NAME = 'PRIMARY'
        ORDER BY ORDINAL_POSITION LIMIT 1
    """, (db, table))
    r = cursor.fetchone()
    return r[0] if r else None


def get_indexes(cursor, db, table):
    cursor.execute("""
        SELECT INDEX_NAME, GROUP_CONCAT(COLUMN_NAME ORDER BY SEQ_IN_INDEX) AS cols, NON_UNIQUE, INDEX_TYPE
        FROM INFORMATION_SCHEMA.STATISTICS
        WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s AND INDEX_NAME != 'PRIMARY'
        GROUP BY INDEX_NAME, NON_UNIQUE, INDEX_TYPE
        ORDER BY INDEX_NAME
    """, (db, table))
    out = []
    for row in cursor.fetchall():
        out.append({
            "index_name": row[0],
            "columns": (row[1] or "").split(","),
            "is_unique": row[2] == 0,
            "index_type": row[3] or "BTREE"
        })
    return out


def get_table_comment(cursor, db, table):
    cursor.execute("SELECT TABLE_COMMENT FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s", (db, table))
    r = cursor.fetchone()
    return (r[0] or "").strip()


def field_def(row):
    col, ctype, nullable, default, extra, comment = row
    s = "`" + col + "` " + ctype
    if nullable == 'NO':
        s += " NOT NULL"
    if default is not None:
        if isinstance(default, str) and default.upper() in ('CURRENT_TIMESTAMP', 'CURRENT_TIMESTAMP()'):
            s += " DEFAULT " + default
        elif isinstance(default, str):
            s += " DEFAULT '" + default.replace("'", "''") + "'"
        else:
            s += " DEFAULT " + str(default)
    if extra:
        s += " " + extra
    if comment:
        s += " COMMENT '" + comment.replace("'", "''") + "'"
    return s


def main():
    db_name = os.getenv('DB_NAME', 'lupopedia')
    os.makedirs(OUT_DIR, exist_ok=True)

    conn = get_db()
    cur = conn.cursor()

    cur.execute("SELECT DATABASE()")
    db = (cur.fetchone() or [None])[0] or db_name

    tables = get_tables(cur, db)
    tables_set = set(tables)

    for t in tables:
        cols = get_columns(cur, db, t)
        fields = [field_def(r) for r in cols]
        pk = get_pk(cur, db, t)
        indexes = get_indexes(cur, db, t)
        notes = get_table_comment(cur, db, t)

        obj = {
            "table_name": t,
            "fields": fields,
            "data": [],
            "indexes": indexes
        }
        if notes:
            obj["notes"] = notes
        if pk:
            obj["primary_key"] = {"column_name": pk, "expected_name": pk, "is_correct": True, "needs_rename": False}
        if t.startswith("lupo_"):
            obj["doctrine_metadata"] = {"no_foreign_keys": True, "no_triggers": True}
        obj["relationships"] = []

        path = os.path.join(OUT_DIR, t + ".toon.json")
        with open(path, 'w', encoding='utf-8') as f:
            json.dump(obj, f, indent=2, ensure_ascii=False)

    # Remove .toon.json for tables that no longer exist in the database
    for f in os.listdir(OUT_DIR):
        if f.endswith('.toon.json'):
            tbl = f[:-10]  # strip .toon.json
            if tbl not in tables_set:
                os.remove(os.path.join(OUT_DIR, f))

    cur.close()
    conn.close()
    print("TOON regeneration complete. Tables:", len(tables), "Output:", OUT_DIR)


if __name__ == '__main__':
    main()
