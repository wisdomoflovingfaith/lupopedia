---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_memory_rollups.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_memory_rollups.md
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
# file: lupo_memory_rollups.md

# lupo_memory_rollups

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_memory_rollups`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `memory_rollup_id` | `bigint NOT NULL` |
| `actor_id` | `int NOT NULL` |
| `summary` | `text NOT NULL` |
| `source_event_ids` | `text NOT NULL` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_memory_rollups_idx_actor_created` | `actor_id`, `created_ymdhis` | no |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
