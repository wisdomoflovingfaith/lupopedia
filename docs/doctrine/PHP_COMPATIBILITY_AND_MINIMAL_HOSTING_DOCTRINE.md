# PHP Compatibility and Minimal Hosting Doctrine

**Status:** Canonical  
**Applies to:** Lupopedia 4.0.0 — all wizard, importer, and endpoint code  
**Overrides:** Any previous assumptions about PHP 7/8 requirements, GD, mbstring, or modern hosting.

---

## 1. PHP Compatibility Doctrine (Non-Negotiable)

Lupopedia 4.0.0 must run on **PHP 5.3 → PHP 8.1+**. This is the full compatibility matrix. All code must run without syntax errors on PHP 5.3 and also run on PHP 8.1+; avoid deprecated/removed functions and modern-only features; handle behavior changes across versions.

### Required constraints (forbidden in generated code)

- **No** `declare(strict_types=1);` (strict types)
- **No** return type declarations (e.g. `: int`, `: string`, `: void`)
- **No** scalar type hints in parameters (e.g. `function foo(int $x)`)
- **No** nullable types (e.g. `?string`, `string|null`)
- **No** union types (e.g. `int|string`)
- **No** PHP 8.1+ language-level enums (MySQL ENUM columns are allowed)
- **No** attributes (`#[...]`)
- **No** arrow functions (`fn() =>`)
- **No** anonymous classes (`new class { ... }`)
- **No** null-coalescing assignment (`??=`)
- **No** match expressions (PHP 8)
- **No** named arguments (PHP 8)
- **No** spread operator in arrays (`...$arr`) in shared code paths targeting 5.3
- **No** other modern PHP 7/8 features (e.g. `??`, `<=>` where avoidable in shared code paths)
- **No** short array syntax `[]` unless already used in Crafty Syntax–derived code
- **No** closures that rely on PHP 7+ semantics (e.g. type hints in closure parameters)
- **No** modern error modes that assume PHP 7+
- **No** relying on loose typing in critical paths — validate or check types where it matters; do not depend on implicit type juggling for correctness.

### Allowed

- **Namespaces** (PHP 5.3+)
- **PDO** for all database access
- **Basic SPL** where available, including **`spl_autoload_register()`** — use only `spl_autoload_register()` for autoloading; no custom __autoload or other autoload mechanisms.
- **ASCII-safe string operations**: `strtolower()`, `trim()`, `preg_replace()`, etc. (avoid deprecated string functions; see §6 below)
- **preg_*** functions (use `preg_replace_callback()`, not `/e`)
- **iconv()** if available (optional; do not require). Do **not** use `iconv_set_encoding()` or rely on `mbstring.func_overload`.

**Rule:** Always generate code that runs on PHP 5.3 unless explicitly told otherwise.

### Runtime and API usage (mandatory)

- **PDO only.** All database access must use PDO. No mysqli, no mysql_*, no other drivers.
- **Use `preg_replace_callback()`** for replacement patterns that need logic. Do not use the deprecated `/e` modifier in `preg_replace()`; use `preg_replace_callback()` instead.
- **Guard `count()`.** Before calling `count($x)`, ensure `$x` is countable: `if (is_array($x) || $x instanceof Countable)` then call `count($x)`. Do not call `count()` on null or non-countable values (PHP 7.2+ warnings, PHP 8 TypeErrors).
- **JSON wrapper.** Use compatibility wrappers **`safe_json_encode()`** / **`safe_json_decode()`** (or equivalent): PHP 5.3 can silently return `false`; PHP 7 may emit warnings; PHP 8 may throw. Check result or catch; on failure return safe default (e.g. `'[]'`, `'{}'`, or empty array). Do not expose raw encode/decode failure.
- **Avoid deprecated and removed functions entirely.** Do not use: `preg_replace(..., '/e')`, `each()`, `create_function()`, `mysql_*`, `split()`, `ereg*`, `ereg`, `eregi`, `spliti`, `set_magic_quotes_runtime()`, `get_magic_quotes_gpc()`, `money_format()`, `iconv_set_encoding()`, `__autoload()`. Do not rely on `mbstring.func_overload`. Use modern equivalents that work on PHP 5.3.
- **Autoload:** Use **`spl_autoload_register()`** only for autoloading. No `__autoload()` or other autoload mechanisms.
- **Avoid loose typing:** Do not rely on implicit type juggling for correctness; validate or check types where it matters (e.g. before arithmetic, before string ops on variables that might be null/non-string).
- **Deprecated string functions:** Avoid deprecated string functions; use error-safe string handling (e.g. ensure variables are strings or guard with `is_string()` / cast where appropriate).

---

## 2. Minimal fallback set (PHP 5.3 → 8.1+)

Lupopedia must provide or use these minimal fallbacks so behaviour is consistent across PHP 5.3–8.1+:

1. **Slug generator (no mbstring)** — ASCII-safe, preg-based; use `strtolower()`; optionally `mb_strtolower()` if available; never require mbstring. See §3 Slug doctrine.
2. **JSON wrapper** — Use **`safe_json_encode()`** / **`safe_json_decode()`** (or equivalent): PHP 5.3 silently fails on invalid UTF-8; PHP 7 emits warnings; PHP 8 throws. Always check result or catch; on failure return safe default.
3. **preg_replace_callback** — Use `preg_replace_callback()` for replacement-with-logic; never use the removed `/e` modifier.
4. **count() guard** — Before `count($x)`, check `is_array($x) || $x instanceof Countable`; then call `count($x)`.
5. **Autoload** — Use **`spl_autoload_register()`** only; no `__autoload()` or other mechanism.
6. **Error-safe string handling** — Avoid deprecated string behaviour: no `iconv_set_encoding()`, no `mbstring.func_overload`; avoid deprecated string functions.
7. **No PHP 7/8-only features** — No return types, scalar types, enums, attributes, arrow functions, union types, null-coalescing assignment, match, named arguments, anonymous classes, spread in array in 5.3 paths.

---

## 3. Slug Generation Doctrine (mbstring-free)

Slug generation must **never** depend on **mbstring**. It must work on the oldest shared hosting.

### Required fallback strategy

When generating slugs (e.g. username → email-style slug, channel keys, identifiers):

1. **Lowercase**
   - Use `strtolower()` (ASCII only).
   - If mbstring is loaded, you may use `mb_strtolower()` for broader character support.
   - If not, **fallback silently** to ASCII lowercase only. No mbstring dependency.

2. **Replace characters**
   - `@` → `-at-`
   - `.` → `-dot-`
   - Spaces → `-`
   - Any character not in `[a-z0-9-]` → remove or replace with `-`

3. **Normalize hyphens**
   - Replace `--` with `-` repeatedly until no double hyphens remain.
   - Trim leading and trailing hyphens.

4. **No domain portion**
   - If the slug has no domain portion (e.g. username-only), append: **`-at-lupopedia-com`**

**No mbstring dependency is allowed.** All slug logic must work with ASCII-only fallbacks.

---

## 4. Minimal Hosting Requirements (Doctrine)

### Required (block installation if missing)

- **PHP 5.3+**
- **pdo_mysql** (extension loaded)
- **json** (extension loaded)
- **Ability to write** `lupopedia-config.php` (project root writable)

### Optional (warn only, never block)

- **mbstring** — fallbacks used if missing
- **curl** — optional
- **openssl** — optional
- **fileinfo** — optional

### Not required (do not check, do not require, do not document as required)

- **gd**
- **imagick**
- **PHP 7/8 features**
- **Composer**
- **Frameworks**
- **Autoloaders** (beyond simple require/include)
- **Modern dependency managers**

### Philosophy

- **Fallbacks over failures.** If a feature is missing, degrade gracefully; do not block unless absolutely required.
- **Graceful degradation over blocking.** Prefer optional behaviour over hard requirements.
- **Shared-hosting compatibility over modern features.** Assume Lupopedia runs on old shared hosting, not modern cloud servers.

---

## 5. Code Generation Rules (Permanent)

From this doctrine onward, all generated code must:

- Be **compatible with PHP 5.3**
- **Avoid mbstring dependencies**; use ASCII-safe fallbacks
- Use **ASCII-safe** string operations for slugs and identifiers
- **Avoid** modern PHP 7/8 features (strict typing, return types, scalar hints, etc.)
- Use **PDO only** for database access
- Use **preg_replace_callback()** (not the deprecated `/e` modifier) for replacement-with-logic
- **Guard count()**: use `is_array($x) || $x instanceof Countable` before calling `count($x)`
- **Wrap json_encode()** in a safe fallback; never expose raw encode failure
- **Avoid deprecated functions** entirely (no `/e`, no `each()`, no `create_function()`, no `mysql_*`, etc.)
- Use **spl_autoload_register()** only for autoloading
- **Avoid** loose typing in critical paths; validate/check types where it matters
- **Avoid** deprecated string functions and removed functions (no `/e`, no `each()`, no `create_function()`, no `mysql_*`, no `iconv_set_encoding()`, no `mbstring.func_overload`, etc.)
- **Avoid** frameworks, Composer, and modern dependency managers unless already in use and doctrine-compliant
- Follow the **minimal hosting doctrine** (required vs optional vs not required)
- Follow the **slug doctrine** (mbstring-free, ASCII fallbacks)
- Follow **reserved channel doctrine** (0, 1, 42, 5100)
- Follow **identity normalization doctrine** (Crafty → Lupopedia email/slug)
- Follow **upgrade / new-install doctrine** (install, seed, import, drop, config order)

These rules are **permanent** and override all previous assumptions about PHP version, extensions, or hosting.

---

## 6. References

- **COMPATIBILITY_MATRIX.md** — PHP 5.3 → 8.1+ range, forbidden features, deprecated functions, required fallbacks, behavior changes
- **MINIMAL_HOSTING_REQUIREMENTS.md** — Required/optional extensions, fallback philosophy, shared hosting constraints
- Pre-flight checks: `install.php` (top-of-file block before `session_start()`)
- Diagnostics: Welcome step in `install.php`; `/system/health` environment block in `SystemHealthController`
- Slug logic: `username_to_slug()` and related helpers in `install.php` and identity normalization paths
- Installation path: `docs/doctrine/INSTALLATION_PATH_DOCTRINE.md`
- Version: `docs/doctrine/VERSION_DOCTRINE.md`
