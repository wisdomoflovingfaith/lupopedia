#!/usr/bin/env python3
"""
Lupopedia Doctrine Enforcement Script
Scans all .sql and .json files in database/ and includes/ for forbidden constructs.
Fails with error if any violation is found.
"""
import os
import sys
import re

FORBIDDEN = [
    r'FOREIGN KEY',
    r'REFERENCES',
    r'AUTO_INCREMENT',
    r'SERIAL',
    r'TIMESTAMP',
    r'DATETIME',
    r'TIMEZONE',
    r'UNSIGNED',
]

ID_PATTERN = re.compile(r'\b([a-zA-Z0-9_]+_id)\b(?!\s*bigint)', re.IGNORECASE)
DATE_PATTERN = re.compile(r'\b([a-zA-Z0-9_]+_date|[a-zA-Z0-9_]+_timestamp)\b', re.IGNORECASE)

ROOTS = [
    'database',
    'includes',
]

violations = []

def scan_file(path):
    with open(path, 'r', encoding='utf-8', errors='ignore') as f:
        text = f.read()
        for keyword in FORBIDDEN:
            if re.search(keyword, text, re.IGNORECASE):
                violations.append(f"{path}: contains forbidden keyword '{keyword}'")
        # Check for ID fields not BIGINT
        for match in re.finditer(r'\b([a-zA-Z0-9_]+_id)\b', text):
            field = match.group(1)
            # Only check in .sql
            if path.endswith('.sql') and not re.search(rf'{field}\s+bigint', text, re.IGNORECASE):
                violations.append(f"{path}: field '{field}' not declared as BIGINT")
        # Check for date fields not _ymdhis
        for match in re.finditer(r'\b([a-zA-Z0-9_]+_date|[a-zA-Z0-9_]+_timestamp)\b', text):
            field = match.group(1)
            if not field.endswith('_ymdhis'):
                violations.append(f"{path}: field '{field}' does not end with _ymdhis")

def main():
    for root in ROOTS:
        for dirpath, _, files in os.walk(root):
            for file in files:
                if file.endswith('.sql') or file.endswith('.json'):
                    scan_file(os.path.join(dirpath, file))
    if violations:
        print("\nDOCTRINE VIOLATIONS DETECTED:")
        for v in violations:
            print(f" - {v}")
        sys.exit(1)
    print("No doctrine violations found.")
    sys.exit(0)

if __name__ == '__main__':
    main()
