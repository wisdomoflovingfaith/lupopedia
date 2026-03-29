---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_system_commands.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_system_commands from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_system_commands.json"
    type: "references"
    weight: 1.0
    reason: "authoritative TOON JSON source"
lupopedia.footer:
  last_verified: "20260328013000"
  last_verified_by: "hephaestus"
  last_verified_by_actor_id: 23
  generated: true
  provenance: "stage3_track_c_normalization"
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
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
