# lupopedia.headers:
#   when_updated: "20260324175617"
#   file_path_from_root: "lupo-scripts/refine_headers.py"
#   last_modified_utc: "20260324175617"
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
        
    # Find the YAML block
    match = re.search(r'---(.*?)---', content, re.DOTALL)
    if not match:
        continue
        
    yaml_block = match.group(1)
    
    # 1. Remove stray tags added by previous script
    yaml_block = yaml_block.replace('tags: ["legacy-reference"]', '')
    
    # 2. Rename wolfie.headers to flare.headers
    yaml_block = yaml_block.replace('wolfie.headers:', 'flare.headers:')
    
    # 3. Handle structure inside flare.headers: { ... }
    h_match = re.search(r'flare\.headers:\s*{(.*?)}', yaml_block, re.DOTALL)
    if h_match:
        h_body = h_match.group(1)
        # Add legacy-reference to tags if they exist
        if 'tags:' in h_body:
            if '"legacy-reference"' not in h_body:
                h_body = re.sub(r'tags:\s*\[(.*?)\]', r'tags: ["legacy-reference", \1]', h_body)
        else:
            # Add tags key
            h_body = h_body.strip()
            if h_body.endswith(','):
                h_body += ' tags: ["legacy-reference"]'
            else:
                h_body += ', tags: ["legacy-reference"]'
        
        # Update path
        h_body = re.sub(r'file_path_from_root:\s*".*?"', f'file_path_from_root: "docs/database/lupopedia/tables/{filename}"', h_body)
        h_body = re.sub(r'file_path_from_root:\s*docs/doctrine/migrations/.*', f'file_path_from_root: docs/database/lupopedia/tables/{filename}', h_body)
        
        yaml_block = yaml_block[:h_match.start()] + f"flare.headers: {{{h_body}}}" + yaml_block[h_match.end():]
    else:
        # Standard YAML style flare.headers
        h_match = re.search(r'flare\.headers:.*?(?=\n\w|\n---|$)', yaml_block, re.DOTALL)
        if h_match:
            h_content = h_match.group(0)
            if "tags:" in h_content:
                if "legacy-reference" not in h_content:
                    h_content = re.sub(r'tags:\s*\[(.*?)\]', r'tags: ["legacy-reference", \1]', h_content)
            else:
                h_content += '\n  tags: ["legacy-reference"]'
            
            # Update path
            h_content = re.sub(r'file_path_from_root:\s*".*?"', f'file_path_from_root: "docs/database/lupopedia/tables/{filename}"', h_content)
            
            yaml_block = yaml_block[:h_match.start()] + h_content + yaml_block[h_match.end():]

    new_content = "---" + yaml_block + "---" + content[match.end():]
    
    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(new_content)

print("Headers refined to FLARE standards.")
