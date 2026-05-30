---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_collections.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_collections.md
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
# file: lupo_collections.md

# lupo_collections

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_collections`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `collection_id` | `bigint NOT NULL auto_increment` |
| `federation_node_id` | `bigint NOT NULL` |
| `actor_id` | `bigint` |
| `department_id` | `bigint` |
| `name` | `varchar(255) NOT NULL` |
| `slug` | `varchar(100) NOT NULL` |
| `color` | `char(6) DEFAULT '666666'` |
| `description` | `text` |
| `sort_order` | `int DEFAULT 0` |
| `properties` | `text` |
| `published_ymdhis` | `bigint` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |
| `parent_id` | `bigint` |
| `channel_id` | `bigint` |
| `is_nav_menu` | `tinyint NOT NULL DEFAULT 0` |
| `nav_icon` | `varchar(64)` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_collections_idx_actor` | `actor_id` | no |
| `lupo_collections_idx_channel_id` | `channel_id` | no |
| `lupo_collections_idx_created_ymdhis` | `created_ymdhis` | no |
| `lupo_collections_idx_department` | `department_id` | no |
| `lupo_collections_idx_domain` | `federation_node_id` | no |
| `lupo_collections_idx_is_deleted` | `is_deleted` | no |
| `lupo_collections_idx_is_nav_menu` | `is_nav_menu` | no |
| `lupo_collections_idx_name` | `name` | no |
| `lupo_collections_idx_sort_order` | `sort_order` | no |
| `lupo_collections_idx_updated_ymdhis` | `updated_ymdhis` | no |
| `lupo_collections_unique_collection_slug_domain` | `federation_node_id`, `slug` | yes |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
