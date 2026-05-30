---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: prd
  when_updated: null
  file_path_from_root: "docs/versions/4.0.93/prd/01_installer_requirements.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.93/prd/01_installer_requirements.md"
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: prd
  artifact_kind: installer
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# Installer Requirements (4.0.93+)

## Purpose
This PRD defines the installer requirements for Lupopedia, ensuring compliance with the root constitutional system requirements and maximum compatibility with shared hosting environments.

---

## 1. Shared Hosting Compatibility
- Installer must run on shared hosting with no root access.
- No server-level dependencies or configuration changes.
- No requirement for composer, npm, or external package managers.
- No background daemons or cron jobs beyond standard PHP cron.

## 2. Subdirectory Installation
- Installer must support and default to subdirectory installation.
- Must not modify or assume control of the document root or parent directories.
- All generated paths must respect `LUPOPEDIA_PUBLIC_PATH`.

## 3. Database Setup
- Must not require database privileges beyond CREATE/INSERT/UPDATE/DELETE.
- Must not create foreign keys, triggers, functions, or procedures.
- Must not use AUTO_INCREMENT or UNSIGNED.
- All primary keys must be generated using `IdGenerator::generate()`.
- All timestamps must be BIGINT(14) UTC.
- Must use database-neutral SQL compatible with MySQL 8.0+ and PostgreSQL 15+.

### 3.1 Schema and seed files (4.0.93+)
- **DDL:** `database/lupopedia/mysql/install/install_new_lupopedia.sql` uses `{{prefix}}` placeholders; the installer replaces them with `LUPO_TABLE_PREFIX` at runtime.
- **Consolidated seed:** After DDL, the wizard runs `install/seed_lupopedia_4_1_0.sql` (single file). Source fragments remain in `database/lupopedia/mysql/seed/` for history and regeneration; rebuild with `scripts/build_consolidated_seed_4_1_0.py` when those sources change.
- **Crafty upgrade:** When upgrading from Crafty Syntax 3.7.5, `import_from_old_crafty_syntax.sql` runs after schema + seed (doctrine: no Lupopedia→Lupopedia upgrade in 4.0.x).
- **Additional post-seed SQL:** Optional seeds (e.g. Anubis queue tables) may run after the consolidated seed; see root `install.php` and CHANGELOG for current list.

### 3.2 Implementation paths (verified 2026-03-30)
- **Entry points:** The install wizard is **`/install.php`** with helpers in **`/install_wizard_classes.php`** at the **repository root** (not `install/install.php`). SQL runner class **`InstallWizardSqlRunner`** lives in `install_wizard_classes.php` (no separate file by that class name).
- **Crafty import file:** `database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql` (uses `{{prefix}}` for Lupopedia tables).
- **Detail:** See `/docs/versions/4.0.93/WHAT_TO_DO_NEXT.md` §14 for read-only verification notes and edge cases.

## 4. PHP Compatibility
- Installer must run on PHP 7.4 through 8.6 (latest). (Archive; align with current PRD 27.)
- Namespaces are allowed (PHP 5.3+).
- No frameworks (Laravel, Symfony, etc.), no Composer, no Docker.
- All required libraries must be bundled in the codebase (e.g., includes/), not installed via Composer or external package managers.
- Must not use strict types, typed properties, arrow functions, enums, or attributes.

## 5. Enforcement
- Installer must validate its own compliance with the root constitutional system requirements.
- Any violation is a constitutional error and must be corrected immediately.

---

## Reference
See: [00_root_constitutional_system_requirements.md](00_root_constitutional_system_requirements.md)
