#!/usr/bin/env python3
#   lupopedia.headers:
#   header_format_version: "4.1.3"
#   file_path_from_root: "scripts/scan_filename_violations.py"
#   web_path: "https://www.lupopedia.com/lupopedia/scripts/scan_filename_violations.py"
#   status: "active"
#   when_updated: "20260420080000"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "memory/development/canonical/1026/04/scan-filename-violations.toon"
#   atoms_toon: "memory/atoms/lupopedia_global_constants.atom.toon"
#   transcript_jsonl: "0/development/scan-filename-violations"
#   artifact_type: implementation
#   artifact_kind: tool
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: null
#   content_id: null
#   content_parent_id: null
#   content_slug: "scan-filename-violations"
#   default_collection_id: null
#   lupopedia.schema: implementation
#   title: "Filename Violations Scanner"
#   summary: "Python script to scan entire repository for filename and folder naming convention violations."
# ---------------------------------------------------------------------

"""
Filename and Folder Naming Violation Scanner for Lupopedia

Scans the entire repository for filenames and folder names that violate the naming doctrine:
- Must be lowercase ASCII
- Must use underscores
- Must not contain uppercase, hyphens, spaces, or Unicode
"""

import os
import re
from pathlib import Path
from typing import List, Dict, Tuple

def is_valid_name(name: str) -> Tuple[bool, List[str]]:
    """Check if a filename or folder name follows the naming doctrine."""
    violations = []
    
    # Skip certain system files and hidden files
    if name.startswith('.') and name in ['.git', '.gitignore', '.gitattributes', '.venv', '.idea', '.cursor', '.claude', '.copilot_origin_changelog.md', '.cascade', '.kiro', '.lexa', '.lilith', '.qodo', '.windsurf', '.augment', '.augmentignore']:
        return True, []
    
    # Skip root-level files that are exceptions
    if name in ['README.md', 'README.txt', 'README_WTF.md', 'INSTALL.txt', 'license.txt', 'version.txt', 'CURRENT_UTC', 'CURRENT_UTC.txt', '_out.txt', 'CHANGELOG.md', 'CHANGELOG_ARCHIVE.md', 'CLAUDE.md', 'CURSOR.md', 'FOR_CLAUDE_CODE_2026_04_06.md', 'for_claude.md', 'for_gemini.md', 'GEMINI.md', 'ONBOARDING.md', 'ORGANIZATION.md', 'QUICKSTART.md', 'TODO.md', 'app', 'channels', 'content', 'debug_collections_dropmenus.php', 'debug_collections_try2.htm', 'debug_dropmenu_content.php', 'debug_hy093.log', 'debug_loggedin.php', 'debug_login.php', 'forgot-password.php', 'image.php', 'index.php', 'install.php', 'install_compare_backup', 'install_wizard_classes.php', 'live.php', 'livehelp.php', 'livehelp_js.php', 'login.php', 'logout.php', 'logs', 'lupo.py', 'ajax.php', 'channels_before_4_0_93', 'data-graph.php', 'live.php', 'memory', 'research', 'session.php', 'sessions', 'tmp', 'ui', 'lupo_actors', 'lupo_config', 'lupo_config_sample.php', 'memories', 'my-channel.php', 'node_modules', 'overview.md', 'package.json', 'phantom_paths.txt', 'plan.md', 'rename_docs.py', 'rename_folders.py', 'renamed_files_log.txt', 'renamed_folders_log.txt', 'report.md', 'scratch', 'select-actor.php', 'select_agent.php', 'session_debug_diff.txt', 'start_over.php', 'tasks.md', 'template.htm', 'test_auth_workflow.php', 'verify_migrations.php', 'web-help.php', 'wolfie_channels_screen.png']:
        return True, []
    
    # Check for non-ASCII characters
    if not name.isascii():
        violations.append("non_ascii")
    
    # Check for uppercase letters (except in specific cases)
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
    # Skip certain files that should remain as-is
    if name.startswith('.') and name in ['.git', '.gitignore', '.gitattributes', '.venv', '.idea', '.cursor', '.claude', '.copilot_origin_changelog.md', '.cascade', '.kiro', '.lexa', '.lilith', '.qodo', '.windsurf', '.augment', '.augmentignore']:
        return name
    
    if name in ['README.md', 'README.txt', 'README_WTF.md', 'INSTALL.txt', 'license.txt', 'version.txt', 'CURRENT_UTC', 'CURRENT_UTC.txt', '_out.txt', 'CHANGELOG.md', 'CHANGELOG_ARCHIVE.md', 'CLAUDE.md', 'CURSOR.md', 'FOR_CLAUDE_CODE_2026_04_06.md', 'for_claude.md', 'for_gemini.md', 'GEMINI.md', 'ONBOARDING.md', 'ORGANIZATION.md', 'QUICKSTART.md', 'TODO.md', 'app', 'channels', 'content', 'debug_collections_dropmenus.php', 'debug_collections_try2.htm', 'debug_dropmenu_content.php', 'debug_hy093.log', 'debug_loggedin.php', 'debug_login.php', 'forgot-password.php', 'image.php', 'index.php', 'install.php', 'install_compare_backup', 'install_wizard_classes.php', 'live.php', 'livehelp.php', 'livehelp_js.php', 'login.php', 'logout.php', 'logs', 'lupo.py', 'ajax.php', 'channels_before_4_0_93', 'data-graph.php', 'live.php', 'memory', 'research', 'session.php', 'sessions', 'tmp', 'ui', 'lupo_actors', 'lupo_config', 'lupo_config_sample.php', 'memories', 'my-channel.php', 'node_modules', 'overview.md', 'package.json', 'phantom_paths.txt', 'plan.md', 'rename_docs.py', 'rename_folders.py', 'renamed_files_log.txt', 'renamed_folders_log.txt', 'report.md', 'scratch', 'select-actor.php', 'select_agent.php', 'session_debug_diff.txt', 'start_over.php', 'tasks.md', 'template.htm', 'test_auth_workflow.php', 'verify_migrations.php', 'web-help.php', 'wolfie_channels_screen.png']:
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

def scan_directory(root_path: Path) -> List[Dict]:
    """Scan directory recursively for naming violations."""
    violations = []
    
    for item in root_path.rglob('*'):
        # Skip the .git directory
        if '.git' in item.parts:
            continue
        
        # Get relative path from root
        try:
            rel_path = item.relative_to(root_path)
        except ValueError:
            # If we can't get relative path, skip
            continue
            
        # Check each part of the relative path (but not the root)
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
    
    print("Scanning repository for filename violations...")
    print(f"Root path: {root_path}")
    print()
    
    violations = scan_directory(root_path)
    
    if not violations:
        print("✅ No filename violations found!")
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
    for vtype, items in by_type.items():
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
    
    # Save detailed report
    report_path = root_path / 'filename_violations_report.txt'
    with open(report_path, 'w', encoding='utf-8') as f:
        f.write("Filename Violations Report\n")
        f.write("=" * 50 + "\n\n")
        f.write(f"Total violations: {len(violations)}\n\n")
        
        for vtype, items in by_type.items():
            f.write(f"{vtype.upper()} violations ({len(items)} items):\n")
            for item in items:
                f.write(f"  {item['name']} → {item['proposed_name']}\n")
                f.write(f"    Path: {item['path']}\n")
            f.write("\n")
    
    print(f"Detailed report saved to: {report_path}")

if __name__ == "__main__":
    main()
