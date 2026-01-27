-- Migration: Create lupo_tldnr table for TL;DR summaries
-- Version: 4.1.6
-- Date: 2026-01-19
-- Module: Help System / Documentation
-- Purpose: Store concise TL;DR summaries for quick reference

-- Create lupo_tldnr table
CREATE TABLE IF NOT EXISTS `lupo_tldnr` (
    `tldnr_id` BIGINT NOT NULL AUTO_INCREMENT COMMENT 'Primary key for TL;DR record',
    `slug` VARCHAR(255) NOT NULL COMMENT 'URL-friendly unique identifier',
    `title` VARCHAR(255) NOT NULL COMMENT 'TL;DR title (e.g., "Lupopedia Overview", "Collection Doctrine")',
    `content_text` LONGTEXT NOT NULL COMMENT 'TL;DR content (plain text or markdown)',
    `topic_type` VARCHAR(100) DEFAULT NULL COMMENT 'Type of topic (e.g., "system", "doctrine", "module", "concept")',
    `topic_reference` VARCHAR(255) DEFAULT NULL COMMENT 'Reference to what this summarizes (e.g., "Lupopedia", "Collection Doctrine", "LABS-001")',
    `system_version` VARCHAR(20) DEFAULT NULL COMMENT 'System version this TL;DR applies to (e.g., "4.1.6")',
    `category` VARCHAR(100) DEFAULT NULL COMMENT 'Category for grouping (e.g., "Core", "Doctrine", "Module")',
    `created_ymdhis` BIGINT NOT NULL COMMENT 'UTC creation timestamp (YYYYMMDDHHIISS)',
    `updated_ymdhis` BIGINT NOT NULL COMMENT 'UTC last update timestamp (YYYYMMDDHHIISS)',
    `is_deleted` TINYINT NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted, 0=active)',
    `deleted_ymdhis` BIGINT DEFAULT NULL COMMENT 'UTC deletion timestamp (YYYYMMDDHHIISS)',
    PRIMARY KEY (`tldnr_id`),
    UNIQUE KEY `uniq_slug` (`slug`),
    KEY `idx_topic_type` (`topic_type`),
    KEY `idx_topic_reference` (`topic_reference`),
    KEY `idx_category` (`category`),
    KEY `idx_system_version` (`system_version`),
    KEY `idx_is_deleted` (`is_deleted`),
    KEY `idx_created` (`created_ymdhis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='TL;DR summaries for quick reference - concise explanations of Lupopedia concepts';

-- Insert initial TL;DR: Lupopedia Overview
INSERT INTO `lupo_tldnr` (`slug`, `title`, `content_text`, `topic_type`, `topic_reference`, `system_version`, `category`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`) VALUES
('lupopedia-overview', 'Lupopedia Overview', 'Lupopedia is a semantic operating system (not a CMS or framework). It records meaning; it doesn''t impose it.

THE FIVE PILLARS:
1. Actor Pillar - Identity is primary (email = login)
2. Temporal Pillar - Time is the spine (BIGINT UTC timestamps)
3. Edge Pillar - Relationships are meaning (no foreign keys, app-managed)
4. Doctrine Pillar - Law prevents drift (rules in text files)
5. Emergence Pillar - Roles are discovered, not assigned

HOW IT WORKS:
- Collections = navigation universes (each has its own tabs)
- Tabs = user-defined semantic categories (you choose the names)
- Content = stored in lupo_content table
- Meaning = created when content is placed under tabs

WHAT YOU DON''T BUILD:
- You don''t build every system
- You don''t define tabs for users
- You don''t impose meaning
- You record what users define

WHAT YOU DO BUILD:
- The infrastructure (database, routing, modules)
- The tools (tab editor, content editor)
- The doctrine (rules in text files)

CURRENT STATUS (4.1.6):
- LABS-001: Actor baseline state (10 declarations required)
- GOV-AD-PROHIBIT-001: No ads in system output
- WOLFIE Headers: Metadata on every file (version tracking)
- UTC_TIMEKEEPER: Single source of truth for timestamps
- Help System: Documentation module
- List System: Browse entities

BOTTOM LINE: Lupopedia is a semantic OS that records user-defined meaning. You build the infrastructure; users define their own collections, tabs, and content. The system records it, doesn''t impose it.', 'system', 'Lupopedia', '4.1.6', 'Core', 20260119040000, 20260119040000, 0)
ON DUPLICATE KEY UPDATE
    `title` = VALUES(`title`),
    `content_text` = VALUES(`content_text`),
    `topic_type` = VALUES(`topic_type`),
    `topic_reference` = VALUES(`topic_reference`),
    `system_version` = VALUES(`system_version`),
    `category` = VALUES(`category`),
    `updated_ymdhis` = VALUES(`updated_ymdhis`);

-- Insert TL;DR: Collection Doctrine
INSERT INTO `lupo_tldnr` (`slug`, `title`, `content_text`, `topic_type`, `topic_reference`, `system_version`, `category`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`) VALUES
('collection-doctrine', 'Collection Doctrine TL;DR', 'COLLECTIONS = Navigation universes. Each collection has its own tabs, content, structure, and meaning.

TABS = User-defined semantic categories. Lupopedia doesn''t impose meaning - it records it.

KEY POINTS:
- Collection 0 (System Collection) has 9 default tabs (Overview, Doctrine, Architecture, Schema, Agents, UI-UX, Developer Guide, History, Appendix)
- Users create their own Collections with their own tabs
- Tabs are semantic "folders" - they define meaning
- Tabs do not cross Collections
- Each Collection has its own permissions (via lupo_permissions table)

EXAMPLES:
- "Desktop" Collection → WHO, WHAT, WHERE, WHEN, WHY, HOW, DO
- "County of Honolulu" Collection → Departments, Parks & Recreation, Activities & Programs, Contact

YOU DON''T BUILD EVERY SYSTEM - users define their own tabs based on their needs.', 'doctrine', 'Collection Doctrine', '4.1.6', 'Doctrine', 20260119040000, 20260119040000, 0)
ON DUPLICATE KEY UPDATE
    `title` = VALUES(`title`),
    `content_text` = VALUES(`content_text`),
    `topic_type` = VALUES(`topic_type`),
    `topic_reference` = VALUES(`topic_reference`),
    `system_version` = VALUES(`system_version`),
    `category` = VALUES(`category`),
    `updated_ymdhis` = VALUES(`updated_ymdhis`);
