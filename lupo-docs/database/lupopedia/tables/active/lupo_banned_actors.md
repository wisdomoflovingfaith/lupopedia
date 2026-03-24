---
lupopedia.headers:
  lupopedia.schema: database_table
  file_path_from_root: lupo-docs/database/lupopedia/tables/active/lupo_banned_actors.md
  web_path: '[lupo_banned_actors](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_banned_actors)'
  last_modified_utc: '20260317'
  channel_id: 42
  actor_id: 102
  actor_name: cursor
  delegation_chain: cursor:root
  artifact_type: table_documentation
  artifact_kind: table
  namespace: core
  purpose: 'Ban list: actors and optionally IPs, with reason and ban metadata'
  tags:
  - database
  - table
  - core
  when_updated: '20260324174654'
lupopedia.edges:
  comment: Snapshot of edges for lupo_banned_actors table doc at 4.0.79 (grounded
    by repo search; non-exhaustive).
  meta: php_hits=3 python_hits=3
  outbound_edges:
  - to: database.table.lupo_banned_actors
    type: DEFINES_SCHEMA_FOR
    weight: 1.0
  - to: lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql
    type: schema_reference
    weight: 1.0
  - to: install_wizard_classes.php
    type: USED_IN_PHP
    weight: 0.6
  - to: lupo-includes/classes/ANUBIS_Resolver.php
    type: USED_IN_PHP
    weight: 0.9
  - to: lupo-includes/functions/ban_gate.php
    type: USED_IN_PHP
    weight: 0.9
  - to: analyze_unused_tables.py
    type: USED_IN_PYTHON
    weight: 0.5
  - to: lupo-scripts/rebuild_schema_from_toons.py
    type: USED_IN_PYTHON
    weight: 0.5
  - to: lupo-tools/anubis_orphan_scanner.py
    type: USED_IN_PYTHON
    weight: 0.5
lupopedia.footer:
  last_verified: '20260317000000'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
---
# file: lupo_banned_actors ? web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_banned_actors
# Table: lupo_banned_actors

## Table Overview

- **Purpose:** Access-control ban list: banned actor_id, optional IP, reason, ban timestamp, and banning actor. Used to enforce bans at login and request time.
- **Category:** Access control / Security
- **Status:** Active
- **Version introduced:** 4.0.0

## Column Documentation

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| banned_actor_id | bigint | No | — | Primary key. |
| actor_id | bigint | No | — | Banned actor (logical → lupo_actors.actor_id). |
| ip_address | varchar(45) | Yes | — | Optional IP ban. |
| reason | varchar(500) | No | — | Ban reason. |
| banned_ymdhis | bigint | No | — | When ban took effect. |
| banned_by_actor_id | bigint | Yes | — | Actor who applied ban. |
| created_ymdhis | bigint | No | 0 | Row creation. |
| updated_ymdhis | bigint | No | — | Last update. |
| is_deleted | tinyint | No | 0 | Soft-delete (unban). |
| deleted_ymdhis | bigint | Yes | — | Soft-delete timestamp. |

## Relationships

- **Logical references:** actor_id, banned_by_actor_id → lupo_actors.actor_id.
- **Inbound:** Auth and ACL checks query this table; lupo_bans_log may record ban events.
- **Join patterns:** By actor_id, ip_address, is_deleted.

## Usage Notes

- **Indexes:** actor_id, ip_address, is_deleted.
- **Timestamps:** BIGINT YYYYMMDDHHIISS UTC.
- **Queries:** Filter `is_deleted = 0` for active bans.
