---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_referers.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_referers.md
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
