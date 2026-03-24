---
lupopedia.headers:
  lupopedia.schema: table
  file_path_from_root: lupo-docs/database/lupopedia/tables/active/lupo_decision_evidence.md
  channel_id: 42
  actor_id: 102
  actor_name: hermes
  faucet_name: cascade
  artifact_type: table_documentation
  artifact_kind: database_schema
  purpose: Complete documentation for lupo_decision_evidence table - decision evidence
    tracking
  tags:
  - table_documentation
  - decisions
  - evidence
  - bayesian
  - 4.0.80
  created_ymdhis: 20260317214000
  when_updated: '20260324174654'
lupopedia:
  footer:
    last_verified: '20260324174654'
    last_verified_by: cursor
    last_verified_by_actor_id: 102
    orchestrator: cursor:root
---

# lupo_decision_evidence - Decision Evidence Tracking

**Table Type**: Evidence Registry  
**Domain**: Decision System  
**Criticality**: MEDIUM - Evidence for decisions and audit trails  
**Primary Key**: `decision_evidence_id`

## Overview

The `lupo_decision_evidence` table tracks evidence supporting decisions in the Bayesian decision system. It stores supporting data, likelihood assessments, and confidence ratings that inform probability calculations and decision justification.

### Key Characteristics
- **Evidence Repository**: Central storage for decision evidence
- **Bayesian Support**: Likelihood and confidence for probability updates
- **Audit Trail**: Complete evidence chain for decision transparency
- **Multi-Source**: Various evidence types and sources

## Table Structure

### Core Identity Fields

| Column | Type | Description | Notes |
|--------|------|-------------|--------|
| `decision_evidence_id` | bigint | **PRIMARY KEY** - Unique evidence ID | Application-assigned |
| `decision_id` | bigint | Associated decision | References `lupo_decisions.decision_id` |
| `channel_id` | bigint | Channel context | References `lupo_channels.channel_id` |
| `project_id` | bigint | Project context | Default 0 (no project) |

### Evidence Fields

| Column | Type | Description | Notes |
|--------|------|-------------|--------|
| `evidence_type` | varchar(64) | Type of evidence | e.g., 'data', 'expert', 'historical', 'test' |
| `evidence_source` | varchar(255) | Source of evidence | System, user, or external source |
| `evidence_value` | text | Evidence content | Flexible text storage |

### Assessment Fields

| Column | Type | Description | Default |
|--------|------|-------------|--------|
| `likelihood` | decimal(10,6) | Likelihood assessment | Bayesian likelihood value |
| `confidence` | decimal(10,6) | Confidence rating | Evidence confidence level |

### System Fields

| Column | Type | Description | Default |
|--------|------|-------------|--------|
| `federation_node_id` | bigint | Federation node | 1 (local) |
| `status` | varchar(32) | Evidence status | 'active' |

### Timestamp Fields

| Column | Type | Description | Default |
|--------|------|-------------|--------|
| `created_ymdhis` | bigint | Creation timestamp | 0 |
| `updated_ymdhis` | bigint | Last update timestamp | Current time |
| `deleted_ymdhis` | bigint | Deletion timestamp | NULL |

### Status Fields

| Column | Type | Description | Default |
|--------|------|-------------|--------|
| `is_deleted` | tinyint | Evidence is deleted | 0 |

## Indexes

### Primary Index
- `PRIMARY KEY (decision_evidence_id)` - Unique evidence identifier

### Performance Indexes
- `lupo_decision_evidence_idx_decision (decision_id)` - Find evidence by decision
- `lupo_decision_evidence_idx_channel (channel_id)` - Find evidence by channel
- `lupo_decision_evidence_idx_status (status)` - Filter by status
- `lupo_decision_evidence_idx_is_deleted (is_deleted)` - Filter deleted evidence

## Key Relationships

### Many-to-One Relationships
- **Decision**: `lupo_decision_evidence.decision_id` → `lupo_decisions.decision_id`
- **Channel**: `lupo_decision_evidence.channel_id` → `lupo_channels.channel_id`
- **Project**: `lupo_decision_evidence.project_id` → `lupo_projects.project_id`

## Usage Patterns

### Evidence Creation
```php
// Add evidence to decision
$evidence = [
    'decision_evidence_id' => generateId(),
    'decision_id' => 12345,
    'channel_id' => 42,
    'project_id' => 1,
    'evidence_type' => 'data',
    'evidence_source' => 'system_metrics',
    'evidence_value' => 'CPU usage at 85%, memory at 70%',
    'likelihood' => 0.750000,
    'confidence' => 0.900000,
    'status' => 'active',
    'created_ymdhis' => 20260317174000,
    'updated_ymdhis' => 20260317174000
];
```

### Evidence Retrieval
```php
// Get decision evidence
$evidence = EvidenceService::getDecisionEvidence($decisionId);

// Get evidence by type
$dataEvidence = EvidenceService::getEvidenceByType($decisionId, 'data');

// Get high-confidence evidence
$highConfidence = EvidenceService::getEvidenceByConfidence($decisionId, 0.8);
```

### Evidence Updates
```php
// Update evidence assessment
EvidenceService::updateEvidenceAssessment($evidenceId, [
    'likelihood' => 0.800000,
    'confidence' => 0.950000,
    'updated_ymdhis' => 20260317175000
]);

// Deactivate evidence
EvidenceService::deactivateEvidence($evidenceId);
```

## Evidence Types

### Data Evidence
- **Type**: 'data'
- **Source**: System metrics, logs, measurements
- **Use Cases**: Performance data, usage statistics
- **Example**: "Server response time: 250ms"

### Expert Evidence
- **Type**: 'expert'
- **Source**: Subject matter experts, stakeholders
- **Use Cases**: Professional opinions, expert judgment
- **Example**: "Security expert recommends additional testing"

### Historical Evidence
- **Type**: 'historical'
- **Source**: Past decisions, historical data
- **Use Cases**: Pattern recognition, trend analysis
- **Example**: "Previous deployment had 95% success rate"

### Test Evidence
- **Type**: 'test'
- **Source**: Test results, experiments
- **Use Cases**: A/B testing, validation results
- **Example**: "Load test passed with 1000 concurrent users"

### User Evidence
- **Type**: 'user'
- **Source**: User feedback, surveys
- **Use Cases**: User preferences, satisfaction data
- **Example**: "User survey shows 85% satisfaction"

## Evidence Sources

### System Sources
- **system_metrics**: Performance metrics, system logs
- **automated_tests**: Automated test results
- **monitoring**: System monitoring data
- **analytics**: Analytics platform data

### Human Sources
- **expert_opinion**: Expert assessments
- **user_feedback**: User input and feedback
- **stakeholder_input**: Stakeholder perspectives
- **team_consensus**: Team agreement

### External Sources
- **third_party_data**: External data sources
- **industry_benchmarks**: Industry standards
- **research_papers**: Academic research
- **market_data**: Market research data

## Likelihood Assessment

### Bayesian Likelihood
- **Range**: 0.000000 to 1.000000
- **Interpretation**: Probability of evidence given hypothesis
- **Usage**: Updates posterior probability in Bayesian calculations

### Likelihood Examples
```php
// Strong supporting evidence
$likelihood = 0.900000; // Very likely given hypothesis

// Weak supporting evidence
$likelihood = 0.600000; // Somewhat likely given hypothesis

// Contradictory evidence
$likelihood = 0.200000; // Unlikely given hypothesis
```

## Confidence Rating

### Confidence Scale
- **0.000000 - 0.300000**: Low confidence
- **0.300000 - 0.700000**: Medium confidence
- **0.700000 - 1.000000**: High confidence

### Confidence Factors
- **Source Reliability**: Trustworthiness of evidence source
- **Data Quality**: Accuracy and completeness of data
- **Sample Size**: Statistical significance
- **Recency**: How recent is the evidence

## Evidence Status

### Status States
- **active**: Currently in use for decision calculations
- **deprecated**: No longer used but kept for audit
- **invalid**: Found to be incorrect or unreliable
- **pending**: Awaiting validation or review

### Status Transitions
```
pending → active → deprecated
    ↓         ↓
  invalid → invalid
```

## Performance Considerations

### High-Volume Operations
- Index on decision_id for fast evidence lookup
- Batch evidence operations for efficiency
- Use evidence caching for repeated access
- Implement evidence archiving for old evidence

### Optimization Strategies
```php
// Batch evidence creation
$evidenceBatch = [
    ['evidence_type' => 'data', 'evidence_value' => 'metric1'],
    ['evidence_type' => 'data', 'evidence_value' => 'metric2']
];
EvidenceService::batchCreateEvidence($decisionId, $evidenceBatch);

// Cache evidence summary
$cacheKey = "evidence_summary:{$decisionId}";
$summary = CacheService::get($cacheKey);
if (!$summary) {
    $summary = EvidenceService::getEvidenceSummary($decisionId);
    CacheService::set($cacheKey, $summary, 300);
}
```

## Common Queries

### Decision Evidence Summary
```sql
SELECT 
    evidence_type,
    COUNT(*) as evidence_count,
    AVG(likelihood) as avg_likelihood,
    AVG(confidence) as avg_confidence
FROM lupo_decision_evidence 
WHERE decision_id = 12345 
  AND is_deleted = 0
GROUP BY evidence_type
ORDER BY evidence_count DESC;
```

### High-Confidence Evidence
```sql
SELECT 
    decision_evidence_id,
    evidence_source,
    evidence_value,
    likelihood,
    confidence
FROM lupo_decision_evidence 
WHERE decision_id = 12345 
  AND confidence >= 0.8
  AND status = 'active'
  AND is_deleted = 0
ORDER BY confidence DESC, likelihood DESC;
```

### Evidence by Type
```sql
SELECT 
    evidence_type,
    evidence_source,
    evidence_value,
    likelihood,
    confidence
FROM lupo_decision_evidence 
WHERE decision_id = 12345 
  AND evidence_type = 'data'
  AND is_deleted = 0
ORDER BY created_ymdhis DESC;
```

### Channel Evidence Analytics
```sql
SELECT 
    channel_id,
    COUNT(*) as total_evidence,
    AVG(likelihood) as avg_likelihood,
    AVG(confidence) as avg_confidence,
    COUNT(CASE WHEN status = 'active' THEN 1 END) as active_count
FROM lupo_decision_evidence 
WHERE is_deleted = 0
GROUP BY channel_id
ORDER BY total_evidence DESC;
```

## Integration Points

### Decision System
- Evidence updates decision probabilities
- Evidence weight affects confidence bounds
- Evidence aggregation for decision support

### Analytics System
- Evidence pattern analysis
- Source reliability tracking
- Evidence quality assessment

### Audit System
- Complete evidence chain for decisions
- Evidence change tracking
- Decision justification documentation

## Security Considerations

### Access Control
- Validate decision access before evidence retrieval
- Restrict evidence modification to authorized users
- Implement evidence audit logging
- Protect sensitive evidence data

### Data Integrity
- Validate likelihood ranges (0.000000 to 1.000000)
- Ensure evidence source validation
- Maintain evidence chain integrity
- Prevent evidence tampering

### Privacy Protection
- Anonymize sensitive evidence sources
- Implement evidence access controls
- Respect data privacy regulations
- Provide evidence deletion capabilities

## Troubleshooting

### Common Issues
1. **Invalid Likelihood**: Check likelihood range validation
2. **Missing Decision**: Verify decision_id exists
3. **Evidence Quality**: Validate evidence source and value
4. **Status Issues**: Check status transition validity

### Debug Queries
```sql
-- Check for invalid likelihood values
SELECT decision_evidence_id, likelihood 
FROM lupo_decision_evidence 
WHERE likelihood < 0 OR likelihood > 1;

-- Find orphaned evidence
SELECT e.* 
FROM lupo_decision_evidence e
LEFT JOIN lupo_decisions d ON e.decision_id = d.decision_id
WHERE e.decision_id NOT IN (SELECT decision_id FROM lupo_decisions) 
  AND e.is_deleted = 0;

-- Check evidence quality
SELECT 
    evidence_type,
    COUNT(*) as count,
    AVG(confidence) as avg_confidence
FROM lupo_decision_evidence 
WHERE confidence < 0.5 
  AND is_deleted = 0
GROUP BY evidence_type;
```

## Migration Notes

### Version History
- **v4.0.77**: Initial evidence tracking system
- **v4.0.78**: Added confidence ratings and evidence types
- **v4.0.79**: Enhanced likelihood assessment precision
- **v4.0.80**: Current schema with comprehensive evidence tracking

### Breaking Changes
- Enhanced likelihood precision to decimal(10,6)
- Added confidence rating field
- Improved evidence type classification

## Best Practices

### Evidence Collection
- Use descriptive evidence_type values
- Validate evidence sources for reliability
- Maintain evidence quality standards
- Document evidence chain of custody

### Assessment Practices
- Use consistent likelihood assessment methods
- Apply appropriate confidence ratings
- Document assessment rationale
- Regular evidence quality reviews

### Performance Optimization
- Cache evidence summaries for repeated access
- Batch evidence operations when possible
- Use appropriate indexes for query patterns
- Monitor evidence creation and access patterns

---

**Table Statistics**:
- **Records**: Variable based on decision activity
- **Size**: Medium - grows with evidence collection
- **Growth Rate**: Medium - evidence added regularly
- **Criticality**: MEDIUM - Decision support and audit

**Dependencies**:
- **Required By**: Decision probability calculations
- **References**: `lupo_decisions`, `lupo_channels`, `lupo_projects`
- **Integrations**: Decision System, Analytics System, Audit System

**Maintenance**:
- **Backup Priority**: MEDIUM
- **Archive Policy**: Soft delete with `is_deleted`
- **Cleanup**: Review inactive evidence quarterly
- **Monitoring**: Track evidence quality and patterns
