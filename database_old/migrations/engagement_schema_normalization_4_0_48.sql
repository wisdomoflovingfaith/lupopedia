-- Engagement Schema Normalization for 4.0.48
-- Task: Complete FLARE engagement schema alignment
-- Date: 2026-02-27
-- Actor: Cascade (completing Antigravity work)

-- Migration 1: Rename columns in lupo_contents for FLARE naming alignment
ALTER TABLE lupo_contents 
  CHANGE COLUMN likes_total like_count bigint NOT NULL DEFAULT 0 COMMENT 'FLARE-aligned engagement cache',
  CHANGE COLUMN shares_total share_count bigint NOT NULL DEFAULT 0 COMMENT 'FLARE-aligned engagement cache';

-- Note: comment_count already exists in lupo_contents table (line 1623 in install_new_lupopedia.sql)
-- No need to add as it's already present

-- Migration 2: lupo_comments table already exists and is properly structured
-- Table already has:
-- - target_table varchar(100) NOT NULL DEFAULT 'lupo_contents' (universal commenting)
-- - target_id bigint NOT NULL (universal targeting)
-- - actor_id bigint NOT NULL (unified identity)
-- - parent_comment_id bigint DEFAULT NULL (threading support)
-- All required indexes are already present

-- No changes needed for lupo_comments as it's already properly aligned

-- Migration 3: Update lupo_actor_object_edges as source of truth
-- This table already supports like, share, bookmark via edge_type
-- No schema changes needed, just application-level consistency checks

COMMIT;
