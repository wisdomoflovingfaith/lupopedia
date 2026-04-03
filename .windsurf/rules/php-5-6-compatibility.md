---
lupopedia.init:
  file_identity: "php-5-6-compatibility.md"
  artifact_type: "windsurf_rule"
  artifact_kind: "doctrine"
  namespace: "windsurf"
  system_version: "4.0.76"
  orchestrator_actor: "windsurf"
  delegation_chain: "windsurf:captain"

lupopedia.headers:
  actor_id: 101
  actor_name: "windsurf"
  delegation_chain: "windsurf:captain"
  lupopedia.version: "4.0.76"
  lupopedia.schema: "windsurf_rule"
  file_path_from_root: ".windsurf/rules/php-5-6-compatibility.md"
  last_modified_utc: "20260402"
  system_version: "4.0.76"
  source_path: "lupo-rules/root/php-5-6-compatibility.md"
  artifact_type: "rule"
  artifact_kind: "windsurf_doctrine"
  purpose: "Windsurf-specific rule derived from canonical root rule"

lupopedia.rules:
  comment: "Rule declaration and provenance block"
  declares:
    - rule_id: "ARC003"
      rule_text: "All Lupopedia core code must run on PHP 5.6 minimum through 8.x; no deprecated PHP 8+ syntax"
      scope: "all_agents"
      category: "compatibility"
      status: "active"
  imports: []
  overrides: []
  provenance:
    authored_by: "wolfie"
    authored_date: "20260402"
    last_reviewed_by: "windsurf"
    last_reviewed_date: "20260402"
    version: "1.0"
    status: "active"
lupopedia.footer:
  version: "4.0.76"
  last_verified: "20260402"
  last_verified_by: "windsurf"
  orchestrator: "windsurf"
  next_action:
    - "Keep in sync with canonical root rules"
---

# file: Rule — PHP 5.6+ Compatibility — session: L-LUPO-WOLFIE — delegation: wolfie:root — web_path: http://www.lupopedia.com/rules/root/php-5-6-compatibility

# PHP 5.6+ Compatibility (MANDATORY)

All code must run on **PHP 5.6 minimum through PHP 8.x**. No Composer or outside frameworks that are not in `lupo-includes`. No deprecated functions that will not work in PHP 8+.

## Forbidden syntax (where not supported across 5.6–8.x)

- **Null coalescing (`??`)** — Use `isset($x) ? $x : $default` where 5.6 compatibility is required.
- **Short array (`[]`)** in core paths — Use `array()` for PHP 5.6 compatibility.
- **Arrow functions** — PHP 7.4+; avoid in shared core paths.
- **Typed properties / return types / parameter types** — PHP 7+; do not use in core paths.
- **Union types / enums** — PHP 8+; do not use.
- **`declare(strict_types=1)`** — Do not use in core.
- **`session_set_cookie_params([...])`** — Array form is PHP 7.3+; use the 5-argument form for PHP 5.6.

## Required patterns

- **Arrays:** Prefer `array(...)` in code that must run on PHP 5.6.
- **Default / missing keys:** `isset($arr['key']) ? $arr['key'] : $default` where null coalescing is not acceptable.
- **Session cookie params (5.6):** `session_set_cookie_params($lifetime, $path, $domain, $secure, $httponly);` — no `samesite` (PHP 7.3+).

## Namespace doctrine

- **Lupopedia core** (entry points, lupo-includes, admin, themes) must **not** use namespaces unless explicitly instructed.
- Third-party or explicitly namespaced libraries are the only exception.

## Where this applies

- All files in `lupo-includes/`, `admin.php`, `index.php`, theme layouts, and any file included in the main request path.
- When editing existing code in those paths, preserve or apply PHP 5.6–compatible patterns.

This rule is permanent.

