---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_banned_actors.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_banned_actors from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_banned_actors.json"
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
# file: lupo_banned_actors.md

# lupo_banned_actors

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_banned_actors`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `banned_actor_id` | `bigint NOT NULL` |
| `actor_id` | `bigint NOT NULL` |
| `actor_name` | `varchar(64)` |
| `ip_address` | `varchar(45)` |
| `reason` | `varchar(500) NOT NULL` |
| `banned_ymdhis` | `bigint NOT NULL` |
| `banned_by_actor_id` | `bigint` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_banned_actors_idx_actor_id` | `actor_id` | no |
| `lupo_banned_actors_idx_actor_name` | `actor_name` | no |
| `lupo_banned_actors_idx_ip_address` | `ip_address` | no |
| `lupo_banned_actors_idx_is_deleted` | `is_deleted` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
