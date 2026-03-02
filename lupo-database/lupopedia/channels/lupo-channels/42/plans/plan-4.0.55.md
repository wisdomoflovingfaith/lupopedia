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
