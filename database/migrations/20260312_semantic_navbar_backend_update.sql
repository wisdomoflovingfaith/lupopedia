-- One-time migration: Semantic Navbar Backend (4.0.71)
-- Adds: lupo_references, lupo_reference_links, lupo_hashtags, lupo_hashtag_map, lupo_folders, lupo_folder_map
-- No FKs; no backward compatibility required. Run once on existing DBs; fresh installs get these from install_new_lupopedia.sql.

-- References (citations / source links)
CREATE TABLE IF NOT EXISTS lupo_references (
  reference_id bigint NOT NULL AUTO_INCREMENT,
  source_entity_type varchar(64) NOT NULL,
  source_entity_id bigint NOT NULL,
  url varchar(2000) DEFAULT NULL,
  title varchar(500) DEFAULT NULL,
  citation_text text,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (reference_id)
);
CREATE INDEX lupo_references_idx_source ON lupo_references (source_entity_type, source_entity_id);
CREATE INDEX lupo_references_idx_created ON lupo_references (created_ymdhis);
CREATE INDEX lupo_references_idx_is_deleted ON lupo_references (is_deleted);

CREATE TABLE IF NOT EXISTS lupo_reference_links (
  reference_link_id bigint NOT NULL AUTO_INCREMENT,
  reference_id bigint NOT NULL,
  object_type varchar(64) NOT NULL,
  object_id bigint NOT NULL,
  sort_order int NOT NULL DEFAULT 0,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (reference_link_id)
);
CREATE INDEX lupo_reference_links_idx_reference ON lupo_reference_links (reference_id);
CREATE INDEX lupo_reference_links_idx_object ON lupo_reference_links (object_type, object_id);
CREATE INDEX lupo_reference_links_idx_is_deleted ON lupo_reference_links (is_deleted);

-- Hashtags (normalized tags; lupo_contents.hashtags JSON remains for denormalized use)
CREATE TABLE IF NOT EXISTS lupo_hashtags (
  hashtag_id bigint NOT NULL AUTO_INCREMENT,
  tag_slug varchar(128) NOT NULL,
  label varchar(255) DEFAULT NULL,
  use_count bigint NOT NULL DEFAULT 0,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (hashtag_id)
);
CREATE UNIQUE INDEX lupo_hashtags_uniq_slug ON lupo_hashtags (tag_slug);
CREATE INDEX lupo_hashtags_idx_use_count ON lupo_hashtags (use_count);
CREATE INDEX lupo_hashtags_idx_is_deleted ON lupo_hashtags (is_deleted);

CREATE TABLE IF NOT EXISTS lupo_hashtag_map (
  hashtag_map_id bigint NOT NULL AUTO_INCREMENT,
  hashtag_id bigint NOT NULL,
  object_type varchar(64) NOT NULL,
  object_id bigint NOT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (hashtag_map_id)
);
CREATE INDEX lupo_hashtag_map_idx_hashtag ON lupo_hashtag_map (hashtag_id);
CREATE INDEX lupo_hashtag_map_idx_object ON lupo_hashtag_map (object_type, object_id);
CREATE INDEX lupo_hashtag_map_idx_is_deleted ON lupo_hashtag_map (is_deleted);

-- Folders (folder-based grouping for navbar)
CREATE TABLE IF NOT EXISTS lupo_folders (
  folder_id bigint NOT NULL AUTO_INCREMENT,
  name varchar(255) NOT NULL,
  slug varchar(128) NOT NULL,
  parent_folder_id bigint DEFAULT NULL,
  actor_id bigint DEFAULT NULL,
  channel_id bigint DEFAULT NULL,
  sort_order int NOT NULL DEFAULT 0,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (folder_id)
);
CREATE INDEX lupo_folders_idx_parent ON lupo_folders (parent_folder_id);
CREATE INDEX lupo_folders_idx_actor ON lupo_folders (actor_id);
CREATE INDEX lupo_folders_idx_channel ON lupo_folders (channel_id);
CREATE INDEX lupo_folders_idx_slug ON lupo_folders (slug);
CREATE INDEX lupo_folders_idx_is_deleted ON lupo_folders (is_deleted);

CREATE TABLE IF NOT EXISTS lupo_folder_map (
  folder_map_id bigint NOT NULL AUTO_INCREMENT,
  folder_id bigint NOT NULL,
  object_type varchar(64) NOT NULL,
  object_id bigint NOT NULL,
  sort_order int NOT NULL DEFAULT 0,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (folder_map_id)
);
CREATE INDEX lupo_folder_map_idx_folder ON lupo_folder_map (folder_id);
CREATE INDEX lupo_folder_map_idx_object ON lupo_folder_map (object_type, object_id);
CREATE INDEX lupo_folder_map_idx_is_deleted ON lupo_folder_map (is_deleted);
