-- =====================================================
-- LupoPedia Migration - Part 3: Channel & Communication Tables
-- Based on TOON schema only - HERITAGE-SAFE MODE
-- =====================================================

CREATE TABLE IF NOT EXISTS `lupo_channels` (
    `channel_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for channel',
    `federation_node_id` bigint NOT NULL COMMENT 'Domain/tenant identifier',
    `created_by_actor_id` bigint NOT NULL COMMENT 'who made this channel',
    `default_actor_id` bigint NOT NULL DEFAULT 1 COMMENT 'Default actor ID',
    `channel_key` varchar(64) NOT NULL COMMENT 'URL-friendly identifier (slug)',
    `channel_slug` varchar(32) NOT NULL DEFAULT 'channel_key' COMMENT 'well if they think it exists i guess i will make it',
    `channel_type` varchar(32) NOT NULL DEFAULT 'chat_room',
    `channel_name` varchar(255) NOT NULL COMMENT 'Human-readable channel name',
    `description` text COMMENT 'Channel description',
    `metadata_json` text COMMENT 'JSON metadata for the channel',
    `bgcolor` varchar(6) NOT NULL DEFAULT 'FFFFFF',
    `status_flag` tinyint NOT NULL DEFAULT 1 COMMENT 'Status flag (1=active, 0=inactive)',
    `end_ymdhis` bigint COMMENT 'Channel end timestamp (YYYYMMDDHHMMSS, NULL if ongoing)',
    `duration_seconds` int COMMENT 'Duration of the channel in seconds (if ended)',
    `created_ymdhis` bigint NOT NULL COMMENT 'Creation timestamp (YYYYMMDDHHMMSS)',
    `updated_ymdhis` bigint NOT NULL COMMENT 'Last update timestamp (YYYYMMDDHHMMSS)',
    `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted, 0=active)',
    `deleted_ymdhis` bigint COMMENT 'Deletion timestamp (YYYYMMDDHHMMSS)',
    `aal_metadata_json` json COMMENT 'Agent Awareness Layer metadata for WHO/WHAT/WHERE/WHEN/WHY/HOW/PURPOSE',
    `fleet_composition_json` json COMMENT 'Current fleet of agents in this channel with their roles',
    `awareness_version` varchar(20) DEFAULT '4.0.72' COMMENT 'AAL protocol version for this channel',
    PRIMARY KEY (`channel_id`),
    UNIQUE KEY `unq_channel_key_per_node` (`channel_key`, `federation_node_id`),
    KEY `idx_awareness_version` (`awareness_version`),
    KEY `idx_channel_key` (`channel_key`),
    KEY `idx_dates` (`end_ymdhis`),
    KEY `idx_domain` (`federation_node_id`),
    KEY `idx_status` (`status_flag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `lupo_actor_channels` (
    `actor_channel_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for actor-channel relationship',
    `actor_id` bigint NOT NULL COMMENT 'Reference to the actor (user/agent)',
    `channel_id` bigint NOT NULL COMMENT 'Reference to the channel',
    `status` char(1) NOT NULL DEFAULT 'A' COMMENT 'Status: A=Active, I=Inactive, etc.',
    `start_date` bigint COMMENT 'Timestamp when actor joined channel (YYYYMMDDHHMMSS)',
    `bg_color` varchar(6) NOT NULL DEFAULT '000000' COMMENT 'Background color (6-char hex, no #)',
    `text_color` varchar(6) NOT NULL DEFAULT '000000' COMMENT 'Text color (6-char hex, no #)',
    `channel_color` varchar(6) NOT NULL DEFAULT 'F7FAFF' COMMENT 'Channel-specific color (6-char hex, no #)',
    `alt_text_color` varchar(6) NOT NULL DEFAULT '000000' COMMENT 'Alternate text color (6-char hex, no #)',
    `last_read_ymdhis` bigint COMMENT 'Timestamp when actor last read messages (YYYYMMDDHHMMSS)',
    `muted_until_ymdhis` bigint COMMENT 'Timestamp until notifications are muted (YYYYMMDDHHMMSS)',
    `preferences_json` json COMMENT 'Additional UI/UX preferences in JSON format',
    `dialog_output_file` varchar(500) COMMENT 'Filesystem dialog log path for this actor in this channel; IDE agents write here as mandatory fallback when database dialog tables are unavailable.',
    `created_ymdhis` bigint NOT NULL COMMENT 'Creation timestamp (YYYYMMDDHHMMSS)',
    `updated_ymdhis` bigint NOT NULL COMMENT 'Last update timestamp (YYYYMMDDHHMMSS)',
    `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted, 0=active)',
    `deleted_ymdhis` bigint COMMENT 'Deletion timestamp (YYYYMMDDHHMMSS)',
    PRIMARY KEY (`actor_channel_id`),
    UNIQUE KEY `unq_actor_channel` (`actor_id`, `channel_id`),
    KEY `idx_actor` (`actor_id`),
    KEY `idx_channel` (`channel_id`),
    KEY `idx_created` (`created_ymdhis`),
    KEY `idx_deleted` (`is_deleted`),
    KEY `idx_status` (`status`),
    KEY `idx_updated` (`updated_ymdhis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `lupo_actor_channel_roles` (
    `actor_channel_role_id` bigint NOT NULL AUTO_INCREMENT,
    `actor_id` bigint NOT NULL,
    `channel_id` bigint NOT NULL,
    `role_key` varchar(64) NOT NULL,
    `created_ymdhis` bigint NOT NULL,
    `updated_ymdhis` bigint NOT NULL,
    `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
    `deleted_ymdhis` bigint,
    `handshake_metadata_json` json COMMENT 'RSHAP handshake identity and synchronization data',
    `awareness_snapshot_json` json COMMENT 'CJP Awareness Snapshot (WHO/WHAT/WHERE/WHEN/WHY/HOW/PURPOSE)',
    `protocol_completion_status` enum('pending','aal_complete','rshap_complete','cjp_complete','ready') DEFAULT 'pending' COMMENT 'Multi-agent protocol completion status',
    `protocol_version` varchar(20) DEFAULT '4.0.72' COMMENT 'Protocol version used for this actor-channel relationship',
    `join_sequence_step` tinyint DEFAULT 0 COMMENT 'Current step in 10-step CJP sequence (0-10)',
    `handshake_completed_ymdhis` bigint COMMENT 'Timestamp when RSHAP was completed',
    `awareness_completed_ymdhis` bigint COMMENT 'Timestamp when AAL was completed',
    `cjp_completed_ymdhis` bigint COMMENT 'Timestamp when full CJP was completed',
    PRIMARY KEY (`actor_channel_role_id`),
    KEY `idx_actor_id` (`actor_id`),
    KEY `idx_channel_id` (`channel_id`),
    KEY `idx_join_sequence_step` (`join_sequence_step`),
    KEY `idx_protocol_completion_status` (`protocol_completion_status`),
    KEY `idx_protocol_version` (`protocol_version`),
    KEY `idx_role_key` (`role_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `lupo_dialog_channels` (
    `dialog_channel_id` bigint NOT NULL AUTO_INCREMENT,
    `channel_id` bigint NOT NULL,
    `dialog_type` varchar(50) NOT NULL,
    `status` varchar(20) NOT NULL DEFAULT 'active',
    `created_ymdhis` bigint NOT NULL,
    `updated_ymdhis` bigint NOT NULL,
    `is_deleted` tinyint NOT NULL DEFAULT 0,
    PRIMARY KEY (`dialog_channel_id`),
    KEY `idx_channel_id` (`channel_id`),
    KEY `idx_dialog_type` (`dialog_type`),
    KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
