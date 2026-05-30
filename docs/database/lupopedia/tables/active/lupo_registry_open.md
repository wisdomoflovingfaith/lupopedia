---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_registry_open.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_registry_open.md
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
# file: lupo_registry_open.md

# lupo_registry_open

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_registry_open`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `unregistry_id` | `bigint NOT NULL auto_increment` |
| `entity_type` | `varchar(50) NOT NULL` |
| `entity_index_id` | `bigint NOT NULL` |
| `reason` | `varchar(255)` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `idx_registry_open_entity_type` | `entity_type` | no |
| `idx_registry_open_unique` | `entity_type`, `entity_index_id` | yes |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
