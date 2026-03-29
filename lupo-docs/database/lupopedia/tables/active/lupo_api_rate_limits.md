---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_api_rate_limits.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "integration"
  purpose: "Normalized table documentation for lupo_api_rate_limits from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_api_rate_limits.json"
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
# file: lupo_api_rate_limits.md

# lupo_api_rate_limits

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_api_rate_limits`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `api_rate_limit_id` | `bigint NOT NULL` |
| `domain_id` | `bigint NOT NULL DEFAULT 1` |
| `api_token_id` | `bigint NOT NULL DEFAULT 0` |
| `actor_id` | `bigint NOT NULL DEFAULT 0` |
| `ip_address` | `varchar(45)` |
| `endpoint` | `varchar(255)` |
| `window_ymdhis` | `bigint NOT NULL` |
| `request_count` | `int NOT NULL DEFAULT 0` |
| `limit_value` | `int NOT NULL DEFAULT 0` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_api_rate_limits_idx_actor_window` | `actor_id`, `window_ymdhis` | no |
| `lupo_api_rate_limits_idx_domain_window` | `domain_id`, `window_ymdhis` | no |
| `lupo_api_rate_limits_idx_endpoint` | `endpoint` | no |
| `lupo_api_rate_limits_idx_ip_window` | `ip_address`, `window_ymdhis` | no |
| `lupo_api_rate_limits_idx_token_window` | `api_token_id`, `window_ymdhis` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
