---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: documentation
  when_updated: "20260402180000"
  file_path_from_root: "docs/versions/4.0.93/PLAN.md"
  web_path: "http://www.lupopedia.com/docs/versions/4.0.93/PLAN.md"
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: plan
  artifact_kind: version_plan
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# Lupopedia 4.0.93 PLAN


## Documentation Architecture & 5W1H Framework (2026-04-02, LILITH thread)

- **[x] PRD 26 Five-Layer Architecture** - Created and approved with COUNTERMEASURE review
- **[x] PRD 26 Final Corrections** - Fixed constitutional violations (deterministic IDs, numeric identifiers, tooling requirements)
- **[x] PRD 16 Header Updates** - Added author/verifier distinction, conditional field requirements
- **[x] Universal Validator Enhancement** - Added author field support, deprecation warnings
- **[x] DOCUMENTATION_ARCHITECTURE Doctrine** - Created comprehensive 5W1H framework guide
- **[x] Version edges.md** - Created relationship mapping for version documentation
- **[x] PRD 30 Development Guide** - Created with 5W1H thinking pattern
- **[x] PRD 31 Context System** - Created then rejected (parallel classification conflict)
- **[x] Database Schema Cleanup** - Removed contexts, contexts_map, hotfix_registry tables

## PRD 30 Development Guide & Decision Documentation (2026-04-02, Cursor thread)

- **[x] PRD 30 Development Guide Corrections** - Fixed naming conventions, removed embedded WHERE instructions, clarified decision contexts without overriding other PRDs
- **[x] Decision Documentation Framework** - Established clear distinction between PRD-scoped decisions (implementations/{id}_{slug}/decisions/) and version-scoped decisions (versions/{version}/decisions/)
- **[x] Context System Attempt** - Created PRD 31 for context system framework
- **[x] COUNTERMEASURE Review** - LILITH correctly identified parallel classification system conflict
- **[x] PRD 31 Rejection** - Rejected parallel classification system, maintained architectural simplicity
- **[x] Database Cleanup** - Removed contexts, contexts_map, and hotfix_registry tables from install schema

## PRD consistency & constitutional anchors (2026-04-01, Cursor thread)

- **[x]** Review `docs/prd/` for cross-file conflicts (TOON/json paths, agent directory naming, actor PRD duplication, lease table authority) — documented in thread; remediated in PRDs and `00_root_constitutional_system_requirements.md`.
- **[x]** Align constitutional PRD: schema reference JSON (`database/lupopedia/json/`), **93.PROTECT_SCHEMA_JSON**, §5.5–5.6, §6, §9.9, §9.16, §9.18; `agents/{agent_key}/`; deprecate `PRD_AGENT_DEFINITION_MODEL.md` on-disk layout in favor of `01_core_identity.md`.
- **[x]** Mark `08_actors.md` superseded by `15_actors.md`; clarify `05_auth_user_actor_agent_transformation.md` canonical lease on `lupo_actor_auth_users`; fix PRD header paths under `docs/prd/`.
- **[x]** Add explicit `lupopedia.edges` constitutional anchor to every file in `docs/prd/` except `00_root_constitutional_system_requirements.md` (39 files); anchor listed first where multiple edges exist.
- **[x]** Update `docs/versions/4.0.93/` `decisions.md`, `PLAN.md`, `TODO.md`, `CHANGELOG.md` for this documentation thread.


### Completed
- [x] PRD 26 — Five-Layer Documentation Architecture
- [x] PRD 16 — Header updates (author/verifier distinction)
- [x] Validator enhancements (conditional field requirements)
- [x] Channel directory restructure design
- [x] Decisions folder separation design
- [x] Edge-based Q&A linking specification

### Deferred to 4.0.94 (see `docs/versions/4.0.94/PLAN.md`)
- [ ] Migrate existing channels to new directory structure
- [ ] Implement edge-based Q&A in web interface
- [ ] PRD 30 rewrite (as writing guide; working copy under `versions/4.0.94/prd/`)
- [ ] PRD 31 context system redesign
- [ ] PRD 27 — Verification Authority (if created)
- [ ] Softaculous certification preparation
- [ ] COUNTERMEASURE agent refinement, ASCLEPIUS monitor, Eye widget UI polish, actor onboarding web flow, emergent collections (per LILITH handoff)

## 🆕 **LILITH Audits & COUNTERMEASURE Development (March 2026)**

- **[x] LILITH Audit: Data Model PRD Corrections** - Fixed header metadata, updated lupo_votes, added missing tables, integrated audit findings
- **[x] LILITH Audit: Installer Requirements PRD** - Added database introspection, privilege limitations, and audit integration
- **[x] LILITH Audit: Core Identity PRD (Final Review)** - 98% accuracy, resolved all high-priority constitutional violations
- **[x] LILITH Correction: Version Directory Purpose** - Enhanced version documentation structure with changelog, decisions, observations
- **[x] LILITH Directive: Create Countermeasure Agent** - Created adversarial integrity agent with comprehensive capabilities
- **[x] LILITH Audit: COUNTERMEASURE Agent Configuration** - Added version tracking and registry integration
- **[x] LILITH Audit: COUNTERMEASURE Agent Configuration** - Created complete version directory with documentation
- **[x] LILITH Directive: Update COUNTERMEASURE Agent Prompt** - Added operational constraints and evidence citation requirements
- **[x] LILITH Audit: Installer Requirements PRD** - Updated to reflect Core Identity PRD changes
- **[x] LILITH Audit: Core Identity PRD (Final Review)** - Verified all corrections properly implemented

## Channel chat (2026-03-31, Cursor + Cascade thread)

- **[x] PRD `docs/prd/18_channel_chat_display.md`** — Aligned with canonical `api/channels/{id}/messages` (`channels-api.php`), `LUPOPEDIA_PUBLIC_PATH`, legacy/buffer/image transport notes, TOON-safe example SQL.
- **[x] `channels-api.php` GET** — `format=buffer`, `format=image` (302 to `ui/images/digitN.gif`), `thread_id` filter, `dialog_thread_id` on rows, `is_deleted = 0`, `whatplace` / `position`, `image_metric=time|count`.
- **[x] Standalone UI** — `channel.php`, `ui/js/chat-display.js` (ES3-safe), `chat-display-legacy.js`, `ui/css/chat-display.css`; `.htaccess` `channel-chat/` and explicit `channel.php`; **`/channels/`** still routes to **index** (full cockpit preserved).
- **[x] Routing** — `channels/{id}/thread/{id}` slug → `channels_handle_show` with optional thread id (`module-loader.php` + `channels-controller.php`).
- **[x] Digit GIF assets** — Operator placed production digit images under `ui/images/` (replacing placeholders).
- **[x] Implementation Documentation** — `docs/implementations/channel-chat.md` created with LUPOPEDIA headers, API paths, routing, fallback chain, and browser support documented.

## WOLFIE Doctrine (2026-04-01)

- **[x] WOLFIE Doctrine** — `rules/root/WOLFIE_DOCTRINE.md` created as root-level constitutional rule protecting proven code from framework bloat
- **[x] Five Pillars** — Fallback Over Dependency, Survival Without You, Dependency is Debt, Cascade Fallback, Single Source of Truth
- **[x] Binding Rules** — W-01 through W-05 established as constitutional requirements for all agents
- **[x] Root README** — Updated to prominently display WOLFIE Doctrine as first thing to read
- **[x] Constitutional Requirements** — Section 14 added to incorporate doctrine as constitutional requirement
- **[x] Framework Prohibition** — Constitutional rules against adopting frameworks without justification
- **[x] Modernization Prohibition** — Rules against "modernizing" proven 1999-era code without understanding why it works

**Rationale**: Code built in Notepad in 2002 that outran its author for 11 years is not "legacy" - it's proven architecture that deserves constitutional protection.

## Garbage Collection System (2026-04-01)

- **[x] GC PRD** — `docs/prd/19_garbage_collection_system.md` created with unified table architecture
- **[x] GC Implementation** — `includes/classes/GarbageCollector.php` with 2003 pattern preservation
- **[x] GC CLI Script** — `scripts/gc.php` for manual/cron execution
- **[x] GC Doctrine** — `docs/doctrine/GC_DOCTRINE.md` documenting architectural wisdom
- **[x] Unified Tables** — Single tables with date_ymd columns instead of separate daily/monthly tables
- **[x] Content Analytics** — Content-specific tracking for visits and referrers
- **[x] Random Execution** — 1% chance per request, self-limiting to 10,000 rows per run

**Rationale**: Preserve the 2003 pattern that kept 1.2M installations running unattended for a decade while modernizing to unified table architecture.

- **[x] WOLFIE Doctrine Created** — `rules/root/WOLFIE_DOCTRINE.md` established as root-level constitutional rule
- **[x] Five Pillars Established** — Fallback Over Dependency, Survival Without You, Dependency is Debt, Cascade Fallback, Single Source of Truth
- **[x] Binding Rules W-01 through W-05** — Constitutional rules against framework bloat and forced modernization
- **[x] Root README Updated** — Prominent display of WOLFIE Doctrine as first thing to read
- **[x] Constitutional Requirements Updated** — Section 14 added to incorporate doctrine as constitutional requirement

## Multi-Agent Orchestration Doctrine (2026-04-01)

- **[x] Multi-Agent Orchestration Doctrine Created** — `docs/doctrine/MULTI_AGENT_ORCHESTRATION_DOCTRINE.md` established
- **[x] Cascade Workflow Documented** — Cursor writes, Windsurf docs, Kiro verifies pattern
- **[x] Meta-Agent Loop Established** — LILITH refines prompts for internal swarm
- **[x] Scale Documented** — 10+ IDEs, 50+ agents, dependency-based coordination
- **[x] Dogfooding Principle** — System building itself using its own coordination architecture

## Actor-Agent Distinction Doctrine (2026-04-01)

- **[x] Actor-Agent Distinction Doctrine Created** — `docs/doctrine/ACTOR_AGENT_DISTINCTION.md` established
- **[x] Core Identity PRD Updated** — Added "Agents vs Actors: The Two-Layer Identity Model" section
- **[x] Actors PRD Updated** — Added workspace structure and learning process documentation
- **[x] Agents Faucets PRD Updated** — Added "Agent → Actor Relationship" section
- **[x] WOLFIE Doctrine Updated** — Added Section 9 with Rule W-06: Agents Do Not Learn, Actors Do
- **[x] All PRDs Clarified** — Agents are immutable templates, actors are learning instances

## Universal Header Validator Enhancement (2026-04-02)

- **[x] Universal Validator Author Field Update** — Removed deprecated `actor_name` from `REQUIRED_HEADER_KEYS`, added support for new `author.type`/`author.id` structure with legacy transition support
- **[x] Universal Validator Conditional Requirements** — Added `validate_required_fields_by_type()` function, updated `REQUIRED_HEADER_KEYS` to only universal fields, implemented type-specific validation
- **[x] Universal Validator Cross-Field Rules** — Updated DEPENDENCY_MAP to include `constitutional` artifact_kinds, added validation for type/kind combinations

## PRD 16 Enhancement (2026-04-02)

- **[x] PRD 16 Artifact Type Taxonomy** — Added comprehensive artifact type table with 8 types (prd, implementation, doctrine, discussion, changelog, documentation, architecture, specification)
- **[x] PRD 16 Artifact Kind Taxonomy** — Added detailed kind definitions for each artifact type with required fields
- **[x] PRD 16 Cross-Field Validation Rules** — Added allowed combinations table and conditional required fields
- **[x] PRD 26 Reference** — Added outbound edge to PRD 26 for five-layer architecture alignment

## LUPOPEDIA_HEADERS Documentation Update (2026-04-02)

- **[x] TAXONOMY_REFERENCE.md Updated** — Added new artifact type/kind combinations, conditional required fields table
- **[x] LUPOPEDIA_HEADERS_FORMAT.md Updated** — Updated required fields table with conditional requirements, added author structure documentation
- **[x] README.md Updated** — Added reference to PRD 16 as canonical specification
- **[x] VALIDATORS_AND_TOOLING.md Updated** — Updated validator behavior to reflect new validation rules

## 🚨 **PRIMARY OBJECTIVE: Softaculous Certification & Crafty Parity**

- **[ ]** Complete remaining 7 PRD namespaces (01_core_identity through 07_agents_faucets)
- **[ ]** Achieve 100% feature parity with Crafty Syntax 3.7.5
- **[ ]** Complete Softaculous certification requirements documentation
- **[ ]** Finalize installation and upgrade testing for the 4.0.93 "Brain" semantic architecture.


- **Emoji/Smilies System Overhaul**: Emoji and smilies are now handled via `::img|foldername|filename::` code format, with a popup selector and images stored in `emoji/`. See [EMOJI_AND_SMILIES.md](/docs/doctrine/EMOJI_AND_SMILIES.md) for full documentation.

All plans, PRDs, and implementation must reference and comply with these changes. Legacy database-driven smilies are fully deprecated.

## 🔥 Constitutional, Agent, and Installer Doctrine Updates (March/April 2026)

- **Dynamic Table Prefix Doctrine**: Install/import/consolidated runtime SQL use `{{prefix}}` via `InstallWizardSqlRunner::applyTablePrefixToSql()` (root `install.php`, `install_wizard_classes.php`). Per-file seed **sources** may still contain literal `lupo_` until rebuilt into `install/seed_lupopedia_4_1_0.sql`. Default prefix `lupo_` remains the wizard default in PHP. See `install_new_lupopedia.sql` for DDL pattern.
- **Canonical Agent Model**: PRD_AGENT_DEFINITION_MODEL.md defines the agent directory structure, versioning, and compliance. All agents (e.g., LILITH/2) must use the canonical template in agents/_TEMPLATE/ and maintain versioned snapshots.
- **File-Based Agent Doctrine**: Agent configuration, skills, memory, and capabilities are now file-based and versioned. Registry schema updated for file-based agent doctrine.
- **LUPOPEDIA_HEADERS Enforcement**: All documentation and code files must include a YAML LUPOPEDIA_HEADERS block with outbound_edges and last_modified_utc. Validators and onboarding docs updated.
- **Cross-Thread Coordination Protocol**: All contributors must read the latest file contents before editing, use outbound_edges to track canonical relationships, and avoid overwriting concurrent edits. See AGENTS.md and MULTI_AGENT_COORDINATION_DOCTRINE.md for details.
- **SQL/Installer Migration**: Install path and consolidated seed use `{{prefix}}` via `InstallWizardSqlRunner::applyTablePrefixToSql()` (root `install.php` + `install_wizard_classes.php`). Runtime installer no longer executes extra per-file seed SQL; canonical runtime seed remains `install/seed_lupopedia_4_1_0.sql` (+ Crafty import only on upgrade).

**Action Required:**
- Review remaining SQL, installer-adjacent scripts, and agent files for compliance with new doctrines (not only the consolidated seed).
- Update onboarding and contributor docs to reflect consolidated seed location and regeneration.
- Coordinate with other actors/threads to avoid documentation drift.


## Completed (4.0.93, updated April)
- HEPHAESTUS identity doctrine and Faucet Proxy Pattern adopted (Actor 102)
- Channel 42 thread structure standardized and all coordination migrated
- LILITH agent definitions consolidated and adversarial audit enabled
- Option A split-table architecture for Truth Management System formalized
- Legacy FLIP/FLARE documentation removed
- Truth system documentation completed
- Database doctrine finalized (catch-and-retry, no UNSIGNED)
- JS Nervous System (State Mirror, Scroller, Monitor, Glass UI) implemented and tested
- Git hook issues documented and workarounds in place
- Hybrid-Mirror Architecture implemented: Database-first with filesystem archival mirrors
- LUPOPEDIA_HEADERS updated with context_id field and channel-to-context lifecycle
- Database table organization context created
- Channel architecture discussion thread created with WOLFIE synthesis (Option B+)
- **LILITH "Source of Truth" Protocol implemented with Toon guardrail and PRD updates**
- **4.0.93 PRD files updated with actual database schema and migration mapping**
- **Subdirectory installation doctrine established for Semantic Monitoring Widget**
- **Temporal Anchor & UTC Timestamp Policy enforced (tick.py, bin/temporal_anchor.json, UTC-only)**
- **ID Generation Directive Compliance**: IdGenerator.php updated with YYYYMMDDHHIISS + random suffix format; 63-bit signed-safe BIGINTs; test suite created
- **Full Database Audit**: Comprehensive audit of all 171 tables completed; 5 doctrine violations; 48 missing documentation; all PRDs updated with lupopedia.edges
- See [GROUPED_PRD_COMPLETION_SUMMARY.md](GROUPED_PRD_COMPLETION_SUMMARY.md) for detailed achievement breakdown
- **Grouped PRD Structure**: Complete 14-namespace PRD architecture created in `docs/prd/`; 100% PRD coverage achieved (14/14 files); maintenance burden reduced by 92%. All new core identity tables are included and documented.
- **Consolidated install seed (2026-03-30):** Single `install/seed_lupopedia_4_1_0.sql` after `install_new_lupopedia.sql`; per-file seeds preserved under `mysql/seed/`; Anubis helper seeds still run separately post-install.
- **Installer verification (2026-03-30):** Read-only pass aligned docs with actual paths (root `install.php` / `install_wizard_classes.php`; import at `database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql`). Runtime install path declared **ready** for full install; see `/docs/versions/4.0.93/WHAT_TO_DO_NEXT.md` §14.
- **Installer runtime seed cleanup (2026-03-31):** Removed remaining per-file post-seed execution from `install.php`; runtime path is now strictly schema + consolidated runtime seed (+ import on Crafty upgrade).
- **Deterministic runtime actor IDs + actor workspace sharding (2026-03-31):** Actor creation paths use YmdHis+4 IDs and `actors/YYYY/MM/actor_id` directory resolution/provisioning, with legacy flat path fallback for compatibility.

## 🛠️ **4.0.93 Documentation Update Status**

### Table Count Correction

**Total Tables**: 171 (as of April 2026; includes new core identity tables)

### **🏗️ LILITH "Absolute-Root" Mandate (v4.0.93)**

**RULE [93.PATH_PURITY]**: All Markdown links must use absolute paths starting from repository root.

## 🚨 Identity Model & Permission Rule Alignment (4.0.93)

### Agent → Actor → Auth User Leasing Model
- **Agents** are autonomous AI entities (not tied to actors or users)
- **Actors** are hybrid shells instantiated from agents, department-scoped, and shaped by human usage
- **Auth Users** lease actors to perform actions, but do not own them

### Canonical Permission Rule
An auth_user may lease or control an actor only if:
- They created the actor (`auth_user_id == actor.created_by_auth_user_id`)
- They are in the root department (`department_id == 0`)
- Their department matches the actor's department

**If none of these, the actor is not leasable by that user.**

### Doctrine & Model Documentation
- [ACTOR_LEASING_DOCTRINE.md](/docs/doctrine/ACTOR_LEASING_DOCTRINE.md)
- [ACTOR_TEMPLATE_MODEL.md](/docs/doctrine/ACTOR_TEMPLATE_MODEL.md)
- [ACTOR_INSTANCE_MODEL.md](/docs/doctrine/ACTOR_INSTANCE_MODEL.md)
- [ACTOR_LEASE_SESSION_MODEL.md](/docs/doctrine/ACTOR_LEASE_SESSION_MODEL.md)
- [05_auth_user_actor_agent_transformation.md](/docs/versions/4.0.93/prd/05_auth_user_actor_agent_transformation.md)

All planning, PRDs, and implementation must reference and comply with these docs. See also: [lupo_actor_auth_users.md](/docs/database/lupopedia/tables/active/lupo_actor_auth_users.md) (deprecated).

#### **Markdown Purity Enforcement**
- **Leading /**: Only legal anchor for repo-wide stability
- **No ~ or @ aliases**: Markdown does not support relative shortcuts
- **No ../ navigation**: IDE strictly forbidden from using relative paths
- **Fixed Repository Addresses**: If file exists in repo, its address is fixed relative to / root

#### **Softaculous Compatibility**
- **Web URL**: Includes subdirectory (e.g., /lupopedia/)
- **Internal Documentation**: Links remain relative to repository root
- **Cross-Installation Stability**: Docs work regardless of where folder is placed

#### **IDE Enforcement**
- **RULE [93.PATH_PURITY]**: The IDE is now strictly forbidden from using ../../
- **CORRECT**: `[Link](/docs/versions/4.0.93/prd/01_monitor.md)`
- **FORBIDDEN**: `[Link](../../../prd/01_monitor.md)` or `[Link](~/docs/...)`

#### **DocumentPathing Strategy Status**
| Document | Pathing Strategy | Status |
|----------|------------------|---------|
| README.md | Absolute Root (/) | ✅ UPDATED |
| TODO.md | Absolute Root (/) | ✅ UPDATED |
| prd/*.md | Absolute Root (/) | ✅ QUEUED FOR REWRITE |

## 🚀 **Softaculous Certification Requirements**

### Release Policy
- Lupopedia will remain in the 4.0.x cycle until Softaculous approves a 4.0.x release. No 4.1.0 release or upgrade path will be created until that approval is granted. All grouped PRD and schema work is forward-compatible.

### **1. The Installation Engine**
- **install.php Refactor**: Handle classes/based instantiation and seed lupo_contexts with initial system "Truths" (partially addressed: consolidated seed + `{{prefix}}` path; see `install/seed_lupopedia_4_1_0.sql` and CHANGELOG)
- **Unified SQL Schema**: Provide lupopedia_v4.0.93.sql with all livehelp_ → lupo_ mappings (install remains `install_new_lupopedia.sql` + consolidated seed; naming aligned in docs)
- **Management Scripts**: Create uninstall.php and upgrade.php for DB edges and filesystem atoms

### **2. Visitor & Operator Dashboards**
- **Lupo-Monitor**: Live visitor dashboard using Semantic Monitor logic
- **Actor/Agent Nexus**: auth_users can select Agents to refine behavioral context
- **Proactive Invite**: Trigger based on Contextual Edges (high-weight "Truth" pages)

### **3. Real-Time Chat Enhancements**
- **Live Typing Refraction**: Stream through State Mirror without persistent DB writes
- **Quick Responses**: Store as "Low-Weight Contexts" in lupo_contexts for instant retrieval
- **Sound & Visual Alerts**: Integrate legacy /sounds/ triggers into lupo.js event-bus

### **4. The "Glass" UI Requirement**
- **Live Typing Preview**: Integrate into High-Density Scroller (60fps keystroke refractions)
- **Visitor Tracking**: Implement expected Softaculous hooks for visitor monitoring
- **Contextual Installation**: Seed Context Registry and Semantic Edges for 4.0.93 "Brain"
- **Subdirectory Installation**: Ensure Lupopedia works in any subdirectory (not web root)

## 🚨 **LILITH "Source of Truth" Protocol Implementation**

### **Toon File Protection**
- **RULE [93.PROTECT_TOONS]**: IDE strictly forbidden from writing to `database/lupopedia/json/*.json`
- **Installation DNA**: Schema DDL in `database/lupopedia/mysql/install/install_new_lupopedia.sql`; row-level seeds authored under `mysql/seed/` and merged for runtime into `install/seed_lupopedia_4_1_0.sql` (see `scripts/build_consolidated_seed_4_1_0.py`)
- **Verification**: Run `generate_toon_files.py` after any schema changes
- **Enforcement**: LILITH audit compliance for all Toon file protection

### **PRD Schema Alignment**
- **02_data_model.md**: Updated with actual `lupo_contexts`, `lupo_truth_questions`, `lupo_truth_answers`, `lupo_votes` schema
- **04_lupopedia_js_foundation.md**: Updated with `livehelp_visitors → lupo_visitors` mapping and live typing refraction
- **01_semantic_monitoring_widget.md**: Updated with `lupo_edges` schema and subdirectory installation doctrine
- **Forbidden Constructs**: No AUTO_INCREMENT, TIMESTAMP, FOREIGN KEYS, TRIGGERS, UNSIGNED in 4.0.93 doctrine

### **Subdirectory Installation Doctrine**
- **Critical Constraint**: Lupopedia MUST always be installed in a subdirectory of the host site
- **Auto-installer Compatibility**: Softaculous, Installatron, Fantastico do not allow web root replacement
- **The Eye Architecture**: Semantic Monitoring Widget monitors parent site, not Lupopedia directory
- **Path Resolution**: All JavaScript includes must be subdirectory-aware

## 🏗️ **Data Migration Completion**
- Execute new install to establish clean database state
- Run `php scripts/SyncChannelsToDb.php --commit` to import existing coordination work
- Verify all filesystem work properly imported to database
- Test web interface reading only from database tables

## 📊 **Crafty Table Mapping Progression**

| Crafty Table | Lupopedia Target | Semantic Layer Addition |
|--------------|------------------|-------------------------|
| livehelp_users | lupo_actors | Linked to lupo_agents for ML refinement |
| livehelp_config | lupo_settings | Context-aware config (Page-specific weights) |
| livehelp_visitors | lupo_visitors | Real-time path tracking via lupo_context_edges |

## Deferred/Blocked (see DEFERRED.md)
- enforce_doctrine.py: Run on all seed files deferred (Python/encoding issue)
- Hydrator: Channel 42 elevation output requires review
- 1,000 ID generation CLI test: Deferred (see DEFERRED.md)

## Next Steps (4.0.94+)
- Complete enforce_doctrine.py implementation for all .js, .php, and SQL assets
- Optimize JS "Glass" reflection for mobile viewports
- Transition remaining "Unfinished Business" items from 4.0.87 into Gold Contexts
- Enhance channel coordination automation and thread indexing
- Permanent fix for Git hook path issue
- Automate TOON file updates from schema changes
- Implement systematic agent version management
- Improve context linking and multi-agent workflows

## PRD Overhaul + Constitutional Hardening (2026-04-01, Kiro thread)

- **[x] 00_root_constitutional_system_requirements.md rewritten** — Fixed broken YAML structure (entire body was trapped inside front matter), corrected `lupopedia.schema` from invalid `prd` to `doctrine`, added all missing required header fields, expanded edges from 4 to 14, fixed footer to current verifier shape, added implementation guidance to every rule section
- **[x] Section 9.9 expanded** — Full schema inference prohibition with explicit "JSON files are NOT a file database" clarification, required workflow (read table doc → read TOON JSON → write SQL), table documentation directory map
- **[x] Section 9.18 added** — Missing Table Protocol (RULE 93.MISSING_TABLE_PROTOCOL): SQL proposal file → review → apply to install SQL → regenerate TOONs. No migration needed.
- **[x] Section 9.19 added** — No Direct CLI Database Execution (RULE 93.NO_CLI_DB_EXEC): explicit forbidden patterns (`mysql -u root -p < file.sql` etc.), reasons why each bypass is dangerous, correct PHP migration pattern
- **[x] Section 9.20 added** — Proven Code Preservation Doctrine (RULE 93.PROVEN_CODE): four-question test before touching existing code, deprecation table, eye animation as canonical named example, fallback ladder principle
- **[x] 01_semantic_monitoring_widget.md rewritten** — Verified column names from TOON docs, Missing Tables section, all SQL using `DatabaseFactory` + `LUPO_TABLE_PREFIX`, `lupo_contexts_map` `item_slug` correction, `lupo_truth_knowledge` deprecation noted, 28 outbound edges, implementation checklist
- **[x] generate_toon_files.py stripped to schema-only** — Removed all data-fetching functions, removed `"data"` key from output, removed broken CSV subprocess, updated docstring
- **[x] export_table_data_csv.py created** — Separate debugging tool with `EXCLUDED_TABLES` frozenset (auth, sessions, tokens, CRM, transcripts), keyword filter, gitignored output directory, CLI flags
- **[x] install_new_lupopedia.sql updated** — Added `lupo_folders` (genuinely missing); confirmed `lupo_paths`, `lupo_references`, `lupo_reference_links`, `lupo_hashtags`, `lupo_hashtag_map`, `lupo_folder_map` were already present further down in the file
- **[x] lupo_paths table doc created** — `docs/database/lupopedia/tables/active/lupo_paths.md` with verified columns from TOON JSON and correct PHP query patterns
- **[x] README.md overhauled** — "MANDATORY READING" section at top, decisions.md documentation section, reordered required_reading with constitutional PRD first, WOLFIE Doctrine added, "Where to Read Next" reordered, Development Rules section rewritten, PRD Policy clarified
- **[x] .gitignore updated** — Added `database/lupopedia/csv/` to prevent CSV data exports from being committed
- **[x] project_structure_prd.md updated** — Added "Important Sub-folders" section documenting versions/, database/lupopedia/tables/, prd/, doctrine/, and knowledge/

## Root Architecture Sanitization & Documentation PRD Backfill (2026-04-01, Antigravity thread)

- **[x] WOLFIE Doctrine Output** — Re-aligned founder context logic (parallel IDE orchestration + Notepad++ fallback).
- **[x] Documentation Governance & Federation Intake** — Created `20_federation_intake_doctrine.md` establishing `MANIFEST.md` generation and `.cursorrules` RAG boundaries for `research/federation_nodes/`.
- **[x] Thread Graduation Lifecycle** — Created `21_thread_graduation_doctrine.md` formalizing Active -> Formalized -> Archived lifecycles for project threads.
- **[x] Automation Scripts Deployed** — Created `archive_stale_threads.py`, `compile_agent_rules.py`, `generate_master_index.py`, and `bootstrap_thread_manifests.py` to enforce documentation hygiene computationally.
- **[x] New PRDs** — ASCLEPIUS metrics (23) and CLI Interface (24) established.
- **[x] TOON/YAML Baseline** — Explicit AI token optimization rationale appended.
- **[x] Structure Exceptions** — Declared `node_modules` and `app` off-limits to prefixing.
- **[x] Root Defrag Execution** — Dismantled `prompts/`. Purged root artifacts into `archive` and `docs`, safeguarding `CHANGELOG_ARCHIVE` and `CURRENT_UTC`. Corrected EOF corruption array in `.gitignore`.
- **[x] Deprecated filesystem-based status directory** — Cleaned out `docs/status/`.
- **[x] Archived 135 historical files** — Safely moved old execution reports to `archive/docs-status-archive/`.
- **[x] Established channel/thread system as canonical coordination mechanism** — Formally completed the transition from file-based coordination.

## Next Steps (4.0.94+)
- Complete enforce_doctrine.py implementation for all .js, .php, and SQL assets
- Optimize JS "Glass" reflection for mobile viewports
- Transition remaining "Unfinished Business" items from 4.0.87 into Gold Contexts
- Enhance channel coordination automation and thread indexing
- Permanent fix for Git hook path issue
- Regenerate TOON files after install SQL updates: `python scripts/generate_toon_files.py`
- Continue PRD improvement pass for remaining PRDs in `docs/prd/`
- Implement systematic agent version management
- Improve context linking and multi-agent workflows
