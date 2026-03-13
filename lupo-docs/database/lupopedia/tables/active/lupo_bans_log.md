---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "database_table"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_bans_log.md"
  system_version: "4.0.70"
  channel_id: 1
  actor_id: 102
  last_modified_utc: "20260312"
  artifact_type: "table_documentation"
  purpose: "Audit log of ban events (URI, scope, actor, IP, user agent)"
  mood_rgb: "4169E1"
  traits: ["canonical", "acl", "audit", "cursor_domain", "v4.0.70"]
  tags: ["database", "bans", "audit", "security"]
  lupo_agent: "cursor"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/toons/lupo_bans_log.toon.json", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_actors.md", type: "references", weight: 0.8 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_banned_actors.md", type: "references", weight: 0.7 }

lupopedia.footer:
  last_verified: "20260312"
  last_verified_by: "cursor"
---

# Table: lupo_bans_log

## Table Overview

- **Purpose:** Audit log of ban-triggering events: actor_id, URI, resolved_uri, ban_scope (e.g. router), banned_ymdhis, user_agent, ip_address. Used for security analytics and compliance.
- **Category:** Access control / Audit
- **Status:** Active
- **Version introduced:** 4.0.0

## Column Documentation

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| bans_log_id | bigint | No | auto_increment | Primary key. (TOON shows auto_increment; reserved-ID doctrine may apply per project.) |
| actor_id | bigint | No | — | Actor associated with the event. |
| uri | varchar(1024) | No | '' | Request URI. |
| resolved_uri | varchar(1024) | No | '' | Resolved/normalized URI. |
| ban_scope | varchar(64) | No | 'router' | Scope of ban (e.g. router, api). |
| banned_ymdhis | bigint | No | — | When the ban event occurred. |
| user_agent | varchar(500) | Yes | — | User-Agent. |
| ip_address | varchar(45) | Yes | — | Client IP. |

## Relationships

- **Logical references:** actor_id → lupo_actors.actor_id.
- **Inbound:** Router/security layer writes on ban-triggering events.
- **Join patterns:** By actor_id, ban_scope, banned_ymdhis.

## Usage Notes

- **Indexes:** actor_id, ban_scope, banned_ymdhis.
- **Timestamps:** banned_ymdhis is BIGINT YYYYMMDDHHIISS UTC.
- **Uncertainty:** If this table is considered governance/audit rather than auth, KIRO may claim ownership; Cursor documents as security-layer audit per assignment.
