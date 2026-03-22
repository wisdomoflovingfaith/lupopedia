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
      objective: "Thread: Channels Migration Directive 4.0.55"
    where:
      repo_paths: ["lupo-database\lupopedia\channels\lupo-channels\42\threads\DEVELOPMENT_CYCLE_4_0_55\thread-006-channels-migration-directive.md"]
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
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-database/lupopedia/channels/channel_id/42/threads/DEVELOPMENT_CYCLE_4_0_55/thread-006-channels-migration-directive.md"
  file_hash: "2a3a5cb691c49bc57f3b1d4ed7359ac0629c2112dc698cc58cc4394c8b2f38f4"
  last_updated_utc: "20260304"
  system_version: "4.0.56"
  channel_id: 1
  actor_id: 1004
  delegation_chain: "1004:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Thread: Channels Migration Directive 4.0.55"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.56"]
  tags: ["lupo-database", "lupopedia", "channels", "lupo-channels", "42", "threads"]
  lupo_agent: "antigravity"

lupopedia.edges:
  outbound_edges: []

lupopedia.footer:
  last_verified: "20260304"
  last_verified_by: "antigravity"

lupopedia.see:
  mappings:
    - ["lupo-database\lupopedia\channels\lupo-channels\42\threads\DEVELOPMENT_CYCLE_4_0_55\thread-006-channels-migration-directive.md", "http://www.lupopedia.com/THREAD-006-CHANNELS-MIGRATION-DIRECTIVE"]

lupopedia.close:
  post_actions:
    - type: register_completion
      channel_id: 0
  actor_id: 1004
---

# Thread: Channels Migration Directive 4.0.55
Channel: 43 (Decision Source) -> Channel 42 (Implementation)
Version: 4.0.55

## Overview
A new directive from channel 43 has instructed that the `lupo-database/` fallback system MUST include a **wholesale recursive migration** of the `lupo-channels/` directory and all its subfolders (tasks, plans, threads, collections, metadata, etc.). This ensures that for every channel, all its planning and communication artifacts are moved into the same unified, database-primary structure.

## Revised Decision Record
- **Full Recursive Move**: `lupo-channels/` is no longer a separate, top-level sibling. It is being relocated to `lupo-database/lupopedia/channels/`.
- **Constant Preservation**: `LUPO_CHANNELS_DIR` will continue to exist as a constant but will now point to the new nested location.
- **Link Integrity**: All internal MD links must be validated and updated. Relative paths are expected to remain functional after the move.
- **Back-up Requirement**: A full recursive copy of `lupo-channels/` must be created and verified before any move is attempted.

## Next Step
Execute the move using a recursive command (e.g., `cp -r` or `mv`) once PHASE 3 is officially launched. Planning is currently 100% complete within Channel 42.
