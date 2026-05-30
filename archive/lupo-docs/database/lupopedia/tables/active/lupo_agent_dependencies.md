---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_agent_dependencies.md"
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
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
