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
