---
lupopedia.headers:
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_help_topics.md"
  last_modified_utc: "20260328013000"
  channel_id: 42
  actor_id: 23
  actor_name: "hephaestus"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "table"
  namespace: "core"
  purpose: "Normalized table documentation for lupo_help_topics from TOON JSON"
  tags:
  - database
  - table
  - normalized
  - 4.0.88
lupopedia.edges:
  comment: "static placeholder edges for stage3 normalization"
  outbound_edges:
  - to: "lupo-database/lupopedia/json/lupo_help_topics.json"
    type: "references"
    weight: 1.0
    reason: "authoritative TOON JSON source"
lupopedia.footer:
  last_verified: "20260328013000"
  last_verified_by: "hephaestus"
  last_verified_by_actor_id: 23
  generated: true
  provenance: "stage3_track_c_normalization"
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
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
