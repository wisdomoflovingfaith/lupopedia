-- Seed skills metadata in lupo_metadata (4.0.68). Explicit metadata_id; BIGINT timestamps (YYYYMMDDHHIISS).

SET @now = 20260310120000;

-- Skill: lupopedia-headers (entity_type='skill', entity_id=1)
INSERT INTO lupo_metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10201, 'skill', 1, NULL, 'metadata', 'name', 'lupopedia-headers', @now, @now, 0, NULL, 42, NULL, 'lupopedia_property');
INSERT INTO lupo_metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10202, 'skill', 1, NULL, 'metadata', 'version', '1.0', @now, @now, 0, NULL, 42, NULL, 'lupopedia_property');
INSERT INTO lupo_metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10203, 'skill', 1, NULL, 'metadata', 'path', 'lupo-skills/lupopedia-headers/', @now, @now, 0, NULL, 42, NULL, 'lupopedia_property');
INSERT INTO lupo_metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10204, 'skill', 1, NULL, 'metadata', 'description', 'Knowledge of LUPOPEDIA header format, structure, and usage', @now, @now, 0, NULL, 42, NULL, 'lupopedia_property');

-- Attach skill to Actor 1 (WOLFIE): entity_type='actor', entity_id=1, property_key=skill name
INSERT INTO lupo_metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10205, 'actor', 1, NULL, 'skill', 'lupopedia-headers', '{"proficiency":"master","acquired":20260310,"verified_by":2}', @now, @now, 0, NULL, 42, NULL, 'lupopedia_property');
