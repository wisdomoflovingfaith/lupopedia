-- ============================================================================
-- ZENCODER IDE AGENT ACTOR SEED — LUPOPEDIA 4.0.77
-- ============================================================================
-- Purpose: Register Zencoder IDE Agent as an actor (actor_id 106, slug zencoder)
-- Actor type: ide_faucet (execution surface, not identity)
-- Paired actor: 1000 (root / wolfie orchestrator)
-- Default channel: 42 (Lupopedia Development)
-- Run after: seed_actors_agents_4.0.45.sql
-- DOCTRINE: actor_id is explicit (not AUTO_INCREMENT); no foreign keys.
-- ============================================================================

SET @now = 20260316000000;

INSERT INTO lupo_actors (
    actor_name,
    actor_id,
    actor_type,
    slug,
    name,
    created_ymdhis,
    updated_ymdhis,
    is_active,
    is_deleted,
    is_kernel,
    can_login,
    is_agent,
    paired_actor_id,
    primary_federation_node_id,
    metadata_json
)
VALUES (
    'zencoder-ide',
    106,
    'ide_faucet',
    'zencoder',
    'Zencoder IDE',
    @now,
    @now,
    1,
    0,
    0,
    0,
    1,
    1000,
    1,
    '{"client_id":"zencoder","provider":"zencoder","purpose":"IDE_integration","full_name":"Zencoder IDE Agent","default_channel_id":42,"archetype":"documentation_and_development"}'
)
ON DUPLICATE KEY UPDATE
    updated_ymdhis = @now,
    is_active = 1,
    is_deleted = 0;
