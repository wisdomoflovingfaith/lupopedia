# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) — see http://www.lupopedia.com/FLARE
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-database/lupopedia/channels/channel_id/0/initialize_readme.md"
  federation_node_id: 0
  web_path: "http://www.lupopedia.com/initialize_readme"
  last_updated_utc: "20260301121500"
  system_version: "4.0.53"
  channel_id: 0
  actor_id: 1006
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "guide"
  purpose: "Channel startup logging and lifecycle tracking schema summary"
  dialog_message: "Updated to include modern startup lifecycle tracking terminology"
  mood_vector: "4169E1"
  traits: ["channel_startup", "lifecycle", "schema", "node_0"]
  tags: ["channel_startup", "startup_log", "startup_lifecycle", "startup_detail", "toons"]
  lupo_agent: "gemini-cli"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/toons/lupo_channel_boot_log.toon.json", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-docs/toons/lupo_channel_boot_detail.toon.json", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-docs/toons/lupo_channel_boot_lifecycle.toon.json", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-docs/toons/lupo_channel_boot_detail_lifecycle.toon.json", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-docs/toons/lupo_channels.toon.json", type: "schema_reference", weight: 0.9 }
    - { to: "lupo-docs/toons/lupo_channel_state.toon.json", type: "schema_reference", weight: 0.9 }
    - { to: "lupo-docs/FLARE_HEADERS_COMPLETE_REFERENCE.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/doctrine/FLARE/FLARE_DOCTRINE.md", type: "references", weight: 1.0 }
  semantic_tags: ["channel_startup", "lifecycle_tracking", "schema_documentation", "node_0"]

lupopedia.footer:
  version: "4.0.53"
  last_verified: "20260301"
  last_verified_by: "gemini-cli"
---

# Channel Startup & Initialization Tables (Node 0)

## Scope
This document summarizes the channel startup logging and initialization tracking tables defined by TOON schema. It covers both the standard startup logs and the modern lifecycle tracking system, ensuring all operations align with the TOON declarations.

## Standard Startup System

### Table: lupo_channel_boot_log
Purpose: High-level tracking of a single startup session.

Primary key: `boot_id`

Fields:
| Column | Type | Notes |
| --- | --- | --- |
| `boot_id` | bigint | Primary key. |
| `actor_id` | bigint | Actor that initiated the boot. |
| `session_id` | varchar(64) | Session identifier. |
| `boot_start_time` | bigint | UTC timestamp (`YYYYMMDDHHIISS`). Start of initialization. |
| `boot_end_time` | bigint | UTC timestamp (`YYYYMMDDHHIISS`). End of initialization. |
| `boot_status` | varchar(64) | Status, default `started`. |
| `channels_loaded` | int | Count of successfully loaded channels. |
| `total_channels` | int | Total channels targeted. |
| `error_details` | json | JSON error payload. |
| `performance_metrics` | json | Performance metrics JSON. |
| `created_ymdhis` | bigint | Row creation timestamp. |

### Table: lupo_channel_boot_detail
Purpose: Granular tracking of individual channel loading within a startup session.

Primary key: `detail_id`

Fields:
| Column | Type | Notes |
| --- | --- | --- |
| `detail_id` | bigint | Primary key. |
| `boot_id` | bigint | Link to `lupo_channel_boot_log`. (Startup Session ID) |
| `channel_id` | bigint | Targeted channel ID. |
| `load_start_time` | bigint | UTC timestamp (`YYYYMMDDHHIISS`). |
| `load_end_time` | bigint | UTC timestamp (`YYYYMMDDHHIISS`). |
| `load_status` | varchar(64) | Status, default `started`. |
| `content_items_loaded` | int | Success count. |
| `total_content_items` | int | Expected count. |
| `load_duration_ms` | int | Duration in milliseconds. |
| `error_message` | text | Error details. |
| `created_ymdhis` | bigint | Row creation timestamp. |

---

## Modern Lifecycle Tracking System

### Table: lupo_channel_boot_lifecycle
Purpose: Robust tracking of channel lifecycle events, supporting multiple types of startup (partial, full, recovery).

Primary key: `lifecycle_id` (AUTO_INCREMENT)

Fields:
| Column | Type | Notes |
| --- | --- | --- |
| `lifecycle_id` | bigint | Primary key (AUTO_INCREMENT). |
| `channel_id` | bigint | Target channel ID. |
| `actor_id` | bigint | Initiating actor. |
| `session_id` | varchar(64) | Active session identifier. |
| `lifecycle_start_time` | bigint | UTC timestamp (`YYYYMMDDHHIISS`). |
| `lifecycle_end_time` | bigint | UTC timestamp (`YYYYMMDDHHIISS`). |
| `lifecycle_status` | varchar(64) | Status, default `started`. |
| `lifecycle_type` | varchar(64) | Type of lifecycle (e.g., `full`, `delta`). |
| `total_channels` | int | Total channels in scope. |
| `channels_processed` | int | Counter for processed channels. |
| `channels_successful` | int | Counter for success. |
| `channels_failed` | int | Counter for failures. |
| `lifecycle_duration_ms` | int | Duration in ms. |
| `error_details` | json | Comprehensive JSON error log. |
| `performance_metrics` | json | Detailed performance metrics. |
| `created_ymdhis` | bigint | Row creation timestamp. |

### Table: lupo_channel_boot_detail_lifecycle
Purpose: Detailed tracking of individual channel events within a specific lifecycle session.

Primary key: `detail_lifecycle_id` (AUTO_INCREMENT)

Fields:
| Column | Type | Notes |
| --- | --- | --- |
| `detail_lifecycle_id` | bigint | Primary key (AUTO_INCREMENT). |
| `lifecycle_id` | bigint | Link to `lupo_channel_boot_lifecycle`. |
| `channel_id` | bigint | Target channel ID. |
| `detail_start_time` | bigint | UTC timestamp (`YYYYMMDDHHIISS`). |
| `detail_end_time` | bigint | UTC timestamp (`YYYYMMDDHHIISS`). |
| `detail_status` | varchar(64) | Detail status, default `started`. |
| `content_items_loaded` | int | Items successfully loaded. |
| `total_content_items` | int | Expected items. |
| `detail_duration_ms` | int | Duration in ms. |
| `error_message` | text | Specific error message. |
| `created_ymdhis` | bigint | Row creation timestamp. |

---

## Key Differences and Integration
- **Startup vs. Lifecycle**: The standard startup system (`lupo_channel_boot_log`) is general-purpose for whole-node initialization. The Lifecycle system (`lupo_channel_boot_lifecycle`) provides more robust state management and type-specific handlers (full vs. partial startup).
- **Primary Keys**: Note that the lifecycle tables utilize `AUTO_INCREMENT` keys in their schema, while the standard startup logs use explicitly managed `bigint` IDs.
- **JSON Fields**: Lifecycle tables rely heavily on JSON fields for extensible error reporting and performance monitoring.

## Timestamp Doctrine
All timestamps MUST be `BIGINT` in `YYYYMMDDHHIISS` UTC format.
- **PHP SET**: `gmdate('YmdHis')`
- **NEVER** use database-side `NOW()` or `CURRENT_TIMESTAMP`.

## Relationship Protocol
All links (`boot_id` → `boot_id`, `lifecycle_id` → `lifecycle_id`) are application-enforced. In accordance with Lupopedia doctrine, **no database foreign keys or triggers** are used.

## References
- `lupo-docs/toons/lupo_channel_boot_log.toon.json`
- `lupo-docs/toons/lupo_channel_boot_detail.toon.json`
- `lupo-docs/toons/lupo_channel_boot_lifecycle.toon.json`
- `lupo-docs/toons/lupo_channel_boot_detail_lifecycle.toon.json`
- `lupo-docs/FLARE_HEADERS_COMPLETE_REFERENCE.md`
