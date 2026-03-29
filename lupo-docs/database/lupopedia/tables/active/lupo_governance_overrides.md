---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_governance_overrides.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "governance"
  purpose: "Normalized table documentation for lupo_governance_overrides from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_governance_overrides.json"
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
# file: lupo_governance_overrides.md

# lupo_governance_overrides

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_governance_overrides`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `governance_overrid_id` | `bigint NOT NULL` |
| `agent_id` | `bigint` |
| `applied_by_agent` | `bigint` |
| `override_type` | `varchar(100) NOT NULL` |
| `target_key` | `varchar(150)` |
| `old_value` | `text` |
| `new_value` | `text` |
| `reason_text` | `text` |
| `metadata_json` | `json` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `expires_ymdhis` | `bigint` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_governance_overrides_idx_agent` | `agent_id` | no |
| `lupo_governance_overrides_idx_applied_by` | `applied_by_agent` | no |
| `lupo_governance_overrides_idx_created` | `created_ymdhis` | no |
| `lupo_governance_overrides_idx_target` | `target_key` | no |
| `lupo_governance_overrides_idx_type` | `override_type` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
