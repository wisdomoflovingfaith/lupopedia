---
lupopedia.headers:
  lupopedia.version: "4.0.78"
  lupopedia.schema: "database_table"
  system_version: "4.0.78"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_metadata.md"
  web_path: "[lupo_metadata](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_metadata)"
  last_modified_utc: "20260316"
  channel_id: 42
  actor_id: 102
  artifact_type: "table_documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Generalized entity metadata storage; LUPOPEDIA HEADERS and extensible key-value metadata for files, channels, and other entities"
  traits: ["canonical", "core_system", "metadata", "v4.0.78"]
  tags: ["database", "metadata", "entity_properties", "headers"]
  table_primary_key: "metadata_id"
  doctrine_note: "No database foreign keys; referential integrity enforced in application code. All timestamps BIGINT UTC YYYYMMDDHHIISS."

lupopedia.edges:
  comment: "Snapshot of edges for lupo_metadata table doc at 4.0.78."
  outbound_edges:
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_channels.md", type: "references", weight: 0.8 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_actors.md", type: "references", weight: 0.8 }
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md", type: "references", weight: 0.9 }

lupopedia.footer:
  version: "4.0.78"
  last_verified: "20260316"
  last_verified_by: "cursor"
---
# file: lupo_metadata — web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_metadata

# Table: lupo_metadata

## Table Overview

- **Purpose:** Consolidated metadata table for entity-scoped key-value properties. Replaces legacy lupo_actor_meta, lupo_actor_properties, lupo_agent_properties. Stores LUPOPEDIA HEADERS and extensible metadata for files, channels, content, and other entities. Supports channel-scoped and hierarchical metadata (parent_metadata_id, class_name). Used for header storage, entity properties, and configuration.
- **Category:** Core System / Metadata
- **Status:** Active (in install_new_lupopedia.sql)
- **Version introduced:** 4.0.68+ (channel_id, parent_metadata_id, class_name)

## Where This Table Is Used

- **LUPOPEDIA HEADERS storage:** Header blocks (lupopedia.headers, lupopedia.metadata, lupopedia.footer) are stored in `lupo_metadata` keyed by entity_type and entity_id (and optionally channel_id). Validators and tooling read/write via this table.
- **Entity properties:** Arbitrary key-value metadata for actors, channels, content, and other entity types; meta_type and property_key identify the property.
- **Channel-scoped metadata:** channel_id scopes metadata to a channel; queries filter by channel_id for channel-specific headers or properties.
- **Hierarchical metadata:** parent_metadata_id links child metadata rows for nested structures; class_name identifies the metadata class.

## Column Documentation

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| metadata_id | bigint | No | — | Primary key. Application-supplied; reserved-ID doctrine where applicable. |
| entity_type | varchar(32) | No | — | Entity type (e.g. file, channel, content). |
| entity_id | bigint | No | — | Entity identifier. |
| domain_id | bigint | Yes | NULL | Optional domain/scope. |
| meta_type | varchar(64) | Yes | NULL | Metadata type or category. |
| property_key | varchar(255) | No | — | Property name or key. |
| property_value | text | Yes | — | Property value. |
| created_ymdhis | bigint | No | 0 | Creation timestamp (BIGINT UTC YYYYMMDDHHIISS). |
| updated_ymdhis | bigint | No | — | Last update timestamp (BIGINT UTC). |
| is_deleted | tinyint | No | 0 | Soft delete flag. |
| deleted_ymdhis | bigint | Yes | NULL | Soft delete timestamp. |
| channel_id | bigint | Yes | NULL | Channel scope (4.0.68+). |
| parent_metadata_id | bigint | Yes | NULL | Parent metadata row for hierarchy (4.0.68+). |
| class_name | varchar(128) | Yes | NULL | Metadata class identifier (4.0.68+). |
| schema_ref | varchar(64) | Yes | NULL | Schema reference. |

## Indexes

- **PRIMARY KEY:** metadata_id
- **UNIQUE:** lupo_metadata_unique_entity_domain_property (entity_type, entity_id, domain_id, property_key)
- **INDEX:** lupo_metadata_idx_entity, lupo_metadata_idx_domain, lupo_metadata_idx_meta_type, lupo_metadata_idx_property_key, lupo_metadata_idx_created_ymdhis, lupo_metadata_idx_updated_ymdhis, lupo_metadata_idx_is_deleted, lupo_metadata_idx_channel_id, lupo_metadata_idx_parent_metadata_id, lupo_metadata_idx_class_name, lupo_metadata_idx_entity_deleted, lupo_metadata_idx_channel_deleted, lupo_metadata_idx_parent_deleted, lupo_metadata_idx_meta_type_deleted, lupo_metadata_idx_class_deleted

## Relationships

- **Logical references (no DB FKs):** channel_id → lupo_channels; parent_metadata_id → lupo_metadata (self). entity_type/entity_id reference various entities (actors, channels, content) by application convention.

## Doctrine notes

- No database foreign keys; referential integrity enforced in application code.
- All timestamps BIGINT UTC YYYYMMDDHHIISS; set in PHP with `gmdate('YmdHis')`.
- Soft delete: filter `is_deleted = 0` unless querying deleted rows.
