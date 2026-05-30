#!/usr/bin/env python3
"""
Audit loose files in includes/ root.
Identifies which files are referenced elsewhere in the codebase.
"""

import os
import re
from pathlib import Path

LUPO_INCLUDES = Path('includes')
IGNORE_DIRS = ['classes', 'modules', 'functions', 'security', 'semantic', 'templates', 'themes', 'ui']

def find_references(file_name, root_dir=Path('.')):
    """Search for references to a file in the codebase."""
    pattern = re.compile(re.escape(file_name))
    references = []
    
    for file_path in root_dir.rglob('*'):
        if file_path.is_file() and file_path.suffix in ['.php', '.js', '.py', '.sh']:
            try:
                with open(file_path, 'r', encoding='utf-8', errors='ignore') as f:
                    content = f.read()
                    if pattern.search(content):
                        # Exclude the file itself
                        if file_path != LUPO_INCLUDES / file_name:
                            references.append(str(file_path))
            except Exception:
                continue
    
    return references

def main():
    print("=== includes Loose File Audit ===\n")
    
    if not LUPO_INCLUDES.exists():
        print(f"Directory not found: {LUPO_INCLUDES}")
        return
        
    for item in LUPO_INCLUDES.iterdir():
        if item.is_file() and item.suffix == '.php':
            # Skip files in active subdirectories
            if any(item.name.startswith(prefix) for prefix in ['classes/', 'lupo_']) or True: # We want to audit ALL php files in the root
                references = find_references(item.name)
                
                status = "ACTIVE" if references else "DEAD"
                print(f"{status}: {item.name}")
                if references:
                    print(f"   Referenced in: {references[0]}")
                    if len(references) > 1:
                        print(f"   + {len(references)-1} more")
                print()

if __name__ == '__main__':
    main()
