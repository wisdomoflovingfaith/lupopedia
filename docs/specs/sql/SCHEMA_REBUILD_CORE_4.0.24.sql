-- ============================================================
-- Schema Rebuild Core Migration 4.0.24
-- Generated: 2026-02-21T03:08:58.734212Z
-- Critical Tables: 7
-- ============================================================

-- lupo_dialog_channels
CREATE TABLE `lupo_dialog_channels` (
  `channel_id` bigint NOT NULL,
  `channel_name` varchar(255) NOT NULL,
  `file_source` varchar(255) NOT NULL,
  `title` varchar(500),
  `description` text,
  `speaker` varchar(100),
  `target` varchar(100),
  `categories` json,
  `collections` json,
  `channels` json,
  `tags` json,
  `version` varchar(20),
  `status` varchar(64) DEFAULT 'published',
  `author` varchar(100),
  `created_timestamp` bigint NOT NULL,
  `modified_timestamp` bigint NOT NULL,
  `message_count` int DEFAULT 0,
  `metadata_json` json,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

UNIQUE INDEX `lupo_dialog_channels_idx_channel_name` (`channel_name`);
INDEX `lupo_dialog_channels_idx_created_timestamp` (`created_timestamp`);
INDEX `lupo_dialog_channels_idx_dialog_channels_composite` (`status`, `created_timestamp`);
INDEX `lupo_dialog_channels_idx_file_source` (`file_source`);
INDEX `lupo_dialog_channels_idx_modified_timestamp` (`modified_timestamp`);
INDEX `lupo_dialog_channels_idx_speaker` (`speaker`);
INDEX `lupo_dialog_channels_idx_status` (`status`);
INDEX `lupo_dialog_channels_idx_target` (`target`);

-- lupo_dialog_messages
CREATE TABLE `lupo_dialog_messages` (
  `dialog_message_id` bigint NOT NULL,
  `dialog_thread_id` bigint,
  `channel_id` bigint,
  `from_actor_id` bigint,
  `to_actor_id` bigint,
  `message_text` varchar(1000) NOT NULL,
  `message_type` varchar(64) NOT NULL DEFAULT 'text',
  `metadata_json` json,
  `mood_rgb` char(6),
  `mood_framework` varchar(32) NOT NULL DEFAULT 'western_analytical',
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  `message_body` mediumtext,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_dialog_messages_idx_channel` (`channel_id`);
INDEX `lupo_dialog_messages_idx_created` (`created_ymdhis`);
INDEX `lupo_dialog_messages_idx_deleted` (`is_deleted`);
INDEX `lupo_dialog_messages_idx_dialog_thread_id` (`dialog_thread_id`);
INDEX `lupo_dialog_messages_idx_message_type` (`message_type`);
INDEX `lupo_dialog_messages_idx_to_actor_id` (`to_actor_id`);
INDEX `lupo_dialog_messages_idx_updated` (`updated_ymdhis`);

-- lupo_registry
CREATE TABLE `lupo_registry` (
  `unified_registry_id` bigint NOT NULL,
  `entity_type` varchar(64) NOT NULL,
  `entity_index` bigint NOT NULL,
  `entity_key` varchar(255),
  `entity_name` varchar(255),
  `entity_table` varchar(128) NOT NULL,
  `federation_node_id` bigint NOT NULL DEFAULT 1,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  `is_active` tinyint NOT NULL DEFAULT 1,
  `is_kernel` tinyint NOT NULL DEFAULT 0,
  `metadata_json` json,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_registry_idx_entity_key` (`entity_key`);
INDEX `lupo_registry_idx_entity_type` (`entity_type`);
INDEX `lupo_registry_idx_source_table` (`entity_table`);
UNIQUE INDEX `lupo_registry_uniq_entity` (`entity_type`, `entity_index`);

-- lupo_actor_channels
CREATE TABLE `lupo_actor_channels` (
  `actor_channel_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `channel_id` bigint NOT NULL,
  `status` char(1) NOT NULL DEFAULT 'A',
  `start_date` bigint,
  `channel_color` varchar(6) NOT NULL DEFAULT 'F7FAFF',
  `last_read_ymdhis` bigint,
  `muted_until_ymdhis` bigint,
  `preferences_json` json,
  `dialog_output_file` varchar(500),
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_actor_channels_idx_actor` (`actor_id`);
INDEX `lupo_actor_channels_idx_channel` (`channel_id`);
INDEX `lupo_actor_channels_idx_created` (`created_ymdhis`);
INDEX `lupo_actor_channels_idx_deleted` (`is_deleted`);
INDEX `lupo_actor_channels_idx_status` (`status`);
INDEX `lupo_actor_channels_idx_updated` (`updated_ymdhis`);
UNIQUE INDEX `lupo_actor_channels_unq_actor_channel` (`actor_id`, `channel_id`);

-- lupo_banned_actors
CREATE TABLE `lupo_banned_actors` (
  `banned_actor_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `ip_address` varchar(45),
  `reason` varchar(500) NOT NULL,
  `banned_ymdhis` bigint NOT NULL,
  `banned_by_actor_id` bigint,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_banned_actors_idx_actor_id` (`actor_id`);
INDEX `lupo_banned_actors_idx_ip_address` (`ip_address`);
INDEX `lupo_banned_actors_idx_is_deleted` (`is_deleted`);

-- lupo_system_events
CREATE TABLE `lupo_system_events` (
  `system_event_id` bigint NOT NULL,
  `event_type` varchar(100) NOT NULL,
  `event_message` text NOT NULL,
  `event_context` text,
  `actor_id` bigint NOT NULL DEFAULT 0,
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_system_events_actor_id` (`actor_id`);
INDEX `lupo_system_events_event_type` (`event_type`);

-- lupo_actor_departments
CREATE TABLE `lupo_actor_departments` (
  `actor_department_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `department_id` bigint NOT NULL,
  `title` varchar(64),
  `created_ymdhis` bigint NOT NULL DEFAULT 0,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint,
  PRIMARY KEY (None)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INDEX `lupo_actor_departments_idx_actor` (`actor_id`);
INDEX `lupo_actor_departments_idx_department` (`department_id`);


-- ============================================================
-- Core Migration Complete
-- Total CREATE statements: 7
-- ============================================================
