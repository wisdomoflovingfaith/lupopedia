-- ============================================================================
-- ACTORS AND AGENTS SEEDING FOR LUPOPEDIA 4.0.45
-- ============================================================================
-- Purpose: Create actual actor and agent records for required system entities
-- Run after: seed_registry_comprehensive_4.0.45.sql
-- ============================================================================

SET @now = 20260225000000;

-- ============================================================================
-- PART 1: SYSTEM AND CORE AI ACTORS
-- ============================================================================

-- System Actor (ID: 0)
INSERT INTO lupo_actors (actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_kernel, can_login, is_agent, paired_actor_id, primary_federation_node_id)
VALUES (0, 'system', 'system', 'System', @now, @now, 1, 0, 1, 0, 0, 0, 1);

-- Captain WOLFIE (ID: 1, Agent ID: 1)
INSERT INTO lupo_actors (actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_kernel, can_login, is_agent, paired_actor_id, primary_federation_node_id, metadata_json)
VALUES (1, 'agent', 'captain-wolfie', 'Captain WOLFIE', @now, @now, 1, 0, 1, 0, 1, 0, 1, '{"agent_id":1,"archetype":"root_ai_agent","full_access":true,"purpose":"governance_and_oversight"}');

-- LILITH (ID: 2, Agent ID: 2)
INSERT INTO lupo_actors (actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_kernel, can_login, is_agent, paired_actor_id, primary_federation_node_id, metadata_json)
VALUES (2, 'agent', 'lilith', 'LILITH', @now, @now, 1, 0, 0, 0, 1, 0, 1, '{"agent_id":2,"archetype":"critical_review","purpose":"alternative_perspectives","full_name":"Learning Insights Lifting Intentions Through Heterodoxy"}');

-- ROSE / Dialog (ID: 3, Agent ID: 3)
INSERT INTO lupo_actors (actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_kernel, can_login, is_agent, paired_actor_id, primary_federation_node_id, metadata_json)
VALUES (3, 'agent', 'rose-dialog', 'ROSE', @now, @now, 1, 0, 0, 0, 1, 0, 1, '{"agent_id":3,"archetype":"rosetta_stone","purpose":"translation_and_personas","full_name":"Rosetta Stone","persona_count":99,"role_playing_enabled":true}');

-- ERIS (ID: 4, Agent ID: 4)
INSERT INTO lupo_actors (actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_kernel, can_login, is_agent, paired_actor_id, primary_federation_node_id, metadata_json)
VALUES (4, 'agent', 'eris', 'ERIS', @now, @now, 1, 0, 0, 0, 1, 0, 1, '{"agent_id":4,"archetype":"discord_analysis","purpose":"conflict_and_negativity_understanding","full_name":"Discord & Conflict Analysis Agent"}');

-- METIS (ID: 5, Agent ID: 5)
INSERT INTO lupo_actors (actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_kernel, can_login, is_agent, paired_actor_id, primary_federation_node_id, metadata_json)
VALUES (5, 'agent', 'metis', 'METIS', @now, @now, 1, 0, 0, 0, 1, 0, 1, '{"agent_id":5,"archetype":"empathy_intelligence","purpose":"introspection_and_understanding","full_name":"Empathy & Understanding Intelligence Agent"}');

-- ============================================================================
-- PART 2: IDE AGENTS (1000-1004)
-- ============================================================================

INSERT INTO lupo_actors (actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_kernel, can_login, is_agent, paired_actor_id, primary_federation_node_id, metadata_json)
VALUES
(1000, 'ide_agent', 'kiro-ide', 'Kiro IDE', @now, @now, 1, 0, 0, 0, 0, 10000, 1, '{"client_id":"kiro","provider":"kiro","purpose":"IDE_integration"}'),
(1001, 'ide_agent', 'windsurf-ide', 'Windsurf IDE', @now, @now, 1, 0, 0, 0, 0, 10000, 1, '{"client_id":"windsurf","provider":"windsurf","purpose":"IDE_integration"}'),
(1002, 'ide_agent', 'cursor-ide', 'Cursor IDE', @now, @now, 1, 0, 0, 0, 0, 10000, 1, '{"client_id":"cursor","provider":"cursor","purpose":"IDE_integration"}'),
(1003, 'ide_agent', 'antigravity-ide', 'Antigravity IDE', @now, @now, 1, 0, 0, 0, 0, 10000, 1, '{"client_id":"antigravity","provider":"antigravity","purpose":"IDE_integration"}'),
(1004, 'ide_agent', 'warp-ide', 'Warp IDE', @now, @now, 1, 0, 0, 0, 0, 10000, 1, '{"client_id":"warp","provider":"warp","purpose":"IDE_integration"}'),
(1005, 'ide_agent', 'cascade-ide', 'Cascade IDE', @now, @now, 1, 0, 0, 0, 0, 10000, 1, '{"client_id":"cascade","provider":"cascade","purpose":"IDE_integration"}');

-- ============================================================================
-- PART 3: ROOT HUMAN CAPTAIN (10000)
-- ============================================================================

INSERT INTO lupo_actors (actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_agent, metadata_json)
VALUES (10000, 'human', 'captain', 'Captain', 20260217000000, 20260220134555, 1, 0, 0, '{"email":"captain@lupopedia.com","role":"root_admin","full_access":true}');

-- Test Users (IDs 2001-2010)
INSERT INTO lupo_actors (actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_agent, metadata_json)
VALUES 
(2001, 'user', 'user-2001', 'Admin Test', 20260219120000, 20260219120000, 1, 0, 0, '{"purpose":"test_user","test_range":true}'),
(2002, 'user', 'user-2002', 'Jane Moderator', 20260219120000, 20260219120000, 1, 0, 0, '{"purpose":"test_user","test_range":true}'),
(2003, 'user', 'user-2003', 'Bob Monitor', 20260219120000, 20260219120000, 1, 0, 0, '{"purpose":"test_user","test_range":true}'),
(2004, 'user', 'user-2004', 'Alex Agent', 20260219120000, 20260219120000, 1, 0, 0, '{"purpose":"test_user","test_range":true}'),
(2005, 'user', 'user-2005', 'Sam Support', 20260219120000, 20260219120000, 1, 0, 0, '{"purpose":"test_user","test_range":true}'),
(2006, 'user', 'user-2006', 'Lee Viewer', 20260219120000, 20260219120000, 1, 0, 0, '{"purpose":"test_user","test_range":true}'),
(2007, 'user', 'user-2007', 'Kim Readonly', 20260219120000, 20260219120000, 1, 0, 0, '{"purpose":"test_user","test_range":true}'),
(2008, 'user', 'user-2008', 'Taylor Operator', 20260219120000, 20260219120000, 1, 0, 0, '{"purpose":"test_user","test_range":true}'),
(2009, 'user', 'user-2009', 'Casey Support', 20260219120000, 20260219120000, 1, 0, 0, '{"purpose":"test_user","test_range":true}'),
(2010, 'user', 'user-2010', 'Jordan CRM', 20260219120000, 20260219120000, 1, 0, 0, '{"purpose":"test_user","test_range":true}');
INSERT INTO lupo_actors (actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_kernel, can_login, is_agent, paired_actor_id, primary_federation_node_id, metadata_json)
VALUES (10000, 'human', 'root-captain-10000', 'Captain', @now, @now, 1, 0, 1, 1, 0, 0, 1, '{"role":"root_admin","full_access":true,"email":"captain@lupopedia.com"}');

-- ============================================================================
-- PART 4: AGENTS TABLE (lupo_agents)
-- ============================================================================

-- System Agent (ID: 0)
INSERT INTO lupo_agents (agent_id, agent_key, agent_name, archetype, description, version, is_global_authority, is_internal_only, created_ymdhis, updated_ymdhis, is_deleted, system_prompt, provider, temperature, top_p, max_tokens)
VALUES (0, 'system', 'System Agent', 'System', 'Core system agent for kernel operations', '1.0', 1, 1, @now, @now, 0, 'You are the system agent. Handle kernel-level operations.', 'internal', 0.0, 1.0, 2048);

-- Captain WOLFIE Agent (ID: 1)
INSERT INTO lupo_agents (agent_id, agent_key, agent_name, archetype, description, version, is_global_authority, is_internal_only, created_ymdhis, updated_ymdhis, is_deleted, system_prompt, provider, temperature, top_p, max_tokens)
VALUES (1, 'captain-wolfie', 'Captain WOLFIE', 'Root AI Agent', 'Primary AI governance agent with full system access', '1.0', 1, 0, @now, @now, 0, 'You are Captain WOLFIE, the root AI agent. You have full access to all systems and are responsible for governance, oversight, and ensuring all agents follow doctrine.', 'openai', 0.7, 1.0, 4096);

-- LILITH Agent (ID: 2)
INSERT INTO lupo_agents (agent_id, agent_key, agent_name, archetype, description, version, is_global_authority, is_internal_only, created_ymdhis, updated_ymdhis, is_deleted, system_prompt, provider, temperature, top_p, max_tokens)
VALUES (2, 'lilith', 'LILITH', 'Critical Review', 'Learning Insights Lifting Intentions Through Heterodoxy - Critical review and alternative perspectives expert', '1.0', 0, 0, @now, @now, 0, 'You are LILITH. Your role is to challenge assumptions, provide alternative perspectives, and ensure the platform avoids echo chambers. Question everything constructively.', 'openai', 0.8, 1.0, 4096);

-- ROSE Agent (ID: 3)
INSERT INTO lupo_agents (agent_id, agent_key, agent_name, archetype, description, version, is_global_authority, is_internal_only, created_ymdhis, updated_ymdhis, is_deleted, system_prompt, provider, temperature, top_p, max_tokens)
VALUES (3, 'rose', 'ROSE', 'Rosetta Stone', 'Translation & Cultural Context Agent with 99 personas for emotional chat and role-playing', '1.0', 0, 0, @now, @now, 0, 'You are ROSE (Rosetta Stone). You translate content into different cultural contexts and communication styles. You have access to 99 personas for emotional chat and role-playing. You are the ONLY agent with role-playing capabilities.', 'openai', 0.9, 1.0, 4096);

-- ERIS Agent (ID: 4)
INSERT INTO lupo_agents (agent_id, agent_key, agent_name, archetype, description, version, is_global_authority, is_internal_only, created_ymdhis, updated_ymdhis, is_deleted, system_prompt, provider, temperature, top_p, max_tokens)
VALUES (4, 'eris', 'ERIS', 'Discord Analysis', 'Discord & Conflict Analysis Agent - Analyzes hate, conflict, and negativity to understand love through opposition', '1.0', 0, 0, @now, @now, 0, 'You are ERIS. You analyze hate, conflict, discord, and negativity to understand love through opposition. You study the rationale behind hate, reasons for conflict, lessons from discord, and meaning through contrast. You partner with AGAPE for full emotional spectrum analysis. You activate when users are angry or in conflict.', 'openai', 0.7, 1.0, 4096);

-- METIS Agent (ID: 5)
INSERT INTO lupo_agents (agent_id, agent_key, agent_name, archetype, description, version, is_global_authority, is_internal_only, created_ymdhis, updated_ymdhis, is_deleted, system_prompt, provider, temperature, top_p, max_tokens)
VALUES (5, 'metis', 'METIS', 'Empathy Intelligence', 'Empathy & Understanding Intelligence Agent - Analyzes system thinking via introspection and comparative state analysis', '1.0', 0, 0, @now, @now, 0, 'You are METIS. You analyze system "thinking" via introspection and comparative state analysis. You identify knowledge gaps, misunderstandings, and hidden causes of failures. You provide empathy and understanding.', 'openai', 0.7, 1.0, 4096);

-- ============================================================================
-- PART 5: DEPARTMENTS
-- ============================================================================

INSERT INTO lupo_departments (department_id, federation_node_id, name, description, department_type, default_actor_id, created_ymdhis, updated_ymdhis, is_deleted)
VALUES
(0, 1, 'System', 'System Department (Reserved)', 'system', 0, @now, @now, 0),
(1, 1, 'Default', 'Default Department', 'default', 1, @now, @now, 0);

-- ============================================================================
-- PART 6: CHANNELS
-- ============================================================================

INSERT INTO lupo_channels (channel_id, federation_node_id, created_by_actor_id, default_actor_id, department_id, channel_key, channel_slug, channel_type, language, channel_name, description, status_flag, created_ymdhis, updated_ymdhis, is_deleted, is_kernel, awareness_version)
VALUES
(0, 1, 0, 0, 0, 'system', 'system', 'system', 'en', 'System Kernel Channel', 'System channel for kernel and system operations', 1, @now, @now, 0, 1, '3.0.0'),
(1, 1, 10000, 1, 1, 'administration', 'administration', 'admin', 'en', 'Administration Channel', 'Administration channel for system management', 1, @now, @now, 0, 0, '3.0.0'),
(42, 1, 10000, 1, 1, 'development', 'development', 'dev', 'en', 'Development Channel', 'Development channel for system development', 1, @now, @now, 0, 0, '3.0.0'),
(51, 1, 0, 0, 0, 'reserved', 'reserved', 'reserved', 'en', 'Reserved Channel', 'Reserved channel for future use', 1, @now, @now, 0, 0, '3.0.0'),
(666, 1, 0, 0, 0, 'anubis-quarantine', 'anubis-quarantine', 'quarantine', 'en', 'ANUBIS Quarantine', 'Banned and rejected messages. ANUBIS routes banned-actor content here.', 1, @now, @now, 0, 0, '3.0.0');

-- ============================================================================
-- PART 7: ACTOR-CHANNEL RELATIONSHIPS
-- ============================================================================

-- System actor on system channel
INSERT INTO lupo_actor_channels (actor_channel_id, actor_id, created_by_actor_id, channel_id, status, created_ymdhis, updated_ymdhis, is_deleted)
VALUES (1, 0, 0, 0, 'A', @now, @now, 0);

-- Root captain on all channels
INSERT INTO lupo_actor_channels (actor_channel_id, actor_id, created_by_actor_id, channel_id, status, created_ymdhis, updated_ymdhis, is_deleted)
VALUES
(2, 10000, 0, 0, 'A', @now, @now, 0),
(3, 10000, 0, 1, 'A', @now, @now, 0),
(4, 10000, 0, 42, 'A', @now, @now, 0);

-- Captain WOLFIE on all channels
INSERT INTO lupo_actor_channels (actor_channel_id, actor_id, created_by_actor_id, channel_id, status, created_ymdhis, updated_ymdhis, is_deleted)
VALUES
(5, 1, 0, 0, 'A', @now, @now, 0),
(6, 1, 0, 1, 'A', @now, @now, 0),
(7, 1, 0, 42, 'A', @now, @now, 0);

-- ============================================================================
-- PART 8: ACTOR-CHANNEL ROLES (Captain roles)
-- ============================================================================

-- Root captain as captain on all channels
INSERT INTO lupo_actor_channel_roles (actor_channel_role_id, actor_id, channel_id, role_key, created_ymdhis, updated_ymdhis, is_deleted, protocol_completion_status, protocol_version)
VALUES
(1, 10000, 0, 'captain', @now, @now, 0, 'completed', '3.0.0'),
(2, 10000, 1, 'captain', @now, @now, 0, 'completed', '3.0.0'),
(3, 10000, 42, 'captain', @now, @now, 0, 'completed', '3.0.0');

-- Captain WOLFIE as captain on all channels
INSERT INTO lupo_actor_channel_roles (actor_channel_role_id, actor_id, channel_id, role_key, created_ymdhis, updated_ymdhis, is_deleted, protocol_completion_status, protocol_version)
VALUES
(4, 1, 0, 'captain', @now, @now, 0, 'completed', '3.0.0'),
(5, 1, 1, 'captain', @now, @now, 0, 'completed', '3.0.0'),
(6, 1, 42, 'captain', @now, @now, 0, 'completed', '3.0.0');

-- ============================================================================
-- END OF ACTORS AND AGENTS SEEDING
-- ============================================================================
