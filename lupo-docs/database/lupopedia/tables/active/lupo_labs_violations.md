---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_labs_violations.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_labs_violations from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_labs_violations.json"
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
# file: lupo_labs_violations.md

# lupo_labs_violations

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_labs_violations`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `labs_violation_id` | `bigint NOT NULL` |
| `actor_id` | `bigint NOT NULL` |
| `certificate_id` | `varchar(64) NOT NULL` |
| `violation_code` | `varchar(64) NOT NULL` |
| `violation_description` | `text` |
| `violation_metadata` | `json` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_labs_violations_idx_actor` | `actor_id` | no |
| `lupo_labs_violations_idx_certificate` | `certificate_id` | no |
| `lupo_labs_violations_idx_created` | `created_ymdhis` | no |
| `lupo_labs_violations_idx_deleted` | `is_deleted` | no |
| `lupo_labs_violations_idx_violation_code` | `violation_code` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
