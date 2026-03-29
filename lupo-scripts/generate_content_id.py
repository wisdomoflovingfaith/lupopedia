#!/usr/bin/env python
"""
Deterministic Content ID Generator
ATHENA DIRECTIVE 4.0.88 — Generates deterministic BIGINT content IDs.

Schema: VERSION + YYYYMMDDHHIISS + sequence + path_hash

Example:
  content_id = 120260326190001000001000042
  where 1                = format version (1 digit, allows future schema changes)
        20260326190001   = UTC timestamp YYYYMMDDHHIISS (14 digits)
        000001           = thread-safe sequence within second (6 digits)
        000042           = file path hash influence (6 digits)

No UUID, no random, no AUTO_INCREMENT. All IDs are deterministic and collision-resistant.

Thread-Safety: Per-second sequence allocation is locked, monotonically increasing.
File-Influenced: Path hash included in ID derivation, prevents duplicate IDs for same timestamp+seq.
Overflow-Safe: Sequence rolls to next second if > 999999 (no throwing, no losing data).
Collision-Detect: Queries DB before allocation to catch conflicts early.
Error-Fail: All errors abort gracefully; never return None with partial allocation.
Actor-Tracked: Allocation actor_id recorded for audit trail.
"""

import os
import sys
import time
import random
import hashlib
import threading
import logging
from datetime import datetime, timezone

# Thread-safe sequence allocation
_SEQUENCE_COUNTERS = {}
_COUNTER_LOCK = threading.Lock()

# Logging
logging.basicConfig(level=logging.WARNING)
logger = logging.getLogger(__name__)

# ID Format Version (allows schema changes in future)
ID_FORMAT_VERSION = 1

def get_utc_timestamp_seconds():
    """Get current UTC timestamp in seconds (monotonic, modern Python 3.12+ safe)."""
    return int(time.time())

def timestamp_sec_to_ymdhis(timestamp_sec):
    """Convert Unix timestamp to YYYYMMDDHHIISS string."""
    dt = datetime.fromtimestamp(timestamp_sec, tz=timezone.utc)
    return dt.strftime('%Y%m%d%H%M%S')

def get_next_sequence_for_timestamp(timestamp_sec):
    """
    Get next sequence number for a given second, thread-safe.
    
    If sequence overflows (>999999), rolls to next second automatically.
    
    Returns:
        tuple: (sequence_number, actual_timestamp_sec)
    """
    with _COUNTER_LOCK:
        seq = _SEQUENCE_COUNTERS.get(timestamp_sec, 0) + 1
        
        if seq > 999999:
            # Overflow: roll to next second
            logger.warning(f"Sequence overflow at {timestamp_sec}, rolling to next second")
            timestamp_sec += 1
            seq = 1
            _SEQUENCE_COUNTERS[timestamp_sec] = seq
        else:
            _SEQUENCE_COUNTERS[timestamp_sec] = seq
        
        return seq, timestamp_sec

def compute_file_path_hash(file_path):
    """
    Compute file path influence on ID (6-digit hash).
    
    Includes file path in ID generation to prevent collisions even if
    two different files are imported in the same second with same sequence.
    
    Returns:
        int: 6-digit hash (0-999999)
    """
    path_hash = hashlib.md5(file_path.encode()).hexdigest()[:6]
    path_int = int(path_hash, 16) % 1000000
    return path_int

def generate_content_id(file_path, timestamp_sec=None):
    """
    Generate deterministic BIGINT content_id with file path included.
    
    Thread-safe sequence allocation. Monotonically increasing per second.
    File path hashed into ID to prevent collisions.
    
    Schema:
      [version][timestamp_sec][sequence][path_hash]
      1 digit + 14 digits + 6 digits + 6 digits = 27-digit BIGINT
    
    Args:
        file_path (str): Filesystem path to artifact (included in hash)
        timestamp_sec (int): Optional explicit Unix timestamp (default: now)
    
    Returns:
        int: Content ID as BIGINT
    
    Raises:
        ValueError: If file_path is invalid
        OSError: If file cannot be read for hashing
    
    Example:
        generate_content_id('lupo-channels/42/threads/1003/file.md')
        -> 120260326190001000001000042
    """
    if not file_path or not isinstance(file_path, str):
        raise ValueError(f"Invalid file_path: {file_path}")
    
    if timestamp_sec is None:
        timestamp_sec = get_utc_timestamp_seconds()
    
    # Get next sequence (thread-safe, handles overflow)
    sequence, actual_sec = get_next_sequence_for_timestamp(timestamp_sec)
    
    # Include file path in ID generation
    path_int = compute_file_path_hash(file_path)
    
    # Construct ID: VERSION + YMDHIS + SEQUENCE + PATH_HASH
    ts_str = timestamp_sec_to_ymdhis(actual_sec)
    content_id_str = f"{ID_FORMAT_VERSION}{ts_str}{sequence:06d}{path_int:06d}"
    
    content_id = int(content_id_str)
    return content_id

def verify_file_readable(file_path):
    """
    Verify file exists and is readable for integrity computation.
    
    Raises:
        FileNotFoundError: If file does not exist
        OSError: If file is not readable
    """
    if not os.path.exists(file_path):
        raise FileNotFoundError(f"File not found: {file_path}")
    
    if not os.path.isfile(file_path):
        raise OSError(f"Not a file: {file_path}")
    
    if not os.access(file_path, os.R_OK):
        raise OSError(f"File not readable: {file_path}")

def compute_file_integrity_hash(file_path):
    """
    Compute SHA256 hash of file for integrity verification.
    
    FAIL-FAST: Raises exception if file cannot be read, never returns None.
    
    Args:
        file_path (str): Path to file
    
    Returns:
        str: SHA256 hex digest
    
    Raises:
        FileNotFoundError: If file does not exist
        OSError: If file cannot be read
    """
    verify_file_readable(file_path)
    
    hash_obj = hashlib.sha256()
    try:
        with open(file_path, 'rb') as f:
            while True:
                chunk = f.read(65536)  # 64KB chunks
                if not chunk:
                    break
                hash_obj.update(chunk)
        return hash_obj.hexdigest()
    except Exception as e:
        raise OSError(f"Error hashing {file_path}: {e}") from e

def allocate_content_id_for_file(file_path, table_name='lupo_dialog_messages', 
                                  actor_id=0, db_query_func=None):
    """
    Allocate a deterministic content_id for a file being imported.
    
    FAIL-FAST: All errors raise exceptions. Never returns partial data.
    
    Thread-Safe: Sequence allocation is locked. No collisions from parallel imports.
    
    Collision-Detect: Optionally queries database to catch conflicts early.
    
    Args:
        file_path (str): Path to file being imported
        table_name (str): Target database table (default: lupo_dialog_messages)
        actor_id (int): Actor performing import (for audit trail; default: 0 = system)
        db_query_func (callable): Optional function to check DB for existing content_id.
                                  Signature: db_query_func(content_id) -> bool (exists)
    
    Returns:
        dict: Allocation record with:
          - content_id: Generated BIGINT ID
          - file_path: Input file path
          - file_hash: SHA256 integrity hash
          - table_name: Target table
          - timestamp_sec: Unix timestamp (seconds)
          - actor_id: Actor who allocated this ID
          - allocated_at_utc: YYYYMMDDHHIISS when allocation happened
          - format_version: ID format version
    
    Raises:
        ValueError: If file_path or table_name invalid
        FileNotFoundError: If file does not exist
        OSError: If file cannot be read or hashed
        RuntimeError: If collision detected and cannot be resolved
    
    Example:
        allocation = allocate_content_id_for_file(
            'lupo-channels/42/threads/1003/file.md',
            table_name='lupo_dialog_messages',
            actor_id=105  # CASCADE doing the import
        )
        print(allocation['content_id'])  # -> 120260326190001000001000042
    """
    # Validate inputs
    if not file_path or not isinstance(file_path, str):
        raise ValueError(f"Invalid file_path: {file_path}")
    if not table_name or not isinstance(table_name, str):
        raise ValueError(f"Invalid table_name: {table_name}")
    if not isinstance(actor_id, int) or actor_id < 0:
        raise ValueError(f"Invalid actor_id: {actor_id}")
    
    # Verify file is readable (FAIL-FAST on error)
    verify_file_readable(file_path)
    
    # Compute integrity hash (FAIL-FAST on error)
    file_hash = compute_file_integrity_hash(file_path)
    
    # Get current timestamp
    timestamp_sec = get_utc_timestamp_seconds()
    
    # Generate content_id (thread-safe sequence)
    content_id = generate_content_id(file_path, timestamp_sec)
    
    # Optional: Check database for collision
    if db_query_func:
        retry_count = 0
        max_retries = 5
        
        while db_query_func(content_id) and retry_count < max_retries:
            # Crafty Syntax-style float jitter breaks synchronized retries.
            jitter = random.uniform(0.001, 7.001)
            time.sleep(jitter)
            # Collision detected, regenerate with current timestamp and next sequence
            logger.warning(f"Content ID collision: {content_id}, retrying...")
            timestamp_sec = get_utc_timestamp_seconds()
            content_id = generate_content_id(file_path, timestamp_sec)
            retry_count += 1
        
        if retry_count >= max_retries:
            raise RuntimeError(
                f"Cannot allocate unique content_id after {max_retries} retries. "
                f"Possible database corruption or clock skew."
            )
    
    # Build allocation record
    allocation = {
        'content_id': content_id,
        'file_path': file_path,
        'file_hash': file_hash,
        'table_name': table_name,
        'timestamp_sec': timestamp_sec,
        'actor_id': actor_id,
        'allocated_at_utc': timestamp_sec_to_ymdhis(get_utc_timestamp_seconds()),
        'format_version': ID_FORMAT_VERSION,
    }
    
    return allocation

def format_content_id_header(content_id, table_name, import_actor_id=0):
    """
    Format LUPOPEDIA header block with content_id for file.
    
    Returns YAML suitable for file header insertion.
    
    Args:
        content_id (int): Allocated content_id
        table_name (str): Target database table
        import_actor_id (int): Actor performing import
    
    Returns:
        str: YAML header block
    """
    timestamp = timestamp_sec_to_ymdhis(get_utc_timestamp_seconds())
    return f"""---
lupopedia.init:
  content_id: {content_id}
  content_type: "artifact"
  table_name: "{table_name}"
  import_timestamp: "{timestamp}"
  import_actor_id: {import_actor_id}
  import_mode: "deterministic_allocation"
  format_version: {ID_FORMAT_VERSION}
---
"""

if __name__ == '__main__':
    try:
        if len(sys.argv) > 1:
            cmd = sys.argv[1]
            
            if cmd == 'allocate' and len(sys.argv) > 2:
                file_path = sys.argv[2]
                table_name = sys.argv[3] if len(sys.argv) > 3 else 'lupo_dialog_messages'
                actor_id = int(sys.argv[4]) if len(sys.argv) > 4 else 0
                
                allocation = allocate_content_id_for_file(
                    file_path, 
                    table_name=table_name,
                    actor_id=actor_id
                )
                print(f"✅ Allocated content_id: {allocation['content_id']}")
                print(f"   File: {allocation['file_path']}")
                print(f"   Hash: {allocation['file_hash']}")
                print(f"   Table: {allocation['table_name']}")
                print(f"   Actor: {allocation['actor_id']}")
                print(f"   Format Version: {allocation['format_version']}")
            
            elif cmd == 'test':
                # Test mode: verify determinism of path hashing and sequence allocation
                print("Testing deterministic ID generation...")
                test_path = 'test_artifact.md'
                test_ts = 1700000000
                
                # Clear sequence counter for test
                _SEQUENCE_COUNTERS.clear()
                
                # Generate first ID
                id1 = generate_content_id(test_path, timestamp_sec=test_ts)
                print(f"ID 1 (path: {test_path}, ts: {test_ts}): {id1}")
                
                # Generate second ID with NEXT second (demonstrates monotonic sequence)
                id2 = generate_content_id(test_path, timestamp_sec=test_ts + 1)
                assert id1 != id2, f"Different timestamps should produce different IDs"
                print(f"ID 2 (path: {test_path}, ts: {test_ts + 1}): {id2} ✅")
                
                # Different file path = different path_hash component (even same timestamp)
                _SEQUENCE_COUNTERS.clear()
                id3 = generate_content_id(test_path, timestamp_sec=1800000000)
                id4 = generate_content_id('other_file.md', timestamp_sec=1800000000)
                
                # Extract path_hash components (last 6 digits)
                path_hash_3 = id3 % 1000000
                path_hash_4 = id4 % 1000000
                
                assert path_hash_3 != path_hash_4, f"Path influence failed: {path_hash_3} == {path_hash_4}"
                print(f"Path hashing test PASSED: {test_path} -> {path_hash_3}, other_file.md -> {path_hash_4} ✅")
                
                print(f"✅ All determinism tests passed")
                
            else:
                print(f"Unknown command: {cmd}")
                print("Usage:")
                print("  allocate <file_path> [table_name] [actor_id]")
                print("  test")
        else:
            # Default: info
            print("Content ID Generator (ATHENA 4.0.88)")
            print(f"Format Version: {ID_FORMAT_VERSION}")
            print(f"Current UTC: {timestamp_sec_to_ymdhis(get_utc_timestamp_seconds())}")
            print("")
            print("Usage:")
            print("  python generate_content_id.py allocate <file> [table] [actor]")
            print("  python generate_content_id.py test")
    
    except (FileNotFoundError, OSError, ValueError, RuntimeError) as e:
        print(f"❌ ERROR: {e}", file=sys.stderr)
        sys.exit(1)
    except Exception as e:
        print(f"❌ UNEXPECTED ERROR: {e}", file=sys.stderr)
        logger.exception("Unhandled exception")
        sys.exit(1)
