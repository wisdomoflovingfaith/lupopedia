---
lupopedia.headers:
  header_format_version: "4.1.4"
  file_path_from_root: lupo-docs/prd/06_A_CONTENT_MANAGEMENT.md
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/prd/06_A_CONTENT_MANAGEMENT.md"
  status: active
  when_updated: "20260421223000"
  trust_tier: canonical
  questions_toon: null
  memory_toon: lupo-memory/development/canonical/1026/04/06_content_management.toon
  atoms_toon: null
  transcript_jsonl: 0/development/prd_files/content-management
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: "0"
  thread_id: 
  content_id: null
  content_parent_id: 
  default_collection_id: null
  lupopedia.schema: prd
  prd_cluster: 00_A_FORBIDDEN_AND_WHY_06_A_CONTENT_MANAGEMENT
  title: "PRD: Content Storage, Files, Uploads, and Version Management"
  summary: 
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
| 06_content_management | This ???????? 05_collections_navigation | Content organization | Collections contain content |
| 06_content_management | This ???????? 04_tags_metadata | Content tagging | Metadata for content |
| 01_core_identity | Core ???????? This | Content ownership | author_actor_id columns |

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
## File-Backed Content Doctrine (4.0.93+, updated 4.0.95)

### Canonical Directory Structure

```
lupo-content/
    federation_node/{federation_node_id}/{folder_key}/{file_name}
    actor/{actor_id}/{folder_key}/{file_name}
```

- `federation_node_id` ???????? integer ID matching `lupo_federation_nodes.federation_node_id`.
- `actor_id` ???????? integer ID matching `lupo_actors.actor_id`.
- `folder_key` ???????? lowercase ASCII, underscores only; matches `lupo_folders.slug`.
- `file_name` ???????? lowercase ASCII, underscores only, no hyphens, no spaces. Extension `.md` or `.txt`.

**Examples:**
- `lupo-content/federation_node/0/captains_log/entry_001.md`
- `lupo-content/actor/10000/reference/minimal_tables.md`

### Canonical Rules

1. **File-backed content** is stored as a file (`.md` or `.txt`) under `lupo-content/` in the canonical structure above.
2. **No body text** is stored in the database for file-backed content (`content` column MUST be NULL, `storage_type` MUST be `'file_backed'`).
3. **Allowed formats:** Markdown (`.md`), plain text (`.txt`). No HTML. No other formats without explicit PRD update.
4. **Mapping:** Each file-backed content item must have exactly one row in `lupo_contents` with `file_path_from_root` set to the canonical repo-relative path.
5. **Folders:** `lupo_folders` must have one row per `folder_key` with `slug` matching the directory name, `name` = title-cased label, `description` = "File-backed content folder for {folder_key}".
6. **No inference:** The DB is the source of truth for registration and metadata. The filesystem is not.
7. **Registration:** New file-backed content requires an explicit INSERT into `lupo_contents` with `IdGenerator::generate()` for `content_id` and packed UTC for timestamps.
8. **Updates:** If a row already exists for a given path, UPDATE ???????? do not INSERT a duplicate.
9. **Slug rules:** All slugs, folder keys, and file names must be lowercase ASCII with underscores as the only word separator. No spaces, hyphens, or uppercase.
10. **storage_type rules:**
    - `storage_type = 'file_backed'` ???????? `content` MUST be NULL; `file_path_from_root` MUST NOT be NULL.
    - `storage_type = 'database'` ???????? `content` MUST NOT be NULL; `file_path_from_root` MUST be NULL.
11. **file_path_from_root:** Repo-relative path from project root; no leading slash. Example: `lupo-content/federation_node/0/captains_log/entry_001.md`.
12. **Timestamps:** Packed UTC BIGINT `YYYYMMDDHHIISS`. Never Unix epoch, never ISO strings.
13. **Prefix:** All file-backed content must live under `lupo-content/` at the project root. No ad hoc or legacy locations.
14. **Format registration:** New file types must be added to this PRD before use.
15. **No HTML:** HTML files are not allowed as file-backed content.
16. **No metadata blocks:** File-backed content files must NOT include YAML or other embedded metadata blocks. All metadata lives in the DB row.
17. **One-to-one:** The mapping between DB rows and filesystem files is one-to-one and explicit. No orphaned files. No orphaned DB rows.
18. **Validation:** All file-backed content must be registered in the DB; all DB rows must point to an existing file.
19. **No soft delete by file removal:** Deleting a file does not soft-delete the DB row. Use the DB `is_deleted` + `deleted_ymdhis` mechanism.
20. **Review:** All changes to file-backed content doctrine must be reviewed and approved via PRD update.

---

## Schema Corrections and Doctrine Clarifications (4.0.93+, applied 4.0.95)

### lupo_contents Table (Revised)

Applied to `install_new_lupopedia.sql` at UTC `20260407123924`:

- `storage_type VARCHAR(16) NOT NULL DEFAULT 'database'` ???????? **ADDED** (4.0.95).  
  Values: `'database'` (body in `content` column) | `'file_backed'` (body in file).
- `file_path_from_root VARCHAR(1024) NULL` ???????? EXISTED (expanded from VARCHAR 500 to 1024).  
  Required when `storage_type='file_backed'`; must be NULL when `storage_type='database'`.
- `slug VARCHAR(255) NOT NULL` ???????? EXISTED.  
  Lowercase ASCII, underscores only; unique per (slug, is_deleted).

**Unique index added:**
```sql
CREATE UNIQUE INDEX {{prefix}}contents_idx_slug_deleted ON {{prefix}}contents (slug, is_deleted);
```

**Application-level constraints:**
```sql
-- If storage_type='file_backed': content MUST be NULL, file_path_from_root MUST NOT be NULL.
-- If storage_type='database': content MUST NOT be NULL, file_path_from_root MUST be NULL.
-- slug must be lowercase ASCII, underscores only, unique per (slug, is_deleted).
```

**For existing databases (migration):**
```sql
ALTER TABLE lupo_contents ADD COLUMN storage_type VARCHAR(16) NOT NULL DEFAULT 'database';
ALTER TABLE lupo_contents MODIFY COLUMN file_path_from_root VARCHAR(1024) DEFAULT NULL;
CREATE UNIQUE INDEX idx_contents_slug_deleted ON lupo_contents(slug, is_deleted);
```

### lupo_folders Table (Canonical Definition)

Applied to `install_new_lupopedia.sql` at UTC `20260407123924` ???????? `description TEXT NULL` added:

```sql
CREATE TABLE lupo_folders (
    folder_id      BIGINT       NOT NULL,
    name           VARCHAR(255) NOT NULL,
    slug           VARCHAR(128) NOT NULL, -- lowercase ASCII, underscores only
    description    TEXT         DEFAULT NULL,
    parent_folder_id BIGINT     DEFAULT NULL,
    actor_id       BIGINT       DEFAULT NULL,
    channel_id     BIGINT       DEFAULT NULL,
    sort_order     INT          NOT NULL DEFAULT 0,
    created_ymdhis BIGINT       NOT NULL DEFAULT 0,
    updated_ymdhis BIGINT       NOT NULL DEFAULT 0,
    is_deleted     TINYINT      NOT NULL DEFAULT 0,
    deleted_ymdhis BIGINT       NOT NULL DEFAULT 0,
    PRIMARY KEY (folder_id)
);
```

### Validation Rules for File-Backed Content

- All file-backed content must have `storage_type='file_backed'`, `content` column NULL, and a valid `file_path_from_root`.
- All database-stored content must have `storage_type='database'`, non-NULL `content`, and `file_path_from_root` NULL.
- All slugs must be lower-case, ASCII, and use underscores only.
- All file-backed content must be registered in the DB; no orphaned files or rows.
- Deleting a file does not soft-delete the DB row; use DB soft delete.

### Metadata Block Policy Clarification

- **LUPOPEDIA headers** are required for documentation, PRDs, and code files as per constitutional rules.
- **File-backed content files** (e.g., Markdown entries) must NOT include embedded YAML or other metadata blocks. All metadata for file-backed content lives in the database row, not in the file itself.

## Two-Path Content Intake Doctrine

Content enters the system through exactly two paths. Both are valid. Both result in a complete header and a database row. See **PRD 16 ??15** for the authoritative specification; this section is the content-management view of the same doctrine.

### Path 1: Web UI (Privileged)

The actor uses the Lupopedia web interface. The system has full context at write time.

- Parent (channel/thread/collection) is selected in the UI before the file is written
- Header is generated and written to disk as part of the same operation
- Database row is created immediately
- ANUBIS is not involved

### Path 2: Filesystem (Unprivileged)

An agent writes a file directly to disk (e.g., Claude Code composing a captain's log entry or an implementation file). The agent does not have UI context.

- File is written to disk without a header ??? this is the expected and correct behavior for filesystem agents
- File is an **orphan** until ANUBIS processes it
- ANUBIS detects the file, infers the parent from content analysis, writes the header, creates the database row, and builds memory graph edges
- The file is fully registered after ANUBIS completes

### Orphan Lifecycle

```
[agent writes file] -> orphan on disk
        |
   [ANUBIS detects]
        |
   [ANUBIS infers parent, writes header, creates DB row, builds edges]
        |
   [file is now a first-class registered content node]
```

If ANUBIS cannot resolve the parent after analysis, the file is escalated to **PRD 19 (Garbage Collection)** for manual triage. It is never silently deleted.

### Rule for Content Storage

A content row in `lupo_contents` without a corresponding file header (for file-backed content) is a constitutional violation. A file on disk without a header is **not** ??? it is simply unprocessed. The violation clock starts when ANUBIS has had the opportunity to process the file and did not produce a result.

## TASK: Design CHRONOS Agent

Name:
CHRONOS - Coordinated Hierarchical Reasoning and Optimization for Network Operation Systems

Trigger condition:
Begin design ONLY AFTER:

* PRD 16 headers finalized
* Install SQL updated to 4.1.0 schema
* Test database aligned with new schema
* Python validators and handlers fully updated

Purpose:
Provide deterministic planning by dependency across agents and system layers.

Notes:
Do not begin implementation until schema and runtime layers are stable.
This agent depends on consistent header structure, memory_toon, atoms_toon, and transcript_jsonl being reliable.
