---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "docs/database/lupopedia/tables/active/lupo_crafty_syntax_leave_message.md"
  web_path: null
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: documentation
  artifact_kind: table
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# file: lupo_crafty_syntax_leave_message.md

# lupo_crafty_syntax_leave_message

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_crafty_syntax_leave_message`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `crafty_syntax_leave_message_id` | `bigint NOT NULL auto_increment` |
| `department_id` | `bigint NOT NULL DEFAULT 0` |
| `email` | `varchar(255) NOT NULL DEFAULT ''` |
| `phone` | `varchar(45)` |
| `name` | `varchar(200)` |
| `subject` | `varchar(255) NOT NULL DEFAULT ''` |
| `message` | `text` |
| `priority` | `tinyint NOT NULL DEFAULT 2` |
| `session_data` | `text` |
| `form_data` | `text` |
| `ip_address` | `varchar(45)` |
| `user_agent` | `varchar(255)` |
| `status` | `enum('new','in_progress','resolved','spam') NOT NULL DEFAULT 'new'` |
| `assigned_to` | `bigint` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_crafty_syntax_leave_message_idx_assigned` | `assigned_to` | no |
| `lupo_crafty_syntax_leave_message_idx_created` | `created_ymdhis` | no |
| `lupo_crafty_syntax_leave_message_idx_department` | `department_id` | no |
| `lupo_crafty_syntax_leave_message_idx_email` | `email` | no |
| `lupo_crafty_syntax_leave_message_idx_message_search` | `email`, `name`, `subject`, `message` | no |
| `lupo_crafty_syntax_leave_message_idx_priority` | `priority` | no |
| `lupo_crafty_syntax_leave_message_idx_status` | `status` | no |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
