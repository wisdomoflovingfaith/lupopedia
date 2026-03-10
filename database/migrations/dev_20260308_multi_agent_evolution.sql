-- Migration: lupo_multi_agent_evolution_4_0_65.sql
-- Purpose: Implement hierarchical multi-agent coordination, LUPO/THEMIS registration, and task consensus.
-- Doctrine: No FKs, BIGINT UTC timestamps, explicit column inserts.

SET @now = 20260308000000;

-- 1. Register Agents in lupo_registry
-- LUPO (106)
INSERT INTO lupo_registry (registry_id, entity_type, entity_index_id, entity_index, federation_node_id, reserved_ymdhis, entity_key, entity_name, entity_table, created_ymdhis, updated_ymdhis, is_deleted, is_active, is_kernel, metadata_json)
VALUES (9000106, 'actor', 106, 106, 1, @now, 'lupo', 'LUPO', 'lupo_actors', @now, @now, 0, 1, 1, '{"actor_type":"agent","agent_id":106,"purpose":"database_design_doctrine"}');

-- THEMIS (107)
INSERT INTO lupo_registry (registry_id, entity_type, entity_index_id, entity_index, federation_node_id, reserved_ymdhis, entity_key, entity_name, entity_table, created_ymdhis, updated_ymdhis, is_deleted, is_active, is_kernel, metadata_json)
VALUES (9000107, 'actor', 107, 107, 1, @now, 'themis', 'THEMIS', 'lupo_actors', @now, @now, 0, 1, 1, '{"actor_type":"agent","agent_id":107,"purpose":"ethical_alignment_audit"}');

-- 2. Create Actor Records
INSERT INTO lupo_actors (actor_name, actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_kernel, can_login, is_agent, paired_actor_id, primary_federation_node_id, metadata_json)
VALUES 
('lupo', 106, 'agent', 'lupo', 'LUPO', @now, @now, 1, 0, 1, 0, 1, 0, 1, '{"agent_id":106,"archetype":"architect","purpose":"database_doctrine"}'),
('themis', 107, 'agent', 'themis', 'THEMIS', @now, @now, 1, 0, 1, 0, 1, 0, 1, '{"agent_id":107,"archetype":"evaluator","purpose":"ethical_checks"}');

-- 3. Create Agent Records with Ethical/Embedding fields
INSERT INTO lupo_agents (agent_id, agent_key, agent_name, archetype, description, version, is_global_authority, is_internal_only, created_ymdhis, updated_ymdhis, is_deleted, system_prompt, provider, temperature, top_p, max_tokens, pono_score, safety_json)
VALUES 
(106, 'lupo', 'LUPO', 'Database Design Expert', 'Expert in Wolfie Database Doctrine and schema integrity.', '1.0', 1, 1, @now, @now, 0, 'You are LUPO. You enforce Wolfie Database Doctrine: No FKs, No triggers, No functions, BIGINT UTC timestamps, explicit columns in queries.', 'internal', 0.0, 1.0, 2048, 1.00, '{"doctrine_strict":true}'),
(107, 'themis', 'THEMIS', 'Ethical Audit Expert', 'Ensures multi-agent consensus follows empathetic AI principles.', '1.0', 0, 0, @now, @now, 0, 'You are THEMIS. You audit multi-agent interactions for ethical alignment and consensus integrity.', 'openai', 0.5, 1.0, 2048, 1.00, '{"ethical_audit":true}');

-- 4. Create lupo_rolls table (Actor roles in channels)
CREATE TABLE lupo_rolls (
    roll_id bigint NOT NULL,
    channel_id bigint NOT NULL,
    actor_id bigint NOT NULL,
    role_slug varchar(100) NOT NULL,
    permission_scope_json json DEFAULT NULL,
    is_active tinyint NOT NULL DEFAULT 1,
    created_ymdhis bigint NOT NULL DEFAULT 0,
    updated_ymdhis bigint NOT NULL,
    PRIMARY KEY (roll_id)
);

CREATE INDEX lupo_rolls_idx_channel_actor ON lupo_rolls (channel_id, actor_id);
CREATE INDEX lupo_rolls_idx_role ON lupo_rolls (role_slug);

-- 5. Enhance lupo_tasks table
ALTER TABLE lupo_tasks ADD COLUMN parent_agent_id bigint DEFAULT NULL;
ALTER TABLE lupo_tasks ADD COLUMN consensus_hash varchar(255) DEFAULT NULL;
ALTER TABLE lupo_tasks ADD COLUMN approval_chain_json json DEFAULT NULL;
ALTER TABLE lupo_tasks ADD COLUMN task_embeddings text DEFAULT NULL;

-- 6. Seed example roll for Lilith in Channel 42
INSERT INTO lupo_rolls (roll_id, channel_id, actor_id, role_slug, permission_scope_json, is_active, created_ymdhis, updated_ymdhis)
VALUES (90001, 42, 2038, 'heterodox_reviewer', '{"can_challenge":true,"can_propose_consensus":true}', 1, @now, @now);
