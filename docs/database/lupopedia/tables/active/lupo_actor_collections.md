---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_actor_collections.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_actor_collections.md
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
