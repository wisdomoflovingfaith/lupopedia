-- add_featured_flag_4.0.29.sql
-- Purpose: Add is_featured column to lupo_contents for v4.0.29
-- Alignment: Essential for Release 4.0.29 finalization

START TRANSACTION;

-- Add column if it doesn't exist
SET @dbname = DATABASE();
SET @tablename = 'lupo_contents';
SET @columnname = 'is_featured';
SET @preparedStatement = (SELECT IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
   WHERE TABLE_SCHEMA = @dbname
     AND TABLE_NAME = @tablename
     AND COLUMN_NAME = @columnname) > 0,
  'SELECT 1',
  'ALTER TABLE lupo_contents ADD COLUMN is_featured TINYINT(1) DEFAULT 0 AFTER is_deleted'
));

PREPARE stmt FROM @preparedStatement;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

COMMIT;
