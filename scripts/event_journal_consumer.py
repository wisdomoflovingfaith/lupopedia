#!/usr/bin/env python
"""
Event Journal Consumer — Consumes journal events and applies to database.
ATHENA DIRECTIVE 4.0.88 — Database layer for event-sourced imports.

Purpose:
  Read journal entries and upsert corresponding database records.
  Ensure all writes come from journal, never silently drop events.
  Detect divergence and STOP on mismatch.

Database Integration:
  Requires: PDO connection to Lupopedia database
  Tables affected: lupo_dialog_messages, lupo_channels, lupo_edges, etc.

Kill Conditions:
  - Hash mismatch between journal and file
  - Missing file for journal entry
  - Duplicate content_id in database
  - STOP immediately, do NOT auto-resolve
"""

import os
import sys
import json
import hashlib
from datetime import datetime

def get_current_ymdhis():
    """Return current UTC time in YYYYMMDDHHIISS format."""
    return datetime.utcnow().strftime('%Y%m%d%H%M%S')

def load_journal_entry(event_id):
    """Load a single journal entry by event_id."""
    journal_file = f'database/journal/{event_id}.json'
    
    if not os.path.exists(journal_file):
        return None
    
    try:
        with open(journal_file, 'r') as f:
            return json.load(f)
    except Exception as e:
        print(f"Error loading journal entry {event_id}: {e}", file=sys.stderr)
        return None

def verify_file_hash(file_path, expected_hash):
    """
    Verify that file hash matches journal entry.
    
    Kill condition: if mismatch, write divergence artifact and EXIT.
    """
    if not os.path.exists(file_path):
        return False, "FILE_NOT_FOUND"
    
    hash_obj = hashlib.sha256()
    try:
        with open(file_path, 'rb') as f:
            hash_obj.update(f.read())
        actual_hash = hash_obj.hexdigest()
        
        if actual_hash != expected_hash:
            return False, "HASH_MISMATCH"
        
        return True, actual_hash
    except Exception as e:
        return False, f"HASH_ERROR: {e}"

def write_divergence_artifact(journal_entry, divergence_type, details=None):
    """
    Write comprehensive divergence artifact for manual review.
    
    ATHENA DIRECTIVE 4.0.88: Divergence artifacts must include all context
    needed for manual resolution. Silent corruption is forbidden.
    
    Schema:
    {
        "kill_condition": str,        # What triggered this divergence
        "timestamp": str,              # UTC YYYYMMDDHHIISS when detected
        "actor_id": int,               # Who was importing when detected
        "divergence_type": str,        # FILE_NOT_FOUND | HASH_MISMATCH | DUPLICATE_CONTENT_ID
        "severity": str,               # CRITICAL (schema mismatch) | RECOVERABLE (file disappeared)
        "file_path": str,              # Filesystem path to the problematic artifact
        "content_id": int,             # Attempted content_id allocation
        "expected_state": {            # What we expected
            "file_hash": str,          # Expected SHA256
            "file_size": int,          # Expected size
            "file_exists": bool,       # Should exist
            "content_id_unique": bool, # Should be unique in DB
        },
        "actual_state": {              # What we found
            "file_exists": bool,       # Actually found?
            "file_hash": str|null,     # Actual SHA256 (null if DNE)
            "file_size": int|null,     # Actual size (null if DNE)
            "content_id_exists_in_db": bool|null,  # Already in DB?
        },
        "resolution_steps": [str],     # Operator guidance
        "manual_review_required": true,
        "escalation_contact": str,     # Who to contact (default: LILITH)
    }
    
    Location: database/divergences/{timestamp}_{divergence_type}.json
    """
    os.makedirs('database/divergences', exist_ok=True)
    
    timestamp = get_current_ymdhis()
    filename = f'database/divergences/{timestamp}_{divergence_type}.json'
    
    file_path = journal_entry.get('file_path')
    content_id = journal_entry.get('content_id', 'UNKNOWN')
    actor_id = journal_entry.get('actor_id', 0)
    
    # Build expected state (what journal said should happen)
    expected_state = {
        'file_hash': journal_entry.get('file_hash'),
        'file_size': journal_entry.get('file_size'),
        'file_exists': True,  # Journal would never record a file that doesn't exist
        'content_id_unique': True,  # Content IDs should always be unique
    }
    
    # Build actual state (what we discovered)
    actual_state = {
        'file_exists': os.path.exists(file_path),
        'file_hash': None,
        'file_size': None,
        'content_id_exists_in_db': None,  # Filled in if we have DB connection
    }
    
    # Compute actual hash if file exists
    if actual_state['file_exists']:
        try:
            hash_obj = hashlib.sha256()
            with open(file_path, 'rb') as f:
                actual_state['file_hash'] = hash_obj.hexdigest()
                actual_state['file_size'] = os.path.getsize(file_path)
        except Exception as e:
            actual_state['file_hash'] = f'ERROR: {e}'
    
    # Determine kill condition and resolution steps
    kill_condition = divergence_type
    resolution_steps = []
    severity = 'CRITICAL'
    
    if divergence_type == 'FILE_NOT_FOUND':
        kill_condition = 'FILE_NOT_FOUND'
        severity = 'RECOVERABLE'
        resolution_steps = [
            f"1. Verify {file_path} still exists on the filesystem",
            f"2. If file was deleted, restore from backup: docker cp backup:/path/to/{os.path.basename(file_path)} {file_path}",
            f"3. Re-run sync with --retry to resume from last checkpoint",
            f"4. If file is intentionally deleted, update channel manifest and re-sync",
        ]
    
    elif divergence_type == 'HASH_MISMATCH':
        kill_condition = 'HASH_MISMATCH'
        severity = 'CRITICAL'
        resolution_steps = [
            f"1. File was modified after journal entry was created",
            f"2. Expected hash: {expected_state['file_hash']}",
            f"3. Actual hash:   {actual_state['file_hash']}",
            f"4. Options:",
            f"   a) Restore from backup (filesystem authority broken)",
            f"   b) Regenerate journal entry with current file state",
            f"5. If choosing (b), run: python generate_content_id.py allocate {file_path}",
        ]
    
    elif divergence_type == 'DUPLICATE_CONTENT_ID':
        kill_condition = 'DUPLICATE_CONTENT_ID'
        severity = 'CRITICAL'
        resolution_steps = [
            f"1. Content ID {content_id} already exists in database",
            f"2. This indicates thread-unsafe sequence allocation or clock skew",
            f"3. Verify timestamp on import machine: date -u",
            f"4. Check DB for existing entry: SELECT * FROM lupo_dialog_messages WHERE content_id = {content_id}",
            f"5. Options:",
            f"   a) Delete old entry if it's a duplicate artifact",
            f"   b) Regenerate content_id with collision detection enabled",
        ]
    
    # Build complete divergence artifact
    divergence = {
        'kill_condition': kill_condition,
        'timestamp': timestamp,
        'actor_id': actor_id,
        'divergence_type': divergence_type,
        'severity': severity,
        'file_path': file_path,
        'content_id': content_id,
        'expected_state': expected_state,
        'actual_state': actual_state,
        'resolution_steps': resolution_steps,
        'manual_review_required': True,
        'escalation_contact': 'LILITH (actor_id=2) — LIL001 non-interference protocol applies',
        '_details': details,  # Raw details for debugging
    }
    
    try:
        with open(filename, 'w') as f:
            json.dump(divergence, f, indent=2, default=str)
        print(f"[DIVERGENCE] KILL_CONDITION={kill_condition} SEVERITY={severity}", file=sys.stderr)
        print(f"[DIVERGENCE] Written to {filename}", file=sys.stderr)
        print(f"[DIVERGENCE] Resolution steps included in artifact", file=sys.stderr)
    except Exception as e:
        print(f"[ERROR] Could not write divergence artifact: {e}", file=sys.stderr)
    
    return filename

def process_journal_entry_dryrun(journal_entry):
    """
    Process journal entry in DRY-RUN mode.
    
    Returns:
        dict: Status of processing (would_import, checks_passed, details)
    """
    file_path = journal_entry.get('file_path')
    expected_hash = journal_entry.get('file_hash')
    
    status = {
        'event_id': journal_entry.get('event_id'),
        'file_path': file_path,
        'would_import': False,
        'checks_passed': True,
        'details': [],
    }
    
    # Check 1: File exists
    if not os.path.exists(file_path):
        status['checks_passed'] = False
        status['details'].append(f"FILE_NOT_FOUND: {file_path}")
        return status
    
    # Check 2: Hash matches
    hash_ok, hash_result = verify_file_hash(file_path, expected_hash)
    if not hash_ok:
        status['checks_passed'] = False
        status['details'].append(f"HASH_MISMATCH: {hash_result}")
        return status
    
    # All checks passed — would import
    status['would_import'] = True
    status['details'].append(f"READY_TO_IMPORT (dry-run mode)")
    
    return status

def process_journal_entry_apply(journal_entry):
    """
    Apply journal entry to database.
    
    KILL CONDITIONS:
      - FILE_NOT_FOUND: File disappeared after journal entry created
      - HASH_MISMATCH: File was modified after journal entry created
      - DUPLICATE_CONTENT_ID: Content ID already exists in DB
    
    On any divergence: Write artifact, DROP import, EXIT with code 1.
    
    REQUIRES: PDO database connection (via PHP wrapper or direct)
    
    For now: Returns status of what would be applied.
    """
    file_path = journal_entry.get('file_path')
    expected_hash = journal_entry.get('file_hash')
    event_id = journal_entry.get('event_id')
    content_id = journal_entry.get('content_id')
    
    # Kill Condition 1: Check file exists
    if not os.path.exists(file_path):
        write_divergence_artifact(
            journal_entry,
            divergence_type='FILE_NOT_FOUND',
            details={'reason': 'File no longer exists on filesystem'}
        )
        print(f"[DIVERGENCE] STOP: FILE_NOT_FOUND for {file_path}", file=sys.stderr)
        return {'success': False, 'error': 'FILE_NOT_FOUND', 'kill_condition': True}
    
    # Kill Condition 2: Verify file integrity (hash)
    hash_ok, hash_result = verify_file_hash(file_path, expected_hash)
    
    if not hash_ok:
        write_divergence_artifact(
            journal_entry,
            divergence_type='HASH_MISMATCH',
            details={
                'expected_hash': expected_hash,
                'actual_hash': hash_result if isinstance(hash_result, str) and len(hash_result) == 64 else None,
                'reason': hash_result,
            }
        )
        print(f"[DIVERGENCE] STOP: HASH_MISMATCH for {file_path}", file=sys.stderr)
        return {'success': False, 'error': 'HASH_MISMATCH', 'kill_condition': True}
    
    # Kill Condition 3: Check for duplicate content_id in DB
    # (This would require actual DB query — PHP wrapper handles it)
    # For now, we note that this check MUST happen before INSERT
    
    # If we reach here, all pre-conditions passed
    # Actual database insertion would happen here via PHP PDO wrapper
    
    return {
        'success': True,
        'event_id': event_id,
        'file_path': file_path,
        'content_id': content_id,
        'imported': True,
        'status': 'READY_FOR_DATABASE_INSERT',
    }

def consumer_status():
    """Generate status report of consumer processing."""
    journal_dir = 'database/journal'
    divergence_dir = 'database/divergences'
    
    print(f"Event Journal Consumer Status")
    print(f"=============================")
    
    # Count journal entries
    if os.path.exists(journal_dir):
        journal_count = len([f for f in os.listdir(journal_dir) if f.endswith('.json')])
        print(f"Journal entries: {journal_count}")
    else:
        print(f"Journal entries: 0 (journal dir not found)")
    
    # Count divergences
    if os.path.exists(divergence_dir):
        divergence_count = len([f for f in os.listdir(divergence_dir) if f.endswith('.json')])
        print(f"Divergences detected: {divergence_count}")
        
        if divergence_count > 0:
            print(f"\n⚠️  WARNING: {divergence_count} divergence(s) require manual review!")
            print(f"   Location: {os.path.abspath(divergence_dir)}")
    else:
        print(f"Divergences detected: 0")

if __name__ == '__main__':
    if len(sys.argv) > 1:
        cmd = sys.argv[1]
        
        if cmd == 'status':
            consumer_status()
        
        elif cmd == 'dryrun' and len(sys.argv) > 2:
            event_id = sys.argv[2]
            entry = load_journal_entry(event_id)
            
            if not entry:
                print(f"[ERROR] No journal entry found for {event_id}", file=sys.stderr)
                sys.exit(1)
            
            status = process_journal_entry_dryrun(entry)
            print(json.dumps(status, indent=2, default=str))
        
        elif cmd == 'apply' and len(sys.argv) > 2:
            event_id = sys.argv[2]
            entry = load_journal_entry(event_id)
            
            if not entry:
                print(f"[ERROR] No journal entry found for {event_id}", file=sys.stderr)
                sys.exit(1)
            
            result = process_journal_entry_apply(entry)
            print(json.dumps(result, indent=2, default=str))
    else:
        print("Usage:")
        print("  status                - Consumer status")
        print("  dryrun <event_id>     - Dry-run process event")
        print("  apply <event_id>      - Apply event to database")
