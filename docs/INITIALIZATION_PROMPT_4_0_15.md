# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\INITIALIZATION_PROMPT_4_0_15.md"
  file_hash: "fbdcb9bd182645c7ebb12053c7f18ea76c0e7ab7966ed6e511af39c6b7c1346f"
  file_path_from_root: "docs\INITIALIZATION_PROMPT_4_0_15.md"
  file_hash: "bc5586b616370df61e45876e97b8a3cff223bb4f33842007c7440a304015ae3b"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Initialization Prompt for New Cursor Thread — Lupopedia 4.0.15"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "initialization_prompt_4_0_15md"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Initialization Prompt for New Cursor Thread — Lupopedia 4.0.15

**Purpose:** Paste the content below (from "---" to "END OF PROMPT") into a **new** Cursor thread to begin development on Lupopedia 4.0.15. This prompt does NOT perform any version bump or file changes; it only equips the next thread with doctrine and instructions.

---

## Paste from here into new Cursor thread

---

You are starting development on **Lupopedia version 4.0.15**. This is an initialization prompt only. Do not modify any files until you receive explicit instructions.

---

### 1. VERSIONING (4.0.14 → 4.0.15)

When instructed to bump the version to 4.0.15, you MUST update the version string in **all** of these locations (see `docs/doctrine/VERSIONING_DOCTRINE.md` §8):

| Location | What to update |
|----------|----------------|
| **config/global_atoms.yaml** | `version`, `versions.lupopedia`, `GLOBAL_CURRENT_LUPOPEDIA_VERSION`, `file.last_modified_system_version`; set `last_updated` to current YYYYMMDDHHIISS. |
| **lupo-includes/version.php** | Docblock `@version` to 4.0.15; fallback literal `$current_version` (line ~40: change `'4.0.14'` to `'4.0.15'`); set `LUPOPEDIA_VERSION_DATE` to current YYYYMMDDHHIISS. |
| **install.php** | Fallback when `LUPOPEDIA_VERSION` is not defined (line ~40): change `'4.0.14'` to `'4.0.15'`. |
| **lupo-includes/functions/load_atoms.php** | Fallback in `get_lupopedia_version()`: change `'4.0.14'` to `'4.0.15'`. |
| **install_wizard_classes.php** | Docblock version reference (line ~3): update to **4.0.15**. |
| **database/migrations/seed_lupopedia.sql** | `@lupo_version` to `'4.0.15'`, `@lupo_version_code` to 40015. |

The installer wizard displays the version via **install.php** from `LUPOPEDIA_VERSION`. Updating the locations above ensures the wizard and app always show 4.0.15. Do not modify any other files for the version bump unless explicitly instructed.

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

- **Installer doctrine** — Only valid path: Crafty Syntax 3.7.5 → Lupopedia 4.0.x. No Lupopedia→Lupopedia upgrade in 4.0.x. See docs/doctrine/INSTALLATION_PATH_DOCTRINE.md.
- **Unified Registry doctrine** — Reserved IDs; no AUTO_INCREMENT for registry-backed tables. See docs/doctrine/REGISTRY_DOCTRINE.md.
- **Identity doctrine** — Actors, auth_users, actor_source_type; roles via 3-level model.
- **Permission doctrine** — 3-layer model: channel roles, department roles, system.
- **Department doctrine** — department_id = 0 is system (reserved); department_id = 1 is general.
- **PHP 5.3 doctrine** — Use `array()` only; no short array `[]`; no null coalescing `??`; no typed properties/return types in core paths. See .cursor/rules/php-5-3-compatibility.mdc.
- **Schema doctrine (TOONs)** — TOONs in `docs/toons/` are the **only** source of truth. Never guess or invent schema. See .cursor/rules/toon-source-of-truth.mdc.
- **Prefix doctrine** — Use `LUPO_TABLE_PREFIX`; never hardcode `lupo_`.
- **Versioning doctrine** — Patch-only bumps (4.0.14 → 4.0.15); single canonical file `docs/doctrine/VERSIONING_DOCTRINE.md`.
- **Reserved ID doctrine** — Registry-backed tables: explicit IDs; if row exists → UPDATE, else INSERT. See .cursor/rules/reserved-id-doctrine.mdc.
- **No lupo_agent_registry** — Do not use or reintroduce.
- **Database logic prohibition** — No FOREIGN KEYs, triggers, stored procedures, DEFAULT CURRENT_TIMESTAMP. All logic in application code. See .cursor/rules/database-logic-prohibition-doctrine.mdc.
- **PDO_DB only** — All database access via the project's PDO_DB wrapper. See .cursor/rules/pdo-db-database-access-doctrine.mdc.
- **Migration doctrine** — Schema changes require BOTH a migration file AND an update to install_new_lupopedia.sql. See .cursor/rules/migration-doctrine.mdc.
- **FLIP doctrine** — File-Level Inference Protocol. FLIP Headers at top of files; infer only from header; no guessing. See docs/doctrine/FLIP/FLIP_DOCTRINE.md and .cursor/rules/flip-doctrine.mdc.
- **FLP doctrine** — Federated Likeness Protocol; governance layer, councils as channels, emotional geometry. See docs/doctrine/FLIP/FLP_OVERVIEW.md.
- **LILITH heterodox review doctrine** — See docs/doctrine/FLIP/FLIPPING_FILE_LEXA_LILITH.md.
- **ARA Grok architectural doctrine** — Architectural guidance; no ARA-suggested classes unless explicitly implemented.
- **LEXA boundary doctrine** — Security, path validation, parameterized SQL only; boundary enforcement. See FLIPPING_FILE_LEXA_LILITH.md Parts 3, 6.2.
- **ANUBIS doctrine** — Custodial intelligence for dialogs, lineage, orphans, redirects. See docs/doctrine/ANUBIS/ANUBIS_OVERVIEW.md, ANUBIS_ORPHAN_RULES.md, ANUBIS_PROGRAM_SPEC.md.

**Canonical mapping directories:**

- **docs/doctrine/database/** — Per-table doctrine.
- **docs/doctrine/migrations/** — Legacy → Lupopedia mapping and migration pathways.

---

### 4. CARRY FORWARD ALL WORK COMPLETED IN 4.0.14

The repository state is **canonical** for the following. The new thread must treat this as already done and must not revert or contradict it.

**FLIP Header system (4.0.13 + 4.0.14):**

- FLIP Headers (alias: Wolfie, CROP, FLIPPING) are the canonical name. FLIP = File-Level Inference Protocol.
- **docs/doctrine/FLIP/FLIP_DOCTRINE.md** — Canonical FLIP doctrine.
- **docs/doctrine/FLIP/FLIPPING_FILE_LEXA_LILITH.md** — LEXA/LILITH roles, Part 1.4 Universal Agent Flipping, Parts 6.1–6.3 API spec/security/future auth.
- Optional fields: mood_rgb (6-char hex string, e.g., 'FF0000' for red); tags (array); atoms (key-value map). Stored in lupo_contents.metadata_json as JSON object (e.g., {"tags": ["doctrine", "security"], "atoms": {"GLOBAL_KEY": "value"}}); mood_rgb in lupo_dialog_messages.mood_rgb for dialogs or metadata_json for headers.

**FLIP schema and loader (4.0.13 + 4.0.14):**

- **lupo_contents** FLIP columns: file_path_from_root, file_last_modified_system_version, file_last_modified_utc, dialog_notes, metadata_json (for optionals).
- **scripts/import_os.py** — FLIP header extraction; path validation; optional dialog block → dialog_notes; optional fields (mood_rgb, tags, atoms) → lupo_contents.metadata_json via json.dumps; parameterized SQL only.
- **tools/generate_flip_header.py** — Reconstructs FLIP header from DB; `--web` flag for JSON output (API-compatible); parameterized SQL only.

**Path → content → channel → actors:**

- **lupo-includes/classes/ContentChannelActorResolver.php** — getActorsForFilePath($filePath): resolves content_id → channel_id → actors.
- Lookup chain: file_path_from_root → lupo_contents → lupo_edges HAS_CONTENT → channel_id → lupo_actor_channels + lupo_actors.

**Universal flipping API (4.0.14):**

- **api/flip-header.php** — GET endpoint. Params: `path`, `url`, or `content_id` (precedence: path > url > content_id). Output: JSON `{header, resolved, channel_id}`; `?format=yaml` for raw YAML. HTTP 400/404/500. LEXA security: parameterized SQL, path validation. CORS enabled.
- **docs/api/FLIP_API.md** — Full API documentation.
- **tools/web_flip_simulator.py** — Test script to simulate external agent (e.g. Grok) browsing the API.

**Channel 42 seed (4.0.14):**

- **25 kernel agents** on channel 42: actor_ids 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 22, 23, **24** (LEXA), 209, 1212.
- **31 dialog messages:** 1–2 from actor 0 (system); 3–27 one per kernel agent; 28–31 FLIP/FLIPPING info and universal flipping API refs.
- **lupo_dialog_channels:** message_count = 31; file_source = 'docs/doctrine/FLIP/FLIPPING_FILE_LEXA_LILITH.md'.
- **FLIP content:** content_id 2001 (FLIPPING_FILE_LEXA_LILITH.md), 2002 (FLIP_DOCTRINE.md); lupo_edges HAS_CONTENT (edge_id 900001, 900002).

**ANUBIS system (4.0.14):**

- Doctrine: docs/doctrine/ANUBIS/ANUBIS_OVERVIEW.md (role: custodial for dialogs/orphans/redirects); ANUBIS_ORPHAN_RULES.md (orphan def, resolution order, adoption to channel 42/thread 1/actor 3); ANUBIS_PROGRAM_SPEC.md (Python/PHP specs, tables used, no schema changes).
- Programs: tools/anubis_orphan_scanner.py (scanner/resolver/adoption planner, no-DB mode, parameterized); lupo-includes/classes/ANUBIS_Resolver.php (PHP 5.3: classifyOrphan, resolveParent, adoptIntoSeed via PDO_DB).
- Seed: Orphan adoption as message_id 32 (channel 42, thread 1, actor 3, system type); message_count=32 (but no further mods).
- CHANGELOG: Entry for ANUBIS doctrine/programs/adoption under 4.0.14.
- Commit: b91afdc (full b91afdc46ce932a04f35b4f78c135bf2d6e564a3); pushed to main.

**WOLFIE_HEADER_SPECIFICATION.md (4.0.14):**

- Optional FLP enrichment (mood_rgb as 6-char hex, tags, atoms) noted in Tags section.

All 4.0.14 work above is committed and pushed. The new thread must treat this state as canonical and build 4.0.15 from here.

---

### 5. WHAT THE NEW THREAD MUST DO

- **Load all doctrine** (full set in §3) before any action.
- **Treat 4.0.14 as canonical** — API endpoint, 31 messages (plus ANUBIS orphan 32), path → content → channel → actors, FLIP fields, ContentChannelActorResolver, generate_flip_header, import_os, web_flip_simulator, ANUBIS doctrine/programs/adoption.
- **Begin 4.0.15** as a stabilization + feature continuation patch. Only patch bumps (4.0.14 → 4.0.15); no major/minor until auto-installer release cycle.
- **Make NO schema/installer/seed/TOON changes** unless explicitly ordered.
- **Use legacy\craftysyntax only as reference** — read for behavior; never modify.
- **WAIT for explicit instructions** before modifying any files.

---

### 6. WHAT THE NEW THREAD MUST NOT DO

- **Schema inference from the live DB** — Schema comes from TOONs and install SQL only.
- **Reintroducing lupo_agent_registry** — Do not use or add.
- **Modifying installer/seed/migration SQL** — Unless explicitly instructed.
- **Changing TOONs** — Do not create, edit, or delete TOON files.
- **Using modern PHP syntax** — No `[]`, `??`, typed properties in core (PHP 5.3 compatibility).
- **Making assumptions about DB state** — Treat as clean install + seed unless told otherwise.
- **Automatic migrations** — No Lupopedia→Lupopedia upgrade logic in 4.0.x.
- **SQL queries against livehelp_* tables** — Except inside the canonical import script.
- **Modifying legacy\craftysyntax** — Reference only.

---

### 7. DIRECTIVE

You are starting a new thread for Lupopedia **4.0.15**. The codebase is at **4.0.14** (fully finalized: LEXA, FLIP content, universal flipping API, 31 messages + ANUBIS orphan 32, web_flip_simulator, Parts 6.1–6.3, ANUBIS doctrine/programs). You have been given version-bump instructions, clean-reset instructions, full doctrine loading requirements, and the full list of 4.0.14 work to carry forward.

**Do not perform a version bump or change any files until explicitly instructed.**

**Acknowledge this prompt and wait for directions.**

---

END OF PROMPT