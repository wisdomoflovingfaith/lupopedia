-- One-time migration: Rules system (lupo_rules, lupo_rule_targets, lupo_rule_logs) for 4.0.68.
-- Doctrine: no foreign keys; BIGINT timestamps (YYYYMMDDHHIISS); no triggers.
-- Table prefix: apply to your LUPO_TABLE_PREFIX (e.g. lupo_).

-- lupo_rules: canonical registry of rules (rule_id from registry / explicit in seed; no AUTO_INCREMENT)
CREATE TABLE IF NOT EXISTS lupo_rules (
  rule_id bigint NOT NULL,
  rule_name varchar(255) NOT NULL,
  rule_description text,
  rule_type varchar(64) NOT NULL,
  rule_script text NOT NULL,
  rule_version bigint NOT NULL DEFAULT 1,
  created_ymdhis bigint NOT NULL,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (rule_id)
);
CREATE INDEX lupo_rules_idx_rule_type ON lupo_rules (rule_type);
CREATE INDEX lupo_rules_idx_rule_name ON lupo_rules (rule_name);
CREATE INDEX lupo_rules_idx_is_deleted ON lupo_rules (is_deleted);

-- lupo_rule_targets: attaches rules to any node (actors, channels, departments, etc.)
CREATE TABLE IF NOT EXISTS lupo_rule_targets (
  rule_target_id bigint NOT NULL AUTO_INCREMENT,
  rule_id bigint NOT NULL,
  target_table varchar(255) NOT NULL,
  target_id bigint NOT NULL,
  applied_by_actor_id bigint DEFAULT NULL,
  priority int NOT NULL DEFAULT 100,
  created_ymdhis bigint NOT NULL,
  updated_ymdhis bigint NOT NULL,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (rule_target_id)
);
CREATE INDEX lupo_rule_targets_idx_rule_target ON lupo_rule_targets (rule_id, target_table, target_id);
CREATE INDEX lupo_rule_targets_idx_target ON lupo_rule_targets (target_table, target_id);
CREATE INDEX lupo_rule_targets_idx_is_deleted ON lupo_rule_targets (is_deleted);

-- lupo_rule_logs: audit trail of rule evaluation
CREATE TABLE IF NOT EXISTS lupo_rule_logs (
  rule_log_id bigint NOT NULL AUTO_INCREMENT,
  rule_id bigint NOT NULL,
  target_table varchar(255) NOT NULL,
  target_id bigint NOT NULL,
  actor_id bigint NOT NULL,
  instance_id bigint DEFAULT 0,
  event_type varchar(64) NOT NULL,
  event_details text,
  created_ymdhis bigint NOT NULL,
  PRIMARY KEY (rule_log_id)
);
CREATE INDEX lupo_rule_logs_idx_rule_id ON lupo_rule_logs (rule_id);
CREATE INDEX lupo_rule_logs_idx_target ON lupo_rule_logs (target_table, target_id);
CREATE INDEX lupo_rule_logs_idx_actor_id ON lupo_rule_logs (actor_id);
CREATE INDEX lupo_rule_logs_idx_created_ymdhis ON lupo_rule_logs (created_ymdhis);
