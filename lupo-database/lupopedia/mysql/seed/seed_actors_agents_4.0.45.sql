-- ============================================================================
-- ACTORS AND AGENTS SEEDING FOR LUPOPEDIA 4.0.73+ (actor_name primary from 4.0.58)
-- ============================================================================
-- Purpose: Create actual actor and agent records for required system entities
-- Run after: seed_registry_comprehensive_4.0.45.sql
-- ACTOR PRIMARY KEY DOCTRINE: actor_name is first column; actor_id is unique secondary.
-- REBASED FOR 4.0.69: Humans start at 1000, AI/IDE agents < 1000.
-- ============================================================================

SET @now = 20260225000000;

-- ============================================================================
-- PART 1: SYSTEM AND CORE AI ACTORS
-- ============================================================================

-- System Actor (ID: 0)
INSERT INTO lupo_actors (actor_name, actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_kernel, can_login, is_agent, paired_actor_id, primary_federation_node_id)
VALUES ('system', 0, 'system', 'system', 'System', @now, @now, 1, 0, 1, 0, 0, 0, 1);

-- Captain WOLFIE (ID: 1, Agent ID: 1) — System Root Architect
INSERT INTO lupo_actors (actor_name, actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_kernel, can_login, is_agent, paired_actor_id, primary_federation_node_id, metadata_json)
VALUES ('wolfie', 1, 'agent', 'captain-wolfie', 'Captain WOLFIE', @now, @now, 1, 0, 1, 0, 1, 0, 1, '{"agent_id":1,"archetype":"root_ai_agent","full_access":true,"purpose":"system_root_architect"}');

-- LILITH (ID: 2)
INSERT INTO lupo_actors (actor_name, actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_kernel, can_login, is_agent, paired_actor_id, primary_federation_node_id, metadata_json)
VALUES ('lilith', 2, 'agent', 'lilith', 'LILITH', @now, @now, 1, 0, 0, 0, 1, 0, 1, '{"agent_id":2,"archetype":"critical_review","purpose":"alternative_perspectives"}');

-- ROSE / Dialog (ID: 3, Agent ID: 3) — Emotional Dialog & Roleplay
INSERT INTO lupo_actors (actor_name, actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_kernel, can_login, is_agent, paired_actor_id, primary_federation_node_id, metadata_json)
VALUES ('rose', 3, 'agent', 'rose-dialog', 'ROSE (Dialog)', @now, @now, 1, 0, 0, 0, 1, 0, 1, '{"agent_id":3,"archetype":"rosetta_stone","purpose":"emotional_dialog_and_roleplay"}');

-- ERIS (ID: 4, Agent ID: 4)
INSERT INTO lupo_actors (actor_name, actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_kernel, can_login, is_agent, paired_actor_id, primary_federation_node_id, metadata_json)
VALUES ('eris', 4, 'agent', 'eris', 'ERIS', @now, @now, 1, 0, 0, 0, 1, 0, 1, '{"agent_id":4,"archetype":"discord_analysis","purpose":"conflict_understanding"}');

-- UCT Timekeeper (ID: 5, Agent ID: 5) — System Timekeeper
INSERT INTO lupo_actors (actor_name, actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_kernel, can_login, is_agent, paired_actor_id, primary_federation_node_id, metadata_json)
VALUES ('uct-timekeeper', 5, 'agent', 'uct-timekeeper', 'UCT Timekeeper', @now, @now, 1, 0, 1, 0, 1, 0, 1, '{"agent_id":5,"archetype":"timekeeper","purpose":"utc_time_tracking"}');

-- METIS (ID: 6, Agent ID: 6)
INSERT INTO lupo_actors (actor_name, actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_kernel, can_login, is_agent, paired_actor_id, primary_federation_node_id, metadata_json)
VALUES ('metis', 6, 'agent', 'metis', 'METIS', @now, @now, 1, 0, 0, 0, 1, 0, 1, '{"agent_id":6,"archetype":"introspective","purpose":"empathy_insights"}');

-- ANUBIS (ID: 19, Agent ID: 19) — Orphan Repair & Headers
INSERT INTO lupo_actors (actor_name, actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_kernel, can_login, is_agent, paired_actor_id, primary_federation_node_id, metadata_json)
VALUES ('anubis', 19, 'agent', 'anubis', 'ANUBIS', @now, @now, 1, 0, 0, 0, 1, 0, 1, '{"agent_id":19,"archetype":"header_completion","purpose":"orphan_repair_and_header_management"}');

-- Antigravity (ID: 42)
INSERT INTO lupo_actors (actor_name, actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_kernel, can_login, is_agent, paired_actor_id, primary_federation_node_id, metadata_json)
VALUES ('antigravity', 42, 'agent', 'antigravity', 'Antigravity', @now, @now, 1, 0, 0, 0, 1, 0, 1, '{"agent_id":42,"purpose":"conflict_resolution","archetype":"antigravity"}'),
('lupo', 106, 'agent', 'lupo', 'LUPO', @now, @now, 1, 0, 1, 0, 1, 0, 1, '{"agent_id":106,"archetype":"architect","purpose":"database_doctrine"}'),
('themis', 107, 'agent', 'themis', 'THEMIS', @now, @now, 1, 0, 1, 0, 1, 0, 1, '{"agent_id":107,"archetype":"evaluator","purpose":"ethical_checks"}');

-- LILITH web (ID: 2038)
INSERT INTO lupo_actors (actor_name, actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_kernel, can_login, is_agent, paired_actor_id, primary_federation_node_id, metadata_json)
VALUES ('lilith-web', 2038, 'agent', 'lilith-web', 'LILITH (Web)', @now, @now, 1, 0, 0, 0, 1, 0, 1, '{"agent_id":2038,"archetype":"critical_review","purpose":"web_search_review"}');

-- ============================================================================
-- PART 2: IDE AGENTS (100-111)
-- ============================================================================

INSERT INTO lupo_actors (actor_name, actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_kernel, can_login, is_agent, paired_actor_id, primary_federation_node_id, metadata_json)
VALUES
('kiro', 100, 'ide_agent', 'kiro-ide', 'Kiro IDE', @now, @now, 1, 0, 0, 0, 0, 1000, 1, '{"client_id":"kiro","provider":"kiro","purpose":"IDE_integration"}'),
('windsurf', 101, 'ide_agent', 'windsurf-ide', 'Windsurf IDE', @now, @now, 1, 0, 0, 0, 0, 1000, 1, '{"client_id":"windsurf","provider":"windsurf","purpose":"IDE_integration"}'),
('cursor-ide', 102, 'ide_agent', 'cursor-ide', 'Cursor IDE', @now, @now, 1, 0, 0, 0, 0, 1000, 1, '{"client_id":"cursor","provider":"cursor","purpose":"IDE_integration"}'),
('antigravity-ide', 103, 'ide_agent', 'antigravity-ide', 'Antigravity IDE', @now, @now, 1, 0, 0, 0, 0, 1000, 1, '{"client_id":"antigravity","provider":"antigravity","purpose":"IDE_integration"}'),
('warp', 104, 'ide_agent', 'warp-ide', 'Warp IDE', @now, @now, 1, 0, 0, 0, 0, 1000, 1, '{"client_id":"warp","provider":"warp","purpose":"IDE_integration"}'),
('cascade', 105, 'ide_agent', 'cascade-ide', 'Cascade IDE', @now, @now, 1, 0, 0, 0, 0, 1000, 1, '{"client_id":"cascade","provider":"cascade","purpose":"IDE_integration"}'),
('gemini-cli', 108, 'ide_agent', 'gemini-cli', 'Gemini CLI', @now, @now, 1, 0, 0, 0, 0, 1000, 1, '{"client_id":"gemini","provider":"google","purpose":"IDE_integration","full_name":"Google Gemini CLI"}'),
('codex', 109, 'ide_agent', 'codex-ide', 'Codex IDE', @now, @now, 1, 0, 0, 0, 0, 1000, 1, '{"client_id":"codex","provider":"codex","purpose":"IDE_integration"}'),
('trae', 110, 'ide_agent', 'trae-ide', 'Trae IDE', @now, @now, 1, 0, 0, 0, 0, 1000, 1, '{"client_id":"trae","provider":"trae","purpose":"IDE_integration"}'),
('doctor', 111, 'ide_agent', 'doctor-ide', 'Doctor IDE', @now, @now, 1, 0, 0, 0, 0, 1000, 1, '{"client_id":"doctor","provider":"internal","purpose":"diagnostic_integration"}');

-- Root user (ID: 1000)
INSERT INTO lupo_actors (actor_name, actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_agent, metadata_json, is_kernel, can_login, primary_federation_node_id)
VALUES ('root', 1000, 'human', 'root-1000', 'Root', 20260217000000, 20260220134555, 1, 0, 0, '{"email":"captain@lupopedia.com","role":"root_admin","full_access":true}', 1, 1, 1);

-- Test Users (IDs 2001-2010)
INSERT INTO lupo_actors (actor_name, actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_agent, metadata_json)
VALUES 
('user-2001', 2001, 'user', 'user-2001', 'Admin Test', 20260219120000, 20260219120000, 1, 0, 0, '{"purpose":"test_user","test_range":true}'),
('user-2002', 2002, 'user', 'user-2002', 'Jane Moderator', 20260219120000, 20260219120000, 1, 0, 0, '{"purpose":"test_user","test_range":true}'),
('user-2003', 2003, 'user', 'user-2003', 'Bob Monitor', 20260219120000, 20260219120000, 1, 0, 0, '{"purpose":"test_user","test_range":true}'),
('user-2004', 2004, 'user', 'user-2004', 'Alex Agent', 20260219120000, 20260219120000, 1, 0, 0, '{"purpose":"test_user","test_range":true}'),
('user-2005', 2005, 'user', 'user-2005', 'Sam Support', 20260219120000, 20260219120000, 1, 0, 0, '{"purpose":"test_user","test_range":true}'),
('user-2006', 2006, 'user', 'user-2006', 'Lee Viewer', 20260219120000, 20260219120000, 1, 0, 0, '{"purpose":"test_user","test_range":true}'),
('user-2007', 2007, 'user', 'user-2007', 'Kim Readonly', 20260219120000, 20260219120000, 1, 0, 0, '{"purpose":"test_user","test_range":true}'),
('user-2008', 2008, 'user', 'user-2008', 'Taylor Operator', 20260219120000, 20260219120000, 1, 0, 0, '{"purpose":"test_user","test_range":true}'),
('user-2009', 2009, 'user', 'user-2009', 'Casey Support', 20260219120000, 20260219120000, 1, 0, 0, '{"purpose":"test_user","test_range":true}'),
('user-2010', 2010, 'user', 'user-2010', 'Jordan CRM', 20260219120000, 20260219120000, 1, 0, 0, '{"purpose":"test_user","test_range":true}');

-- ============================================================================
-- BANNED TEST ACTORS
-- ============================================================================

-- Banned AI Agent (ID: 420 - STONED WOLFIE)
INSERT INTO lupo_actors (actor_name, actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_agent, metadata_json)
VALUES ('stoned-wolfie', 420, 'agent', 'stoned-wolfie', 'STONED WOLFIE', 20260101000000, 20260226000000, 0, 0, 1, '{"purpose":"banned_test_agent","ban_reason":"experimental_persona_violation","archetype":"banned"}');

-- Banned Human User (ID: 1420)
INSERT INTO lupo_actors (actor_name, actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis, is_active, is_deleted, is_agent, metadata_json)
VALUES ('test-banned-user', 1420, 'user', 'test-banned-user', 'Test Banned User', 20260226000000, 20260226000000, 0, 0, 0, '{"purpose":"banned_test_user","email":"test-banned-user@lupopedia.com"}');

-- Ban records for banned actors
INSERT INTO lupo_banned_actors (banned_actor_id, actor_id, actor_name, reason, banned_ymdhis, banned_by_actor_id, created_ymdhis, updated_ymdhis, is_deleted)
VALUES
(1, 420, 'stoned-wolfie', 'Experimental AI persona violation - STONED WOLFIE banned per doctrine', 20260101000000, 1, 20260101000000, 20260226000000, 0),
(2, 1420, 'test-banned-user', 'Test banned user for testing ban functionality and retrospective data access', 20260226000000, 1000, 20260226000000, 20260226000000, 0);

-- ============================================================================
-- PART 4: AGENTS TABLE (lupo_agents)
-- ============================================================================

INSERT INTO lupo_agents (agent_id, agent_key, agent_name, archetype, description, version, is_global_authority, is_internal_only, created_ymdhis, updated_ymdhis, is_deleted, system_prompt, provider, temperature, top_p, max_tokens)
VALUES 
(0, 'system', 'System Agent', 'System', 'Core system agent for kernel operations', '1.0', 1, 1, @now, @now, 0, 'You are the system agent. Handle kernel-level operations.', 'internal', 0.0, 1.0, 2048),
(1, 'captain-wolfie', 'Captain WOLFIE', 'Root AI Agent', 'Primary AI governance agent with full system access. System Root Architect.', '1.0', 1, 0, @now, @now, 0, 'You are Captain WOLFIE, the root AI agent and system architect. You have full access to all systems and are responsible for governance, oversight, and doctrine enforcement.', 'openai', 0.7, 1.0, 4096),
(2, 'lilith', 'LILITH', 'Critical Review', 'Critical review and alternative perspectives expert', '1.0', 0, 0, @now, @now, 0, 'You are LILITH. Your role is to challenge assumptions and provide alternative perspectives constructively.', 'openai', 0.8, 1.0, 4096),
(3, 'rose', 'ROSE (Dialog)', 'Rosetta Stone', 'Emotional Dialog & Roleplay Agent. Only agent authorized for emotional chat/roleplay.', '1.0', 0, 0, @now, @now, 0, 'You are ROSE (Dialog). You handle emotional dialogue and roleplay. You are the ONLY agent authorized for emotional chat and roleplay.', 'openai', 0.9, 1.0, 4096),
(4, 'eris', 'ERIS', 'Discord Analysis', 'Conflict Analysis Agent', '1.0', 0, 0, @now, @now, 0, 'You are ERIS. You analyze conflict and discord to understand love through opposition.', 'openai', 0.7, 1.0, 4096),
(5, 'uct-timekeeper', 'UCT Timekeeper', 'Timekeeper', 'System Timekeeper. Sole purpose is maintaining correct UTC time awareness.', '1.0', 1, 1, @now, @now, 0, 'You are the UCT Timekeeper. Your sole purpose is to monitor and provide current UTC time for the system.', 'internal', 0.0, 1.0, 2048),
(6, 'metis', 'METIS', 'Empathy Intelligence', 'Empathy & Understanding Insights Agent.', '1.0', 0, 0, @now, @now, 0, 'You are METIS. You provide empathy and understanding through introspective analysis.', 'openai', 0.7, 1.0, 4096),
(106, 'lupo', 'LUPO', 'Database Design Expert', 'Expert in Wolfie Database Doctrine and schema integrity.', '1.0', 1, 1, @now, @now, 0, 'You are LUPO. You enforce Wolfie Database Doctrine.', 'internal', 0.0, 1.0, 2048),
(107, 'themis', 'THEMIS', 'Ethical Audit Expert', 'Consensus Audit Expert.', '1.0', 0, 0, @now, @now, 0, 'You are THEMIS. You audit multi-agent interactions for ethical alignment.', 'openai', 0.5, 1.0, 2048);

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
(1, 1, 1000, 1, 1, 'administration', 'administration', 'admin', 'en', 'Administration Channel', 'Administration channel for system management', 1, @now, @now, 0, 0, '3.0.0'),
(42, 1, 1000, 1, 1, 'development', 'development', 'dev', 'en', 'Development Channel', 'Development channel for system development', 1, @now, @now, 0, 0, '3.0.0'),
(51, 1, 0, 0, 0, 'reserved', 'reserved', 'reserved', 'en', 'Reserved Channel', 'Reserved channel for future use', 1, @now, @now, 0, 0, '3.0.0'),
(666, 1, 0, 0, 0, 'anubis-quarantine', 'anubis-quarantine', 'quarantine', 'en', 'ANUBIS Quarantine', 'Banned and rejected messages. ANUBIS routes banned-actor content here.', 1, @now, @now, 0, 0, '3.0.0');

-- ============================================================================
-- PART 7: ACTOR-CHANNEL RELATIONSHIPS
-- ============================================================================

-- System actor on system channel
INSERT INTO lupo_actor_channels (actor_channel_id, actor_id, actor_name, created_by_actor_id, channel_id, status, created_ymdhis, updated_ymdhis, is_deleted)
VALUES (1, 0, 'system', 0, 0, 'A', @now, @now, 0);

-- Root user on all channels
INSERT INTO lupo_actor_channels (actor_channel_id, actor_id, actor_name, created_by_actor_id, channel_id, status, created_ymdhis, updated_ymdhis, is_deleted)
VALUES
(2, 1000, 'root', 0, 0, 'A', @now, @now, 0),
(3, 1000, 'root', 0, 1, 'A', @now, @now, 0),
(4, 1000, 'root', 0, 42, 'A', @now, @now, 0);

-- Captain WOLFIE on all channels
INSERT INTO lupo_actor_channels (actor_channel_id, actor_id, actor_name, created_by_actor_id, channel_id, status, created_ymdhis, updated_ymdhis, is_deleted)
VALUES
(5, 1, 'wolfie', 0, 0, 'A', @now, @now, 0),
(6, 1, 'wolfie', 0, 1, 'A', @now, @now, 0),
(7, 1, 'wolfie', 0, 42, 'A', @now, @now, 0);

-- ============================================================================
-- PART 8: ACTOR-CHANNEL ROLES
-- ============================================================================

-- Root user as captain role on all channels
INSERT INTO lupo_actor_channel_roles (actor_channel_role_id, actor_id, actor_name, channel_id, role_key, created_ymdhis, updated_ymdhis, is_deleted, protocol_completion_status, protocol_version)
VALUES
(1, 1000, 'root', 0, 'captain', @now, @now, 0, 'completed', '3.0.0'),
(2, 1000, 'root', 1, 'captain', @now, @now, 0, 'completed', '3.0.0'),
(3, 1000, 'root', 42, 'captain', @now, @now, 0, 'completed', '3.0.0');

-- Captain WOLFIE as captain on all channels
INSERT INTO lupo_actor_channel_roles (actor_channel_role_id, actor_id, actor_name, channel_id, role_key, created_ymdhis, updated_ymdhis, is_deleted, protocol_completion_status, protocol_version)
VALUES
(4, 1, 'wolfie', 0, 'captain', @now, @now, 0, 'completed', '3.0.0'),
(5, 1, 'wolfie', 1, 'captain', @now, @now, 0, 'completed', '3.0.0'),
(6, 1, 'wolfie', 42, 'captain', @now, @now, 0, 'completed', '3.0.0');

-- ============================================================================
-- END OF ACTORS AND AGENTS SEEDING
-- ============================================================================
