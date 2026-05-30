# lupopedia.headers:
#   when_updated: "20260324175617"
#   file_path_from_root: "lupo-scripts/audit_and_move_dev_tables.py"
#   questions_toon: null
#   channel_id: 42
#   actor_id: 102
#   actor_name: "cursor"
#   delegation_chain: "cursor:root"
#   artifact_type: "tooling"
#   artifact_kind: "script"
# lupopedia.footer:
#   last_verified: "20260324175617"
#   last_verified_by: "cursor"
#   last_verified_by_actor_id: 102

import os
import shutil
import re

# Paths
root_dir = r'C:\ServBay\www\servbay\lupopedia'
toon_dir = os.path.join(root_dir, 'lupo-database', 'lupopedia', 'toon')
docs_dir = os.path.join(root_dir, 'lupo-docs', 'database', 'lupopedia', 'tables', 'active')
dev_dir = os.path.join(docs_dir, 'development')

# Ensure development dir exists
if not os.path.exists(dev_dir):
    os.makedirs(dev_dir)

# Step 1: Gather all code contents (PHP and PY)
code_contents = []
for root, dirs, files in os.walk(root_dir):
    # Prune unwanted directories
    dirs[:] = [d for d in dirs if d not in ['.git', 'lupo-database', 'lupo-docs', '__pycache__', 'node_modules', 'vendor']]
    
    for file in files:
        if file.endswith(('.php', '.py')):
            full_path = os.path.join(root, file)
            try:
                with open(full_path, 'r', encoding='utf-8', errors='ignore') as f:
                    code_contents.append(f.read())
            except Exception as e:
                print(f"Error reading {full_path}: {e}")

all_code = "\n".join(code_contents)

# Step 2: Get all toons
toons = []
for file in os.listdir(toon_dir):
    if file.endswith('.toon'):
        # Extract table name from filename (e.g., lupo_actors.toon -> lupo_actors)
        table_name = file.replace('.toon', '')
        # Also double check line 1 of the file
        full_path = os.path.join(toon_dir, file)
        try:
            with open(full_path, 'r', encoding='utf-8') as f:
                first_line = f.readline().strip()
                if first_line.startswith('table_name:'):
                    table_name = first_line.split(':', 1)[1].strip()
        except:
            pass
        toons.append({'table': table_name, 'file': file})

# Step 3: Audit and Move
moved_count = 0
for item in toons:
    table = item['table']
    toon_file = item['file']
    
    # Check if table name is in code
    # We use a regex for word boundaries to avoid partial matches
    pattern = re.compile(r'\b' + re.escape(table) + r'\b')
    
    # For prefixed tables, check if the base name is used with a prefix variable
    # e.g. "{$prefix}actors" or "{$table_prefix}actors" or "' . TABLE_PREFIX . 'actors"
    base_name = table
    if table.startswith('lupo_'):
        base_name = table[5:]
    elif table.startswith('livehelp_'):
        base_name = table[9:]
    
    # Broad check for the table name itself
    found = pattern.search(all_code)
    
    # If not found directly, check for base name coupled with common prefix patterns
    if not found and base_name != table:
        # Search for things like prefix . 'base_name' or prefix . "base_name" or prefix . "$base_name"
        # Since we have the whole code string, we can do a broader regex
        prefix_pattern = re.compile(r'(prefix|PREFIX).*?[\'"]' + re.escape(base_name) + r'[\'"]', re.IGNORECASE)
        found = prefix_pattern.search(all_code)
        
    if not found:
        print(f"Table NOT referenced: {table}")
        
        # 1. Move TOON file
        src_toon = os.path.join(toon_dir, toon_file)
        dst_toon = os.path.join(dev_dir, toon_file)
        try:
            shutil.move(src_toon, dst_toon)
            print(f"  MOVED TOON: {toon_file}")
        except Exception as e:
            print(f"  Error moving TOON {toon_file}: {e}")
            
        # 2. Move Documentation MD file if it exists
        md_file = f"{table}.md"
        src_md = os.path.join(docs_dir, md_file)
        dst_md = os.path.join(dev_dir, md_file)
        if os.path.exists(src_md):
            try:
                shutil.move(src_md, dst_md)
                print(f"  MOVED MD: {md_file}")
            except Exception as e:
                print(f"  Error moving MD {md_file}: {e}")
        
        moved_count += 1

print(f"\nAudit complete. Total tables moved to development: {moved_count}")
