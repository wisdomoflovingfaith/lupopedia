---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_actor_channel_roles.md"
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
# file: lupo_actor_channel_roles.md

# lupo_actor_channel_roles

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_actor_channel_roles`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `actor_channel_role_id` | `bigint NOT NULL` |
| `actor_id` | `bigint NOT NULL` |
| `actor_name` | `varchar(64)` |
| `channel_id` | `bigint NOT NULL` |
| `role_key` | `varchar(64) NOT NULL` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |
| `handshake_metadata_json` | `json` |
| `awareness_snapshot_json` | `json` |
| `protocol_completion_status` | `varchar(64) DEFAULT 'pending'` |
| `protocol_version` | `varchar(20) DEFAULT '3.0.0'` |
| `join_sequence_step` | `tinyint DEFAULT 0` |
| `handshake_completed_ymdhis` | `bigint` |
| `awareness_completed_ymdhis` | `bigint` |
| `cjp_completed_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_actor_channel_roles_idx_actor_id` | `actor_id` | no |
| `lupo_actor_channel_roles_idx_actor_name` | `actor_name` | no |
| `lupo_actor_channel_roles_idx_channel_id` | `channel_id` | no |
| `lupo_actor_channel_roles_idx_join_sequence_step` | `join_sequence_step` | no |
| `lupo_actor_channel_roles_idx_protocol_completion_status` | `protocol_completion_status` | no |
| `lupo_actor_channel_roles_idx_protocol_version` | `protocol_version` | no |
| `lupo_actor_channel_roles_idx_role_key` | `role_key` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
