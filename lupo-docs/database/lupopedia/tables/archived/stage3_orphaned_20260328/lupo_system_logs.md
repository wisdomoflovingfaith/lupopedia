---
lupopedia.headers:
  lupopedia.schema: database_table
  file_path_from_root: lupo-docs/database/lupopedia/tables/active/lupo_system_logs.md
  web_path: '[lupo_system_logs](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_system_logs)'
  last_modified_utc: '20260317'
  channel_id: 42
  actor_id: 102
  actor_name: cursor
  delegation_chain: cursor:root
  artifact_type: table_documentation
  artifact_kind: table
  namespace: governance
  purpose: Documentation for lupo_system_logs table - operational and application-level
    logging for the Lupopedia system
  tags:
  - database
  - table
  - governance
  when_updated: '20260324174654'
lupopedia.edges:
  comment: Snapshot of edges for lupo_system_logs table doc at 4.0.79 (grounded by
    repo search; non-exhaustive).
  meta: php_hits=0 python_hits=1
  outbound_edges:
  - to: database.table.lupo_system_logs
    type: DEFINES_SCHEMA_FOR
    weight: 1.0
  - to: lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql
    type: schema_reference
    weight: 1.0
  - to: lupo-scripts/wolfie_orms.py
    type: USED_IN_PYTHON
    weight: 0.5
  - to: (no_php_refs_found)
    type: USED_IN_PHP
    weight: 0.0
lupopedia.footer:`n  approved_for_release: "4.1.0"`n  approval_status: "approved"`n  approved_by_actor_id: 1`n  approved_utc: 20260326192115`n  last_verified: '20260317000000'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
---
# file: lupo_system_logs ? web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_system_logs
# Table: lupo_system_logs

## Schema source note

**This table is not present in `install_new_lupopedia.sql` as of 4.0.78.** The schema below is from existing table documentation (and TOON-derived content where applicable). The current install provides **lupo_unified_log** (log_id, log_type, log_level, log_message, log_context, actor_id, channel_id, session_id, ip_address, user_agent, created_ymdhis) for consolidated application/runtime logging. lupo_system_logs represents an alternate or supplemental system-log design when present (e.g. event_type, severity, actor_slug, context_json, recursion_latency, anomaly fields).

## Table Overview

- **Purpose:** Stores operational and application-level logging data for the Lupopedia system. Used for runtime diagnostics, troubleshooting, background task/worker monitoring, incident review, and operational observability. Distinct from audit logs (accountability) and auth audit logs (auth events).
- **Category:** Logging / Operations
- **Status:** Documented; not in current install SQL (see schema source note above).

## Where This Table Is Used

- **Runtime diagnostics:** Application code and services write event_type, severity, message, and context_json for request handling, errors, and internal state transitions.
- **Application troubleshooting:** Support and developers query by event_type, severity, created_ymdhis, or actor_slug to trace failures and unexpected behavior.
- **Background task and worker monitoring:** Workers and cron jobs can log start/complete/failure with context for job history and retry analysis.
- **Incident review:** Post-incident, logs are scanned by time range and severity to reconstruct sequence of events and root cause.
- **Operational observability:** Aggregations by event_type or severity support health dashboards and alerting (when the table is in use).
- **Distinction from other logs:** **lupo_audit_log** is for business-level and security-relevant actions (who did what); **lupo_auth_audit_log** is for authentication events. **lupo_unified_log** (in install) is the current consolidated application log. lupo_system_logs, when present, can hold additional operational/diagnostic fields (e.g. recursion_depth, observation_latency_ms, temporal_anomaly_score) for debugging and maintenance.

## Column Documentation

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| log_id | bigint | No | â€” | Primary key. |
| event_type | varchar(64) | No | â€” | Event identifier. |
| severity | varchar(16) | No | 'info' | Severity (e.g. info, warning, error). |
| actor_slug | varchar(64) | Yes | â€” | Actor slug for attribution. |
| message | text | No | â€” | Log message. |
| context_json | json | Yes | â€” | Optional JSON context. |
| created_ymdhis | bigint | No | 0 | Created timestamp (BIGINT UTC). |
| is_deleted | tinyint | No | 0 | Soft delete flag. |
| deleted_ymdhis | bigint | Yes | â€” | Soft delete timestamp. |
| recursion_depth | tinyint | Yes | 1 | Recursion/depth indicator. |
| observation_latency_ms | int | Yes | â€” | Observation latency in ms. |
| temporal_anomaly_score | decimal(3,2) | Yes | â€” | Optional anomaly score. |

## Indexes

- **PRIMARY KEY:** log_id
- **Indexes (from existing documentation):** lupo_system_logs_idx_actor_slug, lupo_system_logs_idx_created_ymdhis, lupo_system_logs_idx_event_type, lupo_system_logs_idx_is_deleted, lupo_system_logs_idx_severity. Confirm against DDL when table is added to install/future_features.

## Relationships

- **Logical references (no DB FKs):** actor_slug ties to actor identity; no channel_id/session_id in this schemaâ€”use context_json or lupo_unified_log when cross-referencing channel/session. All integrity in application code.

## Doctrine Notes

- **No foreign keys.** All referential integrity in application code.
- **Timestamps:** created_ymdhis, deleted_ymdhis are BIGINT UTC; set in PHP only.
- **Soft delete:** Filter by is_deleted = 0 unless querying deleted logs.
- **Schema source:** Table not in install_new_lupopedia.sql as of 4.0.78; columns and indexes from existing documentation. For consolidated application logging in the current install, use lupo_unified_log. Align this doc to install or future_features DDL when lupo_system_logs is introduced.

