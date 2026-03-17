---
lupopedia.headers:
  lupopedia.version: "4.0.79"
  lupopedia.schema: "database_table"
  system_version: "4.0.79"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_action_authorization.md"
  web_path: "[lupo_action_authorization](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_action_authorization)"
  last_modified_utc: "20260317"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "table_documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Authorization rules for action keys, mapping required trait keys and/or required role keys for channel-scoped actions."
  tags: ["database", "table", "core"]

lupopedia.edges:
  comment: "Snapshot of edges for lupo_action_authorization table doc at 4.0.79 (grounded by repo search; non-exhaustive)."
  meta: "php_hits=1 python_hits=1"
  outbound_edges:
    - { to: "database.table.lupo_action_authorization", type: "DEFINES_SCHEMA_FOR", weight: 1.0 }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-includes/classes/TraitEnforcer.php", type: "USED_IN_PHP", weight: 0.9 }
    - { to: "lupo-scripts/check_doc_schema_consistency.py", type: "USED_IN_PYTHON", weight: 0.5 }

lupopedia.footer:
  version: "4.0.79"
  last_verified: "20260317"
  last_verified_by: "cursor"
---
# file: lupo_action_authorization ? web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_action_authorization
# Table: lupo_action_authorization

## Table Overview

- **Purpose:** Authorization rules for action keys, mapping required trait keys and/or required role keys for channel-scoped actions.
- **Status:** Active (in install_new_lupopedia.sql)
- **Primary key:** `action_authorization_id`

## Where This Table Is Used

- This section is grounded by `USED_IN_PHP` / `USED_IN_PYTHON` edges in the header (populated by repo search).

## Column Documentation

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| action_authorization_id | bigint | No | ? | Primary key. |
| action_key | varchar(100) | No | ? |  |
| description | text | No | ? |  |
| required_trait_keys | text | Yes | NULL |  |
| required_capabilities | text | Yes | NULL |  |
| required_role_keys | text | Yes | NULL |  |
| requires_all_conditions | tinyint | No | 0 |  |
| created_ymdhis | bigint | No | 0 |  |
| created_by_actor_id | bigint | No | ? |  |

## Indexes

- **PRIMARY KEY:** action_authorization_id
- **INDEX:** lupo_action_authorization_idx_action (action_key)

## Relationships

- **Logical references only (no DB FKs):** Identify referencing columns by name and usage in code; enforce integrity in application code.

## Doctrine notes

- No foreign keys, triggers, procedures, views, or computed columns. All logic in PHP.
- All timestamps are BIGINT UTC YYYYMMDDHHIISS and are written by application code.
