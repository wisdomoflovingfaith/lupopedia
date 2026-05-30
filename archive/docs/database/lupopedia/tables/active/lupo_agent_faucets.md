---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "docs/database/lupopedia/tables/active/lupo_agent_faucets.md"
  web_path: null
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: documentation
  artifact_kind: table
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# file: lupo_agent_faucets.md

# lupo_agent_faucets

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_agent_faucets`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `agent_faucet_id` | `bigint NOT NULL` |
| `actor_id` | `bigint NOT NULL` |
| `name` | `varchar(100) NOT NULL` |
| `alias_name` | `varchar(100)` |
| `slug` | `varchar(100) NOT NULL` |
| `faucet_class` | `varchar(32)` |
| `description` | `text` |
| `style_preset` | `varchar(100)` |
| `model_name` | `varchar(100)` |
| `provider` | `varchar(50)` |
| `temperature` | `float` |
| `top_p` | `float` |
| `max_tokens` | `int` |
| `presence_penalty` | `float` |
| `frequency_penalty` | `float` |
| `system_prompt` | `text` |
| `safety_json` | `json` |
| `response_format` | `varchar(50)` |
| `capabilities_json` | `text` |
| `is_default` | `tinyint NOT NULL DEFAULT 0` |
| `domain_id` | `bigint NOT NULL DEFAULT 1` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_agent_faucets_idx_agent` | `actor_id` | no |
| `lupo_agent_faucets_idx_default` | `is_default` | no |
| `lupo_agent_faucets_idx_domain` | `domain_id` | no |
| `lupo_agent_faucets_idx_faucet_class` | `faucet_class` | no |
| `lupo_agent_faucets_idx_slug` | `slug` | no |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
