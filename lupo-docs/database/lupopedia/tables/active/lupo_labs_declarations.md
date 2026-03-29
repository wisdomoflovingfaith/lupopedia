---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_labs_declarations.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_labs_declarations from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_labs_declarations.json"
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
# file: lupo_labs_declarations.md

# lupo_labs_declarations

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_labs_declarations`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `labs_declaration_id` | `bigint NOT NULL` |
| `actor_id` | `bigint NOT NULL` |
| `certificate_id` | `varchar(64) NOT NULL` |
| `declaration_timestamp` | `bigint NOT NULL` |
| `declarations_json` | `json NOT NULL` |
| `validation_status` | `varchar(64) NOT NULL DEFAULT 'valid'` |
| `labs_version` | `varchar(16) NOT NULL DEFAULT '1.0'` |
| `next_revalidation_ymdhis` | `bigint NOT NULL` |
| `validation_log_json` | `json` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_labs_declarations_idx_actor_id` | `actor_id` | no |
| `lupo_labs_declarations_idx_actor_status` | `actor_id`, `validation_status`, `is_deleted` | no |
| `lupo_labs_declarations_idx_certificate_id` | `certificate_id` | no |
| `lupo_labs_declarations_idx_next_revalidation` | `next_revalidation_ymdhis` | no |
| `lupo_labs_declarations_idx_revalidation_due` | `next_revalidation_ymdhis`, `validation_status`, `is_deleted` | no |
| `lupo_labs_declarations_idx_validation_status` | `validation_status` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
