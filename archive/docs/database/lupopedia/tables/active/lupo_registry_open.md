---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "docs/database/lupopedia/tables/active/lupo_registry_open.md"
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
# file: lupo_registry_open.md

# lupo_registry_open

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_registry_open`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `unregistry_id` | `bigint NOT NULL auto_increment` |
| `entity_type` | `varchar(50) NOT NULL` |
| `entity_index_id` | `bigint NOT NULL` |
| `reason` | `varchar(255)` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `idx_registry_open_entity_type` | `entity_type` | no |
| `idx_registry_open_unique` | `entity_type`, `entity_index_id` | yes |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
