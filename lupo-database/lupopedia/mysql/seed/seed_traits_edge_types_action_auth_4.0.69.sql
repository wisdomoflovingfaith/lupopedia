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
(6, 1, 'CAPABILITY_COMMUNICATION', 'primary', 1, 1, @now, @now, 0, NULL, NULL)
ON DUPLICATE KEY UPDATE
	actor_id = VALUES(actor_id),
	trait_key = VALUES(trait_key),
	trait_value = VALUES(trait_value),
	federation_node_id = VALUES(federation_node_id),
	created_by_actor_id = VALUES(created_by_actor_id),
	created_ymdhis = VALUES(created_ymdhis),
	updated_ymdhis = VALUES(updated_ymdhis),
	is_deleted = VALUES(is_deleted),
	deleted_ymdhis = VALUES(deleted_ymdhis),
	metadata = VALUES(metadata);

-- ============================================================================
-- lupo_edge_types: canonical edge slugs for channel/thread/artifact graph (4.0.87)
-- Idempotent upsert by primary key or unique slug.
-- ============================================================================
INSERT INTO lupo_edge_types (edge_type_id, slug, label, description, is_bidirectional, created_ymdhis, updated_ymdhis, is_deleted)
VALUES
	(1,  'channel_related',          'Channel Related',             'Channels that are semantically or operationally related. Bidirectional.', 1, @now, @now, 0),
	(2,  'channel_parent',           'Channel Parent',              'Formal hierarchical parent. Supplements parent_channel_id. Directional.', 0, @now, @now, 0),
	(3,  'channel_successor',        'Channel Successor',           'This channel continued as or was superseded by the target channel.', 0, @now, @now, 0),
	(4,  'channel_spawned_thread',   'Channel Spawned Thread',      'This channel originated or owns this thread.', 0, @now, @now, 0),
	(5,  'channel_references',       'Channel References',          'Channel cites or references another channel.', 0, @now, @now, 0),
	(6,  'thread_continuation',      'Thread Continuation',         'This thread continues conversation from the target thread.', 0, @now, @now, 0),
	(7,  'thread_spawned_from',      'Thread Spawned From',         'This thread was forked or branched from the target thread.', 0, @now, @now, 0),
	(8,  'thread_references',        'Thread References',           'This thread cites or references the target thread or channel.', 0, @now, @now, 0),
	(9,  'thread_crosses_channel',   'Thread Crosses Channel',      'Thread activity spans into or involves another channel.', 0, @now, @now, 0),
	(10, 'channel_sibling',          'Channel Sibling',             'Channels at the same level sharing a purpose or origin. Bidirectional.', 1, @now, @now, 0),
	(11, 'artifact_spawned_from',    'Artifact Spawned From',       'Artifact was produced from this thread or channel.', 0, @now, @now, 0),
	(12, 'channel_observes',         'Channel Observes',            'Channel has a monitoring or observation relationship to the target.', 0, @now, @now, 0)
ON DUPLICATE KEY UPDATE
	slug = VALUES(slug),
	label = VALUES(label),
	description = VALUES(description),
	is_bidirectional = VALUES(is_bidirectional),
	updated_ymdhis = VALUES(updated_ymdhis),
	is_deleted = VALUES(is_deleted);

-- ============================================================================
-- lupo_edge_type_definitions: type safety constraints for edge usage (4.0.87)
-- Idempotent upsert by primary key or unique edge_type.
-- ============================================================================
INSERT INTO lupo_edge_type_definitions
	(edge_type_definition_id, edge_type, domain, description, allowed_left_object_types, allowed_right_object_types, is_bidirectional, semantic_meaning, created_ymdhis, created_by_actor_id)
VALUES
	(1, 'channel_related',       'channel',  'Related channels', 'channel',                   'channel',          1, 'Captures semantic or operational relationship between channels', @now, @actor_1),
	(2, 'channel_parent',        'channel',  'Parent hierarchy',  'channel',                   'channel',          0, 'Formal parent; supplements parent_channel_id structural column', @now, @actor_1),
	(3, 'channel_successor',     'channel',  'Channel successor', 'channel',                   'channel',          0, 'Target channel succeeded or continued this channel', @now, @actor_1),
	(4, 'channel_spawned_thread','channel',  'Thread ownership',  'channel',                   'thread',           0, 'Channel originated this thread', @now, @actor_1),
	(5, 'channel_references',    'channel',  'Channel citation',  'channel',                   'channel',          0, 'One channel cites another', @now, @actor_1),
	(6, 'thread_continuation',   'thread',   'Thread lineage',    'thread',                    'thread',           0, 'This thread continues from target thread; replaces thread_lineage TEXT', @now, @actor_1),
	(7, 'thread_spawned_from',   'thread',   'Thread fork',       'thread',                    'thread',           0, 'This thread was forked or branched from target thread', @now, @actor_1),
	(8, 'thread_references',     'thread',   'Thread citation',   'thread',                    'thread,channel',   0, 'Thread cites or references another thread or channel', @now, @actor_1),
	(9, 'thread_crosses_channel','thread',   'Cross-channel',     'thread',                    'channel',          0, 'Thread involves or spans into another channel', @now, @actor_1),
	(10,'channel_sibling',       'channel',  'Channel siblings',  'channel',                   'channel',          1, 'Channels sharing purpose or origin at same level', @now, @actor_1),
	(11,'artifact_spawned_from', 'artifact', 'Artifact lineage',  'artifact',                  'thread,channel',   0, 'Artifact was produced from a thread or channel conversation', @now, @actor_1),
	(12,'channel_observes',      'channel',  'Observation edge',  'channel,actor',             'channel',          0, 'Actor or channel monitors/observes the target channel', @now, @actor_1)
ON DUPLICATE KEY UPDATE
	edge_type = VALUES(edge_type),
	domain = VALUES(domain),
	description = VALUES(description),
	allowed_left_object_types = VALUES(allowed_left_object_types),
	allowed_right_object_types = VALUES(allowed_right_object_types),
	is_bidirectional = VALUES(is_bidirectional),
	semantic_meaning = VALUES(semantic_meaning),
	created_ymdhis = VALUES(created_ymdhis),
	created_by_actor_id = VALUES(created_by_actor_id);

-- ============================================================================
-- lupo_action_authorization: core actions and required traits/roles
-- ============================================================================
INSERT INTO lupo_action_authorization (action_authorization_id, action_key, description, required_trait_keys, required_capabilities, required_role_keys, requires_all_conditions, created_ymdhis, created_by_actor_id)
VALUES
(1, 'dialog.send_message', 'Send message in channel', '["CAPABILITY_COMMUNICATION"]', NULL, '["member","operator","captain"]', 0, @now, @actor_1),
(2, 'channel.create', 'Create new channel', '["CAPABILITY_ORCHESTRATION"]', NULL, NULL, 0, @now, @actor_1),
(3, 'rules.modify', 'Modify system rules', '["CAPABILITY_GOVERNANCE"]', NULL, NULL, 0, @now, @actor_1),
(4, 'traits.assign', 'Assign traits to actors', '["CAPABILITY_ORCHESTRATION"]', NULL, NULL, 0, @now, @actor_1)
ON DUPLICATE KEY UPDATE
	action_key = VALUES(action_key),
	description = VALUES(description),
	required_trait_keys = VALUES(required_trait_keys),
	required_capabilities = VALUES(required_capabilities),
	required_role_keys = VALUES(required_role_keys),
	requires_all_conditions = VALUES(requires_all_conditions),
	created_ymdhis = VALUES(created_ymdhis),
	created_by_actor_id = VALUES(created_by_actor_id);
