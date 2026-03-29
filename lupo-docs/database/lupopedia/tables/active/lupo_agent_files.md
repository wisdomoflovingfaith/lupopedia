---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_agent_files.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_agent_files from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_agent_files.json"
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
# file: lupo_agent_files.md

# lupo_agent_files

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_agent_files`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `file_id` | `bigint NOT NULL` |
| `agent_id` | `bigint NOT NULL` |
| `file_type` | `varchar(50) NOT NULL` |
| `file_name` | `varchar(255) NOT NULL` |
| `file_path` | `varchar(500) NOT NULL` |
| `file_hash` | `varchar(64) NOT NULL` |
| `file_size` | `bigint NOT NULL` |
| `mime_type` | `varchar(100)` |
| `upload_ymdhis` | `bigint NOT NULL` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |
| `migrated_from_directory` | `varchar(255)` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_agent_files_idx_agent_id` | `agent_id` | no |
| `lupo_agent_files_idx_file_hash` | `file_hash` | no |
| `lupo_agent_files_idx_file_type` | `file_type` | no |
| `lupo_agent_files_idx_is_deleted` | `is_deleted` | no |
| `lupo_agent_files_idx_upload_ymdhis` | `upload_ymdhis` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
