---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_tickets.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_tickets from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_tickets.json"
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
# file: lupo_tickets.md

# lupo_tickets

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_tickets`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `ticket_id` | `bigint NOT NULL` |
| `channel_id` | `bigint NOT NULL` |
| `actor_id` | `bigint NOT NULL` |
| `status` | `varchar(64) NOT NULL DEFAULT 'open'` |
| `priority` | `varchar(64) NOT NULL DEFAULT 'medium'` |
| `subject` | `varchar(255) NOT NULL` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `metadata_json` | `json` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_tickets_idx_actor` | `actor_id` | no |
| `lupo_tickets_idx_channel` | `channel_id` | no |
| `lupo_tickets_idx_status` | `status` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
