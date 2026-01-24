#!/usr/bin/env python3
"""
TOON Generator (SHOW TABLES/COLUMNS)

Generates JSON TOON files for all tables using live MySQL schema introspection
via SHOW TABLES / SHOW FULL COLUMNS / SHOW INDEX. Output files are written to:

  docs/toons/<table_name>.toon.json
"""

import json
import os
import sys
from typing import Any, Dict, List, Optional

import mysql.connector
from mysql.connector import Error


STRING_TYPES = {
    "char",
    "varchar",
    "text",
    "tinytext",
    "mediumtext",
    "longtext",
    "enum",
    "set",
}


def get_db_connection():
    host = os.getenv("DB_HOST", "localhost")
    user = os.getenv("DB_USER", "root")
    password = os.getenv("DB_PASS", "ServBay.dev")
    database = os.getenv("DB_NAME", "lupopedia")

    if not database:
        print("Error: DB_NAME is required.", file=sys.stderr)
        sys.exit(1)

    try:
        return mysql.connector.connect(
            host=host,
            user=user,
            password=password,
            database=database,
            charset="utf8mb4",
            collation="utf8mb4_unicode_ci",
        )
    except Error as exc:
        print(f"Database connection error: {exc}", file=sys.stderr)
        print(f"Attempted connection: {user}@{host}/{database}", file=sys.stderr)
        sys.exit(1)


def fetch_tables(cursor) -> List[str]:
    cursor.execute("SHOW TABLES")
    rows = cursor.fetchall()
    if rows and isinstance(rows[0], dict):
        return [list(row.values())[0] for row in rows]
    return [row[0] for row in rows]


def _quote_default(value: Any, data_type: str) -> str:
    if value is None:
        return ""
    if isinstance(value, bytes):
        try:
            value = value.decode("utf-8")
        except UnicodeDecodeError:
            value = value.hex()
    if isinstance(value, str) and value.upper() == "CURRENT_TIMESTAMP":
        return " DEFAULT CURRENT_TIMESTAMP"
    if data_type in STRING_TYPES:
        escaped = str(value).replace("\\", "\\\\").replace("'", "''")
        return f" DEFAULT '{escaped}'"
    return f" DEFAULT {value}"


def fetch_columns(cursor, table_name: str) -> List[str]:
    cursor.execute(f"SHOW FULL COLUMNS FROM `{table_name}`")
    rows = cursor.fetchall()
    columns = []
    for row in rows:
        if isinstance(row, dict):
            name = row["Field"]
            col_type = row["Type"]
            is_nullable = row["Null"] == "YES"
            default = row.get("Default")
            extra = row.get("Extra") or ""
            comment = row.get("Comment") or ""
        else:
            name, col_type, null_flag, default, extra, _, comment = row
            is_nullable = null_flag == "YES"

        parts = [f"`{name}`", col_type]
        if not is_nullable:
            parts.append("NOT NULL")
        default_clause = _quote_default(default, col_type.split("(")[0].lower())
        if default_clause:
            parts.append(default_clause.strip())
        if extra:
            parts.append(extra)
        if comment:
            escaped_comment = str(comment).replace("\\", "\\\\").replace("'", "''")
            parts.append(f"COMMENT '{escaped_comment}'")
        columns.append(" ".join(parts))
    return columns


def fetch_primary_key(cursor, table_name: str) -> Optional[str]:
    cursor.execute(f"SHOW INDEX FROM `{table_name}` WHERE Key_name = 'PRIMARY'")
    rows = cursor.fetchall()
    if not rows:
        return None
    if isinstance(rows[0], dict):
        return rows[0]["Column_name"]
    return rows[0][4]


def fetch_indexes(cursor, table_name: str) -> List[Dict[str, Any]]:
    cursor.execute(f"SHOW INDEX FROM `{table_name}`")
    rows = cursor.fetchall()
    indexes: Dict[str, Dict[str, Any]] = {}
    for row in rows:
        if isinstance(row, dict):
            key_name = row["Key_name"]
            if key_name == "PRIMARY":
                continue
            column = row["Column_name"]
            seq = row["Seq_in_index"]
            non_unique = row["Non_unique"]
            index_type = row.get("Index_type") or "BTREE"
        else:
            key_name = row[2]
            if key_name == "PRIMARY":
                continue
            column = row[4]
            seq = row[3]
            non_unique = row[1]
            index_type = row[10] if len(row) > 10 else "BTREE"

        entry = indexes.setdefault(
            key_name,
            {"columns": {}, "is_unique": non_unique == 0, "index_type": index_type},
        )
        entry["columns"][seq] = column

    result = []
    for name, meta in indexes.items():
        ordered_columns = [meta["columns"][i] for i in sorted(meta["columns"].keys())]
        result.append(
            {
                "index_name": name,
                "columns": ordered_columns,
                "is_unique": bool(meta["is_unique"]),
                "index_type": meta["index_type"],
            }
        )
    return result


def build_primary_key(column_name: Optional[str]) -> Optional[Dict[str, Any]]:
    if not column_name:
        return None
    return {
        "column_name": column_name,
        "expected_name": column_name,
        "is_correct": True,
        "needs_rename": False,
    }


def write_toon(output_dir: str, table_name: str, payload: Dict[str, Any]) -> None:
    os.makedirs(output_dir, exist_ok=True)
    path = os.path.join(output_dir, f"{table_name}.toon.json")
    with open(path, "w", encoding="utf-8") as handle:
        json.dump(payload, handle, indent=2, ensure_ascii=True)


def clear_toon_files(output_dir: str) -> None:
    if not os.path.isdir(output_dir):
        os.makedirs(output_dir, exist_ok=True)
        return
    for name in os.listdir(output_dir):
        if not name.endswith(".toon.json"):
            continue
        path = os.path.join(output_dir, name)
        if os.path.isfile(path):
            os.remove(path)


def main() -> int:
    try:
        conn = get_db_connection()
        cursor = conn.cursor(dictionary=True)
    except Error:
        return 1

    output_dir = os.path.join(os.path.dirname(os.path.dirname(__file__)), "docs", "toons")

    try:
        clear_toon_files(output_dir)
        tables = fetch_tables(cursor)
        for table_name in tables:
            fields = fetch_columns(cursor, table_name)
            indexes = fetch_indexes(cursor, table_name)
            primary_key_name = fetch_primary_key(cursor, table_name)
            primary_key = build_primary_key(primary_key_name)

            payload: Dict[str, Any] = {
                "table_name": table_name,
                "fields": fields,
                "data": [],
                "indexes": indexes,
            }
            if primary_key:
                payload["primary_key"] = primary_key
            if table_name.startswith("lupo_"):
                payload["doctrine_metadata"] = {
                    "no_foreign_keys": True,
                    "no_triggers": True,
                }
            payload["relationships"] = []

            write_toon(output_dir, table_name, payload)
    finally:
        cursor.close()
        conn.close()

    return 0


if __name__ == "__main__":
    raise SystemExit(main())
