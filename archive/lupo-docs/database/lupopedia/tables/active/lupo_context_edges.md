---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_context_edges.md"
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
# file: lupo_context_edges.md

# lupo_context_edges

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_context_edges`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `edge_id` | `bigint NOT NULL` |
| `source_type` | `varchar(64) NOT NULL` |
| `source_id` | `bigint NOT NULL` |
| `target_type` | `varchar(64) NOT NULL` |
| `target_id` | `bigint NOT NULL` |
| `edge_type` | `varchar(64) NOT NULL` |
| `metadata_json` | `text` |
| `created_ymdhis` | `bigint NOT NULL` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_deleted` | `tinyint DEFAULT 0` |
| `deleted_ymdhis` | `bigint DEFAULT 0` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `idx_created` | `created_ymdhis` | no |
| `idx_source` | `source_type`, `source_id` | no |
| `idx_target` | `target_type`, `target_id` | no |
| `idx_type` | `edge_type` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
