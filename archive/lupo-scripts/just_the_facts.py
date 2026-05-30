#!/usr/bin/env python3
"""
md_to_facts_toon.py
Converts Lupopedia Markdown files into compact "just the facts" TOON format.
Usage: python lupo-scripts/just_the_facts.py path/to/file.md
"""

import sys
import json
import re
from datetime import datetime
from pathlib import Path

def extract_header(md_content):
    """Extract lupopedia.headers block."""
    match = re.search(r'---\s*(lupopedia\.headers:.*?)\s*---', md_content, re.DOTALL)
    if match:
        header_text = match.group(1)
        # Simple parsing - in production use ruamel.yaml or similar
        header = {}
        for line in header_text.split('\n'):
            if ':' in line:
                k, v = line.split(':', 1)
                header[k.strip()] = v.strip().strip('"\'')
        return header
    return {}

def extract_facts(md_content):
    """Simple heuristic to turn sections into facts."""
    entries = []
    entry_id = 1
    
    # Split by headings
    sections = re.split(r'^(#{1,3})\s+(.+)$', md_content, flags=re.MULTILINE)
    
    for i in range(1, len(sections), 3):
        level = sections[i]
        title = sections[i+1].strip()
        content = sections[i+2] if i+2 < len(sections) else ""
        
        if not content.strip():
            continue
            
        facts = []
        # Extract bullet points and tables roughly
        bullets = re.findall(r'[-*]\s+(.+?)(?=\n[-*]|\n\n|\Z)', content, re.DOTALL)
        for b in bullets:
            clean = b.strip().replace('\n', ' ').replace('  ', ' ')
            if clean and len(clean) < 300:  # reasonable length
                facts.append(clean)
        
        # Fallback: first few sentences
        if not facts:
            sentences = re.split(r'[.!?]', content)
            for s in sentences[:6]:
                s = s.strip()
                if s and len(s) > 10:
                    facts.append(s + ".")
        
        if facts:
            topic = title.lower().replace(' ', '_').replace(':', '').replace('/', '_')
            entries.append({
                "id": entry_id,
                "topic": topic,
                "facts": facts[:12]  # limit per entry
            })
            entry_id += 1
    
    return entries

def md_to_toon(md_path):
    md_path = Path(md_path)
    content = md_path.read_text(encoding='utf-8')
    
    header = extract_header(content)
    title = header.get('title', md_path.name)
    # Accept legacy memory_key during migration; prefer memory_toon (PRD 16 v4.1.0)
    memory_toon = header.get('memory_toon') or header.get('memory_key') or \
        f"lupo-memory/development/canonical/1026/04/{md_path.stem}.toon"
    last_updated = header.get('when_updated', datetime.now().strftime("%Y%m%d%H%M%S"))

    entries = extract_facts(content)

    toon = {
        "toon.version": "1.0.0",
        "toon.type": "constitutional_facts",
        "title": f"{title} — Just the Facts",
        "last_updated": last_updated,
        "memory_toon": memory_toon,
        "references": ["PRD-00", "PRD-38"],  # can be improved
        "entries": entries,
        "core_rules": [
            "Database = storage. PHP = logic.",
            "ASCII only in data files.",
            "No FKs, no triggers.",
            "Prepared statements mandatory.",
            "Write the why before the how."
        ]
    }
    
    # Write TOON
    toon_path = md_path.with_suffix('.toon')
    toon_path.write_text(json.dumps(toon, indent=2, ensure_ascii=False), encoding='utf-8')
    print(f"✓ Created {toon_path}")
    return toon_path

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print("Usage: python md_to_facts_toon.py <markdown_file.md>")
        sys.exit(1)
    
    for f in sys.argv[1:]:
        md_to_toon(f)