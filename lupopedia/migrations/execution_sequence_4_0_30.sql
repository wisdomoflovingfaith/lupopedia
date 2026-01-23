-- Migration Execution Sequence - Version 4.0.30
-- Execute these scripts in order for clean migration deployment

-- ========================================
-- STEP 1: Cleanup Existing Tables (if they exist)
-- ========================================
-- Only run if you encounter "Table already exists" errors
-- SOURCE migrations/migration_orchestrator_schema_4_0_25_cleanup.sql;
-- SOURCE migrations/ephemeral_schema_4_0_25_cleanup.sql;

-- ========================================
-- STEP 2: Create Schema Infrastructure
-- ========================================
-- Create orchestration schema (8 tables)
SOURCE migrations/migration_orchestrator_schema_4_0_25.sql;

-- Create ephemeral schema (5 tables)
SOURCE migrations/ephemeral_schema_4_0_25.sql;

-- ========================================
-- STEP 3: Doctrine Tab Mappings
-- ========================================
-- Map agent doctrine files (13 files → tab 13)
SOURCE migrations/doctrine_agent_tab_mapping_4_0_26.sql;

-- Map versioning doctrine files (3 files → tab 21)
SOURCE migrations/doctrine_versioning_tab_mapping_4_0_26.sql;

-- Map SQL doctrine files (7 files → tab 11)
SOURCE migrations/doctrine_sql_tab_mapping_4_0_25.sql;

-- ========================================
-- STEP 4: Verification
-- ========================================
-- Run verification queries to confirm success
SOURCE migrations/verification_queries_4_0_30.sql;

-- ========================================
-- EXECUTION NOTES
-- ========================================
-- 1. All migrations are idempotent and safe for multiple executions
-- 2. If tables already exist, run cleanup scripts first (Step 1)
-- 3. Each migration follows Lupopedia doctrine (BIGINT timestamps, no triggers)
-- 4. Tab mappings use three-step pattern (soft-delete → update → insert)
-- 5. Verification queries will show table counts and mapping results
-- 6. After successful execution, update TOON files to reflect new schemas

-- ========================================
-- POST-EXECUTION TASKS
-- ========================================
-- 1. Run verification queries to confirm all tables created
-- 2. Check doctrine tab mappings show correct file counts
-- 3. Verify no duplicate mappings exist
-- 4. Update TOON files with new schema structures
-- 5. Update documentation with migration results
-- 6. Prepare for next phase (4.0.31+)
