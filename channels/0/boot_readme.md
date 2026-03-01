# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) — see http://www.lupopedia.com/FLARE
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: "channels/0/boot_readme.md"
  file_hash: "to_be_generated"
  federation_node_id: 0
  web_path: "http://www.lupopedia.com/boot_readme"
  last_updated_utc: "20260301"
  system_version: "4.0.52"
  channel_id: 0
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "guide"
  purpose: "Channel boot logging schema and usage summary for node 0"
  dialog_message: "Channel boot system documentation with TOON schema authority and federation integration"
  mood_rgb: "4169E1"
  traits: ["channel_boot", "schema", "node_0"]
  tags: ["channel_boot", "boot_log", "boot_detail", "toons"]
  lupo_agent: "windsurf"

flare.edges:
  outbound_edges:
    - { to: "docs/toons/lupo_channel_boot_log.toon.json", type: "schema_reference", weight: 1.0 }
    - { to: "docs/toons/lupo_channel_boot_detail.toon.json", type: "schema_reference", weight: 1.0 }
    - { to: "docs/toons/lupo_channels.toon.json", type: "schema_reference", weight: 0.9 }
    - { to: "docs/toons/lupo_channel_state.toon.json", type: "schema_reference", weight: 0.9 }
    - { to: "docs/toons/lupo_channel_logs.toon.json", type: "schema_reference", weight: 0.9 }
    - { to: "docs/toons/lupo_channel_files.toon.json", type: "schema_reference", weight: 0.8 }
    - { to: "docs/toons/lupo_channel_content.toon.json", type: "schema_reference", weight: 0.8 }
    - { to: "docs/FLARE_HEADERS_COMPLETE_REFERENCE.md", type: "references", weight: 0.9 }
    - { to: "docs/FLARE_HEADERS_QUICK_REFERENCE.md", type: "references", weight: 0.8 }
    - { to: "docs/doctrine/FLARE/FLARE_DOCTRINE.md", type: "references", weight: 1.0 }
    - { to: "actors/registry.json", type: "references", weight: 0.8 }
    - { to: "database/migrations/install_lupopedia.sql", type: "references", weight: 0.7 }
    - { to: "docs/database/lupopedia/tables/lupo_channel_content.md", type: "references", weight: 0.7 }
    - { to: "channels/42/content/federation_node_id/0/FLARE.md", type: "references", weight: 0.9 }
  semantic_tags: ["channel_boot", "schema", "node_0", "canonical"]

flare.footer:
  version: "4.0.52"
  last_verified: "20260301"
  last_verified_by: "windsurf"
---

# Channel Boot Tables (Node 0)

## Scope
This document summarizes the channel boot logging tables defined by TOON schema. It covers how `lupo_channel_boot_log` and `lupo_channel_boot_detail` are expected to be used during a boot cycle, without adding schema beyond what the TOONs declare.

## Table: lupo_channel_boot_log
Purpose: One row per boot run. Tracks who initiated the boot, timing, status, and summary metrics.

Primary key: `boot_id`

Fields:

| Column | Type | Notes |
| --- | --- | --- |
| `boot_id` | bigint | Primary key. |
| `actor_id` | bigint | Actor that initiated the boot. |
| `session_id` | varchar(64) | Session identifier. |
| `boot_start_time` | bigint | UTC timestamp in `YYYYMMDDHHIISS`. |
| `boot_end_time` | bigint | UTC timestamp in `YYYYMMDDHHIISS`. |
| `boot_status` | varchar(64) | Status string, default `started`. |
| `channels_loaded` | int | Count of channels loaded in this boot. |
| `total_channels` | int | Total channels expected to load. |
| `error_details` | json | Optional error payload. |
| `performance_metrics` | json | Optional performance payload. |
| `created_ymdhis` | bigint | Row creation time in `YYYYMMDDHHIISS`. |

Indexes:

| Index | Columns | Notes |
| --- | --- | --- |
| `lupo_channel_boot_log_idx_actor_session` | `actor_id`, `session_id` | Lookup by actor and session. |
| `lupo_channel_boot_log_idx_boot_status_time` | `boot_status`, `boot_start_time` | Status and time range queries. |

## Table: lupo_channel_boot_detail
Purpose: One row per channel within a boot run. Tracks per-channel progress and timing.

Primary key: `detail_id`

Fields:

| Column | Type | Notes |
| --- | --- | --- |
| `detail_id` | bigint | Primary key. |
| `boot_id` | bigint | Boot run identifier. |
| `channel_id` | bigint | Channel being processed. |
| `load_start_time` | bigint | UTC timestamp in `YYYYMMDDHHIISS`. |
| `load_end_time` | bigint | UTC timestamp in `YYYYMMDDHHIISS`. |
| `load_status` | varchar(64) | Status string, default `started`. |
| `content_items_loaded` | int | Items loaded for the channel. |
| `total_content_items` | int | Total items expected. |
| `load_duration_ms` | int | Duration in milliseconds. |
| `error_message` | text | Optional error message. |
| `created_ymdhis` | bigint | Row creation time in `YYYYMMDDHHIISS`. |

Indexes:

| Index | Columns | Notes |
| --- | --- | --- |
| `lupo_channel_boot_detail_fk_boot_detail_channel` | `channel_id` | Channel lookup. |
| `lupo_channel_boot_detail_idx_boot_channel` | `boot_id`, `channel_id` | Boot to channel correlation. |
| `lupo_channel_boot_detail_idx_load_status_time` | `load_status`, `load_start_time` | Status and time range queries. |

## Relationship Notes
- `boot_id` links `lupo_channel_boot_detail` rows to `lupo_channel_boot_log`, but this is not enforced as a foreign key.
- The TOONs explicitly declare `no_foreign_keys` and `no_triggers`.

## Timestamp Rules
All time fields are BIGINT values in UTC `YYYYMMDDHHIISS` format. In PHP, use `gmdate('YmdHis')` for values like `boot_start_time`, `boot_end_time`, `load_start_time`, `load_end_time`, and `created_ymdhis`.

## Suggested Boot Lifecycle (Schema-Aligned)
1. Insert a `lupo_channel_boot_log` row with `boot_status = 'started'`, `boot_start_time`, and `total_channels`.
2. For each channel, insert a `lupo_channel_boot_detail` row with `load_status = 'started'` and `load_start_time`.
3. Update each detail row as content loads, including `content_items_loaded`, `total_content_items`, `load_end_time`, and `load_duration_ms`.
4. Update the boot log with `channels_loaded`, `boot_end_time`, and a final `boot_status`.

## Status Fields
`boot_status` and `load_status` are free-form strings with a default of `started`. The schema does not enforce an enum; any agreed status vocabulary is a system convention.

## References
- `docs/toons/lupo_channel_boot_log.toon.json`
- `docs/toons/lupo_channel_boot_detail.toon.json`
- `docs/FLARE_HEADERS_COMPLETE_REFERENCE.md`
- `docs/FLARE_HEADERS_QUICK_REFERENCE.md`
- `docs/doctrine/FLARE/FLARE_DOCTRINE.md`