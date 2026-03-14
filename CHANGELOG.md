---
lupopedia.init:
  required_reading:
    - path: "lupo-docs/INIT_README.md"
      reason: "Prerequisites and init doctrine"
    - path: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md"
      reason: "Header format and block order"
  required_context:
    - "Canonical version history; reverse chronological; install SQL is schema authority."

lupopedia.metadata:
  comment: "Snapshot of metadata for this file or entity at artifact creation."
  title:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "Lupopedia CHANGELOG", channel_id: 42, class_name: "lupopedia_metadata", created_ymdhis: 20260313000000, updated_ymdhis: 20260313000000 }
  description:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "Canonical version history for Lupopedia; reverse chronological order.", channel_id: 42, class_name: "lupopedia_metadata", created_ymdhis: 20260313000000, updated_ymdhis: 20260313000000 }
  keywords:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "changelog, version_history, lupopedia, v4.0.74", channel_id: 42, class_name: "lupopedia_metadata", created_ymdhis: 20260313000000, updated_ymdhis: 20260314000000 }
  author:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "wolfie", channel_id: 42, class_name: "lupopedia_metadata", created_ymdhis: 20260313000000, updated_ymdhis: 20260313000000 }
  orchestrator:
    - { schema_ref: "lupo_metadata", entity_type: "file", meta_type: "property", property_value: "cursor", channel_id: 42, class_name: "lupopedia_metadata", created_ymdhis: 20260313000000, updated_ymdhis: 20260313000000 }

lupopedia.comments:
  - { comment_id: 1, channel_id: 42, actor_id: 1, actor_name: "wolfie", faucet_id: 101, faucet_name: "windsurf", comment_text: "Excellent work on the 4.0.73 implementation! All priority tasks completed successfully. The comments system will enhance our documentation and collaboration capabilities.", comment_type: "comment", created_ymdhis: 20260313150000, updated_ymdhis: 20260313150000 }
  - { comment_id: 2, channel_id: 42, actor_id: 102, actor_name: "cursor", faucet_id: 102, faucet_name: "cursor", comment_text: "Second example comment for 4.0.73 to demonstrate multiple comment records in the lupopedia.comments block.", comment_type: "comment", created_ymdhis: 20260313151500, updated_ymdhis: 20260313151500 }

lupopedia.headers:
  lupopedia.version: "4.0.75"
  lupopedia.schema: "documentation"
  file_path_from_root: "CHANGELOG.md"
  system_version: "4.0.75"
  last_modified_utc: "20260314"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  faucet_name: "jetbrains_codex"
  artifact_type: "changelog"
  artifact_kind: "history"
  purpose: "Canonical version history for Lupopedia; reverse chronological order."

lupopedia.edges:
  comment: "Snapshot of outbound edges for CHANGELOG at artifact creation."
  meta: "Changelog; version history; core repo."
  outbound_edges:
    - { to: "README.md", type: "references", weight: 1.0 }
    - { to: "TODO.md", type: "references", weight: 0.9 }
    - { to: "CHANGELOG_ARCHIVE.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/status/implementation_cursor_audit_fixes.md", type: "references", weight: 0.8 }
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/OPTIONAL_BLOCKS.md", type: "references", weight: 0.8 }
  semantic_tags: ["changelog", "version_history", "core", "lupopedia"]
 
lupopedia.footer:
  archive_note: "For historical changelog entries from 4.0.67 and earlier, see CHANGELOG_ARCHIVE.md"
  version: "4.0.75"
  last_verified: "20260314"
  last_verified_by: "codex"
  orchestrator: "wolfie"
  next_action:
    - "Add next_action to any new 4.0.75 subsection entries"
    - "Verify version and last_verified align with release"
    - "Keep required reading and doctrine links current"
---
# Lupopedia CHANGELOG

Canonical version history for Lupopedia.  
Entries are listed **in reverse chronological order**.

Older entries (≤4.0.67) are archived in [CHANGELOG_ARCHIVE.md](CHANGELOG_ARCHIVE.md).

---

## Version History

---

## 4.0.75 — rules and updates to governance

**Release Date:** 2026-03-14

Version bump following 4.0.74 push to GitHub. All canonical version markers, atoms (`GLOBAL_CURRENT_LUPOPEDIA_VERSION`), `LUPEDIA_VERSION`, `version.php`, install wizard fallback, CHANGELOG, README, TODO, plan, and `lupo-docs/version.md` updated to 4.0.75. No schema or behavioral changes.

#### Canonical Lupopedia Rules System & IDE Rule Propagation
- **Root Rules Hardened**: Rewrote all 12 root rules in `lupo-rules/root/*.md` to use the new `lupopedia.rules` block. Each rule is explicitly tracked with a unique ID (e.g., `DB001`, `ARC002`), clear constraints, categories, and full provenance tracking.
- **Rule Propagation Pipeline**: Converted propagation logic to support `.cursor/`, `.kiro/`, and `.idea/` workflows. Created `lupo-scripts/propagate_agent_rules.php` generating IDE-specific XML/JSON/MDC outputs, ensuring all IDE agents strictly follow canonical Lupopedia root rules.
- **Doctrine Consolidation**: Replaced mentions of the outdated `sync_root_rules_to_cursor.php` script with the IDE-agent agnostic `propagate_agent_rules.php` across Markdown documents. Fixed assumptions that Cursor acts as the sole target for rules.
- **Contextual Operations Architecture**: Authored and propagated three new rules targeting multi-agent context:
  - `ACT001`: IDE Agent Identity, Auth Users, and Actor Pairing
  - `CTX001`: Context Boundaries (Channels, Federation & L-LUPO Offline Sessions)
  - `DB008`: Database Offline Fallback and Filesystem Sync

#### JetBrains (Codex) Rules Import Hardening (4.0.75)
- **Canonical Research Completed:** Reviewed `lupo-rules/root/` (15 rule files), `.kiro/specs/kiro-rules-import/*`, existing `.kiro/lupopedia_rules.json`, existing `.idea/lupopedia_rules.xml`, AGENTS.md, and root/docs references before implementation.
- **Propagation Parser Hardened:** Refactored `lupo-scripts/propagate_agent_rules.php` to parse `lupopedia.rules` from frontmatter deterministically (without relying on `lupopedia.footer` markers), with warning-based handling for malformed or missing rule fields.
- **JetBrains Target Isolation Added:** Added explicit target dispatch with `--target=idea` and `--target=jetbrains` (alias), while preserving `all`, `cursor`, and `kiro` behavior. JetBrains target writes only `.idea` artifacts.
- **JetBrains XML Output Expanded:** `.idea/lupopedia_rules.xml` now includes rule provenance and metadata fields (`source_path`, `category`, `status`) in addition to `id`, `text`, `enforcement`, and `scope`, with XML-escaped deterministic output.
- **Coverage Fix Validated:** JetBrains propagation now emits all 15 canonical rules (including `ACT001`, `CTX001`, and `DB008`) with command output: `Processed 15 root files; parsed 15 rules; warnings: 0; target: idea`.
- **Kiro Compatibility Alignment:** Shared root parsing and common rule struct fields remain aligned with Kiro (`id`, `text`, `enforcement`, `scope`); target-specific output format differences are preserved (`.kiro/*.json` vs `.idea/*.xml`).

#### Cursor Rules Import and Propagation (4.0.75)
- **Canonical Research Completed:** Reviewed all 15 root rules in `lupo-rules/root/`, `.kiro/specs/kiro-rules-import/design.md` and `requirements.md`, existing `.cursor/` and `.kiro/` artifacts, CHANGELOG.md (Antigravity/Kiro/JetBrains/Windsurf work), and `lupo-scripts/propagate_agent_rules.php` before implementation. Confirmed root rules as single source of truth; Cursor artifacts are derived outputs only.
- **Propagation Pipeline Hardened for Cursor:** Extended `write_cursor_outputs()` so `.cursor/lupopedia_rules.json` includes `source_path` and `slug` for each rule (provenance and enforcement test). `--target=cursor` already existed; Cursor target writes only to `.cursor/` and does not modify `.kiro/`, `.idea/`, or `.windsurf/`.
- **Cursor Artifacts Regenerated:** Propagation run emits all 15 canonical rules (including ACT001, CTX001, DB008) to `.cursor/lupopedia_rules.json` and `.cursor/rules/<slug>.mdc`. Output is deterministic and fully regenerated on each run.
- **Cursor Documentation:** Added `.cursor/README.md` with LUPOPEDIA HEADERS documenting canonical source (`lupo-rules/root/`), propagation command (`php lupo-scripts/propagate_agent_rules.php --target=cursor`), relationship to Kiro/Windsurf/JetBrains, and validation command.

#### Root README update for 4.0.75 (cross-agent review)
- **Cross-Agent Review:** Cursor reviewed CHANGELOG 4.0.75, Antigravity TOON path and .htaccess report, Kiro rules-import specs, and current agent propagation state before editing README.
- **README.md:** Updated root README to version 4.0.75; added **Canonical root rules** section (`lupo-rules/root/` as source of truth, all agents must follow, derived outputs only); added **New agent / web terminal agent onboarding** (must create and register actor, adopt root rules, no anonymous participation); added **Doctrine reminder** under Architecture (install SQL authoritative, TOON at `lupo-database/lupopedia/toon/`, no FKs/procedures, BIGINT UTC, etc.); aligned TOON path and `lupo-database/` security with Antigravity report. See [CURSOR_README_CROSS_AGENT_UPDATE_4_0_75.md](lupo-docs/status/CURSOR_README_CROSS_AGENT_UPDATE_4_0_75.md).

#### Actor registration checklist (4.0.75)
- **Canonical checklist added:** Cursor created [lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md](lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md) derived from TOON files (`lupo_actors.toon.json`), install SQL, seed files (`seed_actors_agents_4.0.45.sql`), and the actor registry (`lupo-database/lupopedia/actors/actor_id/registry.json`). The checklist defines who must register (new IDE agent, new web terminal agent), prerequisites (root rules, ACT001, reserved-id doctrine), identity fields, Step 1 (registry update, required), Step 2 (DB persistence when available), Step 3 (lupo-database fallback when DB unavailable, including optional CSV rehydration), and Step 4 (validation) with an activation boundary.
- **Fallback documented:** Checklist explicitly documents that when the live database is unavailable, registration is recorded in the registry file and optionally in `lupo-database/lupopedia/csv/lupo_actors.csv` in TOON-aligned structure for later rehydration; install SQL remains authoritative.
- **Root docs updated:** README.md “New agent onboarding” now points to the checklist; footer/next_actions consolidated and reference the checklist. AGENTS.md given a prominent link to the checklist and an outbound edge; existing Antigravity/Kiro/Windsurf/JetBrains entries unchanged.

#### Documentation hardening — Actor Registration Checklist (Lilith review integration, 4.0.75)
- **Canonical Actor Registration Checklist finalized:** Integrated Lilith review improvements (rated 9/10 completeness) into [lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md](lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md). Checklist verified against install SQL (`lupo_actors`: actor_name PRIMARY KEY, actor_id UNIQUE), TOON (`lupo_actors.toon.json`), and actor registry format (`registry.json`).
- **Improvements integrated:** (1) **actor_name generation guidance** — naming conventions table (IDE Agent `{slug}-ide`, System Tool `tool-{slug}`, Web Terminal `terminal-{slug}`, Human `user-{id}`) and rules (lowercase, hyphens, no spaces, no special characters). (2) **Troubleshooting section** — table for duplicate actor_id/actor_name, registry-not-committed, DB offline, paired_actor_id failures. (3) **Automation note** after Step 2 — run `php lupo-scripts/propagate_agent_rules.php --target=<your-agent>` to generate IDE rule files. (4) **actor_id vs actor_name clarification** — PRIMARY KEY (semantic identifier) vs UNIQUE actor_id (numeric identifier); 1:1 mapping. (5) **Rule ID quick reference** — ACT001, DB001, DB002, DB006, DB008 with document names and one-line summaries.
- **Checklist structure:** Restructured to final section order: Who must register → Prerequisites → Identity fields → Generating actor_name → Step 1 Registry → Step 2 DB (+ automation note) → Step 3 Fallback → Step 4 Validation → Troubleshooting → Activation boundary → Summary → References → Rule ID quick reference.
- **README.md:** next_action list consolidated (removed duplicate bullets); continues to point to Required Reading, lupo-rules/root/, and ACTOR_REGISTRATION_CHECKLIST.md. README and AGENTS.md reference the checklist for agent onboarding.

#### Antigravity / Google Rules Propagation Hardening (4.0.75)
- **Canonical Research Completed:** Researched `lupo-rules/root/` (15 total rules including new Contextual rules) and compared `.kiro`, `.cursor`, and `.windsurf` outputs. Confirmed no isolated `.google` or `.antigravity` target is justified by repository evidence, pivoting to shared pipeline hardening and documentation alignment.
- **Shared Propagation Hardened:** Extended `lupo-scripts/propagate_agent_rules.php` to include `source_path`, `slug`, `category`, and `status` in the generated `.kiro/lupopedia_rules.json` and `.windsurf/lupopedia_rules.json` to ensure full structural parity with Cursor outputs.
- **Kiro Implementation Gap Closed:** Implemented the `.kiro/rules/*.md` generation and `.kiro/README.md` fallback index mapping explicitly requested within Kiro's design document but previously unimplemented in the codebase.
- **Validation Pipeline Built:** Created `lupo-tests/unit/kiro_rules_enforcement.php` matching the structural integrity tests of `cursor_rules_enforcement.php` strictly enforcing the presence of corresponding rules and slugs in Kiro config. Validated PASS status for both environments.
- **TOON Source Path Unified:** Resolved `lupo-docs/toons/` drift by strictly mapping `lupo-scripts/generate_toon_from_sql.py` directly back to the canonical `lupo-database/lupopedia/toon/` output directory. Documented explicit intent inside updated `lupo-rules/root/toon-source-of-truth.md` (re-propagated globally). Removed dead `lupo-docs/toons` output footprint.
- **lupo-database HTACCESS Hardened:** Instantiated strict `.htaccess` Apache protections utilizing 2.2 (`Deny from all`) and 2.4 (`Require all denied`) syntax preventing local schema JSON, SQL, and generation internals from downloading via naked HTTP requests. Tested PHP core functionality to ensure `fopen/include` abilities natively remain functional.

## 4.0.74 — Documentation Consolidation & Architecture Clarification

**Release Date:** 2026-03-14

This version focuses on **documentation consolidation, architecture clarification, repository alignment, and schema/install readiness** following the 4.0.73 release. It includes schema and seed changes (lupo_projects, 12-table expansion) and path normalization; the primary goal is installation testing and upgrade validation from Crafty Syntax 3.7.5.

#### lupo_projects and seed wiring (4.0.74)
- **lupo_projects** table is in install SQL (`lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`): core registry for projects scoped by channel, orchestrator, and federation node (KIRO proposal; Captain directive).
- **seed_projects.sql** was created in `lupo-database/lupopedia/mysql/seed/` and is **wired into the installer** in four places: bootstrap run, upgrade run (after detect), new-install run, and main seed loop. The installer runs it as part of the 4.0.74 seed set.
- Table documentation: `lupo-docs/database/lupopedia/tables/active/lupo_projects.md`. SCHEMA_REGISTRY includes lupo_projects.

#### 12-table install expansion (2026-03-14)
Per directive 20260314 (Cursor planned-tables implementation), the following **12 tables** were moved from `future_features_lupopedia.sql` into `install_new_lupopedia.sql` and are now created on every fresh install:
- **P0 (core):** `lupo_aliases`, `lupo_legacy_content_mapping`, `lupo_reference_objects`, `lupo_reference_cited_by`
- **P1 (feature-support):** `lupo_search_index`, `lupo_documentation_frameworks`, `lupo_federated_trust`, `lupo_federation_discovery`
- **P2 (ops/audit):** `lupo_unified_log`, `lupo_anubis_operations`, `lupo_system_health_snapshots`, `lupo_hotfix_registry`

DDL was normalized to doctrine (no FKs/triggers, BIGINT UTC timestamps, PK naming). The same 12 tables were removed from the future-features file (annotated as moved). A one-time migration for existing installs was added: `lupo-database/lupopedia/mysql/migrations/migration_20260314_12_table_install_expansion_v4_0_74.sql`. All other planned tables in `future_features_lupopedia.sql` (embeddings, translations, session_recovery, channel_boot_log, persona, gov_*, actor-rule suites, emotional/task/kapu/mood suites, etc.) remain **deferred**.

#### Install SQL and Crafty Syntax upgrade (2026-03-14)
- **Install SQL:** `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` is the canonical schema and includes all 12 tables above (159 tables total). It is ready for **fresh install** and **Crafty Syntax 3.7.5 → Lupopedia** upgrade testing. The wizard runs this file from `LUPO_MYSQL_DIR` (lupo-database/lupopedia/mysql); seed files run from the same base path.
- **Installer script path:** Post–lupo-prefix directory rename, the background command enqueued by the install wizard was updated to use `lupo-scripts/import_channels_and_artifacts.py` (was `scripts/...`) so the post-install system-commands runner finds the script correctly.
- **Prefix/path and TOON corrections (directive 20260314):** `legacy/` documented as the intentional exception to the lupo- prefix rule (read-only legacy code; not renamed). Empty root `scripts/` directory removed. SCHEMA_REGISTRY and README updated: canonical table count = 159 (install-SQL-derived); TOON count discrepancy (230 vs 159) resolved in docs. See [CURSOR_4_0_74_PREFIX_PATH_AND_TOON_CORRECTIONS.md](lupo-docs/status/CURSOR_4_0_74_PREFIX_PATH_AND_TOON_CORRECTIONS.md).

#### Core Version Updates
- **Version bump:** Updated LUPEDIA_VERSION, version.php, install.php, lupo.php, configuration atoms (global_atoms.yaml, GLOBAL_IMPORTANT_ATOMS.yaml), documentation headers, and project documentation to 4.0.74.
- **Testing objective:** This version begins validation of two installation paths:
  - Fresh Lupopedia installation
  - Upgrade path from Crafty Syntax 3.7.5

#### Documentation Architecture Clarification
Major clarification of Lupopedia’s identity and execution model.
- **README.md fully rewritten and audited**
  - Clarified the distinction between:
    - Auth Users — humans stored in auth_users
    - Actors — identities within the Lupopedia system
    - Agents — AI configuration profiles
    - Faucets — execution surfaces (IDE agents, CLI, web interfaces)
  - Corrected repository paths to match the actual structure (`lupo-docs/*`).
  - Updated runtime lifecycle references (index.php, bootstrap.php, lupopedia-loader.php, module-loader.php).
  - Added architecture notes explaining seven IDE faucets and orchestration structure.
- **Header–Database bridge documentation expanded**
  - Clarified how `lupopedia.*` headers represent portable snapshots of database records.
  - Documented snapshot semantics for:
    - `lupopedia.metadata`
    - `lupopedia.edges`
    - `lupopedia.engagement`
  - Explained the role of headers as a file/database synchronization bridge enabling offline storage and federated synchronization.

#### Doctrine Additions
Three core doctrine documents were added to clarify Lupopedia architecture:
- `lupo-docs/doctrine/AUTH_USERS_ACTORS_AGENTS_FAUCETS_DOCTRINE.md`
- `lupo-docs/doctrine/LUPOPEDIA_HEADERS_AND_METADATA_BRIDGE.md`
- `lupo-docs/doctrine/FILESYSTEM_OBJECTS_AND_DATABASE_SNAPSHOTS.md`
These documents answer recurring IDE agent questions regarding identity separation, header semantics, and filesystem/database synchronization.

#### Repository Audit and Alignment
A full documentation audit was conducted to align narrative documentation with the actual repository structure and runtime architecture.
- **Audit artifacts created**
  - `report.md` — consolidated findings from multiple IDE agents
  - `plan.md` — unified implementation backlog (P0/P1/P2 priorities)
- **Evidence-based repository validation**
  - Verified filesystem counts and install SQL table definitions.
  - Removed speculative documentation claims and replaced them with source-of-truth references.

#### Multi-Agent Orchestration Structure
Formalized the orchestration model for IDE agents.
- **Cursor designated Lead Orchestration Actor**
  - actor_id: 102
  - Registry updated in: `lupo-database/lupopedia/actors/actor_id/registry.json`
- **Wolfie remains supporting orchestration actor**
  - actor_id: 1
- **Seven IDE faucets formally documented**
  - Kiro (100)
  - Windsurf (101)
  - Cursor (102)
  - Antigravity (103)
  - Warp (104)
  - Cascade (105)
  - Codex / JetBrains

#### Project Governance Updates
- **AGENTS.md refreshed**
  - Corrected Cursor actor ID
  - Added Lead Orchestration and IDE Faucets section
  - Updated documentation paths to `lupo-docs/*`
  - Added references to `plan.md` and `report.md`.
- **Root orchestration artifacts**
  - `plan.md` and `report.md` created to consolidate findings from:
    - `plan_kiro.md`
    - `plan_windsurf.md`
    - `plan_codex.md`
    - `report_kiro.md`
    - `report_windsurf.md`
    - `report_codex.md`
  - These files now serve as the canonical coordination artifacts for cross-agent development.

#### lupopedia.init doctrine clarified
- **Purpose:** `lupopedia.init` is defined as **required reading / required context** that must be read or understood **before** reading the current file (e.g. `required_reading:`, `required_context:`). It is **not** for file metadata (artifact_type, file_identity, namespace, domain, system_version); those belong in `lupopedia.headers` or `lupopedia.metadata`.
- **Docs updated:** [LUPO_INITIALIZATION_DOCTRINE.md](lupo-docs/doctrine/init/LUPO_INITIALIZATION_DOCTRINE.md), [INIT_README.md](lupo-docs/INIT_README.md), [LUPOPEDIA_HEADERS_FORMAT.md](lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md).
- **Root files:** `plan.md` and `report.md` now use `lupopedia.init` for required_reading/required_context only. P1 plan item added to migrate other files that currently use `lupopedia.init` for metadata.

#### lupopedia.next_actions (suggested next actions; was lupopedia.close)
- **Purpose:** **lupopedia.next_actions** is the optional block for **suggested next actions** after reading or using the file — the "after" counterpart to **lupopedia.init** (which lists what to read before). Use **lupopedia.next_actions** with a `next_actions:` list in new files; **lupopedia.close** is the legacy name (validators accept both).
- **Docs updated:** [LUPOPEDIA_HEADERS_FORMAT.md](lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md), [OPTIONAL_BLOCKS.md](lupo-docs/doctrine/LUPOPEDIA_HEADERS/OPTIONAL_BLOCKS.md), [LUPO_INITIALIZATION_DOCTRINE.md](lupo-docs/doctrine/init/LUPO_INITIALIZATION_DOCTRINE.md), [LUPOPEDIA_HEADERS README](lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md).
- **plan.md:** Added **lupopedia.next_actions** block with next_actions list; P1 item added for adopting next_actions (and migrating from close where used).

#### Plan/report critical fixes (init, actor refs, path drift, edges, validation)
- **lupopedia.init:** plan.md and report.md now use **path + reason** form for required_reading (doctrine supports simple list or path/reason). required_context text updated to bridge/edges and lead-orchestrator wording. LUPO_INITIALIZATION_DOCTRINE: optional path+reason format documented; **circular dependency** note added (INIT_README or HEADERS README as first entry point; do not list "this file" in its own init).
- **lupopedia.actor_references:** New optional block for plan/report files listing actor IDs from registry. plan.md and report.md include cursor: 102, wolfie: 1, kiro: 100, windsurf: 101, antigravity: 103, warp: 104, cascade: 105, codex: TBD. Documented in OPTIONAL_BLOCKS and LUPOPEDIA_HEADERS_FORMAT.
- **P0.5:** Consolidate documentation root (lupo-docs/ vs lupo-docs/) — decision, update references, optional symlinks. Elevated to P0.
- **P1.6:** Edge snapshot maintenance doctrine — when to regenerate lupopedia.edges; "Update when semantic relationships change significantly"; consider lupo-bin/update-edges.php. Added to OPTIONAL_BLOCKS under lupopedia.edges.
- **P1.7:** lupopedia.next_actions backward compatibility — validators accept both next_actions and close; **deprecation date for lupopedia.close: 4.1.0**. OPTIONAL_BLOCKS updated with timeline and validator behavior.
- **Validation / acceptance criteria:** New section in plan.md with P0/P1/P2 criteria (actor IDs match registry, path root resolved, init content only, edge maintenance doctrine, next_actions deprecation).

#### lupo_projects table and table ceiling (Captain directive 4.0.74)
- **lupo_projects:** Approved 4.0.74 schema addition (KIRO proposal, Captain directive). New core registry table added to [install_new_lupopedia.sql](lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql). Columns: project_id (PK, application-supplied), project_key, project_name, project_slug, description, channel_id, orchestrator_id, federation_node_id, status, project_type, metadata_json, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis. Unique (project_key, federation_node_id); indexes on channel_id, orchestrator_id, federation_node_id, status, is_deleted.
- **Seed:** [seed_projects.sql](lupo-database/lupopedia/mysql/seed/seed_projects.sql) added — Lupopedia core (project_id 1) and federation example (project_id 2); timestamps use @now. **Evidence:** seed file exists; it is **not** yet in the installer seed execution path (install.php); follow-up integration or manual run documented.
- **Table ceiling:** Captain directive — table ceiling is **advisory only**; schema expansion permitted when justified. [SYMBOL_OPERATOR_DOCTRINE.md](lupo-docs/channels/doctrine/SYMBOL_OPERATOR_DOCTRINE.md) updated accordingly.
- **Implementation report:** [CURSOR_IMPLEMENTATION_REPORT_4_0_74.md](lupo-docs/status/CURSOR_IMPLEMENTATION_REPORT_4_0_74.md) added (research, implementation, files changed, validation honesty). **TOON generation was not run** in that session; no claim of DB-generated TOON output. When TOON regeneration is needed: `python lupo-scripts/generate_toon_files.py` (from live DB) or `python lupo-scripts/generate_toon_from_sql.py` (from install SQL).
- **Schema registry and doctrine:** [SCHEMA_REGISTRY.md](lupo-docs/database/lupopedia/SCHEMA_REGISTRY.md) updated (lupo_projects row, header 4.0.74, source/authority wording). [lupo_projects.md](lupo-docs/database/lupopedia/tables/active/lupo_projects.md) table doc created.

#### KIRO late submission reviewed (Cursor lead)
- **KIRO_CHANGES_and_report.md** reviewed: KIRO delivered thread summary and 10 files (report_kiro, plan_kiro, README_kiro, CHANGELOG_kiro, README_UPDATED, SCHEMA_REGISTRY_KIRO, VALIDATION_REPORT_KIRO, KIRO_HANDOFF_RESPONSE, TABLE_INDEX_KIRO, KIRO_CHANGES_and_report). **Corrections:** (1) **KIRO actor_id** is **100** per [registry](lupo-database/lupopedia/actors/actor_id/registry.json) (document had 10000); KIRO_CHANGES_and_report.md updated to 100 throughout. (2) **lupopedia.edges** in that file given required `comment` for doctrine. **Accepted:** KIRO domain boundaries (KIRO_HANDOFF_RESPONSE) for coordination; canonical TOON location = lupo-database/lupopedia/toon/; schema truth = install SQL. report.md and plan.md updated with KIRO late submission section and links. All KIRO-authored files from this thread (report_kiro, plan_kiro, SCHEMA_REGISTRY_KIRO, VALIDATION_REPORT_KIRO, TABLE_INDEX_KIRO, KIRO_HANDOFF_RESPONSE) corrected to actor_id **100** and paired_actor_id 1000 per registry.

#### Cursor execution pass — P0/P1 repo alignment (2026-03-14)
- **Directive:** [lupo-prompts/cursor/20260314_cursor_execute_plan_4_0_74.md](lupo-prompts/cursor/20260314_cursor_execute_plan_4_0_74.md) created; Cursor executed P0 and direct P1 items per plan.md.
- **Path drift:** README.md and AGENTS.md — all content references `lupo-docs/` updated to **lupo-docs/** (HELP.md, CLI.md, DOCTOR_HEALTH_CHECK.md, TOON_REFERENCE.md, version.md, doctrine/, actors.md). No top-level `lupo-docs/` directory; lupo-docs/ is canonical.
- **lupopedia.init discipline:** README.md and CHANGELOG.md — removed file_identity, artifact_type, namespace, domain from init; replaced with **required_reading** (path + reason) and **required_context** only. Metadata retained in lupopedia.metadata / lupopedia.headers.
- **Actor ID:** CHANGELOG.md lupopedia.comments example — actor_id 1003 corrected to **102** (Cursor) per registry.
- **lupopedia.next_actions:** README.md given explicit **lupopedia.next_actions** block (canonical; lupopedia.close legacy).
- **Implementation report:** [CURSOR_IMPLEMENTATION_REPORT_4_0_74.md](lupo-docs/status/CURSOR_IMPLEMENTATION_REPORT_4_0_74.md) updated with this pass (files changed, validation, deferred).

#### Cursor Pass 3 — TOON alignment, seed integration, schema inventory (2026-03-14)
- **Directive:** [lupo-prompts/cursor/20260314_cursor_pass3_toon_seed_cleanup_4_0_74.md](lupo-prompts/cursor/20260314_cursor_pass3_toon_seed_cleanup_4_0_74.md) created; Cursor executed TOON path alignment, seed wiring, and schema inventory.
- **TOON script alignment:** `lupo-scripts/generate_toon_from_sql.py` — install path updated from `lupo-database/migrations/install_new_lupopedia.sql` to **lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql**; output path from `lupo-docs/toons/` to **lupo-database/lupopedia/toon/**.
- **Seed integration:** `seed_projects.sql` **wired into installer.** Added `seed_4_0_74` array in install.php in three places: bootstrap (after credentials), new-install run, and main run (after 4.0.69 seeds). Each run uses `is_file($path)` before executing.
- **TOON_REFERENCE.md:** Documented two workflows: (A) from install SQL → `generate_toon_from_sql.py` → lupo-database/lupopedia/toon/*.toon.json (in-repo set); (B) from live DB → `generate_toon_files.py` → lupo-database/lupopedia/json/ and toon/.
- **Schema inventory:** Added to [CURSOR_IMPLEMENTATION_REPORT_4_0_74.md](lupo-docs/status/CURSOR_IMPLEMENTATION_REPORT_4_0_74.md): install SQL table count (100), in-repo TOON count (230), authority vs derived, seed wiring status, coverage gap (lupo_projects.toon.json created when script is run).
- **Merge process:** plan.md P1 updated with explicit merge rule: faucet-specific files authoritative for domain; root canon maintained by Cursor; merges into root with attribution; no silent overwrite.
- **Implementation report:** Pass 3 section and Schema inventory table added; Unresolved list updated (TOON path and seed wiring marked resolved).
- **TOON regeneration:** `python lupo-scripts/generate_toon_from_sql.py` was run; 142 TOONs written to lupo-database/lupopedia/toon/ (includes lupo_projects.toon.json).

#### Antigravity schema refactor — 4.0.7x alignment (2026-03-14)
- **Unified schema refactor (install vs future_features):** Antigravity (actor_id 103) used a Python refactoring script to parse and reorganize DDL in both SQL files without altering intended schema structure.
  - **lupo_comments** and **lupo_hashtags:** Verified already in core install_new_lupopedia.sql (from Windsurf passes); stripped duplicate/planned entries from future_features_lupopedia.sql to avoid definition collisions.
  - **lupo_orchestrator_rules:** Migrated from future_features_lupopedia.sql and appended to install_new_lupopedia.sql so rule persistence is part of canonical installation.
- **Deprecation and cleanup:** **lupo_flare_headers** — schema definition dropped from future_features catalog; headers are synchronized into lupo_metadata and LUPOPEDIA HEADERS is canonical; table was redundant.
- **Consolidation:** **lupo_anubis_operations** — Replaced the four fragmented ANUBIS logging tables (lupo_anubis_deletion_log, lupo_anubis_mirrored, lupo_anubis_orphaned, lupo_anubis_revised) with a unified table in future_features_lupopedia.sql. **lupo_system_health_snapshots** — Replaced separate lupo_temporal_coherence_snapshots with an expanded single snapshot capability in future_features.
- **lupo_metadata retrofit:** Added **schema_ref** varchar(64) DEFAULT NULL column to install_new_lupopedia.sql (after class_name) for backward/forward compatibility with lupopedia.headers (e.g. schema_ref property).
- **TOON generation (Antigravity pass):** Script generate_toon_from_sql.py output path set to **lupo-docs/toons**; lupo-legacy/incorrect directory generation pruned; script run produced **147** Active Database TOONs. *(Note: Cursor Pass 3 had set output to lupo-database/lupopedia/toon/; lead orchestration to reconcile canonical TOON output path if needed.)*
- **Git:** Changes persisted in commit 3883871a: *"antigravity (google): Schema refactor for 4.0.7x alignment. Included orchestrator_rules in active schema, unified ANUBIS logs and system health snapshots, deprecated flare_headers, and corrected TOON generation path."*

#### P1 execution start — folder rename audit and table count doctrine (2026-03-14)
- **Directive:** [lupo-prompts/cursor/20260315_cursor_p1_execution_4_0_74.md](lupo-prompts/cursor/20260315_cursor_p1_execution_4_0_74.md) created; Cursor executed P1 Task 1 (folder audit) and P1 Task 3 (table count doctrine).
- **Folder rename audit:** [lupo-docs/status/FOLDER_RENAME_AUDIT_4_0_74.md](lupo-docs/status/FOLDER_RENAME_AUDIT_4_0_74.md) created. All 17 target directories (lupo-admin/, lupo-admin_sections/, lupo-api/, lupo-backups/, lupo-cache/, lupo-images/, lupo-install/, lupo-legacy/, lupo-meta/, lupo-prompts/, lupo-scripts/, lupo-templates/, lupo-tests/, lupo-tmp/, lupo-tools/, lupo-uploads/, lupo-views/) exist at root. Reference counts and risk levels documented; **no renames performed.** High-risk folders: lupo-admin/, lupo-api/, lupo-images/, lupo-install/, lupo-prompts/, lupo-scripts/, lupo-views/.
- **Table count doctrine:** [lupo-docs/doctrine/TABLE_COUNT_DOCTRINE.md](lupo-docs/doctrine/TABLE_COUNT_DOCTRINE.md) created. Canonical table count = 100 (from CREATE TABLE count in install_new_lupopedia.sql). Install SQL authority, TOON derived status, and advisory table ceiling documented.
- **README:** Database paragraph updated to cite TABLE_COUNT_DOCTRINE and canonical count (100) instead of "200+ tables".
- **Live DB TOON generation (P1 Task 2):** Blocked until DB available; documented in implementation report.
- **Implementation report:** Pass 4 — P1 execution start section added.

#### Next: Upgrade path test (Captain/Wolfie)
- **Planned test:** Drop all database tables, load a **Crafty Syntax 3.7.5** install (legacy schema and data), then run the Lupopedia installer to **upgrade to 4.0.74**. This validates the only supported upgrade path (Crafty 3.7.5 → Lupopedia 4.0.x) and confirms install + seed (including `seed_projects.sql`) and reserved channels. Results to be recorded in plan.md and report.md.

**Note:** All 4.0.74 entries above from other IDE agents (Kiro, Windsurf, Codex, Antigravity, etc.) are preserved. Cursor thread (this session) added: Cursor execution pass (P0/P1 repo alignment), Cursor Pass 3 (TOON alignment, seed integration, schema inventory), and P1 execution start (folder rename audit, table count doctrine). No existing 4.0.74 content was overwritten.

---

## Previous Versions

---

### [4.0.73] — LUPOPEDIA HEADERS expansion (2026-03-13)

- **Consolidation release (pre-4.1.0 migrations):** All schema previously represented in `lupo-database/lupopedia/mysql/migrations/` has been consolidated into the canonical install SQL (`install_new_lupopedia.sql`) and optional/future-features SQL (`future_features_lupopedia.sql`). **Migration replay is no longer the expected path before 4.1.0.** Supported setup paths remain: (1) **fresh Lupopedia install** (install + seed) and (2) **upgrade/import from original Crafty Syntax 3.7.5.** The migrations directory has been cleared; `lupo-database/lupopedia/mysql/migrations/README.md` documents the pre-4.1.0 doctrine. **v4.0.74** will focus on installer and Crafty-upgrade testing for both paths.
- **Install SQL:** Added `actor_name` and index `lupo_sessions_idx_actor_name` to `lupo_sessions` in install so fresh installs match the consolidated schema state.
- **Grouped outbound_edges (transferable edge storage):** Audited edge schema and header format for grouped edge categories (code, documentation, schema, runtime). **lupo_edges** already has **edge_category** (varchar(100)); no schema change. Header format: single **outbound_edges** object with category keys (e.g. `outbound_edges.code`, `outbound_edges.documentation`), each a list of `{ to, type, weight }`. Documented in LUPOPEDIA_HEADERS_FORMAT.md §2.1.6 and OPTIONAL_BLOCKS.md; FlareValidatorService accepts both flat and grouped forms (normalizes then validates); lupo_collections.md and audit doc updated to grouped example. Mapping: group key → **lupo_edges.edge_category** on import; export groups by edge_category to rehydrate YAML. Audit: `lupo-docs/status/EDGE_STRUCTURE_AUDIT_GROUPED_OUTBOUND_EDGES.md`. Validator edge types extended with `documents`, `related_table`, `api_reference`.
- **Cursor (4.0.73, this thread):** LUPOPEDIA_HEADERS_FORMAT and OPTIONAL_BLOCKS updated so **lupopedia.edges** and **lupopedia.engagement** both require **comment** and recommend **meta** (same convention). Created `lupo-collections/L-LUPO-CURSOR-RECENTFILES-V4_0_73.md` with lupopedia.edges and lupopedia.engagement (comment/meta snapshot). Updated `lupo_collections.md` to grouped **outbound_edges** (code: PHP/services/API/components; documentation: README, HOW_TO_USE_LUPOPEDIA, lupo_collection_tabs.md). Added **edge_category** usage note to `lupo_edges.md` for header sync/export. FlareValidatorService: **normalizeOutboundEdges()** and validation for flat or grouped **outbound_edges**; extended valid edge types. Audit doc `lupo-docs/status/EDGE_STRUCTURE_AUDIT_GROUPED_OUTBOUND_EDGES.md` added.
- **LUPOPEDIA HEADERS documentation update:** Formalized historical alias names (**FLARE**, **FLIP**, **WOLFIE**, **FLP**, **FLPH**, **CROP**, **FLAME**) in canonical documentation and updated deprecation notices.
- **Engagement Block:** Introduced `lupopedia.engagement` block (canonical order after `lupopedia.edges`) for tracking engagement metrics. Migration of `view_count`, `like_count`, and `share_count` from `lupopedia.footer` to `lupopedia.engagement` for better separation of metadata (headers) and engagement (metrics).
- **Snapshot Requirement:** Mandatory `comment` or `meta` property requirement added for `lupopedia.edges` and `lupopedia.engagement` blocks. This ensures clarity that these blocks represent a point-in-time snapshot of the database state at artifact creation; the database remains the authority for latest values.
- **Views Calculation:** Defined `views: x` as a calculated property within `lupopedia.engagement`, derived from site visit analytics.
- **Planning Database Documentation:** Generated 55 TOON documentation files in `lupo-docs/database/lupopedia/tables/active/planning/` representing planned tables from `future_features_lupopedia.sql`. This provides a documentation layer for database evolution and agent reasoning. Script: `lupo-scripts/generate_planning_toons.py`.
- **Antigravity Implementation Prompt:** Created `lupo-docs/prompts/20260313_create_planning_toon_files.md`, a reusable directive for regenerating planning schema documentation.
- **Development Table Reorganization:** Audited active tables against codebase references using `lupo-scripts/audit_and_move_dev_tables.py`; relocated 8 unreferenced TOON and documentation files to `lupo-docs/database/lupopedia/tables/active/development/` to clarify implementation status.
- **Release 4.0.73 Hub Collection:** Created a one-time migration (`lupo-database/migrations/20260313_release_4073_hub_collection.sql`) to establish a central "Release 4.0.73 Hub" collection with tabs for Core files, Headers Doctrine, and Agent Activity, based on common edit patterns across IDE agents.
- **Table Documentation Updates:** Updated `lupo-docs/database/lupopedia/tables/active/lupo_channels.md` and `lupo_edges.md` to version 4.0.73 standards, including automated discovery and population of PHP codebase references in `lupopedia.edges`.
- **Grouped Edge Schema Support:** Verified that `lupo_edges` in the install SQL correctly supports the `edge_category` column and index for grouped outbound edges (doctrine: group key maps to `edge_category`). Created a one-time safe migration (`lupo-database/migrations/20260313_add_edge_category_to_lupo_edges.sql`) using simple ALTER statements (avoiding `INFORMATION_SCHEMA` for shared host compatibility).
- **One-Time SQL Runner:** Created `lupo-scripts/run_one_time_sql.php`, a minimal, doctrine-aligned PHP script for running migrations on shared hosts. It handles idempotency by catching and skipping "Duplicate column/index" errors (soft-error strategy), allowing safe reruns without complex SQL logic.
- **Namespace Documentation Expansion:** Expanded Namespace Documentation to include `core`, `collection`, `content`, `session`, `agent`, `federation`, `org`, and `dialog` namespaces. Updated `lupopedia.headers` for 18 additional database table documentation files (including `lupo_actors`, `lupo_channels`, `lupo_metadata`, `lupo_registry`, `lupo_atoms`, `lupo_aliases`, `lupo_collections`, `lupo_contents`, `lupo_sessions`, `lupo_agents`, etc.) to version 4.0.73 with consistent `lupopedia.edges` and `lupopedia.engagement` snapshot blocks.
- **Auth Namespace Documentation:** Researched and implemented the "auth" namespace for database table documentation. Updated `lupo-docs/database/lupopedia/tables/active/lupo_auth_users.md`, `lupo_auth_providers.md`, and `active/development/lupo_auth_audit_log.md` with version 4.0.73 headers, `namespace: "auth"`, and mandatory `lupopedia.edges`/`lupopedia.engagement` snapshot metadata as per Antigravity governance standards.
- **README & Root Hardening:** Updated `README.md` to version 4.0.73; refined focus description to reflect edge schema hardening and shared-host SQL compatibility work.
- **DDL Doctrine Hardening:** Audited and corrected `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` to enforce database doctrine (removed forbidden `tinyint(1)` display widths in `lupo_edges` and verified schema consistency).
- **Database & Orchestrator Rules Audit:** Conducted a full audit of table documentation, TOON transferability, and orchestrator rule integration. Identified critical primary key documentation drift in `lupo_actors.md` and proposed the `lupo_orchestrator_rules` table for DB-canonical rule storage. Report: `lupo-docs/status/report_on_database_tables_antigravity.md`.
- **Orchestrator rules + lupopedia.metadata headers (Cursor):** All 17 files in `lupo-rules/root/` received **lupopedia.init** and **lupopedia.metadata** blocks. README.md, CHANGELOG.md, and new root TODO.md received **lupopedia.metadata** blocks. **lupo_orchestrator_rules** table added to future_features and one-time migration `lupo-database/migrations/20260313_lupo_orchestrator_rules.sql`; sync script `lupo-scripts/sync_orchestrator_rules_to_db.php` reads `lupo-rules/root/*.md` and upserts into the table. `lupo_actors.md` given **lupopedia.init** and **lupopedia.metadata** (PK remains actor_id per doctrine). Report: `lupo-docs/status/implementation_cursor_audit_fixes.md`.
- **lupopedia.metadata semantic correction (Cursor):** **lupopedia.metadata** was incorrectly implemented as a table-schema block (listing column names and SQL types). Corrected so that **lupopedia.metadata** represents a **snapshot of metadata rows/values** for the current file or entity, **grouped by property_key**, not the schema of `lupo_metadata`. Doctrine updated in `lupo-docs/doctrine/LUPOPEDIA_HEADERS/OPTIONAL_BLOCKS.md` (new section) and `LUPOPEDIA_HEADERS_FORMAT.md` (block list). All 18 affected files (lupo-rules/root/*.md, README.md, CHANGELOG.md, TODO.md, lupo_actors.md) now use the minimal valid form: `comment: "Snapshot of metadata for this file or entity at artifact creation."` when no metadata rows exist; when rows exist, structure is property_key → array of row-like objects (domain_id, schema_ref, entity_type, entity_id, meta_type, property_value, channel_id, etc.). Transferability: export from `lupo_metadata` into header; re-import from header into `lupo_metadata`. Do not list column datatypes in lupopedia.metadata.
- **Comments System (4.0.73):** Implemented `lupo_comments` table for commenting on artifacts, documents, and content with faucet traceability. Added `lupopedia.comments` header block to LUPOPEDIA HEADERS format, formatted like `lupopedia.metadata` with records pulled from the `lupo_comments` table. Table includes `comment_id`, `target_type`, `target_id`, `channel_id`, `actor_id`, `faucet_id`, `comment_text`, `comment_type`, `parent_comment_id` for threading, and standard timestamp/deletion fields. Created comprehensive documentation in `lupo-docs/database/lupopedia/tables/active/lupo_comments.md` and seed data in `seed_comments_4.0.73.sql`. Example comment from Wolfie orchestrator with Windsurf faucet added to CHANGELOG.md header.

### [4.0.72] — Version bump (2026-03-12)


- **Version bump:** Updated LUPEDIA_VERSION, version.php, install.php, lupo.php, lupo-config atoms (global_atoms.yaml, GLOBAL_IMPORTANT_ATOMS.yaml), CHANGELOG.md, and README.md to 4.0.72. No schema or behavioral changes; release follows 4.0.71 push to GitHub.
- **IDE agent required-reading prompt:** Added `lupo-prompts/20260313_ide_agent_4.0.72_required_reading.md`, a canonical ordered reading list (version context, core doctrine, changelog + pending tasks, channel 0/42 task indexes, schema/TOONs, audits, atoms, and rules) that every IDE agent must read before making changes in 4.0.72.
- **lupopedia.footer — orchestrator required:** `orchestrator:` added as required metadata in `lupopedia.footer`. Doctrine updated in LUPOPEDIA_HEADERS_FORMAT.md (required fields: `orchestrator`, `last_verified_by`, `next_action`, plus version/last_verified). CHANGELOG and lupo-prompts/20260313_ide_agent_4.0.72_required_reading.md footers updated; lupo-tools flare_header_template.txt and flare_apply.py now include `orchestrator`; OPTIONAL_BLOCKS.md table updated.
- **Windsurf audit prompt for gap check:** Added `lupo-prompts/20260313_windsurf_audit_4.0.69_4.0.71_gap_check.md`, instructing Windsurf to re-audit versions 4.0.69–4.0.71 (using CHANGELOG and Windsurf audit reports) and append any remaining gaps as tasks under the 4.0.72 “Still needing to be done” section.


### [4.0.71] — Lupopedia Synthesized Documentation Framework (2026-03-12)

**Summary of 4.0.71 changes (all agents):** Synthesized Documentation Framework and agent registrations; semantic navbar backend (tables, API, JS generator, TOONs, and Windsurf audit remediation); Session Model A (DB-backed sessions); FLARE → LUPOPEDIA HEADERS and PHP 5.3 → 5.6; FLARE/FLIP/FLP deprecation; LUPOPEDIA HEADERS file order and `next_action` in footer; lupopedia.init prerequisite doctrine and required reading; JetBrains domain documentation and TABLE_INDEX; configuration and directory normalization; Windsurf full cross-agent audit and subsequent TOON/API/integration remediation. See subsections below for per-topic detail.

- Implemented Lupopedia Synthesized Documentation Framework based on [lupo-docs/synthesized-framework.md](lupo-docs/synthesized-framework.md).
- Published Antigravity Database Documentation Discrepancy Report: [DATABASE_DOCUMENTATION_DISCREPANCY_REPORT_ANTIGRAVITY.md](lupo-docs/status/DATABASE_DOCUMENTATION_DISCREPANCY_REPORT_ANTIGRAVITY.md).
- Reorganized database table documentation: 178 active tables documented in `active/`, 16 deprecated tables in `deprecated/`, and 63 lupo-legacy/migration files in `migrations/`.

- Added canonical header enforcement to all new Markdown artifacts (synthesized.headers / LUPOPEDIA HEADERS).
- Created database schema: `lupo_documentation_frameworks` in `future_features_lupopedia.sql` and one-time migration `20260313_documentation_frameworks_synthesized_framework.sql`.
- Generated `lupo-scripts/query_edges.py` and `lupo-bin/query_edges.php` for live edge querying by namespace.
- Developed `lupo-scripts/migrate_legacy_docs.py` for batch adding headers to legacy documentation (Phase 2).
- Implemented `lupo-bin/antigravity_governance.php` for monitoring and rejecting non-compliant headers (Phase 4).
- Added `.cursor/rules/synthesized-documentation-header.mdc` for IDE validation on file creation (Phase 5).
- Registered example agents (Antigravity, Cursor, Windsurf, Kiro, JetBrains, Trae) with quadrant-based Markdown in `lupo-docs/synthesized/agent_registrations/`.
- Ensured concurrency support for IDE agents via namespaces and channels. Roadmap phases 1–5 advanced with initial stubs for schema, migration, and governance.

#### Semantic Navbar Backend Rebuild (4.0.71)

- **Audit Completion:** Performed authoritative audit of semantic navigation database requirements. Identified and corrected missing/incomplete table schemas for Edges, Contexts, Folders, Hashtags, and Q/A.
- **Database Expansion:** Implemented missing mapping and summary tables: `lupo_paths_summary`, `lupo_reference_map`, `lupo_collection_links`, `lupo_collection_map`, `lupo_edge_types`, `lupo_edge_map`, `lupo_questions`, `lupo_answers`, and `lupo_question_map`.
- **Authoritative Migration:** Created one-time migration `lupo-database/migrations/20260313_authoritative_semantic_navbar_rebuild.sql` and updated canonical `install_new_lupopedia.sql` with these tables.
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
- Updated installer SQL: replaced `lupo_sessions` with canonical Model A schema (session_id, actor_id, federation_node_id, ip_hash, ua_hash, csrf_token, last_activity_ymdhis, created_ymdhis, updated_ymdhis, name_key, is_named, metadata). One-time migration: `lupo-database/migrations/20260313_session_model_rewrite.sql` (drops legacy unified_sessions, sessions, session_data, lupo_sessions; creates new lupo_sessions).
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

- **One-time migrations on existing 4.0.x DBs** — Run `20260313_lilith_traits_authorization_faucet.sql` and `20260313_collections_tabs_navigation_4_0_69.sql` on databases installed before these changes; record in `lupo_schema_migrations`. Fresh installs get full schema from `install_new_lupopedia.sql` only.
- **Faucet traceability at runtime** — Ensure all message and session creation paths populate `source_faucet_slug` / `source_faucet_instance_id` and `faucet_slug` / `faucet_instance_id` from session/runtime where available.
- **Collections UI** — Wire global nav and channel sidebar to `getCollectionsForNavMenu()` and `getCollectionsForChannel($channelId)`; implement tab activation and item rendering for artifact/content/url/path.
- **SessionCustodian** — Optional: run `lupo-scripts/session_custodian.php` (and/or Antigravity governance) to audit/correct `lupo-database/sessions/*.md` (e.g. paired_actor_id drift).
- **Doc–schema consistency** — Run `lupo-scripts/check_doc_schema_consistency.py` periodically; consider integrating in CI or pre-commit.
- **TOON regeneration** — After applying migrations to a live DB, run `python lupo-scripts/generate_toon_files.py` so TOONs match current schema.
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
- **One-time migration:** Created `lupo-database/migrations/20260313_semantic_navbar_backend_update.sql` to add the six tables on existing DBs; fresh installs get them from install SQL.
- **Documentation:** Added `lupo-docs/database/lupopedia/tables/semantic_navbar/` with SEMANTIC_NAVBAR_TABLE_AUDIT_REPORT.md, SEMANTIC_NAVBAR_OVERVIEW.md, and per-table docs (lupo_references, lupo_reference_links, lupo_hashtags, lupo_hashtag_map, lupo_folders, lupo_folder_map). Created `lupo-docs/frontend/semantic_navbar.md` (API endpoints, SQL usage, data flow, icon→table mapping, external-site behavior, JS↔Lupopedia communication).

#### Documentation and headers (4.0.71)

- **LUPOPEDIA HEADERS on doctrine and table docs:** Added full LUPOPEDIA HEADERS (lupopedia.init, lupopedia.headers, lupopedia.edges, lupopedia.footer) to `lupo-docs/doctrine/SESSION_MODEL.md`. Replaced remaining "Documentation file with FLARE header applied" purpose strings with "Documentation file with LUPOPEDIA HEADERS applied" across `.md` files. Added identity line to `lupo_sessions.md` table doc. System prompt `lupo-actors/system/prompts/flare-header-scan.md` updated to LUPOPEDIA HEADERS scan; channel README and federation changelog updated to LUPOPEDIA HEADERS protocol.
- **CHANGELOG:** Added lupopedia.init block and "# LUPOPEDIA FOOTER STARTS HERE" section at end of file with repeat lupopedia.footer block for findability.

#### Windsurf Full Cross-Agent Audit of Cursor + Antigravity (4.0.71, 2026-03-12)

- **Comprehensive cross-agent audit completed:** Full validation of Cursor and Antigravity changes in versions 4.0.70 and 4.0.71, including semantic navbar, session model, schema consistency, and documentation.
- **Session Model A validated as excellent:** DB-backed session authority perfectly implemented; no legacy `$_SESSION['actor_id']` usage in active code; proper CSRF handling and session rotation.
- **Critical TOON-source-of-truth violations identified:** 9 semantic navbar tables exist in SQL and documentation but lack TOON files (lupo_paths_summary, lupo_reference_map, lupo_collection_links, lupo_collection_map, lupo_edge_types, lupo_edge_map, lupo_questions, lupo_answers, lupo_question_map).
- **Semantic navbar implementation well-architected:** Premium floating navbar with glassmorphic design, proper API endpoints, and lazy-loading popovers; missing some endpoints (references, namespaces, next, previous).
- **Documentation program achievement outstanding:** 221 TOON tables with 100% coverage; proper categorization and cross-agent coordination maintained.
- **Overall grade: B- with critical action items:** Excellent architectural work undermined by doctrine violations; immediate TOON file generation required.
- **Comprehensive audit report published:** Complete technical validation, risk assessment, and recommendations in `lupo-docs/status/WINDSURF_FULL_AUDIT_4.0.70_4.0.71_CORRECTIONS.md`.

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
- **TOON coverage:** Created 9 missing TOON files in `lupo-database/lupopedia/toon/` for semantic navbar tables: `lupo_paths_summary`, `lupo_reference_map`, `lupo_collection_links`, `lupo_collection_map`, `lupo_edge_types`, `lupo_edge_map`, `lupo_questions`, `lupo_answers`, `lupo_question_map`. Schema matches lupo-install/migration; doctrine-aligned (no FKs, no triggers).
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

- **TOON path:** Canonical TOON location is `lupo-database/lupopedia/toon/` (221 files); directive referenced `lupo-database/lupopedia/toon/`; registry and validation use `lupo-database/lupopedia/toon/`.
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
- **Migration:** `lupo-database/migrations/20260310_remove_duplicate_thread_message_tables.sql`.

#### Actor ID Rebase

- **Human actor range:** Threshold changed from 10000+ to **1000+**. Humans rebased to 1000+; IDE faucets in 100–199; registry and CLI updated.

#### Authorization Enforcement

- **TraitEnforcer.php** — Checks actor traits; validates action authorization; enforces channel role permissions. Example: `dialog.send_message`; unauthorized actions return HTTP 403.

#### Session Infrastructure

- **Session files:** `lupo-database/sessions/{session_id}.md` (e.g. `L-LUPO-ROOT-CURSOR.md`). Session block: `lupopedia.session` with runtime context for IDE faucets.
- **Utilities:** `lupo-scripts/validate_session_consistency.php`, `lupo-scripts/session_custodian.php`.

#### Doctrine Additions

- **New:** TRAITS_DOCTRINE.md, EDGE_TYPE_SEMANTICS_DOCTRINE.md, AUTHORIZATION_DOCTRINE.md, FAUCET_TRACEABILITY_DOCTRINE.md, FEDERATION_NODE_TYPES_DOCTRINE.md, COLLECTIONS_DOCTRINE.md.
- **Spec:** WEB_NAVIGATION_ARCHITECTURE.md (global nav, channel sidebar, tab paths, item types).
- **Updated:** IDENTITY_LAYERS_DOCTRINE.md, COMMUNICATION_DOCTRINE.md, ActorFaucetOntology.md.

#### Documentation Coherence

- All docs state clearly: **Actors orchestrate. Faucets execute.** Updated: README.md, AGENTS.md, IDENTITY_LAYERS_DOCTRINE.md, ActorFaucetOntology.md, COMMUNICATION_DOCTRINE.md, cursor_actors_channels_semantic_architecture_4.0.69.md, brainstorm_on_actors_and_channels.md.
- **Canonical architecture:** `lupo-docs/architecture/` — HOW_ACTORS_ORCHESTRATE_ON_CHANNELS.md, cursor_actors_channels_semantic_architecture_4.0.69.md; lupo-docs/status has redirect/canonical notes.

#### Collections, Tabs, and Navigation

- **Channel-scoped resource bundles:** Collections gain `channel_id`, `is_nav_menu`, `nav_icon`; tabs gain `actor_id` (replacing user_id), `visibility_rule`, `tab_type`. CollectionTabsService: `getCollectionsForNavMenu()`, `getCollectionsForChannel($channelId)`; tab map item_type: artifact, content, url, path.
- **Migration:** `lupo-database/migrations/20260313_collections_tabs_navigation_4_0_69.sql`. Doctrine: COLLECTIONS_DOCTRINE.md; spec: WEB_NAVIGATION_ARCHITECTURE.md.

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

- `lupo-scripts/check_doc_schema_consistency.py` — Documentation ↔ schema verification.
- `lupo-scripts/validate_session_consistency.php` — Session drift detection.
- `lupo-scripts/session_custodian.php` — Optional session file audit/correct.
- `lupo-scripts/propagate_agent_rules.php` — IDE rule synchronization.

#### Repository Strategy

- **Development:** github.com/wisdomoflovingfaith/lupopedia through 4.1.0.
- **Planned canonical org:** github.com/lupopedia (core, web, cli, vercel, docs, ops). Migration planned for 4.1.0.

---

### [4.0.68] — Rules, Skills, Uploads (2026-03-10)

#### Summary

Introduced rules engine, skills system, and path/visit analytics doctrine. Major components: Rules system (`lupo_rules`, `lupo_rule_targets`), Skills system (`lupopedia.skills`), LUPOPEDIA HEADERS protocol, Paths/Visits analytics redesign.

#### Rules system (4.0.68)

- **Database:** `lupo_rules`, `lupo_rule_targets`, `lupo_rule_logs` (migration `lupo-database/migrations/20260310_create_rules_tables.sql`; install in `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`). Rule IDs explicit; targets/logs use AUTO_INCREMENT for their PKs.
- **Seed:** `lupo-database/lupopedia/mysql/seed/seed_rules_doctrine_4.0.68.sql` — five core database rules and attachments to Channel 42; **explicit `rule_target_id`** (1–5) in INSERTs to satisfy schema (no default value).
- **Channel 42:** `lupo-channels/42/content/federation_node_id/0/RULES.md` — database rules doctrine for Channel 42.
- **Engine:** `lupo-includes/classes/RuleEngine.php`, `lupo-includes/classes/RuleEvaluator.php`.
- **CLI:** `php lupo-bin/lupo.php rules --check [target_table] [target_id]`, `rules --evaluate [target_table] [target_id] [context_json]`.
- **Docs:** `lupo-docs/doctrine/RULES_DOCTRINE.md`; `lupo-docs/HELP.md` (rules commands and Rules system section).

#### Rule files (4.0.68)

| File | Purpose |
|------|---------|
| `lupo-rules/skills/lupopedia-headers.md` | Skill rule: Lupopedia Headers, min_proficiency intermediate (LUPOPEDIA header format). |

#### Skills system (4.0.68)

- **Doctrine:** `lupo-docs/doctrine/SKILLS_DOCTRINE.md` — `lupopedia.skills` header, directory structure (`lupo-skills/`, actor `skills/*.md`), proficiency levels, **header format** (`---` first, then YAML, then `# file: ...` as first content line).
- **SkillService:** `lupo-includes/classes/SkillService.php` — getActorDir (id/slug), getActorSkills, hasSkill (min proficiency), getSkillDetails; parse `lupopedia.skills` from profile and `skills/*.md`.
- **Seed:** `lupo-database/lupopedia/mysql/seed/seed_skills_4.0.68.sql` — skill metadata and actor–skill attachment in `lupo_metadata` (metadata_id 10201–10205).
- **CLI:** `php lupo-bin/lupo.php skills --actor [actor_id]`, `skills --check [actor_id] <skill_name> [min_proficiency]`; skills command does not require DB.
- **Docs:** `lupo-docs/HELP.md` (skills commands and Skills system subsection).

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
- **Install:** install_new_lupopedia.sql updated. **Migration:** lupo-database/migrations/20260310_paths_visits_doctrine.sql (one-time). **Crafty import:** import_from_old_crafty_syntax.sql updated for new lupo_visits/lupo_paths schema.

#### v4.0.68 review fixes (TOON-based validation, no information_schema)

- **No information_schema:** All schema validation uses **SHOW TABLES**, **SHOW CREATE TABLE**, and **TOON files** only.
- **Rule 1002 — No Information Schema Queries:** New constraint rule attached to Channel 42. Forbidden patterns: `information_schema`, `INFORMATION_SCHEMA`. Allowed: SHOW TABLES, SHOW CREATE TABLE, TOON files. Document: `lupo-channels/42/content/federation_node_id/0/NO_INFORMATION_SCHEMA_RULE.md`. Seed: rule_id 1002 and rule_target_id 6 in `seed_rules_doctrine_4.0.68.sql`.
- **ToonValidator.php:** getDatabaseTables (SHOW TABLES), getTableStructure (SHOW CREATE TABLE), loadToonFile (lupo-database/lupopedia/toon/*.toon.json), checkForeignKeys/checkTriggers/checkTimestampColumns/checkAutoIncrement by parsing DDL; validateDatabase() returns per-table results. No information_schema usage.
- **RuleEngine:** checkInformationSchemaViolations() scans lupo-includes PHP files for forbidden patterns; constraint rule with forbidden_patterns triggers this check in evaluateRule().
- **RuleEvaluator:** Uses ToonValidator for checkDatabaseSchema(); checkInformationSchemaUsage() delegates to RuleEngine. For evaluateRules('database', 0) adds results['schema'] and results['information_schema'].
- **Rule file format:** `lupo-rules/skills/lupopedia-headers.rule` renamed to `lupopedia-headers.md` with LUPOPEDIA header format.
- **Header format fixes:** `lupo-docs/doctrine/RULES_DOCTRINE.md` and `lupo-channels/42/content/federation_node_id/0/RULES.md` updated so first line is `---`, then YAML, then `---`, then `# file: ...` as first content line.
- **Version:** LUPEDIA_VERSION and lupo.php fallbacks set to 4.0.68.

#### Root rules for actor 1 (lupo-rules/root) (4.0.68)

- **lupo-rules/root/:** Rule .md files with LUPOPEDIA headers — php-5-6-compatibility, no-laravel-no-middleware, pdo-db-database-access-doctrine, migration-doctrine, database-logic-prohibition-doctrine, flip-doctrine (redirects to LUPOPEDIA HEADERS), toon-source-of-truth, reserved-id-doctrine, versioning-doctrine-single-source, pk-reference-naming-doctrine, required-tables-future-features-doctrine, single-install-no-4.0-upgrade-doctrine.
- **flip-doctrine:** Content replaced with redirect to LUPOPEDIA HEADERS doctrine (README, FORMAT, PLAN, VALIDATORS_AND_TOOLING); describes storage in `lupo_metadata` and writing headers to the file.
- **Seed:** `seed_actor_1_cursor_rules_4.0.68.sql` — inserts into `lupo_metadata` for entity_type='actor', entity_id=1, meta_type='root_rule', property_key=slug (12 rules), metadata_id 10301–10311, 10316.
- **README:** `lupo-rules/root/README.md` — index of all root rules and seed reference.

#### Single-install no 4.0 upgrade doctrine (4.0.68)

- **Rule:** No Lupopedia→Lupopedia upgrade until 4.1.0; all 4.0.x from Crafty Syntax 3.7.5 only. All database changes in install SQL + main seed; consolidate 4.0.x migrations into install; no backwards compatibility between 4.0.x versions.
- **Files:** `.cursor/rules/single-install-no-4.0-upgrade-doctrine.mdc`, `lupo-rules/root/single-install-no-4.0-upgrade-doctrine.md`; seed row for actor 1 (metadata_id 10316).

#### LUPOPEDIA HEADERS documentation updates (4.0.68)

- **AGENTS.md:** Updated from FLARE/FLIP to LUPOPEDIA HEADERS; outbound_edges to LUPOPEDIA_HEADERS/README.md; "FLIP Headers" section renamed to "LUPOPEDIA HEADERS".
- **lupo-docs/HELP.md:** "FLARE protocol" section renamed to "LUPOPEDIA HEADERS protocol"; table links to LUPOPEDIA_HEADERS/README.md, LUPOPEDIA_HEADERS_FORMAT.md, VALIDATORS_AND_TOOLING.md.
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

**Migrations / install / seeds:** `lupo-database/migrations/20260310_create_rules_tables.sql`, `lupo-database/migrations/20260310_paths_visits_doctrine.sql`, `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`, `lupo-database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql`, `seed_rules_doctrine_4.0.68.sql`, `seed_skills_4.0.68.sql`, `seed_lupo_metadata_changelog_headers_4.0.68.sql`, `seed_actor_1_cursor_rules_4.0.68.sql`.

**Rule files:** `lupo-rules/skills/lupopedia-headers.md`, `lupo-channels/42/content/federation_node_id/0/NO_INFORMATION_SCHEMA_RULE.md`, `lupo-rules/root/*.md` (16 rules), `lupo-rules/root/README.md`.

**Skill files:** `lupo-skills/README.md`, `lupo-skills/lupopedia-headers/README.md`, `lupo-skills/lupopedia-headers/examples/basic-header.md`, `lupo-skills/uploads/README.md`, `lupo-actors/1/skills/lupopedia-headers.md`, `lupo-actors/wolfie/skills/lupopedia-headers.md`, `lupo-channels/42/content/federation_node_id/0/SKILLS.md`.

**Channel 42 content:** `lupo-channels/42/content/federation_node_id/0/RULES.md`, `lupo-channels/42/content/federation_node_id/0/NO_INFORMATION_SCHEMA_RULE.md`.

**PHP:** `install.php`, `lupo-includes/classes/RuleEngine.php`, `lupo-includes/classes/RuleEvaluator.php`, `lupo-includes/classes/ToonValidator.php`, `lupo-includes/classes/SkillService.php`, `lupo-bin/lupo.php`.

**Doctrine / docs:** `lupo-docs/doctrine/RULES_DOCTRINE.md`, `lupo-docs/doctrine/SKILLS_DOCTRINE.md`, `lupo-docs/HELP.md`, `AGENTS.md`, `lupo-config/global_atoms.yaml`, `.cursor/rules/flip-doctrine.mdc`, `.cursor/rules/single-install-no-4.0-upgrade-doctrine.mdc`, `lupo-docs/status/cursor_4_0_68_reconciliation_report.md`.

# LUPOPEDIA FOOTER STARTS HERE

lupopedia.footer:
  version: "4.0.74"
  last_verified: "20260314"
  archive_note: "For historical changelog entries from 4.0.67 and earlier, see CHANGELOG_ARCHIVE.md"
  next_action:
    - "Execute full upgrade testing from Crafty Syntax 3.7.5 to Lupopedia 4.0.73"
    - "Complete channels web interface hardening and validation"
    - "Address any schema or performance issues discovered during testing"
    - "Document all findings and prepare for production deployment"
    - "Keep required reading and doctrine links current with 4.0.73"


