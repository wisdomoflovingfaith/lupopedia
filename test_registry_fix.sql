-- Test registry table creation
CREATE TABLE lupo_registry (
  registry_id bigint NOT NULL AUTO_INCREMENT,
  entity_type varchar(50) NOT NULL,
  entity_index_id bigint NOT NULL DEFAULT 0,
  entity_index bigint NOT NULL DEFAULT 0,
  federation_node_id bigint NOT NULL DEFAULT 0,
  reserved_ymdhis bigint NOT NULL DEFAULT 0,
  metadata text,
  entity_key varchar(255) DEFAULT NULL,
  entity_name varchar(255) DEFAULT NULL,
  entity_table varchar(255) DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  deleted_ymdhis bigint DEFAULT NULL,
  is_active tinyint NOT NULL DEFAULT 1,
  is_kernel tinyint NOT NULL DEFAULT 0,
  metadata_json text,
  PRIMARY KEY (registry_id)
);

-- Test INSERT without registry_id
INSERT INTO lupo_registry (registry_id, entity_type, entity_index_id, entity_index, entity_key, entity_name, entity_table, federation_node_id, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis, is_active, is_kernel, metadata_json) 
VALUES (1, 'actor', 1000, 1000, 'test', 'Test Actor', 'lupo_actors', 1, 20260222120000, 20260222120000, 0, NULL, 1, 0, '{"test":true}');

SELECT 'Registry table test successful' as result;
