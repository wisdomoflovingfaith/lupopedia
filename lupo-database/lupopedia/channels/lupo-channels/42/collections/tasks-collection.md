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
