---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_projects.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_projects from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_projects.json"
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
# file: lupo_projects.md

# lupo_projects

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_projects`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `project_id` | `bigint NOT NULL` |
| `project_key` | `varchar(64) NOT NULL` |
| `project_slug` | `varchar(255) NOT NULL` |
| `project_name` | `varchar(255) NOT NULL` |
| `federation_node_id` | `bigint NOT NULL` |
| `default_channel_id` | `bigint` |
| `orchestrator_id` | `bigint NOT NULL` |
| `project_type` | `varchar(64) DEFAULT 'standard'` |
| `description` | `text` |
| `github_repository` | `varchar(512)` |
| `status` | `varchar(32) NOT NULL DEFAULT 'active'` |
| `is_active` | `tinyint NOT NULL DEFAULT 1` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `is_archived` | `tinyint NOT NULL DEFAULT 0` |
| `is_frozen` | `tinyint NOT NULL DEFAULT 0` |
| `metadata_json` | `json` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint DEFAULT 0` |
| `created_by_actor_id` | `bigint` |
| `updated_by_actor_id` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_projects_idx_created` | `created_ymdhis` | no |
| `lupo_projects_idx_default_channel` | `default_channel_id` | no |
| `lupo_projects_idx_federation_node` | `federation_node_id`, `status`, `is_deleted` | no |
| `lupo_projects_idx_orchestrator` | `orchestrator_id`, `status`, `is_deleted` | no |
| `lupo_projects_idx_project_key` | `project_key`, `federation_node_id` | no |
| `lupo_projects_idx_project_slug` | `project_slug`, `federation_node_id` | no |
| `lupo_projects_idx_status` | `status`, `is_active`, `is_deleted` | no |
| `lupo_projects_idx_updated` | `updated_ymdhis` | no |
| `uk_project_key_node` | `project_key`, `federation_node_id` | yes |
| `uk_project_slug_node` | `project_slug`, `federation_node_id` | yes |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
