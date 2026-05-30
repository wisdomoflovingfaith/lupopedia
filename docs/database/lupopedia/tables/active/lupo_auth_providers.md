---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_auth_providers.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_auth_providers.md
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
# file: lupo_auth_providers.md

# lupo_auth_providers

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_auth_providers`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `auth_provider_id` | `bigint NOT NULL` |
| `provider_name` | `varchar(50) NOT NULL` |
| `client_id` | `varchar(255) NOT NULL` |
| `client_secret` | `text NOT NULL` |
| `scopes` | `text` |
| `authorization_endpoint` | `varchar(2000) NOT NULL` |
| `token_endpoint` | `varchar(2000) NOT NULL` |
| `userinfo_endpoint` | `varchar(2000)` |
| `jwks_uri` | `varchar(2000)` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_active` | `tinyint NOT NULL DEFAULT 1` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_auth_providers_unique_provider_name` | `provider_name` | yes |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
