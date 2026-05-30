---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_unified_log.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_unified_log.md
  status: ''
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: documentation
  artifact_kind: table
  channel_key: null
  federation_node_id: null
  thread_key: ''
  lupopedia.schema: null
  prd_cluster: null
  title: ''
  summary: ''
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
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
