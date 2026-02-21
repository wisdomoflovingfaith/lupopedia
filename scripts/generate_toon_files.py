#!/usr/bin/env python3
"""
TOON Generator (schema + canonical data from live DB).

Generates JSON TOON files for all tables using live MySQL schema introspection
(SHOW TABLES / SHOW FULL COLUMNS / SHOW INDEX) and optionally canonical rows:

- PK=0 row: for any table with a primary key, include the row where pk = 0 if it exists.
- lupo_unified_registry: all rows from DB + active agents as actors (doctrine; no inactive agents).

Actor/agent doctrine: active agents (lupo_agent_registry WHERE is_active=1) are included in
lupo_unified_registry TOON data as entity_type='actor', entity_table='lupo_agent_registry'.

Output: docs/toons/<table_name>.toon.json
DB config: read from lupopedia-config.php (project root). See scripts/db_config.py.
If SKIP_DB=1, canonical data is skipped and "data" arrays are empty.
"""

import json
import os
import sys
from datetime import date, datetime
from decimal import Decimal
from pathlib import Path
from typing import Any, Dict, List, Optional, Tuple

try:
    import pymysql
    from pymysql.cursors import DictCursor
except ImportError:
    pymysql = None
    DictCursor = None

from db_config import get_connection_params
from actor_agent_doctrine import (
    AGENT_REGISTRY_TABLE,
    UNIFIED_REGISTRY_TABLE,
    build_unified_registry_row_from_agent as doctrine_build_unified_row,
)

STRING_TYPES = {
    "char", "varchar", "text", "tinytext", "mediumtext", "longtext", "enum", "set",
}


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
        default_clause = _quote_default(default, col_type.split("(")[0].lower())
        if default_clause:
            parts.append(default_clause.strip())
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


def json_serializable(val: Any) -> Any:
    """Convert a DB value to a JSON-serializable value."""
    if val is None:
        return None
    if isinstance(val, (bool, int, float, str)):
        return val
    if isinstance(val, (datetime, date)):
        return val.isoformat()
    if isinstance(val, Decimal):
        return int(val) if val == val.to_integral_value() else float(val)
    if isinstance(val, bytes):
        return val.hex()
    return str(val)


def row_to_data_dict(row: Dict[str, Any], column_names: List[str]) -> Dict[str, Any]:
    """Build a dict with keys in column order, values JSON-serializable.
    Row keys may differ in case; match case-insensitively.
    """
    if not row:
        return {c: None for c in column_names}
    row_lower = {str(k).lower(): v for k, v in row.items()}
    return {c: json_serializable(row_lower.get(c.lower())) for c in column_names}


def fetch_pk_zero_row(cursor, table_name: str, pk_column: str) -> Optional[Dict[str, Any]]:
    cursor.execute("SELECT * FROM `{}` WHERE `{}` = 0".format(
        table_name.replace("`", "``"), pk_column.replace("`", "``")))
    return cursor.fetchone()


def fetch_all_rows(cursor, table_name: str) -> List[Dict[str, Any]]:
    cursor.execute("SELECT * FROM `{}`".format(table_name.replace("`", "``")))
    return cursor.fetchall()


def fetch_active_agents(cursor) -> List[Dict[str, Any]]:
    """Fetch lupo_agent_registry WHERE is_active = 1 (doctrine: active agents become actors)."""
    try:
        cursor.execute(
            "SELECT * FROM `{}` WHERE `is_active` = 1 ORDER BY `agent_registry_id`".format(AGENT_REGISTRY_TABLE)
        )
        return cursor.fetchall()
    except Exception:
        return []


def fetch_canonical_data(
    cursor,
    table_name: str,
    column_names: List[str],
    pk_column: Optional[str],
    skip_db: bool,
) -> List[Dict[str, Any]]:
    """Return list of canonical row dicts for the TOON 'data' array.
    - If SKIP_DB: return [].
    - If lupo_unified_registry: return all rows + active agents as actors (doctrine; no inactive agents).
    - Else if table has PK: return [row where pk=0] if exists.
    - Else: return [].
    """
    if skip_db:
        return []

    data = []
    if table_name == UNIFIED_REGISTRY_TABLE:
        rows = fetch_all_rows(cursor, table_name)
        existing_keys = set()
        for row in rows:
            data.append(row_to_data_dict(row, column_names))
            rl = {str(k).lower(): v for k, v in (row or {}).items()}
            existing_keys.add((rl.get("entity_type"), rl.get("entity_id")))
        # Doctrine: active agents become actors in unified registry (no inactive agents).
        active_agents = fetch_active_agents(cursor)
        for agent_row in active_agents:
            rl = {str(k).lower(): v for k, v in (agent_row or {}).items()}
            aid = rl.get("agent_registry_id")
            if aid is None or ("actor", aid) in existing_keys:
                continue
            by_col = doctrine_build_unified_row(agent_row)
            if by_col is not None:
                row_dict = {c: json_serializable(by_col.get(c)) for c in column_names}
                data.append(row_dict)
                existing_keys.add(("actor", aid))
        return data

    if pk_column:
        row = fetch_pk_zero_row(cursor, table_name, pk_column)
        if row is not None:
            data.append(row_to_data_dict(row, column_names))
    return data


def write_toon(output_dir: Path, table_name: str, payload: Dict[str, Any]) -> None:
    output_dir.mkdir(parents=True, exist_ok=True)
    path = output_dir / "{}.toon.json".format(table_name)
    with path.open("w", encoding="utf-8") as f:
        json.dump(payload, f, indent=2, ensure_ascii=False)


def clear_toon_files(output_dir: Path) -> None:
    if not output_dir.is_dir():
        output_dir.mkdir(parents=True, exist_ok=True)
        return
    for path in output_dir.glob("*.toon.json"):
        path.unlink()


def main() -> int:
    base = Path(__file__).resolve().parent
    output_dir = base.parent / "docs" / "toons"
    skip_db = os.getenv("SKIP_DB", "").lower() in ("1", "true", "yes")

    if pymysql is None or DictCursor is None:
        print("pymysql is required. Install with: pip install pymysql", file=sys.stderr)
        return 1

    try:
        params = get_connection_params()
        params["charset"] = "utf8mb4"
        conn = pymysql.connect(cursorclass=DictCursor, **params)
    except Exception as e:
        print("Database connection failed:", e, file=sys.stderr)
        print("Set SKIP_DB=1 only skips canonical data; schema still requires a connection.", file=sys.stderr)
        return 1

    try:
        cursor = conn.cursor()
        clear_toon_files(output_dir)
        tables = fetch_tables(cursor)

        if not tables:
            print("No tables returned from database.", file=sys.stderr)
            return 1

        for table_name in sorted(tables):
            fields, column_names = fetch_columns_and_names(cursor, table_name)
            indexes = fetch_indexes(cursor, table_name)
            pk_name = fetch_primary_key(cursor, table_name)
            primary_key = build_primary_key(pk_name)

            data = fetch_canonical_data(cursor, table_name, column_names, pk_name, skip_db)

            payload = {
                "table_name": table_name,
                "fields": fields,
                "data": data,
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

        print("Wrote {} TOONs to {}".format(len(tables), output_dir))
        
        # Automatically trigger CSV export after TOON generation
        print("Triggering CSV export...")
        try:
            import subprocess
            import sys
            
            # Get the project root directory
            project_root = Path(__file__).parent.parent
            admin_script = project_root / "admin.php"
            
            if admin_script.exists():
                # Call the CSV export via PHP
                result = subprocess.run([
                    "php", str(admin_script), "section=csv-export"
                ], capture_output=True, text=True, cwd=str(project_root))
                
                if result.returncode == 0:
                    print("CSV export completed successfully")
                    print("TOON generation complete. CSV export complete.")
                else:
                    print(f"CSV export failed: {result.stderr}")
            else:
                print("admin.php not found, skipping CSV export")
                
        except Exception as e:
            print(f"Error triggering CSV export: {e}")
            print("TOON generation complete. CSV export failed.")
        
        return 0
    finally:
        if conn:
            conn.close()


if __name__ == "__main__":
    raise SystemExit(main())
