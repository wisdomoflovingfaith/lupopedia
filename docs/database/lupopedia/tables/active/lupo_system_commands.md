---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_system_commands.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_system_commands.md
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
# file: lupo_system_commands.md

# lupo_system_commands

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_system_commands`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `command_id` | `bigint NOT NULL` |
| `command_type` | `varchar(128) NOT NULL` |
| `command_args_json` | `text` |
| `working_dir` | `varchar(512)` |
| `status` | `varchar(32) NOT NULL` |
| `priority` | `int NOT NULL DEFAULT 0` |
| `created_ymdhis` | `bigint NOT NULL` |
| `scheduled_ymdhis` | `bigint NOT NULL` |
| `started_ymdhis` | `bigint` |
| `finished_ymdhis` | `bigint` |
| `claimed_by_actor_id` | `bigint` |
| `claimed_by_host` | `varchar(256)` |
| `process_id` | `varchar(64)` |
| `attempt_count` | `int NOT NULL DEFAULT 0` |
| `max_attempts` | `int NOT NULL DEFAULT 3` |
| `timeout_seconds` | `int NOT NULL DEFAULT 3600` |
| `return_code` | `int` |
| `output_text` | `text` |
| `output_sha1` | `varchar(64)` |
| `last_heartbeat_ymdhis` | `bigint` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_system_commands_idx_created_ymdhis` | `created_ymdhis` | no |
| `lupo_system_commands_idx_is_deleted` | `is_deleted` | no |
| `lupo_system_commands_idx_status_heartbeat` | `status`, `last_heartbeat_ymdhis` | no |
| `lupo_system_commands_idx_status_priority_scheduled` | `status`, `priority`, `scheduled_ymdhis` | no |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
