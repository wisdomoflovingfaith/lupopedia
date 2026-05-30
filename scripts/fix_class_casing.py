import os
import re

print("=== Fixing Class Casing Mismatches ===")

classes_dir = os.path.join('includes', 'classes')
if not os.path.exists(classes_dir):
    print(f"Error: {classes_dir} not found.")
    exit(1)

files_in_classes = os.listdir(classes_dir)
lower_to_actual = {f.lower(): f for f in files_in_classes if f.endswith('.php')}

found_casings = {}

# search all php files
for root, dirs, files in os.walk('.'):
    if 'node_modules' in root or '.git' in root:
        continue
    for filename in files:
        if filename.endswith('.php'):
            filepath = os.path.join(root, filename)
            try:
                with open(filepath, 'r', encoding='utf-8') as f:
                    content = f.read()
            except UnicodeDecodeError:
                with open(filepath, 'r', encoding='latin-1') as f:
                    content = f.read()
            
            # find matches like 'classes/pdo_db.php' or "classes/pdo_db.php"
            matches = re.findall(r'classes/([A-Za-z0-9_.-]+\.php)', content)
            for m in matches:
                found_casings[m.lower()] = m

renames = 0
for lower, exact_casing in found_casings.items():
    if lower in lower_to_actual:
        current_name = lower_to_actual[lower]
        if current_name != exact_casing:
            old_path = os.path.join(classes_dir, current_name)
            tmp_path = os.path.join(classes_dir, exact_casing + ".tmp_rename")
            new_path = os.path.join(classes_dir, exact_casing)
            
            print(f"Renaming physical file: '{current_name}' -> '{exact_casing}'")
            # Windows renaming case workaround: rename to temp, then to exact case
            os.rename(old_path, tmp_path)
            os.rename(tmp_path, new_path)
            renames += 1

print(f"\nDone! Restored perfectly matched casing for {renames} files based on Notepad++ string scans.")
