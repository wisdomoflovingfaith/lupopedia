#!/usr/bin/env python3
"""
THOTH - Task Registry Construction
Scans all channels and threads to build master task registry
"""

import os
import re
import json
from pathlib import Path
from datetime import datetime

def extract_metadata_from_file(filepath):
    """Extract metadata from file header (YAML)"""
    try:
        with open(filepath, 'r', encoding='utf-8') as f:
            content = f.read(500)
            
        # Look for lupopedia.headers section
        if '---' in content:
            parts = content.split('---')
            if len(parts) >= 2:
                yaml_part = parts[1]
                
                # Extract fields
                meta = {}
                
                # Extract task_id
                task_match = re.search(r'task_id:\s*["\']?([^"\'\n]+)', yaml_part)
                if task_match:
                    meta['task_id'] = task_match.group(1).strip()
                
                # Extract artifact_type
                type_match = re.search(r'artifact_type:\s*["\']?([^"\'\n]+)', yaml_part)
                if type_match:
                    meta['artifact_type'] = type_match.group(1).strip()
                
                # Extract actor (delegation_chain or actor_id)
                actor_match = re.search(r'delegation_chain:\s*["\']?([^"\'\n]+)', yaml_part)
                if actor_match:
                    meta['actor'] = actor_match.group(1).strip()
                else:
                    actor_match = re.search(r'actor_id:\s*(\d+)', yaml_part)
                    if actor_match:
                        meta['actor_id'] = actor_match.group(1).strip()
                        
                # Extract purpose
                purpose_match = re.search(r'purpose:\s*["\']?([^"\'\n]+)', yaml_part)
                if purpose_match:
                    meta['purpose'] = purpose_match.group(1).strip()
                
                return meta
    except Exception as e:
        pass
    
    return {}

def extract_thread_id(filepath):
    """Extract thread_id from path like lupo-channels/42/threads/1043/..."""
    parts = filepath.split(os.sep)
    if 'threads' in parts:
        idx = parts.index('threads')
        if idx + 1 < len(parts):
            return parts[idx + 1]
    return None

def extract_timestamp_from_filename(filename):
    """Extract timestamp from filename like 20260321_210000_name.md"""
    match = re.match(r'(\d{8}_\d{6})', filename)
    if match:
        return match.group(1)
    return None

def scan_all_threads():
    """Scan all channels and threads"""
    threads = {}
    channel_dir = Path('lupo-channels')
    
    if not channel_dir.exists():
        print("ERROR: lupo-channels directory not found")
        return threads
    
    # Scan all channels
    for channel_path in sorted(channel_dir.glob('*/threads/*')):
        if not channel_path.is_dir():
            continue
        
        thread_id = channel_path.name
        channel_id = channel_path.parent.parent.name
        
        # Skip non-numeric thread IDs
        try:
            int(thread_id)
        except ValueError:
            continue
        
        thread_key = f"thread_{thread_id}"
        try:
            rel_path = channel_path.relative_to(Path.cwd())
        except ValueError:
            rel_path = channel_path
            
        threads[thread_key] = {
            'thread_id': thread_id,
            'channel_id': channel_id,
            'path': str(rel_path),
            'task_id': None,
            'actor': None,
            'purpose': None,
            'artifact_count': 0,
            'artifacts': []
        }
        
        # Scan artifacts in this thread
        for artifact in sorted(channel_path.glob('*.md')):
            filename = artifact.name
            timestamp = extract_timestamp_from_filename(filename)
            
            # Extract metadata from file
            meta = extract_metadata_from_file(str(artifact))
            
            artifact_info = {
                'file': filename,
                'timestamp': timestamp,
                'type': meta.get('artifact_type', 'unknown'),
                'actor': meta.get('actor') or meta.get('actor_id', 'unknown'),
                'task_id': meta.get('task_id', None),
                'purpose': meta.get('purpose', '')
            }
            
            threads[thread_key]['artifacts'].append(artifact_info)
            threads[thread_key]['artifact_count'] += 1
            
            # Use first artifact's metadata for thread if not yet set
            if not threads[thread_key]['actor'] and artifact_info.get('actor'):
                threads[thread_key]['actor'] = artifact_info['actor']
            if not threads[thread_key]['purpose'] and artifact_info.get('purpose'):
                threads[thread_key]['purpose'] = artifact_info['purpose']
            if not threads[thread_key]['task_id'] and artifact_info.get('task_id'):
                threads[thread_key]['task_id'] = artifact_info['task_id']
    
    return threads

def main():
    print("\n" + "="*80)
    print("THOTH - TASK REGISTRY CONSTRUCTION")
    print("="*80)
    
    print("\nScanning all threads...")
    threads = scan_all_threads()
    
    print(f"\nFound {len(threads)} threads")
    
    # Build registry
    registry = []
    for thread_key in sorted(threads.keys()):
        thread = threads[thread_key]
        
        # Assign auto task_id if missing
        task_id = thread.get('task_id') or f"task_auto_{thread['thread_id']}"
        
        # Get latest artifact as reference
        latest_artifact = thread['artifacts'][0] if thread['artifacts'] else None
        
        entry = {
            'task_id': task_id,
            'thread_id': thread['thread_id'],
            'channel_id': thread['channel_id'],
            'actor': thread.get('actor') or 'UNKNOWN',
            'artifact_count': thread['artifact_count'],
            'purpose': thread.get('purpose') or 'No purpose recorded',
            'path': thread['path'],
            'latest_artifact': latest_artifact['file'] if latest_artifact else None
        }
        registry.append(entry)
        
        print(f"\n  Thread {int(thread['thread_id']):4d} (ch {thread['channel_id']:3s}): "
              f"task={task_id:25s} actor={entry['actor']:15s} artifacts={thread['artifact_count']}")
    
    # Write summary
    print(f"\n{'='*80}")
    print(f"SUMMARY:")
    print(f"{'='*80}")
    print(f"Total threads: {len(threads)}")
    
    orphan_count = sum(1 for t in threads.values() if not t.get('actor') or t.get('actor') == 'UNKNOWN')
    print(f"Orphan threads (no actor): {orphan_count}")
    
    # Order by thread_id
    registry_sorted = sorted(registry, key=lambda x: int(x['thread_id']))
    
    # Save to JSON for next phase
    with open('_thread_registry.json', 'w') as f:
        json.dump(registry_sorted, f, indent=2)
    
    print(f"\nRegistry saved to _thread_registry.json")
    print(f"\nTotal entries: {len(registry_sorted)}")
    
    # Display first 10
    print(f"\nFirst 10 entries:")
    for entry in registry_sorted[:10]:
        print(f"  {entry['task_id']:30s} Thread {entry['thread_id']:4s} ({entry['channel_id']:3s}) - {entry['actor']:15s}")

if __name__ == '__main__':
    main()
