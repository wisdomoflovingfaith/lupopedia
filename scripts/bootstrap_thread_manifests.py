#!/usr/bin/env python3
"""
Bootstrap THREAD_MANIFEST.md files for legacy physical channel threads.
Generates baseline manifests for threads that predate the Thread Graduation Doctrine.

Usage:
  python bootstrap_thread_manifests.py           # Create missing manifests
  python bootstrap_thread_manifests.py --dry-run # Preview only, no writes
"""

import os
import argparse
from pathlib import Path
from datetime import datetime

CHANNELS_DIR = Path('channels')

def get_directory_info(thread_dir):
    """Get oldest/newest dates and file count for the directory."""
    oldest_mtime = float('inf')
    newest_mtime = 0
    file_count = 0
    
    for file_path in thread_dir.rglob('*'):
        if file_path.is_file():
            file_count += 1
            mtime = file_path.stat().st_mtime
            if mtime < oldest_mtime:
                oldest_mtime = mtime
            if mtime > newest_mtime:
                newest_mtime = mtime
    
    if file_count == 0:
        # Fallback if thread is empty
        now_ts = datetime.now().timestamp()
        return {
            'start_date': datetime.fromtimestamp(now_ts),
            'last_activity': datetime.fromtimestamp(now_ts),
            'file_count': 0
        }
    
    return {
        'start_date': datetime.fromtimestamp(oldest_mtime),
        'last_activity': datetime.fromtimestamp(newest_mtime),
        'file_count': file_count
    }

def generate_manifest_content(channel_id, thread_id, info, thread_dir):
    """Generate THREAD_MANIFEST.md content."""
    # Determine status
    # - If there's a RESOLUTION.md or RESOLUTION.txt, it's resolved
    if (thread_dir / 'RESOLUTION.md').exists() or (thread_dir / 'RESOLUTION.txt').exists():
        status = "resolved"
    # - If there's a PRD file (likely formalized), it's formalized
    elif any(f.name.startswith('prd_') or f.name.endswith('_prd.md') for f in thread_dir.iterdir()):
        status = "formalized"
    # - Otherwise, it's a legacy thread (historical)
    else:
        status = "legacy"
    
    # Store status back into info dictionary for logging
    info['status'] = status
    
    return f"""---
thread_id: "{thread_id}"
channel_id: "{channel_id}"
purpose: "Legacy discussion thread (bootstrapped from filesystem)"
start_date: "{info['start_date'].strftime('%Y%m%d')}"
last_activity: "{info['last_activity'].strftime('%Y%m%d')}"
file_count: {info['file_count']}
status: "{status}"
resolution: ""
---"""

def bootstrap_manifests(dry_run=False):
    """Create missing THREAD_MANIFEST.md files for legacy threads."""
    if not CHANNELS_DIR.exists():
        print(f"Channel directory not found: {CHANNELS_DIR}")
        return

    # System channels to completely ignore
    skip_channels = {'0', '666'}

    created_count = 0
    skipped_count = 0
    empty_count = 0
    
    for channel_dir in CHANNELS_DIR.iterdir():
        if not channel_dir.is_dir():
            continue
            
        channel_id = channel_dir.name
        
        # Skip system channels
        if channel_id in skip_channels:
            if dry_run:
                print(f"[SKIP] System channel: {channel_id}")
            continue
            
        threads_dir = channel_dir / 'threads'
        
        if not threads_dir.exists():
            continue
            
        for thread_dir in threads_dir.iterdir():
            if not thread_dir.is_dir():
                continue
                
            thread_id = thread_dir.name
            
            # Skip numeric-only directories (database threads)
            if thread_id.isdigit():
                if dry_run:
                    print(f"[SKIP] Numeric thread (database): {channel_id}/threads/{thread_id}")
                skipped_count += 1
                continue
            
            manifest_path = thread_dir / 'THREAD_MANIFEST.md'
            
            if not manifest_path.exists():
                info = get_directory_info(thread_dir)
                
                if info['file_count'] == 0:
                    if dry_run:
                        print(f"[SKIP] Empty thread: {channel_id}/threads/{thread_id}")
                    empty_count += 1
                    continue
                
                manifest_content = generate_manifest_content(channel_id, thread_id, info, thread_dir)
                
                if dry_run:
                    print(f"[DRY RUN] Would create: {channel_id}/threads/{thread_id}/THREAD_MANIFEST.md")
                    print(f"          Status: {info['status']}, Files: {info['file_count']}")
                else:
                    with open(manifest_path, 'w', encoding='utf-8') as f:
                        f.write(manifest_content)
                    print(f"Created: {channel_id}/threads/{thread_id}/THREAD_MANIFEST.md (files: {info['file_count']})")
                
                created_count += 1

    print(f"\nBootstrapping complete.")
    print(f"  Created: {created_count} manifests")
    print(f"  Skipped: {skipped_count} numeric (database) threads")
    print(f"  Empty threads Skipped: {empty_count}")
    if dry_run:
        print("  (DRY RUN - no files were written)")

if __name__ == '__main__':
    parser = argparse.ArgumentParser(description='Bootstrap THREAD_MANIFEST.md for legacy threads')
    parser.add_argument('--dry-run', action='store_true', help='Preview without writing files')
    args = parser.parse_args()
    
    bootstrap_manifests(dry_run=args.dry_run)
