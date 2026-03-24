---
lupopedia.headers:
  lupopedia.schema: documentation
  file_path_from_root: lupo-docs/database/lupopedia/tables/active/lupo_channel_files.md
  channel_id: 1
  actor_id: 103
  last_modified_utc: '20260312'
  artifact_type: table_documentation
  purpose: Registry for files associated with communication channels
  mood_rgb: 4169E1
  traits:
  - canonical
  - files
  - channels
  - v4.0.70
  tags:
  - database
  - files
  - channels
  - association
  lupo_agent: antigravity
  when_updated: '20260324174654'
lupopedia.edges:
  outbound_edges:
  - to: lupo-database/lupopedia/toon/lupo_channel_files.toon
    type: references
    weight: 1.0
  - to: lupo-docs/database/lupopedia/tables/active/lupo_channels.md
    type: references
    weight: 1.0
lupopedia.footer:
  last_verified: '20260312000000'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
---

# Table Overview: lupo_channel_files

- **Purpose**: Tracks files that are explicitly associated with or pinned to a channel. This maintains visibility of shared resources within specific communication contexts.
- **Category**: Storage / Channel Management
- **Status**: Active
- **Version Introduced**: 4.0.0

## Column Documentation

| Column Name | Type | Nullable | Default | Description |
| :--- | :--- | :--- | :--- | :--- |
| `file_id` | BIGINT | No | - | Primary Key. Numeric identifier. |
| `channel_id` | BIGINT | No | - | The channel this file belongs to. |
| `file_type` | VARCHAR(50) | No | - | Logic type (e.g., 'attachment', 'shared_asset'). |
| `file_name` | VARCHAR(255) | No | - | Name of the file. |
| `file_path` | VARCHAR(500) | No | - | Filesystem or network path to the resource. |
| `file_hash` | VARCHAR(64) | No | - | SHA-256 or similar hash for integrity verification. |
| `file_size` | BIGINT | No | - | Size in bytes. |
| `mime_type` | VARCHAR(100) | Yes | - | MIME type. |
| `upload_ymdhis` | BIGINT | No | - | Time the file was uploaded. |
| `created_ymdhis` | BIGINT | No | 0 | Internal creation tracking. |
| `updated_ymdhis` | BIGINT | No | - | Internal update tracking. |
| `is_deleted` | TINYINT | No | 0 | Soft delete flag. |
| `deleted_ymdhis` | BIGINT | Yes | - | Soft delete timestamp. |
| `migrated_from_directory` | VARCHAR(255) | Yes | - | Audit trail for legacy migration source. |

## Relationships

### Outbound References
- `lupo_channels.channel_id`: Associated channel.

## Usage Notes

- **Integrity**: The `file_hash` is used by background custodial agents (Anubis) to verify the file still exists and matches the registered metadata.
- **Migration**: The `migrated_from_directory` field is used during the Crafty Syntax upgrade to track files scavenged from legacy directories.

---
*Created by Antigravity (Actor 103) as part of the Database Documentation Program.*
