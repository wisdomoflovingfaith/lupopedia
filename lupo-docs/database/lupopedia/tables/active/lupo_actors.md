---
lupopedia.headers:
  lupopedia.schema: database_table
  file_path_from_root: lupo-docs/database/lupopedia/tables/active/lupo_actors.md
  web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_actors
  last_modified_utc: '20260324174926'
  channel_id: 42
  actor_id: 108
  actor_name: junie
  faucet_name: jetbrains
  delegation_chain: junie:root
  artifact_type: table_documentation
  artifact_kind: table
  namespace: core
  purpose: Documentation for lupo_actors table - unified actor identity and management
    (v4.0.86)
  tags:
  - database
  - table
  - core
  - identity
  - v4.0.86
  when_updated: '20260324174926'
lupopedia.edges:
  comment: Snapshot of edges for lupo_actors table doc at 4.0.79 (grounded by repo
    search; non-exhaustive).
  meta: php_hits=33 python_hits=12
  outbound_edges:
  - to: database.table.lupo_actors
    type: DEFINES_SCHEMA_FOR
    weight: 1.0
  - to: lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql
    type: schema_reference
    weight: 1.0
  - to: debug_captain.php
    type: USED_IN_PHP
    weight: 0.6
  - to: init_actor_dirs.php
    type: USED_IN_PHP
    weight: 0.6
  - to: install.php
    type: USED_IN_PHP
    weight: 0.6
  - to: install_wizard_classes.php
    type: USED_IN_PHP
    weight: 0.6
  - to: lupo-bin/lupo.php
    type: USED_IN_PHP
    weight: 0.6
  - to: lupo-database/lupopedia/content/lupo-app/Http/Controllers/AuthController.php
    type: USED_IN_PHP
    weight: 0.6
  - to: lupo-database/lupopedia/content/lupo-app/Services/ActorService.php
    type: USED_IN_PHP
    weight: 0.6
  - to: lupo-database/lupopedia/content/lupo-app/Services/AnubisUnknownRecipientService.php
    type: USED_IN_PHP
    weight: 0.6
  - to: lupo-database/lupopedia/content/lupo-app/Services/CraftySyntax/LegacyFunctions.php
    type: USED_IN_PHP
    weight: 0.6
  - to: lupo-database/lupopedia/content/lupo-app/Services/HybridActorSecurityService.php
    type: USED_IN_PHP
    weight: 0.6
  - to: lupo-database/lupopedia/content/lupo-app/Services/SavedCollectionsService.php
    type: USED_IN_PHP
    weight: 0.6
  - to: lupo-database/lupopedia/content/lupo-app/auth/AuthService.php
    type: USED_IN_PHP
    weight: 0.6
  - to: lupo-includes/DialogChannelMigration/MessageBuilder.php
    type: USED_IN_PHP
    weight: 0.9
  - to: lupo-includes/class-iris.php
    type: USED_IN_PHP
    weight: 0.9
  - to: lupo-includes/classes/AdminRegistryHandler.php
    type: USED_IN_PHP
    weight: 0.9
  - to: analyze_unused_tables.py
    type: USED_IN_PYTHON
    weight: 0.5
  - to: lupo-scripts/actor_agent_doctrine.py
    type: USED_IN_PYTHON
    weight: 0.5
  - to: lupo-scripts/audit_and_move_dev_tables.py
    type: USED_IN_PYTHON
    weight: 0.5
  - to: lupo-scripts/audit_schema_doctrine.py
    type: USED_IN_PYTHON
    weight: 0.5
  - to: lupo-scripts/check_doc_schema_consistency.py
    type: USED_IN_PYTHON
    weight: 0.5
  - to: lupo-scripts/flare_edge_suggester.py
    type: USED_IN_PYTHON
    weight: 0.5
  - to: lupo-scripts/generate_seed_from_toons.py
    type: USED_IN_PYTHON
    weight: 0.5
  - to: lupo-scripts/init_all_actors.py
    type: USED_IN_PYTHON
    weight: 0.5
  - to: lupo-scripts/rebuild_schema_from_toons.py
    type: USED_IN_PYTHON
    weight: 0.5
  - to: lupo-scripts/verify_db_against_toons.py
    type: USED_IN_PYTHON
    weight: 0.5
lupopedia.footer:
  last_verified: '20260324174926'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
---
# file: lupo_actors — delegation: junie:root — web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_actors
# Table: lupo_actors

## Table Overview

- **Purpose:** Single source of truth for all identities in Lupopedia: human users, AI agents, system processes, IDE faucets, and external entities. Primary key is actor_name (canonical semantic identity); actor_id is a unique numeric mapping used for joins, registry alignment, and session ownership. Supports the **Unified Identity Model (v4.0.86)** where actors are paired with human users by department.
- **Category:** Core System / Identity
- **Status:** Active (v4.0.86 Scope Lock)
- **Version introduced:** 4.0.0

## Where This Table Is Used

- **IDE agent registration:** IDE agents (Cursor, Windsurf, Kiro, Zencoder, etc.) are registered as actors; actor_id and slug are defined in the canonical registry (lupo-database/lupopedia/actors/actor_id/registry.json) and persisted here. Propagation and rule targets resolve actors from this table.
- **Human user identity:** Human users have actor_type 'human' and are linked to `lupo_auth_users` via `auth_user_id` or `lupo_actor_auth_users`. can_login and identity_provider_config support web login. Session ownership (lupo_sessions.actor_id) resolves to this table.
- **Orchestrator roles:** Lead orchestration and supporting actors (e.g. Cursor, Wolfie) are identified by actor_id and slug.
- **Paired Actor Model:** `paired_actor_id` allows an AI agent actor to be paired with a human orchestrator actor.
- **Department Association:** `department_id` provides a primary department link, while `lupo_actor_departments` allows multi-department membership.

## Column Documentation

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| actor_name | varchar(64) | No | — | Primary key (Canonical semantic name). |
| actor_id | bigint | Yes | — | Unique numeric mapping (Join key). |
| actor_type | varchar(64) | No | — | Type: system, human, agent, system_tool. |
| slug | varchar(255) | No | — | URL-friendly identifier. |
| name | varchar(255) | No | — | Display name. |
| created_ymdhis | bigint | No | 0 | Creation (BIGINT UTC). |
| updated_ymdhis | bigint | No | — | Last update (BIGINT UTC). |
| is_active | tinyint | No | 1 | Active status. |
| is_deleted | tinyint | No | 0 | Soft delete flag. |
| deleted_ymdhis | bigint | Yes | — | Soft delete timestamp. |
| actor_source_id | bigint | Yes | — | Source system ID. |
| actor_source_type | varchar(64) | Yes | — | Source system type. |
| metadata | text | Yes | — | Legacy metadata. |
| adversarial_role | varchar(64) | Yes | 'none' | Adversarial designation. |
| adversarial_oversight_actor_id | bigint | Yes | — | Oversight actor ID. |
| avatar_hash | varchar(64) | Yes | — | Avatar image hash. |
| actor_config | text | Yes | — | Actor-specific configuration (Serialized/JSON). |
| primary_federation_node_id | bigint | No | 1 | Federation node ID. |
| department_id | bigint | Yes | — | Primary department ID. |
| is_kernel | tinyint | No | 0 | Kernel/system actor flag. |
| can_login | tinyint | No | 0 | Login capability flag. |
| metadata_json | json | Yes | — | Structured metadata (Identity Capsule). |
| identity_provider_config | json | Yes | — | Auth provider config. |
| paired_actor_id | bigint | No | 0 | Paired actor ID (Agent-Human link). |
| is_agent | tinyint | No | 0 | AI agent flag. |
| actor_root_path | varchar(512) | Yes | 'actors/{actor_id}' | Identity Capsule path. |
| workspace_path | varchar(255) | Yes | — | Workspace path. |
| php_namespace | varchar(120) | Yes | — | PHP namespace. |
| who_json_sync_status | varchar(64) | Yes | 'pending' | WHO.json sync status. |
| last_sync_ymdhis | bigint | Yes | 0 | Last sync (BIGINT UTC). |
| auth_user_id | bigint | Yes | — | Direct link to lupo_auth_users. |
| actor_tier | tinyint | Yes | 3 | Identity tier (0-3). |

## Indexes

- **PRIMARY KEY:** actor_name
- **UNIQUE:** lupo_actors_unique_actor_id (actor_id), lupo_actors_unique_slug (slug)
- **INDEX:** lupo_actors_idx_actor_type, lupo_actors_idx_is_active, lupo_actors_idx_created_ymdhis, lupo_actors_idx_workspace_path, lupo_actors_idx_php_namespace

## Relationships

- **Logical references (no DB FKs):** primary_federation_node_id → lupo_federation_nodes; department_id → lupo_departments; adversarial_oversight_actor_id, paired_actor_id → lupo_actors.
- **Tables that reference lupo_actors:** lupo_sessions, lupo_contents, lupo_channels (created_by_actor_id, default_actor_id), lupo_dialog_messages, lupo_auth_users (via actor_id mapping), lupo_actor_channels, lupo_agents, and other actor-scoped tables use actor_id or actor_name as the join key.

## Doctrine Notes

- **No foreign keys.** All referential integrity in application code.
- **Reserved ID:** actor_id must be supplied explicitly; never use AUTO_INCREMENT or lastInsertId() for this table. Allocate from registry or COALESCE(MAX(actor_id),0)+1; use SELECT then UPDATE or INSERT with explicit ID.
- **Primary key:** actor_name is canonical identity; use ActorService::getActorByName / resolveActor for lookups. actor_id is the join key in most referencing tables.
- **Human actor IDs:** Human actors typically have actor_id >= 1000 per Human Actor ID doctrine; IDE/system actors use reserved IDs from registry.
- **Timestamps:** BIGINT YYYYMMDDHHIISS UTC; set in PHP with `gmdate('YmdHis')`.
- **Soft delete:** Filter by `is_deleted = 0` unless querying deleted actors.
