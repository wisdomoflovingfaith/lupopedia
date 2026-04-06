---
lupopedia.init:
  orchestrator_actor: "any"
  rule_set_version: "4.0.74+"
  applies_to: ["audit", "code-gen", "db-sync", "migration", "header-sync"]
  enforcement: strict

lupopedia.metadata:
  comment: "Snapshot of metadata for this file or entity at artifact creation."

lupopedia.headers:
  author:
    type: "actor"
    id: 1
    name: "wolfie"
  delegation_chain: "wolfie:root"
  lupopedia.version: "4.0.74"
  lupopedia.schema: "cursor_rule"
  file_path_from_root: "lupo-rules/root/no-laravel-no-middleware.md"
  web_path: "http://www.lupopedia.com/rules/root/no-laravel-no-middleware"
  last_modified_utc: "20260406044907"
  system_version: "4.0.74"
  rule_name: "No Laravel, No Middleware"
  rule_type: "constraint"
  artifact_type: "rule"
  artifact_kind: "cursor_doctrine"
  purpose: "This project does NOT use Laravel or any framework middleware. Plain PHP only."
  tags: ["cursor", "laravel", "middleware", "doctrine"]
  source_path: ".cursor/rules/no-laravel-no-middleware.mdc"

lupopedia.rules:
  comment: "Rule declaration and provenance block"
  declares:
    - rule_id: "ARC002"
      rule_text: "This project does NOT use Laravel or any framework middleware. Plain PHP only"
      scope: "all_agents"
      category: "architecture"
  imports: []
  overrides: []
  provenance:
    authored_by: "wolfie"
    authored_date: "20260314"
    last_reviewed_by: "antigravity"
    last_reviewed_date: "20260314"
    version: "1.0"
    status: "active"
lupopedia.footer:
  version: "4.0.74"
  last_verified: "20260313"
  last_verified_by: "wolfie"
  orchestrator: "cursor"
  next_action:
    - "Keep in sync with .cursor/rules/no-laravel-no-middleware.mdc"
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
