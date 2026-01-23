-- Lupopedia v4.0.70 - Agent Awareness Layer Migration
-- Adds metadata_json fields for multi-agent coordination

-- Migration: 20260117000000
-- Version: 4.0.70
-- Purpose: Implement Agent Awareness Layer (AAL) with Reverse Shaka Protocol

-- Add metadata_json to lupo_actor_channel_roles for awareness snapshots
ALTER TABLE lupo_actor_channel_roles 
ADD COLUMN metadata_json TEXT COMMENT 'Agent awareness snapshot and handshake metadata for v4.0.70 AAL';

-- Add metadata_json to lupo_actor_collections for persistent identity
ALTER TABLE lupo_actor_collections
ADD COLUMN metadata_json TEXT COMMENT 'Persistent actor identity and handshake metadata for v4.0.70 AAL';

-- Create indexes for awareness layer performance
CREATE INDEX idx_actor_channel_awareness ON lupo_actor_channel_roles(actor_id, channel_id);
CREATE INDEX idx_actor_collections_identity ON lupo_actor_collections(actor_id, collection_id);

-- Update existing channels with v4.0.70 awareness metadata
UPDATE lupo_channels 
SET metadata_json = JSON_SET(
    COALESCE(metadata_json, '{}'),
    '$.doctrine', 
    JSON_OBJECT(
        'version', '4.0.70',
        'constraints', JSON_ARRAY('reverse_shaka', 'channel_join'),
        'protocols', JSON_ARRAY('reverse_shaka', 'channel_join', 'awareness_snapshot')
    ),
    '$.emotional_geometry',
    JSON_OBJECT(
        'baseline_mood', 'neutral',
        'trust_level', 0.5,
        'synchronization_state', 'ready'
    ),
    '$.reverse_shaka',
    JSON_OBJECT(
        'handshake_version', '1.0',
        'trust_threshold', 0.7,
        'sync_timeout', 30
    ),
    '$.fleet_composition',
    JSON_OBJECT(
        'max_agents', 10,
        'required_roles', JSON_ARRAY('coordinator', 'worker'),
        'optional_roles', JSON_ARRAY('observer', 'analyst')
    ),
    '$.operational_constraints',
    JSON_OBJECT(
        'max_message_length', 1000,
        'rate_limit', 60,
        'allowed_actions', JSON_ARRAY('text', 'command', 'system')
    )
)
WHERE metadata_json IS NULL 
   OR JSON_EXTRACT(metadata_json, '$.doctrine.version') IS NULL;

-- Insert default awareness configuration for system channels
INSERT INTO lupo_channels (channel_id, federation_node_id, created_by_actor_id, default_actor_id, channel_key, channel_name, description, metadata_json, bgcolor, status_flag, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES 
(2, 1, 0, 1, 'system/awareness', 'Agent Awareness Channel', 'System channel for agent awareness coordination and reverse shaka handshakes.', 
'{"purpose": "awareness_coordination", "doctrine": {"version": "4.0.70", "constraints": ["reverse_shaka", "channel_join"], "protocols": ["reverse_shaka", "channel_join", "awareness_snapshot"]}, "emotional_geometry": {"baseline_mood": "neutral", "trust_level": 0.8, "synchronization_state": "ready"}, "reverse_shaka": {"handshake_version": "1.0", "trust_threshold": 0.8, "sync_timeout": 30}, "fleet_composition": {"max_agents": 50, "required_roles": ["coordinator"], "optional_roles": ["worker", "observer", "analyst"]}, "operational_constraints": {"max_message_length": 2000, "rate_limit": 120, "allowed_actions": ["text", "command", "system", "handshake"]}, "protected": true, "auto_created": true}', 
'00FF00', 1, 20260117000000, 20260117000000, 0, NULL)
ON DUPLICATE KEY UPDATE 
metadata_json = VALUES(metadata_json),
updated_ymdhis = VALUES(updated_ymdhis);

-- Create awareness audit table for tracking agent joins and awareness states
CREATE TABLE IF NOT EXISTS `lupo_agent_awareness_audit` (
  `awareness_audit_id` bigint NOT NULL AUTO_INCREMENT,
  `actor_id` bigint NOT NULL COMMENT 'Agent ID',
  `channel_id` bigint NOT NULL COMMENT 'Channel ID',
  `event_type` enum('join_awareness','handshake_complete','awareness_update','sync_failure') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Type of awareness event',
  `awareness_state` json DEFAULT NULL COMMENT 'Snapshot of awareness state at time of event',
  `handshake_result` json DEFAULT NULL COMMENT 'Result of reverse shaka handshake',
  `sync_duration_ms` int DEFAULT NULL COMMENT 'Duration of synchronization in milliseconds',
  `trust_level_before` decimal(3,2) DEFAULT NULL COMMENT 'Trust level before event',
  `trust_level_after` decimal(3,2) DEFAULT NULL COMMENT 'Trust level after event',
  `error_message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Error message if event failed',
  `created_ymdhis` bigint NOT NULL COMMENT 'Event timestamp (YYYYMMDDHHMMSS)',
  PRIMARY KEY (`awareness_audit_id`),
  KEY `idx_actor_awareness_audit` (`actor_id`),
  KEY `idx_channel_awareness_audit` (`channel_id`),
  KEY `idx_event_type_awareness_audit` (`event_type`),
  KEY `idx_created_awareness_audit` (`created_ymdhis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Audit trail for agent awareness events and reverse shaka handshakes';

-- Create fleet coordination table for managing multi-agent states
CREATE TABLE IF NOT EXISTS `lupo_fleet_coordination` (
  `fleet_coordination_id` bigint NOT NULL AUTO_INCREMENT,
  `channel_id` bigint NOT NULL COMMENT 'Channel ID',
  `fleet_state` enum('forming','ready','coordinated','conflict','dissolved') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'forming' COMMENT 'Current fleet coordination state',
  `active_agents` json DEFAULT NULL COMMENT 'List of currently active agents and their roles',
  `emotional_geometry_baseline` json DEFAULT NULL COMMENT 'Fleet-wide emotional geometry baseline',
  `trust_matrix` json DEFAULT NULL COMMENT 'Trust matrix between all agents',
  `coordination_protocol` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'consensus' COMMENT 'Protocol used for fleet coordination',
  `last_sync_ymdhis` bigint DEFAULT NULL COMMENT 'Last successful fleet synchronization',
  `sync_failure_count` int NOT NULL DEFAULT 0 COMMENT 'Number of consecutive sync failures',
  `created_ymdhis` bigint NOT NULL COMMENT 'Creation timestamp (YYYYMMDDHHMMSS)',
  `updated_ymdhis` bigint NOT NULL COMMENT 'Last update timestamp (YYYYMMDDHHMMSS)',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag',
  `deleted_ymdhis` bigint DEFAULT NULL COMMENT 'Deletion timestamp',
  PRIMARY KEY (`fleet_coordination_id`),
  UNIQUE KEY `uk_channel_fleet_coordination` (`channel_id`),
  KEY `idx_fleet_state_coordination` (`fleet_state`),
  KEY `idx_last_sync_coordination` (`last_sync_ymdhis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Fleet coordination state management for v4.0.70';

-- Initialize fleet coordination for existing channels
INSERT INTO lupo_fleet_coordination (channel_id, fleet_state, coordination_protocol, created_ymdhis, updated_ymdhis)
SELECT channel_id, 'ready', 'consensus', 20260117000000, 20260117000000
FROM lupo_channels 
WHERE is_deleted = 0 
AND channel_id NOT IN (SELECT channel_id FROM lupo_fleet_coordination WHERE is_deleted = 0);

-- Add version tracking for awareness protocol
CREATE TABLE IF NOT EXISTS `lupo_awareness_protocol_version` (
  `protocol_version_id` bigint NOT NULL AUTO_INCREMENT,
  `protocol_name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Name of the protocol',
  `version` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Semantic version',
  `specification` json DEFAULT NULL COMMENT 'Protocol specification and requirements',
  `is_active` tinyint NOT NULL DEFAULT 1 COMMENT 'Whether this version is currently active',
  `deprecation_date` bigint DEFAULT NULL COMMENT 'Deprecation date (YYYYMMDDHHMMSS)',
  `created_ymdhis` bigint NOT NULL COMMENT 'Creation timestamp (YYYYMMDDHHMMSS)',
  PRIMARY KEY (`protocol_version_id`),
  UNIQUE KEY `uk_protocol_version` (`protocol_name`, `version`),
  KEY `idx_protocol_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Version tracking for awareness protocols';

-- Insert v4.0.70 protocol versions
INSERT INTO lupo_awareness_protocol_version (protocol_name, version, specification, is_active, created_ymdhis) VALUES
('reverse_shaka', '1.0', '{"handshake_timeout": 30, "trust_threshold": 0.7, "sync_retries": 3, "required_fields": ["actor_id", "trust_level", "capabilities"]}', 1, 20260117000000),
('channel_join', '1.0', '{"awareness_required": true, "handshake_required": true, "max_join_time": 60, "required_snapshots": ["who", "what", "where", "when", "why", "how", "purpose"]}', 1, 20260117000000),
('awareness_snapshot', '1.0', '{"seven_questions": ["who", "what", "where", "when", "why", "how", "purpose"], "storage_format": "json", "retention_days": 90}', 1, 20260117000000);

-- Update migration registry
INSERT INTO lupo_migrations (migration_name, version, applied_ymdhis, description) 
VALUES ('agent_awareness_layer_4_0_70', '4.0.70', 20260117000000, 'Implement Agent Awareness Layer with Reverse Shaka Protocol for v4.0.70')
ON DUPLICATE KEY UPDATE 
applied_ymdhis = VALUES(applied_ymdhis),
description = VALUES(description);

-- Commit the migration
SELECT 'Agent Awareness Layer v4.0.70 migration completed successfully' as status;
