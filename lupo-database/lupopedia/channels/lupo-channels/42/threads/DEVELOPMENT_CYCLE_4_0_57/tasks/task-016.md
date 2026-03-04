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
      objective: "TASK-016: Design flare.routing Header Specification"
    where:
      repo_paths: ["lupo-database/lupopedia/channels/lupo-channels/42/threads/DEVELOPMENT_CYCLE_4_0_57/tasks/task-016.md"]
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
  file_path_from_root: "lupo-database/lupopedia/channels/lupo-channels/42/threads/DEVELOPMENT_CYCLE_4_0_57/tasks/task-016.md"
  file_hash: "c55dccfd1c3ad3d0af46c5324c541a1624c29cff1b985b13aff25ed54fcaf144"
  last_updated_utc: "20260304"
  system_version: "4.0.57"
  channel_id: 1
  actor_id: 1004
  delegation_chain: "1004:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "TASK-016: Design flare.routing Header Specification"
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
    - ["lupo-database/lupopedia/channels/lupo-channels/42/threads/DEVELOPMENT_CYCLE_4_0_57/tasks/task-016.md", "http://www.lupopedia.com/TASK-016"]

flame.close:
  post_actions:
    - type: register_completion
      channel_id: 0
  actor_id: 1004
---

# TASK-016: Design flare.routing Header Specification

---
wolfie.headers: {
  file_path_from_root: "lupo-database/lupopedia/channels/lupo-channels/42/threads/DEVELOPMENT_CYCLE_4_0_57/tasks/task-016.md",
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
- **Status**: active
- **Version**: 4.0.55
- **Dependencies**: TASK-001, TASK-014
- **Success Criteria**: A formal YAML/JSON specification for the `flare.routing` header (COMPLETED in `lupo-channels/42/directives/flare_routing_spec.md`) and an update to the `lupo_broadcasts` (or unified log) schema projection to store these as a queryable JSON object.

## Progress Log
- **2026-03-02**: Initial header standard integrated into `thread-001.md`.
- **2026-03-02**: Formal Directive created in `lupo-channels/42/directives/flare_routing_spec.md`.
