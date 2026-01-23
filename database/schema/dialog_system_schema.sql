-- Dialog System Schema for Big Rock 2: Dialog Channel Migration
-- Creates dialog_channels and dialog_messages tables for database-backed dialog storage
-- Updated to match actual database schema per TOON files (4.0.101)
-- 
-- @package Lupopedia
-- @version 4.0.101
-- @author Captain Wolfie

-- Dialog Channels Table
-- Stores channel metadata extracted from WOLFIE headers
CREATE TABLE IF NOT EXISTS `lupo_dialog_channels` (
  `channel_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `channel_name` VARCHAR(255) NOT NULL,
  `file_source` VARCHAR(255) NOT NULL COMMENT 'Original .md filename',
  `title` VARCHAR(500) DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `speaker` VARCHAR(100) DEFAULT NULL,
  `target` VARCHAR(100) DEFAULT NULL,
  `mood_rgb` VARCHAR(7) DEFAULT NULL COMMENT 'Emotional polarity tensor encoded as hex (abstract axes: strife (R), harmony (G), memory depth (B); not literal RGB color channels)',
  `categories` JSON DEFAULT NULL COMMENT 'Array of category strings',
  `collections` JSON DEFAULT NULL COMMENT 'Array of collection strings',
  `channels` JSON DEFAULT NULL COMMENT 'Array of channel strings',
  `tags` JSON DEFAULT NULL COMMENT 'Additional tag metadata',
  `version` VARCHAR(20) DEFAULT NULL COMMENT 'System version when created',
  `status` ENUM('draft', 'published', 'archived') DEFAULT 'published',
  `author` VARCHAR(100) DEFAULT NULL,
  `created_timestamp` BIGINT(14) UNSIGNED NOT NULL COMMENT 'YYYYMMDDHHIISS format',
  `modified_timestamp` BIGINT(14) UNSIGNED NOT NULL COMMENT 'YYYYMMDDHHIISS format',
  `message_count` INT(11) UNSIGNED DEFAULT 0 COMMENT 'Cached count of messages',
  `metadata_json` JSON DEFAULT NULL COMMENT 'Additional metadata from WOLFIE headers',
  PRIMARY KEY (`channel_id`),
  UNIQUE KEY `idx_channel_name` (`channel_name`),
  KEY `idx_file_source` (`file_source`),
  KEY `idx_speaker` (`speaker`),
  KEY `idx_target` (`target`),
  KEY `idx_status` (`status`),
  KEY `idx_created_timestamp` (`created_timestamp`),
  KEY `idx_modified_timestamp` (`modified_timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Dialog channels with metadata from WOLFIE headers';

-- Dialog Messages Table  
-- Stores individual messages within dialog channels
-- NOTE: This schema matches the actual database structure per TOON files
-- Updated to reflect actual columns: dialog_message_id, from_actor_id, to_actor_id, etc.
CREATE TABLE IF NOT EXISTS `lupo_dialog_doctrine` (
  `dialog_message_id` BIGINT(20) NOT NULL AUTO_INCREMENT COMMENT 'Primary key for the dialog message',
  `dialog_thread_id` BIGINT(20) DEFAULT NULL COMMENT 'Optional thread grouping for related dialogs',
  `channel_id` BIGINT(20) DEFAULT NULL COMMENT 'Optional channel identifier',
  `from_actor_id` BIGINT(20) DEFAULT NULL COMMENT 'Actor ID of the message sender',
  `to_actor_id` BIGINT(20) DEFAULT NULL COMMENT 'Agent ID if message is from an AI agent',
  `message_text` VARCHAR(1000) NOT NULL COMMENT 'The message under 1000 chars',
  `message_type` ENUM('text','command','system','error') NOT NULL DEFAULT 'text' COMMENT 'Type of message',
  `metadata_json` JSON DEFAULT NULL COMMENT 'Additional message metadata',
  `mood_rgb` CHAR(6) NOT NULL DEFAULT '666666' COMMENT 'Emotional polarity tensor encoded as hex (abstract axes: strife (R), harmony (G), memory depth (B); not literal RGB color channels)',
  `weight` DECIMAL(3,2) NOT NULL DEFAULT '0.00',
  `created_ymdhis` BIGINT(20) NOT NULL COMMENT 'Creation timestamp (YYYYMMDDHHMMSS)',
  `updated_ymdhis` BIGINT(20) NOT NULL COMMENT 'Last update timestamp (YYYYMMDDHHMMSS)',
  `is_deleted` TINYINT NOT NULL DEFAULT '0' COMMENT 'Soft delete flag (1=deleted)',
  `deleted_ymdhis` BIGINT(20) DEFAULT NULL COMMENT 'Deletion timestamp (YYYYMMDDHHMMSS)',
  PRIMARY KEY (`dialog_message_id`),
  KEY `idx_channel` (`channel_id`),
  KEY `idx_created` (`created_ymdhis`),
  KEY `idx_deleted` (`is_deleted`),
  KEY `idx_dialog_thread_id` (`dialog_thread_id`),
  KEY `idx_message_type` (`message_type`),
  KEY `idx_to_actor_id` (`to_actor_id`),
  KEY `idx_updated` (`updated_ymdhis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores messages in dialog threads between agents and users. Supports rich message types, inline dialogs, and threading.';

-- Dialog Message Bodies Table
-- Stores full long-form message bodies for dialog messages
CREATE TABLE IF NOT EXISTS `lupo_dialog_message_bodies` (
  `dialog_message_body_id` BIGINT(20) NOT NULL AUTO_INCREMENT COMMENT 'Primary key for the full message body',
  `dialog_message_id` BIGINT(20) NOT NULL COMMENT 'Links to dialog_messages entry (no FK by doctrine)',
  `full_text` LONGTEXT NOT NULL COMMENT 'Full long-form message content',
  `metadata_json` JSON DEFAULT NULL COMMENT 'Additional message metadata',
  `mood_rgb` CHAR(6) NOT NULL DEFAULT '666666' COMMENT 'Emotional polarity tensor encoded as hex (abstract axes: strife (R), harmony (G), memory depth (B); not literal RGB color channels)',
  `weight` DECIMAL(3,2) NOT NULL DEFAULT '0.00',
  `created_ymdhis` BIGINT(20) NOT NULL COMMENT 'Creation timestamp (YYYYMMDDHHMMSS)',
  `updated_ymdhis` BIGINT(20) NOT NULL COMMENT 'Last update timestamp (YYYYMMDDHHMMSS)',
  `deleted_flag` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'Soft delete flag (1=deleted, 0=active)',
  `deleted_ymdhis` BIGINT(20) DEFAULT NULL COMMENT 'Deletion timestamp (YYYYMMDDHHMMSS)',
  PRIMARY KEY (`dialog_message_body_id`),
  UNIQUE KEY `unq_dialog_message_id` (`dialog_message_id`),
  KEY `idx_dialog_message_id` (`dialog_message_id`),
  KEY `idx_created` (`created_ymdhis`),
  KEY `idx_updated` (`updated_ymdhis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores full long-form message bodies for dialog messages.';

-- Dialog Threads Table
-- High-level dialog threads grouping messages across agents, tasks, and projects
CREATE TABLE IF NOT EXISTS `lupo_dialog_threads` (
  `dialog_thread_id` BIGINT(20) NOT NULL AUTO_INCREMENT COMMENT 'Primary key for the dialog thread',
  `federation_node_id` BIGINT(20) NOT NULL DEFAULT '1' COMMENT 'Node that owns this thread; default is local installation (1)',
  `channel_id` BIGINT(20) DEFAULT NULL COMMENT 'Optional channel identifier for grouping threads',
  `project_slug` VARCHAR(100) DEFAULT NULL COMMENT 'Project or subsystem this thread belongs to',
  `task_name` VARCHAR(255) DEFAULT NULL COMMENT 'Human-readable task name for this thread',
  `created_by_actor_id` BIGINT(20) NOT NULL COMMENT 'Agent or human who initiated the thread',
  `summary_text` TEXT COMMENT 'Short summary of the thread purpose or context',
  `status` ENUM('Open','Ongoing','Closed','Archived') NOT NULL DEFAULT 'Open' COMMENT 'Thread lifecycle state',
  `artifacts` JSON DEFAULT NULL COMMENT 'Optional JSON list of related files, URLs, or resources',
  `metadata_json` JSON DEFAULT NULL COMMENT 'Metadata: intent, scope, persona, mood, inline dialog metadata',
  `created_ymdhis` BIGINT(20) NOT NULL COMMENT 'Creation timestamp (YYYYMMDDHHMMSS)',
  `updated_ymdhis` BIGINT(20) NOT NULL COMMENT 'Last update timestamp (YYYYMMDDHHMMSS)',
  `is_deleted` TINYINT NOT NULL DEFAULT '0' COMMENT 'Soft delete flag (1=deleted)',
  `deleted_ymdhis` BIGINT(20) DEFAULT NULL COMMENT 'Deletion timestamp (YYYYMMDDHHMMSS)',
  PRIMARY KEY (`dialog_thread_id`),
  KEY `idx_channel_id` (`channel_id`),
  KEY `idx_created_by_actor_id` (`created_by_actor_id`),
  KEY `idx_status` (`status`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_updated_ymdhis` (`updated_ymdhis`),
  KEY `idx_is_deleted` (`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='High-level dialog threads grouping messages across agents, tasks, and projects.';

-- Trigger logic moved to PHP service classes:
-- - app/Services/TriggerReplacements/DialogMessagesInsertService.php
-- - app/Services/TriggerReplacements/DialogMessagesDeleteService.php
-- Triggers removed per NO_TRIGGERS_NO_PROCEDURES_DOCTRINE

-- Note: Foreign keys removed per NO_FK doctrine
-- All relationships are maintained in application layer

-- Create composite index for performance (already exists in database)
-- CREATE INDEX `idx_dialog_channels_composite` ON `lupo_dialog_channels` (`status`, `created_timestamp`);

-- Add comments for documentation
ALTER TABLE `lupo_dialog_channels` COMMENT = 'Dialog channels migrated from .md files with WOLFIE header metadata';
ALTER TABLE `lupo_dialog_doctrine` COMMENT = 'Stores messages in dialog threads between agents and users. Supports rich message types, inline dialogs, and threading.';
ALTER TABLE `lupo_dialog_message_bodies` COMMENT = 'Stores full long-form message bodies for dialog messages.';
ALTER TABLE `lupo_dialog_threads` COMMENT = 'High-level dialog threads grouping messages across agents, tasks, and projects.';