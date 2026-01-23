-- =====================================================
-- LupoPedia Migration - ALTER TABLE Statements
-- Future-proofing additions - HERITAGE-SAFE MODE
-- These ALTER statements are safe to run multiple times
-- Review and manually trim if needed
-- =====================================================

-- Core Tables ALTERs
-- auth_audit_log
-- ALTER TABLE `auth_audit_log` 
--   ADD COLUMN IF NOT EXISTS `additional_context` json COMMENT 'Additional context data' AFTER `event_data`,
--   ADD INDEX IF NOT EXISTS `idx_event_type` (`event_type`),
--   ADD INDEX IF NOT EXISTS `idx_system_context` (`system_context`);

-- integration_test_results  
-- ALTER TABLE `integration_test_results`
--   ADD INDEX IF NOT EXISTS `idx_test_suite` (`test_suite`),
--   ADD INDEX IF NOT EXISTS `idx_status` (`status`);

-- lupo_actors
-- ALTER TABLE `lupo_actors`
--   ADD INDEX IF NOT EXISTS `idx_actor_source_type` (`actor_source_type`),
--   ADD INDEX IF NOT EXISTS `idx_adversarial_role` (`adversarial_role`);

-- lupo_auth_users
-- ALTER TABLE `lupo_auth_users`
--   ADD INDEX IF NOT EXISTS `idx_provider_id` (`provider_id`);

-- Agent Tables ALTERs
-- lupo_agents
-- ALTER TABLE `lupo_agents`
--   ADD INDEX IF NOT EXISTS `idx_provider` (`provider`),
--   ADD INDEX IF NOT EXISTS `idx_model_name` (`model_name`);

-- Channel Tables ALTERs
-- lupo_channels
-- ALTER TABLE `lupo_channels`
--   ADD INDEX IF NOT EXISTS `idx_channel_type` (`channel_type`),
--   ADD INDEX IF NOT EXISTS `idx_created_by_actor_id` (`created_by_actor_id`);

-- Content Tables ALTERs
-- lupo_contents
-- ALTER TABLE `lupo_contents`
--   ADD INDEX IF NOT EXISTS `idx_content_parent_id` (`content_parent_id`),
--   ADD INDEX IF NOT EXISTS `idx_group_id` (`group_id`),
--   ADD INDEX IF NOT EXISTS `idx_triage_status` (`triage_status`),
--   ADD INDEX IF NOT EXISTS `idx_utc_cycle` (`utc_cycle`);

-- lupo_collections
-- ALTER TABLE `lupo_collections`
--   ADD INDEX IF NOT EXISTS `idx_parent_id` (`parent_id`);

-- Actor Relationship Tables ALTERs
-- lupo_actor_capabilities
-- ALTER TABLE `lupo_actor_capabilities`
--   ADD INDEX IF NOT EXISTS `idx_scope_limitation` (`scope_limitation`),
--   ADD INDEX IF NOT EXISTS `idx_requires_approval` (`requires_approval`);

-- lupo_actor_edges
-- ALTER TABLE `lupo_actor_edges`
--   ADD INDEX IF NOT EXISTS `idx_weight` (`weight`);

-- System Tables ALTERs
-- lupo_system_config
-- ALTER TABLE `lupo_system_config`
--   ADD INDEX IF NOT EXISTS `idx_config_type` (`config_type`);

-- lupo_system_events
-- ALTER TABLE `lupo_system_events`
--   ADD INDEX IF NOT EXISTS `idx_processed_ymdhis` (`processed_ymdhis`);

-- Governance Tables ALTERs
-- lupo_gov_events
-- ALTER TABLE `lupo_gov_events`
--   ADD INDEX IF NOT EXISTS `idx_completed_ymdhis` (`completed_ymdhis`);

-- Foreign Key Constraints (Optional - Review before enabling)
-- These are commented out for safety - enable manually if needed

-- ALTER TABLE `lupo_actor_channels` 
--   ADD CONSTRAINT `fk_actor_channels_actor_id` 
--   FOREIGN KEY (`actor_id`) REFERENCES `lupo_actors` (`actor_id`) 
--   ON DELETE CASCADE;

-- ALTER TABLE `lupo_actor_channels` 
--   ADD CONSTRAINT `fk_actor_channels_channel_id` 
--   FOREIGN KEY (`channel_id`) REFERENCES `lupo_channels` (`channel_id`) 
--   ON DELETE CASCADE;

-- ALTER TABLE `lupo_contents` 
--   ADD CONSTRAINT `fk_contents_user_id` 
--   FOREIGN KEY (`user_id`) REFERENCES `lupo_auth_users` (`auth_user_id`) 
--   ON DELETE SET NULL;

-- ALTER TABLE `lupo_contents` 
--   ADD CONSTRAINT `fk_contents_collection_id` 
--   FOREIGN KEY (`default_collection_id`) REFERENCES `lupo_collections` (`collection_id`) 
--   ON DELETE SET NULL;

-- ALTER TABLE `lupo_actor_collections` 
--   ADD CONSTRAINT `fk_actor_collections_actor_id` 
--   FOREIGN KEY (`actor_id`) REFERENCES `lupo_actors` (`actor_id`) 
--   ON DELETE CASCADE;

-- ALTER TABLE `lupo_actor_collections` 
--   ADD CONSTRAINT `fk_actor_collections_collection_id` 
--   FOREIGN KEY (`collection_id`) REFERENCES `lupo_collections` (`collection_id`) 
--   ON DELETE CASCADE;

-- Performance Optimization Indexes (Optional)
-- These indexes may improve query performance but add overhead
-- Review your query patterns before enabling

-- ALTER TABLE `lupo_contents` 
--   ADD INDEX IF NOT EXISTS `idx_composite_status_created` (`status`, `created_ymdhis`),
--   ADD INDEX IF NOT EXISTS `idx_composite_visibility_domain` (`visibility`, `federation_node_id`);

-- ALTER TABLE `lupo_actor_events` 
--   ADD INDEX IF NOT EXISTS `idx_composite_actor_created` (`actor_id`, `created_ymdhis`);

-- ALTER TABLE `lupo_system_logs` 
--   ADD INDEX IF NOT EXISTS `idx_composite_level_created` (`log_level`, `created_ymdhis`);

-- =====================================================
-- END OF ALTER STATEMENTS
-- Review all ALTER statements before execution
-- Some may not be needed for your specific use case
-- =====================================================
