---
lupopedia.headers:
  lupopedia.version: "4.0.79"
  lupopedia.schema: "database_table"
  system_version: "4.0.79"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_bans_log.md"
  web_path: "[lupo_bans_log](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_bans_log)"
  last_modified_utc: "20260317"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "table_documentation"
  artifact_kind: "table"
  namespace: "analytics"
  purpose: "Audit log of ban events (URI, scope, actor, IP, user agent)"
  tags: ["database", "table", "analytics"]

lupopedia.edges:
  comment: "Snapshot of edges for lupo_bans_log table doc at 4.0.79 (grounded by repo search; non-exhaustive)."
  meta: "php_hits=2 python_hits=0"
  outbound_edges:
    - { to: "database.table.lupo_bans_log", type: "DEFINES_SCHEMA_FOR", weight: 1.0 }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "schema_reference", weight: 1.0 }
    - { to: "install.php", type: "USED_IN_PHP", weight: 0.6 }
    - { to: "lupo-includes/functions/ban_gate.php", type: "USED_IN_PHP", weight: 0.9 }
    - { to: "(no_python_refs_found)", type: "USED_IN_PYTHON", weight: 0.0 }

lupopedia.footer:
  version: "4.0.79"
  last_verified: "20260317"
  last_verified_by: "cursor"
---
# file: lupo_bans_log ? web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_bans_log
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
