---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_governance_overrides.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_governance_overrides.md
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
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
