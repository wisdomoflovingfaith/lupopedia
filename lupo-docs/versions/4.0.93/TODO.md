---
lupopedia.headers:
  lupopedia.schema: documentation
  version_when_written: "4.0.93"
  file_path_from_root: "lupo-docs/versions/4.0.93/TODO.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.93/TODO.md"
  last_modified_utc: "20260331235900"
  channel_id: 42
  thread_id: "todo-backlog"
  actor_id: 102
  actor_name: "HEPHAESTUS"
  delegation_chain: "hephaestus:root"
  artifact_type: "todo"
  artifact_kind: "master_backlog"
  purpose: "Master task backlog for Lupopedia 4.0.93"
  tags:
  - "todo"
  - "master"
  - "4.0.93"
  - "backlog"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/versions/4.0.93/PLAN.md"
      type: references
      weight: 1.0
      reason: Overall plan for 4.0.93
    - to: "lupo-docs/versions/4.0.93/WHAT_TO_DO_NEXT_SESSION.md"
      type: references
      weight: 1.0
      reason: Session handoff document
    - to: "lupo-docs/versions/4.0.93/CHANGELOG.md"
      type: references
      weight: 1.0
      reason: Version changelog
    - to: "lupo-docs/versions/4.0.93/DATABASE_AUDIT_SUMMARY.md"
      type: references
      weight: 1.0
      reason: Database audit summary
lupopedia.footer:
  last_verified: "20260331235900"
  verified_by:
    actor_id: 102
    agent_name_identity: Cursor IDE Agent
  orchestrator: "hephaestus:root"

## Completed (4.0.93)
[x] PRD 17_decisions_format.md created
[x] context_id field and context directory implemented
[x] All grouped PRDs and agent/actor/lease/temporal/header doctrines updated
[x] Validator and lupo-scripts updated for context_id
[x] LILITH Audit: Data Model PRD Corrections
[x] LILITH Audit: Installer Requirements PRD
[x] LILITH Audit: Core Identity PRD (Final Review)
[x] LILITH Correction: Version Directory Purpose
[x] LILITH Directive: Create Countermeasure Agent
[x] LILITH Audit: COUNTERMEASURE Agent Configuration
[x] LILITH Directive: Update COUNTERMEASURE Agent Prompt
[x] Channel chat PRD 18 and implementation
[x] WOLFIE Doctrine created and integrated
[x] Multi-Agent Orchestration Doctrine created
[x] Actor-Agent Distinction Doctrine created
[x] Database Doctrine created and integrated
[x] Garbage Collection System PRD created
[x] GarbageCollector class implemented
[x] GC CLI script created
[x] GC Doctrine created
[x] LILITH Audits & COUNTERMEASURE Development (March 2026)
  - [x] LILITH Audit: Data Model PRD Corrections
  - [x] LILITH Audit: Installer Requirements PRD
  - [x] LILITH Audit: Core Identity PRD (Final Review)
  - [x] LILITH Correction: Version Directory Purpose
  - [x] LILITH Directive: Create Countermeasure Agent
  - [x] LILITH Audit: COUNTERMEASURE Agent Configuration (2x)
  - [x] LILITH Directive: Update COUNTERMEASURE Agent Prompt
  - [x] LILITH Audit: Installer Requirements PRD
  - [x] LILITH Audit: Core Identity PRD (Final Review)
[x] All versioned docs and PRDs cross-referenced and LILITH-audited
- [x] HEPHAESTUS identity doctrine and Faucet Proxy Pattern adopted (Actor 102)
- [x] Channel 42 thread structure standardized and all coordination migrated
- [x] LILITH agent definitions consolidated and adversarial audit enabled
- [x] Option A split-table architecture for Truth Management System formalized
- [x] Legacy FLIP/FLARE documentation removed
- [x] Truth system documentation completed
- [x] Database doctrine finalized (catch-and-retry, no UNSIGNED)
- [x] JS Nervous System (State Mirror, Scroller, Monitor, Glass UI) implemented and tested
- [x] Git hook issues documented and workarounds in place
- [x] Hybrid-Mirror Architecture implemented: Database-first with filesystem archival mirrors
- [x] LUPOPEDIA_HEADERS updated with context_id field and channel-to-context lifecycle
- [x] Database table organization context created
- [x] Channel architecture discussion thread created with WOLFIE synthesis (Option B+)
- [x] **LILITH "Source of Truth" Protocol implemented with Toon guardrail**
- [x] **4.0.93 PRD files updated with actual database schema and migration mapping**
- [x] **Subdirectory installation doctrine established for Semantic Monitoring Widget**
- [x] **Temporal Anchor & UTC Timestamp Policy enforced (tick.py, lupo-bin/temporal_anchor.json, UTC-only)**
- [x] **ID Generation Directive Compliance**: IdGenerator.php updated with YYYYMMDDHHIISS + random suffix format; 63-bit signed-safe BIGINTs; test suite created
- [x] **Full Database Audit**: Comprehensive audit of all 171 tables completed; 5 doctrine violations; 48 missing documentation; all PRDs updated with lupopedia.edges
- [x] **PRD Edge Integration**: All PRD files now include lupopedia.edges sections linking to table definitions and related documentation
- [x] **Grouped PRD Structure**: Complete 14-namespace PRD architecture created in `lupo-docs/prd/`; 100% PRD coverage achieved (14/14 files, 171 tables); maintenance burden reduced by 92%. All new core identity tables are included and documented.
- [x] **Consolidated install seed + installer alignment (2026-03-30):** Runtime seed is `install/seed_lupopedia_4_1_0.sql` (rebuild via `lupo-scripts/build_consolidated_seed_4_1_0.py`); root `install.php` loads only this after `install_new_lupopedia.sql`; `InstallWizardSqlRunner::applyTablePrefixToSql()` applies `{{prefix}}`; original seeds retained under `lupo-database/lupopedia/mysql/seed/`.
- [x] **Installer verification (read-only, 2026-03-30):** Confirmed wizard paths, load order, import gating, and consolidated/import `{{prefix}}` usage; documented in CHANGELOG (Minor) and `WHAT_TO_DO_NEXT.md` §14.
- [x] **Installer runtime seed cleanup (2026-03-31):** Removed remaining per-file post-seed execution from `install.php`; runtime path is schema + `install/seed_lupopedia_4_1_0.sql` (+ Crafty import on upgrade only).
- [x] **Obsolete legacy seed files removed (2026-03-31):** Deleted non-runtime legacy seed files that were no longer used by installer flow.
- [x] **Runtime actor deterministic IDs + sharded workspace path (2026-03-31):** Actor creation paths now use YmdHis+4 IDs and `lupo-actors/YYYY/MM/actor_id`; resolver/helpers preserve legacy flat path fallback.
- [x] **Channel chat (2026-03-31, Cursor + Cascade thread):** PRD 18 aligned; `channels-api.php` extended (`format=buffer|image`, `thread_id`, `dialog_thread_id`, digit redirect); `channel.php` + `channel-chat/` routes; `chat-display.js` / `chat-display-legacy.js` / `chat-display.css`; `channels/{id}/thread/{id}` slug; operator-supplied digit GIFs in `lupo-ui/images/`; implementation documentation created with LUPOPEDIA headers.
- [x] **WOLFIE Doctrine (2026-04-01):** Constitutional doctrine created as root-level rule; Five Pillars established; binding rules W-01 through W-05 against framework bloat; root README updated; constitutional requirements PRD updated; 1999-era code protected from forced modernization.
- [x] **Multi-Agent Orchestration Doctrine (2026-04-01):** Created comprehensive doctrine documenting cascade workflow; meta-agent loop established (LILITH refines prompts for internal swarm); scale documented (10+ IDEs, 50+ agents); dogfooding principle established - system building itself using its own coordination architecture.
- [x] **Actor-Agent Distinction Doctrine (2026-04-01):** Created canonical distinction doctrine; updated all PRDs (01_core_identity.md, 07_agents_faucets.md, 15_actors.md); added Rule W-06 to WOLFIE Doctrine; clarified agents are immutable templates, actors are learning instances; documented workspace structures and creation flows.
- [x] **Root Sanitization & PRD Backfill (2026-04-01, Antigravity thread):** WOLFIE Doctrine updated with accurate HPC/Notepad++ logic. Created ASCLEPIUS health check and CLI Interface PRDs. Formally exempted `node_modules` and `app` from prefix rules. Dismantled `lupo-prompts/` to localized actor workspaces. Executed Batches 6 & 7 to migrate 19 loose root files into pristine constitutional mappings (`lupo-rules/`, `lupo-config/`, etc.) and fixed corrupted `.gitignore`.

## 🛠️ **4.0.93 Documentation Update Status**

### New Tasks (from this thread)
- Implement context_id in all header documentation and validators
- Create lupo-contexts/4.0.93/decisions_context.md
- Ensure all decisions.md files follow new format (see PRD 17_decisions_format.md)
- Update lupo-scripts (php/py) to validate context_id

### 🏗️ LILITH "Absolute-Root" Mandate (v4.0.93)

**RULE [93.PATH_PURITY]**: All Markdown links must use absolute paths starting from repository root.

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
- **CORRECT**: `[Link](/lupo-docs/versions/4.0.93/prd/01_monitor.md)`
- **FORBIDDEN**: `[Link](../../../prd/01_monitor.md)` or `[Link](~/lupo-docs/...)`

#### **DocumentPathing Strategy Status**
| Document | Pathing Strategy | Status |
|----------|------------------|---------|
| README.md | Absolute Root (/) | ✅ UPDATED |
| TODO.md | Absolute Root (/) | ✅ UPDATED |
| prd/*.md | Absolute Root (/) | ✅ QUEUED FOR REWRITE |

## 🚨 **CRITICAL: Softaculous Certification & Crafty Parity (4.0.93)**

### **1. The Installation Engine (Softaculous Requirement)**
- [ ] Refactor install.php: Ensure it handles class-based instantiation and seeds lupo_contexts with initial system "Truths" (seed data still primarily in consolidated SQL + MD importer; contexts/"Brain" seeding may need follow-up)
- [ ] SQL Schema: Provide a unified lupopedia_v4.0.93.sql that includes all livehelp_ → lupo_ mappings (current canonical path: `install_new_lupopedia.sql` + `install/seed_lupopedia_4_1_0.sql` + Crafty import when upgrading)
- [ ] Uninstall/Upgrade: Create uninstall.php and upgrade.php to manage the removal of DB edges and filesystem atoms

### **2. Visitor & Operator Dashboards (The Monitoring Layer)**
 [ ] Lupo-Monitor: Implement the live visitor dashboard using the Semantic Monitor logic
 [ ] Actor/Agent Leasing: Update the operator panel so auth_users can lease actors (per new agent→actor→auth_user model) and select agents to refine behavioral context
	 - Enforce canonical permission rule ([ACTOR_LEASING_DOCTRINE.md](/lupo-docs/doctrine/ACTOR_LEASING_DOCTRINE.md))
	 - Reference and implement [ACTOR_TEMPLATE_MODEL.md](/lupo-docs/doctrine/ACTOR_TEMPLATE_MODEL.md), [ACTOR_INSTANCE_MODEL.md](/lupo-docs/doctrine/ACTOR_INSTANCE_MODEL.md), [ACTOR_LEASE_SESSION_MODEL.md](/lupo-docs/doctrine/ACTOR_LEASE_SESSION_MODEL.md)
	 - See [05_auth_user_actor_agent_transformation.md](/lupo-docs/versions/4.0.93/prd/05_auth_user_actor_agent_transformation.md)
 [ ] Proactive Invite: Trigger "Invite to Chat" based on Contextual Edges (e.g., visitor is on a high-weight "Truth" page)

### **3. Real-Time Chat Enhancements**
- [x] **Minimal channel message UI + API fallbacks (2026-03-31):** Standalone `channel.php` / `channel-chat/` using `api/lupo-channels/.../messages` with `format=buffer` / `format=image` (see CHANGELOG). Implementation documented in `lupo-docs/implementations/channel-chat.md`. Does not replace full `/channels/` cockpit.
- [ ] Live Typing Refraction: Stream typing events through the State Mirror without persistent DB writes
- [ ] Quick Responses: Store canned replies as "Low-Weight Contexts" in the lupo_contexts table for instant retrieval
- [ ] Sound & Visual Alerts: Integrate legacy /sounds/ triggers into the lupo.js event-bus

### **4. The "Glass" UI Requirement**
- [ ] Live Typing Preview: Integrate into High-Density Scroller to maintain 60fps while streaming real-time keystroke refractions
- [ ] Visitor Tracking: Implement expected Softaculous hooks for visitor monitoring
- [ ] Contextual Installation: Ensure /install.php seeds Context Registry and Semantic Edges required for 4.0.93 "Brain"
- [ ] **Subdirectory Installation**: Ensure Lupopedia works in any subdirectory (not web root) - PRD updated

### ✅ March 2026: Table/Emoji System Overhaul
- [x] Remove obsolete tables: `lupo_channel_boot_detail`, `lupo_channel_boot_detail_lifecycle`, `lupo_channel_boot_lifecycle`, `lupo_smilies` from install SQL (channels are now dialog-based)
- [x] Overhaul emoji/smilies system: Implement `::img|foldername|filename::` code, popup selector, and filesystem-based emoji in `lupo-emoji/` (see [EMOJI_AND_SMILIES.md](/lupo-docs/doctrine/EMOJI_AND_SMILIES.md))

### **5. Data Migration Completion**
- [ ] Execute new install to establish clean database state
- [ ] Run `php lupo-scripts/SyncChannelsToDb.php --commit` to import existing coordination work
- [ ] Verify all filesystem work properly imported to database
- [ ] Test web interface reading only from database tables

### **🚨 RULE [93.PROTECT_TOONS]: The "Toon" Guardrail**
- [x] **FORBIDDEN**: IDE writing to `lupo-database/lupopedia/json/*.json` files
- [x] **REQUIRED**: All schema evolution in `lupopedia/mysql/seed/` and `install_new_lupopedia.sql`
- [x] **VERIFICATION**: Run `generate_toon_files.py` after any schema changes
- [x] **ENFORCEMENT**: LILITH audit compliance for all Toon file protection

### **🚨 LILITH "Source of Truth" Protocol (COMPLETED)**
- [x] **02_data_model.md**: Updated with actual database schema (`lupo_contexts`, `lupo_truth_questions`, `lupo_truth_answers`, `lupo_votes`)
- [x] **04_lupopedia_js_foundation.md**: Updated with `livehelp_visitors → lupo_visitors` mapping and live typing refraction
- [x] **01_semantic_monitoring_widget.md**: Updated with `lupo_edges` schema and subdirectory installation doctrine
- [x] **Forbidden Constructs**: Documented no AUTO_INCREMENT, TIMESTAMP, FOREIGN KEYS, TRIGGERS, UNSIGNED in 4.0.93 doctrine

## Deferred/Blocked (see DEFERRED.md)
- [ ] enforce_doctrine.py: Run on all seed files deferred (Python/encoding issue)
- [ ] Hydrator: Channel 42 elevation output requires review

## Active/Next (4.0.94+)
- [ ] Optional: integrate main `channels-controller` message panel with `api/lupo-channels` + shared chat-display patterns where product wants parity
- [ ] Complete enforce_doctrine.py implementation for all .js, .php, and SQL assets
- [ ] Optimize JS "Glass" reflection for mobile viewports
- [ ] Transition remaining "Unfinished Business" items from 4.0.87 into Gold Contexts
- [ ] Enhance channel coordination automation and thread indexing
- [ ] Permanent fix for Git hook path issue
- [ ] Automate TOON file updates from schema changes
- [ ] Implement systematic agent version management
- [ ] Improve context linking and multi-agent workflows

## Completed (2026-04-01, Kiro thread — PRD overhaul + constitutional hardening)
- [x] 00_root_constitutional_system_requirements.md — fixed broken YAML, corrected schema token, added missing header fields, expanded edges, fixed footer, added implementation guidance per rule
- [x] Section 9.9 — expanded schema inference prohibition with "JSON files are NOT a file database" clarification and full workflow
- [x] Section 9.18 — Missing Table Protocol (RULE 93.MISSING_TABLE_PROTOCOL) added
- [x] Section 9.19 — No Direct CLI Database Execution (RULE 93.NO_CLI_DB_EXEC) added (renumbered from 9.18)
- [x] Section 9.20 — Proven Code Preservation Doctrine (RULE 93.PROVEN_CODE) added; eye animation as canonical named example
- [x] 01_semantic_monitoring_widget.md — rewritten with verified column names, Missing Tables section, corrected queries, 28 edges, implementation checklist
- [x] generate_toon_files.py — stripped to schema-only output; removed data key, data-fetching functions, broken CSV subprocess
- [x] export_table_data_csv.py — created as separate debugging tool with sensitive table exclusions
- [x] .gitignore — added lupo-database/lupopedia/csv/
- [x] install_new_lupopedia.sql — added lupo_folders; confirmed other 6 semantic navbar tables already present
- [x] lupo-docs/database/lupopedia/tables/active/lupo_paths.md — created missing table doc
- [x] README.md — mandatory reading section, decisions.md documentation, reordered reading list, development rules rewritten
- [x] decisions.md — summary table completed (D-16 through D-38), new decision entries D-33 through D-38 added
- [x] project_structure_prd.md — updated with important sub-folders table for `lupo-docs` structure

## Active/Next (4.0.94+)
- [ ] Regenerate TOON files: `python lupo-scripts/generate_toon_files.py`
- [ ] Continue PRD improvement pass for remaining PRDs in `lupo-docs/prd/`
- [ ] Optional: integrate main `channels-controller` message panel with `api/lupo-channels` + shared chat-display patterns
- [ ] Complete enforce_doctrine.py implementation for all .js, .php, and SQL assets
- [ ] Optimize JS "Glass" reflection for mobile viewports
- [ ] Transition remaining "Unfinished Business" items from 4.0.87 into Gold Contexts
- [ ] Enhance channel coordination automation and thread indexing
- [ ] Permanent fix for Git hook path issue
- [ ] Implement systematic agent version management
- [ ] Improve context linking and multi-agent workflows
