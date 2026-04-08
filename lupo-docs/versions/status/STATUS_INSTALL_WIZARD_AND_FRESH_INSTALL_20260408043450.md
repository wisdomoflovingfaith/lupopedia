---
lupopedia.headers:
  lupopedia.schema: documentation
  file_path_from_root: lupo-docs/versions/status/STATUS_INSTALL_WIZARD_AND_FRESH_INSTALL_20260408043450.md
  web_path: http://www.lupopedia.com/lupopedia/lupo-docs/versions/status/STATUS_INSTALL_WIZARD_AND_FRESH_INSTALL_20260408043450.md
  last_modified_utc: "20260408043450"
  channel_id: 42
  actor_id: 102
  actor_name: cursor
  delegation_chain: "cursor:root"
  artifact_type: status
  artifact_kind: version_handoff
  purpose: "Summary of installer/PRD 00 changes (shared-host safe SHOW, no information_schema); fresh install readiness"
  tags:
    - "installer"
    - "install_wizard"
    - "shared_hosting"
    - "4.0.x"
  when_updated: "20260408043450"
lupopedia.edges:
  outbound_edges:
    - to: lupo-docs/prd/00_root_constitutional_system_requirements.md
      type: references
      weight: 1.0
      reason: RULE 93.NO_INFORMATION_SCHEMA
    - to: install_wizard_classes.php
      type: implements
      weight: 1.0
    - to: install.php
      type: implements
      weight: 1.0
lupopedia.footer:
  last_verified: "20260408043450"
  verified_by:
    identity_type: actor
    actor_id: 102
---

# file: STATUS_INSTALL_WIZARD_AND_FRESH_INSTALL — delegation: cursor:root

# Status: Install wizard changes and fresh-install safety (2026-04-08)

**Temporal anchor:** `20260408043450` UTC (`python lupo-bin/tick.py`).

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
| **Config permissions** | **`InstallWizardConfigWriter`** and **`lupo-includes/lupopedia-setup.php`** write config with **`chmod(0600)`** (not world-readable). |
| **`lupopedia_detect_livehelp_tables()`** | Same **`livehelp\_%`** pattern as the wizard (no `information_schema`). |
| **PRD 00** | **§1 RULE 93.NO_INFORMATION_SCHEMA** and **§3.6** clarification (portable SQL vs MySQL-only PHP `SHOW`). |

---

## Fresh install: is it safe?

**Yes — for the intended 4.0.x workflow:** wipe Lupopedia data, remove **`lupopedia-config.php`** (resolved via **`LupopediaConfigResolver`**; often project root or parent per install), open **`install.php`**, and run **New install** with valid DB credentials and an allowed table prefix (`^[a-z0-9_]+$`).

** Preconditions**

1. **Extensions:** **`mysqli`**, **`pdo_mysql`**, **`json`** (preflight in **`install.php`**).
2. **Writable project root** so the wizard can write **`lupopedia-config.php`**.
3. **Seed file:** Wizard expects **`install/seed_lupopedia_4_1_0.sql`** when present; if missing, it falls back to **`lupo-database/lupopedia/mysql/seed/seed_4.1.0.sql`** and logs a warning (fallback may hardcode `lupo_` in places — prefer merged seed for custom prefix).
4. **MySQL directory:** **`LUPO_MYSQL_DIR`** must exist (under **`lupo-database/lupopedia/mysql/`**).

** Drop / delete steps (operator)**

1. **Database:** Drop all Lupopedia tables (or drop the whole database and recreate empty) — same as any **4.0.x** “fresh install” (no Lupopedia→Lupopedia upgrade until **4.1.0** per doctrine).
2. **Config:** Remove **`lupopedia-config.php`** (and do not leave a stale half-written config).
3. **Browser:** **`install.php?step=welcome`** (or use **force reinstall** if your tree documents it).

**Residual risks (unchanged by this batch)**

- **Custom table prefix:** Rely on **`{{prefix}}`** in install SQL and merged seed; avoid depending on fallback **`seed_4.1.0.sql`** alone for production.
- **Git hooks / CI:** If **`git commit`** uses a hook that invokes missing **WSL/bash**, use **`git commit --no-verify`** or fix the hook (environment issue, not installer logic).

---

## Related docs

- **`lupo-docs/versions/4.0.96/status/INSTALLER_AUDIT_20260407.md`** — original audit checklist (conditional pass items addressed in code above where applicable).
- **`lupo-docs/prd/00_root_constitutional_system_requirements.md`** — **RULE 93.NO_INFORMATION_SCHEMA**.

---

*This output complies with Lupopedia Constitutional Root Rules.*
