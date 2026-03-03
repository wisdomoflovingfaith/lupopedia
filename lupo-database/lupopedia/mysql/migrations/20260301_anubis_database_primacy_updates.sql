-- ANUBIS Database Primacy Updates
-- Version: 4.0.53
-- Purpose: Support database-first queueing where file content is stored in DB to survive filesystem loss.

-- Update lupo_anubis_queue
ALTER TABLE `lupo_anubis_queue` 
ADD COLUMN `file_content` LONGTEXT AFTER `file_hash`,
ADD COLUMN `filesystem_copy_exists` TINYINT DEFAULT 1 AFTER `assigned_to_actor_id`,
ADD COLUMN `filesystem_backup_path` VARCHAR(512) AFTER `filesystem_copy_exists`;

-- Update lupo_anubis_quarantine
ALTER TABLE `lupo_anubis_quarantine`
ADD COLUMN `file_content` LONGTEXT AFTER `file_hash`;

-- Ensure all current items are marked as having filesystem copies
UPDATE `lupo_anubis_queue` SET `filesystem_copy_exists` = 1 WHERE `filesystem_copy_exists` IS NULL;
