---
lupopedia.init:
  file_identity: "PHP_VERSION_COMPATIBILITY.md"
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
  file_path_from_root: ".windsurf/rules/PHP_VERSION_COMPATIBILITY.md"
  last_modified_utc: "20260406"
  system_version: "4.0.76"
  source_path: "lupo-rules/root/PHP_VERSION_COMPATIBILITY.md"
  artifact_type: "rule"
  artifact_kind: "windsurf_doctrine"
  purpose: "Windsurf-specific rule derived from canonical root rule"

lupopedia.rules:
  comment: "Rule declaration and provenance block"
  declares:
    - rule_id: "ARC006"
      rule_text: "Shared core SHALL avoid PHP 7+–only syntax where PHP 5.6 reach is required; production clock safety remains PHP 7.4+ 64-bit (ARC003). Y2038-safe packed UTC as a PHP int is not guaranteed on 32-bit — document and upgrade."
      scope: "all_agents"
      category: "compatibility"
      status: "active"
  imports: []
  overrides: []
  provenance:
    authored_by: "wolfie"
    authored_date: "20260406"
    last_reviewed_by: "windsurf"
    last_reviewed_date: "20260406"
    version: "1.0"
    status: "active"
lupopedia.footer:
  version: "4.0.76"
  last_verified: "20260406"
  last_verified_by: "windsurf"
  orchestrator: "windsurf"
  next_action:
    - "Keep in sync with canonical root rules"
---

# file: Rule — PHP 5.6+ source compatibility and Y2038 tier — session: L-LUPO-WOLFIE — delegation: cursor:root — web_path: http://www.lupopedia.com/lupo-rules/root/PHP_VERSION_COMPATIBILITY.md

# PHP 5.6+ source compatibility (shared core)

Constitutional source: **PRD 00 section 4** (Option 4) and **section 3.5.4**. **Production** norm remains **PHP 7.4+** on **64-bit** — see **`lupo-rules/root/php-7-4-compatibility.md`** (**ARC003**). This file defines **syntax** constraints for **legacy reach** and states the **Y2038 / integer width** trap in plain terms.

## Tiered runtime vs source (summary)

| Tier | PHP | Architecture | Y2038 / fourteen-digit packed UTC as PHP `int` |
|------|-----|--------------|-----------------------------------------------|
| **Production (normative)** | **7.4+** | **64-bit** (`PHP_INT_SIZE === 8`) | **Safe** for `gmdate('YmdHis')`-style values and **`timestamp_ymdhis`** integer math |
| **Legacy / transitional** | **5.6+** | 32-bit allowed (not recommended) | **Not guaranteed** — see Y2038 trap below |

**Source code (shared core):** Prefer syntax valid on **PHP 5.6+** so existing Crafty Syntax and old hosts can parse and run the tree. That means **avoiding PHP 7+–only syntax** in those paths unless a file is explicitly scoped as modern-only.

**Installer overrides:** `LUPOPEDIA_LEGACY_INSTALL`, `lupo-install-legacy-php.flag`, `LUPOPEDIA_ALLOW_32BIT`, `lupo-install-allow-32bit.flag` — see repository root **`install.php`**.

## The Y2038 / `PHP_INT_MAX` trap (why 7.4+ 64-bit is the production default)

Fourteen-digit packed UTC values (example `20260406032941`) are on the order of **10^13**. On **32-bit PHP**, **`PHP_INT_MAX`** is about **2.1e9**, so those values **do not fit** in a signed PHP integer. Casts and arithmetic can **overflow to float**, **truncate**, or behave inconsistently — **including in 2026**, not only after calendar year 2038. **64-bit PHP** (`PHP_INT_SIZE === 8`) is required for safe **integer** handling of packed UTC and for **`timestamp_ymdhis`** helpers that assume integers.

**Diagnostics only:** **`timestamp_ymdhis::runtimePackedUtcIntSafe()`** in **`lupo-includes/classes/TimestampYmdhis.php`** reports whether the runtime can treat packed UTC as a safe integer; it **does not** replace upgrading production hosts to **64-bit PHP 7.4+**.

## Core rule (syntax)

All code in **shared core paths** SHOULD remain parseable on **PHP 5.6.0** or higher. No features introduced in PHP 7.0+ are permitted in those paths unless there is a documented polyfill or the file is explicitly a modern-only island.

## Forbidden PHP 7+ features

| Feature | Reason | PHP 5.6 alternative |
|---------|--------|---------------------|
| `??` null coalescing operator | PHP 7.0+ only | `isset($var) ? $var : $default` |
| `<=>` spaceship operator | PHP 7.0+ only | Manual comparison with if/else |
| `int`, `string`, `array` scalar type hints | PHP 7.0+ only | Remove scalar hints; use `is_int()`, `is_string()`, etc. |
| Return type declarations (`: int`, `: string`) | PHP 7.0+ only | Remove return type declarations |
| `declare(strict_types=1)` | PHP 7.0+ only | Omit strict types |
| Anonymous classes (`new class { ... }`) | PHP 7.0+ only | Use named classes |
| `random_bytes()` without polyfill | PHP 7.0+ only | Polyfill or `openssl_random_pseudo_bytes()` / documented fallback |
| `??=` null coalescing assignment | PHP 7.4+ only | Explicit isset assignment |
| Array destructuring with `[]` | PHP 7.1+ | Use `list()` |
| `Throwable` interface | PHP 7.0+ | Use `Exception` only where 5.6 must parse |
| Multiple catch per try | PHP 7.1+ | Separate catch blocks |

## Required polyfills

For PHP 5.6 compatibility, these functions MUST have polyfills where used:

```php
// Example pattern: lupo-includes/functions/php56_polyfills.php

if (!function_exists('random_bytes')) {
    function random_bytes($length) {
        if (function_exists('openssl_random_pseudo_bytes')) {
            $bytes = openssl_random_pseudo_bytes($length, $strong);
            if ($strong !== false) {
                return $bytes;
            }
        }
        $bytes = '';
        for ($i = 0; $i < $length; $i++) {
            $bytes .= chr(mt_rand(0, 255));
        }
        return $bytes;
    }
}
```

## Validation rules

1. **No PHP 7+ syntax** in shared core paths unless scoped and documented.
2. **Type hints:** only patterns valid on PHP 5.6 in those paths.
3. **Return types:** none in PHP 5.6-targeted files.
4. **Array syntax:** in files that must parse on 5.6, prefer `array()`; match existing file style when editing.

## Testing requirements

- Exercise shared paths on **PHP 5.6.40** where feasible.
- Use `php -l filename.php` for syntax checks.
- No unintended PHP 7+ parse errors in 5.6-targeted trees.

## Enforcement and preventing header drift

- **LEXA / review** flags PHP 7+ syntax in scoped paths.
- **LILITH / audit** may cite **PRD 00** if rule Markdown omits headers or contradicts Option 4.

### Mandatory Markdown shape (lupo-rules/root)

1. **Line 1** of the file is **`---`** only (see **`.cursor/rules/lupopedia-headers-file-order.mdc`**).
2. Include **`lupopedia.headers`** (with **`file_path_from_root`**, **`web_path`**, **`last_modified_utc`**, **`when_updated`**, **`purpose`**, **`tags`**, **`federation_node_id`**, etc.).
3. Include **`lupopedia.rules`** with **`declares`** — **`lupo-scripts/propagate_agent_rules.php` skips** root rule files that omit this block.
4. Prefer **`lupopedia.edges`** and **`lupopedia.footer`** with **`last_verified`** aligned to **`tick.py`** for the batch.
5. **After edits:** run **`python lupo-bin/tick.py`** once per batch; use printed **`current_utc`** in header/footer fields you change.

