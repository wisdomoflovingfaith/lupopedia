---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "database_table"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_agent_faucets.md"
  system_version: "4.0.70"
  channel_id: 1
  actor_id: 102
  last_modified_utc: "20260312"
  artifact_type: "table_documentation"
  purpose: "Per-actor faucets (IDE/surface): slug, model, prompts, style"
  mood_rgb: "4169E1"
  traits: ["canonical", "agent", "faucet", "cursor_domain", "v4.0.70"]
  tags: ["database", "agents", "faucets", "ide"]
  lupo_agent: "cursor"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/toons/lupo_agent_faucets.toon.json", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_agents.md", type: "references", weight: 0.8 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_agent_faucet_credentials.md", type: "references", weight: 0.8 }

lupopedia.footer:
  last_verified: "20260312"
  last_verified_by: "cursor"
---

# Table: lupo_agent_faucets

## Table Overview

- **Purpose:** Faucets (execution surfaces, e.g. IDE agents) per actor: name, slug, description, model_name, provider, temperature, system_prompt, capabilities_json, is_default, domain_id. One actor can have multiple faucets (e.g. Cursor, Windsurf).
- **Category:** Agent / Identity
- **Status:** Active
- **Version introduced:** 4.0.0

## Column Documentation

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| agent_faucet_id | bigint | No | — | Primary key. |
| actor_id | bigint | No | — | Owning actor (logical → lupo_actors). |
| name | varchar(100) | No | — | Faucet name. |
| alias_name | varchar(100) | Yes | — | Alias. |
| slug | varchar(100) | No | — | URL-friendly slug. |
| description | text | Yes | — | Description. |
| style_preset | varchar(100) | Yes | — | Style preset. |
| model_name | varchar(100) | Yes | — | Model name. |
| provider | varchar(50) | Yes | — | Provider. |
| temperature | float | Yes | — | Temperature. |
| top_p | float | Yes | — | Top_p. |
| max_tokens | int | Yes | — | Max tokens. |
| presence_penalty | float | Yes | — | Presence penalty. |
| frequency_penalty | float | Yes | — | Frequency penalty. |
| system_prompt | text | Yes | — | System prompt. |
| safety_json | json | Yes | — | Safety config. |
| response_format | varchar(50) | Yes | — | Response format. |
| capabilities_json | text | Yes | — | Capabilities JSON. |
| is_default | tinyint | No | 0 | Default faucet flag. |
| domain_id | bigint | No | 1 | Domain. |
| created_ymdhis | bigint | No | 0 | Creation. |
| updated_ymdhis | bigint | No | — | Last update. |
| deleted_ymdhis | bigint | Yes | — | Soft-delete timestamp. |

## Relationships

- **Logical references:** actor_id → lupo_actors.agent_faucet_id referenced by lupo_agent_faucet_credentials.faucet_id, lupo_agent_tool_calls.faucet_id.
- **Inbound:** Faucet resolution, LUPOPEDIA HEADERS agent_name_identity, IDE agent registry.
- **Join patterns:** By actor_id, is_default, domain_id, slug.

## Usage Notes

- **Indexes:** actor_id, is_default, domain_id, slug.
- **Timestamps:** BIGINT YYYYMMDDHHIISS UTC.
