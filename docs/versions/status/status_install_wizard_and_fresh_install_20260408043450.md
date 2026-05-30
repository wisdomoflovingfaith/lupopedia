---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/versions/status/status_install_wizard_and_fresh_install_20260408043450.md
  web_path: https://www.lupopedia.com/lupopedia/docs/versions/status/status_install_wizard_and_fresh_install_20260408043450.md
  status: ''
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: status
  artifact_kind: version_handoff
  channel_key: null
  federation_node_id: null
  thread_key: ''
  lupopedia.schema: documentation
  prd_cluster: null
  title: ''
  summary: ''
---
# file: STATUS_INSTALL_WIZARD_AND_FRESH_INSTALL — delegation: cursor:root

# Status: Install wizard changes and fresh-install safety (2026-04-08)

**Temporal anchor:** `20260408043450` UTC (`python bin/tick.py`).

---

## What changed (installer + related)

| Area | Change |
|------|--------|
| **No `information_schema`** | Shipped installer code **does not** query `information_schema` (shared hosts often deny it). Table existence uses **`SHOW TABLES LIKE`** with **`InstallWizardDb::escapeMysqlLikePattern()`** so `_` / `%` match literally. |
| **`InstallWizardDb::tableExists()`** | Central helper used by upgrade schema check, ANUBIS table verification, Crafty pre-migration table loop, and `livehelp_users` gate in channel wizard code. |
| **`InstallWizardDb::detectLivehelpTables()`** | Uses pattern **`livehelp\_%`** (escaped underscore) so detection matches the `livehelp_` prefix, not SQL `LIKE` single-char wildcards. |
| **`InstallWizardDb::connectPdoBuffered()`** | Sets **`PDO::ATTR_STRINGIFY_FETCHES => true`** and **`PDO::ATTR_EMULATE_PREPARES => false`** (trust-ladder / BIGINT string handling for PDO_DB during AI activation). |
| **`InstallWizardSqlRunner::applyTablePrefixToSql()`** | Replaces literal **`lupo_`** only **outside** single-quoted strings when prefix is not `lupo_`, so string data in `VALUES (...)` is not corrupted. |
| **`InstallWizardCredentials::validateCraftyPreMigration()`** | Optional **`$table_prefix`**; **`LEFT JOIN`** uses **`{prefix}actors`**, not hardcoded **`lupo_actors`**; required Crafty tables checked via **`tableExists()`**. |
| **`install.php`** | Departments schema probe and ANUBIS table checks use **`tableExists()`**; consolidated-seed resolution logs **`error_log`** when falling back to **`mysql/seed/seed_4.1.0.sql`**. |
| **Config permissions** | **`InstallWizardConfigWriter`** and **`includes/lupopedia-setup.php`** write config with **`chmod(0600)`** (not world-readable). |
| **`lupopedia_detect_livehelp_tables()`** | Same **`livehelp\_%`** pattern as the wizard (no `information_schema`). |
| **PRD 00** | **§1 RULE 93.NO_INFORMATION_SCHEMA** and **§3.6** clarification (portable SQL vs MySQL-only PHP `SHOW`). |

---

## Fresh install: is it safe?

**Yes — for the intended 4.0.x workflow:** wipe Lupopedia data, remove **`lupopedia-config.php`** (resolved via **`LupopediaConfigResolver`**; often project root or parent per install), open **`install.php`**, and run **New install** with valid DB credentials and an allowed table prefix (`^[a-z0-9_]+$`).

** Preconditions**

1. **Extensions:** **`mysqli`**, **`pdo_mysql`**, **`json`** (preflight in **`install.php`**).
2. **Writable project root** so the wizard can write **`lupopedia-config.php`**.
3. **Seed file:** Wizard expects **`install/seed_lupopedia_4_1_0.sql`** when present; if missing, it falls back to **`database/lupopedia/mysql/seed/seed_4.1.0.sql`** and logs a warning (fallback may hardcode `lupo_` in places — prefer merged seed for custom prefix).
4. **MySQL directory:** **`LUPO_MYSQL_DIR`** must exist (under **`database/lupopedia/mysql/`**).

** Drop / delete steps (operator)**

1. **Database:** Drop all Lupopedia tables (or drop the whole database and recreate empty) — same as any **4.0.x** “fresh install” (no Lupopedia→Lupopedia upgrade until **4.1.0** per doctrine).
2. **Config:** Remove **`lupopedia-config.php`** (and do not leave a stale half-written config).
3. **Browser:** **`install.php?step=welcome`** (or use **force reinstall** if your tree documents it).

**Residual risks (unchanged by this batch)**

- **Custom table prefix:** Rely on **`{{prefix}}`** in install SQL and merged seed; avoid depending on fallback **`seed_4.1.0.sql`** alone for production.
- **Git hooks / CI:** If **`git commit`** uses a hook that invokes missing **WSL/bash**, use **`git commit --no-verify`** or fix the hook (environment issue, not installer logic).

---

## Related docs

- **`docs/versions/4.0.96/status/INSTALLER_AUDIT_20260407.md`** — original audit checklist (conditional pass items addressed in code above where applicable).
- **`docs/prd/00_root_constitutional_system_requirements.md`** — **RULE 93.NO_INFORMATION_SCHEMA**.

---

*This output complies with Lupopedia Constitutional Root Rules.*
