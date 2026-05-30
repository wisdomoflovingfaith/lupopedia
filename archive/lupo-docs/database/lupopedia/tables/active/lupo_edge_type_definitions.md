---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_edge_type_definitions.md"
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
# file: lupo_edge_type_definitions.md

# lupo_edge_type_definitions

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_edge_type_definitions`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `edge_type_definition_id` | `bigint NOT NULL` |
| `edge_type` | `varchar(100) NOT NULL` |
| `domain` | `varchar(100) NOT NULL` |
| `description` | `text NOT NULL` |
| `allowed_left_object_types` | `text NOT NULL` |
| `allowed_right_object_types` | `text NOT NULL` |
| `is_bidirectional` | `tinyint NOT NULL DEFAULT 0` |
| `semantic_meaning` | `text` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `created_by_actor_id` | `bigint NOT NULL` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_edge_type_definitions_idx_domain` | `domain` | no |
| `lupo_edge_type_definitions_unique_edge_type` | `edge_type` | yes |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
