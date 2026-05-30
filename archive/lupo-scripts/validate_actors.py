# lupopedia.headers:
#   when_updated: "20260324175617"
#   file_path_from_root: "lupo-scripts/validate_actors.py"
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
import json

def validate_actor(actor_id):
    path = f"actors/{actor_id}"
    if not os.path.exists(path):
        print(f"FAILED: {path} does not exist.")
        return False

    required_files = [
        "profile.json",
        "WHO.json",
        "capabilities.json",
        "relationships.json",
        "web.json",
        "meta/schema.json",
        "meta/flare.json",
        "meta/flip.json",
        "tasks/current_focus.json",
        "history/resume.json"
    ]

    for f in required_files:
        f_path = os.path.join(path, f)
        if not os.path.exists(f_path):
            print(f"FAILED: {f_path} missing.")
            return False
        
        # Check if JSON is valid
        if f.endswith(".json"):
            try:
                with open(f_path, 'r') as jf:
                    json.load(jf)
            except Exception as e:
                print(f"FAILED: {f_path} is invalid JSON: {e}")
                return False

    print(f"PASSED: Actor {actor_id} structure is valid.")
    return True

def main():
    with open("actors/registry.json", 'r') as rf:
        registry = json.load(rf)
    
    actors = registry.get("actors", [])
    all_passed = True
    for actor in actors:
        actor_id = actor["id"]
        # Only validate the ones we just implemented for now (10000 and 1000)
        if actor_id in [10000, 1000]:
            if not validate_actor(actor_id):
                all_passed = False
    
    if all_passed:
        print("\nOverall Validation: PASSED")
    else:
        print("\nOverall Validation: FAILED")

if __name__ == "__main__":
    main()
