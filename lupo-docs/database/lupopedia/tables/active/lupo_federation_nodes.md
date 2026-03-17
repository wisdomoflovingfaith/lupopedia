---
lupopedia.headers:
  lupopedia.version: "4.0.79"
  lupopedia.schema: "database_table"
  system_version: "4.0.79"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_federation_nodes.md"
  web_path: "[lupo_federation_nodes](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_federation_nodes)"
  last_modified_utc: "20260317"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "table_documentation"
  artifact_kind: "table"
  namespace: "federation"
  purpose: "Federation node registry; base URL, trust level, status, cached counts, and node metadata for multi-node Lupopedia"
  tags: ["database", "table", "federation"]

lupopedia.edges:
  comment: "Snapshot of edges for lupo_federation_nodes table doc at 4.0.79 (grounded by repo search; non-exhaustive)."
  meta: "php_hits=2 python_hits=4"
  outbound_edges:
    - { to: "database.table.lupo_federation_nodes", type: "DEFINES_SCHEMA_FOR", weight: 1.0 }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-bin/initialize_system.php", type: "USED_IN_PHP", weight: 0.6 }
    - { to: "lupo-includes/theme/theme-loader.php", type: "USED_IN_PHP", weight: 0.9 }
    - { to: "analyze_unused_tables.py", type: "USED_IN_PYTHON", weight: 0.5 }
    - { to: "lupo-scripts/wolfie_orms.py", type: "USED_IN_PYTHON", weight: 0.5 }
    - { to: "lupo-tools/flare_apply.py", type: "USED_IN_PYTHON", weight: 0.5 }
    - { to: "lupo-tools/merge_batch_into_seed.py", type: "USED_IN_PYTHON", weight: 0.5 }

lupopedia.footer:
  version: "4.0.79"
  last_verified: "20260317"
  last_verified_by: "cursor"
---
# file: lupo_federation_nodes ? web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_federation_nodes
# Table: lupo_federation_nodes

## Table Overview

- **Purpose:** Central registry for federation nodes. Each row is one node: federation_node_id (PK), node_type (e.g. local), node_base_url, default_department_id, node_name, node_description, allows_foreign_traits, node_contact, meta_json, cached counts (content_count, atom_count, hashtag_count, actor_count), last_sync_ymdhis, trust_level, status, soft delete, timestamps, and active_theme_slug. Used for multi-node connectivity, trust, and sync.
- **Category:** Federation / Network
- **Status:** Active (in install_new_lupopedia.sql)
- **Version introduced:** 4.0.x

## Where This Table Is Used

- **Channel and content partitioning:** Channels, collections, departments, and content reference federation_node_id; routing and queries scope by node.
- **Trust and sync:** trust_level and last_sync_ymdhis support federated sync and trust policies; node_base_url is used for outbound requests to the node.
- **Default department:** default_department_id assigns a default department for content originating from this node.
- **Cached counts:** content_count, atom_count, hashtag_count, actor_count are denormalized counts for display or sync decisions.

## Column Documentation

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| federation_node_id | bigint | No | — | Primary key. |
| node_type | varchar(32) | No | 'local' | Node type (e.g. local). |
| node_base_url | varchar(500) | No | — | Base URL of the node. |
| default_department_id | bigint | Yes | NULL | Default department for this node. |
| node_name | varchar(255) | Yes | NULL | Display name. |
| node_description | text | Yes | NULL | Description. |
| allows_foreign_traits | tinyint | No | 1 | Allow foreign traits flag. |
| node_contact | varchar(255) | Yes | NULL | Contact for node admin. |
| meta_json | json | Yes | NULL | Node metadata (JSON). |
| content_count | bigint | No | 0 | Cached content count. |
| atom_count | bigint | No | 0 | Cached atom count. |
| hashtag_count | bigint | No | 0 | Cached hashtag count. |
| actor_count | bigint | No | 0 | Cached actor count. |
| last_sync_ymdhis | bigint | No | 0 | Last sync timestamp (BIGINT UTC). |
| trust_level | tinyint | No | 0 | Trust level. |
| status | tinyint | No | 1 | Status. |
| is_deleted | tinyint | No | 0 | Soft delete flag. |
| deleted_ymdhis | bigint | No | 0 | Soft delete timestamp. |
| created_ymdhis | bigint | No | 0 | Creation timestamp (BIGINT UTC). |
| updated_ymdhis | bigint | No | 0 | Last update timestamp (BIGINT UTC). |
| active_theme_slug | varchar(64) | Yes | 'default' | Active theme slug. |

## Indexes

- **PRIMARY KEY:** federation_node_id
- **INDEX:** lupo_federation_nodes_idx_node_base_url (node_base_url), lupo_federation_nodes_idx_status (status), lupo_federation_nodes_idx_trust_level (trust_level), lupo_federation_nodes_idx_is_deleted (is_deleted)

## Relationships

- **Logical references (no DB FKs):** default_department_id → lupo_departments. lupo_channels, lupo_collections, lupo_departments, lupo_registry, and other tables reference federation_node_id to scope data per node.

## Doctrine notes

- No database foreign keys; referential integrity enforced in application code.
- All timestamps BIGINT UTC YYYYMMDDHHIISS.
- Reserved-ID doctrine may apply: federation_node_id is often application-supplied (e.g. 1 = local).
- Soft delete: filter `is_deleted = 0` unless querying deleted rows.
