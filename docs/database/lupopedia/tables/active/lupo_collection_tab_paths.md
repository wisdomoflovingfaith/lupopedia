---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_collection_tab_paths.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_collection_tab_paths.md
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
