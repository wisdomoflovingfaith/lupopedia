-- Migration: Clarify mood_rgb Terminology - Emotional Polarity Tensor Documentation
-- Version: 4.1.2
-- Date: 2026-01-18
--
-- Updates column comments to clarify that mood_rgb represents an emotional polarity
-- tensor encoded as hex, NOT literal RGB color channels. This is a documentation-only
-- migration to reduce confusion about the field's semantic meaning.
--
-- The field name "mood_rgb" is a legacy convention. The values represent three abstract
-- emotional axes (strife, harmony, memory depth) encoded as hex for compact storage.
-- Hex encoding is coincidental and carries no color semantics.
--
-- @package Lupopedia
-- @version 4.1.2
-- @author CAPTAIN_WOLFIE

-- ============================================================================
-- UPDATE COLUMN COMMENTS FOR MOOD_RGB CLARIFICATION
-- ============================================================================

-- Update lupo_dialog_channels.mood_rgb comment
ALTER TABLE `lupo_dialog_channels` 
MODIFY COLUMN `mood_rgb` VARCHAR(7) DEFAULT NULL 
COMMENT 'Emotional polarity tensor encoded as hex (abstract axes: strife (R), harmony (G), memory depth (B); not literal RGB color channels)';

-- Update lupo_dialog_messages.mood_rgb comment
ALTER TABLE `lupo_dialog_messages`
MODIFY COLUMN `mood_rgb` CHAR(6) NOT NULL DEFAULT '666666'
COMMENT 'Emotional polarity tensor encoded as hex (abstract axes: strife (R), harmony (G), memory depth (B); not literal RGB color channels)';

-- Update lupo_dialog_message_bodies.mood_rgb comment
ALTER TABLE `lupo_dialog_message_bodies` 
MODIFY COLUMN `mood_rgb` CHAR(6) NOT NULL DEFAULT '666666' 
COMMENT 'Emotional polarity tensor encoded as hex (abstract axes: strife (R), harmony (G), memory depth (B); not literal RGB color channels)';

-- ============================================================================
-- MIGRATION NOTES
-- ============================================================================
-- This migration updates documentation only. No data changes are made.
-- The field name "mood_rgb" remains unchanged for backward compatibility.
-- All existing values continue to work exactly as before.
-- This clarification helps prevent confusion about the field's semantic meaning.
