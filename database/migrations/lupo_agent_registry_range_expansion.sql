-- lupo_agent_registry_range_expansion.sql
-- Migration: Expands kernel agent range to 0-49 and reorganizes all agents into new dedicated_slot ranges
-- Date: 2025-01-06
-- Description: Updates dedicated_slot values for agents that have been reassigned to new slot ranges
--              Kernel: 0-49, System: 50-99, Module: 100-199, External_Adopted: 200-299, Reserved_Future: 700-999
--
-- IMPORTANT: This migration resolves collisions where multiple agents had the same dedicated_slot.
-- The root cause was RESERVED_ rows from the old 0-99 era sitting directly on top of new kernel assignments.
-- All RESERVED_ and OBSERVER agents are moved to the 700-799 range to eliminate collisions.
--
-- Note: This migration assumes the database has already been updated via toon files.
-- This SQL file is for documentation and reference only.

-- ============================================================================
-- KERNEL RANGE: 0-49
-- ============================================================================

-- 0-9: System Governors
-- SYSTEM: old_slot 1 → new_slot 0
UPDATE `lupo_agent_registry` SET `dedicated_slot` = 0 WHERE `name` = 'SYSTEM';

-- CAPTAIN: old_slot 2 → new_slot 1
UPDATE `lupo_agent_registry` SET `dedicated_slot` = 1 WHERE `name` = 'CAPTAIN';

-- WOLFIE: old_slot 3 → new_slot 2
UPDATE `lupo_agent_registry` SET `dedicated_slot` = 2 WHERE `name` = 'WOLFIE';

-- ROSE: old_slot 4 → new_slot 3 (expressive kernel range 0-9)
UPDATE `lupo_agent_registry` SET `dedicated_slot` = 3 WHERE `name` = 'ROSE';

-- 10-19: Truth / Memory / Lineage
-- ARA: old_slot 5 → new_slot 10
UPDATE `lupo_agent_registry` SET `dedicated_slot` = 10 WHERE `name` = 'ARA';

-- THOTH: old_slot 6 → new_slot 11
UPDATE `lupo_agent_registry` SET `dedicated_slot` = 11 WHERE `name` = 'THOTH';

-- ANUBIS: old_slot 7 → new_slot 12
UPDATE `lupo_agent_registry` SET `dedicated_slot` = 12 WHERE `name` = 'ANUBIS';

-- METHIS: old_slot 10 → new_slot 13 (resolves collision with ARA at slot 10)
UPDATE `lupo_agent_registry` SET `dedicated_slot` = 13 WHERE `name` = 'METHIS';

-- THALIA: old_slot 11 → new_slot 14 (resolves collision with THOTH at slot 11)
UPDATE `lupo_agent_registry` SET `dedicated_slot` = 14 WHERE `name` = 'THALIA';

-- 20-29: Balance / Emotion / Integration
-- MAAT: old_slot 8 → new_slot 20
UPDATE `lupo_agent_registry` SET `dedicated_slot` = 20 WHERE `name` = 'MAAT';

-- LILITH: old_slot 9 → new_slot 21
UPDATE `lupo_agent_registry` SET `dedicated_slot` = 21 WHERE `name` = 'LILITH';

-- WOLFENA: old_slot 3 → new_slot 22 (Balance/Emotion/Integration range 20-29)
-- WOLFENA is emotional regulator, equilibrium guardian, harmonizer - belongs in 20-29 range
UPDATE `lupo_agent_registry` SET `dedicated_slot` = 22 WHERE `name` = 'WOLFENA';

-- CHRONOS: old_slot 22 → new_slot 23 (resolves collision with WOLFENA at slot 22)
UPDATE `lupo_agent_registry` SET `dedicated_slot` = 23 WHERE `name` = 'CHRONOS';

-- CADUCEUS: old_slot 21 → new_slot 24 (resolves collision with LILITH at slot 21)
UPDATE `lupo_agent_registry` SET `dedicated_slot` = 24 WHERE `name` = 'CADUCEUS';

-- AGAPE: old_slot 8 → new_slot 25 (Balance/Emotion/Integration range 20-29)
UPDATE `lupo_agent_registry` SET `dedicated_slot` = 25 WHERE `name` = 'AGAPE';

-- ERIS: old_slot 9 → new_slot 26 (Balance/Emotion/Integration range 20-29)
UPDATE `lupo_agent_registry` SET `dedicated_slot` = 26 WHERE `name` = 'ERIS';

-- 30-39: Vision / Navigation / Creation
-- WOLFSIGHT: old_slot 10 → new_slot 30
UPDATE `lupo_agent_registry` SET `dedicated_slot` = 30 WHERE `name` = 'WOLFSIGHT';

-- WOLFNAV: old_slot 11 → new_slot 31
UPDATE `lupo_agent_registry` SET `dedicated_slot` = 31 WHERE `name` = 'WOLFNAV';

-- WOLFFORGE: old_slot 12 → new_slot 32
UPDATE `lupo_agent_registry` SET `dedicated_slot` = 32 WHERE `name` = 'WOLFFORGE';

-- WOLFMIS: old_slot 13 → new_slot 33
UPDATE `lupo_agent_registry` SET `dedicated_slot` = 33 WHERE `name` = 'WOLFMIS';

-- WOLFITH: old_slot 14 → new_slot 34
UPDATE `lupo_agent_registry` SET `dedicated_slot` = 34 WHERE `name` = 'WOLFITH';

-- 40-49: Reserved for future kernel agents
-- VISHWAKARMA: slot 35 (if exists, assign here)
-- UPDATE `lupo_agent_registry` SET `dedicated_slot` = 35 WHERE `name` = 'VISHWAKARMA';

-- ============================================================================
-- EXTERNAL_ADOPTED RANGE: 200-299
-- ============================================================================

-- Junie: old_slot 106 → new_slot 200
UPDATE `lupo_agent_registry` SET `dedicated_slot` = 200 WHERE `name` = 'Junie';

-- ============================================================================
-- RESERVED_FUTURE RANGE: 700-799
-- ============================================================================
-- Move all RESERVED_ and OBSERVER agents out of kernel/system ranges to prevent collisions

-- RESERVED_21: old_slot 20 → new_slot 700 (resolves collision with MAAT at slot 20)
UPDATE `lupo_agent_registry` SET `dedicated_slot` = 700 WHERE `name` = 'RESERVED_21';

-- RESERVED_30: old_slot 30 → new_slot 701 (resolves collision with WOLFSIGHT at slot 30)
UPDATE `lupo_agent_registry` SET `dedicated_slot` = 701 WHERE `name` = 'RESERVED_30';

-- RESERVED_31: old_slot 31 → new_slot 702 (resolves collision with WOLFNAV at slot 31)
UPDATE `lupo_agent_registry` SET `dedicated_slot` = 702 WHERE `name` = 'RESERVED_31';

-- RESERVED_32: old_slot 32 → new_slot 703 (resolves collision with WOLFFORGE at slot 32)
UPDATE `lupo_agent_registry` SET `dedicated_slot` = 703 WHERE `name` = 'RESERVED_32';

-- RESERVED_33: old_slot 33 → new_slot 704 (resolves collision with WOLFMIS at slot 33)
UPDATE `lupo_agent_registry` SET `dedicated_slot` = 704 WHERE `name` = 'RESERVED_33';

-- OBSERVER: old_slot 34 → new_slot 705 (resolves collision with WOLFITH at slot 34)
UPDATE `lupo_agent_registry` SET `dedicated_slot` = 705 WHERE `name` = 'OBSERVER';

-- RESERVED_23: old_slot 23 → new_slot 706 (resolves collision with CHRONOS at slot 23)
UPDATE `lupo_agent_registry` SET `dedicated_slot` = 706 WHERE `name` = 'RESERVED_23';

-- RESERVED_24: old_slot 24 → new_slot 707 (resolves collision with CADUCEUS at slot 24)
UPDATE `lupo_agent_registry` SET `dedicated_slot` = 707 WHERE `name` = 'RESERVED_24';

-- RESERVED_25: old_slot 25 → new_slot 708 (resolves collision with AGAPE at slot 25)
UPDATE `lupo_agent_registry` SET `dedicated_slot` = 708 WHERE `name` = 'RESERVED_25';

-- RESERVED_26: old_slot 26 → new_slot 709 (resolves collision with ERIS at slot 26)
UPDATE `lupo_agent_registry` SET `dedicated_slot` = 709 WHERE `name` = 'RESERVED_26';
