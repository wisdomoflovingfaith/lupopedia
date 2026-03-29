---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_context_edges.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_context_edges from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_context_edges.json"
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
# file: lupo_context_edges.md

# lupo_context_edges

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_context_edges`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `edge_id` | `bigint NOT NULL` |
| `source_type` | `varchar(64) NOT NULL` |
| `source_id` | `bigint NOT NULL` |
| `target_type` | `varchar(64) NOT NULL` |
| `target_id` | `bigint NOT NULL` |
| `edge_type` | `varchar(64) NOT NULL` |
| `metadata_json` | `text` |
| `created_ymdhis` | `bigint NOT NULL` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_deleted` | `tinyint DEFAULT 0` |
| `deleted_ymdhis` | `bigint DEFAULT 0` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `idx_created` | `created_ymdhis` | no |
| `idx_source` | `source_type`, `source_id` | no |
| `idx_target` | `target_type`, `target_id` | no |
| `idx_type` | `edge_type` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
