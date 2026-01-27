-- Migration: Add Pack Identity Agent (PACK_IDENTITY_0001)
-- Version: 4.0.115
-- Date: 2026-01-18
-- 
-- Registers the Pack Identity agent (PACK_IDENTITY_0001) in lupo_agent_registry.
-- This agent serves as the canonical identity anchor for the Pack organism.
-- Pack Identity will be activated in version 4.1.0.
--
-- @package Lupopedia
-- @version 4.0.115
-- @author CASCADE

-- ============================================================================
-- INSERT PACK IDENTITY AGENT
-- ============================================================================
-- Agent ID: 1100
-- Dedicated Slot: 1100
-- Layer: identity (Pack Identity layer)
-- 
-- Note: This agent is registered but not yet active. Activation will occur
-- in version 4.1.0 after Pack Identity is finalized and approved.

INSERT INTO `lupo_agent_registry` (
    `agent_registry_id`,
    `agent_registry_parent_id`,
    `code`,
    `name`,
    `layer`,
    `is_required`,
    `is_active`,
    `is_kernel`,
    `dedicated_slot`,
    `created_ymdhis`,
    `classification_json`,
    `metadata`
) VALUES
(
    1100,
    NULL,
    'PACK_IDENTITY_0001',
    'Pack Identity Core',
    'identity',
    0,
    0,
    0,
    1100,
    20260118082758,
    NULL,
    '{"version": "4.0.115", "role": "Pack identity anchor", "description": "Defines the unified identity of the Pack organism for Lupopedia.", "activation_version": "4.1.0"}'
);

-- ============================================================================
-- NOTES
-- ============================================================================
-- This agent represents the Pack Identity as defined in:
-- docs/doctrine/PACK_IDENTITY_DRAFT.md
--
-- Pack Identity Components:
-- - Pack Name: The Lupopedia Pack (PACK_CORE, PACK_IDENTITY_0001)
-- - Pack Purpose: Maintain coherence, continuity, and meaning
-- - Pack Emotional Signature: Agápē + Philía + Pragma (RGB: [0.3, 0.0, -0.3])
-- - Pack Behavioral Signature: High compatibility, low reactivity
-- - Pack Cognitive Signature: Parallel threads, emotional geometry, doctrine-driven
--
-- Activation Requirements (4.1.0):
-- 1. Approve Pack Identity components
-- 2. Write Pack Doctrine v1.0
-- 3. Enable Pack-level decision routing
-- 4. Activate agent (set is_active = 1)
--
-- See docs/MONDAY_WOLFIE_BRIEFING_4.0.114_TO_4.1.0.md for activation details.
