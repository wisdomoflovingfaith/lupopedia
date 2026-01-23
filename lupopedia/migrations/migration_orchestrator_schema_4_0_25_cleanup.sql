-- Migration Orchestrator Schema Cleanup - Version 4.0.25
-- Drops existing tables to allow clean recreation

USE lupopedia_orchestration;

-- Drop existing tables if they exist
DROP TABLE IF EXISTS migration_batches;
DROP TABLE IF EXISTS migration_files;
DROP TABLE IF EXISTS migration_validation_log;
DROP TABLE IF EXISTS migration_rollback_log;
DROP TABLE IF EXISTS migration_dependencies;
DROP TABLE IF EXISTS migration_system_state;
DROP TABLE IF EXISTS migration_progress;
DROP TABLE IF EXISTS migration_alerts;
