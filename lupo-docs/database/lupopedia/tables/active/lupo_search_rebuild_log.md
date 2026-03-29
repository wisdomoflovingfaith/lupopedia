---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_search_rebuild_log.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_search_rebuild_log from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_search_rebuild_log.json"
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
# file: lupo_search_rebuild_log.md

# lupo_search_rebuild_log

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_search_rebuild_log`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `search_rebuild_log_id` | `bigint NOT NULL` |
| `entity_type` | `varchar(50) NOT NULL` |
| `entity_id` | `bigint NOT NULL` |
| `action` | `varchar(64) NOT NULL` |
| `status` | `varchar(64) NOT NULL DEFAULT 'pending'` |
| `attempts` | `tinyint NOT NULL DEFAULT 0` |
| `last_error` | `text` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `processed_ymdhis` | `bigint` |
| `next_attempt_ymdhis` | `bigint` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_search_rebuild_log_idx_created` | `created_ymdhis` | no |
| `lupo_search_rebuild_log_idx_entity` | `entity_type`, `entity_id` | no |
| `lupo_search_rebuild_log_idx_status_retry` | `status`, `next_attempt_ymdhis` | no |
| `lupo_search_rebuild_log_unique_entity_operation` | `entity_type`, `entity_id`, `action` | yes |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
