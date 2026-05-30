---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_actor_history.md"
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
# file: lupo_actor_history.md

# lupo_actor_history

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_actor_history`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `history_id` | `bigint NOT NULL` |
| `actor_id` | `bigint NOT NULL` |
| `achievement_id` | `varchar(100)` |
| `title` | `varchar(255) NOT NULL` |
| `description` | `text` |
| `impact` | `text` |
| `date_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `channel_id` | `bigint` |
| `tags` | `json` |
| `metrics` | `json` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_actor_history_idx_actor_id` | `actor_id` | no |
| `lupo_actor_history_idx_channel_id` | `channel_id` | no |
| `lupo_actor_history_idx_date_ymdhis` | `date_ymdhis` | no |
| `lupo_actor_history_idx_is_deleted` | `is_deleted` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
