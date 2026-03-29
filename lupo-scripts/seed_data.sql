-- Lupopedia Seed Data for Fresh Install
-- Timestamp Format: YYYYMMDDHHIISS (BIGINT)
-- All timestamps use UTC time

-- ========================================
-- REQUIRED ACTORS (13 Primary Coordination Personas)
-- ========================================

-- Seed actors with YYYYMMDDHHIISS timestamps
INSERT INTO lupo_actors (actor_id, actor_name, slug, name, actor_type, is_agent, created_ymdhis, updated_ymdhis) VALUES
(1, 'WOLFIE', 'wolfie', 'WOLFIE', 'system', 1, 20260328120000, 20260328120000),
(2, 'LILITH', 'lilith', 'LILITH', 'system', 1, 20260328120000, 20260328120000),
(3, 'ROSE', 'rose', 'ROSE', 'system', 1, 20260328120000, 20260328120000),
(4, 'ATHENA', 'athena', 'ATHENA', 'system', 1, 20260328120000, 20260328120000),
(5, 'LEXA', 'lexa', 'LEXA', 'system', 1, 20260328120000, 20260328120000),
(6, 'ANUBIS', 'anubis', 'ANUBIS', 'system', 1, 20260328120000, 20260328120000),
(7, 'MAAT', 'maat', 'MAAT', 'system', 1, 20260328120000, 20260328120000),
(8, 'HEIMDALL', 'heimdall', 'HEIMDALL', 'system', 1, 20260328120000, 20260328120000),
(9, 'THEMIS', 'themis', 'THEMIS', 'system', 1, 20260328120000, 20260328120000),
(10, 'SESHAT', 'seshat', 'SESHAT', 'system', 1, 20260328120000, 20260328120000),
(11, 'THOTH', 'thoth', 'THOTH', 'system', 1, 20260328120000, 20260328120000),
(12, 'JANUS', 'janus', 'JANUS', 'system', 1, 20260328120000, 20260328120000),
(14, 'HEPHAESTUS', 'hephaestus', 'HEPHAESTUS', 'system', 1, 20260328120000, 20260328120000);

-- ========================================
-- REQUIRED AGENTS (for Actor Selection)
-- ========================================

-- Seed agents with YYYYMMDDHHIISS timestamps
INSERT INTO lupo_agents (agent_id, agent_key, agent_name, description, created_ymdhis, updated_ymdhis) VALUES
(1, 'wolfie', 'WOLFIE', 'Orchestrator - strategic planning, delegation, enforcement', 20260328120000, 20260328120000),
(2, 'lilith', 'LILITH', 'Critic - non-interfering reviewer, contradiction detection', 20260328120000, 20260328120000),
(3, 'rose', 'ROSE', 'Emotional dialogue - context, stakeholder needs, human factors', 20260328120000, 20260328120000),
(4, 'athena', 'ATHENA', 'Wisdom & strategy - strategic analysis, architectural guidance', 20260328120000, 20260328120000),
(5, 'lexa', 'LEXA', 'Security enforcement - boundary enforcement, policy compliance', 20260328120000, 20260328120000),
(6, 'anubis', 'ANUBIS', 'Custodian - data integrity, lineage, custody audit', 20260328120000, 20260328120000),
(7, 'maat', 'MAAT', 'Truth & justice - conflict resolution, fairness, accountability', 20260328120000, 20260328120000),
(8, 'heimdall', 'HEIMDALL', 'Security guardian - access control, perimeter defense', 20260328120000, 20260328120000),
(9, 'themis', 'THEMIS', 'Law & compliance - regulatory compliance, binding rules', 20260328120000, 20260328120000),
(10, 'seshat', 'SESHAT', 'Content review - content quality, documentation accuracy', 20260328120000, 20260328120000),
(11, 'thoth', 'THOTH', 'Knowledge & records - documentation, record-keeping, provenance', 20260328120000, 20260328120000),
(12, 'janus', 'JANUS', 'Transitions & gateways - state transitions, boundary management', 20260328120000, 20260328120000),
(14, 'hephaestus', 'HEPHAESTUS', 'Implementer - code, docs, schema execution', 20260328120000, 20260328120000);

-- ========================================
-- REQUIRED AUTH USERS (Root User)
-- ========================================

-- Seed auth users with YYYYMMDDHHIISS timestamps
INSERT INTO lupo_auth_users (auth_user_id, username, email, display_name, created_ymdhis, updated_ymdhis) VALUES
(1000, 'root', 'wisdomoflovingfaith@gmail.com', 'root', 20260328120000, 20260328120000);

-- ========================================
-- VERIFICATION QUERIES
-- ========================================

-- Verify all actors are present
SELECT COUNT(*) as actors_count FROM lupo_actors WHERE actor_type = 'system' AND is_agent = 1;
-- Expected: 13

-- Verify all agents are present
SELECT COUNT(*) as agents_count FROM lupo_agents WHERE is_deleted = 0;
-- Expected: 13

-- Verify root user is present
SELECT COUNT(*) as auth_users_count FROM lupo_auth_users WHERE auth_user_id = 1000;
-- Expected: 1

-- Verify specific critical actors
SELECT actor_id, actor_name, slug FROM lupo_actors WHERE actor_id IN (1, 2, 3, 14) ORDER BY actor_id;
-- Expected: WOLFIE, LILITH, ROSE, HEPHAESTUS
