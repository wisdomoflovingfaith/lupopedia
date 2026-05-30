---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_permissions.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_permissions.md
  status: ''
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: documentation
  artifact_kind: table
  channel_key: null
  federation_node_id: null
  thread_key: ''
  lupopedia.schema: null
  prd_cluster: null
  title: ''
  summary: ''
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
