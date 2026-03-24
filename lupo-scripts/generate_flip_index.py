#!/usr/bin/env python3
# lupopedia.headers:
#   when_updated: "20260324175617"
#   file_path_from_root: "lupo-scripts/generate_flip_index.py"
#   last_modified_utc: "20260324175617"
#   channel_id: 42
#   actor_id: 102
#   actor_name: "cursor"
#   delegation_chain: "cursor:root"
#   artifact_type: "tooling"
#   artifact_kind: "script"
# lupopedia.footer:
#   last_verified: "20260324175617"
#   last_verified_by: "cursor"
#   last_verified_by_actor_id: 102

"""
FLIP Header/Footer Index Generator

Scans repository for wolfie.headers and flip.footer blocks,
creates queryable index files for metadata lookup.

Usage: python scripts/generate_flip_index.py

Output:
- docs/index/flip_index.json (main index)
- docs/index/by_actor/*.json (per-actor indices)
- docs/index/by_channel/*.json (per-channel indices)
- docs/index/orphans.json (missing headers/footers)
- docs/status/header_lookup_build_report_20260223.md (build report)
"""

import os
import re
import json
import yaml
from pathlib import Path
from collections import defaultdict
from datetime import datetime

# Directories to scan
SCAN_DIRS = ['docs/', 'prompts/', 'channels/', '.']
EXCLUDE_DIRS = ['.git', '.idea', '.vscode', '.kiro', 'node_modules', 'vendor']
EXCLUDE_FILES = ['.gitignore', '.gitattributes']

# Output directories
INDEX_DIR = 'docs/index'
BY_ACTOR_DIR = f'{INDEX_DIR}/by_actor'
BY_CHANNEL_DIR = f'{INDEX_DIR}/by_channel'
BY_FORWARD_DIR = f'{INDEX_DIR}/by_forward'
STATUS_DIR = 'docs/status'

class FLIPIndexer:
    def __init__(self):
        self.entries = []
        self.orphans = []
        self.stats = {
            'files_scanned': 0,
            'headers_found': 0,
            'footers_found': 0,
            'orphans_found': 0,
            'errors': []
        }
    
    def extract_yaml_block(self, content, block_name):
        """Extract YAML block from markdown content"""
        # Pattern: ---\nblock_name:\n  field: value\n...\n---
        pattern = rf'^---\s*\n{re.escape(block_name)}:\s*\n(.*?)\n(?:---|\.\.\.|flip\.footer:)'
        match = re.search(pattern, content, re.MULTILINE | re.DOTALL)
        
        if match:
            yaml_content = match.group(1)
            try:
                # Parse YAML with proper indentation
                parsed = yaml.safe_load(yaml_content)
                return parsed if parsed else {}
            except yaml.YAMLError as e:
                return {'_parse_error': str(e)}
        return None
    
    def extract_flip_footer(self, content):
        """Extract flip.footer block from markdown content"""
        # Pattern: flip.footer:\n  field: value\n...\n---
        pattern = r'flip\.footer:\s*\n(.*?)\n(?:---|\.\.\.|$)'
        match = re.search(pattern, content, re.MULTILINE | re.DOTALL)
        
        if match:
            yaml_content = match.group(1)
            try:
                parsed = yaml.safe_load(yaml_content)
                return parsed if parsed else {}
            except yaml.YAMLError as e:
                return {'_parse_error': str(e)}
        return None
    
    def normalize_date(self, date_value):
        """Normalize date to YYYYMMDD format"""
        if not date_value:
            return None
        
        date_str = str(date_value)
        # Remove hyphens, slashes, spaces
        date_str = re.sub(r'[-/\s]', '', date_str)
        
        # Extract first 8 digits
        match = re.match(r'(\d{8})', date_str)
        if match:
            return match.group(1)
        
        return None
    
    def scan_file(self, filepath):
        """Scan a single file for FLIP headers/footers"""
        try:
            with open(filepath, 'r', encoding='utf-8') as f:
                content = f.read()
            
            self.stats['files_scanned'] += 1
            
            # Extract wolfie.headers
            header = self.extract_yaml_block(content, 'wolfie.headers')
            
            # Extract flip.footer
            footer = self.extract_flip_footer(content)
            
            # Build index entry
            entry = {
                'file_path_from_root': str(filepath).replace('\\', '/'),
                'header_present': header is not None,
                'footer_present': footer is not None,
            }
            
            # Extract header fields
            if header:
                self.stats['headers_found'] += 1
                entry['actor_id'] = header.get('actor_id')
                entry['lupo_agent'] = header.get('lupo_agent')
                entry['x_lupo_forwarded'] = header.get('x_lupo_forwarded')
                entry['channel_id'] = header.get('channel_id')
                entry['system_version'] = header.get('system_version')
                entry['purpose'] = header.get('purpose')
                entry['mood_rgb'] = header.get('mood_rgb')
                
                # Normalize date
                last_modified = header.get('last_modified') or header.get('last_modified_utc')
                entry['last_modified'] = self.normalize_date(last_modified)
                
                # Check for parse errors
                if '_parse_error' in header:
                    entry['header_parse_error'] = header['_parse_error']
            
            # Extract footer fields
            if footer:
                self.stats['footers_found'] += 1
                entry['referenced_by_files'] = footer.get('referenced_by_files', [])
                entry['referenced_by_channels'] = footer.get('referenced_by_channels', [])
                entry['referenced_by_actors'] = footer.get('referenced_by_actors', [])
                entry['inbound_edges'] = footer.get('inbound_edges', [])
                entry['footnotes'] = footer.get('footnotes', [])
                entry['version'] = footer.get('version')
                entry['last_verified'] = self.normalize_date(footer.get('last_verified') or footer.get('last_verified_utc'))
                entry['last_verified_by'] = footer.get('last_verified_by')
                
                # Check for parse errors
                if '_parse_error' in footer:
                    entry['footer_parse_error'] = footer['_parse_error']
            
            # Track orphans (files with only header or only footer)
            if header and not footer:
                self.orphans.append({
                    'file_path': entry['file_path_from_root'],
                    'issue': 'missing_footer',
                    'actor_id': entry.get('actor_id'),
                    'last_modified': entry.get('last_modified')
                })
                self.stats['orphans_found'] += 1
            elif footer and not header:
                self.orphans.append({
                    'file_path': entry['file_path_from_root'],
                    'issue': 'missing_header'
                })
                self.stats['orphans_found'] += 1
            
            # Only add entries that have at least a header or footer
            if header or footer:
                self.entries.append(entry)
        
        except Exception as e:
            self.stats['errors'].append({
                'file': str(filepath),
                'error': str(e)
            })
    
    def scan_directory(self, directory):
        """Recursively scan directory for markdown files"""
        path = Path(directory)
        
        if not path.exists():
            return
        
        for item in path.rglob('*.md'):
            # Skip excluded directories
            if any(excluded in item.parts for excluded in EXCLUDE_DIRS):
                continue
            
            # Skip excluded files
            if item.name in EXCLUDE_FILES:
                continue
            
            self.scan_file(item)
    
    def build_indices(self):
        """Build all index files"""
        # Create output directories
        os.makedirs(INDEX_DIR, exist_ok=True)
        os.makedirs(BY_ACTOR_DIR, exist_ok=True)
        os.makedirs(BY_CHANNEL_DIR, exist_ok=True)
        os.makedirs(BY_FORWARD_DIR, exist_ok=True)
        os.makedirs(STATUS_DIR, exist_ok=True)
        
        # Main index
        main_index = {
            'generated_at': datetime.utcnow().strftime('%Y%m%d'),
            'total_entries': len(self.entries),
            'stats': self.stats,
            'entries': self.entries
        }
        
        with open(f'{INDEX_DIR}/flip_index.json', 'w', encoding='utf-8') as f:
            json.dump(main_index, f, indent=2, ensure_ascii=False)
        
        # By actor index
        by_actor = defaultdict(list)
        for entry in self.entries:
            actor_id = entry.get('actor_id')
            if actor_id:
                by_actor[str(actor_id)].append(entry)
        
        for actor_id, entries in by_actor.items():
            with open(f'{BY_ACTOR_DIR}/{actor_id}.json', 'w', encoding='utf-8') as f:
                json.dump({
                    'actor_id': actor_id,
                    'total_entries': len(entries),
                    'entries': entries
                }, f, indent=2, ensure_ascii=False)
        
        # By channel index
        by_channel = defaultdict(list)
        for entry in self.entries:
            channel_id = entry.get('channel_id')
            if channel_id:
                by_channel[str(channel_id)].append(entry)
        
        for channel_id, entries in by_channel.items():
            with open(f'{BY_CHANNEL_DIR}/{channel_id}.json', 'w', encoding='utf-8') as f:
                json.dump({
                    'channel_id': channel_id,
                    'total_entries': len(entries),
                    'entries': entries
                }, f, indent=2, ensure_ascii=False)
        
        # By x_lupo_forwarded index
        by_forward = defaultdict(list)
        for entry in self.entries:
            forward = entry.get('x_lupo_forwarded')
            if forward:
                # Normalize format: "1001:10000" -> "1001_10000"
                forward_key = str(forward).replace(':', '_')
                by_forward[forward_key].append(entry)
        
        for forward_key, entries in by_forward.items():
            with open(f'{BY_FORWARD_DIR}/{forward_key}.json', 'w', encoding='utf-8') as f:
                json.dump({
                    'x_lupo_forwarded': forward_key.replace('_', ':'),
                    'total_entries': len(entries),
                    'entries': entries
                }, f, indent=2, ensure_ascii=False)
        
        # Orphans index
        with open(f'{INDEX_DIR}/orphans.json', 'w', encoding='utf-8') as f:
            json.dump({
                'generated_at': datetime.utcnow().strftime('%Y%m%d'),
                'total_orphans': len(self.orphans),
                'orphans': self.orphans
            }, f, indent=2, ensure_ascii=False)
        
        return {
            'main_index': f'{INDEX_DIR}/flip_index.json',
            'by_actor_count': len(by_actor),
            'by_channel_count': len(by_channel),
            'by_forward_count': len(by_forward),
            'orphans_count': len(self.orphans)
        }
    
    def generate_report(self, index_info):
        """Generate build report"""
        report = f"""---
wolfie.headers:
  file_path_from_root: "docs/status/header_lookup_build_report_20260223.md"
  system_version: "4.0.34"
  channel_id: 42
  mood_rgb: "00AAFF"
  purpose: "FLIP header/footer index build report"
  last_modified: "20260223"
  x_lupo_forwarded: "1001:10000"
  actor_id: 1001
  lupo_agent: "kiro"

flip.footer:
  referenced_by_files:
    - "CHANGELOG.md"
    - "docs/index/flip_index.json"
  referenced_by_channels:
    - 42
  referenced_by_actors:
    - 1001
    - 10000
  inbound_edges:
    - "header_lookup"
    - "index_build"
  footnotes:
    - "Generated by scripts/generate_flip_index.py"
    - "Index is regeneratable from repo state"
  version: "4.0.34"
  last_verified: "20260223"
  last_verified_by: "kiro"
---

# FLIP HEADER/FOOTER INDEX BUILD REPORT

**Generated:** {datetime.utcnow().strftime('%Y-%m-%d %H:%M:%S')} UTC  
**Script:** scripts/generate_flip_index.py  
**Agent:** KIRO IDE (actor_id 1001)  

---

## BUILD STATISTICS

**Files Scanned:** {self.stats['files_scanned']}  
**Headers Found:** {self.stats['headers_found']}  
**Footers Found:** {self.stats['footers_found']}  
**Orphans Found:** {self.stats['orphans_found']}  
**Errors:** {len(self.stats['errors'])}  

**Total Index Entries:** {len(self.entries)}  

---

## INDEX FILES CREATED

### Main Index
- `{index_info['main_index']}` - Complete index of all entries

### By Actor Indices
- `docs/index/by_actor/*.json` - {index_info['by_actor_count']} actor-specific indices

### By Channel Indices
- `docs/index/by_channel/*.json` - {index_info['by_channel_count']} channel-specific indices

### By X_LUPO_FORWARDED Indices
- `docs/index/by_forward/*.json` - {index_info['by_forward_count']} forwarding-specific indices

### Orphans Index
- `docs/index/orphans.json` - {index_info['orphans_count']} files with missing headers/footers

---

## ACCEPTANCE TEST RESULTS

### Test 1: Show all files with x_lupo_forwarded = "1001:10000"

**Query:** `docs/index/by_forward/1001_10000.json`

**Result:**
"""
        
        # Test 1: x_lupo_forwarded = "1001:10000"
        test1_results = [e for e in self.entries if e.get('x_lupo_forwarded') == '1001:10000']
        report += f"- Found {len(test1_results)} files\n"
        for entry in test1_results[:5]:  # Show first 5
            report += f"  - {entry['file_path_from_root']}\n"
        if len(test1_results) > 5:
            report += f"  - ... and {len(test1_results) - 5} more\n"
        
        report += "\n### Test 2: Show all artifacts that mention actor_id 1003\n\n"
        report += "**Query:** `docs/index/by_actor/1003.json`\n\n**Result:**\n"
        
        # Test 2: actor_id = 1003
        test2_results = [e for e in self.entries if e.get('actor_id') == 1003]
        report += f"- Found {len(test2_results)} files\n"
        for entry in test2_results[:5]:
            report += f"  - {entry['file_path_from_root']}\n"
        if len(test2_results) > 5:
            report += f"  - ... and {len(test2_results) - 5} more\n"
        
        report += "\n### Test 3: Show all artifacts missing flip.footer\n\n"
        report += "**Query:** `docs/index/orphans.json` (filter by issue='missing_footer')\n\n**Result:**\n"
        
        # Test 3: Missing footers
        test3_results = [o for o in self.orphans if o.get('issue') == 'missing_footer']
        report += f"- Found {len(test3_results)} files\n"
        for orphan in test3_results[:5]:
            report += f"  - {orphan['file_path']}\n"
        if len(test3_results) > 5:
            report += f"  - ... and {len(test3_results) - 5} more\n"
        
        report += "\n### Test 4: Show latest last_modified per actor_id\n\n"
        report += "**Query:** Aggregate from `docs/index/by_actor/*.json`\n\n**Result:**\n"
        
        # Test 4: Latest activity per actor
        latest_by_actor = {}
        for entry in self.entries:
            actor_id = entry.get('actor_id')
            last_modified = entry.get('last_modified')
            if actor_id and last_modified:
                if actor_id not in latest_by_actor or last_modified > latest_by_actor[actor_id]:
                    latest_by_actor[actor_id] = last_modified
        
        for actor_id, last_modified in sorted(latest_by_actor.items(), key=lambda x: x[1], reverse=True)[:10]:
            report += f"- Actor {actor_id}: {last_modified}\n"
        
        report += "\n### Test 5: Show all inbound_edges containing 'header_lookup'\n\n"
        report += "**Query:** Filter main index by inbound_edges\n\n**Result:**\n"
        
        # Test 5: inbound_edges containing 'header_lookup'
        test5_results = [e for e in self.entries if 'header_lookup' in e.get('inbound_edges', [])]
        report += f"- Found {len(test5_results)} files\n"
        for entry in test5_results[:5]:
            report += f"  - {entry['file_path_from_root']}\n"
        if len(test5_results) > 5:
            report += f"  - ... and {len(test5_results) - 5} more\n"
        
        report += "\n---\n\n## ERRORS\n\n"
        
        if self.stats['errors']:
            for error in self.stats['errors']:
                report += f"- **{error['file']}:** {error['error']}\n"
        else:
            report += "No errors encountered.\n"
        
        report += "\n---\n\n## USAGE EXAMPLES\n\n"
        report += """### Query by Actor ID

```bash
# View all files by actor 1001
cat docs/index/by_actor/1001.json | jq '.entries[] | .file_path_from_root'
```

### Query by Channel

```bash
# View all files in channel 42
cat docs/index/by_channel/42.json | jq '.entries[] | .file_path_from_root'
```

### Query by X_LUPO_FORWARDED

```bash
# View all files with x_lupo_forwarded = "1001:10000"
cat docs/index/by_forward/1001_10000.json | jq '.entries[] | .file_path_from_root'
```

### Find Orphans

```bash
# View all files missing footers
cat docs/index/orphans.json | jq '.orphans[] | select(.issue == "missing_footer") | .file_path'
```

### Latest Activity by Actor

```bash
# Find latest activity for each actor
cat docs/index/flip_index.json | jq '.entries | group_by(.actor_id) | map({actor_id: .[0].actor_id, latest: (map(.last_modified) | max)})'
```

---

## REGENERATION

To regenerate the index:

```bash
python scripts/generate_flip_index.py
```

The index is deterministic and derived strictly from repository state. No manual edits required.

---

**BUILD COMPLETE**

KIRO IDE (actor_id 1001)  
UTC Date: 20260223  

**END OF REPORT**
"""
        
        with open(f'{STATUS_DIR}/header_lookup_build_report_20260223.md', 'w', encoding='utf-8') as f:
            f.write(report)
        
        return f'{STATUS_DIR}/header_lookup_build_report_20260223.md'

def main():
    """Main execution"""
    print("FLIP Header/Footer Index Generator")
    print("=" * 50)
    print()
    
    indexer = FLIPIndexer()
    
    # Scan directories
    print("Scanning directories...")
    for directory in SCAN_DIRS:
        if os.path.exists(directory):
            print(f"  - {directory}")
            indexer.scan_directory(directory)
    
    print()
    print(f"Files scanned: {indexer.stats['files_scanned']}")
    print(f"Headers found: {indexer.stats['headers_found']}")
    print(f"Footers found: {indexer.stats['footers_found']}")
    print(f"Orphans found: {indexer.stats['orphans_found']}")
    print()
    
    # Build indices
    print("Building indices...")
    index_info = indexer.build_indices()
    print(f"  - Main index: {index_info['main_index']}")
    print(f"  - By actor: {index_info['by_actor_count']} files")
    print(f"  - By channel: {index_info['by_channel_count']} files")
    print(f"  - By forward: {index_info['by_forward_count']} files")
    print(f"  - Orphans: {index_info['orphans_count']} files")
    print()
    
    # Generate report
    print("Generating report...")
    report_path = indexer.generate_report(index_info)
    print(f"  - Report: {report_path}")
    print()
    
    print("=" * 50)
    print("INDEX BUILD COMPLETE")
    print()
    print("View the report:")
    print(f"  cat {report_path}")
    print()
    print("Query examples:")
    print("  cat docs/index/by_actor/1001.json | jq '.entries[] | .file_path_from_root'")
    print("  cat docs/index/by_channel/42.json | jq '.entries[] | .file_path_from_root'")
    print("  cat docs/index/orphans.json | jq '.orphans[] | .file_path'")

if __name__ == '__main__':
    main()