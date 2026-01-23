#!/usr/bin/env python3
"""
Script to create a clean, chronologically ordered changelog_dialog_MONDAY_WOLFIE.md
from the existing changelog_dialog.md file.

This script:
1. Parses the existing changelog_dialog.md
2. Extracts all dialog entries (marked by ## YYYY-MM-DD headers)
3. Sorts them chronologically
4. Generates a new file with a fresh sovereign header
"""

import re
from datetime import datetime
from pathlib import Path

def parse_changelog_file(file_path):
    """Parse the changelog file and extract all entries."""
    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # Split by ## entries (dialog entries)
    # Pattern: ## YYYY-MM-DD — ... or ## YYYY-MM-DD HH:MM — ...
    entry_pattern = r'^## (\d{4}-\d{2}-\d{2})(?:\s+(\d{2}):(\d{2}))?'
    
    entries = []
    current_entry = None
    current_date = None
    current_time = None
    
    lines = content.split('\n')
    in_header = True
    
    for i, line in enumerate(lines):
        # Check if we've passed the header (look for first ## entry)
        if in_header and re.match(r'^## \d{4}-\d{2}-\d{2}', line):
            in_header = False
        
        # Check if this is a new entry header
        match = re.match(entry_pattern, line)
        if match:
            # Save previous entry if exists
            if current_entry:
                entries.append({
                    'date': current_date,
                    'time': current_time,
                    'content': '\n'.join(current_entry).rstrip()
                })
            
            # Start new entry
            current_date = match.group(1)
            if match.group(2) and match.group(3):
                current_time = (int(match.group(2)), int(match.group(3)))
            else:
                current_time = None
            current_entry = [line]
        elif current_entry is not None:
            # Continue current entry
            current_entry.append(line)
    
    # Don't forget the last entry
    if current_entry:
        entries.append({
            'date': current_date,
            'time': current_time,
            'content': '\n'.join(current_entry).rstrip()
        })
    
    return entries, None

def sort_entries_chronologically(entries):
    """Sort entries by date, then by time if available."""
    def sort_key(entry):
        date_str = entry['date']
        # Try to parse date
        try:
            date_obj = datetime.strptime(date_str, '%Y-%m-%d')
            # Use time from entry if available
            if entry.get('time'):
                hour, minute = entry['time']
                date_obj = date_obj.replace(hour=hour, minute=minute)
            else:
                # Try to find time in content
                time_match = re.search(r'(\d{2}):(\d{2})', entry['content'])
                if time_match:
                    hour = int(time_match.group(1))
                    minute = int(time_match.group(2))
                    date_obj = date_obj.replace(hour=hour, minute=minute)
            return date_obj
        except:
            return datetime.min
    
    return sorted(entries, key=sort_key)

def create_sovereign_header(utc_timestamp, utc_day):
    """Create a fresh sovereign WOLFIE header for Monday Wolfie."""
    return f"""---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.1.6
file.last_modified_utc: {utc_timestamp}
file.utc_day: {utc_day}
file.name: "changelog_dialog_MONDAY_WOLFIE.md"
file.lupopedia.5: 5
GOV-AD-PROHIBIT-001: true
UTC_TIMEKEEPER__CHANNEL_ID: "dev"
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
  - UTC_TIMEKEEPER__CHANNEL_ID
temporal_edges:
  actor_identity: "Eric (Captain Wolfie)"
  actor_location: "Sioux Falls, South Dakota"
  system_context: "Schema Freeze Active / Channel-ID Anchor Established / File-Sovereignty Transition / Table Count Violation: 140 tables (5 over 135 limit)"
dialog:
  speaker: MONDAY_WOLFIE
  target: @everyone @FLEET @CURSOR @LILITH @ROSE
  mood_RGB: "000000"
  message: "Changelog reorganized chronologically for Monday Wolfie review. All entries sorted by date and time. Sovereign header applied. File-Sovereignty compliance maintained."
tags:
  categories: ["documentation", "changelog", "dialog", "monday-wolfie"]
  collections: ["core-docs", "historical-records"]
  channels: ["dev", "public", "historical"]
file:
  name: "changelog_dialog_MONDAY_WOLFIE.md"
  title: "Changelog Dialog History - Monday Wolfie Chronological Edition"
  description: "Chronologically ordered dialog log for Monday Wolfie review - all entries sorted by date and time"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: active
  author: GLOBAL_CURRENT_AUTHORS
system_context:
  schema_state: "Frozen"
  table_count: 140
  table_ceiling: 135
  table_count_violation: true
  table_count_overage: 5
  database_logic_prohibited: true
  governance_active: ["GOV-AD-PROHIBIT-001", "LABS-001", "GOV-WOLFIE-HEADERS-001", "TABLE_COUNT_DOCTRINE", "LIMITS_DOCTRINE"]
  doctrine_mode: "File-Sovereignty"
---

# 📋 Changelog Dialog History - Monday Wolfie Chronological Edition

**Version:** GLOBAL_CURRENT_LUPOPEDIA_VERSION  
**Status:** ACTIVE  
**Created:** {utc_day}  
**Purpose:** Chronologically ordered dialog log for Monday Wolfie review  
**Total Entries:** [TO BE FILLED]

---

"""

def main():
    """Main execution function."""
    # Get current UTC time
    now = datetime.utcnow()
    utc_timestamp = now.strftime('%Y%m%d%H%M%S')
    utc_day = now.strftime('%Y%m%d')
    
    # Paths
    script_dir = Path(__file__).parent
    repo_root = script_dir.parent
    input_file = repo_root / 'dialogs' / 'changelog_dialog.md'
    output_file = repo_root / 'dialogs' / 'changelog_dialog_MONDAY_WOLFIE.md'
    
    print(f"Parsing {input_file}...")
    entries, header = parse_changelog_file(input_file)
    
    print(f"Found {len(entries)} entries")
    print("Sorting entries chronologically...")
    sorted_entries = sort_entries_chronologically(entries)
    
    print("Creating new file with sovereign header...")
    sovereign_header = create_sovereign_header(utc_timestamp, utc_day)
    
    # Update entry count in header
    sovereign_header = sovereign_header.replace('[TO BE FILLED]', str(len(sorted_entries)))
    
    # Write new file
    with open(output_file, 'w', encoding='utf-8') as f:
        f.write(sovereign_header)
        f.write('\n')
        
        for entry in sorted_entries:
            f.write(entry['content'])
            f.write('\n\n---\n\n')
    
    print(f"SUCCESS: Created {output_file}")
    print(f"   Total entries: {len(sorted_entries)}")
    print(f"   Date range: {sorted_entries[0]['date']} to {sorted_entries[-1]['date']}")

if __name__ == '__main__':
    main()
