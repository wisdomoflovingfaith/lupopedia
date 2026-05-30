#!/usr/bin/env python3
"""
Validate Purpose 1 / Purpose 2 pseudocode under .../decisions/pseudocode/
per docs/prd/17_decisions_format.md (classification mirrors
validate_pseudocode_discipline.py). Does not modify files.

Exit 0: no violations.
Exit 1: one or more violations.
"""

from __future__ import unicode_literals

import os
import re
import sys
from pathlib import Path

# Repository root: parent of scripts/
REPO_ROOT = Path(__file__).resolve().parent.parent

SKIP_DIR_NAMES = {
    ".git",
    "node_modules",
    "vendor",
    ".svn",
    ".hg",
}

P1_SUFFIX = "_constitution.pseudo.md"
P2_DESIGN_SUFFIX = "_design.pseudo.md"
THREAD_INDEX_NAME = "THREAD_INDEX.md"
# PRD 17: Purpose 1 naming — *_constitution.pseudo.md, shipped 00_* digests, lupopedia_quickstart.pseudo.md
P1_QUICKSTART_NAME = "lupopedia_quickstart.pseudo.md"

# Purpose 1: DDL / PHP entry and fences (PRD 17 — no production DDL/PHP in Purpose 1)
P1_FORBIDDEN = (
    "CREATE TABLE",
    "ALTER TABLE",
    "<?php",
)

# PHP-shaped class/function declarations (line-start), not English prose containing "class "
P1_PHP_CLASS_LINE = re.compile(r"(?m)^\s*class\s+[A-Za-z_]")
P1_PHP_FUNCTION_LINE = re.compile(r"(?m)^\s*function\s+[A-Za-z_]")

P2_DDL = (
    "CREATE TABLE",
    "ALTER TABLE",
)

# Executable PHP example from spec + obvious PDO calls
P2_EXEC_PATTERNS = (
    re.compile(r"\$pdo\s*->\s*query\s*\("),
    re.compile(r"\$[a-zA-Z_][a-zA-Z0-9_]*\s*->\s*(query|exec|prepare)\s*\("),
)

PHP_FENCE_START = re.compile(r"^```\s*php\s*$", re.IGNORECASE | re.MULTILINE)

MAX_P1_BYTES = 10 * 1024


def _norm(p):
    return str(p).replace("\\", "/")


def should_skip_dir(name):
    return name in SKIP_DIR_NAMES or name.startswith(".")


def iter_pseudocode_dirs(root):
    """Yield each .../decisions/pseudocode directory path."""
    seen = set()
    for dirpath, dirnames, _ in os.walk(root, topdown=True):
        dirnames[:] = [d for d in dirnames if not should_skip_dir(d)]
        p = Path(dirpath)
        if p.name == "pseudocode":
            parent = p.parent
            if parent.name == "decisions":
                rp = p.resolve()
                if rp not in seen:
                    seen.add(rp)
                    yield rp


def is_pseudo_filename(filename):
    return (
        filename.endswith(".pseudo.md")
        or filename.endswith(".pseudo.php")
        or filename.endswith(".pseudo.txt")
    )


def classify_purpose(filename, rel_norm):
    """
    Return 'p1', 'p2', or 'unknown'.
    Aligns with docs/prd/17_decisions_format.md and validate_pseudocode_discipline.py:
    Purpose 1 — *_constitution.pseudo.md, 00_*.pseudo.md digests, lupopedia_quickstart.pseudo.md,
    and all .pseudo.md / .pseudo.txt under docs/decisions/pseudocode/ (cross-cutting routers).
    Purpose 2 — *_design.pseudo.md, *.pseudo.php, other exploratory *.pseudo.md / *.pseudo.txt.
    Discipline script treats *.pseudo.php under decisions/pseudocode as Purpose 2 (checked first).
    """
    fn = filename
    rel = rel_norm.replace("\\", "/").lower()

    # Match validate_pseudocode_discipline.py: PHP pseudocode is always Purpose 2
    if fn.endswith(".pseudo.php"):
        return "p2"

    if fn.endswith(P1_SUFFIX):
        return "p1"
    if fn == P1_QUICKSTART_NAME:
        return "p1"
    if fn.startswith("00_") and (fn.endswith(".pseudo.md") or fn.endswith(".pseudo.txt")):
        return "p1"
    # Entire docs/decisions/pseudocode/ tree = Purpose 1 digest/router (not .pseudo.php)
    if "docs/decisions/pseudocode/" in rel and (
        fn.endswith(".pseudo.md") or fn.endswith(".pseudo.txt")
    ):
        return "p1"

    if fn.endswith(P2_DESIGN_SUFFIX):
        return "p2"
    if fn.endswith(".pseudo.txt"):
        return "p2"
    if fn.endswith(".pseudo.md"):
        return "p2"
    return "unknown"


def read_text_ok(path):
    try:
        with open(path, "rb") as f:
            return f.read().decode("utf-8", errors="replace"), None
    except OSError as e:
        return None, str(e)


def has_lupopedia_headers(text):
    return "lupopedia.headers:" in text


def index_lists_basename(index_text, basename):
    if not index_text:
        return False
    return basename in index_text


def violations_p1(path, text):
    errs = []
    for s in P1_FORBIDDEN:
        if s in text:
            errs.append("forbidden substring: %r" % (s,))
    if P1_PHP_CLASS_LINE.search(text):
        errs.append("PHP-like class declaration (line with class Name)")
    if P1_PHP_FUNCTION_LINE.search(text):
        errs.append("PHP-like function declaration (line with function name)")
    if PHP_FENCE_START.search(text):
        errs.append("PHP code fence (```php) present")
    try:
        sz = path.stat().st_size
    except OSError:
        sz = len(text.encode("utf-8", errors="replace"))
    if sz >= MAX_P1_BYTES:
        errs.append("file size %s bytes (Purpose 1 requires < 10 KB)" % sz)
    return errs


def violations_p2(_path, text):
    errs = []
    for s in P2_DDL:
        if s in text:
            errs.append("DDL substring: %r" % (s,))
    for rx in P2_EXEC_PATTERNS:
        if rx.search(text):
            errs.append("executable PHP pattern: %s" % rx.pattern)
            break
    return errs


def main():
    root = REPO_ROOT
    errors = []
    stats = {
        "total": 0,
        "p1_ok": 0,
        "p1_bad": 0,
        "p2_ok": 0,
        "p2_bad": 0,
        "missing_headers": 0,
        "naming": 0,
        "ddl": 0,
        "thread_index_mismatch": 0,
        "plain_php": 0,
    }

    pseudocode_roots = list(iter_pseudocode_dirs(root))
    index_by_dir = {}

    for d in pseudocode_roots:
        idx_path = d / THREAD_INDEX_NAME
        if not idx_path.is_file():
            errors.append("[%s] missing %s" % (_norm(d.relative_to(root)), THREAD_INDEX_NAME))
        else:
            t, err = read_text_ok(idx_path)
            if err:
                errors.append("[%s] unreadable: %s" % (idx_path, err))
                index_by_dir[d] = ""
            else:
                index_by_dir[d] = t

    # Plain .php (not .pseudo.php) under decisions/pseudocode
    for d in pseudocode_roots:
        for dirpath, _, filenames in os.walk(d):
            for fn in filenames:
                if not fn.endswith(".php"):
                    continue
                if fn.endswith(".pseudo.php"):
                    continue
                stats["plain_php"] += 1
                fp = Path(dirpath) / fn
                errors.append("[%s] plain .php file forbidden under pseudocode/" % _norm(fp.relative_to(root)))

    # Content files
    for d in pseudocode_roots:
        idx_text = index_by_dir.get(d, "")
        for dirpath, _, filenames in os.walk(d):
            for fn in filenames:
                if fn == THREAD_INDEX_NAME:
                    continue
                fp = Path(dirpath) / fn
                if not fp.is_file():
                    continue
                if not is_pseudo_filename(fn):
                    continue
                stats["total"] += 1
                rel = _norm(fp.relative_to(root))
                purpose = classify_purpose(fn, rel)
                text, err = read_text_ok(fp)
                if err:
                    errors.append("[%s] %s" % (_norm(fp.relative_to(root)), err))
                    if purpose == "p1":
                        stats["p1_bad"] += 1
                    elif purpose == "p2":
                        stats["p2_bad"] += 1
                    else:
                        stats["naming"] += 1
                    continue

                if not has_lupopedia_headers(text):
                    stats["missing_headers"] += 1
                    errors.append("[%s] missing lupopedia.headers block" % rel)

                if not index_lists_basename(idx_text, fn):
                    stats["thread_index_mismatch"] += 1
                    errors.append("[%s] not referenced in %s" % (rel, _norm((d / THREAD_INDEX_NAME).relative_to(root))))

                if purpose == "unknown":
                    stats["naming"] += 1
                    errors.append(
                        "[%s] not a recognized pseudocode name (*.pseudo.md, *.pseudo.php, *.pseudo.txt)"
                        % rel
                    )
                    continue

                if purpose == "p1":
                    v = violations_p1(fp, text)
                    for x in v:
                        if "CREATE" in x or "ALTER" in x or "DDL" in x:
                            stats["ddl"] += 1
                        errors.append("[%s] Purpose 1: %s" % (rel, x))
                    if v:
                        stats["p1_bad"] += 1
                    else:
                        stats["p1_ok"] += 1
                else:
                    v = violations_p2(fp, text)
                    for x in v:
                        if "DDL" in x or "CREATE" in x or "alter" in x.lower():
                            stats["ddl"] += 1
                        errors.append("[%s] Purpose 2: %s" % (rel, x))
                    if v:
                        stats["p2_bad"] += 1
                    else:
                        stats["p2_ok"] += 1

    # Summary
    print("--- validate_pseudocode_purposes.py ---")
    print("Total pseudocode files (excluding %s): %s" % (THREAD_INDEX_NAME, stats["total"]))
    print("Purpose 1 valid: %s  invalid: %s" % (stats["p1_ok"], stats["p1_bad"]))
    print("Purpose 2 valid: %s  invalid: %s" % (stats["p2_ok"], stats["p2_bad"]))
    print("Missing lupopedia.headers: %s" % stats["missing_headers"])
    print("Naming violations: %s" % stats["naming"])
    print("DDL-related flags: %s" % stats["ddl"])
    print("THREAD_INDEX mismatches: %s" % stats["thread_index_mismatch"])
    print("Plain .php files: %s" % stats["plain_php"])
    print("---")

    if errors:
        print("Violations (%s):" % len(errors))
        for e in errors:
            print(e)
        sys.exit(1)
    sys.exit(0)


if __name__ == "__main__":
    main()
