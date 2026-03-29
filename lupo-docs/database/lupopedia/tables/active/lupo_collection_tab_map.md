---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_collection_tab_map.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_collection_tab_map from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_collection_tab_map.json"
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
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
