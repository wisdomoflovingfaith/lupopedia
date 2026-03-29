---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_edge_map.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_edge_map from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_edge_map.json"
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
# file: lupo_edge_map.md

# lupo_edge_map

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_edge_map`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `edge_map_id` | `bigint NOT NULL auto_increment` |
| `edge_id` | `bigint NOT NULL` |
| `edge_type_id` | `bigint NOT NULL` |
| `source_type` | `varchar(64) NOT NULL` |
| `source_id` | `bigint NOT NULL` |
| `target_type` | `varchar(64) NOT NULL` |
| `target_id` | `bigint NOT NULL` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_edge_map_idx_edge` | `edge_id` | no |
| `lupo_edge_map_idx_source` | `source_type`, `source_id` | no |
| `lupo_edge_map_idx_target` | `target_type`, `target_id` | no |
| `lupo_edge_map_idx_type` | `edge_type_id` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
