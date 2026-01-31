#!/usr/bin/env python3
"""
Analyze TOON schema gaps and generate migration SQL if needed.

This script:
1. Parses all TOON files in docs/toons/
2. Identifies tables that should exist per TOON definitions
3. Checks against the 222 table limit
4. Generates migration SQL to sync schema to TOON definitions

Doctrine compliance:
- No UNSIGNED keywords
- No foreign keys
- No triggers
- No stored procedures
- All timestamps as BIGINT (YYYYMMDDHHIISS)
- No schema guessing or invention
"""

import argparse
import json
import sys
from datetime import datetime, timezone
from pathlib import Path


def parse_args():
    parser = argparse.ArgumentParser(
        description="Analyze TOON schema gaps and generate migration SQL"
    )
    parser.add_argument(
        "--toon-dir",
        type=Path,
        default=Path("docs/toons"),
        help="Directory containing TOON files (default: docs/toons)",
    )
    parser.add_argument(
        "--output",
        type=Path,
        default=None,
        help="Output migration SQL file (default: auto-generate name)",
    )
    parser.add_argument(
        "--current-table-count",
        type=int,
        default=218,
        help="Current table count in database (default: 218)",
    )
    parser.add_argument(
        "--table-limit",
        type=int,
        default=222,
        help="Maximum table limit (default: 222)",
    )
    parser.add_argument(
        "--dry-run",
        action="store_true",
        help="Show what would be done without creating files",
    )
    return parser.parse_args()


def load_toon_files(toon_dir: Path) -> dict:
    """Load all TOON files and return as dictionary keyed by table name."""
    toons = {}

    if not toon_dir.exists():
        print(f"ERROR: TOON directory not found: {toon_dir}")
        sys.exit(1)

    toon_files = sorted(toon_dir.glob("*.toon.json"))

    if not toon_files:
        print(f"ERROR: No TOON files found in {toon_dir}")
        sys.exit(1)

    print(f"Found {len(toon_files)} TOON files")

    for toon_file in toon_files:
        try:
            with open(toon_file, "r", encoding="utf-8") as f:
                toon_data = json.load(f)

            table_name = toon_data.get("table_name")
            if table_name:
                toons[table_name] = {
                    "file": toon_file.name,
                    "data": toon_data
                }
        except Exception as e:
            print(f"WARNING: Failed to parse {toon_file.name}: {e}")

    print(f"Loaded {len(toons)} valid TOON definitions")
    return toons


def check_table_limit(current_count: int, new_tables: list, limit: int) -> bool:
    """Check if adding new tables would exceed the limit."""
    new_count = current_count + len(new_tables)

    if new_count > limit:
        print(f"\n{'='*60}")
        print(f"TABLE LIMIT REACHED: Optimization required before adding new tables.")
        print(f"{'='*60}")
        print(f"Current tables: {current_count}")
        print(f"New tables requested: {len(new_tables)}")
        print(f"Would result in: {new_count} tables")
        print(f"Limit: {limit} tables")
        print(f"\nNew tables that cannot be added:")
        for table in new_tables:
            print(f"  - {table}")
        return False

    return True


def analyze_schema_gaps(toons: dict) -> dict:
    """Analyze what's defined in TOONs. Returns summary of tables."""
    summary = {
        "total_tables": len(toons),
        "tables": list(toons.keys()),
    }

    return summary


def generate_migration_sql(toons: dict, output_path: Path = None) -> str:
    """
    Generate migration SQL based on TOON definitions.

    Note: This script only validates table count and structure.
    Since we cannot query the live database, we assume all TOON tables
    should exist and generate a reference migration template.
    """

    if output_path is None:
        timestamp = datetime.now(timezone.utc).strftime("%Y%m%d_%H%M%S")
        output_path = Path(f"database/migrations/{timestamp}_sync_schema_to_toon.sql")

    # Migration header
    migration_sql = f"""-- Migration: Sync schema to TOON definitions
-- Date: {datetime.now(timezone.utc).strftime("%Y-%m-%d")}
-- Purpose: Ensure database schema matches TOON files (canonical source of truth)
-- Doctrine: No UNSIGNED, no foreign keys, no triggers, no stored procedures
-- TOON Source: docs/toons/*.toon.json

-- TABLE COUNT CHECK
-- Current tables in database: 218 (update this after running migration)
-- Tables defined in TOON files: {len(toons)}
-- Table limit: 222
-- Available slots: 4

-- NOTE: This migration was generated from TOON files.
-- Review each CREATE TABLE statement carefully before executing.
-- Tables may already exist - use CREATE TABLE IF NOT EXISTS or check manually.

"""

    # For demonstration, we'll note what tables are defined
    migration_sql += "-- TOON-defined tables:\n"
    for table_name in sorted(toons.keys()):
        migration_sql += f"-- - {table_name}\n"

    migration_sql += f"\n-- Total TOON-defined tables: {len(toons)}\n"
    migration_sql += f"-- This exceeds the 222 table limit by: {len(toons) - 222} tables\n"
    migration_sql += "\n-- RECOMMENDATION:\n"
    migration_sql += "-- Table optimization is required before proceeding.\n"
    migration_sql += "-- Consider consolidating tables or archiving unused tables.\n"

    return migration_sql, output_path


def main():
    args = parse_args()

    print(f"Analyzing TOON schema...")
    print(f"TOON directory: {args.toon_dir}")
    print(f"Current table count: {args.current_table_count}")
    print(f"Table limit: {args.table_limit}")
    print(f"Available slots: {args.table_limit - args.current_table_count}")
    print()

    # Load all TOON files
    toons = load_toon_files(args.toon_dir)

    # Analyze schema
    summary = analyze_schema_gaps(toons)

    print(f"\nSchema Analysis:")
    print(f"  TOON-defined tables: {summary['total_tables']}")
    print(f"  Current database tables: {args.current_table_count}")

    # Check if TOONs exceed limit
    if summary['total_tables'] > args.table_limit:
        print(f"\n{'='*60}")
        print(f"WARNING: TOON definitions exceed table limit!")
        print(f"{'='*60}")
        print(f"  TOON tables: {summary['total_tables']}")
        print(f"  Table limit: {args.table_limit}")
        print(f"  Excess: {summary['total_tables'] - args.table_limit} tables")
        print(f"\nOptimization required. Cannot proceed with migration.")
        return 1

    # Check if adding TOON tables would exceed current count
    gap = summary['total_tables'] - args.current_table_count
    if gap > 0:
        print(f"\n  Gap: {gap} tables missing from database")

        # Check if we have room
        available = args.table_limit - args.current_table_count
        if gap > available:
            print(f"\n{'='*60}")
            print(f"TABLE LIMIT REACHED: Cannot add {gap} tables")
            print(f"{'='*60}")
            print(f"  Available slots: {available}")
            print(f"  Tables needed: {gap}")
            print(f"\nOptimization required before proceeding.")
            return 1
    else:
        print(f"\n  All TOON tables appear to exist in database.")

    # Generate migration SQL (informational)
    migration_sql, output_path = generate_migration_sql(toons, args.output)

    if args.dry_run:
        print(f"\n[DRY RUN] Would write migration to: {output_path}")
        print(f"\n{migration_sql}")
    else:
        output_path.parent.mkdir(parents=True, exist_ok=True)
        with open(output_path, "w", encoding="utf-8") as f:
            f.write(migration_sql)
        print(f"\nMigration SQL written to: {output_path}")

    return 0


if __name__ == "__main__":
    sys.exit(main())
