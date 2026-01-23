-- Version: 4.2.0
-- Purpose: Schema freeze monitoring (optional MySQL EVENT)
-- Date: 2026-01-20
-- Doctrine note: TABLE_COUNT_DOCTRINE prefers application-level enforcement.
--   This EVENT is database-scheduled logic. Run only if CAPTAIN_WOLFIE and
--   LILITH approve. Requires: SET GLOBAL event_scheduler = ON;

-- =============================================================================
-- OPTIONAL: schema_freeze_enforcement_4_2_0
-- Logs to lupo_system_logs when table count > 180. lupo_system_alerts not used.
-- =============================================================================

DELIMITER $$

DROP EVENT IF EXISTS schema_freeze_enforcement_4_2_0$$

CREATE EVENT schema_freeze_enforcement_4_2_0
ON SCHEDULE EVERY 1 HOUR
DO
BEGIN
    DECLARE cnt INT;

    SELECT COUNT(*) INTO cnt
    FROM information_schema.tables
    WHERE table_schema = DATABASE();

    IF cnt > 180 THEN
        INSERT INTO lupo_system_logs
        (event_type, severity, actor_slug, message, context_json, created_ymdhis)
        VALUES (
            'doctrine',
            'critical',
            'LILITH',
            'Schema freeze violation: table count exceeded',
            JSON_OBJECT('table_count', cnt, 'limit', 180, 'version', '4.2.0'),
            CAST(DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS UNSIGNED)
        );
    END IF;
END$$

DELIMITER ;

-- Verify: SHOW EVENTS LIKE 'schema_freeze_enforcement_4_2_0';
