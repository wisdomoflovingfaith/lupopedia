#!/usr/bin/env python3
"""
Generate one-time dev migration to align LIVE DATABASE with install_new_lupopedia.sql.
Canonical schema = install_new_lupopedia.sql. Output: ALTER TABLE ... MODIFY COLUMN only.
No CREATE/DROP TABLE, no data migrations, no FKs, no triggers, no UNSIGNED, no display widths.
Skips PRIMARY KEY columns to preserve AUTO_INCREMENT on live DB.

Run from project root: python scripts/generate_schema_alignment_migration.py
"""

import re
from pathlib import Path


def parse_install_sql(path: Path) -> tuple:
    """Parse install_new_lupopedia.sql. Returns (tables, indexes).
    tables[table_name] = [(col_name, col_def), ...]
    indexes[table_name] = [(index_name, columns, is_unique), ...]
    """
    text = path.read_text(encoding="utf-8", errors="replace")
    tables = {}
    indexes = {}

    # CREATE TABLE name ( ... );
    for m in re.finditer(
        r"CREATE TABLE\s+(\w+)\s*\(\s*\n(.*?)\n\)\s*;",
        text,
        re.DOTALL | re.IGNORECASE,
    ):
        tname = m.group(1)
        body = m.group(2)
        cols = []
        pk_cols = set()
        for line in body.split("\n"):
            line = line.strip().rstrip(",").strip()
            if not line or line.startswith("--"):
                continue
            if re.match(r"PRIMARY\s+KEY\s*\(.*\)", line, re.IGNORECASE):
                # extract column names from PRIMARY KEY (a, b)
                pk_m = re.search(r"PRIMARY\s+KEY\s*\(\s*([^)]+)\s*\)", line, re.IGNORECASE)
                if pk_m:
                    pk_cols.update(x.strip() for x in pk_m.group(1).split(","))
                continue
            # column: name type [NOT NULL] [DEFAULT ...]
            col_m = re.match(r"(\w+)\s+(.+)$", line)
            if col_m:
                cname = col_m.group(1)
                cdef = col_m.group(2).strip()
                cols.append((cname, cdef))
        tables[tname] = (cols, pk_cols)

    # CREATE [UNIQUE] INDEX name ON table (col1, col2);
    for m in re.finditer(
        r"CREATE\s+(UNIQUE\s+)?INDEX\s+(\w+)\s+ON\s+(\w+)\s*\(\s*([^)]+)\s*\)\s*;",
        text,
        re.IGNORECASE,
    ):
        is_unique = bool(m.group(1))
        idx_name = m.group(2)
        tname = m.group(3)
        columns = [x.strip() for x in m.group(4).split(",")]
        if tname not in indexes:
            indexes[tname] = []
        indexes[tname].append((idx_name, columns, is_unique))

    return tables, indexes


def main():
    base = Path(__file__).resolve().parent
    project_root = base.parent
    migrations_dir = project_root / "database" / "migrations"
    install_path = migrations_dir / "install_new_lupopedia.sql"
    if not install_path.exists():
        print("Missing:", install_path)
        return 1

    tables, indexes = parse_install_sql(install_path)
    out_lines = [
        "-- FILE: database/migrations/dev_20260204_fix_schema_alignment.sql",
        "-- TYPE: sql",
        "-- Purpose: One-time dev migration. Align LIVE database schema with install_new_lupopedia.sql.",
        "-- Doctrine 18.9: no CREATE/DROP TABLE, no data migrations, no FKs, no triggers.",
        "-- SQL Doctrine: no UNSIGNED, no display widths, no timestamp/datetime.",
        "-- PK columns are skipped to preserve AUTO_INCREMENT on live DB.",
        "--",
    ]
    corrections = []

    for tname in sorted(tables.keys()):
        cols, pk_cols = tables[tname]
        for cname, cdef in cols:
            if cname in pk_cols:
                continue
            # Normalize: ensure no unsigned, no display width in def (install is already clean)
            def_clean = cdef
            def_clean = re.sub(r"\s+UNSIGNED", "", def_clean, flags=re.IGNORECASE)
            def_clean = re.sub(
                r"\b(BIGINT|INT|SMALLINT|TINYINT)\s*\(\s*\d+\s*\)",
                lambda m: m.group(1),
                def_clean,
                flags=re.IGNORECASE,
            )
            out_lines.append(
                f"ALTER TABLE {tname} MODIFY COLUMN `{cname}` {def_clean};"
            )
            corrections.append((tname, cname, def_clean))

    out_path = migrations_dir / "dev_20260204_fix_schema_alignment.sql"
    out_path.write_text("\n".join(out_lines), encoding="utf-8")
    print("Wrote", out_path, "(", len(corrections), "MODIFY COLUMN statements)")

    # Summary file for documentation
    summary_path = migrations_dir / "dev_20260204_fix_schema_alignment_summary.txt"
    summary_lines = [
        "Schema alignment migration summary",
        "====================================",
        "",
        "Canonical schema: install_new_lupopedia.sql",
        "Target: LIVE database (e.g. phpMyAdmin)",
        "",
        "Columns corrected (non-PK only; PK skipped to preserve AUTO_INCREMENT):",
        "",
    ]
    for tname, cname, def_clean in corrections:
        summary_lines.append(f"  {tname}.{cname} -> {def_clean}")
    summary_lines.extend([
        "",
        "Why each correction:",
        "  - Live DB may have UNSIGNED on integers; install uses signed only (SQL Doctrine).",
        "  - Live DB may have display widths (INT(11), BIGINT(20)); install has none.",
        "  - Live DB may have wrong type (INT vs BIGINT), NULL/NOT NULL, or DEFAULT; install is canonical.",
        "  - No timestamp/datetime; install uses BIGINT for all temporal (Doctrine §5).",
        "",
        "Indexes:",
        "  - This migration does NOT add or drop indexes.",
        "  - If your live DB has extra indexes not in install_new_lupopedia.sql, drop them manually.",
        "  - If your live DB is missing indexes from install_new_lupopedia.sql, add them manually from that file.",
    ])
    summary_path.write_text("\n".join(summary_lines), encoding="utf-8")
    print("Wrote", summary_path)

    return 0


if __name__ == "__main__":
    raise SystemExit(main())
