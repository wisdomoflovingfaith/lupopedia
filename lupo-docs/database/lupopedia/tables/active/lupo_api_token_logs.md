---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_api_token_logs.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "integration"
  purpose: "Normalized table documentation for lupo_api_token_logs from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_api_token_logs.json"
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
# file: lupo_api_token_logs.md

# lupo_api_token_logs

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_api_token_logs`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `api_token_log_id` | `bigint NOT NULL` |
| `domain_id` | `bigint NOT NULL DEFAULT 1` |
| `api_token_id` | `bigint NOT NULL` |
| `actor_id` | `bigint NOT NULL DEFAULT 0` |
| `endpoint` | `varchar(255) NOT NULL` |
| `http_method` | `varchar(10) NOT NULL` |
| `ip_address` | `varchar(45)` |
| `user_agent` | `varchar(255)` |
| `status_code` | `int NOT NULL` |
| `request_ymdhis` | `bigint NOT NULL` |
| `duration_ms` | `int` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_api_token_logs_idx_actor` | `actor_id` | no |
| `lupo_api_token_logs_idx_domain_time` | `domain_id`, `request_ymdhis` | no |
| `lupo_api_token_logs_idx_endpoint` | `endpoint` | no |
| `lupo_api_token_logs_idx_status` | `status_code` | no |
| `lupo_api_token_logs_idx_token` | `api_token_id` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
