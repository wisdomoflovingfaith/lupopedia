#!/usr/bin/env python3
"""
merge_changelog_buffer.py — Legacy version-folder Markdown fragment merge

Scans Markdown fragments under ``lupo-docs/versions/<VERSION>/buffer/``, sorts by a
``## [YYYY-MM-DD HH:MM UTC]`` anchor, appends to that version's CHANGELOG.md, and archives.

**JSON buffer (mandatory for IDE agents):** use ``lupo-changelog-pending/*.json`` per
CHANGELOG_BUFFER_ARCHITECTURE.md and run::

  python lupo-scripts/consolidate_lupo_changelog_pending.py --commit

That script appends to ``lupo-docs/versions/4.1.3/changelog.md`` by default and moves merged
JSON into ``lupo-changelog-archive/``. This file remains for the older per-version ``buffer/``
Markdown workflow only.
"""

import os
import re
import shutil
import argparse
from datetime import datetime

VERSION = "4.1.2"
CHANGELOG_PATH = f"lupo-docs/versions/{VERSION}/CHANGELOG.md"
BUFFER_DIR = f"lupo-docs/versions/{VERSION}/buffer"
ARCHIVE_DIR = os.path.join(BUFFER_DIR, "archive")

def parse_utc_timestamp(content):
    # Match ## [YYYY-MM-DD HH:MM UTC] or ## [YYYY-MM-DD HH:MM–HH:MM UTC]
    match = re.search(r'## \[(\d{4}-\d{2}-\d{2} \d{2}:\d{2})(?:–\d{2}:\d{2})? UTC\]', content)
    if match:
        return datetime.strptime(match.group(1), "%Y-%m-%d %H:%M")
    return None

def main():
    parser = argparse.ArgumentParser(description="Merge changelog fragments atomically.")
    parser.add_argument("--commit", action="store_true", help="Actually append to CHANGELOG.md and archive fragments.")
    args = parser.parse_args()

    if not os.path.exists(BUFFER_DIR):
        print(f"[ERROR] Buffer directory {BUFFER_DIR} not found.")
        return

    fragments = []
    for filename in os.listdir(BUFFER_DIR):
        if filename.endswith(".md") and filename != "README.md":
            path = os.path.join(BUFFER_DIR, filename)
            with open(path, 'r', encoding='utf-8') as f:
                content = f.read()
                timestamp = parse_utc_timestamp(content)
                if timestamp:
                    fragments.append({
                        'filename': filename,
                        'path': path,
                        'content': content.strip(),
                        'timestamp': timestamp
                    })
                else:
                    print(f"[WARN] No valid [UTC] anchor found in {filename}. Skipping.")

    if not fragments:
        print("No pending fragments found.")
        return

    # Sort fragments chronologically
    fragments.sort(key=lambda x: x['timestamp'])

    # Read current changelog for deduplication
    with open(CHANGELOG_PATH, 'r', encoding='utf-8') as f:
        current_changelog = f.read()

    new_entries = []
    for frag in fragments:
        # Simple deduplication: check if the first 100 chars of the fragment are already in the changelog
        header_check = frag['content'].split('\n')[0]
        if header_check in current_changelog:
            print(f"[SKIP] Fragment {frag['filename']} already merged.")
            continue
        new_entries.append(frag)

    if not new_entries:
        print("No new unique entries to merge.")
        return

    print(f"Found {len(new_entries)} fragments to merge:")
    for frag in new_entries:
        print(f"  - {frag['timestamp'].strftime('%Y-%m-%d %H:%M')}: {frag['filename']}")

    if args.commit:
        if not os.path.exists(ARCHIVE_DIR):
            os.makedirs(ARCHIVE_DIR)

        with open(CHANGELOG_PATH, 'a', encoding='utf-8') as f:
            for frag in new_entries:
                f.write("\n\n---\n\n")
                f.write(frag['content'])
                f.write("\n")
                
                # Archive
                dest_path = os.path.join(ARCHIVE_DIR, frag['filename'])
                if os.path.exists(dest_path):
                    # Handle name collision in archive
                    timestamp_str = datetime.now().strftime("%Y%m%d%H%M%S")
                    dest_path = os.path.join(ARCHIVE_DIR, f"{timestamp_str}_{frag['filename']}")
                
                shutil.move(frag['path'], dest_path)
                print(f"[DONE] Merged and archived {frag['filename']}")
        
        # Update when_updated in CHANGELOG.md header
        with open(CHANGELOG_PATH, 'r', encoding='utf-8') as f:
            lines = f.readlines()
        
        new_utc = datetime.utcnow().strftime("%Y%m%d%H%M%S")
        for i, line in enumerate(lines):
            if line.startswith("  when_updated:"):
                lines[i] = f"  when_updated: \"{new_utc}\"\n"
                break
        
        with open(CHANGELOG_PATH, 'w', encoding='utf-8') as f:
            f.writelines(lines)
        print(f"[DONE] Updated when_updated to {new_utc}")

    else:
        print("\nDRY RUN: Use --commit to apply changes.")

if __name__ == "__main__":
    main()
