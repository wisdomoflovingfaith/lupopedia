---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_visits.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_visits from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_visits.json"
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
# file: lupo_visits.md

# lupo_visits

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_visits`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `visit_id` | `bigint NOT NULL auto_increment` |
| `session_id` | `bigint` |
| `actor_id` | `bigint` |
| `instance_id` | `bigint` |
| `path_url` | `text` |
| `entercontentid` | `bigint` |
| `exitcontentid` | `bigint` |
| `enter_table` | `varchar(255)` |
| `exit_table` | `varchar(255)` |
| `transition_type` | `varchar(64)` |
| `transition_metadata` | `text` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `is_processed` | `tinyint NOT NULL DEFAULT 0` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_visits_idx_actor` | `actor_id` | no |
| `lupo_visits_idx_created` | `created_ymdhis` | no |
| `lupo_visits_idx_enter_exit` | `entercontentid`, `exitcontentid` | no |
| `lupo_visits_idx_is_deleted` | `is_deleted` | no |
| `lupo_visits_idx_is_processed` | `is_processed` | no |
| `lupo_visits_idx_session` | `session_id` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
