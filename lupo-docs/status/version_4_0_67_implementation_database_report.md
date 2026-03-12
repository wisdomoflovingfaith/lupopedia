---
file_path_from_root: "docs/status/version_4_0_67_implementation_database_report.md"
file.last_modified_system_version: "4.0.67"
file.last_modified_utc: "20260309"
---

# Version 4.0.67 Implementation Review (Database Setup Focus)

**Scope reviewed:** `CHANGELOG.md` section `## [4.0.67] - Install & Upgrade Validation (2026-03-09)`  
**Review focus:** database setup, install/upgrade path safety, and schema/runtime alignment.

## Executive Summary

Version 4.0.67 is **substantially implemented** with meaningful database-setup improvements:

- Import script/schema alignment is mostly correct for key blocked tables.
- Install wizard mode hardening is implemented and reduces accidental wrong-path upgrades.
- Core install schema now includes root-doctrine tables/columns and a reduced runtime footprint via future-features split.

However, there are **critical/important consistency gaps** that should be addressed:

1. **Critical runtime-schema mismatch remains in admin tasks UI** (`AdminTasksHandler`) referencing non-installed tables/columns.
2. **Changelog-to-code mismatch**: importer does not set `lupo_dialog_threads.last_message_ymdhis` from transcript summary/time despite claim.
3. **Migration tracking table exists but no PHP runtime integration** (no migration-runner write path to `lupo_schema_migrations`).

## Verification Matrix (4.0.67 Claims)

### 1) Version display and atoms
- **Status:** Verified
- **Evidence:**
  - `get_lupopedia_version()` fallback is `4.0.67` in `lupo-includes/functions/load_atoms.php:57`.
  - CLI fallbacks set to `4.0.67` in `lupo-bin/lupo.php:57`, `lupo-bin/lupo.php:498`.
  - Atom files set to `GLOBAL_CURRENT_LUPOPEDIA_VERSION: "4.0.67"` in:
    - `lupo-config/config/global_atoms.yaml:22`
    - `lupo-config/global_atoms.yaml:22`
    - `lupo-config/config/GLOBAL_IMPORTANT_ATOMS.yaml:14`
    - `lupo-config/GLOBAL_IMPORTANT_ATOMS.yaml:14`

### 2) Import SQL fixes (`import_from_old_crafty_syntax.sql`)
- **Status:** Mostly verified
- **Evidence:**
  - `lupo_truth_knowledge` includes `truth_question_parent_id`: `.../import_from_old_crafty_syntax.sql:713`.
  - `lupo_truth_answers` uses `confidence`, `evidence_count`, `source_count`, `status`: lines `801-804`.
  - `lupo_collections` uses `collection_id`, `federation_node_id`, `parent_id`: lines `834-850`.
  - Analytics daily/monthly use `visit_type`, `total_visits`, soft-delete columns: lines `1289-1295`, `1329-1335`.
  - `lupo_actors` insert includes `actor_name` with fallback: lines `1648`, `1666`.
- **Gap noted:**
  - Changelog says `lupo_dialog_threads` title uses transcript summary and sets `last_message_ymdhis`; importer currently sets static title and `last_message_ymdhis = NULL`:
    - `.../import_from_old_crafty_syntax.sql:1456-1457`.

### 3) Install wizard and config hardening
- **Status:** Verified
- **Evidence:**
  - `LUPO_DATABASE_DIR` guarded by `if (!defined(...))`: `install_wizard_classes.php:561`.
  - Stoned Wolfie inserts include `actor_name` and human row uses `actor_source_type='lupo_auth_users'`: `install_wizard_classes.php:1671`, `install_wizard_classes.php:1686`.
  - MD importer pre-validates filename format and ignores non-matching `.md` files:
    - `install/InstallWizardMdImporter.php:75-80`, `86`.

### 4) Install mode selection hardening
- **Status:** Verified
- **Evidence:**
  - Explicit required mode with error if omitted: `install.php:318-319`.
  - Upgrade blocked when no `livehelp_*` tables: `install.php:345-346`.
  - Mode mismatch warning persisted for confirm step: `install.php:353-355`, rendered at `1464-1465` and `1490-1491`.
  - Session cleanup includes new mode keys:
    - Start-over cleanup: `install.php:289`
    - Config completion cleanup: `install.php:832`
    - Complete-step cleanup: `install.php:853`
  - Button copy updated to "Connect and continue": `install.php:1279`.

### 5) Install schema split to future features
- **Status:** Verified (with comment-only residual references in install SQL)
- **Evidence:**
  - 2026-03-09 moved-table blocks exist in `future_features_lupopedia.sql` (e.g., `lupo_actor_aliases` at `112`, `lupo_unified_log` at `1041`).
  - No moved-table `CREATE TABLE` statements remain active in install SQL for sampled set; remaining hits are comments (e.g., `install_new_lupopedia.sql:3113`, `3137`, `3139`, `3141`).

### 6) Admin UI schema alignment
- **Status:** Partially verified, with critical residual mismatch
- **Evidence:**
  - `AdminChannelsHandler` correctly uses `lupo_tasks.task_status` and `task_priority` varchar:
    - `lupo-includes/classes/AdminChannelsHandler.php:56-63`, `137-151`.
  - **Critical mismatch still present in `AdminTasksHandler`:**
    - Joins nonexistent `lupo_task_statuses` / `lupo_task_priorities`: `AdminTasksHandler.php:77-78`
    - Queries nonexistent filters from those tables: `91`, `94`
    - Uses `status_id` / `priority_id` fields not present in canonical `lupo_tasks` table.

### 7) Act-as dropdown restriction
- **Status:** Verified
- **Evidence:**
  - Root `auth_user_id=10000` gets all non-deleted actors: `.../ActorService.php:268-270`.
  - Non-root uses `lupo_actor_edges` with `edge_type='supports'`: `.../ActorService.php:292-293`.
  - `switch-actor.php` validates target actor against `getActorsUserCanActAs(...)`: `switch-actor.php:60-62`.

### 8) Root rename (10000)
- **Status:** Verified (core paths)
- **Evidence:**
  - Seed actor 10000 now `actor_name='root'`, slug `root-10000`: `seed_actors_agents_4.0.45.sql:71`.
  - Registry entry `root-10000`: `seed_registry_comprehensive_4.0.45.sql:44`.
  - Open-registry note updated to root: `seed_registry_open_4.0.45.sql:12`.
  - Workspace migration maps 10000 to `lupo-actors/root`:
    - `.../migrations/dev_20260306_add_actor_workspace_namespace.sql:23`
    - `database/migrations/20260306_add_actor_workspace_namespace.sql:24`

### 9) ROOT doctrine database additions
- **Status:** Verified (DDL/migration), runtime usage pending
- **Evidence:**
  - `lupo_contents` includes `federation_source_url` and `channel_id`: `install_new_lupopedia.sql:1467-1468`.
  - Index `lupo_contents_idx_channel_id`: `install_new_lupopedia.sql:1534`.
  - New tables in install DDL:
    - `lupo_actor_apps`: `259-268`
    - `lupo_channel_departments`: `1800-1810`
    - `lupo_schema_migrations`: `1009-1018`
  - One-time migration includes same changes: `database/migrations/20260309_root_doctrine_content_channel_actor_apps.sql:10-51`.
- **Gap noted:**
  - No runtime PHP references found yet for `lupo_schema_migrations`, `lupo_actor_apps`, or `lupo_channel_departments` (present as schema only).

## Database Setup Improvements Confirmed

1. **Upgrade safety improved**
- Explicit install mode + upgrade guardrail materially reduce accidental destructive/import-mismatch runs.

2. **Importer/schema mismatch risk reduced**
- Key blocked import sections (truth answers, collections, analytics, actors) now align with canonical columns.

3. **Lean install footprint**
- Future-feature table relocation reduces fresh install schema surface and likely lowers bootstrap complexity.

4. **Schema extensibility added**
- Content-channel placement and federation canonical URL fields enable cleaner content routing and federation origin tracking.

5. **Governance-aligned act-as controls**
- Root/full vs support-edge scoped impersonation is implemented at service and switch endpoint layers.

## Findings Requiring Follow-up (Severity Ordered)

### Critical
1. **Admin task page still references removed schema**
- `lupo-includes/classes/AdminTasksHandler.php:77-78`, `91`, `94`.
- Impact: fresh installs/upgrades aligned to canonical schema can fail or show broken admin tasks view.

### Medium
2. **Changelog claim mismatch in dialog thread importer**
- `import_from_old_crafty_syntax.sql:1456-1457` does not derive title from transcript summary and leaves `last_message_ymdhis` null.
- Impact: reduced data quality and mismatch between documented behavior and actual import output.

3. **Migration tracking table not yet operationalized**
- `lupo_schema_migrations` exists in DDL/migration but no runtime writer/reader flow found.
- Impact: run-once migration governance is schema-ready but not yet enforceable in code path.

## Recommended Next Actions

1. Refactor `AdminTasksHandler` to canonical `lupo_tasks.task_status/task_priority` fields (mirror `AdminChannelsHandler` approach).
2. Update transcript import mapping to populate `last_message_ymdhis` and derive title from transcript summary/content.
3. Add a minimal migration runner utility that records applied one-time migrations into `lupo_schema_migrations`.
