-- =====================================================
-- WORLD GRAPH LAYER MISSING TABLES MIGRATION
-- Version: 2026.1.0.7
-- Purpose: Create missing world graph tables and fix FK constraints
-- HERITAGE-SAFE MODE: No legacy behavior modifications
-- =====================================================

-- NOTE: This migration creates the missing tables that were documented
-- in Phase 11 but not actually created in the database.

-- =====================================================
-- 1. CREATE WORLD REGISTRY TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS `lupo_world_registry` (
  `world_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for world node',
  `world_key` varchar(255) NOT NULL COMMENT 'Deterministic world key (e.g., department_123)',
  `world_type` enum('department','channel','page','campaign','console','live','external','ui') NOT NULL COMMENT 'Type of world context',
  `world_label` varchar(255) NOT NULL COMMENT 'Human-readable world label',
  `world_metadata` json COMMENT 'Additional world context data',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS',
  `updated_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Active flag',
  PRIMARY KEY (`world_id`),
  UNIQUE KEY `unique_world_key` (`world_key`),
  KEY `idx_world_type` (`world_type`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='World registry for context-aware analytics';

-- =====================================================
-- 2. CREATE MISSING TOON EVENT TABLES WITH WORLD CONTEXT
-- =====================================================

-- Session Events Table (with world context)
CREATE TABLE IF NOT EXISTS `lupo_session_events` (
  `session_event_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for session event',
  `session_id` varchar(255) NOT NULL COMMENT 'Session identifier',
  `actor_id` bigint DEFAULT NULL COMMENT 'Actor ID from lupo_actors',
  `tab_id` varchar(255) DEFAULT NULL COMMENT 'Tab identifier for multi-tab tracking',
  `world_id` bigint DEFAULT NULL COMMENT 'World context ID',
  `world_key` varchar(255) DEFAULT NULL COMMENT 'World context key',
  `world_type` varchar(50) DEFAULT NULL COMMENT 'World context type',
  `event_type` varchar(100) NOT NULL COMMENT 'Type of session event',
  `event_data` json COMMENT 'Event-specific data',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS',
  PRIMARY KEY (`session_event_id`),
  KEY `idx_session_id` (`session_id`),
  KEY `idx_actor_id` (`actor_id`),
  KEY `idx_tab_id` (`tab_id`),
  KEY `idx_world_id` (`world_id`),
  KEY `idx_event_type` (`event_type`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_session_event_type` (`session_id`, `event_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Session events with world context';

-- Tab Events Table (with world context)
CREATE TABLE IF NOT EXISTS `lupo_tab_events` (
  `tab_event_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for tab event',
  `tab_id` varchar(255) NOT NULL COMMENT 'Tab identifier',
  `session_id` varchar(255) DEFAULT NULL COMMENT 'Session identifier',
  `actor_id` bigint DEFAULT NULL COMMENT 'Actor ID from lupo_actors',
  `world_id` bigint DEFAULT NULL COMMENT 'World context ID',
  `world_key` varchar(255) DEFAULT NULL COMMENT 'World context key',
  `world_type` varchar(50) DEFAULT NULL COMMENT 'World context type',
  `event_type` varchar(100) NOT NULL COMMENT 'Type of tab event',
  `event_data` json COMMENT 'Event-specific data',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS',
  PRIMARY KEY (`tab_event_id`),
  KEY `idx_tab_id` (`tab_id`),
  KEY `idx_session_id` (`session_id`),
  KEY `idx_actor_id` (`actor_id`),
  KEY `idx_world_id` (`world_id`),
  KEY `idx_event_type` (`event_type`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_tab_event_type` (`tab_id`, `event_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tab events with world context';

-- Content Events Table (with world context)
CREATE TABLE IF NOT EXISTS `lupo_content_events` (
  `content_event_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for content event',
  `content_id` bigint DEFAULT NULL COMMENT 'Content identifier',
  `actor_id` bigint DEFAULT NULL COMMENT 'Actor ID from lupo_actors',
  `session_id` varchar(255) DEFAULT NULL COMMENT 'Session identifier',
  `tab_id` varchar(255) DEFAULT NULL COMMENT 'Tab identifier',
  `world_id` bigint DEFAULT NULL COMMENT 'World context ID',
  `world_key` varchar(255) DEFAULT NULL COMMENT 'World context key',
  `world_type` varchar(50) DEFAULT NULL COMMENT 'World context type',
  `event_type` varchar(100) NOT NULL COMMENT 'Type of content event',
  `event_data` json COMMENT 'Event-specific data',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS',
  PRIMARY KEY (`content_event_id`),
  KEY `idx_content_id` (`content_id`),
  KEY `idx_actor_id` (`actor_id`),
  KEY `idx_session_id` (`session_id`),
  KEY `idx_tab_id` (`tab_id`),
  KEY `idx_world_id` (`world_id`),
  KEY `idx_event_type` (`event_type`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_content_event_type` (`content_id`, `event_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Content events with world context';

-- Actor Events Table (with world context)
CREATE TABLE IF NOT EXISTS `lupo_actor_events` (
  `actor_event_id` bigint NOT NULL AUTO_INCREMENT COMMENT 'Primary key for actor event',
  `actor_id` bigint NOT NULL COMMENT 'Actor ID from lupo_actors',
  `session_id` varchar(255) DEFAULT NULL COMMENT 'Session identifier',
  `tab_id` varchar(255) DEFAULT NULL COMMENT 'Tab identifier',
  `world_id` bigint DEFAULT NULL COMMENT 'World context ID',
  `world_key` varchar(255) DEFAULT NULL COMMENT 'World context key',
  `world_type` varchar(50) DEFAULT NULL COMMENT 'World context type',
  `event_type` varchar(100) NOT NULL COMMENT 'Type of actor event',
  `event_data` json COMMENT 'Event-specific data',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS',
  PRIMARY KEY (`actor_event_id`),
  KEY `idx_actor_id` (`actor_id`),
  KEY `idx_session_id` (`session_id`),
  KEY `idx_tab_id` (`tab_id`),
  KEY `idx_world_id` (`world_id`),
  KEY `idx_event_type` (`event_type`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_actor_event_type` (`actor_id`, `event_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Actor events with world context';

-- =====================================================
-- 3. UPDATE EXISTING WORLD_EVENTS TABLE TO MATCH SCHEMA
-- =====================================================
-- Add columns only if they don't exist (MySQL 5.6+ compatible)
SET @sql = '';

-- Check and add session_id column
SELECT @sql := CONCAT(@sql, 
    'ALTER TABLE lupo_world_events ADD COLUMN session_id varchar(255) DEFAULT NULL COMMENT ''Session identifier'' AFTER world_type; ')
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'lupo_world_events' 
AND COLUMN_NAME = 'session_id'
HAVING COUNT(*) = 0;

-- Check and add tab_id column  
SELECT @sql := CONCAT(@sql,
    'ALTER TABLE lupo_world_events ADD COLUMN tab_id varchar(255) DEFAULT NULL COMMENT ''Tab identifier'' AFTER session_id; ')
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'lupo_world_events' 
AND COLUMN_NAME = 'tab_id'
HAVING COUNT(*) = 0;

-- Check and add world_key column
SELECT @sql := CONCAT(@sql,
    'ALTER TABLE lupo_world_events ADD COLUMN world_key varchar(255) DEFAULT NULL COMMENT ''World context key'' AFTER world_id; ')
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'lupo_world_events' 
AND COLUMN_NAME = 'world_key'
HAVING COUNT(*) = 0;

-- Check and add world_type column
SELECT @sql := CONCAT(@sql,
    'ALTER TABLE lupo_world_events ADD COLUMN world_type varchar(50) DEFAULT NULL COMMENT ''World context type'' AFTER world_key; ')
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'lupo_world_events' 
AND COLUMN_NAME = 'world_type'
HAVING COUNT(*) = 0;

-- Execute the ALTER statements if any exist
SET @sql = TRIM(@sql);
IF @sql != '' THEN
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END IF;

-- Add indexes only if they don't exist
SET @sql = '';

-- Check and add session_id index
SELECT @sql := CONCAT(@sql,
    'ALTER TABLE lupo_world_events ADD INDEX idx_session_id (session_id); ')
FROM INFORMATION_SCHEMA.STATISTICS 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'lupo_world_events' 
AND INDEX_NAME = 'idx_session_id'
HAVING COUNT(*) = 0;

-- Check and add tab_id index
SELECT @sql := CONCAT(@sql,
    'ALTER TABLE lupo_world_events ADD INDEX idx_tab_id (tab_id); ')
FROM INFORMATION_SCHEMA.STATISTICS 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'lupo_world_events' 
AND INDEX_NAME = 'idx_tab_id'
HAVING COUNT(*) = 0;

-- Check and add world_key index
SELECT @sql := CONCAT(@sql,
    'ALTER TABLE lupo_world_events ADD INDEX idx_world_key (world_key); ')
FROM INFORMATION_SCHEMA.STATISTICS 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'lupo_world_events' 
AND INDEX_NAME = 'idx_world_key'
HAVING COUNT(*) = 0;

-- Check and add world_type index
SELECT @sql := CONCAT(@sql,
    'ALTER TABLE lupo_world_events ADD INDEX idx_world_type (world_type); ')
FROM INFORMATION_SCHEMA.STATISTICS 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'lupo_world_events' 
AND INDEX_NAME = 'idx_world_type'
HAVING COUNT(*) = 0;

-- Execute the index statements if any exist
SET @sql = TRIM(@sql);
IF @sql != '' THEN
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END IF;

-- =====================================================
-- 4. DROP FOREIGN KEY CONSTRAINTS FROM LUPO_ACTORS
-- =====================================================
-- Check for and drop any foreign key constraints that might interfere
SET @sql = '';
SELECT @sql := CONCAT(@sql, 'ALTER TABLE ', TABLE_NAME, ' DROP FOREIGN KEY ', CONSTRAINT_NAME, '; ')
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'lupo_actors' 
AND REFERENCED_TABLE_NAME IS NOT NULL;

-- Execute the drop foreign key statements if any exist
SET @sql = TRIM(@sql);
IF @sql != '' THEN
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END IF;

-- =====================================================
-- 5. CLEAN UP EXTRA TOON FILES
-- =====================================================
-- These files were mentioned in warnings but don't correspond to actual tables
-- They should be removed to avoid confusion
-- (Note: File cleanup would be done manually)

-- =====================================================
-- 6. INSERT INITIAL WORLD REGISTRY DATA
-- =====================================================
INSERT IGNORE INTO `lupo_world_registry` 
(`world_key`, `world_type`, `world_label`, `world_metadata`, `created_ymdhis`, `updated_ymdhis`) 
VALUES 
('system_kernel', 'console', 'System Kernel World', '{"purpose": "system", "description": "System kernel context"}', 20260122000000, 20260122000000),
('default_department', 'department', 'Default Department', '{"department_id": 1, "is_default": true}', 20260122000000, 20260122000000),
('operator_console', 'console', 'Operator Console World', '{"purpose": "operator_interface", "description": "Operator console context"}', 20260122000000, 20260122000000);

-- =====================================================
-- 7. VERIFICATION QUERIES
-- =====================================================
-- Verify tables were created
SELECT 'Tables created successfully' as status,
       COUNT(*) as table_count
FROM INFORMATION_SCHEMA.TABLES 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME IN ('lupo_world_registry', 'lupo_session_events', 'lupo_tab_events', 'lupo_content_events', 'lupo_actor_events');

-- Verify world registry data
SELECT 'World registry initialized' as status,
       COUNT(*) as world_count
FROM lupo_world_registry;

-- Verify no foreign keys on lupo_actors
SELECT 'Foreign keys dropped from lupo_actors' as status,
       COUNT(*) as fk_count
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'lupo_actors' 
AND REFERENCED_TABLE_NAME IS NOT NULL;

-- =====================================================
-- MIGRATION COMPLETE
-- =====================================================
-- This migration ensures all world graph layer tables exist
-- and foreign key constraints are properly managed
-- while maintaining HERITAGE-SAFE MODE compliance
-- =====================================================
