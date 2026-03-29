---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_contexts_map.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_contexts_map from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_contexts_map.json"
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
# file: lupo_contexts_map.md

# lupo_contexts_map

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_contexts_map`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `contexts_map_id` | `bigint NOT NULL` |
| `context_id` | `bigint NOT NULL` |
| `item_type` | `varchar(50) NOT NULL` |
| `item_slug` | `varchar(255) NOT NULL` |
| `description` | `text` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL DEFAULT 0` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_contexts_map_idx_context_id` | `context_id` | no |
| `lupo_contexts_map_idx_context_item` | `context_id`, `item_type`, `item_slug` | no |
| `lupo_contexts_map_idx_is_deleted` | `is_deleted` | no |
| `lupo_contexts_map_idx_item_slug` | `item_slug` | no |
| `lupo_contexts_map_idx_item_type` | `item_type` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
