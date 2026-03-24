---
lupopedia.headers:
  lupopedia.schema: database_table
  file_path_from_root: lupo-docs/database/lupopedia/tables/active/lupo_agent_dependencies.md
  channel_id: 1
  actor_id: 102
  last_modified_utc: '20260312'
  artifact_type: table_documentation
  purpose: Agent-to-agent dependency graph (depends_on_agent_id, is_required)
  mood_rgb: 4169E1
  traits:
  - canonical
  - agent
  - cursor_domain
  - v4.0.70
  tags:
  - database
  - agents
  - dependencies
  lupo_agent: cursor
  when_updated: '20260324174654'
lupopedia.edges:
  outbound_edges:
  - to: lupo-database/lupopedia/toon/lupo_agent_dependencies.toon.json
    type: schema_reference
    weight: 1.0
  - to: lupo-docs/database/lupopedia/tables/active/lupo_agents.md
    type: references
    weight: 0.9
lupopedia.footer:
  last_verified: '20260312000000'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
---

# Table: lupo_agent_dependencies

## Table Overview

- **Purpose:** Defines agent dependency graph: agent_id depends on depends_on_agent_id (and depends_on_agent_code), with is_required and optional notes.
- **Category:** Agent
- **Status:** Active
- **Version introduced:** 4.0.0

## Column Documentation

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| agent_dependency_id | bigint | No | — | Primary key. |
| agent_id | bigint | No | — | Agent (logical → lupo_agents.agent_id). |
| depends_on_agent_id | bigint | No | — | Dependent agent id. |
| depends_on_agent_code | varchar(50) | No | — | Dependent agent code/key. |
| is_required | tinyint | No | 1 | Required dependency flag. |
| notes | text | Yes | — | Notes. |
| created_ymdhis | bigint | No | 0 | Creation. |
| updated_ymdhis | bigint | Yes | — | Last update. |

## Relationships

- **Logical references:** agent_id, depends_on_agent_id → lupo_agents.agent_id.
- **Inbound:** Agent orchestration and dependency resolution.
- **Join patterns:** By agent_id, depends_on_agent_id.

## Usage Notes

- **Indexes:** agent_id, depends_on_agent_id.
- **Timestamps:** BIGINT YYYYMMDDHHIISS UTC.
