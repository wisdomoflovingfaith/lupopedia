#!/usr/bin/env python3
"""
FLARE Header Application Script - v4.0.51
Scans repository for .md files without FLARE headers and applies them
"""

import os
import sys
import re
import hashlib
from datetime import datetime, timezone

# Configuration
VERSION = "4.0.51"
UTC_DATE = datetime.now(timezone.utc).strftime("%Y%m%d%H%M%S")
FLARE_HEADER_PATTERN = r"^(?!# FLARE Header)"

def has_flare_header(file_path):
    """Check if file has FLARE header"""
    try:
        with open(file_path, 'r', encoding='utf-8') as f:
            content = f.read()
            # Check for FLARE header at the beginning
            return bool(re.search(FLARE_HEADER_PATTERN, content))
    except:
        return False

def apply_flare_header(file_path):
    """Apply FLARE header to file"""
    try:
        with open(file_path, 'r', encoding='utf-8') as f:
            original_content = f.read()
        
        # Generate file hash
        file_hash = hashlib.sha256(original_content.encode('utf-8')).hexdigest()
        
        # Build FLARE header
        header = f"""# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: "{file_path}"
  file_hash: "{file_hash}"
  last_updated_utc: "{UTC_DATE}"
  system_version: "{VERSION}"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with FLARE header applied"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v{VERSION}"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

flare.edges:
  outbound_edges:
    - {{ to: "CHANGELOG.md", type: "references", weight: 1.0 }}
    - {{ to: "docs/doctrine/", type: "references", weight: 1.0 }}

flare.footer:
  last_verified: "{UTC_DATE}"
  last_verified_by: "windsurf"
---

"""
        
        # Write file with FLARE header
        with open(file_path, 'w', encoding='utf-8') as f:
            f.write(header + original_content)
        
        print(f"✅ FLARE header applied to: {file_path}")
        return True
        
    except Exception as e:
        print(f"❌ Error processing {file_path}: {e}")
        return False

def main():
    """Main execution"""
    print(f"FLARE Header Application - v{VERSION}")
    print(f"UTC Date: {UTC_DATE}")
    print()
    
    # Count files processed
    processed_count = 0
    error_count = 0
    
    # Walk through repository
    for root, dirs, files in os.walk("."):
        if ".git" in dirs:
            continue
            
        for file in files:
            if file.endswith(".md"):
                file_path = os.path.join(root, file)
                
                # Check if already has FLARE header
                if has_flare_header(file_path):
                    print(f"⏭ SKIP: {file_path} (already has FLARE header)")
                    continue
                
                # Apply FLARE header
                if apply_flare_header(file_path):
                    processed_count += 1
                else:
                    error_count += 1
    
    print(f"\n=== FLARE HEADER APPLICATION SUMMARY ===")
    print(f"Version: {VERSION}")
    print(f"UTC Date: {UTC_DATE}")
    print(f"Files processed: {processed_count}")
    print(f"FLARE headers applied: {processed_count}")
    print(f"Errors: {error_count}")
    
    if error_count == 0:
        print("✅ All .md files now have FLARE headers")
        return 0
    else:
        print("❌ Some files failed to get FLARE headers")
        return 1

if __name__ == "__main__":
    sys.exit(main())
