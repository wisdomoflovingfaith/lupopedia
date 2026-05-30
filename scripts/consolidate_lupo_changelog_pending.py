#!/usr/bin/env python3
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.3"
#   file_path_from_root: "scripts/consolidate_lupo_changelog_pending.py"
#   web_path: "https://www.lupopedia.com/lupopedia/scripts/consolidate_lupo_changelog_pending.py"
#   status: "active"
#   when_updated: "20260417212800"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "memory/development/canonical/1026/04/consolidate-changelog-pending.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/consolidate-changelog-pending"
#   artifact_type: documentation
#   artifact_kind: guide
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: ""
#   content_id: null
#   content_parent_id: null
#   content_slug: "consolidate-changelog-pending"
#   default_collection_id: null
#   lupopedia.schema: documentation
#   title: "Consolidate changelog-pending JSON into version changelog.md"
#   summary: "Reads CHANGELOG_BUFFER_ARCHITECTURE JSON files from changelog-pending, appends Entry blocks to a target changelog, archives merged files."
# ---------------------------------------------------------------------
"""
consolidate_lupo_changelog_pending.py

Merges mandatory JSON buffer entries (see docs/doctrine/CHANGELOG_BUFFER_ARCHITECTURE.md)
from changelog-pending/ into a version changelog Markdown file (default:
docs/versions/4.1.3/changelog.md).

Dedup: each merged fragment embeds <!-- changelog-merged: FILENAME -->; re-runs skip
files whose marker already appears in the target.

Legacy Markdown fragment merge (versioned buffer/ folder) remains in merge_changelog_buffer.py.

Usage:
  python scripts/consolidate_lupo_changelog_pending.py
  python scripts/consolidate_lupo_changelog_pending.py --commit
  python scripts/consolidate_lupo_changelog_pending.py --target docs/versions/4.1.3/changelog.md --commit
"""
from __future__ import print_function

import argparse
import json
import os
import re
import shutil
import sys

MERGED_COMMENT_RE = re.compile(r"<!--\s*changelog-merged:\s*([^>]+?)\s*-->")
YMDHIS14 = re.compile(r"^\d{14}$")


def repo_root_from_here():
    return os.path.dirname(os.path.dirname(os.path.abspath(__file__)))


def read_anchor_utc(repo):
    anchor = os.path.join(repo, "bin", "temporal_anchor.json")
    if not os.path.isfile(anchor):
        return None
    try:
        with open(anchor, "r", encoding="utf-8") as f:
            data = json.load(f)
        u = data.get("current_utc")
        if u is not None and YMDHIS14.match(str(u).strip()):
            return str(u).strip()
    except (ValueError, OSError, IOError, TypeError):
        pass
    return None


def changelog_stub(target_rel, when_updated):
    """Initial body for docs/versions/4.1.3/changelog.md when created by this tool."""
    return """---
lupopedia.headers:
  header_format_version: "4.1.3"
  file_path_from_root: "%(fp)s"
  web_path: "https://www.lupopedia.com/lupopedia/%(fp)s"
  status: "active"
  when_updated: "%(wu)s"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "memory/development/canonical/1026/04/version-4-1-3-changelog.toon"
  atoms_toon: null
  transcript_jsonl: "0/development/4_1_3_changelog_buffer"
  artifact_type: changelog
  artifact_kind: version_specific
  channel_key: "development"
  federation_node_id: 0
  thread_id: ""
  content_id: null
  content_parent_id: null
  content_slug: "version-4-1-3-changelog"
  default_collection_id: null
  lupopedia.schema: changelog
  title: "Lupopedia 4.1.3 changelog (buffer-consolidated)"
  summary: "Entries appended from changelog-pending JSON by consolidate_lupo_changelog_pending.py."
---
# Lupopedia 4.1.3 -- Changelog

Consolidated **buffer** entries (WHO / UTC / WHAT) from `changelog-pending/*.json` per
CHANGELOG_BUFFER_ARCHITECTURE.md. Each entry includes a hidden merge marker for idempotent re-runs.

""" % {"fp": target_rel.replace("\\", "/"), "wu": when_updated}


def parse_pending(repo, pending_dir):
    out = []
    try:
        names = sorted(os.listdir(pending_dir))
    except OSError as e:
        print("[ERROR] Cannot list %s: %s" % (pending_dir, e))
        return out
    for name in names:
        if not name.endswith(".json"):
            continue
        path = os.path.join(pending_dir, name)
        try:
            with open(path, "r", encoding="utf-8") as f:
                raw = f.read()
            data = json.loads(raw)
        except (ValueError, OSError, IOError) as e:
            print("[WARN] Skip %s (invalid JSON or read error): %s" % (name, e))
            continue
        if not isinstance(data, dict):
            print("[WARN] Skip %s (root not object)" % name)
            continue
        ts = data.get("timestamp")
        agent = data.get("agent_id")
        summary = data.get("summary")
        if not ts or not YMDHIS14.match(str(ts).strip()):
            print("[WARN] Skip %s (missing or invalid timestamp)" % name)
            continue
        if agent is None or str(agent).strip() == "":
            print("[WARN] Skip %s (missing agent_id)" % name)
            continue
        if summary is None or str(summary).strip() == "":
            print("[WARN] Skip %s (missing summary)" % name)
            continue
        files_changed = data.get("files_changed")
        if files_changed is None:
            files_changed = []
        if not isinstance(files_changed, list):
            files_changed = [str(files_changed)]
        out.append(
            {
                "filename": name,
                "path": path,
                "timestamp": str(ts).strip(),
                "agent_id": str(agent).strip(),
                "channel": str(data.get("channel") or "").strip(),
                "thread": str(data.get("thread") or "").strip(),
                "summary": str(summary).strip(),
                "files_changed": [str(x) for x in files_changed],
                "open_questions": data.get("open_questions"),
                "handoff_to": data.get("handoff_to"),
                "related_toons": data.get("related_toons"),
            }
        )
    out.sort(key=lambda x: x["timestamp"])
    return out


def already_merged(target_text, filename):
    marker = "<!-- changelog-merged: %s -->" % filename
    return marker in target_text


def format_entry(row):
    lines = []
    lines.append("## Entry")
    lines.append("<!-- changelog-merged: %s -->" % row["filename"])
    lines.append("- **WHO:** %s" % row["agent_id"])
    ch = row["channel"] or "(unspecified)"
    th = row["thread"] or "(unspecified)"
    lines.append("- **CHANNEL / THREAD:** %s / %s" % (ch, th))
    lines.append("- **UTC (BIGINT):** `%s`" % row["timestamp"])
    lines.append("- **WHAT:**")
    lines.append("  - %s" % row["summary"])
    if row["files_changed"]:
        fc = ", ".join("`%s`" % p.replace("\\", "/") for p in row["files_changed"])
        lines.append("  - Files: %s" % fc)
    rt = row.get("related_toons")
    if isinstance(rt, list) and rt:
        lines.append("  - Related toons:")
        for t in rt:
            if str(t).strip():
                lines.append("    - `%s`" % str(t).strip().replace("\\", "/"))
    oq = row.get("open_questions")
    if isinstance(oq, list) and oq:
        lines.append("  - Open questions: %s" % ", ".join(str(x) for x in oq))
    if row.get("handoff_to"):
        lines.append("  - Handoff to: `%s`" % str(row["handoff_to"]).strip())
    lines.append("")
    return "\n".join(lines)


def update_when_updated_md(text, new_utc):
    lines = text.splitlines(True)
    for i, line in enumerate(lines):
        if line.startswith("  when_updated:"):
            lines[i] = '  when_updated: "%s"\n' % new_utc
            break
    return "".join(lines)


def ensure_archive_directory(archive_abs, repo_root):
    """
    CHANGELOG_BUFFER_ARCHITECTURE expects a directory at changelog-archive/.
    If a legacy single file occupied that path, rename it so makedirs can succeed.
    """
    if os.path.isfile(archive_abs):
        legacy = archive_abs + ".legacy-fragment.md"
        n = 0
        while os.path.exists(legacy):
            n += 1
            legacy = archive_abs + (".legacy-fragment-%d.md" % n)
        shutil.move(archive_abs, legacy)
        print(
            "[NOTE] Renamed blocking file at archive path to %s (directory required)."
            % os.path.relpath(legacy, repo_root)
        )
    if not os.path.isdir(archive_abs):
        os.makedirs(archive_abs)


def reconcile_orphan_pending(target_text, pending_abs, archive_abs, repo_root, do_commit):
    """
    Move pending JSON into archive when changelog already contains its merge marker
    (recovery after a failed run that wrote the changelog but did not archive).
    """
    if not do_commit or not os.path.isdir(pending_abs):
        return 0
    ensure_archive_directory(archive_abs, repo_root)
    moved = 0
    try:
        names = os.listdir(pending_abs)
    except OSError:
        return 0
    for name in names:
        if not name.endswith(".json"):
            continue
        if not already_merged(target_text, name):
            continue
        src = os.path.join(pending_abs, name)
        if not os.path.isfile(src):
            continue
        dest = os.path.join(archive_abs, name)
        if os.path.exists(dest):
            base = name[:-5] if name.endswith(".json") else name
            dest = os.path.join(archive_abs, "%s.reconcile-%d.json" % (base, moved))
        try:
            shutil.move(src, dest)
            moved += 1
            print("[RECONCILE] Archived orphan %s -> %s" % (name, os.path.relpath(dest, repo_root)))
        except OSError as e:
            print("[WARN] Could not archive orphan %s: %s" % (name, e))
    return moved


def main():
    parser = argparse.ArgumentParser(
        description="Merge changelog-pending/*.json into a version changelog.md"
    )
    parser.add_argument(
        "--target",
        default="docs/versions/4.1.3/changelog.md",
        help="Repo-relative path to changelog Markdown (default: 4.1.3)",
    )
    parser.add_argument(
        "--pending-dir",
        default="changelog-pending",
        help="Repo-relative pending JSON directory",
    )
    parser.add_argument(
        "--archive-dir",
        default="changelog-archive",
        help="Repo-relative archive directory for merged JSON",
    )
    parser.add_argument(
        "--commit",
        action="store_true",
        help="Append to changelog and move JSON files to archive",
    )
    args = parser.parse_args()

    repo = repo_root_from_here()
    target_abs = os.path.normpath(os.path.join(repo, args.target.replace("/", os.sep)))
    pending_abs = os.path.normpath(os.path.join(repo, args.pending_dir.replace("/", os.sep)))
    archive_abs = os.path.normpath(os.path.join(repo, args.archive_dir.replace("/", os.sep)))

    if not os.path.isdir(pending_abs):
        print("[ERROR] Pending directory not found: %s" % pending_abs)
        sys.exit(1)

    new_utc = read_anchor_utc(repo)
    if not new_utc:
        print("[ERROR] Run python bin/tick.py first (temporal_anchor.json missing or invalid).")
        sys.exit(1)

    rows = parse_pending(repo, pending_abs)
    if not rows:
        print("No valid pending JSON entries in %s" % pending_abs)
        return

    target_rel = args.target.replace("\\", "/")
    if os.path.isfile(target_abs):
        with open(target_abs, "r", encoding="utf-8") as f:
            existing = f.read()
    else:
        existing = ""

    to_merge = [r for r in rows if not already_merged(existing, r["filename"])]
    if not to_merge:
        print("No new pending entries (all filenames already merged in target).")
        if args.commit and os.path.isfile(target_abs):
            with open(target_abs, "r", encoding="utf-8") as f:
                ft = f.read()
            ensure_archive_directory(archive_abs, repo)
            n_rec = reconcile_orphan_pending(ft, pending_abs, archive_abs, repo, True)
            if n_rec:
                print("[DONE] Reconciled %d orphan pending file(s)." % n_rec)
            else:
                print("(No orphan pending files to archive.)")
        elif args.commit and not os.path.isfile(target_abs):
            print("[WARN] --commit but target changelog is missing; nothing to reconcile.")
        return

    blocks = [format_entry(r) for r in to_merge]
    append_text = "\n".join(blocks)
    if existing.strip():
        if not existing.endswith("\n"):
            existing += "\n"
        new_body = existing + "\n" + append_text
    else:
        new_body = changelog_stub(target_rel, new_utc) + append_text

    new_body = update_when_updated_md(new_body, new_utc)

    print("Pending entries to merge: %d" % len(to_merge))
    for r in to_merge:
        print("  - %s  %s  %s" % (r["timestamp"], r["filename"], r["summary"][:70]))

    if not args.commit:
        print("\nDRY RUN: pass --commit to write %s and archive JSON files." % args.target)
        return

    target_dir = os.path.dirname(target_abs)
    if not os.path.isdir(target_dir):
        os.makedirs(target_dir)

    ensure_archive_directory(archive_abs, repo)

    with open(target_abs, "w", encoding="utf-8") as f:
        f.write(new_body)

    for r in to_merge:
        dest = os.path.join(archive_abs, r["filename"])
        if os.path.exists(dest):
            dest = os.path.join(archive_abs, "%s_%s" % (new_utc, r["filename"]))
        try:
            shutil.move(r["path"], dest)
            print("[ARCHIVED] %s -> %s" % (r["filename"], os.path.relpath(dest, repo)))
        except OSError as e:
            print("[ERROR] Could not archive %s: %s" % (r["filename"], e))

    with open(target_abs, "r", encoding="utf-8") as f:
        final_text = f.read()
    n_rec = reconcile_orphan_pending(final_text, pending_abs, archive_abs, repo, True)
    if n_rec:
        print("[DONE] Reconciled %d orphan pending file(s)." % n_rec)

    print("[DONE] Updated %s (when_updated=%s)" % (args.target, new_utc))


if __name__ == "__main__":
    main()
