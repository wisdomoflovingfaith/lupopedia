---
lupopedia.init:
  file_identity: "no-laravel-no-middleware.md"
  artifact_type: "windsurf_rule"
  artifact_kind: "doctrine"
  namespace: "windsurf"
  system_version: "4.0.75"
  orchestrator_actor: "windsurf"
  delegation_chain: "windsurf:captain"

lupopedia.headers:
  actor_id: 101
  actor_name: "windsurf"
  delegation_chain: "windsurf:captain"
  lupopedia.version: "4.0.75"
  lupopedia.schema: "windsurf_rule"
  file_path_from_root: ".windsurf/rules/no-laravel-no-middleware.md"
  last_modified_utc: "20260314"
  system_version: "4.0.75"
  source_path: "lupo-rules/root/no-laravel-no-middleware.md"
  artifact_type: "rule"
  artifact_kind: "windsurf_doctrine"
  purpose: "Windsurf-specific rule derived from canonical root rule"

lupopedia.rules:
  comment: "Rule declaration and provenance block"
  declares:
    - rule_id: "ARC002"
      rule_text: "This project does NOT use Laravel or any framework middleware. Plain PHP only"
      scope: "all_agents"
      category: "architecture"
      status: "active"
  imports: []
  overrides: []
  provenance:
    authored_by: "wolfie"
    authored_date: "20260314"
    last_reviewed_by: "windsurf"
    last_reviewed_date: "20260314"
    version: "1.0"
    status: "active"
lupopedia.footer:
  version: "4.0.75"
  last_verified: "20260314"
  last_verified_by: "windsurf"
  orchestrator: "windsurf"
  next_action:
    - "Keep in sync with canonical root rules"
---

# file: Rule — No Laravel, No Middleware — session: L-LUPO-WOLFIE — delegation: wolfie:root — web_path: http://www.lupopedia.com/rules/root/no-laravel-no-middleware

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

