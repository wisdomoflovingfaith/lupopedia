-- One-time migration: lupo_orchestrator_rules (v4.0.73)
-- Doctrine: no FK/triggers; BIGINT timestamps; TINYINT without display width.
-- Run via scripts/run_one_time_sql.php or manually. Idempotent: CREATE TABLE IF NOT EXISTS.

CREATE TABLE IF NOT EXISTS lupo_orchestrator_rules (
  rule_id bigint NOT NULL AUTO_INCREMENT,
  rule_slug varchar(128) NOT NULL,
  orchestrator_actor varchar(64) NOT NULL,
  rule_set_version varchar(32) NOT NULL,
  applies_to_json text NOT NULL,
  enforcement_level varchar(32) NOT NULL DEFAULT 'strict',
  rule_content text NOT NULL,
  checksum varchar(64) NOT NULL,
  is_active tinyint NOT NULL DEFAULT 1,
  updated_ymdhis bigint NOT NULL,
  PRIMARY KEY (rule_id)
);
CREATE UNIQUE INDEX lupo_orchestrator_rules_uniq_slug ON lupo_orchestrator_rules (rule_slug);
CREATE INDEX lupo_orchestrator_rules_idx_actor_version ON lupo_orchestrator_rules (orchestrator_actor, rule_set_version);
CREATE INDEX lupo_orchestrator_rules_idx_active ON lupo_orchestrator_rules (is_active);
CREATE INDEX lupo_orchestrator_rules_idx_updated ON lupo_orchestrator_rules (updated_ymdhis);
