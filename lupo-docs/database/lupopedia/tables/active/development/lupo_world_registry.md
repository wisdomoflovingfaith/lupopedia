---
lupopedia.headers:
  lupopedia.version: "4.0.77"
  lupopedia.schema: "database_table"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/development/lupo_world_registry.md"
  system_version: "4.0.77"
  namespace: "core"
  channel_id: 42
  actor_id: 106
  last_modified_utc: "20260316"
  artifact_type: "table_documentation"
  purpose: "Documentation for lupo_world_registry table - canonical registry of named semantic worlds/namespaces"
  mood_rgb: "4169E1"
  artifact_kind: "table"
  traits: ["development", "semantic", "world", "registry", "v4.0.77"]
  tags: ["database", "world", "registry", "semantic", "development"]
  lupo_agent: "zencoder"
  table_primary_key: "world_id"
  lupo_world_registry.world_id: "BIGINT NOT NULL primary key"
  lupo_world_registry.world_key: "VARCHAR(255) NOT NULL UNIQUE — machine-readable world identifier"
  lupo_world_registry.world_type: "VARCHAR(64) NOT NULL — classification of the world (e.g. 'semantic', 'federation', 'channel_group')"
  lupo_world_registry.world_label: "VARCHAR(255) NOT NULL — human-readable display name"
  lupo_world_registry.world_metadata: "JSON — extended attributes and config for this world"
  lupo_world_registry.created_ymdhis: "BIGINT NOT NULL DEFAULT 0 — YYYYMMDDHHIISS UTC creation timestamp"
  lupo_world_registry.updated_ymdhis: "BIGINT NOT NULL — YYYYMMDDHHIISS UTC last-update timestamp"
  lupo_world_registry.is_active: "TINYINT NOT NULL DEFAULT 1 — soft active flag (0 = disabled)"
  table_engine: "InnoDB"
  table_charset: "utf8mb4"
  table_indexes: ["lupo_world_registry_idx_created_ymdhis", "lupo_world_registry_idx_is_active", "lupo_world_registry_idx_world_type", "lupo_world_registry_unique_world_key"]
  doctrine_note: "No database foreign keys; referential integrity enforced in application code."

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_edges.md", type: "references", weight: 0.8 }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "schema_reference", weight: 0.9 }

lupopedia.footer:
  version: "4.0.77"
  last_verified: "20260316"
  last_verified_by: "zencoder"
---

# Table: lupo_world_registry

## Table Overview

- **Purpose:** Canonical registry of named semantic worlds — logical groupings or namespaces that partition the semantic graph. Each world has a machine-readable key, a type classifier, a display label, and optional JSON metadata. Worlds organize federation nodes, semantic edges, channel groups, and other scoped entities.
- **Category:** Semantic OS / World / Registry
- **Status:** Development (not yet in canonical install SQL; defined in `development/` TOON only)
- **Version introduced:** 4.0.x (development)

## Where This Table Is Used

- **Semantic edge scoping:** Edges and truth nodes may reference a `world_key` to indicate which semantic world they belong to, allowing partitioned graph traversal.
- **Federation world partitioning:** Federation nodes may be classified under a world to group related nodes by deployment context or semantic domain.
- **Channel group organization:** Channel groups or project namespaces can be associated with a world entry to partition content and activity by domain.
- **World-aware query context:** Application services that support multi-world deployments query this table to resolve an active world by `world_key` before performing scoped lookups.
- **Admin and tooling:** Development tooling (CLI, admin UI) uses this registry to list available worlds and validate world_key references in semantic metadata.

## Column Documentation

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| world_id | bigint | No | — | Primary key. Explicit BIGINT; not AUTO_INCREMENT per reserved-ID doctrine. |
| world_key | varchar(255) | No | — | Unique machine-readable identifier for this world (e.g. `lupopedia-core`, `dev`, `public`). |
| world_type | varchar(64) | No | — | Type/class of world: e.g. `semantic`, `federation`, `channel_group`, `project_namespace`. |
| world_label | varchar(255) | No | — | Human-readable display name shown in UI and tooling. |
| world_metadata | json | Yes | — | Optional JSON payload for extended configuration (e.g. default channel, root actor, access rules). |
| created_ymdhis | bigint | No | `0` | Creation timestamp in YYYYMMDDHHIISS UTC format. |
| updated_ymdhis | bigint | No | — | Last-updated timestamp in YYYYMMDDHHIISS UTC format. |
| is_active | tinyint | No | `1` | Active flag. `1` = active; `0` = disabled/archived. |

## Indexes

| Index Name | Columns | Unique | Notes |
|-----------|---------|--------|-------|
| PRIMARY | world_id | Yes | Primary key |
| lupo_world_registry_unique_world_key | world_key | Yes | Enforces world_key uniqueness across all worlds |
| lupo_world_registry_idx_world_type | world_type | No | Filter worlds by type |
| lupo_world_registry_idx_is_active | is_active | No | Quickly filter active/disabled worlds |
| lupo_world_registry_idx_created_ymdhis | created_ymdhis | No | Time-based ordering and range queries |

## Usage Patterns

### Resolve active world by key
```php
$db = DatabaseFactory::getConnection();
$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$world = $db->fetchRow(
    "SELECT world_id, world_label, world_metadata
     FROM {$prefix}world_registry
     WHERE world_key = :key AND is_active = 1",
    ['key' => 'lupopedia-core']
);
```

### List all active worlds of a given type
```php
$db = DatabaseFactory::getConnection();
$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$worlds = $db->fetchAll(
    "SELECT world_id, world_key, world_label
     FROM {$prefix}world_registry
     WHERE world_type = :type AND is_active = 1
     ORDER BY world_label ASC",
    ['type' => 'semantic']
);
```

### Upsert a world entry
```php
$db = DatabaseFactory::getConnection();
$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$now = (int) gmdate('YmdHis');
$db->query(
    "INSERT INTO {$prefix}world_registry
     (world_id, world_key, world_type, world_label, world_metadata, created_ymdhis, updated_ymdhis, is_active)
     VALUES (:id, :key, :type, :label, :meta, :ts, :ts, 1)
     ON DUPLICATE KEY UPDATE world_label = :label, world_metadata = :meta, updated_ymdhis = :ts",
    ['id' => $newId, 'key' => 'lupopedia-core', 'type' => 'semantic',
     'label' => 'Lupopedia Core', 'meta' => json_encode($config), 'ts' => $now]
);
```

## Doctrine Notes

- **No foreign keys.** `world_id` is referenced externally by application code only; no DB-level FK constraints per database-logic-prohibition-doctrine.
- **Unique world_key.** The `world_key` column has a unique index; ON DUPLICATE KEY UPDATE must be used when upserting.
- **Timestamps:** `created_ymdhis` and `updated_ymdhis` are BIGINT UTC YYYYMMDDHHIISS; never use `CURRENT_TIMESTAMP` or `ON UPDATE`.
- **is_active soft flag.** Use `is_active = 0` to disable a world without deleting it; all active-world queries must filter `is_active = 1`.
- **Status:** Development table; not present in `install_new_lupopedia.sql` as of 4.0.77. Schema defined in `lupo-docs/database/lupopedia/tables/active/development/lupo_world_registry.toon`.
