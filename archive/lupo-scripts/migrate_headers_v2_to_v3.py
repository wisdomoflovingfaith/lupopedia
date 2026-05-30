#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Migrate LUPOPEDIA HEADERS from v2 rich YAML to v3 minimal pointer headers.

Adds trust-tier memory pathing:
- seed: lupo-memory/{channel}/seed/{slug}.toon
- canonical: lupo-memory/{channel}/canonical/{actual_year-1000}/{month}/{slug}.toon
- staging: lupo-memory/{channel}/staging/{actual_year}/{month}/{slug}.toon
- archive: lupo-memory/{channel}/archive/{actual_year}/{month}/{slug}.toon
"""

from __future__ import annotations

import argparse
import json
import re
from datetime import datetime, timezone
from pathlib import Path

try:
    import yaml
except ImportError as exc:  # pragma: no cover
    raise SystemExit("PyYAML required: pip install pyyaml") from exc


REPO_ROOT = Path(__file__).resolve().parent.parent
CHANNEL_REGISTRY = REPO_ROOT / "lupo-channels" / "registry.json"
VALID_TIERS = ("seed", "canonical", "staging", "archive")


def now_utc() -> str:
    return datetime.now(timezone.utc).strftime("%Y%m%d%H%M%S")


def load_registry() -> tuple[dict, dict]:
    slug_map = {}
    id_to_slug = {}
    if CHANNEL_REGISTRY.exists():
        raw = json.loads(CHANNEL_REGISTRY.read_text(encoding="utf-8"))
        channels = raw.get("channels", {})
        for slug, cfg in channels.items():
            slug_map[slug] = cfg if isinstance(cfg, dict) else {}
            cid = str(slug_map[slug].get("id", ""))
            if cid:
                id_to_slug[cid] = slug
    return slug_map, id_to_slug


def split_frontmatter(text: str):
    if not text.startswith("---"):
        return None, text
    lines = text.splitlines()
    if not lines or lines[0].strip() != "---":
        return None, text
    for i in range(1, len(lines)):
        if lines[i].strip() == "---":
            return "\n".join(lines[1:i]), "\n".join(lines[i + 1 :]).lstrip("\n")
    return None, text


def slugify(value: str) -> str:
    s = value.lower()
    s = re.sub(r"[^a-z0-9_-]+", "-", s)
    s = re.sub(r"-{2,}", "-", s).strip("-")
    return s or "header"


def resolve_channel_key(headers: dict, id_to_slug: dict) -> str:
    explicit_key = headers.get("channel_key")
    if isinstance(explicit_key, str) and explicit_key:
        return explicit_key
    cid = headers.get("channel_id")
    if cid is None:
        return "development"
    return id_to_slug.get(str(cid), "development")


def resolve_trust_tier(headers: dict, channel_cfg: dict) -> str:
    tier = headers.get("trust_tier")
    if isinstance(tier, str) and tier in VALID_TIERS:
        return tier
    default_tier = channel_cfg.get("default_trust_tier", "canonical")
    if default_tier in VALID_TIERS:
        return default_tier
    return "canonical"


def compute_memory_key(channel_key: str, trust_tier: str, ts: str, slug: str) -> tuple[str, int, int, int]:
    actual_year = int(ts[0:4])
    month = int(ts[4:6])
    if trust_tier == "seed":
        return (
            "lupo-memory/{0}/seed/{1}.toon".format(channel_key, slug),
            0,
            actual_year,
            month,
        )
    if trust_tier == "canonical":
        display_year = actual_year - 1000
    else:
        display_year = actual_year
    return (
        "lupo-memory/{0}/{1}/{2}/{3:02d}/{4}.toon".format(
            channel_key, trust_tier, display_year, month, slug
        ),
        display_year,
        actual_year,
        month,
    )


def build_memory_payload(
    rel_path: str,
    header_data: dict,
    ts: str,
    channel_key: str,
    trust_tier: str,
    display_year: int,
    actual_year: int,
    month: int,
) -> dict:
    headers = header_data.get("lupopedia.headers", {}) or {}
    edges = header_data.get("lupopedia.edges", {}) or {}
    footer = header_data.get("lupopedia.footer", {}) or {}
    return {
        "id": slugify(Path(rel_path).stem),
        "type": "header_metadata",
        "schema_version": "toon_v1",
        "header_format_version": 3,
        "file_path_from_root": rel_path,
        "channel_key": channel_key,
        "trust_tier": trust_tier,
        "display_year": display_year,
        "actual_year": actual_year,
        "month": month,
        "edges": {"outbound": edges.get("outbound_edges", [])},
        "tags": headers.get("tags", []),
        "purpose": headers.get("purpose", ""),
        "status": headers.get("status", "draft"),
        "author": {
            "type": "actor",
            "id": headers.get("actor_id", 0),
            "name": headers.get("actor_name", ""),
        },
        "delegation_chain": headers.get("delegation_chain", ""),
        "footer": footer or {"last_verified": ts, "next_action": []},
    }


def migrate_file(path: Path, slug_map: dict, id_to_slug: dict, dry_run: bool = False) -> bool:
    text = path.read_text(encoding="utf-8")
    frontmatter, body = split_frontmatter(text)
    if frontmatter is None:
        return False

    parsed = yaml.safe_load(frontmatter) or {}
    headers = parsed.get("lupopedia.headers")
    if not isinstance(headers, dict):
        return False

    rel_path = str(path.relative_to(REPO_ROOT)).replace("\\", "/")
    ts = now_utc()
    channel_key = resolve_channel_key(headers, id_to_slug)
    channel_cfg = slug_map.get(channel_key, {})
    trust_tier = resolve_trust_tier(headers, channel_cfg)
    slug = slugify(Path(rel_path).stem)
    memory_key, display_year, actual_year, month = compute_memory_key(
        channel_key, trust_tier, ts, slug
    )

    memory_payload = build_memory_payload(
        rel_path,
        parsed,
        ts,
        channel_key,
        trust_tier,
        display_year,
        actual_year,
        month,
    )
    memory_path = REPO_ROOT / memory_key
    if not dry_run:
        memory_path.parent.mkdir(parents=True, exist_ok=True)
        memory_path.write_text(json.dumps(memory_payload, indent=2), encoding="utf-8")

    new_headers = {
        "lupopedia.headers": {
            "header_format_version": 3,
            "lupopedia.schema": headers.get("lupopedia.schema", "documentation"),
            "when_updated": str(headers.get("when_updated", ts)),
            "file_path_from_root": rel_path,
            "web_path": headers.get(
                "web_path",
                "http://www.lupopedia.com/lupopedia/" + rel_path,
            ),
            "last_modified_utc": ts,
            "federation_node_id": int(headers.get("federation_node_id", 0)),
            "channel_key": channel_key,
            "trust_tier": trust_tier,
            "memory_key": memory_key,
            "thread_id": headers.get("thread_id", "unassigned-thread"),
            "artifact_type": headers.get("artifact_type", "documentation"),
            "artifact_kind": headers.get("artifact_kind", "documentation"),
        }
    }

    new_frontmatter = yaml.safe_dump(
        new_headers, sort_keys=False, default_flow_style=False
    ).strip()
    new_text = "---\n" + new_frontmatter + "\n---\n\n" + body.lstrip("\n")
    if not dry_run:
        path.write_text(new_text, encoding="utf-8")
    return True


def collect_files(target: str) -> list:
    p = Path(target)
    if not p.is_absolute():
        p = (REPO_ROOT / p).resolve()
    if p.is_file():
        return [p]
    return sorted(p.rglob("*.md"))


def main() -> None:
    parser = argparse.ArgumentParser(description="Migrate v2 headers to v3 format.")
    parser.add_argument("--path", required=True, help="File or directory path")
    parser.add_argument("--dry-run", action="store_true", help="Preview without writing")
    args = parser.parse_args()

    slug_map, id_to_slug = load_registry()
    files = collect_files(args.path)
    migrated = 0
    for fpath in files:
        if migrate_file(fpath, slug_map, id_to_slug, dry_run=args.dry_run):
            migrated += 1
            print("[OK] migrated {0}".format(str(fpath)))
    print("[DONE] migrated={0} scanned={1}".format(migrated, len(files)))


if __name__ == "__main__":
    main()
