#!/usr/bin/env python3
"""
Import channels and artifacts from filesystem markdown files into Lupopedia database.

Imports:
- channels/*/broadcasts/*.md -> lupo_dialog_messages
- artifacts/*/*.md -> lupo_artifacts
- channels/*/threads/*.md -> lupo_dialog_threads

Usage:
    python scripts/import_channels_and_artifacts.py [--dry-run] [--verbose]
"""

import os
import sys
import re
import json
import hashlib
from datetime import datetime
from pathlib import Path

# Add parent directory to path for imports
sys.path.insert(0, os.path.dirname(os.path.dirname(os.path.abspath(__file__))))

def load_config():
    """Load database configuration from lupopedia-config.php"""
    config_paths = [
        '../lupopedia-config.php',
        'lupopedia-config.php',
        os.path.join(os.path.dirname(os.path.dirname(__file__)), 'lupopedia-config.php')
    ]
    
    for config_path in config_paths:
        if os.path.exists(config_path):
            with open(config_path, 'r', encoding='utf-8') as f:
                content = f.read()
                
            # Extract database credentials
            db_host = re.search(r"define\('DB_HOST',\s*'([^']+)'\)", content)
            db_name = re.search(r"define\('DB_NAME',\s*'([^']+)'\)", content)
            db_user = re.search(r"define\('DB_USER',\s*'([^']+)'\)", content)
            db_pass = re.search(r"define\('DB_PASSWORD',\s*'([^']+)'\)", content)
            table_prefix = re.search(r"define\('LUPO_TABLE_PREFIX',\s*'([^']+)'\)", content)
            
            if all([db_host, db_name, db_user, db_pass]):
                return {
                    'host': db_host.group(1),
                    'database': db_name.group(1),
                    'user': db_user.group(1),
                    'password': db_pass.group(1),
                    'prefix': table_prefix.group(1) if table_prefix else 'lupo_'
                }
    
    raise Exception("Could not find lupopedia-config.php")

def get_db_connection(config):
    """Create database connection"""
    try:
        import mysql.connector
        return mysql.connector.connect(
            host=config['host'],
            database=config['database'],
            user=config['user'],
            password=config['password']
        )
    except ImportError:
        print("ERROR: mysql-connector-python not installed")
        print("Install with: pip install mysql-connector-python")
        sys.exit(1)

def parse_markdown_file(filepath):
    """Parse markdown file with YAML front matter"""
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # Extract YAML front matter
    yaml_match = re.match(r'^---\s*\n(.*?)\n---\s*\n(.*)$', content, re.DOTALL)
    if not yaml_match:
        return None
    
    yaml_content = yaml_match.group(1)
    message_body = yaml_match.group(2).strip()
    
    # Parse YAML manually (simple key: value pairs)
    metadata = {}
    for line in yaml_content.split('\n'):
        if ':' in line and not line.strip().startswith('#'):
            key, value = line.split(':', 1)
            key = key.strip()
            value = value.strip().strip('"\'')
            metadata[key] = value
    
    # Extract message text (first 1000 chars of body, no YAML)
    message_text = message_body[:1000]
    
    # Calculate file hash
    file_hash = hashlib.sha256(content.encode('utf-8')).hexdigest()
    
    return {
        'metadata': metadata,
        'message_text': message_text,
        'message_body': message_body,
        'file_hash': file_hash,
        'filepath': filepath
    }

def extract_timestamp_from_filename(filename):
    """Extract YYYYMMDDHHIISS timestamp from filename"""
    match = re.match(r'^(\d{14})', filename)
    if match:
        return int(match.group(1))
    return int(datetime.utcnow().strftime('%Y%m%d%H%M%S'))

def generate_dialog_message_id(cursor, table_prefix, base_timestamp):
    """Generate unique dialog_message_id from timestamp with collision detection"""
    dialog_message_id = base_timestamp
    max_attempts = 1000
    
    for attempt in range(max_attempts):
        # Check if ID exists
        cursor.execute(
            f"SELECT COUNT(*) FROM {table_prefix}dialog_messages WHERE dialog_message_id = %s",
            (dialog_message_id,)
        )
        count = cursor.fetchone()[0]
        
        if count == 0:
            return dialog_message_id
        
        # Collision detected, increment by 1
        dialog_message_id += 1
    
    raise Exception(f"Could not generate unique dialog_message_id after {max_attempts} attempts")

def import_broadcast(cursor, table_prefix, filepath, dry_run=False, verbose=False):
    """Import a single broadcast message"""
    data = parse_markdown_file(filepath)
    if not data:
        if verbose:
            print(f"  SKIP: {filepath} (no YAML front matter)")
        return False
    
    metadata = data['metadata']
    
    # Extract channel_id from directory structure
    parts = Path(filepath).parts
    channel_idx = parts.index('channels') if 'channels' in parts else -1
    if channel_idx == -1 or channel_idx + 1 >= len(parts):
        if verbose:
            print(f"  SKIP: {filepath} (not in channels directory)")
        return False
    
    channel_id = int(parts[channel_idx + 1])
    
    # Extract timestamp from filename
    filename = os.path.basename(filepath)
    created_ymdhis = extract_timestamp_from_filename(filename)
    updated_ymdhis = int(datetime.utcnow().strftime('%Y%m%d%H%M%S'))
    
    # Extract actor IDs from metadata or filename
    from_actor_id = int(metadata.get('from_actor_id', 0))
    to_actor_id = int(metadata.get('to_actor_id', 0))
    
    # If not in metadata, try filename pattern: TIMESTAMP_FROM_TO_CHANNEL_slug.md
    if from_actor_id == 0 or to_actor_id == 0:
        filename_parts = filename.replace('.md', '').split('_')
        if len(filename_parts) >= 4:
            try:
                from_actor_id = int(filename_parts[1])
                to_actor_id = int(filename_parts[2])
            except (ValueError, IndexError):
                pass
    
    # Generate unique dialog_message_id
    dialog_message_id = generate_dialog_message_id(cursor, table_prefix, created_ymdhis)
    
    # Build metadata JSON
    metadata_json = {
        'original_path': filepath,
        'file_hash': data['file_hash'],
        'imported_ymdhis': updated_ymdhis,
        'header': metadata
    }
    
    # Check if already imported (by file hash)
    cursor.execute(
        f"SELECT COUNT(*) FROM {table_prefix}dialog_messages WHERE metadata_json LIKE %s",
        (f'%{data["file_hash"]}%',)
    )
    if cursor.fetchone()[0] > 0:
        if verbose:
            print(f"  SKIP: {filepath} (already imported)")
        return False
    
    if verbose:
        print(f"  IMPORT: {filepath}")
        print(f"    dialog_message_id: {dialog_message_id}")
        print(f"    channel_id: {channel_id}")
        print(f"    from_actor_id: {from_actor_id}")
        print(f"    to_actor_id: {to_actor_id}")
        print(f"    created_ymdhis: {created_ymdhis}")
    
    if dry_run:
        return True
    
    # Insert into database
    cursor.execute(f"""
        INSERT INTO {table_prefix}dialog_messages (
            dialog_message_id,
            message_id,
            dialog_thread_id,
            channel_id,
            from_actor_id,
            to_actor_id,
            read_by_actor_id,
            read_by_actor_utc,
            message_text,
            message_type,
            metadata_json,
            mood_rgb,
            mood_framework,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis,
            message_body
        ) VALUES (
            %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s
        )
    """, (
        dialog_message_id,
        0,  # message_id (legacy)
        None,  # dialog_thread_id
        channel_id,
        from_actor_id,
        to_actor_id,
        0,  # read_by_actor_id
        0,  # read_by_actor_utc
        data['message_text'],
        'broadcast',
        json.dumps(metadata_json),
        None,  # mood_rgb
        'western_analytical',  # mood_framework
        created_ymdhis,
        updated_ymdhis,
        0,  # is_deleted
        None,  # deleted_ymdhis
        data['message_body']
    ))
    
    return True

def main():
    """Main import process"""
    import argparse
    
    parser = argparse.ArgumentParser(description='Import channels and artifacts into Lupopedia database')
    parser.add_argument('--dry-run', action='store_true', help='Preview import without making changes')
    parser.add_argument('--verbose', action='store_true', help='Show detailed output')
    args = parser.parse_args()
    
    print("Lupopedia Channel & Artifact Import")
    print("=" * 60)
    
    if args.dry_run:
        print("MODE: DRY RUN (no database changes)")
    else:
        print("MODE: LIVE IMPORT")
    
    print()
    
    # Load configuration
    print("Loading configuration...")
    config = load_config()
    print(f"  Database: {config['database']}")
    print(f"  Table prefix: {config['prefix']}")
    print()
    
    # Connect to database
    print("Connecting to database...")
    conn = get_db_connection(config)
    cursor = conn.cursor()
    print("  Connected")
    print()
    
    # Find all broadcast files
    print("Scanning for broadcast files...")
    broadcast_files = []
    channels_dir = 'channels'
    if os.path.exists(channels_dir):
        for root, dirs, files in os.walk(channels_dir):
            if 'broadcasts' in root:
                for file in files:
                    if file.endswith('.md'):
                        broadcast_files.append(os.path.join(root, file))
    
    print(f"  Found {len(broadcast_files)} broadcast files")
    print()
    
    # Import broadcasts
    print("Importing broadcasts...")
    imported_count = 0
    skipped_count = 0
    
    for filepath in sorted(broadcast_files):
        try:
            if import_broadcast(cursor, config['prefix'], filepath, args.dry_run, args.verbose):
                imported_count += 1
            else:
                skipped_count += 1
        except Exception as e:
            print(f"  ERROR: {filepath}")
            print(f"    {str(e)}")
            skipped_count += 1
    
    print()
    print("=" * 60)
    print(f"SUMMARY:")
    print(f"  Imported: {imported_count}")
    print(f"  Skipped: {skipped_count}")
    print(f"  Total: {len(broadcast_files)}")
    
    if not args.dry_run:
        conn.commit()
        print()
        print("Changes committed to database")
    
    cursor.close()
    conn.close()
    
    print()
    print("Import complete")

if __name__ == '__main__':
    main()
