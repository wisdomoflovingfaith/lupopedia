---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_api_webhooks.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_api_webhooks.md
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
# file: lupo_api_webhooks.md

# lupo_api_webhooks

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_api_webhooks`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `api_webhook_id` | `bigint NOT NULL` |
| `domain_id` | `bigint NOT NULL DEFAULT 1` |
| `actor_id` | `bigint NOT NULL DEFAULT 0` |
| `module_id` | `bigint NOT NULL DEFAULT 0` |
| `endpoint_url` | `varchar(500) NOT NULL` |
| `secret_key` | `varchar(255) NOT NULL` |
| `event_types` | `text NOT NULL` |
| `is_active` | `tinyint NOT NULL DEFAULT 1` |
| `max_retries` | `int NOT NULL DEFAULT 5` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `expires_ymdhis` | `bigint` |
| `notes` | `text` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_api_webhooks_idx_active` | `is_active` | no |
| `lupo_api_webhooks_idx_actor` | `actor_id` | no |
| `lupo_api_webhooks_idx_domain` | `domain_id` | no |
| `lupo_api_webhooks_idx_expires` | `expires_ymdhis` | no |
| `lupo_api_webhooks_idx_module` | `module_id` | no |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
