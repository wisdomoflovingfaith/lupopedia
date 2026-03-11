---
lupopedia.headers:
  lupopedia.version: "4.0.69"
  lupopedia.schema: "documentation"
  file_path_from_root: "CHANGELOG.md"
  system_version: "4.0.69"
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
---
# Lupopedia CHANGELOG

This document tracks version history, focusing on key changes, task migrations, and optimizations. Entries are in reverse chronological order.

**Archive Note:** For historical changelog entries from 4.0.67 and earlier, see [CHANGELOG_ARCHIVE.md](CHANGELOG_ARCHIVE.md).

---

## Version History

### [4.0.69] — Orchestration, Traits, Authorization, Documentation Coherence (2026-03-11 → 2026-03-12)

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

- **CHANGELOG headers in lupo_metadata:** `lupo-database/lupopedia/mysql/seed/seed_lupo_metadata_changelog_headers_4.0.68.sql` — root + flare.headers + flare.footer block rows for CHANGELOG.md (entity_type `lupopedia_header`, entity_id 1; metadata_id 10001–10021).
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

- **lupo-rules/root/:** Rule .md files with LUPOPEDIA headers — php-5-3-compatibility, no-laravel-no-middleware, pdo-db-database-access-doctrine, migration-doctrine, database-logic-prohibition-doctrine, flip-doctrine (redirects to LUPOPEDIA HEADERS), toon-source-of-truth, reserved-id-doctrine, versioning-doctrine-single-source, pk-reference-naming-doctrine, required-tables-future-features-doctrine, wheeler-reverse20-ban, stoned-wolfie-schrodinger-ban, quantum-state-uncertainty-ban, experimental-ai-artifact-ban, single-install-no-4.0-upgrade-doctrine.
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
