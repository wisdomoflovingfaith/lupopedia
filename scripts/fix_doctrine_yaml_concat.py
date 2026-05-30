#!/usr/bin/env python3
"""Insert missing newlines before lupopedia.edges / lupopedia.footer when glued to prior line."""
from __future__ import print_function

import json
import re
import sys
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
DOCTRINE = ROOT / "docs" / "doctrine"


def fix_text(text):
    # Glued top-level keys (invalid YAML)
    t = re.sub(r"([^\n])lupopedia\.edges:", r"\1\n\nlupopedia.edges:", text)
    t = re.sub(r"([^\n])lupopedia\.footer:", r"\1\n\nlupopedia.footer:", t)
    # Empty outbound_edges followed immediately by duplicate lupopedia.edges:
    t = re.sub(
        r"lupopedia\.edges:\n  outbound_edges: \[\]\nlupopedia\.edges:",
        "lupopedia.edges:",
        t,
    )
    # outbound_edges: [] then list items (invalid); use proper list
    t = re.sub(
        r"outbound_edges: \[\]\n(\s+)-\s",
        r"outbound_edges:\n\1- ",
        t,
    )
    return t


def main():
    apply_fix = "--apply" in sys.argv
    nfiles = 0
    for path in sorted(DOCTRINE.rglob("*.md")):
        raw = path.read_text(encoding="utf-8", errors="replace")
        fixed = fix_text(raw)
        if fixed != raw:
            nfiles += 1
            if apply_fix:
                path.write_text(fixed, encoding="utf-8", newline="\n")
            else:
                print(path.relative_to(ROOT).as_posix())
    print(json.dumps({"files_changed": nfiles, "apply": apply_fix}, indent=2))


if __name__ == "__main__":
    main()
