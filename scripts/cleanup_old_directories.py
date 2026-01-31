#!/usr/bin/env python3
"""
Cleanup Script for Old Numeric Directories

Safely removes old agents/NNNN and channels/NNNN directories after
verifying that all data has been successfully migrated to the database.

Usage: python scripts/cleanup_old_directories.py [--force] [--type=agents|channels]

IMPORTANT: Run migration script first and verify data integrity!

@package Lupopedia
@version 2026.3.9.0
"""

import sys
import os
import shutil
from pathlib import Path
from datetime import datetime
from typing import Dict, Optional
import argparse
import re

# Add project root to path
PROJECT_ROOT = Path(__file__).parent.parent
sys.path.insert(0, str(PROJECT_ROOT))

# Database imports
try:
    import pymysql
    from pymysql.cursors import DictCursor
except ImportError:
    print("Error: pymysql not installed. Run: pip install pymysql")
    sys.exit(1)


class DirectoryCleanup:
    """Directory cleanup class"""

    def __init__(self, force: bool = False):
        self.force = force
        self.db = None
        self.backup_dir = PROJECT_ROOT / 'backups' / f'filesystem_migration_{datetime.utcnow().strftime("%Y%m%d_%H%M%S")}'
        self.stats = {
            'agents_dirs_removed': 0,
            'agents_dirs_backed_up': 0,
            'channels_dirs_removed': 0,
            'channels_dirs_backed_up': 0,
            'bytes_freed': 0
        }

        self.connect_database()

    def connect_database(self):
        """Connect to MySQL database"""
        try:
            # Read database config from PHP config file
            config = self._read_php_config()

            self.db = pymysql.connect(
                host=config.get('DB_HOST', 'localhost'),
                port=int(config.get('DB_PORT', 3306)),
                user=config.get('DB_USER', 'root'),
                password=config.get('DB_PASSWORD', ''),
                database=config.get('DB_NAME', 'lupopedia'),
                charset=config.get('DB_CHARSET', 'utf8mb4'),
                cursorclass=DictCursor,
                autocommit=False
            )
            print("✓ Database connected")
        except Exception as e:
            print(f"✗ Database connection failed: {e}")
            sys.exit(1)

    def _read_php_config(self) -> Dict[str, str]:
        """Read database config from PHP config file"""
        config = {}
        config_file = PROJECT_ROOT / 'lupopedia-config.php'

        if not config_file.exists():
            return {
                'DB_HOST': 'localhost',
                'DB_PORT': '3306',
                'DB_USER': 'root',
                'DB_PASSWORD': 'ServBay.dev',
                'DB_NAME': 'lupopedia',
                'DB_CHARSET': 'utf8mb4'
            }

        with open(config_file, 'r', encoding='utf-8') as f:
            content = f.read()

            # Extract define() statements
            pattern = r"define\('([^']+)',\s*'([^']*)'\)"
            matches = re.findall(pattern, content)

            for key, value in matches:
                if key.startswith('DB_'):
                    config[key] = value

        return config

    def verify_migration(self) -> bool:
        """Verify migration status before cleanup"""
        print("\n" + "=" * 70)
        print("VERIFYING MIGRATION STATUS")
        print("=" * 70)

        # Check migration log for failures
        with self.db.cursor() as cursor:
            cursor.execute("""
                SELECT COUNT(*) as failed_count
                FROM lupo_filesystem_migration_log
                WHERE status = 'failed'
            """)
            result = cursor.fetchone()

        if result['failed_count'] > 0:
            print(f"✗ WARNING: {result['failed_count']} migration failures detected!")
            print("  Review migration log before proceeding with cleanup.\n")

            if not self.force:
                print("Use --force to proceed anyway (not recommended)")
                return False
            else:
                print("⚠ Proceeding with cleanup despite failures (--force)\n")
        else:
            print("✓ No migration failures detected")

        # Count successful migrations
        with self.db.cursor() as cursor:
            cursor.execute("""
                SELECT
                    migration_type,
                    COUNT(*) as count,
                    SUM(files_migrated) as total_files
                FROM lupo_filesystem_migration_log
                WHERE status = 'success'
                GROUP BY migration_type
            """)
            results = cursor.fetchall()

        print("\nMigration summary:")
        for row in results:
            print(f"  {row['migration_type']}: {row['count']} directories, {row['total_files']} files")

        return True

    def cleanup_agents(self):
        """Clean up agent directories"""
        agents_dir = PROJECT_ROOT / 'agents'

        if not agents_dir.exists():
            print(f"✗ Agents directory not found: {agents_dir}")
            return

        directories = [d for d in agents_dir.iterdir() if d.is_dir()]

        print("\n" + "=" * 70)
        print("CLEANING UP AGENT DIRECTORIES")
        print("=" * 70)
        print(f"Found {len(directories)} agent directories\n")

        for directory in directories:
            dir_name = directory.name

            # Only process numeric directories
            if not re.match(r'^\d{4}$', dir_name):
                print(f"⊘ Skipping non-numeric directory: {dir_name}")
                continue

            # Verify this directory was successfully migrated
            migrated = self.check_migration_status('agents', str(directory))

            if not migrated and not self.force:
                print(f"⊘ Skipping {dir_name} (not migrated)")
                continue

            print(f"Processing {dir_name} ... ", end='', flush=True)

            try:
                size = self.get_directory_size(directory)
                self.backup_directory(directory, 'agents')
                self.remove_directory(directory)
                self.stats['agents_dirs_removed'] += 1
                self.stats['agents_dirs_backed_up'] += 1
                self.stats['bytes_freed'] += size
                print(f"✓ removed ({self.format_bytes(size)} freed)")
            except Exception as e:
                print(f"✗ {e}")

    def cleanup_channels(self):
        """Clean up channel directories"""
        channels_dir = PROJECT_ROOT / 'channels'

        if not channels_dir.exists():
            print(f"✗ Channels directory not found: {channels_dir}")
            return

        directories = [d for d in channels_dir.iterdir() if d.is_dir()]

        print("\n" + "=" * 70)
        print("CLEANING UP CHANNEL DIRECTORIES")
        print("=" * 70)
        print(f"Found {len(directories)} channel directories\n")

        for directory in directories:
            dir_name = directory.name

            # Only process numeric directories
            if not re.match(r'^\d{4}$', dir_name):
                print(f"⊘ Skipping non-numeric directory: {dir_name}")
                continue

            # Verify this directory was successfully migrated
            migrated = self.check_migration_status('channels', str(directory))

            if not migrated and not self.force:
                print(f"⊘ Skipping {dir_name} (not migrated)")
                continue

            print(f"Processing {dir_name} ... ", end='', flush=True)

            try:
                size = self.get_directory_size(directory)
                self.backup_directory(directory, 'channels')
                self.remove_directory(directory)
                self.stats['channels_dirs_removed'] += 1
                self.stats['channels_dirs_backed_up'] += 1
                self.stats['bytes_freed'] += size
                print(f"✓ removed ({self.format_bytes(size)} freed)")
            except Exception as e:
                print(f"✗ {e}")

    def check_migration_status(self, migration_type: str, dir_path: str) -> bool:
        """Check if directory was successfully migrated"""
        with self.db.cursor() as cursor:
            cursor.execute("""
                SELECT status
                FROM lupo_filesystem_migration_log
                WHERE migration_type = %s
                AND directory_path = %s
                AND status = 'success'
                LIMIT 1
            """, (migration_type, dir_path))
            return cursor.fetchone() is not None

    @staticmethod
    def get_directory_size(directory: Path) -> int:
        """Get total size of directory"""
        total_size = 0
        for item in directory.rglob('*'):
            if item.is_file():
                total_size += item.stat().st_size
        return total_size

    def backup_directory(self, source_dir: Path, dir_type: str):
        """Backup directory before removal"""
        dir_name = source_dir.name
        backup_path = self.backup_dir / dir_type / dir_name

        if not backup_path.parent.exists():
            backup_path.parent.mkdir(parents=True, exist_ok=True)

        shutil.copytree(source_dir, backup_path)

    @staticmethod
    def remove_directory(directory: Path):
        """Remove directory recursively"""
        if not directory.exists():
            return

        shutil.rmtree(directory)

    @staticmethod
    def format_bytes(bytes_val: int) -> str:
        """Format bytes to human-readable size"""
        units = ['B', 'KB', 'MB', 'GB']
        i = 0
        while bytes_val >= 1024 and i < len(units) - 1:
            bytes_val /= 1024
            i += 1
        return f"{bytes_val:.2f} {units[i]}"

    def print_stats(self):
        """Print cleanup statistics"""
        print("\n" + "=" * 70)
        print("CLEANUP STATISTICS")
        print("=" * 70)
        print("Agent directories:")
        print(f"  Removed:         {self.stats['agents_dirs_removed']}")
        print(f"  Backed up:       {self.stats['agents_dirs_backed_up']}")
        print()
        print("Channel directories:")
        print(f"  Removed:         {self.stats['channels_dirs_removed']}")
        print(f"  Backed up:       {self.stats['channels_dirs_backed_up']}")
        print()
        print(f"Space freed:       {self.stats['bytes_freed']:,} bytes ({self.format_bytes(self.stats['bytes_freed'])})")
        print(f"Backup location:   {self.backup_dir}")
        print("=" * 70)

    def close(self):
        """Close database connection"""
        if self.db:
            self.db.close()


def main():
    """Main execution"""
    parser = argparse.ArgumentParser(description='Old Directory Cleanup Script')
    parser.add_argument('--force', action='store_true',
                       help='Force mode (skip verification)')
    parser.add_argument('--type', choices=['agents', 'channels', 'all'], default='all',
                       help='Cleanup type')
    args = parser.parse_args()

    print()
    print("=" * 70)
    print("OLD DIRECTORY CLEANUP SCRIPT")
    print("=" * 70)
    print(f"Force mode: {'YES (skip verification)' if args.force else 'NO (verify migration first)'}")
    print(f"Type: {args.type.upper()}")
    print("=" * 70)
    print()

    if args.force:
        print("⚠ WARNING: Force mode enabled - directories will be removed even if migration failed!\n")

    print("This script will:")
    print("  1. Verify migration status")
    print("  2. Backup directories to /backups/")
    print("  3. Remove old numeric directories\n")

    if not args.force:
        print("Press Ctrl+C to cancel, or press Enter to continue...", end='', flush=True)
        try:
            input()
        except KeyboardInterrupt:
            print("\n\n✗ Cancelled by user")
            sys.exit(0)
    print()

    cleanup = DirectoryCleanup(args.force)

    try:
        # Verify migration status first
        if not cleanup.verify_migration():
            print("\n✗ Migration verification failed. Aborting cleanup.\n")
            sys.exit(1)

        # Perform cleanup
        if args.type in ['agents', 'all']:
            cleanup.cleanup_agents()

        if args.type in ['channels', 'all']:
            cleanup.cleanup_channels()

        cleanup.print_stats()

        print("\n✓ Cleanup complete!\n")
        print("Important:")
        print("- Backups are stored in the backups/ directory")
        print("- Keep backups for at least 30 days before permanent deletion")
        print("- Verify application functionality before removing backups\n")

    finally:
        cleanup.close()


if __name__ == '__main__':
    main()
