---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "docs/database/lupopedia/tables/active/lupo_event_metadata.md"
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
# file: lupo_event_metadata.md

# lupo_event_metadata

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_event_metadata`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `metadata_id` | `bigint NOT NULL` |
| `event_id` | `bigint NOT NULL` |
| `metadata_key` | `varchar(100) NOT NULL` |
| `metadata_value` | `text` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_event_metadata_idx_created_ymdhis` | `created_ymdhis` | no |
| `lupo_event_metadata_idx_event_id` | `event_id` | no |
| `lupo_event_metadata_idx_metadata_key` | `metadata_key` | no |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
