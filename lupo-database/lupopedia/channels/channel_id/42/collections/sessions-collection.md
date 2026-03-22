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
      objective: "Collection: Sessions"
    where:
      repo_paths: ["lupo-database\lupopedia\channels\lupo-channels\42\collections\sessions-collection.md"]
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
  file_path_from_root: "lupo-database/lupopedia/channels/channel_id/42/collections/sessions-collection.md"
  file_hash: "c33bd3d65802ec75559f6b6da064a9e362deaac48f71e695603004825433eae0"
  last_updated_utc: "20260304"
  system_version: "4.0.56"
  channel_id: 1
  actor_id: 1004
  delegation_chain: "1004:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Collection: Sessions"
  mood_rgb: "4169E1"
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
    - ["lupo-database\lupopedia\channels\lupo-channels\42\collections\sessions-collection.md", "http://www.lupopedia.com/SESSIONS-COLLECTION"]

lupopedia.close:
  post_actions:
    - type: register_completion
      channel_id: 0
  actor_id: 1004
---

# Collection: Sessions

---
wolfie.headers: {
  file_path_from_root: "lupo-channels/42/collections/sessions-collection.md",
  system_version: "4.0.55",
  channel_id: 42,
  actor_id: 1006,
  created_ymdhis: 20260302041310,
  updated_ymdhis: 20260302041310,
  message_type: "collection",
  visibility: "public",
  priority: "normal"
}
---

## Description
Tracks active and historical actor sessions, including state recovery data for AI agents.

## Associated Tables
### Primary (Active)
- `lupo_sessions`: Stores session identifiers, actor links, and recovery state.

### Legacy (Merged/Dropped)
- `lupo_session_recovery`: Now consolidated into the `recovery_state` column of `lupo_sessions`.

## Optimization & MD Representation
- **MD Mapping**: Session lifecycle is documented in `DEVELOPMENT_CYCLE` threads for each version.
- **Future Goal**: Implement TTL logic within the sessions collection to auto-cleanup expired MD records.
