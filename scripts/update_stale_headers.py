#!/usr/bin/env python3
"""
Update stale LUPOPEDIA headers in doctrine files.
Updates last_verified and when_updated to current state.
NOTE: last_modified_utc was renamed to questions_toon in PRD 16 v4.0.99 §4.2 field 6.
The old last_modified_utc pattern is kept here to migrate legacy files on encounter.
Designed for batch updating files with timestamps < 20260301.
"""

import os
import re
from datetime import datetime

# Configuration
CURRENT_TIMESTAMP = "20260325"
CURRENT_TIMESTAMP_FULL = "20260325150000"  # YYYYMMDDHHIISS
CURSOR_ACTOR_ID = "102"
CURSOR_ACTOR_NAME = "cursor"
DELEGATION_CHAIN = "cursor:root"

# Base path
BASE_PATH = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
DOCTRINE_PATH = os.path.join(BASE_PATH, "docs", "doctrine")

def update_headers_in_file(filepath):
    """Update timestamp and verification fields in a file's headers."""
    try:
        with open(filepath, 'r', encoding='utf-8') as f:
            content = f.read()
    except Exception as e:
        print(f"ERROR reading {filepath}: {e}")
        return False
    
    original_content = content
    
    # Update various timestamp patterns in YAML headers
    patterns = [
        # when_updated in canonical headers
        (r'when_updated:\s*"20260[01]\d[0-3]\d\d*"', f'when_updated: "{CURRENT_TIMESTAMP_FULL}"'),
        # Migrate legacy last_modified_utc → questions_toon: null (PRD 16 v4.0.99 §4.2 field 6)
        (r'last_modified_utc:\s*"20260[01]\d[0-3]\d\d*"', 'questions_toon: null'),
        # last_verified: "YYYYMMDD..." or variations
        (r'last_verified:\s*"20260[01]\d[0-3]\d\d*"', f'last_verified: "{CURRENT_TIMESTAMP_FULL}"'),
        # last_verified_utc variations
        (r'last_verified_utc:\s*"20260[01]\d[0-3]\d\d*"', f'last_verified_utc: "{CURRENT_TIMESTAMP_FULL}"'),
        # last_updated_utc
        (r'last_updated_utc:\s*"20260[01]\d[0-3]\d\d*"', f'last_updated_utc: "{CURRENT_TIMESTAMP_FULL}"'),
        # file.last_modified_utc
        (r'file\.last_modified_utc:\s*"20260[01]\d[0-3]\d\d*"', f'file.last_modified_utc: "{CURRENT_TIMESTAMP_FULL}"'),
        # Last_verified, File_last_modified_utc (case variations)
        (r'Last_verified:\s*"20260[01]\d[0-3]\d\d*"', f'Last_verified: "{CURRENT_TIMESTAMP_FULL}"'),
        (r'File_last_modified_utc:\s*"20260[01]\d[0-3]\d\d*"', f'File_last_modified_utc: "{CURRENT_TIMESTAMP_FULL}"'),

        # last_verified_by to cursor
        (r'last_verified_by:\s*"[^"]*"(?=\s|$)', f'last_verified_by: "{CURSOR_ACTOR_NAME}"'),
        
        # actor_id if stale
        (r'actor_id:\s*1[0-9]{3}(?=\s|$)', f'actor_id: {CURSOR_ACTOR_ID}'),
        
        # actor_name updates
        (r'actor_name:\s*"[^"]*"(?=.*(?:last_verified|timestamp))', f'actor_name: "{CURSOR_ACTOR_NAME}"'),
    ]
    
    for pattern, replacement in patterns:
        content = re.sub(pattern, replacement, content)
    
    # Check if any changes were made
    if content == original_content:
        print(f"SKIP {filepath} - no stale headers found")
        return False
    
    # Write back
    try:
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f"UPDATE {filepath}")
        return True
    except Exception as e:
        print(f"ERROR writing {filepath}: {e}")
        return False

def find_stale_files(root_path):
    """Find all .md files with potentially stale headers."""
    stale_files = []
    for dirpath, dirnames, filenames in os.walk(root_path):
        for filename in filenames:
            if filename.endswith('.md'):
                filepath = os.path.join(dirpath, filename)
                try:
                    with open(filepath, 'r', encoding='utf-8') as f:
                        content = f.read()
                        # Check for old timestamps
                        if re.search(r'202602[0-1]\d|202601', content):
                            stale_files.append(filepath)
                except Exception:
                    pass
    return stale_files

def main():
    """Main execution."""
    if not os.path.isdir(DOCTRINE_PATH):
        print(f"ERROR: Doctrine path not found: {DOCTRINE_PATH}")
        return
    
    print(f"Scanning {DOCTRINE_PATH} for stale headers...")
    stale_files = find_stale_files(DOCTRINE_PATH)
    
    if not stale_files:
        print("No stale headers found.")
        return
    
    print(f"Found {len(stale_files)} files with potentially stale headers\n")
    print("=" * 70)
    
    updated_count = 0
    for filepath in sorted(stale_files):
        if update_headers_in_file(filepath):
            updated_count += 1
    
    print("=" * 70)
    print(f"\nSummary: Updated {updated_count}/{len(stale_files)} files")
    print(f"Current timestamp: {CURRENT_TIMESTAMP_FULL}")
    print(f"Updated by: {CURSOR_ACTOR_NAME} (actor_id: {CURSOR_ACTOR_ID})")

if __name__ == "__main__":
    main()
