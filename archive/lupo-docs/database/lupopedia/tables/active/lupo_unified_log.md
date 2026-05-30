---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_unified_log.md"
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
# file: lupo_unified_log.md

# lupo_unified_log

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_unified_log`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `log_id` | `bigint NOT NULL auto_increment` |
| `log_type` | `varchar(64) NOT NULL` |
| `log_level` | `varchar(32) NOT NULL DEFAULT 'info'` |
| `log_message` | `text NOT NULL` |
| `log_context` | `json` |
| `actor_id` | `bigint` |
| `channel_id` | `bigint` |
| `session_id` | `varchar(128)` |
| `ip_address` | `varchar(45)` |
| `user_agent` | `text` |
| `created_ymdhis` | `bigint NOT NULL` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_unified_log_idx_actor_id` | `actor_id` | no |
| `lupo_unified_log_idx_actor_log` | `actor_id`, `log_type` | no |
| `lupo_unified_log_idx_channel_id` | `channel_id` | no |
| `lupo_unified_log_idx_channel_log` | `channel_id`, `log_type` | no |
| `lupo_unified_log_idx_created_ymdhis` | `created_ymdhis` | no |
| `lupo_unified_log_idx_log_level` | `log_level` | no |
| `lupo_unified_log_idx_log_type` | `log_type` | no |
| `lupo_unified_log_idx_log_type_created` | `log_type`, `created_ymdhis` | no |
| `lupo_unified_log_idx_session_id` | `session_id` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
