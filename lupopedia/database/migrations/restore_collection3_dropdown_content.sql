-- ======================================================================
-- Restore Collection 3 Dropdown Content Using TOON Files
-- Migration for Lupopedia 4.2.3
-- Created: 2026-01-20
-- Based on lupo_collection_tab_map.toon data
-- ======================================================================

-- Restore tab → content mappings for Collection 3
-- Overview tab (collection_tab_id = 1)
INSERT IGNORE INTO `lupo_collection_tab_map`
(`collection_tab_id`, `federations_node_id`, `item_type`, `item_id`, `sort_order`, `properties`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`)
VALUES
(1, 1, 'content', 2000, 1, NULL, 20260120000000, 20260120000000, 0, NULL),
(1, 1, 'content', 2001, 2, NULL, 20260120000000, 20260120000000, 0, NULL),
(1, 1, 'content', 2002, 3, NULL, 20260120000000, 20260120000000, 0, NULL),
(1, 1, 'content', 2003, 4, NULL, 20260120000000, 20260120000000, 0, NULL),
(1, 1, 'content', 2004, 5, NULL, 20260120000000, 20260120000000, 0, NULL),
(1, 1, 'content', 2005, 6, NULL, 20260120000000, 20260120000000, 0, NULL),
(1, 1, 'content', 2006, 7, NULL, 20260120000000, 20260120000000, 0, NULL),
(1, 1, 'content', 2007, 8, NULL, 20260120000000, 20260120000000, 0, NULL),
(1, 1, 'content', 2008, 9, NULL, 20260120000000, 20260120000000, 0, NULL);

-- Doctrine tab (collection_tab_id = 2) - active content only
INSERT IGNORE INTO `lupo_collection_tab_map`
(`collection_tab_id`, `federations_node_id`, `item_type`, `item_id`, `sort_order`, `properties`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`)
VALUES
(2, 1, 'content', 2015, 7, NULL, 20260120000000, 20260120000000, 0, NULL),
(2, 1, 'content', 2018, 10, NULL, 20260120000000, 20260120000000, 0, NULL),
(2, 1, 'content', 2019, 11, NULL, 20260120000000, 20260120000000, 0, NULL),
(2, 1, 'content', 2020, 12, NULL, 20260120000000, 20260120000000, 0, NULL),
(2, 1, 'content', 2021, 13, NULL, 20260120000000, 20260120000000, 0, NULL),
(2, 1, 'content', 2022, 14, NULL, 20260120000000, 20260120000000, 0, NULL),
(2, 1, 'content', 2023, 15, NULL, 20260120000000, 20260120000000, 0, NULL),
(2, 1, 'content', 2024, 16, NULL, 20260120000000, 20260120000000, 0, NULL),
(2, 1, 'content', 2025, 17, NULL, 20260120000000, 20260120000000, 0, NULL),
(2, 1, 'content', 2026, 18, NULL, 20260120000000, 20260120000000, 0, NULL),
(2, 1, 'content', 2027, 19, NULL, 20260120000000, 20260120000000, 0, NULL),
(2, 1, 'content', 2028, 20, NULL, 20260120000000, 20260120000000, 0, NULL),
(2, 1, 'content', 2029, 21, NULL, 20260120000000, 20260120000000, 0, NULL),
(2, 1, 'content', 2030, 22, NULL, 20260120000000, 20260120000000, 0, NULL),
(2, 1, 'content', 2032, 24, NULL, 20260120000000, 20260120000000, 0, NULL),
(2, 1, 'content', 2033, 25, NULL, 20260120000000, 20260120000000, 0, NULL),
(2, 1, 'content', 2036, 28, NULL, 20260120000000, 20260120000000, 0, NULL),
(2, 1, 'content', 2038, 30, NULL, 20260120000000, 20260120000000, 0, NULL),
(2, 1, 'content', 2039, 31, NULL, 20260120000000, 20260120000000, 0, NULL),
(2, 1, 'content', 2041, 33, NULL, 20260120000000, 20260120000000, 0, NULL),
(2, 1, 'content', 2042, 34, NULL, 20260120000000, 20260120000000, 0, NULL),
(2, 1, 'content', 2043, 35, NULL, 20260120000000, 20260120000000, 0, NULL),
(2, 1, 'content', 2044, 36, NULL, 20260120000000, 20260120000000, 0, NULL),
(2, 1, 'content', 2045, 37, NULL, 20260120000000, 20260120000000, 0, NULL),
(2, 1, 'content', 2046, 38, NULL, 20260120000000, 20260120000000, 0, NULL),
(2, 1, 'content', 2047, 39, NULL, 20260120000000, 20260120000000, 0, NULL),
(2, 1, 'content', 2049, 41, NULL, 20260120000000, 20260120000000, 0, NULL),
(2, 1, 'content', 2051, 43, NULL, 20260120000000, 20260120000000, 0, NULL),
(2, 1, 'content', 2052, 44, NULL, 20260120000000, 20260120000000, 0, NULL),
(2, 1, 'content', 2053, 45, NULL, 20260120000000, 20260120000000, 0, NULL),
(2, 1, 'content', 2054, 46, NULL, 20260120000000, 20260120000000, 0, NULL),
(2, 1, 'content', 2055, 47, NULL, 20260120000000, 20260120000000, 0, NULL),
(2, 1, 'content', 2056, 48, NULL, 20260120000000, 20260120000000, 0, NULL),
(2, 1, 'content', 2058, 50, NULL, 20260120000000, 20260120000000, 0, NULL),
(2, 1, 'content', 2059, 51, NULL, 20260120000000, 20260120000000, 0, NULL),
(2, 1, 'content', 2062, 54, NULL, 20260120000000, 20260120000000, 0, NULL),
(2, 1, 'content', 2063, 55, NULL, 20260120000000, 20260120000000, 0, NULL),
(2, 1, 'content', 2064, 56, NULL, 20260120000000, 20260120000000, 0, NULL),
(2, 1, 'content', 2067, 59, NULL, 20260120000000, 20260120000000, 0, NULL);

-- Architecture tab (collection_tab_id = 3)
INSERT IGNORE INTO `lupo_collection_tab_map`
(`collection_tab_id`, `federations_node_id`, `item_type`, `item_id`, `sort_order`, `properties`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`)
VALUES
(3, 1, 'content', 2068, 1, NULL, 20260120000000, 20260120000000, 0, NULL);

-- Schema tab (collection_tab_id = 4)
INSERT IGNORE INTO `lupo_collection_tab_map`
(`collection_tab_id`, `federations_node_id`, `item_type`, `item_id`, `sort_order`, `properties`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`)
VALUES
(4, 1, 'content', 2069, 1, NULL, 20260120000000, 20260120000000, 0, NULL),
(4, 1, 'content', 2070, 2, NULL, 20260120000000, 20260120000000, 0, NULL);

-- Agents tab (collection_tab_id = 5)
INSERT IGNORE INTO `lupo_collection_tab_map`
(`collection_tab_id`, `federations_node_id`, `item_type`, `item_id`, `sort_order`, `properties`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`)
VALUES
(5, 1, 'content', 2071, 1, NULL, 20260120000000, 20260120000000, 0, NULL),
(5, 1, 'content', 2072, 2, NULL, 20260120000000, 20260120000000, 0, NULL),
(5, 1, 'content', 2073, 3, NULL, 20260120000000, 20260120000000, 0, NULL),
(5, 1, 'content', 2074, 4, NULL, 20260120000000, 20260120000000, 0, NULL),
(5, 1, 'content', 2075, 5, NULL, 20260120000000, 20260120000000, 0, NULL),
(5, 1, 'content', 2076, 6, NULL, 20260120000000, 20260120000000, 0, NULL),
(5, 1, 'content', 2077, 7, NULL, 20260120000000, 20260120000000, 0, NULL),
(5, 1, 'content', 2078, 8, NULL, 20260120000000, 20260120000000, 0, NULL),
(5, 1, 'content', 2079, 9, NULL, 20260120000000, 20260120000000, 0, NULL),
(5, 1, 'content', 2080, 10, NULL, 20260120000000, 20260120000000, 0, NULL),
(5, 1, 'content', 2081, 11, NULL, 20260120000000, 20260120000000, 0, NULL),
(5, 1, 'content', 2082, 12, NULL, 20260120000000, 20260120000000, 0, NULL),
(5, 1, 'content', 2083, 13, NULL, 20260120000000, 20260120000000, 0, NULL),
(5, 1, 'content', 2084, 14, NULL, 20260120000000, 20260120000000, 0, NULL),
(5, 1, 'content', 2085, 15, NULL, 20260120000000, 20260120000000, 0, NULL),
(5, 1, 'content', 2086, 16, NULL, 20260120000000, 20260120000000, 0, NULL),
(5, 1, 'content', 2087, 17, NULL, 20260120000000, 20260120000000, 0, NULL),
(5, 1, 'content', 2088, 18, NULL, 20260120000000, 20260120000000, 0, NULL),
(5, 1, 'content', 2089, 19, NULL, 20260120000000, 20260120000000, 0, NULL),
(5, 1, 'content', 2090, 20, NULL, 20260120000000, 20260120000000, 0, NULL),
(5, 1, 'content', 2091, 21, NULL, 20260120000000, 20260120000000, 0, NULL);

-- UI-UX tab (collection_tab_id = 6)
INSERT IGNORE INTO `lupo_collection_tab_map`
(`collection_tab_id`, `federations_node_id`, `item_type`, `item_id`, `sort_order`, `properties`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`)
VALUES
(6, 1, 'content', 2092, 1, NULL, 20260120000000, 20260120000000, 0, NULL);

-- Developer Guide tab (collection_tab_id = 7)
INSERT IGNORE INTO `lupo_collection_tab_map`
(`collection_tab_id`, `federations_node_id`, `item_type`, `item_id`, `sort_order`, `properties`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`)
VALUES
(7, 1, 'content', 2093, 1, NULL, 20260120000000, 20260120000000, 0, NULL),
(7, 1, 'content', 2094, 2, NULL, 20260120000000, 20260120000000, 0, NULL),
(7, 1, 'content', 2095, 3, NULL, 20260120000000, 20260120000000, 0, NULL),
(7, 1, 'content', 2096, 4, NULL, 20260120000000, 20260120000000, 0, NULL),
(7, 1, 'content', 2097, 5, NULL, 20260120000000, 20260120000000, 0, NULL),
(7, 1, 'content', 2098, 6, NULL, 20260120000000, 20260120000000, 0, NULL),
(7, 1, 'content', 2099, 7, NULL, 20260120000000, 20260120000000, 0, NULL),
(7, 1, 'content', 2100, 8, NULL, 20260120000000, 20260120000000, 0, NULL),
(7, 1, 'content', 2101, 9, NULL, 20260120000000, 20260120000000, 0, NULL),
(7, 1, 'content', 2102, 10, NULL, 20260120000000, 20260120000000, 0, NULL),
(7, 1, 'content', 2103, 11, NULL, 20260120000000, 20260120000000, 0, NULL),
(7, 1, 'content', 2104, 12, NULL, 20260120000000, 20260120000000, 0, NULL),
(7, 1, 'content', 2105, 13, NULL, 20260120000000, 20260120000000, 0, NULL),
(7, 1, 'content', 2106, 14, NULL, 20260120000000, 20260120000000, 0, NULL),
(7, 1, 'content', 2107, 15, NULL, 20260120000000, 20260120000000, 0, NULL),
(7, 1, 'content', 2108, 16, NULL, 20260120000000, 20260120000000, 0, NULL),
(7, 1, 'content', 2109, 17, NULL, 20260120000000, 20260120000000, 0, NULL),
(7, 1, 'content', 2110, 18, NULL, 20260120000000, 20260120000000, 0, NULL),
(7, 1, 'content', 2111, 19, NULL, 20260120000000, 20260120000000, 0, NULL),
(7, 1, 'content', 2112, 20, NULL, 20260120000000, 20260120000000, 0, NULL),
(7, 1, 'content', 2113, 21, NULL, 20260120000000, 20260120000000, 0, NULL),
(7, 1, 'content', 2114, 22, NULL, 20260120000000, 20260120000000, 0, NULL),
(7, 1, 'content', 2115, 23, NULL, 20260120000000, 20260120000000, 0, NULL),
(7, 1, 'content', 2116, 24, NULL, 20260120000000, 20260120000000, 0, NULL),
(7, 1, 'content', 2117, 25, NULL, 20260120000000, 20260120000000, 0, NULL),
(7, 1, 'content', 2118, 26, NULL, 20260120000000, 20260120000000, 0, NULL),
(7, 1, 'content', 2119, 27, NULL, 20260120000000, 20260120000000, 0, NULL),
(7, 1, 'content', 2120, 28, NULL, 20260120000000, 20260120000000, 0, NULL),
(7, 1, 'content', 2121, 29, NULL, 20260120000000, 20260120000000, 0, NULL),
(7, 1, 'content', 2122, 30, NULL, 20260120000000, 20260120000000, 0, NULL),
(7, 1, 'content', 2123, 31, NULL, 20260120000000, 20260120000000, 0, NULL),
(7, 1, 'content', 2124, 32, NULL, 20260120000000, 20260120000000, 0, NULL),
(7, 1, 'content', 2125, 33, NULL, 20260120000000, 20260120000000, 0, NULL),
(7, 1, 'content', 2126, 34, NULL, 20260120000000, 20260120000000, 0, NULL),
(7, 1, 'content', 2127, 35, NULL, 20260120000000, 20260120000000, 0, NULL);

-- History tab (collection_tab_id = 8)
INSERT IGNORE INTO `lupo_collection_tab_map`
(`collection_tab_id`, `federations_node_id`, `item_type`, `item_id`, `sort_order`, `properties`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`)
VALUES
(8, 1, 'content', 2128, 1, NULL, 20260120000000, 20260120000000, 0, NULL),
(8, 1, 'content', 2129, 2, NULL, 20260120000000, 20260120000000, 0, NULL),
(8, 1, 'content', 2130, 3, NULL, 20260120000000, 20260120000000, 0, NULL),
(8, 1, 'content', 2131, 4, NULL, 20260120000000, 20260120000000, 0, NULL),
(8, 1, 'content', 2132, 5, NULL, 20260120000000, 20260120000000, 0, NULL);

-- Appendix tab (collection_tab_id = 9)
INSERT IGNORE INTO `lupo_collection_tab_map`
(`collection_tab_id`, `federations_node_id`, `item_type`, `item_id`, `sort_order`, `properties`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`, `deleted_ymdhis`)
VALUES
(9, 1, 'content', 2133, 1, NULL, 20260120000000, 20260120000000, 0, NULL),
(9, 1, 'content', 2134, 2, NULL, 20260120000000, 20260120000000, 0, NULL),
(9, 1, 'content', 2135, 3, NULL, 20260120000000, 20260120000000, 0, NULL),
(9, 1, 'content', 2136, 4, NULL, 20260120000000, 20260120000000, 0, NULL),
(9, 1, 'content', 2137, 5, NULL, 20260120000000, 20260120000000, 0, NULL);

-- Restore additional tab paths from TOON data
INSERT IGNORE INTO `lupo_collection_tab_paths`
(`collection_id`, `collection_tab_id`, `path`, `depth`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`)
VALUES
(3, 1, 'overview', 1, 20260120000000, 20260120000000, 0),
(3, 6, 'ui-ux', 1, 20260120000000, 20260120000000, 0),
(3, 7, 'developer-guide', 1, 20260120000000, 20260120000000, 0);

-- Restore nested tab paths from TOON data
INSERT IGNORE INTO `lupo_collection_tab_paths`
(`collection_id`, `collection_tab_id`, `path`, `depth`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`)
VALUES
(3, 2, 'doctrine/sql', 2, 20260120000000, 20260120000000, 0),
(3, 2, 'doctrine/cursor', 2, 20260120000000, 20260120000000, 0),
(3, 2, 'doctrine/agent', 2, 20260120000000, 20260120000000, 0),
(3, 2, 'doctrine/docs', 2, 20260120000000, 20260120000000, 0),
(3, 2, 'doctrine/install', 2, 20260120000000, 20260120000000, 0),
(3, 2, 'doctrine/naming', 2, 20260120000000, 20260120000000, 0),
(3, 2, 'doctrine/semantic', 2, 20260120000000, 20260120000000, 0),
(3, 2, 'doctrine/ui', 2, 20260120000000, 20260120000000, 0),
(3, 2, 'doctrine/safety', 2, 20260120000000, 20260120000000, 0),
(3, 2, 'doctrine/mythic', 2, 20260120000000, 20260120000000, 0),
(3, 2, 'doctrine/versioning', 2, 20260120000000, 20260120000000, 0),
(3, 2, 'doctrine/misc', 2, 20260120000000, 20260120000000, 0),
(3, 5, 'agents/runtime', 2, 20260120000000, 20260120000000, 0),
(3, 5, 'agents/dialog', 2, 20260120000000, 20260120000000, 0),
(3, 5, 'agents/header', 2, 20260120000000, 20260120000000, 0),
(3, 5, 'agents/mythic', 2, 20260120000000, 20260120000000, 0),
(3, 9, 'appendix/company', 2, 20260120000000, 20260120000000, 0),
(3, 9, 'appendix/press', 2, 20260120000000, 20260120000000, 0),
(3, 9, 'appendix/technical', 2, 20260120000000, 20260120000000, 0),
(3, 9, 'appendix/mythic', 2, 20260120000000, 20260120000000, 0);
