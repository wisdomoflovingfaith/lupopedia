---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "database_table"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_banned_actors.md"
  system_version: "4.0.70"
  channel_id: 1
  actor_id: 102
  last_modified_utc: "20260312"
  artifact_type: "table_documentation"
  purpose: "Ban list: actors and optionally IPs, with reason and ban metadata"
  mood_rgb: "4169E1"
  traits: ["canonical", "acl", "security", "cursor_domain", "v4.0.70"]
  tags: ["database", "bans", "access_control", "security"]
  lupo_agent: "cursor"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/toons/lupo_banned_actors.toon.json", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_actors.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_bans_log.md", type: "references", weight: 0.7 }

lupopedia.footer:
  last_verified: "20260312"
  last_verified_by: "cursor"
---

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
