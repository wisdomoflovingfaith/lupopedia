---
# FLIP Header
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/INITIALIZATION_PROMPT_4_0_21.md
file.last_modified_system_version: "4.0.21"
file.last_modified_utc: "20260220000000"
# channel_id: 51 (Doctrine Council)
---

# Lupopedia 4.0.21 — Initialization Prompt
# Date: 2026-02-20

You are starting development on **Lupopedia version 4.0.21**.  
This is an **initialization prompt only**.  
Do NOT modify any files until explicitly instructed (e.g., "Do T1").

============================================================
1. 4.0.21 SCOPE (AUTHORITATIVE)
============================================================

4.0.21 is a **test-only release**. Purpose:

- **Full end-to-end upgrade testing** — Crafty Syntax 3.7.5 → Lupopedia 4.0.21
- **Full admin UI testing** — All admin sections, handlers, permissions, CSRF, diagnostics
- **Full channel UI testing** — Channel list, channel views, channel-scoped actions
- **Full thread UI testing** — Thread list, thread views, thread-scoped actions
- **Full multi-user + multi-agent interaction testing** — Actors, roles, channels, agents
- **Full diagnostics validation** — T2 admin diagnostics (permission_check, csrf, session, admin_action); JSONL logs; rotation
- **Full seed validation** — Seed SQL, reserved IDs, Stoned Wolfie identities
- **Full importer validation** — import_from_old_crafty_syntax.sql; identity normalization; operator channels
- **Full Stoned Wolfie behavior validation** — AI (actor_id 420) and human (stonedwolfie@lupopedia.com) banned identities in seed and upgrade

**NO new features. NO schema changes. NO routing/resolver changes. NO admin UI redesign. NO channel/thread redesign.**

All work is validation, test execution, and documentation of correct behavior. The 4.0.20 stack (routing, resolver, caching, Smart 404, Ban-at-Gate, admin UI, channel UI, thread UI) remains unchanged.

============================================================
2. DOCTRINE SOURCES YOU MUST USE
============================================================

Load and apply **before any work**:

**Versioning and upgrade**
- docs/doctrine/VERSIONING_DOCTRINE.md — Canonical version 4.0.20 → 4.0.21 bump; patch-only; Crafty 3.7.5 → Lupopedia 4.0.x only
- docs/doctrine/INSTALLATION_PATH_DOCTRINE.md — Subdirectory install; LUPOPEDIA_PATH, LUPOPEDIA_PUBLIC_PATH; no hardcoded paths

**Session, security, admin**
- docs/doctrine/SESSION_DOCTRINE.md — Session binding (cookie only); ban enforcement (ANUBIS / Ban-at-Gate)
- docs/doctrine/SECURITY_DOCTRINE.md — If present; else infer from lupo-includes/functions/security.php, CSRF, auth
- docs/doctrine/ADMIN_DOCTRINE.md — If present; else infer from admin.php, AdminUsersHandler, admin layouts, permissions

**Channels and threads**
- docs/channels/doctrine/ — Channel registry, channel UI, channel-scoped roles (reference)
- docs/channels/doctrine/ORCHESTRATOR_DOCTRINE.md — If present
- Thread behavior — Infer from thread-related handlers and lupo_contents / channel/thread tables

**FLIP and file identity**
- docs/doctrine/FLIP/FLIP_DOCTRINE.md — File-Level Inference Protocol; infer only from FLIP Headers; no guessing

**Reserved ID and actor/user/agent mapping**
- .cursor/rules/reserved-id-doctrine.mdc — Explicit IDs for lupo_actors, lupo_channels, lupo_auth_users; no AUTO_INCREMENT for registry-backed tables; SELECT/UPDATE or INSERT with explicit ID
- Actor/User/Agent mapping — System/AI actors 0–9999; human actors ≥ 10000; lupo_actors, lupo_auth_users, lupo_agents linkage

**Installer and migration**
- docs/doctrine/MIGRATION_DOCTRINE.md — Two-place rule (install_new_lupopedia.sql + migration file); TOONs as schema source; wizard does not run migration SQL; Crafty 3.7.5 → Lupopedia only
- docs/doctrine/INSTALLATION_PATH_DOCTRINE.md — Install path and config

**4.0.20 diagnostics and testing**
- docs/diagnostics/4.0.20_ADMIN_TESTING.md — Admin diagnostics (T2): permission_check, csrf, session, admin_action; JSONL; rotation at 1MB; flock; regression and adversarial overview
- tests/regression/ — Structure and coverage (admin, auth, session, legacy, csrf, permissions, installer)
- tests/adversarial/ — StonedWolfieHarness, run.php, sanity_test.php, vectors, results/YYYY-MM-DD.jsonl
- scripts/run_unit_tests.sh, run_regression_tests.sh, run_tests.sh, run_adversarial_tests.sh

**Banned and experimental artifacts (do not reintroduce)**
- .cursor/rules/wheeler-reverse20-ban.mdc — No Wheeler, Reverse-20, WHEELER_MODE
- .cursor/rules/stoned-wolfie-schrodinger-ban.mdc — No STONED WOLFIE persona/subsystem, no schrodingers_state
- .cursor/rules/quantum-state-uncertainty-ban.mdc — No quantum-state, uncertainty-metadata
- .cursor/rules/experimental-ai-artifact-ban.mdc — No experimental AI artifacts, cosmic/psychedelic/joke-doctrine
- **Exception:** "Stoned Wolfie" as **banned test identities** (AI actor_id 420, human stonedwolfie@lupopedia.com) for adversarial harness only — present in seed and wizard; not a persona or workflow.

Continue to obey:
- PHP 5.3 compatibility (no ??, no [], no return types in core)
- PDO only (PDO_DB wrapper); no raw query()/exec()
- No DB-side logic (no FKs, triggers, DEFAULT CURRENT_TIMESTAMP)
- Reserved ID doctrine for actors, channels, auth_users
- No changes to routing, resolver, caching, Smart 404, or Ban-at-Gate (4.0.20 stack)
- TOON source of truth: docs/toons/ and install_new_lupopedia.sql, seed_lupopedia.sql

============================================================
3. MIGRATION SOURCES YOU MUST USE
============================================================

**Canonical SQL (database/migrations/)**
- import_from_old_crafty_syntax.sql — Crafty 3.7.5 → Lupopedia data and table mapping; required tables
- install_new_lupopedia.sql — Full schema for fresh install; required tables only
- seed_lupopedia.sql — Seed data; version; reserved channels (0, 1, 42, 51); Stoned Wolfie AI (actor_id 420) and human (actor_id 10001 for fresh install)
- drop_old_crafty_syntax_tables.sql — Optional drop of livehelp_* after import
- future_features_lupopedia.sql — Non-required tables only

**One-time / legacy (database/migrations_legacy/)**
- migration_auth_users_username_255.sql — username varchar(255); idempotent
- Other one-time patches — Wizard does NOT run these

**Mapping rules**
- 34 legacy livehelp_* tables → Lupopedia target tables (see import_from_old_crafty_syntax.sql and docs referencing CRAFTY_SYNTAX_TO_LUPOPEDIA)
- Department/group/channel/thread mapping — lupo_departments, lupo_channels, lupo_contents; operator workspace → channel semantics
- User/actor/agent mapping — livehelp_users → lupo_actors, lupo_auth_users; operator → actor_id ≥ 10000; system/AI 0–9999
- Identity normalization — Email unique per operator; username → slug; applied before import

**TOON files (docs/toons/)**
- Canonical table/column definitions; regenerate from install SQL via scripts/generate_toon_from_sql.py when schema changes
- Example: lupo_auth_users.toon.json (username varchar(255), etc.)

============================================================
4. UI + INTERACTION SOURCES YOU MUST USE
============================================================

**Admin UI**
- admin.php — Entry; section=users, channels, agents, departments, leads, settings, etc.
- lupo-includes/classes/AdminUsersHandler.php, AdminChannelsHandler.php, etc.
- lupo-includes/themes/default/layouts/admin_layout.php, admin_sections/*.php
- Auth: app/auth/AuthService.php, Session.php, AuthRoleResolver.php; lupo-includes/functions/auth-helpers.php
- Diagnostics: lupo-includes/functions/admin_diagnostics.php — lupo_diag_write, lupo_diag_permission_check, lupo_diag_csrf, lupo_diag_session, lupo_diag_admin_action

**Channel UI**
- Channel list and channel-scoped views (per channel doctrine and existing handlers)
- lupo_channels, lupo_actor_channel_roles, lupo_departments

**Thread UI**
- Thread list and thread-scoped views; lupo_contents and thread/channel linkage

**Multi-agent / multi-user**
- Multiple actors (human + AI); roles per channel; captain/administrator; agent slots (e.g. 420 for Stoned Wolfie AI)

**Test infrastructure (4.0.20)**
- Unit: tests/unit/ (e.g. admin_diagnostics.php, admin_users_handler.php)
- Regression: tests/regression/admin/, auth/, session/, legacy/, csrf/, permissions/, installer/
- Integration: tests/integration/*.sh (curl-based)
- Adversarial: tests/adversarial/StonedWolfieHarness.php, run.php, sanity_test.php; results/ JSONL

============================================================
5. SPECIAL ENTITIES YOU MUST INCLUDE
============================================================

**Stoned Wolfie (AI agent, banned)**
- actor_id: **420** (fixed; system/AI range 0–9999)
- lupo_agents.agent_id 420, lupo_actors.actor_id 420, lupo_auth_users (is_active=0), lupo_banned_actors
- Present in seed_lupopedia.sql (idempotent INSERTs) and in upgrade path via InstallWizardBannedIdentities::ensureStonedWolfieBannedIdentities() after import/seed

**stonedwolfie@lupopedia.com (human banned user)**
- actor_id: next free ≥ 10000 (seed uses 10001 for fresh install; upgrade uses MAX(actor_id)+1)
- lupo_actors, lupo_auth_users, lupo_banned_actors
- Same seed block and wizard step as above

**Validation in 4.0.21**
- Confirm both identities exist after fresh install (seed) and after upgrade (wizard)
- Confirm adversarial harness can reference them (banned access tests)
- No schema drift; no new Stoned Wolfie–related tables or columns

============================================================
6. TASK ORDER FOR 4.0.21 (T1–T8)
============================================================

When instructed to begin implementation, follow this order:

**T1** — Version bump to 4.0.21  
Update all canonical version locations per VERSIONING_DOCTRINE.md §8: config/global_atoms.yaml, lupo-includes/version.php, install.php fallback, load_atoms.php fallback, install_wizard_classes.php docblock, seed_lupopedia.sql @lupo_version/@lupo_version_code, VERSIONING_DOCTRINE.md current version and FLIP header.

**T2** — Upgrade path validation  
Document and verify full Crafty Syntax 3.7.5 → Lupopedia 4.0.21 upgrade: drop tables, load old_crafty_syntax_3_7_5_start.sql (or equivalent), restore Crafty config.php, delete lupopedia-config.php, run install.php; steps: credentials → bootstrap → normalize → confirm → run → config. No code changes to importer or wizard logic unless a bug is found and fix is scoped to test-only.

**T3** — Admin UI testing  
Execute and document admin UI tests: all sections (users, channels, agents, departments, leads, settings), AdminUsersHandler and other handlers, permissions, CSRF, diagnostics (T2 logs). Use existing regression and integration tests; add or adjust only test scripts, not admin code.

**T4** — Channel UI testing  
Execute and document channel UI tests: channel list, channel views, channel-scoped roles. Use existing tests where present; no channel UI redesign.

**T5** — Thread UI testing  
Execute and document thread UI tests: thread list, thread views, thread-scoped actions. No thread UI redesign.

**T6** — Multi-user and multi-agent validation  
Verify multi-actor interaction, roles (captain, administrator), agent slots, Stoned Wolfie banned identities (420, stonedwolfie@lupopedia.com) in seed and after upgrade. Run adversarial harness; confirm pass/fail expectations.

**T7** — Diagnostics and seed validation  
Validate admin diagnostics (permission_check, csrf, session, admin_action) and JSONL rotation. Validate seed: reserved channels (0, 1, 42, 51), Stoned Wolfie AI and human, lupo_auth_users.username 255, no schema drift.

**T8** — Finalization and CHANGELOG  
Update CHANGELOG.md with 4.0.21 section (test-only; no features/schema/routing/UI changes). Confirm all tests pass; document any known gaps. Ready for tag and push when instructed.

Do NOT begin any task until explicitly instructed.

============================================================
7. RULES AND CONSTRAINTS
============================================================

- **No new features** — 4.0.21 adds no user-facing features.
- **No schema changes** — install_new_lupopedia.sql, seed_lupopedia.sql, TOONs unchanged except version strings.
- **No routing/resolver/caching changes** — Use 4.0.20 stack as-is.
- **No admin UI redesign** — Test existing admin; fix only bugs that block testing if explicitly scoped.
- **No channel/thread redesign** — Test existing channel and thread UI only.
- **No Ban-at-Gate logic changes** — Test existing behavior only.
- **Test-only release** — Validation, test runs, documentation, and version bump only.
- **Reserved ID doctrine** — All inserts into lupo_actors, lupo_channels, lupo_auth_users use explicit IDs; no lastInsertId() for these.
- **Single upgrade path** — Crafty Syntax 3.7.5 → Lupopedia 4.0.x only; no Lupopedia→Lupopedia upgrade in 4.0.x.

============================================================
8. OUTPUT EXPECTATIONS
============================================================

- All task outputs must be consistent with the doctrines listed in §2.
- Test results (pass/fail/skip) must be reported clearly; failures must be categorized (environment, missing dependency, code bug).
- CHANGELOG 4.0.21 entry must state: test-only; full upgrade testing; full admin/channel/thread testing; full diagnostics and seed validation; Stoned Wolfie validation; no new features, no schema changes, no routing/UI changes.

============================================================
9. YOUR FIRST OUTPUT
============================================================

Your first output **must** be:

1. **Confirmation** that you have loaded the doctrine sources listed in §2 (and that you will use the migration and UI sources in §3–§4).
2. **Summary** of the 4.0.21 scope (test-only; upgrade, admin, channel, thread, diagnostics, seed, importer, Stoned Wolfie validation; no features/schema/routing/UI changes).
3. **List** of test directories and scripts you will use (tests/unit, tests/regression, tests/integration, tests/adversarial; scripts/run_*.sh).
4. **Readiness statement:**  
   "Awaiting instruction (e.g., 'Do T1')."

Do NOT modify any files yet.

============================================================
END OF INITIALIZATION PROMPT
============================================================
