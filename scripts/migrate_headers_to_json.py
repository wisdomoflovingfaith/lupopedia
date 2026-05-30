#!/usr/bin/env python3
# -*- coding: utf-8 -*-
# DRAFT — scripts/migrate_headers_to_json.py
# Scan .md files with lupopedia.headers YAML blocks,
# extract edges/tags/footer, and write companion JSON files
# to docs/headers/{filename_without_ext}.json
#
# Usage:
#   python scripts/migrate_headers_to_json.py --dir docs/prd
#   python scripts/migrate_headers_to_json.py --dir docs/prd --dry-run
#   python scripts/migrate_headers_to_json.py --file docs/prd/00_root_constitutional_system_requirements.md
#   python scripts/migrate_headers_to_json.py --dir docs/prd --force

from __future__ import annotations

import argparse
import json
import re
import sys
from datetime import datetime, timezone
from pathlib import Path

try:
    import yaml
except ImportError:
    sys.stderr.write("PyYAML required: pip install pyyaml\n")
    sys.exit(1)

PROJECT_ROOT = Path(__file__).resolve().parent.parent
HEADERS_DIR = PROJECT_ROOT / "docs" / "headers"
MEMORY_BASE = "memory/2026/04"
SCHEMA_VERSION = "header_json_v1"
TOON_SCHEMA_VERSION = "toon_v1"
ACTOR_ID = 116
AGENT_NAME = "Claude Code"


def now_utc() -> str:
    return datetime.now(timezone.utc).strftime("%Y%m%d%H%M%S")


def split_frontmatter(text: str):
    """Return (yaml_str, body) or (None, text)."""
    if not text.startswith("---"):
        return None, text
    lines = text.splitlines()
    if lines[0].strip() != "---":
        return None, text
    for i in range(1, min(len(lines), 300)):
        if lines[i].strip() == "---":
            return "\n".join(lines[1:i]), "\n".join(lines[i + 1:]).lstrip("\n")
    # No closing ---, try H1 boundary
    for i, line in enumerate(lines[1:], 1):
        if re.match(r"^#\s+\S", line):
            return "\n".join(lines[1:i]), "\n".join(lines[i:])
    return None, text


def parse_yaml_headers(md_path: Path):
    """Return dict with keys: headers, edges, footer, raw_data."""
    text = md_path.read_text(encoding="utf-8")
    ym, _ = split_frontmatter(text)
    if not ym:
        return None
    try:
        data = yaml.safe_load(ym)
    except yaml.YAMLError as e:
        print(f"[WARN] YAML parse error in {md_path.name}: {e}")
        return None
    if not isinstance(data, dict):
        return None
    headers = data.get("lupopedia.headers") or {}
    edges_block = data.get("lupopedia.edges") or {}
    footer_block = data.get("lupopedia.footer") or {}
    return {
        "headers": headers,
        "edges_block": edges_block,
        "footer_block": footer_block,
        "raw_data": data,
    }


def build_outbound_edges(edges_block: dict) -> list:
    """Convert lupopedia.edges.outbound_edges to compact list."""
    outbound_raw = edges_block.get("outbound_edges") or []
    out = []
    for item in outbound_raw:
        if not isinstance(item, dict):
            continue
        to = item.get("to")
        if not to:
            continue
        out.append({
            "to": str(to),
            "type": str(item.get("type", "references")),
            "weight": float(item.get("weight", 1.0)),
        })
    return out


def toon_slug_from(stem: str) -> str:
    """Generate a toon memory slug from a file stem."""
    slug = re.sub(r"[^a-zA-Z0-9_-]", "-", stem).lower().strip("-")
    slug = re.sub(r"-{2,}", "-", slug)
    return slug


def build_header_json(md_path: Path, parsed: dict, ts: str) -> dict:
    """Build the companion JSON header dict."""
    headers = parsed["headers"]
    edges_block = parsed["edges_block"]
    footer_block = parsed["footer_block"]

    stem = md_path.stem
    toon_slug = toon_slug_from(stem)
    date_part = ts[:8]
    year = date_part[:4]
    month = date_part[4:6]
    memory_ref = f"memory/{year}/{month}/M-{toon_slug}-{date_part}.toon"

    outbound = build_outbound_edges(edges_block)

    # Tags from YAML headers
    tags = headers.get("tags") or []
    if isinstance(tags, str):
        tags = [tags]
    if "draft" not in tags:
        tags = list(tags) + ["draft"]

    companion = {
        "file_id": md_path.name,
        "file_path": str(md_path.relative_to(PROJECT_ROOT)).replace("\\", "/"),
        "last_updated": ts,
        "memory_ref": memory_ref,
        "edges": {
            "outbound": outbound
        },
        "tags": tags,
        "schema_version": SCHEMA_VERSION,
        "footer": {
            "last_verified": ts,
            "verified_by": {
                "actor_id": ACTOR_ID,
                "agent_name_identity": AGENT_NAME,
            }
        }
    }

    # Carry forward useful identity fields if present
    for key in ("prd_id", "prd_slug", "status", "purpose", "thread_id", "actor_id"):
        val = headers.get(key)
        if val is not None:
            companion[key] = val

    return companion


def build_toon(md_path: Path, companion: dict, ts: str) -> tuple:
    """Build .toon memory dict. Returns (toon_dict, toon_path)."""
    stem = md_path.stem
    toon_slug = toon_slug_from(stem)
    date_part = ts[:8]
    year = date_part[:4]
    month = date_part[4:6]
    toon_id = f"M-{toon_slug}-{date_part}"
    toon_path = PROJECT_ROOT / "memory" / year / month / f"{toon_id}.toon"

    edges = [{"to": f"FILE:{md_path.name}", "type": "modifies", "weight": 1.0}]
    for e in companion.get("edges", {}).get("outbound", [])[:5]:
        edges.append({"to": e["to"], "type": e["type"], "weight": e["weight"]})

    toon = {
        "id": toon_id,
        "type": "migration_memory",
        "ts": f"{ts}.000",
        "actor_id": ACTOR_ID,
        "summary": f"JSON header companion created for {md_path.name} via migrate_headers_to_json.py",
        "edges": edges,
        "content": {
            "action": "json_header_companion_created",
            "header_schema_version": SCHEMA_VERSION,
            "outbound_edge_count": len(companion["edges"]["outbound"]),
            "status": "draft"
        },
        "schema_version": TOON_SCHEMA_VERSION,
        "status": "draft"
    }
    return toon, toon_path


def process_file(md_path: Path, dry_run: bool, force: bool, ts: str) -> bool:
    """Process one .md file. Returns True if written."""
    parsed = parse_yaml_headers(md_path)
    if parsed is None:
        print(f"  [SKIP] {md_path.name} — no lupopedia.headers")
        return False

    stem = md_path.stem
    out_path = HEADERS_DIR / f"{stem}.json"

    if out_path.exists() and not force:
        print(f"  [EXISTS] {out_path.name} - skipping (use --force to overwrite)")
        return False

    companion = build_header_json(md_path, parsed, ts)
    toon, toon_path = build_toon(md_path, companion, ts)

    if dry_run:
        print(f"  [DRY-RUN] Would write: {out_path}")
        print(f"  [DRY-RUN] Would write: {toon_path}")
        return False

    HEADERS_DIR.mkdir(parents=True, exist_ok=True)
    out_path.write_text(json.dumps(companion, indent=2), encoding="utf-8")

    toon_path.parent.mkdir(parents=True, exist_ok=True)
    toon_path.write_text(json.dumps(toon, indent=2), encoding="utf-8")

    print(f"  [OK] {out_path.name}")
    print(f"  [OK] {toon_path.name}")
    return True


def main() -> None:
    parser = argparse.ArgumentParser(
        description="[DRAFT] Migrate YAML headers to JSON companion files + .toon memory nodes"
    )
    parser.add_argument("--dir", metavar="DIR", help="Scan all .md files in this directory")
    parser.add_argument("--file", metavar="FILE", help="Process a single .md file")
    parser.add_argument("--dry-run", action="store_true", help="Show what would be written without writing")
    parser.add_argument("--force", action="store_true", help="Overwrite existing companion files")
    parser.add_argument("--recursive", action="store_true", help="Scan DIR recursively")
    args = parser.parse_args()

    if not args.dir and not args.file:
        parser.print_help()
        sys.exit(1)

    ts = now_utc()
    files = []

    if args.file:
        p = Path(args.file)
        if not p.exists():
            sys.exit(f"File not found: {args.file}")
        files.append(p)
    elif args.dir:
        d = Path(args.dir)
        if not d.is_dir():
            sys.exit(f"Directory not found: {args.dir}")
        pattern = "**/*.md" if args.recursive else "*.md"
        files = sorted(d.glob(pattern))

    written = 0
    skipped = 0
    for f in files:
        result = process_file(f, dry_run=args.dry_run, force=args.force, ts=ts)
        if result:
            written += 1
        else:
            skipped += 1

    print(f"\nProcessed: {len(files)}, Written: {written}, Skipped/Existing: {skipped}")
    if args.dry_run:
        print("[DRY-RUN] No files were written.")


if __name__ == "__main__":
    main()
