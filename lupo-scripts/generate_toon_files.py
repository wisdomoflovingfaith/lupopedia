#!/usr/bin/env python3
# lupopedia.headers:
#   when_updated: "20260403193715"
#   file_path_from_root: "lupo-scripts/generate_toon_files.py"
#   last_modified_utc: "20260403193715"
#   channel_id: 42
#   actor_id: 102
#   actor_name: "cursor"
#   delegation_chain: "cursor:root"
#   artifact_type: "tooling"
#   artifact_kind: "script"
# lupopedia.footer:
#   last_verified: "20260403193715"
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

**Primary workflow:** open a **validated** MySQL connection (**ping**), require **at least 100**
tables in the target database (**SHOW TABLES**), then delete **every regular file** under
**json/** and **toon/** and write fresh exports. If pymysql is missing, the server is down,
credentials fail, ping fails, or the table count is below the minimum, the script **exits
without** clearing those directories.

**Offline alternative:** ``generate_toon_from_sql.py`` builds ``*.toon.json`` from
``install_new_lupopedia.sql`` (no DB) and prunes ``lupo_*`` exports that are not in that DDL;
use when the database is unavailable or to diff repo schema vs install text.

DB config: **lupopedia-config.php** resolved like **index.php** (three-path search + env overrides).
See **lupo-scripts/db_config.py**.
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

# Refuse to wipe json/ + toon/ unless the database looks like a full Lupopedia schema.
MIN_TABLE_COUNT_FOR_EXPORT = 100


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
    """
    Remove all regular files in json/ and toon/ before regenerating from the live DB.

    Uses a full directory wipe (not per-extension globs) so ``*.toon.json`` is not matched
    twice — e.g. ``lupo_x.toon.json`` matches both ``*.json`` and ``*.toon.json``, which
    caused FileNotFoundError on the second unlink on Windows.
    """
    for dir_path in (json_dir.resolve(), toon_dir.resolve()):
        if not dir_path.is_dir():
            dir_path.mkdir(parents=True, exist_ok=True)
            continue
        for path in dir_path.iterdir():
            if path.is_file():
                path.unlink()


def main() -> int:
    base = Path(__file__).resolve().parent
    project_root = base.parent
    json_dir = project_root / "lupo-database" / "lupopedia" / "json"
    toon_dir = project_root / "lupo-database" / "lupopedia" / "toon"

    if pymysql is None or DictCursor is None:
        print("pymysql is required. Install with: pip install pymysql", file=sys.stderr)
        return 1

    conn = None
    try:
        params = get_connection_params()
        params["charset"] = "utf8mb4"
        conn = pymysql.connect(cursorclass=DictCursor, **params)
        conn.ping(reconnect=False)
    except Exception as e:
        print("MySQL not available or database connection failed:", e, file=sys.stderr)
        return 1

    try:
        cursor = conn.cursor()
        tables = fetch_tables(cursor)
        n_tables = len(tables)
        if n_tables < MIN_TABLE_COUNT_FOR_EXPORT:
            print(
                "Refusing to clear export directories: expected at least {} tables, found {}. "
                "Fix the database connection or schema; json/ and toon/ were not modified.".format(
                    MIN_TABLE_COUNT_FOR_EXPORT,
                    n_tables,
                ),
                file=sys.stderr,
            )
            return 1

        clear_toon_files(json_dir, toon_dir)

        for table_name in sorted(tables):
            fields, _column_names = fetch_columns_and_names(cursor, table_name)
            indexes = fetch_indexes(cursor, table_name)
            pk_name = fetch_primary_key(cursor, table_name)
            primary_key = build_primary_key(pk_name)

            # Add required metadata fields for all generated JSON files
            payload = {
                "_comment": "THIS FILE IS AUTO-GENERATED. DO NOT EDIT MANUALLY.",
                "_purpose": "Schema reference only — column names, types, indexes. Contains NO data.",
                "_source": "Generated from live database by generate_toon_files.py",
                "_read_only": True,
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
    ret = main()
    # Also export lupo_memory_nodes for actor_id 116
    try:
        from subprocess import run
        script_path = str((Path(__file__).parent / "export_memory_nodes_116.py").resolve())
        run([sys.executable, script_path], check=True)
    except Exception as e:
        print("[WARN] Could not export lupo_memory_nodes for actor_id 116:", e, file=sys.stderr)
    raise SystemExit(ret)