---
lupopedia.headers:
  lupopedia.version: "4.0.79"
  lupopedia.schema: "documentation"
  system_version: "4.0.79"
  file_path_from_root: "lupo-docs/versions/4.0.79/TODO.md"
  web_path: "[TODO](http://www.lupopedia.com/versions/4.0.79/TODO)"
  last_modified_utc: "20260317"
  channel_id: 42
  actor_id: 1
  artifact_type: "todo"
  artifact_kind: "version_todo"
  purpose: "Concrete task list for 4.0.79 (Top 50 table docs + Bayesian Decision Tracking implementation)"
  tags: ["todo", "4.0.79", "table_documentation", "top_50", "bayesian"]

lupopedia.footer:
  version: "4.0.79"
  last_verified: "20260317"
  last_verified_by: "wolfie"
  next_action:
    - "Complete Top 50 table documentation AND implement Bayesian Decision Tracking core functionality"
---
# file: Version 4.0.79 TODO — web_path: http://www.lupopedia.com/versions/4.0.79/TODO

# Version 4.0.79 — TODO List

## Status

- **State:** Closed
- **Resolution:** Remaining tasks moved to v4.0.80
- **Completed:** Core Bayesian Decision Tracking implementation, partial Top 50 table documentation
- **Carried forward:** Auth table docs, Analytics table docs, Top 50 expansion, Bayesian enhancements

---

## A. Remaining Top 50 — Auth

1. **lupo_auth_providers.md** — [ ] Update to 4.0.79 LUPOPEDIA_HEADERS; Table Overview; Where This Table Is Used; columns from install SQL; namespace auth.
2. **lupo_auth_audit_log.md** — [ ] Same pattern (auth).
3. **lupo_banned_actors.md** — [ ] Same pattern (auth).
4. **lupo_bans_log.md** — [ ] Same pattern (auth).

---

## C. Remaining Top 50 — Analytics

9. **lupo_unified_log.md** — [ ] Update to 4.0.79 LUPOPEDIA_HEADERS; Table Overview; Where This Table Is Used; columns; namespace analytics.
10. **lupo_analytics_events.md** — [ ] Same (analytics).

---

## D. Remaining Top 50 — Core/Agent (38–50)

11. **lupo_actor_capabilities.md** — [x] Update to 4.0.79 LUPOPEDIA_HEADERS; Table Overview; Where This Table Is Used; columns; namespace core.
12. **lupo_actor_events.md** — [x] Deferred (table not in install SQL).
13. **lupo_channel_state.md** — [x] Update to 4.0.79 LUPOPEDIA_HEADERS; Table Overview; Where This Table Is Used; columns; namespace channels.
14. **lupo_dialog_threads.md** — [x] Update to 4.0.79 LUPOPEDIA_HEADERS; Table Overview; Where This Table Is Used; columns; namespace channels.
15. **lupo_help_topics.md** — [x] Update to 4.0.79 LUPOPEDIA_HEADERS; Table Overview; Where This Table Is Used; columns; namespace content.
16. **lupo_permissions.md** — [x] Update to 4.0.79 LUPOPEDIA_HEADERS; Table Overview; Where This Table Is Used; columns; namespace core.
17. **lupo_search_index.md** — [x] Update to 4.0.79 LUPOPEDIA_HEADERS; Table Overview; Where This Table Is Used; columns; namespace core.
18. **lupo_semantic_index.md** — [x] Update to 4.0.79 LUPOPEDIA_HEADERS; Table Overview; Where This Table Is Used; columns; namespace core.
19. **lupo_tasks.md** — [x] Update to 4.0.79 LUPOPEDIA_HEADERS; Table Overview; Where This Table Is Used; columns; namespace core.
20. **lupo_truth_knowledge.md** — [x] Update to 4.0.79 LUPOPEDIA_HEADERS; Table Overview; Where This Table Is Used; columns; namespace truth.
21. **Additional Top 50 (21–50)** — [ ] Select from install SQL tables by system criticality to reach 50 total.

---

## E. Header & Namespace Cleanup

22. **Header version normalization** — [ ] Ensure all Top 50 docs have `lupopedia.version: "4.0.79"` and `system_version: "4.0.79"`.
23. **Namespace validation** — [ ] Verify all table docs are in correct namespace (auth, content, analytics, core).
24. **TABLE_INDEX.md completion** — [ ] Add missing Top 50 table entries.
25. **Duplicate/FLARE cleanup** — [ ] Remove any remaining FLARE references.

---

## F. Bayesian Decision Tracking Implementation (NEW)

### Ground Truth: Schema + Doctrine

**Tables (from install SQL + docs):**
- `lupo_decisions` – core decision records
- `lupo_decision_edges` – graph edges between decisions (parent/child/related)
- `lupo_decision_influences` – influence weights between decisions or between evidence and decisions

**Doctrine / Planning:**
- `lupo-docs/doctrine/BAYESIAN_DECISION_DOCTRINE.md` – defines what "Bayesian decision" means in Lupopedia
- `docs/planning/bayesian_decision_tracking_PLAN.md + ..._TASKS.md`
- `lupo-docs/status/Bayesian_Decision_Tracking_wolfie.md (+ lilith_review_bayesian.md if present)`

### Implementation Tasks

26. **Probability validation (BayesianDecisionService.php)** — [x] Add reusable validator:
   ```php
   private function assertProbability($value, $fieldName) {
       if (!is_numeric($value)) {
           throw new InvalidArgumentException($fieldName . ' must be numeric.');
       }
       $v = (float) $value;
       if ($v < 0.0 || $v > 1.0) {
           throw new InvalidArgumentException($fieldName . ' must be between 0.0 and 1.0.');
       }
       return $v;
   }
   ```
   Call from any create/update that writes probability fields.

27. **Core Bayesian update methods** — [x] Add to BayesianDecisionService:
   ```php
   public function calculatePosterior($prior, $likelihood, $evidenceProbability) {
       $pPrior = $this->assertProbability($prior, 'prior');
       $pLike  = $this->assertProbability($likelihood, 'likelihood');
       $pEv    = $this->assertProbability($evidenceProbability, 'evidence');
       if ($pEv == 0.0) {
           throw new InvalidArgumentException('evidenceProbability must be > 0 for Bayes update.');
       }
       $posterior = ($pPrior * $pLike) / $pEv;
       return $this->normalizeProbability($posterior);
   }
   
   public function normalizeProbability($p) {
       if ($p < 0.0) { return 0.0; }
       if ($p > 1.0) { return 1.0; }
       return (float) $p;
   }
   ```

28. **Evidence combination** — [x] Add sequential evidence updates:
   ```php
   public function combineEvidenceSequential($prior, array $likelihoods, $evidenceProbability) {
       $p = $this->assertProbability($prior, 'prior');
       foreach ($likelihoods as $idx => $lik) {
           $p = $this->calculatePosterior($p, $lik, $evidenceProbability);
       }
       return $p;
   }
   ```

29. **Decision probability updates** — [x] Add method to update from evidence:
   ```php
   public function updateDecisionProbabilityFromEvidence($decisionId, array $likelihoods, $evidenceProbability) {
       $decision = $this->getDecisionById($decisionId);
       if (!$decision) {
           throw new RuntimeException('Decision not found: ' . (int)$decisionId);
       }
       $prior = isset($decision['probability']) ? $decision['probability'] : 0.5;
       $posterior = $this->combineEvidenceSequential($prior, $likelihoods, $evidenceProbability);
       $this->saveDecisionProbability($decisionId, $prior, $posterior);
       return $posterior;
   }
   ```

30. **Influence processing** — [x] Add weighted influence handling:
   ```php
   public function applyInfluences($basePosterior, array $influences) {
       // Weighted average blend: half base, half influenced
       $base = $this->assertProbability($basePosterior, 'basePosterior');
       if (!$influences) { return $base; }
       
       $weighted = 0.0; $totalW = 0.0;
       foreach ($influences as $inf) {
           $w = isset($inf['influence_weight']) ? (float)$inf['influence_weight'] : 0.0;
           if ($w <= 0.0) { continue; }
           // Source probability lookup and weighted calculation
           // ... (implementation details)
       }
       if ($totalW <= 0.0) { return $base; }
       $influenced = $weighted / $totalW;
       return $this->normalizeProbability(0.5 * $base + 0.5 * $influenced);
   }
   ```

31. **Decision traversal helpers** — [x] Add to BayesianDecisionService:
   ```php
   public function getParentDecisions($decisionId)
   public function getChildDecisions($decisionId)
   public function getRootDecision($decisionId)
   public function getDecisionDepth($decisionId)
   ```

32. **Evidence tracking table** — [x] Add `lupo_decision_evidence` table to install SQL:
   ```sql
   CREATE TABLE lupo_decision_evidence (
     decision_evidence_id bigint NOT NULL,
     decision_id bigint NOT NULL,
     channel_id bigint NOT NULL,
     project_id bigint DEFAULT 0,
     evidence_type varchar(64) NOT NULL,
     evidence_source varchar(255) NOT NULL,
     evidence_value text,
     likelihood decimal(10,6) DEFAULT NULL,
     confidence decimal(10,6) DEFAULT NULL,
     federation_node_id bigint NOT NULL DEFAULT 1,
     status varchar(32) NOT NULL DEFAULT 'active',
     created_ymdhis bigint NOT NULL DEFAULT 0,
     updated_ymdhis bigint NOT NULL,
     is_deleted tinyint NOT NULL DEFAULT 0,
     deleted_ymdhis bigint DEFAULT NULL,
     PRIMARY KEY (decision_evidence_id)
   );
   ```
   Add corresponding TOON and table doc.

33. **Evidence service methods** — [x] Add to BayesianDecisionService:
   ```php
   public function recordEvidence($decisionId, $channelId, $projectId, $type, $source, $value, $likelihood, $confidence)
   public function getEvidenceForDecision($decisionId)
   ```

34. **Decision state model** — [x] Add state transitions:
   ```php
   public function setStatePending($decisionId)
   public function setStateEvaluating($decisionId)
   public function confirmDecision($decisionId)
   public function rejectDecision($decisionId)
   ```
   State values: `pending`, `evaluating`, `confirmed`, `rejected`.

35. **Decision update history (optional)** — [ ] Add `lupo_decision_updates` table for audit trail.

36. **Integration hooks** — [x] Minimal safe integration:
   - Add linkDecisionToTask($decisionId, $taskId) in TaskService
   - Add dialog_thread_id metadata in dialog flow
   - Keep as service-level associations; avoid heavy UI changes.

37. **Minimal API surface** — [x] Add `lupo-includes/modules/api/decisions-api.php`:
   - POST /api/decisions → create decision (with initial prior)
   - GET /api/decisions/{id} → fetch decision + state + probability
   - POST /api/decisions/{id}/evidence → record evidence + update probability
   - GET /api/decisions/{id}/evidence → list evidence
   - Security: Auth from session, no client-supplied actor_id

38. **Tests** — [x] Add `lupo-tests/unit/bayesian_decision_service_test.php`:
   - calculatePosterior with known numbers (e.g. prior 0.5, likelihood 0.8, evidence 0.5 → expected 0.8)
   - Invalid probabilities throw
   - combineEvidenceSequential behaves as expected
   - applyInfluences blends base and influenced value predictably

39. **Architecture documentation** — [x] Create `lupo-docs/architecture/BAYESIAN_DECISION_TRACKING_ARCHITECTURE.md`:
   - Tables: lupo_decisions, lupo_decision_edges, lupo_decision_influences, lupo_decision_evidence
   - Flow: create decision → record evidence → compute posterior → apply influences → update state
   - Include worked example with explicit numbers

40. **Table docs updates** — [x] Update all Bayesian table docs:
   - lupo_decisions.md
   - lupo_decision_edges.md  
   - lupo_decision_influences.md
   - lupo_decision_evidence.md (if created)
   - lupo_decision_updates.md (if created)
   - Add USED_IN_PHP edges to new service/API files

41. **Status artifact** — [x] Create `lupo-docs/status/bayesian_decision_tracking_implementation_4_0_79.md`:
   - Before vs after (foundation vs functioning system)
   - Schema changes vs existing tables used as-is
   - Code files touched
   - Tests added
   - Deferred items

42. **CHANGELOG entry** — [x] Add under 4.0.79:
   "Cursor — Bayesian Decision Tracking implementation (4.0.79)" summarizing:
   - Real Bayes updates
   - Evidence tracking support
   - Decision state helpers
   - Tests, docs, and API/service surface

---

## Implementation Priority

**HIGH:** Tasks 26-30 (Core Bayesian algorithms)  
**MEDIUM:** Tasks 31-37 (Integration & API)  
**LOW:** Tasks 38-42 (Testing & Documentation)
