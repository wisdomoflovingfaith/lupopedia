-- ======================================================================
-- Recreate Missing lupo_collection_tabs + Restore Collection 3 Tabs
-- Migration for Lupopedia 4.2.3
-- Created: 2026-01-20
-- Based on actual TOON file schema
-- ======================================================================

-- Drop table if it exists to ensure clean recreation
DROP TABLE IF EXISTS `lupo_collection_tabs`;

-- Recreate lupo_collection_tabs table with exact TOON schema
CREATE TABLE IF NOT EXISTS `lupo_collection_tabs` (
    `collection_tab_id` bigint NOT NULL AUTO_INCREMENT,
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
    KEY `idx_parent_tab_id` (`collection_tab_parent_id`),
    KEY `idx_slug` (`slug`),
    KEY `idx_is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert root-level tabs for Collection ID = 3 from TOON data
INSERT IGNORE INTO `lupo_collection_tabs`
(`collection_tab_id`, `collection_tab_parent_id`, `collection_id`, `federations_node_id`, `group_id`, `user_id`, `sort_order`, `name`, `slug`, `color`, `description`, `is_hidden`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `deleted_ymdhis`)
VALUES
(1, NULL, 3, 1, NULL, NULL, 1, 'Overview', 'overview', '4caf50', 'Overview of the Lupopedia system', 0, 20260120000000, 20260120000000, 1, 0, NULL),
(2, NULL, 3, 1, NULL, NULL, 2, 'Doctrine', 'doctrine', '4caf50', 'System doctrine and principles', 0, 20260120000000, 20260120000000, 1, 0, NULL),
(3, NULL, 3, 1, NULL, NULL, 3, 'Architecture', 'architecture', '4caf50', 'System architecture documentation', 0, 20260120000000, 20260120000000, 1, 0, NULL),
(4, NULL, 3, 1, NULL, NULL, 4, 'Schema', 'schema', '4caf50', 'Database schema documentation', 0, 20260120000000, 20260120000000, 1, 0, NULL),
(5, NULL, 3, 1, NULL, NULL, 5, 'Agents', 'agents', '4caf50', 'AI agents and automation', 0, 20260120000000, 20260120000000, 1, 0, NULL),
(6, NULL, 3, 1, NULL, NULL, 6, 'UI-UX', 'ui-ux', '4caf50', 'User interface and experience', 0, 20260120000000, 20260120000000, 1, 0, NULL),
(7, NULL, 3, 1, NULL, NULL, 7, 'Developer Guide', 'developer-guide', '4caf50', 'Developer documentation and guides', 0, 20260120000000, 20260120000000, 1, 0, NULL),
(8, NULL, 3, 1, NULL, NULL, 8, 'History', 'history', '4caf50', 'Historical documentation', 0, 20260120000000, 20260120000000, 1, 0, NULL),
(9, NULL, 3, 1, NULL, NULL, 9, 'Appendix', 'appendix', '4caf50', 'Additional reference materials', 0, 20260120000000, 20260120000000, 1, 0, NULL);

-- Create lupo_collection_tab_paths table with correct TOON schema
CREATE TABLE IF NOT EXISTS `lupo_collection_tab_paths` (
    `collection_tab_path_id` bigint NOT NULL AUTO_INCREMENT,
    `collection_id` bigint NOT NULL,
    `collection_tab_id` bigint NOT NULL,
    `path` varchar(500) NOT NULL COMMENT 'Full tab path: departments/parks-and-recreation/summer-programs',
    `depth` int NOT NULL COMMENT 'Number of levels (1 = root tab)',
    `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS',
    `updated_ymdhis` bigint COMMENT 'UTC YYYYMMDDHHMMSS',
    `is_deleted` tinyint NOT NULL DEFAULT 0,
    `deleted_ymdhis` bigint,
    PRIMARY KEY (`collection_tab_path_id`),
    UNIQUE KEY `unique_tab_path` (`collection_id`, `collection_tab_id`, `path`),
    KEY `idx_collection` (`collection_id`),
    KEY `idx_collection_tab` (`collection_tab_id`),
    KEY `idx_path` (`path`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert tab paths for Collection ID = 3 (root level tabs only)
INSERT IGNORE INTO `lupo_collection_tab_paths`
(`collection_id`, `collection_tab_id`, `path`, `depth`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`)
VALUES
(3, 2, 'doctrine', 1, 20260120000000, 20260120000000, 0),
(3, 5, 'agents', 1, 20260120000000, 20260120000000, 0),
(3, 3, 'architecture', 1, 20260120000000, 20260120000000, 0),
(3, 4, 'schema', 1, 20260120000000, 20260120000000, 0),
(3, 8, 'history', 1, 20260120000000, 20260120000000, 0),
(3, 9, 'appendix', 1, 20260120000000, 20260120000000, 0);

-- Create lupo_collection_tab_map table with correct TOON schema
CREATE TABLE IF NOT EXISTS `lupo_collection_tab_map` (
    `collection_tab_map_id` bigint NOT NULL AUTO_INCREMENT,
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
    KEY `idx_collection_tab_id` (`collection_tab_id`),
    KEY `idx_item_type_id` (`item_type`, `item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert tab map entries for Collection ID = 3
INSERT IGNORE INTO `lupo_collection_tab_map`
(`collection_tab_id`, `federations_node_id`, `item_type`, `item_id`, `sort_order`, `properties`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`)
VALUES
(1, 1, 'tab', 1, 1, '{}', 20260120000000, 20260120000000, 0),
(2, 1, 'tab', 2, 1, '{}', 20260120000000, 20260120000000, 0),
(3, 1, 'tab', 3, 1, '{}', 20260120000000, 20260120000000, 0),
(4, 1, 'tab', 4, 1, '{}', 20260120000000, 20260120000000, 0),
(5, 1, 'tab', 5, 1, '{}', 20260120000000, 20260120000000, 0),
(6, 1, 'tab', 6, 1, '{}', 20260120000000, 20260120000000, 0),
(7, 1, 'tab', 7, 1, '{}', 20260120000000, 20260120000000, 0),
(8, 1, 'tab', 8, 1, '{}', 20260120000000, 20260120000000, 0),
(9, 1, 'tab', 9, 1, '{}', 20260120000000, 20260120000000, 0);
