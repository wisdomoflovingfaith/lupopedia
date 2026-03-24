---
lupopedia.headers:
  lupopedia.schema: database_table
  file_path_from_root: lupo-docs/database/lupopedia/tables/active/lupo_actor_traits.md
  web_path: '[lupo_actor_traits](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_actor_traits)'
  last_modified_utc: '20260317'
  channel_id: 42
  actor_id: 102
  actor_name: cursor
  delegation_chain: cursor:root
  artifact_type: table_documentation
  artifact_kind: table
  namespace: core
  purpose: Per-actor trait records (capabilities / flags) used for authorization and
    orchestration behavior.
  tags:
  - database
  - table
  - core
  when_updated: '20260324174654'
lupopedia.edges:
  comment: Snapshot of edges for lupo_actor_traits table doc at 4.0.79 (grounded by
    repo search; non-exhaustive).
  meta: php_hits=0 python_hits=1
  outbound_edges:
  - to: database.table.lupo_actor_traits
    type: DEFINES_SCHEMA_FOR
    weight: 1.0
  - to: lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql
    type: schema_reference
    weight: 1.0
  - to: lupo-scripts/check_doc_schema_consistency.py
    type: USED_IN_PYTHON
    weight: 0.5
  - to: (no_php_refs_found)
    type: USED_IN_PHP
    weight: 0.0
lupopedia.footer:
  last_verified: '20260317000000'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
---
# file: lupo_actor_traits ? web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_actor_traits
# Table: lupo_actor_traits

## Table Overview

- **Purpose:** Per-actor trait records (capabilities / flags) used for authorization and orchestration behavior.
- **Status:** Active (in install_new_lupopedia.sql)
- **Primary key:** `actor_trait_id`

## Where This Table Is Used

- This section is grounded by `USED_IN_PHP` / `USED_IN_PYTHON` edges in the header (populated by repo search).

## Column Documentation

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| actor_trait_id | bigint | No | ? | Primary key. |
| actor_id | bigint | No | ? |  |
| trait_key | varchar(128) | No | ? |  |
| trait_value | varchar(512) | Yes | NULL |  |
| federation_node_id | bigint | No | 1 |  |
| created_by_actor_id | bigint | Yes | NULL |  |
| created_ymdhis | bigint | No | 0 |  |
| updated_ymdhis | bigint | Yes | NULL |  |
| is_deleted | tinyint | No | 0 |  |
| deleted_ymdhis | bigint | Yes | NULL |  |
| metadata | text | Yes | NULL |  |

## Indexes

- **PRIMARY KEY:** actor_trait_id
- **INDEX:** lupo_actor_traits_idx_actor (actor_id)
- **INDEX:** lupo_actor_traits_idx_actor_key (actor_id, trait_key)
- **INDEX:** lupo_actor_traits_idx_trait_key (trait_key)
- **INDEX:** lupo_actor_traits_idx_federation (federation_node_id)
- **INDEX:** lupo_actor_traits_idx_is_deleted (is_deleted)

## Relationships

- **Logical references only (no DB FKs):** Identify referencing columns by name and usage in code; enforce integrity in application code.

## Doctrine notes

- No foreign keys, triggers, procedures, views, or computed columns. All logic in PHP.
- All timestamps are BIGINT UTC YYYYMMDDHHIISS and are written by application code.
