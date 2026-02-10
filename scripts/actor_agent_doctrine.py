"""
Actor/Agent Doctrine — single source of truth for all scripts.

Enforced by: generate_seed_from_toons.py, generate_toon_files.py.
Doctrine doc: docs/channels/doctrine/ACTOR_AGENT_DOCTRINE.md

Constants and row builder MUST NOT be changed without updating the doctrine doc.
"""

import json
from typing import Any, Dict, Optional

# Table names
AGENT_REGISTRY_TABLE = "lupo_agent_registry"
UNIFIED_REGISTRY_TABLE = "lupo_unified_registry"
ACTORS_TABLE = "lupo_actors"

# Actor ID space: 0–9999 reserved for AI agents; human actors start at 10000
ACTOR_ID_RESERVED_MAX = 9999
ACTOR_HUMAN_START = 10000

# Unified registry ID for agent-derived rows: 9000000 + agent_registry_id (fixed, do not change)
UNIFIED_REGISTRY_AGENT_OFFSET = 9000000


def build_unified_registry_row_from_agent(agent_row: Optional[Dict[str, Any]]) -> Optional[Dict[str, Any]]:
    """
    Build a lupo_unified_registry row (as column_name -> value) from an active agent row.

    Doctrine: entity_type='actor', entity_table='lupo_agent_registry', entity_id=dedicated_index_id=agent_registry_id,
    entity_key=code, entity_name=name, federation_node_id=1, is_active=1, metadata_json with actor_source_type/id.

    Returns dict suitable for both seed (convert values to SQL via sql_value) and TOON (json_serializable).
    """
    if not agent_row:
        return None
    row_lower = {str(k).lower(): v for k, v in agent_row.items()}
    agent_registry_id = row_lower.get("agent_registry_id")
    if agent_registry_id is None:
        return None
    created = row_lower.get("created_ymdhis") or 0
    code = row_lower.get("code") or ""
    name = row_lower.get("name") or ""
    is_kernel = row_lower.get("is_kernel") if row_lower.get("is_kernel") is not None else 0
    metadata_json = json.dumps({
        "actor_source_type": "lupo_agent_registry",
        "actor_source_id": agent_registry_id,
    }, separators=(",", ":"))
    unified_id = UNIFIED_REGISTRY_AGENT_OFFSET + int(agent_registry_id)
    return {
        "unified_registry_id": unified_id,
        "entity_type": "actor",
        "entity_id": agent_registry_id,
        "entity_key": code,
        "entity_name": name,
        "dedicated_index_id": agent_registry_id,
        "entity_table": "lupo_agent_registry",
        "federation_node_id": 1,
        "created_ymdhis": created,
        "updated_ymdhis": created,
        "is_deleted": 0,
        "deleted_ymdhis": None,
        "is_active": 1,
        "is_kernel": is_kernel,
        "metadata_json": metadata_json,
    }


def build_actor_row_from_agent(agent_row: Optional[Dict[str, Any]]) -> Optional[Dict[str, Any]]:
    """
    Build a lupo_actors row (as column_name -> value) from an active agent row.

    Doctrine: active agents become actors. actor_id = agent_registry_id (0-9999 reserved for AI),
    actor_type='agent', slug=code (slugified), actor_source_type='lupo_agent_registry', actor_source_id=agent_registry_id.

    Returns dict suitable for seed INSERT (values converted to SQL via sql_value in caller).
    """
    if not agent_row:
        return None
    row_lower = {str(k).lower(): v for k, v in agent_row.items()}
    agent_registry_id = row_lower.get("agent_registry_id")
    if agent_registry_id is None:
        return None
    created = row_lower.get("created_ymdhis") or 0
    code = (row_lower.get("code") or "").strip()
    name = (row_lower.get("name") or "").strip()
    slug = code.lower().replace(" ", "-") if code else "agent-{}".format(agent_registry_id)
    metadata_str = json.dumps({
        "actor_source_type": "lupo_agent_registry",
        "actor_source_id": agent_registry_id,
    }, separators=(",", ":"))
    return {
        "actor_id": agent_registry_id,
        "actor_type": "agent",
        "slug": slug,
        "name": name or slug,
        "created_ymdhis": created,
        "updated_ymdhis": created,
        "is_active": 1,
        "is_deleted": 0,
        "deleted_ymdhis": None,
        "actor_source_id": agent_registry_id,
        "actor_source_type": "lupo_agent_registry",
        "metadata": metadata_str,
        "adversarial_role": "none",
        "adversarial_oversight_actor_id": None,
        "avatar_hash": None,
    }
