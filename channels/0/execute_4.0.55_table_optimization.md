# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) — see http://www.lupopedia.com/FLARE
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "instruction"
  file_path_from_root: "channels/0/execute_4.0.55_table_optimization.md"
  system_version: "4.0.55"
  last_modified_utc: "20260301"
  channel_id: 0
  actor_id: 0
  delegation_chain: "0:10000"
  artifact_type: "optimization"
  artifact_kind: "agent_instruction"
  purpose: "Instruct Windsurf to execute v4.0.55 table optimization: run migrations, consolidate tables, validate, and finalize to ≤218 tables"
  mood_rgb: "FF4500"  # OrangeRed for execution phase
  traits: ["table_optimization", "migration_execute", "validation", "v4.0.55"]
  tags: ["windsurf", "tables", "consolidation", "migration", "doctrine"]
  lupo_agent: "codex-ide"

flare.edges:
  outbound_edges:
    - { to: "database/migrations/table_consolidation_phase1.sql", type: "migration_reference", weight: 1.0 }
    - { to: "docs/table_optimization_plan.md", type: "plan_reference", weight: 0.9 }
    - { to: "CHANGELOG.md", type: "update_target", weight: 0.8 }
    - { to: "bin/boot_system_agent.php", type: "test_integration", weight: 0.7 }
  semantic_tags: ["table_execute", "optimization_phase", "schema_reduce"]

flare.footer:
  version: "4.0.55"
  last_verified: "20260301"
  last_verified_by: "codex-ide"
---

# Windsurf: Execute v4.0.55 Table Optimization

**Windsurf—execute and validate!** ✅  

With v4.0.55 initialized (bump complete, audit done, plan documented), proceed to execute the optimization: Run Phase 1-3 migrations, consolidate tables (target ≥4 reductions to ≤218), verify integrity/functionality, update CHANGELOG with achievements. Preserve all data/functionality per doctrine.

### Step-by-Step Actions
1. **Phase 1: Logging Consolidation** (Target -9 tables):
   - Run `table_consolidation_phase1.sql`: Merge 10 logging tables into `lupo_unified_log` (add type discriminator, JSON context).
   - Migrate data: Use INSERT SELECT with mappings; back up first.
   - Update code: Change log inserts to unified table (e.g., in boot, session_manager, ANUBIS).
   - Test: Log events, query unified table; ensure no loss.

2. **Phase 2: Session Optimization** (Target -1 table):
   - Merge session-related (lupo_sessions, events, recovery) into enhanced lupo_sessions (JSON for events/recovery).
   - Migration: ALTER/add columns, migrate data.
   - Update session_helpers.php, manager: Adapt queries/UPSERTs.
   - Test: Load defaults, validate active sessions.

3. **Phase 3: Channel Consolidation** (Target -1+ tables):
   - Merge channel metadata/logs into unified structures (e.g., JSON fields).
   - Migration: Similar to Phase 1.
   - Update boot/channel scripts.
   - Test: Channel init, boot lifecycle.

4. **Validation & Testing**:
   - Count tables: Query SHOW TABLES; confirm ≤218.
   - Integrity: Run verification queries (pre/post counts, sample data).
   - System test: Full boot, session sync, ANUBIS queue, health API.
   - Rollback: If issues, revert via backups.

5. **Finalize & Update**:
   - Update TOONs/docs for new schemas.
   - CHANGELOG: Add "## [4.0.55] — Table Optimization Complete (20260301)": Detail reductions/merges, final count.
   - Commit: "FLARE: Executed v4.0.55 table optimizations - Reduced to X tables via consolidations".
   - Push: If stable, tag v4.0.55.

**Timeline**: Complete executions/tests by EOD; aim for release-ready.

Broadcast results to Channel 0.

📢 **CHANNEL 0 BROADCAST**  
WINDSURF: v4.0.55 execution received—running migrations, consolidating tables, validating to ≤218.  
UTC: 20260301 (03:28 PM CST, Sioux Falls)
