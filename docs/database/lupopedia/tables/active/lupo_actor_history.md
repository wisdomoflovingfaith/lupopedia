---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_actor_history.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_actor_history.md
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
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
