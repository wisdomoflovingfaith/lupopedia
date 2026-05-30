#!/usr/bin/env python3
"""
Rename deprecated YAML key ``wolfie.headers:`` to ``lupopedia.headers:`` in Markdown.

Only lines matching ``^\\s*wolfie\\.headers\\s*:`` are changed (same rule as
find_version_ghosts.py). This preserves inline values and nested blocks; it does
not merge duplicate front matter or fix broken YAML.

Targets: docs/doctrine and docs/prd (excluding versions/3.0.x and archive).

Usage:
  python scripts/convert_wolfie_to_lupo.py          # dry-run (JSON to stdout)
  python scripts/convert_wolfie_to_lupo.py --apply  # write files
"""
from __future__ import print_function

import argparse
import json
import re
import sys
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]

DEFAULT_REL_ROOTS = ("docs/doctrine", "docs/prd")

EXCLUDE_SUBSTRINGS = (
    "docs/versions/3.0.x/",
    "/versions/archive/",
)

# Line-start key only (not wolfie.headers.version:)
RE_WOLFIE_KEY = re.compile(r"^(\s*)wolfie\.headers(\s*:)", re.MULTILINE)


def should_skip(rel_posix):
    p = rel_posix.replace("\\", "/")
    for ex in EXCLUDE_SUBSTRINGS:
        if ex in p:
            return True
    return False


def convert_text(text):
    """Return (new_text, replacement_count)."""
    def repl(m):
        return "%slupopedia.headers%s" % (m.group(1), m.group(2))

    new_text, n = RE_WOLFIE_KEY.subn(repl, text)
    return new_text, n


def main():
    ap = argparse.ArgumentParser()
    ap.add_argument("--roots", default=",".join(DEFAULT_REL_ROOTS))
    ap.add_argument("--apply", action="store_true")
    args = ap.parse_args()

    roots = [r.strip() for r in args.roots.split(",") if r.strip()]
    summary = []
    total_repls = 0
    changed_files = 0

    for r in roots:
        base = ROOT.joinpath(*r.split("/"))
        if not base.is_dir():
            continue
        for path in sorted(base.rglob("*.md")):
            rel = path.relative_to(ROOT).as_posix()
            if should_skip(rel):
                continue
            raw = path.read_text(encoding="utf-8", errors="replace")
            new_text, n = convert_text(raw)
            if n == 0:
                continue
            changed_files += 1
            total_repls += n
            summary.append({"path": rel, "replacements": n})
            if args.apply:
                path.write_text(new_text, encoding="utf-8", newline="\n")

    out = {
        "roots": roots,
        "changed_files": changed_files,
        "total_replacements": total_repls,
        "files": summary,
        "apply": bool(args.apply),
    }
    print(json.dumps(out, indent=2))
    sys.exit(0)


if __name__ == "__main__":
    main()
