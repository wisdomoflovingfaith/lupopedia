-- Migration: Create lupo_migration_log for LupopediaMigrationController
-- Version: 4.2.2
-- Prerequisite: run before using LupopediaMigrationController
-- Doctrine: no FK, no triggers, BIGINT/TINYINT without display widths
--
-- @package Lupopedia
-- @see app/Services/System/LupopediaMigrationController.php

CREATE TABLE `lupo_migration_log` (
    `id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key',
    `executed_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when migration was attempted',
    `sql_snippet` text COMMENT 'First 2000 chars of migration SQL',
    `status` varchar(20) NOT NULL COMMENT 'success or blocked',
    `reason` text COMMENT 'If blocked, validation or execution reason',
    PRIMARY KEY (`id`),
    KEY `idx_executed_ymdhis` (`executed_ymdhis`),
    KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Log of migration attempts by LupopediaMigrationController';
