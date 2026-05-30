---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_tasks.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_tasks.md
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
# file: lupo_tasks.md

# lupo_tasks

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_tasks`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `task_id` | `bigint NOT NULL` |
| `task_key` | `varchar(64) NOT NULL` |
| `channel_id` | `bigint NOT NULL` |
| `owner_actor_id` | `bigint NOT NULL` |
| `title` | `varchar(255) NOT NULL` |
| `description` | `text` |
| `prompt_path` | `varchar(512)` |
| `acting_as_actor_id` | `bigint` |
| `estimated_duration_seconds` | `int` |
| `actual_duration_seconds` | `int` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `started_ymdhis` | `bigint` |
| `completed_ymdhis` | `bigint` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |
| `metadata_json` | `text` |
| `task_type` | `varchar(64)` |
| `task_status` | `varchar(64)` |
| `task_priority` | `enum('low','normal','high','urgent','critical') NOT NULL DEFAULT 'normal'` |
| `parent_agent_id` | `bigint` |
| `consensus_hash` | `varchar(255)` |
| `approval_chain_json` | `json` |
| `task_embeddings` | `text` |
| `visibility_status` | `varchar(32) NOT NULL DEFAULT 'active'` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_tasks_idx_acting_as_actor_id` | `acting_as_actor_id` | no |
| `lupo_tasks_idx_channel_id` | `channel_id` | no |
| `lupo_tasks_idx_created_ymdhis` | `created_ymdhis` | no |
| `lupo_tasks_idx_is_deleted` | `is_deleted` | no |
| `lupo_tasks_idx_owner_actor_id` | `owner_actor_id` | no |
| `lupo_tasks_idx_parent_agent_id` | `parent_agent_id` | no |
| `lupo_tasks_idx_task_priority` | `task_priority` | no |
| `lupo_tasks_idx_task_status` | `task_status` | no |
| `lupo_tasks_idx_task_type` | `task_type` | no |
| `lupo_tasks_idx_visibility_status` | `visibility_status` | no |
| `lupo_tasks_uniq_task_key_per_channel` | `task_key`, `channel_id` | yes |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
