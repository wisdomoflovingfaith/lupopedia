#!/usr/bin/env python3
# wolfie.headers: explicit architecture with structured clarity for every file.
# file.last_modified_system_version: 2026.3.7.6
# file.channel: schema
"""
LiveHelp TOON Cleanup Script
Removes all legacy livehelp_ TOON files from Crafty Syntax 3.7.0 and recalculates true table count.

Usage:
    python cleanup_livehelp_toons.py

This script:
1. Identifies all livehelp_ TOON files
2. Removes them from the database/toon_data directory
3. Recalculates the true table count (TOON files vs existing schema)
4. Updates documentation with correct numbers
"""

import json
import os
import shutil
from datetime import datetime
from pathlib import Path
from typing import Dict, List, Set


class LiveHelpTOONCleanup:
    def __init__(self, lupopedia_root: str = None):
        if lupopedia_root is None:
            # Assume script is run from lupopedia/database/
            self.lupopedia_root = Path(__file__).parent.parent
        else:
            self.lupopedia_root = Path(lupopedia_root)

        self.toon_data_dir = self.lupopedia_root / "database" / "toon_data"
        self.backup_dir = self.lupopedia_root / "database" / "livehelp_backup"
        self.schema_file = (
            self.lupopedia_root / "database" / "install" / "lupopedia_mysql.sql"
        )

        # Storage for results
        self.all_toon_files: List[Path] = []
        self.livehelp_toon_files: List[Path] = []
        self.remaining_toon_files: List[Path] = []
        self.existing_tables: Set[str] = set()

    def scan_toon_files(self) -> None:
        """Scan all TOON files and identify livehelp_ files."""
        print("📁 Scanning TOON files...")

        if not self.toon_data_dir.exists():
            print(f"❌ TOON data directory not found: {self.toon_data_dir}")
            return

        self.all_toon_files = list(self.toon_data_dir.glob("*.toon"))
        print(f"📊 Found {len(self.all_toon_files)} total TOON files")

        # Separate livehelp files from others
        for toon_file in self.all_toon_files:
            if toon_file.name.startswith("livehelp_"):
                self.livehelp_toon_files.append(toon_file)
            else:
                self.remaining_toon_files.append(toon_file)

        print(f"🗑️  LiveHelp files to remove: {len(self.livehelp_toon_files)}")
        print(f"✅ Lupopedia files to keep: {len(self.remaining_toon_files)}")

    def backup_livehelp_files(self) -> None:
        """Backup livehelp files before deletion."""
        print(f"💾 Creating backup of livehelp files...")

        # Create backup directory
        os.makedirs(self.backup_dir, exist_ok=True)

        # Copy livehelp TOON files to backup
        for toon_file in self.livehelp_toon_files:
            backup_path = self.backup_dir / toon_file.name
            shutil.copy2(toon_file, backup_path)

        # Also backup any .txt files
        txt_files = list(self.toon_data_dir.glob("livehelp_*.txt"))
        for txt_file in txt_files:
            backup_path = self.backup_dir / txt_file.name
            shutil.copy2(txt_file, backup_path)

        print(
            f"✅ Backed up {len(self.livehelp_toon_files)} TOON files to {self.backup_dir}"
        )
        print(f"✅ Backed up {len(txt_files)} TXT files to {self.backup_dir}")

    def remove_livehelp_files(self) -> None:
        """Remove livehelp TOON files from the main directory."""
        print(f"🗑️  Removing {len(self.livehelp_toon_files)} livehelp TOON files...")

        removed_count = 0
        for toon_file in self.livehelp_toon_files:
            try:
                toon_file.unlink()
                removed_count += 1
                print(f"   ❌ Removed: {toon_file.name}")
            except FileNotFoundError:
                print(f"   ⚠️  Already gone: {toon_file.name}")
            except Exception as e:
                print(f"   🚨 Error removing {toon_file.name}: {e}")

        # Also remove corresponding .txt files
        txt_files = list(self.toon_data_dir.glob("livehelp_*.txt"))
        for txt_file in txt_files:
            try:
                txt_file.unlink()
                print(f"   ❌ Removed: {txt_file.name}")
            except Exception as e:
                print(f"   🚨 Error removing {txt_file.name}: {e}")

        print(f"✅ Successfully removed {removed_count} livehelp TOON files")

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
            import re

            create_table_pattern = r"CREATE TABLE `?([a-zA-Z_][a-zA-Z0-9_]*)`?\s*\("
            matches = re.findall(
                create_table_pattern, schema_content, re.IGNORECASE | re.MULTILINE
            )

            self.existing_tables = set(matches)
            print(f"✅ Found {len(self.existing_tables)} existing tables in schema")

        except FileNotFoundError as e:
            print(f"❌ Failed to load schema file: {e}")

    def calculate_true_table_count(self) -> Dict[str, int]:
        """Calculate the true table count after cleanup."""
        print("🧮 Calculating true table count...")

        # Load remaining TOON files to get table names
        remaining_table_names = set()
        for toon_file in self.remaining_toon_files:
            try:
                with open(toon_file, "r", encoding="utf-8") as f:
                    toon_data = json.load(f)
                    table_name = toon_data.get("table_name")
                    if table_name:
                        remaining_table_names.add(table_name)
            except Exception as e:
                print(f"⚠️  Failed to parse {toon_file.name}: {e}")

        # Calculate statistics
        total_toon_tables = len(remaining_table_names)
        existing_tables_count = len(self.existing_tables)
        missing_tables = remaining_table_names - self.existing_tables
        missing_count = len(missing_tables)

        stats = {
            "total_toon_tables": total_toon_tables,
            "existing_tables": existing_tables_count,
            "missing_tables": missing_count,
            "target_after_migration": total_toon_tables,
        }

        return stats

    def generate_report(self) -> str:
        """Generate comprehensive cleanup and table count report."""
        stats = self.calculate_true_table_count()

        report = []
        report.append("=" * 60)
        report.append("LIVEHELP TOON CLEANUP & TRUE TABLE COUNT REPORT")
        report.append("=" * 60)
        report.append(f"Generated: {datetime.now().isoformat()}")
        report.append("")

        report.append("🗑️  CLEANUP SUMMARY:")
        report.append(
            f"   • LiveHelp TOON files removed: {len(self.livehelp_toon_files)}"
        )
        report.append(f"   • Backup location: {self.backup_dir}")
        report.append(f"   • Remaining TOON files: {len(self.remaining_toon_files)}")
        report.append("")

        report.append("📊 TRUE TABLE COUNT (After LiveHelp Removal):")
        report.append(f"   • TOON files define: {stats['total_toon_tables']} tables")
        report.append(f"   • Current schema has: {stats['existing_tables']} tables")
        report.append(f"   • Missing tables: {stats['missing_tables']} tables")
        report.append(
            f"   • Target after migration: {stats['target_after_migration']} tables"
        )
        report.append("")

        report.append("🎯 DOCTRINE COMPLIANCE CHECK:")
        target_count = stats["target_after_migration"]
        if target_count <= 222:
            report.append(f"   ✅ COMPLIANT: {target_count} ≤ 222 (target)")
            report.append(f"   ✅ COMPLIANT: {target_count} ≤ 222 (maximum)")
            margin = 222 - target_count
            report.append(f"   📈 Safety margin: {margin} tables under target")
        elif target_count == 222:
            report.append(
                f"   ⚠️  AT MAXIMUM: {target_count} = 222 (hard ceiling reached)"
            )
        else:
            report.append(
                f"   🚨 VIOLATION: {target_count} > 222 (trigger Table Optimization Cycle)"
            )
            overage = target_count - 222
            report.append(f"   📉 Overage: {overage} tables over maximum")

        report.append("")
        report.append("📋 NEXT STEPS:")
        if stats["missing_tables"] > 0:
            report.append(f"   1. Review {stats['missing_tables']} missing tables")
            report.append(f"   2. Generate clean migration (excluding livehelp)")
            report.append(
                f"   3. Execute migration to reach {stats['target_after_migration']} tables"
            )
        else:
            report.append("   ✅ No missing tables - schema is complete!")

        report.append(
            f"   4. Update all documentation with true count: {stats['target_after_migration']}"
        )
        report.append("")

        # List removed livehelp tables
        report.append("🗑️  REMOVED LIVEHELP TABLES:")
        for i, toon_file in enumerate(sorted(self.livehelp_toon_files), 1):
            table_name = toon_file.stem  # filename without extension
            report.append(f"   {i:2d}. {table_name}")

        return "\n".join(report)

    def run_cleanup(self) -> Dict[str, int]:
        """Run the complete cleanup process."""
        print("🚀 Starting LiveHelp TOON Cleanup...")
        print("=" * 50)

        # Scan files
        self.scan_toon_files()

        if not self.livehelp_toon_files:
            print("✅ No livehelp files found - cleanup not needed!")
            return self.calculate_true_table_count()

        # Backup before removal
        self.backup_livehelp_files()

        # Remove livehelp files
        self.remove_livehelp_files()

        # Load existing schema
        self.load_existing_schema()

        # Generate report
        print("\n📋 Cleanup Report:")
        print(self.generate_report())

        return self.calculate_true_table_count()


def main():
    """Main entry point."""
    try:
        cleanup = LiveHelpTOONCleanup()
        stats = cleanup.run_cleanup()

        print(f"\n🎯 FINAL RESULT:")
        print(f"   True table count after cleanup: {stats['target_after_migration']}")
        print(
            f"   Doctrine compliance: {'✅ PASS' if stats['target_after_migration'] <= 200 else '🚨 FAIL'}"
        )

        return 0

    except Exception as e:
        print(f"❌ Cleanup failed: {e}")
        import traceback

        traceback.print_exc()
        return 1


if __name__ == "__main__":
    exit(main())
