---
lupopedia.headers:
  lupopedia.schema: database_table
  file_path_from_root: lupo-docs/database/lupopedia/tables/active/lupo_session_events.md
  namespace: auth
  channel_id: 1
  actor_id: 1003
  last_modified_utc: '20260313'
  artifact_type: table_documentation
  purpose: Per-session event log (tab, world, event type)
  mood_rgb: 4169E1
  traits:
  - canonical
  - session
  - antigravity_rotation
  - v4.0.73
  tags:
  - database
  - sessions
  - events
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
  - to: lupo-database/lupopedia/toon/lupo_session_events.toon.json
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

# Table: lupo_session_events

## Table Overview

- **Purpose:** Event log per session: event type, tab, world context, and optional JSON payload. Used for session analytics and audit trail.
- **Category:** Session / Audit
- **Status:** Active
- **Version introduced:** 4.0.0

## Column Documentation

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| session_event_id | bigint | No | — | Primary key. |
| session_id | varchar(255) | No | — | Session reference (logical → lupo_sessions.session_id). |
| actor_id | bigint | Yes | — | Actor at event time. |
| tab_id | varchar(255) | Yes | — | Tab identifier. |
| world_id | bigint | Yes | — | World context id. |
| world_key | varchar(255) | Yes | — | World key. |
| world_type | varchar(50) | Yes | — | World type. |
| event_type | varchar(100) | No | — | Event type code. |
| event_data | json | Yes | — | Optional event payload. |
| created_ymdhis | bigint | No | 0 | Event timestamp. |

## Relationships

- **Logical references:** session_id → lupo_sessions.session_id; actor_id → lupo_actors.actor_id.
- **Inbound:** Session and analytics code write events.
- **Join patterns:** By session_id, actor_id, event_type, (session_id, event_type), tab_id, world_id, created_ymdhis.

## Usage Notes

- **Indexes:** session_id, actor_id, event_type, (session_id, event_type), tab_id, world_id, created_ymdhis.
- **Timestamps:** BIGINT YYYYMMDDHHIISS UTC.
