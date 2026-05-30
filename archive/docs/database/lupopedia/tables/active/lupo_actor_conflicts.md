---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "docs/database/lupopedia/tables/active/lupo_actor_conflicts.md"
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
# file: lupo_actor_conflicts.md

# lupo_actor_conflicts

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_actor_conflicts`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `actor_conflict_id` | `bigint NOT NULL` |
| `domain_id` | `bigint NOT NULL DEFAULT 1` |
| `actor_a_id` | `bigint NOT NULL` |
| `actor_b_id` | `bigint NOT NULL` |
| `conflict_type` | `varchar(64) NOT NULL` |
| `conflict_summary` | `text NOT NULL` |
| `resolution_status` | `varchar(64) NOT NULL DEFAULT 'unresolved'` |
| `resolution_summary` | `text` |
| `resolved_by` | `bigint` |
| `resolved_ymdhis` | `bigint` |
| `severity` | `varchar(64) NOT NULL DEFAULT 'medium'` |
| `context_json` | `json` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_actor_conflicts_idx_agent_a` | `actor_a_id` | no |
| `lupo_actor_conflicts_idx_agent_b` | `actor_b_id` | no |
| `lupo_actor_conflicts_idx_agent_pair` | `actor_a_id`, `actor_b_id` | no |
| `lupo_actor_conflicts_idx_conflict_type` | `conflict_type` | no |
| `lupo_actor_conflicts_idx_created` | `created_ymdhis` | no |
| `lupo_actor_conflicts_idx_deleted` | `is_deleted` | no |
| `lupo_actor_conflicts_idx_domain` | `domain_id` | no |
| `lupo_actor_conflicts_idx_resolved_ymdhis` | `resolved_ymdhis` | no |
| `lupo_actor_conflicts_idx_severity` | `severity` | no |
| `lupo_actor_conflicts_idx_status` | `resolution_status` | no |
| `lupo_actor_conflicts_idx_updated` | `updated_ymdhis` | no |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
