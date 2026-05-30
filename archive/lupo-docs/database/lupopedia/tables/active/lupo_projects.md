---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_projects.md"
  web_path: null
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: documentation
  artifact_kind: table
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
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
