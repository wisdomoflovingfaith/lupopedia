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
      objective: "Collection: Channels (Fallback Migration)"
    where:
      repo_paths: ["lupo-database\lupopedia\channels\lupo-channels\42\collections\channels-collection.md"]
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
  file_path_from_root: "lupo-database\lupopedia\channels\lupo-channels\42\collections\channels-collection.md"
  file_hash: "b43398c3ed39f44c631a380563ae1dc620638552c1066fc3b203cda475005760"
  last_updated_utc: "20260304"
  system_version: "4.0.56"
  channel_id: 1
  actor_id: 1004
  delegation_chain: "1004:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Collection: Channels (Fallback Migration)"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.56"]
  tags: ["lupo-database", "lupopedia", "channels", "lupo-channels", "42", "collections"]
  lupo_agent: "antigravity"

flare.edges:
  outbound_edges: []

flare.footer:
  last_verified: "20260304"
  last_verified_by: "antigravity"

flame.see:
  mappings:
    - ["lupo-database\lupopedia\channels\lupo-channels\42\collections\channels-collection.md", "http://www.lupopedia.com/CHANNELS-COLLECTION"]

flame.close:
  post_actions:
    - type: register_completion
      channel_id: 0
  actor_id: 1004
---

# Collection: Channels (Fallback Migration)

This collection documents the full move of all channel-related artifacts into the `lupo-database/` directory.

## Migration Path
Original Path: `lupo-channels/` (All files and subfolders)
Fallback Path: `lupo-database/lupopedia/channels/` (Recursive preservation)

## Key Subfolder Mapping
The entire tree is moved, keeping internal relationships intact:
- `lupo-database/lupopedia/channels/<id>/tasks/`: All MD tasks.
- `lupo-database/lupopedia/channels/<id>/plans/`: All MD plans.
- `lupo-database/lupopedia/channels/<id>/threads/`: All MD threads.
- `lupo-database/lupopedia/channels/<id>/collections/`: Channel-specific object collections.
- `lupo-database/lupopedia/channels/<id>/metadata/`: FLARE routing and relationship maps.

## Risks & Mitigations
- **Path Breakage**: Fixed-string paths like `lupo-channels/42/tasks/task-001.md` will break.
- **Solution**: These MUST be updated to either relative links `tasks/task-001.md` or use the new `LUPO_CHANNELS_DIR` constant for resolution.

## Table Mappings
- `lupo_channels`
- `lupo_channel_state`
- `lupo_channel_content`
- `lupo_broadcasts`
- `lupo_unified_log` (channel-specific logs)

## Version
Created as part of Phase 2 for version 4.0.55.
