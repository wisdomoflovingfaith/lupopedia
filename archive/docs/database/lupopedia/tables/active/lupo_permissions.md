---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "docs/database/lupopedia/tables/active/lupo_permissions.md"
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
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
