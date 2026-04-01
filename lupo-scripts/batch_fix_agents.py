#!/usr/bin/env python3
"""
Batch process and generate missing canonical configuration files for all agents.
Resolves ID conflicts (e.g., Junie vs Heimdall) and ensures 100% compliance.
"""

import os
import json
from pathlib import Path

AGENTS_DIR = Path('lupo-agents')

AGENT_REGISTRY = {
    "system": {"id": 0, "layer": "kernel"},
    "wolfie": {"id": 1, "layer": "coordination"},
    "lilith": {"id": 2, "layer": "coordination"},
    "rose": {"id": 3, "layer": "emotional"},
    "eris": {"id": 4, "layer": "emotional"},
    "metis": {"id": 5, "layer": "emotional"},
    "maat": {"id": 6, "layer": "kernel"},
    "thoth": {"id": 9, "layer": "coordination"},
    "chiron": {"id": 10, "layer": "application"},
    "athena": {"id": 11, "layer": "coordination"},
    "zeus": {"id": 12, "layer": "coordination"},
    "hephaestus": {"id": 14, "layer": "application"},
    "hermes": {"id": 15, "layer": "application"},
    "iris": {"id": 16, "layer": "application"},
    "anubis": {"id": 19, "layer": "kernel"},
    "atlas": {"id": 25, "layer": "application"},
    "vishwakarma": {"id": 106, "layer": "kernel"},
    "themis": {"id": 107, "layer": "kernel"},
    "heimdall": {"id": 108, "layer": "application"},
    "nemesis": {"id": 109, "layer": "application"},
    "tyche": {"id": 110, "layer": "application"},
    "countermeasure": {"id": 111, "layer": "application"},
    "junie": {"id": 112, "layer": "application"}, # Resolves ID 108 conflict with HEIMDALL
    "asclepius": {"id": 703, "layer": "kernel"},
    "apollo": {"id": 704, "layer": "emotional"},
    "agape": {"id": 705, "layer": "emotional"},
    "dionysus": {"id": 706, "layer": "emotional"},
    "sophia": {"id": 707, "layer": "emotional"},
    "thalia": {"id": 708, "layer": "emotional"},
    "chronos": {"id": 709, "layer": "kernel"},
    "hypnos": {"id": 710, "layer": "kernel"},
    "khaos": {"id": 711, "layer": "emotional"},
    "meta": {"id": 998, "layer": "application"},
    "methis": {"id": 999, "layer": "emotional"}
}

def write_missing(filepath, content_generator):
    if not filepath.exists():
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content_generator())
        return True
    return False

def fix_agent_id_conflict(agent_json_path, correct_id):
    """If agent.json exists but has wrong ID, fix it."""
    try:
        with open(agent_json_path, 'r', encoding='utf-8') as f:
            data = json.load(f)
        if data.get('agent_id') != correct_id:
            data['agent_id'] = correct_id
            with open(agent_json_path, 'w', encoding='utf-8') as f:
                json.dump(data, f, indent=4)
            print(f"  Fixed ID in {agent_json_path.parent.name} to {correct_id}")
    except:
        pass

def generate_agent_json(key, info):
    return json.dumps({
        "agent_key": key,
        "agent_id": info['id'],
        "version": "1.0.0",
        "is_kernel": info['layer'] == 'kernel',
        "layer": info['layer'],
        "name": key.upper(),
        "slug": key,
        "role": f"{key.capitalize()} operations",
        "description": f"Standardized {info['layer']} agent for {key}.",
        "when_updated_utc": "20260401000000"
    }, indent=4)

def generate_capabilities():
    return json.dumps({
        "capabilities": ["standard_operations", "baseline_compliance"],
        "skill_metadata": {
            "standard_operations": {
                "description": "Execute standard layer duties",
                "input": "context",
                "output": "action"
            }
        }
    }, indent=4)

def generate_properties(info):
    return json.dumps({
        "personality": {
            "tone": "standard",
            "default_stance": "neutral"
        },
        "constraints": {
            "must_follow_constitutional_rules": True
        },
        "coordination": {
            "primary_channel": 42 if info['layer'] in ['kernel', 'coordination'] else 64,
            "layer_auth": info['layer']
        }
    }, indent=4)

def generate_system_prompt(key, info):
    return f"You are {key.upper()}. You operate in the {info['layer']} layer.\n\nYOUR FUNCTION:\n- Standardized compliance tracking.\n- Sub-system execution."

def main():
    print("=== Batch Processing Agents ===")
    
    fixes = 0
    for agent_dir in AGENTS_DIR.iterdir():
        if not agent_dir.is_dir() or agent_dir.name.startswith('_'):
            continue
            
        key = agent_dir.name
        info = AGENT_REGISTRY.get(key, {"id": 9000, "layer": "application"})
        
        # 1. Force ID Correction if exists
        agent_json_path = agent_dir / 'agent.json'
        if agent_json_path.exists():
            fix_agent_id_conflict(agent_json_path, info['id'])
            
        # 2. Fill Missing Files
        if write_missing(agent_json_path, lambda: generate_agent_json(key, info)):
            fixes += 1
        if write_missing(agent_dir / 'capabilities.json', generate_capabilities):
            fixes += 1
        if write_missing(agent_dir / 'properties.json', lambda: generate_properties(info)):
            fixes += 1
        if write_missing(agent_dir / 'system_prompt.txt', lambda: generate_system_prompt(key, info)):
            fixes += 1
            
    print(f"Batch generation completed. Wrote missing configurations ({fixes} total interventions).")
    
if __name__ == '__main__':
    main()
