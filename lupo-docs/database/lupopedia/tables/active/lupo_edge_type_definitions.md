---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_edge_type_definitions.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_edge_type_definitions from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_edge_type_definitions.json"
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
# file: lupo_edge_type_definitions.md

# lupo_edge_type_definitions

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_edge_type_definitions`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `edge_type_definition_id` | `bigint NOT NULL` |
| `edge_type` | `varchar(100) NOT NULL` |
| `domain` | `varchar(100) NOT NULL` |
| `description` | `text NOT NULL` |
| `allowed_left_object_types` | `text NOT NULL` |
| `allowed_right_object_types` | `text NOT NULL` |
| `is_bidirectional` | `tinyint NOT NULL DEFAULT 0` |
| `semantic_meaning` | `text` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `created_by_actor_id` | `bigint NOT NULL` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_edge_type_definitions_idx_domain` | `domain` | no |
| `lupo_edge_type_definitions_unique_edge_type` | `edge_type` | yes |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
