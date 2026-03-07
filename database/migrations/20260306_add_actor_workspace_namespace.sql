-- Lupopedia Migration
-- 20260306_add_actor_workspace_namespace.sql
-- Task: Add workspace_path and php_namespace to lupo_actors
-- Target Version: 4.0.62
-- From: Antigravity (42)

-- UP
-- Add columns to lupo_actors
ALTER TABLE `lupo_actors`
ADD COLUMN `workspace_path` VARCHAR(255) NULL DEFAULT NULL AFTER `actor_root_path`,
ADD COLUMN `php_namespace` VARCHAR(120) NULL DEFAULT NULL AFTER `workspace_path`;

-- Add indexes
CREATE INDEX `lupo_actors_idx_workspace_path` ON `lupo_actors` (`workspace_path`);
CREATE INDEX `lupo_actors_idx_php_namespace` ON `lupo_actors` (`php_namespace`);

-- Backfill workspace_path from current name-based convention
UPDATE `lupo_actors` 
SET `workspace_path` = CONCAT('lupo-actors/', LOWER(REPLACE(`name`, ' ', '-')));

-- Ensure captain and system are correct
UPDATE `lupo_actors` SET `workspace_path` = 'lupo-actors/system' WHERE `actor_id` = 0;
UPDATE `lupo_actors` SET `workspace_path` = 'lupo-actors/wolfie' WHERE `actor_id` = 1;
UPDATE `lupo_actors` SET `workspace_path` = 'lupo-actors/captain' WHERE `actor_id` = 10000;

-- Backfill php_namespace for IDE agents (example pattern)
UPDATE `lupo_actors` SET `php_namespace` = 'Lupo\\Agents\\Cursor' WHERE `actor_name` = 'cursor';
UPDATE `lupo_actors` SET `php_namespace` = 'Lupo\\Agents\\Antigravity' WHERE `actor_name` = 'antigravity';
UPDATE `lupo_actors` SET `php_namespace` = 'Lupo\\Agents\\Kiro' WHERE `actor_name` = 'kiro';
UPDATE `lupo_actors` SET `php_namespace` = 'Lupo\\Agents\\Windsurf' WHERE `actor_name` = 'windsurf';

-- DOWN
-- ALTER TABLE `lupo_actors` DROP COLUMN `workspace_path`;
-- ALTER TABLE `lupo_actors` DROP COLUMN `php_namespace`;
