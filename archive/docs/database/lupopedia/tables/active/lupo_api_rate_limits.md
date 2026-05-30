---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "docs/database/lupopedia/tables/active/lupo_api_rate_limits.md"
  web_path: null
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: documentation
  artifact_kind: table
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
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
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
