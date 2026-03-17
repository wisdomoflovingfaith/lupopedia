---
lupopedia.headers:
  lupopedia.version: "4.0.79"
  lupopedia.schema: "database_table"
  system_version: "4.0.79"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_unified_log.md"
  web_path: "[lupo_unified_log](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_unified_log)"
  last_modified_utc: "20260317"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "table_documentation"
  artifact_kind: "table"
  namespace: "analytics"
  purpose: "Unified logging table for system events and audit-relevant records across subsystems."
  tags: ["database", "table", "analytics"]

lupopedia.edges:
  comment: "Snapshot of edges for lupo_unified_log table doc at 4.0.79 (grounded by repo search; non-exhaustive)."
  meta: "php_hits=0 python_hits=0"
  outbound_edges:
    - { to: "database.table.lupo_unified_log", type: "DEFINES_SCHEMA_FOR", weight: 1.0 }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "schema_reference", weight: 1.0 }
    - { to: "(no_php_refs_found)", type: "USED_IN_PHP", weight: 0.0 }
    - { to: "(no_python_refs_found)", type: "USED_IN_PYTHON", weight: 0.0 }

lupopedia.footer:
  version: "4.0.79"
  last_verified: "20260317"
  last_verified_by: "cursor"
---
# file: lupo_unified_log ? web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_unified_log
# Table: lupo_unified_log

## Table Overview

- **Purpose:** Unified logging table for system events and audit-relevant records across subsystems.
- **Status:** Active (in install_new_lupopedia.sql)
- **Primary key:** `log_id`

## Where This Table Is Used

- This section is grounded by `USED_IN_PHP` / `USED_IN_PYTHON` edges in the header (populated by repo search).

## Column Documentation

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| log_id | bigint | No | ? | Primary key. |
| log_type | varchar(64) | No | ? |  |
| log_level | varchar(32) | No | 'info' |  |
| log_message | text | No | ? |  |
| log_context | json | No | ? |  |
| actor_id | bigint | Yes | NULL |  |
| channel_id | bigint | Yes | NULL |  |
| session_id | varchar(128) | Yes | NULL |  |
| ip_address | varchar(45) | Yes | NULL |  |
| user_agent | text | No | ? |  |
| created_ymdhis | bigint | No | ? |  |

## Indexes

- **PRIMARY KEY:** log_id
- **INDEX:** lupo_unified_log_idx_actor_id (actor_id)
- **INDEX:** lupo_unified_log_idx_channel_id (channel_id)
- **INDEX:** lupo_unified_log_idx_created_ymdhis (created_ymdhis)
- **INDEX:** lupo_unified_log_idx_log_level (log_level)
- **INDEX:** lupo_unified_log_idx_log_type (log_type)
- **INDEX:** lupo_unified_log_idx_session_id (session_id)
- **INDEX:** lupo_unified_log_idx_actor_log (actor_id, log_type)
- **INDEX:** lupo_unified_log_idx_channel_log (channel_id, log_type)
- **INDEX:** lupo_unified_log_idx_log_type_created (log_type, created_ymdhis)

## Relationships

- **Logical references only (no DB FKs):** Identify referencing columns by name and usage in code; enforce integrity in application code.

## Doctrine notes

- No foreign keys, triggers, procedures, views, or computed columns. All logic in PHP.
- All timestamps are BIGINT UTC YYYYMMDDHHIISS and are written by application code.
