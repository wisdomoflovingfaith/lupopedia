-- Phase 2: Session Optimization
-- Target: Merge lupo_session_recovery into lupo_sessions

-- 1. Add recovery columns to lupo_sessions
ALTER TABLE lupo_sessions ADD COLUMN recovery_attempts INT DEFAULT 0;
ALTER TABLE lupo_sessions ADD COLUMN recovery_data JSON;

-- 2. Migrate data
-- Since we are consolidating, we map recovery_id data if session_id matches
UPDATE lupo_sessions s
INNER JOIN lupo_session_recovery r ON s.session_id = r.session_id
SET s.recovery_attempts = r.recovery_attempts,
    s.recovery_data = JSON_OBJECT(
        'state_snapshot', r.state_snapshot,
        'context_data', r.context_data,
        'last_activity', r.last_activity_ymdhis
    );

-- 3. Drop session recovery table
DROP TABLE IF EXISTS lupo_session_recovery;
