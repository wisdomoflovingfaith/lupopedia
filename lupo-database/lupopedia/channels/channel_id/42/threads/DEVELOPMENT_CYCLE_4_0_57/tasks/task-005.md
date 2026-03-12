# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) — see http://www.lupopedia.com/lupo-database/lupopedia/channels/lupo-channels/42/threads/DEVELOPMENT_CYCLE_4_0_57/tasks/task-005

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
      objective: "FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)"
    where:
      repo_paths: ["lupo-database/lupopedia/channels/lupo-channels/42/threads/DEVELOPMENT_CYCLE_4_0_57/tasks/task-005.md"]
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

lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "task"
  file_path_from_root: "lupo-database/lupopedia/channels/lupo-channels/42/threads/DEVELOPMENT_CYCLE_4_0_57/tasks/task-005.md"
  file_hash: "696951dabab0abff8d8c2607b285ed2c37ef43ed2d6dc23a32f1f5aeb0361621"
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

lupopedia.edges:
  outbound_edges: []

lupopedia.footer:
  last_verified: "20260304"
  last_verified_by: "antigravity"

lupopedia.see:
  mappings:
    - ["lupo-database/lupopedia/channels/lupo-channels/42/threads/DEVELOPMENT_CYCLE_4_0_57/tasks/task-005.md", "http://www.lupopedia.com/lupo-database/lupopedia/channels/lupo-channels/42/threads/DEVELOPMENT_CYCLE_4_0_57/tasks/task-005"]

lupopedia.close:
  post_actions:
    - type: register_completion
      channel_id: 0
  actor_id: 1004
---


# TASK-005: Map MD File Fields to DB Schema for Syncing Logic

---
wolfie.headers: {
  file_path_from_root: "lupo-database/lupopedia/channels/lupo-channels/42/threads/DEVELOPMENT_CYCLE_4_0_57/tasks/task-005.md",
  system_version: "4.0.55",
  channel_id: 42,
  actor_id: 1006,
  created_ymdhis: 20260302041900,
  updated_ymdhis: 20260302041900,
  message_type: "task",
  visibility: "public",
  priority: "normal"
}
---

## Description
Create a formal mapping between Markdown file paths/headers and optimized DB tables. This is critical for the sync tools that will eventually populate the consolidated database from the "offline" MD files.

## Details
- **Assigned Agent**: Antigravity
- **Status**: active
- **Version**: 4.0.55
- **Dependencies**: TASK-001, TASK-002
- **Success Criteria**: A mapping document (YAML or MD table) linking file patterns to SQL insert patterns.