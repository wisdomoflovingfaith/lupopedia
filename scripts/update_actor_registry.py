#!/usr/bin/env python3
"""
Actor Registry Updater for Lupopedia

This script provides canonical methods to update the actor registry
while maintaining database neutrality and proper timestamp handling.
"""

import json
import time
from pathlib import Path
from typing import Dict, List, Any, Optional

# Canonical registry path
REGISTRY_PATH = Path("registry/actor_registry.toon")

# Canonical field definitions
REQUIRED_FIELDS = [
    "actor_id",
    "type", 
    "gateway",
    "channel_key",
    "task",
    "memory_handoff_toon",
    "last_action_utc",
    "last_lilith_review_utc",
    "last_ara_review_utc", 
    "last_thoth_review_utc",
    "last_rose_mood_review_utc",
    "status",
    "notes"
]

# Valid gateway types (canonical taxonomy)
VALID_GATEWAYS = {
    "api_http": "REST/JSON API",
    "api_ws": "WebSocket API", 
    "local_agent": "Python/daemon/IDE plugin",
    "manual_web_chat": "browser chat, cut/paste",
    "ide_panel": "Cursor, Windsurf, Antigravity, Warp",
    "system_daemon": "background OS agent",
    "batch_script": "offline script producing TOONs"
}

def now_utc_bigint() -> int:
    """Generate current UTC timestamp as BIGINT(14) format"""
    return int(time.strftime("%Y%m%d%H%M%S", time.gmtime()))

def load_registry() -> List[Dict[str, Any]]:
    """Load the actor registry from TOON file"""
    if not REGISTRY_PATH.exists():
        return []
    
    try:
        with open(REGISTRY_PATH, 'r', encoding='utf-8') as f:
            return json.load(f)
    except (json.JSONDecodeError, IOError) as e:
        print(f"Error loading registry: {e}")
        return []

def save_registry(registry: List[Dict[str, Any]]) -> bool:
    """Save the actor registry to TOON file"""
    try:
        # Ensure directory exists
        REGISTRY_PATH.parent.mkdir(parents=True, exist_ok=True)
        
        with open(REGISTRY_PATH, 'w', encoding='utf-8') as f:
            json.dump(registry, f, indent=2, sort_keys=True)
        return True
    except IOError as e:
        print(f"Error saving registry: {e}")
        return False

def find_actor(actor_id: int) -> Optional[Dict[str, Any]]:
    """Find an actor by ID in the registry"""
    registry = load_registry()
    for actor in registry:
        if actor.get("actor_id") == actor_id:
            return actor
    return None

def update_actor(actor_id: int, **fields) -> bool:
    """
    Update or insert an actor in the registry
    
    Args:
        actor_id: Unique actor identifier
        **fields: Fields to update (must be in REQUIRED_FIELDS)
    
    Returns:
        True if successful, False otherwise
    """
    registry = load_registry()
    actor = find_actor(actor_id)
    
    # Validate fields
    for field in fields:
        if field not in REQUIRED_FIELDS:
            print(f"Warning: Field '{field}' not in required fields")
    
    # Validate gateway if provided
    if "gateway" in fields and fields["gateway"] not in VALID_GATEWAYS:
        print(f"Error: Invalid gateway '{fields['gateway']}'")
        print(f"Valid gateways: {', '.join(VALID_GATEWAYS.keys())}")
        return False
    
    # Create new actor if not found
    if actor is None:
        actor = {"actor_id": actor_id}
        # Initialize required fields with defaults
        for field in REQUIRED_FIELDS:
            if field != "actor_id":
                actor[field] = "" if isinstance(actor.get(field), str) else 0
        registry.append(actor)
        print(f"Created new actor {actor_id}")
    else:
        print(f"Updating existing actor {actor_id}")
    
    # Update fields
    for k, v in fields.items():
        actor[k] = v
    
    # Always update last_action_utc
    actor["last_action_utc"] = now_utc_bigint()
    
    # Save registry
    if save_registry(registry):
        print(f"Successfully updated actor {actor_id}")
        return True
    else:
        print(f"Failed to save registry for actor {actor_id}")
        return False

def list_actors() -> List[Dict[str, Any]]:
    """List all actors in the registry"""
    return load_registry()

def validate_registry() -> List[str]:
    """Validate the registry and return list of issues"""
    registry = load_registry()
    issues = []
    
    for i, actor in enumerate(registry):
        actor_id = actor.get("actor_id", "unknown")
        
        # Check required fields
        for field in REQUIRED_FIELDS:
            if field not in actor:
                issues.append(f"Actor {actor_id}: Missing required field '{field}'")
        
        # Check gateway validity
        gateway = actor.get("gateway")
        if gateway and gateway not in VALID_GATEWAYS:
            issues.append(f"Actor {actor_id}: Invalid gateway '{gateway}'")
        
        # Check for duplicate actor_ids
        duplicates = sum(1 for a in registry if a.get("actor_id") == actor_id)
        if duplicates > 1:
            issues.append(f"Actor {actor_id}: Duplicate actor_id found")
    
    return issues

def main():
    """Command-line interface for the registry updater"""
    import argparse
    
    parser = argparse.ArgumentParser(description="Update Lupopedia Actor Registry")
    parser.add_argument("action", choices=["update", "list", "validate", "show"])
    parser.add_argument("--actor-id", type=int, help="Actor ID for update action")
    parser.add_argument("--type", help="Actor type")
    parser.add_argument("--gateway", help="Gateway type")
    parser.add_argument("--channel-key", help="Channel key")
    parser.add_argument("--task", help="Current task")
    parser.add_argument("--status", help="Actor status")
    parser.add_argument("--notes", help="Actor notes")
    
    args = parser.parse_args()
    
    if args.action == "update":
        if not args.actor_id:
            print("Error: --actor-id required for update action")
            return
        
        # Build update fields
        fields = {}
        if args.type:
            fields["type"] = args.type
        if args.gateway:
            fields["gateway"] = args.gateway
        if args.channel_key:
            fields["channel_key"] = args.channel_key
        if args.task:
            fields["task"] = args.task
        if args.status:
            fields["status"] = args.status
        if args.notes:
            fields["notes"] = args.notes
        
        update_actor(args.actor_id, **fields)
    
    elif args.action == "list":
        actors = list_actors()
        if actors:
            print(f"Found {len(actors)} actors:")
            for actor in actors:
                print(f"  ID: {actor.get('actor_id')} | Type: {actor.get('type')} | Gateway: {actor.get('gateway')} | Status: {actor.get('status')}")
        else:
            print("No actors found in registry")
    
    elif args.action == "validate":
        issues = validate_registry()
        if issues:
            print(f"Found {len(issues)} issues:")
            for issue in issues:
                print(f"  - {issue}")
        else:
            print("Registry validation passed")
    
    elif args.action == "show":
        if not args.actor_id:
            print("Error: --actor-id required for show action")
            return
        
        actor = find_actor(args.actor_id)
        if actor:
            print(f"Actor {args.actor_id}:")
            for field in REQUIRED_FIELDS:
                value = actor.get(field, "")
                print(f"  {field}: {value}")
        else:
            print(f"Actor {args.actor_id} not found")

if __name__ == "__main__":
    main()
