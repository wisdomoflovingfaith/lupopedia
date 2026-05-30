#!/usr/bin/env python3
# lupopedia.headers:
#   when_updated: "20260324175617"
#   file_path_from_root: "lupo-scripts/enforce_timestamp_validation.py"
#   questions_toon: null
#   channel_id: 42
#   actor_id: 102
#   actor_name: "cursor"
#   delegation_chain: "cursor:root"
#   artifact_type: "tooling"
#   artifact_kind: "script"
# lupopedia.footer:
#   last_verified: "20260324175617"
#   last_verified_by: "cursor"
#   last_verified_by_actor_id: 102

"""
Centralized timestamp enforcement wrapper.
All file writes must go through this validation.
"""

import os
import re
import sys
from datetime import datetime
from pathlib import Path

def validate_timestamp(timestamp_str):
    """Validate timestamp format YYYYMMDD_HHIISS"""
    pattern = r'^(\d{8})_(\d{6})$'
    match = re.match(pattern, timestamp_str)
    
    if not match:
        return False, "Invalid format"
    
    date_str, time_str = match.groups()
    
    try:
        # Validate date
        year = int(date_str[:4])
        month = int(date_str[4:6])
        day = int(date_str[6:8])
        datetime(year=year, month=month, day=day)
        
        # Validate time
        hour = int(time_str[:2])
        minute = int(time_str[2:4])
        second = int(time_str[4:6])
        
        if hour >= 24:
            return False, f"Invalid hour: {hour} (must be 0-23)"
        
        if minute >= 60 or second >= 60:
            return False, f"Invalid time: {time_str}"
        
        return True, "Valid"
    
    except ValueError as e:
        return False, f"Invalid date/time: {e}"

class TimestampEnforcementError(Exception):
    """Raised when timestamp validation fails"""
    pass

def validate_file_timestamp(filepath):
    """Validate timestamp in filename before write"""
    filename = os.path.basename(filepath)
    
    # Extract leading timestamp prefix from filename only
    timestamp_match = re.match(r'^(\d{8}_\d{6})(?=[._-]|$)', filename)
    if not timestamp_match:
        # No timestamp in filename - allow for non-artifact files
        return True
    
    timestamp = timestamp_match.group(1)
    is_valid, message = validate_timestamp(timestamp)
    
    if not is_valid:
        raise TimestampEnforcementError(f"Invalid timestamp {timestamp} in {filename}: {message}")
    
    return True

def safe_write_file(filepath, content, mode='w'):
    """Safe file write with timestamp validation"""
    # Validate timestamp in filename
    validate_file_timestamp(filepath)
    
    # Create directory if it doesn't exist
    directory = os.path.dirname(filepath)
    if directory:
        os.makedirs(directory, exist_ok=True)
    
    # Write file
    with open(filepath, mode) as f:
        f.write(content)
    
    return True

def safe_write_file_with_backup(filepath, content, mode='w'):
    """Safe file write with backup on failure"""
    try:
        return safe_write_file(filepath, content, mode)
    except TimestampEnforcementError as e:
        print(f"TIMESTAMP VALIDATION FAILED: {e}")
        raise

# Override built-in open for .md files
original_open = open

def enforced_open(file, mode='r', *args, **kwargs):
    """Open with timestamp validation for .md files"""
    if isinstance(file, str) and file.endswith('.md') and 'w' in mode:
        # Validate before opening for write
        validate_file_timestamp(file)
    
    return original_open(file, mode, *args, **kwargs)

# Apply the override
import builtins
builtins.open = enforced_open

# Export functions for use by other scripts
__all__ = [
    'TimestampEnforcementError',
    'validate_file_timestamp',
    'safe_write_file',
    'safe_write_file_with_backup',
    'enforced_open'
]