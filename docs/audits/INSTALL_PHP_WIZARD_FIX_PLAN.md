# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\audits\INSTALL_PHP_WIZARD_FIX_PLAN.md"
  file_hash: "5d605d4ebfed5acd7a359d7924d7f8558033e90399b846b6d7093fcc2adc6b53"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Install.php + Wizard — Fix Plan (Do NOT Apply Yet)"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "audits", "install_php_wizard_fix_planmd"]
  lupo_agent: "windsurf"

flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Install.php + Wizard — Fix Plan (Do NOT Apply Yet)

**Date:** 2025-02-04  
**Purpose:** Structured plan to bring installer and wizard into full doctrine compliance. **No code has been modified. No patches applied. Await explicit approval before applying any fix.**

Doctrine targets: PHP 5.3→8.1 compatibility; no-framework / no-middleware / no-Composer; no ORM / no query builders; no triggers/procedures/DB logic; ASCII-safe fallbacks; PDO-only; class-conversion; reference-semantics; scope-preservation; minimal hosting; dual-path schema; wizard repeatability.

---

## 1. PHP 5.3 Syntax Fixes

All references are to **install.php** unless otherwise stated.

### 1.1 Replace `[]` with `array()`

- **Lines:** 104, 132, 133, 147–154, 158–163, 209, 254, 294, 367 (default param), 370, 486, 495, 532, 536, 548, 572, 573, 577, 596, 604, 614, 710, 735, 806, 808, 847, 914, 954, 955, 956, 957, 989, 1021, 1040, 1115, 1134, 1180, 1212, 1401, 1409, 1524, 1525.
- **Action:** Replace every `= []`, `=> []`, `: []`, and literal `[]` in return/array literals with `array()`. Replace `$x !== []` (line 808) with `$x !== array()` or `count($x) > 0`.
- **Note:** Return arrays like `['id' => 'welcome', 'label' => 'Welcome']` at 147–163 must become `array('id' => 'welcome', 'label' => 'Welcome')`.

### 1.2 Replace `??` with `isset()` ternary

- **Lines:** 83, 91, 97, 103, 129, 144, 198, 199, 232, 243, 497, 498, 499, 506, 517, 576, 696, 755, 756, 861, 862, 894, 909, 929, 930, 942, 944, 948, 960, 962, 965, 967, 986, 992, 1011, 1020, 1021, 1023, 1027, 1031, 1038, 1039, 1040, 1042, 1046, 1059, 1119, 1122, 1124, 1125, 1126, 1127, 1128, 1129, 1130, 1131, 1163, 1164, 1178, 1201, 1212, 1213, 1214, 1215, 1216, 1223, 1228, 1331, 1332, 1333, 1334, 1409, 1419, 1451, 1453, 1482, 1496, 1498, 1528.
- **Action:** Replace each `$var ?? default` with `isset($var) ? $var : default`. For chained `??` (e.g. `$_POST['x'] ?? $_SESSION['x'] ?? 'y'`) use nested ternary or multiple isset checks.

### 1.3 Remove return types

- **Lines (function definitions):** 143, 167, 177, 184, 194, 205, 218, 225, 282, 293, 305, 351, 367, 449, 460, 469, 475, 484, 494, 531, 547, 571, 628, 692, 709, 802, 821.
- **Action:** Remove `: array`, `: int`, `: string`, `: bool`, `: void`, `: PDO`, `?array`, `?string` from each function signature.

### 1.4 Remove scalar and parameter type hints

- **Same function list as 1.3.** Parameters to adjust: `string $step`, `string $type`, `string $message`, `string $context`, `array $vars`, `PDO $pdo`, `string $path`, `array &$log`, `array $tables`, `array $options`, `string $username`, `string $email`, `string $s`, `string $value`, `array $users`, `array $identities`, `array $resolved`, `array $identities` (repeated), `array $resolvedEmails`, etc.
- **Action:** Remove all type hints from parameters (e.g. `function get_wizard_steps()` not `function get_wizard_steps(): array` and no typed params). Preserve `&` for by-reference parameters (e.g. `array &$log` becomes `&$log` only if removing type; doctrine says preserve reference, so keep `&$log`, just remove the `array` hint).

### 1.5 Remove nullable types

- **Line 225:** `get_db_credentials(): ?array` → remove `?array` return type.
- **Line 367:** `write_config(..., array $options = []): ?string` → remove `?string` return type; change default to `array()` not `[]`.

### 1.6 Rewrite typed closure

- **Lines 319–323:** `array_filter(..., static function (string $s): bool { return $s !== '' && !preg_match(...); })`
- **Action:** Replace with untyped, non-static closure: `function ($s) { return $s !== '' && !preg_match('/^\s*SET\s+/i', $s); }`. Remove `static` and parameter/return types.

### 1.7 Other PHP 7+ syntax

- No `match`, named arguments, attributes, arrow functions, or spread in arrays in this file. After 1.1–1.6, no further syntax changes required for PHP 5.3 compatibility in this file.

---

## 2. PHP 7/8 Function Fallbacks

### 2.1 random_bytes

- **Location:** install.php, line 186, in `get_csrf_token()`.
- **Current:** `$_SESSION['lupo_csrf_token'] = bin2hex(random_bytes(32));`
- **Required:** Provide a 5.3-safe source of 32 cryptographically random bytes. Options: (a) if `function_exists('openssl_random_pseudo_bytes')`, use `openssl_random_pseudo_bytes(32)` and bin2hex; (b) else use a fallback (e.g. mt_rand loop building 32 bytes — document as weak for 5.3 without openssl). Insert a small helper (e.g. `lupo_random_bytes(32)`) at top of file or in a tiny include used only by install, returning string of 32 bytes; then `bin2hex(lupo_random_bytes(32))`.
- **Integration:** Called only when generating CSRF token; no change to wizard step flow. Fallback must run before any step that needs the token.

### 2.2 random_int

- **Location:** install.php, line 374, in `write_config()` (config key generation loop).
- **Current:** `$keyValues[$k] .= $auth[random_int(0, strlen($auth) - 1)];`
- **Required:** 5.3-safe random integer in range. If `function_exists('random_int')` use it; else use `mt_rand(0, strlen($auth) - 1)`. Wrap in helper e.g. `lupo_random_int($min, $max)` so one call site.
- **Integration:** Only used when writing config file; no impact on step flow. Fallback used only when random_int not available.

### 2.3 hash_equals

- **Location:** install.php, line 199, in `validate_csrf()`.
- **Current:** `return $token !== '' && hash_equals($_SESSION['lupo_csrf_token'] ?? '', $token);`
- **Required:** Timing-safe string comparison for PHP 5.3. Add polyfill: if `function_exists('hash_equals')` use it; else implement constant-time compare (e.g. compare length first, then loop byte-by-byte with XOR). Replace `??` in same line per section 1.2.
- **Integration:** Used on every POST (except start_over). Polyfill must be defined before first validate_csrf() call.

### 2.4 Other PHP 7+ functions

- No other PHP 7/8-only functions identified in install.php (no json_encode/json_decode in this file; date('c'), filter_var, PDO, preg_* are 5.3-safe). No further fallbacks required in this file.

---

## 3. Class Conversion Plan

### 3.1 Helpers to migrate and suggested class

| Helper | Suggested class | Preserve `&$log` / refs | Safe for optional optimization later |
|--------|-----------------|-------------------------|--------------------------------------|
| get_wizard_steps | InstallWizardSteps | N/A | Yes (pure) |
| get_current_step_index | InstallWizardSteps | N/A | Yes |
| get_total_steps | InstallWizardSteps | N/A | Yes |
| get_csrf_token | InstallWizardSecurity | N/A | No (security-sensitive) |
| validate_csrf | InstallWizardSecurity | N/A | No |
| log_entry | InstallWizardLogger | N/A | Yes |
| safe_error_message | InstallWizardLogger or InstallWizardSteps | N/A | Yes |
| get_db_credentials | InstallWizardCredentials | N/A | Yes |
| connect_pdo | InstallWizardDb | N/A | No (DB; keep PDO only) |
| detect_livehelp_tables | InstallWizardDb | N/A | Yes |
| run_sql_file | InstallWizardSqlRunner | **Yes &$log** | No |
| drop_livehelp_tables | InstallWizardSqlRunner | **Yes &$log** | No |
| write_config | InstallWizardConfigWriter | **Yes &$log** | No |
| username_to_slug | InstallWizardNormalize | N/A | Yes |
| is_valid_email | InstallWizardNormalize | N/A | Yes |
| is_valid_lupopedia_slug | InstallWizardNormalize | N/A | Yes |
| is_acceptable_resolved_email | InstallWizardNormalize | N/A | Yes |
| load_crafty_users | InstallWizardNormalize | N/A | Yes |
| compute_proposed_identities | InstallWizardNormalize | N/A | Yes |
| find_duplicate_email_groups | InstallWizardNormalize | N/A | Yes |
| collect_normalize_warnings | InstallWizardNormalize | N/A | Yes |
| validate_resolved_emails | InstallWizardNormalize | N/A | Yes |
| create_reserved_system_channels | InstallWizardChannels | **Yes &$log** | No |
| apply_normalization_to_livehelp | InstallWizardNormalize | N/A | Yes |
| create_operator_channels | InstallWizardChannels | **Yes &$log** | No |
| ensure_reserved_channels | InstallWizardChannels | **Yes &$log** | No |
| ensure_operator_channels | InstallWizardChannels | **Yes &$log** | No |

### 3.2 Reference-semantics preservation

- **Must preserve `array &$log`:** run_sql_file, drop_livehelp_tables, write_config, create_reserved_system_channels, create_operator_channels, ensure_reserved_channels, ensure_operator_channels. When moved to class methods, signature must keep `&$log` (e.g. `public function runSqlFile($pdo, $path, &$log)`). Do not replace with return value unless user explicitly requests optimization for that function.

### 3.3 Wrapper strategy

- For each migrated helper: (1) Add class and method (e.g. `InstallWizardSteps::getWizardSteps()`). (2) Keep existing global function in install.php. (3) In function body, replace implementation with single call to class method (e.g. `return InstallWizardSteps::getWizardSteps();` or instance call if stateful). (4) Test wizard end-to-end. (5) Remove wrapper only after approval and re-test. One helper (or one logical group) at a time; do not convert all helpers in one change.

### 3.4 Do not perform conversion yet

- This plan does not include implementing the classes or wrappers. Execute only after approval and in the order specified in Section 9.

---

## 4. Scope & Reference Semantics Review

### 4.1 Helpers that rely on global state

- **get_wizard_steps:** Reads `$_SESSION['lupo_install_type']`. Preserve session access in class (pass session or read from global in method).
- **get_csrf_token, validate_csrf:** Read/write `$_SESSION['lupo_csrf_token']`, read `$_POST['lupo_csrf']`, `$_SERVER['REQUEST_METHOD']`. Preserve.
- **log_entry:** Reads/writes `$_SESSION['lupo_wizard_audit_log']`. Preserve.
- **get_db_credentials:** Reads `$_POST`, `$_SESSION['lupo_install_db_vars']`, and file system (Crafty config paths). Preserve.
- All other helpers either receive PDO/arrays as arguments or use session/post only via callers. When converting, do not introduce new global or static state beyond what is needed to preserve behavior (e.g. same $_SESSION keys).

### 4.2 Helpers that mutate arrays by reference

- **run_sql_file($pdo, $path, array &$log):** Appends to `$log`. Preserve `&$log`.
- **drop_livehelp_tables(..., array &$log):** Appends to `$log`. Preserve `&$log`.
- **write_config(..., array &$log, ...):** Appends to `$log`. Preserve `&$log`.
- **create_reserved_system_channels($pdo, array &$log):** Appends to `$log`. Preserve `&$log`.
- **create_operator_channels($pdo, array &$log):** Appends to `$log`. Preserve `&$log`.
- **ensure_reserved_channels($pdo, array &$log):** Appends to `$log`. Preserve `&$log`.
- **ensure_operator_channels($pdo, array &$log):** Appends to `$log`. Preserve `&$log`.

### 4.3 Helpers that must preserve `&$log`

- Same seven as 4.2. No removal of reference semantics without explicit user request to optimize that specific function.

### 4.4 Helpers that require careful scope preservation

- **get_db_credentials:** Returns credentials from POST, then session, then file; multiple code paths. Preserve order and side effects (no file read if POST present, etc.).
- **run_sql_file:** Mutates `$log` in loop; callers rely on log array being updated. Preserve pass-by-reference.
- **write_config:** Mutates `$log`; uses `$options` array; writes file. Preserve same scope (no new temp object holding options if it changes lifetime).
- All step-handling logic in main script (not in helpers) uses `$errors`, `$log`, `$step`, session. When extracting to classes, do not move step flow into a class that holds state in instance properties unless session is still the source of truth and class only reads/writes it.

---

## 5. Wizard Flow Fixes

### 5.1 CSRF fixes

- **Current:** CSRF uses random_bytes (line 186) and hash_equals (line 199), which are not 5.3-safe.
- **Required:** Implement fallbacks per Section 2.1 and 2.3 so token generation and validation work on PHP 5.3. No change to flow (still generate on first use, validate on POST). Ensure token is stored in session and compared in constant time.

### 5.2 Session lifecycle fixes

- **Current:** Start over clears listed keys; complete unsets install-related keys. No doctrine violation found.
- **Required:** None for doctrine. Optional: ensure all wizard keys are listed in start_over and complete so no orphan keys remain (audit did not find missing keys).

### 5.3 Idempotency fixes

- **Current:** Import skip via lupo_import_run; ensure_reserved_channels; ensure_operator_channels. Compliant.
- **Required:** None. Keep current idempotency logic when applying syntax/fallback fixes.

### 5.4 Logic updates for doctrine compliance

- **Database Access:** Doctrine forbids `new PDO()` in application code; installer runs before config. **Decision required:** Either (a) document an explicit installer exception in doctrine (install.php may use `new PDO()` when config does not exist), or (b) introduce a bootstrap connection helper that doctrine permits (e.g. a single place that creates PDO for install only, documented as exception). Plan does not change connect_pdo behavior until decision is made.
- **No other wizard flow logic** needs to change for doctrine; step order, redirects, and SQL run order are correct.

---

## 6. Schema Alignment Fixes

### 6.1 Tables referenced by wizard to verify in install SQL

- **lupo_channels:** create_reserved_system_channels and create_operator_channels insert into this table. Verify install_new_lupopedia.sql defines lupo_channels with columns: channel_id, federation_node_id, created_by_actor_id, default_actor_id, department_id, channel_key, channel_slug, channel_type, language, channel_name, description, website_link, status_flag, created_ymdhis, updated_ymdhis, is_deleted, is_kernel.
- **lupo_channel_roles:** Same two functions insert channel_role_id, channel_id, actor_id, role_type, created_ymdhis, updated_ymdhis, is_deleted. Verify in install_new_lupopedia.sql.
- **lupo_actors:** create_operator_channels selects from lupo_actors (actor_id, slug, name, actor_source_type, is_deleted). Verify table and columns exist in install_new_lupopedia.sql (or are created by import/seed).
- **livehelp_users:** load_crafty_users, apply_normalization_to_livehelp. Legacy table; no change in install SQL. Confirmed.
- **lupo_auth_users:** Config step checks admin email against this table. Verify table exists after install+seed+import.

### 6.2 Seed rows to add

- Audit did not identify missing seed rows. If verification in 6.1 shows that lupo_actors or required rows for operator detection are missing from seed_lupopedia.sql, add them per dual-path doctrine (one-time migration if needed, plus seed_lupopedia.sql, plus wizard unchanged).

### 6.3 Migration alignment

- Wizard runs install_new_lupopedia.sql, seed_lupopedia.sql, import_from_old_crafty_syntax.sql, drop_old_crafty_syntax_tables.sql in the documented order. No change to which files are run. Only verification that install_new_lupopedia.sql (and seed) define the tables/columns the wizard expects.

---

## 7. Minimal Hosting Fixes

### 7.1 mbstring assumptions

- **Current:** username_to_slug uses strtolower and preg_replace; no mbstring. Pre-flight warns if mbstring missing. No fix required.

### 7.2 Extension assumptions

- **Current:** Pre-flight blocks only on PHP 5.3+, pdo_mysql, json, writable root. Warns on mbstring, curl, openssl, fileinfo. No fix required. Fallbacks for random_bytes/random_int/hash_equals (Section 2) should use extensions only when available (e.g. openssl_random_pseudo_bytes if loaded) and not require them.

### 7.3 File-write assumptions

- **Current:** Config write checks is_writable(LUPOPEDIA_PATH) and writes lupopedia-config.php. No fix required.

### 7.4 Fallback logic needed

- Only the PHP 5.3 function fallbacks in Section 2. No additional minimal-hosting fallbacks (slug already ASCII-safe; no GD/curl required).

---

## 8. Risk Assessment

### 8.1 High-risk changes

- **Removing all return types and type hints in one pass:** Risk of introducing typos or wrong parameter order when editing many functions. Mitigation: do in small batches (e.g. 5–10 functions at a time) and run wizard after each batch.
- **Replacing every `??` and `[]` in one pass:** Risk of missing an occurrence or breaking nested structures. Mitigation: use search/replace with care; run full wizard path (new install + upgrade) after syntax fixes.
- **Class conversion:** Risk of breaking reference semantics or session scope if a method signature or call site is wrong. Mitigation: convert one helper at a time; keep wrapper; test; preserve &$log in method and caller.

### 8.2 Low-risk changes

- **Adding fallback helpers (lupo_random_bytes, lupo_random_int, lupo_hash_equals):** Isolated; no change to step flow. Low risk.
- **Replacing single closure (lines 319–323):** One small block. Low risk.
- **Schema verification (6.1):** Read-only check of install_new_lupopedia.sql and seed; no code change to wizard. Low risk.

### 8.3 Changes that require user approval

- **Database Access (connect_pdo / new PDO):** Whether to document an installer exception or add a bootstrap connection abstraction. User must approve approach.
- **Class conversion scope:** Which classes to introduce and in what order. User may prefer different grouping (e.g. one class per step). Approval before starting conversion.
- **Optional optimization:** Any future removal of &$log or simplification of helpers may be done only when user explicitly requests it for a specific function.

### 8.4 Changes that require incremental testing

- **All PHP 5.3 syntax fixes:** Test on PHP 5.3 (or 5.4) and on PHP 8.1 after each batch (welcome → credentials → … → complete).
- **Fallback insertion:** Test CSRF (submit form, then submit with wrong token); test config write (full install to completion).
- **Each class conversion:** After moving one helper to a class and adding wrapper, run new install and upgrade path before proceeding.

---

## 9. Execution Plan

Apply fixes in the following order. Do not apply any fix until explicit approval.

### Step 1: PHP 5.3 syntax fixes (install.php)

1. **1.1** Replace all `[]` with `array()` (including default parameter and `$x !== []`).
2. **1.2** Replace all `??` with `isset(…) ? … : default`.
3. **1.3** Remove all return types from function declarations.
4. **1.4** Remove all parameter type hints (keep `&` for by-reference parameters).
5. **1.5** Remove nullable types; fix default `[]` to `array()` in write_config.
6. **1.6** Rewrite the array_filter closure (lines 319–323) to untyped, non-static.

Run wizard once (new install + upgrade path) on PHP 5.3 (or lowest target) and PHP 8.1. Fix any regressions before Step 2.

### Step 2: PHP 7/8 function fallbacks (install.php)

1. **2.1** Add lupo_random_bytes (or equivalent) and use in get_csrf_token (line 186).
2. **2.2** Add lupo_random_int (or equivalent) and use in write_config (line 374).
3. **2.3** Add lupo_hash_equals (or equivalent) and use in validate_csrf (line 199); ensure the `??` on that line was already replaced in Step 1.

Test CSRF (valid token, invalid token) and config write on PHP 5.3 and 8.1.

### Step 3: CSRF and wizard logic

1. **5.1** Confirm CSRF flow unchanged; only implementation is 5.3-safe.
2. **5.4** Resolve Database Access: either document installer exception for new PDO or add permitted bootstrap connection (user-approved approach).

No change to step order or session lifecycle.

### Step 4: Schema alignment

1. **6.1** Verify install_new_lupopedia.sql (and seed_lupopedia.sql if needed) defines lupo_channels, lupo_channel_roles, lupo_actors with columns used by create_reserved_system_channels and create_operator_channels.
2. **6.2** If any required table or column is missing, add via one-time migration and install_new_lupopedia.sql (and seed if needed) per dual-path doctrine; do not change wizard logic unless schema contract changes.

### Step 5: Class conversion (incremental, reference-aware)

1. Introduce first class (e.g. InstallWizardSteps). Add methods for get_wizard_steps, get_current_step_index, get_total_steps. Keep global functions as wrappers calling class. Test.
2. Next class (e.g. InstallWizardSecurity): get_csrf_token, validate_csrf. Wrappers; test.
3. Next: InstallWizardLogger (log_entry, safe_error_message). Wrappers; test.
4. Next: InstallWizardCredentials (get_db_credentials). Wrappers; test.
5. Next: InstallWizardDb (connect_pdo, detect_livehelp_tables). Wrappers; test.
6. Next: InstallWizardSqlRunner (run_sql_file, drop_livehelp_tables) — preserve &$log. Wrappers; test.
7. Next: InstallWizardConfigWriter (write_config) — preserve &$log. Wrappers; test.
8. Next: InstallWizardNormalize (username_to_slug, is_valid_*, load_crafty_users, compute_proposed_identities, find_duplicate_email_groups, collect_normalize_warnings, validate_resolved_emails, apply_normalization_to_livehelp). Wrappers; test.
9. Next: InstallWizardChannels (create_reserved_system_channels, create_operator_channels, ensure_reserved_channels, ensure_operator_channels) — preserve &$log. Wrappers; test.
10. After all wrappers tested, optionally remove wrappers one at a time and update call sites to use classes directly; test after each removal. (Or keep wrappers permanently if preferred.)

### Step 6: Final cleanup

1. Optional: add count() guard where doctrine requires (e.g. before count($validation['errors']) if that value could be non-array).
2. Final run: new install and upgrade on PHP 5.3 (or 5.4) and PHP 8.1.
3. Update audit doc or fix plan to mark items as done (after approval).

---

**End of fix plan. No code has been modified. Await explicit approval before applying any fix.**
