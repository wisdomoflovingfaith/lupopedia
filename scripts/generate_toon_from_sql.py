#!/usr/bin/env python3
"""
Generate TOON files from canonical schema (install_new_lupopedia.sql).

Canonical schema source: database/migrations/install_new_lupopedia.sql
TOON files are derived from this file; no live DB required.
Output: docs/toons/<table_name>.toon.json
"""

import json
import re
import sys
from pathlib import Path
from typing import Any, Dict, List, Optional, Tuple


def parse_install_sql(sql_path: Path) -> Dict[str, Dict[str, Any]]:
    """Parse install_new_lupopedia.sql and return {table_name: {fields, primary_key, indexes}}."""
    text = sql_path.read_text(encoding="utf-8")
    tables = {}

    # Find all CREATE TABLE blocks
    # Pattern: CREATE TABLE table_name ( ... );
    create_table_re = re.compile(
        r"CREATE\s+TABLE\s+(?:`?)(\w+)(?:`?)\s*\((.*?)\)\s*;",
        re.DOTALL | re.IGNORECASE
    )
    for m in create_table_re.finditer(text):
        table_name = m.group(1)
        body = m.group(2)

        fields = []
        primary_key_col = None

        # Split body by comma, but respect parentheses (e.g. varchar(64))
        lines = [line.strip() for line in body.split("\n") if line.strip()]
        for line in lines:
            line = line.rstrip(",").strip()
            if not line:
                continue
            if line.upper().startswith("PRIMARY KEY"):
                pk_match = re.search(r"PRIMARY\s+KEY\s*\(\s*`?(\w+)`?\s*\)", line, re.I)
                if pk_match:
                    primary_key_col = pk_match.group(1)
                continue
            if line.upper().startswith("UNIQUE") or line.upper().startswith("KEY") or line.upper().startswith("INDEX"):
                continue
            # Column: name type [NOT NULL] [DEFAULT x]
            col_match = re.match(
                r"^`?(\w+)`?\s+(\S+(?:\s*\([^)]+\))?)\s*(.*)$",
                line
            )
            if col_match:
                col_name = col_match.group(1)
                col_type = col_match.group(2)
                rest = col_match.group(3).strip()
                parts = ["`" + col_name + "`", col_type]
                if "NOT NULL" in rest.upper():
                    parts.append("NOT NULL")
                if "DEFAULT" in rest.upper():
                    default_match = re.search(r"DEFAULT\s+(.+?)(?:\s|$)", rest, re.I | re.DOTALL)
                    if default_match:
                        dv = default_match.group(1).strip().rstrip(",")
                        if dv.upper() == "NULL":
                            pass
                        elif col_type.upper().startswith(("VARCHAR", "CHAR", "TEXT", "ENUM", "SET")):
                            parts.append("DEFAULT " + dv)
                        else:
                            parts.append("DEFAULT " + dv)
                fields.append(" ".join(parts))

        # Find indexes for this table
        indexes = []
        index_re = re.compile(
            r"CREATE\s+(UNIQUE\s+)?INDEX\s+(\w+)\s+ON\s+(?:`?)(\w+)(?:`?)\s*\((.*?)\)",
            re.IGNORECASE
        )
        for im in index_re.finditer(text):
            if im.group(3) == table_name:
                idx_name = im.group(2)
                is_unique = im.group(1) is not None
                cols_str = im.group(4)
                cols = [c.strip().strip("`").split()[0] for c in cols_str.split(",")]
                indexes.append({
                    "index_name": idx_name,
                    "columns": cols,
                    "is_unique": is_unique,
                    "index_type": "BTREE",
                })

        tables[table_name] = {
            "fields": fields,
            "primary_key_col": primary_key_col,
            "indexes": indexes,
        }

    return tables


def build_toon(table_name: str, meta: Dict[str, Any]) -> Dict[str, Any]:
    """Build TOON JSON payload."""
    payload = {
        "table_name": table_name,
        "fields": meta["fields"],
        "data": [],
        "indexes": meta["indexes"],
        "relationships": [],
    }
    if meta.get("primary_key_col"):
        payload["primary_key"] = {
            "column_name": meta["primary_key_col"],
            "expected_name": meta["primary_key_col"],
            "is_correct": True,
            "needs_rename": False,
        }
    if table_name.startswith("lupo_"):
        payload["doctrine_metadata"] = {
            "no_foreign_keys": True,
            "no_triggers": True,
        }
    return payload


def main() -> int:
    base = Path(__file__).resolve().parent
    install_sql = base.parent / "database" / "migrations" / "install_new_lupopedia.sql"
    output_dir = base.parent / "docs" / "toons"

    if not install_sql.exists():
        print("install_new_lupopedia.sql not found:", install_sql, file=sys.stderr)
        return 1

    tables = parse_install_sql(install_sql)
    output_dir.mkdir(parents=True, exist_ok=True)

    for table_name in sorted(tables.keys()):
        meta = tables[table_name]
        payload = build_toon(table_name, meta)
        path = output_dir / (table_name + ".toon.json")
        path.write_text(json.dumps(payload, indent=2, ensure_ascii=False), encoding="utf-8")

    print("Generated {} TOONs from {}".format(len(tables), install_sql))
    return 0


if __name__ == "__main__":
    sys.exit(main())
