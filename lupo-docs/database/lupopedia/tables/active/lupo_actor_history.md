---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_actor_history.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_actor_history from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_actor_history.json"
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
# file: lupo_actor_history.md

# lupo_actor_history

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_actor_history`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `history_id` | `bigint NOT NULL` |
| `actor_id` | `bigint NOT NULL` |
| `achievement_id` | `varchar(100)` |
| `title` | `varchar(255) NOT NULL` |
| `description` | `text` |
| `impact` | `text` |
| `date_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `channel_id` | `bigint` |
| `tags` | `json` |
| `metrics` | `json` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_actor_history_idx_actor_id` | `actor_id` | no |
| `lupo_actor_history_idx_channel_id` | `channel_id` | no |
| `lupo_actor_history_idx_date_ymdhis` | `date_ymdhis` | no |
| `lupo_actor_history_idx_is_deleted` | `is_deleted` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
