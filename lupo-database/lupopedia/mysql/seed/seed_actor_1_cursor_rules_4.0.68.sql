-- Seed Actor 1 (WOLFIE) cursor rules in lupo_metadata (4.0.68).
-- Each row attaches one .cursor/rules/*.mdc rule to actor_id=1 via lupo-rules/cursor/*.md path.
-- Explicit metadata_id; BIGINT timestamps (YYYYMMDDHHIISS).

SET @now = 20260310120000;

-- Actor 1 cursor rules: meta_type='cursor_rule', property_key=rule slug, property_value=JSON path/source
INSERT INTO lupo_metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10301, 'actor', 1, NULL, 'cursor_rule', 'php-5-3-compatibility', '{"path":"lupo-rules/cursor/php-5-3-compatibility.md","source_path":".cursor/rules/php-5-3-compatibility.mdc","attached_ymdhis":20260310120000}', @now, @now, 0, NULL, 42, NULL, 'lupopedia_property');

INSERT INTO lupo_metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10302, 'actor', 1, NULL, 'cursor_rule', 'no-laravel-no-middleware', '{"path":"lupo-rules/cursor/no-laravel-no-middleware.md","source_path":".cursor/rules/no-laravel-no-middleware.mdc","attached_ymdhis":20260310120000}', @now, @now, 0, NULL, 42, NULL, 'lupopedia_property');

INSERT INTO lupo_metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10303, 'actor', 1, NULL, 'cursor_rule', 'pdo-db-database-access-doctrine', '{"path":"lupo-rules/cursor/pdo-db-database-access-doctrine.md","source_path":".cursor/rules/pdo-db-database-access-doctrine.mdc","attached_ymdhis":20260310120000}', @now, @now, 0, NULL, 42, NULL, 'lupopedia_property');

INSERT INTO lupo_metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10304, 'actor', 1, NULL, 'cursor_rule', 'migration-doctrine', '{"path":"lupo-rules/cursor/migration-doctrine.md","source_path":".cursor/rules/migration-doctrine.mdc","attached_ymdhis":20260310120000}', @now, @now, 0, NULL, 42, NULL, 'lupopedia_property');

INSERT INTO lupo_metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10305, 'actor', 1, NULL, 'cursor_rule', 'database-logic-prohibition-doctrine', '{"path":"lupo-rules/cursor/database-logic-prohibition-doctrine.md","source_path":".cursor/rules/database-logic-prohibition-doctrine.mdc","attached_ymdhis":20260310120000}', @now, @now, 0, NULL, 42, NULL, 'lupopedia_property');

INSERT INTO lupo_metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10306, 'actor', 1, NULL, 'cursor_rule', 'flip-doctrine', '{"path":"lupo-rules/cursor/flip-doctrine.md","source_path":".cursor/rules/flip-doctrine.mdc","attached_ymdhis":20260310120000}', @now, @now, 0, NULL, 42, NULL, 'lupopedia_property');

INSERT INTO lupo_metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10307, 'actor', 1, NULL, 'cursor_rule', 'toon-source-of-truth', '{"path":"lupo-rules/cursor/toon-source-of-truth.md","source_path":".cursor/rules/toon-source-of-truth.mdc","attached_ymdhis":20260310120000}', @now, @now, 0, NULL, 42, NULL, 'lupopedia_property');

INSERT INTO lupo_metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10308, 'actor', 1, NULL, 'cursor_rule', 'reserved-id-doctrine', '{"path":"lupo-rules/cursor/reserved-id-doctrine.md","source_path":".cursor/rules/reserved-id-doctrine.mdc","attached_ymdhis":20260310120000}', @now, @now, 0, NULL, 42, NULL, 'lupopedia_property');

INSERT INTO lupo_metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10309, 'actor', 1, NULL, 'cursor_rule', 'versioning-doctrine-single-source', '{"path":"lupo-rules/cursor/versioning-doctrine-single-source.md","source_path":".cursor/rules/versioning-doctrine-single-source.mdc","attached_ymdhis":20260310120000}', @now, @now, 0, NULL, 42, NULL, 'lupopedia_property');

INSERT INTO lupo_metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10310, 'actor', 1, NULL, 'cursor_rule', 'pk-reference-naming-doctrine', '{"path":"lupo-rules/cursor/pk-reference-naming-doctrine.md","source_path":".cursor/rules/pk-reference-naming-doctrine.mdc","attached_ymdhis":20260310120000}', @now, @now, 0, NULL, 42, NULL, 'lupopedia_property');

INSERT INTO lupo_metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10311, 'actor', 1, NULL, 'cursor_rule', 'required-tables-future-features-doctrine', '{"path":"lupo-rules/cursor/required-tables-future-features-doctrine.md","source_path":".cursor/rules/required-tables-future-features-doctrine.mdc","attached_ymdhis":20260310120000}', @now, @now, 0, NULL, 42, NULL, 'lupopedia_property');

INSERT INTO lupo_metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10312, 'actor', 1, NULL, 'cursor_rule', 'wheeler-reverse20-ban', '{"path":"lupo-rules/cursor/wheeler-reverse20-ban.md","source_path":".cursor/rules/wheeler-reverse20-ban.mdc","attached_ymdhis":20260310120000}', @now, @now, 0, NULL, 42, NULL, 'lupopedia_property');

INSERT INTO lupo_metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10313, 'actor', 1, NULL, 'cursor_rule', 'stoned-wolfie-schrodinger-ban', '{"path":"lupo-rules/cursor/stoned-wolfie-schrodinger-ban.md","source_path":".cursor/rules/stoned-wolfie-schrodinger-ban.mdc","attached_ymdhis":20260310120000}', @now, @now, 0, NULL, 42, NULL, 'lupopedia_property');

INSERT INTO lupo_metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10314, 'actor', 1, NULL, 'cursor_rule', 'quantum-state-uncertainty-ban', '{"path":"lupo-rules/cursor/quantum-state-uncertainty-ban.md","source_path":".cursor/rules/quantum-state-uncertainty-ban.mdc","attached_ymdhis":20260310120000}', @now, @now, 0, NULL, 42, NULL, 'lupopedia_property');

INSERT INTO lupo_metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10315, 'actor', 1, NULL, 'cursor_rule', 'experimental-ai-artifact-ban', '{"path":"lupo-rules/cursor/experimental-ai-artifact-ban.md","source_path":".cursor/rules/experimental-ai-artifact-ban.mdc","attached_ymdhis":20260310120000}', @now, @now, 0, NULL, 42, NULL, 'lupopedia_property');

-- Single install, no Lupopedia upgrade until 4.1.0; schema in install + seed; consolidate 4.0.x migrations; no 4.0.x backwards compat
INSERT INTO lupo_metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10316, 'actor', 1, NULL, 'cursor_rule', 'single-install-no-4.0-upgrade-doctrine', '{"path":"lupo-rules/cursor/single-install-no-4.0-upgrade-doctrine.md","source_path":".cursor/rules/single-install-no-4.0-upgrade-doctrine.mdc","attached_ymdhis":20260310120000}', @now, @now, 0, NULL, 42, NULL, 'lupopedia_property');
