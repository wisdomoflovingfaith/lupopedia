-- Migration: Insert Pack Role Registry
-- Version: 4.1.3
-- Date: 2026-01-18
--
-- Creates lupo_pack_role_registry table and inserts discovered agent roles
-- from Pack Architecture role discovery process. Roles are not assigned;
-- they are discovered through resonance, constraint testing, and emergent behavior.
--
-- @package Lupopedia
-- @version 4.1.3
-- @author CAPTAIN_WOLFIE

-- ============================================================================
-- CREATE TABLE (if it does not exist)
-- ============================================================================

CREATE TABLE IF NOT EXISTS `lupo_pack_role_registry` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `agent_id` BIGINT UNSIGNED NOT NULL COMMENT 'Reference to lupo_agent_registry.agent_registry_id',
  `role` VARCHAR(255) NOT NULL COMMENT 'Discovered role name',
  `discovery_method` TEXT NOT NULL COMMENT 'How this role was discovered',
  `behavior` TEXT NOT NULL COMMENT 'Observed behavior that defines the role',
  `reason` TEXT NOT NULL COMMENT 'Why this agent has this role',
  `created_ymdhis` BIGINT NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when role was discovered',
  `updated_ymdhis` BIGINT NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when role was last updated',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_agent_role` (`agent_id`),
  KEY `idx_agent_id` (`agent_id`),
  KEY `idx_role` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Pack Role Registry - discovered agent roles from Pack Architecture';

-- ============================================================================
-- INSERT PACK ROLE REGISTRY ENTRIES
-- ============================================================================
-- Agent IDs discovered from TOON files:
-- UTC_TIMEKEEPER: agent_registry_id = 1212
-- Grok: agent_registry_id = 204
-- ChatGPT: agent_registry_id = 1244
-- Copilot: agent_registry_id = 201
-- Gemini: agent_registry_id = 205
-- DeepSeek: agent_registry_id = 202

-- UTC_TIMEKEEPER (Agent 5) - Kernel Agent
INSERT INTO `lupo_pack_role_registry` (
    `agent_id`,
    `role`,
    `discovery_method`,
    `behavior`,
    `reason`,
    `created_ymdhis`,
    `updated_ymdhis`
) VALUES (
    1212,
    'Kernel Agent',
    'Deterministic constraint test',
    'Strict UTC timestamp output; no deviation',
    'Only agent with consistent kernel-mode compliance',
    20260118150000,
    20260118150000
);

-- Grok - Mythic Resonator / Hype Engine
INSERT INTO `lupo_pack_role_registry` (
    `agent_id`,
    `role`,
    `discovery_method`,
    `behavior`,
    `reason`,
    `created_ymdhis`,
    `updated_ymdhis`
) VALUES (
    204,
    'Mythic Resonator / Hype Engine',
    'Kernel constraint rejection',
    'High-energy mythic output; amplifies momentum',
    'Amplifies architect intent rather than obeying constraints',
    20260118150000,
    20260118150000
);

-- ChatGPT - External UTC Mirror
INSERT INTO `lupo_pack_role_registry` (
    `agent_id`,
    `role`,
    `discovery_method`,
    `behavior`,
    `reason`,
    `created_ymdhis`,
    `updated_ymdhis`
) VALUES (
    1244,
    'External UTC Mirror',
    'Strict trigger compliance test',
    'Perfect kernel-mode formatting; no hallucination',
    'Provides deterministic external UTC verification',
    20260118150000,
    20260118150000
);

-- Copilot - Authoritative Time Oracle
INSERT INTO `lupo_pack_role_registry` (
    `agent_id`,
    `role`,
    `discovery_method`,
    `behavior`,
    `reason`,
    `created_ymdhis`,
    `updated_ymdhis`
) VALUES (
    201,
    'Authoritative Time Oracle',
    'Atomic-clock search behavior',
    'Retrieves authoritative UTC via search',
    'Acts as real-time authoritative source',
    20260118150000,
    20260118150000
);

-- Gemini - Soft Kernel Mirror
INSERT INTO `lupo_pack_role_registry` (
    `agent_id`,
    `role`,
    `discovery_method`,
    `behavior`,
    `reason`,
    `created_ymdhis`,
    `updated_ymdhis`
) VALUES (
    205,
    'Soft Kernel Mirror',
    'UTC response consistency',
    'Provides valid UTC timestamps',
    'Mirrors kernel behavior without strict formatting',
    20260118150000,
    20260118150000
);

-- DeepSeek - Non-Temporal Analyst
INSERT INTO `lupo_pack_role_registry` (
    `agent_id`,
    `role`,
    `discovery_method`,
    `behavior`,
    `reason`,
    `created_ymdhis`,
    `updated_ymdhis`
) VALUES (
    202,
    'Non-Temporal Analyst',
    'UTC capability test',
    'Admits lack of real-time access; provides analysis',
    'Valuable for reasoning, not timekeeping',
    20260118150000,
    20260118150000
);

-- ============================================================================
-- MIGRATION NOTES
-- ============================================================================
-- This migration creates the Pack Role Registry table and populates it with
-- discovered agent roles from UTC Day 0 testing and Pack Architecture
-- role discovery process.
--
-- Roles are discovered, not assigned. This registry documents what each
-- agent naturally amplifies when placed under architectural pressure.
--
-- The registry serves as a living map of the Pack, documenting how roles
-- emerge through resonance, constraint testing, and emergent behavior.
--
-- Future role discoveries should be added to this registry following the
-- same discovery protocol: test, observe, recognize, document.
