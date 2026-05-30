---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "docs/database/lupopedia/tables/active/lupo_capability_usage.md"
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
# file: lupo_capability_usage.md

# lupo_capability_usage

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_capability_usage`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `usage_id` | `bigint NOT NULL` |
| `actor_id` | `bigint NOT NULL` |
| `capability` | `varchar(100) NOT NULL` |
| `usage_count` | `bigint DEFAULT 0` |
| `success_rate` | `float DEFAULT 1` |
| `avg_response_time_ms` | `int DEFAULT 0` |
| `last_used_ymdhis` | `bigint DEFAULT 0` |
| `performance_metrics` | `json` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_capability_usage_idx_actor_capability` | `actor_id`, `capability` | no |
| `lupo_capability_usage_idx_capability` | `capability` | no |
| `lupo_capability_usage_idx_is_deleted` | `is_deleted` | no |
| `lupo_capability_usage_idx_last_used` | `last_used_ymdhis` | no |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
