---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_governance_overrides.md"
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
# file: lupo_governance_overrides.md

# lupo_governance_overrides

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_governance_overrides`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `governance_overrid_id` | `bigint NOT NULL` |
| `agent_id` | `bigint` |
| `applied_by_agent` | `bigint` |
| `override_type` | `varchar(100) NOT NULL` |
| `target_key` | `varchar(150)` |
| `old_value` | `text` |
| `new_value` | `text` |
| `reason_text` | `text` |
| `metadata_json` | `json` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `expires_ymdhis` | `bigint` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_governance_overrides_idx_agent` | `agent_id` | no |
| `lupo_governance_overrides_idx_applied_by` | `applied_by_agent` | no |
| `lupo_governance_overrides_idx_created` | `created_ymdhis` | no |
| `lupo_governance_overrides_idx_target` | `target_key` | no |
| `lupo_governance_overrides_idx_type` | `override_type` | no |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
