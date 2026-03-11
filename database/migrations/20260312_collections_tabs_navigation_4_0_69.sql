-- One-time migration: Collections/tabs navigation expansion (4.0.69).
-- For existing 4.0.x databases only. New installs get schema from install_new_lupopedia.sql.
-- Run once. Record in lupo_schema_migrations (schema_migration_id, version, name, applied_ymdhis).
-- Use LUPO_TABLE_PREFIX if your install uses a different prefix (replace lupo_ in table names).

-- 1. lupo_collections: add channel_id, is_nav_menu, nav_icon
ALTER TABLE lupo_collections
  ADD COLUMN channel_id bigint DEFAULT NULL AFTER parent_id,
  ADD COLUMN is_nav_menu tinyint NOT NULL DEFAULT 0 AFTER channel_id,
  ADD COLUMN nav_icon varchar(64) DEFAULT NULL AFTER is_nav_menu;
CREATE INDEX lupo_collections_idx_channel_id ON lupo_collections (channel_id);
CREATE INDEX lupo_collections_idx_is_nav_menu ON lupo_collections (is_nav_menu);

-- 2. lupo_collection_tabs: rename user_id to actor_id, add visibility_rule, tab_type
ALTER TABLE lupo_collection_tabs
  CHANGE COLUMN user_id actor_id bigint DEFAULT NULL,
  ADD COLUMN visibility_rule text DEFAULT NULL AFTER is_hidden,
  ADD COLUMN tab_type varchar(32) DEFAULT NULL AFTER visibility_rule;
CREATE INDEX lupo_collection_tabs_idx_actor_id ON lupo_collection_tabs (actor_id);

-- 3. Record migration
INSERT INTO lupo_schema_migrations (schema_migration_id, version, name, applied_ymdhis)
VALUES (20260312002, '20260312', '20260312_collections_tabs_navigation_4_0_69', 20260312120000);
