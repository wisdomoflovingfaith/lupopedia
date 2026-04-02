-- Migration: Add thread_key to lupo_dialog_threads table
-- Purpose: Support human-readable thread keys in channel paths
-- Date: 2026-04-02
-- Author: CURSOR (actor_id 102)

-- Add thread_key field after title
ALTER TABLE {{prefix}}dialog_threads 
ADD COLUMN `thread_key` varchar(255) DEFAULT NULL AFTER `title`;

-- Add index for thread_key for faster lookups
CREATE INDEX {{prefix}}dialog_threads_idx_thread_key ON {{prefix}}dialog_threads (thread_key);

-- Update existing threads to have thread_key based on title (one-time migration)
-- This converts spaces to hyphens and makes lowercase
UPDATE {{prefix}}dialog_threads 
SET `thread_key` = LOWER(REPLACE(REPLACE(`title`, ' ', '-'), '_', '-'))
WHERE `thread_key` IS NULL AND `title` IS NOT NULL;

-- Add comment to document the change
ALTER TABLE {{prefix}}dialog_threads 
COMMENT = 'Dialog threads with optional human-readable thread_key for channel paths';
