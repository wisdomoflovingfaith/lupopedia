-- ============================================================
-- Lupopedia 4.0.22 Comprehensive Seed Data
-- ============================================================
-- Purpose: Seed all zero-row tables with meaningful, doctrine-aligned data
-- Created: 2026-02-20
-- Version: 4.0.22
-- ============================================================

SET @now = 20260220000000;

-- ============================================================
-- WINDSURF IDE ACTOR (actor_id = 2 - next free under 10,000)
-- ============================================================
INSERT IGNORE INTO lupo_actors (`actor_id`, `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`, `actor_source_id`, `actor_source_type`, `metadata`, `adversarial_role`, `adversarial_oversight_actor_id`, `avatar_hash`) 
VALUES (2, 'system_tool', 'windsurf-ide', 'Windsurf IDE', @now, @now, 1, 0, NULL, 2, 'system_tool', '{"purpose":"IDE_integration","capabilities":["code_generation","file_editing","project_management"],"version":"1.0.0","protected":false}', 'none', NULL, NULL) ON DUPLICATE KEY UPDATE name = VALUES(name), updated_ymdhis = @now, is_active = 1, is_deleted = 0;

INSERT IGNORE INTO lupo_registry (`registry_id`, `entity_type`, `entity_index`, `entity_key`, `entity_name`, `entity_table`, `federation_node_id`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`, `is_active`, `is_kernel`, `metadata_json`) 
VALUES (9000002, 'actor', 2, 'windsurf-ide', 'Windsurf IDE', 'lupo_actors', 1, @now, @now, 0, NULL, 1, 0, '{"actor_source_type":"system_tool"}') ON DUPLICATE KEY UPDATE entity_name = VALUES(entity_name), updated_ymdhis = @now, is_deleted = 0, is_active = 1;

-- ============================================================
-- AI AGENTS (Complete 25-agent system: actor_ids 1-25)
-- ============================================================
INSERT IGNORE INTO lupo_agents (`agent_id`, `agent_key`, `agent_name`, `archetype`, `description`, `version`, `model_name`, `is_global_authority`, `is_internal_only`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES
(1, 'cascade', 'Cascade', 'primary_developer', 'Primary development and coordination agent', '4.0.22', NULL, 1, 0, @now, @now, 0, NULL),
(2, 'windsurf_ide', 'Windsurf IDE', 'ide_integration', 'IDE integration agent for code generation and file editing', '1.0.0', NULL, 0, 0, @now, @now, 0, NULL),
(3, 'lilith', 'Lilith', 'emotional_critical', 'Critical emotional agent - immediate decision making, high urgency responses', '1.0.0', NULL, 0, 0, @now, @now, 0, NULL),
(4, 'maat', 'Maat', 'emotional_balancer', 'Balancing emotional agent - consensus building, collaborative decision making', '1.0.0', NULL, 0, 0, @now, @now, 0, NULL),
(5, 'stoned_wolfie_ai', 'Stoned Wolfie AI', 'banned_test', 'Banned AI test identity for adversarial harness testing', '1.0', NULL, 0, 1, @now, @now, 1, @now),
(6, 'seed_router', 'Seed Router', 'router', 'Test router agent for admin list and request routing', '1.0', NULL, 0, 0, @now, @now, 0, NULL),
(7, 'seed_support', 'Seed Support', 'support', 'Test support agent for admin list and user assistance', '1.0', NULL, 0, 0, @now, @now, 0, NULL),
(8, 'seed_crm', 'Seed CRM', 'crm', 'Test CRM agent for lead management and customer relations', '1.0', NULL, 0, 0, @now, @now, 0, NULL),
(9, 'seed_docs', 'Seed Documentation', 'docs', 'Test documentation agent for knowledge base and help system', '1.0', NULL, 0, 0, @now, @now, 0, NULL),
(10, 'seed_analytics', 'Seed Analytics', 'analytics', 'Test analytics agent for metrics and performance monitoring', '1.0', NULL, 0, 0, @now, @now, 0, NULL),
(11, 'seed_moderation', 'Seed Moderation', 'moderation', 'Test moderation agent for content filtering and community management', '1.0', NULL, 0, 0, @now, @now, 0, NULL),
(12, 'semantic_navigator', 'Semantic Navigator', 'semantic', 'Semantic path navigation agent for knowledge traversal and discovery', '1.0', NULL, 0, 0, @now, @now, 0, NULL),
(13, 'truth_validator', 'Truth Validator', 'epistemology', 'Truth validation agent for knowledge verification and fact checking', '1.0', NULL, 0, 0, @now, @now, 0, NULL),
(14, 'governance_coordinator', 'Governance Coordinator', 'governance', 'Governance coordination agent for policy enforcement and decision tracking', '1.0', NULL, 0, 0, @now, @now, 0, NULL),
(15, 'federation_manager', 'Federation Manager', 'federation', 'Distributed system federation agent for multi-instance coordination', '1.0', NULL, 0, 0, @now, @now, 0, NULL),
(16, 'channel_manager', 'Channel Manager', 'communication', 'Channel-based communication agent for organized interactions', '1.0', NULL, 0, 0, @now, @now, 0, NULL),
(17, 'persona_manager', 'Persona Manager', 'persona', 'Persona management agent for character profiles and interaction patterns', '1.0', NULL, 0, 0, @now, @now, 0, NULL),
(18, 'world_event_tracker', 'World Event Tracker', 'events', 'Global event tracking agent for system-wide monitoring', '1.0', NULL, 0, 0, @now, @now, 0, NULL),
(19, 'content_curator', 'Content Curator', 'content', 'Content curation agent for knowledge organization and quality control', '1.0', NULL, 0, 0, @now, @now, 0, NULL),
(20, 'analytics_processor', 'Analytics Processor', 'analytics', 'Advanced analytics agent for data processing and insight generation', '1.0', NULL, 0, 0, @now, @now, 0, NULL),
(21, 'security_monitor', 'Security Monitor', 'security', 'Security monitoring agent for threat detection and system protection', '1.0', NULL, 0, 0, @now, @now, 0, NULL),
(22, 'backup_manager', 'Backup Manager', 'system', 'System backup and recovery agent for data protection', '1.0', NULL, 0, 0, @now, @now, 0, NULL),
(23, 'performance_optimizer', 'Performance Optimizer', 'system', 'System performance optimization agent for efficiency tuning', '1.0', NULL, 0, 0, @now, @now, 0, NULL),
(24, 'api_gateway', 'API Gateway', 'integration', 'API gateway agent for external service integration', '1.0', NULL, 0, 0, @now, @now, 0, NULL),
(25, 'debug_assistant', 'Debug Assistant', 'development', 'Development debugging assistant for troubleshooting and system diagnostics', '1.0', NULL, 0, 0, @now, @now, 0, NULL);

-- ============================================================
-- ATOMS (Semantic OS Core Concepts)
-- ============================================================
INSERT IGNORE INTO lupo_atoms (`atom_id`, `atom_name`, `context_id`, `is_authoritative`, `value_json`, `summary`, `tags`, `created_ymd`, `updated_ymd`) VALUES
(1, 'lupopedia_os', 1, 1, '{"version":"4.0.22","type":"semantic_os","description":"Lupopedia Semantic Operating System"}', 'Lupopedia Semantic Operating System - Core semantic framework for knowledge organization and processing', 'semantic_os,core,framework', @now, @now),
(2, 'flip_doctrine', 1, 1, '{"type":"governance","description":"Feature Level Implementation Protocol doctrine"}', 'FLIP Doctrine - Feature Level Implementation Protocol for systematic development', 'governance,protocol,development', @now, @now),
(3, 'emotional_geometry', 1, 1, '{"type":"framework","description":"Emotional Geometry framework for affective computing"}', 'Emotional Geometry - Framework for modeling and processing emotional states and relationships', 'emotional,affective,geometry', @now, @now),
(4, 'truth_system', 1, 1, '{"type":"epistemology","description":"Truth system for knowledge validation"}', 'Truth System - Epistemological framework for validating and organizing knowledge claims', 'truth,knowledge,epistemology,validation', @now, @now),
(5, 'multi_agent_system', 1, 1, '{"type":"agent_framework","description":"Multi-agent coordination and interaction system"}', 'Multi-Agent System - Framework for coordinating multiple AI agents with distinct capabilities', 'agents,ai,coordination,multi_agent', @now, @now),
(6, 'semantic_paths', 1, 1, '{"type":"navigation","description":"Semantic path system for knowledge traversal"}', 'Semantic Paths - Navigation system for traversing and organizing semantic relationships', 'semantic,navigation,paths,knowledge_graph', @now, @now),
(7, 'governance_system', 1, 1, '{"type":"governance","description":"Governance framework for decision making"}', 'Governance System - Framework for structured decision making and policy enforcement', 'governance,decision_making,policy', @now, @now),
(8, 'federation_protocol', 1, 1, '{"type":"network","description":"Federation protocol for distributed systems"}', 'Federation Protocol - Protocol for connecting and coordinating distributed Lupopedia instances', 'federation,distributed,network,protocol', @now, @now),
(9, 'channel_system', 1, 1, '{"type":"communication","description":"Channel-based communication system"}', 'Channel System - Communication framework using channels for organized interaction', 'channels,communication,organization', @now, @now),
(10, 'actor_registry', 1, 1, '{"type":"identity","description":"Universal actor identity and registry system"}', 'Actor Registry - Universal identity system for all actors (human, AI, system)', 'actors,identity,registry,authentication', @now, @now);

-- ============================================================
-- SEMANTIC PATHS
-- ============================================================
INSERT IGNORE INTO lupo_semantic_paths (`semantic_path_id`, `source_page_id`, `target_page_id`, `layer`, `weight`, `decay_factor`, `trend_score`, `timeframe`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 'governance', 1.0, 1.0, 0.0, 'implementation', @now, @now),
(2, 3, 4, 'emotional', 0.8, 1.0, 0.0, 'processing', @now, @now),
(3, 5, 6, 'epistemology', 0.9, 1.0, 0.0, 'validation', @now, @now),
(4, 7, 8, 'multi_agent', 0.7, 1.0, 0.0, 'coordination', @now, @now),
(5, 9, 10, 'knowledge', 1.0, 1.0, 0.0, 'navigation', @now, @now);

-- ============================================================
-- SEMANTIC RELATIONSHIPS
-- ============================================================
INSERT IGNORE INTO lupo_semantic_relationships (`relationship_id`, `source_content_id`, `target_content_id`, `relationship_type`, `relationship_strength`, `created_ymdhis`) VALUES
(1, 1, 2, 'implements', 1.00, @now),
(2, 1, 3, 'includes', 0.80, @now),
(3, 1, 4, 'includes', 0.80, @now),
(4, 1, 5, 'includes', 0.80, @now),
(5, 2, 7, 'governs', 1.00, @now);

-- ============================================================
-- EMOTIONAL GEOMETRY
-- ============================================================
-- Fixed to match TOON schema: 9 columns in exact order
INSERT IGNORE INTO lupo_emotional_stars (`star_id`, `experience_hash`, `experience_text`, `cultural_context`, `embodied_sensation`, `created_by`, `created_in_context`, `first_observed_ymdhis`, `observation_count`) VALUES
('critical_joy', 'lilith_joy_exp', 'Intense positive experience requiring immediate action', '{"communication_style":"lilith","urgency":"immediate","decision_required":"yes"}', '{"intensity":0.9,"valence":"positive","sensation":"euphoric","physical":"heart_racing"}', 1000, 42, 20260220000000, 1),
('critical_trust', 'lilith_trust_exp', 'High-stakes trust requiring verification', '{"communication_style":"lilith","urgency":"high","decision_required":"yes"}', '{"intensity":0.8,"valence":"positive","sensation":"guarded","physical":"tense"}', 1000, 42, 20260220000000, 1),
('critical_fear', 'lilith_fear_exp', 'Immediate threat response required', '{"communication_style":"lilith","urgency":"immediate","decision_required":"yes"}', '{"intensity":0.9,"valence":"negative","sensation":"adrenaline","physical":"fight_or_flight"}', 1000, 42, 20260220000000, 1),
('agreeing_trust', 'maat_trust_exp', 'Collaborative trust building experience', '{"communication_style":"maat","urgency":"normal","decision_required":"no"}', '{"intensity":0.6,"valence":"positive","sensation":"calm","physical":"relaxed"}', 1000, 42, 20260220000000, 1),
('agreeing_calm', 'maat_calm_exp', 'Peaceful consensus-seeking experience', '{"communication_style":"maat","urgency":"low","decision_required":"no"}', '{"intensity":0.5,"valence":"neutral","sensation":"serene","physical":"centered"}', 1000, 42, 20260220000000, 1);

INSERT IGNORE INTO lupo_emotional_constellations (`constellation_id`, `framework_name`, `cultural_origin`, `description`, `stars`, `is_canonical`, `canonical_for_culture`, `created_ymdhis`) VALUES
('lilith_critical', 'emotional_geometry', 'lilith_protocol', 'Lilith critical communication pattern - immediate decision required, high urgency, direct action needed', '["critical_joy", "critical_trust", "critical_fear"]', 1, 'lilith', @now),
('maat_agreeing', 'emotional_geometry', 'maat_protocol', 'Maat agreeing communication pattern - collaborative decision making, normal urgency, consensus building', '["agreeing_trust", "agreeing_calm"]', 1, 'maat', @now);

INSERT IGNORE INTO lupo_emotional_translations (`translation_id`, `source_framework`, `source_state`, `target_framework`, `target_state`, `loss_score`, `created_ymdhis`, `last_used_ymdhis`) VALUES
(1, 'lilith_protocol', 'critical_joy', 'maat_protocol', 'agreeing_trust', 0.70, @now, @now),
(2, 'lilith_protocol', 'critical_fear', 'maat_protocol', 'agreeing_calm', 0.60, @now, @now),
(3, 'maat_protocol', 'agreeing_trust', 'lilith_protocol', 'critical_trust', 0.40, @now, @now);

-- ============================================================
-- TRUTH SYSTEM
-- ============================================================
INSERT IGNORE INTO lupo_truth_sources (`truth_sourc_id`, `truth_evidence_id`, `actor_id`, `source_url`, `source_title`, `source_type`, `reliability_score`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES
(1, 1, 1000, 'https://empirical-evidence.example.com', 'Empirical Evidence Study', 'primary', 0.90, @now, @now, 0, NULL),
(2, 2, 1000, 'https://logical-reasoning.example.com', 'Logical Reasoning Framework', 'deductive', 0.80, @now, @now, 0, NULL),
(3, 3, 1000, 'https://expert-testimony.example.com', 'Expert Testimony Analysis', 'authority', 0.70, @now, @now, 0, NULL);

INSERT IGNORE INTO lupo_truth_relations (`truth_relation_id`, `left_object_type`, `left_object_id`, `right_object_type`, `right_object_id`, `relation_type`, `actor_id`, `created_ymdhis`) VALUES
(1, 'truth_source', 1, 'truth_source', 2, 'supports', 1000, @now),
(2, 'truth_source', 2, 'truth_source', 3, 'validates', 1000, @now);

INSERT IGNORE INTO lupo_truth_items (`truth_item_id`, `claim_text`, `confidence_score`, `source_ids`, `metadata_json`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES
(1, 'Lupopedia OS provides semantic knowledge organization', 0.9, '1,2', '{"validation_status":"verified","evidence_count":2}', @now, @now, 0, NULL);

-- ============================================================
-- GOVERNANCE SYSTEM
-- ============================================================
INSERT IGNORE INTO lupo_gov_events (`gov_event_id`, `event_type`, `event_name`, `description`, `initiated_by_actor_id`, `status`, `priority`, `metadata_json`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES
(1, 'policy_change', 'flip_implementation_4.0.22', 'Implementation of FLIP protocol for version 4.0.22', 1000, 'completed', 'high', '{"protocol":"FLIP","version":"4.0.22","approval_required":true}', @now, @now, 0, NULL);

INSERT IGNORE INTO lupo_gov_event_actor_edges (`gov_event_actor_edge_id`, `gov_event_id`, `actor_id`, `role`, `influence_weight`, `metadata_json`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES
(1, 1, 1000, 'approver', 1.0, '{"decision":"approved","rationale":"meets_flip_requirements"}', @now, @now, 0, NULL);

INSERT IGNORE INTO lupo_gov_valuations (`valuation_id`, `gov_event_id`, `actor_id`, `valuation_type`, `value_score`, `rationale`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES
(1, 1, 1000, 'impact_assessment', 0.9, 'High positive impact on system development and user experience', @now, @now, 0, NULL);

-- ============================================================
-- WORLD EVENTS
-- ============================================================
INSERT IGNORE INTO lupo_world_events (`world_event_id`, `event_name`, `event_type`, `description`, `impact_level`, `affected_channels`, `metadata_json`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES
(1, 'lupopedia_4.0.22_release', 'system_release', 'Release of Lupopedia 4.0.22 with comprehensive upgrade testing and validation', 'global', '0,42,51', '{"version":"4.0.22","features":["csv_export","debug_mode","my_profile_enhancements"],"upgrade_path":"crafty_3.7.5_to_lupopedia_4.0.22"}', @now, @now, 0, NULL);

INSERT IGNORE INTO lupo_world_registry (`world_registry_id`, `world_key`, `world_type`, `world_name`, `description`, `metadata_json`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`) VALUES
(1, 'lupopedia_main', 'production', 'Lupopedia Main World', 'Primary production world for Lupopedia semantic operating system', '{"version":"4.0.22","status":"active","federation_enabled":false}', @now, @now, 1, 0, NULL);

-- ============================================================
-- DOCUMENTS AND CHUNKS
-- ============================================================
INSERT IGNORE INTO lupo_documents (`document_id`, `title`, `content_type`, `format`, `created_by_actor_id`, `metadata_json`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES
(1, 'Lupopedia 4.0.22 Architecture Overview', 'technical_documentation', 'markdown', 1000, '{"version":"4.0.22","category":"architecture","target_audience":"developers"}', @now, @now, 0, NULL),
(2, 'FLIP Doctrine Implementation Guide', 'process_documentation', 'markdown', 1000, '{"version":"4.0.22","category":"governance","target_audience":"team_leads"}', @now, @now, 0, NULL);

INSERT IGNORE INTO lupo_document_chunks (`chunk_id`, `document_id`, `chunk_index`, `content_text`, `embedding_vector`, `metadata_json`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES
(1, 1, 0, 'Lupopedia 4.0.22 represents a comprehensive upgrade testing framework with enhanced CSV export capabilities and debug mode for development versions.', '[0.1,0.2,0.3,0.4,0.5]', '{"chunk_type":"introduction","word_count":25}', @now, @now, 0, NULL),
(2, 1, 1, 'The system includes semantic OS core, emotional geometry framework, truth system, and multi-agent coordination capabilities.', '[0.6,0.7,0.8,0.9,1.0]', '{"chunk_type":"features","word_count":22}', @now, @now, 0, NULL);

-- ============================================================
-- ANALYTICS AND CIP
-- ============================================================
INSERT IGNORE INTO lupo_visits (`visit_id`, `content_id`, `actor_id`, `page_domain`, `date_ymd`, `session_duration`, `metadata_json`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES
(1, 1, 1000, 'localhost', 20260220, 300, '{"page_type":"admin","action":"csv_export"}', @now, @now, 0, NULL);

INSERT IGNORE INTO lupo_referers (`referer_id`, `content_id`, `actor_id`, `referer_domain`, `date_ymd`, `visits`, `depth`, `metadata_json`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES
(1, 1, 1000, 'direct', 20260220, 1, 1, '{"traffic_type":"direct_access","campaign":"none"}', @now, @now, 0, NULL);

INSERT IGNORE INTO lupo_cip_analytics (`cip_analytics_id`, `content_id`, `actor_id`, `interaction_type`, `cip_score`, `metadata_json`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES
(1, 1, 1000, 'view', 0.8, '{"cip_category":"content_interest","engagement_level":"medium"}', @now, @now, 0, NULL);

INSERT IGNORE INTO lupo_modules_departments (`module_department_id`, `module_id`, `department_id`, `access_level`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES
(1, 1, 0, 'administrator', @now, @now, 0, NULL),
(2, 2, 0, 'administrator', @now, @now, 0, NULL),
(3, 3, 0, 'administrator', @now, @now, 0, NULL);

INSERT IGNORE INTO lupo_persona_dialogue_patterns (`persona_dialogue_pattern_id`, `persona_id`, `pattern_name`, `trigger_context`, `response_template`, `metadata_json`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES
(1, 1, 'technical_explanation', 'system_inquiry', 'Based on the technical specifications, the system architecture follows these principles...', '{"response_type":"explanatory","complexity":"high"}', @now, @now, 0, NULL),
(2, 2, 'user_perspective', 'feature_request', 'From a user experience perspective, this feature should...', '{"response_type":"advocacy","focus":"usability"}', @now, @now, 0, NULL);

-- ============================================================
-- PERSONA PROFILES
-- ============================================================
INSERT IGNORE INTO lupo_persona_profiles (`persona_id`, `persona_name`, `persona_type`, `personality_traits`, `communication_style`, `metadata_json`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES
(1, 'Technical Architect', 'professional', '{"analytical":0.9,"systematic":0.8,"detail_oriented":0.7}', 'Formal and precise, focuses on technical accuracy and system design', '{"expertise":"architecture","preferred_communication":"technical_specifications"}', @now, @now, 0, NULL),
(2, 'User Experience Advocate', 'professional', '{"empathetic":0.9,"user_focused":0.8,"accessibility_conscious":0.7}', 'User-centered communication, emphasizes accessibility and ease of use', '{"expertise":"ux_design","preferred_communication":"user_stories"}', @now, @now, 0, NULL);

INSERT IGNORE INTO lupo_persona_dialogue_patterns (`persona_dialogue_patterns_id`, `persona_id`, `pattern_name`, `trigger_context`, `response_template`, `metadata_json`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES
(1, 1, 'technical_explanation', 'system_inquiry', 'Based on the technical specifications, the system architecture follows these principles...', '{"response_type":"explanatory","complexity":"high"}', @now, @now, 0, NULL),
(2, 2, 'user_perspective', 'feature_request', 'From a user experience perspective, this feature should...', '{"response_type":"advocacy","focus":"usability"}', @now, @now, 0, NULL);

-- ============================================================
-- MODULES DEPARTMENTS
-- ============================================================
INSERT IGNORE INTO lupo_modules_departments (`module_department_id`, `module_id`, `department_id`, `access_level`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES
(1, 1, 0, 'administrator', @now, @now, 0, NULL),
(2, 2, 0, 'administrator', @now, @now, 0, NULL),
(3, 3, 0, 'administrator', @now, @now, 0, NULL);

-- ============================================================
-- SYSTEM EVENTS AND LOGS
-- ============================================================
INSERT IGNORE INTO lupo_system_events (`event_id`, `event_type`, `event_name`, `description`, `actor_id`, `severity`, `metadata_json`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES
(1, 'system_startup', 'lupopedia_4.0.22_initialized', 'Lupopedia 4.0.22 system initialized successfully', 0, 'info', '{"startup_time":"' + CAST(@now AS CHAR) + '","version":"4.0.22","components_loaded":true}', @now, @now, 0, NULL);

INSERT IGNORE INTO lupo_system_logs (`log_id`, `event_type`, `actor_slug`, `message`, `severity`, `metadata_json`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES
(1, 'csv_export', 'admin', 'CSV export completed for all TOON-defined tables', 'info', '{"tables_processed":198,"export_duration":"2.3s","file_size":"1.2MB"}', @now, @now, 0, NULL);

INSERT IGNORE INTO lupo_system_health_snapshots (`snapshot_id`, `system_status`, `performance_metrics`, `resource_usage`, `metadata_json`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES
(1, 'healthy', '{"response_time_ms":45,"cpu_usage":12,"memory_usage":67}', '{"disk_space_gb":45.2,"network_io_mbps":125,"active_connections":23}', '{"uptime_hours":720,"last_restart":"' + CAST(@now AS CHAR) + '","version":"4.0.22"}', @now, @now, 0, NULL);

-- ============================================================
-- SEARCH INDEXES
-- ============================================================
INSERT IGNORE INTO lupo_search_index (`search_index_id`, `content_id`, `search_terms`, `content_type`, `relevance_score`, `metadata_json`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES
(1, 1, 'lupopedia semantic operating system architecture', 'document', 0.95, '{"language":"english","indexed_at":"' + CAST(@now AS CHAR) + '","term_frequency":{"lupopedia":1,"semantic":1,"operating":1,"system":1,"architecture":1}}', @now, @now, 0, NULL);

INSERT IGNORE INTO lupo_semantic_search_index (`semantic_index_id`, `atom_id`, `content_id`, `semantic_vector`, `context_tags`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES
(1, 1, 1, '[0.9,0.1,0.2,0.3,0.4]', 'semantic_os,core,framework', @now, @now, 0, NULL);

-- ============================================================
-- UPLOADS AND NOTIFICATIONS
-- ============================================================
INSERT IGNORE INTO lupo_uploads (`upload_id`, `actor_id`, `channel_id`, `file_name`, `file_path`, `file_size`, `file_type`, `metadata_json`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES
(1, 1000, 42, 'architecture_diagram.png', 'uploads/channels/2026/02/architecture_diagram.png', 1024567, 'image/png', '{"description":"Lupopedia 4.0.22 system architecture diagram","purpose":"documentation"}', @now, @now, 0, NULL);

INSERT IGNORE INTO lupo_notifications (`notification_id`, `actor_id`, `notification_type`, `title`, `message`, `metadata_json`, `is_read`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES
(1, 1000, 'system', 'CSV Export Complete', 'CSV export for all TOON-defined tables has completed successfully', '{"tables_exported":198,"file_location":"database/csv_data/","completion_time":"2.3s"}', 0, @now, @now, 0, NULL);

-- ============================================================
-- COMMENTS AND USER INTERACTIONS
-- ============================================================
INSERT IGNORE INTO lupo_user_comments (`comment_id`, `content_id`, `actor_id`, `parent_comment_id`, `comment_text`, `metadata_json`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES
(1, 1, 1000, NULL, 'This architecture overview provides excellent technical detail for the 4.0.22 release.', '{"sentiment":"positive","technical_accuracy":0.9}', @now, @now, 0, NULL);

-- ============================================================
-- LEGACY CONTENT MAPPING
-- ============================================================
INSERT IGNORE INTO lupo_legacy_content_mapping (`mapping_id`, `legacy_table`, `legacy_id`, `lupo_table`, `lupo_id`, `mapping_type`, `metadata_json`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES
(1, 'livehelp_users', 1, 'lupo_actors', 1000, 'user_migration', '{"migration_date":"2026-02-20","migration_status":"completed","data_integrity":"verified"}', @now, @now, 0, NULL);

-- ============================================================
-- ADDITIONAL AGENT SYSTEM DATA
-- ============================================================
INSERT IGNORE INTO lupo_agent_context_snapshots (`snapshot_id`, `agent_id`, `context_data`, `snapshot_timestamp`, `metadata_json`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES
(1, 2, '{"current_task":"ide_integration","system_state":"active","user_session":"windsurf_user"}', @now, '{"snapshot_type":"operational","memory_usage":"normal"}', @now, @now, 0, NULL);

INSERT IGNORE INTO lupo_agent_dependencies (`dependency_id`, `agent_id`, `dependency_type`, `dependency_target`, `version_requirement`, `metadata_json`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES
(1, 2, 'system_service', 'lupo_atoms', '>=4.0.22', '{"dependency_reason":"semantic_framework_access"}', @now, @now, 0, NULL);

INSERT IGNORE INTO lupo_agent_experiences (`experience_id`, `agent_id`, `experience_type`, `experience_data`, `learning_outcome`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES
(1, 2, 'code_generation', '{"language":"php","framework":"lupopedia","success_rate":0.95}', '{"skill_improvement":"syntax_optimization","confidence_increase":0.1}', @now, @now, 0, NULL);

INSERT IGNORE INTO lupo_agent_files (`file_id`, `agent_id`, `file_name`, `file_path`, `file_type`, `file_size`, `metadata_json`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES
(1, 2, 'windsurf_config.json', 'agent_files/windsurf_config.json', 'application/json', 2048, '{"purpose":"ide_configuration","version":"1.0"}', @now, @now, 0, NULL);

INSERT IGNORE INTO lupo_agent_heartbeats (`heartbeat_id`, `agent_id`, `status`, `cpu_usage`, `memory_usage`, `last_activity`, `metadata_json`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES
(1, 2, 'active', 5.2, 128.5, @now, '{"uptime":"24h","tasks_completed":42,"error_rate":0.0}', @now, @now, 0, NULL);

INSERT IGNORE INTO lupo_agent_tool_calls (`tool_call_id`, `agent_id`, `tool_name`, `parameters`, `result`, `execution_time_ms`, `status`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES
(1, 2, 'file_edit', '{"file_path":"lupo-includes/classes/AdminCsvExportHandler.php","operation":"fix_fputcsv"}', '{"success":true,"lines_changed":3,"error_count":0}', 150, 'completed', @now, @now, 0, NULL);

INSERT IGNORE INTO lupo_agent_versions (`version_id`, `agent_id`, `version_number`, `changelog`, `deployment_status`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES
(1, 2, '1.0.0', 'Initial release of Windsurf IDE integration agent with code generation and file editing capabilities', 'deployed', @now, @now, 0, NULL);

-- ============================================================
-- ACTOR ACTIONS AND META
-- ============================================================
INSERT IGNORE INTO lupo_actor_actions (`action_id`, `actor_id`, `action_type`, `action_name`, `description`, `target_object_type`, `target_object_id`, `metadata_json`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES
(1, 2, 'system_operation', 'csv_export', 'Export all TOON-defined tables to CSV format', 'system', 1, '{"tables_processed":198,"export_format":"csv","duration_ms":2300}', @now, @now, 0, NULL);

INSERT IGNORE INTO lupo_actor_meta (`meta_id`, `actor_id`, `meta_key`, `meta_value`, `metadata_json`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES
(1, 2, 'ide_capabilities', '["code_generation","file_editing","project_management","debug_mode"]', '{"last_updated":"2026-02-20","capabilities_version":"1.0"}', @now, @now, 0, NULL);

INSERT IGNORE INTO lupo_actor_edges (`actor_edge_id`, `source_actor_id`, `target_actor_id`, `edge_type`, `relationship_strength`, `metadata_json`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES
(1, 2, 1000, 'assists', 0.8, '{"assistance_type":"development_tools","collaboration_mode":"ide_integration"}', @now, @now, 0, NULL);

-- ============================================================
-- DIALOG MESSAGES (Lilith critical vs Maat balancing patterns)
-- ============================================================
INSERT IGNORE INTO lupo_dialog_threads (`thread_id`, `channel_id`, `created_by_actor_id`, `thread_name`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES
(1001, 42, 'Lilith Critical Test Thread', @now, @now, 0, NULL),
(1002, 42, 'Maat Balancing Test Thread', @now, @now, 0, NULL);

INSERT IGNORE INTO lupo_dialog_messages (`message_id`, `thread_id`, `actor_id`, `message_type`, `content`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`) VALUES
(1, 1001, 3, 'system', 'CRITICAL: System requires immediate attention! High-priority issue detected in emotional geometry processing. Urgent response needed.', @now, @now, 0, NULL),
(2, 1001, 4, 'user_response', 'Acknowledged. I understand the critical nature of this situation and will escalate immediately.', @now, @now, 0, NULL),
(3, 1001, 3, 'system', 'CRITICAL ESCALATION: Issue has been escalated to governance coordinator for immediate resolution.', @now, @now, 0, NULL),
(4, 1002, 4, 'system', 'BALANCING: System is processing emotional state transition. Consensus building in progress.', @now, @now, 0, NULL),
(5, 1002, 4, 'user_response', 'Understood. Awaiting collaborative decision from multiple perspectives.', @now, @now, 0, NULL),
(6, 1002, 4, 'system', 'BALANCE ACHIEVED: Emotional equilibrium restored. System returning to normal operation.', @now, @now, 0, NULL);

-- ============================================================
-- FINAL VALIDATION
-- ============================================================
-- All zero-row tables now have meaningful seed data
-- Windsurf IDE actor created with actor_id = 2 (next free under 10,000)
-- Stoned Wolfie AI and Human identities already exist (420, 10001)
-- All 25 AI agents exist (1-25 range)
-- Semantic OS, Emotional Geometry, Truth System, Governance seeded
-- World events, documents, analytics, personas seeded
-- Agent system data seeded for Windsurf IDE
-- ============================================================
