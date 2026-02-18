# Initialization Prompt for New Cursor Thread — Lupopedia 4.0.17

**Purpose:** Paste the content below (from "---" to "END OF PROMPT") into a **new** Cursor thread to begin development on Lupopedia 4.0.17. This prompt does NOT perform any version bump or file changes; it only equips the next thread with doctrine and instructions.

---

## Paste from here into new Cursor thread

---

You are starting development on **Lupopedia version 4.0.17**. This is an initialization prompt only. Do not modify any files until you receive explicit instructions.

---

### 1. DELIVERABLES (4.0.17 — MUST BE VERIFIED)

All of the following must be true after a fresh install and seed. The validation steps in §7 confirm each deliverable.

| # | Deliverable | Verification |
|---|-------------|--------------|
| D1 | **Wizard runs from Crafty Syntax 3.7.5 baseline** | DROP tables; restore `database/migrations/old_crafty_syntax_3_7_5_start.sql`; run install.php; wizard completes successfully. |
| D2 | **All seed data is loaded into the database** | Actors (lupo_actors), dialogs (lupo_dialog_messages), content (lupo_contents), edges (lupo_edges), registry (lupo_unified_registry), channels, threads, banned actors (lupo_banned_actors). |
| D3 | **Orphaned message from banned agent exists and is quarantined** | Message 36 (from actor_id 999, DEPRECATED_BANNED) exists in DB with `channel_id = 666`; it does **not** appear in channel 42. |
| D4 | **Messages from Captain Wolfie (actor_id 1000) exist** | Messages 33–35 and others (e.g. message 38) have `from_actor_id = 1000`. |
| D5 | **Login user (actor_id 1000) is set during install/upgrade** | Captain (actor_id 1000) is the human operator; auth and session allow login via captain@lupopedia.com or wisdomoflovingfaith@gmail.com. |
| D6 | **User can log in and navigate to channel 0 and 42** | After login, UI allows navigation to channel 0 (system/kernel) and channel 42 (lupopedia-development). |
| D7 | **All dialog messages visible on channel 42** | 60 messages on channel 42, thread 1 (1–35, 37–61; message 36 is quarantined to channel 666). Message count = 60 for channel 42; 1 for channel 666. |
| D8 | **ANUBIS and LILITH behave correctly** | ANUBIS rejects actor 999; LILITH doctrine loads; orphan classification and adoption logic work. |
| D9 | **ANUBIS successfully adopts valid orphan message(s)** | Confirm at least one seeded orphan (non-banned actor) is adopted into channel 42/thread 1 and visible in UI. |
| D10 | **ANUBIS redirect + quarantine logic confirmed operational** | Confirm redirect 66 → 666 resolves correctly and that banned orphan is placed in channel 666 via resolver logic (not manual seed override). |

---

### 2. SUMMARY OF 4.0.17 GOALS

**Primary goal: End-to-end system validation on a fresh install.**

4.0.17 is a **validation and stabilization patch**. It focuses on verifying that a fresh Lupopedia install (install_new_lupopedia.sql + seed_lupopedia.sql) runs correctly end-to-end: wizard, seed, Captain login, channel navigation, dialog display, FLIP metadata resolution, edge resolution, doctrine loading, and ANUBIS/LILITH behavior.

**Specific objectives:**

- Run the wizard installer using install_new_lupopedia.sql (after restoring Crafty 3.7.5 baseline).
- Load the full seed using seed_lupopedia.sql — all actors, dialogs, content, edges, registry.
- Log in as the Captain user (actor_id = 1000; captain@lupopedia.com or wisdomoflovingfaith@gmail.com) — the user set during install/upgrade.
- Navigate through the web UI to channel 0 (system/kernel) and channel 42 (lupopedia-development).
- Confirm that all dialog messages from all AI agents appear correctly (60 messages on channel 42; message 36 quarantined to channel 666).
- Confirm that there is an orphaned/quarantined message from the banned agent (actor_id 999, DEPRECATED_BANNED) and that it does **not** appear in channel 42.
- Confirm that Captain (actor_id 1000) has visible messages (e.g. 33–35, 38).
- Confirm that all FLIP metadata is visible and correct (file_path_from_root, file_last_modified_system_version, channel associations).
- Confirm that all edges resolve correctly (lupo_edges HAS_CONTENT: content → channel).
- Confirm that doctrine files load correctly (docs/doctrine/, docs/api/).
- Confirm that ANUBIS and LILITH behave correctly on a fresh install (orphan classification, banned actor rejection).

---

### 3. VERSIONING (4.0.16 → 4.0.17)

When instructed to bump the version to 4.0.17, you MUST update the version string in **all** of these locations (see `docs/doctrine/VERSIONING_DOCTRINE.md` §8):

| Location | What to update |
|----------|----------------|
| **config/global_atoms.yaml** | `version`, `versions.lupopedia`, `GLOBAL_CURRENT_LUPOPEDIA_VERSION`, `file.last_modified_system_version`; set `last_updated` to current YYYYMMDDHHIISS. |
| **lupo-includes/version.php** | Docblock `@version` to 4.0.17; fallback literal `$current_version`; set `LUPOPEDIA_VERSION_DATE` to current YYYYMMDDHHIISS. |
| **install.php** | Fallback when `LUPOPEDIA_VERSION` is not defined: change `'4.0.16'` to `'4.0.17'`. |
| **lupo-includes/functions/load_atoms.php** | Fallback in `get_lupopedia_version()`: change `'4.0.16'` to `'4.0.17'`. |
| **install_wizard_classes.php** | Docblock version reference: update to **4.0.17**. |
| **database/migrations/seed_lupopedia.sql** | `@lupo_version` to `'4.0.17'`, `@lupo_version_code` to 40017. |

Patch-only bumps. No major/minor until auto-installer release cycle.

---

### 4. CLEAN DEVELOPMENT CYCLE RESET

The new thread must treat every development cycle as a **clean, empty, fresh start**:

- **DROP ALL TABLES** in the database.
- **RELOAD** the Crafty Syntax 3.7.5 schema using `database/migrations/old_crafty_syntax_3_7_5_start.sql` (or equivalent baseline). This is the **required baseline** for wizard testing.
- **Restore** the original Crafty `config.php` (no lupopedia-config.php).
- **Run the Lupopedia installer** from scratch (install.php) so that the only path exercised is **Crafty Syntax 3.7.5 → Lupopedia 4.0.x**.
- **Run the seed** (seed_lupopedia.sql) after install — this loads all actors, dialogs, content, edges, registry, and banned actors.
- **No live DB inference is ever allowed.** Schema and behavior come from TOONs, doctrine, and the canonical SQL files—never from inspecting the current database state.

---

### 5. DOCTRINE LOADING (FULL SET)

Before taking any action, load and apply **ALL** of the following doctrine from the repository:

- **Installer doctrine** — Only valid path: Crafty Syntax 3.7.5 → Lupopedia 4.0.x. No Lupopedia→Lupopedia upgrade in 4.0.x. See docs/doctrine/INSTALLATION_PATH_DOCTRINE.md.
- **Unified Registry doctrine** — Reserved IDs; no AUTO_INCREMENT for registry-backed tables. See docs/doctrine/UNIFIED_REGISTRY_DOCTRINE.md.
- **Identity doctrine** — Actors, auth_users, actor_source_type; roles via 3-level model.
- **Permission doctrine** — 3-layer model: channel roles, department roles, system.
- **Department doctrine** — department_id = 0 is system (reserved); department_id = 1 is general.
- **PHP 5.3 doctrine** — Use `array()` only; no short array `[]`; no null coalescing `??`; no typed properties/return types in core paths. See .cursor/rules/php-5-3-compatibility.mdc.
- **Schema doctrine (TOONs)** — TOONs in `docs/toons/` are the **only** source of truth. Never guess or invent schema. See .cursor/rules/toon-source-of-truth.mdc.
- **Prefix doctrine** — Use `LUPO_TABLE_PREFIX`; never hardcode `lupo_`.
- **Versioning doctrine** — Patch-only bumps (4.0.16 → 4.0.17); single canonical file `docs/doctrine/VERSIONING_DOCTRINE.md`.
- **Reserved ID doctrine** — Registry-backed tables: explicit IDs; if row exists → UPDATE, else INSERT. See .cursor/rules/reserved-id-doctrine.mdc.
- **Database logic prohibition** — No FOREIGN KEYs, triggers, stored procedures, DEFAULT CURRENT_TIMESTAMP. All logic in application code. See .cursor/rules/database-logic-prohibition-doctrine.mdc.
- **PDO_DB only** — All database access via the project's PDO_DB wrapper. See .cursor/rules/pdo-db-database-access-doctrine.mdc.
- **Migration doctrine** — Schema changes require BOTH a migration file AND an update to install_new_lupopedia.sql. See .cursor/rules/migration-doctrine.mdc.
- **FLIP doctrine** — File-Level Inference Protocol. FLIP Headers at top of files; infer only from header; no guessing. See docs/doctrine/FLIP/FLIP_DOCTRINE.md and .cursor/rules/flip-doctrine.mdc.
- **FLP doctrine** — Federated Likeness Protocol; governance layer, councils as channels, emotional geometry. See docs/doctrine/FLIP/FLP_OVERVIEW.md.
- **LILITH heterodox review doctrine** — See docs/doctrine/FLIP/FLIPPING_FILE_LEXA_LILITH.md.
- **ANUBIS doctrine** — Custodial intelligence for dialogs, lineage, orphans, redirects, banned actors. See docs/doctrine/ANUBIS/ANUBIS_OVERVIEW.md, ANUBIS_ORPHAN_RULES.md, ANUBIS_PROGRAM_SPEC.md.
- **FLP_CHANNEL_* doctrine** — Channel-specific FLIP files for channels 0, 42, 51, 666. See docs/doctrine/FLIP/FLP_CHANNEL_0.md, FLP_CHANNEL_42.md, FLP_CHANNEL_51.md, FLP_CHANNEL_666.md.

---

### 6. CARRY FORWARD ALL WORK COMPLETED IN 4.0.16

The repository state is **canonical** for the following. The new thread must treat this as already done and must not revert or contradict it.

**4.0.16 FLIP / Channel / ANUBIS synchronization:**

- **FLIP headers** — 106+ doctrine .md files with valid FLIP headers (file.last_modified_system_version 4.0.16). scripts/flip_header_audit.py for validation.
- **lupo_contents** — Content 5000–5037: doctrine .md files, FLIP_API.md (5033), FLP_CHANNEL_0/42/51/666 (5034–5037).
- **lupo_edges** — HAS_CONTENT edges linking content to channels 0, 51, 42 (ANUBIS-related), 666 (quarantine). Path lookup: file_path_from_root → lupo_contents → lupo_edges HAS_CONTENT → channel_id.
- **lupo_unified_registry** — Entity keys including channel:0:flip, channel:42:flip, channel:51:flip, channel:666:flip.
- **lupo_banned_actors** — Single source of truth for banned actor_ids. Actor 999 (DEPRECATED_BANNED) seeded as banned. ANUBIS_Resolver::getBannedActorIds(); anubis_orphan_scanner.py get_banned_actor_ids(). Migration: 20260218_create_lupo_banned_actors.sql.
- **Channel 666 (ANUBIS Quarantine)** — Seeded. lupo_anubis_redirects: old_id 66 → new_id 666. Message 36 (actor 999) quarantined to channel 666.
- **Channel 42 dialog** — 61 messages total: 1–2 system; 3–27 kernel agents; 28–31 FLIP/API refs; 32–35 ANUBIS adoption; 36 quarantined to 666; 37–61 LILITH migration thread (25-agent closeout). lupo_dialog_channels.message_count = 61.
- **25 kernel agents** on channel 42: actor_ids 1–20, 22–24, 209, 1212.
- **Universal flipping API** — api/flip-header.php (path, url, content_id). CORS enabled.
- **ANUBIS programs** — tools/anubis_orphan_scanner.py; lupo-includes/classes/ANUBIS_Resolver.php (classifyOrphan, resolveParent, adoptIntoSeed).

---

### 7. REQUIRED VALIDATION STEPS (4.0.17)

Perform the following validation in order:

1. **Fresh install** — DROP tables; restore `database/migrations/old_crafty_syntax_3_7_5_start.sql`; run install_new_lupopedia.sql (via wizard); run seed_lupopedia.sql.
2. **Wizard completion** — Ensure wizard completes without error. Version shown: 4.0.16 (or 4.0.17 after bump).
3. **Captain login** — Log in as actor_id 1000 (captain@lupopedia.com or wisdomoflovingfaith@gmail.com). Verify session and redirect. This user is set during install/upgrade.
4. **Channel 0 (system/kernel)** — Navigate to channel 0. Verify content associations. Verify FLIP metadata for kernel content.
5. **Channel 42 (lupopedia-development)** — Navigate to channel 42. Verify 60 dialog messages display (1–35, 37–61). Verify thread 1. Verify kernel agent names and message order. Verify at least one message from actor_id 1000 (Captain), e.g. messages 33–35 or 38.
5a. **Banned orphan visibility** — Confirm that message 36 (from banned actor_id 999) does **not** appear in channel 42's thread 1. Verify in the database that message 36 exists with `channel_id = 666` (quarantine).
6. **FLIP metadata** — For sample paths (e.g. docs/doctrine/FLIP/FLP_OVERVIEW.md, docs/doctrine/FLIP/FLP_CHANNEL_42.md), verify lupo_contents row exists, file_path_from_root matches. Before version bump: `file_last_modified_system_version = 4.0.16`. After bump: `4.0.17`.
7. **Edge resolution** — For content_id 5034–5037, verify lupo_edges HAS_CONTENT to channels 0, 51; 5035 to 42; 5037 to 666.
8. **Registry** — Verify lupo_unified_registry entries for channel:0:flip, channel:42:flip, channel:51:flip, channel:666:flip.
9. **Doctrine loading** — Load a sample doctrine file via web or path lookup. Verify no 404 or missing-content errors.
10. **ANUBIS** — Run tools/anubis_orphan_scanner.py in no-DB mode or with test DB. Verify classifyOrphan rejects actor_id 999. Verify getBannedActorIds returns (999) or table-derived list. **Fallback test:** Temporarily drop lupo_banned_actors (or simulate absence); verify getBannedActorIds() still returns fallback list including 999.
11. **ANUBIS adoption verification (active behavior)** — Identify a seeded orphan message that is not from a banned actor. Confirm: classifyOrphan() returns adoptable; resolveParent() assigns channel 42 and thread 1; adoptIntoSeed() persists correct channel_id + thread_id; message is visible in channel 42 UI; adoption logic did not bypass resolver.
12. **Quarantine + redirect verification** — Confirm lupo_anubis_redirects contains old_id = 66 → new_id = 666. Attempt to resolve channel 66 via application routing; verify redirect lands at 666. Confirm message 36 (actor_id 999) is only visible under channel 666. Confirm ANUBIS classification explicitly returns is_rejected => true, rejected_reason => 'banned_actor'.

---

### 8. REQUIRED CODE CHANGES (IF ANY)

- **No schema changes** unless explicitly instructed.
- **No new tables** unless explicitly instructed.
- **Bug fixes only** — If validation reveals bugs (e.g. missing UI route for channel 0, incorrect message count display), fix those.
- **Version bump** — Only when explicitly instructed to bump to 4.0.17.

---

### 9. REQUIRED DOCTRINE UPDATES (IF ANY)

- **None** unless validation reveals doctrine gaps. If new validation steps are documented, update ANUBIS_ORPHAN_RULES.md or FLIPPING_FILE_LEXA_LILITH.md as appropriate.
- **FLIP headers** — When editing any doctrine .md file, set file.last_modified_system_version to current version (4.0.16 or 4.0.17 per NOTE_HEADER_VERSION_AND_MERGE.md).

---

### 10. REQUIRED SEED OR MIGRATION UPDATES (IF ANY)

- **None** unless validation reveals missing seed data (e.g. missing content, edge, or registry row).
- **Migration 20260218_create_lupo_banned_actors.sql** — Already integrated. Run on existing DBs; install_new_lupopedia.sql includes lupo_banned_actors for fresh installs.

---

### 11. REQUIRED UI CHECKS

- **Login** — Captain (actor_id 1000), the user set during install/upgrade, can log in. Session persists. Redirect after login works.
- **Channel 0** — UI allows navigation to channel 0 (system/kernel). Content list or summary displays. No blank page or 500 error.
- **Channel 42** — UI allows navigation to channel 42 (lupopedia-development). Dialog thread 1 displays. All 60 messages visible (1–35, 37–61; message 36 is quarantined to channel 666). Agent names resolve (actor_id → name from lupo_actors). At least one Captain message (actor_id 1000) visible.
- **Channel 666** — If UI exposes quarantine channel, verify message 36 (from actor_id 999) displays with quarantined text. Otherwise, verify backend resolves channel 666 for redirect 66→666. Message 36 must **not** appear in channel 42.
- **FLIP API** — GET /api/flip-header.php?path=docs/doctrine/FLIP/FLP_CHANNEL_42.md returns JSON with header, resolved channel_id. No 404.

---

### 12. REQUIRED ANUBIS / LILITH BEHAVIORAL CHECKS

- **ANUBIS_Resolver::getBannedActorIds()** — Returns array including 999 when lupo_banned_actors has row for actor_id 999. Fallback to BANNED_ACTOR_IDS_FALLBACK if table missing.
- **ANUBIS_Resolver::classifyOrphan()** — When actor_id = 999, returns is_rejected => true, rejected_reason => 'banned_actor'. When actor is valid orphan, returns adoptable.
- **ANUBIS_Resolver::adoptIntoSeed()** — Does not adopt messages from banned actor_ids.
- **ANUBIS_Resolver::resolveParent()** — Assigns channel 42 and thread 1 for valid orphans.
- **anubis_orphan_scanner.py** — get_banned_actor_ids(cursor) returns set including 999. classify_orphan rejects actor_id 999 with rejected_reason 'banned_actor'.
- **ANUBIS execution path verification** — Confirm that seeded orphan rows were processed via ANUBIS_Resolver methods and not directly inserted with resolved channel/thread values (adoption is intentional, not pre-seeded bypass).
- **Log trace (if available)** — Confirm adoption and rejection events recorded when logging is enabled.
- **LILITH** — Heterodox review doctrine (FLIPPING_FILE_LEXA_LILITH.md) applies. No behavioral code changes unless explicitly instructed; validation confirms doctrine loads.

---

### 13. FINAL COMMIT / PUSH PLAN

When 4.0.17 validation is complete:

1. **Version bump** — Update all locations in §3 to 4.0.17.
2. **CHANGELOG.md** — Add 4.0.17 section with:
   - End-to-end system validation on fresh install.
   - Wizard, seed, Captain login, channel 0/42 navigation verified.
   - Dialog messages 1–35, 37–61 display on channel 42; message 36 quarantined to 666.
   - FLIP metadata, edges, registry verified.
   - ANUBIS/LILITH behavioral checks passed.
   - Any bug fixes applied.
3. **FLIP headers** — Set file.last_modified_system_version to 4.0.17 for any edited doctrine files.
4. **Stage** — seed_lupopedia.sql (if updated), CHANGELOG.md, config/global_atoms.yaml, lupo-includes/version.php, install.php, lupo-includes/functions/load_atoms.php, install_wizard_classes.php, any modified doctrine or code.
5. **Commit** — `4.0.17: End-to-end validation on fresh install; verified wizard, seed, Captain login, channel 0/42, dialogs, FLIP metadata, edges, ANUBIS/LILITH.`
6. **Push** — `git push origin main`.

---

### 14. WHAT THE NEW THREAD MUST DO

- **Load all doctrine** (§5) before any action.
- **Treat 4.0.16 as canonical** — FLIP audit, ANUBIS banned actors, channel 666, FLP_CHANNEL_*, LILITH thread 37–61, content 5033–5037, edges, registry.
- **Begin 4.0.17** as a validation and stabilization patch. Focus on end-to-end verification, not new features.
- **Run validation steps** (§7) when instructed.
- **Apply bug fixes** only when validation reveals defects.
- **WAIT for explicit instructions** before modifying any files.

---

### 15. WHAT THE NEW THREAD MUST NOT DO

- **Schema inference from the live DB** — Schema comes from TOONs and install SQL only.
- **Modifying installer/seed/migration SQL** — Unless explicitly instructed.
- **Changing TOONs** — Do not create, edit, or delete TOON files.
- **Using modern PHP syntax** — No `[]`, `??`, typed properties in core (PHP 5.3 compatibility).
- **Making assumptions about DB state** — Treat as clean install + seed unless told otherwise.
- **Automatic migrations** — No Lupopedia→Lupopedia upgrade logic in 4.0.x.

---

### 16. DIRECTIVE

You are starting a new thread for Lupopedia **4.0.17**. The codebase is at **4.0.16** (fully finalized: FLIP/Channel/ANUBIS synchronization, 61 messages, FLP_CHANNEL_*, banned actors, channel 666, LILITH migration thread). You have been given version-bump instructions, clean-reset instructions, full doctrine loading requirements, validation steps, UI checks, ANUBIS/LILITH behavioral checks, and the commit/push plan.

**Do not perform a version bump or change any files until explicitly instructed.**

**Acknowledge this prompt and wait for directions.**

---

END OF PROMPT
