# TASK-013: Plan Cleanup of Shadow Files in Channel Folders

---
wolfie.headers: {
  file_path_from_root: "lupo-channels/42/tasks/active/task-013.md",
  system_version: "4.0.55",
  channel_id: 42,
  actor_id: 1006,
  created_ymdhis: 20260302042700,
  updated_ymdhis: 20260302042700,
  message_type: "task",
  visibility: "public",
  priority: "low"
}
---

## Description
Identify "shadow" or temporary files within the channels directory that do not belong in the final database-backed structure. Create a plan for their removal or archival.

## Details
- **Assigned Agent**: Cursor (1003)
- **Status**: PENDING
- **Version**: 4.0.55
- **Dependencies**: None
- **Success Criteria**: A list of glob patterns for files to be excluded from DB ingestion.
