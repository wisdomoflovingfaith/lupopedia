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
      objective: "Collection: Atoms (Fallback Configuration)"
    where:
      repo_paths: ["lupo-database\lupopedia\channels\lupo-channels\42\collections\atoms-collection.md"]
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
  file_path_from_root: "lupo-database\lupopedia\channels\lupo-channels\42\collections\atoms-collection.md"
  file_hash: "c8b164adb634e4dda40c9744270cdf9de577b13d8b938829be9824704b11038b"
  last_updated_utc: "20260304"
  system_version: "4.0.56"
  channel_id: 1
  actor_id: 1004
  delegation_chain: "1004:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Collection: Atoms (Fallback Configuration)"
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
    - ["lupo-database\lupopedia\channels\lupo-channels\42\collections\atoms-collection.md", "http://www.lupopedia.com/ATOMS-COLLECTION"]

lupopedia.close:
  post_actions:
    - type: register_completion
      channel_id: 0
  actor_id: 1004
---

# Collection: Atoms (Fallback Configuration)

This collection represents the YAML-based system atoms and constants used by the fallback database.

## Migration Path
New Path: `lupo-database/lupopedia/atoms/`

## Key Assets
- `config-atoms.yaml`: All `lupopedia-config.php` constants in YAML format.
- `version-atoms.yaml`: System and module version history.
- `global-atoms.yaml`: Universal system-wide constants.

## Table Mappings
- `lupo_atoms`
- `lupo_config_registry`
- `lupo_system_constants` (consolidated)

## Version
Created as part of Phase 2 for version 4.0.55.
