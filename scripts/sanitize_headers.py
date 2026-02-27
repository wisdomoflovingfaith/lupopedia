import os
import re

target_dir = r"c:\ServBay\www\servbay\lupopedia\docs\database\lupopedia\tables"

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

for filename in migration_files:
    filepath = os.path.join(target_dir, filename)
    if not os.path.exists(filepath):
        continue
        
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
        
    # Find the header region
    match = re.search(r'---(.*?)---', content, re.DOTALL)
    if not match:
        continue
        
    header = match.group(1)
    
    # Clean up the hashtags mistake if it happened
    if '"legacy-reference"' in header and 'hashtags:' in header:
        header = header.replace('"legacy-reference", ', '')
        header = header.replace(', "legacy-reference"', '')
        header = header.replace('"legacy-reference"', '')
        # Remove empty strings in lists like [, #tag]
        header = header.replace('[,', '[')
        header = header.replace(', ]', ']')
        
    # Ensure tags: ["legacy-reference"] exists
    # If there is a "tags:" key (standalone, not inside another word)
    if re.search(r'\btags:\s*\[', header):
        if 'legacy-reference' not in header:
            header = re.sub(r'(\btags:\s*\[)', r'\1"legacy-reference", ', header)
    elif re.search(r'\btags:', header):
        # YAML style tags
        if 'legacy-reference' not in header:
            header = re.sub(r'(\btags:)', r'\1\n  - legacy-reference', header)
    else:
        # Add tags key
        header += '\n  tags: ["legacy-reference"]'
        
    # Also ensure file_path_from_root is correct
    if 'file_path_from_root:' in header:
        header = re.sub(r'file_path_from_root:\s*".*?"', f'file_path_from_root: "docs/database/lupopedia/tables/{filename}"', header)
        header = re.sub(r'file_path_from_root:\s*docs/doctrine/migrations/.*', f'file_path_from_root: docs/database/lupopedia/tables/{filename}', header)
        
    new_content = "---" + header + "---" + content[match.end():]
    
    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(new_content)

print("Headers sanitized.")
