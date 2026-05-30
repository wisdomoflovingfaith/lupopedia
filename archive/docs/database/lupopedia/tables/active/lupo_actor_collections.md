---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: null
  when_updated: "20260328013000"
  file_path_from_root: "docs/database/lupopedia/tables/active/lupo_actor_collections.md"
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
# file: lupo_actor_collections.md

# lupo_actor_collections

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_actor_collections`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `actor_collection_id` | `bigint NOT NULL` |
| `actor_id` | `bigint NOT NULL` |
| `collection_id` | `bigint NOT NULL` |
| `access_level` | `varchar(64) NOT NULL DEFAULT 'read'` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |
| `persistent_identity_json` | `json` |
| `identity_signature` | `varchar(255)` |
| `trust_level` | `varchar(64) DEFAULT 'standard'` |
| `emotional_geometry_baseline` | `json` |
| `doctrine_alignment_version` | `varchar(20) DEFAULT '3.0.0'` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_actor_collections_idx_access_level` | `access_level` | no |
| `lupo_actor_collections_idx_actor` | `actor_id` | no |
| `lupo_actor_collections_idx_collection` | `collection_id` | no |
| `lupo_actor_collections_idx_created_ymdhis` | `created_ymdhis` | no |
| `lupo_actor_collections_idx_identity_signature` | `identity_signature` | no |
| `lupo_actor_collections_idx_is_deleted` | `is_deleted` | no |
| `lupo_actor_collections_idx_trust_level` | `trust_level` | no |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
