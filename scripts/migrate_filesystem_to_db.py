#!/usr/bin/env python3
"""
Filesystem to Database Migration Script

Migrates agent and channel metadata from numeric filesystem directories
to database tables with new hash-based upload structure.

Usage: python scripts/migrate_filesystem_to_db.py [--dry-run] [--type=agents|channels]

@package Lupopedia
@version 2026.3.9.0
"""

import sys
import os
import json
import hashlib
import shutil
from pathlib import Path
from datetime import datetime
from typing import Dict, List, Optional, Tuple
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


class FilesystemMigrator:
    """Main migration class"""

    def __init__(self, dry_run: bool = False):
        self.dry_run = dry_run
        self.db = None
        self.uploads_root = PROJECT_ROOT / 'uploads'
        self.stats = {
            'agents_processed': 0,
            'agents_success': 0,
            'agents_failed': 0,
            'channels_processed': 0,
            'channels_success': 0,
            'channels_failed': 0,
            'files_migrated': 0,
            'bytes_processed': 0
        }

        self.connect_database()
        self.ensure_upload_directories()

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

    def ensure_upload_directories(self):
        """Create upload directories if they don't exist"""
        dirs = ['agents', 'channels', 'operators']

        for dir_name in dirs:
            path = self.uploads_root / dir_name
            if not path.exists():
                if not self.dry_run:
                    path.mkdir(parents=True, exist_ok=True)
                print(f"✓ Created upload directory: {dir_name}/")

    @staticmethod
    def ymdhis() -> str:
        """Get current timestamp in YMDHIS format"""
        return datetime.utcnow().strftime('%Y%m%d%H%M%S')

    @staticmethod
    def get_date_path() -> str:
        """Get current date path (YYYY/MM)"""
        now = datetime.utcnow()
        return f"{now.year}/{now.month:02d}"

    @staticmethod
    def generate_hash_filename(original_filename: str, content: bytes) -> str:
        """Generate hash-based filename"""
        hash_obj = hashlib.sha256(content)
        hash_str = hash_obj.hexdigest()

        ext = Path(original_filename).suffix
        return f"{hash_str}{ext}"

    def migrate_agents(self):
        """Migrate all agents from filesystem to database"""
        agents_dir = PROJECT_ROOT / 'agents'

        if not agents_dir.exists():
            print(f"✗ Agents directory not found: {agents_dir}")
            return

        directories = [d for d in agents_dir.iterdir() if d.is_dir()]

        print("\n" + "=" * 70)
        print("MIGRATING AGENTS")
        print("=" * 70)
        print(f"Found {len(directories)} agent directories\n")

        for directory in directories:
            dir_name = directory.name

            # Skip non-numeric directories
            if not re.match(r'^\d{4}$', dir_name):
                print(f"⊘ Skipping non-numeric directory: {dir_name}")
                continue

            self.stats['agents_processed'] += 1
            print(f"Processing agent directory: {dir_name} ... ", end='', flush=True)

            try:
                self.migrate_agent_directory(directory, dir_name)
                self.stats['agents_success'] += 1
                print("✓")
            except Exception as e:
                self.stats['agents_failed'] += 1
                print(f"✗ {e}")
                self.log_migration_error('agents', str(directory), 'agent', None, str(e))

    def migrate_agent_directory(self, dir_path: Path, dir_name: str):
        """Migrate a single agent directory"""
        metadata_file = dir_path / 'metadata.json'

        if not metadata_file.exists():
            raise Exception("No metadata.json found")

        with open(metadata_file, 'r', encoding='utf-8') as f:
            metadata = json.load(f)

        if not metadata:
            raise Exception("Invalid metadata.json")

        # Extract agent data
        agent_key = metadata.get('code', dir_name.upper())
        agent_id = metadata.get('id', dir_name)
        version = metadata.get('version', '1.0.0')

        # Check if agent already exists
        existing_agent = self.find_agent_by_key(agent_key)

        if not existing_agent:
            if not self.dry_run:
                agent_db_id = self.create_agent_record(agent_key, dir_name, version, metadata)
            else:
                agent_db_id = None
                print(f"[DRY-RUN] Would create agent: {agent_key} ", end='')
        else:
            agent_db_id = existing_agent['agent_id']
            print(f"[EXISTS: agent_id={agent_db_id}] ", end='')

        # Migrate files
        files_migrated = self.migrate_agent_files(dir_path, agent_db_id or 0, dir_name)

        if not self.dry_run and agent_db_id:
            self.log_migration_success('agents', str(dir_path), 'agent', agent_db_id, files_migrated)

        return agent_db_id

    def find_agent_by_key(self, agent_key: str) -> Optional[Dict]:
        """Find agent by key in database"""
        with self.db.cursor() as cursor:
            cursor.execute("""
                SELECT agent_id, agent_key
                FROM lupo_agents
                WHERE agent_key = %s
                AND is_deleted = 0
                LIMIT 1
            """, (agent_key,))
            return cursor.fetchone()

    def create_agent_record(self, agent_key: str, agent_name: str, version: str, metadata: Dict) -> int:
        """Create new agent record in database"""
        now = self.ymdhis()

        with self.db.cursor() as cursor:
            cursor.execute("""
                INSERT INTO lupo_agents (
                    agent_key,
                    agent_name,
                    version,
                    description,
                    created_ymdhis,
                    updated_ymdhis,
                    is_deleted
                ) VALUES (
                    %s, %s, %s, %s, %s, %s, 0
                )
            """, (
                agent_key,
                agent_name,
                version,
                f'Migrated from filesystem directory: {agent_name}',
                now,
                now
            ))
            self.db.commit()
            return cursor.lastrowid

    def migrate_agent_files(self, dir_path: Path, agent_id: int, dir_name: str) -> int:
        """Migrate all files in an agent directory"""
        files_migrated = 0

        for file_path in dir_path.iterdir():
            if not file_path.is_file():
                continue

            file_name = file_path.name
            file_type = self.detect_file_type(file_name)

            with open(file_path, 'rb') as f:
                content = f.read()

            file_size = file_path.stat().st_size
            hash_str = hashlib.sha256(content).hexdigest()

            # Generate new path with date structure and hash
            date_path = self.get_date_path()
            new_filename = self.generate_hash_filename(file_name, content)
            new_relative_path = f"agents/{date_path}/{new_filename}"
            new_full_path = self.uploads_root / new_relative_path

            # Create directory structure
            new_dir = new_full_path.parent
            if not new_dir.exists() and not self.dry_run:
                new_dir.mkdir(parents=True, exist_ok=True)

            # Copy file to new location
            if not self.dry_run and not new_full_path.exists():
                shutil.copy2(file_path, new_full_path)

            # Insert file record
            if not self.dry_run:
                self.insert_agent_file_record(
                    agent_id, file_type, file_name, new_relative_path,
                    hash_str, file_size, dir_name
                )

            files_migrated += 1
            self.stats['files_migrated'] += 1
            self.stats['bytes_processed'] += file_size

        return files_migrated

    @staticmethod
    def detect_file_type(file_name: str) -> str:
        """Detect file type from filename"""
        file_name_lower = file_name.lower()

        if file_name_lower == 'metadata.json':
            return 'metadata'
        if file_name_lower == 'system_prompt.txt':
            return 'system_prompt'
        if file_name_lower == 'readme.md':
            return 'readme'
        if 'config' in file_name_lower:
            return 'config'

        ext = Path(file_name).suffix.lstrip('.')
        return ext or 'unknown'

    def insert_agent_file_record(self, agent_id: int, file_type: str, file_name: str,
                                 file_path: str, hash_str: str, file_size: int, original_dir: str):
        """Insert agent file record into database"""
        now = self.ymdhis()
        mime_type = self.get_mime_type(file_name)

        with self.db.cursor() as cursor:
            cursor.execute("""
                INSERT INTO lupo_agent_files (
                    agent_id,
                    file_type,
                    file_name,
                    file_path,
                    file_hash,
                    file_size,
                    mime_type,
                    upload_ymdhis,
                    created_ymdhis,
                    updated_ymdhis,
                    is_deleted,
                    migrated_from_directory
                ) VALUES (
                    %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, 0, %s
                )
            """, (
                agent_id, file_type, file_name, file_path, hash_str,
                file_size, mime_type, now, now, now, f'agents/{original_dir}'
            ))
            self.db.commit()

    def migrate_channels(self):
        """Migrate all channels from filesystem to database"""
        channels_dir = PROJECT_ROOT / 'channels'

        if not channels_dir.exists():
            print(f"✗ Channels directory not found: {channels_dir}")
            return

        directories = [d for d in channels_dir.iterdir() if d.is_dir()]

        print("\n" + "=" * 70)
        print("MIGRATING CHANNELS")
        print("=" * 70)
        print(f"Found {len(directories)} channel directories\n")

        for directory in directories:
            dir_name = directory.name

            # Skip non-numeric directories
            if not re.match(r'^\d{4}$', dir_name):
                print(f"⊘ Skipping non-numeric directory: {dir_name}")
                continue

            self.stats['channels_processed'] += 1
            print(f"Processing channel directory: {dir_name} ... ", end='', flush=True)

            try:
                self.migrate_channel_directory(directory, dir_name)
                self.stats['channels_success'] += 1
                print("✓")
            except Exception as e:
                self.stats['channels_failed'] += 1
                print(f"✗ {e}")
                self.log_migration_error('channels', str(directory), 'channel', None, str(e))

    def migrate_channel_directory(self, dir_path: Path, dir_name: str):
        """Migrate a single channel directory"""
        metadata_file = dir_path / 'metadata.json'

        if not metadata_file.exists():
            raise Exception("No metadata.json found")

        with open(metadata_file, 'r', encoding='utf-8') as f:
            metadata = json.load(f)

        if not metadata:
            raise Exception("Invalid metadata.json")

        # Extract channel data
        channel_key = f'channel_{dir_name}'

        # Check if channel already exists
        existing_channel = self.find_channel_by_key(channel_key)

        if not existing_channel:
            if not self.dry_run:
                channel_db_id = self.create_channel_record(channel_key, dir_name, metadata)
            else:
                channel_db_id = None
                print(f"[DRY-RUN] Would create channel: {channel_key} ", end='')
        else:
            channel_db_id = existing_channel['channel_id']
            print(f"[EXISTS: channel_id={channel_db_id}] ", end='')

        # Migrate files
        files_migrated = self.migrate_channel_files(dir_path, channel_db_id or 0, dir_name)

        if not self.dry_run and channel_db_id:
            self.log_migration_success('channels', str(dir_path), 'channel', channel_db_id, files_migrated)

        return channel_db_id

    def find_channel_by_key(self, channel_key: str) -> Optional[Dict]:
        """Find channel by key in database"""
        with self.db.cursor() as cursor:
            cursor.execute("""
                SELECT channel_id, channel_key
                FROM lupo_channels
                WHERE channel_key = %s
                AND is_deleted = 0
                LIMIT 1
            """, (channel_key,))
            return cursor.fetchone()

    def create_channel_record(self, channel_key: str, channel_name: str, metadata: Dict) -> int:
        """Create new channel record in database"""
        now = self.ymdhis()

        with self.db.cursor() as cursor:
            cursor.execute("""
                INSERT INTO lupo_channels (
                    federation_node_id,
                    created_by_actor_id,
                    default_actor_id,
                    channel_key,
                    channel_slug,
                    channel_type,
                    channel_name,
                    description,
                    created_ymdhis,
                    updated_ymdhis,
                    is_deleted
                ) VALUES (
                    1, 1, 1, %s, %s, 'migrated', %s, %s, %s, %s, 0
                )
            """, (
                channel_key,
                channel_key,
                f'Channel {channel_name}',
                f'Migrated from filesystem directory: {channel_name}',
                now,
                now
            ))
            self.db.commit()
            return cursor.lastrowid

    def migrate_channel_files(self, dir_path: Path, channel_id: int, dir_name: str) -> int:
        """Migrate all files in a channel directory"""
        files_migrated = 0

        for file_path in dir_path.iterdir():
            if not file_path.is_file():
                continue

            file_name = file_path.name
            file_type = self.detect_file_type(file_name)

            with open(file_path, 'rb') as f:
                content = f.read()

            file_size = file_path.stat().st_size
            hash_str = hashlib.sha256(content).hexdigest()

            # Generate new path
            date_path = self.get_date_path()
            new_filename = self.generate_hash_filename(file_name, content)
            new_relative_path = f"channels/{date_path}/{new_filename}"
            new_full_path = self.uploads_root / new_relative_path

            new_dir = new_full_path.parent
            if not new_dir.exists() and not self.dry_run:
                new_dir.mkdir(parents=True, exist_ok=True)

            if not self.dry_run and not new_full_path.exists():
                shutil.copy2(file_path, new_full_path)

            if not self.dry_run:
                self.insert_channel_file_record(
                    channel_id, file_type, file_name, new_relative_path,
                    hash_str, file_size, dir_name
                )

            files_migrated += 1
            self.stats['files_migrated'] += 1
            self.stats['bytes_processed'] += file_size

        return files_migrated

    def insert_channel_file_record(self, channel_id: int, file_type: str, file_name: str,
                                   file_path: str, hash_str: str, file_size: int, original_dir: str):
        """Insert channel file record into database"""
        now = self.ymdhis()
        mime_type = self.get_mime_type(file_name)

        with self.db.cursor() as cursor:
            cursor.execute("""
                INSERT INTO lupo_channel_files (
                    channel_id,
                    file_type,
                    file_name,
                    file_path,
                    file_hash,
                    file_size,
                    mime_type,
                    upload_ymdhis,
                    created_ymdhis,
                    updated_ymdhis,
                    is_deleted,
                    migrated_from_directory
                ) VALUES (
                    %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, 0, %s
                )
            """, (
                channel_id, file_type, file_name, file_path, hash_str,
                file_size, mime_type, now, now, now, f'channels/{original_dir}'
            ))
            self.db.commit()

    @staticmethod
    def get_mime_type(file_name: str) -> str:
        """Get MIME type from filename extension"""
        ext = Path(file_name).suffix.lstrip('.').lower()

        mime_map = {
            'json': 'application/json',
            'txt': 'text/plain',
            'md': 'text/markdown',
            'toon': 'application/json'
        }

        return mime_map.get(ext, 'application/octet-stream')

    def log_migration_success(self, migration_type: str, dir_path: str, entity_type: str,
                             entity_id: int, files_migrated: int):
        """Log successful migration"""
        now = self.ymdhis()

        with self.db.cursor() as cursor:
            cursor.execute("""
                INSERT INTO lupo_filesystem_migration_log (
                    migration_type,
                    directory_path,
                    entity_type,
                    entity_id,
                    status,
                    files_migrated,
                    started_ymdhis,
                    completed_ymdhis
                ) VALUES (
                    %s, %s, %s, %s, 'success', %s, %s, %s
                )
            """, (migration_type, dir_path, entity_type, entity_id, files_migrated, now, now))
            self.db.commit()

    def log_migration_error(self, migration_type: str, dir_path: str, entity_type: str,
                           entity_id: Optional[int], error_message: str):
        """Log migration error"""
        if self.dry_run:
            return

        now = self.ymdhis()

        with self.db.cursor() as cursor:
            cursor.execute("""
                INSERT INTO lupo_filesystem_migration_log (
                    migration_type,
                    directory_path,
                    entity_type,
                    entity_id,
                    status,
                    error_message,
                    started_ymdhis
                ) VALUES (
                    %s, %s, %s, %s, 'failed', %s, %s
                )
            """, (migration_type, dir_path, entity_type, entity_id, error_message, now))
            self.db.commit()

    def print_stats(self):
        """Print migration statistics"""
        print("\n" + "=" * 70)
        print("MIGRATION STATISTICS")
        print("=" * 70)
        print(f"Agents processed:    {self.stats['agents_processed']}")
        print(f"  ✓ Success:         {self.stats['agents_success']}")
        print(f"  ✗ Failed:          {self.stats['agents_failed']}")
        print()
        print(f"Channels processed:  {self.stats['channels_processed']}")
        print(f"  ✓ Success:         {self.stats['channels_success']}")
        print(f"  ✗ Failed:          {self.stats['channels_failed']}")
        print()
        print(f"Files migrated:      {self.stats['files_migrated']}")
        print(f"Bytes processed:     {self.stats['bytes_processed']:,} ({self.format_bytes(self.stats['bytes_processed'])})")
        print("=" * 70)

    @staticmethod
    def format_bytes(bytes_val: int) -> str:
        """Format bytes to human-readable size"""
        units = ['B', 'KB', 'MB', 'GB']
        i = 0
        while bytes_val >= 1024 and i < len(units) - 1:
            bytes_val /= 1024
            i += 1
        return f"{bytes_val:.2f} {units[i]}"

    def close(self):
        """Close database connection"""
        if self.db:
            self.db.close()


def main():
    """Main execution"""
    parser = argparse.ArgumentParser(description='Filesystem to Database Migration')
    parser.add_argument('--dry-run', action='store_true', help='Dry run mode (no changes)')
    parser.add_argument('--type', choices=['agents', 'channels', 'all'], default='all',
                       help='Migration type')
    args = parser.parse_args()

    print()
    print("=" * 70)
    print("FILESYSTEM TO DATABASE MIGRATION")
    print("=" * 70)
    print(f"Mode: {'DRY RUN (no changes will be made)' if args.dry_run else 'LIVE (changes will be applied)'}")
    print(f"Type: {args.type.upper()}")
    print("=" * 70)
    print()

    if args.dry_run:
        print("⚠ DRY RUN MODE - No database changes will be made")
        print("⚠ Files will NOT be copied to new upload structure\n")

    migrator = FilesystemMigrator(args.dry_run)

    try:
        if args.type in ['agents', 'all']:
            migrator.migrate_agents()

        if args.type in ['channels', 'all']:
            migrator.migrate_channels()

        migrator.print_stats()

        print("\n✓ Migration complete!\n")

        if args.dry_run:
            print("To run the actual migration, execute without --dry-run:")
            print("  python scripts/migrate_filesystem_to_db.py\n")
        else:
            print("Next steps:")
            print("1. Verify data integrity in database")
            print("2. Test application with new database-backed system")
            print("3. Run cleanup script: python scripts/cleanup_old_directories.py\n")

    finally:
        migrator.close()


if __name__ == '__main__':
    main()
