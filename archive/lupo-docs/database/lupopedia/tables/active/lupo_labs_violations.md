---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_labs_violations.md"
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
# file: lupo_labs_violations.md

# lupo_labs_violations

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_labs_violations`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `labs_violation_id` | `bigint NOT NULL` |
| `actor_id` | `bigint NOT NULL` |
| `certificate_id` | `varchar(64) NOT NULL` |
| `violation_code` | `varchar(64) NOT NULL` |
| `violation_description` | `text` |
| `violation_metadata` | `json` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_labs_violations_idx_actor` | `actor_id` | no |
| `lupo_labs_violations_idx_certificate` | `certificate_id` | no |
| `lupo_labs_violations_idx_created` | `created_ymdhis` | no |
| `lupo_labs_violations_idx_deleted` | `is_deleted` | no |
| `lupo_labs_violations_idx_violation_code` | `violation_code` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
