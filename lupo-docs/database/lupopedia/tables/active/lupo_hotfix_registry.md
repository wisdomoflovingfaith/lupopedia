---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_hotfix_registry.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_hotfix_registry from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_hotfix_registry.json"
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
# file: lupo_hotfix_registry.md

# lupo_hotfix_registry

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_hotfix_registry`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `hotfix_id` | `bigint NOT NULL` |
| `hotfix_version` | `varchar(20) NOT NULL` |
| `applied_ymdhis` | `bigint NOT NULL` |
| `applied_by_actor_id` | `bigint` |
| `description` | `text` |
| `metadata_json` | `json` |

### Indexes

| Index | Columns | Unique |
|---|---|---|

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
