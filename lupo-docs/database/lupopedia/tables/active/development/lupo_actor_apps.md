---
lupopedia.headers:
  lupopedia.version: "4.0.78"
  lupopedia.schema: "database_table"
  system_version: "4.0.78"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/development/lupo_actor_apps.md"
  web_path: "[lupo_actor_apps](http://www.lupopedia.com/database/lupopedia/tables/active/development/lupo_actor_apps)"
  last_modified_utc: "20260316"
  channel_id: 42
  actor_id: 102
  artifact_type: "table_documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Documentation for lupo_actor_apps table - maps actors to filesystem application workspaces"
  traits: ["canonical", "actor_system", "filesystem", "v4.0.78"]
  tags: ["database", "actors", "apps", "filesystem"]
  table_primary_key: "actor_app_id"
  doctrine_note: "No database foreign keys; referential integrity enforced in application code."

lupopedia.edges:
  comment: "Snapshot of edges for lupo_actor_apps table doc at 4.0.78."
  outbound_edges:
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_actors.md", type: "references", weight: 1.0 }

lupopedia.footer:
  version: "4.0.78"
  last_verified: "20260316"
  last_verified_by: "cursor"
---
# file: lupo_actor_apps — web_path: http://www.lupopedia.com/database/lupopedia/tables/active/development/lupo_actor_apps

# Table: lupo_actor_apps

## Table Overview

- **Purpose:** Maps each actor to a single filesystem application workspace path (`apps_path`). One row per actor (enforced by unique index on actor_id). Used for actor workspace discovery, IDE agent application directories, and actor-level application deployment.
- **Category:** Actor System / Filesystem
- **Status:** Active (in install_new_lupopedia.sql)
- **Version introduced:** 4.0.x

## Where This Table Is Used

- **Actor workspace discovery:** Resolves an actor's app directory for loading actor-specific tools, scripts, and resource files. Bootstrap and session logic query by actor_id to get the canonical apps path.
- **IDE agent application directories:** IDE agents (Cursor, Windsurf, Kiro, etc.) have actor records; this table stores the path to each agent's `apps/` directory (e.g. under lupo-actors/{actor_id}/apps/ or a configured path).
- **Actor-level application deployment:** Application code deploys or discovers tools per actor using apps_path; ownership of application resources is by actor.
- **Runtime actor tooling:** When resolving which scripts or assets belong to an actor, services read apps_path from this table rather than hardcoding paths.
- **App discovery for agents:** Graph and documentation tooling can list actor apps by joining lupo_actors to this table; apps_path is the filesystem integration point for actor capsules.

## Column Documentation

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| actor_app_id | bigint | No | — | Primary key. Reserved-ID doctrine: application supplies explicit ID; not AUTO_INCREMENT. |
| actor_id | bigint | No | — | Logical reference to lupo_actors.actor_id. Unique — at most one apps path per actor. |
| apps_path | varchar(512) | No | '' | Filesystem path to the actor's apps directory (e.g. lupo-actors/102/apps/). |
| updated_ymdhis | bigint | No | 0 | Last-update timestamp in YYYYMMDDHHIISS UTC format. Set in application code via gmdate('YmdHis'). |

## Indexes

- **PRIMARY KEY:** actor_app_id
- **UNIQUE:** lupo_actor_apps_unq_actor (actor_id) — enforces one apps_path per actor
- **INDEX:** lupo_actor_apps_idx_updated (updated_ymdhis)

## Relationships

- **Logical references (no DB FKs):** actor_id → lupo_actors.actor_id. Application code must ensure the actor exists when inserting.
- **Usage:** Typically read by actor bootstrap, ActorService, or any code that needs the actor's apps directory; written when provisioning or updating an actor's app path.

## Doctrine Notes

- **No foreign keys.** Referential integrity enforced in application code.
- **Timestamps:** updated_ymdhis is BIGINT UTC YYYYMMDDHHIISS; set in PHP only.
- **Unique on actor_id:** Only one row per actor; use SELECT then UPDATE or INSERT with explicit actor_app_id, or handle ON DUPLICATE KEY UPDATE in application logic.
- **Reserved ID:** actor_app_id is not AUTO_INCREMENT; application must supply explicit value.
