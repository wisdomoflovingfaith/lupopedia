# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flame.init:
  requirements:
    flare:
      version: ">=4.0.55"
  execution_mode: "advisory"
  pre_actions:
    - type: dependency_check
      path: "lupo-includes/bootstrap.php"

flare.conditional:
  guards:
    execution_mode: "advisory"
    allow:
      actor_ids: [0, 1004]
      agent_names: ["system", "antigravity"]
    deny:
      actor_ids: []
    time_window:
      not_before_utc: "2026-03-04T00:00:00Z"
      not_after_utc: "2026-03-11T00:00:00Z"
    conditions:
      - type: feature_flag_enabled
        flag: "FLAME_V1"
  brief:
    who:
      owner_actor_id: 1004
      intended_actors: [0, 1004]
      audience: ["agents"]
    what:
      artifact_type: "guide"
      objective: "TASK-012: Directory Migration (Actors/Content)"
    where:
      repo_paths: ["lupo-database\lupopedia\channels\lupo-channels\42\tasks\active\task-012-directory-migration.md"]
      runtime_scope: "cli"
      channels:
        primary_channel_id: 1
    when:
      urgency: "standard"
      effective_utc: "2026-03-04T10:08:32Z"
    why:
      rationale: "Standard artifact generation"
    how:
      method: "FLARE automated application"
      success_criteria: ["header applied correctly"]

flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: "lupo-database\lupopedia\channels\lupo-channels\42\tasks\active\task-012-directory-migration.md"
  file_hash: "7638f5e0ad8a917a475f71a2c31f56e664b065ec149e0e91655bb874358f8938"
  last_updated_utc: "20260304"
  system_version: "4.0.56"
  channel_id: 1
  actor_id: 1004
  delegation_chain: "1004:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "TASK-012: Directory Migration (Actors/Content)"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.56"]
  tags: ["lupo-database", "lupopedia", "channels", "lupo-channels", "42", "tasks"]
  lupo_agent: "antigravity"

flare.edges:
  outbound_edges: []

flare.footer:
  last_verified: "20260304"
  last_verified_by: "antigravity"

flame.see:
  mappings:
    - ["lupo-database\lupopedia\channels\lupo-channels\42\tasks\active\task-012-directory-migration.md", "http://www.lupopedia.com/TASK-012-DIRECTORY-MIGRATION"]

flame.close:
  post_actions:
    - type: register_completion
      channel_id: 0
  actor_id: 1004
---

# TASK-012: Directory Migration (Actors/Content)
Version: 4.0.55
Status: planned

## Description
Physically move the existing `lupo-actors/`, and `lupo-content/` directories into the new `lupo-database/lupopedia/` structure. This also includes creating the new `collections/`, `atoms/`, and `contents/` subfolders for the fallback database.

## List of Moves/Creations
- Move `lupo-actors/` → `lupo-database/lupopedia/actors/` (Recursive)
- Move `lupo-content/` → `lupo-database/lupopedia/content/` (Recursive)
- Create `lupo-database/lupopedia/collections/`
- Create `lupo-database/lupopedia/atoms/`
- Create `lupo-database/lupopedia/contents/`
- Create `lupo-database/lupopedia/metadata/`

## Proposed Config Snippet
- Already covered by TASK-011.

## Dependencies
- TASK-011: Config Constants Update
- TASK-014: Full Channels Recursive Migration

## Migration Notes
- Ensure path consistency and update any automated scripts (e.g., bin/lupo-cli) that reference these paths directly.
