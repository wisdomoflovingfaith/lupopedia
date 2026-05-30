#!/usr/bin/env python3
import os
import re
import json
import yaml

def parse_md_to_json(md_path):
    with open(md_path, 'r', encoding='utf-8') as f:
        content = f.read()

    # Split frontmatter
    parts = re.split(r'^---$', content, flags=re.MULTILINE)
    if len(parts) < 3:
        print(f"[WARN] No frontmatter found in {md_path}")
        return None

    frontmatter_raw = parts[1]
    body = parts[2]

    try:
        frontmatter = yaml.safe_load(frontmatter_raw)
    except yaml.YAMLError as e:
        print(f"[ERROR] YAML error in {md_path}: {e}")
        return None

    # Extract fields from frontmatter
    headers = frontmatter.get('lupopedia.headers', {})
    timestamp = headers.get('when_updated') or frontmatter.get('when_updated')
    summary = frontmatter.get('summary') or headers.get('summary')
    channel = headers.get('channel_key') or "development"
    thread = headers.get('thread_id') or ""

    # Extract fields from body
    # WHO: Cursor (actor_id 102)
    who_match = re.search(r'\*\*WHO\*\*:\s*([^(]+)\(actor_id\s*([^)]+)\)', body)
    agent_id = "cursor"
    if who_match:
        agent_id = who_match.group(1).strip().lower()

    # WHERE: file1\nfile2
    where_match = re.search(r'\*\*WHERE\*\*:\s*(.*?)\n\s*\*\*', body, re.DOTALL)
    files_changed = []
    if where_match:
        where_text = where_match.group(1).strip()
        files_changed = [f.strip() for f in where_text.splitlines() if f.strip()]

    # Open Questions
    open_questions = []
    # If the file is open_questions.md update, it might not contain NEW questions in metadata.
    
    # Why (for internal use if needed)
    why_match = re.search(r'\*\*WHY\*\*:\s*(.*?)$', body, re.DOTALL)
    why_text = ""
    if why_match:
        why_text = why_match.group(1).strip()

    # Construct JSON
    data = {
        "timestamp": str(timestamp),
        "agent_id": agent_id,
        "channel": channel,
        "thread": thread,
        "summary": summary,
        "files_changed": files_changed,
        "open_questions": open_questions,
        "handoff_to": None,
        "related_toons": []
    }
    
    # Special case for timestamp if it's not 14-digit
    if timestamp and len(str(timestamp)) < 14:
        # Try to find it in body
        when_match = re.search(r'\*\*WHEN\*\*:\s*(\d{14})', body)
        if when_match:
            data["timestamp"] = when_match.group(1)

    return data

def main():
    pending_dir = "lupo-changelog-pending"
    for filename in os.listdir(pending_dir):
        if filename.endswith(".md") and filename != "README.md":
            md_path = os.path.join(pending_dir, filename)
            json_data = parse_md_to_json(md_path)
            if json_data:
                json_filename = filename.replace(".md", ".json")
                json_path = os.path.join(pending_dir, json_filename)
                with open(json_path, 'w', encoding='utf-8') as f:
                    json.dump(json_data, f, indent=2)
                print(f"[DONE] Converted {filename} to {json_filename}")
                os.remove(md_path)

if __name__ == "__main__":
    main()
