import os
import re
import json

# Target directory for migration files
target_dir = r"c:\ServBay\www\servbay\lupopedia\docs\database\lupopedia\tables"
toons_dir = r"c:\ServBay\www\servbay\lupopedia\docs\toons"
source_dir = r"c:\ServBay\www\servbay\lupopedia\docs\doctrine\migrations"

migration_files = [
    "MIGRATION_MAPPING_REFERENCE.md",
    "livehelp_autoinvite_migration.md",
    "livehelp_channels_migration.md",
    "livehelp_config_migration.md",
    "livehelp_departments_migration.md",
    "livehelp_emailque_migration.md",
    "livehelp_emails_migration.md",
    "livehelp_identity_migration.md",
    "livehelp_keywords_migration.md",
    "livehelp_layerinvites_migration.md",
    "livehelp_leads_migration.md",
    "livehelp_leavemessage_migration.md",
    "livehelp_messages_migration.md",
    "livehelp_modules_dep_migration.md",
    "livehelp_modules_migration.md",
    "livehelp_operator_channels_migration.md",
    "livehelp_operator_departments_migration.md",
    "livehelp_operator_history_migration.md",
    "livehelp_paths_firsts_migration.md",
    "livehelp_qa_migration.md",
    "livehelp_questions_migration.md",
    "livehelp_quick_migration.md",
    "livehelp_referers_daily_migration.md",
    "livehelp_sessions_migration.md",
    "livehelp_smilies_migration.md",
    "livehelp_transcripts_migration.md",
    "livehelp_users_migration.md",
    "livehelp_visit_track_migration.md",
    "livehelp_websites_migration.md",
    "operator_to_roles_migration.md"
]

warning_text = """## WARNING: Legacy Reference Only

These database tables should never be used in the new Lupopedia system. They exist just for reference on what the old Crafty Syntax system's database tables contained and how they map to the new tables. All legacy tables will not exist in version 4.1.1+ of Lupopedia."""

toon_warning_text = "NOTE: The tables described here are just for reference on how the old Crafty Syntax system worked. As we develop the new Lupopedia system using the mapped new tables to replicate all functionality of Crafty Syntax, these legacy tables will not exist in version 4.1.1+."

# Ensure target directory exists
if not os.path.exists(target_dir):
    os.makedirs(target_dir)

# 1. Relocate and modify migration files
for filename in migration_files:
    src_path = os.path.join(source_dir, filename)
    dst_path = os.path.join(target_dir, filename)
    
    # If it's already in destination, use that. Otherwise move it.
    if os.path.exists(src_path):
        print(f"Relocating {filename}...")
        os.rename(src_path, dst_path)
    elif not os.path.exists(dst_path):
        print(f"Warning: {filename} not found in source or destination.")
        continue
    
    with open(dst_path, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # 2. Insert warning section
    # Check if warning already exists
    if "## WARNING: Legacy Reference Only" not in content:
        # Find position to insert: after flare header if exists, or at top
        match = re.search(r'---.*?---', content, re.DOTALL)
        if match:
            header_end = match.end()
            content = content[:header_end] + "\n\n" + warning_text + "\n" + content[header_end:]
        else:
            content = warning_text + "\n\n" + content
            
    # 3. Update tags and path in flare header
    match = re.search(r'(wolfie\.headers|flare\.headers):\s*{(.*?)}', content, re.DOTALL)
    if match:
        header_type = match.group(1)
        header_body = match.group(2)
        
        # Update file_path_from_root
        header_body = re.sub(r'file_path_from_root:\s*".*?"', f'file_path_from_root: "docs/database/lupopedia/tables/{filename}"', header_body)
        header_body = re.sub(r'file_path_from_root:\s*docs/doctrine/migrations/.*', f'file_path_from_root: docs/database/lupopedia/tables/{filename}', header_body)
        
        # Update tags
        if "tags:" in header_body:
            if '"legacy-reference"' not in header_body:
                header_body = re.sub(r'tags:\s*\[(.*?)\]', r'tags: ["legacy-reference", \1]', header_body)
        else:
            header_body += f', tags: ["legacy-reference"]'
            
        content = content[:match.start()] + f"{header_type}: {{{header_body}}}" + content[match.end():]
    else:
        # If no structured header but a YAML block
        match = re.search(r'---(.*?)---', content, re.DOTALL)
        if match:
            yaml_body = match.group(1)
            if "file_path_from_root:" in yaml_body:
                yaml_body = re.sub(r'file_path_from_root:\s*".*?"', f'file_path_from_root: "docs/database/lupopedia/tables/{filename}"', yaml_body)
                yaml_body = re.sub(r'file_path_from_root:\s*docs/doctrine/migrations/.*', f'file_path_from_root: docs/database/lupopedia/tables/{filename}', yaml_body)
            else:
                yaml_body += f'\nfile_path_from_root: "docs/database/lupopedia/tables/{filename}"'
            
            if "tags:" in yaml_body:
                if "legacy-reference" not in yaml_body:
                    yaml_body = re.sub(r'tags:\s*\[(.*?)\]', r'tags: ["legacy-reference", \1]', yaml_body)
                    yaml_body = re.sub(r'tags:\s*\n(\s*)-\s*(.*?)', r'tags:\n\1- legacy-reference\n\1- \2', yaml_body)
            else:
                yaml_body += '\ntags: ["legacy-reference"]'
            
            # Update updated_ymdhis to current if it's there
            import datetime
            now_ymdhis = datetime.datetime.now().strftime("%Y%m%d%H%M%S")
            # yaml_body = re.sub(r'updated_ymdhis:.*', f'updated_ymdhis: {now_ymdhis}', yaml_body)
            
            content = "---" + yaml_body + "---" + content[match.end():]

    with open(dst_path, 'w', encoding='utf-8') as f:
        f.write(content)

# 4. Modify Toons Files
for filename in os.listdir(toons_dir):
    if filename.endswith(".toon.json"):
        # The prompt says ONLY for legacy reference. 
        # I'll apply it to all that are old (livehelp_*) or as per the prompt "every file"?
        # The prompt says "For every MD file... As we develop the new Lupopedia system... these legacy tables will not exist".
        # This implies it should be applied to legacy ones.
        # But if I follow "For each file" literally, I should do all.
        # However, it mentions "legacy tables will not exist".
        # So I'll do it for livehelp_* files.
        if filename.startswith("livehelp_"):
            filepath = os.path.join(toons_dir, filename)
            with open(filepath, 'r', encoding='utf-8') as f:
                try:
                    data = json.load(f)
                    data["reference_note"] = toon_warning_text
                    
                    # Also, if there's no metadata, the user mentioned adding a FLARE header. 
                    # But for JSON, we just add the key.
                    
                    with open(filepath, 'w', encoding='utf-8') as fw:
                        json.dump(data, fw, indent=2)
                except json.JSONDecodeError:
                    print(f"Error decoding JSON in {filename}")

print("Modification complete.")
