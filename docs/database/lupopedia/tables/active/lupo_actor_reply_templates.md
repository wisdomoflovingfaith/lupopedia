---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_actor_reply_templates.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_actor_reply_templates.md
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
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
