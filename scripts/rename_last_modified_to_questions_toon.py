# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.0"
#   file_path_from_root: "scripts/rename_last_modified_to_questions_toon.py"
#   web_path: "https://www.lupopedia.com/lupopedia/scripts/rename_last_modified_to_questions_toon.py"
#   status: "active"
#   when_updated: "20260415050000"
#   trust_tier: "staging"
#   questions_toon: null
#   memory_toon: "memory/development/staging/2026/04/rename-last-modified-to-questions-toon.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/lupopedia-headers"
#   artifact_type: implementation
#   artifact_kind: script
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: ""
#   content_id: null
#   pk_id: null
#   pk_slug: ""
#   parent_pk_id: "16"
#   lupopedia.schema: implementation
#   title: ""
#   summary: "One-shot migration: rename last_modified_utc to questions_toon: null in the comment-grid own-headers of all Python scripts in scripts/ and bin/"
# ---------------------------------------------------------------------
"""
rename_last_modified_to_questions_toon.py

Renames  last_modified_utc: "<timestamp>"  →  questions_toon: null
ONLY inside the Python comment-grid own-header envelope (lines 1-30 of
each .py file, between the opening and closing  # ---  fences).

Does NOT touch functional code (string templates, .get() calls, etc.)
outside the header envelope.

Usage:
    python scripts/rename_last_modified_to_questions_toon.py --dry-run
    python scripts/rename_last_modified_to_questions_toon.py
"""

import argparse
import re
import sys
from pathlib import Path

# Match  #   last_modified_utc: "20260xxx"  or  #   last_modified_utc: null
_OWN_HEADER_LMU_RE = re.compile(
    r'^(#\s{3})last_modified_utc:\s+.*$', re.MULTILINE
)

# The replacement line — always null (questions_toon has no value yet)
_REPLACEMENT = r'\g<1>questions_toon: null'

# Fences that delimit the comment-grid header block in Python scripts
_FENCE_RE = re.compile(r'^# -+\s*$', re.MULTILINE)

# Maximum line to search for the header (the own-header is always near the top)
_MAX_HEADER_LINE = 35


def fix_own_header(text: str) -> tuple[str, bool]:
    """
    Replace last_modified_utc with questions_toon: null ONLY inside
    the first comment-grid header block (between the first two # --- fences).
    Returns (new_text, changed).
    """
    lines = text.splitlines(keepends=True)
    # Find first and second fence within _MAX_HEADER_LINE lines
    fence_indices = []
    for i, line in enumerate(lines[:_MAX_HEADER_LINE]):
        if _FENCE_RE.match(line):
            fence_indices.append(i)
        if len(fence_indices) == 2:
            break

    if len(fence_indices) < 2:
        return text, False  # No comment-grid header found

    start, end = fence_indices[0], fence_indices[1]
    header_block = ''.join(lines[start:end + 1])

    if 'last_modified_utc:' not in header_block:
        return text, False  # Field not present in this header

    new_header_block = _OWN_HEADER_LMU_RE.sub(_REPLACEMENT, header_block)
    if new_header_block == header_block:
        return text, False

    new_lines = lines[:start] + list(new_header_block) + lines[end + 1:]
    # splitlines+join approach is cleaner
    new_text = ''.join(lines[:start]) + new_header_block + ''.join(lines[end + 1:])
    return new_text, True


def process_dir(directory: str, dry_run: bool) -> tuple[int, int, int]:
    fixed = skipped = errors = 0
    for path in sorted(Path(directory).glob('*.py')):
        try:
            text = path.read_text(encoding='utf-8')
        except Exception as exc:
            print(f'  ERROR reading {path.name}: {exc}')
            errors += 1
            continue
        new_text, changed = fix_own_header(text)
        if not changed:
            skipped += 1
            continue
        verb = 'DRY-RUN' if dry_run else 'FIXED  '
        print(f'  {verb}  {path.name}')
        if not dry_run:
            path.write_text(new_text, encoding='utf-8')
        fixed += 1
    return fixed, skipped, errors


def main():
    ap = argparse.ArgumentParser(description=__doc__,
                                 formatter_class=argparse.RawDescriptionHelpFormatter)
    ap.add_argument('--dry-run', action='store_true',
                    help='Show what would change without writing files')
    args = ap.parse_args()

    mode = 'DRY-RUN' if args.dry_run else 'LIVE'
    print(f'\nrename_last_modified_to_questions_toon.py  [{mode}]\n')

    total_fixed = total_skip = total_err = 0
    for d in ('scripts', 'scripts/lib', 'bin'):
        p = Path(d)
        if not p.is_dir():
            continue
        print(f'\n--- {d}/ ---')
        f, s, e = process_dir(d, args.dry_run)
        total_fixed += f
        total_skip += s
        total_err += e

    print(f'\nTotal: {total_fixed} {"would fix" if args.dry_run else "fixed"}, '
          f'{total_skip} already correct / no header, {total_err} errors')


if __name__ == '__main__':
    main()
