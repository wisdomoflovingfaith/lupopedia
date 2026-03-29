---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_channel_boot_lifecycle.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "channels"
  purpose: "Normalized table documentation for lupo_channel_boot_lifecycle from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_channel_boot_lifecycle.json"
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
# file: lupo_channel_boot_lifecycle.md

# lupo_channel_boot_lifecycle

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_channel_boot_lifecycle`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `lifecycle_id` | `bigint NOT NULL auto_increment` |
| `channel_id` | `bigint NOT NULL` |
| `actor_id` | `bigint NOT NULL` |
| `session_id` | `varchar(64) NOT NULL` |
| `lifecycle_start_time` | `bigint NOT NULL` |
| `lifecycle_end_time` | `bigint` |
| `lifecycle_status` | `varchar(64) NOT NULL DEFAULT 'started'` |
| `lifecycle_type` | `varchar(64) NOT NULL` |
| `total_channels` | `int NOT NULL DEFAULT 0` |
| `channels_processed` | `int NOT NULL DEFAULT 0` |
| `channels_successful` | `int NOT NULL DEFAULT 0` |
| `channels_failed` | `int NOT NULL DEFAULT 0` |
| `lifecycle_duration_ms` | `int` |
| `error_details` | `json` |
| `performance_metrics` | `json` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_channel_boot_lifecycle_fk_lifecycle_channel` | `channel_id` | no |
| `lupo_channel_boot_lifecycle_idx_actor_session` | `actor_id`, `session_id` | no |
| `lupo_channel_boot_lifecycle_idx_status_time` | `lifecycle_status`, `lifecycle_start_time` | no |
| `lupo_channel_boot_lifecycle_idx_type_time` | `lifecycle_type`, `lifecycle_start_time` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
