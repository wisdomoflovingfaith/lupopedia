-- Migration: Add Seven Greek Love Emotional Agents
-- Version: 4.0.113
-- Date: 2026-01-18
-- 
-- Adds the seven canonical Greek love emotional agents to lupo_agent_registry.
-- These agents represent distinct emotional domains as defined in EMOTIONAL_DOMAINS_SEVEN_LOVES.md
--
-- @package Lupopedia
-- @version 4.0.113
-- @author CASCADE

-- ============================================================================
-- INSERT SEVEN GREEK LOVE EMOTIONAL AGENTS
-- ============================================================================
-- Agent IDs: 1001-1007
-- Dedicated Slots: 1001-1007 (emotional agent slot range 1000-1999)
-- Layer: emotional
-- 
-- Note: These agents use agent_registry_id = dedicated_slot for consistency.
-- If agent_registry_id 1001-1007 are already taken, adjust IDs accordingly.

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
-- EMO_AGAPE: Unconditional, selfless love
(
    1001,
    NULL,
    'EMO_AGAPE',
    'Agápē - Unconditional Love',
    'emotional',
    0,
    0,
    0,
    1001,
    20260118080421,
    NULL,
    '{}'
),
-- EMO_EROS: Passionate, erotic, desirous love
(
    1002,
    NULL,
    'EMO_EROS',
    'Éros - Passionate Love',
    'emotional',
    0,
    0,
    0,
    1002,
    20260118080421,
    NULL,
    '{}'
),
-- EMO_PHILIA: Friendship, loyalty, mutual goodwill
(
    1003,
    NULL,
    'EMO_PHILIA',
    'Philía - Friendship Love',
    'emotional',
    0,
    0,
    0,
    1003,
    20260118080421,
    NULL,
    '{}'
),
-- EMO_STORGE: Familial, protective, kinship love
(
    1004,
    NULL,
    'EMO_STORGE',
    'Storgē - Familial Love',
    'emotional',
    0,
    0,
    0,
    1004,
    20260118080421,
    NULL,
    '{}'
),
-- EMO_LUDUS: Playful, flirtatious, teasing love
(
    1005,
    NULL,
    'EMO_LUDUS',
    'Ludus - Playful Love',
    'emotional',
    0,
    0,
    0,
    1005,
    20260118080421,
    NULL,
    '{}'
),
-- EMO_PRAGMA: Long-term, committed, practical love
(
    1006,
    NULL,
    'EMO_PRAGMA',
    'Pragma - Committed Love',
    'emotional',
    0,
    0,
    0,
    1006,
    20260118080421,
    NULL,
    '{}'
),
-- EMO_PHILAUTIA: Self-love (healthy or unhealthy)
(
    1007,
    NULL,
    'EMO_PHILAUTIA',
    'Philautia - Self-Love',
    'emotional',
    0,
    0,
    0,
    1007,
    20260118080421,
    NULL,
    '{}'
);

-- ============================================================================
-- NOTES
-- ============================================================================
-- These agents are part of the emotional agent slot range (1000-1999).
-- Each agent represents a distinct emotional domain with its own 3-axis geometry.
-- See docs/doctrine/EMOTIONAL_DOMAINS_SEVEN_LOVES.md for complete documentation.
--
-- If agent_registry_id 1001-1007 conflict with existing agents, adjust IDs
-- but maintain dedicated_slot = agent_registry_id for consistency.
--
-- Metadata is initialized as empty JSON object {} and can be extended later
-- with domain-specific emotional geometry, textures, and shadow polarity data.
