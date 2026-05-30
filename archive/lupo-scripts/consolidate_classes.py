#!/usr/bin/env python3
"""
Consolidate classes/*.php files from lupo-includes/ root into lupo-includes/classes/
with conflict detection and resolution.
"""

import os
import re
import shutil
from pathlib import Path
from datetime import datetime

LUPO_INCLUDES = Path('lupo-includes')
CLASSES_DIR = LUPO_INCLUDES / 'classes'
BACKUP_DIR = LUPO_INCLUDES / 'classes/_conflict_backup'

def to_pascal_case(filename):
    """Convert classes/pdo_db.php -> PdoDb.php"""
    name = filename.replace('classes/', '').replace('.php', '')
    parts = name.split('_')
    return ''.join(word.capitalize() for word in parts)

def detect_conflicts():
    """Return dict of conflicting classes with file info."""
    conflicts = {}
    
    # Root files
    root_files = {}
    for f in LUPO_INCLUDES.glob('classes/*.php'):
        class_name = to_pascal_case(f.name)
        root_files[class_name] = f
    
    # Classes directory files
    classes_files = {}
    if CLASSES_DIR.exists():
        for f in CLASSES_DIR.glob('*.php'):
            classes_files[f.stem] = f
    
    # Find conflicts
    for class_name in set(root_files.keys()) & set(classes_files.keys()):
        conflicts[class_name] = {
            'root': root_files[class_name],
            'classes': classes_files[class_name]
        }
    
    return conflicts

def resolve_conflict(class_name, root_file, classes_file, keep='newer', dry_run=True):
    """Resolve conflict between root and classes versions."""
    
    root_mtime = root_file.stat().st_mtime
    classes_mtime = classes_file.stat().st_mtime
    
    if keep == 'newer':
        keep_root = root_mtime > classes_mtime
    elif keep == 'root':
        keep_root = True
    elif keep == 'classes':
        keep_root = False
    else:
        raise ValueError(f"Unknown keep option: {keep}")
    
    winner = root_file if keep_root else classes_file
    loser = classes_file if keep_root else root_file
    
    if dry_run:
        print(f"  Would keep: {winner.name} (newer)" if keep == 'newer' else f"  Would keep: {winner.name}")
        print(f"  Would archive: {loser.name} to _conflict_backup/")
    else:
        # Backup the loser
        BACKUP_DIR.mkdir(parents=True, exist_ok=True)
        backup_path = BACKUP_DIR / f"{class_name}_{loser.name}"
        shutil.copy2(loser, backup_path)
        print(f"  Backed up: {loser.name} -> {backup_path}")
        
        # If we're keeping root, we need to move it to classes
        if keep_root:
            new_path = CLASSES_DIR / f"{class_name}.php"
            shutil.move(str(root_file), str(new_path))
            print(f"  Moved: {root_file.name} -> classes/{class_name}.php")
        else:
            # Classes version already in correct place, just remove root
            root_file.unlink()
            print(f"  Removed root: {root_file.name}")

def update_references(file_path, old_name, new_name, new_path, dry_run=False):
    """Update all references to a moved class file."""
    try:
        with open(file_path, 'r', encoding='utf-8') as f:
            content = f.read()
    except UnicodeDecodeError:
        return False
    
    # Update include/require statements
    old_pattern = re.escape(old_name)
    new_reference = f'classes/{new_name}'
    
    new_content = re.sub(
        rf'(require|include)(_once)?\s*[\(\'"]{old_pattern}[\)\'"]',
        rf'\1\2(\'{new_reference}\')',
        content
    )
    
    if new_content != content:
        if dry_run:
            print(f"  Would update: {file_path}")
        else:
            with open(file_path, 'w', encoding='utf-8') as f:
                f.write(new_content)
            print(f"  Updated: {file_path}")
        return True
    return False

def main(keep='newer', resolve_conflicts=False, dry_run=True):
    print("=== Class Consolidation Script ===\n")
    
    if not LUPO_INCLUDES.exists():
        print(f"Directory not found: {LUPO_INCLUDES}")
        return
    
    # Create classes directory if it doesn't exist
    CLASSES_DIR.mkdir(exist_ok=True)
    
    # Detect conflicts first
    conflicts = detect_conflicts()
    
    if conflicts:
        print(f"⚠️  CONFLICTS DETECTED: {len(conflicts)} classes exist in both locations")
        for class_name, files in conflicts.items():
            print(f"\n  {class_name}.php")
            print(f"    root:    {files['root'].name} ({datetime.fromtimestamp(files['root'].stat().st_mtime)})")
            print(f"    classes: {files['classes'].name} ({datetime.fromtimestamp(files['classes'].stat().st_mtime)})")
        
        if not resolve_conflicts:
            print("\n❌ Conflicts require resolution. Run with --resolve-conflicts to proceed.")
            return
    
    # Find all root class files (excluding those we'll handle via conflict resolution)
    root_files_to_move = []
    for f in LUPO_INCLUDES.glob('classes/*.php'):
        class_name = to_pascal_case(f.name)
        if class_name in conflicts:
            continue  # Handled separately
        root_files_to_move.append((f, class_name))
    
    print(f"\n📦 Files to move (no conflict): {len(root_files_to_move)}")
    for f, class_name in root_files_to_move:
        print(f"  {f.name} -> classes/{class_name}.php")
    
    # Handle conflicts if resolution requested
    if conflicts and resolve_conflicts:
        print(f"\n⚖️  Resolving {len(conflicts)} conflicts (keeping {keep} version)...")
        for class_name, files in conflicts.items():
            resolve_conflict(class_name, files['root'], files['classes'], keep, dry_run)
    
    # Move non-conflicting files
    if root_files_to_move:
        print(f"\n📦 Moving {len(root_files_to_move)} files...")
        for old_path, class_name in root_files_to_move:
            new_path = CLASSES_DIR / f"{class_name}.php"
            
            if dry_run:
                print(f"  Would move: {old_path.name} -> classes/{class_name}.php")
            else:
                shutil.move(str(old_path), str(new_path))
                print(f"  Moved: {old_path.name} -> classes/{class_name}.php")
            
            # Update references
            updated_count = 0
            for php_file in Path('.').rglob('*.php'):
                if php_file == old_path or php_file == new_path:
                    continue
                if update_references(php_file, old_path.name, f"{class_name}.php", new_path, dry_run):
                    updated_count += 1
            print(f"    Updated {updated_count} references")
    
    if dry_run:
        print("\n[DRY RUN] No changes were made. Add --execute to apply changes.")
    else:
        print("\n=== Consolidation Complete ===")
        print(f"Conflicts resolved: {len(conflicts)} (kept {keep} versions)")
        print(f"Files moved: {len(root_files_to_move)}")
        print("\nNext steps:")
        print("1. Test application thoroughly")
        print("2. Review _conflict_backup/ for archived versions")
        print("3. Update project_structure_prd.md")

if __name__ == '__main__':
    import argparse
    parser = argparse.ArgumentParser(description='Consolidate class files')
    parser.add_argument('--execute', action='store_true', help='Actually perform the consolidation')
    parser.add_argument('--resolve-conflicts', action='store_true', help='Automatically resolve conflicts')
    parser.add_argument('--keep', choices=['newer', 'root', 'classes'], default='newer', 
                        help='Which version to keep when conflicts exist')
    args = parser.parse_args()
    
    main(keep=args.keep, resolve_conflicts=args.resolve_conflicts, dry_run=not args.execute)
