---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/doctrine/MIGRATION_DOCTRINE.md
file.last_modified_system_version: "4.0.15"
file.last_modified_utc: "20260217235900"
# channel_id unresolved — requires lupo_contents lookup by application.
---
# Migration Doctrine (MANDATORY)

**Status:** Permanent. Applies to all code, all SQL, all installer logic, and all future development.  
**Audience:** All AI agents (including Cursor), contributors, and system stewards.  
**Single source of truth for schema:** TOON files in `docs/toons/`.

---

## 1. Two-place rule for database structure changes

ALL database structure changes MUST be done in TWO PLACES:

1. **install_new_lupopedia.sql** — The canonical full schema. Every table and column that is required for a fresh install must be defined here. When schema changes, this file is updated to match the TOONs so that future installs get the new structure.
2. **A new migration SQL file** — A one-time patch for development. Stored in `database/migrations/` (or `database/migrations_legacy/` if non-canonical). Used to apply the same change to an existing (live) database so that current development and testing match the canonical schema.

Cursor must NEVER change only one of these. If the canonical schema is updated, a migration file must be provided for existing databases; if a migration is written, the canonical install file must be updated to match.

---

## 2. Prohibitions — Cursor MUST NEVER

- **Run "scoop mysql"** or any command-line SQL tool.
- **Modify the database directly** (no live DB writes from Cursor).
- **Infer schema from the live database.** The only source of truth for schema is TOON files in `/docs/toons/`.

---

## 3. Requirements — Cursor MUST ALWAYS

- **Read the schema from TOON files** in `/docs/toons/` (source of truth). Do not infer from PHP, from existing SQL, or from the live database.
- **Update install_new_lupopedia.sql** to match the TOONs when a schema change is applied.
- **Generate a migration SQL file** that applies the same change to the live DB when a schema change is required for development.

---

## 4. Migration SQL file rules

Migration SQL files MUST:

- Be **idempotent** (safe to run more than once; use `ADD COLUMN IF NOT EXISTS` or equivalent where supported, or document "run once").
- Use **explicit ALTER TABLE** statements (or explicit INSERT with explicit IDs); no reliance on implicit behavior.
- Use **explicit IDs** (no AUTO_INCREMENT for registry-backed tables; reserved-ID doctrine applies).
- Follow **timestamp doctrine**: BIGINT UTC YYYYMMDDHHIISS only; no CURRENT_TIMESTAMP, no DATETIME, no epoch.
- **NEVER drop or recreate tables** unless explicitly instructed. Prefer ADD/ALTER over DROP/CREATE.

---

## 5. Wizard and installer rules

The wizard MUST NOT:

- **Run any migration SQL.** The wizard runs only the canonical set: install_new_lupopedia.sql, seed_lupopedia.sql, import_from_old_crafty_syntax.sql, drop_old_crafty_syntax_tables.sql (and optionally future_features_lupopedia.sql is not run by the wizard). No one-off migration files are executed by the wizard.
- **Attempt to upgrade Lupopedia → Lupopedia.** The only supported path is Crafty Syntax 3.7.5 → Lupopedia 4.0.x.
- **Modify schema except during Crafty Syntax 3.7.5 → Lupopedia install.** Schema is created once via install_new_lupopedia.sql and seed_lupopedia.sql; the wizard does not apply migration patches.

---

## 6. Summary

| Rule | Requirement |
|------|--------------|
| Schema source | TOON files in `/docs/toons/` only. |
| Canonical schema | `database/migrations/install_new_lupopedia.sql` must match TOONs. |
| Live DB changes | One-time migration SQL file; never direct DB modification by Cursor. |
| No CLI SQL | No scoop mysql or command-line SQL tools. |
| No live inference | Never infer schema from the live database. |
| Migration format | Idempotent, explicit ALTER, explicit IDs, timestamp doctrine. |
| Wizard | No migration SQL; no Lupopedia→Lupopedia upgrade; schema only via install/seed. |

This doctrine is permanent and applies to all future development.
