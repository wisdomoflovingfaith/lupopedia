---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_capability_usage.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_capability_usage from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_capability_usage.json"
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
# file: lupo_capability_usage.md

# lupo_capability_usage

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_capability_usage`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `usage_id` | `bigint NOT NULL` |
| `actor_id` | `bigint NOT NULL` |
| `capability` | `varchar(100) NOT NULL` |
| `usage_count` | `bigint DEFAULT 0` |
| `success_rate` | `float DEFAULT 1` |
| `avg_response_time_ms` | `int DEFAULT 0` |
| `last_used_ymdhis` | `bigint DEFAULT 0` |
| `performance_metrics` | `json` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_capability_usage_idx_actor_capability` | `actor_id`, `capability` | no |
| `lupo_capability_usage_idx_capability` | `capability` | no |
| `lupo_capability_usage_idx_is_deleted` | `is_deleted` | no |
| `lupo_capability_usage_idx_last_used` | `last_used_ymdhis` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
