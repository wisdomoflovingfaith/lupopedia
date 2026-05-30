---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_uploads.md"
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
# file: lupo_uploads.md

# lupo_uploads

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_uploads`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `upload_id` | `bigint NOT NULL` |
| `actor_id` | `bigint NOT NULL` |
| `channel_id` | `bigint` |
| `original_filename` | `varchar(255) NOT NULL` |
| `stored_filename` | `varchar(255) NOT NULL` |
| `file_extension` | `varchar(16) NOT NULL` |
| `mime_type` | `varchar(128) NOT NULL` |
| `file_size_bytes` | `bigint NOT NULL` |
| `storage_path` | `varchar(512) NOT NULL` |
| `metadata_json` | `json` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_uploads_idx_actor_id` | `actor_id` | no |
| `lupo_uploads_idx_channel_id` | `channel_id` | no |
| `lupo_uploads_idx_created_ymdhis` | `created_ymdhis` | no |
| `lupo_uploads_idx_file_extension` | `file_extension` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
