-- One-time upgrade script: Lupopedia 4.0.x feature migration (2026-03-28)
-- Adds missing columns, creates new tables, and drops obsolete daily/monthly tables

-- 1. Add new columns to lupo_auth_users (if not present)
ALTER TABLE lupo_auth_users 
  ADD COLUMN two_factor_secret varchar(255) DEFAULT NULL AFTER deleted_ymdhis,
  ADD COLUMN two_factor_enabled tinyint NOT NULL DEFAULT 0 AFTER two_factor_secret,
  ADD COLUMN two_factor_backup_codes text DEFAULT NULL AFTER two_factor_enabled,
  ADD COLUMN timezone_offset decimal(4,2) DEFAULT '0.00' AFTER two_factor_backup_codes,
  ADD COLUMN timezone_name varchar(100) DEFAULT 'UTC' AFTER timezone_offset;

-- 2. Add geolocation columns to lupo_visits (if not present)
ALTER TABLE lupo_visits 
  ADD COLUMN country_code char(2) DEFAULT NULL AFTER deleted_ymdhis,
  ADD COLUMN city varchar(100) DEFAULT NULL AFTER country_code,
  ADD COLUMN latitude decimal(10,8) DEFAULT NULL AFTER city,
  ADD COLUMN longitude decimal(11,8) DEFAULT NULL AFTER latitude,
  ADD COLUMN geolocation_source varchar(50) DEFAULT NULL AFTER longitude,
  ADD INDEX idx_country (country_code);

-- 3. Create new tables if not present
CREATE TABLE IF NOT EXISTS lupo_two_factor_audit (
  two_factor_audit_id bigint NOT NULL AUTO_INCREMENT,
  auth_user_id bigint NOT NULL,
  action varchar(50) NOT NULL,
  ip_address varchar(45),
  user_agent text,
  created_ymdhis bigint NOT NULL,
  PRIMARY KEY (two_factor_audit_id),
  INDEX idx_user_id (auth_user_id),
  INDEX idx_created (created_ymdhis)
);

CREATE TABLE IF NOT EXISTS lupo_magic_link_tokens (
  magic_link_token_id bigint NOT NULL AUTO_INCREMENT,
  email varchar(255) NOT NULL,
  token char(64) NOT NULL,
  expires_ymdhis bigint NOT NULL,
  used tinyint NOT NULL DEFAULT 0,
  created_ymdhis bigint NOT NULL,
  PRIMARY KEY (magic_link_token_id),
  UNIQUE KEY idx_token (token),
  INDEX idx_email (email),
  INDEX idx_expires (expires_ymdhis)
);

CREATE TABLE IF NOT EXISTS lupo_auth_rate_limits (
  auth_rate_limit_id bigint NOT NULL AUTO_INCREMENT,
  identifier varchar(255) NOT NULL,
  attempt_type varchar(50),
  attempted_ymdhis bigint NOT NULL,
  PRIMARY KEY (auth_rate_limit_id),
  INDEX idx_identifier (identifier),
  INDEX idx_attempt_time (attempted_ymdhis)
);

-- 4. Drop obsolete daily/monthly tables if they exist
DROP TABLE IF EXISTS lupo_visits_daily;
DROP TABLE IF EXISTS lupo_visits_monthly;
DROP TABLE IF EXISTS lupo_referers_daily;
DROP TABLE IF EXISTS lupo_referers_monthly;

-- 5. (Optional) Add/modify columns in lupo_referers to match canonical schema
-- (No changes needed if already matches canonical structure)

-- End of upgrade script
