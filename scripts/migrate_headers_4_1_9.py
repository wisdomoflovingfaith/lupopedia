#!/usr/bin/env python3
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.9"
#   path_from_lupopedia_root: scripts/migrate_headers_4_1_9.py
#   web_path: https://www.lupopedia.com/lupopedia/scripts/migrate_headers_4_1_9.py
#   status: complete
#   when_updated: "20260523042341"
#   trust_tier: canonical
#   questions_toon: null
#   memory_toon: null
#   atoms_toon: null
#   transcript_jsonl: 0/development/migrate-headers-4-1-9
#   artifact_type: implementation
#   artifact_kind: tool
#   channel_key: development
#   federation_node_id: 0
#   thread_key: ""
#   lupopedia.schema: implementation
#   prd_cluster: 00_A_16_C
#   title: Migrate LUPOPEDIA headers to 4.1.9 (22-field envelope)
#   summary: "Adds edges_toon, channel_index, source_timestamp; removes deprecated identity fields"
#   edges_toon: null
#   channel_index: lupopedia
#   source_timestamp: null
# ---------------------------------------------------------------------
"""
Migrate in-repo headers to header_format_version 4.1.9 (22 canonical fields).

Repo-native defaults (unless --external):
  edges_toon: null
  channel_index: lupopedia
  source_timestamp: null

External / imported (--external or --channel-index != lupopedia):
  Requires --channel-index, --source-timestamp (ISO 8601), generates edges_toon when absent.

Removes deprecated keys: content_id, content_parent_id, default_collection_id, content_slug,
pk_*, prd_id, prd_slug, parent_prd.

Usage:
  python scripts/migrate_headers_4_1_9.py path/to/file.md
  python scripts/migrate_headers_4_1_9.py docs/doctrine --dry-run
  python scripts/migrate_headers_4_1_9.py imports/foo.md --external --channel-index patreon \\
      --source-timestamp 2026-01-15T12:00:00Z
"""

from __future__ import annotations

import argparse
import os
import re
import sys

_SCRIPTS = os.path.dirname(os.path.abspath(__file__))
_REPO = os.path.dirname(_SCRIPTS)
sys.path.insert(0, os.path.join(_SCRIPTS, "lib"))

from lupopedia_markdown_header_peel import peel_leading_lupopedia_yaml_blocks
from header_spec_v3_1 import (
    V4_HEADER_KEYS_ORDERED,
    REMOVED_HEADER_FIELDS_V419,
    emit_markdown_inner_from_header_dict,
    emit_python_header_block_lines_from_header_dict,
    merge_legacy_header_keys,
    build_edges_toon_path,
    line_key_to_canonical,
)

IMPORT_CHANNEL_HINTS = (
    ("patreon", "patreon"),
    ("facebook", "facebook"),
    ("blog", "blog"),
    ("website", "website"),
    ("imported", "imported"),
    ("external", "external"),
)


def _repo_rel(path: str) -> str:
    ap = os.path.normpath(os.path.abspath(path))
    root = os.path.normpath(_REPO)
    rel = os.path.relpath(ap, root).replace("\\", "/")
    if rel.startswith(".."):
        raise ValueError("path outside repo: %s" % path)
    return rel


def _strip_yaml_scalar(val) -> str:
    if val is None:
        return ""
    t = str(val).strip()
    while len(t) >= 2 and (
        (t.startswith('"') and t.endswith('"')) or (t.startswith("'") and t.endswith("'"))
    ):
        t = t[1:-1].strip()
    return t


def _slug_from_path(rel: str) -> str:
    stem = os.path.splitext(os.path.basename(rel))[0]
    s = stem.lower().replace("_", "-")
    s = re.sub(r"[^a-z0-9-]+", "-", s)
    s = re.sub(r"-+", "-", s).strip("-")
    return s or "artifact"


def _infer_channel_index_from_path(rel: str) -> str:
    low = rel.lower().replace("\\", "/")
    for needle, idx in IMPORT_CHANNEL_HINTS:
        if needle in low:
            return idx
    return "lupopedia"


def _parse_yaml_header_mapping(yaml_text: str) -> dict:
    hdr = {}
    for line in yaml_text.splitlines():
        if not line.strip() or line.strip() == "lupopedia.headers:":
            continue
        m = re.match(r"^\s*([A-Za-z0-9_.]+):\s*(.*)$", line)
        if not m:
            continue
        key = line_key_to_canonical(m.group(1))
        raw = m.group(2).strip()
        if raw in ("null", "~", ""):
            val = None
        elif raw == '""':
            val = ""
        elif (raw.startswith('"') and raw.endswith('"')) or (
            raw.startswith("'") and raw.endswith("'")
        ):
            val = raw[1:-1]
        elif raw.isdigit():
            val = int(raw)
        else:
            val = raw
        hdr[key] = val
    return hdr


def _parse_hash_header(lines: list) -> dict:
    hdr = {}
    for line in lines:
        s = line.strip()
        if not s.startswith("#"):
            continue
        s = s.lstrip("#").strip()
        if s == "lupopedia.headers:" or s.startswith("-----"):
            continue
        m = re.match(r"^([A-Za-z0-9_.]+):\s*(.*)$", s)
        if not m:
            continue
        key = line_key_to_canonical(m.group(1))
        raw = m.group(2).strip()
        if raw in ("null", "~"):
            val = None
        elif raw == '""':
            val = ""
        elif (raw.startswith('"') and raw.endswith('"')) or (
            raw.startswith("'") and raw.endswith("'")
        ):
            val = raw[1:-1]
        elif raw.isdigit():
            val = int(raw)
        else:
            val = raw
        hdr[key] = val
    return hdr


def extract_header_and_body(content: str, ext: str):
    if ext == ".md":
        peeled_blocks, body = peel_leading_lupopedia_yaml_blocks(content)
        if not peeled_blocks:
            return None, content
        yaml_text = peeled_blocks[0] if isinstance(peeled_blocks, list) else peeled_blocks
        return _parse_yaml_header_mapping(yaml_text), body
    lines = content.splitlines(True)
    if ext == ".py":
        start = 0
        if lines and lines[0].lstrip("\ufeff").startswith("#!"):
            start = 1
        grid = []
        i = start
        while i < len(lines) and lines[i].lstrip().startswith("#"):
            grid.append(lines[i])
            if lines[i].strip().startswith("# -----") and len(grid) > 2:
                break
            i += 1
        if not grid:
            return None, content
        hdr = _parse_hash_header(grid)
        return hdr, "".join(lines[i + 1 :])
    if ext == ".php":
        php_i = -1
        for j, ln in enumerate(lines):
            if ln.lstrip("\ufeff").strip().startswith("<?php"):
                php_i = j
                break
        if php_i < 0:
            return None, content
        grid = []
        i = php_i + 1
        while i < len(lines) and lines[i].lstrip().startswith("#"):
            grid.append(lines[i])
            if lines[i].strip().startswith("# -----") and len(grid) > 2:
                break
            i += 1
        if not grid:
            return None, content
        return _parse_hash_header(grid), lines[0] + "".join(lines[i:])
    return None, content


def migrate_header_dict(
    hdr: dict,
    rel: str,
    *,
    external: bool,
    channel_index: str,
    source_timestamp: str,
    edges_toon: str,
) -> dict:
    h = merge_legacy_header_keys(dict(hdr))
    for bad in REMOVED_HEADER_FIELDS_V419:
        h.pop(bad, None)
    if "file_path_from_root" in h and "path_from_lupopedia_root" not in h:
        h["path_from_lupopedia_root"] = h.pop("file_path_from_root")
    h["header_format_version"] = "4.1.9"
    h["path_from_lupopedia_root"] = h.get("path_from_lupopedia_root") or rel
    slug = _slug_from_path(rel)
    ci = (channel_index or "").strip() or _infer_channel_index_from_path(rel)
    if external:
        ci = (channel_index or "").strip() or ci
    if ci == "lupopedia" and not external:
        ci = "lupopedia"
    h["channel_index"] = ci
    wu = _strip_yaml_scalar(h.get("when_updated")) or "20260101000000"
    if len(wu) < 14:
        wu = "20260101000000"
    tt = str(h.get("trust_tier") or "canonical").strip().lower()
    ck = str(h.get("channel_key") or "development").strip()
    tk = str(h.get("thread_key", h.get("thread_id", "")) or "").strip()
    if h.get("thread_key") is None and h.get("thread_id") is None:
        tk = ""
    if ci != "lupopedia":
        if not (source_timestamp or "").strip():
            raise ValueError(
                "external/imported artifact requires --source-timestamp (ISO 8601)"
            )
        h["source_timestamp"] = source_timestamp.strip()
        h["edges_toon"] = (edges_toon or "").strip() or build_edges_toon_path(
            ck, tk, slug, wu, tt
        )
    else:
        h["source_timestamp"] = (source_timestamp or "").strip() or None
        h["edges_toon"] = (edges_toon or "").strip() or None
    if "prd_cluster" not in h or h["prd_cluster"] is None:
        h["prd_cluster"] = ""
    h["title"] = _strip_yaml_scalar(h.get("title"))
    h["summary"] = _strip_yaml_scalar(h.get("summary"))
    h["when_updated"] = wu
    h["thread_key"] = tk
    out = {}
    for k in V4_HEADER_KEYS_ORDERED:
        if k in h:
            out[k] = h[k]
        elif k in ("edges_toon", "source_timestamp"):
            out[k] = None
        elif k == "channel_index":
            out[k] = ci
        elif k in ("thread_key", "title", "status", "summary", "prd_cluster"):
            out[k] = h.get(k, "")
        else:
            out[k] = None
    return out


def write_file(path: str, hdr: dict, body: str) -> None:
    ext = os.path.splitext(path)[1].lower()
    if ext == ".md":
        inner = emit_markdown_inner_from_header_dict(hdr)
        new_content = "---\n" + inner.rstrip("\n") + "\n---\n" + body.lstrip("\n\r")
    elif ext == ".py":
        grid = emit_python_header_block_lines_from_header_dict(hdr)
        lines = body.splitlines(True)
        shebang = ""
        rest = body
        if lines and lines[0].lstrip("\ufeff").startswith("#!"):
            shebang = lines[0]
            rest = "".join(lines[1:])
        block = "\n".join(grid) + "\n"
        new_content = shebang + block + rest.lstrip("\n\r")
    elif ext == ".php":
        grid = emit_python_header_block_lines_from_header_dict(hdr)
        block = "\n".join(grid) + "\n"
        lines = body.splitlines(True)
        if lines and lines[0].lstrip("\ufeff").strip().startswith("<?php"):
            new_content = lines[0] + block + "".join(lines[1:]).lstrip("\n\r")
        else:
            new_content = "<?php\n" + block + body.lstrip("\n\r")
    else:
        raise ValueError("unsupported extension: %s" % ext)
    with open(path, "w", encoding="utf-8", newline="") as f:
        f.write(new_content)


def iter_targets(root: str):
    if os.path.isfile(root):
        yield root
        return
    for dirpath, _dirnames, filenames in os.walk(root):
        for name in filenames:
            if name.endswith((".md", ".py", ".php")):
                yield os.path.join(dirpath, name)


def main() -> int:
    parser = argparse.ArgumentParser(description="Migrate headers to 4.1.9 (22 fields)")
    parser.add_argument("path", help="File or directory under repo root")
    parser.add_argument("--dry-run", action="store_true")
    parser.add_argument("--external", action="store_true")
    parser.add_argument("--channel-index", default="")
    parser.add_argument("--source-timestamp", default="")
    parser.add_argument("--edges-toon", default="")
    args = parser.parse_args()
    targets = list(iter_targets(os.path.abspath(args.path)))
    if not targets:
        print("[WARN] no targets")
        return 0
    ok = 0
    for target in targets:
        ext = os.path.splitext(target)[1].lower()
        if ext not in (".md", ".py", ".php"):
            continue
        try:
            rel = _repo_rel(target)
        except ValueError:
            continue
        with open(target, "r", encoding="utf-8-sig") as f:
            content = f.read()
        hdr, body = extract_header_and_body(content, ext)
        if hdr is None:
            print("[SKIP] no header: %s" % rel)
            continue
        try:
            new_hdr = migrate_header_dict(
                hdr,
                rel,
                external=bool(args.external),
                channel_index=args.channel_index,
                source_timestamp=args.source_timestamp,
                edges_toon=args.edges_toon,
            )
        except ValueError as e:
            print("[ERROR] %s: %s" % (rel, e))
            return 1
        if args.dry_run:
            print("[DRY] %s -> 4.1.9 (%d keys)" % (rel, len(new_hdr)))
            ok += 1
            continue
        write_file(target, new_hdr, body)
        print("[OK] migrated: %s" % rel)
        ok += 1
    print("[DONE] %d file(s)" % ok)
    return 0


if __name__ == "__main__":
    sys.exit(main())
