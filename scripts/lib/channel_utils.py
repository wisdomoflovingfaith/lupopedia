#!/usr/bin/env python3
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.2"
#   file_path_from_root: "scripts/lib/channel_utils.py"
#   web_path: "https://www.lupopedia.com/lupopedia/scripts/lib/channel_utils.py"
#   status: "complete"
#   when_updated: "20260417113505"   # ← update this timestamp when saving
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "memory/development/canonical/1026/04/channel-utils.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/channel-utils"
#   artifact_type: implementation
#   artifact_kind: tool
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: ""
#   content_id: null
#   content_parent_id: "16"
#   content_slug: "channel-utils"
#   default_collection_id: null
#   lupopedia.schema: implementation
#   title: "Shared channel key derivation utilities"
#   summary: "Shared channel_key derivation helpers for runtime and validator parity per PRD 16 §10 and PRD 38 channel scope."
# ---------------------------------------------------------------------
"""
Shared channel utilities for Lupopedia 4.1.2.

Provides deterministic channel_key resolution used by:
- db_memory_writer.py
- import_memory_edges_from_sidecar.py
- (future) validator parity checks

Follows doctrine order: explicit header → path derivation → unresolved.
"""

from __future__ import annotations

from typing import Any, Dict, Optional


def norm_path(path: str) -> str:
    """Normalize path for consistent parsing across writers and importers."""
    value = str(path or "").strip().replace("\\", "/")
    while "//" in value:
        value = value.replace("//", "/")
    return value.lstrip("/")


def derive_channel_from_lupo_memory_path(path_like: str) -> Optional[str]:
    """
    Derive channel_key from Type A path:
      memory/{channel_key}/{trust_tier}/...
    
    Per PRD 16 §10 and PRD 38 channel scope doctrine.
    """
    path = norm_path(path_like)
    if not path:
        return None
    parts = path.split("/")
    if len(parts) < 3 or parts[0] != "memory":
        return None
    channel_key = str(parts[1] or "").strip()
    return channel_key or None


def resolve_channel_key_for_artifact(
    explicit_channel_key: Any,
    memory_toon: Any,
    file_path_from_root: Any = None,
) -> Dict[str, Optional[str]]:
    """
    Resolve channel_key using strict doctrine order:
      1. Explicit non-empty channel_key from header
      2. Derive from memory_toon or file_path_from_root (memory/{channel_key}/...)
      3. Unresolved (None)

    Returns dict with:
      channel_key, source, explicit, derived, error
    """
    explicit = str(explicit_channel_key or "").strip() or None
    derived = derive_channel_from_lupo_memory_path(str(memory_toon or ""))
    if not derived:
        derived = derive_channel_from_lupo_memory_path(str(file_path_from_root or ""))

    if explicit and derived and explicit != derived:
        return {
            "channel_key": None,
            "source": "mismatch",
            "explicit": explicit,
            "derived": derived,
            "error": f"channel_key mismatch: explicit={explicit!r}, derived={derived!r}",
        }

    if explicit:
        return {
            "channel_key": explicit,
            "source": "explicit",
            "explicit": explicit,
            "derived": derived,
            "error": None,
        }

    if derived:
        return {
            "channel_key": derived,
            "source": "memory_toon",
            "explicit": explicit,
            "derived": derived,
            "error": None,
        }

    return {
        "channel_key": None,
        "source": "unresolved",
        "explicit": explicit,
        "derived": derived,
        "error": "channel_key unresolved",
    }


# Temporary backward-compatible aliases (while db_memory_writer.py and importer converge)
_norm_path = norm_path
_derive_channel_from_lupo_memory_path = derive_channel_from_lupo_memory_path