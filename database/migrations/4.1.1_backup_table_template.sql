-- ============================================================
-- Backup Table Template
-- Version: 4.1.1
-- Purpose: Standardized template for creating backup tables
-- Doctrine: No foreign keys, temporal integrity, signed integers
-- ============================================================

-- ============================================================
-- Usage Instructions:
-- 1. Replace {TABLE_NAME} with actual table name
-- 2. Replace {TABLE_DESCRIPTION} with actual table description
-- 3. Replace {COLUMN_DEFINITIONS} with actual column definitions
-- 4. Replace {INDEX_DEFINITIONS} with actual index definitions
-- ============================================================

-- ============================================================
-- Backup Table Structure Template
-- ============================================================

CREATE TABLE `{TABLE_NAME}_backup` (
  {COLUMN_DEFINITIONS}
  {INDEX_DEFINITIONS}
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Backup table for {TABLE_NAME}. {TABLE_DESCRIPTION}';

-- ============================================================
-- Data Migration Script Template
-- ============================================================

-- Step 1: Create backup table
-- (Run the CREATE TABLE statement above)

-- Step 2: Copy existing data to backup
INSERT INTO `{TABLE_NAME}_backup` 
SELECT * FROM `{TABLE_NAME}`;

-- Step 3: Verify backup data count
SELECT 
  '{TABLE_NAME}' as original_table,
  COUNT(*) as original_count
FROM `{TABLE_NAME}`
UNION ALL
SELECT 
  '{TABLE_NAME}_backup' as backup_table,
  COUNT(*) as backup_count
FROM `{TABLE_NAME}_backup`;

-- Step 4: Optional - Add backup metadata
CREATE TABLE IF NOT EXISTS `lupo_backup_metadata` (
  backup_id bigint PRIMARY KEY AUTO_INCREMENT,
  table_name varchar(255) NOT NULL,
  backup_table_name varchar(255) NOT NULL,
  backup_reason varchar(100),
  backup_ymdhis bigint NOT NULL,
  record_count bigint NOT NULL,
  backup_size_mb decimal(10,2),
  created_ymdhis bigint NOT NULL,
  INDEX idx_table_name (table_name),
  INDEX idx_backup_ymdhis (backup_ymdhis)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Metadata for table backups';

-- Step 5: Record backup metadata
INSERT INTO `lupo_backup_metadata` 
  (table_name, backup_table_name, backup_reason, backup_ymdhis, record_count, created_ymdhis)
VALUES 
  ('{TABLE_NAME}', '{TABLE_NAME}_backup', '{BACKUP_REASON}', {BACKUP_TIMESTAMP}, 
   (SELECT COUNT(*) FROM `{TABLE_NAME}_backup`), {CURRENT_TIMESTAMP});

-- ============================================================
-- Restore Script Template
-- ============================================================

-- Step 1: Verify backup exists
SELECT COUNT(*) as backup_exists 
FROM `lupo_backup_metadata` 
WHERE table_name = '{TABLE_NAME}' AND backup_table_name = '{TABLE_NAME}_backup';

-- Step 2: Optional - Create additional backup before restore
-- CREATE TABLE `{TABLE_NAME}_pre_restore_backup` AS SELECT * FROM `{TABLE_NAME}`;

-- Step 3: Clear original table
DELETE FROM `{TABLE_NAME}`;

-- Step 4: Restore data from backup
INSERT INTO `{TABLE_NAME}` 
SELECT * FROM `{TABLE_NAME}_backup`;

-- Step 5: Verify restore
SELECT 
  '{TABLE_NAME}' as table_name,
  (SELECT COUNT(*) FROM `{TABLE_NAME}`) as restored_count,
  (SELECT COUNT(*) FROM `{TABLE_NAME}_backup`) as backup_count,
  CASE 
    WHEN (SELECT COUNT(*) FROM `{TABLE_NAME}`) = (SELECT COUNT(*) FROM `{TABLE_NAME}_backup') 
    THEN 'SUCCESS' 
    ELSE 'MISMATCH' 
  END as restore_status;

-- ============================================================
-- Cleanup Script Template
-- ============================================================

-- Step 1: Verify restore was successful
-- (Run the verification query above)

-- Step 2: Drop backup table (optional - keep for safety)
-- DROP TABLE IF EXISTS `{TABLE_NAME}_backup`;

-- Step 3: Update backup metadata
UPDATE `lupo_backup_metadata` 
SET restore_ymdhis = {CURRENT_TIMESTAMP},
    restore_status = 'COMPLETED'
WHERE table_name = '{TABLE_NAME}' AND backup_table_name = '{TABLE_NAME}_backup';

-- ============================================================
-- Example Usage for lupo_actors table
-- ============================================================

/*
-- Example backup table creation
CREATE TABLE `lupo_actors_backup` (
  actor_id bigint PRIMARY KEY AUTO_INCREMENT,
  email varchar(255) UNIQUE,
  type enum('human', 'agent', 'service') NOT NULL,
  slug varchar(255) UNIQUE,
  name varchar(255),
  metadata text,
  is_active tinyint DEFAULT 1,
  is_deleted tinyint DEFAULT 0,
  deleted_ymdhis bigint,
  actor_source_id bigint,
  actor_source_type varchar(20),
  created_ymdhis bigint NOT NULL,
  updated_ymdhis bigint NOT NULL,
  INDEX idx_type (type),
  INDEX idx_email (email),
  INDEX idx_slug (slug),
  INDEX idx_created_ymdhis (created_ymdhis),
  INDEX idx_is_active (is_active),
  INDEX idx_is_deleted (is_deleted)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Backup table for lupo_actors. All actor records.';

-- Example data migration
INSERT INTO `lupo_actors_backup` 
SELECT * FROM `lupo_actors`;

-- Example restore
DELETE FROM `lupo_actors`;
INSERT INTO `lupo_actors` 
SELECT * FROM `lupo_actors_backup`;
*/

-- ============================================================
-- Automated Backup Procedure
-- ============================================================

-- ============================================================
-- Automated Backup Procedure
-- ============================================================

DELIMITER //

CREATE PROCEDURE CreateBackupTable(
  IN p_table_name VARCHAR(255),
  IN p_backup_reason VARCHAR(100),
  IN p_current_timestamp BIGINT
)
BEGIN
  DECLARE v_backup_table_name VARCHAR(255);
  DECLARE v_sql TEXT;
  DECLARE v_record_count BIGINT;
  
  -- Set backup table name
  SET v_backup_table_name = CONCAT(p_table_name, '_backup_', DATE_FORMAT(p_current_timestamp, '%Y%m%d%H%i%s'));
  
  -- Create backup table
  SET v_sql = CONCAT('CREATE TABLE `', v_backup_table_name, '` AS SELECT * FROM `', p_table_name, '`');
  SET @sql = v_sql;
  PREPARE stmt FROM @sql;
  EXECUTE stmt;
  DEALLOCATE PREPARE stmt;
  
  -- Get record count
  SET v_sql = CONCAT('SELECT COUNT(*) INTO @record_count FROM `', v_backup_table_name, '`');
  SET @sql = v_sql;
  PREPARE stmt FROM @sql;
  EXECUTE stmt;
  DEALLOCATE PREPARE stmt;
  
  -- Record backup metadata
  INSERT INTO `lupo_backup_metadata` 
    (table_name, backup_table_name, backup_reason, backup_ymdhis, record_count, created_ymdhis)
  VALUES 
    (p_table_name, v_backup_table_name, p_backup_reason, p_current_timestamp, @record_count, p_current_timestamp);
  
  SELECT 
    p_table_name as original_table,
    v_backup_table_name as backup_table,
    @record_count as record_count,
    'BACKUP_CREATED' as status;
END //

DELIMITER ;

DELIMITER ;

-- ============================================================
-- Usage Example for Automated Backup
-- ============================================================

-- Backup lupo_actors table
-- CALL CreateBackupTable('lupo_actors', 'schema_migration', UNIX_TIMESTAMP());

-- Backup lupo_agents table  
-- CALL CreateBackupTable('lupo_agents', 'agent_system_update', UNIX_TIMESTAMP());

-- ============================================================
-- Notes and Best Practices
-- ============================================================

/*
1. Always verify backup exists before dropping original tables
2. Test restore procedures in non-production environments
3. Keep backup metadata for audit trails
4. Use timestamped backup names for multiple backups
5. Consider storage requirements for large tables
6. Document backup reasons for future reference
7. Test data integrity after restore operations
8. Use transactions where possible for atomic operations
9. Monitor backup table sizes and cleanup old backups
10. Ensure proper permissions for backup operations
*/
