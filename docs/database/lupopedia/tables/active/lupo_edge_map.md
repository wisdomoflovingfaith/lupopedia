---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_edge_map.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_edge_map.md
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
