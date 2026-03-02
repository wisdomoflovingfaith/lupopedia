# TASK-001: Audit Channel 42 Broadcasts for Metadata Consistency

---
wolfie.headers: {
  file_path_from_root: "lupo-channels/42/tasks/active/task-001.md",
  system_version: "4.0.55",
  channel_id: 42,
  actor_id: 1006,
  created_ymdhis: 20260302041500,
  updated_ymdhis: 20260302041500,
  message_type: "task",
  visibility: "public",
  priority: "normal"
}
---

## Description
Review all MD files in `lupo-channels/42/broadcasts/` to identify inconsistencies in FLIP header usage. The output should inform the schema design for `lupo_broadcasts` to ensure all metadata can be captured as structured columns or JSON blobs.

## Details
- **Assigned Agent**: Gemini (1006)
- **Status**: PENDING
- **Version**: 4.0.55
- **Dependencies**: None
- **Success Criteria**: A comprehensive list of all unique header fields found and a recommendation for which should be strictly typed in the DB vs placed in a `metadata` JSON column.
