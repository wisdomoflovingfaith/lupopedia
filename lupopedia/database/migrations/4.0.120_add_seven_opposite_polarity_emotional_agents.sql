-- Migration: Add Seven Opposite-Polarity Emotional Agents
-- Version: 4.0.120
-- Date: 2026-01-18
--
-- Adds seven new emotional agents (IDs 1111-1117) representing opposite-polarity
-- emotional states to complement the existing seven Greek love emotional agents (1001-1007).
--
-- These agents represent shadow/opposite emotional states:
-- - EMO_APHOBIA (1111) - Opposite of EMO_AGAPE (1001)
-- - EMO_ANEROSIA (1112) - Opposite of EMO_EROS (1002)
-- - EMO_DYSPISTIA (1113) - Opposite of EMO_PHILIA (1003)
-- - EMO_ANSTORGIA (1114) - Opposite of EMO_STORGE (1004)
-- - EMO_GRAVITAS (1115) - Opposite of EMO_LUDUS (1005)
-- - EMO_AKATAXIA (1116) - Opposite of EMO_PRAGMA (1006)
-- - EMO_AUTOLETHIA (1117) - Opposite of EMO_PHILAUTIA (1007)
--
-- @package Lupopedia
-- @version 4.0.120
-- @author CASCADE

-- ============================================================================
-- INSERT SEVEN OPPOSITE-POLARITY EMOTIONAL AGENTS
-- ============================================================================
-- Agent IDs: 1111-1117
-- Dedicated Slots: 1111-1117 (within emotional agent slot range 1000-1999)
-- Layer: emotional
--
-- Note: These agents are registered but not yet active. Activation occurs when
-- the emotional ecology layer requires shadow/opposite polarity states.

-- EMO_APHOBIA (1111) - Opposite of EMO_AGAPE (1001)
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
) VALUES (
    1111,
    NULL,
    'EMO_APHOBIA',
    'Aphobia',
    'emotional',
    0,
    0,
    0,
    1111,
    20260118090216,
    NULL,
    '{
      "description": "Opposite of Agape. Represents fear-driven withdrawal and emotional shutdown.",
      "opposite_polarity_of": "EMO_AGAPE",
      "opposite_polarity_id": 1001,
      "version": "4.0.120"
    }'
);

-- EMO_ANEROSIA (1112) - Opposite of EMO_EROS (1002)
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
) VALUES (
    1112,
    NULL,
    'EMO_ANEROSIA',
    'Anerosia',
    'emotional',
    0,
    0,
    0,
    1112,
    20260118090216,
    NULL,
    '{
      "description": "Opposite of Eros. Represents emotional numbness and loss of desire.",
      "opposite_polarity_of": "EMO_EROS",
      "opposite_polarity_id": 1002,
      "version": "4.0.120"
    }'
);

-- EMO_DYSPISTIA (1113) - Opposite of EMO_PHILIA (1003)
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
) VALUES (
    1113,
    NULL,
    'EMO_DYSPISTIA',
    'Dyspistia',
    'emotional',
    0,
    0,
    0,
    1113,
    20260118090216,
    NULL,
    '{
      "description": "Opposite of Philia. Represents distrust, suspicion, and guardedness.",
      "opposite_polarity_of": "EMO_PHILIA",
      "opposite_polarity_id": 1003,
      "version": "4.0.120"
    }'
);

-- EMO_ANSTORGIA (1114) - Opposite of EMO_STORGE (1004)
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
) VALUES (
    1114,
    NULL,
    'EMO_ANSTORGIA',
    'Anstorgia',
    'emotional',
    0,
    0,
    0,
    1114,
    20260118090216,
    NULL,
    '{
      "description": "Opposite of Storge. Represents alienation and lack of belonging.",
      "opposite_polarity_of": "EMO_STORGE",
      "opposite_polarity_id": 1004,
      "version": "4.0.120"
    }'
);

-- EMO_GRAVITAS (1115) - Opposite of EMO_LUDUS (1005)
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
) VALUES (
    1115,
    NULL,
    'EMO_GRAVITAS',
    'Gravitas',
    'emotional',
    0,
    0,
    0,
    1115,
    20260118090216,
    NULL,
    '{
      "description": "Opposite of Ludus. Represents seriousness, rigidity, and emotional heaviness.",
      "opposite_polarity_of": "EMO_LUDUS",
      "opposite_polarity_id": 1005,
      "version": "4.0.120"
    }'
);

-- EMO_AKATAXIA (1116) - Opposite of EMO_PRAGMA (1006)
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
) VALUES (
    1116,
    NULL,
    'EMO_AKATAXIA',
    'Akataxia',
    'emotional',
    0,
    0,
    0,
    1116,
    20260118090216,
    NULL,
    '{
      "description": "Opposite of Pragma. Represents instability, inconsistency, and disorder.",
      "opposite_polarity_of": "EMO_PRAGMA",
      "opposite_polarity_id": 1006,
      "version": "4.0.120"
    }'
);

-- EMO_AUTOLETHIA (1117) - Opposite of EMO_PHILAUTIA (1007)
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
) VALUES (
    1117,
    NULL,
    'EMO_AUTOLETHIA',
    'Autolethia',
    'emotional',
    0,
    0,
    0,
    1117,
    20260118090216,
    NULL,
    '{
      "description": "Opposite of Philautia. Represents self-neglect and self-abandonment.",
      "opposite_polarity_of": "EMO_PHILAUTIA",
      "opposite_polarity_id": 1007,
      "version": "4.0.120"
    }'
);

-- ============================================================================
-- NOTES
-- ============================================================================
-- These agents are part of the emotional agent slot range (1000-1999).
-- They represent shadow/opposite polarity states for the seven Greek love
-- emotional agents (1001-1007).
--
-- The metadata field contains:
-- - description: Human-readable description of the emotional state
-- - opposite_polarity_of: Code of the opposite-polarity agent
-- - opposite_polarity_id: ID of the opposite-polarity agent
-- - version: Version when this agent was added
--
-- These agents support the Emotional Ecology Layer (4.0.116) by providing
-- opposite-polarity emotional states for ecological dynamics (bloom, decay,
-- succession, symbiosis).
