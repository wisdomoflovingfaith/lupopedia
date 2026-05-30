---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_channel_content.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_channel_content.md
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
# file: lupo_channel_content.md

# lupo_channel_content

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_channel_content`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `channel_content_id` | `bigint NOT NULL auto_increment` |
| `channel_id` | `bigint NOT NULL` |
| `federation_node_id` | `bigint NOT NULL` |
| `file_path` | `varchar(500) NOT NULL` |
| `web_path` | `varchar(500) NOT NULL` |
| `metadata_json` | `json` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_channel_content_idx_channel` | `channel_id` | no |
| `lupo_channel_content_idx_created` | `created_ymdhis` | no |
| `lupo_channel_content_idx_federation_node` | `federation_node_id` | no |
| `lupo_channel_content_idx_file_path` | `file_path` | no |
| `lupo_channel_content_idx_is_deleted` | `is_deleted` | no |
| `lupo_channel_content_idx_updated` | `updated_ymdhis` | no |
| `lupo_channel_content_idx_web_path` | `web_path` | no |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
