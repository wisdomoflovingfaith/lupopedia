import os
import re
import sys
import json
import argparse

def to_compliant_file_name(filename):
    """
    ALL filenames MUST be lowercase with underscores.
    - dots/hyphens replaced with underscores (except last dot for extension)
    """
    parts = filename.split('.')
    if len(parts) > 1:
        ext = parts[-1]
        name = '_'.join(parts[:-1])
    else:
        name = filename
        ext = ''
        
    # Replace spaces, dots, hyphens with underscores
    new_name = re.sub(r'[\s\.\-]+', '_', name)
    new_name = new_name.lower()
    new_name = re.sub(r'[^a-z0-9_]', '_', new_name)
    new_name = re.sub(r'_+', '_', new_name)
    new_name = new_name.strip('_')
    
    if ext:
        return new_name + '.' + ext.lower()
    return new_name

def to_compliant_folder_name(foldername):
    """
    ALL folder names MUST be lowercase with hyphens.
    - spaces/dots/underscores replaced with hyphens
    """
    # Replace spaces, dots, underscores with hyphens
    new_name = re.sub(r'[\s\._]+', '-', foldername)
    new_name = new_name.lower()
    new_name = re.sub(r'[^a-z0-9\-]', '-', new_name)
    new_name = re.sub(r'\-+', '-', new_name)
    new_name = new_name.strip('-')
    return new_name

def scan_directories(targets):
    mapping = {}
    collisions = {}
    stats = {
        "scanned_files": 0,
        "scanned_folders": 0,
        "to_rename_files": 0,
        "to_rename_folders": 0,
        "collisions": 0,
        "already_normalized_files": 0,
        "already_normalized_folders": 0
    }
    
    # Track all target paths to detect collisions (case-insensitive for safety)
    target_paths = {}

    for target_dir in targets:
        if not os.path.exists(target_dir):
            continue
            
        for root, dirs, files in os.walk(target_dir):
            if 'node_modules' in root or '.git' in root:
                continue
                
            for folder in dirs:
                stats["scanned_folders"] += 1
                new_folder_name = to_compliant_folder_name(folder)
                
                if folder == new_folder_name:
                    stats["already_normalized_folders"] += 1
                else:
                    stats["to_rename_folders"] += 1

            for file in files:
                stats["scanned_files"] += 1
                old_file_path = os.path.join(root, file)
                
                # Deconstruct path into components to normalize each part
                # We normalize the path relative to the current working directory.
                path_parts = old_file_path.replace('\\', '/').split('/')
                new_path_parts = []
                
                for i, part in enumerate(path_parts):
                    if i == len(path_parts) - 1:
                        # It's a file
                        new_path_parts.append(to_compliant_file_name(part))
                    else:
                        # It's a folder
                        new_path_parts.append(to_compliant_folder_name(part))
                
                new_file_path = os.path.join(*new_path_parts)
                
                # Check if the whole path changed
                if old_file_path.replace('\\', '/') == new_file_path.replace('\\', '/'):
                    stats["already_normalized_files"] += 1
                    continue
                
                # Check for collisions
                low_new_path = new_file_path.lower().replace('\\', '/')
                if low_new_path in target_paths:
                    if low_new_path not in collisions:
                        collisions[low_new_path] = [target_paths[low_new_path]]
                    collisions[low_new_path].append(old_file_path)
                    stats["collisions"] += 1
                else:
                    target_paths[low_new_path] = old_file_path
                    mapping[old_file_path] = new_file_path
                    stats["to_rename_files"] += 1
                
    return mapping, collisions, stats

if __name__ == "__main__":
    parser = argparse.ArgumentParser(description="Lupopedia Doc Rename Mapping Tool")
    parser.add_argument("--apply", action="store_true", help="Perform actual renames (DANGEROUS)")
    parser.add_argument("--output-dir", default="lupo-docs/versions/4.0.99/status", help="Directory for reports")
    args = parser.parse_args()

    targets = ["lupo-docs", "lupo-memory"]
    mapping, collisions, stats = scan_directories(targets)

    os.makedirs(args.output_dir, exist_ok=True)

    # 1. Write Mapping JSON
    mapping_path = os.path.join(args.output_dir, "rename_docs_mapping.json")
    with open(mapping_path, "w") as f:
        json.dump({"stats": stats, "mapping": mapping}, f, indent=4)

    # 2. Write Collisions MD
    collision_path = os.path.join(args.output_dir, "rename_docs_collisions.md")
    with open(collision_path, "w") as f:
        f.write("# Rename Collision Report\n\n")
        if not collisions:
            f.write("No collisions detected.\n")
        else:
            for target, sources in collisions.items():
                f.write(f"### Target: `{target}`\n")
                for src in sources:
                    f.write(f"- Source: `{src}`\n")
                f.write("\n")

    # 3. Write Phase 1 Report
    report_path = os.path.join(args.output_dir, "rename_docs_phase1_report.md")
    with open(report_path, "w") as f:
        f.write("# Phase 1: Rename Mapping Report\n\n")
        f.write(f"- **Scanned Files:** {stats['scanned_files']}\n")
        f.write(f"- **Scanned Folders:** {stats['scanned_folders']}\n")
        f.write(f"- **Files to Rename:** {stats['to_rename_files']}\n")
        f.write(f"- **Folders to Rename:** {stats['to_rename_folders']}\n")
        f.write(f"- **Collisions Detected:** {stats['collisions']}\n")
        f.write(f"- **Already Normalized Files:** {stats['already_normalized_files']}\n")
        f.write(f"- **Already Normalized Folders:** {stats['already_normalized_folders']}\n\n")
        
        f.write("## Risk Assessment\n")
        f.write("- **Folder Renames:** Folder names are now normalized to `lowercase-with-hyphens`. This changes the base path of all files contained within.\n")
        f.write("- **File Renames:** Filenames are normalized to `lowercase_with_underscores`. Dots (except for extension) and hyphens are replaced with underscores.\n")
        f.write("- **Reference Integrity:** This phase **DOES NOT** update internal links. Applying these renames now will break the site.\n")
        
        f.write("\n## Recommendation for Phase 2\n")
        f.write("1. Review `rename_docs_collisions.md` and manually resolve conflicts.\n")
        f.write("2. Implement a global search-and-replace tool that uses `rename_docs_mapping.json` to update all references.\n")
        f.write("3. Perform a dry-run of the reference update before applying any renames.\n")

    print(f"Phase 1 Complete.")
    print(f"Mapping: {mapping_path}")
    print(f"Collisions: {collision_path}")
    print(f"Report: {report_path}")

    if args.apply:
        print("\n[ERROR] --apply is not yet fully implemented for safety.")
        sys.exit(1)
