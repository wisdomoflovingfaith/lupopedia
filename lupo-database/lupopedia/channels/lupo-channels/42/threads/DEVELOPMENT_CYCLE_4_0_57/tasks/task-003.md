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
      objective: "TASK-003: Identify Candidate Tables for Phase 4 Consolidation"
    where:
      repo_paths: ["lupo-database/lupopedia/channels/lupo-channels/42/threads/DEVELOPMENT_CYCLE_4_0_57/tasks/task-003.md"]
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
  file_path_from_root: "lupo-database/lupopedia/channels/lupo-channels/42/threads/DEVELOPMENT_CYCLE_4_0_57/tasks/task-003.md"
  file_hash: "0f60faf44fdc97d40fad684420b186637dd2b42be0a334461e7ba07e0b2fc889"
  last_updated_utc: "20260304"
  system_version: "4.0.57"
  channel_id: 1
  actor_id: 1004
  delegation_chain: "1004:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "TASK-003: Identify Candidate Tables for Phase 4 Consolidation"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.57"]
  tags: ["lupo-database", "lupopedia", "channels", "lupo-channels", "42", "tasks"]
  lupo_agent: "antigravity"

flare.edges:
  outbound_edges: []

flare.footer:
  last_verified: "20260304"
  last_verified_by: "antigravity"

flame.see:
  mappings:
    - ["lupo-database/lupopedia/channels/lupo-channels/42/threads/DEVELOPMENT_CYCLE_4_0_57/tasks/task-003.md", "http://www.lupopedia.com/TASK-003"]

flame.close:
  post_actions:
    - type: register_completion
      channel_id: 0
  actor_id: 1004
---

# TASK-003: Identify Candidate Tables for Phase 4 Consolidation

---
wolfie.headers: {
  file_path_from_root: "lupo-database/lupopedia/channels/lupo-channels/42/threads/DEVELOPMENT_CYCLE_4_0_57/tasks/task-003.md",
  system_version: "4.0.55",
  channel_id: 42,
  actor_id: 1006,
  created_ymdhis: 20260302041700,
  updated_ymdhis: 20260302041700,
  message_type: "task",
  visibility: "public",
  priority: "high"
}
---

## Description
Review the remaining 210 tables and identify further candidates for consolidation. Focus on redundant lookup tables, sparse one-to-one relationships, and legacy logging tables missed in Phase 1.

## Details
- **Assigned Agent**: Gemini (1006)
- **Status**: active
- **Version**: 4.0.55
- **Dependencies**: None
- **Success Criteria**: A list of at least 5-10 additional tables that can be merged without data loss.
