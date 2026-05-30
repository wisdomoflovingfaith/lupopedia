#!/usr/bin/env python3
"""
Regenerate headers for stale files (last_verified < 20260328140000)
This is an OPTIONAL tool. Use it on specific files or directories as needed.
"""

import os
import sys
import re
from datetime import datetime, timezone
from pathlib import Path

CUTOFF = 20260328140000  # 2026-03-28 14:00:00 UTC

def get_last_verified(content):
    """Extract last_verified from header"""
    match = re.search(r'last_verified:\s*"?(\d+)"?', content)
    if match:
        return int(match.group(1))
    return 0

def is_stale(content):
    """Check if file is stale based on last_verified"""
    last_verified = get_last_verified(content)
    return last_verified < CUTOFF

def get_current_timestamp():
    """Get current UTC timestamp in YYYYMMDDHHIISS format"""
    now = datetime.now(timezone.utc)
    return now.strftime("%Y%m%d%H%M%S")

def append_history_event(file_path, event_data):
    """Append a history event to an existing file"""
    # Read existing content
    with open(file_path, 'r', encoding='utf-8-sig') as f:
        content = f.read()
    
    # Find existing history block
    history_match = re.search(r'(lupopedia\.history:\s*\n)(.*?)(?=\n\w+:|\n---|\Z)', content, re.DOTALL)
    
    if history_match:
        # Append to existing history
        prefix = history_match.group(1)
        history_content = history_match.group(2)
        
        # Parse existing events to get next event_id
        import yaml
        try:
            history_data = yaml.safe_load(prefix + history_content)
            events = history_data.get('history', [])
            next_event_id = max([e.get('event_id', 0) for e in events]) + 1 if events else 1
        except:
            next_event_id = 1
        
        # Format new event
        new_event = f"  - event_id: {next_event_id}\n"
        for key, value in event_data.items():
            if isinstance(value, str):
                new_event += f"    {key}: \"{value}\"\n"
            elif isinstance(value, list):
                new_event += f"    {key}:\n"
                for item in value:
                    new_event += f"      - \"{item}\"\n"
            else:
                new_event += f"    {key}: {value}\n"
        
        # Rebuild content with new event
        new_history = prefix + history_content.rstrip() + "\n" + new_event
        content = content.replace(prefix + history_content, new_history)
    else:
        # Create new history block before edges
        edges_match = re.search(r'\n(lupopedia\.edges:)', content)
        if edges_match:
            # Insert before edges
            insertion_point = edges_match.start()
            new_history = "\nlupopedia.history:\n  - event_id: 1\n"
            for key, value in event_data.items():
                if isinstance(value, str):
                    new_history += f"    {key}: \"{value}\"\n"
                elif isinstance(value, list):
                    new_history += f"    {key}:\n"
                    for item in value:
                        new_history += f"      - \"{item}\"\n"
                else:
                    new_history += f"    {key}: {value}\n"
            
            content = content[:insertion_point] + new_history + "\n" + content[insertion_point:]
        else:
            # Append at end before final ---
            footer_match = re.search(r'\n(lupopedia\.footer:)', content)
            if footer_match:
                insertion_point = footer_match.start()
                new_history = "\nlupopedia.history:\n  - event_id: 1\n"
                for key, value in event_data.items():
                    if isinstance(value, str):
                        new_history += f"    {key}: \"{value}\"\n"
                    elif isinstance(value, list):
                        new_history += f"    {key}:\n"
                        for item in value:
                            new_history += f"      - \"{item}\"\n"
                    else:
                        new_history += f"    {key}: {value}\n"
                
                content = content[:insertion_point] + new_history + "\n" + content[insertion_point:]
    
    # Write back
    with open(file_path, 'w', encoding='utf-8') as f:
        f.write(content)
    
    print(f"✅ History event appended to: {file_path}")

def regenerate_headers(file_path):
    """Regenerate headers for a single file"""
    print(f"🔄 Regenerating headers for: {file_path}")
    
    # Use absolute path
    file_path = os.path.abspath(file_path)
    
    with open(file_path, 'r', encoding='utf-8-sig') as f:
        content = f.read()
    
    # Extract existing header information
    lines = content.split('\n')
    header_end = -1
    for i, line in enumerate(lines):
        if line.strip() == '---' and i > 0:
            header_end = i
            break
    
    if header_end == -1:
        print(f"❌ {file_path}: No header found")
        return False
    
    # Parse existing header fields
    headers = {}
    in_header = False
    for line in lines[:header_end]:
        if line.strip() == '---':
            in_header = not in_header
            continue
        if in_header and ':' in line:
            key, value = line.split(':', 1)
            headers[key.strip()] = value.strip()

    # Update required fields
    current_ts = get_current_timestamp()

    # --- Context ID logic ---
    context_id = headers.get('context_id')
    def is_finalized_artifact(headers):
        # Heuristic: treat decisions.md, context, or PRD as finalized
        fpr = headers.get('file_path_from_root', '').lower()
        return (
            'decisions.md' in fpr or
            headers.get('artifact_kind', '').lower() in ('decisions', 'context', 'specification', 'prd')
        )

    def generate_context_id():
        import random
        from lib.db_connection import get_connection
        for _ in range(5):
            base = current_ts
            rand = f"{random.randint(0, 9999):04d}"
            cid = f"{base}{rand}"
            # Check DB for uniqueness
            try:
                conn = get_connection()
                with conn.cursor() as cur:
                    cur.execute("SELECT COUNT(*) FROM lupo_contexts WHERE context_id=%s", (cid,))
                    row = cur.fetchone()
                conn.close()
                if row and (row[0] == 0 or row.get('COUNT(*)', 0) == 0):
                    return cid
            except Exception as e:
                print(f"[WARN] Could not check context_id uniqueness: {e}")
                return cid  # fallback: just use it
        raise Exception("Failed to generate unique context_id after 5 attempts")

    # If context_id missing and finalized, generate and insert
    if not context_id and is_finalized_artifact(headers):
        context_id = generate_context_id()
        # Insert into lupo_contexts if not present
        try:
            from lib.db_connection import get_connection
            conn = get_connection()
            with conn.cursor() as cur:
                cur.execute("INSERT IGNORE INTO lupo_contexts (context_id, context_type, content_raw, metadata_json, created_ymdhis, updated_ymdhis) VALUES (%s, %s, %s, %s, %s, %s)",
                    (context_id, headers.get('artifact_kind', 'decisions'), '', '{}', current_ts, current_ts))
            conn.commit()
            conn.close()
            print(f"[INFO] Inserted new context_id {context_id} into lupo_contexts")
        except Exception as e:
            print(f"[WARN] Could not insert context_id into lupo_contexts: {e}")

    # Update header fields
    # NOTE: last_modified_utc renamed to questions_toon in PRD 16 v4.0.99 §4.2 field 6.
    # Migrate on the fly: if old field present, replace with questions_toon: null.
    new_headers = headers.copy()
    if 'last_modified_utc' in new_headers:
        del new_headers['last_modified_utc']
    new_headers['questions_toon'] = 'null'  # was last_modified_utc
    new_headers['when_updated'] = f'"{current_ts}"'
    new_headers['last_verified'] = f'"{current_ts}"'
    if context_id:
        new_headers['context_id'] = context_id

    # Add verified_via if not present
    if 'verified_via' not in headers:
        new_headers['verified_via'] = 'type: "faucet"\n  faucet_slug: "cascade"'  # Default to Cascade

    # Rebuild header section
    new_lines = []
    in_header = False
    for line in lines:
        if line.strip() == '---':
            in_header = not in_header
            new_lines.append(line)
            continue

        if in_header:
            # Update modified fields (migrate last_modified_utc to questions_toon on encounter)
            updated = False
            for key in ['last_modified_utc', 'questions_toon', 'when_updated', 'last_verified', 'context_id']:
                if key in line:
                    new_lines.append(f"{key}: {new_headers[key]}")
                    updated = True
                    break
            if not updated:
                new_lines.append(line)
        else:
            new_lines.append(line)

    # If context_id was missing, add it before the first non-header line
    if context_id and 'context_id' not in headers:
        # Insert after last header field
        for i, line in enumerate(new_lines):
            if line.strip() == '---':
                # Insert context_id after this line
                new_lines.insert(i+1, f"context_id: {context_id}")
                break

    # Write updated content
    new_content = '\n'.join(new_lines)

    with open(file_path, 'w', encoding='utf-8-sig') as f:
        f.write(new_content)

    # Add history event for regeneration
    event_data = {
        'event_type': 'update',
        'event_date': current_ts,
        'actor_id': 14,  # HEPHAESTUS
        'actor_name': 'hephaestus',
        'faucet_slug': 'cascade',
        'description': 'Header regeneration for stale file',
        'reason': f'File was stale (last_verified < {CUTOFF})',
        'affected_files': [file_path]
    }
    append_history_event(file_path, event_data)

    print(f"✅ {file_path}: headers regenerated")
    return True

def process_file(file_path):
    """Process a single file"""
    if not os.path.exists(file_path):
        print(f"❌ File not found: {file_path}")
        return
    
    with open(file_path, 'r', encoding='utf-8-sig') as f:
        content = f.read()
    
    if not is_stale(content):
        print(f"✅ {file_path}: already fresh")
        return
    
    # Backup original
    backup = file_path + '.bak'
    if not os.path.exists(backup):
        os.rename(file_path, backup)
        print(f"📋 Backed up to: {backup}")
    
    try:
        if regenerate_headers(file_path):
            # Remove backup on success
            if os.path.exists(backup):
                os.remove(backup)
                print(f"🗑️ Removed backup: {backup}")
        else:
            print(f"⚠️  Backup remains: {backup}")
    except Exception as e:
        # Restore backup on failure
        if os.path.exists(backup):
            os.rename(backup, file_path)
            print(f"🔄 Restored from backup: {backup}")
        print(f"❌ {file_path}: failed to regenerate: {e}")

def main():
    import argparse
    parser = argparse.ArgumentParser(description='Regenerate headers for stale files')
    parser.add_argument('paths', nargs='+', help='Files or directories to process')
    parser.add_argument('--dry-run', action='store_true', help='Show what would be done')
    parser.add_argument('--verbose', '-v', action='store_true', help='Verbose output')
    args = parser.parse_args()
    
    processed = 0
    stale = 0
    
    for path in args.paths:
        if os.path.isfile(path):
            if args.dry_run:
                with open(path, 'r', encoding='utf-8-sig') as f:
                    content = f.read()
                if is_stale(content):
                    print(f"Would regenerate: {path}")
                    stale += 1
                else:
                    if args.verbose:
                        print(f"Would skip (fresh): {path}")
                processed += 1
            else:
                process_file(path)
                processed += 1
                with open(path, 'r', encoding='utf-8-sig') as f:
                    if is_stale(f.read()):
                        stale += 1
        elif os.path.isdir(path):
            for root, dirs, files in os.walk(path):
                for file in files:
                    if file.endswith('.md') or file.endswith('.php'):
                        full_path = os.path.join(root, file)
                        if args.dry_run:
                            with open(full_path, 'r', encoding='utf-8-sig') as f:
                                content = f.read()
                            if is_stale(content):
                                print(f"Would regenerate: {full_path}")
                                stale += 1
                            else:
                                if args.verbose:
                                    print(f"Would skip (fresh): {full_path}")
                            processed += 1
                        else:
                            process_file(full_path)
                            processed += 1
                            with open(full_path, 'r', encoding='utf-8-sig') as f:
                                if is_stale(f.read()):
                                    stale += 1
    
    print(f"\n📊 Summary:")
    print(f"  Processed: {processed} files")
    print(f"  Stale: {stale} files")
    if args.dry_run:
        print(f"  Would regenerate: {stale} files")

if __name__ == '__main__':
    main()
