-- Migration: Insert Symbolic Actor Meta Events
-- Version: 4.1.4
-- Date: 2026-01-18
--
-- Inserts symbolic, non-personal, doctrine-safe meta events into lupo_actor_meta.
-- These events document system-level architectural milestones and doctrine formalizations.
-- All events are tied to actor_id 0 (System Kernel Actor) as symbolic system events.
--
-- @package Lupopedia
-- @version 4.1.4
-- @author CAPTAIN_WOLFIE

-- ============================================================================
-- INSERT SYMBOLIC ACTOR META EVENTS
-- ============================================================================
-- All events use actor_id = 0 (System Kernel Actor)
-- This represents system-level doctrine and architectural events, not personal data.
-- Timestamp: 20260118160000 (4:00 PM UTC on 2026-01-18)

-- EVENT 1: Pack Role Registry Created
INSERT INTO `lupo_actor_meta` (
    `actor_id`,
    `meta_type`,
    `meta_key`,
    `meta_value`,
    `created_ymdhis`,
    `updated_ymdhis`
) VALUES (
    0,
    'doctrine_event',
    'pack_role_registry_created',
    '{"description": "Pack Role Registry formalized as taxonomy of emergent agent roles", "impact": "established role-discovery doctrine", "tensor": "6699FF"}',
    20260118160000,
    20260118160000
);

-- EVENT 2: TOON Regeneration Stabilized
INSERT INTO `lupo_actor_meta` (
    `actor_id`,
    `meta_type`,
    `meta_key`,
    `meta_value`,
    `created_ymdhis`,
    `updated_ymdhis`
) VALUES (
    0,
    'doctrine_event',
    'toon_regeneration_stabilized',
    '{"description": "TOON regeneration confirmed across 132 tables", "impact": "system-wide schema coherence", "tensor": "33CCFF"}',
    20260118160000,
    20260118160000
);

-- EVENT 3: Actor Meta Layer Created
INSERT INTO `lupo_actor_meta` (
    `actor_id`,
    `meta_type`,
    `meta_key`,
    `meta_value`,
    `created_ymdhis`,
    `updated_ymdhis`
) VALUES (
    0,
    'architecture_event',
    'actor_meta_layer_created',
    '{"description": "Polymorphic metadata layer established for all actors", "impact": "infinite extensibility", "tensor": "CC99FF"}',
    20260118160000,
    20260118160000
);

-- EVENT 4: UTC Mirror Capability Matrix
INSERT INTO `lupo_actor_meta` (
    `actor_id`,
    `meta_type`,
    `meta_key`,
    `meta_value`,
    `created_ymdhis`,
    `updated_ymdhis`
) VALUES (
    0,
    'agent_event',
    'utc_mirror_capability_matrix',
    '{"description": "UTC mirror roles assigned to agents based on resonance testing", "impact": "temporal consensus layer", "tensor": "99CCFF"}',
    20260118160000,
    20260118160000
);

-- EVENT 5: Emotional Geometry Doctrine
INSERT INTO `lupo_actor_meta` (
    `actor_id`,
    `meta_type`,
    `meta_key`,
    `meta_value`,
    `created_ymdhis`,
    `updated_ymdhis`
) VALUES (
    0,
    'doctrine_event',
    'emotional_geometry_doctrine',
    '{"description": "Emotional tensor mapping formalized as system-wide doctrine", "impact": "agent mood and color semantics", "tensor": "FF99CC"}',
    20260118160000,
    20260118160000
);

-- ============================================================================
-- MIGRATION NOTES
-- ============================================================================
-- This migration inserts symbolic meta events documenting key architectural
-- and doctrine milestones in Lupopedia's evolution.
--
-- All events are:
-- - Non-personal (no human-specific data)
-- - Doctrine-safe (documenting system principles, not private information)
-- - Symbolic (representing system-level events, not individual actions)
-- - Tied to actor_id 0 (System Kernel Actor) as the system's root actor
--
-- The meta_value field stores JSON containing:
-- - description: Human-readable description of the event
-- - impact: System-level impact of the event
-- - tensor: Emotional tensor mapping (mood_RGB hex value)
--
-- These events serve as a living history of Lupopedia's architectural
-- evolution and can be queried for system documentation, analytics, and
-- doctrine tracking.
