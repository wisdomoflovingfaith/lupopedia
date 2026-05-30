#!/usr/bin/env python
"""
Event Journal Writer — Filesystem Event Sourcing Foundation
ATHENA DIRECTIVE 4.0.88 — Journal-driven import system.

Purpose:
  Write every filesystem artifact write (create, update) as an immutable journal entry.
  Journal is the source of truth for what changed and when.
  
Location: database/journal/
Format: JSON event records (one per event)

Journal Entry Structure:
  {
    "event_id": "20260326190001000001",
    "event_type": "artifact_write",
    "file_path": "channels/66/threads/2012/file.md",
    "file_hash": "sha256_hash",
    "actor_id": 1,
    "created_utc": "20260326190001",
    "sequence": 1,
    "file_status": "created" | "updated",
    "file_size": 1024,
    "content_preview": "first 500 chars"
  }
"""

import os
import sys
import json
import hashlib
from datetime import datetime

JOURNAL_DIR = 'database/journal'

def ensure_journal_dir():
    """Create journal directory if it doesn't exist."""
    os.makedirs(JOURNAL_DIR, exist_ok=True)

def get_current_ymdhis():
    """Return current UTC time in YYYYMMDDHHIISS format."""
    return datetime.utcnow().strftime('%Y%m%d%H%M%S')

def compute_file_hash(file_path):
    """Compute SHA256 hash of file."""
    if not os.path.exists(file_path):
        return None
    
    hash_obj = hashlib.sha256()
    try:
        with open(file_path, 'rb') as f:
            hash_obj.update(f.read())
        return hash_obj.hexdigest()
    except Exception as e:
        print(f"Error hashing {file_path}: {e}", file=sys.stderr)
        return None

def read_file_preview(file_path, max_chars=500):
    """Read first N characters of file for preview."""
    try:
        with open(file_path, 'r', encoding='utf-8', errors='ignore') as f:
            return f.read(max_chars)
    except:
        return None

def create_journal_entry(file_path, file_status='created', event_id=None, actor_id=0):
    """
    Create a journal event for a filesystem write.
    
    Args:
        file_path (str): Relative path from project root
        file_status (str): 'created' or 'updated'
        event_id (str): Optional explicit event_id (YYYYMMDDHHIISS + sequence)
        actor_id (int): Actor ID responsible for write (default: 0 = system)
    
    Returns:
        dict: Journal entry
    """
    if event_id is None:
        # Generate event_id from current timestamp + sequence
        # For simplicity, use timestamp + random suffix
        import random
        timestamp = get_current_ymdhis()
        seq = random.randint(1, 999999)
        event_id = f"{timestamp}{seq:06d}"
    
    file_hash = compute_file_hash(file_path)
    file_size = os.path.getsize(file_path) if os.path.exists(file_path) else 0
    preview = read_file_preview(file_path)
    
    entry = {
        'event_id': event_id,
        'event_type': 'artifact_write',
        'file_path': file_path,
        'file_hash': file_hash,
        'actor_id': actor_id,
        'created_utc': get_current_ymdhis(),
        'file_status': file_status,
        'file_size': file_size,
        'content_preview': preview[:500] if preview else None,
    }
    
    return entry

def write_journal_entry(entry, actor_id=0):
    """
    Write a journal entry to disk.
    
    Filename: {event_id}.json
    Location: database/journal/{event_id}.json
    
    Args:
        entry (dict): Journal entry dict
        actor_id (int): Actor writing journal (for audit)
    
    Returns:
        str: Path to written journal file
    """
    ensure_journal_dir()
    
    event_id = entry.get('event_id')
    journal_file = os.path.join(JOURNAL_DIR, f'{event_id}.json')
    
    try:
        with open(journal_file, 'w', encoding='utf-8') as f:
            json.dump(entry, f, indent=2, default=str)
        return journal_file
    except Exception as e:
        print(f"Error writing journal entry {event_id}: {e}", file=sys.stderr)
        return None

def load_journal_entries(limit=None):
    """
    Load all journal entries from disk (in order).
    
    Args:
        limit (int): Optional limit on number to load
    
    Returns:
        list: Journal entries sorted by event_id
    """
    ensure_journal_dir()
    
    entries = []
    for filename in sorted(os.listdir(JOURNAL_DIR)):
        if filename.endswith('.json'):
            try:
                with open(os.path.join(JOURNAL_DIR, filename), 'r') as f:
                    entry = json.load(f)
                    entries.append(entry)
                    if limit and len(entries) >= limit:
                        break
            except Exception as e:
                print(f"Error loading journal {filename}: {e}", file=sys.stderr)
    
    return entries

def journal_status_report():
    """Generate status report of journal contents."""
    entries = load_journal_entries()
    
    print(f"Journal Status Report")
    print(f"====================")
    print(f"Total entries: {len(entries)}")
    
    if entries:
        print(f"Oldest entry: {entries[0]['created_utc']}")
        print(f"Newest entry: {entries[-1]['created_utc']}")
        
        created = sum(1 for e in entries if e['file_status'] == 'created')
        updated = sum(1 for e in entries if e['file_status'] == 'updated')
        print(f"Created: {created}, Updated: {updated}")

if __name__ == '__main__':
    if len(sys.argv) > 1:
        cmd = sys.argv[1]
        
        if cmd == 'write' and len(sys.argv) > 2:
            file_path = sys.argv[2]
            file_status = sys.argv[3] if len(sys.argv) > 3 else 'created'
            
            entry = create_journal_entry(file_path, file_status)
            journal_path = write_journal_entry(entry)
            print(f"Journal entry written: {journal_path}")
        
        elif cmd == 'status':
            journal_status_report()
        
        elif cmd == 'load':
            limit = int(sys.argv[2]) if len(sys.argv) > 2 else 10
            entries = load_journal_entries(limit)
            for entry in entries:
                print(json.dumps(entry, indent=2, default=str))
    else:
        print("Usage:")
        print("  write <file_path> [created|updated]  - Write journal entry")
        print("  status                                 - Show journal status")
        print("  load [limit]                           - Load journal entries")
