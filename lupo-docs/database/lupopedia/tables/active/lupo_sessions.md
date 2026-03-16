---
lupopedia.headers:
  lupopedia.version: "4.0.77"
  lupopedia.schema: "database_table"
  system_version: "4.0.77"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_sessions.md"
  web_path: "[lupo_sessions](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_sessions)"
  last_modified_utc: "20260316"
  channel_id: 42
  actor_id: 102
  artifact_type: "table_documentation"
  artifact_kind: "table"
  purpose: "DB-backed session authority (Model A). Web and API session storage; identity resolved from lupo_sessions via App\\Auth\\Session."
  traits: ["canonical", "session", "auth", "v4.0.77"]
  tags: ["database", "sessions", "auth", "identity"]
  doctrine_note: "No database foreign keys; referential integrity enforced in application code. All session access via App\\Auth\\Session and PDO_DB."

lupopedia.edges:
  comment: "Snapshot of edges for lupo_sessions table doc at 4.0.77 lead pass."
  outbound_edges:
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "schema_reference", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_actors.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_channels.md", type: "references", weight: 0.8 }
    - { to: "app/Auth/Session.php", type: "referenced_by", weight: 1.0 }

lupopedia.footer:
  version: "4.0.77"
  last_verified: "20260316"
  last_verified_by: "cursor"
---
# file: lupo_sessions — session: L-LUPO-ROOT-CURSOR — web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_sessions

# Table: lupo_sessions

## Table Overview

- **Purpose:** DB-backed session authority (Model A). Stores only session id, actor_id, federation node, ip_hash, ua_hash, csrf_token, and timestamps. Browser stores only session_id; all identity is resolved from this table via the Session class. No session payload, no signed tokens, no JWT. All session read/write must go through `App\Auth\Session` using PDO_DB.
- **Category:** Session / Authentication
- **Status:** Active
- **Version introduced:** 4.0.0

## Where This Table Is Used

- **Session handler:** `App\Auth\Session` reads and writes this table exclusively for web and API session state. Lookup by `session_id`; identity (actor_id, channel_id) resolved from rows.
- **Auth guard:** Authentication and authorization logic use session rows to determine current actor, channel, and federation node.
- **Session lifecycle:** Session creation on login or anonymous visit; updates to `last_seen_ymdhis`, `expires_ymdhis`; soft delete via `is_deleted` and `deleted_ymdhis`.
- **Session events / recovery:** Related tables `lupo_session_events` and session recovery flows reference or extend session context; session_id is the join key.

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
