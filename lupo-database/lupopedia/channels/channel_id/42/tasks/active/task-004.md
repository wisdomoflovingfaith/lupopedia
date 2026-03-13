# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) — see http://www.lupopedia.com/lupo-database/lupopedia/channels/lupo-channels/42/tasks/active/task-004

---
lupopedia.init:
  requirements:
    flare:
      version: ">=4.0.73"
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
      objective: "FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)"
    where:
      repo_paths: ["lupo-database/lupopedia/channels/lupo-channels/42/tasks/active/task-004.md"]
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

lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "task"
  file_path_from_root: "lupo-database/lupopedia/channels/lupo-channels/42/tasks/active/task-004.md"
  file_hash: "ceabf46802f4d0e9a88980c1e317a0098f88fd02896cc8b248a150eab0eb559f"
  last_updated_utc: "20260304"
  system_version: "4.0.73"
  channel_id: 1
  actor_id: 1004
  delegation_chain: "1004:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.73"]
  tags: ["lupo-database", "lupopedia", "channels", "lupo-channels", "42", "tasks"]
  lupo_agent: "antigravity"

lupopedia.edges:
  outbound_edges: []

lupopedia.footer:
  last_verified: "20260304"
  last_verified_by: "antigravity"

lupopedia.see:
  mappings:
    - ["lupo-database/lupopedia/channels/lupo-channels/42/tasks/active/task-004.md", "http://www.lupopedia.com/lupo-database/lupopedia/channels/lupo-channels/42/tasks/active/task-004"]

lupopedia.close:
  post_actions:
    - type: register_completion
      channel_id: 0
  actor_id: 1004
---


# TASK-004: Design Universal Attribute Pattern for Actors and Sessions

---
wolfie.headers: {
  file_path_from_root: "lupo-channels/42/tasks/active/task-004.md",
  system_version: "4.0.73",
  channel_id: 42,
  actor_id: 1006,
  created_ymdhis: 20260302041800,
  updated_ymdhis: 20260302041800,
  message_type: "task",
  visibility: "public",
  priority: "normal"
}
---

## Description
Propose a "Universal Attribute" logic where additional actor/session properties (currently in separate tables or missing) are stored in a standard `attributes` JSON column. This follows the session recovery optimization pattern from Phase 1.

## Details
- **Assigned Agent**: Antigravity
- **Status**: PENDING
- **Version**: 4.0.55
- **Dependencies**: TASK-003
- **Success Criteria**: Data dictionary for common attributes and indexing plan.
