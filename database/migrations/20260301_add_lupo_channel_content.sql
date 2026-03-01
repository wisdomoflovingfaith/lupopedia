-- Migration: Add lupo_channel_content table for federation node content management
-- Version: 4.0.52
-- Date: 20260301
-- Author: Windsurf (1002)

CREATE TABLE lupo_channel_content (
  channel_content_id bigint NOT NULL AUTO_INCREMENT,
  channel_id int NOT NULL,
  federation_node_id int NOT NULL,
  file_path varchar(500) NOT NULL,
  web_path varchar(500) NOT NULL,
  metadata_json json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (channel_content_id)
);

-- Indexes for performance
CREATE INDEX lupo_channel_content_idx_channel ON lupo_channel_content (channel_id);
CREATE INDEX lupo_channel_content_idx_federation_node ON lupo_channel_content (federation_node_id);
CREATE INDEX lupo_channel_content_idx_file_path ON lupo_channel_content (file_path);
CREATE INDEX lupo_channel_content_idx_web_path ON lupo_channel_content (web_path);
CREATE INDEX lupo_channel_content_idx_created ON lupo_channel_content (created_ymdhis);
CREATE INDEX lupo_channel_content_idx_updated ON lupo_channel_content (updated_ymdhis);
CREATE INDEX lupo_channel_content_idx_is_deleted ON lupo_channel_content (is_deleted);

-- Insert canonical FLARE entry for federation node 0
INSERT INTO lupo_channel_content
(channel_id, federation_node_id, file_path, web_path, metadata_json, created_ymdhis, updated_ymdhis, is_deleted)
VALUES
(
  42,
  0,
  'channels/42/content/federation_node_id/0/readme.md',
  'http://www.lupopedia.com/readme',
  JSON_OBJECT('description', 'Federation node 0 README with comprehensive Lupopedia overview and FLARE integration'),
  20260301120000,
  20260301120000,
  0
);
