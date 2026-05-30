# lupopedia.headers:
#   when_updated: "20260324175617"
#   file_path_from_root: "scripts/apply_md_path_fixes.py"
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
import re

directories = [
    "admin", "admin_sections", "api", "backups", "cache", "images", 
    "install", "meta", "prompts", "scripts", "templates", "tests", 
    "tmp", "tools", "uploads", "views",
    "docs", "database", "includes", "agents", "actors", "rules", "channels", "legacy"
]

# We need a regex that precisely identifies these as path prefixes.
# Examples to match:
# - scripts/run_tests.sh
# - admin/index.php
# - `docs/status/plan.md`
# - [Link](docs/status/plan.md)
# Examples NOT to match:
# - https://example.com/api/
# - However, since URLs to project files often use relative paths, we need to be careful.
# - Actually, Lupopedia is mostly self-contained. Let's just avoid "http://..." or "https://..."

def replace_in_file(filepath):
    try:
        with open(filepath, 'r', encoding='utf-8') as f:
            content = f.read()
    except:
        return 0
        
    original = content
    
    for d in directories:
        # We look for word boundary, the directory name, then a slash or backslash
        # The lookbehind ensures not prefixed by alphanumeric, -, _, or / (to avoid mid-path matches)
        # Note: we want to allow ` or " or [ or space before it.
        # But we DO NOT want to match if it's preceded by "http" or ".com" to avoid URLs,
        # Though lupopedia urls might actually need the  prefix now. The user said: "verify the directory name changes all have the  prefix for the folder names in the md documentation and docutrine"
        
        # We will use this regex:
        # negative lookbehind for [A-Za-z0-9_.-] and / and \ 
        # So it must be start of line, space, quote, bracket, backtick, etc.
        pattern = r'(?<![A-Za-z0-9_.\\/-])' + d + r'([/\\])'
        replacement = r'' + d + r'\1'
        
        content = re.sub(pattern, replacement, content)

    if content != original:
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content)
        return 1
    return 0

changed_files = 0
for root, dirs, files in os.walk('.'):
    # skip .git, .cursor, node_modules, legacy (though legacy doesn't have markdown but still)
    if any(skip in root for skip in ['.git', '.cursor', 'node_modules', 'legacy']):
        continue
        
    for file in files:
        if file.endswith('.md') or file.endswith('.txt') or file.endswith('.sql'):
            # Only do this for .md to be safe, maybe .txt? Let's just do .md to match the prompt
            if not file.endswith('.md'):
                continue
            filepath = os.path.join(root, file)
            changed_files += replace_in_file(filepath)

print(f"Updated {changed_files} files.")
