# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\docs\status\antigravity_artifact_chunk_migration_4_0_42.md"
  file_hash: "2de06ec7d93ea740fc588201c96c295986e0687a365365a294144b3a258266a9"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with FLARE header applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

flare.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

flare.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\status\antigravity_artifact_chunk_migration_4_0_42.md"
  file_hash: "ddd7fbda6352786bc2ad0db066d7ec5da6041133a9082f354fa73ef37abe2c27"
  file_path_from_root: "docs\status\antigravity_artifact_chunk_migration_4_0_42.md"
  file_hash: "95a039f1468cfff93a75acf346c27a2f31107dcf6f10d5dfa972ab42234ffc0a"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Antigravity Status: Artifact Chunk Migration (v4.0.42)"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "status", "antigravity_artifact_chunk_migration_4_0_42md"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

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