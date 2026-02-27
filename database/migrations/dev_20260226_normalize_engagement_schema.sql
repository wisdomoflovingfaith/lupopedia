-- dev_20260226_normalize_engagement_schema.sql
-- Normalize lupo_contents and refactor lupo_user_comments for FLARE 4.1.0

-- 1. Normalize lupo_contents Engagement Cache
ALTER TABLE lupo_contents CHANGE likes_total like_count bigint NOT NULL DEFAULT 0;
ALTER TABLE lupo_contents DROP COLUMN share_count;
ALTER TABLE lupo_contents CHANGE shares_total share_count bigint NOT NULL DEFAULT 0;
ALTER TABLE lupo_contents ADD COLUMN comment_count bigint NOT NULL DEFAULT 0 AFTER share_count;

-- Update indexes for lupo_contents
DROP INDEX lupo_contents_idx_has_likes_shares ON lupo_contents;
CREATE INDEX lupo_contents_idx_engagement_counts ON lupo_contents (like_count, share_count, comment_count);

-- 2. Refactor lupo_user_comments to lupo_comments (Universal Commenting)
RENAME TABLE lupo_user_comments TO lupo_comments;

ALTER TABLE lupo_comments CHANGE user_comment_id comment_id bigint NOT NULL;
ALTER TABLE lupo_comments CHANGE user_id actor_id bigint NOT NULL;
ALTER TABLE lupo_comments CHANGE content_id target_id bigint NOT NULL;
ALTER TABLE lupo_comments ADD COLUMN target_table varchar(100) NOT NULL DEFAULT 'lupo_contents' AFTER actor_id;
ALTER TABLE lupo_comments ADD COLUMN status varchar(64) NOT NULL DEFAULT 'approved' AFTER comment_text;

-- Update indexes for lupo_comments
DROP INDEX lupo_user_comments_idx_user_id ON lupo_comments;
DROP INDEX lupo_user_comments_idx_content_id ON lupo_comments;
CREATE INDEX lupo_comments_idx_actor_id ON lupo_comments (actor_id);
CREATE INDEX lupo_comments_idx_target ON lupo_comments (target_table, target_id);
CREATE INDEX lupo_comments_idx_parent ON lupo_comments (parent_comment_id);
CREATE INDEX lupo_comments_idx_status ON lupo_comments (status);
