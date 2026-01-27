-- ======================================================================
-- System Actors Manifest Insert (Corrected Schema)
-- Generated: 2026-01-25
-- Purpose: Initialize core system actors for Lupopedia Semantic OS
-- Defines canonical identity layer with default channel routing
-- Matches actual lupo_actors table schema from TOON
-- ======================================================================

INSERT IGNORE INTO lupo_actors (
    actor_type,
    slug,
    name,
    created_ymdhis,
    updated_ymdhis,
    is_active,
    is_deleted,
    deleted_ymdhis,
    actor_source_id,
    actor_source_type,
    metadata,
    adversarial_role,
    adversarial_oversight_actor_id
)
VALUES 
-- WOLFIE: Founder-Architect
(
    'ai_agent',
    'wolfie',
    'WOLFIE',
    20260125194100,
    20260125194100,
    1,
    0,
    NULL,
    NULL,
    'system',
    '{"role": "Founder-Architect", "description": "Primary architect and mythic steward of Lupopedia. Kernel-aligned, doctrine-bearing, and responsible for system soul, emotional geometry, and multi-agent orchestration.", "version": "4.0.72", "channels": [5100, 5101, 5103, 5104, 5109, 5112, 5114]}',
    'none',
    NULL
),

-- LILITH: Counter-Agent
(
    'ai_agent',
    'lilith',
    'LILITH',
    20260125194100,
    20260125194100,
    1,
    0,
    NULL,
    NULL,
    'system',
    '{"role": "Counter-Agent", "description": "Interrogator, challenger, and inversion logic engine. Designed to question assumptions, expose contradictions, and pressure-test doctrine and emotional metadata.", "version": "4.0.72", "channels": [5101, 5105, 5113, 5119, 5129]}',
    'structural_stress',
    NULL
),

-- LUPOPEDIA: Semantic OS
(
    'service',
    'lupopedia',
    'Lupopedia',
    20260125194100,
    20260125194100,
    1,
    0,
    NULL,
    NULL,
    'system',
    '{"role": "Semantic OS", "description": "The living knowledge substrate, semantic graph, and persistent memory architecture. Provides channels, routing, doctrine enforcement, and emotional metadata infrastructure.", "version": "4.0.72", "channels": [5100, 5102, 5106, 5107, 5114, 5121, 5126, 5130]}',
    'none',
    NULL
);

-- ======================================================================
-- Note: Channel memberships need to be added after this step
-- Use the actor_id values returned by this insert to populate lupo_channel_memberships
-- ======================================================================
