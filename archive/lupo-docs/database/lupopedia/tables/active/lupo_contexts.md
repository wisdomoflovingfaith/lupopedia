---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_contexts.md"
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
# file: lupo_contexts.md

# lupo_contexts

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_contexts`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `context_id` | `int NOT NULL` |
| `context_code` | `varchar(16) NOT NULL` |
| `context_name` | `varchar(255) NOT NULL` |
| `context_description` | `text` |
| `parent_context_id` | `int` |
| `is_system` | `tinyint NOT NULL DEFAULT 0` |
| `is_fiction` | `tinyint NOT NULL DEFAULT 0` |
| `is_installation_local` | `tinyint NOT NULL DEFAULT 0` |
| `sort_order` | `int NOT NULL DEFAULT 0` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `weight_score` | `decimal(5,2) NOT NULL DEFAULT 0.00` |
| `is_active` | `tinyint NOT NULL DEFAULT 1` |
| `metadata_json` | `json` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_contexts_idx_parent_context` | `parent_context_id` | no |
| `lupo_contexts_uq_context_code` | `context_code` | yes |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
