---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_api_webhooks.md"
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
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
