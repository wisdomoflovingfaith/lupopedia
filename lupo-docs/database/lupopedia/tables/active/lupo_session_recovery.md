---
lupopedia.headers:
  lupopedia.schema: database_table
  file_path_from_root: lupo-docs/database/lupopedia/tables/active/lupo_session_recovery.md
  namespace: auth
  channel_id: 1
  actor_id: 1003
  last_modified_utc: '20260313'
  artifact_type: table_documentation
  purpose: Session recovery state and attempt tracking
  mood_rgb: 4169E1
  traits:
  - canonical
  - session
  - antigravity_rotation
  - v4.0.73
  tags:
  - database
  - sessions
  - recovery
  lupo_agent: antigravity
  when_updated: '20260324174654'
lupopedia.edges:
  comment: Snapshot of files edited during 4.0.73 finalization and initialization
    thread by ANTIGRAVITY IDE Agent. Edges reflect discovered relationships between
    database tables and PHP/Python codebase entities. Values should be verified against
    live database schemas/queries for the most current semantic graph state.
  meta: "Thread: Finalize 4.0.72 \u2192 Push to GitHub \u2192 Initialize 4.0.73 \u2192\
    \ Migrate Tasks \u2192 Validate Upgrade Path"
  outbound_edges:
  - to: lupo-database/lupopedia/toon/lupo_session_recovery.toon.json
    type: schema_reference
    weight: 1.0
  - to: lupo-docs/database/lupopedia/tables/active/lupo_sessions.md
    type: references
    weight: 0.9
lupopedia.engagement:
  comment: Snapshot of files edited during 4.0.73 finalization and initialization
    thread by ANTIGRAVITY IDE Agent. Engagement metrics track edit frequency and importance
    of each file in the version transition process.
  meta: "Thread: Finalize 4.0.72 \u2192 Push to GitHub \u2192 Initialize 4.0.73 \u2192\
    \ Migrate Tasks \u2192 Validate Upgrade Path"
  views: 0
lupopedia.footer:
  last_verified: '20260313000000'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
---

# Table: lupo_session_recovery

## Table Overview

- **Purpose:** Stores session recovery state: session_data, state_snapshot, context_data, and recovery attempt counts. Used to restore or resume sessions after disconnect.
- **Category:** Session / Recovery
- **Status:** Active
- **Version introduced:** 4.0.0

## Column Documentation

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| recovery_id | bigint | No | — | Primary key. |
| actor_id | bigint | No | — | Actor (logical → lupo_actors.actor_id). |
| session_id | varchar(255) | No | — | Session (logical → lupo_sessions.session_id). |
| session_data | json | Yes | — | Serialized session data. |
| state_snapshot | json | Yes | — | State snapshot. |
| context_data | json | Yes | — | Context for recovery. |
| last_activity_ymdhis | bigint | Yes | 0 | Last activity timestamp. |
| recovery_attempts | int | Yes | 0 | Number of recovery attempts. |
| max_recovery_attempts | int | Yes | 3 | Max attempts allowed. |
| created_ymdhis | bigint | No | 0 | Creation timestamp. |
| updated_ymdhis | bigint | Yes | — | Last update. |
| is_deleted | tinyint | No | 0 | Soft-delete flag. |
| deleted_ymdhis | bigint | Yes | — | Soft-delete timestamp. |

## Relationships

- **Logical references:** actor_id → lupo_actors; session_id → lupo_sessions.session_id.
- **Inbound:** Session recovery logic reads/writes this table.
- **Join patterns:** By actor_id, session_id, is_deleted, last_activity_ymdhis.

## Usage Notes

- **Indexes:** actor_id, is_deleted, last_activity_ymdhis, session_id.
- **Timestamps:** BIGINT YYYYMMDDHHIISS UTC.
