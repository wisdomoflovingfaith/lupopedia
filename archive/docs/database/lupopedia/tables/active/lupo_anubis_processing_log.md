---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "docs/database/lupopedia/tables/active/lupo_anubis_processing_log.md"
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
# file: lupo_anubis_processing_log.md

# lupo_anubis_processing_log

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_anubis_processing_log`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `log_id` | `bigint NOT NULL auto_increment` |
| `queue_id` | `bigint NOT NULL` |
| `file_path` | `varchar(512) NOT NULL` |
| `action` | `varchar(64) NOT NULL` |
| `details` | `text` |
| `actor_id` | `bigint` |
| `created_utc` | `bigint NOT NULL` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_anubis_processing_log_idx_created` | `created_utc` | no |
| `lupo_anubis_processing_log_idx_queue` | `queue_id` | no |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
