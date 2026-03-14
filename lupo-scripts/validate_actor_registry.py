#!/usr/bin/env python3
"""
Validator for actors/registry.json and actors/aliases.csv

Ensures:
1. No duplicate active aliases in aliases.csv
2. All actor_ids in aliases.csv exist in registry.json
3. All canonical_slugs in registry.json have corresponding canonical alias
4. No collisions (same alias mapping to multiple actor_ids)
5. Soft delete integrity (deleted aliases have deleted_ymdhis)

Exit codes:
0 = All validations passed
1 = Validation failures found
"""

import json
import csv
import sys
from pathlib import Path
from collections import defaultdict

def load_registry(registry_path):
    """Load and parse registry.json"""
    with open(registry_path, 'r', encoding='utf-8') as f:
        return json.load(f)

def load_aliases(aliases_path):
    """Load and parse aliases.csv"""
    aliases = []
    with open(aliases_path, 'r', encoding='utf-8') as f:
        reader = csv.DictReader(f)
        for row in reader:
            aliases.append(row)
    return aliases

def validate_registry_aliases(registry, aliases):
    """Run all validation checks"""
    errors = []
    warnings = []
    
    # Convert registry keys to integers for comparison
    registry_ids = set(int(actor_id) for actor_id in registry.keys())
    
    # Track active aliases
    active_aliases = defaultdict(list)
    alias_to_actors = defaultdict(list)
    
    # Check 1: Parse aliases and build lookup tables
    for alias in aliases:
        alias_slug = alias['alias_slug'].lower()
        actor_id = int(alias['actor_id'])
        is_deleted = int(alias['is_deleted'])
        deleted_ymdhis = alias['deleted_ymdhis']
        
        # Check soft delete integrity
        if is_deleted == 1 and (not deleted_ymdhis or deleted_ymdhis == '0'):
            errors.append(f"Alias '{alias_slug}' is deleted but has no deleted_ymdhis")
        
        if is_deleted == 0 and deleted_ymdhis and deleted_ymdhis != '0':
            warnings.append(f"Alias '{alias_slug}' is active but has deleted_ymdhis set")
        
        # Track active aliases
        if is_deleted == 0:
            active_aliases[alias_slug].append(actor_id)
            alias_to_actors[actor_id].append(alias_slug)
    
    # Check 2: Duplicate active aliases
    for alias_slug, actor_ids in active_aliases.items():
        if len(actor_ids) > 1:
            errors.append(f"Duplicate active alias '{alias_slug}' maps to multiple actors: {actor_ids}")
    
    # Check 3: All actor_ids in aliases exist in registry
    alias_actor_ids = set(int(alias['actor_id']) for alias in aliases)
    missing_in_registry = alias_actor_ids - registry_ids
    if missing_in_registry:
        errors.append(f"Actor IDs in aliases.csv missing from registry.json: {sorted(missing_in_registry)}")
    
    # Check 4: All canonical_slugs in registry have canonical alias
    for actor_id, actor_data in registry.items():
        canonical_slug = actor_data['canonical_slug']
        actor_id_int = int(actor_id)
        is_actor_deleted = int(actor_data.get('is_deleted', 0))
        
        # Find canonical alias for this actor
        canonical_aliases = [
            alias for alias in aliases
            if int(alias['actor_id']) == actor_id_int
            and alias['alias_type'] == 'canonical'
        ]
        
        if not canonical_aliases:
            errors.append(f"Actor {actor_id} ({canonical_slug}) has no canonical alias in aliases.csv")
        else:
            # For deleted actors, allow only deleted aliases
            if is_actor_deleted == 1:
                active_canonical = [a for a in canonical_aliases if int(a['is_deleted']) == 0]
                if active_canonical:
                    errors.append(
                        f"Deleted actor {actor_id} ({canonical_slug}) has active canonical alias"
                    )
            else:
                # For active actors, require active canonical alias
                active_canonical = [a for a in canonical_aliases if int(a['is_deleted']) == 0]
                if not active_canonical:
                    errors.append(
                        f"Active actor {actor_id} ({canonical_slug}) has no active canonical alias"
                    )
                elif len(active_canonical) > 1:
                    errors.append(
                        f"Actor {actor_id} ({canonical_slug}) has multiple active canonical aliases"
                    )
                else:
                    # Verify canonical alias matches canonical_slug
                    if active_canonical[0]['alias_slug'] != canonical_slug:
                        errors.append(
                            f"Actor {actor_id} canonical_slug '{canonical_slug}' "
                            f"doesn't match canonical alias '{active_canonical[0]['alias_slug']}'"
                        )
    
    # Check 5: Orphaned registry entries (no aliases at all)
    for actor_id in registry_ids:
        actor_data = registry[str(actor_id)]
        is_actor_deleted = int(actor_data.get('is_deleted', 0))
        
        if actor_id not in alias_to_actors:
            # Only warn for active actors with no aliases
            if is_actor_deleted == 0:
                warnings.append(f"Active actor {actor_id} in registry has no aliases")
    
    return errors, warnings

def main():
    """Main validation entry point"""
    # Determine paths
    script_dir = Path(__file__).parent
    repo_root = script_dir.parent
    registry_path = repo_root / 'actors' / 'registry.json'
    aliases_path = repo_root / 'actors' / 'aliases.csv'
    
    # Check files exist
    if not registry_path.exists():
        print(f"ERROR: registry.json not found at {registry_path}")
        return 1
    
    if not aliases_path.exists():
        print(f"ERROR: aliases.csv not found at {aliases_path}")
        return 1
    
    # Load data
    try:
        registry = load_registry(registry_path)
        aliases = load_aliases(aliases_path)
    except Exception as e:
        print(f"ERROR: Failed to load files: {e}")
        return 1
    
    # Run validations
    errors, warnings = validate_registry_aliases(registry, aliases)
    
    # Report results
    print("=" * 70)
    print("ACTOR REGISTRY VALIDATION REPORT")
    print("=" * 70)
    print(f"Registry entries: {len(registry)}")
    print(f"Alias entries: {len(aliases)}")
    print(f"Active aliases: {sum(1 for a in aliases if int(a['is_deleted']) == 0)}")
    print(f"Deleted aliases: {sum(1 for a in aliases if int(a['is_deleted']) == 1)}")
    print("=" * 70)
    
    if warnings:
        print(f"\n⚠️  WARNINGS ({len(warnings)}):")
        for warning in warnings:
            print(f"  - {warning}")
    
    if errors:
        print(f"\n❌ ERRORS ({len(errors)}):")
        for error in errors:
            print(f"  - {error}")
        print("\n" + "=" * 70)
        print("VALIDATION FAILED")
        print("=" * 70)
        return 1
    else:
        if not warnings:
            print("\n✅ ALL VALIDATIONS PASSED")
        else:
            print(f"\n✅ ALL VALIDATIONS PASSED (with {len(warnings)} warnings)")
        print("=" * 70)
        return 0

if __name__ == '__main__':
    sys.exit(main())
