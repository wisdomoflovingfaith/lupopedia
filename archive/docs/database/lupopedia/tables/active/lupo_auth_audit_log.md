---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "docs/database/lupopedia/tables/active/lupo_auth_audit_log.md"
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
# file: lupo_auth_audit_log.md

# lupo_auth_audit_log

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_auth_audit_log`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `auth_audit_log_id` | `bigint NOT NULL` |
| `user_id` | `bigint` |
| `crafty_operator_id` | `int` |
| `event_type` | `varchar(50) NOT NULL` |
| `system_context` | `varchar(50) NOT NULL` |
| `ip_address` | `varchar(45)` |
| `user_agent` | `text` |
| `event_data` | `json` |
| `success` | `tinyint NOT NULL DEFAULT 1` |
| `error_message` | `text` |
| `created_at` | `bigint` |
| `updated_at` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_auth_audit_log_idx_crafty_operator_id` | `crafty_operator_id` | no |
| `lupo_auth_audit_log_idx_created_at` | `created_at` | no |
| `lupo_auth_audit_log_idx_event_type` | `event_type` | no |
| `lupo_auth_audit_log_idx_success` | `success` | no |
| `lupo_auth_audit_log_idx_system_context` | `system_context` | no |
| `lupo_auth_audit_log_idx_user_id` | `user_id` | no |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
