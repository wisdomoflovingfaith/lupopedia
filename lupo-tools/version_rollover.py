#!/usr/bin/env python3
"""
Version Rollover Script - v4.0.50 to v4.0.51
Updates all system atoms and configuration files to new version
"""

import os
import sys
import json
import re
import hashlib
from datetime import datetime, timezone

# Configuration
OLD_VERSION = "4.0.50"
NEW_VERSION = "4.0.51"
UTC_DATE = datetime.now(timezone.utc).strftime("%Y%m%d%H%M%S")
OLD_VERSION_PATTERN = r"4\.0\.50"
NEW_VERSION_PATTERN = r"4\.0\.51"

# Files to update
FILES_TO_UPDATE = [
    "config/global_atoms.yaml",
    "bin/lupo.php",
    "CHANGELOG.md",
    "docs/doctrine/FLARE/FLARE_DOCTRINE.md",
    "channels/42/tasks/active/meta/flare.json"
]

def update_file(file_path, old_pattern, new_pattern, replacement):
    """Update version in a file"""
    if not os.path.exists(file_path):
        print(f"WARNING: {file_path} not found")
        return False
    
    try:
        with open(file_path, 'r', encoding='utf-8') as f:
            content = f.read()
        
        # Replace version patterns
        updated_content = re.sub(old_pattern, replacement, content)
        
        if updated_content == content:
            print(f"INFO: {file_path} - no changes needed")
            return True
        
        # Write back
        with open(file_path, 'w', encoding='utf-8') as f:
            f.write(updated_content)
        
        print(f"SUCCESS: {file_path} - updated {OLD_VERSION} → {NEW_VERSION}")
        return True
        
    except Exception as e:
        print(f"ERROR: {file_path} - {e}")
        return False

def update_file_with_content_search(file_path, old_pattern, new_pattern, replacement):
    """Update version in a file by searching for specific content patterns"""
    if not os.path.exists(file_path):
        print(f"WARNING: {file_path} not found")
        return False
    
    try:
        with open(file_path, 'r', encoding='utf-8') as f:
            content = f.read()
        
        # Search for specific patterns and replace
        if old_pattern in content:
            updated_content = re.sub(old_pattern, replacement, content)
        else:
            print(f"INFO: {file_path} - pattern not found, no changes needed")
            return True
        
        if updated_content == content:
            print(f"INFO: {file_path} - no changes needed")
            return True
        
        # Write back
        with open(file_path, 'w', encoding='utf-8') as f:
            f.write(updated_content)
        
        print(f"SUCCESS: {file_path} - updated {OLD_VERSION} → {NEW_VERSION}")
        return True
        
    except Exception as e:
        print(f"ERROR: {file_path} - {e}")
        return False

def update_global_atoms():
    """Update global atoms configuration"""
    atoms_path = "config/global_atoms.yaml"
    if not os.path.exists(atoms_path):
        print(f"WARNING: {atoms_path} not found")
        return
    
    try:
        with open(atoms_path, 'r', encoding='utf-8') as f:
            content = f.read()
        
        # Update GLOBAL_CURRENT_LUPOPEDIA_VERSION
        updated_content = re.sub(
            rf"GLOBAL_CURRENT_LUPOPEDIA_VERSION:.*{OLD_VERSION}.*",
            f"GLOBAL_CURRENT_LUPOPEDIA_VERSION: {NEW_VERSION}",
            content
        )
        
        with open(atoms_path, 'w', encoding='utf-8') as f:
            f.write(updated_content)
        
        print(f"SUCCESS: {atoms_path} - updated GLOBAL_CURRENT_LUPOPEDIA_VERSION")
        
    except Exception as e:
        print(f"ERROR: {atoms_path} - {e}")

def update_lupo_php():
    """Update lupo.php version"""
    php_path = "bin/lupo.php"
    if not os.path.exists(php_path):
        print(f"WARNING: {php_path} not found")
        return
    
    try:
        with open(php_path, 'r', encoding='utf-8') as f:
            content = f.read()
        
        # Update version constant
        updated_content = re.sub(
            rf"define\('LUPOPEDIA_VERSION',\s*'{OLD_VERSION}'\);",
            f"define('LUPOPEDIA_VERSION', '{NEW_VERSION}');",
            content
        )
        
        with open(php_path, 'w', encoding='utf-8') as f:
            f.write(updated_content)
        
        print(f"SUCCESS: {php_path} - updated LUPOPEDIA_VERSION")
        
    except Exception as e:
        print(f"ERROR: {php_path} - {e}")

def update_changelog():
    """Update CHANGELOG.md version section"""
    changelog_path = "CHANGELOG.md"
    if not os.path.exists(changelog_path):
        print(f"WARNING: {changelog_path} not found")
        return
    
    try:
        with open(changelog_path, 'r', encoding='utf-8') as f:
            content = f.read()
        
        # Update version section
        updated_content = re.sub(
            rf"## \[4\.0\.50\].*?(\n.*?)(?=## \[|\Z)",
            f"## [4.0.51] — IN DEVELOPMENT (2026-02-28)\\1\\n\\nStatus: ACTIVE DEVELOPMENT\\nTheme: Version rollover and task migration from v4.0.50\\nLead Agent: Windsurf (1002)\\nUTC Date: 20260228\\nPhase: Active Development\\n\\n### Mission Objectives\\n**Primary Objective**: Continue development work from v4.0.50 rollover tasks. Migrate incomplete items to v4.0.51, ensure v4.0.51 is dedicated solely to ANUBIS faucet execution for FLARE header addition, and prepare for v4.0.52 initiation.\\n",
            content
        )
        
        with open(changelog_path, 'w', encoding='utf-8') as f:
            f.write(updated_content)
        
        print(f"SUCCESS: {changelog_path} - added v4.0.51 section")
        
    except Exception as e:
        print(f"ERROR: {changelog_path} - {e}")

def main():
    """Main execution"""
    print(f"Version Rollover: {OLD_VERSION} → {NEW_VERSION}")
    print(f"UTC Date: {UTC_DATE}")
    print()
    
    success_count = 0
    total_count = len(FILES_TO_UPDATE)
    
    # Update global atoms
    if update_global_atoms():
        success_count += 1
    
    # Update lupo.php
    if update_lupo_php():
        success_count += 1
    
    # Update CHANGELOG.md
    changelog_path = "CHANGELOG.md"
    if update_file_with_content_search(changelog_path, 
        r"## \[4\.0\.50\].*?(\n.*?)(?=## \[|\Z)",
        f"## [4.0.51] — IN DEVELOPMENT (2026-02-28)\\n\\nStatus: ACTIVE DEVELOPMENT\\nTheme: Version rollover and task migration from v4.0.50\\nLead Agent: Windsurf (1002)\\nUTC Date: 20260228\\nPhase: Active Development\\n\\n### Mission Objectives\\n**Primary Objective**: Continue development work from v4.0.50 rollover tasks. Migrate incomplete items to v4.0.51, ensure v4.0.51 is dedicated solely to ANUBIS faucet execution for FLARE header addition, and prepare for v4.0.52 initiation.\\n\\n### Pending Tasks\\n- File count optimization preparation for v4.1.0\\n- Performance impact assessment of FLARE implementation\\n\\n---\\n\\n## [4.0.49] — Continued stabilization and rollover from v4.0.48. (2026-02-28)\\n\\n**Status**: ✅ RELEASED\\n",
        NEW_VERSION
    ):
        success_count += 1
    
    # Update other files with version patterns
    for file_path in FILES_TO_UPDATE[3:]:
        if update_file(file_path, OLD_VERSION_PATTERN, NEW_VERSION, NEW_VERSION):
            success_count += 1
    
    print(f"\n=== VERSION ROLLOVER SUMMARY ===")
    print(f"Files processed: {total_count}")
    print(f"Successfully updated: {success_count}")
    print(f"Failed: {total_count - success_count}")
    
    if success_count == total_count:
        print("✅ Version rollover completed successfully")
        return 0
    else:
        print("❌ Version rollover completed with errors")
        return 1

if __name__ == "__main__":
    sys.exit(main())
