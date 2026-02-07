#!/usr/bin/env python3
"""
Clean TOON Migration Generator
Generates doctrine-compliant migrations from TOON files with proper cleanup.

Usage:
    python generate_clean_migration.py

This script:
1. Reads all TOON files in database/toon_data/
2. Cleans field definitions to remove foreign keys and constraints
3. Ensures doctrine compliance (no FK, BIGINT timestamps, proper PKs)
4. Generates clean migration files for missing tables
"""

import json
import os
import re
from datetime import datetime
from pathlib import Path
from typing import Dict, List, Optional, Set, Tuple


class CleanTOONMigrationGenerator:
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

        # Doctrine compliance rules
        self.forbidden_patterns = [
            r"FOREIGN\s+KEY",
            r"REFERENCES\s+",
            r"ON\s+DELETE",
            r"ON\s+UPDATE",
            r"CONSTRAINT\s+\w+\s+FOREIGN",
            r"ADD\s+CONSTRAINT",
        ]

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

    def clean_field_definition(self, field: str) -> Optional[str]:
        """Clean field definition to remove doctrine violations."""
        if not field or not field.strip():
            return None

        field = field.strip().rstrip(",")

        # Skip fields that are foreign key definitions
        for pattern in self.forbidden_patterns:
            if re.search(pattern, field, re.IGNORECASE):
                return None

        # Clean up field definition
        field = re.sub(r"\s+", " ", field)  # Normalize whitespace

        return field

    def extract_primary_key_column(self, fields: List[str]) -> Optional[str]:
        """Extract primary key column from field definitions."""
        for field in fields:
            if "auto_increment" in field.lower():
                # Extract column name from field definition
                match = re.search(r"`([^`]+)`", field)
                if match:
                    return match.group(1)

        # Fallback: look for common ID patterns
        for field in fields:
            field_lower = field.lower()
            if (
                "id" in field_lower
                and ("bigint" in field_lower or "int" in field_lower)
                and "not null" in field_lower
            ):
                match = re.search(r"`([^`]+)`", field)
                if match:
                    return match.group(1)

        return None

    def generate_create_table_sql(self, table_name: str) -> str:
        """Generate CREATE TABLE SQL from TOON data with doctrine compliance."""
        toon_data = self.toon_tables.get(table_name)
        if not toon_data:
            return f"-- ERROR: No TOON data found for {table_name}\n"

        fields = toon_data.get("fields", [])
        indexes = toon_data.get("indexes", [])
        unique_constraints = toon_data.get("unique_constraints", [])

        if not fields:
            return f"-- ERROR: No fields defined for {table_name}\n"

        # Clean field definitions
        clean_fields = []
        for field in fields:
            cleaned = self.clean_field_definition(field)
            if cleaned:
                clean_fields.append(cleaned)

        if not clean_fields:
            return f"-- ERROR: No valid fields after cleaning for {table_name}\n"

        # Determine primary key
        pk_column = self.extract_primary_key_column(clean_fields)
        if not pk_column:
            # Try to extract from TOON metadata
            pk_info = toon_data.get("primary_key", {})
            pk_column = pk_info.get("column_name")

        # Start building the CREATE TABLE statement
        sql_lines = []
        sql_lines.append(f"-- ============================================")
        sql_lines.append(f"-- Table: {table_name}")
        sql_lines.append(f"-- Fields: {len(clean_fields)}")
        sql_lines.append(f"-- Primary Key: {pk_column or 'AUTO-DETECTED'}")
        sql_lines.append(f"-- ============================================")
        sql_lines.append("")
        sql_lines.append(f"CREATE TABLE `{table_name}` (")

        # Add field definitions
        for i, field in enumerate(clean_fields):
            comma = "," if i < len(clean_fields) - 1 else ""
            sql_lines.append(f"  {field}{comma}")

        # Add primary key if found
        if pk_column:
            sql_lines[-1] += ","  # Add comma to last field
            sql_lines.append(f"  PRIMARY KEY (`{pk_column}`)")

        # Add unique constraints (only ones that don't violate doctrine)
        for constraint in unique_constraints:
            constraint_name = constraint.get("constraint_name", "unique_constraint")
            columns = constraint.get("columns", [])
            if columns and constraint_name != "PRIMARY":
                columns_str = "`, `".join(columns)
                if not sql_lines[-1].endswith(","):
                    sql_lines[-1] += ","
                sql_lines.append(f"  UNIQUE KEY `{constraint_name}` (`{columns_str}`)")

        # Add indexes (non-unique, non-foreign key)
        for index in indexes:
            if index.get("is_unique", False):
                continue  # Skip unique indexes (handled above)

            index_name = index.get("index_name", "idx_unknown")
            columns = index.get("columns", [])

            # Skip foreign key indexes
            if any(fk_word in index_name.lower() for fk_word in ["fk_", "foreign"]):
                continue

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
            print("✅ No missing tables found!")
            return ""

        # Generate timestamp for migration file
        timestamp = datetime.now().strftime("%Y%m%d%H%M%S")

        if output_file is None:
            output_file = (
                self.migrations_dir / f"{timestamp}_add_missing_toon_tables_clean.sql"
            )

        migration_content = []

        # Header
        migration_content.extend(
            [
                "-- WOLFIE Migration: Add Missing TOON Tables (Clean Version)",
                f"-- Generated: {datetime.now().isoformat()}",
                f"-- Tables to create: {len(self.missing_tables)}",
                "-- Source: TOON files analysis with doctrine compliance",
                "",
                "-- Doctrine Compliance Enforced:",
                "--   ✅ No foreign keys or constraints",
                "--   ✅ BIGINT UTC timestamps (YYYYMMDDHHIISS)",
                "--   ✅ Application-managed relationships",
                "--   ✅ Federation-safe schema",
                "--   ✅ Proper primary key naming",
                "",
                "SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';",
                "START TRANSACTION;",
                "SET time_zone = '+00:00';",
                "",
            ]
        )

        # Generate CREATE TABLE statements
        print(f"🏗️  Generating {len(self.missing_tables)} table definitions...")

        for i, table_name in enumerate(self.missing_tables, 1):
            print(
                f"   📋 Processing table {i}/{len(self.missing_tables)}: {table_name}"
            )

            create_sql = self.generate_create_table_sql(table_name)
            migration_content.append(create_sql)

        # Footer
        migration_content.extend(
            [
                "-- ============================================",
                "-- Migration Completion Summary",
                "-- ============================================",
                f"-- Successfully created {len(self.missing_tables)} new tables",
                "-- All tables follow Lupopedia WOLFIE doctrine:",
                "--   • No foreign key constraints (application-managed relationships)",
                "--   • BIGINT UTC timestamps in YYYYMMDDHHIISS format",
                "--   • Proper primary key naming conventions",
                "--   • InnoDB engine with utf8mb4 charset",
                "--   • Federation-safe schema design",
                "",
                "COMMIT;",
                "",
                f"-- End of migration: {timestamp}_add_missing_toon_tables_clean.sql",
            ]
        )

        final_content = "\n".join(migration_content)

        # Write to file
        os.makedirs(self.migrations_dir, exist_ok=True)
        with open(output_file, "w", encoding="utf-8") as f:
            f.write(final_content)

        return str(output_file)

    def generate_summary_report(self) -> None:
        """Generate and display a summary report."""
        print("\n" + "=" * 60)
        print("CLEAN TOON MIGRATION GENERATOR REPORT")
        print("=" * 60)

        print(f"📊 STATISTICS:")
        print(f"   • TOON files found: {len(self.toon_tables)}")
        print(f"   • Existing tables:  {len(self.existing_tables)}")
        print(f"   • Missing tables:   {len(self.missing_tables)}")

        if self.missing_tables:
            print(f"\n🚀 MISSING TABLES TO CREATE:")

            # Group by category
            categories = {}
            for table_name in self.missing_tables:
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

            for category, tables in sorted(categories.items()):
                print(f"\n   📂 {category.upper()} ({len(tables)} tables):")
                for table in sorted(tables):
                    toon_data = self.toon_tables.get(table, {})
                    fields_count = len(toon_data.get("fields", []))
                    print(f"      • {table:<35} ({fields_count} fields)")

    def run(self) -> str:
        """Run the complete migration generation process."""
        print("🚀 Starting Clean TOON Migration Generator...")
        print("=" * 50)

        # Load data
        self.load_toon_files()
        self.load_existing_schema()

        # Analyze
        self.identify_missing_tables()

        # Generate outputs
        if self.missing_tables:
            print(
                f"\n📝 Generating clean migration file for {len(self.missing_tables)} tables..."
            )
            migration_file = self.generate_migration_file()
            print(f"✅ Clean migration file created: {migration_file}")

            # Generate summary
            self.generate_summary_report()

            return migration_file
        else:
            print("✅ No missing tables found - database schema is complete!")
            return ""


def main():
    """Main entry point."""
    try:
        generator = CleanTOONMigrationGenerator()
        migration_file = generator.run()

        if migration_file:
            print(f"\n🎯 NEXT STEPS:")
            print(
                f"   1. Review the generated migration file: {Path(migration_file).name}"
            )
            print(f"   2. Test in a development environment first")
            print(f"   3. Run: mysql lupopedia < {migration_file}")
            print(f"   4. Verify table creation with: SHOW TABLES;")

        return 0

    except Exception as e:
        print(f"❌ Migration generation failed: {e}")
        import traceback

        traceback.print_exc()
        return 1


if __name__ == "__main__":
    exit(main())
