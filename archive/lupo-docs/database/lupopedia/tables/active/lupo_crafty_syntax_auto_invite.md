---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_crafty_syntax_auto_invite.md"
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
# file: lupo_crafty_syntax_auto_invite.md

# lupo_crafty_syntax_auto_invite

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_crafty_syntax_auto_invite`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `crafty_syntax_auto_invite_id` | `bigint NOT NULL auto_increment` |
| `is_offline` | `tinyint NOT NULL DEFAULT 0` |
| `is_active` | `tinyint NOT NULL DEFAULT 0` |
| `department_id` | `bigint NOT NULL DEFAULT 0` |
| `message` | `mediumtext` |
| `page_url` | `varchar(500)` |
| `visits` | `int NOT NULL DEFAULT 0` |
| `referrer_url` | `varchar(500)` |
| `invite_type` | `varchar(50)` |
| `trigger_seconds` | `int NOT NULL DEFAULT 0` |
| `operator_user_id` | `bigint NOT NULL DEFAULT 0` |
| `show_socialpane` | `tinyint NOT NULL DEFAULT 0` |
| `exclude_mobile` | `tinyint NOT NULL DEFAULT 0` |
| `only_mobile` | `tinyint NOT NULL DEFAULT 0` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 20250101000000` |
| `updated_ymdhis` | `bigint NOT NULL DEFAULT 20250101000000` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_crafty_syntax_auto_invite_idx_created` | `created_ymdhis` | no |
| `lupo_crafty_syntax_auto_invite_idx_department` | `department_id` | no |
| `lupo_crafty_syntax_auto_invite_idx_operator` | `operator_user_id` | no |
| `lupo_crafty_syntax_auto_invite_idx_page_url` | `page_url` | no |
| `lupo_crafty_syntax_auto_invite_idx_status` | `is_active`, `is_deleted` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
