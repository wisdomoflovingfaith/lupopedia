---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "docs/database/lupopedia/tables/active/lupo_collection_tab_paths.md"
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
# file: lupo_collection_tab_paths.md

# lupo_collection_tab_paths

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_collection_tab_paths`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `collection_tab_path_id` | `bigint NOT NULL` |
| `collection_id` | `bigint NOT NULL` |
| `collection_tab_id` | `bigint NOT NULL` |
| `path` | `varchar(500) NOT NULL` |
| `depth` | `int NOT NULL` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_collection_tab_paths_idx_collection` | `collection_id` | no |
| `lupo_collection_tab_paths_idx_collection_tab` | `collection_tab_id` | no |
| `lupo_collection_tab_paths_idx_path` | `path` | no |
| `lupo_collection_tab_paths_unique_tab_path` | `collection_id`, `collection_tab_id`, `path` | yes |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
