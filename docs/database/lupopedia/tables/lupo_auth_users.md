# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) see http://www.lupopedia.com/lupopedia/content/FLARE and see http://www.lupopedia.com/lupopedia/qa/FLARE
---
flare.headers:
  file_path_from_root: "docs/database/lupopedia/tables/lupo_auth_users.md"
  system_version: "4.0.49"
  channel_id: 1
  actor_id: 1007
  last_modified_utc: "20260227"
  delegation_chain: "1007:10000"
  artifact_type: "table_documentation"
  purpose: "Authentication accounts for human operators"
  dialog_message: "DBDOC batch 2: enriched documentation and optimization notes."
  mood_rgb: "4B0082"
  artifact_kind: "table"
  traits: ["canonical", "database", "curated"]
  tags: ["database", "table", "lupo_auth_users"]
  lupo_agent: "codex-ide"
  lupo_auth_users.auth_user_id: "bigint NOT NULL"
  lupo_auth_users.username: "varchar(255) NOT NULL"
  lupo_auth_users.display_name: "varchar(42) NOT NULL"
  lupo_auth_users.email: "varchar(100)"
  lupo_auth_users.password_hash: "varchar(255)"
  lupo_auth_users.auth_provider: "varchar(50)"
  lupo_auth_users.provider_id: "varchar(255)"
  lupo_auth_users.profile_image_url: "varchar(2000)"
  lupo_auth_users.last_login_ymdhis: "bigint"
  lupo_auth_users.created_ymdhis: "bigint NOT NULL DEFAULT 0"
  lupo_auth_users.updated_ymdhis: "bigint NOT NULL"
  lupo_auth_users.is_active: "tinyint NOT NULL DEFAULT 1"
  lupo_auth_users.is_deleted: "tinyint NOT NULL DEFAULT 0"
  lupo_auth_users.deleted_ymdhis: "bigint DEFAULT 0"
  table_primary_key: "auth_user_id"
  table_engine: "unknown"
  table_charset: "unknown"
  table_collation: "unknown"
  table_indexes: ["lupo_auth_users_idx_created_ymdhis", "lupo_auth_users_idx_email", "lupo_auth_users_idx_is_active", "lupo_auth_users_idx_is_deleted", "lupo_auth_users_idx_updated_ymdhis", "lupo_auth_users_unique_provider_user", "lupo_auth_users_unique_username"]
  table_foreign_keys: []

# FLARE Edge Automation Tip:
# Use the FLARE Edge Suggester Tool to automatically discover and suggest edges:
# python scripts/flare_edge_suggester.py --file <path> --include-db --format yaml

flare.footer:
  outbound_edges:
    - { to: "docs/toons/lupo_auth_users.toon.json", type: "schema_reference", weight: 1.0, reason: "TOON schema definition", db_source: "lupo_auth_users" }
    - { to: "docs/database/lupopedia/tables/lupo_actors.md", type: "references", weight: 0.7, reason: "actor linkage" }
    - { to: "docs/database/lupopedia/tables/lupo_sessions.md", type: "references", weight: 0.7, reason: "sessions" }
  inbound_edges: []
  semantic_tags: ["database", "table", "auth"]
  version: "4.0.49"
  last_verified: "20260227"
  last_verified_by: "codex-ide"
---

# Table: lupo_auth_users

Purpose: Stores human authentication accounts for operators and admins.
Type: database_table
Status: production_ready
Volume: low

## 1. Overview
- Key responsibilities: account identity, provider metadata, login timestamps.
- System role: authentication backing store for sessions.
- Importance: core security boundary for admin access.

## 2. Schema Reference
Primary Key: auth_user_id
Field Categories: identity, provider, security, lifecycle.

### All Fields
| Column | Type | Notes |
|---|---|---|
| auth_user_id | bigint NOT NULL | Primary key. |
| username | varchar(255) NOT NULL | Username. |
| display_name | varchar(42) NOT NULL | Display name. |
| email | varchar(100) | Email address. |
| password_hash | varchar(255) | Password hash. |
| auth_provider | varchar(50) | Provider name. |
| provider_id | varchar(255) | Provider user id. |
| profile_image_url | varchar(2000) | Profile image. |
| last_login_ymdhis | bigint | Last login timestamp. |
| created_ymdhis | bigint NOT NULL DEFAULT 0 | Created timestamp. |
| updated_ymdhis | bigint NOT NULL | Updated timestamp. |
| is_active | tinyint NOT NULL DEFAULT 1 | Active flag. |
| is_deleted | tinyint NOT NULL DEFAULT 0 | Soft delete flag. |
| deleted_ymdhis | bigint DEFAULT 0 | Soft delete timestamp. |

## 3. Relationships and Dependencies
- Primary relationships: actor mapping and sessions.
- Referencing tables: lupo_sessions, admin tooling.
- Integration points: login, password reset, OAuth.

## 4. Indexes and Performance
Primary Indexes:
- auth_user_id
Performance Indexes:
- lupo_auth_users_unique_username
- lupo_auth_users_idx_email
- lupo_auth_users_unique_provider_user
Index Strategy: optimize lookup by username/email/provider.

## 5. Usage Patterns
Common Queries:
```sql
SELECT * FROM lupo_auth_users WHERE username = :username AND is_deleted = 0 LIMIT 1;
SELECT * FROM lupo_auth_users WHERE email = :email AND is_deleted = 0 LIMIT 1;
UPDATE lupo_auth_users SET last_login_ymdhis = :ts WHERE auth_user_id = :id;
```
Best Practices: always filter by is_deleted and is_active.
Anti-Patterns: storing plaintext passwords or email in logs.

## 6. Performance Considerations
- High-volume operations: low.
- Optimization tips: add composite index on (email, is_active) for auth lookup filtering.
- Scaling considerations: cache auth_user_id per session.

## 7. Data Integrity
- Constraints: username required and unique.
- Validation rules: enforce provider_id uniqueness per provider.
- Soft delete: keep audit trail of deleted_ymdhis.

## 8. Common Issues and Solutions
- Login failures: verify is_active and is_deleted flags.
- Duplicate identities: rely on unique indexes.
- Provider drift: update auth_provider mapping carefully.

## 9. Future Enhancements
- Add password_updated_ymdhis for rotation policies.
- Add locked_until_ymdhis for lockout workflows.
