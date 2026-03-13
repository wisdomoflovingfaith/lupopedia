---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "database_table"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_auth_providers.md"
  system_version: "4.0.73"
  namespace: "auth"
  channel_id: 1
  actor_id: 1003
  last_modified_utc: "20260312"
  artifact_type: "table_documentation"
  purpose: "OAuth/SSO provider configuration for authentication"
  mood_rgb: "4169E1"
  traits: ["canonical", "auth", "antigravity_rotation", "v4.0.73"]
  tags: ["database", "auth", "oauth", "providers"]
  lupo_agent: "antigravity"

lupopedia.edges:
  comment: "Snapshot of files edited during 4.0.73 finalization and initialization thread by ANTIGRAVITY IDE Agent. Edges reflect discovered relationships between database tables and PHP/Python codebase entities. Values should be verified against live database schemas/queries for the most current semantic graph state."
  meta: "Thread: Finalize 4.0.72 → Push to GitHub → Initialize 4.0.73 → Migrate Tasks → Validate Upgrade Path"
  outbound_edges:
    - { to: "lupo-docs/toons/lupo_auth_providers.toon.json", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_auth_users.md", type: "references", weight: 0.9 }
    - { to: "lupo-includes/functions/auth-helpers.php", type: "referenced_by", weight: 0.8 }

lupopedia.engagement:
  comment: "Snapshot of files edited during 4.0.73 finalization and initialization thread by ANTIGRAVITY IDE Agent. Engagement metrics track edit frequency and importance of each file in the version transition process."
  meta: "Thread: Finalize 4.0.72 → Push to GitHub → Initialize 4.0.73 → Migrate Tasks → Validate Upgrade Path"
  views: 0

lupopedia.footer:
  version: "4.0.73"
  last_verified: "20260312"
  last_verified_by: "antigravity"
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
