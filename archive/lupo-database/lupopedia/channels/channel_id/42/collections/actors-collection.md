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
      objective: "Collection: Actors (Fallback Migration)"
    where:
      repo_paths: ["lupo-database\lupopedia\channels\lupo-channels\42\collections\actors-collection.md"]
      runtime_scope: "cli"
      channels:
        primary_channel_id: 1
    when:
      urgency: "standard"
      effective_utc: "2026-03-04T10:08:31Z"
    why:
      rationale: "Standard artifact generation"
    how:
      method: "FLARE automated application"
      success_criteria: ["header applied correctly"]

lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-database/lupopedia/channels/channel_id/42/collections/actors-collection.md"
  file_hash: "b00f72dc0227d1604be62249b998779ad2eea83c3d2e199c78d2953cfd567156"
  last_updated_utc: "20260304"
  system_version: "4.0.56"
  channel_id: 1
  actor_id: 1004
  delegation_chain: "1004:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Collection: Actors (Fallback Migration)"
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
    - ["lupo-database\lupopedia\channels\lupo-channels\42\collections\actors-collection.md", "http://www.lupopedia.com/ACTORS-COLLECTION"]

lupopedia.close:
  post_actions:
    - type: register_completion
      channel_id: 0
  actor_id: 1004
---

# Collection: Actors (Fallback Migration)

This collection documents the move of all actor-related artifacts into the `lupo-database/` directory.

## Migration Path
Original Path: `lupo-actors/`
Fallback Path: `lupo-database/lupopedia/actors/`

## Key Assets
- `lupo-database/lupopedia/actors/<actor_id>/WHO.json`: Primary identity file.
- `lupo-database/lupopedia/actors/<actor_id>/session.json`: Active session anchor.
- `lupo-database/lupopedia/actors/<actor_id>/help.md`: Actor documentation.
- `lupo-database/lupopedia/actors/<actor_id>/profile.png`: Actor avatar.

## Table Mappings
- `lupo_actors`
- `lupo_auth_users` (linked identities)
- `lupo_auth_groups`
- `lupo_actor_roles`

## Version
Created as part of Phase 2 for version 4.0.55.
