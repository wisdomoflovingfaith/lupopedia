#!/usr/bin/env python3
"""
Migration Syntax Tester
Tests migration file syntax by extracting and validating individual CREATE TABLE statements.

Usage:
    python test_migration_syntax.py <migration_file>

This script:
1. Extracts each CREATE TABLE statement from the migration file
2. Tests basic SQL syntax validation
3. Identifies any actual syntax errors vs false positives
4. Provides a clean assessment of migration readiness
"""

import re
import sys
from pathlib import Path
from typing import Dict, List, Tuple


class MigrationSyntaxTester:
    def __init__(self, migration_file: str):
        self.migration_file = Path(migration_file)
        self.content = ""
        self.create_table_statements: List[Dict] = []
        self.syntax_errors: List[Dict] = []
        self.warnings: List[Dict] = []

    def load_migration_file(self) -> bool:
        """Load the migration file."""
        try:
            with open(self.migration_file, "r", encoding="utf-8") as f:
                self.content = f.read()
            return True
        except FileNotFoundError:
            print(f"❌ Migration file not found: {self.migration_file}")
            return False
        except Exception as e:
            print(f"❌ Error reading file: {e}")
            return False

    def extract_create_table_statements(self) -> None:
        """Extract all CREATE TABLE statements from the migration."""
        # Pattern to match CREATE TABLE statements
        pattern = r"CREATE TABLE `([^`]+)`\s*\((.*?)\)\s*ENGINE=InnoDB[^;]*;"

        matches = re.findall(pattern, self.content, re.DOTALL | re.IGNORECASE)

        for table_name, table_definition in matches:
            self.create_table_statements.append(
                {
                    "table_name": table_name,
                    "definition": table_definition.strip(),
                    "full_statement": f"CREATE TABLE `{table_name}` ({table_definition.strip()}) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",
                }
            )

    def validate_table_syntax(self, table_info: Dict) -> List[str]:
        """Validate syntax of a single CREATE TABLE statement."""
        errors = []
        table_name = table_info["table_name"]
        definition = table_info["definition"]

        # Check for balanced parentheses in the definition
        open_count = definition.count("(")
        close_count = definition.count(")")
        if open_count != close_count:
            errors.append(
                f"Mismatched parentheses: {open_count} open, {close_count} close"
            )

        # Check for primary key presence
        if "PRIMARY KEY" not in definition.upper():
            # This might be acceptable for some legacy tables
            self.warnings.append(
                {
                    "table": table_name,
                    "message": "No PRIMARY KEY found - may be intentional for legacy tables",
                }
            )

        # Check field definitions are properly formatted
        lines = [line.strip() for line in definition.split("\n") if line.strip()]
        for i, line in enumerate(lines):
            if (
                line.startswith("`")
                and not line.endswith(",")
                and not line.startswith("PRIMARY KEY")
                and not line.startswith("UNIQUE KEY")
                and not line.startswith("KEY")
            ):
                if i < len(lines) - 1:  # Not the last line
                    next_line = lines[i + 1]
                    if (
                        not next_line.startswith("PRIMARY KEY")
                        and not next_line.startswith("UNIQUE KEY")
                        and not next_line.startswith("KEY")
                    ):
                        errors.append(f"Field definition missing comma: {line[:50]}...")

        return errors

    def check_doctrine_compliance(self, table_info: Dict) -> List[str]:
        """Check for actual doctrine violations (not false positives)."""
        violations = []
        definition = table_info["definition"]

        # Check for actual foreign key constraints (not just the word in comments)
        fk_pattern = r"FOREIGN\s+KEY\s*\([^)]+\)\s+REFERENCES"
        if re.search(fk_pattern, definition, re.IGNORECASE):
            violations.append("Contains actual FOREIGN KEY constraint")

        # Check for actual constraint definitions
        constraint_pattern = r"CONSTRAINT\s+\w+\s+FOREIGN"
        if re.search(constraint_pattern, definition, re.IGNORECASE):
            violations.append("Contains actual CONSTRAINT with FOREIGN KEY")

        return violations

    def run_tests(self) -> bool:
        """Run all syntax tests."""
        print(f"🧪 Testing migration syntax: {self.migration_file.name}")
        print("=" * 50)

        if not self.load_migration_file():
            return False

        # Extract CREATE TABLE statements
        self.extract_create_table_statements()
        print(
            f"📋 Extracted {len(self.create_table_statements)} CREATE TABLE statements"
        )

        if not self.create_table_statements:
            print("❌ No CREATE TABLE statements found!")
            return False

        # Test each table
        all_passed = True
        for table_info in self.create_table_statements:
            table_name = table_info["table_name"]

            # Test syntax
            syntax_errors = self.validate_table_syntax(table_info)
            if syntax_errors:
                all_passed = False
                self.syntax_errors.append(
                    {"table": table_name, "errors": syntax_errors}
                )

            # Test doctrine compliance
            doctrine_violations = self.check_doctrine_compliance(table_info)
            if doctrine_violations:
                all_passed = False
                self.syntax_errors.append(
                    {"table": table_name, "errors": doctrine_violations}
                )

        # Report results
        self.print_results()

        return all_passed

    def print_results(self) -> None:
        """Print test results."""
        print("\n📊 SYNTAX TEST RESULTS:")
        print("=" * 40)

        if not self.syntax_errors and not self.warnings:
            print("✅ ALL TESTS PASSED!")
            print("   • No syntax errors found")
            print("   • No doctrine violations detected")
            print("   • Migration appears ready for execution")
        else:
            if self.syntax_errors:
                print("🚨 SYNTAX ERRORS FOUND:")
                for error_info in self.syntax_errors:
                    table = error_info["table"]
                    print(f"\n   Table: {table}")
                    for error in error_info["errors"]:
                        print(f"   ❌ {error}")

            if self.warnings:
                print(f"\n💡 WARNINGS ({len(self.warnings)}):")
                for warning in self.warnings:
                    print(f"   ⚠️  {warning['table']}: {warning['message']}")

        print(f"\n📋 SUMMARY:")
        print(f"   • Tables tested: {len(self.create_table_statements)}")
        print(f"   • Syntax errors: {len(self.syntax_errors)}")
        print(f"   • Warnings: {len(self.warnings)}")

        if not self.syntax_errors:
            print(f"\n🎯 RECOMMENDATION:")
            print(f"   Migration file syntax is valid and ready for execution.")
            if self.warnings:
                print(f"   Warnings are informational and don't prevent execution.")
        else:
            print(f"\n🔧 ACTION REQUIRED:")
            print(f"   Fix syntax errors before executing migration.")

    def get_sample_tables(self, count: int = 5) -> None:
        """Print sample table definitions for manual inspection."""
        print(f"\n🔍 SAMPLE TABLE DEFINITIONS (first {count}):")
        print("=" * 50)

        for i, table_info in enumerate(self.create_table_statements[:count]):
            table_name = table_info["table_name"]
            definition_lines = table_info["definition"].split("\n")[:5]  # First 5 lines

            print(f"\n{i + 1}. {table_name}:")
            for line in definition_lines:
                if line.strip():
                    print(f"   {line.strip()}")
            if len(table_info["definition"].split("\n")) > 5:
                print("   ...")


def main():
    """Main entry point."""
    if len(sys.argv) < 2:
        print("Usage: python test_migration_syntax.py <migration_file>")
        print("\nExample:")
        print(
            "  python test_migration_syntax.py database/migrations/20260120111220_add_missing_toon_tables_clean.sql"
        )
        return 1

    migration_file = sys.argv[1]

    try:
        tester = MigrationSyntaxTester(migration_file)
        success = tester.run_tests()

        # Show sample tables for manual inspection
        if len(sys.argv) > 2 and sys.argv[2] == "--samples":
            tester.get_sample_tables()

        return 0 if success else 1

    except Exception as e:
        print(f"❌ Test failed with error: {e}")
        import traceback

        traceback.print_exc()
        return 1


if __name__ == "__main__":
    exit(main())
