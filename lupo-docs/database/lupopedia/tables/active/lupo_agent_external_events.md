---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "database_table"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_agent_external_events.md"
  system_version: "4.0.70"
  channel_id: 1
  actor_id: 102
  last_modified_utc: "20260312"
  artifact_type: "table_documentation"
  purpose: "External events ingested for agents (source_system, event_type, payload)"
  mood_rgb: "4169E1"
  traits: ["canonical", "agent", "cursor_domain", "v4.0.70"]
  tags: ["database", "agents", "events"]
  lupo_agent: "cursor"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/toons/lupo_agent_external_events.toon.json", type: "schema_reference", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260312"
  last_verified_by: "cursor"
---

# Table: lupo_agent_external_events

## Table Overview

- **Purpose:** Log of external events ingested for agents: agent_name, source_system, event_type, event_payload_json, created_ymdhis. No direct agent_id FK; agent identified by name.
- **Category:** Agent / Events
- **Status:** Active
- **Version introduced:** 4.0.0

## Column Documentation

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| external_event_id | bigint | No | — | Primary key. |
| agent_name | varchar(255) | No | — | Agent name. |
| source_system | varchar(255) | No | — | Source system identifier. |
| event_type | varchar(50) | No | — | Event type. |
| event_payload_json | json | Yes | — | Event payload. |
| created_ymdhis | bigint | No | 0 | Event timestamp. |

## Relationships

- **Logical references:** agent_name may resolve to lupo_agents.agent_name or agent_key (application-level).
- **Inbound:** Event ingestion pipeline.
- **Join patterns:** No indexes in TOON; consider adding index on (agent_name, created_ymdhis) or event_type if needed.

## Usage Notes

- **Indexes:** None in current TOON.
- **Timestamps:** BIGINT YYYYMMDDHHIISS UTC.
