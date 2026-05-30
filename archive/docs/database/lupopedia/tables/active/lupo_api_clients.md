---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "docs/database/lupopedia/tables/active/lupo_api_clients.md"
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
# file: lupo_api_clients.md

# lupo_api_clients

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_api_clients`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `api_client_id` | `bigint NOT NULL` |
| `actor_id` | `bigint NOT NULL DEFAULT 0` |
| `client_key` | `varchar(255) NOT NULL` |
| `client_secret` | `varchar(255) NOT NULL` |
| `client_name` | `varchar(150) NOT NULL` |
| `client_description` | `text` |
| `scopes` | `text` |
| `is_active` | `tinyint NOT NULL DEFAULT 1` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `expires_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_api_clients_idx_active` | `is_active` | no |
| `lupo_api_clients_idx_actor` | `actor_id` | no |
| `lupo_api_clients_idx_expires` | `expires_ymdhis` | no |
| `lupo_api_clients_uq_client_key` | `client_key` | yes |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
