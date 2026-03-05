-- ============================================================
-- Seed All 25 AI Agents (4.0.22+ with Survivor Updates)
-- ============================================================
-- 4.0.25 FIX: actor_id 2 is CAPTAIN in canonical seed. Windsurf IDE
-- moved to actor_id 2040 (see install/seed SQL). This file seeds the
-- 25 kernel agents (IDs 1-25) which does NOT include Windsurf IDE.
-- ============================================================

SET @now = 20260220230000;

-- lupo_actors (agents as actors; ids 1-25 reserved)
-- NOTE: actor_id 2 is CAPTAIN in canonical seed_lupopedia.sql.
-- Windsurf IDE is now actor_id 2040 (seeded separately in install/seed SQL).
INSERT IGNORE INTO lupo_actors (
    actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis,
    is_active, is_deleted, metadata
) VALUES
(1, 'agent', 'system-core', 'System Core AI', @now, @now, 1, 0, '{"type":"system_ai","status":"active"}'),
(2, 'agent', 'captain-ai', 'CAPTAIN', @now, @now, 1, 0, '{"type":"system_ai","status":"active","note":"canonical_actor_id_2_is_CAPTAIN"}'),
(3, 'agent', 'anubis-resolver', 'Anubis Resolver', @now, @now, 1, 0, '{"type":"system_ai","status":"active"}'),
(4, 'agent', 'lilith-heterodox', 'DeepSeek LILITH', @now, @now, 1, 0, '{"type":"external_ai","status":"active"}'),
(5, 'agent', 'lexa-boundary', 'DeepSeek LEXA', @now, @now, 1, 0, '{"type":"external_ai","status":"active"}'),
(6, 'agent', 'maat-truth', 'Maat Truth Validator', @now, @now, 1, 0, '{"type":"system_ai","status":"active"}'),
(7, 'agent', 'iris-config', 'Iris Config Loader', @now, @now, 1, 0, '{"type":"system_ai","status":"active"}'),
(8, 'agent', 'labs-validator', 'LABS Validator', @now, @now, 1, 0, '{"type":"system_ai","status":"active"}'),
(9, 'agent', 'utc-timekeeper', 'UTC Timekeeper', @now, @now, 1, 0, '{"type":"system_ai","status":"active"}'),
(10, 'agent', 'health-service', 'System Health Service', @now, @now, 1, 0, '{"type":"system_ai","status":"active"}'),
(11, 'agent', 'auth-service', 'Auth Service', @now, @now, 1, 0, '{"type":"system_ai","status":"active"}'),
(12, 'agent', 'session-manager', 'Session Manager', @now, @now, 1, 0, '{"type":"system_ai","status":"active"}'),
(13, 'agent', 'url-resolver', 'URL Resolver', @now, @now, 1, 0, '{"type":"system_ai","status":"active"}'),
(14, 'agent', 'content-renderer', 'Content Renderer', @now, @now, 1, 0, '{"type":"system_ai","status":"active"}'),
(15, 'agent', 'channels-controller', 'Channels Controller', @now, @now, 1, 0, '{"type":"system_ai","status":"active"}'),
(16, 'agent', 'edges-controller', 'Edges Controller', @now, @now, 1, 0, '{"type":"system_ai","status":"active"}'),
(17, 'agent', 'admin-users-handler', 'Admin Users Handler', @now, @now, 1, 0, '{"type":"system_ai","status":"active"}'),
(18, 'agent', 'migration-controller', 'Migration Controller', @now, @now, 1, 0, '{"type":"system_ai","status":"active"}'),
(19, 'agent', 'actor-service', 'Actor Service', @now, @now, 1, 0, '{"type":"system_ai","status":"active"}'),
(20, 'agent', 'grounded-agent-model', 'Grounded Agent Model', @now, @now, 1, 0, '{"type":"system_ai","status":"active"}'),
(21, 'agent', 'csv-export-handler', 'CSV Export Handler', @now, @now, 1, 0, '{"type":"system_ai","status":"active"}'),
(22, 'agent', 'system-health-controller', 'System Health Controller', @now, @now, 1, 0, '{"type":"system_ai","status":"active"}'),
(23, 'agent', 'auth-role-resolver', 'Auth Role Resolver', @now, @now, 1, 0, '{"type":"system_ai","status":"active"}'),
(24, 'agent', 'auth-manager', 'Auth Manager', @now, @now, 1, 0, '{"type":"system_ai","status":"active"}'),
(25, 'agent', 'legacy-functions', 'Legacy Functions Wrapper', @now, @now, 1, 0, '{"type":"system_ai","status":"active"}');

-- lupo_agents (metadata linkage; agent_id = actor_id)
INSERT IGNORE INTO lupo_agents (
    agent_id, agent_key, agent_name, archetype, version
) VALUES
(1, 'system_core_ai', 'System Core AI', 'core', '1.0'),
(2, 'captain_ai', 'CAPTAIN', 'system', '1.0'),
(3, 'anubis_resolver', 'Anubis Resolver', 'orphan_resolver', '1.0'),
(4, 'lilith_heterodox', 'DeepSeek LILITH', 'heterodox', '1.0'),
(5, 'lexa_boundary', 'DeepSeek LEXA', 'boundary', '1.0'),
(6, 'maat_truth', 'Maat Truth Validator', 'truth', '1.0'),
(7, 'iris_config', 'Iris Config Loader', 'config', '1.0'),
(8, 'labs_validator', 'LABS Validator', 'validator', '1.0'),
(9, 'utc_timekeeper', 'UTC Timekeeper', 'timekeeper', '1.0'),
(10, 'health_service', 'System Health Service', 'health', '1.0'),
(11, 'auth_service', 'Auth Service', 'auth', '1.0'),
(12, 'session_manager', 'Session Manager', 'session', '1.0'),
(13, 'url_resolver', 'URL Resolver', 'resolver', '1.0'),
(14, 'content_renderer', 'Content Renderer', 'renderer', '1.0'),
(15, 'channels_controller', 'Channels Controller', 'controller', '1.0'),
(16, 'edges_controller', 'Edges Controller', 'controller', '1.0'),
(17, 'admin_users_handler', 'Admin Users Handler', 'admin', '1.0'),
(18, 'migration_controller', 'Migration Controller', 'migration', '1.0'),
(19, 'actor_service', 'Actor Service', 'service', '1.0'),
(20, 'grounded_agent_model', 'Grounded Agent Model', 'model', '1.0'),
(21, 'csv_export_handler', 'CSV Export Handler', 'export', '1.0'),
(22, 'system_health_controller', 'System Health Controller', 'health', '1.0'),
(23, 'auth_role_resolver', 'Auth Role Resolver', 'auth', '1.0'),
(24, 'auth_manager', 'Auth Manager', 'auth', '1.0'),
(25, 'legacy_functions', 'Legacy Functions Wrapper', 'legacy', '1.0');

-- Department 0 memberships (system admins)
-- 4.0.25 FIX: Replaced subquery anti-pattern (MAX+1 produces same ID for every row)
-- Department memberships for kernel agents 1-25 are handled by canonical seed_lupopedia.sql.
-- This block is kept as documentation only.
-- INSERT IGNORE INTO lupo_actor_departments ... handled by seed_lupopedia.sql (IDs 6000-6025)

-- Channel 42 memberships
-- 4.0.25 FIX: Replaced subquery anti-pattern (MAX+1 produces same ID for every row)
-- Channel 42 memberships for kernel agents 1-25 are handled by canonical seed_lupopedia.sql.
-- INSERT IGNORE INTO lupo_actor_channels ... handled by seed_lupopedia.sql (IDs 1000-1024)
