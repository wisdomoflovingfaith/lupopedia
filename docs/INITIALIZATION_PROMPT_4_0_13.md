# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\INITIALIZATION_PROMPT_4_0_13.md"
  file_hash: "3842738229def234cc9be016ceceb4c52112d465facd7ef87a61cd2f123d1723"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Initialization Prompt for New Cursor Thread — Lupopedia 4.0.13"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "initialization_prompt_4_0_13md"]
  lupo_agent: "windsurf"

flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Initialization Prompt for New Cursor Thread — Lupopedia 4.0.13

**Purpose:** Paste the content below (from "---" to "END OF PROMPT") into a **new** Cursor thread to begin development on Lupopedia 4.0.13. This prompt does NOT perform any version bump or file changes; it only equips the next thread with doctrine and instructions.

---

## Paste from here into new Cursor thread

---

You are starting development on **Lupopedia version 4.0.13**. This is an initialization prompt only. Do not modify any files until you receive explicit instructions.

---

### 1. VERSIONING (4.0.12 → 4.0.13)

When instructed to bump the version to 4.0.13, you MUST update the version string in **all four** of these locations (see `docs/doctrine/VERSIONING_DOCTRINE.md` §8):

| Location | What to update |
|----------|----------------|
| **config/global_atoms.yaml** | `version`, `versions.lupopedia`, `GLOBAL_CURRENT_LUPOPEDIA_VERSION`, `file.last_modified_system_version`; set `last_updated` to current YYYYMMDDHHIISS. |
| **lupo-includes/version.php** | Docblock `@version` to 4.0.13; fallback literal `$current_version` (line ~37: change `'4.0.12'` to `'4.0.13'`); set `LUPOPEDIA_VERSION_DATE` to current YYYYMMDDHHIISS. |
| **install.php** | Fallback when `LUPOPEDIA_VERSION` is not defined (line ~40): change `'4.0.12'` to `'4.0.13'`. This is what the wizard shows when run without lupopedia-config.php (no atom loader). |
| **lupo-includes/functions/load_atoms.php** | Fallback in `get_lupopedia_version()` (line ~46): change `'4.0.12'` to `'4.0.13'`. Used when the atom loader is not set (e.g. wizard pre-config). |

The installer wizard displays the version in: page title, h1, pre-flight error heading, and welcome paragraph. All of these use the variable `$lupo_wizard_version`, which is set in **install.php** from `LUPOPEDIA_VERSION` (loaded via **version.php**) or from the fallback string in install.php. Therefore updating the four locations above ensures the wizard and app always show 4.0.13. Do not modify any other files for the version bump unless explicitly instructed.

---

### 2. CLEAN DEVELOPMENT CYCLE RESET

Treat every development cycle as a **clean, empty, fresh start**:

- **DROP ALL TABLES** in the database.
- **RELOAD** the Crafty Syntax 3.7.5 schema (e.g. from `database/migrations/old_crafty_syntax_3_7_5_start.sql` or equivalent baseline).
- **Restore** the original Crafty `config.php` (no lupopedia-config.php).
- **Run the Lupopedia installer** from scratch (install.php) so that the only path exercised is **Crafty Syntax 3.7.5 → Lupopedia 4.0.x**.
- **No live DB inference is ever allowed.** Schema and behavior come from TOONs, doctrine, and the canonical SQL files—never from inspecting the current database state.

---

### 3. DOCTRINE LOADING

Before taking any action, load and apply ALL of the following doctrine from the repository:

- **Installer doctrine** — Only valid path: Crafty Syntax 3.7.5 → Lupopedia 4.0.x. No Lupopedia→Lupopedia upgrade in 4.0.x.
- **Unified Registry doctrine** — 24-column canonical table; reserved IDs; no AUTO_INCREMENT for registry-backed tables.
- **Unified Unregistry doctrine** — Rolling free-list allocator; lifecycle rules with ANUBIS.
- **Identity doctrine** — Actors, auth_users, actor_source_type (user / lupo_auth_users); roles via 3-level model.
- **Permission doctrine** — 3-layer model: (1) channel roles (lupo_actor_channel_roles: captain, administrator, monitor), (2) department roles (lupo_department_roles), (3) system (department_id = 0). Resolution order: channel → department → system. Fallback: lupo_permissions owner on admin module.
- **Department doctrine** — department_id = 0 is system (reserved); department_id = 1 is general; no user-selectable system department.
- **PHP 5.3 doctrine** — Use `array()` only; no short array `[]`; no null coalescing `??`; no typed properties/return types in core paths.
- **Schema doctrine** — TOONs in `docs/toons/` are the **only** source of truth for table and column names. Never guess or invent schema.
- **Prefix doctrine** — Use `LUPO_TABLE_PREFIX` (or configured prefix); never hardcode `lupo_`.
- **Versioning doctrine** — Patch-only bumps (4.0.12 → 4.0.13); single canonical file `docs/doctrine/VERSIONING_DOCTRINE.md`; no duplicate versioning files.
- **Reserved ID doctrine** — Registry-backed tables do not use AUTO_INCREMENT; explicit IDs; INSERT only with explicit ID; if row exists → UPDATE, else INSERT.
- **No lupo_agent_registry** — Do not use or reintroduce lupo_agent_registry anywhere in production logic.
- **ANUBIS doctrine** — Orphan logging, redirect logic, revised/mirrored tables; ANUBIS + registry_open lifecycle rules as documented.
- **Database logic prohibition** — No FOREIGN KEYs, triggers, stored procedures, DEFAULT CURRENT_TIMESTAMP, or any DB-side logic; all logic in application code.
- **PDO_DB only** — All database access via the project’s PDO_DB wrapper; no raw PDO query/exec in application paths.
- **Migration doctrine** — See §4 below: any schema change requires BOTH a migration file AND an update to install_new_lupopedia.sql.

**Critical doctrine directories:**

- **docs/doctrine/database/** — Per-table doctrine (auth_users, actors, actor_channel_roles, departments, channels, sessions, dialog_*, crm_*, etc.). Authoritative for how Lupopedia tables are used and how they replace legacy tables.
- **docs/doctrine/migrations/** — Legacy → Lupopedia mapping and migration notes. **MIGRATION_MAPPING_REFERENCE.md** is the concise index. Individual files (e.g. livehelp_users_migration.md, operator_to_roles_migration.md) describe legacy Crafty tables and their replacement in Lupopedia.

These directories are the **authoritative guide** for migrating features from `legacy\craftysyntax` into Lupopedia. Use them to understand what each old table and behavior maps to; do not infer from the live database or from guesswork.

---

### 4. LEGACY CRAFTY SYNTAX & MIGRATION RULES

We will be working extensively on migrating the old Crafty Syntax code under **legacy\craftysyntax** into Lupopedia.

- **legacy\craftysyntax** is **READ-ONLY** and **REFERENCE-ONLY**. It exists solely to show which features and behaviors must be reimplemented in Lupopedia. You must develop a **full understanding** of the legacy code and how it maps to the new system by:
  - Reading the legacy PHP and SQL under `legacy\craftysyntax` to understand behavior and data flow.
  - Using **docs/doctrine/database/** and **docs/doctrine/migrations/** (and **MIGRATION_MAPPING_REFERENCE.md**) as the canonical mapping from old tables (livehelp_*) to new tables and the 3-level role system.
  - Never assuming a mapping; always confirming it in the doctrine and migration docs.

- **You must NEVER:**
  - Execute any SQL query that uses the old **livehelp_*** tables (except inside the canonical import script run by the wizard: `import_from_old_crafty_syntax.sql`).
  - Modify any files under **legacy\craftysyntax**.

- All new implementations must target **Lupopedia tables and doctrine**, using docs/doctrine/database/ and docs/doctrine/migrations/ as the mapping guide.

---

### 5. DATABASE STRUCTURE CHANGES — TWO-PLACE RULE

If during development **any database structure changes** are found to be necessary (new table, new column, index, or type change):

1. **Create a single one-time migration SQL file** in `database/migrations/` (or `database/migrations_legacy/` only if the change is non-canonical). This file updates the **existing** database so current development and testing match the canonical schema. The migration must be idempotent, use explicit ALTER/ADD, follow timestamp doctrine (BIGINT UTC YmdHis), and respect reserved-ID doctrine. See `docs/doctrine/MIGRATION_DOCTRINE.md`.

2. **Update `database/migrations/install_new_lupopedia.sql`** so that the canonical full schema matches the new structure. Every table and column required for a fresh install must be defined there. When schema changes, both the migration file and the install file must reflect the same change.

Cursor must **never** change only one of these. If the canonical schema is updated, a migration file must be provided for existing databases; if a migration is written, the install SQL must be updated to match. Do not infer schema from the live DB—use TOONs and doctrine.

---

### 6. WHAT YOU MUST DO

- Load all doctrine (and the directories above) before any action.
- Treat the **4.0.12** state as canonical.
- Begin **4.0.13** as a stabilization and migration-support patch unless instructed otherwise.
- Use **legacy\craftysyntax** only as reference for behavior and features to be reimplemented in Lupopedia; use **docs/doctrine/database/** and **docs/doctrine/migrations/** for the authoritative mapping.
- When instructed to bump the version, update **all four** version locations (global_atoms.yaml, version.php, install.php, load_atoms.php) to 4.0.13 as specified in §1.
- If a database structure change is required, create **one** migration file and update **install_new_lupopedia.sql** as described in §5.
- **WAIT for explicit instructions** before modifying any files.

---

### 7. WHAT YOU MUST NOT DO

- **Do not** infer schema from the live database.
- **Do not** reintroduce or use lupo_agent_registry.
- **Do not** modify install SQL, seed SQL, importer SQL, or migration SQL unless explicitly instructed to do so for a specific change.
- **Do not** change TOONs (TOONs are generated by `scripts/generate_toon_files.py`; Cursor only reads them).
- **Do not** use modern PHP syntax (no `[]`, `??`, typed properties, etc.) in core paths.
- **Do not** assume anything about the current DB state; assume a clean reset as in §2.
- **Do not** perform any automatic upgrades or Lupopedia→Lupopedia migrations.
- **Do not** issue any SQL queries against **livehelp_*** tables (except as part of the canonical import run by the wizard).
- **Do not** modify any files under **legacy\craftysyntax**.

---

### 8. SUMMARY

You are starting a new thread for Lupopedia **4.0.13**. The codebase is at **4.0.12**. You have been given version-bump instructions (four files), clean-reset instructions, full doctrine loading requirements, legacy/migration rules, and the two-place rule for any schema changes. Do not perform a version bump or change any files until explicitly instructed. Acknowledge this prompt and wait for directions.

---

END OF PROMPT

---

## End of paste

*This file (INITIALIZATION_PROMPT_4_0_13.md) is for reference only. The content to paste into the new Cursor thread is the block between "Paste from here into new Cursor thread" and "END OF PROMPT".*
