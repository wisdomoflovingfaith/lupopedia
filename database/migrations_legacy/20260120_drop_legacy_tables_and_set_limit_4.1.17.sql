-- Version: 4.1.17
-- Purpose: Drop deprecated legacy tables and enforce table ceiling
-- Date: 2026-01-20
-- Notes: No data migrations; all tables confirmed safe to remove

-- Doctrine Table Ceiling: 180

-- ======================================================================
-- DROP deprecated legacy tables (8)
-- ======================================================================

DROP TABLE IF EXISTS livehelp_channels;
DROP TABLE IF EXISTS livehelp_messages;
DROP TABLE IF EXISTS livehelp_modules;
DROP TABLE IF EXISTS livehelp_modules_dep;
DROP TABLE IF EXISTS livehelp_operator_channels;
DROP TABLE IF EXISTS livehelp_referers_monthly;
DROP TABLE IF EXISTS livehelp_sessions;
DROP TABLE IF EXISTS livehelp_visit_track;

-- ======================================================================
-- Placeholder for optional new tables (leave commented unless specified)
-- ======================================================================
-- CREATE TABLE new_table_name_here ( ... );
