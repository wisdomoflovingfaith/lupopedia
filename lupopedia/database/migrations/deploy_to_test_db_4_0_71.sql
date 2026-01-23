-- Deploy Multi-Agent Protocol Schema to test_lupopedia_v4_0_71
-- Integration Testing Phase - Version 4.0.71
-- Database: test_lupopedia_v4_0_71
-- Purpose: Deploy AAL + RSHAP + CJP protocol extensions for testing

USE `test_lupopedia_v4_0_71`;

-- ============================================================================
-- DEPLOY MULTI-AGENT PROTOCOL SCHEMA EXTENSIONS
-- ============================================================================

-- Extend lupo_channels for AAL metadata
ALTER TABLE `lupo_channels` 
ADD COLUMN IF NOT EXISTS `aal_metadata_json` JSON DEFAULT NULL COMMENT 'Agent Awareness Layer metadata for WHO/WHAT/WHERE/WHEN/WHY/HOW/PURPOSE',
ADD COLUMN IF NOT EXISTS `fleet_composition_json` JSON DEFAULT NULL COMMENT 'Current fleet of agents in this channel with their roles',
ADD COLUMN IF NOT EXISTS `awareness_version` VARCHAR(20) DEFAULT '4.0.71' COMMENT 'AAL protocol version for this channel';

-- Extend lupo_actor_channel_roles for RSHAP and CJP
ALTER TABLE `lupo_actor_channel_roles`
ADD COLUMN IF NOT EXISTS `handshake_metadata_json` JSON DEFAULT NULL COMMENT 'RSHAP handshake identity and synchronization data',
ADD COLUMN IF NOT EXISTS `awareness_snapshot_json` JSON DEFAULT NULL COMMENT 'CJP Awareness Snapshot (WHO/WHAT/WHERE/WHEN/WHY/HOW/PURPOSE)',
ADD COLUMN IF NOT EXISTS `protocol_completion_status` ENUM('pending', 'aal_complete', 'rshap_complete', 'cjp_complete', 'ready') DEFAULT 'pending' COMMENT 'Multi-agent protocol completion status',
ADD COLUMN IF NOT EXISTS `protocol_version` VARCHAR(20) DEFAULT '4.0.71' COMMENT 'Protocol version used for this actor-channel relationship',
ADD COLUMN IF NOT EXISTS `join_sequence_step` TINYINT DEFAULT 0 COMMENT 'Current step in 10-step CJP sequence (0-10)',
ADD COLUMN IF NOT EXISTS `handshake_completed_ymdhis` BIGINT DEFAULT NULL COMMENT 'Timestamp when RSHAP was completed',
ADD COLUMN IF NOT EXISTS `awareness_completed_ymdhis` BIGINT DEFAULT NULL COMMENT 'Timestamp when AAL was completed',
ADD COLUMN IF NOT EXISTS `cjp_completed_ymdhis` BIGINT DEFAULT NULL COMMENT 'Timestamp when full CJP was completed';

-- Extend lupo_actor_collections for persistent identity
ALTER TABLE `lupo_actor_collections`
ADD COLUMN IF NOT EXISTS `persistent_identity_json` JSON DEFAULT NULL COMMENT 'RSHAP persistent identity metadata',
ADD COLUMN IF NOT EXISTS `identity_signature` VARCHAR(255) DEFAULT NULL COMMENT 'Unique identity signature for handshake verification',
ADD COLUMN IF NOT EXISTS `trust_level` ENUM('system', 'verified', 'standard', 'restricted', 'untrusted') DEFAULT 'standard' COMMENT 'Trust level for multi-agent interactions',
ADD COLUMN IF NOT EXISTS `emotional_geometry_baseline` JSON DEFAULT NULL COMMENT 'Baseline emotional geometry for agent interactions',
ADD COLUMN IF NOT EXISTS `doctrine_alignment_version` VARCHAR(20) DEFAULT '4.0.71' COMMENT 'Version of doctrine this actor aligns with';

-- ============================================================================
-- CREATE TESTING-SPECIFIC INDEXES
-- ============================================================================

-- Indexes for protocol status queries
CREATE INDEX IF NOT EXISTS `idx_protocol_completion_status_test` ON `lupo_actor_channel_roles` (`protocol_completion_status`);
CREATE INDEX IF NOT EXISTS `idx_join_sequence_step_test` ON `lupo_actor_channel_roles` (`join_sequence_step`);
CREATE INDEX IF NOT EXISTS `idx_protocol_version_test` ON `lupo_actor_channel_roles` (`protocol_version`);

-- Indexes for identity lookups
CREATE INDEX IF NOT EXISTS `idx_identity_signature_test` ON `lupo_actor_collections` (`identity_signature`);
CREATE INDEX IF NOT EXISTS `idx_trust_level_test` ON `lupo_actor_collections` (`trust_level`);

-- Indexes for channel awareness
CREATE INDEX IF NOT EXISTS `idx_awareness_version_test` ON `lupo_channels` (`awareness_version`);

-- ============================================================================
-- CREATE TESTING DATA POPULATION
-- ============================================================================

-- Insert test channel for integration testing
INSERT IGNORE INTO `lupo_channels` (
    `channel_id`, `federation_node_id`, `created_by_actor_id`, `default_actor_id`,
    `channel_key`, `channel_name`, `description`, `metadata_json`,
    `aal_metadata_json`, `fleet_composition_json`, `awareness_version`,
    `bgcolor`, `status_flag`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`
) VALUES (
    999, 1, 0, 1,
    'test/integration-testing', 'Integration Testing Channel', 
    'Channel for testing AAL+RSHAP+CJP protocol integration',
    '{"purpose": "integration_testing", "test_environment": true}',
    '{"testing_phase": "4.0.71", "validation_scope": ["AAL", "RSHAP", "CJP"]}',
    '{"total_agents": 0, "active_protocols": [], "test_mode": true}',
    '4.0.71',
    'FFFF00', 1, 20260117031200, 20260117031200, 0
);

-- Insert test actors for multi-agent testing
INSERT IGNORE INTO `lupo_actors` (
    `actor_id`, `actor_type`, `slug`, `name`, `metadata`,
    `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`
) VALUES 
(100, 'ai_agent', 'test-agent-kiro', 'Test Agent KIRO', 
 '{"test_agent": true, "protocol_version": "4.0.71", "testing_role": "coordinator"}',
 20260117031200, 20260117031200, 1, 0),
(101, 'ai_agent', 'test-agent-cascade', 'Test Agent CASCADE', 
 '{"test_agent": true, "protocol_version": "4.0.71", "testing_role": "validator"}',
 20260117031200, 20260117031200, 1, 0),
(102, 'ai_agent', 'test-agent-windsuf', 'Test Agent windsuf', 
 '{"test_agent": true, "protocol_version": "4.0.71", "testing_role": "integrator"}',
 20260117031200, 20260117031200, 1, 0);

-- ============================================================================
-- TESTING VALIDATION QUERIES
-- ============================================================================

-- Verify schema deployment
SELECT 'Schema Deployment Verification' as test_name;
SELECT COUNT(*) as channels_with_aal FROM lupo_channels WHERE aal_metadata_json IS NOT NULL;
SELECT COUNT(*) as test_actors FROM lupo_actors WHERE actor_id >= 100;

-- Test protocol status tracking
SELECT 'Protocol Status Tracking Test' as test_name;
SELECT protocol_completion_status, COUNT(*) as count 
FROM lupo_actor_channel_roles 
GROUP BY protocol_completion_status;

-- Verify TOON file alignment
SELECT 'TOON File Alignment Verification' as test_name;
DESCRIBE lupo_actor_channel_roles;
DESCRIBE lupo_actor_collections;
DESCRIBE lupo_channels;

-- ============================================================================
-- TESTING PHASE READY
-- ============================================================================
SELECT 'Integration Testing Database Ready' as status, 
       'test_lupopedia_v4_0_71' as database_name,
       '4.0.71' as version,
       NOW() as deployment_timestamp;