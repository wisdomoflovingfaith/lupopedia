---
lupopedia.init:
  orchestrator_actor: "any"
  rule_set_version: "4.0.94+"
  applies_to: ["audit", "code-gen", "ci", "review"]
  enforcement: strict

lupopedia.metadata:
  comment: "Snapshot of metadata for this file or entity at artifact creation."

lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: "doctrine"
  file_path_from_root: "rules/root/PHP_VERSION_COMPATIBILITY.md"
  web_path: "http://www.lupopedia.com/rules/root/PHP_VERSION_COMPATIBILITY.md"
  federation_node_id: 0
  last_modified_utc: "20260406033318"
  when_updated: "20260406033318"
  channel_id: 42
  thread_id: "php-version-compat"
  author:
    type: "actor"
    id: 1
    name: "WOLFIE"
  delegation_chain: "wolfie:root"
  lupopedia.version: "4.0.94"
  system_version: "4.0.94"
  artifact_type: "doctrine"
  artifact_kind: "rule"
  purpose: "PHP version compatibility — shared core syntax targets PHP 5.6+; production runtime SHALL be 64-bit PHP 7.4+ (PRD 00 section 4); honest Y2038 and PHP_INT_MAX stance"
  tags:
    - "php56"
    - "php74"
    - "y2038"
    - "compatibility"
    - "doctrine"
    - "prd00"
    - "arc006"
    - "64bit"

lupopedia.rules:
  comment: "Rule declaration and provenance block"
  declares:
    - rule_id: "ARC006"
      rule_text: "Shared core SHALL avoid PHP 7+–only syntax where PHP 5.6 reach is required; production clock safety remains PHP 7.4+ 64-bit (ARC003). Y2038-safe packed UTC as a PHP int is not guaranteed on 32-bit — document and upgrade."
      scope: "all_agents"
      category: "compatibility"
  imports: []
  overrides: []
  provenance:
    authored_by: "wolfie"
    authored_date: "20260314"
    last_reviewed_by: "wolfie"
    last_reviewed_date: "20260406"
    version: "2.1"
    status: "active"

lupopedia.edges:
  outbound_edges:
    - to: "docs/prd/00_root_constitutional_system_requirements.md"
      type: "implements"
      weight: 1.0
      reason: "PRD 00 section 4.5 and section 4 Option 4"
    - to: "rules/root/php-7-4-compatibility.md"
      type: "references"
      weight: 1.0
      reason: "ARC003 production tier and PHP 8 avoidance"
    - to: "includes/classes/TimestampYmdhis.php"
      type: "references"
      weight: 1.0
      reason: "runtimePackedUtcIntSafe() and packed UTC assumptions"
    - to: "install.php"
      type: "references"
      weight: 0.9
      reason: "LUPOPEDIA_LEGACY_INSTALL and 32-bit allow flags"

lupopedia.footer:
  version: "4.0.94"
  last_verified: "20260406033318"
  last_verified_by: "wolfie"
  orchestrator: "wolfie:root"
  next_action:
    - "Markdown rule files: YAML between --- at line 1 only — never wrap front matter in PHP comment delimiters"
    - "Never ship rules/root/*.md rule docs without lupopedia.headers and lupopedia.rules (propagate_agent_rules.php skips files missing rules)"
    - "Before merge: python scripts/validate_lupopedia_headers_universal.py on touched Markdown"
    - "Keep paired with php-7-4-compatibility.md (ARC003) and .cursor/rules/php-7-4-compatibility.mdc"
---

# file: Doctrine — PHP 5.6+ source compatibility and Y2038 tier — session: L-LUPO-WOLFIE — delegation: wolfie:root — web_path: http://www.lupopedia.com/rules/root/PHP_VERSION_COMPATIBILITY.md

# PHP 5.6+ source compatibility (shared core)

Constitutional source: **PRD 00 section 4** (Option 4) and **section 4.5**. **Production (normative)** is **64-bit PHP 7.4+** — see **`rules/root/php-7-4-compatibility.md`** (**ARC003**). This document defines **syntax** for legacy reach and states **Y2038 / integer width** limits without softening production requirements.

## Tiered runtime vs source (summary)

| Tier | PHP | Architecture | Y2038 / fourteen-digit packed UTC as PHP `int` |
|------|-----|--------------|-----------------------------------------------|
| **Production (normative)** | **7.4+** | **64-bit required** (`PHP_INT_SIZE === 8`) | **Safe** for `gmdate('YmdHis')`-style values and **`timestamp_ymdhis`** integer math |
| **Legacy / transitional** | **5.6+** | 32-bit **not** production-safe | **Not guaranteed** — operators accept risk only via explicit installer flags; see below |

**Source code (shared core):** Prefer syntax valid on **PHP 5.6+** so existing Crafty Syntax and old hosts can **parse** the tree. That does **not** relax **production** runtime: deploy **64-bit PHP 7.4+** for any host that must treat packed UTC as integers.

## Installer flags (explicit)

| Flag / marker | Purpose |
|----------------|--------|
| Environment **`LUPOPEDIA_LEGACY_INSTALL=1`** **or** empty file **`install-legacy-php.flag`** in the project root | Allows the **install wizard** to run on **PHP 5.6+** when the version is **below 7.4**. **Not** a production recommendation — normative floor for production remains **7.4+ 64-bit** (PRD 00 §4). |
| Environment **`LUPOPEDIA_ALLOW_32BIT=1`** **or** empty file **`install-allow-32bit.flag`** | Allows continuing **standard** preflight when **`PHP_INT_SIZE !== 8`**. **Y2038 / packed-UTC-as-`int` is unsafe** on 32-bit; use only when the operator explicitly accepts that risk. Implementation: repository root **`install.php`**. |

## Y2038 warning (32-bit PHP)

**Packed UTC timestamps (`YYYYMMDDHHIISS` as fourteen-digit values) do not fit a 32-bit signed PHP integer** (`PHP_INT_MAX` ≈ 2.1×10⁹). That failure mode appears **as soon as those values are used as integers in PHP** — not only after calendar year **2038**.

- **Production (normative):** **64-bit PHP 7.4+** — required for correct integer handling of packed UTC and for **`timestamp_ymdhis`** arithmetic.
- **Legacy 32-bit installs:** Storage as **`BIGINT`** in the database remains valid; **PHP** must not assume safe **`int`** arithmetic (`addSeconds`, `diffInSeconds`, naive casts). **Upgrade to 64-bit PHP 7.4+** before relying on clock math in application code.

**Diagnostics only:** **`timestamp_ymdhis::runtimePackedUtcIntSafe()`** in **`includes/classes/TimestampYmdhis.php`** — does **not** replace upgrading the runtime.

Full doctrine: **PRD 00 section 4.5** and **section 4**.

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
| `random_bytes()` without polyfill | PHP 7.0+ only | Polyfill using **OpenSSL only** (see below) — **do not** use **`mcrypt_*`** (removed / unavailable on many builds) |
| `??=` null coalescing assignment | PHP 7.4+ only | Explicit isset assignment |
| Array destructuring with `[]` | PHP 7.1+ | Use `list()` |
| `Throwable` interface | PHP 7.0+ | Use `Exception` only where 5.6 must parse |
| Multiple catch per try | PHP 7.1+ | Separate catch blocks |

**Do not** recommend **`create_function()`** — deprecated, poor ergonomics, and unsuitable as a general pattern; use **named functions** or **closures** where the PHP version allows.

## Required polyfills (random_bytes — OpenSSL only)

For PHP 5.6, **`random_bytes()`** may be missing. **Prefer `openssl_random_pseudo_bytes()`** only — **never** **`mcrypt_create_iv()`** in new or documented patterns (extension absent or removed on many stacks). For **cryptographic** use, **fail closed** if OpenSSL cannot return strong bytes.

```php
// Example pattern only — adjust to project error handling

if (!function_exists('random_bytes')) {
    function random_bytes($length) {
        if (!function_exists('openssl_random_pseudo_bytes')) {
            throw new RuntimeException('random_bytes: OpenSSL extension required');
        }
        $strong = false;
        $bytes = openssl_random_pseudo_bytes($length, $strong);
        if ($bytes === false || $strong === false) {
            throw new RuntimeException('random_bytes: OpenSSL could not provide strong bytes');
        }
        return $bytes;
    }
}
```

**Note:** **`bin2hex()`** exists in PHP 5.6 — **no polyfill** in this doctrine.

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

### Mandatory Markdown shape (`rules/root`)

1. **Line 1** is **`---`** only (see **`.cursor/rules/lupopedia-headers-file-order.mdc`**). **Do not** wrap YAML in **`/*` … `*/`** — that pattern applies to **PHP** sources with embedded header comments, **not** to **`.md`** rule files.
2. Include **`lupopedia.headers`** (`header_format_version`, `when_updated`, `file_path_from_root`, `web_path`, `last_modified_utc`, **`author`** with **`type`** and **`id`**, `federation_node_id`, `artifact_type`, `artifact_kind`, `purpose`, `tags`, etc.).
3. Include **`lupopedia.rules`** with **`declares`** — **`scripts/propagate_agent_rules.php` skips** root rule files that omit this block.
4. Prefer **`lupopedia.edges`** and **`lupopedia.footer`**; align **`last_verified`** with **`python bin/tick.py`** for the batch.
5. **After edits:** run **`tick.py`** once per batch; use printed **`current_utc`** in header/footer fields you change.
