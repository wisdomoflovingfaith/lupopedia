---
lupopedia.headers:
  lupopedia.schema: database_table
  file_path_from_root: "docs/database/lupopedia/tables/active/lupo_auth_users.md"
  web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_auth_users
  last_modified_utc: "20260327234500"
  channel_id: 42
  actor_id: 108
  actor_name: junie
  faucet_name: jetbrains
  delegation_chain: junie:root
  artifact_type: table_documentation
  artifact_kind: table
  namespace: "auth"
  purpose: Authentication accounts for physical human users; paired with Actors for
    orchestration (v4.0.86)
  tags:
  - database
  - table
  - auth
  - identity
  - v4.0.86
  when_updated: "20260327234500"
lupopedia.edges:
  comment: "Snapshot stage1 confidence-scored edges (git=1.0, code-scan=0.7, db=0.5)."
    repo search; non-exhaustive).
  meta: php_hits=16 python_hits=3
  outbound_edges:
  - to: database.table.lupo_auth_users
    type: DEFINES_SCHEMA_FOR
    weight: 1.0
    confidence: 1.0
    source: "git-restored"
  - to: database/lupopedia/mysql/install/install_new_lupopedia.sql
    type: schema_reference
    weight: 1.0
    confidence: 1.0
    source: "git-restored"
  - to: debug_captain.php
    type: USED_IN_PHP
    weight: 0.6
    confidence: 0.7
    source: "code-scan"
  - to: install_wizard_classes.php
    type: USED_IN_PHP
    weight: 0.6
    confidence: 0.7
    source: "code-scan"
  - to: database/lupopedia/content/app/Http/Controllers/Admin/AuthenticationController.php
    type: USED_IN_PHP
    weight: 0.6
    confidence: 0.7
    source: "code-scan"
  - to: database/lupopedia/content/app/Http/Controllers/AuthController.php
    type: USED_IN_PHP
    weight: 0.6
    confidence: 0.7
    source: "code-scan"
  - to: database/lupopedia/content/app/Services/ActorService.php
    type: USED_IN_PHP
    weight: 0.6
    confidence: 0.7
    source: "code-scan"
  - to: database/lupopedia/content/app/Services/CraftySyntax/LegacyAdminOptions.php
    type: USED_IN_PHP
    weight: 0.6
    confidence: 0.7
    source: "code-scan"
  - to: database/lupopedia/content/app/Services/CraftySyntax/LegacyChannels.php
    type: USED_IN_PHP
    weight: 0.6
    confidence: 0.7
    source: "code-scan"
  - to: database/lupopedia/content/app/Services/CraftySyntax/LegacyFunctions.php
    type: USED_IN_PHP
    weight: 0.6
    confidence: 0.7
    source: "code-scan"
  - to: database/lupopedia/content/app/auth/AuthManager.php
    type: USED_IN_PHP
    weight: 0.6
    confidence: 0.7
    source: "code-scan"
  - to: database/lupopedia/content/app/auth/AuthRoleResolver.php
    type: USED_IN_PHP
    weight: 0.6
    confidence: 0.7
    source: "code-scan"
  - to: database/lupopedia/content/app/auth/AuthService.php
    type: USED_IN_PHP
    weight: 0.6
    confidence: 0.7
    source: "code-scan"
  - to: includes/functions/reserved-id-helpers.php
    type: USED_IN_PHP
    weight: 0.9
    confidence: 0.7
    source: "code-scan"
  - to: includes/modules/actors/actors-controller.php
    type: USED_IN_PHP
    weight: 0.9
    confidence: 0.7
    source: "code-scan"
  - to: scripts/audit_schema_doctrine.php
    type: USED_IN_PHP
    weight: 0.7
    confidence: 0.7
    source: "code-scan"
  - to: scripts/migrate_user_mappings.php
    type: USED_IN_PHP
    weight: 0.7
    confidence: 0.7
    source: "code-scan"
  - to: analyze_unused_tables.py
    type: USED_IN_PYTHON
    weight: 0.5
    confidence: 0.7
    source: "code-scan"
  - to: scripts/audit_schema_doctrine.py
    type: USED_IN_PYTHON
    weight: 0.5
    confidence: 0.7
    source: "code-scan"
  - to: scripts/wolfie_orms.py
    type: USED_IN_PYTHON
    weight: 0.5
    confidence: 0.7
    source: "code-scan"
lupopedia.footer:
  provenance: "phase2_git_header_recovered_body_regenerated"
  generated: true
  last_verified: "20260327234500"
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
---
# file: lupo_auth_users.md

# lupo_auth_users

## Purpose
Canonical table documentation regenerated from TOON JSON for `lupo_auth_users`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `auth_user_id` | `bigint NOT NULL` |
| `username` | `varchar(255) NOT NULL` |
| `display_name` | `varchar(42) NOT NULL` |
| `email` | `varchar(100)` |
| `password_hash` | `varchar(255)` |
| `auth_provider` | `varchar(50)` |
| `provider_id` | `varchar(255)` |
| `profile_image_url` | `varchar(2000)` |
| `last_login_ymdhis` | `bigint` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_active` | `tinyint NOT NULL DEFAULT 1` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_auth_users_idx_created_ymdhis` | `created_ymdhis` | no |
| `lupo_auth_users_idx_email` | `email` | no |
| `lupo_auth_users_idx_is_active` | `is_active` | no |
| `lupo_auth_users_idx_is_deleted` | `is_deleted` | no |
| `lupo_auth_users_idx_updated_ymdhis` | `updated_ymdhis` | no |
| `lupo_auth_users_unique_provider_user` | `auth_provider`, `provider_id` | yes |
| `lupo_auth_users_unique_username` | `username` | yes |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Phase 2 deterministic rebuild
- Edge mode: placeholder only
