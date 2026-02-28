# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\audits\INSTALL_PHP_WIZARD_DOCTRINE_AUDIT.md"
  file_hash: "b381a0f5be0f331cf6fc29ed38c5752991e7f0233fed93baa5a72da0d39c9b6c"
  file_path_from_root: "docs\audits\INSTALL_PHP_WIZARD_DOCTRINE_AUDIT.md"
  file_hash: "6745a38ca16cc51cff323ec9a808bc7cb458eaaba79e3baee90c6e4bea82a183"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Install.php + Wizard — Full Doctrine Compliance Audit (PHP 5.3 → 8.1)"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "audits", "install_php_wizard_doctrine_auditmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Install.php + Wizard — Full Doctrine Compliance Audit (PHP 5.3 → 8.1)

**Date:** 2025-02-04  
**Scope:** `install.php` and all wizard-related code (single file; no separate wizard includes).  
**Purpose:** Analysis only. No code modified. Wait for explicit approval before applying fixes.

Doctrine checked: PHP 5.3→8.1 compatibility; no-framework / no-middleware / no-Composer; no ORM / no query builders; no triggers/procedures/DB logic; ASCII-safe fallbacks; PDO-only; class-conversion; reference-semantics; scope-preservation; minimal hosting; dual-path schema; wizard repeatability; installer doctrine; no modern PHP features (no strict types, return types, scalar type hints, nullable/union types, attributes, arrow functions, typed properties, match, named arguments; must use array() not []; must use isset() not ??; random_bytes/random_int/hash_equals require fallbacks).

---

## Task 1 — Scan all code / identify every violation

- **install.php** is the only wizard file; no separate includes for wizard logic.
- Violations identified: **Database Access** (new PDO); **Class Conversion** (all logic in global helpers); **PHP 5.3** (??, [], return types, type hints, nullable, typed closure, random_bytes, random_int, hash_equals); **No modern PHP** (all of the above). See sections below for full lists.

---

## Task 2 — PHP 5.3 compatibility

Flagged usage:

| Category | Lines / pattern | Requirement |
|----------|-----------------|-------------|
| **Short array `[]`** (must use array()) | 104, 132–133, 147–154, 158–163, 209, 254, 294, 367, 370, 486, 495, 532, 536, 548, 572–573, 577, 596, 604, 614, 710, 735, 806, 808, 847, 914, 954–957, 989, 1021, 1040, 1115, 1134, 1180, 1212, 1401, 1409, 1524–1525; also return arrays like `['id'=>'welcome','label'=>'Welcome']` at 147–163 | Replace with `array()`. Replace `$x !== []` with `$x !== array()` or equivalent. |
| **Null coalescing `??`** (must use isset()) | 83, 91, 97, 103, 129, 144, 198–199, 232, 243, 497–499, 506, 517, 576, 696, 755–756, 861–862, 894, 909, 929–930, 942, 944, 948, 960, 962, 965, 967, 986, 992, 1011, 1020–1021, 1023, 1027, 1031, 1038–1040, 1042, 1046, 1059, 1119, 1122–1131, 1163–1164, 1178, 1201, 1212–1216, 1223, 1228, 1331–1334, 1409, 1419, 1451, 1453, 1482, 1496, 1498, 1528 | Replace with `isset($var) ? $var : default`. |
| **Return types** | 143, 167, 177, 184, 194, 205, 218, 225, 282, 293, 305, 351, 367, 449, 460, 469, 475, 484, 494, 531, 547, 571, 628, 692, 709, 802, 821 | Remove `: array`, `: int`, `: string`, `: bool`, `: void`, `: PDO`, `?array`, `?string`. |
| **Scalar/parameter type hints** | Same function list as above | Remove all parameter types (string, array, PDO, etc.) for 5.3. |
| **Nullable types** | 225 (`?array`), 367 (`?string`, `array $options = []`) | Remove `?` and default `[]` → `array()`. |
| **Typed closure** | 321–322 `static function (string $s): bool` | Replace with untyped closure `function ($s)` and no return type. |
| **random_bytes / random_int / hash_equals** | 186 `random_bytes(32)`, 374 `random_int(0, n)`, 199 `hash_equals()` | Add 5.3 fallbacks: e.g. openssl_random_pseudo_bytes or mt_rand loop; timing-safe compare polyfill. |
| **PHP 7/8-only syntax** | All of the above | None of the above exist in PHP 5.3. |

---

## Task 3 — PHP 8.1 compatibility

- No deprecated or removed functions used (no each, preg_replace /e, mysql_*, etc.).
- fetchColumn(), PDO::FETCH_COLUMN, date('c'), filter_var, timezone_identifiers_list are valid on 8.1.
- Risk: passing null to internals; current code uses casts and isset. No change required for 8.1 if 5.3 fixes are applied without introducing 8.1-incompatible code.

---

## Task 4 — Class-conversion and reference-semantics doctrine

- **Helpers that should be in classes:** All 25+ global functions (get_wizard_steps, get_current_step_index, get_total_steps, get_csrf_token, validate_csrf, log_entry, safe_error_message, get_db_credentials, connect_pdo, detect_livehelp_tables, run_sql_file, drop_livehelp_tables, write_config, username_to_slug, is_valid_email, is_valid_lupopedia_slug, is_acceptable_resolved_email, load_crafty_users, compute_proposed_identities, find_duplicate_email_groups, collect_normalize_warnings, validate_resolved_emails, create_reserved_system_channels, apply_normalization_to_livehelp, create_operator_channels, ensure_reserved_channels, ensure_operator_channels). Must be migrated into classes incrementally; wrappers kept until tested.
- **Reference semantics:** All `array &$log` usages are correct and must be preserved when converting to class methods. No removal of & without explicit user request.
- **Scope:** No unintended scope changes; session and globals used consistently. When converting, preserve same session keys and lifetime; no new object-held wizard state unless requested.
- **Global state:** Wizard relies on $_SESSION and $_GET/$_POST; no framework or DI. Preserve these assumptions.

---

## Task 5 — Minimal hosting doctrine

- **mbstring:** Not required; warning only; username_to_slug is ASCII-safe. OK.
- **GD:** Not used. OK.
- **curl/openssl:** Optional, warn only. OK. (random_bytes/random_int/hash_equals fallbacks may use openssl if available; not required.)
- **Modern extensions:** None required. OK.
- **Pre-flight:** PHP 5.3+, pdo_mysql, json, writable root; optional mbstring, curl, openssl, fileinfo. Compliant.

---

## Task 6 — Dual-path schema doctrine

- **Schema in wizard:** create_reserved_system_channels and create_operator_channels assume tables lupo_channels, lupo_channel_roles, lupo_actors (and columns) exist. **Verify** these exist in install_new_lupopedia.sql and seed where needed.
- **install_new_lupopedia.sql / seed_lupopedia.sql:** Referenced and run in correct order. OK.
- **Importer:** import_from_old_crafty_syntax.sql run after normalize; wizard does not modify importer SQL. OK.
- **Seed rows:** Wizard does not assume seed content beyond tables existing. No missing seed rows flagged.
- **Importer mismatches:** None identified; wizard runs import file as-is.

---

## Task 7 — Wizard repeatability

- **Drop → load Crafty → run wizard → test → repeat:** Supported. Upgrade detect, bootstrap (install+seed+reserved), normalize, confirm, run (import → operator channels → drop), config, complete. No step assumes one-time-only state that would break repeat runs.
- **Idempotency:** Import skipped if lupo_import_run set; reserved channels recreated if missing; operator channels ensured for missing only. OK.
- **Legacy drop:** drop_old_crafty_syntax_tables.sql + detect_livehelp_tables + drop_livehelp_tables for any remaining. OK.
- **Session:** Start over clears wizard keys; complete unsets install-related keys. OK.
- Nothing found that breaks the repeatability workflow.

---

## 1. All doctrine violations

| Item | Location | Description |
|------|----------|-------------|
| **Database Access** | Line 284 (`connect_pdo`) | Uses `new PDO()` directly. Doctrine requires **DatabaseFactory::getConnection()** or **lupo_get_db()**. Installer runs before config exists, so Factory may be unavailable; doctrine does not currently state an installer exception. |
| **Class Conversion** | Entire file | All wizard logic is in **global helper functions** (e.g. `get_wizard_steps`, `run_sql_file`, `create_reserved_system_channels`). Doctrine: "All new logic must be in classes"; "Existing helper functions must be migrated into classes one-by-one." No helpers have been moved to classes yet. |

---

## 2. All PHP 5.3 compatibility issues

| Item | Location / pattern | Description |
|------|--------------------|-------------|
| **Null coalescing operator `??`** | Throughout (e.g. 83, 91, 97, 103, 129, 144, 198–199, 232, 243, 497–499, 576, 696, 755–756, 861–862, 894, 909, 929–930, 942, 948, 960, 965, 986, 992, 1011, 1020–1021, 1038–1040, 1046, 1059, 1119, 1122–1131, 1163–1164, 1178, 1201, 1212–1216, 1223, 1228, 1331–1334, 1409, 1419, 1451, 1453, 1482, 1496, 1498, 1528) | `??` was added in **PHP 7.0**. PHP 5.3 will parse it as a syntax error (or in older 5.3, undefined). Must be replaced with `isset($x) ? $x : default` (or equivalent). |
| **Short array syntax `[]`** | Throughout (e.g. 36–37, 105–106, 109–110, 115–116, 125, 147–166, 255–267, 319–323, 634–639, 803, 806, 807, 848, 1124–1131, 1212–1213) | Short array `[]` was added in **PHP 5.4**. PHP 5.3 requires `array()`. All `[]` and `$x !== []` must use `array()` and `$x !== array()`. |
| **Return type declarations** | 143, 167, 177, 184, 194, 205, 218, 225, 282, 293, 305, 351, 367, 449, 460, 469, 475, 484, 494, 531, 547, 571, 628, 692, 709, 802, 821 | Syntax `: array`, `: int`, `: string`, `: bool`, `: void`, `: PDO`, `?array`, `?string` is **PHP 7.0+**. PHP 5.3 will parse error. Must be removed. |
| **Scalar/parameter type hints** | All functions above | `string $step`, `array $vars`, `PDO $pdo`, `string $path`, `array &$log`, etc. Type hints for parameters are **PHP 7.0+** (and PDO is a class; class type hints are 5.0+ but with return types and nullable it's 7+). Scalar/return types must be removed for 5.3. |
| **Nullable types `?array`, `?string`** | 225, 367 | **PHP 7.1+**. Remove. |
| **Closure with type hint and return type** | 321–322 | `static function (string $s): bool` uses parameter type and return type in closure — **PHP 7+**. Must be replaced with untyped closure. |
| **`static` in closure** | 321 | `static function` for closures is **PHP 5.4+**. PHP 5.3 only has `function`. Use non-static closure. |
| **`random_bytes(32)`** | 186 | **PHP 7.0+**. CSRF token generation will fail on 5.3. Need fallback (e.g. mt_rand/openssl if available, or documented 5.3 limitation). |
| **`random_int()`** | 374 (in `write_config`) | **PHP 7.0+**. Config key generation will fail on 5.3. Need fallback (e.g. mt_rand loop with range). |
| **`hash_equals()`** | 199 | **PHP 5.6+**. PHP 5.3 does not have it. CSRF validation will fail. Need polyfill or timing-safe compare fallback. |

---

## 3. All PHP 8.1 compatibility issues

| Item | Location | Description |
|------|----------|-------------|
| **Passing null to non-nullable internal** | Possible in edge cases | If any superglobal or session key is null where a string is expected, PHP 8.1+ may deprecate or error. Mitigation: continue to cast and use isset/empty; no specific violation found. |
| **`$stmt->fetchColumn()`** | 551, 561, 806, 845–846, 848 | In PHP 8.0+ `fetchColumn()` with no argument returns first column; behavior is compatible. No change required. |
| **Return type and void** | All `: void` functions | PHP 7.1+; already listed under 5.3 (remove for 5.3). No additional 8.1-specific issue. |

No additional PHP 8.1-only violations identified; main risk is removal of 5.3-incompatible syntax so 8.1 continues to run.

---

## 4. All deprecated/removed functions

| Item | Location | Description |
|------|----------|-------------|
| **None** | — | No `each()`, `preg_replace('/e')`, `mysql_*`, `split`, `ereg*`, `create_function()`, `get_magic_quotes_gpc()`, `money_format()`, `__autoload()` found. |
| **`date('c')`** | 209 | ISO 8601 date; valid in 5.3 and 8.1. Safe. |

---

## 5. All forbidden features

- **Strict types:** Not used. OK.
- **Return type declarations:** Used throughout. Forbidden for 5.3→8.1 doctrine; must remove.
- **Scalar type hints:** Used in all helper signatures. Forbidden; must remove.
- **Nullable types:** Used (`?array`, `?string`). Forbidden; must remove.
- **Short array syntax (must use array()):** Used throughout. Forbidden; must use `array()` not `[]`.
- **Null-coalescing operator (must use isset()):** Used throughout. Forbidden; must use `isset($x) ? $x : default` not `??`.
- **random_bytes / random_int / hash_equals without fallbacks:** Used at 186, 374, 199. Forbidden without 5.3 fallbacks.
- **Union types:** Not used. OK.
- **Attributes:** Not used. OK.
- **Arrow functions:** Not used. OK.
- **Anonymous classes:** Not used. OK.
- **Match expressions:** Not used. OK.
- **Named arguments:** Not used. OK.
- **Spread in arrays:** Not used. OK.
- **PHP 8.1 enums:** Not used. OK.
- **Typed properties:** Not used (no classes). OK.
- **Generators:** Not used. OK.
- **Traits:** Not used. OK.

---

## 6. All missing fallbacks

| Item | Location | Description |
|------|----------|-------------|
| **JSON** | N/A | install.php does not call `json_encode`/`json_decode`. No JSON fallback needed in this file. |
| **Slug** | 449–456 (`username_to_slug`) | Uses `strtolower`, `preg_replace`; no mbstring. **ASCII-safe and mbstring-free.** Compliant. |
| **preg_replace** | 316–318, 454–455, 471 | No `/e` modifier; uses `preg_replace_callback` where logic needed (none in file) or simple `preg_replace`. Compliant. |
| **count() guard** | 178, 540, 608, 907, 1002, 1010 | Doctrine: guard with `is_array($x) \|\| $x instanceof Countable` before `count($x)`. Lines 178, 907, 1002, 1010 use `count()` on values that are always arrays in current flow; 1215–1216 already use `is_array(...) ? count(...) : 0`. For strict doctrine compliance, add guard anywhere `count()` is used on variables that could be non-array (e.g. `$validation['errors']`). |
| **Autoload** | N/A | install.php does not register autoload. No violation. |
| **CSRF / random** | 186, 199, 374 | `random_bytes` and `random_int` have no 5.3 fallback; `hash_equals` has no 5.3 fallback. These are **missing fallbacks** for PHP 5.3. |

---

## 7. All class-conversion issues

| Item | Description |
|------|-------------|
| **All logic in global helpers** | Doctrine: "All new functions must be placed inside classes"; "No new global helper functions"; "Existing helper functions must be migrated into classes one-by-one." install.php defines 25+ global functions. None are in classes. Required: incremental migration into classes (e.g. `InstallWizardSteps`, `InstallSqlRunner`, `InstallNormalize`, etc.) with wrappers kept until tested. |
| **Reference semantics** | Functions that take `array &$log` (e.g. `run_sql_file`, `drop_livehelp_tables`, `write_config`, `create_reserved_system_channels`, `create_operator_channels`, `ensure_reserved_channels`, `ensure_operator_channels`) **preserve** `&$log` correctly. When converted to class methods, `&$log` must be preserved per Class Conversion Doctrine. |
| **Scope** | No DI or service containers; session and globals used as in procedural flow. When converting to classes, same session/global usage and side effects must be preserved. |
| **Modern OOP** | No DI, no middleware. Classes, when introduced, must behave as "structured namespaces" per doctrine. |

---

## 8. All reference-semantics issues

| Item | Description |
|------|-------------|
| **Preserved** | `run_sql_file(PDO $pdo, string $path, array &$log)`, `drop_livehelp_tables(..., array &$log)`, `write_config(..., array &$log)`, `create_reserved_system_channels(PDO $pdo, array &$log)`, `create_operator_channels(PDO $pdo, array &$log)`, `ensure_reserved_channels(PDO $pdo, array &$log)`, `ensure_operator_channels(PDO $pdo, array &$log)` all use `&$log` and mutate the array. No reference semantics were removed. |
| **Correction needed** | None for current code. When converting to classes, methods must keep `array &$log` (and any other by-ref params) unless user explicitly asks to optimize. |

---

## 9. All scope issues

| Item | Description |
|------|-------------|
| **Session and flow** | Step flow, session keys (`lupo_install_type`, `lupo_install_db_vars`, etc.), and redirects are consistent. No new scopes or broken shared state observed. |
| **Correction needed** | When moving to classes, do not introduce new object instances that hold wizard state in place of `$_SESSION` unless explicitly requested; preserve same session keys and lifetime. |

---

## 10. All wizard-flow issues

| Item | Status | Notes |
|------|--------|------|
| **Upgrade detection** | OK | `detect_livehelp_tables()`; `count($livehelp_tables) > 0` → upgrade. |
| **Normalization** | OK | Upgrade path: credentials → bootstrap (install+seed+reserved) → normalize → confirm → run → config → complete. |
| **Import** | OK | `import_from_old_crafty_syntax.sql` run after normalize; `lupo_import_run` prevents re-run. |
| **Operator channels** | OK | `create_operator_channels` after import; `ensure_operator_channels` for idempotency. |
| **Reserved channels** | OK | 0, 1, 42, 51 created at bootstrap (upgrade) or at run (new); `ensure_reserved_channels` recreated if missing. |
| **Drop legacy** | OK | `drop_old_crafty_syntax_tables.sql` then `detect_livehelp_tables` + `drop_livehelp_tables` for any remaining. |
| **Config write** | OK | `write_config()`; session cleared on complete. |
| **Session lifecycle** | OK | Start over clears session keys; complete unsets install-related keys. |
| **Idempotency** | OK | Import skip, reserved/operator channel ensure logic. |
| **Safe mode** | OK | Errors collected; log; no silent failures. |
| **Diagnostics** | OK | Welcome step shows PHP version, pdo_mysql, json, writable root, optional warnings. |
| **POST-only run** | OK | Run step redirects to confirm if not POST. |
| **CSRF** | Present | Token generated and validated; implementation uses PHP 7+ functions (see §2, §6). |

---

## 11. All migration/seed alignment issues

| Item | Status | Notes |
|------|--------|------|
| **install_new_lupopedia.sql** | OK | Referenced and run for new install and for upgrade (at credentials/bootstrap). |
| **seed_lupopedia.sql** | OK | Run after install in both paths. |
| **import_from_old_crafty_syntax.sql** | OK | Run only on upgrade, after normalize; importer SQL not modified by wizard. |
| **drop_old_crafty_syntax_tables.sql** | OK | Exists in `database/migrations/`; run after import on upgrade. |
| **Wizard assumes schema** | Verify | `create_reserved_system_channels` and `create_operator_channels` assume `lupo_channels`, `lupo_channel_roles`, `lupo_actors` (and columns) exist. These must exist in `install_new_lupopedia.sql` and seed where applicable. Recommendation: confirm install_new_lupopedia.sql defines these tables and columns. |
| **One-time migrations** | N/A | Wizard does not run dev one-time migrations; only install, seed, import, drop. Compliant. |

---

## 12. All minimal-hosting issues

| Item | Status | Notes |
|------|--------|------|
| **Pre-flight** | OK | PHP 5.3+, pdo_mysql, json, writable project root required; mbstring, curl, openssl, fileinfo warned only. |
| **mbstring** | OK | Not required; warning only; `username_to_slug` is ASCII-safe. |
| **GD** | OK | Not used or required. |
| **Extensions** | OK | No assumption of gd, imagick, composer, frameworks. |
| **Config writable** | OK | Checked; config write path uses LUPOPEDIA_PATH. |
| **Strict types** | OK | Not used. |

---

## 13. What follows doctrine

- Pre-flight: PHP 5.3+, pdo_mysql, json, writable root; optional extensions warned only.
- No frameworks, no middleware, no Composer, no ORM, no query builders, no DB logic (triggers/procedures/views) in PHP.
- PDO only for DB (with Database Access exception noted above).
- Slug: ASCII-safe, no mbstring dependency.
- preg_*: no `/e`; safe usage.
- Reference semantics: `&$log` preserved in all helpers that mutate log.
- Wizard flow: upgrade detect → bootstrap (install+seed+reserved) → normalize → confirm → run (import → operator channels → drop) → config → complete.
- Dual-path: uses install_new_lupopedia.sql, seed_lupopedia.sql, import_from_old_crafty_syntax.sql, drop_old_crafty_syntax_tables.sql; does not modify importer SQL.
- Reserved channels 0, 1, 42, 51; operator channels created after import; legacy tables dropped.
- No declare(strict_types); no attributes, arrow functions, anonymous classes, match, named arguments, enums, typed properties, traits (in this file).

---

## 14. What is risky

- **PHP 5.3 run:** Current code will not run on PHP 5.3 (syntax and function availability). Entire file is written for PHP 7+.
- **CSRF on older PHP:** Without `random_bytes`/`random_int`/`hash_equals` fallbacks, CSRF is weak or broken on 5.3.
- **Database Access:** Raw `new PDO()` in installer may be intentional; if doctrine is applied strictly, installer might need an explicit exception or a bootstrap Factory that works without config.

---

## 15. What must be updated (summary)

1. **PHP 5.3 compatibility:** Remove all return types and parameter type hints; replace `??` with isset/ternary; replace `[]` with `array()`; replace `random_bytes`/`random_int`/`hash_equals` with 5.3-safe fallbacks; replace typed/static closure with plain closure.
2. **Class Conversion:** Plan incremental migration of wizard helpers into classes; keep existing functions as wrappers until tested; preserve `&$log` and scope.
3. **Database Access:** Either document installer exception for `new PDO()` when config does not exist, or provide a bootstrap connection path that doctrine allows.

---

## 16. What is safe

- Wizard step order and redirects.
- Session key usage and cleanup.
- SQL file paths and execution order (install → seed → reserved; upgrade: import → operator channels → drop).
- Pre-flight checks and diagnostics display.
- ASCII slug and preg usage.
- Reference parameters for log mutation.
- No use of deprecated/removed functions listed in doctrine.
- No JSON in this file (no JSON fallback needed here).
- Idempotency and reserved/operator channel logic.

---

## 17. What needs fallback logic

- **CSRF token:** `random_bytes(32)` → 5.3-safe (e.g. mt_rand + loop, or openssl_random_pseudo_bytes if available).
- **Config keys:** `random_int(0, n)` → 5.3-safe (e.g. mt_rand(0, n) with validation).
- **CSRF validation:** `hash_equals()` → 5.3-safe timing-safe compare (e.g. polyfill or inline compare with constant time where possible).

---

## 18. What needs PHP 5.3 compatibility fixes

- Remove all return type declarations (`: array`, `: int`, `: string`, `: bool`, `: void`, `: PDO`, `?array`, `?string`).
- Remove all parameter type hints (scalar and class) from function signatures if targeting 5.3 (doctrine says 5.3→8.1+; 5.3 cannot parse these).
- Replace every `??` with `isset($var) ? $var : default` (or equivalent).
- Replace every `[]` with `array()`; replace `$x !== []` with `$x !== array()` (or `count($x) > 0` where appropriate).
- Replace closure `static function (string $s): bool { ... }` with untyped `function ($s) { ... }` (and ensure $s is string in logic).
- Replace `random_bytes(32)` with a 5.3-safe 32-byte value (e.g. openssl_random_pseudo_bytes(32) if ext openssl, else fallback).
- Replace `random_int(0, n)` with mt_rand(0, n) or equivalent 5.3-safe.
- Replace `hash_equals($a, $b)` with a 5.3-safe timing-safe comparison (polyfill function).

---

## 19. What needs PHP 8.1 compatibility fixes

- No additional 8.1-specific fixes identified. Ensuring 5.3 compatibility (removing 7+ syntax) and avoiding deprecated functions will keep 8.1 safe. Optional: ensure no null is passed to internal functions expecting string (defensive casts already used in places).

---

## 20. What needs class-conversion corrections

- Migrate global wizard helpers into one or more classes (e.g. steps, SQL runner, normalizer, channel creator).
- One helper (or one logical group) at a time; keep old function as wrapper calling class method; remove wrapper after testing.
- Preserve all `array &$log` and other by-ref parameters in new methods.
- Preserve session and global usage; no DI or service containers.
- Do not add strict types, return types, or typed properties to new classes (PHP 5.3→8.1 doctrine).

---

## 21. What needs reference-semantics corrections

- None in current code. When converting to classes, do not drop `&$log` or other by-ref parameters.

---

## 22. What needs scope corrections

- None in current code. When converting to classes, preserve same session keys and variable lifetime; do not introduce new state holders that replace $_SESSION for wizard flow unless explicitly requested.

---

## 23. What needs minimal-hosting adjustments

- None. Pre-flight and slug/extension handling already match minimal-hosting doctrine. After adding 5.3 fallbacks for random and hash_equals, hosting requirements remain unchanged.

---

**End of audit. No code was changed. Awaiting user approval before making any changes.**