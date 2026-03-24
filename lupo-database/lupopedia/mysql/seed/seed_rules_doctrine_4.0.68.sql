-- Seed core database rules (4.0.68). Use with LUPO_TABLE_PREFIX (default lupo_).
-- Doctrine: explicit column lists; BIGINT timestamps (YYYYMMDDHHIISS).

SET @now = 20260310120000;

INSERT INTO lupo_rules (rule_id, rule_name, rule_description, rule_type, rule_script, rule_version, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES (1, 'No Foreign Keys Doctrine', 'All database tables must NOT use foreign keys. Relationships are managed in application code.', 'constraint', '{"doctrine": "database", "rule": "no_foreign_keys", "enforcement": "strict"}', 1, @now, @now, 0, NULL);

INSERT INTO lupo_rules (rule_id, rule_name, rule_description, rule_type, rule_script, rule_version, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES (2, 'No Database Logic Doctrine', 'No triggers, stored procedures, views, or computed columns. Database is dumb storage.', 'constraint', '{"doctrine": "database", "rule": "no_db_logic", "enforcement": "strict"}', 1, @now, @now, 0, NULL);

INSERT INTO lupo_rules (rule_id, rule_name, rule_description, rule_type, rule_script, rule_version, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES (3, 'Timestamp Doctrine', 'All timestamps must be BIGINT in YYYYMMDDHHIISS UTC format. No DATETIME, no TIMESTAMP columns.', 'constraint', '{"doctrine": "database", "rule": "timestamp_format", "enforcement": "strict"}', 1, @now, @now, 0, NULL);

INSERT INTO lupo_rules (rule_id, rule_name, rule_description, rule_type, rule_script, rule_version, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES (4, 'Explicit INSERT Doctrine', 'All INSERT statements must explicitly list every column. Do not rely on column order or defaults.', 'constraint', '{"doctrine": "database", "rule": "explicit_inserts", "enforcement": "strict"}', 1, @now, @now, 0, NULL);

INSERT INTO lupo_rules (rule_id, rule_name, rule_description, rule_type, rule_script, rule_version, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES (5, 'Registry Open Doctrine', 'All primary keys must come from registry_open. No AUTO_INCREMENT for registry-backed tables. IDs are allocated from registry.', 'constraint', '{"doctrine": "database", "rule": "registry_open", "enforcement": "strict"}', 1, @now, @now, 0, NULL);

-- Attach rules to Channel 42 (applied_by_actor_id 0 = root). Explicit rule_target_id per doctrine.
INSERT INTO lupo_rule_targets (rule_target_id, rule_id, target_table, target_id, applied_by_actor_id, priority, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES (1, 1, 'channels', 42, 0, 100, @now, @now, 0, NULL);
INSERT INTO lupo_rule_targets (rule_target_id, rule_id, target_table, target_id, applied_by_actor_id, priority, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES (2, 2, 'channels', 42, 0, 100, @now, @now, 0, NULL);
INSERT INTO lupo_rule_targets (rule_target_id, rule_id, target_table, target_id, applied_by_actor_id, priority, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES (3, 3, 'channels', 42, 0, 100, @now, @now, 0, NULL);
INSERT INTO lupo_rule_targets (rule_target_id, rule_id, target_table, target_id, applied_by_actor_id, priority, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES (4, 4, 'channels', 42, 0, 100, @now, @now, 0, NULL);
INSERT INTO lupo_rule_targets (rule_target_id, rule_id, target_table, target_id, applied_by_actor_id, priority, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES (5, 5, 'channels', 42, 0, 100, @now, @now, 0, NULL);

-- No Information Schema rule (shared hosting: use SHOW TABLES and TOON files)
INSERT INTO lupo_rules (rule_id, rule_name, rule_description, rule_type, rule_script, rule_version, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES (1002, 'No Information Schema Queries', 'Never use information_schema queries — use SHOW TABLES and TOON files instead', 'constraint', '{"forbidden_patterns": ["information_schema", "INFORMATION_SCHEMA"], "allowed_alternatives": ["SHOW TABLES", "SHOW CREATE TABLE", "TOON files"]}', 1, @now, @now, 0, NULL);

INSERT INTO lupo_rule_targets (rule_target_id, rule_id, target_table, target_id, applied_by_actor_id, priority, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
VALUES (6, 1002, 'channels', 42, 0, 100, @now, @now, 0, NULL);
