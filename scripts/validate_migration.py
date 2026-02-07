#!/usr/bin/env python3
"""
Migration Validation Script
Validates generated migration files for syntax and doctrine compliance.

Usage:
    python validate_migration.py [migration_file]

This script:
1. Validates SQL syntax
2. Checks doctrine compliance (no FK, BIGINT timestamps, etc.)
3. Verifies table structure consistency
4. Reports potential issues before execution
"""

import re
import sys
from pathlib import Path
from typing import Dict, List, Optional, Set, Tuple


class MigrationValidator:
    def __init__(self, migration_file: str):
        self.migration_file = Path(migration_file)
        self.content = ""
        self.tables_found: List[str] = []
        self.issues: List[Dict[str, str]] = []
        self.warnings: List[Dict[str, str]] = []

        # Doctrine rules
        self.forbidden_keywords = [
            "FOREIGN KEY",
            "REFERENCES",
            "ON DELETE",
            "ON UPDATE",
            "CONSTRAINT",
            "TRIGGER",
            "PROCEDURE",
            "FUNCTION",
        ]

        self.required_patterns = {
            "bigint_timestamps": r"`\w*_ymdhis`\s+bigint",
            "primary_key_naming": r"PRIMARY KEY \(`\w+_id`\)",
            "innodb_engine": r"ENGINE=InnoDB",
            "utf8mb4_charset": r"CHARSET=utf8mb4",
        }

    def load_migration_file(self) -> bool:
        """Load and parse the migration file."""
        try:
            with open(self.migration_file, "r", encoding="utf-8") as f:
                self.content = f.read()
            return True
        except FileNotFoundError:
            self.add_issue(
                "critical", f"Migration file not found: {self.migration_file}"
            )
            return False
        except Exception as e:
            self.add_issue("critical", f"Failed to read migration file: {e}")
            return False

    def add_issue(self, severity: str, message: str, table: str = None) -> None:
        """Add an issue to the issues list."""
        self.issues.append(
            {
                "severity": severity,
                "message": message,
                "table": table or "general",
                "type": "error" if severity == "critical" else "warning",
            }
        )

    def add_warning(self, message: str, table: str = None) -> None:
        """Add a warning to the warnings list."""
        self.warnings.append({"message": message, "table": table or "general"})

    def extract_tables(self) -> None:
        """Extract all CREATE TABLE statements and table names."""
        create_table_pattern = r"CREATE TABLE `([^`]+)`"
        matches = re.findall(create_table_pattern, self.content, re.IGNORECASE)
        self.tables_found = matches

    def validate_doctrine_compliance(self) -> None:
        """Check for doctrine violations."""
        print("🔍 Validating doctrine compliance...")

        # Check for forbidden keywords (but not in comments)
        for keyword in self.forbidden_keywords:
            # Remove comments before checking
            content_no_comments = re.sub(
                r"--.*?$", "", self.content, flags=re.MULTILINE
            )
            content_no_comments = re.sub(
                r"/\*.*?\*/", "", content_no_comments, flags=re.DOTALL
            )

            if re.search(keyword, content_no_comments, re.IGNORECASE):
                self.add_issue("critical", f"Forbidden keyword found: {keyword}")

        # Check for required patterns
        for pattern_name, pattern in self.required_patterns.items():
            matches = re.findall(pattern, self.content, re.IGNORECASE)
            if not matches and pattern_name in ["innodb_engine", "utf8mb4_charset"]:
                self.add_issue("critical", f"Required pattern missing: {pattern_name}")

        print(f"   ✅ Checked {len(self.forbidden_keywords)} forbidden patterns")
        print(f"   ✅ Verified {len(self.required_patterns)} required patterns")

    def validate_table_structure(self) -> None:
        """Validate individual table structures."""
        print(f"🏗️  Validating {len(self.tables_found)} table structures...")

        for table_name in self.tables_found:
            self.validate_single_table(table_name)

        print(f"   ✅ Validated {len(self.tables_found)} tables")

    def validate_single_table(self, table_name: str) -> None:
        """Validate a single table's structure."""
        # Extract table definition
        table_pattern = rf"CREATE TABLE `{re.escape(table_name)}`\s*\((.*?)\)\s*ENGINE"
        table_match = re.search(table_pattern, self.content, re.IGNORECASE | re.DOTALL)

        if not table_match:
            self.add_issue("critical", f"Could not parse table definition", table_name)
            return

        table_def = table_match.group(1)

        # Check primary key naming convention
        pk_pattern = (
            rf"PRIMARY KEY \(`{re.escape(table_name[5:])}_id`\)"
            if table_name.startswith("lupo_")
            else rf"PRIMARY KEY \(`\w+_id`\)"
        )
        if not re.search(pk_pattern, table_def, re.IGNORECASE):
            # Check for any primary key
            if not re.search(r"PRIMARY KEY", table_def, re.IGNORECASE):
                self.add_issue("high", f"No primary key found", table_name)
            else:
                self.add_warning(
                    f"Primary key naming may not follow convention", table_name
                )

        # Check for timestamp fields
        if not re.search(r"`\w*_ymdhis`\s+bigint", table_def, re.IGNORECASE):
            self.add_warning(f"No BIGINT UTC timestamp field found", table_name)

        # Check for AUTO_INCREMENT on non-BIGINT
        auto_inc_pattern = r"`\w+`\s+(int|smallint|mediumint)\s+[^,]*auto_increment"
        if re.search(auto_inc_pattern, table_def, re.IGNORECASE):
            self.add_issue("medium", f"AUTO_INCREMENT on non-BIGINT field", table_name)

    def validate_sql_syntax(self) -> None:
        """Basic SQL syntax validation."""
        print("⚙️  Validating SQL syntax...")

        # Check for matching parentheses
        open_parens = self.content.count("(")
        close_parens = self.content.count(")")
        if open_parens != close_parens:
            self.add_issue(
                "critical",
                f"Mismatched parentheses: {open_parens} open, {close_parens} close",
            )

        # Check for transaction boundaries
        if "START TRANSACTION;" not in self.content:
            self.add_issue("medium", "No transaction start found")

        if "COMMIT;" not in self.content:
            self.add_issue("medium", "No transaction commit found")

        # Check for missing semicolons on CREATE TABLE
        create_tables = re.findall(
            r"CREATE TABLE.*?\) ENGINE=InnoDB[^;]*",
            self.content,
            re.IGNORECASE | re.DOTALL,
        )
        for i, table_def in enumerate(create_tables):
            if not table_def.strip().endswith(";"):
                self.add_issue(
                    "high", f"Missing semicolon on CREATE TABLE statement #{i + 1}"
                )

        print(f"   ✅ Basic syntax checks completed")

    def validate_lupopedia_conventions(self) -> None:
        """Validate Lupopedia-specific conventions."""
        print("🐺 Validating Lupopedia conventions...")

        # Check table naming
        for table_name in self.tables_found:
            if table_name.startswith("lupo_"):
                # Lupo tables should follow naming convention
                if not re.match(r"^lupo_[a-z_]+$", table_name):
                    self.add_warning(
                        f"Table name may not follow lupo_ convention", table_name
                    )
            elif table_name.startswith("livehelp_"):
                # Legacy livehelp tables are acceptable
                pass
            else:
                # Other table naming patterns
                if not re.match(r"^[a-z][a-z0-9_]*$", table_name):
                    self.add_warning(
                        f"Table name contains unusual characters", table_name
                    )

        print(f"   ✅ Convention checks completed")

    def generate_report(self) -> str:
        """Generate a comprehensive validation report."""
        report = []
        report.append("=" * 60)
        report.append("LUPOPEDIA MIGRATION VALIDATION REPORT")
        report.append("=" * 60)
        report.append(f"File: {self.migration_file}")
        report.append(f"Tables found: {len(self.tables_found)}")
        report.append("")

        # Summary
        critical_issues = [i for i in self.issues if i["severity"] == "critical"]
        high_issues = [i for i in self.issues if i["severity"] == "high"]
        medium_issues = [i for i in self.issues if i["severity"] == "medium"]

        report.append("📊 VALIDATION SUMMARY:")
        report.append(f"   🚨 Critical issues: {len(critical_issues)}")
        report.append(f"   ⚠️  High issues:     {len(high_issues)}")
        report.append(f"   ℹ️  Medium issues:   {len(medium_issues)}")
        report.append(f"   💡 Warnings:        {len(self.warnings)}")
        report.append("")

        # Critical issues
        if critical_issues:
            report.append("🚨 CRITICAL ISSUES (Must fix before execution):")
            for issue in critical_issues:
                table_info = (
                    f" [{issue['table']}]" if issue["table"] != "general" else ""
                )
                report.append(f"   • {issue['message']}{table_info}")
            report.append("")

        # High issues
        if high_issues:
            report.append("⚠️ HIGH PRIORITY ISSUES:")
            for issue in high_issues:
                table_info = (
                    f" [{issue['table']}]" if issue["table"] != "general" else ""
                )
                report.append(f"   • {issue['message']}{table_info}")
            report.append("")

        # Medium issues
        if medium_issues:
            report.append("ℹ️ MEDIUM PRIORITY ISSUES:")
            for issue in medium_issues:
                table_info = (
                    f" [{issue['table']}]" if issue["table"] != "general" else ""
                )
                report.append(f"   • {issue['message']}{table_info}")
            report.append("")

        # Warnings
        if self.warnings:
            report.append("💡 WARNINGS (Review recommended):")
            for warning in self.warnings:
                table_info = (
                    f" [{warning['table']}]" if warning["table"] != "general" else ""
                )
                report.append(f"   • {warning['message']}{table_info}")
            report.append("")

        # Success message
        if not critical_issues and not high_issues:
            report.append("✅ VALIDATION PASSED!")
            report.append("   Migration file appears safe for execution.")
            if medium_issues or self.warnings:
                report.append("   Consider reviewing medium issues and warnings.")
        else:
            report.append("❌ VALIDATION FAILED!")
            report.append("   Fix critical and high issues before execution.")

        report.append("")
        report.append("📋 TABLE LIST:")
        for i, table in enumerate(self.tables_found, 1):
            report.append(f"   {i:2d}. {table}")

        return "\n".join(report)

    def validate(self) -> bool:
        """Run complete validation."""
        print(f"🚀 Starting migration validation: {self.migration_file.name}")
        print("=" * 50)

        # Load file
        if not self.load_migration_file():
            return False

        # Extract tables
        self.extract_tables()
        print(f"📋 Found {len(self.tables_found)} tables to validate")

        # Run validations
        self.validate_sql_syntax()
        self.validate_doctrine_compliance()
        self.validate_table_structure()
        self.validate_lupopedia_conventions()

        # Generate report
        print("\n📋 Validation Report:")
        print(self.generate_report())

        # Return success status
        critical_issues = [i for i in self.issues if i["severity"] == "critical"]
        return len(critical_issues) == 0


def main():
    """Main entry point."""
    if len(sys.argv) < 2:
        print("Usage: python validate_migration.py <migration_file>")
        print("\nExample:")
        print(
            "  python validate_migration.py database/migrations/20260120110914_add_missing_toon_tables.sql"
        )
        return 1

    migration_file = sys.argv[1]

    try:
        validator = MigrationValidator(migration_file)
        success = validator.validate()

        return 0 if success else 1

    except Exception as e:
        print(f"❌ Validation failed with error: {e}")
        return 1


if __name__ == "__main__":
    exit(main())
