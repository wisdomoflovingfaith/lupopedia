---
lupopedia.headers:
  lupopedia.version: "4.0.79"
  lupopedia.schema: "database_table"
  system_version: "4.0.79"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_modules.md"
  web_path: "[lupo_modules](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_modules)"
  last_modified_utc: "20260317"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "table_documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Module registry and configuration; plugin/module key, namespace, version, paths, config_json, and federation node scoping"
  tags: ["database", "table", "core"]

lupopedia.edges:
  comment: "Snapshot of edges for lupo_modules table doc at 4.0.79 (grounded by repo search; non-exhaustive)."
  meta: "php_hits=2 python_hits=1"
  outbound_edges:
    - { to: "database.table.lupo_modules", type: "DEFINES_SCHEMA_FOR", weight: 1.0 }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "schema_reference", weight: 1.0 }
    - { to: "debug_captain.php", type: "USED_IN_PHP", weight: 0.6 }
    - { to: "lupo-includes/modules/crafty_syntax/visitor-chat-stream.php", type: "USED_IN_PHP", weight: 0.9 }
    - { to: "analyze_unused_tables.py", type: "USED_IN_PYTHON", weight: 0.5 }

lupopedia.footer:
  version: "4.0.79"
  last_verified: "20260317"
  last_verified_by: "cursor"
---
# file: lupo_modules ? web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_modules
# Table: lupo_modules

## Table Overview

- **Purpose:** Module registry and configuration for Lupopedia plugins/modules. Each row defines a module by module_key (unique), module_name, namespace, version, version_code, minimum_core_version, optional user_path/admin_path/api_path, route_params, description, dependencies, conflicts, config_json (required), and federation_node_id. is_system and is_active control loading; installed_ymdhis and settings support lifecycle and per-instance settings.
- **Category:** Core System / Modules
- **Status:** Active (in install_new_lupopedia.sql)
- **Version introduced:** 4.0.x

## Where This Table Is Used

- **Module loading and routing:** Module loader and routing resolve modules by module_key; user_path, admin_path, api_path and route_params drive URL routing and entry points.
- **Dependency and conflict resolution:** dependencies and conflicts text fields support declarative dependency checks before activation.
- **Federation scoping:** federation_node_id scopes modules per node for multi-node deployments.
- **Configuration:** config_json and settings store module configuration; is_active and is_deleted control visibility and lifecycle.

## Column Documentation

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| module_id | bigint | No | — | Primary key. |
| module_key | varchar(100) | No | — | Unique module identifier. |
| module_name | varchar(150) | No | — | Display name. |
| namespace | varchar(100) | No | — | Module namespace. |
| version | varchar(50) | No | — | Version string. |
| version_code | int | No | — | Numeric version. |
| minimum_core_version | varchar(50) | No | — | Minimum core version required. |
| user_path | varchar(255) | Yes | NULL | User-facing path. |
| admin_path | varchar(255) | Yes | NULL | Admin path. |
| api_path | varchar(255) | Yes | NULL | API path. |
| route_params | text | Yes | NULL | Route parameters. |
| description | text | Yes | NULL | Description. |
| author | varchar(100) | Yes | NULL | Author. |
| website | varchar(255) | Yes | NULL | Website. |
| icon | varchar(100) | Yes | 'puzzle-piece' | Icon identifier. |
| dependencies | text | Yes | NULL | Dependency list. |
| conflicts | text | Yes | NULL | Conflict list. |
| config_json | text | No | — | Module configuration (JSON). |
| is_system | tinyint | No | 0 | System module flag. |
| is_active | tinyint | No | 0 | Active flag. |
| federation_node_id | bigint | No | 1 | Federation node. |
| settings | text | Yes | NULL | Instance settings. |
| installed_ymdhis | bigint | Yes | NULL | Install timestamp (BIGINT UTC). |
| created_ymdhis | bigint | No | 0 | Creation timestamp (BIGINT UTC). |
| updated_ymdhis | bigint | Yes | NULL | Last update timestamp (BIGINT UTC). |
| is_deleted | tinyint | No | 0 | Soft delete flag. |
| deleted_ymdhis | bigint | Yes | NULL | Soft delete timestamp. |

## Indexes

- **PRIMARY KEY:** module_id
- **UNIQUE:** lupo_modules_uq_module_key (module_key)
- **INDEX:** lupo_modules_idx_namespace (namespace), lupo_modules_idx_status (is_active, is_deleted), lupo_modules_idx_system (is_system), lupo_modules_idx_installed (installed_ymdhis)

## Relationships

- **Logical references (no DB FKs):** federation_node_id → lupo_federation_nodes. Module loader and routing code reference this table; no DB foreign keys.

## Doctrine notes

- No database foreign keys; referential integrity enforced in application code.
- All timestamps BIGINT UTC YYYYMMDDHHIISS.
- Soft delete: filter `is_deleted = 0` unless querying deleted rows.
