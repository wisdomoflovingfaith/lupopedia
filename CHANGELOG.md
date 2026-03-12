---
lupopedia.init:
  document_type: "changelog"
  system_version: "4.0.72"

lupopedia.headers:
  lupopedia.version: "4.0.72"
  lupopedia.schema: "documentation"
  file_path_from_root: "CHANGELOG.md"
  system_version: "4.0.72"
  last_modified_utc: "20260312"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  faucet_name: "cursor"
  artifact_type: "changelog"
  artifact_kind: "history"
  purpose: "Canonical version history for Lupopedia; reverse chronological order."

lupopedia.footer:
  archive_note: "For historical changelog entries from 4.0.67 and earlier, see CHANGELOG_ARCHIVE.md"
  version: "4.0.72"
  last_verified: "20260312"
  last_verified_by: "wolfie"
  orchestrator: "cursor"
  next_action:
    - "Add next_action to any new 4.0.72 subsection entries"
    - "Verify version and last_verified align with release"
    - "Keep required reading and doctrine links current"
---
# Lupopedia CHANGELOG

This document tracks version history, focusing on key changes, task migrations, and optimizations. Entries are in reverse chronological order.

**Archive Note:** For historical changelog entries from 4.0.67 and earlier, see [CHANGELOG_ARCHIVE.md](CHANGELOG_ARCHIVE.md).

---

## Version History

### [4.0.72] — Version bump (2026-03-12)

- **Version bump:** Updated LUPEDIA_VERSION, version.php, install.php, lupo.php, lupo-config atoms (global_atoms.yaml, GLOBAL_IMPORTANT_ATOMS.yaml), CHANGELOG.md, and README.md to 4.0.72. No schema or behavioral changes; release follows 4.0.71 push to GitHub.
- **lupopedia.footer — orchestrator required:** `orchestrator:` added as required metadata in `lupopedia.footer`. Doctrine updated in LUPOPEDIA_HEADERS_FORMAT.md (required fields: `orchestrator`, `last_verified_by`, `next_action`, plus version/last_verified). CHANGELOG and prompts/20260312_ide_agent_4.0.72_required_reading.md footers updated; lupo-tools flare_header_template.txt and flare_apply.py now include `orchestrator`; OPTIONAL_BLOCKS.md table updated.
- **Windsurf audit prompt for gap check:** Added `prompts/20260312_windsurf_audit_4.0.69_4.0.71_gap_check.md`, instructing Windsurf to re-audit versions 4.0.69–4.0.71 (using CHANGELOG and Windsurf audit reports) and append any remaining gaps as tasks under the 4.0.72 “Still needing to be done” section.

#### Still needing to be done (Channel 42 / Channel 0)

The following active or pending work is drawn from Channel 42 and Channel 0 task indexes, the pending-tasks dialog fallback, Windsurf audit recommendations, and Antigravity reviews. No schema or code changes in 4.0.72; this list is for planning.

**From CHANGELOG pending tasks (4.0.71):**
- Run one-time migrations on existing 4.0.x DBs: `20260312_lilith_traits_authorization_faucet.sql`, `20260312_collections_tabs_navigation_4_0_69.sql`; record in `lupo_schema_migrations`. Fresh installs get full schema from install only.
- **Faucet traceability:** Populate `source_faucet_slug` / `source_faucet_instance_id` and `faucet_slug` / `faucet_instance_id` on message and session creation from session/runtime.
- **Collections UI:** Wire global nav and channel sidebar to `getCollectionsForNavMenu()` and `getCollectionsForChannel($channelId)`; tab activation and item rendering for artifact/content/url/path.
- **SessionCustodian:** Optional run of `scripts/session_custodian.php` to audit/correct `lupo-database/sessions/*.md`.
- **Doc–schema consistency:** Run `scripts/check_doc_schema_consistency.py` periodically; consider CI or pre-commit.
- **TOON regeneration:** After applying migrations to a live DB, run `python scripts/generate_toon_files.py`.

**From pending-tasks fallback (Channel 42, `lupo-database/lupopedia/pending_tasks_dialog_fallback_4.0.69.md`):**
- **TraitEnforcer class** (`lupo-includes/classes/TraitEnforcer.php`): `actorHasTrait()`, `isActionAuthorized()`; PDO_DB only; PHP 5.6 compatible.
- **Pre-action hooks:** Call TraitEnforcer before dialog send and other kernel operations; reject if not authorized.
- **Session/message faucet tracking:** Set `faucet_slug` / `faucet_instance_id` on session create; set `source_faucet_slug` / `source_faucet_instance_id` when creating `lupo_dialog_messages`.
- **Seed data:** Seed kernel actor traits (`lupo_actor_traits`), core edge types (`lupo_edge_type_definitions`), core actions (`lupo_action_authorization`). Use allocator/registry for IDs; timestamps in PHP.
- **Documentation:** TRAITS_DOCTRINE, EDGE_TYPE_SEMANTICS_DOCTRINE, AUTHORIZATION_DOCTRINE, FAUCET_TRACEABILITY_DOCTRINE, FEDERATION_NODE_TYPES_DOCTRINE; update IDENTITY_LAYERS_DOCTRINE, ActorFaucetOntology, COMMUNICATION_DOCTRINE.
- **TOONs:** Regenerate/update TOONs for `lupo_actor_traits`, `lupo_dialog_messages`, `lupo_sessions`, `lupo_federation_nodes`, `lupo_edge_type_definitions`, `lupo_action_authorization` after schema apply.

**Channel 42 — active tasks** (see `lupo-database/lupopedia/channels/channel_id/42/tasks/active/`):
- Multiple numbered tasks (task-001 through task-015) and named tasks: database_documentation_remaining_tables, file_count_optimization_4_1_0, repository_cleanup_legacy_files_removal (CLEANUP-2026-02-27-001). One pending task: graph relationship analysis (`tasks/pending/20260225170200_task_42_25_graph_relationship_analysis.md`). Full list in channel 42 task directory and in `lupo-docs/TASK_STATUS_REFERENCE.md` where maintained.

**Channel 0 — active tasks** (see `lupo-docs/CHANNEL_0_ACTOR_0_TASKS.md`):
- Six active tasks under `lupo-database/lupopedia/channels/channel_id/0/tasks/active/`: drop tables and run install, primary install/upgrade 4.0.46, broadcast normalization, db reset and install, installer integration, registry lock. Assignees in filenames (e.g. actor 10000, 19). No pending tasks listed; completed tasks in `tasks/completed/`.

**Windsurf audit — remaining (medium/low):**
- **Semantic navbar:** API response caching; client-side caching headers; query optimization.
- **Session model:** Session cleanup routine; session analytics.
- **Documentation:** Finish legacy `$_SESSION['actor_id']` references in testing docs; add semantic navbar and session model usage examples.

**Antigravity (Channel 42) — recommendations pending implementation:**
- **Collections/tabs:** Evolve collections into channel-scoped resource bundles; add `channel_id` to collections; formalize navigation flags so Channel 42 (and others) can provide tabbed interfaces for artifacts, internal docs, and external links. See `lupo-docs/status/ANTIGRAVITY_COLLECTIONS_TABS_NAVIGATION_REVIEW_4.0.69.md` (status: Recommendations Pending Implementation).

**Doctrine transition (Channel 42):**
- **Doctrine tables → contents:** Target moving doctrine blocks, refinement tracking, and evolution audit to `{prefix}contents` on channel 42 instead of dedicated doctrine tables. See `lupo-docs/doctrine/DOCTRINE_TABLES_TRANSITION_NOTE.md`.

### [4.0.71] — Lupopedia Synthesized Documentation Framework (2026-03-12)

**Summary of 4.0.71 changes (all agents):** Synthesized Documentation Framework and agent registrations; semantic navbar backend (tables, API, JS generator, TOONs, and Windsurf audit remediation); Session Model A (DB-backed sessions); FLARE → LUPOPEDIA HEADERS and PHP 5.3 → 5.6; FLARE/FLIP/FLP deprecation; LUPOPEDIA HEADERS file order and `next_action` in footer; lupopedia.init prerequisite doctrine and required reading; JetBrains domain documentation and TABLE_INDEX; configuration and directory normalization; Windsurf full cross-agent audit and subsequent TOON/API/integration remediation. See subsections below for per-topic detail.

- Implemented Lupopedia Synthesized Documentation Framework based on [lupo-docs/synthesized-framework.md](lupo-docs/synthesized-framework.md).
- Published Antigravity Database Documentation Discrepancy Report: [DATABASE_DOCUMENTATION_DISCREPANCY_REPORT_ANTIGRAVITY.md](lupo-docs/status/DATABASE_DOCUMENTATION_DISCREPANCY_REPORT_ANTIGRAVITY.md).
- Reorganized database table documentation: 178 active tables documented in `active/`, 16 deprecated tables in `deprecated/`, and 63 legacy/migration files in `migrations/`.

- Added canonical header enforcement to all new Markdown artifacts (synthesized.headers / LUPOPEDIA HEADERS).
- Created database schema: `lupo_documentation_frameworks` in `future_features_lupopedia.sql` and one-time migration `20260312_documentation_frameworks_synthesized_framework.sql`.
- Generated `scripts/query_edges.py` and `lupo-bin/query_edges.php` for live edge querying by namespace.
- Developed `scripts/migrate_legacy_docs.py` for batch adding headers to legacy documentation (Phase 2).
- Implemented `lupo-bin/antigravity_governance.php` for monitoring and rejecting non-compliant headers (Phase 4).
- Added `.cursor/rules/synthesized-documentation-header.mdc` for IDE validation on file creation (Phase 5).
- Registered example agents (Antigravity, Cursor, Windsurf, Kiro, JetBrains, Trae) with quadrant-based Markdown in `lupo-docs/synthesized/agent_registrations/`.
- Ensured concurrency support for IDE agents via namespaces and channels. Roadmap phases 1–5 advanced with initial stubs for schema, migration, and governance.

#### Semantic Navbar Backend Rebuild (4.0.71)

- **Audit Completion:** Performed authoritative audit of semantic navigation database requirements. Identified and corrected missing/incomplete table schemas for Edges, Contexts, Folders, Hashtags, and Q/A.
- **Database Expansion:** Implemented missing mapping and summary tables: `lupo_paths_summary`, `lupo_reference_map`, `lupo_collection_links`, `lupo_collection_map`, `lupo_edge_types`, `lupo_edge_map`, `lupo_questions`, `lupo_answers`, and `lupo_question_map`.
- **Authoritative Migration:** Created one-time migration `database/migrations/20260312_authoritative_semantic_navbar_rebuild.sql` and updated canonical `install_new_lupopedia.sql` with these tables.
- **REST API Endpoints:** Developed unified semantic API controller `lupo-includes/modules/api/semantic-navbar-api.php` and updated `module-loader.php` to serve `/lupopedia/<type>/<slug>` JSON endpoints for edges, contexts, hashtags, folders, and qa.
- **JS Generator:** Implemented PHP-to-JS generator `lupo-includes/modules/nav/semantic-navbar-js.php` (accessible via `/lupopedia/nav/semantic-navbar`) that renders a premium floating navbar with lazy-loading popovers and style injection for rich aesthetics.
- **Status Report:** Detailed the rebuild process in [lupo-docs/status/ANTIGRAVITY_SEMANTIC_NAVBAR_REBUILD_4.0.71.md](lupo-docs/status/ANTIGRAVITY_SEMANTIC_NAVBAR_REBUILD_4.0.71.md).

#### Configuration & Architecture (4.0.71)

- **Directory Normalization:** Relocated `lupopedia.docs/` to `lupo-docs/` to align with canonical directory structure.
- **Config Standardization:** Updated `lupopedia-config.php` to include `LUPO_DOCS_DIR` and ensured proper loading of directory constants.
- **Prefix Governance:** Reaffirmed `lupo_` as the default table prefix and normalized all new backend components to honor `LUPO_TABLE_PREFIX`.

#### Session Model A (DB-backed sessions)

- Implemented DB-backed session authority (Model A): browser stores only `session_id`; all protected data (actor_id, roles, CSRF, IP/UA hash, last activity) in `lupo_sessions`. Never use `$_SESSION['actor_id']`; resolve identity via `Session::loadById($db, session_id()); $session->actor_id`.
- Removed signed session payloads and JWT for web sessions; DB is canonical source of truth. Session revocation is DB-driven (delete row); session rotation on login.
- Updated installer SQL: replaced `lupo_sessions` with canonical Model A schema (session_id, actor_id, federation_node_id, ip_hash, ua_hash, csrf_token, last_activity_ymdhis, created_ymdhis, updated_ymdhis, name_key, is_named, metadata). One-time migration: `database/migrations/20260312_session_model_rewrite.sql` (drops legacy unified_sessions, sessions, session_data, lupo_sessions; creates new lupo_sessions).
- Refactored `App\Auth\Session`: `Session::loadById($db, $session_id)`, `Session::create($db, $actor_id)`, `$session->touch()`, `$session->destroy()`, `$session->rotate()`. CSRF token stored in DB; `lupo_get_csrf_token()` reads from Session.
- Replaced all `$_SESSION['user_id']` / `$_SESSION['actor_id']` usage with `$lupo_session->getActorId()` or Session::loadById. Updated auth-controller, oauth-controller, header, main_layout, basic_layout, collections_dropdown, list_user_collections, security.php, admin_bootstrap.
- New doctrine: `lupo-docs/doctrine/SESSION_MODEL.md`. Updated `lupo_sessions` table doc and SESSION_RECONCILIATION_DOCTRINE.

#### FLARE → LUPOPEDIA HEADERS and PHP 5.3 → 5.6 renames (4.0.71)

- **LUPOPEDIA HEADERS:** Replaced FLARE naming across documentation and tooling. All `lupopedia.headers`, `lupopedia.edges`, `lupopedia.footer`, `lupopedia.version`, and `lupopedia.schema` references in `lupo-docs/**/*.md` updated to `lupopedia.headers`, `lupopedia.edges`, `lupopedia.footer`, `lupopedia.version`, and `lupopedia.schema`. Header title "# FLARE Header (aliases: …)" replaced with "# LUPOPEDIA HEADERS (replaces FLARE)". Updated `lupo-docs/doctrine/required_flare_headers.md` and `lupo-docs/doctrine/FILESYSTEM_MIGRATION_GUIDE.md`; bulk-updated all Markdown under `lupo-docs/` for canonical block names. Synthesized-documentation and antigravity governance accept `lupopedia.headers` (and legacy `lupopedia.headers`). Flip-doctrine rule already mandates LUPOPEDIA HEADERS as canonical.
- **PHP 5.6 file and reference renames:** Cursor rule `.cursor/rules/php-5-3-compatibility.mdc` renamed to `.cursor/rules/php-5-6-compatibility.mdc`. Root rule `lupo-rules/root/php-5-3-compatibility.md` renamed to `lupo-rules/root/php-5-6-compatibility.md` and content updated for PHP 5.6 minimum. Session compatibility file `lupo-includes/functions/session-compat-5.3.php` renamed to `session-compat-5.6.php`; all require paths in `Session.php`, `auth-helpers.php`, and `auth-controller.php` updated. References in `INITIALIZATION_PROMPT_4_0_15.md`, `INITIALIZATION_PROMPT_4_0_17.md`, `lupo-rules/root/README.md`, and `CHANGELOG.md` updated to `php-5-6-compatibility`. Historical/audit docs (e.g. AUTH_COMPATIBILITY_AUDIT, broadcast filenames) retain legacy names where they describe past behavior.

#### Runtime and compatibility (4.0.71)

- **Minimum PHP:** 5.6. No Composer or outside frameworks that are not in `lupo-includes`. No deprecated functions that will not work in PHP 8+.
- **Database doctrine:** We do not use database logic: no stored procedures, no stored functions, no foreign keys, no triggers. No `UNSIGNED` integer types. No `TIMESTAMP` or `DATETIME` — all timestamps are `BIGINT` in format `YYYYMMDDHHIISS`, all in UTC; no timezone.

#### Pending tasks (moved from 4.0.70)

- **One-time migrations on existing 4.0.x DBs** — Run `20260312_lilith_traits_authorization_faucet.sql` and `20260312_collections_tabs_navigation_4_0_69.sql` on databases installed before these changes; record in `lupo_schema_migrations`. Fresh installs get full schema from `install_new_lupopedia.sql` only.
- **Faucet traceability at runtime** — Ensure all message and session creation paths populate `source_faucet_slug` / `source_faucet_instance_id` and `faucet_slug` / `faucet_instance_id` from session/runtime where available.
- **Collections UI** — Wire global nav and channel sidebar to `getCollectionsForNavMenu()` and `getCollectionsForChannel($channelId)`; implement tab activation and item rendering for artifact/content/url/path.
- **SessionCustodian** — Optional: run `scripts/session_custodian.php` (and/or Antigravity governance) to audit/correct `lupo-database/sessions/*.md` (e.g. paired_actor_id drift).
- **Doc–schema consistency** — Run `scripts/check_doc_schema_consistency.py` periodically; consider integrating in CI or pre-commit.
- **TOON regeneration** — After applying migrations to a live DB, run `python scripts/generate_toon_files.py` so TOONs match current schema.
- **Database Documentation Program** — 5 IDE agents completed comprehensive database documentation program covering active TOON tables; Antigravity enforced anti-chaos structure. Windsurf audit remediation (4.0.71) added 9 semantic navbar TOONs; total TOON count 230 in registry.
- **Schema registry updated** — `lupo-docs/database/lupopedia/SCHEMA_REGISTRY.md` updated to v4.0.71 with accurate documentation paths and domain ownership.
- **Documentation structure** — Finalized organization into `active/`, `deprecated/`, and `migrations/` directories with standardized LUPOPEDIA HEADERS and 100% active table coverage.
- **Validation completed** — `lupo-docs/database/lupopedia/tables/VALIDATION_REPORT.md` (v4.0.71) confirms total coverage and identifies 11 stale tables moved to `deprecated/`.

#### JetBrains domain documentation update (4.0.71, 2026-03-12)

- Added canonical domain index file: `lupo-docs/database/lupopedia/tables/TABLE_INDEX.md`.
- Documented JetBrains-owned **application structure / knowledge organization** tables under `lupo-docs/database/lupopedia/tables/active/`:
  - `lupo_collections`
  - `lupo_collection_tabs`
  - `lupo_collection_tab_map`
  - `lupo_collection_tab_paths`
  - `lupo_contents`
  - `lupo_departments`
  - `lupo_department_roles`
  - `lupo_department_metadata`
  - `lupo_modules`
  - `lupo_help_topics`
  - `lupo_help_tree`
  - `lupo_truth_knowledge`
  - `lupo_truth_answers`
  - `lupo_artifacts`
  - `lupo_artifact_chunks`
- Added deprecated records for stale knowledge-organization docs that exist in legacy flat docs but are absent from current TOON/install schema:
  - `lupo_reference_objects`
  - `lupo_reference_cited_by`
  - `lupo_modules_departments`
- Included KIRO discrepancy notes in `TABLE_INDEX.md` for cross-domain validation (duplicate path coexistence and stale-table tracking).
- Followed coordination constraints: no edits to Windsurf-owned migration/livehelp documentation.

#### Semantic Navbar Backend (4.0.71)

- **Table audit:** Validated all DB tables required for the semantic floating navigation bar (previous pages, references, contexts, edges, hashtags, folders, Q/A, next pages). Existing tables: lupo_paths, lupo_edges, lupo_edge_type_definitions, lupo_collections, lupo_collection_tabs, lupo_collection_tab_map, lupo_collection_tab_paths, lupo_contents, lupo_truth_knowledge, lupo_truth_answers, lupo_visits.
- **New tables:** Added lupo_references, lupo_reference_links, lupo_hashtags, lupo_hashtag_map, lupo_folders, lupo_folder_map to `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` (no FKs; BIGINT timestamps only).
- **One-time migration:** Created `database/migrations/20260312_semantic_navbar_backend_update.sql` to add the six tables on existing DBs; fresh installs get them from install SQL.
- **Documentation:** Added `lupo-docs/database/lupopedia/tables/semantic_navbar/` with SEMANTIC_NAVBAR_TABLE_AUDIT_REPORT.md, SEMANTIC_NAVBAR_OVERVIEW.md, and per-table docs (lupo_references, lupo_reference_links, lupo_hashtags, lupo_hashtag_map, lupo_folders, lupo_folder_map). Created `lupo-docs/frontend/semantic_navbar.md` (API endpoints, SQL usage, data flow, icon→table mapping, external-site behavior, JS↔Lupopedia communication).

#### Documentation and headers (4.0.71)

- **LUPOPEDIA HEADERS on doctrine and table docs:** Added full LUPOPEDIA HEADERS (lupopedia.init, lupopedia.headers, lupopedia.edges, lupopedia.footer) to `lupo-docs/doctrine/SESSION_MODEL.md`. Replaced remaining "Documentation file with FLARE header applied" purpose strings with "Documentation file with LUPOPEDIA HEADERS applied" across `.md` files. Added identity line to `lupo_sessions.md` table doc. System prompt `lupo-actors/system/prompts/flare-header-scan.md` updated to LUPOPEDIA HEADERS scan; channel README and federation changelog updated to LUPOPEDIA HEADERS protocol.
- **CHANGELOG:** Added lupopedia.init block and "# LUPOPEDIA FOOTER STARTS HERE" section at end of file with repeat lupopedia.footer block for findability.

#### FLARE / FLIP / FLP deprecation and LUPOPEDIA HEADERS consolidation (4.0.71)

- **Deprecation notice:** FLARE, FLIP, and FLP (and aliases Wolfie, FLPH, CROP) are **deprecated** and **replaced** by **LUPOPEDIA HEADERS**. New and modified files must use `lupopedia.*` block names; validators accept legacy `flare.*` / `flame.*` only for backward compatibility.
- **New docs:** Added `lupo-docs/doctrine/LUPOPEDIA_HEADERS/DEPRECATION_FLARE_FLIP_FLP.md` (deprecation notice and mapping) and `OPTIONAL_BLOCKS.md` (lupopedia.routing, lupopedia.lists — functionality carried over from FLARE). Linked from LUPOPEDIA_HEADERS README and PLAN.
- **LUPOPEDIA HEADERS README/plan:** README now states FLARE/FLIP/FLP are deprecated and points to DEPRECATION doc; legacy block names clarified as deprecated. PLAN updated with deprecation and optional-blocks reference.
- **Legacy doc notices:** Added deprecation callouts at top of `lupo-docs/doctrine/FLARE/FLARE_DOCTRINE.md`, `lupo-docs/doctrine/FLIP/FLIP_DOCTRINE.md`, `lupo-docs/doctrine/FLIP/README.md`, and `lupo-docs/api/FLARE_HEADERS_COMPLETE_REFERENCE.md`; content retained for historical reference; current spec is LUPOPEDIA_HEADERS.
- **Cursor rule:** `.cursor/rules/flip-doctrine.mdc` updated to state FLIP/FLARE/FLP are deprecated and to link to DEPRECATION_FLARE_FLIP_FLP.md.
- **Functionality in LUPOPEDIA HEADERS:** Routing (to, from, delegation_chain, channel_id, thread_id, read_by, routing_path) and lists (file.dialog, file.history, file.actors) from FLARE are documented as optional blocks `lupopedia.routing` and `lupopedia.lists` in OPTIONAL_BLOCKS.md so all FLARE/FLIP/FLP behavior exists under LUPOPEDIA HEADERS.

#### LUPOPEDIA HEADERS file order and duplicate-header enforcement (4.0.71)

- **Mandatory file order:** First line of any Markdown with LUPOPEDIA HEADERS must be `---` only; the identity line `# file: ...` must come **after** the closing `---` of the YAML block, never before. Exactly **one** front matter block per file (no duplicate `---` … YAML … `---` blocks).
- **New Cursor rule:** `.cursor/rules/lupopedia-headers-file-order.mdc` added with `alwaysApply: true` and `globs: ["**/*.md"]`. States correct order, "WRONG" (no identity on line 1, no duplicate header), and quick check for all IDE agents.
- **Format and README:** `lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md` and README updated with explicit DO NOT: do not put identity line on line 1; do not duplicate the header (one opening `---`, one YAML block, one closing `---` per file). README quick reference and flip-doctrine.mdc reference the file-order rule.
- **File fixes:** `lupo-docs/doctrine/required_flare_headers.md` — identity line moved to after closing `---`. `lupo-docs/doctrine/FILESYSTEM_MIGRATION_GUIDE.md` — first line set to `---`, duplicate/legacy FLIP block moved into a "Legacy FLIP header" code block. `lupo-docs/status/INITIALIZATION_PROMPT_4_0_15.md` and `INITIALIZATION_PROMPT_4_0_17.md` — removed wrong first line and duplicate YAML blocks; single correct front matter, identity line after `---`, then body. **AGENTS.md** — first line set to `---`, identity line added after closing `---`, `next_action` added to `lupopedia.footer`; edge paths normalized to `lupo-docs/`.

#### next_action in lupopedia.footer (4.0.71)

- **Required footer field:** Every **`lupopedia.footer`** block must include **`next_action:`** — a list of 1–3 suggested next actions (contextual, forward-looking). Documented in `lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md` §5, `OPTIONAL_BLOCKS.md`, and `lupo-docs/api/FLARE_HEADERS_COMPLETE_REFERENCE.md`.
- **Templates and tooling:** `lupo-tools/flare_header_template.txt` and `lupo-tools/flare_apply.py` updated to include `next_action:` in generated footers; new and updated headers get the field automatically.
- **Key files updated:** Doctrine (format, optional blocks, API reference), CHANGELOG (including both header and end-of-file footer blocks), README, lupo-docs/README, INIT_README, LUPO_INITIALIZATION_DOCTRINE, required_flare_headers, SESSION_MODEL, DEPRECATION_FLARE_FLIP_FLP, AGENTS.md, and other files with `lupopedia.footer` updated to include contextual `next_action` lists. Remaining files with `lupopedia.footer` should be updated incrementally to add `next_action` per doctrine.

#### Windsurf audit remediation — TOON compliance, API completion, integration (4.0.71)
- **TOON coverage:** Created 9 missing TOON files in `lupo-docs/toons/` for semantic navbar tables: `lupo_paths_summary`, `lupo_reference_map`, `lupo_collection_links`, `lupo_collection_map`, `lupo_edge_types`, `lupo_edge_map`, `lupo_questions`, `lupo_answers`, `lupo_question_map`. Schema matches install/migration; doctrine-aligned (no FKs, no triggers).
- **Schema registry:** `lupo-docs/database/lupopedia/SCHEMA_REGISTRY.md` updated with the 9 tables and TOON count (230).
- **Semantic navbar API:** Completed missing endpoints in `lupo-includes/modules/api/semantic-navbar-api.php`: `references` (lupo_reference_links + lupo_references), `namespaces` (channel_id + collections), `next` / `previous` (deterministic content_id ordering within channel). Route in `module-loader.php` extended to `references|namespaces|next|previous` in addition to edges, contexts, hashtags, folders, qa.
- **Integration file:** Added `lupo-includes/modules/nav/semantic_navbar.php` as canonical PHP integration entry (delegates to semantic-navbar-js.php). Route `nav/semantic_navbar` added so references to semantic_navbar.php resolve.
- **Session documentation cleanup:** `lupo-docs/channels/developer/dev/AUTH_INTEGRATION_CHECKS_3.0.8.md` and `AUTH_TESTING_CHECKLIST_3.0.8.md` updated to describe Session Model A (identity via `$GLOBALS['lupo_session']->getActorId()`, not `$_SESSION['actor_id']`).
- **Audit doc:** `lupo-docs/status/WINDSURF_FULL_AUDIT_4.0.70_4.0.71_CORRECTIONS.md` updated with remediation status for TOONs, API, and semantic_navbar.php.

#### lupopedia.init documentation and prerequisite doctrine (4.0.71)

- **Prerequisite doctrine:** Documented required reading order for anyone working with `lupopedia.init`. Prerequisites include LUPOPEDIA HEADERS, versioning doctrine, directory structure, agent & faucet doctrine, and (recommended) semantic graph & collections doctrine.
- **New files:** Added `lupo-docs/doctrine/init/LUPO_INITIALIZATION_DOCTRINE.md` (authoritative prerequisite list, rationale per doctrine, definition of `lupopedia.init`, warning about misunderstanding headers/versioning). Added `lupo-docs/INIT_README.md` ("Before You Read This File", required reading list with why each is required, link to full init doctrine).
- **lupo-docs/README.md:** Replaced duplicate/wrong headers with single LUPOPEDIA HEADERS block; added "Required reading before using Lupopedia" section linking to INIT_README, LUPOPEDIA_HEADERS, and LUPO_INITIALIZATION_DOCTRINE; clarified that lupopedia.init is not the first file to read.
- **Root README.md:** Added section "Required Reading Before Using Lupopedia" with links to INIT_README, LUPOPEDIA_HEADERS, and LUPO_INITIALIZATION_DOCTRINE; explained correct reading order and that Lupopedia is doctrine-driven and header-driven; noted that lupopedia.init is not the first file to read.
- **LUPOPEDIA HEADERS:** All new and updated documentation use valid LUPOPEDIA HEADERS (first line `---`, single front matter block, identity line after closing `---`); file_path_from_root and web_path set; no FLARE references in new content (canonical name LUPOPEDIA HEADERS).

---

### [4.0.70] — Version bump, upgrade verification (2026-03-12)

#### Summary

Version bump after 4.0.69 release pushed to GitHub. Pending tasks for this cycle are recorded in **4.0.71** (Runtime and compatibility, Pending tasks). This entry documents the database documentation program and human manual upgrade verification for the 4.0.x line.

#### Database Documentation

Multi-agent database documentation program completed. **Cursor** (acting KIRO) produced schema coordination and validation; **JetBrains** documented knowledge, collections, departments, and artifact tables; **Antigravity** documented federation, Anubis, uploads, and channel filesystem tables; **Windsurf** documented legacy and migration tables. Canonical table docs live in `lupo-docs/database/lupopedia/tables/active/` (181 files), providing 100% coverage of active `lupo_*` tables in the `install_new_lupopedia.sql` schema. Key domains documented include:
- **Core System**: actors, channels, metadata, governance (moved from flat tables/ to active/).
- **Identity & Auth**: lupo_auth_users, lupo_auth_providers, and full agent registry.
- **Session & API**: lupo_sessions, recovery, tokens, clients, and rate limits.
- **Federation & Filesystem**: lupo_federation_nodes/categories, lupo_anubis_* (log, events, queue), and artifact/upload storage.
- **Collections & Knowledge**: lupo_collections, tabs, truth system, and content graphs.
- **Sync & Consensus**: lupo_multi_agent_critique_sync, lupo_registry_open.
Schema registry: `lupo-docs/database/lupopedia/SCHEMA_REGISTRY.md`. Validation report: `lupo-docs/database/lupopedia/tables/VALIDATION_REPORT.md`.

#### Migration Documentation

All **34 livehelp_*** legacy Crafty Syntax tables and their corresponding `*_migration.md` files (63 files total) have been consolidated into `lupo-docs/database/lupopedia/tables/migrations/`. These are linked via `lupo-docs/database/lupopedia/tables/MIGRATION_MAPPING_REFERENCE.md`. Legacy → lupo_* mappings (e.g. livehelp_users → lupo_auth_users/lupo_actors, livehelp_transcripts → lupo_dialog_threads/lupo_dialog_messages) are fully documented. DROPPED legacy tables are noted in the mapping; they are preserved as Migration status in the registry but do not exist in the active Lupopedia schema.

#### Deprecated Tables

Tables documented under `lupo-docs/database/lupopedia/tables/deprecated/`: **lupo_anubis_deletion_log**, **lupo_anubis_orphaned**, **lupo_anubis_mirrored**, **lupo_anubis_revised**, **lupo_registry_import**, **lupo_reference_cited_by**, **lupo_reference_objects**, **lupo_modules_departments**, **lupo_federated_trust**, **lupo_federation_discovery**, **lupo_flip_artifacts**. Removed table **lupo_operators** is documented as DROPPED in operator_to_roles_migration; no TOON. Legacy livehelp_* tables that are DROPPED (e.g. livehelp_messages, livehelp_sessions) are documented in migration mapping only; they are Migration status in the registry, not deprecated/ in the repo.

#### Schema Corrections

- **TOON path:** Canonical TOON location is `lupo-docs/toons/` (221 files); directive referenced `lupo-database/lupopedia/toon/`; registry and validation use `lupo-docs/toons/`.
- **Canonical placement:** When both flat `tables/<table>.md` and `active/<table>.md` exist, `active/` is treated as canonical; flat docs preserved (no delete).
- **Domain boundaries:** lupo_auth_audit_log (governance), lupo_bans_log (ACL/audit), lupo_capability_usage vs lupo_permissions (usage vs policy) documented in VALIDATION_REPORT and CURSOR_KIRO_HANDOFF; no schema change.
- **Uncertain tables:** lupo_actor_properties, lupo_file_index, lupo_headers have no TOON; referenced in plan or mapping; left as Uncertain in registry until verified.

#### Validation Summary

- **Coverage:** 100% of the 221 TOON tables covered; 187 Active (lupo_*), 34 Migration (livehelp_*).
- **Consolidation:** 181 active `lupo_*` docs moved to `active/`; 63 `livehelp_*` and migration docs moved to `migrations/`.
- **Historical Cleanup:** 11 stale/removed tables identified and moved to `deprecated/`. Redundant non-prefixed table docs moved to `deprecated/`.
- **Orphans:** No orphan table docs remain in root; `README`, `TABLE_INDEX`, `MIGRATION_MAPPING_REFERENCE`, `CURSOR_KIRO_HANDOFF`, and system overviews (actors, channels) are verified as intentional indices.
- **Header/format:** FLARE headers standardized across all newly documented files; system version 4.0.71 applied to registry and validation documents.

#### Human manual task: Upgrade from Crafty Syntax 3.7.5 to Lupopedia 4.0.71

- **Objective:** Verify that the only supported upgrade path (Crafty Syntax 3.7.5 → Lupopedia 4.0.x) produces a working 4.0.71 install.
- **Steps:**
  1. Start from a clean Crafty Syntax 3.7.5 database (or load `old_crafty_syntax_3_7_5_start.sql` and legacy config as required).
  2. Run the Lupopedia install wizard (`install.php`).
  3. Complete upgrade flow: identity normalization (if upgrade), install SQL, seeds, import, drop old tables, write config.
  4. Confirm application runs as Lupopedia 4.0.71; verify actors, channels, dialog tables, and core features.
- **Note:** There is no Lupopedia→Lupopedia upgrade until 4.1.0. This task validates the Crafty 3.7.5 → 4.0.71 path only.

---

### [4.0.69] — Orchestration, Traits, Authorization, Documentation Coherence (2026-03-11)

#### Summary

Version 4.0.69 focuses on actor orchestration architecture, doctrine alignment, session infrastructure, and documentation coherence. This release finalizes the Actor–Faucet model, introduces traits and authorization enforcement, and unifies documentation so users clearly understand that **actors orchestrate** the system while **faucets execute** tasks.

#### Core Architecture

- **Actor–Faucet ontology finalized**
  - Actors represent identity and orchestration logic.
  - Faucets represent execution surfaces (Cursor, Kiro, Antigravity, API).
  - IDE agents are faucets, not independent actors.

- **Identity Layers Doctrine implemented**
  - Actor = identity | Faucet = execution surface | Session = runtime state | Trait = intrinsic actor constraint | Role = channel-scoped permission | Task = ephemeral work item.

- **Orchestration clarification**
  - Actors orchestrate agents and faucets across channels.
  - Faucets execute code or reasoning on behalf of actors.

#### Database & Schema Changes

Canonical schema is defined in `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`.

New and updated tables include: `lupo_actor_traits`, `lupo_action_authorization`, `lupo_edge_type_definitions`, `lupo_dialog_messages`, `lupo_sessions`, `lupo_federation_nodes`, `lupo_agent_faucets.faucet_class`, `lupo_collections` (channel_id, is_nav_menu, nav_icon), `lupo_collection_tabs` (actor_id, visibility_rule, tab_type).

Key additions:

- **Actor traits** — `lupo_actor_traits`: intrinsic actor capabilities and constraints.
- **Action authorization** — `lupo_action_authorization`: controls which actors may perform specific actions.
- **Edge vocabulary** — `lupo_edge_type_definitions`: canonical edge relationships for the semantic graph.
- **Faucet traceability** — `lupo_dialog_messages`: `source_faucet_slug`, `source_faucet_instance_id`; `lupo_sessions`: `faucet_slug`, `faucet_instance_id`.
- **Collections as resource bundles** — `lupo_collections`: `channel_id`, `is_nav_menu`, `nav_icon`; `lupo_collection_tabs`: `actor_id` (was user_id), `visibility_rule`, `tab_type`. Enables channel sidebar and top-level nav menus; formalized `item_type` in tab map (artifact, content, url, path).

#### Dialog System Consolidation

- **Removed:** `lupo_threads`, `lupo_messages`.
- **Canonical tables:** `lupo_dialog_channels`, `lupo_dialog_threads`, `lupo_dialog_messages`.
- **Migration:** `database/migrations/20260310_remove_duplicate_thread_message_tables.sql`.

#### Actor ID Rebase

- **Human actor range:** Threshold changed from 10000+ to **1000+**. Humans rebased to 1000+; IDE faucets in 100–199; registry and CLI updated.

#### Authorization Enforcement

- **TraitEnforcer.php** — Checks actor traits; validates action authorization; enforces channel role permissions. Example: `dialog.send_message`; unauthorized actions return HTTP 403.

#### Session Infrastructure

- **Session files:** `lupo-database/sessions/{session_id}.md` (e.g. `L-LUPO-ROOT-CURSOR.md`). Session block: `lupopedia.session` with runtime context for IDE faucets.
- **Utilities:** `scripts/validate_session_consistency.php`, `scripts/session_custodian.php`.

#### Doctrine Additions

- **New:** TRAITS_DOCTRINE.md, EDGE_TYPE_SEMANTICS_DOCTRINE.md, AUTHORIZATION_DOCTRINE.md, FAUCET_TRACEABILITY_DOCTRINE.md, FEDERATION_NODE_TYPES_DOCTRINE.md, COLLECTIONS_DOCTRINE.md.
- **Spec:** WEB_NAVIGATION_ARCHITECTURE.md (global nav, channel sidebar, tab paths, item types).
- **Updated:** IDENTITY_LAYERS_DOCTRINE.md, COMMUNICATION_DOCTRINE.md, ActorFaucetOntology.md.

#### Documentation Coherence

- All docs state clearly: **Actors orchestrate. Faucets execute.** Updated: README.md, AGENTS.md, IDENTITY_LAYERS_DOCTRINE.md, ActorFaucetOntology.md, COMMUNICATION_DOCTRINE.md, cursor_actors_channels_semantic_architecture_4.0.69.md, brainstorm_on_actors_and_channels.md.
- **Canonical architecture:** `lupo-docs/architecture/` — HOW_ACTORS_ORCHESTRATE_ON_CHANNELS.md, cursor_actors_channels_semantic_architecture_4.0.69.md; docs/status has redirect/canonical notes.

#### Collections, Tabs, and Navigation

- **Channel-scoped resource bundles:** Collections gain `channel_id`, `is_nav_menu`, `nav_icon`; tabs gain `actor_id` (replacing user_id), `visibility_rule`, `tab_type`. CollectionTabsService: `getCollectionsForNavMenu()`, `getCollectionsForChannel($channelId)`; tab map item_type: artifact, content, url, path.
- **Migration:** `database/migrations/20260312_collections_tabs_navigation_4_0_69.sql`. Doctrine: COLLECTIONS_DOCTRINE.md; spec: WEB_NAVIGATION_ARCHITECTURE.md.

#### Status & Review Reports

Multiple IDE agents produced architecture reviews:

- ANTIGRAVITY_WOLFIE_IMPLEMENTATION_REVIEW_4_0_69.md
- KIRO_ORCHESTRATION_IMPLEMENTATION_REVIEW_4_0_69.md
- CURSOR_IMPLEMENTATION_CORRECTIONS_FROM_JETBRAINS_AND_ANTIGRAVITY_4.0.69.md
- CURSOR_IMPLEMENTATION_UPDATE_FROM_MULTI_IDE_REVIEWS_4_0_69.md
- CURSOR_COLLECTIONS_TABS_NAVIGATION_IMPLEMENTATION_4.0.69.md
- ORCHESTRATION_ACTORS_AND_SUPPORTING_ACTORS_REVIEW_4.0.69.md
- CURSOR_4_0_69_DOCUMENTATION_COHERENCE_CORRECTIONS.md

These confirm doctrine alignment and schema correctness.

#### Tooling

- `scripts/check_doc_schema_consistency.py` — Documentation ↔ schema verification.
- `scripts/validate_session_consistency.php` — Session drift detection.
- `scripts/session_custodian.php` — Optional session file audit/correct.
- `scripts/sync_root_rules_to_cursor.php` — IDE rule synchronization.

#### Repository Strategy

- **Development:** github.com/wisdomoflovingfaith/lupopedia through 4.1.0.
- **Planned canonical org:** github.com/lupopedia (core, web, cli, vercel, docs, ops). Migration planned for 4.1.0.

---

### [4.0.68] — Rules, Skills, Uploads (2026-03-10)

#### Summary

Introduced rules engine, skills system, and path/visit analytics doctrine. Major components: Rules system (`lupo_rules`, `lupo_rule_targets`), Skills system (`lupopedia.skills`), LUPOPEDIA HEADERS protocol, Paths/Visits analytics redesign.

#### Rules system (4.0.68)

- **Database:** `lupo_rules`, `lupo_rule_targets`, `lupo_rule_logs` (migration `database/migrations/20260310_create_rules_tables.sql`; install in `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`). Rule IDs explicit; targets/logs use AUTO_INCREMENT for their PKs.
- **Seed:** `lupo-database/lupopedia/mysql/seed/seed_rules_doctrine_4.0.68.sql` — five core database rules and attachments to Channel 42; **explicit `rule_target_id`** (1–5) in INSERTs to satisfy schema (no default value).
- **Channel 42:** `lupo-channels/42/content/federation_node_id/0/RULES.md` — database rules doctrine for Channel 42.
- **Engine:** `lupo-includes/classes/RuleEngine.php`, `lupo-includes/classes/RuleEvaluator.php`.
- **CLI:** `php lupo-bin/lupo.php rules --check [target_table] [target_id]`, `rules --evaluate [target_table] [target_id] [context_json]`.
- **Docs:** `lupo-docs/doctrine/RULES_DOCTRINE.md`; `docs/HELP.md` (rules commands and Rules system section).

#### Rule files (4.0.68)

| File | Purpose |
|------|---------|
| `lupo-rules/skills/lupopedia-headers.md` | Skill rule: Lupopedia Headers, min_proficiency intermediate (LUPOPEDIA header format). |

#### Skills system (4.0.68)

- **Doctrine:** `lupo-docs/doctrine/SKILLS_DOCTRINE.md` — `lupopedia.skills` header, directory structure (`lupo-skills/`, actor `skills/*.md`), proficiency levels, **header format** (`---` first, then YAML, then `# file: ...` as first content line).
- **SkillService:** `lupo-includes/classes/SkillService.php` — getActorDir (id/slug), getActorSkills, hasSkill (min proficiency), getSkillDetails; parse `lupopedia.skills` from profile and `skills/*.md`.
- **Seed:** `lupo-database/lupopedia/mysql/seed/seed_skills_4.0.68.sql` — skill metadata and actor–skill attachment in `lupo_metadata` (metadata_id 10201–10205).
- **CLI:** `php lupo-bin/lupo.php skills --actor [actor_id]`, `skills --check [actor_id] <skill_name> [min_proficiency]`; skills command does not require DB.
- **Docs:** `docs/HELP.md` (skills commands and Skills system subsection).

#### Skill files (4.0.68)

| File | Purpose |
|------|---------|
| `lupo-skills/README.md` | Skills index (lupopedia-headers, uploads). |
| `lupo-skills/lupopedia-headers/README.md` | Lupopedia Headers skill: format, blocks, proficiency levels, usage. |
| `lupo-skills/lupopedia-headers/examples/basic-header.md` | Basic LUPOPEDIA header example. |
| `lupo-skills/uploads/README.md` | **Uploads skill:** canonical entities, upload layout, auth_users namespace, date partitioning, hash naming, schema notes. |
| `lupo-actors/1/skills/lupopedia-headers.md` | Actor 1 (WOLFIE) — Lupopedia Headers skill at master. |
| `lupo-actors/wolfie/skills/lupopedia-headers.md` | WOLFIE — Lupopedia Headers skill (same, slug path). |
| `lupo-channels/42/content/federation_node_id/0/SKILLS.md` | **Channel 42 skills:** uploads skill (intermediate); `lupopedia.skills` for channel scope 42. |

#### Header format correction (4.0.68)

- **Canonical format:** First line of file = `---`; then YAML blocks; then closing `---`; then `# file: {title} — session: ... — delegation: ... — web_path: ...` as the first content line. The identity line is **not** at the very top of the file.
- **Updated to this format:** `lupo-docs/doctrine/SKILLS_DOCTRINE.md`, `lupo-skills/README.md`, `lupo-actors/wolfie/skills/lupopedia-headers.md`, `lupo-actors/1/skills/lupopedia-headers.md`; doctrine and examples in `lupo-skills/lupopedia-headers/README.md` and `examples/basic-header.md`.

#### Metadata and other seeds (4.0.68)

- **CHANGELOG headers in lupo_metadata:** `lupo-database/lupopedia/mysql/seed/seed_lupo_metadata_changelog_headers_4.0.68.sql` — root + lupopedia.headers + lupopedia.footer block rows for CHANGELOG.md (entity_type `lupopedia_header`, entity_id 1; metadata_id 10001–10021).
- **Skills metadata:** `lupo-database/lupopedia/mysql/seed/seed_skills_4.0.68.sql` — skill "lupopedia-headers" and attachment to Actor 1 in `lupo_metadata`.

#### Paths and visits (4.0.68) — doctrine-aligned consolidation

- **Design:** Paths = aggregated navigation flows (low-volume); visits = raw per-event logs (high-volume, append-only). gc.php aggregates unprocessed visits into paths; then marks visits as is_processed. No session/actor/instance on paths; visits are session- and actor-aware.
- **Removed tables:** lupo_analytics_visits, lupo_analytics_visits_daily, lupo_analytics_visits_monthly, lupo_analytics_paths; previous lupo_visits (content_id/page_url/date_ymd style) replaced.
- **lupo_paths:** path_id, entercontentid, exitcontentid, enter_table, exit_table, year_num, month_num, day_num, count_num, transition_type, transition_metadata, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis.
- **lupo_visits:** visit_id, session_id, actor_id, instance_id, path_url, entercontentid, exitcontentid, enter_table, exit_table, transition_type, transition_metadata, created_ymdhis, is_processed, is_deleted, deleted_ymdhis.
- **Install:** install_new_lupopedia.sql updated. **Migration:** database/migrations/20260310_paths_visits_doctrine.sql (one-time). **Crafty import:** import_from_old_crafty_syntax.sql updated for new lupo_visits/lupo_paths schema.

#### v4.0.68 review fixes (TOON-based validation, no information_schema)

- **No information_schema:** All schema validation uses **SHOW TABLES**, **SHOW CREATE TABLE**, and **TOON files** only.
- **Rule 1002 — No Information Schema Queries:** New constraint rule attached to Channel 42. Forbidden patterns: `information_schema`, `INFORMATION_SCHEMA`. Allowed: SHOW TABLES, SHOW CREATE TABLE, TOON files. Document: `lupo-channels/42/content/federation_node_id/0/NO_INFORMATION_SCHEMA_RULE.md`. Seed: rule_id 1002 and rule_target_id 6 in `seed_rules_doctrine_4.0.68.sql`.
- **ToonValidator.php:** getDatabaseTables (SHOW TABLES), getTableStructure (SHOW CREATE TABLE), loadToonFile (lupo-docs/toons/*.toon.json), checkForeignKeys/checkTriggers/checkTimestampColumns/checkAutoIncrement by parsing DDL; validateDatabase() returns per-table results. No information_schema usage.
- **RuleEngine:** checkInformationSchemaViolations() scans lupo-includes PHP files for forbidden patterns; constraint rule with forbidden_patterns triggers this check in evaluateRule().
- **RuleEvaluator:** Uses ToonValidator for checkDatabaseSchema(); checkInformationSchemaUsage() delegates to RuleEngine. For evaluateRules('database', 0) adds results['schema'] and results['information_schema'].
- **Rule file format:** `lupo-rules/skills/lupopedia-headers.rule` renamed to `lupopedia-headers.md` with LUPOPEDIA header format.
- **Header format fixes:** `lupo-docs/doctrine/RULES_DOCTRINE.md` and `lupo-channels/42/content/federation_node_id/0/RULES.md` updated so first line is `---`, then YAML, then `---`, then `# file: ...` as first content line.
- **Version:** LUPEDIA_VERSION and lupo.php fallbacks set to 4.0.68.

#### Root rules for actor 1 (lupo-rules/root) (4.0.68)

- **lupo-rules/root/:** Rule .md files with LUPOPEDIA headers — php-5-6-compatibility, no-laravel-no-middleware, pdo-db-database-access-doctrine, migration-doctrine, database-logic-prohibition-doctrine, flip-doctrine (redirects to LUPOPEDIA HEADERS), toon-source-of-truth, reserved-id-doctrine, versioning-doctrine-single-source, pk-reference-naming-doctrine, required-tables-future-features-doctrine, wheeler-reverse20-ban, stoned-wolfie-schrodinger-ban, quantum-state-uncertainty-ban, experimental-ai-artifact-ban, single-install-no-4.0-upgrade-doctrine.
- **flip-doctrine:** Content replaced with redirect to LUPOPEDIA HEADERS doctrine (README, FORMAT, PLAN, VALIDATORS_AND_TOOLING); describes storage in `lupo_metadata` and writing headers to the file.
- **Seed:** `seed_actor_1_cursor_rules_4.0.68.sql` — inserts into `lupo_metadata` for entity_type='actor', entity_id=1, meta_type='root_rule', property_key=slug (16 rules), metadata_id 10301–10316.
- **README:** `lupo-rules/root/README.md` — index of all root rules and seed reference.

#### Single-install no 4.0 upgrade doctrine (4.0.68)

- **Rule:** No Lupopedia→Lupopedia upgrade until 4.1.0; all 4.0.x from Crafty Syntax 3.7.5 only. All database changes in install SQL + main seed; consolidate 4.0.x migrations into install; no backwards compatibility between 4.0.x versions.
- **Files:** `.cursor/rules/single-install-no-4.0-upgrade-doctrine.mdc`, `lupo-rules/root/single-install-no-4.0-upgrade-doctrine.md`; seed row for actor 1 (metadata_id 10316).

#### LUPOPEDIA HEADERS documentation updates (4.0.68)

- **AGENTS.md:** Updated from FLARE/FLIP to LUPOPEDIA HEADERS; outbound_edges to LUPOPEDIA_HEADERS/README.md; "FLIP Headers" section renamed to "LUPOPEDIA HEADERS".
- **docs/HELP.md:** "FLARE protocol" section renamed to "LUPOPEDIA HEADERS protocol"; table links to LUPOPEDIA_HEADERS/README.md, LUPOPEDIA_HEADERS_FORMAT.md, VALIDATORS_AND_TOOLING.md.
- **CHANGELOG.md:** purpose and outbound_edges updated to LUPOPEDIA HEADERS doctrine.

#### Project root atom (4.0.68)

- **lupo-config/global_atoms.yaml:** Added `LUPOPEDIA_PROJECT_ROOT` for path resolution; paths in file_path_from_root, see_also_from_root, and outbound_edges are relative to project root.
- **NO_INFORMATION_SCHEMA_RULE.md:** See Also links fixed; added `see_also_from_root` in YAML.

#### 4.0.68 reconciliation (Cursor directive 20260310)

- **Installer seed alignment:** `install.php` runs 4.0.68 seeds in order after base seeds: `seed_rules_doctrine_4.0.68.sql`, `seed_skills_4.0.68.sql`, `seed_lupo_metadata_changelog_headers_4.0.68.sql`, `seed_actor_1_cursor_rules_4.0.68.sql`. Seeds run in bootstrap (upgrade), new-install, and post–content-seed paths; each file run only if present (idempotent).
- **Rule evaluation pipeline:** CLI `rules --evaluate` uses `RuleEvaluator` (not `RuleEngine` directly). Full pipeline: CLI → RuleEvaluator → RuleEngine → validators. For target `database`/`0`, schema and information_schema checks appended to results and printed. Invalid `rule_script` (JSON decode failure) reported with rule name and error.
- **information_schema scanner:** `RuleEngine::checkInformationSchemaViolations()` excludes files whose path or basename contains `RuleEngine`, `RuleEvaluator`, or `ToonValidator`. Comment text stripped before scanning.
- **ToonValidator:** AUTO_INCREMENT no longer reported as per-table violation; triggers reported once globally (`_triggers_global`). DDL regex checks use comment-stripped SQL.
- **CHANGELOG metadata seed:** `seed_lupo_metadata_changelog_headers_4.0.68.sql` updated to match current CHANGELOG.
- **SkillService:** Actor slug resolution uses DB then filesystem registry then static fallback. Parser for `lupopedia.skills` tolerates `\r\n`, optional spaces around colons, quoted/unquoted values.
- **Paths/visits:** Schema confirmed in `install_new_lupopedia.sql`; import and migration unchanged.

#### Files created or modified in 4.0.68 (summary)

**Migrations / install / seeds:** `database/migrations/20260310_create_rules_tables.sql`, `database/migrations/20260310_paths_visits_doctrine.sql`, `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`, `lupo-database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql`, `seed_rules_doctrine_4.0.68.sql`, `seed_skills_4.0.68.sql`, `seed_lupo_metadata_changelog_headers_4.0.68.sql`, `seed_actor_1_cursor_rules_4.0.68.sql`.

**Rule files:** `lupo-rules/skills/lupopedia-headers.md`, `lupo-channels/42/content/federation_node_id/0/NO_INFORMATION_SCHEMA_RULE.md`, `lupo-rules/root/*.md` (16 rules), `lupo-rules/root/README.md`.

**Skill files:** `lupo-skills/README.md`, `lupo-skills/lupopedia-headers/README.md`, `lupo-skills/lupopedia-headers/examples/basic-header.md`, `lupo-skills/uploads/README.md`, `lupo-actors/1/skills/lupopedia-headers.md`, `lupo-actors/wolfie/skills/lupopedia-headers.md`, `lupo-channels/42/content/federation_node_id/0/SKILLS.md`.

**Channel 42 content:** `lupo-channels/42/content/federation_node_id/0/RULES.md`, `lupo-channels/42/content/federation_node_id/0/NO_INFORMATION_SCHEMA_RULE.md`.

**PHP:** `install.php`, `lupo-includes/classes/RuleEngine.php`, `lupo-includes/classes/RuleEvaluator.php`, `lupo-includes/classes/ToonValidator.php`, `lupo-includes/classes/SkillService.php`, `lupo-bin/lupo.php`.

**Doctrine / docs:** `lupo-docs/doctrine/RULES_DOCTRINE.md`, `lupo-docs/doctrine/SKILLS_DOCTRINE.md`, `docs/HELP.md`, `AGENTS.md`, `lupo-config/global_atoms.yaml`, `.cursor/rules/flip-doctrine.mdc`, `.cursor/rules/single-install-no-4.0-upgrade-doctrine.mdc`, `docs/status/cursor_4_0_68_reconciliation_report.md`.

#### Windsurf Full Cross-Agent Audit of Cursor + Antigravity (4.0.71, 2026-03-12)

- **Comprehensive cross-agent audit completed:** Full validation of Cursor and Antigravity changes in versions 4.0.70 and 4.0.71, including semantic navbar, session model, schema consistency, and documentation.
- **Session Model A validated as excellent:** DB-backed session authority perfectly implemented; no legacy `$_SESSION['actor_id']` usage in active code; proper CSRF handling and session rotation.
- **Critical TOON-source-of-truth violations identified:** 9 semantic navbar tables exist in SQL and documentation but lack TOON files (lupo_paths_summary, lupo_reference_map, lupo_collection_links, lupo_collection_map, lupo_edge_types, lupo_edge_map, lupo_questions, lupo_answers, lupo_question_map).
- **Semantic navbar implementation well-architected:** Premium floating navbar with glassmorphic design, proper API endpoints, and lazy-loading popovers; missing some endpoints (references, namespaces, next, previous).
- **Documentation program achievement outstanding:** 221 TOON tables with 100% coverage; proper categorization and cross-agent coordination maintained.
- **Overall grade: B- with critical action items:** Excellent architectural work undermined by doctrine violations; immediate TOON file generation required.
- **Comprehensive audit report published:** Complete technical validation, risk assessment, and recommendations in `lupo-docs/status/WINDSURF_FULL_AUDIT_4.0.70_4.0.71_CORRECTIONS.md`.

---

# LUPOPEDIA FOOTER STARTS HERE

lupopedia.footer:
  version: "4.0.72"
  last_verified: "20260312"
  last_verified_by: "wolfie"
  orchestrator: "cursor"
  archive_note: "For historical changelog entries from 4.0.67 and earlier, see CHANGELOG_ARCHIVE.md"
  next_action:
    - "Add next_action to any new 4.0.72 subsection entries"
    - "Verify version and last_verified align with release"
    - "Keep required reading and doctrine links current"
