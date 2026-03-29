---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_departments.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_departments from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_departments.json"
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
# file: lupo_departments.md

# lupo_departments

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_departments`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `department_id` | `bigint NOT NULL` |
| `federation_node_id` | `bigint NOT NULL` |
| `name` | `varchar(64) NOT NULL` |
| `description` | `text` |
| `department_type` | `varchar(32) NOT NULL DEFAULT 'general'` |
| `default_actor_id` | `bigint NOT NULL DEFAULT 1` |
| `settings_json` | `json` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_departments_idx_federation_node` | `federation_node_id` | no |
| `lupo_departments_idx_name` | `name` | no |
| `lupo_departments_idx_type` | `department_type` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
