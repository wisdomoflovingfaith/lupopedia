---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_contexts_map.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_contexts_map.md
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
# file: lupo_contexts_map.md

# lupo_contexts_map

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_contexts_map`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `contexts_map_id` | `bigint NOT NULL` |
| `context_id` | `bigint NOT NULL` |
| `item_type` | `varchar(50) NOT NULL` |
| `item_slug` | `varchar(255) NOT NULL` |
| `description` | `text` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL DEFAULT 0` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_contexts_map_idx_context_id` | `context_id` | no |
| `lupo_contexts_map_idx_context_item` | `context_id`, `item_type`, `item_slug` | no |
| `lupo_contexts_map_idx_is_deleted` | `is_deleted` | no |
| `lupo_contexts_map_idx_item_slug` | `item_slug` | no |
| `lupo_contexts_map_idx_item_type` | `item_type` | no |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
