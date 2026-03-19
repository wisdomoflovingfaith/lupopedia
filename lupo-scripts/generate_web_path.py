#!/usr/bin/env python3
"""
Deterministically generate `web_path` from `file_path_from_root` for Markdown artifacts.

Canonical rule:
  web_path = "http://www.lupopedia.com/" + file_path_from_root

Notes:
- This tool can *write* (when --apply) but validators MUST NOT auto-fix.
- Updates only the first YAML frontmatter block (between the first two --- delimiters).
- Does nothing if no frontmatter or no file_path_from_root.
"""

from __future__ import annotations

import argparse
import re
import sys
from pathlib import Path


WEB_BASE = "http://www.lupopedia.com/"

RE_FILE_PATH = re.compile(
    r'^\s*file_path_from_root\s*:\s*(?:"([^"]+)"|([^\s#]+))\s*$',
    re.MULTILINE,
)
RE_WEB_PATH_LINE = re.compile(
    r'^(\s*web_path\s*:\s*)(?:"[^"]*"|[^\s#]+)(\s*)$',
    re.MULTILINE,
)


def _scalar(m: re.Match | None) -> str | None:
    if not m:
        return None
    return m.group(1) if m.group(1) is not None else m.group(2)


def expected_web_path(file_path_from_root: str) -> str:
    p = (file_path_from_root or "").strip().lstrip("/")
    return WEB_BASE + p


def split_frontmatter(text: str) -> tuple[str | None, str | None, str]:
    if not text.startswith("---"):
        return (None, None, text)
    parts = text.split("---", 2)
    if len(parts) < 3:
        return (None, None, text)
    # parts[0] = "" (before first ---), parts[1] = yaml, parts[2] = body (after second ---)
    return ("---", parts[1], parts[2])


def update_frontmatter(yaml_text: str) -> tuple[str, str | None, str | None]:
    """
    Returns (updated_yaml_text, old_web_path, new_web_path) or (yaml_text, None, None) if no change.
    """
    m_fp = RE_FILE_PATH.search(yaml_text)
    fp = _scalar(m_fp)
    if not fp:
        return (yaml_text, None, None)
    fp = fp.strip().lstrip("/")
    new_wp = expected_web_path(fp)

    m_wp = re.search(r'^\s*web_path\s*:\s*(?:"([^"]+)"|([^\s#]+))\s*$', yaml_text, re.MULTILINE)
    old_wp = _scalar(m_wp).strip() if m_wp and _scalar(m_wp) else None

    if m_wp:
        # Replace existing web_path line.
        updated = RE_WEB_PATH_LINE.sub(r'\1"%s"\2' % new_wp, yaml_text, count=1)
        if updated != yaml_text:
            return (updated, old_wp, new_wp)
        return (yaml_text, old_wp, new_wp)

    # Insert web_path after file_path_from_root line with same indentation level.
    # Determine indentation from the matched file_path_from_root line.
    line_start = yaml_text.rfind("\n", 0, m_fp.start())
    if line_start < 0:
        line_start = 0
    else:
        line_start += 1
    fp_line = yaml_text[line_start : yaml_text.find("\n", line_start) if "\n" in yaml_text[line_start:] else len(yaml_text)]
    indent = re.match(r"^(\s*)", fp_line).group(1)
    insert = fp_line + "\n" + indent + 'web_path: "%s"' % new_wp
    updated = yaml_text[:line_start] + insert + yaml_text[line_start + len(fp_line) :]
    return (updated, None, new_wp)


def process_file(path: Path, apply: bool) -> int:
    try:
        text = path.read_text(encoding="utf-8", errors="replace")
    except OSError as e:
        print("READ_ERROR: %s (%s)" % (path, e), file=sys.stderr)
        return 2

    _, fm, body = split_frontmatter(text)
    if fm is None:
        return 0

    updated_fm, old_wp, new_wp = update_frontmatter(fm)
    if new_wp is None:
        return 0

    if updated_fm == fm:
        # Ensure presence is correct even if formatting substitution didn't change.
        return 0

    if not apply:
        print("WOULD_UPDATE: %s web_path: %s -> %s" % (path, old_wp or "(missing)", new_wp))
        return 1

    out = "---" + updated_fm + "---" + body
    try:
        path.write_text(out, encoding="utf-8")
    except OSError as e:
        print("WRITE_ERROR: %s (%s)" % (path, e), file=sys.stderr)
        return 2

    print("UPDATED: %s" % path)
    return 1


def main() -> int:
    ap = argparse.ArgumentParser()
    ap.add_argument("--repo-root", default=".")
    ap.add_argument("--apply", action="store_true", help="Write changes to disk (default: dry-run)")
    ap.add_argument("--path", default="", help="Optional file or directory to scan (default: repo root)")
    args = ap.parse_args()

    repo = Path(args.repo_root).resolve()
    target = Path(args.path).resolve() if args.path else repo

    changed = 0
    if target.is_file():
        if target.suffix.lower() == ".md":
            rc = process_file(target, args.apply)
            changed += 1 if rc == 1 else 0
        return 0 if changed == 0 else 1

    for f in target.rglob("*.md"):
        if f.name == "README.md":
            continue
        rc = process_file(f, args.apply)
        changed += 1 if rc == 1 else 0

    return 0 if changed == 0 else 1


if __name__ == "__main__":
    raise SystemExit(main())

