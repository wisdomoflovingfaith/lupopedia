---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_crm_leads.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_crm_leads from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_crm_leads.json"
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
# file: lupo_crm_leads.md

# lupo_crm_leads

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_crm_leads`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `crm_lead_id` | `bigint NOT NULL` |
| `email` | `varchar(255)` |
| `phone` | `varchar(45)` |
| `first_name` | `varchar(100)` |
| `last_name` | `varchar(100)` |
| `source` | `varchar(100)` |
| `status` | `varchar(50) NOT NULL DEFAULT 'new'` |
| `lead_score` | `int NOT NULL DEFAULT 0` |
| `assigned_to` | `bigint` |
| `lead_data` | `text` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
