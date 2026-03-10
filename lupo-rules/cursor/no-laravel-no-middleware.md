---
lupopedia.headers:
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  lupopedia.version: "1.0"
  lupopedia.schema: "cursor_rule"
  file_path_from_root: "lupo-rules/cursor/no-laravel-no-middleware.md"
  web_path: "http://www.lupopedia.com/rules/cursor/no-laravel-no-middleware"
  last_modified_utc: "20260310"
  system_version: "4.0.68"
  rule_name: "No Laravel, No Middleware"
  rule_type: "constraint"
  artifact_type: "rule"
  artifact_kind: "cursor_doctrine"
  purpose: "This project does NOT use Laravel or any framework middleware. Plain PHP only."
  tags: ["cursor", "laravel", "middleware", "doctrine"]
  source_path: ".cursor/rules/no-laravel-no-middleware.mdc"

lupopedia.footer:
  version: "4.0.68"
  last_verified: "20260310"
  last_verified_by: "wolfie"
---
# file: Rule — No Laravel, No Middleware — session: L-LUPO-WOLFIE — delegation: wolfie:root — web_path: http://www.lupopedia.com/rules/cursor/no-laravel-no-middleware

# No Laravel, No Middleware (PERMANENT)

This project does **not** use Laravel or any framework middleware. Enforce the following across the entire codebase.

## Forbidden

- **Laravel:** No `Illuminate\*`, Eloquent, Model, ServiceProvider, `config()`, `routes/web.php`, or Laravel-style `config/*`
- **Middleware:** No middleware system of any kind — no registration, no Kernel, no middleware stacks, no request/response wrapping
- **Framework patterns:** No `Request`, `Response`, `redirect()`, `abort()`, Laravel timestamp helpers (`now()`, `today()`), or framework lifecycle assumptions

## Required

- **Request handling:** Plain PHP only — arrays for input, explicit method calls, no framework lifecycle
- **Database:** Our PDO wrapper (e.g. `PDO_DB`), no Eloquent
- **Session:** Our `SessionHandler`
- **Auth:** Our `AuthGuard` (in `App\Auth`), not Laravel middleware
- **Timestamps:** Explicit doctrine — BIGINT UTC YmdHis, set in PHP only (e.g. `gmdate('YmdHis')`), never DB-generated or UNIX timestamps for stored values

## Refactor rules

If any file contains Laravel or middleware remnants:

1. Replace with plain PHP classes and explicit method calls.
2. Use arrays for input/output; caller sends JSON or redirects as needed.
3. Do not assume middleware runs; do not register or check for middleware.
4. Controllers and handlers use only our own architecture (PDO, SessionHandler, AuthGuard, timestamp doctrine).

This rule is permanent and applies to all future refactors.
