---
lupopedia.headers:
  lupopedia.version: "4.0.80"
  lupopedia.schema: "table"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/rules/lupo_orchestrator_rules.md"
  channel_id: 42
  actor_id: 102
  actor_name: "hermes"
  faucet_name: "cascade"
  artifact_type: "table_documentation"
  artifact_kind: "database_schema"
  purpose: "Complete documentation for lupo_orchestrator_rules table - orchestration logic"
  tags: ["table_documentation", "orchestrator_rules", "orchestration", "automation", "4.0.80"]
  created_ymdhis: 20260317_223000
---

# lupo_orchestrator_rules - Orchestration Logic

**Table Type**: Orchestrator Rule Registry  
**Domain**: Rule System  
**Criticality**: MEDIUM - Orchestration logic and automation  
**Primary Key**: `rule_id` (AUTO_INCREMENT)

## Overview

The `lupo_orchestrator_rules` table stores orchestration rules that define system-wide automation and coordination logic. These rules are specifically designed for orchestrator actors (like WOLFIE) to manage system operations, workflows, and multi-agent coordination.

### Key Characteristics
- **Orchestration-Specific**: Rules for system orchestration and coordination
- **Actor-Scoped**: Rules associated with specific orchestrator actors
- **Versioned**: Rule set versioning for consistency
- **Flexible Targeting**: JSON-based rule targeting and application

## Table Structure

### Core Identity Fields

| Column | Type | Description | Notes |
|--------|------|-------------|--------|
| `rule_id` | bigint | **PRIMARY KEY** - Auto-increment ID | Unique rule identifier |
| `rule_slug` | varchar(128) | Rule slug identifier | Human-readable unique identifier |
| `orchestrator_actor` | varchar(64) | Orchestrator actor name | 'wolfie', 'hermes', etc. |
| `rule_set_version` | varchar(32) | Rule set version | Version identifier for rule sets |

### Configuration Fields

| Column | Type | Description | Default |
|--------|------|-------------|--------|
| `applies_to_json` | text | Rule application targets | JSON structure defining targets |
| `enforcement_level` | varchar(32) | Enforcement level | 'strict', 'moderate', 'advisory' |
| `rule_content` | text | Rule content | Rule logic and instructions |
| `checksum` | varchar(64) | Rule checksum | Integrity verification |

### Status Fields

| Column | Type | Description | Default |
|--------|------|-------------|--------|
| `is_active` | tinyint | Rule is active | 1 |
| `updated_ymdhis` | bigint | Last update timestamp | Current time |

## Indexes

### Primary Index
- `PRIMARY KEY (rule_id)` - Auto-increment unique identifier

### Unique Index
- `lupo_orchestrator_rules_uniq_slug (rule_slug)` - Unique rule slug

### Performance Indexes
- `lupo_orchestrator_rules_idx_active (is_active)` - Active rule filtering
- `lupo_orchestrator_rules_idx_actor_version (orchestrator_actor, rule_set_version)` - Actor and version filtering
- `lupo_orchestrator_rules_idx_updated (updated_ymdhis)` - Time-based sorting

## Key Relationships

### Actor Relationships
- **Orchestrator**: `orchestrator_actor` references `lupo_actors.actor_name`
- **Rule Targets**: `applies_to_json` references various system entities
- **Rule Execution**: Rules executed by orchestrator actors

### System Integration
- **Multi-Agent Coordination**: Rules for agent coordination
- **Workflow Management**: Rules for workflow automation
- **System Governance**: Rules for system-wide policies

## Usage Patterns

### Rule Creation
```php
// Create an orchestrator rule
$rule = [
    'rule_slug' => 'wolfie_channel_coordination',
    'orchestrator_actor' => 'wolfie',
    'rule_set_version' => '4.0.80',
    'applies_to_json' => json_encode([
        'channels' => [42, 43, 44],
        'actors' => ['hermes', 'lilith'],
        'operations' => ['task_assignment', 'coordination']
    ]),
    'enforcement_level' => 'strict',
    'rule_content' => 'Coordinate channel activities and assign tasks based on priority',
    'checksum' => generateChecksum($ruleContent),
    'is_active' => 1,
    'updated_ymdhis' => 20260317223000
];
```

### Rule Retrieval
```php
// Get rules by orchestrator
$rules = OrchestratorRuleService::getRulesByActor('wolfie');

// Get active rules
$activeRules = OrchestratorRuleService::getActiveRules();

// Get rule by slug
$rule = OrchestratorRuleService::getRuleBySlug('wolfie_channel_coordination');
```

### Rule Execution
```php
// Execute orchestration rule
$result = OrchestratorRuleService::executeRule($ruleSlug, $context);

// Execute rules for actor
$results = OrchestratorRuleService::executeRulesForActor('wolfie', $context);

// Validate rule application
$isValid = OrchestratorRuleService::validateRuleApplication($rule, $context);
```

## Orchestrator Actors

### Primary Orchestrators
- **wolfie**: Main system orchestrator
- **hermes**: Technical implementation orchestrator
- **lilith**: Quality assurance orchestrator
- **athena**: Strategy and wisdom orchestrator

### Actor Responsibilities
- **wolfie**: System-wide coordination and governance
- **hermes**: Technical implementation and development
- **lilith**: Quality assurance and validation
- **athena**: Strategic planning and decision making

## Rule Set Versioning

### Version Management
```php
// Create new rule set version
$newVersion = OrchestratorRuleService::createRuleSetVersion('4.0.81', $rules);

// Get rules by version
$rules = OrchestratorRuleService::getRulesByVersion('4.0.80', 'wolfie');

// Migrate rules between versions
OrchestratorRuleService::migrateRuleSet('4.0.80', '4.0.81', 'wolfie');
```

### Version Strategy
- Semantic versioning (major.minor.patch)
- Backward compatibility considerations
- Version deprecation policies
- Rollback capabilities

## Application Targets

### Target Structure
```json
{
    "channels": {
        "include": [42, 43, 44],
        "exclude": [],
        "types": ["development", "coordination"]
    },
    "actors": {
        "include": ["hermes", "lilith"],
        "exclude": [],
        "types": ["technical", "qa"]
    },
    "operations": {
        "include": ["task_assignment", "coordination"],
        "exclude": ["system_maintenance"]
    },
    "projects": {
        "include": ["lupopedia_core"],
        "exclude": []
    }
}
```

### Target Types
- **Channels**: Channel-based rule application
- **Actors**: Actor-specific rule targeting
- **Operations**: Operation-specific rules
- **Projects**: Project-scoped rules
- **System**: System-wide rules

## Enforcement Levels

### Strict Enforcement
- **Level**: 'strict'
- **Behavior**: Rules must be followed without exception
- **Use Cases**: Security policies, critical operations
- **Compliance**: Mandatory compliance

### Moderate Enforcement
- **Level**: 'moderate'
- **Behavior**: Rules should be followed with flexibility
- **Use Cases**: Standard procedures, best practices
- **Compliance**: High compliance expected

### Advisory Enforcement
- **Level**: 'advisory'
- **Behavior**: Rules are recommendations
- **Use Cases**: Guidelines, suggestions
- **Compliance**: Voluntary compliance

## Rule Content

### Content Structure
```
# Rule: Channel Coordination

## Purpose
Coordinate channel activities and ensure proper task assignment

## Conditions
- Channel is active
- Actors have required capabilities
- Tasks are properly prioritized

## Actions
1. Review channel status
2. Assign tasks based on priority
3. Coordinate actor activities
4. Monitor progress

## Validation
- Tasks assigned appropriately
- No conflicts in assignments
- Progress tracked correctly
```

### Content Types
- **Procedural Rules**: Step-by-step procedures
- **Conditional Rules**: If-then conditions
- **Policy Rules**: System policies and guidelines
- **Coordination Rules**: Multi-agent coordination

## Performance Considerations

### High-Volume Operations
- Cache frequently accessed rules
- Use actor-based indexing for efficient filtering
- Batch rule execution for multiple rules
- Implement rule result caching

### Optimization Strategies
```php
// Cache rules by actor
$cacheKey = "orchestrator_rules:{$actorName}";
$rules = CacheService::get($cacheKey);
if (!$rules) {
    $rules = OrchestratorRuleService::getRulesByActor($actorName);
    CacheService::set($cacheKey, $rules, 300);
}

// Batch rule execution
$rules = OrchestratorRuleService::getActiveRules();
$results = OrchestratorRuleService::batchExecuteRules($rules, $context);
```

## Common Queries

### Rules by Orchestrator
```sql
SELECT 
    rule_id,
    rule_slug,
    rule_set_version,
    enforcement_level,
    updated_ymdhis
FROM lupo_orchestrator_rules 
WHERE orchestrator_actor = 'wolfie'
  AND is_active = 1
ORDER BY rule_slug;
```

### Rules by Version
```sql
SELECT 
    orchestrator_actor,
    COUNT(*) as rule_count,
    rule_set_version
FROM lupo_orchestrator_rules 
WHERE is_active = 1
GROUP BY orchestrator_actor, rule_set_version
ORDER BY rule_set_version DESC, orchestrator_actor;
```

### Recently Updated Rules
```sql
SELECT 
    rule_id,
    rule_slug,
    orchestrator_actor,
    enforcement_level,
    updated_ymdhis
FROM lupo_orchestrator_rules 
WHERE is_active = 1
  AND updated_ymdhis >= 20260317000000
ORDER BY updated_ymdhis DESC;
```

### Rules by Enforcement Level
```sql
SELECT 
    enforcement_level,
    COUNT(*) as rule_count,
    COUNT(DISTINCT orchestrator_actor) as actor_count
FROM lupo_orchestrator_rules 
WHERE is_active = 1
GROUP BY enforcement_level
ORDER BY rule_count DESC;
```

## Integration Points

### Multi-Agent System
- Rules for agent coordination
- Workflow automation between agents
- Conflict resolution mechanisms
- Task assignment and distribution

### Channel System
- Channel-based rule application
- Channel coordination rules
- Activity monitoring and control
- Communication protocols

### Project System
- Project orchestration rules
- Task assignment and tracking
- Resource allocation
- Progress monitoring

## Security Considerations

### Access Control
- Restrict rule creation to authorized orchestrators
- Implement rule modification controls
- Audit rule changes and executions
- Protect sensitive orchestration logic

### Rule Integrity
- Validate rule checksums
- Implement rule versioning
- Monitor rule execution integrity
- Provide rule rollback capabilities

### System Protection
- Validate rule application targets
- Prevent rule conflicts
- Monitor resource usage
- Implement rule execution limits

## Troubleshooting

### Common Issues
1. **Rule Conflicts**: Check for conflicting rules
2. **Target Issues**: Validate applies_to_json structure
3. **Execution Failures**: Check rule content and context
4. **Version Issues**: Verify rule set version consistency

### Debug Queries
```sql
-- Check for duplicate rule slugs
SELECT rule_slug, COUNT(*) as count
FROM lupo_orchestrator_rules 
WHERE is_active = 1
GROUP BY rule_slug
HAVING COUNT(*) > 1;

-- Find inactive rules
SELECT rule_id, rule_slug, orchestrator_actor
FROM lupo_orchestrator_rules 
WHERE is_active = 0;

-- Check rule checksum integrity
SELECT rule_id, rule_slug, checksum
FROM lupo_orchestrator_rules 
WHERE checksum IS NULL OR checksum = '';
```

## Migration Notes

### Version History
- **v4.0.73**: Initial orchestrator rules implementation
- **v4.0.75**: Added enforcement levels and checksums
- **v4.0.78**: Enhanced JSON targeting and versioning
- **v4.0.80**: Current schema with comprehensive orchestration

### Breaking Changes
- Added enforcement_level for rule compliance
- Enhanced applies_to_json with structured targeting
- Improved checksum validation for integrity

## Best Practices

### Rule Design
- Use descriptive rule slugs and content
- Implement proper error handling in rules
- Use appropriate enforcement levels
- Document rule logic and dependencies

### Performance Optimization
- Cache frequently accessed rules
- Optimize rule execution patterns
- Use appropriate indexes for queries
- Monitor rule execution performance

### Security Practices
- Validate rule content and targets
- Implement proper access controls
- Audit rule executions and changes
- Use checksums for integrity verification

---

**Table Statistics**:
- **Records**: Variable based on orchestration complexity
- **Size**: Small - focused orchestration rules
- **Growth Rate**: Low - rules added as needed
- **Criticality**: MEDIUM - System orchestration and automation

**Dependencies**:
- **Required By**: Orchestrator actors and automation systems
- **References**: `lupo_actors`, various system entities
- **Integrations**: Multi-Agent System, Channel System, Project System

**Maintenance**:
- **Backup Priority**: MEDIUM
- **Archive Policy**: Soft delete with `is_active`
- **Cleanup**: Review inactive rules quarterly
- **Monitoring**: Track rule execution and system impact
