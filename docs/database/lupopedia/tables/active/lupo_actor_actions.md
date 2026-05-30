---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_actor_actions.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_actor_actions.md
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
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
