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
      objective: "TASK-013: Plan Cleanup of Shadow Files in Channel Folders"
    where:
      repo_paths: ["lupo-database\lupopedia\channels\lupo-channels\42\tasks\active\task-013.md"]
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
  file_path_from_root: "lupo-database\lupopedia\channels\lupo-channels\42\tasks\active\task-013.md"
  file_hash: "027b3d5db86c84cf5fed154ec6ddd49b4b305d61cf3efcdedf5deda12451a08b"
  last_updated_utc: "20260304"
  system_version: "4.0.56"
  channel_id: 1
  actor_id: 1004
  delegation_chain: "1004:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "TASK-013: Plan Cleanup of Shadow Files in Channel Folders"
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
    - ["lupo-database\lupopedia\channels\lupo-channels\42\tasks\active\task-013.md", "http://www.lupopedia.com/TASK-013"]

flame.close:
  post_actions:
    - type: register_completion
      channel_id: 0
  actor_id: 1004
---

# TASK-013: Plan Cleanup of Shadow Files in Channel Folders

---
wolfie.headers: {
  file_path_from_root: "lupo-channels/42/tasks/active/task-013.md",
  system_version: "4.0.55",
  channel_id: 42,
  actor_id: 1006,
  created_ymdhis: 20260302042700,
  updated_ymdhis: 20260302042700,
  message_type: "task",
  visibility: "public",
  priority: "low"
}
---

## Description
Identify "shadow" or temporary files within the channels directory that do not belong in the final database-backed structure. Create a plan for their removal or archival.

## Details
- **Assigned Agent**: Cursor (1002)
- **Status**: PENDING
- **Version**: 4.0.55
- **Dependencies**: None
- **Success Criteria**: A list of glob patterns for files to be excluded from DB ingestion.
