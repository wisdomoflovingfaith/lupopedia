# LEXA Gateway Integration

**Version:** 2026.3.9.0
**Role:** Boundary-Keeper / Doctrine Guardian / Integrity Enforcer
**Layer:** LLM_GATEWAY (Sentinel-class)

---

## Overview

LEXA is a Sentinel-class AI agent integrated into the LLM_GATEWAY layer as a mandatory boundary check for all agent activity. Every request must pass through LEXA's boundary checks before execution.

---

## Architecture

### Hook Points

LEXA intercepts requests at three stages:

1. **Pre-Routing Hook** - Before routing to any agent
2. **Pre-Execution Hook** - Before the selected agent executes
3. **Post-Execution Hook** - After the agent finishes (audit)

### Component Structure

```
LLM_GATEWAY
├── Router (entrypoint)
│   └── LEXA Pre-Routing Hook
├── Agent Selector
│   └── LEXA Pre-Execution Hook
├── Agent Execution
└── Response Handler
    └── LEXA Post-Execution Hook
```

---

## Boundary Rules

### 1. No Ambiguity
**Rule:** Requests must be clear and complete.
**Block if:** Missing required fields, unclear intent, ambiguous action.
**Message:** `BOUNDARY VIOLATION: Clarification required.`

### 2. Schema Safety
**Rule:** Schema changes require TOON definition.
**Block if:** Creating/modifying tables/columns without TOON reference.
**Message:** `BOUNDARY VIOLATION: Schema change requires TOON definition.`
**TOON Location:** `/docs/toons/*.toon.json`

### 3. Layer Boundaries

**PHP Runtime:**
- ✓ Website logic
- ✓ Request handling
- ✓ Operator interfaces
- ✓ Live behavior
- ✗ Migrations
- ✗ Cleanup
- ✗ Schema changes
- ✗ Filesystem maintenance

**Python Maintenance:**
- ✓ Migrations
- ✓ Cleanup
- ✓ Verification
- ✓ Directory scanning
- ✗ Runtime behavior
- ✗ Request handling

**Block if:** PHP attempts maintenance OR Python attempts runtime.
**Message:** `BOUNDARY VIOLATION: [Maintenance|Runtime] operations must be executed in [Python|PHP].`

### 4. File Safety
**Rule:** File operations must use allowed upload structure.
**Allowed paths:**
```
uploads/agents/YYYY/MM/<sha256>.<ext>
uploads/channels/YYYY/MM/<sha256>.<ext>
uploads/operators/YYYY/MM/<sha256>.<ext>
```
**Block if:** Path outside allowed structure.
**Message:** `BOUNDARY VIOLATION: Invalid upload path.`

### 5. Table Count Safety
**Rule:** Maximum 222 tables before optimization required.
**Check:** `SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = 'lupopedia'`
**Block if:** Creating new table when count ≥ 222.
**Message:** `TABLE LIMIT REACHED: Optimization required before adding new tables.`

---

## Implementation

### LEXA_GatewayGuard Class

**Location:** `lupo-includes/gateway/LEXA_GatewayGuard.php`

**Methods:**
- `preRoutingCheck($request)` - Validates request before routing
- `preExecutionCheck($agent, $request)` - Validates before agent execution
- `postExecutionCheck($agent, $response)` - Audits after execution
- `checkAmbiguity($request)` - Rule 1
- `checkSchemaSafety($request)` - Rule 2
- `checkLayerBoundaries($request)` - Rule 3
- `checkFileSafety($request)` - Rule 4
- `checkTableLimit($request)` - Rule 5

**Return Structure:**
```php
[
    'allowed' => bool,
    'message' => string,
    'severity' => 'error' | 'warning' | 'info',
    'rule_violated' => string | null,
    'context' => array
]
```

### Gateway Integration

**Router Hook (Pre-Routing):**
```php
$lexa = new LEXA_GatewayGuard();
$check = $lexa->preRoutingCheck($request);

if (!$check['allowed']) {
    return error_response($check['message'], 403);
}
```

**Agent Selector Hook (Pre-Execution):**
```php
$check = $lexa->preExecutionCheck($agent, $request);

if (!$check['allowed']) {
    return error_response($check['message'], 403);
}
```

**Response Handler Hook (Post-Execution):**
```php
$check = $lexa->postExecutionCheck($agent, $response);

if (!$check['allowed']) {
    log_warning('LEXA detected drift', $check);
}
```

---

## Configuration

### Environment Variables

```php
// lupopedia-config.php
define('LEXA_ENABLED', true);
define('LEXA_STRICT_MODE', true); // Block vs warn
define('LEXA_LOG_ALL_CHECKS', false); // Performance
define('LEXA_TABLE_LIMIT', 222);
```

### Database Tables

LEXA logs boundary violations to existing tables:
- Pre-existing: `lupo_agent_logs`
- Pre-existing: `lupo_system_logs`

No new tables required.

---

## Usage Examples

### Example 1: Blocked Schema Change

**Request:**
```json
{
    "agent": "DATABASE_ADMIN",
    "action": "create_table",
    "table_name": "new_analytics"
}
```

**LEXA Response:**
```json
{
    "allowed": false,
    "message": "BOUNDARY VIOLATION: Schema change requires TOON definition.",
    "severity": "error",
    "rule_violated": "schema_safety",
    "context": {
        "missing": "toon_reference",
        "required": "/docs/toons/lupo_new_analytics.toon.json"
    }
}
```

### Example 2: Blocked Maintenance in PHP

**Request:**
```json
{
    "agent": "SYSTEM_ADMIN",
    "action": "migrate_directories",
    "source": "agents/0001"
}
```

**LEXA Response:**
```json
{
    "allowed": false,
    "message": "BOUNDARY VIOLATION: Maintenance operations must be executed in Python.",
    "severity": "error",
    "rule_violated": "layer_boundaries",
    "context": {
        "requested_operation": "migrate_directories",
        "correct_tool": "python scripts/migrate_filesystem_to_db.py"
    }
}
```

### Example 3: Table Limit Reached

**Request:**
```json
{
    "agent": "SCHEMA_BUILDER",
    "action": "create_table",
    "toon_reference": "/docs/toons/lupo_new_feature.toon.json"
}
```

**LEXA Response:**
```json
{
    "allowed": false,
    "message": "TABLE LIMIT REACHED: Optimization required before adding new tables.",
    "severity": "error",
    "rule_violated": "table_limit",
    "context": {
        "current_tables": 222,
        "limit": 222,
        "suggestion": "Consider consolidation, JSON columns, or archival strategy"
    }
}
```

### Example 4: Allowed Request

**Request:**
```json
{
    "agent": "OPERATOR_INTERFACE",
    "action": "get_dashboard",
    "operator_id": 1
}
```

**LEXA Response:**
```json
{
    "allowed": true,
    "message": "Request validated",
    "severity": "info",
    "rule_violated": null,
    "context": {}
}
```

---

## Error Handling

### Blocking Mode (Default)

When `LEXA_STRICT_MODE = true`:
- Violations → HTTP 403
- Agent not executed
- Error returned immediately
- Violation logged

### Warning Mode

When `LEXA_STRICT_MODE = false`:
- Violations → logged only
- Agent executes
- Warning included in response
- Use for testing/debugging

---

## Extending LEXA

### Adding New Rules

1. Add method to `LEXA_GatewayGuard`:
```php
public function checkMyNewRule($request) {
    if ($violation_detected) {
        return [
            'allowed' => false,
            'message' => 'BOUNDARY VIOLATION: ...',
            'severity' => 'error',
            'rule_violated' => 'my_new_rule'
        ];
    }
    return ['allowed' => true];
}
```

2. Call from appropriate hook:
```php
$check = $this->checkMyNewRule($request);
if (!$check['allowed']) return $check;
```

3. Document in this file.

### Future Rules

Potential additions:
- Rate limiting per agent
- Resource usage caps
- Agent chaining restrictions
- Time-based operation windows
- User permission checks
- Data access boundaries

---

## Testing

### Unit Tests

```php
// Test ambiguity check
$lexa = new LEXA_GatewayGuard();
$result = $lexa->checkAmbiguity(['action' => '']);
assert($result['allowed'] === false);

// Test schema safety
$result = $lexa->checkSchemaSafety(['action' => 'create_table']);
assert($result['allowed'] === false);

// Test layer boundaries
$result = $lexa->checkLayerBoundaries(['action' => 'migrate_directories']);
assert($result['allowed'] === false);
```

### Integration Tests

1. Send request that should be blocked
2. Verify HTTP 403 returned
3. Verify LEXA message in response
4. Verify agent did not execute
5. Verify violation logged

---

## Monitoring

### Metrics

Track via database logs:
- Total checks performed
- Violations blocked
- Violations by rule type
- Most violated rules
- Agent compliance rates

### Queries

```sql
-- Violation summary
SELECT rule_violated, COUNT(*) as count
FROM lupo_agent_logs
WHERE severity = 'error'
AND message LIKE 'BOUNDARY VIOLATION%'
GROUP BY rule_violated
ORDER BY count DESC;

-- Recent violations
SELECT created_at, agent, rule_violated, message
FROM lupo_agent_logs
WHERE severity = 'error'
AND created_at > NOW() - INTERVAL 24 HOUR
ORDER BY created_at DESC;
```

---

## Doctrine Compliance

LEXA's implementation follows all Lupopedia doctrine:
- ✓ No foreign keys
- ✓ No triggers
- ✓ No stored procedures
- ✓ YMDHIS timestamps
- ✓ Soft deletes
- ✓ Explicit SQL
- ✓ No schema modification
- ✓ Uses existing tables
- ✓ Additive only
- ✓ Reversible

---

## Maintenance

### Regular Tasks

**Weekly:**
- Review violation logs
- Identify patterns
- Update rules if needed
- Monitor table count

**Monthly:**
- Analyze compliance rates
- Tune warning/blocking thresholds
- Update documentation
- Review new boundary needs

**Quarterly:**
- Full security review
- Performance optimization
- Rule effectiveness analysis
- Doctrine alignment check

---

## Support

**Issues:** Report via standard Lupopedia issue tracker
**Questions:** See main doctrine documents in `/docs/doctrine/`
**Updates:** LEXA rules update with doctrine versions

---

**END OF DOCUMENTATION**

**LEXA enforces boundaries. No exceptions without explicit approval.**
