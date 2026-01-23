-- Migration SQL: Drop Crafty Syntax Tables and Add Essential Semantic Tables
-- Version 4.3.4 - Semantic Views Integration for Crafty Syntax
-- Target: Stay under 180 table ceiling
-- Generated: 2026-01-20

-- Current State Analysis:
-- - Total tables: 180 (146 lupo + 34 livehelp)
-- - Target: Under 180 tables
-- - Action: Drop 34 livehelp tables, add 8 essential semantic tables
-- - Result: 154 tables (146 lupo + 8 new semantic)

-- Drop all 34 legacy Crafty Syntax tables after successful migration
DROP TABLE IF EXISTS livehelp_autoinvite;
DROP TABLE IF EXISTS livehelp_channels;
DROP TABLE IF EXISTS livehelp_config;
DROP TABLE IF EXISTS livehelp_departments;
DROP TABLE IF EXISTS livehelp_emailque;
DROP TABLE IF EXISTS livehelp_emails;
DROP TABLE IF EXISTS livehelp_identity_daily;
DROP TABLE IF EXISTS livehelp_identity_monthly;
DROP TABLE IF EXISTS livehelp_keywords_daily;
DROP TABLE IF EXISTS livehelp_keywords_monthly;
DROP TABLE IF EXISTS livehelp_layerinvites;
DROP TABLE IF EXISTS livehelp_leads;
DROP TABLE IF EXISTS livehelp_leavemessage;
DROP TABLE IF EXISTS livehelp_messages;
DROP TABLE IF EXISTS livehelp_modules;
DROP TABLE IF EXISTS livehelp_modules_dep;
DROP TABLE IF EXISTS livehelp_operator_channels;
DROP TABLE IF EXISTS livehelp_operator_departments;
DROP TABLE IF EXISTS livehelp_operator_history;
DROP TABLE IF EXISTS livehelp_paths_firsts;
DROP TABLE IF EXISTS livehelp_paths_monthly;
DROP TABLE IF EXISTS livehelp_qa;
DROP TABLE IF EXISTS livehelp_questions;
DROP TABLE IF EXISTS livehelp_quick;
DROP TABLE IF EXISTS livehelp_referers_daily;
DROP TABLE IF EXISTS livehelp_referers_monthly;
DROP TABLE IF EXISTS livehelp_sessions;
DROP TABLE IF EXISTS livehelp_smilies;
DROP TABLE IF EXISTS livehelp_transcripts;
DROP TABLE IF EXISTS livehelp_users;
DROP TABLE IF EXISTS livehelp_visits_daily;
DROP TABLE IF EXISTS livehelp_visits_monthly;
DROP TABLE IF EXISTS livehelp_visit_track;
DROP TABLE IF EXISTS livehelp_websites;

-- Add only 8 essential semantic view tables for v4.3.4
-- These tables provide core semantic navigation and content views for Crafty Syntax users

-- 1. Legacy Content Mapping Table - Maps legacy URLs to semantic URLs
CREATE TABLE lupo_legacy_content_mapping (
    mapping_id BIGINT NOT NULL AUTO_INCREMENT COMMENT 'Primary key for content mapping',
    legacy_url VARCHAR(2048) NOT NULL COMMENT 'Original Crafty Syntax URL',
    semantic_url VARCHAR(2048) NOT NULL COMMENT 'New semantic URL',
    content_type ENUM('page','topic','collection') NOT NULL COMMENT 'Type of content',
    content_id BIGINT NULL COMMENT 'Reference to semantic content',
    created_ymdhis BIGINT NOT NULL COMMENT 'Mapping creation timestamp',
    updated_ymdhis BIGINT NOT NULL COMMENT 'Mapping update timestamp',
    is_active TINYINT NOT NULL DEFAULT 1 COMMENT 'Mapping active flag',
    PRIMARY KEY (mapping_id),
    UNIQUE KEY uk_legacy_url (legacy_url),
    KEY idx_semantic_url (semantic_url),
    KEY idx_content_type (content_type),
    KEY idx_content_id (content_id),
    KEY idx_is_active (is_active),
    KEY idx_created (created_ymdhis),
    KEY idx_created_ymdhis (created_ymdhis, is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Maps legacy Crafty Syntax URLs to new semantic URLs for seamless migration';

-- 2. Semantic Content Views Table - Defines different view types
CREATE TABLE lupo_semantic_content_views (
    semantic_view_id BIGINT NOT NULL AUTO_INCREMENT COMMENT 'Primary key for semantic content view',
    view_name VARCHAR(255) NOT NULL COMMENT 'View name identifier',
    view_type ENUM('navigation','content','search','collection') NOT NULL COMMENT 'Type of semantic view',
    title VARCHAR(255) NOT NULL COMMENT 'View title',
    description TEXT COMMENT 'View description',
    template_path VARCHAR(512) NOT NULL COMMENT 'Template file path',
    created_ymdhis BIGINT NOT NULL COMMENT 'Creation timestamp',
    updated_ymdhis BIGINT NOT NULL COMMENT 'Last update timestamp',
    is_active TINYINT NOT NULL DEFAULT 1 COMMENT 'View active flag',
    is_default TINYINT NOT NULL DEFAULT 0 COMMENT 'Default view flag',
    PRIMARY KEY (semantic_view_id),
    UNIQUE KEY uk_view_name (view_name),
    KEY idx_view_type (view_type),
    KEY idx_is_active (is_active),
    KEY idx_is_default (is_default),
    KEY idx_created (created_ymdhis),
    KEY idx_created_ymdhis (created_ymdhis, is_active),
    KEY idx_created_ymdhis (created_ymdhis, is_default, is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Defines different types of semantic views for Crafty Syntax content';

-- 3. Semantic Categories Table - Content categorization system
CREATE TABLE lupo_semantic_categories (
    category_id BIGINT NOT NULL AUTO_INCREMENT COMMENT 'Primary key for semantic category',
    category_name VARCHAR(255) NOT NULL COMMENT 'Category name',
    category_slug VARCHAR(255) NOT NULL COMMENT 'URL-friendly category slug',
    description TEXT COMMENT 'Category description',
    parent_category_id BIGINT NULL COMMENT 'Parent category ID',
    sort_order INT NOT NULL DEFAULT 0 COMMENT 'Display order',
    created_ymdhis BIGINT NOT NULL COMMENT 'Creation timestamp',
    updated_ymdhis BIGINT NOT NULL COMMENT 'Last update timestamp',
    is_active TINYINT NOT NULL DEFAULT 1 COMMENT 'Category active flag',
    PRIMARY KEY (category_id),
    UNIQUE KEY uk_category_slug (category_slug),
    KEY idx_parent_category (parent_category_id),
    KEY idx_sort_order (sort_order),
    KEY idx_is_active (is_active),
    KEY idx_created (created_ymdhis),
    KEY idx_created_ymdhis (created_ymdhis, is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Categories for organizing semantic content';

-- 4. Semantic Tags Table - Tag system for content categorization
CREATE TABLE lupo_semantic_tags (
    tag_id BIGINT NOT NULL AUTO_INCREMENT COMMENT 'Primary key for semantic tag',
    tag_name VARCHAR(255) NOT NULL COMMENT 'Tag name',
    tag_slug VARCHAR(255) NOT NULL COMMENT 'URL-friendly tag slug',
    description TEXT COMMENT 'Tag description',
    color VARCHAR(7) NOT NULL DEFAULT '#666666' COMMENT 'Tag color hex',
    created_ymdhis BIGINT NOT NULL COMMENT 'Creation timestamp',
    updated_ymdhis BIGINT NOT NULL COMMENT 'Last update timestamp',
    is_active TINYINT NOT NULL DEFAULT 1 COMMENT 'Tag active flag',
    PRIMARY KEY (tag_id),
    UNIQUE KEY uk_tag_slug (tag_slug),
    KEY idx_is_active (is_active),
    KEY idx_created (created_ymdhis),
    KEY idx_created_ymdhis (created_ymdhis, is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tags for categorizing semantic content';

-- 5. Content-Tag Relationships Table - Many-to-many relationships
CREATE TABLE lupo_content_tag_relationships (
    relationship_id BIGINT NOT NULL AUTO_INCREMENT COMMENT 'Primary key for content-tag relationship',
    content_id BIGINT NOT NULL COMMENT 'Reference to content table',
    tag_id BIGINT NOT NULL COMMENT 'Reference to tag table',
    relationship_type ENUM('category','topic','label') NOT NULL COMMENT 'Type of relationship',
    created_ymdhis BIGINT NOT NULL COMMENT 'Creation timestamp',
    PRIMARY KEY (relationship_id),
    KEY idx_content_id (content_id),
    KEY idx_tag_id (tag_id),
    KEY idx_relationship_type (relationship_type),
    KEY idx_created (created_ymdhis),
    KEY idx_created_ymdhis (created_ymdhis, relationship_type, content_id, tag_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Many-to-many relationships between content and tags';

-- 6. Semantic Relationships Table - Relationships between content items
CREATE TABLE lupo_semantic_relationships (
    relationship_id BIGINT NOT NULL AUTO_INCREMENT COMMENT 'Primary key for semantic relationship',
    source_content_id BIGINT NOT NULL COMMENT 'Source content ID',
    target_content_id BIGINT NULL COMMENT 'Target content ID',
    relationship_type ENUM('related','series','hierarchy') NOT NULL COMMENT 'Type of semantic relationship',
    relationship_strength DECIMAL(3,2) NOT NULL DEFAULT 1.0 COMMENT 'Relationship strength (0.0-1.0)',
    created_ymdhis BIGINT NOT NULL COMMENT 'Creation timestamp',
    PRIMARY KEY (relationship_id),
    KEY idx_source_content (source_content_id),
    KEY idx_target_content (target_content_id),
    KEY idx_relationship_type (relationship_type),
    KEY idx_created (created_ymdhis),
    KEY idx_created_ymdhis (created_ymdhis, relationship_type, source_content_id, target_content_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Semantic relationships between content items';

-- 7. Semantic Search Index Table - Search indexes for content discovery
CREATE TABLE lupo_semantic_search_index (
    search_index_id BIGINT NOT NULL AUTO_INCREMENT COMMENT 'Primary key for search index',
    index_name VARCHAR(255) NOT NULL COMMENT 'Search index name',
    index_type ENUM('content','legacy_mapping','views') NOT NULL COMMENT 'Type of search index',
    description TEXT COMMENT 'Search index description',
    index_data JSON NOT NULL COMMENT 'Search index data',
    created_ymdhis BIGINT NOT NULL COMMENT 'Creation timestamp',
    updated_ymdhis BIGINT NOT NULL COMMENT 'Last update timestamp',
    is_active TINYINT NOT NULL DEFAULT 1 COMMENT 'Search index active flag',
    PRIMARY KEY (search_index_id),
    UNIQUE KEY uk_index_name (index_name),
    KEY idx_index_type (index_type),
    KEY idx_is_active (is_active),
    KEY idx_created (created_ymdhis),
    KEY idx_created_ymdhis (created_ymdhis, is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Search indexes for semantic content discovery';

-- 8. Semantic Navigation Overview Table - Complete site structure
CREATE TABLE lupo_semantic_navigation_overview (
    navigation_id BIGINT NOT NULL AUTO_INCREMENT COMMENT 'Primary key for semantic navigation',
    title VARCHAR(255) NOT NULL COMMENT 'Navigation title',
    description TEXT COMMENT 'Navigation description',
    navigation_tree JSON NOT NULL COMMENT 'Hierarchical navigation structure',
    content_categories JSON NOT NULL COMMENT 'Content categories included',
    created_ymdhis BIGINT NOT NULL COMMENT 'Creation timestamp',
    updated_ymdhis BIGINT NOT NULL COMMENT 'Last update timestamp',
    is_deleted TINYINT NOT NULL DEFAULT 0 COMMENT 'Soft delete flag',
    deleted_ymdhis BIGINT NULL COMMENT 'Deletion timestamp',
    PRIMARY KEY (navigation_id),
    KEY idx_created (created_ymdhis),
    KEY idx_is_deleted (is_deleted),
    KEY idx_created_ymdhis (created_ymdhis, is_deleted)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Semantic Navigation Overview - Provides complete site structure with semantic relationships for Crafty Syntax users';

-- Insert initial semantic view data
INSERT INTO lupo_semantic_content_views (view_name, view_type, title, description, template_path, created_ymdhis, updated_ymdhis, is_active, is_default) VALUES
('navigation_overview', 'navigation', 'Semantic Navigation Overview', 'Complete site structure with semantic relationships', 'semantic/navigation-overview', 20260120000000, 20260120000000, 1, 1),
('content_view', 'content', 'Semantic Content View', 'Full semantic content rendering for legacy pages', 'semantic/content-view', 20260120000000, 20260120000000, 1, 0),
('search_view', 'search', 'Semantic Search View', 'Search interface for semantic content discovery', 'semantic/search-view', 20260120000000, 20260120000000, 1, 0),
('collection_view', 'collection', 'Semantic Collection View', 'Organized content collections with semantic metadata', 'semantic/collection-view', 20260120000000, 20260120000000, 1, 0);

-- Insert initial semantic categories
INSERT INTO lupo_semantic_categories (category_name, category_slug, description, parent_category_id, sort_order, created_ymdhis, updated_ymdhis, is_active) VALUES
('navigation', 'navigation', 'Navigation Structure', NULL, 0, 20260120000000, 20260120000000, 1),
('content', 'content', 'Content Types', NULL, 1, 20260120000000, 20260120000000, 1),
('collections', 'collections', 'Content Collections', NULL, 2, 20260120000000, 20260120000000, 1),
('help', 'help', 'Help Documentation', NULL, 3, 20260120000000, 20260120000000, 1),
('system', 'system', 'System Content', NULL, 4, 20260120000000, 20260120000000, 1);

-- Insert initial semantic tags
INSERT INTO lupo_semantic_tags (tag_name, tag_slug, description, color, created_ymdhis, updated_ymdhis, is_active) VALUES
('navigation', 'navigation', 'Navigation elements and structure', '#0066cc', 20260120000000, 20260120000000, 1),
('content-type', 'content-type', 'Content type classifications', '#ff9800', 20260120000000, 20260120000000, 1),
('legacy-url', 'legacy-url', 'Legacy Crafty Syntax URL patterns', '#ff5722', 20260120000000, 20260120000000, 1),
('semantic-view', 'semantic-view', 'Semantic view types', '#2196f3', 20260120000000, 20260120000000, 1),
('collection', 'collection', 'Collection categories', '#4caf50', 20260120000000, 20260120000000, 1),
('help', 'help', 'Help content', '#03a9f4', 20260120000000, 20260120000000, 1),
('system', 'system', 'System functionality', '#f44336', 20260120000000, 20260120000000, 1);

-- Insert initial search index
INSERT INTO lupo_semantic_search_index (index_name, index_type, description, index_data, created_ymdhis, updated_ymdhis, is_active) VALUES
('content_index', 'content', 'Index of all semantic content', '{"tables": ["lupo_contents", "lupo_dialog_threads", "lupo_collections", "lupo_help_topics"]}', 20260120000000, 20260120000000, 1),
('legacy_mapping', 'legacy_mapping', 'Legacy URL to semantic URL mappings', '{"total_mappings": 0, "mappings": []}', 20260120000000, 20260120000000, 1),
('views_index', 'views', 'Available semantic views', '["navigation_overview", "content_view", "search_view", "collection_view"]}', 20260120000000, 20260120000000, 1);

-- Migration Summary:
-- Tables before: 180 (146 lupo + 34 livehelp)
-- Tables dropped: 34 (all livehelp tables)
-- Tables added: 8 (essential semantic tables)
-- Tables after: 154 (146 lupo + 8 semantic)
-- Table ceiling: 180 (26 tables under ceiling)
-- Compliance: TABLE_COUNT_DOCTRINE satisfied
