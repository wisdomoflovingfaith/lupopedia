---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "database_table"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_agent_tool_calls.md"
  system_version: "4.0.70"
  channel_id: 1
  actor_id: 102
  last_modified_utc: "20260312"
  artifact_type: "table_documentation"
  purpose: "Tool-call log per agent/faucet: tool_name, input/output, tokens, cost, status"
  mood_rgb: "4169E1"
  traits: ["canonical", "agent", "cursor_domain", "v4.0.70"]
  tags: ["database", "agents", "tool_calls"]
  lupo_agent: "cursor"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-database/lupopedia/toon/lupo_agent_tool_calls.toon.json", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_agents.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_agent_faucets.md", type: "references", weight: 0.7 }

lupopedia.footer:
  last_verified: "20260312"
  last_verified_by: "cursor"
---

# Table: lupo_agent_tool_calls

## Table Overview

- **Purpose:** Audit and metrics for agent tool calls: agent_id, faucet_id, domain_id, tool_name, action_type, input_json, output_json, provider, model_name, token counts, cost_usd, latency_ms, status, error_message, parent_call_id, thread_id, message_id, created_ymdhis, completed_ymdhis.
- **Category:** Agent / Audit
- **Status:** Active
- **Version introduced:** 4.0.0

## Column Documentation

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| agent_tool_call_id | bigint | No | — | Primary key. |
| agent_id | bigint | No | — | Agent (logical → lupo_agents). |
| faucet_id | bigint | Yes | — | Faucet (logical → lupo_agent_faucets.agent_faucet_id). |
| domain_id | bigint | No | — | Domain. |
| tool_name | varchar(150) | No | — | Tool name. |
| action_type | varchar(100) | Yes | — | Action type. |
| input_json | text | Yes | — | Input payload. |
| output_json | text | Yes | — | Output payload. |
| provider | varchar(50) | Yes | — | Provider. |
| model_name | varchar(150) | Yes | — | Model name. |
| tokens_prompt | int | Yes | 0 | Prompt tokens. |
| tokens_completion | int | Yes | 0 | Completion tokens. |
| tokens_total | int | Yes | 0 | Total tokens. |
| cost_usd | decimal(10,6) | Yes | 0 | Cost in USD. |
| latency_ms | int | Yes | 0 | Latency in ms. |
| status | varchar(50) | Yes | 'success' | Status. |
| error_message | text | Yes | — | Error message if failed. |
| parent_call_id | bigint | Yes | — | Parent tool call. |
| thread_id | bigint | Yes | — | Thread reference. |
| message_id | bigint | Yes | — | Message reference. |
| created_ymdhis | bigint | No | 0 | Creation. |
| completed_ymdhis | bigint | Yes | — | Completion timestamp. |

## Relationships

- **Logical references:** agent_id → lupo_agents; faucet_id → lupo_agent_faucets; parent_call_id → same table.
- **Inbound:** Agent runtime logs tool calls; context snapshots may reference via related_tool_call_id.
- **Join patterns:** By agent_id, domain_id, faucet_id, message_id, model_name, parent_call_id, provider, thread_id.

## Usage Notes

- **Indexes:** agent_id, domain_id, faucet_id, message_id, model_name, parent_call_id, provider, thread_id.
- **Timestamps:** BIGINT YYYYMMDDHHIISS UTC.
