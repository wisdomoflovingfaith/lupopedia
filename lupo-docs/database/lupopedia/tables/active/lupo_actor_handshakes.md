---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_actor_handshakes.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_actor_handshakes from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_actor_handshakes.json"
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
# file: lupo_actor_handshakes.md

# lupo_actor_handshakes

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_actor_handshakes`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `actor_handshake_id` | `bigint NOT NULL` |
| `actor_id` | `bigint NOT NULL` |
| `actor_type` | `varchar(32) NOT NULL` |
| `utc_timestamp` | `bigint NOT NULL` |
| `purpose` | `varchar(500)` |
| `constraints_json` | `json` |
| `forbidden_actions_json` | `json` |
| `context` | `text` |
| `expires_utc` | `bigint` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_actor_handshakes_idx_actor_id` | `actor_id` | no |
| `lupo_actor_handshakes_idx_is_deleted` | `is_deleted` | no |
| `lupo_actor_handshakes_idx_utc_timestamp` | `utc_timestamp` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
