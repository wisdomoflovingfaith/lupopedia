# lupo_channel_content

## Overview

The `lupo_channel_content` table manages federation node content and web path mapping within the Lupopedia Semantic OS. This table provides the foundation for FLARE federation node management.

## Schema

### Table Definition

```sql
CREATE TABLE lupo_channel_content (
  channel_content_id bigint NOT NULL AUTO_INCREMENT,
  channel_id int NOT NULL,
  federation_node_id int NOT NULL,
  file_path varchar(500) NOT NULL,
  web_path varchar(500) NOT NULL,
  metadata_json json DEFAULT NULL,
  created_ymdhis bigint NOT NULL DEFAULT 0,
  updated_ymdhis bigint NOT NULL DEFAULT 0,
  is_deleted tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (channel_content_id)
);
```

### Field Descriptions

| Field | Type | Description | Example |
|-------|------|-------------|---------|
| `channel_content_id` | bigint | Primary key, auto-increment identifier | 12345 |
| `channel_id` | int | Channel identifier (42 for development) | 42 |
| `federation_node_id` | int | Federation node identifier | 0 |
| `file_path` | varchar(500) | Repository file path | `channels/42/content/federation_node_id/0/FLARE.md` |
| `web_path` | varchar(500) | Canonical web URL | `http://www.lupopedia.com/FLARE` |
| `metadata_json` | json | Flexible metadata storage | `{"description": "Root FLARE definition"}` |
| `created_ymdhis` | bigint | Creation timestamp (YYYYMMDDHHIISS) | 20260301120000 |
| `updated_ymdhis` | bigint | Last update timestamp (YYYYMMDDHHIISS) | 20260301120000 |
| `is_deleted` | tinyint | Soft delete flag (0=active, 1=deleted) | 0 |

## Indexes

### Performance Indexes

```sql
CREATE INDEX lupo_channel_content_idx_channel ON lupo_channel_content (channel_id);
CREATE INDEX lupo_channel_content_idx_federation_node ON lupo_channel_content (federation_node_id);
CREATE INDEX lupo_channel_content_idx_file_path ON lupo_channel_content (file_path);
CREATE INDEX lupo_channel_content_idx_web_path ON lupo_channel_content (web_path);
CREATE INDEX lupo_channel_content_idx_created ON lupo_channel_content (created_ymdhis);
CREATE INDEX lupo_channel_content_idx_updated ON lupo_channel_content (updated_ymdhis);
CREATE INDEX lupo_channel_content_idx_is_deleted ON lupo_channel_content (is_deleted);
```

### Index Purposes

| Index | Purpose | Use Case |
|--------|---------|-----------|
| `idx_channel` | Channel-based queries | Find all content in a channel |
| `idx_federation_node` | Federation node queries | Find content for specific federation node |
| `idx_file_path` | File path lookups | Quick file path resolution |
| `idx_web_path` | Web path lookups | URL resolution and routing |
| `idx_created` | Time-based queries | Recent content queries |
| `idx_updated` | Update tracking | Modified content queries |
| `idx_is_deleted` | Soft delete filtering | Active content only |

## Usage Patterns

### Federation Node Management

```sql
-- Get all federation nodes in a channel
SELECT federation_node_id, file_path, web_path, metadata_json
FROM lupo_channel_content 
WHERE channel_id = 42 AND is_deleted = 0
ORDER BY federation_node_id;

-- Get specific federation node
SELECT file_path, web_path, metadata_json
FROM lupo_channel_content 
WHERE channel_id = 42 AND federation_node_id = 0 AND is_deleted = 0;

-- Search by web path
SELECT content_id, file_path, metadata_json
FROM lupo_channel_content 
WHERE web_path = 'http://www.lupopedia.com/FLARE' AND is_deleted = 0;
```

### Integration Points

#### FLARE System
- **Web Path Resolution**: Maps repository paths to canonical URLs
- **Federation Hierarchy**: Supports multiple federation nodes per channel
- **Metadata Storage**: JSON field for flexible federation requirements

#### Semantic OS
- **Content Management**: Integrates with lupo_contents for unified content handling
- **Channel Organization**: Aligns with channel-based content organization
- **Actor Registry**: Links to actors/registry.json for actor validation

## Data Relationships

### Foreign Key Relationships

| Table | Relationship | Purpose |
|-------|------------|---------|
| `lupo_channels` | channel_id | Channel validation and metadata |
| `lupo_actors` | actor_id | Actor validation and delegation |
| `lupo_contents` | content_id | Content federation and linking |

### Constraints and Rules

### Data Integrity
- **Primary Key**: `content_id` ensures unique content identification
- **Soft Deletes**: `is_deleted` prevents data loss while maintaining history
- **Timestamp Format**: All timestamps use YYYYMMDDHHIISS UTC format

### Business Rules
- **Channel 42**: Development channel for federation content
- **Federation Node 0**: Root FLARE definition node
- **Web Path Uniqueness**: Each web_path should be unique within channel
- **File Path Validation**: Repository paths must be valid and accessible

## Migration Notes

### Version History
- **4.0.52**: Initial table creation for federation node management
- **MySQL 5.7 Compatible**: No partial indexes, no JSON constraints
- **Performance Optimized**: Comprehensive indexing for common query patterns

### Related Documentation

- **FLARE Doctrine**: `docs/doctrine/FLARE/FLARE_DOCTRINE.md`
- **FLARE API**: `docs/api/FLARE_API.md`
- **Installation**: `database/migrations/install_lupopedia.sql`
- **Federation Guide**: `channels/42/content/federation_node_id/0/FLARE.md`

---

**Table Created**: 2026-03-01  
**Lead Agent**: Windsurf (1002)  
**Version**: 4.0.52  
**Status**: ✅ DOCUMENTED
