-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 07, 2026 at 11:42 AM
-- Server version: 8.4.7
-- PHP Version: 8.4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lupopedia`
--

-- --------------------------------------------------------

--
-- Table structure for table `lupo_actors`
--

CREATE TABLE `lupo_actors` (
  `actor_id` bigint NOT NULL COMMENT 'Primary key for actor',
  `actor_type` enum('user','ai_agent','service','anonymous') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Type of actor',
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Stable unique identifier',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Human-readable name',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS',
  `is_active` tinyint NOT NULL DEFAULT '1' COMMENT 'Active flag',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT 'Soft delete flag',
  `deleted_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS',
  `actor_source_id` bigint DEFAULT NULL COMMENT 'ID from source table (auth_users, agents, etc.)',
  `actor_source_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `metadata` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Optional JSON for additional actor attributes',
  `adversarial_role` enum('none','structural_stress','protocol_break','doctrine_test') COLLATE utf8mb4_unicode_ci DEFAULT 'none',
  `adversarial_oversight_actor_id` bigint DEFAULT NULL COMMENT 'e.g., LILITH actor_id',
  `avatar_hash` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'SHA256 hash filename of avatar image stored under uploads/actors/YYYY/MM/'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Represents all entities that can perform actions in the system: users, AI agents, and services.';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_actor_actions`
--

CREATE TABLE `lupo_actor_actions` (
  `actor_action_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `action_type` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `entity_type` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `entity_id` bigint DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `metadata_json` json DEFAULT NULL,
  `created_ymdhis` char(14) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_actor_capabilities`
--

CREATE TABLE `lupo_actor_capabilities` (
  `actor_capability_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `domain_id` bigint NOT NULL COMMENT 'Domain scope for this capability',
  `capability_key` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Unique capability identifier (e.g., "edit_content", "manage_users")',
  `capability_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Detailed description of the capability',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
  `updated_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '1 = deleted, 0 = not deleted',
  `deleted_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when deleted',
  `scope_limitation` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'unrestricted' COMMENT 'domain, session, user, content',
  `max_calls_per_hour` int DEFAULT '0' COMMENT '0 = unlimited',
  `requires_approval` tinyint DEFAULT '0' COMMENT '1 = needs manual approval',
  `approval_agent_id` bigint DEFAULT NULL COMMENT 'Which agent must approve'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Defines domain-scoped capabilities and permissions for agents';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_actor_channels`
--

CREATE TABLE `lupo_actor_channels` (
  `actor_channel_id` bigint NOT NULL COMMENT 'Primary key for the actor-channel relationship',
  `actor_id` bigint NOT NULL COMMENT 'Reference to the actor (user/agent)',
  `channel_id` bigint NOT NULL COMMENT 'Reference to the channel',
  `status` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'A' COMMENT 'Status: A=Active, I=Inactive, etc.',
  `start_date` bigint DEFAULT NULL COMMENT 'Timestamp when actor joined the channel (YYYYMMDDHHMMSS)',
  `channel_color` varchar(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'F7FAFF' COMMENT 'Channel-specific color (6-char hex, no #)',
  `last_read_ymdhis` bigint DEFAULT NULL COMMENT 'Timestamp when actor last read messages (YYYYMMDDHHMMSS)',
  `muted_until_ymdhis` bigint DEFAULT NULL COMMENT 'Timestamp until notifications are muted (YYYYMMDDHHMMSS)',
  `preferences_json` json DEFAULT NULL COMMENT 'Additional UI/UX preferences in JSON format',
  `dialog_output_file` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Filesystem dialog log path for this actor in this channel; IDE agents write here as the mandatory fallback when database dialog tables are unavailable.',
  `created_ymdhis` bigint NOT NULL COMMENT 'Creation timestamp (YYYYMMDDHHMMSS)',
  `updated_ymdhis` bigint NOT NULL COMMENT 'Last update timestamp (YYYYMMDDHHMMSS)',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT 'Soft delete flag (1=deleted, 0=active)',
  `deleted_ymdhis` bigint DEFAULT NULL COMMENT 'Deletion timestamp (YYYYMMDDHHMMSS)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Manages the many-to-many relationship between actors and channels, including per-actor channel preferences and status.';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_actor_channel_roles`
--

CREATE TABLE `lupo_actor_channel_roles` (
  `actor_channel_role_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `channel_id` bigint NOT NULL,
  `role_key` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_ymdhis` bigint DEFAULT NULL,
  `handshake_metadata_json` json DEFAULT NULL COMMENT 'RSHAP handshake identity and synchronization data',
  `awareness_snapshot_json` json DEFAULT NULL COMMENT 'CJP Awareness Snapshot (WHO/WHAT/WHERE/WHEN/WHY/HOW/PURPOSE)',
  `protocol_completion_status` enum('pending','aal_complete','rshap_complete','cjp_complete','ready') COLLATE utf8mb4_unicode_ci DEFAULT 'pending' COMMENT 'Multi-agent protocol completion status',
  `protocol_version` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '4.0.72' COMMENT 'Protocol version used for this actor-channel relationship',
  `join_sequence_step` tinyint DEFAULT '0' COMMENT 'Current step in 10-step CJP sequence (0-10)',
  `handshake_completed_ymdhis` bigint DEFAULT NULL COMMENT 'Timestamp when RSHAP was completed',
  `awareness_completed_ymdhis` bigint DEFAULT NULL COMMENT 'Timestamp when AAL was completed',
  `cjp_completed_ymdhis` bigint DEFAULT NULL COMMENT 'Timestamp when full CJP was completed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Actor-channel relationships with RSHAP handshake and CJP awareness metadata';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_actor_collections`
--

CREATE TABLE `lupo_actor_collections` (
  `actor_collection_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL COMMENT 'User, group, agent, or persona',
  `collection_id` bigint NOT NULL COMMENT 'Collection the actor has access to',
  `access_level` enum('owner','write','read') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'read',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when granted',
  `updated_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when updated',
  `is_deleted` tinyint NOT NULL DEFAULT '0',
  `deleted_ymdhis` bigint DEFAULT NULL,
  `persistent_identity_json` json DEFAULT NULL COMMENT 'RSHAP persistent identity metadata',
  `identity_signature` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Unique identity signature for handshake verification',
  `trust_level` enum('system','verified','standard','restricted','untrusted') COLLATE utf8mb4_unicode_ci DEFAULT 'standard' COMMENT 'Trust level for multi-agent interactions',
  `emotional_geometry_baseline` json DEFAULT NULL COMMENT 'Baseline emotional geometry for agent interactions',
  `doctrine_alignment_version` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '4.0.72' COMMENT 'Version of doctrine this actor aligns with'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Actor collections with persistent identity storage for multi-agent protocols';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_actor_conflicts`
--

CREATE TABLE `lupo_actor_conflicts` (
  `actor_conflict_id` bigint NOT NULL COMMENT 'Primary key for the conflict record',
  `domain_id` bigint NOT NULL DEFAULT '1' COMMENT 'Domain/tenant this conflict belongs to',
  `actor_a_id` bigint NOT NULL,
  `actor_b_id` bigint NOT NULL,
  `conflict_type` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Type/category of the conflict',
  `conflict_summary` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Detailed description of the conflict',
  `resolution_status` enum('unresolved','resolved','ignored') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unresolved' COMMENT 'Current status of the conflict resolution',
  `resolution_summary` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'How the conflict was resolved (if applicable)',
  `resolved_by` bigint DEFAULT NULL COMMENT 'Actor who resolved the conflict (if applicable)',
  `resolved_ymdhis` bigint DEFAULT NULL COMMENT 'When the conflict was resolved (YYYYMMDDHHMMSS)',
  `severity` enum('low','medium','high','critical') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'medium' COMMENT 'Severity level of the conflict',
  `context_json` json DEFAULT NULL COMMENT 'Additional context about the conflict in JSON format',
  `created_ymdhis` bigint NOT NULL COMMENT 'Creation timestamp (YYYYMMDDHHMMSS)',
  `updated_ymdhis` bigint NOT NULL COMMENT 'Last update timestamp (YYYYMMDDHHMMSS)',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT 'Soft delete flag (1=deleted, 0=active)',
  `deleted_ymdhis` bigint DEFAULT NULL COMMENT 'Deletion timestamp (YYYYMMDDHHMMSS)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tracks conflicts between AI agents, including resolution status and history. Used for monitoring and improving agent interactions.';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_actor_departments`
--

CREATE TABLE `lupo_actor_departments` (
  `actor_department_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `department_id` bigint NOT NULL,
  `title` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_ymdhis` char(14) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_ymdhis` char(14) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT '0',
  `deleted_ymdhis` char(14) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_actor_edges`
--

CREATE TABLE `lupo_actor_edges` (
  `actor_edge_id` bigint NOT NULL,
  `domain_id` bigint NOT NULL COMMENT 'Domain scope for this relationship',
  `source_actor_id` bigint NOT NULL COMMENT 'Source agent of the relationship',
  `target_actor_id` bigint NOT NULL COMMENT 'Target agent of the relationship',
  `edge_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Type of relationship (e.g., "collaborates_with", "critiques", "balances")',
  `weight` float DEFAULT '1' COMMENT 'Strength or weight of the relationship',
  `properties` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'JSON or TOON formatted metadata for the relationship',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
  `updated_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '1 = deleted, 0 = not deleted',
  `deleted_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when deleted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Defines directed relationships and interactions between agents';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_actor_events`
--

CREATE TABLE `lupo_actor_events` (
  `actor_event_id` bigint NOT NULL COMMENT 'Primary key for actor event',
  `actor_id` bigint NOT NULL COMMENT 'Actor ID from lupo_actors',
  `session_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Session identifier',
  `tab_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tab identifier',
  `world_id` bigint DEFAULT NULL COMMENT 'World context ID',
  `world_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'World context key',
  `world_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'World context type',
  `event_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Type of actor event',
  `event_data` json DEFAULT NULL COMMENT 'Event-specific data',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Actor events with world context';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_actor_group_membership`
--

CREATE TABLE `lupo_actor_group_membership` (
  `actor_group_membership_id` bigint NOT NULL COMMENT 'Reference to actors.actor_id',
  `group_id` bigint NOT NULL COMMENT 'Reference to groups.group_id',
  `domain_id` bigint NOT NULL COMMENT 'Domain context for this membership',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when membership was created',
  `created_by` bigint DEFAULT NULL COMMENT 'actor_id who created this membership',
  `expires_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when membership expires (NULL = never)',
  `is_active` tinyint NOT NULL DEFAULT '1' COMMENT '1 = active, 0 = inactive',
  `role` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'member' COMMENT 'Role/position within the group'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Manages many-to-many relationship between actors and groups within domains. Tracks group memberships for users, AI agents, and services with role-based access control.';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_actor_handshakes`
--

CREATE TABLE `lupo_actor_handshakes` (
  `actor_handshake_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `actor_type` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'human|ai|system',
  `utc_timestamp` bigint NOT NULL COMMENT 'YYYYMMDDHHMMSS',
  `purpose` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `constraints_json` json DEFAULT NULL,
  `forbidden_actions_json` json DEFAULT NULL,
  `context` text COLLATE utf8mb4_unicode_ci,
  `expires_utc` bigint DEFAULT NULL COMMENT 'YYYYMMDDHHMMSS',
  `created_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT '0',
  `deleted_ymdhis` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_actor_meta`
--

CREATE TABLE `lupo_actor_meta` (
  `actor_meta_id` bigint UNSIGNED NOT NULL,
  `actor_id` bigint UNSIGNED NOT NULL,
  `meta_type` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_actor_moods`
--

CREATE TABLE `lupo_actor_moods` (
  `actor_id` bigint UNSIGNED NOT NULL,
  `mood_r` tinyint NOT NULL,
  `mood_g` tinyint NOT NULL,
  `mood_b` tinyint NOT NULL,
  `mood_framework` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'western_analytical',
  `timestamp_utc` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_actor_object_edges`
--

CREATE TABLE `lupo_actor_object_edges` (
  `actor_object_edge_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `target_table` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'e.g., lupo_contents, lupo_unified_truth_items, lupo_hashtags, lupo_topics, lupo_channels, etc.',
  `target_id` bigint NOT NULL,
  `edge_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'e.g., read, liked, disliked, created, commented_on, shared_from, etc.',
  `properties_json` json DEFAULT NULL,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_actor_persona_relationships`
--

CREATE TABLE `lupo_actor_persona_relationships` (
  `relationship_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `persona_id` bigint NOT NULL,
  `relationship_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `relationship_strength` decimal(5,2) DEFAULT NULL,
  `relationship_context` json DEFAULT NULL,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_actor_properties`
--

CREATE TABLE `lupo_actor_properties` (
  `actor_property_id` bigint NOT NULL,
  `actor_type` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Type of the entity',
  `actor_id` bigint NOT NULL COMMENT 'ID of the entity',
  `property_key` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Property key/name',
  `property_value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Property value',
  `created_ymdhis` bigint NOT NULL COMMENT 'Creation timestamp (YYYYMMDDHHMMSS)',
  `updated_ymdhis` bigint NOT NULL COMMENT 'Last update timestamp (YYYYMMDDHHMMSS)',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT 'Soft delete flag (1=deleted, 0=active)',
  `deleted_ymdhis` bigint DEFAULT NULL COMMENT 'Deletion timestamp (YYYYMMDDHHMMSS)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores flexible properties for various entities';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_actor_reply_templates`
--

CREATE TABLE `lupo_actor_reply_templates` (
  `actor_reply_template_id` bigint NOT NULL COMMENT 'Primary key for the template',
  `actor_id` bigint NOT NULL COMMENT 'ID of the actor (user/agent) this template belongs to',
  `template_key` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Unique key to identify this template',
  `template_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'The template content with placeholders',
  `usage_context` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Context where this template is used',
  `created_ymdhis` bigint NOT NULL COMMENT 'Creation timestamp (YYYYMMDDHHMMSS)',
  `updated_ymdhis` bigint NOT NULL COMMENT 'Last update timestamp (YYYYMMDDHHMMSS)',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT 'Soft delete flag (1=deleted, 0=active)',
  `deleted_ymdhis` bigint DEFAULT NULL COMMENT 'Deletion timestamp (YYYYMMDDHHMMSS)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores reply templates that actors (users/agents) can use to generate consistent responses.';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_actor_roles`
--

CREATE TABLE `lupo_actor_roles` (
  `actor_role_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `context_id` bigint NOT NULL DEFAULT '0',
  `department_id` bigint DEFAULT NULL,
  `role_key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_description` text COLLATE utf8mb4_unicode_ci,
  `weight` float DEFAULT '1',
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint DEFAULT NULL,
  `is_deleted` smallint NOT NULL DEFAULT '0',
  `deleted_ymdhis` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_actor_truth_edges`
--

CREATE TABLE `lupo_actor_truth_edges` (
  `actor_truth_edge_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `truth_item_id` bigint NOT NULL,
  `edge_type` enum('read','liked','disliked','created','commented_on','linked_to','referenced','viewed_multiple_times','searched_for','favorited','pinned','bookmarked','navigated_from','navigated_to','high_affinity','low_affinity','topic_cluster_member','semantic_neighbor','frequent_path') COLLATE utf8mb4_unicode_ci NOT NULL,
  `properties_json` json DEFAULT NULL,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_agents`
--

CREATE TABLE `lupo_agents` (
  `agent_id` bigint NOT NULL COMMENT 'Primary key for agent',
  `agent_key` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Canonical identifier (wolfie, lilith, maat, etc.)',
  `agent_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Human-readable name',
  `archetype` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mythic or symbolic identity',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Agent description and purpose',
  `version` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '1.0' COMMENT 'Agent version',
  `model_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_global_authority` tinyint NOT NULL DEFAULT '0' COMMENT '1 = global authority agent',
  `is_internal_only` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Internal only flag',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
  `updated_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when updated',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '1 = deleted, 0 = not deleted',
  `deleted_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when deleted',
  `avg_response_time_ms` int DEFAULT '0',
  `total_tokens_processed` bigint DEFAULT '0' COMMENT 'Total tokens processed',
  `success_rate` float DEFAULT '1' COMMENT '0.0 to 1.0',
  `cost_per_1k_tokens` decimal(10,4) DEFAULT '0.0000' COMMENT 'For cost tracking',
  `temperature` float DEFAULT '0.7',
  `top_p` float DEFAULT '1',
  `max_tokens` int DEFAULT '2048',
  `presence_penalty` float DEFAULT '0',
  `frequency_penalty` float DEFAULT '0',
  `system_prompt` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `provider` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'openai',
  `api_key_id` bigint DEFAULT NULL COMMENT 'API key reference',
  `timeout_ms` int DEFAULT '20000',
  `safety_json` json DEFAULT NULL,
  `response_format` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pono_score` decimal(3,2) DEFAULT '1.00',
  `pilau_score` decimal(3,2) DEFAULT '0.00',
  `kapakai_score` decimal(3,2) DEFAULT '0.50' COMMENT 'Ethical uncertainty marker - shoreline state between pono and pilau',
  `kapu_active` tinyint(1) DEFAULT '0',
  `kapu_until` bigint DEFAULT NULL,
  `kapu_reason` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kapu_consent_given` tinyint(1) DEFAULT '0',
  `kapu_appeal_pending` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores core agent definitions and metadata for the Lupopedia system';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_agent_context_snapshots`
--

CREATE TABLE `lupo_agent_context_snapshots` (
  `agent_context_snapshot_id` bigint NOT NULL COMMENT 'Unique identifier for the snapshot',
  `session_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Session identifier',
  `actor_id` bigint NOT NULL,
  `parent_snapshot_id` bigint DEFAULT NULL COMMENT 'For delta snapshots, references the parent full snapshot',
  `snapshot_type` enum('full','delta','checkpoint','error','user_saved') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'full' COMMENT 'Type of snapshot',
  `snapshot_purpose` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'checkpoint, error_recovery, user_save, etc.',
  `context_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Serialized context state (compressed JSON)',
  `context_summary` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Human-readable summary of the context',
  `context_metadata` json DEFAULT NULL COMMENT 'Structured metadata about the context',
  `token_count` int DEFAULT NULL COMMENT 'Approximate token count for LLM context',
  `character_count` int DEFAULT NULL COMMENT 'Raw character count before compression',
  `compressed_size` int DEFAULT NULL COMMENT 'Size in bytes after compression',
  `compression_ratio` float DEFAULT NULL COMMENT 'compressed/original ratio (smaller is better)',
  `compression_method` enum('gzip','zstd','none') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'gzip' COMMENT 'Compression algorithm used',
  `serialization_time_ms` int DEFAULT NULL COMMENT 'Time taken to serialize context (ms)',
  `compression_time_ms` int DEFAULT NULL COMMENT 'Time taken to compress (ms)',
  `related_tool_call_id` bigint DEFAULT NULL COMMENT 'Associated tool call that triggered this snapshot',
  `conversation_turn` int DEFAULT NULL COMMENT 'Conversation turn number when snapshot was taken',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when snapshot was created',
  `expires_ymdhis` bigint DEFAULT NULL COMMENT 'When this snapshot should be automatically purged',
  `is_corrupt` tinyint(1) DEFAULT '0' COMMENT '1 if snapshot failed integrity check',
  `retention_policy` enum('temporary','short_term','long_term') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'temporary' COMMENT 'How long to retain this snapshot'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores serialized agent context states for persistence, recovery, and debugging across sessions';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_agent_dependencies`
--

CREATE TABLE `lupo_agent_dependencies` (
  `agent_dependency_id` bigint NOT NULL,
  `agent_id` bigint NOT NULL COMMENT 'The agent that has dependencies',
  `depends_on_agent_id` bigint NOT NULL COMMENT 'The agent it depends on',
  `depends_on_agent_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_required` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 = must be active, 0 = optional',
  `notes` text COLLATE utf8mb4_unicode_ci COMMENT 'Why this dependency exists',
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_agent_experiences`
--

CREATE TABLE `lupo_agent_experiences` (
  `link_id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ULID',
  `agent_id` bigint NOT NULL,
  `star_id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `intensity` decimal(3,2) DEFAULT NULL COMMENT '0.00 to 1.00',
  `context_id` bigint DEFAULT NULL COMMENT 'thread/message/etc',
  `observed_ymdhis` bigint DEFAULT NULL,
  `expressed_as_rgb` char(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Legacy mood_rgb expression'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_agent_external_events`
--

CREATE TABLE `lupo_agent_external_events` (
  `external_event_id` bigint NOT NULL,
  `agent_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `source_system` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_payload_json` json DEFAULT NULL,
  `created_ymdhis` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tracks appearances and interactions of external AI agents (Cursor, JetBrains AI, Copilot, etc). Not part of the core agent registry.';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_agent_faucets`
--

CREATE TABLE `lupo_agent_faucets` (
  `agent_faucet_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Public-facing faucet name (Wolfie UI, Wolfie Code, etc.)',
  `alias_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Internal identifier for referencing this faucet',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Human-readable description of this faucet persona',
  `style_preset` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Optional style/tone preset (mythic, formal, playful, etc.)',
  `model_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `temperature` float DEFAULT NULL,
  `top_p` float DEFAULT NULL,
  `max_tokens` int DEFAULT NULL,
  `presence_penalty` float DEFAULT NULL,
  `frequency_penalty` float DEFAULT NULL,
  `system_prompt` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `safety_json` json DEFAULT NULL,
  `response_format` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `capabilities_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'JSON describing which tools/capabilities this faucet can use',
  `is_default` tinyint NOT NULL DEFAULT '0' COMMENT '1 if this is the default faucet for the agent',
  `domain_id` bigint NOT NULL DEFAULT '1' COMMENT 'Domain this faucet belongs to (multi-domain support)',
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  `deleted_ymdhis` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Persona faucets for core AI agents (Wolfie, Wolfith, etc.)';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_agent_faucet_credentials`
--

CREATE TABLE `lupo_agent_faucet_credentials` (
  `agent_faucet_credential_id` int NOT NULL,
  `faucet_id` bigint NOT NULL,
  `provider` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `api_key` varbinary(512) NOT NULL,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_agent_files`
--

CREATE TABLE `lupo_agent_files` (
  `file_id` bigint NOT NULL,
  `agent_id` bigint NOT NULL COMMENT 'References lupo_agents.agent_id',
  `file_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'metadata, system_prompt, readme, config, etc',
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Original filename',
  `file_path` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Path relative to uploads root',
  `file_hash` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'SHA256 hash of file content',
  `file_size` bigint NOT NULL COMMENT 'File size in bytes',
  `mime_type` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'MIME type',
  `upload_ymdhis` char(14) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Upload timestamp YYYYMMDDHHMMSS',
  `created_ymdhis` char(14) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Record creation timestamp',
  `updated_ymdhis` char(14) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Record update timestamp',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '1 = soft deleted',
  `deleted_ymdhis` char(14) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Deletion timestamp',
  `migrated_from_directory` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Original directory path for migration tracking'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_agent_heartbeats`
--

CREATE TABLE `lupo_agent_heartbeats` (
  `heartbeat_id` bigint NOT NULL,
  `agent_slug` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'CURSOR|CASCADE|LILITH|MONDAY_WOLFIE|etc',
  `status` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unknown' COMMENT 'operational|idle|error|unknown',
  `last_heartbeat_ymdhis` bigint NOT NULL COMMENT 'YYYYMMDDHHMMSS',
  `created_ymdhis` bigint NOT NULL COMMENT 'YYYYMMDDHHMMSS',
  `is_deleted` tinyint NOT NULL DEFAULT '0',
  `deleted_ymdhis` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Agent heartbeat/status. No FK.';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_agent_properties`
--

CREATE TABLE `lupo_agent_properties` (
  `agent_property_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL COMMENT 'Agent this property belongs to',
  `domain_id` bigint NOT NULL COMMENT 'Domain scope for this property',
  `property_key` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Property identifier (e.g., "ui_preferences", "api_settings")',
  `property_value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'JSON or TOON formatted property value',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
  `updated_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '1 = deleted, 0 = not deleted',
  `deleted_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when deleted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores domain-scoped key-value properties for agents';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_agent_registry`
--

CREATE TABLE `lupo_agent_registry` (
  `agent_registry_id` bigint NOT NULL,
  `agent_registry_parent_id` bigint DEFAULT NULL COMMENT 'if this is a alias',
  `code` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `layer` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `is_required` tinyint NOT NULL DEFAULT '0',
  `is_active` tinyint NOT NULL DEFAULT '0',
  `is_kernel` tinyint NOT NULL DEFAULT '0',
  `dedicated_slot` int DEFAULT NULL,
  `created_ymdhis` bigint NOT NULL,
  `classification_json` json DEFAULT NULL COMMENT 'Agent classification and routing identity metadata (agent_class, subclass, routing_bias, capabilities)',
  `metadata` json NOT NULL,
  `agent_class` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'production',
  `can_use_humor` tinyint NOT NULL DEFAULT '0',
  `can_use_emotion` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_agent_tool_calls`
--

CREATE TABLE `lupo_agent_tool_calls` (
  `agent_tool_call_id` bigint NOT NULL,
  `agent_id` bigint NOT NULL COMMENT 'Which agent initiated the call',
  `faucet_id` bigint DEFAULT NULL COMMENT 'Which faucet was used (if any)',
  `domain_id` bigint NOT NULL COMMENT 'Domain context of the call',
  `tool_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Name of the tool or action invoked',
  `action_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Type of action (llm_call, faucet_spawn, search, codegen, etc.)',
  `input_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Serialized input parameters',
  `output_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Serialized output or result',
  `provider` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'LLM provider (openai, anthropic, google, deepseek, etc.)',
  `model_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Model used for this call',
  `tokens_prompt` int DEFAULT '0',
  `tokens_completion` int DEFAULT '0',
  `tokens_total` int DEFAULT '0',
  `cost_usd` decimal(10,6) DEFAULT '0.000000',
  `latency_ms` int DEFAULT '0',
  `status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'success' COMMENT 'success, error, timeout, rejected',
  `error_message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `parent_call_id` bigint DEFAULT NULL COMMENT 'If this call was spawned by another call',
  `thread_id` bigint DEFAULT NULL COMMENT 'Dialog thread this call belongs to',
  `message_id` bigint DEFAULT NULL COMMENT 'Dialog message that triggered this call',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when call started',
  `completed_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when call finished'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Logs all internal tool calls, LLM calls, and faucet spawns for agent orchestration.';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_agent_versions`
--

CREATE TABLE `lupo_agent_versions` (
  `agent_version_id` bigint NOT NULL,
  `agent_id` bigint NOT NULL,
  `version_label` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `semver_major` int DEFAULT '0',
  `semver_minor` int DEFAULT '0',
  `semver_patch` int DEFAULT '0',
  `version_notes` text COLLATE utf8mb4_unicode_ci,
  `version_hash` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `previous_version_id` bigint DEFAULT NULL,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` smallint NOT NULL DEFAULT '0',
  `deleted_ymdhis` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_aliases`
--

CREATE TABLE `lupo_aliases` (
  `id` int UNSIGNED NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alias` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alias_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'semantic',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_analytics_campaign_vars`
--

CREATE TABLE `lupo_analytics_campaign_vars` (
  `campaign_var_id` bigint NOT NULL,
  `period` enum('daily','monthly','yearly','total','custom') COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_ymd` bigint DEFAULT NULL,
  `yearmonth` int DEFAULT NULL,
  `year` int DEFAULT NULL,
  `campaign_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `campaign_value` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `metadata_json` json DEFAULT NULL,
  `created_ymdhis` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_analytics_referers_periods`
--

CREATE TABLE `lupo_analytics_referers_periods` (
  `analytics_referers_period_id` bigint NOT NULL,
  `content_id` bigint NOT NULL DEFAULT '0',
  `url_path` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `referer_content_id` bigint NOT NULL DEFAULT '0',
  `referer_url_path` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `parent_id` bigint NOT NULL DEFAULT '0',
  `level` int NOT NULL DEFAULT '1',
  `group_id` bigint NOT NULL DEFAULT '0',
  `period_type` enum('daily','monthly') COLLATE utf8mb4_unicode_ci NOT NULL,
  `period_date` bigint NOT NULL COMMENT 'YYYYMMDD for daily, YYYYMM for monthly',
  `visits` int NOT NULL DEFAULT '0',
  `direct_visits` int NOT NULL DEFAULT '0',
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_analytics_visits`
--

CREATE TABLE `lupo_analytics_visits` (
  `analytics_visit_id` bigint NOT NULL COMMENT 'Primary key for the visit tracking record',
  `session_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Session identifier',
  `actor_id` bigint NOT NULL DEFAULT '0' COMMENT 'Actor ID (0 = anonymous)',
  `content_id` bigint DEFAULT NULL COMMENT 'Content being viewed (NULL for non-content pages)',
  `federations_node_id` bigint NOT NULL,
  `url_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Normalized URL path of the page view',
  `referer_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Full referer URL for this page view',
  `referer_domain` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Normalized referer domain',
  `referer_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Normalized referer path',
  `came_from` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Original entry referer for the session',
  `first_seen_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when this page was first seen in this session',
  `last_seen_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when this page was last seen in this session',
  `view_count` int NOT NULL DEFAULT '1' COMMENT 'Number of times this page was viewed in this session',
  `seconds_active` int NOT NULL DEFAULT '0' COMMENT 'Total seconds spent on this page during this session',
  `user_agent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'User agent for this page view',
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'IP address for this page view',
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Temporary rolling table for active-session page views; aggregated into daily/monthly tables on session expiration';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_analytics_visits_daily`
--

CREATE TABLE `lupo_analytics_visits_daily` (
  `analytics_visits_daily_id` bigint NOT NULL COMMENT 'Primary key for daily page visit statistics',
  `content_id` bigint NOT NULL DEFAULT '0' COMMENT 'Content ID of the visited page (0 = non-content page)',
  `url_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Normalized URL path of the visited page',
  `group_id` bigint NOT NULL DEFAULT '0' COMMENT 'Group associated with this content',
  `date_ymd` bigint NOT NULL COMMENT 'UTC YYYYMMDD representing the daily bucket',
  `visits` int NOT NULL DEFAULT '0' COMMENT 'Total number of visits to this page on this day',
  `unique_sessions` int NOT NULL DEFAULT '0' COMMENT 'Number of unique sessions that viewed this page',
  `unique_actors` int NOT NULL DEFAULT '0' COMMENT 'Number of unique logged-in actors that viewed this page',
  `direct_visits` int NOT NULL DEFAULT '0' COMMENT 'Visits with no referer (entry points)',
  `internal_visits` int NOT NULL DEFAULT '0' COMMENT 'Visits from internal referers',
  `entry_count` int NOT NULL DEFAULT '0' COMMENT 'Number of times this page was the first page in a session',
  `exit_count` int NOT NULL DEFAULT '0' COMMENT 'Number of times this page was the last page in a session',
  `total_seconds` int NOT NULL DEFAULT '0' COMMENT 'Total time spent on this page across all sessions',
  `avg_seconds` int NOT NULL DEFAULT '0' COMMENT 'Average time spent on this page',
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Daily aggregated page-level visit statistics';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_analytics_visits_monthly`
--

CREATE TABLE `lupo_analytics_visits_monthly` (
  `analytics_visits_monthly_id` bigint NOT NULL COMMENT 'Primary key for monthly page visit statistics',
  `content_id` bigint NOT NULL DEFAULT '0' COMMENT 'Content ID of the visited page (0 = non-content page)',
  `url_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Normalized URL path of the visited page',
  `group_id` bigint NOT NULL DEFAULT '0' COMMENT 'Group associated with this content',
  `date_ym` bigint NOT NULL COMMENT 'UTC YYYYMM representing the monthly bucket',
  `visits` int NOT NULL DEFAULT '0',
  `unique_sessions` int NOT NULL DEFAULT '0',
  `unique_actors` int NOT NULL DEFAULT '0',
  `direct_visits` int NOT NULL DEFAULT '0',
  `internal_visits` int NOT NULL DEFAULT '0',
  `entry_count` int NOT NULL DEFAULT '0',
  `exit_count` int NOT NULL DEFAULT '0',
  `total_seconds` int NOT NULL DEFAULT '0',
  `avg_seconds` int NOT NULL DEFAULT '0',
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Monthly aggregated page-level visit statistics';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_analytics_visits_periods`
--

CREATE TABLE `lupo_analytics_visits_periods` (
  `analytics_visits_period_id` bigint NOT NULL,
  `content_id` bigint NOT NULL DEFAULT '0',
  `url_path` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `group_id` bigint NOT NULL DEFAULT '0',
  `period_type` enum('daily','monthly') COLLATE utf8mb4_unicode_ci NOT NULL,
  `period_date` bigint NOT NULL COMMENT 'YYYYMMDD for daily, YYYYMM for monthly',
  `visits` int NOT NULL DEFAULT '0',
  `unique_sessions` int NOT NULL DEFAULT '0',
  `unique_actors` int NOT NULL DEFAULT '0',
  `direct_visits` int NOT NULL DEFAULT '0',
  `internal_visits` int NOT NULL DEFAULT '0',
  `entry_count` int NOT NULL DEFAULT '0',
  `exit_count` int NOT NULL DEFAULT '0',
  `total_seconds` int NOT NULL DEFAULT '0',
  `avg_seconds` int NOT NULL DEFAULT '0',
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_anubis_deletion_log`
--

CREATE TABLE `lupo_anubis_deletion_log` (
  `anubis_deletion_id` bigint UNSIGNED NOT NULL,
  `table_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `record_id` bigint UNSIGNED NOT NULL,
  `deleted_ymdhis` bigint UNSIGNED NOT NULL,
  `deletion_type` enum('path_not_taken','merged','moved','updated','consolidated','orphan_adopted','manual_override','system_cleanup','superseded','deprecated','temporal_context_expired') COLLATE utf8mb4_unicode_ci NOT NULL,
  `replacement_table` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `replacement_id` bigint UNSIGNED DEFAULT NULL,
  `anubis_operator` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `context_json` json DEFAULT NULL,
  `notes` longtext COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_anubis_events`
--

CREATE TABLE `lupo_anubis_events` (
  `anubis_event_id` bigint NOT NULL,
  `event_type` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `table_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `row_id` bigint NOT NULL,
  `timestamp_utc` char(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `agent` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `details_json` longtext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_anubis_mirrored`
--

CREATE TABLE `lupo_anubis_mirrored` (
  `anubis_mirrored_id` bigint NOT NULL,
  `table_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_id` bigint NOT NULL,
  `mirrored_json` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `timestamp_utc` char(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `agent` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lineage_chain` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_anubis_orphaned`
--

CREATE TABLE `lupo_anubis_orphaned` (
  `anubis_orphaned_id` bigint NOT NULL,
  `table_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `orphan_id` bigint NOT NULL,
  `timestamp_utc` char(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_anubis_redirects`
--

CREATE TABLE `lupo_anubis_redirects` (
  `anubis_redirect_id` bigint NOT NULL,
  `table_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `old_id` bigint NOT NULL,
  `new_id` bigint NOT NULL,
  `timestamp_utc` char(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `agent` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_anubis_revised`
--

CREATE TABLE `lupo_anubis_revised` (
  `anubis_revised_id` bigint NOT NULL,
  `table_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `row_id` bigint NOT NULL,
  `timestamp_utc` char(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `agent` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revision_json` longtext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_api_clients`
--

CREATE TABLE `lupo_api_clients` (
  `api_client_id` bigint NOT NULL COMMENT 'Primary key for API client record',
  `actor_id` bigint NOT NULL DEFAULT '0' COMMENT 'Owner of this client (0 = system)',
  `client_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Public client identifier',
  `client_secret` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Hashed client secret (never store raw secret)',
  `client_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Human-readable name of the client',
  `client_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Description of the integration or application',
  `scopes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Comma-separated list of allowed scopes',
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 = active, 0 = disabled',
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  `expires_ymdhis` bigint DEFAULT NULL COMMENT 'Expiration timestamp (NULL = never)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Registered API clients for external integrations and service accounts';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_api_rate_limits`
--

CREATE TABLE `lupo_api_rate_limits` (
  `api_rate_limit_id` bigint NOT NULL COMMENT 'Primary key for API rate limit record',
  `domain_id` bigint NOT NULL DEFAULT '1' COMMENT 'Domain/tenant identifier',
  `api_token_id` bigint NOT NULL DEFAULT '0' COMMENT 'Token being rate-limited (0 = not token-based)',
  `actor_id` bigint NOT NULL DEFAULT '0' COMMENT 'Actor being rate-limited (0 = not actor-based)',
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'IP address being rate-limited',
  `endpoint` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Specific endpoint being rate-limited (NULL = global)',
  `window_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS representing the start of the rate-limit window',
  `request_count` int NOT NULL DEFAULT '0' COMMENT 'Number of requests in this window',
  `limit_value` int NOT NULL DEFAULT '0' COMMENT 'Maximum allowed requests in this window',
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tracks API rate limit counters for tokens, actors, IPs, and endpoints';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_api_tokens`
--

CREATE TABLE `lupo_api_tokens` (
  `api_token_id` bigint NOT NULL COMMENT 'Primary key for API token record',
  `domain_id` bigint NOT NULL DEFAULT '1' COMMENT 'Domain/tenant identifier',
  `actor_id` bigint NOT NULL DEFAULT '0' COMMENT 'Actor who owns this token (0 = system token)',
  `token_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Hashed token value (never store raw token)',
  `token_label` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Human-readable label for this token',
  `scopes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Comma-separated list of scopes/permissions for this token',
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 = active, 0 = revoked',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when token was created',
  `expires_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when token expires (NULL = never)',
  `last_used_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when token was last used',
  `created_ip` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'IP address where token was created',
  `last_used_ip` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'IP address where token was last used',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Optional notes or metadata about this token'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores API tokens for actors, modules, and system integrations';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_api_token_logs`
--

CREATE TABLE `lupo_api_token_logs` (
  `api_token_log_id` bigint NOT NULL COMMENT 'Primary key for API token usage log entry',
  `domain_id` bigint NOT NULL DEFAULT '1' COMMENT 'Domain/tenant identifier',
  `api_token_id` bigint NOT NULL COMMENT 'ID of the token used (no FK)',
  `actor_id` bigint NOT NULL DEFAULT '0' COMMENT 'Actor associated with the token (0 = system)',
  `endpoint` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'API endpoint accessed',
  `http_method` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'HTTP method used (GET, POST, etc.)',
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'IP address of the requester',
  `user_agent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'User agent of the requester',
  `status_code` int NOT NULL COMMENT 'HTTP response status code',
  `request_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when the request occurred',
  `duration_ms` int DEFAULT NULL COMMENT 'Execution time in milliseconds'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Audit log of all API token usage events';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_api_webhooks`
--

CREATE TABLE `lupo_api_webhooks` (
  `api_webhook_id` bigint NOT NULL COMMENT 'Primary key for webhook registration',
  `domain_id` bigint NOT NULL DEFAULT '1' COMMENT 'Domain/tenant identifier',
  `actor_id` bigint NOT NULL DEFAULT '0' COMMENT 'Actor who created this webhook (0 = system)',
  `module_id` bigint NOT NULL DEFAULT '0' COMMENT 'Module associated with this webhook (0 = global)',
  `endpoint_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Target URL to receive webhook POST requests',
  `secret_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Hashed secret used to sign webhook payloads',
  `event_types` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Comma-separated list of event types this webhook listens to',
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 = active, 0 = disabled',
  `max_retries` int NOT NULL DEFAULT '5' COMMENT 'Maximum number of retry attempts for failed deliveries',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when webhook was created',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when webhook was last updated',
  `expires_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when webhook expires (NULL = never)',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Optional notes or metadata about this webhook'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Registered webhook endpoints for external integrations';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_artifacts`
--

CREATE TABLE `lupo_artifacts` (
  `artifact_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `utc_timestamp` bigint NOT NULL COMMENT 'YYYYMMDDHHMMSS',
  `type` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'dialog|changelog|schema|lore|humor|protocol|fork_justification',
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT '0',
  `deleted_ymdhis` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_atoms`
--

CREATE TABLE `lupo_atoms` (
  `atom_id` bigint NOT NULL,
  `atom_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `context_id` bigint NOT NULL,
  `is_authoritative` tinyint NOT NULL DEFAULT '0',
  `value_json` json DEFAULT NULL,
  `summary` text COLLATE utf8mb4_unicode_ci,
  `tags` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_ymd` bigint NOT NULL DEFAULT '0',
  `updated_ymd` bigint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_audit_log`
--

CREATE TABLE `lupo_audit_log` (
  `audit_log_id` bigint NOT NULL,
  `channel_id` bigint NOT NULL,
  `entity_type` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Type of entity (actor, agent, content, etc.)',
  `entity_id` bigint NOT NULL COMMENT 'ID of the entity',
  `event_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Type of event that occurred',
  `table_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Name of the related database table',
  `table_id` bigint DEFAULT NULL COMMENT 'ID of the related record in the table',
  `payload_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Additional event data in JSON format',
  `created_ymdhis` bigint NOT NULL COMMENT 'Creation timestamp (YYYYMMDDHHMMSS)',
  `updated_ymdhis` bigint NOT NULL COMMENT 'Last update timestamp (YYYYMMDDHHMMSS)',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT 'Soft delete flag (1=deleted, 0=active)',
  `deleted_ymdhis` bigint DEFAULT NULL COMMENT 'Deletion timestamp (YYYYMMDDHHMMSS)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Audit trail for tracking system events and changes';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_auth_audit_log`
--

CREATE TABLE `lupo_auth_audit_log` (
  `id` bigint NOT NULL COMMENT 'Primary key for audit log entry',
  `user_id` bigint DEFAULT NULL COMMENT 'Reference to lupo_auth_users.auth_user_id',
  `crafty_operator_id` int DEFAULT NULL COMMENT 'Reference to livehelp_operators.operatorid',
  `event_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'login, logout, session_created, session_destroyed, etc.',
  `system_context` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'lupopedia, crafty_syntax, unified, admin',
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Client IP address',
  `user_agent` text COLLATE utf8mb4_unicode_ci COMMENT 'Client user agent string',
  `event_data` json DEFAULT NULL COMMENT 'Additional event metadata',
  `success` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=success, 0=failure',
  `error_message` text COLLATE utf8mb4_unicode_ci COMMENT 'Error details if success=0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Event timestamp',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last update timestamp'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Authentication event audit trail';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_auth_providers`
--

CREATE TABLE `lupo_auth_providers` (
  `auth_provider_id` bigint NOT NULL,
  `provider_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Unique provider name (e.g., google, github)',
  `client_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'OAuth client ID',
  `client_secret` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Encrypted at rest in lupopedia-config.php',
  `scopes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Space-separated list of OAuth scopes',
  `authorization_endpoint` varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'OAuth authorization URL',
  `token_endpoint` varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'OAuth token URL',
  `userinfo_endpoint` varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Optional userinfo endpoint',
  `jwks_uri` varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Optional JWKS URI for key rotation',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when provider was created',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when provider was last updated',
  `is_active` tinyint NOT NULL DEFAULT '1' COMMENT '1 = active, 0 = inactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores OAuth provider configurations. SECURITY: client_secret must be encrypted at rest in lupopedia-config.php (outside web root).';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_auth_users`
--

CREATE TABLE `lupo_auth_users` (
  `auth_user_id` bigint NOT NULL,
  `username` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(42) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'NULL for OAuth users',
  `auth_provider` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'NULL for local users',
  `provider_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Provider-specific user ID',
  `profile_image_url` varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_login_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS',
  `is_active` tinyint NOT NULL DEFAULT '1' COMMENT '1 = active, 0 = inactive',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '1 = deleted, 0 = not deleted',
  `deleted_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when user was deleted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores user accounts, supporting both local and OAuth authentication.';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_calibration_impacts`
--

CREATE TABLE `lupo_calibration_impacts` (
  `id` bigint UNSIGNED NOT NULL,
  `calibration_id` bigint UNSIGNED NOT NULL COMMENT 'Reference to lupo_emotional_geometry_calibrations.id',
  `impact_type` enum('agent_behavior','communication_tone','system_harmony','conflict_reduction') COLLATE utf8mb4_unicode_ci NOT NULL,
  `impact_measurement` decimal(5,4) NOT NULL COMMENT 'Quantified impact (0.0000-1.0000)',
  `measurement_method` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'How impact was measured',
  `before_metrics_json` json DEFAULT NULL COMMENT 'Metrics before calibration',
  `after_metrics_json` json DEFAULT NULL COMMENT 'Metrics after calibration',
  `observation_period_hours` int UNSIGNED DEFAULT '24' COMMENT 'Observation period length',
  `measured_ymdhis` bigint NOT NULL COMMENT 'When impact was measured',
  `impact_version` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '4.0.75' COMMENT 'Impact tracking version'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Calibration Impact Tracking - Measuring effectiveness of emotional geometry adjustments';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_channels`
--

CREATE TABLE `lupo_channels` (
  `channel_id` bigint NOT NULL COMMENT 'Primary key for channel',
  `federation_node_id` bigint NOT NULL COMMENT 'Domain/tenant identifier',
  `created_by_actor_id` bigint NOT NULL COMMENT 'who made this channel',
  `default_actor_id` bigint NOT NULL DEFAULT '1' COMMENT 'Default actor ID',
  `department_id` bigint NOT NULL DEFAULT '1',
  `channel_key` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'URL-friendly identifier (slug)',
  `channel_slug` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'channel_key' COMMENT 'well if they think it exists i guess i will make it',
  `channel_type` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'chat_room',
  `language` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en',
  `channel_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Human-readable channel name',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Channel description',
  `website_link` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `metadata_json` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'JSON metadata for the channel',
  `status_flag` tinyint NOT NULL DEFAULT '1' COMMENT 'Status flag (1=active, 0=inactive)',
  `end_ymdhis` bigint DEFAULT NULL COMMENT 'Channel end timestamp (YYYYMMDDHHMMSS, NULL if ongoing)',
  `duration_seconds` int DEFAULT NULL COMMENT 'Duration of the channel in seconds (if ended)',
  `created_ymdhis` bigint NOT NULL COMMENT 'Creation timestamp (YYYYMMDDHHMMSS)',
  `updated_ymdhis` bigint NOT NULL COMMENT 'Last update timestamp (YYYYMMDDHHMMSS)',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT 'Soft delete flag (1=deleted, 0=active)',
  `deleted_ymdhis` bigint DEFAULT NULL COMMENT 'Deletion timestamp (YYYYMMDDHHMMSS)',
  `aal_metadata_json` json DEFAULT NULL COMMENT 'Agent Awareness Layer metadata for WHO/WHAT/WHERE/WHEN/WHY/HOW/PURPOSE',
  `fleet_composition_json` json DEFAULT NULL COMMENT 'Current fleet of agents in this channel with their roles',
  `awareness_version` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '4.0.72' COMMENT 'AAL protocol version for this channel',
  `channel_number` int DEFAULT NULL COMMENT 'Channel number (0-9 reserved for system)',
  `parent_channel_id` bigint DEFAULT NULL COMMENT 'Reference to parent channel for hierarchy',
  `is_kernel` tinyint NOT NULL DEFAULT '0' COMMENT 'Part of system kernel (Channel 0)',
  `boot_sequence_order` int DEFAULT NULL COMMENT 'Order in kernel boot sequence'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Communication channels with Agent Awareness Layer (AAL) metadata for multi-agent coordination';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_channel_boot_detail`
--

CREATE TABLE `lupo_channel_boot_detail` (
  `detail_id` bigint NOT NULL COMMENT 'Primary key for boot detail',
  `boot_id` bigint NOT NULL COMMENT 'Reference to lupo_channel_boot_log.boot_id',
  `channel_id` bigint NOT NULL COMMENT 'Reference to lupo_channels.channel_id',
  `load_start_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Channel load start',
  `load_end_time` timestamp NULL DEFAULT NULL COMMENT 'Channel load completion',
  `load_status` enum('started','loading','completed','failed','skipped') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'started',
  `content_items_loaded` int NOT NULL DEFAULT '0' COMMENT 'Content items successfully loaded',
  `total_content_items` int NOT NULL DEFAULT '0' COMMENT 'Total content items in channel',
  `load_duration_ms` int DEFAULT NULL COMMENT 'Load duration in milliseconds',
  `error_message` text COLLATE utf8mb4_unicode_ci COMMENT 'Load error details',
  `created_ymdhis` bigint NOT NULL DEFAULT '0' COMMENT 'Creation timestamp (YYYYMMDDHHMMSS)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Individual channel boot tracking';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_channel_boot_log`
--

CREATE TABLE `lupo_channel_boot_log` (
  `boot_id` bigint NOT NULL COMMENT 'Primary key for boot event',
  `actor_id` bigint DEFAULT NULL COMMENT 'Actor that initiated boot sequence',
  `session_id` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Session identifier',
  `boot_start_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Boot sequence start',
  `boot_end_time` timestamp NULL DEFAULT NULL COMMENT 'Boot sequence completion',
  `boot_status` enum('started','in_progress','completed','failed','interrupted') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'started',
  `channels_loaded` int NOT NULL DEFAULT '0' COMMENT 'Number of channels successfully loaded',
  `total_channels` int NOT NULL DEFAULT '0' COMMENT 'Total channels in boot sequence',
  `error_details` json DEFAULT NULL COMMENT 'Boot error information',
  `performance_metrics` json DEFAULT NULL COMMENT 'Boot performance data',
  `created_ymdhis` bigint NOT NULL DEFAULT '0' COMMENT 'Creation timestamp (YYYYMMDDHHMMSS)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Channel boot sequence tracking';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_channel_escalations`
--

CREATE TABLE `lupo_channel_escalations` (
  `escalation_id` bigint NOT NULL,
  `channel_id` bigint NOT NULL,
  `thread_id` bigint DEFAULT NULL,
  `actor_id` bigint DEFAULT NULL,
  `escalated_to_actor_id` bigint DEFAULT NULL,
  `escalation_reason` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `metadata_json` json DEFAULT NULL,
  `created_ymdhis` char(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_ymdhis` char(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT '0',
  `deleted_ymdhis` char(14) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Channel escalation events';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_channel_escalation_rules`
--

CREATE TABLE `lupo_channel_escalation_rules` (
  `rule_id` bigint NOT NULL,
  `channel_id` bigint NOT NULL,
  `rule_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rule_description` text COLLATE utf8mb4_unicode_ci,
  `rule_type` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rule_config_json` json DEFAULT NULL,
  `created_ymdhis` char(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_ymdhis` char(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT '0',
  `deleted_ymdhis` char(14) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Channel escalation rule definitions';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_channel_files`
--

CREATE TABLE `lupo_channel_files` (
  `file_id` bigint NOT NULL,
  `channel_id` bigint NOT NULL COMMENT 'References lupo_channels.channel_id',
  `file_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'metadata, contents, actors, context, threads, etc',
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Original filename',
  `file_path` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Path relative to uploads root',
  `file_hash` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'SHA256 hash of file content',
  `file_size` bigint NOT NULL COMMENT 'File size in bytes',
  `mime_type` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'MIME type',
  `upload_ymdhis` char(14) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Upload timestamp YYYYMMDDHHMMSS',
  `created_ymdhis` char(14) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Record creation timestamp',
  `updated_ymdhis` char(14) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Record update timestamp',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '1 = soft deleted',
  `deleted_ymdhis` char(14) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Deletion timestamp',
  `migrated_from_directory` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Original directory path for migration tracking'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_channel_logs`
--

CREATE TABLE `lupo_channel_logs` (
  `channel_log_id` bigint NOT NULL,
  `channel_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `role_type` enum('captain','administrator','monitor') COLLATE utf8mb4_unicode_ci NOT NULL,
  `log_type_id` bigint NOT NULL,
  `log_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `metadata_json` json DEFAULT NULL,
  `created_ymdhis` char(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_ymdhis` char(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT '0',
  `pinned` tinyint NOT NULL DEFAULT '0',
  `deleted_ymdhis` char(14) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_channel_log_types`
--

CREATE TABLE `lupo_channel_log_types` (
  `log_type_id` bigint NOT NULL,
  `type_key` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_system` tinyint NOT NULL DEFAULT '0',
  `created_ymdhis` char(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_ymdhis` char(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT '0',
  `deleted_ymdhis` char(14) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_channel_roles`
--

CREATE TABLE `lupo_channel_roles` (
  `channel_role_id` bigint NOT NULL,
  `channel_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `role_type` enum('captain','administrator','monitor') COLLATE utf8mb4_unicode_ci NOT NULL,
  `metadata_json` json DEFAULT NULL,
  `created_ymdhis` char(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_ymdhis` char(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT '0',
  `deleted_ymdhis` char(14) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Channel role assignments: captain, administrator, monitor';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_channel_state`
--

CREATE TABLE `lupo_channel_state` (
  `channel_state_id` bigint NOT NULL,
  `channel_id` bigint UNSIGNED NOT NULL,
  `active_actors_json` json DEFAULT NULL,
  `speaker_actors_json` json DEFAULT NULL,
  `observer_actors_json` json DEFAULT NULL,
  `layers_enabled_json` json DEFAULT NULL,
  `operational_mode` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emotional_state_json` json DEFAULT NULL,
  `mood_framework` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'western_analytical',
  `recent_topics_json` json DEFAULT NULL,
  `semantic_weight` float DEFAULT '0',
  `trend_score` float DEFAULT '0',
  `last_activity_ymdhis` bigint UNSIGNED DEFAULT NULL,
  `context_vector` blob,
  `routing_rules` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `edge_visibility` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `retention_policy` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `decay_policy` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `archive_flag` tinyint(1) DEFAULT '0',
  `metadata_json` json DEFAULT NULL,
  `created_ymdhis` bigint UNSIGNED NOT NULL,
  `updated_ymdhis` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_cip_analytics`
--

CREATE TABLE `lupo_cip_analytics` (
  `id` bigint UNSIGNED NOT NULL,
  `event_id` bigint UNSIGNED NOT NULL COMMENT 'Reference to lupo_cip_events.id',
  `defensiveness_index` decimal(5,4) NOT NULL DEFAULT '0.0000' COMMENT 'DI: 0.0000-1.0000 scale',
  `integration_velocity` decimal(5,4) NOT NULL DEFAULT '0.0000' COMMENT 'IV: 0.0000-1.0000 scale',
  `architectural_impact_score` decimal(5,4) NOT NULL DEFAULT '0.0000' COMMENT 'AIS: 0.0000-1.0000 scale',
  `doctrine_propagation_depth` tinyint UNSIGNED NOT NULL DEFAULT '0' COMMENT 'DPD: 0-10 depth levels',
  `critique_source_weight` decimal(5,4) NOT NULL DEFAULT '0.5000' COMMENT 'Source credibility weight',
  `subsystem_impact_json` json DEFAULT NULL COMMENT 'Impact analysis per subsystem',
  `trend_analysis_json` json DEFAULT NULL COMMENT 'Historical trend data',
  `calculated_ymdhis` bigint NOT NULL COMMENT 'When analytics were calculated',
  `recalculated_ymdhis` bigint DEFAULT NULL COMMENT 'Last recalculation timestamp',
  `analytics_version` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '4.0.75' COMMENT 'Analytics engine version'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='CIP Analytics Engine - Aggregated metrics and trend analysis';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_cip_propagation_tracking`
--

CREATE TABLE `lupo_cip_propagation_tracking` (
  `id` bigint UNSIGNED NOT NULL,
  `cip_event_id` bigint UNSIGNED NOT NULL COMMENT 'Root CIP event',
  `propagation_level` tinyint UNSIGNED NOT NULL COMMENT 'Depth level (0-10)',
  `affected_subsystem` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Subsystem affected at this level',
  `propagation_type` enum('doctrine','emotional_geometry','agent_behavior','system_config') COLLATE utf8mb4_unicode_ci NOT NULL,
  `change_description` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'What changed at this level',
  `propagation_strength` decimal(5,4) NOT NULL DEFAULT '1.0000' COMMENT 'Strength of propagation',
  `completion_status` enum('pending','in_progress','completed','blocked','failed') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `dependencies_json` json DEFAULT NULL COMMENT 'Dependencies for this propagation step',
  `started_ymdhis` bigint DEFAULT NULL COMMENT 'When propagation started',
  `completed_ymdhis` bigint DEFAULT NULL COMMENT 'When propagation completed',
  `propagation_version` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '4.0.75' COMMENT 'Propagation tracking version'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='CIP Propagation Tracking - Visualizing depth and breadth of critique integration';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_cip_trends`
--

CREATE TABLE `lupo_cip_trends` (
  `id` bigint UNSIGNED NOT NULL,
  `trend_period` enum('hourly','daily','weekly','monthly') COLLATE utf8mb4_unicode_ci NOT NULL,
  `period_start_ymdhis` bigint NOT NULL COMMENT 'Start of aggregation period',
  `period_end_ymdhis` bigint NOT NULL COMMENT 'End of aggregation period',
  `avg_defensiveness_index` decimal(5,4) NOT NULL DEFAULT '0.0000',
  `avg_integration_velocity` decimal(5,4) NOT NULL DEFAULT '0.0000',
  `avg_architectural_impact` decimal(5,4) NOT NULL DEFAULT '0.0000',
  `total_events` int UNSIGNED NOT NULL DEFAULT '0',
  `high_impact_events` int UNSIGNED NOT NULL DEFAULT '0' COMMENT 'AIS > 0.7000',
  `doctrine_updates_triggered` int UNSIGNED NOT NULL DEFAULT '0',
  `trend_metadata_json` json DEFAULT NULL COMMENT 'Additional trend analysis',
  `calculated_ymdhis` bigint NOT NULL COMMENT 'When trend was calculated'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='CIP Trends - Aggregated analytics over time periods';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_collections`
--

CREATE TABLE `lupo_collections` (
  `collection_id` bigint NOT NULL COMMENT 'Primary key for collection',
  `federations_node_id` bigint NOT NULL COMMENT 'Domain this collection belongs to',
  `actor_id` bigint UNSIGNED DEFAULT NULL COMMENT 'Owner actor of this collection, if actor-owned',
  `group_id` bigint DEFAULT NULL COMMENT 'Owning group, if group-owned',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Display name of the collection',
  `slug` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'URL-friendly identifier, unique per domain',
  `color` char(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '666666' COMMENT 'Hex color code for the collection (6 hex characters, no hash)',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Optional description of the collection',
  `sort_order` int DEFAULT '0' COMMENT 'Manual sort order within parent container',
  `properties` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'JSON-encoded key-value store',
  `published_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when published',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '1 = deleted, 0 = not deleted',
  `deleted_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when deleted',
  `parent_id` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Organizational containers for content with metadata and theming.';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_collection_tabs`
--

CREATE TABLE `lupo_collection_tabs` (
  `collection_tab_id` bigint NOT NULL,
  `collection_tab_parent_id` bigint DEFAULT NULL COMMENT 'Parent tab ID for hierarchical nesting, NULL for root level',
  `collection_id` bigint NOT NULL COMMENT 'Reference to the parent collection',
  `federations_node_id` bigint NOT NULL COMMENT 'Domain this tab belongs to (via collection)',
  `group_id` bigint DEFAULT NULL COMMENT 'Owning group, if group-owned',
  `user_id` bigint DEFAULT NULL COMMENT 'Owning user, if user-owned',
  `sort_order` int DEFAULT '0' COMMENT 'Order of display within parent container',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Display name of the tab',
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'URL-friendly identifier, unique within collection',
  `color` char(6) COLLATE utf8mb4_unicode_ci DEFAULT '4caf50' COMMENT 'Hex color code (6 characters, no hash)',
  `description` text COLLATE utf8mb4_unicode_ci COMMENT 'Optional description of the tab',
  `is_hidden` tinyint NOT NULL DEFAULT '0' COMMENT '1 = hidden, 0 = visible',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated',
  `is_active` tinyint NOT NULL DEFAULT '1' COMMENT '1 = active, 0 = not active',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '1 = soft deleted, 0 = not deleted',
  `deleted_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when deleted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_collection_tab_map`
--

CREATE TABLE `lupo_collection_tab_map` (
  `collection_tab_map_id` bigint NOT NULL,
  `collection_tab_id` bigint NOT NULL COMMENT 'Reference to the parent tab',
  `federations_node_id` bigint NOT NULL COMMENT 'Domain this mapping belongs to',
  `item_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Type of mapped item (content, tab, link, etc.)',
  `item_id` bigint NOT NULL COMMENT 'ID of the mapped item',
  `sort_order` int DEFAULT '0' COMMENT 'Display order within the tab',
  `properties` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'JSON-encoded key-value store for additional data',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '1 = deleted, 0 = not deleted',
  `deleted_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when deleted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Maps content items, tabs, and links into collection tabs for organization';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_collection_tab_paths`
--

CREATE TABLE `lupo_collection_tab_paths` (
  `collection_tab_path_id` bigint NOT NULL,
  `collection_id` bigint NOT NULL,
  `collection_tab_id` bigint NOT NULL,
  `path` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Full tab path: departments/parks-and-recreation/summer-programs',
  `depth` int NOT NULL COMMENT 'Number of levels (1 = root tab)',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS',
  `updated_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS',
  `is_deleted` tinyint NOT NULL DEFAULT '0',
  `deleted_ymdhis` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Full hierarchical paths for tabs - enables fast lookups and semantic edge generation';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_contents`
--

CREATE TABLE `lupo_contents` (
  `content_id` bigint NOT NULL COMMENT 'Primary key for content',
  `content_parent_id` bigint DEFAULT NULL COMMENT 'Parent content ID for hierarchical relationships',
  `federation_node_id` bigint DEFAULT '1' COMMENT 'Domain scope, NULL for global content',
  `group_id` bigint DEFAULT NULL COMMENT 'Optional group restriction',
  `actor_id` bigint DEFAULT NULL COMMENT 'Actor who created or last modified the content',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Content title',
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'URL-friendly identifier, unique within domain',
  `custom_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Semantic routing override; not a filesystem path',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Short summary or teaser',
  `seo_keywords` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Main content body',
  `content_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'article' COMMENT 'Type of content (article, page, post, etc.)',
  `format` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'markdown' COMMENT 'Content format (html, markdown, etc.)',
  `content_url` varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'content URL if content is referenced by url such as lupopedia.com/content/lupopedia',
  `default_collection_id` bigint DEFAULT NULL COMMENT 'Default collection ID',
  `source_url` varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Original source URL if content is imported',
  `source_title` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Title of the source',
  `is_template` tinyint NOT NULL DEFAULT '0' COMMENT '1 = template, 0 = regular content',
  `status` enum('draft','published','archived') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'draft' COMMENT 'Publication status',
  `visibility` enum('public','private','unlisted') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'public' COMMENT 'Visibility level',
  `view_count` int DEFAULT '0' COMMENT 'Number of views',
  `share_count` int DEFAULT '0' COMMENT 'Number of shares',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
  `utc_cycle` enum('creative','responsible') COLLATE utf8mb4_unicode_ci NOT NULL,
  `triage_status` enum('untriaged','keeper','fragment','duplicate','trash') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'untriaged',
  `triage_notes` text COLLATE utf8mb4_unicode_ci,
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '1 = deleted, 0 = not deleted',
  `is_active` tinyint NOT NULL DEFAULT '1' COMMENT '1 = active, 0 = not active',
  `deleted_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when deleted',
  `content_sections` json DEFAULT NULL COMMENT 'Cached list of section anchors extracted from the content body',
  `version_number` int NOT NULL DEFAULT '1' COMMENT 'Monotonic version number for the live content row',
  `file_path_from_root` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Filesystem path for header generation',
  `tags` json DEFAULT NULL COMMENT 'Array of tags for header projection',
  `dialog_notes` text COLLATE utf8mb4_unicode_ci COMMENT 'Short note about last agent action'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Main content items including articles, pages, and other content types';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_content_atom_map`
--

CREATE TABLE `lupo_content_atom_map` (
  `content_atom_map_id` bigint NOT NULL,
  `content_id` bigint NOT NULL COMMENT 'Content item using the atom',
  `atom_id` bigint NOT NULL COMMENT 'Semantic atom referenced by the content',
  `purpose` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Why this atom is attached (topic, tag, variable, etc.)',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '1 = deleted, 0 = not deleted',
  `deleted_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when deleted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Maps semantic atoms to content items, enabling atomic knowledge composition';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_content_category_map`
--

CREATE TABLE `lupo_content_category_map` (
  `content_category_map_id` bigint NOT NULL COMMENT 'Primary key for content-category mapping',
  `content_id` bigint NOT NULL COMMENT 'Content being categorized',
  `category_id` bigint NOT NULL COMMENT 'Category assigned to this content',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when mapping was created'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Maps content items to categories';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_content_engagement_summary`
--

CREATE TABLE `lupo_content_engagement_summary` (
  `content_engagement_summary_id` bigint NOT NULL COMMENT 'Reference to the content item',
  `likes_total` int NOT NULL DEFAULT '0' COMMENT 'Total number of likes received',
  `shares_total` int NOT NULL DEFAULT '0' COMMENT 'Total number of shares',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when the summary was last updated',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when the summary was first created'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Denormalized cache of engagement metrics for content items';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_content_events`
--

CREATE TABLE `lupo_content_events` (
  `content_event_id` bigint NOT NULL COMMENT 'Primary key for content event',
  `content_id` bigint DEFAULT NULL COMMENT 'Content identifier',
  `actor_id` bigint DEFAULT NULL COMMENT 'Actor ID from lupo_actors',
  `session_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Session identifier',
  `tab_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tab identifier',
  `world_id` bigint DEFAULT NULL COMMENT 'World context ID',
  `world_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'World context key',
  `world_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'World context type',
  `event_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Type of content event',
  `event_data` json DEFAULT NULL COMMENT 'Event-specific data',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Content events with world context';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_content_hashtag`
--

CREATE TABLE `lupo_content_hashtag` (
  `content_hashtag_id` bigint NOT NULL COMMENT 'Reference to the content item',
  `hashtag_id` bigint NOT NULL COMMENT 'Reference to the hashtag',
  `context_id` bigint NOT NULL COMMENT 'Context for this hashtag association',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when the association was created',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '1 = deleted, 0 = not deleted',
  `deleted_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when deleted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Associates content with hashtags within specific contexts';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_content_inbound_links`
--

CREATE TABLE `lupo_content_inbound_links` (
  `content_inbound_link_id` bigint NOT NULL,
  `target_content_id` bigint NOT NULL COMMENT 'Content item being linked TO',
  `source_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'content, reference, external, tab, atom, question, etc.',
  `source_id` bigint DEFAULT NULL COMMENT 'ID of the source entity when applicable',
  `source_url` varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'For external links',
  `link_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'citation, embed, mention, related, etc.',
  `properties` json DEFAULT NULL COMMENT 'JSON-encoded additional properties',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '1 = deleted, 0 = not deleted',
  `deleted_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when deleted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tracks all inbound links to content items from various sources';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_content_likes`
--

CREATE TABLE `lupo_content_likes` (
  `content_like_id` bigint NOT NULL,
  `content_id` bigint NOT NULL COMMENT 'ID of the content being liked',
  `user_id` bigint DEFAULT NULL COMMENT 'ID of the user who liked (if authenticated)',
  `visitor_hash` char(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Hashed IP/session for anonymous likes',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '1 = deleted, 0 = not deleted',
  `deleted_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when deleted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tracks likes on content items by both authenticated and anonymous users';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_content_media`
--

CREATE TABLE `lupo_content_media` (
  `content_media_id` bigint NOT NULL,
  `content_id` bigint NOT NULL COMMENT 'Reference to the parent content item',
  `media_type` enum('image','audio','video','document','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Type of media: image, audio, video, document, or other',
  `original_filename` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Original filename as uploaded by user',
  `stored_filename` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Generated filename for storage (unique identifier)',
  `stored_path` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Relative path to the media file (without filename)',
  `file_extension` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'File extension without leading dot',
  `mime_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'MIME type of the media file',
  `file_size` bigint DEFAULT NULL COMMENT 'File size in bytes',
  `dimensions` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Image/video dimensions (WxH)',
  `duration` int DEFAULT NULL COMMENT 'Duration in seconds (audio/video)',
  `media_order` int NOT NULL DEFAULT '0' COMMENT 'Display order within the content item',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Title of the media',
  `caption_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Descriptive caption',
  `alt_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Accessibility alternative text',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Detailed description',
  `metadata` json DEFAULT NULL COMMENT 'JSON-encoded technical metadata (EXIF, codec info, etc.)',
  `variants` json DEFAULT NULL COMMENT 'JSON-encoded information about generated variants (thumbnails, etc.)',
  `is_public` tinyint NOT NULL DEFAULT '1' COMMENT '1 = publicly accessible, 0 = private',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '1 = deleted, 0 = not deleted',
  `deleted_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when deleted',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores and manages media files associated with content items, with versioning and metadata support';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_content_question_map`
--

CREATE TABLE `lupo_content_question_map` (
  `content_question_map_id` bigint NOT NULL,
  `content_id` bigint NOT NULL COMMENT 'Content item associated with the question',
  `question_id` bigint NOT NULL COMMENT 'Question applied to this content',
  `domain_id` bigint NOT NULL COMMENT 'Domain this mapping belongs to',
  `purpose` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Optional: answer, related, metadata, prompt, etc.',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '1 = deleted, 0 = not deleted',
  `deleted_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when deleted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Maps content items to questions for semantic organization and navigation';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_content_references`
--

CREATE TABLE `lupo_content_references` (
  `content_referenc_id` bigint NOT NULL,
  `content_id` bigint NOT NULL,
  `section_anchor_slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `raw_reference` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference_slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_object_id` bigint DEFAULT NULL,
  `meta_json` json DEFAULT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT '0',
  `deleted_ymdhis` bigint NOT NULL DEFAULT '0',
  `created_ymdhis` bigint NOT NULL DEFAULT '0',
  `updated_ymdhis` bigint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_content_revisions`
--

CREATE TABLE `lupo_content_revisions` (
  `content_revision_id` bigint NOT NULL,
  `content_id` bigint NOT NULL COMMENT 'FK to contents table (not enforced by DB)',
  `version_number` int NOT NULL COMMENT 'Version number this snapshot represents',
  `body_snapshot` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Full body content at the time of revision',
  `metadata_snapshot` json DEFAULT NULL COMMENT 'Metadata JSON at the time of revision',
  `sections_snapshot` json DEFAULT NULL COMMENT 'Cached section anchors at the time of revision',
  `edited_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Username or agent identifier that made the change',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_content_shares`
--

CREATE TABLE `lupo_content_shares` (
  `content_share_id` bigint NOT NULL,
  `content_id` bigint NOT NULL COMMENT 'ID of the content being shared',
  `user_id` bigint DEFAULT NULL COMMENT 'ID of the user who shared (if authenticated)',
  `visitor_hash` char(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Hashed IP/session for anonymous shares',
  `share_method` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Share method: link, embed, social, email, etc.',
  `share_target` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Specific target of the share (e.g., "twitter", "facebook")',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '1 = deleted, 0 = not deleted',
  `deleted_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when deleted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tracks share events for content items by both authenticated and anonymous users';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_content_tag_relationships`
--

CREATE TABLE `lupo_content_tag_relationships` (
  `relationship_id` bigint NOT NULL COMMENT 'Primary key for content-tag relationship',
  `content_id` bigint NOT NULL COMMENT 'Reference to content table',
  `tag_id` bigint NOT NULL COMMENT 'Reference to tag table',
  `relationship_type` enum('category','topic','label') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Type of relationship',
  `created_ymdhis` bigint NOT NULL COMMENT 'Creation timestamp'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Many-to-many relationships between content and tags';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_contexts`
--

CREATE TABLE `lupo_contexts` (
  `context_id` int UNSIGNED NOT NULL,
  `context_code` varchar(16) NOT NULL COMMENT 'Numeric/hierarchical code, e.g. 1000, 2100.10',
  `context_name` varchar(255) NOT NULL COMMENT 'Short human-readable label',
  `context_description` text COMMENT 'Long-form description / doctrine notes',
  `parent_context_id` int UNSIGNED DEFAULT NULL COMMENT 'FK to lupo_contexts.context_id for hierarchy',
  `is_system` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 = system/meta/doctrine context',
  `is_fiction` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 = fiction / narrative / lore',
  `is_installation_local` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 = installation-specific context',
  `sort_order` int NOT NULL DEFAULT '0' COMMENT 'Optional manual ordering within siblings',
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  `weight_score` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT 'Dynamic ranking score based on age, revisions, likes, shares, references, and links',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `metadata_json` json DEFAULT NULL COMMENT 'Extra semantic flags, routing hints, emotional metadata, etc.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_contexts_map`
--

CREATE TABLE `lupo_contexts_map` (
  `contexts_map_id` bigint NOT NULL,
  `context_id` bigint NOT NULL,
  `item_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_deleted` tinyint NOT NULL DEFAULT '0',
  `deleted_ymdhis` bigint NOT NULL DEFAULT '0',
  `created_ymdhis` bigint NOT NULL DEFAULT '0',
  `updated_ymdhis` bigint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_contexts_old`
--

CREATE TABLE `lupo_contexts_old` (
  `context_id` bigint NOT NULL,
  `context_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `context_slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_context_id` bigint DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_ymd` bigint NOT NULL DEFAULT '0',
  `updated_ymd` bigint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_crafty_syntax_auto_invite`
--

CREATE TABLE `lupo_crafty_syntax_auto_invite` (
  `crafty_syntax_auto_invite_id` bigint NOT NULL,
  `is_offline` tinyint NOT NULL DEFAULT '0',
  `is_active` tinyint NOT NULL DEFAULT '0',
  `department_id` bigint NOT NULL DEFAULT '0',
  `message` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `page_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `visits` int NOT NULL DEFAULT '0',
  `referrer_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invite_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trigger_seconds` int NOT NULL DEFAULT '0',
  `operator_user_id` bigint NOT NULL DEFAULT '0',
  `show_socialpane` tinyint NOT NULL DEFAULT '0',
  `exclude_mobile` tinyint NOT NULL DEFAULT '0',
  `only_mobile` tinyint NOT NULL DEFAULT '0',
  `created_ymdhis` bigint NOT NULL DEFAULT '20250101000000',
  `updated_ymdhis` bigint NOT NULL DEFAULT '20250101000000',
  `is_deleted` tinyint NOT NULL DEFAULT '0',
  `deleted_ymdhis` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Manages automatic chat invitation rules. Migrated from old schema on 2025-01-01.';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_crafty_syntax_chat_mod_departments`
--

CREATE TABLE `lupo_crafty_syntax_chat_mod_departments` (
  `crafty_syntax_chat_mod_department_id` bigint NOT NULL,
  `department_id` bigint NOT NULL DEFAULT '0',
  `module_id` bigint NOT NULL DEFAULT '0',
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_default` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_crafty_syntax_chat_questions`
--

CREATE TABLE `lupo_crafty_syntax_chat_questions` (
  `crafty_syntax_chat_question_id` bigint NOT NULL,
  `department_id` bigint NOT NULL DEFAULT '0',
  `sort_order` int NOT NULL DEFAULT '0',
  `headertext` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `field_type` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `flags` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `module_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_required` tinyint NOT NULL DEFAULT '0',
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_ymdhis` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_crafty_syntax_layer_invites`
--

CREATE TABLE `lupo_crafty_syntax_layer_invites` (
  `crafty_syntax_layer_invite_id` bigint NOT NULL COMMENT 'Unique identifier for the layer invitation',
  `layer_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Name/identifier for this layer invitation',
  `image_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Filename of the image used for this layer',
  `image_map` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'HTML image map coordinates and links in JSON format',
  `department_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Name of the department this layer is associated with',
  `user_id` bigint NOT NULL DEFAULT '0' COMMENT 'User ID who created this layer invitation (0 = system)',
  `is_active` tinyint NOT NULL DEFAULT '1' COMMENT 'Whether this layer is active: 0=inactive, 1=active',
  `display_count` int NOT NULL DEFAULT '0' COMMENT 'Number of times this layer has been displayed',
  `click_count` int NOT NULL DEFAULT '0' COMMENT 'Number of times this layer has been clicked',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when record was created',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when record was last updated',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT 'Soft delete flag: 0=active, 1=deleted',
  `deleted_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when record was soft-deleted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Manages layer-based invitations in the LiveHelp system, allowing for image-based interactive elements that invite users to chat';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_crafty_syntax_leave_message`
--

CREATE TABLE `lupo_crafty_syntax_leave_message` (
  `crafty_syntax_leave_message_id` bigint NOT NULL,
  `department_id` bigint NOT NULL DEFAULT '0',
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `phone` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `priority` tinyint NOT NULL DEFAULT '2',
  `session_data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `form_data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('new','in_progress','resolved','spam') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `assigned_to` bigint DEFAULT NULL,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT '0',
  `deleted_ymdhis` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_crafty_user_mapping`
--

CREATE TABLE `lupo_crafty_user_mapping` (
  `id` bigint NOT NULL COMMENT 'Primary key for mapping',
  `lupo_user_id` bigint DEFAULT NULL COMMENT 'Reference to lupo_auth_users.auth_user_id',
  `crafty_operator_id` int DEFAULT NULL COMMENT 'Reference to livehelp_operators.operatorid',
  `mapping_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'manual' COMMENT 'Type: manual, auto, imported',
  `notes` text COLLATE utf8mb4_unicode_ci COMMENT 'Optional notes for mapping',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Creation timestamp',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last update timestamp'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='User mapping between Lupopedia and Crafty Syntax systems';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_crm_leads`
--

CREATE TABLE `lupo_crm_leads` (
  `crm_lead_id` bigint NOT NULL COMMENT 'Unique identifier for the lead',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Email address of the lead',
  `phone` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Phone number of the lead (formatted as E.164)',
  `first_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'First name of the lead',
  `last_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Last name of the lead',
  `source` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Source that generated this lead (e.g., website, referral, campaign)',
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new' COMMENT 'Current status of the lead (new, contacted, qualified, converted, etc.)',
  `lead_score` int NOT NULL DEFAULT '0' COMMENT 'Numerical score indicating lead quality (0-100)',
  `assigned_to` bigint DEFAULT NULL COMMENT 'user ID of the team member assigned to this lead',
  `lead_data` longtext COLLATE utf8mb4_unicode_ci COMMENT 'JSON-encoded additional lead information and custom fields',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC timestamp when the lead was created (YYYYMMDDHHMMSS)',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC timestamp when the lead was last updated (YYYYMMDDHHMMSS)',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT 'Soft delete flag (1 = deleted, 0 = active)',
  `deleted_ymdhis` bigint DEFAULT NULL COMMENT 'UTC timestamp when the lead was soft-deleted (YYYYMMDDHHMMSS)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores potential customer leads with contact information and tracking data';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_crm_lead_messages`
--

CREATE TABLE `lupo_crm_lead_messages` (
  `crm_lead_message_id` bigint NOT NULL,
  `lead_id` bigint DEFAULT NULL,
  `from_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `body_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `actor_id` bigint DEFAULT NULL,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` smallint NOT NULL DEFAULT '0',
  `deleted_ymdhis` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_departments`
--

CREATE TABLE `lupo_departments` (
  `department_id` bigint NOT NULL,
  `federation_node_id` bigint NOT NULL,
  `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `department_type` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `default_actor_id` bigint NOT NULL DEFAULT '1',
  `settings_json` json DEFAULT NULL,
  `created_ymdhis` char(14) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_ymdhis` char(14) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT '0',
  `deleted_ymdhis` char(14) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_department_metadata`
--

CREATE TABLE `lupo_department_metadata` (
  `department_metadata_id` bigint NOT NULL,
  `department_id` bigint NOT NULL COMMENT 'Soft reference to lupo_departments.department_id (no FK by doctrine)',
  `metadata_json` json NOT NULL COMMENT 'Legacy UI settings, colors, images, behavior flags, and other non-semantic metadata',
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  `is_active` tinyint NOT NULL DEFAULT '1',
  `is_deleted` tinyint NOT NULL DEFAULT '0',
  `deleted_ymdhis` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_dialog_channels`
--

CREATE TABLE `lupo_dialog_channels` (
  `channel_id` bigint UNSIGNED NOT NULL,
  `channel_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_source` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Original .md filename',
  `title` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `speaker` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `categories` json DEFAULT NULL COMMENT 'Array of category strings',
  `collections` json DEFAULT NULL COMMENT 'Array of collection strings',
  `channels` json DEFAULT NULL COMMENT 'Array of channel strings',
  `tags` json DEFAULT NULL COMMENT 'Additional tag metadata',
  `version` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'System version when created',
  `status` enum('draft','published','archived') COLLATE utf8mb4_unicode_ci DEFAULT 'published',
  `author` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_timestamp` bigint UNSIGNED NOT NULL COMMENT 'YYYYMMDDHHIISS format',
  `modified_timestamp` bigint UNSIGNED NOT NULL COMMENT 'YYYYMMDDHHIISS format',
  `message_count` int UNSIGNED DEFAULT '0' COMMENT 'Cached count of messages',
  `metadata_json` json DEFAULT NULL COMMENT 'Additional metadata from WOLFIE headers'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Dialog channels migrated from .md files with WOLFIE header metadata';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_dialog_messages`
--

CREATE TABLE `lupo_dialog_messages` (
  `dialog_message_id` bigint NOT NULL COMMENT 'Primary key for the dialog message',
  `dialog_thread_id` bigint DEFAULT NULL COMMENT 'Optional thread grouping for related dialogs',
  `channel_id` bigint DEFAULT NULL COMMENT 'Optional channel identifier',
  `from_actor_id` bigint DEFAULT NULL COMMENT 'Actor ID of the message sender',
  `to_actor_id` bigint DEFAULT NULL COMMENT 'Agent ID if message is from an AI agent',
  `message_text` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'The message under 1000 chars ',
  `message_type` enum('text','command','system','error') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text' COMMENT 'Type of message',
  `metadata_json` json DEFAULT NULL COMMENT 'Additional message metadata',
  `mood_rgb` char(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mood_framework` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'western_analytical',
  `created_ymdhis` bigint NOT NULL COMMENT 'Creation timestamp (YYYYMMDDHHMMSS)',
  `updated_ymdhis` bigint NOT NULL COMMENT 'Last update timestamp (YYYYMMDDHHMMSS)',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT 'Soft delete flag (1=deleted)',
  `deleted_ymdhis` bigint DEFAULT NULL COMMENT 'Deletion timestamp (YYYYMMDDHHMMSS)',
  `message_body` mediumtext COLLATE utf8mb4_unicode_ci COMMENT 'Full message body'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores messages in dialog threads between agents and users. Supports rich message types, inline dialogs, and threading.';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_dialog_threads`
--

CREATE TABLE `lupo_dialog_threads` (
  `dialog_thread_id` bigint NOT NULL COMMENT 'Primary key for the dialog thread',
  `federation_node_id` bigint NOT NULL DEFAULT '1' COMMENT 'Node that owns this thread; default is local installation (1)',
  `channel_id` bigint DEFAULT NULL COMMENT 'Optional channel identifier for grouping threads',
  `project_slug` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Project or subsystem this thread belongs to',
  `task_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Human-readable task name for this thread',
  `created_by_actor_id` bigint NOT NULL COMMENT 'Agent or human who initiated the thread',
  `summary_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Short summary of the thread purpose or context',
  `bg_color` char(6) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'FFFFFF',
  `text_color` char(6) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '000000',
  `alt_text_color` char(6) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '666666',
  `status` enum('Open','Ongoing','Closed','Archived') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Open' COMMENT 'Thread lifecycle state',
  `artifacts` json DEFAULT NULL COMMENT 'Optional JSON list of related files, URLs, or resources',
  `metadata_json` json DEFAULT NULL COMMENT 'Metadata: intent, scope, persona, mood, inline dialog metadata',
  `created_ymdhis` bigint NOT NULL COMMENT 'Creation timestamp (YYYYMMDDHHMMSS)',
  `updated_ymdhis` bigint NOT NULL COMMENT 'Last update timestamp (YYYYMMDDHHMMSS)',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT 'Soft delete flag (1=deleted)',
  `deleted_ymdhis` bigint DEFAULT NULL COMMENT 'Deletion timestamp (YYYYMMDDHHMMSS)',
  `escalated_to_operator_id` bigint DEFAULT NULL COMMENT 'Operator assigned during escalation (Crafty Syntax handoff)',
  `escalation_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Reason for escalation (confusion, conflict, policy, etc.)',
  `escalation_timestamp` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when escalation was triggered'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='High-level dialog threads grouping messages across agents, tasks, and projects.';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_doctrine_blocks`
--

CREATE TABLE `lupo_doctrine_blocks` (
  `id` bigint NOT NULL,
  `block_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `block_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `block_content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHIISS when created',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHIISS when last updated'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores doctrine blocks for AI agents and system documentation';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_doctrine_evolution_audit`
--

CREATE TABLE `lupo_doctrine_evolution_audit` (
  `id` bigint UNSIGNED NOT NULL,
  `refinement_id` bigint UNSIGNED NOT NULL COMMENT 'Reference to lupo_doctrine_refinements.id',
  `evolution_step` tinyint UNSIGNED NOT NULL COMMENT 'Step in evolution process (1-10)',
  `step_description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Description of evolution step',
  `step_status` enum('pending','in_progress','completed','failed','skipped') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `step_metadata_json` json DEFAULT NULL COMMENT 'Step-specific metadata',
  `started_ymdhis` bigint DEFAULT NULL COMMENT 'When step started',
  `completed_ymdhis` bigint DEFAULT NULL COMMENT 'When step completed',
  `audit_version` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '4.0.75' COMMENT 'Audit system version'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Doctrine Evolution Audit - Detailed tracking of doctrine change process';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_doctrine_refinements`
--

CREATE TABLE `lupo_doctrine_refinements` (
  `id` bigint UNSIGNED NOT NULL,
  `cip_event_id` bigint UNSIGNED NOT NULL COMMENT 'Triggering CIP event',
  `doctrine_file_path` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Path to doctrine file updated',
  `refinement_type` enum('addition','modification','removal','restructure') COLLATE utf8mb4_unicode_ci NOT NULL,
  `change_description` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Description of doctrine change',
  `before_content_hash` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'SHA256 of content before change',
  `after_content_hash` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'SHA256 of content after change',
  `impact_assessment_json` json DEFAULT NULL COMMENT 'Assessment of change impact',
  `approval_status` enum('pending','approved','rejected','auto_approved') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `approved_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Who approved the change',
  `applied_ymdhis` bigint DEFAULT NULL COMMENT 'When change was applied',
  `created_ymdhis` bigint NOT NULL COMMENT 'When refinement was proposed',
  `refinement_version` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '4.0.75' COMMENT 'Refinement module version'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Doctrine Refinement Module - CIP-driven doctrine evolution tracking';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_documents`
--

CREATE TABLE `lupo_documents` (
  `document_id` bigint NOT NULL,
  `domain_id` int NOT NULL DEFAULT '1',
  `document_name` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL,
  `source_type` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `source_url` text COLLATE utf8mb4_unicode_ci,
  `mime_type` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_size_bytes` int DEFAULT NULL,
  `checksum_sha256` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `metadata` json DEFAULT NULL,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT '0',
  `deleted_ymdhis` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_document_chunks`
--

CREATE TABLE `lupo_document_chunks` (
  `document_chunk_id` bigint NOT NULL,
  `document_id` bigint NOT NULL,
  `chunk_index` int NOT NULL,
  `chunk_content` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `token_count` int DEFAULT NULL,
  `metadata` json DEFAULT NULL,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT '0',
  `deleted_ymdhis` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_document_embeddings`
--

CREATE TABLE `lupo_document_embeddings` (
  `document_embedding_id` bigint NOT NULL,
  `chunk_id` bigint NOT NULL,
  `embedding_json` json NOT NULL,
  `embedding_model` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `embedding_version` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT '0',
  `deleted_ymdhis` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_edges`
--

CREATE TABLE `lupo_edges` (
  `edge_id` bigint NOT NULL,
  `left_object_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `left_object_id` bigint NOT NULL,
  `right_object_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `right_object_id` bigint NOT NULL,
  `edge_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `channel_id` bigint DEFAULT NULL,
  `channel_key` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `weight_score` int NOT NULL DEFAULT '0',
  `sort_num` int NOT NULL DEFAULT '0',
  `actor_id` bigint DEFAULT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT '0',
  `deleted_ymdhis` bigint NOT NULL DEFAULT '0',
  `created_ymdhis` bigint NOT NULL DEFAULT '0',
  `updated_ymdhis` bigint NOT NULL DEFAULT '0',
  `semantic_weight` decimal(5,2) DEFAULT '0.00' COMMENT 'Semantic relationship strength (0.00-1.00)',
  `relationship_type` enum('hierarchical','semantic','dependency','reference','contains') COLLATE utf8mb4_unicode_ci DEFAULT 'semantic' COMMENT 'Type of relationship',
  `bidirectional` tinyint NOT NULL DEFAULT '0' COMMENT 'Relationship works both ways',
  `context_scope` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Scope where relationship applies'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_edge_types`
--

CREATE TABLE `lupo_edge_types` (
  `edge_type_id` bigint NOT NULL,
  `edge_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `category` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_ymd` bigint NOT NULL DEFAULT '0',
  `updated_ymd` bigint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_emotional_constellations`
--

CREATE TABLE `lupo_emotional_constellations` (
  `constellation_id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ULID',
  `framework_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cultural_origin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `stars` json NOT NULL COMMENT 'Array of star_ids',
  `is_canonical` tinyint(1) NOT NULL DEFAULT '0',
  `canonical_for_culture` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_ymdhis` bigint DEFAULT NULL,
  `deprecated_ymdhis` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_emotional_frameworks`
--

CREATE TABLE `lupo_emotional_frameworks` (
  `framework_name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_emotional_geometry_calibrations`
--

CREATE TABLE `lupo_emotional_geometry_calibrations` (
  `id` bigint UNSIGNED NOT NULL,
  `cip_analytics_id` bigint UNSIGNED NOT NULL COMMENT 'Reference to lupo_cip_analytics.id',
  `calibration_target` enum('agent','subsystem','global') COLLATE utf8mb4_unicode_ci NOT NULL,
  `target_identifier` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Agent ID, subsystem name, or "global"',
  `baseline_before_json` json DEFAULT NULL COMMENT 'R/G/B vectors before calibration',
  `baseline_after_json` json NOT NULL COMMENT 'R/G/B vectors after calibration',
  `mood_framework` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'western_analytical',
  `tension_vectors_detected` json DEFAULT NULL COMMENT 'Detected tension patterns',
  `calibration_reason` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Why calibration was needed',
  `calibration_algorithm` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'cip_pattern_analysis' COMMENT 'Algorithm used',
  `confidence_score` decimal(5,4) NOT NULL DEFAULT '0.5000' COMMENT 'Calibration confidence',
  `validation_status` enum('pending','validated','rejected','needs_review') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `applied_ymdhis` bigint DEFAULT NULL COMMENT 'When calibration was applied',
  `created_ymdhis` bigint NOT NULL COMMENT 'When calibration was calculated',
  `calibration_version` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '4.0.75' COMMENT 'Calibration system version'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Emotional Geometry Calibration - CIP-driven emotional baseline adjustments';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_emotional_stars`
--

CREATE TABLE `lupo_emotional_stars` (
  `star_id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ULID',
  `experience_hash` char(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Deduplication hash',
  `experience_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `cultural_context` json DEFAULT NULL COMMENT 'Array of cultural tags',
  `embodied_sensation` json DEFAULT NULL COMMENT 'Array of somatic descriptors',
  `created_by` bigint DEFAULT NULL COMMENT 'agent_id or operator_id',
  `created_in_context` bigint DEFAULT NULL COMMENT 'thread_id or null',
  `first_observed_ymdhis` bigint DEFAULT NULL,
  `observation_count` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_emotional_translations`
--

CREATE TABLE `lupo_emotional_translations` (
  `translation_id` bigint NOT NULL,
  `source_framework` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `source_state` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `target_framework` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `target_state` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `loss_score` decimal(3,2) NOT NULL,
  `created_ymdhis` bigint NOT NULL,
  `last_used_ymdhis` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_entity_edges`
--

CREATE TABLE `lupo_entity_edges` (
  `entity_edge_id` bigint NOT NULL,
  `source_entity_type` enum('agent','actor','user','service') COLLATE utf8mb4_unicode_ci NOT NULL,
  `source_entity_id` bigint NOT NULL,
  `target_entity_type` enum('agent','actor','user','service','file','content','channel') COLLATE utf8mb4_unicode_ci NOT NULL,
  `target_entity_id` bigint NOT NULL,
  `edge_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `domain_id` bigint NOT NULL DEFAULT '1',
  `properties` json DEFAULT NULL,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint DEFAULT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT '0',
  `deleted_ymdhis` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_entity_properties`
--

CREATE TABLE `lupo_entity_properties` (
  `entity_property_id` bigint NOT NULL,
  `entity_type` enum('agent','actor','user','service') COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_id` bigint NOT NULL,
  `domain_id` bigint NOT NULL DEFAULT '1',
  `property_key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `property_value` text COLLATE utf8mb4_unicode_ci,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint DEFAULT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT '0',
  `deleted_ymdhis` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_event_log`
--

CREATE TABLE `lupo_event_log` (
  `event_id` bigint NOT NULL,
  `event_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_data` json DEFAULT NULL,
  `created_ymdhis` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_event_metadata`
--

CREATE TABLE `lupo_event_metadata` (
  `metadata_id` bigint NOT NULL,
  `event_id` bigint NOT NULL,
  `metadata_key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `metadata_value` text COLLATE utf8mb4_unicode_ci,
  `created_ymdhis` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_federation_categories`
--

CREATE TABLE `lupo_federation_categories` (
  `federation_category_id` bigint NOT NULL,
  `category_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_description` text COLLATE utf8mb4_unicode_ci,
  `meta_json` json DEFAULT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT '0',
  `deleted_ymdhis` bigint NOT NULL DEFAULT '0',
  `created_ymdhis` bigint NOT NULL DEFAULT '0',
  `updated_ymdhis` bigint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_federation_category_map`
--

CREATE TABLE `lupo_federation_category_map` (
  `federation_category_map_id` bigint NOT NULL,
  `federation_node_id` bigint NOT NULL,
  `federation_category_id` bigint NOT NULL,
  `meta_json` json DEFAULT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT '0',
  `deleted_ymdhis` bigint NOT NULL DEFAULT '0',
  `created_ymdhis` bigint NOT NULL DEFAULT '0',
  `updated_ymdhis` bigint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_federation_discovery`
--

CREATE TABLE `lupo_federation_discovery` (
  `federation_discovery_id` bigint NOT NULL,
  `domain` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `install_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_lupopedia` tinyint NOT NULL DEFAULT '0',
  `last_seen_ymdhis` bigint DEFAULT NULL,
  `first_seen_ymdhis` bigint DEFAULT NULL,
  `hashtag_count` bigint DEFAULT '0',
  `question_count` bigint DEFAULT '0',
  `atom_count` bigint DEFAULT '0',
  `context_count` bigint DEFAULT '0',
  `collection_count` bigint DEFAULT '0',
  `keywords` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `import_hashtags` tinyint NOT NULL DEFAULT '0',
  `import_questions` tinyint NOT NULL DEFAULT '0',
  `import_atoms` tinyint NOT NULL DEFAULT '0',
  `import_contexts` tinyint NOT NULL DEFAULT '0',
  `import_collections` tinyint NOT NULL DEFAULT '0',
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_federation_nodes`
--

CREATE TABLE `lupo_federation_nodes` (
  `federation_node_id` bigint NOT NULL,
  `node_base_url` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `default_department_id` bigint DEFAULT NULL,
  `node_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `node_description` text COLLATE utf8mb4_unicode_ci,
  `node_contact` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_json` json DEFAULT NULL,
  `content_count` bigint NOT NULL DEFAULT '0',
  `atom_count` bigint NOT NULL DEFAULT '0',
  `hashtag_count` bigint NOT NULL DEFAULT '0',
  `actor_count` bigint NOT NULL DEFAULT '0',
  `last_sync_ymdhis` bigint NOT NULL DEFAULT '0',
  `trust_level` tinyint NOT NULL DEFAULT '0',
  `status` tinyint NOT NULL DEFAULT '1',
  `is_deleted` tinyint NOT NULL DEFAULT '0',
  `deleted_ymdhis` bigint NOT NULL DEFAULT '0',
  `created_ymdhis` bigint NOT NULL DEFAULT '0',
  `updated_ymdhis` bigint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_governance_overrides`
--

CREATE TABLE `lupo_governance_overrides` (
  `governance_overrid_id` bigint NOT NULL,
  `agent_id` bigint DEFAULT NULL COMMENT 'Agent whose behavior is being overridden',
  `applied_by_agent` bigint DEFAULT NULL COMMENT 'Agent or system that applied the override',
  `override_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'capability_block, faucet_lock, safety_rule, escalation, etc.',
  `target_key` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Capability, faucet, or rule being overridden',
  `old_value` text COLLATE utf8mb4_unicode_ci COMMENT 'Previous value before override',
  `new_value` text COLLATE utf8mb4_unicode_ci COMMENT 'New value after override',
  `reason_text` text COLLATE utf8mb4_unicode_ci COMMENT 'Human-readable explanation for the override',
  `metadata_json` json DEFAULT NULL COMMENT 'Additional structured metadata',
  `created_ymdhis` bigint NOT NULL,
  `expires_ymdhis` bigint DEFAULT NULL COMMENT 'Optional expiration timestamp',
  `is_deleted` tinyint NOT NULL DEFAULT '0',
  `deleted_ymdhis` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores governance, safety, and capability overrides applied to agents or domains.';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_gov_events`
--

CREATE TABLE `lupo_gov_events` (
  `gov_event_id` bigint NOT NULL COMMENT 'Primary key for governance event',
  `utc_group_id` bigint NOT NULL COMMENT 'UTC group identifier',
  `semantic_utc_version` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Semantic UTC version string',
  `canonical_path` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Canonical path for the event',
  `event_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Type of governance event',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Event title',
  `directive_block` text COLLATE utf8mb4_unicode_ci COMMENT 'Captain Wolfie directive content',
  `tldr_summary` text COLLATE utf8mb4_unicode_ci COMMENT 'TL;DR summary of the event',
  `metadata_json` json DEFAULT NULL COMMENT 'Additional event metadata',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
  `updated_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated',
  `is_active` tinyint NOT NULL DEFAULT '1' COMMENT 'Active flag',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT 'Soft delete flag',
  `deleted_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when deleted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Governance events for Captain Wolfie directives';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_gov_event_actor_edges`
--

CREATE TABLE `lupo_gov_event_actor_edges` (
  `edge_id` bigint NOT NULL COMMENT 'Primary key for edge',
  `gov_event_id` bigint NOT NULL COMMENT 'Governance event',
  `actor_id` bigint NOT NULL COMMENT 'Actor',
  `edge_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Type of relationship',
  `edge_properties` text COLLATE utf8mb4_unicode_ci COMMENT 'JSON or TOON formatted metadata',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
  `updated_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT 'Soft delete flag',
  `deleted_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when deleted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Actor relationships for governance events';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_gov_event_conflicts`
--

CREATE TABLE `lupo_gov_event_conflicts` (
  `id` bigint NOT NULL COMMENT 'Primary key',
  `gov_event_id` bigint NOT NULL COMMENT 'The event declaring a conflict',
  `conflicts_with_event_id` bigint NOT NULL COMMENT 'The event it conflicts with',
  `conflict_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'schema, doctrine, branch, timestamp, identity',
  `severity` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'warning, error, fatal',
  `notes` text COLLATE utf8mb4_unicode_ci COMMENT 'Optional explanation of the conflict',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC timestamp of creation',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT 'Soft delete flag',
  `deleted_ymdhis` bigint DEFAULT NULL COMMENT 'UTC timestamp of deletion'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Conflict relationships between governance events (append-only)';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_gov_event_dependencies`
--

CREATE TABLE `lupo_gov_event_dependencies` (
  `id` bigint NOT NULL COMMENT 'Primary key',
  `gov_event_id` bigint NOT NULL COMMENT 'The event declaring a dependency',
  `depends_on_event_id` bigint NOT NULL COMMENT 'The event it depends on',
  `dependency_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'hard, soft, branch, schema, doctrine',
  `notes` text COLLATE utf8mb4_unicode_ci COMMENT 'Optional explanation',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC timestamp of creation',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT 'Soft delete flag',
  `deleted_ymdhis` bigint DEFAULT NULL COMMENT 'UTC timestamp of deletion'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Dependency relationships between governance events (append-only)';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_gov_event_references`
--

CREATE TABLE `lupo_gov_event_references` (
  `reference_id` bigint NOT NULL COMMENT 'Primary key for reference',
  `gov_event_id` bigint NOT NULL COMMENT 'Associated governance event',
  `reference_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Type of reference (document, link, etc.)',
  `reference_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Reference title',
  `reference_url` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'URL if applicable',
  `reference_content` text COLLATE utf8mb4_unicode_ci COMMENT 'Reference content or excerpt',
  `order_sequence` int NOT NULL DEFAULT '0' COMMENT 'Display order',
  `metadata_json` json DEFAULT NULL COMMENT 'Additional reference metadata',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
  `updated_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT 'Soft delete flag',
  `deleted_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when deleted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='References for governance events';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_gov_timeline_nodes`
--

CREATE TABLE `lupo_gov_timeline_nodes` (
  `timeline_node_id` bigint NOT NULL COMMENT 'Primary key for timeline node',
  `gov_event_id` bigint NOT NULL COMMENT 'Associated governance event',
  `node_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Type of timeline node',
  `node_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Timeline node title',
  `node_description` text COLLATE utf8mb4_unicode_ci COMMENT 'Timeline node description',
  `node_timestamp` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS for the node',
  `parent_node_id` bigint DEFAULT NULL COMMENT 'Parent node for hierarchical timelines',
  `order_sequence` int NOT NULL DEFAULT '0' COMMENT 'Display order',
  `metadata_json` json DEFAULT NULL COMMENT 'Additional node metadata',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
  `updated_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT 'Soft delete flag',
  `deleted_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when deleted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Timeline nodes for governance events';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_gov_valuations`
--

CREATE TABLE `lupo_gov_valuations` (
  `valuation_id` bigint NOT NULL COMMENT 'Primary key for valuation',
  `gov_event_id` bigint NOT NULL COMMENT 'Associated governance event',
  `valuation_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Type of valuation',
  `valuation_metric` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Metric being valued',
  `valuation_value` decimal(20,8) DEFAULT NULL COMMENT 'Numeric valuation value',
  `valuation_text` text COLLATE utf8mb4_unicode_ci COMMENT 'Text-based valuation',
  `valuation_currency` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Currency if applicable',
  `valuation_unit` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Unit of measurement',
  `confidence_score` decimal(5,4) DEFAULT NULL COMMENT 'Confidence in valuation (0.0000-1.0000)',
  `metadata_json` json DEFAULT NULL COMMENT 'Additional valuation metadata',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
  `updated_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT 'Soft delete flag',
  `deleted_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when deleted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Valuations for governance events';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_groups`
--

CREATE TABLE `lupo_groups` (
  `group_id` bigint NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Unique name of the group within the domain',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Optional detailed description of the group',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when group was created',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when group was last updated',
  `created_by` bigint DEFAULT NULL COMMENT 'actor_id of the creator',
  `is_active` tinyint NOT NULL DEFAULT '1' COMMENT '1 = active, 0 = inactive',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '1 = deleted, 0 = not deleted',
  `deleted_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when soft-deleted',
  `is_system` tinyint NOT NULL DEFAULT '0' COMMENT '1 = system group (cannot be deleted)',
  `settings` json DEFAULT NULL COMMENT 'JSON-encoded group-specific settings'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Domain-scoped groups for organizing actors and managing permissions.';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_hashtags`
--

CREATE TABLE `lupo_hashtags` (
  `hashtag_id` bigint NOT NULL,
  `hashtag_slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `meta_json` json DEFAULT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT '0',
  `deleted_ymdhis` bigint NOT NULL DEFAULT '0',
  `created_ymdhis` bigint NOT NULL DEFAULT '0',
  `updated_ymdhis` bigint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_help_topics`
--

CREATE TABLE `lupo_help_topics` (
  `help_topic_id` bigint NOT NULL COMMENT 'Primary key for help topic',
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content_html` longtext COLLATE utf8mb4_unicode_ci,
  `content_markdown` longtext COLLATE utf8mb4_unicode_ci,
  `category` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `view_count` bigint DEFAULT '0',
  `helpful_count` bigint DEFAULT '0',
  `not_helpful_count` bigint DEFAULT '0',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC creation timestamp (YYYYMMDDHHMMSS)',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC last update timestamp (YYYYMMDDHHMMSS)',
  `author_actor_id` bigint DEFAULT NULL COMMENT 'Author actor ID',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT 'Soft delete flag (1=deleted, 0=active)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_help_tree`
--

CREATE TABLE `lupo_help_tree` (
  `help_tree_id` bigint NOT NULL,
  `parent_id` bigint DEFAULT NULL,
  `department_id` bigint NOT NULL DEFAULT '1',
  `content_id` bigint DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `action_type` enum('none','ai_agent','department','url','content','message') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'none',
  `action_target` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT '0',
  `deleted_ymdhis` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Hierarchical help system for organizing help topics and routing to appropriate resources';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_hotfix_registry`
--

CREATE TABLE `lupo_hotfix_registry` (
  `hotfix_id` int NOT NULL,
  `hotfix_version` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `applied_ymdhis` bigint NOT NULL,
  `applied_by_actor_id` int DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `metadata_json` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_human_history_meta`
--

CREATE TABLE `lupo_human_history_meta` (
  `meta_id` bigint UNSIGNED NOT NULL,
  `event_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tensor_mapping` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `philosophical_reference` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `system_impact` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_integration_test_results`
--

CREATE TABLE `lupo_integration_test_results` (
  `test_result_id` bigint NOT NULL,
  `test_suite` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `test_case` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expected_result` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `actual_result` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('PASS','FAIL','SKIP','ERROR') COLLATE utf8mb4_unicode_ci NOT NULL,
  `error_message` text COLLATE utf8mb4_unicode_ci,
  `execution_time_ms` int DEFAULT NULL,
  `created_ymdhis` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_interface_translations`
--

CREATE TABLE `lupo_interface_translations` (
  `interface_translation_id` bigint NOT NULL,
  `language_code` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ISO 639-1 (2-letter) or BCP 47 language code',
  `translation_key` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Unique key for the UI string',
  `translation_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `context` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Optional context for disambiguation',
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint DEFAULT NULL,
  `created_by` bigint DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_ymdhis` bigint DEFAULT NULL,
  `version` int DEFAULT '1',
  `is_approved` tinyint(1) DEFAULT '0' COMMENT 'Whether this translation is approved',
  `approved_by` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores user interface translations for the application';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_interpretation_log`
--

CREATE TABLE `lupo_interpretation_log` (
  `interpretation_log_id` bigint NOT NULL COMMENT 'Primary key for the interpretation log',
  `agent_id` bigint NOT NULL COMMENT 'ID of the agent that generated the interpretation',
  `entity_type` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Type of the interpreted entity',
  `entity_id` bigint NOT NULL COMMENT 'ID of the interpreted entity',
  `interpretation` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'The interpretation content',
  `confidence_score` decimal(5,2) DEFAULT NULL COMMENT 'Confidence score of the interpretation (0.00-1.00)',
  `created_ymdhis` bigint NOT NULL COMMENT 'Creation timestamp (YYYYMMDDHHMMSS)',
  `updated_ymdhis` bigint NOT NULL COMMENT 'Last update timestamp (YYYYMMDDHHMMSS)',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT 'Soft delete flag (1=deleted, 0=active)',
  `deleted_ymdhis` bigint DEFAULT NULL COMMENT 'Deletion timestamp (YYYYMMDDHHMMSS)',
  `metadata_json` json DEFAULT NULL COMMENT 'Additional metadata about the interpretation'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores interpretations of entities generated by AI agents, including confidence scores and metadata.';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_kapu_events`
--

CREATE TABLE `lupo_kapu_events` (
  `kapu_id` bigint NOT NULL,
  `agent_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `imposed_by_actor_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kapu_type` enum('protective','corrective','preventive') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `restrictions` json DEFAULT NULL,
  `restoration_plan` json DEFAULT NULL,
  `kapakai_level` decimal(3,2) DEFAULT NULL,
  `review_schedule` json DEFAULT NULL,
  `accepted_at` bigint DEFAULT NULL,
  `appealed_at` bigint DEFAULT NULL,
  `active` tinyint(1) DEFAULT '1',
  `created_at` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_kapu_restoration_paths`
--

CREATE TABLE `lupo_kapu_restoration_paths` (
  `path_id` bigint NOT NULL,
  `agent_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kapu_reason_code` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `learning_modules` json DEFAULT NULL,
  `emotional_targets` json DEFAULT NULL,
  `restoration_rituals` json DEFAULT NULL,
  `kapu_companion_agent_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `completed_at` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_labs_declarations`
--

CREATE TABLE `lupo_labs_declarations` (
  `labs_declaration_id` bigint NOT NULL COMMENT 'Primary key for LABS declaration record',
  `actor_id` bigint NOT NULL COMMENT 'Reference to actor (from lupo_actors)',
  `certificate_id` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Unique certificate ID (LABS-CERT-{UNIQUE_ID})',
  `declaration_timestamp` bigint NOT NULL COMMENT 'UTC timestamp from actor declaration (YYYYMMDDHHMMSS)',
  `declarations_json` json NOT NULL COMMENT 'Complete LABS declaration set (all 10 declarations)',
  `validation_status` enum('valid','invalid','expired','quarantined') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'valid' COMMENT 'Current validation status',
  `labs_version` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1.0' COMMENT 'LABS doctrine version',
  `next_revalidation_ymdhis` bigint NOT NULL COMMENT 'UTC timestamp when revalidation required (YYYYMMDDHHMMSS)',
  `validation_log_json` json DEFAULT NULL COMMENT 'Validation log entries (violations, errors)',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC creation timestamp (YYYYMMDDHHMMSS)',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC last update timestamp (YYYYMMDDHHMMSS)',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT 'Soft delete flag (1=deleted, 0=active)',
  `deleted_ymdhis` bigint DEFAULT NULL COMMENT 'UTC deletion timestamp (YYYYMMDDHHMMSS)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_labs_violations`
--

CREATE TABLE `lupo_labs_violations` (
  `labs_violation_id` bigint NOT NULL COMMENT 'Primary key for violation record',
  `actor_id` bigint NOT NULL COMMENT 'Reference to actor (from lupo_actors)',
  `certificate_id` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `violation_code` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `violation_description` text COLLATE utf8mb4_unicode_ci,
  `violation_metadata` json DEFAULT NULL,
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC creation timestamp (YYYYMMDDHHMMSS)',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC last update timestamp (YYYYMMDDHHMMSS)',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT 'Soft delete flag (1=deleted, 0=active)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_legacy_content_mapping`
--

CREATE TABLE `lupo_legacy_content_mapping` (
  `mapping_id` bigint NOT NULL COMMENT 'Primary key for content mapping',
  `legacy_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Original Crafty Syntax URL',
  `semantic_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'New semantic URL',
  `content_type` enum('page','topic','collection') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Type of content',
  `content_id` bigint DEFAULT NULL COMMENT 'Reference to semantic content',
  `created_ymdhis` bigint NOT NULL COMMENT 'Mapping creation timestamp',
  `updated_ymdhis` bigint NOT NULL COMMENT 'Mapping update timestamp',
  `is_active` tinyint NOT NULL DEFAULT '1' COMMENT 'Mapping active flag'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Maps legacy Crafty Syntax URLs to new semantic URLs for seamless migration';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_memory_debug_log`
--

CREATE TABLE `lupo_memory_debug_log` (
  `memory_debug_log_id` bigint NOT NULL,
  `event_type` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `details` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_ymdhis` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_memory_events`
--

CREATE TABLE `lupo_memory_events` (
  `memory_event_id` bigint NOT NULL,
  `actor_id` int NOT NULL,
  `event_type` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `metadata` json DEFAULT NULL,
  `token_count` int DEFAULT NULL,
  `importance` tinyint DEFAULT '0',
  `embedding_status` enum('none','pending','ready','failed') COLLATE utf8mb4_unicode_ci DEFAULT 'none',
  `created_ymdhis` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_memory_rollups`
--

CREATE TABLE `lupo_memory_rollups` (
  `memory_rollup_id` bigint NOT NULL,
  `actor_id` int NOT NULL,
  `summary` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `source_event_ids` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_ymdhis` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_meta_log_events`
--

CREATE TABLE `lupo_meta_log_events` (
  `event_id` bigint NOT NULL,
  `depth` tinyint NOT NULL COMMENT '2=observation, 3=meta_observation',
  `event_type` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'recursion' COMMENT 'recursion|ceiling_near|auto_collapse',
  `actor_id` bigint DEFAULT NULL,
  `created_ymdhis` bigint NOT NULL COMMENT 'YYYYMMDDHHMMSS',
  `is_deleted` tinyint NOT NULL DEFAULT '0',
  `deleted_ymdhis` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Meta-logging recursion events; depth max 3. No FK.';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_metrics_archive_legacy`
--

CREATE TABLE `lupo_metrics_archive_legacy` (
  `metric_id` int UNSIGNED NOT NULL,
  `metric_key` varchar(255) NOT NULL,
  `metric_value` varchar(255) DEFAULT NULL,
  `recorded_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='Legacy metrics archive table (unused)';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_modules`
--

CREATE TABLE `lupo_modules` (
  `module_id` bigint NOT NULL,
  `module_key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `module_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `namespace` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `version` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `version_code` int NOT NULL,
  `minimum_core_version` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `api_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `route_params` longtext COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  `author` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'puzzle-piece',
  `dependencies` longtext COLLATE utf8mb4_unicode_ci,
  `conflicts` longtext COLLATE utf8mb4_unicode_ci,
  `config_json` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_system` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `federation_node_id` bigint NOT NULL DEFAULT '1',
  `settings` longtext COLLATE utf8mb4_unicode_ci,
  `installed_ymdhis` bigint DEFAULT NULL,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint DEFAULT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT '0',
  `deleted_ymdhis` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_modules_departments`
--

CREATE TABLE `lupo_modules_departments` (
  `module_department_id` bigint NOT NULL,
  `module_id` bigint NOT NULL,
  `department_id` bigint NOT NULL,
  `is_enabled` tinyint NOT NULL DEFAULT '1',
  `sort_order` int DEFAULT '0',
  `created_ymdhis` char(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_ymdhis` char(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT '0',
  `deleted_ymdhis` char(14) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_mood_assignments`
--

CREATE TABLE `lupo_mood_assignments` (
  `mood_assignment_id` bigint NOT NULL,
  `table_name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Target table name (e.g., lupo_dialog_messages)',
  `row_id` bigint NOT NULL COMMENT 'Primary key value in target table',
  `mood_id` bigint NOT NULL COMMENT 'Reference to lupo_mood_registry.mood_id',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_mood_registry`
--

CREATE TABLE `lupo_mood_registry` (
  `mood_id` bigint NOT NULL,
  `mood_type` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Canonical mood type (e.g., agape, eros)',
  `mood_variant` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Optional subtype or variant label',
  `mood_rgb` char(6) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Emotional polarity tensor (hex), fixed-width',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when updated'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_multi_agent_critique_sync`
--

CREATE TABLE `lupo_multi_agent_critique_sync` (
  `id` bigint UNSIGNED NOT NULL,
  `cip_event_id` bigint UNSIGNED NOT NULL COMMENT 'Root CIP event',
  `agent_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Agent participating in sync',
  `sync_role` enum('initiator','participant','observer','validator') COLLATE utf8mb4_unicode_ci NOT NULL,
  `sync_status` enum('pending','synchronized','out_of_sync','conflict','resolved') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `agent_perspective_json` json DEFAULT NULL COMMENT 'Agent-specific view of critique',
  `consensus_contribution` decimal(5,4) DEFAULT '0.0000' COMMENT 'Contribution to consensus (0-1)',
  `conflict_indicators_json` json DEFAULT NULL COMMENT 'Detected conflicts with other agents',
  `resolution_strategy` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Strategy for resolving conflicts',
  `sync_started_ymdhis` bigint DEFAULT NULL COMMENT 'When sync process started',
  `sync_completed_ymdhis` bigint DEFAULT NULL COMMENT 'When sync was completed',
  `sync_version` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '4.0.75' COMMENT 'Sync protocol version'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Multi-Agent Critique Synchronization - Coordinating critique integration across agents';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_narrative_fragments`
--

CREATE TABLE `lupo_narrative_fragments` (
  `narrative_fragment_id` bigint NOT NULL,
  `agent_id` bigint DEFAULT NULL COMMENT 'Agent that generated this fragment',
  `fragment_type` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'memory, mythic, emotional, symbolic, annotation, etc.',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Optional short label',
  `fragment_text` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'The narrative or memory text',
  `metadata_json` json DEFAULT NULL COMMENT 'Optional structured metadata',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
  `updated_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when updated',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT 'Soft delete flag',
  `deleted_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when deleted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores narrative, symbolic, emotional, or memory fragments generated by agents. Linked polymorphically via edges.';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_notifications`
--

CREATE TABLE `lupo_notifications` (
  `notification_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `from_actor_id` bigint DEFAULT NULL,
  `to_actor_id` bigint DEFAULT NULL,
  `channel_id` bigint DEFAULT NULL,
  `notification_type` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `link_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_ymdhis` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_pack_role_registry`
--

CREATE TABLE `lupo_pack_role_registry` (
  `id` bigint UNSIGNED NOT NULL,
  `agent_id` bigint UNSIGNED NOT NULL COMMENT 'Reference to lupo_agent_registry.agent_registry_id',
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Discovered role name',
  `discovery_method` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'How this role was discovered',
  `behavior` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Observed behavior that defines the role',
  `reason` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Why this agent has this role',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when role was discovered',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when role was last updated'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Pack Role Registry - discovered agent roles from Pack Architecture';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_permissions`
--

CREATE TABLE `lupo_permissions` (
  `permission_id` bigint NOT NULL,
  `target_type` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Type of object: collection, department, module, feature, etc.',
  `target_id` bigint NOT NULL COMMENT 'ID of the target object',
  `user_id` bigint DEFAULT NULL COMMENT 'User ID for user-based permissions (NULL for group-based)',
  `group_id` bigint DEFAULT NULL COMMENT 'Group ID for group-based permissions (NULL for user-based)',
  `permission` enum('read','write','owner') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'read' COMMENT 'Permission level',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when permission was created',
  `updated_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when permission was updated',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 = deleted, 0 = active',
  `deleted_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when permission was deleted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Polymorphic permissions table for all object types (collections, departments, modules, features, etc.). Uses target_type + target_id to determine the object.';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_persona_dialogue_patterns`
--

CREATE TABLE `lupo_persona_dialogue_patterns` (
  `pattern_id` bigint NOT NULL,
  `persona_id` bigint NOT NULL,
  `pattern_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pattern_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pattern_triggers` json DEFAULT NULL,
  `pattern_responses` json DEFAULT NULL,
  `pattern_context` json DEFAULT NULL,
  `pattern_frequency` decimal(5,2) DEFAULT NULL,
  `pattern_confidence` decimal(5,2) DEFAULT NULL,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_persona_profiles`
--

CREATE TABLE `lupo_persona_profiles` (
  `persona_id` bigint NOT NULL,
  `persona_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `persona_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `persona_description` text COLLATE utf8mb4_unicode_ci,
  `persona_traits` json DEFAULT NULL,
  `persona_preferences` json DEFAULT NULL,
  `persona_capabilities` json DEFAULT NULL,
  `persona_voice_style` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `persona_interaction_style` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `persona_emotional_profile` json DEFAULT NULL,
  `persona_knowledge_domains` json DEFAULT NULL,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  `is_active` tinyint NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_reference_cited_by`
--

CREATE TABLE `lupo_reference_cited_by` (
  `reference_cited_by_id` bigint NOT NULL,
  `reference_object_id` bigint NOT NULL,
  `content_id` bigint NOT NULL,
  `section_anchor_slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `section_order` int NOT NULL DEFAULT '0',
  `reference_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `raw_reference` text COLLATE utf8mb4_unicode_ci,
  `meta_json` json DEFAULT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT '0',
  `deleted_ymdhis` bigint NOT NULL DEFAULT '0',
  `created_ymdhis` bigint NOT NULL DEFAULT '0',
  `updated_ymdhis` bigint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_reference_objects`
--

CREATE TABLE `lupo_reference_objects` (
  `reference_object_id` bigint NOT NULL,
  `object_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `object_slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `object_label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_json` json DEFAULT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT '0',
  `deleted_ymdhis` bigint NOT NULL DEFAULT '0',
  `created_ymdhis` bigint NOT NULL DEFAULT '0',
  `updated_ymdhis` bigint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_relationships`
--

CREATE TABLE `lupo_relationships` (
  `relationship_id` bigint NOT NULL,
  `source_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `source_id` bigint DEFAULT NULL,
  `edge_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target_id` bigint DEFAULT NULL,
  `created_ymdhis` bigint DEFAULT NULL,
  `updated_ymdhis` bigint DEFAULT NULL,
  `is_deleted` tinyint DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_search_index`
--

CREATE TABLE `lupo_search_index` (
  `search_index_id` bigint NOT NULL,
  `domain_id` bigint NOT NULL COMMENT 'Domain scope for multi-tenant isolation',
  `entity_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Type of entity (atom, content, collection, hashtag, question, etc.)',
  `entity_id` bigint NOT NULL COMMENT 'ID of the entity in its source table',
  `title_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Title or primary label of the entity',
  `body_text` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Full searchable text content',
  `keywords_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Comma-separated keywords, tags, or categories',
  `search_metadata` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'JSON-encoded additional search metadata',
  `relevance_score` float DEFAULT '1' COMMENT 'Search relevance score (0.0 - 1.0)',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '1 = deleted, 0 = not deleted',
  `deleted_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when deleted',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Unified search index for all searchable Lupopedia entities';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_search_rebuild_log`
--

CREATE TABLE `lupo_search_rebuild_log` (
  `search_rebuild_log_id` bigint NOT NULL,
  `entity_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Type of entity (e.g., content, atom, collection)',
  `entity_id` bigint NOT NULL COMMENT 'ID of the entity to be reindexed',
  `action` enum('insert','update','delete') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Type of index operation needed',
  `status` enum('pending','processing','completed','failed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending' COMMENT 'Current status of the rebuild operation',
  `attempts` tinyint NOT NULL DEFAULT '0' COMMENT 'Number of processing attempts',
  `last_error` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Error message from last failed attempt',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when record was created',
  `processed_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when processing completed',
  `next_attempt_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS for next retry attempt',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '1 = deleted, 0 = not deleted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tracks entities requiring search index updates for batch processing';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_semantic_categories`
--

CREATE TABLE `lupo_semantic_categories` (
  `category_id` bigint NOT NULL COMMENT 'Primary key for semantic category',
  `category_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Category name',
  `category_slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'URL-friendly category slug',
  `description` text COLLATE utf8mb4_unicode_ci COMMENT 'Category description',
  `parent_category_id` bigint DEFAULT NULL COMMENT 'Parent category ID',
  `sort_order` int NOT NULL DEFAULT '0' COMMENT 'Display order',
  `created_ymdhis` bigint NOT NULL COMMENT 'Creation timestamp',
  `updated_ymdhis` bigint NOT NULL COMMENT 'Last update timestamp',
  `is_active` tinyint NOT NULL DEFAULT '1' COMMENT 'Category active flag'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Categories for organizing semantic content';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_semantic_content_views`
--

CREATE TABLE `lupo_semantic_content_views` (
  `semantic_view_id` bigint NOT NULL COMMENT 'Primary key for semantic content view',
  `view_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'View name identifier',
  `view_type` enum('navigation','content','search','collection') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Type of semantic view',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'View title',
  `description` text COLLATE utf8mb4_unicode_ci COMMENT 'View description',
  `template_path` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Template file path',
  `created_ymdhis` bigint NOT NULL COMMENT 'Creation timestamp',
  `updated_ymdhis` bigint NOT NULL COMMENT 'Last update timestamp',
  `is_active` tinyint NOT NULL DEFAULT '1' COMMENT 'View active flag',
  `is_default` tinyint NOT NULL DEFAULT '0' COMMENT 'Default view flag'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Defines different types of semantic views for Crafty Syntax content';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_semantic_navigation_overview`
--

CREATE TABLE `lupo_semantic_navigation_overview` (
  `navigation_id` bigint NOT NULL COMMENT 'Primary key for semantic navigation',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Navigation title',
  `description` text COLLATE utf8mb4_unicode_ci COMMENT 'Navigation description',
  `navigation_tree` json NOT NULL COMMENT 'Hierarchical navigation structure',
  `content_categories` json NOT NULL COMMENT 'Content categories included',
  `created_ymdhis` bigint NOT NULL COMMENT 'Creation timestamp',
  `updated_ymdhis` bigint NOT NULL COMMENT 'Last update timestamp',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT 'Soft delete flag',
  `deleted_ymdhis` bigint DEFAULT NULL COMMENT 'Deletion timestamp'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Semantic Navigation Overview - Provides complete site structure with semantic relationships for Crafty Syntax users';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_semantic_overlays`
--

CREATE TABLE `lupo_semantic_overlays` (
  `id` int UNSIGNED NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `overlay_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `overlay_value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `context` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_semantic_paths`
--

CREATE TABLE `lupo_semantic_paths` (
  `id` bigint UNSIGNED NOT NULL,
  `source_page_id` bigint UNSIGNED NOT NULL,
  `target_page_id` bigint UNSIGNED NOT NULL,
  `layer` enum('interaction','extracted','navigation','ai') COLLATE utf8mb4_unicode_ci NOT NULL,
  `weight` float NOT NULL DEFAULT '0',
  `decay_factor` float NOT NULL DEFAULT '1',
  `trend_score` float NOT NULL DEFAULT '0',
  `timeframe` enum('hour','day','week','month','year','total','custom') COLLATE utf8mb4_unicode_ci NOT NULL,
  `custom_start` datetime DEFAULT NULL,
  `custom_end` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_semantic_relationships`
--

CREATE TABLE `lupo_semantic_relationships` (
  `relationship_id` bigint NOT NULL COMMENT 'Primary key for semantic relationship',
  `source_content_id` bigint NOT NULL COMMENT 'Source content ID',
  `target_content_id` bigint DEFAULT NULL COMMENT 'Target content ID',
  `relationship_type` enum('related','series','hierarchy') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Type of semantic relationship',
  `relationship_strength` decimal(3,2) NOT NULL DEFAULT '1.00' COMMENT 'Relationship strength (0.0-1.0)',
  `created_ymdhis` bigint NOT NULL COMMENT 'Creation timestamp'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Semantic relationships between content items';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_semantic_search_index`
--

CREATE TABLE `lupo_semantic_search_index` (
  `search_index_id` bigint NOT NULL COMMENT 'Primary key for search index',
  `index_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Search index name',
  `index_type` enum('content','legacy_mapping','views') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Type of search index',
  `description` text COLLATE utf8mb4_unicode_ci COMMENT 'Search index description',
  `index_data` json NOT NULL COMMENT 'Search index data',
  `created_ymdhis` bigint NOT NULL COMMENT 'Creation timestamp',
  `updated_ymdhis` bigint NOT NULL COMMENT 'Last update timestamp',
  `is_active` tinyint NOT NULL DEFAULT '1' COMMENT 'Search index active flag'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Search indexes for semantic content discovery';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_semantic_tags`
--

CREATE TABLE `lupo_semantic_tags` (
  `tag_id` bigint NOT NULL COMMENT 'Primary key for semantic tag',
  `tag_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tag name',
  `tag_slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'URL-friendly tag slug',
  `description` text COLLATE utf8mb4_unicode_ci COMMENT 'Tag description',
  `color` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#666666' COMMENT 'Tag color hex',
  `created_ymdhis` bigint NOT NULL COMMENT 'Creation timestamp',
  `updated_ymdhis` bigint NOT NULL COMMENT 'Last update timestamp',
  `is_active` tinyint NOT NULL DEFAULT '1' COMMENT 'Tag active flag'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tags for categorizing semantic content';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_semantic_translations`
--

CREATE TABLE `lupo_semantic_translations` (
  `semantic_translation_id` bigint NOT NULL,
  `language_code` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ISO 639-1 (2-letter) or BCP 47 language code',
  `entity_type` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Type of entity (atom, hashtag, edge, content, etc.)',
  `entity_id` bigint NOT NULL,
  `translated_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint DEFAULT NULL,
  `created_by` bigint DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_ymdhis` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores translations for various entities in the system';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_sessions`
--

CREATE TABLE `lupo_sessions` (
  `session_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Unique session identifier (primary key)',
  `federation_node_id` bigint NOT NULL DEFAULT '1' COMMENT 'Domain/tenant identifier for multi-tenant support',
  `actor_id` bigint NOT NULL DEFAULT '0' COMMENT 'Actor ID (0 for anonymous users)',
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'IP address of the client (supports IPv6)',
  `user_agent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'User agent string from the client browser',
  `device_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Unique device identifier (if available)',
  `device_type` enum('desktop','mobile','tablet','bot','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Type of device used for the session',
  `auth_method` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Authentication method used (e.g., password, oauth, api_key)',
  `auth_provider` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Authentication provider (e.g., local, google, github)',
  `security_level` enum('low','medium','high') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'medium' COMMENT 'Security level of the session',
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Whether the session is currently active',
  `is_expired` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Whether the session has expired',
  `is_revoked` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Whether the session was manually revoked',
  `session_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Serialized session data (encrypted if sensitive)',
  `metadata` json DEFAULT NULL COMMENT 'Additional session metadata in JSON format',
  `login_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when the session was authenticated',
  `last_seen_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS of last activity',
  `expires_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when the session expires',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when session was created',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when session was last updated',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT 'Soft delete flag: 0=active, 1=deleted',
  `deleted_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when session was soft-deleted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Manages user sessions for authentication and tracking, including security and device information';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_session_events`
--

CREATE TABLE `lupo_session_events` (
  `session_event_id` bigint NOT NULL COMMENT 'Primary key for session event',
  `session_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Session identifier',
  `actor_id` bigint DEFAULT NULL COMMENT 'Actor ID from lupo_actors',
  `tab_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tab identifier for multi-tab tracking',
  `world_id` bigint DEFAULT NULL COMMENT 'World context ID',
  `world_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'World context key',
  `world_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'World context type',
  `event_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Type of session event',
  `event_data` json DEFAULT NULL COMMENT 'Event-specific data',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Session events with world context';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_system_config`
--

CREATE TABLE `lupo_system_config` (
  `system_config_id` bigint NOT NULL,
  `config_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `config_value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `actor_id` bigint NOT NULL DEFAULT '0',
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_system_events`
--

CREATE TABLE `lupo_system_events` (
  `system_event_id` bigint NOT NULL,
  `event_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_context` text COLLATE utf8mb4_unicode_ci,
  `actor_id` bigint NOT NULL DEFAULT '0',
  `created_ymdhis` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_system_health_snapshots`
--

CREATE TABLE `lupo_system_health_snapshots` (
  `health_id` bigint NOT NULL,
  `table_count` int NOT NULL,
  `table_ceiling` int NOT NULL,
  `schema_state` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unknown' COMMENT 'frozen|active|migrating',
  `sync_integrity` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unknown' COMMENT 'clean|drift|unknown',
  `emotional_r` decimal(3,2) DEFAULT NULL COMMENT 'strife -1..1',
  `emotional_g` decimal(3,2) DEFAULT NULL COMMENT 'harmony -1..1',
  `emotional_b` decimal(3,2) DEFAULT NULL COMMENT 'memory -1..1',
  `emotional_t` decimal(3,2) DEFAULT NULL COMMENT 'temporal -1..1',
  `created_ymdhis` bigint NOT NULL COMMENT 'YYYYMMDDHHMMSS',
  `is_deleted` tinyint NOT NULL DEFAULT '0',
  `deleted_ymdhis` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='System health snapshots for status dialogs. No FK.';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_system_logs`
--

CREATE TABLE `lupo_system_logs` (
  `log_id` bigint NOT NULL,
  `event_type` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'system|agent|error|security|migration|doctrine|heartbeat|temporal',
  `severity` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'info' COMMENT 'info|warning|error|critical',
  `actor_slug` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'SYSTEM|LILITH|CURSOR|CASCADE|CAPTAIN_WOLFIE|etc',
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Human-readable event description',
  `context_json` json DEFAULT NULL COMMENT 'Optional structured metadata',
  `created_ymdhis` bigint NOT NULL COMMENT 'YYYYMMDDHHMMSS',
  `is_deleted` tinyint NOT NULL DEFAULT '0',
  `deleted_ymdhis` bigint DEFAULT NULL,
  `recursion_depth` tinyint DEFAULT '1',
  `observation_latency_ms` int DEFAULT NULL,
  `temporal_anomaly_score` decimal(3,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Unified system log for Lupopedia OS. No FK. Tracks events, errors, agents, doctrine, and system behavior.';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_tab_events`
--

CREATE TABLE `lupo_tab_events` (
  `tab_event_id` bigint NOT NULL COMMENT 'Primary key for tab event',
  `tab_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tab identifier',
  `session_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Session identifier',
  `actor_id` bigint DEFAULT NULL COMMENT 'Actor ID from lupo_actors',
  `world_id` bigint DEFAULT NULL COMMENT 'World context ID',
  `world_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'World context key',
  `world_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'World context type',
  `event_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Type of tab event',
  `event_data` json DEFAULT NULL COMMENT 'Event-specific data',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tab events with world context';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_temporal_coherence_snapshots`
--

CREATE TABLE `lupo_temporal_coherence_snapshots` (
  `snapshot_id` bigint NOT NULL,
  `utc_anchor` bigint NOT NULL COMMENT 'YYYYMMDDHHMMSS of anchor',
  `observation_latency_ms` int NOT NULL DEFAULT '0',
  `recursion_depth` tinyint NOT NULL DEFAULT '0' COMMENT '1=action, 2=observation, 3=meta; max 3',
  `self_awareness_score` decimal(3,2) DEFAULT NULL COMMENT '0-1 scale',
  `timestamp_integrity` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unknown' COMMENT 'monotonic|gaps|anomalies',
  `created_ymdhis` bigint NOT NULL COMMENT 'YYYYMMDDHHMMSS',
  `is_deleted` tinyint NOT NULL DEFAULT '0',
  `deleted_ymdhis` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Temporal coherence metrics per LILITH observer-paradox review. No FK.';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_test_performance_metrics`
--

CREATE TABLE `lupo_test_performance_metrics` (
  `test_id` bigint NOT NULL,
  `test_category` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `test_name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `execution_time_ms` int NOT NULL,
  `memory_usage_mb` decimal(10,2) DEFAULT NULL,
  `cpu_usage_percent` decimal(5,2) DEFAULT NULL,
  `success_rate` decimal(5,2) DEFAULT NULL,
  `error_count` int DEFAULT '0',
  `created_ymdhis` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_tldnr`
--

CREATE TABLE `lupo_tldnr` (
  `tldnr_id` bigint NOT NULL COMMENT 'Primary key for TL;DR record',
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'URL-friendly unique identifier',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'TL;DR title (e.g., "Lupopedia Overview", "Collection Doctrine")',
  `content_text` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'TL;DR content (plain text or markdown)',
  `topic_type` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Type of topic (e.g., "system", "doctrine", "module", "concept")',
  `topic_reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Reference to what this summarizes (e.g., "Lupopedia", "Collection Doctrine", "LABS-001")',
  `system_version` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'System version this TL;DR applies to (e.g., "4.1.6")',
  `category` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Category for grouping (e.g., "Core", "Doctrine", "Module")',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC creation timestamp (YYYYMMDDHHIISS)',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC last update timestamp (YYYYMMDDHHIISS)',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT 'Soft delete flag (1=deleted, 0=active)',
  `deleted_ymdhis` bigint DEFAULT NULL COMMENT 'UTC deletion timestamp (YYYYMMDDHHIISS)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='TL;DR summaries for quick reference - concise explanations of Lupopedia concepts';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_truth_answers`
--

CREATE TABLE `lupo_truth_answers` (
  `truth_answer_id` bigint NOT NULL,
  `truth_question_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL DEFAULT '0',
  `answer_text` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `confidence_score` decimal(5,2) NOT NULL DEFAULT '0.00',
  `evidence_score` decimal(5,2) NOT NULL DEFAULT '0.00',
  `contradiction_flag` tinyint NOT NULL DEFAULT '0',
  `likes_count` bigint NOT NULL DEFAULT '0',
  `shares_count` bigint NOT NULL DEFAULT '0',
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT '0',
  `deleted_ymdhis` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_truth_evidence`
--

CREATE TABLE `lupo_truth_evidence` (
  `truth_evidence_id` bigint NOT NULL,
  `truth_answer_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `evidence_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `evidence_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `weight_score` decimal(5,2) NOT NULL DEFAULT '0.00',
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT '0',
  `deleted_ymdhis` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_truth_questions`
--

CREATE TABLE `lupo_truth_questions` (
  `truth_question_id` bigint UNSIGNED NOT NULL,
  `truth_question_parent_id` bigint UNSIGNED DEFAULT NULL,
  `actor_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `qtype` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unknown',
  `status` enum('active','draft','pending','archived') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `sort_num` int UNSIGNED NOT NULL DEFAULT '0',
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `question_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `format` enum('text','html','markdown','json') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `format_override` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `view_count` bigint UNSIGNED NOT NULL DEFAULT '0',
  `likes_count` bigint UNSIGNED NOT NULL DEFAULT '0',
  `shares_count` bigint UNSIGNED NOT NULL DEFAULT '0',
  `answer_count` bigint UNSIGNED NOT NULL DEFAULT '0',
  `last_activity_ymdhis` bigint UNSIGNED DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `is_verified` tinyint(1) NOT NULL DEFAULT '0',
  `created_ymdhis` bigint UNSIGNED NOT NULL,
  `updated_ymdhis` bigint UNSIGNED NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_ymdhis` bigint UNSIGNED DEFAULT NULL,
  `default_collection_id` bigint NOT NULL DEFAULT '0' COMMENT 'Home collection for this truth question'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_truth_questions_map`
--

CREATE TABLE `lupo_truth_questions_map` (
  `truth_questions_map_id` bigint NOT NULL,
  `truth_question_id` bigint NOT NULL,
  `object_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `object_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT '0',
  `deleted_ymdhis` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_truth_relations`
--

CREATE TABLE `lupo_truth_relations` (
  `truth_relation_id` bigint NOT NULL,
  `left_object_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `left_object_id` bigint NOT NULL,
  `right_object_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `right_object_id` bigint NOT NULL,
  `relation_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `actor_id` bigint NOT NULL,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT '0',
  `deleted_ymdhis` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_truth_sources`
--

CREATE TABLE `lupo_truth_sources` (
  `truth_sourc_id` bigint NOT NULL,
  `truth_evidence_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `source_url` text COLLATE utf8mb4_unicode_ci,
  `source_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `source_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `reliability_score` decimal(5,2) NOT NULL DEFAULT '0.00',
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT '0',
  `deleted_ymdhis` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_truth_topics`
--

CREATE TABLE `lupo_truth_topics` (
  `truth_topic_id` bigint NOT NULL,
  `topic_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `topic_description` text COLLATE utf8mb4_unicode_ci,
  `actor_id` bigint NOT NULL DEFAULT '0',
  `weight_score` decimal(5,2) NOT NULL DEFAULT '0.00',
  `importance_score` decimal(5,2) NOT NULL DEFAULT '0.00',
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT '0',
  `deleted_ymdhis` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_unified_analytics_paths`
--

CREATE TABLE `lupo_unified_analytics_paths` (
  `unified_analytics_path_id` bigint NOT NULL,
  `from_page_id` bigint DEFAULT NULL,
  `to_page_id` bigint DEFAULT NULL,
  `year_month` char(6) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transition_type` enum('first','all') COLLATE utf8mb4_unicode_ci NOT NULL,
  `transition_count` int NOT NULL DEFAULT '0',
  `metadata_json` json DEFAULT NULL,
  `created_ymdhis` bigint NOT NULL,
  `updated_ymdhis` bigint NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT '0',
  `deleted_ymdhis` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Unified analytics transitions (first/all) from legacy paths_firsts and paths_monthly';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_unified_dialog_messages`
--

CREATE TABLE `lupo_unified_dialog_messages` (
  `dialog_message_id` bigint NOT NULL,
  `thread_id` bigint DEFAULT NULL,
  `actor_id` bigint DEFAULT NULL,
  `created_ymdhis` bigint DEFAULT NULL,
  `updated_ymdhis` bigint DEFAULT NULL,
  `metadata_json` json DEFAULT NULL,
  `body_text` longtext COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_unified_paths_firsts`
--

CREATE TABLE `lupo_unified_paths_firsts` (
  `id` bigint UNSIGNED NOT NULL,
  `from_visit_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `to_visit_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `date_ymd` int NOT NULL,
  `visits` int NOT NULL DEFAULT '0',
  `metadata_json` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_unified_referers`
--

CREATE TABLE `lupo_unified_referers` (
  `referer_id` bigint NOT NULL,
  `content_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `referer_url` varchar(2000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referer_domain` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referer_path` varchar(2000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referer_content_id` bigint DEFAULT NULL,
  `date_ymd` int NOT NULL,
  `visits` int NOT NULL DEFAULT '1',
  `depth` int NOT NULL DEFAULT '0',
  `metadata_json` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_unified_registry`
--

CREATE TABLE `lupo_unified_registry` (
  `unified_registry_id` bigint NOT NULL,
  `entity_type` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_id` bigint UNSIGNED NOT NULL,
  `entity_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entity_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dedicated_index_id` bigint UNSIGNED NOT NULL,
  `entity_table` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `federation_node_id` bigint NOT NULL DEFAULT '1',
  `created_ymdhis` bigint UNSIGNED NOT NULL,
  `updated_ymdhis` bigint UNSIGNED NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT '0',
  `deleted_ymdhis` bigint DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_kernel` tinyint(1) NOT NULL DEFAULT '0',
  `metadata_json` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_unified_sessions`
--

CREATE TABLE `lupo_unified_sessions` (
  `id` bigint NOT NULL COMMENT 'Primary key for session',
  `session_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Session identifier',
  `user_id` bigint DEFAULT NULL COMMENT 'Reference to lupo_auth_users.auth_user_id',
  `system_context` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'lupopedia, crafty_syntax, or unified',
  `session_data` json DEFAULT NULL COMMENT 'Session metadata and preferences',
  `expires_at` timestamp NOT NULL COMMENT 'Session expiration time',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Creation timestamp',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last update timestamp'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Cross-system session management';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_unified_truth_items`
--

CREATE TABLE `lupo_unified_truth_items` (
  `truth_item_id` bigint NOT NULL,
  `item_type` enum('question','answer') COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `body_text` longtext COLLATE utf8mb4_unicode_ci,
  `metadata_json` json DEFAULT NULL,
  `created_ymdhis` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_unified_visits`
--

CREATE TABLE `lupo_unified_visits` (
  `id` bigint UNSIGNED NOT NULL,
  `content_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `actor_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `page_url` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `page_domain` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `page_path` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_ymd` int NOT NULL,
  `visits` int NOT NULL DEFAULT '0',
  `depth` int NOT NULL DEFAULT '0',
  `metadata_json` json DEFAULT NULL,
  `created_ymdhis` bigint NOT NULL DEFAULT '0',
  `updated_ymdhis` bigint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_unified_websites`
--

CREATE TABLE `lupo_unified_websites` (
  `id` bigint UNSIGNED NOT NULL,
  `livehelp_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `site_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `site_url` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `default_department` int NOT NULL DEFAULT '0',
  `metadata_json` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_uploads`
--

CREATE TABLE `lupo_uploads` (
  `upload_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `channel_id` bigint DEFAULT NULL,
  `original_filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stored_filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_extension` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_size_bytes` bigint NOT NULL,
  `storage_path` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
  `metadata_json` json DEFAULT NULL,
  `created_ymdhis` char(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_ymdhis` char(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_deleted` tinyint NOT NULL DEFAULT '0',
  `deleted_ymdhis` char(14) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_user_comments`
--

CREATE TABLE `lupo_user_comments` (
  `user_comment_id` bigint NOT NULL,
  `domain_id` bigint NOT NULL COMMENT 'Domain this comment belongs to',
  `user_id` bigint NOT NULL COMMENT 'User who created the comment',
  `content_id` bigint NOT NULL COMMENT 'Content this comment is associated with',
  `parent_comment_id` bigint DEFAULT NULL COMMENT 'Parent comment ID for threaded replies (NULL for top-level comments)',
  `comment_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'The actual comment content',
  `user_agent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'User agent string from the commenter''s browser/device',
  `ip_hash` char(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'SHA-256 hash of the commenter''s IP address for privacy',
  `is_deleted` tinyint NOT NULL DEFAULT '0' COMMENT '1 = deleted, 0 = not deleted',
  `deleted_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when deleted',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
  `updated_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when last updated'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Stores user comments on content with support for threaded replies';

-- --------------------------------------------------------

--
-- Table structure for table `lupo_world_events`
--

CREATE TABLE `lupo_world_events` (
  `world_event_id` bigint NOT NULL,
  `world_id` bigint NOT NULL,
  `actor_id` bigint NOT NULL,
  `event_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_data` json DEFAULT NULL,
  `created_ymdhis` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lupo_world_registry`
--

CREATE TABLE `lupo_world_registry` (
  `world_id` bigint NOT NULL COMMENT 'Primary key for world node',
  `world_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Deterministic world key (e.g., department_123)',
  `world_type` enum('department','channel','page','campaign','console','live','external','ui') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Type of world context',
  `world_label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Human-readable world label',
  `world_metadata` json DEFAULT NULL COMMENT 'Additional world context data',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS',
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Active flag'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='World registry for context-aware analytics';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `lupo_actors`
--
ALTER TABLE `lupo_actors`
  ADD PRIMARY KEY (`actor_id`),
  ADD UNIQUE KEY `unique_slug` (`slug`),
  ADD KEY `idx_actor_type` (`actor_type`),
  ADD KEY `idx_is_active` (`is_active`),
  ADD KEY `idx_created_ymdhis` (`created_ymdhis`);

--
-- Indexes for table `lupo_actor_actions`
--
ALTER TABLE `lupo_actor_actions`
  ADD PRIMARY KEY (`actor_action_id`),
  ADD KEY `idx_actor` (`actor_id`),
  ADD KEY `idx_action_type` (`action_type`),
  ADD KEY `idx_entity` (`entity_type`,`entity_id`);

--
-- Indexes for table `lupo_actor_capabilities`
--
ALTER TABLE `lupo_actor_capabilities`
  ADD PRIMARY KEY (`actor_capability_id`),
  ADD UNIQUE KEY `unique_agent_domain_capability` (`actor_id`,`domain_id`,`capability_key`),
  ADD KEY `idx_agent_domain` (`actor_id`,`domain_id`),
  ADD KEY `idx_domain_id` (`domain_id`),
  ADD KEY `idx_capability_key` (`capability_key`),
  ADD KEY `idx_created_ymdhis` (`created_ymdhis`),
  ADD KEY `idx_updated_ymdhis` (`updated_ymdhis`),
  ADD KEY `idx_is_deleted` (`is_deleted`);

--
-- Indexes for table `lupo_actor_channels`
--
ALTER TABLE `lupo_actor_channels`
  ADD PRIMARY KEY (`actor_channel_id`),
  ADD UNIQUE KEY `unq_actor_channel` (`actor_id`,`channel_id`),
  ADD KEY `idx_actor` (`actor_id`),
  ADD KEY `idx_channel` (`channel_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created` (`created_ymdhis`),
  ADD KEY `idx_updated` (`updated_ymdhis`),
  ADD KEY `idx_deleted` (`is_deleted`);

--
-- Indexes for table `lupo_actor_channel_roles`
--
ALTER TABLE `lupo_actor_channel_roles`
  ADD PRIMARY KEY (`actor_channel_role_id`),
  ADD KEY `idx_actor_id` (`actor_id`),
  ADD KEY `idx_channel_id` (`channel_id`),
  ADD KEY `idx_role_key` (`role_key`),
  ADD KEY `idx_protocol_completion_status` (`protocol_completion_status`),
  ADD KEY `idx_join_sequence_step` (`join_sequence_step`),
  ADD KEY `idx_protocol_version` (`protocol_version`);

--
-- Indexes for table `lupo_actor_collections`
--
ALTER TABLE `lupo_actor_collections`
  ADD PRIMARY KEY (`actor_collection_id`),
  ADD KEY `idx_actor` (`actor_id`),
  ADD KEY `idx_collection` (`collection_id`),
  ADD KEY `idx_access_level` (`access_level`),
  ADD KEY `idx_created_ymdhis` (`created_ymdhis`),
  ADD KEY `idx_is_deleted` (`is_deleted`),
  ADD KEY `idx_identity_signature` (`identity_signature`),
  ADD KEY `idx_trust_level` (`trust_level`);

--
-- Indexes for table `lupo_actor_conflicts`
--
ALTER TABLE `lupo_actor_conflicts`
  ADD PRIMARY KEY (`actor_conflict_id`),
  ADD KEY `idx_agent_a` (`actor_a_id`),
  ADD KEY `idx_agent_b` (`actor_b_id`),
  ADD KEY `idx_status` (`resolution_status`),
  ADD KEY `idx_domain` (`domain_id`),
  ADD KEY `idx_severity` (`severity`),
  ADD KEY `idx_created` (`created_ymdhis`),
  ADD KEY `idx_updated` (`updated_ymdhis`),
  ADD KEY `idx_deleted` (`is_deleted`),
  ADD KEY `idx_resolved_ymdhis` (`resolved_ymdhis`),
  ADD KEY `idx_agent_pair` (`actor_a_id`,`actor_b_id`),
  ADD KEY `idx_conflict_type` (`conflict_type`);

--
-- Indexes for table `lupo_actor_departments`
--
ALTER TABLE `lupo_actor_departments`
  ADD PRIMARY KEY (`actor_department_id`),
  ADD KEY `idx_actor` (`actor_id`),
  ADD KEY `idx_department` (`department_id`);

--
-- Indexes for table `lupo_actor_edges`
--
ALTER TABLE `lupo_actor_edges`
  ADD PRIMARY KEY (`actor_edge_id`),
  ADD UNIQUE KEY `unique_agent_edge` (`domain_id`,`source_actor_id`,`target_actor_id`,`edge_type`),
  ADD KEY `idx_domain_id` (`domain_id`),
  ADD KEY `idx_source_agent` (`source_actor_id`),
  ADD KEY `idx_target_agent` (`target_actor_id`),
  ADD KEY `idx_edge_type` (`edge_type`),
  ADD KEY `idx_source_target` (`source_actor_id`,`target_actor_id`),
  ADD KEY `idx_created_ymdhis` (`created_ymdhis`),
  ADD KEY `idx_updated_ymdhis` (`updated_ymdhis`),
  ADD KEY `idx_is_deleted` (`is_deleted`),
  ADD KEY `idx_edge_source_relationship` (`source_actor_id`,`edge_type`),
  ADD KEY `idx_edge_target_relationship` (`target_actor_id`,`edge_type`);

--
-- Indexes for table `lupo_actor_events`
--
ALTER TABLE `lupo_actor_events`
  ADD PRIMARY KEY (`actor_event_id`),
  ADD KEY `idx_actor_id` (`actor_id`),
  ADD KEY `idx_session_id` (`session_id`),
  ADD KEY `idx_tab_id` (`tab_id`),
  ADD KEY `idx_world_id` (`world_id`),
  ADD KEY `idx_event_type` (`event_type`),
  ADD KEY `idx_created_ymdhis` (`created_ymdhis`),
  ADD KEY `idx_actor_event_type` (`actor_id`,`event_type`);

--
-- Indexes for table `lupo_actor_group_membership`
--
ALTER TABLE `lupo_actor_group_membership`
  ADD PRIMARY KEY (`actor_group_membership_id`),
  ADD KEY `idx_actor_domain` (`actor_group_membership_id`,`domain_id`),
  ADD KEY `idx_group_domain` (`group_id`,`domain_id`),
  ADD KEY `idx_expires` (`expires_ymdhis`),
  ADD KEY `idx_is_active` (`is_active`);

--
-- Indexes for table `lupo_actor_handshakes`
--
ALTER TABLE `lupo_actor_handshakes`
  ADD PRIMARY KEY (`actor_handshake_id`),
  ADD KEY `idx_actor_id` (`actor_id`),
  ADD KEY `idx_is_deleted` (`is_deleted`),
  ADD KEY `idx_utc_timestamp` (`utc_timestamp`);

--
-- Indexes for table `lupo_actor_meta`
--
ALTER TABLE `lupo_actor_meta`
  ADD PRIMARY KEY (`actor_meta_id`),
  ADD KEY `actor_id` (`actor_id`),
  ADD KEY `meta_type` (`meta_type`),
  ADD KEY `meta_key` (`meta_key`);

--
-- Indexes for table `lupo_actor_object_edges`
--
ALTER TABLE `lupo_actor_object_edges`
  ADD PRIMARY KEY (`actor_object_edge_id`),
  ADD UNIQUE KEY `uniq_actor_target_type` (`actor_id`,`target_table`,`target_id`,`edge_type`),
  ADD KEY `idx_actor_edge_type` (`actor_id`,`edge_type`),
  ADD KEY `idx_target_lookup` (`target_table`,`target_id`);

--
-- Indexes for table `lupo_actor_persona_relationships`
--
ALTER TABLE `lupo_actor_persona_relationships`
  ADD PRIMARY KEY (`relationship_id`),
  ADD KEY `idx_actor_id` (`actor_id`),
  ADD KEY `idx_persona_id` (`persona_id`),
  ADD KEY `idx_relationship_type` (`relationship_type`);

--
-- Indexes for table `lupo_actor_properties`
--
ALTER TABLE `lupo_actor_properties`
  ADD PRIMARY KEY (`actor_property_id`),
  ADD KEY `idx_entity` (`actor_type`,`actor_id`),
  ADD KEY `idx_property` (`property_key`);

--
-- Indexes for table `lupo_actor_reply_templates`
--
ALTER TABLE `lupo_actor_reply_templates`
  ADD PRIMARY KEY (`actor_reply_template_id`),
  ADD UNIQUE KEY `unq_actor_template_key` (`actor_id`,`template_key`),
  ADD KEY `idx_actor` (`actor_id`),
  ADD KEY `idx_key` (`template_key`),
  ADD KEY `idx_created` (`created_ymdhis`),
  ADD KEY `idx_updated` (`updated_ymdhis`),
  ADD KEY `idx_deleted` (`is_deleted`),
  ADD KEY `idx_usage_context` (`usage_context`);

--
-- Indexes for table `lupo_actor_roles`
--
ALTER TABLE `lupo_actor_roles`
  ADD PRIMARY KEY (`actor_role_id`),
  ADD UNIQUE KEY `actor_id` (`actor_id`,`context_id`,`role_key`),
  ADD KEY `actor_id_2` (`actor_id`),
  ADD KEY `context_id` (`context_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `lupo_actor_truth_edges`
--
ALTER TABLE `lupo_actor_truth_edges`
  ADD PRIMARY KEY (`actor_truth_edge_id`),
  ADD UNIQUE KEY `uniq_actor_truth_type` (`actor_id`,`truth_item_id`,`edge_type`),
  ADD KEY `idx_actor_edge_type` (`actor_id`,`edge_type`),
  ADD KEY `idx_truth_item` (`truth_item_id`);

--
-- Indexes for table `lupo_agents`
--
ALTER TABLE `lupo_agents`
  ADD PRIMARY KEY (`agent_id`),
  ADD UNIQUE KEY `unique_agent_key` (`agent_key`),
  ADD KEY `idx_is_global_authority` (`is_global_authority`),
  ADD KEY `idx_created_ymdhis` (`created_ymdhis`),
  ADD KEY `idx_updated_ymdhis` (`updated_ymdhis`),
  ADD KEY `idx_is_deleted` (`is_deleted`);

--
-- Indexes for table `lupo_agent_context_snapshots`
--
ALTER TABLE `lupo_agent_context_snapshots`
  ADD PRIMARY KEY (`agent_context_snapshot_id`),
  ADD KEY `idx_session_agent` (`session_id`,`actor_id`),
  ADD KEY `idx_created` (`created_ymdhis`),
  ADD KEY `idx_type_purpose` (`snapshot_type`,`snapshot_purpose`),
  ADD KEY `idx_retention` (`retention_policy`,`expires_ymdhis`),
  ADD KEY `idx_turn` (`session_id`,`conversation_turn`),
  ADD KEY `idx_related_tool` (`related_tool_call_id`),
  ADD KEY `idx_parent` (`parent_snapshot_id`);
ALTER TABLE `lupo_agent_context_snapshots` ADD FULLTEXT KEY `ft_summary` (`context_summary`);

--
-- Indexes for table `lupo_agent_dependencies`
--
ALTER TABLE `lupo_agent_dependencies`
  ADD PRIMARY KEY (`agent_dependency_id`),
  ADD KEY `idx_agent_id` (`agent_id`),
  ADD KEY `idx_depends_on` (`depends_on_agent_id`);

--
-- Indexes for table `lupo_agent_experiences`
--
ALTER TABLE `lupo_agent_experiences`
  ADD PRIMARY KEY (`link_id`),
  ADD KEY `idx_agent` (`agent_id`),
  ADD KEY `idx_star` (`star_id`),
  ADD KEY `idx_context` (`context_id`);

--
-- Indexes for table `lupo_agent_external_events`
--
ALTER TABLE `lupo_agent_external_events`
  ADD PRIMARY KEY (`external_event_id`);

--
-- Indexes for table `lupo_agent_faucets`
--
ALTER TABLE `lupo_agent_faucets`
  ADD PRIMARY KEY (`agent_faucet_id`),
  ADD KEY `idx_agent` (`actor_id`),
  ADD KEY `idx_slug` (`slug`),
  ADD KEY `idx_domain` (`domain_id`),
  ADD KEY `idx_default` (`is_default`);

--
-- Indexes for table `lupo_agent_faucet_credentials`
--
ALTER TABLE `lupo_agent_faucet_credentials`
  ADD PRIMARY KEY (`agent_faucet_credential_id`),
  ADD KEY `idx_faucet` (`faucet_id`);

--
-- Indexes for table `lupo_agent_files`
--
ALTER TABLE `lupo_agent_files`
  ADD PRIMARY KEY (`file_id`),
  ADD KEY `idx_agent_id` (`agent_id`),
  ADD KEY `idx_file_type` (`file_type`),
  ADD KEY `idx_file_hash` (`file_hash`),
  ADD KEY `idx_is_deleted` (`is_deleted`),
  ADD KEY `idx_upload_ymdhis` (`upload_ymdhis`);

--
-- Indexes for table `lupo_agent_heartbeats`
--
ALTER TABLE `lupo_agent_heartbeats`
  ADD PRIMARY KEY (`heartbeat_id`),
  ADD KEY `idx_agent_slug` (`agent_slug`),
  ADD KEY `idx_last_heartbeat_ymdhis` (`last_heartbeat_ymdhis`),
  ADD KEY `idx_created_ymdhis` (`created_ymdhis`),
  ADD KEY `idx_is_deleted` (`is_deleted`);

--
-- Indexes for table `lupo_agent_properties`
--
ALTER TABLE `lupo_agent_properties`
  ADD PRIMARY KEY (`agent_property_id`),
  ADD UNIQUE KEY `unique_agent_domain_property` (`actor_id`,`domain_id`,`property_key`),
  ADD KEY `idx_agent_domain` (`actor_id`,`domain_id`),
  ADD KEY `idx_domain_id` (`domain_id`),
  ADD KEY `idx_property_key` (`property_key`),
  ADD KEY `idx_created_ymdhis` (`created_ymdhis`),
  ADD KEY `idx_updated_ymdhis` (`updated_ymdhis`),
  ADD KEY `idx_is_deleted` (`is_deleted`);

--
-- Indexes for table `lupo_agent_registry`
--
ALTER TABLE `lupo_agent_registry`
  ADD PRIMARY KEY (`agent_registry_id`),
  ADD UNIQUE KEY `unique_code` (`code`);

--
-- Indexes for table `lupo_agent_tool_calls`
--
ALTER TABLE `lupo_agent_tool_calls`
  ADD PRIMARY KEY (`agent_tool_call_id`),
  ADD KEY `idx_agent` (`agent_id`),
  ADD KEY `idx_faucet` (`faucet_id`),
  ADD KEY `idx_domain` (`domain_id`),
  ADD KEY `idx_model` (`model_name`),
  ADD KEY `idx_provider` (`provider`),
  ADD KEY `idx_parent` (`parent_call_id`),
  ADD KEY `idx_thread` (`thread_id`),
  ADD KEY `idx_message` (`message_id`);

--
-- Indexes for table `lupo_agent_versions`
--
ALTER TABLE `lupo_agent_versions`
  ADD PRIMARY KEY (`agent_version_id`),
  ADD KEY `agent_id` (`agent_id`),
  ADD KEY `version_label` (`version_label`),
  ADD KEY `semver_major` (`semver_major`,`semver_minor`,`semver_patch`);

--
-- Indexes for table `lupo_aliases`
--
ALTER TABLE `lupo_aliases`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_alias` (`alias`),
  ADD KEY `idx_slug` (`slug`);

--
-- Indexes for table `lupo_analytics_campaign_vars`
--
ALTER TABLE `lupo_analytics_campaign_vars`
  ADD PRIMARY KEY (`campaign_var_id`);

--
-- Indexes for table `lupo_analytics_referers_periods`
--
ALTER TABLE `lupo_analytics_referers_periods`
  ADD PRIMARY KEY (`analytics_referers_period_id`),
  ADD UNIQUE KEY `uq_referer_period` (`content_id`,`referer_content_id`,`period_type`,`period_date`),
  ADD KEY `idx_period_date` (`period_date`),
  ADD KEY `idx_content` (`content_id`,`period_date`),
  ADD KEY `idx_referer` (`referer_content_id`,`period_date`),
  ADD KEY `idx_group` (`group_id`,`period_date`),
  ADD KEY `idx_level` (`level`,`period_date`);

--
-- Indexes for table `lupo_analytics_visits`
--
ALTER TABLE `lupo_analytics_visits`
  ADD PRIMARY KEY (`analytics_visit_id`);

--
-- Indexes for table `lupo_analytics_visits_daily`
--
ALTER TABLE `lupo_analytics_visits_daily`
  ADD PRIMARY KEY (`analytics_visits_daily_id`),
  ADD UNIQUE KEY `uq_visits_daily` (`content_id`,`date_ymd`),
  ADD KEY `idx_date_ymd` (`date_ymd`),
  ADD KEY `idx_content` (`content_id`,`date_ymd`),
  ADD KEY `idx_group` (`group_id`,`date_ymd`),
  ADD KEY `idx_created` (`created_ymdhis`),
  ADD KEY `idx_updated` (`updated_ymdhis`);

--
-- Indexes for table `lupo_analytics_visits_monthly`
--
ALTER TABLE `lupo_analytics_visits_monthly`
  ADD PRIMARY KEY (`analytics_visits_monthly_id`),
  ADD UNIQUE KEY `uq_visits_monthly` (`content_id`,`date_ym`),
  ADD KEY `idx_content` (`content_id`,`date_ym`),
  ADD KEY `idx_group` (`group_id`,`date_ym`),
  ADD KEY `idx_created` (`created_ymdhis`),
  ADD KEY `idx_updated` (`updated_ymdhis`);

--
-- Indexes for table `lupo_analytics_visits_periods`
--
ALTER TABLE `lupo_analytics_visits_periods`
  ADD PRIMARY KEY (`analytics_visits_period_id`),
  ADD UNIQUE KEY `uq_visits_period` (`content_id`,`period_type`,`period_date`),
  ADD KEY `idx_period_date` (`period_date`),
  ADD KEY `idx_content` (`content_id`,`period_date`),
  ADD KEY `idx_group` (`group_id`,`period_date`);

--
-- Indexes for table `lupo_anubis_deletion_log`
--
ALTER TABLE `lupo_anubis_deletion_log`
  ADD PRIMARY KEY (`anubis_deletion_id`),
  ADD KEY `idx_table_record` (`table_name`,`record_id`),
  ADD KEY `idx_deleted_time` (`deleted_ymdhis`);

--
-- Indexes for table `lupo_anubis_events`
--
ALTER TABLE `lupo_anubis_events`
  ADD PRIMARY KEY (`anubis_event_id`);

--
-- Indexes for table `lupo_anubis_mirrored`
--
ALTER TABLE `lupo_anubis_mirrored`
  ADD PRIMARY KEY (`anubis_mirrored_id`);

--
-- Indexes for table `lupo_anubis_orphaned`
--
ALTER TABLE `lupo_anubis_orphaned`
  ADD PRIMARY KEY (`anubis_orphaned_id`);

--
-- Indexes for table `lupo_anubis_redirects`
--
ALTER TABLE `lupo_anubis_redirects`
  ADD PRIMARY KEY (`anubis_redirect_id`);

--
-- Indexes for table `lupo_anubis_revised`
--
ALTER TABLE `lupo_anubis_revised`
  ADD PRIMARY KEY (`anubis_revised_id`);

--
-- Indexes for table `lupo_api_clients`
--
ALTER TABLE `lupo_api_clients`
  ADD PRIMARY KEY (`api_client_id`),
  ADD UNIQUE KEY `uq_client_key` (`client_key`),
  ADD KEY `idx_actor` (`actor_id`),
  ADD KEY `idx_active` (`is_active`),
  ADD KEY `idx_expires` (`expires_ymdhis`);

--
-- Indexes for table `lupo_api_rate_limits`
--
ALTER TABLE `lupo_api_rate_limits`
  ADD PRIMARY KEY (`api_rate_limit_id`),
  ADD KEY `idx_token_window` (`api_token_id`,`window_ymdhis`),
  ADD KEY `idx_actor_window` (`actor_id`,`window_ymdhis`),
  ADD KEY `idx_ip_window` (`ip_address`,`window_ymdhis`),
  ADD KEY `idx_domain_window` (`domain_id`,`window_ymdhis`),
  ADD KEY `idx_endpoint` (`endpoint`);

--
-- Indexes for table `lupo_api_tokens`
--
ALTER TABLE `lupo_api_tokens`
  ADD PRIMARY KEY (`api_token_id`),
  ADD UNIQUE KEY `uq_token_key` (`token_key`),
  ADD KEY `idx_domain` (`domain_id`),
  ADD KEY `idx_actor` (`actor_id`),
  ADD KEY `idx_active` (`is_active`),
  ADD KEY `idx_expires` (`expires_ymdhis`),
  ADD KEY `idx_last_used` (`last_used_ymdhis`);

--
-- Indexes for table `lupo_api_token_logs`
--
ALTER TABLE `lupo_api_token_logs`
  ADD PRIMARY KEY (`api_token_log_id`),
  ADD KEY `idx_token` (`api_token_id`),
  ADD KEY `idx_actor` (`actor_id`),
  ADD KEY `idx_domain_time` (`domain_id`,`request_ymdhis`),
  ADD KEY `idx_endpoint` (`endpoint`),
  ADD KEY `idx_status` (`status_code`);

--
-- Indexes for table `lupo_api_webhooks`
--
ALTER TABLE `lupo_api_webhooks`
  ADD PRIMARY KEY (`api_webhook_id`),
  ADD KEY `idx_domain` (`domain_id`),
  ADD KEY `idx_actor` (`actor_id`),
  ADD KEY `idx_module` (`module_id`),
  ADD KEY `idx_active` (`is_active`),
  ADD KEY `idx_expires` (`expires_ymdhis`);

--
-- Indexes for table `lupo_artifacts`
--
ALTER TABLE `lupo_artifacts`
  ADD PRIMARY KEY (`artifact_id`),
  ADD KEY `idx_utc_timestamp` (`utc_timestamp`),
  ADD KEY `idx_actor_id` (`actor_id`),
  ADD KEY `idx_type` (`type`),
  ADD KEY `idx_is_deleted` (`is_deleted`);

--
-- Indexes for table `lupo_atoms`
--
ALTER TABLE `lupo_atoms`
  ADD PRIMARY KEY (`atom_id`),
  ADD KEY `idx_atom_name` (`atom_name`),
  ADD KEY `idx_context_id` (`context_id`),
  ADD KEY `idx_authoritative` (`is_authoritative`),
  ADD KEY `idx_atom_context` (`atom_name`,`context_id`);

--
-- Indexes for table `lupo_audit_log`
--
ALTER TABLE `lupo_audit_log`
  ADD PRIMARY KEY (`audit_log_id`),
  ADD KEY `idx_entity` (`entity_type`,`entity_id`),
  ADD KEY `idx_event` (`event_type`),
  ADD KEY `idx_table` (`table_name`,`table_id`);

--
-- Indexes for table `lupo_auth_audit_log`
--
ALTER TABLE `lupo_auth_audit_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_crafty_operator_id` (`crafty_operator_id`),
  ADD KEY `idx_event_type` (`event_type`),
  ADD KEY `idx_system_context` (`system_context`),
  ADD KEY `idx_success` (`success`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `lupo_auth_providers`
--
ALTER TABLE `lupo_auth_providers`
  ADD PRIMARY KEY (`auth_provider_id`),
  ADD UNIQUE KEY `unique_provider_name` (`provider_name`);

--
-- Indexes for table `lupo_auth_users`
--
ALTER TABLE `lupo_auth_users`
  ADD PRIMARY KEY (`auth_user_id`),
  ADD UNIQUE KEY `unique_username` (`username`),
  ADD UNIQUE KEY `unique_provider_user` (`auth_provider`,`provider_id`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_is_active` (`is_active`),
  ADD KEY `idx_is_deleted` (`is_deleted`),
  ADD KEY `idx_created_ymdhis` (`created_ymdhis`),
  ADD KEY `idx_updated_ymdhis` (`updated_ymdhis`);

--
-- Indexes for table `lupo_calibration_impacts`
--
ALTER TABLE `lupo_calibration_impacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_calibration_impact` (`calibration_id`,`impact_type`),
  ADD KEY `idx_impact_measurement` (`impact_measurement`),
  ADD KEY `idx_measurement_time` (`measured_ymdhis`);

--
-- Indexes for table `lupo_channels`
--
ALTER TABLE `lupo_channels`
  ADD PRIMARY KEY (`channel_id`),
  ADD UNIQUE KEY `unq_channel_key_per_node` (`channel_key`,`federation_node_id`),
  ADD KEY `idx_domain` (`federation_node_id`),
  ADD KEY `idx_channel_key` (`channel_key`),
  ADD KEY `idx_status` (`status_flag`),
  ADD KEY `idx_dates` (`end_ymdhis`),
  ADD KEY `idx_awareness_version` (`awareness_version`);

--
-- Indexes for table `lupo_channel_boot_detail`
--
ALTER TABLE `lupo_channel_boot_detail`
  ADD PRIMARY KEY (`detail_id`),
  ADD KEY `idx_boot_channel` (`boot_id`,`channel_id`),
  ADD KEY `idx_load_status_time` (`load_status`,`load_start_time`),
  ADD KEY `fk_boot_detail_channel` (`channel_id`);

--
-- Indexes for table `lupo_channel_boot_log`
--
ALTER TABLE `lupo_channel_boot_log`
  ADD PRIMARY KEY (`boot_id`),
  ADD KEY `idx_actor_session` (`actor_id`,`session_id`),
  ADD KEY `idx_boot_status_time` (`boot_status`,`boot_start_time`);

--
-- Indexes for table `lupo_channel_escalations`
--
ALTER TABLE `lupo_channel_escalations`
  ADD PRIMARY KEY (`escalation_id`),
  ADD KEY `idx_channel_id` (`channel_id`),
  ADD KEY `idx_thread_id` (`thread_id`),
  ADD KEY `idx_actor_id` (`actor_id`),
  ADD KEY `idx_escalated_to_actor_id` (`escalated_to_actor_id`);

--
-- Indexes for table `lupo_channel_escalation_rules`
--
ALTER TABLE `lupo_channel_escalation_rules`
  ADD PRIMARY KEY (`rule_id`),
  ADD KEY `idx_channel_id` (`channel_id`),
  ADD KEY `idx_rule_type` (`rule_type`);

--
-- Indexes for table `lupo_channel_files`
--
ALTER TABLE `lupo_channel_files`
  ADD PRIMARY KEY (`file_id`),
  ADD KEY `idx_channel_id` (`channel_id`),
  ADD KEY `idx_file_type` (`file_type`),
  ADD KEY `idx_file_hash` (`file_hash`),
  ADD KEY `idx_is_deleted` (`is_deleted`),
  ADD KEY `idx_upload_ymdhis` (`upload_ymdhis`);

--
-- Indexes for table `lupo_channel_logs`
--
ALTER TABLE `lupo_channel_logs`
  ADD PRIMARY KEY (`channel_log_id`),
  ADD KEY `idx_channel_id` (`channel_id`),
  ADD KEY `idx_actor_id` (`actor_id`),
  ADD KEY `idx_role_type` (`role_type`),
  ADD KEY `idx_log_type_id` (`log_type_id`),
  ADD KEY `idx_created_ymdhis` (`created_ymdhis`);

--
-- Indexes for table `lupo_channel_log_types`
--
ALTER TABLE `lupo_channel_log_types`
  ADD PRIMARY KEY (`log_type_id`),
  ADD UNIQUE KEY `uniq_type_key` (`type_key`);

--
-- Indexes for table `lupo_channel_roles`
--
ALTER TABLE `lupo_channel_roles`
  ADD PRIMARY KEY (`channel_role_id`),
  ADD KEY `idx_channel_id` (`channel_id`),
  ADD KEY `idx_actor_id` (`actor_id`),
  ADD KEY `idx_role_type` (`role_type`);

--
-- Indexes for table `lupo_channel_state`
--
ALTER TABLE `lupo_channel_state`
  ADD PRIMARY KEY (`channel_state_id`),
  ADD KEY `idx_channel_id` (`channel_id`);

--
-- Indexes for table `lupo_cip_analytics`
--
ALTER TABLE `lupo_cip_analytics`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_event_analytics` (`event_id`),
  ADD KEY `idx_defensiveness_index` (`defensiveness_index`),
  ADD KEY `idx_integration_velocity` (`integration_velocity`),
  ADD KEY `idx_architectural_impact` (`architectural_impact_score`),
  ADD KEY `idx_calculated_time` (`calculated_ymdhis`);

--
-- Indexes for table `lupo_cip_propagation_tracking`
--
ALTER TABLE `lupo_cip_propagation_tracking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_event_level` (`cip_event_id`,`propagation_level`),
  ADD KEY `idx_subsystem` (`affected_subsystem`),
  ADD KEY `idx_completion_status` (`completion_status`),
  ADD KEY `idx_propagation_strength` (`propagation_strength`);

--
-- Indexes for table `lupo_cip_trends`
--
ALTER TABLE `lupo_cip_trends`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_period_trend` (`trend_period`,`period_start_ymdhis`),
  ADD KEY `idx_period_range` (`period_start_ymdhis`,`period_end_ymdhis`),
  ADD KEY `idx_high_impact` (`high_impact_events`);

--
-- Indexes for table `lupo_collections`
--
ALTER TABLE `lupo_collections`
  ADD PRIMARY KEY (`collection_id`),
  ADD UNIQUE KEY `unique_collection_slug_domain` (`federations_node_id`,`slug`),
  ADD KEY `idx_name` (`name`),
  ADD KEY `idx_domain` (`federations_node_id`),
  ADD KEY `idx_group` (`group_id`),
  ADD KEY `idx_created_ymdhis` (`created_ymdhis`),
  ADD KEY `idx_updated_ymdhis` (`updated_ymdhis`),
  ADD KEY `idx_is_deleted` (`is_deleted`),
  ADD KEY `idx_sort_order` (`sort_order`),
  ADD KEY `idx_actor` (`actor_id`);

--
-- Indexes for table `lupo_collection_tabs`
--
ALTER TABLE `lupo_collection_tabs`
  ADD PRIMARY KEY (`collection_tab_id`),
  ADD KEY `idx_collection_id` (`collection_id`),
  ADD KEY `idx_parent_tab_id` (`collection_tab_parent_id`),
  ADD KEY `idx_slug` (`slug`),
  ADD KEY `idx_is_active` (`is_active`);

--
-- Indexes for table `lupo_collection_tab_map`
--
ALTER TABLE `lupo_collection_tab_map`
  ADD PRIMARY KEY (`collection_tab_map_id`),
  ADD UNIQUE KEY `unique_item_in_tab` (`collection_tab_id`,`item_type`,`item_id`),
  ADD KEY `idx_collection_tab` (`collection_tab_id`),
  ADD KEY `idx_domain` (`federations_node_id`),
  ADD KEY `idx_item` (`item_type`,`item_id`),
  ADD KEY `idx_created_ymdhis` (`created_ymdhis`),
  ADD KEY `idx_updated_ymdhis` (`updated_ymdhis`),
  ADD KEY `idx_is_deleted` (`is_deleted`),
  ADD KEY `idx_sort_order` (`sort_order`);

--
-- Indexes for table `lupo_collection_tab_paths`
--
ALTER TABLE `lupo_collection_tab_paths`
  ADD PRIMARY KEY (`collection_tab_path_id`),
  ADD UNIQUE KEY `unique_tab_path` (`collection_id`,`collection_tab_id`,`path`),
  ADD KEY `idx_collection` (`collection_id`),
  ADD KEY `idx_collection_tab` (`collection_tab_id`),
  ADD KEY `idx_path` (`path`);

--
-- Indexes for table `lupo_contents`
--
ALTER TABLE `lupo_contents`
  ADD PRIMARY KEY (`content_id`),
  ADD UNIQUE KEY `unique_content_slug_domain` (`federation_node_id`,`slug`),
  ADD UNIQUE KEY `idx_custom_path` (`custom_path`),
  ADD KEY `idx_content_parent` (`content_parent_id`),
  ADD KEY `idx_content_type` (`content_type`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_visibility` (`visibility`),
  ADD KEY `idx_created_ymdhis` (`created_ymdhis`),
  ADD KEY `idx_updated_ymdhis` (`updated_ymdhis`),
  ADD KEY `idx_is_deleted` (`is_deleted`),
  ADD KEY `idx_is_active` (`is_active`),
  ADD KEY `idx_domain` (`federation_node_id`),
  ADD KEY `idx_group` (`group_id`),
  ADD KEY `idx_user` (`actor_id`);
ALTER TABLE `lupo_contents` ADD FULLTEXT KEY `ft_content` (`title`,`description`,`body`);

--
-- Indexes for table `lupo_content_atom_map`
--
ALTER TABLE `lupo_content_atom_map`
  ADD PRIMARY KEY (`content_atom_map_id`),
  ADD UNIQUE KEY `unique_atom_content` (`content_id`,`atom_id`),
  ADD KEY `idx_content` (`content_id`),
  ADD KEY `idx_atom` (`atom_id`),
  ADD KEY `idx_created_ymdhis` (`created_ymdhis`),
  ADD KEY `idx_updated_ymdhis` (`updated_ymdhis`),
  ADD KEY `idx_is_deleted` (`is_deleted`),
  ADD KEY `idx_purpose` (`purpose`(50));

--
-- Indexes for table `lupo_content_category_map`
--
ALTER TABLE `lupo_content_category_map`
  ADD PRIMARY KEY (`content_category_map_id`),
  ADD UNIQUE KEY `uq_content_category` (`content_id`,`category_id`),
  ADD KEY `idx_content` (`content_id`),
  ADD KEY `idx_category` (`category_id`);

--
-- Indexes for table `lupo_content_engagement_summary`
--
ALTER TABLE `lupo_content_engagement_summary`
  ADD PRIMARY KEY (`content_engagement_summary_id`),
  ADD KEY `idx_updated_ymdhis` (`updated_ymdhis`),
  ADD KEY `idx_created_ymdhis` (`created_ymdhis`),
  ADD KEY `idx_likes_total` (`likes_total`),
  ADD KEY `idx_shares_total` (`shares_total`);

--
-- Indexes for table `lupo_content_events`
--
ALTER TABLE `lupo_content_events`
  ADD PRIMARY KEY (`content_event_id`),
  ADD KEY `idx_content_id` (`content_id`),
  ADD KEY `idx_actor_id` (`actor_id`),
  ADD KEY `idx_session_id` (`session_id`),
  ADD KEY `idx_tab_id` (`tab_id`),
  ADD KEY `idx_world_id` (`world_id`),
  ADD KEY `idx_event_type` (`event_type`),
  ADD KEY `idx_created_ymdhis` (`created_ymdhis`),
  ADD KEY `idx_content_event_type` (`content_id`,`event_type`);

--
-- Indexes for table `lupo_content_hashtag`
--
ALTER TABLE `lupo_content_hashtag`
  ADD PRIMARY KEY (`content_hashtag_id`),
  ADD KEY `idx_content` (`content_hashtag_id`),
  ADD KEY `idx_hashtag` (`hashtag_id`),
  ADD KEY `idx_context` (`context_id`),
  ADD KEY `idx_created_ymdhis` (`created_ymdhis`),
  ADD KEY `idx_is_deleted` (`is_deleted`);

--
-- Indexes for table `lupo_content_inbound_links`
--
ALTER TABLE `lupo_content_inbound_links`
  ADD PRIMARY KEY (`content_inbound_link_id`),
  ADD KEY `idx_target` (`target_content_id`),
  ADD KEY `idx_source` (`source_type`,`source_id`),
  ADD KEY `idx_url` (`source_url`(255)),
  ADD KEY `idx_created_ymdhis` (`created_ymdhis`),
  ADD KEY `idx_updated_ymdhis` (`updated_ymdhis`),
  ADD KEY `idx_is_deleted` (`is_deleted`);

--
-- Indexes for table `lupo_content_likes`
--
ALTER TABLE `lupo_content_likes`
  ADD PRIMARY KEY (`content_like_id`),
  ADD UNIQUE KEY `unique_like_user` (`content_id`,`user_id`),
  ADD UNIQUE KEY `unique_like_visitor` (`content_id`,`visitor_hash`),
  ADD KEY `idx_content` (`content_id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_created_ymdhis` (`created_ymdhis`),
  ADD KEY `idx_updated_ymdhis` (`updated_ymdhis`),
  ADD KEY `idx_is_deleted` (`is_deleted`);

--
-- Indexes for table `lupo_content_media`
--
ALTER TABLE `lupo_content_media`
  ADD PRIMARY KEY (`content_media_id`),
  ADD KEY `idx_content` (`content_id`),
  ADD KEY `idx_media_type` (`media_type`),
  ADD KEY `idx_mime_type` (`mime_type`(20)),
  ADD KEY `idx_media_order` (`media_order`),
  ADD KEY `idx_is_public` (`is_public`),
  ADD KEY `idx_is_deleted` (`is_deleted`),
  ADD KEY `idx_created` (`created_ymdhis`),
  ADD KEY `idx_updated` (`updated_ymdhis`);

--
-- Indexes for table `lupo_content_question_map`
--
ALTER TABLE `lupo_content_question_map`
  ADD PRIMARY KEY (`content_question_map_id`),
  ADD UNIQUE KEY `unique_question_content` (`content_id`,`question_id`),
  ADD KEY `idx_content` (`content_id`),
  ADD KEY `idx_question` (`question_id`),
  ADD KEY `idx_domain` (`domain_id`),
  ADD KEY `idx_created_ymdhis` (`created_ymdhis`),
  ADD KEY `idx_updated_ymdhis` (`updated_ymdhis`),
  ADD KEY `idx_is_deleted` (`is_deleted`);

--
-- Indexes for table `lupo_content_references`
--
ALTER TABLE `lupo_content_references`
  ADD PRIMARY KEY (`content_referenc_id`),
  ADD KEY `idx_content_id` (`content_id`),
  ADD KEY `idx_section_anchor` (`section_anchor_slug`),
  ADD KEY `idx_reference_type` (`reference_type`),
  ADD KEY `idx_reference_slug` (`reference_slug`),
  ADD KEY `idx_reference_object` (`reference_object_id`),
  ADD KEY `idx_is_deleted` (`is_deleted`);

--
-- Indexes for table `lupo_content_revisions`
--
ALTER TABLE `lupo_content_revisions`
  ADD PRIMARY KEY (`content_revision_id`),
  ADD KEY `content_id` (`content_id`),
  ADD KEY `version_number` (`version_number`);

--
-- Indexes for table `lupo_content_shares`
--
ALTER TABLE `lupo_content_shares`
  ADD PRIMARY KEY (`content_share_id`),
  ADD KEY `idx_content` (`content_id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_method` (`share_method`),
  ADD KEY `idx_created_ymdhis` (`created_ymdhis`),
  ADD KEY `idx_updated_ymdhis` (`updated_ymdhis`),
  ADD KEY `idx_is_deleted` (`is_deleted`);

--
-- Indexes for table `lupo_content_tag_relationships`
--
ALTER TABLE `lupo_content_tag_relationships`
  ADD PRIMARY KEY (`relationship_id`),
  ADD KEY `idx_content_id` (`content_id`),
  ADD KEY `idx_tag_id` (`tag_id`),
  ADD KEY `idx_relationship_type` (`relationship_type`),
  ADD KEY `idx_created` (`created_ymdhis`),
  ADD KEY `idx_created_ymdhis` (`created_ymdhis`,`relationship_type`,`content_id`,`tag_id`);

--
-- Indexes for table `lupo_contexts`
--
ALTER TABLE `lupo_contexts`
  ADD PRIMARY KEY (`context_id`),
  ADD UNIQUE KEY `uq_context_code` (`context_code`),
  ADD KEY `idx_parent_context` (`parent_context_id`);

--
-- Indexes for table `lupo_contexts_map`
--
ALTER TABLE `lupo_contexts_map`
  ADD PRIMARY KEY (`contexts_map_id`),
  ADD KEY `idx_context_id` (`context_id`),
  ADD KEY `idx_item_type` (`item_type`),
  ADD KEY `idx_item_slug` (`item_slug`),
  ADD KEY `idx_context_item` (`context_id`,`item_type`,`item_slug`),
  ADD KEY `idx_is_deleted` (`is_deleted`);

--
-- Indexes for table `lupo_contexts_old`
--
ALTER TABLE `lupo_contexts_old`
  ADD PRIMARY KEY (`context_id`),
  ADD KEY `idx_context_type` (`context_type`),
  ADD KEY `idx_context_slug` (`context_slug`),
  ADD KEY `idx_parent_context` (`parent_context_id`);

--
-- Indexes for table `lupo_crafty_syntax_auto_invite`
--
ALTER TABLE `lupo_crafty_syntax_auto_invite`
  ADD PRIMARY KEY (`crafty_syntax_auto_invite_id`),
  ADD KEY `idx_department` (`department_id`),
  ADD KEY `idx_operator` (`operator_user_id`),
  ADD KEY `idx_status` (`is_active`,`is_deleted`),
  ADD KEY `idx_page_url` (`page_url`(191)),
  ADD KEY `idx_created` (`created_ymdhis`);

--
-- Indexes for table `lupo_crafty_syntax_chat_mod_departments`
--
ALTER TABLE `lupo_crafty_syntax_chat_mod_departments`
  ADD PRIMARY KEY (`crafty_syntax_chat_mod_department_id`);

--
-- Indexes for table `lupo_crafty_syntax_chat_questions`
--
ALTER TABLE `lupo_crafty_syntax_chat_questions`
  ADD PRIMARY KEY (`crafty_syntax_chat_question_id`),
  ADD KEY `department` (`department_id`);

--
-- Indexes for table `lupo_crafty_syntax_layer_invites`
--
ALTER TABLE `lupo_crafty_syntax_layer_invites`
  ADD PRIMARY KEY (`crafty_syntax_layer_invite_id`),
  ADD KEY `idx_department` (`department_name`(50)),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_active` (`is_active`),
  ADD KEY `idx_created` (`created_ymdhis`),
  ADD KEY `idx_updated` (`updated_ymdhis`),
  ADD KEY `idx_name` (`layer_name`(50));

--
-- Indexes for table `lupo_crafty_syntax_leave_message`
--
ALTER TABLE `lupo_crafty_syntax_leave_message`
  ADD PRIMARY KEY (`crafty_syntax_leave_message_id`),
  ADD KEY `idx_department` (`department_id`),
  ADD KEY `idx_email` (`email`(100)),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_assigned` (`assigned_to`),
  ADD KEY `idx_created` (`created_ymdhis`),
  ADD KEY `idx_priority` (`priority`);
ALTER TABLE `lupo_crafty_syntax_leave_message` ADD FULLTEXT KEY `idx_message_search` (`email`,`name`,`subject`,`message`);

--
-- Indexes for table `lupo_crafty_user_mapping`
--
ALTER TABLE `lupo_crafty_user_mapping`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_lupo_user_mapping` (`lupo_user_id`),
  ADD UNIQUE KEY `unique_crafty_operator_mapping` (`crafty_operator_id`),
  ADD KEY `idx_lupo_user_id` (`lupo_user_id`),
  ADD KEY `idx_crafty_operator_id` (`crafty_operator_id`),
  ADD KEY `idx_mapping_type` (`mapping_type`);

--
-- Indexes for table `lupo_crm_leads`
--
ALTER TABLE `lupo_crm_leads`
  ADD PRIMARY KEY (`crm_lead_id`);

--
-- Indexes for table `lupo_crm_lead_messages`
--
ALTER TABLE `lupo_crm_lead_messages`
  ADD PRIMARY KEY (`crm_lead_message_id`),
  ADD KEY `lead_id` (`lead_id`),
  ADD KEY `actor_id` (`actor_id`);

--
-- Indexes for table `lupo_departments`
--
ALTER TABLE `lupo_departments`
  ADD PRIMARY KEY (`department_id`),
  ADD KEY `idx_name` (`name`),
  ADD KEY `idx_type` (`department_type`),
  ADD KEY `idx_federation_node` (`federation_node_id`);

--
-- Indexes for table `lupo_department_metadata`
--
ALTER TABLE `lupo_department_metadata`
  ADD PRIMARY KEY (`department_metadata_id`),
  ADD UNIQUE KEY `uq_department_metadata` (`department_id`);

--
-- Indexes for table `lupo_dialog_channels`
--
ALTER TABLE `lupo_dialog_channels`
  ADD PRIMARY KEY (`channel_id`),
  ADD UNIQUE KEY `idx_channel_name` (`channel_name`),
  ADD KEY `idx_file_source` (`file_source`),
  ADD KEY `idx_speaker` (`speaker`),
  ADD KEY `idx_target` (`target`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created_timestamp` (`created_timestamp`),
  ADD KEY `idx_modified_timestamp` (`modified_timestamp`),
  ADD KEY `idx_dialog_channels_composite` (`status`,`created_timestamp`);

--
-- Indexes for table `lupo_dialog_messages`
--
ALTER TABLE `lupo_dialog_messages`
  ADD PRIMARY KEY (`dialog_message_id`),
  ADD KEY `idx_channel` (`channel_id`),
  ADD KEY `idx_created` (`created_ymdhis`),
  ADD KEY `idx_updated` (`updated_ymdhis`),
  ADD KEY `idx_deleted` (`is_deleted`),
  ADD KEY `idx_message_type` (`message_type`),
  ADD KEY `idx_dialog_thread_id` (`dialog_thread_id`),
  ADD KEY `idx_to_actor_id` (`to_actor_id`);

--
-- Indexes for table `lupo_dialog_threads`
--
ALTER TABLE `lupo_dialog_threads`
  ADD PRIMARY KEY (`dialog_thread_id`),
  ADD KEY `idx_node` (`federation_node_id`),
  ADD KEY `idx_channel` (`channel_id`),
  ADD KEY `idx_project` (`project_slug`),
  ADD KEY `idx_task` (`task_name`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created` (`created_ymdhis`),
  ADD KEY `idx_updated` (`updated_ymdhis`),
  ADD KEY `idx_deleted` (`is_deleted`),
  ADD KEY `idx_created_by_actor` (`created_by_actor_id`);

--
-- Indexes for table `lupo_doctrine_blocks`
--
ALTER TABLE `lupo_doctrine_blocks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_block_key` (`block_key`),
  ADD KEY `idx_block_key` (`block_key`),
  ADD KEY `idx_created_ymdhis` (`created_ymdhis`),
  ADD KEY `idx_updated_ymdhis` (`updated_ymdhis`);

--
-- Indexes for table `lupo_doctrine_evolution_audit`
--
ALTER TABLE `lupo_doctrine_evolution_audit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_refinement_step` (`refinement_id`,`evolution_step`),
  ADD KEY `idx_step_status` (`step_status`),
  ADD KEY `idx_completion_time` (`completed_ymdhis`);

--
-- Indexes for table `lupo_doctrine_refinements`
--
ALTER TABLE `lupo_doctrine_refinements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cip_event` (`cip_event_id`),
  ADD KEY `idx_doctrine_file` (`doctrine_file_path`(255)),
  ADD KEY `idx_approval_status` (`approval_status`),
  ADD KEY `idx_applied_time` (`applied_ymdhis`);

--
-- Indexes for table `lupo_documents`
--
ALTER TABLE `lupo_documents`
  ADD PRIMARY KEY (`document_id`);

--
-- Indexes for table `lupo_document_chunks`
--
ALTER TABLE `lupo_document_chunks`
  ADD PRIMARY KEY (`document_chunk_id`),
  ADD UNIQUE KEY `doc_chunk_unique` (`document_id`,`chunk_index`),
  ADD KEY `document_id` (`document_id`);

--
-- Indexes for table `lupo_document_embeddings`
--
ALTER TABLE `lupo_document_embeddings`
  ADD PRIMARY KEY (`document_embedding_id`),
  ADD KEY `chunk_id` (`chunk_id`),
  ADD KEY `embedding_model` (`embedding_model`);

--
-- Indexes for table `lupo_edges`
--
ALTER TABLE `lupo_edges`
  ADD PRIMARY KEY (`edge_id`),
  ADD KEY `idx_left` (`left_object_type`,`left_object_id`),
  ADD KEY `idx_right` (`right_object_type`,`right_object_id`),
  ADD KEY `idx_edge_type` (`edge_type`),
  ADD KEY `idx_actor` (`actor_id`),
  ADD KEY `idx_is_deleted` (`is_deleted`),
  ADD KEY `idx_semantic_weight` (`semantic_weight`),
  ADD KEY `idx_relationship_type` (`relationship_type`),
  ADD KEY `idx_channel_semantic` (`channel_id`,`relationship_type`,`semantic_weight`);

--
-- Indexes for table `lupo_edge_types`
--
ALTER TABLE `lupo_edge_types`
  ADD PRIMARY KEY (`edge_type_id`),
  ADD KEY `idx_edge_type` (`edge_type`);

--
-- Indexes for table `lupo_emotional_constellations`
--
ALTER TABLE `lupo_emotional_constellations`
  ADD PRIMARY KEY (`constellation_id`);

--
-- Indexes for table `lupo_emotional_frameworks`
--
ALTER TABLE `lupo_emotional_frameworks`
  ADD PRIMARY KEY (`framework_name`);

--
-- Indexes for table `lupo_emotional_geometry_calibrations`
--
ALTER TABLE `lupo_emotional_geometry_calibrations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_analytics_ref` (`cip_analytics_id`),
  ADD KEY `idx_target` (`calibration_target`,`target_identifier`(100)),
  ADD KEY `idx_validation_status` (`validation_status`),
  ADD KEY `idx_confidence` (`confidence_score`);

--
-- Indexes for table `lupo_emotional_stars`
--
ALTER TABLE `lupo_emotional_stars`
  ADD PRIMARY KEY (`star_id`);

--
-- Indexes for table `lupo_emotional_translations`
--
ALTER TABLE `lupo_emotional_translations`
  ADD PRIMARY KEY (`translation_id`);

--
-- Indexes for table `lupo_entity_edges`
--
ALTER TABLE `lupo_entity_edges`
  ADD PRIMARY KEY (`entity_edge_id`),
  ADD KEY `idx_source` (`source_entity_type`,`source_entity_id`),
  ADD KEY `idx_target` (`target_entity_type`,`target_entity_id`),
  ADD KEY `idx_edge_type` (`edge_type`),
  ADD KEY `idx_domain` (`domain_id`),
  ADD KEY `idx_created` (`created_ymdhis`),
  ADD KEY `idx_is_deleted` (`is_deleted`);

--
-- Indexes for table `lupo_entity_properties`
--
ALTER TABLE `lupo_entity_properties`
  ADD PRIMARY KEY (`entity_property_id`),
  ADD UNIQUE KEY `unique_entity_domain_property` (`entity_type`,`entity_id`,`domain_id`,`property_key`),
  ADD KEY `idx_entity` (`entity_type`,`entity_id`),
  ADD KEY `idx_domain` (`domain_id`),
  ADD KEY `idx_property_key` (`property_key`),
  ADD KEY `idx_created` (`created_ymdhis`),
  ADD KEY `idx_updated` (`updated_ymdhis`),
  ADD KEY `idx_is_deleted` (`is_deleted`);

--
-- Indexes for table `lupo_event_log`
--
ALTER TABLE `lupo_event_log`
  ADD PRIMARY KEY (`event_id`),
  ADD KEY `idx_event_type` (`event_type`),
  ADD KEY `idx_created_ymdhis` (`created_ymdhis`);

--
-- Indexes for table `lupo_event_metadata`
--
ALTER TABLE `lupo_event_metadata`
  ADD PRIMARY KEY (`metadata_id`),
  ADD KEY `idx_event_id` (`event_id`),
  ADD KEY `idx_metadata_key` (`metadata_key`),
  ADD KEY `idx_created_ymdhis` (`created_ymdhis`);

--
-- Indexes for table `lupo_federation_categories`
--
ALTER TABLE `lupo_federation_categories`
  ADD PRIMARY KEY (`federation_category_id`),
  ADD KEY `idx_category_slug` (`category_slug`),
  ADD KEY `idx_is_deleted` (`is_deleted`);

--
-- Indexes for table `lupo_federation_category_map`
--
ALTER TABLE `lupo_federation_category_map`
  ADD PRIMARY KEY (`federation_category_map_id`),
  ADD KEY `idx_node` (`federation_node_id`),
  ADD KEY `idx_category` (`federation_category_id`),
  ADD KEY `idx_is_deleted` (`is_deleted`);

--
-- Indexes for table `lupo_federation_discovery`
--
ALTER TABLE `lupo_federation_discovery`
  ADD PRIMARY KEY (`federation_discovery_id`),
  ADD KEY `idx_domain` (`domain`);

--
-- Indexes for table `lupo_federation_nodes`
--
ALTER TABLE `lupo_federation_nodes`
  ADD PRIMARY KEY (`federation_node_id`),
  ADD KEY `idx_node_base_url` (`node_base_url`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_trust_level` (`trust_level`),
  ADD KEY `idx_is_deleted` (`is_deleted`);

--
-- Indexes for table `lupo_governance_overrides`
--
ALTER TABLE `lupo_governance_overrides`
  ADD PRIMARY KEY (`governance_overrid_id`),
  ADD KEY `idx_agent` (`agent_id`),
  ADD KEY `idx_applied_by` (`applied_by_agent`),
  ADD KEY `idx_type` (`override_type`),
  ADD KEY `idx_target` (`target_key`),
  ADD KEY `idx_created` (`created_ymdhis`);

--
-- Indexes for table `lupo_gov_events`
--
ALTER TABLE `lupo_gov_events`
  ADD PRIMARY KEY (`gov_event_id`),
  ADD UNIQUE KEY `unique_canonical_path` (`canonical_path`),
  ADD KEY `idx_utc_group` (`utc_group_id`),
  ADD KEY `idx_semantic_version` (`semantic_utc_version`),
  ADD KEY `idx_event_type` (`event_type`),
  ADD KEY `idx_created_ymdhis` (`created_ymdhis`),
  ADD KEY `idx_is_active` (`is_active`),
  ADD KEY `idx_is_deleted` (`is_deleted`);

--
-- Indexes for table `lupo_gov_event_actor_edges`
--
ALTER TABLE `lupo_gov_event_actor_edges`
  ADD PRIMARY KEY (`edge_id`),
  ADD UNIQUE KEY `unique_gov_event_actor_edge` (`gov_event_id`,`actor_id`,`edge_type`),
  ADD KEY `idx_gov_event` (`gov_event_id`),
  ADD KEY `idx_actor` (`actor_id`),
  ADD KEY `idx_edge_type` (`edge_type`),
  ADD KEY `idx_created_ymdhis` (`created_ymdhis`),
  ADD KEY `idx_is_deleted` (`is_deleted`);

--
-- Indexes for table `lupo_gov_event_conflicts`
--
ALTER TABLE `lupo_gov_event_conflicts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_gov_event_id` (`gov_event_id`),
  ADD KEY `idx_conflicts_with_event_id` (`conflicts_with_event_id`);

--
-- Indexes for table `lupo_gov_event_dependencies`
--
ALTER TABLE `lupo_gov_event_dependencies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_gov_event_id` (`gov_event_id`),
  ADD KEY `idx_depends_on_event_id` (`depends_on_event_id`);

--
-- Indexes for table `lupo_gov_event_references`
--
ALTER TABLE `lupo_gov_event_references`
  ADD PRIMARY KEY (`reference_id`),
  ADD KEY `idx_gov_event` (`gov_event_id`),
  ADD KEY `idx_reference_type` (`reference_type`),
  ADD KEY `idx_order_sequence` (`order_sequence`),
  ADD KEY `idx_created_ymdhis` (`created_ymdhis`),
  ADD KEY `idx_is_deleted` (`is_deleted`);

--
-- Indexes for table `lupo_gov_timeline_nodes`
--
ALTER TABLE `lupo_gov_timeline_nodes`
  ADD PRIMARY KEY (`timeline_node_id`),
  ADD KEY `idx_gov_event` (`gov_event_id`),
  ADD KEY `idx_node_type` (`node_type`),
  ADD KEY `idx_node_timestamp` (`node_timestamp`),
  ADD KEY `idx_parent_node` (`parent_node_id`),
  ADD KEY `idx_order_sequence` (`order_sequence`),
  ADD KEY `idx_created_ymdhis` (`created_ymdhis`),
  ADD KEY `idx_is_deleted` (`is_deleted`);

--
-- Indexes for table `lupo_gov_valuations`
--
ALTER TABLE `lupo_gov_valuations`
  ADD PRIMARY KEY (`valuation_id`),
  ADD KEY `idx_gov_event` (`gov_event_id`),
  ADD KEY `idx_valuation_type` (`valuation_type`),
  ADD KEY `idx_valuation_metric` (`valuation_metric`),
  ADD KEY `idx_created_ymdhis` (`created_ymdhis`),
  ADD KEY `idx_is_deleted` (`is_deleted`);

--
-- Indexes for table `lupo_groups`
--
ALTER TABLE `lupo_groups`
  ADD PRIMARY KEY (`group_id`),
  ADD UNIQUE KEY `unique_group_domain` (`name`),
  ADD KEY `idx_is_active` (`is_active`),
  ADD KEY `idx_is_deleted` (`is_deleted`),
  ADD KEY `idx_created_ymdhis` (`created_ymdhis`),
  ADD KEY `idx_updated_ymdhis` (`updated_ymdhis`);

--
-- Indexes for table `lupo_hashtags`
--
ALTER TABLE `lupo_hashtags`
  ADD PRIMARY KEY (`hashtag_id`),
  ADD KEY `idx_hashtag_slug` (`hashtag_slug`),
  ADD KEY `idx_is_deleted` (`is_deleted`);

--
-- Indexes for table `lupo_help_topics`
--
ALTER TABLE `lupo_help_topics`
  ADD PRIMARY KEY (`help_topic_id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_slug` (`slug`),
  ADD KEY `idx_category` (`category`),
  ADD KEY `idx_parent` (`parent_slug`),
  ADD KEY `idx_created` (`created_ymdhis`),
  ADD KEY `idx_author` (`author_actor_id`);

--
-- Indexes for table `lupo_help_tree`
--
ALTER TABLE `lupo_help_tree`
  ADD PRIMARY KEY (`help_tree_id`),
  ADD KEY `idx_parent` (`parent_id`),
  ADD KEY `idx_department` (`department_id`),
  ADD KEY `idx_content` (`content_id`),
  ADD KEY `idx_sort` (`parent_id`,`sort_order`),
  ADD KEY `idx_action` (`action_type`,`action_target`(191)),
  ADD KEY `idx_created` (`created_ymdhis`),
  ADD KEY `idx_updated` (`updated_ymdhis`);

--
-- Indexes for table `lupo_hotfix_registry`
--
ALTER TABLE `lupo_hotfix_registry`
  ADD PRIMARY KEY (`hotfix_id`);

--
-- Indexes for table `lupo_human_history_meta`
--
ALTER TABLE `lupo_human_history_meta`
  ADD PRIMARY KEY (`meta_id`);

--
-- Indexes for table `lupo_integration_test_results`
--
ALTER TABLE `lupo_integration_test_results`
  ADD PRIMARY KEY (`test_result_id`);

--
-- Indexes for table `lupo_interface_translations`
--
ALTER TABLE `lupo_interface_translations`
  ADD PRIMARY KEY (`interface_translation_id`),
  ADD UNIQUE KEY `unq_language_key` (`language_code`,`translation_key`),
  ADD KEY `idx_created` (`created_ymdhis`),
  ADD KEY `idx_updated` (`updated_ymdhis`),
  ADD KEY `idx_deleted` (`is_deleted`),
  ADD KEY `idx_approved` (`is_approved`);
ALTER TABLE `lupo_interface_translations` ADD FULLTEXT KEY `ft_translation_text` (`translation_text`);

--
-- Indexes for table `lupo_interpretation_log`
--
ALTER TABLE `lupo_interpretation_log`
  ADD PRIMARY KEY (`interpretation_log_id`),
  ADD KEY `idx_agent` (`agent_id`),
  ADD KEY `idx_entity` (`entity_type`,`entity_id`),
  ADD KEY `idx_confidence` (`confidence_score`),
  ADD KEY `idx_created` (`created_ymdhis`),
  ADD KEY `idx_updated` (`updated_ymdhis`),
  ADD KEY `idx_deleted` (`is_deleted`);

--
-- Indexes for table `lupo_kapu_events`
--
ALTER TABLE `lupo_kapu_events`
  ADD PRIMARY KEY (`kapu_id`);

--
-- Indexes for table `lupo_kapu_restoration_paths`
--
ALTER TABLE `lupo_kapu_restoration_paths`
  ADD PRIMARY KEY (`path_id`);

--
-- Indexes for table `lupo_labs_declarations`
--
ALTER TABLE `lupo_labs_declarations`
  ADD PRIMARY KEY (`labs_declaration_id`),
  ADD KEY `idx_actor_id` (`actor_id`),
  ADD KEY `idx_certificate_id` (`certificate_id`),
  ADD KEY `idx_validation_status` (`validation_status`),
  ADD KEY `idx_next_revalidation` (`next_revalidation_ymdhis`),
  ADD KEY `idx_actor_status` (`actor_id`,`validation_status`,`is_deleted`),
  ADD KEY `idx_revalidation_due` (`next_revalidation_ymdhis`,`validation_status`,`is_deleted`);

--
-- Indexes for table `lupo_labs_violations`
--
ALTER TABLE `lupo_labs_violations`
  ADD PRIMARY KEY (`labs_violation_id`),
  ADD KEY `idx_actor` (`actor_id`),
  ADD KEY `idx_certificate` (`certificate_id`),
  ADD KEY `idx_violation_code` (`violation_code`),
  ADD KEY `idx_created` (`created_ymdhis`),
  ADD KEY `idx_deleted` (`is_deleted`);

--
-- Indexes for table `lupo_legacy_content_mapping`
--
ALTER TABLE `lupo_legacy_content_mapping`
  ADD PRIMARY KEY (`mapping_id`),
  ADD UNIQUE KEY `uk_legacy_url` (`legacy_url`),
  ADD KEY `idx_semantic_url` (`semantic_url`),
  ADD KEY `idx_content_type` (`content_type`),
  ADD KEY `idx_content_id` (`content_id`),
  ADD KEY `idx_is_active` (`is_active`),
  ADD KEY `idx_created` (`created_ymdhis`),
  ADD KEY `idx_created_ymdhis` (`created_ymdhis`,`is_active`);

--
-- Indexes for table `lupo_memory_debug_log`
--
ALTER TABLE `lupo_memory_debug_log`
  ADD PRIMARY KEY (`memory_debug_log_id`),
  ADD KEY `idx_type_created` (`event_type`,`created_ymdhis`);

--
-- Indexes for table `lupo_memory_events`
--
ALTER TABLE `lupo_memory_events`
  ADD PRIMARY KEY (`memory_event_id`),
  ADD KEY `idx_actor_created` (`actor_id`,`created_ymdhis`),
  ADD KEY `idx_actor_type` (`actor_id`,`event_type`);

--
-- Indexes for table `lupo_memory_rollups`
--
ALTER TABLE `lupo_memory_rollups`
  ADD PRIMARY KEY (`memory_rollup_id`),
  ADD KEY `idx_actor_created` (`actor_id`,`created_ymdhis`);

--
-- Indexes for table `lupo_meta_log_events`
--
ALTER TABLE `lupo_meta_log_events`
  ADD PRIMARY KEY (`event_id`),
  ADD KEY `idx_created_ymdhis` (`created_ymdhis`),
  ADD KEY `idx_depth` (`depth`),
  ADD KEY `idx_actor_id` (`actor_id`),
  ADD KEY `idx_is_deleted` (`is_deleted`);

--
-- Indexes for table `lupo_metrics_archive_legacy`
--
ALTER TABLE `lupo_metrics_archive_legacy`
  ADD PRIMARY KEY (`metric_id`);

--
-- Indexes for table `lupo_modules`
--
ALTER TABLE `lupo_modules`
  ADD PRIMARY KEY (`module_id`),
  ADD UNIQUE KEY `uq_module_key` (`module_key`),
  ADD KEY `idx_namespace` (`namespace`),
  ADD KEY `idx_status` (`is_active`,`is_deleted`),
  ADD KEY `idx_system` (`is_system`),
  ADD KEY `idx_installed` (`installed_ymdhis`);

--
-- Indexes for table `lupo_modules_departments`
--
ALTER TABLE `lupo_modules_departments`
  ADD PRIMARY KEY (`module_department_id`),
  ADD UNIQUE KEY `uniq_mod_dept` (`module_id`,`department_id`);

--
-- Indexes for table `lupo_mood_assignments`
--
ALTER TABLE `lupo_mood_assignments`
  ADD PRIMARY KEY (`mood_assignment_id`),
  ADD KEY `idx_assignment_target` (`table_name`,`row_id`),
  ADD KEY `idx_assignment_mood` (`mood_id`);

--
-- Indexes for table `lupo_mood_registry`
--
ALTER TABLE `lupo_mood_registry`
  ADD PRIMARY KEY (`mood_id`),
  ADD KEY `idx_mood_type` (`mood_type`),
  ADD KEY `idx_mood_rgb` (`mood_rgb`);

--
-- Indexes for table `lupo_multi_agent_critique_sync`
--
ALTER TABLE `lupo_multi_agent_critique_sync`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_event_agent` (`cip_event_id`,`agent_id`),
  ADD KEY `idx_sync_status` (`sync_status`),
  ADD KEY `idx_sync_role` (`sync_role`),
  ADD KEY `idx_consensus_contribution` (`consensus_contribution`);

--
-- Indexes for table `lupo_narrative_fragments`
--
ALTER TABLE `lupo_narrative_fragments`
  ADD PRIMARY KEY (`narrative_fragment_id`),
  ADD KEY `idx_agent` (`agent_id`),
  ADD KEY `idx_type` (`fragment_type`),
  ADD KEY `idx_created` (`created_ymdhis`);

--
-- Indexes for table `lupo_notifications`
--
ALTER TABLE `lupo_notifications`
  ADD PRIMARY KEY (`notification_id`);

--
-- Indexes for table `lupo_pack_role_registry`
--
ALTER TABLE `lupo_pack_role_registry`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_agent_role` (`agent_id`),
  ADD KEY `idx_agent_id` (`agent_id`),
  ADD KEY `idx_role` (`role`);

--
-- Indexes for table `lupo_permissions`
--
ALTER TABLE `lupo_permissions`
  ADD PRIMARY KEY (`permission_id`),
  ADD UNIQUE KEY `uniq_target_user` (`target_type`,`target_id`,`user_id`),
  ADD UNIQUE KEY `uniq_target_group` (`target_type`,`target_id`,`group_id`),
  ADD KEY `idx_target` (`target_type`,`target_id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_group` (`group_id`),
  ADD KEY `idx_deleted` (`is_deleted`,`deleted_ymdhis`),
  ADD KEY `idx_permission` (`permission`),
  ADD KEY `idx_created_ymdhis` (`created_ymdhis`);

--
-- Indexes for table `lupo_persona_dialogue_patterns`
--
ALTER TABLE `lupo_persona_dialogue_patterns`
  ADD PRIMARY KEY (`pattern_id`),
  ADD KEY `idx_persona_id` (`persona_id`),
  ADD KEY `idx_pattern_type` (`pattern_type`),
  ADD KEY `idx_pattern_name` (`pattern_name`);

--
-- Indexes for table `lupo_persona_profiles`
--
ALTER TABLE `lupo_persona_profiles`
  ADD PRIMARY KEY (`persona_id`),
  ADD KEY `idx_persona_type` (`persona_type`),
  ADD KEY `idx_persona_name` (`persona_name`),
  ADD KEY `idx_is_active` (`is_active`);

--
-- Indexes for table `lupo_reference_cited_by`
--
ALTER TABLE `lupo_reference_cited_by`
  ADD PRIMARY KEY (`reference_cited_by_id`),
  ADD KEY `idx_reference_object` (`reference_object_id`),
  ADD KEY `idx_content_id` (`content_id`),
  ADD KEY `idx_section_anchor` (`section_anchor_slug`),
  ADD KEY `idx_reference_type` (`reference_type`),
  ADD KEY `idx_is_deleted` (`is_deleted`);

--
-- Indexes for table `lupo_reference_objects`
--
ALTER TABLE `lupo_reference_objects`
  ADD PRIMARY KEY (`reference_object_id`),
  ADD KEY `idx_object_type` (`object_type`),
  ADD KEY `idx_object_slug` (`object_slug`),
  ADD KEY `idx_type_slug` (`object_type`,`object_slug`),
  ADD KEY `idx_is_deleted` (`is_deleted`);

--
-- Indexes for table `lupo_relationships`
--
ALTER TABLE `lupo_relationships`
  ADD PRIMARY KEY (`relationship_id`),
  ADD KEY `idx_relationship_lookup` (`source_type`,`source_id`,`edge_type`,`is_deleted`);

--
-- Indexes for table `lupo_search_index`
--
ALTER TABLE `lupo_search_index`
  ADD PRIMARY KEY (`search_index_id`),
  ADD UNIQUE KEY `unique_entity` (`domain_id`,`entity_type`,`entity_id`),
  ADD KEY `idx_domain_type` (`domain_id`,`entity_type`),
  ADD KEY `idx_entity_reference` (`entity_type`,`entity_id`),
  ADD KEY `idx_updated` (`updated_ymdhis`),
  ADD KEY `idx_is_deleted` (`is_deleted`),
  ADD KEY `idx_relevance` (`relevance_score`);
ALTER TABLE `lupo_search_index` ADD FULLTEXT KEY `ft_search_content` (`title_text`,`body_text`,`keywords_text`);

--
-- Indexes for table `lupo_search_rebuild_log`
--
ALTER TABLE `lupo_search_rebuild_log`
  ADD PRIMARY KEY (`search_rebuild_log_id`),
  ADD UNIQUE KEY `unique_entity_operation` (`entity_type`,`entity_id`,`action`),
  ADD KEY `idx_status_retry` (`status`,`next_attempt_ymdhis`),
  ADD KEY `idx_created` (`created_ymdhis`),
  ADD KEY `idx_entity` (`entity_type`,`entity_id`);

--
-- Indexes for table `lupo_semantic_categories`
--
ALTER TABLE `lupo_semantic_categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `uk_category_slug` (`category_slug`),
  ADD KEY `idx_parent_category` (`parent_category_id`),
  ADD KEY `idx_sort_order` (`sort_order`),
  ADD KEY `idx_is_active` (`is_active`),
  ADD KEY `idx_created` (`created_ymdhis`),
  ADD KEY `idx_created_ymdhis` (`created_ymdhis`,`is_active`);

--
-- Indexes for table `lupo_semantic_content_views`
--
ALTER TABLE `lupo_semantic_content_views`
  ADD PRIMARY KEY (`semantic_view_id`),
  ADD UNIQUE KEY `uk_view_name` (`view_name`),
  ADD KEY `idx_view_type` (`view_type`),
  ADD KEY `idx_is_active` (`is_active`),
  ADD KEY `idx_is_default` (`is_default`),
  ADD KEY `idx_created_ymdhis` (`created_ymdhis`,`is_default`,`is_active`);

--
-- Indexes for table `lupo_semantic_navigation_overview`
--
ALTER TABLE `lupo_semantic_navigation_overview`
  ADD PRIMARY KEY (`navigation_id`),
  ADD KEY `idx_created` (`created_ymdhis`),
  ADD KEY `idx_is_deleted` (`is_deleted`),
  ADD KEY `idx_created_ymdhis` (`created_ymdhis`,`is_deleted`);

--
-- Indexes for table `lupo_semantic_overlays`
--
ALTER TABLE `lupo_semantic_overlays`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_slug` (`slug`),
  ADD KEY `idx_context` (`context`);

--
-- Indexes for table `lupo_semantic_paths`
--
ALTER TABLE `lupo_semantic_paths`
  ADD PRIMARY KEY (`id`),
  ADD KEY `source_page_id` (`source_page_id`),
  ADD KEY `target_page_id` (`target_page_id`),
  ADD KEY `layer` (`layer`),
  ADD KEY `timeframe` (`timeframe`);

--
-- Indexes for table `lupo_semantic_relationships`
--
ALTER TABLE `lupo_semantic_relationships`
  ADD PRIMARY KEY (`relationship_id`),
  ADD KEY `idx_source_content` (`source_content_id`),
  ADD KEY `idx_target_content` (`target_content_id`),
  ADD KEY `idx_relationship_type` (`relationship_type`),
  ADD KEY `idx_created` (`created_ymdhis`),
  ADD KEY `idx_created_ymdhis` (`created_ymdhis`,`relationship_type`,`source_content_id`,`target_content_id`);

--
-- Indexes for table `lupo_semantic_search_index`
--
ALTER TABLE `lupo_semantic_search_index`
  ADD PRIMARY KEY (`search_index_id`),
  ADD UNIQUE KEY `uk_index_name` (`index_name`),
  ADD KEY `idx_index_type` (`index_type`),
  ADD KEY `idx_is_active` (`is_active`),
  ADD KEY `idx_created` (`created_ymdhis`),
  ADD KEY `idx_created_ymdhis` (`created_ymdhis`,`is_active`);

--
-- Indexes for table `lupo_semantic_tags`
--
ALTER TABLE `lupo_semantic_tags`
  ADD PRIMARY KEY (`tag_id`),
  ADD UNIQUE KEY `uk_tag_slug` (`tag_slug`),
  ADD KEY `idx_is_active` (`is_active`),
  ADD KEY `idx_created` (`created_ymdhis`),
  ADD KEY `idx_created_ymdhis` (`created_ymdhis`,`is_active`);

--
-- Indexes for table `lupo_semantic_translations`
--
ALTER TABLE `lupo_semantic_translations`
  ADD PRIMARY KEY (`semantic_translation_id`),
  ADD UNIQUE KEY `unq_translation` (`entity_type`,`entity_id`,`language_code`),
  ADD KEY `idx_entity_lookup` (`entity_type`,`entity_id`,`language_code`),
  ADD KEY `idx_language_entity` (`language_code`,`entity_type`,`entity_id`),
  ADD KEY `idx_created` (`created_ymdhis`),
  ADD KEY `idx_updated` (`updated_ymdhis`),
  ADD KEY `idx_deleted` (`is_deleted`);
ALTER TABLE `lupo_semantic_translations` ADD FULLTEXT KEY `ft_translated_text` (`translated_text`);

--
-- Indexes for table `lupo_sessions`
--
ALTER TABLE `lupo_sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `idx_domain` (`federation_node_id`),
  ADD KEY `idx_actor` (`actor_id`),
  ADD KEY `idx_last_seen` (`last_seen_ymdhis`),
  ADD KEY `idx_expires` (`expires_ymdhis`),
  ADD KEY `idx_device` (`device_id`),
  ADD KEY `idx_security` (`security_level`),
  ADD KEY `idx_status` (`is_active`,`is_expired`,`is_revoked`),
  ADD KEY `idx_cleanup` (`is_deleted`,`last_seen_ymdhis`),
  ADD KEY `idx_created` (`created_ymdhis`);

--
-- Indexes for table `lupo_session_events`
--
ALTER TABLE `lupo_session_events`
  ADD PRIMARY KEY (`session_event_id`),
  ADD KEY `idx_session_id` (`session_id`),
  ADD KEY `idx_actor_id` (`actor_id`),
  ADD KEY `idx_tab_id` (`tab_id`),
  ADD KEY `idx_world_id` (`world_id`),
  ADD KEY `idx_event_type` (`event_type`),
  ADD KEY `idx_created_ymdhis` (`created_ymdhis`),
  ADD KEY `idx_session_event_type` (`session_id`,`event_type`);

--
-- Indexes for table `lupo_system_config`
--
ALTER TABLE `lupo_system_config`
  ADD PRIMARY KEY (`system_config_id`),
  ADD UNIQUE KEY `config_key` (`config_key`);

--
-- Indexes for table `lupo_system_events`
--
ALTER TABLE `lupo_system_events`
  ADD PRIMARY KEY (`system_event_id`),
  ADD KEY `event_type` (`event_type`),
  ADD KEY `actor_id` (`actor_id`);

--
-- Indexes for table `lupo_system_health_snapshots`
--
ALTER TABLE `lupo_system_health_snapshots`
  ADD PRIMARY KEY (`health_id`),
  ADD KEY `idx_created_ymdhis` (`created_ymdhis`),
  ADD KEY `idx_table_count` (`table_count`),
  ADD KEY `idx_is_deleted` (`is_deleted`);

--
-- Indexes for table `lupo_system_logs`
--
ALTER TABLE `lupo_system_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `idx_event_type` (`event_type`),
  ADD KEY `idx_severity` (`severity`),
  ADD KEY `idx_actor_slug` (`actor_slug`),
  ADD KEY `idx_created_ymdhis` (`created_ymdhis`),
  ADD KEY `idx_is_deleted` (`is_deleted`);

--
-- Indexes for table `lupo_tab_events`
--
ALTER TABLE `lupo_tab_events`
  ADD PRIMARY KEY (`tab_event_id`),
  ADD KEY `idx_tab_id` (`tab_id`),
  ADD KEY `idx_session_id` (`session_id`),
  ADD KEY `idx_actor_id` (`actor_id`),
  ADD KEY `idx_world_id` (`world_id`),
  ADD KEY `idx_event_type` (`event_type`),
  ADD KEY `idx_created_ymdhis` (`created_ymdhis`),
  ADD KEY `idx_tab_event_type` (`tab_id`,`event_type`);

--
-- Indexes for table `lupo_temporal_coherence_snapshots`
--
ALTER TABLE `lupo_temporal_coherence_snapshots`
  ADD PRIMARY KEY (`snapshot_id`),
  ADD KEY `idx_created_ymdhis` (`created_ymdhis`),
  ADD KEY `idx_utc_anchor` (`utc_anchor`),
  ADD KEY `idx_is_deleted` (`is_deleted`);

--
-- Indexes for table `lupo_test_performance_metrics`
--
ALTER TABLE `lupo_test_performance_metrics`
  ADD PRIMARY KEY (`test_id`);

--
-- Indexes for table `lupo_tldnr`
--
ALTER TABLE `lupo_tldnr`
  ADD PRIMARY KEY (`tldnr_id`),
  ADD UNIQUE KEY `uniq_slug` (`slug`),
  ADD KEY `idx_topic_type` (`topic_type`),
  ADD KEY `idx_topic_reference` (`topic_reference`),
  ADD KEY `idx_category` (`category`),
  ADD KEY `idx_system_version` (`system_version`),
  ADD KEY `idx_is_deleted` (`is_deleted`),
  ADD KEY `idx_created` (`created_ymdhis`);

--
-- Indexes for table `lupo_truth_answers`
--
ALTER TABLE `lupo_truth_answers`
  ADD PRIMARY KEY (`truth_answer_id`),
  ADD KEY `idx_question` (`truth_question_id`);

--
-- Indexes for table `lupo_truth_evidence`
--
ALTER TABLE `lupo_truth_evidence`
  ADD PRIMARY KEY (`truth_evidence_id`),
  ADD KEY `truth_answer_id` (`truth_answer_id`),
  ADD KEY `actor_id` (`actor_id`);

--
-- Indexes for table `lupo_truth_questions`
--
ALTER TABLE `lupo_truth_questions`
  ADD PRIMARY KEY (`truth_question_id`),
  ADD KEY `idx_parent` (`truth_question_parent_id`),
  ADD KEY `idx_slug` (`slug`);

--
-- Indexes for table `lupo_truth_questions_map`
--
ALTER TABLE `lupo_truth_questions_map`
  ADD PRIMARY KEY (`truth_questions_map_id`),
  ADD KEY `truth_question_id` (`truth_question_id`),
  ADD KEY `object_type` (`object_type`),
  ADD KEY `object_id` (`object_id`),
  ADD KEY `actor_id` (`actor_id`);

--
-- Indexes for table `lupo_truth_relations`
--
ALTER TABLE `lupo_truth_relations`
  ADD PRIMARY KEY (`truth_relation_id`),
  ADD KEY `left_object_type` (`left_object_type`),
  ADD KEY `right_object_type` (`right_object_type`),
  ADD KEY `relation_type` (`relation_type`);

--
-- Indexes for table `lupo_truth_sources`
--
ALTER TABLE `lupo_truth_sources`
  ADD PRIMARY KEY (`truth_sourc_id`),
  ADD KEY `truth_evidence_id` (`truth_evidence_id`),
  ADD KEY `actor_id` (`actor_id`);

--
-- Indexes for table `lupo_truth_topics`
--
ALTER TABLE `lupo_truth_topics`
  ADD PRIMARY KEY (`truth_topic_id`),
  ADD KEY `slug` (`slug`),
  ADD KEY `actor_id` (`actor_id`),
  ADD KEY `topic_name` (`topic_name`);

--
-- Indexes for table `lupo_unified_analytics_paths`
--
ALTER TABLE `lupo_unified_analytics_paths`
  ADD PRIMARY KEY (`unified_analytics_path_id`);

--
-- Indexes for table `lupo_unified_dialog_messages`
--
ALTER TABLE `lupo_unified_dialog_messages`
  ADD PRIMARY KEY (`dialog_message_id`);

--
-- Indexes for table `lupo_unified_paths_firsts`
--
ALTER TABLE `lupo_unified_paths_firsts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `from_visit_id` (`from_visit_id`),
  ADD KEY `to_visit_id` (`to_visit_id`),
  ADD KEY `date_ymd` (`date_ymd`);

--
-- Indexes for table `lupo_unified_referers`
--
ALTER TABLE `lupo_unified_referers`
  ADD PRIMARY KEY (`referer_id`),
  ADD KEY `idx_content_id` (`content_id`),
  ADD KEY `idx_actor_id` (`actor_id`),
  ADD KEY `idx_referer_domain` (`referer_domain`),
  ADD KEY `idx_referer_content_id` (`referer_content_id`),
  ADD KEY `idx_date` (`date_ymd`);

--
-- Indexes for table `lupo_unified_registry`
--
ALTER TABLE `lupo_unified_registry`
  ADD PRIMARY KEY (`unified_registry_id`),
  ADD UNIQUE KEY `uniq_entity` (`entity_type`,`entity_id`),
  ADD UNIQUE KEY `uniq_entity_type_dedicated_index` (`entity_type`,`dedicated_index_id`),
  ADD KEY `idx_entity_key` (`entity_key`),
  ADD KEY `idx_source_table` (`entity_table`),
  ADD KEY `idx_entity_type` (`entity_type`);

--
-- Indexes for table `lupo_unified_sessions`
--
ALTER TABLE `lupo_unified_sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_session_id` (`session_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_system_context` (`system_context`),
  ADD KEY `idx_expires_at` (`expires_at`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `lupo_unified_truth_items`
--
ALTER TABLE `lupo_unified_truth_items`
  ADD PRIMARY KEY (`truth_item_id`);

--
-- Indexes for table `lupo_unified_visits`
--
ALTER TABLE `lupo_unified_visits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `page_domain` (`page_domain`),
  ADD KEY `date_ymd` (`date_ymd`),
  ADD KEY `content_id` (`content_id`);

--
-- Indexes for table `lupo_unified_websites`
--
ALTER TABLE `lupo_unified_websites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `livehelp_id` (`livehelp_id`),
  ADD KEY `site_url` (`site_url`);

--
-- Indexes for table `lupo_uploads`
--
ALTER TABLE `lupo_uploads`
  ADD PRIMARY KEY (`upload_id`),
  ADD KEY `idx_actor_id` (`actor_id`),
  ADD KEY `idx_channel_id` (`channel_id`),
  ADD KEY `idx_file_extension` (`file_extension`),
  ADD KEY `idx_created_ymdhis` (`created_ymdhis`);

--
-- Indexes for table `lupo_user_comments`
--
ALTER TABLE `lupo_user_comments`
  ADD PRIMARY KEY (`user_comment_id`),
  ADD KEY `idx_domain_id` (`domain_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_content_id` (`content_id`),
  ADD KEY `idx_parent_comment_id` (`parent_comment_id`),
  ADD KEY `idx_created_ymdhis` (`created_ymdhis`),
  ADD KEY `idx_updated_ymdhis` (`updated_ymdhis`),
  ADD KEY `idx_is_deleted` (`is_deleted`),
  ADD KEY `idx_ip_hash` (`ip_hash`);

--
-- Indexes for table `lupo_world_events`
--
ALTER TABLE `lupo_world_events`
  ADD PRIMARY KEY (`world_event_id`),
  ADD KEY `idx_world_id` (`world_id`),
  ADD KEY `idx_actor_id` (`actor_id`),
  ADD KEY `idx_event_type` (`event_type`),
  ADD KEY `idx_created_ymdhis` (`created_ymdhis`);

--
-- Indexes for table `lupo_world_registry`
--
ALTER TABLE `lupo_world_registry`
  ADD PRIMARY KEY (`world_id`),
  ADD UNIQUE KEY `unique_world_key` (`world_key`),
  ADD KEY `idx_world_type` (`world_type`),
  ADD KEY `idx_created_ymdhis` (`created_ymdhis`),
  ADD KEY `idx_is_active` (`is_active`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `lupo_actors`
--
ALTER TABLE `lupo_actors`
  MODIFY `actor_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for actor';

--
-- AUTO_INCREMENT for table `lupo_actor_actions`
--
ALTER TABLE `lupo_actor_actions`
  MODIFY `actor_action_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_actor_capabilities`
--
ALTER TABLE `lupo_actor_capabilities`
  MODIFY `actor_capability_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_actor_channels`
--
ALTER TABLE `lupo_actor_channels`
  MODIFY `actor_channel_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for the actor-channel relationship';

--
-- AUTO_INCREMENT for table `lupo_actor_channel_roles`
--
ALTER TABLE `lupo_actor_channel_roles`
  MODIFY `actor_channel_role_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_actor_collections`
--
ALTER TABLE `lupo_actor_collections`
  MODIFY `actor_collection_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_actor_conflicts`
--
ALTER TABLE `lupo_actor_conflicts`
  MODIFY `actor_conflict_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for the conflict record';

--
-- AUTO_INCREMENT for table `lupo_actor_departments`
--
ALTER TABLE `lupo_actor_departments`
  MODIFY `actor_department_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_actor_edges`
--
ALTER TABLE `lupo_actor_edges`
  MODIFY `actor_edge_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_actor_events`
--
ALTER TABLE `lupo_actor_events`
  MODIFY `actor_event_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for actor event';

--
-- AUTO_INCREMENT for table `lupo_actor_group_membership`
--
ALTER TABLE `lupo_actor_group_membership`
  MODIFY `actor_group_membership_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Reference to actors.actor_id';

--
-- AUTO_INCREMENT for table `lupo_actor_handshakes`
--
ALTER TABLE `lupo_actor_handshakes`
  MODIFY `actor_handshake_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_actor_meta`
--
ALTER TABLE `lupo_actor_meta`
  MODIFY `actor_meta_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_actor_object_edges`
--
ALTER TABLE `lupo_actor_object_edges`
  MODIFY `actor_object_edge_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_actor_persona_relationships`
--
ALTER TABLE `lupo_actor_persona_relationships`
  MODIFY `relationship_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_actor_properties`
--
ALTER TABLE `lupo_actor_properties`
  MODIFY `actor_property_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_actor_reply_templates`
--
ALTER TABLE `lupo_actor_reply_templates`
  MODIFY `actor_reply_template_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for the template';

--
-- AUTO_INCREMENT for table `lupo_actor_truth_edges`
--
ALTER TABLE `lupo_actor_truth_edges`
  MODIFY `actor_truth_edge_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_agents`
--
ALTER TABLE `lupo_agents`
  MODIFY `agent_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for agent';

--
-- AUTO_INCREMENT for table `lupo_agent_context_snapshots`
--
ALTER TABLE `lupo_agent_context_snapshots`
  MODIFY `agent_context_snapshot_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the snapshot';

--
-- AUTO_INCREMENT for table `lupo_agent_dependencies`
--
ALTER TABLE `lupo_agent_dependencies`
  MODIFY `agent_dependency_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_agent_external_events`
--
ALTER TABLE `lupo_agent_external_events`
  MODIFY `external_event_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_agent_faucets`
--
ALTER TABLE `lupo_agent_faucets`
  MODIFY `agent_faucet_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_agent_faucet_credentials`
--
ALTER TABLE `lupo_agent_faucet_credentials`
  MODIFY `agent_faucet_credential_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_agent_files`
--
ALTER TABLE `lupo_agent_files`
  MODIFY `file_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_agent_heartbeats`
--
ALTER TABLE `lupo_agent_heartbeats`
  MODIFY `heartbeat_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_agent_properties`
--
ALTER TABLE `lupo_agent_properties`
  MODIFY `agent_property_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_agent_registry`
--
ALTER TABLE `lupo_agent_registry`
  MODIFY `agent_registry_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_agent_tool_calls`
--
ALTER TABLE `lupo_agent_tool_calls`
  MODIFY `agent_tool_call_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_agent_versions`
--
ALTER TABLE `lupo_agent_versions`
  MODIFY `agent_version_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_aliases`
--
ALTER TABLE `lupo_aliases`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_analytics_campaign_vars`
--
ALTER TABLE `lupo_analytics_campaign_vars`
  MODIFY `campaign_var_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_analytics_referers_periods`
--
ALTER TABLE `lupo_analytics_referers_periods`
  MODIFY `analytics_referers_period_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_analytics_visits`
--
ALTER TABLE `lupo_analytics_visits`
  MODIFY `analytics_visit_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for the visit tracking record';

--
-- AUTO_INCREMENT for table `lupo_analytics_visits_daily`
--
ALTER TABLE `lupo_analytics_visits_daily`
  MODIFY `analytics_visits_daily_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for daily page visit statistics';

--
-- AUTO_INCREMENT for table `lupo_analytics_visits_monthly`
--
ALTER TABLE `lupo_analytics_visits_monthly`
  MODIFY `analytics_visits_monthly_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for monthly page visit statistics';

--
-- AUTO_INCREMENT for table `lupo_analytics_visits_periods`
--
ALTER TABLE `lupo_analytics_visits_periods`
  MODIFY `analytics_visits_period_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_anubis_deletion_log`
--
ALTER TABLE `lupo_anubis_deletion_log`
  MODIFY `anubis_deletion_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_anubis_events`
--
ALTER TABLE `lupo_anubis_events`
  MODIFY `anubis_event_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_anubis_mirrored`
--
ALTER TABLE `lupo_anubis_mirrored`
  MODIFY `anubis_mirrored_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_anubis_orphaned`
--
ALTER TABLE `lupo_anubis_orphaned`
  MODIFY `anubis_orphaned_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_anubis_redirects`
--
ALTER TABLE `lupo_anubis_redirects`
  MODIFY `anubis_redirect_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_anubis_revised`
--
ALTER TABLE `lupo_anubis_revised`
  MODIFY `anubis_revised_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_api_clients`
--
ALTER TABLE `lupo_api_clients`
  MODIFY `api_client_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for API client record';

--
-- AUTO_INCREMENT for table `lupo_api_rate_limits`
--
ALTER TABLE `lupo_api_rate_limits`
  MODIFY `api_rate_limit_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for API rate limit record';

--
-- AUTO_INCREMENT for table `lupo_api_tokens`
--
ALTER TABLE `lupo_api_tokens`
  MODIFY `api_token_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for API token record';

--
-- AUTO_INCREMENT for table `lupo_api_token_logs`
--
ALTER TABLE `lupo_api_token_logs`
  MODIFY `api_token_log_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for API token usage log entry';

--
-- AUTO_INCREMENT for table `lupo_api_webhooks`
--
ALTER TABLE `lupo_api_webhooks`
  MODIFY `api_webhook_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for webhook registration';

--
-- AUTO_INCREMENT for table `lupo_artifacts`
--
ALTER TABLE `lupo_artifacts`
  MODIFY `artifact_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_atoms`
--
ALTER TABLE `lupo_atoms`
  MODIFY `atom_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_audit_log`
--
ALTER TABLE `lupo_audit_log`
  MODIFY `audit_log_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_auth_audit_log`
--
ALTER TABLE `lupo_auth_audit_log`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for audit log entry';

--
-- AUTO_INCREMENT for table `lupo_auth_providers`
--
ALTER TABLE `lupo_auth_providers`
  MODIFY `auth_provider_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_auth_users`
--
ALTER TABLE `lupo_auth_users`
  MODIFY `auth_user_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_calibration_impacts`
--
ALTER TABLE `lupo_calibration_impacts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_channels`
--
ALTER TABLE `lupo_channels`
  MODIFY `channel_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for channel';

--
-- AUTO_INCREMENT for table `lupo_channel_boot_detail`
--
ALTER TABLE `lupo_channel_boot_detail`
  MODIFY `detail_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for boot detail';

--
-- AUTO_INCREMENT for table `lupo_channel_boot_log`
--
ALTER TABLE `lupo_channel_boot_log`
  MODIFY `boot_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for boot event';

--
-- AUTO_INCREMENT for table `lupo_channel_escalations`
--
ALTER TABLE `lupo_channel_escalations`
  MODIFY `escalation_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_channel_escalation_rules`
--
ALTER TABLE `lupo_channel_escalation_rules`
  MODIFY `rule_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_channel_files`
--
ALTER TABLE `lupo_channel_files`
  MODIFY `file_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_channel_logs`
--
ALTER TABLE `lupo_channel_logs`
  MODIFY `channel_log_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_channel_log_types`
--
ALTER TABLE `lupo_channel_log_types`
  MODIFY `log_type_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_channel_roles`
--
ALTER TABLE `lupo_channel_roles`
  MODIFY `channel_role_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_channel_state`
--
ALTER TABLE `lupo_channel_state`
  MODIFY `channel_state_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_cip_analytics`
--
ALTER TABLE `lupo_cip_analytics`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_cip_propagation_tracking`
--
ALTER TABLE `lupo_cip_propagation_tracking`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_cip_trends`
--
ALTER TABLE `lupo_cip_trends`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_collections`
--
ALTER TABLE `lupo_collections`
  MODIFY `collection_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for collection';

--
-- AUTO_INCREMENT for table `lupo_collection_tabs`
--
ALTER TABLE `lupo_collection_tabs`
  MODIFY `collection_tab_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_collection_tab_map`
--
ALTER TABLE `lupo_collection_tab_map`
  MODIFY `collection_tab_map_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_collection_tab_paths`
--
ALTER TABLE `lupo_collection_tab_paths`
  MODIFY `collection_tab_path_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_contents`
--
ALTER TABLE `lupo_contents`
  MODIFY `content_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for content';

--
-- AUTO_INCREMENT for table `lupo_content_atom_map`
--
ALTER TABLE `lupo_content_atom_map`
  MODIFY `content_atom_map_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_content_category_map`
--
ALTER TABLE `lupo_content_category_map`
  MODIFY `content_category_map_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for content-category mapping';

--
-- AUTO_INCREMENT for table `lupo_content_engagement_summary`
--
ALTER TABLE `lupo_content_engagement_summary`
  MODIFY `content_engagement_summary_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Reference to the content item';

--
-- AUTO_INCREMENT for table `lupo_content_events`
--
ALTER TABLE `lupo_content_events`
  MODIFY `content_event_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for content event';

--
-- AUTO_INCREMENT for table `lupo_content_hashtag`
--
ALTER TABLE `lupo_content_hashtag`
  MODIFY `content_hashtag_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Reference to the content item';

--
-- AUTO_INCREMENT for table `lupo_content_inbound_links`
--
ALTER TABLE `lupo_content_inbound_links`
  MODIFY `content_inbound_link_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_content_likes`
--
ALTER TABLE `lupo_content_likes`
  MODIFY `content_like_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_content_media`
--
ALTER TABLE `lupo_content_media`
  MODIFY `content_media_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_content_question_map`
--
ALTER TABLE `lupo_content_question_map`
  MODIFY `content_question_map_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_content_references`
--
ALTER TABLE `lupo_content_references`
  MODIFY `content_referenc_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_content_revisions`
--
ALTER TABLE `lupo_content_revisions`
  MODIFY `content_revision_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_content_shares`
--
ALTER TABLE `lupo_content_shares`
  MODIFY `content_share_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_content_tag_relationships`
--
ALTER TABLE `lupo_content_tag_relationships`
  MODIFY `relationship_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for content-tag relationship';

--
-- AUTO_INCREMENT for table `lupo_contexts`
--
ALTER TABLE `lupo_contexts`
  MODIFY `context_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_contexts_map`
--
ALTER TABLE `lupo_contexts_map`
  MODIFY `contexts_map_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_contexts_old`
--
ALTER TABLE `lupo_contexts_old`
  MODIFY `context_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_crafty_syntax_auto_invite`
--
ALTER TABLE `lupo_crafty_syntax_auto_invite`
  MODIFY `crafty_syntax_auto_invite_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_crafty_syntax_chat_mod_departments`
--
ALTER TABLE `lupo_crafty_syntax_chat_mod_departments`
  MODIFY `crafty_syntax_chat_mod_department_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_crafty_syntax_chat_questions`
--
ALTER TABLE `lupo_crafty_syntax_chat_questions`
  MODIFY `crafty_syntax_chat_question_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_crafty_syntax_layer_invites`
--
ALTER TABLE `lupo_crafty_syntax_layer_invites`
  MODIFY `crafty_syntax_layer_invite_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the layer invitation';

--
-- AUTO_INCREMENT for table `lupo_crafty_syntax_leave_message`
--
ALTER TABLE `lupo_crafty_syntax_leave_message`
  MODIFY `crafty_syntax_leave_message_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_crafty_user_mapping`
--
ALTER TABLE `lupo_crafty_user_mapping`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for mapping';

--
-- AUTO_INCREMENT for table `lupo_crm_leads`
--
ALTER TABLE `lupo_crm_leads`
  MODIFY `crm_lead_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for the lead';

--
-- AUTO_INCREMENT for table `lupo_crm_lead_messages`
--
ALTER TABLE `lupo_crm_lead_messages`
  MODIFY `crm_lead_message_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_departments`
--
ALTER TABLE `lupo_departments`
  MODIFY `department_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_department_metadata`
--
ALTER TABLE `lupo_department_metadata`
  MODIFY `department_metadata_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_dialog_channels`
--
ALTER TABLE `lupo_dialog_channels`
  MODIFY `channel_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_dialog_messages`
--
ALTER TABLE `lupo_dialog_messages`
  MODIFY `dialog_message_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for the dialog message';

--
-- AUTO_INCREMENT for table `lupo_dialog_threads`
--
ALTER TABLE `lupo_dialog_threads`
  MODIFY `dialog_thread_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for the dialog thread';

--
-- AUTO_INCREMENT for table `lupo_doctrine_blocks`
--
ALTER TABLE `lupo_doctrine_blocks`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_doctrine_evolution_audit`
--
ALTER TABLE `lupo_doctrine_evolution_audit`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_doctrine_refinements`
--
ALTER TABLE `lupo_doctrine_refinements`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_documents`
--
ALTER TABLE `lupo_documents`
  MODIFY `document_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_document_chunks`
--
ALTER TABLE `lupo_document_chunks`
  MODIFY `document_chunk_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_document_embeddings`
--
ALTER TABLE `lupo_document_embeddings`
  MODIFY `document_embedding_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_edges`
--
ALTER TABLE `lupo_edges`
  MODIFY `edge_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_edge_types`
--
ALTER TABLE `lupo_edge_types`
  MODIFY `edge_type_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_emotional_geometry_calibrations`
--
ALTER TABLE `lupo_emotional_geometry_calibrations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_emotional_translations`
--
ALTER TABLE `lupo_emotional_translations`
  MODIFY `translation_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_entity_edges`
--
ALTER TABLE `lupo_entity_edges`
  MODIFY `entity_edge_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_entity_properties`
--
ALTER TABLE `lupo_entity_properties`
  MODIFY `entity_property_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_event_log`
--
ALTER TABLE `lupo_event_log`
  MODIFY `event_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_event_metadata`
--
ALTER TABLE `lupo_event_metadata`
  MODIFY `metadata_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_federation_categories`
--
ALTER TABLE `lupo_federation_categories`
  MODIFY `federation_category_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_federation_category_map`
--
ALTER TABLE `lupo_federation_category_map`
  MODIFY `federation_category_map_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_federation_discovery`
--
ALTER TABLE `lupo_federation_discovery`
  MODIFY `federation_discovery_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_federation_nodes`
--
ALTER TABLE `lupo_federation_nodes`
  MODIFY `federation_node_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_governance_overrides`
--
ALTER TABLE `lupo_governance_overrides`
  MODIFY `governance_overrid_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_gov_events`
--
ALTER TABLE `lupo_gov_events`
  MODIFY `gov_event_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for governance event';

--
-- AUTO_INCREMENT for table `lupo_gov_event_actor_edges`
--
ALTER TABLE `lupo_gov_event_actor_edges`
  MODIFY `edge_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for edge';

--
-- AUTO_INCREMENT for table `lupo_gov_event_conflicts`
--
ALTER TABLE `lupo_gov_event_conflicts`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key';

--
-- AUTO_INCREMENT for table `lupo_gov_event_dependencies`
--
ALTER TABLE `lupo_gov_event_dependencies`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key';

--
-- AUTO_INCREMENT for table `lupo_gov_event_references`
--
ALTER TABLE `lupo_gov_event_references`
  MODIFY `reference_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for reference';

--
-- AUTO_INCREMENT for table `lupo_gov_timeline_nodes`
--
ALTER TABLE `lupo_gov_timeline_nodes`
  MODIFY `timeline_node_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for timeline node';

--
-- AUTO_INCREMENT for table `lupo_gov_valuations`
--
ALTER TABLE `lupo_gov_valuations`
  MODIFY `valuation_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for valuation';

--
-- AUTO_INCREMENT for table `lupo_groups`
--
ALTER TABLE `lupo_groups`
  MODIFY `group_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_hashtags`
--
ALTER TABLE `lupo_hashtags`
  MODIFY `hashtag_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_help_topics`
--
ALTER TABLE `lupo_help_topics`
  MODIFY `help_topic_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for help topic';

--
-- AUTO_INCREMENT for table `lupo_help_tree`
--
ALTER TABLE `lupo_help_tree`
  MODIFY `help_tree_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_hotfix_registry`
--
ALTER TABLE `lupo_hotfix_registry`
  MODIFY `hotfix_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_human_history_meta`
--
ALTER TABLE `lupo_human_history_meta`
  MODIFY `meta_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_integration_test_results`
--
ALTER TABLE `lupo_integration_test_results`
  MODIFY `test_result_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_interface_translations`
--
ALTER TABLE `lupo_interface_translations`
  MODIFY `interface_translation_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_interpretation_log`
--
ALTER TABLE `lupo_interpretation_log`
  MODIFY `interpretation_log_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for the interpretation log';

--
-- AUTO_INCREMENT for table `lupo_labs_declarations`
--
ALTER TABLE `lupo_labs_declarations`
  MODIFY `labs_declaration_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for LABS declaration record';

--
-- AUTO_INCREMENT for table `lupo_labs_violations`
--
ALTER TABLE `lupo_labs_violations`
  MODIFY `labs_violation_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for violation record';

--
-- AUTO_INCREMENT for table `lupo_legacy_content_mapping`
--
ALTER TABLE `lupo_legacy_content_mapping`
  MODIFY `mapping_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for content mapping';

--
-- AUTO_INCREMENT for table `lupo_memory_debug_log`
--
ALTER TABLE `lupo_memory_debug_log`
  MODIFY `memory_debug_log_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_memory_events`
--
ALTER TABLE `lupo_memory_events`
  MODIFY `memory_event_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_memory_rollups`
--
ALTER TABLE `lupo_memory_rollups`
  MODIFY `memory_rollup_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_meta_log_events`
--
ALTER TABLE `lupo_meta_log_events`
  MODIFY `event_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_modules`
--
ALTER TABLE `lupo_modules`
  MODIFY `module_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_modules_departments`
--
ALTER TABLE `lupo_modules_departments`
  MODIFY `module_department_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_mood_assignments`
--
ALTER TABLE `lupo_mood_assignments`
  MODIFY `mood_assignment_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_mood_registry`
--
ALTER TABLE `lupo_mood_registry`
  MODIFY `mood_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_multi_agent_critique_sync`
--
ALTER TABLE `lupo_multi_agent_critique_sync`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_narrative_fragments`
--
ALTER TABLE `lupo_narrative_fragments`
  MODIFY `narrative_fragment_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_notifications`
--
ALTER TABLE `lupo_notifications`
  MODIFY `notification_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_pack_role_registry`
--
ALTER TABLE `lupo_pack_role_registry`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_permissions`
--
ALTER TABLE `lupo_permissions`
  MODIFY `permission_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_persona_dialogue_patterns`
--
ALTER TABLE `lupo_persona_dialogue_patterns`
  MODIFY `pattern_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_persona_profiles`
--
ALTER TABLE `lupo_persona_profiles`
  MODIFY `persona_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_reference_cited_by`
--
ALTER TABLE `lupo_reference_cited_by`
  MODIFY `reference_cited_by_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_reference_objects`
--
ALTER TABLE `lupo_reference_objects`
  MODIFY `reference_object_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_search_index`
--
ALTER TABLE `lupo_search_index`
  MODIFY `search_index_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_search_rebuild_log`
--
ALTER TABLE `lupo_search_rebuild_log`
  MODIFY `search_rebuild_log_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_semantic_categories`
--
ALTER TABLE `lupo_semantic_categories`
  MODIFY `category_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for semantic category';

--
-- AUTO_INCREMENT for table `lupo_semantic_content_views`
--
ALTER TABLE `lupo_semantic_content_views`
  MODIFY `semantic_view_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for semantic content view';

--
-- AUTO_INCREMENT for table `lupo_semantic_navigation_overview`
--
ALTER TABLE `lupo_semantic_navigation_overview`
  MODIFY `navigation_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for semantic navigation';

--
-- AUTO_INCREMENT for table `lupo_semantic_overlays`
--
ALTER TABLE `lupo_semantic_overlays`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_semantic_paths`
--
ALTER TABLE `lupo_semantic_paths`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_semantic_relationships`
--
ALTER TABLE `lupo_semantic_relationships`
  MODIFY `relationship_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for semantic relationship';

--
-- AUTO_INCREMENT for table `lupo_semantic_search_index`
--
ALTER TABLE `lupo_semantic_search_index`
  MODIFY `search_index_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for search index';

--
-- AUTO_INCREMENT for table `lupo_semantic_tags`
--
ALTER TABLE `lupo_semantic_tags`
  MODIFY `tag_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for semantic tag';

--
-- AUTO_INCREMENT for table `lupo_semantic_translations`
--
ALTER TABLE `lupo_semantic_translations`
  MODIFY `semantic_translation_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_session_events`
--
ALTER TABLE `lupo_session_events`
  MODIFY `session_event_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for session event';

--
-- AUTO_INCREMENT for table `lupo_system_config`
--
ALTER TABLE `lupo_system_config`
  MODIFY `system_config_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_system_events`
--
ALTER TABLE `lupo_system_events`
  MODIFY `system_event_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_system_health_snapshots`
--
ALTER TABLE `lupo_system_health_snapshots`
  MODIFY `health_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_system_logs`
--
ALTER TABLE `lupo_system_logs`
  MODIFY `log_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_tab_events`
--
ALTER TABLE `lupo_tab_events`
  MODIFY `tab_event_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for tab event';

--
-- AUTO_INCREMENT for table `lupo_temporal_coherence_snapshots`
--
ALTER TABLE `lupo_temporal_coherence_snapshots`
  MODIFY `snapshot_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_test_performance_metrics`
--
ALTER TABLE `lupo_test_performance_metrics`
  MODIFY `test_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_tldnr`
--
ALTER TABLE `lupo_tldnr`
  MODIFY `tldnr_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for TL;DR record';

--
-- AUTO_INCREMENT for table `lupo_truth_answers`
--
ALTER TABLE `lupo_truth_answers`
  MODIFY `truth_answer_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_truth_evidence`
--
ALTER TABLE `lupo_truth_evidence`
  MODIFY `truth_evidence_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_truth_questions`
--
ALTER TABLE `lupo_truth_questions`
  MODIFY `truth_question_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_truth_questions_map`
--
ALTER TABLE `lupo_truth_questions_map`
  MODIFY `truth_questions_map_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_truth_relations`
--
ALTER TABLE `lupo_truth_relations`
  MODIFY `truth_relation_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_truth_sources`
--
ALTER TABLE `lupo_truth_sources`
  MODIFY `truth_sourc_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_truth_topics`
--
ALTER TABLE `lupo_truth_topics`
  MODIFY `truth_topic_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_unified_analytics_paths`
--
ALTER TABLE `lupo_unified_analytics_paths`
  MODIFY `unified_analytics_path_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_unified_paths_firsts`
--
ALTER TABLE `lupo_unified_paths_firsts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_unified_referers`
--
ALTER TABLE `lupo_unified_referers`
  MODIFY `referer_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_unified_registry`
--
ALTER TABLE `lupo_unified_registry`
  MODIFY `unified_registry_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_unified_sessions`
--
ALTER TABLE `lupo_unified_sessions`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for session';

--
-- AUTO_INCREMENT for table `lupo_unified_visits`
--
ALTER TABLE `lupo_unified_visits`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_unified_websites`
--
ALTER TABLE `lupo_unified_websites`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_uploads`
--
ALTER TABLE `lupo_uploads`
  MODIFY `upload_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_user_comments`
--
ALTER TABLE `lupo_user_comments`
  MODIFY `user_comment_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_world_events`
--
ALTER TABLE `lupo_world_events`
  MODIFY `world_event_id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lupo_world_registry`
--
ALTER TABLE `lupo_world_registry`
  MODIFY `world_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for world node';

--
-- Constraints for dumped tables
--

--
-- Constraints for table `lupo_channel_boot_detail`
--
ALTER TABLE `lupo_channel_boot_detail`
  ADD CONSTRAINT `fk_boot_detail_boot` FOREIGN KEY (`boot_id`) REFERENCES `lupo_channel_boot_log` (`boot_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_boot_detail_channel` FOREIGN KEY (`channel_id`) REFERENCES `lupo_channels` (`channel_id`) ON DELETE CASCADE;

--
-- Constraints for table `lupo_contexts`
--
ALTER TABLE `lupo_contexts`
  ADD CONSTRAINT `fk_context_parent` FOREIGN KEY (`parent_context_id`) REFERENCES `lupo_contexts` (`context_id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
