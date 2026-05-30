---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_hotfix_registry.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_hotfix_registry.md
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
# file: lupo_hotfix_registry.md

# lupo_hotfix_registry

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_hotfix_registry`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `hotfix_id` | `bigint NOT NULL` |
| `hotfix_version` | `varchar(20) NOT NULL` |
| `applied_ymdhis` | `bigint NOT NULL` |
| `applied_by_actor_id` | `bigint` |
| `description` | `text` |
| `metadata_json` | `json` |

### Indexes

| Index | Columns | Unique |
|---|---|---|

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
