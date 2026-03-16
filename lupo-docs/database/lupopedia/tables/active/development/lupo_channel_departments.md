---
lupopedia.headers:
  lupopedia.version: "4.0.77"
  lupopedia.schema: "database_table"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/development/lupo_channel_departments.md"
  system_version: "4.0.77"
  namespace: "channel"
  channel_id: 42
  actor_id: 106
  last_modified_utc: "20260316"
  artifact_type: "table_documentation"
  purpose: "Documentation for lupo_channel_departments table - junction mapping channels to departments"
  mood_rgb: "4169E1"
  artifact_kind: "table"
  traits: ["development", "channel_system", "departments", "junction", "v4.0.77"]
  tags: ["database", "channels", "departments", "junction", "development"]
  lupo_agent: "zencoder"
  table_primary_key: "channel_department_id"
  lupo_channel_departments.channel_department_id: "BIGINT NOT NULL primary key"
  lupo_channel_departments.channel_id: "BIGINT NOT NULL reference to lupo_channels.channel_id"
  lupo_channel_departments.department_id: "BIGINT NOT NULL reference to lupo_departments.department_id"
  lupo_channel_departments.created_ymdhis: "BIGINT NOT NULL DEFAULT 0 YYYYMMDDHHIISS UTC creation timestamp"
  table_engine: "InnoDB"
  table_charset: "utf8mb4"
  table_indexes: ["PRIMARY", "lupo_channel_departments_idx_channel", "lupo_channel_departments_idx_department", "lupo_channel_departments_unq_channel_department"]
  doctrine_note: "No database foreign keys; referential integrity enforced in application code."

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_channels.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_departments.md", type: "references", weight: 1.0 }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "schema_reference", weight: 0.9 }

lupopedia.footer:
  version: "4.0.77"
  last_verified: "20260316"
  last_verified_by: "zencoder"
---

# Table: lupo_channel_departments

## Table Overview

- **Purpose:** Junction table that assigns departments to channels. Enables a many-to-many relationship between `lupo_channels` and `lupo_departments`. A channel may serve multiple departments, and a department may be active across multiple channels.
- **Category:** Channel System / Departments / Junction
- **Status:** Development (not yet in canonical install SQL; defined in `development/` TOON only)
- **Version introduced:** 4.0.x (development)

## Where This Table Is Used

- **Department routing:** When a visitor or actor joins a channel, the system can use this table to determine which departments are active/available for that channel — enabling department-based routing and operator assignment.
- **Channel configuration:** Admin UI or service logic reads this table to populate the list of departments available within a given channel context.
- **Operator assignment:** Combined with `lupo_actor_departments`, allows operator/agent routing based on channel+department affiliation.
- **Crafty Syntax upgrade path:** Replaces the implicit channel↔department binding from legacy `livehelp_operator_departments` and `livehelp_departments` tables during the 3.7.5 → 4.0.x upgrade.

## Column Documentation

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| channel_department_id | bigint | No | — | Primary key. Explicit BIGINT; not AUTO_INCREMENT per reserved-ID doctrine. |
| channel_id | bigint | No | — | Reference to `lupo_channels.channel_id`. The channel this department assignment belongs to. |
| department_id | bigint | No | — | Reference to `lupo_departments.department_id`. The department assigned to this channel. |
| created_ymdhis | bigint | No | `0` | Creation timestamp in YYYYMMDDHHIISS UTC format. Set in application code via `gmdate('YmdHis')`. |

## Indexes

| Index Name | Columns | Unique | Notes |
|-----------|---------|--------|-------|
| PRIMARY | channel_department_id | Yes | Primary key |
| lupo_channel_departments_unq_channel_department | channel_id, department_id | Yes | Prevents duplicate channel-department mappings |
| lupo_channel_departments_idx_channel | channel_id | No | Fast lookup of all departments for a channel |
| lupo_channel_departments_idx_department | department_id | No | Fast lookup of all channels for a department |

## Usage Patterns

### Get all departments for a channel
```php
$db = DatabaseFactory::getConnection();
$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$rows = $db->fetchAll(
    "SELECT cd.department_id, d.department_name
     FROM {$prefix}channel_departments cd
     JOIN {$prefix}departments d ON d.department_id = cd.department_id
     WHERE cd.channel_id = :channel_id",
    ['channel_id' => $channelId]
);
```

### Assign a department to a channel
```php
$db = DatabaseFactory::getConnection();
$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$now = gmdate('YmdHis');
$db->query(
    "INSERT IGNORE INTO {$prefix}channel_departments
     (channel_department_id, channel_id, department_id, created_ymdhis)
     VALUES (:id, :channel_id, :dept_id, :ts)",
    ['id' => $newId, 'channel_id' => $channelId, 'dept_id' => $deptId, 'ts' => $now]
);
```

### Check if department is assigned to channel
```php
$row = $db->fetchRow(
    "SELECT channel_department_id FROM {$prefix}channel_departments
     WHERE channel_id = :channel_id AND department_id = :dept_id",
    ['channel_id' => $channelId, 'dept_id' => $deptId]
);
$isAssigned = (bool) $row;
```

## Doctrine Notes

- **No foreign keys.** `channel_id` and `department_id` logically reference their parent tables but no DB-level FKs are created (see database-logic-prohibition-doctrine).
- **Timestamps:** `created_ymdhis` is BIGINT UTC YYYYMMDDHHIISS; never use `CURRENT_TIMESTAMP`.
- **Unique composite constraint** `(channel_id, department_id)` prevents duplicates; application code should use `INSERT IGNORE` or handle duplicates explicitly.
- **No soft delete:** This junction table does not use `is_deleted`; remove rows explicitly when a department is unassigned from a channel.
- **Status:** Development table; not present in `install_new_lupopedia.sql` as of 4.0.77. Schema defined in `lupo-docs/database/lupopedia/tables/active/development/lupo_channel_departments.toon`.
