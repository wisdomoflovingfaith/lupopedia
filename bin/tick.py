#!/usr/bin/env python3
# bin/tick.py — Update session.json timestamp
# DRAFT — Memory compaction update: channel config loading + auto-create config.json

import json
import argparse
import os
from datetime import datetime, timezone
from pathlib import Path

SESSION_PATH = Path('config/session.json')
CHANNELS_ROOT = Path('channels/0')
ACTOR_REGISTRY_PATH = Path('database/lupopedia/actors/registry.json')
GLOBAL_ATOMS_PATH = Path('memory/atoms/lupopedia_global_constants.atom.toon')

DEFAULT_CHANNEL_CONFIG = {
    "created": None,
    "memory_follow_rules": {
        "max_depth": 3,
        "edge_types": ["parent", "related", "implements", "references", "follows", "modifies"],
        "exclude_patterns": ["*_deprecated.toon", "*/archive/*"],
        "active_memory_ref": None,
        "exclude_memory_refs": []
    }
}


def now_utc():
    return datetime.now(timezone.utc).strftime("%Y%m%d%H%M%S")


def load_global_atoms():
    """Load global atoms file and return the data dictionary."""
    if not GLOBAL_ATOMS_PATH.exists():
        return {}
    try:
        with open(GLOBAL_ATOMS_PATH, 'r', encoding='utf-8') as f:
            data = json.load(f)
        return data if isinstance(data, dict) else {}
    except (OSError, json.JSONDecodeError):
        return {}


def get_version_from_atoms():
    """Get the current Lupopedia version from global atoms file."""
    atoms = load_global_atoms()
    constants = atoms.get('constants', {})
    versioning = constants.get('versioning', {})
    return versioning.get('current_lupopedia_version', '4.1.4')


def load_actor_registry():
    if not ACTOR_REGISTRY_PATH.exists():
        return {}
    try:
        with open(ACTOR_REGISTRY_PATH, 'r', encoding='utf-8') as f:
            data = json.load(f)
        actors = data.get('actors', {})
        return actors if isinstance(actors, dict) else {}
    except (OSError, json.JSONDecodeError):
        return {}


def build_actor_indexes(actors):
    by_name = {}
    by_id = {}
    for actor_name, rec in actors.items():
        if not isinstance(rec, dict):
            continue
        aid = rec.get('actor_id')
        if isinstance(aid, int):
            by_id[aid] = actor_name
        by_name[str(actor_name).strip().lower()] = rec
    return by_name, by_id


def resolve_actor_selection(actor_selector):
    """
    Resolve actor by numeric id or actor_name using registry.
    Returns tuple: (actor_id, actor_name) or (None, None) on failure.
    """
    actors = load_actor_registry()
    by_name, by_id = build_actor_indexes(actors)
    raw = str(actor_selector).strip()
    if raw.isdigit():
        aid = int(raw)
        name = by_id.get(aid)
        if name is None:
            return None, None
        return aid, name
    rec = by_name.get(raw.lower())
    if rec is None:
        return None, None
    aid = rec.get('actor_id')
    if not isinstance(aid, int):
        return None, None
    return aid, str(rec.get('actor_name', raw)).strip() or raw


def list_available_actors():
    actors = load_actor_registry()
    pairs = []
    for actor_name, rec in actors.items():
        if isinstance(rec, dict) and isinstance(rec.get('actor_id'), int):
            pairs.append((rec['actor_id'], actor_name))
    pairs.sort(key=lambda t: t[0])
    return pairs


def ensure_channel_config(channel_key, slug, ts):
    """Create default config.json for a channel if it doesn't exist. Returns config dict."""
    config_path = CHANNELS_ROOT / channel_key / slug / "config.json"
    if not config_path.exists():
        config_path.parent.mkdir(parents=True, exist_ok=True)
        config = dict(DEFAULT_CHANNEL_CONFIG)
        config["channel_key"] = channel_key
        config["slug"] = slug
        config["created"] = ts
        with open(config_path, 'w') as f:
            json.dump(config, f, indent=2)
        print(f"[OK] Created channel config: {config_path}")
        return config
    with open(config_path, 'r') as f:
        return json.load(f)


def load_active_memory_ref(channel_key, slug):
    """Read active_memory_ref from channel config. Returns None if not set."""
    config_path = CHANNELS_ROOT / channel_key / slug / "config.json"
    if not config_path.exists():
        return None
    try:
        with open(config_path, 'r') as f:
            config = json.load(f)
        return config.get("memory_follow_rules", {}).get("active_memory_ref")
    except (OSError, json.JSONDecodeError):
        return None


def update_active_memory_ref(channel_key, slug, mem_ref=None):
    """Updates active_memory_ref in config.json and syncs to session.json"""
    config_path = CHANNELS_ROOT / channel_key / slug / "config.json"
    if config_path.exists():
        with open(config_path, 'r') as f:
            config = json.load(f)
        
        if mem_ref is not None:
            if "memory_follow_rules" not in config:
                config["memory_follow_rules"] = {}
            config["memory_follow_rules"]["active_memory_ref"] = mem_ref
            with open(config_path, 'w') as f:
                json.dump(config, f, indent=2)
        else:
            mem_ref = config.get('memory_follow_rules', {}).get('active_memory_ref')
        
        # Update session.json
        if SESSION_PATH.exists():
            with open(SESSION_PATH, 'r') as f:
                session = json.load(f)
            session['active_memory_ref'] = mem_ref
            with open(SESSION_PATH, 'w') as f:
                json.dump(session, f, indent=2)

def main():
    parser = argparse.ArgumentParser()
    parser.add_argument('--set-version', type=str, help='Set version')
    parser.add_argument('--channel', type=str, help='Set channel (alias for --channel_key)')
    parser.add_argument('--channel_key', type=str, help='Set channel key')
    parser.add_argument('--slug', type=str, help='Set channel slug')
    parser.add_argument('--prd', type=str, help='Set PRD focus (comma-separated)')
    parser.add_argument('--actor', type=str, help='Actor ID (triggers transcript append)')
    parser.add_argument('--action', type=str, help='Action to log alongside tick')
    parser.add_argument('--set-actor', type=str, help='Set active actor (actor_id or actor_name)')
    parser.add_argument('--list-actors', action='store_true', help='List available actors from registry')
    args = parser.parse_args()

    if args.list_actors:
        pairs = list_available_actors()
        if not pairs:
            print("[WARN] No actors found in registry")
            return
        print("Available actors:")
        for aid, name in pairs:
            print(f"  {aid}: {name}")
        return

    with open(SESSION_PATH, 'r') as f:
        session = json.load(f)

    # Initialize timestamp if missing
    if 'timestamp' not in session:
        session['timestamp'] = {}

    ts = now_utc()
    session['timestamp']['current'] = ts

    if args.set_version:
        session['version'] = args.set_version
    if args.channel:
        session['active_channel_key'] = args.channel
    if args.channel_key:
        session['active_channel_key'] = args.channel_key
    if args.slug:
        session['active_slug'] = args.slug
    if args.prd:
        session['prd_focus'] = [p.strip() for p in args.prd.split(',')]
    if args.set_actor:
        aid, aname = resolve_actor_selection(args.set_actor)
        if aid is None:
            print(f"[ERROR] Unknown actor selector: {args.set_actor}")
            print("[HINT] Use --list-actors to see valid actor_id/actor_name values")
            return
        session['active_actor_id'] = aid
        session['active_actor_name'] = aname
        session['active_delegation_chain'] = f"{aname}:root"
        print(f"[OK] Active actor set: {aid} ({aname})")

    # When channel or slug changes, load/create channel config and copy active_memory_ref
    channel_changed = args.channel or args.channel_key or args.slug
    if channel_changed:
        ck = session.get('active_channel_key', '')
        sl = session.get('active_slug', '')
        if ck and sl:
            channel_config = ensure_channel_config(ck, sl, ts)
            update_active_memory_ref(ck, sl)
            mem_ref = session.get('active_memory_ref')
            if mem_ref:
                print(f"[OK] active_memory_ref loaded: {mem_ref}")
            else:
                print(f"[OK] Channel config loaded (active_memory_ref: null — fresh start)")

    with open(SESSION_PATH, 'w') as f:
        json.dump(session, f, indent=2)

    # Get version from global atoms (single source of truth)
    ver = get_version_from_atoms()
    ck = session.get('active_channel_key', 'N/A')
    sl = session.get('active_slug', 'N/A')
    actor_id = session.get('active_actor_id', 'N/A')
    actor_name = session.get('active_actor_name', 'N/A')
    print(f"[OK] Updated: version={ver}, channel_key={ck}, slug={sl}, actor={actor_id}:{actor_name}, time={ts}")

    # If actor and action provided, automatically route a transcript log
    if args.actor and args.action:
        import subprocess
        cmd = [
            "python", "bin/transcript.py",
            "--actor", args.actor,
            "--action", args.action
        ]
        try:
            subprocess.run(cmd, check=True)
            print(f"[OK] Startup log routed to transcript for actor {args.actor}")
        except Exception as e:
            print(f"[WARN] Could not execute transcript: {e}")

if __name__ == "__main__":
    main()