#!/usr/bin/env python3
"""One-off: validate every repo file that contains lupopedia.headers (PRD 16 + --strict-memory-pair)."""
from __future__ import print_function

import os
import subprocess
import sys

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
SKIP_DIRS = frozenset(
    (
        ".git",
        "node_modules",
        "vendor",
        ".venv",
        "venv",
        "__pycache__",
        "lupo-archive",
        "dist",
        "build",
    )
)
EXTS = (".md", ".py", ".php", ".js")
MARKER = "lupopedia.headers"

files = []
for dirpath, dirnames, filenames in os.walk(ROOT):
    dirnames[:] = [d for d in dirnames if d not in SKIP_DIRS and d != ".cursor"]
    for fn in filenames:
        if not fn.lower().endswith(EXTS):
            continue
        path = os.path.join(dirpath, fn)
        try:
            with open(path, "r", encoding="utf-8", errors="ignore") as fh:
                chunk = fh.read(16000)
        except OSError:
            continue
        if MARKER not in chunk:
            continue
        files.append(path)

validator = os.path.join(ROOT, "lupo-scripts", "validate_lupopedia_headers_universal.py")
failed = []
print("Found %d files containing %r" % (len(files), MARKER))
for path in sorted(files):
    rel = os.path.relpath(path, ROOT).replace("\\", "/")
    r = subprocess.call([sys.executable, validator, rel, "--strict-memory-pair"], cwd=ROOT)
    if r != 0:
        failed.append(rel)

print("")
if failed:
    print("[FAIL] %d file(s):" % len(failed))
    for f in failed:
        print("  %s" % f)
    sys.exit(1)
print("[OK] All %d files passed." % len(files))
sys.exit(0)
