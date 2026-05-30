---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_actor_actions.md"
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
# file: lupo_actor_actions.md

# lupo_actor_actions

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_actor_actions`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `actor_action_id` | `bigint NOT NULL` |
| `actor_id` | `bigint NOT NULL` |
| `action_type` | `varchar(64) NOT NULL` |
| `entity_type` | `varchar(64)` |
| `entity_id` | `bigint` |
| `description` | `text` |
| `metadata_json` | `json` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_actor_actions_idx_action_type` | `action_type` | no |
| `lupo_actor_actions_idx_actor` | `actor_id` | no |
| `lupo_actor_actions_idx_entity` | `entity_type`, `entity_id` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
