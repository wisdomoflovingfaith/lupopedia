# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\INITIALIZATION_PROMPT_4_0_14.md"
  file_hash: "5de422d6652f13567837dab1633d84cae081fcfffd5155b6d9d418f607fc040a"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Initialization Prompt for New Cursor Thread — Lupopedia 4.0.14"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "initialization_prompt_4_0_14md"]
  lupo_agent: "windsurf"

flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Initialization Prompt for New Cursor Thread — Lupopedia 4.0.14

**Purpose:** Paste the content below (from "---" to "END OF PROMPT") into a **new** Cursor thread to begin development on Lupopedia 4.0.14. This prompt does NOT perform any version bump or file changes; it only equips the next thread with doctrine and instructions.

---

## Paste from here into new Cursor thread

---

You are starting development on **Lupopedia version 4.0.14**. This is an initialization prompt only. Do not modify any files until you receive explicit instructions.

---

### 1. VERSIONING (4.0.13 → 4.0.14)

When instructed to bump the version to 4.0.14, you MUST update the version string in **all** of these locations (see `docs/doctrine/VERSIONING_DOCTRINE.md` §8):

| Location | What to update |
|----------|----------------|
| **config/global_atoms.yaml** | `version`, `versions.lupopedia`, `GLOBAL_CURRENT_LUPOPEDIA_VERSION`, `file.last_modified_system_version`; set `last_updated` to current YYYYMMDDHHIISS. |
| **lupo-includes/version.php** | Docblock `@version` to 4.0.14; fallback literal `$current_version` (line ~37: change `'4.0.13'` to `'4.0.14'`); set `LUPOPEDIA_VERSION_DATE` to current YYYYMMDDHHIISS. |
| **install.php** | Fallback when `LUPOPEDIA_VERSION` is not defined (line ~40): change `'4.0.13'` to `'4.0.14'`. This is what the wizard shows when run without lupopedia-config.php (no atom loader). |
| **lupo-includes/functions/load_atoms.php** | Fallback in `get_lupopedia_version()` (line ~46): change `'4.0.13'` to `'4.0.14'`. Used when the atom loader is not set (e.g. wizard pre-config). |
| **install_wizard_classes.php** | Docblock version reference (line ~3): update from "4.0.6" or current value to **4.0.14** so the wizard classes docblock reflects the current patch. |

The installer wizard displays the version via **install.php** from `LUPOPEDIA_VERSION` (loaded via **version.php**) or from the fallback string in install.php. Updating the locations above ensures the wizard and app always show 4.0.14. Do not modify any other files for the version bump unless explicitly instructed.

---

### 2. CLEAN DEVELOPMENT CYCLE RESET

The new thread must treat every development cycle as a **clean, empty, fresh start**:

- **DROP ALL TABLES** in the database.
- **RELOAD** the Crafty Syntax 3.7.5 schema (e.g. from `database/migrations/old_crafty_syntax_3_7_5_start.sql` or equivalent baseline).
- **Restore** the original Crafty `config.php` (no lupopedia-config.php).
- **Run the Lupopedia installer** from scratch (install.php) so that the only path exercised is **Crafty Syntax 3.7.5 → Lupopedia 4.0.x**.
- **No live DB inference is ever allowed.** Schema and behavior come from TOONs, doctrine, and the canonical SQL files—never from inspecting the current database state.
- The new thread must treat this as a **CLEAN, EMPTY, FRESH** start. No assumptions about existing data or schema.

---

### 3. DOCTRINE LOADING (FULL SET)

Before taking any action, load and apply **ALL** of the following doctrine from the repository:

- **Installer doctrine** — Only valid path: Crafty Syntax 3.7.5 → Lupopedia 4.0.x. No Lupopedia→Lupopedia upgrade in 4.0.x. See docs/doctrine/INSTALLATION_PATH_DOCTRINE.md and CRAFTY_SYNTAX_MIGRATION_PROJECT_BRIEF.md.
- **Unified Registry doctrine** — 24-column canonical table (`docs/doctrine/REGISTRY_DOCTRINE.md`); reserved IDs; no AUTO_INCREMENT for registry-backed tables.
- **Unified Unregistry doctrine** — Rolling free-list allocator; lifecycle rules with ANUBIS. See REGISTRY_DOCTRINE and related allocator docs.
- **Identity doctrine** — Actors, auth_users, actor_source_type (user / lupo_auth_users); roles via 3-level model. See docs/doctrine/database/actors.md, auth_users.md, actor_channel_roles.md.
- **Permission doctrine** — 3-layer model: (1) channel roles (lupo_actor_channel_roles: captain, administrator, monitor), (2) department roles (lupo_department_roles), (3) system (department_id = 0). Resolution order: channel → department → system.
- **Department doctrine** — department_id = 0 is system (reserved); department_id = 1 is general; no user-selectable system department. See docs/doctrine/database/departments.md.
- **PHP 5.3 doctrine** — Use `array()` only; no short array `[]`; no null coalescing `??`; no typed properties/return types in core paths. See docs/doctrine/PHP_COMPATIBILITY_AND_MINIMAL_HOSTING_DOCTRINE.md.
- **Schema doctrine (TOONs)** — TOONs in `docs/toons/` are the **only** source of truth for table and column names. Never guess or invent schema. See .cursor/rules/toon-source-of-truth.mdc.
- **Prefix doctrine** — Use `LUPO_TABLE_PREFIX` (or configured prefix); never hardcode `lupo_`.
- **Versioning doctrine** — Patch-only bumps (4.0.13 → 4.0.14); single canonical file `docs/doctrine/VERSIONING_DOCTRINE.md`; no duplicate versioning files.
- **Reserved ID doctrine** — Registry-backed tables do not use AUTO_INCREMENT; explicit IDs; if row exists → UPDATE, else INSERT with explicit ID. No ON DUPLICATE KEY UPDATE for registry-backed tables. See .cursor/rules/reserved-id-doctrine.mdc.
- **No lupo_agent_registry** — Do not use or reintroduce lupo_agent_registry anywhere in production logic.
- **ANUBIS doctrine** — Orphan logging, redirect logic; ANUBIS + registry_open lifecycle rules as documented.
- **ANUBIS + registry_open lifecycle rules** — As documented in doctrine; do not bypass.
- **Database logic prohibition** — No FOREIGN KEYs, triggers, stored procedures, DEFAULT CURRENT_TIMESTAMP, or any DB-side logic; all logic in application code. See .cursor/rules/database-logic-prohibition-doctrine.mdc.
- **PDO_DB only** — All database access via the project's PDO_DB wrapper; no raw PDO query/exec in application paths. See .cursor/rules/pdo-db-database-access-doctrine.mdc.
- **Migration doctrine** — Any schema change requires BOTH a migration file AND an update to install_new_lupopedia.sql. See docs/doctrine/MIGRATION_DOCTRINE.md and .cursor/rules/migration-doctrine.mdc.
- **FLIP doctrine** — File-Level Inference Protocol. FLIP Headers (alias: Wolfie, CROP, FLIPPING) at top of files; infer file identity, lineage, channel, version only from header; no guessing. See docs/doctrine/FLIP/FLIP_DOCTRINE.md and .cursor/rules/flip-doctrine.mdc.
- **FLP doctrine** — Federated Likeness Protocol; governance layer, councils as channels, emotional geometry, etc. See docs/doctrine/FLIP/FLP_OVERVIEW.md and FLP_*.md in docs/doctrine/FLIP/.
- **LILITH heterodox review doctrine** — See docs/doctrine/FLIP/FLIPPING_FILE_LEXA_LILITH.md (LEXA/LILITH roles, optional dialog block, path validation).
- **ARA Grok architectural doctrine** — Architectural and normalization guidance; no ARA-suggested classes (e.g. FlipHeaderParser, AtomResolver) unless explicitly implemented.

**Canonical mapping directories (mandatory):**

- **docs/doctrine/database/** — Per-table doctrine (auth_users, actors, actor_channel_roles, departments, channels, sessions, dialog_*, crm_*, federation_nodes, etc.). Authoritative for how Lupopedia tables are used and how they replace legacy Crafty tables.
- **docs/doctrine/migrations/** — Legacy → Lupopedia mapping and migration pathways. **MIGRATION_MAPPING_REFERENCE.md** is the concise index. Individual files (e.g. livehelp_users_migration.md, livehelp_operator_departments_migration.md) describe old Crafty Syntax tables (livehelp_*) and their replacement in Lupopedia.

These directories contain the **mapping between**: (1) old Crafty Syntax tables (livehelp_*), (2) new Lupopedia tables, (3) migration pathways. Cursor must NOT guess doctrine—it must gather the important information from these doctrine files and the repository. Use them to understand every mapping; do not infer from the live database or from guesswork.

---

### 4. CARRY FORWARD ALL WORK COMPLETED IN 4.0.13 (AND 4.0.14 STARTED)

The repository state is **canonical** for the following. The new thread must treat this as already done and must not revert or contradict it.

**FLIP Header system (4.0.13):**

- FLIP Headers (alias: Wolfie, CROP, FLIPPING) are the canonical name for the header block. FLIP = File-Level Inference Protocol.
- **docs/doctrine/FLIP/FLIP_DOCTRINE.md** — Canonical FLIP doctrine; inference only from header.
- **docs/doctrine/FLIP/** — Contains FLIP_DOCTRINE.md, NOTE_HEADER_VERSION_AND_MERGE.md, and all FLP_*.md (FLP_OVERVIEW, FLP_EMOTIONAL_GEOMETRY, FLP_COUNCILS_AS_CHANNELS, FLP_HETERODOX_REVIEWERS, FLP_EMOTIONAL_AGGREGATION, FLP_ESCROW_AND_FUND_LAYER, FLP_LUPOPEDIA_COUNCIL_SEAT, FLP_DOCTRINE_BOUNDARIES). Directory docs/doctrine/flp/ was renamed to docs/doctrine/FLIP/.
- All doctrine files in docs/doctrine/FLIP/ have a FLIP Header at the top with file_path_from_root, file.last_modified_system_version, file.last_modified_utc; channel_id unresolved (requires lupo_contents lookup by application).
- **.cursor/rules/flip-doctrine.mdc** — Cursor rule: FLIP Headers canonical; infer only from header; set file.last_modified_system_version when editing.

**FLIP schema and installer (4.0.13):**

- **lupo_contents** in install_new_lupopedia.sql includes: file_path_from_root (varchar(500)), file_last_modified_system_version (varchar(20)), file_last_modified_utc (bigint), dialog_notes (text). Index lupo_contents_idx_file_path_from_root on lupo_contents(file_path_from_root).
- One-time migrations: 20260217_add_flip_header_fields.sql, 20260217_add_missing_flip_fields.sql, 20260217_add_contents_file_path_from_root_index.sql. Installer SQL is updated to include all FLIP fields and the index; seed SQL is updated for channel 42 and dialog.

**Loader and path validation (4.0.13):**

- **scripts/import_os.py** — Recognizes FLIP Headers; extracts file_path_from_root; stores in lupo_contents.file_path_from_root; path validation (inside root, no '..'); parameterized SQL only; optional dialog block → dialog_notes. Does not infer channel_id; application resolves channel via lupo_contents lookup later.
- Only sanitized paths may be stored as file_path_from_root (validate_and_sanitize_path_from_root or equivalent).

**Path → content → channel → actors (4.0.13):**

- Lookup chain: (1) file_path_from_root → content_id via lupo_contents; (2) content_id → channel_id via lupo_edges (edge_type = 'HAS_CONTENT'); (3) channel_id → actors via lupo_actor_channels + lupo_actors.
- **lupo-includes/classes/ContentChannelActorResolver.php** — getActorsForFilePath($filePath): validates path, resolves content_id → channel_id → actors; returns actor_id, actor_name, type, role, status, joined_at. Uses PDO_DB and table prefix only.

**generate_flip_header.py (4.0.13):**

- **tools/generate_flip_header.py** — Reconstructs FLIP header from DB; SELECT includes dialog_notes; when present, outputs optional dialog block. Parameterized SQL only.

**Channel 42 seed (4.0.13 + 4.0.14):**

- **All kernel actors active on channel 42:** actor_ids 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 22, 23, **24**, 209, 1212 (25 kernel agents). LEXA = actor_id 24 (boundary keeper), added in 4.0.14.
- **lupo_actor_channels:** 25 rows (actor_channel_id 1000–1024) for channel 42; lupo_actor_channel_roles: 25 rows (actor_channel_role_id 2000–2024), role_key 'admin'.
- **One dialog message per actor** in dialog_thread_id = 1: message_ids 1–2 from actor 0 (system); 3–27 one per kernel agent (from_actor_id = that agent), message_type 'system'. LEXA (actor_id 24): message_text "Boundary enforcement active. LEXA online." Others: "hello from &lt;agent_name&gt;".
- **lupo_dialog_channels:** channel_id 42, message_count = 27.
- Seed uses explicit IDs and ON DUPLICATE KEY UPDATE where appropriate (idempotent). Reserved-ID tables (e.g. lupo_registry for LEXA) use explicit INSERT only.

**FLIPPING_FILE_LEXA_LILITH.md (4.0.13 + 4.0.14):**

- Single authoritative doc for LEXA and LILITH. Part 2.5: database tables and navigation (lupo_contents, lupo_edges, lupo_channels, lupo_dialog_*, etc.). Part 2.11: Channel 42 seed (25 kernel agents, 27 messages, LEXA actor_id 24). Part 2.12: Optional dialog block in FLIP Headers (informational only; app parses safely). Path validation pseudocode (validate_path_inside_root, validate_and_sanitize_path_from_root). Quick Reference updated for 25 agents, 27 messages, message_count 27.
- FLIP header in that file: file.last_modified_system_version 4.0.14; dialog speaker LEXA; activation message.

**Other 4.0.13 updates:**

- **docs/channels/agents/WOLFIE_HEADER_SPECIFICATION.md** — FLIP subsection; dialog block optional and non-authoritative; link to FLIPPING_FILE_LEXA_LILITH.md Part 2.12.
- **README.md** — FLIP bullet, FLIP Header Update Requirements, doctrine list includes FLIP_DOCTRINE.md.
- **database/migrations/seed_lupopedia.sql** — @lupo_version 4.0.13, @lupo_version_code 40013; channel 42 with 25 kernel agents including LEXA (actor_id 24); 27 dialog messages; lupo_registry row for LEXA (registry_id 9001024, is_kernel = 1); lupo_actors row for LEXA (actor_id 24, slug lexa, name LEXA).

All 4.0.13 and 4.0.14 work above is committed and pushed. The new thread must treat this state as canonical and build 4.0.14 from here.

---

### 5. WHAT THE NEW THREAD MUST DO

- **Load all doctrine** (full set in §3) before any action.
- **Treat the 4.0.13 state (and 4.0.14 state already in repo) as canonical** — path → content → channel → actors, FLIP fields, ContentChannelActorResolver, seed with 25 kernel agents on channel 42 and 27 messages, LEXA at actor_id 24.
- **Begin 4.0.14** as a stabilization and feature-continuation patch. Only patch bumps (4.0.13 → 4.0.14); no major/minor until auto-installer release cycle.
- **Make NO schema changes** unless explicitly ordered.
- **Make NO installer/seed/migration SQL changes** unless explicitly ordered.
- **Make NO TOON changes** unless explicitly ordered (TOONs are read-only; generated by scripts/generate_toon_files.py from live DB per governance).
- **Use legacy\craftysyntax only as reference** — read for behavior and mapping; never modify files under legacy\craftysyntax.
- **Wait for explicit instructions** before modifying any files.

---

### 6. WHAT THE NEW THREAD MUST NOT DO

- **Schema inference from the live DB** — Never infer schema from the current database. Schema comes from TOONs and install_new_lupopedia.sql only.
- **Reintroducing lupo_agent_registry** — Do not use or add lupo_agent_registry in production logic.
- **Modifying install SQL, seed SQL, importer SQL, or migration SQL** — Unless explicitly instructed.
- **Changing TOONs** — Do not create, edit, or delete TOON files.
- **Using modern PHP syntax** — No `[]`, `??`, typed properties, return types, or PHP 7+ features in core paths (PHP 5.3 compatibility).
- **Making assumptions about the DB state** — Treat every run as clean install + seed unless told otherwise.
- **Performing any automatic upgrades or migrations** — No Lupopedia→Lupopedia upgrade logic in 4.0.x.
- **Issuing any SQL queries against livehelp_* tables** — Except inside the canonical import script run by the wizard (import_from_old_crafty_syntax.sql).
- **Modifying any files under legacy\craftysyntax** — Reference only.

---

### 7. DIRECTIVE

You are starting a new thread for Lupopedia **4.0.14**. The codebase is at **4.0.13** (with 4.0.14 work already begun: LEXA in seed, FLIPPING doc updated). You have been given version-bump instructions (config/global_atoms.yaml, version.php, install.php, load_atoms.php, install_wizard_classes.php), clean-reset instructions, full doctrine loading requirements, canonical mapping directories (docs/doctrine/database/, docs/doctrine/migrations/), legacy/livehelp_* prohibitions, and the full list of 4.0.13 and 4.0.14 work to carry forward.

**Do not perform a version bump or change any files until explicitly instructed.**

**Acknowledge this prompt and wait for directions.**

---

END OF PROMPT
