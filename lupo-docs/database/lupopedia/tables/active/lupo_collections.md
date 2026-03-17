---
lupopedia.headers:
  lupopedia.version: "4.0.79"
  lupopedia.schema: "database_table"
  system_version: "4.0.79"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_collections.md"
  web_path: "[lupo_collections](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_collections)"
  last_modified_utc: "20260317"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "table_documentation"
  artifact_kind: "table"
  namespace: "content"
  purpose: "Collection management; resource bundles for content grouping, navigation, and channel-scoped organization"
  tags: ["database", "table", "content"]

lupopedia.edges:
  comment: "Snapshot of edges for lupo_collections table doc at 4.0.79 (grounded by repo search; non-exhaustive)."
  meta: "php_hits=0 python_hits=1"
  outbound_edges:
    - { to: "database.table.lupo_collections", type: "DEFINES_SCHEMA_FOR", weight: 1.0 }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "schema_reference", weight: 1.0 }
    - { to: "analyze_unused_tables.py", type: "USED_IN_PYTHON", weight: 0.5 }
    - { to: "(no_php_refs_found)", type: "USED_IN_PHP", weight: 0.0 }

lupopedia.footer:
  version: "4.0.79"
  last_verified: "20260317"
  last_verified_by: "cursor"
---
# file: lupo_collections ? web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_collections
# Table: lupo_collections

## Table Overview

- **Purpose:** Collections are resource bundles that group content for navigation, knowledge domains, and UI presentation. Each collection has a name, slug (unique per federation node), optional department and actor, channel scope, sort order, and optional nav menu usage (is_nav_menu, nav_icon). Supports hierarchy via parent_id and channel-scoped organization (channel_id, 4.0.69+).
- **Category:** Content / Collections
- **Status:** Active (in install_new_lupopedia.sql)
- **Version introduced:** 4.0.x (channel_id, is_nav_menu, nav_icon in 4.0.69)

## Where This Table Is Used

- **Content grouping:** Content rows can reference default_collection_id or be linked via collection_tab_map; collections organize content for help trees, knowledge bases, and listing pages.
- **Navigation:** is_nav_menu and nav_icon drive UI navigation; collections appear as menu groups or tabs.
- **Federation and departments:** federation_node_id scopes collections per node; department_id and actor_id assign ownership; slug is unique per (federation_node_id, slug).
- **Channel scope:** channel_id scopes a collection to a channel for channel-specific resource bundles.

## Column Documentation

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| collection_id | bigint | No | — | Primary key. |
| federation_node_id | bigint | No | — | Federation node; slug unique per node. |
| actor_id | bigint | Yes | NULL | Owning actor. |
| department_id | bigint | Yes | NULL | Department assignment. |
| name | varchar(255) | No | — | Display name. |
| slug | varchar(100) | No | — | URL-friendly slug; unique per federation_node_id. |
| color | char(6) | Yes | '666666' | Hex color. |
| description | text | Yes | NULL | Description. |
| sort_order | int | Yes | 0 | Sort order. |
| properties | text | Yes | NULL | Additional properties. |
| published_ymdhis | bigint | Yes | NULL | Publication timestamp (BIGINT UTC). |
| created_ymdhis | bigint | No | 0 | Creation timestamp (BIGINT UTC). |
| updated_ymdhis | bigint | No | — | Last update timestamp (BIGINT UTC). |
| is_deleted | tinyint | No | 0 | Soft delete flag. |
| deleted_ymdhis | bigint | Yes | NULL | Soft delete timestamp. |
| parent_id | bigint | Yes | NULL | Parent collection for hierarchy. |
| channel_id | bigint | Yes | NULL | Channel scope (4.0.69+). |
| is_nav_menu | tinyint | No | 0 | Show in nav menu (4.0.69+). |
| nav_icon | varchar(64) | Yes | NULL | Nav icon identifier (4.0.69+). |

## Indexes

- **PRIMARY KEY:** collection_id
- **UNIQUE:** lupo_collections_unique_collection_slug_domain (federation_node_id, slug)
- **INDEX:** lupo_collections_idx_name, lupo_collections_idx_domain, lupo_collections_idx_department, lupo_collections_idx_created_ymdhis, lupo_collections_idx_updated_ymdhis, lupo_collections_idx_is_deleted, lupo_collections_idx_sort_order, lupo_collections_idx_actor, lupo_collections_idx_channel_id, lupo_collections_idx_is_nav_menu

## Relationships

- **Logical references (no DB FKs):** federation_node_id → lupo_federation_nodes; actor_id → lupo_actors; department_id → lupo_departments; parent_id → lupo_collections; channel_id → lupo_channels. lupo_collection_tabs and lupo_collection_tab_map link tabs and items to collections.

## Doctrine notes

- No database foreign keys; referential integrity enforced in application code.
- All timestamps BIGINT UTC YYYYMMDDHHIISS.
- Soft delete: filter `is_deleted = 0` unless querying deleted rows.
