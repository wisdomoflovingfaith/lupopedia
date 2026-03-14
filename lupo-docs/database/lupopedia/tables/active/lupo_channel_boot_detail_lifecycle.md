---
lupopedia.headers:
  file_path_from_root: "lupo-docs/database/lupopedia/tables/lupo_channel_boot_detail_lifecycle.md"
  system_version: "4.0.56"
  channel_id: 42
  actor_id: 1003
  last_modified_utc: "20260303"
  artifact_type: "table_documentation"
  purpose: "Channel boot detail lifecycle — per-channel boot phase details (content load, duration, status)"
  traits: ["database", "table", "channel", "cursor"]
  tags: ["database", "table", "lupo_channel_boot_detail_lifecycle", "channel", "boot"]
  lupo_agent: "cursor"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-database/lupopedia/toon/lupo_channel_boot_detail_lifecycle.toon.json", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/lupo_channel_boot_log.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/database/lupopedia/tables/lupo_channels.md", type: "references", weight: 0.8 }
  semantic_tags: ["channel", "boot", "lifecycle"]

lupopedia.footer:
  last_verified_utc: "20260303"
  last_verified_by: "cursor"
---

# Database Documentation: lupo_channel_boot_detail_lifecycle

**Version:** 4.0.56  
**Date:** 2026-03-03

## 1. Overview

The `lupo_channel_boot_detail_lifecycle` table stores fine-grained lifecycle steps for a channel boot (e.g. content loading). Each row is one detail phase: start/end time, status (e.g. started, completed), content items loaded vs total, duration, and optional error message. Links to a parent lifecycle via `lifecycle_id` and to a channel via `channel_id`.

**Doctrine:** No foreign keys or triggers; application-level linkage to channel boot log and `lupo_channels`.

## 2. Schema (from TOON)

| Column | Type | Description |
|--------|------|-------------|
| `detail_lifecycle_id` | bigint NOT NULL | Primary key (auto_increment). |
| `lifecycle_id` | bigint NOT NULL | Parent boot lifecycle id. |
| `channel_id` | bigint NOT NULL | Channel being booted. |
| `detail_start_time` | bigint NOT NULL | When this detail phase started. |
| `detail_end_time` | bigint | When it ended (null if in progress). |
| `detail_status` | varchar(64) NOT NULL | e.g. started, completed (default started). |
| `content_items_loaded` | int NOT NULL | Count of content items loaded (default 0). |
| `total_content_items` | int NOT NULL | Total expected (default 0). |
| `detail_duration_ms` | int | Duration in milliseconds. |
| `error_message` | text | Error if phase failed. |
| `created_ymdhis` | bigint NOT NULL | Record creation (YmdHis, default 0). |

## 3. Indexes

- (none in TOON)

## 4. Primary key

- `detail_lifecycle_id`

---

*Documentation for TOON: lupo_channel_boot_detail_lifecycle.toon.json. Maintained by Cursor (1003).*
