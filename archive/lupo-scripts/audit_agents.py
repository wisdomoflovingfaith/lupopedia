#!/usr/bin/env python3
"""
Audit all agent directories for required files and configuration.
Detects ID conflicts and missing required files.
"""

import os
import json
from pathlib import Path
from collections import defaultdict

AGENTS_DIR = Path('lupo-agents')
REQUIRED_FILES = ['agent.json', 'capabilities.json', 'properties.json', 'system_prompt.txt']
OPTIONAL_DIRS = ['versions']

def parse_agent_id(agent_dir):
    """Extract agent_id from agent.json if exists."""
    agent_file = agent_dir / 'agent.json'
    if not agent_file.exists():
        return None
    try:
        with open(agent_file, 'r') as f:
            data = json.load(f)
            return data.get('agent_id')
    except:
        return None

def audit_agent(agent_dir):
    """Check if agent has all required files."""
    missing = []
    for req in REQUIRED_FILES:
        if not (agent_dir / req).exists():
            missing.append(req)
    
    has_versions = (agent_dir / 'versions').exists()
    agent_id = parse_agent_id(agent_dir)
    
    return {
        'agent_key': agent_dir.name,
        'agent_id': agent_id,
        'missing': missing,
        'has_versions': has_versions,
        'ok': len(missing) == 0 and agent_id is not None
    }

def main():
    print("=== Agent Audit ===\n")
    
    ok_count = 0
    bad_count = 0
    id_map = defaultdict(list)
    
    for agent_dir in sorted(AGENTS_DIR.iterdir()):
        if not agent_dir.is_dir():
            continue
        if agent_dir.name.startswith('_'):
            continue  # Skip templates
        
        result = audit_agent(agent_dir)
        
        if result['agent_id'] is not None:
            id_map[result['agent_id']].append(result['agent_key'])
        
        if result['ok']:
            print(f"✅ {result['agent_key']} (ID: {result['agent_id']})")
            ok_count += 1
        else:
            print(f"❌ {result['agent_key']} (ID: {result['agent_id']}) — missing: {', '.join(result['missing'])}")
            bad_count += 1
    
    # Check for ID conflicts
    conflicts = {id: agents for id, agents in id_map.items() if len(agents) > 1}
    
    print(f"\n=== ID Conflicts ===")
    if conflicts:
        for id, agents in conflicts.items():
            print(f"⚠️ ID {id} assigned to: {', '.join(agents)}")
    else:
        print("✅ No ID conflicts detected")
    
    print(f"\nSummary: {ok_count} OK, {bad_count} need work")

if __name__ == '__main__':
    main()
