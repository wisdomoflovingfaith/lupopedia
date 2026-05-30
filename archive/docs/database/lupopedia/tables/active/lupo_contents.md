---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "docs/database/lupopedia/tables/active/lupo_contents.md"
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
# file: lupo_contents.md

# lupo_contents

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_contents`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `content_id` | `bigint NOT NULL` |
| `content_parent_id` | `bigint` |
| `federation_node_id` | `bigint DEFAULT 1` |
| `federation_source_url` | `varchar(2000) COMMENT 'Canonical URL of content at source federation node'` |
| `channel_id` | `bigint COMMENT 'Channel this content belongs to (doctrine: content placement)'` |
| `department_id` | `bigint` |
| `actor_id` | `bigint` |
| `title` | `varchar(255) NOT NULL` |
| `slug` | `varchar(255) NOT NULL` |
| `custom_path` | `varchar(255)` |
| `description` | `text` |
| `seo_keywords` | `varchar(500)` |
| `body` | `text` |
| `content` | `text` |
| `content_type` | `varchar(50) DEFAULT 'article'` |
| `format` | `varchar(20) DEFAULT 'markdown'` |
| `content_url` | `varchar(2000)` |
| `default_collection_id` | `bigint` |
| `source_url` | `varchar(2000)` |
| `source_title` | `varchar(500)` |
| `is_template` | `tinyint NOT NULL DEFAULT 0` |
| `status` | `varchar(64) DEFAULT 'draft'` |
| `visibility` | `varchar(64) DEFAULT 'public'` |
| `view_count` | `int DEFAULT 0` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `utc_cycle` | `varchar(64) NOT NULL` |
| `triage_status` | `varchar(64) NOT NULL DEFAULT 'untriaged'` |
| `triage_notes` | `text` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `is_active` | `tinyint NOT NULL DEFAULT 1` |
| `deleted_ymdhis` | `bigint` |
| `content_sections` | `json` |
| `version_number` | `int NOT NULL DEFAULT 1` |
| `file_path_from_root` | `varchar(500) COMMENT 'FLIP Header: path from repo root (4.0.86)'` |
| `file_last_modified_system_version` | `varchar(20) COMMENT 'FLIP: system version at last file edit'` |
| `file_last_modified_utc` | `bigint COMMENT 'FLIP: UTC last modified YYYYMMDDHHIISS'` |
| `tags` | `json` |
| `dialog_notes` | `text` |
| `atom_mappings` | `json COMMENT 'Consolidated from lupo_content_atom_map'` |
| `category_mappings` | `json COMMENT 'Consolidated from lupo_content_category_map'` |
| `content_events` | `json COMMENT 'Consolidated from lupo_content_events'` |
| `hashtags` | `json COMMENT 'Consolidated from lupo_content_hashtag'` |
| `inbound_links` | `json COMMENT 'Consolidated from lupo_content_inbound_links'` |
| `like_users` | `json COMMENT 'Consolidated from lupo_content_likes'` |
| `media_attachments` | `json COMMENT 'Consolidated from lupo_content_media'` |
| `question_mappings` | `json COMMENT 'Consolidated from lupo_content_question_map'` |
| `content_references` | `json COMMENT 'Consolidated from lupo_content_references'` |
| `revision_history` | `json COMMENT 'Consolidated from lupo_content_revisions'` |
| `share_users` | `json COMMENT 'Consolidated from lupo_content_shares'` |
| `tag_relationships` | `json COMMENT 'Consolidated from lupo_content_tag_relationships'` |
| `like_count` | `bigint NOT NULL DEFAULT 0 COMMENT 'FLARE-aligned engagement cache'` |
| `tags` | `json` | **[DEPRECATED]** Use `lupo_hashtags`/`lupo_hashtag_map` for all new tag relationships. This column is retained for legacy compatibility only. |
| `hashtags` | `json` | **[DEPRECATED]** Use `lupo_hashtags`/`lupo_hashtag_map` for all new hashtag relationships. This column is retained for legacy compatibility only. |
| `share_count` | `bigint NOT NULL DEFAULT 0 COMMENT 'FLARE-aligned engagement cache'` |
| `comment_count` | `bigint NOT NULL DEFAULT 0 COMMENT 'FLARE-aligned engagement cache'` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_contents_idx_channel_id` | `channel_id` | no |
| `lupo_contents_idx_content_parent` | `content_parent_id` | no |
| `lupo_contents_idx_content_type` | `content_type` | no |
| `lupo_contents_idx_created_ymdhis` | `created_ymdhis` | no |
| `lupo_contents_idx_custom_path` | `custom_path` | yes |
| `lupo_contents_idx_department` | `department_id` | no |
| `lupo_contents_idx_domain` | `federation_node_id` | no |
| `lupo_contents_idx_engagement_counts` | `like_count`, `share_count`, `comment_count` | no |
| `lupo_contents_idx_file_path_from_root` | `file_path_from_root` | no |
| `lupo_contents_idx_has_events` | `None` | no |
| `lupo_contents_idx_has_hashtags` | `None` | no |
| `lupo_contents_idx_has_media` | `None` | no |
| `lupo_contents_idx_is_active` | `is_active` | no |
| `lupo_contents_idx_is_deleted` | `is_deleted` | no |
| `lupo_contents_idx_status` | `status` | no |
| `lupo_contents_idx_updated_ymdhis` | `updated_ymdhis` | no |
| `lupo_contents_idx_user` | `actor_id` | no |
| `lupo_contents_idx_visibility` | `visibility` | no |
| `lupo_contents_unique_content_slug_domain` | `federation_node_id`, `slug` | yes |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
