---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_channel_escalations.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "channels"
  purpose: "Normalized table documentation for lupo_channel_escalations from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_channel_escalations.json"
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
# file: lupo_channel_escalations.md

# lupo_channel_escalations

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_channel_escalations`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `escalation_id` | `bigint NOT NULL` |
| `channel_id` | `bigint NOT NULL` |
| `thread_id` | `bigint` |
| `actor_id` | `bigint` |
| `escalated_to_actor_id` | `bigint` |
| `escalation_reason` | `varchar(512)` |
| `metadata_json` | `json` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_channel_escalations_idx_actor_id` | `actor_id` | no |
| `lupo_channel_escalations_idx_channel_id` | `channel_id` | no |
| `lupo_channel_escalations_idx_escalated_to_actor_id` | `escalated_to_actor_id` | no |
| `lupo_channel_escalations_idx_thread_id` | `thread_id` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
