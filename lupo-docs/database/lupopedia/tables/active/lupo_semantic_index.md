---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_semantic_index.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_semantic_index from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_semantic_index.json"
    type: "references"
    weight: 1.0
    reason: "authoritative TOON JSON source"
lupopedia.footer:
  last_verified: "20260328013000"
  last_verified_by: "hephaestus"
  last_verified_by_actor_id: 23
  generated: true
  provenance: "stage3_track_c_normalization"
---
# file: lupo_semantic_index.md

# lupo_semantic_index

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_semantic_index`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `semantic_id` | `bigint NOT NULL` |
| `semantic_type` | `varchar(32) NOT NULL` |
| `slug` | `varchar(255)` |
| `name` | `varchar(255)` |
| `title` | `varchar(255)` |
| `description` | `text` |
| `parent_id` | `bigint` |
| `sort_order` | `int DEFAULT 0` |
| `weight` | `float DEFAULT 0` |
| `relationship_strength` | `decimal(3,2) DEFAULT 1.00` |
| `layer` | `varchar(64)` |
| `timeframe` | `varchar(64)` |
| `language_code` | `varchar(8)` |
| `color` | `varchar(7) DEFAULT '#666666'` |
| `template_path` | `varchar(512)` |
| `json_data` | `json` |
| `text_value` | `text` |
| `source_content_id` | `bigint` |
| `target_content_id` | `bigint` |
| `source_page_id` | `bigint` |
| `target_page_id` | `bigint` |
| `entity_type` | `varchar(32)` |
| `entity_id` | `bigint` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_active` | `tinyint NOT NULL DEFAULT 1` |
| `is_default` | `tinyint NOT NULL DEFAULT 0` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |
| `created_by` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_semantic_index_idx_created_ymdhis` | `created_ymdhis`, `is_active`, `is_deleted` | no |
| `lupo_semantic_index_idx_entity` | `entity_type`, `entity_id` | no |
| `lupo_semantic_index_idx_is_active` | `is_active` | no |
| `lupo_semantic_index_idx_is_default` | `is_default` | no |
| `lupo_semantic_index_idx_is_deleted` | `is_deleted` | no |
| `lupo_semantic_index_idx_language` | `language_code` | no |
| `lupo_semantic_index_idx_layer` | `layer` | no |
| `lupo_semantic_index_idx_parent` | `parent_id` | no |
| `lupo_semantic_index_idx_source_content` | `source_content_id` | no |
| `lupo_semantic_index_idx_source_page` | `source_page_id` | no |
| `lupo_semantic_index_idx_target_content` | `target_content_id` | no |
| `lupo_semantic_index_idx_target_page` | `target_page_id` | no |
| `lupo_semantic_index_idx_timeframe` | `timeframe` | no |
| `lupo_semantic_index_idx_type` | `semantic_type` | no |
| `lupo_semantic_index_idx_updated_ymdhis` | `updated_ymdhis` | no |
| `lupo_semantic_index_uk_type_slug` | `semantic_type`, `slug` | yes |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
