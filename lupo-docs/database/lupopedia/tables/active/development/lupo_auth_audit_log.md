---
lupopedia.headers:
  lupopedia.version: "4.0.77"
  lupopedia.schema: "database_table"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/development/lupo_auth_audit_log.md"
  system_version: "4.0.77"
  namespace: "auth"
  channel_id: 42
  actor_id: 106
  last_modified_utc: "20260316"
  artifact_type: "table_documentation"
  purpose: "Documentation for lupo_auth_audit_log table - records authentication events (login, logout, failure) with IP and user_agent"
  mood_rgb: "4169E1"
  artifact_kind: "table"
  traits: ["development", "auth", "audit", "security", "v4.0.77"]
  tags: ["database", "auth", "audit", "security", "logging", "development"]
  lupo_agent: "zencoder"
  table_primary_key: "auth_audit_log_id"
  lupo_auth_audit_log.auth_audit_log_id: "BIGINT NOT NULL primary key"
  lupo_auth_audit_log.user_id: "BIGINT — references lupo_auth_users.user_id (no FK)"
  lupo_auth_audit_log.crafty_operator_id: "INT — legacy Crafty Syntax operator ID for migrated records"
  lupo_auth_audit_log.event_type: "VARCHAR(50) NOT NULL — event class (login_success, login_failure, logout, password_reset, etc.)"
  lupo_auth_audit_log.system_context: "VARCHAR(50) NOT NULL — subsystem that generated the event (web, api, cli, admin)"
  lupo_auth_audit_log.ip_address: "VARCHAR(45) — client IP address (supports IPv4 and IPv6)"
  lupo_auth_audit_log.user_agent: "TEXT — HTTP User-Agent string from the request"
  lupo_auth_audit_log.event_data: "JSON — structured payload with event-specific details"
  lupo_auth_audit_log.success: "TINYINT NOT NULL DEFAULT 1 — 1 = success, 0 = failure"
  lupo_auth_audit_log.error_message: "TEXT — error description for failed events"
  lupo_auth_audit_log.created_at: "BIGINT — YYYYMMDDHHIISS UTC timestamp of event"
  lupo_auth_audit_log.updated_at: "BIGINT — YYYYMMDDHHIISS UTC last-update timestamp"
  table_engine: "InnoDB"
  table_charset: "utf8mb4"
  table_indexes: ["lupo_auth_audit_log_idx_user_id", "lupo_auth_audit_log_idx_crafty_operator_id", "lupo_auth_audit_log_idx_event_type", "lupo_auth_audit_log_idx_system_context", "lupo_auth_audit_log_idx_success", "lupo_auth_audit_log_idx_created_at"]
  doctrine_note: "No database foreign keys; referential integrity enforced in application code."

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_auth_users.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_sessions.md", type: "references", weight: 0.8 }
    - { to: "lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql", type: "schema_reference", weight: 0.9 }

lupopedia.footer:
  version: "4.0.77"
  last_verified: "20260316"
  last_verified_by: "zencoder"
---

# Table: lupo_auth_audit_log

## Table Overview

- **Purpose:** Immutable audit trail for authentication events in Lupopedia. Each row records a single auth event — login success/failure, logout, password reset, token usage — with the actor, IP address, user agent, system context, and any error detail. Provides security teams with a tamper-evident login history per user.
- **Category:** Auth / Security / Audit
- **Status:** Development (schema defined in `development/` TOON; used alongside `lupo_auth_users`)
- **Version introduced:** 4.0.x (development)

## Where This Table Is Used

- **AuthService login/logout:** `app/Services/AuthService.php` writes a row on every login attempt (success and failure), logout event, and token refresh. The `event_type`, `success`, and `error_message` fields capture the outcome.
- **Security monitoring and alerting:** Background jobs scan this table for repeated `login_failure` events from the same `ip_address` or `user_id` within a rolling time window to trigger account lockouts or rate-limiting.
- **Admin security dashboard:** The admin panel queries recent rows filtered by `user_id` or `ip_address` to show a user's authentication history and flag suspicious activity.
- **Crafty Syntax migration:** Legacy login events originating from the old Crafty Syntax system are imported with `crafty_operator_id` populated; `user_id` is `NULL` for those records until a mapping is resolved.
- **API access logging:** When API tokens are used, the API layer logs token-based auth events with `system_context = 'api'` so token usage can be audited independently of web logins.
- **Compliance / GDPR exports:** Auth audit logs are included in user data exports to satisfy audit trail requirements.

## Column Documentation

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| auth_audit_log_id | bigint | No | — | Primary key. Explicit BIGINT; not AUTO_INCREMENT per reserved-ID doctrine. |
| user_id | bigint | Yes | — | Logical reference to `lupo_auth_users.user_id`. NULL for legacy/migrated Crafty records. |
| crafty_operator_id | int | Yes | — | Legacy Crafty Syntax operator ID; used to link imported records before user mapping is resolved. |
| event_type | varchar(50) | No | — | Event classification: `login_success`, `login_failure`, `logout`, `password_reset`, `token_used`, etc. |
| system_context | varchar(50) | No | — | Subsystem origin: `web`, `api`, `cli`, `admin`. |
| ip_address | varchar(45) | Yes | — | Client IP address (IPv4 or IPv6). |
| user_agent | text | Yes | — | HTTP User-Agent string from the request. |
| event_data | json | Yes | — | Structured payload with event-specific details (e.g. token_id, attempted_username). |
| success | tinyint | No | `1` | `1` = successful auth event; `0` = failure. |
| error_message | text | Yes | — | Human-readable error description for `success = 0` events. |
| created_at | bigint | Yes | — | Event creation timestamp in YYYYMMDDHHIISS UTC format. |
| updated_at | bigint | Yes | — | Last-update timestamp in YYYYMMDDHHIISS UTC format. |

## Indexes

| Index Name | Columns | Unique | Notes |
|-----------|---------|--------|-------|
| PRIMARY | auth_audit_log_id | Yes | Primary key |
| lupo_auth_audit_log_idx_user_id | user_id | No | Lookup auth history for a specific user |
| lupo_auth_audit_log_idx_crafty_operator_id | crafty_operator_id | No | Legacy operator ID resolution |
| lupo_auth_audit_log_idx_event_type | event_type | No | Filter by event class |
| lupo_auth_audit_log_idx_system_context | system_context | No | Filter by subsystem origin |
| lupo_auth_audit_log_idx_success | success | No | Quickly find failed events |
| lupo_auth_audit_log_idx_created_at | created_at | No | Time-range queries (recent events) |

## Usage Patterns

### Record a login attempt
```php
$db = DatabaseFactory::getConnection();
$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$now = (int) gmdate('YmdHis');
$db->query(
    "INSERT INTO {$prefix}auth_audit_log
     (auth_audit_log_id, user_id, event_type, system_context, ip_address, user_agent, success, error_message, created_at, updated_at)
     VALUES (:id, :uid, :type, :ctx, :ip, :ua, :ok, :err, :ts, :ts)",
    ['id' => $logId, 'uid' => $userId, 'type' => 'login_success', 'ctx' => 'web',
     'ip' => $_SERVER['REMOTE_ADDR'], 'ua' => $_SERVER['HTTP_USER_AGENT'],
     'ok' => 1, 'err' => null, 'ts' => $now]
);
```

### Count recent login failures for an IP
```php
$db = DatabaseFactory::getConnection();
$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$since = (int) gmdate('YmdHis', strtotime('-15 minutes'));
$count = $db->fetchValue(
    "SELECT COUNT(*) FROM {$prefix}auth_audit_log
     WHERE ip_address = :ip AND success = 0 AND event_type = 'login_failure' AND created_at >= :since",
    ['ip' => $ipAddress, 'since' => $since]
);
```

## Doctrine Notes

- **No foreign keys.** `user_id` logically references `lupo_auth_users.user_id` but no DB-level FK per database-logic-prohibition-doctrine.
- **Append-only pattern.** Auth audit records must never be modified after creation; `updated_at` tracks metadata corrections only.
- **Timestamps:** `created_at` and `updated_at` are BIGINT UTC YYYYMMDDHHIISS; never use `CURRENT_TIMESTAMP` or `ON UPDATE`.
- **crafty_operator_id:** Populated only for legacy Crafty Syntax migrated records; should be NULL for all new Lupopedia auth events.
- **Status:** Development table; not present in `install_new_lupopedia.sql` as of 4.0.77. Schema defined in `lupo-docs/database/lupopedia/tables/active/development/lupo_auth_audit_log.toon`.
