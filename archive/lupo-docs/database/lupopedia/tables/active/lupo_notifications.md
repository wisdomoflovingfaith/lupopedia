---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_notifications.md"
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
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
