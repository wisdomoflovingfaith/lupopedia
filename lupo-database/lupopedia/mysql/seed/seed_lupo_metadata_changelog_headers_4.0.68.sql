-- Seed lupo_metadata with LUPOPEDIA header data for CHANGELOG.md (4.0.68).
-- Source: CHANGELOG.md FLARE header blocks. Doctrine: explicit column lists; BIGINT timestamps.
-- Entity: entity_type='lupopedia_header', entity_id=1 => CHANGELOG.md.

SET @now = 20260310120000;

-- Root row for CHANGELOG.md header (per LUPOPEDIA_HEADERS storage model)
INSERT INTO lupo_metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10001, 'lupopedia_header', 1, NULL, 'lupopedia_header', '__root__', '1', @now, @now, 0, NULL, 1, NULL, 'lupopedia_header_root');

-- flare.headers block row
INSERT INTO lupo_metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10002, 'lupopedia_header', 1, NULL, 'block', 'flare.headers', '', @now, @now, 0, NULL, 1, 10001, 'lupopedia_block');

-- flare.headers properties (from CHANGELOG.md)
INSERT INTO lupo_metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10003, 'lupopedia_header', 1, NULL, 'block', 'flare.version', '1.0', @now, @now, 0, NULL, 1, 10002, 'lupopedia_property');
INSERT INTO lupo_metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10004, 'lupopedia_header', 1, NULL, 'block', 'flare.schema', 'documentation', @now, @now, 0, NULL, 1, 10002, 'lupopedia_property');
INSERT INTO lupo_metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10005, 'lupopedia_header', 1, NULL, 'block', 'file_path_from_root', 'CHANGELOG.md', @now, @now, 0, NULL, 1, 10002, 'lupopedia_property');
INSERT INTO lupo_metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10006, 'lupopedia_header', 1, NULL, 'block', 'file_hash', 'to_be_generated', @now, @now, 0, NULL, 1, 10002, 'lupopedia_property');
INSERT INTO lupo_metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10007, 'lupopedia_header', 1, NULL, 'block', 'system_version', '4.0.68', @now, @now, 0, NULL, 1, 10002, 'lupopedia_property');
INSERT INTO lupo_metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10008, 'lupopedia_header', 1, NULL, 'block', 'channel_id', '1', @now, @now, 0, NULL, 1, 10002, 'lupopedia_property');
INSERT INTO lupo_metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10009, 'lupopedia_header', 1, NULL, 'block', 'actor_id', '1', @now, @now, 0, NULL, 1, 10002, 'lupopedia_property');
INSERT INTO lupo_metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10010, 'lupopedia_header', 1, NULL, 'block', 'last_modified_utc', '20260310', @now, @now, 0, NULL, 1, 10002, 'lupopedia_property');
INSERT INTO lupo_metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10011, 'lupopedia_header', 1, NULL, 'block', 'delegation_chain', 'antigravity:cursor:captain', @now, @now, 0, NULL, 1, 10002, 'lupopedia_property');
INSERT INTO lupo_metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10012, 'lupopedia_header', 1, NULL, 'block', 'artifact_type', 'changelog', @now, @now, 0, NULL, 1, 10002, 'lupopedia_property');
INSERT INTO lupo_metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10013, 'lupopedia_header', 1, NULL, 'block', 'artifact_kind', 'history', @now, @now, 0, NULL, 1, 10002, 'lupopedia_property');
INSERT INTO lupo_metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10014, 'lupopedia_header', 1, NULL, 'block', 'purpose', 'Canonical version history for Lupopedia with LUPOPEDIA HEADERS protocol; rules, skills, paths/visits, TOON-based validation, Cursor rules for actor 1, single-install doctrine, and FLIP/FLARE replaced by LUPOPEDIA HEADERS.', @now, @now, 0, NULL, 1, 10002, 'lupopedia_property');
INSERT INTO lupo_metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10015, 'lupopedia_header', 1, NULL, 'block', 'mood_rgb', '4169E1', @now, @now, 0, NULL, 1, 10002, 'lupopedia_property');
INSERT INTO lupo_metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10016, 'lupopedia_header', 1, NULL, 'block', 'traits', 'canonical, comprehensive, v4.0.68', @now, @now, 0, NULL, 1, 10002, 'lupopedia_property');
INSERT INTO lupo_metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10017, 'lupopedia_header', 1, NULL, 'block', 'tags', 'changelog, versions, releases, history, lupopedia-headers, federation', @now, @now, 0, NULL, 1, 10002, 'lupopedia_property');
INSERT INTO lupo_metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10018, 'lupopedia_header', 1, NULL, 'block', 'lupo_agent', 'wolfie', @now, @now, 0, NULL, 1, 10002, 'lupopedia_property');

-- flare.footer block row
INSERT INTO lupo_metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10019, 'lupopedia_header', 1, NULL, 'block', 'flare.footer', '', @now, @now, 0, NULL, 1, 10001, 'lupopedia_block');
INSERT INTO lupo_metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10020, 'lupopedia_header', 1, NULL, 'block', 'last_verified', '20260310', @now, @now, 0, NULL, 1, 10019, 'lupopedia_property');
INSERT INTO lupo_metadata (metadata_id, entity_type, entity_id, domain_id, meta_type, property_key, property_value, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, channel_id, parent_metadata_id, class_name)
VALUES (10021, 'lupopedia_header', 1, NULL, 'block', 'last_verified_by', 'cursor', @now, @now, 0, NULL, 1, 10019, 'lupopedia_property');
