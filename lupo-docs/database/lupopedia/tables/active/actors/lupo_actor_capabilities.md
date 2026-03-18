---
lupopedia.headers:
  lupopedia.version: "4.0.80"
  lupopedia.schema: "table"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/actors/lupo_actor_capabilities.md"
  channel_id: 42
  actor_id: 102
  actor_name: "hermes"
  faucet_name: "cascade"
  artifact_type: "table_documentation"
  artifact_kind: "database_schema"
  purpose: "Complete documentation for lupo_actor_capabilities table - actor permission management"
  tags: ["table_documentation", "actors", "capabilities", "permissions", "4.0.80"]
  created_ymdhis: 20260317165000
---

# lupo_actor_capabilities - Actor Capability Management

**Table Type**: Permission Registry  
**Domain**: Actor System  
**Criticality**: HIGH - Controls what actors can do  
**Primary Key**: `actor_capability_id`  
**Unique Key**: `(actor_id, domain_id, capability_key)`

## Overview

The `lupo_actor_capabilities` table manages permissions and capabilities for actors within specific domains. It provides a flexible, domain-scoped permission system that allows fine-grained control over actor actions throughout the Lupopedia platform.

### Key Characteristics
- **Domain-Scoped**: Capabilities are scoped to specific domains
- **Flexible Permissions**: Supports various capability types and restrictions
- **Approval Workflow**: Optional approval system for sensitive capabilities
- **Rate Limiting**: Built-in rate limiting for capability usage

## Table Structure

### Core Identity Fields

| Column | Type | Description | Notes |
|--------|------|-------------|--------|
| `actor_capability_id` | bigint | **PRIMARY KEY** - Unique capability ID | Application-assigned |
| `actor_id` | bigint | Actor ID | References `lupo_actors.actor_id` |
| `domain_id` | bigint | Domain ID | References `lupo_domains.domain_id` |
| `capability_key` | varchar(100) | Capability identifier | e.g., 'create_content', 'admin_access' |

### Description Fields

| Column | Type | Description | Notes |
|--------|------|-------------|--------|
| `capability_description` | text | Human-readable description | Explains what the capability allows |

### Timestamp Fields

| Column | Type | Description | Default |
|--------|------|-------------|--------|
| `created_ymdhis` | bigint | Creation timestamp | 0 |
| `updated_ymdhis` | bigint | Last update timestamp | NULL |
| `deleted_ymdhis` | bigint | Deletion timestamp | NULL |

### Status Fields

| Column | Type | Description | Default |
|--------|------|-------------|--------|
| `is_deleted` | tinyint | Capability is deleted | 0 |

### Control Fields

| Column | Type | Description | Default |
|--------|------|-------------|--------|
| `scope_limitation` | varchar(50) | Scope restriction | 'unrestricted' |
| `max_calls_per_hour` | int | Rate limit per hour | 0 (unlimited) |
| `requires_approval` | tinyint | Requires approval before use | 0 |
| `approval_agent_id` | bigint | Agent that can approve | NULL |

## Indexes

### Primary Index
- `PRIMARY KEY (actor_capability_id)` - Unique capability identifier

### Unique Index
- `lupo_actor_capabilities_unique_agent_domain_capability (actor_id, domain_id, capability_key)` - Prevents duplicate capabilities

### Performance Indexes
- `lupo_actor_capabilities_idx_agent_domain (actor_id, domain_id)` - Find actor capabilities in domain
- `lupo_actor_capabilities_idx_domain_id (domain_id)` - Find all capabilities in domain
- `lupo_actor_capabilities_idx_capability_key (capability_key)` - Find specific capability across actors
- `lupo_actor_capabilities_idx_created_ymdhis (created_ymdhis)` - Sort by creation time
- `lupo_actor_capabilities_idx_updated_ymdhis (updated_ymdhis)` - Sort by update time
- `lupo_actor_capabilities_idx_is_deleted (is_deleted)` - Filter deleted capabilities

## Key Relationships

### Many-to-One Relationships
- **Actor**: `lupo_actor_capabilities.actor_id` → `lupo_actors.actor_id`
- **Domain**: `lupo_actor_capabilities.domain_id` → `lupo_domains.domain_id`
- **Approval Agent**: `lupo_actor_capabilities.approval_agent_id` → `lupo_actors.actor_id`

### Usage Tracking
- **Capability Usage**: `lupo_capability_usage.actor_capability_id` → `lupo_actor_capabilities.actor_capability_id`

## Usage Patterns

### Capability Assignment
```php
// Grant capability to actor
$capability = [
    'actor_capability_id' => generateId(),
    'actor_id' => 102,
    'domain_id' => 1,
    'capability_key' => 'create_content',
    'capability_description' => 'Can create new content',
    'created_ymdhis' => 20260317165000,
    'scope_limitation' => 'unrestricted',
    'max_calls_per_hour' => 100,
    'requires_approval' => 0
];
```

### Capability Check
```php
// Check if actor has capability
$hasCapability = CapabilityService::actorHasCapability(
    $actorId, 
    $domainId, 
    'create_content'
);

// Check with rate limiting
$canUse = CapabilityService::canUseCapability(
    $actorId, 
    $capabilityId, 
    $context
);
```

### Capability Types
Common capability keys include:
- **Content**: `create_content`, `edit_content`, `delete_content`, `publish_content`
- **Admin**: `admin_access`, `user_management`, `system_configuration`
- **Channel**: `create_channel`, `join_channel`, `moderate_channel`
- **Agent**: `execute_agent`, `configure_agent`, `deploy_agent`

## Scope Limitations

### Scope Types
- **unrestricted**: No scope limitations (default)
- **own_content**: Can only act on own content
- **domain_content**: Can act on any content in domain
- **channel_content**: Limited to specific channels
- **department_content**: Limited to department content

### Scope Implementation
```php
// Apply scope limitation
switch ($capability['scope_limitation']) {
    case 'own_content':
        $whereClause = "created_by_actor_id = :actor_id";
        break;
    case 'domain_content':
        $whereClause = "domain_id = :domain_id";
        break;
    case 'channel_content':
        $whereClause = "channel_id IN (SELECT channel_id FROM lupo_actor_channels WHERE actor_id = :actor_id)";
        break;
}
```

## Rate Limiting

### Rate Limit Implementation
```php
// Check rate limit
$usage = CapabilityUsageService::getUsageCount($capabilityId, $hourStart);
if ($capability['max_calls_per_hour'] > 0 && $usage >= $capability['max_calls_per_hour']) {
    throw new RateLimitException("Capability rate limit exceeded");
}

// Record usage
CapabilityUsageService::recordUsage($capabilityId, $context);
```

### Rate Limit Strategies
- **0**: Unlimited usage
- **1-10**: Highly restricted capabilities
- **11-100**: Normal user capabilities
- **101-1000**: Power user capabilities
- **1000+**: System/admin capabilities

## Approval Workflow

### Approval Process
```php
// Check if approval required
if ($capability['requires_approval']) {
    $approval = ApprovalService::requestApproval(
        $actorId,
        $capabilityId,
        $context
    );
    
    if (!$approval->isApproved()) {
        throw new ApprovalRequiredException("Capability requires approval");
    }
}
```

### Approval Agents
- **Domain Admin**: Can approve domain-scoped capabilities
- **System Admin**: Can approve system-wide capabilities
- **Capability Owner**: Can approve specific capability types

## Security Considerations

### Permission Validation
- Always validate capabilities before executing actions
- Check both capability existence and scope limitations
- Apply rate limiting to prevent abuse
- Require approval for sensitive operations

### Capability Escalation
- Monitor for privilege escalation attempts
- Log all capability usage for audit trails
- Implement capability inheritance where appropriate
- Regular review of high-risk capabilities

### Data Protection
- Secure storage of capability descriptions
- Audit trail for capability changes
- Protect against capability injection attacks
- Validate capability keys against whitelist

## Performance Considerations

### High-Volume Operations
- Cache frequently used capability checks
- Batch capability validation for multiple operations
- Use indexes for efficient capability lookups
- Implement capability caching per actor

### Optimization Strategies
```php
// Cache actor capabilities
$actorCapabilities = CacheService::getActorCapabilities($actorId, $domainId);

// Batch validation
$requiredCapabilities = ['create_content', 'edit_content'];
$hasAllCapabilities = CapabilityService::actorHasCapabilities(
    $actorId, 
    $domainId, 
    $requiredCapabilities
);
```

## Common Queries

### Actor Capabilities in Domain
```sql
SELECT capability_key, capability_description, scope_limitation, max_calls_per_hour
FROM lupo_actor_capabilities 
WHERE actor_id = 102 
  AND domain_id = 1 
  AND is_deleted = 0;
```

### Capabilities Requiring Approval
```sql
SELECT ac.actor_id, a.actor_name, ac.capability_key, ac.approval_agent_id
FROM lupo_actor_capabilities ac
JOIN lupo_actors a ON ac.actor_id = a.actor_id
WHERE ac.requires_approval = 1 
  AND ac.is_deleted = 0;
```

### Rate Limited Capabilities
```sql
SELECT capability_key, max_calls_per_hour, COUNT(*) as actor_count
FROM lupo_actor_capabilities 
WHERE max_calls_per_hour > 0 
  AND is_deleted = 0
GROUP BY capability_key, max_calls_per_hour
ORDER BY max_calls_per_hour DESC;
```

### Domain Capability Summary
```sql
SELECT 
    d.domain_name,
    COUNT(*) as total_capabilities,
    SUM(CASE WHEN requires_approval = 1 THEN 1 ELSE 0 END) as approval_required,
    SUM(CASE WHEN max_calls_per_hour > 0 THEN 1 ELSE 0 END) as rate_limited
FROM lupo_actor_capabilities ac
JOIN lupo_domains d ON ac.domain_id = d.domain_id
WHERE ac.is_deleted = 0
GROUP BY d.domain_id, d.domain_name;
```

## Integration Points

### Authentication System
- Capabilities checked during login process
- Session storage of frequently used capabilities
- Integration with role-based access control

### Channel System
- Channel-specific capabilities
- Membership-based capability inheritance
- Channel moderation capabilities

### Agent System
- Agent execution capabilities
- Agent configuration permissions
- Agent deployment restrictions

## Troubleshooting

### Common Issues
1. **Missing Capability**: Check if capability is deleted or wrong domain
2. **Rate Limit Exceeded**: Verify max_calls_per_hour settings
3. **Approval Required**: Check requires_approval flag and approval status
4. **Scope Violation**: Verify scope_limitation matches usage context

### Debug Queries
```sql
-- Check actor capabilities
SELECT * FROM lupo_actor_capabilities 
WHERE actor_id = 102 
  AND domain_id = 1 
  AND is_deleted = 0;

-- Find duplicate capabilities
SELECT actor_id, domain_id, capability_key, COUNT(*) as count
FROM lupo_actor_capabilities 
GROUP BY actor_id, domain_id, capability_key
HAVING COUNT(*) > 1;

-- Check rate limit usage
SELECT 
    ac.capability_key,
    ac.max_calls_per_hour,
    COUNT(cu.usage_id) as usage_count
FROM lupo_actor_capabilities ac
LEFT JOIN lupo_capability_usage cu ON ac.actor_capability_id = cu.actor_capability_id
    AND cu.last_used_ymdhis >= 20260317000000
WHERE ac.actor_id = 102 
  AND ac.max_calls_per_hour > 0
GROUP BY ac.actor_capability_id, ac.capability_key, ac.max_calls_per_hour;
```

## Migration Notes

### Version History
- **v4.0.50**: Initial capability system
- **v4.0.65**: Added rate limiting and approval workflow
- **v4.0.75**: Enhanced scope limitations
- **v4.0.80**: Current schema with comprehensive permission management

### Breaking Changes
- Added `requires_approval` and `approval_agent_id` fields
- Enhanced `scope_limitation` with more options
- Improved rate limiting granularity

## Best Practices

### Capability Design
- Use descriptive capability keys
- Provide clear capability descriptions
- Implement appropriate scope limitations
- Set reasonable rate limits

### Security Practices
- Regular audit of capability assignments
- Monitor for unusual usage patterns
- Implement capability expiration where appropriate
- Use approval workflows for sensitive operations

### Performance Practices
- Cache capability checks for frequently accessed actors
- Batch capability validation when possible
- Use appropriate indexes for query optimization
- Monitor capability lookup performance

---

**Table Statistics**:
- **Records**: Variable based on actor count and capabilities
- **Size**: Medium - grows with actor base
- **Growth Rate**: Medium - new capabilities added as needed
- **Criticality**: HIGH - Controls system access

**Dependencies**:
- **Required By**: Capability validation throughout system
- **References**: `lupo_actors`, `lupo_domains`
- **Integrations**: Authentication, Channels, Agents, Content

**Maintenance**:
- **Backup Priority**: HIGH
- **Archive Policy**: Soft delete with `is_deleted`
- **Cleanup**: Review unused capabilities quarterly
- **Monitoring**: Track capability usage and performance
