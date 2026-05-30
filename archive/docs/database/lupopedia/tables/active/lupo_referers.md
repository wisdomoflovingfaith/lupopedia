---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "docs/database/lupopedia/tables/active/lupo_referers.md"
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
# file: lupo_referers.md

# lupo_referers

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_referers`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `referer_id` | `bigint NOT NULL auto_increment` |
| `content_id` | `bigint NOT NULL` |
| `actor_id` | `bigint NOT NULL` |
| `referer_url` | `varchar(2000)` |
| `referer_domain` | `varchar(255)` |
| `referer_path` | `varchar(2000)` |
| `referer_content_id` | `bigint` |
| `date_ymd` | `int NOT NULL` |
| `visits` | `int NOT NULL DEFAULT 1` |
| `depth` | `int NOT NULL DEFAULT 0` |
| `metadata_json` | `json` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_referers_idx_actor_id` | `actor_id` | no |
| `lupo_referers_idx_content_id` | `content_id` | no |
| `lupo_referers_idx_date` | `date_ymd` | no |
| `lupo_referers_idx_referer_content_id` | `referer_content_id` | no |
| `lupo_referers_idx_referer_domain` | `referer_domain` | no |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
