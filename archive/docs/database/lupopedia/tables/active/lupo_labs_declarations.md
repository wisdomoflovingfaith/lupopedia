---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "docs/database/lupopedia/tables/active/lupo_labs_declarations.md"
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
# file: lupo_labs_declarations.md

# lupo_labs_declarations

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_labs_declarations`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `labs_declaration_id` | `bigint NOT NULL` |
| `actor_id` | `bigint NOT NULL` |
| `certificate_id` | `varchar(64) NOT NULL` |
| `declaration_timestamp` | `bigint NOT NULL` |
| `declarations_json` | `json NOT NULL` |
| `validation_status` | `varchar(64) NOT NULL DEFAULT 'valid'` |
| `labs_version` | `varchar(16) NOT NULL DEFAULT '1.0'` |
| `next_revalidation_ymdhis` | `bigint NOT NULL` |
| `validation_log_json` | `json` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_labs_declarations_idx_actor_id` | `actor_id` | no |
| `lupo_labs_declarations_idx_actor_status` | `actor_id`, `validation_status`, `is_deleted` | no |
| `lupo_labs_declarations_idx_certificate_id` | `certificate_id` | no |
| `lupo_labs_declarations_idx_next_revalidation` | `next_revalidation_ymdhis` | no |
| `lupo_labs_declarations_idx_revalidation_due` | `next_revalidation_ymdhis`, `validation_status`, `is_deleted` | no |
| `lupo_labs_declarations_idx_validation_status` | `validation_status` | no |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
