---
lupopedia.headers:
  lupopedia.schema: database_table
  file_path_from_root: lupo-docs/database/lupopedia/tables/active/lupo_audit_log.md
  web_path: '[lupo_audit_log](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_audit_log)'
  last_modified_utc: '20260317'
  channel_id: 42
  actor_id: 102
  actor_name: cursor
  delegation_chain: cursor:root
  artifact_type: table_documentation
  artifact_kind: table
  namespace: governance
  purpose: Documentation for lupo_audit_log table - system-wide audit trail of sensitive
    or important actions
  tags:
  - database
  - table
  - governance
  when_updated: '20260324174654'
lupopedia.edges:
  comment: Snapshot of edges for lupo_audit_log table doc at 4.0.79 (grounded by repo
    search; non-exhaustive).
  meta: php_hits=1 python_hits=2
  outbound_edges:
  - to: database.table.lupo_audit_log
    type: DEFINES_SCHEMA_FOR
    weight: 1.0
  - to: lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql
    type: schema_reference
    weight: 1.0
  - to: lupo-includes/schema-config.php
    type: USED_IN_PHP
    weight: 0.9
  - to: analyze_unused_tables.py
    type: USED_IN_PYTHON
    weight: 0.5
  - to: lupo-scripts/wolfie_orms.py
    type: USED_IN_PYTHON
    weight: 0.5
lupopedia.footer:
  last_verified: '20260317000000'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
---
# file: lupo_audit_log ? web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_audit_log
# Table: lupo_audit_log

## Table Overview

- **Purpose:** System-wide audit trail for sensitive or important actions. Each row records an event (event_type) against an entity (entity_type, entity_id) in a channel (channel_id), with optional table/row context (table_name, table_id) and a JSON payload. Used for administrative action tracking, moderation review, security traceability, and change accountability.
- **Category:** Audit / Governance
- **Status:** Active (in install_new_lupopedia.sql)
- **Version introduced:** 4.0.x

## Where This Table Is Used

- **Administrative action tracking:** Admin UI and backend services write audit rows when configuration, content, or user/actor data is changed so that who did what and when can be reviewed.
- **Moderation and governance review:** Moderation actions (e.g. bans, overrides, role changes) can be logged here for later review and compliance.
- **Security review and traceability:** Security-sensitive operations (e.g. permission changes, auth-related updates) are audited to support incident investigation and access reviews.
- **Change accountability:** Any high-value or irreversible action can be logged with entity_type, entity_id, event_type, and payload_json so that changes are attributable and auditable.
- **Investigation and diagnostics:** When debugging disputes or investigating incidents, auditors query by entity, event_type, channel_id, or time range to reconstruct what happened.

## Distinction from other log-like tables

- **lupo_auth_audit_log:** Auth-specific (login, logout, token, failure); use for authentication events. **lupo_audit_log** is general-purpose and entity/event-scoped.
- **lupo_unified_log:** Consolidated application/runtime log (log_type, log_level, log_message); used for operational and diagnostic logging. **lupo_audit_log** is for business-level and security-relevant actions that need an audit trail.
- **lupo_system_logs** (if present): Typically operational/diagnostic; **lupo_audit_log** is for accountability and governance.

## Column Documentation

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| audit_log_id | bigint | No | — | Primary key. Reserved-ID doctrine: application supplies explicit ID; not AUTO_INCREMENT. |
| channel_id | bigint | No | — | Channel scope for the event. Logical reference to lupo_channels. |
| entity_type | varchar(32) | No | — | Type of entity affected (e.g. content, actor, channel). |
| entity_id | bigint | No | — | ID of the affected entity. |
| event_type | varchar(100) | No | — | Event identifier (e.g. update, delete, permission_change). |
| table_name | varchar(100) | Yes | — | Optional table name for table/row context. |
| table_id | bigint | Yes | — | Optional row identifier. |
| payload_json | text | Yes | — | Optional JSON payload (before/after, details). |
| created_ymdhis | bigint | No | 0 | Creation timestamp (BIGINT UTC). |
| updated_ymdhis | bigint | No | — | Last update timestamp (BIGINT UTC). |
| is_deleted | tinyint | No | 0 | Soft delete flag. |
| deleted_ymdhis | bigint | Yes | — | Soft delete timestamp. |

## Indexes

- **PRIMARY KEY:** audit_log_id
- **INDEX:** lupo_audit_log_idx_entity (entity_type, entity_id), lupo_audit_log_idx_event (event_type), lupo_audit_log_idx_table (table_name, table_id)

## Relationships

- **Logical references (no DB FKs):** channel_id → lupo_channels.channel_id. entity_type/entity_id and table_name/table_id identify the affected entity or row; application code interprets them. created_by or actor context is not in this table; add to payload_json if needed.

## Doctrine Notes

- **No foreign keys.** All referential integrity in application code.
- **Timestamps:** created_ymdhis and updated_ymdhis are BIGINT UTC YYYYMMDDHHIISS; set in PHP only.
- **Soft delete:** Filter by is_deleted = 0 unless querying deleted audit rows.
- **Reserved ID:** audit_log_id is not AUTO_INCREMENT; application must supply explicit value.
