-- Mockup SQL Migration: Create Release 4.0.73 Hub Collection and Tabs
-- Created: 2026-03-13 by Antigravity IDE Agent
-- Purpose: Consolidate commonly edited files for version 4.0.73 based on agent RECENTFILES collections.

-- 1. Create the Main Collection
INSERT INTO lupo_collections (
    federation_node_id, actor_id, name, slug, color, description, 
    sort_order, created_ymdhis, updated_ymdhis, channel_id, is_nav_menu, nav_icon
) VALUES (
    1, 1000, 'Release 4.0.73 Hub', 'release-4073-hub', '4169E1', 
    'Central hub for tracking commonly edited files and doctrine updates in version 4.0.73.', 
    10, 20260313070000, 20260313070000, 42, 1, 'hub'
);

-- Store the collection_id for subqueries (assuming last_insert_id() logic or manual mapping)
SET @collection_id = LAST_INSERT_ID();

-- 2. Create Tabs for the Collection

-- Tab: Overview
INSERT INTO lupo_collection_tabs (
    collection_id, federations_node_id, actor_id, sort_order, name, slug, 
    color, description, tab_type, created_ymdhis, updated_ymdhis
) VALUES (
    @collection_id, 1, 1000, 1, 'Overview', 'overview', 
    '4169E1', 'Changelog, README, and core versioning files.', 'content', 20260313070000, 20260313070000
);
SET @tab_overview_id = LAST_INSERT_ID();

-- Tab: Headers Doctrine
INSERT INTO lupo_collection_tabs (
    collection_id, federations_node_id, actor_id, sort_order, name, slug, 
    color, description, tab_type, created_ymdhis, updated_ymdhis
) VALUES (
    @collection_id, 1, 1000, 2, 'Headers Doctrine', 'headers-doctrine', 
    'FFD700', 'Canonical documentation for LUPOPEDIA HEADERS, snapshop doctrine, and engagement blocks.', 'content', 20260313070000, 20260313070000
);
SET @tab_doctrine_id = LAST_INSERT_ID();

-- Tab: Agent Activity
INSERT INTO lupo_collection_tabs (
    collection_id, federations_node_id, actor_id, sort_order, name, slug, 
    color, description, tab_type, created_ymdhis, updated_ymdhis
) VALUES (
    @collection_id, 1, 1000, 3, 'Agent Activity', 'agent-activity', 
    '4caf50', 'Individual agent RECENTFILES collections for 4.0.73.', 'collection', 20260313070000, 20260313070000
);
SET @tab_activity_id = LAST_INSERT_ID();

-- 3. Map Items to Tabs (Mockup mapping via slugs in lupo_contents)

-- Mapping for Overview Tab
INSERT INTO lupo_collection_tab_map (collection_tab_id, federations_node_id, item_type, item_id, sort_order, created_ymdhis, updated_ymdhis)
SELECT @tab_overview_id, 1, 'content', content_id, 1, 20260313070000, 20260313070000 FROM lupo_contents WHERE slug = 'changelog' AND is_deleted = 0 LIMIT 1;

INSERT INTO lupo_collection_tab_map (collection_tab_id, federations_node_id, item_type, item_id, sort_order, created_ymdhis, updated_ymdhis)
SELECT @tab_overview_id, 1, 'content', content_id, 2, 20260313070000, 20260313070000 FROM lupo_contents WHERE slug = 'readme' AND is_deleted = 0 LIMIT 1;

-- Mapping for Headers Doctrine Tab
INSERT INTO lupo_collection_tab_map (collection_tab_id, federations_node_id, item_type, item_id, sort_order, created_ymdhis, updated_ymdhis)
SELECT @tab_doctrine_id, 1, 'content', content_id, 1, 20260313070000, 20260313070000 FROM lupo_contents WHERE slug = 'lupopedia-headers' AND is_deleted = 0 LIMIT 1;

INSERT INTO lupo_collection_tab_map (collection_tab_id, federations_node_id, item_type, item_id, sort_order, created_ymdhis, updated_ymdhis)
SELECT @tab_doctrine_id, 1, 'content', content_id, 2, 20260313070000, 20260313070000 FROM lupo_contents WHERE slug = 'lupopedia-headers-format' AND is_deleted = 0 LIMIT 1;

INSERT INTO lupo_collection_tab_map (collection_tab_id, federations_node_id, item_type, item_id, sort_order, created_ymdhis, updated_ymdhis)
SELECT @tab_doctrine_id, 1, 'content', content_id, 3, 20260313070000, 20260313070000 FROM lupo_contents WHERE slug = 'optional-blocks' AND is_deleted = 0 LIMIT 1;

-- Mapping for Agent Activity Tab (Linking to existing collections by slug)
INSERT INTO lupo_collection_tab_map (collection_tab_id, federations_node_id, item_type, item_id, sort_order, created_ymdhis, updated_ymdhis)
SELECT @tab_activity_id, 1, 'collection', collection_id, 1, 20260313070000, 20260313070000 FROM lupo_collections WHERE slug = 'L-LUPO-ANTIGRAVITY-RECENTFILES-V4_0_73' AND is_deleted = 0 LIMIT 1;

INSERT INTO lupo_collection_tab_map (collection_tab_id, federations_node_id, item_type, item_id, sort_order, created_ymdhis, updated_ymdhis)
SELECT @tab_activity_id, 1, 'collection', collection_id, 2, 20260313070000, 20260313070000 FROM lupo_collections WHERE slug = 'L-LUPO-CURSOR-RECENTFILES-V4_0_73' AND is_deleted = 0 LIMIT 1;

INSERT INTO lupo_collection_tab_map (collection_tab_id, federations_node_id, item_type, item_id, sort_order, created_ymdhis, updated_ymdhis)
SELECT @tab_activity_id, 1, 'collection', collection_id, 3, 20260313070000, 20260313070000 FROM lupo_collections WHERE slug = 'L-LUPO-WINDSURF-RECENTFILES-V4_0_73' AND is_deleted = 0 LIMIT 1;
