---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: actors/anubis/rules/php-7-4-compatibility.md
  web_path: https://www.lupopedia.com/lupopedia/actors/anubis/rules/php-7-4-compatibility.md
  status: null
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: rule
  artifact_kind: cursor_doctrine
  channel_key: null
  federation_node_id: null
  thread_key: null
  lupopedia.schema: cursor_rule
  prd_cluster: null
  title: null
  summary: null
---
# file: Rule — PHP 7.4+ Compatibility — session: L-LUPO-WOLFIE — delegation: wolfie:root — web_path: http://www.lupopedia.com/rules/root/php-7-4-compatibility

# PHP 7.4+ Compatibility (MANDATORY)

All code must run on **PHP 7.4 minimum** through **supported PHP 8.x**. No Composer or outside frameworks that are not in `includes`. No deprecated functions that will not work in PHP 8+.

## Allowed in core paths (PHP 7.4+)

- **Null coalescing (`??`)** and **null coalescing assignment (`??=`)**
- **Short array syntax (`[]`)**
- **Arrow functions** (`fn`)
- **Typed properties, parameter types, and return types** (use when they improve clarity; match surrounding file style)
- **`session_set_cookie_params` with array argument** (PHP 7.3+) including `samesite` where needed

## Forbidden syntax (PHP 8.0+ only — do not use in shared core paths)

- **Union types** (`string|int`), **mixed** as standalone if policy tightens, **`static` return type** nuances — prefer single types or docblocks until a file is explicitly modern-only
- **`match` expressions**
- **Named arguments**
- **Enums**
- **Attributes**
- **`readonly` properties** (PHP 8.1+)
- **`declare(strict_types=1)`** — do not use in core unless a file is explicitly scoped as strict

## Required patterns

- **Default / missing keys:** `??` or `isset($arr['key']) ? $arr['key'] : $default` — both acceptable
- **Arrays:** `array()` and `[]` are both acceptable; prefer consistency within a file

## Namespace doctrine

- **Lupopedia core** (entry points, includes, admin, themes) must **not** use namespaces unless explicitly instructed.
- Third-party or explicitly namespaced libraries are the only exception.

## Where this applies

- All files in `includes/`, `admin.php`, `index.php`, theme layouts, and any file included in the main request path.
- When editing existing code in those paths, preserve or apply PHP 7.4–compatible patterns and avoid PHP 8+ only features unless the file is explicitly modern-only.

This rule is permanent.
