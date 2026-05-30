---`nlupopedia.footer:
  approved_for_release: "4.1.0"
  approval_status: "approved"
  approved_by_actor_id: 1
  approved_utc: 20260326192115lupopedia.headers:
  lupopedia.schema: table
  file_path_from_root: lupo-docs/database/lupopedia/tables/active/lupo_decisions.md
  channel_id: 42
  actor_id: 102
  actor_name: hermes
  faucet_name: cascade
  artifact_type: table_documentation
  artifact_kind: database_schema
  purpose: Complete documentation for lupo_decisions table - Bayesian decision tracking
  status: DEPRECATED
  deprecated_in_version: '4.0.87'
  deprecated_reason: >-
    Bayesian decision tracking removed. Decision history is now represented
    through channels, threads, and artifacts. See lupo-docs/doctrine/DECISION_MODEL.md.
  tags:
  - table_documentation
  - decisions
  - bayesian
  - deprecated
  - 4.0.87
  created_ymdhis: 20260317213000
  when_updated: '20260325000000'
lupopedia:
  footer:
    last_verified: '20260325000000'
    last_verified_by: cursor
    last_verified_by_actor_id: 102
    orchestrator: cursor:root
---

> **DEPRECATED (4.0.87):** This table has been removed. Decision history is now represented through channels, threads, and artifacts. ROSE interprets decision context from conversation history. See [DECISION_MODEL.md](../../doctrine/DECISION_MODEL.md).

# lupo_decisions - Bayesian Decision Tracking

**Table Type**: Decision Registry  
**Domain**: Decision System  
**Criticality**: MEDIUM - Decision analytics and pattern recognition  
**Primary Key**: `decision_id`

## Overview

The `lupo_decisions` table implements Bayesian decision tracking, capturing decision points, their probabilities, and relationships within channels and projects. It provides the foundation for decision analytics, pattern recognition, and intelligent decision support in Lupopedia.

### Key Characteristics
- **Bayesian Framework**: Probability-based decision tracking
- **Hierarchical Structure**: Parent-child decision relationships
- **Session Context**: Decision tracking within sessions
- **Channel/Project Scoping**: Decisions scoped to specific contexts

## Table Structure

### Core Identity Fields

| Column | Type | Description | Notes |
|--------|------|-------------|--------|
| `decision_id` | bigint | **PRIMARY KEY** - Unique decision ID | Application-assigned |
| `actor_id` | bigint | Actor who made the decision | References `lupo_actors.actor_id` |
| `channel_id` | bigint | Channel context | References `lupo_channels.channel_id` |
| `project_id` | bigint | Project context | References `lupo_projects.project_id` |
| `session_id` | bigint | Session context | References `lupo_sessions.session_id` |

### Hierarchical Fields

| Column | Type | Description | Notes |
|--------|------|-------------|--------|
| `root_decision_id` | bigint | Root decision in hierarchy | NULL for top-level decisions |
| `parent_decision_id` | bigint | Parent decision | NULL for top-level decisions |
| `depth` | int | Depth in decision tree | 0 for root decisions |

### Decision Fields

| Column | Type | Description | Notes |
|--------|------|-------------|--------|
| `decision_type` | varchar(50) | Type of decision | e.g., 'binary', 'multiclass', 'regression' |
| `decision_status` | varchar(32) | Decision status | 'pending', 'made', 'abandoned', 'pruned' |
| `decision_key` | varchar(255) | Decision identifier | Human-readable key |

### Probability Fields

| Column | Type | Description | Notes |
|--------|------|-------------|--------|
| `probability` | decimal(4,3) | Decision probability | 0.000 to 1.000 |
| `probability_lower` | decimal(4,3) | Lower confidence bound | Bayesian lower bound |
| `probability_upper` | decimal(4,3) | Upper confidence bound | Bayesian upper bound |
| `probability_model` | varchar(64) | Probability model used | e.g., 'bayesian', 'frequentist' |

### System Fields

| Column | Type | Description | Notes |
|--------|------|-------------|--------|
| `state_snapshot_id` | bigint | System state snapshot | References state at decision time |
| `federation_node_id` | bigint | Federation node | Default 1 (local) |
| `origin_decision_id` | bigint | Original decision ID | For federation synchronization |

### Timestamp Fields

| Column | Type | Description | Notes |
|--------|------|-------------|--------|
| `created_ymdhis` | bigint | Creation timestamp | YYYYMMDDHHIISS format |
| `created_by_actor_id` | bigint | Actor who created decision | References `lupo_actors.actor_id` |
| `updated_ymdhis` | bigint | Last update timestamp | NULL |
| `abandoned_ymdhis` | bigint | Abandonment timestamp | NULL |
| `pruned_ymdhis` | bigint | Pruning timestamp | NULL |
| `deleted_ymdhis` | bigint | Deletion timestamp | NULL |

### Status Fields

| Column | Type | Description | Default |
|--------|------|-------------|--------|
| `is_deleted` | tinyint | Decision is deleted | 0 |

## Indexes

### Primary Index
- `PRIMARY KEY (decision_id)` - Unique decision identifier

### Performance Indexes
- `lupo_decisions_idx_actor_time (actor_id, created_ymdhis)` - Actor decisions over time
- `lupo_decisions_idx_session_time (session_id, created_ymdhis)` - Session decisions over time
- `lupo_decisions_idx_root_depth (root_decision_id, depth)` - Hierarchical navigation
- `lupo_decisions_idx_parent (parent_decision_id)` - Find child decisions
- `lupo_decisions_idx_status (decision_status)` - Filter by status
- `lupo_decisions_idx_probability (probability)` - Find by probability
- `lupo_decisions_idx_federation (federation_node_id)` - Federation node filtering

### Context Indexes
- `lupo_decisions_idx_channel_time (channel_id, created_ymdhis)` - Channel decisions over time
- `lupo_decisions_idx_project_time (project_id, created_ymdhis)` - Project decisions over time
- `lupo_decisions_idx_channel_project_time (channel_id, project_id, created_ymdhis)` - Combined context

## Key Relationships

### Many-to-One Relationships
- **Actor**: `lupo_decisions.actor_id` â†’ `lupo_actors.actor_id`
- **Channel**: `lupo_decisions.channel_id` â†’ `lupo_channels.channel_id`
- **Project**: `lupo_decisions.project_id` â†’ `lupo_projects.project_id`
- **Session**: `lupo_decisions.session_id` â†’ `lupo_sessions.session_id`
- **Creator**: `lupo_decisions.created_by_actor_id` â†’ `lupo_actors.actor_id`

### Hierarchical Relationships
- **Root Decision**: `lupo_decisions.root_decision_id` â†’ `lupo_decisions.decision_id`
- **Parent Decision**: `lupo_decisions.parent_decision_id` â†’ `lupo_decisions.decision_id`
- **Child Decisions**: Reverse relationship for decision trees

### Evidence Relationships
- **Decision Evidence**: `lupo_decision_evidence.decision_id` â†’ `lupo_decisions.decision_id`

## Usage Patterns

### Decision Creation
```php
// Create a root decision
$decision = [
    'decision_id' => generateId(),
    'actor_id' => 102,
    'channel_id' => 42,
    'project_id' => 1,
    'session_id' => 12345,
    'decision_type' => 'binary',
    'decision_status' => 'pending',
    'decision_key' => 'deploy_to_production',
    'probability' => 0.750,
    'probability_lower' => 0.650,
    'probability_upper' => 0.850,
    'probability_model' => 'bayesian',
    'created_ymdhis' => 20260317173000,
    'created_by_actor_id' => 102
];
```

### Hierarchical Decision
```php
// Create child decision
$childDecision = [
    'decision_id' => generateId(),
    'actor_id' => 102,
    'channel_id' => 42,
    'project_id' => 1,
    'session_id' => 12345,
    'root_decision_id' => $rootDecisionId,
    'parent_decision_id' => $rootDecisionId,
    'depth' => 1,
    'decision_type' => 'binary',
    'decision_status' => 'pending',
    'decision_key' => 'test_before_deploy',
    'probability' => 0.900,
    'created_ymdhis' => 20260317173000,
    'created_by_actor_id' => 102
];
```

### Decision Retrieval
```php
// Get actor decisions
$decisions = DecisionService::getActorDecisions($actorId, $limit = 50);

// Get decision tree
$tree = DecisionService::getDecisionTree($rootDecisionId);

// Get decisions by probability
$highProbability = DecisionService::getDecisionsByProbability(0.8, 1.0);
```

## Decision Types

### Binary Decisions
- **Type**: 'binary'
- **Outcomes**: Yes/No, True/False, 1/0
- **Use Cases**: Deployment decisions, approval decisions
- **Example**: "Deploy to production?"

### Multiclass Decisions
- **Type**: 'multiclass'
- **Outcomes**: Multiple discrete options
- **Use Cases**: Feature selection, technology choices
- **Example**: "Which database to use?"

### Regression Decisions
- **Type**: 'regression'
- **Outcomes**: Continuous values
- **Use Cases**: Resource allocation, timing decisions
- **Example**: "How many servers needed?"

## Decision Status Flow

### Status States
```
pending â†’ made â†’ completed
    â†“       â†“
abandoned â†’ pruned
```

### Status Descriptions
- **pending**: Decision being considered
- **made**: Decision has been made
- **abandoned**: Decision abandoned without resolution
- **pruned**: Decision removed from active consideration
- **completed**: Decision fully executed and archived

## Probability Models

### Bayesian Model
- **Calculation**: Posterior probability based on evidence
- **Confidence**: Lower and upper bounds represent confidence interval
- **Updating**: Probabilities updated as new evidence arrives

### Frequentist Model
- **Calculation**: Based on historical frequency
- **Confidence**: Standard statistical confidence intervals
- **Use Cases**: When large historical datasets available

### Hybrid Model
- **Calculation**: Combines Bayesian and frequentist approaches
- **Adaptation**: Model selection based on data availability
- **Use Cases**: Complex decision environments

## Hierarchical Decision Trees

### Tree Structure
```
Root Decision (depth=0)
+-- Child Decision 1 (depth=1)
|   +-- Grandchild Decision 1.1 (depth=2)
|   +-- Grandchild Decision 1.2 (depth=2)
+-- Child Decision 2 (depth=1)
    +-- Grandchild Decision 2.1 (depth=2)
```

### Tree Operations
```php
// Build decision tree
$tree = DecisionService::buildDecisionTree($rootDecisionId);

// Calculate tree probabilities
$treeProbabilities = DecisionService::calculateTreeProbabilities($rootDecisionId);

// Find optimal path
$optimalPath = DecisionService::findOptimalPath($rootDecisionId);

// Prune tree branches
DecisionService::pruneTree($rootDecisionId, $threshold);
```

## Session Context

### Session Tracking
- Decisions are grouped by session_id
- Sessions represent coherent decision-making periods
- Session context includes environmental factors

### Session Analytics
```php
// Get session decisions
$sessionDecisions = DecisionService::getSessionDecisions($sessionId);

// Calculate session success rate
$successRate = DecisionService::calculateSessionSuccessRate($sessionId);

// Analyze decision patterns
$patterns = DecisionService::analyzeSessionPatterns($sessionId);
```

## Performance Considerations

### High-Volume Operations
- Index on (actor_id, created_ymdhis) for actor decision history
- Use probability index for decision filtering
- Batch decision operations for efficiency
- Cache decision trees for repeated access

### Optimization Strategies
```php
// Batch decision creation
$decisions = [
    ['decision_key' => 'decision1', 'probability' => 0.8],
    ['decision_key' => 'decision2', 'probability' => 0.6]
];
DecisionService::batchCreateDecisions($decisions);

// Cache decision tree
$cacheKey = "decision_tree:{$rootDecisionId}";
$tree = CacheService::get($cacheKey);
if (!$tree) {
    $tree = DecisionService::getDecisionTree($rootDecisionId);
    CacheService::set($cacheKey, $tree, 300);
}
```

## Common Queries

### Actor Decision History
```sql
SELECT 
    decision_id,
    decision_key,
    decision_type,
    decision_status,
    probability,
    created_ymdhis
FROM lupo_decisions 
WHERE actor_id = 102 
  AND is_deleted = 0
ORDER BY created_ymdhis DESC
LIMIT 50;
```

### High Probability Decisions
```sql
SELECT 
    decision_id,
    actor_id,
    decision_key,
    probability,
    probability_lower,
    probability_upper
FROM lupo_decisions 
WHERE probability >= 0.8 
  AND decision_status = 'pending'
  AND is_deleted = 0
ORDER BY probability DESC;
```

### Decision Tree
```sql
SELECT 
    decision_id,
    decision_key,
    depth,
    probability,
    decision_status
FROM lupo_decisions 
WHERE root_decision_id = 12345 
  AND is_deleted = 0
ORDER BY depth, created_ymdhis;
```

### Channel Decision Analytics
```sql
SELECT 
    channel_id,
    COUNT(*) as decision_count,
    AVG(probability) as avg_probability,
    COUNT(CASE WHEN decision_status = 'made' THEN 1 END) as made_count
FROM lupo_decisions 
WHERE is_deleted = 0
GROUP BY channel_id
ORDER BY decision_count DESC;
```

## Integration Points

### Evidence System
- Links to `lupo_decision_evidence` for supporting evidence
- Evidence updates probability calculations
- Evidence weight affects confidence bounds

### Session System
- Decisions grouped by session for context
- Session analytics and pattern recognition
- Session-based decision recommendations

### Analytics System
- Decision pattern analysis
- Probability distribution analysis
- Decision outcome prediction

## Security Considerations

### Access Control
- Validate actor permissions before decision creation
- Restrict access to sensitive decision data
- Implement decision audit trails
- Protect decision confidentiality

### Data Integrity
- Validate probability ranges (0.000 to 1.000)
- Ensure hierarchical consistency
- Validate status transitions
- Prevent circular references in hierarchy

### Privacy Protection
- Anonymize sensitive decision data
- Implement decision access logging
- Respect actor privacy preferences
- Provide decision deletion capabilities

## Troubleshooting

### Common Issues
1. **Invalid Probability**: Check probability range validation
2. **Hierarchy Issues**: Validate parent-child relationships
3. **Session Context**: Verify session_id exists
4. **Circular References**: Check for circular parent references

### Debug Queries
```sql
-- Check for invalid probabilities
SELECT decision_id, probability 
FROM lupo_decisions 
WHERE probability < 0 OR probability > 1;

-- Find orphaned decisions
SELECT d.* 
FROM lupo_decisions d
LEFT JOIN lupo_actors a ON d.actor_id = a.actor_id
WHERE d.actor_id NOT IN (SELECT actor_id FROM lupo_actors) 
  AND d.is_deleted = 0;

-- Check hierarchy integrity
SELECT 
    decision_id,
    parent_decision_id,
    depth
FROM lupo_decisions 
WHERE depth != (
    SELECT COUNT(*) 
    FROM lupo_decisions 
    WHERE root_decision_id = d.root_decision_id 
      AND created_ymdhis <= d.created_ymdhis
) - 1;
```

## Migration Notes

### Version History
- **v4.0.77**: Initial Bayesian decision tracking system
- **v4.0.78**: Added hierarchical decision support
- **v4.0.79**: Enhanced probability models and confidence bounds
- **v4.0.80**: Current schema with comprehensive decision tracking

### Breaking Changes
- Added depth field for hierarchical tracking
- Enhanced probability fields with confidence bounds
- Improved session context tracking

## Best Practices

### Decision Design
- Use descriptive decision_key values
- Implement proper probability validation
- Maintain decision hierarchy consistency
- Use appropriate decision types

### Performance Optimization
- Cache decision trees for repeated access
- Batch decision operations when possible
- Use appropriate indexes for query patterns
- Monitor decision creation rates

### Analytics Practices
- Track decision outcomes for model improvement
- Analyze decision patterns for insights
- Use confidence bounds for risk assessment
- Implement decision recommendation systems

---

**Table Statistics**:
- **Records**: Variable based on decision volume
- **Size**: Medium - grows with decision activity
- **Growth Rate**: Medium - decisions made regularly
- **Criticality**: MEDIUM - Analytics and decision support

**Dependencies**:
- **Required By**: Decision analytics and evidence tracking
- **References**: `lupo_actors`, `lupo_channels`, `lupo_projects`, `lupo_sessions`
- **Integrations**: Evidence System, Session System, Analytics System

**Maintenance**:
- **Backup Priority**: MEDIUM
- **Archive Policy**: Soft delete with `is_deleted`
- **Cleanup**: Review abandoned decisions monthly
- **Monitoring**: Track decision patterns and accuracy

