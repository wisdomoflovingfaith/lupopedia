#!/usr/bin/env python3
"""
LUPOPEDIA HEADERS Validation Script

Validates that markdown files with LUPOPEDIA HEADERS follow the correct format:
1. Must start with --- (line 1)
2. Must have proper header structure
3. Must have required fields
4. Must have proper footer structure if present

Usage: python lupo-scripts/validate_lupopedia_headers.py [file_path]
"""

import sys
import os
import re
import yaml
from pathlib import Path

class LupopediaHeaderValidator:
    def __init__(self):
        self.errors = []
        self.warnings = []
        
    def validate_file(self, file_path):
        """Validate a single file for LUPOPEDIA HEADERS compliance"""
        try:
            with open(file_path, 'r', encoding='utf-8') as f:
                content = f.read()
                lines = content.split('\n')
            
            return self.validate_content(content, lines, file_path)
            
        except Exception as e:
            self.errors.append(f"Error reading {file_path}: {e}")
            return False
    
    def validate_content(self, content, lines, file_path):
        """Validate content for LUPOPEDIA HEADERS compliance"""
        valid = True
        
        # Check 1: Must start with ---
        if not content.startswith('---'):
            self.errors.append(f"{file_path}: File must start with --- (line 1)")
            valid = False
        
        # Check 2: Must have proper YAML header structure
        if '---' not in content[3:]:
            self.errors.append(f"{file_path}: Missing closing --- for YAML header")
            valid = False
        else:
            # Extract YAML header
            header_end = content.find('---', 3)
            header_content = content[3:header_end]
            
            try:
                header_data = yaml.safe_load(header_content)
                
                # Validate required header fields
                if 'lupopedia.headers' not in header_data:
                    self.errors.append(f"{file_path}: Missing lupopedia.headers section")
                    valid = False
                else:
                    headers = header_data['lupopedia.headers']
                    
                    # Check for deprecated fields
                    if 'version_when_written' in headers:
                        self.warnings.append(f"{file_path}: version_when_written is deprecated, use when_updated")
                    
                    if 'lupopedia.version' in headers:
                        self.warnings.append(f"{file_path}: lupopedia.version is deprecated in headers")
                    
                    # Check required fields
                    required_fields = ['when_updated', 'file_path_from_root', 'web_path']
                    for field in required_fields:
                        if field not in headers:
                            self.errors.append(f"{file_path}: Missing required field {field}")
                            valid = False
                    
                    # Check web_path format
                    if 'web_path' in headers:
                        web_path = headers['web_path']
                        if not web_path.startswith('http://www.lupopedia.com/lupopedia/'):
                            self.warnings.append(f"{file_path}: web_path should include /lupopedia/ subdirectory")
                    
                    # Check for proper footer if present
                    if 'lupopedia.footer' in header_data:
                        footer = header_data['lupopedia.footer']
                        
                        # Check for deprecated footer fields
                        if 'last_verified_by' in footer:
                            self.warnings.append(f"{file_path}: last_verified_by is deprecated, use verified_by structure")
                        
                        # Check required footer fields
                        if 'last_verified' in footer:
                            if 'verified_by' not in footer:
                                self.errors.append(f"{file_path}: footer has last_verified but missing verified_by structure")
                                valid = False
                            else:
                                verified_by = footer['verified_by']
                                required_verified_fields = ['identity_type', 'actor_id']
                                for field in required_verified_fields:
                                    if field not in verified_by:
                                        self.errors.append(f"{file_path}: missing verified_by.{field}")
                                        valid = False
                
            except yaml.YAMLError as e:
                self.errors.append(f"{file_path}: Invalid YAML in header: {e}")
                valid = False
        
        return valid
    
    def print_results(self):
        """Print validation results"""
        if self.errors:
            print("ERRORS:")
            for error in self.errors:
                print(f"  ❌ {error}")
        
        if self.warnings:
            print("WARNINGS:")
            for warning in self.warnings:
                print(f"  ⚠️  {warning}")
        
        if not self.errors and not self.warnings:
            print("✅ All validations passed")
        
        return len(self.errors) == 0

def main():
    if len(sys.argv) != 2:
        print("Usage: python validate_lupopedia_headers.py <file_path>")
        sys.exit(1)
    
    file_path = sys.argv[1]
    
    if not os.path.exists(file_path):
        print(f"❌ File not found: {file_path}")
        sys.exit(1)
    
    validator = LupopediaHeaderValidator()
    valid = validator.validate_file(file_path)
    
    if not validator.print_results():
        sys.exit(1)
    else:
        sys.exit(0)

if __name__ == "__main__":
    main()
