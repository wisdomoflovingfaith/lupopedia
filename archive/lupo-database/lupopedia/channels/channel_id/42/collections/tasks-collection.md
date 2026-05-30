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
      objective: "Collection: Tasks"
    where:
      repo_paths: ["lupo-database\lupopedia\channels\lupo-channels\42\collections\tasks-collection.md"]
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
  file_path_from_root: "lupo-database/lupopedia/channels/channel_id/42/collections/tasks-collection.md"
  file_hash: "cf17e1ade16dcbffdab7094de704dea0f655854e94c049763ca37949ebfbb863"
  last_updated_utc: "20260304"
  system_version: "4.0.56"
  channel_id: 1
  actor_id: 1004
  delegation_chain: "1004:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Collection: Tasks"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.56"]
  tags: ["lupo-database", "lupopedia", "channels", "lupo-channels", "42", "collections"]
  lupo_agent: "antigravity"

lupopedia.edges:
  outbound_edges: []

lupopedia.footer:
  last_verified: "20260304"
  last_verified_by: "antigravity"

lupopedia.see:
  mappings:
    - ["lupo-database\lupopedia\channels\lupo-channels\42\collections\tasks-collection.md", "http://www.lupopedia.com/TASKS-COLLECTION"]

lupopedia.close:
  post_actions:
    - type: register_completion
      channel_id: 0
  actor_id: 1004
---

# Collection: Tasks

---
wolfie.headers: {
  file_path_from_root: "lupo-channels/42/collections/tasks-collection.md",
  system_version: "4.0.55",
  channel_id: 42,
  actor_id: 1006,
  created_ymdhis: 20260302041320,
  updated_ymdhis: 20260302041320,
  message_type: "collection",
  visibility: "public",
  priority: "normal"
}
---

## Description
The primary mission-control collection. Tracks all work items, assignments, and dependencies across the project.

## Associated Tables
### Primary (Active)
- `lupo_tasks`: The main task registry.

### Legacy (Merged/Dropped)
- `task_types`
- `task_statuses`
- `task_priorities`
(Consolidated into `lupo_tasks` as VARCHAR columns).

## Optimization & MD Representation
- **MD Mapping**: Each row in `lupo_tasks` directly corresponds to an MD file in `lupo-channels/42/tasks/active/`.
- **Future Goal**: Optimize dependency mapping (adjacency lists) to minimize recursive SQL queries.
