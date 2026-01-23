-- Migration: Create lupo_help_topics table
-- Version: 4.1.1
-- Date: 2026-01-18
-- Module: Help System

CREATE TABLE IF NOT EXISTS `lupo_help_topics` (
    `help_topic_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Primary key for help topic',
    `slug` VARCHAR(255) NOT NULL COMMENT 'URL-friendly unique identifier',
    `title` VARCHAR(255) NOT NULL COMMENT 'Topic title',
    `content_html` LONGTEXT COMMENT 'HTML content of the help topic',
    `category` VARCHAR(100) COMMENT 'Category for grouping topics',
    `created_ymdhis` BIGINT UNSIGNED NOT NULL COMMENT 'UTC creation timestamp (YYYYMMDDHHMMSS)',
    `updated_ymdhis` BIGINT UNSIGNED NOT NULL COMMENT 'UTC last update timestamp (YYYYMMDDHHMMSS)',
    `is_deleted` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted, 0=active)',
    `deleted_ymdhis` BIGINT UNSIGNED DEFAULT NULL COMMENT 'UTC deletion timestamp (YYYYMMDDHHMMSS)',
    PRIMARY KEY (`help_topic_id`),
    UNIQUE KEY `uniq_slug` (`slug`),
    KEY `idx_category` (`category`),
    KEY `idx_is_deleted` (`is_deleted`),
    KEY `idx_category_deleted` (`category`, `is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Help documentation topics for Lupopedia';
