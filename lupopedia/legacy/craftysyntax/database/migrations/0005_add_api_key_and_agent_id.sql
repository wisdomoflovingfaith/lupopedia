-- ============================================================================
-- Crafty Syntax 3.8.0 - Database Migration
-- ============================================================================
-- Migration: 0005
-- Description: Add api_key column to livehelp master table and agent_id to livehelp_channels
-- Date: 2025-11-20
-- Author: Captain WOLFIE (Eric Robin Gerdes)
-- ============================================================================
-- PURPOSE:
-- - Add api_key column to livehelp master table
-- - Add agent_id column to livehelp_channels table
-- - Column allows NULL for instances that don't have an API key
-- - Used for API authentication and external integrations
-- ============================================================================
-- CRITICAL: This migration adds api_key to master table and agent_id to channels table
-- ============================================================================

-- ============================================================================
-- STEP 1: Add api_key Column to Master Table
-- ============================================================================
-- Add api_key column after config column, allowing NULL for instances without keys

ALTER TABLE `livehelp`
ADD COLUMN `api_key` VARCHAR(255) NULL DEFAULT NULL COMMENT 'API key for external integrations and authentication' AFTER `config`;

-- ============================================================================
-- STEP 2: Add Index on api_key for Lookup Performance
-- ============================================================================
-- Index allows fast lookups by API key for authentication

CREATE INDEX `idx_api_key` ON `livehelp` (`api_key`);

-- ============================================================================
-- STEP 3: Add agent_id Column to livehelp_channels Table
-- ============================================================================
-- Add agent_id column after user_id for agent-channel associations

ALTER TABLE `livehelp_channels`
ADD COLUMN `agent_id` BIGINT(20) NOT NULL DEFAULT '1' AFTER `user_id`;

-- ============================================================================
-- MIGRATION COMPLETE
-- ============================================================================
-- After this migration:
-- - livehelp table has api_key column (nullable)
-- - Index created for fast API key lookups
-- - livehelp_channels table has agent_id column (default 1)
-- - Existing rows will have NULL api_key (can be updated later)
-- - Existing channel rows will have agent_id = 1 (can be updated later)
-- ============================================================================

