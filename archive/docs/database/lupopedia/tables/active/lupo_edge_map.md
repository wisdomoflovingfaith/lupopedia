---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "docs/database/lupopedia/tables/active/lupo_edge_map.md"
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
# file: lupo_edge_map.md

# lupo_edge_map

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_edge_map`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `edge_map_id` | `bigint NOT NULL auto_increment` |
| `edge_id` | `bigint NOT NULL` |
| `edge_type_id` | `bigint NOT NULL` |
| `source_type` | `varchar(64) NOT NULL` |
| `source_id` | `bigint NOT NULL` |
| `target_type` | `varchar(64) NOT NULL` |
| `target_id` | `bigint NOT NULL` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_edge_map_idx_edge` | `edge_id` | no |
| `lupo_edge_map_idx_source` | `source_type`, `source_id` | no |
| `lupo_edge_map_idx_target` | `target_type`, `target_id` | no |
| `lupo_edge_map_idx_type` | `edge_type_id` | no |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
