import os
import re

# The targeted directories that were heavily used in docs
target_dirs = [
    "admin", "admin_sections", "api", "backups", "cache", "images", "install", 
    "meta", "prompts", "scripts", "templates", "tests", "tmp", "tools", "uploads", "views"
]

# Note: docs, database, includes, agents, actors, rules, channels were renamed previously
# but we can check them too if needed. Let's stick to the 17 listed in the recent P1 tasks 
# minus legacy (which we might want to keep if legacy wasn't renamed to lupo-legacy).
# Wait, let's check legacy.
target_dirs_extended = target_dirs + [
    "docs", "database", "includes", "agents", "actors", "rules", "channels", "legacy"
]

def analyze_markdown_paths():
    found_issues = []
    
    # Path pattern: look for paths like "scripts/run_tests.sh", "docs/status/...", "`admin/`"
    # Negative lookbehind to avoid matching "lupo-scripts/" or "my_scripts/"
    pattern = re.compile(r'(?<![A-Za-z0-9_-])(' + '|'.join(target_dirs_extended) + r')([/\\])')
    
    for root, dirs, files in os.walk('.'):
        if any(skip in root for skip in ['.git', '.cursor', 'node_modules', 'legacy']):
            continue
            
        for file in files:
            if not file.endswith('.md'): continue
            
            filepath = os.path.join(root, file)
            try:
                with open(filepath, 'r', encoding='utf-8') as f:
                    content = f.read()
            except UnicodeDecodeError:
                continue
                
            matches = pattern.finditer(content)
            for match in matches:
                # Get some context around the match
                start = max(0, match.start() - 20)
                end = min(len(content), match.end() + 40)
                context = content[start:end].replace('\n', ' ')
                
                found_issues.append({
                    'file': filepath,
                    'dir': match.group(1),
                    'context': context,
                    'match_string': match.group(0)
                })
                
    return found_issues

issues = analyze_markdown_paths()
print(f"Found {len(issues)} possible un-prefixed directory references.")

# Group by file
by_file = {}
for issue in issues:
    f = issue['file']
    by_file.setdefault(f, []).append(issue['dir'] + issue['match_string'][-1])

for f, dirs in list(by_file.items())[:20]:
    print(f"{f}: {set(dirs)}")
