---
lupopedia.init:
  document_type: "doctrine"
  system_version: "4.0.71"

lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "doctrine"
  file_path_from_root: "lupo-docs/doctrine/SESSION_MODEL.md"
  web_path: "http://www.lupopedia.com/doctrine/SESSION_MODEL"
  system_version: "4.0.71"
  last_modified_utc: "20260312"
  channel_id: 42
  actor_id: 1003
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "doctrine"
  artifact_kind: "session_model"
  purpose: "DB-backed session authority (Model A); browser stores only session_id; identity and CSRF from lupo_sessions."
  tags: ["session", "model_a", "lupo_sessions", "doctrine", "4.0.71"]

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/database/lupopedia/tables/active/lupo_sessions.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/SESSION_RECONCILIATION_DOCTRINE.md", type: "references", weight: 1.0 }
    - { to: "lupo-database/lupopedia/content/lupo-app/auth/Session.php", type: "references", weight: 0.9 }

lupopedia.footer:
  version: "4.0.71"
  last_verified: "20260312"
  last_verified_by: "cursor"
  next_action:
    - "Review Session class and lupo_sessions table doc for alignment"
    - "Validate session rotation and CSRF paths in bootstrap"
    - "Audit any remaining $_SESSION usage for Model A compliance"
---
# file: Session Model (Model A) — session: L-LUPO-ROOT-CURSOR — delegation: cursor:root — web_path: http://www.lupopedia.com/doctrine/SESSION_MODEL

# Session Model (Model A — DB-Backed Sessions)

**Doctrine:** DB-backed session authority. The browser stores only the session identifier; all protected identity and session data lives in the database. No identity in PHP `$_SESSION`.

## Principles

1. **Browser stores only `session_id`**  
   The session cookie holds only the session identifier. No signed payload, no JWT, no serialized user data in the cookie or in PHP session storage.

2. **All protected data in the database**  
   Identity and session state are stored in `lupo_sessions`:
   - `actor_id`
   - Roles / permissions (resolved from actor, not stored in session row)
   - MFA state (if applicable)
   - Federation node
   - IP hash, UA hash (binding)
   - CSRF token
   - Last activity

3. **Never use `$_SESSION['actor_id']`**  
   Identity must always be resolved from the database via the Session class.

4. **Resolve identity via Session**  
   ```php
   $session = Session::loadById($db, session_id());
   if ($session) {
       $actor_id = $session->actor_id;
   }
   ```

5. **Session revocation is DB-driven**  
   Revoking a session means deleting or invalidating the row in `lupo_sessions`. No signed tokens to revoke; the DB is the source of truth.

6. **Session rotation on login**  
   On login, issue a new session id and create a new row (or update the row with the new id). Old session id is no longer valid.

7. **No signed session payloads**  
   Do not sign or HMAC session data. Do not use JWT or token signing for web session identity. The database row is the authority.

8. **No JWT-style tokens for web sessions**  
   Web sessions are cookie + DB only. API tokens are a separate mechanism (e.g. `lupo_api_tokens`).

9. **DB is the canonical source of truth**  
   Session validity, actor_id, and CSRF token are read from `lupo_sessions`. Application code must use the Session class for all session read/write.

## Table: `lupo_sessions`

- `session_id` — Primary key; stored in cookie.
- `actor_id` — Identity; always read from DB.
- `federation_node_id` — Federation node for the session.
- `ip_hash` — Hash of client IP (binding).
- `ua_hash` — Hash of User-Agent (binding).
- `csrf_token` — CSRF token stored in DB, not in PHP session.
- `last_activity_ymdhis` — Last activity (BIGINT UTC YYYYMMDDHHIISS).
- `created_ymdhis` — Creation time (BIGINT UTC).
- `updated_ymdhis` — Last update (BIGINT UTC).

All timestamps are BIGINT UTC; no TIMESTAMP/DATETIME. No `session_data`, no serialized payload, no stored procedures or triggers.

## Session class API

- **`Session::loadById($db, $session_id)`** — Load session from DB; returns Session instance with `actor_id`, `csrf_token`, etc., or `null`.
- **`Session::create($db, $actor_id)`** — Create new session row; session rotation on login; returns Session instance.
- **`$session->rotate()`** — Rotate to a new session id (new row, old row removed).
- **`$session->destroy()`** — Delete session row and clear cookie.
- **`$session->touch()`** — Update `last_activity_ymdhis` and `updated_ymdhis`.

## References

- `lupo-docs/database/lupopedia/tables/active/lupo_sessions.md` — Table documentation.
- `lupo-database/lupopedia/content/lupo-app/auth/Session.php` — Session class implementation.
