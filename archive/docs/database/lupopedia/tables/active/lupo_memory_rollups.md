---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "docs/database/lupopedia/tables/active/lupo_memory_rollups.md"
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
# file: lupo_memory_rollups.md

# lupo_memory_rollups

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_memory_rollups`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `memory_rollup_id` | `bigint NOT NULL` |
| `actor_id` | `int NOT NULL` |
| `summary` | `text NOT NULL` |
| `source_event_ids` | `text NOT NULL` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_memory_rollups_idx_actor_created` | `actor_id`, `created_ymdhis` | no |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
