-- Multi-Agent Protocol Schema Extensions
-- Implements Agent Awareness Layer (AAL), Reverse Shaka Handshake Protocol (RSHAP), 
-- and Channel Join Protocol (CJP) as defined in version 4.0.72
-- 
-- @package Lupopedia
-- @version 4.0.72
-- @author kiro (AI Assistant)

-- ============================================================================
-- EXTEND lupo_channels TABLE FOR AAL METADATA
-- ============================================================================
-- Add AAL-specific fields for channel awareness
-- Note: metadata_json already exists in lupo_channels (see TOON files)
-- MySQL doesn't support IF NOT EXISTS for ADD COLUMN - columns will error if they already exist
ALTER TABLE `lupo_channels` 
ADD COLUMN `aal_metadata_json` JSON DEFAULT NULL COMMENT 'Agent Awareness Layer metadata for WHO/WHAT/WHERE/WHEN/WHY/HOW/PURPOSE',
ADD COLUMN `fleet_composition_json` JSON DEFAULT NULL COMMENT 'Current fleet of agents in this channel with their roles',
ADD COLUMN `awareness_version` VARCHAR(20) DEFAULT '4.0.72' COMMENT 'AAL protocol version for this channel';

-- ============================================================================
-- EXTEND lupo_actor_channel_roles TABLE FOR RSHAP AND CJP
-- ============================================================================
-- Add RSHAP handshake metadata and CJP awareness snapshot

ALTER TABLE `lupo_actor_channel_roles`
ADD COLUMN `handshake_metadata_json` JSON DEFAULT NULL COMMENT 'RSHAP handshake identity and synchronization data',
ADD COLUMN `awareness_snapshot_json` JSON DEFAULT NULL COMMENT 'CJP Awareness Snapshot (WHO/WHAT/WHERE/WHEN/WHY/HOW/PURPOSE)',
ADD COLUMN `protocol_completion_status` ENUM('pending', 'aal_complete', 'rshap_complete', 'cjp_complete', 'ready') DEFAULT 'pending' COMMENT 'Multi-agent protocol completion status',
ADD COLUMN `protocol_version` VARCHAR(20) DEFAULT '4.0.72' COMMENT 'Protocol version used for this actor-channel relationship',
ADD COLUMN `join_sequence_step` TINYINT DEFAULT 0 COMMENT 'Current step in 10-step CJP sequence (0-10)',
ADD COLUMN `handshake_completed_ymdhis` BIGINT DEFAULT NULL COMMENT 'Timestamp when RSHAP was completed',
ADD COLUMN `awareness_completed_ymdhis` BIGINT DEFAULT NULL COMMENT 'Timestamp when AAL was completed',
ADD COLUMN `cjp_completed_ymdhis` BIGINT DEFAULT NULL COMMENT 'Timestamp when full CJP was completed';

-- ============================================================================
-- EXTEND lupo_actor_collections TABLE FOR PERSISTENT IDENTITY
-- ============================================================================
-- Add persistent identity storage for RSHAP

ALTER TABLE `lupo_actor_collections`
ADD COLUMN `persistent_identity_json` JSON DEFAULT NULL COMMENT 'RSHAP persistent identity metadata',
ADD COLUMN `identity_signature` VARCHAR(255) DEFAULT NULL COMMENT 'Unique identity signature for handshake verification',
ADD COLUMN `trust_level` ENUM('system', 'verified', 'standard', 'restricted', 'untrusted') DEFAULT 'standard' COMMENT 'Trust level for multi-agent interactions',
ADD COLUMN `emotional_geometry_baseline` JSON DEFAULT NULL COMMENT 'Baseline emotional geometry for agent interactions',
ADD COLUMN `doctrine_alignment_version` VARCHAR(20) DEFAULT '4.0.72' COMMENT 'Version of doctrine this actor aligns with';

-- ============================================================================
-- CREATE INDEXES FOR PERFORMANCE
-- ============================================================================

-- Indexes for protocol status queries
-- Note: MySQL doesn't support IF NOT EXISTS for CREATE INDEX - will error if index already exists
CREATE INDEX `idx_protocol_completion_status` ON `lupo_actor_channel_roles` (`protocol_completion_status`);
CREATE INDEX `idx_join_sequence_step` ON `lupo_actor_channel_roles` (`join_sequence_step`);
CREATE INDEX `idx_protocol_version` ON `lupo_actor_channel_roles` (`protocol_version`);

-- Indexes for identity lookups
CREATE INDEX `idx_identity_signature` ON `lupo_actor_collections` (`identity_signature`);
CREATE INDEX `idx_trust_level` ON `lupo_actor_collections` (`trust_level`);

-- Indexes for channel awareness
CREATE INDEX `idx_awareness_version` ON `lupo_channels` (`awareness_version`);

-- ============================================================================
-- PROTOCOL ENFORCEMENT (MOVED TO PHP SERVICE CLASS)
-- ============================================================================

-- Trigger logic moved to PHP service class:
-- - app/Services/TriggerReplacements/EnforceProtocolCompletionService.php
-- Trigger removed per NO_TRIGGERS_NO_PROCEDURES_DOCTRINE

-- ============================================================================
-- COMMENTS AND DOCUMENTATION
-- ============================================================================

ALTER TABLE `lupo_channels` COMMENT = 'Communication channels with Agent Awareness Layer (AAL) metadata for multi-agent coordination';
ALTER TABLE `lupo_actor_channel_roles` COMMENT = 'Actor-channel relationships with RSHAP handshake and CJP awareness metadata';
ALTER TABLE `lupo_actor_collections` COMMENT = 'Actor collections with persistent identity storage for multi-agent protocols';