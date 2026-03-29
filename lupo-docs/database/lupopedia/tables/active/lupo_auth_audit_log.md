---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_auth_audit_log.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "auth"
  purpose: "Normalized table documentation for lupo_auth_audit_log from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_auth_audit_log.json"
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
# file: lupo_auth_audit_log.md

# lupo_auth_audit_log

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_auth_audit_log`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `auth_audit_log_id` | `bigint NOT NULL` |
| `user_id` | `bigint` |
| `crafty_operator_id` | `int` |
| `event_type` | `varchar(50) NOT NULL` |
| `system_context` | `varchar(50) NOT NULL` |
| `ip_address` | `varchar(45)` |
| `user_agent` | `text` |
| `event_data` | `json` |
| `success` | `tinyint NOT NULL DEFAULT 1` |
| `error_message` | `text` |
| `created_at` | `bigint` |
| `updated_at` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_auth_audit_log_idx_crafty_operator_id` | `crafty_operator_id` | no |
| `lupo_auth_audit_log_idx_created_at` | `created_at` | no |
| `lupo_auth_audit_log_idx_event_type` | `event_type` | no |
| `lupo_auth_audit_log_idx_success` | `success` | no |
| `lupo_auth_audit_log_idx_system_context` | `system_context` | no |
| `lupo_auth_audit_log_idx_user_id` | `user_id` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
