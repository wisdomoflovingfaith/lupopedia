#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Scan text files for memory_key lines that put a calendar-year segment (2xxx)
immediately under .../canonical/ — usually wrong for new verified canonical exports.

Default: print findings and exit 0 (legacy repo still contains canonical/2026/...).
Strict: exit 1 if any match (for CI once the tree is migrated).

Does NOT prove a path wrong when PRD 16 documents intentional legacy on-disk layout;
use as an advisory sweep and for pre-commit on touched files.

Usage:
  python lupo-scripts/validate_trust_ladder_paths.py
  python lupo-scripts/validate_trust_ladder_paths.py --strict
  python lupo-scripts/validate_trust_ladder_paths.py --root lupo-docs/prd
"""

from __future__ import print_function

import argparse
import os
import re
import sys

# memory_toon or legacy memory_key: ... /canonical/2YYY/ ...  (calendar band under canonical)
CANONICAL_CALENDAR_YEAR = re.compile(
    r"(?:memory_toon|memory_key)\s*:\s*[^\n]*?/canonical/(2\d{3})/",
    re.IGNORECASE,
)

SKIP_DIRS = {
    ".git",
    "node_modules",
    "vendor",
    "lupo-archive",
    "__pycache__",
    ".cursor",
}

TEXT_SUFFIXES = (
    ".md",
    ".py",
    ".json",
    ".toon",
    ".yaml",
    ".yml",
    ".txt",
    ".mc",
)


def should_skip_dir(name):
    return name in SKIP_DIRS or name.startswith(".")


def scan_file(path):
    violations = []
    try:
        with open(path, "r", encoding="utf-8", errors="replace") as f:
            for lineno, line in enumerate(f, 1):
                if CANONICAL_CALENDAR_YEAR.search(line):
                    violations.append((path, lineno, line.rstrip()))
    except (IOError, OSError) as e:
        return [("<<read_error>>", 0, "%s: %s" % (path, e))]
    return violations


def walk_roots(roots):
    for root in roots:
        root = os.path.normpath(root)
        if os.path.isfile(root):
            if root.lower().endswith(TEXT_SUFFIXES):
                yield root
            continue
        for dirpath, dirnames, filenames in os.walk(root):
            dirnames[:] = [d for d in dirnames if not should_skip_dir(d)]
            for name in filenames:
                lower = name.lower()
                if not lower.endswith(TEXT_SUFFIXES):
                    continue
                yield os.path.join(dirpath, name)


def main():
    parser = argparse.ArgumentParser(
        description="Find memory_key paths using /canonical/2xxx/ (calendar year under canonical)."
    )
    parser.add_argument(
        "--root",
        action="append",
        default=[],
        help="File or directory to scan (repeatable). Default: repository root.",
    )
    parser.add_argument(
        "--strict",
        action="store_true",
        help="Exit 1 if any violation is found.",
    )
    parser.add_argument(
        "--max-print",
        type=int,
        default=50,
        help="Max violation lines to print (default 50).",
    )
    args = parser.parse_args()

    script_dir = os.path.dirname(os.path.abspath(__file__))
    repo_root = os.path.dirname(script_dir)
    roots = args.root if args.root else [repo_root]

    all_v = []
    for path in walk_roots(roots):
        all_v.extend(scan_file(path))

    if not all_v:
        print("[OK] No memory_key lines matched /canonical/2xxx/ pattern.")
        return 0

    n = 0
    for path, lineno, text in all_v:
        if n >= args.max_print:
            print("... (%d more omitted)" % (len(all_v) - n,))
            break
        if lineno:
            print("%s:%d: %s" % (path, lineno, text))
        else:
            print("%s" % (text,))
        n += 1

    print(
        "\n[INFO] Found %d matching lines. "
        "Canonical tier SHOULD use display year (calendar - 1000), e.g. 1026 for 2026. "
        "Many matches are expected until legacy paths are migrated. "
        "See lupo-docs/doctrine/CHRONOLOGICAL_TRUST_LADDER.md and "
        "lupo-docs/doctrine/TRUST_LADDER_DO_NOT_FIX.txt"
        % (len(all_v),)
    )

    if args.strict:
        print("[ERROR] Strict mode: exit 1.")
        return 1
    return 0


if __name__ == "__main__":
    sys.exit(main())
