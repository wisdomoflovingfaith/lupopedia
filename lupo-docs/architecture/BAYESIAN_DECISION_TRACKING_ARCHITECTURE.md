---
lupopedia.headers:
  lupopedia.version: "4.0.79"
  system_version: "4.0.79"
  file_path_from_root: "lupo-docs/architecture/BAYESIAN_DECISION_TRACKING_ARCHITECTURE.md"
  channel_id: 42
  actor_id: 102
  artifact_type: "architecture"
  artifact_kind: "design"
---
# Bayesian Decision Tracking Architecture (4.0.79)

## 1. Purpose

The Bayesian Decision Tracking system provides a probabilistic framework for making and tracking decisions across the Lupopedia ecosystem. It enables evidence-based decision making, probability calculations using Bayes' theorem, and influence management between related decisions. This system supports AI reasoning, human decision support, and audit trails for organizational decision processes.

## 2. Tables involved

### Core Decision Tables

- **`lupo_decisions`** — Core decision record with probability, state, and metadata
  - Key fields: `decision_id`, `channel_id`, `project_id`, `actor_id`, `decision_type`, `probability`, `decision_status`
  - Supports: decision lifecycle, probability tracking, state management

- **`lupo_decision_edges`** — Graph edges between decisions (parent/child/related)
  - Key fields: `source_decision_id`, `target_decision_id`, `edge_type`, `probability`
  - Supports: decision hierarchies, relationship tracking, graph navigation

- **`lupo_decision_influences`** — Weighted influences between decisions
  - Key fields: `decision_id`, `influencing_decision_id`, `influence_type`, `influence_weight`
  - Supports: cross-decision influence, weighted averaging, dependency tracking

- **`lupo_decision_evidence`** — Evidence items tied to decisions (4.0.79)
  - Key fields: `decision_evidence_id`, `decision_id`, `evidence_type`, `evidence_source`, `likelihood`, `confidence`
  - Supports: evidence tracking, likelihood calculations, confidence scoring

## 3. Service and API

### BayesianDecisionService.php

Core service implementing Bayesian logic and decision management:

**Core Bayesian Methods:**
- `assertProbability($value, $fieldName)` — Validates probability values (0.0 to 1.0)
- `calculatePosterior($prior, $likelihood, $evidenceProbability)` — Applies Bayes' theorem
- `normalizeProbability($p)` — Clamps probabilities to valid range
- `combineEvidenceSequential($prior, $likelihoods, $evidenceProbability)` — Sequential evidence updates
- `updateDecisionProbabilityFromEvidence($decisionId, $likelihoods, $evidenceProbability)` — Updates decision probability

**Evidence Management:**
- `recordEvidence($decisionId, $channelId, $projectId, $type, $source, $value, $likelihood, $confidence)` — Records evidence
- `getEvidenceForDecision($decisionId)` — Retrieves evidence for a decision

**Influence Processing:**
- `applyInfluences($basePosterior, array $influences)` — Applies weighted influences
- `getInfluencesForDecision($decisionId)` — Retrieves decision influences

**Decision Traversal:**
- `getParentDecision($decisionId)` — Gets parent decision
- `getChildDecisions($decisionId)` — Gets child decisions
- `getRootDecision($decisionId)` — Finds root decision in hierarchy
- `getDecisionDepth($decisionId)` — Calculates decision depth

**State Management:**
- `setStatePending($decisionId)` — Sets decision to pending state
- `setStateEvaluating($decisionId)` — Sets decision to evaluating state
- `confirmDecision($decisionId)` — Confirms decision
- `rejectDecision($decisionId)` — Rejects decision

### decisions-api.php

REST API controller for decision operations:

**Endpoints:**
- `POST /api/decisions` — Create new decision
- `GET /api/decisions/{id}` — Fetch decision details
- `POST /api/decisions/{id}/evidence` — Record evidence
- `GET /api/decisions/{id}/evidence` — List evidence

**Security:**
- Session-based authentication
- Channel membership validation
- No client-supplied actor_id

## 4. Flow

### Decision Lifecycle

1. **Create Decision**
   - Set initial prior probability (default: 0.5)
   - Set state to `pending` or `evaluating`
   - Record decision metadata

2. **Record Evidence**
   - Add evidence items via `recordEvidence()`
   - Each evidence has `likelihood` and `confidence`
   - Evidence stored in `lupo_decision_evidence`

3. **Compute Posterior**
   - Use `updateDecisionProbabilityFromEvidence()`
   - Apply sequential evidence updates
   - Calculate final posterior probability

4. **Apply Influences**
   - Retrieve related decisions via `getInfluencesForDecision()`
   - Apply weighted blending via `applyInfluences()`
   - Final probability = 0.5 × posterior + 0.5 × influenced

5. **Update State**
   - Transition through states: `pending` → `evaluating` → `confirmed`/`rejected`
   - Record state changes with timestamps

### State Transitions

```
pending → evaluating → confirmed/rejected
    ↓              ↓
   evidence      decision
   collection     finalization
```

## 5. Worked Example

### Scenario: Feature Implementation Decision

**Initial State:**
- Decision: "Implement user authentication feature"
- Prior probability: 0.5 (neutral)
- State: `pending`

**Evidence Collection:**
1. User research shows 80% of similar features succeed
   - Evidence: `likelihood = 0.8`, `confidence = 0.9`
2. Security audit passes with 90% confidence
   - Evidence: `likelihood = 0.9`, `confidence = 0.8`
3. Technical feasibility analysis shows 60% chance
   - Evidence: `likelihood = 0.6`, `confidence = 0.7`

**Sequential Update:**
- Start: P = 0.5
- After evidence 1: P₁ = (0.5 × 0.8) / 0.7 = 0.571
- After evidence 2: P₂ = (0.571 × 0.9) / 0.8 = 0.642
- After evidence 3: P₃ = (0.642 × 0.6) / 0.8 = 0.482

**Influence Application:**
- Related decision "Implement user profile system" has probability 0.7
- Influence weight: 0.6
- Influenced probability = 0.7 × 0.6 = 0.42
- Final blend: 0.5 × 0.482 + 0.5 × 0.42 = 0.241 + 0.21 = 0.451

**Final Result:**
- Posterior probability: 0.482
- Influenced probability: 0.451
- Final probability: 0.451
- State transition: `evaluating` → `confirmed`

## 6. Current Limits / Future Work

### Current Limitations

- **One-level influence processing** — Only direct influences considered
- **Sequential evidence assumption** — Evidence independence assumed
- **Simple weighted blending** — 50/50 base/influence split
- **No learning/adaptation** — Static probability calculations

### Future Enhancements

- **Multi-level influence propagation** — Cascading influences through decision graph
- **Evidence correlation modeling** — Handle dependent evidence
- **Adaptive probability weighting** — Learn from historical accuracy
- **Decision pattern recognition** — Identify common decision patterns
- **Integration with workflow systems** — Connect to task management and project planning

## 7. Integration Points

### System Integration

- **Task Management** — Link decisions to tasks for execution tracking
- **Dialog System** — Associate decisions with conversation threads
- **Analytics** — Decision pattern analysis and optimization
- **User Interface** — Decision dashboards and approval workflows

### Data Flow

```
User Interface → decisions-api.php → BayesianDecisionService → Database
                        ↓
Evidence Sources → evidence recording → probability updates → state changes
                        ↓
Analytics ← decision data ← influence processing ← traversal helpers
```

This architecture provides a solid foundation for evidence-based decision making while maintaining flexibility for future enhancements and integrations.
