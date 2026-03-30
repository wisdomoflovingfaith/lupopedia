---
lupopedia.headers:
  lupopedia.schema: documentation
  version_when_written: "4.0.93"
  file_path_from_root: "lupo-docs/versions/4.0.93/PLAN.md"
  web_path: "http://www.lupopedia.com/lupo-docs/versions/4.0.93/PLAN.md"
  last_modified_utc: "20260330"
  channel_id: 42
  actor_id: 102
  actor_name: "HEPHAESTUS"
  delegation_chain: "hephaestus:root"
  artifact_type: "plan"
  artifact_kind: "version_plan"
  purpose: "Unified plan for 4.0.93 development, Softaculous certification, and Crafty Syntax parity."
---

# Lupopedia 4.0.93 PLAN

## 🚨 **PRIMARY OBJECTIVE: Softaculous Certification & Crafty Parity**

**Goal**: Achieve 100% feature parity with Crafty Syntax 3.7.5 while enforcing the Lupopedia Actor-Agent Doctrine and maintaining the 4.0.93 "Brain" semantic architecture.

## Completed (4.0.93)
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

## 🚀 **Softaculous Certification Requirements**

### **1. The Installation Engine**
- **install.php Refactor**: Handle class-based instantiation and seed lupo_contexts with initial system "Truths"
- **Unified SQL Schema**: Provide lupopedia_v4.0.93.sql with all livehelp_ → lupo_ mappings
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
- **Installation DNA**: All schema evolution must be in `lupopedia/mysql/seed/` and `install_new_lupopedia.sql`
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
