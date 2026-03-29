---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_actor_channel_roles.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_actor_channel_roles from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_actor_channel_roles.json"
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
