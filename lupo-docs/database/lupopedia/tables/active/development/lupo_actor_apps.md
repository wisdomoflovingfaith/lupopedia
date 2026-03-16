---
lupopedia.headers:
  lupopedia.version: "4.0.77"
  lupopedia.schema: "database_table"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/development/lupo_actor_apps.md"
  system_version: "4.0.77"
  namespace: "actor"
  channel_id: 42
  actor_id: 106
  last_modified_utc: "20260316"
  artifact_type: "table_documentation"
  purpose: "Documentation for lupo_actor_apps table - maps actors to their assigned application filesystem paths"
  mood_rgb: "4169E1"
  artifact_kind: "table"
  traits: ["development", "actor_system", "filesystem", "v4.0.77"]
  tags: ["database", "actors", "apps", "filesystem", "development"]
  lupo_agent: "zencoder"
  table_primary_key: "actor_app_id"
  lupo_actor_apps.actor_app_id: "BIGINT NOT NULL primary key"
  lupo_actor_apps.actor_id: "BIGINT NOT NULL reference to lupo_actors.actor_id (unique — one app path per actor)"
  lupo_actor_apps.apps_path: "VARCHAR(512) NOT NULL DEFAULT '' filesystem path to the actor's apps directory"
  lupo_actor_apps.updated_ymdhis: "BIGINT NOT NULL DEFAULT 0 YYYYMMDDHHIISS UTC last-update timestamp"
  table_engine: "InnoDB"
  table_charset: "utf8mb4"
  table_indexes: ["PRIMARY", "lupo_actor_apps_idx_updated", "lupo_actor_apps_unq_actor"]
  doctrine_note: "No database foreign keys; referential integrity enforced in application code."

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_actors.md", type: "references", weight: 1.0 }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "schema_reference", weight: 0.9 }

lupopedia.footer:
  version: "4.0.77"
  last_verified: "20260316"
  last_verified_by: "zencoder"
---

# Table: lupo_actor_apps

## Table Overview

- **Purpose:** Maps each actor to a dedicated filesystem `apps_path`, allowing actors to own and isolate their application resources within the Lupopedia directory structure. One actor may have at most one apps path (enforced by unique index on `actor_id`).
- **Category:** Actor System / Filesystem
- **Status:** Development (not yet in canonical install SQL; defined in `development/` TOON only)
- **Version introduced:** 4.0.x (development)

## Where This Table Is Used

- **Actor filesystem isolation:** Resolves an actor's app directory for loading actor-specific tools, scripts, and resource files.
- **lupo-actors/ directory model:** Mirrors the `lupo-actors/{actor_id}/apps/` path convention; this table provides the canonical DB-side record of that path.
- **ActorService / actor bootstrap:** Application code may query this table when resolving an actor's resource root during session or boot initialization.

## Column Documentation

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| actor_app_id | bigint | No | — | Primary key. Explicit BIGINT; not AUTO_INCREMENT per reserved-ID doctrine. |
| actor_id | bigint | No | — | Reference to `lupo_actors.actor_id`. Unique — each actor has at most one apps path. |
| apps_path | varchar(512) | No | `''` | Absolute or relative filesystem path to the actor's apps directory (e.g. `lupo-actors/102/apps/`). |
| updated_ymdhis | bigint | No | `0` | Last-updated timestamp in YYYYMMDDHHIISS UTC format. Set in application code via `gmdate('YmdHis')`. |

## Indexes

| Index Name | Columns | Unique | Notes |
|-----------|---------|--------|-------|
| PRIMARY | actor_app_id | Yes | Primary key |
| lupo_actor_apps_unq_actor | actor_id | Yes | Enforces one apps_path per actor |
| lupo_actor_apps_idx_updated | updated_ymdhis | No | Supports time-based queries |

## Usage Patterns

### Resolve actor apps path
```php
$db = DatabaseFactory::getConnection();
$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$row = $db->fetchRow(
    "SELECT apps_path FROM {$prefix}actor_apps WHERE actor_id = :actor_id",
    ['actor_id' => $actorId]
);
$appsPath = $row ? $row['apps_path'] : null;
```

### Upsert actor apps path
```php
$db = DatabaseFactory::getConnection();
$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$now = gmdate('YmdHis');
$db->query(
    "INSERT INTO {$prefix}actor_apps (actor_app_id, actor_id, apps_path, updated_ymdhis)
     VALUES (:id, :actor_id, :path, :ts)
     ON DUPLICATE KEY UPDATE apps_path = :path, updated_ymdhis = :ts",
    ['id' => $newId, 'actor_id' => $actorId, 'path' => $path, 'ts' => $now]
);
```

## Doctrine Notes

- **No foreign keys.** `actor_id` logically references `lupo_actors.actor_id` but no DB-level FK is created (see database-logic-prohibition-doctrine).
- **Timestamps:** `updated_ymdhis` is BIGINT UTC YYYYMMDDHHIISS; never use `CURRENT_TIMESTAMP` or `ON UPDATE`.
- **Unique constraint on actor_id** ensures only one apps path per actor; application code must handle the `ON DUPLICATE KEY` scenario explicitly.
- **Status:** Development table; not present in `install_new_lupopedia.sql` as of 4.0.77. Schema defined in `lupo-docs/database/lupopedia/tables/active/development/lupo_actor_apps.toon`.
