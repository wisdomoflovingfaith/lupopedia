#!/usr/bin/env python3
# lupopedia.headers:
#   when_updated: "20260324175617"
#   file_path_from_root: "scripts/migrate_legacy_docs.py"
#   questions_toon: null
#   channel_id: 42
#   actor_id: 102
#   actor_name: "cursor"
#   delegation_chain: "cursor:root"
#   artifact_type: "tooling"
#   artifact_kind: "script"
# lupopedia.footer:
#   last_verified: "20260324175617"
#   last_verified_by: "cursor"
#   last_verified_by_actor_id: 102

"""
Phase 2: Migrate legacy docs in docs/ to add canonical Synthesized Documentation headers.

Scans Markdown files under docs/ (or given path), adds a YAML header block if missing.
Header fields: FILE, CLASS, NAMESPACE, CHANNEL, COLLECTION, ORCHESTRATOR, FACET, AGENT, ROLE,
TASK, TIMESTAMP_UTC, DATABASE.TABLE, RUNTIME.MIN_PHP. Uses sensible defaults; override via --config.

Usage:
  python scripts/migrate_legacy_docs.py [--dry-run] [--path docs/]
"""

from __future__ import print_function

import argparse
import os
import re
import sys
from datetime import datetime

SCRIPT_DIR = os.path.dirname(os.path.abspath(__file__))
if SCRIPT_DIR not in sys.path:
    sys.path.insert(0, SCRIPT_DIR)

# Default header template (Synthesized Documentation Framework)
DEFAULT_HEADER = """---
synthesized.headers:
  FILE: "{file_path}"
  CLASS: "documentation"
  NAMESPACE: "lupopedia.docs.legacy"
  CHANNEL: "ide"
  COLLECTION: "active"
  ORCHESTRATOR: "wolfie"
  FACET: "documentation"
  AGENT: "cursor"
  ROLE: "writer"
  TASK: "legacy_migration"
  TIMESTAMP_UTC: "{timestamp_utc}"
  DATABASE.TABLE: ""
  RUNTIME.MIN_PHP: "5.6"
Lupopedia.footer:
  orchestrator.actor: "wolfie"
  version_written: "4.0.71"
---
"""


def has_canonical_header(content):
    """Return True if content starts with --- and has synthesized.headers or flare.headers or lupopedia.headers."""
    if not content.startswith("---"):
        return False
    match = re.match(r"^---\s*\n(.*?)\n---", content, re.DOTALL)
    if not match:
        return False
    front = match.group(1)
    return "synthesized.headers:" in front or "flare.headers:" in front or "lupopedia.headers:" in front


def add_header(file_path, content, rel_path, timestamp_utc):
    """Build new content with header prepended."""
    file_placeholder = rel_path.replace("\\", "/")
    header = DEFAULT_HEADER.format(file_path=file_placeholder, timestamp_utc=timestamp_utc)
    return header + content.lstrip()


def main():
    parser = argparse.ArgumentParser(description="Add canonical headers to legacy Markdown in docs/")
    parser.add_argument("--dry-run", action="store_true", help="Do not write files")
    parser.add_argument("--path", default=None, help="Root path to scan (default: docs/ under project root)")
    args = parser.parse_args()
    root = args.path
    if not root:
        root = os.path.join(SCRIPT_DIR, "..", "docs")
    root = os.path.abspath(root)
    if not os.path.isdir(root):
        print("Not a directory: " + root, file=sys.stderr)
        sys.exit(1)
    timestamp_utc = datetime.utcnow().strftime("%Y%m%d%H%M%S")
    updated = 0
    for dirpath, _dirnames, filenames in os.walk(root):
        for name in filenames:
            if not name.lower().endswith(".md"):
                continue
            path = os.path.join(dirpath, name)
            try:
                with open(path, "r", encoding="utf-8", errors="replace") as f:
                    content = f.read()
            except Exception as e:
                print("Read error {}: {}".format(path, e), file=sys.stderr)
                continue
            if has_canonical_header(content):
                continue
            rel_path = os.path.relpath(path, os.path.dirname(root))
            new_content = add_header(path, content, rel_path, timestamp_utc)
            if args.dry_run:
                print("Would add header: " + path)
            else:
                try:
                    with open(path, "w", encoding="utf-8") as f:
                        f.write(new_content)
                    print("Added header: " + path)
                except Exception as e:
                    print("Write error {}: {}".format(path, e), file=sys.stderr)
                    continue
            updated += 1
    print("Done. Files updated: {}".format(updated))
    return 0


if __name__ == "__main__":
    sys.exit(main())