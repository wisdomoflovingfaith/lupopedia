-- Migration: Remove duplicate thread/message tables
-- Version: 4.0.69
-- Purpose: Unify all communication through lupo_dialog_* tables (see lupo-docs/doctrine/COMMUNICATION_DOCTRINE.md)
-- Run once. If using a table prefix other than lupo_, replace lupo_ in table names below.

-- Step 1: Drop the duplicate tables (per audit, these were unused in production)
DROP TABLE IF EXISTS lupo_messages;
DROP TABLE IF EXISTS lupo_threads;

-- Step 2: Record migration (idempotent: use INSERT IGNORE; if version already exists, skip)
INSERT IGNORE INTO lupo_schema_migrations (schema_migration_id, version, name, applied_ymdhis)
VALUES (2026031001, '20260310_remove_duplicate_thread_message_tables', 'remove_duplicate_thread_message_tables', 20260310120000);
