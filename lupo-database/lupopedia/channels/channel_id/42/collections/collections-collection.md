# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
lupopedia.init:
  requirements:
    flare:
      version: ">=4.0.55"
  execution_mode: "advisory"
  pre_actions:
    - type: dependency_check
      path: "lupo-includes/bootstrap.php"

lupopedia.conditional:
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
      objective: "Collection: Database Collections (Fallback Directory)"
    where:
      repo_paths: ["lupo-database\lupopedia\channels\lupo-channels\42\collections\collections-collection.md"]
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

lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-database\lupopedia\channels\lupo-channels\42\collections\collections-collection.md"
  file_hash: "0e518fe36787283715281a8cbb890b095db71c81c1d751e92d070ff058380afc"
  last_updated_utc: "20260304"
  system_version: "4.0.56"
  channel_id: 1
  actor_id: 1004
  delegation_chain: "1004:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Collection: Database Collections (Fallback Directory)"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.56"]
  tags: ["lupo-database", "lupopedia", "channels", "lupo-channels", "42", "collections"]
  lupo_agent: "antigravity"

lupopedia.edges:
  outbound_edges: []

lupopedia.footer:
  last_verified: "20260304"
  last_verified_by: "antigravity"

lupopedia.see:
  mappings:
    - ["lupo-database\lupopedia\channels\lupo-channels\42\collections\collections-collection.md", "http://www.lupopedia.com/COLLECTIONS-COLLECTION"]

lupopedia.close:
  post_actions:
    - type: register_completion
      channel_id: 0
  actor_id: 1004
---

# Collection: Database Collections (Fallback Directory)

This collection provides a directory of objects and their high-level mappings to the SQL database.

## Migration Path
New Path: `lupo-database/lupopedia/collections/`

## Key Assets
- `task-collections.md`: All TASK related data.
- `session-collections.md`: All SESSION related data.
- `logging-collections.md`: All LOGGING related data.
- `identity-collections.md`: All ACTOR and IDENTITY related data.
- `meta-collections.md`: All METADATA and FLARE related data.

## Table Mappings
- `lupo_tasks`
- `lupo_sessions`
- `lupo_unified_log`
- `lupo_actors`
- `lupo_anubis_queue` (meta data)

## Version
Created as part of Phase 2 for version 4.0.55.
