---
lupopedia.headers:
  lupopedia.version: "4.0.80"
  lupopedia.schema: "documentation"
  system_version: "4.0.80"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/auth/lupo_auth_providers.md"
  web_path: "http://www.lupopedia.com/lupo-docs/database/lupopedia/tables/active/auth/lupo_auth_providers"
  last_modified_utc: "20260317"
  channel_id: 42
  actor_id: 102
  actor_name: "hermes"
  faucet_name: "cascade"
  delegation_chain: "hermes:wolfie"
  artifact_type: "documentation"
  artifact_kind: "table_documentation"
  purpose: "Documentation for lupo_auth_providers table - authentication provider management"
  tags: ["table_documentation", "auth", "4.0.80", "top_50"]
---

# lupo_auth_providers.md

## Table Overview

The `lupo_auth_providers` table manages authentication providers in the Lupopedia system. It stores configuration and metadata for external authentication services (OAuth, LDAP, SAML, etc.) that can be integrated with the platform for user authentication.

**Namespace**: `auth`  
**Table Type**: Configuration / Authentication  
**Criticality**: HIGH - Core authentication system

## Where This Table Is Used

- **Authentication System**: Primary lookup for available auth providers
- **User Login Flows**: Determines which authentication methods are available
- **Provider Integration**: Manages OAuth, LDAP, SAML, and custom auth providers
- **Admin Configuration**: Auth provider setup and management interfaces
- **Session Management**: Links authentication methods to user sessions

## Columns

| Column | Type | Description | Notes |
|--------|------|-------------|--------|
| `auth_provider_id` | bigint NOT NULL | **PRIMARY KEY** - Unique provider identifier | Application-assigned ID |
| `provider_name` | varchar(50) NOT NULL | Provider name | Human-readable name |
| `client_id` | varchar(255) NOT NULL | OAuth client ID | For OAuth providers |
| `client_secret` | text NOT NULL | OAuth client secret | Encrypted storage |
| `scopes` | text | OAuth scopes | Optional permission scopes |
| `authorization_endpoint` | varchar(2000) NOT NULL | Authorization URL | OAuth authorization endpoint |
| `token_endpoint` | varchar(2000) NOT NULL | Token URL | OAuth token endpoint |
| `userinfo_endpoint` | varchar(2000) | User info URL | OAuth user info endpoint |
| `jwks_uri` | varchar(2000) | JWKS URI | JSON Web Key Set endpoint |
| `created_ymdhis` | bigint NOT NULL DEFAULT 0 | Creation timestamp | YYYYMMDDHHIISS format |
| `updated_ymdhis` | bigint NOT NULL | Last update timestamp | YYYYMMDDHHIISS format |
| `is_active` | tinyint NOT NULL DEFAULT 1 | Provider status | 1 = active, 0 = inactive |

## Indexes

### Primary Index
- `PRIMARY KEY (auth_provider_id)` - Unique provider identifier

### Unique Index
- `lupo_auth_providers_unique_provider_name (provider_name)` - Unique provider name

## Key Relationships

### Related Tables
- **lupo_auth_audit_log** - Authentication event logging
- **User Authentication** - Provider usage in user login flows

## Usage Patterns

### OAuth2 Provider Configuration
The table stores OAuth2 provider configuration including:
- Client credentials (client_id, client_secret)
- Endpoint URLs (authorization, token, user info)
- Optional JWKS URI for token validation
- Permission scopes for access control

### Provider Management
- `is_active` flag enables/disables providers
- `provider_name` must be unique
- Timestamps track provider configuration changes

## Notes

This table is designed for OAuth2/OIDC providers primarily. It stores the essential configuration needed for OAuth flows including client credentials and endpoint URLs.

For other authentication methods (LDAP, SAML), additional configuration may be stored in separate tables or handled by custom authentication modules.
