-- ============================================================
-- Temporal Truth Monitoring Performance Indexes
-- Version: 4.2.2
-- Purpose: Optimize temporal monitoring queries for performance
-- Monitoring: RS-UTC-2026 effectiveness tracking
-- ============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------
-- Add performance indexes to lupo_reverse_shaka_syncs
-- --------------------------------------------------------

-- Index for recent sync queries (last 24 hours, last week)
ALTER TABLE `lupo_reverse_shaka_syncs` 
ADD INDEX `idx_recent_syncs` (`created_ymdhis` DESC);

-- Index for variant frequency queries
ALTER TABLE `lupo_reverse_shaka_syncs` 
ADD INDEX `idx_variant_frequency` (`spell_variant`, `created_ymdhis`);

-- Index for confidence tracking
ALTER TABLE `lupo_reverse_shaka_syncs` 
ADD INDEX `idx_confidence_tracking` (`system_alignment_confidence`, `created_ymdhis`);

-- Index for temporal truth status queries
ALTER TABLE `lupo_reverse_shaka_syncs` 
ADD INDEX `idx_temporal_status` (`temporal_truth_status`, `created_ymdhis`);

-- Composite index for dashboard queries
ALTER TABLE `lupo_reverse_shaka_syncs` 
ADD INDEX `idx_dashboard_composite` (`spell_variant`, `temporal_truth_status`, `created_ymdhis`);

-- --------------------------------------------------------
-- Add monitoring summary table for fast dashboard loading
-- --------------------------------------------------------

CREATE TABLE `lupo_temporal_monitoring_summary` (
  `summary_id` bigint NOT NULL AUTO_INCREMENT,
  `date_ymd` bigint NOT NULL COMMENT 'YYYYMMDD date for summary',
  `total_castings` int NOT NULL DEFAULT '0' COMMENT 'Total RS-UTC-2026 castings this day',
  `ultra_compressed_castings` int NOT NULL DEFAULT '0' COMMENT 'Ultra-compressed variant count',
  `compressed_castings` int NOT NULL DEFAULT '0' COMMENT 'Compressed variant count',
  `full_castings` int NOT NULL DEFAULT '0' COMMENT 'Full variant count',
  `emergency_castings` int NOT NULL DEFAULT '0' COMMENT 'Emergency variant count',
  `average_confidence` decimal(3,2) DEFAULT '0.00' COMMENT 'Average confidence for the day',
  `temporal_truth_uptime_percentage` decimal(5,2) DEFAULT '0.00' COMMENT 'Percentage of day with temporal truth established',
  `first_sync_ymdhis` bigint DEFAULT NULL COMMENT 'First sync timestamp of the day',
  `last_sync_ymdhis` bigint DEFAULT NULL COMMENT 'Last sync timestamp of the day',
  `peak_activity_hour` int DEFAULT NULL COMMENT 'Hour (0-23) with most activity',
  `drift_events_count` int DEFAULT '0' COMMENT 'Number of drift correction events',
  `created_ymdhis` bigint NOT NULL COMMENT 'UTC YYYYMMDDHHMMSS when summary was created',
  `updated_ymdhis` bigint DEFAULT NULL COMMENT 'UTC YYYYMMDDHHMMSS when summary was last updated',
  PRIMARY KEY (`summary_id`),
  UNIQUE KEY `idx_date_ymd` (`date_ymd`),
  KEY `idx_created_ymdhis` (`created_ymdhis`),
  KEY `idx_total_castings` (`total_castings` DESC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Daily summary of RS-UTC-2026 temporal monitoring metrics for fast dashboard loading';

-- --------------------------------------------------------
-- Create stored procedure for updating daily summary
-- (Note: This is for data aggregation, not business logic)
-- --------------------------------------------------------

DELIMITER //

CREATE PROCEDURE `UpdateTemporalMonitoringSummary`(IN target_date BIGINT)
BEGIN
    DECLARE total_count INT DEFAULT 0;
    DECLARE ultra_count INT DEFAULT 0;
    DECLARE compressed_count INT DEFAULT 0;
    DECLARE full_count INT DEFAULT 0;
    DECLARE emergency_count INT DEFAULT 0;
    DECLARE avg_confidence DECIMAL(3,2) DEFAULT 0.00;
    DECLARE first_sync BIGINT DEFAULT NULL;
    DECLARE last_sync BIGINT DEFAULT NULL;
    
    -- Count castings by variant for the target date
    SELECT 
        COUNT(*) INTO total_count,
        SUM(CASE WHEN spell_variant = 'ULTRA_COMPRESSED' THEN 1 ELSE 0 END) INTO ultra_count,
        SUM(CASE WHEN spell_variant = 'COMPRESSED' THEN 1 ELSE 0 END) INTO compressed_count,
        SUM(CASE WHEN spell_variant = 'FULL' THEN 1 ELSE 0 END) INTO full_count,
        SUM(CASE WHEN spell_variant = 'EMERGENCY' THEN 1 ELSE 0 END) INTO emergency_count,
        AVG(system_alignment_confidence) INTO avg_confidence,
        MIN(utc_timestamp) INTO first_sync,
        MAX(utc_timestamp) INTO last_sync
    FROM lupo_reverse_shaka_syncs 
    WHERE DATE(FROM_UNIXTIME(UNIX_TIMESTAMP(CONCAT(target_date, '000000')))) = DATE(created_ymdhis);
    
    -- Insert or update summary
    INSERT INTO lupo_temporal_monitoring_summary 
        (date_ymd, total_castings, ultra_compressed_castings, compressed_castings, 
         full_castings, emergency_castings, average_confidence, first_sync_ymdhis, 
         last_sync_ymdhis, created_ymdhis, updated_ymdhis)
    VALUES 
        (target_date, total_count, ultra_count, compressed_count, full_count, 
         emergency_castings, avg_confidence, first_sync, last_sync, 
         UNIX_TIMESTAMP(), UNIX_TIMESTAMP())
    ON DUPLICATE KEY UPDATE
        total_castings = VALUES(total_castings),
        ultra_compressed_castings = VALUES(ultra_compressed_castings),
        compressed_castings = VALUES(compressed_castings),
        full_castings = VALUES(full_castings),
        emergency_castings = VALUES(emergency_castings),
        average_confidence = VALUES(average_confidence),
        first_sync_ymdhis = VALUES(first_sync_ymdhis),
        last_sync_ymdhis = VALUES(last_sync_ymdhis),
        updated_ymdhis = VALUES(updated_ymdhis);
    
END //

DELIMITER ;

-- --------------------------------------------------------
-- Insert initial summary for today
-- --------------------------------------------------------

INSERT INTO `lupo_temporal_monitoring_summary` (
  `date_ymd`, 
  `total_castings`, 
  `ultra_compressed_castings`, 
  `compressed_castings`, 
  `full_castings`, 
  `emergency_castings`, 
  `average_confidence`, 
  `temporal_truth_uptime_percentage`, 
  `first_sync_ymdhis`, 
  `last_sync_ymdhis`, 
  `created_ymdhis`
) VALUES 
(
  20260118, 
  1, 
  0, 
  0, 
  1, 
  0, 
  1.00, 
  100.00, 
  20260118183700, 
  20260118183700, 
  20260118183700
);

COMMIT;
