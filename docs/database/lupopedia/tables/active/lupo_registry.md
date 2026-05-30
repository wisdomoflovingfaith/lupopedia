---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/database/lupopedia/tables/active/lupo_registry.md
  web_path: https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_registry.md
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
# file: lupo_registry.md

# lupo_registry

## Purpose
Canonical table documentation normalized from TOON JSON for `lupo_registry`.

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `registry_id` | `bigint NOT NULL auto_increment` |
| `entity_type` | `varchar(50) NOT NULL` |
| `entity_index_id` | `bigint NOT NULL DEFAULT 0` |
| `entity_index` | `bigint NOT NULL DEFAULT 0` |
| `federation_node_id` | `bigint NOT NULL DEFAULT 0` |
| `reserved_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `metadata` | `text` |
| `entity_key` | `varchar(255)` |
| `entity_name` | `varchar(255)` |
| `entity_table` | `varchar(255)` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |
| `is_active` | `tinyint NOT NULL DEFAULT 1` |
| `is_kernel` | `tinyint NOT NULL DEFAULT 0` |
| `metadata_json` | `text` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `idx_registry_entity_type` | `entity_type` | no |
| `idx_registry_federation_node` | `federation_node_id` | no |
| `idx_registry_unique` | `entity_type`, `entity_index_id`, `federation_node_id` | yes |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Stage 3 deterministic normalization
- Edge mode: placeholder baseline
