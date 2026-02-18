#!/usr/bin/env python3
"""
md_flip_ingest.py — Discover .md files, read FLIP headers, output SQL for seed.

Scans the repo for .md files. For each file, reads the FLIP header (or infers
minimal metadata). Produces deterministic SQL inserts for lupo_contents,
lupo_unified_registry, and lupo_edges for seed_lupopedia.sql.

Channel mapping:
- docs/doctrine/ → channels 0 (System Kernel) and 51 (Doctrine Council)
- Other .md → channel 0 only

HYBRID mode: Required fields only; optional fields only when inferable.
Output is idempotent (ON DUPLICATE KEY UPDATE).

Usage:
  python tools/md_flip_ingest.py [--batch 0] [--limit 25] [--repo-root PATH]
  --batch N: process batch N (0-based), each batch has --limit files
  --limit N: files per batch (default 25)
  --repo-root: repo root path (default: script dir parent)
"""

from __future__ import print_function

import argparse
import os
import re
import sys

# Default repo root: parent of tools/
_SCRIPT_DIR = os.path.dirname(os.path.abspath(__file__))
_DEFAULT_REPO_ROOT = os.path.dirname(_SCRIPT_DIR)


def _normalize_path(path, repo_root):
    """Normalize path to forward-slash, relative to repo root."""
    path = os.path.normpath(path)
    path = path.replace(os.sep, "/")
    if path.startswith("./"):
        path = path[2:]
    return path


def discover_md_files(repo_root, limit=None, offset=0, doctrine_only=False):
    """
    Discover .md files sorted by path. Returns list of (rel_path, is_doctrine).
    If doctrine_only=True, only include docs/doctrine/ files.
    """
    out = []
    for root, dirs, files in os.walk(repo_root):
        # Skip node_modules, .git, etc.
        dirs[:] = [d for d in dirs if d not in (".git", "node_modules", "__pycache__", ".cursor")]
        for f in files:
            if not f.lower().endswith(".md"):
                continue
            full = os.path.join(root, f)
            rel = os.path.relpath(full, repo_root)
            rel = _normalize_path(rel, repo_root)
            is_doctrine = rel.startswith("docs/doctrine/")
            if doctrine_only and not is_doctrine:
                continue
            # Exclude files already in seed (content 2001, 2002)
            if rel in ("docs/doctrine/FLIP/FLIPPING_FILE_LEXA_LILITH.md", "docs/doctrine/FLIP/FLIP_DOCTRINE.md"):
                continue
            out.append((rel, is_doctrine))
    out.sort(key=lambda x: x[0])
    if offset:
        out = out[offset:]
    if limit is not None:
        out = out[:limit]
    return out


def parse_flip_header(filepath):
    """
    Read file and parse FLIP header (YAML between --- delimiters).
    Returns dict with file_path_from_root, file.last_modified_system_version,
    file.last_modified_utc (or defaults). HYBRID: only infer when present.
    """
    result = {
        "file_path_from_root": filepath,
        "file_last_modified_system_version": "4.0.15",
        "file_last_modified_utc": 20260217230000,
    }
    try:
        with open(filepath, "r", encoding="utf-8", errors="replace") as f:
            content = f.read(4096)
    except (IOError, OSError):
        return result

    # Look for --- ... --- block
    match = re.match(r"^---\s*\n(.*?)\n---", content, re.DOTALL)
    if not match:
        return result

    block = match.group(1)
    for line in block.split("\n"):
        line = line.strip()
        if not line or line.startswith("#"):
            continue
        if ":" in line:
            k, v = line.split(":", 1)
            k = k.strip()
            v = v.strip().strip('"').strip("'")
            if k == "file_path_from_root" and v:
                result["file_path_from_root"] = v
            elif k == "file.last_modified_system_version" and v:
                result["file_last_modified_system_version"] = v
            elif k == "file.last_modified_utc" and v:
                try:
                    result["file_last_modified_utc"] = int(v)
                except ValueError:
                    pass
    return result


def slug_from_path(path):
    """Generate unique slug from path for lupo_contents."""
    s = path.replace("/", "-").replace(".md", "").lower()
    s = re.sub(r"[^a-z0-9\-]", "", s)
    return s[:200] if s else "md"


def escape_sql(s):
    """Escape single quotes for SQL."""
    if s is None:
        return "NULL"
    return "'" + str(s).replace("\\", "\\\\").replace("'", "''") + "'"


def generate_sql(records, content_id_start, edge_id_start, unified_registry_id_start, now, node_id):
    """
    Generate SQL for lupo_contents, lupo_unified_registry, lupo_edges.
    """
    lines = []
    lines.append("-- md_flip_ingest: batch of .md files")
    lines.append("")
    edge_counter = [0]  # mutable so inner lambda can update

    for i, (rel_path, is_doctrine) in enumerate(records):
        cid = content_id_start + i
        slug = slug_from_path(rel_path)
        if len(slug) < 4:
            slug = "md-" + str(cid)

        header = {}
        full_path = os.path.join(_DEFAULT_REPO_ROOT, rel_path.replace("/", os.sep))
        if os.path.isfile(full_path):
            header = parse_flip_header(full_path)
        else:
            header = {
                "file_path_from_root": rel_path,
                "file_last_modified_system_version": "4.0.15",
                "file_last_modified_utc": now,
            }

        ver = header.get("file_last_modified_system_version", "4.0.15")
        utc = header.get("file_last_modified_utc", now)
        path_val = escape_sql(rel_path)
        title = rel_path.split("/")[-1].replace(".md", "").replace("-", " ").replace("_", " ")[:200]

        lines.append("INSERT INTO lupo_contents (")
        lines.append("    content_id, content_parent_id, federation_node_id, department_id, actor_id, title, slug, custom_path, description, seo_keywords, body,")
        lines.append("    content_type, format, content_url, default_collection_id, source_url, source_title, is_template, status, visibility, view_count, share_count,")
        lines.append("    created_ymdhis, utc_cycle, triage_status, triage_notes, updated_ymdhis, is_deleted, is_active, deleted_ymdhis, content_sections, version_number,")
        lines.append("    file_path_from_root, file_last_modified_system_version, file_last_modified_utc, tags, dialog_notes")
        lines.append(") VALUES (")
        lines.append("    {cid}, NULL, {node_id}, NULL, NULL, {title}, {slug}, NULL, NULL, NULL, NULL,".format(cid=cid, node_id=node_id, title=escape_sql(title), slug=escape_sql(slug)))
        lines.append("    'article', 'markdown', NULL, 0, NULL, NULL, 0, 'published', 'public', 0, 0,")
        lines.append("    {now}, 'seed', 'untriaged', NULL, {now}, 0, 1, NULL, NULL, 1,".format(now=now))
        lines.append("    {path}, {ver}, {utc}, NULL, NULL".format(path=path_val, ver=escape_sql(str(ver)), utc=utc))
        lines.append(") ON DUPLICATE KEY UPDATE file_path_from_root = VALUES(file_path_from_root), file_last_modified_system_version = VALUES(file_last_modified_system_version), file_last_modified_utc = VALUES(file_last_modified_utc), title = VALUES(title), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;")
        lines.append("")

        urid = unified_registry_id_start + i
        lines.append("INSERT INTO lupo_unified_registry (unified_registry_id, entity_type, entity_index, entity_key, entity_name, entity_table, federation_node_id, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, is_active, is_kernel, metadata_json)")
        lines.append("VALUES ({urid}, 'content', {cid}, {path}, {title}, 'lupo_contents', 1, {now}, {now}, 0, NULL, 1, 0, NULL)".format(urid=urid, cid=cid, path=path_val, title=escape_sql(title), now=now))
        lines.append("ON DUPLICATE KEY UPDATE entity_key = VALUES(entity_key), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, is_active = 1;")
        lines.append("")

        channels = [0, 51] if is_doctrine else [0]
        for ch in channels:
            eid = edge_id_start + edge_counter[0]
            edge_counter[0] += 1
            ch_key = "system/kernel" if ch == 0 else "51"
            lines.append("INSERT INTO lupo_edges (edge_id, left_object_type, left_object_id, right_object_type, right_object_id, edge_type, channel_id, channel_key, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)")
            lines.append("VALUES ({eid}, 'channel', {ch}, 'content', {cid}, 'HAS_CONTENT', {ch}, {ch_key}, 0, 0, NULL, 0, 0, {now}, {now})".format(eid=eid, ch=ch, cid=cid, ch_key=escape_sql(ch_key), now=now))
            lines.append("ON DUPLICATE KEY UPDATE left_object_id = VALUES(left_object_id), right_object_id = VALUES(right_object_id), edge_type = VALUES(edge_type), updated_ymdhis = VALUES(updated_ymdhis), is_deleted = 0, deleted_ymdhis = 0;")
            lines.append("")

    return "\n".join(lines)


def main():
    ap = argparse.ArgumentParser(description="Discover .md files and output seed SQL")
    ap.add_argument("--batch", type=int, default=0, help="Batch number (0-based)")
    ap.add_argument("--limit", type=int, default=25, help="Files per batch")
    ap.add_argument("--doctrine-only", action="store_true", help="Only docs/doctrine/ files")
    ap.add_argument("--repo-root", default=_DEFAULT_REPO_ROOT, help="Repo root path")
    ap.add_argument("--content-id-start", type=int, default=5000, help="Starting content_id")
    ap.add_argument("--edge-id-start", type=int, default=910000, help="Starting edge_id")
    ap.add_argument("--registry-id-start", type=int, default=9050000, help="Starting unified_registry_id")
    ap.add_argument("--now", type=int, default=20260217230000, help="Seed timestamp YmdHis")
    ap.add_argument("--seed-mode", action="store_true", help="Output @now for timestamps (for inline seed use)")
    ap.add_argument("--output", "-o", help="Write SQL to file (UTF-8) instead of stdout")
    args = ap.parse_args()

    repo_root = os.path.abspath(args.repo_root)
    if not os.path.isdir(repo_root):
        print("Error: repo root not found: {}".format(repo_root), file=sys.stderr)
        sys.exit(1)

    offset = args.batch * args.limit
    records = discover_md_files(repo_root, limit=args.limit, offset=offset, doctrine_only=args.doctrine_only)

    if not records:
        print("-- No .md files in batch {}".format(args.batch), file=sys.stderr)
        return

    # Edge IDs: doctrine gets 2 edges each, non-doctrine gets 1
    edge_offset = 0
    for _, is_doctrine in records:
        if is_doctrine:
            edge_offset += 2
        else:
            edge_offset += 1

    now_val = "@now" if args.seed_mode else args.now
    sql = generate_sql(
        records,
        args.content_id_start + offset,
        args.edge_id_start + sum(2 if isd else 1 for isd, _ in [(r[1], None) for r in records[:offset]]),
        args.registry_id_start + offset,
        now_val,
        1,
    )
    if args.output:
        with open(args.output, "w", encoding="utf-8") as f:
            f.write(sql)
    else:
        print(sql)


if __name__ == "__main__":
    main()
