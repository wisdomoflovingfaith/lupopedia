# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/lupo_document_embeddings.md"
  file_hash: "da06abf6b452617709df1dc73e408709c9da8ee8239e4f442c0705cc059475b0"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  namespace: "core"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:`n  approved_for_release: "4.1.0"`n  approval_status: "approved"`n  approved_by_actor_id: 1`n  approved_utc: 20260326192115`n  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  file_path_from_root: "lupo-docs/database/lupopedia/tables/lupo_document_embeddings.md"
  file_hash: "0721e579a6e322f55616c23d32591356775f32c150b57e4122a77d197f1a532f"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1007
  last_modified_utc: "20260227"
  delegation_chain: "1007:10000"
  artifact_type: "table_documentation"
  purpose: "Vector embedding store for document chunks"
  dialog_message: "DBDOC batch 1: enriched documentation and optimization notes."
  mood_rgb: "4B0082"
  artifact_kind: "table"
  traits: ["canonical", "database", "curated"]
  tags: ["database", "table", "lupo_document_embeddings", "vector"]
  lupo_agent: "codex-ide"
  lupo_document_embeddings.document_embedding_id: "bigint NOT NULL"
  lupo_document_embeddings.chunk_id: "bigint NOT NULL"
  lupo_document_embeddings.embedding_json: "json NOT NULL"
  lupo_document_embeddings.embedding_model: "varchar(128) NOT NULL"
  lupo_document_embeddings.embedding_version: "varchar(64)"
  lupo_document_embeddings.created_ymdhis: "bigint NOT NULL DEFAULT 0"
  lupo_document_embeddings.updated_ymdhis: "bigint NOT NULL DEFAULT 0"
  lupo_document_embeddings.is_deleted: "tinyint NOT NULL DEFAULT 0"
  lupo_document_embeddings.deleted_ymdhis: "bigint DEFAULT 0"
  table_primary_key: "document_embedding_id"
  table_engine: "unknown"
  table_charset: "unknown"
  table_collation: "unknown"
  table_indexes: ["lupo_document_embeddings_chunk_id", "lupo_document_embeddings_embedding_model"]
  table_foreign_keys: []

# FLARE Edge Automation Tip:
# Use the FLARE Edge Suggester Tool to automatically discover and suggest edges:
# python lupo-scripts/flare_edge_suggester.py --file <path> --include-db --format yaml

  last_updated_utc: "20260228"
  system_version: "4.0.50"
lupopedia.footer:`n  approved_for_release: "4.1.0"`n  approval_status: "approved"`n  approved_by_actor_id: 1`n  approved_utc: 20260326192115`n  outbound_edges:
    - { to: "lupo-database/lupopedia/toon/lupo_document_embeddings.toon.json", type: "schema_reference", weight: 1.0, reason: "TOON schema definition", db_source: "lupo_document_embeddings" }
    - { to: "lupo-docs/database/lupopedia/tables/lupo_artifact_chunks.md", type: "references", weight: 0.8, reason: "chunk_id references artifact chunks" }
    - { to: "lupo-docs/database/lupopedia/tables/lupo_contents.md", type: "references", weight: 0.7, reason: "embeddings derived from content" }
    - { to: "lupo-docs/FLARE_HEADERS_COMPLETE_REFERENCE.md", type: "references", weight: 0.7, reason: "FLARE header reference" }
  inbound_edges: []
  semantic_tags: ["database", "table", "embeddings", "vector"]
  version: "4.0.49"
  last_verified: "20260227"
  last_verified_by: "codex-ide"
---

# Table: lupo_document_embeddings

Purpose: Stores vector embeddings per chunk for semantic search and retrieval.
Type: database_table
Status: production_ready
Volume: medium_high

## 1. Overview
- Key responsibilities: store embeddings for chunk-level retrieval and semantic search.
- System role: powers semantic query resolution and relevance scoring.
- Importance: enables AI-driven search and content ranking.

## 2. Schema Reference
Primary Key: document_embedding_id
Field Categories: identity, embedding payload, lifecycle, soft delete.

### All Fields
| Column | Type | Notes |
|---|---|---|
| document_embedding_id | bigint NOT NULL | Primary key. |
| chunk_id | bigint NOT NULL | Links to chunk source. |
| embedding_json | json NOT NULL | Serialized vector payload. |
| embedding_model | varchar(128) NOT NULL | Embedding model identifier. |
| embedding_version | varchar(64) | Model/version tag. |
| created_ymdhis | bigint NOT NULL DEFAULT 0 | Created timestamp. |
| updated_ymdhis | bigint NOT NULL DEFAULT 0 | Last update timestamp. |
| is_deleted | tinyint NOT NULL DEFAULT 0 | Soft delete flag. |
| deleted_ymdhis | bigint DEFAULT 0 | Soft delete timestamp. |

## 3. Relationships and Dependencies
- Primary relationships: chunk_id should align with chunk storage table.
- Referencing tables: search index or semantic services.
- Integration points: semantic search, embedding refresh pipelines.

## 4. Indexes and Performance
Primary Indexes:
- document_embedding_id
Performance Indexes:
- lupo_document_embeddings_chunk_id
- lupo_document_embeddings_embedding_model
Index Strategy: optimize lookups by chunk and model.

## 5. Usage Patterns
Common Queries:
```sql
SELECT * FROM lupo_document_embeddings WHERE chunk_id = :chunk_id AND is_deleted = 0;
SELECT * FROM lupo_document_embeddings WHERE embedding_model = :model AND is_deleted = 0;
SELECT COUNT(*) AS total FROM lupo_document_embeddings WHERE is_deleted = 0;
UPDATE lupo_document_embeddings SET updated_ymdhis = :ts WHERE document_embedding_id = :id;
```
Best Practices: update embeddings in batches and keep model/version consistent.
Anti-Patterns: store multiple models without indexing or versioning strategy.

## 6. Performance Considerations
- High-volume operations: batch insert during re-embedding.
- Optimization tips: add composite index on (chunk_id, embedding_model) if multi-model usage grows.
- Scaling considerations: split by model or shard by chunk_id ranges.

## 7. Data Integrity
- Constraints: embedding_json must be valid JSON.
- Validation rules: enforce model and version values in application logic.
- Soft delete: required for bulk refreshes.

## 8. Common Issues and Solutions
- Performance issues: add composite index for chunk + model queries.
- Data consistency: re-embed on content edits; store updated_ymdhis.
- Troubleshooting: check for duplicate chunk_id across models.

## 9. Future Enhancements
- Add embedding_hash for deduplication.
- Consider storing vector dimensionality for validation.

