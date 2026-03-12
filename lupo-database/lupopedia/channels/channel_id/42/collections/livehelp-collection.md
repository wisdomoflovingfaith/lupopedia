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
      objective: "Collection: LiveHelp (Network)"
    where:
      repo_paths: ["lupo-database\lupopedia\channels\lupo-channels\42\collections\livehelp-collection.md"]
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
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-database\lupopedia\channels\lupo-channels\42\collections\livehelp-collection.md"
  file_hash: "24e93e7c3299407940c91e1fa34ca1d2f5b1125c80db327d754f025751c9b0ba"
  last_updated_utc: "20260304"
  system_version: "4.0.56"
  channel_id: 1
  actor_id: 1004
  delegation_chain: "1004:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Collection: LiveHelp (Network)"
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
    - ["lupo-database\lupopedia\channels\lupo-channels\42\collections\livehelp-collection.md", "http://www.lupopedia.com/LIVEHELP-COLLECTION"]

lupopedia.close:
  post_actions:
    - type: register_completion
      channel_id: 0
  actor_id: 1004
---

# Collection: LiveHelp (Network)

---
wolfie.headers: {
  file_path_from_root: "lupo-channels/42/collections/livehelp-collection.md",
  system_version: "4.0.55",
  channel_id: 42,
  actor_id: 1006,
  created_ymdhis: 20260302041330,
  updated_ymdhis: 20260302041330,
  message_type: "collection",
  visibility: "public",
  priority: "normal"
}
---

## Description
Handles communication channels, operator assignments, and live interaction transcripts.

## Associated Tables
- `livehelp_channels`
- `livehelp_agents`
- `conversation`
- `operator`
- `transcript`
- `invite`

## Optimization & MD Representation
- **MD Mapping**: Conversations and transcripts are mirrored as `threads/*.md` files within the channel structure.
- **Future Goal**: Consolidate `operator` and `livehelp_agents` into a unified `agent_registry` pattern to reduce table count.
