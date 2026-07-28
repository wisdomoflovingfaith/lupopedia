#!/usr/bin/env python3
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.9"
#   path_from_lupopedia_root: scripts/logging/header_generator.py
#   web_path: https://www.lupopedia.com/lupopedia/scripts/logging/header_generator.py
#   status: active
#   when_updated: "20260728131310"
#   trust_tier: canonical
#   questions_toon: null
#   memory_toon: null
#   atoms_toon: null
#   transcript_jsonl: 0/development/dual-operational-logs
#   artifact_type: script
#   artifact_kind: reference
#   channel_key: development
#   federation_node_id: 0
#   thread_key: ""
#   lupopedia.schema: script
#   prd_cluster: 16_C_98_C
#   title: Dual operational log header generator
#   summary: Generates PRD 16 twenty-two field constitutional headers for Captain/WOLFIE logs.
#   edges_toon: null
#   channel_index: lupopedia
#   source_timestamp: null
# ---------------------------------------------------------------------
"""PRD 98_C / PRD 16 constitutional header generator for dual operational logs."""

from __future__ import annotations

from datetime import datetime

HEADER_FORMAT_VERSION = "4.1.9"

HEADER_KEYS_ORDERED = (
    "header_format_version",
    "path_from_lupopedia_root",
    "web_path",
    "status",
    "when_updated",
    "trust_tier",
    "questions_toon",
    "memory_toon",
    "atoms_toon",
    "transcript_jsonl",
    "artifact_type",
    "artifact_kind",
    "channel_key",
    "federation_node_id",
    "thread_key",
    "lupopedia.schema",
    "prd_cluster",
    "title",
    "summary",
    "edges_toon",
    "channel_index",
    "source_timestamp",
)


def to_packed_utc(dt=None):
    if dt is None:
        dt = datetime.utcnow()
    return dt.strftime("%Y%m%d%H%M%S")


def packed_to_iso(ymdhis):
    if not isinstance(ymdhis, str) or len(ymdhis) != 14 or not ymdhis.isdigit():
        raise ValueError("timestamp_ymdhis must be 14 digits YYYYMMDDHHIISS")
    return "%s-%s-%sT%s:%s:%sZ" % (
        ymdhis[0:4],
        ymdhis[4:6],
        ymdhis[6:8],
        ymdhis[8:10],
        ymdhis[10:12],
        ymdhis[12:14],
    )


def generate_constitutional_header(overrides):
    """Return a dict with exactly 22 PRD 16 keys in order."""
    if not overrides or "path_from_lupopedia_root" not in overrides:
        raise ValueError("path_from_lupopedia_root is required")
    if "title" not in overrides or "summary" not in overrides:
        raise ValueError("title and summary are required")

    path = str(overrides["path_from_lupopedia_root"]).replace("\\", "/")
    when = overrides.get("when_updated") or to_packed_utc()

    base = {
        "header_format_version": HEADER_FORMAT_VERSION,
        "path_from_lupopedia_root": path,
        "web_path": "https://www.lupopedia.com/lupopedia/" + path,
        "status": "active",
        "when_updated": when,
        "trust_tier": "canonical",
        "questions_toon": None,
        "memory_toon": None,
        "atoms_toon": None,
        "transcript_jsonl": "0/logs/dual-operational",
        "artifact_type": "log",
        "artifact_kind": "reference",
        "channel_key": "logs",
        "federation_node_id": 0,
        "thread_key": overrides.get("thread_key") or "",
        "lupopedia.schema": "log",
        "prd_cluster": "98_C",
        "title": overrides["title"],
        "summary": overrides["summary"],
        "edges_toon": None,
        "channel_index": "lupopedia",
        "source_timestamp": None,
    }
    base.update(overrides)
    base["path_from_lupopedia_root"] = path
    base["header_format_version"] = HEADER_FORMAT_VERSION
    if not base.get("web_path"):
        base["web_path"] = "https://www.lupopedia.com/lupopedia/" + path

    ordered = {}
    for key in HEADER_KEYS_ORDERED:
        ordered[key] = base.get(key)
    return ordered
