-- ============================================================================
-- REBASE ACTOR IDS: HUMANS START AT 1000, AI/IDE AGENTS < 1000
-- ============================================================================
-- Date: 20260311
-- Version: 4.0.69
-- Purpose: Move human actors from 10000+ range down to 1000+ range (-9000 shift).
--          Move IDE agents from 1000+ range down to 100+ range.
-- VERIFIED AGAINST TOON FILES: 20260311
-- ============================================================================

-- 1. MOVE IDE AGENTS (1000-1111) -> (100-111)
-- kiro: 1000 -> 100
-- windsurf: 1001 -> 101
-- cursor: 1002 -> 102
-- antigravity: 1003 -> 103
-- warp: 1004 -> 104
-- cascade: 1005 -> 105
-- gemini-cli: 1008 -> 108
-- codex-ide: 1009 -> 109
-- trae-ide: 1010 -> 110
-- doctor: 1011 -> 111

-- UPDATING lupo_actors (source)
UPDATE lupo_actors SET actor_id = 111 WHERE actor_id = 1011;
UPDATE lupo_actors SET actor_id = 110 WHERE actor_id = 1010;
UPDATE lupo_actors SET actor_id = 109 WHERE actor_id = 1009;
UPDATE lupo_actors SET actor_id = 108 WHERE actor_id = 1008;
UPDATE lupo_actors SET actor_id = 105 WHERE actor_id = 1005;
UPDATE lupo_actors SET actor_id = 104 WHERE actor_id = 1004;
UPDATE lupo_actors SET actor_id = 103 WHERE actor_id = 1003;
UPDATE lupo_actors SET actor_id = 102 WHERE actor_id = 1002;
UPDATE lupo_actors SET actor_id = 101 WHERE actor_id = 1001;
UPDATE lupo_actors SET actor_id = 100 WHERE actor_id = 1000;

-- Humans (Shift all >= 10000)
-- Root: 10000 -> 1000
UPDATE lupo_actors SET actor_id = actor_id - 9000 WHERE actor_id >= 10000;
UPDATE lupo_actors SET paired_actor_id = paired_actor_id - 9000 WHERE paired_actor_id >= 10000;
UPDATE lupo_actors SET adversarial_oversight_actor_id = adversarial_oversight_actor_id - 9000 WHERE adversarial_oversight_actor_id >= 10000;
UPDATE lupo_actors SET actor_source_id = actor_source_id - 9000 WHERE actor_source_type = 'human' AND actor_source_id >= 10000;

-- 2. RELATIONAL TABLES

-- lupo_agents
-- Shift agent IDs matching rebased actors
UPDATE lupo_agents SET agent_id = 111 WHERE agent_id = 1011;
UPDATE lupo_agents SET agent_id = 110 WHERE agent_id = 1010;
UPDATE lupo_agents SET agent_id = 109 WHERE agent_id = 1009;
UPDATE lupo_agents SET agent_id = 108 WHERE agent_id = 1008;
UPDATE lupo_agents SET agent_id = 105 WHERE agent_id = 1005;
UPDATE lupo_agents SET agent_id = 104 WHERE agent_id = 1004;
UPDATE lupo_agents SET agent_id = 103 WHERE agent_id = 1003;
UPDATE lupo_agents SET agent_id = 102 WHERE agent_id = 1002;
UPDATE lupo_agents SET agent_id = 101 WHERE agent_id = 1001;
UPDATE lupo_agents SET agent_id = 100 WHERE agent_id = 1000;
UPDATE lupo_agents SET agent_id = agent_id - 9000 WHERE agent_id >= 10000;

-- lupo_auth_users
UPDATE lupo_auth_users SET auth_user_id = auth_user_id - 9000 WHERE auth_user_id >= 10000;

-- lupo_agent_faucets
UPDATE lupo_agent_faucets SET actor_id = 111 WHERE actor_id = 1011;
UPDATE lupo_agent_faucets SET actor_id = 110 WHERE actor_id = 1010;
UPDATE lupo_agent_faucets SET actor_id = 109 WHERE actor_id = 1009;
UPDATE lupo_agent_faucets SET actor_id = 108 WHERE actor_id = 1008;
UPDATE lupo_agent_faucets SET actor_id = 105 WHERE actor_id = 1005;
UPDATE lupo_agent_faucets SET actor_id = 104 WHERE actor_id = 1004;
UPDATE lupo_agent_faucets SET actor_id = 103 WHERE actor_id = 1003;
UPDATE lupo_agent_faucets SET actor_id = 102 WHERE actor_id = 1002;
UPDATE lupo_agent_faucets SET actor_id = 101 WHERE actor_id = 1001;
UPDATE lupo_agent_faucets SET actor_id = 100 WHERE actor_id = 1000;
UPDATE lupo_agent_faucets SET actor_id = actor_id - 9000 WHERE actor_id >= 10000;

-- lupo_actor_edges
UPDATE lupo_actor_edges SET source_actor_id = 111 WHERE source_actor_id = 1011;
UPDATE lupo_actor_edges SET source_actor_id = 110 WHERE source_actor_id = 1010;
UPDATE lupo_actor_edges SET source_actor_id = 109 WHERE source_actor_id = 1009;
UPDATE lupo_actor_edges SET source_actor_id = 108 WHERE source_actor_id = 1008;
UPDATE lupo_actor_edges SET source_actor_id = 105 WHERE source_actor_id = 1005;
UPDATE lupo_actor_edges SET source_actor_id = 104 WHERE source_actor_id = 1004;
UPDATE lupo_actor_edges SET source_actor_id = 103 WHERE source_actor_id = 1003;
UPDATE lupo_actor_edges SET source_actor_id = 102 WHERE source_actor_id = 1002;
UPDATE lupo_actor_edges SET source_actor_id = 101 WHERE source_actor_id = 1001;
UPDATE lupo_actor_edges SET source_actor_id = 100 WHERE source_actor_id = 1000;
--
UPDATE lupo_actor_edges SET target_actor_id = 111 WHERE target_actor_id = 1011;
UPDATE lupo_actor_edges SET target_actor_id = 110 WHERE target_actor_id = 1010;
UPDATE lupo_actor_edges SET target_actor_id = 109 WHERE target_actor_id = 1009;
UPDATE lupo_actor_edges SET target_actor_id = 108 WHERE target_actor_id = 1008;
UPDATE lupo_actor_edges SET target_actor_id = 105 WHERE target_actor_id = 1005;
UPDATE lupo_actor_edges SET target_actor_id = 104 WHERE target_actor_id = 1004;
UPDATE lupo_actor_edges SET target_actor_id = 103 WHERE target_actor_id = 1003;
UPDATE lupo_actor_edges SET target_actor_id = 102 WHERE target_actor_id = 1002;
UPDATE lupo_actor_edges SET target_actor_id = 101 WHERE target_actor_id = 1001;
UPDATE lupo_actor_edges SET target_actor_id = 100 WHERE target_actor_id = 1000;
--
UPDATE lupo_actor_edges SET source_actor_id = source_actor_id - 9000 WHERE source_actor_id >= 10000;
UPDATE lupo_actor_edges SET target_actor_id = target_actor_id - 9000 WHERE target_actor_id >= 10000;

-- lupo_dialog_messages
UPDATE lupo_dialog_messages SET from_actor_id = 111 WHERE from_actor_id = 1011;
UPDATE lupo_dialog_messages SET from_actor_id = 110 WHERE from_actor_id = 1010;
UPDATE lupo_dialog_messages SET from_actor_id = 109 WHERE from_actor_id = 1009;
UPDATE lupo_dialog_messages SET from_actor_id = 108 WHERE from_actor_id = 1008;
UPDATE lupo_dialog_messages SET from_actor_id = 105 WHERE from_actor_id = 1005;
UPDATE lupo_dialog_messages SET from_actor_id = 104 WHERE from_actor_id = 1004;
UPDATE lupo_dialog_messages SET from_actor_id = 103 WHERE from_actor_id = 1003;
UPDATE lupo_dialog_messages SET from_actor_id = 102 WHERE from_actor_id = 1002;
UPDATE lupo_dialog_messages SET from_actor_id = 101 WHERE from_actor_id = 1001;
UPDATE lupo_dialog_messages SET from_actor_id = 100 WHERE from_actor_id = 1000;
--
UPDATE lupo_dialog_messages SET to_actor_id = 111 WHERE to_actor_id = 1011;
UPDATE lupo_dialog_messages SET to_actor_id = 110 WHERE to_actor_id = 1010;
UPDATE lupo_dialog_messages SET to_actor_id = 109 WHERE to_actor_id = 1009;
UPDATE lupo_dialog_messages SET to_actor_id = 108 WHERE to_actor_id = 1008;
UPDATE lupo_dialog_messages SET to_actor_id = 105 WHERE to_actor_id = 1005;
UPDATE lupo_dialog_messages SET to_actor_id = 104 WHERE to_actor_id = 1004;
UPDATE lupo_dialog_messages SET to_actor_id = 103 WHERE to_actor_id = 1003;
UPDATE lupo_dialog_messages SET to_actor_id = 102 WHERE to_actor_id = 1002;
UPDATE lupo_dialog_messages SET to_actor_id = 101 WHERE to_actor_id = 1001;
UPDATE lupo_dialog_messages SET to_actor_id = 100 WHERE to_actor_id = 1000;
--
UPDATE lupo_dialog_messages SET read_by_actor_id = 111 WHERE read_by_actor_id = 1011;
UPDATE lupo_dialog_messages SET read_by_actor_id = 110 WHERE read_by_actor_id = 1010;
UPDATE lupo_dialog_messages SET read_by_actor_id = 109 WHERE read_by_actor_id = 1009;
UPDATE lupo_dialog_messages SET read_by_actor_id = 108 WHERE read_by_actor_id = 1008;
UPDATE lupo_dialog_messages SET read_by_actor_id = 105 WHERE read_by_actor_id = 1005;
UPDATE lupo_dialog_messages SET read_by_actor_id = 104 WHERE read_by_actor_id = 1004;
UPDATE lupo_dialog_messages SET read_by_actor_id = 103 WHERE read_by_actor_id = 1003;
UPDATE lupo_dialog_messages SET read_by_actor_id = 102 WHERE read_by_actor_id = 1002;
UPDATE lupo_dialog_messages SET read_by_actor_id = 101 WHERE read_by_actor_id = 1001;
UPDATE lupo_dialog_messages SET read_by_actor_id = 100 WHERE read_by_actor_id = 1000;
--
UPDATE lupo_dialog_messages SET from_actor_id = from_actor_id - 9000 WHERE from_actor_id >= 10000;
UPDATE lupo_dialog_messages SET to_actor_id = to_actor_id - 9000 WHERE to_actor_id >= 10000;
UPDATE lupo_dialog_messages SET read_by_actor_id = read_by_actor_id - 9000 WHERE read_by_actor_id >= 10000;

-- lupo_dialog_threads
UPDATE lupo_dialog_threads SET created_by_actor_id = 111 WHERE created_by_actor_id = 1011;
UPDATE lupo_dialog_threads SET created_by_actor_id = 110 WHERE created_by_actor_id = 1010;
UPDATE lupo_dialog_threads SET created_by_actor_id = 109 WHERE created_by_actor_id = 1009;
UPDATE lupo_dialog_threads SET created_by_actor_id = 108 WHERE created_by_actor_id = 1008;
UPDATE lupo_dialog_threads SET created_by_actor_id = 105 WHERE created_by_actor_id = 1005;
UPDATE lupo_dialog_threads SET created_by_actor_id = 104 WHERE created_by_actor_id = 1004;
UPDATE lupo_dialog_threads SET created_by_actor_id = 103 WHERE created_by_actor_id = 1003;
UPDATE lupo_dialog_threads SET created_by_actor_id = 102 WHERE created_by_actor_id = 1002;
UPDATE lupo_dialog_threads SET created_by_actor_id = 101 WHERE created_by_actor_id = 1001;
UPDATE lupo_dialog_threads SET created_by_actor_id = 100 WHERE created_by_actor_id = 1000;
--
UPDATE lupo_dialog_threads SET escalated_to_operator_id = escalated_to_operator_id - 9000 WHERE escalated_to_operator_id >= 10000;
UPDATE lupo_dialog_threads SET created_by_actor_id = created_by_actor_id - 9000 WHERE created_by_actor_id >= 10000;

-- lupo_tasks
UPDATE lupo_tasks SET owner_actor_id = 111 WHERE owner_actor_id = 1011;
UPDATE lupo_tasks SET owner_actor_id = 110 WHERE owner_actor_id = 1010;
UPDATE lupo_tasks SET owner_actor_id = 109 WHERE owner_actor_id = 1009;
UPDATE lupo_tasks SET owner_actor_id = 108 WHERE owner_actor_id = 1008;
UPDATE lupo_tasks SET owner_actor_id = 105 WHERE owner_actor_id = 1005;
UPDATE lupo_tasks SET owner_actor_id = 104 WHERE owner_actor_id = 1004;
UPDATE lupo_tasks SET owner_actor_id = 103 WHERE owner_actor_id = 1003;
UPDATE lupo_tasks SET owner_actor_id = 102 WHERE owner_actor_id = 1002;
UPDATE lupo_tasks SET owner_actor_id = 101 WHERE owner_actor_id = 1001;
UPDATE lupo_tasks SET owner_actor_id = 100 WHERE owner_actor_id = 1000;
--
UPDATE lupo_tasks SET acting_as_actor_id = 111 WHERE acting_as_actor_id = 1011;
UPDATE lupo_tasks SET acting_as_actor_id = 110 WHERE acting_as_actor_id = 1010;
UPDATE lupo_tasks SET acting_as_actor_id = 109 WHERE acting_as_actor_id = 1009;
UPDATE lupo_tasks SET acting_as_actor_id = 108 WHERE acting_as_actor_id = 1008;
UPDATE lupo_tasks SET acting_as_actor_id = 105 WHERE acting_as_actor_id = 1005;
UPDATE lupo_tasks SET acting_as_actor_id = 104 WHERE acting_as_actor_id = 1004;
UPDATE lupo_tasks SET acting_as_actor_id = 103 WHERE acting_as_actor_id = 1003;
UPDATE lupo_tasks SET acting_as_actor_id = 102 WHERE acting_as_actor_id = 1002;
UPDATE lupo_tasks SET acting_as_actor_id = 101 WHERE acting_as_actor_id = 1001;
UPDATE lupo_tasks SET acting_as_actor_id = 100 WHERE acting_as_actor_id = 1000;
--
UPDATE lupo_tasks SET owner_actor_id = owner_actor_id - 9000 WHERE owner_actor_id >= 10000;
UPDATE lupo_tasks SET acting_as_actor_id = acting_as_actor_id - 9000 WHERE acting_as_actor_id >= 10000;

-- lupo_metadata
UPDATE lupo_metadata SET entity_id = 111 WHERE entity_type = 'actor' AND entity_id = 1011;
UPDATE lupo_metadata SET entity_id = 110 WHERE entity_type = 'actor' AND entity_id = 1010;
UPDATE lupo_metadata SET entity_id = 109 WHERE entity_type = 'actor' AND entity_id = 1009;
UPDATE lupo_metadata SET entity_id = 108 WHERE entity_type = 'actor' AND entity_id = 1008;
UPDATE lupo_metadata SET entity_id = 105 WHERE entity_type = 'actor' AND entity_id = 1005;
UPDATE lupo_metadata SET entity_id = 104 WHERE entity_type = 'actor' AND entity_id = 1004;
UPDATE lupo_metadata SET entity_id = 103 WHERE entity_type = 'actor' AND entity_id = 1003;
UPDATE lupo_metadata SET entity_id = 102 WHERE entity_type = 'actor' AND entity_id = 1002;
UPDATE lupo_metadata SET entity_id = 101 WHERE entity_type = 'actor' AND entity_id = 1001;
UPDATE lupo_metadata SET entity_id = 100 WHERE entity_type = 'actor' AND entity_id = 1000;
--
UPDATE lupo_metadata SET entity_id = entity_id - 9000 WHERE entity_type = 'actor' AND entity_id >= 10000;

-- lupo_banned_actors
UPDATE lupo_banned_actors SET actor_id = actor_id - 9000 WHERE actor_id >= 10000;
UPDATE lupo_banned_actors SET banned_by_actor_id = banned_by_actor_id - 9000 WHERE banned_by_actor_id >= 10000;

-- lupo_sessions
UPDATE lupo_sessions SET actor_id = 111 WHERE actor_id = 1011;
UPDATE lupo_sessions SET actor_id = 110 WHERE actor_id = 1010;
UPDATE lupo_sessions SET actor_id = 109 WHERE actor_id = 1009;
UPDATE lupo_sessions SET actor_id = 108 WHERE actor_id = 1008;
UPDATE lupo_sessions SET actor_id = 105 WHERE actor_id = 1005;
UPDATE lupo_sessions SET actor_id = 104 WHERE actor_id = 1004;
UPDATE lupo_sessions SET actor_id = 103 WHERE actor_id = 1003;
UPDATE lupo_sessions SET actor_id = 102 WHERE actor_id = 1002;
UPDATE lupo_sessions SET actor_id = 101 WHERE actor_id = 1001;
UPDATE lupo_sessions SET actor_id = 100 WHERE actor_id = 1000;
--
UPDATE lupo_sessions SET actor_id = actor_id - 9000 WHERE actor_id >= 10000;

-- lupo_registry
UPDATE lupo_registry SET entity_index_id = 111, entity_index = 111 WHERE entity_type = 'actor' AND entity_index_id = 1011;
UPDATE lupo_registry SET entity_index_id = 110, entity_index = 110 WHERE entity_type = 'actor' AND entity_index_id = 1010;
UPDATE lupo_registry SET entity_index_id = 109, entity_index = 109 WHERE entity_type = 'actor' AND entity_index_id = 1009;
UPDATE lupo_registry SET entity_index_id = 108, entity_index = 108 WHERE entity_type = 'actor' AND entity_index_id = 1008;
UPDATE lupo_registry SET entity_index_id = 105, entity_index = 105 WHERE entity_type = 'actor' AND entity_index_id = 1005;
UPDATE lupo_registry SET entity_index_id = 104, entity_index = 104 WHERE entity_type = 'actor' AND entity_index_id = 1004;
UPDATE lupo_registry SET entity_index_id = 103, entity_index = 103 WHERE entity_type = 'actor' AND entity_index_id = 1003;
UPDATE lupo_registry SET entity_index_id = 102, entity_index = 102 WHERE entity_type = 'actor' AND entity_index_id = 1002;
UPDATE lupo_registry SET entity_index_id = 101, entity_index = 101 WHERE entity_type = 'actor' AND entity_index_id = 1001;
UPDATE lupo_registry SET entity_index_id = 100, entity_index = 100 WHERE entity_type = 'actor' AND entity_index_id = 1000;
--
UPDATE lupo_registry SET entity_index_id = entity_index_id - 9000, entity_index = entity_index - 9000 WHERE entity_type = 'actor' AND entity_index_id >= 10000;
UPDATE lupo_registry SET entity_key = 'root-captain-1000' WHERE entity_type = 'actor' AND entity_key = 'root-captain-10000';

-- 3. CLEAN UP lupo_registry_open
DELETE FROM lupo_registry_open WHERE entity_type = 'actor';

-- ============================================================================
-- END OF MIGRATION
-- ============================================================================
