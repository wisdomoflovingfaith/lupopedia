---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_api_clients.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_api_clients.md
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
