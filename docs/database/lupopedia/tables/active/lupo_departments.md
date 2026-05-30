---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_departments.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_departments.md
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
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
