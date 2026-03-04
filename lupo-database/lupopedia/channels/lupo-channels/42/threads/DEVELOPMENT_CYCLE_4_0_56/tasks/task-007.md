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
      objective: "TASK-007: Update TOON Projections for Consolidated Tables"
    where:
      repo_paths: ["lupo-database\lupopedia\channels\lupo-channels\42\threads\DEVELOPMENT_CYCLE_4_0_56\tasks\task-007.md"]
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
  file_path_from_root: "lupo-database\lupopedia\channels\lupo-channels\42\threads\DEVELOPMENT_CYCLE_4_0_56\tasks\task-007.md"
  file_hash: "9f8433258aabde8595a02c81c27487447599e8cd16f972ddd040cc08431fb6a0"
  last_updated_utc: "20260304"
  system_version: "4.0.56"
  channel_id: 1
  actor_id: 1004
  delegation_chain: "1004:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "TASK-007: Update TOON Projections for Consolidated Tables"
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
    - ["lupo-database\lupopedia\channels\lupo-channels\42\threads\DEVELOPMENT_CYCLE_4_0_56\tasks\task-007.md", "http://www.lupopedia.com/TASK-007"]

flame.close:
  post_actions:
    - type: register_completion
      channel_id: 0
  actor_id: 1004
---

# TASK-007: Update TOON Projections for Consolidated Tables

---
wolfie.headers: {
  file_path_from_root: "lupo-channels/42/tasks/active/task-007.md",
  system_version: "4.0.55",
  channel_id: 42,
  actor_id: 1006,
  created_ymdhis: 20260302042100,
  updated_ymdhis: 20260302042100,
  message_type: "task",
  visibility: "public",
  priority: "normal"
}
---

## Description
Draft future-state TOON files (in MD format) for the next set of consolidated tables. These will serve as the spec for the Phase 3 migration scripts.

## Details
- **Assigned Agent**: Gemini (1006)
- **Status**: PENDING
- **Version**: 4.0.55
- **Dependencies**: TASK-003, TASK-004
- **Success Criteria**: 3-5 draft JSON/MD structures for consolidated tables (e.g., `lupo_unified_actor_meta`).
