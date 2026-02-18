# Lupopedia Changelog

Canonical version history.

Each release entry follows this format:

## Lupopedia [VERSION] — [single line description] - [YYYY-MM-DD]

As we continue development on a version, we append new changes under that version's header until it is released.

## Versioning doctrine (4.0.x)

- **Purpose of 4.0.x:** The 4.0.x series (4.0.0 → 4.0.x and all future 4.0.x patches) is a development and stabilization series. It exists solely to refine the single supported upgrade path: **Crafty Syntax 3.7.5 → Lupopedia 4.0.x**. Each patch is an iteration on the installer, wizard, importer, doctrine enforcement, and compatibility rules for that path.
- **No Lupopedia → Lupopedia upgrades before 4.1.0.** In the 4.0.x line there are no supported upgrades from an existing Lupopedia installation. The only valid inputs are a new install or an upgrade from Crafty Syntax 3.7.5.
- **4.1.0** will be the first version to support Lupopedia → Lupopedia upgrades. 4.1.0 will not be created until a stable 4.0.x release is published through auto-installers (e.g. Softaculous, Installatron). Until then, 4.0.x remains the development/stabilization series.

---



## Lupopedia 4.0.16 — FLIP header audit, ANUBIS adoption of recovered doctrine files - 2026-02-18

- **Version bump 4.0.15 → 4.0.16** in config/global_atoms.yaml, lupo-includes/version.php, install.php, lupo-includes/functions/load_atoms.php, install_wizard_classes.php, database/migrations/seed_lupopedia.sql, api/flip-header.php, docs/api/FLIP_API.md, docs/doctrine/VERSIONING_DOCTRINE.md, README.md, tools/md_flip_ingest.py.
- **FLIP headers aligned to 4.0.16:** All doctrine .md files with `file.last_modified_system_version` updated from 4.0.13/4.0.15 to 4.0.16 (FLIP, FLP, ANUBIS, migrations, etc.). flip-doctrine.mdc, NOTE_HEADER_VERSION_AND_MERGE.md, VERSIONING_DOCTRINE.md canonical version, GOV-TOON-GENERATION-001, UNIVERSAL_WOLFIE_HEADER_SPECIFICATION. lupo_contents seed entries (file_last_modified_system_version) and dialog message 32 (v4.0.16).
- **Performed full FLIP header audit** across all doctrine files (docs/doctrine/, docs/api/).
- **Recovered and adopted missing-header doctrine files via ANUBIS.** Added HYBRID FLIP headers to 78 .md files previously missing the wolfie.headers signature.
- **Seeded FLIP metadata for recovered files** into lupo_contents (content_id 5033 for docs/api/FLIP_API.md) and linked to channels 42, 0, and 51 via lupo_edges.
- **Ensured total FLIP header count meets 4.0.16 baseline requirements** (102 doctrine .md files with valid FLIP headers).
- scripts/flip_header_audit.py added for future FLIP header validation.
- **lupo_banned_actors table:** Single source of truth for banned actor_ids. Columns: banned_actor_id, actor_id, ip_address (optional), reason, banned_ymdhis, banned_by_actor_id, is_deleted. ANUBIS reads from table; fallback to BANNED_ACTOR_IDS_FALLBACK if table missing.
- **ANUBIS banned-actor logic:** Messages from banned actor_ids (e.g. actor_id 999 DEPRECATED_BANNED) are not adopted. `ANUBIS_Resolver::getBannedActorIds()` reads from lupo_banned_actors; `adoptIntoSeed()` rejects banned actors; `classifyOrphan()` returns `is_rejected => true`, `rejected_reason => 'banned_actor'`. Python scanner `get_banned_actor_ids(cursor)` reads from table. ANUBIS_ORPHAN_RULES §5 documents banned actors.
- **Actor 999 (DEPRECATED_BANNED):** Seeded as banned actor placeholder (is_deleted=1 in lupo_actors). Row in lupo_banned_actors (banned_actor_id 1, reason deprecated_experimental_persona, banned_by 1000). Deprecated experimental personas that promoted forbidden doctrine are on the banned list.
- **Orphan message 36:** Example message from actor_id 999 in channel 42/thread 1; documents banned-actor behavior. message_count for channel 42 set to 36.
- Migration 20260218_create_lupo_banned_actors.sql for existing DBs.
- REQUIRED_TABLES: add lupo_banned_actors to required list.
- **Channel 666 (ANUBIS Quarantine):** Seeded in lupo_channels, lupo_dialog_channels, lupo_dialog_threads. Banned/rejected messages route here.
- **lupo_anubis_redirects:** Seeded redirect: table lupo_channels, old_id 66 -> new_id 666. References to channel 66 resolve to 666 (Quarantine).
- **lupo_actor_channels (999↔666):** Actor 999 membership on channel 666 (actor_channel_id 1999, status I).
- **Quarantined message 36:** Moved from channel 42/thread 1 to channel 666/thread 666. Generic text: "FORBIDDEN MESSAGE — quarantined by ANUBIS". metadata_json: banned, reason deprecated_experimental_persona. Channel 42 message_count = 35; channel 666 message_count = 1.
- **Performed full FLP_* doctrine seeding audit.** Verified all 8 FLP_* files (docs/doctrine/FLIP/FLP_*.md) have lupo_contents (content_id 5019–5026), lupo_edges to channels 0 and 51 (HAS_CONTENT), and lupo_unified_registry (9050019–9050026). FLP files are not ANUBIS-related; channel 42 edges are optional. All FLIP headers report file.last_modified_system_version 4.0.16 and file.last_modified_utc. No missing entries; seed already complete.
- **4.0.16 closeout: LILITH migration thread (messages 37–61).** Seeded structured 25-agent migration conversation on channel 42, thread 1. Narrative tone: reduced metaphor density, increased architectural clarity, stronger doctrinal framing. Topics: migration overview, history, FLIP headers, seeding philosophy, CHANGELOG, edges, ANUBIS (actor 999 banned), heterodoxy, compassion, chaos, tooling, growth, conversation, audit, navigation, tools, orphans, ethics, adoption, truth, emotional geometry, time, security, completion, transition. lupo_dialog_channels.message_count for channel 42 set to 61. Transition to 4.0.17 initiated.
- **Completed channel FLIP header database audit.** Added and verified FLIP headers for all active channels (0, 42, 51, 666). Created FLP_CHANNEL_0.md, FLP_CHANNEL_42.md, FLP_CHANNEL_51.md, FLP_CHANNEL_666.md with FLIP headers (file.last_modified_system_version 4.0.16, file.last_modified_utc, channel_id, tags, mood_rgb). Seeded missing channel FLIP metadata into lupo_contents (content_id 5034–5037), lupo_unified_registry (entity_key channel:0:flip, channel:42:flip, channel:51:flip, channel:666:flip), and lupo_edges (HAS_CONTENT to channels 0, 51; 42 for ANUBIS-related; 666 for quarantine). Ensured channel-level FLIP doctrine is complete for 4.0.16.
- **4.0.16 finalization sweep.** Channel FLIP Header Database Audit and FLIP Doctrine Seeding Audit complete. Seeded content 5033 (FLIP_API.md), 5034–5037 (FLP_CHANNEL_*). All edges and registry entries present. Migration 20260218_create_lupo_banned_actors.sql integrated (CREATE TABLE IF NOT EXISTS for existing DBs). install_new_lupopedia.sql and seed_lupopedia.sql synchronized. Ready for 4.0.17.

---

## Lupopedia 4.0.17 — (pending)

- **Status:** Pending. Version bump and goals to be defined.

---

## Lupopedia 4.0.15 — Initialized 4.0.15 dev cycle: global .md FLIP ingestion, hybrid headers, doctrine on channels 0 and 51 - 2026-02-17

- **Initialized Lupopedia 4.0.15** development cycle.
- **Version bump 4.0.14 → 4.0.15** in config/global_atoms.yaml, lupo-includes/version.php, install.php, lupo-includes/functions/load_atoms.php, install_wizard_classes.php, database/migrations/seed_lupopedia.sql (including FLIP content 2001 and @lupo_version / @lupo_version_code).
- **Actor 1000 (CAPTAIN):** wisdomoflovingfaith@gmail.com; admin roles on channels 0, 42, 51 via lupo_actor_channels and lupo_actor_channel_roles.
- **ANUBIS doctrine completed:** docs/doctrine/ANUBIS/ANUBIS_OVERVIEW.md, ANUBIS_ORPHAN_RULES.md, ANUBIS_PROGRAM_SPEC.md. Doctrine content in lupo_contents on channels 0 and 51 for contextual classification, orphan resolution hints, lineage/redirect logic.
- **ANUBIS program implemented:** tools/anubis_orphan_scanner.py (Python orphan scanner, resolver, adoption planner); lupo-includes/classes/ANUBIS_Resolver.php (PHP 5.3: classifyOrphan, resolveParent, adoptIntoSeed).
- **ANUBIS adoption:** Multiple orphaned dialog messages adopted into channel 42 seed thread via ANUBIS doctrine. ANUBIS adopted a lost CAPTAIN-originated message (actor_id 1000) into channel 42 seed thread; message had no parent, no thread, and no FLIP header.
- **HYBRID FLIP headers:** Implemented for ANUBIS doctrine files. Verified FLIP headers for all FLIP/FLP/LILITH/LEXA doctrine files.
- **Seed-based .md ingestion:** All .md files ingested into lupo_contents, lupo_unified_registry, lupo_edges during seed. First batch (~30 doctrine .md files, content_id 5000–5029) inlined in seed_lupopedia.sql. tools/md_flip_ingest.py with --seed-mode and -o for batch generation.
- **Channel mapping:** Doctrine .md files (docs/doctrine/) mapped to channels 0 (System Kernel) and 51 (Doctrine Council); other .md files mapped to channel 0.
- **ContentChannelActorResolver and FLIP loader:** Stability confirmed; no behavioral changes.
- **Universal flipping API:** api/flip-header.php remains functional and documented. LUPOPEDIA_PUBLIC_PATH subdir support documented. docs/api/FLIP_API.md, docs/doctrine/FLIP/FLIPPING_FILE_LEXA_LILITH.md version references updated to 4.0.15.
- **docs/channels/agents/WOLFIE_HEADER_SPECIFICATION.md:** Updated with hybrid optional field rules (mood_rgb, tags, atoms).
- **docs/doctrine/FLIP/FLIPPING_FILE_LEXA_LILITH.md:** Header and dialog updated to 4.0.15; message notes wizard main admin and FLIP API.
- **Install wizard — main admin user:** For **new installs**, the Config step includes main admin account creation. Default email **captain@lupopedia.com**; user enters password (min 8 characters). Creates **auth_user_id 10000**, **actor_id 10000** (reserved ID doctrine: explicit ID; if exists → UPDATE, else INSERT). Main admin receives: captain role on **channel 0** (system kernel), **channel 1** (Administration), **channel 42** (Lupopedia Development); administrator on **department 0** (system); **owner** on Admin module (global admin access). InstallWizardMainAdmin class in install_wizard_classes.php; PHP 5.3–safe bcrypt in wizard (no config dependency).
- **TOON authority replaced with seed SQL authority:** install_new_lupopedia.sql and seed_lupopedia.sql are the single source of truth for table and column definitions.
- **Regenerated TOON files** from canonical schema via scripts/generate_toon_from_sql.py (196 tables).
- **Verified schema consistency** between seed SQL and regenerated TOONs.
- **Seeded all FLIP headers** into lupo_contents and mapped via lupo_edges (LILITH_ANUBIS_GUIDANCE, LILITH_ANUBIS_GUIDANCE_FLIP; channels 0, 51, 42).
- **Added FLIP metadata entry for dialog_message_id 34 (Ara/Lilith heterodox review).** content_id 5032, slug `dialog-flip-34-ara-lilith-review`; lupo_unified_registry (9050032, entity_key `dialog:34`); lupo_edges HAS_CONTENT to channels 42, 0, 51.
- **Ensured dialogs also have FLIP metadata seeded from the beginning.**
- **Verified regenerated TOONs match canonical schema** (install_new_lupopedia.sql and seed_lupopedia.sql).
- **Ensured all doctrine files** contain HYBRID FLIP headers (ANUBIS, FLIP, root doctrine).
- No new schema; seed already had messages 30–31 and FLIP content; generate_flip_header.py (--web) and import_os.py (tags → lupo_contents.tags) unchanged.

**4.0.15 thread summary (canonical):**
- Replaced outdated TOON authority with canonical schema from install_new_lupopedia.sql and seed_lupopedia.sql.
- Regenerated all TOON files from canonical schema (lupo_contents, lupo_edges, lupo_channels, lupo_unified_registry, lupo_actors, lupo_actor_channels, lupo_actor_channel_roles, lupo_dialog_threads, lupo_dialog_messages, lupo_dialog_channels).
- Verified schema consistency between regenerated TOONs and seed SQL (no mismatches; no drift).
- Verified FLIP headers for all doctrine files under docs/doctrine/.
- Added missing HYBRID FLIP headers to INSTALLATION_PATH_DOCTRINE.md, UNIFIED_REGISTRY_DOCTRINE.md, and VERSIONING_DOCTRINE.md.
- Created FLIP-only file docs/doctrine/ANUBIS/LILITH_ANUBIS_GUIDANCE_FLIP.md containing only the FLIP header.
- Seeded all FLIP headers into lupo_contents with explicit IDs (5000–5029) and mapped them via lupo_edges to channels 0, 51, and 42 (ANUBIS-related).
- Ensured all FLIP metadata is seeded from the beginning (no reconstruction required).
- Added FLIP metadata entry for dialog_message_id 34 (Ara/Lilith heterodox review) as content_id 5032, with unified registry entry and edges to channels 42, 0, and 51.
- Adopted lost CAPTAIN message (actor_id 1000) into channel 42/thread 1 via ANUBIS.
- Adopted Lilith's heterodox review as dialog_message_id 34 via ANUBIS.
- Updated message_count for channel 42 accordingly.
- Completed ANUBIS doctrine set (ANUBIS_OVERVIEW.md, ANUBIS_ORPHAN_RULES.md, ANUBIS_PROGRAM_SPEC.md).
- Confirmed ANUBIS program stability (anubis_orphan_scanner.py and ANUBIS_Resolver.php).
- Updated WOLFIE_HEADER_SPECIFICATION.md with hybrid optional field rules.
- Confirmed ContentChannelActorResolver stability.
- Confirmed universal flipping API functionality (api/flip-header.php).
- Seeded first batch of doctrine files (content_id 5000–5029).
- Ensured doctrine files are mapped to channels 0 and 51.
- Confirmed CAPTAIN identity (actor_id 1000, wisdomoflovingfaith@gmail.com) with admin roles on channels 0, 42, and 51.
- Prepared version bump for 4.0.15 (version.php, atoms, installer text).
- No schema changes introduced in 4.0.15.
- Added missing HYBRID FLIP headers to INSTALLATION_PATH_DOCTRINE.md and UNIFIED_REGISTRY_DOCTRINE.md.
- Ensured all doctrine files now contain valid FLIP headers consistent with 4.0.15 requirements.

---

## Lupopedia 4.0.14 — LEXA activated, FLIP content seeded, universal flipping API - 2026-02-17

- **Added actor_id 1000 (CAPTAIN, captain@lupopedia.com)** to installer and seed. Added channel 42 membership, admin role, and initial dialog message.
- **LEXA (boundary keeper)** added to seeded kernel agents on channel 42 (Lupopedia Development).
- **database/migrations/seed_lupopedia.sql:** LEXA as **actor_id 24**: new row in lupo_actors (slug `lexa`, name `LEXA`); new row in lupo_unified_registry (unified_registry_id 9001024, entity_index 24, is_kernel = 1); lupo_actor_channels (actor_channel_id 1024, channel_id 42); lupo_actor_channel_roles (actor_channel_role_id 2022, role_key `admin`); one dialog message (dialog_message_id 25): "Boundary enforcement active. LEXA online." (message_type `system`). Channel 42: 25 kernel agents, 31 dialog messages.
- **Self-referential FLIP content:** content_id 2001 (FLIPPING_FILE_LEXA_LILITH.md), 2002 (FLIP_DOCTRINE.md) with file_path_from_root, file_last_modified_system_version, file_last_modified_utc. lupo_edges HAS_CONTENT (edge_id 900001, 900002) linking channel 42 to those contents. Path lookup chain seeded: file_path_from_root → content_id → channel_id (lupo_edges) → actors.
- **Dialog messages 28–32:** FLIP/FLIPPING basic info (28–29); universal flipping API refs (30–31 from LEXA and SYSTEM); orphaned dialog adopted via ANUBIS doctrine (32, WOLFIE). lupo_dialog_channels.file_source set to `docs/doctrine/FLIP/FLIPPING_FILE_LEXA_LILITH.md`; message_count 32.
- **api/flip-header.php:** New GET endpoint. Params: `path`, `url`, or `content_id` (precedence: path > url > content_id). Output: default JSON `{header, resolved, channel_id}`; `?format=yaml` for raw YAML. HTTP status: 400 (invalid/missing params), 404 (not found), 500 (internal). LEXA security: parameterized SQL, path validation (inside repo root, no `..`). CORS enabled for external agent browsing.
- **docs/api/FLIP_API.md:** Documentation for `/api/flip-header.php` (params, precedence, format, responses, security).
- **docs/doctrine/FLIP/FLIPPING_FILE_LEXA_LILITH.md:** Part 1.4 Universal Agent Flipping (subdir-aware); expanded 1.2 optional fields (mood_rgb, tags, atoms; storage in lupo_contents.tags/dialog_notes); Part 2.10 web API validation note; Parts 6.1–6.3 API spec, security/doctrine, future auth; Quick Reference updated; version 4.0.14.
- **tools/generate_flip_header.py:** Added `--web` flag for JSON output (API-compatible).
- **scripts/import_os.py:** Optional `tags` parsing from FLIP header to lupo_contents.tags (JSON).
- **tools/web_flip_simulator.py:** Test script to simulate external agent (e.g. Grok) browsing the API.
- **docs/channels/agents/WOLFIE_HEADER_SPECIFICATION.md:** Optional FLP enrichment (mood_rgb, tags, atoms) noted in Tags section.
- No new schema; uses existing lupo_* tables.
- **ANUBIS adoption:** Adopted orphaned dialog message into channel 42 seed thread via ANUBIS doctrine (dialog_message_id 32).
- **Completed ANUBIS doctrine and ANUBIS program.** Doctrine: `docs/doctrine/ANUBIS/` (ANUBIS_OVERVIEW.md, ANUBIS_ORPHAN_RULES.md, ANUBIS_PROGRAM_SPEC.md). Program: `tools/anubis_orphan_scanner.py` (Python orphan scanner, resolver, adoption planner); `lupo-includes/classes/ANUBIS_Resolver.php` (PHP 5.3: classifyOrphan, resolveParent, adoptIntoSeed). Adopted orphaned dialog message into channel 42 seed thread via ANUBIS.

---

## Lupopedia 4.0.13 — Version bump, FLIP doctrine, FLIP Headers, loader alignment - 2026-02-17

Lupopedia 4.0.13 is part of the iterative development cycle for the **Crafty Syntax 3.7.5 → Lupopedia 4.0.x** upgrade path.  
There are **no Lupopedia → Lupopedia upgrades** in the 4.0.x series.

### Version bump 4.0.12 → 4.0.13

- **config/global_atoms.yaml:** `file.last_modified_system_version`, `version`, `versions.lupopedia`, `GLOBAL_CURRENT_LUPOPEDIA_VERSION` set to 4.0.13; `last_updated` set to 20260217140000.
- **lupo-includes/version.php:** Docblock `@version` and fallback literals (when atom loader unavailable) updated to 4.0.13; `LUPOPEDIA_VERSION_DATE` set to 20260217140000.
- **install.php:** Fallback when `LUPOPEDIA_VERSION` is not defined updated to `'4.0.13'` so the wizard shows 4.0.13 when run without lupopedia-config.php.
- **lupo-includes/functions/load_atoms.php:** Fallback in `get_lupopedia_version()` updated to `'4.0.13'`.
- **database/migrations/seed_lupopedia.sql:** `@lupo_version` set to `'4.0.13'`, `@lupo_version_code` to 40013.
- **docs/doctrine/VERSIONING_DOCTRINE.md:** Canonical current version and §8 patch examples updated to 4.0.13.

### FLIP — File-Level Inference Protocol (canonical naming)

- **FLIP** = File-Level Inference Protocol. **FLIP Headers** is the canonical name for the header block at the top of files; **Wolfie Headers**, **CROP Headers**, and **FLIPPING Headers** are aliases of the same system.
- **docs/doctrine/FLIP/FLIP_DOCTRINE.md:** Created (then moved from docs/doctrine/ into docs/doctrine/FLIP/). Canonical FLIP doctrine: infer file identity, lineage, channel, version, emotional state, doctrine, placement, and semantic meaning entirely from the FLIP Header; no guessing. Compliance checklist for agents.
- **docs/channels/agents/WOLFIE_HEADER_SPECIFICATION.md:** New subsection **FLIP — File-Level Inference Protocol**. States that the header block is canonically **FLIP Headers** (alias: Wolfie Headers, CROP Headers, FLIPPING Headers); inference from FLIP Header only; link to docs/doctrine/FLIP/FLIP_DOCTRINE.md.
- **README.md:** Under "Lupopedia adds," FLIP bullet with link to docs/doctrine/FLIP/FLIP_DOCTRINE.md; under "All contributors and AI agents must read and follow," FLIP_DOCTRINE.md added to doctrine list; section **FLIP Header Update Requirements** (replacing "Wolfie Header Update Requirements") with first mention "FLIP Headers (alias: Wolfie Headers, CROP Headers)"; subsequent references use "FLIP Header(s)."
- **.cursor/rules/flip-doctrine.mdc:** New Cursor rule (alwaysApply: true). FLIP Headers canonical; Wolfie/CROP/FLIPPING aliases; infer only from header; no guessing; treat absence as absence. When adding or editing a FLIP Header, set file.last_modified_system_version to current Lupopedia version (4.0.13); 3.x vs 4.0.x merge note and link to NOTE_HEADER_VERSION_AND_MERGE.md.

### Directory docs/doctrine/flp/ → docs/doctrine/FLIP/

- **Renamed** docs/doctrine/flp/ to docs/doctrine/FLIP/ (Federated Likeness Protocol docs remain FLP_*.md; FLIP doctrine lives in same folder).
- **Moved** docs/doctrine/FLIP_DOCTRINE.md to docs/doctrine/FLIP/FLIP_DOCTRINE.md.
- **docs/doctrine/FLIP/README.md:** Updated to list both FLIP (File-Level Inference Protocol) and FLP (Federated Likeness Protocol); FLIP_DOCTRINE.md and NOTE_HEADER_VERSION_AND_MERGE.md under FLIP; all FLP_*.md under FLP. FLP_OVERVIEW.md path reference updated from docs/doctrine/flp/ to docs/doctrine/FLIP/.

### FLIP Headers added across docs/doctrine/FLIP/

- All doctrine files in docs/doctrine/FLIP/ now have a **FLIP Header** (alias: Wolfie Header, CROP Header, FLIPPING Header) at the top.
- Each header includes: canonical naming comment; `wolfie.headers: explicit architecture with structured clarity for every file.`; `file_path_from_root: docs/doctrine/FLIP/<filename>`; `file.last_modified_system_version: "4.0.13"`; `file.last_modified_utc: "00000000000000"`; comment `# channel_id unresolved — requires lupo_contents lookup by application.`
- **Files receiving FLIP Headers:** README.md, NOTE_HEADER_VERSION_AND_MERGE.md, FLIP_DOCTRINE.md, FLP_OVERVIEW.md, FLP_EMOTIONAL_GEOMETRY.md, FLP_COUNCILS_AS_CHANNELS.md, FLP_HETERODOX_REVIEWERS.md, FLP_EMOTIONAL_AGGREGATION.md, FLP_ESCROW_AND_FUND_LAYER.md, FLP_LUPOPEDIA_COUNCIL_SEAT.md, FLP_DOCTRINE_BOUNDARIES.md. No schema or TOON changes.

### NOTE_HEADER_VERSION_AND_MERGE.md

- **docs/doctrine/FLIP/NOTE_HEADER_VERSION_AND_MERGE.md:** Created. Reminder: when editing a file, set file.last_modified_system_version to current Lupopedia version (4.0.13); source of truth global_atoms.yaml, version.php, VERSIONING_DOCTRINE.md. 3.x vs 4.0.13: when merging legacy material, prefer existing 4.0.x docs; FLIP Headers canonical, Wolfie/CROP/FLIPPING aliases. Linked from flip-doctrine.mdc and README in FLIP folder.

### Loader alignment (import_os.py)

- **scripts/import_os.py:** Recognizes **FLIP Headers** as canonical; treats **Wolfie/CROP/FLIPPING** as aliases (same YAML block, signature or file_path_from_root). Extracts file_path_from_root from header and stores it in lupo_contents.file_path_from_root; falls back to path from repo root when absent. **LEXA security:** parameterized SQL only for all inserts/updates; path validation (file must be inside Lupopedia root, no '..' escape); no eval/exec/shell; header values stored as plain text; safe error logging (no sensitive info). Does not infer channel_id — stores path only; application resolves channel via lupo_contents lookup later. No schema or database structure changes; no triggers or automation.

### FLP — Federated Likeness Protocol (documentation only)

- **docs/doctrine/FLIP/:** Contains FLP doctrine (councils as channels, emotional geometry, heterodox reviewers, emotional aggregation, escrow/fund layer, Lupopedia council seat, doctrine boundaries). All FLP_*.md files have FLIP Headers; version 4.0.13. No schema, SQL, triggers, or DB automation; documentation only.

### ARA / version normalization

- All doctrine and header references in this cycle aligned to **4.0.13**. Canonical naming **FLIP Headers** (aliases Wolfie, CROP, FLIPPING) used in new and updated docs. No ARA-suggested PHP classes (e.g. FlipHeaderParser, AtomResolver, InferenceEngine) implemented in this release.

### FLIPPING File (LEXA/LILITH) and actor-chain (4.0.13 finalization)

- **docs/doctrine/FLIP/FLIPPING_FILE_LEXA_LILITH.md:** Single authoritative doc for LEXA and LILITH. Added FLIP Header dialog entry (CURSOR: incorporated LILITH critique). Added LILITH-required sections: **Actors on a channel** (SQL using lupo_actor_channels + lupo_actors + lupo_actor_channel_roles); **dialog_notes** purpose and how to parse; **FLP soft-reference example** (council → members via lupo_edges / lupo_actor_channels); **Sample reconstructed FLIP header** for the file; **Path validation pseudocode** (validate_path_inside_root, validate_and_sanitize_path_from_root). Part 2.5 documents database tables and navigation from content to channel_id and dialog (lupo_contents, lupo_edges, lupo_channels, lupo_dialog_threads, lupo_dialog_messages, lupo_dialog_channels).
- **lupo-includes/classes/ContentChannelActorResolver.php:** New PHP 5.3–compatible class. `getActorsForFilePath($filePath)` validates/sanitizes path (inside root, no `..`), resolves content_id from lupo_contents, channel_id from lupo_edges (HAS_CONTENT), then returns actors from lupo_actor_channels JOIN lupo_actors (optional LEFT JOIN lupo_actor_channel_roles for role_key). Returns array of actor_id, actor_name, type, role, status, joined_at. Uses PDO_DB and table prefix only; no FKs, no triggers, no schema inference.
- **scripts/import_os.py:** LEXA path validation clarified: only sanitized paths may be stored in DB as file_path_from_root; comments state that only the return value of validate_and_sanitize_path_from_root (or computed path passed through it) may be written to lupo_contents. No schema or TOON changes.
- **FLIP/FLP schema check:** Re-scanned lupo_contents TOON, install_new_lupopedia.sql, and migrations. No additional FLIP/FLP fields required for full header reconstruction, actor-chain navigation, path validation, or channel resolution. Existing columns (file_path_from_root, file_last_modified_system_version, file_last_modified_utc) and existing tables (lupo_edges, lupo_actor_channels, lupo_actors, lupo_actor_channel_roles) suffice. No new migration or installer SQL in this step.

### Seed: one dialog message per agent on channel 42

- **database/migrations/seed_lupopedia.sql:** Added one seeded dialog message for every kernel AI/agent on channel 42 (Lupopedia Development). Agent list is taken from existing seed: lupo_actor_channels rows for channel_id = 42 (actor_ids 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 22, 23, 209, 1212). Each agent has exactly one message in the active thread (dialog_thread_id = 1), e.g. "hello from &lt;agent_name&gt;" with agent_name from lupo_actors seed. Ensures each agent has a presence in the initial development thread. Uses explicit dialog_message_id values 3–26, message_type = 'system', ON DUPLICATE KEY UPDATE for idempotency. lupo_dialog_channels.message_count for channel 42 updated to 26. No schema changes.

### FLIP/FLP implementation per Lilith review (4.0.13)

- **docs/doctrine/FLIP/FLIPPING_FILE_LEXA_LILITH.md:** Implemented Ara Grok/Lilith review. Added **Part 2.12 — Optional dialog block in FLIP Headers**: dialog block is optional and purely informational; not for inference; may overlap with lupo_contents.dialog_notes; app parses safely (no eval). Updated header (file.last_modified_utc, dialog speaker ARA_GROK, target @cursor). Quick Reference extended with optional dialog block, loader/generator behavior.
- **docs/channels/agents/WOLFIE_HEADER_SPECIFICATION.md:** FLIP note under Dialog Block (section 4): dialog block is optional and non-authoritative for FLIP inference; link to FLIPPING_FILE_LEXA_LILITH.md Part 2.12.
- **scripts/import_os.py:** Optional parsing of `dialog` block from FLIP Header; when present, serialized safely and stored in lupo_contents.dialog_notes on insert (parameterized SQL; no eval). Existing FLIP columns and path validation unchanged.
- **tools/generate_flip_header.py:** SELECT extended to include dialog_notes; when present, generator outputs optional `dialog:`-style block in reconstructed header. Parameterized SQL only.
- **database/migrations/seed_lupopedia.sql:** Optional mood_rgb on seeded dialog messages: system messages (1–2) use 'FF0000'; agent messages (3–26) use NULL. Matches lupo_dialog_messages TOON (mood_rgb char(6)).
- No new migrations; install and existing 20260217 FLIP migrations remain canonical. TOONs are read-only source of truth; no TOON edits.

### Full FLIP header reconstruction and channel 42 seed (4.0.13)

- **Schema verification:** Re-scanned TOONs (lupo_contents, lupo_edges, lupo_actor_channels, lupo_actors, lupo_dialog_messages, lupo_dialog_threads). lupo_contents in install_new_lupopedia.sql contains all fields required for full FLIP header reconstruction: file_path_from_root (varchar(500)), file_last_modified_system_version (varchar(20)), file_last_modified_utc (bigint), dialog_notes (text). No new FLIP column migrations required.
- **Path → content → channel → actors:** Installer and seed guarantee the lookup chain: (1) file_path_from_root → content_id via lupo_contents; (2) content_id → channel_id via lupo_edges (edge_type = 'HAS_CONTENT'); (3) channel_id → actors via lupo_actor_channels + lupo_actors. Added index lupo_contents_idx_file_path_from_root on lupo_contents(file_path_from_root) in install_new_lupopedia.sql for efficient path lookup. One-time migration database/migrations/20260217_add_contents_file_path_from_root_index.sql adds the same index for existing databases.
- **Channel 42 seed:** All actors assigned to channel 42 (actor_ids 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 22, 23, 209, 1212) are active, present in lupo_actor_channels (status = 'A'), have admin role in lupo_actor_channel_roles, and have exactly one dialog message in dialog_thread_id = 1 (message_ids 3–26). System messages 1–2 from actor 0. lupo_dialog_channels.message_count = 26. Seed uses ON DUPLICATE KEY UPDATE for idempotency.
- **Installer:** install_new_lupopedia.sql includes all FLIP fields in lupo_contents and the file_path_from_root index. Seed_lupopedia.sql provides channel 42, kernel actors, actor-channel and role rows, dialog thread 1, and one message per actor; fresh install + seed guarantees full FLIP reconstruction and path → content → channel → actors behavior.

**4.0.x doctrine:** This version is part of the iterative development cycle for the **Crafty Syntax 3.7.5 → Lupopedia 4.0.x** upgrade path. No Lupopedia → Lupopedia upgrades exist for this version.

**Files modified or added (4.0.13):** `config/global_atoms.yaml`, `lupo-includes/version.php`, `lupo-includes/functions/load_atoms.php`, `install.php`, `database/migrations/seed_lupopedia.sql`, `docs/doctrine/VERSIONING_DOCTRINE.md`, `README.md`, `docs/channels/agents/WOLFIE_HEADER_SPECIFICATION.md`, `scripts/import_os.py`, `CHANGELOG.md`, `docs/doctrine/FLIP/FLIPPING_FILE_LEXA_LILITH.md`; **.cursor/rules/flip-doctrine.mdc** (new); **lupo-includes/classes/ContentChannelActorResolver.php** (new); **docs/doctrine/FLIP/** (new: FLIP_DOCTRINE.md, FLP_OVERVIEW.md, FLP_EMOTIONAL_GEOMETRY.md, FLP_COUNCILS_AS_CHANNELS.md, FLP_HETERODOX_REVIEWERS.md, FLP_EMOTIONAL_AGGREGATION.md, FLP_ESCROW_AND_FUND_LAYER.md, FLP_LUPOPEDIA_COUNCIL_SEAT.md, FLP_DOCTRINE_BOUNDARIES.md, README.md, NOTE_HEADER_VERSION_AND_MERGE.md); **docs/INITIALIZATION_PROMPT_4_0_13.md** (new). Directory docs/doctrine/flp/ renamed to docs/doctrine/FLIP/. **Migrations and installer (4.0.13):** database/migrations/20260217_add_flip_header_fields.sql, database/migrations/20260217_add_missing_flip_fields.sql, database/migrations/20260217_add_contents_file_path_from_root_index.sql; database/migrations/install_new_lupopedia.sql (FLIP columns in lupo_contents + index lupo_contents_idx_file_path_from_root); tools/generate_flip_header.py.

---

## Lupopedia 4.0.12 — Version bump, import actor ID range, progress blog, admin setup,  README and HISTORY - 2026-02-17

Lupopedia 4.0.12 is part of the iterative development cycle for the **Crafty Syntax 3.7.5 → Lupopedia 4.0.x** upgrade path.  
There are **no Lupopedia → Lupopedia upgrades** in the 4.0.x series.

### Version bump 4.0.11 → 4.0.12

- **config/global_atoms.yaml:** `file.last_modified_system_version`, `version`, `versions.lupopedia`, `GLOBAL_CURRENT_LUPOPEDIA_VERSION` set to 4.0.12; `last_updated` set to 20260217120000.
- **lupo-includes/version.php:** Docblock `@version` and fallback literals (when atom loader unavailable) updated to 4.0.12; `LUPOPEDIA_VERSION_DATE` set to 20260217120000.

### Import: actor ID range (human users ≥ 10000)

- **Actor ID doctrine:** actor_id 0–9999 = system/AI agents only; human actors must use actor_id ≥ 10000. Import now remaps Crafty `user_id` into the human range so imported users never collide with seed agents.
- **database/migrations/import_from_old_crafty_syntax.sql:** Both **lupo_auth_users** INSERTs use `(10000 + u.user_id) AS auth_user_id`; NOT EXISTS checks updated to `(10000 + u.user_id)`. **lupo_actors** INSERT unchanged (uses `au.auth_user_id`, now ≥ 10000). Comments added for ACTOR ID RANGE and remap.
- **Same offset applied everywhere Crafty user/operator IDs are written:** `lupo_crafty_syntax_auto_invite.operator_user_id` = (10000 + a.user_id); `lupo_crafty_syntax_layer_invites.user_id` = (10000 + `user`); `lupo_actor_departments.actor_id` (from livehelp_operator_departments) = (10000 + user_id); `lupo_actor_reply_templates.actor_id` (from livehelp_quick) = (10000 + `user`); `lupo_audit_log.entity_id` (from livehelp_operator_history.opid) = (10000 + opid).
- **docs/doctrine/migrations/livehelp_users_migration.md:** Documented that on import, auth_user_id = 10000 + livehelp_users.user_id and imported human actor_id ≥ 10000.

### Progress blog (reports_for_boss → progress_blog)

- **progress_blog/pre-4_0_1.md:** Created; content moved from former `reports_for_boss/20260223.md` (Lupopedia Weekly Impact Report, Jan 22–24, 2026) as the pre–4.0.1 progress report.
- **progress_blog/pre-4_0_1_to_4_0_11.md:** Created; summarizes CHANGELOG changes for versions 4.0.1 through 4.0.11.
- **reports_for_boss:** Folder and contents removed.

### README: narrative lead and structure

- **README.md:** Lead updated to “Crafty Syntax Reborn — Now Inside a Semantic Operating System” with taglines (Same product → new universe; Everything familiar → everything extended). New sections: **Why Lupopedia Exists** (Crafty = real-time help, Lupopedia = semantic layer; visitors/pages/referrers/sessions → content atoms, tabs, collections, meaning edges); **What 4.0.x Focuses On** (rebuild Crafty inside Lupopedia, add Semantic OS layer); **Crafty Syntax + Semantic OS = Lupopedia** (Crafty provides / Lupopedia adds; heart/brain); **The Five Pillars (Simplified)**; **Upgrade Path**; **What Lupopedia Is** / **What Lupopedia Is Not**. Reference (doctrine/database, legacy/craftysyntax, migrations) and Origins (link to HISTORY.md) retained. Duplicate “What Lupopedia 4.0.x Is” and “What Lupopedia 4.0.x Is NOT” removed. **In One Sentence** updated to Crafty Syntax reborn inside a Semantic OS. “Not a replacement for your website” and “semantic reference layer” preserved.

### HISTORY.md (origins and Crafty lineage)

- **docs/channels/appendix/HISTORY.md:** Created and expanded. Full narrative: **Origins** (WOLFIE spiritual research engine → 222 tables → semantic OS → Lupopedia); **The Second Origin: Crafty Syntax Returns** (2002–2014 Crafty Syntax Live Help, semantic behavioral data, “missing half”); **The Evolution Path: Crafty Syntax → Lupopedia** (4.0.x as next evolutionary stage, feature list, legacy/craftysyntax reference-only); **The Modern System** (both lineages, unified successor). Link to Founder’s Note retained.

### database/migrations organization (wizard vs one-time migrations)

- **Canonical set in database/migrations/:** Only wizard- and revert-related SQL remain: `install_new_lupopedia.sql`, `seed_lupopedia.sql`, `import_from_old_crafty_syntax.sql`, `drop_old_crafty_syntax_tables.sql`, `future_features_lupopedia.sql`, `old_crafty_syntax_3_7_5_start.sql` (Crafty 3.7.5 snapshot for dev/testing revert). **database/migrations/README.md** updated with a canonical-set table and baseline filename `old_crafty_syntax_3_7_5_start.sql`.
- **Moved to database/migrations_legacy/:** One-time and Lupopedia→Lupopedia migration files (not run by wizard): `migration_unified_registry_*`, `migration_operator_to_actor_channel_roles.sql`, `migration_drop_lupo_channel_roles.sql`, `migration_system_department_and_admin_roles.sql`, `grant_captain_admin_channel_role.sql`, `registry_seed_raw_test.sql`, `dev_20260212_sessions_and_unified_analytics_paths.sql`, `dev_20260204_fix_schema_alignment_summary.txt`, `reserved_word_audit_report.txt`, `transform_out.txt`, `transform_result.sql`.
- **Docs and rules:** References to moved migrations updated to `database/migrations_legacy/` in `docs/doctrine/VERSIONING_DOCTRINE.md`, `docs/doctrine/migrations/operator_to_roles_migration.md`, `docs/doctrine/database/actor_channel_roles.md`, `docs/doctrine/DEVELOPMENT_WORKFLOW_DOCTRINE.md` (baseline name → `old_crafty_syntax_3_7_5_start.sql`), `docs/audits/OPERATOR_TO_ROLE_BASED_SWEEP_REPORT.md`, `docs/audits/DYNAMIC_TABLE_PREFIX_AUDIT.md`, `docs/audits/FUTURE_FEATURES_AND_REQUIRED_TABLES_ALIGNMENT_SUMMARY.md`, `docs/audits/VERSIONING_DOCTRINE_ALIGNMENT_SUMMARY.md`. **.cursor/rules/required-tables-future-features-doctrine.mdc:** canonical SQL set extended to include `old_crafty_syntax_3_7_5_start.sql` and clarified as wizard + revert-to-Crafty baseline only.

### Wizard version display (4.0.12 on install step)

- **install.php:** Fallback when `LUPOPEDIA_VERSION` is not defined (line ~40) updated from `'4.0.10'` to `'4.0.12'`. Ensures the wizard shows the current version when run without lupopedia-config.php (no atom loader).
- **lupo-includes/functions/load_atoms.php:** Fallback in `get_lupopedia_version()` (line ~46) updated from `'4.0.10'` to `'4.0.12'`. Used when the atom loader is not set (e.g. pre-config wizard).
- **docs/doctrine/VERSIONING_DOCTRINE.md:** New **§8 Patch bump: locations to update** — checklist of four places to update on each patch (global_atoms.yaml, version.php, install.php, load_atoms.php). Canonical version and summary table set to 4.0.12.

### Admin menu (legacy Crafty parity)

- **admin.php:** Full admin navigation rewritten to match **legacy/craftysyntax/navigation.php**. Menu is grouped into sections: **Overview** (Dashboard), **General** (Documentation, Master Settings, Help, Support, Security Registration, Lupopedia Registration, Member Services, Questions and Answers), **CRM tools** (Leads Database, Email message database, Proactive Leads, Import Leads), **Agents & Channels** (Agents, Channels), **Live Help** (Live, Quick replies, Quick images, Quick URLs, Auto invite, Emotion Icons, Edit Layer Images), **Operators** (Edit your account, Create / Edit / Delete), **Departments** (HTML code for departments, Create / Edit / Delete departments), **Data** (Visits, Messages, Referrers, Visits by period, Paths, Keywords, Users), **Modules** (Questions & Answers), **Extras** (View Directory), **Information** (Donations, Updates, Changelog). Each item links to `admin.php?section=<slug>`. **Users** keeps existing AdminUsersHandler; all other sections show a placeholder (“This section is a placeholder…”).
- **lupo-includes/themes/default/layouts/admin_layout.php:** Sidebar renders from `$admin_menu_sections` when set (grouped by section with `<h2>` per group). Fallback to flat `$admin_menu_items` when sections not set. PHP 5.3-safe arrays; `$admin_menu_sections` defaulted when unset; `.admin-placeholder-text` style added.

### Crafty admin rights → Lupopedia global admin (wizard migration)

- **Legacy:** In Crafty Syntax (legacy/craftysyntax/operators.php), `livehelp_users.isadmin` = 'Y' means Admin; 'N' = Normal; 'R' = Restricted; 'L' = Live-Help-ONLY. Lupopedia has no `isadmin` column; admin is determined by the 3-level role system (channel 1 captain, department 0 administrator, and/or owner on admin module).
- **app/auth/AuthRoleResolver.php:** **getAuthUserIdFromActorId** now accepts `actor_source_type = 'user'` or `actor_source_type = 'lupo_auth_users'` so imported Crafty operators (stored as lupo_auth_users) resolve correctly for the permissions fallback (owner on admin module).
- **install_wizard_classes.php — createOperatorChannels:** Crafty admins (livehelp_users.isadmin = 'Y') are resolved via a single JOIN (livehelp_users → lupo_auth_users → lupo_actors) so canonical **actor_id** is used for all role inserts. For each such admin the wizard ensures: (1) captain on channel 1 (Administration), (2) lupo_actor_departments (department_id = 0, System Administrator), (3) lupo_department_roles (department_id = 0, role_key = 'administrator'), (4) **lupo_permissions** owner on the **admin** module when that module exists (so they have “admin * access to everything” and AuthRoleResolver’s permissions fallback grants global admin). Non-admin operators keep normal roles only (personal channel + captain).
- **database/migrations/seed_lupopedia.sql:** New **admin** module (module_id = 9, module_key = 'admin', module_name = 'Admin', paths /admin.php) and matching **lupo_unified_registry** row (unified_registry_id = 88, entity_type = 'module', entity_index = 9). Used by the wizard to grant owner permission to Crafty admins and by AuthRoleResolver for global admin checks.

**Files modified (4.0.12):** `config/global_atoms.yaml`, `lupo-includes/version.php`, `lupo-includes/functions/load_atoms.php`, `install.php`, `admin.php`, `app/auth/AuthRoleResolver.php`, `install_wizard_classes.php`, `database/migrations/seed_lupopedia.sql`, `database/migrations/import_from_old_crafty_syntax.sql`, `database/migrations/README.md`, `docs/doctrine/migrations/livehelp_users_migration.md`, `docs/doctrine/VERSIONING_DOCTRINE.md`, `docs/doctrine/migrations/operator_to_roles_migration.md`, `docs/doctrine/database/actor_channel_roles.md`, `docs/doctrine/DEVELOPMENT_WORKFLOW_DOCTRINE.md`, `docs/audits/OPERATOR_TO_ROLE_BASED_SWEEP_REPORT.md`, `docs/audits/DYNAMIC_TABLE_PREFIX_AUDIT.md`, `docs/audits/FUTURE_FEATURES_AND_REQUIRED_TABLES_ALIGNMENT_SUMMARY.md`, `docs/audits/VERSIONING_DOCTRINE_ALIGNMENT_SUMMARY.md`, `.cursor/rules/required-tables-future-features-doctrine.mdc`, `README.md`, `docs/channels/appendix/HISTORY.md` (new), `progress_blog/pre-4_0_1.md` (new), `progress_blog/pre-4_0_1_to_4_0_11.md` (new), `lupo-includes/themes/default/layouts/admin_layout.php`. **Removed:** `reports_for_boss/20260223.md`, `reports_for_boss/` folder. **Moved to database/migrations_legacy/:** 14 one-time migration and report files (see above).

---

## Lupopedia 4.0.11 — Version bump, installer import logging, Crafty config detection and removal - 2026-02-17

Lupopedia 4.0.11 is part of the iterative development cycle for the **Crafty Syntax 3.7.5 → Lupopedia 4.0.x** upgrade path.  
There are **no Lupopedia → Lupopedia upgrades** in the 4.0.x series.

### Version bump 4.0.10 → 4.0.11

- **config/global_atoms.yaml:** `file.last_modified_system_version`, `version`, `versions.lupopedia`, `GLOBAL_CURRENT_LUPOPEDIA_VERSION` set to 4.0.11; `last_updated` set to 20250216120000.
- **lupo-includes/version.php:** Docblock `@version` and fallback literals (when atom loader unavailable) updated to 4.0.11; `LUPOPEDIA_VERSION_DATE` set to 20250216120000.

### Install wizard: import run logging and failure handling

- **install.php:** Before running `import_from_old_crafty_syntax.sql`, the run log now records an explicit line that the import converts `livehelp_*` tables to `utf8mb4_unicode_ci` and migrates data. The return value of `runSqlFile()` is checked; if false, the log records an error that import reported failures and legacy `livehelp_*` tables may not have been converted. Comment added that livehelp_ detection is done at the credentials step using the connection from the submitted form (no earlier connection; no config file required).

### Crafty config.php: upgrade detection and single-config outcome

- **install_wizard_classes.php:** New **InstallWizardCredentials::craftyConfigExists()** returns true if a Crafty-style `config.php` exists in any standard location (project root, parent dir, or document root) and the file contains `$server` or `$database`. Used so that the presence of Crafty config alone forces upgrade path.
- **install.php:** Install type is set to **upgrade** when either `livehelp_*` tables are detected **or** `craftyConfigExists()` is true — so "config.php exists" means for sure an upgrade from Crafty Syntax.
- **install_wizard_classes.php:** Comment in **writeConfig()** updated: Crafty `config.php` is only used during upgrade; after successful write of **lupopedia-config.php** it is removed so only one config remains and users are not confused by two configs. Removal logic unchanged (project-root `config.php` deleted after verifying `lupopedia-config.php` was written and contains `LUPOPEDIA_CONFIG_LOADED`).
- **install.php:** Completion screen text updated from "Crafty config.php has been backed up or removed" to "Crafty config.php has been removed so only one config remains."

### Doctrine database table docs and migration alignment

- **docs/doctrine/database/:** New folder with **README.md** (index and 3-level permission model summary) and per-table doctrine for migration targets: **auth_users.md**, **actors.md**, **actor_departments.md**, **actor_channel_roles.md**, **departments.md**, **channels.md**, **sessions.md**, **dialog_threads.md**, **dialog_messages.md**, **crm_leads.md**, **crm_lead_messages.md**, **audit_log.md**, **crafty_syntax_auto_invite.md**, **actor_reply_templates.md**, **federation_nodes.md**. Each doc describes the table’s use, schema source (TOONs), and mapping from legacy Crafty tables.
- **docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md:** Identity/operators and channel-interface rows updated to use **lupo_actor_channel_roles** and the **3-level role system** (channel → department → system); all references to **lupo_operators** removed. New “Operator-to-roles” section references operator_to_roles_migration.md.
- **docs/doctrine/migrations/livehelp_users_migration.md:** Related tables and replacement list updated: permissions use 3-level role system (lupo_actor_channel_roles, lupo_department_roles), not lupo_operators.
- **docs/doctrine/migrations/operator_to_roles_migration.md:** New migration note describing removal of lupo_operators and lupo_operators_*; 3-level role system (channel roles, department roles, system); resolution order; import and wizard behavior; references to sweep report and actor_channel_roles.md.
- **docs/doctrine/MigrationAtlas.md:** livehelp_identity_daily and livehelp_identity_monthly entries updated to DROPPED (no import); anonymous in sessions only.

### Anonymous users: sessions only, no lupo_actors

- **database/migrations/import_from_old_crafty_syntax.sql:** Removed the **INSERT into lupo_actors** from livehelp_identity_monthly (anonymous actors). Replaced with a comment: anonymous users are not inserted into lupo_actors; only authenticated users, agents, and system users have actor rows; anonymous visitors exist in lupo_sessions only. livehelp_identity_monthly / livehelp_identity_daily are converted and deprecated only; no import into actors.
- **docs/doctrine/migrations/livehelp_identity_migration.md:** Status set to DROPPED (no import into lupo_actors). Replacement: anonymous visitors in lupo_sessions only; no anonymous rows in lupo_actors. Migration behavior and mapping summary updated: no import from identity_monthly/daily; no anonymous actor range.
- **docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md:** livehelp_identity_monthly → DROPPED (no import); anonymous users in lupo_sessions only; no anonymous actor rows or range.
- **docs/doctrine/database/actors.md:** Purpose and use updated: lupo_actors is for authenticated humans, agents, and system users only; anonymous users do not have rows (they exist in lupo_sessions only); no dedicated ID range for anonymous. Mapping: livehelp_identity_monthly not imported into lupo_actors.
- **docs/doctrine/database/README.md:** lupo_actors row updated to “livehelp_users (operators only); anonymous users are not in actors (sessions only)”.
- **docs/doctrine/database/sessions.md:** Purpose clarified: anonymous users exist only in sessions (no lupo_actors row); authenticated users have both session and actor.

**Files modified (4.0.11):** `config/global_atoms.yaml`, `lupo-includes/version.php`, `install.php`, `install_wizard_classes.php`, `database/migrations/import_from_old_crafty_syntax.sql`, `CHANGELOG.md`, `docs/doctrine/database/README.md`, `docs/doctrine/database/auth_users.md`, `docs/doctrine/database/actors.md`, `docs/doctrine/database/actor_departments.md`, `docs/doctrine/database/actor_channel_roles.md`, `docs/doctrine/database/departments.md`, `docs/doctrine/database/channels.md`, `docs/doctrine/database/sessions.md`, `docs/doctrine/database/dialog_threads.md`, `docs/doctrine/database/dialog_messages.md`, `docs/doctrine/database/crm_leads.md`, `docs/doctrine/database/crm_lead_messages.md`, `docs/doctrine/database/audit_log.md`, `docs/doctrine/database/crafty_syntax_auto_invite.md`, `docs/doctrine/database/actor_reply_templates.md`, `docs/doctrine/database/federation_nodes.md`, `docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md`, `docs/doctrine/migrations/livehelp_users_migration.md`, `docs/doctrine/migrations/operator_to_roles_migration.md` (new), `docs/doctrine/migrations/livehelp_identity_migration.md`, `docs/doctrine/MigrationAtlas.md`.

---

## Lupopedia 4.0.10 — Version bump, actor_aliases table - 2026-02-16

Lupopedia 4.0.10 is part of the iterative development cycle for the **Crafty Syntax 3.7.5 → Lupopedia 4.0.x** upgrade path.  
There are **no Lupopedia → Lupopedia upgrades** in the 4.0.x series.

### Version bump 4.0.9 → 4.0.10

- **config/global_atoms.yaml:** `file.last_modified_system_version`, `version`, `versions.lupopedia`, `GLOBAL_CURRENT_LUPOPEDIA_VERSION` set to 4.0.10; `last_updated` set to 20260216000000.
- **lupo-includes/version.php:** Docblock `@version` and fallback literals (when atom loader unavailable) updated to 4.0.10; `LUPOPEDIA_VERSION_DATE` set to 20260216000000.

### Actor aliases table (installer only)

- **database/migrations/install_new_lupopedia.sql:** New table **lupo_actor_aliases** added with `alias_id` (BIGINT AUTO_INCREMENT), `actor_id`, `alias_name` (VARCHAR(255)), `created_ymdhis`, `updated_ymdhis`. Aliases are stored in a dedicated table; unified_registry remains a reserved-ID ledger only and does not store alias relationships. No seed, importer, migration, or TOON changes in this patch.

### Installer version display (4.0.10 fallbacks)

- **install.php:** Fallback when `LUPOPEDIA_VERSION` is not defined changed from `'4.0.9'` to `'4.0.10'` so the wizard UI shows 4.0.10 on first step.
- **lupo-includes/functions/load_atoms.php:** `get_lupopedia_version()` fallback when atom loader unavailable changed from `'4.0.9'` to `'4.0.10'`.

### Reserved channel 5100 → 51 (install, seed, docs, doctrine)

- **Rationale:** Channel 51 avoids a large gap between reserved system channels and the next `MAX(channel_id)`; reserved list is now (0, 1, 42, 51).
- **install.php:** All reserved-channel references (0, 1, 42, 5100) updated to (0, 1, 42, 51) in comments and UI strings.
- **install_wizard_classes.php:** Reserved channels array key and `ensureReservedChannels` / `createReservedSystemChannels` use channel id 51 (ai-dev) instead of 5100; `$required` and `WHERE channel_id IN (...)` updated to 51.
- **database/migrations/seed_lupopedia.sql:** Lupopedia channel in unified_registry: `entity_index` and `entity_key` 5100 → 51; `channel_number` in metadata 5100 → 51 for the Lupopedia row (id 58) and for entity 1023 (lupopedia).
- **Docs and doctrine:** CHANGELOG, audits (OPERATOR_TO_ROLE_BASED_SWEEP_REPORT, INSTALL_PHP_WIZARD_DOCTRINE_AUDIT), PHP_COMPATIBILITY_AND_MINIMAL_HOSTING_DOCTRINE, INDEX, channels.md, channels/filesystem_padding_layer.md, channels/0042/DOCTRINE.md, README, channel_registry, channel_summary, channels/overview/versioning/CHANGELOG, and **channels/registry.json**, **DIRECTORY_TREE.md** updated so reserved channel 51 (and references to 5100-series where appropriate) are consistent.

### Reserved channel creation: error logging and verification

- **install_wizard_classes.php:** When reserved channel INSERT fails, the log now includes the actual PDO exception message (e.g. `Reserved channel 51 (ai-dev) failed: ...`) instead of only "see server log." After `ensureReservedChannels` calls `createReservedSystemChannels`, the wizard re-checks which of (0, 1, 42, 51) exist; if any are still missing it logs an error; otherwise it logs "Reserved channels created: ...".

### Unified unregistry seed (install wizard)

- **install_wizard_classes.php:** New class **InstallWizardUnregistry** with **seedUnregistryFromGaps($pdo, &$log, $maxCap = 500)**. Populates **lupo_unified_unregistry** with free IDs (gaps) in the range [0, min(MAX(id), maxCap)] for **channel** and **actor** entity types so allocation (findpuka) can reuse them FIFO. Uses cap 500 so the table does not grow huge when MAX(channel_id) or MAX(actor_id) is large; logs when range is capped.
- **install.php:** At end of run step (new install and upgrade), calls **InstallWizardUnregistry::seedUnregistryFromGaps($pdo, $log, InstallWizardUnregistry::DEFAULT_MAX_CAP)** so the free list is seeded after install/seed/reserved channels (and after import/operator channels on upgrade).

### ANUBIS doctrine: unified_unregistry lifecycle

- **docs/channels/doctrine/ANIBUS_DOCTRINE.md:** New **section 15 — Unified Unregistry Awareness (Required for ANUBIS)**. When ANUBIS performs a hard delete, it must decide whether the deleted ID is safe to return to the unified_unregistry free list: do not add if the row has an active redirect (anubis_redirects) or is an unresolved orphan; only fully resolved, redirect-free IDs may be inserted into unified_unregistry. ANUBIS must never modify unified_registry; it interacts only with unified_unregistry. Rules for dynamic table prefix and no live-DB schema inference are documented. Frontmatter `in_this_file_we_have` updated.
- **docs/channels/doctrine/NO_FOREIGN_KEYS_DOCTRINE.md:** New **subsection 4.4 — Unified Unregistry (Hard-Delete Lifecycle)**. Summarizes that ANUBIS must follow unified_unregistry doctrine on hard deletes and references ANIBUS_DOCTRINE.md section 15 for full rules.

**Files modified (4.0.10):** `config/global_atoms.yaml`, `lupo-includes/version.php`, `lupo-includes/functions/load_atoms.php`, `install.php`, `install_wizard_classes.php`, `database/migrations/install_new_lupopedia.sql`, `database/migrations/seed_lupopedia.sql`, `CHANGELOG.md`, `channels/registry.json`, `channel_summary.md`, `DIRECTORY_TREE.md`, `docs/audits/OPERATOR_TO_ROLE_BASED_SWEEP_REPORT.md`, `docs/audits/INSTALL_PHP_WIZARD_DOCTRINE_AUDIT.md`, `docs/doctrine/PHP_COMPATIBILITY_AND_MINIMAL_HOSTING_DOCTRINE.md`, `docs/doctrine/INDEX.md`, `docs/doctrine/channels.md`, `docs/doctrine/channels/filesystem_padding_layer.md`, `docs/channels/0042/DOCTRINE.md`, `docs/README.md`, `docs/channels/overview/channel_registry.md`, `docs/channels/overview/versioning/CHANGELOG.md`, `docs/channels/doctrine/ANIBUS_DOCTRINE.md`, `docs/channels/doctrine/NO_FOREIGN_KEYS_DOCTRINE.md`.

---

## Lupopedia 4.0.9 — Version bump, installer fixes, seed duplicate removal - 2026-02-15

Lupopedia 4.0.9 is part of the iterative development cycle for the **Crafty Syntax 3.7.5 → Lupopedia 4.0.x** upgrade path.  
There are **no Lupopedia → Lupopedia upgrades** in the 4.0.x series.

### Version bump 4.0.8 → 4.0.9

- **config/global_atoms.yaml:** `file.last_modified_system_version`, `version`, `versions.lupopedia`, `GLOBAL_CURRENT_LUPOPEDIA_VERSION` set to 4.0.9; `last_updated` set to 20260215000000.
- **docs/doctrine/VERSIONING_DOCTRINE.md:** Canonical current version and patch pattern updated to 4.0.9.
- **lupo-includes/version.php:** Docblock `@version` and fallback constants updated to 4.0.9; `LUPOPEDIA_VERSION_DATE` set to 20260215000000.
- **lupo-includes/functions/load_atoms.php:** Fallback in `get_lupopedia_version()` changed from `'3.0.0'` to `'4.0.9'` so the wizard shows 4.0.9 when the atom loader is not set.

### Install wizard version display

- **install.php:** Loads `lupo-includes/version.php` and sets `$lupo_wizard_version` from `LUPOPEDIA_VERSION`. All UI strings that previously showed a hardcoded "4.0.6" (title, h1, welcome text, pre-flight error) now use `$lupo_wizard_version`.

### Import from Crafty — SQL split and troubleshooting

- **install_wizard_classes.php:** Added `InstallWizardSqlRunner::splitSqlStatements($sql)` so the import file is split on `;` only when not inside single-quoted strings. This fixes the broken statement caused by the semicolon inside the long `COMMENT = '...;...'` on line 1017 of **import_from_old_crafty_syntax.sql** (livehelp_smilies), which was splitting one statement into two and causing later imports (e.g. lupo_audit_log) to misbehave. On SQL failure, the runner now logs statement index and a short preview so the failing statement can be located in the import file.
- **docs/doctrine/IMPORT_FROM_CRAFTY_TROUBLESHOOTING.md:** New doc covering use of the log, prerequisites (34 livehelp_* tables, MySQL 5.7.8+ / MariaDB 10.2.3+ for JSON_OBJECT), and that the runner respects semicolons inside strings.

### Optional drop of legacy tables

- **install.php:** Checkbox on credentials step: "Drop deprecated Crafty (livehelp_*) tables after import" (default **unchecked**). Value stored in `$_SESSION['lupo_drop_livehelp_tables']`; cleared on "Start over." Upgrade run step runs **drop_old_crafty_syntax_tables.sql** and dropLivehelpTables only when that option is checked; otherwise logs "Skipped: drop deprecated livehelp_* tables (option unchecked at credentials)." Confirm step text updated so the drop is listed only when the option is checked.

### Doctrine tables (doctrine_blocks removed, transition note)

- **install_new_lupopedia.sql:** Entire `lupo_doctrine_blocks` CREATE and indexes **removed** (table unused).
- **docs/doctrine/DOCTRINE_TABLES_TRANSITION_NOTE.md:** New doc stating (1) doctrine storage should use **{prefix}contents** on **channel 42**; (2) doctrine_blocks removed from install; (3) doctrine_refinements and doctrine_evolution_audit remain in install but should be transitioned to contents on channel 42 when CIP is refactored.

### Seed duplicate 0-entry records removed

- **database/migrations/seed_lupopedia.sql:** Removed the **duplicate block** of `lupo_unified_registry` INSERTs (the second "TOON-defined canonical rows" section, 253 lines). The seed had been inserting the same unified_registry rows twice (ids 1–58, 59–87, 9000001–9001212), causing duplicate key and duplicate 0-entry errors during install. The first occurrence (Unified registry + actors + PK=0 rows) is retained; the duplicate section was deleted so the seed flows directly to "Actor/agent doctrine" (ALTER TABLE lupo_actors AUTO_INCREMENT = 10000) and Collection 0.

### Unified registry: entity_index, drop unused columns, PHP and seed alignment

- **lupo_unified_registry (install + migrations):** Column **entity_id** renamed to **entity_index**; **dedicated_index_id** dropped (redundant). Unused columns **code**, **name**, **layer**, **agent_registry_parent_id**, **is_required**, **classification_json**, **agent_class**, **can_use_humor**, **can_use_emotion** removed from install. Canonical identity is **entity_type** + **entity_index**; **entity_table** names the table that owns the reserved index; **entity_key** used for lookup by string (e.g. `UTC_TIMEKEEPER`).
- **lupo_unified_unregistry:** Table added (install + **migration_add_unified_unregistry.sql**) with **entity_type**, **entity_index**, **federation_node_id** (default 1), **created_utc**, **metadata_json** (reference snapshot when index was freed). **migration_unified_registry_entity_index_drop_dedicated_index.sql** renames entity_id → entity_index, drops dedicated_index_id, adds metadata_json to unregistry. **migration_unified_registry_drop_unused_columns.sql** drops the nine unused registry columns on existing DBs.
- **seed_lupopedia.sql:** All unified_registry INSERTs use only the kept columns (no code, name, layer, etc.); VALUES trimmed to match.
- **PHP:** **lupo-includes/class-iris.php** — `loadAgentConfig()` selects **entity_index**, **entity_table**, **entity_key**, **entity_name**, **is_active**, **is_kernel**; fallback prompts use entity_key or entity_name instead of code/name. **lupo-includes/classes/LABSValidator.php** — UTC_TIMEKEEPER check selects **entity_index**, **entity_table**, **is_active** and filters by **entity_key = 'UTC_TIMEKEEPER'** only.
- **Docs:** **docs/channels/doctrine/ACTOR_AGENT_DOCTRINE.md** and **docs/doctrine/UNIFIED_REGISTRY_DOCTRINE.md** updated to describe entity_index, entity_table, entity_key as canonical; removed columns noted.

**Files modified (4.0.9):** `config/global_atoms.yaml`, `docs/doctrine/VERSIONING_DOCTRINE.md`, `lupo-includes/version.php`, `lupo-includes/functions/load_atoms.php`, `install.php`, `install_wizard_classes.php`, `database/migrations/install_new_lupopedia.sql`, `database/migrations/seed_lupopedia.sql`, `database/migrations/migration_add_unified_unregistry.sql`, `database/migrations/migration_unified_registry_entity_index_drop_dedicated_index.sql`, `database/migrations/migration_unified_registry_drop_unused_columns.sql` (new), `lupo-includes/class-iris.php`, `lupo-includes/classes/LABSValidator.php`, `docs/channels/doctrine/ACTOR_AGENT_DOCTRINE.md`, `docs/doctrine/UNIFIED_REGISTRY_DOCTRINE.md`, `docs/doctrine/IMPORT_FROM_CRAFTY_TROUBLESHOOTING.md` (new), `docs/doctrine/DOCTRINE_TABLES_TRANSITION_NOTE.md` (new), `CHANGELOG.md`.

---

## Lupopedia 4.0.8 — Agent Registry Deprecation (Unified Registry Only) - 2026-02-14

Lupopedia 4.0.8 is part of the iterative development cycle for the **Crafty Syntax 3.7.5 → Lupopedia 4.0.x** upgrade path.  
There are **no Lupopedia → Lupopedia upgrades** in the 4.0.x series.

This patch removes all use of the deprecated table **lupo_agent_registry**. All agent-related logic now uses **lupo_unified_registry** exclusively (entity_type = 'agent', entity_id / dedicated_index_id for identity).

### 1. Install SQL — lupo_agent_registry No Longer Created

- **install_new_lupopedia.sql:** Removed the entire `CREATE TABLE lupo_agent_registry` block and `CREATE UNIQUE INDEX lupo_agent_registry_unique_code`.
- Fresh installs no longer create the table. The canonical registry for agents, channels, modules, and actors is **lupo_unified_registry** only.

### 2. Runtime — Agent Config and Lookups Use Unified Registry

- **lupo-includes/class-iris.php:** `loadAgentConfig()` now loads agent metadata from **lupo_unified_registry** with `entity_type = 'agent'` and `entity_id = :agent_id`. Uses table prefix; agent properties still loaded from **lupo_agent_properties** by `actor_id` (same as entity_id for agents). PHP 5.3: `??` replaced with `isset() ? :` in property merge.
- **lupo-includes/classes/LABSValidator.php:** `check_utc_timekeeper_available()` now queries **lupo_unified_registry** with `entity_type = 'agent'` and `(code = 'UTC_TIMEKEEPER' OR entity_key = 'UTC_TIMEKEEPER')` and `is_active = 1`; uses configurable table prefix.

### 3. System Health — Unified Registry Check

- **app/Services/System/SystemHealthService.php:** `checkAgentRegistry()` now checks for the existence of table **lupo_unified_registry** (instead of `lupo_agent_registry`). Messages updated to "Unified registry" / "Unified registry (agents, channels, modules) healthy".
- **app/Http/Controllers/SystemHealthController.php:** Health response key changed from `agent_registry` to `unified_registry`; still calls `checkAgentRegistry()`.

### 4. Verification (No Changes Required)

- **import_from_old_crafty_syntax.sql:** No references to lupo_agent_registry.
- **drop_old_crafty_syntax_tables.sql:** No references to lupo_agent_registry.
- **install_wizard_classes.php**, **install.php:** No references to lupo_agent_registry.
- **seed_lupopedia.sql:** References only the **column** `agent_registry_parent_id` on lupo_unified_registry (schema), not the removed table.

### 5. Migration for Existing Databases

- **database/migrations/migration_unified_registry_agents_columns_and_insert.sql** is unchanged. It remains the one-time migration that copies agent data from **lupo_agent_registry** into **lupo_unified_registry** for existing DBs that still have the old table. New installs never create lupo_agent_registry.

### 6. Unified Registry Identity Doctrine and ID Conflict Validation

- **docs/doctrine/UNIFIED_REGISTRY_DOCTRINE.md:** New doctrine document. Defines: purpose of the unified registry (global IDs for channels, agents, actors); identity doctrine (no auto-generated IDs, no renumbering, explicit IDs only); update doctrine (before inserting new registry rows, check if primary key already exists — if so, fatal error "Unified registry ID conflict: ID {id} already exists."); prohibitions (no schema inference, no edits to install/seed/migration unless instructed, no lupo_agent_registry, PHP 5.3 only).
- **install_wizard_classes.php:** New class `InstallWizardUnifiedRegistryValidator` with `extractUnifiedRegistryIdsFromSql()` and `checkUnifiedRegistryIdConflict()`. Before `InstallWizardSqlRunner::runSqlFile()` executes any SQL file that contains INSERT into unified_registry, it extracts IDs, checks the DB for conflicts, and throws `RuntimeException` with the doctrine message if any ID already exists.
- **install.php:** Run step and upgrade bootstrap wrapped in `try/catch (RuntimeException)` so the unified registry conflict message is shown to the user instead of an uncaught exception.

### 7. Version Bump 4.0.7 → 4.0.8

- **config/global_atoms.yaml:** `file.last_modified_system_version`, `version`, `versions.lupopedia`, `GLOBAL_CURRENT_LUPOPEDIA_VERSION` set to 4.0.8.
- **docs/doctrine/VERSIONING_DOCTRINE.md:** Current version and patch pattern updated to 4.0.8.
- **.cursor/rules/required-tables-future-features-doctrine.mdc**, **.cursorrules:** Example patch references updated to 4.0.8. CHANGELOG and seed SQL were not modified for the bump (changelog already had 4.0.8; seed left as per doctrine).

### 8. Dynamic Table Prefix Audit

- **Doctrine:** All runtime PHP must use `LUPO_TABLE_PREFIX` (or fallback `'lupo_'`) for table names. No literal `lupo_tablename` in PHP. Schema files (install, seed, import, migration, TOONs) remain allowed to use literal `lupo_` as canonical baseline.
- **install.php:** `lupo_auth_users` and `lupo_actors` in SQL replaced with dynamic prefix.
- **install_wizard_classes.php:** All SQL in InstallWizardUnifiedRegistryValidator, InstallWizardDepartments, and InstallWizardChannels now uses `(defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_') . 'tablename'` for departments, channels, actor_channel_roles, actors, auth_users, actor_departments, department_roles, unified_registry.
- **app/Services/System/SystemHealthService.php:** Core tables check and unified registry table check use dynamic prefix.
- **lupo-includes/classes/LABSValidator.php:** Queries for actors and labs_declarations use dynamic prefix.
- **lupo-includes/models/GroundedAgentModel.php:** All table references (agents, agent_owners, actors, actor_actions) use dynamic prefix.
- **lupo-includes/theme/theme-loader.php:** Federation nodes table uses dynamic prefix.
- **app/Services/System/LupopediaMigrationController.php:** Migration log table uses dynamic prefix.
- **scripts/run_labs_handshake.php**, **scripts/migrate_user_mappings.php:** Actors, auth_users, crafty_user_mapping use dynamic prefix.
- **docs/audits/DYNAMIC_TABLE_PREFIX_AUDIT.md:** New audit document listing allowed vs fixed vs remaining PHP files; remaining files (api, TriggerReplacements, AgentAwarenessLayer, CIP, DialogChannelMigration, content/truth modules, other scripts) documented for follow-up.

### 9. Configurable Table Prefix in Install Wizard

- **Purpose:** The install wizard previously hardcoded the table prefix `lupo_` in the generated config and in the SQL it runs. Installations can now use any valid prefix (e.g. `myprefix_`) so that table names match the runtime `LUPO_TABLE_PREFIX` doctrine.
- **install.php — credentials step:** New "Table prefix" form field (default `lupo_`). Validated on submit: only `[a-z0-9_]+`. Stored in `$_SESSION['lupo_table_prefix']`. Before running bootstrap (upgrade) or run step (new install), `LUPO_TABLE_PREFIX` is defined from session so all wizard PHP (departments, channels, actors check) uses the chosen prefix. `runSqlFile()` is called with the session table prefix for install, seed, and import.
- **install_wizard_classes.php — runSqlFile():** New optional 4th parameter `$table_prefix = null`. When set and not `''` and not `'lupo_'`, the SQL file content is passed through `str_replace('lupo_', $table_prefix, $sql)` before execution, so install_new_lupopedia.sql, seed_lupopedia.sql, and import_from_old_crafty_syntax.sql create/import tables with the chosen prefix. Drop and other SQL are unchanged (no substitution).
- **install_wizard_classes.php — writeConfig():** Generated lupopedia-config.php now uses `$options['table_prefix']` (validated `[a-z0-9_]+`) when present; otherwise `'lupo_'`. So the written config’s `LUPO_TABLE_PREFIX` matches the prefix used for the created tables.
- **install.php — config step:** `table_prefix` from session is added to `$options` when calling `writeConfig()`, so the final config file reflects the prefix chosen at credentials.

**Files modified (4.0.8):** `database/migrations/install_new_lupopedia.sql`, `lupo-includes/class-iris.php`, `lupo-includes/classes/LABSValidator.php`, `app/Services/System/SystemHealthService.php`, `app/Http/Controllers/SystemHealthController.php`, `docs/doctrine/UNIFIED_REGISTRY_DOCTRINE.md` (new), `install_wizard_classes.php`, `install.php`, `config/global_atoms.yaml`, `docs/doctrine/VERSIONING_DOCTRINE.md`, `.cursor/rules/required-tables-future-features-doctrine.mdc`, `.cursorrules`, `lupo-includes/models/GroundedAgentModel.php`, `lupo-includes/theme/theme-loader.php`, `app/Services/System/LupopediaMigrationController.php`, `scripts/run_labs_handshake.php`, `scripts/migrate_user_mappings.php`, `docs/audits/DYNAMIC_TABLE_PREFIX_AUDIT.md` (new).

**Versioning Note:**  
Lupopedia 4.0.x is a development/stabilization series.  
The ONLY supported upgrade path is:

    Crafty Syntax 3.7.5 → Lupopedia 4.0.x

There are **no Lupopedia → Lupopedia upgrades** until **4.1.0**.

---

## Lupopedia 4.0.7 — Stabilization Patch (Installer Run Step, Seed SET, Channel Roles Fix, Analytics Visits Import) - 2026-02-13

Lupopedia 4.0.7 is part of the iterative development cycle for the **Crafty Syntax 3.7.5 → Lupopedia 4.0.x** upgrade path.  
There are **no Lupopedia → Lupopedia upgrades** in the 4.0.x series.

This patch includes:

### 1. Installer Run Step Fix (install.php)

- **Problem:** When the user clicked "Run installation" on the Confirm step, the wizard redirected to `install.php?step=run`. The next request was a **GET**, but the run step required **POST** and redirected back to Confirm. Install/seed/import/drop SQL never executed.
- **Fix:** On Confirm step, when POST and `action=run` and no errors, set `$step = 'run'` and continue in the same request instead of redirecting. The run block then executes with POST, so install_new_lupopedia.sql, seed_lupopedia.sql, import (upgrade), and drop run as intended.
- **Result:** New installs get schema + seed + reserved channels; upgrades get import + operator channels + drop legacy tables.

### 2. Seed SQL SET Statements Fix (install_wizard_classes.php)

- **Problem:** `InstallWizardSqlRunner::runSqlFile()` filtered out any statement matching `^\s*SET\s+`. Seed file uses `SET @now = ...` and `SET @node_id = ...`; those were never run, so INSERTs using `@now` / `@node_id` failed or inserted NULLs. Tables appeared empty after install.
- **Fix:** Removed the SET filter in the statement filter. Only empty statements are now excluded; SET (and all other statements) are executed.
- **Result:** seed_lupopedia.sql runs correctly; departments 0 and 1 and all seed data insert with valid timestamps and IDs.

### 3. module-loader.php — lupo_channel_roles Removal

- **Problem:** Two queries still used the dropped table `lupo_channel_roles` (POST channel permission check and list of channels where user has a role). Would break after 4.0.6.
- **Fix:** Replaced with `lupo_actor_channel_roles`, `role_key` (aliased as `role_type` for the signon view), and `(is_deleted = 0 OR is_deleted IS NULL)` to match AuthRoleResolver.
- **Files:** `lupo-includes/modules/module-loader.php` — no references to `lupo_channel_roles` remain; PHP 5.3 compatible.

### 4. Analytics Visits Import (import_from_old_crafty_syntax.sql)

- **Context:** Existing import already populated **lupo_unified_visits** from livehelp_visits_daily and livehelp_visits_monthly. **lupo_analytics_visits_daily** and **lupo_analytics_visits_monthly** were not populated from Crafty.
- **Addition:** After the lupo_unified_visits imports, added:
  - **livehelp_visits_daily → lupo_analytics_visits_daily:** Aggregated by (livehelp_id, dateof) to match unique (content_id, date_ymd). content_id = livehelp_id, date_ymd = dateof, visits = SUM(levelvisits + directvisits), direct_visits = SUM(directvisits), url_path = SUBSTRING(MAX(pageurl), 1, 500), department_id = MAX(department). Columns without Crafty equivalents (unique_sessions, unique_actors, internal_visits, entry_count, exit_count, total_seconds, avg_seconds) set to 0. created_ymdhis/updated_ymdhis via UTC_TIMESTAMP.
  - **livehelp_visits_monthly → lupo_analytics_visits_monthly:** Aggregated by dateof (content_id = 0; one row per month). Same visit/direct_visits and timestamp logic. TRUNCATE before each insert; analytics_visits_daily_id / analytics_visits_monthly_id assigned via @rn.
- **lupo_analytics_visits** (raw per-session table): No Crafty source; not imported; remains for runtime only.

**Files modified (4.0.7):** `install.php`, `install_wizard_classes.php`, `lupo-includes/modules/module-loader.php`, `database/migrations/import_from_old_crafty_syntax.sql`.

**Versioning Note:**  
Lupopedia 4.0.x is a development/stabilization series.  
The ONLY supported upgrade path is:

    Crafty Syntax 3.7.5 → Lupopedia 4.0.x

There are **no Lupopedia → Lupopedia upgrades** until **4.1.0**.

---

## Lupopedia 4.0.6 — Stabilization Patch (System Department, 3-Layer Permissions, Installer Fixes) - 2026-02-12

Lupopedia 4.0.6 is part of the iterative development cycle for the **Crafty Syntax 3.7.5 → Lupopedia 4.0.x** upgrade path.  
There are **no Lupopedia → Lupopedia upgrades** in the 4.0.x series.

This patch includes:

### 1. Install Redirect Doctrine (Constitutional)

- **index.php:** If `lupopedia-config.php` does NOT exist, ALWAYS redirect to `install.php`. config.php MUST NOT block this redirect. Redirect occurs before any output. A white page must never occur.
- This rule is mandatory for all future versions.

### 2. Config Deletion After Install

- **install_wizard_classes.php:** After successfully writing `lupopedia-config.php`, the wizard now **deletes** (not renames) the old `config.php`.
- Safety check: Only delete if `lupopedia-config.php` exists, is readable, and contains `LUPOPEDIA_CONFIG_LOADED`. If not safe, skip deletion and log.

### 3. lupo_channel_roles Removal (Identity Doctrine)

- **install_new_lupopedia.sql:** Removed `lupo_channel_roles` table. Identity doctrine: NO lupo_channel_roles.
- **install_wizard_classes.php:** Removed dual-write to `lupo_channel_roles`; reserved channels now use only `lupo_actor_channel_roles`.
- **AuthRoleResolver.php:** Switched from `lupo_channel_roles` / `role_type` to `lupo_actor_channel_roles` / `role_key`.
- **AuthManager.php:** Switched from `lupo_channel_roles` / `role_type` to `lupo_actor_channel_roles` / `role_key`.
- **livehelp-js.php, visitor-image.php, choosedepartment.php, operator-accept-visitor-api.php:** All "anyone online" and role checks now use `lupo_actor_channel_roles`.
- **database/migrations/migration_drop_lupo_channel_roles.sql:** New migration to drop `lupo_channel_roles` for existing databases. Run after `migration_operator_to_actor_channel_roles.sql` if upgrading from pre-4.0.5.

### 4. System Department (department_id = 0)

- **Department 0** seeded as the **System Department** in `seed_lupopedia.sql` and `import_from_old_crafty_syntax.sql`.
- **Department 1** seeded as **General** (default department for channels) in seed and import.
- **install_wizard_classes.php:** `InstallWizardDepartments::ensureSystemDepartment()` creates department 0 if missing; `ensureDefaultDepartment()` creates department 1 if missing.
- **install.php:** `ensureSystemDepartment()` runs before reserved channels (new install) and after import (upgrade).
- **import_from_old_crafty_syntax.sql:** Ensures department 0 and 1 exist after department import; assigns Crafty admins (`isadmin='Y'`) to department 0:
  - `lupo_actor_departments` (actor membership in department 0)
  - `lupo_department_roles` (`role_key='administrator'` for department 0)
- Department 0 is **protected**: cannot be edited, cannot be deleted, hidden from UI (choosedepartment.php, livehelp-js.php, livehelp_js.php, visitor-image.php use `department_id > 0`).
- **Helper functions:** `lupo_is_system_department($department_id)`, `InstallWizardDepartments::isSystemDepartment($departmentId)`.
- **Constant:** `LUPO_SYSTEM_DEPARTMENT_ID` = 0.

**Files involved:** `seed_lupopedia.sql`, `import_from_old_crafty_syntax.sql`, `install_new_lupopedia.sql`, `install_wizard_classes.php`, `install.php`, `REQUIRED_TABLES_4.0.6.md`.

### 5. 3-Layer Permission Resolution Model

- **AuthRoleResolver** updated with new permission hierarchy (resolution order: channel → department → system):

1. **Channel roles** (captain, administrator, monitor) → `lupo_actor_channel_roles`
2. **Department roles** (administrator in channel's department) → `lupo_department_roles`
3. **System roles** (department 0: administrator) → global admin for ALL departments

- **AuthRoleResolver.php:** `hasAdminForChannel($actorId, $channelId)` for channel-scoped admin checks; `getDepartmentIdForChannel($channelId)` private helper; `hasAdminViaPermissions()` fallback; `isAdmin()` delegates to channel 1 admin check via `hasAdminForChannel`.
- **AuthService.php:** `hasAdminForChannel($actorId, $channelId)`.
- **auth-helpers.php:** `lupo_has_admin_for_channel($actor_id, $channel_id)`.
- **lupo_department_roles** table: required for department-scoped roles; indexed on actor_id, department_id, role_key.

**Files involved:** `app/auth/AuthRoleResolver.php`, `app/auth/AuthService.php`, `lupo-includes/functions/auth-helpers.php`.

### 6. Channel → Department Link

- All channels have exactly one `department_id` (lupo_channels schema).
- Permission checks use the channel's `department_id` for layer 2 (department roles).
- `getDepartmentIdForChannel()` used consistently in AuthRoleResolver for department-role lookups.

**Files involved:** `AuthRoleResolver.php`, `channels-controller.php`, `install_wizard_classes.php`.

### 7. Installer / Importer / Wizard Updates

**Installer:**
- `ensureSystemDepartment()` creates department 0.
- `ensureDefaultDepartment()` creates department 1.
- Department 0 protected via `isSystemDepartment()`.
- After import, installer ensures departments 0 and 1 exist.

**Importer:**
- Ensures department 0 and 1 exist (INSERT ON DUPLICATE / INSERT IGNORE).
- Assigns Crafty admins to department 0 (actor_departments + department_roles).
- Preserves `actor_id = auth_user_id` mapping.

**Wizard:**
- Department 0 hidden from UI (excluded from department lists).
- Department 0 cannot be edited or deleted.

**Files involved:** `install_wizard_classes.php`, `install.php`, `import_from_old_crafty_syntax.sql`.

### 8. PHP 5.3 Compatibility Sweep (Continuation from 4.0.5)

- Additional `[]` → `array()` conversions across updated files.
- Enforcement of `array()` only; no short array syntax.
- Removal of PHP 5.4+ syntax where introduced.
- **operator-accept-visitor-api.php:** Replaced `??` with `isset() ? : `; replaced `[]` with `array()` in json_encode and execute(); replaced `Throwable` with `Exception`; `date()` → `gmdate()` for UTC.
- **Rule:** `.cursor/rules/php-5-3-compatibility.mdc` — short array syntax never generated in new or edited code.
- **Audit report:** `docs/audits/PHP_5_3_ARRAY_SYNTAX_SWEEP_REPORT.md` documents the sweep and patterns.

### 9. Role System Consistency

- All permission checks use `lupo_actor_channel_roles` and `role_key` (captain, administrator, monitor).
- No code references `role_type`, `lupo_channel_roles`, or operator privileges.
- **reserved-id-helpers.php:** Comment updated (lupo_channel_roles → lupo_actor_channel_roles).

### 10. Migrations for Existing Installs

- **database/migrations/migration_drop_lupo_channel_roles.sql:** Drops `lupo_channel_roles` for existing databases. Run after `migration_operator_to_actor_channel_roles.sql` if upgrading from pre-4.0.5.
- **database/migrations/migration_system_department_and_admin_roles.sql:** Idempotent migration that:
  - Creates `lupo_department_roles` if missing (with indexes).
  - Inserts department 0 if missing.
  - Inserts department 1 if missing.
  - Assigns existing admins (from `lupo_actor_channel_roles` channel 1 or `lupo_permissions` admin module) to department 0 in `lupo_actor_departments` and `lupo_department_roles`.

### 11. Versioning and Documentation

- **docs/doctrine/VERSIONING_DOCTRINE.md:** Updated to current version 4.0.6. Patch pattern 4.0.6 → 4.0.7.
- **docs/REQUIRED_TABLES_4.0.6.md:** Created; replaces REQUIRED_TABLES_4.0.2.md. Removed lupo_channel_roles. Roles = lupo_actor_channel_roles + lupo_department_roles. Describes department 0 (system), department 1 (default), and 3-layer permission model.
- **TOONs:** Regenerated after schema changes via `scripts/generate_toon_files.py`.
- Updated references in .cursorrules, required-tables-future-features-doctrine.mdc, FUTURE_FEATURES_AND_REQUIRED_TABLES_ALIGNMENT_SUMMARY.md, future_features_lupopedia.sql.

### 12. Additional Changes

- **install_wizard_classes.php:** Crafty admins assigned captain on channel 1 and administrator on department 0 during `createOperatorChannels`.
- **livehelp-js.php, livehelp_js.php, visitor-image.php, choosedepartment.php:** Default department selection excludes department 0 (`AND department_id > 0`).
- **systemHasNoAdmins():** Now checks `lupo_department_roles` for department 0 before channel roles and permissions.

**Files modified (representative):** `index.php`, `app/auth/AuthRoleResolver.php`, `app/auth/AuthService.php`, `lupo-includes/functions/auth-helpers.php`, `install_wizard_classes.php`, `install.php`, `database/migrations/seed_lupopedia.sql`, `database/migrations/import_from_old_crafty_syntax.sql`, `database/migrations/install_new_lupopedia.sql`, `database/migrations/migration_system_department_and_admin_roles.sql`, `database/migrations/migration_drop_lupo_channel_roles.sql`, `docs/doctrine/VERSIONING_DOCTRINE.md`, `docs/REQUIRED_TABLES_4.0.6.md`, `lupo-includes/modules/crafty_syntax/choosedepartment.php`, `lupo-includes/modules/crafty_syntax/livehelp-js.php`, `lupo-includes/modules/crafty_syntax/visitor-image.php`, `livehelp_js.php`, `lupo-includes/modules/channels/operator-accept-visitor-api.php`, `lupo-includes/functions/reserved-id-helpers.php`.

**Versioning Note:**  
Lupopedia 4.0.x is a development/stabilization series.  
The ONLY supported upgrade path is:

    Crafty Syntax 3.7.5 → Lupopedia 4.0.x

There are **no Lupopedia → Lupopedia upgrades** until **4.1.0**.

---


## Lupopedia 4.0.5 — Stabilization Patch (Role-Based Identity, PHP 5.3 Compatibility) - 2026-02-11

Lupopedia 4.0.5 is part of the iterative development cycle for the **Crafty Syntax 3.7.5 → Lupopedia 4.0.x** upgrade path.  
There are **no Lupopedia → Lupopedia upgrades** in the 4.0.x series.

This patch includes:

### 1. PHP 5.3 Compatibility (Array Syntax Sweep)

- Replaced short array syntax `[]` with `array()` in all updated files to enforce PHP 5.3 compatibility.
- **Files updated:** `lupo-includes/themes/default/layouts/main_layout.php`, `lupo-includes/modules/channels/channels-controller.php`, `debug_collection_zero.php`, `api/load_collection_tabs.php`, `app/Services/CraftySyntax/LegacyFunctions.php`, `app/Services/ActorService.php`.
- In `channels-controller.php`: all empty-array assignments, all `execute()`, `render_main_layout()`, and `extract()` array arguments, and inline array literals (e.g. `$pending_visitors[] = [...]`) converted to `array()` with correct closing parentheses.
- Default parameters (e.g. `array $params = []`) and ternary fallbacks (`: []`) converted to `array()`.
- **Rule:** `.cursor/rules/php-5-3-compatibility.mdc` already required `array()`; wording strengthened so short array syntax is never generated in new or edited code.
- **Confirmation:** `array()` is not deprecated in PHP 8.3 and remains fully supported.
- **Audit report:** `docs/audits/PHP_5_3_ARRAY_SYNTAX_SWEEP_REPORT.md` documents the sweep, lists updated files, and provides patterns for converting any remaining files. Array push (`$var[] = value`) was not changed.

### 2. Operator → Role-Based Identity Migration

- Removed all operator-based terminology and logic from the identity and permission model.
- **No `lupo_operators` table;** identity is `lupo_auth_users` + `lupo_actors`; permissions are `lupo_actor_channel_roles` with `role_key` (`captain`, `administrator`, `monitor`).
- **Files updated:** `livehelp_js.php`, `image.php` (role checks: `role_key IN ('captain','monitor','administrator')`); `install_wizard_classes.php` (personal channel creation and captain assignment use `lupo_actor_channel_roles`; reserved channel 1 = Administration; captain for Crafty admins on channel 1); `install.php` (wording: operator channels → personal channels and captain roles); `lupo-includes/modules/channels/channels-controller.php` (all permission and role logic switched from `lupo_channel_roles` to `lupo_actor_channel_roles`; `channel_role_id`/`role_type` → `actor_channel_role_id`/`role_key`); `lupo-includes/classes/AdminUsersHandler.php` (channel 1 admin role via `lupo_actor_channel_roles` and `role_key`); `lupo-includes/themes/default/layouts/main_layout.php` (comment: channel staff interface); `README.md` (operator sessions → staff sessions; uploads path no longer references operators).
- **Audit report:** `docs/audits/OPERATOR_TO_ROLE_BASED_SWEEP_REPORT.md` lists all files changed, installer logic, migration file, and confirmations.

### 3. Installer Enhancements

- **Personal channels for Crafty operators:** For each `livehelp_users` row with `isoperator='Y'`, the wizard creates a row in `lupo_channels` with `channel_name = name + "'s Channel"` and inserts into `lupo_actor_channel_roles` with `role_key = 'captain'`. No `lupo_channel_roles`; all assignments use `lupo_actor_channel_roles`.
- **Global admin channel (channel_id = 1):** Reserved channel 1 is defined as **Administration** (key `administration`, name `Administration`). For each `livehelp_users` row with `isadmin='Y'`, the wizard inserts into `lupo_actor_channel_roles` with `actor_id = auth_user_id`, `channel_id = 1`, `role_key = 'captain'` (idempotent).
- **Reserved channels:** System actor (actor_id = 0) is assigned captain in `lupo_actor_channel_roles` for channels 1, 42, 51. `createReservedSystemChannels` inserts those captain entries so role-based checks see them.
- **Wizard wording:** All references to "operator channels" updated to "personal channels and captain roles"; step descriptions and session keys retained for compatibility.

### 4. Importer Validation

- **`import_from_old_crafty_syntax.sql`** confirmed and left correct: first INSERT into `lupo_auth_users` from `livehelp_users` WHERE `isoperator='Y'`; second INSERT for all remaining users (idempotent). Single INSERT into `lupo_actors` for Crafty operators only (`isoperator='Y'`) with **`actor_id = auth_user_id`**, `actor_source_id = auth_user_id`, `actor_source_type = 'lupo_auth_users'`; timestamps via `CAST(DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS SIGNED)`; idempotent. No INSERT into `lupo_operators`; no role assignment during import (wizard assigns roles later). Department mapping UPDATE retained (`lupo_actor_departments.actor_id`).
- No UNSIGNED in importer; no operator table usage; actor_id = auth_user_id enforced for imported humans.

### 5. Permission System Rewrite

- All permission checks now use **`lupo_actor_channel_roles`** and **`role_key`** (captain, administrator, monitor).
- **channels-controller.php:** Every use of `lupo_channel_roles` (channel_role_id, role_type) replaced with `lupo_actor_channel_roles` (actor_channel_role_id, role_key). All SELECTs, UPDATEs, and INSERTs for channel roles use the new table and column names; view data still exposed as `role_type` for compatibility.
- **AdminUsersHandler.php:** Channel 1 (admin channel) role read/write uses `lupo_actor_channel_roles` and `role_key`.
- **livehelp_js.php, image.php:** "Anyone online" checks use `role_key IN ('captain','monitor','administrator')` (replaced former `operator` with `administrator`).
- No code path checks `isoperator` or `isadmin` for runtime permissions; all permission checks go through `lupo_actor_channel_roles`.

### 6. Migration File for Existing Databases

- **`database/migrations/migration_operator_to_actor_channel_roles.sql`** added for existing installations that previously used `lupo_channel_roles` for permission checks. It: (1) sets `lupo_channels` row for `channel_id = 1` to key/slug/name **Administration** and updates `updated_ymdhis` (BIGINT UTC); (2) copies rows from `lupo_channel_roles` into `lupo_actor_channel_roles` (idempotent, with generated `actor_channel_role_id`; `role_type` → `role_key`). Run once after deploying 4.0.5 if the live DB still has roles only in `lupo_channel_roles`. New installs get roles from the wizard in `lupo_actor_channel_roles` only.

### 7. Documentation and Doctrine

- **README.md:** Operator sessions → staff (captain/administrator/monitor) sessions; uploads path no longer includes `operators`.
- **Migration doctrine:** `docs/doctrine/MIGRATION_DOCTRINE.md` and `.cursor/rules/migration-doctrine.mdc` added (single source for migration doctrine; no DB inference; no CLI SQL; compatibility notes). README sections for database access and SQL compatibility updated.
- **Audit reports:** `docs/audits/OPERATOR_TO_ROLE_BASED_SWEEP_REPORT.md`, `docs/audits/PHP_5_3_ARRAY_SYNTAX_SWEEP_REPORT.md` document the operator→role sweep and the PHP 5.3 array syntax sweep, including files touched, installer logic, migration file, and patterns for remaining work.

### 8. Other Fixes in This Patch

- **livehelp_js.php (root):** `date()` → `gmdate()` for UTC.
- **lupo-includes/modules/crafty_syntax/livehelp-js.php:** Replaced direct PDO with PDO_DB wrapper; all `date('YmdHis')` → `gmdate('YmdHis')`; removed `??` for PHP 5.3; default-department logic corrected.
- **channels-controller.php:** One `??` in pending-visitors block replaced with `isset() ? : ` for PHP 5.3 compatibility where edited.

**Versioning Note:**  
Lupopedia 4.0.x is a development/stabilization series.  
The ONLY supported upgrade path is:

    Crafty Syntax 3.7.5 → Lupopedia 4.0.x

There are **no Lupopedia → Lupopedia upgrades** until **4.1.0**.

---


## Lupopedia 4.0.4 — Stabilization Patch (Crafty Syntax 3.7.5 → Lupopedia 4.0.x) - 2026-02-10

Lupopedia 4.0.4 is part of the iterative development cycle for the **Crafty Syntax 3.7.5 → Lupopedia 4.0.x** upgrade path.  
There are **no Lupopedia → Lupopedia upgrades** in the 4.0.x series.

This patch includes:

### 1. Identity & Actor Model Corrections

- Clarified unified actor model (humans, system identities, AI agents share `actor_id`; separate `users` and `agents` tables exist for metadata, but all relationships use `actor_id` exclusively).
- Updated README Five Pillars to reflect correct identity architecture and global ID registry (`actor_id`, `collection_id`, `channel_id`).
- Ensured doctrine consistently states that the `actors` table is the unified identity layer for the entire semantic OS.

### 2. Collection 0 Fixes

- Seeded Collection 0 correctly.
- Corrected tabs assigned to wrong `collection_id` (1 instead of 0).
- Seeded `lupo_contents` for Collection 0; added `default_collection_id = 0` where required.
- Seeded tab → content mapping (`lupo_collection_tab_map`).
- Added **`debug_collection_zero.php`** in project root for diagnostics (standalone PDO script; no bootstrap/session/auth; runs collections, tabs, contents, and tab→content mapping queries; outputs HTML tables and row counts). Usable at `https://localhost/lupopedia/debug_collection_zero.php`.
- Session default `collection_id` set to 0 where appropriate; main layout and tab render allow `collection_id = 0`; JS auto-loads tabs for collection 0 on DOM ready; API `load_collection_tabs.php` accepts `collection_id = 0`.

### 3. Installer & Wizard Fixes

- Wizard writes `lupopedia-config.php` correctly; after writing, wizard renames or removes old Crafty Syntax `config.php` to `config_backup.php` (or removes it if rename fails); action logged in wizard config log.
- Bootstrap/entry config load order updated to prefer **`lupopedia-config.php` first**, then `config.php` only if lupopedia-config does not exist (legacy mode). Applied in `index.php`.
- Install complete step confirms `lupopedia-config.php` is active and Crafty `config.php` has been backed up or removed; displays config log on success.
- Installer and seed logic use correct timestamp doctrine (BIGINT UTC `YYYYMMDDHHIISS`); installer seeds Collection 0, tabs, contents, and mappings.

### 4. AJAX & UI Pipeline Fixes

- Fixed saved-collections-container not loading on page load.
- Default session `collection_id = 0`.
- JS triggers `loadTabsForCollection()` (or equivalent) on DOM ready for collection 0.
- AJAX endpoint `load_collection_tabs.php` accepts `collection_id = 0`.

### 5. Doctrine & README Rewrite

- Rewrote README to:
  - Clarify Lupopedia 4.0.x = Crafty Syntax reborn + Semantic OS + optional AI agents.
  - Clarify 4.0.x versioning doctrine (only path: Crafty Syntax 3.7.5 → Lupopedia 4.0.x; no L→L until 4.1.0).
  - Add unified actor model explanation and Five Pillars (Unified Actor, Temporal, Relationship, Doctrine, Federation).
  - Unify timestamp format to **`YYYYMMDDHHIISS`** throughout; add standard audit fields (`created_ymdhis`, `updated_ymdhis`); cross-reference **`timestamp_ymdhis`** class for arithmetic; add soft delete pattern (`is_deleted`, `deleted_ymdhis`).
  - Add **PHP & Database Development Standards** subsection (PHP 5.3–8.3+ compatibility, OOP, timestamp format, database constraints, soft delete).
  - Add **Security Doctrine** section (per LEXA): PHP compatibility security, input validation, file upload security, session management, configuration security, error handling, dependency security, security violation classification.
  - Add **Security Audit Doctrine** (security review before merge, quarterly audits, immediate audit after incidents, AI-generated code same review as human).
  - Add Quick Start requirements: PHP 5.3 through 8.3+; table count goal under 200 (196 as of 2/14/2026).
- CHANGELOG: versioning doctrine block and per-version 4.0.x doctrine lines retained/updated.

### 6. Security Enhancements (LEXA Boundary Keeper)

- Added full **Security Doctrine** section in README: PHP compatibility security, input validation, file upload security, session management, configuration security (`lupopedia-config.php` 0640, credentials only in config), error handling (generic user messages, detailed file logs, no stack traces in production), dependency security (bundled libs, `VERSIONS.md`, patches within 30 days), security violation classification (CRITICAL/MAJOR/MINOR).
- Added **Security Audit Doctrine**: security review before merge, quarterly full audits, immediate audit after security incident, AI-generated code must pass same security review as human code.

### 7. Seed File Corrections

- Added `@now` (or equivalent) timestamp variable where applicable.
- All seed inserts use BIGINT UTC timestamps (`YYYYMMDDHHIISS`).
- Idempotent patterns (e.g. `INSERT … ON DUPLICATE KEY UPDATE`) where appropriate.
- Collection 0, tabs, contents, and tab→content mappings seeded correctly.

### 8. Repository Hygiene

- Wolfie Header rules: `file.last_modified_system_version` and `file.channel` updated on edits; default channel `0000` when unknown.
- Removed drift and inconsistencies across touched files (156 files touched in this thread).

### 9. Miscellaneous Fixes

- Auth and session: AuthGuard, AuthManager, AuthRoleResolver, AuthService, Session, auth-helpers, auth-ui-helpers, identity-helpers, session-compat-5.3.php, auth-controller, auth-renderer, password-hash aligned with PHP 5.3–compatible patterns and identity doctrine.
- Corrected doctrine references; updated navigation logic; fixed missing or incorrect includes; fixed session initialization and config loading; fixed installer path and config precedence so post-install only `lupopedia-config.php` is used.

**Files and areas touched (representative):** `install_wizard_classes.php`, `install.php`, `index.php`, `debug_collection_zero.php`, `lupo-includes/bootstrap.php`, `lupo-includes/themes/default/layouts/main_layout.php`, `api/load_collection_tabs.php`, `app/auth/*` (AuthGuard, AuthManager, AuthRoleResolver, AuthService, Session), `lupo-includes/functions/auth-helpers.php`, `lupo-includes/functions/auth-ui-helpers.php`, `lupo-includes/functions/identity-helpers.php`, `lupo-includes/functions/session-compat-5.3.php`, `lupo-includes/modules/auth/auth-controller.php`, `lupo-includes/modules/auth/auth-renderer.php`, `lupo-includes/security/password-hash.php`, `README.md`, `CHANGELOG.md`, seed and installer-related files, and related layout/API/auth call sites. Many additional files touched across the 4.0.4 stabilization thread (doctrine updates, security sections, README rewrite). No TOON files or future_features tables were modified by this patch.

**Versioning Note:**  
Lupopedia 4.0.x is a development/stabilization series.  
The ONLY supported upgrade path is:

    Crafty Syntax 3.7.5 → Lupopedia 4.0.x

There are **no Lupopedia → Lupopedia upgrades** until **4.1.0**, which will not be created until after a stable 4.0.x release is published through auto-installers.


## Lupopedia 4.0.3 - updates to version and compatibility - 2026-02-09

- **4.0.x doctrine:** This version is part of the iterative development cycle for the **Crafty Syntax 3.7.5 → Lupopedia 4.0.x** upgrade path. No Lupopedia → Lupopedia upgrades exist for this version.

- **PHP 5.3+ compatibility:** Sweep across core request paths to remove null coalescing (`??`) and short array syntax (`[]`). Replaced with `isset() ? : ` ternaries and `array()`. Session cookie params use the 5-argument form for PHP 5.3 (no array form, no `samesite`). Files touched: `content-renderer.php`, `index.php`, `bootstrap.php`, `module-loader.php`, `topbar.php`, `actors-controller.php`, `my-profile.php`, `admin.php`, and related layout/view files.
- **Reserved ID doctrine:** Added `.cursor/rules/reserved-id-doctrine.mdc`. Tables for actors, channels, and users do not use AUTO_INCREMENT; IDs are reserved or explicitly allocated. Code must never rely on `lastInsertId()` for these tables; must check if ID exists and then UPDATE or INSERT with explicit ID.
- **Schema (install):** Removed AUTO_INCREMENT from `lupo_actors` and `lupo_auth_users` in `database/migrations/install_new_lupopedia.sql`. Primary keys remain plain bigint; application supplies IDs.
- **`lupo_findpuka()`:** New helper in `lupo-includes/functions/reserved-id-helpers.php` (PHP 5.3–compatible, no namespace). Returns the next available primary-key ID for a given table/column, optionally within a range. Uses PDO_DB only; no AUTO_INCREMENT or lastInsertId(). Loaded from `bootstrap.php`.
- **Insert-path corrections:** All actor and channel (and channel_roles) insert paths updated to use explicit IDs:
  - **ActorService:** `createActorForAuthUser()` uses `lupo_findpuka()` for next `actor_id`, then insert with explicit `actor_id`; returns that ID (no lastInsertId).
  - **LegacyFunctions:** `resolve_actor_from_lupo_user()` uses `lupo_findpuka()` and insert with explicit `actor_id`.
  - **run_labs_handshake.php:** Allocates `actor_id` via `lupo_findpuka()` (fallback to MAX+1), inserts with explicit `actor_id`.
  - **channels-controller.php:** Captain, administrator, and monitor role inserts use `lupo_findpuka()` for `channel_role_id` and INSERT with explicit `channel_role_id`.
  - **AdminUsersHandler:** New channel role uses `lupo_findpuka()` for `channel_role_id` (fallback to MAX+1).
  - **migrate_filesystem_to_db.php:** `createChannelRecord()` allocates `channel_id` with MAX+1 and inserts with explicit `channel_id`; returns that ID (no lastInsertId).
  - **GroundedAgentModel:** `createActorRecord()` allocates `actor_id` with MAX+1, builds full row for `lupo_actors`, inserts and returns allocated `actor_id` (does not use insert_id).
- **My Profile save:** Fixed profile save (e.g. `/my-profile/save`) so updates persist. `lupo_actor_properties` and `lupo_uploads` have no AUTO_INCREMENT; controller now allocates explicit `actor_property_id` and `upload_id` and uses PDO_DB `query`/`fetchRow`/`update`/`insert` only (no raw prepare/execute). TOON-backed column names preserved.
- **Admin Users (OOP):** Admin users section logic moved into non-namespaced class `AdminUsersHandler` in `lupo-includes/classes/AdminUsersHandler.php`. `admin.php` delegates to `AdminUsersHandler::render()` for the Users section.
- **My Profile UI:** Timezone on profile edit page is a dropdown of UTC offsets (decimal style) with human-readable labels (e.g. Central — Chicago, Sioux Falls). Stored in `actor_properties.property_value` as before.
- **Cursor rules:** Added `.cursor/rules/php-5-3-compatibility.mdc` (no `??`, no `[]`, no return types in core, session cookie 5-arg form).

---

## Lupopedia 4.0.2 - no description - 2026-02-08

- **4.0.x doctrine:** This version is part of the iterative development cycle for the **Crafty Syntax 3.7.5 → Lupopedia 4.0.x** upgrade path. No Lupopedia → Lupopedia upgrades exist for this version.
- **Helper refactors:** Domain-by-domain migration of helpers to services/wrappers (Collection Zero, Collection Tabs, Saved Collections, Redirect, Limits, Atoms/Version, Upload). Thin wrappers in `lupo-includes/functions/` call into `app/Services` and `app/Support` where applicable.
- **Version doctrine:** Canonical versioning doctrine established: patch-only 4.0.x; only upgrade path Crafty Syntax 3.7.5 → Lupopedia 4.0.x; no Lupopedia→Lupopedia upgrades until 4.1.0. Version fallbacks and examples in code/atoms set to the single current target; stray version references removed.
- **Python scripts:** All Python scripts consolidated under `scripts/`. Generators and utilities moved from `database/` and `dialogs/` into `scripts/`; doctrine updated so Python lives only in `scripts/`.
- **Reserved-word column renames:** One-time migration for MySQL reserved words: `lupo_actor_group_membership.role` → `role_key`, `lupo_artifacts.type` → `entity_type`, `lupo_pack_role_registry.role` → `role_key`. Install schema and API (artifact, timeline) updated accordingly.
- **Doctrine and rules:** Mandatory .cursorrules for zero-installations / no backward compatibility and version lock; LUPOPEDIA_DOCTRINE and related docs updated to reflect current version and upgrade path.

---

## Lupopedia 4.0.1 - no description - 2026-02-07

- **4.0.x doctrine:** This version is part of the iterative development cycle for the **Crafty Syntax 3.7.5 → Lupopedia 4.0.x** upgrade path. No Lupopedia → Lupopedia upgrades exist for this version.
- **Architecture rebuild:** Structural changes and Crafty Syntax integration preparation. Legacy agent and channel directories removed; new doctrine and TOON files added; migration SQLs for actor model and related fixes.
- **Login and session:** New login system (MD5 upgrade path, redirect-back, session upgrade). Collection 0 documentation landing and Q/A module with routing consolidation.
- **Channels and edges:** ChannelsController and EdgesController added with routing for channels and edges; placeholder views and 3-panel channel UI skeleton; module-loader and layout updates.
- **Prefix normalization:** Table-prefix normalization completed; tables use dynamic `lupo_` prefix from config; legacy unified tables renamed with `_old` suffix where preserved.
- **Crafty Syntax subsystem:** Operator console activated under crafty_syntax; operator expertise and AI→human escalation engine; routing, controllers, and views for Crafty Syntax module.

---

## Lupopedia 4.0.0 - no description - 2026-02-06

- **4.0.x doctrine:** This version is part of the iterative development cycle for the **Crafty Syntax 3.7.5 → Lupopedia 4.0.x** upgrade path. No Lupopedia → Lupopedia upgrades exist for this version.
- Initial Lupopedia release.
- **Upgrade path:** This version only supports new installs or upgrades from **Crafty Syntax 3.7.5**. No Lupopedia→Lupopedia upgrades exist. Lupopedia→Lupopedia upgrade paths do not exist until after version 4.1.0.

---

## Crafty Syntax 3.7.5 (Legacy) - Final legacy release of Crafty Syntax - 2025-11-14

- Final legacy release of Crafty Syntax.
- This is the only supported source for upgrading to Lupopedia 4.0.x. All upgrades to Lupopedia 4.0.x are from Crafty Syntax 3.7.5 (or new installs). No other upgrade paths are valid for the 4.0.x line.
