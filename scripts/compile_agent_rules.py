#!/usr/bin/env python3
"""
Compile agent rules from canonical PRDs and structural doctrines into .cursorrules.
Uses injection boundaries to preserve manual configurations.
"""

import os
import re
from pathlib import Path
import yaml

TARGETS = [
    ("CONSTITUTIONAL RULES", "rules/root/LUPOPEDIA_CONSTITUTIONAL_ROOT_RULES.md"),
    ("WOLFIE DOCTRINE", "rules/root/WOLFIE_DOCTRINE.md"),
    ("CORE IDENTITY RULES", "docs/prd/01_core_identity.md"),
    ("FEDERATION INTAKE DOCTRINE", "docs/prd/20_federation_intake_doctrine.md")
]

def extract_summary(file_path):
    path = Path(file_path)
    if not path.exists():
        return f"# File not found: {file_path}"
    
    with open(path, 'r', encoding='utf-8') as f:
        content = f.read()

    # Split by frontmatter
    parts = content.split('---', 2)
    if len(parts) >= 3:
        body = parts[2].strip()
    else:
        body = content.strip()
        
    lines = body.split('\n')
    summary_lines = []
    
    # We want to extract a summary, avoiding dumping thousands of tokens.
    # Take the first ~25 lines of actual content after the frontmatter, skipping empty lines early on.
    content_lines_grabbed = 0
    for line in lines:
        if content_lines_grabbed > 25:
            summary_lines.append("# ... [See source file for full details]")
            break
            
        summary_lines.append(f"# {line}")
        if line.strip() != "":
            content_lines_grabbed += 1
            
    return '\n'.join(summary_lines)

def compile_rules():
    compiled = []
    compiled.append("# This section is auto-generated. Do not edit manually.")
    compiled.append("# Source: scripts/compile_agent_rules.py\n")
    
    for title, path in TARGETS:
        compiled.append(f"# === {title} ===")
        compiled.append(f"# Source: {path}")
        compiled.append(extract_summary(path))
        compiled.append("")
        
    return '\n'.join(compiled)

def update_cursorrules(compiled_content):
    cursorrules_path = Path('.cursorrules')
    marker_start = '# === GENERATED RULES START ==='
    marker_end = '# === GENERATED RULES END ==='
    
    if cursorrules_path.exists():
        with open(cursorrules_path, 'r', encoding='utf-8') as f:
            existing = f.read()
        
        # Check if marker exists
        if marker_start in existing and marker_end in existing:
            # Replace between markers
            pattern = f'{marker_start}.*?{marker_end}'
            new_content = re.sub(pattern, f'{marker_start}\n{compiled_content}\n{marker_end}', existing, flags=re.DOTALL)
        else:
            # Append at end with markers
            new_content = existing + f'\n\n{marker_start}\n{compiled_content}\n{marker_end}\n'
    else:
        new_content = f'{marker_start}\n{compiled_content}\n{marker_end}\n'
    
    with open(cursorrules_path, 'w', encoding='utf-8') as f:
        f.write(new_content)

if __name__ == '__main__':
    # Jump to root directory to ensure paths match
    script_dir = Path(__file__).resolve().parent
    root_dir = script_dir.parent
    os.chdir(root_dir)
    
    content = compile_rules()
    update_cursorrules(content)
    print("Agent rules successfully compiled into .cursorrules")
