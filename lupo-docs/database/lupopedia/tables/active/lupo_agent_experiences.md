---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "database_table"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_agent_experiences.md"
  system_version: "4.0.70"
  channel_id: 1
  actor_id: 102
  last_modified_utc: "20260312"
  artifact_type: "table_documentation"
  purpose: "Agent–star links (emotional/experience model): link_id, star_id, intensity, context"
  mood_rgb: "4169E1"
  traits: ["canonical", "agent", "cursor_domain", "v4.0.70"]
  tags: ["database", "agents", "experiences"]
  lupo_agent: "cursor"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-database/lupopedia/toon/lupo_agent_experiences.toon.json", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_agents.md", type: "references", weight: 0.8 }

lupopedia.footer:
  last_verified: "20260312"
  last_verified_by: "cursor"
---

# Table: lupo_agent_experiences

## Table Overview

- **Purpose:** Links agents to "stars" (emotional/experience model): link_id (PK), agent_id, star_id, intensity, context_id, observed_ymdhis, expressed_as_rgb. Used for agent experience/emotional state tracking.
- **Category:** Agent
- **Status:** Active
- **Version introduced:** 4.0.0

## Column Documentation

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| link_id | char(26) | No | — | Primary key (e.g. ULID). |
| agent_id | bigint | No | — | Agent (logical → lupo_agents). |
| star_id | char(26) | No | — | Star identifier. |
| intensity | decimal(3,2) | Yes | — | Intensity value. |
| context_id | bigint | Yes | — | Context reference. |
| observed_ymdhis | bigint | Yes | — | Observation timestamp. |
| expressed_as_rgb | char(6) | Yes | — | RGB color code. |

## Relationships

- **Logical references:** agent_id → lupo_agents.agent_id; star_id and context_id reference other domain tables (emotional/context).
- **Inbound:** Experience/emotional subsystem writes links.
- **Join patterns:** By agent_id, context_id, star_id.

## Usage Notes

- **Indexes:** agent_id, context_id, star_id.
- **Primary key:** link_id is char(26), not bigint; per TOON.
