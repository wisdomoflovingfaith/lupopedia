---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_collections.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_collections from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_collections.json"
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
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
