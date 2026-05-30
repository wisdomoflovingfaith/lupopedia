#!/usr/bin/env python3
import os
import json
import re
import shutil
from datetime import datetime, timedelta

def main():
    repo = os.getcwd()
    pending_dir = os.path.join(repo, "changelog-pending")
    archive_dir = os.path.join(repo, "changelog-archive")
    target_changelog = os.path.join(repo, "docs/versions/4.1.2/CHANGELOG.md")
    target_oq = os.path.join(repo, "docs/versions/4.1.2/status/open_questions.md")

    if not os.path.exists(archive_dir):
        os.makedirs(archive_dir)

    # 1. Read pending
    entries = []
    for filename in os.listdir(pending_dir):
        if filename.endswith(".json"):
            path = os.path.join(pending_dir, filename)
            with open(path, 'r', encoding='utf-8') as f:
                data = json.load(f)
                data['filename'] = filename
                entries.append(data)

    if not entries:
        print("No pending entries.")
        return

    # 2. Sort by timestamp
    entries.sort(key=lambda x: x['timestamp'])

    # 3. Merge adjacent
    merged_entries = []
    if entries:
        current = entries[0]
        # Ensure files_changed is a set for easy deduplication during merge
        current['files_changed'] = set(current['files_changed'])
        current['filenames'] = [current['filename']]
        
        for next_entry in entries[1:]:
            # Convert timestamps to datetime for comparison
            fmt = "%Y%m%d%H%M%S"
            t1 = datetime.strptime(current['timestamp'], fmt)
            t2 = datetime.strptime(next_entry['timestamp'], fmt)
            
            if (next_entry['agent_id'] == current['agent_id'] and 
                next_entry['thread'] == current['thread'] and 
                (t2 - t1) <= timedelta(minutes=10)):
                
                # Merge
                current['summary'] += "; " + next_entry['summary']
                current['files_changed'].update(next_entry['files_changed'])
                current['filenames'].append(next_entry['filename'])
                # Keep original timestamp as the entry start
            else:
                merged_entries.append(current)
                current = next_entry
                current['files_changed'] = set(current['files_changed'])
                current['filenames'] = [current['filename']]
        merged_entries.append(current)

    # 4. Format and append to CHANGELOG.md
    with open(target_changelog, 'a', encoding='utf-8') as f:
        for entry in merged_entries:
            ts = entry['timestamp']
            # Format YYYYMMDDHHIISS to YYYY-MM-DD HH:MM
            formatted_ts = f"{ts[0:4]}-{ts[4:6]}-{ts[6:8]} {ts[8:10]}:{ts[10:12]}"
            
            f.write(f"\n### {formatted_ts} UTC -- {entry['agent_id'].capitalize()} -- {entry['summary'].split(';')[0]}\n\n")
            f.write("Context:\n")
            f.write(f"- channel_key: {entry['channel']}\n")
            f.write(f"- thread_id: {entry['thread'] or 'none'}\n")
            f.write(f"- artifact: {', '.join(sorted(list(entry['files_changed'])))}\n")
            f.write("- inherited_from: none\n\n")
            
            f.write("Changes:\n")
            for sub_summary in entry['summary'].split('; '):
                f.write(f"- {sub_summary}\n")
            f.write("\n")
            
            f.write("Result:\n")
            f.write(f"- Processed via buffer consolidation.\n")
            
            # Add hidden merge markers
            for fn in entry['filenames']:
                f.write(f"<!-- changelog-merged: {fn} -->\n")

    # 5. Extract Open Questions
    new_oqs = []
    for entry in merged_entries:
        if entry.get('open_questions'):
            for oq in entry['open_questions']:
                new_oqs.append({
                    'q': oq,
                    'agent': entry['agent_id'],
                    'thread': entry['thread'],
                    'timestamp': entry['timestamp']
                })

    if new_oqs:
        with open(target_oq, 'a', encoding='utf-8') as f:
            f.write("\n\n## New Questions from Consolidation\n")
            for oq in new_oqs:
                f.write(f"\n- **QUESTION:** {oq['q']}\n")
                f.write(f"  - **AGENT:** {oq['agent']}\n")
                f.write(f"  - **THREAD:** {oq['thread']}\n")
                f.write(f"  - **TIMESTAMP:** {oq['timestamp']}\n")

    # 6. Archive
    for entry in entries:
        src = os.path.join(pending_dir, entry['filename'])
        dest = os.path.join(archive_dir, entry['filename'])
        shutil.move(src, dest)
        print(f"Archived {entry['filename']}")

    # 7. Update when_updated in CHANGELOG.md
    with open(target_changelog, 'r', encoding='utf-8') as f:
        lines = f.readlines()
    
    # Get current UTC
    now_utc = datetime.utcnow().strftime("%Y%m%d%H%M%S")
    for i, line in enumerate(lines):
        if line.startswith("  when_updated:"):
            lines[i] = f'  when_updated: "{now_utc}"\n'
            break
    
    with open(target_changelog, 'w', encoding='utf-8') as f:
        f.writelines(lines)

    print(f"Consolidated {len(entries)} entries into {len(merged_entries)} blocks.")

if __name__ == "__main__":
    main()
