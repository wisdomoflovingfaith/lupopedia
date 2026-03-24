---
lupopedia.headers:
  lupopedia.schema: database_table
  file_path_from_root: lupo-docs/database/lupopedia/tables/active/lupo_auth_providers.md
  web_path: '[lupo_auth_providers](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_auth_providers)'
  last_modified_utc: '20260317'
  channel_id: 42
  actor_id: 102
  actor_name: cursor
  delegation_chain: cursor:root
  artifact_type: table_documentation
  artifact_kind: table
  namespace: auth
  purpose: OAuth/SSO provider configuration for authentication
  tags:
  - database
  - table
  - auth
  when_updated: '20260324174654'
lupopedia.edges:
  comment: Snapshot of edges for lupo_auth_providers table doc at 4.0.79 (grounded
    by repo search; non-exhaustive).
  meta: php_hits=0 python_hits=1
  outbound_edges:
  - to: database.table.lupo_auth_providers
    type: DEFINES_SCHEMA_FOR
    weight: 1.0
  - to: lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql
    type: schema_reference
    weight: 1.0
  - to: lupo-scripts/wolfie_orms.py
    type: USED_IN_PYTHON
    weight: 0.5
  - to: (no_php_refs_found)
    type: USED_IN_PHP
    weight: 0.0
lupopedia.footer:
  last_verified: '20260317000000'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
---
# file: lupo_auth_providers ? web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_auth_providers
# Table: lupo_auth_providers

## Table Overview

- **Purpose:** Stores OAuth/SSO and external auth provider configuration (endpoints, client credentials, scopes). Used by authentication layer to resolve providers referenced in `lupo_auth_users.auth_provider`.
- **Category:** Authentication / Credentials
- **Status:** Active
- **Version introduced:** 4.0.0

## Column Documentation

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| auth_provider_id | bigint | No | — | Primary key. |
| provider_name | varchar(50) | No | — | Unique provider identifier (e.g. google, github). |
| client_id | varchar(255) | No | — | OAuth client id. |
| client_secret | text | No | — | OAuth client secret. |
| scopes | text | Yes | — | Requested scopes. |
| authorization_endpoint | varchar(2000) | No | — | OAuth authorization URL. |
| token_endpoint | varchar(2000) | No | — | OAuth token URL. |
| userinfo_endpoint | varchar(2000) | Yes | — | UserInfo URL. |
| jwks_uri | varchar(2000) | Yes | — | JWKS URI for token verification. |
| created_ymdhis | bigint | No | 0 | Creation timestamp. |
| updated_ymdhis | bigint | No | — | Last update timestamp. |
| is_active | tinyint | No | 1 | Whether provider is enabled. |

## Relationships

- **Logical references:** `lupo_auth_users.auth_provider` matches `provider_name` (application-level).
- **Inbound:** Auth service reads this table when performing OAuth/SSO flows.

## Usage Notes

- **Index:** Unique on `provider_name`. No foreign keys (doctrine: no DB-side FKs).
- **Timestamps:** BIGINT YYYYMMDDHHIISS UTC; set in application.
