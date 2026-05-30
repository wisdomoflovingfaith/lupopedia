#!/usr/bin/env python3
"""
Inventory classes and detect conflicts between root classes/*.php and classes/*.php
"""

import os
from pathlib import Path
from datetime import datetime

LUPO_INCLUDES = Path('includes')
CLASSES_DIR = LUPO_INCLUDES / 'classes'

def to_pascal_case(filename):
    """Convert classes/pdo_db.php -> PdoDb.php"""
    name = filename.replace('classes/', '').replace('.php', '')
    parts = name.split('_')
    return ''.join(word.capitalize() for word in parts)

def get_file_info(file_path):
    """Get file metadata for comparison."""
    stat = file_path.stat()
    return {
        'path': str(file_path),
        'size': stat.st_size,
        'modified': datetime.fromtimestamp(stat.st_mtime),
        'created': datetime.fromtimestamp(stat.st_ctime)
    }

def main():
    print("=== Class Inventory with Conflict Detection ===\n")
    
    # Find all root class files
    root_files = {}
    for f in LUPO_INCLUDES.glob('classes/*.php'):
        class_name = to_pascal_case(f.name)
        root_files[class_name] = {
            'file': f,
            'info': get_file_info(f)
        }
    
    # Find all classes directory files
    classes_dir_files = {}
    if CLASSES_DIR.exists():
        for f in CLASSES_DIR.glob('*.php'):
            class_name = f.stem  # filename without .php
            classes_dir_files[class_name] = {
                'file': f,
                'info': get_file_info(f)
            }
    
    print(f"Root classes/*.php files: {len(root_files)}")
    print(f"Classes directory files: {len(classes_dir_files)}")
    
    # Detect conflicts
    conflicts = []
    for class_name in set(root_files.keys()) & set(classes_dir_files.keys()):
        root = root_files[class_name]
        classes = classes_dir_files[class_name]
        conflicts.append((class_name, root, classes))
    
    print(f"\n=== CONFLICTS DETECTED: {len(conflicts)} ===")
    for class_name, root, classes in conflicts:
        print(f"\n{class_name}.php")
        print(f"  ROOT:    {root['file']} (modified: {root['info']['modified']})")
        print(f"  CLASSES: {classes['file']} (modified: {classes['info']['modified']})")
        
        # Recommend which to keep based on recency
        if root['info']['modified'] > classes['info']['modified']:
            print(f"  → KEEP: root version (newer)")
        else:
            print(f"  → KEEP: classes version (newer)")
    
    # Unique files
    only_root = set(root_files.keys()) - set(classes_dir_files.keys())
    only_classes = set(classes_dir_files.keys()) - set(root_files.keys())
    
    print(f"\n=== UNIQUE TO ROOT ({len(only_root)}) ===")
    for name in sorted(only_root):
        print(f"  {name}.php")
    
    print(f"\n=== UNIQUE TO CLASSES ({len(only_classes)}) ===")
    for name in sorted(only_classes):
        print(f"  {name}.php")
    
    print("\n=== RECOMMENDATION ===")
    if conflicts:
        print(f"1. Resolve {len(conflicts)} conflicts manually before consolidating")
        print("2. For each conflict, compare code and keep the most recent/correct version")
    if only_root:
        print(f"3. Move {len(only_root)} unique root files to classes/ (safe)")
    if only_classes:
        print(f"4. Keep {len(only_classes)} unique classes files (already in correct location)")

if __name__ == '__main__':
    main()
