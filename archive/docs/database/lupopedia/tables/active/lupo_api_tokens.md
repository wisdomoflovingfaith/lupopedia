---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "docs/database/lupopedia/tables/active/lupo_api_tokens.md"
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
# file: lupo_api_tokens.md

# lupo_api_tokens

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_api_tokens`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `api_token_id` | `bigint NOT NULL` |
| `domain_id` | `bigint NOT NULL DEFAULT 1` |
| `actor_id` | `bigint NOT NULL DEFAULT 0` |
| `token_key` | `varchar(255) NOT NULL` |
| `token_label` | `varchar(150)` |
| `scopes` | `text` |
| `is_active` | `tinyint NOT NULL DEFAULT 1` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `expires_ymdhis` | `bigint` |
| `last_used_ymdhis` | `bigint` |
| `created_ip` | `varchar(45)` |
| `last_used_ip` | `varchar(45)` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |
| `notes` | `text` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_api_tokens_idx_active` | `is_active` | no |
| `lupo_api_tokens_idx_actor` | `actor_id` | no |
| `lupo_api_tokens_idx_actor_active` | `actor_id`, `is_active` | no |
| `lupo_api_tokens_idx_domain` | `domain_id` | no |
| `lupo_api_tokens_idx_expires` | `expires_ymdhis` | no |
| `lupo_api_tokens_idx_last_used` | `last_used_ymdhis` | no |
| `lupo_api_tokens_uq_token_key` | `token_key` | yes |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
