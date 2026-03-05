-- Migration script for 4.0.42: Migrate lupo_documents and lupo_document_chunks to lupo_artifacts system.
-- SET @now = 20260224133200;

-- 1. Create lupo_artifact_chunks if it doesn't exist (failsafe)
CREATE TABLE IF NOT EXISTS lupo_artifact_chunks (
  artifact_chunk_id bigint NOT NULL,
  artifact_id bigint NOT NULL,
  chunk_index int NOT NULL,
  chunk_content mediumtext NOT NULL,
  token_count int DEFAULT NULL,
  metadata json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT '0',
  deleted_ymdhis bigint DEFAULT NULL,
  PRIMARY KEY (artifact_chunk_id)
);

-- 2. Migrate documents to artifacts
-- Map document_id -> artifact_id
-- document_name -> content (as part of a JSON structure or identifier)
-- We'll store the original document mapping in metadata
INSERT INTO lupo_artifacts (
  artifact_id, actor_id, `utc_timestamp`, entity_type, content, metadata, 
  created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis
)
SELECT 
  document_id, 
  1, -- Default SYSTEM actor
  created_ymdhis, 
  'document', 
  CONCAT('Document: ', document_name), 
  JSON_OBJECT(
    'original_document_id', document_id,
    'document_name', document_name,
    'source_type', source_type,
    'source_url', source_url,
    'mime_type', mime_type,
    'file_size_bytes', file_size_bytes,
    'checksum_sha256', checksum_sha256
  ),
  created_ymdhis,
  updated_ymdhis,
  is_deleted,
  deleted_ymdhis
FROM lupo_documents
ON DUPLICATE KEY UPDATE content = VALUES(content);

-- 3. Migrate document_chunks to artifact_chunks
INSERT INTO lupo_artifact_chunks (
  artifact_chunk_id, artifact_id, chunk_index, chunk_content, token_count, 
  metadata, created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis
)
SELECT 
  document_chunk_id,
  document_id,
  chunk_index,
  chunk_content,
  token_count,
  metadata,
  created_ymdhis,
  updated_ymdhis,
  is_deleted,
  deleted_ymdhis
FROM lupo_document_chunks
ON DUPLICATE KEY UPDATE chunk_content = VALUES(chunk_content);

-- 4. Tag migrated artifacts as documents in entity_type
UPDATE lupo_artifacts SET entity_type = 'document' WHERE entity_type = 'document';
