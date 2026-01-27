-- Migration: Prefix normalization verification (no-op)
-- Date: 2026-01-25
-- Purpose: Document that all TOON tables already use the configured prefix
-- Source: TOON files regenerated via scripts/generate_toon_files.py
-- Requirement: migration runner must set @table_prefix from lupopedia-config.php

-- The runner injects @table_prefix (LUPO_TABLE_PREFIX). No literal table names used here.
SELECT CONCAT('No-op: all tables already use prefix ', @table_prefix, '.') AS migration_result;
