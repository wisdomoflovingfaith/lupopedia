import os
import re

tasks_dir = r"c:\ServBay\www\servbay\lupopedia\lupo-database\lupopedia\channels\lupo-channels\42\threads\DEVELOPMENT_CYCLE_4_0_57\tasks"

for filename in os.listdir(tasks_dir):
    if not filename.endswith(".md"):
        continue
    
    filepath = os.path.join(tasks_dir, filename)
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # Update system_version in YAML
    content = re.sub(r'system_version: "4\.0\.56"', 'system_version: "4.0.57"', content)
    
    # Update traits
    content = re.sub(r'traits: \[(.*)"v4\.0\.56"(.*)\]', r'traits: [\1"v4.0.57"\2]', content)
    
    # Update file_path_from_root (FLARE/Wolfie)
    new_root_path = f"lupo-database/lupopedia/channels/lupo-channels/42/threads/DEVELOPMENT_CYCLE_4_0_57/tasks/{filename}"
    content = re.sub(r'file_path_from_root: ".*"', f'file_path_from_root: "{new_root_path}"', content)
    
    # Update Status to active if not complete (Markdown body)
    if "Status**: complete" not in content and "Status**: COMPLETE" not in content:
         content = re.sub(r'Status\*\*: .*', 'Status**: active', content)
         content = re.sub(r'status\*\*: .*', 'status**: active', content)
         content = re.sub(r'Status: .*', 'Status: active', content)

    # Update repo_paths in brief
    content = re.sub(r'repo_paths: \["lupo-database\\lupopedia\\channels\\lupo-channels\\42\\tasks\\active\\(.*)"\]', 
                     rf'repo_paths: ["lupo-database/lupopedia/channels/lupo-channels/42/threads/DEVELOPMENT_CYCLE_4_0_57/tasks/\1"]', content)

    # Update flame.see mappings
    content = re.sub(r'\["lupo-database\\lupopedia\\channels\\lupo-channels\\42\\tasks\\active\\(.*)", "(.*)"\]', 
                     rf'["lupo-database/lupopedia/channels/lupo-channels/42/threads/DEVELOPMENT_CYCLE_4_0_57/tasks/\1", "\2"]', content)

    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(content)

print("Task migration script v2 completed.")
