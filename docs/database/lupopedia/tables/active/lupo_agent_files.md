---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_agent_files.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_agent_files.md
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
# file: lupo_agent_files.md

# lupo_agent_files

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_agent_files`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `file_id` | `bigint NOT NULL` |
| `agent_id` | `bigint NOT NULL` |
| `file_type` | `varchar(50) NOT NULL` |
| `file_name` | `varchar(255) NOT NULL` |
| `file_path` | `varchar(500) NOT NULL` |
| `file_hash` | `varchar(64) NOT NULL` |
| `file_size` | `bigint NOT NULL` |
| `mime_type` | `varchar(100)` |
| `upload_ymdhis` | `bigint NOT NULL` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |
| `migrated_from_directory` | `varchar(255)` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_agent_files_idx_agent_id` | `agent_id` | no |
| `lupo_agent_files_idx_file_hash` | `file_hash` | no |
| `lupo_agent_files_idx_file_type` | `file_type` | no |
| `lupo_agent_files_idx_is_deleted` | `is_deleted` | no |
| `lupo_agent_files_idx_upload_ymdhis` | `upload_ymdhis` | no |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
