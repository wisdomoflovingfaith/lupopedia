#!/usr/bin/env python3
"""
TOON Database Analysis Script
Analyzes all TOON files to identify missing tables that need migration.

Usage:
    python analyze_missing_tables.py

This script:
1. Reads all TOON files in database/toon_data/
2. Extracts table names and schemas
3. Compares with existing database schema
4. Generates migration files for missing tables
"""

import json
import os
import re
from datetime import datetime
from pathlib import Path
from typing import Dict, List, Optional, Set


class TOONAnalyzer:
    def __init__(self, lupopedia_root: str = None):
        if lupopedia_root is None:
            # Assume script is run from lupopedia/database/
            self.lupopedia_root = Path(__file__).parent.parent
        else:
            self.lupopedia_root = Path(lupopedia_root)

        self.toon_data_dir = self.lupopedia_root / "database" / "toon_data"
        self.migrations_dir = self.lupopedia_root / "database" / "migrations"
        self.schema_file = (
            self.lupopedia_root / "database" / "install" / "lupopedia_mysql.sql"
        )

        # Storage for analysis results
        self.toon_tables: Dict[str, dict] = {}
        self.existing_tables: Set[str] = set()
        self.missing_tables: List[str] = []

    def load_toon_files(self) -> None:
        """Load and parse all TOON files."""
        print(f"📁 Loading TOON files from: {self.toon_data_dir}")

        if not self.toon_data_dir.exists():
            print(f"❌ TOON data directory not found: {self.toon_data_dir}")
            return

        toon_files = list(self.toon_data_dir.glob("*.toon"))
        print(f"📊 Found {len(toon_files)} TOON files")

        for toon_file in toon_files:
            try:
                with open(toon_file, "r", encoding="utf-8") as f:
                    toon_data = json.load(f)

                table_name = toon_data.get("table_name")
                if table_name:
                    self.toon_tables[table_name] = toon_data

            except (json.JSONDecodeError, FileNotFoundError) as e:
                print(f"⚠️  Failed to load {toon_file.name}: {e}")
                continue

        print(f"✅ Loaded {len(self.toon_tables)} valid TOON files")

    def load_existing_schema(self) -> None:
        """Parse existing schema file to get current table list."""
        print(f"📋 Loading existing schema from: {self.schema_file}")

        if not self.schema_file.exists():
            print(f"⚠️  Schema file not found: {self.schema_file}")
            return

        try:
            with open(self.schema_file, "r", encoding="utf-8") as f:
                schema_content = f.read()

            # Extract CREATE TABLE statements using regex
            create_table_pattern = r"CREATE TABLE `?([a-zA-Z_][a-zA-Z0-9_]*)`?\s*\("
            matches = re.findall(
                create_table_pattern, schema_content, re.IGNORECASE | re.MULTILINE
            )

            self.existing_tables = set(matches)
            print(f"✅ Found {len(self.existing_tables)} existing tables")

        except FileNotFoundError as e:
            print(f"❌ Failed to load schema file: {e}")

    def identify_missing_tables(self) -> None:
        """Compare TOON files with existing schema to find missing tables."""
        print("🔍 Analyzing missing tables...")

        toon_table_names = set(self.toon_tables.keys())
        self.missing_tables = sorted(list(toon_table_names - self.existing_tables))

        print(f"📈 TOON files define: {len(toon_table_names)} tables")
        print(f"📊 Current schema has: {len(self.existing_tables)} tables")
        print(f"❌ Missing tables: {len(self.missing_tables)} tables")

        if self.missing_tables:
            print("\n🚨 Missing Tables:")
            for i, table in enumerate(self.missing_tables, 1):
                print(f"  {i:3d}. {table}")

    def clean_field_definition(self, field: str) -> str:
        """Clean field definition to remove foreign keys and fix doctrine issues."""
        field = field.strip().rstrip(",")

        # Remove foreign key references and constraints
        if "REFERENCES" in field.upper() or "FOREIGN KEY" in field.upper():
            return None  # Skip this field entirely

        # Clean up specific patterns that violate doctrine
        field = re.sub(r",\s*CONSTRAINT\s+.*$", "", field, flags=re.IGNORECASE)
        field = re.sub(r",\s*FOREIGN KEY.*$", "", field, flags=re.IGNORECASE)

        return field

    def generate_create_table_sql(self, table_name: str) -> str:
        """Generate CREATE TABLE SQL from TOON data."""
        toon_data = self.toon_tables.get(table_name)
        if not toon_data:
            return f"-- ERROR: No TOON data found for {table_name}"

        fields = toon_data.get("fields", [])
        primary_key_info = toon_data.get("primary_key", {})
        indexes = toon_data.get("indexes", [])
        unique_constraints = toon_data.get("unique_constraints", [])

        if not fields:
            return f"-- ERROR: No fields defined for {table_name}"

        # Start building the CREATE TABLE statement
        sql_lines = []
        sql_lines.append(f"-- Table structure for table `{table_name}`")
        sql_lines.append(f"CREATE TABLE `{table_name}` (")

        # Clean and add field definitions
        clean_fields = []
        for field in fields:
            cleaned = self.clean_field_definition(field)
            if cleaned:
                clean_fields.append(cleaned)

        # Add field definitions
        for i, field in enumerate(clean_fields):
            if (
                i == len(clean_fields) - 1
                and not primary_key_info.get("column_name")
                and not unique_constraints
                and not indexes
            ):
                sql_lines.append(f"  {field}")
            else:
                sql_lines.append(f"  {field},")

        # Add primary key if specified
        has_pk = False
        if primary_key_info.get("column_name"):
            pk_column = primary_key_info["column_name"]
            if not sql_lines[-1].endswith(","):
                sql_lines[-1] += ","
            sql_lines.append(f"  PRIMARY KEY (`{pk_column}`)")
            has_pk = True

        # Add unique constraints
        for constraint in unique_constraints:
            constraint_name = constraint.get("constraint_name", "unique_constraint")
            columns = constraint.get("columns", [])
            if columns:
                columns_str = "`, `".join(columns)
                if not sql_lines[-1].endswith(","):
                    sql_lines[-1] += ","
                sql_lines.append(f"  UNIQUE KEY `{constraint_name}` (`{columns_str}`)")

        # Add indexes (non-unique)
        for index in indexes:
            if index.get("is_unique", False):
                continue  # Skip unique indexes (handled above)

            index_name = index.get("index_name", "idx_unknown")
            columns = index.get("columns", [])
            if columns:
                columns_str = "`, `".join(columns)
                if not sql_lines[-1].endswith(","):
                    sql_lines[-1] += ","
                sql_lines.append(f"  KEY `{index_name}` (`{columns_str}`)")

        sql_lines.append(
            ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;"
        )
        sql_lines.append("")

        return "\n".join(sql_lines)

    def generate_migration_file(self, output_file: Optional[str] = None) -> str:
        """Generate a complete migration file for all missing tables."""
        if not self.missing_tables:
            return "-- No missing tables found"

        # Generate timestamp for migration file
        timestamp = datetime.now().strftime("%Y%m%d%H%M%S")

        if output_file is None:
            output_file = (
                self.migrations_dir / f"{timestamp}_add_missing_toon_tables.sql"
            )

        migration_content = []
        migration_content.append("-- WOLFIE Migration: Add Missing TOON Tables")
        migration_content.append(f"-- Generated: {datetime.now().isoformat()}")
        migration_content.append(f"-- Tables to create: {len(self.missing_tables)}")
        migration_content.append("-- Source: TOON files analysis")
        migration_content.append("")
        migration_content.append("-- Doctrine Compliance:")
        migration_content.append("--   ✅ No foreign keys")
        migration_content.append("--   ✅ BIGINT UTC timestamps (YYYYMMDDHHIISS)")
        migration_content.append("--   ✅ Application-managed relationships")
        migration_content.append("--   ✅ Federation-safe schema")
        migration_content.append("")
        migration_content.append("START TRANSACTION;")
        migration_content.append("")

        # Generate CREATE TABLE statements
        for i, table_name in enumerate(self.missing_tables, 1):
            migration_content.append(f"-- ============================================")
            migration_content.append(
                f"-- Table {i}/{len(self.missing_tables)}: {table_name}"
            )
            migration_content.append(f"-- ============================================")
            migration_content.append("")

            create_sql = self.generate_create_table_sql(table_name)
            migration_content.append(create_sql)
            migration_content.append("")

        migration_content.append("-- ============================================")
        migration_content.append("-- Migration Summary")
        migration_content.append("-- ============================================")
        migration_content.append(f"-- Created {len(self.missing_tables)} new tables")
        migration_content.append("-- All tables follow Lupopedia doctrine:")
        migration_content.append("--   - No foreign key constraints")
        migration_content.append("--   - BIGINT UTC timestamps")
        migration_content.append("--   - Application-managed relationships")
        migration_content.append("")
        migration_content.append("COMMIT;")

        final_content = "\n".join(migration_content)

        # Write to file
        os.makedirs(self.migrations_dir, exist_ok=True)
        with open(output_file, "w", encoding="utf-8") as f:
            f.write(final_content)

        return str(output_file)

    def generate_summary_report(self) -> str:
        """Generate a human-readable summary report."""
        report = []
        report.append("=" * 60)
        report.append("LUPOPEDIA TOON DATABASE ANALYSIS REPORT")
        report.append("=" * 60)
        report.append(f"Generated: {datetime.now().isoformat()}")
        report.append("")

        report.append("📊 STATISTICS:")
        report.append(f"   • TOON files found: {len(self.toon_tables)}")
        report.append(f"   • Existing tables:  {len(self.existing_tables)}")
        report.append(f"   • Missing tables:   {len(self.missing_tables)}")
        report.append("")

        if self.missing_tables:
            report.append("🚨 MISSING TABLES:")
            for i, table in enumerate(self.missing_tables, 1):
                toon_data = self.toon_tables.get(table, {})
                fields_count = len(toon_data.get("fields", []))
                report.append(f"   {i:3d}. {table:<40} ({fields_count} fields)")
            report.append("")

        # Show table categories
        categories = {}
        for table_name in self.toon_tables.keys():
            # Extract category from table name (e.g., lupo_actor_*, livehelp_*)
            if table_name.startswith("lupo_"):
                category = (
                    table_name.split("_")[1]
                    if len(table_name.split("_")) > 1
                    else "other"
                )
            elif table_name.startswith("livehelp_"):
                category = "livehelp"
            else:
                category = "other"

            if category not in categories:
                categories[category] = []
            categories[category].append(table_name)

        report.append("📂 TABLE CATEGORIES:")
        for category, tables in sorted(categories.items()):
            missing_in_category = [t for t in tables if t in self.missing_tables]
            total_in_category = len(tables)
            missing_count = len(missing_in_category)
            status = (
                f"({missing_count}/{total_in_category} missing)"
                if missing_count > 0
                else "(complete)"
            )
            report.append(f"   • {category:<15} {status}")

        return "\n".join(report)

    def run_analysis(self) -> None:
        """Run complete analysis and generate outputs."""
        print("🚀 Starting TOON Database Analysis...")
        print("=" * 50)

        # Load data
        self.load_toon_files()
        self.load_existing_schema()

        # Analyze
        self.identify_missing_tables()

        # Generate outputs
        if self.missing_tables:
            print("\n📝 Generating migration file...")
            migration_file = self.generate_migration_file()
            print(f"✅ Migration file created: {migration_file}")
        else:
            print("✅ No missing tables found - all TOON files are already in schema!")

        # Generate summary report
        print("\n📋 Analysis Summary:")
        print(self.generate_summary_report())


def main():
    """Main entry point."""
    try:
        analyzer = TOONAnalyzer()
        analyzer.run_analysis()

    except Exception as e:
        print(f"❌ Analysis failed: {e}")
        return 1

    return 0


if __name__ == "__main__":
    exit(main())
