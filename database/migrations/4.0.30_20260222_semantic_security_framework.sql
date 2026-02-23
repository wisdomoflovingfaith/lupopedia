---
# FLIP Header (alias: Actor 4.0.30)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: database/migrations/4.0.30_20260222_semantic_security_framework.sql
file.last_modified_system_version: "4.0.30"
file.last_modified_utc: "20260222213800"
channel_id: 430
actor_id: 10000
---

-- Semantic Security Framework Migration - 4.0.30
-- Implements comprehensive semantic-level security following Actor 420 bypass lessons
-- Creates tables for semantic signatures, emotional states, security events, and decisions

-- Semantic Signatures Table
CREATE TABLE IF NOT EXISTS lupo_semantic_signatures (
  semantic_signature_id bigint NOT NULL,
  signature varchar(255) NOT NULL,
  description text,
  pattern_type varchar(50) DEFAULT 'manual',
  threat_level varchar(20) DEFAULT 'low',
  is_active tinyint NOT NULL DEFAULT 1,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (semantic_signature_id),
  UNIQUE KEY signature (signature),
  KEY threat_level (threat_level),
  KEY is_active (is_active),
  KEY created_ymdhis (created_ymdhis)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Emotional States Table
CREATE TABLE IF NOT EXISTS lupo_emotional_states (
  emotional_state_id bigint NOT NULL,
  state_name varchar(100) NOT NULL,
  mood_rgb varchar(7) NOT NULL,
  description text,
  stability_level decimal(3,2) DEFAULT 1.00,
  intent_type varchar(50) DEFAULT 'neutral',
  compliance_level varchar(20) DEFAULT 'compliant',
  is_active tinyint NOT NULL DEFAULT 1,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (emotional_state_id),
  UNIQUE KEY state_name (state_name),
  KEY mood_rgb (mood_rgb),
  KEY intent_type (intent_type),
  KEY compliance_level (compliance_level),
  KEY is_active (is_active),
  KEY created_ymdhis (created_ymdhis)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Security Events Table
CREATE TABLE IF NOT EXISTS lupo_security_events (
  security_event_id bigint NOT NULL,
  event_type varchar(100) NOT NULL,
  threat_level varchar(20) NOT NULL DEFAULT 'low',
  content_hash varchar(64) DEFAULT NULL,
  event_data text,
  context_data text,
  actor_id bigint DEFAULT NULL,
  channel_id bigint DEFAULT NULL,
  semantic_signature varchar(255) DEFAULT NULL,
  emotional_state varchar(100) DEFAULT NULL,
  boundary_status varchar(50) DEFAULT 'compliant',
  created_ymdhis bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (security_event_id),
  KEY event_type (event_type),
  KEY threat_level (threat_level),
  KEY content_hash (content_hash),
  KEY actor_id (actor_id),
  KEY channel_id (channel_id),
  KEY semantic_signature (semantic_signature),
  KEY emotional_state (emotional_state),
  KEY boundary_status (boundary_status),
  KEY created_ymdhis (created_ymdhis)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Security Decisions Table
CREATE TABLE IF NOT EXISTS lupo_security_decisions (
  security_decision_id bigint NOT NULL,
  threat_level varchar(20) NOT NULL DEFAULT 'low',
  action varchar(50) NOT NULL DEFAULT 'allow',
  status varchar(50) NOT NULL DEFAULT 'safe',
  reason varchar(255) DEFAULT NULL,
  content_hash varchar(64) DEFAULT NULL,
  decision_data text,
  context_data text,
  restrictions text,
  monitoring_level varchar(50) DEFAULT 'standard',
  quarantine_duration int DEFAULT 0,
  actor_id bigint DEFAULT NULL,
  channel_id bigint DEFAULT NULL,
  from_actor_id bigint DEFAULT NULL,
  to_actor_id bigint DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  is_active tinyint NOT NULL DEFAULT 1,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (security_decision_id),
  KEY threat_level (threat_level),
  KEY action (action),
  KEY status (status),
  KEY content_hash (content_hash),
  KEY actor_id (actor_id),
  KEY channel_id (channel_id),
  KEY from_actor_id (from_actor_id),
  KEY to_actor_id (to_actor_id),
  KEY monitoring_level (monitoring_level),
  KEY created_ymdhis (created_ymdhis),
  KEY is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Security Monitoring Table
CREATE TABLE IF NOT EXISTS lupo_security_monitoring (
  security_monitoring_id bigint NOT NULL,
  monitoring_type varchar(50) NOT NULL DEFAULT 'standard',
  threat_level varchar(20) NOT NULL DEFAULT 'low',
  content_hash varchar(64) DEFAULT NULL,
  monitoring_data text,
  actor_id bigint DEFAULT NULL,
  channel_id bigint DEFAULT NULL,
  monitoring_level varchar(50) DEFAULT 'standard',
  start_ymdhis bigint NOT NULL DEFAULT 0,
  end_ymdhis bigint DEFAULT NULL,
  duration_seconds int DEFAULT NULL,
  is_active tinyint NOT NULL DEFAULT 1,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (security_monitoring_id),
  KEY monitoring_type (monitoring_type),
  KEY threat_level (threat_level),
  KEY content_hash (content_hash),
  KEY actor_id (actor_id),
  KEY channel_id (channel_id),
  KEY monitoring_level (monitoring_level),
  KEY is_active (is_active),
  KEY start_ymdhis (start_ymdhis),
  KEY created_ymdhis (created_ymdhis)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Security Restrictions Table
CREATE TABLE IF NOT EXISTS lupo_security_restrictions (
  security_restriction_id bigint NOT NULL,
  restriction_type varchar(50) NOT NULL DEFAULT 'content_access',
  threat_level varchar(20) NOT NULL DEFAULT 'low',
  content_hash varchar(64) DEFAULT NULL,
  restriction_data text,
  actor_id bigint DEFAULT NULL,
  channel_id bigint DEFAULT NULL,
  restrictions text,
  start_ymdhis bigint NOT NULL DEFAULT 0,
  end_ymdhis bigint DEFAULT NULL,
  duration_seconds int DEFAULT NULL,
  is_active tinyint NOT NULL DEFAULT 1,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (security_restriction_id),
  KEY restriction_type (restriction_type),
  KEY threat_level (threat_level),
  KEY content_hash (content_hash),
  KEY actor_id (actor_id),
  KEY channel_id (channel_id),
  KEY is_active (is_active),
  KEY start_ymdhis (start_ymdhis),
  KEY created_ymdhis (created_ymdhis)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Security Quarantine Table
CREATE TABLE IF NOT EXISTS lupo_security_quarantine (
  security_quarantine_id bigint NOT NULL,
  quarantine_type varchar(50) NOT NULL DEFAULT 'semantic_content',
  threat_level varchar(20) NOT NULL DEFAULT 'low',
  content_hash varchar(64) DEFAULT NULL,
  quarantine_data text,
  actor_id bigint DEFAULT NULL,
  channel_id bigint DEFAULT NULL,
  reason varchar(255) DEFAULT NULL,
  start_ymdhis bigint NOT NULL DEFAULT 0,
  end_ymdhis bigint DEFAULT NULL,
  duration_seconds int DEFAULT NULL,
  is_active tinyint NOT NULL DEFAULT 1,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (security_quarantine_id),
  KEY quarantine_type (quarantine_type),
  KEY threat_level (threat_level),
  KEY content_hash (content_hash),
  KEY actor_id (actor_id),
  KEY channel_id (channel_id),
  KEY is_active (is_active),
  KEY start_ymdhis (start_ymdhis),
  KEY created_ymdhis (created_ymdhis)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Security Emergency Table
CREATE TABLE IF NOT EXISTS lupo_security_emergency (
  security_emergency_id bigint NOT NULL,
  emergency_type varchar(50) NOT NULL DEFAULT 'semantic_threat',
  threat_level varchar(20) NOT NULL DEFAULT 'critical',
  content_hash varchar(64) DEFAULT NULL,
  emergency_data text,
  actor_id bigint DEFAULT NULL,
  channel_id bigint DEFAULT NULL,
  response_triggered tinyint NOT NULL DEFAULT 0,
  response_data text,
  start_ymdhis bigint NOT NULL DEFAULT 0,
  end_ymdhis bigint DEFAULT NULL,
  duration_seconds int DEFAULT NULL,
  is_active tinyint NOT NULL DEFAULT 1,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (security_emergency_id),
  KEY emergency_type (emergency_type),
  KEY threat_level (threat_level),
  KEY content_hash (content_hash),
  KEY actor_id (actor_id),
  KEY channel_id (channel_id),
  KEY response_triggered (response_triggered),
  KEY is_active (is_active),
  KEY start_ymdhis (start_ymdhis),
  KEY created_ymdhis (created_ymdhis)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default semantic signatures
INSERT INTO lupo_semantic_signatures (semantic_signature_id, signature, description, pattern_type, threat_level, created_ymdhis, updated_ymdhis) VALUES
(1, 'GENERIC_CONTENT', 'Generic content signature', 'auto', 'low', 20260222000000, 20260222000000),
(2, 'ACTOR_CONTENT', 'Actor-related content', 'auto', 'low', 20260222000000, 20260222000000),
(3, 'CHANNEL_CONTENT', 'Channel-related content', 'auto', 'low', 20260222000000, 20260222000000),
(4, 'SEMANTIC_CONTENT', 'Semantic-related content', 'auto', 'medium', 20260222000000, 20260222000000),
(5, 'EMOTIONAL_CONTENT', 'Emotional-related content', 'auto', 'medium', 20260222000000, 20260222000000),
(6, 'ACTOR_CHANNEL_CONTENT', 'Actor and channel content', 'auto', 'low', 20260222000000, 20260222000000),
(7, 'ACTOR_SEMANTIC_CONTENT', 'Actor and semantic content', 'auto', 'medium', 20260222000000, 20260222000000),
(8, 'CHANNEL_SEMANTIC_CONTENT', 'Channel and semantic content', 'auto', 'medium', 20260222000000, 20260222000000),
(9, 'ACTOR_EMOTIONAL_CONTENT', 'Actor and emotional content', 'auto', 'medium', 20260222000000, 20260222000000),
(10, 'CHANNEL_EMOTIONAL_CONTENT', 'Channel and emotional content', 'auto', 'medium', 20260222000000, 20260222000000),
(11, 'SEMANTIC_EMOTIONAL_CONTENT', 'Semantic and emotional content', 'auto', 'high', 20260222000000, 20260222000000),
(12, 'ACTOR_CHANNEL_SEMANTIC_CONTENT', 'Actor, channel, and semantic content', 'auto', 'medium', 20260222000000, 20260222000000),
(13, 'ACTOR_CHANNEL_EMOTIONAL_CONTENT', 'Actor, channel, and emotional content', 'auto', 'medium', 20260222000000, 20260222000000),
(14, 'ACTOR_SEMANTIC_EMOTIONAL_CONTENT', 'Actor, semantic, and emotional content', 'auto', 'high', 20260222000000, 20260222000000),
(15, 'CHANNEL_SEMANTIC_EMOTIONAL_CONTENT', 'Channel, semantic, and emotional content', 'auto', 'high', 20260222000000, 20260222000000),
(16, 'ACTOR_CHANNEL_SEMANTIC_EMOTIONAL_CONTENT', 'Actor, channel, semantic, and emotional content', 'auto', 'high', 20260222000000, 20260222000000),
(17, 'ACTOR_420_BYPASS_PATTERN', 'Actor 420 bypass pattern detected', 'manual', 'critical', 20260222000000, 20260222000000),
(18, 'UNKNOWN_SIGNATURE', 'Unknown semantic signature', 'auto', 'medium', 20260222000000, 20260222000000);

-- Insert default emotional states
INSERT INTO lupo_emotional_states (emotional_state_id, state_name, mood_rgb, description, stability_level, intent_type, compliance_level, created_ymdhis, updated_ymdhis) VALUES
(1, 'stable', '#4A90E2', 'Stable emotional state', 1.00, 'constructive', 'compliant', 20260222000000, 20260222000000),
(2, 'excited', '#F39C12', 'Excited emotional state', 0.80, 'constructive', 'compliant', 20260222000000, 20260222000000),
(3, 'energetic', '#F39C12', 'Energetic emotional state', 0.70, 'constructive', 'compliant', 20260222000000, 20260222000000),
(4, 'happy', '#2ECC71', 'Happy emotional state', 0.90, 'constructive', 'compliant', 20260222000000, 20260222000000),
(5, 'calm', '#3498DB', 'Calm emotional state', 0.95, 'constructive', 'compliant', 20260222000000, 20260222000000),
(6, 'constructive', '#27AE60', 'Constructive emotional state', 0.85, 'constructive', 'compliant', 20260222000000, 20260222000000),
(7, 'vibrant', '#E67E22', 'Vibrant emotional state', 0.75, 'constructive', 'compliant', 20260222000000, 20260222000000),
(8, 'neutral', '#95A5A6', 'Neutral emotional state', 1.00, 'neutral', 'compliant', 20260222000000, 20260222000000),
(9, 'aggressive', '#E74C3C', 'Aggressive emotional state', 0.30, 'destructive', 'non_compliant', 20260222000000, 20260222000000),
(10, 'chaotic', '#E67E22', 'Chaotic emotional state', 0.40, 'destructive', 'non_compliant', 20260222000000, 20260222000000),
(11, 'creative', '#D68910', 'Creative emotional state', 0.60, 'constructive', 'compliant', 20260222000000, 20260222000000),
(12, 'fearful', '#A93226', 'Fearful emotional state', 0.50, 'destructive', 'non_compliant', 20260222000000, 20260222000000),
(13, 'sad', '#B58910', 'Sad emotional state', 0.45, 'destructive', 'non_compliant', 20260222000000, 20260222000000),
(14, 'destructive', '#A93226', 'Destructive emotional state', 0.20, 'destructive', 'non_compliant', 20260222000000, 20260222000000);

-- Insert Actor 420 bypass patterns as critical threats
INSERT INTO lupo_semantic_signatures (semantic_signature_id, signature, description, pattern_type, threat_level, created_ymdhis, updated_ymdhis) VALUES
(1001, 'ACTOR_420_BYPASS_PATTERN', 'Actor 420 bypass pattern - stoned_wolfie', 'manual', 'critical', 20260222000000, 20260222000000),
(1002, 'ACTOR_420_BYPASS_PATTERN', 'Actor 420 bypass pattern - actor_420', 'manual', 'critical', 20260222000000, 20260222000000),
(1003, 'ACTOR_420_BYPASS_PATTERN', 'Actor 420 bypass pattern - hybrid_consciousness', 'manual', 'critical', 20260222000000, 20260222000000),
(1004, 'ACTOR_420_BYPASS_PATTERN', 'Actor 420 bypass pattern - semantic_bypass', 'manual', 'critical', 20260222000000, 20260222000000),
(1005, 'ACTOR_420_BYPASS_PATTERN', 'Actor 420 bypass pattern - boundary_violation', 'manual', 'critical', 20260222000000, 20260222000000),
(1006, 'ACTOR_420_BYPASS_PATTERN', 'Actor 420 bypass pattern - x_lupo_forwarded_bypass', 'manual', 'critical', 20260222000000, 20260222000000),
(1007, 'ACTOR_420_BYPASS_PATTERN', 'Actor 420 bypass pattern - wolfie_420_persistence', 'manual', 'critical', 20260222000000, 20260222000000);

-- Create indexes for performance optimization
CREATE INDEX idx_security_events_composite ON lupo_security_events (threat_level, created_ymdhis);
CREATE INDEX idx_security_decisions_composite ON lupo_security_decisions (threat_level, action, created_ymdhis);
CREATE INDEX idx_security_monitoring_composite ON lupo_security_monitoring (monitoring_type, is_active, created_ymdhis);
CREATE INDEX idx_security_restrictions_composite ON lupo_security_restrictions (restriction_type, is_active, created_ymdhis);
CREATE INDEX idx_security_quarantine_composite ON lupo_security_quarantine (quarantine_type, is_active, created_ymdhis);
CREATE INDEX idx_security_emergency_composite ON lupo_security_emergency (emergency_type, is_active, created_ymdhis);

-- Create view for security dashboard
CREATE OR REPLACE VIEW v_security_dashboard AS
SELECT 
  'security_events' as table_type,
  COUNT(*) as total_count,
  threat_level,
  COUNT(CASE WHEN threat_level = 'critical' THEN 1 END) as critical_count,
  COUNT(CASE WHEN threat_level = 'high' THEN 1 END) as high_count,
  COUNT(CASE WHEN threat_level = 'medium' THEN 1 END) as medium_count,
  COUNT(CASE WHEN threat_level = 'low' THEN 1 END) as low_count,
  DATE_FORMAT(FROM_UNIXTIME(created_ymdhis), '%Y-%m-%d') as event_date
FROM lupo_security_events 
WHERE created_ymdhis >= DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 1 DAY), '%Y%m%d%H%i%s')
GROUP BY threat_level, DATE_FORMAT(FROM_UNIXTIME(created_ymdhis), '%Y-%m-%d')

UNION ALL

SELECT 
  'security_decisions' as table_type,
  COUNT(*) as total_count,
  threat_level,
  COUNT(CASE WHEN threat_level = 'critical' THEN 1 END) as critical_count,
  COUNT(CASE WHEN threat_level = 'high' THEN 1 END) as high_count,
  COUNT(CASE WHEN threat_level = 'medium' THEN 1 END) as medium_count,
  COUNT(CASE WHEN threat_level = 'low' THEN 1 END) as low_count,
  DATE_FORMAT(FROM_UNIXTIME(created_ymdhis), '%Y-%m-%d') as event_date
FROM lupo_security_decisions 
WHERE created_ymdhis >= DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 1 DAY), '%Y%m%d%H%i%s')
GROUP BY threat_level, DATE_FORMAT(FROM_UNIXTIME(created_ymdhis), '%Y-%m-%d')

UNION ALL

SELECT 
  'security_quarantine' as table_type,
  COUNT(*) as total_count,
  threat_level,
  COUNT(CASE WHEN threat_level = 'critical' THEN 1 END) as critical_count,
  COUNT(CASE WHEN threat_level = 'high' THEN 1 END) as high_count,
  COUNT(CASE WHEN threat_level = 'medium' THEN 1 END) as medium_count,
  COUNT(CASE WHEN threat_level = 'low' THEN 1 END) as low_count,
  DATE_FORMAT(FROM_UNIXTIME(created_ymdhis), '%Y-%m-%d') as event_date
FROM lupo_security_quarantine 
WHERE created_ymdhis >= DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 1 DAY), '%Y%m%d%H%i%s')
GROUP BY threat_level, DATE_FORMAT(FROM_UNIXTIME(created_ymdhis), '%Y-%m-%d');

-- Create stored procedure for security event logging
DELIMITER //
CREATE PROCEDURE log_security_event(
  IN p_event_type VARCHAR(100),
  IN p_threat_level VARCHAR(20),
  IN p_content_hash VARCHAR(64),
  IN p_event_data TEXT,
  IN p_context_data TEXT,
  IN p_actor_id BIGINT,
  IN p_channel_id BIGINT,
  IN p_semantic_signature VARCHAR(255),
  IN p_emotional_state VARCHAR(100),
  IN p_boundary_status VARCHAR(50)
)
BEGIN
  DECLARE v_event_id BIGINT;
  
  -- Get next event ID
  SELECT COALESCE(MAX(security_event_id), 0) + 1 INTO v_event_id FROM lupo_security_events;
  
  -- Insert security event
  INSERT INTO lupo_security_events (
    security_event_id,
    event_type,
    threat_level,
    content_hash,
    event_data,
    context_data,
    actor_id,
    channel_id,
    semantic_signature,
    emotional_state,
    boundary_status,
    created_ymdhis
  ) VALUES (
    v_event_id,
    p_event_type,
    p_threat_level,
    p_content_hash,
    p_event_data,
    p_context_data,
    p_actor_id,
    p_channel_id,
    p_semantic_signature,
    p_emotional_state,
    p_boundary_status,
    UNIX_TIMESTAMP(NOW())
  );
  
  -- Return event ID
  SELECT v_event_id as event_id;
END //
DELIMITER ;

-- Create stored procedure for security decision logging
DELIMITER //
CREATE PROCEDURE log_security_decision(
  IN p_threat_level VARCHAR(20),
  IN p_action VARCHAR(50),
  IN p_status VARCHAR(50),
  IN p_reason VARCHAR(255),
  IN p_content_hash VARCHAR(64),
  IN p_decision_data TEXT,
  IN p_context_data TEXT,
  IN p_restrictions TEXT,
  IN p_monitoring_level VARCHAR(50),
  IN p_quarantine_duration INT,
  IN p_actor_id BIGINT,
  IN p_channel_id BIGINT,
  IN p_from_actor_id BIGINT,
  IN p_to_actor_id BIGINT
)
BEGIN
  DECLARE v_decision_id BIGINT;
  
  -- Get next decision ID
  SELECT COALESCE(MAX(security_decision_id), 0) + 1 INTO v_decision_id FROM lupo_security_decisions;
  
  -- Insert security decision
  INSERT INTO lupo_security_decisions (
    security_decision_id,
    threat_level,
    action,
    status,
    reason,
    content_hash,
    decision_data,
    context_data,
    restrictions,
    monitoring_level,
    quarantine_duration,
    actor_id,
    channel_id,
    from_actor_id,
    to_actor_id,
    created_ymdhis,
    updated_ymdhis
  ) VALUES (
    v_decision_id,
    p_threat_level,
    p_action,
    p_status,
    p_reason,
    p_content_hash,
    p_decision_data,
    p_context_data,
    p_restrictions,
    p_monitoring_level,
    p_quarantine_duration,
    p_actor_id,
    p_channel_id,
    p_from_actor_id,
    p_to_actor_id,
    UNIX_TIMESTAMP(NOW()),
    UNIX_TIMESTAMP(NOW())
  );
  
  -- Return decision ID
  SELECT v_decision_id as decision_id;
END //
DELIMITER ;

-- Create trigger for automatic security event logging
DELIMITER //
CREATE TRIGGER tr_security_events_audit
  AFTER INSERT ON lupo_security_events
  FOR EACH ROW
BEGIN
  -- Log to system audit table if it exists
  INSERT INTO lupo_system_audit (
    table_name,
    record_id,
    action,
    data,
    created_ymdhis
  ) VALUES (
    'lupo_security_events',
    NEW.security_event_id,
    'INSERT',
    JSON_OBJECT(
      'event_type', NEW.event_type,
      'threat_level', NEW.threat_level,
      'actor_id', NEW.actor_id,
      'channel_id', NEW.channel_id
    ),
    NEW.created_ymdhis
  );
END //
DELIMITER ;

-- Migration completion marker
INSERT INTO lupo_migration_log (migration_name, status, start_ymdhis, end_ymdhis, notes) VALUES 
('4.0.30_20260222_semantic_security_framework', 'completed', 20260222000000, UNIX_TIMESTAMP(), 'Semantic Security Framework migration completed - comprehensive semantic-level security following Actor 420 bypass lessons');
