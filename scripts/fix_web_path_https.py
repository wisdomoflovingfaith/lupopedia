# -----
# lupopedia.headers:
#   header_format_version: "4.1.0"
#   file_path_from_root: scripts/fix_web_path_https.py
#   web_path: "https://www.lupopedia.com/lupopedia/scripts/fix_web_path_https.py"
#   status: "active"
#   when_updated: "20260415020000"
#   trust_tier: "staging"
#   questions_toon: null
#   memory_toon: "memory/development/staging/2026/04/fix-web-path-https.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/lupopedia-headers"
#   artifact_type: implementation
#   artifact_kind: script
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: ''
#   content_id: null
#   pk_id: null
#   pk_slug: "fix-web-path-https"
#   parent_pk_id: "16"
#   lupopedia.schema: implementation
#   title: ""
#   summary: "Replace http:// with https:// in the web_path header field of all Lupopedia in-scope files"
# -----
"""
fix_web_path_https.py  --  Replace http:// with https:// in web_path header fields.

Targets: docs/prd/*.md by default.
Use --dir to point at another directory, or pass explicit file paths.

Usage:
  python scripts/fix_web_path_https.py --dry-run
  python scripts/fix_web_path_https.py
  python scripts/fix_web_path_https.py scripts/*.py includes/*.php
"""

import argparse
import re
import sys
from pathlib import Path

# Matches: `  web_path: "http://...` inside the 22-line header envelope
_HTTP_RE = re.compile(r'^(\s{2}web_path:\s+")http://')
# For Python/PHP comment-grid headers: `#   web_path: "http://...`
_HTTP_COMMENT_RE = re.compile(r'^(#\s{3}web_path:\s+")http://')


def fix_file(path: Path, dry_run: bool) -> tuple[bool, str]:
    """Fix web_path http->https. Returns (changed, error_message)."""
    try:
        text = path.read_text(encoding='utf-8')
    except Exception as exc:
        return False, str(exc)

    lines = text.splitlines(keepends=True)
    suffix = path.suffix.lower()

    changed = False

    if suffix == '.md':
        # Markdown: look in header envelope lines[2]..lines[23]
        if len(lines) < 25:
            return False, 'too short'
        if lines[0].strip() != '---' or 'lupopedia.headers:' not in lines[1]:
            return False, 'no Markdown header'
        for i in range(2, 24):
            if _HTTP_RE.match(lines[i]):
                lines[i] = _HTTP_RE.sub(r'\g<1>https://', lines[i])
                changed = True
                break
    elif suffix in ('.py', '.php', '.js'):
        # Script: look in first 40 lines for the comment-grid web_path
        for i in range(min(40, len(lines))):
            if _HTTP_COMMENT_RE.match(lines[i]):
                lines[i] = _HTTP_COMMENT_RE.sub(r'\g<1>https://', lines[i])
                changed = True
                break
    else:
        return False, f'unsupported extension {suffix!r}'

    if changed and not dry_run:
        path.write_text(''.join(lines), encoding='utf-8')

    return changed, ''


def main():
    ap = argparse.ArgumentParser(description=__doc__,
                                 formatter_class=argparse.RawDescriptionHelpFormatter)
    ap.add_argument('paths', nargs='*', help='Files to process (default: docs/prd/*.md)')
    ap.add_argument('--dir', default='docs/prd',
                    help='Directory to scan (default: docs/prd); scans *.md')
    ap.add_argument('--dry-run', action='store_true',
                    help='Show what would change without writing')
    args = ap.parse_args()

    if args.paths:
        files = [Path(p) for p in args.paths if Path(p).is_file()]
    else:
        files = sorted(Path(args.dir).glob('*.md'))

    if not files:
        print(f'No files found.', file=sys.stderr)
        sys.exit(1)

    mode = 'DRY-RUN' if args.dry_run else 'LIVE'
    print(f'\nfix_web_path_https.py  [{mode}]  --  {len(files)} file(s)\n')

    n_fixed = 0
    n_ok = 0
    n_err = 0

    for path in files:
        changed, err = fix_file(path, dry_run=args.dry_run)
        if err and err not in ('too short', 'no Markdown header'):
            print(f'  SKIP   {path.name}  ({err})')
            n_err += 1
        elif changed:
            verb = 'WOULD FIX' if args.dry_run else 'FIXED    '
            print(f'  {verb}  {path.name}')
            n_fixed += 1
        else:
            n_ok += 1

    print(f'\nResults:')
    print(f'  {"Would fix" if args.dry_run else "Fixed"}  : {n_fixed}')
    print(f'  Already https: {n_ok}')
    print(f'  Skipped      : {n_err}')


if __name__ == '__main__':
    main()
