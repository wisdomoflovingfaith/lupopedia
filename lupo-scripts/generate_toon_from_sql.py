#!/usr/bin/env python3
# lupopedia.headers:
#   when_updated: "20260403193256"
#   file_path_from_root: "lupo-scripts/generate_toon_from_sql.py"
#   last_modified_utc: "20260403193256"
#   channel_id: 42
#   actor_id: 102
#   actor_name: "cursor"
#   delegation_chain: "cursor:root"
#   artifact_type: "tooling"
#   artifact_kind: "script"
# lupopedia.footer:
#   last_verified: "20260403193256"
#   last_verified_by: "cursor"
#   last_verified_by_actor_id: 102

"""
Offline TOON export from **install SQL** (no database).

**Preferred agent workflow** when a DB is available: run **generate_toon_files.py**, which
empties **json/** and **toon/** and regenerates from **SHOW TABLES** / introspection.

This script exists for CI, laptops without MySQL, or checking that **install_new_lupopedia.sql**
matches what you expect — it writes **only** ``*.toon.json`` under **toon/** and then
**prune_stale_table_exports** removes **lupo_*** ``.json`` / ``.toon`` / ``.toon.json`` that
are not in that DDL (it does not empty **json/** entirely).

Canonical schema source: lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql
Output: lupo-database/lupopedia/toon/<table_name>.toon.json
"""

import json
import re
import sys
from pathlib import Path
from typing import Any, Dict, List, Set

TOON_JSON_SUFFIX = ".toon.json"


def prune_stale_table_exports(
    canonical_tables: Set[str],
    json_dir: Path,
    toon_dir: Path,
) -> List[str]:
    """
    Delete lupo_* schema export files not present in canonical_tables.
    Touches only: json/lupo_*.json, toon/lupo_*.toon.json, toon/lupo_*.toon
    """
    canonical = set(canonical_tables)
    removed = []
    if json_dir.is_dir():
        for path in sorted(json_dir.glob("lupo_*.json")):
            if path.stem not in canonical:
                path.unlink()
                removed.append(str(path))
    if toon_dir.is_dir():
        for path in sorted(toon_dir.glob("lupo_*.toon.json")):
            name = path.name
            if not name.endswith(TOON_JSON_SUFFIX):
                continue
            tname = name[: -len(TOON_JSON_SUFFIX)]
            if tname not in canonical:
                path.unlink()
                removed.append(str(path))
        for path in sorted(toon_dir.glob("lupo_*.toon")):
            if path.stem not in canonical:
                path.unlink()
                removed.append(str(path))
    return removed


def parse_install_sql(sql_path: Path) -> Dict[str, Dict[str, Any]]:
    """Parse install_new_lupopedia.sql and return {table_name: {fields, primary_key, indexes}}."""
    text = sql_path.read_text(encoding="utf-8")
    # Install DDL uses {{prefix}} placeholders; normalize to canonical lupo_ for TOON names.
    text = text.replace("{{prefix}}", "lupo_")
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
    project_root = base.parent
    install_sql = project_root / "lupo-database" / "lupopedia" / "mysql" / "install" / "install_new_lupopedia.sql"
    output_dir = project_root / "lupo-database" / "lupopedia" / "toon"
    json_dir = project_root / "lupo-database" / "lupopedia" / "json"

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

    removed = prune_stale_table_exports(set(tables.keys()), json_dir, output_dir)
    if removed:
        print("Removed {} stale export(s) not in install DDL.".format(len(removed)))
        for p in removed:
            print("  {}".format(p))

    print("Generated {} TOONs from {}".format(len(tables), install_sql))
    return 0


if __name__ == "__main__":
    sys.exit(main())