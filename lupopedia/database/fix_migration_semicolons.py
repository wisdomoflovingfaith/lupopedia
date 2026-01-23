#!/usr/bin/env python3
"""
Migration Semicolon Fixer
Fixes missing semicolons in migration files.

Usage:
    python fix_migration_semicolons.py <migration_file>

This script:
1. Reads a migration file
2. Fixes missing semicolons on CREATE TABLE statements
3. Writes the corrected version back
"""

import re
import sys
from pathlib import Path


def fix_migration_semicolons(migration_file: str) -> bool:
    """Fix missing semicolons in a migration file."""
    file_path = Path(migration_file)

    if not file_path.exists():
        print(f"❌ Migration file not found: {migration_file}")
        return False

    try:
        # Read the file
        with open(file_path, "r", encoding="utf-8") as f:
            content = f.read()

        # Fix missing semicolons on CREATE TABLE statements
        # Pattern: ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        # Should be: ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        pattern = r"(\) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci)(?![;\s]*\n)"

        # Count matches before fixing
        matches = re.findall(pattern, content)
        print(f"🔧 Found {len(matches)} CREATE TABLE statements missing semicolons")

        # Apply fix
        fixed_content = re.sub(pattern, r"\1;", content)

        # Write back to file
        with open(file_path, "w", encoding="utf-8") as f:
            f.write(fixed_content)

        print(f"✅ Fixed {len(matches)} missing semicolons in {file_path.name}")
        return True

    except Exception as e:
        print(f"❌ Error processing file: {e}")
        return False


def main():
    """Main entry point."""
    if len(sys.argv) < 2:
        print("Usage: python fix_migration_semicolons.py <migration_file>")
        print("\nExample:")
        print(
            "  python fix_migration_semicolons.py database/migrations/20260120111220_add_missing_toon_tables_clean.sql"
        )
        return 1

    migration_file = sys.argv[1]
    success = fix_migration_semicolons(migration_file)

    return 0 if success else 1


if __name__ == "__main__":
    exit(main())
