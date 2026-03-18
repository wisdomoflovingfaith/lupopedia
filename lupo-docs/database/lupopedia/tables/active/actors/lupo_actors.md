---
lupopedia.headers:
  lupopedia.version: "4.0.80"
  lupopedia.schema: "table"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/actors/lupo_actors.md"
  channel_id: 42
  actor_id: 102
  actor_name: "hermes"
  faucet_name: "cascade"
  artifact_type: "table_documentation"
  artifact_kind: "database_schema"
  purpose: "Complete documentation for lupo_actors table - master actor registry"
  tags: ["table_documentation", "actors", "master_table", "4.0.80"]
  created_ymdhis: 20260317164000
---

# lupo_actors - Master Actor Registry

**Table Type**: Master Registry  
**Domain**: Actor System  
**Criticality**: ESSENTIAL - System cannot operate without this table  
**Primary Key**: `actor_name` (canonical identifier)  
**Unique Keys**: `actor_id`, `slug`

## Overview

The `lupo_actors` table is the central registry for all entities in the Lupopedia system. It stores comprehensive information about actors, including users, agents, and system entities. This table is the foundation for all actor-based operations throughout the platform.

### Key Characteristics
- **Canonical Source**: Single source of truth for all actor information
- **Flexible Schema**: Supports both human users and AI agents
- **Federation Ready**: Designed for multi-node federation
- **Metadata Rich**: Extensive metadata and configuration storage

## Table Structure

### Core Identity Fields

| Column | Type | Description | Notes |
|--------|------|-------------|--------|
| `actor_name` | varchar(64) | **PRIMARY KEY** - Unique actor name | Primary identifier |
| `actor_id` | bigint | Unique actor ID | Secondary unique identifier |
| `actor_type` | varchar(64) | Type of actor | 'human', 'agent', 'system', etc. |
| `slug` | varchar(255) | URL-friendly slug | Human-readable identifier |
| `name` | varchar(255) | Display name | Human-friendly name |

### Timestamp Fields

| Column | Type | Description | Notes |
|--------|------|-------------|--------|
| `created_ymdhis` | bigint | Creation timestamp | YYYYMMDDHHIISS format |
| `updated_ymdhis` | bigint | Last update timestamp | YYYYMMDDHHIISS format |
| `deleted_ymdhis` | bigint | Deletion timestamp | NULL if not deleted |

### Status Fields

| Column | Type | Description | Default |
|--------|------|-------------|---------|
| `is_active` | tinyint | Actor is active | 1 |
| `is_deleted` | tinyint | Actor is deleted | 0 |
| `is_kernel` | tinyint | Kernel-level actor | 0 |
| `can_login` | tinyint | Can authenticate | 0 |
| `is_agent` | tinyint | Is an AI agent | 0 |

### Relationship Fields

| Column | Type | Description | Notes |
|--------|------|-------------|--------|
| `actor_source_id` | bigint | Source system ID | For federation/sync |
| `actor_source_type` | varchar(64) | Source system type | 'system_tool', 'human', etc. |
| `paired_actor_id` | bigint | Paired actor ID | For faucet/actor pairs |
| `department_id` | bigint | Department ID | Optional department assignment |
| `adversarial_oversight_actor_id` | bigint | Oversight actor for adversarial roles | Optional |
| `primary_federation_node_id` | bigint | Primary federation node | Default 1 |

### Configuration Fields

| Column | Type | Description | Default |
|--------|------|-------------|--------|
| `adversarial_role` | varchar(64) | Adversarial role | 'none' |
| `avatar_hash` | varchar(64) | Avatar image hash | Optional |
| `metadata` | text | Legacy metadata | Deprecated, use metadata_json |
| `metadata_json` | json | JSON metadata | Flexible key-value storage |
| `identity_provider_config` | json | Identity provider config | OAuth/OpenID config |

### Path and Namespace Fields

| Column | Type | Description | Default |
|--------|------|-------------|--------|
| `actor_root_path` | varchar(512) | Actor root path | 'actors/{actor_id}' |
| `workspace_path` | varchar(255) | Workspace path | Optional |
| `php_namespace` | varchar(120) | PHP namespace | Optional |

### Sync Fields

| Column | Type | Description | Notes |
|--------|------|-------------|--------|
| `who_json_sync_status` | varchar(64) | WHO JSON sync status | 'pending', 'synced', 'error' |
| `last_sync_ymdhis` | bigint | Last sync timestamp | YYYYMMDDHHIISS format |

## Indexes

### Primary Index
- `PRIMARY KEY (actor_name)` - Unique actor name identifier

### Unique Indexes
- `lupo_actors_unique_actor_id (actor_id)` - Unique actor ID
- `lupo_actors_unique_slug (slug)` - Unique slug identifier

### Performance Indexes
- `lupo_actors_idx_actor_type (actor_type)` - Actor type filtering
- `lupo_actors_idx_created_ymdhis (created_ymdhis)` - Time-based sorting
- `lupo_actors_idx_is_active (is_active)` - Active status filtering
- `lupo_actors_idx_php_namespace (php_namespace)` - Namespace filtering
- `lupo_actors_idx_workspace_path (workspace_path)` - Workspace path filtering

## Key Relationships

### One-to-Many Relationships
- **Actor Channels**: `lupo_actor_channels.actor_id` → `lupo_actors.actor_id`
- **Actor Capabilities**: `lupo_actor_capabilities.actor_id` → `lupo_actors.actor_id`
- **Actor Actions**: `lupo_actor_actions.actor_id` → `lupo_actors.actor_id`
- **Actor Traits**: `lupo_actor_traits.actor_id` → `lupo_actors.actor_id`
- **Actor History**: `lupo_actor_history.actor_id` → `lupo_actors.actor_id`

### Many-to-One Relationships
- **Department**: `lupo_actors.department_id` → `lupo_departments.department_id`
- **Paired Actor**: `lupo_actors.paired_actor_id` → `lupo_actors.actor_id`

## Usage Patterns

### Actor Creation
```php
// Create a new actor
$actor = [
    'actor_name' => 'new_user',
    'actor_id' => 1001,  // Must be >= 1000 for humans
    'actor_type' => 'human',
    'slug' => 'new-user',
    'name' => 'New User',
    'created_ymdhis' => 20260317164000,
    'is_active' => 1,
    'can_login' => 1
];
```

### Actor Resolution
```php
// Resolve actor by name (canonical)
$actor = ActorService::getActorByName('wolfie');

// Resolve actor by ID
$actor = ActorService::getActorById(1);

// Resolve actor by slug
$actor = ActorService::getActorBySlug('wolfie');
```

### Actor Types
- **human**: Human users with actor_id >= 1000
- **agent**: AI agents and bots
- **system**: System processes and services
- **faucet**: IDE interfaces and human interfaces

## Important Doctrines

### Actor Primary Key Doctrine (v4.0.58)
- `actor_name` is the canonical primary key
- `actor_id` is a unique secondary key
- Always use `ActorService::getActorByName()` for resolution

### Human Actor ID Doctrine
- Human actors must have `actor_id >= 1000`
- Allocate from `lupo_registry_open`
- Reserved IDs: 1-999 for system actors

### Federation Doctrine
- `primary_federation_node_id` determines ownership
- `actor_source_id` and `actor_source_type` for external sync
- WHO JSON sync status for federation synchronization

## Security Considerations

### Access Control
- `is_active` flag controls actor availability
- `can_login` determines authentication ability
- `is_deleted` provides soft deletion

### Adversarial Actors
- `adversarial_role` identifies testing/red team actors
- `adversarial_oversight_actor_id` provides oversight
- Used for security testing and validation

### Metadata Security
- `metadata_json` stores sensitive configuration
- `identity_provider_config` contains auth secrets
- Access to these fields should be controlled

## Performance Considerations

### High-Volume Operations
- Index on `actor_name` for fast lookups
- Filter on `is_active` to exclude deleted actors
- Use `created_ymdhis` for time-based queries

### Caching Strategy
- Cache frequently accessed actors
- Invalidate cache on `updated_ymdhis` changes
- Consider memory caching for kernel actors

### Federation Performance
- Minimize cross-node actor lookups
- Cache remote actor information
- Use `primary_federation_node_id` for routing

## Common Queries

### Active Actors by Type
```sql
SELECT actor_id, actor_name, name 
FROM lupo_actors 
WHERE is_active = 1 
  AND is_deleted = 0 
  AND actor_type = 'agent'
ORDER BY created_ymdhis DESC;
```

### Actor with Capabilities
```sql
SELECT a.actor_name, a.name, c.domain_id, c.capability
FROM lupo_actors a
JOIN lupo_actor_capabilities c ON a.actor_id = c.actor_id
WHERE a.is_active = 1 
  AND c.is_deleted = 0;
```

### Login Capable Actors
```sql
SELECT actor_id, actor_name, name, actor_type
FROM lupo_actors 
WHERE can_login = 1 
  AND is_active = 1 
  AND is_deleted = 0;
```

## Migration Notes

### Version History
- **v4.0.58**: Actor Primary Key Doctrine established
- **v4.0.68**: Added metadata_json and identity_provider_config
- **v4.0.77**: Added federation fields
- **v4.0.80**: Current schema with comprehensive actor support

### Breaking Changes
- `metadata` field deprecated in favor of `metadata_json`
- `actor_id` no longer auto-increment (application-assigned)
- Human actor IDs must be >= 1000

## Troubleshooting

### Common Issues
1. **Duplicate actor_name**: Check for existing actors before creation
2. **Invalid actor_id**: Ensure human IDs >= 1000
3. **Missing slug**: Generate from actor_name if not provided
4. **Sync failures**: Check who_json_sync_status field

### Debug Queries
```sql
-- Check for duplicate names
SELECT actor_name, COUNT(*) as count
FROM lupo_actors 
GROUP BY actor_name 
HAVING COUNT(*) > 1;

-- Find inactive actors
SELECT actor_name, is_active, is_deleted
FROM lupo_actors 
WHERE is_active = 0 OR is_deleted = 1;

-- Check federation sync status
SELECT actor_name, who_json_sync_status, last_sync_ymdhis
FROM lupo_actors 
WHERE who_json_sync_status != 'synced';
```

## Integration Points

### Authentication System
- Links to `lupo_auth_providers` via `identity_provider_config`
- `can_login` flag determines authentication eligibility
- Used in session management and user login

### Channel System
- `lupo_actor_channels` references this table
- Channel membership and role assignments
- Used for access control and permissions

### Agent System
- `is_agent` flag identifies AI agents
- `php_namespace` for agent class loading
- `actor_root_path` for agent file organization

---

**Table Statistics**:
- **Records**: ~108 (current registry)
- **Size**: Variable based on metadata
- **Growth Rate**: Low (controlled actor creation)
- **Criticality**: ESSENTIAL - System foundation

**Dependencies**:
- **Required By**: All actor-related tables
- **References**: `lupo_departments`, `lupo_actors` (self)
- **Integrations**: Authentication, Channels, Agents

**Maintenance**:
- **Backup Priority**: CRITICAL
- **Archive Policy**: Soft delete with `is_deleted`
- **Cleanup**: Review inactive actors quarterly
- **Monitoring**: Track actor creation and deletion rates
