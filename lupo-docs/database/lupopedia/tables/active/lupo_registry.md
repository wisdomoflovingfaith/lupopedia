---
lupopedia.headers:
  lupopedia.version: "4.0.78"
  lupopedia.schema: "database_table"
  system_version: "4.0.78"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_registry.md"
  web_path: "[lupo_registry](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_registry)"
  last_modified_utc: "20260316"
  channel_id: 42
  actor_id: 102
  artifact_type: "table_documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Unified registry for entity indexing and ID reservation across federation nodes; entity_type, entity_index_id, optional metadata"
  traits: ["canonical", "core_system", "registry", "v4.0.78"]
  tags: ["database", "registry", "indexing", "reservation", "federation"]
  table_primary_key: "registry_id"
  doctrine_note: "No database foreign keys; referential integrity enforced in application code. All timestamps BIGINT UTC YYYYMMDDHHIISS. Reserved-ID and allocation logic in application."

lupopedia.edges:
  comment: "Snapshot of edges for lupo_registry table doc at 4.0.78."
  outbound_edges:
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_federation_nodes.md", type: "references", weight: 0.8 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_actors.md", type: "references", weight: 0.7 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_channels.md", type: "references", weight: 0.7 }

lupopedia.footer:
  version: "4.0.78"
  last_verified: "20260316"
  last_verified_by: "cursor"
---
# file: lupo_registry — web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_registry

# Table: lupo_registry

## Table Overview

- **Purpose:** Unified registry for entity indexing and ID reservation across federation nodes. Each row records an entity_type (e.g. actor, channel), entity_index_id and entity_index for allocation or lookup, federation_node_id, reserved_ymdhis, and optional metadata/entity_key/entity_name/entity_table. Used to avoid ID collisions and to track reserved IDs per node and type.
- **Category:** Core System / Registry
- **Status:** Active (in install_new_lupopedia.sql)
- **Version introduced:** 4.0.x

## Where This Table Is Used

- **ID reservation and allocation:** Application allocates or reserves IDs for registry-backed tables (e.g. actors, channels) by consulting or inserting rows keyed by entity_type and federation_node_id; entity_index_id and entity_index support next-ID or range semantics.
- **Federation scoping:** federation_node_id scopes registry entries per node; unique constraint on (entity_type, entity_index_id, federation_node_id) prevents duplicate reservations per node.
- **Entity metadata:** entity_key, entity_name, entity_table, metadata, metadata_json store optional context for the registered entity; is_kernel and is_active support system vs user entities.

## Column Documentation

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| registry_id | bigint | No | — | Primary key. |
| entity_type | varchar(50) | No | — | Entity type (e.g. actor, channel). |
| entity_index_id | bigint | No | 0 | Entity index identifier. |
| entity_index | bigint | No | 0 | Entity index value. |
| federation_node_id | bigint | No | 0 | Federation node. |
| reserved_ymdhis | bigint | No | 0 | Reservation timestamp (BIGINT UTC). |
| metadata | text | Yes | NULL | Legacy metadata. |
| entity_key | varchar(255) | Yes | NULL | Optional entity key. |
| entity_name | varchar(255) | Yes | NULL | Optional entity name. |
| entity_table | varchar(255) | Yes | NULL | Optional table name. |
| created_ymdhis | bigint | No | 0 | Creation timestamp (BIGINT UTC). |
| updated_ymdhis | bigint | No | 0 | Last update timestamp (BIGINT UTC). |
| is_deleted | tinyint | No | 0 | Soft delete flag. |
| deleted_ymdhis | bigint | Yes | NULL | Soft delete timestamp. |
| is_active | tinyint | No | 1 | Active flag. |
| is_kernel | tinyint | No | 0 | Kernel/system entity flag. |
| metadata_json | text | Yes | NULL | JSON metadata. |

## Indexes

- **PRIMARY KEY:** registry_id
- **UNIQUE:** idx_registry_unique (entity_type, entity_index_id, federation_node_id)
- **INDEX:** idx_registry_entity_type (entity_type), idx_registry_federation_node (federation_node_id)

## Relationships

- **Logical references (no DB FKs):** federation_node_id → lupo_federation_nodes. Application uses this table to allocate or reserve IDs for lupo_actors, lupo_channels, and other registry-backed entities.

## Doctrine notes

- No database foreign keys; referential integrity enforced in application code.
- All timestamps BIGINT UTC YYYYMMDDHHIISS.
- Reserved-ID doctrine: application supplies explicit IDs for registry-backed tables; this table supports allocation and collision avoidance.
- Soft delete: filter `is_deleted = 0` unless querying deleted rows.
