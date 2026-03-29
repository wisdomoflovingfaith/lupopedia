---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_permissions.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_permissions from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_permissions.json"
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
# file: lupo_permissions.md

# lupo_permissions

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_permissions`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `permission_id` | `bigint NOT NULL` |
| `target_type` | `varchar(64) NOT NULL` |
| `target_id` | `bigint NOT NULL` |
| `user_id` | `bigint` |
| `department_id` | `bigint` |
| `permission` | `varchar(64) NOT NULL DEFAULT 'read'` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_permissions_idx_created_ymdhis` | `created_ymdhis` | no |
| `lupo_permissions_idx_deleted` | `is_deleted`, `deleted_ymdhis` | no |
| `lupo_permissions_idx_department` | `department_id` | no |
| `lupo_permissions_idx_permission` | `permission` | no |
| `lupo_permissions_idx_target` | `target_type`, `target_id` | no |
| `lupo_permissions_idx_user` | `user_id` | no |
| `lupo_permissions_uniq_target_department` | `target_type`, `target_id`, `department_id` | yes |
| `lupo_permissions_uniq_target_user` | `target_type`, `target_id`, `user_id` | yes |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
