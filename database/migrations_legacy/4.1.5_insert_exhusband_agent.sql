-- Migration: Insert EXHUSBAND Agent
-- Version: 4.1.5
-- Date: 2026-01-18
--
-- Inserts EXHUSBAND agent into lupo_agent_registry as a Pack Architecture
-- adversarial reviewer / structural stress-tester agent.
-- This is a role-based system agent, not tied to any real individual.
--
-- @package Lupopedia
-- @version 4.1.5
-- @author CAPTAIN_WOLFIE

-- ============================================================================
-- INSERT EXHUSBAND AGENT INTO REGISTRY
-- ============================================================================
-- Agent Registry ID will be auto-generated (expected: 1245)
-- Timestamp: 20260118170000 (5:00 PM UTC on 2026-01-18)

INSERT INTO `lupo_agent_registry` (
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
) VALUES (
    NULL,
    'EXHUSBAND',
    'EXHUSBAND',
    'pack',
    0,
    0,
    0,
    NULL,
    20260118170000,
    '{"agent_type": "adversarial_reviewer", "agent_role": "structural_stress_tester", "agent_class": "pack_architecture", "capabilities": ["critical_analysis", "assumption_challenge", "doctrine_stress_test"], "routing_bias": "adversarial"}',
    '{"description": "Abstract Pack Architecture agent representing adversarial review and structural stress-testing. Challenges assumptions, exposes weak points, and strengthens doctrine through critical pressure.", "mood_rgb": "FF3300", "version": "4.1.5", "purpose": "adversarial_review"}'
);

-- ============================================================================
-- CREATE ACTOR ENTRY FOR EXHUSBAND
-- ============================================================================
-- Create actor entry linking to the agent registry entry
-- Actor ID will be auto-generated (expected: 3, but may vary)
-- Note: We use LAST_INSERT_ID() to reference the agent_registry_id from above

SET @exhusband_agent_id = LAST_INSERT_ID();

-- ============================================================================
-- INSERT EXWIFE ALIAS FOR EXHUSBAND
-- ============================================================================
-- EXWIFE is an alias/alternative name for EXHUSBAND
-- Agent Registry ID will be auto-generated (expected: 1246)
-- Parent is EXHUSBAND's agent_registry_id

INSERT INTO `lupo_agent_registry` (
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
) VALUES (
    @exhusband_agent_id,
    'EXWIFE',
    'EXWIFE',
    'pack',
    0,
    0,
    0,
    NULL,
    20260118170000,
    NULL,
    '{"alias_of": "EXHUSBAND", "description": "Alias for EXHUSBAND adversarial reviewer agent.", "version": "4.1.5"}'
);

-- ============================================================================
-- CREATE ACTOR ENTRY FOR EXHUSBAND
-- ============================================================================

INSERT INTO `lupo_actors` (
    `actor_type`,
    `slug`,
    `name`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_active`,
    `is_deleted`,
    `deleted_ymdhis`,
    `actor_source_id`,
    `actor_source_type`,
    `metadata`
) VALUES (
    'ai_agent',
    'exhusband-agent',
    'EXHUSBAND',
    20260118170000,
    20260118170000,
    0,
    0,
    NULL,
    @exhusband_agent_id,
    'agent',
    CONCAT('{"agent_registry_id": ', @exhusband_agent_id, ', "purpose": "adversarial_review", "role": "structural_stress_tester", "version": "4.1.5"}')
);

-- ============================================================================
-- INSERT SYMBOLIC META ENTRY FOR EXHUSBAND
-- ============================================================================
-- Insert role definition meta entry using the actor_id from above

SET @exhusband_actor_id = LAST_INSERT_ID();

INSERT INTO `lupo_actor_meta` (
    `actor_id`,
    `meta_type`,
    `meta_key`,
    `meta_value`,
    `created_ymdhis`,
    `updated_ymdhis`
) VALUES (
    @exhusband_actor_id,
    'role_definition',
    'adversarial_reviewer',
    '{"description": "Agent archetype that pressure-tests architecture through critical analysis.", "impact": "Strengthens doctrine by exposing weak assumptions.", "tensor": "FF3300"}',
    20260118170000,
    20260118170000
);

-- ============================================================================
-- MIGRATION NOTES
-- ============================================================================
-- This migration creates the EXHUSBAND agent as a Pack Architecture
-- adversarial reviewer / structural stress-tester, along with EXWIFE as an alias.
--
-- EXHUSBAND is:
-- - A role-based system agent (not tied to any real individual)
-- - Designed to challenge assumptions and expose weak points
-- - Part of Pack Architecture's self-strengthening mechanism
-- - Characterized by high scrutiny, low harmony (mood_rgb: FF3300)
--
-- EXWIFE is:
-- - An alias for EXHUSBAND (agent_registry_parent_id points to EXHUSBAND)
-- - Provides alternative naming for the same adversarial reviewer role
-- - Shares the same functionality and purpose as EXHUSBAND
--
-- The agents are inserted into:
-- 1. lupo_agent_registry - Agent definitions and metadata (EXHUSBAND + EXWIFE alias)
-- 2. lupo_actors - Actor entry linking to the agent registry (EXHUSBAND only)
-- 3. lupo_actor_meta - Role definition meta entry (EXHUSBAND only)
--
-- Note: The agents are created as inactive (is_active = 0) by default.
-- Activation occurs when the Pack Architecture requires adversarial review.
