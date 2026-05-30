#!/usr/bin/env python3
#   lupopedia.headers:
#   header_format_version: "4.1.3"
#   file_path_from_root: "lupo-scripts/scan_lupopedia_violations.py"
#   web_path: "https://www.lupopedia.com/lupopedia/lupo-scripts/scan_lupopedia_violations.py"
#   status: "active"
#   when_updated: "20260420080000"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "lupo-memory/development/canonical/1026/04/scan-lupopedia-violations.toon"
#   atoms_toon: "lupo-memory/atoms/lupopedia_global_constants.atom.toon"
#   transcript_jsonl: "0/development/scan-lupopedia-violations"
#   artifact_type: implementation
#   artifact_kind: tool
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: null
#   content_id: null
#   content_parent_id: null
#   content_slug: "scan-lupopedia-violations"
#   default_collection_id: null
#   lupopedia.schema: implementation
#   title: "Lupopedia Violations Scanner"
#   summary: "Python script to scan Lupopedia directories for naming convention violations."
# ---------------------------------------------------------------------

"""
Focused Filename Violation Scanner for Lupopedia Content

Scans only Lupopedia-specific directories for naming violations:
- lupo-*
- lupo-docs
- lupo-memory
- lupo-scripts
- lupo-tests
- lupo-templates
"""

import os
import re
from pathlib import Path
from typing import List, Dict, Tuple

def is_valid_name(name: str) -> Tuple[bool, List[str]]:
    """Check if a filename or folder name follows the naming doctrine."""
    violations = []
    
    # Skip hidden files and specific exceptions
    if name.startswith('.'):
        return True, []
    
    # Skip root-level files that are exceptions
    if name in ['README.md', 'README.txt', 'README_WTF.md', 'INSTALL.txt', 'license.txt', 'version.txt', 'CURRENT_UTC', 'CURRENT_UTC.txt', '_out.txt', 'CHANGELOG.md', 'CHANGELOG_ARCHIVE.md', 'CLAUDE.md', 'CURSOR.md', 'FOR_CLAUDE_CODE_2026_04_06.md', 'for_claude.md', 'for_gemini.md', 'GEMINI.md', 'ONBOARDING.md', 'ORGANIZATION.md', 'QUICKSTART.md', 'TODO.md']:
        return True, []
    
    # Skip top-level lupo-* directories (these are the main Lupopedia directories)
    if name.startswith('lupo-'):
        return True, []
    
    # Check for non-ASCII characters
    if not name.isascii():
        violations.append("non_ascii")
    
    # Check for uppercase letters
    if re.search(r'[A-Z]', name):
        violations.append("uppercase")
    
    # Check for hyphens
    if '-' in name:
        violations.append("hyphens")
    
    # Check for spaces
    if ' ' in name:
        violations.append("spaces")
    
    # Check for multiple dots (except .md files)
    dot_count = name.count('.')
    if dot_count > 1:
        violations.append("multiple_dots")
    
    # Check for invalid characters (only allow letters, numbers, underscores, and single dot)
    if not re.match(r'^[a-z0-9_]+(\.[a-z0-9_]+)?$', name):
        violations.append("invalid_chars")
    
    return len(violations) == 0, violations

def propose_corrected_name(name: str) -> str:
    """Propose a corrected name following the naming doctrine."""
    # Skip hidden files
    if name.startswith('.'):
        return name
    
    # Skip root-level exceptions
    if name in ['README.md', 'README.txt', 'README_WTF.md', 'INSTALL.txt', 'license.txt', 'version.txt', 'CURRENT_UTC', 'CURRENT_UTC.txt', '_out.txt', 'CHANGELOG.md', 'CHANGELOG_ARCHIVE.md', 'CLAUDE.md', 'CURSOR.md', 'FOR_CLAUDE_CODE_2026_04_06.md', 'for_claude.md', 'for_gemini.md', 'GEMINI.md', 'ONBOARDING.md', 'ORGANIZATION.md', 'QUICKSTART.md', 'TODO.md']:
        return name
    
    # Skip top-level lupo-* directories
    if name.startswith('lupo-'):
        return name
    
    # Convert to lowercase
    corrected = name.lower()
    
    # Replace hyphens with underscores
    corrected = corrected.replace('-', '_')
    
    # Replace spaces with underscores
    corrected = corrected.replace(' ', '_')
    
    # Remove multiple consecutive underscores
    corrected = re.sub(r'_+', '_', corrected)
    
    # Remove leading/trailing underscores
    corrected = corrected.strip('_')
    
    return corrected

def scan_lupopedia_directories(root_path: Path) -> List[Dict]:
    """Scan only Lupopedia-specific directories for naming violations."""
    violations = []
    
    # Define directories to scan
    scan_dirs = [
        'lupo-actors',
        'lupo-admin',
        'lupo-agents',
        'lupo-api',
        'lupo-archive',
        'lupo-backups',
        'lupo-bin',
        'lupo-cache',
        'lupo-channels',
        'lupo-config',
        'lupo-database',
        'lupo-docs',
        'lupo-includes',
        'lupo-memory',
        'lupo-research',
        'lupo-scripts',
        'lupo-sessions',
        'lupo-templates',
        'lupo-tests',
        'lupo-tmp',
        'lupo-ui',
        'lupo-user',
    ]
    
    for scan_dir in scan_dirs:
        dir_path = root_path / scan_dir
        if not dir_path.exists():
            continue
            
        print(f"Scanning {scan_dir}...")
        
        for item in dir_path.rglob('*'):
            # Skip .git and other hidden directories
            if '.git' in item.parts or any(p.startswith('.') for p in item.parts):
                continue
            
            # Get relative path from root
            try:
                rel_path = item.relative_to(root_path)
            except ValueError:
                continue
                
            # Check each part of the relative path
            for part in rel_path.parts:
                is_valid, violation_types = is_valid_name(part)
                if not is_valid:
                    violations.append({
                        'path': str(rel_path),
                        'name': part,
                        'violations': violation_types,
                        'proposed_name': propose_corrected_name(part)
                    })
                    break  # Only record once per file/folder
    
    return violations

def main():
    """Main function to scan and report violations."""
    root_path = Path(__file__).parent.parent
    
    print("Scanning Lupopedia directories for filename violations...")
    print(f"Root path: {root_path}")
    print()
    
    violations = scan_lupopedia_directories(root_path)
    
    if not violations:
        print("✅ No filename violations found in Lupopedia directories!")
        return
    
    print(f"🔍 Found {len(violations)} filename violations:")
    print()
    
    # Group by violation type
    by_type = {}
    for violation in violations:
        for vtype in violation['violations']:
            if vtype not in by_type:
                by_type[vtype] = []
            by_type[vtype].append(violation)
    
    # Report by type
    for vtype, items in sorted(by_type.items()):
        print(f"## {vtype.upper()} violations ({len(items)} items)")
        for item in items[:10]:  # Show first 10 of each type
            print(f"  {item['name']} → {item['proposed_name']}")
            print(f"    Path: {item['path']}")
        if len(items) > 10:
            print(f"  ... and {len(items) - 10} more")
        print()
    
    # Generate migration plan
    print("## Migration Plan")
    print("Total files/folders to rename:", len(violations))
    print()
    
    # Create TOON format report
    toon_content = """---
lupopedia.headers:
  header_format_version: "4.1.3"
  file_path_from_root: "lupo-memory/doctrine/canonical/filename-doctrine-enforcement-report.toon"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-memory/doctrine/canonical/filename-doctrine-enforcement-report.toon"
  status: "active"
  when_updated: "20260420054500"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: "0/doctrine/filename-doctrine-enforcement"
  artifact_type: report
  artifact_kind: enforcement
  channel_key: "doctrine"
  federation_node_id: 0
  thread_id: "filename-doctrine-enforcement"
  content_id: null
  content_parent_id: null
  content_slug: "filename-doctrine-enforcement-report"
  default_collection_id: null
  lupopedia.schema: report
  title: "Filename Doctrine Enforcement Report"
  summary: "Comprehensive scan of Lupopedia repository for filename violations and migration plan."
---
{
  "scan_timestamp": "2026-04-20T05:45:00Z",
  "total_violations": """ + str(len(violations)) + """,
  "violation_types": {
"""
    
    for vtype, items in sorted(by_type.items()):
        toon_content += f'    "{vtype}": {len(items)},\n'
    
    toon_content += """  },
  "violations": [
"""
    
    for violation in violations[:100]:  # Limit to first 100 for TOON
        toon_content += """    {
      "original_name": """" + violation['name'] + """",
      "proposed_name": """" + violation['proposed_name'] + """",
      "path": """" + violation['path'] + """",
      "violations": [""" + ', '.join(f'"{v}"' for v in violation['violations']) + """]
    },\n"""
    
    toon_content = toon_content.rstrip(',\n') + """
  ]
}
"""
    
    # Save TOON report
    toon_dir = root_path / 'lupo-memory' / 'doctrine' / 'canonical'
    toon_dir.mkdir(parents=True, exist_ok=True)
    toon_path = toon_dir / 'filename-doctrine-enforcement-report.toon'
    
    with open(toon_path, 'w', encoding='utf-8') as f:
        f.write(toon_content)
    
    print(f"TOON report saved to: {toon_path}")
    
    # Save detailed text report
    report_path = root_path / 'lupo_filename_violations_report.txt'
    with open(report_path, 'w', encoding='utf-8') as f:
        f.write("Lupopedia Filename Violations Report\n")
        f.write("=" * 50 + "\n\n")
        f.write(f"Total violations: {len(violations)}\n\n")
        
        for vtype, items in sorted(by_type.items()):
            f.write(f"{vtype.upper()} violations ({len(items)} items):\n")
            for item in items:
                f.write(f"  {item['name']} → {item['proposed_name']}\n")
                f.write(f"    Path: {item['path']}\n")
            f.write("\n")
    
    print(f"Detailed report saved to: {report_path}")

if __name__ == "__main__":
    main()
