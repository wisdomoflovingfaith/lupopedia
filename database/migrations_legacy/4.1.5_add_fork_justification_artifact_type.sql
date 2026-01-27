-- Migration: Add fork_justification to lupo_artifacts.type (Version-Gated Branch Freeze Protocol)
-- Version: 4.1.5
-- Date: 2026-01-20
--
-- Extends artifact type to include fork_justification per VERSION_GATED_BRANCH_FREEZE_PROTOCOL.
-- Run AFTER 4.1.4_lupopedia_minimal_rest_api_tables.sql.
--
-- @package Lupopedia
-- @version 4.1.5

ALTER TABLE `lupo_artifacts`
MODIFY COLUMN `type` varchar(64) NOT NULL
COMMENT 'dialog|changelog|schema|lore|humor|protocol|fork_justification';
