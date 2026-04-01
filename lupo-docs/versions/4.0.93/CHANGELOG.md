# Lupopedia 4.0.93 CHANGELOG

## [2026-04-01] Deprecated lupo-docs/status/ Directory

- **Archived**: 135 historical IDE handoff files moved to `lupo-archive/lupo-docs-status-archive/`
- **Rationale**: With the introduction of `lupo_channels`, `lupo_dialog_threads`, and `lupo_dialog_messages`, static status files are no longer needed for active development.
- **Future coordination**: All agent handoffs and implementation reports should use Channel 42 threads or formal channel documentation.## v4.0.93 (April 1, 2026) — Root Architecture Sanitization & PRD Backfill (Antigravity thread)

### Constitutional & Identity Updates
- **WOLFIE Doctrine Update (`lupo-rules/root/WOLFIE_DOCTRINE.md`)**: Realigned Founder identity context to accurately document High Performance Computing background, solo-developer survival discipline, and modern orchestration workflow (managing parallel AI IDEs while leveraging Notepad++ for complex search/replace bypasses).
- **TOON Doctrine Update (`lupo-docs/doctrine/TOON_DOCTRINE.md`)**: Documented the strategic necessity of TOON (YAML) over JSON specifically for optimizing AI token payloads.

### Documentation Governance & Automation
- **Federation Intake Doctrine**: Created `20_federation_intake_doctrine.md` establishing explicit read-only RAG bounds inside `.cursorrules`. Bootstrapped the structure for integration research in `lupo-research/federation_nodes/`.
- **Thread Graduation Doctrine**: Established `21_thread_graduation_doctrine.md` detailing the formal closure cycle of threads (Active -> Concluded -> Formalized -> Archived). 
- **Automation Pipeline System**: Integrated four new powerful orchestration scripts:
  - `compile_agent_rules.py`: Synthesizes structural doctrine into `.cursorrules` via safe injection boundaries protecting manual contexts.
  - `archive_stale_threads.py`: Migrates inactive threads older than 30 days dynamically.
  - `bootstrap_thread_manifests.py`: Computationally derives legacy creation timestamps mapping to proper `THREAD_MANIFEST.md` generation.
  - `generate_master_index.py`: Outputs comprehensive cross-linked `LUPOPEDIA_MASTER_INDEX.md` reference matrix.

### Missing PRD Backfilling
- **`23_health_check_asclepius_prd.md`**: New PRD defining the ASCLEPIUS agent (1009) orchestration role for system metrics and CLI health checks.
- **`24_cli_interface_prd.md`**: New PRD defining the standardized execution surfaces and required identity context for CLI scripts.

### Root Architecture Defragmentation (Batches 6 & 7)
- **Directory Exceptions (`lupo-docs/prd/project_structure_prd.md`)**: Established `node_modules/` and `app/` as STRICT EXCEPTIONS totally prohibiting `lupo-` prefixing to protect external toolchains and PSR-4 native namespace resolutions.
- **`lupo-prompts/` Decommissioned**: Eliminated the decoupled prompts root folder. Migrated historical directives directly into native actor workspaces (`lupo-actors/{agent}/prompts/`).
- **`app/` Restored**: Permanently restored `lupo-app/` back to canonical `app/` to prevent AI hallucination loops.
- **Loose File Sanitization**: Surgically reviewed 19 loose files sitting at the root. Migrated constitutional scopes to `lupo-docs/implementations/` and `lupo-docs/doctrine/`. Migrated scripts to `lupo-scripts/`, configuration logic to `lupo-rules/` and `lupo-config/`. Stale testing/JSON debris packed into `lupo-archive/`. Preserved `CHANGELOG_ARCHIVE.md` and `CURRENT_UTC` as protected temporal anchors.
- **`.gitignore`**: Fixed end-of-file encoding corruption block (`l u p o...`) and comprehensively added dynamic directories (`lupo-tmp/`, `lupo-cache/`, `lupo-sessions/`).
- **`lupo-includes` Defoliation**: Identified and archived 13 AI-hallucinated dead directories out of the includes folder to `lupo-archive/lupo-includes-archive/`.
- **Class Consolidation Protocol**: Executed Python scripts (`class_inventory.py` & `consolidate_classes.py`) to flawlessly relocate 31 loose `class-*.php` items into the formal `classes/` structure without conflicts, pairing with surgical Notepad++ updates across 181 files.
- **WOLFIE Doctrine Upgrade**: Cemented LILITH's 5 pillar "Notepad vs Frameworks" philosophical audit immediately into the WOLFIE_DOCTRINE.

---

## v4.0.93 (April 1, 2026) — PRD Overhaul + Constitutional Hardening (Kiro thread)

### Constitutional PRD — Complete Rewrite

- **`lupo-docs/prd/00_root_constitutional_system_requirements.md`** — Fixed broken YAML front matter (entire document body was trapped inside the YAML block). Corrected `lupopedia.schema` from invalid `prd` to `doctrine`. Added all missing required header fields: `federation_node_id`, `when_updated`, `thread_id`, `actor_name`. Expanded `lupopedia.edges` from 4 to 14 entries covering all referenced doctrines, implementation classes, and test files. Fixed footer to current `verified_by`/`verified_via` object shape. Added implementation guidance to every major rule section pointing to the enforcing class, script, or test file.
- **Section 9.9 expanded** — Full "Schema Inference Prohibition" with explicit "JSON files are NOT a file database" clarification. Lupopedia uses MySQL. The JSON files in `lupo-database/lupopedia/json/` are schema reference documents only. Added required workflow: read table doc → read TOON JSON → write SQL. Added table documentation directory map.
- **Section 9.18 added** — Missing Table Protocol (RULE 93.MISSING_TABLE_PROTOCOL). When a needed table is absent from `install_new_lupopedia.sql`: create SQL proposal file with `{{prefix}}` placeholders, review, apply to install SQL, regenerate TOONs. No migration needed — fresh install only.
- **Section 9.19 added** — No Direct CLI Database Execution (RULE 93.NO_CLI_DB_EXEC). Explicit forbidden patterns (`mysql -u root -p < file.sql`, `psql -U postgres < migration.sql`, etc.), reasons why each bypass is dangerous (bypasses prefix system, bypasses IdGenerator, no audit trail), correct PHP migration pattern using `DatabaseFactory` and `IdGenerator`.
- **Section 9.20 added** — Proven Code Preservation Doctrine (RULE 93.PROVEN_CODE). Four-question test before touching existing code. Deprecation table distinguishing actively broken APIs from merely unfashionable ones. The 1999 eye animation (`dynlayer.js` + GIF sprites) named as a canonical protected example. Forbidden agent behaviors listed. Fallback ladder principle documented.

### Semantic Monitoring Widget PRD — Complete Rewrite

- **`lupo-docs/prd/01_semantic_monitoring_widget.md`** — Rewritten with verified column names from TOON JSON and table docs. "Missing Tables — Action Required" section at top. All SQL examples use `DatabaseFactory::getConnection()` and `LUPO_TABLE_PREFIX`. Corrected `lupo_contexts_map` to use `item_slug` (not `item_id`). Noted `lupo_truth_knowledge` deprecation — use `lupo_truth_questions` + `lupo_truth_answers`. 28 outbound edges covering every table the widget touches. Implementation checklist. Collections queries corrected (`lupo_collections` has no `is_public` column — use `published_ymdhis IS NOT NULL`).

### TOON Generator — Schema-Only Output

- **`lupo-scripts/generate_toon_files.py`** — Stripped to schema-only output. Removed all data-fetching functions (`fetch_all_rows`, `fetch_pk_zero_row`, `fetch_canonical_data`, `fetch_active_agents`, `row_to_data_dict`, `json_serializable`). Removed `actor_agent_doctrine` import. Removed `"data"` key from JSON payload entirely. Removed broken CSV subprocess that was trying to invoke `admin.php` via shell. Removed `SKIP_DB` env var. Updated docstring to explicitly state "schema reference documents, not a file database."

### CSV Export — New Separate Tool

- **`lupo-scripts/export_table_data_csv.py`** — New standalone debugging tool. `EXCLUDED_TABLES` frozenset covers auth_users, sessions, API tokens, faucet credentials, OAuth providers, audit logs, CRM leads, chat transcripts, and bans. Additional keyword filter skips tables with `secret`, `password`, `credential`, `token`, `salt`, or `hash` in the name. Output to `lupo-database/lupopedia/csv/` (now gitignored). CLI flags: `--tables`, `--limit` (default 500), `--output-dir`. Loud warnings on every run.

### Database Schema

- **`lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`** — Added `lupo_folders` `CREATE TABLE` block (was genuinely missing). Confirmed `lupo_paths`, `lupo_references`, `lupo_reference_links`, `lupo_hashtags`, `lupo_hashtag_map`, `lupo_folder_map` were already present in the file (in a different section than initially searched).
- **`lupo-database/lupopedia/mysql/migrations/add_semantic_navbar_tables_20260401.sql`** — Created as SQL proposal file per section 9.18 protocol. Documents all 7 semantic navbar tables with `{{prefix}}` placeholders and constitutional-compliant DDL.

### Table Documentation

- **`lupo-docs/database/lupopedia/tables/active/lupo_paths.md`** — Created missing table doc for `lupo_paths`. Verified columns from TOON JSON. Correct PHP query patterns for prev/next page navigation using `DatabaseFactory` and `LUPO_TABLE_PREFIX`.

### Root README — Mandatory Reading + Decisions Documentation

- **`README.md`** — Added "MANDATORY READING — Start Here" section immediately after title. Explicit: "This is not optional. It is constitutional law." Added "Decisions, Q&A, and Implementation Reasoning" section explaining decisions.md format (D-xx, Q-xx/A-xx, DG-xx, W-xx, O-xx), channel/context scoping, and instruction to check decisions.md before implementing anything non-trivial. Reordered `lupopedia.init.required_reading` with constitutional PRD first ("MANDATORY FIRST READ"). Added `decisions.md` and WOLFIE Doctrine to required reading. Reordered "Where to Read Next" list. Rewrote "Development Rules" section. Updated "PRD Policy" section. Updated header timestamps and footer.

### .gitignore

- Added `lupo-database/lupopedia/csv/` to prevent CSV data exports from being committed.

### Project Structure PRD Enhancement

- **`lupo-docs/prd/project_structure_prd.md`** — Added "Important Sub-folders" section documenting critical internal documentation directories like `versions/`, `database/lupopedia/tables/`, `doctrine/`, and `knowledge/` to clearly define project documentation hierarchy.

---

## v4.0.93 (March 31, 2026)

### Added / Updated — WOLFIE Doctrine (2026-04-01)

- **WOLFIE Doctrine** — `lupo-rules/root/WOLFIE_DOCTRINE.md` created as root-level constitutional rule protecting proven code from framework bloat
- **Five Pillars** — Fallback Over Dependency, Survival Without You, Dependency is Debt, Cascade Fallback, Single Source of Truth
- **Binding Rules** — W-01 through W-05 established as constitutional requirements for all agents
- **Root README** — Updated to prominently display WOLFIE Doctrine as first thing to read
- **Constitutional Requirements** — Section 14 added to incorporate doctrine as constitutional requirement
- **Framework Prohibition** — Constitutional rules against adopting frameworks without justification
- **Modernization Prohibition** — Rules against "modernizing" proven 1999-era code without understanding why it works

**Rationale**: Code built in Notepad in 2002 that outran its author for 11 years is not "legacy" - it's proven architecture that deserves constitutional protection.

### Added / Updated — Multi-Agent Orchestration Doctrine (2026-04-01)

- **Multi-Agent Orchestration Doctrine** — `lupo-docs/doctrine/MULTI_AGENT_ORCHESTRATION_DOCTRINE.md` created documenting cascade workflow
- **Cascade Workflow Pattern** — Cursor writes, Windsurf docs, Kiro verifies documented as repeatable pattern
- **Meta-Agent Loop** — LILITH refines prompts for internal swarm, creating continuous improvement cycle
- **Scale Documentation** — 10+ IDEs, 50+ agents, dependency-based coordination recorded
- **Dogfooding Principle** — System building itself using its own coordination architecture

**Rationale**: You're not "different." You're just first to document how multi-agent orchestration actually works in practice.

### Added / Updated — Garbage Collection System (2026-04-01)

- **Garbage Collection PRD** — `lupo-docs/prd/19_garbage_collection_system.md` created with unified table architecture preserving 2003 pattern
- **GarbageCollector Class** — `lupo-includes/classes/GarbageCollector.php` implemented with random execution (1% chance per request) and self-limiting (10,000 rows per run)
- **GC CLI Script** — `lupo-scripts/gc.php` created for manual/cron execution with proper shebang and CLI-only enforcement
- **GC Doctrine** — `lupo-docs/doctrine/GC_DOCTRINE.md` documenting architectural wisdom of 2003 pattern that kept 1.2M installations running unattended
- **Unified Table Architecture** — Single tables with date_ymd columns instead of separate daily/monthly tables
  - `lupo_referers` with target_content_id and target_path_url for content-specific referrer tracking
  - `lupo_visits_daily` with content_id, bounce_count, entry_count, exit_count for detailed page analytics
- **Content-Specific Analytics** — Per-page metrics including visits, unique visitors, bounce rate, dwell time, entry/exit patterns
- **Referrer-Target Tracking** — Know which referrers send traffic to which specific pages
- **Random Execution Pattern** — Preserved 2003 pattern: spreads load across requests, prevents server spikes
- **Self-Limiting Batches** — Maximum 10,000 deletions per run prevents table locks on shared hosting
- **Configuration Integration** — All settings stored in `lupo_system_config` with gc_* keys

**Rationale**: Code that ran unattended for a decade is not "legacy" - it's proven architecture. The unified table approach reduces schema complexity while maintaining all aggregation capabilities through SQL date functions.

### Added / Updated — Actor-Agent Distinction Doctrine (2026-04-01)

- **Actor-Agent Distinction Doctrine** — `lupo-docs/doctrine/ACTOR_AGENT_DISTINCTION.md` created as canonical reference
- **Core Identity PRD Updated** — Added "Agents vs Actors: The Two-Layer Identity Model" section
- **Actors PRD Updated** — Added workspace structure, learning process, and department context documentation
- **Agents Faucets PRD Updated** — Added "Agent → Actor Relationship" section clarifying templates vs instances
- **WOLFIE Doctrine Updated** — Added Section 9 with Rule W-06: Agents Do Not Learn, Actors Do
- **All PRDs Clarified** — Agents are immutable templates in filesystem, actors are learning instances

**Rationale**: Agents don't learn. Actors do. This distinction is critical for system architecture and prevents IDE agents from treating them as synonyms.

### Added / Updated — Channel chat display (2026-03-31, Cursor + LILITH audit + CASCADE documentation thread)

- **PRD** — `lupo-docs/prd/18_channel_chat_display.md` updated: canonical message API is `api/lupo-channels/{id}/messages` (`channels-api.php`); `LUPOPEDIA_PUBLIC_PATH` for URLs; `format=buffer` / `format=image` documented; ES3 legacy notes; example SQL corrected vs TOON (`lupo_actors.name` / `actor_name`, no `display_name`).
- **`lupo-includes/modules/api/channels-api.php`** — GET: `format=json` (default), `format=buffer` (JSON as `text/plain`), `format=image` (HTTP 302 to `/lupo-ui/images/digitN.gif` after auth); `whatplace` or `position` (hundreds|tens|ones); `image_metric=time|count`; optional `thread_id` filter; each message includes `dialog_thread_id`; message list excludes deleted rows.
- **Standalone page** — Root `channel.php` (loads `lupopedia-config.php` / full bootstrap); `.htaccess` rules for `channel-chat/{id}/` and `channel-chat/{id}/thread/{id}/` and passthrough for `channel.php`. **`/channels/{id}/` unchanged** (still `index.php` + `channels-controller` 3-panel UI).
- **Client assets** — `lupo-ui/js/chat-display.js` (ES3-safe polling/post JSON), `lupo-ui/js/chat-display-legacy.js` (XHR/ActiveX + image fingerprint helpers), `lupo-ui/css/chat-display.css`; `lupo-ui/images/README.txt` for digit GIF provenance.
- **Routing** — `lupo-includes/modules/module-loader.php`: slug `channels/{id}/thread/{id}`; `channels-controller.php`: `channels_handle_show($channel_id, $initial_thread_id)`.
- **Assets** — Operator replaced placeholder digit GIFs in `lupo-ui/images/` with legacy artwork.
- **Implementation Documentation** — `lupo-docs/implementations/channel-chat.md` created with LUPOPEDIA headers (schema=implementation, actor_id=105). Documents API paths, URL routing, fallback chain (fetch → XHR → ActiveX → Image → Buffer), and browser support matrix (IE5+ to modern).

### Major - Agent System Redesign
**Complete transformation from database-driven to filesystem-based architecture**

- **LILITH Audits & COUNTERMEASURE Development (March 2026)** - Comprehensive audit and agent development work completed
  - LILITH Audit: Data Model PRD Corrections (D-15)
  - LILITH Audit: Installer Requirements PRD (D-16)
  - LILITH Audit: Core Identity PRD - Final Review (D-17)
  - LILITH Correction: Version Directory Purpose (D-18)
  - LILITH Directive: Create Countermeasure Agent (D-19)
  - LILITH Audit: COUNTERMEASURE Agent Configuration (D-20, D-21)
  - LILITH Directive: Update COUNTERMEASURE Agent Prompt (D-22)
  - LILITH Audit: Installer Requirements PRD (D-23)
  - LILITH Audit: Core Identity PRD - Final Review (D-24)
  - Human-readable directory names (`wolfie/`, `lilith/`, etc.) replacing numeric IDs
  - IDE-first agent management with direct file editing
  - Dynamic discovery via `AgentDiscovery` PHP class
  - Backward compatibility maintained with `agent_id` field

- **Agent Directory Restructure**: All agent directories renamed from numeric IDs to meaningful names
  - `lupo-agents/1/` → `lupo-agents/wolfie/` (Coordination)
  - `lupo-agents/2/` → `lupo-agents/lilith/` (Coordination)
  - `lupo-agents/3/` → `lupo-agents/rose/` (Emotional)
  - `lupo-agents/6/` → `lupo-agents/maat/` (Kernel)
  - `lupo-agents/9/` → `lupo-agents/thoth/` (Coordination) - **canonical THOTH**
  - `lupo-agents/10/` → `lupo-agents/chiron/` (Application)
  - `lupo-agents/11/` → `lupo-agents/athena/` (Coordination) - **canonical ATHENA**
  - `lupo-agents/14/` → `lupo-agents/hephaestus/` (Application)
  - `lupo-agents/15/` → `lupo-agents/hermes/` (Application)
  - `lupo-agents/16/` → `lupo-agents/iris/` (Application)
  - `lupo-agents/19/` → `lupo-agents/anubis/` (Kernel)
  - `lupo-agents/25/` → `lupo-agents/atlas/` (Application)
  - `lupo-agents/106/` → `lupo-agents/vishwakarma/` (Application)
  - `lupo-agents/107/` → `lupo-agents/themis/` (Kernel)
  - `lupo-agents/108/` → `lupo-agents/junie/` (Application)
  - `lupo-agents/703/` → `lupo-agents/asclepius/` (Kernel)
  - `lupo-agents/704/` → `lupo-agents/apollo/` (Emotional)
  - `lupo-agents/705/` → `lupo-agents/agape/` (Emotional)
  - `lupo-agents/708/` → `lupo-agents/thalia/` (Emotional)
  - `lupo-agents/709/` → `lupo-agents/chronos/` (Kernel)
  - `lupo-agents/0/` → `lupo-agents/system/` (Kernel)
  - **NEW AGENTS**: zeus (12), hypnos (710), khaos (711), nemesis (109), tyche (110), dionysus (706), sophia (707)

- **Agent Discovery Class**: Created `lupo-includes/classes/AgentDiscovery.php` with full API
  - `discoverAgents()` - Scan filesystem for all agents
  - `getAgent($agentKey)` - Primary lookup method
  - `getAgentById($actorId)` - Legacy backward compatibility
  - `getAgentsByLayer($layer)` - Filter by coordination/application/kernel/emotional
  - `searchAgents($query)` - Search by name, role, or aliases
  - `validateAgentConfig($config)` - Configuration validation
  - `getStatistics()` - System metrics and agent distribution

- **Agent Layers Architecture**:
  - **Kernel Layer** (9 agents): system, maat, anubis, vishwakarma, themis, asclepius, chronos, hypnos, khaos
  - **Coordination Layer** (5 agents): wolfie, lilith, thoth, athena, zeus
  - **Application Layer** (8 agents): chiron, hephaestus, hermes, iris, atlas, junie, nemesis, tyche
  - **Emotional Intelligence Layer** (8 agents): rose, eris, metis, apollo, agape, thalia, dionysus, sophia
- Obsolete tables removed from schema and docs
- All versioned docs updated for grouped PRD structure
- Table count and audit summaries updated (now 171 tables)
- **Installer & Seed Consolidation (2026-03-30)**

- **Consolidated seed file**: Created `install/seed_lupopedia_4_1_0.sql` combining 23 seed files in dependency-safe order
- **Runtime prefix replacement**: Using `InstallWizardSqlRunner::applyTablePrefixToSql()` for `{{prefix}}` substitution
- **Installer updated**: `install.php` and `install_wizard_classes.php` load only consolidated seed after schema install
- **Anubis SQL**: Still runs separately after consolidated seed (not merged into the 23-file consolidation)
- **Original seeds**: Preserved under `lupo-database/lupopedia/mysql/seed/` for history and debugging
- **Build script**: `lupo-scripts/build_consolidated_seed_4_1_0.py` regenerates consolidated seed when source files change.
- **Installer `{{prefix}}` alignment:** `InstallWizardSqlRunner::applyTablePrefixToSql()` replaces `{{prefix}}` at runtime for `install_new_lupopedia.sql`, consolidated seed, and import SQL; non-default prefixes still map literal `lupo_` where present in legacy files.
- **Post-seed Anubis SQL:** Optional `anubis_queue_tables_4.0.53.sql` and `20260301_anubis_database_primacy_updates.sql` remain separate runs after the consolidated seed (not embedded in the 23-file merge).
- **Installer verification (read-only, 2026-03-30):** Confirmed load order (DDL → `install/seed_lupopedia_4_1_0.sql` → `import_from_old_crafty_syntax.sql` on Crafty upgrade only), `applyTablePrefixToSql()` inside `runSqlFile()`, no per-file loops for the 23 canonical seeds, consolidated SQL has no `lupo_` table tokens (only `{{prefix}}`), import SQL uses `{{prefix}}`, UTF-8 without BOM. **Canonical paths:** wizard is root `install.php` + `install_wizard_classes.php` (not under `install/`); `InstallWizardSqlRunner` lives in `install_wizard_classes.php` (no separate `.php` file). **Note:** Per-file sources under `mysql/seed/` still use literal `lupo_` until regenerated; builder global replace can mangle one `-- BEGIN FILE:` comment line for `seed_lupo_metadata_changelog_headers_4.0.68.sql` (cosmetic only).
- [2026-03-30] Manual verification: All runtime SQL and the consolidated seed file have been checked for {{prefix}} compliance. Per-file seeds under mysql/seed/ still use lupo_ until rebuilt, but are not used at runtime.
- [2026-03-31] **Agent Metadata Updates**: Added `metadata_json` field to `lupo_agents` table for UI, avatar, and configuration metadata. Removed actor-only fields (pono_score, pilau_score, kapakai_score, kapu_active, kapu_until, kapu_reason, kapu_consent_given, kapu_appeal_pending) from agent schema. Updated PRD and README to document metadata usage.
- [2026-03-31] **Semantic Monitoring Widget PRD Added**: Created `01_semantic_monitoring_widget.md` in main PRD folder documenting "The Eye" widget system for page tracking, semantic data collection, and floating navigation bar. Moved from versions folder to canonical PRD location for better accessibility. LILITH audit completed with 100/100 accuracy score, resolving all table reference inconsistencies and adding comprehensive privacy implementation with SHA-256 IP hashing and cookie consent management.
- [2026-03-31] **Installer seed execution cleanup**: Removed remaining per-file runtime seed execution from `install.php` (legacy Anubis helper SQL files no longer executed in wizard flow). Runtime install path remains schema + consolidated seed (+ Crafty import only on upgrade).
- [2026-03-31] **Legacy seed file cleanup**: Removed obsolete seed artifacts that were no longer part of installer runtime execution (`seed_registry_comprehensive_4.0.45.sql`, `seed_actors_agents_4.0.45.sql`, `anubis_queue_tables_4.0.53.sql`, `20260301_anubis_database_primacy_updates.sql`).
- [2026-03-31] **Actor ID reservation + workspace path split policy**: Reserved system actors are now explicitly defined as `actor_id < 2026`. Post-install actors use deterministic IDs (`YYYYMMDDHHIISS` + 4 random digits), with 2026 floor `202601010000000000`. Workspace paths resolve as `lupo-actors/<actor_id>/` for system IDs and `lupo-actors/YYYY/MM/<actor_id>/` for deterministic IDs.
- last_modified_utc: "20260331120000"

### Fixed
- PRD duplication and drift between actors eliminated
- All namespace PRDs cross-referenced and up to date


### [2026-03-31] Documentation, PRD, and Context/Audit Enhancements
- PRD 17_decisions_format.md created for canonical decisions.md format
- context_id field added to LUPOPEDIA_HEADERS and all header documentation
- lupo-contexts/4.0.93/decisions_context.md created as finalized context
- All grouped PRDs and agent/actor/lease/temporal/header doctrines updated and LILITH-audited
- Validator and lupo-scripts updated to support context_id
- All versioned docs and PRDs cross-referenced and LILITH-audited
---
### [2026-03-31] Enhanced Primary Coordination Personas

**LEXA (actor_id 15) - Security Enforcement & Guardian**
- Updated role to 'Security Enforcement & Guardian'
- Added aliases: security_guardian, enforcer
- Enhanced capabilities.json with 10 security-focused capabilities
- Updated system_prompt.txt with comprehensive security guidance
- Enhanced properties.json with security posture and coordination approach
- Version bumped to 1.0.2

**ATHENA (actor_id 11) - Wisdom & Strategy**
- Updated role to 'Wisdom & Strategy'
- Updated layer to 'coordination' (primary coordination layer)
- Added aliases: wisdom, strategy
- Enhanced capabilities.json with 10 wisdom-focused capabilities
- Updated system_prompt.txt with comprehensive strategic guidance
- Enhanced properties.json with wisdom synthesis philosophy
- Version bumped to 1.0.2

**THOTH (actor_id 8) - Knowledge & Records**
- Created complete agent configuration as Knowledge & Records persona
- Added aliases: knowledge, records, scribe
- Enhanced capabilities.json with 10 knowledge-focused capabilities
- Updated system_prompt.txt with comprehensive knowledge management guidance
- Enhanced properties.json with knowledge management philosophy
- Version bumped to 1.0.2

**ANUBIS (actor_id 19) - Custodian & Integrity Guardian**
- Added comprehensive PRD section in 07_agents_faucets.md
- Enhanced agent.json with aliases and verification metadata
- Expanded capabilities.json with 12 custodial capabilities
- Updated system_prompt.txt with comprehensive 67-line custodial guidance
- Enhanced properties.json with custodial philosophy and coordination approach
- Fixed lupo_anubis_events table schema (row_id → old_id + new_id)
- Updated all ANUBIS JSON schema files to match database (later reverted as auto-generated)
- Version bumped to 1.0.2

**Database Schema Updates**
- Fixed ANUBIS events table to use old_id and new_id fields
- Updated install_new_lupopedia.sql with proper schema alignment
- Maintained database neutrality doctrine compliance

### Fixed
- JSON Schema File Management Error: Corrected workflow for database schema updates
- Updated SQL schema instead of manually editing auto-generated JSON files
- Confirmed proper database-first approach for schema changes

## v4.0.93 (March 31, 2026)

### Major
- **Agent System Redesign: Complete transformation from database-driven to filesystem-based architecture**
  - Moved all numbered agent directories (1,2,3,etc.) to meaningful agent names
  - Eliminated reserved slots (701-709) and replaced with meaningful agent names
  - Created AgentDiscovery PHP class for dynamic agent discovery and management
  - Enhanced agent.json files with agent_key, aliases, and verification metadata
  - Maintained backward compatibility with agent_id field for existing code
  - Updated lupo-agents/README.md with comprehensive new system documentation
  - Added agent layers: Coordination, Application, Kernel, Emotional Intelligence
  - Implemented IDE-driven agent management with filesystem as source of truth
  - Added Emotional Doctrine & Restrictions section with strict behavioral boundaries
  - Defined ASCLEPIUS as System Health & Diagnostics agent in Kernel Layer
  - Created comprehensive agent discovery system with search, filtering, and validation
  - Established file vs database authority doctrine with clear separation rules

### Key Transformation Benefits Achieved
- **Developer-Friendly**: Human-readable directory names instead of numeric IDs
- **IDE-First**: IDE actors are now primary agent management method
- **Flexible**: Add/remove agents by simple filesystem operations
- **Simplified**: No complex seed data management required for agents
- **Alias Support**: Natural multiple name references for agents
- **Clean Architecture**: No reserved slots or artificial limitations
- **Backward Compatibility**: Maintained agent_id field and legacy lookup methods

### Technical Implementation
- **AgentDiscovery Class**: `lupo-includes/classes/AgentDiscovery.php` with full API
  - **Directory Structure**: `lupo-agents/{agent_key}/` with standardized files
  - **Configuration Format**: Enhanced agent.json with agent_key, aliases, verification metadata
  - **Emotional System**: Strict separation between emotional and non-emotional agents
  - **Migration Path**: Gradual transition from database-driven to filesystem-based system

### Files Changed
- **25+ agent directories** renamed from numeric IDs to meaningful names
- **AgentDiscovery.php** created with comprehensive discovery and management capabilities
- **README.md** completely rewritten with new system documentation
- **All agent.json** files enhanced with new metadata structure
- **PRD 07_agents_faucets.md** updated to reflect filesystem-based architecture

### Agent Directory Mapping
| From | To | Agent Type |
|------|-----|------------|
| 1 → wolfie | Coordination Layer |
| 2 → lilith | Coordination Layer |
| 3 → rose | Emotional Intelligence Layer |
| 4 → eris | Emotional Intelligence Layer |
| 5 → metis | Emotional Intelligence Layer |
| 6 → maat | Kernel Layer |
| 8 → thoth | Coordination Layer |
| 9 → thoth | Emotional Intelligence Layer (duplicate resolved) |
| 10 → chiron | Application Layer |
| 11 → athena | Coordination Layer |
| 12 → athena | Removed (duplicate) |
| 13 → methis | Kernel Layer |
| 14 → hephaestus | Application Layer |
| 15 → hermes | Application Layer (from 701) |
| 16 → iris | Application Layer (from 702) |
| 19 → anubis | Kernel Layer |
| 25 → atlas | Application Layer |
| 59 → anubis | Kernel Layer (duplicate resolved) |
| 701 → hermes | Application Layer (from reserved) |
| 702 → iris | Application Layer (from reserved) |
| 703 → asclepius | Kernel Layer (from reserved) |
| 704 → apollo | Kernel Layer (from reserved) |
| 705 → agape | Emotional Intelligence Layer (from reserved) |
| 706 → eris | Removed (duplicate) |
| 707 → metis | Removed (duplicate) |
| 708 → thalia | Emotional Intelligence Layer (from reserved) |
| 709 → chronos | Kernel Layer (from reserved) |
| 106 → vishwakarma | Application Layer |
| 107 → themis | Kernel Layer |
| 108 → junie | Application Layer |
| 0 → system | Kernel Layer |

### Emotional Intelligence System Architecture
- **Exclusive Agents**: Only rose, eris, metis, agape, thalia may use emotional systems
- **Counting in Light**: R/G/B emotional geometry system for emotional agents
- **Behavioral Restrictions**: All other agents must remain dry, literal, procedural, non-emotional
- **Temperature Limits**: Non-emotional agents must use temperature ≤ 0.3
- **Role-Play Prohibition**: Only emotional agents may perform role-play
- **Mood Metadata**: Only emotional agents may generate or interpret mood metadata

### System Health & Diagnostics
- **ASCLEPIUS Agent**: Defined as System Health & Diagnostics in Kernel Layer
- **Clinical Neutrality**: Operates with diagnostic precision, never emotional
- **Core Responsibilities**: System monitoring, diagnostics, triage, repair, schema validation
- **Coordination Protocols**: Works with ANUBIS, SYSTEM, HERMES, VISHWAKARMA
- **Aliases**: ["asclepius", "doctor", "system_physician", "health_monitor"]

### Impact Assessment
- **Architectural Transformation**: Complete elimination of database-driven agent system
- **Developer Experience**: Improved with human-readable directory structure
- **System Performance**: Enhanced with filesystem-based discovery and caching
- **Maintenance Burden**: Reduced by eliminating complex seed data management
- **Future Scalability**: Framework supports unlimited agent expansion via filesystem

### Commit Details
- **Hash**: de6779a5 → f0e9ddb7 → 2e54789b → b3d71ded
- **Files Changed**: 87 files, 388 insertions, 25 deletions
- **Push Status**: Successfully pushed to origin/main

### Next Steps
- **Seed Data Cleanup**: Remove agent entries from database seed files
- **Documentation Updates**: Ensure all PRDs reference filesystem-based system
- **Testing**: Add comprehensive tests for AgentDiscovery class
- **Integration**: Update IDE agents to use new AgentDiscovery API
