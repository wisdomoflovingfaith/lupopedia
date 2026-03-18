#!/usr/bin/env python3
"""
Move selected coordination status markdown files into version archive.

Default (4.0.80): moves five persona/doctrine alignment artifacts to
lupo-docs/versions/4.0.80/status_coordination_archive/

Does not delete lupo-docs/status/prompts/ or other files unless --extra specified.

Usage:
  python lupo-scripts/migrate_status_files.py --repo-root . --execute
  python lupo-scripts/migrate_status_files.py --repo-root .   # dry-run
"""
from __future__ import annotations

import argparse
import os
import shutil
import sys
from pathlib import Path

DEFAULT_FILES = [
    "lupo-docs/status/multi_agent_doctrine_alignment_4_0_80.md",
    "lupo-docs/status/comprehensive_registry_update_108_actors.md",
    "lupo-docs/status/doctrine_comprehensive_update_108_agents.md",
    "lupo-docs/status/ten_primary_coordination_personas_update.md",
    "lupo-docs/status/rose_added_as_11th_primary_coordination_persona.md",
]


def main() -> int:
    ap = argparse.ArgumentParser()
    ap.add_argument("--repo-root", default=".")
    ap.add_argument(
        "--dest",
        default="lupo-docs/versions/4.0.80/status_coordination_archive",
        help="Destination directory relative to repo root",
    )
    ap.add_argument("--execute", action="store_true", help="Actually move files")
    ap.add_argument(
        "--files",
        nargs="*",
        default=DEFAULT_FILES,
        help="Relative paths to move",
    )
    args = ap.parse_args()
    root = Path(args.repo_root).resolve()
    dest = root / args.dest
    for rel in args.files:
        src = root / rel.replace("/", os.sep)
        if not src.is_file():
            print("SKIP (missing):", rel)
            continue
        target = dest / src.name
        if args.execute:
            dest.mkdir(parents=True, exist_ok=True)
            shutil.move(str(src), str(target))
            print("MOVED", rel, "->", target.relative_to(root))
        else:
            print("WOULD MOVE", rel, "->", dest.as_posix() + "/" + src.name)
    if not args.execute:
        print("\nDry-run. Pass --execute to move.")
    return 0


if __name__ == "__main__":
    sys.exit(main())
