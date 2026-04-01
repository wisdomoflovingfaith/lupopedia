import os
import shutil
from pathlib import Path
from datetime import datetime

AGENTS_DIR = Path('lupo-agents')
REQUIRED_FILES = ['agent.json', 'capabilities.json', 'properties.json', 'system_prompt.txt']
MD_TEMPLATES = ['changelog.md', 'decisions.md', 'observations.md']

def bootstrap_agent(agent_dir):
    """Ensure versions/v1.0.0/ exists and copies root files down."""
    versions_dir = agent_dir / 'versions'
    v1_dir = versions_dir / 'v1.0.0'
    
    os.makedirs(v1_dir, exist_ok=True)
    
    # Check if root has files to copy
    for req in REQUIRED_FILES:
        root_file = agent_dir / req
        v1_file = v1_dir / req
        if root_file.exists():
            shutil.copy2(root_file, v1_file)
            
    # Touch markdown files
    for md in MD_TEMPLATES:
        md_file = v1_dir / md
        if not md_file.exists():
            with open(md_file, 'w', encoding='utf-8') as f:
                f.write(f"# {md.split('.')[0].capitalize()}\n\nInitial scaffold created during D-53 standardization.\n")

def main():
    print("=== Bootstrapping Agent Version Directories ===")
    count = 0
    for agent_dir in AGENTS_DIR.iterdir():
        if not agent_dir.is_dir() or agent_dir.name.startswith('_'):
            continue
        
        # Only bootstrap if it has the basic 4 files (or if it's explicitly MAAT/HEIMDALL)
        # We can just iterate them all and if versions doesn't exist, we create it.
        bootstrap_agent(agent_dir)
        count += 1
        
    print(f"Bootstrapped v1.0.0 structures for {count} agent directories.")

if __name__ == '__main__':
    main()
