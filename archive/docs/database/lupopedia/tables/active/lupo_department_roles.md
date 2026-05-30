---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "docs/database/lupopedia/tables/active/lupo_department_roles.md"
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
# file: lupo_department_roles.md

# lupo_department_roles

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_department_roles`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `department_role_id` | `bigint NOT NULL` |
| `actor_id` | `bigint NOT NULL` |
| `department_id` | `bigint NOT NULL` |
| `role_key` | `varchar(64) NOT NULL` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_department_roles_idx_actor_id` | `actor_id` | no |
| `lupo_department_roles_idx_department_id` | `department_id` | no |
| `lupo_department_roles_idx_role_key` | `role_key` | no |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
