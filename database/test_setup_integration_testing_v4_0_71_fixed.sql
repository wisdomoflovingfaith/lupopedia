-- Test Environment Setup for Integration Testing v4.0.71 (Fixed)
-- Creates test database and loads base schema + v4.0.70 migration + v4.0.71 test infrastructure

-- Drop existing test database if it exists
DROP DATABASE IF EXISTS test_lupopedia_v4_0_71;

-- Create test database
CREATE DATABASE test_lupopedia_v4_0_71;
USE test_lupopedia_v4_0_71;

-- Load base schema (v4.0.70)
-- Note: Use backticks for path in MySQL
SOURCE `c:/ServBay/www/servbay/lupopedia/database/install/lupopedia_mysql.sql`;

-- Apply v4.0.70 Agent Awareness Layer migration
SOURCE `c:/ServBay/www/servbay/lupopedia/database/migrations/agent_awareness_layer_4_0_70.sql`;

-- Apply v4.0.71 integration testing infrastructure
SOURCE `c:/ServBay/www/servbay/lupopedia/database/migrations/4.0.71.sql`;

-- Verify test environment setup
SELECT 'Test Environment Setup Complete' as status;

-- Test data population for integration testing scenarios
-- Test agents for coordination scenarios
INSERT INTO lupo_actors (actor_id, actor_type, slug, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, name) VALUES
(1001, 'ai_agent', 'test_agent_1', 20260117000000, 20260117000000, 0, NULL, 'Test Agent 1'),
(1002, 'ai_agent', 'test_agent_2', 20260117000000, 20260117000000, 0, NULL, 'Test Agent 2'),
(1003, 'ai_agent', 'test_agent_3', 20260117000000, 20260117000000, 0, NULL, 'Test Agent 3'),
(1004, 'ai_agent', 'test_coordinator', 20260117000000, 20260117000000, 0, NULL, 'Test Coordinator'),
(1005, 'ai_agent', 'test_validator', 20260117000000, 20260117000000, 0, NULL, 'Test Validator');

-- Test channels for integration testing
INSERT INTO lupo_channels (channel_id, federation_node_id, created_by_actor_id, default_actor_id, channel_key, channel_name, description, metadata_json, bgcolor, status_flag, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis) VALUES
(2001, 1, 1001, 1001, 'test_integration_channel', 'Integration Test Channel', 'Test channel for validating AAL interactions', '{"purpose": "integration_testing", "doctrine": {"version": "4.0.71", "test_mode": true}, "emotional_geometry": {"baseline_mood": "neutral", "test_scenario": "integration"}}', 'FFFFFF', 1, 20260117000000, 20260117000000, 0, NULL),
(2002, 1, 1001, 1001, 'test_fleet_channel', 'Fleet Coordination Test Channel', 'Test channel for multi-agent fleet operations', '{"purpose": "fleet_testing", "doctrine": {"version": "4.0.71", "test_mode": true}, "emotional_geometry": {"baseline_mood": "neutral", "test_scenario": "fleet"}}', '00FFFF', 1, 20260117000000, 20260117000000, 0, NULL);

-- Test actor-channel relationships for awareness testing
INSERT INTO lupo_actor_channel_roles (actor_channel_role_id, actor_id, channel_id, role, metadata_json, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis) VALUES
(1, 1001, 2001, 'participant', '{"awareness_snapshot": {"who": "Test Agent 1", "what": "AI Agent for integration testing", "where": "Integration Test Channel", "when": "2026-01-17", "why": "Part of v4.0.71 integration testing framework", "how": "Following test blueprint procedures", "purpose": "Validate AAL implementation"}}', 20260117000000, 20260117000000, 0, NULL),
(2, 1002, 2001, 'participant', '{"awareness_snapshot": {"who": "Test Agent 2", "what": "AI Agent for integration testing", "where": "Integration Test Channel", "when": "2026-01-17", "why": "Part of v4.0.71 integration testing framework", "how": "Following test blueprint procedures", "purpose": "Validate AAL implementation"}}', 20260117000000, 20260117000000, 0, NULL),
(3, 1003, 2001, 'participant', '{"awareness_snapshot": {"who": "Test Agent 3", "what": "AI Agent for integration testing", "where": "Integration Test Channel", "when": "2026-01-17", "why": "Part of v4.0.71 integration testing framework", "how": "Following test blueprint procedures", "purpose": "Validate AAL implementation"}}', 20260117000000, 20260117000000, 0, NULL),
(4, 1004, 2001, 'coordinator', '{"awareness_snapshot": {"who": "Test Coordinator", "what": "AI Agent for coordinating integration tests", "where": "Integration Test Channel", "when": "2026-01-17", "why": "Lead integration testing coordinator", "how": "Orchestrate test execution and validate results", "purpose": "Coordinate multi-agent testing scenarios"}}', 20260117000000, 20260117000000, 0, NULL),
(5, 1005, 2001, 'validator', '{"awareness_snapshot": {"who": "Test Validator", "what": "AI Agent for validating integration test results", "where": "Integration Test Channel", "when": "2026-01-17", "why": "Validate integration test success criteria", "how": "Check test results against success metrics", "purpose": "Ensure integration testing meets quality standards"}}', 20260117000000, 20260117000000, 0, NULL);

-- Test collections for persistent identity storage
INSERT INTO lupo_actor_collections (actor_collection_id, actor_id, collection_id, metadata_json, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis) VALUES
(1, 1001, 1, 'test_identity_collection', '{"handshake_identity": {"identity_signature": "test_agent_1_v4_0.71", "trust_level": "verified", "emotional_geometry_baseline": {"baseline_mood": "neutral", "test_scenario": "integration"}}', 20260117000000, 20260117000000, 0, NULL),
(2, 1002, 2, 'test_fleet_collection', '{"fleet_membership": {"agents": [1001, 1002, 1003], "coordination_state": "active", "test_scenario": "fleet"}}', 20260117000000, 20260117000000, 0, NULL);

-- Performance monitoring setup
INSERT INTO test_performance_metrics (test_category, test_name, execution_time_ms, memory_usage_mb, cpu_usage_percent, success_rate, error_count, created_ymdhis) VALUES
('environment_setup', 'test_database_creation', 500, 64.5, 15.2, 100.0, 0, 20260117000000);

SELECT 'Test Environment Setup Complete' as status;
