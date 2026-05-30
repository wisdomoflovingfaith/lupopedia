---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "docs/database/lupopedia/tables/active/lupo_help_topics.md"
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
# file: lupo_help_topics.md

# lupo_help_topics

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_help_topics`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `help_topic_id` | `bigint NOT NULL` |
| `slug` | `varchar(255) NOT NULL` |
| `title` | `varchar(255) NOT NULL` |
| `content_html` | `text` |
| `content_markdown` | `text` |
| `category` | `varchar(100)` |
| `parent_slug` | `varchar(255)` |
| `view_count` | `bigint DEFAULT 0` |
| `helpful_count` | `bigint DEFAULT 0` |
| `not_helpful_count` | `bigint DEFAULT 0` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `author_actor_id` | `bigint` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_help_topics_idx_author` | `author_actor_id` | no |
| `lupo_help_topics_idx_category` | `category` | no |
| `lupo_help_topics_idx_created` | `created_ymdhis` | no |
| `lupo_help_topics_idx_parent` | `parent_slug` | no |
| `lupo_help_topics_idx_slug` | `slug` | no |
| `lupo_help_topics_slug` | `slug` | yes |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
