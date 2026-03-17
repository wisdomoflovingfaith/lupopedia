---
lupopedia.headers:
  lupopedia.version: "4.0.80"
  lupopedia.schema: "documentation"
  system_version: "4.0.80"
  file_path_from_root: "lupo-docs/versions/4.0.80/TODO.md"
  web_path: "[TODO](http://www.lupopedia.com/versions/4.0.80/TODO)"
  last_modified_utc: "20260317"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "todo"
  artifact_kind: "version_todo"
  purpose: "Concrete task list for 4.0.80 (completion of Top 50 table docs + Bayesian expansion)"
  tags: ["todo", "4.0.80", "table_documentation", "top_50", "bayesian"]

lupopedia.footer:
  version: "4.0.80"
  last_verified: "20260317"
  last_verified_by: "wolfie"
  next_action:
    - "Complete remaining Top 50 table documentation AND expand Bayesian Decision Tracking"
---
# file: Version 4.0.80 TODO — web_path: http://www.lupopedia.com/versions/4.0.80/TODO

# Version 4.0.80 — TODO List

## Status

- **State:** Open (post–4.0.79 release)
- **Theme:** Completion of Top 50 operational table documentation + Bayesian Decision Tracking expansion
- **Source:** Carried over from 4.0.79 incomplete tasks. Pattern: [TABLE_DOCUMENTATION_4_0_77_STOP_LINE.md](lupo-docs/status/TABLE_DOCUMENTATION_4_0_77_STOP_LINE.md).

---

## A. Remaining Top 50 — Auth (from 4.0.79)

1. **lupo_auth_providers.md** — [ ] Update to 4.0.80 LUPOPEDIA_HEADERS; Table Overview; Where This Table Is Used; columns from install SQL; namespace auth.
2. **lupo_auth_audit_log.md** — [ ] Same pattern (auth).
3. **lupo_banned_actors.md** — [ ] Same pattern (auth).
4. **lupo_bans_log.md** — [ ] Same pattern (auth).

---

## B. Remaining Top 50 — Analytics (from 4.0.79)

5. **lupo_unified_log.md** — [ ] Update to 4.0.80 LUPOPEDIA_HEADERS; Table Overview; Where This Table Is Used; columns; namespace analytics.
6. **lupo_analytics_events.md** — [ ] Verify table exists in install SQL; if present, document; if not, omit.

---

## C. Remaining Top 50 — Expansion (from 4.0.79)

7. **Additional Top 50 (21–50)** — [ ] Select from install SQL tables by system criticality to reach 50 total.
8. **Header version normalization** — [ ] Ensure all Top 50 docs have `lupopedia.version: "4.0.80"` and `system_version: "4.0.80"`.
9. **Namespace validation** — [ ] Verify all table docs are in correct namespace (auth, content, analytics, core).
10. **TABLE_INDEX.md completion** — [ ] Add missing Top 50 table entries.
11. **Duplicate/FLARE cleanup** — [ ] Remove any remaining FLARE references.

---

## D. Bayesian Decision Tracking Expansion (NEW for 4.0.80)

### Ground Truth: Schema + Doctrine (from 4.0.79)

**Tables (from install SQL + docs):**
- `lupo_decisions` – core decision records
- `lupo_decision_edges` – graph edges between decisions (parent/child/related)
- `lupo_decision_influences` – influence weights between decisions or between evidence and decisions
- `lupo_decision_evidence` – evidence tracking (implemented in 4.0.79)

### Expansion Tasks

12. **Decision update history table** — [ ] Add `lupo_decision_updates` table for audit trail:
   ```sql
   CREATE TABLE lupo_decision_updates (
     decision_update_id bigint NOT NULL,
     decision_id bigint NOT NULL,
     update_type varchar(32) NOT NULL,
     old_probability decimal(10,6) DEFAULT NULL,
     new_probability decimal(10,6) DEFAULT NULL,
     old_status varchar(32) DEFAULT NULL,
     new_status varchar(32) DEFAULT NULL,
     updated_by_actor_id bigint DEFAULT NULL,
     created_ymdhis bigint NOT NULL DEFAULT 0,
     PRIMARY KEY (decision_update_id)
   );
   ```

13. **History service methods** — [ ] Add to BayesianDecisionService:
   ```php
   public function recordDecisionUpdate($decisionId, $updateType, $oldProbability, $newProbability, $oldStatus, $newStatus, $actorId)
   public function getDecisionHistory($decisionId)
   ```

14. **Multi-level influence propagation** — [ ] Enhance influence processing:
   ```php
   public function applyMultiLevelInfluences($basePosterior, array $influences, $maxDepth = 3)
   public function getInfluenceChain($decisionId, $maxDepth = 3)
   ```

15. **Evidence correlation modeling** — [ ] Handle dependent evidence:
   ```php
   public function combineEvidenceWithCorrelation($prior, array $evidenceItems, $correlationMatrix)
   public function calculateEvidenceDependencies($decisionId)
   ```

16. **Adaptive probability weighting** — [ ] Learn from historical accuracy:
   ```php
   public function applyAdaptiveWeighting($basePosterior, $historicalAccuracy)
   public function updateHistoricalAccuracy($decisionId, $actualOutcome)
   ```

17. **Decision pattern recognition** — [ ] Identify common decision patterns:
   ```php
   public function analyzeDecisionPatterns($actorId, $timeframe)
   public function suggestSimilarDecisions($decisionId, $threshold = 0.8)
   ```

18. **Performance optimizations** — [ ] Optimize for high-volume scenarios:
   - Add caching for frequently accessed decisions
   - Batch evidence processing
   - Optimized graph traversal algorithms

19. **Enhanced API surface** — [ ] Add advanced API endpoints:
   - GET /api/decisions/{id}/history → decision update history
   - POST /api/decisions/{id}/influence → apply influence
   - GET /api/decisions/patterns/{actor_id} → decision patterns

20. **Expanded testing** — [ ] Add comprehensive test coverage:
   - Multi-level influence tests
   - Evidence correlation tests
   - Performance benchmark tests
   - Pattern recognition tests

21. **Documentation updates** — [ ] Update all Bayesian documentation:
   - Architecture doc with new features
   - Table docs for lupo_decision_updates
   - API documentation
   - Performance guidelines

---

## Implementation Priority

**HIGH:** Tasks 1-11 (Top 50 completion)  
**MEDIUM:** Tasks 12-18 (Bayesian expansion)  
**LOW:** Tasks 19-21 (Testing & Documentation)
