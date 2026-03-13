-- Seed kernel actor traits, core edge type definitions, and core action authorizations (4.0.73).
-- LILITH implementation prompt; single install path: run after install_new_lupopedia.sql.
-- Explicit IDs (no AUTO_INCREMENT); BIGINT timestamps. Use LUPO_TABLE_PREFIX (default lupo_).

SET @now = 20260312000000;
SET @actor_1 = 1;

-- ============================================================================
-- lupo_actor_traits: kernel actor traits (federation_node_id, created_by_actor_id from install)
-- ============================================================================
INSERT INTO lupo_actor_traits (actor_trait_id, actor_id, trait_key, trait_value, federation_node_id, created_by_actor_id, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, metadata)
VALUES
(1, 1, 'CAPABILITY_ORCHESTRATION', 'primary', 1, 1, @now, @now, 0, NULL, NULL),
(2, 2, 'CAPABILITY_EDGE_EXPLORATION', 'kernel', 1, 1, @now, @now, 0, NULL, NULL),
(3, 3, 'CAPABILITY_TRUTH_ALIGNMENT', 'kernel', 1, 1, @now, @now, 0, NULL, NULL),
(4, 5, 'CAPABILITY_TIMEKEEPING', 'primary', 1, 1, @now, @now, 0, NULL, NULL),
(5, 103, 'CAPABILITY_SESSION_CUSTODIAN', 'governance', 1, 1, @now, @now, 0, NULL, NULL),
(6, 1, 'CAPABILITY_COMMUNICATION', 'primary', 1, 1, @now, @now, 0, NULL, NULL);

-- ============================================================================
-- lupo_edge_type_definitions: core edge types for semantic/graph validation
-- ============================================================================
INSERT INTO lupo_edge_type_definitions (edge_type_definition_id, edge_type, domain, description, allowed_left_object_types, allowed_right_object_types, is_bidirectional, semantic_meaning, created_ymdhis, created_by_actor_id)
VALUES
(1, 'REFERENCES', 'semantic', 'Left references right as a source', '["content","actor"]', '["content","actor","channel"]', 0, 'Source attribution: left cites right as reference', @now, @actor_1),
(2, 'HAS_CONTENT', 'structural', 'Channel contains content', '["channel"]', '["content"]', 0, 'Channel-content relationship', @now, @actor_1),
(3, 'HAS_MEMBER', 'structural', 'Channel has actor member', '["channel"]', '["actor"]', 0, 'Channel membership', @now, @actor_1),
(4, 'DELEGATES_TO', 'governance', 'Actor delegates authority to another actor', '["actor"]', '["actor"]', 0, 'Delegation chain', @now, @actor_1),
(5, 'MENTIONS', 'semantic', 'Content mentions actor or other content', '["content","message"]', '["actor","content"]', 0, 'Referential mention', @now, @actor_1);

-- ============================================================================
-- lupo_action_authorization: core actions and required traits/roles
-- ============================================================================
INSERT INTO lupo_action_authorization (action_authorization_id, action_key, description, required_trait_keys, required_capabilities, required_role_keys, requires_all_conditions, created_ymdhis, created_by_actor_id)
VALUES
(1, 'dialog.send_message', 'Send message in channel', '["CAPABILITY_COMMUNICATION"]', NULL, '["member","operator","captain"]', 0, @now, @actor_1),
(2, 'channel.create', 'Create new channel', '["CAPABILITY_ORCHESTRATION"]', NULL, NULL, 0, @now, @actor_1),
(3, 'rules.modify', 'Modify system rules', '["CAPABILITY_GOVERNANCE"]', NULL, NULL, 0, @now, @actor_1),
(4, 'traits.assign', 'Assign traits to actors', '["CAPABILITY_ORCHESTRATION"]', NULL, NULL, 0, @now, @actor_1);
