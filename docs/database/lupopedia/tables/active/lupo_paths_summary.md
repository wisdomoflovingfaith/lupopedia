---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_paths_summary.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_paths_summary.md
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
# file: lupo_paths_summary.md

# lupo_paths_summary

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_paths_summary`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `summary_id` | `bigint NOT NULL auto_increment` |
| `path_id` | `bigint NOT NULL` |
| `total_count` | `bigint NOT NULL DEFAULT 0` |
| `last_used_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL DEFAULT 0` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_paths_summary_idx_path` | `path_id` | no |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
