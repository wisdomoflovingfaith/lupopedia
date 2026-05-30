---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_api_token_logs.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_api_token_logs.md
  status: ''
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: documentation
  artifact_kind: table
  channel_key: null
  federation_node_id: null
  thread_key: ''
  lupopedia.schema: null
  prd_cluster: null
  title: ''
  summary: ''
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
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
