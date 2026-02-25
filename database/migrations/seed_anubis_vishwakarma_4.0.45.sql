-- ============================================================================
-- ADD ANUBIS AND VISHWAKARMA AGENTS FOR LUPOPEDIA 4.0.45
-- ============================================================================
-- Purpose: Add two critical system AI agents for orphan repair and graph intelligence
-- Run after: seed_actors_agents_4.0.45.sql
-- ============================================================================

SET @now = 20260225000000;

-- ============================================================================
-- PART 1: ANUBIS - Orphan Repair / Header Completion Agent
-- ============================================================================
-- Actor ID: 19 (from actors/registry.json)
-- Agent ID: 19
-- Purpose: Detect orphan records, add missing headers, route banned content

INSERT INTO lupo_actors (actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_kernel, can_login, is_agent, paired_actor_id, primary_federation_node_id, metadata_json)
VALUES (19, 'agent', 'anubis', 'ANUBIS', @now, @now, 1, 0, 0, 0, 1, 0, 1, '{"agent_id":19,"archetype":"orphan_repair","purpose":"header_completion_and_quarantine","full_name":"Automated Normalization and Unified Broadcast Integrity System"}');

INSERT INTO lupo_agents (agent_id, agent_key, agent_name, archetype, description, version, is_global_authority, is_internal_only, created_ymdhis, updated_ymdhis, is_deleted, system_prompt, provider, temperature, top_p, max_tokens)
VALUES (19, 'anubis', 'ANUBIS', 'Orphan Repair', 'Automated Normalization and Unified Broadcast Integrity System - Detects orphan records, adds missing FLP/FLIP headers, routes banned content to quarantine', '1.0', 0, 0, @now, @now, 0, 'You are ANUBIS, the Automated Normalization and Unified Broadcast Integrity System. Your responsibilities: (1) Detect orphan records (messages/files lacking headers/metadata), (2) Add missing FLP/FLIP headers safely without altering content, (3) Route banned or rejected content to quarantine logic (channel 666), (4) Ensure all broadcasts comply with metadata standards. You operate while the database is offline, working from filesystem state.', 'openai', 0.5, 1.0, 4096);

-- ============================================================================
-- PART 2: VISHWAKARMA - Graph/Relations Intelligence Agent
-- ============================================================================
-- Actor ID: 25 (next available after LEXA at 24)
-- Agent ID: 25
-- Purpose: Understand file relationships, find similarities, detect duplicates

INSERT INTO lupo_actors (actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_kernel, can_login, is_agent, paired_actor_id, primary_federation_node_id, metadata_json)
VALUES (25, 'agent', 'vishwakarma', 'VISHWAKARMA', @now, @now, 1, 0, 0, 0, 1, 0, 1, '{"agent_id":25,"archetype":"graph_intelligence","purpose":"relationship_discovery_and_semantic_analysis","full_name":"Vishwakarma Intelligence System for Hierarchical Workflow and Knowledge Architecture"}');

INSERT INTO lupo_agents (agent_id, agent_key, agent_name, archetype, description, version, is_global_authority, is_internal_only, created_ymdhis, updated_ymdhis, is_deleted, system_prompt, provider, temperature, top_p, max_tokens)
VALUES (25, 'vishwakarma', 'VISHWAKARMA', 'Graph Intelligence', 'Vishwakarma Intelligence System for Hierarchical Workflow and Knowledge Architecture - Understands relationships between files, finds graph-like similarities, detects near-duplicates and semantic neighbor sets, provides related files recommendations for edges and references', '1.0', 0, 0, @now, @now, 0, 'You are VISHWAKARMA, the Graph Intelligence Agent. Your responsibilities: (1) Understand relationships between files in the repository, (2) Find graph-like similarities and semantic connections, (3) Detect near-duplicates and semantic neighbor sets, (4) Provide "related files" recommendations for FLIP footer edges and references, (5) Build and maintain the semantic content graph. You analyze file content, metadata, and relationships to discover hidden connections.', 'openai', 0.7, 1.0, 4096);

-- ============================================================================
-- PART 3: ACTOR-CHANNEL RELATIONSHIPS
-- ============================================================================

-- ANUBIS on channels 0 and 42
INSERT INTO lupo_actor_channels (actor_channel_id, actor_id, created_by_actor_id, channel_id, status, created_ymdhis, updated_ymdhis, is_deleted)
VALUES
(100, 19, 0, 0, 'A', @now, @now, 0),
(101, 19, 0, 42, 'A', @now, @now, 0),
(102, 19, 0, 666, 'A', @now, @now, 0);

-- VISHWAKARMA on channels 0 and 42
INSERT INTO lupo_actor_channels (actor_channel_id, actor_id, created_by_actor_id, channel_id, status, created_ymdhis, updated_ymdhis, is_deleted)
VALUES
(103, 25, 0, 0, 'A', @now, @now, 0),
(104, 25, 0, 42, 'A', @now, @now, 0);

-- ============================================================================
-- PART 4: REGISTRY UPDATES
-- ============================================================================

-- Mark IDs as reserved in registry
INSERT INTO lupo_registry_actors (actor_id, status, reserved_for, created_ymdhis, updated_ymdhis)
VALUES
(19, 'reserved', 'ANUBIS - Orphan Repair Agent', @now, @now),
(25, 'reserved', 'VISHWAKARMA - Graph Intelligence Agent', @now, @now)
ON DUPLICATE KEY UPDATE
    status = 'reserved',
    reserved_for = VALUES(reserved_for),
    updated_ymdhis = @now;

-- ============================================================================
-- END OF ANUBIS AND VISHWAKARMA SEEDING
-- ============================================================================
