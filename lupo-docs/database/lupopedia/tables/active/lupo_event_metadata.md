---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_event_metadata.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_event_metadata from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_event_metadata.json"
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
# file: lupo_event_metadata.md

# lupo_event_metadata

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_event_metadata`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `metadata_id` | `bigint NOT NULL` |
| `event_id` | `bigint NOT NULL` |
| `metadata_key` | `varchar(100) NOT NULL` |
| `metadata_value` | `text` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_event_metadata_idx_created_ymdhis` | `created_ymdhis` | no |
| `lupo_event_metadata_idx_event_id` | `event_id` | no |
| `lupo_event_metadata_idx_metadata_key` | `metadata_key` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
