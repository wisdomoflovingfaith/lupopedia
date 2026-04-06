---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/doctrine/COMPATIBILITY_MATRIX.md"
  file_hash: "1bb6c9d049e92f627895a9aca5406b2662d691237507772eead4ef663ffafd9e"
  last_updated_utc: "20260228155738"
  system_version: "4.0.88"
  channel_id: 1
  actor_id: 102
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: implements
      weight: 1.0
      reason: "Doctrine PRD lineage; constitutional audit 20260403"

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "cursor"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  

lupopedia.edges: []
  file_path_from_root: "lupo-docs\doctrine\COMPATIBILITY_MATRIX.md"
  file_hash: "f8453c23f34d61ba4fc62fcd1c40581673fbac4849a541d5c9f202d1615e3a82"
  file_path_from_root: "lupo-docs\doctrine\COMPATIBILITY_MATRIX.md"
  file_hash: "e6293937c282294fdf2f3292f990c726098ccdd7dff7f9d5e2097cf6f74ef529"
  last_updated_utc: "20260228"
  system_version: "4.0.88"
  channel_id: 1
  actor_id: 102
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for COMPATIBILITY_MATRIX.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "compatibility_matrixmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.88"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "cursor"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use lupopedia.headers"]
---

---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
lupopedia.headers: explicit architecture with structured clarity for every file.
file_path_from_root: lupo-docs/doctrine/COMPATIBILITY_MATRIX.md
file.last_modified_system_version: "4.0.88"
file.last_modified_utc: "20260218000000"
channel_id: 42   # ANUBIS adoption channel
tags: ["lost", "orphan", "doctrine"]
mood_rgb: "FFDAB9"
atoms:
  recovery_event: true
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool (Auto-Fixed)"
X-Lupo-File-Path: lupo-docs/doctrine/COMPATIBILITY_MATRIX.md
---

# PHP Compatibility Matrix (7.4 → 8.x)

**Status:** Canonical summary; syntax detail: **`lupo-rules/root/php-7-4-compatibility.md`**  
**Applies to:** Lupopedia 4.0.x — core PHP code (`lupo-includes/`, entrypoints, themes)  
**Overrides:** Older “PHP 5.3 / 5.6 matrix” language elsewhere in this file’s history.

---

## 1. Compatibility range (permanent rule)

Lupopedia **core** must run on **PHP 7.4+** through **supported PHP 8.x**.

All generated code must:

- Run **without syntax errors** on PHP 7.4
- **Avoid** deprecated/removed functions that break on PHP 8
- **Avoid** PHP **8.0+ only** language features in **shared** core paths unless a file is explicitly scoped modern-only (see **`php-7-4-compatibility.md`**)
- **Gracefully handle** behavior changes across versions (e.g. JSON, count, errors)

---

## 2. Forbidden PHP features (shared core paths)

Do **not** use these in **`lupo-includes/`**, **`admin.php`**, **`index.php`**, and theme layouts unless the file is explicitly modern-only:

- Union types (`string|int`), **`match`**, **named arguments**, **enums**, **attributes**, **`readonly`** properties (PHP 8.0+)
- `declare(strict_types=1)` in core unless explicitly allowed for that file

**Allowed on PHP 7.4:** null coalescing (`??`, `??=`), short arrays (`[]`), arrow functions (`fn`), typed properties, scalar parameter/return types, `session_set_cookie_params` array form.

**Note:** **MySQL ENUM columns** are allowed. Only PHP language enums are forbidden in shared core paths.

---

## 3. Deprecated / removed functions (never use)

Avoid all functions removed or deprecated in PHP 7/8. Use modern equivalents that still work on PHP 5.3:

| Forbidden | Use instead / note |
|-----------|--------------------|
| `mysql_*` | PDO only |
| `ereg`, `eregi`, `split`, `spliti` | `preg_*` |
| `preg_replace('/.../e')` | `preg_replace_callback()` |
| `each()` | `foreach` or iteration without each |
| `create_function()` | `function` or closure (PHP 5.3+) |
| `set_magic_quotes_runtime()` | Removed; do not use |
| `get_magic_quotes_gpc()` | Removed; do not use |
| `money_format()` | Removed in 8.0; use number_format or equivalent |
| `iconv_set_encoding()` | Do not use |
| `__autoload()` | Use `spl_autoload_register()` only |

Do not rely on `mbstring.func_overload`.

---

## 4. Required fallbacks (always implement)

To support PHP 5.3 → 8.1+, use these fallback-safe patterns:

### Slug generation (no mbstring dependency)

- Use ASCII-safe replacements
- Use `strtolower()`
- Optionally use `mb_strtolower()` if available
- Never require mbstring

### JSON wrapper

- PHP 5.3 can silently return `false` on invalid UTF-8
- PHP 7 may emit warnings
- PHP 8 may throw exceptions

Use compatibility wrappers: **`safe_json_encode()`** and **`safe_json_decode()`** (or equivalent): check result / catch, return safe default on failure.

### preg_replace_callback

- Never use the `/e` modifier in `preg_replace()`
- Use `preg_replace_callback()` for replacement-with-logic

### count() guard

Always check before `count($x)`:

```php
if (is_array($x) || $x instanceof Countable)
```

Then call `count($x)`. Do not call `count()` on null or non-countable values.

### Autoload

- Use **`spl_autoload_register()`** only
- No `__autoload()` or other autoload mechanism

### Error-safe string handling

- Avoid deprecated/removed string behavior
- Do not use `iconv_set_encoding()` or rely on `mbstring.func_overload`
- Validate or sanitize strings where behavior differs across PHP versions

---

## 5. Behavior changes across versions

| Area | PHP 5.3 | PHP 7 / 8 | Handling |
|------|---------|-----------|----------|
| `json_encode()` / `json_decode()` | Returns `false` on invalid UTF-8 | Warnings or exceptions | Use safe wrapper; check return; catch if available |
| `count(null)` | Allowed (returns 0) | PHP 7.2+ warning; PHP 8 TypeError | Guard: `is_array($x) \|\| $x instanceof Countable` |
| Removed functions | Some still exist | Removed | Do not use; use listed alternatives |
| Error reporting | Many notices | Stricter | Validate types and operands; avoid loose typing in critical paths |

---

## 6. Absolute prohibitions: no frameworks, no middleware, no Composer, no DB logic

Lupopedia is **pure procedural PHP + PDO, nothing else.**

- **No frameworks** — No Laravel, Symfony, CodeIgniter, CakePHP, Zend, Slim, Lumen, Yii, Phalcon, or any PHP framework. No middleware pattern. No PSR-7/PSR-15 request/response abstractions. No dependency injection containers. No routing libraries. No autoloaders beyond `spl_autoload_register()`.
- **No Composer** — No Composer packages, no `vendor/` directory, no modern PHP ecosystem tooling.
- **No database logic** — No stored procedures, stored functions, triggers, views, computed/generated columns, foreign keys, cascades, ORM-generated schema, or database-side validation or logic. The database is for **storage only**; all logic lives in PHP.
- **No ORM, no query builders** — No Eloquent, Doctrine ORM/DBAL, Propel, RedBean, Medoo, Capsule, or any query builder or abstraction beyond PDO. **PDO only, with manually written SQL.**

See **MINIMAL_HOSTING_REQUIREMENTS.md** and **.cursorrules** for the full prohibition lists and enforcement.

---

## 7. References

- **PHP_COMPATIBILITY_AND_MINIMAL_HOSTING_DOCTRINE.md** — Full PHP and slug doctrine
- **MINIMAL_HOSTING_REQUIREMENTS.md** — Extensions and hosting constraints
- **.cursorrules** — PHP 5.3 and PHP 8.1+ compatibility requirement and enforcement
