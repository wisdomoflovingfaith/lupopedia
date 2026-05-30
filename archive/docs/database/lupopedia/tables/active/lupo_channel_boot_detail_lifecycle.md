---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "docs/database/lupopedia/tables/active/lupo_channel_boot_detail_lifecycle.md"
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
# file: lupo_channel_boot_detail_lifecycle.md

# lupo_channel_boot_detail_lifecycle

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_channel_boot_detail_lifecycle`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `detail_lifecycle_id` | `bigint NOT NULL auto_increment` |
| `lifecycle_id` | `bigint NOT NULL` |
| `channel_id` | `bigint NOT NULL` |
| `detail_start_time` | `bigint NOT NULL` |
| `detail_end_time` | `bigint` |
| `detail_status` | `varchar(64) NOT NULL DEFAULT 'started'` |
| `content_items_loaded` | `int NOT NULL DEFAULT 0` |
| `total_content_items` | `int NOT NULL DEFAULT 0` |
| `detail_duration_ms` | `int` |
| `error_message` | `text` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_channel_boot_detail_lifecycle_fk_detail_lifecycle` | `lifecycle_id` | no |
| `lupo_channel_boot_detail_lifecycle_idx_channel` | `channel_id` | no |
| `lupo_channel_boot_detail_lifecycle_idx_status_time` | `detail_status`, `detail_start_time` | no |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
