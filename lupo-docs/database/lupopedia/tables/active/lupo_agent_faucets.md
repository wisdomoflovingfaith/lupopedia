---
lupopedia.headers:
  lupopedia.version: "4.0.79"
  lupopedia.schema: "database_table"
  system_version: "4.0.79"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_agent_faucets.md"
  web_path: "[lupo_agent_faucets](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_agent_faucets)"
  last_modified_utc: "20260317"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "table_documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Per-actor faucets (IDE/surface): slug, model, prompts, style"
  tags: ["database", "table", "core"]

lupopedia.edges:
  comment: "Snapshot of edges for lupo_agent_faucets table doc at 4.0.79 (grounded by repo search; non-exhaustive)."
  meta: "php_hits=3 python_hits=2"
  outbound_edges:
    - { to: "database.table.lupo_agent_faucets", type: "DEFINES_SCHEMA_FOR", weight: 1.0 }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-bin/faucet_integrity_audit.php", type: "USED_IN_PHP", weight: 0.6 }
    - { to: "lupo-bin/faucet_loader.php", type: "USED_IN_PHP", weight: 0.6 }
    - { to: "lupo-bin/validate_faucets.php", type: "USED_IN_PHP", weight: 0.6 }
    - { to: "analyze_unused_tables.py", type: "USED_IN_PYTHON", weight: 0.5 }
    - { to: "lupo-scripts/wolfie_orms.py", type: "USED_IN_PYTHON", weight: 0.5 }

lupopedia.footer:
  version: "4.0.79"
  last_verified: "20260317"
  last_verified_by: "cursor"
---
# file: lupo_agent_faucets ? web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_agent_faucets
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
