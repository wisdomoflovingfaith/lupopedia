---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_metadata.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_metadata from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_metadata.json"
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
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
