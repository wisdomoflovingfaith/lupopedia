---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_contexts.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_contexts from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_contexts.json"
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
# file: lupo_contexts.md

# lupo_contexts

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_contexts`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `context_id` | `int NOT NULL` |
| `context_code` | `varchar(16) NOT NULL` |
| `context_name` | `varchar(255) NOT NULL` |
| `context_description` | `text` |
| `parent_context_id` | `int` |
| `is_system` | `tinyint NOT NULL DEFAULT 0` |
| `is_fiction` | `tinyint NOT NULL DEFAULT 0` |
| `is_installation_local` | `tinyint NOT NULL DEFAULT 0` |
| `sort_order` | `int NOT NULL DEFAULT 0` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `weight_score` | `decimal(5,2) NOT NULL DEFAULT 0.00` |
| `is_active` | `tinyint NOT NULL DEFAULT 1` |
| `metadata_json` | `json` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_contexts_idx_parent_context` | `parent_context_id` | no |
| `lupo_contexts_uq_context_code` | `context_code` | yes |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
