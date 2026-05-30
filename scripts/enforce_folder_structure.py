# lupopedia.headers:
#   when_updated: "20260324175617"
#   file_path_from_root: "scripts/enforce_folder_structure.py"
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
from pathlib import Path

def enforce_structure(base_path):
    channels_dir = Path(base_path) / "channels"
    artifacts_dir = Path(base_path) / "artifacts"
    
    # 1. Ensure channels/ structure
    if not channels_dir.exists():
        channels_dir.mkdir(parents=True)
        
    # Standard subfolders for each numeric channel
    subfolders = ["broadcasts", "threads", "actors", "directives"]
    
    # Standard top-level folders in channels/
    (channels_dir / "departments").mkdir(exist_ok=True)
    (channels_dir / "actors").mkdir(exist_ok=True)
    
    for item in channels_dir.iterdir():
        if item.is_dir() and item.name.isdigit():
            print(f"Checking channel {item.name}")
            for sub in subfolders:
                (item / sub).mkdir(exist_ok=True)
                
    # Ensure departments/ structure (minimal for now)
    # The user mentioned channels/departments/<department_name>/
    # I'll just check if it's there.
    
    # 2. Ensure artifacts/ structure
    if not artifacts_dir.exists():
        artifacts_dir.mkdir(parents=True)
        
    # We don't necessarily create artifact subfolders unless we have IDs.
    
    print("Directory structure enforcement complete.")

if __name__ == "__main__":
    enforce_structure("c:/ServBay/www/servbay/lupopedia")
