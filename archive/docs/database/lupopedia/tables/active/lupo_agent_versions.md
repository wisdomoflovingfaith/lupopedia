---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "docs/database/lupopedia/tables/active/lupo_agent_versions.md"
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
# file: lupo_agent_versions.md

# lupo_agent_versions

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_agent_versions`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `agent_version_id` | `bigint NOT NULL` |
| `agent_id` | `bigint NOT NULL` |
| `version_label` | `varchar(64) NOT NULL` |
| `semver_major` | `int DEFAULT 0` |
| `semver_minor` | `int DEFAULT 0` |
| `semver_patch` | `int DEFAULT 0` |
| `version_notes` | `text` |
| `version_hash` | `varchar(128)` |
| `previous_version_id` | `bigint` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_deleted` | `smallint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_agent_versions_agent_id` | `agent_id` | no |
| `lupo_agent_versions_semver_major` | `semver_major`, `semver_minor`, `semver_patch` | no |
| `lupo_agent_versions_version_label` | `version_label` | no |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
