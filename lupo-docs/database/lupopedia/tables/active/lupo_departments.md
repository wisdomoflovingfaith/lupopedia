---
lupopedia.headers:
  lupopedia.schema: database_table
  file_path_from_root: lupo-docs/database/lupopedia/tables/active/lupo_departments.md
  web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_departments
  last_modified_utc: '20260324'
  channel_id: 42
  actor_id: 108
  actor_name: junie
  faucet_name: jetbrains
  delegation_chain: junie:root
  artifact_type: table_documentation
  artifact_kind: table
  namespace: governance
  purpose: 'Department organization: grouping Actors and Users for orchestration (v4.0.86)'
  tags:
  - database
  - table
  - governance
  - identity
  - v4.0.86
  when_updated: '20260324174654'
lupopedia.edges:
  comment: Snapshot of edges for lupo_departments table doc at 4.0.79 (grounded by
    repo search; non-exhaustive).
  meta: php_hits=8 python_hits=1
  outbound_edges:
  - to: database.table.lupo_departments
    type: DEFINES_SCHEMA_FOR
    weight: 1.0
  - to: lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql
    type: schema_reference
    weight: 1.0
  - to: check_db_state.php
    type: USED_IN_PHP
    weight: 0.6
  - to: install.php
    type: USED_IN_PHP
    weight: 0.6
  - to: livehelp_js.php
    type: USED_IN_PHP
    weight: 0.6
  - to: lupo-database/lupopedia/content/lupo-app/Services/CraftySyntax/LegacyIsFlushDetection.php
    type: USED_IN_PHP
    weight: 0.6
  - to: lupo-database/lupopedia/content/lupo-app/Services/CraftySyntax/WorldGraphHelper.php
    type: USED_IN_PHP
    weight: 0.6
  - to: lupo-includes/classes/AdminDepartmentsHandler.php
    type: USED_IN_PHP
    weight: 0.9
  - to: lupo-includes/modules/crafty_syntax/choosedepartment.php
    type: USED_IN_PHP
    weight: 0.9
  - to: lupo-includes/modules/crafty_syntax/livehelp.php
    type: USED_IN_PHP
    weight: 0.9
  - to: analyze_unused_tables.py
    type: USED_IN_PYTHON
    weight: 0.5
lupopedia.footer:
  last_verified: '20260324000000'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
---
# file: lupo_departments — delegation: junie:root — web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_departments
# Table: lupo_departments

## Table Overview

- **Purpose:** Departments are the organizational units that group **Actors** and **Users**. In the **Unified Identity Model (v4.0.86)**, they define the context for orchestration (e.g., "Root", "Dev", "QA"). They organize channels, actors, and permissions per federation node.
- **Category:** Governance / Identity
- **Status:** Active
- **Version introduced:** 4.0.0

## Where This Table Is Used

- **Channel organization:** lupo_channels.department_id and lupo_channel_departments link channels to departments; routing and UI group by department.
- **Operator assignment:** default_actor_id and lupo_department_roles assign actors to departments; used for workload and permission context.
- **Federation scoping:** federation_node_id scopes departments per node; multi-node deployments have separate department sets.
- **Collections and content:** lupo_collections and related tables can scope by department_id for department-specific content and tabs.

## Column Documentation

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| department_id | bigint | No | — | Primary key. |
| federation_node_id | bigint | No | — | Federation node this department belongs to. |
| name | varchar(64) | No | — | Department name. |
| description | text | Yes | NULL | Description. |
| department_type | varchar(32) | No | 'general' | Type (e.g. general). |
| default_actor_id | bigint | No | 1 | Default actor for the department. |
| settings_json | json | Yes | NULL | Optional JSON settings. |
| created_ymdhis | bigint | No | 0 | Creation timestamp (BIGINT UTC). |
| updated_ymdhis | bigint | No | — | Last update timestamp (BIGINT UTC). |
| is_deleted | tinyint | No | 0 | Soft delete flag. |
| deleted_ymdhis | bigint | Yes | NULL | Soft delete timestamp. |

## Indexes

- **PRIMARY KEY:** department_id
- **INDEX:** lupo_departments_idx_name (name), lupo_departments_idx_type (department_type), lupo_departments_idx_federation_node (federation_node_id)

## Relationships

- **Logical references (no DB FKs):** federation_node_id → lupo_federation_nodes; default_actor_id → lupo_actors. lupo_channel_departments links channels to departments (many-to-many); lupo_department_roles links actors to departments; lupo_channels.department_id and lupo_collections.department_id reference this table.

## Doctrine notes

- No database foreign keys; referential integrity enforced in application code.
- All timestamps BIGINT UTC YYYYMMDDHHIISS.
- Soft delete: filter `is_deleted = 0` unless querying deleted rows.
