---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_agent_dependencies.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_agent_dependencies.md
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
# file: lupo_agent_dependencies.md

# lupo_agent_dependencies

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_agent_dependencies`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `agent_dependency_id` | `bigint NOT NULL` |
| `agent_id` | `bigint NOT NULL` |
| `depends_on_agent_id` | `bigint NOT NULL` |
| `depends_on_agent_code` | `varchar(50) NOT NULL` |
| `is_required` | `tinyint NOT NULL DEFAULT 1` |
| `notes` | `text` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_agent_dependencies_idx_agent_id` | `agent_id` | no |
| `lupo_agent_dependencies_idx_depends_on` | `depends_on_agent_id` | no |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
