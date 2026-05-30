#!/usr/bin/env python3
"""
lupo-scripts/update_atom.py — Update global atoms

Usage:
    python update_atom.py version=4.0.98
    python update_atom.py current_utc=20260408220000
    python update_atom.py session_salt=<new_salt>
    python update_atom.py --show-all
    python update_atom.py --show version
"""

import argparse
import json
import sys
import re
from pathlib import Path
from datetime import datetime, timezone

# Paths
SCRIPT_DIR = Path(__file__).parent.absolute()
PROJECT_ROOT = SCRIPT_DIR.parent
ATOMS_PATH = PROJECT_ROOT / 'lupo-config' / 'global_atoms.json'
VERSION_FILE = PROJECT_ROOT / 'lupo-config' / 'CURRENT_LUPOPEDIA_VERSION.txt'

def load_atoms():
    if ATOMS_PATH.exists():
        with open(ATOMS_PATH, 'r') as f:
            return json.load(f)
    return {}

def save_atoms(atoms):
    with open(ATOMS_PATH, 'w') as f:
        json.dump(atoms, f, indent=2)
    print(f"✅ Updated {ATOMS_PATH}")

def update_version(new_version):
    """Update version in atoms and version file"""
    atoms = load_atoms()
    atoms['version'] = new_version
    atoms['last_updated'] = datetime.now(timezone.utc).strftime('%Y%m%d%H%M%S')
    save_atoms(atoms)
    
    # Also update version file
    VERSION_FILE.parent.mkdir(parents=True, exist_ok=True)
    VERSION_FILE.write_text(new_version + "\n")
    print(f"✅ Updated {VERSION_FILE} to {new_version}")

def update_atom(key, value):
    """Update a single atom"""
    atoms = load_atoms()
    
    # Handle nested keys with dot notation (e.g., project.name)
    if '.' in key:
        parts = key.split('.')
        current = atoms
        for part in parts[:-1]:
            if part not in current:
                current[part] = {}
            current = current[part]
        current[parts[-1]] = value
    else:
        atoms[key] = value
    
    atoms['last_updated'] = datetime.now(timezone.utc).strftime('%Y%m%d%H%M%S')
    save_atoms(atoms)
    print(f"✅ Set {key} = {value}")

def show_atoms():
    atoms = load_atoms()
    print(json.dumps(atoms, indent=2))

def show_atom(key):
    atoms = load_atoms()
    if '.' in key:
        parts = key.split('.')
        current = atoms
        for part in parts:
            if isinstance(current, dict):
                current = current.get(part)
            else:
                current = None
                break
        print(current)
    else:
        print(atoms.get(key, 'null'))

def main():
    parser = argparse.ArgumentParser(description="Update global atoms")
    parser.add_argument('--show-all', action='store_true', help='Show all atoms')
    parser.add_argument('--show', type=str, help='Show specific atom (e.g., version, project.name)')
    parser.add_argument('set', nargs='*', help='key=value pairs to set')
    args = parser.parse_args()

    if args.show_all:
        show_atoms()
        return

    if args.show:
        show_atom(args.show)
        return

    for item in args.set:
        if '=' not in item:
            print(f"ERROR: {item} is not key=value", file=sys.stderr)
            sys.exit(1)
        
        key, value = item.split('=', 1)
        
        # Handle special keys
        if key == 'version':
            update_version(value)
        else:
            # Try to parse as JSON for numbers/booleans
            try:
                value = json.loads(value)
            except json.JSONDecodeError:
                pass  # Keep as string
            update_atom(key, value)

if __name__ == "__main__":
    main()
