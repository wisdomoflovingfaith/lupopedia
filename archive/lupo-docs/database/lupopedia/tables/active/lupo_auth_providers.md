---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_auth_providers.md"
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
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
