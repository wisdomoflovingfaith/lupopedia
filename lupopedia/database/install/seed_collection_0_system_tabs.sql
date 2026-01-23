-- ============================================================
-- Lupopedia 4.0.10 — Collection 0 System Collection Seed
-- Initializes Collection 0 (Lupopedia System) and its 9 tabs
-- ============================================================
--
-- wolfie.headers.version: "4.0.10"
-- header_atoms:
--   - GLOBAL_CURRENT_LUPOPEDIA_VERSION
-- dialog:
--   - speaker: CURSOR
--     target: @everyone
--     message: "Version 4.0.10: Created seed SQL file for Collection 0 (System Collection) and 9 system tabs. All inserts are idempotent using ON DUPLICATE KEY UPDATE. Uses BIGINT UTC timestamp format."
--     mood: "00FF00"
-- tags:
--   categories: ["database", "seed", "collections"]
--   collections: ["core-modules"]
--   channels: ["dev"]
-- file:
--   title: "Collection 0 System Tabs Seed"
--   description: "Seed file for Collection 0 (Lupopedia System) and 9 default tabs: Overview, Doctrine, Architecture, Schema, Agents, UI-UX, Developer Guide, History, Appendix"
--   version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
--   status: active
--   author: GLOBAL_CURRENT_AUTHORS
--
-- Purpose: Seed the System Collection (Collection 0) with 9 default tabs
-- for organizing Lupopedia system documentation and doctrine.
--
-- Rules:
-- - All inserts are idempotent using INSERT ... ON DUPLICATE KEY UPDATE
-- - Uses BIGINT UTC timestamp format (YYYYMMDDHHMMSS)
-- - Collection 0 is the System Collection
-- - 9 tabs: Overview, Doctrine, Architecture, Schema, Agents, UI-UX, Developer Guide, History, Appendix
-- - No content mapping yet (lupo_collection_tab_map remains empty)
--
-- ============================================================

-- Set deterministic timestamp for seed operations
SET @now = 20260113000000;

-- Set domain/node ID (default to 1)
SET @node_id = 1;

-- ============================================================
-- COLLECTION 0 (SYSTEM COLLECTION)
-- ============================================================

INSERT INTO `lupo_collections` (
    `collection_id`,
    `federations_node_id`,
    `user_id`,
    `group_id`,
    `name`,
    `slug`,
    `color`,
    `description`,
    `sort_order`,
    `properties`,
    `published_ymdhis`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    0,
    @node_id,
    NULL,
    NULL,
    'Lupopedia System',
    'lupopedia-system',
    '666666',
    'System-level documentation and doctrine for Lupopedia.',
    0,
    '{"is_system": 1}',
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `name` = VALUES(`name`),
    `slug` = VALUES(`slug`),
    `color` = VALUES(`color`),
    `description` = VALUES(`description`),
    `properties` = VALUES(`properties`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0,
    `deleted_ymdhis` = NULL;

-- ============================================================
-- SYSTEM TAB SET (9 TABS FOR COLLECTION 0)
-- ============================================================

-- Tab 1: Overview
INSERT INTO `lupo_collection_tabs` (
    `collection_tab_id`,
    `collection_tab_parent_id`,
    `collection_id`,
    `federations_node_id`,
    `group_id`,
    `user_id`,
    `sort_order`,
    `name`,
    `slug`,
    `color`,
    `description`,
    `is_hidden`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_active`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    1,
    NULL,
    0,
    @node_id,
    NULL,
    NULL,
    1,
    'Overview',
    'overview',
    '4caf50',
    'Overview of the Lupopedia system',
    0,
    @now,
    @now,
    1,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `name` = VALUES(`name`),
    `slug` = VALUES(`slug`),
    `color` = VALUES(`color`),
    `description` = VALUES(`description`),
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_active` = 1,
    `is_deleted` = 0,
    `deleted_ymdhis` = NULL;

-- Tab 2: Doctrine
INSERT INTO `lupo_collection_tabs` (
    `collection_tab_id`,
    `collection_tab_parent_id`,
    `collection_id`,
    `federations_node_id`,
    `group_id`,
    `user_id`,
    `sort_order`,
    `name`,
    `slug`,
    `color`,
    `description`,
    `is_hidden`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_active`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    2,
    NULL,
    0,
    @node_id,
    NULL,
    NULL,
    2,
    'Doctrine',
    'doctrine',
    '4caf50',
    'System doctrine and principles',
    0,
    @now,
    @now,
    1,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `name` = VALUES(`name`),
    `slug` = VALUES(`slug`),
    `color` = VALUES(`color`),
    `description` = VALUES(`description`),
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_active` = 1,
    `is_deleted` = 0,
    `deleted_ymdhis` = NULL;

-- Tab 3: Architecture
INSERT INTO `lupo_collection_tabs` (
    `collection_tab_id`,
    `collection_tab_parent_id`,
    `collection_id`,
    `federations_node_id`,
    `group_id`,
    `user_id`,
    `sort_order`,
    `name`,
    `slug`,
    `color`,
    `description`,
    `is_hidden`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_active`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    3,
    NULL,
    0,
    @node_id,
    NULL,
    NULL,
    3,
    'Architecture',
    'architecture',
    '4caf50',
    'System architecture documentation',
    0,
    @now,
    @now,
    1,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `name` = VALUES(`name`),
    `slug` = VALUES(`slug`),
    `color` = VALUES(`color`),
    `description` = VALUES(`description`),
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_active` = 1,
    `is_deleted` = 0,
    `deleted_ymdhis` = NULL;

-- Tab 4: Schema
INSERT INTO `lupo_collection_tabs` (
    `collection_tab_id`,
    `collection_tab_parent_id`,
    `collection_id`,
    `federations_node_id`,
    `group_id`,
    `user_id`,
    `sort_order`,
    `name`,
    `slug`,
    `color`,
    `description`,
    `is_hidden`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_active`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    4,
    NULL,
    0,
    @node_id,
    NULL,
    NULL,
    4,
    'Schema',
    'schema',
    '4caf50',
    'Database schema documentation',
    0,
    @now,
    @now,
    1,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `name` = VALUES(`name`),
    `slug` = VALUES(`slug`),
    `color` = VALUES(`color`),
    `description` = VALUES(`description`),
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_active` = 1,
    `is_deleted` = 0,
    `deleted_ymdhis` = NULL;

-- Tab 5: Agents
INSERT INTO `lupo_collection_tabs` (
    `collection_tab_id`,
    `collection_tab_parent_id`,
    `collection_id`,
    `federations_node_id`,
    `group_id`,
    `user_id`,
    `sort_order`,
    `name`,
    `slug`,
    `color`,
    `description`,
    `is_hidden`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_active`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    5,
    NULL,
    0,
    @node_id,
    NULL,
    NULL,
    5,
    'Agents',
    'agents',
    '4caf50',
    'AI agent documentation',
    0,
    @now,
    @now,
    1,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `name` = VALUES(`name`),
    `slug` = VALUES(`slug`),
    `color` = VALUES(`color`),
    `description` = VALUES(`description`),
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_active` = 1,
    `is_deleted` = 0,
    `deleted_ymdhis` = NULL;

-- Tab 6: UI-UX
INSERT INTO `lupo_collection_tabs` (
    `collection_tab_id`,
    `collection_tab_parent_id`,
    `collection_id`,
    `federations_node_id`,
    `group_id`,
    `user_id`,
    `sort_order`,
    `name`,
    `slug`,
    `color`,
    `description`,
    `is_hidden`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_active`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    6,
    NULL,
    0,
    @node_id,
    NULL,
    NULL,
    6,
    'UI-UX',
    'ui-ux',
    '4caf50',
    'User interface and user experience documentation',
    0,
    @now,
    @now,
    1,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `name` = VALUES(`name`),
    `slug` = VALUES(`slug`),
    `color` = VALUES(`color`),
    `description` = VALUES(`description`),
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_active` = 1,
    `is_deleted` = 0,
    `deleted_ymdhis` = NULL;

-- Tab 7: Developer Guide
INSERT INTO `lupo_collection_tabs` (
    `collection_tab_id`,
    `collection_tab_parent_id`,
    `collection_id`,
    `federations_node_id`,
    `group_id`,
    `user_id`,
    `sort_order`,
    `name`,
    `slug`,
    `color`,
    `description`,
    `is_hidden`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_active`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    7,
    NULL,
    0,
    @node_id,
    NULL,
    NULL,
    7,
    'Developer Guide',
    'developer-guide',
    '4caf50',
    'Developer documentation and guides',
    0,
    @now,
    @now,
    1,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `name` = VALUES(`name`),
    `slug` = VALUES(`slug`),
    `color` = VALUES(`color`),
    `description` = VALUES(`description`),
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_active` = 1,
    `is_deleted` = 0,
    `deleted_ymdhis` = NULL;

-- Tab 8: History
INSERT INTO `lupo_collection_tabs` (
    `collection_tab_id`,
    `collection_tab_parent_id`,
    `collection_id`,
    `federations_node_id`,
    `group_id`,
    `user_id`,
    `sort_order`,
    `name`,
    `slug`,
    `color`,
    `description`,
    `is_hidden`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_active`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    8,
    NULL,
    0,
    @node_id,
    NULL,
    NULL,
    8,
    'History',
    'history',
    '4caf50',
    'System history and changelog',
    0,
    @now,
    @now,
    1,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `name` = VALUES(`name`),
    `slug` = VALUES(`slug`),
    `color` = VALUES(`color`),
    `description` = VALUES(`description`),
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_active` = 1,
    `is_deleted` = 0,
    `deleted_ymdhis` = NULL;

-- Tab 9: Appendix
INSERT INTO `lupo_collection_tabs` (
    `collection_tab_id`,
    `collection_tab_parent_id`,
    `collection_id`,
    `federations_node_id`,
    `group_id`,
    `user_id`,
    `sort_order`,
    `name`,
    `slug`,
    `color`,
    `description`,
    `is_hidden`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_active`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    9,
    NULL,
    0,
    @node_id,
    NULL,
    NULL,
    9,
    'Appendix',
    'appendix',
    '4caf50',
    'Additional reference materials',
    0,
    @now,
    @now,
    1,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `name` = VALUES(`name`),
    `slug` = VALUES(`slug`),
    `color` = VALUES(`color`),
    `description` = VALUES(`description`),
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_active` = 1,
    `is_deleted` = 0,
    `deleted_ymdhis` = NULL;

-- ============================================================
-- END OF SEED
-- ============================================================
-- 
-- Note: lupo_collection_tab_map remains empty in this patch.
-- Content mapping will be added in a future patch.
