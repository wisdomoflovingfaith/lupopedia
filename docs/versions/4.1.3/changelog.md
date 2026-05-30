---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/versions/4.1.3/changelog.md
  web_path: https://www.lupopedia.com/lupopedia/docs/versions/4.1.3/changelog.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/version-4-1-3-changelog.toon
  atoms_toon: null
  transcript_jsonl: 0/development/4_1_3_changelog_buffer
  artifact_type: changelog
  artifact_kind: version_specific
  channel_key: development
  federation_node_id: 0
  thread_key: ''
  lupopedia.schema: changelog
  prd_cluster: null
  title: Lupopedia 4.1.3 changelog (buffer-consolidated)
  summary: Entries appended from changelog-pending JSON by consolidate_lupo_changelog_pending.py.
---
# Lupopedia 4.1.3 -- Changelog

Consolidated **buffer** entries (WHO / UTC / WHAT) from `changelog-pending/*.json` per
CHANGELOG_BUFFER_ARCHITECTURE.md. Each entry includes a hidden merge marker for idempotent re-runs.

## April 20, 2026 - Installer & Wizard Overhaul Complete

### JSON Schema Enforcement
- All installer, wizard, and import logic now load JSON schemas from database/lupopedia/json/
- All SQL now uses explicit column lists
- No predictive column naming allowed

### Installer Cleanup (install.php)
- Installer now runs only install_new_lupopedia.sql
- Seeds only users 10000 (system) and 10001 (admin)
- Removed all references to livehelp_* tables
- Removed all import logic from installer
- Installer now signals "base install complete" to wizard

### Wizard Update
- Added optional "Import legacy Crafty Syntax data" step
- Step runs only after base install
- Wizard checks for the 5 legacy tables:
  - livehelp_autoinvite
  - livehelp_channels
  - livehelp_operator_departments
  - livehelp_operator_channels
  - livehelp_users
- Wizard displays import results (imported users, skipped users, mapping summary)

### Import Wrapper Implementation
- Created/updated install/ImportLegacyCraftySyntax.php
- Loads JSON schema
- Builds mapping table (legacy_id → new_id)
- Sequential IDs starting at 1
- Stops if new_id > 9999
- Detects tables containing user_id
- Rewrites user_id values in 4 tables (users table is source only)
- Returns structured results to wizard

### Import Script Rewrite
- Removed old "10000 + user_id" logic
- Added sequential remapping using mapping table
- Enforced 9999 ID limit
- Updated 4 legacy tables with user_id columns

### Legacy Table Identification
- Confirmed only 5 Crafty Syntax tables contain user_id:
  - livehelp_autoinvite
  - livehelp_channels
  - livehelp_operator_departments
  - livehelp_operator_channels
  - livehelp_users

### Documentation Added
- Created 20_lupopedia_headers_and_import.md summarizing:
  - installer cleanup
  - wizard import step
  - mapping logic
  - legacy table list
  - Captain's Log index

### Scheduled Work
- Added note: Full installer test scheduled for 2026-04-21

## Entry
<!-- changelog-merged: 20260419211230_cursor_seed_dependency_doc_hardening.json -->
- **WHO:** cursor (actor_id 102)
- **CHANNEL / THREAD:** database / seed-dependency-doc
- **UTC (BIGINT):** `20260419211230`
- **WHAT:**
  - Seed dependency document hardened (removed unsafe assumptions and ambiguous approvals)
  - Files: `docs/versions/4.1.3/DATABASE_SEED_DEPENDENCIES.md`

## Entry
<!-- changelog-merged: 20260420074000_cascade_installer_wizard_debugging_analysis.json -->
- **WHO:** cascade (actor_id 102)
- **CHANNEL / THREAD:** registry / actor_rebuild_and_installer_update
- **UTC (BIGINT):** `20260420074000`
- **WHAT:**
  - Complete installer wizard debugging analysis and 4.1.3 implementation
  - Generated comprehensive installer flow with channel-based coordination
  - Created updated seed file (seed_4.1.3.sql) with all 47 filesystem actors
  - Developed actor rebuild plan and migration SQL (actor_rebuild_4.1.3.sql)
  - Designed updated installer screens HTML templates
  - Files created:
    - `docs/versions/4.1.3/status/install_wizard_analysis.md`
    - `docs/versions/4.1.3/status/updated_installer_flow.md`
    - `docs/versions/4.1.3/status/filesystem_seed_analysis.md`
    - `docs/versions/4.1.3/status/actor_rebuild_plan.md`
    - `database/lupopedia/mysql/seed/seed_4.1.3.sql`
    - `database/lupopedia/mysql/migrate/actor_rebuild_4.1.3.sql`
    - `docs/versions/4.1.3/status/updated_installer_screens.md`
  - Key features implemented:
    - Channel-based coordination system (channels 0, 42, 51, 666)
    - Actor registration from filesystem (47 actors total)
    - Memory path configuration for all actors
    - Enhanced API provider support (8+ providers)
    - Red-team user support (auth_user_id 420)
    - New installer steps: actors, channels, memory configuration

## Entry
<!-- changelog-merged: 20260419200000_cascade_database_import_reconciliation.json -->
- **WHO:** cascade (actor_id 102)
- **CHANNEL / THREAD:** database / import-reconciliation
- **UTC (BIGINT):** `20260419200000`
- **WHAT:**
  - Critical database import reconciliation for Crafty Syntax 3.7.5 → Lupopedia migration
  - Added safety warnings to import script (FRESH-INSTALL-ONLY)
  - Removed references to ejected tables (actor_filesystem, actor_sync_state)
  - Corrected stale table count from 199 to 142 to match canonical install
  - Created comprehensive audit documentation and correction plan
  - Files: 
    - `database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql`
    - `docs/versions/4.1.3/DATABASE_IMPORT_AUDIT.md` (NEW)
    - `docs/versions/4.1.3/PLAN_DATABASE_CORRECTION.md` (NEW)
    - `docs/versions/4.1.3/TODO.md` (updated)
  - Status: Import script now safe with warnings, critical blockers removed

## Entry
<!-- changelog-merged: 20260419203000_cascade_actor_auth_linkage_repair.json -->
- **WHO:** cascade (actor_id 102)
- **CHANNEL / THREAD:** database / actor-auth-linkage-repair
- **UTC (BIGINT):** `20260419203000`
- **WHAT:**
  - Fixed actor ↔ auth user linkage in Crafty import script
  - Added auth_user_id column to INSERT statement for actors
  - Imported operators now explicitly reference auth_users.auth_user_id
  - Files: `database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql`
  - Status: Actor linkage repaired, canonical identity mapping preserved

## Entry
<!-- changelog-merged: 20260419204500_cascade_incomplete_mappings_documentation.json -->
- **WHO:** cascade (actor_id 102)
- **CHANNEL / THREAD:** database / incomplete-mappings-documentation
- **UTC (BIGINT):** `20260419204500`
- **WHAT:**
  - Identified and documented 6 legacy tables with incomplete data migration
  - Created DATABASE_INCOMPLETE_MAPPINGS.md with detailed analysis
  - Added TODO comments to import SQL pointing to documentation
  - Updated TODO.md with incomplete mappings status section
  - Files: 
    - `docs/versions/4.1.3/DATABASE_INCOMPLETE_MAPPINGS.md` (NEW)
    - `database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql` (comments)
    - `docs/versions/4.1.3/TODO.md` (updated)
  - Status: 5 tables require decisions on import strategy

## Entry
<!-- changelog-merged: 20260419210000_cascade_mapping_classification.json -->
- **WHO:** cascade (actor_id 102)
- **CHANNEL / THREAD:** database / mapping-classification
- **UTC (BIGINT):** `20260419210000`
- **WHAT:**
  - Classified remaining incomplete mappings into execution buckets
  - Created DATABASE_MAPPING_CLASSIFICATION.md with detailed classification
  - Separated intentionally dropped tables from unresolved work
  - Updated TODO comments to point to classification file
  - Files: 
    - `docs/versions/4.1.3/DATABASE_MAPPING_CLASSIFICATION.md` (NEW)
    - `docs/versions/4.1.3/DATABASE_INCOMPLETE_MAPPINGS.md` (updated)
    - `docs/versions/4.1.3/TODO.md` (updated)
    - `database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql` (comments)
  - Status: 1 patchable now, 2 seed-dependent, 2 deferred, 1 intentionally dropped

## Entry
<!-- changelog-merged: 20260419211500_cascade_livehelp_modules_patch.json -->
- **WHO:** cascade (actor_id 102)
- **CHANNEL / THREAD:** database / livehelp-modules-patch
- **UTC (BIGINT):** `20260419211500`
- **WHAT:**
  - Patched livehelp_modules import with full SQL mapping to modules table
  - Implemented mapping: id→module_id, name→module_key/module_name, path→user_path, adminpath→admin_path
  - Added proper field documentation for unmapped query_string column
  - Updated all documentation to reflect patched status
  - Files: 
    - `database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql` (patched)
    - `docs/versions/4.1.3/DATABASE_INCOMPLETE_MAPPINGS.md` (updated)
    - `docs/versions/4.1.3/DATABASE_MAPPING_CLASSIFICATION.md` (updated)
    - `docs/versions/4.1.3/TODO.md` (updated)
  - Status: livehelp_modules fully patched, 4 tables remaining incomplete

## Entry
<!-- changelog-merged: 20260419213000_cascade_seed_dependency_analysis.json -->
- **WHO:** cascade (actor_id 102)
- **CHANNEL / THREAD:** database / seed-dependency-analysis
- **UTC (BIGINT):** `20260419213000`
- **WHAT:**
  - Analyzed seed-dependent mappings for livehelp_channels and livehelp_config
  - Created DATABASE_SEED_DEPENDENCIES.md with explicit decision criteria
  - Determined livehelp_config is APPROVED WITH PRECONDITION (seed row required)
  - Determined livehelp_channels NEEDS PRODUCT DECISION (transient vs persistent)
  - Updated classification and documentation with clear decisions
  - Added dependency documentation to import SQL comments
  - Files: 
    - `docs/versions/4.1.3/DATABASE_SEED_DEPENDENCIES.md` (NEW)
    - `docs/versions/4.1.3/DATABASE_MAPPING_CLASSIFICATION.md` (updated)
    - `docs/versions/4.1.3/TODO.md` (updated)
    - `database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql` (comments)
  - Status: 1 approved with precondition, 1 needs product decision, 4 tables remaining incomplete

## Entry
<!-- changelog-merged: 20260417071250_cursor_5f8c3a7be6514870a6ce96f85d1143f1.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / changelog-buffer-architecture
- **UTC (BIGINT):** `20260417071250`
- **WHAT:**
  - Replaced AGENTS changelog completion protocol with the new JSON buffer system and added canonical doctrine documentation for pending and archive buffers.
  - Files: `AGENTS.md`, `docs/doctrine/CHANGELOG_BUFFER_ARCHITECTURE.md`
  - Related toons:
    - `memory/development/canonical/1026/04/agents-md.toon`
    - `memory/development/canonical/1026/04/changelog-buffer-architecture-md.toon`

## Entry
<!-- changelog-merged: 20260417071544_cursor_7a4b9f2d1c.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / changelog-buffer-doctrine-update
- **UTC (BIGINT):** `20260417071544`
- **WHAT:**
  - Refined AGENTS changelog buffer protocol section with strict filename slug pattern, explicit handoff toon rule, and THOTH consolidation ownership.
  - Files: `AGENTS.md`

## Entry
<!-- changelog-merged: 20260417071640_cursor_c9e8f4d2ab.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / bootstrap-header-refresh
- **UTC (BIGINT):** `20260417071640`
- **WHAT:**
  - Updated bootstrap header metadata and doctrine comment formatting and added usage guidance above the spend-tracking hook.
  - Files: `includes/bootstrap.php`, `docs/versions/4.1.2/buffer/20260417071640_102.md`

## Entry
<!-- changelog-merged: 20260417071904_cursor_1d2f8b6a4c.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / config-sample-header-update
- **UTC (BIGINT):** `20260417071904`
- **WHAT:**
  - Updated lupopedia-config-sample header metadata and added a legacy-fallback note for LUPO_APP_DIR while preserving existing security and provider defaults.
  - Files: `lupopedia-config-sample.php`, `docs/versions/4.1.2/buffer/20260417071904_102.md`

## Entry
<!-- changelog-merged: 20260417072047_cursor_8e1c4d6ab2.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / config-resolver-update
- **UTC (BIGINT):** `20260417072047`
- **WHAT:**
  - Updated LupopediaConfigResolver header metadata and modernized protectConfigFile .htaccess deny rule with legacy fallback comments.
  - Files: `includes/classes/LupopediaConfigResolver.php`, `docs/versions/4.1.2/buffer/20260417072047_102.md`

## Entry
<!-- changelog-merged: 20260417072349_cursor_e5b2d7f9a1.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / install-wizard-classes-update
- **UTC (BIGINT):** `20260417072349`
- **WHAT:**
  - Updated install_wizard_classes metadata and filesystem doctrine comment and kept modern config deny rule fallback guidance aligned with validator constraints.
  - Files: `install_wizard_classes.php`, `docs/versions/4.1.2/buffer/20260417072349_102.md`

## Entry
<!-- changelog-merged: 20260417072515_antigravity_a1b2c3.json -->
- **WHO:** antigravity
- **CHANNEL / THREAD:** development / header-update
- **UTC (BIGINT):** `20260417072515`
- **WHAT:**
  - Fixed bootstrap.php Lupopedia headers to use canonical PHP grid format.
  - Files: `includes/bootstrap.php`

## Entry
<!-- changelog-merged: 20260417072637_cursor_3ab7f1d2c4.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / install-wizard-header-exact-block
- **UTC (BIGINT):** `20260417072637`
- **WHAT:**
  - Applied the exact requested header block to install_wizard_classes and kept the filesystem doctrine comment unchanged.
  - Files: `install_wizard_classes.php`, `docs/versions/4.1.2/buffer/20260417072637_102.md`

## Entry
<!-- changelog-merged: 20260417072645_antigravity_cfgsmp.json -->
- **WHO:** antigravity
- **CHANNEL / THREAD:** development / header-update
- **UTC (BIGINT):** `20260417072645`
- **WHAT:**
  - Fixed lupopedia-config-sample.php header to use canonical PHP grid format.
  - Files: `lupopedia-config-sample.php`

## Entry
<!-- changelog-merged: 20260417072802_antigravity_instwiz.json -->
- **WHO:** antigravity
- **CHANNEL / THREAD:** development / header-update
- **UTC (BIGINT):** `20260417072802`
- **WHAT:**
  - Fixed install_wizard_classes.php Lupopedia headers to use canonical PHP grid format.
  - Files: `install_wizard_classes.php`

## Entry
<!-- changelog-merged: 20260417072820_cursor_9bf4d1e2a8.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / api-provider-header-refresh
- **UTC (BIGINT):** `20260417072820`
- **WHAT:**
  - Refreshed ApiProviderChainService header timestamp to current tick value while keeping filesystem doctrine comment unchanged.
  - Files: `app/Services/ApiProviderChainService.php`, `docs/versions/4.1.2/buffer/20260417072820_102.md`

## Entry
<!-- changelog-merged: 20260417072920_antigravity_polish.json -->
- **WHO:** antigravity
- **CHANNEL / THREAD:** development / header-update
- **UTC (BIGINT):** `20260417072920`
- **WHAT:**
  - Polished install_wizard_classes.php Lupopedia headers to use canonical PHP grid format.
  - Files: `install_wizard_classes.php`

## Entry
<!-- changelog-merged: 20260417072920_cursor_4d1a8f2bce.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / install-header-exact-standard
- **UTC (BIGINT):** `20260417072920`
- **WHAT:**
  - Applied the exact requested standard header block to install.php and kept the filesystem doctrine comment unchanged.
  - Files: `install.php`, `docs/versions/4.1.2/buffer/20260417072920_102.md`

## Entry
<!-- changelog-merged: 20260417073013_cursor_6e1b2d4fa9.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / prd16-header-polish
- **UTC (BIGINT):** `20260417073013`
- **WHAT:**
  - Polished three PRD 16 files by refreshing header timestamps and adding default_collection_id nullable notes in main and migration sections.
  - Files: `docs/prd/16_lupopedia_headers.md`, `docs/prd/16_lupopedia_headers_migration.md`, `docs/prd/16_lupopedia_headers_examples.md`, `docs/versions/4.1.2/buffer/20260417073013_102.md`

## Entry
<!-- changelog-merged: 20260417073210_antigravity_clean.json -->
- **WHO:** antigravity
- **CHANNEL / THREAD:** development / header-update
- **UTC (BIGINT):** `20260417073210`
- **WHAT:**
  - Cleaned install_wizard_classes.php header to remove old pk_* fields and replace with content_parent_id/content_slug.
  - Files: `install_wizard_classes.php`

## Entry
<!-- changelog-merged: 20260417073224_cursor_b2e91c4d7a.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / agents-minimal-polish
- **UTC (BIGINT):** `20260417073224`
- **WHAT:**
  - Refreshed AGENTS header timestamp, shortened summary text, and added two concise full-doctrine pointers with consistent internal link paths.
  - Files: `AGENTS.md`, `docs/versions/4.1.2/buffer/20260417073224_102.md`

## Entry
<!-- changelog-merged: 20260417073324_antigravity_inst.json -->
- **WHO:** antigravity
- **CHANNEL / THREAD:** development / header-update
- **UTC (BIGINT):** `20260417073324`
- **WHAT:**
  - Cleaned install.php header to remove old pk_* fields and use canonical PHP grid format.
  - Files: `install.php`

## Entry
<!-- changelog-merged: 20260417073437_cursor_8c2e1f5ab4.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / changelog-buffer-doctrine-polish
- **UTC (BIGINT):** `20260417073437`
- **WHAT:**
  - Refined changelog buffer doctrine wording by tightening metadata text and adding explicit THOTH-only and pending-entry noninterference rules.
  - Files: `docs/doctrine/CHANGELOG_BUFFER_ARCHITECTURE.md`, `docs/versions/4.1.2/buffer/20260417073437_102.md`

## Entry
<!-- changelog-merged: 20260417073556_cursor_a9d24e1f6b.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / bootstrap-header-standardization
- **UTC (BIGINT):** `20260417073556`
- **WHAT:**
  - Standardized bootstrap header metadata with fresh UTC and validator-compliant implementation fields while preserving existing doctrine and hook comments.
  - Files: `includes/bootstrap.php`, `docs/versions/4.1.2/buffer/20260417073556_102.md`

## Entry
<!-- changelog-merged: 20260417073722_antigravity_boot.json -->
- **WHO:** antigravity
- **CHANNEL / THREAD:** development / header-update
- **UTC (BIGINT):** `20260417073722`
- **WHAT:**
  - Polished bootstrap.php header removing old pk_* fields and replacing with content_parent_id/content_slug.
  - Files: `includes/bootstrap.php`

## Entry
<!-- changelog-merged: 20260417073758_cursor_1fe9c2b4ad.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / cursor-handoff-toon-urgent-update
- **UTC (BIGINT):** `20260417073758`
- **WHAT:**
  - Updated cursor handoff toon with completed header work, rate-limit status, pending items, and explicit handoff of primary header maintenance to Antigravity IDE.
  - Files: `memory/development/staging/2026/04/cursor_handoff.toon`, `docs/versions/4.1.2/buffer/20260417073758_102.md`
  - Related toons:
    - `memory/development/staging/2026/04/cursor_handoff.toon`
  - Handoff to: `antigravity-ide`

## Entry
<!-- changelog-merged: 20260417074010_antigravity_configres.json -->
- **WHO:** antigravity
- **CHANNEL / THREAD:** development / header-update
- **UTC (BIGINT):** `20260417074010`
- **WHAT:**
  - Polished LupopediaConfigResolver.php header to use canonical PHP grid format and clean values.
  - Files: `includes/classes/LupopediaConfigResolver.php`

## Entry
<!-- changelog-merged: 20260417074210_antigravity_wizclass.json -->
- **WHO:** antigravity
- **CHANNEL / THREAD:** development / header-update
- **UTC (BIGINT):** `20260417074210`
- **WHAT:**
  - Polished install_wizard_classes.php header to use canonical indented PHP grid format.
  - Files: `install_wizard_classes.php`

## Entry
<!-- changelog-merged: 20260417074448_antigravity_apiproc.json -->
- **WHO:** antigravity
- **CHANNEL / THREAD:** development / header-update
- **UTC (BIGINT):** `20260417074448`
- **WHAT:**
  - Polished ApiProviderChainService.php header to use canonical indented PHP grid format and updated status.
  - Files: `app/Services/ApiProviderChainService.php`

## Entry
<!-- changelog-merged: 20260417075457_antigravity_api_fix.json -->
- **WHO:** antigravity
- **CHANNEL / THREAD:** development / header-update
- **UTC (BIGINT):** `20260417075457`
- **WHAT:**
  - Fixed ApiProviderChainService.php header artifact fields and un-quoted values.
  - Files: `app/Services/ApiProviderChainService.php`

## Entry
<!-- changelog-merged: 20260417092315_cursor_5ac18efb42.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / anubis-doctrine-split
- **UTC (BIGINT):** `20260417092315`
- **WHAT:**
  - Patched PRD16 with a canonical synchronous ANUBIS execution baseline and created a separate non-canonical queue execution proposal doctrine.
  - Files: `docs/prd/16_lupopedia_headers.md`, `docs/doctrine/runtime/ANUBIS_QUEUE_EXECUTION_PROPOSAL.md`, `docs/versions/4.1.2/buffer/20260417092315_102.md`

## Entry
<!-- changelog-merged: 20260417094016_cursor_9d4a7f1b2c3e4d5f.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / prd16-prd38-doctrine-validation
- **UTC (BIGINT):** `20260417094016`
- **WHAT:**
  - Validated PRD 16 and PRD 38 doctrine consistency and manually reconstructed rich TOON sidecars with explicit graph edges.
  - Files: `memory/lupopedia_headers/canonical/2026/04/16_lupopedia_headers.toon`, `memory/memory/canonical/2026/04/38_memory_unification.toon`, `docs/versions/4.1.2/buffer/20260417094016_102.md`
  - Related toons:
    - `memory/lupopedia_headers/canonical/2026/04/16_lupopedia_headers.toon`
    - `memory/memory/canonical/2026/04/38_memory_unification.toon`
  - Open questions: Should PRD 16 and PRD 38 header channel_key and memory_toon values be normalized to the same channels used by the new sidecars?

## Entry
<!-- changelog-merged: 20260417094411_cursor_7c1d9e5a3b42f1d0.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / install-sql-schema-sync
- **UTC (BIGINT):** `20260417094411`
- **WHAT:**
  - Synced install_new_lupopedia.sql memory_nodes and memory_edges with live schema by adding channel_key columns and indexes.
  - Files: `database/lupopedia/mysql/install/install_new_lupopedia.sql`, `docs/versions/4.1.2/buffer/20260417094411_102.md`

## Entry
<!-- changelog-merged: 20260417095522_cursor_18ae42c7d9f1438b.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / prd16-source-resolution-rule
- **UTC (BIGINT):** `20260417095522`
- **WHAT:**
  - Added canonical file-first, database-first, and broken-link repair-state identity authority rules to PRD 16.
  - Files: `docs/prd/16_lupopedia_headers.md`, `docs/versions/4.1.2/buffer/20260417095522_102.md`

## Entry
<!-- changelog-merged: 20260417095805_cursor_4b7a9de31c0f4a22.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / formal-content-id-resolution-model
- **UTC (BIGINT):** `20260417095805`
- **WHAT:**
  - Formalized deterministic three-state content_id resolution in PRD 16 and enforced explicit state classification in validator DB-check path.
  - Files: `docs/prd/16_lupopedia_headers.md`, `scripts/validate_lupopedia_headers_universal.py`, `docs/versions/4.1.2/buffer/20260417095805_102.md`

## Entry
<!-- changelog-merged: 20260417100528_cursor_e3474cb95a7d4d70.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / trust-tier-doctrine-clarification
- **UTC (BIGINT):** `20260417100528`
- **WHAT:**
  - Formalized trust_tier doctrine as canonical versus development and added warning-level STATUS alignment validation.
  - Files: `docs/prd/16_lupopedia_headers.md`, `scripts/lib/header_spec_v3_1.py`, `scripts/validate_lupopedia_headers_universal.py`, `docs/doctrine/runtime/ANUBIS_QUEUE_EXECUTION_PROPOSAL.md`, `docs/versions/4.1.2/buffer/20260417100528_102.md`

## Entry
<!-- changelog-merged: 20260417100835_cursor_a1e7f95c4d2b46fa.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / header-spec-targeted-gap-fill
- **UTC (BIGINT):** `20260417100835`
- **WHAT:**
  - Enhanced header_spec_v3_1.py with lightweight trust tier and status warning hook, channel key presence hook, content_id state classification helper, and doctrine comments.
  - Files: `scripts/lib/header_spec_v3_1.py`, `docs/versions/4.1.2/buffer/20260417100835_102.md`

## Entry
<!-- changelog-merged: 20260417100950_cursor_6f4c9a1be8324d17.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / validator-doctrine-wording-pass
- **UTC (BIGINT):** `20260417100950`
- **WHAT:**
  - Updated validator summary/docstring wording to reflect canonical synchronous ANUBIS baseline and clarified content_id shape-only validation scope.
  - Files: `scripts/validate_lupopedia_headers_universal.py`, `docs/versions/4.1.2/buffer/20260417100950_102.md`

## Entry
<!-- changelog-merged: 20260417101921_cursor_2f8e3a96dc7541ab.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / prd16-precision-cleanup
- **UTC (BIGINT):** `20260417101921`
- **WHAT:**
  - Completed precision cleanup on PRD 16 for consistency and clarity with no structural or doctrinal redesign.
  - Files: `docs/prd/16_lupopedia_headers.md`, `docs/versions/4.1.2/buffer/20260417101921_102.md`

## Entry
<!-- changelog-merged: 20260417102228_cursor_9c5f2b41a7d84e23.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / uncertainty-contradiction-sweep
- **UTC (BIGINT):** `20260417102228`
- **WHAT:**
  - Appended Cursor uncertainty sweep findings to 4.1.2 open questions with evidence, risk, and next-action items.
  - Files: `docs/versions/4.1.2/status/open_questions.md`, `docs/versions/4.1.2/buffer/20260417102228_102.md`

## Entry
<!-- changelog-merged: 20260417102548_cursor_73f2a9c18e4d4b6f.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / trust-tier-status-clarification
- **UTC (BIGINT):** `20260417102548`
- **WHAT:**
  - Clarified in PRD 16 that trust_tier and status are independent fields with advisory warning-level alignment checks.
  - Files: `docs/prd/16_lupopedia_headers.md`, `docs/versions/4.1.2/buffer/20260417102548_102.md`

## Entry
<!-- changelog-merged: 20260417102802_cursor_0f37c5b2d14a4e6a.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / open-questions-structural-cleanup
- **UTC (BIGINT):** `20260417102802`
- **WHAT:**
  - Applied structural consistency cleanup in 4.1.2 open_questions.md with no meaning changes.
  - Files: `docs/versions/4.1.2/status/open_questions.md`, `docs/versions/4.1.2/buffer/20260417102802_102.md`

## Entry
<!-- changelog-merged: 20260417104335_cursor_8d0f0e16fd834227.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / memory-authority-arbitration-model
- **UTC (BIGINT):** `20260417104335`
- **WHAT:**
  - Added a single PRD 38 doctrine section to define memory authority and arbitration across file, memory_toon, and database states.
  - Files: `docs/prd/38_memory_unification.md`, `docs/versions/4.1.2/buffer/20260417104335_102.md`

## Entry
<!-- changelog-merged: 20260417105049_cursor_58409c15370d48ff.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / file-first-ingestion-bridge
- **UTC (BIGINT):** `20260417105049`
- **WHAT:**
  - Implemented channel_key derivation enforcement and edge migration completion for file-first ingestion paths.
  - Files: `scripts/import_memory_edges_from_sidecar.py`, `scripts/lib/db_memory_writer.py`, `docs/versions/4.1.2/buffer/20260417105049_102.md`

## Entry
<!-- changelog-merged: 20260417111930_cursor_3d8a1f56b8544f86.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / open_questions
- **UTC (BIGINT):** `20260417111930`
- **WHAT:**
  - Completed bounded channel_key and sidecar edge ingestion verification lock with status and changelog updates.
  - Files: `scripts/lib/db_memory_writer.py`, `scripts/import_memory_edges_from_sidecar.py`, `docs/versions/4.1.2/status/open_questions.md`, `docs/versions/4.1.2/CHANGELOG.md`, `docs/versions/4.1.2/buffer/20260417111930_102.md`

## Entry
<!-- changelog-merged: 20260417192105_cursor_prd02_ui_layout.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / prd-02-channels-ui
- **UTC (BIGINT):** `20260417192105`
- **WHAT:**
  - PRD 02 v4.1.3: documented central feed horizontal nav bar, one-feed thread rules, left panel vs feed scope, visual ref; migrated header to content_* keys for validator.
  - Files: `docs/prd/02_channels_discussions.md`, `changelog-pending/20260417192105_cursor_prd02_ui_layout.json`

## Entry
<!-- changelog-merged: 20260417192257_cursor_version_413_scaffold.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / version-4-1-3
- **UTC (BIGINT):** `20260417192257`
- **WHAT:**
  - Bumped project version to 4.1.3 in global_atoms.yaml, README, global constants atom, version.php; created docs/versions/4.1.3 scaffold (buffer/archive, status).
  - Files: `config/global_atoms.yaml`, `README.md`, `memory/atoms/lupopedia_global_constants.atom.toon`, `includes/version.php`, `changelog-pending/20260417192257_cursor_version_413_scaffold.json`

## Entry
<!-- changelog-merged: 20260417193000_cursor_channels_mockup_layout.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / channels-ui-mockup
- **UTC (BIGINT):** `20260417193000`
- **WHAT:**
  - channels/index.php: mockup_try2-style main column with 9-slice frame around feed, bottom-area stack, multi-color message rows; shared lupo_channel_chat_row_html for fetch-messages parity.
  - Files: `channels/index.php`, `api/dialog/fetch-messages.php`, `includes/functions/channel_chat_row.php`, `changelog-pending/20260417193000_cursor_channels_mockup_layout.json`

## Entry
<!-- changelog-merged: 20260417203317_cursor_send_route_channels.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / channels-ui
- **UTC (BIGINT):** `20260417203317`
- **WHAT:**
  - Added per-message [send to actor] routing UI on channels index, shared row HTML, channel-members and send-route-copy APIs.
  - Files: `includes/functions/channel_chat_row.php`, `channels/index.php`, `api/dialog/channel-members.php`, `api/dialog/send-route-copy.php`
  - Related toons:
    - `memory/development/staging/2026/04/channel-members-api.toon`
    - `memory/development/staging/2026/04/send-route-copy-api.toon`

## Entry
<!-- changelog-merged: 20260417211834_cursor_prompt_library_dispatch.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / prompt-library
- **UTC (BIGINT):** `20260417211834`
- **WHAT:**
  - Prompt Library on channels/index.php: lupo_prompts schema, prompts API routes, PromptLibraryService, modal UI for save/list/preview/dispatch aligned with active target and message/task send.
  - Files: `channels/index.php`, `includes/modules/module-loader.php`, `includes/modules/api/prompts-api.php`, `app/Services/PromptLibraryService.php`, `database/lupopedia/mysql/install/install_new_lupopedia.sql`

## Entry
<!-- changelog-merged: 20260417212939_cursor_changelog_consolidator.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / changelog-consolidator
- **UTC (BIGINT):** `20260417212939`
- **WHAT:**
  - Added consolidate_lupo_changelog_pending.py for JSON buffer merges into docs/versions/4.1.3/changelog.md, merged 45 pending entries, archived orphans, updated CHANGELOG_BUFFER_ARCHITECTURE and merge_changelog_buffer docstring.
  - Files: `scripts/consolidate_lupo_changelog_pending.py`, `scripts/merge_changelog_buffer.py`, `docs/versions/4.1.3/changelog.md`, `docs/doctrine/CHANGELOG_BUFFER_ARCHITECTURE.md`, `changelog-archive.legacy-fragment.md`

## Entry
<!-- changelog-merged: 20260418035707_cursor_eod_session_handoff.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / eod-handoff-20260418
- **UTC (BIGINT):** `20260418035707`
- **WHAT:**
  - Channels UI: dark channel-sidecar (right rail); api_dashboard partial included from channels with admin_layout; PRD 45 template-first staged workflow; PRD 02 cross-links and PRD_INDEX; captain log headers (apple2e timeline); prompt library / dialog APIs / channel row HTML and related files per full git commit.
  - Files: `channels/index.php`, `templates/admin/api_dashboard.php`, `docs/prd/45_template_first_staged_ui_workflow.md`, `docs/prd/PRD_INDEX.md`, `docs/prd/02_channels_discussions.md`, `content/federation_node/0/captains_log/20260418_apple2e_2_supercomputer.md`, `includes/functions/channel_chat_row.php`, `api/dialog/fetch-messages.php`, `scripts/consolidate_lupo_changelog_pending.py`

## Entry
<!-- changelog-merged: 20260418172738_cursor_todo_ui_model.json, 20260418173241_cursor_todo_milestones.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / 4-1-3-todo
- **UTC (BIGINT):** `20260418172738`
- **WHAT:**
  - Added UI and data model decisions section to docs/versions/4.1.3/todo.md (actor live help baseline, canned vs saved_prompt, terminology, mode separation).
  - Revised 4.1.3 todo.md milestones: May 1 ship for baseline, May rolling 4.1.5/4.1.5, June 1 4.2.0; added integration checkpoint note.
  - Files: `docs/versions/4.1.3/todo.md`

## Entry
<!-- changelog-merged: 20260418184001_cursor_prd02_projection.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / prd-02-projection
- **UTC (BIGINT):** `20260418184001`
- **WHAT:**
  - PRD 02 corrected for projection-based routing, presence vs visibility, 4.1.3 human live help baseline vs 4.2.0 orchestration; observer omniscience and global-feed assumptions removed or reframed.
  - Files: `docs/prd/02_channels_discussions.md`

## Entry
<!-- changelog-merged: 20260418184432_cursor_prd17_workflow.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / prd-17-workflow
- **UTC (BIGINT):** `20260418184432`
- **WHAT:**
  - PRD 17 header v4.1.3 (content_* fields), staged workflow, template-first rule, language array rule, artifact table, validation item 10, and reframed implementation notes.
  - Files: `docs/prd/17_decisions_format.md`

## Entry
<!-- changelog-merged: 20260418184639_cursor_prd02_enforcement.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / prd-02-enforcement
- **UTC (BIGINT):** `20260418184639`
- **WHAT:**
  - PRD 02 guardrail pass: staged workflow, template-first MUST, localization MUST NOT, 4.1.3 orchestration deferral bullet, mockup checklist upgrade, Summary item 7.
  - Files: `docs/prd/02_channels_discussions.md`

## Entry
<!-- changelog-merged: 20260418185514_cursor_prd02_release_line.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / prd-02-release-line
- **UTC (BIGINT):** `20260418185514`
- **WHAT:**
  - PRD 02 release-line correction: 4.1.5-4.1.9 orchestration dev, 4.2.0 first public only; no false deferral of orchestration to 4.2.0; fresh-install / no Lupopedia-to-Lupopedia upgrade note; TOC, summary, Orchestration intro, scattered labels.
  - Files: `docs/prd/02_channels_discussions.md`

## Entry
<!-- changelog-merged: 20260418190503_cursor_prd17_pass.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / prd-17-consistency
- **UTC (BIGINT):** `20260418190503`
- **WHAT:**
  - PRD 17 surgical pass: header_format_version aligned to 4.1.x family, UTF-8 mojibake cleanup, pseudocode and no-inference clarifications, THREAD_INDEX and Action Items alignment notes.
  - Files: `docs/prd/17_decisions_format.md`

## Entry
<!-- changelog-merged: 20260418190751_cursor_prd02_final_pass.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / prd-02-projection-final
- **UTC (BIGINT):** `20260418190751`
- **WHAT:**
  - PRD 02 final consistency: projection feed wording, AUTO_INCREMENT removal in example DDL with IdGenerator note, agent write-only clarification, explicit-only cross-channel tasks, observer and tab rules, 4.1.3 scope boundary, pending_tasks vs prefix tasks.
  - Files: `docs/prd/02_channels_discussions.md`

## Entry
<!-- changelog-merged: 20260418191306_cursor_utf8_write_guard.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / utf8-structured-write
- **UTC (BIGINT):** `20260418191306`
- **WHAT:**
  - Added Utf8StructuredWrite PHP guard and utf8_structured_write.py helper; HermesService transcript JSONL uses prepare before write with P2 defect on failure; generate_json_headers wires Python prepare.
  - Files: `app/Services/HermesService.php, includes/classes/Utf8StructuredWrite.php, scripts/generate_json_headers.py, scripts/utf8_structured_write.py`

## Entry
<!-- changelog-merged: 20260418192221_cursor_emoji_doctrine.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / emoji-doctrine
- **UTC (BIGINT):** `20260418192221`
- **WHAT:**
  - Unified emoji doctrine: SMILIES_IMPLEMENTATION.md deprecated with Crafty DB framing; EMOJI_AND_SMILIES.md completed as canonical filesystem ::img| tokens, validation, rendering, storage, encoding, anti-patterns; headers 4.1.3, pk fields removed.
  - Files: `docs/doctrine/EMOJI_AND_SMILIES.md, docs/doctrine/SMILIES_IMPLEMENTATION.md, memory/development/canonical/1026/04/emoji-and-smilies.json, memory/development/canonical/1026/04/emoji-and-smilies.toon`

## Entry
<!-- changelog-merged: 20260418192619_cursor_header_freeze_413.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / header-policy-4-1-3
- **UTC (BIGINT):** `20260418192619`
- **WHAT:**
  - Documented PRD 16 header policy at 4.1.3 until Crafty Syntax live-help baseline; aligned format doc, validators doc, PRD 17 examples, AGENTS, Cursor rule, templates note, and v2 decisions context.
  - Files: `.cursor/rules/lupopedia-headers-mandatory.mdc, AGENTS.md, changelog-pending/20260418192619_cursor_header_freeze_413.json, docs/doctrine/lupopedia-headers/lupopedia_headers_format.md, docs/doctrine/lupopedia-headers/templates_new_file.md, docs/doctrine/lupopedia-headers/validators_and_tooling.md, docs/doctrine/lupopedia-headers/versions/2.0/decisions.md, docs/prd/16_lupopedia_headers.md, docs/prd/17_decisions_format.md`

## Entry
<!-- changelog-merged: 20260418200257_cursor_prd16_atoms_spec.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / prd-16-atoms-spec
- **UTC (BIGINT):** `20260418200257`
- **WHAT:**
  - Added secondary PRD 16 spec 16_atoms_system_and_global_constants.md; fixed PRD_INDEX primary pick when multiple specifications share a group (prefer linked content_id); regenerated PRD_INDEX and memory sidecar for new file.
  - Files: `changelog-pending/20260418200257_cursor_prd16_atoms_spec.json, docs/prd/16_atoms_system_and_global_constants.md, docs/prd/PRD_INDEX.md, memory/headers/canonical/1026/04/16-atoms-system-global-constants.json, memory/headers/canonical/1026/04/16-atoms-system-global-constants.toon, scripts/generate_prd_index.py`
  - Related toons:
    - `memory/headers/canonical/1026/04/16-atoms-system-global-constants.toon`

## Entry
<!-- changelog-merged: 20260418200841_cursor_utf8structuredwrite_hardening.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / utf8-structured-write
- **UTC (BIGINT):** `20260418200841`
- **WHAT:**
  - Utf8StructuredWrite: mojibake table comments, iconv empty-string assign, docblock for pass-through file_path, UTF-8-only policy note, final class.
  - Files: `changelog-pending/20260418200841_cursor_utf8structuredwrite_hardening.json, includes/classes/Utf8StructuredWrite.php`

## Entry
<!-- changelog-merged: 20260418200947_cursor_hermes_hardening.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / hermes-service
- **UTC (BIGINT):** `20260418200947`
- **WHAT:**
  - HermesService appendTranscript: P1 log when Utf8StructuredWrite missing; json_last_error_msg on encode fail; P2 soft size guard 256KB; mkdir race recheck is_dir; createPendingTask insert log adds table and task_id.
  - Files: `app/Services/HermesService.php, changelog-pending/20260418200947_cursor_hermes_hardening.json`

## Entry
<!-- changelog-merged: 20260418201851_cursor_utf8_py_hardening.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / utf8-structured-write-py
- **UTC (BIGINT):** `20260418201851`
- **WHAT:**
  - utf8_structured_write.py: commented mojibake pairs, coerce_to_utf8 vs PHP iconv doc, prepare doc (file_path pass-through, JSONL framing), dumps_json_ready_for_write rename + newline doc, header tick.
  - Files: `changelog-pending/20260418201851_cursor_utf8_py_hardening.json, scripts/utf8_structured_write.py`

## Entry
<!-- changelog-merged: 20260418202017_cursor_generate_json_headers.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / generate-json-headers
- **UTC (BIGINT):** `20260418202017`
- **WHAT:**
  - generate_json_headers: require utf8_structured_write import (exit 1); json.dumps try/except; 256KB soft warn; YAML parse warn; split_frontmatter scan to EOF; draft tag only when no tags/status; UTF-8 decode error on TOON read.
  - Files: `changelog-pending/20260418202017_cursor_generate_json_headers.json, scripts/generate_json_headers.py`

## Entry
<!-- changelog-merged: 20260418210404_cursor_prd_index_audit_wrap.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / prd-index-audit
- **UTC (BIGINT):** `20260418210404`
- **WHAT:**
  - PRD index regeneration; PRD 03 duplicate doctrine and legacy YAML removed; PRD 19 and 21 title mojibake fixed; PRD 26 related-doc links and when_updated; PRD_GAPS and doctrine audit cross-links.
  - Files: `ORGANIZATION.md, changelog-pending/20260418210404_cursor_prd_index_audit_wrap.json, docs/prd/03_goals_and_success_criteria.md, docs/prd/19_garbage_collection_system.md, docs/prd/21_semantic_navbar.md, docs/prd/26_five_layer_documentation_architecture.md, docs/prd/PRD_INDEX.md`

## Entry
<!-- changelog-merged: 20260418210750_cursor_organization_placement_gate.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / documentation-architecture
- **UTC (BIGINT):** `20260418210750`
- **WHAT:**
  - ORGANIZATION.md: encode documentation crisis doctrine (placement gate, anti-litter, root markdown rule, PRD 31 mirrors, changelog buffer path typo fix).
  - Files: `ORGANIZATION.md, changelog-pending/20260418210750_cursor_organization_placement_gate.json`

## Entry
<!-- changelog-merged: 20260418211412_cursor_prd45_workflow_gates.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / prd-45-workflow
- **UTC (BIGINT):** `20260418211412`
- **WHAT:**
  - PRD 45: add G0-G4 gates, PRD-to-ship prohibition, locale/template contracts, cross-refs to PRD 00 13 29; PRD 13 29 31 back-links; IDE facet base prompt UI workflow bullets; regenerate PRD_INDEX.
  - Files: `agents/_shared/ide_facet_base_system_prompt.txt, changelog-pending/20260418211412_cursor_prd45_workflow_gates.json, docs/prd/13_crafty_integration.md, docs/prd/29_project_structure.md, docs/prd/31_implementation_folder_guidelines.md, docs/prd/45_template_first_staged_ui_workflow.md, docs/prd/PRD_INDEX.md`

## Entry
<!-- changelog-merged: 20260418211622_cursor_prd16_doc_fixes.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / prd-16-headers
- **UTC (BIGINT):** `20260418211622`
- **WHAT:**
  - PRD 16: v4.1.3 field-order label; normative transcript_jsonl slug; questions_toon example; HDR_PK_LEGACY_ALIAS in validator list; PRD 38/51/19/79 cross-refs; line_end for final content_sections; artifact_kind table separator; merge PRD 38 cite in 10.1.
  - Files: `changelog-pending/20260418211622_cursor_prd16_doc_fixes.json, docs/prd/16_lupopedia_headers.md`

## Entry
<!-- changelog-merged: 20260418212313_cursor_prd17_channel_first.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / prd-17-decisions
- **UTC (BIGINT):** `20260418212313`
- **WHAT:**
  - PRD 17: channel-first decisions/{channel_key}/ tree; questions+answers in questions/; derived decision doctrine; THREAD_INDEX rules; edge types; header example per PRD 16; transcript_jsonl 3-segment slug; version metadata in body; memory appendix moved to PRD 03/38/51; pseudocode path updates.
  - Files: `changelog-pending/20260418212313_cursor_prd17_channel_first.json, docs/prd/17_decisions_format.md, docs/prd/PRD_INDEX.md`

## Entry
<!-- changelog-merged: 20260418221552_cursor_ascii_doctrine_lilith.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / lilith-directive-ascii
- **UTC (BIGINT):** `20260418221552`
- **WHAT:**
  - Added LILITH ASCII-only doctrine to AGENTS.md, ORGANIZATION.md encoding section, and agents/_shared/ide_facet_base_system_prompt.txt; ASCII-normalized shared IDE prompt; AGENTS header thread_id null.
  - Files: `AGENTS.md, ORGANIZATION.md, agents/_shared/ide_facet_base_system_prompt.txt, changelog-pending/20260418221552_cursor_ascii_doctrine_lilith.json`

## Entry
<!-- changelog-merged: 20260418221750_cursor_prd_index_primary_ascii.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / lilith-ascii-primary-marker
- **UTC (BIGINT):** `20260418221750`
- **WHAT:**
  - PRD_INDEX primary row marker: Unicode star removed; generate_prd_index.py uses ASCII (primary); ORGANIZATION.md updated; PRD_INDEX.md regenerated.
  - Files: `ORGANIZATION.md, changelog-pending/20260418221750_cursor_prd_index_primary_ascii.json, docs/prd/PRD_INDEX.md, scripts/generate_prd_index.py`

## Entry
<!-- changelog-merged: 20260418221907_cursor_prd45_thread_graph_refs.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / prd-45-header-phase4
- **UTC (BIGINT):** `20260418221907`
- **WHAT:**
  - PRD 45: thread_id null; Phase 4 and G4 cite PRD 38 sections 3 and 6 and PRD 51; when_updated from tick.
  - Files: `changelog-pending/20260418221907_cursor_prd45_thread_graph_refs.json, docs/prd/45_template_first_staged_ui_workflow.md`

## Entry
<!-- changelog-merged: 20260418224537_cursor_prd13_polish.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / prd-13-crafty-integration
- **UTC (BIGINT):** `20260418224537`
- **WHAT:**
  - PRD 13: ASCII polish (PRD 45 dash, Crafty path arrow, section 4.1, mental model separator, normative punctuation, IMPORTANT label, PRD 38/51 links), when_updated from tick.py; removed obsolete _fix_prd13_tmp.py.
  - Files: `_fix_prd13_tmp.py, docs/prd/13_crafty_integration.md`

## Entry
<!-- changelog-merged: 20260418225627_cursor_lilith_ascii_root_docs.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / lilith-ascii-root-docs
- **UTC (BIGINT):** `20260418225627`
- **WHAT:**
  - LILITH absolute ASCII mandate: expanded AGENTS directive; ORGANIZATION encoding; README section + diagram ASCII; CONTRIBUTING/ONBOARDING headers 4.1.3 + mandate blocks; CONTRIBUTING wolfie.headers deprecated field removed; AGENTS body punctuation normalized ASCII.
  - Files: `AGENTS.md, CONTRIBUTING.md, ONBOARDING.md, ORGANIZATION.md, README.md`

## Entry
<!-- changelog-merged: 20260418233700_cursor_personal_context_isolation.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / personal-context-isolation
- **UTC (BIGINT):** `20260418233700`
- **WHAT:**
  - Added PERSONAL_CONTEXT_ISOLATION doctrine; PRD 00 System integrity rules section + memory sidecar; RULE 93.PERSONAL_CONTEXT_ISOLATION.
  - Files: `docs/doctrine/PERSONAL_CONTEXT_ISOLATION.md, docs/prd/00_root_constitutional_system_requirements.md, memory/development/canonical/1026/04/personal-context-isolation.json, memory/development/canonical/1026/04/personal-context-isolation.toon`
  - Related toons:
    - `memory/development/canonical/1026/04/personal-context-isolation.toon`

## Entry
<!-- changelog-merged: 20260418234047_cursor_onboarding_prd_first_docs.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / onboarding-prd-first-docs
- **UTC (BIGINT):** `20260418234047`
- **WHAT:**
  - ONBOARDING.md: added Documentation Architecture (PRD-First System) after section 2; hierarchy, grouping, hard rule, decision table, rationale; merged former section 11; removed duplicate bottom section; header summary and when_updated.
  - Files: `ONBOARDING.md`

## Entry
<!-- changelog-merged: 20260418234246_cursor_contributing_prd_first_docs.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / contributing-prd-first-docs
- **UTC (BIGINT):** `20260418234246`
- **WHAT:**
  - CONTRIBUTING.md: added Documentation Rules (PRD-First Requirement) after section 4 Repository Structure; hard prohibition, placement table, PRD grouping, mis-placed doc behavior, rationale; header when_updated and summary.
  - Files: `CONTRIBUTING.md`

## Entry
<!-- changelog-merged: 20260418234424_cursor_readme_prd_first_docs.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / readme-prd-first-docs
- **UTC (BIGINT):** `20260418234424`
- **WHAT:**
  - README.md: added Documentation Architecture (PRD-First System) after section 1; hierarchy, hard rule, grouping, rationale, cross-links to PRD_INDEX ONBOARDING CONTRIBUTING; header when_updated and summary.
  - Files: `README.md`

## Entry
<!-- changelog-merged: 20260418234551_cursor_agents_prd_first_docs.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / prd-first-docs
- **UTC (BIGINT):** `20260418234551`
- **WHAT:**
  - AGENTS.md: added Documentation Architecture Enforcement (PRD-First) after LUPOPEDIA HEADERS; updated when_updated.
  - Files: `AGENTS.md`

## Entry
<!-- changelog-merged: 20260418234714_cursor_personal_context_non_system.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / personal-context-isolation
- **UTC (BIGINT):** `20260418234714`
- **WHAT:**
  - PERSONAL_CONTEXT_ISOLATION.md: added section 2.1 non-system storage clarification; when_updated tick.
  - Files: `docs/doctrine/PERSONAL_CONTEXT_ISOLATION.md`

## Entry
<!-- changelog-merged: 20260419000416_cursor_prd00_header_413_ascii.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / prd-00-alignment
- **UTC (BIGINT):** `20260419000416`
- **WHAT:**
  - PRD 00: header 4.1.3, content_* fields, ASCII and mojibake cleanup, title 4.1.3, version label note, Rule 7 emoji list rewrite.
  - Files: `docs/prd/00_root_constitutional_system_requirements.md`

## Entry
<!-- changelog-merged: 20260419000543_cursor_onboarding_enforcement.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / onboarding-prd-first
- **UTC (BIGINT):** `20260419000543`
- **WHAT:**
  - ONBOARDING.md: enforcement behavior subsection, PRD_INDEX MUST gate, AGENTS.md linkage; when_updated tick.
  - Files: `ONBOARDING.md`

## Entry
<!-- changelog-merged: 20260419000721_cursor_contributing_enforcement.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / contributing-prd-first
- **UTC (BIGINT):** `20260419000721`
- **WHAT:**
  - CONTRIBUTING.md: PRD_INDEX MUST gate wording; AGENTS.md execution enforcement line; when_updated tick.
  - Files: `CONTRIBUTING.md`

## Entry
<!-- changelog-merged: 20260419002533_cursor_captains_log_em_dash_headers.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / captains-log-headers
- **UTC (BIGINT):** `20260419002533`
- **WHAT:**
  - 30_the_em_dash_conspiracy.md: added PRD 16 LUPOPEDIA HEADERS 4.1.3; generated memory JSON/TOON via generate_memory_from_header.py.
  - Files: `content/federation_node/0/captains_log/30_the_em_dash_conspiracy.md, memory/captains_log/canonical/1026/04/30-em-dash-conspiracy.json, memory/captains_log/canonical/1026/04/30-em-dash-conspiracy.toon`
  - Related toons:
    - `memory/captains_log/canonical/1026/04/30-em-dash-conspiracy.toon`

## Entry
<!-- changelog-merged: 20260419013442_cursor_channel_feed_visibility_dark_rows.json -->
- **WHO:** cursor
- **CHANNEL / THREAD:** development / channel-ui
- **UTC (BIGINT):** `20260419013442`
- **WHAT:**
  - Channel feed: filter dialog_messages by recipient/sender; dark row CSS; own/broadcast classes; align poll APIs.

## Entry
<!-- changelog-merged: 20260419185114_cursor_4_1_3_todo_rewrite.json -->
- **WHO:** cursor (actor_id 102)
- **CHANNEL / THREAD:** development / 4-1-3-planning
- **UTC (BIGINT):** `20260419185114`
- **WHAT:**
  - Rewrote TODO.md to June 1 2026 six-week skeleton; Crafty parity only; ASCII-clean; Notes and Micro-PRD deferral recorded.
  - Files: `docs/versions/4.1.3/TODO.md`

## Entry
<!-- changelog-merged: 20260419190254_cursor_todo_dependency_chain.json -->
- **WHO:** cursor (actor_id 102)
- **CHANNEL / THREAD:** development / 4-1-3-planning
- **UTC (BIGINT):** `20260419190254`
- **WHAT:**
  - TODO.md: replaced calendar plan with A-E dependency chain + F deferred; header when_updated tick to UTC.
  - Files: `docs/versions/4.1.3/TODO.md`

## Entry
<!-- changelog-merged: 20260419190519_cursor_report_todo_dependency.json -->
- **WHO:** cursor (actor_id 102)
- **CHANNEL / THREAD:** development / 4-1-3-docs
- **UTC (BIGINT):** `20260419190519`
- **WHAT:**
  - Synced TODO when_updated; rewrote Helen weekly report for Thursday cadence, removed dated milestones and May 1 4.1.5 kickoff, added Next Dependencies table aligned with layers A-F and channel row/task notes.
  - Files: `docs/versions/4.1.3/TODO.md`, `docs/versions/4.1.3/REPORT_EMAIL_TO_HELEN_2026_04_23.md`

## Entry
<!-- changelog-merged: 20260419190851_cursor_todo_report_sanitize_ascii.json -->
- **WHO:** cursor (actor_id 102)
- **CHANNEL / THREAD:** development / 4-1-3-docs
- **UTC (BIGINT):** `20260419190851`
- **WHAT:**
  - TODO Layer A now cites scripts/sanitize_ascii.py --fix; Helen report ties Thursday cadence, Next Dependencies, and status tables to that ASCII audit path.
  - Files: `docs/versions/4.1.3/TODO.md`, `docs/versions/4.1.3/REPORT_EMAIL_TO_HELEN_2026_04_23.md`

## Entry
<!-- changelog-merged: 20260419203203_cursor_database_migration_plan_docs.json -->
- **WHO:** cursor (actor_id 102)
- **CHANNEL / THREAD:** database / crafty-import-plan
- **UTC (BIGINT):** `20260419203203`
- **WHAT:**
  - Added TODO_DATABASE_MIGRATION.md and PLAN_DATABASE_MIGRATION.md under docs/versions/4.1.3 with measured table counts (34 Crafty, 140 install), blockers, doctrine violations, livehelp mapping matrix, and ordered fix plan; no SQL edits.
  - Files: `docs/versions/4.1.3/TODO_DATABASE_MIGRATION.md`, `docs/versions/4.1.3/PLAN_DATABASE_MIGRATION.md`, `memory/database/canonical/1026/04/database-migration-plan.json`, `memory/database/canonical/1026/04/database-migration-plan.toon`

## Entry
<!-- changelog-merged: 20260419203703_cursor_cascade_db_handoff_toon.json -->
- **WHO:** cursor (actor_id 102)
- **CHANNEL / THREAD:** database / cascade-handoff
- **UTC (BIGINT):** `20260419203703`
- **WHAT:**
  - Created TOON_DB_HANDOFF_FOR_CASCADE.md under docs/agents/cascade/handoff with PRD 16 header and memory sidecar for Cascade/Windsurf database facet bootstrap.
  - Files: `docs/agents/cascade/handoff/TOON_DB_HANDOFF_FOR_CASCADE.md`, `memory/database/canonical/1026/04/cascade-windsurf-db-handoff.json`, `memory/database/canonical/1026/04/cascade-windsurf-db-handoff.toon`
  - Files: `channels/index.php, includes/functions/channel_chat_row.php, includes/modules/channels/channel-messages-api.php, api/dialog/fetch-messages.php`

## Entry
<!-- changelog-merged: 20260419210000_claude_todo_database_migration_sync.json -->
- **WHO:** claude (actor_id 116)
- **CHANNEL / THREAD:** database / todo-database-migration-sync
- **UTC (BIGINT):** `20260419210000`
- **WHAT:**
  - TODO_DATABASE_MIGRATION.md contradictions resolved; mapping language hardened; dev-only warning added.
  - B3 (actor linkage) moved from active blockers to RESOLVED BLOCKERS section; fixed by cascade (20260419203000).
  - livehelp_modules status corrected from NEEDS DECISION to PATCHED IN SQL.
  - livehelp_config status corrected from MIGRATED (partial) to CONDITIONALLY MIGRATED (SEED DEPENDENT).
  - All MIGRATED labels replaced with MAPPED (DEV IMPORT); split-table entries use MAPPED (DEV IMPORT -- SPLIT).
  - Table count standardized to 142 (cascade measured; updated from earlier 140).
  - B5 updated to remove livehelp_modules (now patched); only operator_channels and config remain.
  - Section 5 step 3 marked RESOLVED; step 5 updated to reflect modules done.
  - Dev-only WARNING block added near top of file.
  - Files: `docs/versions/4.1.3/TODO_DATABASE_MIGRATION.md`

## Entry
<!-- changelog-merged: 20260419215000_claude_plan_database_migration_sync.json -->
- **WHO:** claude (actor_id 116)
- **CHANNEL / THREAD:** database / plan-database-migration-sync
- **UTC (BIGINT):** `20260419215000`
- **WHAT:**
  - PLAN_DATABASE_MIGRATION.md synchronized with corrected TODO state; outdated blockers removed; mapping statuses updated.
  - B3 (actor linkage) collapsed to RESOLVED reference; full RESOLVED BLOCKERS section added.
  - livehelp_modules: NEEDS DECISION -> PATCHED IN SQL.
  - livehelp_config: MIGRATED (partial) -> CONDITIONALLY MIGRATED (SEED DEPENDENT).
  - All MIGRATED labels replaced with MIGRATED (DEV IMPORT -- NOT PRODUCTION SAFE).
  - Table count standardized to 142 throughout.
  - B5 updated; Section 5 steps 1/3/5 updated to reflect resolved and remaining work.
  - Dev-only WARNING block added near top of file.
  - Sync rule note updated to acknowledge label wording divergence between PLAN and TODO (intent identical).
  - changelog.md header timestamp corrected: was rolled back to 20260419203703 (wrong) then 20260419210000 (still behind original 20260419211230); now advanced to 20260419215000 to reflect current file state.
  - Files: `docs/versions/4.1.3/PLAN_DATABASE_MIGRATION.md`, `docs/versions/4.1.3/changelog.md`
