#!/usr/bin/env python3
from __future__ import annotations

import json
from pathlib import Path

import yaml

ROOT = Path(__file__).resolve().parent.parent
PRD_DIR = ROOT / "lupo-docs" / "prd"
TS = "20260409140338"
ACTUAL_YEAR = int(TS[:4])
MONTH = TS[4:6]
DISPLAY_YEAR = str(ACTUAL_YEAR - 1000)

TARGETS = [
    ("38_memory_unification.md", "memory", "canonical", "38-memory-unification", "memory-unification-prd"),
    ("44_session_config_and_transcript.md", "sessions", "canonical", "44-session-config-and-transcript", "session-config-prd"),
    ("43_parent_child_trust_ladder.md", "trust_ladder", "canonical", "43-parent-child-trust-ladder", "parent-child-trust-ladder"),
    ("17_decisions_format.md", "decisions", "canonical", "17-decisions-format", "prd-decisions-format"),
    ("16_lupopedia_headers.md", "headers", "canonical", "16-lupopedia-headers", "prd-lupopedia-headers"),
    ("79_install_seed_doctrine.md", "install", "seed", "79-install-seed-doctrine", "install-seed-doctrine"),
    ("42_content_seeding_and_truth_tables.md", "content", "seed", "42-content-seeding", "content-seeding-prd"),
    ("33_softaculous_certification_4_1_0_gate.md", "release", "canonical", "33-softaculous-certification", "softaculous-gate"),
    ("37_kairos_channel_memory_consolidation.md", "kairos", "canonical", "37-kairos-memory", "kairos-prd"),
    ("36_rose_multi_persona_synthetic_dialog.md", "rose", "canonical", "36-rose-dialog", "rose-prd"),
    ("15_actors.md", "actors", "canonical", "15-actors", "actors-prd"),
]


def split_frontmatter(text: str):
    if not text.startswith("---\n"):
        return None, text
    end = text.find("\n---\n", 4)
    if end == -1:
        return None, text
    return text[4:end], text[end + 5 :]


def memory_path(channel_key: str, trust_tier: str, slug: str) -> str:
    if trust_tier == "seed":
        return f"lupo-memory/{channel_key}/seed/{slug}.toon"
    return f"lupo-memory/{channel_key}/canonical/{DISPLAY_YEAR}/{MONTH}/{slug}.toon"


def get_nested(d: dict, path: list, default=None):
    cur = d
    for p in path:
        if not isinstance(cur, dict):
            return default
        cur = cur.get(p)
    return cur if cur is not None else default


for filename, channel_key, trust_tier, slug, thread_id in TARGETS:
    fpath = PRD_DIR / filename
    if not fpath.exists():
        print(f"[SKIP] missing {filename}")
        continue
    text = fpath.read_text(encoding="utf-8")
    front, body = split_frontmatter(text)
    data = {}
    if front:
        data = yaml.safe_load(front) or {}
    headers = data.get("lupopedia.headers", {}) if isinstance(data, dict) else {}
    edges = data.get("lupopedia.edges", {}) if isinstance(data, dict) else {}
    footer = data.get("lupopedia.footer", {}) if isinstance(data, dict) else {}

    purpose = headers.get("purpose", "")
    status = headers.get("status", "draft")
    tags = headers.get("tags", [])
    author = headers.get("author", {})
    author_id = author.get("id", headers.get("actor_id", 102))
    author_name = author.get("name", headers.get("actor_name", "cursor"))
    author_type = author.get("type", "actor")
    delegation_chain = headers.get("delegation_chain", "cursor:root")

    if not isinstance(tags, list):
        tags = [str(tags)] if tags else []

    outbound = get_nested(edges, ["outbound_edges"], [])
    if not isinstance(outbound, list):
        outbound = []

    footer_last_verified = footer.get("last_verified", TS)
    verified_by = footer.get("verified_by", {})
    verified_via = footer.get("verified_via", {})
    orchestrator = footer.get("orchestrator", "cursor:root")
    next_action = footer.get("next_action", [])
    if not isinstance(next_action, list):
        next_action = []

    memory_key = memory_path(channel_key, trust_tier, slug)
    rel_path = f"lupo-docs/prd/{filename}"

    memory_payload = {
        "id": slug,
        "type": "header_metadata",
        "schema_version": "toon_v1",
        "header_format_version": 3,
        "file_path_from_root": rel_path,
        "channel_key": channel_key,
        "trust_tier": trust_tier,
        "purpose": purpose,
        "status": status,
        "tags": tags,
        "author": {"type": author_type, "id": author_id, "name": author_name},
        "delegation_chain": delegation_chain,
        "edges": {"outbound": outbound},
        "footer": {
            "last_verified": str(footer_last_verified),
            "verified_by": {
                "identity_type": verified_by.get("identity_type", "actor"),
                "actor_id": verified_by.get("actor_id", author_id),
                "agent_name_identity": verified_by.get("agent_name_identity", str(author_name)),
            },
            "verified_via": {
                "type": verified_via.get("type", "faucet"),
                "faucet_slug": verified_via.get("faucet_slug", "cursor"),
            },
            "orchestrator": orchestrator,
            "next_action": next_action,
        },
    }

    mpath = ROOT / memory_key
    mpath.parent.mkdir(parents=True, exist_ok=True)
    mpath.write_text(json.dumps(memory_payload, indent=2), encoding="utf-8")

    new_header = {
        "lupopedia.headers": {
            "header_format_version": 3,
            "lupopedia.schema": "prd",
            "when_updated": TS,
            "file_path_from_root": rel_path,
            "web_path": f"http://www.lupopedia.com/lupopedia/{rel_path}",
            "last_modified_utc": TS,
            "federation_node_id": 0,
            "channel_key": channel_key,
            "trust_tier": trust_tier,
            "memory_key": memory_key,
            "thread_id": thread_id,
            "artifact_type": "prd",
            "artifact_kind": "specification",
        }
    }

    new_front = yaml.safe_dump(new_header, sort_keys=False).strip()
    new_text = f"---\n{new_front}\n---\n{body if front is not None else text}"
    fpath.write_text(new_text, encoding="utf-8")
    print(f"[OK] migrated {filename}")

print("[DONE] top PRDs migrated")
