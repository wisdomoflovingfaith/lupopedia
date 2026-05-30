#!/usr/bin/env python3
# lupopedia.headers:
#   when_updated: "20260324175617"
#   file_path_from_root: "scripts/create_artifact.py"
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

"""
Artifact creation with mandatory timestamp validation.
All artifact creation must use this script.
"""

import os
import sys
from datetime import datetime, timezone

# Import enforcement
sys.path.append(os.path.dirname(__file__))
from enforce_timestamp_validation import safe_write_file, TimestampEnforcementError

def create_artifact(actor_name, artifact_type, content, directory=""):
    """Create artifact with validated timestamp"""
    # Generate current UTC timestamp
    timestamp = datetime.now(timezone.utc).strftime("%Y%m%d_%H%M%S")
    
    # Create filename
    filename = f"{timestamp}_{actor_name}_{artifact_type}.md"
    
    # Create full path
    if directory:
        filepath = os.path.join(directory, filename)
    else:
        filepath = filename
    
    try:
        # Write with validation
        safe_write_file(filepath, content)
        print(f"Artifact created: {filepath}")
        return filepath
    except TimestampEnforcementError as e:
        print(f"FAILED: {e}")
        raise

if __name__ == "__main__":
    if len(sys.argv) < 4:
        print("Usage: python create_artifact.py <actor_name> <artifact_type> <content> [directory]")
        sys.exit(1)
    
    actor_name = sys.argv[1]
    artifact_type = sys.argv[2]
    content = sys.argv[3]
    directory = sys.argv[4] if len(sys.argv) > 4 else ""
    
    create_artifact(actor_name, artifact_type, content, directory)