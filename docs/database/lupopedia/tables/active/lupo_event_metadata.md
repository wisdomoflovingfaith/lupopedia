---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_event_metadata.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_event_metadata.md
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
