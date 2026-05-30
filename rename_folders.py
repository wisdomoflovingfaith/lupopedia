import os
import re
import sys

def to_compliant_folder_name(name):
    # Replace underscores and spaces with hyphens for folders
    new_name = re.sub(r'[\s_]+', '-', name)
    # Lowercase
    new_name = new_name.lower()
    # Remove any character that is not alphanumeric or hyphen
    new_name = re.sub(r'[^a-z0-9\-]', '', new_name)
    # Remove multiple hyphens
    new_name = re.sub(r'\-+', '-', new_name)
    # Remove leading/trailing hyphens
    new_name = new_name.strip('-')
    return new_name

def process_directories(root_dir):
    renamed_dirs = []
    # Walk bottom-up to rename subdirectories before their parents
    for root, dirs, files in os.walk(root_dir, topdown=False):
        for name in dirs:
            # Skip if already lowercase and no underscores
            if name.lower() == name and '_' not in name and ' ' not in name:
                continue
                
            old_path = os.path.join(root, name)
            new_name = to_compliant_folder_name(name)
            new_path = os.path.join(root, new_name)
            
            if old_path != new_path:
                is_case_only = old_path.lower() == new_path.lower()
                
                if is_case_only:
                    temp_path = old_path + "_tmp_rename"
                    print(f"Case Folder Rename: {old_path} -> {new_path}")
                    os.rename(old_path, temp_path)
                    os.rename(temp_path, new_path)
                    renamed_dirs.append((old_path, new_path))
                else:
                    if os.path.exists(new_path):
                        # print(f"CONFLICT: {new_path} already exists. Skipping {old_path}")
                        continue
                    print(f"Folder Rename: {old_path} -> {new_path}")
                    os.rename(old_path, new_path)
                    renamed_dirs.append((old_path, new_path))
                    
    return renamed_dirs

if __name__ == "__main__":
    target = sys.argv[1] if len(sys.argv) > 1 else "lupo-docs"
    renamed = process_directories(target)
    print(f"\nTotal folders renamed in {target}: {len(renamed)}")
    mode = "a" if os.path.exists("renamed_folders_log.txt") else "w"
    with open("renamed_folders_log.txt", mode) as f:
        for old, new in renamed:
            f.write(f"{old} -> {new}\n")
