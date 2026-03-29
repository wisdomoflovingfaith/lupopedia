#!/usr/bin/env python3
"""
Ensure a LUPOPEDIA markdown file is imported into the database (lupo_contents + metadata + edges).

If lupopedia.headers.content_id is missing, runs import_content.py, then reports content_id.

Usage:
  python lupo-scripts/ensure_imported.py <path-to.md>

Requires: PyYAML, pymysql, lupopedia-config.php or DB_* env vars (same as import_content.py).
"""

from __future__ import annotations

import re
import subprocess
import sys
from pathlib import Path
from typing import Optional

_SCRIPTS = Path(__file__).resolve().parent
_REPO = _SCRIPTS.parent
if str(_SCRIPTS) not in sys.path:
    sys.path.insert(0, str(_SCRIPTS))

from lib.header_validation import parse_front_matter_header  # noqa: E402


def _content_id_from_header_text(path: Path) -> Optional[int]:
    text = path.read_text(encoding="utf-8", errors="replace")
    parsed = parse_front_matter_header(text)
    if not parsed.get("valid"):
        return None
    header = parsed.get("header") or {}
    raw = header.get("content_id")
    if raw is None:
        return None
    if isinstance(raw, int):
        return raw
    s = str(raw).strip()
    if re.fullmatch(r"\d+", s):
        return int(s)
    return None


def main() -> int:
    if len(sys.argv) != 2:
        print("Usage: python lupo-scripts/ensure_imported.py <file.md>", file=sys.stderr)
        return 2

    target = Path(sys.argv[1])
    if not target.is_file():
        print("ERROR: Not a file: %s" % (target,), file=sys.stderr)
        return 2

    cid = _content_id_from_header_text(target)
    if cid is not None:
        print("OK already imported: content_id=%s (%s)" % (cid, target))
        return 0

    print("Importing (no content_id in header): %s" % (target,), file=sys.stderr)
    proc = subprocess.run(
        [sys.executable, str(_SCRIPTS / "import_content.py"), str(target.resolve())],
        cwd=str(_REPO),
    )
    if proc.returncode != 0:
        print("ERROR: import_content.py failed with exit %s" % proc.returncode, file=sys.stderr)
        return proc.returncode or 1

    cid2 = _content_id_from_header_text(target)
    if cid2 is None:
        print("ERROR: Import ran but content_id still missing in %s" % (target,), file=sys.stderr)
        return 1

    print("OK imported: content_id=%s (%s)" % (cid2, target))
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
