# lupopedia.headers:
#   when_updated: "20260324175617"
#   file_path_from_root: "lupo-scripts/actor_agent_doctrine.py"
#   questions_toon: null
#   channel_id: 42
#   actor_id: 102
#   actor_name: "cursor"
#   delegation_chain: "cursor:root"
#   artifact_type: "tooling"
#   artifact_kind: "script"
# lupopedia.footer:
#   last_verified: "20260324175617"
#   last_verified_by: "cursor"
#   last_verified_by_actor_id: 102

"""
Actor/Agent Doctrine — single source of truth for all scripts.

Enforced by: generate_seed_from_toons.py, generate_toon_files.py.
Doctrine doc: docs/channels/doctrine/ACTOR_AGENT_DOCTRINE.md

Constants and row builder MUST NOT be changed without updating the doctrine doc.
"""

import json
from typing import Any, Dict, Optional

# Table names
AGENT_REGISTRY_TABLE = None  # DEPRECATED: moved to lupo_registry
REGISTRY_TABLE = "lupo_registry"
ACTORS_TABLE = "lupo_actors"

# Actor ID space: 0–9999 reserved for AI agents; human actors start at 10000
ACTOR_ID_RESERVED_MAX = 9999
ACTOR_HUMAN_START = 10000

# Registry ID for agent-derived rows: 9000000 + agent_registry_id (fixed, do not change)
REGISTRY_AGENT_OFFSET = 9000000


def build_registry_row_from_agent(agent_row: Optional[Dict[str, Any]]) -> Optional[Dict[str, Any]]:
    """
    DEPRECATED: Agents are now directly in lupo_registry.
    """
    return None


def build_actor_row_from_agent(agent_row: Optional[Dict[str, Any]]) -> Optional[Dict[str, Any]]:
    """
    DEPRECATED: Agents are now directly in lupo_registry.
    """
    return None
