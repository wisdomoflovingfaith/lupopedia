# lupopedia.headers:
#   when_updated: "20260324175617"
#   file_path_from_root: "lupo-scripts/init_all_actors.py"
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
import json

def create_actor_structure(actor_id, actor_type, slug):
    path = f"actors/{actor_id}"
    dirs = [
        "config", "channels", "logs", "history", "communications/inbox", 
        "communications/outbox", "communications/drafts", "tasks", 
        "state/tmp", "resources/files", "resources/assets", "meta", "performance", "www"
    ]
    
    for d in dirs:
        os.makedirs(os.path.join(path, d), exist_ok=True)
        
    # identity.json (DB parity)
    identity = {
        "schema_version": "4.0.47",
        "actor_id": str(actor_id),
        "actor_type": actor_type,
        "slug": slug,
        "name": slug.replace("-", " ").title(),
        "created_ymdhis": "20260227000000",
        "updated_ymdhis": "20260227000000"
    }
    with open(os.path.join(path, "identity.json"), 'w') as f:
        json.dump(identity, f, indent=2)
        
    # WHO.json
    who = {
        "schema_version": "4.0.47",
        "actor_id": str(actor_id),
        "whoami": {
            "identity": actor_type,
            "role": slug,
            "persona": f"The {slug} actor in the Lupopedia system.",
            "capabilities": ["basic_operations"],
            "status": "active"
        },
        "created_utc": "20260101",
        "last_updated_utc": "20260227"
    }
    with open(os.path.join(path, "WHO.json"), 'w') as f:
        json.dump(who, f, indent=2)
        
    # current_focus.json
    focus = {
        "schema_version": "4.0.47",
        "actor_id": str(actor_id),
        "current_tasks": [],
        "next_tasks": []
    }
    if not os.path.exists(os.path.join(path, "tasks/current_focus.json")):
        with open(os.path.join(path, "tasks/current_focus.json"), 'w') as f:
            json.dump(focus, f, indent=2)
            
    # resume.json
    resume = {
        "schema_version": "4.0.47",
        "actor_id": str(actor_id),
        "best_work": [],
        "skills_mastered": [],
        "total_contributions": 0
    }
    if not os.path.exists(os.path.join(path, "history/resume.json")):
        with open(os.path.join(path, "history/resume.json"), 'w') as f:
            json.dump(resume, f, indent=2)

    # meta/schema.json
    schema = {
        "version": "4.0.47",
        "db_sync": { "profile.json": "lupo_actors", "logs/*.ndjson": "lupo_logs" }
    }
    with open(os.path.join(path, "meta/schema.json"), 'w') as f:
        json.dump(schema, f, indent=2)

def main():
    with open("actors/registry.json", 'r') as rf:
        registry = json.load(rf)
    
    for actor in registry["actors"]:
        print(f"Initializing structure for actor {actor['id']}...")
        create_actor_structure(actor["id"], actor["type"], actor["slug"])

if __name__ == "__main__":
    main()
