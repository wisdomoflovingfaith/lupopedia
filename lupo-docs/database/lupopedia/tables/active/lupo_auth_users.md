---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "database_table"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_auth_users.md"
  system_version: "4.0.70"
  channel_id: 1
  actor_id: 102
  last_modified_utc: "20260312"
  artifact_type: "table_documentation"
  purpose: "Authentication and identity accounts for human operators and users"
  mood_rgb: "4169E1"
  traits: ["canonical", "auth", "identity", "cursor_domain", "v4.0.70"]
  tags: ["database", "auth", "users", "credentials", "identity"]
  lupo_agent: "cursor"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/toons/lupo_auth_users.toon.json", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_actors.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_sessions.md", type: "references", weight: 0.8 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_auth_providers.md", type: "references", weight: 0.8 }

lupopedia.footer:
  last_verified: "20260312"
  last_verified_by: "cursor"
---

# Table: lupo_auth_users

## Table Overview

- **Purpose:** Stores authentication and identity data for human operators and users. Credentials, provider linkage, and login metadata. Mapped from legacy `livehelp_users` on Crafty Syntax upgrade.
- **Category:** User / Authentication / Identity
- **Status:** Active
- **Version introduced:** 4.0.0
- **Migration references:** See `lupo-docs/database/lupopedia/tables/MIGRATION_MAPPING_REFERENCE.md` (livehelp_users → lupo_auth_users).

## Column Documentation

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| auth_user_id | bigint | No | — | Primary key. Application-assigned; do not use AUTO_INCREMENT per reserved-ID doctrine. |
| username | varchar(255) | No | — | Unique username. |
| display_name | varchar(42) | No | — | Display name. |
| email | varchar(100) | Yes | — | Email address. |
| password_hash | varchar(255) | Yes | — | Hashed password (local auth). |
| auth_provider | varchar(50) | Yes | — | Provider name (e.g. local, oauth). |
| provider_id | varchar(255) | Yes | — | External provider user id. |
| profile_image_url | varchar(2000) | Yes | — | Optional profile image URL. |
| last_login_ymdhis | bigint | Yes | — | Last login timestamp (YYYYMMDDHHIISS UTC). |
| created_ymdhis | bigint | No | 0 | Creation timestamp. |
| updated_ymdhis | bigint | No | — | Last update timestamp. |
| is_active | tinyint | No | 1 | Active flag. |
| is_deleted | tinyint | No | 0 | Soft-delete flag. |
| deleted_ymdhis | bigint | Yes | — | Soft-delete timestamp. |

## Relationships

- **Logical references (no DB FKs):** Identity links to `lupo_actors` via application logic; sessions reference auth via actor. `auth_provider` / `provider_id` align with `lupo_auth_providers.provider_name`.
- **Inbound:** Sessions, actor resolution, and login flows read this table.
- **Join patterns:** Lookup by `username`, or by `(auth_provider, provider_id)` (unique).

## Usage Notes

- **Reserved ID:** Inserts must supply `auth_user_id` explicitly; use registry or allocation pattern. No `lastInsertId()` for this table.
- **Timestamps:** All timestamps BIGINT YYYYMMDDHHIISS UTC; set in application with `gmdate('YmdHis')`.
- **Indexes:** `lupo_auth_users_unique_username`, `lupo_auth_users_unique_provider_user`, plus idx on created_ymdhis, email, is_active, is_deleted, updated_ymdhis.
