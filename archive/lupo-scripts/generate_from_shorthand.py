#!/usr/bin/env python3
import json
import os
import glob
from pathlib import Path
from datetime import datetime

# Path definition
BASE_DIR = Path(__file__).resolve().parent.parent

def process_toon(toon_path):
    print(f"Processing shorthand .toon file: {toon_path}")
    with open(toon_path, 'r', encoding='utf-8') as f:
        data = json.load(f)

    # 1. Generate JSON headers
    header_dir = BASE_DIR / 'lupo-docs/headers'
    header_dir.mkdir(parents=True, exist_ok=True)
    
    header_file = header_dir / f"{data.get('id', 'unknown')}.json"
    header_content = {
        "file_id": f"{data.get('id', 'unknown')}.md",
        "last_updated": data.get("ts", datetime.now().strftime("%Y%m%d%H%M%S")),
        "memory_ref": str(toon_path),
        "tags": ["draft", "shorthand"],
        "edges": {"outbound": []}
    }
    
    with open(header_file, 'w', encoding='utf-8') as f:
        json.dump(header_content, f, indent=2)
    print(f"Generated JSON header: {header_file}")

    # 2. Generate Memory files
    memory_dir = BASE_DIR / f"lupo-memory/{datetime.now().strftime('%Y/%m')}"
    memory_dir.mkdir(parents=True, exist_ok=True)
    
    # We already have a .toon file, but maybe generate a specific memory file based on the shorthand
    memory_file = memory_dir / f"M-{data.get('id', 'unknown')}-{datetime.now().strftime('%Y%m%d')}.toon"
    memory_content = {
        "id": f"M-{data.get('id', 'unknown')}-{datetime.now().strftime('%Y%m%d')}",
        "type": "shorthand_memory",
        "ts": data.get("ts"),
        "actor_id": 116,
        "summary": data.get("content", {}).get("summary", "Generated memory"),
        "edges": [{"to": f"FILE:{data.get('id', 'unknown')}.md", "type": "modifies", "weight": 1.0}],
        "content": data.get("content", {})
    }
    
    with open(memory_file, 'w', encoding='utf-8') as f:
        json.dump(memory_content, f, indent=2)
    print(f"Generated memory file: {memory_file}")


if __name__ == '__main__':
    # Read .toon files instead of .pseudo
    search_path = BASE_DIR / "lupo-memory/**/*.toon"
    for file in glob.glob(str(search_path), recursive=True):
        if "shorthand" in file:
            process_toon(Path(file))
