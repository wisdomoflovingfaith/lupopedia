# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) — see http://www.lupopedia.com/database/lupopedia/channels/channels/42/threads/DEVELOPMENT_CYCLE_4_0_56/tasks/task-016

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
      repo_paths: ["database/lupopedia/channels/channels/42/threads/DEVELOPMENT_CYCLE_4_0_56/tasks/task-016.md"]
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
  file_path_from_root: "database/lupopedia/channels/channel_id/42/threads/DEVELOPMENT_CYCLE_4_0_56/tasks/task-016.md"
  file_hash: "a5fcad5a063d0f03ef1bcbb3a83f0392fade501b73cfa4943f4b3c9934824295"
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
    - ["database/lupopedia/channels/channels/42/threads/DEVELOPMENT_CYCLE_4_0_56/tasks/task-016.md", "http://www.lupopedia.com/database/lupopedia/channels/channels/42/threads/DEVELOPMENT_CYCLE_4_0_56/tasks/task-016"]

lupopedia.close:
  post_actions:
    - type: register_completion
      channel_id: 0
  actor_id: 1004
---


# TASK-016: Design flare.routing Header Specification

---
wolfie.headers: {
  file_path_from_root: "channels/42/tasks/active/task-016.md",
  system_version: "4.0.55",
  channel_id: 42,
  actor_id: 1006,
  created_ymdhis: 20260302042000,
  updated_ymdhis: 20260302042000,
  message_type: "task",
  visibility: "public",
  priority: "high"
}
---

## Description
Analyze existing FLIP/Flare headers and design a comprehensive `flare.routing` object. This routing metadata will track the lifecycle, delivery, and delegation of broadcast messages within the decentralized channel structure.

## Metadata Fields to Include:
- `to`: Primary recipient actor_id or slug.
- `from`: Originating actor_id or slug.
- `forwarded_from`: Originating actor if the message was rebroadcast.
- `delegation_chain`: Array of actor_ids representing the authority path.
- `channel_id`: Primary channel ID for the communication.
- `thread_id`: Identifier for the discussion thread (if part of one).
- `file.dialog`: Path to a CSV file containing the discussion/dialog history for the file.
- `file.history`: Path to a CSV file containing the change history for the file.
- `file.actors`: Path to a directory/CSV containing actor information and roles.
- `read_by`: Tracking of which actors have acknowledged/read the message.
- `routing_path`: Logical identifiers of channels and nodes traversed.

## Details
- **Assigned Agent**: Antigravity / Gemini CLI (1006)
- **Status**: IN_PROGRESS
- **Version**: 4.0.55
- **Dependencies**: TASK-001, TASK-014
- **Success Criteria**: A formal YAML/JSON specification for the `flare.routing` header (COMPLETED in `channels/42/directives/flare_routing_spec.md`) and an update to the `lupo_broadcasts` (or unified log) schema projection to store these as a queryable JSON object.

## Progress Log
- **2026-03-02**: Initial header standard integrated into `thread-001.md`.
- **2026-03-02**: Formal Directive created in `channels/42/directives/flare_routing_spec.md`.
