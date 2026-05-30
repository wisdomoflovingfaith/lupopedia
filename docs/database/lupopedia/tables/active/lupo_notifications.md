---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_notifications.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_notifications.md
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
# file: lupo_notifications.md

# lupo_notifications

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_notifications`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `notification_id` | `bigint NOT NULL` |
| `actor_id` | `bigint NOT NULL` |
| `from_actor_id` | `bigint` |
| `to_actor_id` | `bigint` |
| `channel_id` | `bigint` |
| `notification_type` | `varchar(64) NOT NULL` |
| `title` | `varchar(255)` |
| `message` | `text` |
| `link_url` | `varchar(255)` |
| `is_read` | `tinyint NOT NULL DEFAULT 0` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |

### Indexes

| Index | Columns | Unique |
|---|---|---|

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
