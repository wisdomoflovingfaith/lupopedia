#!/usr/bin/env python3
"""
Archive stale channel threads based on Thread Graduation Doctrine.
Moves threads with no activity > 30 days to lupo-archive/threads/.
"""

import os
import shutil
import argparse
from pathlib import Path
from datetime import datetime, timedelta
import yaml

# Configuration
CHANNELS_DIR = Path('lupo-channels')
ARCHIVE_DIR = Path('lupo-archive/threads')
INACTIVE_DAYS = 30

def parse_manifest(manifest_path):
    """Parse THREAD_MANIFEST.md frontmatter."""
    with open(manifest_path, 'r', encoding='utf-8') as f:
        content = f.read()
    
    if not content.startswith('---'):
        return None
    
    # Extract frontmatter
    parts = content.split('---', 2)
    if len(parts) < 3:
        return None
    
    try:
        return yaml.safe_load(parts[1])
    except yaml.YAMLError:
        return None

def get_last_activity(thread_dir):
    """Get last activity date from manifest or file modification time."""
    manifest_path = thread_dir / 'THREAD_MANIFEST.md'
    
    if manifest_path.exists():
        manifest = parse_manifest(manifest_path)
        if manifest and 'last_activity' in manifest:
            try:
                return datetime.strptime(str(manifest['last_activity']), '%Y%m%d')
            except (ValueError, TypeError):
                pass
    
    # Fallback to most recent file modification
    latest_mtime = 0
    for file_path in thread_dir.rglob('*'):
        if file_path.is_file():
            mtime = file_path.stat().st_mtime
            if mtime > latest_mtime:
                latest_mtime = mtime
    
    if latest_mtime > 0:
        return datetime.fromtimestamp(latest_mtime)
    
    return None

def archive_thread(thread_path, channel_id, thread_id):
    """Move thread to archive directory."""
    now = datetime.now()
    archive_path = ARCHIVE_DIR / f'{now.year:04d}' / f'{now.month:02d}' / thread_id
    
    # Create archive directory if needed
    archive_path.mkdir(parents=True, exist_ok=True)
    
    # Move files
    for item in thread_path.iterdir():
        shutil.move(str(item), str(archive_path / item.name))
    
    # Remove empty thread directory
    thread_path.rmdir()
    
    print(f"Archived: {channel_id}/threads/{thread_id} -> {archive_path}")
    return archive_path

def main(dry_run=False):
    """Main archival routine."""
    if not CHANNELS_DIR.exists():
        print(f"Channel directory not found: {CHANNELS_DIR}")
        return
    
    cutoff = datetime.now() - timedelta(days=INACTIVE_DAYS)
    archived_count = 0
    
    for channel_dir in CHANNELS_DIR.iterdir():
        if not channel_dir.is_dir():
            continue
        
        channel_id = channel_dir.name
        threads_dir = channel_dir / 'threads'
        
        if not threads_dir.exists():
            continue
        
        for thread_dir in threads_dir.iterdir():
            if not thread_dir.is_dir():
                continue
            
            thread_id = thread_dir.name
            
            # Skip numeric-only directories (these are database threads, not filesystem)
            if thread_id.isdigit():
                continue
            
            last_activity = get_last_activity(thread_dir)
            
            if last_activity and last_activity < cutoff:
                if dry_run:
                    print(f"[DRY RUN] Would archive: {channel_id}/threads/{thread_id} (Last activity: {last_activity.strftime('%Y-%m-%d')})")
                else:
                    archive_thread(thread_dir, channel_id, thread_id)
                    archived_count += 1
    
    print(f"Archived {archived_count} threads" + (" (dry run)" if dry_run else ""))

if __name__ == '__main__':
    parser = argparse.ArgumentParser(description='Archive stale channel threads')
    parser.add_argument('--dry-run', action='store_true', help='Preview without moving files')
    args = parser.parse_args()
    
    main(dry_run=args.dry_run)
