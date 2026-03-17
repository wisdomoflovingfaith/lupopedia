---
lupopedia.headers:
  lupopedia.version: "4.0.79"
  lupopedia.schema: "database_table"
  system_version: "4.0.79"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_agents.md"
  web_path: "[lupo_agents](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_agents)"
  last_modified_utc: "20260317"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "table_documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "AI agent registry: key, name, model, provider, prompts, and Kapu/governance fields"
  tags: ["database", "table", "core"]

lupopedia.edges:
  comment: "Snapshot of edges for lupo_agents table doc at 4.0.79 (grounded by repo search; non-exhaustive)."
  meta: "php_hits=5 python_hits=4"
  outbound_edges:
    - { to: "database.table.lupo_agents", type: "DEFINES_SCHEMA_FOR", weight: 1.0 }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "schema_reference", weight: 1.0 }
    - { to: "install_wizard_classes.php", type: "USED_IN_PHP", weight: 0.6 }
    - { to: "lupo-scripts/audit_schema_doctrine.php", type: "USED_IN_PHP", weight: 0.7 }
    - { to: "lupo-scripts/migrate_filesystem_to_db.php", type: "USED_IN_PHP", weight: 0.7 }
    - { to: "lupo-scripts/verify_architecture_files.php", type: "USED_IN_PHP", weight: 0.7 }
    - { to: "lupo-scripts/verify_grounded_architecture.php", type: "USED_IN_PHP", weight: 0.7 }
    - { to: "analyze_unused_tables.py", type: "USED_IN_PYTHON", weight: 0.5 }
    - { to: "lupo-scripts/audit_schema_doctrine.py", type: "USED_IN_PYTHON", weight: 0.5 }
    - { to: "lupo-scripts/migrate_filesystem_to_db.py", type: "USED_IN_PYTHON", weight: 0.5 }
    - { to: "lupo-scripts/wolfie_orms.py", type: "USED_IN_PYTHON", weight: 0.5 }

lupopedia.footer:
  version: "4.0.79"
  last_verified: "20260317"
  last_verified_by: "cursor"
---
# file: lupo_agents ? web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_agents
# Table: lupo_agents

## Table Overview

- **Purpose:** Central registry for AI agents: agent_key, agent_name, archetype, model_name, provider, system_prompt, and LLM/API parameters. Includes Kapu-related fields (kapu_active, kapu_until, kapu_reason, etc.) and optional metrics (avg_response_time_ms, total_tokens_processed, success_rate, cost).
- **Category:** Agent / Identity / Security layer
- **Status:** Active
- **Version introduced:** 4.0.0

## Column Documentation

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| agent_id | bigint | No | — | Primary key. |
| agent_key | varchar(100) | No | — | Unique agent identifier. |
| agent_name | varchar(150) | No | — | Display name. |
| archetype | varchar(150) | Yes | — | Agent archetype. |
| description | text | Yes | — | Description. |
| version | varchar(50) | Yes | '1.0' | Version string. |
| model_name | varchar(100) | Yes | — | LLM model name. |
| is_global_authority | tinyint | No | 0 | Global authority flag. |
| is_internal_only | tinyint | No | 0 | Internal-only flag. |
| created_ymdhis | bigint | No | 0 | Creation timestamp. |
| updated_ymdhis | bigint | Yes | — | Last update. |
| is_deleted | tinyint | No | 0 | Soft-delete flag. |
| deleted_ymdhis | bigint | Yes | — | Soft-delete timestamp. |
| avg_response_time_ms | int | Yes | 0 | Avg response time. |
| total_tokens_processed | bigint | Yes | 0 | Total tokens. |
| success_rate | float | Yes | 1 | Success rate. |
| cost_per_1k_tokens | decimal(10,4) | Yes | 0 | Cost per 1k tokens. |
| temperature | float | Yes | 0.7 | LLM temperature. |
| top_p | float | Yes | 1 | LLM top_p. |
| max_tokens | int | Yes | 2048 | Max tokens. |
| presence_penalty | float | Yes | 0 | Presence penalty. |
| frequency_penalty | float | Yes | 0 | Frequency penalty. |
| system_prompt | text | Yes | — | System prompt. |
| provider | varchar(50) | Yes | 'openai' | Provider name. |
| api_key_id | bigint | Yes | — | API key reference. |
| timeout_ms | int | Yes | 20000 | Timeout in ms. |
| safety_json | json | Yes | — | Safety config. |
| response_format | varchar(50) | Yes | — | Response format. |
| pono_score | decimal(3,2) | Yes | 1.00 | Pono score. |
| pilau_score | decimal(3,2) | Yes | 0.00 | Pilau score. |
| kapakai_score | decimal(3,2) | Yes | 0.50 | Kapakai score. |
| kapu_active | tinyint | Yes | 0 | Kapu active flag. |
| kapu_until | bigint | Yes | — | Kapu until timestamp. |
| kapu_reason | varchar(500) | Yes | — | Kapu reason. |
| kapu_consent_given | tinyint | Yes | 0 | Consent given. |
| kapu_appeal_pending | tinyint | Yes | 0 | Appeal pending. |

## Relationships

- **Logical references:** agent_id may align with actor identity in lupo_actors (is_agent). Referenced by lupo_agent_faucets (actor_id), lupo_agent_versions, lupo_agent_dependencies, lupo_agent_tool_calls, lupo_agent_files.
- **Inbound:** Agent runtime, faucet resolution, versioning, tool-call logging.
- **Join patterns:** By agent_key (unique), created_ymdhis, is_deleted, is_global_authority, updated_ymdhis.

## Usage Notes

- **Indexes:** created_ymdhis, is_deleted, is_global_authority, updated_ymdhis; unique on agent_key.
- **Timestamps:** BIGINT YYYYMMDDHHIISS UTC. Kapu fields may overlap with governance (KIRO); documented here as agent identity/config.
