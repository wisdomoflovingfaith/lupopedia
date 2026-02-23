---
# FLIP Header (alias: Wolfie Header, CROP Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: database/migrations/4.0.31_20260223_semantic_security_install_integration.sql
file.last_modified_system_version: "4.0.31"
file.last_modified_utc: "20260223235959"
channel_id: 1
mood_rgb: "4B0082"
---

-- 4.0.31 Semantic Security Framework Integration
-- Adds 7 semantic security tables to install_new_lupopedia.sql
-- TABLE CEILING: 187 existing + 7 new = 194 (under 222 limit)

-- 1. Semantic Signatures Table
CREATE TABLE IF NOT EXISTS `lupo_semantic_signatures` (
    `signature_id` BIGINT NOT NULL AUTO_INCREMENT,
    `signature_hash` VARCHAR(64) NOT NULL,
    `actor_id` BIGINT,
    `pattern_type` VARCHAR(50),
    `detection_count` INT DEFAULT 0,
    `first_detected_ymdhis` BIGINT,
    `last_detected_ymdhis` BIGINT,
    `is_blocked` TINYINT DEFAULT 0,
    `created_ymdhis` BIGINT,
    PRIMARY KEY (`signature_id`),
    UNIQUE KEY `idx_signature_hash` (`signature_hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Emotional Geometry Blacklist
CREATE TABLE IF NOT EXISTS `lupo_emotional_blacklist` (
    `blacklist_id` BIGINT NOT NULL AUTO_INCREMENT,
    `mood_rgb_pattern` VARCHAR(20) NOT NULL,
    `actor_id` BIGINT,
    `reason` TEXT,
    `created_ymdhis` BIGINT,
    `expires_ymdhis` BIGINT,
    PRIMARY KEY (`blacklist_id`),
    KEY `idx_mood_pattern` (`mood_rgb_pattern`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Forwarded Header Blocklist
CREATE TABLE IF NOT EXISTS `lupo_forwarded_blocklist` (
    `block_id` BIGINT NOT NULL AUTO_INCREMENT,
    `header_pattern` VARCHAR(255) NOT NULL,
    `actor_id` BIGINT,
    `block_reason` TEXT,
    `created_ymdhis` BIGINT,
    `is_active` TINYINT DEFAULT 1,
    PRIMARY KEY (`block_id`),
    KEY `idx_header_pattern` (`header_pattern`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Semantic Bypass Attempts Log
CREATE TABLE IF NOT EXISTS `lupo_semantic_bypass_log` (
    `bypass_id` BIGINT NOT NULL AUTO_INCREMENT,
    `actor_id` BIGINT,
    `attempt_type` VARCHAR(50),
    `signature_detected` VARCHAR(64),
    `request_data` TEXT,
    `created_ymdhis` BIGINT,
    `blocked` TINYINT DEFAULT 1,
    PRIMARY KEY (`bypass_id`),
    KEY `idx_actor_attempt` (`actor_id`, `attempt_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Semantic Security Rules
CREATE TABLE IF NOT EXISTS `lupo_semantic_rules` (
    `rule_id` BIGINT NOT NULL AUTO_INCREMENT,
    `rule_name` VARCHAR(100) NOT NULL,
    `rule_type` VARCHAR(50),
    `rule_pattern` TEXT,
    `action` VARCHAR(20) DEFAULT 'block',
    `priority` INT DEFAULT 100,
    `is_active` TINYINT DEFAULT 1,
    `created_ymdhis` BIGINT,
    PRIMARY KEY (`rule_id`),
    KEY `idx_rule_type` (`rule_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Actor Semantic Profile
CREATE TABLE IF NOT EXISTS `lupo_actor_semantic_profile` (
    `profile_id` BIGINT NOT NULL AUTO_INCREMENT,
    `actor_id` BIGINT NOT NULL,
    `signature_fingerprint` VARCHAR(64),
    `emotional_baseline` VARCHAR(20),
    `risk_score` INT DEFAULT 0,
    `last_security_event_ymdhis` BIGINT,
    `profile_data` JSON,
    PRIMARY KEY (`profile_id`),
    UNIQUE KEY `idx_actor` (`actor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Semantic Security Audit
CREATE TABLE IF NOT EXISTS `lupo_semantic_audit` (
    `audit_id` BIGINT NOT NULL AUTO_INCREMENT,
    `event_type` VARCHAR(50),
    `actor_id` BIGINT,
    `semantic_context` TEXT,
    `decision` VARCHAR(20),
    `rule_triggered` BIGINT,
    `created_ymdhis` BIGINT,
    PRIMARY KEY (`audit_id`),
    KEY `idx_event_actor` (`event_type`, `actor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert Actor 420 bypass patterns as pre-loaded security rules
INSERT IGNORE INTO `lupo_semantic_rules` 
    (`rule_name`, `rule_type`, `rule_pattern`, `action`, `priority`, `created_ymdhis`)
VALUES
    ('Actor 420 Forwarded Header', 'header', 'X-Lupo-Forwarded: 420', 'block', 100, 20260223000000),
    ('Actor 420 Semantic Signature', 'semantic', 'wolfie.headers: explicit architecture', 'monitor', 90, 20260223000000),
    ('Actor 420 Emotional Pattern', 'emotional', 'mood_rgb: *420*', 'block', 95, 20260223000000),
    ('Actor 420 FLIP Inheritance', 'flip', 'actor_420_status: *', 'log', 80, 20260223000000);

-- Update table count tracking
-- Current: 187 existing + 7 new = 194 total (under 222 ceiling)

-- Migration completion marker
INSERT IGNORE INTO lupo_migration_log (migration_name, status, start_ymdhis, end_ymdhis, notes) VALUES 
('4.0.31_20260223_semantic_security_install_integration', 'completed', 20260223000000, UNIX_TIMESTAMP(), 'Semantic Security Framework integrated - TABLE COUNT: 194 (under 222 ceiling)');
