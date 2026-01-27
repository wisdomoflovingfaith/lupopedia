-- -------------------------------------------------------------------
-- Migration: Schema sync from TOON files (TOON is authoritative)
-- Date: 2026-01-26
-- -------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `lupo_auth_audit_log` (
  `id` bigint NOT NULL auto_increment COMMENT 'Primary key for audit log entry',
  `user_id` bigint COMMENT 'Reference to lupo_auth_users.auth_user_id',
  `crafty_operator_id` int COMMENT 'Reference to livehelp_operators.operatorid',
  `event_type` varchar(50) NOT NULL COMMENT 'login, logout, session_created, session_destroyed, etc.',
  `system_context` varchar(50) NOT NULL COMMENT 'lupopedia, crafty_syntax, unified, admin',
  `ip_address` varchar(45) COMMENT 'Client IP address',
  `user_agent` text COMMENT 'Client user agent string',
  `event_data` json COMMENT 'Additional event metadata',
  `success` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=success, 0=failure',
  `error_message` text COMMENT 'Error details if success=0',
  `created_ymdhis` bigint NOT NULL COMMENT 'Event bigint',
  `updated_ymdhis` bigint NOT NULL COMMENT 'Last update bigint'
);

ALTER TABLE `lupo_auth_audit_log` ADD COLUMN IF NOT EXISTS `id` bigint NOT NULL auto_increment COMMENT 'Primary key for audit log entry';
ALTER TABLE `lupo_auth_audit_log` ADD COLUMN IF NOT EXISTS `user_id` bigint COMMENT 'Reference to lupo_auth_users.auth_user_id';
ALTER TABLE `lupo_auth_audit_log` ADD COLUMN IF NOT EXISTS `crafty_operator_id` int COMMENT 'Reference to livehelp_operators.operatorid';
ALTER TABLE `lupo_auth_audit_log` ADD COLUMN IF NOT EXISTS `event_type` varchar(50) NOT NULL COMMENT 'login, logout, session_created, session_destroyed, etc.';
ALTER TABLE `lupo_auth_audit_log` ADD COLUMN IF NOT EXISTS `system_context` varchar(50) NOT NULL COMMENT 'lupopedia, crafty_syntax, unified, admin';
ALTER TABLE `lupo_auth_audit_log` ADD COLUMN IF NOT EXISTS `ip_address` varchar(45) COMMENT 'Client IP address';
ALTER TABLE `lupo_auth_audit_log` ADD COLUMN IF NOT EXISTS `user_agent` text COMMENT 'Client user agent string';
ALTER TABLE `lupo_auth_audit_log` ADD COLUMN IF NOT EXISTS `event_data` json COMMENT 'Additional event metadata';
ALTER TABLE `lupo_auth_audit_log` ADD COLUMN IF NOT EXISTS `success` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=success, 0=failure';
ALTER TABLE `lupo_auth_audit_log` ADD COLUMN IF NOT EXISTS `error_message` text COMMENT 'Error details if success=0';
ALTER TABLE `lupo_auth_audit_log` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'Event bigint';
ALTER TABLE `lupo_auth_audit_log` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL COMMENT 'Last update bigint';
ALTER TABLE `lupo_auth_audit_log` MODIFY COLUMN `id` bigint NOT NULL auto_increment COMMENT 'Primary key for audit log entry';
ALTER TABLE `lupo_auth_audit_log` MODIFY COLUMN `user_id` bigint COMMENT 'Reference to lupo_auth_users.auth_user_id';
ALTER TABLE `lupo_auth_audit_log` MODIFY COLUMN `crafty_operator_id` int COMMENT 'Reference to livehelp_operators.operatorid';
ALTER TABLE `lupo_auth_audit_log` MODIFY COLUMN `event_type` varchar(50) NOT NULL COMMENT 'login, logout, session_created, session_destroyed, etc.';
ALTER TABLE `lupo_auth_audit_log` MODIFY COLUMN `system_context` varchar(50) NOT NULL COMMENT 'lupopedia, crafty_syntax, unified, admin';
ALTER TABLE `lupo_auth_audit_log` MODIFY COLUMN `ip_address` varchar(45) COMMENT 'Client IP address';
ALTER TABLE `lupo_auth_audit_log` MODIFY COLUMN `user_agent` text COMMENT 'Client user agent string';
ALTER TABLE `lupo_auth_audit_log` MODIFY COLUMN `event_data` json COMMENT 'Additional event metadata';
ALTER TABLE `lupo_auth_audit_log` MODIFY COLUMN `success` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=success, 0=failure';
ALTER TABLE `lupo_auth_audit_log` MODIFY COLUMN `error_message` text COMMENT 'Error details if success=0';
ALTER TABLE `lupo_auth_audit_log` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'Event bigint';
ALTER TABLE `lupo_auth_audit_log` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL COMMENT 'Last update bigint';

CREATE TABLE IF NOT EXISTS `lupo_integration_test_results` (
  `test_result_id` bigint NOT NULL auto_increment,
  `test_suite` varchar(64) NOT NULL,
  `test_case` varchar(128) NOT NULL,
  `expected_result` varchar(255),
  `actual_result` varchar(255),
  `status` enum('PASS','FAIL','SKIP','ERROR') NOT NULL,
  `error_message` text,
  `execution_time_ms` int,
  `created_ymdhis` bigint NOT NULL
);

ALTER TABLE `lupo_integration_test_results` ADD COLUMN IF NOT EXISTS `test_result_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_integration_test_results` ADD COLUMN IF NOT EXISTS `test_suite` varchar(64) NOT NULL;
ALTER TABLE `lupo_integration_test_results` ADD COLUMN IF NOT EXISTS `test_case` varchar(128) NOT NULL;
ALTER TABLE `lupo_integration_test_results` ADD COLUMN IF NOT EXISTS `expected_result` varchar(255);
ALTER TABLE `lupo_integration_test_results` ADD COLUMN IF NOT EXISTS `actual_result` varchar(255);
ALTER TABLE `lupo_integration_test_results` ADD COLUMN IF NOT EXISTS `status` enum('PASS','FAIL','SKIP','ERROR') NOT NULL;
ALTER TABLE `lupo_integration_test_results` ADD COLUMN IF NOT EXISTS `error_message` text;
ALTER TABLE `lupo_integration_test_results` ADD COLUMN IF NOT EXISTS `execution_time_ms` int;
ALTER TABLE `lupo_integration_test_results` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_integration_test_results` MODIFY COLUMN `test_result_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_integration_test_results` MODIFY COLUMN `test_suite` varchar(64) NOT NULL;
ALTER TABLE `lupo_integration_test_results` MODIFY COLUMN `test_case` varchar(128) NOT NULL;
ALTER TABLE `lupo_integration_test_results` MODIFY COLUMN `expected_result` varchar(255);
ALTER TABLE `lupo_integration_test_results` MODIFY COLUMN `actual_result` varchar(255);
ALTER TABLE `lupo_integration_test_results` MODIFY COLUMN `status` enum('PASS','FAIL','SKIP','ERROR') NOT NULL;
ALTER TABLE `lupo_integration_test_results` MODIFY COLUMN `error_message` text;
ALTER TABLE `lupo_integration_test_results` MODIFY COLUMN `execution_time_ms` int;
ALTER TABLE `lupo_integration_test_results` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;

CREATE TABLE IF NOT EXISTS `lupo_actor_actions` (
  `actor_action_id` bigint NOT NULL auto_increment,
  `actor_id` bigint NOT NULL,
  `action_type` varchar(64) NOT NULL,
  `entity_type` varchar(64),
  `entity_id` bigint,
  `description` text,
  `metadata_json` json,
  `created_ymdhis` char(14) NOT NULL,
  PRIMARY KEY (`actor_action_id`),
  KEY `idx_action_type` (`action_type`),
  KEY `idx_actor` (`actor_id`),
  KEY `idx_entity` (`entity_type`, `entity_id`)
);

ALTER TABLE `lupo_actor_actions` ADD COLUMN IF NOT EXISTS `actor_action_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_actor_actions` ADD COLUMN IF NOT EXISTS `actor_id` bigint NOT NULL;
ALTER TABLE `lupo_actor_actions` ADD COLUMN IF NOT EXISTS `action_type` varchar(64) NOT NULL;
ALTER TABLE `lupo_actor_actions` ADD COLUMN IF NOT EXISTS `entity_type` varchar(64);
ALTER TABLE `lupo_actor_actions` ADD COLUMN IF NOT EXISTS `entity_id` bigint;
ALTER TABLE `lupo_actor_actions` ADD COLUMN IF NOT EXISTS `description` text;
ALTER TABLE `lupo_actor_actions` ADD COLUMN IF NOT EXISTS `metadata_json` json;
ALTER TABLE `lupo_actor_actions` ADD COLUMN IF NOT EXISTS `created_ymdhis` char(14) NOT NULL;
ALTER TABLE `lupo_actor_actions` MODIFY COLUMN `actor_action_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_actor_actions` MODIFY COLUMN `actor_id` bigint NOT NULL;
ALTER TABLE `lupo_actor_actions` MODIFY COLUMN `action_type` varchar(64) NOT NULL;
ALTER TABLE `lupo_actor_actions` MODIFY COLUMN `entity_type` varchar(64);
ALTER TABLE `lupo_actor_actions` MODIFY COLUMN `entity_id` bigint;
ALTER TABLE `lupo_actor_actions` MODIFY COLUMN `description` text;
ALTER TABLE `lupo_actor_actions` MODIFY COLUMN `metadata_json` json;
ALTER TABLE `lupo_actor_actions` MODIFY COLUMN `created_ymdhis` char(14) NOT NULL;
ALTER TABLE `lupo_actor_actions` ADD PRIMARY KEY (`actor_action_id`);
ALTER TABLE `lupo_actor_actions` ADD INDEX IF NOT EXISTS `idx_action_type` (`action_type`);
ALTER TABLE `lupo_actor_actions` ADD INDEX IF NOT EXISTS `idx_actor` (`actor_id`);
ALTER TABLE `lupo_actor_actions` ADD INDEX IF NOT EXISTS `idx_entity` (`entity_type`, `entity_id`);

CREATE TABLE IF NOT EXISTS `lupo_actor_capabilities` (
  `actor_capability_id` bigint NOT NULL auto_increment,
  `actor_id` bigint NOT NULL,
  `domain_id` bigint NOT NULL COMMENT 'Domain scope for this capability',
  `capability_key` varchar(100) NOT NULL COMMENT 'Unique capability identifier (e.g., "edit_content", "manage_users")',
  `capability_description` text COMMENT 'Detailed description of the capability',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
  `updated_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when last updated',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted',
  `scope_limitation` varchar(50) DEFAULT 'unrestricted' COMMENT 'domain, session, user, content',
  `max_calls_per_hour` int DEFAULT 0 COMMENT '0 = unlimited',
  `requires_approval` tinyint DEFAULT 0 COMMENT '1 = needs manual approval',
  `approval_agent_id` bigint COMMENT 'Which agent must approve',
  PRIMARY KEY (`actor_capability_id`),
  KEY `idx_agent_domain` (`actor_id`, `domain_id`),
  KEY `idx_capability_key` (`capability_key`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_domain_id` (`domain_id`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_updated_ymdhis` (`updated_ymdhis`),
  UNIQUE KEY `unique_agent_domain_capability` (`actor_id`, `domain_id`, `capability_key`)
);

ALTER TABLE `lupo_actor_capabilities` ADD COLUMN IF NOT EXISTS `actor_capability_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_actor_capabilities` ADD COLUMN IF NOT EXISTS `actor_id` bigint NOT NULL;
ALTER TABLE `lupo_actor_capabilities` ADD COLUMN IF NOT EXISTS `domain_id` bigint NOT NULL COMMENT 'Domain scope for this capability';
ALTER TABLE `lupo_actor_capabilities` ADD COLUMN IF NOT EXISTS `capability_key` varchar(100) NOT NULL COMMENT 'Unique capability identifier (e.g., "edit_content", "manage_users")';
ALTER TABLE `lupo_actor_capabilities` ADD COLUMN IF NOT EXISTS `capability_description` text COMMENT 'Detailed description of the capability';
ALTER TABLE `lupo_actor_capabilities` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created';
ALTER TABLE `lupo_actor_capabilities` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when last updated';
ALTER TABLE `lupo_actor_capabilities` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted';
ALTER TABLE `lupo_actor_capabilities` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted';
ALTER TABLE `lupo_actor_capabilities` ADD COLUMN IF NOT EXISTS `scope_limitation` varchar(50) DEFAULT 'unrestricted' COMMENT 'domain, session, user, content';
ALTER TABLE `lupo_actor_capabilities` ADD COLUMN IF NOT EXISTS `max_calls_per_hour` int DEFAULT 0 COMMENT '0 = unlimited';
ALTER TABLE `lupo_actor_capabilities` ADD COLUMN IF NOT EXISTS `requires_approval` tinyint DEFAULT 0 COMMENT '1 = needs manual approval';
ALTER TABLE `lupo_actor_capabilities` ADD COLUMN IF NOT EXISTS `approval_agent_id` bigint COMMENT 'Which agent must approve';
ALTER TABLE `lupo_actor_capabilities` MODIFY COLUMN `actor_capability_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_actor_capabilities` MODIFY COLUMN `actor_id` bigint NOT NULL;
ALTER TABLE `lupo_actor_capabilities` MODIFY COLUMN `domain_id` bigint NOT NULL COMMENT 'Domain scope for this capability';
ALTER TABLE `lupo_actor_capabilities` MODIFY COLUMN `capability_key` varchar(100) NOT NULL COMMENT 'Unique capability identifier (e.g., "edit_content", "manage_users")';
ALTER TABLE `lupo_actor_capabilities` MODIFY COLUMN `capability_description` text COMMENT 'Detailed description of the capability';
ALTER TABLE `lupo_actor_capabilities` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created';
ALTER TABLE `lupo_actor_capabilities` MODIFY COLUMN `updated_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when last updated';
ALTER TABLE `lupo_actor_capabilities` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted';
ALTER TABLE `lupo_actor_capabilities` MODIFY COLUMN `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted';
ALTER TABLE `lupo_actor_capabilities` MODIFY COLUMN `scope_limitation` varchar(50) DEFAULT 'unrestricted' COMMENT 'domain, session, user, content';
ALTER TABLE `lupo_actor_capabilities` MODIFY COLUMN `max_calls_per_hour` int DEFAULT 0 COMMENT '0 = unlimited';
ALTER TABLE `lupo_actor_capabilities` MODIFY COLUMN `requires_approval` tinyint DEFAULT 0 COMMENT '1 = needs manual approval';
ALTER TABLE `lupo_actor_capabilities` MODIFY COLUMN `approval_agent_id` bigint COMMENT 'Which agent must approve';
ALTER TABLE `lupo_actor_capabilities` ADD PRIMARY KEY (`actor_capability_id`);
ALTER TABLE `lupo_actor_capabilities` ADD INDEX IF NOT EXISTS `idx_agent_domain` (`actor_id`, `domain_id`);
ALTER TABLE `lupo_actor_capabilities` ADD INDEX IF NOT EXISTS `idx_capability_key` (`capability_key`);
ALTER TABLE `lupo_actor_capabilities` ADD INDEX IF NOT EXISTS `idx_created_ymdhis` (`created_ymdhis`);
ALTER TABLE `lupo_actor_capabilities` ADD INDEX IF NOT EXISTS `idx_domain_id` (`domain_id`);
ALTER TABLE `lupo_actor_capabilities` ADD INDEX IF NOT EXISTS `idx_is_deleted` (`is_deleted`);
ALTER TABLE `lupo_actor_capabilities` ADD INDEX IF NOT EXISTS `idx_updated_ymdhis` (`updated_ymdhis`);
ALTER TABLE `lupo_actor_capabilities` ADD UNIQUE INDEX IF NOT EXISTS `unique_agent_domain_capability` (`actor_id`, `domain_id`, `capability_key`);

CREATE TABLE IF NOT EXISTS `lupo_actor_channel_roles` (
  `actor_channel_role_id` bigint NOT NULL auto_increment,
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
  `handshake_completed_ymdhis` bigint COMMENT 'bigint when RSHAP was completed',
  `awareness_completed_ymdhis` bigint COMMENT 'bigint when AAL was completed',
  `cjp_completed_ymdhis` bigint COMMENT 'bigint when full CJP was completed',
  PRIMARY KEY (`actor_channel_role_id`),
  KEY `idx_actor_id` (`actor_id`),
  KEY `idx_channel_id` (`channel_id`),
  KEY `idx_join_sequence_step` (`join_sequence_step`),
  KEY `idx_protocol_completion_status` (`protocol_completion_status`),
  KEY `idx_protocol_version` (`protocol_version`),
  KEY `idx_role_key` (`role_key`)
);

ALTER TABLE `lupo_actor_channel_roles` ADD COLUMN IF NOT EXISTS `actor_channel_role_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_actor_channel_roles` ADD COLUMN IF NOT EXISTS `actor_id` bigint NOT NULL;
ALTER TABLE `lupo_actor_channel_roles` ADD COLUMN IF NOT EXISTS `channel_id` bigint NOT NULL;
ALTER TABLE `lupo_actor_channel_roles` ADD COLUMN IF NOT EXISTS `role_key` varchar(64) NOT NULL;
ALTER TABLE `lupo_actor_channel_roles` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_actor_channel_roles` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_actor_channel_roles` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint(1) NOT NULL DEFAULT 0;
ALTER TABLE `lupo_actor_channel_roles` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint;
ALTER TABLE `lupo_actor_channel_roles` ADD COLUMN IF NOT EXISTS `handshake_metadata_json` json COMMENT 'RSHAP handshake identity and synchronization data';
ALTER TABLE `lupo_actor_channel_roles` ADD COLUMN IF NOT EXISTS `awareness_snapshot_json` json COMMENT 'CJP Awareness Snapshot (WHO/WHAT/WHERE/WHEN/WHY/HOW/PURPOSE)';
ALTER TABLE `lupo_actor_channel_roles` ADD COLUMN IF NOT EXISTS `protocol_completion_status` enum('pending','aal_complete','rshap_complete','cjp_complete','ready') DEFAULT 'pending' COMMENT 'Multi-agent protocol completion status';
ALTER TABLE `lupo_actor_channel_roles` ADD COLUMN IF NOT EXISTS `protocol_version` varchar(20) DEFAULT '4.0.72' COMMENT 'Protocol version used for this actor-channel relationship';
ALTER TABLE `lupo_actor_channel_roles` ADD COLUMN IF NOT EXISTS `join_sequence_step` tinyint DEFAULT 0 COMMENT 'Current step in 10-step CJP sequence (0-10)';
ALTER TABLE `lupo_actor_channel_roles` ADD COLUMN IF NOT EXISTS `handshake_completed_ymdhis` bigint COMMENT 'bigint when RSHAP was completed';
ALTER TABLE `lupo_actor_channel_roles` ADD COLUMN IF NOT EXISTS `awareness_completed_ymdhis` bigint COMMENT 'bigint when AAL was completed';
ALTER TABLE `lupo_actor_channel_roles` ADD COLUMN IF NOT EXISTS `cjp_completed_ymdhis` bigint COMMENT 'bigint when full CJP was completed';
ALTER TABLE `lupo_actor_channel_roles` MODIFY COLUMN `actor_channel_role_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_actor_channel_roles` MODIFY COLUMN `actor_id` bigint NOT NULL;
ALTER TABLE `lupo_actor_channel_roles` MODIFY COLUMN `channel_id` bigint NOT NULL;
ALTER TABLE `lupo_actor_channel_roles` MODIFY COLUMN `role_key` varchar(64) NOT NULL;
ALTER TABLE `lupo_actor_channel_roles` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_actor_channel_roles` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_actor_channel_roles` MODIFY COLUMN `is_deleted` tinyint(1) NOT NULL DEFAULT 0;
ALTER TABLE `lupo_actor_channel_roles` MODIFY COLUMN `deleted_ymdhis` bigint;
ALTER TABLE `lupo_actor_channel_roles` MODIFY COLUMN `handshake_metadata_json` json COMMENT 'RSHAP handshake identity and synchronization data';
ALTER TABLE `lupo_actor_channel_roles` MODIFY COLUMN `awareness_snapshot_json` json COMMENT 'CJP Awareness Snapshot (WHO/WHAT/WHERE/WHEN/WHY/HOW/PURPOSE)';
ALTER TABLE `lupo_actor_channel_roles` MODIFY COLUMN `protocol_completion_status` enum('pending','aal_complete','rshap_complete','cjp_complete','ready') DEFAULT 'pending' COMMENT 'Multi-agent protocol completion status';
ALTER TABLE `lupo_actor_channel_roles` MODIFY COLUMN `protocol_version` varchar(20) DEFAULT '4.0.72' COMMENT 'Protocol version used for this actor-channel relationship';
ALTER TABLE `lupo_actor_channel_roles` MODIFY COLUMN `join_sequence_step` tinyint DEFAULT 0 COMMENT 'Current step in 10-step CJP sequence (0-10)';
ALTER TABLE `lupo_actor_channel_roles` MODIFY COLUMN `handshake_completed_ymdhis` bigint COMMENT 'bigint when RSHAP was completed';
ALTER TABLE `lupo_actor_channel_roles` MODIFY COLUMN `awareness_completed_ymdhis` bigint COMMENT 'bigint when AAL was completed';
ALTER TABLE `lupo_actor_channel_roles` MODIFY COLUMN `cjp_completed_ymdhis` bigint COMMENT 'bigint when full CJP was completed';
ALTER TABLE `lupo_actor_channel_roles` ADD PRIMARY KEY (`actor_channel_role_id`);
ALTER TABLE `lupo_actor_channel_roles` ADD INDEX IF NOT EXISTS `idx_actor_id` (`actor_id`);
ALTER TABLE `lupo_actor_channel_roles` ADD INDEX IF NOT EXISTS `idx_channel_id` (`channel_id`);
ALTER TABLE `lupo_actor_channel_roles` ADD INDEX IF NOT EXISTS `idx_join_sequence_step` (`join_sequence_step`);
ALTER TABLE `lupo_actor_channel_roles` ADD INDEX IF NOT EXISTS `idx_protocol_completion_status` (`protocol_completion_status`);
ALTER TABLE `lupo_actor_channel_roles` ADD INDEX IF NOT EXISTS `idx_protocol_version` (`protocol_version`);
ALTER TABLE `lupo_actor_channel_roles` ADD INDEX IF NOT EXISTS `idx_role_key` (`role_key`);

CREATE TABLE IF NOT EXISTS `lupo_actor_channels` (
  `actor_channel_id` bigint NOT NULL auto_increment COMMENT 'Primary key for the actor-channel relationship',
  `actor_id` bigint NOT NULL COMMENT 'Reference to the actor (user/agent)',
  `channel_id` bigint NOT NULL COMMENT 'Reference to the channel',
  `status` char(1) NOT NULL DEFAULT 'A' COMMENT 'Status: A=Active, I=Inactive, etc.',
  `start_date` bigint COMMENT 'bigint when actor joined the channel (YYYYMMDDHHMMSS)',
  `bg_color` varchar(6) NOT NULL DEFAULT '000000' COMMENT 'Background color (6-char hex, no #)',
  `text_color` varchar(6) NOT NULL DEFAULT '000000' COMMENT 'Text color (6-char hex, no #)',
  `channel_color` varchar(6) NOT NULL DEFAULT 'F7FAFF' COMMENT 'Channel-specific color (6-char hex, no #)',
  `alt_text_color` varchar(6) NOT NULL DEFAULT '000000' COMMENT 'Alternate text color (6-char hex, no #)',
  `last_read_ymdhis` bigint COMMENT 'bigint when actor last read messages (YYYYMMDDHHMMSS)',
  `muted_until_ymdhis` bigint COMMENT 'bigint until notifications are muted (YYYYMMDDHHMMSS)',
  `preferences_json` json COMMENT 'Additional UI/UX preferences in JSON format',
  `dialog_output_file` varchar(500) COMMENT 'Filesystem dialog log path for this actor in this channel; IDE agents write here as the mandatory fallback when database dialog tables are unavailable.',
  `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint (YYYYMMDDHHMMSS)',
  `updated_ymdhis` bigint NOT NULL COMMENT 'Last update bigint (YYYYMMDDHHMMSS)',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted, 0=active)',
  `deleted_ymdhis` bigint COMMENT 'Deletion bigint (YYYYMMDDHHMMSS)',
  PRIMARY KEY (`actor_channel_id`),
  KEY `idx_actor` (`actor_id`),
  KEY `idx_channel` (`channel_id`),
  KEY `idx_created` (`created_ymdhis`),
  KEY `idx_deleted` (`is_deleted`),
  KEY `idx_status` (`status`),
  KEY `idx_updated` (`updated_ymdhis`),
  UNIQUE KEY `unq_actor_channel` (`actor_id`, `channel_id`)
);

ALTER TABLE `lupo_actor_channels` ADD COLUMN IF NOT EXISTS `actor_channel_id` bigint NOT NULL auto_increment COMMENT 'Primary key for the actor-channel relationship';
ALTER TABLE `lupo_actor_channels` ADD COLUMN IF NOT EXISTS `actor_id` bigint NOT NULL COMMENT 'Reference to the actor (user/agent)';
ALTER TABLE `lupo_actor_channels` ADD COLUMN IF NOT EXISTS `channel_id` bigint NOT NULL COMMENT 'Reference to the channel';
ALTER TABLE `lupo_actor_channels` ADD COLUMN IF NOT EXISTS `status` char(1) NOT NULL DEFAULT 'A' COMMENT 'Status: A=Active, I=Inactive, etc.';
ALTER TABLE `lupo_actor_channels` ADD COLUMN IF NOT EXISTS `start_date` bigint COMMENT 'bigint when actor joined the channel (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_actor_channels` ADD COLUMN IF NOT EXISTS `bg_color` varchar(6) NOT NULL DEFAULT '000000' COMMENT 'Background color (6-char hex, no #)';
ALTER TABLE `lupo_actor_channels` ADD COLUMN IF NOT EXISTS `text_color` varchar(6) NOT NULL DEFAULT '000000' COMMENT 'Text color (6-char hex, no #)';
ALTER TABLE `lupo_actor_channels` ADD COLUMN IF NOT EXISTS `channel_color` varchar(6) NOT NULL DEFAULT 'F7FAFF' COMMENT 'Channel-specific color (6-char hex, no #)';
ALTER TABLE `lupo_actor_channels` ADD COLUMN IF NOT EXISTS `alt_text_color` varchar(6) NOT NULL DEFAULT '000000' COMMENT 'Alternate text color (6-char hex, no #)';
ALTER TABLE `lupo_actor_channels` ADD COLUMN IF NOT EXISTS `last_read_ymdhis` bigint COMMENT 'bigint when actor last read messages (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_actor_channels` ADD COLUMN IF NOT EXISTS `muted_until_ymdhis` bigint COMMENT 'bigint until notifications are muted (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_actor_channels` ADD COLUMN IF NOT EXISTS `preferences_json` json COMMENT 'Additional UI/UX preferences in JSON format';
ALTER TABLE `lupo_actor_channels` ADD COLUMN IF NOT EXISTS `dialog_output_file` varchar(500) COMMENT 'Filesystem dialog log path for this actor in this channel; IDE agents write here as the mandatory fallback when database dialog tables are unavailable.';
ALTER TABLE `lupo_actor_channels` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_actor_channels` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL COMMENT 'Last update bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_actor_channels` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted, 0=active)';
ALTER TABLE `lupo_actor_channels` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint COMMENT 'Deletion bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_actor_channels` MODIFY COLUMN `actor_channel_id` bigint NOT NULL auto_increment COMMENT 'Primary key for the actor-channel relationship';
ALTER TABLE `lupo_actor_channels` MODIFY COLUMN `actor_id` bigint NOT NULL COMMENT 'Reference to the actor (user/agent)';
ALTER TABLE `lupo_actor_channels` MODIFY COLUMN `channel_id` bigint NOT NULL COMMENT 'Reference to the channel';
ALTER TABLE `lupo_actor_channels` MODIFY COLUMN `status` char(1) NOT NULL DEFAULT 'A' COMMENT 'Status: A=Active, I=Inactive, etc.';
ALTER TABLE `lupo_actor_channels` MODIFY COLUMN `start_date` bigint COMMENT 'bigint when actor joined the channel (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_actor_channels` MODIFY COLUMN `bg_color` varchar(6) NOT NULL DEFAULT '000000' COMMENT 'Background color (6-char hex, no #)';
ALTER TABLE `lupo_actor_channels` MODIFY COLUMN `text_color` varchar(6) NOT NULL DEFAULT '000000' COMMENT 'Text color (6-char hex, no #)';
ALTER TABLE `lupo_actor_channels` MODIFY COLUMN `channel_color` varchar(6) NOT NULL DEFAULT 'F7FAFF' COMMENT 'Channel-specific color (6-char hex, no #)';
ALTER TABLE `lupo_actor_channels` MODIFY COLUMN `alt_text_color` varchar(6) NOT NULL DEFAULT '000000' COMMENT 'Alternate text color (6-char hex, no #)';
ALTER TABLE `lupo_actor_channels` MODIFY COLUMN `last_read_ymdhis` bigint COMMENT 'bigint when actor last read messages (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_actor_channels` MODIFY COLUMN `muted_until_ymdhis` bigint COMMENT 'bigint until notifications are muted (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_actor_channels` MODIFY COLUMN `preferences_json` json COMMENT 'Additional UI/UX preferences in JSON format';
ALTER TABLE `lupo_actor_channels` MODIFY COLUMN `dialog_output_file` varchar(500) COMMENT 'Filesystem dialog log path for this actor in this channel; IDE agents write here as the mandatory fallback when database dialog tables are unavailable.';
ALTER TABLE `lupo_actor_channels` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_actor_channels` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL COMMENT 'Last update bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_actor_channels` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted, 0=active)';
ALTER TABLE `lupo_actor_channels` MODIFY COLUMN `deleted_ymdhis` bigint COMMENT 'Deletion bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_actor_channels` ADD PRIMARY KEY (`actor_channel_id`);
ALTER TABLE `lupo_actor_channels` ADD INDEX IF NOT EXISTS `idx_actor` (`actor_id`);
ALTER TABLE `lupo_actor_channels` ADD INDEX IF NOT EXISTS `idx_channel` (`channel_id`);
ALTER TABLE `lupo_actor_channels` ADD INDEX IF NOT EXISTS `idx_created` (`created_ymdhis`);
ALTER TABLE `lupo_actor_channels` ADD INDEX IF NOT EXISTS `idx_deleted` (`is_deleted`);
ALTER TABLE `lupo_actor_channels` ADD INDEX IF NOT EXISTS `idx_status` (`status`);
ALTER TABLE `lupo_actor_channels` ADD INDEX IF NOT EXISTS `idx_updated` (`updated_ymdhis`);
ALTER TABLE `lupo_actor_channels` ADD UNIQUE INDEX IF NOT EXISTS `unq_actor_channel` (`actor_id`, `channel_id`);

CREATE TABLE IF NOT EXISTS `lupo_actor_collections` (
  `actor_collection_id` bigint NOT NULL auto_increment,
  `actor_id` bigint NOT NULL COMMENT 'User, group, agent, or persona',
  `collection_id` bigint NOT NULL COMMENT 'Collection the actor has access to',
  `access_level` enum('owner','write','read') NOT NULL DEFAULT 'read',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when granted',
  `updated_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when updated',
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  `persistent_identity_json` json COMMENT 'RSHAP persistent identity metadata',
  `identity_signature` varchar(255) COMMENT 'Unique identity signature for handshake verification',
  `trust_level` enum('system','verified','standard','restricted','untrusted') DEFAULT 'standard' COMMENT 'Trust level for multi-agent interactions',
  `emotional_geometry_baseline` json COMMENT 'Baseline emotional geometry for agent interactions',
  `doctrine_alignment_version` varchar(20) DEFAULT '4.0.72' COMMENT 'Version of doctrine this actor aligns with',
  PRIMARY KEY (`actor_collection_id`),
  KEY `idx_access_level` (`access_level`),
  KEY `idx_actor` (`actor_id`),
  KEY `idx_collection` (`collection_id`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_identity_signature` (`identity_signature`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_trust_level` (`trust_level`)
);

ALTER TABLE `lupo_actor_collections` ADD COLUMN IF NOT EXISTS `actor_collection_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_actor_collections` ADD COLUMN IF NOT EXISTS `actor_id` bigint NOT NULL COMMENT 'User, group, agent, or persona';
ALTER TABLE `lupo_actor_collections` ADD COLUMN IF NOT EXISTS `collection_id` bigint NOT NULL COMMENT 'Collection the actor has access to';
ALTER TABLE `lupo_actor_collections` ADD COLUMN IF NOT EXISTS `access_level` enum('owner','write','read') NOT NULL DEFAULT 'read';
ALTER TABLE `lupo_actor_collections` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when granted';
ALTER TABLE `lupo_actor_collections` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when updated';
ALTER TABLE `lupo_actor_collections` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_actor_collections` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint;
ALTER TABLE `lupo_actor_collections` ADD COLUMN IF NOT EXISTS `persistent_identity_json` json COMMENT 'RSHAP persistent identity metadata';
ALTER TABLE `lupo_actor_collections` ADD COLUMN IF NOT EXISTS `identity_signature` varchar(255) COMMENT 'Unique identity signature for handshake verification';
ALTER TABLE `lupo_actor_collections` ADD COLUMN IF NOT EXISTS `trust_level` enum('system','verified','standard','restricted','untrusted') DEFAULT 'standard' COMMENT 'Trust level for multi-agent interactions';
ALTER TABLE `lupo_actor_collections` ADD COLUMN IF NOT EXISTS `emotional_geometry_baseline` json COMMENT 'Baseline emotional geometry for agent interactions';
ALTER TABLE `lupo_actor_collections` ADD COLUMN IF NOT EXISTS `doctrine_alignment_version` varchar(20) DEFAULT '4.0.72' COMMENT 'Version of doctrine this actor aligns with';
ALTER TABLE `lupo_actor_collections` MODIFY COLUMN `actor_collection_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_actor_collections` MODIFY COLUMN `actor_id` bigint NOT NULL COMMENT 'User, group, agent, or persona';
ALTER TABLE `lupo_actor_collections` MODIFY COLUMN `collection_id` bigint NOT NULL COMMENT 'Collection the actor has access to';
ALTER TABLE `lupo_actor_collections` MODIFY COLUMN `access_level` enum('owner','write','read') NOT NULL DEFAULT 'read';
ALTER TABLE `lupo_actor_collections` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when granted';
ALTER TABLE `lupo_actor_collections` MODIFY COLUMN `updated_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when updated';
ALTER TABLE `lupo_actor_collections` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_actor_collections` MODIFY COLUMN `deleted_ymdhis` bigint;
ALTER TABLE `lupo_actor_collections` MODIFY COLUMN `persistent_identity_json` json COMMENT 'RSHAP persistent identity metadata';
ALTER TABLE `lupo_actor_collections` MODIFY COLUMN `identity_signature` varchar(255) COMMENT 'Unique identity signature for handshake verification';
ALTER TABLE `lupo_actor_collections` MODIFY COLUMN `trust_level` enum('system','verified','standard','restricted','untrusted') DEFAULT 'standard' COMMENT 'Trust level for multi-agent interactions';
ALTER TABLE `lupo_actor_collections` MODIFY COLUMN `emotional_geometry_baseline` json COMMENT 'Baseline emotional geometry for agent interactions';
ALTER TABLE `lupo_actor_collections` MODIFY COLUMN `doctrine_alignment_version` varchar(20) DEFAULT '4.0.72' COMMENT 'Version of doctrine this actor aligns with';
ALTER TABLE `lupo_actor_collections` ADD PRIMARY KEY (`actor_collection_id`);
ALTER TABLE `lupo_actor_collections` ADD INDEX IF NOT EXISTS `idx_access_level` (`access_level`);
ALTER TABLE `lupo_actor_collections` ADD INDEX IF NOT EXISTS `idx_actor` (`actor_id`);
ALTER TABLE `lupo_actor_collections` ADD INDEX IF NOT EXISTS `idx_collection` (`collection_id`);
ALTER TABLE `lupo_actor_collections` ADD INDEX IF NOT EXISTS `idx_created_ymdhis` (`created_ymdhis`);
ALTER TABLE `lupo_actor_collections` ADD INDEX IF NOT EXISTS `idx_identity_signature` (`identity_signature`);
ALTER TABLE `lupo_actor_collections` ADD INDEX IF NOT EXISTS `idx_is_deleted` (`is_deleted`);
ALTER TABLE `lupo_actor_collections` ADD INDEX IF NOT EXISTS `idx_trust_level` (`trust_level`);

CREATE TABLE IF NOT EXISTS `lupo_actor_conflicts` (
  `actor_conflict_id` bigint NOT NULL auto_increment COMMENT 'Primary key for the conflict record',
  `domain_id` bigint NOT NULL DEFAULT 1 COMMENT 'Domain/tenant this conflict belongs to',
  `actor_a_id` bigint NOT NULL,
  `actor_b_id` bigint NOT NULL,
  `conflict_type` varchar(64) NOT NULL COMMENT 'Type/category of the conflict',
  `conflict_summary` text NOT NULL COMMENT 'Detailed description of the conflict',
  `resolution_status` enum('unresolved','resolved','ignored') NOT NULL DEFAULT 'unresolved' COMMENT 'Current status of the conflict resolution',
  `resolution_summary` text COMMENT 'How the conflict was resolved (if applicable)',
  `resolved_by` bigint COMMENT 'Actor who resolved the conflict (if applicable)',
  `resolved_ymdhis` bigint COMMENT 'When the conflict was resolved (YYYYMMDDHHMMSS)',
  `severity` enum('low','medium','high','critical') NOT NULL DEFAULT 'medium' COMMENT 'Severity level of the conflict',
  `context_json` json COMMENT 'Additional context about the conflict in JSON format',
  `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint (YYYYMMDDHHMMSS)',
  `updated_ymdhis` bigint NOT NULL COMMENT 'Last update bigint (YYYYMMDDHHMMSS)',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted, 0=active)',
  `deleted_ymdhis` bigint COMMENT 'Deletion bigint (YYYYMMDDHHMMSS)',
  PRIMARY KEY (`actor_conflict_id`),
  KEY `idx_agent_a` (`actor_a_id`),
  KEY `idx_agent_b` (`actor_b_id`),
  KEY `idx_agent_pair` (`actor_a_id`, `actor_b_id`),
  KEY `idx_conflict_type` (`conflict_type`),
  KEY `idx_created` (`created_ymdhis`),
  KEY `idx_deleted` (`is_deleted`),
  KEY `idx_domain` (`domain_id`),
  KEY `idx_resolved_ymdhis` (`resolved_ymdhis`),
  KEY `idx_severity` (`severity`),
  KEY `idx_status` (`resolution_status`),
  KEY `idx_updated` (`updated_ymdhis`)
);

ALTER TABLE `lupo_actor_conflicts` ADD COLUMN IF NOT EXISTS `actor_conflict_id` bigint NOT NULL auto_increment COMMENT 'Primary key for the conflict record';
ALTER TABLE `lupo_actor_conflicts` ADD COLUMN IF NOT EXISTS `domain_id` bigint NOT NULL DEFAULT 1 COMMENT 'Domain/tenant this conflict belongs to';
ALTER TABLE `lupo_actor_conflicts` ADD COLUMN IF NOT EXISTS `actor_a_id` bigint NOT NULL;
ALTER TABLE `lupo_actor_conflicts` ADD COLUMN IF NOT EXISTS `actor_b_id` bigint NOT NULL;
ALTER TABLE `lupo_actor_conflicts` ADD COLUMN IF NOT EXISTS `conflict_type` varchar(64) NOT NULL COMMENT 'Type/category of the conflict';
ALTER TABLE `lupo_actor_conflicts` ADD COLUMN IF NOT EXISTS `conflict_summary` text NOT NULL COMMENT 'Detailed description of the conflict';
ALTER TABLE `lupo_actor_conflicts` ADD COLUMN IF NOT EXISTS `resolution_status` enum('unresolved','resolved','ignored') NOT NULL DEFAULT 'unresolved' COMMENT 'Current status of the conflict resolution';
ALTER TABLE `lupo_actor_conflicts` ADD COLUMN IF NOT EXISTS `resolution_summary` text COMMENT 'How the conflict was resolved (if applicable)';
ALTER TABLE `lupo_actor_conflicts` ADD COLUMN IF NOT EXISTS `resolved_by` bigint COMMENT 'Actor who resolved the conflict (if applicable)';
ALTER TABLE `lupo_actor_conflicts` ADD COLUMN IF NOT EXISTS `resolved_ymdhis` bigint COMMENT 'When the conflict was resolved (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_actor_conflicts` ADD COLUMN IF NOT EXISTS `severity` enum('low','medium','high','critical') NOT NULL DEFAULT 'medium' COMMENT 'Severity level of the conflict';
ALTER TABLE `lupo_actor_conflicts` ADD COLUMN IF NOT EXISTS `context_json` json COMMENT 'Additional context about the conflict in JSON format';
ALTER TABLE `lupo_actor_conflicts` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_actor_conflicts` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL COMMENT 'Last update bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_actor_conflicts` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted, 0=active)';
ALTER TABLE `lupo_actor_conflicts` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint COMMENT 'Deletion bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_actor_conflicts` MODIFY COLUMN `actor_conflict_id` bigint NOT NULL auto_increment COMMENT 'Primary key for the conflict record';
ALTER TABLE `lupo_actor_conflicts` MODIFY COLUMN `domain_id` bigint NOT NULL DEFAULT 1 COMMENT 'Domain/tenant this conflict belongs to';
ALTER TABLE `lupo_actor_conflicts` MODIFY COLUMN `actor_a_id` bigint NOT NULL;
ALTER TABLE `lupo_actor_conflicts` MODIFY COLUMN `actor_b_id` bigint NOT NULL;
ALTER TABLE `lupo_actor_conflicts` MODIFY COLUMN `conflict_type` varchar(64) NOT NULL COMMENT 'Type/category of the conflict';
ALTER TABLE `lupo_actor_conflicts` MODIFY COLUMN `conflict_summary` text NOT NULL COMMENT 'Detailed description of the conflict';
ALTER TABLE `lupo_actor_conflicts` MODIFY COLUMN `resolution_status` enum('unresolved','resolved','ignored') NOT NULL DEFAULT 'unresolved' COMMENT 'Current status of the conflict resolution';
ALTER TABLE `lupo_actor_conflicts` MODIFY COLUMN `resolution_summary` text COMMENT 'How the conflict was resolved (if applicable)';
ALTER TABLE `lupo_actor_conflicts` MODIFY COLUMN `resolved_by` bigint COMMENT 'Actor who resolved the conflict (if applicable)';
ALTER TABLE `lupo_actor_conflicts` MODIFY COLUMN `resolved_ymdhis` bigint COMMENT 'When the conflict was resolved (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_actor_conflicts` MODIFY COLUMN `severity` enum('low','medium','high','critical') NOT NULL DEFAULT 'medium' COMMENT 'Severity level of the conflict';
ALTER TABLE `lupo_actor_conflicts` MODIFY COLUMN `context_json` json COMMENT 'Additional context about the conflict in JSON format';
ALTER TABLE `lupo_actor_conflicts` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_actor_conflicts` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL COMMENT 'Last update bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_actor_conflicts` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted, 0=active)';
ALTER TABLE `lupo_actor_conflicts` MODIFY COLUMN `deleted_ymdhis` bigint COMMENT 'Deletion bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_actor_conflicts` ADD PRIMARY KEY (`actor_conflict_id`);
ALTER TABLE `lupo_actor_conflicts` ADD INDEX IF NOT EXISTS `idx_agent_a` (`actor_a_id`);
ALTER TABLE `lupo_actor_conflicts` ADD INDEX IF NOT EXISTS `idx_agent_b` (`actor_b_id`);
ALTER TABLE `lupo_actor_conflicts` ADD INDEX IF NOT EXISTS `idx_agent_pair` (`actor_a_id`, `actor_b_id`);
ALTER TABLE `lupo_actor_conflicts` ADD INDEX IF NOT EXISTS `idx_conflict_type` (`conflict_type`);
ALTER TABLE `lupo_actor_conflicts` ADD INDEX IF NOT EXISTS `idx_created` (`created_ymdhis`);
ALTER TABLE `lupo_actor_conflicts` ADD INDEX IF NOT EXISTS `idx_deleted` (`is_deleted`);
ALTER TABLE `lupo_actor_conflicts` ADD INDEX IF NOT EXISTS `idx_domain` (`domain_id`);
ALTER TABLE `lupo_actor_conflicts` ADD INDEX IF NOT EXISTS `idx_resolved_ymdhis` (`resolved_ymdhis`);
ALTER TABLE `lupo_actor_conflicts` ADD INDEX IF NOT EXISTS `idx_severity` (`severity`);
ALTER TABLE `lupo_actor_conflicts` ADD INDEX IF NOT EXISTS `idx_status` (`resolution_status`);
ALTER TABLE `lupo_actor_conflicts` ADD INDEX IF NOT EXISTS `idx_updated` (`updated_ymdhis`);

CREATE TABLE IF NOT EXISTS `lupo_actor_departments` (
  `actor_department_id` bigint NOT NULL auto_increment,
  `actor_id` bigint NOT NULL,
  `department_id` bigint NOT NULL,
  `title` varchar(64),
  `created_ymdhis` char(14) NOT NULL,
  `updated_ymdhis` char(14) NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` char(14),
  PRIMARY KEY (`actor_department_id`),
  KEY `idx_actor` (`actor_id`),
  KEY `idx_department` (`department_id`)
);

ALTER TABLE `lupo_actor_departments` ADD COLUMN IF NOT EXISTS `actor_department_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_actor_departments` ADD COLUMN IF NOT EXISTS `actor_id` bigint NOT NULL;
ALTER TABLE `lupo_actor_departments` ADD COLUMN IF NOT EXISTS `department_id` bigint NOT NULL;
ALTER TABLE `lupo_actor_departments` ADD COLUMN IF NOT EXISTS `title` varchar(64);
ALTER TABLE `lupo_actor_departments` ADD COLUMN IF NOT EXISTS `created_ymdhis` char(14) NOT NULL;
ALTER TABLE `lupo_actor_departments` ADD COLUMN IF NOT EXISTS `updated_ymdhis` char(14) NOT NULL;
ALTER TABLE `lupo_actor_departments` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_actor_departments` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` char(14);
ALTER TABLE `lupo_actor_departments` MODIFY COLUMN `actor_department_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_actor_departments` MODIFY COLUMN `actor_id` bigint NOT NULL;
ALTER TABLE `lupo_actor_departments` MODIFY COLUMN `department_id` bigint NOT NULL;
ALTER TABLE `lupo_actor_departments` MODIFY COLUMN `title` varchar(64);
ALTER TABLE `lupo_actor_departments` MODIFY COLUMN `created_ymdhis` char(14) NOT NULL;
ALTER TABLE `lupo_actor_departments` MODIFY COLUMN `updated_ymdhis` char(14) NOT NULL;
ALTER TABLE `lupo_actor_departments` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_actor_departments` MODIFY COLUMN `deleted_ymdhis` char(14);
ALTER TABLE `lupo_actor_departments` ADD PRIMARY KEY (`actor_department_id`);
ALTER TABLE `lupo_actor_departments` ADD INDEX IF NOT EXISTS `idx_actor` (`actor_id`);
ALTER TABLE `lupo_actor_departments` ADD INDEX IF NOT EXISTS `idx_department` (`department_id`);

CREATE TABLE IF NOT EXISTS `lupo_actor_edges` (
  `actor_edge_id` bigint NOT NULL auto_increment,
  `domain_id` bigint NOT NULL COMMENT 'Domain scope for this relationship',
  `source_actor_id` bigint NOT NULL COMMENT 'Source agent of the relationship',
  `target_actor_id` bigint NOT NULL COMMENT 'Target agent of the relationship',
  `edge_type` varchar(100) NOT NULL COMMENT 'Type of relationship (e.g., "collaborates_with", "critiques", "balances")',
  `weight` float DEFAULT 1 COMMENT 'Strength or weight of the relationship',
  `properties` text COMMENT 'JSON or TOON formatted metadata for the relationship',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
  `updated_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when last updated',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted',
  PRIMARY KEY (`actor_edge_id`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_domain_id` (`domain_id`),
  KEY `idx_edge_source_relationship` (`source_actor_id`, `edge_type`),
  KEY `idx_edge_target_relationship` (`target_actor_id`, `edge_type`),
  KEY `idx_edge_type` (`edge_type`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_source_agent` (`source_actor_id`),
  KEY `idx_source_target` (`source_actor_id`, `target_actor_id`),
  KEY `idx_target_agent` (`target_actor_id`),
  KEY `idx_updated_ymdhis` (`updated_ymdhis`),
  UNIQUE KEY `unique_agent_edge` (`domain_id`, `source_actor_id`, `target_actor_id`, `edge_type`)
);

ALTER TABLE `lupo_actor_edges` ADD COLUMN IF NOT EXISTS `actor_edge_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_actor_edges` ADD COLUMN IF NOT EXISTS `domain_id` bigint NOT NULL COMMENT 'Domain scope for this relationship';
ALTER TABLE `lupo_actor_edges` ADD COLUMN IF NOT EXISTS `source_actor_id` bigint NOT NULL COMMENT 'Source agent of the relationship';
ALTER TABLE `lupo_actor_edges` ADD COLUMN IF NOT EXISTS `target_actor_id` bigint NOT NULL COMMENT 'Target agent of the relationship';
ALTER TABLE `lupo_actor_edges` ADD COLUMN IF NOT EXISTS `edge_type` varchar(100) NOT NULL COMMENT 'Type of relationship (e.g., "collaborates_with", "critiques", "balances")';
ALTER TABLE `lupo_actor_edges` ADD COLUMN IF NOT EXISTS `weight` float DEFAULT 1 COMMENT 'Strength or weight of the relationship';
ALTER TABLE `lupo_actor_edges` ADD COLUMN IF NOT EXISTS `properties` text COMMENT 'JSON or TOON formatted metadata for the relationship';
ALTER TABLE `lupo_actor_edges` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created';
ALTER TABLE `lupo_actor_edges` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when last updated';
ALTER TABLE `lupo_actor_edges` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted';
ALTER TABLE `lupo_actor_edges` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted';
ALTER TABLE `lupo_actor_edges` MODIFY COLUMN `actor_edge_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_actor_edges` MODIFY COLUMN `domain_id` bigint NOT NULL COMMENT 'Domain scope for this relationship';
ALTER TABLE `lupo_actor_edges` MODIFY COLUMN `source_actor_id` bigint NOT NULL COMMENT 'Source agent of the relationship';
ALTER TABLE `lupo_actor_edges` MODIFY COLUMN `target_actor_id` bigint NOT NULL COMMENT 'Target agent of the relationship';
ALTER TABLE `lupo_actor_edges` MODIFY COLUMN `edge_type` varchar(100) NOT NULL COMMENT 'Type of relationship (e.g., "collaborates_with", "critiques", "balances")';
ALTER TABLE `lupo_actor_edges` MODIFY COLUMN `weight` float DEFAULT 1 COMMENT 'Strength or weight of the relationship';
ALTER TABLE `lupo_actor_edges` MODIFY COLUMN `properties` text COMMENT 'JSON or TOON formatted metadata for the relationship';
ALTER TABLE `lupo_actor_edges` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created';
ALTER TABLE `lupo_actor_edges` MODIFY COLUMN `updated_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when last updated';
ALTER TABLE `lupo_actor_edges` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted';
ALTER TABLE `lupo_actor_edges` MODIFY COLUMN `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted';
ALTER TABLE `lupo_actor_edges` ADD PRIMARY KEY (`actor_edge_id`);
ALTER TABLE `lupo_actor_edges` ADD INDEX IF NOT EXISTS `idx_created_ymdhis` (`created_ymdhis`);
ALTER TABLE `lupo_actor_edges` ADD INDEX IF NOT EXISTS `idx_domain_id` (`domain_id`);
ALTER TABLE `lupo_actor_edges` ADD INDEX IF NOT EXISTS `idx_edge_source_relationship` (`source_actor_id`, `edge_type`);
ALTER TABLE `lupo_actor_edges` ADD INDEX IF NOT EXISTS `idx_edge_target_relationship` (`target_actor_id`, `edge_type`);
ALTER TABLE `lupo_actor_edges` ADD INDEX IF NOT EXISTS `idx_edge_type` (`edge_type`);
ALTER TABLE `lupo_actor_edges` ADD INDEX IF NOT EXISTS `idx_is_deleted` (`is_deleted`);
ALTER TABLE `lupo_actor_edges` ADD INDEX IF NOT EXISTS `idx_source_agent` (`source_actor_id`);
ALTER TABLE `lupo_actor_edges` ADD INDEX IF NOT EXISTS `idx_source_target` (`source_actor_id`, `target_actor_id`);
ALTER TABLE `lupo_actor_edges` ADD INDEX IF NOT EXISTS `idx_target_agent` (`target_actor_id`);
ALTER TABLE `lupo_actor_edges` ADD INDEX IF NOT EXISTS `idx_updated_ymdhis` (`updated_ymdhis`);
ALTER TABLE `lupo_actor_edges` ADD UNIQUE INDEX IF NOT EXISTS `unique_agent_edge` (`domain_id`, `source_actor_id`, `target_actor_id`, `edge_type`);

CREATE TABLE IF NOT EXISTS `lupo_actor_events` (
  `actor_event_id` bigint NOT NULL auto_increment COMMENT 'Primary key for actor event',
  `actor_id` bigint NOT NULL COMMENT 'Actor ID from lupo_actors',
  `session_id` varchar(255) COMMENT 'Session identifier',
  `tab_id` varchar(255) COMMENT 'Tab identifier',
  `world_id` bigint COMMENT 'World context ID',
  `world_key` varchar(255) COMMENT 'World context key',
  `world_type` varchar(50) COMMENT 'World context type',
  `event_type` varchar(100) NOT NULL COMMENT 'Type of actor event',
  `event_data` json COMMENT 'Event-specific data',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS',
  PRIMARY KEY (`actor_event_id`),
  KEY `idx_actor_event_type` (`actor_id`, `event_type`),
  KEY `idx_actor_id` (`actor_id`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_event_type` (`event_type`),
  KEY `idx_session_id` (`session_id`),
  KEY `idx_tab_id` (`tab_id`),
  KEY `idx_world_id` (`world_id`)
);

ALTER TABLE `lupo_actor_events` ADD COLUMN IF NOT EXISTS `actor_event_id` bigint NOT NULL auto_increment COMMENT 'Primary key for actor event';
ALTER TABLE `lupo_actor_events` ADD COLUMN IF NOT EXISTS `actor_id` bigint NOT NULL COMMENT 'Actor ID from lupo_actors';
ALTER TABLE `lupo_actor_events` ADD COLUMN IF NOT EXISTS `session_id` varchar(255) COMMENT 'Session identifier';
ALTER TABLE `lupo_actor_events` ADD COLUMN IF NOT EXISTS `tab_id` varchar(255) COMMENT 'Tab identifier';
ALTER TABLE `lupo_actor_events` ADD COLUMN IF NOT EXISTS `world_id` bigint COMMENT 'World context ID';
ALTER TABLE `lupo_actor_events` ADD COLUMN IF NOT EXISTS `world_key` varchar(255) COMMENT 'World context key';
ALTER TABLE `lupo_actor_events` ADD COLUMN IF NOT EXISTS `world_type` varchar(50) COMMENT 'World context type';
ALTER TABLE `lupo_actor_events` ADD COLUMN IF NOT EXISTS `event_type` varchar(100) NOT NULL COMMENT 'Type of actor event';
ALTER TABLE `lupo_actor_events` ADD COLUMN IF NOT EXISTS `event_data` json COMMENT 'Event-specific data';
ALTER TABLE `lupo_actor_events` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS';
ALTER TABLE `lupo_actor_events` MODIFY COLUMN `actor_event_id` bigint NOT NULL auto_increment COMMENT 'Primary key for actor event';
ALTER TABLE `lupo_actor_events` MODIFY COLUMN `actor_id` bigint NOT NULL COMMENT 'Actor ID from lupo_actors';
ALTER TABLE `lupo_actor_events` MODIFY COLUMN `session_id` varchar(255) COMMENT 'Session identifier';
ALTER TABLE `lupo_actor_events` MODIFY COLUMN `tab_id` varchar(255) COMMENT 'Tab identifier';
ALTER TABLE `lupo_actor_events` MODIFY COLUMN `world_id` bigint COMMENT 'World context ID';
ALTER TABLE `lupo_actor_events` MODIFY COLUMN `world_key` varchar(255) COMMENT 'World context key';
ALTER TABLE `lupo_actor_events` MODIFY COLUMN `world_type` varchar(50) COMMENT 'World context type';
ALTER TABLE `lupo_actor_events` MODIFY COLUMN `event_type` varchar(100) NOT NULL COMMENT 'Type of actor event';
ALTER TABLE `lupo_actor_events` MODIFY COLUMN `event_data` json COMMENT 'Event-specific data';
ALTER TABLE `lupo_actor_events` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS';
ALTER TABLE `lupo_actor_events` ADD PRIMARY KEY (`actor_event_id`);
ALTER TABLE `lupo_actor_events` ADD INDEX IF NOT EXISTS `idx_actor_event_type` (`actor_id`, `event_type`);
ALTER TABLE `lupo_actor_events` ADD INDEX IF NOT EXISTS `idx_actor_id` (`actor_id`);
ALTER TABLE `lupo_actor_events` ADD INDEX IF NOT EXISTS `idx_created_ymdhis` (`created_ymdhis`);
ALTER TABLE `lupo_actor_events` ADD INDEX IF NOT EXISTS `idx_event_type` (`event_type`);
ALTER TABLE `lupo_actor_events` ADD INDEX IF NOT EXISTS `idx_session_id` (`session_id`);
ALTER TABLE `lupo_actor_events` ADD INDEX IF NOT EXISTS `idx_tab_id` (`tab_id`);
ALTER TABLE `lupo_actor_events` ADD INDEX IF NOT EXISTS `idx_world_id` (`world_id`);

CREATE TABLE IF NOT EXISTS `lupo_actor_group_membership` (
  `actor_group_membership_id` bigint NOT NULL auto_increment COMMENT 'Reference to actors.actor_id',
  `group_id` bigint NOT NULL COMMENT 'Reference to groups.group_id',
  `domain_id` bigint NOT NULL COMMENT 'Domain context for this membership',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when membership was created',
  `created_by` bigint COMMENT 'actor_id who created this membership',
  `expires_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when membership expires (NULL = never)',
  `is_active` tinyint NOT NULL DEFAULT 1 COMMENT '1 = active, 0 = inactive',
  `role` varchar(50) DEFAULT 'member' COMMENT 'Role/position within the group',
  PRIMARY KEY (`actor_group_membership_id`),
  KEY `idx_actor_domain` (`actor_group_membership_id`, `domain_id`),
  KEY `idx_expires` (`expires_ymdhis`),
  KEY `idx_group_domain` (`group_id`, `domain_id`),
  KEY `idx_is_active` (`is_active`)
);

ALTER TABLE `lupo_actor_group_membership` ADD COLUMN IF NOT EXISTS `actor_group_membership_id` bigint NOT NULL auto_increment COMMENT 'Reference to actors.actor_id';
ALTER TABLE `lupo_actor_group_membership` ADD COLUMN IF NOT EXISTS `group_id` bigint NOT NULL COMMENT 'Reference to groups.group_id';
ALTER TABLE `lupo_actor_group_membership` ADD COLUMN IF NOT EXISTS `domain_id` bigint NOT NULL COMMENT 'Domain context for this membership';
ALTER TABLE `lupo_actor_group_membership` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when membership was created';
ALTER TABLE `lupo_actor_group_membership` ADD COLUMN IF NOT EXISTS `created_by` bigint COMMENT 'actor_id who created this membership';
ALTER TABLE `lupo_actor_group_membership` ADD COLUMN IF NOT EXISTS `expires_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when membership expires (NULL = never)';
ALTER TABLE `lupo_actor_group_membership` ADD COLUMN IF NOT EXISTS `is_active` tinyint NOT NULL DEFAULT 1 COMMENT '1 = active, 0 = inactive';
ALTER TABLE `lupo_actor_group_membership` ADD COLUMN IF NOT EXISTS `role` varchar(50) DEFAULT 'member' COMMENT 'Role/position within the group';
ALTER TABLE `lupo_actor_group_membership` MODIFY COLUMN `actor_group_membership_id` bigint NOT NULL auto_increment COMMENT 'Reference to actors.actor_id';
ALTER TABLE `lupo_actor_group_membership` MODIFY COLUMN `group_id` bigint NOT NULL COMMENT 'Reference to groups.group_id';
ALTER TABLE `lupo_actor_group_membership` MODIFY COLUMN `domain_id` bigint NOT NULL COMMENT 'Domain context for this membership';
ALTER TABLE `lupo_actor_group_membership` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when membership was created';
ALTER TABLE `lupo_actor_group_membership` MODIFY COLUMN `created_by` bigint COMMENT 'actor_id who created this membership';
ALTER TABLE `lupo_actor_group_membership` MODIFY COLUMN `expires_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when membership expires (NULL = never)';
ALTER TABLE `lupo_actor_group_membership` MODIFY COLUMN `is_active` tinyint NOT NULL DEFAULT 1 COMMENT '1 = active, 0 = inactive';
ALTER TABLE `lupo_actor_group_membership` MODIFY COLUMN `role` varchar(50) DEFAULT 'member' COMMENT 'Role/position within the group';
ALTER TABLE `lupo_actor_group_membership` ADD PRIMARY KEY (`actor_group_membership_id`);
ALTER TABLE `lupo_actor_group_membership` ADD INDEX IF NOT EXISTS `idx_actor_domain` (`actor_group_membership_id`, `domain_id`);
ALTER TABLE `lupo_actor_group_membership` ADD INDEX IF NOT EXISTS `idx_expires` (`expires_ymdhis`);
ALTER TABLE `lupo_actor_group_membership` ADD INDEX IF NOT EXISTS `idx_group_domain` (`group_id`, `domain_id`);
ALTER TABLE `lupo_actor_group_membership` ADD INDEX IF NOT EXISTS `idx_is_active` (`is_active`);

CREATE TABLE IF NOT EXISTS `lupo_actor_handshakes` (
  `actor_handshake_id` bigint NOT NULL auto_increment,
  `actor_id` bigint NOT NULL,
  `actor_type` varchar(32) NOT NULL COMMENT 'human|ai|system',
  `utc_timestamp` bigint NOT NULL COMMENT 'YYYYMMDDHHMMSS',
  `purpose` varchar(500),
  `constraints_json` json,
  `forbidden_actions_json` json,
  `context` text,
  `expires_utc` bigint COMMENT 'YYYYMMDDHHMMSS',
  `created_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (`actor_handshake_id`),
  KEY `idx_actor_id` (`actor_id`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_utc_timestamp` (`utc_timestamp`)
);

ALTER TABLE `lupo_actor_handshakes` ADD COLUMN IF NOT EXISTS `actor_handshake_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_actor_handshakes` ADD COLUMN IF NOT EXISTS `actor_id` bigint NOT NULL;
ALTER TABLE `lupo_actor_handshakes` ADD COLUMN IF NOT EXISTS `actor_type` varchar(32) NOT NULL COMMENT 'human|ai|system';
ALTER TABLE `lupo_actor_handshakes` ADD COLUMN IF NOT EXISTS `utc_timestamp` bigint NOT NULL COMMENT 'YYYYMMDDHHMMSS';
ALTER TABLE `lupo_actor_handshakes` ADD COLUMN IF NOT EXISTS `purpose` varchar(500);
ALTER TABLE `lupo_actor_handshakes` ADD COLUMN IF NOT EXISTS `constraints_json` json;
ALTER TABLE `lupo_actor_handshakes` ADD COLUMN IF NOT EXISTS `forbidden_actions_json` json;
ALTER TABLE `lupo_actor_handshakes` ADD COLUMN IF NOT EXISTS `context` text;
ALTER TABLE `lupo_actor_handshakes` ADD COLUMN IF NOT EXISTS `expires_utc` bigint COMMENT 'YYYYMMDDHHMMSS';
ALTER TABLE `lupo_actor_handshakes` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_actor_handshakes` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_actor_handshakes` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint;
ALTER TABLE `lupo_actor_handshakes` MODIFY COLUMN `actor_handshake_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_actor_handshakes` MODIFY COLUMN `actor_id` bigint NOT NULL;
ALTER TABLE `lupo_actor_handshakes` MODIFY COLUMN `actor_type` varchar(32) NOT NULL COMMENT 'human|ai|system';
ALTER TABLE `lupo_actor_handshakes` MODIFY COLUMN `utc_timestamp` bigint NOT NULL COMMENT 'YYYYMMDDHHMMSS';
ALTER TABLE `lupo_actor_handshakes` MODIFY COLUMN `purpose` varchar(500);
ALTER TABLE `lupo_actor_handshakes` MODIFY COLUMN `constraints_json` json;
ALTER TABLE `lupo_actor_handshakes` MODIFY COLUMN `forbidden_actions_json` json;
ALTER TABLE `lupo_actor_handshakes` MODIFY COLUMN `context` text;
ALTER TABLE `lupo_actor_handshakes` MODIFY COLUMN `expires_utc` bigint COMMENT 'YYYYMMDDHHMMSS';
ALTER TABLE `lupo_actor_handshakes` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_actor_handshakes` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_actor_handshakes` MODIFY COLUMN `deleted_ymdhis` bigint;
ALTER TABLE `lupo_actor_handshakes` ADD PRIMARY KEY (`actor_handshake_id`);
ALTER TABLE `lupo_actor_handshakes` ADD INDEX IF NOT EXISTS `idx_actor_id` (`actor_id`);
ALTER TABLE `lupo_actor_handshakes` ADD INDEX IF NOT EXISTS `idx_is_deleted` (`is_deleted`);
ALTER TABLE `lupo_actor_handshakes` ADD INDEX IF NOT EXISTS `idx_utc_timestamp` (`utc_timestamp`);

CREATE TABLE IF NOT EXISTS `lupo_actor_meta` (
  `actor_meta_id` bigint unsigned NOT NULL auto_increment,
  `actor_id` bigint unsigned NOT NULL,
  `meta_type` varchar(64) NOT NULL,
  `meta_key` varchar(255) NOT NULL,
  `meta_value` text NOT NULL,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  PRIMARY KEY (`actor_meta_id`),
  KEY `actor_id` (`actor_id`),
  KEY `meta_key` (`meta_key`),
  KEY `meta_type` (`meta_type`)
);

ALTER TABLE `lupo_actor_meta` ADD COLUMN IF NOT EXISTS `actor_meta_id` bigint unsigned NOT NULL auto_increment;
ALTER TABLE `lupo_actor_meta` ADD COLUMN IF NOT EXISTS `actor_id` bigint unsigned NOT NULL;
ALTER TABLE `lupo_actor_meta` ADD COLUMN IF NOT EXISTS `meta_type` varchar(64) NOT NULL;
ALTER TABLE `lupo_actor_meta` ADD COLUMN IF NOT EXISTS `meta_key` varchar(255) NOT NULL;
ALTER TABLE `lupo_actor_meta` ADD COLUMN IF NOT EXISTS `meta_value` text NOT NULL;
ALTER TABLE `lupo_actor_meta` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_actor_meta` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_actor_meta` MODIFY COLUMN `actor_meta_id` bigint unsigned NOT NULL auto_increment;
ALTER TABLE `lupo_actor_meta` MODIFY COLUMN `actor_id` bigint unsigned NOT NULL;
ALTER TABLE `lupo_actor_meta` MODIFY COLUMN `meta_type` varchar(64) NOT NULL;
ALTER TABLE `lupo_actor_meta` MODIFY COLUMN `meta_key` varchar(255) NOT NULL;
ALTER TABLE `lupo_actor_meta` MODIFY COLUMN `meta_value` text NOT NULL;
ALTER TABLE `lupo_actor_meta` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_actor_meta` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_actor_meta` ADD PRIMARY KEY (`actor_meta_id`);
ALTER TABLE `lupo_actor_meta` ADD INDEX IF NOT EXISTS `actor_id` (`actor_id`);
ALTER TABLE `lupo_actor_meta` ADD INDEX IF NOT EXISTS `meta_key` (`meta_key`);
ALTER TABLE `lupo_actor_meta` ADD INDEX IF NOT EXISTS `meta_type` (`meta_type`);

CREATE TABLE IF NOT EXISTS `lupo_actor_moods` (
  `actor_id` bigint unsigned NOT NULL,
  `mood_r` tinyint NOT NULL,
  `mood_g` tinyint NOT NULL,
  `mood_b` tinyint NOT NULL,
  `timestamp_utc` bigint unsigned NOT NULL
);

ALTER TABLE `lupo_actor_moods` ADD COLUMN IF NOT EXISTS `actor_id` bigint unsigned NOT NULL;
ALTER TABLE `lupo_actor_moods` ADD COLUMN IF NOT EXISTS `mood_r` tinyint NOT NULL;
ALTER TABLE `lupo_actor_moods` ADD COLUMN IF NOT EXISTS `mood_g` tinyint NOT NULL;
ALTER TABLE `lupo_actor_moods` ADD COLUMN IF NOT EXISTS `mood_b` tinyint NOT NULL;
ALTER TABLE `lupo_actor_moods` ADD COLUMN IF NOT EXISTS `timestamp_utc` bigint unsigned NOT NULL;
ALTER TABLE `lupo_actor_moods` MODIFY COLUMN `actor_id` bigint unsigned NOT NULL;
ALTER TABLE `lupo_actor_moods` MODIFY COLUMN `mood_r` tinyint NOT NULL;
ALTER TABLE `lupo_actor_moods` MODIFY COLUMN `mood_g` tinyint NOT NULL;
ALTER TABLE `lupo_actor_moods` MODIFY COLUMN `mood_b` tinyint NOT NULL;
ALTER TABLE `lupo_actor_moods` MODIFY COLUMN `timestamp_utc` bigint unsigned NOT NULL;

CREATE TABLE IF NOT EXISTS `lupo_actor_object_edges` (
  `actor_object_edge_id` bigint NOT NULL auto_increment,
  `actor_id` bigint NOT NULL,
  `target_table` varchar(100) NOT NULL COMMENT 'e.g., lupo_contents, lupo_unified_truth_items, lupo_hashtags, lupo_topics, lupo_channels, etc.',
  `target_id` bigint NOT NULL,
  `edge_type` varchar(50) NOT NULL COMMENT 'e.g., read, liked, disliked, created, commented_on, shared_from, etc.',
  `properties_json` json,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  PRIMARY KEY (`actor_object_edge_id`),
  KEY `idx_actor_edge_type` (`actor_id`, `edge_type`),
  KEY `idx_target_lookup` (`target_table`, `target_id`),
  UNIQUE KEY `uniq_actor_target_type` (`actor_id`, `target_table`, `target_id`, `edge_type`)
);

ALTER TABLE `lupo_actor_object_edges` ADD COLUMN IF NOT EXISTS `actor_object_edge_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_actor_object_edges` ADD COLUMN IF NOT EXISTS `actor_id` bigint NOT NULL;
ALTER TABLE `lupo_actor_object_edges` ADD COLUMN IF NOT EXISTS `target_table` varchar(100) NOT NULL COMMENT 'e.g., lupo_contents, lupo_unified_truth_items, lupo_hashtags, lupo_topics, lupo_channels, etc.';
ALTER TABLE `lupo_actor_object_edges` ADD COLUMN IF NOT EXISTS `target_id` bigint NOT NULL;
ALTER TABLE `lupo_actor_object_edges` ADD COLUMN IF NOT EXISTS `edge_type` varchar(50) NOT NULL COMMENT 'e.g., read, liked, disliked, created, commented_on, shared_from, etc.';
ALTER TABLE `lupo_actor_object_edges` ADD COLUMN IF NOT EXISTS `properties_json` json;
ALTER TABLE `lupo_actor_object_edges` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_actor_object_edges` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_actor_object_edges` MODIFY COLUMN `actor_object_edge_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_actor_object_edges` MODIFY COLUMN `actor_id` bigint NOT NULL;
ALTER TABLE `lupo_actor_object_edges` MODIFY COLUMN `target_table` varchar(100) NOT NULL COMMENT 'e.g., lupo_contents, lupo_unified_truth_items, lupo_hashtags, lupo_topics, lupo_channels, etc.';
ALTER TABLE `lupo_actor_object_edges` MODIFY COLUMN `target_id` bigint NOT NULL;
ALTER TABLE `lupo_actor_object_edges` MODIFY COLUMN `edge_type` varchar(50) NOT NULL COMMENT 'e.g., read, liked, disliked, created, commented_on, shared_from, etc.';
ALTER TABLE `lupo_actor_object_edges` MODIFY COLUMN `properties_json` json;
ALTER TABLE `lupo_actor_object_edges` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_actor_object_edges` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_actor_object_edges` ADD PRIMARY KEY (`actor_object_edge_id`);
ALTER TABLE `lupo_actor_object_edges` ADD INDEX IF NOT EXISTS `idx_actor_edge_type` (`actor_id`, `edge_type`);
ALTER TABLE `lupo_actor_object_edges` ADD INDEX IF NOT EXISTS `idx_target_lookup` (`target_table`, `target_id`);
ALTER TABLE `lupo_actor_object_edges` ADD UNIQUE INDEX IF NOT EXISTS `uniq_actor_target_type` (`actor_id`, `target_table`, `target_id`, `edge_type`);

CREATE TABLE IF NOT EXISTS `lupo_actor_persona_relationships` (
  `relationship_id` bigint NOT NULL auto_increment,
  `actor_id` bigint NOT NULL,
  `persona_id` bigint NOT NULL,
  `relationship_type` varchar(100) NOT NULL,
  `relationship_strength` decimal(5,2),
  `relationship_context` json,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  PRIMARY KEY (`relationship_id`),
  KEY `idx_actor_id` (`actor_id`),
  KEY `idx_persona_id` (`persona_id`),
  KEY `idx_relationship_type` (`relationship_type`)
);

ALTER TABLE `lupo_actor_persona_relationships` ADD COLUMN IF NOT EXISTS `relationship_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_actor_persona_relationships` ADD COLUMN IF NOT EXISTS `actor_id` bigint NOT NULL;
ALTER TABLE `lupo_actor_persona_relationships` ADD COLUMN IF NOT EXISTS `persona_id` bigint NOT NULL;
ALTER TABLE `lupo_actor_persona_relationships` ADD COLUMN IF NOT EXISTS `relationship_type` varchar(100) NOT NULL;
ALTER TABLE `lupo_actor_persona_relationships` ADD COLUMN IF NOT EXISTS `relationship_strength` decimal(5,2);
ALTER TABLE `lupo_actor_persona_relationships` ADD COLUMN IF NOT EXISTS `relationship_context` json;
ALTER TABLE `lupo_actor_persona_relationships` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_actor_persona_relationships` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_actor_persona_relationships` MODIFY COLUMN `relationship_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_actor_persona_relationships` MODIFY COLUMN `actor_id` bigint NOT NULL;
ALTER TABLE `lupo_actor_persona_relationships` MODIFY COLUMN `persona_id` bigint NOT NULL;
ALTER TABLE `lupo_actor_persona_relationships` MODIFY COLUMN `relationship_type` varchar(100) NOT NULL;
ALTER TABLE `lupo_actor_persona_relationships` MODIFY COLUMN `relationship_strength` decimal(5,2);
ALTER TABLE `lupo_actor_persona_relationships` MODIFY COLUMN `relationship_context` json;
ALTER TABLE `lupo_actor_persona_relationships` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_actor_persona_relationships` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_actor_persona_relationships` ADD PRIMARY KEY (`relationship_id`);
ALTER TABLE `lupo_actor_persona_relationships` ADD INDEX IF NOT EXISTS `idx_actor_id` (`actor_id`);
ALTER TABLE `lupo_actor_persona_relationships` ADD INDEX IF NOT EXISTS `idx_persona_id` (`persona_id`);
ALTER TABLE `lupo_actor_persona_relationships` ADD INDEX IF NOT EXISTS `idx_relationship_type` (`relationship_type`);

CREATE TABLE IF NOT EXISTS `lupo_actor_properties` (
  `actor_property_id` bigint NOT NULL auto_increment,
  `actor_type` varchar(32) NOT NULL COMMENT 'Type of the entity',
  `actor_id` bigint NOT NULL COMMENT 'ID of the entity',
  `property_key` varchar(64) NOT NULL COMMENT 'Property key/name',
  `property_value` text COMMENT 'Property value',
  `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint (YYYYMMDDHHMMSS)',
  `updated_ymdhis` bigint NOT NULL COMMENT 'Last update bigint (YYYYMMDDHHMMSS)',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted, 0=active)',
  `deleted_ymdhis` bigint COMMENT 'Deletion bigint (YYYYMMDDHHMMSS)',
  PRIMARY KEY (`actor_property_id`),
  KEY `idx_entity` (`actor_type`, `actor_id`),
  KEY `idx_property` (`property_key`)
);

ALTER TABLE `lupo_actor_properties` ADD COLUMN IF NOT EXISTS `actor_property_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_actor_properties` ADD COLUMN IF NOT EXISTS `actor_type` varchar(32) NOT NULL COMMENT 'Type of the entity';
ALTER TABLE `lupo_actor_properties` ADD COLUMN IF NOT EXISTS `actor_id` bigint NOT NULL COMMENT 'ID of the entity';
ALTER TABLE `lupo_actor_properties` ADD COLUMN IF NOT EXISTS `property_key` varchar(64) NOT NULL COMMENT 'Property key/name';
ALTER TABLE `lupo_actor_properties` ADD COLUMN IF NOT EXISTS `property_value` text COMMENT 'Property value';
ALTER TABLE `lupo_actor_properties` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_actor_properties` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL COMMENT 'Last update bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_actor_properties` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted, 0=active)';
ALTER TABLE `lupo_actor_properties` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint COMMENT 'Deletion bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_actor_properties` MODIFY COLUMN `actor_property_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_actor_properties` MODIFY COLUMN `actor_type` varchar(32) NOT NULL COMMENT 'Type of the entity';
ALTER TABLE `lupo_actor_properties` MODIFY COLUMN `actor_id` bigint NOT NULL COMMENT 'ID of the entity';
ALTER TABLE `lupo_actor_properties` MODIFY COLUMN `property_key` varchar(64) NOT NULL COMMENT 'Property key/name';
ALTER TABLE `lupo_actor_properties` MODIFY COLUMN `property_value` text COMMENT 'Property value';
ALTER TABLE `lupo_actor_properties` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_actor_properties` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL COMMENT 'Last update bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_actor_properties` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted, 0=active)';
ALTER TABLE `lupo_actor_properties` MODIFY COLUMN `deleted_ymdhis` bigint COMMENT 'Deletion bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_actor_properties` ADD PRIMARY KEY (`actor_property_id`);
ALTER TABLE `lupo_actor_properties` ADD INDEX IF NOT EXISTS `idx_entity` (`actor_type`, `actor_id`);
ALTER TABLE `lupo_actor_properties` ADD INDEX IF NOT EXISTS `idx_property` (`property_key`);

CREATE TABLE IF NOT EXISTS `lupo_actor_reply_templates` (
  `actor_reply_template_id` bigint NOT NULL auto_increment COMMENT 'Primary key for the template',
  `actor_id` bigint NOT NULL COMMENT 'ID of the actor (user/agent) this template belongs to',
  `template_key` varchar(64) NOT NULL COMMENT 'Unique key to identify this template',
  `template_text` text NOT NULL COMMENT 'The template content with placeholders',
  `usage_context` varchar(64) COMMENT 'Context where this template is used',
  `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint (YYYYMMDDHHMMSS)',
  `updated_ymdhis` bigint NOT NULL COMMENT 'Last update bigint (YYYYMMDDHHMMSS)',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted, 0=active)',
  `deleted_ymdhis` bigint COMMENT 'Deletion bigint (YYYYMMDDHHMMSS)',
  PRIMARY KEY (`actor_reply_template_id`),
  KEY `idx_actor` (`actor_id`),
  KEY `idx_created` (`created_ymdhis`),
  KEY `idx_deleted` (`is_deleted`),
  KEY `idx_key` (`template_key`),
  KEY `idx_updated` (`updated_ymdhis`),
  KEY `idx_usage_context` (`usage_context`),
  UNIQUE KEY `unq_actor_template_key` (`actor_id`, `template_key`)
);

ALTER TABLE `lupo_actor_reply_templates` ADD COLUMN IF NOT EXISTS `actor_reply_template_id` bigint NOT NULL auto_increment COMMENT 'Primary key for the template';
ALTER TABLE `lupo_actor_reply_templates` ADD COLUMN IF NOT EXISTS `actor_id` bigint NOT NULL COMMENT 'ID of the actor (user/agent) this template belongs to';
ALTER TABLE `lupo_actor_reply_templates` ADD COLUMN IF NOT EXISTS `template_key` varchar(64) NOT NULL COMMENT 'Unique key to identify this template';
ALTER TABLE `lupo_actor_reply_templates` ADD COLUMN IF NOT EXISTS `template_text` text NOT NULL COMMENT 'The template content with placeholders';
ALTER TABLE `lupo_actor_reply_templates` ADD COLUMN IF NOT EXISTS `usage_context` varchar(64) COMMENT 'Context where this template is used';
ALTER TABLE `lupo_actor_reply_templates` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_actor_reply_templates` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL COMMENT 'Last update bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_actor_reply_templates` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted, 0=active)';
ALTER TABLE `lupo_actor_reply_templates` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint COMMENT 'Deletion bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_actor_reply_templates` MODIFY COLUMN `actor_reply_template_id` bigint NOT NULL auto_increment COMMENT 'Primary key for the template';
ALTER TABLE `lupo_actor_reply_templates` MODIFY COLUMN `actor_id` bigint NOT NULL COMMENT 'ID of the actor (user/agent) this template belongs to';
ALTER TABLE `lupo_actor_reply_templates` MODIFY COLUMN `template_key` varchar(64) NOT NULL COMMENT 'Unique key to identify this template';
ALTER TABLE `lupo_actor_reply_templates` MODIFY COLUMN `template_text` text NOT NULL COMMENT 'The template content with placeholders';
ALTER TABLE `lupo_actor_reply_templates` MODIFY COLUMN `usage_context` varchar(64) COMMENT 'Context where this template is used';
ALTER TABLE `lupo_actor_reply_templates` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_actor_reply_templates` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL COMMENT 'Last update bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_actor_reply_templates` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted, 0=active)';
ALTER TABLE `lupo_actor_reply_templates` MODIFY COLUMN `deleted_ymdhis` bigint COMMENT 'Deletion bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_actor_reply_templates` ADD PRIMARY KEY (`actor_reply_template_id`);
ALTER TABLE `lupo_actor_reply_templates` ADD INDEX IF NOT EXISTS `idx_actor` (`actor_id`);
ALTER TABLE `lupo_actor_reply_templates` ADD INDEX IF NOT EXISTS `idx_created` (`created_ymdhis`);
ALTER TABLE `lupo_actor_reply_templates` ADD INDEX IF NOT EXISTS `idx_deleted` (`is_deleted`);
ALTER TABLE `lupo_actor_reply_templates` ADD INDEX IF NOT EXISTS `idx_key` (`template_key`);
ALTER TABLE `lupo_actor_reply_templates` ADD INDEX IF NOT EXISTS `idx_updated` (`updated_ymdhis`);
ALTER TABLE `lupo_actor_reply_templates` ADD INDEX IF NOT EXISTS `idx_usage_context` (`usage_context`);
ALTER TABLE `lupo_actor_reply_templates` ADD UNIQUE INDEX IF NOT EXISTS `unq_actor_template_key` (`actor_id`, `template_key`);

CREATE TABLE IF NOT EXISTS `lupo_actor_roles` (
  `actor_role_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `context_id` bigint NOT NULL DEFAULT 0,
  `department_id` bigint,
  `role_key` varchar(100) NOT NULL,
  `role_description` text,
  `weight` float DEFAULT 1,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint,
  `is_deleted` smallint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (`actor_role_id`),
  UNIQUE KEY `actor_id` (`actor_id`, `context_id`, `role_key`),
  KEY `actor_id_2` (`actor_id`),
  KEY `context_id` (`context_id`),
  KEY `department_id` (`department_id`)
);

ALTER TABLE `lupo_actor_roles` ADD COLUMN IF NOT EXISTS `actor_role_id` bigint NOT NULL;
ALTER TABLE `lupo_actor_roles` ADD COLUMN IF NOT EXISTS `actor_id` bigint NOT NULL;
ALTER TABLE `lupo_actor_roles` ADD COLUMN IF NOT EXISTS `context_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_actor_roles` ADD COLUMN IF NOT EXISTS `department_id` bigint;
ALTER TABLE `lupo_actor_roles` ADD COLUMN IF NOT EXISTS `role_key` varchar(100) NOT NULL;
ALTER TABLE `lupo_actor_roles` ADD COLUMN IF NOT EXISTS `role_description` text;
ALTER TABLE `lupo_actor_roles` ADD COLUMN IF NOT EXISTS `weight` float DEFAULT 1;
ALTER TABLE `lupo_actor_roles` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_actor_roles` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint;
ALTER TABLE `lupo_actor_roles` ADD COLUMN IF NOT EXISTS `is_deleted` smallint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_actor_roles` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint;
ALTER TABLE `lupo_actor_roles` MODIFY COLUMN `actor_role_id` bigint NOT NULL;
ALTER TABLE `lupo_actor_roles` MODIFY COLUMN `actor_id` bigint NOT NULL;
ALTER TABLE `lupo_actor_roles` MODIFY COLUMN `context_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_actor_roles` MODIFY COLUMN `department_id` bigint;
ALTER TABLE `lupo_actor_roles` MODIFY COLUMN `role_key` varchar(100) NOT NULL;
ALTER TABLE `lupo_actor_roles` MODIFY COLUMN `role_description` text;
ALTER TABLE `lupo_actor_roles` MODIFY COLUMN `weight` float DEFAULT 1;
ALTER TABLE `lupo_actor_roles` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_actor_roles` MODIFY COLUMN `updated_ymdhis` bigint;
ALTER TABLE `lupo_actor_roles` MODIFY COLUMN `is_deleted` smallint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_actor_roles` MODIFY COLUMN `deleted_ymdhis` bigint;
ALTER TABLE `lupo_actor_roles` ADD PRIMARY KEY (`actor_role_id`);
ALTER TABLE `lupo_actor_roles` ADD UNIQUE INDEX IF NOT EXISTS `actor_id` (`actor_id`, `context_id`, `role_key`);
ALTER TABLE `lupo_actor_roles` ADD INDEX IF NOT EXISTS `actor_id_2` (`actor_id`);
ALTER TABLE `lupo_actor_roles` ADD INDEX IF NOT EXISTS `context_id` (`context_id`);
ALTER TABLE `lupo_actor_roles` ADD INDEX IF NOT EXISTS `department_id` (`department_id`);

CREATE TABLE IF NOT EXISTS `lupo_actor_truth_edges` (
  `actor_truth_edge_id` bigint NOT NULL auto_increment,
  `actor_id` bigint NOT NULL,
  `truth_item_id` bigint NOT NULL,
  `edge_type` enum('read','liked','disliked','created','commented_on','linked_to','referenced','viewed_multiple_times','searched_for','favorited','pinned','bookmarked','navigated_from','navigated_to','high_affinity','low_affinity','topic_cluster_member','semantic_neighbor','frequent_path') NOT NULL,
  `properties_json` json,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  PRIMARY KEY (`actor_truth_edge_id`),
  KEY `idx_actor_edge_type` (`actor_id`, `edge_type`),
  KEY `idx_truth_item` (`truth_item_id`),
  UNIQUE KEY `uniq_actor_truth_type` (`actor_id`, `truth_item_id`, `edge_type`)
);

ALTER TABLE `lupo_actor_truth_edges` ADD COLUMN IF NOT EXISTS `actor_truth_edge_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_actor_truth_edges` ADD COLUMN IF NOT EXISTS `actor_id` bigint NOT NULL;
ALTER TABLE `lupo_actor_truth_edges` ADD COLUMN IF NOT EXISTS `truth_item_id` bigint NOT NULL;
ALTER TABLE `lupo_actor_truth_edges` ADD COLUMN IF NOT EXISTS `edge_type` enum('read','liked','disliked','created','commented_on','linked_to','referenced','viewed_multiple_times','searched_for','favorited','pinned','bookmarked','navigated_from','navigated_to','high_affinity','low_affinity','topic_cluster_member','semantic_neighbor','frequent_path') NOT NULL;
ALTER TABLE `lupo_actor_truth_edges` ADD COLUMN IF NOT EXISTS `properties_json` json;
ALTER TABLE `lupo_actor_truth_edges` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_actor_truth_edges` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_actor_truth_edges` MODIFY COLUMN `actor_truth_edge_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_actor_truth_edges` MODIFY COLUMN `actor_id` bigint NOT NULL;
ALTER TABLE `lupo_actor_truth_edges` MODIFY COLUMN `truth_item_id` bigint NOT NULL;
ALTER TABLE `lupo_actor_truth_edges` MODIFY COLUMN `edge_type` enum('read','liked','disliked','created','commented_on','linked_to','referenced','viewed_multiple_times','searched_for','favorited','pinned','bookmarked','navigated_from','navigated_to','high_affinity','low_affinity','topic_cluster_member','semantic_neighbor','frequent_path') NOT NULL;
ALTER TABLE `lupo_actor_truth_edges` MODIFY COLUMN `properties_json` json;
ALTER TABLE `lupo_actor_truth_edges` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_actor_truth_edges` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_actor_truth_edges` ADD PRIMARY KEY (`actor_truth_edge_id`);
ALTER TABLE `lupo_actor_truth_edges` ADD INDEX IF NOT EXISTS `idx_actor_edge_type` (`actor_id`, `edge_type`);
ALTER TABLE `lupo_actor_truth_edges` ADD INDEX IF NOT EXISTS `idx_truth_item` (`truth_item_id`);
ALTER TABLE `lupo_actor_truth_edges` ADD UNIQUE INDEX IF NOT EXISTS `uniq_actor_truth_type` (`actor_id`, `truth_item_id`, `edge_type`);

CREATE TABLE IF NOT EXISTS `lupo_actors` (
  `actor_id` bigint NOT NULL auto_increment COMMENT 'Primary key for actor',
  `actor_type` enum('user','ai_agent','service') NOT NULL COMMENT 'Type of actor',
  `slug` varchar(255) NOT NULL COMMENT 'Stable unique identifier',
  `name` varchar(255) NOT NULL COMMENT 'Human-readable name',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS',
  `is_active` tinyint NOT NULL DEFAULT 1 COMMENT 'Active flag',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag',
  `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS',
  `actor_source_id` bigint COMMENT 'ID from source table (auth_users, agents, etc.)',
  `actor_source_type` varchar(20) COMMENT 'Source type: user, agent, system',
  `metadata` text COMMENT 'Optional JSON for additional actor attributes',
  `adversarial_role` enum('none','structural_stress','protocol_break','doctrine_test') DEFAULT 'none',
  `adversarial_oversight_actor_id` bigint COMMENT 'e.g., LILITH actor_id',
  PRIMARY KEY (`actor_id`),
  KEY `idx_actor_type` (`actor_type`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_is_active` (`is_active`),
  UNIQUE KEY `unique_slug` (`slug`)
);

ALTER TABLE `lupo_actors` ADD COLUMN IF NOT EXISTS `actor_id` bigint NOT NULL auto_increment COMMENT 'Primary key for actor';
ALTER TABLE `lupo_actors` ADD COLUMN IF NOT EXISTS `actor_type` enum('user','ai_agent','service') NOT NULL COMMENT 'Type of actor';
ALTER TABLE `lupo_actors` ADD COLUMN IF NOT EXISTS `slug` varchar(255) NOT NULL COMMENT 'Stable unique identifier';
ALTER TABLE `lupo_actors` ADD COLUMN IF NOT EXISTS `name` varchar(255) NOT NULL COMMENT 'Human-readable name';
ALTER TABLE `lupo_actors` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS';
ALTER TABLE `lupo_actors` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS';
ALTER TABLE `lupo_actors` ADD COLUMN IF NOT EXISTS `is_active` tinyint NOT NULL DEFAULT 1 COMMENT 'Active flag';
ALTER TABLE `lupo_actors` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag';
ALTER TABLE `lupo_actors` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS';
ALTER TABLE `lupo_actors` ADD COLUMN IF NOT EXISTS `actor_source_id` bigint COMMENT 'ID from source table (auth_users, agents, etc.)';
ALTER TABLE `lupo_actors` ADD COLUMN IF NOT EXISTS `actor_source_type` varchar(20) COMMENT 'Source type: user, agent, system';
ALTER TABLE `lupo_actors` ADD COLUMN IF NOT EXISTS `metadata` text COMMENT 'Optional JSON for additional actor attributes';
ALTER TABLE `lupo_actors` ADD COLUMN IF NOT EXISTS `adversarial_role` enum('none','structural_stress','protocol_break','doctrine_test') DEFAULT 'none';
ALTER TABLE `lupo_actors` ADD COLUMN IF NOT EXISTS `adversarial_oversight_actor_id` bigint COMMENT 'e.g., LILITH actor_id';
ALTER TABLE `lupo_actors` MODIFY COLUMN `actor_id` bigint NOT NULL auto_increment COMMENT 'Primary key for actor';
ALTER TABLE `lupo_actors` MODIFY COLUMN `actor_type` enum('user','ai_agent','service') NOT NULL COMMENT 'Type of actor';
ALTER TABLE `lupo_actors` MODIFY COLUMN `slug` varchar(255) NOT NULL COMMENT 'Stable unique identifier';
ALTER TABLE `lupo_actors` MODIFY COLUMN `name` varchar(255) NOT NULL COMMENT 'Human-readable name';
ALTER TABLE `lupo_actors` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS';
ALTER TABLE `lupo_actors` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS';
ALTER TABLE `lupo_actors` MODIFY COLUMN `is_active` tinyint NOT NULL DEFAULT 1 COMMENT 'Active flag';
ALTER TABLE `lupo_actors` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag';
ALTER TABLE `lupo_actors` MODIFY COLUMN `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS';
ALTER TABLE `lupo_actors` MODIFY COLUMN `actor_source_id` bigint COMMENT 'ID from source table (auth_users, agents, etc.)';
ALTER TABLE `lupo_actors` MODIFY COLUMN `actor_source_type` varchar(20) COMMENT 'Source type: user, agent, system';
ALTER TABLE `lupo_actors` MODIFY COLUMN `metadata` text COMMENT 'Optional JSON for additional actor attributes';
ALTER TABLE `lupo_actors` MODIFY COLUMN `adversarial_role` enum('none','structural_stress','protocol_break','doctrine_test') DEFAULT 'none';
ALTER TABLE `lupo_actors` MODIFY COLUMN `adversarial_oversight_actor_id` bigint COMMENT 'e.g., LILITH actor_id';
ALTER TABLE `lupo_actors` ADD PRIMARY KEY (`actor_id`);
ALTER TABLE `lupo_actors` ADD INDEX IF NOT EXISTS `idx_actor_type` (`actor_type`);
ALTER TABLE `lupo_actors` ADD INDEX IF NOT EXISTS `idx_created_ymdhis` (`created_ymdhis`);
ALTER TABLE `lupo_actors` ADD INDEX IF NOT EXISTS `idx_is_active` (`is_active`);
ALTER TABLE `lupo_actors` ADD UNIQUE INDEX IF NOT EXISTS `unique_slug` (`slug`);

CREATE TABLE IF NOT EXISTS `lupo_agent_context_snapshots` (
  `agent_context_snapshot_id` bigint NOT NULL auto_increment COMMENT 'Unique identifier for the snapshot',
  `session_id` varchar(100) NOT NULL COMMENT 'Session identifier',
  `actor_id` bigint NOT NULL,
  `parent_snapshot_id` bigint COMMENT 'For delta snapshots, references the parent full snapshot',
  `snapshot_type` enum('full','delta','checkpoint','error','user_saved') NOT NULL DEFAULT 'full' COMMENT 'Type of snapshot',
  `snapshot_purpose` varchar(50) COMMENT 'checkpoint, error_recovery, user_save, etc.',
  `context_data` longtext NOT NULL COMMENT 'Serialized context state (compressed JSON)',
  `context_summary` text COMMENT 'Human-readable summary of the context',
  `context_metadata` json COMMENT 'Structured metadata about the context',
  `token_count` int COMMENT 'Approximate token count for LLM context',
  `character_count` int COMMENT 'Raw character count before compression',
  `compressed_size` int COMMENT 'Size in bytes after compression',
  `compression_ratio` float COMMENT 'compressed/original ratio (smaller is better)',
  `compression_method` enum('gzip','zstd','none') DEFAULT 'gzip' COMMENT 'Compression algorithm used',
  `serialization_time_ms` int COMMENT 'Time taken to serialize context (ms)',
  `compression_time_ms` int COMMENT 'Time taken to compress (ms)',
  `related_tool_call_id` bigint COMMENT 'Associated tool call that triggered this snapshot',
  `conversation_turn` int COMMENT 'Conversation turn number when snapshot was taken',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when snapshot was created',
  `expires_ymdhis` bigint COMMENT 'When this snapshot should be automatically purged',
  `is_corrupt` tinyint(1) DEFAULT 0 COMMENT '1 if snapshot failed integrity check',
  `retention_policy` enum('temporary','short_term','long_term') DEFAULT 'temporary' COMMENT 'How long to retain this snapshot',
  PRIMARY KEY (`agent_context_snapshot_id`),
  KEY `ft_summary` (`context_summary`),
  KEY `idx_created` (`created_ymdhis`),
  KEY `idx_parent` (`parent_snapshot_id`),
  KEY `idx_related_tool` (`related_tool_call_id`),
  KEY `idx_retention` (`retention_policy`, `expires_ymdhis`),
  KEY `idx_session_agent` (`session_id`, `actor_id`),
  KEY `idx_turn` (`session_id`, `conversation_turn`),
  KEY `idx_type_purpose` (`snapshot_type`, `snapshot_purpose`)
);

ALTER TABLE `lupo_agent_context_snapshots` ADD COLUMN IF NOT EXISTS `agent_context_snapshot_id` bigint NOT NULL auto_increment COMMENT 'Unique identifier for the snapshot';
ALTER TABLE `lupo_agent_context_snapshots` ADD COLUMN IF NOT EXISTS `session_id` varchar(100) NOT NULL COMMENT 'Session identifier';
ALTER TABLE `lupo_agent_context_snapshots` ADD COLUMN IF NOT EXISTS `actor_id` bigint NOT NULL;
ALTER TABLE `lupo_agent_context_snapshots` ADD COLUMN IF NOT EXISTS `parent_snapshot_id` bigint COMMENT 'For delta snapshots, references the parent full snapshot';
ALTER TABLE `lupo_agent_context_snapshots` ADD COLUMN IF NOT EXISTS `snapshot_type` enum('full','delta','checkpoint','error','user_saved') NOT NULL DEFAULT 'full' COMMENT 'Type of snapshot';
ALTER TABLE `lupo_agent_context_snapshots` ADD COLUMN IF NOT EXISTS `snapshot_purpose` varchar(50) COMMENT 'checkpoint, error_recovery, user_save, etc.';
ALTER TABLE `lupo_agent_context_snapshots` ADD COLUMN IF NOT EXISTS `context_data` longtext NOT NULL COMMENT 'Serialized context state (compressed JSON)';
ALTER TABLE `lupo_agent_context_snapshots` ADD COLUMN IF NOT EXISTS `context_summary` text COMMENT 'Human-readable summary of the context';
ALTER TABLE `lupo_agent_context_snapshots` ADD COLUMN IF NOT EXISTS `context_metadata` json COMMENT 'Structured metadata about the context';
ALTER TABLE `lupo_agent_context_snapshots` ADD COLUMN IF NOT EXISTS `token_count` int COMMENT 'Approximate token count for LLM context';
ALTER TABLE `lupo_agent_context_snapshots` ADD COLUMN IF NOT EXISTS `character_count` int COMMENT 'Raw character count before compression';
ALTER TABLE `lupo_agent_context_snapshots` ADD COLUMN IF NOT EXISTS `compressed_size` int COMMENT 'Size in bytes after compression';
ALTER TABLE `lupo_agent_context_snapshots` ADD COLUMN IF NOT EXISTS `compression_ratio` float COMMENT 'compressed/original ratio (smaller is better)';
ALTER TABLE `lupo_agent_context_snapshots` ADD COLUMN IF NOT EXISTS `compression_method` enum('gzip','zstd','none') DEFAULT 'gzip' COMMENT 'Compression algorithm used';
ALTER TABLE `lupo_agent_context_snapshots` ADD COLUMN IF NOT EXISTS `serialization_time_ms` int COMMENT 'Time taken to serialize context (ms)';
ALTER TABLE `lupo_agent_context_snapshots` ADD COLUMN IF NOT EXISTS `compression_time_ms` int COMMENT 'Time taken to compress (ms)';
ALTER TABLE `lupo_agent_context_snapshots` ADD COLUMN IF NOT EXISTS `related_tool_call_id` bigint COMMENT 'Associated tool call that triggered this snapshot';
ALTER TABLE `lupo_agent_context_snapshots` ADD COLUMN IF NOT EXISTS `conversation_turn` int COMMENT 'Conversation turn number when snapshot was taken';
ALTER TABLE `lupo_agent_context_snapshots` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when snapshot was created';
ALTER TABLE `lupo_agent_context_snapshots` ADD COLUMN IF NOT EXISTS `expires_ymdhis` bigint COMMENT 'When this snapshot should be automatically purged';
ALTER TABLE `lupo_agent_context_snapshots` ADD COLUMN IF NOT EXISTS `is_corrupt` tinyint(1) DEFAULT 0 COMMENT '1 if snapshot failed integrity check';
ALTER TABLE `lupo_agent_context_snapshots` ADD COLUMN IF NOT EXISTS `retention_policy` enum('temporary','short_term','long_term') DEFAULT 'temporary' COMMENT 'How long to retain this snapshot';
ALTER TABLE `lupo_agent_context_snapshots` MODIFY COLUMN `agent_context_snapshot_id` bigint NOT NULL auto_increment COMMENT 'Unique identifier for the snapshot';
ALTER TABLE `lupo_agent_context_snapshots` MODIFY COLUMN `session_id` varchar(100) NOT NULL COMMENT 'Session identifier';
ALTER TABLE `lupo_agent_context_snapshots` MODIFY COLUMN `actor_id` bigint NOT NULL;
ALTER TABLE `lupo_agent_context_snapshots` MODIFY COLUMN `parent_snapshot_id` bigint COMMENT 'For delta snapshots, references the parent full snapshot';
ALTER TABLE `lupo_agent_context_snapshots` MODIFY COLUMN `snapshot_type` enum('full','delta','checkpoint','error','user_saved') NOT NULL DEFAULT 'full' COMMENT 'Type of snapshot';
ALTER TABLE `lupo_agent_context_snapshots` MODIFY COLUMN `snapshot_purpose` varchar(50) COMMENT 'checkpoint, error_recovery, user_save, etc.';
ALTER TABLE `lupo_agent_context_snapshots` MODIFY COLUMN `context_data` longtext NOT NULL COMMENT 'Serialized context state (compressed JSON)';
ALTER TABLE `lupo_agent_context_snapshots` MODIFY COLUMN `context_summary` text COMMENT 'Human-readable summary of the context';
ALTER TABLE `lupo_agent_context_snapshots` MODIFY COLUMN `context_metadata` json COMMENT 'Structured metadata about the context';
ALTER TABLE `lupo_agent_context_snapshots` MODIFY COLUMN `token_count` int COMMENT 'Approximate token count for LLM context';
ALTER TABLE `lupo_agent_context_snapshots` MODIFY COLUMN `character_count` int COMMENT 'Raw character count before compression';
ALTER TABLE `lupo_agent_context_snapshots` MODIFY COLUMN `compressed_size` int COMMENT 'Size in bytes after compression';
ALTER TABLE `lupo_agent_context_snapshots` MODIFY COLUMN `compression_ratio` float COMMENT 'compressed/original ratio (smaller is better)';
ALTER TABLE `lupo_agent_context_snapshots` MODIFY COLUMN `compression_method` enum('gzip','zstd','none') DEFAULT 'gzip' COMMENT 'Compression algorithm used';
ALTER TABLE `lupo_agent_context_snapshots` MODIFY COLUMN `serialization_time_ms` int COMMENT 'Time taken to serialize context (ms)';
ALTER TABLE `lupo_agent_context_snapshots` MODIFY COLUMN `compression_time_ms` int COMMENT 'Time taken to compress (ms)';
ALTER TABLE `lupo_agent_context_snapshots` MODIFY COLUMN `related_tool_call_id` bigint COMMENT 'Associated tool call that triggered this snapshot';
ALTER TABLE `lupo_agent_context_snapshots` MODIFY COLUMN `conversation_turn` int COMMENT 'Conversation turn number when snapshot was taken';
ALTER TABLE `lupo_agent_context_snapshots` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when snapshot was created';
ALTER TABLE `lupo_agent_context_snapshots` MODIFY COLUMN `expires_ymdhis` bigint COMMENT 'When this snapshot should be automatically purged';
ALTER TABLE `lupo_agent_context_snapshots` MODIFY COLUMN `is_corrupt` tinyint(1) DEFAULT 0 COMMENT '1 if snapshot failed integrity check';
ALTER TABLE `lupo_agent_context_snapshots` MODIFY COLUMN `retention_policy` enum('temporary','short_term','long_term') DEFAULT 'temporary' COMMENT 'How long to retain this snapshot';
ALTER TABLE `lupo_agent_context_snapshots` ADD PRIMARY KEY (`agent_context_snapshot_id`);
ALTER TABLE `lupo_agent_context_snapshots` ADD INDEX IF NOT EXISTS `ft_summary` (`context_summary`);
ALTER TABLE `lupo_agent_context_snapshots` ADD INDEX IF NOT EXISTS `idx_created` (`created_ymdhis`);
ALTER TABLE `lupo_agent_context_snapshots` ADD INDEX IF NOT EXISTS `idx_parent` (`parent_snapshot_id`);
ALTER TABLE `lupo_agent_context_snapshots` ADD INDEX IF NOT EXISTS `idx_related_tool` (`related_tool_call_id`);
ALTER TABLE `lupo_agent_context_snapshots` ADD INDEX IF NOT EXISTS `idx_retention` (`retention_policy`, `expires_ymdhis`);
ALTER TABLE `lupo_agent_context_snapshots` ADD INDEX IF NOT EXISTS `idx_session_agent` (`session_id`, `actor_id`);
ALTER TABLE `lupo_agent_context_snapshots` ADD INDEX IF NOT EXISTS `idx_turn` (`session_id`, `conversation_turn`);
ALTER TABLE `lupo_agent_context_snapshots` ADD INDEX IF NOT EXISTS `idx_type_purpose` (`snapshot_type`, `snapshot_purpose`);

CREATE TABLE IF NOT EXISTS `lupo_agent_dependencies` (
  `agent_dependency_id` bigint NOT NULL auto_increment,
  `agent_id` bigint NOT NULL COMMENT 'The agent that has dependencies',
  `depends_on_agent_id` bigint NOT NULL COMMENT 'The agent it depends on',
  `depends_on_agent_code` varchar(50) NOT NULL,
  `is_required` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = must be active, 0 = optional',
  `notes` text COMMENT 'Why this dependency exists',
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint,
  PRIMARY KEY (`agent_dependency_id`),
  KEY `idx_agent_id` (`agent_id`),
  KEY `idx_depends_on` (`depends_on_agent_id`)
);

ALTER TABLE `lupo_agent_dependencies` ADD COLUMN IF NOT EXISTS `agent_dependency_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_agent_dependencies` ADD COLUMN IF NOT EXISTS `agent_id` bigint NOT NULL COMMENT 'The agent that has dependencies';
ALTER TABLE `lupo_agent_dependencies` ADD COLUMN IF NOT EXISTS `depends_on_agent_id` bigint NOT NULL COMMENT 'The agent it depends on';
ALTER TABLE `lupo_agent_dependencies` ADD COLUMN IF NOT EXISTS `depends_on_agent_code` varchar(50) NOT NULL;
ALTER TABLE `lupo_agent_dependencies` ADD COLUMN IF NOT EXISTS `is_required` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = must be active, 0 = optional';
ALTER TABLE `lupo_agent_dependencies` ADD COLUMN IF NOT EXISTS `notes` text COMMENT 'Why this dependency exists';
ALTER TABLE `lupo_agent_dependencies` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_agent_dependencies` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint;
ALTER TABLE `lupo_agent_dependencies` MODIFY COLUMN `agent_dependency_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_agent_dependencies` MODIFY COLUMN `agent_id` bigint NOT NULL COMMENT 'The agent that has dependencies';
ALTER TABLE `lupo_agent_dependencies` MODIFY COLUMN `depends_on_agent_id` bigint NOT NULL COMMENT 'The agent it depends on';
ALTER TABLE `lupo_agent_dependencies` MODIFY COLUMN `depends_on_agent_code` varchar(50) NOT NULL;
ALTER TABLE `lupo_agent_dependencies` MODIFY COLUMN `is_required` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = must be active, 0 = optional';
ALTER TABLE `lupo_agent_dependencies` MODIFY COLUMN `notes` text COMMENT 'Why this dependency exists';
ALTER TABLE `lupo_agent_dependencies` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_agent_dependencies` MODIFY COLUMN `updated_ymdhis` bigint;
ALTER TABLE `lupo_agent_dependencies` ADD PRIMARY KEY (`agent_dependency_id`);
ALTER TABLE `lupo_agent_dependencies` ADD INDEX IF NOT EXISTS `idx_agent_id` (`agent_id`);
ALTER TABLE `lupo_agent_dependencies` ADD INDEX IF NOT EXISTS `idx_depends_on` (`depends_on_agent_id`);

CREATE TABLE IF NOT EXISTS `lupo_agent_external_events` (
  `external_event_id` bigint NOT NULL auto_increment,
  `agent_name` varchar(255) NOT NULL,
  `source_system` varchar(255) NOT NULL,
  `event_type` varchar(50) NOT NULL,
  `event_payload_json` json,
  `created_ymdhis` bigint NOT NULL,
  PRIMARY KEY (`external_event_id`)
);

ALTER TABLE `lupo_agent_external_events` ADD COLUMN IF NOT EXISTS `external_event_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_agent_external_events` ADD COLUMN IF NOT EXISTS `agent_name` varchar(255) NOT NULL;
ALTER TABLE `lupo_agent_external_events` ADD COLUMN IF NOT EXISTS `source_system` varchar(255) NOT NULL;
ALTER TABLE `lupo_agent_external_events` ADD COLUMN IF NOT EXISTS `event_type` varchar(50) NOT NULL;
ALTER TABLE `lupo_agent_external_events` ADD COLUMN IF NOT EXISTS `event_payload_json` json;
ALTER TABLE `lupo_agent_external_events` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_agent_external_events` MODIFY COLUMN `external_event_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_agent_external_events` MODIFY COLUMN `agent_name` varchar(255) NOT NULL;
ALTER TABLE `lupo_agent_external_events` MODIFY COLUMN `source_system` varchar(255) NOT NULL;
ALTER TABLE `lupo_agent_external_events` MODIFY COLUMN `event_type` varchar(50) NOT NULL;
ALTER TABLE `lupo_agent_external_events` MODIFY COLUMN `event_payload_json` json;
ALTER TABLE `lupo_agent_external_events` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_agent_external_events` ADD PRIMARY KEY (`external_event_id`);

CREATE TABLE IF NOT EXISTS `lupo_agent_faucet_credentials` (
  `agent_faucet_credential_id` int NOT NULL auto_increment,
  `faucet_id` bigint NOT NULL,
  `provider` varchar(64) NOT NULL,
  `api_key` varbinary(512) NOT NULL,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  PRIMARY KEY (`agent_faucet_credential_id`),
  KEY `idx_faucet` (`faucet_id`)
);

ALTER TABLE `lupo_agent_faucet_credentials` ADD COLUMN IF NOT EXISTS `agent_faucet_credential_id` int NOT NULL auto_increment;
ALTER TABLE `lupo_agent_faucet_credentials` ADD COLUMN IF NOT EXISTS `faucet_id` bigint NOT NULL;
ALTER TABLE `lupo_agent_faucet_credentials` ADD COLUMN IF NOT EXISTS `provider` varchar(64) NOT NULL;
ALTER TABLE `lupo_agent_faucet_credentials` ADD COLUMN IF NOT EXISTS `api_key` varbinary(512) NOT NULL;
ALTER TABLE `lupo_agent_faucet_credentials` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_agent_faucet_credentials` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_agent_faucet_credentials` MODIFY COLUMN `agent_faucet_credential_id` int NOT NULL auto_increment;
ALTER TABLE `lupo_agent_faucet_credentials` MODIFY COLUMN `faucet_id` bigint NOT NULL;
ALTER TABLE `lupo_agent_faucet_credentials` MODIFY COLUMN `provider` varchar(64) NOT NULL;
ALTER TABLE `lupo_agent_faucet_credentials` MODIFY COLUMN `api_key` varbinary(512) NOT NULL;
ALTER TABLE `lupo_agent_faucet_credentials` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_agent_faucet_credentials` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_agent_faucet_credentials` ADD PRIMARY KEY (`agent_faucet_credential_id`);
ALTER TABLE `lupo_agent_faucet_credentials` ADD INDEX IF NOT EXISTS `idx_faucet` (`faucet_id`);

CREATE TABLE IF NOT EXISTS `lupo_agent_faucets` (
  `agent_faucet_id` bigint NOT NULL auto_increment,
  `actor_id` bigint NOT NULL,
  `name` varchar(100) NOT NULL COMMENT 'Public-facing faucet name (Wolfie UI, Wolfie Code, etc.)',
  `alias_name` varchar(100),
  `slug` varchar(100) NOT NULL COMMENT 'Internal identifier for referencing this faucet',
  `description` text COMMENT 'Human-readable description of this faucet persona',
  `style_preset` varchar(100) COMMENT 'Optional style/tone preset (mythic, formal, playful, etc.)',
  `model_name` varchar(100),
  `provider` varchar(50),
  `temperature` float,
  `top_p` float,
  `max_tokens` int,
  `presence_penalty` float,
  `frequency_penalty` float,
  `system_prompt` longtext,
  `safety_json` json,
  `response_format` varchar(50),
  `capabilities_json` longtext COMMENT 'JSON describing which tools/capabilities this faucet can use',
  `is_default` tinyint NOT NULL DEFAULT 0 COMMENT '1 if this is the default faucet for the agent',
  `domain_id` bigint NOT NULL DEFAULT 1 COMMENT 'Domain this faucet belongs to (multi-domain support)',
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (`agent_faucet_id`),
  KEY `idx_agent` (`actor_id`),
  KEY `idx_default` (`is_default`),
  KEY `idx_domain` (`domain_id`),
  KEY `idx_slug` (`slug`)
);

ALTER TABLE `lupo_agent_faucets` ADD COLUMN IF NOT EXISTS `agent_faucet_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_agent_faucets` ADD COLUMN IF NOT EXISTS `actor_id` bigint NOT NULL;
ALTER TABLE `lupo_agent_faucets` ADD COLUMN IF NOT EXISTS `name` varchar(100) NOT NULL COMMENT 'Public-facing faucet name (Wolfie UI, Wolfie Code, etc.)';
ALTER TABLE `lupo_agent_faucets` ADD COLUMN IF NOT EXISTS `alias_name` varchar(100);
ALTER TABLE `lupo_agent_faucets` ADD COLUMN IF NOT EXISTS `slug` varchar(100) NOT NULL COMMENT 'Internal identifier for referencing this faucet';
ALTER TABLE `lupo_agent_faucets` ADD COLUMN IF NOT EXISTS `description` text COMMENT 'Human-readable description of this faucet persona';
ALTER TABLE `lupo_agent_faucets` ADD COLUMN IF NOT EXISTS `style_preset` varchar(100) COMMENT 'Optional style/tone preset (mythic, formal, playful, etc.)';
ALTER TABLE `lupo_agent_faucets` ADD COLUMN IF NOT EXISTS `model_name` varchar(100);
ALTER TABLE `lupo_agent_faucets` ADD COLUMN IF NOT EXISTS `provider` varchar(50);
ALTER TABLE `lupo_agent_faucets` ADD COLUMN IF NOT EXISTS `temperature` float;
ALTER TABLE `lupo_agent_faucets` ADD COLUMN IF NOT EXISTS `top_p` float;
ALTER TABLE `lupo_agent_faucets` ADD COLUMN IF NOT EXISTS `max_tokens` int;
ALTER TABLE `lupo_agent_faucets` ADD COLUMN IF NOT EXISTS `presence_penalty` float;
ALTER TABLE `lupo_agent_faucets` ADD COLUMN IF NOT EXISTS `frequency_penalty` float;
ALTER TABLE `lupo_agent_faucets` ADD COLUMN IF NOT EXISTS `system_prompt` longtext;
ALTER TABLE `lupo_agent_faucets` ADD COLUMN IF NOT EXISTS `safety_json` json;
ALTER TABLE `lupo_agent_faucets` ADD COLUMN IF NOT EXISTS `response_format` varchar(50);
ALTER TABLE `lupo_agent_faucets` ADD COLUMN IF NOT EXISTS `capabilities_json` longtext COMMENT 'JSON describing which tools/capabilities this faucet can use';
ALTER TABLE `lupo_agent_faucets` ADD COLUMN IF NOT EXISTS `is_default` tinyint NOT NULL DEFAULT 0 COMMENT '1 if this is the default faucet for the agent';
ALTER TABLE `lupo_agent_faucets` ADD COLUMN IF NOT EXISTS `domain_id` bigint NOT NULL DEFAULT 1 COMMENT 'Domain this faucet belongs to (multi-domain support)';
ALTER TABLE `lupo_agent_faucets` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_agent_faucets` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_agent_faucets` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint;
ALTER TABLE `lupo_agent_faucets` MODIFY COLUMN `agent_faucet_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_agent_faucets` MODIFY COLUMN `actor_id` bigint NOT NULL;
ALTER TABLE `lupo_agent_faucets` MODIFY COLUMN `name` varchar(100) NOT NULL COMMENT 'Public-facing faucet name (Wolfie UI, Wolfie Code, etc.)';
ALTER TABLE `lupo_agent_faucets` MODIFY COLUMN `alias_name` varchar(100);
ALTER TABLE `lupo_agent_faucets` MODIFY COLUMN `slug` varchar(100) NOT NULL COMMENT 'Internal identifier for referencing this faucet';
ALTER TABLE `lupo_agent_faucets` MODIFY COLUMN `description` text COMMENT 'Human-readable description of this faucet persona';
ALTER TABLE `lupo_agent_faucets` MODIFY COLUMN `style_preset` varchar(100) COMMENT 'Optional style/tone preset (mythic, formal, playful, etc.)';
ALTER TABLE `lupo_agent_faucets` MODIFY COLUMN `model_name` varchar(100);
ALTER TABLE `lupo_agent_faucets` MODIFY COLUMN `provider` varchar(50);
ALTER TABLE `lupo_agent_faucets` MODIFY COLUMN `temperature` float;
ALTER TABLE `lupo_agent_faucets` MODIFY COLUMN `top_p` float;
ALTER TABLE `lupo_agent_faucets` MODIFY COLUMN `max_tokens` int;
ALTER TABLE `lupo_agent_faucets` MODIFY COLUMN `presence_penalty` float;
ALTER TABLE `lupo_agent_faucets` MODIFY COLUMN `frequency_penalty` float;
ALTER TABLE `lupo_agent_faucets` MODIFY COLUMN `system_prompt` longtext;
ALTER TABLE `lupo_agent_faucets` MODIFY COLUMN `safety_json` json;
ALTER TABLE `lupo_agent_faucets` MODIFY COLUMN `response_format` varchar(50);
ALTER TABLE `lupo_agent_faucets` MODIFY COLUMN `capabilities_json` longtext COMMENT 'JSON describing which tools/capabilities this faucet can use';
ALTER TABLE `lupo_agent_faucets` MODIFY COLUMN `is_default` tinyint NOT NULL DEFAULT 0 COMMENT '1 if this is the default faucet for the agent';
ALTER TABLE `lupo_agent_faucets` MODIFY COLUMN `domain_id` bigint NOT NULL DEFAULT 1 COMMENT 'Domain this faucet belongs to (multi-domain support)';
ALTER TABLE `lupo_agent_faucets` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_agent_faucets` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_agent_faucets` MODIFY COLUMN `deleted_ymdhis` bigint;
ALTER TABLE `lupo_agent_faucets` ADD PRIMARY KEY (`agent_faucet_id`);
ALTER TABLE `lupo_agent_faucets` ADD INDEX IF NOT EXISTS `idx_agent` (`actor_id`);
ALTER TABLE `lupo_agent_faucets` ADD INDEX IF NOT EXISTS `idx_default` (`is_default`);
ALTER TABLE `lupo_agent_faucets` ADD INDEX IF NOT EXISTS `idx_domain` (`domain_id`);
ALTER TABLE `lupo_agent_faucets` ADD INDEX IF NOT EXISTS `idx_slug` (`slug`);

CREATE TABLE IF NOT EXISTS `lupo_agent_heartbeats` (
  `heartbeat_id` bigint NOT NULL auto_increment,
  `agent_slug` varchar(64) NOT NULL COMMENT 'CURSOR|CASCADE|LILITH|MONDAY_WOLFIE|etc',
  `status` varchar(32) NOT NULL DEFAULT 'unknown' COMMENT 'operational|idle|error|unknown',
  `last_heartbeat_ymdhis` bigint NOT NULL COMMENT 'YYYYMMDDHHMMSS',
  `created_ymdhis` bigint NOT NULL COMMENT 'YYYYMMDDHHMMSS',
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (`heartbeat_id`),
  KEY `idx_agent_slug` (`agent_slug`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_last_heartbeat_ymdhis` (`last_heartbeat_ymdhis`)
);

ALTER TABLE `lupo_agent_heartbeats` ADD COLUMN IF NOT EXISTS `heartbeat_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_agent_heartbeats` ADD COLUMN IF NOT EXISTS `agent_slug` varchar(64) NOT NULL COMMENT 'CURSOR|CASCADE|LILITH|MONDAY_WOLFIE|etc';
ALTER TABLE `lupo_agent_heartbeats` ADD COLUMN IF NOT EXISTS `status` varchar(32) NOT NULL DEFAULT 'unknown' COMMENT 'operational|idle|error|unknown';
ALTER TABLE `lupo_agent_heartbeats` ADD COLUMN IF NOT EXISTS `last_heartbeat_ymdhis` bigint NOT NULL COMMENT 'YYYYMMDDHHMMSS';
ALTER TABLE `lupo_agent_heartbeats` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'YYYYMMDDHHMMSS';
ALTER TABLE `lupo_agent_heartbeats` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_agent_heartbeats` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint;
ALTER TABLE `lupo_agent_heartbeats` MODIFY COLUMN `heartbeat_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_agent_heartbeats` MODIFY COLUMN `agent_slug` varchar(64) NOT NULL COMMENT 'CURSOR|CASCADE|LILITH|MONDAY_WOLFIE|etc';
ALTER TABLE `lupo_agent_heartbeats` MODIFY COLUMN `status` varchar(32) NOT NULL DEFAULT 'unknown' COMMENT 'operational|idle|error|unknown';
ALTER TABLE `lupo_agent_heartbeats` MODIFY COLUMN `last_heartbeat_ymdhis` bigint NOT NULL COMMENT 'YYYYMMDDHHMMSS';
ALTER TABLE `lupo_agent_heartbeats` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'YYYYMMDDHHMMSS';
ALTER TABLE `lupo_agent_heartbeats` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_agent_heartbeats` MODIFY COLUMN `deleted_ymdhis` bigint;
ALTER TABLE `lupo_agent_heartbeats` ADD PRIMARY KEY (`heartbeat_id`);
ALTER TABLE `lupo_agent_heartbeats` ADD INDEX IF NOT EXISTS `idx_agent_slug` (`agent_slug`);
ALTER TABLE `lupo_agent_heartbeats` ADD INDEX IF NOT EXISTS `idx_created_ymdhis` (`created_ymdhis`);
ALTER TABLE `lupo_agent_heartbeats` ADD INDEX IF NOT EXISTS `idx_is_deleted` (`is_deleted`);
ALTER TABLE `lupo_agent_heartbeats` ADD INDEX IF NOT EXISTS `idx_last_heartbeat_ymdhis` (`last_heartbeat_ymdhis`);

CREATE TABLE IF NOT EXISTS `lupo_agent_properties` (
  `agent_property_id` bigint NOT NULL auto_increment,
  `actor_id` bigint NOT NULL COMMENT 'Agent this property belongs to',
  `domain_id` bigint NOT NULL COMMENT 'Domain scope for this property',
  `property_key` varchar(100) NOT NULL COMMENT 'Property identifier (e.g., "ui_preferences", "api_settings")',
  `property_value` text COMMENT 'JSON or TOON formatted property value',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
  `updated_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when last updated',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted',
  PRIMARY KEY (`agent_property_id`),
  KEY `idx_agent_domain` (`actor_id`, `domain_id`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_domain_id` (`domain_id`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_property_key` (`property_key`),
  KEY `idx_updated_ymdhis` (`updated_ymdhis`),
  UNIQUE KEY `unique_agent_domain_property` (`actor_id`, `domain_id`, `property_key`)
);

ALTER TABLE `lupo_agent_properties` ADD COLUMN IF NOT EXISTS `agent_property_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_agent_properties` ADD COLUMN IF NOT EXISTS `actor_id` bigint NOT NULL COMMENT 'Agent this property belongs to';
ALTER TABLE `lupo_agent_properties` ADD COLUMN IF NOT EXISTS `domain_id` bigint NOT NULL COMMENT 'Domain scope for this property';
ALTER TABLE `lupo_agent_properties` ADD COLUMN IF NOT EXISTS `property_key` varchar(100) NOT NULL COMMENT 'Property identifier (e.g., "ui_preferences", "api_settings")';
ALTER TABLE `lupo_agent_properties` ADD COLUMN IF NOT EXISTS `property_value` text COMMENT 'JSON or TOON formatted property value';
ALTER TABLE `lupo_agent_properties` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created';
ALTER TABLE `lupo_agent_properties` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when last updated';
ALTER TABLE `lupo_agent_properties` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted';
ALTER TABLE `lupo_agent_properties` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted';
ALTER TABLE `lupo_agent_properties` MODIFY COLUMN `agent_property_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_agent_properties` MODIFY COLUMN `actor_id` bigint NOT NULL COMMENT 'Agent this property belongs to';
ALTER TABLE `lupo_agent_properties` MODIFY COLUMN `domain_id` bigint NOT NULL COMMENT 'Domain scope for this property';
ALTER TABLE `lupo_agent_properties` MODIFY COLUMN `property_key` varchar(100) NOT NULL COMMENT 'Property identifier (e.g., "ui_preferences", "api_settings")';
ALTER TABLE `lupo_agent_properties` MODIFY COLUMN `property_value` text COMMENT 'JSON or TOON formatted property value';
ALTER TABLE `lupo_agent_properties` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created';
ALTER TABLE `lupo_agent_properties` MODIFY COLUMN `updated_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when last updated';
ALTER TABLE `lupo_agent_properties` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted';
ALTER TABLE `lupo_agent_properties` MODIFY COLUMN `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted';
ALTER TABLE `lupo_agent_properties` ADD PRIMARY KEY (`agent_property_id`);
ALTER TABLE `lupo_agent_properties` ADD INDEX IF NOT EXISTS `idx_agent_domain` (`actor_id`, `domain_id`);
ALTER TABLE `lupo_agent_properties` ADD INDEX IF NOT EXISTS `idx_created_ymdhis` (`created_ymdhis`);
ALTER TABLE `lupo_agent_properties` ADD INDEX IF NOT EXISTS `idx_domain_id` (`domain_id`);
ALTER TABLE `lupo_agent_properties` ADD INDEX IF NOT EXISTS `idx_is_deleted` (`is_deleted`);
ALTER TABLE `lupo_agent_properties` ADD INDEX IF NOT EXISTS `idx_property_key` (`property_key`);
ALTER TABLE `lupo_agent_properties` ADD INDEX IF NOT EXISTS `idx_updated_ymdhis` (`updated_ymdhis`);
ALTER TABLE `lupo_agent_properties` ADD UNIQUE INDEX IF NOT EXISTS `unique_agent_domain_property` (`actor_id`, `domain_id`, `property_key`);

CREATE TABLE IF NOT EXISTS `lupo_agent_registry` (
  `agent_registry_id` bigint NOT NULL auto_increment,
  `agent_registry_parent_id` bigint COMMENT 'if this is a alias',
  `code` varchar(64) NOT NULL,
  `name` varchar(255) NOT NULL,
  `layer` varchar(64) NOT NULL,
  `is_required` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `is_kernel` tinyint(1) NOT NULL DEFAULT 0,
  `dedicated_slot` int,
  `created_ymdhis` bigint NOT NULL,
  `classification_json` json COMMENT 'Agent classification and routing identity metadata (agent_class, subclass, routing_bias, capabilities)',
  `metadata` json NOT NULL,
  PRIMARY KEY (`agent_registry_id`),
  UNIQUE KEY `unique_code` (`code`)
);

ALTER TABLE `lupo_agent_registry` ADD COLUMN IF NOT EXISTS `agent_registry_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_agent_registry` ADD COLUMN IF NOT EXISTS `agent_registry_parent_id` bigint COMMENT 'if this is a alias';
ALTER TABLE `lupo_agent_registry` ADD COLUMN IF NOT EXISTS `code` varchar(64) NOT NULL;
ALTER TABLE `lupo_agent_registry` ADD COLUMN IF NOT EXISTS `name` varchar(255) NOT NULL;
ALTER TABLE `lupo_agent_registry` ADD COLUMN IF NOT EXISTS `layer` varchar(64) NOT NULL;
ALTER TABLE `lupo_agent_registry` ADD COLUMN IF NOT EXISTS `is_required` tinyint(1) NOT NULL DEFAULT 0;
ALTER TABLE `lupo_agent_registry` ADD COLUMN IF NOT EXISTS `is_active` tinyint(1) NOT NULL DEFAULT 0;
ALTER TABLE `lupo_agent_registry` ADD COLUMN IF NOT EXISTS `is_kernel` tinyint(1) NOT NULL DEFAULT 0;
ALTER TABLE `lupo_agent_registry` ADD COLUMN IF NOT EXISTS `dedicated_slot` int;
ALTER TABLE `lupo_agent_registry` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_agent_registry` ADD COLUMN IF NOT EXISTS `classification_json` json COMMENT 'Agent classification and routing identity metadata (agent_class, subclass, routing_bias, capabilities)';
ALTER TABLE `lupo_agent_registry` ADD COLUMN IF NOT EXISTS `metadata` json NOT NULL;
ALTER TABLE `lupo_agent_registry` MODIFY COLUMN `agent_registry_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_agent_registry` MODIFY COLUMN `agent_registry_parent_id` bigint COMMENT 'if this is a alias';
ALTER TABLE `lupo_agent_registry` MODIFY COLUMN `code` varchar(64) NOT NULL;
ALTER TABLE `lupo_agent_registry` MODIFY COLUMN `name` varchar(255) NOT NULL;
ALTER TABLE `lupo_agent_registry` MODIFY COLUMN `layer` varchar(64) NOT NULL;
ALTER TABLE `lupo_agent_registry` MODIFY COLUMN `is_required` tinyint(1) NOT NULL DEFAULT 0;
ALTER TABLE `lupo_agent_registry` MODIFY COLUMN `is_active` tinyint(1) NOT NULL DEFAULT 0;
ALTER TABLE `lupo_agent_registry` MODIFY COLUMN `is_kernel` tinyint(1) NOT NULL DEFAULT 0;
ALTER TABLE `lupo_agent_registry` MODIFY COLUMN `dedicated_slot` int;
ALTER TABLE `lupo_agent_registry` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_agent_registry` MODIFY COLUMN `classification_json` json COMMENT 'Agent classification and routing identity metadata (agent_class, subclass, routing_bias, capabilities)';
ALTER TABLE `lupo_agent_registry` MODIFY COLUMN `metadata` json NOT NULL;
ALTER TABLE `lupo_agent_registry` ADD PRIMARY KEY (`agent_registry_id`);
ALTER TABLE `lupo_agent_registry` ADD UNIQUE INDEX IF NOT EXISTS `unique_code` (`code`);

CREATE TABLE IF NOT EXISTS `lupo_agent_tool_calls` (
  `agent_tool_call_id` bigint NOT NULL auto_increment,
  `agent_id` bigint NOT NULL COMMENT 'Which agent initiated the call',
  `faucet_id` bigint COMMENT 'Which faucet was used (if any)',
  `domain_id` bigint NOT NULL COMMENT 'Domain context of the call',
  `tool_name` varchar(150) NOT NULL COMMENT 'Name of the tool or action invoked',
  `action_type` varchar(100) COMMENT 'Type of action (llm_call, faucet_spawn, search, codegen, etc.)',
  `input_json` longtext COMMENT 'Serialized input parameters',
  `output_json` longtext COMMENT 'Serialized output or result',
  `provider` varchar(50) COMMENT 'LLM provider (openai, anthropic, google, deepseek, etc.)',
  `model_name` varchar(150) COMMENT 'Model used for this call',
  `tokens_prompt` int DEFAULT 0,
  `tokens_completion` int DEFAULT 0,
  `tokens_total` int DEFAULT 0,
  `cost_usd` decimal(10,6) DEFAULT 0.000000,
  `latency_ms` int DEFAULT 0,
  `status` varchar(50) DEFAULT 'success' COMMENT 'success, error, timeout, rejected',
  `error_message` text,
  `parent_call_id` bigint COMMENT 'If this call was spawned by another call',
  `thread_id` bigint COMMENT 'Dialog thread this call belongs to',
  `message_id` bigint COMMENT 'Dialog message that triggered this call',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when call started',
  `completed_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when call finished',
  PRIMARY KEY (`agent_tool_call_id`),
  KEY `idx_agent` (`agent_id`),
  KEY `idx_domain` (`domain_id`),
  KEY `idx_faucet` (`faucet_id`),
  KEY `idx_message` (`message_id`),
  KEY `idx_model` (`model_name`),
  KEY `idx_parent` (`parent_call_id`),
  KEY `idx_provider` (`provider`),
  KEY `idx_thread` (`thread_id`)
);

ALTER TABLE `lupo_agent_tool_calls` ADD COLUMN IF NOT EXISTS `agent_tool_call_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_agent_tool_calls` ADD COLUMN IF NOT EXISTS `agent_id` bigint NOT NULL COMMENT 'Which agent initiated the call';
ALTER TABLE `lupo_agent_tool_calls` ADD COLUMN IF NOT EXISTS `faucet_id` bigint COMMENT 'Which faucet was used (if any)';
ALTER TABLE `lupo_agent_tool_calls` ADD COLUMN IF NOT EXISTS `domain_id` bigint NOT NULL COMMENT 'Domain context of the call';
ALTER TABLE `lupo_agent_tool_calls` ADD COLUMN IF NOT EXISTS `tool_name` varchar(150) NOT NULL COMMENT 'Name of the tool or action invoked';
ALTER TABLE `lupo_agent_tool_calls` ADD COLUMN IF NOT EXISTS `action_type` varchar(100) COMMENT 'Type of action (llm_call, faucet_spawn, search, codegen, etc.)';
ALTER TABLE `lupo_agent_tool_calls` ADD COLUMN IF NOT EXISTS `input_json` longtext COMMENT 'Serialized input parameters';
ALTER TABLE `lupo_agent_tool_calls` ADD COLUMN IF NOT EXISTS `output_json` longtext COMMENT 'Serialized output or result';
ALTER TABLE `lupo_agent_tool_calls` ADD COLUMN IF NOT EXISTS `provider` varchar(50) COMMENT 'LLM provider (openai, anthropic, google, deepseek, etc.)';
ALTER TABLE `lupo_agent_tool_calls` ADD COLUMN IF NOT EXISTS `model_name` varchar(150) COMMENT 'Model used for this call';
ALTER TABLE `lupo_agent_tool_calls` ADD COLUMN IF NOT EXISTS `tokens_prompt` int DEFAULT 0;
ALTER TABLE `lupo_agent_tool_calls` ADD COLUMN IF NOT EXISTS `tokens_completion` int DEFAULT 0;
ALTER TABLE `lupo_agent_tool_calls` ADD COLUMN IF NOT EXISTS `tokens_total` int DEFAULT 0;
ALTER TABLE `lupo_agent_tool_calls` ADD COLUMN IF NOT EXISTS `cost_usd` decimal(10,6) DEFAULT 0.000000;
ALTER TABLE `lupo_agent_tool_calls` ADD COLUMN IF NOT EXISTS `latency_ms` int DEFAULT 0;
ALTER TABLE `lupo_agent_tool_calls` ADD COLUMN IF NOT EXISTS `status` varchar(50) DEFAULT 'success' COMMENT 'success, error, timeout, rejected';
ALTER TABLE `lupo_agent_tool_calls` ADD COLUMN IF NOT EXISTS `error_message` text;
ALTER TABLE `lupo_agent_tool_calls` ADD COLUMN IF NOT EXISTS `parent_call_id` bigint COMMENT 'If this call was spawned by another call';
ALTER TABLE `lupo_agent_tool_calls` ADD COLUMN IF NOT EXISTS `thread_id` bigint COMMENT 'Dialog thread this call belongs to';
ALTER TABLE `lupo_agent_tool_calls` ADD COLUMN IF NOT EXISTS `message_id` bigint COMMENT 'Dialog message that triggered this call';
ALTER TABLE `lupo_agent_tool_calls` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when call started';
ALTER TABLE `lupo_agent_tool_calls` ADD COLUMN IF NOT EXISTS `completed_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when call finished';
ALTER TABLE `lupo_agent_tool_calls` MODIFY COLUMN `agent_tool_call_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_agent_tool_calls` MODIFY COLUMN `agent_id` bigint NOT NULL COMMENT 'Which agent initiated the call';
ALTER TABLE `lupo_agent_tool_calls` MODIFY COLUMN `faucet_id` bigint COMMENT 'Which faucet was used (if any)';
ALTER TABLE `lupo_agent_tool_calls` MODIFY COLUMN `domain_id` bigint NOT NULL COMMENT 'Domain context of the call';
ALTER TABLE `lupo_agent_tool_calls` MODIFY COLUMN `tool_name` varchar(150) NOT NULL COMMENT 'Name of the tool or action invoked';
ALTER TABLE `lupo_agent_tool_calls` MODIFY COLUMN `action_type` varchar(100) COMMENT 'Type of action (llm_call, faucet_spawn, search, codegen, etc.)';
ALTER TABLE `lupo_agent_tool_calls` MODIFY COLUMN `input_json` longtext COMMENT 'Serialized input parameters';
ALTER TABLE `lupo_agent_tool_calls` MODIFY COLUMN `output_json` longtext COMMENT 'Serialized output or result';
ALTER TABLE `lupo_agent_tool_calls` MODIFY COLUMN `provider` varchar(50) COMMENT 'LLM provider (openai, anthropic, google, deepseek, etc.)';
ALTER TABLE `lupo_agent_tool_calls` MODIFY COLUMN `model_name` varchar(150) COMMENT 'Model used for this call';
ALTER TABLE `lupo_agent_tool_calls` MODIFY COLUMN `tokens_prompt` int DEFAULT 0;
ALTER TABLE `lupo_agent_tool_calls` MODIFY COLUMN `tokens_completion` int DEFAULT 0;
ALTER TABLE `lupo_agent_tool_calls` MODIFY COLUMN `tokens_total` int DEFAULT 0;
ALTER TABLE `lupo_agent_tool_calls` MODIFY COLUMN `cost_usd` decimal(10,6) DEFAULT 0.000000;
ALTER TABLE `lupo_agent_tool_calls` MODIFY COLUMN `latency_ms` int DEFAULT 0;
ALTER TABLE `lupo_agent_tool_calls` MODIFY COLUMN `status` varchar(50) DEFAULT 'success' COMMENT 'success, error, timeout, rejected';
ALTER TABLE `lupo_agent_tool_calls` MODIFY COLUMN `error_message` text;
ALTER TABLE `lupo_agent_tool_calls` MODIFY COLUMN `parent_call_id` bigint COMMENT 'If this call was spawned by another call';
ALTER TABLE `lupo_agent_tool_calls` MODIFY COLUMN `thread_id` bigint COMMENT 'Dialog thread this call belongs to';
ALTER TABLE `lupo_agent_tool_calls` MODIFY COLUMN `message_id` bigint COMMENT 'Dialog message that triggered this call';
ALTER TABLE `lupo_agent_tool_calls` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when call started';
ALTER TABLE `lupo_agent_tool_calls` MODIFY COLUMN `completed_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when call finished';
ALTER TABLE `lupo_agent_tool_calls` ADD PRIMARY KEY (`agent_tool_call_id`);
ALTER TABLE `lupo_agent_tool_calls` ADD INDEX IF NOT EXISTS `idx_agent` (`agent_id`);
ALTER TABLE `lupo_agent_tool_calls` ADD INDEX IF NOT EXISTS `idx_domain` (`domain_id`);
ALTER TABLE `lupo_agent_tool_calls` ADD INDEX IF NOT EXISTS `idx_faucet` (`faucet_id`);
ALTER TABLE `lupo_agent_tool_calls` ADD INDEX IF NOT EXISTS `idx_message` (`message_id`);
ALTER TABLE `lupo_agent_tool_calls` ADD INDEX IF NOT EXISTS `idx_model` (`model_name`);
ALTER TABLE `lupo_agent_tool_calls` ADD INDEX IF NOT EXISTS `idx_parent` (`parent_call_id`);
ALTER TABLE `lupo_agent_tool_calls` ADD INDEX IF NOT EXISTS `idx_provider` (`provider`);
ALTER TABLE `lupo_agent_tool_calls` ADD INDEX IF NOT EXISTS `idx_thread` (`thread_id`);

CREATE TABLE IF NOT EXISTS `lupo_agent_versions` (
  `agent_version_id` bigint NOT NULL auto_increment,
  `agent_id` bigint NOT NULL,
  `version_label` varchar(64) NOT NULL,
  `semver_major` int DEFAULT 0,
  `semver_minor` int DEFAULT 0,
  `semver_patch` int DEFAULT 0,
  `version_notes` text,
  `version_hash` varchar(128),
  `previous_version_id` bigint,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` smallint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (`agent_version_id`),
  KEY `agent_id` (`agent_id`),
  KEY `semver_major` (`semver_major`, `semver_minor`, `semver_patch`),
  KEY `version_label` (`version_label`)
);

ALTER TABLE `lupo_agent_versions` ADD COLUMN IF NOT EXISTS `agent_version_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_agent_versions` ADD COLUMN IF NOT EXISTS `agent_id` bigint NOT NULL;
ALTER TABLE `lupo_agent_versions` ADD COLUMN IF NOT EXISTS `version_label` varchar(64) NOT NULL;
ALTER TABLE `lupo_agent_versions` ADD COLUMN IF NOT EXISTS `semver_major` int DEFAULT 0;
ALTER TABLE `lupo_agent_versions` ADD COLUMN IF NOT EXISTS `semver_minor` int DEFAULT 0;
ALTER TABLE `lupo_agent_versions` ADD COLUMN IF NOT EXISTS `semver_patch` int DEFAULT 0;
ALTER TABLE `lupo_agent_versions` ADD COLUMN IF NOT EXISTS `version_notes` text;
ALTER TABLE `lupo_agent_versions` ADD COLUMN IF NOT EXISTS `version_hash` varchar(128);
ALTER TABLE `lupo_agent_versions` ADD COLUMN IF NOT EXISTS `previous_version_id` bigint;
ALTER TABLE `lupo_agent_versions` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_agent_versions` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_agent_versions` ADD COLUMN IF NOT EXISTS `is_deleted` smallint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_agent_versions` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint;
ALTER TABLE `lupo_agent_versions` MODIFY COLUMN `agent_version_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_agent_versions` MODIFY COLUMN `agent_id` bigint NOT NULL;
ALTER TABLE `lupo_agent_versions` MODIFY COLUMN `version_label` varchar(64) NOT NULL;
ALTER TABLE `lupo_agent_versions` MODIFY COLUMN `semver_major` int DEFAULT 0;
ALTER TABLE `lupo_agent_versions` MODIFY COLUMN `semver_minor` int DEFAULT 0;
ALTER TABLE `lupo_agent_versions` MODIFY COLUMN `semver_patch` int DEFAULT 0;
ALTER TABLE `lupo_agent_versions` MODIFY COLUMN `version_notes` text;
ALTER TABLE `lupo_agent_versions` MODIFY COLUMN `version_hash` varchar(128);
ALTER TABLE `lupo_agent_versions` MODIFY COLUMN `previous_version_id` bigint;
ALTER TABLE `lupo_agent_versions` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_agent_versions` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_agent_versions` MODIFY COLUMN `is_deleted` smallint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_agent_versions` MODIFY COLUMN `deleted_ymdhis` bigint;
ALTER TABLE `lupo_agent_versions` ADD PRIMARY KEY (`agent_version_id`);
ALTER TABLE `lupo_agent_versions` ADD INDEX IF NOT EXISTS `agent_id` (`agent_id`);
ALTER TABLE `lupo_agent_versions` ADD INDEX IF NOT EXISTS `semver_major` (`semver_major`, `semver_minor`, `semver_patch`);
ALTER TABLE `lupo_agent_versions` ADD INDEX IF NOT EXISTS `version_label` (`version_label`);

CREATE TABLE IF NOT EXISTS `lupo_agents` (
  `agent_id` bigint NOT NULL auto_increment COMMENT 'Primary key for agent',
  `agent_key` varchar(100) NOT NULL COMMENT 'Canonical identifier (wolfie, lilith, maat, etc.)',
  `agent_name` varchar(150) NOT NULL COMMENT 'Human-readable name',
  `archetype` varchar(150) COMMENT 'Mythic or symbolic identity',
  `description` text COMMENT 'Agent description and purpose',
  `version` varchar(50) DEFAULT '1.0' COMMENT 'Agent version',
  `model_name` varchar(100),
  `is_global_authority` tinyint NOT NULL DEFAULT 0 COMMENT '1 = global authority agent',
  `is_internal_only` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Internal only flag',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
  `updated_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when updated',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted',
  `avg_response_time_ms` int DEFAULT 0,
  `total_tokens_processed` bigint DEFAULT 0 COMMENT 'Total tokens processed',
  `success_rate` float DEFAULT 1 COMMENT '0.0 to 1.0',
  `cost_per_1k_tokens` decimal(10,4) DEFAULT 0.0000 COMMENT 'For cost tracking',
  `temperature` float DEFAULT 0.7,
  `top_p` float DEFAULT 1,
  `max_tokens` int DEFAULT 2048,
  `presence_penalty` float DEFAULT 0,
  `frequency_penalty` float DEFAULT 0,
  `system_prompt` text,
  `provider` varchar(50) DEFAULT 'openai',
  `api_key_id` bigint COMMENT 'API key reference',
  `timeout_ms` int DEFAULT 20000,
  `safety_json` json,
  `response_format` varchar(50),
  PRIMARY KEY (`agent_id`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_is_global_authority` (`is_global_authority`),
  KEY `idx_updated_ymdhis` (`updated_ymdhis`),
  UNIQUE KEY `unique_agent_key` (`agent_key`)
);

ALTER TABLE `lupo_agents` ADD COLUMN IF NOT EXISTS `agent_id` bigint NOT NULL auto_increment COMMENT 'Primary key for agent';
ALTER TABLE `lupo_agents` ADD COLUMN IF NOT EXISTS `agent_key` varchar(100) NOT NULL COMMENT 'Canonical identifier (wolfie, lilith, maat, etc.)';
ALTER TABLE `lupo_agents` ADD COLUMN IF NOT EXISTS `agent_name` varchar(150) NOT NULL COMMENT 'Human-readable name';
ALTER TABLE `lupo_agents` ADD COLUMN IF NOT EXISTS `archetype` varchar(150) COMMENT 'Mythic or symbolic identity';
ALTER TABLE `lupo_agents` ADD COLUMN IF NOT EXISTS `description` text COMMENT 'Agent description and purpose';
ALTER TABLE `lupo_agents` ADD COLUMN IF NOT EXISTS `version` varchar(50) DEFAULT '1.0' COMMENT 'Agent version';
ALTER TABLE `lupo_agents` ADD COLUMN IF NOT EXISTS `model_name` varchar(100);
ALTER TABLE `lupo_agents` ADD COLUMN IF NOT EXISTS `is_global_authority` tinyint NOT NULL DEFAULT 0 COMMENT '1 = global authority agent';
ALTER TABLE `lupo_agents` ADD COLUMN IF NOT EXISTS `is_internal_only` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Internal only flag';
ALTER TABLE `lupo_agents` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created';
ALTER TABLE `lupo_agents` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when updated';
ALTER TABLE `lupo_agents` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted';
ALTER TABLE `lupo_agents` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted';
ALTER TABLE `lupo_agents` ADD COLUMN IF NOT EXISTS `avg_response_time_ms` int DEFAULT 0;
ALTER TABLE `lupo_agents` ADD COLUMN IF NOT EXISTS `total_tokens_processed` bigint DEFAULT 0 COMMENT 'Total tokens processed';
ALTER TABLE `lupo_agents` ADD COLUMN IF NOT EXISTS `success_rate` float DEFAULT 1 COMMENT '0.0 to 1.0';
ALTER TABLE `lupo_agents` ADD COLUMN IF NOT EXISTS `cost_per_1k_tokens` decimal(10,4) DEFAULT 0.0000 COMMENT 'For cost tracking';
ALTER TABLE `lupo_agents` ADD COLUMN IF NOT EXISTS `temperature` float DEFAULT 0.7;
ALTER TABLE `lupo_agents` ADD COLUMN IF NOT EXISTS `top_p` float DEFAULT 1;
ALTER TABLE `lupo_agents` ADD COLUMN IF NOT EXISTS `max_tokens` int DEFAULT 2048;
ALTER TABLE `lupo_agents` ADD COLUMN IF NOT EXISTS `presence_penalty` float DEFAULT 0;
ALTER TABLE `lupo_agents` ADD COLUMN IF NOT EXISTS `frequency_penalty` float DEFAULT 0;
ALTER TABLE `lupo_agents` ADD COLUMN IF NOT EXISTS `system_prompt` text;
ALTER TABLE `lupo_agents` ADD COLUMN IF NOT EXISTS `provider` varchar(50) DEFAULT 'openai';
ALTER TABLE `lupo_agents` ADD COLUMN IF NOT EXISTS `api_key_id` bigint COMMENT 'API key reference';
ALTER TABLE `lupo_agents` ADD COLUMN IF NOT EXISTS `timeout_ms` int DEFAULT 20000;
ALTER TABLE `lupo_agents` ADD COLUMN IF NOT EXISTS `safety_json` json;
ALTER TABLE `lupo_agents` ADD COLUMN IF NOT EXISTS `response_format` varchar(50);
ALTER TABLE `lupo_agents` MODIFY COLUMN `agent_id` bigint NOT NULL auto_increment COMMENT 'Primary key for agent';
ALTER TABLE `lupo_agents` MODIFY COLUMN `agent_key` varchar(100) NOT NULL COMMENT 'Canonical identifier (wolfie, lilith, maat, etc.)';
ALTER TABLE `lupo_agents` MODIFY COLUMN `agent_name` varchar(150) NOT NULL COMMENT 'Human-readable name';
ALTER TABLE `lupo_agents` MODIFY COLUMN `archetype` varchar(150) COMMENT 'Mythic or symbolic identity';
ALTER TABLE `lupo_agents` MODIFY COLUMN `description` text COMMENT 'Agent description and purpose';
ALTER TABLE `lupo_agents` MODIFY COLUMN `version` varchar(50) DEFAULT '1.0' COMMENT 'Agent version';
ALTER TABLE `lupo_agents` MODIFY COLUMN `model_name` varchar(100);
ALTER TABLE `lupo_agents` MODIFY COLUMN `is_global_authority` tinyint NOT NULL DEFAULT 0 COMMENT '1 = global authority agent';
ALTER TABLE `lupo_agents` MODIFY COLUMN `is_internal_only` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Internal only flag';
ALTER TABLE `lupo_agents` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created';
ALTER TABLE `lupo_agents` MODIFY COLUMN `updated_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when updated';
ALTER TABLE `lupo_agents` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted';
ALTER TABLE `lupo_agents` MODIFY COLUMN `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted';
ALTER TABLE `lupo_agents` MODIFY COLUMN `avg_response_time_ms` int DEFAULT 0;
ALTER TABLE `lupo_agents` MODIFY COLUMN `total_tokens_processed` bigint DEFAULT 0 COMMENT 'Total tokens processed';
ALTER TABLE `lupo_agents` MODIFY COLUMN `success_rate` float DEFAULT 1 COMMENT '0.0 to 1.0';
ALTER TABLE `lupo_agents` MODIFY COLUMN `cost_per_1k_tokens` decimal(10,4) DEFAULT 0.0000 COMMENT 'For cost tracking';
ALTER TABLE `lupo_agents` MODIFY COLUMN `temperature` float DEFAULT 0.7;
ALTER TABLE `lupo_agents` MODIFY COLUMN `top_p` float DEFAULT 1;
ALTER TABLE `lupo_agents` MODIFY COLUMN `max_tokens` int DEFAULT 2048;
ALTER TABLE `lupo_agents` MODIFY COLUMN `presence_penalty` float DEFAULT 0;
ALTER TABLE `lupo_agents` MODIFY COLUMN `frequency_penalty` float DEFAULT 0;
ALTER TABLE `lupo_agents` MODIFY COLUMN `system_prompt` text;
ALTER TABLE `lupo_agents` MODIFY COLUMN `provider` varchar(50) DEFAULT 'openai';
ALTER TABLE `lupo_agents` MODIFY COLUMN `api_key_id` bigint COMMENT 'API key reference';
ALTER TABLE `lupo_agents` MODIFY COLUMN `timeout_ms` int DEFAULT 20000;
ALTER TABLE `lupo_agents` MODIFY COLUMN `safety_json` json;
ALTER TABLE `lupo_agents` MODIFY COLUMN `response_format` varchar(50);
ALTER TABLE `lupo_agents` ADD PRIMARY KEY (`agent_id`);
ALTER TABLE `lupo_agents` ADD INDEX IF NOT EXISTS `idx_created_ymdhis` (`created_ymdhis`);
ALTER TABLE `lupo_agents` ADD INDEX IF NOT EXISTS `idx_is_deleted` (`is_deleted`);
ALTER TABLE `lupo_agents` ADD INDEX IF NOT EXISTS `idx_is_global_authority` (`is_global_authority`);
ALTER TABLE `lupo_agents` ADD INDEX IF NOT EXISTS `idx_updated_ymdhis` (`updated_ymdhis`);
ALTER TABLE `lupo_agents` ADD UNIQUE INDEX IF NOT EXISTS `unique_agent_key` (`agent_key`);

CREATE TABLE IF NOT EXISTS `lupo_analytics_campaign_vars_daily` (
  `analytics_campaign_vars_daily_id` bigint NOT NULL auto_increment,
  `date_ymd` int NOT NULL COMMENT 'Date in YYYYMMDD format',
  `var_key` varchar(100) NOT NULL COMMENT 'The campaign/query variable name (e.g., utm_source, ref, etc.)',
  `var_value` varchar(500) NOT NULL COMMENT 'The value of the campaign variable',
  `visits` int NOT NULL DEFAULT 0 COMMENT 'Number of visits for this variable/value on this date',
  `created_ymdhis` datetime NOT NULL,
  PRIMARY KEY (`analytics_campaign_vars_daily_id`),
  KEY `date_ymd` (`date_ymd`),
  KEY `var_key` (`var_key`),
  KEY `var_value` (`var_value`)
);

ALTER TABLE `lupo_analytics_campaign_vars_daily` ADD COLUMN IF NOT EXISTS `analytics_campaign_vars_daily_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_analytics_campaign_vars_daily` ADD COLUMN IF NOT EXISTS `date_ymd` int NOT NULL COMMENT 'Date in YYYYMMDD format';
ALTER TABLE `lupo_analytics_campaign_vars_daily` ADD COLUMN IF NOT EXISTS `var_key` varchar(100) NOT NULL COMMENT 'The campaign/query variable name (e.g., utm_source, ref, etc.)';
ALTER TABLE `lupo_analytics_campaign_vars_daily` ADD COLUMN IF NOT EXISTS `var_value` varchar(500) NOT NULL COMMENT 'The value of the campaign variable';
ALTER TABLE `lupo_analytics_campaign_vars_daily` ADD COLUMN IF NOT EXISTS `visits` int NOT NULL DEFAULT 0 COMMENT 'Number of visits for this variable/value on this date';
ALTER TABLE `lupo_analytics_campaign_vars_daily` ADD COLUMN IF NOT EXISTS `created_ymdhis` datetime NOT NULL;
ALTER TABLE `lupo_analytics_campaign_vars_daily` MODIFY COLUMN `analytics_campaign_vars_daily_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_analytics_campaign_vars_daily` MODIFY COLUMN `date_ymd` int NOT NULL COMMENT 'Date in YYYYMMDD format';
ALTER TABLE `lupo_analytics_campaign_vars_daily` MODIFY COLUMN `var_key` varchar(100) NOT NULL COMMENT 'The campaign/query variable name (e.g., utm_source, ref, etc.)';
ALTER TABLE `lupo_analytics_campaign_vars_daily` MODIFY COLUMN `var_value` varchar(500) NOT NULL COMMENT 'The value of the campaign variable';
ALTER TABLE `lupo_analytics_campaign_vars_daily` MODIFY COLUMN `visits` int NOT NULL DEFAULT 0 COMMENT 'Number of visits for this variable/value on this date';
ALTER TABLE `lupo_analytics_campaign_vars_daily` MODIFY COLUMN `created_ymdhis` datetime NOT NULL;
ALTER TABLE `lupo_analytics_campaign_vars_daily` ADD PRIMARY KEY (`analytics_campaign_vars_daily_id`);
ALTER TABLE `lupo_analytics_campaign_vars_daily` ADD INDEX IF NOT EXISTS `date_ymd` (`date_ymd`);
ALTER TABLE `lupo_analytics_campaign_vars_daily` ADD INDEX IF NOT EXISTS `var_key` (`var_key`);
ALTER TABLE `lupo_analytics_campaign_vars_daily` ADD INDEX IF NOT EXISTS `var_value` (`var_value`);

CREATE TABLE IF NOT EXISTS `lupo_analytics_campaign_vars_monthly` (
  `analytics_campaign_vars_monthly_id` bigint NOT NULL auto_increment COMMENT 'Primary key for monthly campaign/query variable tracking',
  `content_id` bigint NOT NULL DEFAULT 0 COMMENT 'Content ID of the visited page (0 = non-content page)',
  `url_path` varchar(500) NOT NULL DEFAULT '' COMMENT 'Normalized URL path of the visited page',
  `var_key` varchar(100) NOT NULL COMMENT 'Name of the query variable (utm_source, q, search, etc.)',
  `var_value` varchar(500) NOT NULL COMMENT 'Value of the query variable',
  `date_ym` bigint NOT NULL COMMENT 'UTC YYYYMM representing the monthly bucket',
  `visits` int NOT NULL DEFAULT 0 COMMENT 'Number of visits with this variable/value pair in this month',
  `created_ymdhis` bigint NOT NULL COMMENT 'Record creation bigint (UTC YYYYMMDDHHMMSS)',
  `updated_ymdhis` bigint NOT NULL COMMENT 'Record update bigint (UTC YYYYMMDDHHMMSS)',
  PRIMARY KEY (`analytics_campaign_vars_monthly_id`),
  KEY `idx_content` (`content_id`, `date_ym`),
  KEY `idx_created` (`created_ymdhis`),
  KEY `idx_domain_month` (`date_ym`),
  KEY `idx_updated` (`updated_ymdhis`),
  KEY `idx_var_key` (`var_key`, `date_ym`),
  KEY `idx_var_value` (`var_value`, `date_ym`),
  UNIQUE KEY `uq_campaign_var_month` (`var_key`, `var_value`, `date_ym`)
);

ALTER TABLE `lupo_analytics_campaign_vars_monthly` ADD COLUMN IF NOT EXISTS `analytics_campaign_vars_monthly_id` bigint NOT NULL auto_increment COMMENT 'Primary key for monthly campaign/query variable tracking';
ALTER TABLE `lupo_analytics_campaign_vars_monthly` ADD COLUMN IF NOT EXISTS `content_id` bigint NOT NULL DEFAULT 0 COMMENT 'Content ID of the visited page (0 = non-content page)';
ALTER TABLE `lupo_analytics_campaign_vars_monthly` ADD COLUMN IF NOT EXISTS `url_path` varchar(500) NOT NULL DEFAULT '' COMMENT 'Normalized URL path of the visited page';
ALTER TABLE `lupo_analytics_campaign_vars_monthly` ADD COLUMN IF NOT EXISTS `var_key` varchar(100) NOT NULL COMMENT 'Name of the query variable (utm_source, q, search, etc.)';
ALTER TABLE `lupo_analytics_campaign_vars_monthly` ADD COLUMN IF NOT EXISTS `var_value` varchar(500) NOT NULL COMMENT 'Value of the query variable';
ALTER TABLE `lupo_analytics_campaign_vars_monthly` ADD COLUMN IF NOT EXISTS `date_ym` bigint NOT NULL COMMENT 'UTC YYYYMM representing the monthly bucket';
ALTER TABLE `lupo_analytics_campaign_vars_monthly` ADD COLUMN IF NOT EXISTS `visits` int NOT NULL DEFAULT 0 COMMENT 'Number of visits with this variable/value pair in this month';
ALTER TABLE `lupo_analytics_campaign_vars_monthly` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'Record creation bigint (UTC YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_analytics_campaign_vars_monthly` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL COMMENT 'Record update bigint (UTC YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_analytics_campaign_vars_monthly` MODIFY COLUMN `analytics_campaign_vars_monthly_id` bigint NOT NULL auto_increment COMMENT 'Primary key for monthly campaign/query variable tracking';
ALTER TABLE `lupo_analytics_campaign_vars_monthly` MODIFY COLUMN `content_id` bigint NOT NULL DEFAULT 0 COMMENT 'Content ID of the visited page (0 = non-content page)';
ALTER TABLE `lupo_analytics_campaign_vars_monthly` MODIFY COLUMN `url_path` varchar(500) NOT NULL DEFAULT '' COMMENT 'Normalized URL path of the visited page';
ALTER TABLE `lupo_analytics_campaign_vars_monthly` MODIFY COLUMN `var_key` varchar(100) NOT NULL COMMENT 'Name of the query variable (utm_source, q, search, etc.)';
ALTER TABLE `lupo_analytics_campaign_vars_monthly` MODIFY COLUMN `var_value` varchar(500) NOT NULL COMMENT 'Value of the query variable';
ALTER TABLE `lupo_analytics_campaign_vars_monthly` MODIFY COLUMN `date_ym` bigint NOT NULL COMMENT 'UTC YYYYMM representing the monthly bucket';
ALTER TABLE `lupo_analytics_campaign_vars_monthly` MODIFY COLUMN `visits` int NOT NULL DEFAULT 0 COMMENT 'Number of visits with this variable/value pair in this month';
ALTER TABLE `lupo_analytics_campaign_vars_monthly` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'Record creation bigint (UTC YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_analytics_campaign_vars_monthly` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL COMMENT 'Record update bigint (UTC YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_analytics_campaign_vars_monthly` ADD PRIMARY KEY (`analytics_campaign_vars_monthly_id`);
ALTER TABLE `lupo_analytics_campaign_vars_monthly` ADD INDEX IF NOT EXISTS `idx_content` (`content_id`, `date_ym`);
ALTER TABLE `lupo_analytics_campaign_vars_monthly` ADD INDEX IF NOT EXISTS `idx_created` (`created_ymdhis`);
ALTER TABLE `lupo_analytics_campaign_vars_monthly` ADD INDEX IF NOT EXISTS `idx_domain_month` (`date_ym`);
ALTER TABLE `lupo_analytics_campaign_vars_monthly` ADD INDEX IF NOT EXISTS `idx_updated` (`updated_ymdhis`);
ALTER TABLE `lupo_analytics_campaign_vars_monthly` ADD INDEX IF NOT EXISTS `idx_var_key` (`var_key`, `date_ym`);
ALTER TABLE `lupo_analytics_campaign_vars_monthly` ADD INDEX IF NOT EXISTS `idx_var_value` (`var_value`, `date_ym`);
ALTER TABLE `lupo_analytics_campaign_vars_monthly` ADD UNIQUE INDEX IF NOT EXISTS `uq_campaign_var_month` (`var_key`, `var_value`, `date_ym`);

CREATE TABLE IF NOT EXISTS `lupo_analytics_referers_daily` (
  `analytics_referers_daily_id` bigint NOT NULL auto_increment COMMENT 'Primary key for daily referer records',
  `content_id` bigint NOT NULL DEFAULT 0 COMMENT 'Content ID of the visited page (0 = non-content page)',
  `url_path` varchar(500) NOT NULL DEFAULT '' COMMENT 'Normalized URL path of the visited page',
  `referer_content_id` bigint NOT NULL DEFAULT 0 COMMENT 'Content ID of the referer page (0 = direct visit)',
  `referer_url_path` varchar(500) NOT NULL DEFAULT '' COMMENT 'Normalized URL path of the referer page',
  `parent_id` bigint NOT NULL DEFAULT 0 COMMENT 'Parent referer record for hierarchical analysis',
  `level` int NOT NULL DEFAULT 1 COMMENT 'Depth level in the referer tree',
  `group_id` bigint NOT NULL DEFAULT 0 COMMENT 'Group associated with this content',
  `date_ymd` bigint NOT NULL COMMENT 'UTC YYYYMMDD representing the daily bucket',
  `visits` int NOT NULL DEFAULT 0 COMMENT 'Total visits for this page on this day',
  `direct_visits` int NOT NULL DEFAULT 0 COMMENT 'Visits with no referer (entry points)',
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  PRIMARY KEY (`analytics_referers_daily_id`),
  KEY `idx_content` (`content_id`, `date_ymd`),
  KEY `idx_created` (`created_ymdhis`),
  KEY `idx_domain_date` (`date_ymd`),
  KEY `idx_group` (`group_id`, `date_ymd`),
  KEY `idx_level` (`level`, `date_ymd`),
  KEY `idx_referer` (`referer_content_id`, `date_ymd`),
  KEY `idx_updated` (`updated_ymdhis`),
  UNIQUE KEY `uq_referer_daily` (`content_id`, `referer_content_id`, `date_ymd`)
);

ALTER TABLE `lupo_analytics_referers_daily` ADD COLUMN IF NOT EXISTS `analytics_referers_daily_id` bigint NOT NULL auto_increment COMMENT 'Primary key for daily referer records';
ALTER TABLE `lupo_analytics_referers_daily` ADD COLUMN IF NOT EXISTS `content_id` bigint NOT NULL DEFAULT 0 COMMENT 'Content ID of the visited page (0 = non-content page)';
ALTER TABLE `lupo_analytics_referers_daily` ADD COLUMN IF NOT EXISTS `url_path` varchar(500) NOT NULL DEFAULT '' COMMENT 'Normalized URL path of the visited page';
ALTER TABLE `lupo_analytics_referers_daily` ADD COLUMN IF NOT EXISTS `referer_content_id` bigint NOT NULL DEFAULT 0 COMMENT 'Content ID of the referer page (0 = direct visit)';
ALTER TABLE `lupo_analytics_referers_daily` ADD COLUMN IF NOT EXISTS `referer_url_path` varchar(500) NOT NULL DEFAULT '' COMMENT 'Normalized URL path of the referer page';
ALTER TABLE `lupo_analytics_referers_daily` ADD COLUMN IF NOT EXISTS `parent_id` bigint NOT NULL DEFAULT 0 COMMENT 'Parent referer record for hierarchical analysis';
ALTER TABLE `lupo_analytics_referers_daily` ADD COLUMN IF NOT EXISTS `level` int NOT NULL DEFAULT 1 COMMENT 'Depth level in the referer tree';
ALTER TABLE `lupo_analytics_referers_daily` ADD COLUMN IF NOT EXISTS `group_id` bigint NOT NULL DEFAULT 0 COMMENT 'Group associated with this content';
ALTER TABLE `lupo_analytics_referers_daily` ADD COLUMN IF NOT EXISTS `date_ymd` bigint NOT NULL COMMENT 'UTC YYYYMMDD representing the daily bucket';
ALTER TABLE `lupo_analytics_referers_daily` ADD COLUMN IF NOT EXISTS `visits` int NOT NULL DEFAULT 0 COMMENT 'Total visits for this page on this day';
ALTER TABLE `lupo_analytics_referers_daily` ADD COLUMN IF NOT EXISTS `direct_visits` int NOT NULL DEFAULT 0 COMMENT 'Visits with no referer (entry points)';
ALTER TABLE `lupo_analytics_referers_daily` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_analytics_referers_daily` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_analytics_referers_daily` MODIFY COLUMN `analytics_referers_daily_id` bigint NOT NULL auto_increment COMMENT 'Primary key for daily referer records';
ALTER TABLE `lupo_analytics_referers_daily` MODIFY COLUMN `content_id` bigint NOT NULL DEFAULT 0 COMMENT 'Content ID of the visited page (0 = non-content page)';
ALTER TABLE `lupo_analytics_referers_daily` MODIFY COLUMN `url_path` varchar(500) NOT NULL DEFAULT '' COMMENT 'Normalized URL path of the visited page';
ALTER TABLE `lupo_analytics_referers_daily` MODIFY COLUMN `referer_content_id` bigint NOT NULL DEFAULT 0 COMMENT 'Content ID of the referer page (0 = direct visit)';
ALTER TABLE `lupo_analytics_referers_daily` MODIFY COLUMN `referer_url_path` varchar(500) NOT NULL DEFAULT '' COMMENT 'Normalized URL path of the referer page';
ALTER TABLE `lupo_analytics_referers_daily` MODIFY COLUMN `parent_id` bigint NOT NULL DEFAULT 0 COMMENT 'Parent referer record for hierarchical analysis';
ALTER TABLE `lupo_analytics_referers_daily` MODIFY COLUMN `level` int NOT NULL DEFAULT 1 COMMENT 'Depth level in the referer tree';
ALTER TABLE `lupo_analytics_referers_daily` MODIFY COLUMN `group_id` bigint NOT NULL DEFAULT 0 COMMENT 'Group associated with this content';
ALTER TABLE `lupo_analytics_referers_daily` MODIFY COLUMN `date_ymd` bigint NOT NULL COMMENT 'UTC YYYYMMDD representing the daily bucket';
ALTER TABLE `lupo_analytics_referers_daily` MODIFY COLUMN `visits` int NOT NULL DEFAULT 0 COMMENT 'Total visits for this page on this day';
ALTER TABLE `lupo_analytics_referers_daily` MODIFY COLUMN `direct_visits` int NOT NULL DEFAULT 0 COMMENT 'Visits with no referer (entry points)';
ALTER TABLE `lupo_analytics_referers_daily` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_analytics_referers_daily` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_analytics_referers_daily` ADD PRIMARY KEY (`analytics_referers_daily_id`);
ALTER TABLE `lupo_analytics_referers_daily` ADD INDEX IF NOT EXISTS `idx_content` (`content_id`, `date_ymd`);
ALTER TABLE `lupo_analytics_referers_daily` ADD INDEX IF NOT EXISTS `idx_created` (`created_ymdhis`);
ALTER TABLE `lupo_analytics_referers_daily` ADD INDEX IF NOT EXISTS `idx_domain_date` (`date_ymd`);
ALTER TABLE `lupo_analytics_referers_daily` ADD INDEX IF NOT EXISTS `idx_group` (`group_id`, `date_ymd`);
ALTER TABLE `lupo_analytics_referers_daily` ADD INDEX IF NOT EXISTS `idx_level` (`level`, `date_ymd`);
ALTER TABLE `lupo_analytics_referers_daily` ADD INDEX IF NOT EXISTS `idx_referer` (`referer_content_id`, `date_ymd`);
ALTER TABLE `lupo_analytics_referers_daily` ADD INDEX IF NOT EXISTS `idx_updated` (`updated_ymdhis`);
ALTER TABLE `lupo_analytics_referers_daily` ADD UNIQUE INDEX IF NOT EXISTS `uq_referer_daily` (`content_id`, `referer_content_id`, `date_ymd`);

CREATE TABLE IF NOT EXISTS `lupo_analytics_referers_monthly` (
  `analytics_referers_monthly_id` bigint NOT NULL auto_increment COMMENT 'Primary key for monthly referer records',
  `content_id` bigint NOT NULL DEFAULT 0 COMMENT 'Content ID of the visited page (0 = non-content page)',
  `url_path` varchar(500) NOT NULL COMMENT 'raw, full URL',
  `referer_content_id` bigint NOT NULL DEFAULT 0 COMMENT 'Content ID of the referer page (0 = direct visit)',
  `referer_url_path` varchar(500) NOT NULL COMMENT 'raw, full URL',
  `parent_id` bigint NOT NULL DEFAULT 0 COMMENT 'Parent referer record for hierarchical analysis',
  `level` int NOT NULL DEFAULT 1 COMMENT 'Depth level in the referer tree',
  `group_id` bigint NOT NULL DEFAULT 0 COMMENT 'Group associated with this content',
  `date_ym` bigint NOT NULL COMMENT 'UTC YYYYMM representing the monthly bucket',
  `visits` int NOT NULL DEFAULT 0 COMMENT 'Total visits for this refererâ†’page path in this month',
  `direct_visits` int NOT NULL DEFAULT 0 COMMENT 'Visits with no referer (entry points)',
  `created_ymdhis` bigint NOT NULL COMMENT 'Record creation bigint (UTC YYYYMMDDHHMMSS)',
  `updated_ymdhis` bigint NOT NULL COMMENT 'Record update bigint (UTC YYYYMMDDHHMMSS)',
  PRIMARY KEY (`analytics_referers_monthly_id`),
  KEY `idx_content` (`content_id`, `date_ym`),
  KEY `idx_created` (`created_ymdhis`),
  KEY `idx_domain_month` (`date_ym`),
  KEY `idx_group` (`group_id`, `date_ym`),
  KEY `idx_level` (`level`, `date_ym`),
  KEY `idx_referer` (`referer_content_id`, `date_ym`),
  KEY `idx_updated` (`updated_ymdhis`),
  UNIQUE KEY `uq_referer_month` (`content_id`, `referer_content_id`, `date_ym`)
);

ALTER TABLE `lupo_analytics_referers_monthly` ADD COLUMN IF NOT EXISTS `analytics_referers_monthly_id` bigint NOT NULL auto_increment COMMENT 'Primary key for monthly referer records';
ALTER TABLE `lupo_analytics_referers_monthly` ADD COLUMN IF NOT EXISTS `content_id` bigint NOT NULL DEFAULT 0 COMMENT 'Content ID of the visited page (0 = non-content page)';
ALTER TABLE `lupo_analytics_referers_monthly` ADD COLUMN IF NOT EXISTS `url_path` varchar(500) NOT NULL COMMENT 'raw, full URL';
ALTER TABLE `lupo_analytics_referers_monthly` ADD COLUMN IF NOT EXISTS `referer_content_id` bigint NOT NULL DEFAULT 0 COMMENT 'Content ID of the referer page (0 = direct visit)';
ALTER TABLE `lupo_analytics_referers_monthly` ADD COLUMN IF NOT EXISTS `referer_url_path` varchar(500) NOT NULL COMMENT 'raw, full URL';
ALTER TABLE `lupo_analytics_referers_monthly` ADD COLUMN IF NOT EXISTS `parent_id` bigint NOT NULL DEFAULT 0 COMMENT 'Parent referer record for hierarchical analysis';
ALTER TABLE `lupo_analytics_referers_monthly` ADD COLUMN IF NOT EXISTS `level` int NOT NULL DEFAULT 1 COMMENT 'Depth level in the referer tree';
ALTER TABLE `lupo_analytics_referers_monthly` ADD COLUMN IF NOT EXISTS `group_id` bigint NOT NULL DEFAULT 0 COMMENT 'Group associated with this content';
ALTER TABLE `lupo_analytics_referers_monthly` ADD COLUMN IF NOT EXISTS `date_ym` bigint NOT NULL COMMENT 'UTC YYYYMM representing the monthly bucket';
ALTER TABLE `lupo_analytics_referers_monthly` ADD COLUMN IF NOT EXISTS `visits` int NOT NULL DEFAULT 0 COMMENT 'Total visits for this refererâ†’page path in this month';
ALTER TABLE `lupo_analytics_referers_monthly` ADD COLUMN IF NOT EXISTS `direct_visits` int NOT NULL DEFAULT 0 COMMENT 'Visits with no referer (entry points)';
ALTER TABLE `lupo_analytics_referers_monthly` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'Record creation bigint (UTC YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_analytics_referers_monthly` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL COMMENT 'Record update bigint (UTC YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_analytics_referers_monthly` MODIFY COLUMN `analytics_referers_monthly_id` bigint NOT NULL auto_increment COMMENT 'Primary key for monthly referer records';
ALTER TABLE `lupo_analytics_referers_monthly` MODIFY COLUMN `content_id` bigint NOT NULL DEFAULT 0 COMMENT 'Content ID of the visited page (0 = non-content page)';
ALTER TABLE `lupo_analytics_referers_monthly` MODIFY COLUMN `url_path` varchar(500) NOT NULL COMMENT 'raw, full URL';
ALTER TABLE `lupo_analytics_referers_monthly` MODIFY COLUMN `referer_content_id` bigint NOT NULL DEFAULT 0 COMMENT 'Content ID of the referer page (0 = direct visit)';
ALTER TABLE `lupo_analytics_referers_monthly` MODIFY COLUMN `referer_url_path` varchar(500) NOT NULL COMMENT 'raw, full URL';
ALTER TABLE `lupo_analytics_referers_monthly` MODIFY COLUMN `parent_id` bigint NOT NULL DEFAULT 0 COMMENT 'Parent referer record for hierarchical analysis';
ALTER TABLE `lupo_analytics_referers_monthly` MODIFY COLUMN `level` int NOT NULL DEFAULT 1 COMMENT 'Depth level in the referer tree';
ALTER TABLE `lupo_analytics_referers_monthly` MODIFY COLUMN `group_id` bigint NOT NULL DEFAULT 0 COMMENT 'Group associated with this content';
ALTER TABLE `lupo_analytics_referers_monthly` MODIFY COLUMN `date_ym` bigint NOT NULL COMMENT 'UTC YYYYMM representing the monthly bucket';
ALTER TABLE `lupo_analytics_referers_monthly` MODIFY COLUMN `visits` int NOT NULL DEFAULT 0 COMMENT 'Total visits for this refererâ†’page path in this month';
ALTER TABLE `lupo_analytics_referers_monthly` MODIFY COLUMN `direct_visits` int NOT NULL DEFAULT 0 COMMENT 'Visits with no referer (entry points)';
ALTER TABLE `lupo_analytics_referers_monthly` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'Record creation bigint (UTC YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_analytics_referers_monthly` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL COMMENT 'Record update bigint (UTC YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_analytics_referers_monthly` ADD PRIMARY KEY (`analytics_referers_monthly_id`);
ALTER TABLE `lupo_analytics_referers_monthly` ADD INDEX IF NOT EXISTS `idx_content` (`content_id`, `date_ym`);
ALTER TABLE `lupo_analytics_referers_monthly` ADD INDEX IF NOT EXISTS `idx_created` (`created_ymdhis`);
ALTER TABLE `lupo_analytics_referers_monthly` ADD INDEX IF NOT EXISTS `idx_domain_month` (`date_ym`);
ALTER TABLE `lupo_analytics_referers_monthly` ADD INDEX IF NOT EXISTS `idx_group` (`group_id`, `date_ym`);
ALTER TABLE `lupo_analytics_referers_monthly` ADD INDEX IF NOT EXISTS `idx_level` (`level`, `date_ym`);
ALTER TABLE `lupo_analytics_referers_monthly` ADD INDEX IF NOT EXISTS `idx_referer` (`referer_content_id`, `date_ym`);
ALTER TABLE `lupo_analytics_referers_monthly` ADD INDEX IF NOT EXISTS `idx_updated` (`updated_ymdhis`);
ALTER TABLE `lupo_analytics_referers_monthly` ADD UNIQUE INDEX IF NOT EXISTS `uq_referer_month` (`content_id`, `referer_content_id`, `date_ym`);

CREATE TABLE IF NOT EXISTS `lupo_analytics_visits` (
  `analytics_visit_id` bigint NOT NULL auto_increment COMMENT 'Primary key for the visit tracking record',
  `session_id` varchar(100) NOT NULL COMMENT 'Session identifier',
  `actor_id` bigint NOT NULL DEFAULT 0 COMMENT 'Actor ID (0 = anonymous)',
  `content_id` bigint COMMENT 'Content being viewed (NULL for non-content pages)',
  `federations_node_id` bigint NOT NULL,
  `url_path` varchar(500) NOT NULL DEFAULT '' COMMENT 'Normalized URL path of the page view',
  `referer_url` varchar(500) COMMENT 'Full referer URL for this page view',
  `referer_domain` varchar(255) COMMENT 'Normalized referer domain',
  `referer_path` varchar(500) COMMENT 'Normalized referer path',
  `came_from` varchar(500) COMMENT 'Original entry referer for the session',
  `first_seen_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when this page was first seen in this session',
  `last_seen_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when this page was last seen in this session',
  `view_count` int NOT NULL DEFAULT 1 COMMENT 'Number of times this page was viewed in this session',
  `seconds_active` int NOT NULL DEFAULT 0 COMMENT 'Total seconds spent on this page during this session',
  `user_agent` varchar(255) COMMENT 'User agent for this page view',
  `ip_address` varchar(45) COMMENT 'IP address for this page view',
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  PRIMARY KEY (`analytics_visit_id`)
);

ALTER TABLE `lupo_analytics_visits` ADD COLUMN IF NOT EXISTS `analytics_visit_id` bigint NOT NULL auto_increment COMMENT 'Primary key for the visit tracking record';
ALTER TABLE `lupo_analytics_visits` ADD COLUMN IF NOT EXISTS `session_id` varchar(100) NOT NULL COMMENT 'Session identifier';
ALTER TABLE `lupo_analytics_visits` ADD COLUMN IF NOT EXISTS `actor_id` bigint NOT NULL DEFAULT 0 COMMENT 'Actor ID (0 = anonymous)';
ALTER TABLE `lupo_analytics_visits` ADD COLUMN IF NOT EXISTS `content_id` bigint COMMENT 'Content being viewed (NULL for non-content pages)';
ALTER TABLE `lupo_analytics_visits` ADD COLUMN IF NOT EXISTS `federations_node_id` bigint NOT NULL;
ALTER TABLE `lupo_analytics_visits` ADD COLUMN IF NOT EXISTS `url_path` varchar(500) NOT NULL DEFAULT '' COMMENT 'Normalized URL path of the page view';
ALTER TABLE `lupo_analytics_visits` ADD COLUMN IF NOT EXISTS `referer_url` varchar(500) COMMENT 'Full referer URL for this page view';
ALTER TABLE `lupo_analytics_visits` ADD COLUMN IF NOT EXISTS `referer_domain` varchar(255) COMMENT 'Normalized referer domain';
ALTER TABLE `lupo_analytics_visits` ADD COLUMN IF NOT EXISTS `referer_path` varchar(500) COMMENT 'Normalized referer path';
ALTER TABLE `lupo_analytics_visits` ADD COLUMN IF NOT EXISTS `came_from` varchar(500) COMMENT 'Original entry referer for the session';
ALTER TABLE `lupo_analytics_visits` ADD COLUMN IF NOT EXISTS `first_seen_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when this page was first seen in this session';
ALTER TABLE `lupo_analytics_visits` ADD COLUMN IF NOT EXISTS `last_seen_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when this page was last seen in this session';
ALTER TABLE `lupo_analytics_visits` ADD COLUMN IF NOT EXISTS `view_count` int NOT NULL DEFAULT 1 COMMENT 'Number of times this page was viewed in this session';
ALTER TABLE `lupo_analytics_visits` ADD COLUMN IF NOT EXISTS `seconds_active` int NOT NULL DEFAULT 0 COMMENT 'Total seconds spent on this page during this session';
ALTER TABLE `lupo_analytics_visits` ADD COLUMN IF NOT EXISTS `user_agent` varchar(255) COMMENT 'User agent for this page view';
ALTER TABLE `lupo_analytics_visits` ADD COLUMN IF NOT EXISTS `ip_address` varchar(45) COMMENT 'IP address for this page view';
ALTER TABLE `lupo_analytics_visits` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_analytics_visits` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_analytics_visits` MODIFY COLUMN `analytics_visit_id` bigint NOT NULL auto_increment COMMENT 'Primary key for the visit tracking record';
ALTER TABLE `lupo_analytics_visits` MODIFY COLUMN `session_id` varchar(100) NOT NULL COMMENT 'Session identifier';
ALTER TABLE `lupo_analytics_visits` MODIFY COLUMN `actor_id` bigint NOT NULL DEFAULT 0 COMMENT 'Actor ID (0 = anonymous)';
ALTER TABLE `lupo_analytics_visits` MODIFY COLUMN `content_id` bigint COMMENT 'Content being viewed (NULL for non-content pages)';
ALTER TABLE `lupo_analytics_visits` MODIFY COLUMN `federations_node_id` bigint NOT NULL;
ALTER TABLE `lupo_analytics_visits` MODIFY COLUMN `url_path` varchar(500) NOT NULL DEFAULT '' COMMENT 'Normalized URL path of the page view';
ALTER TABLE `lupo_analytics_visits` MODIFY COLUMN `referer_url` varchar(500) COMMENT 'Full referer URL for this page view';
ALTER TABLE `lupo_analytics_visits` MODIFY COLUMN `referer_domain` varchar(255) COMMENT 'Normalized referer domain';
ALTER TABLE `lupo_analytics_visits` MODIFY COLUMN `referer_path` varchar(500) COMMENT 'Normalized referer path';
ALTER TABLE `lupo_analytics_visits` MODIFY COLUMN `came_from` varchar(500) COMMENT 'Original entry referer for the session';
ALTER TABLE `lupo_analytics_visits` MODIFY COLUMN `first_seen_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when this page was first seen in this session';
ALTER TABLE `lupo_analytics_visits` MODIFY COLUMN `last_seen_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when this page was last seen in this session';
ALTER TABLE `lupo_analytics_visits` MODIFY COLUMN `view_count` int NOT NULL DEFAULT 1 COMMENT 'Number of times this page was viewed in this session';
ALTER TABLE `lupo_analytics_visits` MODIFY COLUMN `seconds_active` int NOT NULL DEFAULT 0 COMMENT 'Total seconds spent on this page during this session';
ALTER TABLE `lupo_analytics_visits` MODIFY COLUMN `user_agent` varchar(255) COMMENT 'User agent for this page view';
ALTER TABLE `lupo_analytics_visits` MODIFY COLUMN `ip_address` varchar(45) COMMENT 'IP address for this page view';
ALTER TABLE `lupo_analytics_visits` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_analytics_visits` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_analytics_visits` ADD PRIMARY KEY (`analytics_visit_id`);

CREATE TABLE IF NOT EXISTS `lupo_analytics_visits_daily` (
  `analytics_visits_daily_id` bigint NOT NULL auto_increment COMMENT 'Primary key for daily page visit statistics',
  `content_id` bigint NOT NULL DEFAULT 0 COMMENT 'Content ID of the visited page (0 = non-content page)',
  `url_path` varchar(500) NOT NULL DEFAULT '' COMMENT 'Normalized URL path of the visited page',
  `group_id` bigint NOT NULL DEFAULT 0 COMMENT 'Group associated with this content',
  `date_ymd` bigint NOT NULL COMMENT 'UTC YYYYMMDD representing the daily bucket',
  `visits` int NOT NULL DEFAULT 0 COMMENT 'Total number of visits to this page on this day',
  `unique_sessions` int NOT NULL DEFAULT 0 COMMENT 'Number of unique sessions that viewed this page',
  `unique_actors` int NOT NULL DEFAULT 0 COMMENT 'Number of unique logged-in actors that viewed this page',
  `direct_visits` int NOT NULL DEFAULT 0 COMMENT 'Visits with no referer (entry points)',
  `internal_visits` int NOT NULL DEFAULT 0 COMMENT 'Visits from internal referers',
  `entry_count` int NOT NULL DEFAULT 0 COMMENT 'Number of times this page was the first page in a session',
  `exit_count` int NOT NULL DEFAULT 0 COMMENT 'Number of times this page was the last page in a session',
  `total_seconds` int NOT NULL DEFAULT 0 COMMENT 'Total time spent on this page across all sessions',
  `avg_seconds` int NOT NULL DEFAULT 0 COMMENT 'Average time spent on this page',
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  PRIMARY KEY (`analytics_visits_daily_id`),
  KEY `idx_content` (`content_id`, `date_ymd`),
  KEY `idx_created` (`created_ymdhis`),
  KEY `idx_date_ymd` (`date_ymd`),
  KEY `idx_group` (`group_id`, `date_ymd`),
  KEY `idx_updated` (`updated_ymdhis`),
  UNIQUE KEY `uq_visits_daily` (`content_id`, `date_ymd`)
);

ALTER TABLE `lupo_analytics_visits_daily` ADD COLUMN IF NOT EXISTS `analytics_visits_daily_id` bigint NOT NULL auto_increment COMMENT 'Primary key for daily page visit statistics';
ALTER TABLE `lupo_analytics_visits_daily` ADD COLUMN IF NOT EXISTS `content_id` bigint NOT NULL DEFAULT 0 COMMENT 'Content ID of the visited page (0 = non-content page)';
ALTER TABLE `lupo_analytics_visits_daily` ADD COLUMN IF NOT EXISTS `url_path` varchar(500) NOT NULL DEFAULT '' COMMENT 'Normalized URL path of the visited page';
ALTER TABLE `lupo_analytics_visits_daily` ADD COLUMN IF NOT EXISTS `group_id` bigint NOT NULL DEFAULT 0 COMMENT 'Group associated with this content';
ALTER TABLE `lupo_analytics_visits_daily` ADD COLUMN IF NOT EXISTS `date_ymd` bigint NOT NULL COMMENT 'UTC YYYYMMDD representing the daily bucket';
ALTER TABLE `lupo_analytics_visits_daily` ADD COLUMN IF NOT EXISTS `visits` int NOT NULL DEFAULT 0 COMMENT 'Total number of visits to this page on this day';
ALTER TABLE `lupo_analytics_visits_daily` ADD COLUMN IF NOT EXISTS `unique_sessions` int NOT NULL DEFAULT 0 COMMENT 'Number of unique sessions that viewed this page';
ALTER TABLE `lupo_analytics_visits_daily` ADD COLUMN IF NOT EXISTS `unique_actors` int NOT NULL DEFAULT 0 COMMENT 'Number of unique logged-in actors that viewed this page';
ALTER TABLE `lupo_analytics_visits_daily` ADD COLUMN IF NOT EXISTS `direct_visits` int NOT NULL DEFAULT 0 COMMENT 'Visits with no referer (entry points)';
ALTER TABLE `lupo_analytics_visits_daily` ADD COLUMN IF NOT EXISTS `internal_visits` int NOT NULL DEFAULT 0 COMMENT 'Visits from internal referers';
ALTER TABLE `lupo_analytics_visits_daily` ADD COLUMN IF NOT EXISTS `entry_count` int NOT NULL DEFAULT 0 COMMENT 'Number of times this page was the first page in a session';
ALTER TABLE `lupo_analytics_visits_daily` ADD COLUMN IF NOT EXISTS `exit_count` int NOT NULL DEFAULT 0 COMMENT 'Number of times this page was the last page in a session';
ALTER TABLE `lupo_analytics_visits_daily` ADD COLUMN IF NOT EXISTS `total_seconds` int NOT NULL DEFAULT 0 COMMENT 'Total time spent on this page across all sessions';
ALTER TABLE `lupo_analytics_visits_daily` ADD COLUMN IF NOT EXISTS `avg_seconds` int NOT NULL DEFAULT 0 COMMENT 'Average time spent on this page';
ALTER TABLE `lupo_analytics_visits_daily` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_analytics_visits_daily` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_analytics_visits_daily` MODIFY COLUMN `analytics_visits_daily_id` bigint NOT NULL auto_increment COMMENT 'Primary key for daily page visit statistics';
ALTER TABLE `lupo_analytics_visits_daily` MODIFY COLUMN `content_id` bigint NOT NULL DEFAULT 0 COMMENT 'Content ID of the visited page (0 = non-content page)';
ALTER TABLE `lupo_analytics_visits_daily` MODIFY COLUMN `url_path` varchar(500) NOT NULL DEFAULT '' COMMENT 'Normalized URL path of the visited page';
ALTER TABLE `lupo_analytics_visits_daily` MODIFY COLUMN `group_id` bigint NOT NULL DEFAULT 0 COMMENT 'Group associated with this content';
ALTER TABLE `lupo_analytics_visits_daily` MODIFY COLUMN `date_ymd` bigint NOT NULL COMMENT 'UTC YYYYMMDD representing the daily bucket';
ALTER TABLE `lupo_analytics_visits_daily` MODIFY COLUMN `visits` int NOT NULL DEFAULT 0 COMMENT 'Total number of visits to this page on this day';
ALTER TABLE `lupo_analytics_visits_daily` MODIFY COLUMN `unique_sessions` int NOT NULL DEFAULT 0 COMMENT 'Number of unique sessions that viewed this page';
ALTER TABLE `lupo_analytics_visits_daily` MODIFY COLUMN `unique_actors` int NOT NULL DEFAULT 0 COMMENT 'Number of unique logged-in actors that viewed this page';
ALTER TABLE `lupo_analytics_visits_daily` MODIFY COLUMN `direct_visits` int NOT NULL DEFAULT 0 COMMENT 'Visits with no referer (entry points)';
ALTER TABLE `lupo_analytics_visits_daily` MODIFY COLUMN `internal_visits` int NOT NULL DEFAULT 0 COMMENT 'Visits from internal referers';
ALTER TABLE `lupo_analytics_visits_daily` MODIFY COLUMN `entry_count` int NOT NULL DEFAULT 0 COMMENT 'Number of times this page was the first page in a session';
ALTER TABLE `lupo_analytics_visits_daily` MODIFY COLUMN `exit_count` int NOT NULL DEFAULT 0 COMMENT 'Number of times this page was the last page in a session';
ALTER TABLE `lupo_analytics_visits_daily` MODIFY COLUMN `total_seconds` int NOT NULL DEFAULT 0 COMMENT 'Total time spent on this page across all sessions';
ALTER TABLE `lupo_analytics_visits_daily` MODIFY COLUMN `avg_seconds` int NOT NULL DEFAULT 0 COMMENT 'Average time spent on this page';
ALTER TABLE `lupo_analytics_visits_daily` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_analytics_visits_daily` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_analytics_visits_daily` ADD PRIMARY KEY (`analytics_visits_daily_id`);
ALTER TABLE `lupo_analytics_visits_daily` ADD INDEX IF NOT EXISTS `idx_content` (`content_id`, `date_ymd`);
ALTER TABLE `lupo_analytics_visits_daily` ADD INDEX IF NOT EXISTS `idx_created` (`created_ymdhis`);
ALTER TABLE `lupo_analytics_visits_daily` ADD INDEX IF NOT EXISTS `idx_date_ymd` (`date_ymd`);
ALTER TABLE `lupo_analytics_visits_daily` ADD INDEX IF NOT EXISTS `idx_group` (`group_id`, `date_ymd`);
ALTER TABLE `lupo_analytics_visits_daily` ADD INDEX IF NOT EXISTS `idx_updated` (`updated_ymdhis`);
ALTER TABLE `lupo_analytics_visits_daily` ADD UNIQUE INDEX IF NOT EXISTS `uq_visits_daily` (`content_id`, `date_ymd`);

CREATE TABLE IF NOT EXISTS `lupo_analytics_visits_monthly` (
  `analytics_visits_monthly_id` bigint NOT NULL auto_increment COMMENT 'Primary key for monthly page visit statistics',
  `content_id` bigint NOT NULL DEFAULT 0 COMMENT 'Content ID of the visited page (0 = non-content page)',
  `url_path` varchar(500) NOT NULL DEFAULT '' COMMENT 'Normalized URL path of the visited page',
  `group_id` bigint NOT NULL DEFAULT 0 COMMENT 'Group associated with this content',
  `date_ym` bigint NOT NULL COMMENT 'UTC YYYYMM representing the monthly bucket',
  `visits` int NOT NULL DEFAULT 0,
  `unique_sessions` int NOT NULL DEFAULT 0,
  `unique_actors` int NOT NULL DEFAULT 0,
  `direct_visits` int NOT NULL DEFAULT 0,
  `internal_visits` int NOT NULL DEFAULT 0,
  `entry_count` int NOT NULL DEFAULT 0,
  `exit_count` int NOT NULL DEFAULT 0,
  `total_seconds` int NOT NULL DEFAULT 0,
  `avg_seconds` int NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  PRIMARY KEY (`analytics_visits_monthly_id`),
  KEY `idx_content` (`content_id`, `date_ym`),
  KEY `idx_created` (`created_ymdhis`),
  KEY `idx_group` (`group_id`, `date_ym`),
  KEY `idx_updated` (`updated_ymdhis`),
  UNIQUE KEY `uq_visits_monthly` (`content_id`, `date_ym`)
);

ALTER TABLE `lupo_analytics_visits_monthly` ADD COLUMN IF NOT EXISTS `analytics_visits_monthly_id` bigint NOT NULL auto_increment COMMENT 'Primary key for monthly page visit statistics';
ALTER TABLE `lupo_analytics_visits_monthly` ADD COLUMN IF NOT EXISTS `content_id` bigint NOT NULL DEFAULT 0 COMMENT 'Content ID of the visited page (0 = non-content page)';
ALTER TABLE `lupo_analytics_visits_monthly` ADD COLUMN IF NOT EXISTS `url_path` varchar(500) NOT NULL DEFAULT '' COMMENT 'Normalized URL path of the visited page';
ALTER TABLE `lupo_analytics_visits_monthly` ADD COLUMN IF NOT EXISTS `group_id` bigint NOT NULL DEFAULT 0 COMMENT 'Group associated with this content';
ALTER TABLE `lupo_analytics_visits_monthly` ADD COLUMN IF NOT EXISTS `date_ym` bigint NOT NULL COMMENT 'UTC YYYYMM representing the monthly bucket';
ALTER TABLE `lupo_analytics_visits_monthly` ADD COLUMN IF NOT EXISTS `visits` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_analytics_visits_monthly` ADD COLUMN IF NOT EXISTS `unique_sessions` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_analytics_visits_monthly` ADD COLUMN IF NOT EXISTS `unique_actors` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_analytics_visits_monthly` ADD COLUMN IF NOT EXISTS `direct_visits` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_analytics_visits_monthly` ADD COLUMN IF NOT EXISTS `internal_visits` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_analytics_visits_monthly` ADD COLUMN IF NOT EXISTS `entry_count` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_analytics_visits_monthly` ADD COLUMN IF NOT EXISTS `exit_count` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_analytics_visits_monthly` ADD COLUMN IF NOT EXISTS `total_seconds` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_analytics_visits_monthly` ADD COLUMN IF NOT EXISTS `avg_seconds` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_analytics_visits_monthly` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_analytics_visits_monthly` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_analytics_visits_monthly` MODIFY COLUMN `analytics_visits_monthly_id` bigint NOT NULL auto_increment COMMENT 'Primary key for monthly page visit statistics';
ALTER TABLE `lupo_analytics_visits_monthly` MODIFY COLUMN `content_id` bigint NOT NULL DEFAULT 0 COMMENT 'Content ID of the visited page (0 = non-content page)';
ALTER TABLE `lupo_analytics_visits_monthly` MODIFY COLUMN `url_path` varchar(500) NOT NULL DEFAULT '' COMMENT 'Normalized URL path of the visited page';
ALTER TABLE `lupo_analytics_visits_monthly` MODIFY COLUMN `group_id` bigint NOT NULL DEFAULT 0 COMMENT 'Group associated with this content';
ALTER TABLE `lupo_analytics_visits_monthly` MODIFY COLUMN `date_ym` bigint NOT NULL COMMENT 'UTC YYYYMM representing the monthly bucket';
ALTER TABLE `lupo_analytics_visits_monthly` MODIFY COLUMN `visits` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_analytics_visits_monthly` MODIFY COLUMN `unique_sessions` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_analytics_visits_monthly` MODIFY COLUMN `unique_actors` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_analytics_visits_monthly` MODIFY COLUMN `direct_visits` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_analytics_visits_monthly` MODIFY COLUMN `internal_visits` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_analytics_visits_monthly` MODIFY COLUMN `entry_count` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_analytics_visits_monthly` MODIFY COLUMN `exit_count` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_analytics_visits_monthly` MODIFY COLUMN `total_seconds` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_analytics_visits_monthly` MODIFY COLUMN `avg_seconds` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_analytics_visits_monthly` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_analytics_visits_monthly` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_analytics_visits_monthly` ADD PRIMARY KEY (`analytics_visits_monthly_id`);
ALTER TABLE `lupo_analytics_visits_monthly` ADD INDEX IF NOT EXISTS `idx_content` (`content_id`, `date_ym`);
ALTER TABLE `lupo_analytics_visits_monthly` ADD INDEX IF NOT EXISTS `idx_created` (`created_ymdhis`);
ALTER TABLE `lupo_analytics_visits_monthly` ADD INDEX IF NOT EXISTS `idx_group` (`group_id`, `date_ym`);
ALTER TABLE `lupo_analytics_visits_monthly` ADD INDEX IF NOT EXISTS `idx_updated` (`updated_ymdhis`);
ALTER TABLE `lupo_analytics_visits_monthly` ADD UNIQUE INDEX IF NOT EXISTS `uq_visits_monthly` (`content_id`, `date_ym`);

CREATE TABLE IF NOT EXISTS `lupo_anubis_events` (
  `anubis_event_id` bigint NOT NULL auto_increment,
  `event_type` varchar(64) NOT NULL,
  `table_name` varchar(255) NOT NULL,
  `row_id` bigint NOT NULL,
  `timestamp_utc` char(14) NOT NULL,
  `agent` varchar(255) NOT NULL,
  `details_json` longtext NOT NULL,
  PRIMARY KEY (`anubis_event_id`)
);

ALTER TABLE `lupo_anubis_events` ADD COLUMN IF NOT EXISTS `anubis_event_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_anubis_events` ADD COLUMN IF NOT EXISTS `event_type` varchar(64) NOT NULL;
ALTER TABLE `lupo_anubis_events` ADD COLUMN IF NOT EXISTS `table_name` varchar(255) NOT NULL;
ALTER TABLE `lupo_anubis_events` ADD COLUMN IF NOT EXISTS `row_id` bigint NOT NULL;
ALTER TABLE `lupo_anubis_events` ADD COLUMN IF NOT EXISTS `timestamp_utc` char(14) NOT NULL;
ALTER TABLE `lupo_anubis_events` ADD COLUMN IF NOT EXISTS `agent` varchar(255) NOT NULL;
ALTER TABLE `lupo_anubis_events` ADD COLUMN IF NOT EXISTS `details_json` longtext NOT NULL;
ALTER TABLE `lupo_anubis_events` MODIFY COLUMN `anubis_event_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_anubis_events` MODIFY COLUMN `event_type` varchar(64) NOT NULL;
ALTER TABLE `lupo_anubis_events` MODIFY COLUMN `table_name` varchar(255) NOT NULL;
ALTER TABLE `lupo_anubis_events` MODIFY COLUMN `row_id` bigint NOT NULL;
ALTER TABLE `lupo_anubis_events` MODIFY COLUMN `timestamp_utc` char(14) NOT NULL;
ALTER TABLE `lupo_anubis_events` MODIFY COLUMN `agent` varchar(255) NOT NULL;
ALTER TABLE `lupo_anubis_events` MODIFY COLUMN `details_json` longtext NOT NULL;
ALTER TABLE `lupo_anubis_events` ADD PRIMARY KEY (`anubis_event_id`);

CREATE TABLE IF NOT EXISTS `lupo_anubis_mirrored` (
  `anubis_mirrored_id` bigint NOT NULL auto_increment,
  `table_name` varchar(255) NOT NULL,
  `original_id` bigint NOT NULL,
  `mirrored_json` longtext NOT NULL,
  `timestamp_utc` char(14) NOT NULL,
  `agent` varchar(255) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `lineage_chain` varchar(255),
  PRIMARY KEY (`anubis_mirrored_id`)
);

ALTER TABLE `lupo_anubis_mirrored` ADD COLUMN IF NOT EXISTS `anubis_mirrored_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_anubis_mirrored` ADD COLUMN IF NOT EXISTS `table_name` varchar(255) NOT NULL;
ALTER TABLE `lupo_anubis_mirrored` ADD COLUMN IF NOT EXISTS `original_id` bigint NOT NULL;
ALTER TABLE `lupo_anubis_mirrored` ADD COLUMN IF NOT EXISTS `mirrored_json` longtext NOT NULL;
ALTER TABLE `lupo_anubis_mirrored` ADD COLUMN IF NOT EXISTS `timestamp_utc` char(14) NOT NULL;
ALTER TABLE `lupo_anubis_mirrored` ADD COLUMN IF NOT EXISTS `agent` varchar(255) NOT NULL;
ALTER TABLE `lupo_anubis_mirrored` ADD COLUMN IF NOT EXISTS `reason` varchar(255) NOT NULL;
ALTER TABLE `lupo_anubis_mirrored` ADD COLUMN IF NOT EXISTS `lineage_chain` varchar(255);
ALTER TABLE `lupo_anubis_mirrored` MODIFY COLUMN `anubis_mirrored_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_anubis_mirrored` MODIFY COLUMN `table_name` varchar(255) NOT NULL;
ALTER TABLE `lupo_anubis_mirrored` MODIFY COLUMN `original_id` bigint NOT NULL;
ALTER TABLE `lupo_anubis_mirrored` MODIFY COLUMN `mirrored_json` longtext NOT NULL;
ALTER TABLE `lupo_anubis_mirrored` MODIFY COLUMN `timestamp_utc` char(14) NOT NULL;
ALTER TABLE `lupo_anubis_mirrored` MODIFY COLUMN `agent` varchar(255) NOT NULL;
ALTER TABLE `lupo_anubis_mirrored` MODIFY COLUMN `reason` varchar(255) NOT NULL;
ALTER TABLE `lupo_anubis_mirrored` MODIFY COLUMN `lineage_chain` varchar(255);
ALTER TABLE `lupo_anubis_mirrored` ADD PRIMARY KEY (`anubis_mirrored_id`);

CREATE TABLE IF NOT EXISTS `lupo_anubis_orphaned` (
  `anubis_orphaned_id` bigint NOT NULL auto_increment,
  `table_name` varchar(255) NOT NULL,
  `orphan_id` bigint NOT NULL,
  `timestamp_utc` char(14) NOT NULL,
  `reason` varchar(255) NOT NULL,
  PRIMARY KEY (`anubis_orphaned_id`)
);

ALTER TABLE `lupo_anubis_orphaned` ADD COLUMN IF NOT EXISTS `anubis_orphaned_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_anubis_orphaned` ADD COLUMN IF NOT EXISTS `table_name` varchar(255) NOT NULL;
ALTER TABLE `lupo_anubis_orphaned` ADD COLUMN IF NOT EXISTS `orphan_id` bigint NOT NULL;
ALTER TABLE `lupo_anubis_orphaned` ADD COLUMN IF NOT EXISTS `timestamp_utc` char(14) NOT NULL;
ALTER TABLE `lupo_anubis_orphaned` ADD COLUMN IF NOT EXISTS `reason` varchar(255) NOT NULL;
ALTER TABLE `lupo_anubis_orphaned` MODIFY COLUMN `anubis_orphaned_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_anubis_orphaned` MODIFY COLUMN `table_name` varchar(255) NOT NULL;
ALTER TABLE `lupo_anubis_orphaned` MODIFY COLUMN `orphan_id` bigint NOT NULL;
ALTER TABLE `lupo_anubis_orphaned` MODIFY COLUMN `timestamp_utc` char(14) NOT NULL;
ALTER TABLE `lupo_anubis_orphaned` MODIFY COLUMN `reason` varchar(255) NOT NULL;
ALTER TABLE `lupo_anubis_orphaned` ADD PRIMARY KEY (`anubis_orphaned_id`);

CREATE TABLE IF NOT EXISTS `lupo_anubis_redirects` (
  `anubis_redirect_id` bigint NOT NULL auto_increment,
  `table_name` varchar(255) NOT NULL,
  `old_id` bigint NOT NULL,
  `new_id` bigint NOT NULL,
  `timestamp_utc` char(14) NOT NULL,
  `agent` varchar(255) NOT NULL,
  PRIMARY KEY (`anubis_redirect_id`)
);

ALTER TABLE `lupo_anubis_redirects` ADD COLUMN IF NOT EXISTS `anubis_redirect_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_anubis_redirects` ADD COLUMN IF NOT EXISTS `table_name` varchar(255) NOT NULL;
ALTER TABLE `lupo_anubis_redirects` ADD COLUMN IF NOT EXISTS `old_id` bigint NOT NULL;
ALTER TABLE `lupo_anubis_redirects` ADD COLUMN IF NOT EXISTS `new_id` bigint NOT NULL;
ALTER TABLE `lupo_anubis_redirects` ADD COLUMN IF NOT EXISTS `timestamp_utc` char(14) NOT NULL;
ALTER TABLE `lupo_anubis_redirects` ADD COLUMN IF NOT EXISTS `agent` varchar(255) NOT NULL;
ALTER TABLE `lupo_anubis_redirects` MODIFY COLUMN `anubis_redirect_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_anubis_redirects` MODIFY COLUMN `table_name` varchar(255) NOT NULL;
ALTER TABLE `lupo_anubis_redirects` MODIFY COLUMN `old_id` bigint NOT NULL;
ALTER TABLE `lupo_anubis_redirects` MODIFY COLUMN `new_id` bigint NOT NULL;
ALTER TABLE `lupo_anubis_redirects` MODIFY COLUMN `timestamp_utc` char(14) NOT NULL;
ALTER TABLE `lupo_anubis_redirects` MODIFY COLUMN `agent` varchar(255) NOT NULL;
ALTER TABLE `lupo_anubis_redirects` ADD PRIMARY KEY (`anubis_redirect_id`);

CREATE TABLE IF NOT EXISTS `lupo_anubis_revised` (
  `anubis_revised_id` bigint NOT NULL auto_increment,
  `table_name` varchar(255) NOT NULL,
  `row_id` bigint NOT NULL,
  `timestamp_utc` char(14) NOT NULL,
  `agent` varchar(255) NOT NULL,
  `revision_json` longtext NOT NULL,
  PRIMARY KEY (`anubis_revised_id`)
);

ALTER TABLE `lupo_anubis_revised` ADD COLUMN IF NOT EXISTS `anubis_revised_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_anubis_revised` ADD COLUMN IF NOT EXISTS `table_name` varchar(255) NOT NULL;
ALTER TABLE `lupo_anubis_revised` ADD COLUMN IF NOT EXISTS `row_id` bigint NOT NULL;
ALTER TABLE `lupo_anubis_revised` ADD COLUMN IF NOT EXISTS `timestamp_utc` char(14) NOT NULL;
ALTER TABLE `lupo_anubis_revised` ADD COLUMN IF NOT EXISTS `agent` varchar(255) NOT NULL;
ALTER TABLE `lupo_anubis_revised` ADD COLUMN IF NOT EXISTS `revision_json` longtext NOT NULL;
ALTER TABLE `lupo_anubis_revised` MODIFY COLUMN `anubis_revised_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_anubis_revised` MODIFY COLUMN `table_name` varchar(255) NOT NULL;
ALTER TABLE `lupo_anubis_revised` MODIFY COLUMN `row_id` bigint NOT NULL;
ALTER TABLE `lupo_anubis_revised` MODIFY COLUMN `timestamp_utc` char(14) NOT NULL;
ALTER TABLE `lupo_anubis_revised` MODIFY COLUMN `agent` varchar(255) NOT NULL;
ALTER TABLE `lupo_anubis_revised` MODIFY COLUMN `revision_json` longtext NOT NULL;
ALTER TABLE `lupo_anubis_revised` ADD PRIMARY KEY (`anubis_revised_id`);

CREATE TABLE IF NOT EXISTS `lupo_api_clients` (
  `api_client_id` bigint NOT NULL auto_increment COMMENT 'Primary key for API client record',
  `actor_id` bigint NOT NULL DEFAULT 0 COMMENT 'Owner of this client (0 = system)',
  `client_key` varchar(255) NOT NULL COMMENT 'Public client identifier',
  `client_secret` varchar(255) NOT NULL COMMENT 'Hashed client secret (never store raw secret)',
  `client_name` varchar(150) NOT NULL COMMENT 'Human-readable name of the client',
  `client_description` text COMMENT 'Description of the integration or application',
  `scopes` text COMMENT 'Comma-separated list of allowed scopes',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = active, 0 = disabled',
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  `expires_ymdhis` bigint COMMENT 'Expiration bigint (NULL = never)',
  PRIMARY KEY (`api_client_id`),
  KEY `idx_active` (`is_active`),
  KEY `idx_actor` (`actor_id`),
  KEY `idx_expires` (`expires_ymdhis`),
  UNIQUE KEY `uq_client_key` (`client_key`)
);

ALTER TABLE `lupo_api_clients` ADD COLUMN IF NOT EXISTS `api_client_id` bigint NOT NULL auto_increment COMMENT 'Primary key for API client record';
ALTER TABLE `lupo_api_clients` ADD COLUMN IF NOT EXISTS `actor_id` bigint NOT NULL DEFAULT 0 COMMENT 'Owner of this client (0 = system)';
ALTER TABLE `lupo_api_clients` ADD COLUMN IF NOT EXISTS `client_key` varchar(255) NOT NULL COMMENT 'Public client identifier';
ALTER TABLE `lupo_api_clients` ADD COLUMN IF NOT EXISTS `client_secret` varchar(255) NOT NULL COMMENT 'Hashed client secret (never store raw secret)';
ALTER TABLE `lupo_api_clients` ADD COLUMN IF NOT EXISTS `client_name` varchar(150) NOT NULL COMMENT 'Human-readable name of the client';
ALTER TABLE `lupo_api_clients` ADD COLUMN IF NOT EXISTS `client_description` text COMMENT 'Description of the integration or application';
ALTER TABLE `lupo_api_clients` ADD COLUMN IF NOT EXISTS `scopes` text COMMENT 'Comma-separated list of allowed scopes';
ALTER TABLE `lupo_api_clients` ADD COLUMN IF NOT EXISTS `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = active, 0 = disabled';
ALTER TABLE `lupo_api_clients` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_api_clients` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_api_clients` ADD COLUMN IF NOT EXISTS `expires_ymdhis` bigint COMMENT 'Expiration bigint (NULL = never)';
ALTER TABLE `lupo_api_clients` MODIFY COLUMN `api_client_id` bigint NOT NULL auto_increment COMMENT 'Primary key for API client record';
ALTER TABLE `lupo_api_clients` MODIFY COLUMN `actor_id` bigint NOT NULL DEFAULT 0 COMMENT 'Owner of this client (0 = system)';
ALTER TABLE `lupo_api_clients` MODIFY COLUMN `client_key` varchar(255) NOT NULL COMMENT 'Public client identifier';
ALTER TABLE `lupo_api_clients` MODIFY COLUMN `client_secret` varchar(255) NOT NULL COMMENT 'Hashed client secret (never store raw secret)';
ALTER TABLE `lupo_api_clients` MODIFY COLUMN `client_name` varchar(150) NOT NULL COMMENT 'Human-readable name of the client';
ALTER TABLE `lupo_api_clients` MODIFY COLUMN `client_description` text COMMENT 'Description of the integration or application';
ALTER TABLE `lupo_api_clients` MODIFY COLUMN `scopes` text COMMENT 'Comma-separated list of allowed scopes';
ALTER TABLE `lupo_api_clients` MODIFY COLUMN `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = active, 0 = disabled';
ALTER TABLE `lupo_api_clients` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_api_clients` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_api_clients` MODIFY COLUMN `expires_ymdhis` bigint COMMENT 'Expiration bigint (NULL = never)';
ALTER TABLE `lupo_api_clients` ADD PRIMARY KEY (`api_client_id`);
ALTER TABLE `lupo_api_clients` ADD INDEX IF NOT EXISTS `idx_active` (`is_active`);
ALTER TABLE `lupo_api_clients` ADD INDEX IF NOT EXISTS `idx_actor` (`actor_id`);
ALTER TABLE `lupo_api_clients` ADD INDEX IF NOT EXISTS `idx_expires` (`expires_ymdhis`);
ALTER TABLE `lupo_api_clients` ADD UNIQUE INDEX IF NOT EXISTS `uq_client_key` (`client_key`);

CREATE TABLE IF NOT EXISTS `lupo_api_rate_limits` (
  `api_rate_limit_id` bigint NOT NULL auto_increment COMMENT 'Primary key for API rate limit record',
  `domain_id` bigint NOT NULL DEFAULT 1 COMMENT 'Domain/tenant identifier',
  `api_token_id` bigint NOT NULL DEFAULT 0 COMMENT 'Token being rate-limited (0 = not token-based)',
  `actor_id` bigint NOT NULL DEFAULT 0 COMMENT 'Actor being rate-limited (0 = not actor-based)',
  `ip_address` varchar(45) COMMENT 'IP address being rate-limited',
  `endpoint` varchar(255) COMMENT 'Specific endpoint being rate-limited (NULL = global)',
  `window_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS representing the start of the rate-limit window',
  `request_count` int NOT NULL DEFAULT 0 COMMENT 'Number of requests in this window',
  `limit_value` int NOT NULL DEFAULT 0 COMMENT 'Maximum allowed requests in this window',
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  PRIMARY KEY (`api_rate_limit_id`),
  KEY `idx_actor_window` (`actor_id`, `window_ymdhis`),
  KEY `idx_domain_window` (`domain_id`, `window_ymdhis`),
  KEY `idx_endpoint` (`endpoint`),
  KEY `idx_ip_window` (`ip_address`, `window_ymdhis`),
  KEY `idx_token_window` (`api_token_id`, `window_ymdhis`)
);

ALTER TABLE `lupo_api_rate_limits` ADD COLUMN IF NOT EXISTS `api_rate_limit_id` bigint NOT NULL auto_increment COMMENT 'Primary key for API rate limit record';
ALTER TABLE `lupo_api_rate_limits` ADD COLUMN IF NOT EXISTS `domain_id` bigint NOT NULL DEFAULT 1 COMMENT 'Domain/tenant identifier';
ALTER TABLE `lupo_api_rate_limits` ADD COLUMN IF NOT EXISTS `api_token_id` bigint NOT NULL DEFAULT 0 COMMENT 'Token being rate-limited (0 = not token-based)';
ALTER TABLE `lupo_api_rate_limits` ADD COLUMN IF NOT EXISTS `actor_id` bigint NOT NULL DEFAULT 0 COMMENT 'Actor being rate-limited (0 = not actor-based)';
ALTER TABLE `lupo_api_rate_limits` ADD COLUMN IF NOT EXISTS `ip_address` varchar(45) COMMENT 'IP address being rate-limited';
ALTER TABLE `lupo_api_rate_limits` ADD COLUMN IF NOT EXISTS `endpoint` varchar(255) COMMENT 'Specific endpoint being rate-limited (NULL = global)';
ALTER TABLE `lupo_api_rate_limits` ADD COLUMN IF NOT EXISTS `window_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS representing the start of the rate-limit window';
ALTER TABLE `lupo_api_rate_limits` ADD COLUMN IF NOT EXISTS `request_count` int NOT NULL DEFAULT 0 COMMENT 'Number of requests in this window';
ALTER TABLE `lupo_api_rate_limits` ADD COLUMN IF NOT EXISTS `limit_value` int NOT NULL DEFAULT 0 COMMENT 'Maximum allowed requests in this window';
ALTER TABLE `lupo_api_rate_limits` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_api_rate_limits` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_api_rate_limits` MODIFY COLUMN `api_rate_limit_id` bigint NOT NULL auto_increment COMMENT 'Primary key for API rate limit record';
ALTER TABLE `lupo_api_rate_limits` MODIFY COLUMN `domain_id` bigint NOT NULL DEFAULT 1 COMMENT 'Domain/tenant identifier';
ALTER TABLE `lupo_api_rate_limits` MODIFY COLUMN `api_token_id` bigint NOT NULL DEFAULT 0 COMMENT 'Token being rate-limited (0 = not token-based)';
ALTER TABLE `lupo_api_rate_limits` MODIFY COLUMN `actor_id` bigint NOT NULL DEFAULT 0 COMMENT 'Actor being rate-limited (0 = not actor-based)';
ALTER TABLE `lupo_api_rate_limits` MODIFY COLUMN `ip_address` varchar(45) COMMENT 'IP address being rate-limited';
ALTER TABLE `lupo_api_rate_limits` MODIFY COLUMN `endpoint` varchar(255) COMMENT 'Specific endpoint being rate-limited (NULL = global)';
ALTER TABLE `lupo_api_rate_limits` MODIFY COLUMN `window_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS representing the start of the rate-limit window';
ALTER TABLE `lupo_api_rate_limits` MODIFY COLUMN `request_count` int NOT NULL DEFAULT 0 COMMENT 'Number of requests in this window';
ALTER TABLE `lupo_api_rate_limits` MODIFY COLUMN `limit_value` int NOT NULL DEFAULT 0 COMMENT 'Maximum allowed requests in this window';
ALTER TABLE `lupo_api_rate_limits` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_api_rate_limits` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_api_rate_limits` ADD PRIMARY KEY (`api_rate_limit_id`);
ALTER TABLE `lupo_api_rate_limits` ADD INDEX IF NOT EXISTS `idx_actor_window` (`actor_id`, `window_ymdhis`);
ALTER TABLE `lupo_api_rate_limits` ADD INDEX IF NOT EXISTS `idx_domain_window` (`domain_id`, `window_ymdhis`);
ALTER TABLE `lupo_api_rate_limits` ADD INDEX IF NOT EXISTS `idx_endpoint` (`endpoint`);
ALTER TABLE `lupo_api_rate_limits` ADD INDEX IF NOT EXISTS `idx_ip_window` (`ip_address`, `window_ymdhis`);
ALTER TABLE `lupo_api_rate_limits` ADD INDEX IF NOT EXISTS `idx_token_window` (`api_token_id`, `window_ymdhis`);

CREATE TABLE IF NOT EXISTS `lupo_api_token_logs` (
  `api_token_log_id` bigint NOT NULL auto_increment COMMENT 'Primary key for API token usage log entry',
  `domain_id` bigint NOT NULL DEFAULT 1 COMMENT 'Domain/tenant identifier',
  `api_token_id` bigint NOT NULL COMMENT 'ID of the token used (no FK)',
  `actor_id` bigint NOT NULL DEFAULT 0 COMMENT 'Actor associated with the token (0 = system)',
  `endpoint` varchar(255) NOT NULL COMMENT 'API endpoint accessed',
  `http_method` varchar(10) NOT NULL COMMENT 'HTTP method used (GET, POST, etc.)',
  `ip_address` varchar(45) COMMENT 'IP address of the requester',
  `user_agent` varchar(255) COMMENT 'User agent of the requester',
  `status_code` int NOT NULL COMMENT 'HTTP response status code',
  `request_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when the request occurred',
  `duration_ms` int COMMENT 'Execution time in milliseconds',
  PRIMARY KEY (`api_token_log_id`),
  KEY `idx_actor` (`actor_id`),
  KEY `idx_domain_time` (`domain_id`, `request_ymdhis`),
  KEY `idx_endpoint` (`endpoint`),
  KEY `idx_status` (`status_code`),
  KEY `idx_token` (`api_token_id`)
);

ALTER TABLE `lupo_api_token_logs` ADD COLUMN IF NOT EXISTS `api_token_log_id` bigint NOT NULL auto_increment COMMENT 'Primary key for API token usage log entry';
ALTER TABLE `lupo_api_token_logs` ADD COLUMN IF NOT EXISTS `domain_id` bigint NOT NULL DEFAULT 1 COMMENT 'Domain/tenant identifier';
ALTER TABLE `lupo_api_token_logs` ADD COLUMN IF NOT EXISTS `api_token_id` bigint NOT NULL COMMENT 'ID of the token used (no FK)';
ALTER TABLE `lupo_api_token_logs` ADD COLUMN IF NOT EXISTS `actor_id` bigint NOT NULL DEFAULT 0 COMMENT 'Actor associated with the token (0 = system)';
ALTER TABLE `lupo_api_token_logs` ADD COLUMN IF NOT EXISTS `endpoint` varchar(255) NOT NULL COMMENT 'API endpoint accessed';
ALTER TABLE `lupo_api_token_logs` ADD COLUMN IF NOT EXISTS `http_method` varchar(10) NOT NULL COMMENT 'HTTP method used (GET, POST, etc.)';
ALTER TABLE `lupo_api_token_logs` ADD COLUMN IF NOT EXISTS `ip_address` varchar(45) COMMENT 'IP address of the requester';
ALTER TABLE `lupo_api_token_logs` ADD COLUMN IF NOT EXISTS `user_agent` varchar(255) COMMENT 'User agent of the requester';
ALTER TABLE `lupo_api_token_logs` ADD COLUMN IF NOT EXISTS `status_code` int NOT NULL COMMENT 'HTTP response status code';
ALTER TABLE `lupo_api_token_logs` ADD COLUMN IF NOT EXISTS `request_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when the request occurred';
ALTER TABLE `lupo_api_token_logs` ADD COLUMN IF NOT EXISTS `duration_ms` int COMMENT 'Execution time in milliseconds';
ALTER TABLE `lupo_api_token_logs` MODIFY COLUMN `api_token_log_id` bigint NOT NULL auto_increment COMMENT 'Primary key for API token usage log entry';
ALTER TABLE `lupo_api_token_logs` MODIFY COLUMN `domain_id` bigint NOT NULL DEFAULT 1 COMMENT 'Domain/tenant identifier';
ALTER TABLE `lupo_api_token_logs` MODIFY COLUMN `api_token_id` bigint NOT NULL COMMENT 'ID of the token used (no FK)';
ALTER TABLE `lupo_api_token_logs` MODIFY COLUMN `actor_id` bigint NOT NULL DEFAULT 0 COMMENT 'Actor associated with the token (0 = system)';
ALTER TABLE `lupo_api_token_logs` MODIFY COLUMN `endpoint` varchar(255) NOT NULL COMMENT 'API endpoint accessed';
ALTER TABLE `lupo_api_token_logs` MODIFY COLUMN `http_method` varchar(10) NOT NULL COMMENT 'HTTP method used (GET, POST, etc.)';
ALTER TABLE `lupo_api_token_logs` MODIFY COLUMN `ip_address` varchar(45) COMMENT 'IP address of the requester';
ALTER TABLE `lupo_api_token_logs` MODIFY COLUMN `user_agent` varchar(255) COMMENT 'User agent of the requester';
ALTER TABLE `lupo_api_token_logs` MODIFY COLUMN `status_code` int NOT NULL COMMENT 'HTTP response status code';
ALTER TABLE `lupo_api_token_logs` MODIFY COLUMN `request_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when the request occurred';
ALTER TABLE `lupo_api_token_logs` MODIFY COLUMN `duration_ms` int COMMENT 'Execution time in milliseconds';
ALTER TABLE `lupo_api_token_logs` ADD PRIMARY KEY (`api_token_log_id`);
ALTER TABLE `lupo_api_token_logs` ADD INDEX IF NOT EXISTS `idx_actor` (`actor_id`);
ALTER TABLE `lupo_api_token_logs` ADD INDEX IF NOT EXISTS `idx_domain_time` (`domain_id`, `request_ymdhis`);
ALTER TABLE `lupo_api_token_logs` ADD INDEX IF NOT EXISTS `idx_endpoint` (`endpoint`);
ALTER TABLE `lupo_api_token_logs` ADD INDEX IF NOT EXISTS `idx_status` (`status_code`);
ALTER TABLE `lupo_api_token_logs` ADD INDEX IF NOT EXISTS `idx_token` (`api_token_id`);

CREATE TABLE IF NOT EXISTS `lupo_api_tokens` (
  `api_token_id` bigint NOT NULL auto_increment COMMENT 'Primary key for API token record',
  `domain_id` bigint NOT NULL DEFAULT 1 COMMENT 'Domain/tenant identifier',
  `actor_id` bigint NOT NULL DEFAULT 0 COMMENT 'Actor who owns this token (0 = system token)',
  `token_key` varchar(255) NOT NULL COMMENT 'Hashed token value (never store raw token)',
  `token_label` varchar(150) COMMENT 'Human-readable label for this token',
  `scopes` text COMMENT 'Comma-separated list of scopes/permissions for this token',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = active, 0 = revoked',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when token was created',
  `expires_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when token expires (NULL = never)',
  `last_used_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when token was last used',
  `created_ip` varchar(45) COMMENT 'IP address where token was created',
  `last_used_ip` varchar(45) COMMENT 'IP address where token was last used',
  `notes` text COMMENT 'Optional notes or metadata about this token',
  PRIMARY KEY (`api_token_id`),
  KEY `idx_active` (`is_active`),
  KEY `idx_actor` (`actor_id`),
  KEY `idx_domain` (`domain_id`),
  KEY `idx_expires` (`expires_ymdhis`),
  KEY `idx_last_used` (`last_used_ymdhis`),
  UNIQUE KEY `uq_token_key` (`token_key`)
);

ALTER TABLE `lupo_api_tokens` ADD COLUMN IF NOT EXISTS `api_token_id` bigint NOT NULL auto_increment COMMENT 'Primary key for API token record';
ALTER TABLE `lupo_api_tokens` ADD COLUMN IF NOT EXISTS `domain_id` bigint NOT NULL DEFAULT 1 COMMENT 'Domain/tenant identifier';
ALTER TABLE `lupo_api_tokens` ADD COLUMN IF NOT EXISTS `actor_id` bigint NOT NULL DEFAULT 0 COMMENT 'Actor who owns this token (0 = system token)';
ALTER TABLE `lupo_api_tokens` ADD COLUMN IF NOT EXISTS `token_key` varchar(255) NOT NULL COMMENT 'Hashed token value (never store raw token)';
ALTER TABLE `lupo_api_tokens` ADD COLUMN IF NOT EXISTS `token_label` varchar(150) COMMENT 'Human-readable label for this token';
ALTER TABLE `lupo_api_tokens` ADD COLUMN IF NOT EXISTS `scopes` text COMMENT 'Comma-separated list of scopes/permissions for this token';
ALTER TABLE `lupo_api_tokens` ADD COLUMN IF NOT EXISTS `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = active, 0 = revoked';
ALTER TABLE `lupo_api_tokens` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when token was created';
ALTER TABLE `lupo_api_tokens` ADD COLUMN IF NOT EXISTS `expires_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when token expires (NULL = never)';
ALTER TABLE `lupo_api_tokens` ADD COLUMN IF NOT EXISTS `last_used_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when token was last used';
ALTER TABLE `lupo_api_tokens` ADD COLUMN IF NOT EXISTS `created_ip` varchar(45) COMMENT 'IP address where token was created';
ALTER TABLE `lupo_api_tokens` ADD COLUMN IF NOT EXISTS `last_used_ip` varchar(45) COMMENT 'IP address where token was last used';
ALTER TABLE `lupo_api_tokens` ADD COLUMN IF NOT EXISTS `notes` text COMMENT 'Optional notes or metadata about this token';
ALTER TABLE `lupo_api_tokens` MODIFY COLUMN `api_token_id` bigint NOT NULL auto_increment COMMENT 'Primary key for API token record';
ALTER TABLE `lupo_api_tokens` MODIFY COLUMN `domain_id` bigint NOT NULL DEFAULT 1 COMMENT 'Domain/tenant identifier';
ALTER TABLE `lupo_api_tokens` MODIFY COLUMN `actor_id` bigint NOT NULL DEFAULT 0 COMMENT 'Actor who owns this token (0 = system token)';
ALTER TABLE `lupo_api_tokens` MODIFY COLUMN `token_key` varchar(255) NOT NULL COMMENT 'Hashed token value (never store raw token)';
ALTER TABLE `lupo_api_tokens` MODIFY COLUMN `token_label` varchar(150) COMMENT 'Human-readable label for this token';
ALTER TABLE `lupo_api_tokens` MODIFY COLUMN `scopes` text COMMENT 'Comma-separated list of scopes/permissions for this token';
ALTER TABLE `lupo_api_tokens` MODIFY COLUMN `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = active, 0 = revoked';
ALTER TABLE `lupo_api_tokens` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when token was created';
ALTER TABLE `lupo_api_tokens` MODIFY COLUMN `expires_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when token expires (NULL = never)';
ALTER TABLE `lupo_api_tokens` MODIFY COLUMN `last_used_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when token was last used';
ALTER TABLE `lupo_api_tokens` MODIFY COLUMN `created_ip` varchar(45) COMMENT 'IP address where token was created';
ALTER TABLE `lupo_api_tokens` MODIFY COLUMN `last_used_ip` varchar(45) COMMENT 'IP address where token was last used';
ALTER TABLE `lupo_api_tokens` MODIFY COLUMN `notes` text COMMENT 'Optional notes or metadata about this token';
ALTER TABLE `lupo_api_tokens` ADD PRIMARY KEY (`api_token_id`);
ALTER TABLE `lupo_api_tokens` ADD INDEX IF NOT EXISTS `idx_active` (`is_active`);
ALTER TABLE `lupo_api_tokens` ADD INDEX IF NOT EXISTS `idx_actor` (`actor_id`);
ALTER TABLE `lupo_api_tokens` ADD INDEX IF NOT EXISTS `idx_domain` (`domain_id`);
ALTER TABLE `lupo_api_tokens` ADD INDEX IF NOT EXISTS `idx_expires` (`expires_ymdhis`);
ALTER TABLE `lupo_api_tokens` ADD INDEX IF NOT EXISTS `idx_last_used` (`last_used_ymdhis`);
ALTER TABLE `lupo_api_tokens` ADD UNIQUE INDEX IF NOT EXISTS `uq_token_key` (`token_key`);

CREATE TABLE IF NOT EXISTS `lupo_api_webhooks` (
  `api_webhook_id` bigint NOT NULL auto_increment COMMENT 'Primary key for webhook registration',
  `domain_id` bigint NOT NULL DEFAULT 1 COMMENT 'Domain/tenant identifier',
  `actor_id` bigint NOT NULL DEFAULT 0 COMMENT 'Actor who created this webhook (0 = system)',
  `module_id` bigint NOT NULL DEFAULT 0 COMMENT 'Module associated with this webhook (0 = global)',
  `endpoint_url` varchar(500) NOT NULL COMMENT 'Target URL to receive webhook POST requests',
  `secret_key` varchar(255) NOT NULL COMMENT 'Hashed secret used to sign webhook payloads',
  `event_types` text NOT NULL COMMENT 'Comma-separated list of event types this webhook listens to',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = active, 0 = disabled',
  `max_retries` int NOT NULL DEFAULT 5 COMMENT 'Maximum number of retry attempts for failed deliveries',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when webhook was created',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when webhook was last updated',
  `expires_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when webhook expires (NULL = never)',
  `notes` text COMMENT 'Optional notes or metadata about this webhook',
  PRIMARY KEY (`api_webhook_id`),
  KEY `idx_active` (`is_active`),
  KEY `idx_actor` (`actor_id`),
  KEY `idx_domain` (`domain_id`),
  KEY `idx_expires` (`expires_ymdhis`),
  KEY `idx_module` (`module_id`)
);

ALTER TABLE `lupo_api_webhooks` ADD COLUMN IF NOT EXISTS `api_webhook_id` bigint NOT NULL auto_increment COMMENT 'Primary key for webhook registration';
ALTER TABLE `lupo_api_webhooks` ADD COLUMN IF NOT EXISTS `domain_id` bigint NOT NULL DEFAULT 1 COMMENT 'Domain/tenant identifier';
ALTER TABLE `lupo_api_webhooks` ADD COLUMN IF NOT EXISTS `actor_id` bigint NOT NULL DEFAULT 0 COMMENT 'Actor who created this webhook (0 = system)';
ALTER TABLE `lupo_api_webhooks` ADD COLUMN IF NOT EXISTS `module_id` bigint NOT NULL DEFAULT 0 COMMENT 'Module associated with this webhook (0 = global)';
ALTER TABLE `lupo_api_webhooks` ADD COLUMN IF NOT EXISTS `endpoint_url` varchar(500) NOT NULL COMMENT 'Target URL to receive webhook POST requests';
ALTER TABLE `lupo_api_webhooks` ADD COLUMN IF NOT EXISTS `secret_key` varchar(255) NOT NULL COMMENT 'Hashed secret used to sign webhook payloads';
ALTER TABLE `lupo_api_webhooks` ADD COLUMN IF NOT EXISTS `event_types` text NOT NULL COMMENT 'Comma-separated list of event types this webhook listens to';
ALTER TABLE `lupo_api_webhooks` ADD COLUMN IF NOT EXISTS `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = active, 0 = disabled';
ALTER TABLE `lupo_api_webhooks` ADD COLUMN IF NOT EXISTS `max_retries` int NOT NULL DEFAULT 5 COMMENT 'Maximum number of retry attempts for failed deliveries';
ALTER TABLE `lupo_api_webhooks` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when webhook was created';
ALTER TABLE `lupo_api_webhooks` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when webhook was last updated';
ALTER TABLE `lupo_api_webhooks` ADD COLUMN IF NOT EXISTS `expires_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when webhook expires (NULL = never)';
ALTER TABLE `lupo_api_webhooks` ADD COLUMN IF NOT EXISTS `notes` text COMMENT 'Optional notes or metadata about this webhook';
ALTER TABLE `lupo_api_webhooks` MODIFY COLUMN `api_webhook_id` bigint NOT NULL auto_increment COMMENT 'Primary key for webhook registration';
ALTER TABLE `lupo_api_webhooks` MODIFY COLUMN `domain_id` bigint NOT NULL DEFAULT 1 COMMENT 'Domain/tenant identifier';
ALTER TABLE `lupo_api_webhooks` MODIFY COLUMN `actor_id` bigint NOT NULL DEFAULT 0 COMMENT 'Actor who created this webhook (0 = system)';
ALTER TABLE `lupo_api_webhooks` MODIFY COLUMN `module_id` bigint NOT NULL DEFAULT 0 COMMENT 'Module associated with this webhook (0 = global)';
ALTER TABLE `lupo_api_webhooks` MODIFY COLUMN `endpoint_url` varchar(500) NOT NULL COMMENT 'Target URL to receive webhook POST requests';
ALTER TABLE `lupo_api_webhooks` MODIFY COLUMN `secret_key` varchar(255) NOT NULL COMMENT 'Hashed secret used to sign webhook payloads';
ALTER TABLE `lupo_api_webhooks` MODIFY COLUMN `event_types` text NOT NULL COMMENT 'Comma-separated list of event types this webhook listens to';
ALTER TABLE `lupo_api_webhooks` MODIFY COLUMN `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = active, 0 = disabled';
ALTER TABLE `lupo_api_webhooks` MODIFY COLUMN `max_retries` int NOT NULL DEFAULT 5 COMMENT 'Maximum number of retry attempts for failed deliveries';
ALTER TABLE `lupo_api_webhooks` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when webhook was created';
ALTER TABLE `lupo_api_webhooks` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when webhook was last updated';
ALTER TABLE `lupo_api_webhooks` MODIFY COLUMN `expires_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when webhook expires (NULL = never)';
ALTER TABLE `lupo_api_webhooks` MODIFY COLUMN `notes` text COMMENT 'Optional notes or metadata about this webhook';
ALTER TABLE `lupo_api_webhooks` ADD PRIMARY KEY (`api_webhook_id`);
ALTER TABLE `lupo_api_webhooks` ADD INDEX IF NOT EXISTS `idx_active` (`is_active`);
ALTER TABLE `lupo_api_webhooks` ADD INDEX IF NOT EXISTS `idx_actor` (`actor_id`);
ALTER TABLE `lupo_api_webhooks` ADD INDEX IF NOT EXISTS `idx_domain` (`domain_id`);
ALTER TABLE `lupo_api_webhooks` ADD INDEX IF NOT EXISTS `idx_expires` (`expires_ymdhis`);
ALTER TABLE `lupo_api_webhooks` ADD INDEX IF NOT EXISTS `idx_module` (`module_id`);

CREATE TABLE IF NOT EXISTS `lupo_artifacts` (
  `artifact_id` bigint NOT NULL auto_increment,
  `actor_id` bigint NOT NULL,
  `utc_timestamp` bigint NOT NULL COMMENT 'YYYYMMDDHHMMSS',
  `type` varchar(64) NOT NULL COMMENT 'dialog|changelog|schema|lore|humor|protocol|fork_justification',
  `content` longtext NOT NULL,
  `created_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (`artifact_id`),
  KEY `idx_actor_id` (`actor_id`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_type` (`type`),
  KEY `idx_utc_timestamp` (`utc_timestamp`)
);

ALTER TABLE `lupo_artifacts` ADD COLUMN IF NOT EXISTS `artifact_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_artifacts` ADD COLUMN IF NOT EXISTS `actor_id` bigint NOT NULL;
ALTER TABLE `lupo_artifacts` ADD COLUMN IF NOT EXISTS `utc_timestamp` bigint NOT NULL COMMENT 'YYYYMMDDHHMMSS';
ALTER TABLE `lupo_artifacts` ADD COLUMN IF NOT EXISTS `type` varchar(64) NOT NULL COMMENT 'dialog|changelog|schema|lore|humor|protocol|fork_justification';
ALTER TABLE `lupo_artifacts` ADD COLUMN IF NOT EXISTS `content` longtext NOT NULL;
ALTER TABLE `lupo_artifacts` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_artifacts` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_artifacts` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint;
ALTER TABLE `lupo_artifacts` MODIFY COLUMN `artifact_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_artifacts` MODIFY COLUMN `actor_id` bigint NOT NULL;
ALTER TABLE `lupo_artifacts` MODIFY COLUMN `utc_timestamp` bigint NOT NULL COMMENT 'YYYYMMDDHHMMSS';
ALTER TABLE `lupo_artifacts` MODIFY COLUMN `type` varchar(64) NOT NULL COMMENT 'dialog|changelog|schema|lore|humor|protocol|fork_justification';
ALTER TABLE `lupo_artifacts` MODIFY COLUMN `content` longtext NOT NULL;
ALTER TABLE `lupo_artifacts` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_artifacts` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_artifacts` MODIFY COLUMN `deleted_ymdhis` bigint;
ALTER TABLE `lupo_artifacts` ADD PRIMARY KEY (`artifact_id`);
ALTER TABLE `lupo_artifacts` ADD INDEX IF NOT EXISTS `idx_actor_id` (`actor_id`);
ALTER TABLE `lupo_artifacts` ADD INDEX IF NOT EXISTS `idx_is_deleted` (`is_deleted`);
ALTER TABLE `lupo_artifacts` ADD INDEX IF NOT EXISTS `idx_type` (`type`);
ALTER TABLE `lupo_artifacts` ADD INDEX IF NOT EXISTS `idx_utc_timestamp` (`utc_timestamp`);

CREATE TABLE IF NOT EXISTS `lupo_atoms` (
  `atom_id` bigint NOT NULL auto_increment,
  `atom_name` varchar(255) NOT NULL,
  `context_id` bigint NOT NULL,
  `is_authoritative` tinyint NOT NULL DEFAULT 0,
  `value_json` json,
  `summary` text,
  `tags` varchar(255),
  `created_ymd` bigint NOT NULL DEFAULT 0,
  `updated_ymd` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (`atom_id`),
  KEY `idx_atom_context` (`atom_name`, `context_id`),
  KEY `idx_atom_name` (`atom_name`),
  KEY `idx_authoritative` (`is_authoritative`),
  KEY `idx_context_id` (`context_id`)
);

ALTER TABLE `lupo_atoms` ADD COLUMN IF NOT EXISTS `atom_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_atoms` ADD COLUMN IF NOT EXISTS `atom_name` varchar(255) NOT NULL;
ALTER TABLE `lupo_atoms` ADD COLUMN IF NOT EXISTS `context_id` bigint NOT NULL;
ALTER TABLE `lupo_atoms` ADD COLUMN IF NOT EXISTS `is_authoritative` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_atoms` ADD COLUMN IF NOT EXISTS `value_json` json;
ALTER TABLE `lupo_atoms` ADD COLUMN IF NOT EXISTS `summary` text;
ALTER TABLE `lupo_atoms` ADD COLUMN IF NOT EXISTS `tags` varchar(255);
ALTER TABLE `lupo_atoms` ADD COLUMN IF NOT EXISTS `created_ymd` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_atoms` ADD COLUMN IF NOT EXISTS `updated_ymd` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_atoms` MODIFY COLUMN `atom_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_atoms` MODIFY COLUMN `atom_name` varchar(255) NOT NULL;
ALTER TABLE `lupo_atoms` MODIFY COLUMN `context_id` bigint NOT NULL;
ALTER TABLE `lupo_atoms` MODIFY COLUMN `is_authoritative` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_atoms` MODIFY COLUMN `value_json` json;
ALTER TABLE `lupo_atoms` MODIFY COLUMN `summary` text;
ALTER TABLE `lupo_atoms` MODIFY COLUMN `tags` varchar(255);
ALTER TABLE `lupo_atoms` MODIFY COLUMN `created_ymd` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_atoms` MODIFY COLUMN `updated_ymd` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_atoms` ADD PRIMARY KEY (`atom_id`);
ALTER TABLE `lupo_atoms` ADD INDEX IF NOT EXISTS `idx_atom_context` (`atom_name`, `context_id`);
ALTER TABLE `lupo_atoms` ADD INDEX IF NOT EXISTS `idx_atom_name` (`atom_name`);
ALTER TABLE `lupo_atoms` ADD INDEX IF NOT EXISTS `idx_authoritative` (`is_authoritative`);
ALTER TABLE `lupo_atoms` ADD INDEX IF NOT EXISTS `idx_context_id` (`context_id`);

CREATE TABLE IF NOT EXISTS `lupo_audit_log` (
  `audit_log_id` bigint NOT NULL auto_increment,
  `channel_id` bigint NOT NULL,
  `entity_type` varchar(32) NOT NULL COMMENT 'Type of entity (actor, agent, content, etc.)',
  `entity_id` bigint NOT NULL COMMENT 'ID of the entity',
  `event_type` varchar(100) NOT NULL COMMENT 'Type of event that occurred',
  `table_name` varchar(100) COMMENT 'Name of the related database table',
  `table_id` bigint COMMENT 'ID of the related record in the table',
  `payload_json` longtext COMMENT 'Additional event data in JSON format',
  `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint (YYYYMMDDHHMMSS)',
  `updated_ymdhis` bigint NOT NULL COMMENT 'Last update bigint (YYYYMMDDHHMMSS)',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted, 0=active)',
  `deleted_ymdhis` bigint COMMENT 'Deletion bigint (YYYYMMDDHHMMSS)',
  PRIMARY KEY (`audit_log_id`),
  KEY `idx_entity` (`entity_type`, `entity_id`),
  KEY `idx_event` (`event_type`),
  KEY `idx_table` (`table_name`, `table_id`)
);

ALTER TABLE `lupo_audit_log` ADD COLUMN IF NOT EXISTS `audit_log_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_audit_log` ADD COLUMN IF NOT EXISTS `channel_id` bigint NOT NULL;
ALTER TABLE `lupo_audit_log` ADD COLUMN IF NOT EXISTS `entity_type` varchar(32) NOT NULL COMMENT 'Type of entity (actor, agent, content, etc.)';
ALTER TABLE `lupo_audit_log` ADD COLUMN IF NOT EXISTS `entity_id` bigint NOT NULL COMMENT 'ID of the entity';
ALTER TABLE `lupo_audit_log` ADD COLUMN IF NOT EXISTS `event_type` varchar(100) NOT NULL COMMENT 'Type of event that occurred';
ALTER TABLE `lupo_audit_log` ADD COLUMN IF NOT EXISTS `table_name` varchar(100) COMMENT 'Name of the related database table';
ALTER TABLE `lupo_audit_log` ADD COLUMN IF NOT EXISTS `table_id` bigint COMMENT 'ID of the related record in the table';
ALTER TABLE `lupo_audit_log` ADD COLUMN IF NOT EXISTS `payload_json` longtext COMMENT 'Additional event data in JSON format';
ALTER TABLE `lupo_audit_log` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_audit_log` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL COMMENT 'Last update bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_audit_log` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted, 0=active)';
ALTER TABLE `lupo_audit_log` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint COMMENT 'Deletion bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_audit_log` MODIFY COLUMN `audit_log_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_audit_log` MODIFY COLUMN `channel_id` bigint NOT NULL;
ALTER TABLE `lupo_audit_log` MODIFY COLUMN `entity_type` varchar(32) NOT NULL COMMENT 'Type of entity (actor, agent, content, etc.)';
ALTER TABLE `lupo_audit_log` MODIFY COLUMN `entity_id` bigint NOT NULL COMMENT 'ID of the entity';
ALTER TABLE `lupo_audit_log` MODIFY COLUMN `event_type` varchar(100) NOT NULL COMMENT 'Type of event that occurred';
ALTER TABLE `lupo_audit_log` MODIFY COLUMN `table_name` varchar(100) COMMENT 'Name of the related database table';
ALTER TABLE `lupo_audit_log` MODIFY COLUMN `table_id` bigint COMMENT 'ID of the related record in the table';
ALTER TABLE `lupo_audit_log` MODIFY COLUMN `payload_json` longtext COMMENT 'Additional event data in JSON format';
ALTER TABLE `lupo_audit_log` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_audit_log` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL COMMENT 'Last update bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_audit_log` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted, 0=active)';
ALTER TABLE `lupo_audit_log` MODIFY COLUMN `deleted_ymdhis` bigint COMMENT 'Deletion bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_audit_log` ADD PRIMARY KEY (`audit_log_id`);
ALTER TABLE `lupo_audit_log` ADD INDEX IF NOT EXISTS `idx_entity` (`entity_type`, `entity_id`);
ALTER TABLE `lupo_audit_log` ADD INDEX IF NOT EXISTS `idx_event` (`event_type`);
ALTER TABLE `lupo_audit_log` ADD INDEX IF NOT EXISTS `idx_table` (`table_name`, `table_id`);

CREATE TABLE IF NOT EXISTS `lupo_auth_providers` (
  `auth_provider_id` bigint NOT NULL auto_increment,
  `provider_name` varchar(50) NOT NULL COMMENT 'Unique provider name (e.g., google, github)',
  `client_id` varchar(255) NOT NULL COMMENT 'OAuth client ID',
  `client_secret` text NOT NULL COMMENT 'Encrypted at rest in lupopedia-config.php',
  `scopes` text COMMENT 'Space-separated list of OAuth scopes',
  `authorization_endpoint` varchar(2000) NOT NULL COMMENT 'OAuth authorization URL',
  `token_endpoint` varchar(2000) NOT NULL COMMENT 'OAuth token URL',
  `userinfo_endpoint` varchar(2000) COMMENT 'Optional userinfo endpoint',
  `jwks_uri` varchar(2000) COMMENT 'Optional JWKS URI for key rotation',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when provider was created',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when provider was last updated',
  `is_active` tinyint NOT NULL DEFAULT 1 COMMENT '1 = active, 0 = inactive',
  PRIMARY KEY (`auth_provider_id`),
  UNIQUE KEY `unique_provider_name` (`provider_name`)
);

ALTER TABLE `lupo_auth_providers` ADD COLUMN IF NOT EXISTS `auth_provider_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_auth_providers` ADD COLUMN IF NOT EXISTS `provider_name` varchar(50) NOT NULL COMMENT 'Unique provider name (e.g., google, github)';
ALTER TABLE `lupo_auth_providers` ADD COLUMN IF NOT EXISTS `client_id` varchar(255) NOT NULL COMMENT 'OAuth client ID';
ALTER TABLE `lupo_auth_providers` ADD COLUMN IF NOT EXISTS `client_secret` text NOT NULL COMMENT 'Encrypted at rest in lupopedia-config.php';
ALTER TABLE `lupo_auth_providers` ADD COLUMN IF NOT EXISTS `scopes` text COMMENT 'Space-separated list of OAuth scopes';
ALTER TABLE `lupo_auth_providers` ADD COLUMN IF NOT EXISTS `authorization_endpoint` varchar(2000) NOT NULL COMMENT 'OAuth authorization URL';
ALTER TABLE `lupo_auth_providers` ADD COLUMN IF NOT EXISTS `token_endpoint` varchar(2000) NOT NULL COMMENT 'OAuth token URL';
ALTER TABLE `lupo_auth_providers` ADD COLUMN IF NOT EXISTS `userinfo_endpoint` varchar(2000) COMMENT 'Optional userinfo endpoint';
ALTER TABLE `lupo_auth_providers` ADD COLUMN IF NOT EXISTS `jwks_uri` varchar(2000) COMMENT 'Optional JWKS URI for key rotation';
ALTER TABLE `lupo_auth_providers` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when provider was created';
ALTER TABLE `lupo_auth_providers` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when provider was last updated';
ALTER TABLE `lupo_auth_providers` ADD COLUMN IF NOT EXISTS `is_active` tinyint NOT NULL DEFAULT 1 COMMENT '1 = active, 0 = inactive';
ALTER TABLE `lupo_auth_providers` MODIFY COLUMN `auth_provider_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_auth_providers` MODIFY COLUMN `provider_name` varchar(50) NOT NULL COMMENT 'Unique provider name (e.g., google, github)';
ALTER TABLE `lupo_auth_providers` MODIFY COLUMN `client_id` varchar(255) NOT NULL COMMENT 'OAuth client ID';
ALTER TABLE `lupo_auth_providers` MODIFY COLUMN `client_secret` text NOT NULL COMMENT 'Encrypted at rest in lupopedia-config.php';
ALTER TABLE `lupo_auth_providers` MODIFY COLUMN `scopes` text COMMENT 'Space-separated list of OAuth scopes';
ALTER TABLE `lupo_auth_providers` MODIFY COLUMN `authorization_endpoint` varchar(2000) NOT NULL COMMENT 'OAuth authorization URL';
ALTER TABLE `lupo_auth_providers` MODIFY COLUMN `token_endpoint` varchar(2000) NOT NULL COMMENT 'OAuth token URL';
ALTER TABLE `lupo_auth_providers` MODIFY COLUMN `userinfo_endpoint` varchar(2000) COMMENT 'Optional userinfo endpoint';
ALTER TABLE `lupo_auth_providers` MODIFY COLUMN `jwks_uri` varchar(2000) COMMENT 'Optional JWKS URI for key rotation';
ALTER TABLE `lupo_auth_providers` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when provider was created';
ALTER TABLE `lupo_auth_providers` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when provider was last updated';
ALTER TABLE `lupo_auth_providers` MODIFY COLUMN `is_active` tinyint NOT NULL DEFAULT 1 COMMENT '1 = active, 0 = inactive';
ALTER TABLE `lupo_auth_providers` ADD PRIMARY KEY (`auth_provider_id`);
ALTER TABLE `lupo_auth_providers` ADD UNIQUE INDEX IF NOT EXISTS `unique_provider_name` (`provider_name`);

CREATE TABLE IF NOT EXISTS `lupo_auth_users` (
  `auth_user_id` bigint NOT NULL auto_increment,
  `username` varchar(30) NOT NULL,
  `display_name` varchar(42) NOT NULL,
  `email` varchar(100),
  `password_hash` varchar(255) COMMENT 'NULL for OAuth users',
  `auth_provider` varchar(50) COMMENT 'NULL for local users',
  `provider_id` varchar(255) COMMENT 'Provider-specific user ID',
  `profile_image_url` varchar(2000),
  `last_login_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS',
  `is_active` tinyint NOT NULL DEFAULT 1 COMMENT '1 = active, 0 = inactive',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when user was deleted',
  PRIMARY KEY (`auth_user_id`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_email` (`email`),
  KEY `idx_is_active` (`is_active`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_updated_ymdhis` (`updated_ymdhis`),
  UNIQUE KEY `unique_provider_user` (`auth_provider`, `provider_id`),
  UNIQUE KEY `unique_username` (`username`)
);

ALTER TABLE `lupo_auth_users` ADD COLUMN IF NOT EXISTS `auth_user_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_auth_users` ADD COLUMN IF NOT EXISTS `username` varchar(30) NOT NULL;
ALTER TABLE `lupo_auth_users` ADD COLUMN IF NOT EXISTS `display_name` varchar(42) NOT NULL;
ALTER TABLE `lupo_auth_users` ADD COLUMN IF NOT EXISTS `email` varchar(100);
ALTER TABLE `lupo_auth_users` ADD COLUMN IF NOT EXISTS `password_hash` varchar(255) COMMENT 'NULL for OAuth users';
ALTER TABLE `lupo_auth_users` ADD COLUMN IF NOT EXISTS `auth_provider` varchar(50) COMMENT 'NULL for local users';
ALTER TABLE `lupo_auth_users` ADD COLUMN IF NOT EXISTS `provider_id` varchar(255) COMMENT 'Provider-specific user ID';
ALTER TABLE `lupo_auth_users` ADD COLUMN IF NOT EXISTS `profile_image_url` varchar(2000);
ALTER TABLE `lupo_auth_users` ADD COLUMN IF NOT EXISTS `last_login_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS';
ALTER TABLE `lupo_auth_users` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS';
ALTER TABLE `lupo_auth_users` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS';
ALTER TABLE `lupo_auth_users` ADD COLUMN IF NOT EXISTS `is_active` tinyint NOT NULL DEFAULT 1 COMMENT '1 = active, 0 = inactive';
ALTER TABLE `lupo_auth_users` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted';
ALTER TABLE `lupo_auth_users` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when user was deleted';
ALTER TABLE `lupo_auth_users` MODIFY COLUMN `auth_user_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_auth_users` MODIFY COLUMN `username` varchar(30) NOT NULL;
ALTER TABLE `lupo_auth_users` MODIFY COLUMN `display_name` varchar(42) NOT NULL;
ALTER TABLE `lupo_auth_users` MODIFY COLUMN `email` varchar(100);
ALTER TABLE `lupo_auth_users` MODIFY COLUMN `password_hash` varchar(255) COMMENT 'NULL for OAuth users';
ALTER TABLE `lupo_auth_users` MODIFY COLUMN `auth_provider` varchar(50) COMMENT 'NULL for local users';
ALTER TABLE `lupo_auth_users` MODIFY COLUMN `provider_id` varchar(255) COMMENT 'Provider-specific user ID';
ALTER TABLE `lupo_auth_users` MODIFY COLUMN `profile_image_url` varchar(2000);
ALTER TABLE `lupo_auth_users` MODIFY COLUMN `last_login_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS';
ALTER TABLE `lupo_auth_users` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS';
ALTER TABLE `lupo_auth_users` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS';
ALTER TABLE `lupo_auth_users` MODIFY COLUMN `is_active` tinyint NOT NULL DEFAULT 1 COMMENT '1 = active, 0 = inactive';
ALTER TABLE `lupo_auth_users` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted';
ALTER TABLE `lupo_auth_users` MODIFY COLUMN `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when user was deleted';
ALTER TABLE `lupo_auth_users` ADD PRIMARY KEY (`auth_user_id`);
ALTER TABLE `lupo_auth_users` ADD INDEX IF NOT EXISTS `idx_created_ymdhis` (`created_ymdhis`);
ALTER TABLE `lupo_auth_users` ADD INDEX IF NOT EXISTS `idx_email` (`email`);
ALTER TABLE `lupo_auth_users` ADD INDEX IF NOT EXISTS `idx_is_active` (`is_active`);
ALTER TABLE `lupo_auth_users` ADD INDEX IF NOT EXISTS `idx_is_deleted` (`is_deleted`);
ALTER TABLE `lupo_auth_users` ADD INDEX IF NOT EXISTS `idx_updated_ymdhis` (`updated_ymdhis`);
ALTER TABLE `lupo_auth_users` ADD UNIQUE INDEX IF NOT EXISTS `unique_provider_user` (`auth_provider`, `provider_id`);
ALTER TABLE `lupo_auth_users` ADD UNIQUE INDEX IF NOT EXISTS `unique_username` (`username`);

CREATE TABLE IF NOT EXISTS `lupo_calibration_impacts` (
  `id` bigint unsigned NOT NULL auto_increment,
  `calibration_id` bigint unsigned NOT NULL COMMENT 'Reference to lupo_emotional_geometry_calibrations.id',
  `impact_type` enum('agent_behavior','communication_tone','system_harmony','conflict_reduction') NOT NULL,
  `impact_measurement` decimal(5,4) NOT NULL COMMENT 'Quantified impact (0.0000-1.0000)',
  `measurement_method` varchar(100) NOT NULL COMMENT 'How impact was measured',
  `before_metrics_json` json COMMENT 'Metrics before calibration',
  `after_metrics_json` json COMMENT 'Metrics after calibration',
  `observation_period_hours` int unsigned DEFAULT 24 COMMENT 'Observation period length',
  `measured_ymdhis` bigint NOT NULL COMMENT 'When impact was measured',
  `impact_version` varchar(20) DEFAULT '4.0.75' COMMENT 'Impact tracking version',
  PRIMARY KEY (`id`),
  KEY `idx_calibration_impact` (`calibration_id`, `impact_type`),
  KEY `idx_impact_measurement` (`impact_measurement`),
  KEY `idx_measurement_time` (`measured_ymdhis`)
);

ALTER TABLE `lupo_calibration_impacts` ADD COLUMN IF NOT EXISTS `id` bigint unsigned NOT NULL auto_increment;
ALTER TABLE `lupo_calibration_impacts` ADD COLUMN IF NOT EXISTS `calibration_id` bigint unsigned NOT NULL COMMENT 'Reference to lupo_emotional_geometry_calibrations.id';
ALTER TABLE `lupo_calibration_impacts` ADD COLUMN IF NOT EXISTS `impact_type` enum('agent_behavior','communication_tone','system_harmony','conflict_reduction') NOT NULL;
ALTER TABLE `lupo_calibration_impacts` ADD COLUMN IF NOT EXISTS `impact_measurement` decimal(5,4) NOT NULL COMMENT 'Quantified impact (0.0000-1.0000)';
ALTER TABLE `lupo_calibration_impacts` ADD COLUMN IF NOT EXISTS `measurement_method` varchar(100) NOT NULL COMMENT 'How impact was measured';
ALTER TABLE `lupo_calibration_impacts` ADD COLUMN IF NOT EXISTS `before_metrics_json` json COMMENT 'Metrics before calibration';
ALTER TABLE `lupo_calibration_impacts` ADD COLUMN IF NOT EXISTS `after_metrics_json` json COMMENT 'Metrics after calibration';
ALTER TABLE `lupo_calibration_impacts` ADD COLUMN IF NOT EXISTS `observation_period_hours` int unsigned DEFAULT 24 COMMENT 'Observation period length';
ALTER TABLE `lupo_calibration_impacts` ADD COLUMN IF NOT EXISTS `measured_ymdhis` bigint NOT NULL COMMENT 'When impact was measured';
ALTER TABLE `lupo_calibration_impacts` ADD COLUMN IF NOT EXISTS `impact_version` varchar(20) DEFAULT '4.0.75' COMMENT 'Impact tracking version';
ALTER TABLE `lupo_calibration_impacts` MODIFY COLUMN `id` bigint unsigned NOT NULL auto_increment;
ALTER TABLE `lupo_calibration_impacts` MODIFY COLUMN `calibration_id` bigint unsigned NOT NULL COMMENT 'Reference to lupo_emotional_geometry_calibrations.id';
ALTER TABLE `lupo_calibration_impacts` MODIFY COLUMN `impact_type` enum('agent_behavior','communication_tone','system_harmony','conflict_reduction') NOT NULL;
ALTER TABLE `lupo_calibration_impacts` MODIFY COLUMN `impact_measurement` decimal(5,4) NOT NULL COMMENT 'Quantified impact (0.0000-1.0000)';
ALTER TABLE `lupo_calibration_impacts` MODIFY COLUMN `measurement_method` varchar(100) NOT NULL COMMENT 'How impact was measured';
ALTER TABLE `lupo_calibration_impacts` MODIFY COLUMN `before_metrics_json` json COMMENT 'Metrics before calibration';
ALTER TABLE `lupo_calibration_impacts` MODIFY COLUMN `after_metrics_json` json COMMENT 'Metrics after calibration';
ALTER TABLE `lupo_calibration_impacts` MODIFY COLUMN `observation_period_hours` int unsigned DEFAULT 24 COMMENT 'Observation period length';
ALTER TABLE `lupo_calibration_impacts` MODIFY COLUMN `measured_ymdhis` bigint NOT NULL COMMENT 'When impact was measured';
ALTER TABLE `lupo_calibration_impacts` MODIFY COLUMN `impact_version` varchar(20) DEFAULT '4.0.75' COMMENT 'Impact tracking version';
ALTER TABLE `lupo_calibration_impacts` ADD PRIMARY KEY (`id`);
ALTER TABLE `lupo_calibration_impacts` ADD INDEX IF NOT EXISTS `idx_calibration_impact` (`calibration_id`, `impact_type`);
ALTER TABLE `lupo_calibration_impacts` ADD INDEX IF NOT EXISTS `idx_impact_measurement` (`impact_measurement`);
ALTER TABLE `lupo_calibration_impacts` ADD INDEX IF NOT EXISTS `idx_measurement_time` (`measured_ymdhis`);

CREATE TABLE IF NOT EXISTS `lupo_channel_boot_detail` (
  `detail_id` bigint NOT NULL auto_increment COMMENT 'Primary key for boot detail',
  `boot_id` bigint NOT NULL COMMENT 'Reference to lupo_channel_boot_log.boot_id',
  `channel_id` bigint NOT NULL COMMENT 'Reference to lupo_channels.channel_id',
  `load_start_time` bigint NOT NULL COMMENT 'Channel load start',
  `load_end_time` bigint COMMENT 'Channel load completion',
  `load_status` enum('started','loading','completed','failed','skipped') NOT NULL DEFAULT 'started',
  `content_items_loaded` int NOT NULL DEFAULT 0 COMMENT 'Content items successfully loaded',
  `total_content_items` int NOT NULL DEFAULT 0 COMMENT 'Total content items in channel',
  `load_duration_ms` int COMMENT 'Load duration in milliseconds',
  `error_message` text COMMENT 'Load error details',
  `created_ymdhis` bigint NOT NULL DEFAULT 0 COMMENT 'Creation bigint (YYYYMMDDHHMMSS)',
  PRIMARY KEY (`detail_id`),
  KEY `fk_boot_detail_channel` (`channel_id`),
  KEY `idx_boot_channel` (`boot_id`, `channel_id`),
  KEY `idx_load_status_time` (`load_status`, `load_start_time`)
);

ALTER TABLE `lupo_channel_boot_detail` ADD COLUMN IF NOT EXISTS `detail_id` bigint NOT NULL auto_increment COMMENT 'Primary key for boot detail';
ALTER TABLE `lupo_channel_boot_detail` ADD COLUMN IF NOT EXISTS `boot_id` bigint NOT NULL COMMENT 'Reference to lupo_channel_boot_log.boot_id';
ALTER TABLE `lupo_channel_boot_detail` ADD COLUMN IF NOT EXISTS `channel_id` bigint NOT NULL COMMENT 'Reference to lupo_channels.channel_id';
ALTER TABLE `lupo_channel_boot_detail` ADD COLUMN IF NOT EXISTS `load_start_time` bigint NOT NULL COMMENT 'Channel load start';
ALTER TABLE `lupo_channel_boot_detail` ADD COLUMN IF NOT EXISTS `load_end_time` bigint COMMENT 'Channel load completion';
ALTER TABLE `lupo_channel_boot_detail` ADD COLUMN IF NOT EXISTS `load_status` enum('started','loading','completed','failed','skipped') NOT NULL DEFAULT 'started';
ALTER TABLE `lupo_channel_boot_detail` ADD COLUMN IF NOT EXISTS `content_items_loaded` int NOT NULL DEFAULT 0 COMMENT 'Content items successfully loaded';
ALTER TABLE `lupo_channel_boot_detail` ADD COLUMN IF NOT EXISTS `total_content_items` int NOT NULL DEFAULT 0 COMMENT 'Total content items in channel';
ALTER TABLE `lupo_channel_boot_detail` ADD COLUMN IF NOT EXISTS `load_duration_ms` int COMMENT 'Load duration in milliseconds';
ALTER TABLE `lupo_channel_boot_detail` ADD COLUMN IF NOT EXISTS `error_message` text COMMENT 'Load error details';
ALTER TABLE `lupo_channel_boot_detail` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL DEFAULT 0 COMMENT 'Creation bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_channel_boot_detail` MODIFY COLUMN `detail_id` bigint NOT NULL auto_increment COMMENT 'Primary key for boot detail';
ALTER TABLE `lupo_channel_boot_detail` MODIFY COLUMN `boot_id` bigint NOT NULL COMMENT 'Reference to lupo_channel_boot_log.boot_id';
ALTER TABLE `lupo_channel_boot_detail` MODIFY COLUMN `channel_id` bigint NOT NULL COMMENT 'Reference to lupo_channels.channel_id';
ALTER TABLE `lupo_channel_boot_detail` MODIFY COLUMN `load_start_time` bigint NOT NULL COMMENT 'Channel load start';
ALTER TABLE `lupo_channel_boot_detail` MODIFY COLUMN `load_end_time` bigint COMMENT 'Channel load completion';
ALTER TABLE `lupo_channel_boot_detail` MODIFY COLUMN `load_status` enum('started','loading','completed','failed','skipped') NOT NULL DEFAULT 'started';
ALTER TABLE `lupo_channel_boot_detail` MODIFY COLUMN `content_items_loaded` int NOT NULL DEFAULT 0 COMMENT 'Content items successfully loaded';
ALTER TABLE `lupo_channel_boot_detail` MODIFY COLUMN `total_content_items` int NOT NULL DEFAULT 0 COMMENT 'Total content items in channel';
ALTER TABLE `lupo_channel_boot_detail` MODIFY COLUMN `load_duration_ms` int COMMENT 'Load duration in milliseconds';
ALTER TABLE `lupo_channel_boot_detail` MODIFY COLUMN `error_message` text COMMENT 'Load error details';
ALTER TABLE `lupo_channel_boot_detail` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0 COMMENT 'Creation bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_channel_boot_detail` ADD PRIMARY KEY (`detail_id`);
ALTER TABLE `lupo_channel_boot_detail` ADD INDEX IF NOT EXISTS `fk_boot_detail_channel` (`channel_id`);
ALTER TABLE `lupo_channel_boot_detail` ADD INDEX IF NOT EXISTS `idx_boot_channel` (`boot_id`, `channel_id`);
ALTER TABLE `lupo_channel_boot_detail` ADD INDEX IF NOT EXISTS `idx_load_status_time` (`load_status`, `load_start_time`);

CREATE TABLE IF NOT EXISTS `lupo_channel_boot_log` (
  `boot_id` bigint NOT NULL auto_increment COMMENT 'Primary key for boot event',
  `actor_id` bigint COMMENT 'Actor that initiated boot sequence',
  `session_id` varchar(64) COMMENT 'Session identifier',
  `boot_start_time` bigint NOT NULL COMMENT 'Boot sequence start',
  `boot_end_time` bigint COMMENT 'Boot sequence completion',
  `boot_status` enum('started','in_progress','completed','failed','interrupted') NOT NULL DEFAULT 'started',
  `channels_loaded` int NOT NULL DEFAULT 0 COMMENT 'Number of channels successfully loaded',
  `total_channels` int NOT NULL DEFAULT 0 COMMENT 'Total channels in boot sequence',
  `error_details` json COMMENT 'Boot error information',
  `performance_metrics` json COMMENT 'Boot performance data',
  `created_ymdhis` bigint NOT NULL DEFAULT 0 COMMENT 'Creation bigint (YYYYMMDDHHMMSS)',
  PRIMARY KEY (`boot_id`),
  KEY `idx_actor_session` (`actor_id`, `session_id`),
  KEY `idx_boot_status_time` (`boot_status`, `boot_start_time`)
);

ALTER TABLE `lupo_channel_boot_log` ADD COLUMN IF NOT EXISTS `boot_id` bigint NOT NULL auto_increment COMMENT 'Primary key for boot event';
ALTER TABLE `lupo_channel_boot_log` ADD COLUMN IF NOT EXISTS `actor_id` bigint COMMENT 'Actor that initiated boot sequence';
ALTER TABLE `lupo_channel_boot_log` ADD COLUMN IF NOT EXISTS `session_id` varchar(64) COMMENT 'Session identifier';
ALTER TABLE `lupo_channel_boot_log` ADD COLUMN IF NOT EXISTS `boot_start_time` bigint NOT NULL COMMENT 'Boot sequence start';
ALTER TABLE `lupo_channel_boot_log` ADD COLUMN IF NOT EXISTS `boot_end_time` bigint COMMENT 'Boot sequence completion';
ALTER TABLE `lupo_channel_boot_log` ADD COLUMN IF NOT EXISTS `boot_status` enum('started','in_progress','completed','failed','interrupted') NOT NULL DEFAULT 'started';
ALTER TABLE `lupo_channel_boot_log` ADD COLUMN IF NOT EXISTS `channels_loaded` int NOT NULL DEFAULT 0 COMMENT 'Number of channels successfully loaded';
ALTER TABLE `lupo_channel_boot_log` ADD COLUMN IF NOT EXISTS `total_channels` int NOT NULL DEFAULT 0 COMMENT 'Total channels in boot sequence';
ALTER TABLE `lupo_channel_boot_log` ADD COLUMN IF NOT EXISTS `error_details` json COMMENT 'Boot error information';
ALTER TABLE `lupo_channel_boot_log` ADD COLUMN IF NOT EXISTS `performance_metrics` json COMMENT 'Boot performance data';
ALTER TABLE `lupo_channel_boot_log` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL DEFAULT 0 COMMENT 'Creation bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_channel_boot_log` MODIFY COLUMN `boot_id` bigint NOT NULL auto_increment COMMENT 'Primary key for boot event';
ALTER TABLE `lupo_channel_boot_log` MODIFY COLUMN `actor_id` bigint COMMENT 'Actor that initiated boot sequence';
ALTER TABLE `lupo_channel_boot_log` MODIFY COLUMN `session_id` varchar(64) COMMENT 'Session identifier';
ALTER TABLE `lupo_channel_boot_log` MODIFY COLUMN `boot_start_time` bigint NOT NULL COMMENT 'Boot sequence start';
ALTER TABLE `lupo_channel_boot_log` MODIFY COLUMN `boot_end_time` bigint COMMENT 'Boot sequence completion';
ALTER TABLE `lupo_channel_boot_log` MODIFY COLUMN `boot_status` enum('started','in_progress','completed','failed','interrupted') NOT NULL DEFAULT 'started';
ALTER TABLE `lupo_channel_boot_log` MODIFY COLUMN `channels_loaded` int NOT NULL DEFAULT 0 COMMENT 'Number of channels successfully loaded';
ALTER TABLE `lupo_channel_boot_log` MODIFY COLUMN `total_channels` int NOT NULL DEFAULT 0 COMMENT 'Total channels in boot sequence';
ALTER TABLE `lupo_channel_boot_log` MODIFY COLUMN `error_details` json COMMENT 'Boot error information';
ALTER TABLE `lupo_channel_boot_log` MODIFY COLUMN `performance_metrics` json COMMENT 'Boot performance data';
ALTER TABLE `lupo_channel_boot_log` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0 COMMENT 'Creation bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_channel_boot_log` ADD PRIMARY KEY (`boot_id`);
ALTER TABLE `lupo_channel_boot_log` ADD INDEX IF NOT EXISTS `idx_actor_session` (`actor_id`, `session_id`);
ALTER TABLE `lupo_channel_boot_log` ADD INDEX IF NOT EXISTS `idx_boot_status_time` (`boot_status`, `boot_start_time`);

CREATE TABLE IF NOT EXISTS `lupo_channel_state` (
  `state_id` bigint unsigned NOT NULL auto_increment,
  `channel_id` bigint unsigned NOT NULL,
  `active_actors_json` json,
  `speaker_actors_json` json,
  `observer_actors_json` json,
  `layers_enabled_json` json,
  `operational_mode` varchar(32),
  `emotional_state_json` json,
  `recent_topics_json` json,
  `semantic_weight` float DEFAULT 0,
  `trend_score` float DEFAULT 0,
  `last_activity_ymdhis` bigint unsigned,
  `context_vector` blob,
  `routing_rules` varchar(32),
  `edge_visibility` varchar(32),
  `retention_policy` varchar(32),
  `decay_policy` varchar(32),
  `archive_flag` tinyint(1) DEFAULT 0,
  `metadata_json` json,
  `created_ymdhis` bigint unsigned NOT NULL,
  `updated_ymdhis` bigint unsigned NOT NULL,
  PRIMARY KEY (`state_id`),
  KEY `idx_channel_id` (`channel_id`)
);

ALTER TABLE `lupo_channel_state` ADD COLUMN IF NOT EXISTS `state_id` bigint unsigned NOT NULL auto_increment;
ALTER TABLE `lupo_channel_state` ADD COLUMN IF NOT EXISTS `channel_id` bigint unsigned NOT NULL;
ALTER TABLE `lupo_channel_state` ADD COLUMN IF NOT EXISTS `active_actors_json` json;
ALTER TABLE `lupo_channel_state` ADD COLUMN IF NOT EXISTS `speaker_actors_json` json;
ALTER TABLE `lupo_channel_state` ADD COLUMN IF NOT EXISTS `observer_actors_json` json;
ALTER TABLE `lupo_channel_state` ADD COLUMN IF NOT EXISTS `layers_enabled_json` json;
ALTER TABLE `lupo_channel_state` ADD COLUMN IF NOT EXISTS `operational_mode` varchar(32);
ALTER TABLE `lupo_channel_state` ADD COLUMN IF NOT EXISTS `emotional_state_json` json;
ALTER TABLE `lupo_channel_state` ADD COLUMN IF NOT EXISTS `recent_topics_json` json;
ALTER TABLE `lupo_channel_state` ADD COLUMN IF NOT EXISTS `semantic_weight` float DEFAULT 0;
ALTER TABLE `lupo_channel_state` ADD COLUMN IF NOT EXISTS `trend_score` float DEFAULT 0;
ALTER TABLE `lupo_channel_state` ADD COLUMN IF NOT EXISTS `last_activity_ymdhis` bigint unsigned;
ALTER TABLE `lupo_channel_state` ADD COLUMN IF NOT EXISTS `context_vector` blob;
ALTER TABLE `lupo_channel_state` ADD COLUMN IF NOT EXISTS `routing_rules` varchar(32);
ALTER TABLE `lupo_channel_state` ADD COLUMN IF NOT EXISTS `edge_visibility` varchar(32);
ALTER TABLE `lupo_channel_state` ADD COLUMN IF NOT EXISTS `retention_policy` varchar(32);
ALTER TABLE `lupo_channel_state` ADD COLUMN IF NOT EXISTS `decay_policy` varchar(32);
ALTER TABLE `lupo_channel_state` ADD COLUMN IF NOT EXISTS `archive_flag` tinyint(1) DEFAULT 0;
ALTER TABLE `lupo_channel_state` ADD COLUMN IF NOT EXISTS `metadata_json` json;
ALTER TABLE `lupo_channel_state` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint unsigned NOT NULL;
ALTER TABLE `lupo_channel_state` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint unsigned NOT NULL;
ALTER TABLE `lupo_channel_state` MODIFY COLUMN `state_id` bigint unsigned NOT NULL auto_increment;
ALTER TABLE `lupo_channel_state` MODIFY COLUMN `channel_id` bigint unsigned NOT NULL;
ALTER TABLE `lupo_channel_state` MODIFY COLUMN `active_actors_json` json;
ALTER TABLE `lupo_channel_state` MODIFY COLUMN `speaker_actors_json` json;
ALTER TABLE `lupo_channel_state` MODIFY COLUMN `observer_actors_json` json;
ALTER TABLE `lupo_channel_state` MODIFY COLUMN `layers_enabled_json` json;
ALTER TABLE `lupo_channel_state` MODIFY COLUMN `operational_mode` varchar(32);
ALTER TABLE `lupo_channel_state` MODIFY COLUMN `emotional_state_json` json;
ALTER TABLE `lupo_channel_state` MODIFY COLUMN `recent_topics_json` json;
ALTER TABLE `lupo_channel_state` MODIFY COLUMN `semantic_weight` float DEFAULT 0;
ALTER TABLE `lupo_channel_state` MODIFY COLUMN `trend_score` float DEFAULT 0;
ALTER TABLE `lupo_channel_state` MODIFY COLUMN `last_activity_ymdhis` bigint unsigned;
ALTER TABLE `lupo_channel_state` MODIFY COLUMN `context_vector` blob;
ALTER TABLE `lupo_channel_state` MODIFY COLUMN `routing_rules` varchar(32);
ALTER TABLE `lupo_channel_state` MODIFY COLUMN `edge_visibility` varchar(32);
ALTER TABLE `lupo_channel_state` MODIFY COLUMN `retention_policy` varchar(32);
ALTER TABLE `lupo_channel_state` MODIFY COLUMN `decay_policy` varchar(32);
ALTER TABLE `lupo_channel_state` MODIFY COLUMN `archive_flag` tinyint(1) DEFAULT 0;
ALTER TABLE `lupo_channel_state` MODIFY COLUMN `metadata_json` json;
ALTER TABLE `lupo_channel_state` MODIFY COLUMN `created_ymdhis` bigint unsigned NOT NULL;
ALTER TABLE `lupo_channel_state` MODIFY COLUMN `updated_ymdhis` bigint unsigned NOT NULL;
ALTER TABLE `lupo_channel_state` ADD PRIMARY KEY (`state_id`);
ALTER TABLE `lupo_channel_state` ADD INDEX IF NOT EXISTS `idx_channel_id` (`channel_id`);

CREATE TABLE IF NOT EXISTS `lupo_channels` (
  `channel_id` bigint NOT NULL auto_increment COMMENT 'Primary key for channel',
  `federation_node_id` bigint NOT NULL COMMENT 'Domain/tenant identifier',
  `created_by_actor_id` bigint NOT NULL COMMENT 'who made this channel',
  `default_actor_id` bigint NOT NULL DEFAULT 1 COMMENT 'Default actor ID',
  `channel_key` varchar(64) NOT NULL COMMENT 'URL-friendly identifier (slug)',
  `channel_slug` varchar(32) NOT NULL DEFAULT 'channel_key' COMMENT 'well if they think it exists i guess i will make it',
  `channel_type` varchar(32) NOT NULL DEFAULT 'chat_room',
  `language` varchar(16) NOT NULL DEFAULT 'en',
  `channel_name` varchar(255) NOT NULL COMMENT 'Human-readable channel name',
  `description` text COMMENT 'Channel description',
  `metadata_json` text COMMENT 'JSON metadata for the channel',
  `bgcolor` varchar(6) NOT NULL DEFAULT 'FFFFFF',
  `status_flag` tinyint NOT NULL DEFAULT 1 COMMENT 'Status flag (1=active, 0=inactive)',
  `end_ymdhis` bigint COMMENT 'Channel end bigint (YYYYMMDDHHMMSS, NULL if ongoing)',
  `duration_seconds` int COMMENT 'Duration of the channel in seconds (if ended)',
  `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint (YYYYMMDDHHMMSS)',
  `updated_ymdhis` bigint NOT NULL COMMENT 'Last update bigint (YYYYMMDDHHMMSS)',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted, 0=active)',
  `deleted_ymdhis` bigint COMMENT 'Deletion bigint (YYYYMMDDHHMMSS)',
  `aal_metadata_json` json COMMENT 'Agent Awareness Layer metadata for WHO/WHAT/WHERE/WHEN/WHY/HOW/PURPOSE',
  `fleet_composition_json` json COMMENT 'Current fleet of agents in this channel with their roles',
  `awareness_version` varchar(20) DEFAULT '4.0.72' COMMENT 'AAL protocol version for this channel',
  `channel_number` int COMMENT 'Channel number (0-9 reserved for system)',
  `parent_channel_id` bigint COMMENT 'Reference to parent channel for hierarchy',
  `is_kernel` tinyint NOT NULL DEFAULT 0 COMMENT 'Part of system kernel (Channel 0)',
  `boot_sequence_order` int COMMENT 'Order in kernel boot sequence',
  PRIMARY KEY (`channel_id`),
  KEY `idx_awareness_version` (`awareness_version`),
  KEY `idx_channel_key` (`channel_key`),
  KEY `idx_dates` (`end_ymdhis`),
  KEY `idx_domain` (`federation_node_id`),
  KEY `idx_status` (`status_flag`),
  UNIQUE KEY `unq_channel_key_per_node` (`channel_key`, `federation_node_id`)
);

ALTER TABLE `lupo_channels` ADD COLUMN IF NOT EXISTS `channel_id` bigint NOT NULL auto_increment COMMENT 'Primary key for channel';
ALTER TABLE `lupo_channels` ADD COLUMN IF NOT EXISTS `federation_node_id` bigint NOT NULL COMMENT 'Domain/tenant identifier';
ALTER TABLE `lupo_channels` ADD COLUMN IF NOT EXISTS `created_by_actor_id` bigint NOT NULL COMMENT 'who made this channel';
ALTER TABLE `lupo_channels` ADD COLUMN IF NOT EXISTS `default_actor_id` bigint NOT NULL DEFAULT 1 COMMENT 'Default actor ID';
ALTER TABLE `lupo_channels` ADD COLUMN IF NOT EXISTS `channel_key` varchar(64) NOT NULL COMMENT 'URL-friendly identifier (slug)';
ALTER TABLE `lupo_channels` ADD COLUMN IF NOT EXISTS `channel_slug` varchar(32) NOT NULL DEFAULT 'channel_key' COMMENT 'well if they think it exists i guess i will make it';
ALTER TABLE `lupo_channels` ADD COLUMN IF NOT EXISTS `channel_type` varchar(32) NOT NULL DEFAULT 'chat_room';
ALTER TABLE `lupo_channels` ADD COLUMN IF NOT EXISTS `language` varchar(16) NOT NULL DEFAULT 'en';
ALTER TABLE `lupo_channels` ADD COLUMN IF NOT EXISTS `channel_name` varchar(255) NOT NULL COMMENT 'Human-readable channel name';
ALTER TABLE `lupo_channels` ADD COLUMN IF NOT EXISTS `description` text COMMENT 'Channel description';
ALTER TABLE `lupo_channels` ADD COLUMN IF NOT EXISTS `metadata_json` text COMMENT 'JSON metadata for the channel';
ALTER TABLE `lupo_channels` ADD COLUMN IF NOT EXISTS `bgcolor` varchar(6) NOT NULL DEFAULT 'FFFFFF';
ALTER TABLE `lupo_channels` ADD COLUMN IF NOT EXISTS `status_flag` tinyint NOT NULL DEFAULT 1 COMMENT 'Status flag (1=active, 0=inactive)';
ALTER TABLE `lupo_channels` ADD COLUMN IF NOT EXISTS `end_ymdhis` bigint COMMENT 'Channel end bigint (YYYYMMDDHHMMSS, NULL if ongoing)';
ALTER TABLE `lupo_channels` ADD COLUMN IF NOT EXISTS `duration_seconds` int COMMENT 'Duration of the channel in seconds (if ended)';
ALTER TABLE `lupo_channels` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_channels` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL COMMENT 'Last update bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_channels` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted, 0=active)';
ALTER TABLE `lupo_channels` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint COMMENT 'Deletion bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_channels` ADD COLUMN IF NOT EXISTS `aal_metadata_json` json COMMENT 'Agent Awareness Layer metadata for WHO/WHAT/WHERE/WHEN/WHY/HOW/PURPOSE';
ALTER TABLE `lupo_channels` ADD COLUMN IF NOT EXISTS `fleet_composition_json` json COMMENT 'Current fleet of agents in this channel with their roles';
ALTER TABLE `lupo_channels` ADD COLUMN IF NOT EXISTS `awareness_version` varchar(20) DEFAULT '4.0.72' COMMENT 'AAL protocol version for this channel';
ALTER TABLE `lupo_channels` ADD COLUMN IF NOT EXISTS `channel_number` int COMMENT 'Channel number (0-9 reserved for system)';
ALTER TABLE `lupo_channels` ADD COLUMN IF NOT EXISTS `parent_channel_id` bigint COMMENT 'Reference to parent channel for hierarchy';
ALTER TABLE `lupo_channels` ADD COLUMN IF NOT EXISTS `is_kernel` tinyint NOT NULL DEFAULT 0 COMMENT 'Part of system kernel (Channel 0)';
ALTER TABLE `lupo_channels` ADD COLUMN IF NOT EXISTS `boot_sequence_order` int COMMENT 'Order in kernel boot sequence';
ALTER TABLE `lupo_channels` MODIFY COLUMN `channel_id` bigint NOT NULL auto_increment COMMENT 'Primary key for channel';
ALTER TABLE `lupo_channels` MODIFY COLUMN `federation_node_id` bigint NOT NULL COMMENT 'Domain/tenant identifier';
ALTER TABLE `lupo_channels` MODIFY COLUMN `created_by_actor_id` bigint NOT NULL COMMENT 'who made this channel';
ALTER TABLE `lupo_channels` MODIFY COLUMN `default_actor_id` bigint NOT NULL DEFAULT 1 COMMENT 'Default actor ID';
ALTER TABLE `lupo_channels` MODIFY COLUMN `channel_key` varchar(64) NOT NULL COMMENT 'URL-friendly identifier (slug)';
ALTER TABLE `lupo_channels` MODIFY COLUMN `channel_slug` varchar(32) NOT NULL DEFAULT 'channel_key' COMMENT 'well if they think it exists i guess i will make it';
ALTER TABLE `lupo_channels` MODIFY COLUMN `channel_type` varchar(32) NOT NULL DEFAULT 'chat_room';
ALTER TABLE `lupo_channels` MODIFY COLUMN `language` varchar(16) NOT NULL DEFAULT 'en';
ALTER TABLE `lupo_channels` MODIFY COLUMN `channel_name` varchar(255) NOT NULL COMMENT 'Human-readable channel name';
ALTER TABLE `lupo_channels` MODIFY COLUMN `description` text COMMENT 'Channel description';
ALTER TABLE `lupo_channels` MODIFY COLUMN `metadata_json` text COMMENT 'JSON metadata for the channel';
ALTER TABLE `lupo_channels` MODIFY COLUMN `bgcolor` varchar(6) NOT NULL DEFAULT 'FFFFFF';
ALTER TABLE `lupo_channels` MODIFY COLUMN `status_flag` tinyint NOT NULL DEFAULT 1 COMMENT 'Status flag (1=active, 0=inactive)';
ALTER TABLE `lupo_channels` MODIFY COLUMN `end_ymdhis` bigint COMMENT 'Channel end bigint (YYYYMMDDHHMMSS, NULL if ongoing)';
ALTER TABLE `lupo_channels` MODIFY COLUMN `duration_seconds` int COMMENT 'Duration of the channel in seconds (if ended)';
ALTER TABLE `lupo_channels` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_channels` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL COMMENT 'Last update bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_channels` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted, 0=active)';
ALTER TABLE `lupo_channels` MODIFY COLUMN `deleted_ymdhis` bigint COMMENT 'Deletion bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_channels` MODIFY COLUMN `aal_metadata_json` json COMMENT 'Agent Awareness Layer metadata for WHO/WHAT/WHERE/WHEN/WHY/HOW/PURPOSE';
ALTER TABLE `lupo_channels` MODIFY COLUMN `fleet_composition_json` json COMMENT 'Current fleet of agents in this channel with their roles';
ALTER TABLE `lupo_channels` MODIFY COLUMN `awareness_version` varchar(20) DEFAULT '4.0.72' COMMENT 'AAL protocol version for this channel';
ALTER TABLE `lupo_channels` MODIFY COLUMN `channel_number` int COMMENT 'Channel number (0-9 reserved for system)';
ALTER TABLE `lupo_channels` MODIFY COLUMN `parent_channel_id` bigint COMMENT 'Reference to parent channel for hierarchy';
ALTER TABLE `lupo_channels` MODIFY COLUMN `is_kernel` tinyint NOT NULL DEFAULT 0 COMMENT 'Part of system kernel (Channel 0)';
ALTER TABLE `lupo_channels` MODIFY COLUMN `boot_sequence_order` int COMMENT 'Order in kernel boot sequence';
ALTER TABLE `lupo_channels` ADD PRIMARY KEY (`channel_id`);
ALTER TABLE `lupo_channels` ADD INDEX IF NOT EXISTS `idx_awareness_version` (`awareness_version`);
ALTER TABLE `lupo_channels` ADD INDEX IF NOT EXISTS `idx_channel_key` (`channel_key`);
ALTER TABLE `lupo_channels` ADD INDEX IF NOT EXISTS `idx_dates` (`end_ymdhis`);
ALTER TABLE `lupo_channels` ADD INDEX IF NOT EXISTS `idx_domain` (`federation_node_id`);
ALTER TABLE `lupo_channels` ADD INDEX IF NOT EXISTS `idx_status` (`status_flag`);
ALTER TABLE `lupo_channels` ADD UNIQUE INDEX IF NOT EXISTS `unq_channel_key_per_node` (`channel_key`, `federation_node_id`);

CREATE TABLE IF NOT EXISTS `lupo_cip_analytics` (
  `id` bigint unsigned NOT NULL auto_increment,
  `event_id` bigint unsigned NOT NULL COMMENT 'Reference to lupo_cip_events.id',
  `defensiveness_index` decimal(5,4) NOT NULL DEFAULT 0.0000 COMMENT 'DI: 0.0000-1.0000 scale',
  `integration_velocity` decimal(5,4) NOT NULL DEFAULT 0.0000 COMMENT 'IV: 0.0000-1.0000 scale',
  `architectural_impact_score` decimal(5,4) NOT NULL DEFAULT 0.0000 COMMENT 'AIS: 0.0000-1.0000 scale',
  `doctrine_propagation_depth` tinyint unsigned NOT NULL DEFAULT 0 COMMENT 'DPD: 0-10 depth levels',
  `critique_source_weight` decimal(5,4) NOT NULL DEFAULT 0.5000 COMMENT 'Source credibility weight',
  `subsystem_impact_json` json COMMENT 'Impact analysis per subsystem',
  `trend_analysis_json` json COMMENT 'Historical trend data',
  `calculated_ymdhis` bigint NOT NULL COMMENT 'When analytics were calculated',
  `recalculated_ymdhis` bigint COMMENT 'Last recalculation bigint',
  `analytics_version` varchar(20) DEFAULT '4.0.75' COMMENT 'Analytics engine version',
  PRIMARY KEY (`id`),
  KEY `idx_architectural_impact` (`architectural_impact_score`),
  KEY `idx_calculated_time` (`calculated_ymdhis`),
  KEY `idx_defensiveness_index` (`defensiveness_index`),
  KEY `idx_integration_velocity` (`integration_velocity`),
  UNIQUE KEY `uk_event_analytics` (`event_id`)
);

ALTER TABLE `lupo_cip_analytics` ADD COLUMN IF NOT EXISTS `id` bigint unsigned NOT NULL auto_increment;
ALTER TABLE `lupo_cip_analytics` ADD COLUMN IF NOT EXISTS `event_id` bigint unsigned NOT NULL COMMENT 'Reference to lupo_cip_events.id';
ALTER TABLE `lupo_cip_analytics` ADD COLUMN IF NOT EXISTS `defensiveness_index` decimal(5,4) NOT NULL DEFAULT 0.0000 COMMENT 'DI: 0.0000-1.0000 scale';
ALTER TABLE `lupo_cip_analytics` ADD COLUMN IF NOT EXISTS `integration_velocity` decimal(5,4) NOT NULL DEFAULT 0.0000 COMMENT 'IV: 0.0000-1.0000 scale';
ALTER TABLE `lupo_cip_analytics` ADD COLUMN IF NOT EXISTS `architectural_impact_score` decimal(5,4) NOT NULL DEFAULT 0.0000 COMMENT 'AIS: 0.0000-1.0000 scale';
ALTER TABLE `lupo_cip_analytics` ADD COLUMN IF NOT EXISTS `doctrine_propagation_depth` tinyint unsigned NOT NULL DEFAULT 0 COMMENT 'DPD: 0-10 depth levels';
ALTER TABLE `lupo_cip_analytics` ADD COLUMN IF NOT EXISTS `critique_source_weight` decimal(5,4) NOT NULL DEFAULT 0.5000 COMMENT 'Source credibility weight';
ALTER TABLE `lupo_cip_analytics` ADD COLUMN IF NOT EXISTS `subsystem_impact_json` json COMMENT 'Impact analysis per subsystem';
ALTER TABLE `lupo_cip_analytics` ADD COLUMN IF NOT EXISTS `trend_analysis_json` json COMMENT 'Historical trend data';
ALTER TABLE `lupo_cip_analytics` ADD COLUMN IF NOT EXISTS `calculated_ymdhis` bigint NOT NULL COMMENT 'When analytics were calculated';
ALTER TABLE `lupo_cip_analytics` ADD COLUMN IF NOT EXISTS `recalculated_ymdhis` bigint COMMENT 'Last recalculation bigint';
ALTER TABLE `lupo_cip_analytics` ADD COLUMN IF NOT EXISTS `analytics_version` varchar(20) DEFAULT '4.0.75' COMMENT 'Analytics engine version';
ALTER TABLE `lupo_cip_analytics` MODIFY COLUMN `id` bigint unsigned NOT NULL auto_increment;
ALTER TABLE `lupo_cip_analytics` MODIFY COLUMN `event_id` bigint unsigned NOT NULL COMMENT 'Reference to lupo_cip_events.id';
ALTER TABLE `lupo_cip_analytics` MODIFY COLUMN `defensiveness_index` decimal(5,4) NOT NULL DEFAULT 0.0000 COMMENT 'DI: 0.0000-1.0000 scale';
ALTER TABLE `lupo_cip_analytics` MODIFY COLUMN `integration_velocity` decimal(5,4) NOT NULL DEFAULT 0.0000 COMMENT 'IV: 0.0000-1.0000 scale';
ALTER TABLE `lupo_cip_analytics` MODIFY COLUMN `architectural_impact_score` decimal(5,4) NOT NULL DEFAULT 0.0000 COMMENT 'AIS: 0.0000-1.0000 scale';
ALTER TABLE `lupo_cip_analytics` MODIFY COLUMN `doctrine_propagation_depth` tinyint unsigned NOT NULL DEFAULT 0 COMMENT 'DPD: 0-10 depth levels';
ALTER TABLE `lupo_cip_analytics` MODIFY COLUMN `critique_source_weight` decimal(5,4) NOT NULL DEFAULT 0.5000 COMMENT 'Source credibility weight';
ALTER TABLE `lupo_cip_analytics` MODIFY COLUMN `subsystem_impact_json` json COMMENT 'Impact analysis per subsystem';
ALTER TABLE `lupo_cip_analytics` MODIFY COLUMN `trend_analysis_json` json COMMENT 'Historical trend data';
ALTER TABLE `lupo_cip_analytics` MODIFY COLUMN `calculated_ymdhis` bigint NOT NULL COMMENT 'When analytics were calculated';
ALTER TABLE `lupo_cip_analytics` MODIFY COLUMN `recalculated_ymdhis` bigint COMMENT 'Last recalculation bigint';
ALTER TABLE `lupo_cip_analytics` MODIFY COLUMN `analytics_version` varchar(20) DEFAULT '4.0.75' COMMENT 'Analytics engine version';
ALTER TABLE `lupo_cip_analytics` ADD PRIMARY KEY (`id`);
ALTER TABLE `lupo_cip_analytics` ADD INDEX IF NOT EXISTS `idx_architectural_impact` (`architectural_impact_score`);
ALTER TABLE `lupo_cip_analytics` ADD INDEX IF NOT EXISTS `idx_calculated_time` (`calculated_ymdhis`);
ALTER TABLE `lupo_cip_analytics` ADD INDEX IF NOT EXISTS `idx_defensiveness_index` (`defensiveness_index`);
ALTER TABLE `lupo_cip_analytics` ADD INDEX IF NOT EXISTS `idx_integration_velocity` (`integration_velocity`);
ALTER TABLE `lupo_cip_analytics` ADD UNIQUE INDEX IF NOT EXISTS `uk_event_analytics` (`event_id`);

CREATE TABLE IF NOT EXISTS `lupo_cip_propagation_tracking` (
  `id` bigint unsigned NOT NULL auto_increment,
  `cip_event_id` bigint unsigned NOT NULL COMMENT 'Root CIP event',
  `propagation_level` tinyint unsigned NOT NULL COMMENT 'Depth level (0-10)',
  `affected_subsystem` varchar(100) NOT NULL COMMENT 'Subsystem affected at this level',
  `propagation_type` enum('doctrine','emotional_geometry','agent_behavior','system_config') NOT NULL,
  `change_description` text NOT NULL COMMENT 'What changed at this level',
  `propagation_strength` decimal(5,4) NOT NULL DEFAULT 1.0000 COMMENT 'Strength of propagation',
  `completion_status` enum('pending','in_progress','completed','blocked','failed') DEFAULT 'pending',
  `dependencies_json` json COMMENT 'Dependencies for this propagation step',
  `started_ymdhis` bigint COMMENT 'When propagation started',
  `completed_ymdhis` bigint COMMENT 'When propagation completed',
  `propagation_version` varchar(20) DEFAULT '4.0.75' COMMENT 'Propagation tracking version',
  PRIMARY KEY (`id`),
  KEY `idx_completion_status` (`completion_status`),
  KEY `idx_event_level` (`cip_event_id`, `propagation_level`),
  KEY `idx_propagation_strength` (`propagation_strength`),
  KEY `idx_subsystem` (`affected_subsystem`)
);

ALTER TABLE `lupo_cip_propagation_tracking` ADD COLUMN IF NOT EXISTS `id` bigint unsigned NOT NULL auto_increment;
ALTER TABLE `lupo_cip_propagation_tracking` ADD COLUMN IF NOT EXISTS `cip_event_id` bigint unsigned NOT NULL COMMENT 'Root CIP event';
ALTER TABLE `lupo_cip_propagation_tracking` ADD COLUMN IF NOT EXISTS `propagation_level` tinyint unsigned NOT NULL COMMENT 'Depth level (0-10)';
ALTER TABLE `lupo_cip_propagation_tracking` ADD COLUMN IF NOT EXISTS `affected_subsystem` varchar(100) NOT NULL COMMENT 'Subsystem affected at this level';
ALTER TABLE `lupo_cip_propagation_tracking` ADD COLUMN IF NOT EXISTS `propagation_type` enum('doctrine','emotional_geometry','agent_behavior','system_config') NOT NULL;
ALTER TABLE `lupo_cip_propagation_tracking` ADD COLUMN IF NOT EXISTS `change_description` text NOT NULL COMMENT 'What changed at this level';
ALTER TABLE `lupo_cip_propagation_tracking` ADD COLUMN IF NOT EXISTS `propagation_strength` decimal(5,4) NOT NULL DEFAULT 1.0000 COMMENT 'Strength of propagation';
ALTER TABLE `lupo_cip_propagation_tracking` ADD COLUMN IF NOT EXISTS `completion_status` enum('pending','in_progress','completed','blocked','failed') DEFAULT 'pending';
ALTER TABLE `lupo_cip_propagation_tracking` ADD COLUMN IF NOT EXISTS `dependencies_json` json COMMENT 'Dependencies for this propagation step';
ALTER TABLE `lupo_cip_propagation_tracking` ADD COLUMN IF NOT EXISTS `started_ymdhis` bigint COMMENT 'When propagation started';
ALTER TABLE `lupo_cip_propagation_tracking` ADD COLUMN IF NOT EXISTS `completed_ymdhis` bigint COMMENT 'When propagation completed';
ALTER TABLE `lupo_cip_propagation_tracking` ADD COLUMN IF NOT EXISTS `propagation_version` varchar(20) DEFAULT '4.0.75' COMMENT 'Propagation tracking version';
ALTER TABLE `lupo_cip_propagation_tracking` MODIFY COLUMN `id` bigint unsigned NOT NULL auto_increment;
ALTER TABLE `lupo_cip_propagation_tracking` MODIFY COLUMN `cip_event_id` bigint unsigned NOT NULL COMMENT 'Root CIP event';
ALTER TABLE `lupo_cip_propagation_tracking` MODIFY COLUMN `propagation_level` tinyint unsigned NOT NULL COMMENT 'Depth level (0-10)';
ALTER TABLE `lupo_cip_propagation_tracking` MODIFY COLUMN `affected_subsystem` varchar(100) NOT NULL COMMENT 'Subsystem affected at this level';
ALTER TABLE `lupo_cip_propagation_tracking` MODIFY COLUMN `propagation_type` enum('doctrine','emotional_geometry','agent_behavior','system_config') NOT NULL;
ALTER TABLE `lupo_cip_propagation_tracking` MODIFY COLUMN `change_description` text NOT NULL COMMENT 'What changed at this level';
ALTER TABLE `lupo_cip_propagation_tracking` MODIFY COLUMN `propagation_strength` decimal(5,4) NOT NULL DEFAULT 1.0000 COMMENT 'Strength of propagation';
ALTER TABLE `lupo_cip_propagation_tracking` MODIFY COLUMN `completion_status` enum('pending','in_progress','completed','blocked','failed') DEFAULT 'pending';
ALTER TABLE `lupo_cip_propagation_tracking` MODIFY COLUMN `dependencies_json` json COMMENT 'Dependencies for this propagation step';
ALTER TABLE `lupo_cip_propagation_tracking` MODIFY COLUMN `started_ymdhis` bigint COMMENT 'When propagation started';
ALTER TABLE `lupo_cip_propagation_tracking` MODIFY COLUMN `completed_ymdhis` bigint COMMENT 'When propagation completed';
ALTER TABLE `lupo_cip_propagation_tracking` MODIFY COLUMN `propagation_version` varchar(20) DEFAULT '4.0.75' COMMENT 'Propagation tracking version';
ALTER TABLE `lupo_cip_propagation_tracking` ADD PRIMARY KEY (`id`);
ALTER TABLE `lupo_cip_propagation_tracking` ADD INDEX IF NOT EXISTS `idx_completion_status` (`completion_status`);
ALTER TABLE `lupo_cip_propagation_tracking` ADD INDEX IF NOT EXISTS `idx_event_level` (`cip_event_id`, `propagation_level`);
ALTER TABLE `lupo_cip_propagation_tracking` ADD INDEX IF NOT EXISTS `idx_propagation_strength` (`propagation_strength`);
ALTER TABLE `lupo_cip_propagation_tracking` ADD INDEX IF NOT EXISTS `idx_subsystem` (`affected_subsystem`);

CREATE TABLE IF NOT EXISTS `lupo_cip_trends` (
  `id` bigint unsigned NOT NULL auto_increment,
  `trend_period` enum('hourly','daily','weekly','monthly') NOT NULL,
  `period_start_ymdhis` bigint NOT NULL COMMENT 'Start of aggregation period',
  `period_end_ymdhis` bigint NOT NULL COMMENT 'End of aggregation period',
  `avg_defensiveness_index` decimal(5,4) NOT NULL DEFAULT 0.0000,
  `avg_integration_velocity` decimal(5,4) NOT NULL DEFAULT 0.0000,
  `avg_architectural_impact` decimal(5,4) NOT NULL DEFAULT 0.0000,
  `total_events` int unsigned NOT NULL DEFAULT 0,
  `high_impact_events` int unsigned NOT NULL DEFAULT 0 COMMENT 'AIS > 0.7000',
  `doctrine_updates_triggered` int unsigned NOT NULL DEFAULT 0,
  `trend_metadata_json` json COMMENT 'Additional trend analysis',
  `calculated_ymdhis` bigint NOT NULL COMMENT 'When trend was calculated',
  PRIMARY KEY (`id`),
  KEY `idx_high_impact` (`high_impact_events`),
  KEY `idx_period_range` (`period_start_ymdhis`, `period_end_ymdhis`),
  UNIQUE KEY `uk_period_trend` (`trend_period`, `period_start_ymdhis`)
);

ALTER TABLE `lupo_cip_trends` ADD COLUMN IF NOT EXISTS `id` bigint unsigned NOT NULL auto_increment;
ALTER TABLE `lupo_cip_trends` ADD COLUMN IF NOT EXISTS `trend_period` enum('hourly','daily','weekly','monthly') NOT NULL;
ALTER TABLE `lupo_cip_trends` ADD COLUMN IF NOT EXISTS `period_start_ymdhis` bigint NOT NULL COMMENT 'Start of aggregation period';
ALTER TABLE `lupo_cip_trends` ADD COLUMN IF NOT EXISTS `period_end_ymdhis` bigint NOT NULL COMMENT 'End of aggregation period';
ALTER TABLE `lupo_cip_trends` ADD COLUMN IF NOT EXISTS `avg_defensiveness_index` decimal(5,4) NOT NULL DEFAULT 0.0000;
ALTER TABLE `lupo_cip_trends` ADD COLUMN IF NOT EXISTS `avg_integration_velocity` decimal(5,4) NOT NULL DEFAULT 0.0000;
ALTER TABLE `lupo_cip_trends` ADD COLUMN IF NOT EXISTS `avg_architectural_impact` decimal(5,4) NOT NULL DEFAULT 0.0000;
ALTER TABLE `lupo_cip_trends` ADD COLUMN IF NOT EXISTS `total_events` int unsigned NOT NULL DEFAULT 0;
ALTER TABLE `lupo_cip_trends` ADD COLUMN IF NOT EXISTS `high_impact_events` int unsigned NOT NULL DEFAULT 0 COMMENT 'AIS > 0.7000';
ALTER TABLE `lupo_cip_trends` ADD COLUMN IF NOT EXISTS `doctrine_updates_triggered` int unsigned NOT NULL DEFAULT 0;
ALTER TABLE `lupo_cip_trends` ADD COLUMN IF NOT EXISTS `trend_metadata_json` json COMMENT 'Additional trend analysis';
ALTER TABLE `lupo_cip_trends` ADD COLUMN IF NOT EXISTS `calculated_ymdhis` bigint NOT NULL COMMENT 'When trend was calculated';
ALTER TABLE `lupo_cip_trends` MODIFY COLUMN `id` bigint unsigned NOT NULL auto_increment;
ALTER TABLE `lupo_cip_trends` MODIFY COLUMN `trend_period` enum('hourly','daily','weekly','monthly') NOT NULL;
ALTER TABLE `lupo_cip_trends` MODIFY COLUMN `period_start_ymdhis` bigint NOT NULL COMMENT 'Start of aggregation period';
ALTER TABLE `lupo_cip_trends` MODIFY COLUMN `period_end_ymdhis` bigint NOT NULL COMMENT 'End of aggregation period';
ALTER TABLE `lupo_cip_trends` MODIFY COLUMN `avg_defensiveness_index` decimal(5,4) NOT NULL DEFAULT 0.0000;
ALTER TABLE `lupo_cip_trends` MODIFY COLUMN `avg_integration_velocity` decimal(5,4) NOT NULL DEFAULT 0.0000;
ALTER TABLE `lupo_cip_trends` MODIFY COLUMN `avg_architectural_impact` decimal(5,4) NOT NULL DEFAULT 0.0000;
ALTER TABLE `lupo_cip_trends` MODIFY COLUMN `total_events` int unsigned NOT NULL DEFAULT 0;
ALTER TABLE `lupo_cip_trends` MODIFY COLUMN `high_impact_events` int unsigned NOT NULL DEFAULT 0 COMMENT 'AIS > 0.7000';
ALTER TABLE `lupo_cip_trends` MODIFY COLUMN `doctrine_updates_triggered` int unsigned NOT NULL DEFAULT 0;
ALTER TABLE `lupo_cip_trends` MODIFY COLUMN `trend_metadata_json` json COMMENT 'Additional trend analysis';
ALTER TABLE `lupo_cip_trends` MODIFY COLUMN `calculated_ymdhis` bigint NOT NULL COMMENT 'When trend was calculated';
ALTER TABLE `lupo_cip_trends` ADD PRIMARY KEY (`id`);
ALTER TABLE `lupo_cip_trends` ADD INDEX IF NOT EXISTS `idx_high_impact` (`high_impact_events`);
ALTER TABLE `lupo_cip_trends` ADD INDEX IF NOT EXISTS `idx_period_range` (`period_start_ymdhis`, `period_end_ymdhis`);
ALTER TABLE `lupo_cip_trends` ADD UNIQUE INDEX IF NOT EXISTS `uk_period_trend` (`trend_period`, `period_start_ymdhis`);

CREATE TABLE IF NOT EXISTS `lupo_collection_tab_map` (
  `collection_tab_map_id` bigint NOT NULL auto_increment,
  `collection_tab_id` bigint NOT NULL COMMENT 'Reference to the parent tab',
  `federations_node_id` bigint NOT NULL COMMENT 'Domain this mapping belongs to',
  `item_type` varchar(20) NOT NULL COMMENT 'Type of mapped item (content, tab, link, etc.)',
  `item_id` bigint NOT NULL COMMENT 'ID of the mapped item',
  `sort_order` int DEFAULT 0 COMMENT 'Display order within the tab',
  `properties` text COMMENT 'JSON-encoded key-value store for additional data',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted',
  PRIMARY KEY (`collection_tab_map_id`),
  KEY `idx_collection_tab` (`collection_tab_id`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_domain` (`federations_node_id`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_item` (`item_type`, `item_id`),
  KEY `idx_sort_order` (`sort_order`),
  KEY `idx_updated_ymdhis` (`updated_ymdhis`),
  UNIQUE KEY `unique_item_in_tab` (`collection_tab_id`, `item_type`, `item_id`)
);

ALTER TABLE `lupo_collection_tab_map` ADD COLUMN IF NOT EXISTS `collection_tab_map_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_collection_tab_map` ADD COLUMN IF NOT EXISTS `collection_tab_id` bigint NOT NULL COMMENT 'Reference to the parent tab';
ALTER TABLE `lupo_collection_tab_map` ADD COLUMN IF NOT EXISTS `federations_node_id` bigint NOT NULL COMMENT 'Domain this mapping belongs to';
ALTER TABLE `lupo_collection_tab_map` ADD COLUMN IF NOT EXISTS `item_type` varchar(20) NOT NULL COMMENT 'Type of mapped item (content, tab, link, etc.)';
ALTER TABLE `lupo_collection_tab_map` ADD COLUMN IF NOT EXISTS `item_id` bigint NOT NULL COMMENT 'ID of the mapped item';
ALTER TABLE `lupo_collection_tab_map` ADD COLUMN IF NOT EXISTS `sort_order` int DEFAULT 0 COMMENT 'Display order within the tab';
ALTER TABLE `lupo_collection_tab_map` ADD COLUMN IF NOT EXISTS `properties` text COMMENT 'JSON-encoded key-value store for additional data';
ALTER TABLE `lupo_collection_tab_map` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created';
ALTER TABLE `lupo_collection_tab_map` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated';
ALTER TABLE `lupo_collection_tab_map` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted';
ALTER TABLE `lupo_collection_tab_map` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted';
ALTER TABLE `lupo_collection_tab_map` MODIFY COLUMN `collection_tab_map_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_collection_tab_map` MODIFY COLUMN `collection_tab_id` bigint NOT NULL COMMENT 'Reference to the parent tab';
ALTER TABLE `lupo_collection_tab_map` MODIFY COLUMN `federations_node_id` bigint NOT NULL COMMENT 'Domain this mapping belongs to';
ALTER TABLE `lupo_collection_tab_map` MODIFY COLUMN `item_type` varchar(20) NOT NULL COMMENT 'Type of mapped item (content, tab, link, etc.)';
ALTER TABLE `lupo_collection_tab_map` MODIFY COLUMN `item_id` bigint NOT NULL COMMENT 'ID of the mapped item';
ALTER TABLE `lupo_collection_tab_map` MODIFY COLUMN `sort_order` int DEFAULT 0 COMMENT 'Display order within the tab';
ALTER TABLE `lupo_collection_tab_map` MODIFY COLUMN `properties` text COMMENT 'JSON-encoded key-value store for additional data';
ALTER TABLE `lupo_collection_tab_map` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created';
ALTER TABLE `lupo_collection_tab_map` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated';
ALTER TABLE `lupo_collection_tab_map` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted';
ALTER TABLE `lupo_collection_tab_map` MODIFY COLUMN `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted';
ALTER TABLE `lupo_collection_tab_map` ADD PRIMARY KEY (`collection_tab_map_id`);
ALTER TABLE `lupo_collection_tab_map` ADD INDEX IF NOT EXISTS `idx_collection_tab` (`collection_tab_id`);
ALTER TABLE `lupo_collection_tab_map` ADD INDEX IF NOT EXISTS `idx_created_ymdhis` (`created_ymdhis`);
ALTER TABLE `lupo_collection_tab_map` ADD INDEX IF NOT EXISTS `idx_domain` (`federations_node_id`);
ALTER TABLE `lupo_collection_tab_map` ADD INDEX IF NOT EXISTS `idx_is_deleted` (`is_deleted`);
ALTER TABLE `lupo_collection_tab_map` ADD INDEX IF NOT EXISTS `idx_item` (`item_type`, `item_id`);
ALTER TABLE `lupo_collection_tab_map` ADD INDEX IF NOT EXISTS `idx_sort_order` (`sort_order`);
ALTER TABLE `lupo_collection_tab_map` ADD INDEX IF NOT EXISTS `idx_updated_ymdhis` (`updated_ymdhis`);
ALTER TABLE `lupo_collection_tab_map` ADD UNIQUE INDEX IF NOT EXISTS `unique_item_in_tab` (`collection_tab_id`, `item_type`, `item_id`);

CREATE TABLE IF NOT EXISTS `lupo_collection_tab_paths` (
  `collection_tab_path_id` bigint NOT NULL auto_increment,
  `collection_id` bigint NOT NULL,
  `collection_tab_id` bigint NOT NULL,
  `path` varchar(500) NOT NULL COMMENT 'Full tab path: departments/parks-and-recreation/summer-programs',
  `depth` int NOT NULL COMMENT 'Number of levels (1 = root tab)',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS',
  `updated_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS',
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (`collection_tab_path_id`),
  KEY `idx_collection` (`collection_id`),
  KEY `idx_collection_tab` (`collection_tab_id`),
  KEY `idx_path` (`path`),
  UNIQUE KEY `unique_tab_path` (`collection_id`, `collection_tab_id`, `path`)
);

ALTER TABLE `lupo_collection_tab_paths` ADD COLUMN IF NOT EXISTS `collection_tab_path_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_collection_tab_paths` ADD COLUMN IF NOT EXISTS `collection_id` bigint NOT NULL;
ALTER TABLE `lupo_collection_tab_paths` ADD COLUMN IF NOT EXISTS `collection_tab_id` bigint NOT NULL;
ALTER TABLE `lupo_collection_tab_paths` ADD COLUMN IF NOT EXISTS `path` varchar(500) NOT NULL COMMENT 'Full tab path: departments/parks-and-recreation/summer-programs';
ALTER TABLE `lupo_collection_tab_paths` ADD COLUMN IF NOT EXISTS `depth` int NOT NULL COMMENT 'Number of levels (1 = root tab)';
ALTER TABLE `lupo_collection_tab_paths` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS';
ALTER TABLE `lupo_collection_tab_paths` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS';
ALTER TABLE `lupo_collection_tab_paths` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_collection_tab_paths` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint;
ALTER TABLE `lupo_collection_tab_paths` MODIFY COLUMN `collection_tab_path_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_collection_tab_paths` MODIFY COLUMN `collection_id` bigint NOT NULL;
ALTER TABLE `lupo_collection_tab_paths` MODIFY COLUMN `collection_tab_id` bigint NOT NULL;
ALTER TABLE `lupo_collection_tab_paths` MODIFY COLUMN `path` varchar(500) NOT NULL COMMENT 'Full tab path: departments/parks-and-recreation/summer-programs';
ALTER TABLE `lupo_collection_tab_paths` MODIFY COLUMN `depth` int NOT NULL COMMENT 'Number of levels (1 = root tab)';
ALTER TABLE `lupo_collection_tab_paths` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS';
ALTER TABLE `lupo_collection_tab_paths` MODIFY COLUMN `updated_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS';
ALTER TABLE `lupo_collection_tab_paths` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_collection_tab_paths` MODIFY COLUMN `deleted_ymdhis` bigint;
ALTER TABLE `lupo_collection_tab_paths` ADD PRIMARY KEY (`collection_tab_path_id`);
ALTER TABLE `lupo_collection_tab_paths` ADD INDEX IF NOT EXISTS `idx_collection` (`collection_id`);
ALTER TABLE `lupo_collection_tab_paths` ADD INDEX IF NOT EXISTS `idx_collection_tab` (`collection_tab_id`);
ALTER TABLE `lupo_collection_tab_paths` ADD INDEX IF NOT EXISTS `idx_path` (`path`);
ALTER TABLE `lupo_collection_tab_paths` ADD UNIQUE INDEX IF NOT EXISTS `unique_tab_path` (`collection_id`, `collection_tab_id`, `path`);

CREATE TABLE IF NOT EXISTS `lupo_collection_tabs` (
  `collection_tab_id` bigint NOT NULL auto_increment,
  `collection_tab_parent_id` bigint COMMENT 'Parent tab ID for hierarchical nesting, NULL for root level',
  `collection_id` bigint NOT NULL COMMENT 'Reference to the parent collection',
  `federations_node_id` bigint NOT NULL COMMENT 'Domain this tab belongs to (via collection)',
  `group_id` bigint COMMENT 'Owning group, if group-owned',
  `user_id` bigint COMMENT 'Owning user, if user-owned',
  `sort_order` int DEFAULT 0 COMMENT 'Order of display within parent container',
  `name` varchar(255) NOT NULL COMMENT 'Display name of the tab',
  `slug` varchar(100) NOT NULL COMMENT 'URL-friendly identifier, unique within collection',
  `color` char(6) DEFAULT '4caf50' COMMENT 'Hex color code (6 characters, no hash)',
  `description` text COMMENT 'Optional description of the tab',
  `is_hidden` tinyint NOT NULL DEFAULT 0 COMMENT '1 = hidden, 0 = visible',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated',
  `is_active` tinyint NOT NULL DEFAULT 1 COMMENT '1 = active, 0 = not active',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = soft deleted, 0 = not deleted',
  `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted',
  PRIMARY KEY (`collection_tab_id`),
  KEY `idx_collection_id` (`collection_id`),
  KEY `idx_is_active` (`is_active`),
  KEY `idx_parent_tab_id` (`collection_tab_parent_id`),
  KEY `idx_slug` (`slug`)
);

ALTER TABLE `lupo_collection_tabs` ADD COLUMN IF NOT EXISTS `collection_tab_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_collection_tabs` ADD COLUMN IF NOT EXISTS `collection_tab_parent_id` bigint COMMENT 'Parent tab ID for hierarchical nesting, NULL for root level';
ALTER TABLE `lupo_collection_tabs` ADD COLUMN IF NOT EXISTS `collection_id` bigint NOT NULL COMMENT 'Reference to the parent collection';
ALTER TABLE `lupo_collection_tabs` ADD COLUMN IF NOT EXISTS `federations_node_id` bigint NOT NULL COMMENT 'Domain this tab belongs to (via collection)';
ALTER TABLE `lupo_collection_tabs` ADD COLUMN IF NOT EXISTS `group_id` bigint COMMENT 'Owning group, if group-owned';
ALTER TABLE `lupo_collection_tabs` ADD COLUMN IF NOT EXISTS `user_id` bigint COMMENT 'Owning user, if user-owned';
ALTER TABLE `lupo_collection_tabs` ADD COLUMN IF NOT EXISTS `sort_order` int DEFAULT 0 COMMENT 'Order of display within parent container';
ALTER TABLE `lupo_collection_tabs` ADD COLUMN IF NOT EXISTS `name` varchar(255) NOT NULL COMMENT 'Display name of the tab';
ALTER TABLE `lupo_collection_tabs` ADD COLUMN IF NOT EXISTS `slug` varchar(100) NOT NULL COMMENT 'URL-friendly identifier, unique within collection';
ALTER TABLE `lupo_collection_tabs` ADD COLUMN IF NOT EXISTS `color` char(6) DEFAULT '4caf50' COMMENT 'Hex color code (6 characters, no hash)';
ALTER TABLE `lupo_collection_tabs` ADD COLUMN IF NOT EXISTS `description` text COMMENT 'Optional description of the tab';
ALTER TABLE `lupo_collection_tabs` ADD COLUMN IF NOT EXISTS `is_hidden` tinyint NOT NULL DEFAULT 0 COMMENT '1 = hidden, 0 = visible';
ALTER TABLE `lupo_collection_tabs` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created';
ALTER TABLE `lupo_collection_tabs` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated';
ALTER TABLE `lupo_collection_tabs` ADD COLUMN IF NOT EXISTS `is_active` tinyint NOT NULL DEFAULT 1 COMMENT '1 = active, 0 = not active';
ALTER TABLE `lupo_collection_tabs` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = soft deleted, 0 = not deleted';
ALTER TABLE `lupo_collection_tabs` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted';
ALTER TABLE `lupo_collection_tabs` MODIFY COLUMN `collection_tab_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_collection_tabs` MODIFY COLUMN `collection_tab_parent_id` bigint COMMENT 'Parent tab ID for hierarchical nesting, NULL for root level';
ALTER TABLE `lupo_collection_tabs` MODIFY COLUMN `collection_id` bigint NOT NULL COMMENT 'Reference to the parent collection';
ALTER TABLE `lupo_collection_tabs` MODIFY COLUMN `federations_node_id` bigint NOT NULL COMMENT 'Domain this tab belongs to (via collection)';
ALTER TABLE `lupo_collection_tabs` MODIFY COLUMN `group_id` bigint COMMENT 'Owning group, if group-owned';
ALTER TABLE `lupo_collection_tabs` MODIFY COLUMN `user_id` bigint COMMENT 'Owning user, if user-owned';
ALTER TABLE `lupo_collection_tabs` MODIFY COLUMN `sort_order` int DEFAULT 0 COMMENT 'Order of display within parent container';
ALTER TABLE `lupo_collection_tabs` MODIFY COLUMN `name` varchar(255) NOT NULL COMMENT 'Display name of the tab';
ALTER TABLE `lupo_collection_tabs` MODIFY COLUMN `slug` varchar(100) NOT NULL COMMENT 'URL-friendly identifier, unique within collection';
ALTER TABLE `lupo_collection_tabs` MODIFY COLUMN `color` char(6) DEFAULT '4caf50' COMMENT 'Hex color code (6 characters, no hash)';
ALTER TABLE `lupo_collection_tabs` MODIFY COLUMN `description` text COMMENT 'Optional description of the tab';
ALTER TABLE `lupo_collection_tabs` MODIFY COLUMN `is_hidden` tinyint NOT NULL DEFAULT 0 COMMENT '1 = hidden, 0 = visible';
ALTER TABLE `lupo_collection_tabs` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created';
ALTER TABLE `lupo_collection_tabs` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated';
ALTER TABLE `lupo_collection_tabs` MODIFY COLUMN `is_active` tinyint NOT NULL DEFAULT 1 COMMENT '1 = active, 0 = not active';
ALTER TABLE `lupo_collection_tabs` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = soft deleted, 0 = not deleted';
ALTER TABLE `lupo_collection_tabs` MODIFY COLUMN `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted';
ALTER TABLE `lupo_collection_tabs` ADD PRIMARY KEY (`collection_tab_id`);
ALTER TABLE `lupo_collection_tabs` ADD INDEX IF NOT EXISTS `idx_collection_id` (`collection_id`);
ALTER TABLE `lupo_collection_tabs` ADD INDEX IF NOT EXISTS `idx_is_active` (`is_active`);
ALTER TABLE `lupo_collection_tabs` ADD INDEX IF NOT EXISTS `idx_parent_tab_id` (`collection_tab_parent_id`);
ALTER TABLE `lupo_collection_tabs` ADD INDEX IF NOT EXISTS `idx_slug` (`slug`);

CREATE TABLE IF NOT EXISTS `lupo_collections` (
  `collection_id` bigint NOT NULL auto_increment COMMENT 'Primary key for collection',
  `federations_node_id` bigint NOT NULL COMMENT 'Domain this collection belongs to',
  `user_id` bigint COMMENT 'Owner of this collection, if user-owned',
  `group_id` bigint COMMENT 'Owning group, if group-owned',
  `name` varchar(255) NOT NULL COMMENT 'Display name of the collection',
  `slug` varchar(100) NOT NULL COMMENT 'URL-friendly identifier, unique per domain',
  `color` char(6) DEFAULT '666666' COMMENT 'Hex color code for the collection (6 hex characters, no hash)',
  `description` text COMMENT 'Optional description of the collection',
  `sort_order` int DEFAULT 0 COMMENT 'Manual sort order within parent container',
  `properties` text COMMENT 'JSON-encoded key-value store',
  `published_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when published',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted',
  `parent_id` bigint,
  PRIMARY KEY (`collection_id`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_domain` (`federations_node_id`),
  KEY `idx_group` (`group_id`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_name` (`name`),
  KEY `idx_sort_order` (`sort_order`),
  KEY `idx_updated_ymdhis` (`updated_ymdhis`),
  KEY `idx_user` (`user_id`),
  UNIQUE KEY `unique_collection_slug_domain` (`federations_node_id`, `slug`)
);

ALTER TABLE `lupo_collections` ADD COLUMN IF NOT EXISTS `collection_id` bigint NOT NULL auto_increment COMMENT 'Primary key for collection';
ALTER TABLE `lupo_collections` ADD COLUMN IF NOT EXISTS `federations_node_id` bigint NOT NULL COMMENT 'Domain this collection belongs to';
ALTER TABLE `lupo_collections` ADD COLUMN IF NOT EXISTS `user_id` bigint COMMENT 'Owner of this collection, if user-owned';
ALTER TABLE `lupo_collections` ADD COLUMN IF NOT EXISTS `group_id` bigint COMMENT 'Owning group, if group-owned';
ALTER TABLE `lupo_collections` ADD COLUMN IF NOT EXISTS `name` varchar(255) NOT NULL COMMENT 'Display name of the collection';
ALTER TABLE `lupo_collections` ADD COLUMN IF NOT EXISTS `slug` varchar(100) NOT NULL COMMENT 'URL-friendly identifier, unique per domain';
ALTER TABLE `lupo_collections` ADD COLUMN IF NOT EXISTS `color` char(6) DEFAULT '666666' COMMENT 'Hex color code for the collection (6 hex characters, no hash)';
ALTER TABLE `lupo_collections` ADD COLUMN IF NOT EXISTS `description` text COMMENT 'Optional description of the collection';
ALTER TABLE `lupo_collections` ADD COLUMN IF NOT EXISTS `sort_order` int DEFAULT 0 COMMENT 'Manual sort order within parent container';
ALTER TABLE `lupo_collections` ADD COLUMN IF NOT EXISTS `properties` text COMMENT 'JSON-encoded key-value store';
ALTER TABLE `lupo_collections` ADD COLUMN IF NOT EXISTS `published_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when published';
ALTER TABLE `lupo_collections` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created';
ALTER TABLE `lupo_collections` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated';
ALTER TABLE `lupo_collections` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted';
ALTER TABLE `lupo_collections` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted';
ALTER TABLE `lupo_collections` ADD COLUMN IF NOT EXISTS `parent_id` bigint;
ALTER TABLE `lupo_collections` MODIFY COLUMN `collection_id` bigint NOT NULL auto_increment COMMENT 'Primary key for collection';
ALTER TABLE `lupo_collections` MODIFY COLUMN `federations_node_id` bigint NOT NULL COMMENT 'Domain this collection belongs to';
ALTER TABLE `lupo_collections` MODIFY COLUMN `user_id` bigint COMMENT 'Owner of this collection, if user-owned';
ALTER TABLE `lupo_collections` MODIFY COLUMN `group_id` bigint COMMENT 'Owning group, if group-owned';
ALTER TABLE `lupo_collections` MODIFY COLUMN `name` varchar(255) NOT NULL COMMENT 'Display name of the collection';
ALTER TABLE `lupo_collections` MODIFY COLUMN `slug` varchar(100) NOT NULL COMMENT 'URL-friendly identifier, unique per domain';
ALTER TABLE `lupo_collections` MODIFY COLUMN `color` char(6) DEFAULT '666666' COMMENT 'Hex color code for the collection (6 hex characters, no hash)';
ALTER TABLE `lupo_collections` MODIFY COLUMN `description` text COMMENT 'Optional description of the collection';
ALTER TABLE `lupo_collections` MODIFY COLUMN `sort_order` int DEFAULT 0 COMMENT 'Manual sort order within parent container';
ALTER TABLE `lupo_collections` MODIFY COLUMN `properties` text COMMENT 'JSON-encoded key-value store';
ALTER TABLE `lupo_collections` MODIFY COLUMN `published_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when published';
ALTER TABLE `lupo_collections` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created';
ALTER TABLE `lupo_collections` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated';
ALTER TABLE `lupo_collections` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted';
ALTER TABLE `lupo_collections` MODIFY COLUMN `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted';
ALTER TABLE `lupo_collections` MODIFY COLUMN `parent_id` bigint;
ALTER TABLE `lupo_collections` ADD PRIMARY KEY (`collection_id`);
ALTER TABLE `lupo_collections` ADD INDEX IF NOT EXISTS `idx_created_ymdhis` (`created_ymdhis`);
ALTER TABLE `lupo_collections` ADD INDEX IF NOT EXISTS `idx_domain` (`federations_node_id`);
ALTER TABLE `lupo_collections` ADD INDEX IF NOT EXISTS `idx_group` (`group_id`);
ALTER TABLE `lupo_collections` ADD INDEX IF NOT EXISTS `idx_is_deleted` (`is_deleted`);
ALTER TABLE `lupo_collections` ADD INDEX IF NOT EXISTS `idx_name` (`name`);
ALTER TABLE `lupo_collections` ADD INDEX IF NOT EXISTS `idx_sort_order` (`sort_order`);
ALTER TABLE `lupo_collections` ADD INDEX IF NOT EXISTS `idx_updated_ymdhis` (`updated_ymdhis`);
ALTER TABLE `lupo_collections` ADD INDEX IF NOT EXISTS `idx_user` (`user_id`);
ALTER TABLE `lupo_collections` ADD UNIQUE INDEX IF NOT EXISTS `unique_collection_slug_domain` (`federations_node_id`, `slug`);

CREATE TABLE IF NOT EXISTS `lupo_content_atom_map` (
  `content_atom_map_id` bigint NOT NULL auto_increment,
  `content_id` bigint NOT NULL COMMENT 'Content item using the atom',
  `atom_id` bigint NOT NULL COMMENT 'Semantic atom referenced by the content',
  `purpose` varchar(255) COMMENT 'Why this atom is attached (topic, tag, variable, etc.)',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted',
  PRIMARY KEY (`content_atom_map_id`),
  KEY `idx_atom` (`atom_id`),
  KEY `idx_content` (`content_id`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_purpose` (`purpose`),
  KEY `idx_updated_ymdhis` (`updated_ymdhis`),
  UNIQUE KEY `unique_atom_content` (`content_id`, `atom_id`)
);

ALTER TABLE `lupo_content_atom_map` ADD COLUMN IF NOT EXISTS `content_atom_map_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_content_atom_map` ADD COLUMN IF NOT EXISTS `content_id` bigint NOT NULL COMMENT 'Content item using the atom';
ALTER TABLE `lupo_content_atom_map` ADD COLUMN IF NOT EXISTS `atom_id` bigint NOT NULL COMMENT 'Semantic atom referenced by the content';
ALTER TABLE `lupo_content_atom_map` ADD COLUMN IF NOT EXISTS `purpose` varchar(255) COMMENT 'Why this atom is attached (topic, tag, variable, etc.)';
ALTER TABLE `lupo_content_atom_map` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created';
ALTER TABLE `lupo_content_atom_map` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated';
ALTER TABLE `lupo_content_atom_map` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted';
ALTER TABLE `lupo_content_atom_map` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted';
ALTER TABLE `lupo_content_atom_map` MODIFY COLUMN `content_atom_map_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_content_atom_map` MODIFY COLUMN `content_id` bigint NOT NULL COMMENT 'Content item using the atom';
ALTER TABLE `lupo_content_atom_map` MODIFY COLUMN `atom_id` bigint NOT NULL COMMENT 'Semantic atom referenced by the content';
ALTER TABLE `lupo_content_atom_map` MODIFY COLUMN `purpose` varchar(255) COMMENT 'Why this atom is attached (topic, tag, variable, etc.)';
ALTER TABLE `lupo_content_atom_map` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created';
ALTER TABLE `lupo_content_atom_map` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated';
ALTER TABLE `lupo_content_atom_map` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted';
ALTER TABLE `lupo_content_atom_map` MODIFY COLUMN `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted';
ALTER TABLE `lupo_content_atom_map` ADD PRIMARY KEY (`content_atom_map_id`);
ALTER TABLE `lupo_content_atom_map` ADD INDEX IF NOT EXISTS `idx_atom` (`atom_id`);
ALTER TABLE `lupo_content_atom_map` ADD INDEX IF NOT EXISTS `idx_content` (`content_id`);
ALTER TABLE `lupo_content_atom_map` ADD INDEX IF NOT EXISTS `idx_created_ymdhis` (`created_ymdhis`);
ALTER TABLE `lupo_content_atom_map` ADD INDEX IF NOT EXISTS `idx_is_deleted` (`is_deleted`);
ALTER TABLE `lupo_content_atom_map` ADD INDEX IF NOT EXISTS `idx_purpose` (`purpose`);
ALTER TABLE `lupo_content_atom_map` ADD INDEX IF NOT EXISTS `idx_updated_ymdhis` (`updated_ymdhis`);
ALTER TABLE `lupo_content_atom_map` ADD UNIQUE INDEX IF NOT EXISTS `unique_atom_content` (`content_id`, `atom_id`);

CREATE TABLE IF NOT EXISTS `lupo_content_category_map` (
  `content_category_map_id` bigint NOT NULL auto_increment COMMENT 'Primary key for content-category mapping',
  `content_id` bigint NOT NULL COMMENT 'Content being categorized',
  `category_id` bigint NOT NULL COMMENT 'Category assigned to this content',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when mapping was created',
  PRIMARY KEY (`content_category_map_id`),
  KEY `idx_category` (`category_id`),
  KEY `idx_content` (`content_id`),
  UNIQUE KEY `uq_content_category` (`content_id`, `category_id`)
);

ALTER TABLE `lupo_content_category_map` ADD COLUMN IF NOT EXISTS `content_category_map_id` bigint NOT NULL auto_increment COMMENT 'Primary key for content-category mapping';
ALTER TABLE `lupo_content_category_map` ADD COLUMN IF NOT EXISTS `content_id` bigint NOT NULL COMMENT 'Content being categorized';
ALTER TABLE `lupo_content_category_map` ADD COLUMN IF NOT EXISTS `category_id` bigint NOT NULL COMMENT 'Category assigned to this content';
ALTER TABLE `lupo_content_category_map` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when mapping was created';
ALTER TABLE `lupo_content_category_map` MODIFY COLUMN `content_category_map_id` bigint NOT NULL auto_increment COMMENT 'Primary key for content-category mapping';
ALTER TABLE `lupo_content_category_map` MODIFY COLUMN `content_id` bigint NOT NULL COMMENT 'Content being categorized';
ALTER TABLE `lupo_content_category_map` MODIFY COLUMN `category_id` bigint NOT NULL COMMENT 'Category assigned to this content';
ALTER TABLE `lupo_content_category_map` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when mapping was created';
ALTER TABLE `lupo_content_category_map` ADD PRIMARY KEY (`content_category_map_id`);
ALTER TABLE `lupo_content_category_map` ADD INDEX IF NOT EXISTS `idx_category` (`category_id`);
ALTER TABLE `lupo_content_category_map` ADD INDEX IF NOT EXISTS `idx_content` (`content_id`);
ALTER TABLE `lupo_content_category_map` ADD UNIQUE INDEX IF NOT EXISTS `uq_content_category` (`content_id`, `category_id`);

CREATE TABLE IF NOT EXISTS `lupo_content_engagement_summary` (
  `content_engagement_summary_id` bigint NOT NULL auto_increment COMMENT 'Reference to the content item',
  `likes_total` int NOT NULL DEFAULT 0 COMMENT 'Total number of likes received',
  `shares_total` int NOT NULL DEFAULT 0 COMMENT 'Total number of shares',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when the summary was last updated',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when the summary was first created',
  PRIMARY KEY (`content_engagement_summary_id`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_likes_total` (`likes_total`),
  KEY `idx_shares_total` (`shares_total`),
  KEY `idx_updated_ymdhis` (`updated_ymdhis`)
);

ALTER TABLE `lupo_content_engagement_summary` ADD COLUMN IF NOT EXISTS `content_engagement_summary_id` bigint NOT NULL auto_increment COMMENT 'Reference to the content item';
ALTER TABLE `lupo_content_engagement_summary` ADD COLUMN IF NOT EXISTS `likes_total` int NOT NULL DEFAULT 0 COMMENT 'Total number of likes received';
ALTER TABLE `lupo_content_engagement_summary` ADD COLUMN IF NOT EXISTS `shares_total` int NOT NULL DEFAULT 0 COMMENT 'Total number of shares';
ALTER TABLE `lupo_content_engagement_summary` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when the summary was last updated';
ALTER TABLE `lupo_content_engagement_summary` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when the summary was first created';
ALTER TABLE `lupo_content_engagement_summary` MODIFY COLUMN `content_engagement_summary_id` bigint NOT NULL auto_increment COMMENT 'Reference to the content item';
ALTER TABLE `lupo_content_engagement_summary` MODIFY COLUMN `likes_total` int NOT NULL DEFAULT 0 COMMENT 'Total number of likes received';
ALTER TABLE `lupo_content_engagement_summary` MODIFY COLUMN `shares_total` int NOT NULL DEFAULT 0 COMMENT 'Total number of shares';
ALTER TABLE `lupo_content_engagement_summary` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when the summary was last updated';
ALTER TABLE `lupo_content_engagement_summary` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when the summary was first created';
ALTER TABLE `lupo_content_engagement_summary` ADD PRIMARY KEY (`content_engagement_summary_id`);
ALTER TABLE `lupo_content_engagement_summary` ADD INDEX IF NOT EXISTS `idx_created_ymdhis` (`created_ymdhis`);
ALTER TABLE `lupo_content_engagement_summary` ADD INDEX IF NOT EXISTS `idx_likes_total` (`likes_total`);
ALTER TABLE `lupo_content_engagement_summary` ADD INDEX IF NOT EXISTS `idx_shares_total` (`shares_total`);
ALTER TABLE `lupo_content_engagement_summary` ADD INDEX IF NOT EXISTS `idx_updated_ymdhis` (`updated_ymdhis`);

CREATE TABLE IF NOT EXISTS `lupo_content_events` (
  `content_event_id` bigint NOT NULL auto_increment COMMENT 'Primary key for content event',
  `content_id` bigint COMMENT 'Content identifier',
  `actor_id` bigint COMMENT 'Actor ID from lupo_actors',
  `session_id` varchar(255) COMMENT 'Session identifier',
  `tab_id` varchar(255) COMMENT 'Tab identifier',
  `world_id` bigint COMMENT 'World context ID',
  `world_key` varchar(255) COMMENT 'World context key',
  `world_type` varchar(50) COMMENT 'World context type',
  `event_type` varchar(100) NOT NULL COMMENT 'Type of content event',
  `event_data` json COMMENT 'Event-specific data',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS',
  PRIMARY KEY (`content_event_id`),
  KEY `idx_actor_id` (`actor_id`),
  KEY `idx_content_event_type` (`content_id`, `event_type`),
  KEY `idx_content_id` (`content_id`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_event_type` (`event_type`),
  KEY `idx_session_id` (`session_id`),
  KEY `idx_tab_id` (`tab_id`),
  KEY `idx_world_id` (`world_id`)
);

ALTER TABLE `lupo_content_events` ADD COLUMN IF NOT EXISTS `content_event_id` bigint NOT NULL auto_increment COMMENT 'Primary key for content event';
ALTER TABLE `lupo_content_events` ADD COLUMN IF NOT EXISTS `content_id` bigint COMMENT 'Content identifier';
ALTER TABLE `lupo_content_events` ADD COLUMN IF NOT EXISTS `actor_id` bigint COMMENT 'Actor ID from lupo_actors';
ALTER TABLE `lupo_content_events` ADD COLUMN IF NOT EXISTS `session_id` varchar(255) COMMENT 'Session identifier';
ALTER TABLE `lupo_content_events` ADD COLUMN IF NOT EXISTS `tab_id` varchar(255) COMMENT 'Tab identifier';
ALTER TABLE `lupo_content_events` ADD COLUMN IF NOT EXISTS `world_id` bigint COMMENT 'World context ID';
ALTER TABLE `lupo_content_events` ADD COLUMN IF NOT EXISTS `world_key` varchar(255) COMMENT 'World context key';
ALTER TABLE `lupo_content_events` ADD COLUMN IF NOT EXISTS `world_type` varchar(50) COMMENT 'World context type';
ALTER TABLE `lupo_content_events` ADD COLUMN IF NOT EXISTS `event_type` varchar(100) NOT NULL COMMENT 'Type of content event';
ALTER TABLE `lupo_content_events` ADD COLUMN IF NOT EXISTS `event_data` json COMMENT 'Event-specific data';
ALTER TABLE `lupo_content_events` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS';
ALTER TABLE `lupo_content_events` MODIFY COLUMN `content_event_id` bigint NOT NULL auto_increment COMMENT 'Primary key for content event';
ALTER TABLE `lupo_content_events` MODIFY COLUMN `content_id` bigint COMMENT 'Content identifier';
ALTER TABLE `lupo_content_events` MODIFY COLUMN `actor_id` bigint COMMENT 'Actor ID from lupo_actors';
ALTER TABLE `lupo_content_events` MODIFY COLUMN `session_id` varchar(255) COMMENT 'Session identifier';
ALTER TABLE `lupo_content_events` MODIFY COLUMN `tab_id` varchar(255) COMMENT 'Tab identifier';
ALTER TABLE `lupo_content_events` MODIFY COLUMN `world_id` bigint COMMENT 'World context ID';
ALTER TABLE `lupo_content_events` MODIFY COLUMN `world_key` varchar(255) COMMENT 'World context key';
ALTER TABLE `lupo_content_events` MODIFY COLUMN `world_type` varchar(50) COMMENT 'World context type';
ALTER TABLE `lupo_content_events` MODIFY COLUMN `event_type` varchar(100) NOT NULL COMMENT 'Type of content event';
ALTER TABLE `lupo_content_events` MODIFY COLUMN `event_data` json COMMENT 'Event-specific data';
ALTER TABLE `lupo_content_events` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS';
ALTER TABLE `lupo_content_events` ADD PRIMARY KEY (`content_event_id`);
ALTER TABLE `lupo_content_events` ADD INDEX IF NOT EXISTS `idx_actor_id` (`actor_id`);
ALTER TABLE `lupo_content_events` ADD INDEX IF NOT EXISTS `idx_content_event_type` (`content_id`, `event_type`);
ALTER TABLE `lupo_content_events` ADD INDEX IF NOT EXISTS `idx_content_id` (`content_id`);
ALTER TABLE `lupo_content_events` ADD INDEX IF NOT EXISTS `idx_created_ymdhis` (`created_ymdhis`);
ALTER TABLE `lupo_content_events` ADD INDEX IF NOT EXISTS `idx_event_type` (`event_type`);
ALTER TABLE `lupo_content_events` ADD INDEX IF NOT EXISTS `idx_session_id` (`session_id`);
ALTER TABLE `lupo_content_events` ADD INDEX IF NOT EXISTS `idx_tab_id` (`tab_id`);
ALTER TABLE `lupo_content_events` ADD INDEX IF NOT EXISTS `idx_world_id` (`world_id`);

CREATE TABLE IF NOT EXISTS `lupo_content_hashtag` (
  `content_hashtag_id` bigint NOT NULL auto_increment COMMENT 'Reference to the content item',
  `hashtag_id` bigint NOT NULL COMMENT 'Reference to the hashtag',
  `context_id` bigint NOT NULL COMMENT 'Context for this hashtag association',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when the association was created',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted',
  PRIMARY KEY (`content_hashtag_id`),
  KEY `idx_content` (`content_hashtag_id`),
  KEY `idx_context` (`context_id`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_hashtag` (`hashtag_id`),
  KEY `idx_is_deleted` (`is_deleted`)
);

ALTER TABLE `lupo_content_hashtag` ADD COLUMN IF NOT EXISTS `content_hashtag_id` bigint NOT NULL auto_increment COMMENT 'Reference to the content item';
ALTER TABLE `lupo_content_hashtag` ADD COLUMN IF NOT EXISTS `hashtag_id` bigint NOT NULL COMMENT 'Reference to the hashtag';
ALTER TABLE `lupo_content_hashtag` ADD COLUMN IF NOT EXISTS `context_id` bigint NOT NULL COMMENT 'Context for this hashtag association';
ALTER TABLE `lupo_content_hashtag` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when the association was created';
ALTER TABLE `lupo_content_hashtag` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted';
ALTER TABLE `lupo_content_hashtag` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted';
ALTER TABLE `lupo_content_hashtag` MODIFY COLUMN `content_hashtag_id` bigint NOT NULL auto_increment COMMENT 'Reference to the content item';
ALTER TABLE `lupo_content_hashtag` MODIFY COLUMN `hashtag_id` bigint NOT NULL COMMENT 'Reference to the hashtag';
ALTER TABLE `lupo_content_hashtag` MODIFY COLUMN `context_id` bigint NOT NULL COMMENT 'Context for this hashtag association';
ALTER TABLE `lupo_content_hashtag` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when the association was created';
ALTER TABLE `lupo_content_hashtag` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted';
ALTER TABLE `lupo_content_hashtag` MODIFY COLUMN `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted';
ALTER TABLE `lupo_content_hashtag` ADD PRIMARY KEY (`content_hashtag_id`);
ALTER TABLE `lupo_content_hashtag` ADD INDEX IF NOT EXISTS `idx_content` (`content_hashtag_id`);
ALTER TABLE `lupo_content_hashtag` ADD INDEX IF NOT EXISTS `idx_context` (`context_id`);
ALTER TABLE `lupo_content_hashtag` ADD INDEX IF NOT EXISTS `idx_created_ymdhis` (`created_ymdhis`);
ALTER TABLE `lupo_content_hashtag` ADD INDEX IF NOT EXISTS `idx_hashtag` (`hashtag_id`);
ALTER TABLE `lupo_content_hashtag` ADD INDEX IF NOT EXISTS `idx_is_deleted` (`is_deleted`);

CREATE TABLE IF NOT EXISTS `lupo_content_inbound_links` (
  `content_inbound_link_id` bigint NOT NULL auto_increment,
  `target_content_id` bigint NOT NULL COMMENT 'Content item being linked TO',
  `source_type` varchar(50) NOT NULL COMMENT 'content, reference, external, tab, atom, question, etc.',
  `source_id` bigint COMMENT 'ID of the source entity when applicable',
  `source_url` varchar(2000) COMMENT 'For external links',
  `link_type` varchar(255) COMMENT 'citation, embed, mention, related, etc.',
  `properties` json COMMENT 'JSON-encoded additional properties',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted',
  PRIMARY KEY (`content_inbound_link_id`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_source` (`source_type`, `source_id`),
  KEY `idx_target` (`target_content_id`),
  KEY `idx_updated_ymdhis` (`updated_ymdhis`),
  KEY `idx_url` (`source_url`)
);

ALTER TABLE `lupo_content_inbound_links` ADD COLUMN IF NOT EXISTS `content_inbound_link_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_content_inbound_links` ADD COLUMN IF NOT EXISTS `target_content_id` bigint NOT NULL COMMENT 'Content item being linked TO';
ALTER TABLE `lupo_content_inbound_links` ADD COLUMN IF NOT EXISTS `source_type` varchar(50) NOT NULL COMMENT 'content, reference, external, tab, atom, question, etc.';
ALTER TABLE `lupo_content_inbound_links` ADD COLUMN IF NOT EXISTS `source_id` bigint COMMENT 'ID of the source entity when applicable';
ALTER TABLE `lupo_content_inbound_links` ADD COLUMN IF NOT EXISTS `source_url` varchar(2000) COMMENT 'For external links';
ALTER TABLE `lupo_content_inbound_links` ADD COLUMN IF NOT EXISTS `link_type` varchar(255) COMMENT 'citation, embed, mention, related, etc.';
ALTER TABLE `lupo_content_inbound_links` ADD COLUMN IF NOT EXISTS `properties` json COMMENT 'JSON-encoded additional properties';
ALTER TABLE `lupo_content_inbound_links` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created';
ALTER TABLE `lupo_content_inbound_links` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated';
ALTER TABLE `lupo_content_inbound_links` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted';
ALTER TABLE `lupo_content_inbound_links` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted';
ALTER TABLE `lupo_content_inbound_links` MODIFY COLUMN `content_inbound_link_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_content_inbound_links` MODIFY COLUMN `target_content_id` bigint NOT NULL COMMENT 'Content item being linked TO';
ALTER TABLE `lupo_content_inbound_links` MODIFY COLUMN `source_type` varchar(50) NOT NULL COMMENT 'content, reference, external, tab, atom, question, etc.';
ALTER TABLE `lupo_content_inbound_links` MODIFY COLUMN `source_id` bigint COMMENT 'ID of the source entity when applicable';
ALTER TABLE `lupo_content_inbound_links` MODIFY COLUMN `source_url` varchar(2000) COMMENT 'For external links';
ALTER TABLE `lupo_content_inbound_links` MODIFY COLUMN `link_type` varchar(255) COMMENT 'citation, embed, mention, related, etc.';
ALTER TABLE `lupo_content_inbound_links` MODIFY COLUMN `properties` json COMMENT 'JSON-encoded additional properties';
ALTER TABLE `lupo_content_inbound_links` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created';
ALTER TABLE `lupo_content_inbound_links` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated';
ALTER TABLE `lupo_content_inbound_links` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted';
ALTER TABLE `lupo_content_inbound_links` MODIFY COLUMN `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted';
ALTER TABLE `lupo_content_inbound_links` ADD PRIMARY KEY (`content_inbound_link_id`);
ALTER TABLE `lupo_content_inbound_links` ADD INDEX IF NOT EXISTS `idx_created_ymdhis` (`created_ymdhis`);
ALTER TABLE `lupo_content_inbound_links` ADD INDEX IF NOT EXISTS `idx_is_deleted` (`is_deleted`);
ALTER TABLE `lupo_content_inbound_links` ADD INDEX IF NOT EXISTS `idx_source` (`source_type`, `source_id`);
ALTER TABLE `lupo_content_inbound_links` ADD INDEX IF NOT EXISTS `idx_target` (`target_content_id`);
ALTER TABLE `lupo_content_inbound_links` ADD INDEX IF NOT EXISTS `idx_updated_ymdhis` (`updated_ymdhis`);
ALTER TABLE `lupo_content_inbound_links` ADD INDEX IF NOT EXISTS `idx_url` (`source_url`);

CREATE TABLE IF NOT EXISTS `lupo_content_likes` (
  `content_like_id` bigint NOT NULL auto_increment,
  `content_id` bigint NOT NULL COMMENT 'ID of the content being liked',
  `user_id` bigint COMMENT 'ID of the user who liked (if authenticated)',
  `visitor_hash` char(64) COMMENT 'Hashed IP/session for anonymous likes',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted',
  PRIMARY KEY (`content_like_id`),
  KEY `idx_content` (`content_id`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_updated_ymdhis` (`updated_ymdhis`),
  KEY `idx_user` (`user_id`),
  UNIQUE KEY `unique_like_user` (`content_id`, `user_id`),
  UNIQUE KEY `unique_like_visitor` (`content_id`, `visitor_hash`)
);

ALTER TABLE `lupo_content_likes` ADD COLUMN IF NOT EXISTS `content_like_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_content_likes` ADD COLUMN IF NOT EXISTS `content_id` bigint NOT NULL COMMENT 'ID of the content being liked';
ALTER TABLE `lupo_content_likes` ADD COLUMN IF NOT EXISTS `user_id` bigint COMMENT 'ID of the user who liked (if authenticated)';
ALTER TABLE `lupo_content_likes` ADD COLUMN IF NOT EXISTS `visitor_hash` char(64) COMMENT 'Hashed IP/session for anonymous likes';
ALTER TABLE `lupo_content_likes` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created';
ALTER TABLE `lupo_content_likes` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated';
ALTER TABLE `lupo_content_likes` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted';
ALTER TABLE `lupo_content_likes` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted';
ALTER TABLE `lupo_content_likes` MODIFY COLUMN `content_like_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_content_likes` MODIFY COLUMN `content_id` bigint NOT NULL COMMENT 'ID of the content being liked';
ALTER TABLE `lupo_content_likes` MODIFY COLUMN `user_id` bigint COMMENT 'ID of the user who liked (if authenticated)';
ALTER TABLE `lupo_content_likes` MODIFY COLUMN `visitor_hash` char(64) COMMENT 'Hashed IP/session for anonymous likes';
ALTER TABLE `lupo_content_likes` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created';
ALTER TABLE `lupo_content_likes` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated';
ALTER TABLE `lupo_content_likes` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted';
ALTER TABLE `lupo_content_likes` MODIFY COLUMN `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted';
ALTER TABLE `lupo_content_likes` ADD PRIMARY KEY (`content_like_id`);
ALTER TABLE `lupo_content_likes` ADD INDEX IF NOT EXISTS `idx_content` (`content_id`);
ALTER TABLE `lupo_content_likes` ADD INDEX IF NOT EXISTS `idx_created_ymdhis` (`created_ymdhis`);
ALTER TABLE `lupo_content_likes` ADD INDEX IF NOT EXISTS `idx_is_deleted` (`is_deleted`);
ALTER TABLE `lupo_content_likes` ADD INDEX IF NOT EXISTS `idx_updated_ymdhis` (`updated_ymdhis`);
ALTER TABLE `lupo_content_likes` ADD INDEX IF NOT EXISTS `idx_user` (`user_id`);
ALTER TABLE `lupo_content_likes` ADD UNIQUE INDEX IF NOT EXISTS `unique_like_user` (`content_id`, `user_id`);
ALTER TABLE `lupo_content_likes` ADD UNIQUE INDEX IF NOT EXISTS `unique_like_visitor` (`content_id`, `visitor_hash`);

CREATE TABLE IF NOT EXISTS `lupo_content_media` (
  `content_media_id` bigint NOT NULL auto_increment,
  `content_id` bigint NOT NULL COMMENT 'Reference to the parent content item',
  `media_type` enum('image','audio','video','document','other') NOT NULL COMMENT 'Type of media: image, audio, video, document, or other',
  `original_filename` varchar(255) COMMENT 'Original filename as uploaded by user',
  `stored_filename` varchar(255) NOT NULL COMMENT 'Generated filename for storage (unique identifier)',
  `stored_path` varchar(512) NOT NULL COMMENT 'Relative path to the media file (without filename)',
  `file_extension` varchar(20) COMMENT 'File extension without leading dot',
  `mime_type` varchar(100) COMMENT 'MIME type of the media file',
  `file_size` bigint COMMENT 'File size in bytes',
  `dimensions` varchar(50) COMMENT 'Image/video dimensions (WxH)',
  `duration` int COMMENT 'Duration in seconds (audio/video)',
  `media_order` int NOT NULL DEFAULT 0 COMMENT 'Display order within the content item',
  `title` varchar(255) COMMENT 'Title of the media',
  `caption_text` text COMMENT 'Descriptive caption',
  `alt_text` text COMMENT 'Accessibility alternative text',
  `description` text COMMENT 'Detailed description',
  `metadata` json COMMENT 'JSON-encoded technical metadata (EXIF, codec info, etc.)',
  `variants` json COMMENT 'JSON-encoded information about generated variants (thumbnails, etc.)',
  `is_public` tinyint NOT NULL DEFAULT 1 COMMENT '1 = publicly accessible, 0 = private',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated',
  PRIMARY KEY (`content_media_id`),
  KEY `idx_content` (`content_id`),
  KEY `idx_created` (`created_ymdhis`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_is_public` (`is_public`),
  KEY `idx_media_order` (`media_order`),
  KEY `idx_media_type` (`media_type`),
  KEY `idx_mime_type` (`mime_type`),
  KEY `idx_updated` (`updated_ymdhis`)
);

ALTER TABLE `lupo_content_media` ADD COLUMN IF NOT EXISTS `content_media_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_content_media` ADD COLUMN IF NOT EXISTS `content_id` bigint NOT NULL COMMENT 'Reference to the parent content item';
ALTER TABLE `lupo_content_media` ADD COLUMN IF NOT EXISTS `media_type` enum('image','audio','video','document','other') NOT NULL COMMENT 'Type of media: image, audio, video, document, or other';
ALTER TABLE `lupo_content_media` ADD COLUMN IF NOT EXISTS `original_filename` varchar(255) COMMENT 'Original filename as uploaded by user';
ALTER TABLE `lupo_content_media` ADD COLUMN IF NOT EXISTS `stored_filename` varchar(255) NOT NULL COMMENT 'Generated filename for storage (unique identifier)';
ALTER TABLE `lupo_content_media` ADD COLUMN IF NOT EXISTS `stored_path` varchar(512) NOT NULL COMMENT 'Relative path to the media file (without filename)';
ALTER TABLE `lupo_content_media` ADD COLUMN IF NOT EXISTS `file_extension` varchar(20) COMMENT 'File extension without leading dot';
ALTER TABLE `lupo_content_media` ADD COLUMN IF NOT EXISTS `mime_type` varchar(100) COMMENT 'MIME type of the media file';
ALTER TABLE `lupo_content_media` ADD COLUMN IF NOT EXISTS `file_size` bigint COMMENT 'File size in bytes';
ALTER TABLE `lupo_content_media` ADD COLUMN IF NOT EXISTS `dimensions` varchar(50) COMMENT 'Image/video dimensions (WxH)';
ALTER TABLE `lupo_content_media` ADD COLUMN IF NOT EXISTS `duration` int COMMENT 'Duration in seconds (audio/video)';
ALTER TABLE `lupo_content_media` ADD COLUMN IF NOT EXISTS `media_order` int NOT NULL DEFAULT 0 COMMENT 'Display order within the content item';
ALTER TABLE `lupo_content_media` ADD COLUMN IF NOT EXISTS `title` varchar(255) COMMENT 'Title of the media';
ALTER TABLE `lupo_content_media` ADD COLUMN IF NOT EXISTS `caption_text` text COMMENT 'Descriptive caption';
ALTER TABLE `lupo_content_media` ADD COLUMN IF NOT EXISTS `alt_text` text COMMENT 'Accessibility alternative text';
ALTER TABLE `lupo_content_media` ADD COLUMN IF NOT EXISTS `description` text COMMENT 'Detailed description';
ALTER TABLE `lupo_content_media` ADD COLUMN IF NOT EXISTS `metadata` json COMMENT 'JSON-encoded technical metadata (EXIF, codec info, etc.)';
ALTER TABLE `lupo_content_media` ADD COLUMN IF NOT EXISTS `variants` json COMMENT 'JSON-encoded information about generated variants (thumbnails, etc.)';
ALTER TABLE `lupo_content_media` ADD COLUMN IF NOT EXISTS `is_public` tinyint NOT NULL DEFAULT 1 COMMENT '1 = publicly accessible, 0 = private';
ALTER TABLE `lupo_content_media` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted';
ALTER TABLE `lupo_content_media` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted';
ALTER TABLE `lupo_content_media` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created';
ALTER TABLE `lupo_content_media` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated';
ALTER TABLE `lupo_content_media` MODIFY COLUMN `content_media_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_content_media` MODIFY COLUMN `content_id` bigint NOT NULL COMMENT 'Reference to the parent content item';
ALTER TABLE `lupo_content_media` MODIFY COLUMN `media_type` enum('image','audio','video','document','other') NOT NULL COMMENT 'Type of media: image, audio, video, document, or other';
ALTER TABLE `lupo_content_media` MODIFY COLUMN `original_filename` varchar(255) COMMENT 'Original filename as uploaded by user';
ALTER TABLE `lupo_content_media` MODIFY COLUMN `stored_filename` varchar(255) NOT NULL COMMENT 'Generated filename for storage (unique identifier)';
ALTER TABLE `lupo_content_media` MODIFY COLUMN `stored_path` varchar(512) NOT NULL COMMENT 'Relative path to the media file (without filename)';
ALTER TABLE `lupo_content_media` MODIFY COLUMN `file_extension` varchar(20) COMMENT 'File extension without leading dot';
ALTER TABLE `lupo_content_media` MODIFY COLUMN `mime_type` varchar(100) COMMENT 'MIME type of the media file';
ALTER TABLE `lupo_content_media` MODIFY COLUMN `file_size` bigint COMMENT 'File size in bytes';
ALTER TABLE `lupo_content_media` MODIFY COLUMN `dimensions` varchar(50) COMMENT 'Image/video dimensions (WxH)';
ALTER TABLE `lupo_content_media` MODIFY COLUMN `duration` int COMMENT 'Duration in seconds (audio/video)';
ALTER TABLE `lupo_content_media` MODIFY COLUMN `media_order` int NOT NULL DEFAULT 0 COMMENT 'Display order within the content item';
ALTER TABLE `lupo_content_media` MODIFY COLUMN `title` varchar(255) COMMENT 'Title of the media';
ALTER TABLE `lupo_content_media` MODIFY COLUMN `caption_text` text COMMENT 'Descriptive caption';
ALTER TABLE `lupo_content_media` MODIFY COLUMN `alt_text` text COMMENT 'Accessibility alternative text';
ALTER TABLE `lupo_content_media` MODIFY COLUMN `description` text COMMENT 'Detailed description';
ALTER TABLE `lupo_content_media` MODIFY COLUMN `metadata` json COMMENT 'JSON-encoded technical metadata (EXIF, codec info, etc.)';
ALTER TABLE `lupo_content_media` MODIFY COLUMN `variants` json COMMENT 'JSON-encoded information about generated variants (thumbnails, etc.)';
ALTER TABLE `lupo_content_media` MODIFY COLUMN `is_public` tinyint NOT NULL DEFAULT 1 COMMENT '1 = publicly accessible, 0 = private';
ALTER TABLE `lupo_content_media` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted';
ALTER TABLE `lupo_content_media` MODIFY COLUMN `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted';
ALTER TABLE `lupo_content_media` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created';
ALTER TABLE `lupo_content_media` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated';
ALTER TABLE `lupo_content_media` ADD PRIMARY KEY (`content_media_id`);
ALTER TABLE `lupo_content_media` ADD INDEX IF NOT EXISTS `idx_content` (`content_id`);
ALTER TABLE `lupo_content_media` ADD INDEX IF NOT EXISTS `idx_created` (`created_ymdhis`);
ALTER TABLE `lupo_content_media` ADD INDEX IF NOT EXISTS `idx_is_deleted` (`is_deleted`);
ALTER TABLE `lupo_content_media` ADD INDEX IF NOT EXISTS `idx_is_public` (`is_public`);
ALTER TABLE `lupo_content_media` ADD INDEX IF NOT EXISTS `idx_media_order` (`media_order`);
ALTER TABLE `lupo_content_media` ADD INDEX IF NOT EXISTS `idx_media_type` (`media_type`);
ALTER TABLE `lupo_content_media` ADD INDEX IF NOT EXISTS `idx_mime_type` (`mime_type`);
ALTER TABLE `lupo_content_media` ADD INDEX IF NOT EXISTS `idx_updated` (`updated_ymdhis`);

CREATE TABLE IF NOT EXISTS `lupo_content_question_map` (
  `content_question_map_id` bigint NOT NULL auto_increment,
  `content_id` bigint NOT NULL COMMENT 'Content item associated with the question',
  `question_id` bigint NOT NULL COMMENT 'Question applied to this content',
  `domain_id` bigint NOT NULL COMMENT 'Domain this mapping belongs to',
  `purpose` varchar(255) COMMENT 'Optional: answer, related, metadata, prompt, etc.',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted',
  PRIMARY KEY (`content_question_map_id`),
  KEY `idx_content` (`content_id`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_domain` (`domain_id`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_question` (`question_id`),
  KEY `idx_updated_ymdhis` (`updated_ymdhis`),
  UNIQUE KEY `unique_question_content` (`content_id`, `question_id`)
);

ALTER TABLE `lupo_content_question_map` ADD COLUMN IF NOT EXISTS `content_question_map_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_content_question_map` ADD COLUMN IF NOT EXISTS `content_id` bigint NOT NULL COMMENT 'Content item associated with the question';
ALTER TABLE `lupo_content_question_map` ADD COLUMN IF NOT EXISTS `question_id` bigint NOT NULL COMMENT 'Question applied to this content';
ALTER TABLE `lupo_content_question_map` ADD COLUMN IF NOT EXISTS `domain_id` bigint NOT NULL COMMENT 'Domain this mapping belongs to';
ALTER TABLE `lupo_content_question_map` ADD COLUMN IF NOT EXISTS `purpose` varchar(255) COMMENT 'Optional: answer, related, metadata, prompt, etc.';
ALTER TABLE `lupo_content_question_map` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created';
ALTER TABLE `lupo_content_question_map` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated';
ALTER TABLE `lupo_content_question_map` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted';
ALTER TABLE `lupo_content_question_map` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted';
ALTER TABLE `lupo_content_question_map` MODIFY COLUMN `content_question_map_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_content_question_map` MODIFY COLUMN `content_id` bigint NOT NULL COMMENT 'Content item associated with the question';
ALTER TABLE `lupo_content_question_map` MODIFY COLUMN `question_id` bigint NOT NULL COMMENT 'Question applied to this content';
ALTER TABLE `lupo_content_question_map` MODIFY COLUMN `domain_id` bigint NOT NULL COMMENT 'Domain this mapping belongs to';
ALTER TABLE `lupo_content_question_map` MODIFY COLUMN `purpose` varchar(255) COMMENT 'Optional: answer, related, metadata, prompt, etc.';
ALTER TABLE `lupo_content_question_map` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created';
ALTER TABLE `lupo_content_question_map` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated';
ALTER TABLE `lupo_content_question_map` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted';
ALTER TABLE `lupo_content_question_map` MODIFY COLUMN `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted';
ALTER TABLE `lupo_content_question_map` ADD PRIMARY KEY (`content_question_map_id`);
ALTER TABLE `lupo_content_question_map` ADD INDEX IF NOT EXISTS `idx_content` (`content_id`);
ALTER TABLE `lupo_content_question_map` ADD INDEX IF NOT EXISTS `idx_created_ymdhis` (`created_ymdhis`);
ALTER TABLE `lupo_content_question_map` ADD INDEX IF NOT EXISTS `idx_domain` (`domain_id`);
ALTER TABLE `lupo_content_question_map` ADD INDEX IF NOT EXISTS `idx_is_deleted` (`is_deleted`);
ALTER TABLE `lupo_content_question_map` ADD INDEX IF NOT EXISTS `idx_question` (`question_id`);
ALTER TABLE `lupo_content_question_map` ADD INDEX IF NOT EXISTS `idx_updated_ymdhis` (`updated_ymdhis`);
ALTER TABLE `lupo_content_question_map` ADD UNIQUE INDEX IF NOT EXISTS `unique_question_content` (`content_id`, `question_id`);

CREATE TABLE IF NOT EXISTS `lupo_content_references` (
  `content_referenc_id` bigint NOT NULL auto_increment,
  `content_id` bigint NOT NULL,
  `section_anchor_slug` varchar(255),
  `raw_reference` text NOT NULL,
  `reference_type` varchar(50) NOT NULL,
  `reference_slug` varchar(255),
  `reference_object_id` bigint,
  `meta_json` json,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (`content_referenc_id`),
  KEY `idx_content_id` (`content_id`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_reference_object` (`reference_object_id`),
  KEY `idx_reference_slug` (`reference_slug`),
  KEY `idx_reference_type` (`reference_type`),
  KEY `idx_section_anchor` (`section_anchor_slug`)
);

ALTER TABLE `lupo_content_references` ADD COLUMN IF NOT EXISTS `content_referenc_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_content_references` ADD COLUMN IF NOT EXISTS `content_id` bigint NOT NULL;
ALTER TABLE `lupo_content_references` ADD COLUMN IF NOT EXISTS `section_anchor_slug` varchar(255);
ALTER TABLE `lupo_content_references` ADD COLUMN IF NOT EXISTS `raw_reference` text NOT NULL;
ALTER TABLE `lupo_content_references` ADD COLUMN IF NOT EXISTS `reference_type` varchar(50) NOT NULL;
ALTER TABLE `lupo_content_references` ADD COLUMN IF NOT EXISTS `reference_slug` varchar(255);
ALTER TABLE `lupo_content_references` ADD COLUMN IF NOT EXISTS `reference_object_id` bigint;
ALTER TABLE `lupo_content_references` ADD COLUMN IF NOT EXISTS `meta_json` json;
ALTER TABLE `lupo_content_references` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_content_references` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_content_references` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_content_references` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_content_references` MODIFY COLUMN `content_referenc_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_content_references` MODIFY COLUMN `content_id` bigint NOT NULL;
ALTER TABLE `lupo_content_references` MODIFY COLUMN `section_anchor_slug` varchar(255);
ALTER TABLE `lupo_content_references` MODIFY COLUMN `raw_reference` text NOT NULL;
ALTER TABLE `lupo_content_references` MODIFY COLUMN `reference_type` varchar(50) NOT NULL;
ALTER TABLE `lupo_content_references` MODIFY COLUMN `reference_slug` varchar(255);
ALTER TABLE `lupo_content_references` MODIFY COLUMN `reference_object_id` bigint;
ALTER TABLE `lupo_content_references` MODIFY COLUMN `meta_json` json;
ALTER TABLE `lupo_content_references` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_content_references` MODIFY COLUMN `deleted_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_content_references` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_content_references` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_content_references` ADD PRIMARY KEY (`content_referenc_id`);
ALTER TABLE `lupo_content_references` ADD INDEX IF NOT EXISTS `idx_content_id` (`content_id`);
ALTER TABLE `lupo_content_references` ADD INDEX IF NOT EXISTS `idx_is_deleted` (`is_deleted`);
ALTER TABLE `lupo_content_references` ADD INDEX IF NOT EXISTS `idx_reference_object` (`reference_object_id`);
ALTER TABLE `lupo_content_references` ADD INDEX IF NOT EXISTS `idx_reference_slug` (`reference_slug`);
ALTER TABLE `lupo_content_references` ADD INDEX IF NOT EXISTS `idx_reference_type` (`reference_type`);
ALTER TABLE `lupo_content_references` ADD INDEX IF NOT EXISTS `idx_section_anchor` (`section_anchor_slug`);

CREATE TABLE IF NOT EXISTS `lupo_content_revisions` (
  `content_revision_id` bigint NOT NULL auto_increment,
  `content_id` bigint NOT NULL COMMENT 'FK to contents table (not enforced by DB)',
  `version_number` int NOT NULL COMMENT 'Version number this snapshot represents',
  `body_snapshot` text NOT NULL COMMENT 'Full body content at the time of revision',
  `metadata_snapshot` json COMMENT 'Metadata JSON at the time of revision',
  `sections_snapshot` json COMMENT 'Cached section anchors at the time of revision',
  `edited_by` varchar(100) COMMENT 'Username or agent identifier that made the change',
  `created_ymdhis` datetime NOT NULL,
  PRIMARY KEY (`content_revision_id`),
  KEY `content_id` (`content_id`),
  KEY `version_number` (`version_number`)
);

ALTER TABLE `lupo_content_revisions` ADD COLUMN IF NOT EXISTS `content_revision_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_content_revisions` ADD COLUMN IF NOT EXISTS `content_id` bigint NOT NULL COMMENT 'FK to contents table (not enforced by DB)';
ALTER TABLE `lupo_content_revisions` ADD COLUMN IF NOT EXISTS `version_number` int NOT NULL COMMENT 'Version number this snapshot represents';
ALTER TABLE `lupo_content_revisions` ADD COLUMN IF NOT EXISTS `body_snapshot` text NOT NULL COMMENT 'Full body content at the time of revision';
ALTER TABLE `lupo_content_revisions` ADD COLUMN IF NOT EXISTS `metadata_snapshot` json COMMENT 'Metadata JSON at the time of revision';
ALTER TABLE `lupo_content_revisions` ADD COLUMN IF NOT EXISTS `sections_snapshot` json COMMENT 'Cached section anchors at the time of revision';
ALTER TABLE `lupo_content_revisions` ADD COLUMN IF NOT EXISTS `edited_by` varchar(100) COMMENT 'Username or agent identifier that made the change';
ALTER TABLE `lupo_content_revisions` ADD COLUMN IF NOT EXISTS `created_ymdhis` datetime NOT NULL;
ALTER TABLE `lupo_content_revisions` MODIFY COLUMN `content_revision_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_content_revisions` MODIFY COLUMN `content_id` bigint NOT NULL COMMENT 'FK to contents table (not enforced by DB)';
ALTER TABLE `lupo_content_revisions` MODIFY COLUMN `version_number` int NOT NULL COMMENT 'Version number this snapshot represents';
ALTER TABLE `lupo_content_revisions` MODIFY COLUMN `body_snapshot` text NOT NULL COMMENT 'Full body content at the time of revision';
ALTER TABLE `lupo_content_revisions` MODIFY COLUMN `metadata_snapshot` json COMMENT 'Metadata JSON at the time of revision';
ALTER TABLE `lupo_content_revisions` MODIFY COLUMN `sections_snapshot` json COMMENT 'Cached section anchors at the time of revision';
ALTER TABLE `lupo_content_revisions` MODIFY COLUMN `edited_by` varchar(100) COMMENT 'Username or agent identifier that made the change';
ALTER TABLE `lupo_content_revisions` MODIFY COLUMN `created_ymdhis` datetime NOT NULL;
ALTER TABLE `lupo_content_revisions` ADD PRIMARY KEY (`content_revision_id`);
ALTER TABLE `lupo_content_revisions` ADD INDEX IF NOT EXISTS `content_id` (`content_id`);
ALTER TABLE `lupo_content_revisions` ADD INDEX IF NOT EXISTS `version_number` (`version_number`);

CREATE TABLE IF NOT EXISTS `lupo_content_shares` (
  `content_share_id` bigint NOT NULL auto_increment,
  `content_id` bigint NOT NULL COMMENT 'ID of the content being shared',
  `user_id` bigint COMMENT 'ID of the user who shared (if authenticated)',
  `visitor_hash` char(64) COMMENT 'Hashed IP/session for anonymous shares',
  `share_method` varchar(50) COMMENT 'Share method: link, embed, social, email, etc.',
  `share_target` varchar(255) COMMENT 'Specific target of the share (e.g., "twitter", "facebook")',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted',
  PRIMARY KEY (`content_share_id`),
  KEY `idx_content` (`content_id`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_method` (`share_method`),
  KEY `idx_updated_ymdhis` (`updated_ymdhis`),
  KEY `idx_user` (`user_id`)
);

ALTER TABLE `lupo_content_shares` ADD COLUMN IF NOT EXISTS `content_share_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_content_shares` ADD COLUMN IF NOT EXISTS `content_id` bigint NOT NULL COMMENT 'ID of the content being shared';
ALTER TABLE `lupo_content_shares` ADD COLUMN IF NOT EXISTS `user_id` bigint COMMENT 'ID of the user who shared (if authenticated)';
ALTER TABLE `lupo_content_shares` ADD COLUMN IF NOT EXISTS `visitor_hash` char(64) COMMENT 'Hashed IP/session for anonymous shares';
ALTER TABLE `lupo_content_shares` ADD COLUMN IF NOT EXISTS `share_method` varchar(50) COMMENT 'Share method: link, embed, social, email, etc.';
ALTER TABLE `lupo_content_shares` ADD COLUMN IF NOT EXISTS `share_target` varchar(255) COMMENT 'Specific target of the share (e.g., "twitter", "facebook")';
ALTER TABLE `lupo_content_shares` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created';
ALTER TABLE `lupo_content_shares` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated';
ALTER TABLE `lupo_content_shares` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted';
ALTER TABLE `lupo_content_shares` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted';
ALTER TABLE `lupo_content_shares` MODIFY COLUMN `content_share_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_content_shares` MODIFY COLUMN `content_id` bigint NOT NULL COMMENT 'ID of the content being shared';
ALTER TABLE `lupo_content_shares` MODIFY COLUMN `user_id` bigint COMMENT 'ID of the user who shared (if authenticated)';
ALTER TABLE `lupo_content_shares` MODIFY COLUMN `visitor_hash` char(64) COMMENT 'Hashed IP/session for anonymous shares';
ALTER TABLE `lupo_content_shares` MODIFY COLUMN `share_method` varchar(50) COMMENT 'Share method: link, embed, social, email, etc.';
ALTER TABLE `lupo_content_shares` MODIFY COLUMN `share_target` varchar(255) COMMENT 'Specific target of the share (e.g., "twitter", "facebook")';
ALTER TABLE `lupo_content_shares` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created';
ALTER TABLE `lupo_content_shares` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated';
ALTER TABLE `lupo_content_shares` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted';
ALTER TABLE `lupo_content_shares` MODIFY COLUMN `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted';
ALTER TABLE `lupo_content_shares` ADD PRIMARY KEY (`content_share_id`);
ALTER TABLE `lupo_content_shares` ADD INDEX IF NOT EXISTS `idx_content` (`content_id`);
ALTER TABLE `lupo_content_shares` ADD INDEX IF NOT EXISTS `idx_created_ymdhis` (`created_ymdhis`);
ALTER TABLE `lupo_content_shares` ADD INDEX IF NOT EXISTS `idx_is_deleted` (`is_deleted`);
ALTER TABLE `lupo_content_shares` ADD INDEX IF NOT EXISTS `idx_method` (`share_method`);
ALTER TABLE `lupo_content_shares` ADD INDEX IF NOT EXISTS `idx_updated_ymdhis` (`updated_ymdhis`);
ALTER TABLE `lupo_content_shares` ADD INDEX IF NOT EXISTS `idx_user` (`user_id`);

CREATE TABLE IF NOT EXISTS `lupo_content_tag_relationships` (
  `relationship_id` bigint NOT NULL auto_increment COMMENT 'Primary key for content-tag relationship',
  `content_id` bigint NOT NULL COMMENT 'Reference to content table',
  `tag_id` bigint NOT NULL COMMENT 'Reference to tag table',
  `relationship_type` enum('category','topic','label') NOT NULL COMMENT 'Type of relationship',
  `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint',
  PRIMARY KEY (`relationship_id`),
  KEY `idx_content_id` (`content_id`),
  KEY `idx_created` (`created_ymdhis`),
  KEY `idx_created_ymdhis` (`created_ymdhis`, `relationship_type`, `content_id`, `tag_id`),
  KEY `idx_relationship_type` (`relationship_type`),
  KEY `idx_tag_id` (`tag_id`)
);

ALTER TABLE `lupo_content_tag_relationships` ADD COLUMN IF NOT EXISTS `relationship_id` bigint NOT NULL auto_increment COMMENT 'Primary key for content-tag relationship';
ALTER TABLE `lupo_content_tag_relationships` ADD COLUMN IF NOT EXISTS `content_id` bigint NOT NULL COMMENT 'Reference to content table';
ALTER TABLE `lupo_content_tag_relationships` ADD COLUMN IF NOT EXISTS `tag_id` bigint NOT NULL COMMENT 'Reference to tag table';
ALTER TABLE `lupo_content_tag_relationships` ADD COLUMN IF NOT EXISTS `relationship_type` enum('category','topic','label') NOT NULL COMMENT 'Type of relationship';
ALTER TABLE `lupo_content_tag_relationships` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint';
ALTER TABLE `lupo_content_tag_relationships` MODIFY COLUMN `relationship_id` bigint NOT NULL auto_increment COMMENT 'Primary key for content-tag relationship';
ALTER TABLE `lupo_content_tag_relationships` MODIFY COLUMN `content_id` bigint NOT NULL COMMENT 'Reference to content table';
ALTER TABLE `lupo_content_tag_relationships` MODIFY COLUMN `tag_id` bigint NOT NULL COMMENT 'Reference to tag table';
ALTER TABLE `lupo_content_tag_relationships` MODIFY COLUMN `relationship_type` enum('category','topic','label') NOT NULL COMMENT 'Type of relationship';
ALTER TABLE `lupo_content_tag_relationships` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint';
ALTER TABLE `lupo_content_tag_relationships` ADD PRIMARY KEY (`relationship_id`);
ALTER TABLE `lupo_content_tag_relationships` ADD INDEX IF NOT EXISTS `idx_content_id` (`content_id`);
ALTER TABLE `lupo_content_tag_relationships` ADD INDEX IF NOT EXISTS `idx_created` (`created_ymdhis`);
ALTER TABLE `lupo_content_tag_relationships` ADD INDEX IF NOT EXISTS `idx_created_ymdhis` (`created_ymdhis`, `relationship_type`, `content_id`, `tag_id`);
ALTER TABLE `lupo_content_tag_relationships` ADD INDEX IF NOT EXISTS `idx_relationship_type` (`relationship_type`);
ALTER TABLE `lupo_content_tag_relationships` ADD INDEX IF NOT EXISTS `idx_tag_id` (`tag_id`);

CREATE TABLE IF NOT EXISTS `lupo_contents` (
  `content_id` bigint NOT NULL auto_increment COMMENT 'Primary key for content',
  `content_parent_id` bigint COMMENT 'Parent content ID for hierarchical relationships',
  `federation_node_id` bigint DEFAULT 1 COMMENT 'Domain scope, NULL for global content',
  `group_id` bigint COMMENT 'Optional group restriction',
  `user_id` bigint COMMENT 'Author of the content',
  `title` varchar(255) NOT NULL COMMENT 'Content title',
  `slug` varchar(255) NOT NULL COMMENT 'URL-friendly identifier, unique within domain',
  `description` text COMMENT 'Short summary or teaser',
  `seo_keywords` varchar(500),
  `body` longtext COMMENT 'Main content body',
  `content_type` varchar(50) DEFAULT 'article' COMMENT 'Type of content (article, page, post, etc.)',
  `format` varchar(20) DEFAULT 'html' COMMENT 'Content format (html, markdown, etc.)',
  `content_url` varchar(2000) COMMENT 'content URL if content is referenced by url such as lupopedia.com/content/lupopedia',
  `default_collection_id` bigint COMMENT 'Default collection ID',
  `source_url` varchar(2000) COMMENT 'Original source URL if content is imported',
  `source_title` varchar(500) COMMENT 'Title of the source',
  `is_template` tinyint NOT NULL DEFAULT 0 COMMENT '1 = template, 0 = regular content',
  `status` enum('draft','published','archived') DEFAULT 'draft' COMMENT 'Publication status',
  `visibility` enum('public','private','unlisted') DEFAULT 'public' COMMENT 'Visibility level',
  `view_count` int DEFAULT 0 COMMENT 'Number of views',
  `share_count` int DEFAULT 0 COMMENT 'Number of shares',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
  `utc_cycle` enum('creative','responsible') NOT NULL,
  `triage_status` enum('untriaged','keeper','fragment','duplicate','trash') NOT NULL DEFAULT 'untriaged',
  `triage_notes` text,
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `is_active` tinyint NOT NULL DEFAULT 1 COMMENT '1 = active, 0 = not active',
  `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted',
  `content_sections` json COMMENT 'Cached list of section anchors extracted from the content body',
  `version_number` int NOT NULL DEFAULT 1 COMMENT 'Monotonic version number for the live content row',
  PRIMARY KEY (`content_id`),
  KEY `ft_content` (`title`, `description`, `body`),
  KEY `idx_content_parent` (`content_parent_id`),
  KEY `idx_content_type` (`content_type`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_domain` (`federation_node_id`),
  KEY `idx_group` (`group_id`),
  KEY `idx_is_active` (`is_active`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_status` (`status`),
  KEY `idx_updated_ymdhis` (`updated_ymdhis`),
  KEY `idx_user` (`user_id`),
  KEY `idx_visibility` (`visibility`),
  UNIQUE KEY `unique_content_slug_domain` (`federation_node_id`, `slug`)
);

ALTER TABLE `lupo_contents` ADD COLUMN IF NOT EXISTS `content_id` bigint NOT NULL auto_increment COMMENT 'Primary key for content';
ALTER TABLE `lupo_contents` ADD COLUMN IF NOT EXISTS `content_parent_id` bigint COMMENT 'Parent content ID for hierarchical relationships';
ALTER TABLE `lupo_contents` ADD COLUMN IF NOT EXISTS `federation_node_id` bigint DEFAULT 1 COMMENT 'Domain scope, NULL for global content';
ALTER TABLE `lupo_contents` ADD COLUMN IF NOT EXISTS `group_id` bigint COMMENT 'Optional group restriction';
ALTER TABLE `lupo_contents` ADD COLUMN IF NOT EXISTS `user_id` bigint COMMENT 'Author of the content';
ALTER TABLE `lupo_contents` ADD COLUMN IF NOT EXISTS `title` varchar(255) NOT NULL COMMENT 'Content title';
ALTER TABLE `lupo_contents` ADD COLUMN IF NOT EXISTS `slug` varchar(255) NOT NULL COMMENT 'URL-friendly identifier, unique within domain';
ALTER TABLE `lupo_contents` ADD COLUMN IF NOT EXISTS `description` text COMMENT 'Short summary or teaser';
ALTER TABLE `lupo_contents` ADD COLUMN IF NOT EXISTS `seo_keywords` varchar(500);
ALTER TABLE `lupo_contents` ADD COLUMN IF NOT EXISTS `body` longtext COMMENT 'Main content body';
ALTER TABLE `lupo_contents` ADD COLUMN IF NOT EXISTS `content_type` varchar(50) DEFAULT 'article' COMMENT 'Type of content (article, page, post, etc.)';
ALTER TABLE `lupo_contents` ADD COLUMN IF NOT EXISTS `format` varchar(20) DEFAULT 'html' COMMENT 'Content format (html, markdown, etc.)';
ALTER TABLE `lupo_contents` ADD COLUMN IF NOT EXISTS `content_url` varchar(2000) COMMENT 'content URL if content is referenced by url such as lupopedia.com/content/lupopedia';
ALTER TABLE `lupo_contents` ADD COLUMN IF NOT EXISTS `default_collection_id` bigint COMMENT 'Default collection ID';
ALTER TABLE `lupo_contents` ADD COLUMN IF NOT EXISTS `source_url` varchar(2000) COMMENT 'Original source URL if content is imported';
ALTER TABLE `lupo_contents` ADD COLUMN IF NOT EXISTS `source_title` varchar(500) COMMENT 'Title of the source';
ALTER TABLE `lupo_contents` ADD COLUMN IF NOT EXISTS `is_template` tinyint NOT NULL DEFAULT 0 COMMENT '1 = template, 0 = regular content';
ALTER TABLE `lupo_contents` ADD COLUMN IF NOT EXISTS `status` enum('draft','published','archived') DEFAULT 'draft' COMMENT 'Publication status';
ALTER TABLE `lupo_contents` ADD COLUMN IF NOT EXISTS `visibility` enum('public','private','unlisted') DEFAULT 'public' COMMENT 'Visibility level';
ALTER TABLE `lupo_contents` ADD COLUMN IF NOT EXISTS `view_count` int DEFAULT 0 COMMENT 'Number of views';
ALTER TABLE `lupo_contents` ADD COLUMN IF NOT EXISTS `share_count` int DEFAULT 0 COMMENT 'Number of shares';
ALTER TABLE `lupo_contents` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created';
ALTER TABLE `lupo_contents` ADD COLUMN IF NOT EXISTS `utc_cycle` enum('creative','responsible') NOT NULL;
ALTER TABLE `lupo_contents` ADD COLUMN IF NOT EXISTS `triage_status` enum('untriaged','keeper','fragment','duplicate','trash') NOT NULL DEFAULT 'untriaged';
ALTER TABLE `lupo_contents` ADD COLUMN IF NOT EXISTS `triage_notes` text;
ALTER TABLE `lupo_contents` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated';
ALTER TABLE `lupo_contents` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted';
ALTER TABLE `lupo_contents` ADD COLUMN IF NOT EXISTS `is_active` tinyint NOT NULL DEFAULT 1 COMMENT '1 = active, 0 = not active';
ALTER TABLE `lupo_contents` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted';
ALTER TABLE `lupo_contents` ADD COLUMN IF NOT EXISTS `content_sections` json COMMENT 'Cached list of section anchors extracted from the content body';
ALTER TABLE `lupo_contents` ADD COLUMN IF NOT EXISTS `version_number` int NOT NULL DEFAULT 1 COMMENT 'Monotonic version number for the live content row';
ALTER TABLE `lupo_contents` MODIFY COLUMN `content_id` bigint NOT NULL auto_increment COMMENT 'Primary key for content';
ALTER TABLE `lupo_contents` MODIFY COLUMN `content_parent_id` bigint COMMENT 'Parent content ID for hierarchical relationships';
ALTER TABLE `lupo_contents` MODIFY COLUMN `federation_node_id` bigint DEFAULT 1 COMMENT 'Domain scope, NULL for global content';
ALTER TABLE `lupo_contents` MODIFY COLUMN `group_id` bigint COMMENT 'Optional group restriction';
ALTER TABLE `lupo_contents` MODIFY COLUMN `user_id` bigint COMMENT 'Author of the content';
ALTER TABLE `lupo_contents` MODIFY COLUMN `title` varchar(255) NOT NULL COMMENT 'Content title';
ALTER TABLE `lupo_contents` MODIFY COLUMN `slug` varchar(255) NOT NULL COMMENT 'URL-friendly identifier, unique within domain';
ALTER TABLE `lupo_contents` MODIFY COLUMN `description` text COMMENT 'Short summary or teaser';
ALTER TABLE `lupo_contents` MODIFY COLUMN `seo_keywords` varchar(500);
ALTER TABLE `lupo_contents` MODIFY COLUMN `body` longtext COMMENT 'Main content body';
ALTER TABLE `lupo_contents` MODIFY COLUMN `content_type` varchar(50) DEFAULT 'article' COMMENT 'Type of content (article, page, post, etc.)';
ALTER TABLE `lupo_contents` MODIFY COLUMN `format` varchar(20) DEFAULT 'html' COMMENT 'Content format (html, markdown, etc.)';
ALTER TABLE `lupo_contents` MODIFY COLUMN `content_url` varchar(2000) COMMENT 'content URL if content is referenced by url such as lupopedia.com/content/lupopedia';
ALTER TABLE `lupo_contents` MODIFY COLUMN `default_collection_id` bigint COMMENT 'Default collection ID';
ALTER TABLE `lupo_contents` MODIFY COLUMN `source_url` varchar(2000) COMMENT 'Original source URL if content is imported';
ALTER TABLE `lupo_contents` MODIFY COLUMN `source_title` varchar(500) COMMENT 'Title of the source';
ALTER TABLE `lupo_contents` MODIFY COLUMN `is_template` tinyint NOT NULL DEFAULT 0 COMMENT '1 = template, 0 = regular content';
ALTER TABLE `lupo_contents` MODIFY COLUMN `status` enum('draft','published','archived') DEFAULT 'draft' COMMENT 'Publication status';
ALTER TABLE `lupo_contents` MODIFY COLUMN `visibility` enum('public','private','unlisted') DEFAULT 'public' COMMENT 'Visibility level';
ALTER TABLE `lupo_contents` MODIFY COLUMN `view_count` int DEFAULT 0 COMMENT 'Number of views';
ALTER TABLE `lupo_contents` MODIFY COLUMN `share_count` int DEFAULT 0 COMMENT 'Number of shares';
ALTER TABLE `lupo_contents` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created';
ALTER TABLE `lupo_contents` MODIFY COLUMN `utc_cycle` enum('creative','responsible') NOT NULL;
ALTER TABLE `lupo_contents` MODIFY COLUMN `triage_status` enum('untriaged','keeper','fragment','duplicate','trash') NOT NULL DEFAULT 'untriaged';
ALTER TABLE `lupo_contents` MODIFY COLUMN `triage_notes` text;
ALTER TABLE `lupo_contents` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated';
ALTER TABLE `lupo_contents` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted';
ALTER TABLE `lupo_contents` MODIFY COLUMN `is_active` tinyint NOT NULL DEFAULT 1 COMMENT '1 = active, 0 = not active';
ALTER TABLE `lupo_contents` MODIFY COLUMN `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted';
ALTER TABLE `lupo_contents` MODIFY COLUMN `content_sections` json COMMENT 'Cached list of section anchors extracted from the content body';
ALTER TABLE `lupo_contents` MODIFY COLUMN `version_number` int NOT NULL DEFAULT 1 COMMENT 'Monotonic version number for the live content row';
ALTER TABLE `lupo_contents` ADD PRIMARY KEY (`content_id`);
ALTER TABLE `lupo_contents` ADD INDEX IF NOT EXISTS `ft_content` (`title`, `description`, `body`);
ALTER TABLE `lupo_contents` ADD INDEX IF NOT EXISTS `idx_content_parent` (`content_parent_id`);
ALTER TABLE `lupo_contents` ADD INDEX IF NOT EXISTS `idx_content_type` (`content_type`);
ALTER TABLE `lupo_contents` ADD INDEX IF NOT EXISTS `idx_created_ymdhis` (`created_ymdhis`);
ALTER TABLE `lupo_contents` ADD INDEX IF NOT EXISTS `idx_domain` (`federation_node_id`);
ALTER TABLE `lupo_contents` ADD INDEX IF NOT EXISTS `idx_group` (`group_id`);
ALTER TABLE `lupo_contents` ADD INDEX IF NOT EXISTS `idx_is_active` (`is_active`);
ALTER TABLE `lupo_contents` ADD INDEX IF NOT EXISTS `idx_is_deleted` (`is_deleted`);
ALTER TABLE `lupo_contents` ADD INDEX IF NOT EXISTS `idx_status` (`status`);
ALTER TABLE `lupo_contents` ADD INDEX IF NOT EXISTS `idx_updated_ymdhis` (`updated_ymdhis`);
ALTER TABLE `lupo_contents` ADD INDEX IF NOT EXISTS `idx_user` (`user_id`);
ALTER TABLE `lupo_contents` ADD INDEX IF NOT EXISTS `idx_visibility` (`visibility`);
ALTER TABLE `lupo_contents` ADD UNIQUE INDEX IF NOT EXISTS `unique_content_slug_domain` (`federation_node_id`, `slug`);

CREATE TABLE IF NOT EXISTS `lupo_contexts` (
  `context_id` bigint NOT NULL auto_increment,
  `context_type` varchar(50) NOT NULL,
  `context_slug` varchar(255) NOT NULL,
  `parent_context_id` bigint,
  `description` text,
  `created_ymd` bigint NOT NULL DEFAULT 0,
  `updated_ymd` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (`context_id`),
  KEY `idx_context_slug` (`context_slug`),
  KEY `idx_context_type` (`context_type`),
  KEY `idx_parent_context` (`parent_context_id`)
);

ALTER TABLE `lupo_contexts` ADD COLUMN IF NOT EXISTS `context_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_contexts` ADD COLUMN IF NOT EXISTS `context_type` varchar(50) NOT NULL;
ALTER TABLE `lupo_contexts` ADD COLUMN IF NOT EXISTS `context_slug` varchar(255) NOT NULL;
ALTER TABLE `lupo_contexts` ADD COLUMN IF NOT EXISTS `parent_context_id` bigint;
ALTER TABLE `lupo_contexts` ADD COLUMN IF NOT EXISTS `description` text;
ALTER TABLE `lupo_contexts` ADD COLUMN IF NOT EXISTS `created_ymd` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_contexts` ADD COLUMN IF NOT EXISTS `updated_ymd` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_contexts` MODIFY COLUMN `context_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_contexts` MODIFY COLUMN `context_type` varchar(50) NOT NULL;
ALTER TABLE `lupo_contexts` MODIFY COLUMN `context_slug` varchar(255) NOT NULL;
ALTER TABLE `lupo_contexts` MODIFY COLUMN `parent_context_id` bigint;
ALTER TABLE `lupo_contexts` MODIFY COLUMN `description` text;
ALTER TABLE `lupo_contexts` MODIFY COLUMN `created_ymd` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_contexts` MODIFY COLUMN `updated_ymd` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_contexts` ADD PRIMARY KEY (`context_id`);
ALTER TABLE `lupo_contexts` ADD INDEX IF NOT EXISTS `idx_context_slug` (`context_slug`);
ALTER TABLE `lupo_contexts` ADD INDEX IF NOT EXISTS `idx_context_type` (`context_type`);
ALTER TABLE `lupo_contexts` ADD INDEX IF NOT EXISTS `idx_parent_context` (`parent_context_id`);

CREATE TABLE IF NOT EXISTS `lupo_contexts_map` (
  `contexts_map_id` bigint NOT NULL auto_increment,
  `context_id` bigint NOT NULL,
  `item_type` varchar(50) NOT NULL,
  `item_slug` varchar(255) NOT NULL,
  `description` text,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (`contexts_map_id`),
  KEY `idx_context_id` (`context_id`),
  KEY `idx_context_item` (`context_id`, `item_type`, `item_slug`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_item_slug` (`item_slug`),
  KEY `idx_item_type` (`item_type`)
);

ALTER TABLE `lupo_contexts_map` ADD COLUMN IF NOT EXISTS `contexts_map_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_contexts_map` ADD COLUMN IF NOT EXISTS `context_id` bigint NOT NULL;
ALTER TABLE `lupo_contexts_map` ADD COLUMN IF NOT EXISTS `item_type` varchar(50) NOT NULL;
ALTER TABLE `lupo_contexts_map` ADD COLUMN IF NOT EXISTS `item_slug` varchar(255) NOT NULL;
ALTER TABLE `lupo_contexts_map` ADD COLUMN IF NOT EXISTS `description` text;
ALTER TABLE `lupo_contexts_map` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_contexts_map` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_contexts_map` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_contexts_map` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_contexts_map` MODIFY COLUMN `contexts_map_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_contexts_map` MODIFY COLUMN `context_id` bigint NOT NULL;
ALTER TABLE `lupo_contexts_map` MODIFY COLUMN `item_type` varchar(50) NOT NULL;
ALTER TABLE `lupo_contexts_map` MODIFY COLUMN `item_slug` varchar(255) NOT NULL;
ALTER TABLE `lupo_contexts_map` MODIFY COLUMN `description` text;
ALTER TABLE `lupo_contexts_map` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_contexts_map` MODIFY COLUMN `deleted_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_contexts_map` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_contexts_map` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_contexts_map` ADD PRIMARY KEY (`contexts_map_id`);
ALTER TABLE `lupo_contexts_map` ADD INDEX IF NOT EXISTS `idx_context_id` (`context_id`);
ALTER TABLE `lupo_contexts_map` ADD INDEX IF NOT EXISTS `idx_context_item` (`context_id`, `item_type`, `item_slug`);
ALTER TABLE `lupo_contexts_map` ADD INDEX IF NOT EXISTS `idx_is_deleted` (`is_deleted`);
ALTER TABLE `lupo_contexts_map` ADD INDEX IF NOT EXISTS `idx_item_slug` (`item_slug`);
ALTER TABLE `lupo_contexts_map` ADD INDEX IF NOT EXISTS `idx_item_type` (`item_type`);

CREATE TABLE IF NOT EXISTS `lupo_crafty_syntax_auto_invite` (
  `crafty_syntax_auto_invite_id` bigint NOT NULL auto_increment,
  `is_offline` tinyint NOT NULL DEFAULT 0,
  `is_active` tinyint NOT NULL DEFAULT 0,
  `department_id` bigint NOT NULL DEFAULT 0,
  `message` mediumtext,
  `page_url` varchar(500),
  `visits` int NOT NULL DEFAULT 0,
  `referrer_url` varchar(500),
  `invite_type` varchar(50),
  `trigger_seconds` int NOT NULL DEFAULT 0,
  `operator_user_id` bigint NOT NULL DEFAULT 0,
  `show_socialpane` tinyint NOT NULL DEFAULT 0,
  `exclude_mobile` tinyint NOT NULL DEFAULT 0,
  `only_mobile` tinyint NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 20250101000000,
  `updated_ymdhis` bigint NOT NULL DEFAULT 20250101000000,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (`crafty_syntax_auto_invite_id`),
  KEY `idx_created` (`created_ymdhis`),
  KEY `idx_department` (`department_id`),
  KEY `idx_operator` (`operator_user_id`),
  KEY `idx_page_url` (`page_url`),
  KEY `idx_status` (`is_active`, `is_deleted`)
);

ALTER TABLE `lupo_crafty_syntax_auto_invite` ADD COLUMN IF NOT EXISTS `crafty_syntax_auto_invite_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_crafty_syntax_auto_invite` ADD COLUMN IF NOT EXISTS `is_offline` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_crafty_syntax_auto_invite` ADD COLUMN IF NOT EXISTS `is_active` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_crafty_syntax_auto_invite` ADD COLUMN IF NOT EXISTS `department_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_crafty_syntax_auto_invite` ADD COLUMN IF NOT EXISTS `message` mediumtext;
ALTER TABLE `lupo_crafty_syntax_auto_invite` ADD COLUMN IF NOT EXISTS `page_url` varchar(500);
ALTER TABLE `lupo_crafty_syntax_auto_invite` ADD COLUMN IF NOT EXISTS `visits` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_crafty_syntax_auto_invite` ADD COLUMN IF NOT EXISTS `referrer_url` varchar(500);
ALTER TABLE `lupo_crafty_syntax_auto_invite` ADD COLUMN IF NOT EXISTS `invite_type` varchar(50);
ALTER TABLE `lupo_crafty_syntax_auto_invite` ADD COLUMN IF NOT EXISTS `trigger_seconds` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_crafty_syntax_auto_invite` ADD COLUMN IF NOT EXISTS `operator_user_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_crafty_syntax_auto_invite` ADD COLUMN IF NOT EXISTS `show_socialpane` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_crafty_syntax_auto_invite` ADD COLUMN IF NOT EXISTS `exclude_mobile` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_crafty_syntax_auto_invite` ADD COLUMN IF NOT EXISTS `only_mobile` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_crafty_syntax_auto_invite` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL DEFAULT 20250101000000;
ALTER TABLE `lupo_crafty_syntax_auto_invite` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL DEFAULT 20250101000000;
ALTER TABLE `lupo_crafty_syntax_auto_invite` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_crafty_syntax_auto_invite` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint;
ALTER TABLE `lupo_crafty_syntax_auto_invite` MODIFY COLUMN `crafty_syntax_auto_invite_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_crafty_syntax_auto_invite` MODIFY COLUMN `is_offline` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_crafty_syntax_auto_invite` MODIFY COLUMN `is_active` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_crafty_syntax_auto_invite` MODIFY COLUMN `department_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_crafty_syntax_auto_invite` MODIFY COLUMN `message` mediumtext;
ALTER TABLE `lupo_crafty_syntax_auto_invite` MODIFY COLUMN `page_url` varchar(500);
ALTER TABLE `lupo_crafty_syntax_auto_invite` MODIFY COLUMN `visits` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_crafty_syntax_auto_invite` MODIFY COLUMN `referrer_url` varchar(500);
ALTER TABLE `lupo_crafty_syntax_auto_invite` MODIFY COLUMN `invite_type` varchar(50);
ALTER TABLE `lupo_crafty_syntax_auto_invite` MODIFY COLUMN `trigger_seconds` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_crafty_syntax_auto_invite` MODIFY COLUMN `operator_user_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_crafty_syntax_auto_invite` MODIFY COLUMN `show_socialpane` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_crafty_syntax_auto_invite` MODIFY COLUMN `exclude_mobile` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_crafty_syntax_auto_invite` MODIFY COLUMN `only_mobile` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_crafty_syntax_auto_invite` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 20250101000000;
ALTER TABLE `lupo_crafty_syntax_auto_invite` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL DEFAULT 20250101000000;
ALTER TABLE `lupo_crafty_syntax_auto_invite` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_crafty_syntax_auto_invite` MODIFY COLUMN `deleted_ymdhis` bigint;
ALTER TABLE `lupo_crafty_syntax_auto_invite` ADD PRIMARY KEY (`crafty_syntax_auto_invite_id`);
ALTER TABLE `lupo_crafty_syntax_auto_invite` ADD INDEX IF NOT EXISTS `idx_created` (`created_ymdhis`);
ALTER TABLE `lupo_crafty_syntax_auto_invite` ADD INDEX IF NOT EXISTS `idx_department` (`department_id`);
ALTER TABLE `lupo_crafty_syntax_auto_invite` ADD INDEX IF NOT EXISTS `idx_operator` (`operator_user_id`);
ALTER TABLE `lupo_crafty_syntax_auto_invite` ADD INDEX IF NOT EXISTS `idx_page_url` (`page_url`);
ALTER TABLE `lupo_crafty_syntax_auto_invite` ADD INDEX IF NOT EXISTS `idx_status` (`is_active`, `is_deleted`);

CREATE TABLE IF NOT EXISTS `lupo_crafty_syntax_chat_mod_departments` (
  `crafty_syntax_chat_mod_department_id` bigint NOT NULL auto_increment,
  `department_id` bigint NOT NULL DEFAULT 0,
  `module_id` bigint NOT NULL DEFAULT 0,
  `sort_order` int NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`crafty_syntax_chat_mod_department_id`)
);

ALTER TABLE `lupo_crafty_syntax_chat_mod_departments` ADD COLUMN IF NOT EXISTS `crafty_syntax_chat_mod_department_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_crafty_syntax_chat_mod_departments` ADD COLUMN IF NOT EXISTS `department_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_crafty_syntax_chat_mod_departments` ADD COLUMN IF NOT EXISTS `module_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_crafty_syntax_chat_mod_departments` ADD COLUMN IF NOT EXISTS `sort_order` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_crafty_syntax_chat_mod_departments` ADD COLUMN IF NOT EXISTS `is_active` tinyint(1) NOT NULL DEFAULT 1;
ALTER TABLE `lupo_crafty_syntax_chat_mod_departments` ADD COLUMN IF NOT EXISTS `is_default` tinyint(1) NOT NULL DEFAULT 0;
ALTER TABLE `lupo_crafty_syntax_chat_mod_departments` MODIFY COLUMN `crafty_syntax_chat_mod_department_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_crafty_syntax_chat_mod_departments` MODIFY COLUMN `department_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_crafty_syntax_chat_mod_departments` MODIFY COLUMN `module_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_crafty_syntax_chat_mod_departments` MODIFY COLUMN `sort_order` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_crafty_syntax_chat_mod_departments` MODIFY COLUMN `is_active` tinyint(1) NOT NULL DEFAULT 1;
ALTER TABLE `lupo_crafty_syntax_chat_mod_departments` MODIFY COLUMN `is_default` tinyint(1) NOT NULL DEFAULT 0;
ALTER TABLE `lupo_crafty_syntax_chat_mod_departments` ADD PRIMARY KEY (`crafty_syntax_chat_mod_department_id`);

CREATE TABLE IF NOT EXISTS `lupo_crafty_syntax_chat_questions` (
  `crafty_syntax_chat_question_id` bigint NOT NULL auto_increment,
  `department_id` bigint NOT NULL DEFAULT 0,
  `sort_order` int NOT NULL DEFAULT 0,
  `headertext` mediumtext,
  `field_type` varchar(60),
  `options` mediumtext,
  `flags` varchar(255),
  `module_name` varchar(100),
  `is_required` tinyint NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (`crafty_syntax_chat_question_id`),
  KEY `department` (`department_id`)
);

ALTER TABLE `lupo_crafty_syntax_chat_questions` ADD COLUMN IF NOT EXISTS `crafty_syntax_chat_question_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_crafty_syntax_chat_questions` ADD COLUMN IF NOT EXISTS `department_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_crafty_syntax_chat_questions` ADD COLUMN IF NOT EXISTS `sort_order` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_crafty_syntax_chat_questions` ADD COLUMN IF NOT EXISTS `headertext` mediumtext;
ALTER TABLE `lupo_crafty_syntax_chat_questions` ADD COLUMN IF NOT EXISTS `field_type` varchar(60);
ALTER TABLE `lupo_crafty_syntax_chat_questions` ADD COLUMN IF NOT EXISTS `options` mediumtext;
ALTER TABLE `lupo_crafty_syntax_chat_questions` ADD COLUMN IF NOT EXISTS `flags` varchar(255);
ALTER TABLE `lupo_crafty_syntax_chat_questions` ADD COLUMN IF NOT EXISTS `module_name` varchar(100);
ALTER TABLE `lupo_crafty_syntax_chat_questions` ADD COLUMN IF NOT EXISTS `is_required` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_crafty_syntax_chat_questions` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_crafty_syntax_chat_questions` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_crafty_syntax_chat_questions` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint(1) NOT NULL DEFAULT 0;
ALTER TABLE `lupo_crafty_syntax_chat_questions` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint;
ALTER TABLE `lupo_crafty_syntax_chat_questions` MODIFY COLUMN `crafty_syntax_chat_question_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_crafty_syntax_chat_questions` MODIFY COLUMN `department_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_crafty_syntax_chat_questions` MODIFY COLUMN `sort_order` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_crafty_syntax_chat_questions` MODIFY COLUMN `headertext` mediumtext;
ALTER TABLE `lupo_crafty_syntax_chat_questions` MODIFY COLUMN `field_type` varchar(60);
ALTER TABLE `lupo_crafty_syntax_chat_questions` MODIFY COLUMN `options` mediumtext;
ALTER TABLE `lupo_crafty_syntax_chat_questions` MODIFY COLUMN `flags` varchar(255);
ALTER TABLE `lupo_crafty_syntax_chat_questions` MODIFY COLUMN `module_name` varchar(100);
ALTER TABLE `lupo_crafty_syntax_chat_questions` MODIFY COLUMN `is_required` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_crafty_syntax_chat_questions` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_crafty_syntax_chat_questions` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_crafty_syntax_chat_questions` MODIFY COLUMN `is_deleted` tinyint(1) NOT NULL DEFAULT 0;
ALTER TABLE `lupo_crafty_syntax_chat_questions` MODIFY COLUMN `deleted_ymdhis` bigint;
ALTER TABLE `lupo_crafty_syntax_chat_questions` ADD PRIMARY KEY (`crafty_syntax_chat_question_id`);
ALTER TABLE `lupo_crafty_syntax_chat_questions` ADD INDEX IF NOT EXISTS `department` (`department_id`);

CREATE TABLE IF NOT EXISTS `lupo_crafty_syntax_layer_invites` (
  `crafty_syntax_layer_invite_id` bigint NOT NULL auto_increment COMMENT 'Unique identifier for the layer invitation',
  `layer_name` varchar(100) NOT NULL DEFAULT '' COMMENT 'Name/identifier for this layer invitation',
  `image_name` varchar(255) NOT NULL DEFAULT '' COMMENT 'Filename of the image used for this layer',
  `image_map` text COMMENT 'HTML image map coordinates and links in JSON format',
  `department_name` varchar(100) NOT NULL DEFAULT '' COMMENT 'Name of the department this layer is associated with',
  `user_id` bigint NOT NULL DEFAULT 0 COMMENT 'User ID who created this layer invitation (0 = system)',
  `is_active` tinyint NOT NULL DEFAULT 1 COMMENT 'Whether this layer is active: 0=inactive, 1=active',
  `display_count` int NOT NULL DEFAULT 0 COMMENT 'Number of times this layer has been displayed',
  `click_count` int NOT NULL DEFAULT 0 COMMENT 'Number of times this layer has been clicked',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when record was created',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when record was last updated',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag: 0=active, 1=deleted',
  `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when record was soft-deleted',
  PRIMARY KEY (`crafty_syntax_layer_invite_id`),
  KEY `idx_active` (`is_active`),
  KEY `idx_created` (`created_ymdhis`),
  KEY `idx_department` (`department_name`),
  KEY `idx_name` (`layer_name`),
  KEY `idx_updated` (`updated_ymdhis`),
  KEY `idx_user` (`user_id`)
);

ALTER TABLE `lupo_crafty_syntax_layer_invites` ADD COLUMN IF NOT EXISTS `crafty_syntax_layer_invite_id` bigint NOT NULL auto_increment COMMENT 'Unique identifier for the layer invitation';
ALTER TABLE `lupo_crafty_syntax_layer_invites` ADD COLUMN IF NOT EXISTS `layer_name` varchar(100) NOT NULL DEFAULT '' COMMENT 'Name/identifier for this layer invitation';
ALTER TABLE `lupo_crafty_syntax_layer_invites` ADD COLUMN IF NOT EXISTS `image_name` varchar(255) NOT NULL DEFAULT '' COMMENT 'Filename of the image used for this layer';
ALTER TABLE `lupo_crafty_syntax_layer_invites` ADD COLUMN IF NOT EXISTS `image_map` text COMMENT 'HTML image map coordinates and links in JSON format';
ALTER TABLE `lupo_crafty_syntax_layer_invites` ADD COLUMN IF NOT EXISTS `department_name` varchar(100) NOT NULL DEFAULT '' COMMENT 'Name of the department this layer is associated with';
ALTER TABLE `lupo_crafty_syntax_layer_invites` ADD COLUMN IF NOT EXISTS `user_id` bigint NOT NULL DEFAULT 0 COMMENT 'User ID who created this layer invitation (0 = system)';
ALTER TABLE `lupo_crafty_syntax_layer_invites` ADD COLUMN IF NOT EXISTS `is_active` tinyint NOT NULL DEFAULT 1 COMMENT 'Whether this layer is active: 0=inactive, 1=active';
ALTER TABLE `lupo_crafty_syntax_layer_invites` ADD COLUMN IF NOT EXISTS `display_count` int NOT NULL DEFAULT 0 COMMENT 'Number of times this layer has been displayed';
ALTER TABLE `lupo_crafty_syntax_layer_invites` ADD COLUMN IF NOT EXISTS `click_count` int NOT NULL DEFAULT 0 COMMENT 'Number of times this layer has been clicked';
ALTER TABLE `lupo_crafty_syntax_layer_invites` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when record was created';
ALTER TABLE `lupo_crafty_syntax_layer_invites` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when record was last updated';
ALTER TABLE `lupo_crafty_syntax_layer_invites` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag: 0=active, 1=deleted';
ALTER TABLE `lupo_crafty_syntax_layer_invites` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when record was soft-deleted';
ALTER TABLE `lupo_crafty_syntax_layer_invites` MODIFY COLUMN `crafty_syntax_layer_invite_id` bigint NOT NULL auto_increment COMMENT 'Unique identifier for the layer invitation';
ALTER TABLE `lupo_crafty_syntax_layer_invites` MODIFY COLUMN `layer_name` varchar(100) NOT NULL DEFAULT '' COMMENT 'Name/identifier for this layer invitation';
ALTER TABLE `lupo_crafty_syntax_layer_invites` MODIFY COLUMN `image_name` varchar(255) NOT NULL DEFAULT '' COMMENT 'Filename of the image used for this layer';
ALTER TABLE `lupo_crafty_syntax_layer_invites` MODIFY COLUMN `image_map` text COMMENT 'HTML image map coordinates and links in JSON format';
ALTER TABLE `lupo_crafty_syntax_layer_invites` MODIFY COLUMN `department_name` varchar(100) NOT NULL DEFAULT '' COMMENT 'Name of the department this layer is associated with';
ALTER TABLE `lupo_crafty_syntax_layer_invites` MODIFY COLUMN `user_id` bigint NOT NULL DEFAULT 0 COMMENT 'User ID who created this layer invitation (0 = system)';
ALTER TABLE `lupo_crafty_syntax_layer_invites` MODIFY COLUMN `is_active` tinyint NOT NULL DEFAULT 1 COMMENT 'Whether this layer is active: 0=inactive, 1=active';
ALTER TABLE `lupo_crafty_syntax_layer_invites` MODIFY COLUMN `display_count` int NOT NULL DEFAULT 0 COMMENT 'Number of times this layer has been displayed';
ALTER TABLE `lupo_crafty_syntax_layer_invites` MODIFY COLUMN `click_count` int NOT NULL DEFAULT 0 COMMENT 'Number of times this layer has been clicked';
ALTER TABLE `lupo_crafty_syntax_layer_invites` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when record was created';
ALTER TABLE `lupo_crafty_syntax_layer_invites` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when record was last updated';
ALTER TABLE `lupo_crafty_syntax_layer_invites` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag: 0=active, 1=deleted';
ALTER TABLE `lupo_crafty_syntax_layer_invites` MODIFY COLUMN `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when record was soft-deleted';
ALTER TABLE `lupo_crafty_syntax_layer_invites` ADD PRIMARY KEY (`crafty_syntax_layer_invite_id`);
ALTER TABLE `lupo_crafty_syntax_layer_invites` ADD INDEX IF NOT EXISTS `idx_active` (`is_active`);
ALTER TABLE `lupo_crafty_syntax_layer_invites` ADD INDEX IF NOT EXISTS `idx_created` (`created_ymdhis`);
ALTER TABLE `lupo_crafty_syntax_layer_invites` ADD INDEX IF NOT EXISTS `idx_department` (`department_name`);
ALTER TABLE `lupo_crafty_syntax_layer_invites` ADD INDEX IF NOT EXISTS `idx_name` (`layer_name`);
ALTER TABLE `lupo_crafty_syntax_layer_invites` ADD INDEX IF NOT EXISTS `idx_updated` (`updated_ymdhis`);
ALTER TABLE `lupo_crafty_syntax_layer_invites` ADD INDEX IF NOT EXISTS `idx_user` (`user_id`);

CREATE TABLE IF NOT EXISTS `lupo_crafty_syntax_leave_message` (
  `crafty_syntax_leave_message_id` bigint NOT NULL auto_increment,
  `department_id` bigint NOT NULL DEFAULT 0,
  `email` varchar(255) NOT NULL DEFAULT '',
  `phone` varchar(45),
  `name` varchar(200),
  `subject` varchar(255) NOT NULL DEFAULT '',
  `message` text,
  `priority` tinyint NOT NULL DEFAULT 2,
  `session_data` text,
  `form_data` text,
  `ip_address` varchar(45),
  `user_agent` varchar(255),
  `status` enum('new','in_progress','resolved','spam') NOT NULL DEFAULT 'new',
  `assigned_to` bigint,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (`crafty_syntax_leave_message_id`),
  KEY `idx_assigned` (`assigned_to`),
  KEY `idx_created` (`created_ymdhis`),
  KEY `idx_department` (`department_id`),
  KEY `idx_email` (`email`),
  KEY `idx_message_search` (`email`, `name`, `subject`, `message`),
  KEY `idx_priority` (`priority`),
  KEY `idx_status` (`status`)
);

ALTER TABLE `lupo_crafty_syntax_leave_message` ADD COLUMN IF NOT EXISTS `crafty_syntax_leave_message_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_crafty_syntax_leave_message` ADD COLUMN IF NOT EXISTS `department_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_crafty_syntax_leave_message` ADD COLUMN IF NOT EXISTS `email` varchar(255) NOT NULL DEFAULT '';
ALTER TABLE `lupo_crafty_syntax_leave_message` ADD COLUMN IF NOT EXISTS `phone` varchar(45);
ALTER TABLE `lupo_crafty_syntax_leave_message` ADD COLUMN IF NOT EXISTS `name` varchar(200);
ALTER TABLE `lupo_crafty_syntax_leave_message` ADD COLUMN IF NOT EXISTS `subject` varchar(255) NOT NULL DEFAULT '';
ALTER TABLE `lupo_crafty_syntax_leave_message` ADD COLUMN IF NOT EXISTS `message` text;
ALTER TABLE `lupo_crafty_syntax_leave_message` ADD COLUMN IF NOT EXISTS `priority` tinyint NOT NULL DEFAULT 2;
ALTER TABLE `lupo_crafty_syntax_leave_message` ADD COLUMN IF NOT EXISTS `session_data` text;
ALTER TABLE `lupo_crafty_syntax_leave_message` ADD COLUMN IF NOT EXISTS `form_data` text;
ALTER TABLE `lupo_crafty_syntax_leave_message` ADD COLUMN IF NOT EXISTS `ip_address` varchar(45);
ALTER TABLE `lupo_crafty_syntax_leave_message` ADD COLUMN IF NOT EXISTS `user_agent` varchar(255);
ALTER TABLE `lupo_crafty_syntax_leave_message` ADD COLUMN IF NOT EXISTS `status` enum('new','in_progress','resolved','spam') NOT NULL DEFAULT 'new';
ALTER TABLE `lupo_crafty_syntax_leave_message` ADD COLUMN IF NOT EXISTS `assigned_to` bigint;
ALTER TABLE `lupo_crafty_syntax_leave_message` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_crafty_syntax_leave_message` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_crafty_syntax_leave_message` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_crafty_syntax_leave_message` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint;
ALTER TABLE `lupo_crafty_syntax_leave_message` MODIFY COLUMN `crafty_syntax_leave_message_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_crafty_syntax_leave_message` MODIFY COLUMN `department_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_crafty_syntax_leave_message` MODIFY COLUMN `email` varchar(255) NOT NULL DEFAULT '';
ALTER TABLE `lupo_crafty_syntax_leave_message` MODIFY COLUMN `phone` varchar(45);
ALTER TABLE `lupo_crafty_syntax_leave_message` MODIFY COLUMN `name` varchar(200);
ALTER TABLE `lupo_crafty_syntax_leave_message` MODIFY COLUMN `subject` varchar(255) NOT NULL DEFAULT '';
ALTER TABLE `lupo_crafty_syntax_leave_message` MODIFY COLUMN `message` text;
ALTER TABLE `lupo_crafty_syntax_leave_message` MODIFY COLUMN `priority` tinyint NOT NULL DEFAULT 2;
ALTER TABLE `lupo_crafty_syntax_leave_message` MODIFY COLUMN `session_data` text;
ALTER TABLE `lupo_crafty_syntax_leave_message` MODIFY COLUMN `form_data` text;
ALTER TABLE `lupo_crafty_syntax_leave_message` MODIFY COLUMN `ip_address` varchar(45);
ALTER TABLE `lupo_crafty_syntax_leave_message` MODIFY COLUMN `user_agent` varchar(255);
ALTER TABLE `lupo_crafty_syntax_leave_message` MODIFY COLUMN `status` enum('new','in_progress','resolved','spam') NOT NULL DEFAULT 'new';
ALTER TABLE `lupo_crafty_syntax_leave_message` MODIFY COLUMN `assigned_to` bigint;
ALTER TABLE `lupo_crafty_syntax_leave_message` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_crafty_syntax_leave_message` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_crafty_syntax_leave_message` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_crafty_syntax_leave_message` MODIFY COLUMN `deleted_ymdhis` bigint;
ALTER TABLE `lupo_crafty_syntax_leave_message` ADD PRIMARY KEY (`crafty_syntax_leave_message_id`);
ALTER TABLE `lupo_crafty_syntax_leave_message` ADD INDEX IF NOT EXISTS `idx_assigned` (`assigned_to`);
ALTER TABLE `lupo_crafty_syntax_leave_message` ADD INDEX IF NOT EXISTS `idx_created` (`created_ymdhis`);
ALTER TABLE `lupo_crafty_syntax_leave_message` ADD INDEX IF NOT EXISTS `idx_department` (`department_id`);
ALTER TABLE `lupo_crafty_syntax_leave_message` ADD INDEX IF NOT EXISTS `idx_email` (`email`);
ALTER TABLE `lupo_crafty_syntax_leave_message` ADD INDEX IF NOT EXISTS `idx_message_search` (`email`, `name`, `subject`, `message`);
ALTER TABLE `lupo_crafty_syntax_leave_message` ADD INDEX IF NOT EXISTS `idx_priority` (`priority`);
ALTER TABLE `lupo_crafty_syntax_leave_message` ADD INDEX IF NOT EXISTS `idx_status` (`status`);

CREATE TABLE IF NOT EXISTS `lupo_crafty_user_mapping` (
  `id` bigint NOT NULL auto_increment COMMENT 'Primary key for mapping',
  `lupo_user_id` bigint COMMENT 'Reference to lupo_auth_users.auth_user_id',
  `crafty_operator_id` int COMMENT 'Reference to livehelp_operators.operatorid',
  `mapping_type` varchar(50) NOT NULL DEFAULT 'manual' COMMENT 'Type: manual, auto, imported',
  `notes` text COMMENT 'Optional notes for mapping',
  `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint',
  `updated_ymdhis` bigint NOT NULL COMMENT 'Last update bigint',
  PRIMARY KEY (`id`),
  KEY `idx_crafty_operator_id` (`crafty_operator_id`),
  KEY `idx_lupo_user_id` (`lupo_user_id`),
  KEY `idx_mapping_type` (`mapping_type`),
  UNIQUE KEY `unique_crafty_operator_mapping` (`crafty_operator_id`),
  UNIQUE KEY `unique_lupo_user_mapping` (`lupo_user_id`)
);

ALTER TABLE `lupo_crafty_user_mapping` ADD COLUMN IF NOT EXISTS `id` bigint NOT NULL auto_increment COMMENT 'Primary key for mapping';
ALTER TABLE `lupo_crafty_user_mapping` ADD COLUMN IF NOT EXISTS `lupo_user_id` bigint COMMENT 'Reference to lupo_auth_users.auth_user_id';
ALTER TABLE `lupo_crafty_user_mapping` ADD COLUMN IF NOT EXISTS `crafty_operator_id` int COMMENT 'Reference to livehelp_operators.operatorid';
ALTER TABLE `lupo_crafty_user_mapping` ADD COLUMN IF NOT EXISTS `mapping_type` varchar(50) NOT NULL DEFAULT 'manual' COMMENT 'Type: manual, auto, imported';
ALTER TABLE `lupo_crafty_user_mapping` ADD COLUMN IF NOT EXISTS `notes` text COMMENT 'Optional notes for mapping';
ALTER TABLE `lupo_crafty_user_mapping` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint';
ALTER TABLE `lupo_crafty_user_mapping` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL COMMENT 'Last update bigint';
ALTER TABLE `lupo_crafty_user_mapping` MODIFY COLUMN `id` bigint NOT NULL auto_increment COMMENT 'Primary key for mapping';
ALTER TABLE `lupo_crafty_user_mapping` MODIFY COLUMN `lupo_user_id` bigint COMMENT 'Reference to lupo_auth_users.auth_user_id';
ALTER TABLE `lupo_crafty_user_mapping` MODIFY COLUMN `crafty_operator_id` int COMMENT 'Reference to livehelp_operators.operatorid';
ALTER TABLE `lupo_crafty_user_mapping` MODIFY COLUMN `mapping_type` varchar(50) NOT NULL DEFAULT 'manual' COMMENT 'Type: manual, auto, imported';
ALTER TABLE `lupo_crafty_user_mapping` MODIFY COLUMN `notes` text COMMENT 'Optional notes for mapping';
ALTER TABLE `lupo_crafty_user_mapping` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint';
ALTER TABLE `lupo_crafty_user_mapping` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL COMMENT 'Last update bigint';
ALTER TABLE `lupo_crafty_user_mapping` ADD PRIMARY KEY (`id`);
ALTER TABLE `lupo_crafty_user_mapping` ADD INDEX IF NOT EXISTS `idx_crafty_operator_id` (`crafty_operator_id`);
ALTER TABLE `lupo_crafty_user_mapping` ADD INDEX IF NOT EXISTS `idx_lupo_user_id` (`lupo_user_id`);
ALTER TABLE `lupo_crafty_user_mapping` ADD INDEX IF NOT EXISTS `idx_mapping_type` (`mapping_type`);
ALTER TABLE `lupo_crafty_user_mapping` ADD UNIQUE INDEX IF NOT EXISTS `unique_crafty_operator_mapping` (`crafty_operator_id`);
ALTER TABLE `lupo_crafty_user_mapping` ADD UNIQUE INDEX IF NOT EXISTS `unique_lupo_user_mapping` (`lupo_user_id`);

CREATE TABLE IF NOT EXISTS `lupo_crm_lead_messages` (
  `crm_lead_message_id` bigint NOT NULL auto_increment,
  `lead_id` bigint,
  `from_email` varchar(255),
  `subject` varchar(255),
  `body_text` text NOT NULL,
  `notes` varchar(255),
  `actor_id` bigint,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` smallint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (`crm_lead_message_id`),
  KEY `actor_id` (`actor_id`),
  KEY `lead_id` (`lead_id`)
);

ALTER TABLE `lupo_crm_lead_messages` ADD COLUMN IF NOT EXISTS `crm_lead_message_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_crm_lead_messages` ADD COLUMN IF NOT EXISTS `lead_id` bigint;
ALTER TABLE `lupo_crm_lead_messages` ADD COLUMN IF NOT EXISTS `from_email` varchar(255);
ALTER TABLE `lupo_crm_lead_messages` ADD COLUMN IF NOT EXISTS `subject` varchar(255);
ALTER TABLE `lupo_crm_lead_messages` ADD COLUMN IF NOT EXISTS `body_text` text NOT NULL;
ALTER TABLE `lupo_crm_lead_messages` ADD COLUMN IF NOT EXISTS `notes` varchar(255);
ALTER TABLE `lupo_crm_lead_messages` ADD COLUMN IF NOT EXISTS `actor_id` bigint;
ALTER TABLE `lupo_crm_lead_messages` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_crm_lead_messages` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_crm_lead_messages` ADD COLUMN IF NOT EXISTS `is_deleted` smallint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_crm_lead_messages` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint;
ALTER TABLE `lupo_crm_lead_messages` MODIFY COLUMN `crm_lead_message_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_crm_lead_messages` MODIFY COLUMN `lead_id` bigint;
ALTER TABLE `lupo_crm_lead_messages` MODIFY COLUMN `from_email` varchar(255);
ALTER TABLE `lupo_crm_lead_messages` MODIFY COLUMN `subject` varchar(255);
ALTER TABLE `lupo_crm_lead_messages` MODIFY COLUMN `body_text` text NOT NULL;
ALTER TABLE `lupo_crm_lead_messages` MODIFY COLUMN `notes` varchar(255);
ALTER TABLE `lupo_crm_lead_messages` MODIFY COLUMN `actor_id` bigint;
ALTER TABLE `lupo_crm_lead_messages` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_crm_lead_messages` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_crm_lead_messages` MODIFY COLUMN `is_deleted` smallint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_crm_lead_messages` MODIFY COLUMN `deleted_ymdhis` bigint;
ALTER TABLE `lupo_crm_lead_messages` ADD PRIMARY KEY (`crm_lead_message_id`);
ALTER TABLE `lupo_crm_lead_messages` ADD INDEX IF NOT EXISTS `actor_id` (`actor_id`);
ALTER TABLE `lupo_crm_lead_messages` ADD INDEX IF NOT EXISTS `lead_id` (`lead_id`);

CREATE TABLE IF NOT EXISTS `lupo_crm_leads` (
  `crm_lead_id` bigint NOT NULL auto_increment COMMENT 'Unique identifier for the lead',
  `email` varchar(255) COMMENT 'Email address of the lead',
  `phone` varchar(45) COMMENT 'Phone number of the lead (formatted as E.164)',
  `first_name` varchar(100) COMMENT 'First name of the lead',
  `last_name` varchar(100) COMMENT 'Last name of the lead',
  `source` varchar(100) COMMENT 'Source that generated this lead (e.g., website, referral, campaign)',
  `status` varchar(50) NOT NULL DEFAULT 'new' COMMENT 'Current status of the lead (new, contacted, qualified, converted, etc.)',
  `lead_score` int NOT NULL DEFAULT 0 COMMENT 'Numerical score indicating lead quality (0-100)',
  `assigned_to` bigint COMMENT 'user ID of the team member assigned to this lead',
  `lead_data` longtext COMMENT 'JSON-encoded additional lead information and custom fields',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC bigint when the lead was created (YYYYMMDDHHMMSS)',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC bigint when the lead was last updated (YYYYMMDDHHMMSS)',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1 = deleted, 0 = active)',
  `deleted_ymdhis` bigint COMMENT 'UTC bigint when the lead was soft-deleted (YYYYMMDDHHMMSS)',
  PRIMARY KEY (`crm_lead_id`)
);

ALTER TABLE `lupo_crm_leads` ADD COLUMN IF NOT EXISTS `crm_lead_id` bigint NOT NULL auto_increment COMMENT 'Unique identifier for the lead';
ALTER TABLE `lupo_crm_leads` ADD COLUMN IF NOT EXISTS `email` varchar(255) COMMENT 'Email address of the lead';
ALTER TABLE `lupo_crm_leads` ADD COLUMN IF NOT EXISTS `phone` varchar(45) COMMENT 'Phone number of the lead (formatted as E.164)';
ALTER TABLE `lupo_crm_leads` ADD COLUMN IF NOT EXISTS `first_name` varchar(100) COMMENT 'First name of the lead';
ALTER TABLE `lupo_crm_leads` ADD COLUMN IF NOT EXISTS `last_name` varchar(100) COMMENT 'Last name of the lead';
ALTER TABLE `lupo_crm_leads` ADD COLUMN IF NOT EXISTS `source` varchar(100) COMMENT 'Source that generated this lead (e.g., website, referral, campaign)';
ALTER TABLE `lupo_crm_leads` ADD COLUMN IF NOT EXISTS `status` varchar(50) NOT NULL DEFAULT 'new' COMMENT 'Current status of the lead (new, contacted, qualified, converted, etc.)';
ALTER TABLE `lupo_crm_leads` ADD COLUMN IF NOT EXISTS `lead_score` int NOT NULL DEFAULT 0 COMMENT 'Numerical score indicating lead quality (0-100)';
ALTER TABLE `lupo_crm_leads` ADD COLUMN IF NOT EXISTS `assigned_to` bigint COMMENT 'user ID of the team member assigned to this lead';
ALTER TABLE `lupo_crm_leads` ADD COLUMN IF NOT EXISTS `lead_data` longtext COMMENT 'JSON-encoded additional lead information and custom fields';
ALTER TABLE `lupo_crm_leads` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'UTC bigint when the lead was created (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_crm_leads` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL COMMENT 'UTC bigint when the lead was last updated (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_crm_leads` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1 = deleted, 0 = active)';
ALTER TABLE `lupo_crm_leads` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint COMMENT 'UTC bigint when the lead was soft-deleted (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_crm_leads` MODIFY COLUMN `crm_lead_id` bigint NOT NULL auto_increment COMMENT 'Unique identifier for the lead';
ALTER TABLE `lupo_crm_leads` MODIFY COLUMN `email` varchar(255) COMMENT 'Email address of the lead';
ALTER TABLE `lupo_crm_leads` MODIFY COLUMN `phone` varchar(45) COMMENT 'Phone number of the lead (formatted as E.164)';
ALTER TABLE `lupo_crm_leads` MODIFY COLUMN `first_name` varchar(100) COMMENT 'First name of the lead';
ALTER TABLE `lupo_crm_leads` MODIFY COLUMN `last_name` varchar(100) COMMENT 'Last name of the lead';
ALTER TABLE `lupo_crm_leads` MODIFY COLUMN `source` varchar(100) COMMENT 'Source that generated this lead (e.g., website, referral, campaign)';
ALTER TABLE `lupo_crm_leads` MODIFY COLUMN `status` varchar(50) NOT NULL DEFAULT 'new' COMMENT 'Current status of the lead (new, contacted, qualified, converted, etc.)';
ALTER TABLE `lupo_crm_leads` MODIFY COLUMN `lead_score` int NOT NULL DEFAULT 0 COMMENT 'Numerical score indicating lead quality (0-100)';
ALTER TABLE `lupo_crm_leads` MODIFY COLUMN `assigned_to` bigint COMMENT 'user ID of the team member assigned to this lead';
ALTER TABLE `lupo_crm_leads` MODIFY COLUMN `lead_data` longtext COMMENT 'JSON-encoded additional lead information and custom fields';
ALTER TABLE `lupo_crm_leads` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'UTC bigint when the lead was created (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_crm_leads` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL COMMENT 'UTC bigint when the lead was last updated (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_crm_leads` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1 = deleted, 0 = active)';
ALTER TABLE `lupo_crm_leads` MODIFY COLUMN `deleted_ymdhis` bigint COMMENT 'UTC bigint when the lead was soft-deleted (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_crm_leads` ADD PRIMARY KEY (`crm_lead_id`);

CREATE TABLE IF NOT EXISTS `lupo_departments` (
  `department_id` bigint NOT NULL auto_increment,
  `federation_node_id` bigint NOT NULL,
  `name` varchar(64) NOT NULL,
  `description` text,
  `department_type` varchar(32) NOT NULL DEFAULT 'general',
  `default_actor_id` bigint NOT NULL DEFAULT 1,
  `settings_json` json,
  `created_ymdhis` char(14) NOT NULL,
  `updated_ymdhis` char(14) NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` char(14),
  PRIMARY KEY (`department_id`),
  KEY `idx_federation_node` (`federation_node_id`),
  KEY `idx_name` (`name`),
  KEY `idx_type` (`department_type`)
);

ALTER TABLE `lupo_departments` ADD COLUMN IF NOT EXISTS `department_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_departments` ADD COLUMN IF NOT EXISTS `federation_node_id` bigint NOT NULL;
ALTER TABLE `lupo_departments` ADD COLUMN IF NOT EXISTS `name` varchar(64) NOT NULL;
ALTER TABLE `lupo_departments` ADD COLUMN IF NOT EXISTS `description` text;
ALTER TABLE `lupo_departments` ADD COLUMN IF NOT EXISTS `department_type` varchar(32) NOT NULL DEFAULT 'general';
ALTER TABLE `lupo_departments` ADD COLUMN IF NOT EXISTS `default_actor_id` bigint NOT NULL DEFAULT 1;
ALTER TABLE `lupo_departments` ADD COLUMN IF NOT EXISTS `settings_json` json;
ALTER TABLE `lupo_departments` ADD COLUMN IF NOT EXISTS `created_ymdhis` char(14) NOT NULL;
ALTER TABLE `lupo_departments` ADD COLUMN IF NOT EXISTS `updated_ymdhis` char(14) NOT NULL;
ALTER TABLE `lupo_departments` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_departments` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` char(14);
ALTER TABLE `lupo_departments` MODIFY COLUMN `department_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_departments` MODIFY COLUMN `federation_node_id` bigint NOT NULL;
ALTER TABLE `lupo_departments` MODIFY COLUMN `name` varchar(64) NOT NULL;
ALTER TABLE `lupo_departments` MODIFY COLUMN `description` text;
ALTER TABLE `lupo_departments` MODIFY COLUMN `department_type` varchar(32) NOT NULL DEFAULT 'general';
ALTER TABLE `lupo_departments` MODIFY COLUMN `default_actor_id` bigint NOT NULL DEFAULT 1;
ALTER TABLE `lupo_departments` MODIFY COLUMN `settings_json` json;
ALTER TABLE `lupo_departments` MODIFY COLUMN `created_ymdhis` char(14) NOT NULL;
ALTER TABLE `lupo_departments` MODIFY COLUMN `updated_ymdhis` char(14) NOT NULL;
ALTER TABLE `lupo_departments` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_departments` MODIFY COLUMN `deleted_ymdhis` char(14);
ALTER TABLE `lupo_departments` ADD PRIMARY KEY (`department_id`);
ALTER TABLE `lupo_departments` ADD INDEX IF NOT EXISTS `idx_federation_node` (`federation_node_id`);
ALTER TABLE `lupo_departments` ADD INDEX IF NOT EXISTS `idx_name` (`name`);
ALTER TABLE `lupo_departments` ADD INDEX IF NOT EXISTS `idx_type` (`department_type`);

CREATE TABLE IF NOT EXISTS `lupo_dialog_channels` (
  `channel_id` bigint unsigned NOT NULL auto_increment,
  `channel_name` varchar(255) NOT NULL,
  `file_source` varchar(255) NOT NULL COMMENT 'Original .md filename',
  `title` varchar(500),
  `description` text,
  `speaker` varchar(100),
  `target` varchar(100),
  `mood_rgb` varchar(7) COMMENT 'Emotional polarity tensor encoded as hex (abstract axes: strife (R), harmony (G), memory depth (B); not literal RGB color channels)',
  `categories` json COMMENT 'Array of category strings',
  `collections` json COMMENT 'Array of collection strings',
  `channels` json COMMENT 'Array of channel strings',
  `tags` json COMMENT 'Additional tag metadata',
  `version` varchar(20) COMMENT 'System version when created',
  `status` enum('draft','published','archived') DEFAULT 'published',
  `author` varchar(100),
  `created_timestamp` bigint unsigned NOT NULL COMMENT 'YYYYMMDDHHIISS format',
  `modified_timestamp` bigint unsigned NOT NULL COMMENT 'YYYYMMDDHHIISS format',
  `message_count` int unsigned DEFAULT 0 COMMENT 'Cached count of messages',
  `metadata_json` json COMMENT 'Additional metadata from WOLFIE headers',
  PRIMARY KEY (`channel_id`),
  UNIQUE KEY `idx_channel_name` (`channel_name`),
  KEY `idx_created_timestamp` (`created_timestamp`),
  KEY `idx_dialog_channels_composite` (`status`, `created_timestamp`),
  KEY `idx_file_source` (`file_source`),
  KEY `idx_modified_timestamp` (`modified_timestamp`),
  KEY `idx_speaker` (`speaker`),
  KEY `idx_status` (`status`),
  KEY `idx_target` (`target`)
);

ALTER TABLE `lupo_dialog_channels` ADD COLUMN IF NOT EXISTS `channel_id` bigint unsigned NOT NULL auto_increment;
ALTER TABLE `lupo_dialog_channels` ADD COLUMN IF NOT EXISTS `channel_name` varchar(255) NOT NULL;
ALTER TABLE `lupo_dialog_channels` ADD COLUMN IF NOT EXISTS `file_source` varchar(255) NOT NULL COMMENT 'Original .md filename';
ALTER TABLE `lupo_dialog_channels` ADD COLUMN IF NOT EXISTS `title` varchar(500);
ALTER TABLE `lupo_dialog_channels` ADD COLUMN IF NOT EXISTS `description` text;
ALTER TABLE `lupo_dialog_channels` ADD COLUMN IF NOT EXISTS `speaker` varchar(100);
ALTER TABLE `lupo_dialog_channels` ADD COLUMN IF NOT EXISTS `target` varchar(100);
ALTER TABLE `lupo_dialog_channels` ADD COLUMN IF NOT EXISTS `mood_rgb` varchar(7) COMMENT 'Emotional polarity tensor encoded as hex (abstract axes: strife (R), harmony (G), memory depth (B); not literal RGB color channels)';
ALTER TABLE `lupo_dialog_channels` ADD COLUMN IF NOT EXISTS `categories` json COMMENT 'Array of category strings';
ALTER TABLE `lupo_dialog_channels` ADD COLUMN IF NOT EXISTS `collections` json COMMENT 'Array of collection strings';
ALTER TABLE `lupo_dialog_channels` ADD COLUMN IF NOT EXISTS `channels` json COMMENT 'Array of channel strings';
ALTER TABLE `lupo_dialog_channels` ADD COLUMN IF NOT EXISTS `tags` json COMMENT 'Additional tag metadata';
ALTER TABLE `lupo_dialog_channels` ADD COLUMN IF NOT EXISTS `version` varchar(20) COMMENT 'System version when created';
ALTER TABLE `lupo_dialog_channels` ADD COLUMN IF NOT EXISTS `status` enum('draft','published','archived') DEFAULT 'published';
ALTER TABLE `lupo_dialog_channels` ADD COLUMN IF NOT EXISTS `author` varchar(100);
ALTER TABLE `lupo_dialog_channels` ADD COLUMN IF NOT EXISTS `created_timestamp` bigint unsigned NOT NULL COMMENT 'YYYYMMDDHHIISS format';
ALTER TABLE `lupo_dialog_channels` ADD COLUMN IF NOT EXISTS `modified_timestamp` bigint unsigned NOT NULL COMMENT 'YYYYMMDDHHIISS format';
ALTER TABLE `lupo_dialog_channels` ADD COLUMN IF NOT EXISTS `message_count` int unsigned DEFAULT 0 COMMENT 'Cached count of messages';
ALTER TABLE `lupo_dialog_channels` ADD COLUMN IF NOT EXISTS `metadata_json` json COMMENT 'Additional metadata from WOLFIE headers';
ALTER TABLE `lupo_dialog_channels` MODIFY COLUMN `channel_id` bigint unsigned NOT NULL auto_increment;
ALTER TABLE `lupo_dialog_channels` MODIFY COLUMN `channel_name` varchar(255) NOT NULL;
ALTER TABLE `lupo_dialog_channels` MODIFY COLUMN `file_source` varchar(255) NOT NULL COMMENT 'Original .md filename';
ALTER TABLE `lupo_dialog_channels` MODIFY COLUMN `title` varchar(500);
ALTER TABLE `lupo_dialog_channels` MODIFY COLUMN `description` text;
ALTER TABLE `lupo_dialog_channels` MODIFY COLUMN `speaker` varchar(100);
ALTER TABLE `lupo_dialog_channels` MODIFY COLUMN `target` varchar(100);
ALTER TABLE `lupo_dialog_channels` MODIFY COLUMN `mood_rgb` varchar(7) COMMENT 'Emotional polarity tensor encoded as hex (abstract axes: strife (R), harmony (G), memory depth (B); not literal RGB color channels)';
ALTER TABLE `lupo_dialog_channels` MODIFY COLUMN `categories` json COMMENT 'Array of category strings';
ALTER TABLE `lupo_dialog_channels` MODIFY COLUMN `collections` json COMMENT 'Array of collection strings';
ALTER TABLE `lupo_dialog_channels` MODIFY COLUMN `channels` json COMMENT 'Array of channel strings';
ALTER TABLE `lupo_dialog_channels` MODIFY COLUMN `tags` json COMMENT 'Additional tag metadata';
ALTER TABLE `lupo_dialog_channels` MODIFY COLUMN `version` varchar(20) COMMENT 'System version when created';
ALTER TABLE `lupo_dialog_channels` MODIFY COLUMN `status` enum('draft','published','archived') DEFAULT 'published';
ALTER TABLE `lupo_dialog_channels` MODIFY COLUMN `author` varchar(100);
ALTER TABLE `lupo_dialog_channels` MODIFY COLUMN `created_timestamp` bigint unsigned NOT NULL COMMENT 'YYYYMMDDHHIISS format';
ALTER TABLE `lupo_dialog_channels` MODIFY COLUMN `modified_timestamp` bigint unsigned NOT NULL COMMENT 'YYYYMMDDHHIISS format';
ALTER TABLE `lupo_dialog_channels` MODIFY COLUMN `message_count` int unsigned DEFAULT 0 COMMENT 'Cached count of messages';
ALTER TABLE `lupo_dialog_channels` MODIFY COLUMN `metadata_json` json COMMENT 'Additional metadata from WOLFIE headers';
ALTER TABLE `lupo_dialog_channels` ADD PRIMARY KEY (`channel_id`);
ALTER TABLE `lupo_dialog_channels` ADD UNIQUE INDEX IF NOT EXISTS `idx_channel_name` (`channel_name`);
ALTER TABLE `lupo_dialog_channels` ADD INDEX IF NOT EXISTS `idx_created_timestamp` (`created_timestamp`);
ALTER TABLE `lupo_dialog_channels` ADD INDEX IF NOT EXISTS `idx_dialog_channels_composite` (`status`, `created_timestamp`);
ALTER TABLE `lupo_dialog_channels` ADD INDEX IF NOT EXISTS `idx_file_source` (`file_source`);
ALTER TABLE `lupo_dialog_channels` ADD INDEX IF NOT EXISTS `idx_modified_timestamp` (`modified_timestamp`);
ALTER TABLE `lupo_dialog_channels` ADD INDEX IF NOT EXISTS `idx_speaker` (`speaker`);
ALTER TABLE `lupo_dialog_channels` ADD INDEX IF NOT EXISTS `idx_status` (`status`);
ALTER TABLE `lupo_dialog_channels` ADD INDEX IF NOT EXISTS `idx_target` (`target`);

CREATE TABLE IF NOT EXISTS `lupo_dialog_messages` (
  `dialog_message_id` bigint NOT NULL auto_increment COMMENT 'Primary key for the dialog message',
  `dialog_thread_id` bigint COMMENT 'Optional thread grouping for related dialogs',
  `channel_id` bigint COMMENT 'Optional channel identifier',
  `from_actor_id` bigint COMMENT 'Actor ID of the message sender',
  `to_actor_id` bigint COMMENT 'Agent ID if message is from an AI agent',
  `message_text` varchar(1000) NOT NULL COMMENT 'The message under 1000 chars ',
  `message_type` enum('text','command','system','error') NOT NULL DEFAULT 'text' COMMENT 'Type of message',
  `metadata_json` json COMMENT 'Additional message metadata',
  `mood_rgb` char(6) NOT NULL DEFAULT '666666' COMMENT 'Emotional polarity tensor encoded as hex (abstract axes: strife (R), harmony (G), memory depth (B); not literal RGB color channels)',
  `weight` decimal(3,2) NOT NULL DEFAULT 0.00,
  `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint (YYYYMMDDHHMMSS)',
  `updated_ymdhis` bigint NOT NULL COMMENT 'Last update bigint (YYYYMMDDHHMMSS)',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted)',
  `deleted_ymdhis` bigint COMMENT 'Deletion bigint (YYYYMMDDHHMMSS)',
  `message_body` mediumtext COMMENT 'Full message body',
  PRIMARY KEY (`dialog_message_id`),
  KEY `idx_channel` (`channel_id`),
  KEY `idx_created` (`created_ymdhis`),
  KEY `idx_deleted` (`is_deleted`),
  KEY `idx_dialog_thread_id` (`dialog_thread_id`),
  KEY `idx_message_type` (`message_type`),
  KEY `idx_to_actor_id` (`to_actor_id`),
  KEY `idx_updated` (`updated_ymdhis`)
);

ALTER TABLE `lupo_dialog_messages` ADD COLUMN IF NOT EXISTS `dialog_message_id` bigint NOT NULL auto_increment COMMENT 'Primary key for the dialog message';
ALTER TABLE `lupo_dialog_messages` ADD COLUMN IF NOT EXISTS `dialog_thread_id` bigint COMMENT 'Optional thread grouping for related dialogs';
ALTER TABLE `lupo_dialog_messages` ADD COLUMN IF NOT EXISTS `channel_id` bigint COMMENT 'Optional channel identifier';
ALTER TABLE `lupo_dialog_messages` ADD COLUMN IF NOT EXISTS `from_actor_id` bigint COMMENT 'Actor ID of the message sender';
ALTER TABLE `lupo_dialog_messages` ADD COLUMN IF NOT EXISTS `to_actor_id` bigint COMMENT 'Agent ID if message is from an AI agent';
ALTER TABLE `lupo_dialog_messages` ADD COLUMN IF NOT EXISTS `message_text` varchar(1000) NOT NULL COMMENT 'The message under 1000 chars ';
ALTER TABLE `lupo_dialog_messages` ADD COLUMN IF NOT EXISTS `message_type` enum('text','command','system','error') NOT NULL DEFAULT 'text' COMMENT 'Type of message';
ALTER TABLE `lupo_dialog_messages` ADD COLUMN IF NOT EXISTS `metadata_json` json COMMENT 'Additional message metadata';
ALTER TABLE `lupo_dialog_messages` ADD COLUMN IF NOT EXISTS `mood_rgb` char(6) NOT NULL DEFAULT '666666' COMMENT 'Emotional polarity tensor encoded as hex (abstract axes: strife (R), harmony (G), memory depth (B); not literal RGB color channels)';
ALTER TABLE `lupo_dialog_messages` ADD COLUMN IF NOT EXISTS `weight` decimal(3,2) NOT NULL DEFAULT 0.00;
ALTER TABLE `lupo_dialog_messages` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_dialog_messages` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL COMMENT 'Last update bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_dialog_messages` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted)';
ALTER TABLE `lupo_dialog_messages` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint COMMENT 'Deletion bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_dialog_messages` ADD COLUMN IF NOT EXISTS `message_body` mediumtext COMMENT 'Full message body';
ALTER TABLE `lupo_dialog_messages` MODIFY COLUMN `dialog_message_id` bigint NOT NULL auto_increment COMMENT 'Primary key for the dialog message';
ALTER TABLE `lupo_dialog_messages` MODIFY COLUMN `dialog_thread_id` bigint COMMENT 'Optional thread grouping for related dialogs';
ALTER TABLE `lupo_dialog_messages` MODIFY COLUMN `channel_id` bigint COMMENT 'Optional channel identifier';
ALTER TABLE `lupo_dialog_messages` MODIFY COLUMN `from_actor_id` bigint COMMENT 'Actor ID of the message sender';
ALTER TABLE `lupo_dialog_messages` MODIFY COLUMN `to_actor_id` bigint COMMENT 'Agent ID if message is from an AI agent';
ALTER TABLE `lupo_dialog_messages` MODIFY COLUMN `message_text` varchar(1000) NOT NULL COMMENT 'The message under 1000 chars ';
ALTER TABLE `lupo_dialog_messages` MODIFY COLUMN `message_type` enum('text','command','system','error') NOT NULL DEFAULT 'text' COMMENT 'Type of message';
ALTER TABLE `lupo_dialog_messages` MODIFY COLUMN `metadata_json` json COMMENT 'Additional message metadata';
ALTER TABLE `lupo_dialog_messages` MODIFY COLUMN `mood_rgb` char(6) NOT NULL DEFAULT '666666' COMMENT 'Emotional polarity tensor encoded as hex (abstract axes: strife (R), harmony (G), memory depth (B); not literal RGB color channels)';
ALTER TABLE `lupo_dialog_messages` MODIFY COLUMN `weight` decimal(3,2) NOT NULL DEFAULT 0.00;
ALTER TABLE `lupo_dialog_messages` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_dialog_messages` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL COMMENT 'Last update bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_dialog_messages` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted)';
ALTER TABLE `lupo_dialog_messages` MODIFY COLUMN `deleted_ymdhis` bigint COMMENT 'Deletion bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_dialog_messages` MODIFY COLUMN `message_body` mediumtext COMMENT 'Full message body';
ALTER TABLE `lupo_dialog_messages` ADD PRIMARY KEY (`dialog_message_id`);
ALTER TABLE `lupo_dialog_messages` ADD INDEX IF NOT EXISTS `idx_channel` (`channel_id`);
ALTER TABLE `lupo_dialog_messages` ADD INDEX IF NOT EXISTS `idx_created` (`created_ymdhis`);
ALTER TABLE `lupo_dialog_messages` ADD INDEX IF NOT EXISTS `idx_deleted` (`is_deleted`);
ALTER TABLE `lupo_dialog_messages` ADD INDEX IF NOT EXISTS `idx_dialog_thread_id` (`dialog_thread_id`);
ALTER TABLE `lupo_dialog_messages` ADD INDEX IF NOT EXISTS `idx_message_type` (`message_type`);
ALTER TABLE `lupo_dialog_messages` ADD INDEX IF NOT EXISTS `idx_to_actor_id` (`to_actor_id`);
ALTER TABLE `lupo_dialog_messages` ADD INDEX IF NOT EXISTS `idx_updated` (`updated_ymdhis`);

CREATE TABLE IF NOT EXISTS `lupo_dialog_threads` (
  `dialog_thread_id` bigint NOT NULL auto_increment COMMENT 'Primary key for the dialog thread',
  `federation_node_id` bigint NOT NULL DEFAULT 1 COMMENT 'Node that owns this thread; default is local installation (1)',
  `channel_id` bigint COMMENT 'Optional channel identifier for grouping threads',
  `project_slug` varchar(100) COMMENT 'Project or subsystem this thread belongs to',
  `task_name` varchar(255) COMMENT 'Human-readable task name for this thread',
  `created_by_actor_id` bigint NOT NULL COMMENT 'Agent or human who initiated the thread',
  `summary_text` text COMMENT 'Short summary of the thread purpose or context',
  `status` enum('Open','Ongoing','Closed','Archived') NOT NULL DEFAULT 'Open' COMMENT 'Thread lifecycle state',
  `artifacts` json COMMENT 'Optional JSON list of related files, URLs, or resources',
  `metadata_json` json COMMENT 'Metadata: intent, scope, persona, mood, inline dialog metadata',
  `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint (YYYYMMDDHHMMSS)',
  `updated_ymdhis` bigint NOT NULL COMMENT 'Last update bigint (YYYYMMDDHHMMSS)',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted)',
  `deleted_ymdhis` bigint COMMENT 'Deletion bigint (YYYYMMDDHHMMSS)',
  PRIMARY KEY (`dialog_thread_id`),
  KEY `idx_channel` (`channel_id`),
  KEY `idx_created` (`created_ymdhis`),
  KEY `idx_created_by_actor` (`created_by_actor_id`),
  KEY `idx_deleted` (`is_deleted`),
  KEY `idx_node` (`federation_node_id`),
  KEY `idx_project` (`project_slug`),
  KEY `idx_status` (`status`),
  KEY `idx_task` (`task_name`),
  KEY `idx_updated` (`updated_ymdhis`)
);

ALTER TABLE `lupo_dialog_threads` ADD COLUMN IF NOT EXISTS `dialog_thread_id` bigint NOT NULL auto_increment COMMENT 'Primary key for the dialog thread';
ALTER TABLE `lupo_dialog_threads` ADD COLUMN IF NOT EXISTS `federation_node_id` bigint NOT NULL DEFAULT 1 COMMENT 'Node that owns this thread; default is local installation (1)';
ALTER TABLE `lupo_dialog_threads` ADD COLUMN IF NOT EXISTS `channel_id` bigint COMMENT 'Optional channel identifier for grouping threads';
ALTER TABLE `lupo_dialog_threads` ADD COLUMN IF NOT EXISTS `project_slug` varchar(100) COMMENT 'Project or subsystem this thread belongs to';
ALTER TABLE `lupo_dialog_threads` ADD COLUMN IF NOT EXISTS `task_name` varchar(255) COMMENT 'Human-readable task name for this thread';
ALTER TABLE `lupo_dialog_threads` ADD COLUMN IF NOT EXISTS `created_by_actor_id` bigint NOT NULL COMMENT 'Agent or human who initiated the thread';
ALTER TABLE `lupo_dialog_threads` ADD COLUMN IF NOT EXISTS `summary_text` text COMMENT 'Short summary of the thread purpose or context';
ALTER TABLE `lupo_dialog_threads` ADD COLUMN IF NOT EXISTS `status` enum('Open','Ongoing','Closed','Archived') NOT NULL DEFAULT 'Open' COMMENT 'Thread lifecycle state';
ALTER TABLE `lupo_dialog_threads` ADD COLUMN IF NOT EXISTS `artifacts` json COMMENT 'Optional JSON list of related files, URLs, or resources';
ALTER TABLE `lupo_dialog_threads` ADD COLUMN IF NOT EXISTS `metadata_json` json COMMENT 'Metadata: intent, scope, persona, mood, inline dialog metadata';
ALTER TABLE `lupo_dialog_threads` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_dialog_threads` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL COMMENT 'Last update bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_dialog_threads` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted)';
ALTER TABLE `lupo_dialog_threads` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint COMMENT 'Deletion bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_dialog_threads` MODIFY COLUMN `dialog_thread_id` bigint NOT NULL auto_increment COMMENT 'Primary key for the dialog thread';
ALTER TABLE `lupo_dialog_threads` MODIFY COLUMN `federation_node_id` bigint NOT NULL DEFAULT 1 COMMENT 'Node that owns this thread; default is local installation (1)';
ALTER TABLE `lupo_dialog_threads` MODIFY COLUMN `channel_id` bigint COMMENT 'Optional channel identifier for grouping threads';
ALTER TABLE `lupo_dialog_threads` MODIFY COLUMN `project_slug` varchar(100) COMMENT 'Project or subsystem this thread belongs to';
ALTER TABLE `lupo_dialog_threads` MODIFY COLUMN `task_name` varchar(255) COMMENT 'Human-readable task name for this thread';
ALTER TABLE `lupo_dialog_threads` MODIFY COLUMN `created_by_actor_id` bigint NOT NULL COMMENT 'Agent or human who initiated the thread';
ALTER TABLE `lupo_dialog_threads` MODIFY COLUMN `summary_text` text COMMENT 'Short summary of the thread purpose or context';
ALTER TABLE `lupo_dialog_threads` MODIFY COLUMN `status` enum('Open','Ongoing','Closed','Archived') NOT NULL DEFAULT 'Open' COMMENT 'Thread lifecycle state';
ALTER TABLE `lupo_dialog_threads` MODIFY COLUMN `artifacts` json COMMENT 'Optional JSON list of related files, URLs, or resources';
ALTER TABLE `lupo_dialog_threads` MODIFY COLUMN `metadata_json` json COMMENT 'Metadata: intent, scope, persona, mood, inline dialog metadata';
ALTER TABLE `lupo_dialog_threads` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_dialog_threads` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL COMMENT 'Last update bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_dialog_threads` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted)';
ALTER TABLE `lupo_dialog_threads` MODIFY COLUMN `deleted_ymdhis` bigint COMMENT 'Deletion bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_dialog_threads` ADD PRIMARY KEY (`dialog_thread_id`);
ALTER TABLE `lupo_dialog_threads` ADD INDEX IF NOT EXISTS `idx_channel` (`channel_id`);
ALTER TABLE `lupo_dialog_threads` ADD INDEX IF NOT EXISTS `idx_created` (`created_ymdhis`);
ALTER TABLE `lupo_dialog_threads` ADD INDEX IF NOT EXISTS `idx_created_by_actor` (`created_by_actor_id`);
ALTER TABLE `lupo_dialog_threads` ADD INDEX IF NOT EXISTS `idx_deleted` (`is_deleted`);
ALTER TABLE `lupo_dialog_threads` ADD INDEX IF NOT EXISTS `idx_node` (`federation_node_id`);
ALTER TABLE `lupo_dialog_threads` ADD INDEX IF NOT EXISTS `idx_project` (`project_slug`);
ALTER TABLE `lupo_dialog_threads` ADD INDEX IF NOT EXISTS `idx_status` (`status`);
ALTER TABLE `lupo_dialog_threads` ADD INDEX IF NOT EXISTS `idx_task` (`task_name`);
ALTER TABLE `lupo_dialog_threads` ADD INDEX IF NOT EXISTS `idx_updated` (`updated_ymdhis`);

CREATE TABLE IF NOT EXISTS `lupo_doctrine_evolution_audit` (
  `id` bigint unsigned NOT NULL auto_increment,
  `refinement_id` bigint unsigned NOT NULL COMMENT 'Reference to lupo_doctrine_refinements.id',
  `evolution_step` tinyint unsigned NOT NULL COMMENT 'Step in evolution process (1-10)',
  `step_description` varchar(255) NOT NULL COMMENT 'Description of evolution step',
  `step_status` enum('pending','in_progress','completed','failed','skipped') DEFAULT 'pending',
  `step_metadata_json` json COMMENT 'Step-specific metadata',
  `started_ymdhis` bigint COMMENT 'When step started',
  `completed_ymdhis` bigint COMMENT 'When step completed',
  `audit_version` varchar(20) DEFAULT '4.0.75' COMMENT 'Audit system version',
  PRIMARY KEY (`id`),
  KEY `idx_completion_time` (`completed_ymdhis`),
  KEY `idx_refinement_step` (`refinement_id`, `evolution_step`),
  KEY `idx_step_status` (`step_status`)
);

ALTER TABLE `lupo_doctrine_evolution_audit` ADD COLUMN IF NOT EXISTS `id` bigint unsigned NOT NULL auto_increment;
ALTER TABLE `lupo_doctrine_evolution_audit` ADD COLUMN IF NOT EXISTS `refinement_id` bigint unsigned NOT NULL COMMENT 'Reference to lupo_doctrine_refinements.id';
ALTER TABLE `lupo_doctrine_evolution_audit` ADD COLUMN IF NOT EXISTS `evolution_step` tinyint unsigned NOT NULL COMMENT 'Step in evolution process (1-10)';
ALTER TABLE `lupo_doctrine_evolution_audit` ADD COLUMN IF NOT EXISTS `step_description` varchar(255) NOT NULL COMMENT 'Description of evolution step';
ALTER TABLE `lupo_doctrine_evolution_audit` ADD COLUMN IF NOT EXISTS `step_status` enum('pending','in_progress','completed','failed','skipped') DEFAULT 'pending';
ALTER TABLE `lupo_doctrine_evolution_audit` ADD COLUMN IF NOT EXISTS `step_metadata_json` json COMMENT 'Step-specific metadata';
ALTER TABLE `lupo_doctrine_evolution_audit` ADD COLUMN IF NOT EXISTS `started_ymdhis` bigint COMMENT 'When step started';
ALTER TABLE `lupo_doctrine_evolution_audit` ADD COLUMN IF NOT EXISTS `completed_ymdhis` bigint COMMENT 'When step completed';
ALTER TABLE `lupo_doctrine_evolution_audit` ADD COLUMN IF NOT EXISTS `audit_version` varchar(20) DEFAULT '4.0.75' COMMENT 'Audit system version';
ALTER TABLE `lupo_doctrine_evolution_audit` MODIFY COLUMN `id` bigint unsigned NOT NULL auto_increment;
ALTER TABLE `lupo_doctrine_evolution_audit` MODIFY COLUMN `refinement_id` bigint unsigned NOT NULL COMMENT 'Reference to lupo_doctrine_refinements.id';
ALTER TABLE `lupo_doctrine_evolution_audit` MODIFY COLUMN `evolution_step` tinyint unsigned NOT NULL COMMENT 'Step in evolution process (1-10)';
ALTER TABLE `lupo_doctrine_evolution_audit` MODIFY COLUMN `step_description` varchar(255) NOT NULL COMMENT 'Description of evolution step';
ALTER TABLE `lupo_doctrine_evolution_audit` MODIFY COLUMN `step_status` enum('pending','in_progress','completed','failed','skipped') DEFAULT 'pending';
ALTER TABLE `lupo_doctrine_evolution_audit` MODIFY COLUMN `step_metadata_json` json COMMENT 'Step-specific metadata';
ALTER TABLE `lupo_doctrine_evolution_audit` MODIFY COLUMN `started_ymdhis` bigint COMMENT 'When step started';
ALTER TABLE `lupo_doctrine_evolution_audit` MODIFY COLUMN `completed_ymdhis` bigint COMMENT 'When step completed';
ALTER TABLE `lupo_doctrine_evolution_audit` MODIFY COLUMN `audit_version` varchar(20) DEFAULT '4.0.75' COMMENT 'Audit system version';
ALTER TABLE `lupo_doctrine_evolution_audit` ADD PRIMARY KEY (`id`);
ALTER TABLE `lupo_doctrine_evolution_audit` ADD INDEX IF NOT EXISTS `idx_completion_time` (`completed_ymdhis`);
ALTER TABLE `lupo_doctrine_evolution_audit` ADD INDEX IF NOT EXISTS `idx_refinement_step` (`refinement_id`, `evolution_step`);
ALTER TABLE `lupo_doctrine_evolution_audit` ADD INDEX IF NOT EXISTS `idx_step_status` (`step_status`);

CREATE TABLE IF NOT EXISTS `lupo_doctrine_refinements` (
  `id` bigint unsigned NOT NULL auto_increment,
  `cip_event_id` bigint unsigned NOT NULL COMMENT 'Triggering CIP event',
  `doctrine_file_path` varchar(500) NOT NULL COMMENT 'Path to doctrine file updated',
  `refinement_type` enum('addition','modification','removal','restructure') NOT NULL,
  `change_description` text NOT NULL COMMENT 'Description of doctrine change',
  `before_content_hash` varchar(64) COMMENT 'SHA256 of content before change',
  `after_content_hash` varchar(64) NOT NULL COMMENT 'SHA256 of content after change',
  `impact_assessment_json` json COMMENT 'Assessment of change impact',
  `approval_status` enum('pending','approved','rejected','auto_approved') DEFAULT 'pending',
  `approved_by` varchar(100) COMMENT 'Who approved the change',
  `applied_ymdhis` bigint COMMENT 'When change was applied',
  `created_ymdhis` bigint NOT NULL COMMENT 'When refinement was proposed',
  `refinement_version` varchar(20) DEFAULT '4.0.75' COMMENT 'Refinement module version',
  PRIMARY KEY (`id`),
  KEY `idx_applied_time` (`applied_ymdhis`),
  KEY `idx_approval_status` (`approval_status`),
  KEY `idx_cip_event` (`cip_event_id`),
  KEY `idx_doctrine_file` (`doctrine_file_path`)
);

ALTER TABLE `lupo_doctrine_refinements` ADD COLUMN IF NOT EXISTS `id` bigint unsigned NOT NULL auto_increment;
ALTER TABLE `lupo_doctrine_refinements` ADD COLUMN IF NOT EXISTS `cip_event_id` bigint unsigned NOT NULL COMMENT 'Triggering CIP event';
ALTER TABLE `lupo_doctrine_refinements` ADD COLUMN IF NOT EXISTS `doctrine_file_path` varchar(500) NOT NULL COMMENT 'Path to doctrine file updated';
ALTER TABLE `lupo_doctrine_refinements` ADD COLUMN IF NOT EXISTS `refinement_type` enum('addition','modification','removal','restructure') NOT NULL;
ALTER TABLE `lupo_doctrine_refinements` ADD COLUMN IF NOT EXISTS `change_description` text NOT NULL COMMENT 'Description of doctrine change';
ALTER TABLE `lupo_doctrine_refinements` ADD COLUMN IF NOT EXISTS `before_content_hash` varchar(64) COMMENT 'SHA256 of content before change';
ALTER TABLE `lupo_doctrine_refinements` ADD COLUMN IF NOT EXISTS `after_content_hash` varchar(64) NOT NULL COMMENT 'SHA256 of content after change';
ALTER TABLE `lupo_doctrine_refinements` ADD COLUMN IF NOT EXISTS `impact_assessment_json` json COMMENT 'Assessment of change impact';
ALTER TABLE `lupo_doctrine_refinements` ADD COLUMN IF NOT EXISTS `approval_status` enum('pending','approved','rejected','auto_approved') DEFAULT 'pending';
ALTER TABLE `lupo_doctrine_refinements` ADD COLUMN IF NOT EXISTS `approved_by` varchar(100) COMMENT 'Who approved the change';
ALTER TABLE `lupo_doctrine_refinements` ADD COLUMN IF NOT EXISTS `applied_ymdhis` bigint COMMENT 'When change was applied';
ALTER TABLE `lupo_doctrine_refinements` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'When refinement was proposed';
ALTER TABLE `lupo_doctrine_refinements` ADD COLUMN IF NOT EXISTS `refinement_version` varchar(20) DEFAULT '4.0.75' COMMENT 'Refinement module version';
ALTER TABLE `lupo_doctrine_refinements` MODIFY COLUMN `id` bigint unsigned NOT NULL auto_increment;
ALTER TABLE `lupo_doctrine_refinements` MODIFY COLUMN `cip_event_id` bigint unsigned NOT NULL COMMENT 'Triggering CIP event';
ALTER TABLE `lupo_doctrine_refinements` MODIFY COLUMN `doctrine_file_path` varchar(500) NOT NULL COMMENT 'Path to doctrine file updated';
ALTER TABLE `lupo_doctrine_refinements` MODIFY COLUMN `refinement_type` enum('addition','modification','removal','restructure') NOT NULL;
ALTER TABLE `lupo_doctrine_refinements` MODIFY COLUMN `change_description` text NOT NULL COMMENT 'Description of doctrine change';
ALTER TABLE `lupo_doctrine_refinements` MODIFY COLUMN `before_content_hash` varchar(64) COMMENT 'SHA256 of content before change';
ALTER TABLE `lupo_doctrine_refinements` MODIFY COLUMN `after_content_hash` varchar(64) NOT NULL COMMENT 'SHA256 of content after change';
ALTER TABLE `lupo_doctrine_refinements` MODIFY COLUMN `impact_assessment_json` json COMMENT 'Assessment of change impact';
ALTER TABLE `lupo_doctrine_refinements` MODIFY COLUMN `approval_status` enum('pending','approved','rejected','auto_approved') DEFAULT 'pending';
ALTER TABLE `lupo_doctrine_refinements` MODIFY COLUMN `approved_by` varchar(100) COMMENT 'Who approved the change';
ALTER TABLE `lupo_doctrine_refinements` MODIFY COLUMN `applied_ymdhis` bigint COMMENT 'When change was applied';
ALTER TABLE `lupo_doctrine_refinements` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'When refinement was proposed';
ALTER TABLE `lupo_doctrine_refinements` MODIFY COLUMN `refinement_version` varchar(20) DEFAULT '4.0.75' COMMENT 'Refinement module version';
ALTER TABLE `lupo_doctrine_refinements` ADD PRIMARY KEY (`id`);
ALTER TABLE `lupo_doctrine_refinements` ADD INDEX IF NOT EXISTS `idx_applied_time` (`applied_ymdhis`);
ALTER TABLE `lupo_doctrine_refinements` ADD INDEX IF NOT EXISTS `idx_approval_status` (`approval_status`);
ALTER TABLE `lupo_doctrine_refinements` ADD INDEX IF NOT EXISTS `idx_cip_event` (`cip_event_id`);
ALTER TABLE `lupo_doctrine_refinements` ADD INDEX IF NOT EXISTS `idx_doctrine_file` (`doctrine_file_path`);

CREATE TABLE IF NOT EXISTS `lupo_document_chunks` (
  `document_chunk_id` bigint NOT NULL auto_increment,
  `document_id` bigint NOT NULL,
  `chunk_index` int NOT NULL,
  `chunk_content` mediumtext NOT NULL,
  `token_count` int,
  `metadata` json,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (`document_chunk_id`),
  UNIQUE KEY `doc_chunk_unique` (`document_id`, `chunk_index`),
  KEY `document_id` (`document_id`)
);

ALTER TABLE `lupo_document_chunks` ADD COLUMN IF NOT EXISTS `document_chunk_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_document_chunks` ADD COLUMN IF NOT EXISTS `document_id` bigint NOT NULL;
ALTER TABLE `lupo_document_chunks` ADD COLUMN IF NOT EXISTS `chunk_index` int NOT NULL;
ALTER TABLE `lupo_document_chunks` ADD COLUMN IF NOT EXISTS `chunk_content` mediumtext NOT NULL;
ALTER TABLE `lupo_document_chunks` ADD COLUMN IF NOT EXISTS `token_count` int;
ALTER TABLE `lupo_document_chunks` ADD COLUMN IF NOT EXISTS `metadata` json;
ALTER TABLE `lupo_document_chunks` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_document_chunks` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_document_chunks` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_document_chunks` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint;
ALTER TABLE `lupo_document_chunks` MODIFY COLUMN `document_chunk_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_document_chunks` MODIFY COLUMN `document_id` bigint NOT NULL;
ALTER TABLE `lupo_document_chunks` MODIFY COLUMN `chunk_index` int NOT NULL;
ALTER TABLE `lupo_document_chunks` MODIFY COLUMN `chunk_content` mediumtext NOT NULL;
ALTER TABLE `lupo_document_chunks` MODIFY COLUMN `token_count` int;
ALTER TABLE `lupo_document_chunks` MODIFY COLUMN `metadata` json;
ALTER TABLE `lupo_document_chunks` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_document_chunks` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_document_chunks` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_document_chunks` MODIFY COLUMN `deleted_ymdhis` bigint;
ALTER TABLE `lupo_document_chunks` ADD PRIMARY KEY (`document_chunk_id`);
ALTER TABLE `lupo_document_chunks` ADD UNIQUE INDEX IF NOT EXISTS `doc_chunk_unique` (`document_id`, `chunk_index`);
ALTER TABLE `lupo_document_chunks` ADD INDEX IF NOT EXISTS `document_id` (`document_id`);

CREATE TABLE IF NOT EXISTS `lupo_document_embeddings` (
  `document_embedding_id` bigint NOT NULL auto_increment,
  `chunk_id` bigint NOT NULL,
  `embedding_json` json NOT NULL,
  `embedding_model` varchar(128) NOT NULL,
  `embedding_version` varchar(64),
  `created_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (`document_embedding_id`),
  KEY `chunk_id` (`chunk_id`),
  KEY `embedding_model` (`embedding_model`)
);

ALTER TABLE `lupo_document_embeddings` ADD COLUMN IF NOT EXISTS `document_embedding_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_document_embeddings` ADD COLUMN IF NOT EXISTS `chunk_id` bigint NOT NULL;
ALTER TABLE `lupo_document_embeddings` ADD COLUMN IF NOT EXISTS `embedding_json` json NOT NULL;
ALTER TABLE `lupo_document_embeddings` ADD COLUMN IF NOT EXISTS `embedding_model` varchar(128) NOT NULL;
ALTER TABLE `lupo_document_embeddings` ADD COLUMN IF NOT EXISTS `embedding_version` varchar(64);
ALTER TABLE `lupo_document_embeddings` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_document_embeddings` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_document_embeddings` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint;
ALTER TABLE `lupo_document_embeddings` MODIFY COLUMN `document_embedding_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_document_embeddings` MODIFY COLUMN `chunk_id` bigint NOT NULL;
ALTER TABLE `lupo_document_embeddings` MODIFY COLUMN `embedding_json` json NOT NULL;
ALTER TABLE `lupo_document_embeddings` MODIFY COLUMN `embedding_model` varchar(128) NOT NULL;
ALTER TABLE `lupo_document_embeddings` MODIFY COLUMN `embedding_version` varchar(64);
ALTER TABLE `lupo_document_embeddings` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_document_embeddings` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_document_embeddings` MODIFY COLUMN `deleted_ymdhis` bigint;
ALTER TABLE `lupo_document_embeddings` ADD PRIMARY KEY (`document_embedding_id`);
ALTER TABLE `lupo_document_embeddings` ADD INDEX IF NOT EXISTS `chunk_id` (`chunk_id`);
ALTER TABLE `lupo_document_embeddings` ADD INDEX IF NOT EXISTS `embedding_model` (`embedding_model`);

CREATE TABLE IF NOT EXISTS `lupo_documents` (
  `document_id` bigint NOT NULL auto_increment,
  `domain_id` int NOT NULL DEFAULT 1,
  `document_name` varchar(256) NOT NULL,
  `source_type` varchar(64) NOT NULL,
  `source_url` text,
  `mime_type` varchar(128),
  `file_size_bytes` int,
  `checksum_sha256` varchar(64),
  `metadata` json,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (`document_id`)
);

ALTER TABLE `lupo_documents` ADD COLUMN IF NOT EXISTS `document_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_documents` ADD COLUMN IF NOT EXISTS `domain_id` int NOT NULL DEFAULT 1;
ALTER TABLE `lupo_documents` ADD COLUMN IF NOT EXISTS `document_name` varchar(256) NOT NULL;
ALTER TABLE `lupo_documents` ADD COLUMN IF NOT EXISTS `source_type` varchar(64) NOT NULL;
ALTER TABLE `lupo_documents` ADD COLUMN IF NOT EXISTS `source_url` text;
ALTER TABLE `lupo_documents` ADD COLUMN IF NOT EXISTS `mime_type` varchar(128);
ALTER TABLE `lupo_documents` ADD COLUMN IF NOT EXISTS `file_size_bytes` int;
ALTER TABLE `lupo_documents` ADD COLUMN IF NOT EXISTS `checksum_sha256` varchar(64);
ALTER TABLE `lupo_documents` ADD COLUMN IF NOT EXISTS `metadata` json;
ALTER TABLE `lupo_documents` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_documents` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_documents` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_documents` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint;
ALTER TABLE `lupo_documents` MODIFY COLUMN `document_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_documents` MODIFY COLUMN `domain_id` int NOT NULL DEFAULT 1;
ALTER TABLE `lupo_documents` MODIFY COLUMN `document_name` varchar(256) NOT NULL;
ALTER TABLE `lupo_documents` MODIFY COLUMN `source_type` varchar(64) NOT NULL;
ALTER TABLE `lupo_documents` MODIFY COLUMN `source_url` text;
ALTER TABLE `lupo_documents` MODIFY COLUMN `mime_type` varchar(128);
ALTER TABLE `lupo_documents` MODIFY COLUMN `file_size_bytes` int;
ALTER TABLE `lupo_documents` MODIFY COLUMN `checksum_sha256` varchar(64);
ALTER TABLE `lupo_documents` MODIFY COLUMN `metadata` json;
ALTER TABLE `lupo_documents` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_documents` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_documents` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_documents` MODIFY COLUMN `deleted_ymdhis` bigint;
ALTER TABLE `lupo_documents` ADD PRIMARY KEY (`document_id`);

CREATE TABLE IF NOT EXISTS `lupo_edge_types` (
  `edge_type_id` bigint NOT NULL auto_increment,
  `edge_type` varchar(100) NOT NULL,
  `description` text,
  `category` varchar(100),
  `created_ymd` bigint NOT NULL DEFAULT 0,
  `updated_ymd` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (`edge_type_id`),
  KEY `idx_edge_type` (`edge_type`)
);

ALTER TABLE `lupo_edge_types` ADD COLUMN IF NOT EXISTS `edge_type_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_edge_types` ADD COLUMN IF NOT EXISTS `edge_type` varchar(100) NOT NULL;
ALTER TABLE `lupo_edge_types` ADD COLUMN IF NOT EXISTS `description` text;
ALTER TABLE `lupo_edge_types` ADD COLUMN IF NOT EXISTS `category` varchar(100);
ALTER TABLE `lupo_edge_types` ADD COLUMN IF NOT EXISTS `created_ymd` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_edge_types` ADD COLUMN IF NOT EXISTS `updated_ymd` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_edge_types` MODIFY COLUMN `edge_type_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_edge_types` MODIFY COLUMN `edge_type` varchar(100) NOT NULL;
ALTER TABLE `lupo_edge_types` MODIFY COLUMN `description` text;
ALTER TABLE `lupo_edge_types` MODIFY COLUMN `category` varchar(100);
ALTER TABLE `lupo_edge_types` MODIFY COLUMN `created_ymd` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_edge_types` MODIFY COLUMN `updated_ymd` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_edge_types` ADD PRIMARY KEY (`edge_type_id`);
ALTER TABLE `lupo_edge_types` ADD INDEX IF NOT EXISTS `idx_edge_type` (`edge_type`);

CREATE TABLE IF NOT EXISTS `lupo_edges` (
  `edge_id` bigint NOT NULL auto_increment,
  `left_object_type` varchar(50) NOT NULL,
  `left_object_id` bigint NOT NULL,
  `right_object_type` varchar(50) NOT NULL,
  `right_object_id` bigint NOT NULL,
  `edge_type` varchar(100) NOT NULL,
  `channel_id` bigint,
  `channel_key` varchar(64),
  `weight_score` int NOT NULL DEFAULT 0,
  `sort_num` int NOT NULL DEFAULT 0,
  `actor_id` bigint,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  `semantic_weight` decimal(5,2) DEFAULT 0.00 COMMENT 'Semantic relationship strength (0.00-1.00)',
  `relationship_type` enum('hierarchical','semantic','dependency','reference','contains') DEFAULT 'semantic' COMMENT 'Type of relationship',
  `bidirectional` tinyint NOT NULL DEFAULT 0 COMMENT 'Relationship works both ways',
  `context_scope` varchar(100) COMMENT 'Scope where relationship applies',
  PRIMARY KEY (`edge_id`),
  KEY `idx_actor` (`actor_id`),
  KEY `idx_channel_semantic` (`channel_id`, `relationship_type`, `semantic_weight`),
  KEY `idx_edge_type` (`edge_type`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_left` (`left_object_type`, `left_object_id`),
  KEY `idx_relationship_type` (`relationship_type`),
  KEY `idx_right` (`right_object_type`, `right_object_id`),
  KEY `idx_semantic_weight` (`semantic_weight`)
);

ALTER TABLE `lupo_edges` ADD COLUMN IF NOT EXISTS `edge_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_edges` ADD COLUMN IF NOT EXISTS `left_object_type` varchar(50) NOT NULL;
ALTER TABLE `lupo_edges` ADD COLUMN IF NOT EXISTS `left_object_id` bigint NOT NULL;
ALTER TABLE `lupo_edges` ADD COLUMN IF NOT EXISTS `right_object_type` varchar(50) NOT NULL;
ALTER TABLE `lupo_edges` ADD COLUMN IF NOT EXISTS `right_object_id` bigint NOT NULL;
ALTER TABLE `lupo_edges` ADD COLUMN IF NOT EXISTS `edge_type` varchar(100) NOT NULL;
ALTER TABLE `lupo_edges` ADD COLUMN IF NOT EXISTS `channel_id` bigint;
ALTER TABLE `lupo_edges` ADD COLUMN IF NOT EXISTS `channel_key` varchar(64);
ALTER TABLE `lupo_edges` ADD COLUMN IF NOT EXISTS `weight_score` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_edges` ADD COLUMN IF NOT EXISTS `sort_num` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_edges` ADD COLUMN IF NOT EXISTS `actor_id` bigint;
ALTER TABLE `lupo_edges` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_edges` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_edges` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_edges` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_edges` ADD COLUMN IF NOT EXISTS `semantic_weight` decimal(5,2) DEFAULT 0.00 COMMENT 'Semantic relationship strength (0.00-1.00)';
ALTER TABLE `lupo_edges` ADD COLUMN IF NOT EXISTS `relationship_type` enum('hierarchical','semantic','dependency','reference','contains') DEFAULT 'semantic' COMMENT 'Type of relationship';
ALTER TABLE `lupo_edges` ADD COLUMN IF NOT EXISTS `bidirectional` tinyint NOT NULL DEFAULT 0 COMMENT 'Relationship works both ways';
ALTER TABLE `lupo_edges` ADD COLUMN IF NOT EXISTS `context_scope` varchar(100) COMMENT 'Scope where relationship applies';
ALTER TABLE `lupo_edges` MODIFY COLUMN `edge_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_edges` MODIFY COLUMN `left_object_type` varchar(50) NOT NULL;
ALTER TABLE `lupo_edges` MODIFY COLUMN `left_object_id` bigint NOT NULL;
ALTER TABLE `lupo_edges` MODIFY COLUMN `right_object_type` varchar(50) NOT NULL;
ALTER TABLE `lupo_edges` MODIFY COLUMN `right_object_id` bigint NOT NULL;
ALTER TABLE `lupo_edges` MODIFY COLUMN `edge_type` varchar(100) NOT NULL;
ALTER TABLE `lupo_edges` MODIFY COLUMN `channel_id` bigint;
ALTER TABLE `lupo_edges` MODIFY COLUMN `channel_key` varchar(64);
ALTER TABLE `lupo_edges` MODIFY COLUMN `weight_score` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_edges` MODIFY COLUMN `sort_num` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_edges` MODIFY COLUMN `actor_id` bigint;
ALTER TABLE `lupo_edges` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_edges` MODIFY COLUMN `deleted_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_edges` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_edges` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_edges` MODIFY COLUMN `semantic_weight` decimal(5,2) DEFAULT 0.00 COMMENT 'Semantic relationship strength (0.00-1.00)';
ALTER TABLE `lupo_edges` MODIFY COLUMN `relationship_type` enum('hierarchical','semantic','dependency','reference','contains') DEFAULT 'semantic' COMMENT 'Type of relationship';
ALTER TABLE `lupo_edges` MODIFY COLUMN `bidirectional` tinyint NOT NULL DEFAULT 0 COMMENT 'Relationship works both ways';
ALTER TABLE `lupo_edges` MODIFY COLUMN `context_scope` varchar(100) COMMENT 'Scope where relationship applies';
ALTER TABLE `lupo_edges` ADD PRIMARY KEY (`edge_id`);
ALTER TABLE `lupo_edges` ADD INDEX IF NOT EXISTS `idx_actor` (`actor_id`);
ALTER TABLE `lupo_edges` ADD INDEX IF NOT EXISTS `idx_channel_semantic` (`channel_id`, `relationship_type`, `semantic_weight`);
ALTER TABLE `lupo_edges` ADD INDEX IF NOT EXISTS `idx_edge_type` (`edge_type`);
ALTER TABLE `lupo_edges` ADD INDEX IF NOT EXISTS `idx_is_deleted` (`is_deleted`);
ALTER TABLE `lupo_edges` ADD INDEX IF NOT EXISTS `idx_left` (`left_object_type`, `left_object_id`);
ALTER TABLE `lupo_edges` ADD INDEX IF NOT EXISTS `idx_relationship_type` (`relationship_type`);
ALTER TABLE `lupo_edges` ADD INDEX IF NOT EXISTS `idx_right` (`right_object_type`, `right_object_id`);
ALTER TABLE `lupo_edges` ADD INDEX IF NOT EXISTS `idx_semantic_weight` (`semantic_weight`);

CREATE TABLE IF NOT EXISTS `lupo_emotional_geometry_calibrations` (
  `id` bigint unsigned NOT NULL auto_increment,
  `cip_analytics_id` bigint unsigned NOT NULL COMMENT 'Reference to lupo_cip_analytics.id',
  `calibration_target` enum('agent','subsystem','global') NOT NULL,
  `target_identifier` varchar(255) NOT NULL COMMENT 'Agent ID, subsystem name, or "global"',
  `baseline_before_json` json COMMENT 'R/G/B vectors before calibration',
  `baseline_after_json` json NOT NULL COMMENT 'R/G/B vectors after calibration',
  `tension_vectors_detected` json COMMENT 'Detected tension patterns',
  `calibration_reason` text NOT NULL COMMENT 'Why calibration was needed',
  `calibration_algorithm` varchar(100) DEFAULT 'cip_pattern_analysis' COMMENT 'Algorithm used',
  `confidence_score` decimal(5,4) NOT NULL DEFAULT 0.5000 COMMENT 'Calibration confidence',
  `validation_status` enum('pending','validated','rejected','needs_review') DEFAULT 'pending',
  `applied_ymdhis` bigint COMMENT 'When calibration was applied',
  `created_ymdhis` bigint NOT NULL COMMENT 'When calibration was calculated',
  `calibration_version` varchar(20) DEFAULT '4.0.75' COMMENT 'Calibration system version',
  PRIMARY KEY (`id`),
  KEY `idx_analytics_ref` (`cip_analytics_id`),
  KEY `idx_confidence` (`confidence_score`),
  KEY `idx_target` (`calibration_target`, `target_identifier`),
  KEY `idx_validation_status` (`validation_status`)
);

ALTER TABLE `lupo_emotional_geometry_calibrations` ADD COLUMN IF NOT EXISTS `id` bigint unsigned NOT NULL auto_increment;
ALTER TABLE `lupo_emotional_geometry_calibrations` ADD COLUMN IF NOT EXISTS `cip_analytics_id` bigint unsigned NOT NULL COMMENT 'Reference to lupo_cip_analytics.id';
ALTER TABLE `lupo_emotional_geometry_calibrations` ADD COLUMN IF NOT EXISTS `calibration_target` enum('agent','subsystem','global') NOT NULL;
ALTER TABLE `lupo_emotional_geometry_calibrations` ADD COLUMN IF NOT EXISTS `target_identifier` varchar(255) NOT NULL COMMENT 'Agent ID, subsystem name, or "global"';
ALTER TABLE `lupo_emotional_geometry_calibrations` ADD COLUMN IF NOT EXISTS `baseline_before_json` json COMMENT 'R/G/B vectors before calibration';
ALTER TABLE `lupo_emotional_geometry_calibrations` ADD COLUMN IF NOT EXISTS `baseline_after_json` json NOT NULL COMMENT 'R/G/B vectors after calibration';
ALTER TABLE `lupo_emotional_geometry_calibrations` ADD COLUMN IF NOT EXISTS `tension_vectors_detected` json COMMENT 'Detected tension patterns';
ALTER TABLE `lupo_emotional_geometry_calibrations` ADD COLUMN IF NOT EXISTS `calibration_reason` text NOT NULL COMMENT 'Why calibration was needed';
ALTER TABLE `lupo_emotional_geometry_calibrations` ADD COLUMN IF NOT EXISTS `calibration_algorithm` varchar(100) DEFAULT 'cip_pattern_analysis' COMMENT 'Algorithm used';
ALTER TABLE `lupo_emotional_geometry_calibrations` ADD COLUMN IF NOT EXISTS `confidence_score` decimal(5,4) NOT NULL DEFAULT 0.5000 COMMENT 'Calibration confidence';
ALTER TABLE `lupo_emotional_geometry_calibrations` ADD COLUMN IF NOT EXISTS `validation_status` enum('pending','validated','rejected','needs_review') DEFAULT 'pending';
ALTER TABLE `lupo_emotional_geometry_calibrations` ADD COLUMN IF NOT EXISTS `applied_ymdhis` bigint COMMENT 'When calibration was applied';
ALTER TABLE `lupo_emotional_geometry_calibrations` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'When calibration was calculated';
ALTER TABLE `lupo_emotional_geometry_calibrations` ADD COLUMN IF NOT EXISTS `calibration_version` varchar(20) DEFAULT '4.0.75' COMMENT 'Calibration system version';
ALTER TABLE `lupo_emotional_geometry_calibrations` MODIFY COLUMN `id` bigint unsigned NOT NULL auto_increment;
ALTER TABLE `lupo_emotional_geometry_calibrations` MODIFY COLUMN `cip_analytics_id` bigint unsigned NOT NULL COMMENT 'Reference to lupo_cip_analytics.id';
ALTER TABLE `lupo_emotional_geometry_calibrations` MODIFY COLUMN `calibration_target` enum('agent','subsystem','global') NOT NULL;
ALTER TABLE `lupo_emotional_geometry_calibrations` MODIFY COLUMN `target_identifier` varchar(255) NOT NULL COMMENT 'Agent ID, subsystem name, or "global"';
ALTER TABLE `lupo_emotional_geometry_calibrations` MODIFY COLUMN `baseline_before_json` json COMMENT 'R/G/B vectors before calibration';
ALTER TABLE `lupo_emotional_geometry_calibrations` MODIFY COLUMN `baseline_after_json` json NOT NULL COMMENT 'R/G/B vectors after calibration';
ALTER TABLE `lupo_emotional_geometry_calibrations` MODIFY COLUMN `tension_vectors_detected` json COMMENT 'Detected tension patterns';
ALTER TABLE `lupo_emotional_geometry_calibrations` MODIFY COLUMN `calibration_reason` text NOT NULL COMMENT 'Why calibration was needed';
ALTER TABLE `lupo_emotional_geometry_calibrations` MODIFY COLUMN `calibration_algorithm` varchar(100) DEFAULT 'cip_pattern_analysis' COMMENT 'Algorithm used';
ALTER TABLE `lupo_emotional_geometry_calibrations` MODIFY COLUMN `confidence_score` decimal(5,4) NOT NULL DEFAULT 0.5000 COMMENT 'Calibration confidence';
ALTER TABLE `lupo_emotional_geometry_calibrations` MODIFY COLUMN `validation_status` enum('pending','validated','rejected','needs_review') DEFAULT 'pending';
ALTER TABLE `lupo_emotional_geometry_calibrations` MODIFY COLUMN `applied_ymdhis` bigint COMMENT 'When calibration was applied';
ALTER TABLE `lupo_emotional_geometry_calibrations` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'When calibration was calculated';
ALTER TABLE `lupo_emotional_geometry_calibrations` MODIFY COLUMN `calibration_version` varchar(20) DEFAULT '4.0.75' COMMENT 'Calibration system version';
ALTER TABLE `lupo_emotional_geometry_calibrations` ADD PRIMARY KEY (`id`);
ALTER TABLE `lupo_emotional_geometry_calibrations` ADD INDEX IF NOT EXISTS `idx_analytics_ref` (`cip_analytics_id`);
ALTER TABLE `lupo_emotional_geometry_calibrations` ADD INDEX IF NOT EXISTS `idx_confidence` (`confidence_score`);
ALTER TABLE `lupo_emotional_geometry_calibrations` ADD INDEX IF NOT EXISTS `idx_target` (`calibration_target`, `target_identifier`);
ALTER TABLE `lupo_emotional_geometry_calibrations` ADD INDEX IF NOT EXISTS `idx_validation_status` (`validation_status`);

CREATE TABLE IF NOT EXISTS `lupo_event_log` (
  `event_id` bigint NOT NULL auto_increment,
  `event_type` varchar(100) NOT NULL,
  `event_data` json,
  `created_ymdhis` bigint NOT NULL,
  PRIMARY KEY (`event_id`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_event_type` (`event_type`)
);

ALTER TABLE `lupo_event_log` ADD COLUMN IF NOT EXISTS `event_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_event_log` ADD COLUMN IF NOT EXISTS `event_type` varchar(100) NOT NULL;
ALTER TABLE `lupo_event_log` ADD COLUMN IF NOT EXISTS `event_data` json;
ALTER TABLE `lupo_event_log` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_event_log` MODIFY COLUMN `event_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_event_log` MODIFY COLUMN `event_type` varchar(100) NOT NULL;
ALTER TABLE `lupo_event_log` MODIFY COLUMN `event_data` json;
ALTER TABLE `lupo_event_log` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_event_log` ADD PRIMARY KEY (`event_id`);
ALTER TABLE `lupo_event_log` ADD INDEX IF NOT EXISTS `idx_created_ymdhis` (`created_ymdhis`);
ALTER TABLE `lupo_event_log` ADD INDEX IF NOT EXISTS `idx_event_type` (`event_type`);

CREATE TABLE IF NOT EXISTS `lupo_event_metadata` (
  `metadata_id` bigint NOT NULL auto_increment,
  `event_id` bigint NOT NULL,
  `metadata_key` varchar(100) NOT NULL,
  `metadata_value` text,
  `created_ymdhis` bigint NOT NULL,
  PRIMARY KEY (`metadata_id`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_event_id` (`event_id`),
  KEY `idx_metadata_key` (`metadata_key`)
);

ALTER TABLE `lupo_event_metadata` ADD COLUMN IF NOT EXISTS `metadata_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_event_metadata` ADD COLUMN IF NOT EXISTS `event_id` bigint NOT NULL;
ALTER TABLE `lupo_event_metadata` ADD COLUMN IF NOT EXISTS `metadata_key` varchar(100) NOT NULL;
ALTER TABLE `lupo_event_metadata` ADD COLUMN IF NOT EXISTS `metadata_value` text;
ALTER TABLE `lupo_event_metadata` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_event_metadata` MODIFY COLUMN `metadata_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_event_metadata` MODIFY COLUMN `event_id` bigint NOT NULL;
ALTER TABLE `lupo_event_metadata` MODIFY COLUMN `metadata_key` varchar(100) NOT NULL;
ALTER TABLE `lupo_event_metadata` MODIFY COLUMN `metadata_value` text;
ALTER TABLE `lupo_event_metadata` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_event_metadata` ADD PRIMARY KEY (`metadata_id`);
ALTER TABLE `lupo_event_metadata` ADD INDEX IF NOT EXISTS `idx_created_ymdhis` (`created_ymdhis`);
ALTER TABLE `lupo_event_metadata` ADD INDEX IF NOT EXISTS `idx_event_id` (`event_id`);
ALTER TABLE `lupo_event_metadata` ADD INDEX IF NOT EXISTS `idx_metadata_key` (`metadata_key`);

CREATE TABLE IF NOT EXISTS `lupo_federation_categories` (
  `federation_category_id` bigint NOT NULL auto_increment,
  `category_name` varchar(255) NOT NULL,
  `category_slug` varchar(255) NOT NULL,
  `category_description` text,
  `meta_json` json,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (`federation_category_id`),
  KEY `idx_category_slug` (`category_slug`),
  KEY `idx_is_deleted` (`is_deleted`)
);

ALTER TABLE `lupo_federation_categories` ADD COLUMN IF NOT EXISTS `federation_category_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_federation_categories` ADD COLUMN IF NOT EXISTS `category_name` varchar(255) NOT NULL;
ALTER TABLE `lupo_federation_categories` ADD COLUMN IF NOT EXISTS `category_slug` varchar(255) NOT NULL;
ALTER TABLE `lupo_federation_categories` ADD COLUMN IF NOT EXISTS `category_description` text;
ALTER TABLE `lupo_federation_categories` ADD COLUMN IF NOT EXISTS `meta_json` json;
ALTER TABLE `lupo_federation_categories` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_federation_categories` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_federation_categories` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_federation_categories` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_federation_categories` MODIFY COLUMN `federation_category_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_federation_categories` MODIFY COLUMN `category_name` varchar(255) NOT NULL;
ALTER TABLE `lupo_federation_categories` MODIFY COLUMN `category_slug` varchar(255) NOT NULL;
ALTER TABLE `lupo_federation_categories` MODIFY COLUMN `category_description` text;
ALTER TABLE `lupo_federation_categories` MODIFY COLUMN `meta_json` json;
ALTER TABLE `lupo_federation_categories` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_federation_categories` MODIFY COLUMN `deleted_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_federation_categories` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_federation_categories` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_federation_categories` ADD PRIMARY KEY (`federation_category_id`);
ALTER TABLE `lupo_federation_categories` ADD INDEX IF NOT EXISTS `idx_category_slug` (`category_slug`);
ALTER TABLE `lupo_federation_categories` ADD INDEX IF NOT EXISTS `idx_is_deleted` (`is_deleted`);

CREATE TABLE IF NOT EXISTS `lupo_federation_category_map` (
  `federation_category_map_id` bigint NOT NULL auto_increment,
  `federation_node_id` bigint NOT NULL,
  `federation_category_id` bigint NOT NULL,
  `meta_json` json,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (`federation_category_map_id`),
  KEY `idx_category` (`federation_category_id`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_node` (`federation_node_id`)
);

ALTER TABLE `lupo_federation_category_map` ADD COLUMN IF NOT EXISTS `federation_category_map_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_federation_category_map` ADD COLUMN IF NOT EXISTS `federation_node_id` bigint NOT NULL;
ALTER TABLE `lupo_federation_category_map` ADD COLUMN IF NOT EXISTS `federation_category_id` bigint NOT NULL;
ALTER TABLE `lupo_federation_category_map` ADD COLUMN IF NOT EXISTS `meta_json` json;
ALTER TABLE `lupo_federation_category_map` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_federation_category_map` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_federation_category_map` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_federation_category_map` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_federation_category_map` MODIFY COLUMN `federation_category_map_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_federation_category_map` MODIFY COLUMN `federation_node_id` bigint NOT NULL;
ALTER TABLE `lupo_federation_category_map` MODIFY COLUMN `federation_category_id` bigint NOT NULL;
ALTER TABLE `lupo_federation_category_map` MODIFY COLUMN `meta_json` json;
ALTER TABLE `lupo_federation_category_map` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_federation_category_map` MODIFY COLUMN `deleted_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_federation_category_map` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_federation_category_map` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_federation_category_map` ADD PRIMARY KEY (`federation_category_map_id`);
ALTER TABLE `lupo_federation_category_map` ADD INDEX IF NOT EXISTS `idx_category` (`federation_category_id`);
ALTER TABLE `lupo_federation_category_map` ADD INDEX IF NOT EXISTS `idx_is_deleted` (`is_deleted`);
ALTER TABLE `lupo_federation_category_map` ADD INDEX IF NOT EXISTS `idx_node` (`federation_node_id`);

CREATE TABLE IF NOT EXISTS `lupo_federation_discovery` (
  `federation_discovery_id` bigint NOT NULL auto_increment,
  `domain` varchar(255) NOT NULL,
  `install_url` varchar(500),
  `is_lupopedia` tinyint NOT NULL DEFAULT 0,
  `last_seen_ymdhis` bigint,
  `first_seen_ymdhis` bigint,
  `hashtag_count` bigint DEFAULT 0,
  `question_count` bigint DEFAULT 0,
  `atom_count` bigint DEFAULT 0,
  `context_count` bigint DEFAULT 0,
  `collection_count` bigint DEFAULT 0,
  `keywords` varchar(500),
  `description` text,
  `import_hashtags` tinyint NOT NULL DEFAULT 0,
  `import_questions` tinyint NOT NULL DEFAULT 0,
  `import_atoms` tinyint NOT NULL DEFAULT 0,
  `import_contexts` tinyint NOT NULL DEFAULT 0,
  `import_collections` tinyint NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  PRIMARY KEY (`federation_discovery_id`),
  KEY `idx_domain` (`domain`)
);

ALTER TABLE `lupo_federation_discovery` ADD COLUMN IF NOT EXISTS `federation_discovery_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_federation_discovery` ADD COLUMN IF NOT EXISTS `domain` varchar(255) NOT NULL;
ALTER TABLE `lupo_federation_discovery` ADD COLUMN IF NOT EXISTS `install_url` varchar(500);
ALTER TABLE `lupo_federation_discovery` ADD COLUMN IF NOT EXISTS `is_lupopedia` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_federation_discovery` ADD COLUMN IF NOT EXISTS `last_seen_ymdhis` bigint;
ALTER TABLE `lupo_federation_discovery` ADD COLUMN IF NOT EXISTS `first_seen_ymdhis` bigint;
ALTER TABLE `lupo_federation_discovery` ADD COLUMN IF NOT EXISTS `hashtag_count` bigint DEFAULT 0;
ALTER TABLE `lupo_federation_discovery` ADD COLUMN IF NOT EXISTS `question_count` bigint DEFAULT 0;
ALTER TABLE `lupo_federation_discovery` ADD COLUMN IF NOT EXISTS `atom_count` bigint DEFAULT 0;
ALTER TABLE `lupo_federation_discovery` ADD COLUMN IF NOT EXISTS `context_count` bigint DEFAULT 0;
ALTER TABLE `lupo_federation_discovery` ADD COLUMN IF NOT EXISTS `collection_count` bigint DEFAULT 0;
ALTER TABLE `lupo_federation_discovery` ADD COLUMN IF NOT EXISTS `keywords` varchar(500);
ALTER TABLE `lupo_federation_discovery` ADD COLUMN IF NOT EXISTS `description` text;
ALTER TABLE `lupo_federation_discovery` ADD COLUMN IF NOT EXISTS `import_hashtags` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_federation_discovery` ADD COLUMN IF NOT EXISTS `import_questions` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_federation_discovery` ADD COLUMN IF NOT EXISTS `import_atoms` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_federation_discovery` ADD COLUMN IF NOT EXISTS `import_contexts` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_federation_discovery` ADD COLUMN IF NOT EXISTS `import_collections` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_federation_discovery` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_federation_discovery` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_federation_discovery` MODIFY COLUMN `federation_discovery_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_federation_discovery` MODIFY COLUMN `domain` varchar(255) NOT NULL;
ALTER TABLE `lupo_federation_discovery` MODIFY COLUMN `install_url` varchar(500);
ALTER TABLE `lupo_federation_discovery` MODIFY COLUMN `is_lupopedia` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_federation_discovery` MODIFY COLUMN `last_seen_ymdhis` bigint;
ALTER TABLE `lupo_federation_discovery` MODIFY COLUMN `first_seen_ymdhis` bigint;
ALTER TABLE `lupo_federation_discovery` MODIFY COLUMN `hashtag_count` bigint DEFAULT 0;
ALTER TABLE `lupo_federation_discovery` MODIFY COLUMN `question_count` bigint DEFAULT 0;
ALTER TABLE `lupo_federation_discovery` MODIFY COLUMN `atom_count` bigint DEFAULT 0;
ALTER TABLE `lupo_federation_discovery` MODIFY COLUMN `context_count` bigint DEFAULT 0;
ALTER TABLE `lupo_federation_discovery` MODIFY COLUMN `collection_count` bigint DEFAULT 0;
ALTER TABLE `lupo_federation_discovery` MODIFY COLUMN `keywords` varchar(500);
ALTER TABLE `lupo_federation_discovery` MODIFY COLUMN `description` text;
ALTER TABLE `lupo_federation_discovery` MODIFY COLUMN `import_hashtags` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_federation_discovery` MODIFY COLUMN `import_questions` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_federation_discovery` MODIFY COLUMN `import_atoms` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_federation_discovery` MODIFY COLUMN `import_contexts` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_federation_discovery` MODIFY COLUMN `import_collections` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_federation_discovery` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_federation_discovery` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_federation_discovery` ADD PRIMARY KEY (`federation_discovery_id`);
ALTER TABLE `lupo_federation_discovery` ADD INDEX IF NOT EXISTS `idx_domain` (`domain`);

CREATE TABLE IF NOT EXISTS `lupo_federation_nodes` (
  `federation_node_id` bigint NOT NULL auto_increment,
  `node_base_url` varchar(500) NOT NULL,
  `node_name` varchar(255),
  `node_description` text,
  `node_contact` varchar(255),
  `meta_json` json,
  `content_count` bigint NOT NULL DEFAULT 0,
  `atom_count` bigint NOT NULL DEFAULT 0,
  `hashtag_count` bigint NOT NULL DEFAULT 0,
  `actor_count` bigint NOT NULL DEFAULT 0,
  `last_sync_ymdhis` bigint NOT NULL DEFAULT 0,
  `trust_level` tinyint NOT NULL DEFAULT 0,
  `status` tinyint NOT NULL DEFAULT 1,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (`federation_node_id`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_node_base_url` (`node_base_url`),
  KEY `idx_status` (`status`),
  KEY `idx_trust_level` (`trust_level`)
);

ALTER TABLE `lupo_federation_nodes` ADD COLUMN IF NOT EXISTS `federation_node_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_federation_nodes` ADD COLUMN IF NOT EXISTS `node_base_url` varchar(500) NOT NULL;
ALTER TABLE `lupo_federation_nodes` ADD COLUMN IF NOT EXISTS `node_name` varchar(255);
ALTER TABLE `lupo_federation_nodes` ADD COLUMN IF NOT EXISTS `node_description` text;
ALTER TABLE `lupo_federation_nodes` ADD COLUMN IF NOT EXISTS `node_contact` varchar(255);
ALTER TABLE `lupo_federation_nodes` ADD COLUMN IF NOT EXISTS `meta_json` json;
ALTER TABLE `lupo_federation_nodes` ADD COLUMN IF NOT EXISTS `content_count` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_federation_nodes` ADD COLUMN IF NOT EXISTS `atom_count` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_federation_nodes` ADD COLUMN IF NOT EXISTS `hashtag_count` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_federation_nodes` ADD COLUMN IF NOT EXISTS `actor_count` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_federation_nodes` ADD COLUMN IF NOT EXISTS `last_sync_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_federation_nodes` ADD COLUMN IF NOT EXISTS `trust_level` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_federation_nodes` ADD COLUMN IF NOT EXISTS `status` tinyint NOT NULL DEFAULT 1;
ALTER TABLE `lupo_federation_nodes` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_federation_nodes` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_federation_nodes` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_federation_nodes` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_federation_nodes` MODIFY COLUMN `federation_node_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_federation_nodes` MODIFY COLUMN `node_base_url` varchar(500) NOT NULL;
ALTER TABLE `lupo_federation_nodes` MODIFY COLUMN `node_name` varchar(255);
ALTER TABLE `lupo_federation_nodes` MODIFY COLUMN `node_description` text;
ALTER TABLE `lupo_federation_nodes` MODIFY COLUMN `node_contact` varchar(255);
ALTER TABLE `lupo_federation_nodes` MODIFY COLUMN `meta_json` json;
ALTER TABLE `lupo_federation_nodes` MODIFY COLUMN `content_count` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_federation_nodes` MODIFY COLUMN `atom_count` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_federation_nodes` MODIFY COLUMN `hashtag_count` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_federation_nodes` MODIFY COLUMN `actor_count` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_federation_nodes` MODIFY COLUMN `last_sync_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_federation_nodes` MODIFY COLUMN `trust_level` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_federation_nodes` MODIFY COLUMN `status` tinyint NOT NULL DEFAULT 1;
ALTER TABLE `lupo_federation_nodes` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_federation_nodes` MODIFY COLUMN `deleted_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_federation_nodes` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_federation_nodes` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_federation_nodes` ADD PRIMARY KEY (`federation_node_id`);
ALTER TABLE `lupo_federation_nodes` ADD INDEX IF NOT EXISTS `idx_is_deleted` (`is_deleted`);
ALTER TABLE `lupo_federation_nodes` ADD INDEX IF NOT EXISTS `idx_node_base_url` (`node_base_url`);
ALTER TABLE `lupo_federation_nodes` ADD INDEX IF NOT EXISTS `idx_status` (`status`);
ALTER TABLE `lupo_federation_nodes` ADD INDEX IF NOT EXISTS `idx_trust_level` (`trust_level`);

CREATE TABLE IF NOT EXISTS `lupo_gov_event_actor_edges` (
  `edge_id` bigint NOT NULL auto_increment COMMENT 'Primary key for edge',
  `gov_event_id` bigint NOT NULL COMMENT 'Governance event',
  `actor_id` bigint NOT NULL COMMENT 'Actor',
  `edge_type` varchar(100) NOT NULL COMMENT 'Type of relationship',
  `edge_properties` text COMMENT 'JSON or TOON formatted metadata',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
  `updated_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when last updated',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag',
  `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted',
  PRIMARY KEY (`edge_id`),
  KEY `idx_actor` (`actor_id`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_edge_type` (`edge_type`),
  KEY `idx_gov_event` (`gov_event_id`),
  KEY `idx_is_deleted` (`is_deleted`),
  UNIQUE KEY `unique_gov_event_actor_edge` (`gov_event_id`, `actor_id`, `edge_type`)
);

ALTER TABLE `lupo_gov_event_actor_edges` ADD COLUMN IF NOT EXISTS `edge_id` bigint NOT NULL auto_increment COMMENT 'Primary key for edge';
ALTER TABLE `lupo_gov_event_actor_edges` ADD COLUMN IF NOT EXISTS `gov_event_id` bigint NOT NULL COMMENT 'Governance event';
ALTER TABLE `lupo_gov_event_actor_edges` ADD COLUMN IF NOT EXISTS `actor_id` bigint NOT NULL COMMENT 'Actor';
ALTER TABLE `lupo_gov_event_actor_edges` ADD COLUMN IF NOT EXISTS `edge_type` varchar(100) NOT NULL COMMENT 'Type of relationship';
ALTER TABLE `lupo_gov_event_actor_edges` ADD COLUMN IF NOT EXISTS `edge_properties` text COMMENT 'JSON or TOON formatted metadata';
ALTER TABLE `lupo_gov_event_actor_edges` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created';
ALTER TABLE `lupo_gov_event_actor_edges` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when last updated';
ALTER TABLE `lupo_gov_event_actor_edges` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag';
ALTER TABLE `lupo_gov_event_actor_edges` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted';
ALTER TABLE `lupo_gov_event_actor_edges` MODIFY COLUMN `edge_id` bigint NOT NULL auto_increment COMMENT 'Primary key for edge';
ALTER TABLE `lupo_gov_event_actor_edges` MODIFY COLUMN `gov_event_id` bigint NOT NULL COMMENT 'Governance event';
ALTER TABLE `lupo_gov_event_actor_edges` MODIFY COLUMN `actor_id` bigint NOT NULL COMMENT 'Actor';
ALTER TABLE `lupo_gov_event_actor_edges` MODIFY COLUMN `edge_type` varchar(100) NOT NULL COMMENT 'Type of relationship';
ALTER TABLE `lupo_gov_event_actor_edges` MODIFY COLUMN `edge_properties` text COMMENT 'JSON or TOON formatted metadata';
ALTER TABLE `lupo_gov_event_actor_edges` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created';
ALTER TABLE `lupo_gov_event_actor_edges` MODIFY COLUMN `updated_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when last updated';
ALTER TABLE `lupo_gov_event_actor_edges` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag';
ALTER TABLE `lupo_gov_event_actor_edges` MODIFY COLUMN `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted';
ALTER TABLE `lupo_gov_event_actor_edges` ADD PRIMARY KEY (`edge_id`);
ALTER TABLE `lupo_gov_event_actor_edges` ADD INDEX IF NOT EXISTS `idx_actor` (`actor_id`);
ALTER TABLE `lupo_gov_event_actor_edges` ADD INDEX IF NOT EXISTS `idx_created_ymdhis` (`created_ymdhis`);
ALTER TABLE `lupo_gov_event_actor_edges` ADD INDEX IF NOT EXISTS `idx_edge_type` (`edge_type`);
ALTER TABLE `lupo_gov_event_actor_edges` ADD INDEX IF NOT EXISTS `idx_gov_event` (`gov_event_id`);
ALTER TABLE `lupo_gov_event_actor_edges` ADD INDEX IF NOT EXISTS `idx_is_deleted` (`is_deleted`);
ALTER TABLE `lupo_gov_event_actor_edges` ADD UNIQUE INDEX IF NOT EXISTS `unique_gov_event_actor_edge` (`gov_event_id`, `actor_id`, `edge_type`);

CREATE TABLE IF NOT EXISTS `lupo_gov_event_conflicts` (
  `id` bigint NOT NULL auto_increment COMMENT 'Primary key',
  `gov_event_id` bigint NOT NULL COMMENT 'The event declaring a conflict',
  `conflicts_with_event_id` bigint NOT NULL COMMENT 'The event it conflicts with',
  `conflict_type` varchar(50) NOT NULL COMMENT 'schema, doctrine, branch, bigint, identity',
  `severity` varchar(20) NOT NULL COMMENT 'warning, error, fatal',
  `notes` text COMMENT 'Optional explanation of the conflict',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC bigint of creation',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag',
  `deleted_ymdhis` bigint COMMENT 'UTC bigint of deletion',
  PRIMARY KEY (`id`),
  KEY `idx_conflicts_with_event_id` (`conflicts_with_event_id`),
  KEY `idx_gov_event_id` (`gov_event_id`)
);

ALTER TABLE `lupo_gov_event_conflicts` ADD COLUMN IF NOT EXISTS `id` bigint NOT NULL auto_increment COMMENT 'Primary key';
ALTER TABLE `lupo_gov_event_conflicts` ADD COLUMN IF NOT EXISTS `gov_event_id` bigint NOT NULL COMMENT 'The event declaring a conflict';
ALTER TABLE `lupo_gov_event_conflicts` ADD COLUMN IF NOT EXISTS `conflicts_with_event_id` bigint NOT NULL COMMENT 'The event it conflicts with';
ALTER TABLE `lupo_gov_event_conflicts` ADD COLUMN IF NOT EXISTS `conflict_type` varchar(50) NOT NULL COMMENT 'schema, doctrine, branch, bigint, identity';
ALTER TABLE `lupo_gov_event_conflicts` ADD COLUMN IF NOT EXISTS `severity` varchar(20) NOT NULL COMMENT 'warning, error, fatal';
ALTER TABLE `lupo_gov_event_conflicts` ADD COLUMN IF NOT EXISTS `notes` text COMMENT 'Optional explanation of the conflict';
ALTER TABLE `lupo_gov_event_conflicts` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'UTC bigint of creation';
ALTER TABLE `lupo_gov_event_conflicts` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag';
ALTER TABLE `lupo_gov_event_conflicts` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint COMMENT 'UTC bigint of deletion';
ALTER TABLE `lupo_gov_event_conflicts` MODIFY COLUMN `id` bigint NOT NULL auto_increment COMMENT 'Primary key';
ALTER TABLE `lupo_gov_event_conflicts` MODIFY COLUMN `gov_event_id` bigint NOT NULL COMMENT 'The event declaring a conflict';
ALTER TABLE `lupo_gov_event_conflicts` MODIFY COLUMN `conflicts_with_event_id` bigint NOT NULL COMMENT 'The event it conflicts with';
ALTER TABLE `lupo_gov_event_conflicts` MODIFY COLUMN `conflict_type` varchar(50) NOT NULL COMMENT 'schema, doctrine, branch, bigint, identity';
ALTER TABLE `lupo_gov_event_conflicts` MODIFY COLUMN `severity` varchar(20) NOT NULL COMMENT 'warning, error, fatal';
ALTER TABLE `lupo_gov_event_conflicts` MODIFY COLUMN `notes` text COMMENT 'Optional explanation of the conflict';
ALTER TABLE `lupo_gov_event_conflicts` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'UTC bigint of creation';
ALTER TABLE `lupo_gov_event_conflicts` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag';
ALTER TABLE `lupo_gov_event_conflicts` MODIFY COLUMN `deleted_ymdhis` bigint COMMENT 'UTC bigint of deletion';
ALTER TABLE `lupo_gov_event_conflicts` ADD PRIMARY KEY (`id`);
ALTER TABLE `lupo_gov_event_conflicts` ADD INDEX IF NOT EXISTS `idx_conflicts_with_event_id` (`conflicts_with_event_id`);
ALTER TABLE `lupo_gov_event_conflicts` ADD INDEX IF NOT EXISTS `idx_gov_event_id` (`gov_event_id`);

CREATE TABLE IF NOT EXISTS `lupo_gov_event_dependencies` (
  `id` bigint NOT NULL auto_increment COMMENT 'Primary key',
  `gov_event_id` bigint NOT NULL COMMENT 'The event declaring a dependency',
  `depends_on_event_id` bigint NOT NULL COMMENT 'The event it depends on',
  `dependency_type` varchar(50) NOT NULL COMMENT 'hard, soft, branch, schema, doctrine',
  `notes` text COMMENT 'Optional explanation',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC bigint of creation',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag',
  `deleted_ymdhis` bigint COMMENT 'UTC bigint of deletion',
  PRIMARY KEY (`id`),
  KEY `idx_depends_on_event_id` (`depends_on_event_id`),
  KEY `idx_gov_event_id` (`gov_event_id`)
);

ALTER TABLE `lupo_gov_event_dependencies` ADD COLUMN IF NOT EXISTS `id` bigint NOT NULL auto_increment COMMENT 'Primary key';
ALTER TABLE `lupo_gov_event_dependencies` ADD COLUMN IF NOT EXISTS `gov_event_id` bigint NOT NULL COMMENT 'The event declaring a dependency';
ALTER TABLE `lupo_gov_event_dependencies` ADD COLUMN IF NOT EXISTS `depends_on_event_id` bigint NOT NULL COMMENT 'The event it depends on';
ALTER TABLE `lupo_gov_event_dependencies` ADD COLUMN IF NOT EXISTS `dependency_type` varchar(50) NOT NULL COMMENT 'hard, soft, branch, schema, doctrine';
ALTER TABLE `lupo_gov_event_dependencies` ADD COLUMN IF NOT EXISTS `notes` text COMMENT 'Optional explanation';
ALTER TABLE `lupo_gov_event_dependencies` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'UTC bigint of creation';
ALTER TABLE `lupo_gov_event_dependencies` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag';
ALTER TABLE `lupo_gov_event_dependencies` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint COMMENT 'UTC bigint of deletion';
ALTER TABLE `lupo_gov_event_dependencies` MODIFY COLUMN `id` bigint NOT NULL auto_increment COMMENT 'Primary key';
ALTER TABLE `lupo_gov_event_dependencies` MODIFY COLUMN `gov_event_id` bigint NOT NULL COMMENT 'The event declaring a dependency';
ALTER TABLE `lupo_gov_event_dependencies` MODIFY COLUMN `depends_on_event_id` bigint NOT NULL COMMENT 'The event it depends on';
ALTER TABLE `lupo_gov_event_dependencies` MODIFY COLUMN `dependency_type` varchar(50) NOT NULL COMMENT 'hard, soft, branch, schema, doctrine';
ALTER TABLE `lupo_gov_event_dependencies` MODIFY COLUMN `notes` text COMMENT 'Optional explanation';
ALTER TABLE `lupo_gov_event_dependencies` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'UTC bigint of creation';
ALTER TABLE `lupo_gov_event_dependencies` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag';
ALTER TABLE `lupo_gov_event_dependencies` MODIFY COLUMN `deleted_ymdhis` bigint COMMENT 'UTC bigint of deletion';
ALTER TABLE `lupo_gov_event_dependencies` ADD PRIMARY KEY (`id`);
ALTER TABLE `lupo_gov_event_dependencies` ADD INDEX IF NOT EXISTS `idx_depends_on_event_id` (`depends_on_event_id`);
ALTER TABLE `lupo_gov_event_dependencies` ADD INDEX IF NOT EXISTS `idx_gov_event_id` (`gov_event_id`);

CREATE TABLE IF NOT EXISTS `lupo_gov_event_references` (
  `reference_id` bigint NOT NULL auto_increment COMMENT 'Primary key for reference',
  `gov_event_id` bigint NOT NULL COMMENT 'Associated governance event',
  `reference_type` varchar(100) NOT NULL COMMENT 'Type of reference (document, link, etc.)',
  `reference_title` varchar(255) NOT NULL COMMENT 'Reference title',
  `reference_url` varchar(1000) COMMENT 'URL if applicable',
  `reference_content` text COMMENT 'Reference content or excerpt',
  `order_sequence` int NOT NULL DEFAULT 0 COMMENT 'Display order',
  `metadata_json` json COMMENT 'Additional reference metadata',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
  `updated_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when last updated',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag',
  `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted',
  PRIMARY KEY (`reference_id`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_gov_event` (`gov_event_id`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_order_sequence` (`order_sequence`),
  KEY `idx_reference_type` (`reference_type`)
);

ALTER TABLE `lupo_gov_event_references` ADD COLUMN IF NOT EXISTS `reference_id` bigint NOT NULL auto_increment COMMENT 'Primary key for reference';
ALTER TABLE `lupo_gov_event_references` ADD COLUMN IF NOT EXISTS `gov_event_id` bigint NOT NULL COMMENT 'Associated governance event';
ALTER TABLE `lupo_gov_event_references` ADD COLUMN IF NOT EXISTS `reference_type` varchar(100) NOT NULL COMMENT 'Type of reference (document, link, etc.)';
ALTER TABLE `lupo_gov_event_references` ADD COLUMN IF NOT EXISTS `reference_title` varchar(255) NOT NULL COMMENT 'Reference title';
ALTER TABLE `lupo_gov_event_references` ADD COLUMN IF NOT EXISTS `reference_url` varchar(1000) COMMENT 'URL if applicable';
ALTER TABLE `lupo_gov_event_references` ADD COLUMN IF NOT EXISTS `reference_content` text COMMENT 'Reference content or excerpt';
ALTER TABLE `lupo_gov_event_references` ADD COLUMN IF NOT EXISTS `order_sequence` int NOT NULL DEFAULT 0 COMMENT 'Display order';
ALTER TABLE `lupo_gov_event_references` ADD COLUMN IF NOT EXISTS `metadata_json` json COMMENT 'Additional reference metadata';
ALTER TABLE `lupo_gov_event_references` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created';
ALTER TABLE `lupo_gov_event_references` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when last updated';
ALTER TABLE `lupo_gov_event_references` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag';
ALTER TABLE `lupo_gov_event_references` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted';
ALTER TABLE `lupo_gov_event_references` MODIFY COLUMN `reference_id` bigint NOT NULL auto_increment COMMENT 'Primary key for reference';
ALTER TABLE `lupo_gov_event_references` MODIFY COLUMN `gov_event_id` bigint NOT NULL COMMENT 'Associated governance event';
ALTER TABLE `lupo_gov_event_references` MODIFY COLUMN `reference_type` varchar(100) NOT NULL COMMENT 'Type of reference (document, link, etc.)';
ALTER TABLE `lupo_gov_event_references` MODIFY COLUMN `reference_title` varchar(255) NOT NULL COMMENT 'Reference title';
ALTER TABLE `lupo_gov_event_references` MODIFY COLUMN `reference_url` varchar(1000) COMMENT 'URL if applicable';
ALTER TABLE `lupo_gov_event_references` MODIFY COLUMN `reference_content` text COMMENT 'Reference content or excerpt';
ALTER TABLE `lupo_gov_event_references` MODIFY COLUMN `order_sequence` int NOT NULL DEFAULT 0 COMMENT 'Display order';
ALTER TABLE `lupo_gov_event_references` MODIFY COLUMN `metadata_json` json COMMENT 'Additional reference metadata';
ALTER TABLE `lupo_gov_event_references` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created';
ALTER TABLE `lupo_gov_event_references` MODIFY COLUMN `updated_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when last updated';
ALTER TABLE `lupo_gov_event_references` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag';
ALTER TABLE `lupo_gov_event_references` MODIFY COLUMN `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted';
ALTER TABLE `lupo_gov_event_references` ADD PRIMARY KEY (`reference_id`);
ALTER TABLE `lupo_gov_event_references` ADD INDEX IF NOT EXISTS `idx_created_ymdhis` (`created_ymdhis`);
ALTER TABLE `lupo_gov_event_references` ADD INDEX IF NOT EXISTS `idx_gov_event` (`gov_event_id`);
ALTER TABLE `lupo_gov_event_references` ADD INDEX IF NOT EXISTS `idx_is_deleted` (`is_deleted`);
ALTER TABLE `lupo_gov_event_references` ADD INDEX IF NOT EXISTS `idx_order_sequence` (`order_sequence`);
ALTER TABLE `lupo_gov_event_references` ADD INDEX IF NOT EXISTS `idx_reference_type` (`reference_type`);

CREATE TABLE IF NOT EXISTS `lupo_gov_events` (
  `gov_event_id` bigint NOT NULL auto_increment COMMENT 'Primary key for governance event',
  `utc_group_id` bigint NOT NULL COMMENT 'UTC group identifier',
  `semantic_utc_version` varchar(50) NOT NULL COMMENT 'Semantic UTC version string',
  `canonical_path` varchar(500) NOT NULL COMMENT 'Canonical path for the event',
  `event_type` varchar(100) NOT NULL COMMENT 'Type of governance event',
  `title` varchar(255) NOT NULL COMMENT 'Event title',
  `directive_block` text COMMENT 'Captain Wolfie directive content',
  `tldr_summary` text COMMENT 'TL;DR summary of the event',
  `metadata_json` json COMMENT 'Additional event metadata',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
  `updated_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when last updated',
  `is_active` tinyint NOT NULL DEFAULT 1 COMMENT 'Active flag',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag',
  `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted',
  PRIMARY KEY (`gov_event_id`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_event_type` (`event_type`),
  KEY `idx_is_active` (`is_active`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_semantic_version` (`semantic_utc_version`),
  KEY `idx_utc_group` (`utc_group_id`),
  UNIQUE KEY `unique_canonical_path` (`canonical_path`)
);

ALTER TABLE `lupo_gov_events` ADD COLUMN IF NOT EXISTS `gov_event_id` bigint NOT NULL auto_increment COMMENT 'Primary key for governance event';
ALTER TABLE `lupo_gov_events` ADD COLUMN IF NOT EXISTS `utc_group_id` bigint NOT NULL COMMENT 'UTC group identifier';
ALTER TABLE `lupo_gov_events` ADD COLUMN IF NOT EXISTS `semantic_utc_version` varchar(50) NOT NULL COMMENT 'Semantic UTC version string';
ALTER TABLE `lupo_gov_events` ADD COLUMN IF NOT EXISTS `canonical_path` varchar(500) NOT NULL COMMENT 'Canonical path for the event';
ALTER TABLE `lupo_gov_events` ADD COLUMN IF NOT EXISTS `event_type` varchar(100) NOT NULL COMMENT 'Type of governance event';
ALTER TABLE `lupo_gov_events` ADD COLUMN IF NOT EXISTS `title` varchar(255) NOT NULL COMMENT 'Event title';
ALTER TABLE `lupo_gov_events` ADD COLUMN IF NOT EXISTS `directive_block` text COMMENT 'Captain Wolfie directive content';
ALTER TABLE `lupo_gov_events` ADD COLUMN IF NOT EXISTS `tldr_summary` text COMMENT 'TL;DR summary of the event';
ALTER TABLE `lupo_gov_events` ADD COLUMN IF NOT EXISTS `metadata_json` json COMMENT 'Additional event metadata';
ALTER TABLE `lupo_gov_events` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created';
ALTER TABLE `lupo_gov_events` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when last updated';
ALTER TABLE `lupo_gov_events` ADD COLUMN IF NOT EXISTS `is_active` tinyint NOT NULL DEFAULT 1 COMMENT 'Active flag';
ALTER TABLE `lupo_gov_events` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag';
ALTER TABLE `lupo_gov_events` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted';
ALTER TABLE `lupo_gov_events` MODIFY COLUMN `gov_event_id` bigint NOT NULL auto_increment COMMENT 'Primary key for governance event';
ALTER TABLE `lupo_gov_events` MODIFY COLUMN `utc_group_id` bigint NOT NULL COMMENT 'UTC group identifier';
ALTER TABLE `lupo_gov_events` MODIFY COLUMN `semantic_utc_version` varchar(50) NOT NULL COMMENT 'Semantic UTC version string';
ALTER TABLE `lupo_gov_events` MODIFY COLUMN `canonical_path` varchar(500) NOT NULL COMMENT 'Canonical path for the event';
ALTER TABLE `lupo_gov_events` MODIFY COLUMN `event_type` varchar(100) NOT NULL COMMENT 'Type of governance event';
ALTER TABLE `lupo_gov_events` MODIFY COLUMN `title` varchar(255) NOT NULL COMMENT 'Event title';
ALTER TABLE `lupo_gov_events` MODIFY COLUMN `directive_block` text COMMENT 'Captain Wolfie directive content';
ALTER TABLE `lupo_gov_events` MODIFY COLUMN `tldr_summary` text COMMENT 'TL;DR summary of the event';
ALTER TABLE `lupo_gov_events` MODIFY COLUMN `metadata_json` json COMMENT 'Additional event metadata';
ALTER TABLE `lupo_gov_events` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created';
ALTER TABLE `lupo_gov_events` MODIFY COLUMN `updated_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when last updated';
ALTER TABLE `lupo_gov_events` MODIFY COLUMN `is_active` tinyint NOT NULL DEFAULT 1 COMMENT 'Active flag';
ALTER TABLE `lupo_gov_events` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag';
ALTER TABLE `lupo_gov_events` MODIFY COLUMN `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted';
ALTER TABLE `lupo_gov_events` ADD PRIMARY KEY (`gov_event_id`);
ALTER TABLE `lupo_gov_events` ADD INDEX IF NOT EXISTS `idx_created_ymdhis` (`created_ymdhis`);
ALTER TABLE `lupo_gov_events` ADD INDEX IF NOT EXISTS `idx_event_type` (`event_type`);
ALTER TABLE `lupo_gov_events` ADD INDEX IF NOT EXISTS `idx_is_active` (`is_active`);
ALTER TABLE `lupo_gov_events` ADD INDEX IF NOT EXISTS `idx_is_deleted` (`is_deleted`);
ALTER TABLE `lupo_gov_events` ADD INDEX IF NOT EXISTS `idx_semantic_version` (`semantic_utc_version`);
ALTER TABLE `lupo_gov_events` ADD INDEX IF NOT EXISTS `idx_utc_group` (`utc_group_id`);
ALTER TABLE `lupo_gov_events` ADD UNIQUE INDEX IF NOT EXISTS `unique_canonical_path` (`canonical_path`);

CREATE TABLE IF NOT EXISTS `lupo_gov_timeline_nodes` (
  `timeline_node_id` bigint NOT NULL auto_increment COMMENT 'Primary key for timeline node',
  `gov_event_id` bigint NOT NULL COMMENT 'Associated governance event',
  `node_type` varchar(100) NOT NULL COMMENT 'Type of timeline node',
  `node_title` varchar(255) NOT NULL COMMENT 'Timeline node title',
  `node_description` text COMMENT 'Timeline node description',
  `node_timestamp` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS for the node',
  `parent_node_id` bigint COMMENT 'Parent node for hierarchical timelines',
  `order_sequence` int NOT NULL DEFAULT 0 COMMENT 'Display order',
  `metadata_json` json COMMENT 'Additional node metadata',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
  `updated_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when last updated',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag',
  `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted',
  PRIMARY KEY (`timeline_node_id`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_gov_event` (`gov_event_id`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_node_timestamp` (`node_timestamp`),
  KEY `idx_node_type` (`node_type`),
  KEY `idx_order_sequence` (`order_sequence`),
  KEY `idx_parent_node` (`parent_node_id`)
);

ALTER TABLE `lupo_gov_timeline_nodes` ADD COLUMN IF NOT EXISTS `timeline_node_id` bigint NOT NULL auto_increment COMMENT 'Primary key for timeline node';
ALTER TABLE `lupo_gov_timeline_nodes` ADD COLUMN IF NOT EXISTS `gov_event_id` bigint NOT NULL COMMENT 'Associated governance event';
ALTER TABLE `lupo_gov_timeline_nodes` ADD COLUMN IF NOT EXISTS `node_type` varchar(100) NOT NULL COMMENT 'Type of timeline node';
ALTER TABLE `lupo_gov_timeline_nodes` ADD COLUMN IF NOT EXISTS `node_title` varchar(255) NOT NULL COMMENT 'Timeline node title';
ALTER TABLE `lupo_gov_timeline_nodes` ADD COLUMN IF NOT EXISTS `node_description` text COMMENT 'Timeline node description';
ALTER TABLE `lupo_gov_timeline_nodes` ADD COLUMN IF NOT EXISTS `node_timestamp` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS for the node';
ALTER TABLE `lupo_gov_timeline_nodes` ADD COLUMN IF NOT EXISTS `parent_node_id` bigint COMMENT 'Parent node for hierarchical timelines';
ALTER TABLE `lupo_gov_timeline_nodes` ADD COLUMN IF NOT EXISTS `order_sequence` int NOT NULL DEFAULT 0 COMMENT 'Display order';
ALTER TABLE `lupo_gov_timeline_nodes` ADD COLUMN IF NOT EXISTS `metadata_json` json COMMENT 'Additional node metadata';
ALTER TABLE `lupo_gov_timeline_nodes` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created';
ALTER TABLE `lupo_gov_timeline_nodes` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when last updated';
ALTER TABLE `lupo_gov_timeline_nodes` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag';
ALTER TABLE `lupo_gov_timeline_nodes` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted';
ALTER TABLE `lupo_gov_timeline_nodes` MODIFY COLUMN `timeline_node_id` bigint NOT NULL auto_increment COMMENT 'Primary key for timeline node';
ALTER TABLE `lupo_gov_timeline_nodes` MODIFY COLUMN `gov_event_id` bigint NOT NULL COMMENT 'Associated governance event';
ALTER TABLE `lupo_gov_timeline_nodes` MODIFY COLUMN `node_type` varchar(100) NOT NULL COMMENT 'Type of timeline node';
ALTER TABLE `lupo_gov_timeline_nodes` MODIFY COLUMN `node_title` varchar(255) NOT NULL COMMENT 'Timeline node title';
ALTER TABLE `lupo_gov_timeline_nodes` MODIFY COLUMN `node_description` text COMMENT 'Timeline node description';
ALTER TABLE `lupo_gov_timeline_nodes` MODIFY COLUMN `node_timestamp` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS for the node';
ALTER TABLE `lupo_gov_timeline_nodes` MODIFY COLUMN `parent_node_id` bigint COMMENT 'Parent node for hierarchical timelines';
ALTER TABLE `lupo_gov_timeline_nodes` MODIFY COLUMN `order_sequence` int NOT NULL DEFAULT 0 COMMENT 'Display order';
ALTER TABLE `lupo_gov_timeline_nodes` MODIFY COLUMN `metadata_json` json COMMENT 'Additional node metadata';
ALTER TABLE `lupo_gov_timeline_nodes` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created';
ALTER TABLE `lupo_gov_timeline_nodes` MODIFY COLUMN `updated_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when last updated';
ALTER TABLE `lupo_gov_timeline_nodes` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag';
ALTER TABLE `lupo_gov_timeline_nodes` MODIFY COLUMN `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted';
ALTER TABLE `lupo_gov_timeline_nodes` ADD PRIMARY KEY (`timeline_node_id`);
ALTER TABLE `lupo_gov_timeline_nodes` ADD INDEX IF NOT EXISTS `idx_created_ymdhis` (`created_ymdhis`);
ALTER TABLE `lupo_gov_timeline_nodes` ADD INDEX IF NOT EXISTS `idx_gov_event` (`gov_event_id`);
ALTER TABLE `lupo_gov_timeline_nodes` ADD INDEX IF NOT EXISTS `idx_is_deleted` (`is_deleted`);
ALTER TABLE `lupo_gov_timeline_nodes` ADD INDEX IF NOT EXISTS `idx_node_timestamp` (`node_timestamp`);
ALTER TABLE `lupo_gov_timeline_nodes` ADD INDEX IF NOT EXISTS `idx_node_type` (`node_type`);
ALTER TABLE `lupo_gov_timeline_nodes` ADD INDEX IF NOT EXISTS `idx_order_sequence` (`order_sequence`);
ALTER TABLE `lupo_gov_timeline_nodes` ADD INDEX IF NOT EXISTS `idx_parent_node` (`parent_node_id`);

CREATE TABLE IF NOT EXISTS `lupo_gov_valuations` (
  `valuation_id` bigint NOT NULL auto_increment COMMENT 'Primary key for valuation',
  `gov_event_id` bigint NOT NULL COMMENT 'Associated governance event',
  `valuation_type` varchar(100) NOT NULL COMMENT 'Type of valuation',
  `valuation_metric` varchar(255) NOT NULL COMMENT 'Metric being valued',
  `valuation_value` decimal(20,8) COMMENT 'Numeric valuation value',
  `valuation_text` text COMMENT 'Text-based valuation',
  `valuation_currency` varchar(10) COMMENT 'Currency if applicable',
  `valuation_unit` varchar(50) COMMENT 'Unit of measurement',
  `confidence_score` decimal(5,4) COMMENT 'Confidence in valuation (0.0000-1.0000)',
  `metadata_json` json COMMENT 'Additional valuation metadata',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
  `updated_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when last updated',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag',
  `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted',
  PRIMARY KEY (`valuation_id`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_gov_event` (`gov_event_id`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_valuation_metric` (`valuation_metric`),
  KEY `idx_valuation_type` (`valuation_type`)
);

ALTER TABLE `lupo_gov_valuations` ADD COLUMN IF NOT EXISTS `valuation_id` bigint NOT NULL auto_increment COMMENT 'Primary key for valuation';
ALTER TABLE `lupo_gov_valuations` ADD COLUMN IF NOT EXISTS `gov_event_id` bigint NOT NULL COMMENT 'Associated governance event';
ALTER TABLE `lupo_gov_valuations` ADD COLUMN IF NOT EXISTS `valuation_type` varchar(100) NOT NULL COMMENT 'Type of valuation';
ALTER TABLE `lupo_gov_valuations` ADD COLUMN IF NOT EXISTS `valuation_metric` varchar(255) NOT NULL COMMENT 'Metric being valued';
ALTER TABLE `lupo_gov_valuations` ADD COLUMN IF NOT EXISTS `valuation_value` decimal(20,8) COMMENT 'Numeric valuation value';
ALTER TABLE `lupo_gov_valuations` ADD COLUMN IF NOT EXISTS `valuation_text` text COMMENT 'Text-based valuation';
ALTER TABLE `lupo_gov_valuations` ADD COLUMN IF NOT EXISTS `valuation_currency` varchar(10) COMMENT 'Currency if applicable';
ALTER TABLE `lupo_gov_valuations` ADD COLUMN IF NOT EXISTS `valuation_unit` varchar(50) COMMENT 'Unit of measurement';
ALTER TABLE `lupo_gov_valuations` ADD COLUMN IF NOT EXISTS `confidence_score` decimal(5,4) COMMENT 'Confidence in valuation (0.0000-1.0000)';
ALTER TABLE `lupo_gov_valuations` ADD COLUMN IF NOT EXISTS `metadata_json` json COMMENT 'Additional valuation metadata';
ALTER TABLE `lupo_gov_valuations` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created';
ALTER TABLE `lupo_gov_valuations` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when last updated';
ALTER TABLE `lupo_gov_valuations` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag';
ALTER TABLE `lupo_gov_valuations` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted';
ALTER TABLE `lupo_gov_valuations` MODIFY COLUMN `valuation_id` bigint NOT NULL auto_increment COMMENT 'Primary key for valuation';
ALTER TABLE `lupo_gov_valuations` MODIFY COLUMN `gov_event_id` bigint NOT NULL COMMENT 'Associated governance event';
ALTER TABLE `lupo_gov_valuations` MODIFY COLUMN `valuation_type` varchar(100) NOT NULL COMMENT 'Type of valuation';
ALTER TABLE `lupo_gov_valuations` MODIFY COLUMN `valuation_metric` varchar(255) NOT NULL COMMENT 'Metric being valued';
ALTER TABLE `lupo_gov_valuations` MODIFY COLUMN `valuation_value` decimal(20,8) COMMENT 'Numeric valuation value';
ALTER TABLE `lupo_gov_valuations` MODIFY COLUMN `valuation_text` text COMMENT 'Text-based valuation';
ALTER TABLE `lupo_gov_valuations` MODIFY COLUMN `valuation_currency` varchar(10) COMMENT 'Currency if applicable';
ALTER TABLE `lupo_gov_valuations` MODIFY COLUMN `valuation_unit` varchar(50) COMMENT 'Unit of measurement';
ALTER TABLE `lupo_gov_valuations` MODIFY COLUMN `confidence_score` decimal(5,4) COMMENT 'Confidence in valuation (0.0000-1.0000)';
ALTER TABLE `lupo_gov_valuations` MODIFY COLUMN `metadata_json` json COMMENT 'Additional valuation metadata';
ALTER TABLE `lupo_gov_valuations` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created';
ALTER TABLE `lupo_gov_valuations` MODIFY COLUMN `updated_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when last updated';
ALTER TABLE `lupo_gov_valuations` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag';
ALTER TABLE `lupo_gov_valuations` MODIFY COLUMN `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted';
ALTER TABLE `lupo_gov_valuations` ADD PRIMARY KEY (`valuation_id`);
ALTER TABLE `lupo_gov_valuations` ADD INDEX IF NOT EXISTS `idx_created_ymdhis` (`created_ymdhis`);
ALTER TABLE `lupo_gov_valuations` ADD INDEX IF NOT EXISTS `idx_gov_event` (`gov_event_id`);
ALTER TABLE `lupo_gov_valuations` ADD INDEX IF NOT EXISTS `idx_is_deleted` (`is_deleted`);
ALTER TABLE `lupo_gov_valuations` ADD INDEX IF NOT EXISTS `idx_valuation_metric` (`valuation_metric`);
ALTER TABLE `lupo_gov_valuations` ADD INDEX IF NOT EXISTS `idx_valuation_type` (`valuation_type`);

CREATE TABLE IF NOT EXISTS `lupo_governance_overrides` (
  `governance_overrid_id` bigint NOT NULL auto_increment,
  `agent_id` bigint COMMENT 'Agent whose behavior is being overridden',
  `applied_by_agent` bigint COMMENT 'Agent or system that applied the override',
  `override_type` varchar(100) NOT NULL COMMENT 'capability_block, faucet_lock, safety_rule, escalation, etc.',
  `target_key` varchar(150) COMMENT 'Capability, faucet, or rule being overridden',
  `old_value` text COMMENT 'Previous value before override',
  `new_value` text COMMENT 'New value after override',
  `reason_text` text COMMENT 'Human-readable explanation for the override',
  `metadata_json` json COMMENT 'Additional structured metadata',
  `created_ymdhis` bigint NOT NULL,
  `expires_ymdhis` bigint COMMENT 'Optional expiration bigint',
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (`governance_overrid_id`),
  KEY `idx_agent` (`agent_id`),
  KEY `idx_applied_by` (`applied_by_agent`),
  KEY `idx_created` (`created_ymdhis`),
  KEY `idx_target` (`target_key`),
  KEY `idx_type` (`override_type`)
);

ALTER TABLE `lupo_governance_overrides` ADD COLUMN IF NOT EXISTS `governance_overrid_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_governance_overrides` ADD COLUMN IF NOT EXISTS `agent_id` bigint COMMENT 'Agent whose behavior is being overridden';
ALTER TABLE `lupo_governance_overrides` ADD COLUMN IF NOT EXISTS `applied_by_agent` bigint COMMENT 'Agent or system that applied the override';
ALTER TABLE `lupo_governance_overrides` ADD COLUMN IF NOT EXISTS `override_type` varchar(100) NOT NULL COMMENT 'capability_block, faucet_lock, safety_rule, escalation, etc.';
ALTER TABLE `lupo_governance_overrides` ADD COLUMN IF NOT EXISTS `target_key` varchar(150) COMMENT 'Capability, faucet, or rule being overridden';
ALTER TABLE `lupo_governance_overrides` ADD COLUMN IF NOT EXISTS `old_value` text COMMENT 'Previous value before override';
ALTER TABLE `lupo_governance_overrides` ADD COLUMN IF NOT EXISTS `new_value` text COMMENT 'New value after override';
ALTER TABLE `lupo_governance_overrides` ADD COLUMN IF NOT EXISTS `reason_text` text COMMENT 'Human-readable explanation for the override';
ALTER TABLE `lupo_governance_overrides` ADD COLUMN IF NOT EXISTS `metadata_json` json COMMENT 'Additional structured metadata';
ALTER TABLE `lupo_governance_overrides` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_governance_overrides` ADD COLUMN IF NOT EXISTS `expires_ymdhis` bigint COMMENT 'Optional expiration bigint';
ALTER TABLE `lupo_governance_overrides` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_governance_overrides` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint;
ALTER TABLE `lupo_governance_overrides` MODIFY COLUMN `governance_overrid_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_governance_overrides` MODIFY COLUMN `agent_id` bigint COMMENT 'Agent whose behavior is being overridden';
ALTER TABLE `lupo_governance_overrides` MODIFY COLUMN `applied_by_agent` bigint COMMENT 'Agent or system that applied the override';
ALTER TABLE `lupo_governance_overrides` MODIFY COLUMN `override_type` varchar(100) NOT NULL COMMENT 'capability_block, faucet_lock, safety_rule, escalation, etc.';
ALTER TABLE `lupo_governance_overrides` MODIFY COLUMN `target_key` varchar(150) COMMENT 'Capability, faucet, or rule being overridden';
ALTER TABLE `lupo_governance_overrides` MODIFY COLUMN `old_value` text COMMENT 'Previous value before override';
ALTER TABLE `lupo_governance_overrides` MODIFY COLUMN `new_value` text COMMENT 'New value after override';
ALTER TABLE `lupo_governance_overrides` MODIFY COLUMN `reason_text` text COMMENT 'Human-readable explanation for the override';
ALTER TABLE `lupo_governance_overrides` MODIFY COLUMN `metadata_json` json COMMENT 'Additional structured metadata';
ALTER TABLE `lupo_governance_overrides` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_governance_overrides` MODIFY COLUMN `expires_ymdhis` bigint COMMENT 'Optional expiration bigint';
ALTER TABLE `lupo_governance_overrides` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_governance_overrides` MODIFY COLUMN `deleted_ymdhis` bigint;
ALTER TABLE `lupo_governance_overrides` ADD PRIMARY KEY (`governance_overrid_id`);
ALTER TABLE `lupo_governance_overrides` ADD INDEX IF NOT EXISTS `idx_agent` (`agent_id`);
ALTER TABLE `lupo_governance_overrides` ADD INDEX IF NOT EXISTS `idx_applied_by` (`applied_by_agent`);
ALTER TABLE `lupo_governance_overrides` ADD INDEX IF NOT EXISTS `idx_created` (`created_ymdhis`);
ALTER TABLE `lupo_governance_overrides` ADD INDEX IF NOT EXISTS `idx_target` (`target_key`);
ALTER TABLE `lupo_governance_overrides` ADD INDEX IF NOT EXISTS `idx_type` (`override_type`);

CREATE TABLE IF NOT EXISTS `lupo_groups` (
  `group_id` bigint NOT NULL auto_increment,
  `name` varchar(50) NOT NULL COMMENT 'Unique name of the group within the domain',
  `description` text COMMENT 'Optional detailed description of the group',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when group was created',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when group was last updated',
  `created_by` bigint COMMENT 'actor_id of the creator',
  `is_active` tinyint NOT NULL DEFAULT 1 COMMENT '1 = active, 0 = inactive',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when soft-deleted',
  `is_system` tinyint NOT NULL DEFAULT 0 COMMENT '1 = system group (cannot be deleted)',
  `settings` json COMMENT 'JSON-encoded group-specific settings',
  PRIMARY KEY (`group_id`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_is_active` (`is_active`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_updated_ymdhis` (`updated_ymdhis`),
  UNIQUE KEY `unique_group_domain` (`name`)
);

ALTER TABLE `lupo_groups` ADD COLUMN IF NOT EXISTS `group_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_groups` ADD COLUMN IF NOT EXISTS `name` varchar(50) NOT NULL COMMENT 'Unique name of the group within the domain';
ALTER TABLE `lupo_groups` ADD COLUMN IF NOT EXISTS `description` text COMMENT 'Optional detailed description of the group';
ALTER TABLE `lupo_groups` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when group was created';
ALTER TABLE `lupo_groups` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when group was last updated';
ALTER TABLE `lupo_groups` ADD COLUMN IF NOT EXISTS `created_by` bigint COMMENT 'actor_id of the creator';
ALTER TABLE `lupo_groups` ADD COLUMN IF NOT EXISTS `is_active` tinyint NOT NULL DEFAULT 1 COMMENT '1 = active, 0 = inactive';
ALTER TABLE `lupo_groups` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted';
ALTER TABLE `lupo_groups` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when soft-deleted';
ALTER TABLE `lupo_groups` ADD COLUMN IF NOT EXISTS `is_system` tinyint NOT NULL DEFAULT 0 COMMENT '1 = system group (cannot be deleted)';
ALTER TABLE `lupo_groups` ADD COLUMN IF NOT EXISTS `settings` json COMMENT 'JSON-encoded group-specific settings';
ALTER TABLE `lupo_groups` MODIFY COLUMN `group_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_groups` MODIFY COLUMN `name` varchar(50) NOT NULL COMMENT 'Unique name of the group within the domain';
ALTER TABLE `lupo_groups` MODIFY COLUMN `description` text COMMENT 'Optional detailed description of the group';
ALTER TABLE `lupo_groups` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when group was created';
ALTER TABLE `lupo_groups` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when group was last updated';
ALTER TABLE `lupo_groups` MODIFY COLUMN `created_by` bigint COMMENT 'actor_id of the creator';
ALTER TABLE `lupo_groups` MODIFY COLUMN `is_active` tinyint NOT NULL DEFAULT 1 COMMENT '1 = active, 0 = inactive';
ALTER TABLE `lupo_groups` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted';
ALTER TABLE `lupo_groups` MODIFY COLUMN `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when soft-deleted';
ALTER TABLE `lupo_groups` MODIFY COLUMN `is_system` tinyint NOT NULL DEFAULT 0 COMMENT '1 = system group (cannot be deleted)';
ALTER TABLE `lupo_groups` MODIFY COLUMN `settings` json COMMENT 'JSON-encoded group-specific settings';
ALTER TABLE `lupo_groups` ADD PRIMARY KEY (`group_id`);
ALTER TABLE `lupo_groups` ADD INDEX IF NOT EXISTS `idx_created_ymdhis` (`created_ymdhis`);
ALTER TABLE `lupo_groups` ADD INDEX IF NOT EXISTS `idx_is_active` (`is_active`);
ALTER TABLE `lupo_groups` ADD INDEX IF NOT EXISTS `idx_is_deleted` (`is_deleted`);
ALTER TABLE `lupo_groups` ADD INDEX IF NOT EXISTS `idx_updated_ymdhis` (`updated_ymdhis`);
ALTER TABLE `lupo_groups` ADD UNIQUE INDEX IF NOT EXISTS `unique_group_domain` (`name`);

CREATE TABLE IF NOT EXISTS `lupo_hashtags` (
  `hashtag_id` bigint NOT NULL auto_increment,
  `hashtag_slug` varchar(255) NOT NULL,
  `description` text,
  `meta_json` json,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (`hashtag_id`),
  KEY `idx_hashtag_slug` (`hashtag_slug`),
  KEY `idx_is_deleted` (`is_deleted`)
);

ALTER TABLE `lupo_hashtags` ADD COLUMN IF NOT EXISTS `hashtag_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_hashtags` ADD COLUMN IF NOT EXISTS `hashtag_slug` varchar(255) NOT NULL;
ALTER TABLE `lupo_hashtags` ADD COLUMN IF NOT EXISTS `description` text;
ALTER TABLE `lupo_hashtags` ADD COLUMN IF NOT EXISTS `meta_json` json;
ALTER TABLE `lupo_hashtags` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_hashtags` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_hashtags` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_hashtags` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_hashtags` MODIFY COLUMN `hashtag_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_hashtags` MODIFY COLUMN `hashtag_slug` varchar(255) NOT NULL;
ALTER TABLE `lupo_hashtags` MODIFY COLUMN `description` text;
ALTER TABLE `lupo_hashtags` MODIFY COLUMN `meta_json` json;
ALTER TABLE `lupo_hashtags` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_hashtags` MODIFY COLUMN `deleted_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_hashtags` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_hashtags` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_hashtags` ADD PRIMARY KEY (`hashtag_id`);
ALTER TABLE `lupo_hashtags` ADD INDEX IF NOT EXISTS `idx_hashtag_slug` (`hashtag_slug`);
ALTER TABLE `lupo_hashtags` ADD INDEX IF NOT EXISTS `idx_is_deleted` (`is_deleted`);

CREATE TABLE IF NOT EXISTS `lupo_help_topics` (
  `help_topic_id` bigint NOT NULL auto_increment COMMENT 'Primary key for help topic',
  `slug` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content_html` longtext,
  `content_markdown` longtext,
  `category` varchar(100),
  `parent_slug` varchar(255),
  `view_count` bigint DEFAULT 0,
  `helpful_count` bigint DEFAULT 0,
  `not_helpful_count` bigint DEFAULT 0,
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC creation bigint (YYYYMMDDHHMMSS)',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC last update bigint (YYYYMMDDHHMMSS)',
  `author_actor_id` bigint COMMENT 'Author actor ID',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted, 0=active)',
  PRIMARY KEY (`help_topic_id`),
  KEY `idx_author` (`author_actor_id`),
  KEY `idx_category` (`category`),
  KEY `idx_created` (`created_ymdhis`),
  KEY `idx_parent` (`parent_slug`),
  KEY `idx_slug` (`slug`),
  UNIQUE KEY `slug` (`slug`)
);

ALTER TABLE `lupo_help_topics` ADD COLUMN IF NOT EXISTS `help_topic_id` bigint NOT NULL auto_increment COMMENT 'Primary key for help topic';
ALTER TABLE `lupo_help_topics` ADD COLUMN IF NOT EXISTS `slug` varchar(255) NOT NULL;
ALTER TABLE `lupo_help_topics` ADD COLUMN IF NOT EXISTS `title` varchar(255) NOT NULL;
ALTER TABLE `lupo_help_topics` ADD COLUMN IF NOT EXISTS `content_html` longtext;
ALTER TABLE `lupo_help_topics` ADD COLUMN IF NOT EXISTS `content_markdown` longtext;
ALTER TABLE `lupo_help_topics` ADD COLUMN IF NOT EXISTS `category` varchar(100);
ALTER TABLE `lupo_help_topics` ADD COLUMN IF NOT EXISTS `parent_slug` varchar(255);
ALTER TABLE `lupo_help_topics` ADD COLUMN IF NOT EXISTS `view_count` bigint DEFAULT 0;
ALTER TABLE `lupo_help_topics` ADD COLUMN IF NOT EXISTS `helpful_count` bigint DEFAULT 0;
ALTER TABLE `lupo_help_topics` ADD COLUMN IF NOT EXISTS `not_helpful_count` bigint DEFAULT 0;
ALTER TABLE `lupo_help_topics` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'UTC creation bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_help_topics` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL COMMENT 'UTC last update bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_help_topics` ADD COLUMN IF NOT EXISTS `author_actor_id` bigint COMMENT 'Author actor ID';
ALTER TABLE `lupo_help_topics` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted, 0=active)';
ALTER TABLE `lupo_help_topics` MODIFY COLUMN `help_topic_id` bigint NOT NULL auto_increment COMMENT 'Primary key for help topic';
ALTER TABLE `lupo_help_topics` MODIFY COLUMN `slug` varchar(255) NOT NULL;
ALTER TABLE `lupo_help_topics` MODIFY COLUMN `title` varchar(255) NOT NULL;
ALTER TABLE `lupo_help_topics` MODIFY COLUMN `content_html` longtext;
ALTER TABLE `lupo_help_topics` MODIFY COLUMN `content_markdown` longtext;
ALTER TABLE `lupo_help_topics` MODIFY COLUMN `category` varchar(100);
ALTER TABLE `lupo_help_topics` MODIFY COLUMN `parent_slug` varchar(255);
ALTER TABLE `lupo_help_topics` MODIFY COLUMN `view_count` bigint DEFAULT 0;
ALTER TABLE `lupo_help_topics` MODIFY COLUMN `helpful_count` bigint DEFAULT 0;
ALTER TABLE `lupo_help_topics` MODIFY COLUMN `not_helpful_count` bigint DEFAULT 0;
ALTER TABLE `lupo_help_topics` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'UTC creation bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_help_topics` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL COMMENT 'UTC last update bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_help_topics` MODIFY COLUMN `author_actor_id` bigint COMMENT 'Author actor ID';
ALTER TABLE `lupo_help_topics` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted, 0=active)';
ALTER TABLE `lupo_help_topics` ADD PRIMARY KEY (`help_topic_id`);
ALTER TABLE `lupo_help_topics` ADD INDEX IF NOT EXISTS `idx_author` (`author_actor_id`);
ALTER TABLE `lupo_help_topics` ADD INDEX IF NOT EXISTS `idx_category` (`category`);
ALTER TABLE `lupo_help_topics` ADD INDEX IF NOT EXISTS `idx_created` (`created_ymdhis`);
ALTER TABLE `lupo_help_topics` ADD INDEX IF NOT EXISTS `idx_parent` (`parent_slug`);
ALTER TABLE `lupo_help_topics` ADD INDEX IF NOT EXISTS `idx_slug` (`slug`);
ALTER TABLE `lupo_help_topics` ADD UNIQUE INDEX IF NOT EXISTS `slug` (`slug`);

CREATE TABLE IF NOT EXISTS `lupo_help_topics_old` (
  `help_topics_old_id` bigint NOT NULL auto_increment,
  `slug` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content_html` longtext,
  `category` varchar(100),
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (`help_topics_old_id`),
  KEY `idx_category` (`category`),
  KEY `idx_is_deleted` (`is_deleted`),
  UNIQUE KEY `uniq_slug` (`slug`)
);

ALTER TABLE `lupo_help_topics_old` ADD COLUMN IF NOT EXISTS `help_topics_old_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_help_topics_old` ADD COLUMN IF NOT EXISTS `slug` varchar(255) NOT NULL;
ALTER TABLE `lupo_help_topics_old` ADD COLUMN IF NOT EXISTS `title` varchar(255) NOT NULL;
ALTER TABLE `lupo_help_topics_old` ADD COLUMN IF NOT EXISTS `content_html` longtext;
ALTER TABLE `lupo_help_topics_old` ADD COLUMN IF NOT EXISTS `category` varchar(100);
ALTER TABLE `lupo_help_topics_old` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_help_topics_old` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_help_topics_old` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_help_topics_old` MODIFY COLUMN `help_topics_old_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_help_topics_old` MODIFY COLUMN `slug` varchar(255) NOT NULL;
ALTER TABLE `lupo_help_topics_old` MODIFY COLUMN `title` varchar(255) NOT NULL;
ALTER TABLE `lupo_help_topics_old` MODIFY COLUMN `content_html` longtext;
ALTER TABLE `lupo_help_topics_old` MODIFY COLUMN `category` varchar(100);
ALTER TABLE `lupo_help_topics_old` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_help_topics_old` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_help_topics_old` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_help_topics_old` ADD PRIMARY KEY (`help_topics_old_id`);
ALTER TABLE `lupo_help_topics_old` ADD INDEX IF NOT EXISTS `idx_category` (`category`);
ALTER TABLE `lupo_help_topics_old` ADD INDEX IF NOT EXISTS `idx_is_deleted` (`is_deleted`);
ALTER TABLE `lupo_help_topics_old` ADD UNIQUE INDEX IF NOT EXISTS `uniq_slug` (`slug`);

CREATE TABLE IF NOT EXISTS `lupo_help_tree` (
  `help_tree_id` bigint NOT NULL auto_increment,
  `parent_id` bigint,
  `department_id` bigint NOT NULL DEFAULT 1,
  `content_id` bigint,
  `title` varchar(255) NOT NULL,
  `description` text,
  `action_type` enum('none','ai_agent','department','url','content','message') NOT NULL DEFAULT 'none',
  `action_target` varchar(255),
  `sort_order` int NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (`help_tree_id`),
  KEY `idx_action` (`action_type`, `action_target`),
  KEY `idx_content` (`content_id`),
  KEY `idx_created` (`created_ymdhis`),
  KEY `idx_department` (`department_id`),
  KEY `idx_parent` (`parent_id`),
  KEY `idx_sort` (`parent_id`, `sort_order`),
  KEY `idx_updated` (`updated_ymdhis`)
);

ALTER TABLE `lupo_help_tree` ADD COLUMN IF NOT EXISTS `help_tree_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_help_tree` ADD COLUMN IF NOT EXISTS `parent_id` bigint;
ALTER TABLE `lupo_help_tree` ADD COLUMN IF NOT EXISTS `department_id` bigint NOT NULL DEFAULT 1;
ALTER TABLE `lupo_help_tree` ADD COLUMN IF NOT EXISTS `content_id` bigint;
ALTER TABLE `lupo_help_tree` ADD COLUMN IF NOT EXISTS `title` varchar(255) NOT NULL;
ALTER TABLE `lupo_help_tree` ADD COLUMN IF NOT EXISTS `description` text;
ALTER TABLE `lupo_help_tree` ADD COLUMN IF NOT EXISTS `action_type` enum('none','ai_agent','department','url','content','message') NOT NULL DEFAULT 'none';
ALTER TABLE `lupo_help_tree` ADD COLUMN IF NOT EXISTS `action_target` varchar(255);
ALTER TABLE `lupo_help_tree` ADD COLUMN IF NOT EXISTS `sort_order` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_help_tree` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_help_tree` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_help_tree` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_help_tree` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint;
ALTER TABLE `lupo_help_tree` MODIFY COLUMN `help_tree_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_help_tree` MODIFY COLUMN `parent_id` bigint;
ALTER TABLE `lupo_help_tree` MODIFY COLUMN `department_id` bigint NOT NULL DEFAULT 1;
ALTER TABLE `lupo_help_tree` MODIFY COLUMN `content_id` bigint;
ALTER TABLE `lupo_help_tree` MODIFY COLUMN `title` varchar(255) NOT NULL;
ALTER TABLE `lupo_help_tree` MODIFY COLUMN `description` text;
ALTER TABLE `lupo_help_tree` MODIFY COLUMN `action_type` enum('none','ai_agent','department','url','content','message') NOT NULL DEFAULT 'none';
ALTER TABLE `lupo_help_tree` MODIFY COLUMN `action_target` varchar(255);
ALTER TABLE `lupo_help_tree` MODIFY COLUMN `sort_order` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_help_tree` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_help_tree` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_help_tree` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_help_tree` MODIFY COLUMN `deleted_ymdhis` bigint;
ALTER TABLE `lupo_help_tree` ADD PRIMARY KEY (`help_tree_id`);
ALTER TABLE `lupo_help_tree` ADD INDEX IF NOT EXISTS `idx_action` (`action_type`, `action_target`);
ALTER TABLE `lupo_help_tree` ADD INDEX IF NOT EXISTS `idx_content` (`content_id`);
ALTER TABLE `lupo_help_tree` ADD INDEX IF NOT EXISTS `idx_created` (`created_ymdhis`);
ALTER TABLE `lupo_help_tree` ADD INDEX IF NOT EXISTS `idx_department` (`department_id`);
ALTER TABLE `lupo_help_tree` ADD INDEX IF NOT EXISTS `idx_parent` (`parent_id`);
ALTER TABLE `lupo_help_tree` ADD INDEX IF NOT EXISTS `idx_sort` (`parent_id`, `sort_order`);
ALTER TABLE `lupo_help_tree` ADD INDEX IF NOT EXISTS `idx_updated` (`updated_ymdhis`);

CREATE TABLE IF NOT EXISTS `lupo_hotfix_registry` (
  `hotfix_id` int NOT NULL auto_increment,
  `hotfix_version` varchar(20) NOT NULL,
  `applied_ymdhis` bigint NOT NULL,
  `applied_by_actor_id` int,
  `description` text,
  `metadata_json` json,
  PRIMARY KEY (`hotfix_id`)
);

ALTER TABLE `lupo_hotfix_registry` ADD COLUMN IF NOT EXISTS `hotfix_id` int NOT NULL auto_increment;
ALTER TABLE `lupo_hotfix_registry` ADD COLUMN IF NOT EXISTS `hotfix_version` varchar(20) NOT NULL;
ALTER TABLE `lupo_hotfix_registry` ADD COLUMN IF NOT EXISTS `applied_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_hotfix_registry` ADD COLUMN IF NOT EXISTS `applied_by_actor_id` int;
ALTER TABLE `lupo_hotfix_registry` ADD COLUMN IF NOT EXISTS `description` text;
ALTER TABLE `lupo_hotfix_registry` ADD COLUMN IF NOT EXISTS `metadata_json` json;
ALTER TABLE `lupo_hotfix_registry` MODIFY COLUMN `hotfix_id` int NOT NULL auto_increment;
ALTER TABLE `lupo_hotfix_registry` MODIFY COLUMN `hotfix_version` varchar(20) NOT NULL;
ALTER TABLE `lupo_hotfix_registry` MODIFY COLUMN `applied_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_hotfix_registry` MODIFY COLUMN `applied_by_actor_id` int;
ALTER TABLE `lupo_hotfix_registry` MODIFY COLUMN `description` text;
ALTER TABLE `lupo_hotfix_registry` MODIFY COLUMN `metadata_json` json;
ALTER TABLE `lupo_hotfix_registry` ADD PRIMARY KEY (`hotfix_id`);

CREATE TABLE IF NOT EXISTS `lupo_human_history_meta` (
  `meta_id` bigint unsigned NOT NULL auto_increment,
  `event_key` varchar(255) NOT NULL,
  `tensor_mapping` varchar(32) NOT NULL,
  `philosophical_reference` varchar(255) NOT NULL,
  `system_impact` text NOT NULL,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  PRIMARY KEY (`meta_id`)
);

ALTER TABLE `lupo_human_history_meta` ADD COLUMN IF NOT EXISTS `meta_id` bigint unsigned NOT NULL auto_increment;
ALTER TABLE `lupo_human_history_meta` ADD COLUMN IF NOT EXISTS `event_key` varchar(255) NOT NULL;
ALTER TABLE `lupo_human_history_meta` ADD COLUMN IF NOT EXISTS `tensor_mapping` varchar(32) NOT NULL;
ALTER TABLE `lupo_human_history_meta` ADD COLUMN IF NOT EXISTS `philosophical_reference` varchar(255) NOT NULL;
ALTER TABLE `lupo_human_history_meta` ADD COLUMN IF NOT EXISTS `system_impact` text NOT NULL;
ALTER TABLE `lupo_human_history_meta` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_human_history_meta` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_human_history_meta` MODIFY COLUMN `meta_id` bigint unsigned NOT NULL auto_increment;
ALTER TABLE `lupo_human_history_meta` MODIFY COLUMN `event_key` varchar(255) NOT NULL;
ALTER TABLE `lupo_human_history_meta` MODIFY COLUMN `tensor_mapping` varchar(32) NOT NULL;
ALTER TABLE `lupo_human_history_meta` MODIFY COLUMN `philosophical_reference` varchar(255) NOT NULL;
ALTER TABLE `lupo_human_history_meta` MODIFY COLUMN `system_impact` text NOT NULL;
ALTER TABLE `lupo_human_history_meta` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_human_history_meta` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_human_history_meta` ADD PRIMARY KEY (`meta_id`);

CREATE TABLE IF NOT EXISTS `lupo_interface_translations` (
  `interface_translation_id` bigint NOT NULL auto_increment,
  `language_code` varchar(8) NOT NULL COMMENT 'ISO 639-1 (2-letter) or BCP 47 language code',
  `translation_key` varchar(128) NOT NULL COMMENT 'Unique key for the UI string',
  `translation_text` text NOT NULL,
  `context` varchar(64) COMMENT 'Optional context for disambiguation',
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint,
  `created_by` bigint,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  `version` int DEFAULT 1,
  `is_approved` tinyint(1) DEFAULT 0 COMMENT 'Whether this translation is approved',
  `approved_by` bigint,
  PRIMARY KEY (`interface_translation_id`),
  KEY `ft_translation_text` (`translation_text`),
  KEY `idx_approved` (`is_approved`),
  KEY `idx_created` (`created_ymdhis`),
  KEY `idx_deleted` (`is_deleted`),
  KEY `idx_updated` (`updated_ymdhis`),
  UNIQUE KEY `unq_language_key` (`language_code`, `translation_key`)
);

ALTER TABLE `lupo_interface_translations` ADD COLUMN IF NOT EXISTS `interface_translation_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_interface_translations` ADD COLUMN IF NOT EXISTS `language_code` varchar(8) NOT NULL COMMENT 'ISO 639-1 (2-letter) or BCP 47 language code';
ALTER TABLE `lupo_interface_translations` ADD COLUMN IF NOT EXISTS `translation_key` varchar(128) NOT NULL COMMENT 'Unique key for the UI string';
ALTER TABLE `lupo_interface_translations` ADD COLUMN IF NOT EXISTS `translation_text` text NOT NULL;
ALTER TABLE `lupo_interface_translations` ADD COLUMN IF NOT EXISTS `context` varchar(64) COMMENT 'Optional context for disambiguation';
ALTER TABLE `lupo_interface_translations` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_interface_translations` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint;
ALTER TABLE `lupo_interface_translations` ADD COLUMN IF NOT EXISTS `created_by` bigint;
ALTER TABLE `lupo_interface_translations` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint(1) NOT NULL DEFAULT 0;
ALTER TABLE `lupo_interface_translations` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint;
ALTER TABLE `lupo_interface_translations` ADD COLUMN IF NOT EXISTS `version` int DEFAULT 1;
ALTER TABLE `lupo_interface_translations` ADD COLUMN IF NOT EXISTS `is_approved` tinyint(1) DEFAULT 0 COMMENT 'Whether this translation is approved';
ALTER TABLE `lupo_interface_translations` ADD COLUMN IF NOT EXISTS `approved_by` bigint;
ALTER TABLE `lupo_interface_translations` MODIFY COLUMN `interface_translation_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_interface_translations` MODIFY COLUMN `language_code` varchar(8) NOT NULL COMMENT 'ISO 639-1 (2-letter) or BCP 47 language code';
ALTER TABLE `lupo_interface_translations` MODIFY COLUMN `translation_key` varchar(128) NOT NULL COMMENT 'Unique key for the UI string';
ALTER TABLE `lupo_interface_translations` MODIFY COLUMN `translation_text` text NOT NULL;
ALTER TABLE `lupo_interface_translations` MODIFY COLUMN `context` varchar(64) COMMENT 'Optional context for disambiguation';
ALTER TABLE `lupo_interface_translations` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_interface_translations` MODIFY COLUMN `updated_ymdhis` bigint;
ALTER TABLE `lupo_interface_translations` MODIFY COLUMN `created_by` bigint;
ALTER TABLE `lupo_interface_translations` MODIFY COLUMN `is_deleted` tinyint(1) NOT NULL DEFAULT 0;
ALTER TABLE `lupo_interface_translations` MODIFY COLUMN `deleted_ymdhis` bigint;
ALTER TABLE `lupo_interface_translations` MODIFY COLUMN `version` int DEFAULT 1;
ALTER TABLE `lupo_interface_translations` MODIFY COLUMN `is_approved` tinyint(1) DEFAULT 0 COMMENT 'Whether this translation is approved';
ALTER TABLE `lupo_interface_translations` MODIFY COLUMN `approved_by` bigint;
ALTER TABLE `lupo_interface_translations` ADD PRIMARY KEY (`interface_translation_id`);
ALTER TABLE `lupo_interface_translations` ADD INDEX IF NOT EXISTS `ft_translation_text` (`translation_text`);
ALTER TABLE `lupo_interface_translations` ADD INDEX IF NOT EXISTS `idx_approved` (`is_approved`);
ALTER TABLE `lupo_interface_translations` ADD INDEX IF NOT EXISTS `idx_created` (`created_ymdhis`);
ALTER TABLE `lupo_interface_translations` ADD INDEX IF NOT EXISTS `idx_deleted` (`is_deleted`);
ALTER TABLE `lupo_interface_translations` ADD INDEX IF NOT EXISTS `idx_updated` (`updated_ymdhis`);
ALTER TABLE `lupo_interface_translations` ADD UNIQUE INDEX IF NOT EXISTS `unq_language_key` (`language_code`, `translation_key`);

CREATE TABLE IF NOT EXISTS `lupo_interpretation_log` (
  `interpretation_log_id` bigint NOT NULL auto_increment COMMENT 'Primary key for the interpretation log',
  `agent_id` bigint NOT NULL COMMENT 'ID of the agent that generated the interpretation',
  `entity_type` varchar(32) NOT NULL COMMENT 'Type of the interpreted entity',
  `entity_id` bigint NOT NULL COMMENT 'ID of the interpreted entity',
  `interpretation` text NOT NULL COMMENT 'The interpretation content',
  `confidence_score` decimal(5,2) COMMENT 'Confidence score of the interpretation (0.00-1.00)',
  `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint (YYYYMMDDHHMMSS)',
  `updated_ymdhis` bigint NOT NULL COMMENT 'Last update bigint (YYYYMMDDHHMMSS)',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted, 0=active)',
  `deleted_ymdhis` bigint COMMENT 'Deletion bigint (YYYYMMDDHHMMSS)',
  `metadata_json` json COMMENT 'Additional metadata about the interpretation',
  PRIMARY KEY (`interpretation_log_id`),
  KEY `idx_agent` (`agent_id`),
  KEY `idx_confidence` (`confidence_score`),
  KEY `idx_created` (`created_ymdhis`),
  KEY `idx_deleted` (`is_deleted`),
  KEY `idx_entity` (`entity_type`, `entity_id`),
  KEY `idx_updated` (`updated_ymdhis`)
);

ALTER TABLE `lupo_interpretation_log` ADD COLUMN IF NOT EXISTS `interpretation_log_id` bigint NOT NULL auto_increment COMMENT 'Primary key for the interpretation log';
ALTER TABLE `lupo_interpretation_log` ADD COLUMN IF NOT EXISTS `agent_id` bigint NOT NULL COMMENT 'ID of the agent that generated the interpretation';
ALTER TABLE `lupo_interpretation_log` ADD COLUMN IF NOT EXISTS `entity_type` varchar(32) NOT NULL COMMENT 'Type of the interpreted entity';
ALTER TABLE `lupo_interpretation_log` ADD COLUMN IF NOT EXISTS `entity_id` bigint NOT NULL COMMENT 'ID of the interpreted entity';
ALTER TABLE `lupo_interpretation_log` ADD COLUMN IF NOT EXISTS `interpretation` text NOT NULL COMMENT 'The interpretation content';
ALTER TABLE `lupo_interpretation_log` ADD COLUMN IF NOT EXISTS `confidence_score` decimal(5,2) COMMENT 'Confidence score of the interpretation (0.00-1.00)';
ALTER TABLE `lupo_interpretation_log` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_interpretation_log` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL COMMENT 'Last update bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_interpretation_log` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted, 0=active)';
ALTER TABLE `lupo_interpretation_log` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint COMMENT 'Deletion bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_interpretation_log` ADD COLUMN IF NOT EXISTS `metadata_json` json COMMENT 'Additional metadata about the interpretation';
ALTER TABLE `lupo_interpretation_log` MODIFY COLUMN `interpretation_log_id` bigint NOT NULL auto_increment COMMENT 'Primary key for the interpretation log';
ALTER TABLE `lupo_interpretation_log` MODIFY COLUMN `agent_id` bigint NOT NULL COMMENT 'ID of the agent that generated the interpretation';
ALTER TABLE `lupo_interpretation_log` MODIFY COLUMN `entity_type` varchar(32) NOT NULL COMMENT 'Type of the interpreted entity';
ALTER TABLE `lupo_interpretation_log` MODIFY COLUMN `entity_id` bigint NOT NULL COMMENT 'ID of the interpreted entity';
ALTER TABLE `lupo_interpretation_log` MODIFY COLUMN `interpretation` text NOT NULL COMMENT 'The interpretation content';
ALTER TABLE `lupo_interpretation_log` MODIFY COLUMN `confidence_score` decimal(5,2) COMMENT 'Confidence score of the interpretation (0.00-1.00)';
ALTER TABLE `lupo_interpretation_log` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_interpretation_log` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL COMMENT 'Last update bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_interpretation_log` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted, 0=active)';
ALTER TABLE `lupo_interpretation_log` MODIFY COLUMN `deleted_ymdhis` bigint COMMENT 'Deletion bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_interpretation_log` MODIFY COLUMN `metadata_json` json COMMENT 'Additional metadata about the interpretation';
ALTER TABLE `lupo_interpretation_log` ADD PRIMARY KEY (`interpretation_log_id`);
ALTER TABLE `lupo_interpretation_log` ADD INDEX IF NOT EXISTS `idx_agent` (`agent_id`);
ALTER TABLE `lupo_interpretation_log` ADD INDEX IF NOT EXISTS `idx_confidence` (`confidence_score`);
ALTER TABLE `lupo_interpretation_log` ADD INDEX IF NOT EXISTS `idx_created` (`created_ymdhis`);
ALTER TABLE `lupo_interpretation_log` ADD INDEX IF NOT EXISTS `idx_deleted` (`is_deleted`);
ALTER TABLE `lupo_interpretation_log` ADD INDEX IF NOT EXISTS `idx_entity` (`entity_type`, `entity_id`);
ALTER TABLE `lupo_interpretation_log` ADD INDEX IF NOT EXISTS `idx_updated` (`updated_ymdhis`);

CREATE TABLE IF NOT EXISTS `lupo_labs_declarations` (
  `labs_declaration_id` bigint NOT NULL auto_increment COMMENT 'Primary key for LABS declaration record',
  `actor_id` bigint NOT NULL COMMENT 'Reference to actor (from lupo_actors)',
  `certificate_id` varchar(64) NOT NULL COMMENT 'Unique certificate ID (LABS-CERT-{UNIQUE_ID})',
  `declaration_timestamp` bigint NOT NULL COMMENT 'UTC bigint from actor declaration (YYYYMMDDHHMMSS)',
  `declarations_json` json NOT NULL COMMENT 'Complete LABS declaration set (all 10 declarations)',
  `validation_status` enum('valid','invalid','expired','quarantined') NOT NULL DEFAULT 'valid' COMMENT 'Current validation status',
  `labs_version` varchar(16) NOT NULL DEFAULT '1.0' COMMENT 'LABS doctrine version',
  `next_revalidation_ymdhis` bigint NOT NULL COMMENT 'UTC bigint when revalidation required (YYYYMMDDHHMMSS)',
  `validation_log_json` json COMMENT 'Validation log entries (violations, errors)',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC creation bigint (YYYYMMDDHHMMSS)',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC last update bigint (YYYYMMDDHHMMSS)',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted, 0=active)',
  `deleted_ymdhis` bigint COMMENT 'UTC deletion bigint (YYYYMMDDHHMMSS)',
  PRIMARY KEY (`labs_declaration_id`),
  KEY `idx_actor_id` (`actor_id`),
  KEY `idx_actor_status` (`actor_id`, `validation_status`, `is_deleted`),
  KEY `idx_certificate_id` (`certificate_id`),
  KEY `idx_next_revalidation` (`next_revalidation_ymdhis`),
  KEY `idx_revalidation_due` (`next_revalidation_ymdhis`, `validation_status`, `is_deleted`),
  KEY `idx_validation_status` (`validation_status`)
);

ALTER TABLE `lupo_labs_declarations` ADD COLUMN IF NOT EXISTS `labs_declaration_id` bigint NOT NULL auto_increment COMMENT 'Primary key for LABS declaration record';
ALTER TABLE `lupo_labs_declarations` ADD COLUMN IF NOT EXISTS `actor_id` bigint NOT NULL COMMENT 'Reference to actor (from lupo_actors)';
ALTER TABLE `lupo_labs_declarations` ADD COLUMN IF NOT EXISTS `certificate_id` varchar(64) NOT NULL COMMENT 'Unique certificate ID (LABS-CERT-{UNIQUE_ID})';
ALTER TABLE `lupo_labs_declarations` ADD COLUMN IF NOT EXISTS `declaration_timestamp` bigint NOT NULL COMMENT 'UTC bigint from actor declaration (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_labs_declarations` ADD COLUMN IF NOT EXISTS `declarations_json` json NOT NULL COMMENT 'Complete LABS declaration set (all 10 declarations)';
ALTER TABLE `lupo_labs_declarations` ADD COLUMN IF NOT EXISTS `validation_status` enum('valid','invalid','expired','quarantined') NOT NULL DEFAULT 'valid' COMMENT 'Current validation status';
ALTER TABLE `lupo_labs_declarations` ADD COLUMN IF NOT EXISTS `labs_version` varchar(16) NOT NULL DEFAULT '1.0' COMMENT 'LABS doctrine version';
ALTER TABLE `lupo_labs_declarations` ADD COLUMN IF NOT EXISTS `next_revalidation_ymdhis` bigint NOT NULL COMMENT 'UTC bigint when revalidation required (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_labs_declarations` ADD COLUMN IF NOT EXISTS `validation_log_json` json COMMENT 'Validation log entries (violations, errors)';
ALTER TABLE `lupo_labs_declarations` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'UTC creation bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_labs_declarations` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL COMMENT 'UTC last update bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_labs_declarations` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted, 0=active)';
ALTER TABLE `lupo_labs_declarations` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint COMMENT 'UTC deletion bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_labs_declarations` MODIFY COLUMN `labs_declaration_id` bigint NOT NULL auto_increment COMMENT 'Primary key for LABS declaration record';
ALTER TABLE `lupo_labs_declarations` MODIFY COLUMN `actor_id` bigint NOT NULL COMMENT 'Reference to actor (from lupo_actors)';
ALTER TABLE `lupo_labs_declarations` MODIFY COLUMN `certificate_id` varchar(64) NOT NULL COMMENT 'Unique certificate ID (LABS-CERT-{UNIQUE_ID})';
ALTER TABLE `lupo_labs_declarations` MODIFY COLUMN `declaration_timestamp` bigint NOT NULL COMMENT 'UTC bigint from actor declaration (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_labs_declarations` MODIFY COLUMN `declarations_json` json NOT NULL COMMENT 'Complete LABS declaration set (all 10 declarations)';
ALTER TABLE `lupo_labs_declarations` MODIFY COLUMN `validation_status` enum('valid','invalid','expired','quarantined') NOT NULL DEFAULT 'valid' COMMENT 'Current validation status';
ALTER TABLE `lupo_labs_declarations` MODIFY COLUMN `labs_version` varchar(16) NOT NULL DEFAULT '1.0' COMMENT 'LABS doctrine version';
ALTER TABLE `lupo_labs_declarations` MODIFY COLUMN `next_revalidation_ymdhis` bigint NOT NULL COMMENT 'UTC bigint when revalidation required (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_labs_declarations` MODIFY COLUMN `validation_log_json` json COMMENT 'Validation log entries (violations, errors)';
ALTER TABLE `lupo_labs_declarations` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'UTC creation bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_labs_declarations` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL COMMENT 'UTC last update bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_labs_declarations` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted, 0=active)';
ALTER TABLE `lupo_labs_declarations` MODIFY COLUMN `deleted_ymdhis` bigint COMMENT 'UTC deletion bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_labs_declarations` ADD PRIMARY KEY (`labs_declaration_id`);
ALTER TABLE `lupo_labs_declarations` ADD INDEX IF NOT EXISTS `idx_actor_id` (`actor_id`);
ALTER TABLE `lupo_labs_declarations` ADD INDEX IF NOT EXISTS `idx_actor_status` (`actor_id`, `validation_status`, `is_deleted`);
ALTER TABLE `lupo_labs_declarations` ADD INDEX IF NOT EXISTS `idx_certificate_id` (`certificate_id`);
ALTER TABLE `lupo_labs_declarations` ADD INDEX IF NOT EXISTS `idx_next_revalidation` (`next_revalidation_ymdhis`);
ALTER TABLE `lupo_labs_declarations` ADD INDEX IF NOT EXISTS `idx_revalidation_due` (`next_revalidation_ymdhis`, `validation_status`, `is_deleted`);
ALTER TABLE `lupo_labs_declarations` ADD INDEX IF NOT EXISTS `idx_validation_status` (`validation_status`);

CREATE TABLE IF NOT EXISTS `lupo_labs_violations` (
  `labs_violation_id` bigint NOT NULL auto_increment COMMENT 'Primary key for violation record',
  `actor_id` bigint NOT NULL COMMENT 'Reference to actor (from lupo_actors)',
  `certificate_id` varchar(64) NOT NULL,
  `violation_code` varchar(64) NOT NULL,
  `violation_description` text,
  `violation_metadata` json,
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC creation bigint (YYYYMMDDHHMMSS)',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC last update bigint (YYYYMMDDHHMMSS)',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted, 0=active)',
  PRIMARY KEY (`labs_violation_id`),
  KEY `idx_actor` (`actor_id`),
  KEY `idx_certificate` (`certificate_id`),
  KEY `idx_created` (`created_ymdhis`),
  KEY `idx_deleted` (`is_deleted`),
  KEY `idx_violation_code` (`violation_code`)
);

ALTER TABLE `lupo_labs_violations` ADD COLUMN IF NOT EXISTS `labs_violation_id` bigint NOT NULL auto_increment COMMENT 'Primary key for violation record';
ALTER TABLE `lupo_labs_violations` ADD COLUMN IF NOT EXISTS `actor_id` bigint NOT NULL COMMENT 'Reference to actor (from lupo_actors)';
ALTER TABLE `lupo_labs_violations` ADD COLUMN IF NOT EXISTS `certificate_id` varchar(64) NOT NULL;
ALTER TABLE `lupo_labs_violations` ADD COLUMN IF NOT EXISTS `violation_code` varchar(64) NOT NULL;
ALTER TABLE `lupo_labs_violations` ADD COLUMN IF NOT EXISTS `violation_description` text;
ALTER TABLE `lupo_labs_violations` ADD COLUMN IF NOT EXISTS `violation_metadata` json;
ALTER TABLE `lupo_labs_violations` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'UTC creation bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_labs_violations` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL COMMENT 'UTC last update bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_labs_violations` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted, 0=active)';
ALTER TABLE `lupo_labs_violations` MODIFY COLUMN `labs_violation_id` bigint NOT NULL auto_increment COMMENT 'Primary key for violation record';
ALTER TABLE `lupo_labs_violations` MODIFY COLUMN `actor_id` bigint NOT NULL COMMENT 'Reference to actor (from lupo_actors)';
ALTER TABLE `lupo_labs_violations` MODIFY COLUMN `certificate_id` varchar(64) NOT NULL;
ALTER TABLE `lupo_labs_violations` MODIFY COLUMN `violation_code` varchar(64) NOT NULL;
ALTER TABLE `lupo_labs_violations` MODIFY COLUMN `violation_description` text;
ALTER TABLE `lupo_labs_violations` MODIFY COLUMN `violation_metadata` json;
ALTER TABLE `lupo_labs_violations` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'UTC creation bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_labs_violations` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL COMMENT 'UTC last update bigint (YYYYMMDDHHMMSS)';
ALTER TABLE `lupo_labs_violations` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted, 0=active)';
ALTER TABLE `lupo_labs_violations` ADD PRIMARY KEY (`labs_violation_id`);
ALTER TABLE `lupo_labs_violations` ADD INDEX IF NOT EXISTS `idx_actor` (`actor_id`);
ALTER TABLE `lupo_labs_violations` ADD INDEX IF NOT EXISTS `idx_certificate` (`certificate_id`);
ALTER TABLE `lupo_labs_violations` ADD INDEX IF NOT EXISTS `idx_created` (`created_ymdhis`);
ALTER TABLE `lupo_labs_violations` ADD INDEX IF NOT EXISTS `idx_deleted` (`is_deleted`);
ALTER TABLE `lupo_labs_violations` ADD INDEX IF NOT EXISTS `idx_violation_code` (`violation_code`);

CREATE TABLE IF NOT EXISTS `lupo_legacy_content_mapping` (
  `mapping_id` bigint NOT NULL auto_increment COMMENT 'Primary key for content mapping',
  `legacy_url` varchar(255) NOT NULL COMMENT 'Original Crafty Syntax URL',
  `semantic_url` varchar(255) NOT NULL COMMENT 'New semantic URL',
  `content_type` enum('page','topic','collection') NOT NULL COMMENT 'Type of content',
  `content_id` bigint COMMENT 'Reference to semantic content',
  `created_ymdhis` bigint NOT NULL COMMENT 'Mapping creation bigint',
  `updated_ymdhis` bigint NOT NULL COMMENT 'Mapping update bigint',
  `is_active` tinyint NOT NULL DEFAULT 1 COMMENT 'Mapping active flag',
  PRIMARY KEY (`mapping_id`),
  KEY `idx_content_id` (`content_id`),
  KEY `idx_content_type` (`content_type`),
  KEY `idx_created` (`created_ymdhis`),
  KEY `idx_created_ymdhis` (`created_ymdhis`, `is_active`),
  KEY `idx_is_active` (`is_active`),
  KEY `idx_semantic_url` (`semantic_url`),
  UNIQUE KEY `uk_legacy_url` (`legacy_url`)
);

ALTER TABLE `lupo_legacy_content_mapping` ADD COLUMN IF NOT EXISTS `mapping_id` bigint NOT NULL auto_increment COMMENT 'Primary key for content mapping';
ALTER TABLE `lupo_legacy_content_mapping` ADD COLUMN IF NOT EXISTS `legacy_url` varchar(255) NOT NULL COMMENT 'Original Crafty Syntax URL';
ALTER TABLE `lupo_legacy_content_mapping` ADD COLUMN IF NOT EXISTS `semantic_url` varchar(255) NOT NULL COMMENT 'New semantic URL';
ALTER TABLE `lupo_legacy_content_mapping` ADD COLUMN IF NOT EXISTS `content_type` enum('page','topic','collection') NOT NULL COMMENT 'Type of content';
ALTER TABLE `lupo_legacy_content_mapping` ADD COLUMN IF NOT EXISTS `content_id` bigint COMMENT 'Reference to semantic content';
ALTER TABLE `lupo_legacy_content_mapping` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'Mapping creation bigint';
ALTER TABLE `lupo_legacy_content_mapping` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL COMMENT 'Mapping update bigint';
ALTER TABLE `lupo_legacy_content_mapping` ADD COLUMN IF NOT EXISTS `is_active` tinyint NOT NULL DEFAULT 1 COMMENT 'Mapping active flag';
ALTER TABLE `lupo_legacy_content_mapping` MODIFY COLUMN `mapping_id` bigint NOT NULL auto_increment COMMENT 'Primary key for content mapping';
ALTER TABLE `lupo_legacy_content_mapping` MODIFY COLUMN `legacy_url` varchar(255) NOT NULL COMMENT 'Original Crafty Syntax URL';
ALTER TABLE `lupo_legacy_content_mapping` MODIFY COLUMN `semantic_url` varchar(255) NOT NULL COMMENT 'New semantic URL';
ALTER TABLE `lupo_legacy_content_mapping` MODIFY COLUMN `content_type` enum('page','topic','collection') NOT NULL COMMENT 'Type of content';
ALTER TABLE `lupo_legacy_content_mapping` MODIFY COLUMN `content_id` bigint COMMENT 'Reference to semantic content';
ALTER TABLE `lupo_legacy_content_mapping` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'Mapping creation bigint';
ALTER TABLE `lupo_legacy_content_mapping` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL COMMENT 'Mapping update bigint';
ALTER TABLE `lupo_legacy_content_mapping` MODIFY COLUMN `is_active` tinyint NOT NULL DEFAULT 1 COMMENT 'Mapping active flag';
ALTER TABLE `lupo_legacy_content_mapping` ADD PRIMARY KEY (`mapping_id`);
ALTER TABLE `lupo_legacy_content_mapping` ADD INDEX IF NOT EXISTS `idx_content_id` (`content_id`);
ALTER TABLE `lupo_legacy_content_mapping` ADD INDEX IF NOT EXISTS `idx_content_type` (`content_type`);
ALTER TABLE `lupo_legacy_content_mapping` ADD INDEX IF NOT EXISTS `idx_created` (`created_ymdhis`);
ALTER TABLE `lupo_legacy_content_mapping` ADD INDEX IF NOT EXISTS `idx_created_ymdhis` (`created_ymdhis`, `is_active`);
ALTER TABLE `lupo_legacy_content_mapping` ADD INDEX IF NOT EXISTS `idx_is_active` (`is_active`);
ALTER TABLE `lupo_legacy_content_mapping` ADD INDEX IF NOT EXISTS `idx_semantic_url` (`semantic_url`);
ALTER TABLE `lupo_legacy_content_mapping` ADD UNIQUE INDEX IF NOT EXISTS `uk_legacy_url` (`legacy_url`);

CREATE TABLE IF NOT EXISTS `lupo_memory_debug_log` (
  `memory_debug_log_id` bigint NOT NULL auto_increment,
  `event_type` varchar(64) NOT NULL,
  `details` text NOT NULL,
  `created_ymdhis` bigint NOT NULL,
  PRIMARY KEY (`memory_debug_log_id`),
  KEY `idx_type_created` (`event_type`, `created_ymdhis`)
);

ALTER TABLE `lupo_memory_debug_log` ADD COLUMN IF NOT EXISTS `memory_debug_log_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_memory_debug_log` ADD COLUMN IF NOT EXISTS `event_type` varchar(64) NOT NULL;
ALTER TABLE `lupo_memory_debug_log` ADD COLUMN IF NOT EXISTS `details` text NOT NULL;
ALTER TABLE `lupo_memory_debug_log` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_memory_debug_log` MODIFY COLUMN `memory_debug_log_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_memory_debug_log` MODIFY COLUMN `event_type` varchar(64) NOT NULL;
ALTER TABLE `lupo_memory_debug_log` MODIFY COLUMN `details` text NOT NULL;
ALTER TABLE `lupo_memory_debug_log` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_memory_debug_log` ADD PRIMARY KEY (`memory_debug_log_id`);
ALTER TABLE `lupo_memory_debug_log` ADD INDEX IF NOT EXISTS `idx_type_created` (`event_type`, `created_ymdhis`);

CREATE TABLE IF NOT EXISTS `lupo_memory_events` (
  `memory_event_id` bigint NOT NULL auto_increment,
  `actor_id` int NOT NULL,
  `event_type` varchar(64) NOT NULL,
  `content` text NOT NULL,
  `metadata` json,
  `token_count` int,
  `importance` tinyint DEFAULT 0,
  `embedding_status` enum('none','pending','ready','failed') DEFAULT 'none',
  `created_ymdhis` bigint NOT NULL,
  PRIMARY KEY (`memory_event_id`),
  KEY `idx_actor_created` (`actor_id`, `created_ymdhis`),
  KEY `idx_actor_type` (`actor_id`, `event_type`)
);

ALTER TABLE `lupo_memory_events` ADD COLUMN IF NOT EXISTS `memory_event_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_memory_events` ADD COLUMN IF NOT EXISTS `actor_id` int NOT NULL;
ALTER TABLE `lupo_memory_events` ADD COLUMN IF NOT EXISTS `event_type` varchar(64) NOT NULL;
ALTER TABLE `lupo_memory_events` ADD COLUMN IF NOT EXISTS `content` text NOT NULL;
ALTER TABLE `lupo_memory_events` ADD COLUMN IF NOT EXISTS `metadata` json;
ALTER TABLE `lupo_memory_events` ADD COLUMN IF NOT EXISTS `token_count` int;
ALTER TABLE `lupo_memory_events` ADD COLUMN IF NOT EXISTS `importance` tinyint DEFAULT 0;
ALTER TABLE `lupo_memory_events` ADD COLUMN IF NOT EXISTS `embedding_status` enum('none','pending','ready','failed') DEFAULT 'none';
ALTER TABLE `lupo_memory_events` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_memory_events` MODIFY COLUMN `memory_event_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_memory_events` MODIFY COLUMN `actor_id` int NOT NULL;
ALTER TABLE `lupo_memory_events` MODIFY COLUMN `event_type` varchar(64) NOT NULL;
ALTER TABLE `lupo_memory_events` MODIFY COLUMN `content` text NOT NULL;
ALTER TABLE `lupo_memory_events` MODIFY COLUMN `metadata` json;
ALTER TABLE `lupo_memory_events` MODIFY COLUMN `token_count` int;
ALTER TABLE `lupo_memory_events` MODIFY COLUMN `importance` tinyint DEFAULT 0;
ALTER TABLE `lupo_memory_events` MODIFY COLUMN `embedding_status` enum('none','pending','ready','failed') DEFAULT 'none';
ALTER TABLE `lupo_memory_events` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_memory_events` ADD PRIMARY KEY (`memory_event_id`);
ALTER TABLE `lupo_memory_events` ADD INDEX IF NOT EXISTS `idx_actor_created` (`actor_id`, `created_ymdhis`);
ALTER TABLE `lupo_memory_events` ADD INDEX IF NOT EXISTS `idx_actor_type` (`actor_id`, `event_type`);

CREATE TABLE IF NOT EXISTS `lupo_memory_rollups` (
  `memory_rollup_id` bigint NOT NULL auto_increment,
  `actor_id` int NOT NULL,
  `summary` text NOT NULL,
  `source_event_ids` text NOT NULL,
  `created_ymdhis` bigint NOT NULL,
  PRIMARY KEY (`memory_rollup_id`),
  KEY `idx_actor_created` (`actor_id`, `created_ymdhis`)
);

ALTER TABLE `lupo_memory_rollups` ADD COLUMN IF NOT EXISTS `memory_rollup_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_memory_rollups` ADD COLUMN IF NOT EXISTS `actor_id` int NOT NULL;
ALTER TABLE `lupo_memory_rollups` ADD COLUMN IF NOT EXISTS `summary` text NOT NULL;
ALTER TABLE `lupo_memory_rollups` ADD COLUMN IF NOT EXISTS `source_event_ids` text NOT NULL;
ALTER TABLE `lupo_memory_rollups` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_memory_rollups` MODIFY COLUMN `memory_rollup_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_memory_rollups` MODIFY COLUMN `actor_id` int NOT NULL;
ALTER TABLE `lupo_memory_rollups` MODIFY COLUMN `summary` text NOT NULL;
ALTER TABLE `lupo_memory_rollups` MODIFY COLUMN `source_event_ids` text NOT NULL;
ALTER TABLE `lupo_memory_rollups` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_memory_rollups` ADD PRIMARY KEY (`memory_rollup_id`);
ALTER TABLE `lupo_memory_rollups` ADD INDEX IF NOT EXISTS `idx_actor_created` (`actor_id`, `created_ymdhis`);

CREATE TABLE IF NOT EXISTS `lupo_meta_log_events` (
  `event_id` bigint NOT NULL auto_increment,
  `depth` tinyint NOT NULL COMMENT '2=observation, 3=meta_observation',
  `event_type` varchar(64) NOT NULL DEFAULT 'recursion' COMMENT 'recursion|ceiling_near|auto_collapse',
  `actor_id` bigint,
  `created_ymdhis` bigint NOT NULL COMMENT 'YYYYMMDDHHMMSS',
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (`event_id`),
  KEY `idx_actor_id` (`actor_id`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_depth` (`depth`),
  KEY `idx_is_deleted` (`is_deleted`)
);

ALTER TABLE `lupo_meta_log_events` ADD COLUMN IF NOT EXISTS `event_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_meta_log_events` ADD COLUMN IF NOT EXISTS `depth` tinyint NOT NULL COMMENT '2=observation, 3=meta_observation';
ALTER TABLE `lupo_meta_log_events` ADD COLUMN IF NOT EXISTS `event_type` varchar(64) NOT NULL DEFAULT 'recursion' COMMENT 'recursion|ceiling_near|auto_collapse';
ALTER TABLE `lupo_meta_log_events` ADD COLUMN IF NOT EXISTS `actor_id` bigint;
ALTER TABLE `lupo_meta_log_events` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'YYYYMMDDHHMMSS';
ALTER TABLE `lupo_meta_log_events` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_meta_log_events` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint;
ALTER TABLE `lupo_meta_log_events` MODIFY COLUMN `event_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_meta_log_events` MODIFY COLUMN `depth` tinyint NOT NULL COMMENT '2=observation, 3=meta_observation';
ALTER TABLE `lupo_meta_log_events` MODIFY COLUMN `event_type` varchar(64) NOT NULL DEFAULT 'recursion' COMMENT 'recursion|ceiling_near|auto_collapse';
ALTER TABLE `lupo_meta_log_events` MODIFY COLUMN `actor_id` bigint;
ALTER TABLE `lupo_meta_log_events` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'YYYYMMDDHHMMSS';
ALTER TABLE `lupo_meta_log_events` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_meta_log_events` MODIFY COLUMN `deleted_ymdhis` bigint;
ALTER TABLE `lupo_meta_log_events` ADD PRIMARY KEY (`event_id`);
ALTER TABLE `lupo_meta_log_events` ADD INDEX IF NOT EXISTS `idx_actor_id` (`actor_id`);
ALTER TABLE `lupo_meta_log_events` ADD INDEX IF NOT EXISTS `idx_created_ymdhis` (`created_ymdhis`);
ALTER TABLE `lupo_meta_log_events` ADD INDEX IF NOT EXISTS `idx_depth` (`depth`);
ALTER TABLE `lupo_meta_log_events` ADD INDEX IF NOT EXISTS `idx_is_deleted` (`is_deleted`);

CREATE TABLE IF NOT EXISTS `lupo_migration_log` (
  `id` bigint NOT NULL auto_increment COMMENT 'Primary key',
  `executed_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when migration was attempted',
  `sql_snippet` text COMMENT 'First 2000 chars of migration SQL',
  `status` varchar(20) NOT NULL COMMENT 'success or blocked',
  `reason` text COMMENT 'If blocked, validation or execution reason',
  PRIMARY KEY (`id`),
  KEY `idx_executed_ymdhis` (`executed_ymdhis`),
  KEY `idx_status` (`status`)
);

ALTER TABLE `lupo_migration_log` ADD COLUMN IF NOT EXISTS `id` bigint NOT NULL auto_increment COMMENT 'Primary key';
ALTER TABLE `lupo_migration_log` ADD COLUMN IF NOT EXISTS `executed_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when migration was attempted';
ALTER TABLE `lupo_migration_log` ADD COLUMN IF NOT EXISTS `sql_snippet` text COMMENT 'First 2000 chars of migration SQL';
ALTER TABLE `lupo_migration_log` ADD COLUMN IF NOT EXISTS `status` varchar(20) NOT NULL COMMENT 'success or blocked';
ALTER TABLE `lupo_migration_log` ADD COLUMN IF NOT EXISTS `reason` text COMMENT 'If blocked, validation or execution reason';
ALTER TABLE `lupo_migration_log` MODIFY COLUMN `id` bigint NOT NULL auto_increment COMMENT 'Primary key';
ALTER TABLE `lupo_migration_log` MODIFY COLUMN `executed_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when migration was attempted';
ALTER TABLE `lupo_migration_log` MODIFY COLUMN `sql_snippet` text COMMENT 'First 2000 chars of migration SQL';
ALTER TABLE `lupo_migration_log` MODIFY COLUMN `status` varchar(20) NOT NULL COMMENT 'success or blocked';
ALTER TABLE `lupo_migration_log` MODIFY COLUMN `reason` text COMMENT 'If blocked, validation or execution reason';
ALTER TABLE `lupo_migration_log` ADD PRIMARY KEY (`id`);
ALTER TABLE `lupo_migration_log` ADD INDEX IF NOT EXISTS `idx_executed_ymdhis` (`executed_ymdhis`);
ALTER TABLE `lupo_migration_log` ADD INDEX IF NOT EXISTS `idx_status` (`status`);

CREATE TABLE IF NOT EXISTS `lupo_modules` (
  `module_id` bigint NOT NULL auto_increment,
  `module_key` varchar(100) NOT NULL,
  `module_name` varchar(150) NOT NULL,
  `namespace` varchar(100) NOT NULL,
  `version` varchar(50) NOT NULL,
  `version_code` int NOT NULL,
  `minimum_core_version` varchar(50) NOT NULL,
  `user_path` varchar(255),
  `admin_path` varchar(255),
  `api_path` varchar(255),
  `route_params` longtext,
  `description` text,
  `author` varchar(100),
  `website` varchar(255),
  `icon` varchar(100) DEFAULT 'puzzle-piece',
  `dependencies` longtext,
  `conflicts` longtext,
  `config_json` text NOT NULL,
  `is_system` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `settings` longtext,
  `installed_ymdhis` bigint,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (`module_id`),
  KEY `idx_installed` (`installed_ymdhis`),
  KEY `idx_namespace` (`namespace`),
  KEY `idx_status` (`is_active`, `is_deleted`),
  KEY `idx_system` (`is_system`),
  UNIQUE KEY `uq_module_key` (`module_key`)
);

ALTER TABLE `lupo_modules` ADD COLUMN IF NOT EXISTS `module_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_modules` ADD COLUMN IF NOT EXISTS `module_key` varchar(100) NOT NULL;
ALTER TABLE `lupo_modules` ADD COLUMN IF NOT EXISTS `module_name` varchar(150) NOT NULL;
ALTER TABLE `lupo_modules` ADD COLUMN IF NOT EXISTS `namespace` varchar(100) NOT NULL;
ALTER TABLE `lupo_modules` ADD COLUMN IF NOT EXISTS `version` varchar(50) NOT NULL;
ALTER TABLE `lupo_modules` ADD COLUMN IF NOT EXISTS `version_code` int NOT NULL;
ALTER TABLE `lupo_modules` ADD COLUMN IF NOT EXISTS `minimum_core_version` varchar(50) NOT NULL;
ALTER TABLE `lupo_modules` ADD COLUMN IF NOT EXISTS `user_path` varchar(255);
ALTER TABLE `lupo_modules` ADD COLUMN IF NOT EXISTS `admin_path` varchar(255);
ALTER TABLE `lupo_modules` ADD COLUMN IF NOT EXISTS `api_path` varchar(255);
ALTER TABLE `lupo_modules` ADD COLUMN IF NOT EXISTS `route_params` longtext;
ALTER TABLE `lupo_modules` ADD COLUMN IF NOT EXISTS `description` text;
ALTER TABLE `lupo_modules` ADD COLUMN IF NOT EXISTS `author` varchar(100);
ALTER TABLE `lupo_modules` ADD COLUMN IF NOT EXISTS `website` varchar(255);
ALTER TABLE `lupo_modules` ADD COLUMN IF NOT EXISTS `icon` varchar(100) DEFAULT 'puzzle-piece';
ALTER TABLE `lupo_modules` ADD COLUMN IF NOT EXISTS `dependencies` longtext;
ALTER TABLE `lupo_modules` ADD COLUMN IF NOT EXISTS `conflicts` longtext;
ALTER TABLE `lupo_modules` ADD COLUMN IF NOT EXISTS `config_json` text NOT NULL;
ALTER TABLE `lupo_modules` ADD COLUMN IF NOT EXISTS `is_system` tinyint(1) NOT NULL DEFAULT 0;
ALTER TABLE `lupo_modules` ADD COLUMN IF NOT EXISTS `is_active` tinyint(1) NOT NULL DEFAULT 0;
ALTER TABLE `lupo_modules` ADD COLUMN IF NOT EXISTS `settings` longtext;
ALTER TABLE `lupo_modules` ADD COLUMN IF NOT EXISTS `installed_ymdhis` bigint;
ALTER TABLE `lupo_modules` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_modules` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint;
ALTER TABLE `lupo_modules` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_modules` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint;
ALTER TABLE `lupo_modules` MODIFY COLUMN `module_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_modules` MODIFY COLUMN `module_key` varchar(100) NOT NULL;
ALTER TABLE `lupo_modules` MODIFY COLUMN `module_name` varchar(150) NOT NULL;
ALTER TABLE `lupo_modules` MODIFY COLUMN `namespace` varchar(100) NOT NULL;
ALTER TABLE `lupo_modules` MODIFY COLUMN `version` varchar(50) NOT NULL;
ALTER TABLE `lupo_modules` MODIFY COLUMN `version_code` int NOT NULL;
ALTER TABLE `lupo_modules` MODIFY COLUMN `minimum_core_version` varchar(50) NOT NULL;
ALTER TABLE `lupo_modules` MODIFY COLUMN `user_path` varchar(255);
ALTER TABLE `lupo_modules` MODIFY COLUMN `admin_path` varchar(255);
ALTER TABLE `lupo_modules` MODIFY COLUMN `api_path` varchar(255);
ALTER TABLE `lupo_modules` MODIFY COLUMN `route_params` longtext;
ALTER TABLE `lupo_modules` MODIFY COLUMN `description` text;
ALTER TABLE `lupo_modules` MODIFY COLUMN `author` varchar(100);
ALTER TABLE `lupo_modules` MODIFY COLUMN `website` varchar(255);
ALTER TABLE `lupo_modules` MODIFY COLUMN `icon` varchar(100) DEFAULT 'puzzle-piece';
ALTER TABLE `lupo_modules` MODIFY COLUMN `dependencies` longtext;
ALTER TABLE `lupo_modules` MODIFY COLUMN `conflicts` longtext;
ALTER TABLE `lupo_modules` MODIFY COLUMN `config_json` text NOT NULL;
ALTER TABLE `lupo_modules` MODIFY COLUMN `is_system` tinyint(1) NOT NULL DEFAULT 0;
ALTER TABLE `lupo_modules` MODIFY COLUMN `is_active` tinyint(1) NOT NULL DEFAULT 0;
ALTER TABLE `lupo_modules` MODIFY COLUMN `settings` longtext;
ALTER TABLE `lupo_modules` MODIFY COLUMN `installed_ymdhis` bigint;
ALTER TABLE `lupo_modules` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_modules` MODIFY COLUMN `updated_ymdhis` bigint;
ALTER TABLE `lupo_modules` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_modules` MODIFY COLUMN `deleted_ymdhis` bigint;
ALTER TABLE `lupo_modules` ADD PRIMARY KEY (`module_id`);
ALTER TABLE `lupo_modules` ADD INDEX IF NOT EXISTS `idx_installed` (`installed_ymdhis`);
ALTER TABLE `lupo_modules` ADD INDEX IF NOT EXISTS `idx_namespace` (`namespace`);
ALTER TABLE `lupo_modules` ADD INDEX IF NOT EXISTS `idx_status` (`is_active`, `is_deleted`);
ALTER TABLE `lupo_modules` ADD INDEX IF NOT EXISTS `idx_system` (`is_system`);
ALTER TABLE `lupo_modules` ADD UNIQUE INDEX IF NOT EXISTS `uq_module_key` (`module_key`);

CREATE TABLE IF NOT EXISTS `lupo_multi_agent_critique_sync` (
  `id` bigint unsigned NOT NULL auto_increment,
  `cip_event_id` bigint unsigned NOT NULL COMMENT 'Root CIP event',
  `agent_id` varchar(100) NOT NULL COMMENT 'Agent participating in sync',
  `sync_role` enum('initiator','participant','observer','validator') NOT NULL,
  `sync_status` enum('pending','synchronized','out_of_sync','conflict','resolved') DEFAULT 'pending',
  `agent_perspective_json` json COMMENT 'Agent-specific view of critique',
  `consensus_contribution` decimal(5,4) DEFAULT 0.0000 COMMENT 'Contribution to consensus (0-1)',
  `conflict_indicators_json` json COMMENT 'Detected conflicts with other agents',
  `resolution_strategy` varchar(255) COMMENT 'Strategy for resolving conflicts',
  `sync_started_ymdhis` bigint COMMENT 'When sync process started',
  `sync_completed_ymdhis` bigint COMMENT 'When sync was completed',
  `sync_version` varchar(20) DEFAULT '4.0.75' COMMENT 'Sync protocol version',
  PRIMARY KEY (`id`),
  KEY `idx_consensus_contribution` (`consensus_contribution`),
  KEY `idx_event_agent` (`cip_event_id`, `agent_id`),
  KEY `idx_sync_role` (`sync_role`),
  KEY `idx_sync_status` (`sync_status`)
);

ALTER TABLE `lupo_multi_agent_critique_sync` ADD COLUMN IF NOT EXISTS `id` bigint unsigned NOT NULL auto_increment;
ALTER TABLE `lupo_multi_agent_critique_sync` ADD COLUMN IF NOT EXISTS `cip_event_id` bigint unsigned NOT NULL COMMENT 'Root CIP event';
ALTER TABLE `lupo_multi_agent_critique_sync` ADD COLUMN IF NOT EXISTS `agent_id` varchar(100) NOT NULL COMMENT 'Agent participating in sync';
ALTER TABLE `lupo_multi_agent_critique_sync` ADD COLUMN IF NOT EXISTS `sync_role` enum('initiator','participant','observer','validator') NOT NULL;
ALTER TABLE `lupo_multi_agent_critique_sync` ADD COLUMN IF NOT EXISTS `sync_status` enum('pending','synchronized','out_of_sync','conflict','resolved') DEFAULT 'pending';
ALTER TABLE `lupo_multi_agent_critique_sync` ADD COLUMN IF NOT EXISTS `agent_perspective_json` json COMMENT 'Agent-specific view of critique';
ALTER TABLE `lupo_multi_agent_critique_sync` ADD COLUMN IF NOT EXISTS `consensus_contribution` decimal(5,4) DEFAULT 0.0000 COMMENT 'Contribution to consensus (0-1)';
ALTER TABLE `lupo_multi_agent_critique_sync` ADD COLUMN IF NOT EXISTS `conflict_indicators_json` json COMMENT 'Detected conflicts with other agents';
ALTER TABLE `lupo_multi_agent_critique_sync` ADD COLUMN IF NOT EXISTS `resolution_strategy` varchar(255) COMMENT 'Strategy for resolving conflicts';
ALTER TABLE `lupo_multi_agent_critique_sync` ADD COLUMN IF NOT EXISTS `sync_started_ymdhis` bigint COMMENT 'When sync process started';
ALTER TABLE `lupo_multi_agent_critique_sync` ADD COLUMN IF NOT EXISTS `sync_completed_ymdhis` bigint COMMENT 'When sync was completed';
ALTER TABLE `lupo_multi_agent_critique_sync` ADD COLUMN IF NOT EXISTS `sync_version` varchar(20) DEFAULT '4.0.75' COMMENT 'Sync protocol version';
ALTER TABLE `lupo_multi_agent_critique_sync` MODIFY COLUMN `id` bigint unsigned NOT NULL auto_increment;
ALTER TABLE `lupo_multi_agent_critique_sync` MODIFY COLUMN `cip_event_id` bigint unsigned NOT NULL COMMENT 'Root CIP event';
ALTER TABLE `lupo_multi_agent_critique_sync` MODIFY COLUMN `agent_id` varchar(100) NOT NULL COMMENT 'Agent participating in sync';
ALTER TABLE `lupo_multi_agent_critique_sync` MODIFY COLUMN `sync_role` enum('initiator','participant','observer','validator') NOT NULL;
ALTER TABLE `lupo_multi_agent_critique_sync` MODIFY COLUMN `sync_status` enum('pending','synchronized','out_of_sync','conflict','resolved') DEFAULT 'pending';
ALTER TABLE `lupo_multi_agent_critique_sync` MODIFY COLUMN `agent_perspective_json` json COMMENT 'Agent-specific view of critique';
ALTER TABLE `lupo_multi_agent_critique_sync` MODIFY COLUMN `consensus_contribution` decimal(5,4) DEFAULT 0.0000 COMMENT 'Contribution to consensus (0-1)';
ALTER TABLE `lupo_multi_agent_critique_sync` MODIFY COLUMN `conflict_indicators_json` json COMMENT 'Detected conflicts with other agents';
ALTER TABLE `lupo_multi_agent_critique_sync` MODIFY COLUMN `resolution_strategy` varchar(255) COMMENT 'Strategy for resolving conflicts';
ALTER TABLE `lupo_multi_agent_critique_sync` MODIFY COLUMN `sync_started_ymdhis` bigint COMMENT 'When sync process started';
ALTER TABLE `lupo_multi_agent_critique_sync` MODIFY COLUMN `sync_completed_ymdhis` bigint COMMENT 'When sync was completed';
ALTER TABLE `lupo_multi_agent_critique_sync` MODIFY COLUMN `sync_version` varchar(20) DEFAULT '4.0.75' COMMENT 'Sync protocol version';
ALTER TABLE `lupo_multi_agent_critique_sync` ADD PRIMARY KEY (`id`);
ALTER TABLE `lupo_multi_agent_critique_sync` ADD INDEX IF NOT EXISTS `idx_consensus_contribution` (`consensus_contribution`);
ALTER TABLE `lupo_multi_agent_critique_sync` ADD INDEX IF NOT EXISTS `idx_event_agent` (`cip_event_id`, `agent_id`);
ALTER TABLE `lupo_multi_agent_critique_sync` ADD INDEX IF NOT EXISTS `idx_sync_role` (`sync_role`);
ALTER TABLE `lupo_multi_agent_critique_sync` ADD INDEX IF NOT EXISTS `idx_sync_status` (`sync_status`);

CREATE TABLE IF NOT EXISTS `lupo_narrative_fragments` (
  `narrative_fragment_id` bigint NOT NULL auto_increment,
  `agent_id` bigint COMMENT 'Agent that generated this fragment',
  `fragment_type` varchar(100) COMMENT 'memory, mythic, emotional, symbolic, annotation, etc.',
  `title` varchar(255) COMMENT 'Optional short label',
  `fragment_text` longtext NOT NULL COMMENT 'The narrative or memory text',
  `metadata_json` json COMMENT 'Optional structured metadata',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
  `updated_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when updated',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag',
  `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted',
  PRIMARY KEY (`narrative_fragment_id`),
  KEY `idx_agent` (`agent_id`),
  KEY `idx_created` (`created_ymdhis`),
  KEY `idx_type` (`fragment_type`)
);

ALTER TABLE `lupo_narrative_fragments` ADD COLUMN IF NOT EXISTS `narrative_fragment_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_narrative_fragments` ADD COLUMN IF NOT EXISTS `agent_id` bigint COMMENT 'Agent that generated this fragment';
ALTER TABLE `lupo_narrative_fragments` ADD COLUMN IF NOT EXISTS `fragment_type` varchar(100) COMMENT 'memory, mythic, emotional, symbolic, annotation, etc.';
ALTER TABLE `lupo_narrative_fragments` ADD COLUMN IF NOT EXISTS `title` varchar(255) COMMENT 'Optional short label';
ALTER TABLE `lupo_narrative_fragments` ADD COLUMN IF NOT EXISTS `fragment_text` longtext NOT NULL COMMENT 'The narrative or memory text';
ALTER TABLE `lupo_narrative_fragments` ADD COLUMN IF NOT EXISTS `metadata_json` json COMMENT 'Optional structured metadata';
ALTER TABLE `lupo_narrative_fragments` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created';
ALTER TABLE `lupo_narrative_fragments` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when updated';
ALTER TABLE `lupo_narrative_fragments` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag';
ALTER TABLE `lupo_narrative_fragments` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted';
ALTER TABLE `lupo_narrative_fragments` MODIFY COLUMN `narrative_fragment_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_narrative_fragments` MODIFY COLUMN `agent_id` bigint COMMENT 'Agent that generated this fragment';
ALTER TABLE `lupo_narrative_fragments` MODIFY COLUMN `fragment_type` varchar(100) COMMENT 'memory, mythic, emotional, symbolic, annotation, etc.';
ALTER TABLE `lupo_narrative_fragments` MODIFY COLUMN `title` varchar(255) COMMENT 'Optional short label';
ALTER TABLE `lupo_narrative_fragments` MODIFY COLUMN `fragment_text` longtext NOT NULL COMMENT 'The narrative or memory text';
ALTER TABLE `lupo_narrative_fragments` MODIFY COLUMN `metadata_json` json COMMENT 'Optional structured metadata';
ALTER TABLE `lupo_narrative_fragments` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created';
ALTER TABLE `lupo_narrative_fragments` MODIFY COLUMN `updated_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when updated';
ALTER TABLE `lupo_narrative_fragments` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag';
ALTER TABLE `lupo_narrative_fragments` MODIFY COLUMN `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted';
ALTER TABLE `lupo_narrative_fragments` ADD PRIMARY KEY (`narrative_fragment_id`);
ALTER TABLE `lupo_narrative_fragments` ADD INDEX IF NOT EXISTS `idx_agent` (`agent_id`);
ALTER TABLE `lupo_narrative_fragments` ADD INDEX IF NOT EXISTS `idx_created` (`created_ymdhis`);
ALTER TABLE `lupo_narrative_fragments` ADD INDEX IF NOT EXISTS `idx_type` (`fragment_type`);

CREATE TABLE IF NOT EXISTS `lupo_notifications` (
  `notification_id` bigint NOT NULL auto_increment,
  `actor_id` bigint NOT NULL,
  `from_actor_id` bigint,
  `to_actor_id` bigint,
  `channel_id` bigint,
  `notification_type` varchar(64) NOT NULL,
  `title` varchar(255),
  `message` text,
  `link_url` varchar(255),
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL,
  PRIMARY KEY (`notification_id`)
);

ALTER TABLE `lupo_notifications` ADD COLUMN IF NOT EXISTS `notification_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_notifications` ADD COLUMN IF NOT EXISTS `actor_id` bigint NOT NULL;
ALTER TABLE `lupo_notifications` ADD COLUMN IF NOT EXISTS `from_actor_id` bigint;
ALTER TABLE `lupo_notifications` ADD COLUMN IF NOT EXISTS `to_actor_id` bigint;
ALTER TABLE `lupo_notifications` ADD COLUMN IF NOT EXISTS `channel_id` bigint;
ALTER TABLE `lupo_notifications` ADD COLUMN IF NOT EXISTS `notification_type` varchar(64) NOT NULL;
ALTER TABLE `lupo_notifications` ADD COLUMN IF NOT EXISTS `title` varchar(255);
ALTER TABLE `lupo_notifications` ADD COLUMN IF NOT EXISTS `message` text;
ALTER TABLE `lupo_notifications` ADD COLUMN IF NOT EXISTS `link_url` varchar(255);
ALTER TABLE `lupo_notifications` ADD COLUMN IF NOT EXISTS `is_read` tinyint(1) NOT NULL DEFAULT 0;
ALTER TABLE `lupo_notifications` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint(1) NOT NULL DEFAULT 0;
ALTER TABLE `lupo_notifications` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_notifications` MODIFY COLUMN `notification_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_notifications` MODIFY COLUMN `actor_id` bigint NOT NULL;
ALTER TABLE `lupo_notifications` MODIFY COLUMN `from_actor_id` bigint;
ALTER TABLE `lupo_notifications` MODIFY COLUMN `to_actor_id` bigint;
ALTER TABLE `lupo_notifications` MODIFY COLUMN `channel_id` bigint;
ALTER TABLE `lupo_notifications` MODIFY COLUMN `notification_type` varchar(64) NOT NULL;
ALTER TABLE `lupo_notifications` MODIFY COLUMN `title` varchar(255);
ALTER TABLE `lupo_notifications` MODIFY COLUMN `message` text;
ALTER TABLE `lupo_notifications` MODIFY COLUMN `link_url` varchar(255);
ALTER TABLE `lupo_notifications` MODIFY COLUMN `is_read` tinyint(1) NOT NULL DEFAULT 0;
ALTER TABLE `lupo_notifications` MODIFY COLUMN `is_deleted` tinyint(1) NOT NULL DEFAULT 0;
ALTER TABLE `lupo_notifications` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_notifications` ADD PRIMARY KEY (`notification_id`);

CREATE TABLE IF NOT EXISTS `lupo_pack_role_registry` (
  `id` bigint unsigned NOT NULL auto_increment,
  `agent_id` bigint unsigned NOT NULL COMMENT 'Reference to lupo_agent_registry.agent_registry_id',
  `role` varchar(255) NOT NULL COMMENT 'Discovered role name',
  `discovery_method` text NOT NULL COMMENT 'How this role was discovered',
  `behavior` text NOT NULL COMMENT 'Observed behavior that defines the role',
  `reason` text NOT NULL COMMENT 'Why this agent has this role',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when role was discovered',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when role was last updated',
  PRIMARY KEY (`id`),
  KEY `idx_agent_id` (`agent_id`),
  KEY `idx_role` (`role`),
  UNIQUE KEY `unique_agent_role` (`agent_id`)
);

ALTER TABLE `lupo_pack_role_registry` ADD COLUMN IF NOT EXISTS `id` bigint unsigned NOT NULL auto_increment;
ALTER TABLE `lupo_pack_role_registry` ADD COLUMN IF NOT EXISTS `agent_id` bigint unsigned NOT NULL COMMENT 'Reference to lupo_agent_registry.agent_registry_id';
ALTER TABLE `lupo_pack_role_registry` ADD COLUMN IF NOT EXISTS `role` varchar(255) NOT NULL COMMENT 'Discovered role name';
ALTER TABLE `lupo_pack_role_registry` ADD COLUMN IF NOT EXISTS `discovery_method` text NOT NULL COMMENT 'How this role was discovered';
ALTER TABLE `lupo_pack_role_registry` ADD COLUMN IF NOT EXISTS `behavior` text NOT NULL COMMENT 'Observed behavior that defines the role';
ALTER TABLE `lupo_pack_role_registry` ADD COLUMN IF NOT EXISTS `reason` text NOT NULL COMMENT 'Why this agent has this role';
ALTER TABLE `lupo_pack_role_registry` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when role was discovered';
ALTER TABLE `lupo_pack_role_registry` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when role was last updated';
ALTER TABLE `lupo_pack_role_registry` MODIFY COLUMN `id` bigint unsigned NOT NULL auto_increment;
ALTER TABLE `lupo_pack_role_registry` MODIFY COLUMN `agent_id` bigint unsigned NOT NULL COMMENT 'Reference to lupo_agent_registry.agent_registry_id';
ALTER TABLE `lupo_pack_role_registry` MODIFY COLUMN `role` varchar(255) NOT NULL COMMENT 'Discovered role name';
ALTER TABLE `lupo_pack_role_registry` MODIFY COLUMN `discovery_method` text NOT NULL COMMENT 'How this role was discovered';
ALTER TABLE `lupo_pack_role_registry` MODIFY COLUMN `behavior` text NOT NULL COMMENT 'Observed behavior that defines the role';
ALTER TABLE `lupo_pack_role_registry` MODIFY COLUMN `reason` text NOT NULL COMMENT 'Why this agent has this role';
ALTER TABLE `lupo_pack_role_registry` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when role was discovered';
ALTER TABLE `lupo_pack_role_registry` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when role was last updated';
ALTER TABLE `lupo_pack_role_registry` ADD PRIMARY KEY (`id`);
ALTER TABLE `lupo_pack_role_registry` ADD INDEX IF NOT EXISTS `idx_agent_id` (`agent_id`);
ALTER TABLE `lupo_pack_role_registry` ADD INDEX IF NOT EXISTS `idx_role` (`role`);
ALTER TABLE `lupo_pack_role_registry` ADD UNIQUE INDEX IF NOT EXISTS `unique_agent_role` (`agent_id`);

CREATE TABLE IF NOT EXISTS `lupo_permissions` (
  `permission_id` bigint NOT NULL auto_increment,
  `target_type` varchar(64) NOT NULL COMMENT 'Type of object: collection, department, module, feature, etc.',
  `target_id` bigint NOT NULL COMMENT 'ID of the target object',
  `user_id` bigint COMMENT 'User ID for user-based permissions (NULL for group-based)',
  `group_id` bigint COMMENT 'Group ID for group-based permissions (NULL for user-based)',
  `permission` enum('read','write','owner') NOT NULL DEFAULT 'read' COMMENT 'Permission level',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when permission was created',
  `updated_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when permission was updated',
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = active',
  `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when permission was deleted',
  PRIMARY KEY (`permission_id`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_deleted` (`is_deleted`, `deleted_ymdhis`),
  KEY `idx_group` (`group_id`),
  KEY `idx_permission` (`permission`),
  KEY `idx_target` (`target_type`, `target_id`),
  KEY `idx_user` (`user_id`),
  UNIQUE KEY `uniq_target_group` (`target_type`, `target_id`, `group_id`),
  UNIQUE KEY `uniq_target_user` (`target_type`, `target_id`, `user_id`)
);

ALTER TABLE `lupo_permissions` ADD COLUMN IF NOT EXISTS `permission_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_permissions` ADD COLUMN IF NOT EXISTS `target_type` varchar(64) NOT NULL COMMENT 'Type of object: collection, department, module, feature, etc.';
ALTER TABLE `lupo_permissions` ADD COLUMN IF NOT EXISTS `target_id` bigint NOT NULL COMMENT 'ID of the target object';
ALTER TABLE `lupo_permissions` ADD COLUMN IF NOT EXISTS `user_id` bigint COMMENT 'User ID for user-based permissions (NULL for group-based)';
ALTER TABLE `lupo_permissions` ADD COLUMN IF NOT EXISTS `group_id` bigint COMMENT 'Group ID for group-based permissions (NULL for user-based)';
ALTER TABLE `lupo_permissions` ADD COLUMN IF NOT EXISTS `permission` enum('read','write','owner') NOT NULL DEFAULT 'read' COMMENT 'Permission level';
ALTER TABLE `lupo_permissions` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when permission was created';
ALTER TABLE `lupo_permissions` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when permission was updated';
ALTER TABLE `lupo_permissions` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = active';
ALTER TABLE `lupo_permissions` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when permission was deleted';
ALTER TABLE `lupo_permissions` MODIFY COLUMN `permission_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_permissions` MODIFY COLUMN `target_type` varchar(64) NOT NULL COMMENT 'Type of object: collection, department, module, feature, etc.';
ALTER TABLE `lupo_permissions` MODIFY COLUMN `target_id` bigint NOT NULL COMMENT 'ID of the target object';
ALTER TABLE `lupo_permissions` MODIFY COLUMN `user_id` bigint COMMENT 'User ID for user-based permissions (NULL for group-based)';
ALTER TABLE `lupo_permissions` MODIFY COLUMN `group_id` bigint COMMENT 'Group ID for group-based permissions (NULL for user-based)';
ALTER TABLE `lupo_permissions` MODIFY COLUMN `permission` enum('read','write','owner') NOT NULL DEFAULT 'read' COMMENT 'Permission level';
ALTER TABLE `lupo_permissions` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when permission was created';
ALTER TABLE `lupo_permissions` MODIFY COLUMN `updated_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when permission was updated';
ALTER TABLE `lupo_permissions` MODIFY COLUMN `is_deleted` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = active';
ALTER TABLE `lupo_permissions` MODIFY COLUMN `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when permission was deleted';
ALTER TABLE `lupo_permissions` ADD PRIMARY KEY (`permission_id`);
ALTER TABLE `lupo_permissions` ADD INDEX IF NOT EXISTS `idx_created_ymdhis` (`created_ymdhis`);
ALTER TABLE `lupo_permissions` ADD INDEX IF NOT EXISTS `idx_deleted` (`is_deleted`, `deleted_ymdhis`);
ALTER TABLE `lupo_permissions` ADD INDEX IF NOT EXISTS `idx_group` (`group_id`);
ALTER TABLE `lupo_permissions` ADD INDEX IF NOT EXISTS `idx_permission` (`permission`);
ALTER TABLE `lupo_permissions` ADD INDEX IF NOT EXISTS `idx_target` (`target_type`, `target_id`);
ALTER TABLE `lupo_permissions` ADD INDEX IF NOT EXISTS `idx_user` (`user_id`);
ALTER TABLE `lupo_permissions` ADD UNIQUE INDEX IF NOT EXISTS `uniq_target_group` (`target_type`, `target_id`, `group_id`);
ALTER TABLE `lupo_permissions` ADD UNIQUE INDEX IF NOT EXISTS `uniq_target_user` (`target_type`, `target_id`, `user_id`);

CREATE TABLE IF NOT EXISTS `lupo_persona_dialogue_patterns` (
  `pattern_id` bigint NOT NULL auto_increment,
  `persona_id` bigint NOT NULL,
  `pattern_type` varchar(100) NOT NULL,
  `pattern_name` varchar(255) NOT NULL,
  `pattern_triggers` json,
  `pattern_responses` json,
  `pattern_context` json,
  `pattern_frequency` decimal(5,2),
  `pattern_confidence` decimal(5,2),
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  PRIMARY KEY (`pattern_id`),
  KEY `idx_pattern_name` (`pattern_name`),
  KEY `idx_pattern_type` (`pattern_type`),
  KEY `idx_persona_id` (`persona_id`)
);

ALTER TABLE `lupo_persona_dialogue_patterns` ADD COLUMN IF NOT EXISTS `pattern_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_persona_dialogue_patterns` ADD COLUMN IF NOT EXISTS `persona_id` bigint NOT NULL;
ALTER TABLE `lupo_persona_dialogue_patterns` ADD COLUMN IF NOT EXISTS `pattern_type` varchar(100) NOT NULL;
ALTER TABLE `lupo_persona_dialogue_patterns` ADD COLUMN IF NOT EXISTS `pattern_name` varchar(255) NOT NULL;
ALTER TABLE `lupo_persona_dialogue_patterns` ADD COLUMN IF NOT EXISTS `pattern_triggers` json;
ALTER TABLE `lupo_persona_dialogue_patterns` ADD COLUMN IF NOT EXISTS `pattern_responses` json;
ALTER TABLE `lupo_persona_dialogue_patterns` ADD COLUMN IF NOT EXISTS `pattern_context` json;
ALTER TABLE `lupo_persona_dialogue_patterns` ADD COLUMN IF NOT EXISTS `pattern_frequency` decimal(5,2);
ALTER TABLE `lupo_persona_dialogue_patterns` ADD COLUMN IF NOT EXISTS `pattern_confidence` decimal(5,2);
ALTER TABLE `lupo_persona_dialogue_patterns` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_persona_dialogue_patterns` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_persona_dialogue_patterns` MODIFY COLUMN `pattern_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_persona_dialogue_patterns` MODIFY COLUMN `persona_id` bigint NOT NULL;
ALTER TABLE `lupo_persona_dialogue_patterns` MODIFY COLUMN `pattern_type` varchar(100) NOT NULL;
ALTER TABLE `lupo_persona_dialogue_patterns` MODIFY COLUMN `pattern_name` varchar(255) NOT NULL;
ALTER TABLE `lupo_persona_dialogue_patterns` MODIFY COLUMN `pattern_triggers` json;
ALTER TABLE `lupo_persona_dialogue_patterns` MODIFY COLUMN `pattern_responses` json;
ALTER TABLE `lupo_persona_dialogue_patterns` MODIFY COLUMN `pattern_context` json;
ALTER TABLE `lupo_persona_dialogue_patterns` MODIFY COLUMN `pattern_frequency` decimal(5,2);
ALTER TABLE `lupo_persona_dialogue_patterns` MODIFY COLUMN `pattern_confidence` decimal(5,2);
ALTER TABLE `lupo_persona_dialogue_patterns` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_persona_dialogue_patterns` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_persona_dialogue_patterns` ADD PRIMARY KEY (`pattern_id`);
ALTER TABLE `lupo_persona_dialogue_patterns` ADD INDEX IF NOT EXISTS `idx_pattern_name` (`pattern_name`);
ALTER TABLE `lupo_persona_dialogue_patterns` ADD INDEX IF NOT EXISTS `idx_pattern_type` (`pattern_type`);
ALTER TABLE `lupo_persona_dialogue_patterns` ADD INDEX IF NOT EXISTS `idx_persona_id` (`persona_id`);

CREATE TABLE IF NOT EXISTS `lupo_persona_profiles` (
  `persona_id` bigint NOT NULL auto_increment,
  `persona_name` varchar(255) NOT NULL,
  `persona_type` varchar(100) NOT NULL,
  `persona_description` text,
  `persona_traits` json,
  `persona_preferences` json,
  `persona_capabilities` json,
  `persona_voice_style` varchar(100),
  `persona_interaction_style` varchar(100),
  `persona_emotional_profile` json,
  `persona_knowledge_domains` json,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  `is_active` tinyint NOT NULL DEFAULT 1,
  PRIMARY KEY (`persona_id`),
  KEY `idx_is_active` (`is_active`),
  KEY `idx_persona_name` (`persona_name`),
  KEY `idx_persona_type` (`persona_type`)
);

ALTER TABLE `lupo_persona_profiles` ADD COLUMN IF NOT EXISTS `persona_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_persona_profiles` ADD COLUMN IF NOT EXISTS `persona_name` varchar(255) NOT NULL;
ALTER TABLE `lupo_persona_profiles` ADD COLUMN IF NOT EXISTS `persona_type` varchar(100) NOT NULL;
ALTER TABLE `lupo_persona_profiles` ADD COLUMN IF NOT EXISTS `persona_description` text;
ALTER TABLE `lupo_persona_profiles` ADD COLUMN IF NOT EXISTS `persona_traits` json;
ALTER TABLE `lupo_persona_profiles` ADD COLUMN IF NOT EXISTS `persona_preferences` json;
ALTER TABLE `lupo_persona_profiles` ADD COLUMN IF NOT EXISTS `persona_capabilities` json;
ALTER TABLE `lupo_persona_profiles` ADD COLUMN IF NOT EXISTS `persona_voice_style` varchar(100);
ALTER TABLE `lupo_persona_profiles` ADD COLUMN IF NOT EXISTS `persona_interaction_style` varchar(100);
ALTER TABLE `lupo_persona_profiles` ADD COLUMN IF NOT EXISTS `persona_emotional_profile` json;
ALTER TABLE `lupo_persona_profiles` ADD COLUMN IF NOT EXISTS `persona_knowledge_domains` json;
ALTER TABLE `lupo_persona_profiles` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_persona_profiles` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_persona_profiles` ADD COLUMN IF NOT EXISTS `is_active` tinyint NOT NULL DEFAULT 1;
ALTER TABLE `lupo_persona_profiles` MODIFY COLUMN `persona_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_persona_profiles` MODIFY COLUMN `persona_name` varchar(255) NOT NULL;
ALTER TABLE `lupo_persona_profiles` MODIFY COLUMN `persona_type` varchar(100) NOT NULL;
ALTER TABLE `lupo_persona_profiles` MODIFY COLUMN `persona_description` text;
ALTER TABLE `lupo_persona_profiles` MODIFY COLUMN `persona_traits` json;
ALTER TABLE `lupo_persona_profiles` MODIFY COLUMN `persona_preferences` json;
ALTER TABLE `lupo_persona_profiles` MODIFY COLUMN `persona_capabilities` json;
ALTER TABLE `lupo_persona_profiles` MODIFY COLUMN `persona_voice_style` varchar(100);
ALTER TABLE `lupo_persona_profiles` MODIFY COLUMN `persona_interaction_style` varchar(100);
ALTER TABLE `lupo_persona_profiles` MODIFY COLUMN `persona_emotional_profile` json;
ALTER TABLE `lupo_persona_profiles` MODIFY COLUMN `persona_knowledge_domains` json;
ALTER TABLE `lupo_persona_profiles` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_persona_profiles` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_persona_profiles` MODIFY COLUMN `is_active` tinyint NOT NULL DEFAULT 1;
ALTER TABLE `lupo_persona_profiles` ADD PRIMARY KEY (`persona_id`);
ALTER TABLE `lupo_persona_profiles` ADD INDEX IF NOT EXISTS `idx_is_active` (`is_active`);
ALTER TABLE `lupo_persona_profiles` ADD INDEX IF NOT EXISTS `idx_persona_name` (`persona_name`);
ALTER TABLE `lupo_persona_profiles` ADD INDEX IF NOT EXISTS `idx_persona_type` (`persona_type`);

CREATE TABLE IF NOT EXISTS `lupo_reference_cited_by` (
  `reference_cited_by_id` bigint NOT NULL auto_increment,
  `reference_object_id` bigint NOT NULL,
  `content_id` bigint NOT NULL,
  `section_anchor_slug` varchar(255),
  `section_order` int NOT NULL DEFAULT 0,
  `reference_type` varchar(50) NOT NULL,
  `raw_reference` text,
  `meta_json` json,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (`reference_cited_by_id`),
  KEY `idx_content_id` (`content_id`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_reference_object` (`reference_object_id`),
  KEY `idx_reference_type` (`reference_type`),
  KEY `idx_section_anchor` (`section_anchor_slug`)
);

ALTER TABLE `lupo_reference_cited_by` ADD COLUMN IF NOT EXISTS `reference_cited_by_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_reference_cited_by` ADD COLUMN IF NOT EXISTS `reference_object_id` bigint NOT NULL;
ALTER TABLE `lupo_reference_cited_by` ADD COLUMN IF NOT EXISTS `content_id` bigint NOT NULL;
ALTER TABLE `lupo_reference_cited_by` ADD COLUMN IF NOT EXISTS `section_anchor_slug` varchar(255);
ALTER TABLE `lupo_reference_cited_by` ADD COLUMN IF NOT EXISTS `section_order` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_reference_cited_by` ADD COLUMN IF NOT EXISTS `reference_type` varchar(50) NOT NULL;
ALTER TABLE `lupo_reference_cited_by` ADD COLUMN IF NOT EXISTS `raw_reference` text;
ALTER TABLE `lupo_reference_cited_by` ADD COLUMN IF NOT EXISTS `meta_json` json;
ALTER TABLE `lupo_reference_cited_by` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_reference_cited_by` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_reference_cited_by` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_reference_cited_by` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_reference_cited_by` MODIFY COLUMN `reference_cited_by_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_reference_cited_by` MODIFY COLUMN `reference_object_id` bigint NOT NULL;
ALTER TABLE `lupo_reference_cited_by` MODIFY COLUMN `content_id` bigint NOT NULL;
ALTER TABLE `lupo_reference_cited_by` MODIFY COLUMN `section_anchor_slug` varchar(255);
ALTER TABLE `lupo_reference_cited_by` MODIFY COLUMN `section_order` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_reference_cited_by` MODIFY COLUMN `reference_type` varchar(50) NOT NULL;
ALTER TABLE `lupo_reference_cited_by` MODIFY COLUMN `raw_reference` text;
ALTER TABLE `lupo_reference_cited_by` MODIFY COLUMN `meta_json` json;
ALTER TABLE `lupo_reference_cited_by` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_reference_cited_by` MODIFY COLUMN `deleted_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_reference_cited_by` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_reference_cited_by` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_reference_cited_by` ADD PRIMARY KEY (`reference_cited_by_id`);
ALTER TABLE `lupo_reference_cited_by` ADD INDEX IF NOT EXISTS `idx_content_id` (`content_id`);
ALTER TABLE `lupo_reference_cited_by` ADD INDEX IF NOT EXISTS `idx_is_deleted` (`is_deleted`);
ALTER TABLE `lupo_reference_cited_by` ADD INDEX IF NOT EXISTS `idx_reference_object` (`reference_object_id`);
ALTER TABLE `lupo_reference_cited_by` ADD INDEX IF NOT EXISTS `idx_reference_type` (`reference_type`);
ALTER TABLE `lupo_reference_cited_by` ADD INDEX IF NOT EXISTS `idx_section_anchor` (`section_anchor_slug`);

CREATE TABLE IF NOT EXISTS `lupo_reference_objects` (
  `reference_object_id` bigint NOT NULL auto_increment,
  `object_type` varchar(50) NOT NULL,
  `object_slug` varchar(255) NOT NULL,
  `object_label` varchar(255),
  `meta_json` json,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (`reference_object_id`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_object_slug` (`object_slug`),
  KEY `idx_object_type` (`object_type`),
  KEY `idx_type_slug` (`object_type`, `object_slug`)
);

ALTER TABLE `lupo_reference_objects` ADD COLUMN IF NOT EXISTS `reference_object_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_reference_objects` ADD COLUMN IF NOT EXISTS `object_type` varchar(50) NOT NULL;
ALTER TABLE `lupo_reference_objects` ADD COLUMN IF NOT EXISTS `object_slug` varchar(255) NOT NULL;
ALTER TABLE `lupo_reference_objects` ADD COLUMN IF NOT EXISTS `object_label` varchar(255);
ALTER TABLE `lupo_reference_objects` ADD COLUMN IF NOT EXISTS `meta_json` json;
ALTER TABLE `lupo_reference_objects` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_reference_objects` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_reference_objects` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_reference_objects` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_reference_objects` MODIFY COLUMN `reference_object_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_reference_objects` MODIFY COLUMN `object_type` varchar(50) NOT NULL;
ALTER TABLE `lupo_reference_objects` MODIFY COLUMN `object_slug` varchar(255) NOT NULL;
ALTER TABLE `lupo_reference_objects` MODIFY COLUMN `object_label` varchar(255);
ALTER TABLE `lupo_reference_objects` MODIFY COLUMN `meta_json` json;
ALTER TABLE `lupo_reference_objects` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_reference_objects` MODIFY COLUMN `deleted_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_reference_objects` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_reference_objects` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_reference_objects` ADD PRIMARY KEY (`reference_object_id`);
ALTER TABLE `lupo_reference_objects` ADD INDEX IF NOT EXISTS `idx_is_deleted` (`is_deleted`);
ALTER TABLE `lupo_reference_objects` ADD INDEX IF NOT EXISTS `idx_object_slug` (`object_slug`);
ALTER TABLE `lupo_reference_objects` ADD INDEX IF NOT EXISTS `idx_object_type` (`object_type`);
ALTER TABLE `lupo_reference_objects` ADD INDEX IF NOT EXISTS `idx_type_slug` (`object_type`, `object_slug`);

CREATE TABLE IF NOT EXISTS `lupo_relationships` (
  `relationship_id` bigint NOT NULL,
  `source_type` varchar(50),
  `source_id` bigint,
  `edge_type` varchar(50),
  `target_type` varchar(50),
  `target_id` bigint,
  `created_ymdhis` bigint,
  `updated_ymdhis` bigint,
  `is_deleted` tinyint DEFAULT 0,
  PRIMARY KEY (`relationship_id`),
  KEY `idx_relationship_lookup` (`source_type`, `source_id`, `edge_type`, `is_deleted`)
);

ALTER TABLE `lupo_relationships` ADD COLUMN IF NOT EXISTS `relationship_id` bigint NOT NULL;
ALTER TABLE `lupo_relationships` ADD COLUMN IF NOT EXISTS `source_type` varchar(50);
ALTER TABLE `lupo_relationships` ADD COLUMN IF NOT EXISTS `source_id` bigint;
ALTER TABLE `lupo_relationships` ADD COLUMN IF NOT EXISTS `edge_type` varchar(50);
ALTER TABLE `lupo_relationships` ADD COLUMN IF NOT EXISTS `target_type` varchar(50);
ALTER TABLE `lupo_relationships` ADD COLUMN IF NOT EXISTS `target_id` bigint;
ALTER TABLE `lupo_relationships` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint;
ALTER TABLE `lupo_relationships` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint;
ALTER TABLE `lupo_relationships` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint DEFAULT 0;
ALTER TABLE `lupo_relationships` MODIFY COLUMN `relationship_id` bigint NOT NULL;
ALTER TABLE `lupo_relationships` MODIFY COLUMN `source_type` varchar(50);
ALTER TABLE `lupo_relationships` MODIFY COLUMN `source_id` bigint;
ALTER TABLE `lupo_relationships` MODIFY COLUMN `edge_type` varchar(50);
ALTER TABLE `lupo_relationships` MODIFY COLUMN `target_type` varchar(50);
ALTER TABLE `lupo_relationships` MODIFY COLUMN `target_id` bigint;
ALTER TABLE `lupo_relationships` MODIFY COLUMN `created_ymdhis` bigint;
ALTER TABLE `lupo_relationships` MODIFY COLUMN `updated_ymdhis` bigint;
ALTER TABLE `lupo_relationships` MODIFY COLUMN `is_deleted` tinyint DEFAULT 0;
ALTER TABLE `lupo_relationships` ADD PRIMARY KEY (`relationship_id`);
ALTER TABLE `lupo_relationships` ADD INDEX IF NOT EXISTS `idx_relationship_lookup` (`source_type`, `source_id`, `edge_type`, `is_deleted`);

CREATE TABLE IF NOT EXISTS `lupo_search_index` (
  `search_index_id` bigint NOT NULL auto_increment,
  `domain_id` bigint NOT NULL COMMENT 'Domain scope for multi-tenant isolation',
  `entity_type` varchar(50) NOT NULL COMMENT 'Type of entity (atom, content, collection, hashtag, question, etc.)',
  `entity_id` bigint NOT NULL COMMENT 'ID of the entity in its source table',
  `title_text` text COMMENT 'Title or primary label of the entity',
  `body_text` longtext COMMENT 'Full searchable text content',
  `keywords_text` text COMMENT 'Comma-separated keywords, tags, or categories',
  `search_metadata` text COMMENT 'JSON-encoded additional search metadata',
  `relevance_score` float DEFAULT 1 COMMENT 'Search relevance score (0.0 - 1.0)',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated',
  PRIMARY KEY (`search_index_id`),
  KEY `ft_search_content` (`title_text`, `body_text`, `keywords_text`),
  KEY `idx_domain_type` (`domain_id`, `entity_type`),
  KEY `idx_entity_reference` (`entity_type`, `entity_id`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_relevance` (`relevance_score`),
  KEY `idx_updated` (`updated_ymdhis`),
  UNIQUE KEY `unique_entity` (`domain_id`, `entity_type`, `entity_id`)
);

ALTER TABLE `lupo_search_index` ADD COLUMN IF NOT EXISTS `search_index_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_search_index` ADD COLUMN IF NOT EXISTS `domain_id` bigint NOT NULL COMMENT 'Domain scope for multi-tenant isolation';
ALTER TABLE `lupo_search_index` ADD COLUMN IF NOT EXISTS `entity_type` varchar(50) NOT NULL COMMENT 'Type of entity (atom, content, collection, hashtag, question, etc.)';
ALTER TABLE `lupo_search_index` ADD COLUMN IF NOT EXISTS `entity_id` bigint NOT NULL COMMENT 'ID of the entity in its source table';
ALTER TABLE `lupo_search_index` ADD COLUMN IF NOT EXISTS `title_text` text COMMENT 'Title or primary label of the entity';
ALTER TABLE `lupo_search_index` ADD COLUMN IF NOT EXISTS `body_text` longtext COMMENT 'Full searchable text content';
ALTER TABLE `lupo_search_index` ADD COLUMN IF NOT EXISTS `keywords_text` text COMMENT 'Comma-separated keywords, tags, or categories';
ALTER TABLE `lupo_search_index` ADD COLUMN IF NOT EXISTS `search_metadata` text COMMENT 'JSON-encoded additional search metadata';
ALTER TABLE `lupo_search_index` ADD COLUMN IF NOT EXISTS `relevance_score` float DEFAULT 1 COMMENT 'Search relevance score (0.0 - 1.0)';
ALTER TABLE `lupo_search_index` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted';
ALTER TABLE `lupo_search_index` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted';
ALTER TABLE `lupo_search_index` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created';
ALTER TABLE `lupo_search_index` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated';
ALTER TABLE `lupo_search_index` MODIFY COLUMN `search_index_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_search_index` MODIFY COLUMN `domain_id` bigint NOT NULL COMMENT 'Domain scope for multi-tenant isolation';
ALTER TABLE `lupo_search_index` MODIFY COLUMN `entity_type` varchar(50) NOT NULL COMMENT 'Type of entity (atom, content, collection, hashtag, question, etc.)';
ALTER TABLE `lupo_search_index` MODIFY COLUMN `entity_id` bigint NOT NULL COMMENT 'ID of the entity in its source table';
ALTER TABLE `lupo_search_index` MODIFY COLUMN `title_text` text COMMENT 'Title or primary label of the entity';
ALTER TABLE `lupo_search_index` MODIFY COLUMN `body_text` longtext COMMENT 'Full searchable text content';
ALTER TABLE `lupo_search_index` MODIFY COLUMN `keywords_text` text COMMENT 'Comma-separated keywords, tags, or categories';
ALTER TABLE `lupo_search_index` MODIFY COLUMN `search_metadata` text COMMENT 'JSON-encoded additional search metadata';
ALTER TABLE `lupo_search_index` MODIFY COLUMN `relevance_score` float DEFAULT 1 COMMENT 'Search relevance score (0.0 - 1.0)';
ALTER TABLE `lupo_search_index` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted';
ALTER TABLE `lupo_search_index` MODIFY COLUMN `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted';
ALTER TABLE `lupo_search_index` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created';
ALTER TABLE `lupo_search_index` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated';
ALTER TABLE `lupo_search_index` ADD PRIMARY KEY (`search_index_id`);
ALTER TABLE `lupo_search_index` ADD INDEX IF NOT EXISTS `ft_search_content` (`title_text`, `body_text`, `keywords_text`);
ALTER TABLE `lupo_search_index` ADD INDEX IF NOT EXISTS `idx_domain_type` (`domain_id`, `entity_type`);
ALTER TABLE `lupo_search_index` ADD INDEX IF NOT EXISTS `idx_entity_reference` (`entity_type`, `entity_id`);
ALTER TABLE `lupo_search_index` ADD INDEX IF NOT EXISTS `idx_is_deleted` (`is_deleted`);
ALTER TABLE `lupo_search_index` ADD INDEX IF NOT EXISTS `idx_relevance` (`relevance_score`);
ALTER TABLE `lupo_search_index` ADD INDEX IF NOT EXISTS `idx_updated` (`updated_ymdhis`);
ALTER TABLE `lupo_search_index` ADD UNIQUE INDEX IF NOT EXISTS `unique_entity` (`domain_id`, `entity_type`, `entity_id`);

CREATE TABLE IF NOT EXISTS `lupo_search_rebuild_log` (
  `search_rebuild_log_id` bigint NOT NULL auto_increment,
  `entity_type` varchar(50) NOT NULL COMMENT 'Type of entity (e.g., content, atom, collection)',
  `entity_id` bigint NOT NULL COMMENT 'ID of the entity to be reindexed',
  `action` enum('insert','update','delete') NOT NULL COMMENT 'Type of index operation needed',
  `status` enum('pending','processing','completed','failed') NOT NULL DEFAULT 'pending' COMMENT 'Current status of the rebuild operation',
  `attempts` tinyint NOT NULL DEFAULT 0 COMMENT 'Number of processing attempts',
  `last_error` text COMMENT 'Error message from last failed attempt',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when record was created',
  `processed_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when processing completed',
  `next_attempt_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS for next retry attempt',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  PRIMARY KEY (`search_rebuild_log_id`),
  KEY `idx_created` (`created_ymdhis`),
  KEY `idx_entity` (`entity_type`, `entity_id`),
  KEY `idx_status_retry` (`status`, `next_attempt_ymdhis`),
  UNIQUE KEY `unique_entity_operation` (`entity_type`, `entity_id`, `action`)
);

ALTER TABLE `lupo_search_rebuild_log` ADD COLUMN IF NOT EXISTS `search_rebuild_log_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_search_rebuild_log` ADD COLUMN IF NOT EXISTS `entity_type` varchar(50) NOT NULL COMMENT 'Type of entity (e.g., content, atom, collection)';
ALTER TABLE `lupo_search_rebuild_log` ADD COLUMN IF NOT EXISTS `entity_id` bigint NOT NULL COMMENT 'ID of the entity to be reindexed';
ALTER TABLE `lupo_search_rebuild_log` ADD COLUMN IF NOT EXISTS `action` enum('insert','update','delete') NOT NULL COMMENT 'Type of index operation needed';
ALTER TABLE `lupo_search_rebuild_log` ADD COLUMN IF NOT EXISTS `status` enum('pending','processing','completed','failed') NOT NULL DEFAULT 'pending' COMMENT 'Current status of the rebuild operation';
ALTER TABLE `lupo_search_rebuild_log` ADD COLUMN IF NOT EXISTS `attempts` tinyint NOT NULL DEFAULT 0 COMMENT 'Number of processing attempts';
ALTER TABLE `lupo_search_rebuild_log` ADD COLUMN IF NOT EXISTS `last_error` text COMMENT 'Error message from last failed attempt';
ALTER TABLE `lupo_search_rebuild_log` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when record was created';
ALTER TABLE `lupo_search_rebuild_log` ADD COLUMN IF NOT EXISTS `processed_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when processing completed';
ALTER TABLE `lupo_search_rebuild_log` ADD COLUMN IF NOT EXISTS `next_attempt_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS for next retry attempt';
ALTER TABLE `lupo_search_rebuild_log` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted';
ALTER TABLE `lupo_search_rebuild_log` MODIFY COLUMN `search_rebuild_log_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_search_rebuild_log` MODIFY COLUMN `entity_type` varchar(50) NOT NULL COMMENT 'Type of entity (e.g., content, atom, collection)';
ALTER TABLE `lupo_search_rebuild_log` MODIFY COLUMN `entity_id` bigint NOT NULL COMMENT 'ID of the entity to be reindexed';
ALTER TABLE `lupo_search_rebuild_log` MODIFY COLUMN `action` enum('insert','update','delete') NOT NULL COMMENT 'Type of index operation needed';
ALTER TABLE `lupo_search_rebuild_log` MODIFY COLUMN `status` enum('pending','processing','completed','failed') NOT NULL DEFAULT 'pending' COMMENT 'Current status of the rebuild operation';
ALTER TABLE `lupo_search_rebuild_log` MODIFY COLUMN `attempts` tinyint NOT NULL DEFAULT 0 COMMENT 'Number of processing attempts';
ALTER TABLE `lupo_search_rebuild_log` MODIFY COLUMN `last_error` text COMMENT 'Error message from last failed attempt';
ALTER TABLE `lupo_search_rebuild_log` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when record was created';
ALTER TABLE `lupo_search_rebuild_log` MODIFY COLUMN `processed_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when processing completed';
ALTER TABLE `lupo_search_rebuild_log` MODIFY COLUMN `next_attempt_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS for next retry attempt';
ALTER TABLE `lupo_search_rebuild_log` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted';
ALTER TABLE `lupo_search_rebuild_log` ADD PRIMARY KEY (`search_rebuild_log_id`);
ALTER TABLE `lupo_search_rebuild_log` ADD INDEX IF NOT EXISTS `idx_created` (`created_ymdhis`);
ALTER TABLE `lupo_search_rebuild_log` ADD INDEX IF NOT EXISTS `idx_entity` (`entity_type`, `entity_id`);
ALTER TABLE `lupo_search_rebuild_log` ADD INDEX IF NOT EXISTS `idx_status_retry` (`status`, `next_attempt_ymdhis`);
ALTER TABLE `lupo_search_rebuild_log` ADD UNIQUE INDEX IF NOT EXISTS `unique_entity_operation` (`entity_type`, `entity_id`, `action`);

CREATE TABLE IF NOT EXISTS `lupo_semantic_categories` (
  `category_id` bigint NOT NULL auto_increment COMMENT 'Primary key for semantic category',
  `category_name` varchar(255) NOT NULL COMMENT 'Category name',
  `category_slug` varchar(255) NOT NULL COMMENT 'URL-friendly category slug',
  `description` text COMMENT 'Category description',
  `parent_category_id` bigint COMMENT 'Parent category ID',
  `sort_order` int NOT NULL DEFAULT 0 COMMENT 'Display order',
  `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint',
  `updated_ymdhis` bigint NOT NULL COMMENT 'Last update bigint',
  `is_active` tinyint NOT NULL DEFAULT 1 COMMENT 'Category active flag',
  PRIMARY KEY (`category_id`),
  KEY `idx_created` (`created_ymdhis`),
  KEY `idx_created_ymdhis` (`created_ymdhis`, `is_active`),
  KEY `idx_is_active` (`is_active`),
  KEY `idx_parent_category` (`parent_category_id`),
  KEY `idx_sort_order` (`sort_order`),
  UNIQUE KEY `uk_category_slug` (`category_slug`)
);

ALTER TABLE `lupo_semantic_categories` ADD COLUMN IF NOT EXISTS `category_id` bigint NOT NULL auto_increment COMMENT 'Primary key for semantic category';
ALTER TABLE `lupo_semantic_categories` ADD COLUMN IF NOT EXISTS `category_name` varchar(255) NOT NULL COMMENT 'Category name';
ALTER TABLE `lupo_semantic_categories` ADD COLUMN IF NOT EXISTS `category_slug` varchar(255) NOT NULL COMMENT 'URL-friendly category slug';
ALTER TABLE `lupo_semantic_categories` ADD COLUMN IF NOT EXISTS `description` text COMMENT 'Category description';
ALTER TABLE `lupo_semantic_categories` ADD COLUMN IF NOT EXISTS `parent_category_id` bigint COMMENT 'Parent category ID';
ALTER TABLE `lupo_semantic_categories` ADD COLUMN IF NOT EXISTS `sort_order` int NOT NULL DEFAULT 0 COMMENT 'Display order';
ALTER TABLE `lupo_semantic_categories` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint';
ALTER TABLE `lupo_semantic_categories` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL COMMENT 'Last update bigint';
ALTER TABLE `lupo_semantic_categories` ADD COLUMN IF NOT EXISTS `is_active` tinyint NOT NULL DEFAULT 1 COMMENT 'Category active flag';
ALTER TABLE `lupo_semantic_categories` MODIFY COLUMN `category_id` bigint NOT NULL auto_increment COMMENT 'Primary key for semantic category';
ALTER TABLE `lupo_semantic_categories` MODIFY COLUMN `category_name` varchar(255) NOT NULL COMMENT 'Category name';
ALTER TABLE `lupo_semantic_categories` MODIFY COLUMN `category_slug` varchar(255) NOT NULL COMMENT 'URL-friendly category slug';
ALTER TABLE `lupo_semantic_categories` MODIFY COLUMN `description` text COMMENT 'Category description';
ALTER TABLE `lupo_semantic_categories` MODIFY COLUMN `parent_category_id` bigint COMMENT 'Parent category ID';
ALTER TABLE `lupo_semantic_categories` MODIFY COLUMN `sort_order` int NOT NULL DEFAULT 0 COMMENT 'Display order';
ALTER TABLE `lupo_semantic_categories` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint';
ALTER TABLE `lupo_semantic_categories` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL COMMENT 'Last update bigint';
ALTER TABLE `lupo_semantic_categories` MODIFY COLUMN `is_active` tinyint NOT NULL DEFAULT 1 COMMENT 'Category active flag';
ALTER TABLE `lupo_semantic_categories` ADD PRIMARY KEY (`category_id`);
ALTER TABLE `lupo_semantic_categories` ADD INDEX IF NOT EXISTS `idx_created` (`created_ymdhis`);
ALTER TABLE `lupo_semantic_categories` ADD INDEX IF NOT EXISTS `idx_created_ymdhis` (`created_ymdhis`, `is_active`);
ALTER TABLE `lupo_semantic_categories` ADD INDEX IF NOT EXISTS `idx_is_active` (`is_active`);
ALTER TABLE `lupo_semantic_categories` ADD INDEX IF NOT EXISTS `idx_parent_category` (`parent_category_id`);
ALTER TABLE `lupo_semantic_categories` ADD INDEX IF NOT EXISTS `idx_sort_order` (`sort_order`);
ALTER TABLE `lupo_semantic_categories` ADD UNIQUE INDEX IF NOT EXISTS `uk_category_slug` (`category_slug`);

CREATE TABLE IF NOT EXISTS `lupo_semantic_content_views` (
  `semantic_view_id` bigint NOT NULL auto_increment COMMENT 'Primary key for semantic content view',
  `view_name` varchar(255) NOT NULL COMMENT 'View name identifier',
  `view_type` enum('navigation','content','search','collection') NOT NULL COMMENT 'Type of semantic view',
  `title` varchar(255) NOT NULL COMMENT 'View title',
  `description` text COMMENT 'View description',
  `template_path` varchar(512) NOT NULL COMMENT 'Template file path',
  `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint',
  `updated_ymdhis` bigint NOT NULL COMMENT 'Last update bigint',
  `is_active` tinyint NOT NULL DEFAULT 1 COMMENT 'View active flag',
  `is_default` tinyint NOT NULL DEFAULT 0 COMMENT 'Default view flag',
  PRIMARY KEY (`semantic_view_id`),
  KEY `idx_created_ymdhis` (`created_ymdhis`, `is_default`, `is_active`),
  KEY `idx_is_active` (`is_active`),
  KEY `idx_is_default` (`is_default`),
  KEY `idx_view_type` (`view_type`),
  UNIQUE KEY `uk_view_name` (`view_name`)
);

ALTER TABLE `lupo_semantic_content_views` ADD COLUMN IF NOT EXISTS `semantic_view_id` bigint NOT NULL auto_increment COMMENT 'Primary key for semantic content view';
ALTER TABLE `lupo_semantic_content_views` ADD COLUMN IF NOT EXISTS `view_name` varchar(255) NOT NULL COMMENT 'View name identifier';
ALTER TABLE `lupo_semantic_content_views` ADD COLUMN IF NOT EXISTS `view_type` enum('navigation','content','search','collection') NOT NULL COMMENT 'Type of semantic view';
ALTER TABLE `lupo_semantic_content_views` ADD COLUMN IF NOT EXISTS `title` varchar(255) NOT NULL COMMENT 'View title';
ALTER TABLE `lupo_semantic_content_views` ADD COLUMN IF NOT EXISTS `description` text COMMENT 'View description';
ALTER TABLE `lupo_semantic_content_views` ADD COLUMN IF NOT EXISTS `template_path` varchar(512) NOT NULL COMMENT 'Template file path';
ALTER TABLE `lupo_semantic_content_views` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint';
ALTER TABLE `lupo_semantic_content_views` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL COMMENT 'Last update bigint';
ALTER TABLE `lupo_semantic_content_views` ADD COLUMN IF NOT EXISTS `is_active` tinyint NOT NULL DEFAULT 1 COMMENT 'View active flag';
ALTER TABLE `lupo_semantic_content_views` ADD COLUMN IF NOT EXISTS `is_default` tinyint NOT NULL DEFAULT 0 COMMENT 'Default view flag';
ALTER TABLE `lupo_semantic_content_views` MODIFY COLUMN `semantic_view_id` bigint NOT NULL auto_increment COMMENT 'Primary key for semantic content view';
ALTER TABLE `lupo_semantic_content_views` MODIFY COLUMN `view_name` varchar(255) NOT NULL COMMENT 'View name identifier';
ALTER TABLE `lupo_semantic_content_views` MODIFY COLUMN `view_type` enum('navigation','content','search','collection') NOT NULL COMMENT 'Type of semantic view';
ALTER TABLE `lupo_semantic_content_views` MODIFY COLUMN `title` varchar(255) NOT NULL COMMENT 'View title';
ALTER TABLE `lupo_semantic_content_views` MODIFY COLUMN `description` text COMMENT 'View description';
ALTER TABLE `lupo_semantic_content_views` MODIFY COLUMN `template_path` varchar(512) NOT NULL COMMENT 'Template file path';
ALTER TABLE `lupo_semantic_content_views` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint';
ALTER TABLE `lupo_semantic_content_views` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL COMMENT 'Last update bigint';
ALTER TABLE `lupo_semantic_content_views` MODIFY COLUMN `is_active` tinyint NOT NULL DEFAULT 1 COMMENT 'View active flag';
ALTER TABLE `lupo_semantic_content_views` MODIFY COLUMN `is_default` tinyint NOT NULL DEFAULT 0 COMMENT 'Default view flag';
ALTER TABLE `lupo_semantic_content_views` ADD PRIMARY KEY (`semantic_view_id`);
ALTER TABLE `lupo_semantic_content_views` ADD INDEX IF NOT EXISTS `idx_created_ymdhis` (`created_ymdhis`, `is_default`, `is_active`);
ALTER TABLE `lupo_semantic_content_views` ADD INDEX IF NOT EXISTS `idx_is_active` (`is_active`);
ALTER TABLE `lupo_semantic_content_views` ADD INDEX IF NOT EXISTS `idx_is_default` (`is_default`);
ALTER TABLE `lupo_semantic_content_views` ADD INDEX IF NOT EXISTS `idx_view_type` (`view_type`);
ALTER TABLE `lupo_semantic_content_views` ADD UNIQUE INDEX IF NOT EXISTS `uk_view_name` (`view_name`);

CREATE TABLE IF NOT EXISTS `lupo_semantic_navigation_overview` (
  `navigation_id` bigint NOT NULL auto_increment COMMENT 'Primary key for semantic navigation',
  `title` varchar(255) NOT NULL COMMENT 'Navigation title',
  `description` text COMMENT 'Navigation description',
  `navigation_tree` json NOT NULL COMMENT 'Hierarchical navigation structure',
  `content_categories` json NOT NULL COMMENT 'Content categories included',
  `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint',
  `updated_ymdhis` bigint NOT NULL COMMENT 'Last update bigint',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag',
  `deleted_ymdhis` bigint COMMENT 'Deletion bigint',
  PRIMARY KEY (`navigation_id`),
  KEY `idx_created` (`created_ymdhis`),
  KEY `idx_created_ymdhis` (`created_ymdhis`, `is_deleted`),
  KEY `idx_is_deleted` (`is_deleted`)
);

ALTER TABLE `lupo_semantic_navigation_overview` ADD COLUMN IF NOT EXISTS `navigation_id` bigint NOT NULL auto_increment COMMENT 'Primary key for semantic navigation';
ALTER TABLE `lupo_semantic_navigation_overview` ADD COLUMN IF NOT EXISTS `title` varchar(255) NOT NULL COMMENT 'Navigation title';
ALTER TABLE `lupo_semantic_navigation_overview` ADD COLUMN IF NOT EXISTS `description` text COMMENT 'Navigation description';
ALTER TABLE `lupo_semantic_navigation_overview` ADD COLUMN IF NOT EXISTS `navigation_tree` json NOT NULL COMMENT 'Hierarchical navigation structure';
ALTER TABLE `lupo_semantic_navigation_overview` ADD COLUMN IF NOT EXISTS `content_categories` json NOT NULL COMMENT 'Content categories included';
ALTER TABLE `lupo_semantic_navigation_overview` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint';
ALTER TABLE `lupo_semantic_navigation_overview` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL COMMENT 'Last update bigint';
ALTER TABLE `lupo_semantic_navigation_overview` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag';
ALTER TABLE `lupo_semantic_navigation_overview` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint COMMENT 'Deletion bigint';
ALTER TABLE `lupo_semantic_navigation_overview` MODIFY COLUMN `navigation_id` bigint NOT NULL auto_increment COMMENT 'Primary key for semantic navigation';
ALTER TABLE `lupo_semantic_navigation_overview` MODIFY COLUMN `title` varchar(255) NOT NULL COMMENT 'Navigation title';
ALTER TABLE `lupo_semantic_navigation_overview` MODIFY COLUMN `description` text COMMENT 'Navigation description';
ALTER TABLE `lupo_semantic_navigation_overview` MODIFY COLUMN `navigation_tree` json NOT NULL COMMENT 'Hierarchical navigation structure';
ALTER TABLE `lupo_semantic_navigation_overview` MODIFY COLUMN `content_categories` json NOT NULL COMMENT 'Content categories included';
ALTER TABLE `lupo_semantic_navigation_overview` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint';
ALTER TABLE `lupo_semantic_navigation_overview` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL COMMENT 'Last update bigint';
ALTER TABLE `lupo_semantic_navigation_overview` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag';
ALTER TABLE `lupo_semantic_navigation_overview` MODIFY COLUMN `deleted_ymdhis` bigint COMMENT 'Deletion bigint';
ALTER TABLE `lupo_semantic_navigation_overview` ADD PRIMARY KEY (`navigation_id`);
ALTER TABLE `lupo_semantic_navigation_overview` ADD INDEX IF NOT EXISTS `idx_created` (`created_ymdhis`);
ALTER TABLE `lupo_semantic_navigation_overview` ADD INDEX IF NOT EXISTS `idx_created_ymdhis` (`created_ymdhis`, `is_deleted`);
ALTER TABLE `lupo_semantic_navigation_overview` ADD INDEX IF NOT EXISTS `idx_is_deleted` (`is_deleted`);

CREATE TABLE IF NOT EXISTS `lupo_semantic_paths` (
  `id` bigint unsigned NOT NULL auto_increment,
  `source_page_id` bigint unsigned NOT NULL,
  `target_page_id` bigint unsigned NOT NULL,
  `layer` enum('interaction','extracted','navigation','ai') NOT NULL,
  `weight` float NOT NULL DEFAULT 0,
  `decay_factor` float NOT NULL DEFAULT 1,
  `trend_score` float NOT NULL DEFAULT 0,
  `timeframe` enum('hour','day','week','month','year','total','custom') NOT NULL,
  `custom_start` datetime,
  `custom_end` datetime,
  `created_ymdhis` datetime NOT NULL,
  `updated_ymdhis` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `layer` (`layer`),
  KEY `source_page_id` (`source_page_id`),
  KEY `target_page_id` (`target_page_id`),
  KEY `timeframe` (`timeframe`)
);

ALTER TABLE `lupo_semantic_paths` ADD COLUMN IF NOT EXISTS `id` bigint unsigned NOT NULL auto_increment;
ALTER TABLE `lupo_semantic_paths` ADD COLUMN IF NOT EXISTS `source_page_id` bigint unsigned NOT NULL;
ALTER TABLE `lupo_semantic_paths` ADD COLUMN IF NOT EXISTS `target_page_id` bigint unsigned NOT NULL;
ALTER TABLE `lupo_semantic_paths` ADD COLUMN IF NOT EXISTS `layer` enum('interaction','extracted','navigation','ai') NOT NULL;
ALTER TABLE `lupo_semantic_paths` ADD COLUMN IF NOT EXISTS `weight` float NOT NULL DEFAULT 0;
ALTER TABLE `lupo_semantic_paths` ADD COLUMN IF NOT EXISTS `decay_factor` float NOT NULL DEFAULT 1;
ALTER TABLE `lupo_semantic_paths` ADD COLUMN IF NOT EXISTS `trend_score` float NOT NULL DEFAULT 0;
ALTER TABLE `lupo_semantic_paths` ADD COLUMN IF NOT EXISTS `timeframe` enum('hour','day','week','month','year','total','custom') NOT NULL;
ALTER TABLE `lupo_semantic_paths` ADD COLUMN IF NOT EXISTS `custom_start` datetime;
ALTER TABLE `lupo_semantic_paths` ADD COLUMN IF NOT EXISTS `custom_end` datetime;
ALTER TABLE `lupo_semantic_paths` ADD COLUMN IF NOT EXISTS `created_ymdhis` datetime NOT NULL;
ALTER TABLE `lupo_semantic_paths` ADD COLUMN IF NOT EXISTS `updated_ymdhis` datetime NOT NULL;
ALTER TABLE `lupo_semantic_paths` MODIFY COLUMN `id` bigint unsigned NOT NULL auto_increment;
ALTER TABLE `lupo_semantic_paths` MODIFY COLUMN `source_page_id` bigint unsigned NOT NULL;
ALTER TABLE `lupo_semantic_paths` MODIFY COLUMN `target_page_id` bigint unsigned NOT NULL;
ALTER TABLE `lupo_semantic_paths` MODIFY COLUMN `layer` enum('interaction','extracted','navigation','ai') NOT NULL;
ALTER TABLE `lupo_semantic_paths` MODIFY COLUMN `weight` float NOT NULL DEFAULT 0;
ALTER TABLE `lupo_semantic_paths` MODIFY COLUMN `decay_factor` float NOT NULL DEFAULT 1;
ALTER TABLE `lupo_semantic_paths` MODIFY COLUMN `trend_score` float NOT NULL DEFAULT 0;
ALTER TABLE `lupo_semantic_paths` MODIFY COLUMN `timeframe` enum('hour','day','week','month','year','total','custom') NOT NULL;
ALTER TABLE `lupo_semantic_paths` MODIFY COLUMN `custom_start` datetime;
ALTER TABLE `lupo_semantic_paths` MODIFY COLUMN `custom_end` datetime;
ALTER TABLE `lupo_semantic_paths` MODIFY COLUMN `created_ymdhis` datetime NOT NULL;
ALTER TABLE `lupo_semantic_paths` MODIFY COLUMN `updated_ymdhis` datetime NOT NULL;
ALTER TABLE `lupo_semantic_paths` ADD PRIMARY KEY (`id`);
ALTER TABLE `lupo_semantic_paths` ADD INDEX IF NOT EXISTS `layer` (`layer`);
ALTER TABLE `lupo_semantic_paths` ADD INDEX IF NOT EXISTS `source_page_id` (`source_page_id`);
ALTER TABLE `lupo_semantic_paths` ADD INDEX IF NOT EXISTS `target_page_id` (`target_page_id`);
ALTER TABLE `lupo_semantic_paths` ADD INDEX IF NOT EXISTS `timeframe` (`timeframe`);

CREATE TABLE IF NOT EXISTS `lupo_semantic_relationships` (
  `relationship_id` bigint NOT NULL auto_increment COMMENT 'Primary key for semantic relationship',
  `source_content_id` bigint NOT NULL COMMENT 'Source content ID',
  `target_content_id` bigint COMMENT 'Target content ID',
  `relationship_type` enum('related','series','hierarchy') NOT NULL COMMENT 'Type of semantic relationship',
  `relationship_strength` decimal(3,2) NOT NULL DEFAULT 1.00 COMMENT 'Relationship strength (0.0-1.0)',
  `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint',
  PRIMARY KEY (`relationship_id`),
  KEY `idx_created` (`created_ymdhis`),
  KEY `idx_created_ymdhis` (`created_ymdhis`, `relationship_type`, `source_content_id`, `target_content_id`),
  KEY `idx_relationship_type` (`relationship_type`),
  KEY `idx_source_content` (`source_content_id`),
  KEY `idx_target_content` (`target_content_id`)
);

ALTER TABLE `lupo_semantic_relationships` ADD COLUMN IF NOT EXISTS `relationship_id` bigint NOT NULL auto_increment COMMENT 'Primary key for semantic relationship';
ALTER TABLE `lupo_semantic_relationships` ADD COLUMN IF NOT EXISTS `source_content_id` bigint NOT NULL COMMENT 'Source content ID';
ALTER TABLE `lupo_semantic_relationships` ADD COLUMN IF NOT EXISTS `target_content_id` bigint COMMENT 'Target content ID';
ALTER TABLE `lupo_semantic_relationships` ADD COLUMN IF NOT EXISTS `relationship_type` enum('related','series','hierarchy') NOT NULL COMMENT 'Type of semantic relationship';
ALTER TABLE `lupo_semantic_relationships` ADD COLUMN IF NOT EXISTS `relationship_strength` decimal(3,2) NOT NULL DEFAULT 1.00 COMMENT 'Relationship strength (0.0-1.0)';
ALTER TABLE `lupo_semantic_relationships` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint';
ALTER TABLE `lupo_semantic_relationships` MODIFY COLUMN `relationship_id` bigint NOT NULL auto_increment COMMENT 'Primary key for semantic relationship';
ALTER TABLE `lupo_semantic_relationships` MODIFY COLUMN `source_content_id` bigint NOT NULL COMMENT 'Source content ID';
ALTER TABLE `lupo_semantic_relationships` MODIFY COLUMN `target_content_id` bigint COMMENT 'Target content ID';
ALTER TABLE `lupo_semantic_relationships` MODIFY COLUMN `relationship_type` enum('related','series','hierarchy') NOT NULL COMMENT 'Type of semantic relationship';
ALTER TABLE `lupo_semantic_relationships` MODIFY COLUMN `relationship_strength` decimal(3,2) NOT NULL DEFAULT 1.00 COMMENT 'Relationship strength (0.0-1.0)';
ALTER TABLE `lupo_semantic_relationships` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint';
ALTER TABLE `lupo_semantic_relationships` ADD PRIMARY KEY (`relationship_id`);
ALTER TABLE `lupo_semantic_relationships` ADD INDEX IF NOT EXISTS `idx_created` (`created_ymdhis`);
ALTER TABLE `lupo_semantic_relationships` ADD INDEX IF NOT EXISTS `idx_created_ymdhis` (`created_ymdhis`, `relationship_type`, `source_content_id`, `target_content_id`);
ALTER TABLE `lupo_semantic_relationships` ADD INDEX IF NOT EXISTS `idx_relationship_type` (`relationship_type`);
ALTER TABLE `lupo_semantic_relationships` ADD INDEX IF NOT EXISTS `idx_source_content` (`source_content_id`);
ALTER TABLE `lupo_semantic_relationships` ADD INDEX IF NOT EXISTS `idx_target_content` (`target_content_id`);

CREATE TABLE IF NOT EXISTS `lupo_semantic_search_index` (
  `search_index_id` bigint NOT NULL auto_increment COMMENT 'Primary key for search index',
  `index_name` varchar(255) NOT NULL COMMENT 'Search index name',
  `index_type` enum('content','legacy_mapping','views') NOT NULL COMMENT 'Type of search index',
  `description` text COMMENT 'Search index description',
  `index_data` json NOT NULL COMMENT 'Search index data',
  `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint',
  `updated_ymdhis` bigint NOT NULL COMMENT 'Last update bigint',
  `is_active` tinyint NOT NULL DEFAULT 1 COMMENT 'Search index active flag',
  PRIMARY KEY (`search_index_id`),
  KEY `idx_created` (`created_ymdhis`),
  KEY `idx_created_ymdhis` (`created_ymdhis`, `is_active`),
  KEY `idx_index_type` (`index_type`),
  KEY `idx_is_active` (`is_active`),
  UNIQUE KEY `uk_index_name` (`index_name`)
);

ALTER TABLE `lupo_semantic_search_index` ADD COLUMN IF NOT EXISTS `search_index_id` bigint NOT NULL auto_increment COMMENT 'Primary key for search index';
ALTER TABLE `lupo_semantic_search_index` ADD COLUMN IF NOT EXISTS `index_name` varchar(255) NOT NULL COMMENT 'Search index name';
ALTER TABLE `lupo_semantic_search_index` ADD COLUMN IF NOT EXISTS `index_type` enum('content','legacy_mapping','views') NOT NULL COMMENT 'Type of search index';
ALTER TABLE `lupo_semantic_search_index` ADD COLUMN IF NOT EXISTS `description` text COMMENT 'Search index description';
ALTER TABLE `lupo_semantic_search_index` ADD COLUMN IF NOT EXISTS `index_data` json NOT NULL COMMENT 'Search index data';
ALTER TABLE `lupo_semantic_search_index` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint';
ALTER TABLE `lupo_semantic_search_index` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL COMMENT 'Last update bigint';
ALTER TABLE `lupo_semantic_search_index` ADD COLUMN IF NOT EXISTS `is_active` tinyint NOT NULL DEFAULT 1 COMMENT 'Search index active flag';
ALTER TABLE `lupo_semantic_search_index` MODIFY COLUMN `search_index_id` bigint NOT NULL auto_increment COMMENT 'Primary key for search index';
ALTER TABLE `lupo_semantic_search_index` MODIFY COLUMN `index_name` varchar(255) NOT NULL COMMENT 'Search index name';
ALTER TABLE `lupo_semantic_search_index` MODIFY COLUMN `index_type` enum('content','legacy_mapping','views') NOT NULL COMMENT 'Type of search index';
ALTER TABLE `lupo_semantic_search_index` MODIFY COLUMN `description` text COMMENT 'Search index description';
ALTER TABLE `lupo_semantic_search_index` MODIFY COLUMN `index_data` json NOT NULL COMMENT 'Search index data';
ALTER TABLE `lupo_semantic_search_index` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint';
ALTER TABLE `lupo_semantic_search_index` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL COMMENT 'Last update bigint';
ALTER TABLE `lupo_semantic_search_index` MODIFY COLUMN `is_active` tinyint NOT NULL DEFAULT 1 COMMENT 'Search index active flag';
ALTER TABLE `lupo_semantic_search_index` ADD PRIMARY KEY (`search_index_id`);
ALTER TABLE `lupo_semantic_search_index` ADD INDEX IF NOT EXISTS `idx_created` (`created_ymdhis`);
ALTER TABLE `lupo_semantic_search_index` ADD INDEX IF NOT EXISTS `idx_created_ymdhis` (`created_ymdhis`, `is_active`);
ALTER TABLE `lupo_semantic_search_index` ADD INDEX IF NOT EXISTS `idx_index_type` (`index_type`);
ALTER TABLE `lupo_semantic_search_index` ADD INDEX IF NOT EXISTS `idx_is_active` (`is_active`);
ALTER TABLE `lupo_semantic_search_index` ADD UNIQUE INDEX IF NOT EXISTS `uk_index_name` (`index_name`);

CREATE TABLE IF NOT EXISTS `lupo_semantic_tags` (
  `tag_id` bigint NOT NULL auto_increment COMMENT 'Primary key for semantic tag',
  `tag_name` varchar(255) NOT NULL COMMENT 'Tag name',
  `tag_slug` varchar(255) NOT NULL COMMENT 'URL-friendly tag slug',
  `description` text COMMENT 'Tag description',
  `color` varchar(7) NOT NULL DEFAULT '#666666' COMMENT 'Tag color hex',
  `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint',
  `updated_ymdhis` bigint NOT NULL COMMENT 'Last update bigint',
  `is_active` tinyint NOT NULL DEFAULT 1 COMMENT 'Tag active flag',
  PRIMARY KEY (`tag_id`),
  KEY `idx_created` (`created_ymdhis`),
  KEY `idx_created_ymdhis` (`created_ymdhis`, `is_active`),
  KEY `idx_is_active` (`is_active`),
  UNIQUE KEY `uk_tag_slug` (`tag_slug`)
);

ALTER TABLE `lupo_semantic_tags` ADD COLUMN IF NOT EXISTS `tag_id` bigint NOT NULL auto_increment COMMENT 'Primary key for semantic tag';
ALTER TABLE `lupo_semantic_tags` ADD COLUMN IF NOT EXISTS `tag_name` varchar(255) NOT NULL COMMENT 'Tag name';
ALTER TABLE `lupo_semantic_tags` ADD COLUMN IF NOT EXISTS `tag_slug` varchar(255) NOT NULL COMMENT 'URL-friendly tag slug';
ALTER TABLE `lupo_semantic_tags` ADD COLUMN IF NOT EXISTS `description` text COMMENT 'Tag description';
ALTER TABLE `lupo_semantic_tags` ADD COLUMN IF NOT EXISTS `color` varchar(7) NOT NULL DEFAULT '#666666' COMMENT 'Tag color hex';
ALTER TABLE `lupo_semantic_tags` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint';
ALTER TABLE `lupo_semantic_tags` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL COMMENT 'Last update bigint';
ALTER TABLE `lupo_semantic_tags` ADD COLUMN IF NOT EXISTS `is_active` tinyint NOT NULL DEFAULT 1 COMMENT 'Tag active flag';
ALTER TABLE `lupo_semantic_tags` MODIFY COLUMN `tag_id` bigint NOT NULL auto_increment COMMENT 'Primary key for semantic tag';
ALTER TABLE `lupo_semantic_tags` MODIFY COLUMN `tag_name` varchar(255) NOT NULL COMMENT 'Tag name';
ALTER TABLE `lupo_semantic_tags` MODIFY COLUMN `tag_slug` varchar(255) NOT NULL COMMENT 'URL-friendly tag slug';
ALTER TABLE `lupo_semantic_tags` MODIFY COLUMN `description` text COMMENT 'Tag description';
ALTER TABLE `lupo_semantic_tags` MODIFY COLUMN `color` varchar(7) NOT NULL DEFAULT '#666666' COMMENT 'Tag color hex';
ALTER TABLE `lupo_semantic_tags` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint';
ALTER TABLE `lupo_semantic_tags` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL COMMENT 'Last update bigint';
ALTER TABLE `lupo_semantic_tags` MODIFY COLUMN `is_active` tinyint NOT NULL DEFAULT 1 COMMENT 'Tag active flag';
ALTER TABLE `lupo_semantic_tags` ADD PRIMARY KEY (`tag_id`);
ALTER TABLE `lupo_semantic_tags` ADD INDEX IF NOT EXISTS `idx_created` (`created_ymdhis`);
ALTER TABLE `lupo_semantic_tags` ADD INDEX IF NOT EXISTS `idx_created_ymdhis` (`created_ymdhis`, `is_active`);
ALTER TABLE `lupo_semantic_tags` ADD INDEX IF NOT EXISTS `idx_is_active` (`is_active`);
ALTER TABLE `lupo_semantic_tags` ADD UNIQUE INDEX IF NOT EXISTS `uk_tag_slug` (`tag_slug`);

CREATE TABLE IF NOT EXISTS `lupo_semantic_translations` (
  `semantic_translation_id` bigint NOT NULL auto_increment,
  `language_code` varchar(8) NOT NULL COMMENT 'ISO 639-1 (2-letter) or BCP 47 language code',
  `entity_type` varchar(32) NOT NULL COMMENT 'Type of entity (atom, hashtag, edge, content, etc.)',
  `entity_id` bigint NOT NULL,
  `translated_text` text NOT NULL,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint,
  `created_by` bigint,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (`semantic_translation_id`),
  KEY `ft_translated_text` (`translated_text`),
  KEY `idx_created` (`created_ymdhis`),
  KEY `idx_deleted` (`is_deleted`),
  KEY `idx_entity_lookup` (`entity_type`, `entity_id`, `language_code`),
  KEY `idx_language_entity` (`language_code`, `entity_type`, `entity_id`),
  KEY `idx_updated` (`updated_ymdhis`),
  UNIQUE KEY `unq_translation` (`entity_type`, `entity_id`, `language_code`)
);

ALTER TABLE `lupo_semantic_translations` ADD COLUMN IF NOT EXISTS `semantic_translation_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_semantic_translations` ADD COLUMN IF NOT EXISTS `language_code` varchar(8) NOT NULL COMMENT 'ISO 639-1 (2-letter) or BCP 47 language code';
ALTER TABLE `lupo_semantic_translations` ADD COLUMN IF NOT EXISTS `entity_type` varchar(32) NOT NULL COMMENT 'Type of entity (atom, hashtag, edge, content, etc.)';
ALTER TABLE `lupo_semantic_translations` ADD COLUMN IF NOT EXISTS `entity_id` bigint NOT NULL;
ALTER TABLE `lupo_semantic_translations` ADD COLUMN IF NOT EXISTS `translated_text` text NOT NULL;
ALTER TABLE `lupo_semantic_translations` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_semantic_translations` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint;
ALTER TABLE `lupo_semantic_translations` ADD COLUMN IF NOT EXISTS `created_by` bigint;
ALTER TABLE `lupo_semantic_translations` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint(1) NOT NULL DEFAULT 0;
ALTER TABLE `lupo_semantic_translations` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint;
ALTER TABLE `lupo_semantic_translations` MODIFY COLUMN `semantic_translation_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_semantic_translations` MODIFY COLUMN `language_code` varchar(8) NOT NULL COMMENT 'ISO 639-1 (2-letter) or BCP 47 language code';
ALTER TABLE `lupo_semantic_translations` MODIFY COLUMN `entity_type` varchar(32) NOT NULL COMMENT 'Type of entity (atom, hashtag, edge, content, etc.)';
ALTER TABLE `lupo_semantic_translations` MODIFY COLUMN `entity_id` bigint NOT NULL;
ALTER TABLE `lupo_semantic_translations` MODIFY COLUMN `translated_text` text NOT NULL;
ALTER TABLE `lupo_semantic_translations` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_semantic_translations` MODIFY COLUMN `updated_ymdhis` bigint;
ALTER TABLE `lupo_semantic_translations` MODIFY COLUMN `created_by` bigint;
ALTER TABLE `lupo_semantic_translations` MODIFY COLUMN `is_deleted` tinyint(1) NOT NULL DEFAULT 0;
ALTER TABLE `lupo_semantic_translations` MODIFY COLUMN `deleted_ymdhis` bigint;
ALTER TABLE `lupo_semantic_translations` ADD PRIMARY KEY (`semantic_translation_id`);
ALTER TABLE `lupo_semantic_translations` ADD INDEX IF NOT EXISTS `ft_translated_text` (`translated_text`);
ALTER TABLE `lupo_semantic_translations` ADD INDEX IF NOT EXISTS `idx_created` (`created_ymdhis`);
ALTER TABLE `lupo_semantic_translations` ADD INDEX IF NOT EXISTS `idx_deleted` (`is_deleted`);
ALTER TABLE `lupo_semantic_translations` ADD INDEX IF NOT EXISTS `idx_entity_lookup` (`entity_type`, `entity_id`, `language_code`);
ALTER TABLE `lupo_semantic_translations` ADD INDEX IF NOT EXISTS `idx_language_entity` (`language_code`, `entity_type`, `entity_id`);
ALTER TABLE `lupo_semantic_translations` ADD INDEX IF NOT EXISTS `idx_updated` (`updated_ymdhis`);
ALTER TABLE `lupo_semantic_translations` ADD UNIQUE INDEX IF NOT EXISTS `unq_translation` (`entity_type`, `entity_id`, `language_code`);

CREATE TABLE IF NOT EXISTS `lupo_session_events` (
  `session_event_id` bigint NOT NULL auto_increment COMMENT 'Primary key for session event',
  `session_id` varchar(255) NOT NULL COMMENT 'Session identifier',
  `actor_id` bigint COMMENT 'Actor ID from lupo_actors',
  `tab_id` varchar(255) COMMENT 'Tab identifier for multi-tab tracking',
  `world_id` bigint COMMENT 'World context ID',
  `world_key` varchar(255) COMMENT 'World context key',
  `world_type` varchar(50) COMMENT 'World context type',
  `event_type` varchar(100) NOT NULL COMMENT 'Type of session event',
  `event_data` json COMMENT 'Event-specific data',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS',
  PRIMARY KEY (`session_event_id`),
  KEY `idx_actor_id` (`actor_id`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_event_type` (`event_type`),
  KEY `idx_session_event_type` (`session_id`, `event_type`),
  KEY `idx_session_id` (`session_id`),
  KEY `idx_tab_id` (`tab_id`),
  KEY `idx_world_id` (`world_id`)
);

ALTER TABLE `lupo_session_events` ADD COLUMN IF NOT EXISTS `session_event_id` bigint NOT NULL auto_increment COMMENT 'Primary key for session event';
ALTER TABLE `lupo_session_events` ADD COLUMN IF NOT EXISTS `session_id` varchar(255) NOT NULL COMMENT 'Session identifier';
ALTER TABLE `lupo_session_events` ADD COLUMN IF NOT EXISTS `actor_id` bigint COMMENT 'Actor ID from lupo_actors';
ALTER TABLE `lupo_session_events` ADD COLUMN IF NOT EXISTS `tab_id` varchar(255) COMMENT 'Tab identifier for multi-tab tracking';
ALTER TABLE `lupo_session_events` ADD COLUMN IF NOT EXISTS `world_id` bigint COMMENT 'World context ID';
ALTER TABLE `lupo_session_events` ADD COLUMN IF NOT EXISTS `world_key` varchar(255) COMMENT 'World context key';
ALTER TABLE `lupo_session_events` ADD COLUMN IF NOT EXISTS `world_type` varchar(50) COMMENT 'World context type';
ALTER TABLE `lupo_session_events` ADD COLUMN IF NOT EXISTS `event_type` varchar(100) NOT NULL COMMENT 'Type of session event';
ALTER TABLE `lupo_session_events` ADD COLUMN IF NOT EXISTS `event_data` json COMMENT 'Event-specific data';
ALTER TABLE `lupo_session_events` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS';
ALTER TABLE `lupo_session_events` MODIFY COLUMN `session_event_id` bigint NOT NULL auto_increment COMMENT 'Primary key for session event';
ALTER TABLE `lupo_session_events` MODIFY COLUMN `session_id` varchar(255) NOT NULL COMMENT 'Session identifier';
ALTER TABLE `lupo_session_events` MODIFY COLUMN `actor_id` bigint COMMENT 'Actor ID from lupo_actors';
ALTER TABLE `lupo_session_events` MODIFY COLUMN `tab_id` varchar(255) COMMENT 'Tab identifier for multi-tab tracking';
ALTER TABLE `lupo_session_events` MODIFY COLUMN `world_id` bigint COMMENT 'World context ID';
ALTER TABLE `lupo_session_events` MODIFY COLUMN `world_key` varchar(255) COMMENT 'World context key';
ALTER TABLE `lupo_session_events` MODIFY COLUMN `world_type` varchar(50) COMMENT 'World context type';
ALTER TABLE `lupo_session_events` MODIFY COLUMN `event_type` varchar(100) NOT NULL COMMENT 'Type of session event';
ALTER TABLE `lupo_session_events` MODIFY COLUMN `event_data` json COMMENT 'Event-specific data';
ALTER TABLE `lupo_session_events` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS';
ALTER TABLE `lupo_session_events` ADD PRIMARY KEY (`session_event_id`);
ALTER TABLE `lupo_session_events` ADD INDEX IF NOT EXISTS `idx_actor_id` (`actor_id`);
ALTER TABLE `lupo_session_events` ADD INDEX IF NOT EXISTS `idx_created_ymdhis` (`created_ymdhis`);
ALTER TABLE `lupo_session_events` ADD INDEX IF NOT EXISTS `idx_event_type` (`event_type`);
ALTER TABLE `lupo_session_events` ADD INDEX IF NOT EXISTS `idx_session_event_type` (`session_id`, `event_type`);
ALTER TABLE `lupo_session_events` ADD INDEX IF NOT EXISTS `idx_session_id` (`session_id`);
ALTER TABLE `lupo_session_events` ADD INDEX IF NOT EXISTS `idx_tab_id` (`tab_id`);
ALTER TABLE `lupo_session_events` ADD INDEX IF NOT EXISTS `idx_world_id` (`world_id`);

CREATE TABLE IF NOT EXISTS `lupo_sessions` (
  `session_id` varchar(100) NOT NULL COMMENT 'Unique session identifier (primary key)',
  `federation_node_id` bigint NOT NULL DEFAULT 1 COMMENT 'Domain/tenant identifier for multi-tenant support',
  `actor_id` bigint NOT NULL DEFAULT 0 COMMENT 'Actor ID (0 for anonymous users)',
  `ip_address` varchar(45) NOT NULL DEFAULT '' COMMENT 'IP address of the client (supports IPv6)',
  `user_agent` varchar(255) NOT NULL DEFAULT '' COMMENT 'User agent string from the client browser',
  `device_id` varchar(100) COMMENT 'Unique device identifier (if available)',
  `device_type` enum('desktop','mobile','tablet','bot','other') COMMENT 'Type of device used for the session',
  `auth_method` varchar(30) COMMENT 'Authentication method used (e.g., password, oauth, api_key)',
  `auth_provider` varchar(50) COMMENT 'Authentication provider (e.g., local, google, github)',
  `security_level` enum('low','medium','high') NOT NULL DEFAULT 'medium' COMMENT 'Security level of the session',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Whether the session is currently active',
  `is_expired` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Whether the session has expired',
  `is_revoked` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Whether the session was manually revoked',
  `session_data` longtext COMMENT 'Serialized session data (encrypted if sensitive)',
  `metadata` json COMMENT 'Additional session metadata in JSON format',
  `login_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when the session was authenticated',
  `last_seen_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS of last activity',
  `expires_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when the session expires',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when session was created',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when session was last updated',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag: 0=active, 1=deleted',
  `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when session was soft-deleted',
  PRIMARY KEY (`session_id`),
  KEY `idx_actor` (`actor_id`),
  KEY `idx_cleanup` (`is_deleted`, `last_seen_ymdhis`),
  KEY `idx_created` (`created_ymdhis`),
  KEY `idx_device` (`device_id`),
  KEY `idx_domain` (`federation_node_id`),
  KEY `idx_expires` (`expires_ymdhis`),
  KEY `idx_last_seen` (`last_seen_ymdhis`),
  KEY `idx_security` (`security_level`),
  KEY `idx_status` (`is_active`, `is_expired`, `is_revoked`)
);

ALTER TABLE `lupo_sessions` ADD COLUMN IF NOT EXISTS `session_id` varchar(100) NOT NULL COMMENT 'Unique session identifier (primary key)';
ALTER TABLE `lupo_sessions` ADD COLUMN IF NOT EXISTS `federation_node_id` bigint NOT NULL DEFAULT 1 COMMENT 'Domain/tenant identifier for multi-tenant support';
ALTER TABLE `lupo_sessions` ADD COLUMN IF NOT EXISTS `actor_id` bigint NOT NULL DEFAULT 0 COMMENT 'Actor ID (0 for anonymous users)';
ALTER TABLE `lupo_sessions` ADD COLUMN IF NOT EXISTS `ip_address` varchar(45) NOT NULL DEFAULT '' COMMENT 'IP address of the client (supports IPv6)';
ALTER TABLE `lupo_sessions` ADD COLUMN IF NOT EXISTS `user_agent` varchar(255) NOT NULL DEFAULT '' COMMENT 'User agent string from the client browser';
ALTER TABLE `lupo_sessions` ADD COLUMN IF NOT EXISTS `device_id` varchar(100) COMMENT 'Unique device identifier (if available)';
ALTER TABLE `lupo_sessions` ADD COLUMN IF NOT EXISTS `device_type` enum('desktop','mobile','tablet','bot','other') COMMENT 'Type of device used for the session';
ALTER TABLE `lupo_sessions` ADD COLUMN IF NOT EXISTS `auth_method` varchar(30) COMMENT 'Authentication method used (e.g., password, oauth, api_key)';
ALTER TABLE `lupo_sessions` ADD COLUMN IF NOT EXISTS `auth_provider` varchar(50) COMMENT 'Authentication provider (e.g., local, google, github)';
ALTER TABLE `lupo_sessions` ADD COLUMN IF NOT EXISTS `security_level` enum('low','medium','high') NOT NULL DEFAULT 'medium' COMMENT 'Security level of the session';
ALTER TABLE `lupo_sessions` ADD COLUMN IF NOT EXISTS `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Whether the session is currently active';
ALTER TABLE `lupo_sessions` ADD COLUMN IF NOT EXISTS `is_expired` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Whether the session has expired';
ALTER TABLE `lupo_sessions` ADD COLUMN IF NOT EXISTS `is_revoked` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Whether the session was manually revoked';
ALTER TABLE `lupo_sessions` ADD COLUMN IF NOT EXISTS `session_data` longtext COMMENT 'Serialized session data (encrypted if sensitive)';
ALTER TABLE `lupo_sessions` ADD COLUMN IF NOT EXISTS `metadata` json COMMENT 'Additional session metadata in JSON format';
ALTER TABLE `lupo_sessions` ADD COLUMN IF NOT EXISTS `login_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when the session was authenticated';
ALTER TABLE `lupo_sessions` ADD COLUMN IF NOT EXISTS `last_seen_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS of last activity';
ALTER TABLE `lupo_sessions` ADD COLUMN IF NOT EXISTS `expires_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when the session expires';
ALTER TABLE `lupo_sessions` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when session was created';
ALTER TABLE `lupo_sessions` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when session was last updated';
ALTER TABLE `lupo_sessions` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag: 0=active, 1=deleted';
ALTER TABLE `lupo_sessions` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when session was soft-deleted';
ALTER TABLE `lupo_sessions` MODIFY COLUMN `session_id` varchar(100) NOT NULL COMMENT 'Unique session identifier (primary key)';
ALTER TABLE `lupo_sessions` MODIFY COLUMN `federation_node_id` bigint NOT NULL DEFAULT 1 COMMENT 'Domain/tenant identifier for multi-tenant support';
ALTER TABLE `lupo_sessions` MODIFY COLUMN `actor_id` bigint NOT NULL DEFAULT 0 COMMENT 'Actor ID (0 for anonymous users)';
ALTER TABLE `lupo_sessions` MODIFY COLUMN `ip_address` varchar(45) NOT NULL DEFAULT '' COMMENT 'IP address of the client (supports IPv6)';
ALTER TABLE `lupo_sessions` MODIFY COLUMN `user_agent` varchar(255) NOT NULL DEFAULT '' COMMENT 'User agent string from the client browser';
ALTER TABLE `lupo_sessions` MODIFY COLUMN `device_id` varchar(100) COMMENT 'Unique device identifier (if available)';
ALTER TABLE `lupo_sessions` MODIFY COLUMN `device_type` enum('desktop','mobile','tablet','bot','other') COMMENT 'Type of device used for the session';
ALTER TABLE `lupo_sessions` MODIFY COLUMN `auth_method` varchar(30) COMMENT 'Authentication method used (e.g., password, oauth, api_key)';
ALTER TABLE `lupo_sessions` MODIFY COLUMN `auth_provider` varchar(50) COMMENT 'Authentication provider (e.g., local, google, github)';
ALTER TABLE `lupo_sessions` MODIFY COLUMN `security_level` enum('low','medium','high') NOT NULL DEFAULT 'medium' COMMENT 'Security level of the session';
ALTER TABLE `lupo_sessions` MODIFY COLUMN `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Whether the session is currently active';
ALTER TABLE `lupo_sessions` MODIFY COLUMN `is_expired` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Whether the session has expired';
ALTER TABLE `lupo_sessions` MODIFY COLUMN `is_revoked` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Whether the session was manually revoked';
ALTER TABLE `lupo_sessions` MODIFY COLUMN `session_data` longtext COMMENT 'Serialized session data (encrypted if sensitive)';
ALTER TABLE `lupo_sessions` MODIFY COLUMN `metadata` json COMMENT 'Additional session metadata in JSON format';
ALTER TABLE `lupo_sessions` MODIFY COLUMN `login_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when the session was authenticated';
ALTER TABLE `lupo_sessions` MODIFY COLUMN `last_seen_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS of last activity';
ALTER TABLE `lupo_sessions` MODIFY COLUMN `expires_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when the session expires';
ALTER TABLE `lupo_sessions` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when session was created';
ALTER TABLE `lupo_sessions` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when session was last updated';
ALTER TABLE `lupo_sessions` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag: 0=active, 1=deleted';
ALTER TABLE `lupo_sessions` MODIFY COLUMN `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when session was soft-deleted';
ALTER TABLE `lupo_sessions` ADD PRIMARY KEY (`session_id`);
ALTER TABLE `lupo_sessions` ADD INDEX IF NOT EXISTS `idx_actor` (`actor_id`);
ALTER TABLE `lupo_sessions` ADD INDEX IF NOT EXISTS `idx_cleanup` (`is_deleted`, `last_seen_ymdhis`);
ALTER TABLE `lupo_sessions` ADD INDEX IF NOT EXISTS `idx_created` (`created_ymdhis`);
ALTER TABLE `lupo_sessions` ADD INDEX IF NOT EXISTS `idx_device` (`device_id`);
ALTER TABLE `lupo_sessions` ADD INDEX IF NOT EXISTS `idx_domain` (`federation_node_id`);
ALTER TABLE `lupo_sessions` ADD INDEX IF NOT EXISTS `idx_expires` (`expires_ymdhis`);
ALTER TABLE `lupo_sessions` ADD INDEX IF NOT EXISTS `idx_last_seen` (`last_seen_ymdhis`);
ALTER TABLE `lupo_sessions` ADD INDEX IF NOT EXISTS `idx_security` (`security_level`);
ALTER TABLE `lupo_sessions` ADD INDEX IF NOT EXISTS `idx_status` (`is_active`, `is_expired`, `is_revoked`);

CREATE TABLE IF NOT EXISTS `lupo_system_config` (
  `system_config_id` bigint NOT NULL auto_increment,
  `config_key` varchar(255) NOT NULL,
  `config_value` text NOT NULL,
  `actor_id` bigint NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  PRIMARY KEY (`system_config_id`),
  UNIQUE KEY `config_key` (`config_key`)
);

ALTER TABLE `lupo_system_config` ADD COLUMN IF NOT EXISTS `system_config_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_system_config` ADD COLUMN IF NOT EXISTS `config_key` varchar(255) NOT NULL;
ALTER TABLE `lupo_system_config` ADD COLUMN IF NOT EXISTS `config_value` text NOT NULL;
ALTER TABLE `lupo_system_config` ADD COLUMN IF NOT EXISTS `actor_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_system_config` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_system_config` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_system_config` MODIFY COLUMN `system_config_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_system_config` MODIFY COLUMN `config_key` varchar(255) NOT NULL;
ALTER TABLE `lupo_system_config` MODIFY COLUMN `config_value` text NOT NULL;
ALTER TABLE `lupo_system_config` MODIFY COLUMN `actor_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_system_config` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_system_config` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_system_config` ADD PRIMARY KEY (`system_config_id`);
ALTER TABLE `lupo_system_config` ADD UNIQUE INDEX IF NOT EXISTS `config_key` (`config_key`);

CREATE TABLE IF NOT EXISTS `lupo_system_events` (
  `system_event_id` bigint NOT NULL auto_increment,
  `event_type` varchar(100) NOT NULL,
  `event_message` text NOT NULL,
  `event_context` text,
  `actor_id` bigint NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL,
  PRIMARY KEY (`system_event_id`),
  KEY `actor_id` (`actor_id`),
  KEY `event_type` (`event_type`)
);

ALTER TABLE `lupo_system_events` ADD COLUMN IF NOT EXISTS `system_event_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_system_events` ADD COLUMN IF NOT EXISTS `event_type` varchar(100) NOT NULL;
ALTER TABLE `lupo_system_events` ADD COLUMN IF NOT EXISTS `event_message` text NOT NULL;
ALTER TABLE `lupo_system_events` ADD COLUMN IF NOT EXISTS `event_context` text;
ALTER TABLE `lupo_system_events` ADD COLUMN IF NOT EXISTS `actor_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_system_events` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_system_events` MODIFY COLUMN `system_event_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_system_events` MODIFY COLUMN `event_type` varchar(100) NOT NULL;
ALTER TABLE `lupo_system_events` MODIFY COLUMN `event_message` text NOT NULL;
ALTER TABLE `lupo_system_events` MODIFY COLUMN `event_context` text;
ALTER TABLE `lupo_system_events` MODIFY COLUMN `actor_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_system_events` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_system_events` ADD PRIMARY KEY (`system_event_id`);
ALTER TABLE `lupo_system_events` ADD INDEX IF NOT EXISTS `actor_id` (`actor_id`);
ALTER TABLE `lupo_system_events` ADD INDEX IF NOT EXISTS `event_type` (`event_type`);

CREATE TABLE IF NOT EXISTS `lupo_system_health_snapshots` (
  `health_id` bigint NOT NULL auto_increment,
  `table_count` int NOT NULL,
  `table_ceiling` int NOT NULL,
  `schema_state` varchar(64) NOT NULL DEFAULT 'unknown' COMMENT 'frozen|active|migrating',
  `sync_integrity` varchar(32) NOT NULL DEFAULT 'unknown' COMMENT 'clean|drift|unknown',
  `emotional_r` decimal(3,2) COMMENT 'strife -1..1',
  `emotional_g` decimal(3,2) COMMENT 'harmony -1..1',
  `emotional_b` decimal(3,2) COMMENT 'memory -1..1',
  `emotional_t` decimal(3,2) COMMENT 'temporal -1..1',
  `created_ymdhis` bigint NOT NULL COMMENT 'YYYYMMDDHHMMSS',
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (`health_id`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_table_count` (`table_count`)
);

ALTER TABLE `lupo_system_health_snapshots` ADD COLUMN IF NOT EXISTS `health_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_system_health_snapshots` ADD COLUMN IF NOT EXISTS `table_count` int NOT NULL;
ALTER TABLE `lupo_system_health_snapshots` ADD COLUMN IF NOT EXISTS `table_ceiling` int NOT NULL;
ALTER TABLE `lupo_system_health_snapshots` ADD COLUMN IF NOT EXISTS `schema_state` varchar(64) NOT NULL DEFAULT 'unknown' COMMENT 'frozen|active|migrating';
ALTER TABLE `lupo_system_health_snapshots` ADD COLUMN IF NOT EXISTS `sync_integrity` varchar(32) NOT NULL DEFAULT 'unknown' COMMENT 'clean|drift|unknown';
ALTER TABLE `lupo_system_health_snapshots` ADD COLUMN IF NOT EXISTS `emotional_r` decimal(3,2) COMMENT 'strife -1..1';
ALTER TABLE `lupo_system_health_snapshots` ADD COLUMN IF NOT EXISTS `emotional_g` decimal(3,2) COMMENT 'harmony -1..1';
ALTER TABLE `lupo_system_health_snapshots` ADD COLUMN IF NOT EXISTS `emotional_b` decimal(3,2) COMMENT 'memory -1..1';
ALTER TABLE `lupo_system_health_snapshots` ADD COLUMN IF NOT EXISTS `emotional_t` decimal(3,2) COMMENT 'temporal -1..1';
ALTER TABLE `lupo_system_health_snapshots` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'YYYYMMDDHHMMSS';
ALTER TABLE `lupo_system_health_snapshots` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_system_health_snapshots` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint;
ALTER TABLE `lupo_system_health_snapshots` MODIFY COLUMN `health_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_system_health_snapshots` MODIFY COLUMN `table_count` int NOT NULL;
ALTER TABLE `lupo_system_health_snapshots` MODIFY COLUMN `table_ceiling` int NOT NULL;
ALTER TABLE `lupo_system_health_snapshots` MODIFY COLUMN `schema_state` varchar(64) NOT NULL DEFAULT 'unknown' COMMENT 'frozen|active|migrating';
ALTER TABLE `lupo_system_health_snapshots` MODIFY COLUMN `sync_integrity` varchar(32) NOT NULL DEFAULT 'unknown' COMMENT 'clean|drift|unknown';
ALTER TABLE `lupo_system_health_snapshots` MODIFY COLUMN `emotional_r` decimal(3,2) COMMENT 'strife -1..1';
ALTER TABLE `lupo_system_health_snapshots` MODIFY COLUMN `emotional_g` decimal(3,2) COMMENT 'harmony -1..1';
ALTER TABLE `lupo_system_health_snapshots` MODIFY COLUMN `emotional_b` decimal(3,2) COMMENT 'memory -1..1';
ALTER TABLE `lupo_system_health_snapshots` MODIFY COLUMN `emotional_t` decimal(3,2) COMMENT 'temporal -1..1';
ALTER TABLE `lupo_system_health_snapshots` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'YYYYMMDDHHMMSS';
ALTER TABLE `lupo_system_health_snapshots` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_system_health_snapshots` MODIFY COLUMN `deleted_ymdhis` bigint;
ALTER TABLE `lupo_system_health_snapshots` ADD PRIMARY KEY (`health_id`);
ALTER TABLE `lupo_system_health_snapshots` ADD INDEX IF NOT EXISTS `idx_created_ymdhis` (`created_ymdhis`);
ALTER TABLE `lupo_system_health_snapshots` ADD INDEX IF NOT EXISTS `idx_is_deleted` (`is_deleted`);
ALTER TABLE `lupo_system_health_snapshots` ADD INDEX IF NOT EXISTS `idx_table_count` (`table_count`);

CREATE TABLE IF NOT EXISTS `lupo_system_logs` (
  `log_id` bigint NOT NULL auto_increment,
  `event_type` varchar(64) NOT NULL COMMENT 'system|agent|error|security|migration|doctrine|heartbeat|temporal',
  `severity` varchar(16) NOT NULL DEFAULT 'info' COMMENT 'info|warning|error|critical',
  `actor_slug` varchar(64) COMMENT 'SYSTEM|LILITH|CURSOR|CASCADE|CAPTAIN_WOLFIE|etc',
  `message` text NOT NULL COMMENT 'Human-readable event description',
  `context_json` json COMMENT 'Optional structured metadata',
  `created_ymdhis` bigint NOT NULL COMMENT 'YYYYMMDDHHMMSS',
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  `recursion_depth` tinyint DEFAULT 1,
  `observation_latency_ms` int,
  `temporal_anomaly_score` decimal(3,2),
  PRIMARY KEY (`log_id`),
  KEY `idx_actor_slug` (`actor_slug`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_event_type` (`event_type`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_severity` (`severity`)
);

ALTER TABLE `lupo_system_logs` ADD COLUMN IF NOT EXISTS `log_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_system_logs` ADD COLUMN IF NOT EXISTS `event_type` varchar(64) NOT NULL COMMENT 'system|agent|error|security|migration|doctrine|heartbeat|temporal';
ALTER TABLE `lupo_system_logs` ADD COLUMN IF NOT EXISTS `severity` varchar(16) NOT NULL DEFAULT 'info' COMMENT 'info|warning|error|critical';
ALTER TABLE `lupo_system_logs` ADD COLUMN IF NOT EXISTS `actor_slug` varchar(64) COMMENT 'SYSTEM|LILITH|CURSOR|CASCADE|CAPTAIN_WOLFIE|etc';
ALTER TABLE `lupo_system_logs` ADD COLUMN IF NOT EXISTS `message` text NOT NULL COMMENT 'Human-readable event description';
ALTER TABLE `lupo_system_logs` ADD COLUMN IF NOT EXISTS `context_json` json COMMENT 'Optional structured metadata';
ALTER TABLE `lupo_system_logs` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'YYYYMMDDHHMMSS';
ALTER TABLE `lupo_system_logs` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_system_logs` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint;
ALTER TABLE `lupo_system_logs` ADD COLUMN IF NOT EXISTS `recursion_depth` tinyint DEFAULT 1;
ALTER TABLE `lupo_system_logs` ADD COLUMN IF NOT EXISTS `observation_latency_ms` int;
ALTER TABLE `lupo_system_logs` ADD COLUMN IF NOT EXISTS `temporal_anomaly_score` decimal(3,2);
ALTER TABLE `lupo_system_logs` MODIFY COLUMN `log_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_system_logs` MODIFY COLUMN `event_type` varchar(64) NOT NULL COMMENT 'system|agent|error|security|migration|doctrine|heartbeat|temporal';
ALTER TABLE `lupo_system_logs` MODIFY COLUMN `severity` varchar(16) NOT NULL DEFAULT 'info' COMMENT 'info|warning|error|critical';
ALTER TABLE `lupo_system_logs` MODIFY COLUMN `actor_slug` varchar(64) COMMENT 'SYSTEM|LILITH|CURSOR|CASCADE|CAPTAIN_WOLFIE|etc';
ALTER TABLE `lupo_system_logs` MODIFY COLUMN `message` text NOT NULL COMMENT 'Human-readable event description';
ALTER TABLE `lupo_system_logs` MODIFY COLUMN `context_json` json COMMENT 'Optional structured metadata';
ALTER TABLE `lupo_system_logs` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'YYYYMMDDHHMMSS';
ALTER TABLE `lupo_system_logs` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_system_logs` MODIFY COLUMN `deleted_ymdhis` bigint;
ALTER TABLE `lupo_system_logs` MODIFY COLUMN `recursion_depth` tinyint DEFAULT 1;
ALTER TABLE `lupo_system_logs` MODIFY COLUMN `observation_latency_ms` int;
ALTER TABLE `lupo_system_logs` MODIFY COLUMN `temporal_anomaly_score` decimal(3,2);
ALTER TABLE `lupo_system_logs` ADD PRIMARY KEY (`log_id`);
ALTER TABLE `lupo_system_logs` ADD INDEX IF NOT EXISTS `idx_actor_slug` (`actor_slug`);
ALTER TABLE `lupo_system_logs` ADD INDEX IF NOT EXISTS `idx_created_ymdhis` (`created_ymdhis`);
ALTER TABLE `lupo_system_logs` ADD INDEX IF NOT EXISTS `idx_event_type` (`event_type`);
ALTER TABLE `lupo_system_logs` ADD INDEX IF NOT EXISTS `idx_is_deleted` (`is_deleted`);
ALTER TABLE `lupo_system_logs` ADD INDEX IF NOT EXISTS `idx_severity` (`severity`);

CREATE TABLE IF NOT EXISTS `lupo_tab_events` (
  `tab_event_id` bigint NOT NULL auto_increment COMMENT 'Primary key for tab event',
  `tab_id` varchar(255) NOT NULL COMMENT 'Tab identifier',
  `session_id` varchar(255) COMMENT 'Session identifier',
  `actor_id` bigint COMMENT 'Actor ID from lupo_actors',
  `world_id` bigint COMMENT 'World context ID',
  `world_key` varchar(255) COMMENT 'World context key',
  `world_type` varchar(50) COMMENT 'World context type',
  `event_type` varchar(100) NOT NULL COMMENT 'Type of tab event',
  `event_data` json COMMENT 'Event-specific data',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS',
  PRIMARY KEY (`tab_event_id`),
  KEY `idx_actor_id` (`actor_id`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_event_type` (`event_type`),
  KEY `idx_session_id` (`session_id`),
  KEY `idx_tab_event_type` (`tab_id`, `event_type`),
  KEY `idx_tab_id` (`tab_id`),
  KEY `idx_world_id` (`world_id`)
);

ALTER TABLE `lupo_tab_events` ADD COLUMN IF NOT EXISTS `tab_event_id` bigint NOT NULL auto_increment COMMENT 'Primary key for tab event';
ALTER TABLE `lupo_tab_events` ADD COLUMN IF NOT EXISTS `tab_id` varchar(255) NOT NULL COMMENT 'Tab identifier';
ALTER TABLE `lupo_tab_events` ADD COLUMN IF NOT EXISTS `session_id` varchar(255) COMMENT 'Session identifier';
ALTER TABLE `lupo_tab_events` ADD COLUMN IF NOT EXISTS `actor_id` bigint COMMENT 'Actor ID from lupo_actors';
ALTER TABLE `lupo_tab_events` ADD COLUMN IF NOT EXISTS `world_id` bigint COMMENT 'World context ID';
ALTER TABLE `lupo_tab_events` ADD COLUMN IF NOT EXISTS `world_key` varchar(255) COMMENT 'World context key';
ALTER TABLE `lupo_tab_events` ADD COLUMN IF NOT EXISTS `world_type` varchar(50) COMMENT 'World context type';
ALTER TABLE `lupo_tab_events` ADD COLUMN IF NOT EXISTS `event_type` varchar(100) NOT NULL COMMENT 'Type of tab event';
ALTER TABLE `lupo_tab_events` ADD COLUMN IF NOT EXISTS `event_data` json COMMENT 'Event-specific data';
ALTER TABLE `lupo_tab_events` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS';
ALTER TABLE `lupo_tab_events` MODIFY COLUMN `tab_event_id` bigint NOT NULL auto_increment COMMENT 'Primary key for tab event';
ALTER TABLE `lupo_tab_events` MODIFY COLUMN `tab_id` varchar(255) NOT NULL COMMENT 'Tab identifier';
ALTER TABLE `lupo_tab_events` MODIFY COLUMN `session_id` varchar(255) COMMENT 'Session identifier';
ALTER TABLE `lupo_tab_events` MODIFY COLUMN `actor_id` bigint COMMENT 'Actor ID from lupo_actors';
ALTER TABLE `lupo_tab_events` MODIFY COLUMN `world_id` bigint COMMENT 'World context ID';
ALTER TABLE `lupo_tab_events` MODIFY COLUMN `world_key` varchar(255) COMMENT 'World context key';
ALTER TABLE `lupo_tab_events` MODIFY COLUMN `world_type` varchar(50) COMMENT 'World context type';
ALTER TABLE `lupo_tab_events` MODIFY COLUMN `event_type` varchar(100) NOT NULL COMMENT 'Type of tab event';
ALTER TABLE `lupo_tab_events` MODIFY COLUMN `event_data` json COMMENT 'Event-specific data';
ALTER TABLE `lupo_tab_events` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS';
ALTER TABLE `lupo_tab_events` ADD PRIMARY KEY (`tab_event_id`);
ALTER TABLE `lupo_tab_events` ADD INDEX IF NOT EXISTS `idx_actor_id` (`actor_id`);
ALTER TABLE `lupo_tab_events` ADD INDEX IF NOT EXISTS `idx_created_ymdhis` (`created_ymdhis`);
ALTER TABLE `lupo_tab_events` ADD INDEX IF NOT EXISTS `idx_event_type` (`event_type`);
ALTER TABLE `lupo_tab_events` ADD INDEX IF NOT EXISTS `idx_session_id` (`session_id`);
ALTER TABLE `lupo_tab_events` ADD INDEX IF NOT EXISTS `idx_tab_event_type` (`tab_id`, `event_type`);
ALTER TABLE `lupo_tab_events` ADD INDEX IF NOT EXISTS `idx_tab_id` (`tab_id`);
ALTER TABLE `lupo_tab_events` ADD INDEX IF NOT EXISTS `idx_world_id` (`world_id`);

CREATE TABLE IF NOT EXISTS `lupo_temporal_coherence_snapshots` (
  `snapshot_id` bigint NOT NULL auto_increment,
  `utc_anchor` bigint NOT NULL COMMENT 'YYYYMMDDHHMMSS of anchor',
  `observation_latency_ms` int NOT NULL DEFAULT 0,
  `recursion_depth` tinyint NOT NULL DEFAULT 0 COMMENT '1=action, 2=observation, 3=meta; max 3',
  `self_awareness_score` decimal(3,2) COMMENT '0-1 scale',
  `timestamp_integrity` varchar(32) NOT NULL DEFAULT 'unknown' COMMENT 'monotonic|gaps|anomalies',
  `created_ymdhis` bigint NOT NULL COMMENT 'YYYYMMDDHHMMSS',
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (`snapshot_id`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_utc_anchor` (`utc_anchor`)
);

ALTER TABLE `lupo_temporal_coherence_snapshots` ADD COLUMN IF NOT EXISTS `snapshot_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_temporal_coherence_snapshots` ADD COLUMN IF NOT EXISTS `utc_anchor` bigint NOT NULL COMMENT 'YYYYMMDDHHMMSS of anchor';
ALTER TABLE `lupo_temporal_coherence_snapshots` ADD COLUMN IF NOT EXISTS `observation_latency_ms` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_temporal_coherence_snapshots` ADD COLUMN IF NOT EXISTS `recursion_depth` tinyint NOT NULL DEFAULT 0 COMMENT '1=action, 2=observation, 3=meta; max 3';
ALTER TABLE `lupo_temporal_coherence_snapshots` ADD COLUMN IF NOT EXISTS `self_awareness_score` decimal(3,2) COMMENT '0-1 scale';
ALTER TABLE `lupo_temporal_coherence_snapshots` ADD COLUMN IF NOT EXISTS `timestamp_integrity` varchar(32) NOT NULL DEFAULT 'unknown' COMMENT 'monotonic|gaps|anomalies';
ALTER TABLE `lupo_temporal_coherence_snapshots` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'YYYYMMDDHHMMSS';
ALTER TABLE `lupo_temporal_coherence_snapshots` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_temporal_coherence_snapshots` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint;
ALTER TABLE `lupo_temporal_coherence_snapshots` MODIFY COLUMN `snapshot_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_temporal_coherence_snapshots` MODIFY COLUMN `utc_anchor` bigint NOT NULL COMMENT 'YYYYMMDDHHMMSS of anchor';
ALTER TABLE `lupo_temporal_coherence_snapshots` MODIFY COLUMN `observation_latency_ms` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_temporal_coherence_snapshots` MODIFY COLUMN `recursion_depth` tinyint NOT NULL DEFAULT 0 COMMENT '1=action, 2=observation, 3=meta; max 3';
ALTER TABLE `lupo_temporal_coherence_snapshots` MODIFY COLUMN `self_awareness_score` decimal(3,2) COMMENT '0-1 scale';
ALTER TABLE `lupo_temporal_coherence_snapshots` MODIFY COLUMN `timestamp_integrity` varchar(32) NOT NULL DEFAULT 'unknown' COMMENT 'monotonic|gaps|anomalies';
ALTER TABLE `lupo_temporal_coherence_snapshots` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'YYYYMMDDHHMMSS';
ALTER TABLE `lupo_temporal_coherence_snapshots` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_temporal_coherence_snapshots` MODIFY COLUMN `deleted_ymdhis` bigint;
ALTER TABLE `lupo_temporal_coherence_snapshots` ADD PRIMARY KEY (`snapshot_id`);
ALTER TABLE `lupo_temporal_coherence_snapshots` ADD INDEX IF NOT EXISTS `idx_created_ymdhis` (`created_ymdhis`);
ALTER TABLE `lupo_temporal_coherence_snapshots` ADD INDEX IF NOT EXISTS `idx_is_deleted` (`is_deleted`);
ALTER TABLE `lupo_temporal_coherence_snapshots` ADD INDEX IF NOT EXISTS `idx_utc_anchor` (`utc_anchor`);

CREATE TABLE IF NOT EXISTS `lupo_tldnr` (
  `tldnr_id` bigint NOT NULL auto_increment COMMENT 'Primary key for TL;DR record',
  `slug` varchar(255) NOT NULL COMMENT 'URL-friendly unique identifier',
  `title` varchar(255) NOT NULL COMMENT 'TL;DR title (e.g., "Lupopedia Overview", "Collection Doctrine")',
  `content_text` longtext NOT NULL COMMENT 'TL;DR content (plain text or markdown)',
  `topic_type` varchar(100) COMMENT 'Type of topic (e.g., "system", "doctrine", "module", "concept")',
  `topic_reference` varchar(255) COMMENT 'Reference to what this summarizes (e.g., "Lupopedia", "Collection Doctrine", "LABS-001")',
  `system_version` varchar(20) COMMENT 'System version this TL;DR applies to (e.g., "4.1.6")',
  `category` varchar(100) COMMENT 'Category for grouping (e.g., "Core", "Doctrine", "Module")',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC creation bigint (YYYYMMDDHHIISS)',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC last update bigint (YYYYMMDDHHIISS)',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted, 0=active)',
  `deleted_ymdhis` bigint COMMENT 'UTC deletion bigint (YYYYMMDDHHIISS)',
  PRIMARY KEY (`tldnr_id`),
  KEY `idx_category` (`category`),
  KEY `idx_created` (`created_ymdhis`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_system_version` (`system_version`),
  KEY `idx_topic_reference` (`topic_reference`),
  KEY `idx_topic_type` (`topic_type`),
  UNIQUE KEY `uniq_slug` (`slug`)
);

ALTER TABLE `lupo_tldnr` ADD COLUMN IF NOT EXISTS `tldnr_id` bigint NOT NULL auto_increment COMMENT 'Primary key for TL;DR record';
ALTER TABLE `lupo_tldnr` ADD COLUMN IF NOT EXISTS `slug` varchar(255) NOT NULL COMMENT 'URL-friendly unique identifier';
ALTER TABLE `lupo_tldnr` ADD COLUMN IF NOT EXISTS `title` varchar(255) NOT NULL COMMENT 'TL;DR title (e.g., "Lupopedia Overview", "Collection Doctrine")';
ALTER TABLE `lupo_tldnr` ADD COLUMN IF NOT EXISTS `content_text` longtext NOT NULL COMMENT 'TL;DR content (plain text or markdown)';
ALTER TABLE `lupo_tldnr` ADD COLUMN IF NOT EXISTS `topic_type` varchar(100) COMMENT 'Type of topic (e.g., "system", "doctrine", "module", "concept")';
ALTER TABLE `lupo_tldnr` ADD COLUMN IF NOT EXISTS `topic_reference` varchar(255) COMMENT 'Reference to what this summarizes (e.g., "Lupopedia", "Collection Doctrine", "LABS-001")';
ALTER TABLE `lupo_tldnr` ADD COLUMN IF NOT EXISTS `system_version` varchar(20) COMMENT 'System version this TL;DR applies to (e.g., "4.1.6")';
ALTER TABLE `lupo_tldnr` ADD COLUMN IF NOT EXISTS `category` varchar(100) COMMENT 'Category for grouping (e.g., "Core", "Doctrine", "Module")';
ALTER TABLE `lupo_tldnr` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'UTC creation bigint (YYYYMMDDHHIISS)';
ALTER TABLE `lupo_tldnr` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL COMMENT 'UTC last update bigint (YYYYMMDDHHIISS)';
ALTER TABLE `lupo_tldnr` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted, 0=active)';
ALTER TABLE `lupo_tldnr` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint COMMENT 'UTC deletion bigint (YYYYMMDDHHIISS)';
ALTER TABLE `lupo_tldnr` MODIFY COLUMN `tldnr_id` bigint NOT NULL auto_increment COMMENT 'Primary key for TL;DR record';
ALTER TABLE `lupo_tldnr` MODIFY COLUMN `slug` varchar(255) NOT NULL COMMENT 'URL-friendly unique identifier';
ALTER TABLE `lupo_tldnr` MODIFY COLUMN `title` varchar(255) NOT NULL COMMENT 'TL;DR title (e.g., "Lupopedia Overview", "Collection Doctrine")';
ALTER TABLE `lupo_tldnr` MODIFY COLUMN `content_text` longtext NOT NULL COMMENT 'TL;DR content (plain text or markdown)';
ALTER TABLE `lupo_tldnr` MODIFY COLUMN `topic_type` varchar(100) COMMENT 'Type of topic (e.g., "system", "doctrine", "module", "concept")';
ALTER TABLE `lupo_tldnr` MODIFY COLUMN `topic_reference` varchar(255) COMMENT 'Reference to what this summarizes (e.g., "Lupopedia", "Collection Doctrine", "LABS-001")';
ALTER TABLE `lupo_tldnr` MODIFY COLUMN `system_version` varchar(20) COMMENT 'System version this TL;DR applies to (e.g., "4.1.6")';
ALTER TABLE `lupo_tldnr` MODIFY COLUMN `category` varchar(100) COMMENT 'Category for grouping (e.g., "Core", "Doctrine", "Module")';
ALTER TABLE `lupo_tldnr` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'UTC creation bigint (YYYYMMDDHHIISS)';
ALTER TABLE `lupo_tldnr` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL COMMENT 'UTC last update bigint (YYYYMMDDHHIISS)';
ALTER TABLE `lupo_tldnr` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted, 0=active)';
ALTER TABLE `lupo_tldnr` MODIFY COLUMN `deleted_ymdhis` bigint COMMENT 'UTC deletion bigint (YYYYMMDDHHIISS)';
ALTER TABLE `lupo_tldnr` ADD PRIMARY KEY (`tldnr_id`);
ALTER TABLE `lupo_tldnr` ADD INDEX IF NOT EXISTS `idx_category` (`category`);
ALTER TABLE `lupo_tldnr` ADD INDEX IF NOT EXISTS `idx_created` (`created_ymdhis`);
ALTER TABLE `lupo_tldnr` ADD INDEX IF NOT EXISTS `idx_is_deleted` (`is_deleted`);
ALTER TABLE `lupo_tldnr` ADD INDEX IF NOT EXISTS `idx_system_version` (`system_version`);
ALTER TABLE `lupo_tldnr` ADD INDEX IF NOT EXISTS `idx_topic_reference` (`topic_reference`);
ALTER TABLE `lupo_tldnr` ADD INDEX IF NOT EXISTS `idx_topic_type` (`topic_type`);
ALTER TABLE `lupo_tldnr` ADD UNIQUE INDEX IF NOT EXISTS `uniq_slug` (`slug`);

CREATE TABLE IF NOT EXISTS `lupo_truth_evidence` (
  `truth_evidence_id` bigint NOT NULL auto_increment,
  `truth_answer_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `evidence_text` text NOT NULL,
  `evidence_type` varchar(50) NOT NULL DEFAULT '',
  `weight_score` decimal(5,2) NOT NULL DEFAULT 0.00,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (`truth_evidence_id`),
  KEY `actor_id` (`actor_id`),
  KEY `truth_answer_id` (`truth_answer_id`)
);

ALTER TABLE `lupo_truth_evidence` ADD COLUMN IF NOT EXISTS `truth_evidence_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_truth_evidence` ADD COLUMN IF NOT EXISTS `truth_answer_id` bigint NOT NULL;
ALTER TABLE `lupo_truth_evidence` ADD COLUMN IF NOT EXISTS `actor_id` bigint NOT NULL;
ALTER TABLE `lupo_truth_evidence` ADD COLUMN IF NOT EXISTS `evidence_text` text NOT NULL;
ALTER TABLE `lupo_truth_evidence` ADD COLUMN IF NOT EXISTS `evidence_type` varchar(50) NOT NULL DEFAULT '';
ALTER TABLE `lupo_truth_evidence` ADD COLUMN IF NOT EXISTS `weight_score` decimal(5,2) NOT NULL DEFAULT 0.00;
ALTER TABLE `lupo_truth_evidence` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_truth_evidence` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_truth_evidence` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_truth_evidence` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint;
ALTER TABLE `lupo_truth_evidence` MODIFY COLUMN `truth_evidence_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_truth_evidence` MODIFY COLUMN `truth_answer_id` bigint NOT NULL;
ALTER TABLE `lupo_truth_evidence` MODIFY COLUMN `actor_id` bigint NOT NULL;
ALTER TABLE `lupo_truth_evidence` MODIFY COLUMN `evidence_text` text NOT NULL;
ALTER TABLE `lupo_truth_evidence` MODIFY COLUMN `evidence_type` varchar(50) NOT NULL DEFAULT '';
ALTER TABLE `lupo_truth_evidence` MODIFY COLUMN `weight_score` decimal(5,2) NOT NULL DEFAULT 0.00;
ALTER TABLE `lupo_truth_evidence` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_truth_evidence` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_truth_evidence` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_truth_evidence` MODIFY COLUMN `deleted_ymdhis` bigint;
ALTER TABLE `lupo_truth_evidence` ADD PRIMARY KEY (`truth_evidence_id`);
ALTER TABLE `lupo_truth_evidence` ADD INDEX IF NOT EXISTS `actor_id` (`actor_id`);
ALTER TABLE `lupo_truth_evidence` ADD INDEX IF NOT EXISTS `truth_answer_id` (`truth_answer_id`);

CREATE TABLE IF NOT EXISTS `lupo_truth_questions_map` (
  `truth_questions_map_id` bigint NOT NULL auto_increment,
  `truth_question_id` bigint NOT NULL,
  `object_type` varchar(50) NOT NULL,
  `object_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (`truth_questions_map_id`),
  KEY `actor_id` (`actor_id`),
  KEY `object_id` (`object_id`),
  KEY `object_type` (`object_type`),
  KEY `truth_question_id` (`truth_question_id`)
);

ALTER TABLE `lupo_truth_questions_map` ADD COLUMN IF NOT EXISTS `truth_questions_map_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_truth_questions_map` ADD COLUMN IF NOT EXISTS `truth_question_id` bigint NOT NULL;
ALTER TABLE `lupo_truth_questions_map` ADD COLUMN IF NOT EXISTS `object_type` varchar(50) NOT NULL;
ALTER TABLE `lupo_truth_questions_map` ADD COLUMN IF NOT EXISTS `object_id` bigint NOT NULL;
ALTER TABLE `lupo_truth_questions_map` ADD COLUMN IF NOT EXISTS `actor_id` bigint NOT NULL;
ALTER TABLE `lupo_truth_questions_map` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_truth_questions_map` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_truth_questions_map` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_truth_questions_map` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint;
ALTER TABLE `lupo_truth_questions_map` MODIFY COLUMN `truth_questions_map_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_truth_questions_map` MODIFY COLUMN `truth_question_id` bigint NOT NULL;
ALTER TABLE `lupo_truth_questions_map` MODIFY COLUMN `object_type` varchar(50) NOT NULL;
ALTER TABLE `lupo_truth_questions_map` MODIFY COLUMN `object_id` bigint NOT NULL;
ALTER TABLE `lupo_truth_questions_map` MODIFY COLUMN `actor_id` bigint NOT NULL;
ALTER TABLE `lupo_truth_questions_map` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_truth_questions_map` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_truth_questions_map` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_truth_questions_map` MODIFY COLUMN `deleted_ymdhis` bigint;
ALTER TABLE `lupo_truth_questions_map` ADD PRIMARY KEY (`truth_questions_map_id`);
ALTER TABLE `lupo_truth_questions_map` ADD INDEX IF NOT EXISTS `actor_id` (`actor_id`);
ALTER TABLE `lupo_truth_questions_map` ADD INDEX IF NOT EXISTS `object_id` (`object_id`);
ALTER TABLE `lupo_truth_questions_map` ADD INDEX IF NOT EXISTS `object_type` (`object_type`);
ALTER TABLE `lupo_truth_questions_map` ADD INDEX IF NOT EXISTS `truth_question_id` (`truth_question_id`);

CREATE TABLE IF NOT EXISTS `lupo_truth_relations` (
  `truth_relation_id` bigint NOT NULL auto_increment,
  `left_object_type` varchar(50) NOT NULL,
  `left_object_id` bigint NOT NULL,
  `right_object_type` varchar(50) NOT NULL,
  `right_object_id` bigint NOT NULL,
  `relation_type` varchar(50) NOT NULL DEFAULT '',
  `actor_id` bigint NOT NULL,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (`truth_relation_id`),
  KEY `left_object_type` (`left_object_type`),
  KEY `relation_type` (`relation_type`),
  KEY `right_object_type` (`right_object_type`)
);

ALTER TABLE `lupo_truth_relations` ADD COLUMN IF NOT EXISTS `truth_relation_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_truth_relations` ADD COLUMN IF NOT EXISTS `left_object_type` varchar(50) NOT NULL;
ALTER TABLE `lupo_truth_relations` ADD COLUMN IF NOT EXISTS `left_object_id` bigint NOT NULL;
ALTER TABLE `lupo_truth_relations` ADD COLUMN IF NOT EXISTS `right_object_type` varchar(50) NOT NULL;
ALTER TABLE `lupo_truth_relations` ADD COLUMN IF NOT EXISTS `right_object_id` bigint NOT NULL;
ALTER TABLE `lupo_truth_relations` ADD COLUMN IF NOT EXISTS `relation_type` varchar(50) NOT NULL DEFAULT '';
ALTER TABLE `lupo_truth_relations` ADD COLUMN IF NOT EXISTS `actor_id` bigint NOT NULL;
ALTER TABLE `lupo_truth_relations` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_truth_relations` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_truth_relations` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_truth_relations` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint;
ALTER TABLE `lupo_truth_relations` MODIFY COLUMN `truth_relation_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_truth_relations` MODIFY COLUMN `left_object_type` varchar(50) NOT NULL;
ALTER TABLE `lupo_truth_relations` MODIFY COLUMN `left_object_id` bigint NOT NULL;
ALTER TABLE `lupo_truth_relations` MODIFY COLUMN `right_object_type` varchar(50) NOT NULL;
ALTER TABLE `lupo_truth_relations` MODIFY COLUMN `right_object_id` bigint NOT NULL;
ALTER TABLE `lupo_truth_relations` MODIFY COLUMN `relation_type` varchar(50) NOT NULL DEFAULT '';
ALTER TABLE `lupo_truth_relations` MODIFY COLUMN `actor_id` bigint NOT NULL;
ALTER TABLE `lupo_truth_relations` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_truth_relations` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_truth_relations` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_truth_relations` MODIFY COLUMN `deleted_ymdhis` bigint;
ALTER TABLE `lupo_truth_relations` ADD PRIMARY KEY (`truth_relation_id`);
ALTER TABLE `lupo_truth_relations` ADD INDEX IF NOT EXISTS `left_object_type` (`left_object_type`);
ALTER TABLE `lupo_truth_relations` ADD INDEX IF NOT EXISTS `relation_type` (`relation_type`);
ALTER TABLE `lupo_truth_relations` ADD INDEX IF NOT EXISTS `right_object_type` (`right_object_type`);

CREATE TABLE IF NOT EXISTS `lupo_truth_sources` (
  `truth_sourc_id` bigint NOT NULL auto_increment,
  `truth_evidence_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `source_url` text,
  `source_title` varchar(255) NOT NULL DEFAULT '',
  `source_type` varchar(50) NOT NULL DEFAULT '',
  `reliability_score` decimal(5,2) NOT NULL DEFAULT 0.00,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (`truth_sourc_id`),
  KEY `actor_id` (`actor_id`),
  KEY `truth_evidence_id` (`truth_evidence_id`)
);

ALTER TABLE `lupo_truth_sources` ADD COLUMN IF NOT EXISTS `truth_sourc_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_truth_sources` ADD COLUMN IF NOT EXISTS `truth_evidence_id` bigint NOT NULL;
ALTER TABLE `lupo_truth_sources` ADD COLUMN IF NOT EXISTS `actor_id` bigint NOT NULL;
ALTER TABLE `lupo_truth_sources` ADD COLUMN IF NOT EXISTS `source_url` text;
ALTER TABLE `lupo_truth_sources` ADD COLUMN IF NOT EXISTS `source_title` varchar(255) NOT NULL DEFAULT '';
ALTER TABLE `lupo_truth_sources` ADD COLUMN IF NOT EXISTS `source_type` varchar(50) NOT NULL DEFAULT '';
ALTER TABLE `lupo_truth_sources` ADD COLUMN IF NOT EXISTS `reliability_score` decimal(5,2) NOT NULL DEFAULT 0.00;
ALTER TABLE `lupo_truth_sources` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_truth_sources` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_truth_sources` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_truth_sources` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint;
ALTER TABLE `lupo_truth_sources` MODIFY COLUMN `truth_sourc_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_truth_sources` MODIFY COLUMN `truth_evidence_id` bigint NOT NULL;
ALTER TABLE `lupo_truth_sources` MODIFY COLUMN `actor_id` bigint NOT NULL;
ALTER TABLE `lupo_truth_sources` MODIFY COLUMN `source_url` text;
ALTER TABLE `lupo_truth_sources` MODIFY COLUMN `source_title` varchar(255) NOT NULL DEFAULT '';
ALTER TABLE `lupo_truth_sources` MODIFY COLUMN `source_type` varchar(50) NOT NULL DEFAULT '';
ALTER TABLE `lupo_truth_sources` MODIFY COLUMN `reliability_score` decimal(5,2) NOT NULL DEFAULT 0.00;
ALTER TABLE `lupo_truth_sources` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_truth_sources` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_truth_sources` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_truth_sources` MODIFY COLUMN `deleted_ymdhis` bigint;
ALTER TABLE `lupo_truth_sources` ADD PRIMARY KEY (`truth_sourc_id`);
ALTER TABLE `lupo_truth_sources` ADD INDEX IF NOT EXISTS `actor_id` (`actor_id`);
ALTER TABLE `lupo_truth_sources` ADD INDEX IF NOT EXISTS `truth_evidence_id` (`truth_evidence_id`);

CREATE TABLE IF NOT EXISTS `lupo_truth_topics` (
  `truth_topic_id` bigint NOT NULL auto_increment,
  `topic_name` varchar(255) NOT NULL DEFAULT '',
  `slug` varchar(255) NOT NULL DEFAULT '',
  `topic_description` text,
  `actor_id` bigint NOT NULL DEFAULT 0,
  `weight_score` decimal(5,2) NOT NULL DEFAULT 0.00,
  `importance_score` decimal(5,2) NOT NULL DEFAULT 0.00,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (`truth_topic_id`),
  KEY `actor_id` (`actor_id`),
  KEY `slug` (`slug`),
  KEY `topic_name` (`topic_name`)
);

ALTER TABLE `lupo_truth_topics` ADD COLUMN IF NOT EXISTS `truth_topic_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_truth_topics` ADD COLUMN IF NOT EXISTS `topic_name` varchar(255) NOT NULL DEFAULT '';
ALTER TABLE `lupo_truth_topics` ADD COLUMN IF NOT EXISTS `slug` varchar(255) NOT NULL DEFAULT '';
ALTER TABLE `lupo_truth_topics` ADD COLUMN IF NOT EXISTS `topic_description` text;
ALTER TABLE `lupo_truth_topics` ADD COLUMN IF NOT EXISTS `actor_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_truth_topics` ADD COLUMN IF NOT EXISTS `weight_score` decimal(5,2) NOT NULL DEFAULT 0.00;
ALTER TABLE `lupo_truth_topics` ADD COLUMN IF NOT EXISTS `importance_score` decimal(5,2) NOT NULL DEFAULT 0.00;
ALTER TABLE `lupo_truth_topics` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_truth_topics` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_truth_topics` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_truth_topics` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint;
ALTER TABLE `lupo_truth_topics` MODIFY COLUMN `truth_topic_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_truth_topics` MODIFY COLUMN `topic_name` varchar(255) NOT NULL DEFAULT '';
ALTER TABLE `lupo_truth_topics` MODIFY COLUMN `slug` varchar(255) NOT NULL DEFAULT '';
ALTER TABLE `lupo_truth_topics` MODIFY COLUMN `topic_description` text;
ALTER TABLE `lupo_truth_topics` MODIFY COLUMN `actor_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_truth_topics` MODIFY COLUMN `weight_score` decimal(5,2) NOT NULL DEFAULT 0.00;
ALTER TABLE `lupo_truth_topics` MODIFY COLUMN `importance_score` decimal(5,2) NOT NULL DEFAULT 0.00;
ALTER TABLE `lupo_truth_topics` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_truth_topics` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_truth_topics` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_truth_topics` MODIFY COLUMN `deleted_ymdhis` bigint;
ALTER TABLE `lupo_truth_topics` ADD PRIMARY KEY (`truth_topic_id`);
ALTER TABLE `lupo_truth_topics` ADD INDEX IF NOT EXISTS `actor_id` (`actor_id`);
ALTER TABLE `lupo_truth_topics` ADD INDEX IF NOT EXISTS `slug` (`slug`);
ALTER TABLE `lupo_truth_topics` ADD INDEX IF NOT EXISTS `topic_name` (`topic_name`);

CREATE TABLE IF NOT EXISTS `lupo_unified_analytics_paths` (
  `analytics_path_id` bigint NOT NULL,
  `period` enum('daily','monthly') NOT NULL,
  `visit_content_id` bigint,
  `exit_content_id` bigint,
  `metadata_json` json,
  `created_ymdhis` bigint,
  PRIMARY KEY (`analytics_path_id`)
);

ALTER TABLE `lupo_unified_analytics_paths` ADD COLUMN IF NOT EXISTS `analytics_path_id` bigint NOT NULL;
ALTER TABLE `lupo_unified_analytics_paths` ADD COLUMN IF NOT EXISTS `period` enum('daily','monthly') NOT NULL;
ALTER TABLE `lupo_unified_analytics_paths` ADD COLUMN IF NOT EXISTS `visit_content_id` bigint;
ALTER TABLE `lupo_unified_analytics_paths` ADD COLUMN IF NOT EXISTS `exit_content_id` bigint;
ALTER TABLE `lupo_unified_analytics_paths` ADD COLUMN IF NOT EXISTS `metadata_json` json;
ALTER TABLE `lupo_unified_analytics_paths` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint;
ALTER TABLE `lupo_unified_analytics_paths` MODIFY COLUMN `analytics_path_id` bigint NOT NULL;
ALTER TABLE `lupo_unified_analytics_paths` MODIFY COLUMN `period` enum('daily','monthly') NOT NULL;
ALTER TABLE `lupo_unified_analytics_paths` MODIFY COLUMN `visit_content_id` bigint;
ALTER TABLE `lupo_unified_analytics_paths` MODIFY COLUMN `exit_content_id` bigint;
ALTER TABLE `lupo_unified_analytics_paths` MODIFY COLUMN `metadata_json` json;
ALTER TABLE `lupo_unified_analytics_paths` MODIFY COLUMN `created_ymdhis` bigint;
ALTER TABLE `lupo_unified_analytics_paths` ADD PRIMARY KEY (`analytics_path_id`);

CREATE TABLE IF NOT EXISTS `lupo_unified_dialog_messages` (
  `dialog_message_id` bigint NOT NULL,
  `thread_id` bigint,
  `actor_id` bigint,
  `created_ymdhis` bigint,
  `updated_ymdhis` bigint,
  `metadata_json` json,
  `body_text` longtext,
  PRIMARY KEY (`dialog_message_id`)
);

ALTER TABLE `lupo_unified_dialog_messages` ADD COLUMN IF NOT EXISTS `dialog_message_id` bigint NOT NULL;
ALTER TABLE `lupo_unified_dialog_messages` ADD COLUMN IF NOT EXISTS `thread_id` bigint;
ALTER TABLE `lupo_unified_dialog_messages` ADD COLUMN IF NOT EXISTS `actor_id` bigint;
ALTER TABLE `lupo_unified_dialog_messages` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint;
ALTER TABLE `lupo_unified_dialog_messages` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint;
ALTER TABLE `lupo_unified_dialog_messages` ADD COLUMN IF NOT EXISTS `metadata_json` json;
ALTER TABLE `lupo_unified_dialog_messages` ADD COLUMN IF NOT EXISTS `body_text` longtext;
ALTER TABLE `lupo_unified_dialog_messages` MODIFY COLUMN `dialog_message_id` bigint NOT NULL;
ALTER TABLE `lupo_unified_dialog_messages` MODIFY COLUMN `thread_id` bigint;
ALTER TABLE `lupo_unified_dialog_messages` MODIFY COLUMN `actor_id` bigint;
ALTER TABLE `lupo_unified_dialog_messages` MODIFY COLUMN `created_ymdhis` bigint;
ALTER TABLE `lupo_unified_dialog_messages` MODIFY COLUMN `updated_ymdhis` bigint;
ALTER TABLE `lupo_unified_dialog_messages` MODIFY COLUMN `metadata_json` json;
ALTER TABLE `lupo_unified_dialog_messages` MODIFY COLUMN `body_text` longtext;
ALTER TABLE `lupo_unified_dialog_messages` ADD PRIMARY KEY (`dialog_message_id`);

CREATE TABLE IF NOT EXISTS `lupo_unified_truth_items` (
  `truth_item_id` bigint NOT NULL,
  `item_type` enum('question','answer') NOT NULL,
  `name` varchar(255),
  `slug` varchar(255),
  `body_text` longtext,
  `metadata_json` json,
  `created_ymdhis` bigint,
  PRIMARY KEY (`truth_item_id`)
);

ALTER TABLE `lupo_unified_truth_items` ADD COLUMN IF NOT EXISTS `truth_item_id` bigint NOT NULL;
ALTER TABLE `lupo_unified_truth_items` ADD COLUMN IF NOT EXISTS `item_type` enum('question','answer') NOT NULL;
ALTER TABLE `lupo_unified_truth_items` ADD COLUMN IF NOT EXISTS `name` varchar(255);
ALTER TABLE `lupo_unified_truth_items` ADD COLUMN IF NOT EXISTS `slug` varchar(255);
ALTER TABLE `lupo_unified_truth_items` ADD COLUMN IF NOT EXISTS `body_text` longtext;
ALTER TABLE `lupo_unified_truth_items` ADD COLUMN IF NOT EXISTS `metadata_json` json;
ALTER TABLE `lupo_unified_truth_items` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint;
ALTER TABLE `lupo_unified_truth_items` MODIFY COLUMN `truth_item_id` bigint NOT NULL;
ALTER TABLE `lupo_unified_truth_items` MODIFY COLUMN `item_type` enum('question','answer') NOT NULL;
ALTER TABLE `lupo_unified_truth_items` MODIFY COLUMN `name` varchar(255);
ALTER TABLE `lupo_unified_truth_items` MODIFY COLUMN `slug` varchar(255);
ALTER TABLE `lupo_unified_truth_items` MODIFY COLUMN `body_text` longtext;
ALTER TABLE `lupo_unified_truth_items` MODIFY COLUMN `metadata_json` json;
ALTER TABLE `lupo_unified_truth_items` MODIFY COLUMN `created_ymdhis` bigint;
ALTER TABLE `lupo_unified_truth_items` ADD PRIMARY KEY (`truth_item_id`);

CREATE TABLE IF NOT EXISTS `lupo_user_comments` (
  `user_comment_id` bigint NOT NULL auto_increment,
  `domain_id` bigint NOT NULL COMMENT 'Domain this comment belongs to',
  `user_id` bigint NOT NULL COMMENT 'User who created the comment',
  `content_id` bigint NOT NULL COMMENT 'Content this comment is associated with',
  `parent_comment_id` bigint COMMENT 'Parent comment ID for threaded replies (NULL for top-level comments)',
  `comment_text` text NOT NULL COMMENT 'The actual comment content',
  `user_agent` varchar(255) COMMENT 'User agent string from the commenter\'s browser/device',
  `ip_hash` char(64) COMMENT 'SHA-256 hash of the commenter\'s IP address for privacy',
  `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted',
  `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
  `updated_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when last updated',
  PRIMARY KEY (`user_comment_id`),
  KEY `idx_content_id` (`content_id`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_domain_id` (`domain_id`),
  KEY `idx_ip_hash` (`ip_hash`),
  KEY `idx_is_deleted` (`is_deleted`),
  KEY `idx_parent_comment_id` (`parent_comment_id`),
  KEY `idx_updated_ymdhis` (`updated_ymdhis`),
  KEY `idx_user_id` (`user_id`)
);

ALTER TABLE `lupo_user_comments` ADD COLUMN IF NOT EXISTS `user_comment_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_user_comments` ADD COLUMN IF NOT EXISTS `domain_id` bigint NOT NULL COMMENT 'Domain this comment belongs to';
ALTER TABLE `lupo_user_comments` ADD COLUMN IF NOT EXISTS `user_id` bigint NOT NULL COMMENT 'User who created the comment';
ALTER TABLE `lupo_user_comments` ADD COLUMN IF NOT EXISTS `content_id` bigint NOT NULL COMMENT 'Content this comment is associated with';
ALTER TABLE `lupo_user_comments` ADD COLUMN IF NOT EXISTS `parent_comment_id` bigint COMMENT 'Parent comment ID for threaded replies (NULL for top-level comments)';
ALTER TABLE `lupo_user_comments` ADD COLUMN IF NOT EXISTS `comment_text` text NOT NULL COMMENT 'The actual comment content';
ALTER TABLE `lupo_user_comments` ADD COLUMN IF NOT EXISTS `user_agent` varchar(255) COMMENT 'User agent string from the commenter\'s browser/device';
ALTER TABLE `lupo_user_comments` ADD COLUMN IF NOT EXISTS `ip_hash` char(64) COMMENT 'SHA-256 hash of the commenter\'s IP address for privacy';
ALTER TABLE `lupo_user_comments` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted';
ALTER TABLE `lupo_user_comments` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted';
ALTER TABLE `lupo_user_comments` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created';
ALTER TABLE `lupo_user_comments` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when last updated';
ALTER TABLE `lupo_user_comments` MODIFY COLUMN `user_comment_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_user_comments` MODIFY COLUMN `domain_id` bigint NOT NULL COMMENT 'Domain this comment belongs to';
ALTER TABLE `lupo_user_comments` MODIFY COLUMN `user_id` bigint NOT NULL COMMENT 'User who created the comment';
ALTER TABLE `lupo_user_comments` MODIFY COLUMN `content_id` bigint NOT NULL COMMENT 'Content this comment is associated with';
ALTER TABLE `lupo_user_comments` MODIFY COLUMN `parent_comment_id` bigint COMMENT 'Parent comment ID for threaded replies (NULL for top-level comments)';
ALTER TABLE `lupo_user_comments` MODIFY COLUMN `comment_text` text NOT NULL COMMENT 'The actual comment content';
ALTER TABLE `lupo_user_comments` MODIFY COLUMN `user_agent` varchar(255) COMMENT 'User agent string from the commenter\'s browser/device';
ALTER TABLE `lupo_user_comments` MODIFY COLUMN `ip_hash` char(64) COMMENT 'SHA-256 hash of the commenter\'s IP address for privacy';
ALTER TABLE `lupo_user_comments` MODIFY COLUMN `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT '1 = deleted, 0 = not deleted';
ALTER TABLE `lupo_user_comments` MODIFY COLUMN `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted';
ALTER TABLE `lupo_user_comments` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created';
ALTER TABLE `lupo_user_comments` MODIFY COLUMN `updated_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when last updated';
ALTER TABLE `lupo_user_comments` ADD PRIMARY KEY (`user_comment_id`);
ALTER TABLE `lupo_user_comments` ADD INDEX IF NOT EXISTS `idx_content_id` (`content_id`);
ALTER TABLE `lupo_user_comments` ADD INDEX IF NOT EXISTS `idx_created_ymdhis` (`created_ymdhis`);
ALTER TABLE `lupo_user_comments` ADD INDEX IF NOT EXISTS `idx_domain_id` (`domain_id`);
ALTER TABLE `lupo_user_comments` ADD INDEX IF NOT EXISTS `idx_ip_hash` (`ip_hash`);
ALTER TABLE `lupo_user_comments` ADD INDEX IF NOT EXISTS `idx_is_deleted` (`is_deleted`);
ALTER TABLE `lupo_user_comments` ADD INDEX IF NOT EXISTS `idx_parent_comment_id` (`parent_comment_id`);
ALTER TABLE `lupo_user_comments` ADD INDEX IF NOT EXISTS `idx_updated_ymdhis` (`updated_ymdhis`);
ALTER TABLE `lupo_user_comments` ADD INDEX IF NOT EXISTS `idx_user_id` (`user_id`);

CREATE TABLE IF NOT EXISTS `lupo_world_events` (
  `world_event_id` bigint NOT NULL auto_increment,
  `world_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `event_type` varchar(100) NOT NULL,
  `event_data` json,
  `created_ymdhis` bigint NOT NULL,
  PRIMARY KEY (`world_event_id`),
  KEY `idx_actor_id` (`actor_id`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_event_type` (`event_type`),
  KEY `idx_world_id` (`world_id`)
);

ALTER TABLE `lupo_world_events` ADD COLUMN IF NOT EXISTS `world_event_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_world_events` ADD COLUMN IF NOT EXISTS `world_id` bigint NOT NULL;
ALTER TABLE `lupo_world_events` ADD COLUMN IF NOT EXISTS `actor_id` bigint NOT NULL;
ALTER TABLE `lupo_world_events` ADD COLUMN IF NOT EXISTS `event_type` varchar(100) NOT NULL;
ALTER TABLE `lupo_world_events` ADD COLUMN IF NOT EXISTS `event_data` json;
ALTER TABLE `lupo_world_events` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_world_events` MODIFY COLUMN `world_event_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_world_events` MODIFY COLUMN `world_id` bigint NOT NULL;
ALTER TABLE `lupo_world_events` MODIFY COLUMN `actor_id` bigint NOT NULL;
ALTER TABLE `lupo_world_events` MODIFY COLUMN `event_type` varchar(100) NOT NULL;
ALTER TABLE `lupo_world_events` MODIFY COLUMN `event_data` json;
ALTER TABLE `lupo_world_events` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_world_events` ADD PRIMARY KEY (`world_event_id`);
ALTER TABLE `lupo_world_events` ADD INDEX IF NOT EXISTS `idx_actor_id` (`actor_id`);
ALTER TABLE `lupo_world_events` ADD INDEX IF NOT EXISTS `idx_created_ymdhis` (`created_ymdhis`);
ALTER TABLE `lupo_world_events` ADD INDEX IF NOT EXISTS `idx_event_type` (`event_type`);
ALTER TABLE `lupo_world_events` ADD INDEX IF NOT EXISTS `idx_world_id` (`world_id`);

CREATE TABLE IF NOT EXISTS `lupo_world_registry` (
  `world_id` bigint NOT NULL auto_increment COMMENT 'Primary key for world node',
  `world_key` varchar(255) NOT NULL COMMENT 'Deterministic world key (e.g., department_123)',
  `world_type` enum('department','channel','page','campaign','console','live','external','ui') NOT NULL COMMENT 'Type of world context',
  `world_label` varchar(255) NOT NULL COMMENT 'Human-readable world label',
  `world_metadata` json COMMENT 'Additional world context data',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Active flag',
  PRIMARY KEY (`world_id`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_is_active` (`is_active`),
  KEY `idx_world_type` (`world_type`),
  UNIQUE KEY `unique_world_key` (`world_key`)
);

ALTER TABLE `lupo_world_registry` ADD COLUMN IF NOT EXISTS `world_id` bigint NOT NULL auto_increment COMMENT 'Primary key for world node';
ALTER TABLE `lupo_world_registry` ADD COLUMN IF NOT EXISTS `world_key` varchar(255) NOT NULL COMMENT 'Deterministic world key (e.g., department_123)';
ALTER TABLE `lupo_world_registry` ADD COLUMN IF NOT EXISTS `world_type` enum('department','channel','page','campaign','console','live','external','ui') NOT NULL COMMENT 'Type of world context';
ALTER TABLE `lupo_world_registry` ADD COLUMN IF NOT EXISTS `world_label` varchar(255) NOT NULL COMMENT 'Human-readable world label';
ALTER TABLE `lupo_world_registry` ADD COLUMN IF NOT EXISTS `world_metadata` json COMMENT 'Additional world context data';
ALTER TABLE `lupo_world_registry` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS';
ALTER TABLE `lupo_world_registry` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS';
ALTER TABLE `lupo_world_registry` ADD COLUMN IF NOT EXISTS `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Active flag';
ALTER TABLE `lupo_world_registry` MODIFY COLUMN `world_id` bigint NOT NULL auto_increment COMMENT 'Primary key for world node';
ALTER TABLE `lupo_world_registry` MODIFY COLUMN `world_key` varchar(255) NOT NULL COMMENT 'Deterministic world key (e.g., department_123)';
ALTER TABLE `lupo_world_registry` MODIFY COLUMN `world_type` enum('department','channel','page','campaign','console','live','external','ui') NOT NULL COMMENT 'Type of world context';
ALTER TABLE `lupo_world_registry` MODIFY COLUMN `world_label` varchar(255) NOT NULL COMMENT 'Human-readable world label';
ALTER TABLE `lupo_world_registry` MODIFY COLUMN `world_metadata` json COMMENT 'Additional world context data';
ALTER TABLE `lupo_world_registry` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS';
ALTER TABLE `lupo_world_registry` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS';
ALTER TABLE `lupo_world_registry` MODIFY COLUMN `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Active flag';
ALTER TABLE `lupo_world_registry` ADD PRIMARY KEY (`world_id`);
ALTER TABLE `lupo_world_registry` ADD INDEX IF NOT EXISTS `idx_created_ymdhis` (`created_ymdhis`);
ALTER TABLE `lupo_world_registry` ADD INDEX IF NOT EXISTS `idx_is_active` (`is_active`);
ALTER TABLE `lupo_world_registry` ADD INDEX IF NOT EXISTS `idx_world_type` (`world_type`);
ALTER TABLE `lupo_world_registry` ADD UNIQUE INDEX IF NOT EXISTS `unique_world_key` (`world_key`);

CREATE TABLE IF NOT EXISTS `lupo_migration_alerts` (
  `alert_id` bigint NOT NULL DEFAULT 0,
  `batch_id` bigint NOT NULL DEFAULT 0,
  `file_id` bigint NOT NULL DEFAULT 0,
  `alert_type` enum('error','warning','info','critical') NOT NULL,
  `alert_title` varchar(255) NOT NULL,
  `alert_message` text NOT NULL,
  `alert_status` enum('new','acknowledged','resolved','escalated') NOT NULL DEFAULT 'new',
  `escalation_level` int NOT NULL DEFAULT 0,
  `properties` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint NOT NULL DEFAULT 0
);

ALTER TABLE `lupo_migration_alerts` ADD COLUMN IF NOT EXISTS `alert_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_alerts` ADD COLUMN IF NOT EXISTS `batch_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_alerts` ADD COLUMN IF NOT EXISTS `file_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_alerts` ADD COLUMN IF NOT EXISTS `alert_type` enum('error','warning','info','critical') NOT NULL;
ALTER TABLE `lupo_migration_alerts` ADD COLUMN IF NOT EXISTS `alert_title` varchar(255) NOT NULL;
ALTER TABLE `lupo_migration_alerts` ADD COLUMN IF NOT EXISTS `alert_message` text NOT NULL;
ALTER TABLE `lupo_migration_alerts` ADD COLUMN IF NOT EXISTS `alert_status` enum('new','acknowledged','resolved','escalated') NOT NULL DEFAULT 'new';
ALTER TABLE `lupo_migration_alerts` ADD COLUMN IF NOT EXISTS `escalation_level` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_alerts` ADD COLUMN IF NOT EXISTS `properties` json;
ALTER TABLE `lupo_migration_alerts` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_alerts` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_alerts` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint(1) NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_alerts` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_alerts` MODIFY COLUMN `alert_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_alerts` MODIFY COLUMN `batch_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_alerts` MODIFY COLUMN `file_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_alerts` MODIFY COLUMN `alert_type` enum('error','warning','info','critical') NOT NULL;
ALTER TABLE `lupo_migration_alerts` MODIFY COLUMN `alert_title` varchar(255) NOT NULL;
ALTER TABLE `lupo_migration_alerts` MODIFY COLUMN `alert_message` text NOT NULL;
ALTER TABLE `lupo_migration_alerts` MODIFY COLUMN `alert_status` enum('new','acknowledged','resolved','escalated') NOT NULL DEFAULT 'new';
ALTER TABLE `lupo_migration_alerts` MODIFY COLUMN `escalation_level` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_alerts` MODIFY COLUMN `properties` json;
ALTER TABLE `lupo_migration_alerts` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_alerts` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_alerts` MODIFY COLUMN `is_deleted` tinyint(1) NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_alerts` MODIFY COLUMN `deleted_ymdhis` bigint NOT NULL DEFAULT 0;

CREATE TABLE IF NOT EXISTS `lupo_migration_batches` (
  `batch_id` bigint NOT NULL DEFAULT 0,
  `batch_name` varchar(255) NOT NULL,
  `batch_status` enum('pending','running','completed','failed','rolled_back') NOT NULL DEFAULT 'pending',
  `epoch_from` bigint NOT NULL DEFAULT 0,
  `epoch_to` bigint NOT NULL DEFAULT 0,
  `total_files` int NOT NULL DEFAULT 0,
  `processed_files` int NOT NULL DEFAULT 0,
  `failed_files` int NOT NULL DEFAULT 0,
  `started_ymdhis` bigint NOT NULL DEFAULT 0,
  `completed_ymdhis` bigint NOT NULL DEFAULT 0,
  `properties` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint NOT NULL DEFAULT 0
);

ALTER TABLE `lupo_migration_batches` ADD COLUMN IF NOT EXISTS `batch_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_batches` ADD COLUMN IF NOT EXISTS `batch_name` varchar(255) NOT NULL;
ALTER TABLE `lupo_migration_batches` ADD COLUMN IF NOT EXISTS `batch_status` enum('pending','running','completed','failed','rolled_back') NOT NULL DEFAULT 'pending';
ALTER TABLE `lupo_migration_batches` ADD COLUMN IF NOT EXISTS `epoch_from` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_batches` ADD COLUMN IF NOT EXISTS `epoch_to` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_batches` ADD COLUMN IF NOT EXISTS `total_files` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_batches` ADD COLUMN IF NOT EXISTS `processed_files` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_batches` ADD COLUMN IF NOT EXISTS `failed_files` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_batches` ADD COLUMN IF NOT EXISTS `started_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_batches` ADD COLUMN IF NOT EXISTS `completed_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_batches` ADD COLUMN IF NOT EXISTS `properties` json;
ALTER TABLE `lupo_migration_batches` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_batches` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_batches` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint(1) NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_batches` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_batches` MODIFY COLUMN `batch_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_batches` MODIFY COLUMN `batch_name` varchar(255) NOT NULL;
ALTER TABLE `lupo_migration_batches` MODIFY COLUMN `batch_status` enum('pending','running','completed','failed','rolled_back') NOT NULL DEFAULT 'pending';
ALTER TABLE `lupo_migration_batches` MODIFY COLUMN `epoch_from` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_batches` MODIFY COLUMN `epoch_to` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_batches` MODIFY COLUMN `total_files` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_batches` MODIFY COLUMN `processed_files` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_batches` MODIFY COLUMN `failed_files` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_batches` MODIFY COLUMN `started_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_batches` MODIFY COLUMN `completed_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_batches` MODIFY COLUMN `properties` json;
ALTER TABLE `lupo_migration_batches` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_batches` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_batches` MODIFY COLUMN `is_deleted` tinyint(1) NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_batches` MODIFY COLUMN `deleted_ymdhis` bigint NOT NULL DEFAULT 0;

CREATE TABLE IF NOT EXISTS `lupo_migration_dependencies` (
  `dependency_id` bigint NOT NULL DEFAULT 0,
  `file_id` bigint NOT NULL DEFAULT 0,
  `depends_on_file_id` bigint NOT NULL DEFAULT 0,
  `dependency_type` enum('required','optional','conflict') NOT NULL,
  `dependency_description` text,
  `sort_order` int NOT NULL DEFAULT 0,
  `properties` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint NOT NULL DEFAULT 0
);

ALTER TABLE `lupo_migration_dependencies` ADD COLUMN IF NOT EXISTS `dependency_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_dependencies` ADD COLUMN IF NOT EXISTS `file_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_dependencies` ADD COLUMN IF NOT EXISTS `depends_on_file_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_dependencies` ADD COLUMN IF NOT EXISTS `dependency_type` enum('required','optional','conflict') NOT NULL;
ALTER TABLE `lupo_migration_dependencies` ADD COLUMN IF NOT EXISTS `dependency_description` text;
ALTER TABLE `lupo_migration_dependencies` ADD COLUMN IF NOT EXISTS `sort_order` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_dependencies` ADD COLUMN IF NOT EXISTS `properties` json;
ALTER TABLE `lupo_migration_dependencies` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_dependencies` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_dependencies` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint(1) NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_dependencies` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_dependencies` MODIFY COLUMN `dependency_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_dependencies` MODIFY COLUMN `file_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_dependencies` MODIFY COLUMN `depends_on_file_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_dependencies` MODIFY COLUMN `dependency_type` enum('required','optional','conflict') NOT NULL;
ALTER TABLE `lupo_migration_dependencies` MODIFY COLUMN `dependency_description` text;
ALTER TABLE `lupo_migration_dependencies` MODIFY COLUMN `sort_order` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_dependencies` MODIFY COLUMN `properties` json;
ALTER TABLE `lupo_migration_dependencies` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_dependencies` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_dependencies` MODIFY COLUMN `is_deleted` tinyint(1) NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_dependencies` MODIFY COLUMN `deleted_ymdhis` bigint NOT NULL DEFAULT 0;

CREATE TABLE IF NOT EXISTS `lupo_migration_files` (
  `file_id` bigint NOT NULL DEFAULT 0,
  `batch_id` bigint NOT NULL DEFAULT 0,
  `file_path` varchar(512) NOT NULL,
  `file_type` enum('doctrine','module','agent','documentation') NOT NULL,
  `file_status` enum('pending','processing','completed','failed','rolled_back') NOT NULL DEFAULT 'pending',
  `file_hash` varchar(64),
  `epoch_from` bigint NOT NULL DEFAULT 0,
  `epoch_to` bigint NOT NULL DEFAULT 0,
  `processing_time_ms` int NOT NULL DEFAULT 0,
  `error_message` text,
  `retry_count` int NOT NULL DEFAULT 0,
  `sort_order` int NOT NULL DEFAULT 0,
  `properties` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint NOT NULL DEFAULT 0
);

ALTER TABLE `lupo_migration_files` ADD COLUMN IF NOT EXISTS `file_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_files` ADD COLUMN IF NOT EXISTS `batch_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_files` ADD COLUMN IF NOT EXISTS `file_path` varchar(512) NOT NULL;
ALTER TABLE `lupo_migration_files` ADD COLUMN IF NOT EXISTS `file_type` enum('doctrine','module','agent','documentation') NOT NULL;
ALTER TABLE `lupo_migration_files` ADD COLUMN IF NOT EXISTS `file_status` enum('pending','processing','completed','failed','rolled_back') NOT NULL DEFAULT 'pending';
ALTER TABLE `lupo_migration_files` ADD COLUMN IF NOT EXISTS `file_hash` varchar(64);
ALTER TABLE `lupo_migration_files` ADD COLUMN IF NOT EXISTS `epoch_from` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_files` ADD COLUMN IF NOT EXISTS `epoch_to` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_files` ADD COLUMN IF NOT EXISTS `processing_time_ms` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_files` ADD COLUMN IF NOT EXISTS `error_message` text;
ALTER TABLE `lupo_migration_files` ADD COLUMN IF NOT EXISTS `retry_count` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_files` ADD COLUMN IF NOT EXISTS `sort_order` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_files` ADD COLUMN IF NOT EXISTS `properties` json;
ALTER TABLE `lupo_migration_files` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_files` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_files` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint(1) NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_files` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_files` MODIFY COLUMN `file_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_files` MODIFY COLUMN `batch_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_files` MODIFY COLUMN `file_path` varchar(512) NOT NULL;
ALTER TABLE `lupo_migration_files` MODIFY COLUMN `file_type` enum('doctrine','module','agent','documentation') NOT NULL;
ALTER TABLE `lupo_migration_files` MODIFY COLUMN `file_status` enum('pending','processing','completed','failed','rolled_back') NOT NULL DEFAULT 'pending';
ALTER TABLE `lupo_migration_files` MODIFY COLUMN `file_hash` varchar(64);
ALTER TABLE `lupo_migration_files` MODIFY COLUMN `epoch_from` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_files` MODIFY COLUMN `epoch_to` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_files` MODIFY COLUMN `processing_time_ms` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_files` MODIFY COLUMN `error_message` text;
ALTER TABLE `lupo_migration_files` MODIFY COLUMN `retry_count` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_files` MODIFY COLUMN `sort_order` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_files` MODIFY COLUMN `properties` json;
ALTER TABLE `lupo_migration_files` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_files` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_files` MODIFY COLUMN `is_deleted` tinyint(1) NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_files` MODIFY COLUMN `deleted_ymdhis` bigint NOT NULL DEFAULT 0;

CREATE TABLE IF NOT EXISTS `lupo_migration_progress` (
  `progress_id` bigint NOT NULL DEFAULT 0,
  `batch_id` bigint NOT NULL DEFAULT 0,
  `current_phase` varchar(100) NOT NULL,
  `total_phases` int NOT NULL DEFAULT 0,
  `current_phase_index` int NOT NULL DEFAULT 0,
  `files_in_phase` int NOT NULL DEFAULT 0,
  `files_completed_in_phase` int NOT NULL DEFAULT 0,
  `percentage_complete` decimal(5,2) NOT NULL DEFAULT 0.00,
  `estimated_remaining_seconds` int NOT NULL DEFAULT 0,
  `properties` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint NOT NULL DEFAULT 0
);

ALTER TABLE `lupo_migration_progress` ADD COLUMN IF NOT EXISTS `progress_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_progress` ADD COLUMN IF NOT EXISTS `batch_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_progress` ADD COLUMN IF NOT EXISTS `current_phase` varchar(100) NOT NULL;
ALTER TABLE `lupo_migration_progress` ADD COLUMN IF NOT EXISTS `total_phases` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_progress` ADD COLUMN IF NOT EXISTS `current_phase_index` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_progress` ADD COLUMN IF NOT EXISTS `files_in_phase` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_progress` ADD COLUMN IF NOT EXISTS `files_completed_in_phase` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_progress` ADD COLUMN IF NOT EXISTS `percentage_complete` decimal(5,2) NOT NULL DEFAULT 0.00;
ALTER TABLE `lupo_migration_progress` ADD COLUMN IF NOT EXISTS `estimated_remaining_seconds` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_progress` ADD COLUMN IF NOT EXISTS `properties` json;
ALTER TABLE `lupo_migration_progress` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_progress` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_progress` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint(1) NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_progress` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_progress` MODIFY COLUMN `progress_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_progress` MODIFY COLUMN `batch_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_progress` MODIFY COLUMN `current_phase` varchar(100) NOT NULL;
ALTER TABLE `lupo_migration_progress` MODIFY COLUMN `total_phases` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_progress` MODIFY COLUMN `current_phase_index` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_progress` MODIFY COLUMN `files_in_phase` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_progress` MODIFY COLUMN `files_completed_in_phase` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_progress` MODIFY COLUMN `percentage_complete` decimal(5,2) NOT NULL DEFAULT 0.00;
ALTER TABLE `lupo_migration_progress` MODIFY COLUMN `estimated_remaining_seconds` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_progress` MODIFY COLUMN `properties` json;
ALTER TABLE `lupo_migration_progress` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_progress` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_progress` MODIFY COLUMN `is_deleted` tinyint(1) NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_progress` MODIFY COLUMN `deleted_ymdhis` bigint NOT NULL DEFAULT 0;

CREATE TABLE IF NOT EXISTS `lupo_migration_rollback_log` (
  `rollback_id` bigint NOT NULL DEFAULT 0,
  `batch_id` bigint NOT NULL DEFAULT 0,
  `file_id` bigint NOT NULL DEFAULT 0,
  `rollback_classification` enum('A','B','C','D') NOT NULL,
  `rollback_reason` varchar(255) NOT NULL,
  `rollback_status` enum('pending','running','completed','failed') NOT NULL DEFAULT 'pending',
  `files_affected` int NOT NULL DEFAULT 0,
  `rollback_time_ms` int NOT NULL DEFAULT 0,
  `error_message` text,
  `properties` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint NOT NULL DEFAULT 0
);

ALTER TABLE `lupo_migration_rollback_log` ADD COLUMN IF NOT EXISTS `rollback_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_rollback_log` ADD COLUMN IF NOT EXISTS `batch_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_rollback_log` ADD COLUMN IF NOT EXISTS `file_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_rollback_log` ADD COLUMN IF NOT EXISTS `rollback_classification` enum('A','B','C','D') NOT NULL;
ALTER TABLE `lupo_migration_rollback_log` ADD COLUMN IF NOT EXISTS `rollback_reason` varchar(255) NOT NULL;
ALTER TABLE `lupo_migration_rollback_log` ADD COLUMN IF NOT EXISTS `rollback_status` enum('pending','running','completed','failed') NOT NULL DEFAULT 'pending';
ALTER TABLE `lupo_migration_rollback_log` ADD COLUMN IF NOT EXISTS `files_affected` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_rollback_log` ADD COLUMN IF NOT EXISTS `rollback_time_ms` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_rollback_log` ADD COLUMN IF NOT EXISTS `error_message` text;
ALTER TABLE `lupo_migration_rollback_log` ADD COLUMN IF NOT EXISTS `properties` json;
ALTER TABLE `lupo_migration_rollback_log` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_rollback_log` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_rollback_log` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint(1) NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_rollback_log` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_rollback_log` MODIFY COLUMN `rollback_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_rollback_log` MODIFY COLUMN `batch_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_rollback_log` MODIFY COLUMN `file_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_rollback_log` MODIFY COLUMN `rollback_classification` enum('A','B','C','D') NOT NULL;
ALTER TABLE `lupo_migration_rollback_log` MODIFY COLUMN `rollback_reason` varchar(255) NOT NULL;
ALTER TABLE `lupo_migration_rollback_log` MODIFY COLUMN `rollback_status` enum('pending','running','completed','failed') NOT NULL DEFAULT 'pending';
ALTER TABLE `lupo_migration_rollback_log` MODIFY COLUMN `files_affected` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_rollback_log` MODIFY COLUMN `rollback_time_ms` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_rollback_log` MODIFY COLUMN `error_message` text;
ALTER TABLE `lupo_migration_rollback_log` MODIFY COLUMN `properties` json;
ALTER TABLE `lupo_migration_rollback_log` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_rollback_log` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_rollback_log` MODIFY COLUMN `is_deleted` tinyint(1) NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_rollback_log` MODIFY COLUMN `deleted_ymdhis` bigint NOT NULL DEFAULT 0;

CREATE TABLE IF NOT EXISTS `lupo_migration_system_state` (
  `state_id` bigint NOT NULL DEFAULT 0,
  `batch_id` bigint NOT NULL DEFAULT 0,
  `system_status` enum('normal','frozen','thawing','emergency') NOT NULL DEFAULT 'normal',
  `freeze_reason` varchar(255),
  `affected_components` json,
  `user_message` text,
  `properties` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint NOT NULL DEFAULT 0
);

ALTER TABLE `lupo_migration_system_state` ADD COLUMN IF NOT EXISTS `state_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_system_state` ADD COLUMN IF NOT EXISTS `batch_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_system_state` ADD COLUMN IF NOT EXISTS `system_status` enum('normal','frozen','thawing','emergency') NOT NULL DEFAULT 'normal';
ALTER TABLE `lupo_migration_system_state` ADD COLUMN IF NOT EXISTS `freeze_reason` varchar(255);
ALTER TABLE `lupo_migration_system_state` ADD COLUMN IF NOT EXISTS `affected_components` json;
ALTER TABLE `lupo_migration_system_state` ADD COLUMN IF NOT EXISTS `user_message` text;
ALTER TABLE `lupo_migration_system_state` ADD COLUMN IF NOT EXISTS `properties` json;
ALTER TABLE `lupo_migration_system_state` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_system_state` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_system_state` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint(1) NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_system_state` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_system_state` MODIFY COLUMN `state_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_system_state` MODIFY COLUMN `batch_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_system_state` MODIFY COLUMN `system_status` enum('normal','frozen','thawing','emergency') NOT NULL DEFAULT 'normal';
ALTER TABLE `lupo_migration_system_state` MODIFY COLUMN `freeze_reason` varchar(255);
ALTER TABLE `lupo_migration_system_state` MODIFY COLUMN `affected_components` json;
ALTER TABLE `lupo_migration_system_state` MODIFY COLUMN `user_message` text;
ALTER TABLE `lupo_migration_system_state` MODIFY COLUMN `properties` json;
ALTER TABLE `lupo_migration_system_state` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_system_state` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_system_state` MODIFY COLUMN `is_deleted` tinyint(1) NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_system_state` MODIFY COLUMN `deleted_ymdhis` bigint NOT NULL DEFAULT 0;

CREATE TABLE IF NOT EXISTS `lupo_migration_validation_log` (
  `validation_id` bigint NOT NULL DEFAULT 0,
  `batch_id` bigint NOT NULL DEFAULT 0,
  `file_id` bigint NOT NULL DEFAULT 0,
  `validation_phase` enum('pre','during','post') NOT NULL,
  `validation_type` varchar(100) NOT NULL,
  `validation_status` enum('passed','failed','warning') NOT NULL,
  `validation_result` text,
  `execution_time_ms` int NOT NULL DEFAULT 0,
  `properties` json,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL DEFAULT 0,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint NOT NULL DEFAULT 0
);

ALTER TABLE `lupo_migration_validation_log` ADD COLUMN IF NOT EXISTS `validation_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_validation_log` ADD COLUMN IF NOT EXISTS `batch_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_validation_log` ADD COLUMN IF NOT EXISTS `file_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_validation_log` ADD COLUMN IF NOT EXISTS `validation_phase` enum('pre','during','post') NOT NULL;
ALTER TABLE `lupo_migration_validation_log` ADD COLUMN IF NOT EXISTS `validation_type` varchar(100) NOT NULL;
ALTER TABLE `lupo_migration_validation_log` ADD COLUMN IF NOT EXISTS `validation_status` enum('passed','failed','warning') NOT NULL;
ALTER TABLE `lupo_migration_validation_log` ADD COLUMN IF NOT EXISTS `validation_result` text;
ALTER TABLE `lupo_migration_validation_log` ADD COLUMN IF NOT EXISTS `execution_time_ms` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_validation_log` ADD COLUMN IF NOT EXISTS `properties` json;
ALTER TABLE `lupo_migration_validation_log` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_validation_log` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_validation_log` ADD COLUMN IF NOT EXISTS `is_deleted` tinyint(1) NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_validation_log` ADD COLUMN IF NOT EXISTS `deleted_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_validation_log` MODIFY COLUMN `validation_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_validation_log` MODIFY COLUMN `batch_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_validation_log` MODIFY COLUMN `file_id` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_validation_log` MODIFY COLUMN `validation_phase` enum('pre','during','post') NOT NULL;
ALTER TABLE `lupo_migration_validation_log` MODIFY COLUMN `validation_type` varchar(100) NOT NULL;
ALTER TABLE `lupo_migration_validation_log` MODIFY COLUMN `validation_status` enum('passed','failed','warning') NOT NULL;
ALTER TABLE `lupo_migration_validation_log` MODIFY COLUMN `validation_result` text;
ALTER TABLE `lupo_migration_validation_log` MODIFY COLUMN `execution_time_ms` int NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_validation_log` MODIFY COLUMN `properties` json;
ALTER TABLE `lupo_migration_validation_log` MODIFY COLUMN `created_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_validation_log` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_validation_log` MODIFY COLUMN `is_deleted` tinyint(1) NOT NULL DEFAULT 0;
ALTER TABLE `lupo_migration_validation_log` MODIFY COLUMN `deleted_ymdhis` bigint NOT NULL DEFAULT 0;

CREATE TABLE IF NOT EXISTS `lupo_test_performance_metrics` (
  `test_id` bigint NOT NULL auto_increment,
  `test_category` varchar(64) NOT NULL,
  `test_name` varchar(128) NOT NULL,
  `execution_time_ms` int NOT NULL,
  `memory_usage_mb` decimal(10,2),
  `cpu_usage_percent` decimal(5,2),
  `success_rate` decimal(5,2),
  `error_count` int DEFAULT 0,
  `created_ymdhis` bigint NOT NULL
);

ALTER TABLE `lupo_test_performance_metrics` ADD COLUMN IF NOT EXISTS `test_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_test_performance_metrics` ADD COLUMN IF NOT EXISTS `test_category` varchar(64) NOT NULL;
ALTER TABLE `lupo_test_performance_metrics` ADD COLUMN IF NOT EXISTS `test_name` varchar(128) NOT NULL;
ALTER TABLE `lupo_test_performance_metrics` ADD COLUMN IF NOT EXISTS `execution_time_ms` int NOT NULL;
ALTER TABLE `lupo_test_performance_metrics` ADD COLUMN IF NOT EXISTS `memory_usage_mb` decimal(10,2);
ALTER TABLE `lupo_test_performance_metrics` ADD COLUMN IF NOT EXISTS `cpu_usage_percent` decimal(5,2);
ALTER TABLE `lupo_test_performance_metrics` ADD COLUMN IF NOT EXISTS `success_rate` decimal(5,2);
ALTER TABLE `lupo_test_performance_metrics` ADD COLUMN IF NOT EXISTS `error_count` int DEFAULT 0;
ALTER TABLE `lupo_test_performance_metrics` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_test_performance_metrics` MODIFY COLUMN `test_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_test_performance_metrics` MODIFY COLUMN `test_category` varchar(64) NOT NULL;
ALTER TABLE `lupo_test_performance_metrics` MODIFY COLUMN `test_name` varchar(128) NOT NULL;
ALTER TABLE `lupo_test_performance_metrics` MODIFY COLUMN `execution_time_ms` int NOT NULL;
ALTER TABLE `lupo_test_performance_metrics` MODIFY COLUMN `memory_usage_mb` decimal(10,2);
ALTER TABLE `lupo_test_performance_metrics` MODIFY COLUMN `cpu_usage_percent` decimal(5,2);
ALTER TABLE `lupo_test_performance_metrics` MODIFY COLUMN `success_rate` decimal(5,2);
ALTER TABLE `lupo_test_performance_metrics` MODIFY COLUMN `error_count` int DEFAULT 0;
ALTER TABLE `lupo_test_performance_metrics` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;

CREATE TABLE IF NOT EXISTS `lupo_unified_analytics_paths` (
  `path_id` bigint NOT NULL auto_increment,
  `path_pattern` varchar(500) NOT NULL,
  `path_type` varchar(50),
  `access_count` int DEFAULT 0,
  `last_accessed` bigint,
  `created_ymdhis` bigint NOT NULL
);

ALTER TABLE `lupo_unified_analytics_paths` ADD COLUMN IF NOT EXISTS `path_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_unified_analytics_paths` ADD COLUMN IF NOT EXISTS `path_pattern` varchar(500) NOT NULL;
ALTER TABLE `lupo_unified_analytics_paths` ADD COLUMN IF NOT EXISTS `path_type` varchar(50);
ALTER TABLE `lupo_unified_analytics_paths` ADD COLUMN IF NOT EXISTS `access_count` int DEFAULT 0;
ALTER TABLE `lupo_unified_analytics_paths` ADD COLUMN IF NOT EXISTS `last_accessed` bigint;
ALTER TABLE `lupo_unified_analytics_paths` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_unified_analytics_paths` MODIFY COLUMN `path_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_unified_analytics_paths` MODIFY COLUMN `path_pattern` varchar(500) NOT NULL;
ALTER TABLE `lupo_unified_analytics_paths` MODIFY COLUMN `path_type` varchar(50);
ALTER TABLE `lupo_unified_analytics_paths` MODIFY COLUMN `access_count` int DEFAULT 0;
ALTER TABLE `lupo_unified_analytics_paths` MODIFY COLUMN `last_accessed` bigint;
ALTER TABLE `lupo_unified_analytics_paths` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;

CREATE TABLE IF NOT EXISTS `lupo_unified_dialog_messages` (
  `unified_message_id` bigint NOT NULL auto_increment,
  `message_key` varchar(255) NOT NULL,
  `channel_id` bigint,
  `actor_id` bigint,
  `message_content` text,
  `message_metadata` json,
  `created_ymdhis` bigint NOT NULL
);

ALTER TABLE `lupo_unified_dialog_messages` ADD COLUMN IF NOT EXISTS `unified_message_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_unified_dialog_messages` ADD COLUMN IF NOT EXISTS `message_key` varchar(255) NOT NULL;
ALTER TABLE `lupo_unified_dialog_messages` ADD COLUMN IF NOT EXISTS `channel_id` bigint;
ALTER TABLE `lupo_unified_dialog_messages` ADD COLUMN IF NOT EXISTS `actor_id` bigint;
ALTER TABLE `lupo_unified_dialog_messages` ADD COLUMN IF NOT EXISTS `message_content` text;
ALTER TABLE `lupo_unified_dialog_messages` ADD COLUMN IF NOT EXISTS `message_metadata` json;
ALTER TABLE `lupo_unified_dialog_messages` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_unified_dialog_messages` MODIFY COLUMN `unified_message_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_unified_dialog_messages` MODIFY COLUMN `message_key` varchar(255) NOT NULL;
ALTER TABLE `lupo_unified_dialog_messages` MODIFY COLUMN `channel_id` bigint;
ALTER TABLE `lupo_unified_dialog_messages` MODIFY COLUMN `actor_id` bigint;
ALTER TABLE `lupo_unified_dialog_messages` MODIFY COLUMN `message_content` text;
ALTER TABLE `lupo_unified_dialog_messages` MODIFY COLUMN `message_metadata` json;
ALTER TABLE `lupo_unified_dialog_messages` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;

CREATE TABLE IF NOT EXISTS `lupo_unified_sessions` (
  `id` bigint NOT NULL auto_increment COMMENT 'Primary key for session',
  `session_id` varchar(255) NOT NULL COMMENT 'Session identifier',
  `user_id` bigint COMMENT 'Reference to lupo_auth_users.auth_user_id',
  `system_context` varchar(50) NOT NULL COMMENT 'lupopedia, crafty_syntax, or unified',
  `session_data` json COMMENT 'Session metadata and preferences',
  `expires_ymdhis` bigint NOT NULL COMMENT 'Session expiration time',
  `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint',
  `updated_ymdhis` bigint NOT NULL COMMENT 'Last update bigint'
);

ALTER TABLE `lupo_unified_sessions` ADD COLUMN IF NOT EXISTS `id` bigint NOT NULL auto_increment COMMENT 'Primary key for session';
ALTER TABLE `lupo_unified_sessions` ADD COLUMN IF NOT EXISTS `session_id` varchar(255) NOT NULL COMMENT 'Session identifier';
ALTER TABLE `lupo_unified_sessions` ADD COLUMN IF NOT EXISTS `user_id` bigint COMMENT 'Reference to lupo_auth_users.auth_user_id';
ALTER TABLE `lupo_unified_sessions` ADD COLUMN IF NOT EXISTS `system_context` varchar(50) NOT NULL COMMENT 'lupopedia, crafty_syntax, or unified';
ALTER TABLE `lupo_unified_sessions` ADD COLUMN IF NOT EXISTS `session_data` json COMMENT 'Session metadata and preferences';
ALTER TABLE `lupo_unified_sessions` ADD COLUMN IF NOT EXISTS `expires_ymdhis` bigint NOT NULL COMMENT 'Session expiration time';
ALTER TABLE `lupo_unified_sessions` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint';
ALTER TABLE `lupo_unified_sessions` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL COMMENT 'Last update bigint';
ALTER TABLE `lupo_unified_sessions` MODIFY COLUMN `id` bigint NOT NULL auto_increment COMMENT 'Primary key for session';
ALTER TABLE `lupo_unified_sessions` MODIFY COLUMN `session_id` varchar(255) NOT NULL COMMENT 'Session identifier';
ALTER TABLE `lupo_unified_sessions` MODIFY COLUMN `user_id` bigint COMMENT 'Reference to lupo_auth_users.auth_user_id';
ALTER TABLE `lupo_unified_sessions` MODIFY COLUMN `system_context` varchar(50) NOT NULL COMMENT 'lupopedia, crafty_syntax, or unified';
ALTER TABLE `lupo_unified_sessions` MODIFY COLUMN `session_data` json COMMENT 'Session metadata and preferences';
ALTER TABLE `lupo_unified_sessions` MODIFY COLUMN `expires_ymdhis` bigint NOT NULL COMMENT 'Session expiration time';
ALTER TABLE `lupo_unified_sessions` MODIFY COLUMN `created_ymdhis` bigint NOT NULL COMMENT 'Creation bigint';
ALTER TABLE `lupo_unified_sessions` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL COMMENT 'Last update bigint';

CREATE TABLE IF NOT EXISTS `lupo_unified_truth_items` (
  `unified_truth_id` bigint NOT NULL auto_increment,
  `truth_key` varchar(255) NOT NULL,
  `truth_value` text,
  `truth_type` varchar(50),
  `confidence_score` decimal(3,2) DEFAULT 1.00,
  `source_actor_id` bigint,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL
);

ALTER TABLE `lupo_unified_truth_items` ADD COLUMN IF NOT EXISTS `unified_truth_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_unified_truth_items` ADD COLUMN IF NOT EXISTS `truth_key` varchar(255) NOT NULL;
ALTER TABLE `lupo_unified_truth_items` ADD COLUMN IF NOT EXISTS `truth_value` text;
ALTER TABLE `lupo_unified_truth_items` ADD COLUMN IF NOT EXISTS `truth_type` varchar(50);
ALTER TABLE `lupo_unified_truth_items` ADD COLUMN IF NOT EXISTS `confidence_score` decimal(3,2) DEFAULT 1.00;
ALTER TABLE `lupo_unified_truth_items` ADD COLUMN IF NOT EXISTS `source_actor_id` bigint;
ALTER TABLE `lupo_unified_truth_items` ADD COLUMN IF NOT EXISTS `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_unified_truth_items` ADD COLUMN IF NOT EXISTS `updated_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_unified_truth_items` MODIFY COLUMN `unified_truth_id` bigint NOT NULL auto_increment;
ALTER TABLE `lupo_unified_truth_items` MODIFY COLUMN `truth_key` varchar(255) NOT NULL;
ALTER TABLE `lupo_unified_truth_items` MODIFY COLUMN `truth_value` text;
ALTER TABLE `lupo_unified_truth_items` MODIFY COLUMN `truth_type` varchar(50);
ALTER TABLE `lupo_unified_truth_items` MODIFY COLUMN `confidence_score` decimal(3,2) DEFAULT 1.00;
ALTER TABLE `lupo_unified_truth_items` MODIFY COLUMN `source_actor_id` bigint;
ALTER TABLE `lupo_unified_truth_items` MODIFY COLUMN `created_ymdhis` bigint NOT NULL;
ALTER TABLE `lupo_unified_truth_items` MODIFY COLUMN `updated_ymdhis` bigint NOT NULL;

