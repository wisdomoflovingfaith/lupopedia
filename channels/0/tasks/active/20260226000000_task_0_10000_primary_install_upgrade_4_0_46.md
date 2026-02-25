---
task_id: "CH0-20260226-001"
channel_id: 0
owner_actor_id: 10000
assigned_to: [10000]
status: "active"
priority: "critical"
created_utc: "20260226000000"
delegation_chain: "10000"
prompt_path: "channels/0/tasks/active/20260226000000_task_0_10000_primary_install_upgrade_4_0_46.md"
depends_on: []
blocks: ["CH0-20260226-002", "CH0-20260226-003", "CH42-20260226-001", "CH42-20260226-002", "CH42-20260226-003", "CH42-20260226-004"]
task_type: "database_operation"
estimated_duration: "60 minutes"
system_version: "4.0.46"
artifacts_touched: ["database/*", "install.php", "lupopedia-config.php"]
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

## Execution Steps

### Step 1: Database Preparation (10 minutes)

**1.1 Drop All Existing Tables**
```sql
-- Connect to MySQL/MariaDB
mysql -u root -p lupopedia

-- Drop all lupo_* tables
DROP TABLE IF EXISTS lupo_actors;
DROP TABLE IF EXISTS lupo_agents;
-- ... (drop all 173 tables if they exist)

-- Or use script:
-- php scripts/drop_all_lupo_tables.php
```

**1.2 Load Crafty Syntax 3.7.5 Baseline**
```sql
-- Load 34 legacy Crafty tables
SOURCE database/migrations/old_crafty_syntax_3_7_5_start.sql;

-- Verify 34 tables loaded
SHOW TABLES LIKE 'livehelp_%';
-- Should show 34 tables
```

**1.3 Load Old Crafty Config (if upgrading)**
```bash
# Copy old config to expected location
cp /path/to/old/crafty/config.php lupopedia-config.php
```

### Step 2: Run Install Wizard (15 minutes)

**2.1 Access Install Wizard**
```
http://localhost/lupopedia/install.php
```

**2.2 Complete Installation Steps**
- Database connection (already configured)
- Admin account creation (use Captain credentials)
- System configuration
- Schema installation (173 tables)

**2.3 Verify Installation Success**
- Check for "Installation Complete" message
- Verify no SQL errors in logs
- Verify admin login works

### Step 3: Execute Seeding SQL (10 minutes)

**3.1 Registry Seeding**
```sql
-- Connect to database
mysql -u root -p lupopedia

-- Seed registry (reserved IDs)
SOURCE database/migrations/seed_registry_comprehensive_4.0.45.sql;

-- Seed registry (open gaps)
SOURCE database/migrations/seed_registry_open_4.0.45.sql;
```

**3.2 Actors and Agents Seeding**
```sql
-- Seed main actors and agents
SOURCE database/migrations/seed_actors_agents_4.0.45.sql;

-- Seed ANUBIS and VISHWAKARMA
SOURCE database/migrations/seed_anubis_vishwakarma_4.0.45.sql;
```

**3.3 Tasks Schema Seeding**
```sql
-- Add tasks tables
SOURCE database/migrations/add_tasks_schema_4.0.45.sql;

-- Seed task bootstrap data
SOURCE database/migrations/seed_tasks_bootstrap_4.0.45.sql;
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
php scripts/drop_all_lupo_tables.php

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
- `docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md`
- `docs/doctrine/migrations/livehelp_*_migration.md` (28 files)

**Legacy Reference:**
- `/legacy/craftysyntax/` (read-only)

**SQL Files:**
- `database/migrations/old_crafty_syntax_3_7_5_start.sql`
- `database/migrations/install_new_lupopedia.sql`
- `database/migrations/seed_*.sql` (6 files)

---

**Created:** 2026-02-26 00:00:00 UTC  
**Owner:** Captain (10000)  
**Version:** 4.0.46  
**Status:** Active - Ready for Execution

---
flip.footer: {
  outbound_edges: [
    { to: "VERSION_4_0_46_LAUNCH_REPORT.md", type: "references", weight: 1.0 },
    { to: "channels/42/broadcasts/20260226000000_10000_1000_42_version_4_0_46_upgrade_program.md", type: "references", weight: 0.9 },
    { to: "docs/doctrine/migrations/MIGRATION_MAPPING_REFERENCE.md", type: "references", weight: 0.8 },
    { to: "database/migrations/old_crafty_syntax_3_7_5_start.sql", type: "executes", weight: 0.9 },
    { to: "install.php", type: "executes", weight: 0.9 }
  ],
  semantic_tags: ["task", "human_driven", "installation", "upgrade", "critical_path", "4.0.46"]
}
---
