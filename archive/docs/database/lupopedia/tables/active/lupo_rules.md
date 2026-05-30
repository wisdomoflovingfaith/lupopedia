---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "docs/database/lupopedia/tables/active/lupo_rules.md"
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
# file: lupo_rules.md

# lupo_rules

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_rules`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `rule_id` | `bigint NOT NULL` |
| `rule_name` | `varchar(255) NOT NULL` |
| `rule_description` | `text` |
| `rule_type` | `varchar(64) NOT NULL` |
| `rule_script` | `text NOT NULL` |
| `rule_version` | `bigint NOT NULL DEFAULT 1` |
| `created_ymdhis` | `bigint NOT NULL` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_rules_idx_is_deleted` | `is_deleted` | no |
| `lupo_rules_idx_rule_name` | `rule_name` | no |
| `lupo_rules_idx_rule_type` | `rule_type` | no |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
