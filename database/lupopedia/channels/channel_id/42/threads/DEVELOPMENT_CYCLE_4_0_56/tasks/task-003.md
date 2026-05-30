# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) — see http://www.lupopedia.com/database/lupopedia/channels/channels/42/threads/DEVELOPMENT_CYCLE_4_0_56/tasks/task-003

---
lupopedia.init:
  requirements:
    flare:
      version: ">=4.0.55"
  execution_mode: "advisory"
  pre_actions:
    - type: dependency_check
      path: "includes/bootstrap.php"

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
      repo_paths: ["database/lupopedia/channels/channels/42/threads/DEVELOPMENT_CYCLE_4_0_56/tasks/task-003.md"]
      runtime_scope: "cli"
      channels:
        primary_channel_id: 1
    when:
      urgency: "standard"
      effective_utc: "2026-03-04T14:39:31Z"
    why:
      rationale: "Standard artifact generation"
    how:
      method: "FLARE automated application"
      success_criteria: ["header applied correctly"]

lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "task"
  file_path_from_root: "database/lupopedia/channels/channel_id/42/threads/DEVELOPMENT_CYCLE_4_0_56/tasks/task-003.md"
  file_hash: "ff4c065604d9a7ce1d27eba9390f2fc6f0f1a42190af2040b512b747e99da8b9"
  last_updated_utc: "20260304"
  system_version: "4.0.56"
  channel_id: 1
  actor_id: 1004
  delegation_chain: "1004:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.56"]
  tags: ["database", "lupopedia", "channels", "channels", "42", "threads"]
  lupo_agent: "antigravity"

lupopedia.edges:
  outbound_edges: []

lupopedia.footer:
  last_verified: "20260304"
  last_verified_by: "antigravity"

lupopedia.see:
  mappings:
    - ["database/lupopedia/channels/channels/42/threads/DEVELOPMENT_CYCLE_4_0_56/tasks/task-003.md", "http://www.lupopedia.com/database/lupopedia/channels/channels/42/threads/DEVELOPMENT_CYCLE_4_0_56/tasks/task-003"]

lupopedia.close:
  post_actions:
    - type: register_completion
      channel_id: 0
  actor_id: 1004
---


# TASK-003: Identify Candidate Tables for Phase 4 Consolidation

---
wolfie.headers: {
  file_path_from_root: "channels/42/tasks/active/task-003.md",
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
- **Status**: PENDING
- **Version**: 4.0.55
- **Dependencies**: None
- **Success Criteria**: A list of at least 5-10 additional tables that can be merged without data loss.
