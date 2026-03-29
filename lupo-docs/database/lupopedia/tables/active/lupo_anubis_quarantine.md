---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_anubis_quarantine.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_anubis_quarantine from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_anubis_quarantine.json"
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
# file: lupo_anubis_quarantine.md

# lupo_anubis_quarantine

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_anubis_quarantine`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `quarantine_id` | `bigint NOT NULL auto_increment` |
| `queue_id` | `bigint NOT NULL` |
| `file_path` | `varchar(512) NOT NULL` |
| `file_hash` | `varchar(64)` |
| `file_content` | `longtext` |
| `quarantine_path` | `varchar(512) NOT NULL` |
| `reason` | `varchar(255) NOT NULL` |
| `quarantined_utc` | `bigint NOT NULL` |
| `expires_utc` | `bigint` |
| `reviewed_by_actor_id` | `bigint` |
| `reviewed_utc` | `bigint` |
| `resolution` | `varchar(64)` |
| `is_deleted` | `tinyint DEFAULT 0` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_anubis_quarantine_idx_expires` | `expires_utc` | no |
| `lupo_anubis_quarantine_idx_queue` | `queue_id` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
