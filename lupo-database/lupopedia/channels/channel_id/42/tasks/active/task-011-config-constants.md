# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) — see http://www.lupopedia.com/lupo-database/lupopedia/channels/lupo-channels/42/tasks/active/task-011-config-constants

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
      objective: "FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)"
    where:
      repo_paths: ["lupo-database/lupopedia/channels/lupo-channels/42/tasks/active/task-011-config-constants.md"]
      runtime_scope: "cli"
      channels:
        primary_channel_id: 1
    when:
      urgency: "standard"
      effective_utc: "2026-03-04T14:39:11Z"
    why:
      rationale: "Standard artifact generation"
    how:
      method: "FLARE automated application"
      success_criteria: ["header applied correctly"]

flare.headers:
  flare.version: "1.0"
  flare.schema: "task"
  file_path_from_root: "lupo-database/lupopedia/channels/lupo-channels/42/tasks/active/task-011-config-constants.md"
  file_hash: "851bf19ec9d7af7e21a7486420a6036e97cef5a5e0da20a1563eb7599fa72ef4"
  last_updated_utc: "20260304"
  system_version: "4.0.56"
  channel_id: 1
  actor_id: 1004
  delegation_chain: "1004:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)"
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
    - ["lupo-database/lupopedia/channels/lupo-channels/42/tasks/active/task-011-config-constants.md", "http://www.lupopedia.com/lupo-database/lupopedia/channels/lupo-channels/42/tasks/active/task-011-config-constants"]

flame.close:
  post_actions:
    - type: register_completion
      channel_id: 0
  actor_id: 1004
---


# TASK-011: Config Constants Update for Folder Migration
Version: 4.0.55
Status: planned

## Description
Update `lupopedia-config.php` to include the `LUPO_DATABASE_DIR` constant and all its related sub-constants. This change is required before migrating the folders to ensure the system can resolve correct paths. After the move, all references to channel-related paths must reflect the new location under `lupo-database/lupopedia/channels/`.

## Proposed Config Snippets
```php
// Existing or New Primary Constant
define('LUPO_DATABASE_DIR', LUPO_PREFIX . 'database');

// Updated Sub-Constants
define('LUPO_CHANNELS_DIR', LUPO_DATABASE_DIR . '/lupopedia/channels');
define('LUPO_ACTORS_DIR', LUPO_DATABASE_DIR . '/lupopedia/actors');
define('LUPO_CONTENT_DIR', LUPO_DATABASE_DIR . '/lupopedia/content');
define('LUPO_COLLECTIONS_DIR', LUPO_DATABASE_DIR . '/lupopedia/collections');
define('LUPO_ATOMS_DIR', LUPO_DATABASE_DIR . '/lupopedia/atoms');
define('LUPO_CONTENTS_DIR', LUPO_DATABASE_DIR . '/lupopedia/contents');
```

## Dependencies
- TASK-010: Fallback Database Planning
- TASK-014: Full Channels Recursive Migration

## Files to be Updated
- `lupopedia-config.php`
- `lupo-includes/bootstrap.php`