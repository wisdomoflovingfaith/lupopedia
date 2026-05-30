#!/usr/bin/env python3
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.0"
#   file_path_from_root: "scripts/migrate_module_to_atoms_toon.py"
#   web_path: "https://www.lupopedia.com/lupopedia/scripts/migrate_module_to_atoms_toon.py"
#   status: "active"
#   when_updated: "20260415050000"
#   trust_tier: "staging"
#   questions_toon: null
#   memory_toon: "memory/development/staging/2026/04/migrate-module-to-atoms-toon.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/migrate-module-to-atoms-toon"
#   artifact_type: implementation
#   artifact_kind: tool
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: ""
#   content_id: null
#   pk_id: null
#   pk_slug: ""
#   parent_pk_id: "16"
#   lupopedia.schema: implementation
#   title: "Migration script: module -> atoms_toon (PRD 16 field 21 → 9)"
#   summary: "Deterministic header-zone migration: module: null -> atoms_toon: null. Non-null module flagged for review."
# ---------------------------------------------------------------------
"""
Deterministic migration script: header field `module` → `atoms_toon` (PRD 16 v4.0.99 field 21).

Rules (per spec):
  - Operates on the HEADER ZONE ONLY (first 50 lines of each file)
  - Replaces `module: null` → `atoms_toon: null` (preserving field position)
  - Files with non-null `module` values are FLAGGED FOR REVIEW (not auto-converted)
  - Preserves field order exactly
  - Supports --dry-run (default: True — must pass --write to apply)
  - Logs all changes and flags

Usage:
  python scripts/migrate_module_to_atoms_toon.py [roots...] [--write] [--verbose]

Defaults:
  roots = docs/prd docs/doctrine scripts agents
  --dry-run (safe by default; pass --write to apply)
"""

from __future__ import annotations

import argparse
import os
import re
import sys
from pathlib import Path
from typing import List, Tuple

# Regex: matches `  module: null` in header zone (exact, case-sensitive, 2-space indent)
_RE_MODULE_NULL = re.compile(r"^  module: null\s*$")
# Regex: matches `  module: <non-null>` for flagging
_RE_MODULE_NONNULL = re.compile(r"^  module: (?!null)(.+?)\s*$")
# Regex: skip if already migrated (atoms_toon already present in header zone)
_RE_ATOMS_TOON = re.compile(r"^  atoms_toon:")

HEADER_ZONE_LINES = 50  # scan first 50 lines (header is always in lines 1-25)


def _scan_file(path: Path) -> Tuple[str, str, str]:
    """
    Returns (status, detail, new_content).
    status: 'migrated' | 'flagged' | 'skip' | 'already_done'
    """
    try:
        text = path.read_text(encoding="utf-8", errors="replace")
    except Exception as exc:
        return ("error", str(exc), "")

    lines = text.split("\n")
    header_zone = lines[:HEADER_ZONE_LINES]

    # Check if atoms_toon already present in header zone
    if any(_RE_ATOMS_TOON.match(ln) for ln in header_zone):
        return ("already_done", "atoms_toon already present", text)

    module_null_idx = None
    nonnull_value = None
    for i, ln in enumerate(header_zone):
        if _RE_MODULE_NULL.match(ln):
            module_null_idx = i
            break
        m = _RE_MODULE_NONNULL.match(ln)
        if m:
            nonnull_value = m.group(1)
            break

    if module_null_idx is not None:
        lines[module_null_idx] = "  atoms_toon: null"
        new_content = "\n".join(lines)
        return ("migrated", "line %d: module: null -> atoms_toon: null" % (module_null_idx + 1), new_content)

    if nonnull_value is not None:
        return ("flagged", "non-null module value %r — MANUAL REVIEW REQUIRED" % nonnull_value, text)

    return ("skip", "no module field found in header zone", text)


def _collect_files(roots: List[str]) -> List[Path]:
    paths = []
    extensions = {".md", ".py", ".php", ".js", ".sql", ".html", ".htm", ".txt"}
    for root in roots:
        p = Path(root)
        if not p.exists():
            continue
        for f in sorted(p.rglob("*")):
            if f.is_file() and f.suffix in extensions:
                paths.append(f)
    return paths


def main() -> int:
    parser = argparse.ArgumentParser(description="Migrate module -> atoms_toon in header zone.")
    parser.add_argument("roots", nargs="*", default=["docs/prd", "docs/doctrine", "scripts", "agents"])
    parser.add_argument("--write", action="store_true", help="Apply changes (default: dry-run)")
    parser.add_argument("--verbose", action="store_true")
    args = parser.parse_args()

    dry_run = not args.write
    files = _collect_files(args.roots)

    counts = {"migrated": 0, "flagged": 0, "skip": 0, "already_done": 0, "error": 0}

    for path in files:
        status, detail, new_content = _scan_file(path)
        counts[status] = counts.get(status, 0) + 1

        if status == "migrated":
            prefix = "[DRY-RUN] " if dry_run else "[MIGRATED]"
            print("%s %s — %s" % (prefix, path, detail))
            if not dry_run:
                path.write_text(new_content, encoding="utf-8")
        elif status == "flagged":
            print("[FLAGGED ] %s — %s" % (path, detail))
        elif status == "error":
            print("[ERROR   ] %s — %s" % (path, detail))
        elif args.verbose:
            print("[%-8s] %s" % (status.upper(), path))

    print("\n--- Summary ---")
    print("Migrated  : %d" % counts["migrated"])
    print("Flagged   : %d (non-null module — manual review required)" % counts["flagged"])
    print("Already OK: %d (atoms_toon already present)" % counts["already_done"])
    print("Skipped   : %d (no module field in header zone)" % counts["skip"])
    print("Errors    : %d" % counts.get("error", 0))
    if dry_run:
        print("\n[DRY-RUN] No files were modified. Pass --write to apply.")
    return 0


if __name__ == "__main__":
    sys.exit(main())
