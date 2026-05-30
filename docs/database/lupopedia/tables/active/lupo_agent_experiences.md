---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_agent_experiences.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_agent_experiences.md
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
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
