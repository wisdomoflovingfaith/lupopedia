-- Migration: Create GOV Event Schema
-- Version: 4.2.2
-- Date: 2026-01-20
-- Module: Governance Events
--
-- Creates tables for Captain Wolfie gov_event system
-- Follows Lupopedia naming conventions and doctrine
--
-- @package Lupopedia
-- @version 4.2.2
-- @author Captain Wolfie

-- ============================================================================
-- TABLE: lupo_gov_events
-- ============================================================================
-- Main governance event storage for Captain Wolfie directives
CREATE TABLE `lupo_gov_events` (
    `gov_event_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for governance event',
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
    UNIQUE KEY `unique_canonical_path` (`canonical_path`),
    KEY `idx_utc_group` (`utc_group_id`),
    KEY `idx_semantic_version` (`semantic_utc_version`),
    KEY `idx_event_type` (`event_type`),
    KEY `idx_created_ymdhis` (`created_ymdhis`),
    KEY `idx_is_active` (`is_active`),
    KEY `idx_is_deleted` (`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Governance events for Captain Wolfie directives';

-- ============================================================================
-- TABLE: lupo_gov_event_references
-- ============================================================================
-- Reference entries for governance events
CREATE TABLE `lupo_gov_event_references` (
    `reference_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for reference',
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
    KEY `idx_gov_event` (`gov_event_id`),
    KEY `idx_reference_type` (`reference_type`),
    KEY `idx_order_sequence` (`order_sequence`),
    KEY `idx_created_ymdhis` (`created_ymdhis`),
    KEY `idx_is_deleted` (`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='References for governance events';

-- ============================================================================
-- TABLE: lupo_gov_timeline_nodes
-- ============================================================================
-- Timeline node management for governance events
CREATE TABLE `lupo_gov_timeline_nodes` (
    `timeline_node_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for timeline node',
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
    KEY `idx_gov_event` (`gov_event_id`),
    KEY `idx_node_type` (`node_type`),
    KEY `idx_node_timestamp` (`node_timestamp`),
    KEY `idx_parent_node` (`parent_node_id`),
    KEY `idx_order_sequence` (`order_sequence`),
    KEY `idx_created_ymdhis` (`created_ymdhis`),
    KEY `idx_is_deleted` (`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Timeline nodes for governance events';

-- ============================================================================
-- TABLE: lupo_gov_valuations
-- ============================================================================
-- Valuation blocks for governance events
CREATE TABLE `lupo_gov_valuations` (
    `valuation_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for valuation',
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
    KEY `idx_gov_event` (`gov_event_id`),
    KEY `idx_valuation_type` (`valuation_type`),
    KEY `idx_valuation_metric` (`valuation_metric`),
    KEY `idx_created_ymdhis` (`created_ymdhis`),
    KEY `idx_is_deleted` (`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Valuations for governance events';

-- ============================================================================
-- TABLE: lupo_gov_event_actor_edges
-- ============================================================================
-- App-managed relationships between gov events and actors
CREATE TABLE `lupo_gov_event_actor_edges` (
    `edge_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for edge',
    `gov_event_id` bigint NOT NULL COMMENT 'Governance event',
    `actor_id` bigint NOT NULL COMMENT 'Actor',
    `edge_type` varchar(100) NOT NULL COMMENT 'Type of relationship',
    `edge_properties` text COMMENT 'JSON or TOON formatted metadata',
    `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when created',
    `updated_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when last updated',
    `is_deleted` tinyint NOT NULL DEFAULT 0 COMMENT 'Soft delete flag',
    `deleted_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS when deleted',
    PRIMARY KEY (`edge_id`),
    UNIQUE KEY `unique_gov_event_actor_edge` (`gov_event_id`, `actor_id`, `edge_type`),
    KEY `idx_gov_event` (`gov_event_id`),
    KEY `idx_actor` (`actor_id`),
    KEY `idx_edge_type` (`edge_type`),
    KEY `idx_created_ymdhis` (`created_ymdhis`),
    KEY `idx_is_deleted` (`is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Actor relationships for governance events';

-- ============================================================================
-- VALIDATION QUERIES
-- ============================================================================
-- Verify tables were created correctly
SELECT 'lupo_gov_events' as table_name, COUNT(*) as row_count FROM `lupo_gov_events`;
SELECT 'lupo_gov_event_references' as table_name, COUNT(*) as row_count FROM `lupo_gov_event_references`;
SELECT 'lupo_gov_timeline_nodes' as table_name, COUNT(*) as row_count FROM `lupo_gov_timeline_nodes`;
SELECT 'lupo_gov_valuations' as table_name, COUNT(*) as row_count FROM `lupo_gov_valuations`;
SELECT 'lupo_gov_event_actor_edges' as table_name, COUNT(*) as row_count FROM `lupo_gov_event_actor_edges`;
