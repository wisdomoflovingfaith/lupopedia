---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_federation_nodes.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_federation_nodes.md
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
# file: lupo_federation_nodes.md

# lupo_federation_nodes

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_federation_nodes`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `federation_node_id` | `bigint NOT NULL` |
| `node_type` | `varchar(32) NOT NULL DEFAULT 'local'` |
| `node_base_url` | `varchar(500) NOT NULL` |
| `default_department_id` | `bigint` |
| `node_name` | `varchar(255)` |
| `node_description` | `text` |
| `allows_foreign_traits` | `tinyint NOT NULL DEFAULT 1` |
| `node_contact` | `varchar(255)` |
| `meta_json` | `json` |
| `content_count` | `bigint NOT NULL DEFAULT 0` |
| `atom_count` | `bigint NOT NULL DEFAULT 0` |
| `hashtag_count` | `bigint NOT NULL DEFAULT 0` |
| `actor_count` | `bigint NOT NULL DEFAULT 0` |
| `last_sync_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `trust_level` | `tinyint NOT NULL DEFAULT 0` |
| `status` | `tinyint NOT NULL DEFAULT 1` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `active_theme_slug` | `varchar(64) DEFAULT 'default'` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_federation_nodes_idx_is_deleted` | `is_deleted` | no |
| `lupo_federation_nodes_idx_node_base_url` | `node_base_url` | no |
| `lupo_federation_nodes_idx_status` | `status` | no |
| `lupo_federation_nodes_idx_trust_level` | `trust_level` | no |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
