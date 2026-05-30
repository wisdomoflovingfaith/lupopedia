---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_metadata.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_metadata.md
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
# file: lupo_metadata.md

# lupo_metadata

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_metadata`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `metadata_id` | `bigint NOT NULL` |
| `entity_type` | `varchar(32) NOT NULL` |
| `entity_id` | `bigint NOT NULL` |
| `domain_id` | `bigint` |
| `meta_type` | `varchar(64)` |
| `property_key` | `varchar(255) NOT NULL` |
| `property_value` | `text` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |
| `channel_id` | `bigint` |
| `parent_metadata_id` | `bigint` |
| `class_name` | `varchar(128)` |
| `schema_ref` | `varchar(64)` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_metadata_idx_channel_deleted` | `channel_id`, `is_deleted` | no |
| `lupo_metadata_idx_channel_id` | `channel_id` | no |
| `lupo_metadata_idx_class_deleted` | `class_name`, `is_deleted` | no |
| `lupo_metadata_idx_class_name` | `class_name` | no |
| `lupo_metadata_idx_created_ymdhis` | `created_ymdhis` | no |
| `lupo_metadata_idx_domain` | `domain_id` | no |
| `lupo_metadata_idx_entity` | `entity_type`, `entity_id` | no |
| `lupo_metadata_idx_entity_deleted` | `entity_type`, `entity_id`, `is_deleted` | no |
| `lupo_metadata_idx_is_deleted` | `is_deleted` | no |
| `lupo_metadata_idx_meta_type` | `meta_type` | no |
| `lupo_metadata_idx_meta_type_deleted` | `meta_type`, `is_deleted` | no |
| `lupo_metadata_idx_parent_deleted` | `parent_metadata_id`, `is_deleted` | no |
| `lupo_metadata_idx_parent_metadata_id` | `parent_metadata_id` | no |
| `lupo_metadata_idx_property_key` | `property_key` | no |
| `lupo_metadata_idx_updated_ymdhis` | `updated_ymdhis` | no |
| `lupo_metadata_unique_entity_domain_property` | `entity_type`, `entity_id`, `domain_id`, `property_key` | yes |

## Doctrine
Source of truth: `database/lupopedia/json/` TOON exports
Regeneration mode: Stage 3 deterministic normalization
Edge mode: placeholder baseline

**Note:** Tag and hashtag relationships are now managed via the canonical `lupo_hashtags` and `lupo_hashtag_map` tables. Do not use lupo_metadata for tag/hashtag storage; see the semantic_navbar documentation for details.
