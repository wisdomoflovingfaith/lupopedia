---
lupopedia.headers:
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  lupopedia.version: "1.0"
  lupopedia.schema: "cursor_rule"
  file_path_from_root: "lupo-rules/root/php-5-3-compatibility.md"
  web_path: "http://www.lupopedia.com/rules/root/php-5-3-compatibility"
  last_modified_utc: "20260310"
  system_version: "4.0.68"
  rule_name: "PHP 5.3+ Compatibility"
  rule_type: "constraint"
  artifact_type: "rule"
  artifact_kind: "cursor_doctrine"
  purpose: "All Lupopedia core code must run on PHP 5.3 through 8.1+; no modern-only syntax"
  tags: ["cursor", "php", "compatibility", "doctrine"]
  source_path: ".cursor/rules/php-5-3-compatibility.mdc"

lupopedia.footer:
  version: "4.0.68"
  last_verified: "20260310"
  last_verified_by: "wolfie"
---
# file: Rule — PHP 5.3+ Compatibility — session: L-LUPO-WOLFIE — delegation: wolfie:root — web_path: http://www.lupopedia.com/rules/root/php-5-3-compatibility

# PHP 5.3+ Compatibility (MANDATORY)

All code must run on **PHP 5.3 through PHP 8.1+**. Shared hosting and minimal hosting doctrine require it.

## Forbidden syntax

- **Null coalescing (`??`)** — Use `isset($x) ? $x : $default` instead.
- **Short array (`[]`)** — Use `array()` only.
- **Arrow functions** — Not available in PHP 5.3.
- **Typed properties / return types / parameter types** — PHP 7+; do not use in core paths.
- **Union types / enums** — PHP 8+; do not use.
- **`declare(strict_types=1)`** — Do not use in core.
- **Traits** — Avoid unless explicitly instructed (PHP 5.4+).
- **`session_set_cookie_params([...])`** — Array form is PHP 7.3+; use the 5-argument form for PHP 5.3.

## Required patterns

- **Arrays:** Always `array(...)`, never `[...]`. Cursor must NEVER generate short array syntax `[]` in any new or edited code.
- **Default / missing keys:** `isset($arr['key']) ? $arr['key'] : $default`, never `$arr['key'] ?? $default`.
- **Session cookie params (PHP 5.3):** `session_set_cookie_params($lifetime, $path, $domain, $secure, $httponly);` — no `samesite` (PHP 7.3+).

## Namespace doctrine

- **Lupopedia core** (entry points, lupo-includes, admin, themes) must **not** use namespaces unless explicitly instructed.
- Third-party or explicitly namespaced libraries are the only exception.

## Where this applies

- All files in `lupo-includes/`, `admin.php`, `index.php`, theme layouts, and any file included in the main request path.
- When editing existing code in those paths, fix any `??` and `[]` to be PHP 5.3-compatible.

This rule is permanent.
