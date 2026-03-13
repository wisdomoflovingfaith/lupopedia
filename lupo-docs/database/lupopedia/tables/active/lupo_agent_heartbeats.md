---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "database_table"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_agent_heartbeats.md"
  system_version: "4.0.70"
  channel_id: 1
  actor_id: 102
  last_modified_utc: "20260312"
  artifact_type: "table_documentation"
  purpose: "Agent liveness: slug, status, last_heartbeat_ymdhis"
  mood_rgb: "4169E1"
  traits: ["canonical", "agent", "cursor_domain", "v4.0.70"]
  tags: ["database", "agents", "heartbeats"]
  lupo_agent: "cursor"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/toons/lupo_agent_heartbeats.toon.json", type: "schema_reference", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260312"
  last_verified_by: "cursor"
---

# Table: lupo_agent_heartbeats

## Table Overview

- **Purpose:** Tracks agent liveness by agent_slug: status, last_heartbeat_ymdhis. Soft-delete supported. Used for health checks and presence.
- **Category:** Agent / Identity
- **Status:** Active
- **Version introduced:** 4.0.0

## Column Documentation

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| heartbeat_id | bigint | No | — | Primary key. |
| agent_slug | varchar(64) | No | — | Agent slug (e.g. cursor, windsurf). |
| status | varchar(32) | No | 'unknown' | Status (e.g. active, unknown). |
| last_heartbeat_ymdhis | bigint | No | — | Last heartbeat timestamp. |
| created_ymdhis | bigint | No | 0 | Row creation. |
| is_deleted | tinyint | No | 0 | Soft-delete flag. |
| deleted_ymdhis | bigint | Yes | — | Soft-delete timestamp. |

## Relationships

- **Logical references:** agent_slug may align with lupo_agent_faucets.slug or agent registry.
- **Inbound:** Heartbeat writer updates last_heartbeat_ymdhis.
- **Join patterns:** By agent_slug, created_ymdhis, is_deleted, last_heartbeat_ymdhis.

## Usage Notes

- **Indexes:** agent_slug, created_ymdhis, is_deleted, last_heartbeat_ymdhis.
- **Timestamps:** BIGINT YYYYMMDDHHIISS UTC.
