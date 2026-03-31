---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: prd
  version_when_written: "4.0.93"
  file_path_from_root: "lupo-docs/prd/06_content_management.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/prd/06_content_management.md"
  last_modified_utc: "20260330163000"
  channel_id: 42
  thread_id: "prd-grouped"
  actor_id: 102
  actor_name: "HEPHAESTUS"
  delegation_chain: "hephaestus:root|lilith:audit"
  artifact_type: "prd"
  artifact_kind: "database_namespace"
  purpose: "PRD for content storage, files, uploads, and version management"
  tags:
  - "prd"
  - "database"
  - "namespace"
  - "content_management"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/database/lupopedia/tables/"
      type: references
      weight: 1.0
      reason: "Detailed table documentation"
    - to: "lupo-docs/prd/05_collections_navigation.md"
      type: references
      weight: 1.0
      reason: "Collections organize content"
    - to: "lupo-docs/prd/04_tags_metadata.md"
      type: references
      weight: 1.0
      reason: "Content uses tags and metadata"
lupopedia.footer:
  last_verified: "20260330163000"
  verified_by:
    actor_id: 102
    agent_name_identity: Cursor IDE Agent
  orchestrator: "hephaestus:root"
---

# PRD: Content Storage, Files, Uploads, and Version Management

## Overview

**Namespace Purpose:** Manages all content storage including files, uploads, references, versions, and content organization. This namespace provides the foundation for content creation, storage, retrieval, and lifecycle management.

**Primary Actors:** 
- Content creators (via lupo_contents)
- File managers (via lupo_uploads)
- Reference managers (via lupo_references)
- Version controllers (via lupo_reference_objects)
- Upload processors (via lupo_upload processing)

**Constitutional Compliance:** All tables in this namespace follow Lupopedia constitutional rules:
- NO foreign keys (relationships in application logic)
- NO triggers
- NO stored procedures
- BIGINT timestamps (YYYYMMDDHHIISS UTC)
- Explicit ID generation (application layer)
- Soft delete (is_deleted + deleted_ymdhis)

## Tables in This Namespace

| Table | Purpose | Primary Key | Key Application Relationships |
|-------|---------|-------------|------------------------------|
| `lupo_contents` | General content storage with metadata | `content_id` | Central to content system |
| `lupo_uploads` | File upload tracking and storage | `upload_id` | File management system |
| `lupo_references` | Reference storage for content linking | `reference_id` | Content relationship system |
| `lupo_reference_links` | Link definitions between references | `reference_link_id` | Reference relationship system |
| `lupo_reference_map` | Many-to-many reference relationships | `reference_map_id` | Links content to references |
| `lupo_reference_objects` | Reference object definitions | `reference_object_id` | Reference type system |
| `lupo_magic_link_tokens` | Temporary access tokens for content | `token_id` | Secure content access |

## Table Details

### `lupo_contents`

**Purpose:** Stores all content with flexible metadata and versioning support.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| content_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| content_type | VARCHAR(32) | NO | 'text' | Type: text, image, file, video, audio |
| title | VARCHAR(255) | NO |  | Content title |
| content | LONGTEXT | NO |  | Main content body |
| excerpt | TEXT | YES | NULL | Content excerpt/summary |
| author_actor_id | BIGINT | NO |  | Foreign reference to lupo_actors |
| status | VARCHAR(32) | NO | 'draft' | Status: draft, published, archived |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| updated_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| published_ymdhis | BIGINT | YES | NULL | When content was published |
| view_count | INT | NO | 0 | Number of times viewed |
| is_featured | TINYINT | NO | 0 | Whether content is featured |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_contents_author | author_actor_id, status, created_ymdhis, is_deleted | Author's content |
| idx_contents_type | content_type, status, is_published, is_deleted | Type-based queries |
| idx_contents_status | status, is_published, created_ymdhis, is_deleted | Status-based queries |
| idx_contents_featured | is_featured, published_ymdhis, is_deleted | Featured content |

### `lupo_uploads`

**Purpose:** Tracks file uploads with metadata and processing status.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| upload_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| actor_id | BIGINT | NO |  | Foreign reference to lupo_actors |
| original_filename | VARCHAR(255) | NO |  | Original uploaded filename |
| stored_filename | VARCHAR(255) | NO |  | Stored filename (may be different) |
| file_path | VARCHAR(1024) | NO |  | Full file path |
| file_size | BIGINT | NO | 0 | File size in bytes |
| mime_type | VARCHAR(255) | NO |  | MIME type detection |
| upload_purpose | VARCHAR(32) | NO | 'content' | Purpose: content, avatar, document, media |
| status | VARCHAR(32) | NO | 'uploading' | Status: uploading, processing, completed, failed |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| processed_ymdhis | BIGINT | YES | NULL | When processing completed |
| expires_ymdhis | BIGINT | YES | NULL | When file expires |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_uploads_actor | actor_id, upload_purpose, status, is_deleted | Actor's uploads |
| idx_uploads_status | status, created_ymdhis, is_deleted | Status-based queries |
| idx_uploads_expires | expires_ymdhis, is_deleted | Cleanup expired uploads |

### `lupo_references`

**Purpose:** Stores references that can be linked to any content type.

**Columns:**

| Column | Type | Nullable | Default | Description |
|--------|------|----------|---------|-------------|
| reference_id | BIGINT | NO | (application) | Primary key, generated via IdGenerator |
| reference_type | VARCHAR(32) | NO | 'url' | Type: url, doi, isbn, internal |
| reference_title | VARCHAR(255) | NO |  | Reference title |
| reference_data | TEXT | NO |  | Reference data (URL, DOI, etc.) |
| metadata_json | JSON | YES | NULL | Additional reference metadata |
| created_by_actor_id | BIGINT | NO |  | Foreign reference to lupo_actors |
| created_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| updated_ymdhis | BIGINT | NO | (application) | UTC timestamp YYYYMMDDHHIISS |
| is_verified | TINYINT | NO | 0 | Whether reference is verified |
| is_deleted | TINYINT | NO | 0 | Soft delete flag |
| deleted_ymdhis | BIGINT | YES | NULL | UTC timestamp when deleted |

**Indexes:**

| Index Name | Columns | Purpose |
|------------|---------|---------|
| idx_references_type | reference_type, is_verified, is_deleted | Type-based queries |
| idx_references_actor | created_by_actor_id, created_ymdhis, is_deleted | Actor's references |

## Cross-Namespace Dependencies

| Dependency | Direction | Purpose | Tables Involved |
|------------|------------|---------|------------------|
| 06_content_management | This → 05_collections_navigation | Content organization | Collections contain content |
| 06_content_management | This → 04_tags_metadata | Content tagging | Metadata for content |
| 01_core_identity | Core → This | Content ownership | author_actor_id columns |

## State Transitions

| State | Description | Transition To |
|--------|-------------|--------------|
| draft | Content being created/editing | published, archived, deleted (soft) |
| published | Publicly available content | archived, deleted (soft) |
| archived | Content no longer active | published, deleted (soft) |
| deleted | Soft-deleted | N/A (can't be restored without explicit action) |

## Security & Privacy

File uploads are scanned for malware and validated

Content access is controlled through actor permissions

Soft delete preserves content history for compliance

## Testing Requirements

Unit tests for content CRUD operations

Integration tests for file upload and processing

Performance tests for content search and filtering

Soft delete behavior verification

## Usage Patterns

```php
// Create content
$contentService = new ContentService();
$contentId = $contentService->create($actorId, $title, $content, $type);

// Upload file
$uploadService = new UploadService();
$uploadId = $uploadService->upload($actorId, $fileData, $purpose);

// Add reference to content
$referenceService = new ReferenceService();
$referenceId = $referenceService->createReference($type, $title, $data);

// Link content to reference
$referenceMapService = new ReferenceMapService();
$mapId = $referenceMapService->linkContent($contentId, $referenceId);
```
