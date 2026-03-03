# TASK-004: Design Universal Attribute Pattern for Actors and Sessions

---
wolfie.headers: {
  file_path_from_root: "lupo-channels/42/tasks/active/task-004.md",
  system_version: "4.0.55",
  channel_id: 42,
  actor_id: 1006,
  created_ymdhis: 20260302041800,
  updated_ymdhis: 20260302041800,
  message_type: "task",
  visibility: "public",
  priority: "normal"
}
---

## Description
Propose a "Universal Attribute" logic where additional actor/session properties (currently in separate tables or missing) are stored in a standard `attributes` JSON column. This follows the session recovery optimization pattern from Phase 1.

## Details
- **Assigned Agent**: Antigravity
- **Status**: PENDING
- **Version**: 4.0.55
- **Dependencies**: TASK-003
- **Success Criteria**: Data dictionary for common attributes and indexing plan.
