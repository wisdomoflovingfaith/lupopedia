#!/usr/bin/env python3
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.0"
#   file_path_from_root: "lupo-scripts/fix_unicode_box_drawing_ascii.py"
#   web_path: "https://www.lupopedia.com/lupopedia/lupo-scripts/fix_unicode_box_drawing_ascii.py"
#   status: "complete"
#   when_updated: "20260410192601"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "lupo-memory/development/canonical/1026/04/fix-unicode-box-drawing-ascii.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/fix-unicode-box-drawing-ascii"
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
#   title: "Fix Unicode box drawing and UTF-8-as-Latin-1 mojibake in text files"
#   summary: ""
# ---------------------------------------------------------------------
"""
Replace Unicode box-drawing glyphs (and UTF-8 misread as Latin-1 mojibake) with
ASCII-safe equivalents for box characters; repair common punctuation mojibake to
proper Unicode (UTF-8) code points.

Targets PRD 00 ASCII safety (section 9.10) for diagrams; punctuation fixes keep
docs readable without adding non-stdlib dependencies (no chardet).

**Order of operations:** Run this **before** ``fix_double_headers.py`` — mojibake
can confuse header peeling / line counts.

Default scope: ``lupo-docs/prd/**/*.md`` (recursive glob).

Usage:
  python lupo-scripts/fix_unicode_box_drawing_ascii.py [--path GLOB] [--dry-run] [--check] \\
      [--backup] [--verbose] [--no-recursive] [--extensions EXT1,EXT2,...]
"""

from __future__ import annotations

import argparse
import glob
import os
import shutil
import sys

# Real box-drawing / block elements (U+2500–U+257F subset + double lines).
UNICODE_BOX_MAP = {
    # Corners and junctions (light)
    "┌": "+",
    "┍": "+",
    "┎": "+",
    "┏": "+",
    "┐": "+",
    "┑": "+",
    "┒": "+",
    "┓": "+",
    "└": "+",
    "┕": "+",
    "┖": "+",
    "┗": "+",
    "┘": "+",
    "┙": "+",
    "┚": "+",
    "┛": "+",
    "├": "+",
    "┝": "+",
    "┞": "+",
    "┟": "+",
    "┠": "+",
    "┡": "+",
    "┢": "+",
    "┣": "+",
    "┤": "+",
    "┥": "+",
    "┦": "+",
    "┧": "+",
    "┨": "+",
    "┩": "+",
    "┪": "+",
    "┫": "+",
    "┬": "+",
    "┭": "+",
    "┮": "+",
    "┯": "+",
    "┰": "+",
    "┱": "+",
    "┲": "+",
    "┳": "+",
    "┴": "+",
    "┵": "+",
    "┶": "+",
    "┷": "+",
    "┸": "+",
    "┹": "+",
    "┺": "+",
    "┻": "+",
    "┼": "+",
    "┽": "+",
    "┾": "+",
    "┿": "+",
    "╀": "+",
    "╁": "+",
    "╂": "+",
    "╃": "+",
    "╄": "+",
    "╅": "+",
    "╆": "+",
    "╇": "+",
    "╈": "+",
    "╉": "+",
    "╊": "+",
    "╋": "+",
    # Lines
    "─": "-",
    "━": "-",
    "│": "|",
    "┃": "|",
    "═": "=",
    "║": "|",
}


def _mojibake_latin1_from_char(ch: str) -> str:
    """UTF-8 encoding of *ch* reinterpreted as Latin-1 (multi-byte mojibake)."""
    return ch.encode("utf-8").decode("latin-1")


def _build_mojibake_map() -> dict:
    """Latin-1 mojibake strings for each UNICODE_BOX_MAP key (UTF-8 misread as Latin-1)."""
    out = {}
    for ch, rep in UNICODE_BOX_MAP.items():
        if len(ch) != 1:
            continue
        try:
            out[_mojibake_latin1_from_char(ch)] = rep
        except UnicodeError:
            continue
    return out


MOJIBAKE_MAP = _build_mojibake_map()

# UTF-8 sequences misinterpreted as Latin-1 → intended character (not ASCII).
# Built with bytes -> decode("latin-1") so literals match file content exactly.
def _latin1_mojibake(b: bytes) -> str:
    return b.decode("latin-1")


ADDITIONAL_MOJIBAKE: tuple[tuple[str, str], ...] = (
    # Two-byte UTF-8 for C1 / Latin-1 supplement misread as two chars
    ("\xc2\xa9", "\u00a9"),  # copyright
    ("\xc2\xb0", "\u00b0"),  # degree
    # Three-byte UTF-8 punctuation (common in docs)
    (_latin1_mojibake(bytes((0xE2, 0x80, 0x98))), "\u2018"),  # ‘
    (_latin1_mojibake(bytes((0xE2, 0x80, 0x99))), "\u2019"),  # ’
    (_latin1_mojibake(bytes((0xE2, 0x80, 0x9C))), "\u201c"),  # “
    (_latin1_mojibake(bytes((0xE2, 0x80, 0x9D))), "\u201d"),  # ”
    (_latin1_mojibake(bytes((0xE2, 0x80, 0x9E))), "\u201e"),  # „
    (_latin1_mojibake(bytes((0xE2, 0x80, 0xA2))), "\u2022"),  # bullet
    (_latin1_mojibake(bytes((0xE2, 0x82, 0xAC))), "\u20ac"),  # €
)

DEFAULT_TEXT_EXTENSIONS = frozenset(
    (
        ".md",
        ".txt",
        ".rst",
        ".py",
        ".php",
        ".js",
        ".html",
        ".htm",
        ".css",
        ".json",
        ".yml",
        ".yaml",
        ".sh",
        ".xml",
    )
)

_BINARY_SNIFF = 8192


def _is_probably_binary(path: str) -> bool:
    """Heuristic: NUL in the first chunk, or high ratio of control bytes."""
    try:
        with open(path, "rb") as f:
            chunk = f.read(_BINARY_SNIFF)
    except OSError:
        return True
    if not chunk:
        return False
    if b"\x00" in chunk:
        return True
    ctrl = sum(1 for b in chunk if b < 32 and b not in (9, 10, 13))
    return len(chunk) > 0 and (ctrl / float(len(chunk))) > 0.30


def _extension_allowed(path: str, allowed: frozenset) -> bool:
    _root, ext = os.path.splitext(path)
    return ext.lower() in allowed


def _apply_pairs(
    fixed: str,
    pairs: tuple[tuple[str, str], ...] | dict[str, str],
    label: str,
    log: list[tuple[str, str, str, int]],
) -> tuple[str, int]:
    """Apply (bad, good) replacements; append to *log* when count > 0."""
    total = 0
    items = pairs.items() if isinstance(pairs, dict) else pairs
    for bad, good in items:
        n = fixed.count(bad)
        if n:
            total += n
            fixed = fixed.replace(bad, good)
            log.append((label, bad, good, n))
    return fixed, total


def normalize_box_chars(
    content: str, verbose_log: list[tuple[str, str, str, int]] | None = None
) -> tuple[str, int]:
    """
    Replace box-drawing Unicode and Latin-1 mojibake with ASCII for boxes;
    repair additional UTF-8/Latin-1 mojibake to Unicode.

    Returns (new_content, replacement_count). If *verbose_log* is a list,
    append (category, bad, good, count) for each substitution batch used.
    """
    log = verbose_log if verbose_log is not None else []
    fixed = content
    total = 0
    t, n = _apply_pairs(fixed, UNICODE_BOX_MAP, "box_unicode", log)
    fixed, total = t, total + n
    t, n = _apply_pairs(fixed, MOJIBAKE_MAP, "box_mojibake", log)
    fixed, total = t, total + n
    t, n = _apply_pairs(fixed, ADDITIONAL_MOJIBAKE, "text_mojibake", log)
    fixed, total = t, total + n
    return fixed, total


def process_file(
    path: str,
    dry_run: bool,
    backup: bool,
    verbose: bool,
) -> int:
    """Return 0 = unchanged, 1 = changed (or would change), 2 = error."""
    try:
        with open(path, "r", encoding="utf-8-sig") as f:
            content = f.read()
    except (OSError, UnicodeError) as e:
        print("[ERROR] Failed to read %s: %s" % (path, e))
        return 2

    content = content.replace("\r\n", "\n")
    vlog: list[tuple[str, str, str, int]] = []
    fixed, nrep = normalize_box_chars(content, vlog if verbose else None)

    if fixed == content:
        return 0

    if verbose and vlog:
        print("[VERBOSE] %s:" % path)
        for cat, bad, good, n in vlog:
            print(
                "  %s: %d x %s -> %s"
                % (cat, n, ascii(bad), ascii(good))
            )

    if dry_run:
        if verbose:
            print("[DRY-RUN] would fix %s (%d replacement(s))" % (path, nrep))
        else:
            print("[DRY-RUN] would fix %s" % path)
        return 1

    if backup:
        bak = path + ".bak"
        try:
            shutil.copy2(path, bak)
            if verbose:
                print("[VERBOSE] backup %s" % bak)
        except OSError as e:
            print("[ERROR] Backup failed %s: %s" % (path, e))
            return 2

    try:
        with open(path, "w", encoding="utf-8", newline="\n") as f:
            f.write(fixed)
    except OSError as e:
        print("[ERROR] Failed to write %s: %s" % (path, e))
        return 2

    if verbose:
        print("[FIXED] %s (%d replacement(s))" % (path, nrep))
    else:
        print("[FIXED] %s" % path)
    return 1


def main() -> int:
    parser = argparse.ArgumentParser(
        description="Replace Unicode box drawing / mojibake in text files (stdlib only)."
    )
    parser.add_argument(
        "--path",
        default="lupo-docs/prd/**/*.md",
        help="Glob relative to repo root (use ** for recursion)",
    )
    parser.add_argument(
        "--no-recursive",
        action="store_true",
        help="Disable recursive glob (needed only if pattern has no **)",
    )
    parser.add_argument(
        "--extensions",
        default="",
        help="Comma-separated extensions to include, e.g. .md,.txt (default: built-in text set)",
    )
    parser.add_argument(
        "--include-all-extensions",
        action="store_true",
        help="Process every regular file matched by --path (skip extension filter)",
    )
    parser.add_argument("--dry-run", action="store_true", help="Do not write files")
    parser.add_argument(
        "--check",
        action="store_true",
        help="Exit 1 if any file would change (no writes); for CI",
    )
    parser.add_argument(
        "--backup",
        action="store_true",
        help="Copy each modified file to path.bak before overwrite",
    )
    parser.add_argument(
        "--verbose",
        "-v",
        action="store_true",
        help="Per-file replacement categories and counts",
    )
    args = parser.parse_args()

    if args.extensions.strip():
        allowed = frozenset(
            e.strip().lower() if e.strip().startswith(".") else "." + e.strip().lower()
            for e in args.extensions.split(",")
            if e.strip()
        )
    else:
        allowed = DEFAULT_TEXT_EXTENSIONS

    dry_run = bool(args.dry_run or args.check)
    script_dir = os.path.dirname(os.path.abspath(__file__))
    repo_root = os.path.dirname(script_dir)
    pattern = os.path.join(repo_root, args.path.replace("/", os.sep))
    paths = sorted(
        glob.glob(pattern, recursive=not args.no_recursive)
    )

    stats = {
        "scanned": 0,
        "fixed": 0,
        "unchanged": 0,
        "skip_binary": 0,
        "skip_ext": 0,
        "errors": 0,
    }

    for path in paths:
        if not os.path.isfile(path):
            continue
        stats["scanned"] += 1
        if not args.include_all_extensions and not _extension_allowed(path, allowed):
            stats["skip_ext"] += 1
            if args.verbose:
                print("[SKIP] %s (extension not allowed)" % path)
            continue
        if _is_probably_binary(path):
            stats["skip_binary"] += 1
            if args.verbose:
                print("[SKIP] %s (looks binary)" % path)
            continue
        try:
            code = process_file(path, dry_run, args.backup, args.verbose)
        except Exception as e:
            print("[ERROR] Unexpected failure on %s: %s" % (path, e))
            stats["errors"] += 1
            continue
        if code == 1:
            stats["fixed"] += 1
        elif code == 2:
            stats["errors"] += 1
        else:
            stats["unchanged"] += 1

    label = "would_change" if dry_run else "changed"
    print(
        "\nSummary: scanned=%d %s=%d unchanged=%d skip_binary=%d skip_ext=%d errors=%d"
        % (
            stats["scanned"],
            label,
            stats["fixed"],
            stats["unchanged"],
            stats["skip_binary"],
            stats["skip_ext"],
            stats["errors"],
        )
    )

    if args.check and stats["fixed"] > 0:
        print(
            "[CHECK] FAIL: %d file(s) need mojibake/box fixes (re-run without --check)"
            % stats["fixed"]
        )
        return 1
    if args.check:
        print("[CHECK] OK: no files need fixes")

    return 1 if stats["errors"] else 0


if __name__ == "__main__":
    sys.exit(main())
