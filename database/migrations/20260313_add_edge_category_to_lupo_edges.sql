-- Database Migration: Add Grouped Edge Support to lupo_edges
-- Task: Verify edge_category support for grouped outbound_edges in LUPOPEDIA HEADERS
-- Version: 4.0.73
-- Author: Antigravity

-- NOTE: This column already exists in its canonical form in install_new_lupopedia.sql (4.0.47+).
-- This migration is for legacy databases that were initialized prior to the 4.0.47 FLARE protocol updates.

-- DOCTRINE: We do not use INFORMATION_SCHEMA in SQL for shared host compatibility.
-- Idempotency is handled by the PHP runner (e.g. scripts/run_one_time_sql.php) 
-- which ignores "Duplicate column name" and "Duplicate key name" errors.

-- Add edge_category column
ALTER TABLE `lupo_edges` ADD COLUMN `edge_category` VARCHAR(100) DEFAULT NULL AFTER `edge_type`;

-- Add index
CREATE INDEX `lupo_edges_idx_edge_category` ON `lupo_edges` (`edge_category`);

-- Verification of doctrine mapping:
-- group key 'code' → lupo_edges.edge_category = 'code'
-- group key 'documentation' → lupo_edges.edge_category = 'documentation'
-- group key 'schema' → lupo_edges.edge_category = 'schema'
-- group key 'runtime' → lupo_edges.edge_category = 'runtime'
