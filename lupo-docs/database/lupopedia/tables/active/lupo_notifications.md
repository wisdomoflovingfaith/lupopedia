---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_notifications.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_notifications from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_notifications.json"
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
# file: lupo_notifications.md

# lupo_notifications

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_notifications`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `notification_id` | `bigint NOT NULL` |
| `actor_id` | `bigint NOT NULL` |
| `from_actor_id` | `bigint` |
| `to_actor_id` | `bigint` |
| `channel_id` | `bigint` |
| `notification_type` | `varchar(64) NOT NULL` |
| `title` | `varchar(255)` |
| `message` | `text` |
| `link_url` | `varchar(255)` |
| `is_read` | `tinyint NOT NULL DEFAULT 0` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |

### Indexes

| Index | Columns | Unique |
|---|---|---|

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
