---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_comments.md"
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
# file: lupo_comments.md

# lupo_comments

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_comments`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `comment_id` | `bigint NOT NULL auto_increment` |
| `target_type` | `varchar(64) NOT NULL` |
| `target_id` | `bigint NOT NULL` |
| `channel_id` | `bigint NOT NULL DEFAULT 42` |
| `actor_id` | `bigint NOT NULL` |
| `faucet_id` | `bigint` |
| `comment_text` | `text NOT NULL` |
| `comment_type` | `varchar(64) NOT NULL DEFAULT 'comment'` |
| `parent_comment_id` | `bigint` |
| `created_ymdhis` | `bigint NOT NULL` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |
| `metadata_json` | `json` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_comments_idx_actor_id` | `actor_id` | no |
| `lupo_comments_idx_channel_id` | `channel_id` | no |
| `lupo_comments_idx_created_ymdhis` | `created_ymdhis` | no |
| `lupo_comments_idx_faucet_id` | `faucet_id` | no |
| `lupo_comments_idx_is_deleted` | `is_deleted` | no |
| `lupo_comments_idx_parent_comment_id` | `parent_comment_id` | no |
| `lupo_comments_idx_target` | `target_type`, `target_id` | no |

## Doctrine
Source of truth: `lupo-database/lupopedia/json/` TOON exports
Regeneration mode: Stage 3 deterministic normalization
Edge mode: placeholder baseline

**Note:** Tag and hashtag relationships are managed via the canonical `lupo_hashtags` and `lupo_hashtag_map` tables, not via comments. See the semantic_navbar documentation for details.
