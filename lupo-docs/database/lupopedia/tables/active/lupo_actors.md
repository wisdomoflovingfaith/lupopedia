---
lupopedia.headers:
  lupopedia.version: "4.0.78"
  lupopedia.schema: "database_table"
  system_version: "4.0.78"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_actors.md"
  web_path: "[lupo_actors](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_actors)"
  last_modified_utc: "20260316"
  channel_id: 42
  actor_id: 102
  artifact_type: "table_documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Documentation for lupo_actors table - unified actor identity and management"
  traits: ["canonical", "core_system", "identity", "v4.0.78"]
  tags: ["database", "actors", "identity", "registry"]
  table_primary_key: "actor_name"
  doctrine_note: "No database foreign keys; referential integrity enforced in application code. actor_id is NOT AUTO_INCREMENT; application must supply explicit ID (reserved-ID doctrine)."

lupopedia.edges:
  comment: "Snapshot of edges for lupo_actors table doc at 4.0.78."
  outbound_edges:
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-database/lupopedia/actors/actor_id/registry.json", type: "references", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_channels.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_sessions.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_contents.md", type: "references", weight: 0.8 }

lupopedia.footer:
  version: "4.0.78"
  last_verified: "20260316"
  last_verified_by: "cursor"
---
# file: lupo_actors — web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_actors

# Table: lupo_actors

## Table Overview

- **Purpose:** Single source of truth for all identities in Lupopedia: human users, AI agents, system processes, IDE faucets, and external entities. Primary key is actor_name (canonical semantic identity); actor_id is a unique numeric mapping used for joins, registry alignment, and session ownership. Supports identity capsule (actor_root_path, WHO.json sync), federation node, department, paired-actor relationship, and adversarial oversight.
- **Category:** Core System / Identity
- **Status:** Active (in install_new_lupopedia.sql)
- **Version introduced:** 4.0.x

## Where This Table Is Used

- **IDE agent registration:** IDE agents (Cursor, Windsurf, Kiro, Zencoder, etc.) are registered as actors; actor_id and slug are defined in the canonical registry (lupo-database/lupopedia/actors/actor_id/registry.json) and persisted here. Propagation and rule targets resolve actors from this table.
- **Human user identity:** Human users have actor_type 'human' and are linked to lupo_auth_users; can_login and identity_provider_config support web login. Session ownership (lupo_sessions.actor_id) resolves to this table.
- **Orchestrator roles:** Lead orchestration and supporting actors (e.g. Cursor, Wolfie) are identified by actor_id and slug; AGENTS.md and doctrine reference this table for who operates the repo.
- **Session ownership:** lupo_sessions stores actor_id; current user/agent identity is resolved from lupo_actors. Auth guard and session lifecycle use this table for actor_id → name/slug/type.
- **Federation actor identities:** primary_federation_node_id anchors each actor to a federation node; multi-node deployments use this for locality and authority.
- **Content and channel ownership:** lupo_contents.actor_id, lupo_channels.created_by_actor_id and default_actor_id, and dialog/message authorship reference lupo_actors.

## Column Documentation

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| actor_name | varchar(64) | No | — | Primary key. Canonical semantic actor identity (e.g. wolfie, cursor). |
| actor_id | bigint | Yes | — | Unique numeric mapping; used for joins, registry, sessions. Not AUTO_INCREMENT; application supplies explicit ID (reserved-ID doctrine). |
| actor_type | varchar(64) | No | — | Type: e.g. system, human, agent, system_tool, external_ai. |
| slug | varchar(255) | No | — | Unique URL-friendly identifier. |
| name | varchar(255) | No | — | Display name. |
| created_ymdhis | bigint | No | 0 | Creation timestamp (BIGINT UTC). |
| updated_ymdhis | bigint | No | — | Last update timestamp (BIGINT UTC). |
| is_active | tinyint | No | 1 | Active status flag. |
| is_deleted | tinyint | No | 0 | Soft delete flag. |
| deleted_ymdhis | bigint | Yes | — | Soft delete timestamp. |
| actor_source_id | bigint | Yes | — | Source system identifier. |
| actor_source_type | varchar(64) | Yes | — | Source system type. |
| metadata | text | Yes | — | Legacy metadata. |
| adversarial_role | varchar(64) | Yes | 'none' | Adversarial role designation. |
| adversarial_oversight_actor_id | bigint | Yes | — | Oversight actor for adversarial actors. Logical reference to lupo_actors. |
| avatar_hash | varchar(64) | Yes | — | Avatar image hash. |
| primary_federation_node_id | bigint | No | 1 | Primary federation node. Logical reference to lupo_federation_nodes. |
| department_id | bigint | Yes | — | Department assignment. Logical reference to lupo_departments. |
| is_kernel | tinyint | No | 0 | Kernel/system actor flag. |
| can_login | tinyint | No | 0 | Login capability flag. |
| metadata_json | json | Yes | — | Structured metadata (e.g. Identity Capsule, capabilities). |
| identity_provider_config | json | Yes | — | Identity provider configuration. |
| paired_actor_id | bigint | No | 0 | Paired actor relationship (e.g. IDE agent paired to human orchestrator). Logical reference to lupo_actors. |
| is_agent | tinyint | No | 0 | AI agent flag. |
| actor_root_path | varchar(512) | Yes | 'actors/{actor_id}' | Filesystem path to actor directory (Identity Capsule). |
| workspace_path | varchar(255) | Yes | — | Workspace path. |
| php_namespace | varchar(120) | Yes | — | PHP namespace when applicable. |
| who_json_sync_status | varchar(64) | Yes | 'pending' | Status of WHO.json synchronization. |
| last_sync_ymdhis | bigint | Yes | 0 | Last filesystem sync timestamp (BIGINT UTC). |

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
