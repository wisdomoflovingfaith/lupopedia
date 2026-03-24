---
lupopedia.headers:
  lupopedia.schema: database_table
  file_path_from_root: lupo-docs/database/lupopedia/tables/active/lupo_uploads.md
  web_path: '[lupo_uploads](http://www.lupopedia.com/database/lupopedia/tables/active/lupo_uploads)'
  last_modified_utc: '20260317'
  channel_id: 42
  actor_id: 102
  actor_name: cursor
  delegation_chain: cursor:root
  artifact_type: table_documentation
  artifact_kind: table
  namespace: content
  purpose: Registry for binary file uploads
  tags:
  - database
  - table
  - content
  when_updated: '20260324174654'
lupopedia.edges:
  comment: Snapshot of edges for lupo_uploads table doc at 4.0.79 (grounded by repo
    search; non-exhaustive).
  meta: php_hits=1 python_hits=0
  outbound_edges:
  - to: database.table.lupo_uploads
    type: DEFINES_SCHEMA_FOR
    weight: 1.0
  - to: lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql
    type: schema_reference
    weight: 1.0
  - to: lupo-includes/modules/actors/actors-controller.php
    type: USED_IN_PHP
    weight: 0.9
  - to: (no_python_refs_found)
    type: USED_IN_PYTHON
    weight: 0.0
lupopedia.footer:
  last_verified: '20260317000000'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
---
# file: lupo_uploads ? web_path: http://www.lupopedia.com/database/lupopedia/tables/active/lupo_uploads
# Table Overview: lupo_uploads

- **Purpose**: Serves as the primary registry for all binary files uploaded to the Lupopedia system by actors. Unlike artifacts, which are often structured text, uploads strictly track external files and their storage locations.
- **Category**: Storage / File Management
- **Status**: Active
- **Version Introduced**: 4.0.0

## Column Documentation

| Column Name | Type | Nullable | Default | Description |
| :--- | :--- | :--- | :--- | :--- |
| `upload_id` | BIGINT | No | - | Primary Key. Numeric identifier. |
| `actor_id` | BIGINT | No | - | Reference to the actor who uploaded the file. |
| `channel_id` | BIGINT | Yes | - | Optional association with a channel where the upload occurred. |
| `original_filename` | VARCHAR(255) | No | - | The name of the file as reported by the user/client. |
| `stored_filename` | VARCHAR(255) | No | - | The internal unique filename used on the storage volume. |
| `file_extension` | VARCHAR(16) | No | - | The normalized extension (e.g., 'png', 'pdf'). |
| `mime_type` | VARCHAR(128) | No | - | The detected MIME type of the file. |
| `file_size_bytes` | BIGINT | No | - | The exact size of the file in bytes. |
| `storage_path` | VARCHAR(512) | No | - | The path relative to the uploads root where the file is stored. |
| `metadata_json` | JSON | Yes | - | Extended properties (e.g., image dimensions, hash). |
| `created_ymdhis` | BIGINT | No | 0 | Upload completion timestamp. |
| `updated_ymdhis` | BIGINT | No | - | Last modification timestamp. |
| `is_deleted` | TINYINT | No | 0 | Soft delete flag. |
| `deleted_ymdhis` | BIGINT | Yes | - | Soft delete timestamp. |

## Relationships

### Outbound References
- `lupo_actors.actor_id`: Uploader identity.
- `lupo_channels.channel_id`: Origin channel.

## Usage Notes

- **Storage Strategy**: Files are typically stored in a date-partitioned or actor-partitioned directory structure managed by the `UploadService`.
- **Retrieval**: The `stored_filename` should be used for all filesystem lookups; `original_filename` is for UI display only.

---
*Created by Antigravity (Actor 103) as part of the Database Documentation Program.*
