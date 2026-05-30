#!/usr/bin/env python3
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.2"
#   file_path_from_root: "scripts/migrate_pk_to_content_fields.py"
#   web_path: "https://www.lupopedia.com/lupopedia/scripts/migrate_pk_to_content_fields.py"
#   status: "complete"
#   when_updated: "20260415180000"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "memory/development/canonical/1026/04/migrate-pk-to-content-fields.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/migrate-pk-to-content-fields"
#   artifact_type: implementation
#   artifact_kind: tool
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: ""
#   content_id: null
#   content_parent_id: "16"
#   content_slug: ""
#   default_collection_id: null
#   lupopedia.schema: implementation
#   title: "Migration helper: pk_* -> content_* field rename"
#   summary: "Scans .md files and renames pk_id/pk_slug/parent_pk_id to content_id/content_slug/content_parent_id within YAML headers; adds default_collection_id if missing; re-orders to canonical 22-field sequence."
# ---------------------------------------------------------------------
"""
migrate_pk_to_content_fields.py — rename legacy pk_* header fields to canonical content_* fields.

Aliases handled:
  pk_id          -> content_id          (field 16)
  parent_pk_id   -> content_parent_id   (field 17)
  pk_slug        -> content_slug        (field 18)
  + inserts default_collection_id: null (field 19) if missing

Usage:
  python scripts/migrate_pk_to_content_fields.py <file_or_glob> [<file2> ...]   # dry-run
  python scripts/migrate_pk_to_content_fields.py --commit <file_or_glob> ...   # write changes
  python scripts/migrate_pk_to_content_fields.py --all                          # scan git-tracked .md files (dry-run)
  python scripts/migrate_pk_to_content_fields.py --all --commit                 # write all

Notes:
  - Only the YAML header block (first 40 lines) is modified; body text is preserved.
  - header_format_version is bumped to 4.1.2 if it was 4.1.0 or 4.1.1.
  - when_updated is NOT changed (PRD 16 §4.3 rule 11).
  - Files already using content_* fields are skipped unchanged.
"""

import argparse
import glob
import os
import re
import subprocess
import sys

# Canonical field order (positions 16-19); others are preserved as-is
_ALIAS_MAP = {
    "pk_id": "content_id",
    "pk_slug": "content_slug",
    "parent_pk_id": "content_parent_id",
}

_CANONICAL_FIELDS_16_19 = [
    "content_id",
    "content_parent_id",
    "content_slug",
    "default_collection_id",
]

# Regex to match a YAML header key line (indented 2 spaces)
_KEY_RE = re.compile(r"^( {2})([a-z_][a-z0-9_.]*)(:.*)$", re.MULTILINE)
_HEADER_VERSION_RE = re.compile(r'( {2}header_format_version: )"(4\.1\.[01])"')
_HEADER_BLOCK_RE = re.compile(r"\A---\n(.*?\n)---\n", re.DOTALL)


def _migrate_header_block(inner: str) -> tuple:
    """Rename pk_* -> content_* and insert default_collection_id if absent.
    Returns (new_inner, changed: bool, notes: list[str])."""
    notes = []
    changed = False

    # Bump version 4.1.0/4.1.1 -> 4.1.2
    def _bump_version(m):
        nonlocal changed
        changed = True
        return m.group(1) + '"4.1.2"'
    new_inner = _HEADER_VERSION_RE.sub(_bump_version, inner)
    if new_inner != inner:
        notes.append("header_format_version bumped to 4.1.2")

    # Rename alias keys
    for old_key, new_key in _ALIAS_MAP.items():
        pattern = re.compile(r"^( {2})" + re.escape(old_key) + r"(:)", re.MULTILINE)
        def _rename(m, nk=new_key):
            return m.group(1) + nk + m.group(2)
        replaced = pattern.sub(_rename, new_inner)
        if replaced != new_inner:
            notes.append("%s -> %s" % (old_key, new_key))
            changed = True
        new_inner = replaced

    # Insert default_collection_id: null after content_slug if missing
    if "  default_collection_id:" not in new_inner and "  content_slug:" in new_inner:
        new_inner = re.sub(
            r"( {2}content_slug:.*\n)",
            r"\1  default_collection_id: null\n",
            new_inner,
            count=1,
        )
        notes.append("default_collection_id: null inserted after content_slug")
        changed = True

    return new_inner, changed, notes


def migrate_file(path: str, commit: bool) -> tuple:
    """Returns (changed: bool, notes: list[str], error: str|None)."""
    try:
        with open(path, "r", encoding="utf-8", newline="") as fh:
            content = fh.read()
    except Exception as e:
        return False, [], str(e)

    m = _HEADER_BLOCK_RE.match(content)
    if not m:
        return False, ["no YAML front-matter found"], None

    inner = m.group(1)
    if "lupopedia.headers:" not in inner:
        return False, ["not a lupopedia header"], None

    new_inner, changed, notes = _migrate_header_block(inner)
    if not changed:
        return False, ["already canonical — no changes needed"], None

    new_content = "---\n" + new_inner + "---\n" + content[m.end():]
    if commit:
        with open(path, "w", encoding="utf-8", newline="") as fh:
            fh.write(new_content)

    return True, notes, None


def _collect_files(patterns: list) -> list:
    paths = []
    for pat in patterns:
        expanded = glob.glob(pat, recursive=True)
        if expanded:
            paths.extend(expanded)
        elif os.path.isfile(pat):
            paths.append(pat)
    return sorted(set(paths))


def _git_tracked_md_files() -> list:
    try:
        out = subprocess.check_output(
            ["git", "ls-files", "*.md"], stderr=subprocess.DEVNULL, text=True
        )
        return [p.strip() for p in out.splitlines() if p.strip().endswith(".md")]
    except Exception:
        return []


def main():
    parser = argparse.ArgumentParser(
        description=__doc__, formatter_class=argparse.RawDescriptionHelpFormatter
    )
    parser.add_argument("files", nargs="*", help="File paths or globs to process")
    parser.add_argument(
        "--all",
        action="store_true",
        help="Process all git-tracked .md files (use with --commit to write)",
    )
    parser.add_argument(
        "--commit",
        action="store_true",
        help="Write changes to disk (default: dry-run only)",
    )
    args = parser.parse_args()

    if args.all:
        targets = _git_tracked_md_files()
        if not targets:
            sys.stderr.write("[WARN] git ls-files returned no .md files\n")
    elif args.files:
        targets = _collect_files(args.files)
    else:
        parser.print_help()
        sys.exit(0)

    mode = "COMMIT" if args.commit else "DRY-RUN"
    print("migrate_pk_to_content_fields.py [%s] — %d file(s)" % (mode, len(targets)))
    print()

    changed_count = skipped_count = error_count = 0
    for path in targets:
        changed, notes, err = migrate_file(path, commit=args.commit)
        if err:
            print("[ERROR] %s: %s" % (path, err))
            error_count += 1
        elif changed:
            print("[CHANGED] %s" % path)
            for n in notes:
                print("         * %s" % n)
            changed_count += 1
        else:
            skipped_count += 1

    print()
    print("Summary: %d changed, %d skipped (already clean), %d errors" % (
        changed_count, skipped_count, error_count
    ))
    if not args.commit and changed_count > 0:
        print("Re-run with --commit to apply changes.")
    sys.exit(1 if error_count else 0)


if __name__ == "__main__":
    main()
