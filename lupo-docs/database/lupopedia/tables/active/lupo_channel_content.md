---
lupopedia.headers:
  lupopedia.schema: table
  file_path_from_root: lupo-docs/database/lupopedia/tables/active/lupo_channel_content.md
  channel_id: 42
  actor_id: 102
  actor_name: hermes
  faucet_name: cascade
  artifact_type: table_documentation
  artifact_kind: database_schema
  purpose: Complete documentation for lupo_channel_content table - content management
    and federation
  tags:
  - table_documentation
  - content
  - federation
  - channel
  - 4.0.80
  created_ymdhis: 20260317171000
  when_updated: '20260324174654'
lupopedia:
  footer:
    last_verified: '20260324174654'
    last_verified_by: cursor
    last_verified_by_actor_id: 102
    orchestrator: cursor:root
---

# lupo_channel_content - Channel Content Management

**Table Type**: Content Registry  
**Domain**: Content System  
**Criticality**: HIGH - Manages all channel content and federation  
**Primary Key**: `channel_content_id` (AUTO_INCREMENT)

## Overview

The `lupo_channel_content` table manages content within channels, providing a bridge between the database and the file system. It handles content organization, federation node mapping, and web path resolution for channel-based content delivery.

### Key Characteristics
- **Content Registry**: Tracks all channel content in the system
- **Federation Ready**: Supports multi-node content federation
- **Path Mapping**: Maps file system paths to web URLs
- **Metadata Storage**: Flexible metadata for content management

## Table Structure

### Core Identity Fields

| Column | Type | Description | Notes |
|--------|------|-------------|--------|
| `channel_content_id` | bigint | **PRIMARY KEY** - Auto-increment ID | Unique content identifier |
| `channel_id` | bigint | Channel ID | References `lupo_channels.channel_id` |
| `federation_node_id` | bigint | Federation node ID | References `lupo_federation_nodes.node_id` |

### Path Fields

| Column | Type | Description | Notes |
|--------|------|-------------|--------|
| `file_path` | varchar(500) | File system path | Relative to project root |
| `web_path` | varchar(500) | Web-accessible URL | For content delivery |

### Metadata Fields

| Column | Type | Description | Notes |
|--------|------|-------------|--------|
| `metadata_json` | json | Content metadata | Flexible key-value storage |

### Timestamp Fields

| Column | Type | Description | Default |
|--------|------|-------------|--------|
| `created_ymdhis` | bigint | Creation timestamp | 0 |
| `updated_ymdhis` | bigint | Last update timestamp | 0 |

### Status Fields

| Column | Type | Description | Default |
|--------|------|-------------|--------|
| `is_deleted` | tinyint | Content is deleted | 0 |

## Indexes

### Primary Index
- `PRIMARY KEY (channel_content_id)` - Auto-increment unique identifier

### Performance Indexes
- `lupo_channel_content_idx_channel (channel_id)` - Find content by channel
- `lupo_channel_content_idx_federation_node (federation_node_id)` - Find content by node
- `lupo_channel_content_idx_file_path (file_path)` - Find content by file path
- `lupo_channel_content_idx_web_path (web_path)` - Find content by web path
- `lupo_channel_content_idx_created (created_ymdhis)` - Sort by creation time
- `lupo_channel_content_idx_updated (updated_ymdhis)` - Sort by update time
- `lupo_channel_content_idx_is_deleted (is_deleted)` - Filter deleted content

## Key Relationships

### Many-to-One Relationships
- **Channel**: `lupo_channel_content.channel_id` → `lupo_channels.channel_id`
- **Federation Node**: `lupo_channel_content.federation_node_id` → `lupo_federation_nodes.node_id`

### Content Types
This table manages various types of channel content:
- **Documents**: Markdown files, documentation
- **Media**: Images, videos, audio files
- **Artifacts**: Channel artifacts, broadcasts, threads
- **Assets**: CSS, JavaScript, templates
- **Uploads**: User-uploaded files

## Usage Patterns

### Content Registration
```php
// Register new content
$content = [
    'channel_id' => 42,
    'federation_node_id' => 1,
    'file_path' => 'channels/42/content/example.md',
    'web_path' => '/channels/42/content/example',
    'metadata_json' => [
        'content_type' => 'document',
        'author' => 'hermes',
        'version' => '1.0',
        'tags' => ['documentation', 'example']
    ],
    'created_ymdhis' => 20260317171000,
    'updated_ymdhis' => 20260317171000
];
```

### Content Retrieval
```php
// Get content by channel
$contents = ContentService::getChannelContents($channelId);

// Get content by web path
$content = ContentService::getContentByWebPath('/channels/42/content/example');

// Get content by file path
$content = ContentService::getContentByFilePath('channels/42/content/example.md');
```

### Content Management
```php
// Update content metadata
ContentService::updateContentMetadata($contentId, $metadata);

// Soft delete content
ContentService::deleteContent($contentId);

// Restore deleted content
ContentService::restoreContent($contentId);
```

## Metadata Structure

### Standard Metadata Fields
```json
{
    "content_type": "document|media|artifact|asset|upload",
    "author": "actor_name",
    "version": "1.0.0",
    "tags": ["tag1", "tag2"],
    "description": "Content description",
    "language": "en",
    "size": 1024,
    "mime_type": "text/markdown",
    "checksum": "sha256_hash",
    "last_modified": "2026-03-17T17:10:00Z"
}
```

### Channel-Specific Metadata
```json
{
    "artifact_type": "broadcast|thread|content|task|rule",
    "thread_id": 1001,
    "message_id": 20260317120001,
    "actor_id": 102,
    "message_type": "text",
    "routing_type": "broadcast"
}
```

### Media Metadata
```json
{
    "width": 1920,
    "height": 1080,
    "duration": 120,
    "format": "mp4",
    "quality": "high",
    "thumbnail": "/channels/42/thumbnails/example.jpg"
}
```

## Federation Support

### Federation Node Mapping
Each content entry is associated with a federation node:
- **Node 0**: Local/primary node
- **Node 1+**: Remote federation nodes

### Content Synchronization
```php
// Sync content to federation nodes
ContentService::syncToFederation($contentId, $targetNodes);

// Check content sync status
$syncStatus = ContentService::getSyncStatus($contentId);

// Resolve content from any node
$content = ContentService::resolveContent($webPath, $preferredNode);
```

### Federation Metadata
```json
{
    "federation": {
        "source_node": 1,
        "sync_nodes": [0, 2, 3],
        "last_sync": "2026-03-17T17:10:00Z",
        "sync_status": "synced",
        "conflict_resolution": "source_wins"
    }
}
```

## Path Management

### File Path Structure
- **Channel Content**: `channels/{channel_id}/content/{path}`
- **Broadcasts**: `channels/{channel_id}/broadcasts/{filename}`
- **Threads**: `channels/{channel_id}/threads/{thread_id}/{filename}`
- **Tasks**: `channels/{channel_id}/tasks/{status}/{filename}`
- **Rules**: `channels/{channel_id}/rules/{filename}`

### Web Path Structure
- **Content**: `/channels/{channel_id}/content/{path}`
- **API**: `/api/channels/{channel_id}/content/{path}`
- **Static**: `/static/channels/{channel_id}/{path}`

### Path Resolution
```php
// Convert file path to web path
$webPath = ContentService::filePathToWebPath($filePath);

// Convert web path to file path
$filePath = ContentService::webPathToFilePath($webPath);

// Validate path security
$isValid = ContentService::validatePath($path, $channelId);
```

## Security Considerations

### Path Security
- Validate all paths to prevent directory traversal
- Restrict content to channel-specific directories
- Sanitize file names and web paths
- Implement proper access controls

### Content Access
- Check channel membership before content access
- Validate federation node permissions
- Implement content-level access controls
- Audit content access attempts

### Metadata Security
- Sanitize metadata before storage
- Validate JSON structure
- Protect sensitive metadata fields
- Implement metadata encryption where needed

## Performance Considerations

### High-Volume Operations
- Cache frequently accessed content metadata
- Batch content operations for efficiency
- Use appropriate indexes for path lookups
- Implement content caching for static assets

### Optimization Strategies
```php
// Cache content metadata
$metadata = CacheService::getContentMetadata($contentId);
if (!$metadata) {
    $metadata = ContentService::getContentMetadata($contentId);
    CacheService::setContentMetadata($contentId, $metadata, 300);
}

// Batch content registration
$contents = [
    ['file_path' => 'path1', 'web_path' => 'web1'],
    ['file_path' => 'path2', 'web_path' => 'web2']
];
ContentService::batchRegisterContent($contents);
```

## Common Queries

### Channel Content Listing
```sql
SELECT 
    channel_content_id,
    file_path,
    web_path,
    created_ymdhis,
    updated_ymdhis
FROM lupo_channel_content 
WHERE channel_id = 42 
  AND is_deleted = 0
ORDER BY created_ymdhis DESC;
```

### Content by Type
```sql
SELECT 
    file_path,
    web_path,
    metadata_json->>'$.content_type' as content_type
FROM lupo_channel_content 
WHERE channel_id = 42 
  AND is_deleted = 0
  AND metadata_json->>'$.content_type' = 'document';
```

### Federation Content Sync
```sql
SELECT 
    channel_id,
    COUNT(*) as content_count,
    federation_node_id
FROM lupo_channel_content 
WHERE is_deleted = 0
GROUP BY channel_id, federation_node_id
ORDER BY channel_id, federation_node_id;
```

### Content Search
```sql
SELECT 
    channel_content_id,
    file_path,
    web_path,
    metadata_json
FROM lupo_channel_content 
WHERE channel_id = 42 
  AND is_deleted = 0
  AND (file_path LIKE '%keyword%' 
       OR web_path LIKE '%keyword%'
       OR metadata_json LIKE '%keyword%');
```

## Integration Points

### File System
- Maps database entries to actual files
- Handles file creation, updates, and deletion
- Manages file permissions and ownership
- Supports various content types and formats

### Web Server
- Provides web path resolution for content delivery
- Handles static content serving
- Supports content caching and CDN integration
- Manages content access control

### Federation System
- Synchronizes content across federation nodes
- Handles content conflicts and resolution
- Manages remote content access
- Supports content replication strategies

## Troubleshooting

### Common Issues
1. **Path Not Found**: Check file_path and web_path accuracy
2. **Access Denied**: Verify channel membership and permissions
3. **Sync Issues**: Check federation node connectivity
4. **Metadata Errors**: Validate JSON structure

### Debug Queries
```sql
-- Check content existence
SELECT * FROM lupo_channel_content 
WHERE channel_id = 42 
  AND file_path = 'channels/42/content/example.md'
  AND is_deleted = 0;

-- Find orphaned content
SELECT cc.* 
FROM lupo_channel_content cc
LEFT JOIN lupo_channels c ON cc.channel_id = c.channel_id
WHERE c.channel_id IS NULL 
  AND cc.is_deleted = 0;

-- Check federation sync
SELECT 
    channel_id,
    federation_node_id,
    COUNT(*) as content_count
FROM lupo_channel_content 
WHERE is_deleted = 0
GROUP BY channel_id, federation_node_id
ORDER BY channel_id, federation_node_id;
```

## Migration Notes

### Version History
- **v4.0.60**: Initial content management system
- **v4.0.70**: Added federation support
- **v4.0.75**: Enhanced metadata with JSON storage
- **v4.0.80**: Current schema with comprehensive content management

### Breaking Changes
- Added federation_node_id for multi-node support
- Enhanced metadata_json with structured fields
- Improved path management and security

## Best Practices

### Content Organization
- Use consistent path structures within channels
- Implement proper content type classification
- Maintain clear metadata standards
- Use descriptive file names and paths

### Performance Optimization
- Cache frequently accessed content metadata
- Batch content operations when possible
- Use appropriate indexes for query patterns
- Monitor content access patterns

### Security Practices
- Validate all paths and file names
- Implement proper access controls
- Regular audit of content permissions
- Protect against content injection attacks

---

**Table Statistics**:
- **Records**: Variable based on content volume
- **Size**: Large - grows with content creation
- **Growth Rate**: High - new content added regularly
- **Criticality**: HIGH - Core content management

**Dependencies**:
- **Required By**: Content delivery throughout system
- **References**: `lupo_channels`, `lupo_federation_nodes`
- **Integrations**: File System, Web Server, Federation

**Maintenance**:
- **Backup Priority**: HIGH
- **Archive Policy**: Soft delete with `is_deleted`
- **Cleanup**: Review orphaned content quarterly
- **Monitoring**: Track content growth and access patterns
