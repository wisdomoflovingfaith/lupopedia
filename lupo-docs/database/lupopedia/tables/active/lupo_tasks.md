---
lupopedia.headers:
  lupopedia.schema: table
  file_path_from_root: lupo-docs/database/lupopedia/tables/active/lupo_tasks.md
  channel_id: 42
  actor_id: 102
  actor_name: hermes
  faucet_name: cascade
  artifact_type: table_documentation
  artifact_kind: database_schema
  purpose: Complete documentation for lupo_tasks table - task tracking and assignment
  tags":
  - table_documentation
  - tasks
  - tracking
  - assignment
  - 4.0.80
  created_ymdhis: 20260317221000
  when_updated: '20260324174654'
lupopedia:
  footer:
    last_verified: '20260324174654'
    last_verified_by: cursor
    last_verified_by_actor_id: 102
    orchestrator: cursor:root
---

# lupo_tasks - Task Tracking and Assignment

**Table Type**: Task Registry  
**Domain**: Task System  
**Criticality**: MEDIUM - Task tracking and work coordination  
**Primary Key**: `task_id` (application-assigned)

## Overview

The `lupo_tasks` table manages task definitions, assignments, and lifecycle status in Lupopedia. It provides comprehensive task tracking capabilities including ownership, duration tracking, approval workflows, and metadata management.

### Key Characteristics
- **Task Registry**: Central storage for all task definitions
- **Channel-Based**: Tasks are scoped to specific channels
- **Ownership Tracking**: Clear task assignment and responsibility
- **Workflow Support**: Approval chains and consensus mechanisms

## Table Structure

### Core Identity Fields

| Column | Type | Description | Notes |
|--------|------|-------------|--------|
| `task_id` | bigint | **PRIMARY KEY** - Unique task ID | Application-assigned, not auto-increment |
| `task_key` | varchar(64) | Task identifier key | Unique within channel |
| `channel_id` | bigint | Channel context | References `lupo_channels.channel_id` |
| `owner_actor_id` | bigint | Task owner | References `lupo_actors.actor_id` |
| `title` | varchar(255) | Task title | Human-readable task name |
| `description` | text | Task description | Detailed task information |

### Configuration Fields

| Column | Type | Description | Notes |
|--------|------|-------------|--------|
| `prompt_path` | varchar(512) | Prompt file path | Optional prompt template reference |
| `acting_as_actor_id` | bigint | Acting actor | References `lupo_actors.actor_id` |
| `parent_agent_id` | bigint | Parent agent | References `lupo_actors.actor_id` |
| `task_type` | varchar(64) | Type of task | 'development', 'documentation', etc. |
| `task_status` | varchar(64) | Task status | 'pending', 'active', 'completed', etc. |
| `task_priority` | varchar(64) | Task priority | 'low', 'medium', 'high', 'critical' |

### Duration Fields

| Column | Type | Description | Notes |
|--------|------|-------------|--------|
| `estimated_duration_seconds` | int | Estimated duration | In seconds |
| `actual_duration_seconds` | int | Actual duration | In seconds |

### Timestamp Fields

| Column | Type | Description | Default |
|--------|------|-------------|--------|
| `created_ymdhis` | bigint | Creation timestamp | 0 |
| `updated_ymdhis` | bigint | Last update timestamp | Current time |
| `started_ymdhis` | bigint | Task start timestamp | NULL |
| `completed_ymdhis` | bigint | Task completion timestamp | NULL |
| `deleted_ymdhis` | bigint | Deletion timestamp | NULL |

### Status Fields

| Column | Type | Description | Default |
|--------|------|-------------|--------|
| `is_deleted` | tinyint | Task is deleted | 0 |

### Advanced Fields

| Column | Type | Description | Notes |
|--------|------|-------------|--------|
| `metadata_json` | text | Task metadata | JSON structure |
| `consensus_hash` | varchar(255) | Consensus hash | For distributed consensus |
| `approval_chain_json` | json | Approval chain | Workflow approval tracking |
| `task_embeddings` | text | Task embeddings | Vector embeddings for similarity |

## Indexes

### Primary Index
- `PRIMARY KEY (task_id)` - Unique task identifier

### Unique Index
- `lupo_tasks_uniq_task_key_per_channel (task_key, channel_id)` - Unique key per channel

### Performance Indexes
- `lupo_tasks_idx_acting_as_actor_id (acting_as_actor_id)` - Acting actor filtering
- `lupo_tasks_idx_channel_id (channel_id)` - Channel-based filtering
- `lupo_tasks_idx_created_ymdhis (created_ymdhis)` - Time-based sorting
- `lupo_tasks_idx_is_deleted (is_deleted)` - Deleted status filtering
- `lupo_tasks_idx_owner_actor_id (owner_actor_id)` - Owner-based filtering
- `lupo_tasks_idx_parent_agent_id (parent_agent_id)` - Parent agent filtering
- `lupo_tasks_idx_task_priority (task_priority)` - Priority-based filtering
- `lupo_tasks_idx_task_status (task_status)` - Status-based filtering
- `lupo_tasks_idx_task_type (task_type)` - Type-based filtering

## Key Relationships

### Many-to-One Relationships
- **Channel**: `lupo_tasks.channel_id` → `lupo_channels.channel_id`
- **Owner**: `lupo_tasks.owner_actor_id` → `lupo_actors.actor_id`
- **Acting As**: `lupo_tasks.acting_as_actor_id` → `lupo_actors.actor_id`
- **Parent Agent**: `lupo_tasks.parent_agent_id` → `lupo_actors.actor_id`

### Related Tables
- **Project Tasks**: If project-based, references `lupo_projects.project_id`
- **Task Comments**: Task discussion and collaboration
- **Task History**: Task change tracking and audit trail

## Usage Patterns

### Task Creation
```php
// Create a new task
$task = [
    'task_id' => generateId(),
    'task_key' => 'doc_auth_tables',
    'channel_id' => 42,
    'owner_actor_id' => 102,
    'title' => 'Document Authentication Tables',
    'description' => 'Create comprehensive documentation for auth system tables',
    'task_type' => 'documentation',
    'task_status' => 'pending',
    'task_priority' => 'high',
    'estimated_duration_seconds' => 7200, // 2 hours
    'created_ymdhis' => 20260317221000,
    'updated_ymdhis' => 20260317221000
];
```

### Task Assignment
```php
// Assign task to actor
TaskService::assignTask($taskId, $ownerActorId);

// Update task status
TaskService::updateTaskStatus($taskId, 'active');

// Start task
TaskService::startTask($taskId);
```

### Task Completion
```php
// Complete task
TaskService::completeTask($taskId, $actualDuration);

// Update duration tracking
TaskService::updateDuration($taskId, $estimatedDuration, $actualDuration);

// Archive completed task
TaskService::archiveTask($taskId);
```

## Task Types

### Development Tasks
- **Type**: 'development'
- **Use Cases**: Software development, feature implementation
- **Characteristics**: Code-related, technical implementation
- **Example**: Implement user authentication system

### Documentation Tasks
- **Type**: 'documentation'
- **Use Cases**: Documentation creation, updates, reviews
- **Characteristics**: Content creation, knowledge transfer
- **Example**: Document API endpoints

### Research Tasks
- **Type**: 'research'
- **Use Cases**: Investigation, analysis, studies
- **Characteristics**: Data gathering, analysis work
- **Example**: Research performance optimization strategies

### Maintenance Tasks
- **Type**: 'maintenance'
- **Use Cases**: System maintenance, bug fixes
- **Characteristics**: Ongoing work, issue resolution
- **Example**: Fix authentication bug

### Testing Tasks
- **Type**: 'testing'
- **Use Cases**: Quality assurance, test creation
- **Characteristics**: Validation, verification work
- **Example**: Create unit tests for auth system

## Task Status Flow

### Status States
```
pending → active → completed
    ↓         ↓
suspended → cancelled
```

### Status Descriptions
- **pending**: Task created but not started
- **active**: Currently being worked on
- **completed**: Successfully completed
- **suspended**: Temporarily suspended
- **cancelled**: Cancelled before completion

## Task Priority Levels

### Priority Levels
- **critical**: Urgent, blocking issues
- **high**: Important, should be done soon
- **medium**: Normal priority
- **low**: Low priority, can be deferred

### Priority Assignment
```php
// Set task priority
TaskService::setTaskPriority($taskId, 'critical');

// Get high priority tasks
$highPriorityTasks = TaskService::getTasksByPriority('high');

// Prioritize tasks by multiple factors
$prioritized = TaskService::prioritizeTasks($channelId);
```

## Duration Tracking

### Duration Management
```php
// Set estimated duration
TaskService::setEstimatedDuration($taskId, 3600); // 1 hour

// Start task (records start time)
TaskService::startTask($taskId);

// Complete task with actual duration
TaskService::completeTask($taskId, 3300); // 55 minutes

// Get duration analytics
$analytics = TaskService::getDurationAnalytics($channelId);
```

### Duration Analysis
- Compare estimated vs actual durations
- Track completion time patterns
- Identify estimation accuracy
- Optimize future estimates

## Approval Workflow

### Approval Chain
```json
{
    "approval_chain": [
        {
            "actor_id": 102,
            "role": "reviewer",
            "status": "pending",
            "timestamp": "20260317221000"
        },
        {
            "actor_id": 1,
            "role": "approver",
            "status": "pending",
            "timestamp": "20260317221000"
        }
    ],
    "current_step": 0,
    "required_approvals": 2
}
```

### Approval Process
```php
// Create task with approval chain
$approvalChain = [
    ['actor_id' => 102, 'role' => 'reviewer'],
    ['actor_id' => 1, 'role' => 'approver']
];
TaskService::createTaskWithApproval($taskData, $approvalChain);

// Process approval step
TaskService::processApproval($taskId, $actorId, 'approved');

// Check approval status
$status = TaskService::getApprovalStatus($taskId);
```

## Metadata Structure

### Standard Metadata
```json
{
    "task_info": {
        "complexity": "medium",
        "dependencies": ["task1", "task2"],
        "tags": ["documentation", "auth", "security"],
        "estimated_effort": 8
    },
    "requirements": {
        "skills": ["technical_writing", "database_knowledge"],
        "tools": ["markdown", "sql_client"],
        "access_level": "developer"
    },
    "tracking": {
        "progress_percentage": 75,
        "milestones": ["outline", "draft", "review", "final"],
        "current_milestone": "review"
    }
}
```

### Custom Metadata
- Tasks can have custom metadata fields
- Flexible structure for task-specific information
- Integration with external systems
- Workflow configuration

## Consensus and Embeddings

### Consensus Hash
- Used for distributed task consensus
- Ensures task consistency across nodes
- Supports collaborative task management
- Conflict resolution mechanism

### Task Embeddings
- Vector embeddings for task similarity
- Enables task recommendation
- Supports intelligent task assignment
- Facilitates task clustering and analysis

## Performance Considerations

### High-Volume Operations
- Index on channel_id for channel-based queries
- Use task_status for active task filtering
- Cache frequently accessed task metadata
- Batch task operations for efficiency

### Optimization Strategies
```php
// Batch task creation
$tasks = [
    ['task_key' => 'task1', 'title' => 'Task 1'],
    ['task_key' => 'task2', 'title' => 'Task 2']
];
TaskService::batchCreateTasks($tasks);

// Cache task metadata
$cacheKey = "task_metadata:{$taskId}";
$metadata = CacheService::get($cacheKey);
if (!$metadata) {
    $metadata = TaskService::getTaskMetadata($taskId);
    CacheService::set($cacheKey, $metadata, 300);
}
```

## Common Queries

### Active Tasks by Channel
```sql
SELECT 
    task_id,
    task_key,
    title,
    task_type,
    task_priority,
    owner_actor_id,
    created_ymdhis
FROM lupo_tasks 
WHERE channel_id = 42 
  AND task_status = 'active'
  AND is_deleted = 0
ORDER BY task_priority DESC, created_ymdhis ASC;
```

### Tasks by Owner
```sql
SELECT 
    task_id,
    title,
    task_type,
    task_status,
    task_priority,
    estimated_duration_seconds,
    actual_duration_seconds
FROM lupo_tasks 
WHERE owner_actor_id = 102 
  AND is_deleted = 0
ORDER BY task_status, task_priority DESC;
```

### Task Duration Analytics
```sql
SELECT 
    task_type,
    AVG(estimated_duration_seconds) as avg_estimated,
    AVG(actual_duration_seconds) as avg_actual,
    COUNT(*) as task_count,
    AVG(actual_duration_seconds / estimated_duration_seconds) as accuracy_ratio
FROM lupo_tasks 
WHERE task_status = 'completed'
  AND estimated_duration_seconds > 0
  AND actual_duration_seconds > 0
  AND is_deleted = 0
GROUP BY task_type
ORDER BY task_count DESC;
```

### High Priority Tasks
```sql
SELECT 
    t.task_id,
    t.title,
    t.task_type,
    a.actor_name as owner_name,
    t.created_ymdhis
FROM lupo_tasks t
JOIN lupo_actors a ON t.owner_actor_id = a.actor_id
WHERE t.task_priority IN ('critical', 'high')
  AND t.task_status IN ('pending', 'active')
  AND t.is_deleted = 0
ORDER BY t.task_priority DESC, t.created_ymdhis ASC;
```

## Integration Points

### Channel System
- Tasks are scoped to channels
- Channel permissions affect task access
- Channel-based task organization

### Actor System
- Task ownership and assignment
- Actor permissions for task management
- Task audit trail with actor tracking

### Project System
- Tasks can be associated with projects
- Project progress based on task completion
- Project-based task organization

## Security Considerations

### Access Control
- Validate channel membership before task access
- Check task ownership for modification permissions
- Implement task-based access controls
- Audit task access and modifications

### Data Integrity
- Validate task_key uniqueness within channel
- Ensure owner_actor_id exists
- Maintain task status consistency
- Prevent circular dependencies in metadata

### Privacy Protection
- Protect sensitive task information
- Implement task access logging
- Respect task privacy settings
- Provide task data deletion capabilities

## Troubleshooting

### Common Issues
1. **Duplicate Task Keys**: Check unique constraint on task_key per channel
2. **Invalid Owner**: Verify owner_actor_id exists
3. **Status Conflicts**: Ensure status consistency with timestamps
4. **Duration Issues**: Validate duration values and relationships

### Debug Queries
```sql
-- Check for duplicate task keys
SELECT task_key, channel_id, COUNT(*) as count
FROM lupo_tasks 
WHERE is_deleted = 0
GROUP BY task_key, channel_id
HAVING COUNT(*) > 1;

-- Find orphaned tasks
SELECT t.* 
FROM lupo_tasks t
LEFT JOIN lupo_channels c ON t.channel_id = c.channel_id
WHERE t.channel_id NOT IN (SELECT channel_id FROM lupo_channels) 
  AND t.is_deleted = 0;

-- Check duration consistency
SELECT task_id, estimated_duration_seconds, actual_duration_seconds, 
       started_ymdhis, completed_ymdhis
FROM lupo_tasks 
WHERE (started_ymdhis IS NOT NULL AND completed_ymdhis IS NOT NULL 
       AND actual_duration_seconds IS NULL)
   OR (actual_duration_seconds > 0 AND completed_ymdhis IS NULL);
```

## Migration Notes

### Version History
- **v4.0.55**: Consolidated task lookup tables into main table
- **v4.0.70**: Added approval workflow and consensus support
- **v4.0.75**: Enhanced metadata and duration tracking
- **v4.0.80**: Current schema with comprehensive task management

### Breaking Changes
- Consolidated separate lookup tables into VARCHAR columns
- Added approval_chain_json for workflow support
- Enhanced duration tracking with actual vs estimated

## Best Practices

### Task Design
- Use descriptive task_key and title values
- Implement proper task type classification
- Maintain consistent task status management
- Use realistic duration estimates

### Performance Optimization
- Cache frequently accessed task data
- Batch task operations when possible
- Use appropriate indexes for query patterns
- Monitor task creation and completion patterns

### Workflow Management
- Design clear approval chains
- Use task priorities effectively
- Track duration accuracy for better estimates
- Implement proper task dependencies

---

**Table Statistics**:
- **Records**: Variable based on task volume
- **Size**: Medium - grows with task creation
- **Growth Rate**: High - new tasks created regularly
- **Criticality**: MEDIUM - Work coordination and tracking

**Dependencies**:
- **Required By**: Task management and work coordination
- **References**: `lupo_channels`, `lupo_actors`
- **Integrations**: Channel System, Actor System, Project System

**Maintenance**:
- **Backup Priority**: MEDIUM
- **Archive Policy**: Soft delete with `is_deleted`
- **Cleanup**: Review completed tasks monthly
- **Monitoring**: Track task creation and completion patterns
