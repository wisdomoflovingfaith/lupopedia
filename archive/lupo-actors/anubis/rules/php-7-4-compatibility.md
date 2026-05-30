---
lupopedia.init:
  orchestrator_actor: "any"
  rule_set_version: "4.0.94+"
  applies_to: ["audit", "code-gen", "db-sync", "migration", "header-sync"]
  enforcement: strict

lupopedia.metadata:
  comment: "Snapshot of metadata for this file or entity at artifact creation."

lupopedia.headers:
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  lupopedia.version: "4.0.94"
  lupopedia.schema: "cursor_rule"
  file_path_from_root: "lupo-actors/anubis/rules/php-7-4-compatibility.md"
  web_path: "http://www.lupopedia.com/rules/root/php-7-4-compatibility"
  last_modified_utc: "20260406021052"
  system_version: "4.0.94"
  rule_name: "PHP 7.4+ Compatibility"
  rule_type: "constraint"
  artifact_type: "rule"
  artifact_kind: "cursor_doctrine"
  purpose: "All Lupopedia core code must run on PHP 7.4 minimum through supported 8.x; no Composer/outside frameworks in runtime; avoid PHP 8.0+ only syntax in shared core paths"
  tags: ["cursor", "php", "compatibility", "doctrine"]
  source_path: ".cursor/rules/php-7-4-compatibility.mdc"

lupopedia.rules:
  comment: "Rule declaration and provenance block"
  declares:
    - rule_id: "ARC003"
      rule_text: "All Lupopedia core code must run on PHP 7.4 minimum through supported 8.x; avoid PHP 8.0+ only syntax in shared core paths"
      scope: "all_agents"
      category: "compatibility"
  imports: []
  overrides: []
  provenance:
    authored_by: "wolfie"
    authored_date: "20260314"
    last_reviewed_by: "cursor"
    last_reviewed_date: "20260406"
    version: "2.0"
    status: "active"
lupopedia.footer:
  version: "4.0.94"
  last_verified: "20260406021052"
  last_verified_by: "cursor"
  orchestrator: "cursor"
  next_action:
    - "Keep in sync with .cursor/rules/php-7-4-compatibility.mdc (regenerate via propagate_agent_rules.php)"
---
# file: Rule — PHP 7.4+ Compatibility — session: L-LUPO-WOLFIE — delegation: wolfie:root — web_path: http://www.lupopedia.com/rules/root/php-7-4-compatibility

# PHP 7.4+ Compatibility (MANDATORY)

All code must run on **PHP 7.4 minimum** through **supported PHP 8.x**. No Composer or outside frameworks that are not in `lupo-includes`. No deprecated functions that will not work in PHP 8+.

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

- **Lupopedia core** (entry points, lupo-includes, admin, themes) must **not** use namespaces unless explicitly instructed.
- Third-party or explicitly namespaced libraries are the only exception.

## Where this applies

- All files in `lupo-includes/`, `admin.php`, `index.php`, theme layouts, and any file included in the main request path.
- When editing existing code in those paths, preserve or apply PHP 7.4–compatible patterns and avoid PHP 8+ only features unless the file is explicitly modern-only.

This rule is permanent.
