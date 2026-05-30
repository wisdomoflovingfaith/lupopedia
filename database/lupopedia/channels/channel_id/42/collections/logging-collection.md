# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

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
      objective: "Collection: Logging System"
    where:
      repo_paths: ["database\lupopedia\channels\channels\42\collections\logging-collection.md"]
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
  file_path_from_root: "database/lupopedia/channels/channel_id/42/collections/logging-collection.md"
  file_hash: "6bb1b8ad6ec4b4508b806e98f95629d938a906c78018286ae97278fc04a771cc"
  last_updated_utc: "20260304"
  system_version: "4.0.56"
  channel_id: 1
  actor_id: 1004
  delegation_chain: "1004:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Collection: Logging System"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.56"]
  tags: ["database", "lupopedia", "channels", "channels", "42", "collections"]
  lupo_agent: "antigravity"

lupopedia.edges:
  outbound_edges: []

lupopedia.footer:
  last_verified: "20260304"
  last_verified_by: "antigravity"

lupopedia.see:
  mappings:
    - ["database\lupopedia\channels\channels\42\collections\logging-collection.md", "http://www.lupopedia.com/LOGGING-COLLECTION"]

lupopedia.close:
  post_actions:
    - type: register_completion
      channel_id: 0
  actor_id: 1004
---

# Collection: Logging System

---
wolfie.headers: {
  file_path_from_root: "channels/42/collections/logging-collection.md",
  system_version: "4.0.55",
  channel_id: 42,
  actor_id: 1006,
  created_ymdhis: 20260302041300,
  updated_ymdhis: 20260302041300,
  message_type: "collection",
  visibility: "public",
  priority: "normal"
}
---

## Description
Centralized logging collection for system events, task logs, and actor behaviors. Phase 1 successfully consolidated the majority of these into a unified structure.

## Associated Tables
### Primary (Active)
- `lupo_unified_log`: The consolidated sink for all system and event data.

### Legacy (Merged/Dropped)
- `lupo_system_logs`
- `lupo_system_events`
- `lupo_task_events`
- `lupo_meta_log_events`
- `lupo_session_events`
- `lupo_memory_events`
- `lupo_tab_events`
- `lupo_world_events`
- `lupo_actor_events`
- `lupo_event_log`

## Optimization & MD Representation
- **MD Mapping**: Contents from `lupo_unified_log` are represented in MD as thread summaries and task updates.
- **Future Goal**: Optimize the `payload` JSON column to handle varied event types without schema mutation.
