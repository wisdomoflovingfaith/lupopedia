---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_channel_state.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_channel_state.md
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
# file: lupo_channel_state.md

# lupo_channel_state

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_channel_state`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `channel_state_id` | `bigint NOT NULL` |
| `channel_id` | `bigint NOT NULL` |
| `active_actors_json` | `json` |
| `speaker_actors_json` | `json` |
| `observer_actors_json` | `json` |
| `layers_enabled_json` | `json` |
| `operational_mode` | `varchar(32)` |
| `emotional_state_json` | `json` |
| `mood_framework` | `varchar(32) NOT NULL DEFAULT 'western_analytical'` |
| `recent_topics_json` | `json` |
| `semantic_weight` | `float DEFAULT 0` |
| `trend_score` | `float DEFAULT 0` |
| `last_activity_ymdhis` | `bigint` |
| `context_vector` | `blob` |
| `routing_rules` | `varchar(32)` |
| `edge_visibility` | `varchar(32)` |
| `retention_policy` | `varchar(32)` |
| `decay_policy` | `varchar(32)` |
| `archive_flag` | `tinyint DEFAULT 0` |
| `metadata_json` | `json` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_channel_state_idx_channel_id` | `channel_id` | no |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
