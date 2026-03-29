---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_agent_versions.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_agent_versions from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_agent_versions.json"
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
# file: lupo_agent_versions.md

# lupo_agent_versions

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_agent_versions`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `agent_version_id` | `bigint NOT NULL` |
| `agent_id` | `bigint NOT NULL` |
| `version_label` | `varchar(64) NOT NULL` |
| `semver_major` | `int DEFAULT 0` |
| `semver_minor` | `int DEFAULT 0` |
| `semver_patch` | `int DEFAULT 0` |
| `version_notes` | `text` |
| `version_hash` | `varchar(128)` |
| `previous_version_id` | `bigint` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_deleted` | `smallint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_agent_versions_agent_id` | `agent_id` | no |
| `lupo_agent_versions_semver_major` | `semver_major`, `semver_minor`, `semver_patch` | no |
| `lupo_agent_versions_version_label` | `version_label` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
