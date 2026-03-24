---
lupopedia.headers:
  lupopedia.version: "4.0.80"
  lupopedia.schema: "table"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/metadata/lupo_metadata.md"
  channel_id: 42
  actor_id: 102
  actor_name: "hermes"
  faucet_name: "cascade"
  artifact_type: "table_documentation"
  artifact_kind: "database_schema"
  purpose: "Complete documentation for lupo_metadata table - unified metadata system"
  tags: ["table_documentation", "metadata", "unified_system", "4.0.80"]
  created_ymdhis: 20260317172000
---

# lupo_metadata - Unified Metadata System

**Table Type**: Metadata Registry  
**Domain**: System Infrastructure  
**Criticality**: HIGH - Central metadata storage for all entities  
**Primary Key**: `metadata_id`  
**Unique Key**: `(entity_type, entity_id, domain_id, property_key)`

## Overview

The `lupo_metadata` table provides a unified metadata system for all entities in Lupopedia, replacing separate metadata tables (lupo_actor_meta, lupo_actor_properties, lupo_agent_properties). It supports hierarchical metadata, channel-scoped metadata, and flexible schema references.

### Key Characteristics
- **Unified Storage**: Single table for all entity metadata
- **Hierarchical Support**: Parent-child metadata relationships
- **Channel Scoped**: Metadata can be scoped to specific channels
- **Schema Flexible**: Supports various metadata schemas and types

## Table Structure

### Core Identity Fields

| Column | Type | Description | Notes |
|--------|------|-------------|--------|
| `metadata_id` | bigint | **PRIMARY KEY** - Unique metadata ID | Application-assigned |
| `entity_type` | varchar(32) | Type of entity | 'actor', 'channel', 'content', 'project', etc. |
| `entity_id` | bigint | Entity ID | References entity's primary key |
| `domain_id` | bigint | Domain ID | Optional domain scoping |
| `property_key` | varchar(255) | Property key/name | Metadata property identifier |

### Value Fields

| Column | Type | Description | Notes |
|--------|------|-------------|--------|
| `property_value` | text | Property value | Flexible text storage |
| `meta_type` | varchar(64) | Metadata type | Classification of metadata |

### Hierarchical Fields

| Column | Type | Description | Notes |
|--------|------|-------------|--------|
| `parent_metadata_id` | bigint | Parent metadata ID | For hierarchical relationships |
| `channel_id` | bigint | Channel scoping | Optional channel-specific metadata |

### Schema Fields

| Column | Type | Description | Notes |
|--------|------|-------------|--------|
| `class_name` | varchar(128) | Class name | For object-oriented metadata |
| `schema_ref` | varchar(64) | Schema reference | External schema identifier |

### Timestamp Fields

| Column | Type | Description | Default |
|--------|------|-------------|--------|
| `created_ymdhis` | bigint | Creation timestamp | 0 |
| `updated_ymdhis` | bigint | Last update timestamp | Current time |
| `deleted_ymdhis` | bigint | Deletion timestamp | NULL |

### Status Fields

| Column | Type | Description | Default |
|--------|------|-------------|--------|
| `is_deleted` | tinyint | Metadata is deleted | 0 |

## Indexes

### Primary Index
- `PRIMARY KEY (metadata_id)` - Unique metadata identifier

### Unique Index
- `lupo_metadata_unique_entity_domain_property (entity_type, entity_id, domain_id, property_key)` - Prevents duplicate properties

### Performance Indexes
- `lupo_metadata_idx_entity (entity_type, entity_id)` - Find entity metadata
- `lupo_metadata_idx_domain (domain_id)` - Find domain metadata
- `lupo_metadata_idx_meta_type (meta_type)` - Find by metadata type
- `lupo_metadata_idx_property_key (property_key)` - Find by property key
- `lupo_metadata_idx_created_ymdhis (created_ymdhis)` - Sort by creation time
- `lupo_metadata_idx_updated_ymdhis (updated_ymdhis)` - Sort by update time
- `lupo_metadata_idx_is_deleted (is_deleted)` - Filter deleted metadata

### Hierarchical Indexes
- `lupo_metadata_idx_channel_id (channel_id)` - Find channel metadata
- `lupo_metadata_idx_parent_metadata_id (parent_metadata_id)` - Find child metadata
- `lupo_metadata_idx_class_name (class_name)` - Find by class name

### Composite Indexes
- `lupo_metadata_idx_entity_deleted (entity_type, entity_id, is_deleted)` - Entity metadata with status
- `lupo_metadata_idx_channel_deleted (channel_id, is_deleted)` - Channel metadata with status
- `lupo_metadata_idx_parent_deleted (parent_metadata_id, is_deleted)` - Hierarchical with status
- `lupo_metadata_idx_meta_type_deleted (meta_type, is_deleted)` - Type with status
- `lupo_metadata_idx_class_deleted (class_name, is_deleted)` - Class with status

## Key Relationships

### Entity Relationships
- **Actors**: `entity_type='actor'`, `entity_id` → `lupo_actors.actor_id`
- **Channels**: `entity_type='channel'`, `entity_id` → `lupo_channels.channel_id`
- **Content**: `entity_type='content'`, `entity_id` → `lupo_channel_content.channel_content_id`
- **Projects**: `entity_type='project'`, `entity_id` → `lupo_projects.project_id`

### Hierarchical Relationships
- **Parent**: `lupo_metadata.parent_metadata_id` → `lupo_metadata.metadata_id`
- **Children**: Reverse relationship for hierarchical metadata

### Domain Relationships
- **Domain**: `lupo_metadata.domain_id` → `lupo_domains.domain_id`
- **Channel**: `lupo_metadata.channel_id` → `lupo_channels.channel_id`

## Usage Patterns

### Basic Metadata Storage
```php
// Store entity metadata
$metadata = [
    'metadata_id' => generateId(),
    'entity_type' => 'actor',
    'entity_id' => 102,
    'property_key' => 'preference_theme',
    'property_value' => 'dark',
    'meta_type' => 'preference',
    'created_ymdhis' => 20260317172000,
    'updated_ymdhis' => 20260317172000
];
```

### Channel-Scoped Metadata
```php
// Store channel-specific metadata
$metadata = [
    'metadata_id' => generateId(),
    'entity_type' => 'actor',
    'entity_id' => 102,
    'channel_id' => 42,
    'property_key' => 'notification_level',
    'property_value' => 'mentions_only',
    'meta_type' => 'preference',
    'created_ymdhis' => 20260317172000,
    'updated_ymdhis' => 20260317172000
];
```

### Hierarchical Metadata
```php
// Parent metadata
$parent = [
    'metadata_id' => generateId(),
    'entity_type' => 'channel',
    'entity_id' => 42,
    'property_key' => 'settings',
    'property_value' => json_encode(['theme' => 'dark']),
    'meta_type' => 'settings_group',
    'created_ymdhis' => 20260317172000,
    'updated_ymdhis' => 20260317172000
];

// Child metadata
$child = [
    'metadata_id' => generateId(),
    'entity_type' => 'channel',
    'entity_id' => 42,
    'parent_metadata_id' => $parent['metadata_id'],
    'property_key' => 'theme_color',
    'property_value' => '#1a73e8',
    'meta_type' => 'setting',
    'created_ymdhis' => 20260317172000,
    'updated_ymdhis' => 20260317172000
];
```

### Metadata Retrieval
```php
// Get all entity metadata
$metadata = MetadataService::getEntityMetadata('actor', 102);

// Get specific property
$value = MetadataService::getProperty('actor', 102, 'preference_theme');

// Get channel-scoped metadata
$channelMetadata = MetadataService::getChannelMetadata('actor', 102, 42);

// Get hierarchical metadata
$hierarchy = MetadataService::getMetadataHierarchy($parentMetadataId);
```

## Entity Types

### Common Entity Types
- **actor**: User and agent metadata
- **channel**: Channel configuration and settings
- **content**: Content properties and classifications
- **project**: Project metadata and configuration
- **task**: Task properties and status
- **domain**: Domain-specific metadata
- **system**: System-wide configuration

### Metadata Types
- **preference**: User preferences and settings
- **configuration**: System configuration values
- **property**: Entity properties
- **setting**: Configurable settings
- **attribute**: Entity attributes
- **tag**: Classification tags
- **annotation**: Notes and annotations

## Hierarchical Metadata

### Hierarchy Structure
```
Root Metadata (parent_metadata_id = NULL)
├── Settings Group (meta_type = 'settings_group')
│   ├── Theme Setting (meta_type = 'setting')
│   ├── Notification Setting (meta_type = 'setting')
│   └── Privacy Setting (meta_type = 'setting')
├── Preferences Group (meta_type = 'preferences_group')
│   ├── UI Preference (meta_type = 'preference')
│   ├── Language Preference (meta_type = 'preference')
│   └── Accessibility Preference (meta_type = 'preference')
└── Custom Group (meta_type = 'custom_group')
    └── Custom Property (meta_type = 'property')
```

### Hierarchy Operations
```php
// Create hierarchy
$rootId = MetadataService::createRootMetadata($entityType, $entityId, 'settings');
$childId = MetadataService::createChildMetadata($rootId, 'theme', 'dark');

// Get hierarchy tree
$tree = MetadataService::getMetadataTree($rootId);

// Flatten hierarchy
$flat = MetadataService::flattenHierarchy($rootId);

// Validate hierarchy
$isValid = MetadataService::validateHierarchy($rootId);
```

## Channel-Scoped Metadata

### Channel Scoping Rules
- **Global Metadata**: No channel_id specified
- **Channel Metadata**: Specific to channel_id
- **Inheritance**: Channel metadata inherits from global
- **Override**: Channel metadata overrides global values

### Scoping Examples
```php
// Global preference
MetadataService::setProperty('actor', 102, 'theme', 'light');

// Channel-specific override
MetadataService::setProperty('actor', 102, 'theme', 'dark', ['channel_id' => 42]);

// Get value with channel context
$value = MetadataService::getProperty('actor', 102, 'theme', ['channel_id' => 42]);
// Returns 'dark' (channel override)

$value = MetadataService::getProperty('actor', 102, 'theme', ['channel_id' => 43]);
// Returns 'light' (global default)
```

## Schema Integration

### Schema References
```php
// Schema-based metadata
$metadata = [
    'schema_ref' => 'lupopedia_actor_v1',
    'class_name' => 'ActorMetadata',
    'property_key' => 'capabilities',
    'property_value' => json_encode(['create_content', 'moderate'])
];
```

### Schema Validation
```php
// Validate against schema
$isValid = MetadataService::validateAgainstSchema($metadata, 'lupopedia_actor_v1');

// Get schema definition
$schema = MetadataService::getSchema('lupopedia_actor_v1');

// Auto-validate on save
$metadata = MetadataService::saveWithValidation($metadata, $schema);
```

## Performance Considerations

### High-Volume Operations
- Cache frequently accessed metadata
- Batch metadata operations for efficiency
- Use appropriate indexes for query patterns
- Implement metadata caching per entity

### Optimization Strategies
```php
// Batch metadata operations
$metadataBatch = [
    ['property_key' => 'theme', 'property_value' => 'dark'],
    ['property_key' => 'language', 'property_value' => 'en'],
    ['property_key' => 'timezone', 'property_value' => 'UTC']
];
MetadataService::batchSetProperties('actor', 102, $metadataBatch);

// Cache entity metadata
$cacheKey = "metadata:actor:102";
$metadata = CacheService::get($cacheKey);
if (!$metadata) {
    $metadata = MetadataService::getEntityMetadata('actor', 102);
    CacheService::set($cacheKey, $metadata, 300);
}
```

## Common Queries

### Entity Metadata
```sql
SELECT property_key, property_value, meta_type
FROM lupo_metadata 
WHERE entity_type = 'actor' 
  AND entity_id = 102 
  AND is_deleted = 0
ORDER BY meta_type, property_key;
```

### Channel-Scoped Metadata
```sql
SELECT property_key, property_value, channel_id
FROM lupo_metadata 
WHERE entity_type = 'actor' 
  AND entity_id = 102 
  AND (channel_id = 42 OR channel_id IS NULL)
  AND is_deleted = 0
ORDER BY channel_id DESC, property_key;
```

### Hierarchical Metadata
```sql
SELECT 
    m1.metadata_id,
    m1.property_key,
    m1.property_value,
    m2.property_key as parent_key
FROM lupo_metadata m1
LEFT JOIN lupo_metadata m2 ON m1.parent_metadata_id = m2.metadata_id
WHERE m1.entity_type = 'channel' 
  AND m1.entity_id = 42 
  AND m1.is_deleted = 0;
```

### Metadata by Type
```sql
SELECT 
    entity_type,
    COUNT(*) as property_count,
    COUNT(DISTINCT entity_id) as entity_count
FROM lupo_metadata 
WHERE is_deleted = 0
GROUP BY entity_type
ORDER BY property_count DESC;
```

## Security Considerations

### Access Control
- Validate entity access before metadata operations
- Implement channel-specific access controls
- Protect sensitive metadata properties
- Audit metadata access and modifications

### Data Validation
- Validate property keys against allowed values
- Sanitize property values before storage
- Implement schema validation where applicable
- Protect against metadata injection attacks

### Privacy Protection
- Encrypt sensitive metadata values
- Implement metadata access logging
- Respect user privacy preferences
- Provide metadata deletion capabilities

## Integration Points

### Entity System
- Provides metadata storage for all entity types
- Supports entity-specific metadata schemas
- Handles entity lifecycle metadata
- Integrates with entity creation/deletion

### Channel System
- Channel-scoped metadata support
- Channel configuration storage
- Channel preference management
- Channel-specific overrides

### Configuration System
- System-wide configuration storage
- Environment-specific metadata
- Feature flag management
- Runtime configuration

## Troubleshooting

### Common Issues
1. **Duplicate Properties**: Check unique constraint violation
2. **Hierarchy Issues**: Validate parent-child relationships
3. **Channel Scoping**: Verify channel_id inheritance
4. **Schema Validation**: Check schema_ref and class_name

### Debug Queries
```sql
-- Check for duplicate properties
SELECT entity_type, entity_id, property_key, COUNT(*) as count
FROM lupo_metadata 
WHERE is_deleted = 0
GROUP BY entity_type, entity_id, property_key
HAVING COUNT(*) > 1;

-- Find orphaned metadata
SELECT m.* 
FROM lupo_metadata m
LEFT JOIN lupo_actors a ON m.entity_type = 'actor' AND m.entity_id = a.actor_id
WHERE m.entity_type = 'actor' 
  AND a.actor_id IS NULL 
  AND m.is_deleted = 0;

-- Check hierarchy integrity
SELECT 
    parent.metadata_id as parent_id,
    parent.property_key as parent_key,
    child.metadata_id as child_id,
    child.property_key as child_key
FROM lupo_metadata parent
JOIN lupo_metadata child ON child.parent_metadata_id = parent.metadata_id
WHERE parent.is_deleted = 0 
  AND child.is_deleted = 0;
```

## Migration Notes

### Version History
- **v4.0.68**: Consolidated from separate metadata tables
- **v4.0.70**: Added channel_id and parent_metadata_id
- **v4.0.75**: Enhanced schema support and class_name
- **v4.0.80**: Current schema with hierarchical and channel support

### Migration from Legacy Tables
- **lupo_actor_meta** → entity_type='actor', meta_type='meta'
- **lupo_actor_properties** → entity_type='actor', meta_type='property'
- **lupo_agent_properties** → entity_type='agent', meta_type='property'

## Best Practices

### Metadata Organization
- Use consistent property naming conventions
- Group related properties with meta_type
- Implement hierarchical structures for complex metadata
- Use schema validation for structured metadata

### Performance Optimization
- Cache frequently accessed metadata
- Batch metadata operations when possible
- Use appropriate indexes for query patterns
- Monitor metadata access patterns

### Security Practices
- Validate all metadata inputs
- Implement proper access controls
- Encrypt sensitive metadata values
- Regular audit of metadata access

---

**Table Statistics**:
- **Records**: Variable based on metadata usage
- **Size**: Large - grows with system complexity
- **Growth Rate**: Medium - new metadata added as needed
- **Criticality**: HIGH - Central metadata system

**Dependencies**:
- **Required By**: All entity systems
- **References**: All entity tables
- **Integrations**: Entity System, Channel System, Configuration

**Maintenance**:
- **Backup Priority**: HIGH
- **Archive Policy**: Soft delete with `is_deleted`
- **Cleanup**: Review orphaned metadata quarterly
- **Monitoring**: Track metadata growth and access patterns
