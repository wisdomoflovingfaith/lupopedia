---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_agent_context_snapshots.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_agent_context_snapshots.md
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
# file: lupo_agent_context_snapshots.md

# lupo_agent_context_snapshots

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_agent_context_snapshots`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `agent_context_snapshot_id` | `bigint NOT NULL` |
| `session_id` | `varchar(100) NOT NULL` |
| `actor_id` | `bigint NOT NULL` |
| `parent_snapshot_id` | `bigint` |
| `snapshot_type` | `varchar(64) NOT NULL DEFAULT 'full'` |
| `snapshot_purpose` | `varchar(50)` |
| `context_data` | `text NOT NULL` |
| `context_summary` | `text` |
| `context_metadata` | `json` |
| `token_count` | `int` |
| `character_count` | `int` |
| `compressed_size` | `int` |
| `compression_ratio` | `float` |
| `compression_method` | `varchar(64) DEFAULT 'gzip'` |
| `serialization_time_ms` | `int` |
| `compression_time_ms` | `int` |
| `related_tool_call_id` | `bigint` |
| `conversation_turn` | `int` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `expires_ymdhis` | `bigint` |
| `is_corrupt` | `tinyint DEFAULT 0` |
| `retention_policy` | `varchar(64) DEFAULT 'temporary'` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_agent_context_snapshots_idx_created` | `created_ymdhis` | no |
| `lupo_agent_context_snapshots_idx_parent` | `parent_snapshot_id` | no |
| `lupo_agent_context_snapshots_idx_related_tool` | `related_tool_call_id` | no |
| `lupo_agent_context_snapshots_idx_retention` | `retention_policy`, `expires_ymdhis` | no |
| `lupo_agent_context_snapshots_idx_session_agent` | `session_id`, `actor_id` | no |
| `lupo_agent_context_snapshots_idx_turn` | `session_id`, `conversation_turn` | no |
| `lupo_agent_context_snapshots_idx_type_purpose` | `snapshot_type`, `snapshot_purpose` | no |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
