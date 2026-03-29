---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_federation_category_map.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "federation"
  purpose: "Normalized table documentation for lupo_federation_category_map from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_federation_category_map.json"
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
# file: lupo_federation_category_map.md

# lupo_federation_category_map

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_federation_category_map`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `federation_category_map_id` | `bigint NOT NULL` |
| `federation_node_id` | `bigint NOT NULL` |
| `federation_category_id` | `bigint NOT NULL` |
| `meta_json` | `json` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL DEFAULT 0` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_federation_category_map_idx_category` | `federation_category_id` | no |
| `lupo_federation_category_map_idx_is_deleted` | `is_deleted` | no |
| `lupo_federation_category_map_idx_node` | `federation_node_id` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
