---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_agent_tool_calls.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_agent_tool_calls.md
  status: ''
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: documentation
  artifact_kind: table
  channel_key: null
  federation_node_id: null
  thread_key: ''
  lupopedia.schema: null
  prd_cluster: null
  title: ''
  summary: ''
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
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
