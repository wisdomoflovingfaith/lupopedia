#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Validate TRUST_LADDER_REGISTRY.md table names against install_new_lupopedia.sql.

What this script checks (only):
  - Every backtick-wrapped `lupo_*` table name in the registry exists as a table
    created in the canonical install SQL (name alignment / drift guard).

What it does not check (intentional / out of scope here):
  - PK column names or participation class strings — keep registry and TOON/docs
    authoritative; optional future script or code review.
  - Whether "full ladder" vs "generator_staging" rows actually match code paths —
    defer to implementation review and CHRONOLOGICAL_TRUST_LADDER.md.
  - Un-backtick table mentions — registry is expected to use backticks consistently
    for machine-readable table names.
  - Reverse direction (install tables missing from registry) — the registry lists
    only ladder-related tables; almost all install tables are intentionally omitted.

Install SQL parsing notes:
  - Matches CREATE TABLE {{prefix}}name (canonical Lupopedia install template).
  - Also matches CREATE TABLE lupo_name / `lupo_name` for forks or pasted DDL.
  - Optional IF NOT EXISTS (ignored for capture).
  - Table names compared case-insensitively (MySQL lower_case_table_names variance).

See: lupo-docs/doctrine/TRUST_LADDER_REGISTRY.md, CHRONOLOGICAL_TRUST_LADDER.md §10.
"""

import argparse
import re
import sys
from pathlib import Path

PROJECT_ROOT = Path(__file__).resolve().parent.parent
DEFAULT_INSTALL_SQL = (
    PROJECT_ROOT / "lupo-database" / "lupopedia" / "mysql" / "install" / "install_new_lupopedia.sql"
)
DEFAULT_REGISTRY = PROJECT_ROOT / "lupo-docs" / "doctrine" / "TRUST_LADDER_REGISTRY.md"

# CREATE TABLE {{prefix}}tablename — short name becomes lupo_<short>
CREATE_TABLE_PREFIX_RE = re.compile(
    r"CREATE\s+TABLE\s+(?:IF\s+NOT\s+EXISTS\s+)?\{\{prefix\}\}(\w+)\s*\(",
    re.IGNORECASE,
)

# CREATE TABLE lupo_tablename (optional backticks) — some exports use literal prefix
CREATE_TABLE_LITERAL_RE = re.compile(
    r"CREATE\s+TABLE\s+(?:IF\s+NOT\s+EXISTS\s+)?`?lupo_(\w+)`?\s*\(",
    re.IGNORECASE,
)

# Backtick-wrapped lupo_* identifiers in the registry doc
BACKTICK_TABLE_RE = re.compile(r"`(lupo_[a-z0-9_]+)`")


def _full_table_name_from_short(short):
    """Normalize short table name to lupo_* full name, lowercased."""
    s = short
    if not s.startswith("lupo_"):
        s = "lupo_" + s
    return s.lower()


def tables_from_install_sql(install_path):
    """Return set of lowercase full table names (lupo_*) found in install DDL."""
    if not install_path.is_file():
        return set()
    text = install_path.read_text(encoding="utf-8", errors="replace")
    found = set()
    for m in CREATE_TABLE_PREFIX_RE.finditer(text):
        found.add(_full_table_name_from_short(m.group(1)))
    for m in CREATE_TABLE_LITERAL_RE.finditer(text):
        found.add(_full_table_name_from_short(m.group(1)))
    return found


def tables_from_registry_doc(registry_path):
    """Return set of lowercase lupo_* names from backtick-wrapped refs."""
    if not registry_path.is_file():
        return set()
    text = registry_path.read_text(encoding="utf-8", errors="replace")
    return set(n.lower() for n in BACKTICK_TABLE_RE.findall(text))


def main():
    parser = argparse.ArgumentParser(
        description="Verify TRUST_LADDER_REGISTRY.md table names exist in install SQL."
    )
    parser.add_argument(
        "--install-sql",
        type=Path,
        default=DEFAULT_INSTALL_SQL,
        help="Path to install_new_lupopedia.sql (default: canonical path under repo)",
    )
    parser.add_argument(
        "--registry",
        type=Path,
        default=DEFAULT_REGISTRY,
        help="Path to TRUST_LADDER_REGISTRY.md (default: canonical doctrine path)",
    )
    args = parser.parse_args()

    install_sql = args.install_sql.resolve()
    registry = args.registry.resolve()

    if not install_sql.exists():
        print("ERROR: Install SQL not found at %s" % install_sql)
        return 1

    if not registry.exists():
        print("ERROR: Registry not found at %s" % registry)
        return 1

    sql_tables = tables_from_install_sql(install_sql)
    reg_tables = tables_from_registry_doc(registry)

    print("Install SQL tables (lupo_*): %s" % len(sql_tables))
    print("Registry backtick table refs: %s" % len(reg_tables))

    if not reg_tables:
        print("WARNING: No backtick `lupo_*` table names found in registry — nothing to validate.")
        return 0

    if not sql_tables:
        print("ERROR: No CREATE TABLE statements parsed from install SQL — check file format.")
        return 1

    missing = sorted(reg_tables - sql_tables)
    if missing:
        print("ERROR: Registry references tables not found in install SQL:")
        for name in missing:
            print("  - %s" % name)
        return 1

    print("OK: All registry table names exist in install SQL")
    return 0


if __name__ == "__main__":
    sys.exit(main())
