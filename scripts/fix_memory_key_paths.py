# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.2"
#   file_path_from_root: "scripts/fix_memory_key_paths.py"
#   web_path: "https://www.lupopedia.com/lupopedia/scripts/fix_memory_key_paths.py"
#   status: "in_progress"
#   when_updated: "20260416185134"
#   trust_tier: "staging"
#   questions_toon: null
#   memory_toon: "memory/development/staging/2026/04/fix-memory-key-paths.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/lupopedia-headers"
#   artifact_type: implementation
#   artifact_kind: tool
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: ""
#   content_id: null
#   content_parent_id: "16"
#   content_slug: "fix-memory-key-paths"
#   default_collection_id: null
#   lupopedia.schema: implementation
#   title: "fix_memory_key_paths"
#   summary: "Fix memory_toon path segments per PRD 16 across Lupopedia header files."
# ---------------------------------------------------------------------
"""
fix_memory_key_paths.py  —  PRD 16 §8.1 memory_key path corrector

Problem:  trust_tier was changed to 'canonical' (or 'seed') but memory_key
          path segments still contain old 'staging' and/or wrong channel_key.

Correct formula (PRD 16 §5.2 + §8.1):
  memory/{channel_key}/{trust_tier}/{year_display}/{MM}/{filename}.toon

Year-display rules (PRD 16 §8.1):
  canonical  → calendar_year - 1000  (e.g. 2026 → 1026)
  seed       → calendar_year         (no offset)
  staging    → calendar_year         (no offset)
  archive    → calendar_year         (no offset)

Usage:
  python scripts/fix_memory_key_paths.py --dry-run
  python scripts/fix_memory_key_paths.py --dry-run --dir docs/prd
  python scripts/fix_memory_key_paths.py            (live — writes files)
  python scripts/fix_memory_key_paths.py docs/prd/16_lupopedia_headers.md
"""

import argparse
import re
import sys
from pathlib import Path

YEAR_OFFSET = {'canonical': -1000, 'seed': 0, 'staging': 0, 'archive': 0}

# Matches the 22 key lines inside the header (2-space indent, any header key)
_KV_RE = re.compile(r'^\s{2}([\w.]+):\s*(.*?)\s*$')
# Matches the memory_toon or legacy memory_key line precisely for replacement (v4.1.0 + backward compat)
_MK_LINE_RE = re.compile(r'^(\s{2}(?:memory_toon|memory_key):\s+)"?(memory/[^"\s]+\.toon)"?(.*)$')


def _strip_quotes(val: str) -> str:
    return val.strip().strip('"').strip("'")


def parse_header_fields(lines: list[str]) -> dict:
    """Return key→value dict for header lines 2..24 (0-indexed, lines[2] to lines[23])."""
    fields: dict = {}
    for line in lines[2:24]:
        m = _KV_RE.match(line)
        if m:
            fields[m.group(1)] = _strip_quotes(m.group(2))
    return fields


def compute_correct_memory_key(fields: dict, existing_mk: str) -> tuple[str | None, str | None]:
    """Return (correct_memory_key, error_message)."""
    trust_tier = fields.get('trust_tier', '')
    channel_key = fields.get('channel_key', '')
    when_updated = fields.get('when_updated', '')

    if not trust_tier:
        return None, 'missing trust_tier'
    if not channel_key:
        return None, 'missing channel_key'

    parts = existing_mk.strip().split('/')
    # Expected: ['memory', seg1, seg2, year, MM, filename]
    if len(parts) != 6 or parts[0] != 'memory' or not parts[5].endswith('.toon'):
        return None, f'unexpected memory_key structure ({len(parts)} parts): {existing_mk}'

    filename = parts[5]

    # Derive calendar year and month from when_updated (YYYYMMDDHHIISS)
    if len(when_updated) < 6:
        return None, f'cannot parse when_updated: {when_updated!r}'
    cal_year = int(when_updated[0:4])
    month_mm = when_updated[4:6]

    offset = YEAR_OFFSET.get(trust_tier, 0)
    display_year = cal_year + offset

    correct = f'memory/{channel_key}/{trust_tier}/{display_year}/{month_mm}/{filename}'
    return correct, None


def fix_file(path: Path, dry_run: bool) -> tuple[bool, str, str, str]:
    """Fix memory_key in a single file. Returns (changed, old_mk, new_mk, error)."""
    try:
        text = path.read_text(encoding='utf-8')
    except Exception as exc:
        return False, '', '', str(exc)

    lines = text.splitlines(keepends=True)

    if len(lines) < 25:
        return False, '', '', f'only {len(lines)} lines — too short for a header'
    if lines[0].strip() != '---' or 'lupopedia.headers:' not in lines[1]:
        return False, '', '', 'not a Lupopedia Markdown header file'

    fields = parse_header_fields(lines)

    # Locate the memory_key line within the header envelope (lines[2]..lines[23])
    mk_line_idx: int | None = None
    current_mk: str = ''
    for i in range(2, 24):
        m = _MK_LINE_RE.match(lines[i])
        if m:
            mk_line_idx = i
            current_mk = m.group(2)
            break

    if mk_line_idx is None:
        return False, '', '', 'memory_key line not found in header envelope'

    correct_mk, err = compute_correct_memory_key(fields, current_mk)
    if err:
        return False, current_mk, '', err

    if current_mk == correct_mk:
        return False, current_mk, correct_mk, ''  # Already correct — no change needed

    if not dry_run:
        m = _MK_LINE_RE.match(lines[mk_line_idx])
        indent_and_key = m.group(1)
        trailing = m.group(3).strip()        # preserve inline comments if any
        comment_part = f'  {trailing}' if trailing else ''
        lines[mk_line_idx] = f'{indent_and_key}"{correct_mk}"{comment_part}\n'
        path.write_text(''.join(lines), encoding='utf-8')

    return True, current_mk, correct_mk, ''


def main():
    ap = argparse.ArgumentParser(
        description='Fix memory_key path segments in Lupopedia header files (PRD 16 §8.1).',
        formatter_class=argparse.RawDescriptionHelpFormatter,
        epilog=__doc__,
    )
    ap.add_argument('paths', nargs='*', help='Specific files to process (default: all .md in --dir)')
    ap.add_argument('--dir', default='docs/prd',
                    help='Directory to scan for *.md files (default: docs/prd)')
    ap.add_argument('--dry-run', action='store_true',
                    help='Show what would change without writing any files')
    args = ap.parse_args()

    if args.paths:
        files = [Path(p) for p in args.paths if Path(p).is_file()]
    else:
        files = sorted(Path(args.dir).glob('*.md'))

    if not files:
        print(f'No files found in {args.dir!r}', file=sys.stderr)
        sys.exit(1)

    mode = 'DRY-RUN' if args.dry_run else 'LIVE'
    print(f'\nfix_memory_key_paths.py  [{mode}]  —  {len(files)} file(s) to check\n')

    n_changed = 0
    n_correct = 0
    n_errors = 0

    for path in files:
        changed, old_mk, new_mk, err = fix_file(path, dry_run=args.dry_run)

        if err:
            print(f'  SKIP   {path.name}')
            print(f'         reason: {err}')
            n_errors += 1
        elif changed:
            verb = 'WOULD FIX' if args.dry_run else 'FIXED    '
            print(f'  {verb}  {path.name}')
            print(f'           OLD: {old_mk}')
            print(f'           NEW: {new_mk}')
            n_changed += 1
        else:
            n_correct += 1

    print()
    print(f'Results:')
    print(f'  {"Would fix" if args.dry_run else "Fixed"}  : {n_changed}')
    print(f'  Already correct: {n_correct}')
    print(f'  Skipped/errors : {n_errors}')
    if args.dry_run and n_changed:
        print(f'\nRe-run without --dry-run to apply {n_changed} fix(es).')


if __name__ == '__main__':
    main()
