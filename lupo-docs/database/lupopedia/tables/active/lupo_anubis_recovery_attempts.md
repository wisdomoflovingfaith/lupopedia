---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_anubis_recovery_attempts.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_anubis_recovery_attempts from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_anubis_recovery_attempts.json"
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
# file: lupo_anubis_recovery_attempts.md

# lupo_anubis_recovery_attempts

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_anubis_recovery_attempts`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `attempt_id` | `bigint NOT NULL auto_increment` |
| `queue_id` | `bigint NOT NULL` |
| `attempt_number` | `tinyint NOT NULL` |
| `attempt_utc` | `bigint NOT NULL` |
| `strategy` | `varchar(64)` |
| `success` | `tinyint DEFAULT 0` |
| `generated_header` | `text` |
| `error_details` | `text` |
| `recovered_file_path` | `varchar(512)` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_anubis_recovery_attempts_idx_queue_attempt` | `queue_id`, `attempt_number` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
