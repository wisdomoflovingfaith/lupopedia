# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) — see http://www.lupopedia.com/lupo-database/lupopedia/channels/lupo-channels/42/threads/DEVELOPMENT_CYCLE_4_0_57/tasks/task-010-fallback-database

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
      repo_paths: ["lupo-database/lupopedia/channels/lupo-channels/42/threads/DEVELOPMENT_CYCLE_4_0_57/tasks/task-010-fallback-database.md"]
      runtime_scope: "cli"
      channels:
        primary_channel_id: 1
    when:
      urgency: "standard"
      effective_utc: "2026-03-04T14:39:37Z"
    why:
      rationale: "Standard artifact generation"
    how:
      method: "FLARE automated application"
      success_criteria: ["header applied correctly"]

flare.headers:
  flare.version: "1.0"
  flare.schema: "task"
  file_path_from_root: "lupo-database/lupopedia/channels/lupo-channels/42/threads/DEVELOPMENT_CYCLE_4_0_57/tasks/task-010-fallback-database.md"
  file_hash: "61a3a826beadb477a3c4f9190767fddfba60f7070f4ae02cac059c9a898408ec"
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
  tags: ["lupo-database", "lupopedia", "channels", "lupo-channels", "42", "threads"]
  lupo_agent: "antigravity"

flare.edges:
  outbound_edges: []

flare.footer:
  last_verified: "20260304"
  last_verified_by: "antigravity"

flame.see:
  mappings:
    - ["lupo-database/lupopedia/channels/lupo-channels/42/threads/DEVELOPMENT_CYCLE_4_0_57/tasks/task-010-fallback-database.md", "http://www.lupopedia.com/lupo-database/lupopedia/channels/lupo-channels/42/threads/DEVELOPMENT_CYCLE_4_0_57/tasks/task-010-fallback-database"]

flame.close:
  post_actions:
    - type: register_completion
      channel_id: 0
  actor_id: 1004
---


# TASK-010: File/Folder/CSV Database Fallback System Implementation
Version: 4.0.55
Status: active

## Description
Introduce a file-based fallback database system that allows the application to read and write from Markdown and CSV files when the primary database (PDO/SQL) is unavailable. This ensures the 210+ optimized tables remain accessible as "Root Truth" files in the `lupo-database/` directory.

## Updated Requirements
- Fully relocate `lupo-channels/` and all its subfolders to `lupo-database/lupopedia/channels/`.
- All planning, tasks, collections, plans, and threads must be moved recursively to avoid data loss or structure fragmentation.

## Proposed Config Snippet
```php
define('LUPO_DATABASE_DIR', LUPO_PREFIX . 'database');
```

## Proposed Structure
- `lupo-database/`
  - `lupopedia/`
    - `channels/` (Moved from `lupo-channels/`)
    - `actors/` (Moved from `lupo-actors/`)
    - `content/` (Moved from `lupo-content/`)
    - `collections/` (Integrated Sub-nesting)
    - `atoms/` (New)
    - `contents/` (Backup)

## Dependencies
- TASK-011: Config Constants Update
- TASK-014: Full Channels Recursive Migration

## Files/Directories to be Moved/Created
- `lupo-database/` (directory creation)
- `lupopedia-config.php` (update)
- `lupo-channels/` (wholesale recursive move)