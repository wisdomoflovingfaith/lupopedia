---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/doctrine/MINIMAL_HOSTING_REQUIREMENTS.md
file.last_modified_system_version: "4.0.16"
file.last_modified_utc: "20260218000000"
channel_id: 42   # ANUBIS adoption channel
tags: ["lost", "orphan", "doctrine"]
mood_rgb: "FFDAB9"
atoms:
  recovery_event: true
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool (Auto-Fixed)"
X-Lupo-File-Path: docs/doctrine/MINIMAL_HOSTING_REQUIREMENTS.md
---

# Minimal Hosting Requirements

**Status:** Canonical  
**Applies to:** Lupopedia 3.0.0 — installer pre-flight, diagnostics, and runtime assumptions  
**Overrides:** Any previous assumptions about required extensions or hosting.

**Minimal hosting doctrine (permanent):** Lupopedia must run on **old shared hosting**. Required: PHP 5.3+, pdo_mysql, json, ability to write config. Optional (warn only): mbstring, curl, openssl, fileinfo. Not required: gd, imagick, composer, frameworks.

---

## 1. Required (block installation if missing)

Lupopedia **requires** the following. The installer must **block** and show a clear error if any is missing:

- **PHP 5.3+**
- **pdo_mysql** (extension loaded)
- **json** (extension loaded)
- **Ability to write** the config file (project root writable; e.g. `lupopedia-config.php`)

Without these, the application cannot run. Do not proceed with installation.

---

## 2. Optional (warn only, never block)

The following are **optional**. If missing, the installer may **warn** but must **not** block:

- **mbstring** — fallbacks used if missing (e.g. ASCII-safe slug)
- **curl** — optional
- **openssl** — optional
- **fileinfo** — optional

Use graceful degradation: when optional extensions are missing, use fallbacks (e.g. no mbstring → ASCII-only slug).

---

## 3. Not required (do not check or require)

Do **not** require, check, or document as required:

- **gd**
- **imagick**
- **composer**
- **frameworks**
- **autoloaders** (beyond simple require/include and `spl_autoload_register()`)
- **modern dependency managers**
- **PHP 7/8-only language features** (as a “requirement”)

---

## 4. Absolute prohibitions: no frameworks, no middleware, no Composer, no DB logic

Lupopedia is **pure procedural PHP + PDO**. The following are **permanent, non-negotiable**:

- **No frameworks** — No Laravel, Symfony, CodeIgniter, CakePHP, Zend, Slim, Lumen, Yii, Phalcon, or any PHP framework. No middleware. No PSR-7/PSR-15. No DI containers. No routing libraries. No autoloaders beyond `spl_autoload_register()`.
- **No Composer** — No Composer packages, no `vendor/`, no modern PHP ecosystem tooling.
- **No database logic** — No stored procedures, stored functions, triggers, views, computed/generated columns, foreign keys, cascades, ORM schema, or database-side validation or logic. Database = storage only; all logic in PHP.
- **No ORM, no query builders** — No Eloquent, Doctrine ORM/DBAL, Propel, RedBean, Medoo, Capsule, or any query builder or abstraction beyond PDO. PDO only, manually written SQL.

---

## 5. Fallback philosophy

- **Fallbacks over failures.** If a feature is missing, degrade gracefully; do not block unless absolutely required.
- **Graceful degradation over blocking.** Prefer optional behaviour over hard requirements.
- **Shared-hosting compatibility over modern features.** Assume Lupopedia runs on **old shared hosting**, not modern cloud servers.

---

## 6. Shared hosting constraints

Lupopedia targets environments where:

- PHP version may be 5.3 through 8.1+
- Only a minimal set of extensions may be enabled (pdo_mysql, json)
- No Composer or framework stack is assumed
- No shell or CLI is assumed for normal operation
- Config and writable project root are the only filesystem requirements

All code and installer logic must respect this minimal set and the fallback philosophy above.

---

## 7. References

- **COMPATIBILITY_MATRIX.md** — PHP 5.3 → 8.1+ compatibility and required fallbacks
- **PHP_COMPATIBILITY_AND_MINIMAL_HOSTING_DOCTRINE.md** — Full PHP and slug doctrine
- Pre-flight checks: `install.php`; diagnostics: welcome step and `/system/health` environment block
