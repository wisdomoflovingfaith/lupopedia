#!/usr/bin/env python3
"""
Generate LUPOPEDIA_MASTER_INDEX.md from all PRDs, rules, doctrines, and implementations.
"""

import os
from pathlib import Path
from datetime import datetime
import yaml

def parse_frontmatter(file_path):
    """Extract YAML frontmatter from markdown file."""
    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()
    
    if not content.startswith('---'):
        return None
    
    parts = content.split('---', 2)
    if len(parts) < 3:
        return None
    
    try:
        return yaml.safe_load(parts[1])
    except yaml.YAMLError:
        return None

def scan_directory(base_dir, file_pattern='*.md'):
    """Scan directory for markdown files with frontmatter."""
    results = []
    base_path = Path(base_dir)
    
    if not base_path.exists():
        return results
    
    for file_path in base_path.rglob(file_pattern):
        # ignore _template files or old backup directories if needed
        if "archive" in str(file_path).lower() or "_templates" in str(file_path).lower():
            continue
            
        frontmatter = parse_frontmatter(file_path)
        if frontmatter:
            results.append({
                'path': str(file_path),
                'frontmatter': frontmatter
            })
    
    return results

def generate_index():
    """Generate master index markdown."""
    sections = {
        'PRDs': 'docs/prd',
        'Constitutional Rules': 'rules/root',
        'Doctrines': 'docs/doctrine',
        'Implementations': 'docs/implementations'
    }
    
    index_content = []
    index_content.append('# LUPOPEDIA MASTER INDEX\n')
    index_content.append(f'*Generated: {datetime.now().strftime("%Y-%m-%d %H:%M:%S")}*\n')
    index_content.append('\nThis index provides a single entry point for all canonical Lupopedia documentation.\n')
    
    for section_name, section_path in sections.items():
        index_content.append(f'\n## {section_name}\n')
        index_content.append('| File | Purpose | Status |\n')
        index_content.append('|------|---------|--------|\n')
        
        files = scan_directory(section_path)
        
        for file_info in sorted(files, key=lambda x: x['path']):
            front = file_info['frontmatter']
            headers = front.get('lupopedia.headers', front)
            
            purpose = headers.get('purpose', 'No purpose defined').replace('\n', ' ')
            title = purpose[:100] + '...' if len(purpose) > 100 else purpose
            status = 'ACTIVE'  # Could parse from frontmatter if available
            
            file_name = file_info['path'].replace('\\', '/')
            # make links relative to root
            if 'lupopedia/' in file_name:
                relative_path = file_name.split('lupopedia/')[-1]
            else:
                relative_path = file_name
                
            index_content.append(f'| [`{relative_path}`]({relative_path}) | {title} | {status} |\n')
    
    return ''.join(index_content)

if __name__ == '__main__':
    index = generate_index()
    
    output_path = Path('LUPOPEDIA_MASTER_INDEX.md')
    with open(output_path, 'w', encoding='utf-8') as f:
        f.write(index)
    
    print(f"Generated: {output_path}")
