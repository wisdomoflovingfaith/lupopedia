# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\audits\INSTALL_WIZARD_STEP5_STEP6_FINAL_REPORT.md"
  file_hash: "d85729ba7b4c9c6c701aba9199916fcdcc4bd84c7495f62b44d88c1b0f58c481"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
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

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "lupo-docs\audits\INSTALL_WIZARD_STEP5_STEP6_FINAL_REPORT.md"
  file_hash: "bebcc0a8c6712dcbcc94468252d789b5c9aab435e655d3664f3b8e033ae61c5c"
  file_path_from_root: "lupo-docs\audits\INSTALL_WIZARD_STEP5_STEP6_FINAL_REPORT.md"
  file_hash: "6121d918d92072996a8474c8b6471d07811cf7c22c33da2588f040e48b721b32"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Install Wizard — Step 5 (Class Conversion) + Step 6 (Final Cleanup) — Final Report"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "audits", "install_wizard_step5_step6_final_reportmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Install Wizard — Step 5 (Class Conversion) + Step 6 (Final Cleanup) — Final Report

**Date:** 2026-02-04  
**Reference:** lupo-docs/audits/INSTALL_PHP_WIZARD_FIX_PLAN.md §9 (Execution Plan)  
**Status:** Complete. All helpers converted to classes; wrappers removed; direct class calls in install.php.

---

## 1. Helpers Converted

| # | Helper (original) | Class | Method |
|---|-------------------|--------|--------|
| 1 | get_wizard_steps | InstallWizardSteps | getWizardSteps() |
| 2 | get_current_step_index | InstallWizardSteps | getCurrentStepIndex($step) |
| 3 | get_total_steps | InstallWizardSteps | getTotalSteps() |
| 4 | get_csrf_token | InstallWizardSecurity | getCsrfToken() |
| 5 | validate_csrf | InstallWizardSecurity | validateCsrf() |
| 6 | log_entry | InstallWizardLogger | logEntry($type, $message) |
| 7 | safe_error_message | InstallWizardLogger | safeErrorMessage($context) |
| 8 | get_db_credentials | InstallWizardCredentials | getDbCredentials() |
| 9 | connect_pdo | InstallWizardDb | connectPdo($vars) |
| 10 | detect_livehelp_tables | InstallWizardDb | detectLivehelpTables($pdo) |
| 11 | run_sql_file | InstallWizardSqlRunner | runSqlFile($pdo, $path, &$log) |
| 12 | drop_livehelp_tables | InstallWizardSqlRunner | dropLivehelpTables($pdo, $tables, &$log) |
| 13 | write_config | InstallWizardConfigWriter | writeConfig($db_vars, &$log, $options) |
| 14 | username_to_slug | InstallWizardNormalize | usernameToSlug($username) |
| 15 | is_valid_email | InstallWizardNormalize | isValidEmail($email) |
| 16 | is_valid_lupopedia_slug | InstallWizardNormalize | isValidLupopediaSlug($s) |
| 17 | is_acceptable_resolved_email | InstallWizardNormalize | isAcceptableResolvedEmail($value) |
| 18 | load_crafty_users | InstallWizardNormalize | loadCraftyUsers($pdo) |
| 19 | compute_proposed_identities | InstallWizardNormalize | computeProposedIdentities($users) |
| 20 | find_duplicate_email_groups | InstallWizardNormalize | findDuplicateEmailGroups($identities) |
| 21 | collect_normalize_warnings | InstallWizardNormalize | collectNormalizeWarnings($identities) |
| 22 | validate_resolved_emails | InstallWizardNormalize | validateResolvedEmails($identities, $resolved) |
| 23 | apply_normalization_to_livehelp | InstallWizardNormalize | applyNormalizationToLivehelp($pdo, $identities, $resolvedEmails) |
| 24 | create_reserved_system_channels | InstallWizardChannels | createReservedSystemChannels($pdo, &$log) |
| 25 | create_operator_channels | InstallWizardChannels | createOperatorChannels($pdo, &$log) |
| 26 | ensure_reserved_channels | InstallWizardChannels | ensureReservedChannels($pdo, &$log) |
| 27 | ensure_operator_channels | InstallWizardChannels | ensureOperatorChannels($pdo, &$log) |

---

## 2. Classes Created

| Class | File | Methods (all static) |
|-------|------|----------------------|
| InstallWizardSteps | install_wizard_classes.php | getWizardSteps, getCurrentStepIndex, getTotalSteps |
| InstallWizardSecurity | install_wizard_classes.php | getCsrfToken, validateCsrf |
| InstallWizardLogger | install_wizard_classes.php | logEntry, safeErrorMessage |
| InstallWizardCredentials | install_wizard_classes.php | getDbCredentials |
| InstallWizardDb | install_wizard_classes.php | connectPdo, detectLivehelpTables |
| InstallWizardSqlRunner | install_wizard_classes.php | runSqlFile, dropLivehelpTables |
| InstallWizardConfigWriter | install_wizard_classes.php | writeConfig |
| InstallWizardNormalize | install_wizard_classes.php | usernameToSlug, isValidEmail, isValidLupopediaSlug, isAcceptableResolvedEmail, loadCraftyUsers, computeProposedIdentities, findDuplicateEmailGroups, collectNormalizeWarnings, validateResolvedEmails, applyNormalizationToLivehelp |
| InstallWizardChannels | install_wizard_classes.php | createReservedSystemChannels, createOperatorChannels, ensureReservedChannels, ensureOperatorChannels |

**Single file:** `install_wizard_classes.php` (project root). Loaded by install.php via `require_once LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'install_wizard_classes.php';`

---

## 3. Wrappers Removed

All 27 wrapper functions were removed from install.php. There are no remaining global helpers for the above; install.php calls the class static methods directly (e.g. `InstallWizardSecurity::validateCsrf()`, `InstallWizardLogger::logEntry(...)`).

---

## 4. Reference Semantics Preserved

- **run_sql_file / drop_livehelp_tables:** Method signatures keep `&$log`; call sites pass `$bootstrapLog` or `$log` by reference; arrays are mutated in place.
- **write_config:** `&$log` preserved in InstallWizardConfigWriter::writeConfig and in install.php call.
- **create_reserved_system_channels, create_operator_channels, ensure_reserved_channels, ensure_operator_channels:** All take `&$log`; behavior unchanged.

No reference semantics were removed or altered.

---

## 5. Scope Preserved

- No dependency injection, no service containers, no new global state.
- Session and superglobals are read/written the same way as before (e.g. $_SESSION['lupo_install_type'], $_POST, etc.).
- Classes are used as structured namespaces (static methods only); no instance state that would change lifetime or visibility.

---

## 6. No Modern PHP Syntax

- install.php and install_wizard_classes.php use only: `array()`, `isset() ? : default`, no return types, no parameter type hints, no nullable types, no short arrays, no null coalescing, no arrow functions, no attributes.
- Fallbacks (lupo_random_bytes, lupo_random_int, lupo_hash_equals) remain in install.php and are used by the classes where needed.

---

## 7. Installer / Wizard Behavior Unchanged

- Step order, redirects, session keys, idempotency (e.g. lupo_import_run, ensure_reserved_channels, ensure_operator_channels) are unchanged.
- CSRF remains POST-only; token generation and validation use the same helpers.
- Schema and SQL run order (install → seed → reserved → import → operator channels → drop → config) unchanged.

---

## 8. PHP 5.3 → 8.1+ Compatibility

- `php -l install.php` and `php -l install_wizard_classes.php` pass.
- No PHP 7+ syntax in either file; both are valid on PHP 5.3 and PHP 8.1+.

---

## 9. Doctrine Compliance

- Class conversion follows CLASS_CONVERSION_DOCTRINE.md: incremental, wrappers removed after direct calls in place, reference semantics and scope preserved, no optimization beyond compatibility.
- PHP 5.3 compatibility per PHP_COMPATIBILITY_AND_MINIMAL_HOSTING_DOCTRINE.md.
- No framework, ORM, middleware, or Composer; PDO-only; installer-only exception for `new PDO()` documented in connect_pdo (now in InstallWizardDb::connectPdo).

---

## 10. Post-Report Fix

One remaining call to the old helper name was updated during this report:

- **install.php line 467:** `connect_pdo($db_vars)` → `InstallWizardDb::connectPdo($db_vars)` (config-step admin-email collision check).

No other wrapper or legacy helper calls remain in install.php.

---

**End of report.**
