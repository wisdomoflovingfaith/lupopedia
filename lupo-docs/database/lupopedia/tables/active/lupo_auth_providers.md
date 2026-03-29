---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_auth_providers.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "auth"
  purpose: "Normalized table documentation for lupo_auth_providers from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_auth_providers.json"
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
