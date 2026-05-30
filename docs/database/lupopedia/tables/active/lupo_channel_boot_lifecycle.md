---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_channel_boot_lifecycle.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_channel_boot_lifecycle.md
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
# file: lupo_channel_boot_lifecycle.md

# lupo_channel_boot_lifecycle

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_channel_boot_lifecycle`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `lifecycle_id` | `bigint NOT NULL auto_increment` |
| `channel_id` | `bigint NOT NULL` |
| `actor_id` | `bigint NOT NULL` |
| `session_id` | `varchar(64) NOT NULL` |
| `lifecycle_start_time` | `bigint NOT NULL` |
| `lifecycle_end_time` | `bigint` |
| `lifecycle_status` | `varchar(64) NOT NULL DEFAULT 'started'` |
| `lifecycle_type` | `varchar(64) NOT NULL` |
| `total_channels` | `int NOT NULL DEFAULT 0` |
| `channels_processed` | `int NOT NULL DEFAULT 0` |
| `channels_successful` | `int NOT NULL DEFAULT 0` |
| `channels_failed` | `int NOT NULL DEFAULT 0` |
| `lifecycle_duration_ms` | `int` |
| `error_details` | `json` |
| `performance_metrics` | `json` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_channel_boot_lifecycle_fk_lifecycle_channel` | `channel_id` | no |
| `lupo_channel_boot_lifecycle_idx_actor_session` | `actor_id`, `session_id` | no |
| `lupo_channel_boot_lifecycle_idx_status_time` | `lifecycle_status`, `lifecycle_start_time` | no |
| `lupo_channel_boot_lifecycle_idx_type_time` | `lifecycle_type`, `lifecycle_start_time` | no |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
