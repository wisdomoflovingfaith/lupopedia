#!/usr/bin/env python3
"""
Check for hardcoded actor IDs in documentation and code.
Flags files that contain numeric IDs that should come from the registry.
Use for review; exclude example blocks and registry home paths.
v4.0.57+ Agent Registry Refinement.
"""

from __future__ import print_function

import os
import re
import sys

# Known actor IDs (for detection only; canonical source is registry)
KNOWN_IDS = [0, 1, 2, 3, 4, 5, 19, 25, 420, 1000, 1001, 1002, 1003, 1004, 1005, 1006, 1007, 10000, 10420]

# Paths to exclude (registry home, generated, vendor)
EXCLUDE_DIRS = ('lupo-database/lupopedia/actors/', '.git', 'node_modules', 'vendor', '__pycache__')
EXCLUDE_FILES = ('registry.json', 'check_hardcoded_ids.py')

# Skip lines that are clearly examples or disclaimers
SKIP_PATTERNS = (
    re.compile(r'example', re.I),
    re.compile(r'illustrative', re.I),
    re.compile(r'resolve from the registry', re.I),
    re.compile(r'Always resolve from', re.I),
    re.compile(r'actor_id \(example\)', re.I),
    re.compile(r'\|\s*\d+\s*\|', re.I),  # table row with number
)


def should_skip_line(line):
    for pat in SKIP_PATTERNS:
        if pat.search(line):
            return True
    return False


def check_file(filepath):
    findings = []
    try:
        with open(filepath, 'r', encoding='utf-8', errors='replace') as f:
            content = f.read()
    except IOError:
        return findings
    lines = content.split('\n')
    for actor_id in KNOWN_IDS:
        pattern = r'\b' + str(actor_id) + r'\b'
        for i, line in enumerate(lines):
            if not re.search(pattern, line):
                continue
            if should_skip_line(line):
                continue
            # Optional: only flag when actor_id key is present (reduces false positives)
            if re.search(r'actor_id\s*[=:]\s*' + str(actor_id), line):
                findings.append({
                    'file': filepath,
                    'line': i + 1,
                    'content': line.strip()[:80],
                    'id': actor_id,
                })
    return findings


def main():
    root_dir = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
    findings = []
    for root, dirs, files in os.walk(root_dir):
        dirs[:] = [d for d in dirs if d not in EXCLUDE_DIRS and not d.startswith('.')]
        rel_root = os.path.relpath(root, root_dir)
        if any(rel_root.startswith(ex) for ex in EXCLUDE_DIRS):
            continue
        for f in files:
            if f in EXCLUDE_FILES:
                continue
            if not f.endswith(('.md', '.php', '.py', '.js', '.json', '.yaml', '.yml')):
                continue
            filepath = os.path.join(root, f)
            rel_path = os.path.relpath(filepath, root_dir)
            findings.extend(check_file(rel_path))

    if findings:
        print("Potential hardcoded actor_id usages (review; examples excluded):")
        for item in findings:
            print("  %s:%s - actor_id %s in: %s" % (
                item['file'], item['line'], item['id'], item['content']))
        # Exit 0 so CI does not break; use --strict to exit 1
        if '--strict' in sys.argv:
            sys.exit(1)
    else:
        print("No hardcoded actor_id usages found (or all in example context).")
    sys.exit(0)


if __name__ == '__main__':
    main()
