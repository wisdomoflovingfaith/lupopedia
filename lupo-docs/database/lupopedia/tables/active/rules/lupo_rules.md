---
lupopedia.headers:
  lupopedia.version: "4.0.80"
  lupopedia.schema: "table"
  file_path_from_root: "lupo-docs/database/lupopedia/tables/active/rules/lupo_rules.md"
  channel_id: 42
  actor_id: 102
  actor_name: "hermes"
  faucet_name: "cascade"
  artifact_type: "table_documentation"
  artifact_kind: "database_schema"
  purpose: "Complete documentation for lupo_rules table - rule engine definitions"
  tags: ["table_documentation", "rules", "engine", "automation", "4.0.80"]
  created_ymdhis: 20260317_222000
---

# lupo_rules - Rule Engine Definitions

**Table Type**: Rule Registry  
**Domain**: Rule System  
**Criticality**: MEDIUM - System rule definitions and automation  
**Primary Key**: `rule_id` (application-assigned)

## Overview

The `lupo_rules` table serves as the canonical registry for all system rules in Lupopedia. It stores rule definitions, scripts, and metadata that drive the rule engine for system automation, validation, and governance.

### Key Characteristics
- **Rule Registry**: Central storage for all system rules
- **Script-Based**: Rules contain executable scripts
- **Version Control**: Built-in versioning for rule evolution
- **Type Classification**: Categorized rule types for organization

## Table Structure

### Core Identity Fields

| Column | Type | Description | Notes |
|--------|------|-------------|--------|
| `rule_id` | bigint | **PRIMARY KEY** - Unique rule ID | Application-assigned, not auto-increment |
| `rule_name` | varchar(255) | Rule name | Human-readable rule identifier |
| `rule_description` | text | Rule description | Detailed rule explanation |
| `rule_type` | varchar(64) | Type of rule | 'validation', 'automation', 'security', etc. |
| `rule_script` | text | Rule script | Executable rule logic |
| `rule_version` | bigint | Rule version | Default 1, increments with updates |

### Timestamp Fields

| Column | Type | Description | Notes |
|--------|------|-------------|--------|
| `created_ymdhis` | bigint | Creation timestamp | YYYYMMDDHHIISS format |
| `updated_ymdhis` | bigint | Last update timestamp | YYYYMMDDHHIISS format |
| `deleted_ymdhis` | bigint | Deletion timestamp | NULL if not deleted |

### Status Fields

| Column | Type | Description | Default |
|--------|------|-------------|--------|
| `is_deleted` | tinyint | Rule is deleted | 0 |

## Indexes

### Primary Index
- `PRIMARY KEY (rule_id)` - Unique rule identifier

### Performance Indexes
- `lupo_rules_idx_is_deleted (is_deleted)` - Deleted status filtering
- `lupo_rules_idx_rule_name (rule_name)` - Rule name lookup
- `lupo_rules_idx_rule_type (rule_type)` - Rule type filtering

## Key Relationships

### One-to-Many Relationships
- **Rule Targets**: `lupo_rule_targets.rule_id` → `lupo_rules.rule_id`
- **Rule Logs**: `lupo_rule_logs.rule_id` → `lupo_rules.rule_id`
- **Rule Executions**: `lupo_rule_executions.rule_id` → `lupo_rules.rule_id`

### Related Tables
- **lupo_rule_targets**: Rule application targets
- **lupo_rule_logs**: Rule execution logs
- **lupo_rule_executions**: Rule execution history

## Usage Patterns

### Rule Creation
```php
// Create a new rule
$rule = [
    'rule_id' => generateId(),
    'rule_name' => 'validate_actor_permissions',
    'rule_description' => 'Validates actor permissions before action execution',
    'rule_type' => 'validation',
    'rule_script' => 'return $actor->hasCapability($required_capability);',
    'rule_version' => 1,
    'created_ymdhis' => 20260317222000,
    'updated_ymdhis' => 20260317222000
];
```

### Rule Retrieval
```php
// Get rule by ID
$rule = RuleService::getRule($ruleId);

// Get rules by type
$validationRules = RuleService::getRulesByType('validation');

// Get all active rules
$activeRules = RuleService::getActiveRules();
```

### Rule Execution
```php
// Execute rule
$result = RuleService::executeRule($ruleId, $context);

// Execute rules by type
$results = RuleService::executeRulesByType('validation', $context);

// Validate with rules
$isValid = RuleService::validateWithRules($context, 'validation');
```

## Rule Types

### Validation Rules
- **Type**: 'validation'
- **Purpose**: Data validation and integrity checks
- **Execution**: Before data operations
- **Examples**: Email format validation, permission checks

### Automation Rules
- **Type**: 'automation'
- **Purpose**: Automated system operations
- **Execution**: Scheduled or event-triggered
- **Examples**: Daily cleanup, user onboarding

### Security Rules
- **Type**: 'security'
- **Purpose**: Security enforcement and monitoring
- **Execution**: On security-sensitive operations
- **Examples**: Access control, threat detection

### Governance Rules
- **Type**: 'governance'
- **Purpose**: System governance and compliance
- **Execution**: On policy-relevant operations
- **Examples**: Data retention, audit requirements

### Notification Rules
- **Type**: 'notification'
- **Purpose**: Notification and alerting
- **Execution**: On relevant events
- **Examples**: Task assignments, system alerts

## Rule Scripts

### Script Languages
- **PHP**: Primary language for system rules
- **SQL**: Database validation rules
- **JavaScript**: Client-side validation
- **Custom**: Domain-specific languages

### Script Structure
```php
// Example validation rule
return [
    'valid' => $input['status'] === 'active',
    'message' => $input['status'] === 'active' ? null : 'Status must be active'
];

// Example automation rule
if ($event['type'] === 'user_created') {
    NotificationService::sendWelcomeEmail($event['user_id']);
    return ['action' => 'welcome_sent'];
}

// Example security rule
if ($action['type'] === 'delete' && !$actor->hasCapability('admin_delete')) {
    throw new SecurityException('Insufficient permissions for delete action');
}
```

### Script Security
- Rule scripts execute in controlled environment
- Sandboxed execution for security
- Resource limits and timeouts
- Audit logging for all executions

## Version Management

### Version Control
```php
// Create new version of rule
$newVersion = RuleService::createNewVersion($ruleId, $updatedScript);

// Get rule version history
$history = RuleService::getRuleVersionHistory($ruleId);

// Revert to previous version
RuleService::revertToVersion($ruleId, $targetVersion);
```

### Version Strategy
- Incremental version numbers
- Backward compatibility considerations
- Version deprecation policies
- Migration between versions

## Rule Targets

### Target Application
- Rules can be applied to specific targets
- Targets include tables, records, or entities
- Priority-based rule application
- Conditional rule execution

### Target Types
- **Table Rules**: Apply to entire table operations
- **Record Rules**: Apply to specific records
- **Entity Rules**: Apply to business entities
- **System Rules**: Apply to system operations

## Performance Considerations

### High-Volume Operations
- Cache frequently accessed rules
- Use rule type indexing for efficient filtering
- Batch rule execution for multiple rules
- Implement rule result caching

### Optimization Strategies
```php
// Cache rule definitions
$cacheKey = "rule_definition:{$ruleId}";
$rule = CacheService::get($cacheKey);
if (!$rule) {
    $rule = RuleService::getRule($ruleId);
    CacheService::set($cacheKey, $rule, 300);
}

// Batch rule execution
$rules = RuleService::getRulesByType('validation');
$results = RuleService::batchExecuteRules($rules, $context);
```

## Common Queries

### Rules by Type
```sql
SELECT 
    rule_id,
    rule_name,
    rule_description,
    rule_version,
    created_ymdhis
FROM lupo_rules 
WHERE rule_type = 'validation'
  AND is_deleted = 0
ORDER BY rule_name;
```

### Rule Version History
```sql
SELECT 
    rule_id,
    rule_name,
    rule_version,
    updated_ymdhis
FROM lupo_rules 
WHERE rule_name = 'validate_actor_permissions'
  AND is_deleted = 0
ORDER BY rule_version DESC;
```

### Active Rules Count
```sql
SELECT 
    rule_type,
    COUNT(*) as rule_count,
    MAX(rule_version) as latest_version
FROM lupo_rules 
WHERE is_deleted = 0
GROUP BY rule_type
ORDER BY rule_count DESC;
```

### Recently Updated Rules
```sql
SELECT 
    rule_id,
    rule_name,
    rule_type,
    updated_ymdhis
FROM lupo_rules 
WHERE is_deleted = 0
  AND updated_ymdhis >= 20260317000000
ORDER BY updated_ymdhis DESC;
```

## Integration Points

### Rule Engine
- Core rule execution engine
- Rule scheduling and triggering
- Rule result aggregation
- Rule performance monitoring

### Validation System
- Input validation rules
- Business rule validation
- Data integrity checks
- Constraint enforcement

### Automation System
- Scheduled task automation
- Event-driven automation
- Workflow automation
- System maintenance automation

## Security Considerations

### Script Security
- Validate rule script syntax
- Implement sandboxed execution
- Resource usage limits
- Audit all rule executions

### Access Control
- Restrict rule creation to authorized actors
- Implement rule modification controls
- Audit rule changes and executions
- Protect sensitive rule logic

### Data Protection
- Encrypt sensitive rule data
- Implement rule access logging
- Protect rule execution context
- Provide rule deletion capabilities

## Troubleshooting

### Common Issues
1. **Script Errors**: Validate rule script syntax
2. **Performance Issues**: Optimize rule execution
3. **Version Conflicts**: Check version consistency
4. **Target Issues**: Validate rule targets

### Debug Queries
```sql
-- Check for duplicate rule names
SELECT rule_name, COUNT(*) as count
FROM lupo_rules 
WHERE is_deleted = 0
GROUP BY rule_name
HAVING COUNT(*) > 1;

-- Find rules with execution issues
SELECT rule_id, rule_name, rule_type
FROM lupo_rules 
WHERE is_deleted = 0
  AND rule_script IS NULL OR rule_script = '';

-- Check rule version consistency
SELECT rule_name, COUNT(*) as version_count
FROM lupo_rules 
WHERE is_deleted = 0
GROUP BY rule_name
HAVING COUNT(*) > 1;
```

## Migration Notes

### Version History
- **v4.0.68**: Initial rule system implementation
- **v4.0.70**: Added rule versioning and targets
- **v4.0.75**: Enhanced script execution and security
- **v4.0.80**: Current schema with comprehensive rule management

### Breaking Changes
- Added rule_version for version control
- Enhanced rule_script with security improvements
- Improved rule type classification

## Best Practices

### Rule Design
- Use descriptive rule names and descriptions
- Implement proper error handling in scripts
- Use appropriate rule types for organization
- Document rule logic and dependencies

### Performance Optimization
- Cache frequently accessed rules
- Optimize rule script execution
- Use rule batching when possible
- Monitor rule execution performance

### Security Practices
- Validate all rule script inputs
- Implement proper access controls
- Audit rule executions and changes
- Use sandboxed execution environments

---

**Table Statistics**:
- **Records**: Variable based on rule volume
- **Size**: Medium - grows with rule creation
- **Growth Rate**: Medium - new rules added as needed
- **Criticality**: MEDIUM - System automation and governance

**Dependencies**:
- **Required By**: Rule engine and automation systems
- **References**: Rule targets, logs, and executions
- **Integrations**: Rule Engine, Validation System, Automation System

**Maintenance**:
- **Backup Priority**: MEDIUM
- **Archive Policy**: Soft delete with `is_deleted`
- **Cleanup**: Review unused rules quarterly
- **Monitoring**: Track rule execution and performance
