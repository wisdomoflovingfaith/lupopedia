---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_channel_state.md"
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
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
