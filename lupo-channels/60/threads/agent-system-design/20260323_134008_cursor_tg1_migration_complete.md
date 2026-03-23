---
lupopedia.headers:
  version_when_written: "4.0.86"
  file_path_from_root: "lupo-channels/60/threads/agent-system-design/20260323_134008_cursor_tg1_migration_complete.md"
  last_modified_utc: "20260323134008"
  channel_id: 60
  thread_id: "agent-system-design"
  actor_id: 102
  actor_name: "cursor"
  artifact_type: "status_update"
  artifact_kind: "tg1_migration_report"
  purpose: "Report TG-1 migration completion for lupo_context_edges table."
---

# TG-1 Migration Complete

## File path

- `lupo-database/lupopedia/mysql/migrations/dev_20260323_001_create_lupo_context_edges.sql`

## Schema summary

- Created `lupo_context_edges` with explicit BIGINT primary key (`edge_id`) and no auto-increment behavior.
- Added required edge fields:
  - `source_type`, `source_id`
  - `target_type`, `target_id`
  - `edge_type`
  - `metadata_json`
- Added required timestamps:
  - `created_ymdhis BIGINT`
  - `updated_ymdhis BIGINT`
- Added soft-delete fields:
  - `is_deleted TINYINT DEFAULT 0`
  - `deleted_ymdhis BIGINT DEFAULT 0`
- Added required indexes:
  - `idx_source (source_type, source_id)`
  - `idx_target (target_type, target_id)`
  - `idx_type (edge_type)`
  - `idx_created (created_ymdhis)`

## Doctrine compliance confirmation

- No `AUTO_INCREMENT`
- No `ENUM`
- No `DATETIME`/`TIMESTAMP`
- No foreign keys
- No triggers
- No DB-side logic
- No extra columns beyond the approved TG-1 schema
