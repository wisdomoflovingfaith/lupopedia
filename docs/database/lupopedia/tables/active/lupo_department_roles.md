---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_department_roles.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_department_roles.md
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
