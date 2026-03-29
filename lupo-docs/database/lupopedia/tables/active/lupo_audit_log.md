---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_audit_log.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_audit_log from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_audit_log.json"
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
# file: lupo_audit_log.md

# lupo_audit_log

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_audit_log`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `audit_log_id` | `bigint NOT NULL` |
| `channel_id` | `bigint NOT NULL` |
| `entity_type` | `varchar(32) NOT NULL` |
| `entity_id` | `bigint NOT NULL` |
| `event_type` | `varchar(100) NOT NULL` |
| `table_name` | `varchar(100)` |
| `table_id` | `bigint` |
| `payload_json` | `text` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_audit_log_idx_entity` | `entity_type`, `entity_id` | no |
| `lupo_audit_log_idx_event` | `event_type` | no |
| `lupo_audit_log_idx_table` | `table_name`, `table_id` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
