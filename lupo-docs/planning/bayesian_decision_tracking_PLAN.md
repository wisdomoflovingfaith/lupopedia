---
lupopedia.init:
  lupopedia.version: "4.0.77"
  system_version: "4.0.77"
  schema_family: "planning"
  artifact_family: "system-planning"
  created_utc: "20260316"
  environment: "lupopedia-core"

lupopedia.metadata:
  file_path_from_root: "docs/planning/bayesian_decision_tracking_PLAN.md"
  artifact_type: "planning-prompt"
  artifact_kind: "architecture-specification"
  purpose: "Define canonical planning artifacts for Bayesian Decision Tracking System after LILITH review"
  status: "schema foundation shipped in 4.0.77; engine and integrations deferred"
  tags: ["bayesian", "decision-tracking", "planning", "doctrine-aligned", "4.0.77"]
  delegation_chain: "wolfie:captain:lilith:antigravity"
  security_scope: "system-internal"

lupopedia.routing:
  channel_id: 42
  actor_id: 103
  actor_name: "antigravity"
  recipient_actor_ids: [1000]
  recipient_actor_names: ["captain"]
  session_id: "L-LUPO-ANTIGRAVITY-PLANNING"
  session_name: "Bayesian Decision Tracking — Planning Phase"
  priority: "high"
  requires_approval_from: ["captain", "lilith"]
  next_status_on_approve: "approved-planning"
  next_location_on_approve: "docs/status/"

lupopedia.headers:
  lupopedia.version: "4.0.77"
  lupopedia.schema: "planning"
  system_version: "4.0.77"
  file_path_from_root: "docs/planning/bayesian_decision_tracking_PLAN.md"
  web_path: "[web_path](http://www.lupopedia.com/planning/bayesian_decision_tracking_PLAN)"
  last_modified_utc: "20260316"
  channel_id: 42
  actor_id: 103
  actor_name: "antigravity"
  delegation_chain: "antigravity:root"
  artifact_type: "planning"
  artifact_kind: "architecture"
  purpose: "Comprehensive planning document for Bayesian Decision Tracking; schema foundation shipped in 4.0.77"
  status: "foundation_shipped"
  tags: ["bayesian", "decisions", "planning", "4.0.77"]

lupopedia.session:
  session_id: "L-LUPO-ANTIGRAVITY-PLANNING"
  session_name: "L-LUPO-ANTIGRAVITY-PLANNING"
  actor_id: 103
  actor_name: "antigravity"
  channel_id: 42
  paired_actor_id: 1000

lupopedia.footer:
  last_verified: "20260316"
  last_verified_by: "antigravity"
  orchestrator: "antigravity"
  next_action:
    - "Review with Captain and LILITH for approval"
    - "Move to docs/status/ upon approval"
    - "Begin implementation after status change to active"
---
# file: Bayesian Decision Tracking System Plan — session: L-LUPO-ANTIGRAVITY-PLANNING — delegation: antigravity:root — web_path: http://www.lupopedia.com/planning/bayesian_decision_tracking_PLAN

# Bayesian Decision Tracking System - Comprehensive Plan

**Status:** Schema foundation shipped in 4.0.77 (required tables, channel/project scope); engine and integrations deferred  
**Version:** 4.0.77  
**Author:** Antigravity (Actor 103); schema scope and doc alignment: Cursor (102)  
**Reviewers:** Captain (Actor 1000), LILITH (Actor 2)

**4.0.77 scope:** Decision tables (`lupo_decisions`, `lupo_decision_edges`, `lupo_decision_influences`) are in `install_new_lupopedia.sql` with required `channel_id` and `project_id`. See BAYESIAN_DECISION_DOCTRINE.md and UPGRADE_POLICY_DOCTRINE.md. No Lupopedia→Lupopedia upgrade in 4.0.x; canonical schema path is install SQL only.

---

## 1. Executive Summary

### 1.1 Problem Statement

Lupopedia needs a robust system to track, analyze, and navigate probabilistic decision trees made by AI agents. Current systems lack the ability to:

- Track decision probability evolution over time
- Navigate decision branches based on Bayesian inference
- Maintain causal relationships between decisions
- Provide audit trails for agent reasoning

### 1.2 High-Level Approach

Implement a Bayesian Decision Tracking System that:

- Records every meaningful decision as a probabilistic node
- Maintains structural edges between decisions (parent/child, influences)
- Stores state snapshots for decision context
- Provides navigation APIs for probability-based traversal
- Integrates with existing dialog, session, and task systems

### 1.3 Key Design Decisions

1. **Doctrine-First Design**: All schema follows Lupopedia constitutional rules (no FKs, BIGINT UTC, registry-based IDs)
2. **Logical References**: All relationships use logical references (no database constraints)
3. **State Snapshots**: Minimal state context stored in lupo_metadata, not full system state
4. **Federation-Aware**: All tables include federation_node_id for multi-node scenarios
5. **Soft Delete**: Uses is_deleted pattern, no hard deletes for lineage preservation

---

## 2. Schema Definition (Final)

### 2.1 lupo_decisions

Primary table storing all decision nodes in the system.

```sql
-- Implemented in v4.0.77 as a required table in
-- lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql
CREATE TABLE lupo_decisions (
  decision_id BIGINT NOT NULL,
  actor_id BIGINT NOT NULL,
  channel_id BIGINT,
  session_id BIGINT NOT NULL,
  root_decision_id BIGINT,
  parent_decision_id BIGINT,
  depth INT NOT NULL DEFAULT 0,
  decision_type VARCHAR(50) NOT NULL,
  decision_status VARCHAR(32) NOT NULL,
  decision_key VARCHAR(255),
  probability DECIMAL(4,3),
  probability_lower DECIMAL(4,3),
  probability_upper DECIMAL(4,3),
  probability_model VARCHAR(64),
  state_snapshot_id BIGINT,
  federation_node_id BIGINT NOT NULL DEFAULT 1,
  origin_decision_id BIGINT,
  created_ymdhis BIGINT NOT NULL,
  created_by_actor_id BIGINT NOT NULL,
  updated_ymdhis BIGINT,
  abandoned_ymdhis BIGINT,
  pruned_ymdhis BIGINT,
  is_deleted TINYINT NOT NULL DEFAULT 0,
  deleted_ymdhis BIGINT,
  PRIMARY KEY (decision_id)
);
```

**Indexes:**
```sql
PRIMARY KEY (decision_id)
INDEX idx_actor_time (actor_id, created_ymdhis)
INDEX idx_session_time (session_id, created_ymdhis)
INDEX idx_root_depth (root_decision_id, depth)
INDEX idx_parent (parent_decision_id)
INDEX idx_status (decision_status)
INDEX idx_probability (probability)
INDEX idx_federation (federation_node_id)
```

### 2.2 lupo_decision_edges

Structural navigation relationships between decisions.

```sql
CREATE TABLE lupo_decision_edges (
  source_decision_id BIGINT NOT NULL,                -- Logical ref to lupo_decisions
  target_decision_id BIGINT NOT NULL,                -- Logical ref to lupo_decisions
  edge_type VARCHAR(50) NOT NULL,                    -- leads_to, contradicts, influences
  probability DECIMAL(4,3),                          -- P(target|source)
  session_id BIGINT,                                 -- Logical ref to lupo_sessions
  federation_node_id BIGINT DEFAULT 1,               -- Federation node
  created_ymdhis BIGINT NOT NULL,                    -- BIGINT UTC
  created_by_actor_id BIGINT NOT NULL,               -- Who created the edge
  is_deleted TINYINT DEFAULT 0,                     -- Soft delete flag
  deleted_ymdhis BIGINT,                             -- When deleted
  
  PRIMARY KEY (source_decision_id, target_decision_id, edge_type)
);
```

**Indexes:**
```sql
PRIMARY KEY (source_decision_id, target_decision_id, edge_type)
INDEX idx_target (target_decision_id)
INDEX idx_probability (probability)
INDEX idx_session (session_id)
```

### 2.3 lupo_decision_influences

Causal and probabilistic influence relationships.

```sql
CREATE TABLE lupo_decision_influences (
  decision_id BIGINT NOT NULL,                       -- Logical ref to lupo_decisions
  influencing_decision_id BIGINT NOT NULL,          -- Logical ref to lupo_decisions
  influence_type VARCHAR(50) NOT NULL,               -- causal, informational, constraint
  weight DECIMAL(4,3),                               -- Strength of influence (0.000-1.000)
  session_id BIGINT,                                 -- Logical ref to lupo_sessions
  federation_node_id BIGINT DEFAULT 1,               -- Federation node
  created_ymdhis BIGINT NOT NULL,                    -- BIGINT UTC
  created_by_actor_id BIGINT NOT NULL,               -- Who recorded the influence
  is_deleted TINYINT DEFAULT 0,                     -- Soft delete flag
  deleted_ymdhis BIGINT,                             -- When deleted
  
  PRIMARY KEY (decision_id, influencing_decision_id, influence_type)
);
```

**Indexes:**
```sql
PRIMARY KEY (decision_id, influencing_decision_id, influence_type)
INDEX idx_influencing (influencing_decision_id)
INDEX idx_weight (weight)
```

### 2.4 lupo_metadata Extension

Extension to support decision state snapshots.

**New entity_type:** `'decision_state'`

**Required fields for decision_state:**
- `entity_key`: decision_id or composite identifier
- `metadata_json`: Minimal decision-relevant context
- `state_hash`: VARCHAR(64) for deduplication (optional)
- `created_ymdhis`: BIGINT UTC timestamp

**Example metadata_json:**
```json
{
  "agent_context": "responding to user query about database schema",
  "prompt_slice": "Should I normalize this table or keep it denormalized?",
  "key_variables": ["table_size", "query_frequency", "join_patterns"],
  "active_rules": ["database_normalization_doctrine", "performance_guidelines"],
  "content_references": ["table_schema_123", "query_log_456"]
}
```

---

## 3. State Snapshot Doctrine

### 3.1 What Belongs in a State Snapshot

**Required Elements:**
- Agent context (active goals, current focus)
- Prompt slice or decision input
- Key variables at time of decision
- Active rules that applied
- References to relevant content (by ID, not blob)

**Explicitly Excluded:**
- Full system state
- Large content blobs
- Irrelevant environment variables
- Complete agent memory

### 3.2 Snapshot Creation Process

1. **Capture Context**: Extract relevant agent state
2. **Hash Content**: Generate SHA-256 hash for deduplication
3. **Store Metadata**: Save in lupo_metadata as decision_state
4. **Link Decision**: Reference state_snapshot_id in lupo_decisions

### 3.3 Deduplication Strategy

- Use state_hash to identify identical decision contexts
- Reuse existing state_snapshot_id when hash matches
- Store only unique decision states

---

## 4. Integration Contracts

### 4.1 Dialog → Decisions

**Contract:** When agent sends message representing a choice, create decision FIRST

**Implementation:**
```php
// Before storing dialog message
$decisionId = $decisionEngine->recordDecision([
    'actor_id' => $actorId,
    'session_id' => $sessionId,
    'decision_type' => 'commit',
    'probability' => $calculatedProbability,
    'state_snapshot_id' => $capturedStateId
]);

// Store decision_id in message metadata
$messageData['metadata_json']['decision_id'] = $decisionId;
```

**Requirements:**
- Must be in same transaction or deterministic order
- Decision creation precedes message storage
- Message metadata includes decision_id reference

### 4.2 Sessions → Decisions

**Schema Extension:**
```sql
ALTER TABLE lupo_sessions ADD COLUMN current_decision_id BIGINT;
```

**Controller Operations Contract:**
All controller operations (UP/DOWN/LEFT/RIGHT/A/B) MUST:

1. **Read Context:**
```php
$currentDecision = $decisionEngine->getDecision($session->current_decision_id);
```

2. **Write New Decision:**
```php
$newDecisionId = $decisionEngine->recordDecision([
    'parent_decision_id' => $currentDecision->decision_id,
    'decision_type' => $operationType,
    // ... other fields
]);
```

3. **Update Session:**
```php
$session->current_decision_id = $newDecisionId;
```

### 4.3 Tasks → Decisions

**Contract:** Task completion MAY emit a decision, but not required

**Implementation:**
```php
if ($taskRepresentsMeaningfulStateChange) {
    $decisionId = $decisionEngine->recordDecision([
        'actor_id' => $task->assigned_actor_id,
        'decision_type' => 'complete',
        'decision_key' => "task_{$task->task_id}_completion",
        // ... other fields
    ]);
    
    // Store decision_id in task metadata
    $task->metadata_json['completion_decision_id'] = $decisionId;
}
```

**Doctrine:** "Meaningful state transitions MAY be recorded as decisions"

### 4.4 Rules → Decisions

**Rule Targets:**
```sql
INSERT INTO lupo_rule_targets (
    target_table, target_field, filter_expression, rule_id
) VALUES (
    'decisions', 'actor_id', 'actor_id = :actor_id', 123
);
```

**Allowed Filters:**
- actor_id (for agent-specific decisions)
- session_id (for session-scoped queries)
- decision_type (commit, speculate, promote, tag, undo)
- decision_status (active, abandoned, pruned, archived)
- probability ranges (e.g., probability > 0.8)

**Rule Engine Integration:**
- Rule engine can query decisions for context
- Should use materialized views for performance
- Avoid full table scans in rule evaluation

---

## 5. Federation Design

### 5.1 Federation Fields

All decision tables include:
- `federation_node_id`: Identifies the originating node
- `origin_decision_id`: Original ID when replicated across nodes

### 5.2 Replication Policy

**Primary Policy:** "Decisions follow session or actor; nodes MAY sync decision graphs"

**Scenarios:**
1. **Session-Based Sync**: Decisions from mobile sessions sync to primary node
2. **Actor-Based Sync**: Agent-specific decisions follow actor across nodes
3. **Selective Sync**: High-probability decisions sync for consensus

### 5.3 Conflict Resolution

**Priority Order:**
1. Higher probability values
2. Earlier timestamps (created_ymdhis)
3. Origin node priority (configured per federation)

---

## 6. Performance & Archiving

### 6.1 Growth Estimates

**Assumptions:**
- ~260k decisions/day (based on agent activity)
- ~7.7M decisions/month
- ~93M decisions/year

**Storage Requirements:**
- lupo_decisions: ~200 bytes/row = ~20GB/year
- lupo_decision_edges: ~50 bytes/row = ~5GB/year
- lupo_decision_influences: ~40 bytes/row = ~4GB/year
- **Total:** ~29GB/year active storage

### 6.2 Partition Strategy

**Primary Strategy:** Monthly partitioning by created_ymdhis

```sql
-- Example partition structure
lupo_decisions_202603
lupo_decisions_202604
lupo_decisions_202605
```

**Alternative Strategy:** Session_id ranges for hot-path optimization

### 6.3 Archival Policy

**Archive Criteria:**
- decision_status IN ('abandoned', 'pruned')
- Age > 90 days (configurable)
- No active references in current sessions

**Archive Process:**
```sql
-- Move to archive tables
INSERT INTO lupo_decisions_archive SELECT * FROM lupo_decisions 
WHERE decision_status IN ('abandoned', 'pruned') 
AND created_ymdhis < (CURRENT_TIMESTAMP - INTERVAL 90 DAY);

-- Soft delete from active
UPDATE lupo_decisions SET is_deleted = 1, deleted_ymdhis = :now
WHERE decision_id IN (SELECT decision_id FROM lupo_decisions_archive);
```

**Query Strategy:**
- Active queries target main tables only
- Historical queries UNION with archive tables
- Archive tables read-only, no further modifications

### 6.4 Materialized Views

**Timeline Summary:**
```sql
CREATE MATERIALIZED VIEW mv_decision_timeline AS
SELECT 
    DATE(FROM_UNIXTIME(created_ymdhis)) as decision_date,
    actor_id,
    decision_type,
    COUNT(*) as decision_count,
    AVG(probability) as avg_probability
FROM lupo_decisions 
WHERE is_deleted = 0
GROUP BY decision_date, actor_id, decision_type;
```

**Agent Comparison:**
```sql
CREATE MATERIALIZED VIEW mv_agent_comparison AS
SELECT 
    actor_id,
    COUNT(*) as total_decisions,
    AVG(probability) as avg_confidence,
    COUNT(CASE WHEN decision_type = 'commit' THEN 1 END) as commits,
    COUNT(CASE WHEN decision_type = 'abandon' THEN 1 END) as abandons
FROM lupo_decisions 
WHERE is_deleted = 0 AND created_ymdhis > :cutoff_date
GROUP BY actor_id;
```

---

## 7. Open Questions

### 7.1 Probability Model Standardization

**Question:** Should we standardize on specific probability models?

**Options:**
1. **Beta Distribution**: P(α,β) for binary decisions
2. **Heuristic Scores**: Simple 0-1 confidence scores
3. **LLM-Based**: Use model's internal probability outputs
4. **Hybrid**: Allow multiple models, store model type

**Recommendation:** Start with heuristic scores, evolve to beta distribution

### 7.2 State Snapshot Granularity

**Question:** How much context should be captured in state snapshots?

**Considerations:**
- Too little: Insufficient context for replay
- Too much: Storage bloat, performance impact
- Variable size: Complex deduplication logic

**Current Approach:** Minimal essential context only

### 7.3 Real-time vs Batch Processing

**Question:** Should decision tracking be real-time or batch processed?

**Factors:**
- Real-time: Immediate feedback, higher complexity
- Batch: Simpler, delayed insights
- Hybrid: Critical decisions real-time, analytics batch

**Recommendation:** Real-time for navigation decisions, batch for analytics

### 7.4 Cross-Node Consistency

**Question:** How to maintain decision consistency across federation nodes?

**Challenges:**
- Network partitions
- Conflicting decisions
- Ordering guarantees

**Approach:** Eventual consistency with conflict resolution rules

---

## 8. Implementation Dependencies

### 8.1 Prerequisites

- **Registry System**: ID allocation for decision_id ranges
- **Metadata Extensions**: Support for decision_state entity_type
- **Session Updates**: current_decision_id field addition

### 8.2 External Dependencies

- **PHP Extensions**: bcmath for precise decimal arithmetic
- **Database Functions**: UUID generation for state hashes
- **Monitoring**: Decision tracking metrics and alerts

### 8.3 Integration Points

- **Dialog System**: Message → Decision pipeline
- **Session Management**: Decision context tracking
- **Task System**: Optional decision emission
- **Rule Engine**: Decision-based rule evaluation

---

## 9. Success Criteria

### 9.1 Functional Requirements

- [ ] All agent decisions can be recorded and retrieved
- [ ] Decision trees support probability-based navigation
- [ ] State snapshots provide sufficient context for replay
- [ ] Integration with existing systems is seamless
- [ ] Performance meets growth estimates

### 9.2 Non-Functional Requirements

- [ ] Query latency < 100ms for hot paths
- [ ] Storage growth within projected estimates
- [ ] 99.9% uptime for decision tracking APIs
- [ ] Comprehensive audit trail for all decisions
- [ ] Federation support for multi-node deployments

### 9.3 Doctrine Compliance

- [ ] No foreign keys or database constraints
- [ ] All timestamps in BIGINT UTC format
- [ ] Registry-based ID allocation
- [ ] Soft delete patterns implemented
- [ ] Federation-aware design

---

## 10. Next Steps

1. **Review**: Captain and LILITH review this comprehensive plan
2. **Approval**: Move to approved-planning status
3. **Task Breakdown**: Create detailed TASKS.md with atomic implementation steps
4. **Resource Allocation**: Assign development phases to appropriate actors
5. **Implementation**: Begin Phase 1 (Schema Foundation) upon approval

---

**Document Status:** Ready for Review  
**Next Reviewers:** Captain (Actor 1000), LILITH (Actor 2)  
**Expected Completion:** Q2 2026 (subject to approval and resource allocation)
