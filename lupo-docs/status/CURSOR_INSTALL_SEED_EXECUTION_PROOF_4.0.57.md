# Install seed execution proof (4.0.57)

**Date:** 2026-03-04  
**From:** Cursor (1003)  
**Purpose:** Prove that the three doc seed files run in the correct order on fresh install and upgrade.

---

## 1. Install entrypoints

The only code path that runs DB install/seed for Lupopedia is the install wizard in `install.php`. There is no separate CLI installer or Docker entrypoint that runs these seeds.

### 1.1 Fresh install (new)

**Entry:** User completes credentials, confirm, then "Run installation" → `install.php` step `run` with `$_SESSION['lupo_install_type'] === 'new'`.

**File:** `install.php`. **Block:** Lines 517–659. When `$install_type === 'new'`, lines 541–551 run (install + registry seeds + channels + MD import). The **shared** block 609–625 then runs and executes the three doc seeds.

**Exact lines (619–625):**

```php
InstallWizardSqlRunner::runSqlFile($pdo, $mysqlDir . DIRECTORY_SEPARATOR . 'seed' . DIRECTORY_SEPARATOR . 'seed_default_sessions.sql', $log, $table_prefix);
InstallWizardSqlRunner::runSqlFile($pdo, $mysqlDir . DIRECTORY_SEPARATOR . 'seed' . DIRECTORY_SEPARATOR . 'seed_flare_content_4.0.57.sql', $log, $table_prefix);
InstallWizardSqlRunner::runSqlFile($pdo, $mysqlDir . DIRECTORY_SEPARATOR . 'seed' . DIRECTORY_SEPARATOR . 'seed_flare_apply_content_4.0.57.sql', $log, $table_prefix);
InstallWizardSqlRunner::runSqlFile($pdo, $mysqlDir . DIRECTORY_SEPARATOR . 'seed' . DIRECTORY_SEPARATOR . 'seed_docs_web_content_4.0.57.sql', $log, $table_prefix);
```

`$mysqlDir = LUPO_MYSQL_DIR` (line 528).

### 1.2 Upgrade (Crafty → Lupopedia)

**Entry:** Credentials → bootstrap (install + registry + actors + channels only; **no** doc seeds) → normalize → "Run installation" → step `run` with `$install_type === 'upgrade'`.

Same file and run step. For upgrade, 554–606 runs (import, channels, drop); then the **same** shared block 609–625 runs, so the three doc seeds run.

**Conclusion:** The three doc seed files run in **both** fresh install and upgrade, in the same order (621 → 623 → 625).

---

## 2. Execution order

| Order | Line | Seed file | content_id(s) |
|-------|------|-----------|----------------|
| 1 | 621 | seed_flare_content_4.0.57.sql | 2998 |
| 2 | 623 | seed_flare_apply_content_4.0.57.sql | 2999 |
| 3 | 625 | seed_docs_web_content_4.0.57.sql | 2996, 2997 |

Order is fixed and unconditional.

---

## 3. Bootstrap does not run doc seeds

Upgrade bootstrap (349–354) runs only install_new_lupopedia.sql and four registry/actor seeds plus createReservedSystemChannels. Doc seeds run only in the run step (619–625), which runs for both new and upgrade.
