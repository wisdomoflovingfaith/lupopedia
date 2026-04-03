---
lupopedia.headers:
  actor_id: 2
  actor_name: "lilith"
  delegation_chain: "lilith:root"
  lupopedia.version: "4.0.79"
  lupopedia.schema: "lilith_rule"
  file_path_from_root: ".lilith/rules/php-5-6-compatibility.md"
  last_modified_utc: "20260402"
  system_version: "4.0.79"
  source_path: "lupo-rules/root/php-5-6-compatibility.md"
  artifact_type: "rule"
  artifact_kind: "lilith_doctrine"
  purpose: "Lilith-specific review and dissent rule derivative"
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

