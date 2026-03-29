#!/usr/bin/env python3
"""
Validate LUPOPEDIA HEADERS in review files (PHP format with docblock comments)
"""

import argparse
import re
import sys
from pathlib import Path

def validate_review_file(file_path):
    """Validate that a review file has proper LUPOPEDIA HEADERS in PHP format"""
    
    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # Check for PHP docblock start
    if not content.strip().startswith('<?php'):
        print(f"❌ {file_path}: Must start with PHP opening tag '<?php'")
        return False
    
    # Check for docblock comment
    if '/**' not in content:
        print(f"❌ {file_path}: Missing docblock comment '/**'")
        return False
    
    # Check for LUPOPEDIA HEADERS block
    headers_match = re.search(r'/\*\s*lupopedia\.headers\s*\{', content)
    if not headers_match:
        print(f"❌ {file_path}: Missing lupoopedia.headers block")
        return False
    
    # Check for required fields in headers
    required_fields = ['when_updated', 'file_path_from_root', 'last_modified_utc', 
                     'channel_id', 'actor_id', 'actor_name', 'delegation_chain',
                     'artifact_type', 'artifact_kind', 'purpose']
    
    for field in required_fields:
        pattern = rf'{field}:\s*"([^"]+)"'
        if not re.search(pattern, content):
            print(f"❌ {file_path}: Missing required field '{field}'")
            return False
    
    # Check for lupoopedia.footer block
    footer_match = re.search(r'/\*\s*lupopedia\.footer\s*\{', content)
    if not footer_match:
        print(f"❌ {file_path}: Missing lupoopedia.footer block")
        return False
    
    # Check for required footer fields
    footer_required = ['last_verified', 'verified_by.identity_type', 'verified_by.actor_id',
                    'verified_via.type', 'verified_via.faucet_slug']
    
    for field in footer_required:
        pattern = rf'{field}:\s*"([^"]+)"'
        if not re.search(pattern, content):
            print(f"❌ {file_path}: Missing required footer field '{field}'")
            return False
    
    print(f"✅ {file_path}: Valid PHP review file format")
    return True

def main():
    parser = argparse.ArgumentParser(description='Validate LUPOPEDIA HEADERS in review files')
    parser.add_argument('file', help='Review file to validate')
    args = parser.parse_args()
    
    if validate_review_file(args.file):
        sys.exit(0)
    else:
        sys.exit(1)

if __name__ == '__main__':
    main()
