# Lupopedia 4.0.93 TODO (Master Backlog)

## Completed (4.0.93)
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

## 🚨 **CRITICAL: Softaculous Certification & Crafty Parity (4.0.93)**

### **1. The Installation Engine (Softaculous Requirement)**
- [ ] Refactor install.php: Ensure it handles class-based instantiation and seeds lupo_contexts with initial system "Truths"
- [ ] SQL Schema: Provide a unified lupopedia_v4.0.93.sql that includes all livehelp_ → lupo_ mappings
- [ ] Uninstall/Upgrade: Create uninstall.php and upgrade.php to manage the removal of DB edges and filesystem atoms

### **2. Visitor & Operator Dashboards (The Monitoring Layer)**
- [ ] Lupo-Monitor: Implement the live visitor dashboard using the Semantic Monitor logic
- [ ] Actor/Agent Nexus: Update the operator panel so auth_users can select Agents to refine their behavioral context
- [ ] Proactive Invite: Trigger "Invite to Chat" based on Contextual Edges (e.g., visitor is on a high-weight "Truth" page)

### **3. Real-Time Chat Enhancements**
- [ ] Live Typing Refraction: Stream typing events through the State Mirror without persistent DB writes
- [ ] Quick Responses: Store canned replies as "Low-Weight Contexts" in the lupo_contexts table for instant retrieval
- [ ] Sound & Visual Alerts: Integrate legacy /sounds/ triggers into the lupo.js event-bus

### **4. The "Glass" UI Requirement**
- [ ] Live Typing Preview: Integrate into High-Density Scroller to maintain 60fps while streaming real-time keystroke refractions
- [ ] Visitor Tracking: Implement expected Softaculous hooks for visitor monitoring
- [ ] Contextual Installation: Ensure /install.php seeds Context Registry and Semantic Edges required for 4.0.93 "Brain"

### **5. Data Migration Completion**
- [ ] Execute new install to establish clean database state
- [ ] Run `php lupo-scripts/SyncChannelsToDb.php --commit` to import existing coordination work
- [ ] Verify all filesystem work properly imported to database
- [ ] Test web interface reading only from database tables

## Deferred/Blocked (see DEFERRED.md)
- [ ] enforce_doctrine.py: Run on all seed files deferred (Python/encoding issue)
- [ ] Hydrator: Channel 42 elevation output requires review
- [ ] 1,000 ID generation CLI test: Deferred (see DEFERRED.md)

## Active/Next (4.0.94+)
- [ ] Complete enforce_doctrine.py implementation for all .js, .php, and SQL assets
- [ ] Optimize JS "Glass" reflection for mobile viewports
- [ ] Transition remaining "Unfinished Business" items from 4.0.87 into Gold Contexts
- [ ] Enhance channel coordination automation and thread indexing
- [ ] Permanent fix for Git hook path issue
- [ ] Automate TOON file updates from schema changes
- [ ] Implement systematic agent version management
- [ ] Improve context linking and multi-agent workflows
