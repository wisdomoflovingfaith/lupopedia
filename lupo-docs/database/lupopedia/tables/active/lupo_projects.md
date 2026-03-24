---
lupopedia.headers:
  lupopedia.version: "4.0.80"
  lupopedia.schema: "table"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/projects/lupo_projects.md"
  channel_id: 42
  actor_id: 102
  actor_name: "hermes"
  faucet_name: "cascade"
  artifact_type: "table_documentation"
  artifact_kind: "database_schema"
  purpose: "Complete documentation for lupo_projects table - project management"
  tags: ["table_documentation", "projects", "management", "4.0.80"]
  created_ymdhis: 20260317_220000
---

# lupo_projects - Project Management

**Table Type**: Project Registry  
**Domain**: Project System  
**Criticality**: MEDIUM - Project organization and work coordination  
**Primary Key**: `project_id` (application-assigned)

## Overview

The `lupo_projects` table manages project definitions, metadata, and lifecycle status in Lupopedia. It provides the foundation for project-based work organization, task management, and coordination across the platform.

### Key Characteristics
- **Project Registry**: Central storage for all project definitions
- **Federation Ready**: Multi-node project support with federation_node_id
- **Metadata Rich**: Flexible JSON metadata for project configuration
- **Lifecycle Management**: Complete project status and archival tracking

## Table Structure

### Core Identity Fields

| Column | Type | Description | Notes |
|--------|------|-------------|--------|
| `project_id` | bigint | **PRIMARY KEY** - Unique project ID | Application-assigned, not auto-increment |
| `project_key` | varchar(64) | Project identifier key | Unique within federation node |
| `project_slug` | varchar(255) | URL-friendly project slug | Unique within federation node |
| `project_name` | varchar(255) | Display name | Human-readable project name |

### Configuration Fields

| Column | Type | Description | Default |
|--------|------|-------------|--------|
| `federation_node_id` | bigint | Federation node ID | Required for multi-node support |
| `default_channel_id` | bigint | Default channel for project | Optional, references `lupo_channels.channel_id` |
| `orchestrator_id` | bigint | Project orchestrator actor | References `lupo_actors.actor_id` |
| `project_type` | varchar(64) | Type of project | 'standard' |
| `description` | text | Project description | Optional project summary |
| `github_repository` | varchar(512) | GitHub repository URL | Optional external repository link |
| `status` | varchar(32) | Project status | 'active' |

### Status Fields

| Column | Type | Description | Default |
|--------|------|-------------|--------|
| `is_active` | tinyint | Project is active | 1 |
| `is_deleted` | tinyint | Project is deleted | 0 |
| `is_archived` | tinyint | Project is archived | 0 |
| `is_frozen` | tinyint | Project is frozen | 0 |

### Metadata Fields

| Column | Type | Description | Notes |
|--------|------|-------------|--------|
| `metadata_json` | json | Project metadata | Flexible key-value storage |

### Timestamp Fields

| Column | Type | Description | Default |
|--------|------|-------------|--------|
| `created_ymdhis` | bigint | Creation timestamp | 0 |
| `updated_ymdhis` | bigint | Last update timestamp | 0 |
| `deleted_ymdhis` | bigint | Deletion timestamp | 0 |

### Audit Fields

| Column | Type | Description | Notes |
|--------|------|-------------|--------|
| `created_by_actor_id` | bigint | Actor who created project | References `lupo_actors.actor_id` |
| `updated_by_actor_id` | bigint | Actor who last updated project | References `lupo_actors.actor_id` |

## Indexes

### Primary Index
- `PRIMARY KEY (project_id)` - Unique project identifier

### Unique Indexes
- `uk_project_key_node (project_key, federation_node_id)` - Unique key per node
- `uk_project_slug_node (project_slug, federation_node_id)` - Unique slug per node

### Performance Indexes
- `lupo_projects_idx_created (created_ymdhis)` - Time-based sorting
- `lupo_projects_idx_default_channel (default_channel_id)` - Channel-based filtering
- `lupo_projects_idx_federation_node (federation_node_id, status, is_deleted)` - Node-based filtering
- `lupo_projects_idx_orchestrator (orchestrator_id, status, is_deleted)` - Orchestrator-based filtering
- `lupo_projects_idx_project_key (project_key, federation_node_id)` - Key lookup
- `lupo_projects_idx_project_slug (project_slug, federation_node_id)` - Slug lookup
- `lupo_projects_idx_status (status, is_active, is_deleted)` - Status filtering
- `lupo_projects_idx_updated (updated_ymdhis)` - Update sorting

## Key Relationships

### Many-to-One Relationships
- **Federation Node**: `lupo_projects.federation_node_id` → `lupo_federation_nodes.node_id`
- **Default Channel**: `lupo_projects.default_channel_id` → `lupo_channels.channel_id`
- **Orchestrator**: `lupo_projects.orchestrator_id` → `lupo_actors.actor_id`
- **Creator**: `lupo_projects.created_by_actor_id` → `lupo_actors.actor_id`
- **Updater**: `lupo_projects.updated_by_actor_id` → `lupo_actors.actor_id`

### One-to-Many Relationships
- **Tasks**: `lupo_tasks.project_id` → `lupo_projects.project_id`
- **Project Channels**: Various channel tables reference projects
- **Project Artifacts**: Content and artifacts scoped to projects

## Usage Patterns

### Project Creation
```php
// Create a new project
$project = [
    'project_id' => generateId(),
    'project_key' => 'lupopedia_core',
    'project_slug' => 'lupopedia-core',
    'project_name' => 'Lupopedia Core Development',
    'federation_node_id' => 1,
    'default_channel_id' => 42,
    'orchestrator_id' => 1,
    'project_type' => 'development',
    'description' => 'Core platform development project',
    'github_repository' => 'https://github.com/lupopedia/core',
    'status' => 'active',
    'created_ymdhis' => 20260317220000,
    'updated_ymdhis' => 20260317220000,
    'created_by_actor_id' => 1,
    'updated_by_actor_id' => 1
];
```

### Project Retrieval
```php
// Get project by ID
$project = ProjectService::getProject($projectId);

// Get project by key
$project = ProjectService::getProjectByKey('lupopedia_core', $nodeId);

// Get active projects
$activeProjects = ProjectService::getActiveProjects($nodeId);
```

### Project Management
```php
// Update project status
ProjectService::updateProjectStatus($projectId, 'completed');

// Archive project
ProjectService::archiveProject($projectId);

// Freeze project (temporarily disable changes)
ProjectService::freezeProject($projectId);
```

## Project Types

### Standard Projects
- **Type**: 'standard'
- **Use Cases**: General purpose projects
- **Features**: Basic project management capabilities
- **Example**: Documentation projects, small development tasks

### Development Projects
- **Type**: 'development'
- **Use Cases**: Software development projects
- **Features**: Code integration, deployment tracking
- **Example**: Lupopedia core development, feature development

### Research Projects
- **Type**: 'research'
- **Use Cases**: Research and investigation projects
- **Features**: Data collection, analysis tools
- **Example**: System research, user studies

### Maintenance Projects
- **Type**: 'maintenance'
- **Use Cases**: Ongoing maintenance work
- **Features**: Issue tracking, regular tasks
- **Example**: System maintenance, bug fixes

## Project Status Flow

### Status States
```
planning → active → completed
    ↓         ↓
suspended → archived
    ↓
  frozen
```

### Status Descriptions
- **planning**: Project in planning phase
- **active**: Currently active and ongoing
- **completed**: Successfully completed
- **suspended**: Temporarily suspended
- **archived**: Completed and archived
- **frozen**: Temporarily frozen (no changes allowed)

## Metadata Structure

### Standard Metadata Fields
```json
{
    "project_info": {
        "start_date": "2026-03-17",
        "target_date": "2026-06-30",
        "budget": 0,
        "priority": "high"
    },
    "team": {
        "lead_actor_id": 1,
        "team_members": [1, 102, 105],
        "stakeholders": [1, 24]
    },
    "configuration": {
        "task_types": ["development", "documentation", "testing"],
        "default_task_status": "pending",
        "auto_archive": true
    },
    "integration": {
        "github_enabled": true,
        "ci_cd_enabled": false,
        "notification_channels": [42, 43]
    }
}
```

### Custom Metadata
- Projects can have custom metadata fields
- Flexible JSON structure allows project-specific configuration
- Metadata can be used for project-specific workflows
- Integration settings for external tools

## Federation Support

### Multi-Node Projects
- Projects can exist across multiple federation nodes
- `federation_node_id` determines ownership
- Project keys and slugs are unique per node
- Cross-node project synchronization

### Node Management
```php
// Get projects on specific node
$projects = ProjectService::getProjectsByNode($nodeId);

// Sync project to another node
ProjectService::syncProjectToNode($projectId, $targetNodeId);

// Check project conflicts across nodes
$conflicts = ProjectService::checkProjectConflicts($projectKey);
```

## Performance Considerations

### High-Volume Operations
- Index on federation_node_id for node-based queries
- Use project_key for fast project lookups
- Cache frequently accessed project metadata
- Batch project operations for efficiency

### Optimization Strategies
```php
// Batch project creation
$projects = [
    ['project_key' => 'project1', 'project_name' => 'Project 1'],
    ['project_key' => 'project2', 'project_name' => 'Project 2']
];
ProjectService::batchCreateProjects($projects);

// Cache project metadata
$cacheKey = "project_metadata:{$projectId}";
$metadata = CacheService::get($cacheKey);
if (!$metadata) {
    $metadata = ProjectService::getProjectMetadata($projectId);
    CacheService::set($cacheKey, $metadata, 300);
}
```

## Common Queries

### Active Projects by Node
```sql
SELECT 
    project_id,
    project_key,
    project_name,
    project_type,
    status,
    created_ymdhis
FROM lupo_projects 
WHERE federation_node_id = 1 
  AND is_active = 1 
  AND is_deleted = 0
ORDER BY project_name;
```

### Projects by Type
```sql
SELECT 
    project_type,
    COUNT(*) as project_count,
    COUNT(CASE WHEN status = 'active' THEN 1 END) as active_count
FROM lupo_projects 
WHERE is_deleted = 0
GROUP BY project_type
ORDER BY project_count DESC;
```

### Project with Tasks
```sql
SELECT 
    p.project_id,
    p.project_name,
    COUNT(t.task_id) as task_count,
    COUNT(CASE WHEN t.status = 'completed' THEN 1 END) as completed_count
FROM lupo_projects p
LEFT JOIN lupo_tasks t ON p.project_id = t.project_id AND t.is_deleted = 0
WHERE p.is_deleted = 0
GROUP BY p.project_id, p.project_name
ORDER BY task_count DESC;
```

### Project Metadata Query
```sql
SELECT 
    project_id,
    project_name,
    metadata_json->>'$.project_info.priority' as priority,
    metadata_json->>'$.team.lead_actor_id' as lead_actor
FROM lupo_projects 
WHERE is_deleted = 0
  AND metadata_json IS NOT NULL;
```

## Integration Points

### Task System
- Projects contain and organize tasks
- Task status affects project progress
- Project metadata influences task behavior

### Channel System
- Projects can have default channels
- Project-based channel organization
- Channel permissions based on project membership

### Actor System
- Projects have orchestrators and team members
- Actor permissions based on project roles
- Project audit trail tracks actor actions

## Security Considerations

### Access Control
- Validate actor permissions before project operations
- Check orchestrator permissions for project management
- Implement project membership controls
- Audit project access and modifications

### Data Integrity
- Validate project key and slug uniqueness
- Ensure federation_node_id exists
- Maintain project status consistency
- Prevent circular references in metadata

### Privacy Protection
- Protect sensitive project information
- Implement project access logging
- Respect project privacy settings
- Provide project data deletion capabilities

## Troubleshooting

### Common Issues
1. **Duplicate Keys**: Check unique constraints on project_key and project_slug
2. **Invalid Federation Node**: Verify federation_node_id exists
3. **Status Conflicts**: Ensure status consistency with is_* flags
4. **Metadata Issues**: Validate JSON structure

### Debug Queries
```sql
-- Check for duplicate project keys
SELECT project_key, federation_node_id, COUNT(*) as count
FROM lupo_projects 
WHERE is_deleted = 0
GROUP BY project_key, federation_node_id
HAVING COUNT(*) > 1;

-- Find orphaned projects
SELECT p.* 
FROM lupo_projects p
LEFT JOIN lupo_federation_nodes f ON p.federation_node_id = f.node_id
WHERE p.federation_node_id NOT IN (SELECT node_id FROM lupo_federation_nodes) 
  AND p.is_deleted = 0;

-- Check status consistency
SELECT project_id, status, is_active, is_deleted, is_archived, is_frozen
FROM lupo_projects 
WHERE (status = 'active' AND is_active = 0)
   OR (status = 'completed' AND is_active = 1)
   OR (is_deleted = 1 AND status != 'deleted');
```

## Migration Notes

### Version History
- **v4.0.70**: Initial project management system
- **v4.0.75**: Added federation support and metadata_json
- **v4.0.78**: Enhanced project types and status management
- **v4.0.80**: Current schema with comprehensive project management

### Breaking Changes
- Added federation_node_id for multi-node support
- Enhanced metadata_json with structured fields
- Improved project status and lifecycle management

## Best Practices

### Project Design
- Use descriptive project_key and project_name values
- Implement proper project type classification
- Maintain consistent project status management
- Use metadata for project-specific configuration

### Performance Optimization
- Cache frequently accessed project data
- Batch project operations when possible
- Use appropriate indexes for query patterns
- Monitor project creation and access patterns

### Federation Management
- Plan project distribution across nodes
- Implement proper project synchronization
- Monitor cross-node project conflicts
- Use federation_node_id strategically

---

**Table Statistics**:
- **Records**: Variable based on project volume
- **Size**: Medium - grows with project creation
- **Growth Rate**: Medium - new projects added as needed
- **Criticality**: MEDIUM - Work organization and coordination

**Dependencies**:
- **Required By**: Task management and project coordination
- **References**: `lupo_federation_nodes`, `lupo_channels`, `lupo_actors`
- **Integrations**: Task System, Channel System, Actor System

**Maintenance**:
- **Backup Priority**: MEDIUM
- **Archive Policy**: Soft delete with `is_deleted`
- **Cleanup**: Review inactive projects quarterly
- **Monitoring**: Track project creation and lifecycle patterns
