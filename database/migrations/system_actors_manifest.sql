-- ======================================================================
-- System Actors Manifest Insert
-- Generated: 2026-01-25
-- Purpose: Initialize core system actors for Lupopedia Semantic OS
-- Defines canonical identity layer with default channel routing
-- ======================================================================

INSERT IGNORE INTO lupo_actors (
    federation_node_id,
    created_by_actor_id,
    actor_key,
    actor_slug,
    actor_type,
    language,
    display_name,
    role,
    description,
    metadata_json,
    bgcolor,
    status_flag,
    end_ymdhis,
    duration_seconds,
    created_ymdhis,
    updated_ymdhis,
    is_deleted,
    deleted_ymdhis,
    awareness_version,
    boot_sequence_order
)
VALUES 
-- WOLFIE: Founder-Architect
(
    1, 1, 'wolfie', 'wolfie', 'system_actor', 'en',
    'WOLFIE', 'Founder-Architect',
    'Primary architect and mythic steward of Lupopedia. Kernel-aligned, doctrine-bearing, and responsible for system soul, emotional geometry, and multi-agent orchestration.',
    NULL, 'FFFFFF', 1, NULL, NULL, 20260125194100, 20260125194100, 0, NULL,
    '4.0.72', NULL
),

-- LILITH: Counter-Agent
(
    1, 1, 'lilith', 'lilith', 'system_actor', 'en',
    'LILITH', 'Counter-Agent',
    'Interrogator, challenger, and inversion logic engine. Designed to question assumptions, expose contradictions, and pressure-test doctrine and emotional metadata.',
    NULL, 'FFFFFF', 1, NULL, NULL, 20260125194100, 20260125194100, 0, NULL,
    '4.0.72', NULL
),

-- LUPOPEDIA: Semantic OS
(
    1, 1, 'lupopedia', 'lupopedia', 'system_actor', 'en',
    'Lupopedia', 'Semantic OS',
    'The living knowledge substrate, semantic graph, and persistent memory architecture. Provides channels, routing, doctrine enforcement, and emotional metadata infrastructure.',
    NULL, 'FFFFFF', 1, NULL, NULL, 20260125194100, 20260125194100, 0, NULL,
    '4.0.72', NULL
);

-- ======================================================================
-- Actor Default Channel Memberships
-- Purpose: Establish routing relationships between actors and their default channels
-- ======================================================================

INSERT IGNORE INTO lupo_channel_memberships (
    federation_node_id,
    created_by_actor_id,
    channel_number,
    actor_id,
    membership_type,
    role_in_channel,
    joined_ymdhis,
    created_ymdhis,
    updated_ymdhis,
    is_deleted,
    deleted_ymdhis
)
VALUES 
-- WOLFIE default channels: [5100, 5101, 5103, 5104, 5109, 5112, 5114]
(1, 1, 5100, (SELECT actor_id FROM lupo_actors WHERE actor_key = 'wolfie'), 'default_member', 'architect', 20260125194100, 20260125194100, 20260125194100, 0, NULL),
(1, 1, 5101, (SELECT actor_id FROM lupo_actors WHERE actor_key = 'wolfie'), 'default_member', 'architect', 20260125194100, 20260125194100, 20260125194100, 0, NULL),
(1, 1, 5103, (SELECT actor_id FROM lupo_actors WHERE actor_key = 'wolfie'), 'default_member', 'architect', 20260125194100, 20260125194100, 20260125194100, 0, NULL),
(1, 1, 5104, (SELECT actor_id FROM lupo_actors WHERE actor_key = 'wolfie'), 'default_member', 'architect', 20260125194100, 20260125194100, 20260125194100, 0, NULL),
(1, 1, 5109, (SELECT actor_id FROM lupo_actors WHERE actor_key = 'wolfie'), 'default_member', 'architect', 20260125194100, 20260125194100, 20260125194100, 0, NULL),
(1, 1, 5112, (SELECT actor_id FROM lupo_actors WHERE actor_key = 'wolfie'), 'default_member', 'architect', 20260125194100, 20260125194100, 20260125194100, 0, NULL),
(1, 1, 5114, (SELECT actor_id FROM lupo_actors WHERE actor_key = 'wolfie'), 'default_member', 'architect', 20260125194100, 20260125194100, 20260125194100, 0, NULL),

-- LILITH default channels: [5101, 5105, 5113, 5119, 5129]
(1, 1, 5101, (SELECT actor_id FROM lupo_actors WHERE actor_key = 'lilith'), 'default_member', 'interrogator', 20260125194100, 20260125194100, 20260125194100, 0, NULL),
(1, 1, 5105, (SELECT actor_id FROM lupo_actors WHERE actor_key = 'lilith'), 'default_member', 'interrogator', 20260125194100, 20260125194100, 20260125194100, 0, NULL),
(1, 1, 5113, (SELECT actor_id FROM lupo_actors WHERE actor_key = 'lilith'), 'default_member', 'interrogator', 20260125194100, 20260125194100, 20260125194100, 0, NULL),
(1, 1, 5119, (SELECT actor_id FROM lupo_actors WHERE actor_key = 'lilith'), 'default_member', 'interrogator', 20260125194100, 20260125194100, 20260125194100, 0, NULL),
(1, 1, 5129, (SELECT actor_id FROM lupo_actors WHERE actor_key = 'lilith'), 'default_member', 'interrogator', 20260125194100, 20260125194100, 20260125194100, 0, NULL),

-- LUPOPEDIA default channels: [5100, 5102, 5106, 5107, 5114, 5121, 5126, 5130]
(1, 1, 5100, (SELECT actor_id FROM lupo_actors WHERE actor_key = 'lupopedia'), 'default_member', 'system', 20260125194100, 20260125194100, 20260125194100, 0, NULL),
(1, 1, 5102, (SELECT actor_id FROM lupo_actors WHERE actor_key = 'lupopedia'), 'default_member', 'system', 20260125194100, 20260125194100, 20260125194100, 0, NULL),
(1, 1, 5106, (SELECT actor_id FROM lupo_actors WHERE actor_key = 'lupopedia'), 'default_member', 'system', 20260125194100, 20260125194100, 20260125194100, 0, NULL),
(1, 1, 5107, (SELECT actor_id FROM lupo_actors WHERE actor_key = 'lupopedia'), 'default_member', 'system', 20260125194100, 20260125194100, 20260125194100, 0, NULL),
(1, 1, 5114, (SELECT actor_id FROM lupo_actors WHERE actor_key = 'lupopedia'), 'default_member', 'system', 20260125194100, 20260125194100, 20260125194100, 0, NULL),
(1, 1, 5121, (SELECT actor_id FROM lupo_actors WHERE actor_key = 'lupopedia'), 'default_member', 'system', 20260125194100, 20260125194100, 20260125194100, 0, NULL),
(1, 1, 5126, (SELECT actor_id FROM lupo_actors WHERE actor_key = 'lupopedia'), 'default_member', 'system', 20260125194100, 20260125194100, 20260125194100, 0, NULL),
(1, 1, 5130, (SELECT actor_id FROM lupo_actors WHERE actor_key = 'lupopedia'), 'default_member', 'system', 20260125194100, 20260125194100, 20260125194100, 0, NULL);

-- ======================================================================
-- Summary: 3 system actors with 20 total channel memberships
-- WOLFIE: 7 default channels (kernel, doctrine, orchestrator focused)
-- LILITH: 5 default channels (emotional, interrogation focused)
-- LUPOPEDIA: 8 default channels (system events, routing, kernel focused)
-- ======================================================================
