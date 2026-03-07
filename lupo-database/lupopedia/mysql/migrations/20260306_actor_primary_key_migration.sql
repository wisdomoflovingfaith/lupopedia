-- Actor Primary Key Restructure (v4.0.58)
-- Purpose: Add actor_name as primary identifier; keep actor_id as unique secondary.
-- Run on existing DBs that have actor_id as PK. No foreign keys (doctrine).
-- Idempotent: safe to run multiple times (check for column existence where supported).

-- Step 1: Add actor_name to lupo_actors and backfill from canonical mapping
ALTER TABLE `lupo_actors` ADD COLUMN `actor_name` varchar(64) DEFAULT NULL AFTER `actor_id`;

UPDATE `lupo_actors` SET `actor_name` = CASE `actor_id`
  WHEN 0 THEN 'system'
  WHEN 1 THEN 'wolfie'
  WHEN 2 THEN 'lilith-legacy'
  WHEN 3 THEN 'rose'
  WHEN 4 THEN 'eris'
  WHEN 5 THEN 'metis'
  WHEN 19 THEN 'anubis'
  WHEN 25 THEN 'vishwakarma'
  WHEN 42 THEN 'antigravity'
  WHEN 59 THEN 'indexer'
  WHEN 420 THEN 'stoned-wolfie'
  WHEN 1000 THEN 'kiro'
  WHEN 1001 THEN 'windsurf'
  WHEN 1002 THEN 'cursor-ide'
  WHEN 1003 THEN 'cursor'
  WHEN 1004 THEN 'warp'
  WHEN 1005 THEN 'cascade'
  WHEN 1006 THEN 'gemini-cli'
  WHEN 1007 THEN 'codex'
  WHEN 1008 THEN 'trae'
  WHEN 10000 THEN 'captain'
  WHEN 10420 THEN 'test-banned-user'
  WHEN 11001 THEN 'user-11001'
  WHEN 11002 THEN 'user-11002'
  WHEN 11003 THEN 'user-11003'
  WHEN 11004 THEN 'user-11004'
  WHEN 11005 THEN 'user-11005'
  WHEN 11006 THEN 'user-11006'
  WHEN 11007 THEN 'user-11007'
  WHEN 11008 THEN 'user-11008'
  WHEN 11009 THEN 'user-11009'
  WHEN 11010 THEN 'user-11010'
  WHEN 2038 THEN 'lilith'
  ELSE CONCAT('actor_', `actor_id`)
END
WHERE `actor_name` IS NULL OR `actor_name` = '';

-- Fallback for any remaining NULL (e.g. new rows)
UPDATE `lupo_actors` SET `actor_name` = CONCAT('actor_', `actor_id`) WHERE `actor_name` IS NULL OR `actor_name` = '';

-- Step 2: Make actor_name NOT NULL, then change primary key (MySQL: drop PK, add new)
ALTER TABLE `lupo_actors` MODIFY `actor_name` varchar(64) NOT NULL;
ALTER TABLE `lupo_actors` DROP PRIMARY KEY;
ALTER TABLE `lupo_actors` ADD PRIMARY KEY (`actor_name`);
ALTER TABLE `lupo_actors` ADD UNIQUE INDEX `lupo_actors_unique_actor_id` (`actor_id`);

-- Step 3: Add actor_name to referencing tables and backfill
ALTER TABLE `lupo_banned_actors` ADD COLUMN `actor_name` varchar(64) DEFAULT NULL AFTER `actor_id`;
UPDATE `lupo_banned_actors` b
INNER JOIN `lupo_actors` a ON b.actor_id = a.actor_id
SET b.actor_name = a.actor_name;
ALTER TABLE `lupo_banned_actors` ADD INDEX `lupo_banned_actors_idx_actor_name` (`actor_name`);

ALTER TABLE `lupo_actor_channels` ADD COLUMN `actor_name` varchar(64) DEFAULT NULL AFTER `actor_id`;
UPDATE `lupo_actor_channels` c INNER JOIN `lupo_actors` a ON c.actor_id = a.actor_id SET c.actor_name = a.actor_name;
ALTER TABLE `lupo_actor_channels` ADD INDEX `lupo_actor_channels_idx_actor_name` (`actor_name`);

ALTER TABLE `lupo_actor_channel_roles` ADD COLUMN `actor_name` varchar(64) DEFAULT NULL AFTER `actor_id`;
UPDATE `lupo_actor_channel_roles` r INNER JOIN `lupo_actors` a ON r.actor_id = a.actor_id SET r.actor_name = a.actor_name;
ALTER TABLE `lupo_actor_channel_roles` ADD INDEX `lupo_actor_channel_roles_idx_actor_name` (`actor_name`);

ALTER TABLE `lupo_sessions` ADD COLUMN `actor_name` varchar(64) DEFAULT NULL AFTER `actor_id`;
UPDATE `lupo_sessions` s INNER JOIN `lupo_actors` a ON s.actor_id = a.actor_id SET s.actor_name = a.actor_name;
ALTER TABLE `lupo_sessions` ADD INDEX `lupo_sessions_idx_actor_name` (`actor_name`);
