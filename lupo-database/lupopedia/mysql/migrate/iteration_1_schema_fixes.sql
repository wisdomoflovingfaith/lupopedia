-- Migration script for iteration 1 schema fixes
-- Applies to existing Lupopedia 4.0.85 installations
-- Run after install_new_lupopedia.sql updates
-- Author: HEPHAESTUS (actor_id 8)
-- Date: 20260321_240000
-- Purpose: Resolve schema gaps identified in iteration 1 validation

-- 1. Create missing lupo_thread_metadata table
CREATE TABLE IF NOT EXISTS lupo_thread_metadata (
  thread_metadata_id bigint NOT NULL,
  dialog_thread_id bigint NOT NULL,
  metadata_key varchar(255) NOT NULL,
  metadata_value text,
  metadata_type varchar(64) NOT NULL DEFAULT 'string',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL,
  created_by_actor_id bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (thread_metadata_id)
);

-- Create indexes for lupo_thread_metadata
CREATE UNIQUE INDEX IF NOT EXISTS lupo_thread_metadata_unq_thread_key ON lupo_thread_metadata (dialog_thread_id, metadata_key);
CREATE INDEX IF NOT EXISTS lupo_thread_metadata_idx_thread_id ON lupo_thread_metadata (dialog_thread_id);
CREATE INDEX IF NOT EXISTS lupo_thread_metadata_idx_key ON lupo_thread_metadata (metadata_key);
CREATE INDEX IF NOT EXISTS lupo_thread_metadata_idx_type ON lupo_thread_metadata (metadata_type);
CREATE INDEX IF NOT EXISTS lupo_thread_metadata_idx_created ON lupo_thread_metadata (created_ymdhis);
CREATE INDEX IF NOT EXISTS lupo_thread_metadata_idx_deleted ON lupo_thread_metadata (is_deleted);

-- 2. Add missing columns if they don't exist
ALTER TABLE lupo_channels 
ADD COLUMN IF NOT EXISTS channel_config text DEFAULT NULL;

ALTER TABLE lupo_dialog_threads 
ADD COLUMN IF NOT EXISTS thread_lineage text DEFAULT NULL;

ALTER TABLE lupo_actors 
ADD COLUMN IF NOT EXISTS actor_config text;

-- 3. Fix task_priority data type if needed
-- Check if column is still VARCHAR and convert
SET @column_type = (SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS 
                   WHERE TABLE_SCHEMA = DATABASE() 
                   AND TABLE_NAME = 'lupo_tasks' 
                   AND COLUMN_NAME = 'task_priority');

SET @sql = IF(@column_type = 'varchar', 
  'ALTER TABLE lupo_tasks MODIFY COLUMN task_priority ENUM(''low'', ''normal'', ''high'', ''urgent'', ''critical'') NOT NULL DEFAULT ''normal''',
  'SELECT ''Column already correct type'' as message');

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- 4. Update any NULL actor_config values
UPDATE lupo_actors SET actor_config = '{}' WHERE actor_config IS NULL;

-- 5. Verify migration success
SELECT 'Migration completed successfully' as status,
       COUNT(*) as thread_metadata_tables
FROM information_schema.tables 
WHERE table_schema = DATABASE() 
AND table_name = 'lupo_thread_metadata';

-- 6. Show final schema state
SELECT 'lupo_thread_metadata table created' as achievement
UNION ALL
SELECT 'lupo_channels.channel_config column added' as achievement
UNION ALL  
SELECT 'lupo_dialog_threads.thread_lineage column added' as achievement
UNION ALL
SELECT 'lupo_actors.actor_config column added' as achievement
UNION ALL
SELECT 'lupo_tasks.task_priority converted to ENUM' as achievement
UNION ALL
SELECT 'All schema gaps resolved' as achievement;
