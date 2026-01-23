-- Migration: Rename lupo_dialog_messages to lupo_dialog_doctrine
-- Version: 4.1.3
-- Date: 2026-01-19
--
-- Renames the table to match the codebase. Run AFTER 4.1.2_clarify_mood_rgb_terminology.sql.
--
-- @package Lupopedia
-- @version 4.1.3

RENAME TABLE `lupo_dialog_messages` TO `lupo_dialog_doctrine`;
