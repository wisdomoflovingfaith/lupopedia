---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "database_table"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_sessions.md"
  system_version: "4.0.71"
  channel_id: 1
  actor_id: 102
  last_modified_utc: "20260312"
  artifact_type: "table_documentation"
  artifact_kind: "database_table"
  purpose: "DB-backed session authority (Model A). Web and API session storage; identity from lupo_sessions."
  traits: ["canonical", "session", "auth", "cursor_domain", "v4.0.71"]
  tags: ["database", "sessions", "auth", "identity"]
  lupo_agent: "cursor"

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/toons/lupo_sessions.toon.json", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_actors.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_session_events.md", type: "references", weight: 0.8 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_session_recovery.md", type: "references", weight: 0.7 }

lupopedia.footer:
  last_verified: "20260312"
  last_verified_by: "cursor"
---
# file: lupo_sessions (table) — web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_sessions

# Table: lupo_sessions

## Table Overview

- **Purpose:** DB-backed session authority (Model A). Stores only session id, actor_id, federation node, ip_hash, ua_hash, csrf_token, and timestamps. Browser stores only session_id; all identity is resolved from this table via the Session class. No session payload, no signed tokens, no JWT. All session read/write must go through `App\Auth\Session` using PDO_DB.
- **Category:** Session / Authentication
- **Status:** Active
- **Version introduced:** 4.0.0

## Column Documentation

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| session_id | varchar(255) | No | — | Primary key. Session identifier. |
| federation_node_id | bigint | No | 1 | Federation node. |
| actor_id | bigint | No | 0 | Actor owning the session. |
| channel_id | bigint | No | 1 | Channel context. |
| ip_address | varchar(45) | No | '' | Client IP. |
| user_agent | varchar(255) | No | '' | User-Agent. |
| device_id | varchar(100) | Yes | — | Device identifier. |
| device_type | varchar(64) | Yes | — | Device type (e.g. desktop). |
| auth_method | varchar(30) | Yes | — | Auth method (e.g. password). |
| auth_provider | varchar(50) | Yes | — | Auth provider name. |
| security_level | varchar(64) | No | 'medium' | Security level. |
| name_key | varchar(100) | Yes | — | Optional name key. |
| is_named | tinyint | No | 0 | Named session flag. |
| is_authenticated | tinyint | No | 0 | Authenticated flag. |
| is_active | tinyint | No | 1 | Active flag. |
| is_expired | tinyint | No | 0 | Expired flag. |
| is_revoked | tinyint | No | 0 | Revoked flag. |
| session_data | — | — | — | Removed in Model A; no session payload. |
| system_context | varchar(50) | Yes | — | System context. |
| metadata | json | Yes | — | Optional metadata. |
| login_ymdhis | bigint | Yes | — | Login timestamp. |
| last_seen_ymdhis | bigint | No | — | Last activity timestamp. |
| expires_ymdhis | bigint | Yes | — | Expiry timestamp. |
| created_ymdhis | bigint | No | 0 | Creation timestamp. |
| updated_ymdhis | bigint | No | — | Last update timestamp. |
| is_deleted | tinyint | No | 0 | Soft-delete flag. |
| deleted_ymdhis | bigint | Yes | — | Soft-delete timestamp. |

## Relationships

- **Logical references:** `actor_id` → lupo_actors; `channel_id` → lupo_channels; `federation_node_id` → federation node registry.
- **Inbound:** Session handler, auth guard, session events, session recovery.
- **Join patterns:** Lookup by `session_id`; cleanup by `(is_deleted, last_seen_ymdhis)`; by actor_id, device_id, expires_ymdhis.

## Usage Notes

- **Doctrine:** All session access via `App\Auth\Session` and PDO_DB; no raw session queries elsewhere.
- **Timestamps:** BIGINT YYYYMMDDHHIISS UTC. Do not use DB DEFAULT CURRENT_TIMESTAMP.
- **Indexes:** actor, cleanup (is_deleted, last_seen_ymdhis), created, device, domain, expires, last_seen, security, status (is_active, is_expired, is_revoked).
