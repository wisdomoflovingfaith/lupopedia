#!/usr/bin/env python3
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.0"
#   file_path_from_root: "scripts/fix_double_headers.py"
#   web_path: "https://www.lupopedia.com/lupopedia/scripts/fix_double_headers.py"
#   status: "complete"
#   when_updated: "20260411035853"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "memory/development/canonical/1026/04/fix-double-headers.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/fix-double-headers"
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
#   title: "Fix double headers (deduplicate YAML front matter)"
#   summary: "Deduplicate leading lupopedia.headers YAML blocks in Markdown"
# ---------------------------------------------------------------------
"""
Remove duplicate Lupopedia YAML front matter blocks from Markdown files.

**Prerequisite:** On files with box-drawing Unicode or mojibake in the header
region, run `fix_unicode_box_drawing_ascii.py` first so line parsing is stable.

Normalization mistakes sometimes left two consecutive `---` blocks, with the
second prefixed by a stray UTF-8 BOM (U+FEFF). When multiple blocks each
contain `lupopedia.headers:`, this script keeps the block with the newest
`when_updated` (tie-break: `last_modified_utc`), then rewrites the file with a
single header and the original body.

Tie-break: ``when_updated`` only. ``last_modified_utc`` was renamed to ``questions_toon``
in PRD 16 v4.0.99 §4.2 field 6 and is no longer a timestamp, so it is not used.

**25-line envelope (PRD 16 v4.0.99):** After the chosen inner block (``lupopedia.headers:``
plus exactly **22** key lines, no internal blanks), reconstruction appends ``\\n---\\n``
so line 25 is the closing ``---`` (dense block; no blank lines 23–24).

Default preset: numbered PRD glob. Override with ``--preset`` or ``--path`` or a single ``--file``.

Usage:
  python scripts/fix_double_headers.py [--dry-run] [--backup] [--verbose] [--preset ...]
  python scripts/fix_double_headers.py --path "docs/prd/*.md"
  python scripts/fix_double_headers.py --file docs/prd/16_lupopedia_headers.md --verbose
  python scripts/fix_double_headers.py --universal-validate
"""

from __future__ import annotations

import argparse
import glob
import os
import shutil
import stat
import subprocess
import sys

_SCRIPTS_DIR = os.path.dirname(os.path.abspath(__file__))
if _SCRIPTS_DIR not in sys.path:
    sys.path.insert(0, _SCRIPTS_DIR)

from lib.lupopedia_markdown_header_peel import (  # noqa: E402
    header_timestamp_tuple,
    peel_leading_lupopedia_yaml_blocks,
    select_newest_lupopedia_header_inner,
)

# After inner: closing --- on the next line (no blank lines before ---).
_ENVELOPE_AFTER_INNER = "\n---\n"
# Inner = lupopedia.headers: plus exactly 22 key lines (PRD 16 v4.0.99 section 4.2).
_EXPECTED_INNER_LINES = 23

_PRESET_GLOBS = {
    "numbered-prd": "docs/prd/[0-9][0-9]_*.md",
    "all-prd": "docs/prd/*.md",
    "doctrine": "docs/doctrine/**/*.md",
    "docs": "docs/**/*.md",
}


def _inner_key_block_line_count(inner: str) -> int:
    t = inner.replace("\r\n", "\n").rstrip("\n")
    if not t:
        return 0
    return len(t.split("\n"))


def _validate_single_header(content: str) -> bool:
    """Ensure merged file peels to exactly one lupopedia block."""
    inners, _ = peel_leading_lupopedia_yaml_blocks(content)
    if len(inners) != 1:
        return False
    if "lupopedia.headers:" not in inners[0]:
        return False
    return True


def fix_double_headers(
    filepath: str,
    dry_run: bool,
    backup: bool,
    verbose: bool,
    check_peel: bool,
    repo_root: str,
    universal_validate: bool,
) -> int:
    """Return 0 = no duplicate blocks, 1 = fixed or dry-run fix, 2 = error."""
    try:
        with open(filepath, "r", encoding="utf-8-sig") as f:
            content = f.read()
    except UnicodeDecodeError as e:
        print("[ERROR] UTF-8 decode failed for %s: %s" % (filepath, e))
        return 2
    except (OSError, IOError) as e:
        print("[ERROR] Failed to read %s: %s" % (filepath, e))
        return 2

    content = content.replace("\r\n", "\n")

    inners, body = peel_leading_lupopedia_yaml_blocks(content)
    if len(inners) < 2:
        if verbose:
            print(
                "[VERBOSE] %s: %d leading lupopedia block(s), nothing to merge"
                % (filepath, len(inners))
            )
        return 0

    if len(inners) > 2:
        print(
            "[WARN] %s: found %d consecutive lupopedia.headers blocks (unusual)"
            % (filepath, len(inners))
        )
        if verbose:
            for i, blk in enumerate(inners):
                w, _lm = header_timestamp_tuple(blk)
                print(
                    "       block %d: when_updated=%014d" % (i + 1, w)
                )

    best_inner = select_newest_lupopedia_header_inner(inners)
    if not best_inner.lstrip().startswith("lupopedia.headers:"):
        print(
            "[WARN] Skipping %s: newest block does not start with lupopedia.headers:"
            % filepath
        )
        return 0

    nlines = _inner_key_block_line_count(best_inner)
    if nlines != _EXPECTED_INNER_LINES:
        print(
            "[WARN] %s: chosen inner has %d lines, expected %d (lupopedia.headers + 22 keys); merge may need normalize_lupopedia_md_header_25.py"
            % (filepath, nlines, _EXPECTED_INNER_LINES)
        )

    if verbose:
        w, _lm = header_timestamp_tuple(best_inner)
        print(
            "[VERBOSE] %s: keeping block with when_updated=%014d (of %d blocks)"
            % (filepath, w, len(inners))
        )

    new_content = (
        "---\n"
        + best_inner.rstrip("\n")
        + _ENVELOPE_AFTER_INNER
        + body.lstrip("\n\r\ufeff \t")
    )

    if check_peel and not _validate_single_header(new_content):
        print(
            "[ERROR] Post-merge peel check failed (expected exactly one header): %s"
            % filepath
        )
        return 2

    if dry_run:
        print("[DRY-RUN] would fix: %s (%d blocks -> 1)" % (filepath, len(inners)))
        return 1

    pre_mode = None
    try:
        pre_mode = stat.S_IMODE(os.stat(filepath).st_mode)
    except OSError:
        pre_mode = None

    if backup:
        backup_path = filepath + ".bak"
        try:
            shutil.copy2(filepath, backup_path)
            if verbose:
                print("[VERBOSE] backup: %s" % backup_path)
        except (OSError, IOError) as e:
            print("[ERROR] Backup failed for %s: %s" % (filepath, e))
            return 2

    try:
        with open(filepath, "w", encoding="utf-8", newline="\n") as f:
            f.write(new_content)
    except (OSError, IOError) as e:
        print("[ERROR] Failed to write %s: %s" % (filepath, e))
        return 2

    if pre_mode is not None:
        try:
            os.chmod(filepath, pre_mode)
        except OSError:
            pass

    print("[FIXED] %s (merged %d duplicate header blocks)" % (filepath, len(inners)))

    if universal_validate:
        rel = os.path.relpath(filepath, repo_root).replace("\\", "/")
        vpy = os.path.join(_SCRIPTS_DIR, "validate_lupopedia_headers_universal.py")
        proc = subprocess.run(
            [sys.executable, vpy, rel, "--quiet"],
            cwd=repo_root,
        )
        if proc.returncode != 0:
            print(
                "[ERROR] universal validator failed for %s (file was written); fix manually"
                % filepath
            )
            return 2

    return 1


def _collect_paths(repo_root: str, preset: str, path_override: str | None, file_one: str | None) -> list[str]:
    if file_one:
        p = os.path.normpath(os.path.join(repo_root, file_one.replace("/", os.sep)))
        return [p] if os.path.isfile(p) else []

    rel = path_override if path_override is not None else _PRESET_GLOBS[preset]
    pattern = os.path.normpath(os.path.join(repo_root, rel.replace("/", os.sep)))
    if "**" in rel:
        return sorted(glob.glob(pattern, recursive=True))
    return sorted(glob.glob(pattern))


def main() -> int:
    parser = argparse.ArgumentParser(description="Deduplicate leading lupopedia.headers YAML blocks.")
    parser.add_argument(
        "--preset",
        choices=sorted(_PRESET_GLOBS.keys()),
        default="numbered-prd",
        help="Which files to scan when --path is omitted (default: numbered-prd)",
    )
    parser.add_argument(
        "--path",
        default=None,
        help="Glob relative to repo root (overrides --preset). Use ** for recursion.",
    )
    parser.add_argument(
        "--file",
        default=None,
        metavar="REL_PATH",
        help="Single Markdown file relative to repo root (overrides --path and --preset)",
    )
    parser.add_argument("--dry-run", action="store_true", help="Print actions without writing")
    parser.add_argument(
        "--backup",
        action="store_true",
        help="Copy each modified file to filepath.bak before overwrite",
    )
    parser.add_argument("--verbose", "-v", action="store_true", help="Per-file diagnostics and timestamps")
    parser.add_argument(
        "--no-validate",
        action="store_true",
        help="Skip post-merge single-block peel check (not recommended)",
    )
    parser.add_argument(
        "--universal-validate",
        action="store_true",
        help="After each write, run validate_lupopedia_headers_universal.py (exit 2 if it fails)",
    )
    args = parser.parse_args()

    repo_root = os.path.dirname(_SCRIPTS_DIR)
    paths = _collect_paths(repo_root, args.preset, args.path, args.file)
    fixed = 0
    unchanged = 0
    errors = 0
    check_peel = not args.no_validate

    if args.file and not paths:
        print("[ERROR] --file not found or not a file: %s" % args.file)
        return 1

    if not paths:
        print("[WARN] No files matched the glob.")
        return 0

    for path in paths:
        if not os.path.isfile(path):
            continue
        try:
            code = fix_double_headers(
                path,
                args.dry_run,
                args.backup,
                args.verbose,
                check_peel,
                repo_root,
                args.universal_validate,
            )
        except Exception as e:
            print("[ERROR] Unexpected failure on %s: %s" % (path, e))
            errors += 1
            continue
        if code == 1:
            fixed += 1
        elif code == 0:
            unchanged += 1
        elif code == 2:
            errors += 1

    scanned = sum(1 for p in paths if os.path.isfile(p))
    print(
        "----------\n[SUMMARY] scanned=%d unchanged=%d %s=%d errors=%d"
        % (
            scanned,
            unchanged,
            "would_fix" if args.dry_run else "fixed",
            fixed,
            errors,
        )
    )
    if errors:
        return 1
    return 0


if __name__ == "__main__":
    sys.exit(main())
