---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_collection_tab_paths.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_collection_tab_paths from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_collection_tab_paths.json"
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
# file: lupo_collection_tab_paths.md

# lupo_collection_tab_paths

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_collection_tab_paths`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `collection_tab_path_id` | `bigint NOT NULL` |
| `collection_id` | `bigint NOT NULL` |
| `collection_tab_id` | `bigint NOT NULL` |
| `path` | `varchar(500) NOT NULL` |
| `depth` | `int NOT NULL` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_collection_tab_paths_idx_collection` | `collection_id` | no |
| `lupo_collection_tab_paths_idx_collection_tab` | `collection_tab_id` | no |
| `lupo_collection_tab_paths_idx_path` | `path` | no |
| `lupo_collection_tab_paths_unique_tab_path` | `collection_id`, `collection_tab_id`, `path` | yes |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
