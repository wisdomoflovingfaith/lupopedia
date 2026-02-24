# Antigravity Status: Artifact Chunk Migration (v4.0.42)

## Overview
As part of the version 4.0.42 cleanup and unification effort, the legacy `lupo_documents` and `lupo_document_chunks` tables have been replaced by a unified `lupo_artifact_chunks` system that integrates directly with the existing `lupo_artifacts` table.

## Changes

### 1. Database Schema (install_new_lupopedia.sql)
- **Removed**: `lupo_documents` and `lupo_document_chunks` table definitions.
- **Added**: `lupo_artifact_chunks` table definition.
- **Enhanced**: `lupo_artifacts` table now includes `metadata` and `updated_ymdhis` columns to support rich document metadata.

#### New Table: lupo_artifact_chunks
| Field | Type | Description |
|-------|------|-------------|
| artifact_chunk_id | BIGINT | Primary Key |
| artifact_id | BIGINT | Foreign Key to lupo_artifacts |
| chunk_index | INT | Order of the chunk within the artifact |
| chunk_content | MEDIUMTEXT | The actual text content |
| token_count | INT | Pre-calculated token count for RAG |
| metadata | JSON | Chunk-specific metadata |
| created_ymdhis | BIGINT | Creation timestamp |
| updated_ymdhis | BIGINT | Last update timestamp |
| is_deleted | TINYINT | Soft delete flag |

### 2. Migration Logic
A new migration script has been created: `database/migrations/migrate_documents_to_artifacts_4.0.42.sql`.
- **Documents → Artifacts**: Maps `lupo_documents` to `lupo_artifacts` with `entity_type = 'document'`. Original document metadata (source_url, mime_type, checksum) is preserved in the `metadata` JSON column.
- **Chunks → Artifact Chunks**: Directly maps `lupo_document_chunks` to `lupo_artifact_chunks`.

This migration has been integrated into `run_closure_migration.php`.

### 3. VSX Extension Updates
- The VSX extension now uses the unified `ArtifactIndex.ts` and `QueryEngine.ts` which operate on the artifact system.
- Standardized on `artifact_id` as the primary identifier for semantic mapping and RAG operations.

### 4. Admin UI Synchronization
- Updated `schema-config.php` to classify `lupo_artifact_chunks` as an ephemeral table.
- References to "documents" in the internal indexing logic (KIP/CIP) now point to the artifact system.

## Impact
- **RAG Subsystem**: The RAG (Retrieval-Augmented Generation) pipeline is now fully unified.
- **Storage Efficiency**: Reduced table count and unified metadata handling.
- **Semantic Intelligence**: Artifacts and their chunks are now treated as first-class citizens in the global registry.

## Verification
- Run `php run_closure_migration.php` to execute the data migration.
- Verify `lupo_artifact_chunks` population via database query.
