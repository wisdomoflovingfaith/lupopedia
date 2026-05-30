---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_agent_experiences.md"
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
# file: lupo_agent_experiences.md

# lupo_agent_experiences

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_agent_experiences`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `link_id` | `char(26) NOT NULL` |
| `agent_id` | `bigint NOT NULL` |
| `star_id` | `char(26) NOT NULL` |
| `intensity` | `decimal(3,2)` |
| `context_id` | `bigint` |
| `observed_ymdhis` | `bigint` |
| `expressed_as_rgb` | `char(6)` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_agent_experiences_idx_agent` | `agent_id` | no |
| `lupo_agent_experiences_idx_context` | `context_id` | no |
| `lupo_agent_experiences_idx_star` | `star_id` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
