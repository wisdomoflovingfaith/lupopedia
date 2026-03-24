---
lupopedia.headers:
  version_when_written: "4.0.86"
  lupopedia.schema: "database_table"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_actor_auth_users.md"
  web_path: "http://www.lupopedia.com/database/lupopedia/tables/active/lupo_actor_auth_users"
  last_modified_utc: "20260324"
  channel_id: 42
  actor_id: 108
  actor_name: "junie"
  faucet_name: "jetbrains"
  delegation_chain: "junie:root"
  artifact_type: "table_documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Mapping table linking Actors to Human Auth Users (v4.0.86)"
  tags: ["database", "table", "core", "identity", "v4.0.86"]

lupopedia.edges:
  outbound_edges:
    - { to: "database.table.lupo_actor_auth_users", type: "DEFINES_SCHEMA_FOR", weight: 1.0 }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "schema_reference", weight: 1.0 }

lupopedia.footer:
  version: "4.0.86"
  last_verified: "20260324"
  last_verified_by: "junie"
---
# file: lupo_actor_auth_users — delegation: junie:root — web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_actor_auth_users
# Table: lupo_actor_auth_users

## Table Overview

- **Purpose:** Core mapping table for the **Unified Identity Model**. It links an `actor_id` (operational identity) to one or more `auth_user_id` (human credentials). This supports multi-human orchestration of a single actor and identifies the 'primary' human responsible for an actor's actions.
- **Category:** Identity / Auth
- **Status:** Active
- **Version introduced:** 4.0.80

## Column Documentation

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| actor_auth_user_id | bigint | No | — | Primary Key. |
| actor_id | bigint | No | — | Operational Actor ID (Join key to lupo_actors). |
| auth_user_id | bigint | No | — | Human Auth User ID (Join key to lupo_auth_users). |
| relationship_role | varchar(64) | No | 'supporting_human' | Role (e.g., owner, orchestrator, observer). |
| is_primary | tinyint | No | 0 | Flag for the primary human owner. |
| routing_priority | smallint | No | 100 | Priority for notification routing. |
| status | varchar(32) | No | 'active' | Membership status. |
| metadata_json | json | Yes | — | Role-specific metadata. |
| created_ymdhis | bigint | No | 0 | Creation timestamp (BIGINT UTC). |
| updated_ymdhis | bigint | No | — | Last update timestamp (BIGINT UTC). |
| is_deleted | tinyint | No | 0 | Soft delete flag. |
| deleted_ymdhis | bigint | Yes | 0 | Soft delete timestamp. |

## Indexes

- **PRIMARY KEY:** actor_auth_user_id
- **UNIQUE:** lupo_actor_auth_users_unq_actor_user_role (actor_id, auth_user_id, relationship_role)
- **INDEX:** lupo_actor_auth_users_idx_actor_role_primary_lookup, lupo_actor_auth_users_idx_auth_user_status

## Relationships

- **Logical references:** actor_id → lupo_actors.actor_id; auth_user_id → lupo_auth_users.auth_user_id.

## Doctrine Notes

- **No foreign keys.**
- **BIGINT Timestamps.**
- **Identity Pairing:** This table is the authoritative source for resolving which human is "acting as" a specific actor in a given context.
