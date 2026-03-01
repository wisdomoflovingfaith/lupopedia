#!/usr/bin/env python3
import os
import sys
import json
import yaml
import re
from datetime import datetime

def validate_flare_file(path):
    """Validate a single FLARE file"""
    errors = []
    warnings = []
    
    try:
        with open(path, "r", encoding="utf-8", errors="replace") as f:
            content = f.read()
        
        # Find FLARE block
        lines = content.splitlines()
        flare_start = -1
        flare_end = -1
        
        for i, line in enumerate(lines):
            if line.strip() == "---" and i < 120:
                for j in range(i+1, min(i+50, len(lines))):
                    if lines[j].strip() == "---":
                        block_content = "\n".join(lines[i:j+1])
                        if "flare.headers:" in block_content:
                            flare_start, flare_end = i, j
                            break
                if flare_start != -1:
                    break
        
        if flare_start == -1:
            errors.append("No FLARE header found")
            return errors, warnings
        
        block_lines = lines[flare_start:flare_end+1]
        block_content = "\n".join(block_lines)
        
        # Required fields
        required_fields = [
            "flare.version",
            "flare.schema", 
            "flare.edges",
            "file_path_from_root",
            "file_hash",
            "last_updated_utc",
            "system_version"
        ]
        
        for field in required_fields:
            if field not in block_content:
                errors.append(f"Missing required field: {field}")
        
        # Date format validation
        date_match = re.search(r'last_updated_utc:\s*"(\d{8})"', block_content)
        if date_match:
            date_str = date_match.group(1)
            if len(date_str) != 8:
                errors.append("Invalid date format (must be YYYYMMDD)")
        
        # Path validation
        path_match = re.search(r'file_path_from_root:\s*"([^"]+)"', block_content)
        if path_match:
            expected_path = os.path.relpath(path, ".")
            if path_match.group(1) != expected_path:
                errors.append(f"Path mismatch: {path_match.group(1)} != {expected_path}")
        
        # Warnings
        if "needs_review:" in block_content:
            warnings.append("Has needs_review fields")
        
        if any(key in block_content for key in ["wolfie.headers:", "flip.headers:", "flp.headers:", "flph.headers:", "crop.headers:"]):
            warnings.append("Has legacy blocks")
        
    except Exception as e:
        errors.append(f"Validation error: {e}")
    
    return errors, warnings

def main():
    if len(sys.argv) > 1 and sys.argv[1] == "--ci":
        ci_mode = True
    else:
        ci_mode = False
    
    # Read index
    index_file = "tools/flare_file_index.txt"
    if not os.path.exists(index_file):
        print("ERROR: tools/flare_file_index.txt not found")
        if ci_mode:
            sys.exit(1)
        return
    
    with open(index_file, "r", encoding="utf-8") as f:
        paths = [line.strip() for line in f if line.strip()]
    
    total_errors = 0
    total_warnings = 0
    
    for path in paths:
        if not path.endswith(".md"):
            continue
        
        if not os.path.exists(path):
            continue
        
        errors, warnings = validate_flare_file(path)
        
        if errors:
            print(f"ERRORS in {path}:")
            for error in errors:
                print(f"  - {error}")
            total_errors += len(errors)
        
        if warnings:
            print(f"WARNINGS in {path}:")
            for warning in warnings:
                print(f"  - {warning}")
            total_warnings += len(warnings)
    
    print(f"\nValidation complete: {total_errors} errors, {total_warnings} warnings")
    
    if ci_mode and total_errors > 0:
        sys.exit(1)

if __name__ == "__main__":
    main()
