---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_agent_tool_calls.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_agent_tool_calls from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_agent_tool_calls.json"
    type: "references"
    weight: 1.0
    reason: "authoritative TOON JSON source"
lupopedia.footer:
  last_verified: "20260328013000"
  last_verified_by: "hephaestus"
  last_verified_by_actor_id: 23
  generated: true
  provenance: "stage3_track_c_normalization"
---
# file: lupo_agent_tool_calls.md

# lupo_agent_tool_calls

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_agent_tool_calls`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `agent_tool_call_id` | `bigint NOT NULL` |
| `agent_id` | `bigint NOT NULL` |
| `faucet_id` | `bigint` |
| `domain_id` | `bigint NOT NULL` |
| `tool_name` | `varchar(150) NOT NULL` |
| `action_type` | `varchar(100)` |
| `input_json` | `text` |
| `output_json` | `text` |
| `provider` | `varchar(50)` |
| `model_name` | `varchar(150)` |
| `tokens_prompt` | `int DEFAULT 0` |
| `tokens_completion` | `int DEFAULT 0` |
| `tokens_total` | `int DEFAULT 0` |
| `cost_usd` | `decimal(10,6) DEFAULT 0.000000` |
| `latency_ms` | `int DEFAULT 0` |
| `status` | `varchar(50) DEFAULT 'success'` |
| `error_message` | `text` |
| `parent_call_id` | `bigint` |
| `thread_id` | `bigint` |
| `message_id` | `bigint` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |
| `archived_ymdhis` | `bigint DEFAULT 0` |
| `completed_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_agent_tool_calls_idx_agent` | `agent_id` | no |
| `lupo_agent_tool_calls_idx_agent_created` | `agent_id`, `created_ymdhis` | no |
| `lupo_agent_tool_calls_idx_domain` | `domain_id` | no |
| `lupo_agent_tool_calls_idx_faucet` | `faucet_id` | no |
| `lupo_agent_tool_calls_idx_message` | `message_id` | no |
| `lupo_agent_tool_calls_idx_model` | `model_name` | no |
| `lupo_agent_tool_calls_idx_parent` | `parent_call_id` | no |
| `lupo_agent_tool_calls_idx_provider` | `provider` | no |
| `lupo_agent_tool_calls_idx_thread` | `thread_id` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
