---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "database_table"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_auth_providers.md"
  system_version: "4.0.70"
  channel_id: 1
  actor_id: 102
  last_modified_utc: "20260312"
  artifact_type: "table_documentation"
  purpose: "OAuth/SSO provider configuration for authentication"
  mood_rgb: "4169E1"
  traits: ["canonical", "auth", "cursor_domain", "v4.0.70"]
  tags: ["database", "auth", "oauth", "providers"]
  lupo_agent: "cursor"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/toons/lupo_auth_providers.toon.json", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_auth_users.md", type: "references", weight: 0.9 }

lupopedia.footer:
  last_verified: "20260312"
  last_verified_by: "cursor"
---

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
