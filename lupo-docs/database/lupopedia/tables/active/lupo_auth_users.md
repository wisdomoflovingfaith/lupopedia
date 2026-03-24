---
lupopedia.headers:
  lupopedia.schema: database_table
  file_path_from_root: lupo-docs/database/lupopedia/tables/active/lupo_auth_users.md
  web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_auth_users
  last_modified_utc: '20260324'
  channel_id: 42
  actor_id: 108
  actor_name: junie
  faucet_name: jetbrains
  delegation_chain: junie:root
  artifact_type: table_documentation
  artifact_kind: table
  namespace: auth
  purpose: Authentication accounts for physical human users; paired with Actors for
    orchestration (v4.0.86)
  tags:
  - database
  - table
  - auth
  - identity
  - v4.0.86
  when_updated: '20260324174654'
lupopedia.edges:
  comment: Snapshot of edges for lupo_auth_users table doc at 4.0.79 (grounded by
    repo search; non-exhaustive).
  meta: php_hits=16 python_hits=3
  outbound_edges:
  - to: database.table.lupo_auth_users
    type: DEFINES_SCHEMA_FOR
    weight: 1.0
  - to: lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql
    type: schema_reference
    weight: 1.0
  - to: debug_captain.php
    type: USED_IN_PHP
    weight: 0.6
  - to: install_wizard_classes.php
    type: USED_IN_PHP
    weight: 0.6
  - to: lupo-database/lupopedia/content/lupo-app/Http/Controllers/Admin/AuthenticationController.php
    type: USED_IN_PHP
    weight: 0.6
  - to: lupo-database/lupopedia/content/lupo-app/Http/Controllers/AuthController.php
    type: USED_IN_PHP
    weight: 0.6
  - to: lupo-database/lupopedia/content/lupo-app/Services/ActorService.php
    type: USED_IN_PHP
    weight: 0.6
  - to: lupo-database/lupopedia/content/lupo-app/Services/CraftySyntax/LegacyAdminOptions.php
    type: USED_IN_PHP
    weight: 0.6
  - to: lupo-database/lupopedia/content/lupo-app/Services/CraftySyntax/LegacyChannels.php
    type: USED_IN_PHP
    weight: 0.6
  - to: lupo-database/lupopedia/content/lupo-app/Services/CraftySyntax/LegacyFunctions.php
    type: USED_IN_PHP
    weight: 0.6
  - to: lupo-database/lupopedia/content/lupo-app/auth/AuthManager.php
    type: USED_IN_PHP
    weight: 0.6
  - to: lupo-database/lupopedia/content/lupo-app/auth/AuthRoleResolver.php
    type: USED_IN_PHP
    weight: 0.6
  - to: lupo-database/lupopedia/content/lupo-app/auth/AuthService.php
    type: USED_IN_PHP
    weight: 0.6
  - to: lupo-includes/functions/reserved-id-helpers.php
    type: USED_IN_PHP
    weight: 0.9
  - to: lupo-includes/modules/actors/actors-controller.php
    type: USED_IN_PHP
    weight: 0.9
  - to: lupo-scripts/audit_schema_doctrine.php
    type: USED_IN_PHP
    weight: 0.7
  - to: lupo-scripts/migrate_user_mappings.php
    type: USED_IN_PHP
    weight: 0.7
  - to: analyze_unused_tables.py
    type: USED_IN_PYTHON
    weight: 0.5
  - to: lupo-scripts/audit_schema_doctrine.py
    type: USED_IN_PYTHON
    weight: 0.5
  - to: lupo-scripts/wolfie_orms.py
    type: USED_IN_PYTHON
    weight: 0.5
lupopedia.footer:
  last_verified: '20260324000000'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
---
# file: lupo_auth_users — delegation: junie:root — web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_auth_users
# Table: lupo_auth_users

## Table Overview

- **Purpose:** Stores authentication credentials for human users. In the **Unified Identity Model (v4.0.86)**, an `auth_user` represents the *physical* human, while a paired `actor` represents their *operational* identity. The link is managed via `lupo_actor_auth_users` or `lupo_actors.auth_user_id`.
- **Category:** Auth / Identity
- **Status:** Active
- **Version introduced:** 4.0.0

## Where This Table Is Used

- **Login and authentication:** Auth guard and login flow resolve users by username or (auth_provider, provider_id); password_hash verified for local auth; AuthService and session logic reference this table.
- **Actor mapping:** Human users map to lupo_actors; auth_user_id or username links session and actor identity for orchestration and permissions.
- **Provider linkage:** auth_provider and provider_id support OAuth/SSO; unique (auth_provider, provider_id) prevents duplicate provider accounts.
- **Profile and display:** display_name, email, profile_image_url used in UI and identity resolution.

## Column Documentation

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| auth_user_id | bigint | No | — | Primary key. **Reserved-ID:** application must supply explicit ID; do not use AUTO_INCREMENT/lastInsertId. **Root User ID is 0.** |
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
