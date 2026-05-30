---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_edge_type_definitions.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_edge_type_definitions.md
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
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
