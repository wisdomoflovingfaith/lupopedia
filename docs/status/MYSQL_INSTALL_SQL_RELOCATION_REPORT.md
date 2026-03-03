# MySQL Install SQL Relocation Report

---
flare.headers:
  flare.version: "1.0"
  file_path_from_root: "docs/status/MYSQL_INSTALL_SQL_RELOCATION_REPORT.md"
  system_version: "4.0.55"
  channel_id: 42
  actor_id: 1002
  last_updated_utc: "20260303"
  artifact_type: "report"
  purpose: "Record of installer SQL relocation to lupo-database/lupopedia/mysql/"
  tags: ["mysql", "installer", "windsurf", "1002"]
---

## 1) Research table — SQL loads by install.php (before edits)

| Caller file | Function / context | Exact path or expression | Resolved directory | Execution order | Type |
|-------------|-------------------|--------------------------|--------------------|-----------------|-----|
| install.php | step=credentials (upgrade bootstrap) | `$migrationsDir . DIRECTORY_SEPARATOR . 'install_new_lupopedia.sql'` | database/migrations | 1 | schema |
| install.php | step=credentials (upgrade bootstrap) | `$migrationsDir . DIRECTORY_SEPARATOR . 'seed_registry_comprehensive_4.0.45.sql'` | database/migrations | 2 | seed |
| install.php | step=credentials (upgrade bootstrap) | `$migrationsDir . DIRECTORY_SEPARATOR . 'seed_registry_additional_csv_entities_4.0.45.sql'` | database/migrations | 3 | seed |
| install.php | step=credentials (upgrade bootstrap) | `$migrationsDir . DIRECTORY_SEPARATOR . 'seed_registry_open_4.0.45.sql'` | database/migrations | 4 | seed |
| install.php | step=credentials (upgrade bootstrap) | `$migrationsDir . DIRECTORY_SEPARATOR . 'seed_actors_agents_4.0.45.sql'` | database/migrations | 5 | seed |
| install.php | step=run (new install) | same 5 files as above | database/migrations | 1–5 | schema + seed |
| install.php | step=run (upgrade) | `$importSql` = `$migrationsDir . DIRECTORY_SEPARATOR . 'import_from_old_crafty_syntax.sql'` | database/migrations | after bootstrap | import |
| install.php | step=run (upgrade, optional) | `$migrationsDir . DIRECTORY_SEPARATOR . 'drop_old_crafty_syntax_tables.sql'` | database/migrations | after import | import |
| install.php | step=run (both) | `$migrationsDir . DIRECTORY_SEPARATOR . 'anubis_queue_tables_4.0.53.sql'` | database/migrations | post seed/import | migrations |
| install.php | step=run (both) | `$migrationsDir . DIRECTORY_SEPARATOR . '20260301_anubis_database_primacy_updates.sql'` | database/migrations | post anubis_queue | migrations |
| install.php | step=run (both) | `$migrationsDir . DIRECTORY_SEPARATOR . 'seed_default_sessions.sql'` | database/migrations | last SQL | seed |

Where `$migrationsDir = LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'migrations'`.

**Note:** `old_crafty_syntax_3_7_5_start.sql` is **not** run by install.php; it is the Crafty 3.7.5 baseline for dev/revert and is referenced in docs. Relocated to `import/` for consistency.

---

## 2) SQL inventory and classification

### Installer-critical (moved under lupo-database/lupopedia/mysql/)

| Current path | Classification | New path |
|-------------|----------------|----------|
| database/migrations/install_new_lupopedia.sql | schema | mysql/install/install_new_lupopedia.sql |
| database/migrations/seed_registry_comprehensive_4.0.45.sql | seed | mysql/seed/seed_registry_comprehensive_4.0.45.sql |
| database/migrations/seed_registry_additional_csv_entities_4.0.45.sql | seed | mysql/seed/seed_registry_additional_csv_entities_4.0.45.sql |
| database/migrations/seed_registry_open_4.0.45.sql | seed | mysql/seed/seed_registry_open_4.0.45.sql |
| database/migrations/seed_actors_agents_4.0.45.sql | seed | mysql/seed/seed_actors_agents_4.0.45.sql |
| database/migrations/seed_default_sessions.sql | seed | mysql/seed/seed_default_sessions.sql |
| database/migrations/import_from_old_crafty_syntax.sql | import | mysql/import/import_from_old_crafty_syntax.sql |
| database/migrations/drop_old_crafty_syntax_tables.sql | import | mysql/import/drop_old_crafty_syntax_tables.sql |
| database/migrations/old_crafty_syntax_3_7_5_start.sql | import (baseline) | mysql/import/old_crafty_syntax_3_7_5_start.sql |
| database/migrations/anubis_queue_tables_4.0.53.sql | migrations | mysql/migrations/anubis_queue_tables_4.0.53.sql |
| database/migrations/20260301_anubis_database_primacy_updates.sql | migrations | mysql/migrations/20260301_anubis_database_primacy_updates.sql |

### Non-critical (remain in database/migrations or elsewhere)

All other `.sql` files in `database/migrations/` (e.g. table_consolidation_phase1–3, dev_*, 4.0.29_*, etc.) are **not** loaded by install.php; they are one-off dev migrations, run manually or by other scripts. Left in place. Same for `database/install/*.sql`, `database/schema/*.sql`, `lupo-docs/specs/sql/*.sql`, and root-level/test SQL files.

---

## 3) Move plan (old → new)

- **install/**  
  - `database/migrations/install_new_lupopedia.sql` → `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`
- **seed/**  
  - `database/migrations/seed_registry_comprehensive_4.0.45.sql` → `lupo-database/lupopedia/mysql/seed/seed_registry_comprehensive_4.0.45.sql`  
  - `database/migrations/seed_registry_additional_csv_entities_4.0.45.sql` → `lupo-database/lupopedia/mysql/seed/seed_registry_additional_csv_entities_4.0.45.sql`  
  - `database/migrations/seed_registry_open_4.0.45.sql` → `lupo-database/lupopedia/mysql/seed/seed_registry_open_4.0.45.sql`  
  - `database/migrations/seed_actors_agents_4.0.45.sql` → `lupo-database/lupopedia/mysql/seed/seed_actors_agents_4.0.45.sql`  
  - `database/migrations/seed_default_sessions.sql` → `lupo-database/lupopedia/mysql/seed/seed_default_sessions.sql`
- **import/**  
  - `database/migrations/import_from_old_crafty_syntax.sql` → `lupo-database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql`  
  - `database/migrations/drop_old_crafty_syntax_tables.sql` → `lupo-database/lupopedia/mysql/import/drop_old_crafty_syntax_tables.sql`  
  - `database/migrations/old_crafty_syntax_3_7_5_start.sql` → `lupo-database/lupopedia/mysql/import/old_crafty_syntax_3_7_5_start.sql`
- **migrations/**  
  - `database/migrations/anubis_queue_tables_4.0.53.sql` → `lupo-database/lupopedia/mysql/migrations/anubis_queue_tables_4.0.53.sql`  
  - `database/migrations/20260301_anubis_database_primacy_updates.sql` → `lupo-database/lupopedia/mysql/migrations/20260301_anubis_database_primacy_updates.sql`

---

## 4) Code and docs updated

| File | What changed |
|------|----------------|
| install.php | Defined `LUPO_MYSQL_DIR` (lupo-database/lupopedia/mysql/). Replaced `$migrationsDir` with `$mysqlDir = LUPO_MYSQL_DIR` and all runSqlFile paths to use install/, seed/, import/, migrations/ under it. Updated @flip header paths and UI bullet list. |
| install_wizard_classes.php | No change (still accepts full path to .sql). |
| lupo-database/lupopedia/mysql/manifest/install_manifest.txt | Created: install/install_new_lupopedia.sql |
| lupo-database/lupopedia/mysql/manifest/seed_manifest.txt | Created: 5 seed paths |
| lupo-database/lupopedia/mysql/manifest/migrations_manifest.txt | Created: 2 migration paths |
| AGENTS.md | Three SQL Entrypoints and Schema Source of Truth now point to lupo-database/lupopedia/mysql/...; dev workflow references mysql/import/old_crafty_syntax_3_7_5_start.sql. |
| docs/status/DATABASE_PATH_NORMALIZATION_REPORT.md | Note added: installer SQL lives under lupo-database/lupopedia/mysql/ and MYSQL_INSTALL_SQL_RELOCATION_REPORT.md. |

---

## 5) Manifest contents

- **install_manifest.txt:** `install/install_new_lupopedia.sql`
- **seed_manifest.txt:**  
  `seed/seed_registry_comprehensive_4.0.45.sql`  
  `seed/seed_registry_additional_csv_entities_4.0.45.sql`  
  `seed/seed_registry_open_4.0.45.sql`  
  `seed/seed_actors_agents_4.0.45.sql`  
  `seed/seed_default_sessions.sql`
- **migrations_manifest.txt:**  
  `migrations/anubis_queue_tables_4.0.53.sql`  
  `migrations/20260301_anubis_database_primacy_updates.sql`

---

## 6) Verification

- Grep for `database/migrations/install_new_lupopedia|seed_registry|import_from_old_crafty|drop_old_crafty|anubis_queue_tables|20260301_anubis|seed_default_sessions` in install.php: all now point under `lupo-database/lupopedia/mysql/`.
- Listed `lupo-database/lupopedia/mysql/{install,seed,import,migrations}/` and confirmed the 11 SQL files exist.
- No schema or SQL content changed; only paths and layout.

---

## Timestamp and actor

- **Date:** 2026-03-03  
- **Actor ID:** 1002 (Windsurf)  
- **Channel:** 42  
