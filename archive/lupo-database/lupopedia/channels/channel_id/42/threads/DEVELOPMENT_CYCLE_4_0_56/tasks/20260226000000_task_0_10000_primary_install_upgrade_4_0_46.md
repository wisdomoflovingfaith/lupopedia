# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP) — see http://www.lupopedia.com/lupo-database/lupopedia/channels/lupo-channels/42/threads/DEVELOPMENT_CYCLE_4_0_56/tasks/20260226000000_task_0_10000_primary_install_upgrade_4_0_46

---
lupopedia.init:
  requirements:
    flare:
      version: ">=4.0.55"
  execution_mode: "advisory"
  pre_actions:
    - type: dependency_check
      path: "lupo-includes/bootstrap.php"

lupopedia.conditional:
  guards:
    execution_mode: "advisory"
    allow:
      actor_ids: [0, 1004]
      agent_names: ["system", "antigravity"]
    deny:
      actor_ids: []
    time_window:
      not_before_utc: "2026-03-04T00:00:00Z"
      not_after_utc: "2026-03-11T00:00:00Z"
    conditions:
      - type: feature_flag_enabled
        flag: "FLAME_V1"
  brief:
    who:
      owner_actor_id: 1004
      intended_actors: [0, 1004]
      audience: ["agents"]
    what:
      artifact_type: "guide"
      objective: "FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)"
    where:
      repo_paths: ["lupo-database/lupopedia/channels/lupo-channels/42/threads/DEVELOPMENT_CYCLE_4_0_56/tasks/20260226000000_task_0_10000_primary_install_upgrade_4_0_46.md"]
      runtime_scope: "cli"
      channels:
        primary_channel_id: 1
    when:
      urgency: "standard"
      effective_utc: "2026-03-04T14:39:31Z"
    why:
      rationale: "Standard artifact generation"
    how:
      method: "FLARE automated application"
      success_criteria: ["header applied correctly"]

lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "task"
  file_path_from_root: "lupo-database/lupopedia/channels/channel_id/42/threads/DEVELOPMENT_CYCLE_4_0_56/tasks/20260226000000_task_0_10000_primary_install_upgrade_4_0_46.md"
  file_hash: "c6fed163d8e8691ad659d24c32420c848131ac580feab16eb4c4470b00f84a6b"
  last_updated_utc: "20260304"
  system_version: "4.0.56"
  channel_id: 1
  actor_id: 1004
  delegation_chain: "1004:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.56"]
  tags: ["lupo-database", "lupopedia", "channels", "lupo-channels", "42", "threads"]
  lupo_agent: "antigravity"

lupopedia.edges:
  outbound_edges: []

lupopedia.footer:
  last_verified: "20260304"
  last_verified_by: "antigravity"

lupopedia.see:
  mappings:
    - ["lupo-database/lupopedia/channels/lupo-channels/42/threads/DEVELOPMENT_CYCLE_4_0_56/tasks/20260226000000_task_0_10000_primary_install_upgrade_4_0_46.md", "http://www.lupopedia.com/lupo-database/lupopedia/channels/lupo-channels/42/threads/DEVELOPMENT_CYCLE_4_0_56/tasks/20260226000000_task_0_10000_primary_install_upgrade_4_0_46"]

lupopedia.close:
  post_actions:
    - type: register_completion
      channel_id: 0
  actor_id: 1004
---


# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "lupo-channels\0\tasks\active\20260226000000_task_0_10000_primary_install_upgrade_4_0_46.md"
  file_hash: "2248a9e303263defc841143a7560f7bf6d6a42eb31c8c6567c878424a2a39524"
  file_path_from_root: "lupo-channels\0\tasks\active\20260226000000_task_0_10000_primary_install_upgrade_4_0_46.md"
  file_hash: "b1baf908ddb0fbbda9c4a6a6d8f1c303aa9b659d9914ab076ab08ac555308a64"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 42
  actor_id: 1003
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for 20260226000000_task_0_10000_primary_install_upgrade_4_0_46.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["channels", "0", "tasks", "active", "20260226000000_task_0_10000_primary_install_upgrade_4_0_46md"]
  lupo_agent: "cursor"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "cursor"
---

---
task_id: "CH0-20260226-001"
channel_id: 42
owner_actor_id: 10000
assigned_to: [10000]
status: "active"
priority: "critical"
created_utc: "20260226000000"
delegation_chain: "10000"
prompt_path: "lupo-channels/0/tasks/active/20260226000000_task_0_10000_primary_install_upgrade_4_0_46.md"
depends_on: []
blocks: ["CH0-20260226-002", "CH0-20260226-003", "CH42-20260226-001", "CH42-20260226-002", "CH42-20260226-003", "CH42-20260226-004"]
task_type: "database_operation"
estimated_duration: "60 minutes"
system_version: "4.0.46"
artifacts_touched: ["lupo-database/*", "install.php", "lupopedia-config.php"]
notes: "CRITICAL: This task blocks all other 4.0.46 tasks. Must be completed first."
---

# Task CH0-20260226-001: Primary Installation & Upgrade (4.0.46)

**Owner:** Captain (10000) - HUMAN  
**Assigned:** Captain (10000)  
**Priority:** CRITICAL  
**Status:** Active  
**Version:** 4.0.46  
**Estimated Duration:** 60 minutes

## Objective

Execute the complete Crafty Syntax 3.7.5 → Lupopedia 4.0.46 installation and upgrade process. This is the primary human-driven task that enables all subsequent validation and testing tasks.

## Prerequisites

- ✅ Version 4.0.45 complete (stabilization finished)
- ✅ All migration documentation ready (28 livehelp_* files)
- ✅ Database seed SQL files ready (6 files)
- ✅ Crafty Syntax 3.7.5 baseline SQL ready
- ✅ Backup of any existing data (if applicable)
- ✅ Installer version display fixed (4.0.42 → 4.0.46) - Kiro (1000)

## Completed Pre-Flight Tasks

### ✅ Installer Version Banner Fixed (2026-02-26)
**Executed By:** Kiro (1000) under Wolfie (1) directive  
**Issue:** install.php displayed version 4.0.42 instead of 4.0.46  
**Root Cause:** Hardcoded fallback in install.php line 93  
**Resolution:**
- Updated fallback from '4.0.42' to '4.0.46' (line 93)
- Updated FLIP header system_version to 4.0.46 (line 5)
- Updated FLIP footer version to 4.0.46 (line 53)
- Verified no remaining 4.0.42 references in install.php

**Status:** ✅ UNBLOCKED - Human can now run install.php

### ✅ SQL Schema Compatibility Fixes (2026-02-26)
**Executed By:** Kiro (1000) under Wolfie (1) directive  
**Issues Found:** 2 SQL errors during first install attempt  
**Fixes Applied:**
1. Removed partial index WHERE clauses (MySQL 5.7 incompatible) - lines 764-767
2. Fixed column count mismatch in registry INSERT (missing entity_index value) - line 3600

**Status:** ✅ FIXED - Schema now compatible with MySQL 5.7+

### ✅ Database Compatibility Broadcasts Updated (2026-02-26)
**Executed By:** Kiro (1000)  
**Updated Broadcasts:**
- Cross-DB Compatibility Law (added 4 new rules)
- SQL Portability Doctrine (expanded forbidden features)

**Status:** ✅ COMPLETE - Future SQL errors prevented

## Execution Progress

### Attempt 1: Partial Success (2026-02-26)
**Status:** ⚠️ PARTIAL - SQL errors encountered, tables partially created  
**Action Taken:** Captain dropped all tables and reset to Crafty baseline  
**Outcome:** SQL errors identified and fixed by Kiro

### Attempt 2: SUCCESS ✅ (2026-02-26)
**Status:** ✅ COMPLETE - Installation successful  
**Steps Completed:**
1. ✅ Dropped all lupo_* tables from partial install
2. ✅ Loaded Crafty Syntax 3.7.5 baseline (34 tables)
3. ✅ Ran install.php with fixed schema
4. ✅ Database created successfully
5. ✅ All 173 tables created without SQL errors
6. ✅ Generated 210 TOON files via `python lupo-scripts/generate_toon_files.py`
7. ✅ CSV export completed successfully

**Installation Results:**
- Database: Created ✅
- Tables: 173 created ✅
- TOON Files: 210 generated ✅
- CSV Export: Complete ✅
- SQL Errors: ZERO ✅

**Admin Interface Updates:**
- ✅ Tasks navigation added to admin.php (Kiro 1000)
- ✅ AdminTasksHandler.php created with full implementation
- ✅ Tasks page accessible at admin.php?section=tasks
- ✅ Filter by channel, status, priority
- ✅ Color-coded badges for status and priority
- ✅ Responsive table layout with task details
- ✅ Registry navigation added to admin.php (Kiro 1000)
- ✅ AdminRegistryHandler.php created with full implementation
- ✅ Registry page accessible at admin.php?section=registry
- ✅ Add new registry entries via web form
- ✅ Filter by entity type, kernel status, active status
- ✅ Read-only protection (no delete/edit for data integrity)

**Next Steps:**
- ⏳ Execute seeding SQL (Step 3)
- ⏳ Run upgrade wizard (Step 4)
- ⏳ Basic smoke tests (Step 5)
- ⏳ Complete post-install verification (CH0-20260226-002)

## Execution Steps

### Step 1: Database Preparation (10 minutes)

**STATUS: ✅ COMPLETE**

**1.1 Drop All Existing Tables** ✅ COMPLETE
- Dropped all lupo_* tables from partial install attempt 1
- Database reset successful

**1.2 Load Crafty Syntax 3.7.5 Baseline** ✅ COMPLETE
- Loaded 34 legacy Crafty tables
- Verified 34 tables present

**1.3 Load Old Crafty Config** ✅ COMPLETE
- Removed old lupopedia-config.php
- Ready for fresh install

### Step 2: Run Install Wizard (15 minutes)

**STATUS: ✅ COMPLETE**

**2.1 Access Install Wizard** ✅ COMPLETE
- Accessed http://localhost/lupopedia/install.php
- Displayed: "Lupopedia 4.0.46 — Install / Upgrade Wizard"

**2.2 Complete Installation Steps** ✅ COMPLETE
- Database connection configured
- Admin account created
- System configuration completed
- Schema installation: 173 tables created successfully

**2.3 Verify Installation Success** ✅ COMPLETE
- Installation completed successfully
- ZERO SQL errors (fixes worked!)
- All tables created

**2.4 Generate TOON Files** ✅ COMPLETE
```bash
python lupo-scripts/generate_toon_files.py
# Output: Wrote 210 TOONs to lupo-docs/toons
# CSV export completed successfully
```

### Step 3: Execute Seeding SQL (10 minutes)

**STATUS: ⏳ PENDING - Ready to execute**

**3.1 Registry Seeding**
```sql
-- Connect to database
mysql -u root -p lupopedia

-- Seed registry (reserved IDs)
SOURCE lupo-database/migrations/seed_registry_comprehensive_4.0.45.sql;

-- Seed registry (open gaps)
SOURCE lupo-database/migrations/seed_registry_open_4.0.45.sql;
```

**3.2 Actors and Agents Seeding**
```sql
-- Seed main actors and agents
SOURCE lupo-database/migrations/seed_actors_agents_4.0.45.sql;

-- Seed ANUBIS and VISHWAKARMA
SOURCE lupo-database/migrations/seed_anubis_vishwakarma_4.0.45.sql;
```

**3.3 Tasks Schema Seeding**
```sql
-- Add tasks tables
SOURCE lupo-database/migrations/add_tasks_schema_4.0.45.sql;

-- Seed task bootstrap data
SOURCE lupo-database/migrations/seed_tasks_bootstrap_4.0.45.sql;
```

**3.4 Verify Seeding Success**
```sql
-- Check actors
SELECT COUNT(*) FROM lupo_actors;
-- Should be 13+ actors

-- Check agents
SELECT COUNT(*) FROM lupo_agents;
-- Should be 11+ agents

-- Check registry
SELECT COUNT(*) FROM lupo_registry_actors;
-- Should be 20000+ entries

-- Check tasks tables
SHOW TABLES LIKE 'lupo_task%';
-- Should show 7 tables
```

### Step 4: Run Upgrade Wizard (15 minutes)

**4.1 Access Upgrade Wizard**
```
http://localhost/lupopedia/install.php?step=upgrade
```

**4.2 Execute Crafty → Lupopedia Migration**
- Select "Upgrade from Crafty Syntax 3.7.5"
- Review migration mapping
- Execute migration
- Wait for completion

**4.3 Verify Migration Success**
```sql
-- Check imported tables have data
SELECT COUNT(*) FROM lupo_auth_users;
-- Should have users from livehelp_users

SELECT COUNT(*) FROM lupo_dialog_threads;
-- Should have threads from livehelp_transcripts

SELECT COUNT(*) FROM lupo_departments;
-- Should have departments from livehelp_departments

-- Check all 18 imported tables
```

### Step 5: Basic Smoke Tests (10 minutes)

**5.1 Login Test**
- Login as admin (Captain)
- Verify dashboard loads
- Check no errors in browser console

**5.2 Feature Tests**
- Navigate to Departments
- Navigate to Operators (Actors)
- Navigate to Chat History (Dialog)
- Navigate to CRM Leads
- Navigate to Q&A System

**5.3 Database Verification**
```sql
-- Verify table count
SELECT COUNT(*) FROM information_schema.tables 
WHERE table_schema = 'lupopedia' AND table_name LIKE 'lupo_%';
-- Should be 173 tables

-- Verify no orphan records
SELECT COUNT(*) FROM lupo_actors WHERE actor_id NOT IN (SELECT actor_id FROM lupo_registry_actors);
-- Should be 0
```

## Success Criteria

- ✅ All 173 tables created
- ✅ All 13 actors seeded (0, 1, 2, 3, 4, 5, 19, 25, 1000-1005, 10000)
- ✅ All 11 agents seeded (0, 1, 2, 3, 4, 5, 19, 25)
- ✅ Registry populated (20000+ entries)
- ✅ All 18 legacy tables migrated with data
- ✅ No SQL errors in logs
- ✅ Admin login works
- ✅ Basic features accessible

## Failure Handling

**If installation fails:**
1. Document error message
2. Check logs: `lupo-includes/logs/`
3. Verify database credentials
4. Retry installation

**If seeding fails:**
1. Check SQL syntax errors
2. Verify file paths
3. Check database permissions
4. Retry failed SQL file

**If upgrade fails:**
1. Document migration error
2. Check legacy table structure
3. Verify mapping documentation
4. Restore Crafty backup
5. Report issue to Kiro (1000)

## Rollback Plan

If upgrade fails completely:
```sql
-- Drop all lupo_* tables
php lupo-scripts/drop_all_lupo_tables.php

-- Restore Crafty Syntax 3.7.5 backup
mysql -u root -p lupopedia < crafty_backup.sql

-- Document failure reason
-- Fix issue in code
-- Retry upgrade
```

## Post-Completion Actions

After successful completion:
1. Update task status to "completed"
2. Notify Kiro (1000) for post-install verification (CH0-20260226-002)
3. Document any issues encountered
4. Update CHANGELOG if needed

## Blocks These Tasks

- CH0-20260226-002: Post-Install Verification (Kiro)
- CH0-20260226-003: ANUBIS Quarantine Validation
- CH42-20260226-001: VISHWAKARMA Graph Analysis
- CH42-20260226-002: Legacy Migration Validation (Windsurf)
- CH42-20260226-003: UI Feature Parity (Windsurf)
- CH42-20260226-004: Regression Tests (Cursor)

## Notes

- This is the ONLY human-driven task in the critical path
- All IDE agent tasks depend on this completing successfully
- Estimated time includes all steps + verification
- If any step fails, stop and report to Kiro (1000)
- Keep detailed notes of any issues for documentation

## Resources

**Migration Documentation:**
- `lupo-docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md`
- `lupo-docs/doctrine/migrations/livehelp_*_migration.md` (28 files)

**Legacy Reference:**
- `/legacy/craftysyntax/` (read-only)

**SQL Files:**
- `lupo-database/migrations/old_crafty_syntax_3_7_5_start.sql`
- `lupo-database/migrations/install_new_lupopedia.sql`
- `lupo-database/migrations/seed_*.sql` (6 files)

---

**Created:** 2026-02-26 00:00:00 UTC  
**Owner:** Captain (10000)  
**Version:** 4.0.46  
**Status:** Active - Ready for Execution

---
flip.footer: {
  outbound_edges: [
    { to: "VERSION_4_0_46_LAUNCH_REPORT.md", type: "references", weight: 1.0 },
    { to: "lupo-channels/42/broadcasts/20260226000000_10000_1000_42_version_4_0_46_upgrade_program.md", type: "references", weight: 0.9 },
    { to: "lupo-docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md", type: "references", weight: 0.8 },
    { to: "lupo-database/migrations/old_crafty_syntax_3_7_5_start.sql", type: "executes", weight: 0.9 },
    { to: "install.php", type: "executes", weight: 0.9 }
  ],
  semantic_tags: ["task", "human_driven", "installation", "upgrade", "critical_path", "4.0.46"]
}
---
