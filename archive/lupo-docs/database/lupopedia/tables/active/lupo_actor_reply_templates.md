---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_actor_reply_templates.md"
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
# file: lupo_actor_reply_templates.md

# lupo_actor_reply_templates

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_actor_reply_templates`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `actor_reply_template_id` | `bigint NOT NULL` |
| `actor_id` | `bigint NOT NULL` |
| `template_key` | `varchar(64) NOT NULL` |
| `template_text` | `text NOT NULL` |
| `usage_context` | `varchar(64)` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_actor_reply_templates_idx_actor` | `actor_id` | no |
| `lupo_actor_reply_templates_idx_created` | `created_ymdhis` | no |
| `lupo_actor_reply_templates_idx_deleted` | `is_deleted` | no |
| `lupo_actor_reply_templates_idx_key` | `template_key` | no |
| `lupo_actor_reply_templates_idx_updated` | `updated_ymdhis` | no |
| `lupo_actor_reply_templates_idx_usage_context` | `usage_context` | no |
| `lupo_actor_reply_templates_unq_actor_template_key` | `actor_id`, `template_key` | yes |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
