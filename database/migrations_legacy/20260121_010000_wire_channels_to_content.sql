-- Migration: Wire Channels to Content
-- Version: 2026.1.0.3
-- Created: 2026-01-21 01:00:00 UTC
-- Purpose: Create edges between channels and their associated content

-- Note: Channel context files do not currently contain explicit content_id references.
-- This migration provides the structure for future channel-content assignments.
-- Content IDs available: 2000-2106 (from lupo_contents.toon)
-- Channel IDs available: 0, 1, 1001, 1002, 1003 (from lupo_channels.toon)

-- Example structure for future channel-content assignments:
-- Uncomment and modify as needed when content is assigned to channels

/*
-- System Kernel Channel (ID: 0) - System Documentation
INSERT INTO lupo_edges
(left_object_type, left_object_id, right_object_type, right_object_id,
 edge_type, channel_id, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES
('channel', 0, 'content', 2000, 'HAS_CONTENT', 0, 0, 0, 1, 0, 0, 20260121010000, 20260121010000);

-- System Lobby Channel (ID: 1) - Entry Point Documentation  
INSERT INTO lupo_edges
(left_object_type, left_object_id, right_object_type, right_object_id,
 edge_type, channel_id, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES
('channel', 1, 'content', 2001, 'HAS_CONTENT', 1, 0, 0, 1, 0, 0, 20260121010000, 20260121010000);

-- Test Awareness Channel (ID: 1001) - Testing Documentation
INSERT INTO lupo_edges
(left_object_type, left_object_id, right_object_type, right_object_id,
 edge_type, channel_id, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES
('channel', 1001, 'content', 2002, 'HAS_CONTENT', 1001, 0, 0, 1, 0, 0, 20260121010000, 20260121010000);

-- Dev Main Thread Channel (ID: 1002) - Development Documentation
INSERT INTO lupo_edges
(left_object_type, left_object_id, right_object_type, right_object_id,
 edge_type, channel_id, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES
('channel', 1002, 'content', 2003, 'HAS_CONTENT', 1002, 0, 0, 1, 0, 0, 20260121010000, 20260121010000);

-- GOV-PROGRAMMERS-001 Channel (ID: 1003) - Governance Documentation
INSERT INTO lupo_edges
(left_object_type, left_object_id, right_object_type, right_object_id,
 edge_type, channel_id, weight_score, sort_num, actor_id, is_deleted, deleted_ymdhis, created_ymdhis, updated_ymdhis)
VALUES
('channel', 1003, 'content', 2004, 'HAS_CONTENT', 1003, 0, 0, 1, 0, 0, 20260121010000, 20260121010000);
*/

-- Current Status:
-- - Channel TOON files created with proper structure
-- - Content TOON files contain content IDs 2000-2106
-- - Context TOON files contain semantic structure but no explicit content_id references
-- - Ready for manual content assignment as needed

-- To activate specific channel-content relationships:
-- 1. Uncomment the relevant INSERT statements above
-- 2. Update the content_id to match the actual content
-- 3. Run the migration
-- 4. Verify the edges in lupo_edges table

-- Available Content IDs (from lupo_contents.toon):
-- 2000, 2001, 2002, 2003, 2004, 2005, 2006, 2007, 2008, 2009, 2010, 2011, 2012, 2013, 2014, 2015, 2016, 2017, 2018, 2019, 2020, 2021, 2022, 2023, 2024, 2025, 2026, 2027, 2028, 2029, 2030, 2031, 2032, 2033, 2034, 2035, 2036, 2037, 2038, 2039, 2040, 2041, 2042, 2043, 2044, 2045, 2046, 2047, 2048, 2049, 2050, 2051, 2052, 2053, 2054, 2055, 2056, 2057, 2058, 2059, 2060, 2061, 2062, 2063, 2064, 2065, 2066, 2067, 2068, 2069, 2070, 2071, 2072, 2073, 2074, 2075, 2076, 2077, 2078, 2079, 2080, 2081, 2082, 2083, 2084, 2085, 2086, 2087, 2088, 2089, 2090, 2091, 2092, 2093, 2094, 2095, 2096, 2097, 2098, 2099, 2100, 2101, 2102, 2103, 2104, 2105, 2106

-- Available Channel IDs (from lupo_channels.toon):
-- 0: system/kernel
-- 1: system/lobby  
-- 1001: test_awareness_channel
-- 1002: dev-main-thread
-- 1003: GOV-PROGRAMMERS-001
