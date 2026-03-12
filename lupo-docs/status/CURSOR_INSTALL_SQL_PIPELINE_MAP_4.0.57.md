# Install SQL pipeline map (4.0.57)

**Date:** 2026-03-04  
**From:** Cursor (1003)  
**Purpose:** Ground truth of which SQL files run during fresh install and upgrade, and in what order.

---

## 1. Entry and directory constants

**Entry file:** `install.php` (project root).

**Constants (install.php lines 95–107):**

```php
if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', __DIR__);
}
if (!defined('LUPO_DATABASE_DIR')) {
    define('LUPO_DATABASE_DIR', LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'lupo-database');
}
if (!defined('LUPO_MYSQL_DIR')) {
    define('LUPO_MYSQL_DIR', LUPO_DATABASE_DIR . DIRECTORY_SEPARATOR . 'lupopedia' . DIRECTORY_SEPARATOR . 'mysql');
}
if (!is_dir(LUPO_MYSQL_DIR)) {
    die('MySQL installer directory not found at LUPO_MYSQL_DIR: ' . LUPO_MYSQL_DIR);
}
```

- **LUPO_MYSQL_DIR** = `{project_root}/lupo-database/lupopedia/mysql`
- All SQL paths below are built as `$mysqlDir . DIRECTORY_SEPARATOR . '<subdir>' . DIRECTORY_SEPARATOR . '<file>.sql'` where `$mysqlDir = LUPO_MYSQL_DIR` in the run step (line 528).

---

## 2. Fresh install (new) — step `run`

When `$_SESSION['lupo_install_type'] === 'new'`, user clicks “Run installation” and the run step executes. Code path: `install.php` step `run`, block starting at line 517 (`if ($step === 'run' && !$run_is_get_with_result)`), then `if ($install_type === 'new')` at 541.

**Execution order (exact line numbers):**

| Order | Line   | SQL file (relative to LUPO_MYSQL_DIR) |
|-------|--------|----------------------------------------|
| 1     | 542    | `install/install_new_lupopedia.sql` |
| 2     | 543    | `seed/seed_registry_comprehensive_4.0.45.sql` |
| 3     | 544    | `seed/seed_registry_additional_csv_entities_4.0.45.sql` |
| 4     | 545    | `seed/seed_registry_open_4.0.45.sql` |
| 5     | 546    | `seed/seed_actors_agents_4.0.45.sql` |
| —     | 547–548| PHP: `ensureSystemDepartment`, `createReservedSystemChannels` |
| —     | 551    | PHP: `ImportWizardMdImporter::importAllMdFiles` |
| 6     | 615    | `migrations/anubis_queue_tables_4.0.53.sql` |
| 7     | 617    | `migrations/20260301_anubis_database_primacy_updates.sql` |
| 8     | 619    | `seed/seed_default_sessions.sql` |
| 9     | 621    | `seed/seed_flare_content_4.0.57.sql` |
| 10    | 623    | `seed/seed_flare_apply_content_4.0.57.sql` |
| 11    | 625    | `seed/seed_docs_web_content_4.0.57.sql` |

After 11, the activations block runs (AI agents 0, 1, 2, 19), then config write and redirect.

**Proof (snippet from install.php):**

```php
// 541-548 (new install only)
if ($install_type === 'new') {
    InstallWizardSqlRunner::runSqlFile($pdo, $mysqlDir . DIRECTORY_SEPARATOR . 'install' . ... . 'install_new_lupopedia.sql', ...);
    InstallWizardSqlRunner::runSqlFile($pdo, $mysqlDir . DIRECTORY_SEPARATOR . 'seed' . ... . 'seed_registry_comprehensive_4.0.45.sql', ...);
    // ... seed_registry_additional_csv_entities, seed_registry_open, seed_actors_agents
    InstallWizardDepartments::ensureSystemDepartment($pdo, $log);
    InstallWizardChannels::createReservedSystemChannels($pdo, $log);
    InstallWizardMdImporter::importAllMdFiles($pdo, $log, $table_prefix);
}
// ...
// 615-625 (both new and upgrade)
InstallWizardSqlRunner::runSqlFile($pdo, $mysqlDir . DIRECTORY_SEPARATOR . 'migrations' . ... . 'anubis_queue_tables_4.0.53.sql', ...);
InstallWizardSqlRunner::runSqlFile($pdo, $mysqlDir . DIRECTORY_SEPARATOR . 'migrations' . ... . '20260301_anubis_database_primacy_updates.sql', ...);
InstallWizardSqlRunner::runSqlFile($pdo, $mysqlDir . DIRECTORY_SEPARATOR . 'seed' . ... . 'seed_default_sessions.sql', ...);
InstallWizardSqlRunner::runSqlFile($pdo, $mysqlDir . DIRECTORY_SEPARATOR . 'seed' . ... . 'seed_flare_content_4.0.57.sql', ...);
InstallWizardSqlRunner::runSqlFile($pdo, $mysqlDir . DIRECTORY_SEPARATOR . 'seed' . ... . 'seed_flare_apply_content_4.0.57.sql', ...);
InstallWizardSqlRunner::runSqlFile($pdo, $mysqlDir . DIRECTORY_SEPARATOR . 'seed' . ... . 'seed_docs_web_content_4.0.57.sql', ...);
```

---

## 3. Upgrade (Crafty → Lupopedia) — two phases

### 3.1 Bootstrap (immediately after credentials + “detect upgrade”)

When `$is_upgrade` is true, after credentials step POST, the wizard runs a bootstrap block (lines 338–360) then redirects to `step=bootstrap`. No “Run installation” click yet.

**Bootstrap SQL order:**

| Order | Line | SQL file |
|-------|------|----------|
| 1 | 349 | `install/install_new_lupopedia.sql` |
| 2 | 350 | `seed/seed_registry_comprehensive_4.0.45.sql` |
| 3 | 351 | `seed/seed_registry_additional_csv_entities_4.0.45.sql` |
| 4 | 352 | `seed/seed_registry_open_4.0.45.sql` |
| 5 | 353 | `seed/seed_actors_agents_4.0.45.sql` |
| — | 354 | PHP: `InstallWizardChannels::createReservedSystemChannels` |

**Note:** Doc seeds (flare, flare_apply, docs_web) are **not** run in bootstrap. They run later in the **run** step.

### 3.2 Run step (after normalize, user clicks “Run installation”)

When `$install_type === 'upgrade'`, the run step (lines 534–606) runs import and related logic; then the **same** shared block (609–625) runs for both new and upgrade. So for upgrade, after import/drop and MD import:

| Order | Line | SQL file |
|-------|------|----------|
| … | 615 | `migrations/anubis_queue_tables_4.0.53.sql` |
| … | 617 | `migrations/20260301_anubis_database_primacy_updates.sql` |
| … | 619 | `seed/seed_default_sessions.sql` |
| … | 621 | `seed/seed_flare_content_4.0.57.sql` |
| … | 623 | `seed/seed_flare_apply_content_4.0.57.sql` |
| … | 625 | `seed/seed_docs_web_content_4.0.57.sql` |

So **doc seed files 9–11 run on both new install and upgrade**.

---

## 4. Full path summary (fresh install)

| # | Full path (conceptual) |
|---|------------------------|
| 1 | `{project}/lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` |
| 2 | `.../mysql/seed/seed_registry_comprehensive_4.0.45.sql` |
| 3 | `.../mysql/seed/seed_registry_additional_csv_entities_4.0.45.sql` |
| 4 | `.../mysql/seed/seed_registry_open_4.0.45.sql` |
| 5 | `.../mysql/seed/seed_actors_agents_4.0.45.sql` |
| 6 | `.../mysql/migrations/anubis_queue_tables_4.0.53.sql` |
| 7 | `.../mysql/migrations/20260301_anubis_database_primacy_updates.sql` |
| 8 | `.../mysql/seed/seed_default_sessions.sql` |
| 9 | `.../mysql/seed/seed_flare_content_4.0.57.sql` |
| 10| `.../mysql/seed/seed_flare_apply_content_4.0.57.sql` |
| 11| `.../mysql/seed/seed_docs_web_content_4.0.57.sql` |

---

## 5. Doc-seed inclusion proof

- **seed_flare_content_4.0.57.sql** — Called at line 621 inside the same `try` as the rest of the run step; runs for both `new` and `upgrade` (lines 619–625 are outside the `if ($install_type === 'new')` and `if ($install_type === 'upgrade')` blocks).
- **seed_flare_apply_content_4.0.57.sql** — Line 623.
- **seed_docs_web_content_4.0.57.sql** — Line 625.

All three are executed via `InstallWizardSqlRunner::runSqlFile($pdo, $path, $log, $table_prefix)`. If any file is missing, the `runSqlFile` call would fail and the exception would be caught (run step catch block), so the pipeline is the one that actually runs.
