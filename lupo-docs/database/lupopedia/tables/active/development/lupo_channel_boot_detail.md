---
lupopedia.headers:
  lupopedia.version: "4.0.77"
  lupopedia.schema: "database_table"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/development/lupo_channel_boot_detail.md"
  system_version: "4.0.77"
  namespace: "channel"
  channel_id: 42
  actor_id: 106
  last_modified_utc: "20260316"
  artifact_type: "table_documentation"
  purpose: "Documentation for lupo_channel_boot_detail table - per-channel step details within a boot lifecycle event"
  mood_rgb: "4169E1"
  artifact_kind: "table"
  traits: ["development", "channel", "boot", "lifecycle", "v4.0.77"]
  tags: ["database", "channel", "boot", "lifecycle", "development"]
  lupo_agent: "zencoder"
  table_primary_key: "detail_id"
  lupo_channel_boot_detail.detail_id: "BIGINT NOT NULL primary key"
  lupo_channel_boot_detail.boot_id: "BIGINT NOT NULL — references lupo_channel_boot_lifecycle.boot_id (no FK)"
  lupo_channel_boot_detail.channel_id: "BIGINT NOT NULL — the channel being booted"
  lupo_channel_boot_detail.detail_start_time: "BIGINT — YYYYMMDDHHIISS UTC start of this load step"
  lupo_channel_boot_detail.detail_end_time: "BIGINT — YYYYMMDDHHIISS UTC end of this load step"
  lupo_channel_boot_detail.load_status: "VARCHAR(64) NOT NULL DEFAULT 'started' — lifecycle state (started, loading, complete, failed)"
  lupo_channel_boot_detail.content_items_loaded: "INT NOT NULL DEFAULT 0 — number of content items loaded so far"
  lupo_channel_boot_detail.total_content_items: "INT NOT NULL DEFAULT 0 — total content items expected for this channel"
  lupo_channel_boot_detail.load_duration_ms: "INT — elapsed time in milliseconds for this load step"
  lupo_channel_boot_detail.error_message: "TEXT — error detail if load_status is 'failed'"
  lupo_channel_boot_detail.created_ymdhis: "BIGINT NOT NULL DEFAULT 0 — YYYYMMDDHHIISS UTC record creation timestamp"
  table_engine: "InnoDB"
  table_charset: "utf8mb4"
  table_indexes: ["lupo_channel_boot_detail_fk_boot_detail_channel", "lupo_channel_boot_detail_idx_boot_channel", "lupo_channel_boot_detail_idx_load_status_time"]
  doctrine_note: "No database foreign keys; referential integrity enforced in application code."

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_channel_boot_lifecycle.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_channels.md", type: "references", weight: 0.9 }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "schema_reference", weight: 0.9 }

lupopedia.footer:
  version: "4.0.77"
  last_verified: "20260316"
  last_verified_by: "zencoder"
---

# Table: lupo_channel_boot_detail

## Table Overview

- **Purpose:** Records per-channel load step details within a single channel boot event. Each row tracks one channel's progress within a boot cycle: start/end timestamps, item counts, duration, load status, and any error detail. Works as a child table of `lupo_channel_boot_lifecycle`.
- **Category:** Channel / Boot / Lifecycle
- **Status:** Development (not yet in canonical install SQL; defined in `development/` TOON only)
- **Version introduced:** 4.0.x (development)

## Where This Table Is Used

- **Channel boot lifecycle tracking:** Populated by the channel startup script (`lupo-bin/channel_startup_lifecycle.php`) as each channel is loaded during a boot event. One row per channel per boot.
- **Boot progress monitoring:** Admin interfaces and CLI tooling query this table (joined with `lupo_channel_boot_lifecycle` on `boot_id`) to display real-time or historical boot progress: which channels loaded, how long each took, and which failed.
- **Error diagnostics:** When `load_status = 'failed'`, the `error_message` column surfaces the failure reason without requiring log file inspection.
- **Performance analysis:** The `load_duration_ms` and item-count columns allow operators to identify slow-loading channels or abnormal item counts across boot cycles.
- **Coordination status files:** The `lupo-database/coordination/` status artifacts may reference boot detail records to confirm that specific channels initialized cleanly during a version transition.

## Column Documentation

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| detail_id | bigint | No | — | Primary key. Explicit BIGINT; not AUTO_INCREMENT per reserved-ID doctrine. |
| boot_id | bigint | No | — | Logical reference to `lupo_channel_boot_lifecycle.boot_id`. Groups all channel details within one boot event. |
| channel_id | bigint | No | — | The channel being loaded. Logical reference to `lupo_channels.channel_id`. |
| detail_start_time | bigint | Yes | — | YYYYMMDDHHIISS UTC timestamp when this channel's load step began. |
| detail_end_time | bigint | Yes | — | YYYYMMDDHHIISS UTC timestamp when this channel's load step completed or failed. |
| load_status | varchar(64) | No | `'started'` | Lifecycle state: `started`, `loading`, `complete`, `failed`. |
| content_items_loaded | int | No | `0` | Count of content items successfully loaded for this channel so far. |
| total_content_items | int | No | `0` | Total expected content items for this channel in this boot. |
| load_duration_ms | int | Yes | — | Elapsed time in milliseconds for this load step. |
| error_message | text | Yes | — | Human-readable error detail; populated when `load_status = 'failed'`. |
| created_ymdhis | bigint | No | `0` | Record creation timestamp in YYYYMMDDHHIISS UTC format. |

## Indexes

| Index Name | Columns | Unique | Notes |
|-----------|---------|--------|-------|
| PRIMARY | detail_id | Yes | Primary key |
| lupo_channel_boot_detail_idx_boot_channel | boot_id, channel_id | No | Composite lookup: all detail rows for a boot + channel |
| lupo_channel_boot_detail_fk_boot_detail_channel | channel_id | No | Single-column index on channel_id for channel-scoped queries |
| lupo_channel_boot_detail_idx_load_status_time | load_status, detail_start_time | No | Filter by status + time range for monitoring queries |

## Usage Patterns

### Insert a channel boot detail row
```php
$db = DatabaseFactory::getConnection();
$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$now = (int) gmdate('YmdHis');
$db->query(
    "INSERT INTO {$prefix}channel_boot_detail
     (detail_id, boot_id, channel_id, detail_start_time, load_status, content_items_loaded, total_content_items, created_ymdhis)
     VALUES (:id, :boot_id, :ch_id, :start, 'started', 0, :total, :ts)",
    ['id' => $detailId, 'boot_id' => $bootId, 'ch_id' => $channelId,
     'start' => $now, 'total' => $totalItems, 'ts' => $now]
);
```

### Mark a channel load as complete
```php
$db = DatabaseFactory::getConnection();
$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$end = (int) gmdate('YmdHis');
$db->query(
    "UPDATE {$prefix}channel_boot_detail
     SET load_status = 'complete', detail_end_time = :end,
         content_items_loaded = :loaded, load_duration_ms = :ms
     WHERE detail_id = :id",
    ['end' => $end, 'loaded' => $count, 'ms' => $durationMs, 'id' => $detailId]
);
```

### Fetch all failed channels in a boot
```php
$db = DatabaseFactory::getConnection();
$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$failures = $db->fetchAll(
    "SELECT channel_id, error_message, load_duration_ms
     FROM {$prefix}channel_boot_detail
     WHERE boot_id = :boot_id AND load_status = 'failed'",
    ['boot_id' => $bootId]
);
```

## Doctrine Notes

- **No foreign keys.** `boot_id` and `channel_id` are logical references enforced by application code only.
- **Timestamps:** `created_ymdhis`, `detail_start_time`, and `detail_end_time` are BIGINT UTC YYYYMMDDHHIISS; never use `CURRENT_TIMESTAMP`.
- **Child table relationship.** This table is a child of `lupo_channel_boot_lifecycle`; always join via `boot_id` when reading boot context.
- **Status:** Development table; not present in `install_new_lupopedia.sql` as of 4.0.77. Schema defined in `lupo-docs/database/lupopedia/tables/active/development/lupo_channel_boot_detail.toon`.
