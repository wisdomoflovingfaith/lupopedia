---
lupopedia.headers:
  header_format_version: "4.0.99"
  lupopedia.schema: documentation
  when_updated: "20260412012420"
  file_path_from_root: "docs/database/lupopedia/tables/active/lupo_channels.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/database/lupopedia/tables/active/lupo_channels.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: "development"
  trust_tier: "canonical"
  memory_key: "memory/development/canonical/1026/04/channels.toon"
  artifact_type: documentation
  artifact_kind: table_schema
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: "channels"
  title: "lupo_channels table (active documentation)"
  status: "complete"
  parent_pk_id: ""
  summary: "TOON-aligned schema reference for lupo_channels; channel management, routing, and namespace channels."
  module: null
  dialog_transcript: "0/development/channels"
---
# file: lupo_channels.md

# lupo_channels

## Purpose
Canonical table documentation regenerated from TOON JSON for `lupo_channels`.

**Tooling (2026-04-12):** `scripts/export_channel_snapshots.py` is **deprecated** (exit stub only). Prefer **DB queries**, **`lupo_memory_nodes` / `lupo_memory_edges`**, **`php bin/export.php`**, or **`scripts/export_memory_nodes_116.py`**. Unsupported archived copy: **`scripts/legacy/export_channel_snapshots_archived_20260324.py`** (see **`scripts/legacy/README.md`**).

## Schema

### Primary Key
(none)

### Columns

| Column | Type Definition |
|---|---|
| `channel_id` | `bigint NOT NULL` |
| `federation_node_id` | `bigint NOT NULL` |
| `created_by_actor_id` | `bigint NOT NULL` |
| `default_actor_id` | `bigint NOT NULL DEFAULT 1` |
| `department_id` | `bigint NOT NULL DEFAULT 1` |
| `channel_key` | `varchar(64) NOT NULL` |
| `channel_slug` | `varchar(32) NOT NULL DEFAULT 'channel_key'` |
| `channel_type` | `varchar(32) NOT NULL DEFAULT 'chat_room'` |
| `language` | `varchar(16) NOT NULL DEFAULT 'en'` |
| `channel_name` | `varchar(255) NOT NULL` |
| `description` | `text` |
| `website_link` | `varchar(512)` |
| `metadata_json` | `text` |
| `channel_config` | `text` |
| `status_flag` | `tinyint NOT NULL DEFAULT 1` |
| `end_ymdhis` | `bigint` |
| `created_ymdhis` | `bigint NOT NULL DEFAULT 0` |
| `updated_ymdhis` | `bigint NOT NULL` |
| `is_deleted` | `tinyint NOT NULL DEFAULT 0` |
| `deleted_ymdhis` | `bigint` |
| `aal_metadata_json` | `json` |
| `fleet_composition_json` | `json` |
| `awareness_version` | `varchar(20) DEFAULT '3.0.0'` |
| `channel_number` | `int` |
| `parent_channel_id` | `bigint` |
| `project_id` | `bigint` |
| `is_kernel` | `tinyint NOT NULL DEFAULT 0` |
| `boot_sequence_order` | `int` |
| `visibility_status` | `varchar(32) NOT NULL DEFAULT 'active'` |
| `owner_actor_id` | `bigint NOT NULL DEFAULT 1` |
| `access_level` | `varchar(32) NOT NULL DEFAULT 'public'` |
| `last_activity_ymdhis` | `bigint NOT NULL DEFAULT 0` |

### Indexes

| Index | Columns | Unique |
|---|---|---|
| `lupo_channels_idx_access_level` | `access_level` | no |
| `lupo_channels_idx_awareness_version` | `awareness_version` | no |
| `lupo_channels_idx_channel_key` | `channel_key` | no |
| `lupo_channels_idx_dates` | `end_ymdhis` | no |
| `lupo_channels_idx_domain` | `federation_node_id` | no |
| `lupo_channels_idx_last_activity` | `last_activity_ymdhis` | no |
| `lupo_channels_idx_owner_actor_id` | `owner_actor_id` | no |
| `lupo_channels_idx_project_id` | `project_id` | no |
| `lupo_channels_idx_status` | `status_flag` | no |
| `lupo_channels_idx_visibility_status` | `visibility_status` | no |
| `lupo_channels_unq_channel_key_per_node` | `channel_key`, `federation_node_id` | yes |

## Doctrine
- Source of truth: `database/lupopedia/json/` TOON exports
- Regeneration mode: Phase 2 deterministic rebuild
- Edge mode: placeholder only
