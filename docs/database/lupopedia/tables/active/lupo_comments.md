---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_comments.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_comments.md
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
Source of truth: `database/lupopedia/json/` TOON exports
Regeneration mode: Stage 3 deterministic normalization
Edge mode: placeholder baseline

**Note:** Tag and hashtag relationships are managed via the canonical `lupo_hashtags` and `lupo_hashtag_map` tables, not via comments. See the semantic_navbar documentation for details.
