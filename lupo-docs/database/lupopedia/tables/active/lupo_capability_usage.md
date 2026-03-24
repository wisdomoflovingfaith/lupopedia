---
lupopedia.headers:
  lupopedia.schema: database_table
  file_path_from_root: lupo-docs/database/lupopedia/tables/active/lupo_capability_usage.md
  channel_id: 1
  actor_id: 102
  last_modified_utc: '20260312'
  artifact_type: table_documentation
  purpose: Per-actor capability usage metrics (count, success rate, latency)
  mood_rgb: 4169E1
  traits:
  - canonical
  - acl
  - cursor_domain
  - v4.0.70
  tags:
  - database
  - capabilities
  - usage
  - metrics
  lupo_agent: cursor
  when_updated: '20260324174654'
lupopedia.edges:
  outbound_edges:
  - to: lupo-database/lupopedia/toon/lupo_capability_usage.toon.json
    type: schema_reference
    weight: 1.0
  - to: lupo-docs/database/lupopedia/tables/active/lupo_actors.md
    type: references
    weight: 0.9
lupopedia.footer:
  last_verified: '20260312000000'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
---

# Table: lupo_capability_usage

## Table Overview

- **Purpose:** Tracks per-actor, per-capability usage: usage_count, success_rate, avg_response_time_ms, last_used_ymdhis, and optional performance_metrics JSON. Supports ACL and quota enforcement.
- **Category:** Access control / Capabilities / Metrics
- **Status:** Active
- **Version introduced:** 4.0.0

## Column Documentation

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| usage_id | bigint | No | — | Primary key. |
| actor_id | bigint | No | — | Actor (logical → lupo_actors.actor_id). |
| capability | varchar(100) | No | — | Capability name/code. |
| usage_count | bigint | Yes | 0 | Usage count. |
| success_rate | float | Yes | 1 | Success rate 0–1. |
| avg_response_time_ms | int | Yes | 0 | Average response time in ms. |
| last_used_ymdhis | bigint | Yes | 0 | Last use timestamp. |
| performance_metrics | json | Yes | — | Optional extended metrics. |
| created_ymdhis | bigint | No | 0 | Row creation. |
| updated_ymdhis | bigint | Yes | — | Last update. |
| is_deleted | tinyint | No | 0 | Soft-delete flag. |
| deleted_ymdhis | bigint | Yes | — | Soft-delete timestamp. |

## Relationships

- **Logical references:** actor_id → lupo_actors.actor_id. Capability names may align with a capability registry (no FK).
- **Inbound:** Capability checks and usage recording.
- **Join patterns:** By (actor_id, capability), capability, is_deleted, last_used_ymdhis.

## Usage Notes

- **Indexes:** (actor_id, capability), capability, is_deleted, last_used_ymdhis.
- **Timestamps:** BIGINT YYYYMMDDHHIISS UTC.
- **Overlap:** If capability definitions and permission policy are considered governance, lupo_permissions (KIRO) may be the authority; this table is usage/telemetry. Flagged for KIRO in handoff.
