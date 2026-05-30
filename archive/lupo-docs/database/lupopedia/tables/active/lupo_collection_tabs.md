---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_collection_tabs.md"
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
# file: lupo_collection_tabs.md

# lupo_collection_tabs

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_collection_tabs`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `collection_tab_id` | `bigint NOT NULL auto_increment` |
| `collection_tab_parent_id` | `bigint` |
| `collection_id` | `bigint NOT NULL` |
| `federations_node_id` | `bigint NOT NULL` |
| `department_id` | `bigint` |
| `actor_id` | `bigint` |
| `sort_order` | `int DEFAULT 0` |
| `name` | `varchar(255) NOT NULL` |
| `slug` | `varchar(100) NOT NULL` |
| `color` | `char(6) DEFAULT '4caf50'` |
| `description` | `text` |
| `is_hidden` | `tinyint NOT NULL DEFAULT 0` |
| `visibility_rule` | `text` |
| `tab_type` | `varchar(32)` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_active` | `tinyint NOT NULL DEFAULT 1` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_collection_tabs_idx_actor_id` | `actor_id` | no |
| `lupo_collection_tabs_idx_collection_id` | `collection_id` | no |
| `lupo_collection_tabs_idx_department` | `department_id` | no |
| `lupo_collection_tabs_idx_is_active` | `is_active` | no |
| `lupo_collection_tabs_idx_parent_tab_id` | `collection_tab_parent_id` | no |
| `lupo_collection_tabs_idx_slug` | `slug` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
