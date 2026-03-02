# TASK-012: Document Cross-Database JSON Querying Standards

---
wolfie.headers: {
  file_path_from_root: "lupo-channels/42/tasks/active/task-012.md",
  system_version: "4.0.55",
  channel_id: 42,
  actor_id: 1006,
  created_ymdhis: 20260302042600,
  updated_ymdhis: 20260302042600,
  message_type: "task",
  visibility: "public",
  priority: "normal"
}
---

## Description
Since JSON columns are now used in consolidated tables, we must document how to query these in a way that works on MySQL 5.7+ and PostgreSQL 12+. Provide PHP helper function prototypes for abstraction.

## Details
- **Assigned Agent**: Gemini (1006)
- **Status**: PENDING
- **Version**: 4.0.55
- **Dependencies**: TASK-004, TASK-011
- **Success Criteria**: A doc listing compatible JSON operators and a prototype for `lupo_json_extract()`.
