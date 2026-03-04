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
      objective: "Plan 4.0.55: Phase 2 — MD-Driven Schema & Collection Optimization"
    where:
      repo_paths: ["lupo-database\lupopedia\channels\lupo-channels\42\plans\plan-4.0.55.md"]
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
  file_path_from_root: "lupo-database\lupopedia\channels\lupo-channels\42\plans\plan-4.0.55.md"
  file_hash: "0dbcbbb9b307cc1e1ecea9469833872f25a2682398f5af0b0daaaf7ed060c776"
  last_updated_utc: "20260304"
  system_version: "4.0.56"
  channel_id: 1
  actor_id: 1004
  delegation_chain: "1004:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Plan 4.0.55: Phase 2 — MD-Driven Schema & Collection Optimization"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.56"]
  tags: ["lupo-database", "lupopedia", "channels", "lupo-channels", "42", "plans"]
  lupo_agent: "antigravity"

flare.edges:
  outbound_edges: []

flare.footer:
  last_verified: "20260304"
  last_verified_by: "antigravity"

flame.see:
  mappings:
    - ["lupo-database\lupopedia\channels\lupo-channels\42\plans\plan-4.0.55.md", "http://www.lupopedia.com/PLAN-4.0.55"]

flame.close:
  post_actions:
    - type: register_completion
      channel_id: 0
  actor_id: 1004
---

# Plan 4.0.55: Phase 2 — MD-Driven Schema & Collection Optimization

---
wolfie.headers: {
  file_path_from_root: "lupo-channels/42/plans/plan-4.0.55.md",
  system_version: "4.0.55",
  channel_id: 42,
  actor_id: 1006,
  created_ymdhis: 20260302041200,
  updated_ymdhis: 20260302163500,
  message_type: "plan",
  visibility: "public",
  priority: "high"
}
flip.footer: {
  outbound_edges: [
    { to: "lupo-channels/42/tasks/active/", type: "references" },
    { to: "lupo-channels/42/threads/DEVELOPMENT_CYCLE_4_0_55/", type: "references" },
    { to: "lupo-channels/42/collections/", type: "references" }
  ],
  semantic_tags: ["planning", "optimization", "collections", "md-derived", "database-fallback", "channels-migration"]
}
---

## Overview
This plan outlines the second phase of version 4.0.55, focusing on transitioning to "Collection-Oriented Design" and establishing a redundant, file-based persistence layer. A critical directive added to this phase is the **recursive migration of all channel data** into a new unified database structure.

## Objectives
- **Collection Mirroring**: Represent all database table groups as MD collection files for offline analysis.
- **Database Fallback System**: Introduce the `lupo-database/` directory as a file-based secondary.
- **Recursive Channel Migration**: Fully relocate `lupo-channels/` and its entire subfolder tree (`tasks/`, `plans/`, `threads/`, etc.) into the new structure.
- **Offline Reliability**: Ensure that even if the SQL database is offline, every artifact remains reachable via filesystem paths.

## File/Folder/CSV Database Fallback System

### 1. Unified Directory structure
A new top-level `lupo-database/` directory will serve as the root for all file-based "Root Truth" data.
- **Migration Target**: `lupo-database/lupopedia/channels/`
- **Migration Source**: `wholesale move of lupo-channels/` (Every file/folder).
- **Secondary Moves**: Actors, Content, Atoms, and Collections.

### 2. Configuration & Constants
`lupopedia-config.php` will be updated to anchor all paths to `LUPO_DATABASE_DIR`.
- `LUPO_CHANNELS_DIR` will now resolve to `LUPO_DATABASE_DIR . '/lupopedia/channels'`.

### 3. Risks & Mitigations
- **Risk**: Path breakage and internal MD link failure.
- **Mitigation**: Use relative paths within MD files or implement an automated path-resolution helper that respects the new configuration.
- **Risk**: Data loss during recursive move.
- **Mitigation**: Create a full backup of the `lupo-channels/` tree before execution and verify file counts post-move.

## Task Breakdown

| Task ID | Description | Assigned Agent | Status |
|---------|-------------|----------------|--------|
| TASK-010 | Design File/Folder/CSV Database Fallback System | Antigravity | PLANNED |
| TASK-011 | Update Config Constants for Folder Migration | Antigravity | PLANNED |
| TASK-012 | Plan Recursive Directory Migration (Actors/Content) | Antigravity | PLANNED |
| TASK-013 | Implement Fallback Logic Stubs | Antigravity | PLANNED |
| TASK-014 | Plan Recursive Channels-to-Database Migration | Antigravity | PLANNED |
| TASK-015 | Version 4.0.55 Phase 2 Summary & Phase 3 Roadmap | Antigravity | PENDING |
| TASK-016 | Design flare.routing Header Specification | Antigravity | COMPLETED |

## Timeline
- **Phase 2 Start**: 2026-03-02
- **Estimated Completion**: 2026-03-04
- **Phase 3 Implementation**: 2026-03-05
