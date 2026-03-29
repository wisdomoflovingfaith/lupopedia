---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_doctrine_evolution_audit.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_doctrine_evolution_audit from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_doctrine_evolution_audit.json"
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
# file: lupo_doctrine_evolution_audit.md

# lupo_doctrine_evolution_audit

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_doctrine_evolution_audit`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `doctrine_evolution_audit_id` | `bigint NOT NULL` |
| `refinement_id` | `bigint NOT NULL` |
| `evolution_step` | `tinyint NOT NULL` |
| `step_description` | `varchar(255) NOT NULL` |
| `step_status` | `varchar(64) DEFAULT 'pending'` |
| `step_metadata_json` | `json` |
| `started_ymdhis` | `bigint` |
| `completed_ymdhis` | `bigint` |
| `audit_version` | `varchar(20) DEFAULT '3.0.0'` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_doctrine_evolution_audit_idx_completion_time` | `completed_ymdhis` | no |
| `lupo_doctrine_evolution_audit_idx_refinement_step` | `refinement_id`, `evolution_step` | no |
| `lupo_doctrine_evolution_audit_idx_step_status` | `step_status` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
