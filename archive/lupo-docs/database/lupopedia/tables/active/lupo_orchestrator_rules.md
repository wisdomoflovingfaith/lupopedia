---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_orchestrator_rules.md"
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
# file: lupo_orchestrator_rules.md

# lupo_orchestrator_rules

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_orchestrator_rules`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `rule_id` | `bigint NOT NULL auto_increment` |
| `rule_slug` | `varchar(128) NOT NULL` |
| `orchestrator_actor` | `varchar(64) NOT NULL` |
| `rule_set_version` | `varchar(32) NOT NULL` |
| `applies_to_json` | `text NOT NULL` |
| `enforcement_level` | `varchar(32) NOT NULL DEFAULT 'strict'` |
| `rule_content` | `text NOT NULL` |
| `checksum` | `varchar(64) NOT NULL` |
| `is_active` | `tinyint NOT NULL DEFAULT 1` |
| `updated_ymdhis` | `bigint NOT NULL` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_orchestrator_rules_idx_active` | `is_active` | no |
| `lupo_orchestrator_rules_idx_actor_version` | `orchestrator_actor`, `rule_set_version` | no |
| `lupo_orchestrator_rules_idx_updated` | `updated_ymdhis` | no |
| `lupo_orchestrator_rules_uniq_slug` | `rule_slug` | yes |

## Doctrine
- Source of truth: `lupo-database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
