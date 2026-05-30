---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "docs/database/lupopedia/tables/active/lupo_hotfix_registry.md"
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
