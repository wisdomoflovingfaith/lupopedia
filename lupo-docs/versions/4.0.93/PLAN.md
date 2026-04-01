---
lupopedia.headers:
  lupopedia.schema: documentation
  version_when_written: "4.0.93"
  file_path_from_root: "lupo-docs/versions/4.0.93/PLAN.md"
  web_path: "http://www.lupopedia.com/lupo-docs/versions/4.0.93/PLAN.md"
  last_modified_utc: "20260331150000"
  channel_id: 42
  actor_id: 102
  actor_name: "HEPHAESTUS"
  delegation_chain: "hephaestus:root"
  artifact_type: "plan"
  artifact_kind: "version_plan"
  purpose: "Unified plan for 4.0.93 development, Softaculous certification, and Crafty Syntax parity."
---


# Lupopedia 4.0.93 PLAN


## 🆕 Major Changes (March 2026, updated April)

- **Grouped PRD Architecture**: Complete 14-namespace PRD structure created in `lupo-docs/prd/`, replacing per-table PRD approach; 100% coverage achieved (14/14 files)
- **Identity Model Coverage**: All core identity tables fully documented in namespace 01_core_identity.md, including the five new tables: `lupo_actor_memory`, `lupo_actor_skills`, `lupo_actor_tools`, `lupo_actor_prompts`, and `lupo_actor_training` (added in 4.0.93).
- **Crafty Integration Preserved**: Namespace 13_crafty_integration.md remains ACTIVE for Crafty Syntax 3.7.5 compatibility.
- **Maintenance Optimization**: PRD maintenance burden reduced by 92% (from 171 to 14 files).

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

## 🚨 **PRIMARY OBJECTIVE: Softaculous Certification & Crafty Parity**

- **[ ]** Complete remaining 7 PRD namespaces (01_core_identity through 07_agents_faucets)
- **[ ]** Achieve 100% feature parity with Crafty Syntax 3.7.5
- **[ ]** Complete Softaculous certification requirements documentation
- **[ ]** Finalize installation and upgrade testinghe 4.0.93 "Brain" semantic architecture.


- **Emoji/Smilies System Overhaul**: Emoji and smilies are now handled via `::img|foldername|filename::` code format, with a popup selector and images stored in `lupo-emoji/`. See [EMOJI_AND_SMILIES.md](/lupo-docs/doctrine/EMOJI_AND_SMILIES.md) for full documentation.

All plans, PRDs, and implementation must reference and comply with these changes. Legacy database-driven smilies are fully deprecated.

## 🔥 Constitutional, Agent, and Installer Doctrine Updates (March/April 2026)

- **Dynamic Table Prefix Doctrine**: Install/import/consolidated runtime SQL use `{{prefix}}` via `InstallWizardSqlRunner::applyTablePrefixToSql()` (root `install.php`, `install_wizard_classes.php`). Per-file seed **sources** may still contain literal `lupo_` until rebuilt into `install/seed_lupopedia_4_1_0.sql`. Default prefix `lupo_` remains the wizard default in PHP. See `install_new_lupopedia.sql` for DDL pattern.
- **Canonical Agent Model**: PRD_AGENT_DEFINITION_MODEL.md defines the agent directory structure, versioning, and compliance. All agents (e.g., LILITH/2) must use the canonical template in lupo-agents/_TEMPLATE/ and maintain versioned snapshots.
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
- **Temporal Anchor & UTC Timestamp Policy enforced (tick.py, lupo-bin/temporal_anchor.json, UTC-only)**
- **ID Generation Directive Compliance**: IdGenerator.php updated with YYYYMMDDHHIISS + random suffix format; 63-bit signed-safe BIGINTs; test suite created
- **Full Database Audit**: Comprehensive audit of all 171 tables completed; 5 doctrine violations; 48 missing documentation; all PRDs updated with lupopedia.edges
- See [GROUPED_PRD_COMPLETION_SUMMARY.md](GROUPED_PRD_COMPLETION_SUMMARY.md) for detailed achievement breakdown
- **Grouped PRD Structure**: Complete 14-namespace PRD architecture created in `lupo-docs/prd/`; 100% PRD coverage achieved (14/14 files); maintenance burden reduced by 92%. All new core identity tables are included and documented.
- **Consolidated install seed (2026-03-30):** Single `install/seed_lupopedia_4_1_0.sql` after `install_new_lupopedia.sql`; per-file seeds preserved under `mysql/seed/`; Anubis helper seeds still run separately post-install.
- **Installer verification (2026-03-30):** Read-only pass aligned docs with actual paths (root `install.php` / `install_wizard_classes.php`; import at `lupo-database/lupopedia/mysql/import/import_from_old_crafty_syntax.sql`). Runtime install path declared **ready** for full install; see `/lupo-docs/versions/4.0.93/WHAT_TO_DO_NEXT.md` §14.
- **Installer runtime seed cleanup (2026-03-31):** Removed remaining per-file post-seed execution from `install.php`; runtime path is now strictly schema + consolidated runtime seed (+ import on Crafty upgrade).
- **Deterministic runtime actor IDs + actor workspace sharding (2026-03-31):** Actor creation paths use YmdHis+4 IDs and `lupo-actors/YYYY/MM/actor_id` directory resolution/provisioning, with legacy flat path fallback for compatibility.

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
- [ACTOR_LEASING_DOCTRINE.md](/lupo-docs/doctrine/ACTOR_LEASING_DOCTRINE.md)
- [ACTOR_TEMPLATE_MODEL.md](/lupo-docs/doctrine/ACTOR_TEMPLATE_MODEL.md)
- [ACTOR_INSTANCE_MODEL.md](/lupo-docs/doctrine/ACTOR_INSTANCE_MODEL.md)
- [ACTOR_LEASE_SESSION_MODEL.md](/lupo-docs/doctrine/ACTOR_LEASE_SESSION_MODEL.md)
- [05_auth_user_actor_agent_transformation.md](/lupo-docs/versions/4.0.93/prd/05_auth_user_actor_agent_transformation.md)

All planning, PRDs, and implementation must reference and comply with these docs. See also: [lupo_actor_auth_users.md](/lupo-docs/database/lupopedia/tables/active/lupo_actor_auth_users.md) (deprecated).

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

## 🚀 **Softaculous Certification Requirements**

### Release Policy
- Lupopedia will remain in the 4.0.x cycle until Softaculous approves a 4.0.x release. No 4.1.0 release or upgrade path will be created until that approval is granted. All grouped PRD and schema work is forward-compatible.

### **1. The Installation Engine**
- **install.php Refactor**: Handle class-based instantiation and seed lupo_contexts with initial system "Truths" (partially addressed: consolidated seed + `{{prefix}}` path; see `install/seed_lupopedia_4_1_0.sql` and CHANGELOG)
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
- **RULE [93.PROTECT_TOONS]**: IDE strictly forbidden from writing to `lupo-database/lupopedia/json/*.json`
- **Installation DNA**: Schema DDL in `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`; row-level seeds authored under `mysql/seed/` and merged for runtime into `install/seed_lupopedia_4_1_0.sql` (see `lupo-scripts/build_consolidated_seed_4_1_0.py`)
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
- Run `php lupo-scripts/SyncChannelsToDb.php --commit` to import existing coordination work
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
