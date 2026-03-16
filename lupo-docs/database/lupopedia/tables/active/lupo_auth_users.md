---
lupopedia.headers:
  lupopedia.version: "4.0.78"
  lupopedia.schema: "database_table"
  system_version: "4.0.78"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_auth_users.md"
  web_path: "[lupo_auth_users](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_auth_users)"
  last_modified_utc: "20260316"
  channel_id: 42
  actor_id: 102
  artifact_type: "table_documentation"
  artifact_kind: "table"
  namespace: "auth"
  purpose: "Authentication and identity accounts for human operators and users; username, credentials, provider linkage, login metadata"
  traits: ["canonical", "auth", "identity", "v4.0.78"]
  tags: ["database", "auth", "users", "credentials", "identity"]
  table_primary_key: "auth_user_id"
  doctrine_note: "No database foreign keys; referential integrity enforced in application code. RESERVED ID DOCTRINE: auth_user_id is NOT AUTO_INCREMENT; application must supply explicit ID. All timestamps BIGINT UTC YYYYMMDDHHIISS."

lupopedia.edges:
  comment: "Snapshot of edges for lupo_auth_users table doc at 4.0.78."
  outbound_edges:
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_actors.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_sessions.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_auth_providers.md", type: "references", weight: 0.8 }
    - { to: "app/Services/AuthService.php", type: "references", weight: 0.8 }

lupopedia.footer:
  version: "4.0.78"
  last_verified: "20260316"
  last_verified_by: "cursor"
---
# file: lupo_auth_users — web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_auth_users

# Table: lupo_auth_users

## Table Overview

- **Purpose:** Stores authentication and identity data for human operators and users. Each row is one auth user: auth_user_id (PK, application-supplied), username (unique), display_name, email, password_hash, auth_provider, provider_id (unique per provider), profile_image_url, last_login_ymdhis, created_ymdhis, updated_ymdhis, is_active, and soft delete. Mapped from legacy livehelp_users on Crafty Syntax upgrade. Linked to lupo_actors for orchestration identity.
- **Category:** Auth / Identity
- **Status:** Active (in install_new_lupopedia.sql)
- **Version introduced:** 4.0.x

## Where This Table Is Used

- **Login and authentication:** Auth guard and login flow resolve users by username or (auth_provider, provider_id); password_hash verified for local auth; AuthService and session logic reference this table.
- **Actor mapping:** Human users map to lupo_actors; auth_user_id or username links session and actor identity for orchestration and permissions.
- **Provider linkage:** auth_provider and provider_id support OAuth/SSO; unique (auth_provider, provider_id) prevents duplicate provider accounts.
- **Profile and display:** display_name, email, profile_image_url used in UI and identity resolution.

## Column Documentation

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| auth_user_id | bigint | No | — | Primary key. **Reserved-ID:** application must supply explicit ID; do not use AUTO_INCREMENT/lastInsertId. |
| username | varchar(255) | No | — | Unique username. |
| display_name | varchar(42) | No | — | Display name. |
| email | varchar(100) | Yes | NULL | Email. |
| password_hash | varchar(255) | Yes | NULL | Password hash (local auth). |
| auth_provider | varchar(50) | Yes | NULL | Auth provider (e.g. google, local). |
| provider_id | varchar(255) | Yes | NULL | Provider-specific user ID. |
| profile_image_url | varchar(2000) | Yes | NULL | Profile image URL. |
| last_login_ymdhis | bigint | Yes | NULL | Last login timestamp (BIGINT UTC). |
| created_ymdhis | bigint | No | 0 | Creation timestamp (BIGINT UTC). |
| updated_ymdhis | bigint | No | — | Last update timestamp (BIGINT UTC). |
| is_active | tinyint | No | 1 | Active flag. |
| is_deleted | tinyint | No | 0 | Soft delete flag. |
| deleted_ymdhis | bigint | Yes | NULL | Soft delete timestamp. |

## Indexes

- **PRIMARY KEY:** auth_user_id
- **UNIQUE:** lupo_auth_users_unique_username (username), lupo_auth_users_unique_provider_user (auth_provider, provider_id)
- **INDEX:** lupo_auth_users_idx_email (email), lupo_auth_users_idx_is_active (is_active), lupo_auth_users_idx_is_deleted (is_deleted), lupo_auth_users_idx_created_ymdhis (created_ymdhis), lupo_auth_users_idx_updated_ymdhis (updated_ymdhis)

## Relationships

- **Logical references (no DB FKs):** Application links auth_user_id to lupo_actors for human orchestrators; lupo_sessions and auth flow resolve identity via this table. lupo_auth_providers documents available providers.

## Doctrine notes

- No database foreign keys; referential integrity enforced in application code.
- **Reserved-ID doctrine:** auth_user_id is NOT AUTO_INCREMENT; application must supply explicit ID (e.g. from registry or import). Do not use lastInsertId() for this table; use SELECT/UPDATE or INSERT with explicit ID.
- All timestamps BIGINT UTC YYYYMMDDHHIISS.
- Soft delete: filter `is_deleted = 0` unless querying deleted rows.
