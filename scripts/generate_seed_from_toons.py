#!/usr/bin/env python3
"""
Generate seed_lupopedia.sql from TOON files and live database.

Sources:
1. Unified registry table (lupo_unified_registry): all canonical rows from DB.
2. PK=0 rows: for each table with a primary key, include row where pk=0 if it exists in DB.
3. TOON "data" array: emit INSERTs for rows explicitly defined in TOONs.

Output: INSERT statements only (no CREATE TABLE, no DROP TABLE).
Writes to database/migrations/seed_lupopedia.sql.

DB config: read from lupopedia-config.php (project root). See scripts/db_config.py.
"""

import json
import os
import re
import sys
from pathlib import Path

try:
    import pymysql
    from pymysql.cursors import DictCursor
except ImportError:
    pymysql = None
    DictCursor = None

from db_config import get_connection_params

# Single unified registry table (old separate registry tables no longer exist).
UNIFIED_REGISTRY_TABLE = "lupo_unified_registry"


def extract_column_names(fields):
    """Extract column name from each TOON field string. Format: `colname` type ..."""
    cols = []
    for f in fields:
        if not isinstance(f, str):
            continue
        m = re.match(r"`([^`]+)`", f)
        if m:
            cols.append(m.group(1))
    return cols


def get_primary_key_column(toon):
    """Return primary key column name from TOON, or None."""
    pk = toon.get("primary_key")
    if isinstance(pk, dict):
        return pk.get("column_name")
    return None


def sql_value(val):
    """Format a Python value for SQL VALUES clause."""
    if val is None:
        return "NULL"
    if isinstance(val, bool):
        return "1" if val else "0"
    if isinstance(val, (int, float)):
        return str(val)
    if isinstance(val, dict):
        s = json.dumps(val, separators=(",", ":"))
        return "'" + s.replace("\\", "\\\\").replace("'", "''") + "'"
    if hasattr(val, "strftime"):
        # datetime / date
        s = val.isoformat().replace("T", " ").split(".")[0]
        return "'" + s.replace("'", "''") + "'"
    if isinstance(val, bytes):
        return "'" + val.hex().replace("'", "''") + "'" if val else "NULL"
    s = str(val)
    return "'" + s.replace("\\", "\\\\").replace("'", "''") + "'"


def row_to_values_list(row_dict, cols):
    """Build list of SQL value strings for a row, using column order from cols.
    Row keys may differ in case from TOON column names; match case-insensitively.
    """
    if not row_dict:
        return [sql_value(None) for _ in cols]
    row_lower = {str(k).lower(): v for k, v in row_dict.items()}
    return [sql_value(row_lower.get(c.lower())) for c in cols]


def build_insert(table_name, cols, values_list):
    """Build INSERT INTO table (cols) VALUES (...);"""
    cols_quoted = ", ".join("`" + c + "`" for c in cols)
    vals_str = ", ".join(values_list)
    return "INSERT INTO {} ({}) VALUES ({});".format(table_name, cols_quoted, vals_str)


def load_toons(toon_dir):
    """Load all TOONs from directory. Return list of dicts."""
    toons = []
    for path in sorted(toon_dir.glob("*.toon.json")):
        try:
            data = json.loads(path.read_text(encoding="utf-8"))
            toons.append(data)
        except Exception as e:
            print("Parse error", path.name, e, file=sys.stderr)
    return toons


def fetch_unified_registry_rows(cursor):
    """Fetch all rows from lupo_unified_registry."""
    try:
        cursor.execute("SELECT * FROM {}".format(UNIFIED_REGISTRY_TABLE))
        return cursor.fetchall()
    except Exception as e:
        print("Warning: could not fetch unified registry:", e, file=sys.stderr)
        return []


def fetch_pk_zero_row(cursor, table_name, pk_column):
    """Fetch single row where primary key = 0. Return None if not found or error."""
    try:
        cursor.execute(
            "SELECT * FROM `{}` WHERE `{}` = 0".format(table_name, pk_column)
        )
        row = cursor.fetchone()
        return row
    except Exception as e:
        print("Warning: could not fetch PK=0 for {}: {}".format(table_name, e), file=sys.stderr)
        return None


def main():
    base = Path(__file__).resolve().parent
    project_root = base.parent
    toon_dir = project_root / "docs" / "toons"
    out_path = project_root / "database" / "migrations" / "seed_lupopedia.sql"

    skip_db = os.getenv("SKIP_DB", "").lower() in ("1", "true", "yes")

    if not toon_dir.exists():
        print("TOON dir not found:", toon_dir, file=sys.stderr)
        return 1

    toons = load_toons(toon_dir)
    if not toons:
        print("No TOONs found under", toon_dir, file=sys.stderr)

    # Map table_name -> {cols, pk_col, toon_data}
    table_info = {}
    for t in toons:
        name = t.get("table_name", "")
        if not name:
            continue
        fields = t.get("fields", [])
        cols = extract_column_names(fields)
        if not cols:
            continue
        pk_col = get_primary_key_column(t)
        data = t.get("data", [])
        if not isinstance(data, list):
            data = []
        table_info[name] = {"cols": cols, "pk_col": pk_col, "toon_data": data}

    # --- 1. Unified registry rows from DB ---
    registry_inserts = []
    if UNIFIED_REGISTRY_TABLE in table_info:
        info = table_info[UNIFIED_REGISTRY_TABLE]
        cols = info["cols"]
        if not skip_db and pymysql is not None:
            try:
                conn = pymysql.connect(cursorclass=DictCursor, **get_connection_params())
                try:
                    with conn.cursor() as cur:
                        rows = fetch_unified_registry_rows(cur)
                        for row in rows:
                            vals = row_to_values_list(row, cols)
                            registry_inserts.append(build_insert(UNIFIED_REGISTRY_TABLE, cols, vals))
                finally:
                    conn.close()
            except Exception as e:
                print("DB connection failed (unified registry):", e, file=sys.stderr)
                print("Set SKIP_DB=1 to generate seed from TOON data only.", file=sys.stderr)

    # --- 2. PK=0 rows from DB (only tables we have TOONs for) ---
    pk0_inserts = []
    if not skip_db and pymysql is not None:
        try:
            conn = pymysql.connect(cursorclass=DictCursor, **get_connection_params())
            try:
                with conn.cursor() as cur:
                    for table_name in sorted(table_info.keys()):
                        if table_name == UNIFIED_REGISTRY_TABLE:
                            continue
                        info = table_info[table_name]
                        pk_col = info.get("pk_col")
                        if not pk_col:
                            continue
                        row = fetch_pk_zero_row(cur, table_name, pk_col)
                        if row is not None:
                            cols = info["cols"]
                            vals = row_to_values_list(row, cols)
                            pk0_inserts.append(build_insert(table_name, cols, vals))
            finally:
                conn.close()
        except Exception as e:
            print("DB connection failed (PK=0):", e, file=sys.stderr)

    # --- 3. TOON-defined canonical rows (from "data" array) ---
    toon_inserts = []
    for t in toons:
        table_name = t.get("table_name", "")
        if table_name not in table_info:
            continue
        info = table_info[table_name]
        cols = info["cols"]
        for row in info["toon_data"]:
            if not isinstance(row, dict):
                continue
            vals = [sql_value(row.get(c)) for c in cols]
            stmt = build_insert(table_name, cols, vals)
            toon_inserts.append((table_name, stmt))

    # --- Output: (a) unified registry, (b) PK=0, (c) TOON data ---
    lines = [
        "-- FILE: database/migrations/seed_lupopedia.sql",
        "-- Generated from docs/toons/*.toon.json and live DB. DO NOT EDIT BY HAND.",
        "-- Purpose: Seed data for fresh Lupopedia 4.0.0 install. Run after install_new_lupopedia.sql.",
        "-- No Crafty Syntax data. No schema. INSERT only.",
        "",
        "-- =============================================================================",
        "-- SEED LUPOPEDIA — CANONICAL BIRTH-STATE",
        "-- =============================================================================",
        "",
    ]

    # (a) Unified registry
    lines.append("-- -----------------------------------------------------------------------------")
    lines.append("-- Unified registry (lupo_unified_registry)")
    lines.append("-- -----------------------------------------------------------------------------")
    if registry_inserts:
        for stmt in registry_inserts:
            lines.append(stmt)
            lines.append("")
    else:
        lines.append("-- (none)")
        lines.append("")

    # (b) PK=0 rows
    lines.append("-- -----------------------------------------------------------------------------")
    lines.append("-- PK=0 / collection-type rows")
    lines.append("-- -----------------------------------------------------------------------------")
    if pk0_inserts:
        for stmt in pk0_inserts:
            lines.append(stmt)
            lines.append("")
    else:
        lines.append("-- (none)")
        lines.append("")

    # (c) TOON-defined canonical rows
    lines.append("-- -----------------------------------------------------------------------------")
    lines.append("-- TOON-defined canonical rows (from \"data\" array)")
    lines.append("-- -----------------------------------------------------------------------------")
    if toon_inserts:
        for _, stmt in toon_inserts:
            lines.append(stmt)
            lines.append("")
    else:
        lines.append("-- (none)")
        lines.append("")

    lines.append("-- =============================================================================")
    lines.append("-- END SEED")
    lines.append("-- =============================================================================")
    lines.append("")

    out_path.parent.mkdir(parents=True, exist_ok=True)
    out_path.write_text("\n".join(lines), encoding="utf-8")
    print("Wrote", out_path)
    print("Unified registry INSERTs:", len(registry_inserts))
    print("PK=0 INSERTs:", len(pk0_inserts))
    print("TOON data INSERTs:", len(toon_inserts))
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
