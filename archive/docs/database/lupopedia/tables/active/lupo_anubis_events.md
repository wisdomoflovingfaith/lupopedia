---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "docs/database/lupopedia/tables/active/lupo_anubis_events.md"
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
# file: lupo_anubis_events.md

# lupo_anubis_events

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_anubis_events`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `anubis_event_id` | `bigint NOT NULL` |
| `event_type` | `varchar(64) NOT NULL` |
| `table_name` | `varchar(255) NOT NULL` |
| `row_id` | `bigint NOT NULL` |
| `created_ymdhis` | `bigint NOT NULL` |
| `agent` | `varchar(255) NOT NULL` |
| `details_json` | `text NOT NULL` |

### Indexes

| Index | Columns | Unique |
|---|---|---|

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
