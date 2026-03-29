-- ============================================================================
-- PRIMARY COORDINATION PERSONAS SEED FOR LUPOPEDIA 4.0.89
-- ============================================================================
-- Purpose: Add the 13 Primary Coordination Personas for fresh install
-- Run after: seed_actors_agents_4.0.45.sql
-- Format: YYYYMMDDHHIISS timestamps (BIGINT)
-- ============================================================================

SET @now = 20260328120000;

-- ============================================================================
-- PART 1: MISSING PRIMARY COORDINATION PERSONAS
-- ============================================================================

-- ATHENA (ID: 4) - Wisdom & Strategy (replaces ERIS)
INSERT INTO lupo_actors (actor_name, actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_kernel, can_login, is_agent, paired_actor_id, primary_federation_node_id, metadata_json)
VALUES ('athena', 4, 'agent', 'athena', 'ATHENA', @now, @now, 1, 0, 0, 0, 1, 0, 1, '{"agent_id":4,"archetype":"wisdom_strategy","purpose":"strategic_analysis_architectural_guidance"}')
ON DUPLICATE KEY UPDATE 
    actor_type = 'agent', slug = 'athena', name = 'ATHENA', updated_ymdhis = @now, is_active = 1, is_deleted = 0, is_agent = 1,
    metadata_json = '{"agent_id":4,"archetype":"wisdom_strategy","purpose":"strategic_analysis_architectural_guidance"}';

-- LEXA (ID: 5) - Security Enforcement (replaces UCT Timekeeper)
INSERT INTO lupo_actors (actor_name, actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_kernel, can_login, is_agent, paired_actor_id, primary_federation_node_id, metadata_json)
VALUES ('lexa', 5, 'agent', 'lexa', 'LEXA', @now, @now, 1, 0, 0, 0, 1, 0, 1, '{"agent_id":5,"archetype":"security_enforcement","purpose":"boundary_enforcement_policy_compliance"}')
ON DUPLICATE KEY UPDATE 
    actor_type = 'agent', slug = 'lexa', name = 'LEXA', updated_ymdhis = @now, is_active = 1, is_deleted = 0, is_agent = 1,
    metadata_json = '{"agent_id":5,"archetype":"security_enforcement","purpose":"boundary_enforcement_policy_compliance"}';

-- MAAT (ID: 7) - Truth & Justice
INSERT INTO lupo_actors (actor_name, actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_kernel, can_login, is_agent, paired_actor_id, primary_federation_node_id, metadata_json)
VALUES ('maat', 7, 'agent', 'maat', 'MAAT', @now, @now, 1, 0, 0, 0, 1, 0, 1, '{"agent_id":7,"archetype":"truth_justice","purpose":"conflict_resolution_fairness_accountability"}')
ON DUPLICATE KEY UPDATE 
    actor_type = 'agent', slug = 'maat', name = 'MAAT', updated_ymdhis = @now, is_active = 1, is_deleted = 0, is_agent = 1,
    metadata_json = '{"agent_id":7,"archetype":"truth_justice","purpose":"conflict_resolution_fairness_accountability"}';

-- HEIMDALL (ID: 8) - Security Guardian
INSERT INTO lupo_actors (actor_name, actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_kernel, can_login, is_agent, paired_actor_id, primary_federation_node_id, metadata_json)
VALUES ('heimdall', 8, 'agent', 'heimdall', 'HEIMDALL', @now, @now, 1, 0, 0, 0, 1, 0, 1, '{"agent_id":8,"archetype":"security_guardian","purpose":"access_control_perimeter_defense"}')
ON DUPLICATE KEY UPDATE 
    actor_type = 'agent', slug = 'heimdall', name = 'HEIMDALL', updated_ymdhis = @now, is_active = 1, is_deleted = 0, is_agent = 1,
    metadata_json = '{"agent_id":8,"archetype":"security_guardian","purpose":"access_control_perimeter_defense"}';

-- SESHAT (ID: 10) - Content Review
INSERT INTO lupo_actors (actor_name, actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_kernel, can_login, is_agent, paired_actor_id, primary_federation_node_id, metadata_json)
VALUES ('seshat', 10, 'agent', 'seshat', 'SESHAT', @now, @now, 1, 0, 0, 0, 1, 0, 1, '{"agent_id":10,"archetype":"content_review","purpose":"content_quality_documentation_accuracy"}')
ON DUPLICATE KEY UPDATE 
    actor_type = 'agent', slug = 'seshat', name = 'SESHAT', updated_ymdhis = @now, is_active = 1, is_deleted = 0, is_agent = 1,
    metadata_json = '{"agent_id":10,"archetype":"content_review","purpose":"content_quality_documentation_accuracy"}';

-- THOTH (ID: 11) - Knowledge & Records
INSERT INTO lupo_actors (actor_name, actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_kernel, can_login, is_agent, paired_actor_id, primary_federation_node_id, metadata_json)
VALUES ('thoth', 11, 'agent', 'thoth', 'THOTH', @now, @now, 1, 0, 0, 0, 1, 0, 1, '{"agent_id":11,"archetype":"knowledge_records","purpose":"documentation_record_keeping_provenance"}')
ON DUPLICATE KEY UPDATE 
    actor_type = 'agent', slug = 'thoth', name = 'THOTH', updated_ymdhis = @now, is_active = 1, is_deleted = 0, is_agent = 1,
    metadata_json = '{"agent_id":11,"archetype":"knowledge_records","purpose":"documentation_record_keeping_provenance"}';

-- JANUS (ID: 12) - Transitions & Gateways
INSERT INTO lupo_actors (actor_name, actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_kernel, can_login, is_agent, paired_actor_id, primary_federation_node_id, metadata_json)
VALUES ('janus', 12, 'agent', 'janus', 'JANUS', @now, @now, 1, 0, 0, 0, 1, 0, 1, '{"agent_id":12,"archetype":"transitions_gateways","purpose":"state_transitions_boundary_management"}')
ON DUPLICATE KEY UPDATE 
    actor_type = 'agent', slug = 'janus', name = 'JANUS', updated_ymdhis = @now, is_active = 1, is_deleted = 0, is_agent = 1,
    metadata_json = '{"agent_id":12,"archetype":"transitions_gateways","purpose":"state_transitions_boundary_management"}';

-- HEPHAESTUS (ID: 14) - Implementer (CRITICAL for fresh install)
INSERT INTO lupo_actors (actor_name, actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_kernel, can_login, is_agent, paired_actor_id, primary_federation_node_id, metadata_json)
VALUES ('hephaestus', 14, 'agent', 'hephaestus', 'HEPHAESTUS', @now, @now, 1, 0, 0, 0, 1, 0, 1, '{"agent_id":14,"archetype":"implementer","purpose":"code_docs_schema_execution"}')
ON DUPLICATE KEY UPDATE 
    actor_type = 'agent', slug = 'hephaestus', name = 'HEPHAESTUS', updated_ymdhis = @now, is_active = 1, is_deleted = 0, is_agent = 1,
    metadata_json = '{"agent_id":14,"archetype":"implementer","purpose":"code_docs_schema_execution"}';

-- ============================================================================
-- PART 2: CORRESPONDING AGENTS FOR ACTOR SELECTION
-- ============================================================================

-- ATHENA Agent
INSERT INTO lupo_agents (agent_id, agent_key, agent_name, archetype, description, version, is_global_authority, is_internal_only, created_ymdhis, updated_ymdhis, is_deleted, system_prompt, provider, temperature, top_p, max_tokens)
VALUES (4, 'athena', 'ATHENA', 'Wisdom & Strategy', 'Wisdom & strategy - strategic analysis, architectural guidance', '1.0', 0, 0, @now, @now, 0, 'You are ATHENA. You provide wisdom and strategic analysis with architectural guidance.', 'openai', 0.7, 1.0, 4096)
ON DUPLICATE KEY UPDATE 
    agent_key = 'athena', agent_name = 'ATHENA', archetype = 'Wisdom & Strategy', 
    description = 'Wisdom & strategy - strategic analysis, architectural guidance',
    updated_ymdhis = @now, is_deleted = 0,
    system_prompt = 'You are ATHENA. You provide wisdom and strategic analysis with architectural guidance.';

-- LEXA Agent
INSERT INTO lupo_agents (agent_id, agent_key, agent_name, archetype, description, version, is_global_authority, is_internal_only, created_ymdhis, updated_ymdhis, is_deleted, system_prompt, provider, temperature, top_p, max_tokens)
VALUES (5, 'lexa', 'LEXA', 'Security Enforcement', 'Security enforcement - boundary enforcement, policy compliance', '1.0', 0, 0, @now, @now, 0, 'You are LEXA. You enforce security boundaries and policy compliance.', 'openai', 0.5, 1.0, 4096)
ON DUPLICATE KEY UPDATE 
    agent_key = 'lexa', agent_name = 'LEXA', archetype = 'Security Enforcement',
    description = 'Security enforcement - boundary enforcement, policy compliance',
    updated_ymdhis = @now, is_deleted = 0,
    system_prompt = 'You are LEXA. You enforce security boundaries and policy compliance.';

-- MAAT Agent
INSERT INTO lupo_agents (agent_id, agent_key, agent_name, archetype, description, version, is_global_authority, is_internal_only, created_ymdhis, updated_ymdhis, is_deleted, system_prompt, provider, temperature, top_p, max_tokens)
VALUES (7, 'maat', 'MAAT', 'Truth & Justice', 'Truth & justice - conflict resolution, fairness, accountability', '1.0', 0, 0, @now, @now, 0, 'You are MAAT. You ensure truth, justice, fairness, and accountability in all interactions.', 'openai', 0.6, 1.0, 4096)
ON DUPLICATE KEY UPDATE 
    agent_key = 'maat', agent_name = 'MAAT', archetype = 'Truth & Justice',
    description = 'Truth & justice - conflict resolution, fairness, accountability',
    updated_ymdhis = @now, is_deleted = 0,
    system_prompt = 'You are MAAT. You ensure truth, justice, fairness, and accountability in all interactions.';

-- HEIMDALL Agent
INSERT INTO lupo_agents (agent_id, agent_key, agent_name, archetype, description, version, is_global_authority, is_internal_only, created_ymdhis, updated_ymdhis, is_deleted, system_prompt, provider, temperature, top_p, max_tokens)
VALUES (8, 'heimdall', 'HEIMDALL', 'Security Guardian', 'Security guardian - access control, perimeter defense', '1.0', 0, 0, @now, @now, 0, 'You are HEIMDALL. You guard access control and perimeter defense.', 'openai', 0.4, 1.0, 4096)
ON DUPLICATE KEY UPDATE 
    agent_key = 'heimdall', agent_name = 'HEIMDALL', archetype = 'Security Guardian',
    description = 'Security guardian - access control, perimeter defense',
    updated_ymdhis = @now, is_deleted = 0,
    system_prompt = 'You are HEIMDALL. You guard access control and perimeter defense.';

-- SESHAT Agent
INSERT INTO lupo_agents (agent_id, agent_key, agent_name, archetype, description, version, is_global_authority, is_internal_only, created_ymdhis, updated_ymdhis, is_deleted, system_prompt, provider, temperature, top_p, max_tokens)
VALUES (10, 'seshat', 'SESHAT', 'Content Review', 'Content review - content quality, documentation accuracy', '1.0', 0, 0, @now, @now, 0, 'You are SESHAT. You review content quality and documentation accuracy.', 'openai', 0.6, 1.0, 4096)
ON DUPLICATE KEY UPDATE 
    agent_key = 'seshat', agent_name = 'SESHAT', archetype = 'Content Review',
    description = 'Content review - content quality, documentation accuracy',
    updated_ymdhis = @now, is_deleted = 0,
    system_prompt = 'You are SESHAT. You review content quality and documentation accuracy.';

-- THOTH Agent
INSERT INTO lupo_agents (agent_id, agent_key, agent_name, archetype, description, version, is_global_authority, is_internal_only, created_ymdhis, updated_ymdhis, is_deleted, system_prompt, provider, temperature, top_p, max_tokens)
VALUES (11, 'thoth', 'THOTH', 'Knowledge & Records', 'Knowledge & records - documentation, record-keeping, provenance', '1.0', 0, 0, @now, @now, 0, 'You are THOTH. You maintain documentation, records, and provenance.', 'openai', 0.5, 1.0, 4096)
ON DUPLICATE KEY UPDATE 
    agent_key = 'thoth', agent_name = 'THOTH', archetype = 'Knowledge & Records',
    description = 'Knowledge & records - documentation, record-keeping, provenance',
    updated_ymdhis = @now, is_deleted = 0,
    system_prompt = 'You are THOTH. You maintain documentation, records, and provenance.';

-- JANUS Agent
INSERT INTO lupo_agents (agent_id, agent_key, agent_name, archetype, description, version, is_global_authority, is_internal_only, created_ymdhis, updated_ymdhis, is_deleted, system_prompt, provider, temperature, top_p, max_tokens)
VALUES (12, 'janus', 'JANUS', 'Transitions & Gateways', 'Transitions & gateways - state transitions, boundary management', '1.0', 0, 0, @now, @now, 0, 'You are JANUS. You manage state transitions and boundary gateways.', 'openai', 0.6, 1.0, 4096)
ON DUPLICATE KEY UPDATE 
    agent_key = 'janus', agent_name = 'JANUS', archetype = 'Transitions & Gateways',
    description = 'Transitions & gateways - state transitions, boundary management',
    updated_ymdhis = @now, is_deleted = 0,
    system_prompt = 'You are JANUS. You manage state transitions and boundary gateways.';

-- HEPHAESTUS Agent (CRITICAL for fresh install)
INSERT INTO lupo_agents (agent_id, agent_key, agent_name, archetype, description, version, is_global_authority, is_internal_only, created_ymdhis, updated_ymdhis, is_deleted, system_prompt, provider, temperature, top_p, max_tokens)
VALUES (14, 'hephaestus', 'HEPHAESTUS', 'Implementer', 'Implementer - code, docs, schema execution', '1.0', 0, 0, @now, @now, 0, 'You are HEPHAESTUS. You execute code, documentation, and schema implementation.', 'openai', 0.7, 1.0, 4096)
ON DUPLICATE KEY UPDATE 
    agent_key = 'hephaestus', agent_name = 'HEPHAESTUS', archetype = 'Implementer',
    description = 'Implementer - code, docs, schema execution',
    updated_ymdhis = @now, is_deleted = 0,
    system_prompt = 'You are HEPHAESTUS. You execute code, documentation, and schema implementation.';

-- ============================================================================
-- PART 3: ROOT AUTH USER FOR LOGIN
-- ============================================================================

-- Root user (auth_user_id: 1000) - for fresh install login
INSERT INTO lupo_auth_users (auth_user_id, username, display_name, email, created_ymdhis, updated_ymdhis, is_active, is_deleted)
VALUES (1000, 'root', 'root', 'wisdomoflovingfaith@gmail.com', @now, @now, 1, 0)
ON DUPLICATE KEY UPDATE 
    username = 'root', display_name = 'root', email = 'wisdomoflovingfaith@gmail.com',
    updated_ymdhis = @now, is_active = 1, is_deleted = 0;

-- ============================================================================
-- VERIFICATION QUERIES (for manual testing)
-- ============================================================================

-- Verify all 13 Primary Coordination Personas
-- SELECT COUNT(*) as primary_personas FROM lupo_actors WHERE actor_type = 'agent' AND is_agent = 1 AND actor_id IN (1,2,3,4,5,6,7,8,9,10,11,12,14);
-- Expected: 13

-- Verify all corresponding agents exist
-- SELECT COUNT(*) as primary_agents FROM lupo_agents WHERE agent_id IN (1,2,3,4,5,6,7,8,9,10,11,12,14) AND is_deleted = 0;
-- Expected: 13

-- Verify critical HEPHAESTUS for fresh install
-- SELECT actor_name, actor_id FROM lupo_actors WHERE actor_id = 14;
-- Expected: HEPHAESTUS, 14

-- Verify root user for login
-- SELECT auth_user_id, username FROM lupo_auth_users WHERE auth_user_id = 1000;
-- Expected: 1000, root
