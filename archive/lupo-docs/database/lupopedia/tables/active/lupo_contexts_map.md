---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_contexts_map.md"
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
# file: lupo_contexts_map.md

# lupo_contexts_map

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_contexts_map`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `contexts_map_id` | `bigint NOT NULL` |
| `context_id` | `bigint NOT NULL` |
| `item_type` | `varchar(50) NOT NULL` |
| `item_slug` | `varchar(255) NOT NULL` |
| `description` | `text` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL DEFAULT 0` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_contexts_map_idx_context_id` | `context_id` | no |
| `lupo_contexts_map_idx_context_item` | `context_id`, `item_type`, `item_slug` | no |
| `lupo_contexts_map_idx_is_deleted` | `is_deleted` | no |
| `lupo_contexts_map_idx_item_slug` | `item_slug` | no |
| `lupo_contexts_map_idx_item_type` | `item_type` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
