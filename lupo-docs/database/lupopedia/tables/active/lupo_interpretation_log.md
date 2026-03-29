---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_interpretation_log.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_interpretation_log from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_interpretation_log.json"
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
# file: lupo_interpretation_log.md

# lupo_interpretation_log

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_interpretation_log`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `interpretation_log_id` | `bigint NOT NULL` |
| `agent_id` | `bigint NOT NULL` |
| `entity_type` | `varchar(32) NOT NULL` |
| `entity_id` | `bigint NOT NULL` |
| `interpretation` | `text NOT NULL` |
| `confidence_score` | `decimal(5,2)` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |
| `metadata_json` | `json` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_interpretation_log_idx_agent` | `agent_id` | no |
| `lupo_interpretation_log_idx_confidence` | `confidence_score` | no |
| `lupo_interpretation_log_idx_created` | `created_ymdhis` | no |
| `lupo_interpretation_log_idx_deleted` | `is_deleted` | no |
| `lupo_interpretation_log_idx_entity` | `entity_type`, `entity_id` | no |
| `lupo_interpretation_log_idx_updated` | `updated_ymdhis` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
