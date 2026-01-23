-- ============================================================
-- Lupopedia 4.0.11 — Collection 0 Tab Content Mapping Seed
-- Maps system documentation content to Collection 0 tabs
-- ============================================================
--
-- wolfie.headers.version: "4.0.11"
-- header_atoms:
--   - GLOBAL_CURRENT_LUPOPEDIA_VERSION
-- dialog:
--   - speaker: CURSOR
--     target: @everyone
--     message: "Version 4.0.11: Created seed SQL file for mapping system documentation content to Collection 0 tabs in lupo_collection_tab_map. All inserts are idempotent using ON DUPLICATE KEY UPDATE."
--     mood: "00FF00"
-- tags:
--   categories: ["database", "seed", "mapping"]
--   collections: ["core-modules"]
--   channels: ["dev"]
-- file:
--   title: "Collection 0 Tab Content Mapping Seed"
--   description: "Seed file for mapping content items to tabs in lupo_collection_tab_map"
--   version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
--   status: active
--   author: GLOBAL_CURRENT_AUTHORS
--
-- Purpose: Map all system documentation content items to their
-- corresponding tabs in Collection 0 (System Collection).
--
-- Rules:
-- - All inserts are idempotent using INSERT ... ON DUPLICATE KEY UPDATE
-- - Uses BIGINT UTC timestamp format (YYYYMMDDHHMMSS)
-- - item_type = 'content'
-- - sort_order = alphabetical by filename
--
-- Tab Mapping:
-- Tab 1 (Overview)         → /docs/core/*.md
-- Tab 2 (Doctrine)         → /docs/doctrine/*.md
-- Tab 3 (Architecture)     → /docs/ARCHITECTURE/*.md
-- Tab 4 (Schema)           → /docs/schema/*.md
-- Tab 5 (Agents)           → /docs/agents/*.md
-- Tab 6 (UI-UX)            → (no content - directory doesn't exist)
-- Tab 7 (Developer Guide)  → /docs/dev/*.md
-- Tab 8 (History)          → /docs/history/*.md
-- Tab 9 (Appendix)         → /docs/appendix/*.md
--
-- ============================================================

-- Set deterministic timestamp for seed operations
SET @now = 20260113000000;

-- Set domain/node ID (default to 1)
SET @node_id = 1;

-- Map content 2000 to tab 1
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    1,
    1,
    @node_id,
    'content',
    2000,
    1,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2001 to tab 1
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    2,
    1,
    @node_id,
    'content',
    2001,
    2,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2002 to tab 1
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    3,
    1,
    @node_id,
    'content',
    2002,
    3,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2003 to tab 1
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    4,
    1,
    @node_id,
    'content',
    2003,
    4,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2004 to tab 1
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    5,
    1,
    @node_id,
    'content',
    2004,
    5,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2005 to tab 1
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    6,
    1,
    @node_id,
    'content',
    2005,
    6,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2006 to tab 1
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    7,
    1,
    @node_id,
    'content',
    2006,
    7,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2007 to tab 1
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    8,
    1,
    @node_id,
    'content',
    2007,
    8,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2008 to tab 1
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    9,
    1,
    @node_id,
    'content',
    2008,
    9,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2009 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    10,
    2,
    @node_id,
    'content',
    2009,
    1,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2010 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    11,
    2,
    @node_id,
    'content',
    2010,
    2,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2011 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    12,
    2,
    @node_id,
    'content',
    2011,
    3,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2012 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    13,
    2,
    @node_id,
    'content',
    2012,
    4,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2013 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    14,
    2,
    @node_id,
    'content',
    2013,
    5,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2014 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    15,
    2,
    @node_id,
    'content',
    2014,
    6,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2015 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    16,
    2,
    @node_id,
    'content',
    2015,
    7,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2016 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    17,
    2,
    @node_id,
    'content',
    2016,
    8,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2017 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    18,
    2,
    @node_id,
    'content',
    2017,
    9,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2018 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    19,
    2,
    @node_id,
    'content',
    2018,
    10,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2019 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    20,
    2,
    @node_id,
    'content',
    2019,
    11,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2020 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    21,
    2,
    @node_id,
    'content',
    2020,
    12,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2021 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    22,
    2,
    @node_id,
    'content',
    2021,
    13,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2022 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    23,
    2,
    @node_id,
    'content',
    2022,
    14,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2023 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    24,
    2,
    @node_id,
    'content',
    2023,
    15,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2024 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    25,
    2,
    @node_id,
    'content',
    2024,
    16,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2025 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    26,
    2,
    @node_id,
    'content',
    2025,
    17,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2026 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    27,
    2,
    @node_id,
    'content',
    2026,
    18,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2027 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    28,
    2,
    @node_id,
    'content',
    2027,
    19,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2028 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    29,
    2,
    @node_id,
    'content',
    2028,
    20,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2029 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    30,
    2,
    @node_id,
    'content',
    2029,
    21,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2030 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    31,
    2,
    @node_id,
    'content',
    2030,
    22,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2031 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    32,
    2,
    @node_id,
    'content',
    2031,
    23,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2032 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    33,
    2,
    @node_id,
    'content',
    2032,
    24,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2033 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    34,
    2,
    @node_id,
    'content',
    2033,
    25,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2034 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    35,
    2,
    @node_id,
    'content',
    2034,
    26,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2035 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    36,
    2,
    @node_id,
    'content',
    2035,
    27,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2036 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    37,
    2,
    @node_id,
    'content',
    2036,
    28,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2037 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    38,
    2,
    @node_id,
    'content',
    2037,
    29,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2038 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    39,
    2,
    @node_id,
    'content',
    2038,
    30,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2039 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    40,
    2,
    @node_id,
    'content',
    2039,
    31,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2040 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    41,
    2,
    @node_id,
    'content',
    2040,
    32,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2041 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    42,
    2,
    @node_id,
    'content',
    2041,
    33,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2042 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    43,
    2,
    @node_id,
    'content',
    2042,
    34,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2043 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    44,
    2,
    @node_id,
    'content',
    2043,
    35,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2044 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    45,
    2,
    @node_id,
    'content',
    2044,
    36,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2045 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    46,
    2,
    @node_id,
    'content',
    2045,
    37,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2046 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    47,
    2,
    @node_id,
    'content',
    2046,
    38,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2047 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    48,
    2,
    @node_id,
    'content',
    2047,
    39,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2048 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    49,
    2,
    @node_id,
    'content',
    2048,
    40,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2049 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    50,
    2,
    @node_id,
    'content',
    2049,
    41,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2050 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    51,
    2,
    @node_id,
    'content',
    2050,
    42,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2051 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    52,
    2,
    @node_id,
    'content',
    2051,
    43,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2052 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    53,
    2,
    @node_id,
    'content',
    2052,
    44,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2053 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    54,
    2,
    @node_id,
    'content',
    2053,
    45,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2054 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    55,
    2,
    @node_id,
    'content',
    2054,
    46,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2055 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    56,
    2,
    @node_id,
    'content',
    2055,
    47,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2056 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    57,
    2,
    @node_id,
    'content',
    2056,
    48,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2057 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    58,
    2,
    @node_id,
    'content',
    2057,
    49,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2058 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    59,
    2,
    @node_id,
    'content',
    2058,
    50,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2059 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    60,
    2,
    @node_id,
    'content',
    2059,
    51,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2060 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    61,
    2,
    @node_id,
    'content',
    2060,
    52,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2061 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    62,
    2,
    @node_id,
    'content',
    2061,
    53,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2062 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    63,
    2,
    @node_id,
    'content',
    2062,
    54,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2063 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    64,
    2,
    @node_id,
    'content',
    2063,
    55,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2064 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    65,
    2,
    @node_id,
    'content',
    2064,
    56,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2065 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    66,
    2,
    @node_id,
    'content',
    2065,
    57,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2066 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    67,
    2,
    @node_id,
    'content',
    2066,
    58,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2067 to tab 2
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    68,
    2,
    @node_id,
    'content',
    2067,
    59,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2068 to tab 3
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    69,
    3,
    @node_id,
    'content',
    2068,
    1,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2069 to tab 4
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    70,
    4,
    @node_id,
    'content',
    2069,
    1,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2070 to tab 4
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    71,
    4,
    @node_id,
    'content',
    2070,
    2,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2071 to tab 5
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    72,
    5,
    @node_id,
    'content',
    2071,
    1,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2072 to tab 5
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    73,
    5,
    @node_id,
    'content',
    2072,
    2,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2073 to tab 5
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    74,
    5,
    @node_id,
    'content',
    2073,
    3,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2074 to tab 5
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    75,
    5,
    @node_id,
    'content',
    2074,
    4,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2075 to tab 5
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    76,
    5,
    @node_id,
    'content',
    2075,
    5,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2076 to tab 5
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    77,
    5,
    @node_id,
    'content',
    2076,
    6,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2077 to tab 5
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    78,
    5,
    @node_id,
    'content',
    2077,
    7,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2078 to tab 5
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    79,
    5,
    @node_id,
    'content',
    2078,
    8,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2079 to tab 5
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    80,
    5,
    @node_id,
    'content',
    2079,
    9,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2080 to tab 5
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    81,
    5,
    @node_id,
    'content',
    2080,
    10,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2081 to tab 5
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    82,
    5,
    @node_id,
    'content',
    2081,
    11,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2082 to tab 5
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    83,
    5,
    @node_id,
    'content',
    2082,
    12,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2083 to tab 7
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    84,
    7,
    @node_id,
    'content',
    2083,
    1,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2084 to tab 7
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    85,
    7,
    @node_id,
    'content',
    2084,
    2,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2085 to tab 7
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    86,
    7,
    @node_id,
    'content',
    2085,
    3,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2086 to tab 7
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    87,
    7,
    @node_id,
    'content',
    2086,
    4,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2087 to tab 7
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    88,
    7,
    @node_id,
    'content',
    2087,
    5,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2088 to tab 7
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    89,
    7,
    @node_id,
    'content',
    2088,
    6,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2089 to tab 7
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    90,
    7,
    @node_id,
    'content',
    2089,
    7,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2090 to tab 7
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    91,
    7,
    @node_id,
    'content',
    2090,
    8,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2091 to tab 7
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    92,
    7,
    @node_id,
    'content',
    2091,
    9,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2092 to tab 7
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    93,
    7,
    @node_id,
    'content',
    2092,
    10,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2093 to tab 7
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    94,
    7,
    @node_id,
    'content',
    2093,
    11,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2094 to tab 7
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    95,
    7,
    @node_id,
    'content',
    2094,
    12,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2095 to tab 7
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    96,
    7,
    @node_id,
    'content',
    2095,
    13,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2096 to tab 8
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    97,
    8,
    @node_id,
    'content',
    2096,
    1,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2097 to tab 9
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    98,
    9,
    @node_id,
    'content',
    2097,
    1,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2098 to tab 9
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    99,
    9,
    @node_id,
    'content',
    2098,
    2,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2099 to tab 9
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    100,
    9,
    @node_id,
    'content',
    2099,
    3,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2100 to tab 9
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    101,
    9,
    @node_id,
    'content',
    2100,
    4,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2101 to tab 9
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    102,
    9,
    @node_id,
    'content',
    2101,
    5,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2102 to tab 9
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    103,
    9,
    @node_id,
    'content',
    2102,
    6,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2103 to tab 9
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    104,
    9,
    @node_id,
    'content',
    2103,
    7,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2104 to tab 9
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    105,
    9,
    @node_id,
    'content',
    2104,
    8,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2105 to tab 9
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    106,
    9,
    @node_id,
    'content',
    2105,
    9,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- Map content 2106 to tab 9
INSERT INTO `lupo_collection_tab_map` (
    `collection_tab_map_id`,
    `collection_tab_id`,
    `federations_node_id`,
    `item_type`,
    `item_id`,
    `sort_order`,
    `properties`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
)
VALUES (
    107,
    9,
    @node_id,
    'content',
    2106,
    10,
    NULL,
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    `sort_order` = VALUES(`sort_order`),
    `updated_ymdhis` = @now,
    `is_deleted` = 0;

-- ============================================================
-- END OF MAPPING SEED
-- ============================================================
