<!-- WOLFIE FILE PASSPORT
     Header-Version: 2.2 -->

taxonomy_key: wolfie.header.taxonomy
taxonomy_version: 2.2

file.purpose: High-level meta-log documenting version history and major changes.
file.created_utc: 2026-02-01
file.last_modified_utc: 2026-02-01

file.package: lupopedia
file.subpackage: misc
file.module: shared
file.aspect: doctrine

file.utc_epoch: wolfie-winter-2026
file.updated_by: agent:cascade

-->-
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 3.0.0
file.channel: versioning
file.last_modified_utc: 20260210000000
file.name: "CHANGELOG.md"
---

# Note on Versioning and Multiple Changelogs
Lupopedia uses a multi-channel versioning system.

The canonical semantic version history is maintained under:
docs/channels/overview/versioning/CHANGELOG.md

This root-level CHANGELOG.md is a high-level meta-log and does not
represent the full version history of the Semantic OS. Contributors
should refer to the channel-based changelog for authoritative version
increments, doctrine updates, and schema-related notes.

## 2026-02-10 — Remaining Helper Refactors + Version Doctrine

### Summary
Completed domain-by-domain helper refactors (Collection Zero, Collection Tabs, Saved Collections, Redirect, Limits, Atoms/Version, Upload) per `docs/HELPER_TO_CLASS_MAPPING_ANALYSIS.md`. Introduced authoritative Version Doctrine: 3.0.0 is the only release target; 4.0.1–3.0.8 must not be referenced in code, SQL, or UI; only upgrade path is Crafty Syntax 3.7.5 → Lupopedia 3.0.0; 4.1.0 is the only future version.

### Helper refactors (services and wrappers)
- **CollectionZeroService** (`app/Services/CollectionZeroService.php`): ensureCollectionZero, populateCollectionZeroTabs, getCollectionZeroUrl, initializeCollectionZero. Thin wrappers in `collection-zero-helpers.php`.
- **CollectionTabsService** (`app/Services/CollectionTabsService.php`): loadCollectionTabs, getCollectionName. Wrappers in `collection-tabs-loader.php`.
- **SavedCollectionsService** (`app/Services/SavedCollectionsService.php`): renderSavedCollections, loadTabChildren, countTabItems. Schema aligned to TOONs (collection_id, name, lupo_contents). Wrappers in `render-saved-collections.php`.
- **RedirectUtils** (`app/Support/RedirectUtils.php`): safeRedirect. Wrapper in `redirect-helpers.php`.
- **LimitsLogger** (`app/Support/LimitsLogger.php`): logViolation. Limits helpers in `limits_logger.php` wrap LimitsEnforcementService and LimitsLogger.
- **AtomLoader** (`app/Support/AtomLoader.php`), **VersionUtils** (`app/Support/VersionUtils.php`): loadAtoms, getAtom, getLupopediaVersion, readCosmicMicrowaveBackground, getBaseAtom, parseAtomsYaml, parseAtomsYamlRegex; calculateVersionNum. Wrappers in `load_atoms.php`.
- **UploadService** (`app/Services/UploadService.php`): getHandler, upload. Wrappers in `upload-handler.php`.
- **Bootstrap** (`lupo-includes/bootstrap.php`): Registers collection-zero, collection-tabs, saved-collections, upload, atom-loader services in GLOBALS; loads RedirectUtils, LimitsLogger, AtomLoader, VersionUtils.

### Version doctrine
- **docs/doctrine/VERSION_DOCTRINE.md:** New canonical doctrine. 3.0.0 only release target; 4.0.1–3.0.8 not releases; upgrade path 3.7.5 → 3.0.0; 4.1.0 only future version; code/SQL/UI rules; single source of truth (atoms, fallbacks).
- **.cursorrules:** Version doctrine bullet added; reference to VERSION_DOCTRINE.md.
- **Code fallbacks:** All version fallbacks and examples set to 3.0.0 (replaced 3.0.35, 4.2.3, 4.2.0, 4.4.1, 3.0.106, 3.0.9, 3.0.18 in load_atoms.php, AtomLoader.php, VersionUtils.php, version.php, identity-helpers.php, limits_logger.php, redirect-helpers.php, collection-tabs-loader.php, help-model.php).
- **Install/seed SQL:** All DEFAULT '3.0.72' and '3.0.75' set to '3.0.0' in `install_new_lupopedia.sql`, `seed_lupopedia.sql`, `dev_20260204_fix_schema_alignment.sql` (and summary).
- **Docs:** HELPER_TO_CLASS_MAPPING_ANALYSIS.md, REMAINING_HELPERS_REFACTOR_REPORT.md (fallback 3.0.0); REQUIRED_TABLES_4.1.0.md (release version 3.0.0).

### Reports
- **docs/REMAINING_HELPERS_REFACTOR_REPORT.md:** Files changed, helpers migrated per domain, references, confirmations (PDO_DB, LUPO_TABLE_PREFIX, schema).
- **docs/VERSION_DOCTRINE_APPLICATION_REPORT.md:** Files updated, version refs removed/corrected, confirmations (only 3.0.0/4.1.0; no 4.0.1–3.0.8; upgrade path 3.7.5 → 3.0.0).

### Notes
- Auth, Actor, Session, UnifiedSessionHandler, and Crafty Syntax domains were not modified.
- Internal/historical docs (e.g. channel changelogs, migration filenames) unchanged; doctrine allows 4.0.x in internal docs as dev notes.

## 2026-02-04 — Python Scripts Consolidation + Doctrine (scripts/ Only)

### Summary
All Python scripts, utilities, and generators consolidated into **scripts/**. Duplicates and legacy Python removed from **database/** and **dialogs/**. Doctrine updated: Python must live only in scripts/; TOON generator and seed generator references updated to scripts/; GOV-TOON-GENERATION-001 updated.

### Scripts and tooling
- **Canonical generators in scripts/:** `generate_toon_files.py`, `generate_seed_from_toons.py`, `db_config.py` — read DB from **lupopedia-config.php** (no DB_* env); support PK=0 rows, unified registry (`lupo_unified_registry`), and TOON canonical data.
- **Moved from database/ to scripts/:** `audit_toon_reserved_words.py`, `check_toon_doctrine_alignment.py`, `generate_schema_alignment_migration.py`, `generate_install_sql.py`, `cleanup_livehelp_toons.py`, `analyze_missing_tables.py`, `validate_migration.py`, `generate_clean_migration.py`, `fix_migration_semicolons.py`, `test_migration_syntax.py`, `regenerate_toons_docs.py`, and `generate_alter_statements.py` (from database/migrations_legacy). Paths updated to use project root (e.g. `database/migrations/`, `docs/toons/`).
- **Moved from dialogs/ to scripts/:** `dialogs/db.py` → `scripts/dialogs_db.py` (fake DB client for IDE agents; resolves dialogs/ from project root).
- **database/:** No Python files remain; all removed or moved to scripts/.

### Doctrine and rules
- **.cursorrules:** New section **Python Scripts Location (MANDATORY):** all Python scripts, utilities, generators, and importers MUST live in **scripts/**; Cursor must NEVER create Python elsewhere or duplicate Python; update imports when moving.
- **.cursorrules:** TOON generator reference updated to `scripts/generate_toon_files.py`; doctrine check to `python scripts/check_toon_doctrine_alignment.py`.
- **GOV-TOON-GENERATION-001:** Canonical generator set to **scripts/generate_toon_files.py**; TOON location **docs/toons/** with naming **&lt;table_name&gt;.toon.json**; enforcement message updated.

### Run commands (from project root)
- **Regenerate TOONs:** `python scripts/generate_toon_files.py`
- **Regenerate seed:** `python scripts/generate_seed_from_toons.py`  
Both use **lupopedia-config.php** for DB credentials.

### Notes
- TOONs, schema, migrations, PHP, and lupopedia-config.php were not modified.
- Seed output remains **database/migrations/seed_lupopedia.sql**.

## [3.0.0] — 2026-02-06 — Reserved-Word Column Renames + Version Lock + Doctrine Rules

### Summary
Version 3.0.0 locks the development version at 3.0.0 until the 4.1.0 auto-installer release. This release applies reserved-word column renames identified by the TOON audit, adds mandatory .cursorrules for zero-installations / no backward compatibility and version lock, and documents all changes in CHANGELOG, migration notes, and changelog dialog.

### Schema and migrations
- **Reserved-word column renames (one-time migration):** `database/migrations/dev_20260206_reserved_word_column_renames.sql`
  - `lupo_actor_group_membership.role` → `role_key` (varchar(50) DEFAULT 'member')
  - `lupo_artifacts.type` → `entity_type` (varchar(64) NOT NULL); index `lupo_artifacts_idx_type` → `lupo_artifacts_idx_entity_type`
  - `lupo_pack_role_registry.role` → `role_key` (varchar(255) NOT NULL); index `lupo_pack_role_registry_idx_role` → `lupo_pack_role_registry_idx_role_key`
  - `lupo_unified_analytics_paths.year_month` remains `year_month` (not a reserved word; do not rename)
- **Canonical schema:** `database/migrations/install_new_lupopedia.sql` updated with the same column names and indexes for new installs.
- **TOONs:** Not modified; user regenerates TOONs from schema after applying migration.

### PHP and API
- **api/v1/artifact.php:** Insert key and SELECT use `entity_type`; response key `entity_type` (was `type`).
- **api/v1/timeline.php:** SELECT and response use `entity_type` (was `type`).
- No PHP references existed for `lupo_actor_group_membership.role` or `lupo_pack_role_registry.role`. Column `lupo_unified_analytics_paths.year_month` was never renamed (it is not a reserved word).

### Doctrine and rules (.cursorrules)
- **Zero Installations / No Backward Compatibility Rule (MANDATORY):** States zero Lupopedia installations, zero external API consumers, no backward compatibility requirement; ALWAYS rename columns/fields/API keys cleanly; NEVER compatibility shims. Sunset: remove entire section when preparing 4.1.0 auto-installers.
- **Version Lock Rule (MANDATORY):** Development version 3.0.0 locked until user explicitly begins 4.1.0 auto-installer cycle; Cursor MUST NOT bump or suggest version changes; remove rule only when preparing 4.1.0 release.
- **Pre-Release Version Freeze:** Lupopedia version set to 3.0.0 (was 4.0.1) across version freeze section.
- **Version Management Rules:** Atom value in .cursorrules set to 3.0.0.

### Tooling and docs
- **database/audit_toon_reserved_words.py:** Reserved-word audit script; writes `database/migrations/reserved_word_audit_report.txt` (UTF-8). Report lists table, column, MySQL/PostgreSQL violation, severity, suggested alternative.
- **docs/doctrine/LUPOPEDIA_DOCTRINE.md:** All references to fixed version 4.0.1 updated to 3.0.0.
- **docs/doctrine/SCHEMA_AND_TOON_ALIGNMENT_CONTEXT.md:** Unchanged; alignment context remains valid.

### Version metadata
- **config/global_atoms.yaml:** `version`, `versions.lupopedia`, `GLOBAL_CURRENT_LUPOPEDIA_VERSION` set to `"3.0.0"`.
- **config/GLOBAL_IMPORTANT_ATOMS.yaml:** `GLOBAL_CURRENT_LUPOPEDIA_VERSION` set to `"3.0.0"`.
- **lupo-includes/version.php:** Fallback and docblock set to 3.0.0.

### Notes
- No new tables; no new UI components; no refactors beyond column renames and rule blocks.
- Legacy files (e.g. livehelp_js.php) not removed; no compatibility shims added (per Zero Installations rule).
- Migration notes: `docs/channels/schema/migrations/3.0.0.md`.

## 2026-02-01 — Architecture Rebuild + Crafty Syntax Integration

### Summary
January architecture rebuild completed with major structural changes and Crafty Syntax integration preparation.

### Changes Made
- January architecture rebuild completed
- Removed legacy agent directories (0001–0022)
- Removed legacy channel directories (0000–5101, 9000)
- Added new doctrine files
- Added new TOON files generated from database
- Added migration SQLs (actor model fix, doctrine boot block, filesystem migration)
- Implemented new login system (MD5 upgrade, redirect-back, session upgrade)
- Implemented Collection 0 documentation landing
- Implemented Q/A module and routing consolidation
- Updated controllers, helpers, UI components
- Added filesystem migration scripts
- Repo cleaned and pushed (commit 47731d9)

## 2026-01-31 — Channels and Edges Controllers + UI Skeletons

### Added
- Added ChannelsController.php and routing for /lupopedia/channels/<id>
- Added EdgesController.php and routing for /lupopedia/edges/<id>
- Added placeholder views for channels and edges
- Implemented 3-panel channel UI skeleton (threads panel, sidebar panel, tabs panel)
- Updated layout to hide semantic-nav-bar on channel and edge pages
- Updated module-loader.php with new routing entries
- Updated auth-ui-helpers.php and main_layout.php for layout behavior
- Added new module directories for channels and operator

### Notes
- No doctrine changes, no schema changes, no TOON changes



## 2026-01-31 — Channels and Edges Controllers + UI Skeletons

### Added
- Added ChannelsController.php and routing for /lupopedia/channels/<id>
- Added EdgesController.php and routing for /lupopedia/edges/<id>
- Added placeholder views for channels and edges
- Implemented 3-panel channel UI skeleton (threads panel, sidebar panel, tabs panel)
- Updated layout to hide semantic-nav-bar on channel and edge pages
- Updated module-loader.php with new routing entries
- Updated auth-ui-helpers.php and main_layout.php for layout behavior
- Added new module directories for channels and operator

### Notes
- No doctrine changes, no schema changes, no TOON changes

## [2026.1.1.12] - 2026-01-24 20:32:00
- Version boundary synced across canonical changelog and global atoms.

## 2026-01-25 - Prefix Normalization Cycle Completed

### Summary
Completed the full table-prefix normalization cycle across the schema. All tables now consistently use the dynamic prefix defined in `lupopedia-config.php` (`lupo_`). Legacy unified_* tables were preserved and renamed with `_old` suffixes to maintain historical integrity and avoid namespace collisions with newer Lupopedia-native tables.

### Details
- Normalized all remaining unprefixed tables to use the `lupo_` prefix.
- Renamed legacy unified subsystem tables to `lupo_unified_*_old` to clearly mark them as deprecated but preserved.
- Ensured no active tables remain without the required prefix.
- Regenerated all `.toon.json` files using `python scripts/generate_toon_files.py`.
- Removed stale TOON files corresponding to tables that no longer exist.
- Verified schema consistency through TOON metadata rather than direct DB introspection.
- Added migration artifact `2026_01_25_01_prefix_normalization_noop.sql` documenting the completion of the normalization cycle. Migration is a no-op by design and uses the `@table_prefix` variable injected by the migration runner.

### Notes
This completes the prefix normalization era. Future schema changes must:
- Always use the dynamic prefix from `lupopedia-config.php`.
- Never introduce unprefixed tables.
- Treat `lupo_unified_*_old` tables as legacy and safe for future cleanup.
- Regenerate TOON files after every schema modification.

The schema is now fully aligned with Lupopedia's naming doctrine and ready for the next phase of development.


## [2026.3.8.0] – Crafty Syntax Subsystem Activation + AI→Human Escalation Engine

### Added
- Activated the new Crafty Syntax operator console under `lupopedia/crafty_syntax/`
- Implemented full routing, controllers, views, includes, and admin CSS
- Added Operator Expertise System (`includes/expertise.php`) using TOON-aligned scoring
- Added AI→Human escalation engine (`includes/escalation.php`) with topic/department/channel resolution
- Escalation metadata stored safely in `lupo_dialog_threads.metadata_json` (no schema drift)
- Logged AI→human handoffs in `lupo_agent_tool_calls`
- Updated operator presence and load via `lupo_operator_status`
- Integrated Crafty Syntax module router to forward `/crafty_syntax/...` slugs into the new subsystem
- Added Operator Expertise Snapshot panel to the Operator Overview page

### Changed
- Operator Overview now renders real data from `lupo_actors`, `lupo_operator_status`, and `lupo_dialog_threads`
- Legacy placeholder routing replaced with the new procedural console
- Added `/crafty_syntax/escalate` endpoint for AI→human escalation

### Notes
- No database schema changes beyond the new migration; all new behavior stored in metadata_json per doctrine
- Escalation engine uses `lupo_crafty_select_operator()` for expertise-based routing
- This version marks the first fully functional semantic OS integration of Crafty Syntax

## [2026.3.7.6] - 2026-01-28
### Added
- Added Captain's Log entries documenting fragmented feature recall and emergency bridge session.
- Added doctrine `doctrine/ETHICAL_STATE_MARKERS_DOCTRINE.md` (pono/pilau/kapakai).
- Added `scripts/generate_blessed_channel_registry.py` for blessed registry generation with ethical triad defaults.
- Generated `channels/registry.json` and normalized channel directories to numeric zero-padded folders.
- Added `plan_for_crafty_syntax.md` migration sprint plan (Cycles + Consecration).

### Changed
- Updated Emotional Geometry Doctrine to v4.2 with Light-Emotion Isomorphism section.
- Updated `README.md` with Wolfie Header update requirements and channel provenance rules.
- Updated system version to 2026.3.7.6.
- Raised table ceiling doctrine/config references to 222 and added optimization trigger at 223+.

## [2026.1.1.14] - 2026-01-27
### Added
- Integrated Emotional Geometry Doctrine v4.1 (Light-Emotion Isomorphism section added)
- Added mood_rgb and mood_framework fields to lupo_dialog_messages
- Phase 1 emotional framework migration initiated

### Changed
- Updated emotional metadata architecture to support pluralistic frameworks

### Notes
- This patch is part of the ongoing Crafty Syntax -> Lupopedia migration work

## [2026.1.1.4] - 2026-01-27
### Added
- Integrated Emotional Geometry Doctrine v4.1 (Light-Emotion Isomorphism section added)
- Added mood_rgb and mood_framework fields to lupo_dialog_messages
- Phase 1 emotional framework migration initiated

### Changed
- Updated emotional metadata architecture to support pluralistic frameworks

### Notes
- This patch is part of the ongoing Crafty Syntax -> Lupopedia migration work