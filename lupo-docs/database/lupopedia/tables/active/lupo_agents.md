---
lupopedia.headers:
  when_updated: "20260403000000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_agents.md"
  last_modified_utc: "20260403000000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_agents from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_agents.json"
    type: "references"
    weight: 1.0
    reason: "authoritative TOON JSON source"
lupopedia.footer:
  last_verified: "20260403000000"
  last_verified_by: "hephaestus"
  last_verified_by_actor_id: 23
  generated: true
  provenance: "stage3_track_c_normalization"
---
# file: lupo_agents.md

# lupo_agents

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_agents`.

## Schema

### Primary Key

| Column | Notes |
|--------|--------|
| `agent_id` | `bigint NOT NULL` — application-assigned id (no auto-increment) |

### Columns

| Column | Type Definition |
|---|---|
| `agent_id` | `bigint NOT NULL` |
| `agent_key` | `varchar(100) NOT NULL` |
| `agent_name` | `varchar(150) NOT NULL` |
| `archetype` | `varchar(150)` |
| `description` | `text` |
| `version` | `varchar(50) DEFAULT '1.0'` |
| `model_name` | `varchar(100)` |
| `is_global_authority` | `tinyint NOT NULL DEFAULT 0` |
| `is_internal_only` | `tinyint NOT NULL DEFAULT 0` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |
| `avg_response_time_ms` | `int DEFAULT 0` |
| `total_tokens_processed` | `bigint DEFAULT 0` |
| `success_rate` | `float DEFAULT 1` |
| `cost_per_1k_tokens` | `decimal(10,4) DEFAULT 0.0000` |
| `temperature` | `float DEFAULT 0.7` |
| `top_p` | `float DEFAULT 1` |
| `max_tokens` | `int DEFAULT 2048` |
| `presence_penalty` | `float DEFAULT 0` |
| `frequency_penalty` | `float DEFAULT 0` |
| `system_prompt` | `text` |
| `provider` | `varchar(50) DEFAULT 'openai'` |
| `api_key_id` | `bigint` |
| `timeout_ms` | `int DEFAULT 20000` |
| `safety_json` | `json` |
| `response_format` | `varchar(50)` |
| `metadata_json` | `json` — UI / avatar / extra agent config (replaces removed actor-style score/kapu columns) |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_agents_idx_api_key_id` | `api_key_id` | no |
| `lupo_agents_idx_created_ymdhis` | `created_ymdhis` | no |
| `lupo_agents_idx_is_deleted` | `is_deleted` | no |
| `lupo_agents_idx_is_global_authority` | `is_global_authority` | no |
| `lupo_agents_idx_updated_ymdhis` | `updated_ymdhis` | no |
| `lupo_agents_unique_agent_key` | `agent_key` | yes |

## Doctrine
- Canonical DDL: `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` (`{{prefix}}agents`)
- JSON/TOON under `lupo-database/lupopedia/json/` and `toon/` should match install; regenerate via `lupo-scripts/generate_toon_from_sql.py` when install changes
- Removed from schema (never shipped on actors; dropped from agents): `pono_score`, `pilau_score`, `kapakai_score`, `kapu_*` — use `metadata_json` or actor-level fields if needed later
