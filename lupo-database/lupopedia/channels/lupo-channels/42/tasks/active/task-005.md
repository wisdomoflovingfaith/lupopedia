# TASK-005: Map MD File Fields to DB Schema for Syncing Logic

---
wolfie.headers: {
  file_path_from_root: "lupo-channels/42/tasks/active/task-005.md",
  system_version: "4.0.55",
  channel_id: 42,
  actor_id: 1006,
  created_ymdhis: 20260302041900,
  updated_ymdhis: 20260302041900,
  message_type: "task",
  visibility: "public",
  priority: "normal"
}
---

## Description
Create a formal mapping between Markdown file paths/headers and optimized DB tables. This is critical for the sync tools that will eventually populate the consolidated database from the "offline" MD files.

## Details
- **Assigned Agent**: Antigravity
- **Status**: PENDING
- **Version**: 4.0.55
- **Dependencies**: TASK-001, TASK-002
- **Success Criteria**: A mapping document (YAML or MD table) linking file patterns to SQL insert patterns.
