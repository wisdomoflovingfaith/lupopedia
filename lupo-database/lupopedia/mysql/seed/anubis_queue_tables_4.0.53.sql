-- ANUBIS queue tables (v4.0.53). Idempotent: CREATE TABLE IF NOT EXISTS.
-- Canonical schema is in install_new_lupopedia.sql; this seed ensures tables exist when run after older installs or standalone.
-- Indexes are created by install; omitted here so this file is safe to run after install without duplicate-key errors.

CREATE TABLE IF NOT EXISTS lupo_anubis_queue (
  queue_id bigint NOT NULL,
  file_path varchar(512) NOT NULL,
  file_hash varchar(64) DEFAULT NULL,
  file_content longtext,
  detected_utc bigint NOT NULL,
  priority tinyint DEFAULT 5,
  status varchar(32) DEFAULT 'pending',
  detection_method varchar(64) DEFAULT NULL,
  header_snapshot text,
  error_message text,
  attempts tinyint DEFAULT 0,
  last_attempt_utc bigint DEFAULT NULL,
  assigned_to_actor_id bigint DEFAULT NULL,
  filesystem_copy_exists tinyint DEFAULT 1,
  filesystem_backup_path varchar(512) DEFAULT NULL,
  created_utc bigint NOT NULL,
  updated_utc bigint NOT NULL,
  is_deleted tinyint DEFAULT 0,
  PRIMARY KEY (queue_id)
);

CREATE TABLE IF NOT EXISTS lupo_anubis_processing_log (
  log_id bigint NOT NULL,
  queue_id bigint NOT NULL,
  file_path varchar(512) NOT NULL,
  action varchar(64) NOT NULL,
  details text,
  actor_id bigint DEFAULT NULL,
  created_utc bigint NOT NULL,
  PRIMARY KEY (log_id)
);

CREATE TABLE IF NOT EXISTS lupo_anubis_recovery_attempts (
  attempt_id bigint NOT NULL,
  queue_id bigint NOT NULL,
  attempt_number tinyint NOT NULL,
  attempt_utc bigint NOT NULL,
  strategy varchar(64) DEFAULT NULL,
  success tinyint DEFAULT 0,
  generated_header text,
  error_details text,
  recovered_file_path varchar(512) DEFAULT NULL,
  PRIMARY KEY (attempt_id)
);

CREATE TABLE IF NOT EXISTS lupo_anubis_quarantine (
  quarantine_id bigint NOT NULL,
  queue_id bigint NOT NULL,
  file_path varchar(512) NOT NULL,
  file_hash varchar(64) DEFAULT NULL,
  file_content longtext,
  quarantine_path varchar(512) NOT NULL,
  reason varchar(255) NOT NULL,
  quarantined_utc bigint NOT NULL,
  expires_utc bigint DEFAULT NULL,
  reviewed_by_actor_id bigint DEFAULT NULL,
  reviewed_utc bigint DEFAULT NULL,
  resolution varchar(64) DEFAULT NULL,
  is_deleted tinyint DEFAULT 0,
  PRIMARY KEY (quarantine_id)
);
