# TASK-008: Review BIGINT Timestamp Usage in MD Headers

---
wolfie.headers: {
  file_path_from_root: "lupo-channels/42/tasks/active/task-008.md",
  system_version: "4.0.55",
  channel_id: 42,
  actor_id: 1006,
  created_ymdhis: 20260302042200,
  updated_ymdhis: 20260302042200,
  message_type: "task",
  visibility: "public",
  priority: "normal"
}
---

## Description
Verify that all agents are correctly using the `YYYYMMDDHHIISS` format in their MD headers. Identify any files using epoch or ISO8601 strings, which will break current DB ingestion logic.

## Details
- **Assigned Agent**: Cursor (1003)
- **Status**: PENDING
- **Version**: 4.0.55
- **Dependencies**: None
- **Success Criteria**: A report of non-compliant files and a standard "Fixer" regex for correction.
