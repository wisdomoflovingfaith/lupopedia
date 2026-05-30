---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "docs/database/lupopedia/tables/active/lupo_action_authorization.md"
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
# file: lupo_action_authorization.md

# lupo_action_authorization

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_action_authorization`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `action_authorization_id` | `bigint NOT NULL` |
| `action_key` | `varchar(100) NOT NULL` |
| `description` | `text NOT NULL` |
| `required_trait_keys` | `text` |
| `required_capabilities` | `text` |
| `required_role_keys` | `text` |
| `requires_all_conditions` | `tinyint NOT NULL DEFAULT 0` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `created_by_actor_id` | `bigint NOT NULL` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_action_authorization_idx_action` | `action_key` | no |
| `lupo_action_authorization_unique_action_key` | `action_key` | yes |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
