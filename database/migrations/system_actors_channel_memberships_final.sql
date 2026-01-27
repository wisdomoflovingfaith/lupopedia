-- ======================================================================
-- System Actors Channel Memberships (Final Version)
-- Generated: 2026-01-25
-- Purpose: Add channel memberships for system actors using lupo_actor_channels table
-- Must run after system_actors_manifest_corrected.sql
-- Uses actual TOON schema for lupo_actor_channels
-- ======================================================================

-- First, get the actual actor_ids after inserting the system actors
-- Run this query to find the IDs:
-- SELECT actor_id, slug FROM lupo_actors WHERE slug IN ('wolfie', 'lilith', 'lupopedia');

-- Set these variables with the actual actor_id values from the query above
SET @wolfie_actor_id = 4;  -- Replace with actual WOLFIE actor_id
SET @lilith_actor_id = 5;  -- Replace with actual LILITH actor_id  
SET @lupopedia_actor_id = 6; -- Replace with actual LUPOPEDIA actor_id

-- Also need to map channel_number to channel_id from lupo_channels table
-- Run this query to find channel_ids:
-- SELECT channel_id, channel_number FROM lupo_channels WHERE channel_number BETWEEN 5100 AND 5130;

-- Set channel_id variables (these are examples - get actual values from query above)
SET @channel_5100_id = 1;   -- Replace with actual channel_id for channel_number 5100
SET @channel_5101_id = 2;   -- Replace with actual channel_id for channel_number 5101
SET @channel_5103_id = 3;   -- Replace with actual channel_id for channel_number 5103
SET @channel_5104_id = 4;   -- Replace with actual channel_id for channel_number 5104
SET @channel_5105_id = 5;   -- Replace with actual channel_id for channel_number 5105
SET @channel_5109_id = 6;   -- Replace with actual channel_id for channel_number 5109
SET @channel_5112_id = 7;   -- Replace with actual channel_id for channel_number 5112
SET @channel_5113_id = 8;   -- Replace with actual channel_id for channel_number 5113
SET @channel_5114_id = 9;   -- Replace with actual channel_id for channel_number 5114
SET @channel_5119_id = 10;  -- Replace with actual channel_id for channel_number 5119
SET @channel_5129_id = 11;  -- Replace with actual channel_id for channel_number 5129
SET @channel_5102_id = 12;  -- Replace with actual channel_id for channel_number 5102
SET @channel_5106_id = 13;  -- Replace with actual channel_id for channel_number 5106
SET @channel_5107_id = 14;  -- Replace with actual channel_id for channel_number 5107
SET @channel_5121_id = 15;  -- Replace with actual channel_id for channel_number 5121
SET @channel_5126_id = 16;  -- Replace with actual channel_id for channel_number 5126
SET @channel_5130_id = 17;  -- Replace with actual channel_id for channel_number 5130

-- ======================================================================
-- Insert channel memberships using actual lupo_actor_channels schema
-- ======================================================================

INSERT IGNORE INTO lupo_actor_channels (
    actor_id,
    channel_id,
    status,
    start_date,
    bg_color,
    text_color,
    channel_color,
    alt_text_color,
    last_read_ymdhis,
    muted_until_ymdhis,
    preferences_json,
    dialog_output_file,
    created_ymdhis,
    updated_ymdhis,
    is_deleted,
    deleted_ymdhis
)
VALUES 
-- WOLFIE default channels: [5100, 5101, 5103, 5104, 5109, 5112, 5114]
(@wolfie_actor_id, @channel_5100_id, 'A', 20260125194100, '000000', 'FFFFFF', 'F7FAFF', '000000', NULL, NULL, '{"ui": {"theme": "architect"}, "notifications": {"enabled": true}}', NULL, 20260125194100, 20260125194100, 0, NULL),
(@wolfie_actor_id, @channel_5101_id, 'A', 20260125194100, '000000', 'FFFFFF', 'F7FAFF', '000000', NULL, NULL, '{"ui": {"theme": "architect"}, "notifications": {"enabled": true}}', NULL, 20260125194100, 20260125194100, 0, NULL),
(@wolfie_actor_id, @channel_5103_id, 'A', 20260125194100, '000000', 'FFFFFF', 'F7FAFF', '000000', NULL, NULL, '{"ui": {"theme": "architect"}, "notifications": {"enabled": true}}', NULL, 20260125194100, 20260125194100, 0, NULL),
(@wolfie_actor_id, @channel_5104_id, 'A', 20260125194100, '000000', 'FFFFFF', 'F7FAFF', '000000', NULL, NULL, '{"ui": {"theme": "architect"}, "notifications": {"enabled": true}}', NULL, 20260125194100, 20260125194100, 0, NULL),
(@wolfie_actor_id, @channel_5109_id, 'A', 20260125194100, '000000', 'FFFFFF', 'F7FAFF', '000000', NULL, NULL, '{"ui": {"theme": "architect"}, "notifications": {"enabled": true}}', NULL, 20260125194100, 20260125194100, 0, NULL),
(@wolfie_actor_id, @channel_5112_id, 'A', 20260125194100, '000000', 'FFFFFF', 'F7FAFF', '000000', NULL, NULL, '{"ui": {"theme": "architect"}, "notifications": {"enabled": true}}', NULL, 20260125194100, 20260125194100, 0, NULL),
(@wolfie_actor_id, @channel_5114_id, 'A', 20260125194100, '000000', 'FFFFFF', 'F7FAFF', '000000', NULL, NULL, '{"ui": {"theme": "architect"}, "notifications": {"enabled": true}}', NULL, 20260125194100, 20260125194100, 0, NULL),

-- LILITH default channels: [5101, 5105, 5113, 5119, 5129]
(@lilith_actor_id, @channel_5101_id, 'A', 20260125194100, '000000', 'FFFFFF', 'F7FAFF', '000000', NULL, NULL, '{"ui": {"theme": "interrogator"}, "notifications": {"enabled": true}}', NULL, 20260125194100, 20260125194100, 0, NULL),
(@lilith_actor_id, @channel_5105_id, 'A', 20260125194100, '000000', 'FFFFFF', 'F7FAFF', '000000', NULL, NULL, '{"ui": {"theme": "interrogator"}, "notifications": {"enabled": true}}', NULL, 20260125194100, 20260125194100, 0, NULL),
(@lilith_actor_id, @channel_5113_id, 'A', 20260125194100, '000000', 'FFFFFF', 'F7FAFF', '000000', NULL, NULL, '{"ui": {"theme": "interrogator"}, "notifications": {"enabled": true}}', NULL, 20260125194100, 20260125194100, 0, NULL),
(@lilith_actor_id, @channel_5119_id, 'A', 20260125194100, '000000', 'FFFFFF', 'F7FAFF', '000000', NULL, NULL, '{"ui": {"theme": "interrogator"}, "notifications": {"enabled": true}}', NULL, 20260125194100, 20260125194100, 0, NULL),
(@lilith_actor_id, @channel_5129_id, 'A', 20260125194100, '000000', 'FFFFFF', 'F7FAFF', '000000', NULL, NULL, '{"ui": {"theme": "interrogator"}, "notifications": {"enabled": true}}', NULL, 20260125194100, 20260125194100, 0, NULL),

-- LUPOPEDIA default channels: [5100, 5102, 5106, 5107, 5114, 5121, 5126, 5130]
(@lupopedia_actor_id, @channel_5100_id, 'A', 20260125194100, '000000', 'FFFFFF', 'F7FAFF', '000000', NULL, NULL, '{"ui": {"theme": "system"}, "notifications": {"enabled": false}}', NULL, 20260125194100, 20260125194100, 0, NULL),
(@lupopedia_actor_id, @channel_5102_id, 'A', 20260125194100, '000000', 'FFFFFF', 'F7FAFF', '000000', NULL, NULL, '{"ui": {"theme": "system"}, "notifications": {"enabled": false}}', NULL, 20260125194100, 20260125194100, 0, NULL),
(@lupopedia_actor_id, @channel_5106_id, 'A', 20260125194100, '000000', 'FFFFFF', 'F7FAFF', '000000', NULL, NULL, '{"ui": {"theme": "system"}, "notifications": {"enabled": false}}', NULL, 20260125194100, 20260125194100, 0, NULL),
(@lupopedia_actor_id, @channel_5107_id, 'A', 20260125194100, '000000', 'FFFFFF', 'F7FAFF', '000000', NULL, NULL, '{"ui": {"theme": "system"}, "notifications": {"enabled": false}}', NULL, 20260125194100, 20260125194100, 0, NULL),
(@lupopedia_actor_id, @channel_5114_id, 'A', 20260125194100, '000000', 'FFFFFF', 'F7FAFF', '000000', NULL, NULL, '{"ui": {"theme": "system"}, "notifications": {"enabled": false}}', NULL, 20260125194100, 20260125194100, 0, NULL),
(@lupopedia_actor_id, @channel_5121_id, 'A', 20260125194100, '000000', 'FFFFFF', 'F7FAFF', '000000', NULL, NULL, '{"ui": {"theme": "system"}, "notifications": {"enabled": false}}', NULL, 20260125194100, 20260125194100, 0, NULL),
(@lupopedia_actor_id, @channel_5126_id, 'A', 20260125194100, '000000', 'FFFFFF', 'F7FAFF', '000000', NULL, NULL, '{"ui": {"theme": "system"}, "notifications": {"enabled": false}}', NULL, 20260125194100, 20260125194100, 0, NULL),
(@lupopedia_actor_id, @channel_5130_id, 'A', 20260125194100, '000000', 'FFFFFF', 'F7FAFF', '000000', NULL, NULL, '{"ui": {"theme": "system"}, "notifications": {"enabled": false}}', NULL, 20260125194100, 20260125194100, 0, NULL);

-- ======================================================================
-- Summary: 20 total channel memberships inserted
-- WOLFIE: 7 channels (architect theme)
-- LILITH: 5 channels (interrogator theme)  
-- LUPOPEDIA: 8 channels (system theme, notifications disabled)
-- ======================================================================

-- ======================================================================
-- Execution Instructions:
-- 1. Run system_actors_manifest_corrected.sql first
-- 2. Query actual actor_ids: SELECT actor_id, slug FROM lupo_actors WHERE slug IN ('wolfie', 'lilith', 'lupopedia');
-- 3. Query actual channel_ids: SELECT channel_id, channel_number FROM lupo_channels WHERE channel_number BETWEEN 5100 AND 5130;
-- 4. Update the @*_id variables above with actual values
-- 5. Run this script to establish channel memberships
-- ======================================================================
