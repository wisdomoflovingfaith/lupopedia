-- ============================================================================
-- Lupopedia 4.2.0 Migration: Fix UNSIGNED Columns and Primary Key Naming
-- Generated from TOON file metadata
-- ============================================================================
-- Purpose: 
--   1. Remove all UNSIGNED keywords from columns (for PostgreSQL compatibility)
--   2. Rename primary keys to follow "singular table name + _id" convention
--   3. Create missing tables (e.g., lupo_collection_tab_paths)
--
-- Doctrine: 
--   - No UNSIGNED (PostgreSQL doesn't support it)
--   - Primary keys must be singular table name + _id (because no FK keys)
--   - All relationships are application-managed, so naming must be explicit
--
-- SCOPE: 
--   - ONLY lupo_* prefixed tables are included in this migration
--   - livehelp_* prefixed tables are OLD Crafty Syntax tables and are IGNORED
--
-- Generated: 2026-01-10 (from TOON files)
-- ============================================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- ============================================================================
-- PART 0: CREATE MISSING TABLES
-- ============================================================================

-- ============================================================================
-- CREATE MISSING TABLE: lupo_collection_tab_paths
-- ============================================================================
CREATE TABLE IF NOT EXISTS `lupo_collection_tab_paths` (
  `collection_tab_path_id` bigint NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `collection_id` bigint NOT NULL,
  `collection_tab_id` bigint NOT NULL,
  `path` varchar(500) NOT NULL COMMENT 'Full tab path: departments/parks-and-recreation/summer-programs',
  `depth` int NOT NULL COMMENT 'Number of levels (1 = root tab)',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS',
  `updated_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS',
  `is_deleted` tinyint NOT NULL DEFAULT 0,
  `deleted_ymdhis` bigint DEFAULT NULL,
  UNIQUE KEY `unique_tab_path` (`collection_id`, `collection_tab_id`, `path`),
  INDEX `idx_collection` (`collection_id`),
  INDEX `idx_collection_tab` (`collection_tab_id`),
  INDEX `idx_path` (`path`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Full hierarchical paths for tabs - enables fast lookups and semantic edge generation';

-- ============================================================================
-- PART 1: REMOVE UNSIGNED FROM ALL COLUMNS (non-PK first)
-- ============================================================================

-- lupo_agent_context_snapshots: Remove UNSIGNED from columns
ALTER TABLE `lupo_agent_context_snapshots`
  MODIFY COLUMN `token_count` int COMMENT 'Approximate token count for LLM context',
  MODIFY COLUMN `character_count` int COMMENT 'Raw character count before compression',
  MODIFY COLUMN `compressed_size` int COMMENT 'Size in bytes after compression',
  MODIFY COLUMN `serialization_time_ms` int COMMENT 'Time taken to serialize context (ms)',
  MODIFY COLUMN `compression_time_ms` int COMMENT 'Time taken to compress (ms)',
  MODIFY COLUMN `conversation_turn` int COMMENT 'Conversation turn number when snapshot was taken';

-- lupo_agent_registry: Remove UNSIGNED from PK agent_registry_id
ALTER TABLE `lupo_agent_registry`
  MODIFY COLUMN `agent_registry_id` int NOT NULL auto_increment FIRST;

-- lupo_interface_translations: Remove UNSIGNED from PK interface_translation_id
ALTER TABLE `lupo_interface_translations`
  MODIFY COLUMN `interface_translation_id` bigint NOT NULL auto_increment FIRST;

-- ============================================================================
-- PART 2: RENAME PRIMARY KEYS AND REMOVE UNSIGNED FROM PKs
-- ============================================================================

-- lupo_actor_capabilities: Rename PK from actor_capabilities_id to actor_capability_id
ALTER TABLE `lupo_actor_capabilities`
  DROP PRIMARY KEY,
  CHANGE COLUMN `actor_capabilities_id` `actor_capability_id` bigint NOT NULL auto_increment FIRST,
  ADD PRIMARY KEY (`actor_capability_id`);

-- lupo_actor_conflicts: Rename PK from conflict_id to actor_conflict_id
ALTER TABLE `lupo_actor_conflicts`
  DROP PRIMARY KEY,
  CHANGE COLUMN `conflict_id` `actor_conflict_id` bigint NOT NULL auto_increment COMMENT 'Primary key for the conflict record' FIRST,
  ADD PRIMARY KEY (`actor_conflict_id`);

-- lupo_actor_edges: Rename PK from actor_edges_id to actor_edge_id
ALTER TABLE `lupo_actor_edges`
  DROP PRIMARY KEY,
  CHANGE COLUMN `actor_edges_id` `actor_edge_id` bigint NOT NULL auto_increment FIRST,
  ADD PRIMARY KEY (`actor_edge_id`);

-- lupo_actor_group_membership: Rename PK from actor_id to actor_group_membership_id
ALTER TABLE `lupo_actor_group_membership`
  DROP PRIMARY KEY,
  CHANGE COLUMN `actor_id` `actor_group_membership_id` bigint NOT NULL COMMENT 'Reference to actors.actor_id' AUTO_INCREMENT FIRST,
  ADD PRIMARY KEY (`actor_group_membership_id`);

-- lupo_actor_properties: Rename PK from actor_properties_id to actor_property_id
ALTER TABLE `lupo_actor_properties`
  DROP PRIMARY KEY,
  CHANGE COLUMN `actor_properties_id` `actor_property_id` bigint NOT NULL auto_increment FIRST,
  ADD PRIMARY KEY (`actor_property_id`);

-- lupo_actor_reply_templates: Rename PK from template_id to actor_reply_template_id
ALTER TABLE `lupo_actor_reply_templates`
  DROP PRIMARY KEY,
  CHANGE COLUMN `template_id` `actor_reply_template_id` bigint NOT NULL auto_increment COMMENT 'Primary key for the template' FIRST,
  ADD PRIMARY KEY (`actor_reply_template_id`);

-- lupo_agent_context_snapshots: Rename PK from snapshot_id to agent_context_snapshot_id
ALTER TABLE `lupo_agent_context_snapshots`
  DROP PRIMARY KEY,
  CHANGE COLUMN `snapshot_id` `agent_context_snapshot_id` bigint NOT NULL auto_increment COMMENT 'Unique identifier for the snapshot' FIRST,
  ADD PRIMARY KEY (`agent_context_snapshot_id`);

-- lupo_agent_dependencies: Rename PK from dependency_id to agent_dependency_id
ALTER TABLE `lupo_agent_dependencies`
  DROP PRIMARY KEY,
  CHANGE COLUMN `dependency_id` `agent_dependency_id` bigint NOT NULL auto_increment FIRST,
  ADD PRIMARY KEY (`agent_dependency_id`);

-- lupo_agent_faucet_credentials: Rename PK from id to agent_faucet_credential_id
ALTER TABLE `lupo_agent_faucet_credentials`
  DROP PRIMARY KEY,
  CHANGE COLUMN `id` `agent_faucet_credential_id` int NOT NULL auto_increment FIRST,
  ADD PRIMARY KEY (`agent_faucet_credential_id`);

-- lupo_agent_faucets: Rename PK from faucet_id to agent_faucet_id
ALTER TABLE `lupo_agent_faucets`
  DROP PRIMARY KEY,
  CHANGE COLUMN `faucet_id` `agent_faucet_id` bigint NOT NULL auto_increment FIRST,
  ADD PRIMARY KEY (`agent_faucet_id`);

-- lupo_agent_properties: Rename PK from agent_properties_id to agent_property_id
ALTER TABLE `lupo_agent_properties`
  DROP PRIMARY KEY,
  CHANGE COLUMN `agent_properties_id` `agent_property_id` bigint NOT NULL auto_increment FIRST,
  ADD PRIMARY KEY (`agent_property_id`);

-- lupo_agent_tool_calls: Rename PK from tool_call_id to agent_tool_call_id
ALTER TABLE `lupo_agent_tool_calls`
  DROP PRIMARY KEY,
  CHANGE COLUMN `tool_call_id` `agent_tool_call_id` bigint NOT NULL auto_increment FIRST,
  ADD PRIMARY KEY (`agent_tool_call_id`);

-- lupo_agent_versions: Rename PK from version_id to agent_version_id
ALTER TABLE `lupo_agent_versions`
  DROP PRIMARY KEY,
  CHANGE COLUMN `version_id` `agent_version_id` bigint NOT NULL AUTO_INCREMENT FIRST,
  ADD PRIMARY KEY (`agent_version_id`);

-- lupo_analytics_campaign_vars_daily: Rename PK from id to analytics_campaign_vars_daily_id
ALTER TABLE `lupo_analytics_campaign_vars_daily`
  DROP PRIMARY KEY,
  CHANGE COLUMN `id` `analytics_campaign_vars_daily_id` bigint NOT NULL auto_increment FIRST,
  ADD PRIMARY KEY (`analytics_campaign_vars_daily_id`);

-- lupo_analytics_campaign_vars_monthly: Rename PK from campaign_vars_monthly_id to analytics_campaign_vars_monthly_id
ALTER TABLE `lupo_analytics_campaign_vars_monthly`
  DROP PRIMARY KEY,
  CHANGE COLUMN `campaign_vars_monthly_id` `analytics_campaign_vars_monthly_id` bigint NOT NULL auto_increment COMMENT 'Primary key for monthly campaign/query variable tracking' FIRST,
  ADD PRIMARY KEY (`analytics_campaign_vars_monthly_id`);

-- lupo_analytics_visits: Rename PK from analytics_visit_track_id to analytics_visit_id
ALTER TABLE `lupo_analytics_visits`
  DROP PRIMARY KEY,
  CHANGE COLUMN `analytics_visit_track_id` `analytics_visit_id` bigint NOT NULL auto_increment COMMENT 'Primary key for the visit tracking record' FIRST,
  ADD PRIMARY KEY (`analytics_visit_id`);

-- lupo_analytics_visits_monthly: Rename PK from analytics_visit_track_monthly_id to analytics_visits_monthly_id
ALTER TABLE `lupo_analytics_visits_monthly`
  DROP PRIMARY KEY,
  CHANGE COLUMN `analytics_visit_track_monthly_id` `analytics_visits_monthly_id` bigint NOT NULL auto_increment COMMENT 'Primary key for monthly page visit statistics' FIRST,
  ADD PRIMARY KEY (`analytics_visits_monthly_id`);

-- lupo_anibus_events: Rename PK from id to anibus_event_id
ALTER TABLE `lupo_anibus_events`
  DROP PRIMARY KEY,
  CHANGE COLUMN `id` `anibus_event_id` int NOT NULL auto_increment FIRST,
  ADD PRIMARY KEY (`anibus_event_id`);

-- lupo_anibus_orphans: Rename PK from id to anibus_orphan_id
ALTER TABLE `lupo_anibus_orphans`
  DROP PRIMARY KEY,
  CHANGE COLUMN `id` `anibus_orphan_id` int NOT NULL auto_increment FIRST,
  ADD PRIMARY KEY (`anibus_orphan_id`);

-- lupo_anibus_redirects: Rename PK from id to anibus_redirect_id
ALTER TABLE `lupo_anibus_redirects`
  DROP PRIMARY KEY,
  CHANGE COLUMN `id` `anibus_redirect_id` int NOT NULL auto_increment FIRST,
  ADD PRIMARY KEY (`anibus_redirect_id`);

-- lupo_api_rate_limits: Rename PK from api_rate_limits_id to api_rate_limit_id
ALTER TABLE `lupo_api_rate_limits`
  DROP PRIMARY KEY,
  CHANGE COLUMN `api_rate_limits_id` `api_rate_limit_id` bigint NOT NULL auto_increment COMMENT 'Primary key for API rate limit record' FIRST,
  ADD PRIMARY KEY (`api_rate_limit_id`);

-- lupo_api_token_logs: Rename PK from api_token_logs_id to api_token_log_id
ALTER TABLE `lupo_api_token_logs`
  DROP PRIMARY KEY,
  CHANGE COLUMN `api_token_logs_id` `api_token_log_id` bigint NOT NULL auto_increment COMMENT 'Primary key for API token usage log entry' FIRST,
  ADD PRIMARY KEY (`api_token_log_id`);

-- lupo_auth_providers: Rename PK from auth_providers_id to auth_provider_id
ALTER TABLE `lupo_auth_providers`
  DROP PRIMARY KEY,
  CHANGE COLUMN `auth_providers_id` `auth_provider_id` bigint NOT NULL auto_increment FIRST,
  ADD PRIMARY KEY (`auth_provider_id`);

-- lupo_auth_users: Rename PK from user_id to auth_user_id
ALTER TABLE `lupo_auth_users`
  DROP PRIMARY KEY,
  CHANGE COLUMN `user_id` `auth_user_id` bigint NOT NULL AUTO_INCREMENT FIRST,
  ADD PRIMARY KEY (`auth_user_id`);

-- lupo_content_engagement_summary: Rename PK from content_id to content_engagement_summary_id
ALTER TABLE `lupo_content_engagement_summary`
  DROP PRIMARY KEY,
  CHANGE COLUMN `content_id` `content_engagement_summary_id` bigint NOT NULL COMMENT 'Reference to the content item' AUTO_INCREMENT FIRST,
  ADD PRIMARY KEY (`content_engagement_summary_id`);

-- lupo_content_hashtag: Rename PK from content_id to content_hashtag_id
ALTER TABLE `lupo_content_hashtag`
  DROP PRIMARY KEY,
  CHANGE COLUMN `content_id` `content_hashtag_id` bigint NOT NULL COMMENT 'Reference to the content item' AUTO_INCREMENT FIRST,
  ADD PRIMARY KEY (`content_hashtag_id`);

-- lupo_content_inbound_links: Rename PK from content_inbound_links_id to content_inbound_link_id
ALTER TABLE `lupo_content_inbound_links`
  DROP PRIMARY KEY,
  CHANGE COLUMN `content_inbound_links_id` `content_inbound_link_id` bigint NOT NULL auto_increment FIRST,
  ADD PRIMARY KEY (`content_inbound_link_id`);

-- lupo_content_likes: Rename PK from content_likes_id to content_like_id
ALTER TABLE `lupo_content_likes`
  DROP PRIMARY KEY,
  CHANGE COLUMN `content_likes_id` `content_like_id` bigint NOT NULL auto_increment FIRST,
  ADD PRIMARY KEY (`content_like_id`);

-- lupo_content_media: Rename PK from media_id to content_media_id
ALTER TABLE `lupo_content_media`
  DROP PRIMARY KEY,
  CHANGE COLUMN `media_id` `content_media_id` bigint NOT NULL auto_increment FIRST,
  ADD PRIMARY KEY (`content_media_id`);

-- lupo_content_references: Rename PK from content_reference_id to content_referenc_id
ALTER TABLE `lupo_content_references`
  DROP PRIMARY KEY,
  CHANGE COLUMN `content_reference_id` `content_referenc_id` bigint NOT NULL auto_increment FIRST,
  ADD PRIMARY KEY (`content_referenc_id`);

-- lupo_content_revisions: Rename PK from revision_id to content_revision_id
ALTER TABLE `lupo_content_revisions`
  DROP PRIMARY KEY,
  CHANGE COLUMN `revision_id` `content_revision_id` bigint NOT NULL auto_increment FIRST,
  ADD PRIMARY KEY (`content_revision_id`);

-- lupo_content_shares: Rename PK from content_shares_id to content_share_id
ALTER TABLE `lupo_content_shares`
  DROP PRIMARY KEY,
  CHANGE COLUMN `content_shares_id` `content_share_id` bigint NOT NULL auto_increment FIRST,
  ADD PRIMARY KEY (`content_share_id`);

-- lupo_contexts_map: Rename PK from context_map_id to contexts_map_id
ALTER TABLE `lupo_contexts_map`
  DROP PRIMARY KEY,
  CHANGE COLUMN `context_map_id` `contexts_map_id` bigint NOT NULL auto_increment FIRST,
  ADD PRIMARY KEY (`contexts_map_id`);

-- lupo_dialog_message_bodies: Rename PK from dialog_message_bodies_id to dialog_message_body_id
ALTER TABLE `lupo_dialog_message_bodies`
  DROP PRIMARY KEY,
  CHANGE COLUMN `dialog_message_bodies_id` `dialog_message_body_id` bigint NOT NULL auto_increment COMMENT 'Primary key for the full message body' FIRST,
  ADD PRIMARY KEY (`dialog_message_body_id`);

-- lupo_dialog_threads: Rename PK from dialog_threads_id to dialog_thread_id
ALTER TABLE `lupo_dialog_threads`
  DROP PRIMARY KEY,
  CHANGE COLUMN `dialog_threads_id` `dialog_thread_id` bigint NOT NULL auto_increment COMMENT 'Primary key for the dialog thread' FIRST,
  ADD PRIMARY KEY (`dialog_thread_id`);

-- lupo_document_chunks: Rename PK from chunk_id to document_chunk_id
ALTER TABLE `lupo_document_chunks`
  DROP PRIMARY KEY,
  CHANGE COLUMN `chunk_id` `document_chunk_id` bigint NOT NULL auto_increment FIRST,
  ADD PRIMARY KEY (`document_chunk_id`);

-- lupo_document_embeddings: Rename PK from embedding_id to document_embedding_id
ALTER TABLE `lupo_document_embeddings`
  DROP PRIMARY KEY,
  CHANGE COLUMN `embedding_id` `document_embedding_id` bigint NOT NULL auto_increment FIRST,
  ADD PRIMARY KEY (`document_embedding_id`);

-- lupo_federation_nodes: Rename PK from federation_node_id to federation_nod_id
ALTER TABLE `lupo_federation_nodes`
  DROP PRIMARY KEY,
  CHANGE COLUMN `federation_node_id` `federation_nod_id` bigint NOT NULL auto_increment FIRST,
  ADD PRIMARY KEY (`federation_nod_id`);

-- lupo_governance_overrides: Rename PK from governance_overrides_id to governance_overrid_id
ALTER TABLE `lupo_governance_overrides`
  DROP PRIMARY KEY,
  CHANGE COLUMN `governance_overrides_id` `governance_overrid_id` bigint NOT NULL auto_increment FIRST,
  ADD PRIMARY KEY (`governance_overrid_id`);

-- lupo_interpretation_log: Rename PK from interpretation_id to interpretation_log_id
ALTER TABLE `lupo_interpretation_log`
  DROP PRIMARY KEY,
  CHANGE COLUMN `interpretation_id` `interpretation_log_id` bigint NOT NULL auto_increment COMMENT 'Primary key for the interpretation log' FIRST,
  ADD PRIMARY KEY (`interpretation_log_id`);

-- lupo_narrative_fragments: Rename PK from fragment_id to narrative_fragment_id
ALTER TABLE `lupo_narrative_fragments`
  DROP PRIMARY KEY,
  CHANGE COLUMN `fragment_id` `narrative_fragment_id` bigint NOT NULL auto_increment FIRST,
  ADD PRIMARY KEY (`narrative_fragment_id`);

-- lupo_truth_sources: Rename PK from truth_source_id to truth_sourc_id
ALTER TABLE `lupo_truth_sources`
  DROP PRIMARY KEY,
  CHANGE COLUMN `truth_source_id` `truth_sourc_id` bigint NOT NULL auto_increment FIRST,
  ADD PRIMARY KEY (`truth_sourc_id`);

-- lupo_truth_topics: Rename PK from topic_id to truth_topic_id
ALTER TABLE `lupo_truth_topics`
  DROP PRIMARY KEY,
  CHANGE COLUMN `topic_id` `truth_topic_id` bigint NOT NULL auto_increment FIRST,
  ADD PRIMARY KEY (`truth_topic_id`);

-- lupo_user_comments: Rename PK from user_comments_id to user_comment_id
ALTER TABLE `lupo_user_comments`
  DROP PRIMARY KEY,
  CHANGE COLUMN `user_comments_id` `user_comment_id` bigint NOT NULL auto_increment FIRST,
  ADD PRIMARY KEY (`user_comment_id`);

-- ============================================================================
-- PART 3: VERIFICATION QUERIES
-- ============================================================================

-- Check for remaining UNSIGNED columns (should return empty)
SELECT 
    TABLE_NAME, 
    COLUMN_NAME, 
    COLUMN_TYPE
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_SCHEMA = DATABASE()
  AND TABLE_NAME LIKE 'lupo_%'
  AND COLUMN_TYPE LIKE '%unsigned%'
ORDER BY TABLE_NAME, COLUMN_NAME;

-- Check primary key naming (should all follow "singular + _id" convention)
SELECT 
    TABLE_NAME,
    COLUMN_NAME as PRIMARY_KEY
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE TABLE_SCHEMA = DATABASE()
  AND TABLE_NAME LIKE 'lupo_%'
  AND CONSTRAINT_NAME = 'PRIMARY'
ORDER BY TABLE_NAME;

COMMIT;
