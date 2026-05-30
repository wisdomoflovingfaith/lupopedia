---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_modules.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_modules.md
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
# file: lupo_modules.md

# lupo_modules

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_modules`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `module_id` | `bigint NOT NULL` |
| `module_key` | `varchar(100) NOT NULL` |
| `module_name` | `varchar(150) NOT NULL` |
| `namespace` | `varchar(100) NOT NULL` |
| `version` | `varchar(50) NOT NULL` |
| `version_code` | `int NOT NULL` |
| `minimum_core_version` | `varchar(50) NOT NULL` |
| `user_path` | `varchar(255)` |
| `admin_path` | `varchar(255)` |
| `api_path` | `varchar(255)` |
| `route_params` | `text` |
| `description` | `text` |
| `author` | `varchar(100)` |
| `website` | `varchar(255)` |
| `icon` | `varchar(100) DEFAULT 'puzzle-piece'` |
| `dependencies` | `text` |
| `conflicts` | `text` |
| `config_json` | `text NOT NULL` |
| `is_system` | `tinyint NOT NULL DEFAULT 0` |
| `is_active` | `tinyint NOT NULL DEFAULT 0` |
| `federation_node_id` | `bigint NOT NULL DEFAULT 1` |
| `settings` | `text` |
| `installed_ymdhis` | `bigint` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_modules_idx_installed` | `installed_ymdhis` | no |
| `lupo_modules_idx_namespace` | `namespace` | no |
| `lupo_modules_idx_status` | `is_active`, `is_deleted` | no |
| `lupo_modules_idx_system` | `is_system` | no |
| `lupo_modules_uq_module_key` | `module_key` | yes |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
