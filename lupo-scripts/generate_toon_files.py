#!/usr/bin/env python3
# lupopedia.headers:
#   when_updated: "20260401000000"
#   file_path_from_root: "lupo-scripts/generate_toon_files.py"
#   last_modified_utc: "20260401000000"
#   channel_id: 42
#   actor_id: 102
#   actor_name: "cursor"
#   delegation_chain: "cursor:root"
#   artifact_type: "tooling"
#   artifact_kind: "script"
# lupopedia.footer:
#   last_verified: "20260401"
#   last_verified_by: "cursor"
#   last_verified_by_actor_id: 102

"""
TOON Generator — schema-only export from live DB.

Generates TOON representations of the database schema for all tables using live MySQL
schema introspection (SHOW TABLES / SHOW FULL COLUMNS / SHOW INDEX).

Output contains STRUCTURE ONLY — no row data is written to any output file.
The JSON files are schema reference documents for agents and tooling, not a file database.
Lupopedia uses MySQL as its database. These files exist so column names, types, and
indexes can be read without parsing large SQL files or guessing.

For debugging data export, use the separate lupo-scripts/export_table_data_csv.py script,
which excludes sensitive tables and requires explicit opt-in.

Output:
  - lupo-database/lupopedia/json/<table_name>.json  (JSON format, schema only)
  - lupo-database/lupopedia/toon/<table_name>.toon  (TOON format: YAML, schema only)

DB config: read from lupopedia-config.php (project root). See scripts/db_config.py.
"""

import json
import os
import sys
from pathlib import Path
from typing import Any, Dict, List, Optional, Tuple

try:
    import pymysql
    from pymysql.cursors import DictCursor
except ImportError:
    pymysql = None
    DictCursor = None

try:
    import yaml
except ImportError:
    yaml = None

from db_config import get_connection_params

def fetch_tables(cursor) -> List[str]:
    cursor.execute("SHOW TABLES")
    rows = cursor.fetchall()
    if rows and isinstance(rows[0], dict):
        return [list(row.values())[0] for row in rows]
    return [row[0] for row in rows]


def fetch_columns_and_names(cursor, table_name: str) -> Tuple[List[str], List[str]]:
    """Return (field_definitions, column_names_in_order)."""
    cursor.execute("SHOW FULL COLUMNS FROM `{}`".format(table_name.replace("`", "``")))
    rows = cursor.fetchall()
    fields = []
    names = []
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

        parts = ["`{}`".format(name), col_type]
        if not is_nullable:
            parts.append("NOT NULL")
        if default is not None:
            dt = col_type.split("(")[0].lower()
            if isinstance(default, str) and default.upper() == "CURRENT_TIMESTAMP":
                parts.append("DEFAULT CURRENT_TIMESTAMP")
            elif dt in {"char", "varchar", "text", "tinytext", "mediumtext", "longtext", "enum", "set"}:
                escaped = str(default).replace("\\", "\\\\").replace("'", "''")
                parts.append("DEFAULT '{}'".format(escaped))
            else:
                parts.append("DEFAULT {}".format(default))
        if extra:
            parts.append(extra)
        if comment:
            escaped_comment = str(comment).replace("\\", "\\\\").replace("'", "''")
            parts.append("COMMENT '{}'".format(escaped_comment))
        fields.append(" ".join(parts))
        names.append(name)
    return fields, names


def fetch_primary_key(cursor, table_name: str) -> Optional[str]:
    cursor.execute("SHOW INDEX FROM `{}` WHERE Key_name = 'PRIMARY'".format(table_name.replace("`", "``")))
    rows = cursor.fetchall()
    if not rows:
        return None
    if isinstance(rows[0], dict):
        return rows[0]["Column_name"]
    return rows[0][4]


def fetch_indexes(cursor, table_name: str) -> List[Dict[str, Any]]:
    cursor.execute("SHOW INDEX FROM `{}`".format(table_name.replace("`", "``")))
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
    for name, meta in sorted(indexes.items()):
        ordered_columns = [meta["columns"][i] for i in sorted(meta["columns"].keys())]
        result.append({
            "index_name": name,
            "columns": ordered_columns,
            "is_unique": bool(meta["is_unique"]),
            "index_type": meta["index_type"],
        })
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


def write_toon(
    json_dir: Path,
    toon_dir: Path,
    table_name: str,
    payload: Dict[str, Any],
) -> None:
    """Write payload: JSON format to json/<table>.json, TOON (YAML) format to toon/<table>.toon."""
    json_dir.mkdir(parents=True, exist_ok=True)
    toon_dir.mkdir(parents=True, exist_ok=True)
    json_path = json_dir / "{}.json".format(table_name)
    toon_path = toon_dir / "{}.toon".format(table_name)
    with json_path.open("w", encoding="utf-8") as f:
        json.dump(payload, f, indent=2, ensure_ascii=False)
    if yaml is not None:
        with toon_path.open("w", encoding="utf-8") as f:
            yaml.dump(
                payload,
                f,
                default_flow_style=False,
                allow_unicode=True,
                sort_keys=False,
            )
    else:
        with toon_path.open("w", encoding="utf-8") as f:
            json.dump(payload, f, indent=2, ensure_ascii=False)


def clear_toon_files(json_dir: Path, toon_dir: Path) -> None:
    """Remove generated .json and .toon files from both output dirs."""
    for dir_path in (json_dir, toon_dir):
        if not dir_path.is_dir():
            dir_path.mkdir(parents=True, exist_ok=True)
            continue
        for path in list(dir_path.glob("*.json")) + list(dir_path.glob("*.toon")):
            path.unlink()


def main() -> int:
    base = Path(__file__).resolve().parent
    project_root = base.parent
    json_dir = project_root / "lupo-database" / "lupopedia" / "json"
    toon_dir = project_root / "lupo-database" / "lupopedia" / "toon"

    if pymysql is None or DictCursor is None:
        print("pymysql is required. Install with: pip install pymysql", file=sys.stderr)
        return 1

    try:
        params = get_connection_params()
        params["charset"] = "utf8mb4"
        conn = pymysql.connect(cursorclass=DictCursor, **params)
    except Exception as e:
        print("Database connection failed:", e, file=sys.stderr)
        return 1

    try:
        cursor = conn.cursor()
        clear_toon_files(json_dir, toon_dir)
        tables = fetch_tables(cursor)

        if not tables:
            print("No tables returned from database.", file=sys.stderr)
            return 1

        for table_name in sorted(tables):
            fields, _column_names = fetch_columns_and_names(cursor, table_name)
            indexes = fetch_indexes(cursor, table_name)
            pk_name = fetch_primary_key(cursor, table_name)
            primary_key = build_primary_key(pk_name)

            # Schema only — no row data written to output files.
            # For data export use lupo-scripts/export_table_data_csv.py (separate tool).
            payload = {
                "table_name": table_name,
                "fields": fields,
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

            write_toon(json_dir, toon_dir, table_name, payload)

        print("Wrote {} TOONs (schema only) to {} (JSON) and {} (.toon)".format(
            len(tables), json_dir, toon_dir))
        return 0
    finally:
        if conn:
            conn.close()


if __name__ == "__main__":
    raise SystemExit(main())