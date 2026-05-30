---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_collection_tab_map.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_collection_tab_map.md
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
# file: lupo_collection_tab_map.md

# lupo_collection_tab_map

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_collection_tab_map`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `collection_tab_map_id` | `bigint NOT NULL` |
| `collection_tab_id` | `bigint NOT NULL` |
| `federations_node_id` | `bigint NOT NULL` |
| `item_type` | `varchar(20) NOT NULL` |
| `item_id` | `bigint NOT NULL` |
| `sort_order` | `int DEFAULT 0` |
| `properties` | `text` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_collection_tab_map_idx_collection_tab` | `collection_tab_id` | no |
| `lupo_collection_tab_map_idx_created_ymdhis` | `created_ymdhis` | no |
| `lupo_collection_tab_map_idx_domain` | `federations_node_id` | no |
| `lupo_collection_tab_map_idx_is_deleted` | `is_deleted` | no |
| `lupo_collection_tab_map_idx_item` | `item_type`, `item_id` | no |
| `lupo_collection_tab_map_idx_sort_order` | `sort_order` | no |
| `lupo_collection_tab_map_idx_updated_ymdhis` | `updated_ymdhis` | no |
| `lupo_collection_tab_map_unique_item_in_tab` | `collection_tab_id`, `item_type`, `item_id` | yes |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
