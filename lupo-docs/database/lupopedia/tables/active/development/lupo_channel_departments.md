---
lupopedia.headers:
  lupopedia.version: "4.0.79"
  lupopedia.schema: "database_table"
  system_version: "4.0.79"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/development/lupo_channel_departments.md"
  web_path: "[lupo_channel_departments](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_channel_departments)"
  last_modified_utc: "20260317"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "table_documentation"
  artifact_kind: "table"
  namespace: "channels"
  purpose: "Documentation for lupo_channel_departments table - departmental subdivisions within channels"
  tags: ["database", "table", "channels"]

lupopedia.edges:
  comment: "Snapshot of edges for lupo_channel_departments table doc at 4.0.79 (grounded by repo search; non-exhaustive)."
  meta: "php_hits=0 python_hits=0"
  outbound_edges:
    - { to: "database.table.lupo_channel_departments", type: "DEFINES_SCHEMA_FOR", weight: 1.0 }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "schema_reference", weight: 1.0 }
    - { to: "(no_php_refs_found)", type: "USED_IN_PHP", weight: 0.0 }
    - { to: "(no_python_refs_found)", type: "USED_IN_PYTHON", weight: 0.0 }

lupopedia.footer:
  version: "4.0.79"
  last_verified: "20260317"
  last_verified_by: "cursor"
---
# file: lupo_channel_departments ? web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_channel_departments
# Table: lupo_channel_departments

## Table Overview

- **Purpose:** Junction table defining which departments are active within which channels. Many-to-many between lupo_channels and lupo_departments. Supports channel organization, department-specific content routing, moderation segmentation, and UI grouping by department within a channel.
- **Category:** Channel System / Departments / Junction
- **Status:** Active (in install_new_lupopedia.sql)
- **Version introduced:** 4.0.x

## Where This Table Is Used

- **Channel organization:** Admin and runtime logic use this table to know which departments belong to a channel; channels can expose multiple departments for routing and display.
- **Department-specific content:** Content and help routing can be scoped by (channel_id, department_id); this table defines the valid channel–department pairs for such filtering.
- **Moderation segmentation:** Moderation and operator assignment use channel–department membership; lupo_actor_departments and operator logic reference which departments exist per channel via this table.
- **Knowledge domain partitioning:** Departments often represent knowledge domains or teams; this table partitions a channel into those domains for navigation and access.
- **UI navigation grouping:** UI can group content or threads by department within a channel using this table to list departments for the current channel.

## Relationship with lupo_channels

- **lupo_channels** has a single default department_id per channel; lupo_channel_departments extends that to many-to-many: a channel can have multiple departments, and a department can appear in multiple channels. Lookup pattern: by channel_id to list departments for a channel; by department_id to list channels that include that department.

## Relationship with content routing

- Content and dialog routing may filter by channel_id and optionally by department_id; the set of valid (channel_id, department_id) pairs is defined here. Application code joins this table when resolving department-scoped routes within a channel.

## Column Documentation

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| channel_department_id | bigint | No | — | Primary key. Reserved-ID doctrine: application supplies explicit ID; not AUTO_INCREMENT. |
| channel_id | bigint | No | — | Logical reference to lupo_channels.channel_id. |
| department_id | bigint | No | — | Logical reference to lupo_departments.department_id. |
| created_ymdhis | bigint | No | 0 | Creation timestamp in YYYYMMDDHHIISS UTC format. Set in application code. |

## Indexes

- **PRIMARY KEY:** channel_department_id
- **UNIQUE:** lupo_channel_departments_unq_channel_department (channel_id, department_id) — prevents duplicate mappings
- **INDEX:** lupo_channel_departments_idx_channel (channel_id), lupo_channel_departments_idx_department (department_id)

## Relationships

- **Logical references (no DB FKs):** channel_id → lupo_channels.channel_id; department_id → lupo_departments.department_id. Application code enforces existence of channel and department when inserting.
- **Logical hierarchy:** Channels and departments are independent entities; this table defines the allowed pairings. No soft delete in this table; remove rows to unassign a department from a channel.

## Doctrine Notes

- **No foreign keys.** All referential integrity in application code.
- **Timestamps:** created_ymdhis is BIGINT UTC YYYYMMDDHHIISS; set in PHP only.
- **Unique (channel_id, department_id):** Prevents duplicate assignments; use INSERT IGNORE or explicit duplicate handling.
- **Reserved ID:** channel_department_id is not AUTO_INCREMENT; application must supply explicit value.
