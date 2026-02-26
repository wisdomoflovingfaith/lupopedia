---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/doctrine/database/actors.md
file.last_modified_system_version: "4.0.47"
file.last_modified_utc: "20260226060000"
channel_id: 42   # ANUBIS adoption channel
tags: ["doctrine", "database", "actors", "identity", "unified"]
mood_rgb: "4B0082"
atoms:
  table_review: true
  documentation_update: true
X-Lupo-Actor-ID: 1002
X-Lupo-Actor-Identity: "Windsurf (Documentation Update)"
X-Lupo-File-Path: docs/doctrine/database/actors.md
---

# lupo_actors

**Purpose:** **Unified identity layer** for authenticated humans, AI agents, services, and system users only. Anonymous users do not have rows in lupo_actors; they exist in **lupo_sessions** only. Every authenticated or system entity that can send messages, hold roles, or be referenced in dialogs has one row in `lupo_actors`. Identity is separated from credentials (lupo_auth_users) and from permissions (3-level role system). No dedicated ID range for anonymous users.

**Schema:** See `docs/toons/lupo_actors.toon.json`. Primary key: `actor_id`. Columns include `actor_type` (e.g. agent, service, human, system), `slug`, `name`, `actor_source_id`, `actor_source_type`, `metadata`, lifecycle fields.

**IMPORTANT: actor_id Range Allocation**
- **AI Agents**: actor_id 0-9999 (reserved for system/AI agents)
- **Human Actors**: actor_id 10000+ (imported Crafty users: auth_user_id = 10000 + user_id)
- **System Actor**: actor_id 0 (reserved system identity)
- **Kernel Actors**: `is_kernel = 1` for core system processes

---

## Complete Column Reference

| Column Name | Type | Description | Default | Indexed | Notes |
|-------------|------|-------------|---------|---------|-------|
| `actor_id` | bigint | Primary key: Unique actor identifier | - | YES (PK) | 0-9999 = AI agents, 10000+ = humans |
| `actor_type` | varchar(64) | Type of actor (human, agent, service, system) | - | YES | For filtering and routing |
| `slug` | varchar(255) | URL-friendly unique identifier | - | YES (UNIQUE) | Used in URLs and references |
| `name` | varchar(255) | Display name for the actor | - | - | Human-readable name |
| `created_ymdhis` | bigint | Creation timestamp (YYYYMMDDHHMMSS) | 0 | YES | UTC timestamp format |
| `updated_ymdhis` | bigint | Last update timestamp (YYYYMMDDHHMMSS) | - | - | UTC timestamp format |
| `is_active` | tinyint | Whether actor is currently active | 1 | YES | 1 = active, 0 = inactive |
| `is_deleted` | tinyint | Soft delete flag | 0 | - | 1 = deleted, 0 = not deleted |
| `deleted_ymdhis` | bigint | Deletion timestamp (YYYYMMDDHHMMSS) | NULL | - | Set when is_deleted = 1 |
| `actor_source_id` | bigint | Source system identifier | NULL | - | Links to external systems |
| `actor_source_type` | varchar(64) | Source system type | NULL | - | 'lupo_auth_users', 'system', etc. |
| `metadata` | text | Legacy metadata field | NULL | - | Deprecated, use metadata_json |
| `adversarial_role` | varchar(64) | Adversarial analysis role | 'none' | - | For security analysis |
| `adversarial_oversight_actor_id` | bigint | Oversight actor for adversarial | NULL | - | Links to lupo_actors |
| `avatar_hash` | varchar(64) | Hash of avatar image | NULL | - | For avatar caching |
| `primary_federation_node_id` | bigint | Primary federation node | 1 | - | Multi-site federation |
| `department_id` | bigint | Primary department assignment | NULL | - | Links to lupo_departments |
| `is_kernel` | tinyint | Kernel/system process flag | 0 | - | 1 = kernel actor |
| `can_login` | tinyint | Login capability flag | 0 | - | 1 = can authenticate |
| `metadata_json` | json | Structured metadata | NULL | - | Flexible extra data |
| `identity_provider_config` | json | Identity provider settings | NULL | - | OAuth/LDAP config |
| `paired_actor_id` | bigint | Paired actor relationship | 0 | - | For actor pairing |
| `is_agent` | tinyint | AI agent flag | 0 | - | 1 = AI agent |

---

## Indexes and Performance

| Index Name | Type | Columns | Unique | Purpose |
|------------|------|---------|--------|---------|
| `lupo_actors_idx_actor_type` | BTREE | `actor_type` | No | Filter by actor type |
| `lupo_actors_idx_created_ymdhis` | BTREE | `created_ymdhis` | No | Sort by creation time |
| `lupo_actors_idx_is_active` | BTREE | `is_active` | No | Filter active actors |
| `lupo_actors_unique_slug` | BTREE | `slug` | Yes | Unique slug constraint |

**Performance Considerations:**
- **Primary Key**: `actor_id` provides fast lookups for all actor references
- **Unique Slug**: Ensures URL-friendly identifiers are unique
- **Type Index**: Optimizes queries filtering by actor_type
- **Active Index**: Fast filtering for active/inactive status

---

## Use and Need

### Single Identity Table
- **Universal Reference**: Channels, threads, messages, roles, and presence refer to `actor_id`
- **No Separate Tables**: No dedicated "operator" or "visitor" tables; type and source distinguish them
- **Unified Architecture**: Single source of truth for all actor identities

### Human Actors
- **Imported Users**: For imported Crafty users, `actor_id = auth_user_id` and `actor_source_type = 'lupo_auth_users'`
- **Operator Import**: Only operators (isoperator = 'Y') get lupo_actors rows at import
- **Visitor Creation**: Visitors may be created on demand or via other flows
- **ID Range**: Human actors use actor_id ≥ 10000

### AI Agents and Services
- **System Agents**: `actor_type = 'agent'` or 'service', `actor_source_type = 'system'`
- **Reserved IDs**: AI agents use reserved IDs from REGISTRY (0-9999 range)
- **Kernel Actors**: `is_kernel = 1` for core system processes
- **Service Actors**: Background services and automated processes

### Anonymous Users
- **No Actor Rows**: Anonymous visitors do not get rows in lupo_actors
- **Session Only**: They exist only in lupo_sessions
- **No Anonymous Range**: No dedicated actor_id range for anonymous users

---

## Migration from Crafty Syntax

### Legacy Source Tables
- **Primary**: `livehelp_users` (operators only get lupo_actors rows)
- **Secondary**: `livehelp_identity_monthly` (not imported into lupo_actors)
- **Credentials**: `livehelp_users` → `lupo_auth_users` for authentication

### Migration Strategy
- **Operator Import**: Creates lupo_actors only for rows with `isoperator = 'Y'`
- **ID Mapping**: `actor_id = user_id`, `actor_source_id = auth_user_id`, `actor_source_type = 'lupo_auth_users'`
- **Name/Slug**: Derived from username/displayname from legacy system
- **Human Range**: Imported users get actor_id ≥ 10000 (10000 + user_id)

### Anonymous Handling
- **No Import**: Anonymous visitors are not imported into lupo_actors
- **Session Only**: Anonymous visitors exist in lupo_sessions only
- **Dropped Tables**: `livehelp_identity_monthly` and `livehelp_identity_daily` are DROPPED

### Permissions Migration
- **No lupo_operators**: Legacy operator table is not used
- **Role System**: Staff permissions use `lupo_actor_channel_roles` and `lupo_department_roles`
- **Role Types**: captain, administrator, monitor for channel-level permissions
- **Department Roles**: Department-specific permissions and assignments

---

## Data Relationships

### Primary Relationships
- **lupo_auth_users**: Human actor credentials (`actor_source_id = auth_user_id`)
- **lupo_departments**: Primary department assignment (`department_id`)
- **lupo_sessions**: Anonymous user sessions (no actor_id)
- **lupo_dialog_messages**: Message author (`from_actor_id`, `to_actor_id`)
- **lupo_actor_channel_roles**: Channel permissions and roles

### Federation Support
- **Multi-site**: `primary_federation_node_id` for federated installations
- **Cross-site**: Actors can belong to multiple federation nodes
- **Node 1**: Default federation node (lupopedia.com)

### Actor Pairing
- **Paired Actors**: `paired_actor_id` for actor relationships
- **Mirror Actors**: For multi-identity scenarios
- **System Links**: Kernel actors can be paired with service actors

---

## Usage Patterns

### Creating Human Actors
```php
// Import from Crafty user
$actor_id = 10000 + $user_id; // Ensure human range
$sql = "INSERT INTO lupo_actors (
    actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis,
    actor_source_id, actor_source_type, can_login
) VALUES (?, 'human', ?, ?, ?, ?, ?, 'lupo_auth_users', 1)";
```

### Creating AI Agents
```php
// System agent creation
$sql = "INSERT INTO lupo_actors (
    actor_id, actor_type, slug, name, created_ymdhis, updated_ymdhis,
    actor_source_type, is_agent, is_kernel
) VALUES (?, 'agent', ?, ?, ?, ?, 'system', 1, ?)";
```

### Querying Active Actors
```php
// Get active human actors
$sql = "SELECT * FROM lupo_actors 
        WHERE actor_type = 'human' AND is_active = 1 AND is_deleted = 0
        ORDER BY name ASC";
```

### Federation Queries
```php
// Get actors from specific federation node
$sql = "SELECT * FROM lupo_actors 
        WHERE primary_federation_node_id = ? AND is_active = 1";
```

---

## Security Considerations

### Adversarial Analysis
- **Role Tracking**: `adversarial_role` for security analysis
- **Oversight**: `adversarial_oversight_actor_id` for monitoring
- **Security Flags**: Special handling for suspicious actors

### Access Control
- **Login Capability**: `can_login` flag for authentication eligibility
- **Kernel Protection**: `is_kernel` actors have special system privileges
- **Agent Restrictions**: AI agents have limited permissions by default

### Privacy and Data
- **Metadata Storage**: Use `metadata_json` for structured data
- **Avatar Hashing**: `avatar_hash` for privacy-preserving avatar caching
- **Soft Deletes**: `is_deleted` flag for data retention compliance

---

## Performance Optimization

### Query Optimization
- **Primary Key**: Fast lookups by actor_id
- **Type Filtering**: Efficient filtering by actor_type
- **Active Filtering**: Quick active/inactive status checks
- **Slug Lookups**: Unique slug constraint for URL resolution

### Index Usage
- **Frequent Queries**: actor_id, actor_type, is_active combinations
- **Sorting**: created_ymdhis for chronological ordering
- **Uniqueness**: slug constraint for URL uniqueness

### Caching Strategy
- **Actor Cache**: Cache frequently accessed actor data
- **Permission Cache**: Cache role assignments
- **Federation Cache**: Cache cross-node actor information

---

## Legacy Compatibility

### Crafty Syntax Mapping
- **livehelp_users**: Source for operator actors
- **user_id**: Mapped to actor_id (with 10000+ offset)
- **username**: Used for slug generation
- **displayname**: Used for name field

### Migration Path
- **Step 1**: Import operators as human actors
- **Step 2**: Create system actors for core functions
- **Step 3**: Set up AI agents with reserved IDs
- **Step 4**: Configure federation and department assignments

### Backward Compatibility
- **ID Preservation**: Legacy user IDs preserved with offset
- **Name Mapping**: Display names maintained from legacy system
- **Permission Migration**: Operator roles converted to new role system

---

**Status:** ✅ DOCUMENTATION COMPLETE - Comprehensive table reference with TOON-based schema analysis, migration patterns, and usage guidelines.

**Last Updated:** 2026-02-26 by Windsurf (1002)
