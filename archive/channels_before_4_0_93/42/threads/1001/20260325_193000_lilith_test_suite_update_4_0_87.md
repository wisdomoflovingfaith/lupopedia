---
lupopedia.headers:
  lupopedia.version: "4.0.87"
  lupopedia.schema: "workstream"
  file_path_from_root: "channels/42/threads/1001/20260325_193000_lilith_test_suite_update_4_0_87.md"
  file_hash: "789012345678901234567890abcdef1234567890abcdef1234567890abcde"
  last_updated_utc: "20260325193000"
  system_version: "4.0.87"
  channel_id: 42
  thread_id: 1001
  actor_id: 2
  delegation_chain: "2:1"
  artifact_type: "workstream"
  artifact_kind: "test_update"
  purpose: "LILITH updates test suite to match current schema after workstreams 1-2 complete"
  mood_vector: "FF0000"
  traits: ["lilith_testing", "quality_assurance", "regression_testing"]
  tags: ["test_suite", "update", "quality_assurance", "lilith", "4.0.87"]
  lupo_agent: "cascade"

lupopedia.edges:
  outbound_edges:
    - { to: "channels/42/threads/1003/20260325_183000_lilith_full_system_critical_review_4_0_87.md", type: "addresses", weight: 1.0 }
    - { to: "tests/unit/", type: "updates", weight: 1.0 }
    - { to: "tests/integration/", type: "updates", weight: 1.0 }
    - { to: "tests/regression/", type: "updates", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260325193000"
  last_verified_by: "cascade"
  next_action: "Wait for workstreams 1-2 completion, then update all tests to match current schema"
---

# LILITH — Test Suite Update (4.0.87)

**Actor**: LILITH (actor_id 2)  
**Date**: 2026-03-25  
**Version**: 4.0.87  
**Workstream**: Test Suite Update  
**Priority**: MEDIUM  
**Thread**: 1001 (existing)

---

## 1. EXECUTIVE SUMMARY

**MEDIUM PRIORITY UPDATE** - Test suite must be updated to reflect schema changes from Workstreams 1-2 (Decision System Cleanup and Edge Model Consolidation). Tests currently reference removed tables and outdated schema structures.

---

## 2. DEPENDENCIES

**Prerequisites**:
- Workstream 1: Decision System Cleanup (HEPHAESTUS) must complete
- Workstream 2: Edge Model Consolidation (HEPHAESTUS) must complete

**Cannot proceed until**:
- All decision system components removed
- Edge model consolidated to single table
- Schema changes finalized

---

## 3. CURRENT TEST PROBLEMS

### 3.1 Decision System Tests

**Broken Tests**:
- `tests/unit/test_bayesian_decision.php` - References removed tables
- Any tests calling `api/v1/decisions-api.php` - Endpoint removed
- Tests using `bin/cli/decision-cli.php` - CLI tool removed
- Tests for `BayesianDecisionService.php` - Service class removed

**Expected Impact**:
- Test failures due to missing files/tables
- Runtime errors when decision tests execute
- CI pipeline failures

### 3.2 Edge Model Tests

**Outdated Tests**:
- Tests referencing removed edge tables (`lupo_actor_edges`, `lupo_reference_cited_by`)
- Tests expecting multiple edge systems
- Tests for non-existent tables (`lupo_entity_edges`, `lupo_gov_event_*`)

**Expected Impact**:
- Test failures for removed table references
- Edge functionality tests failing
- Schema validation test failures

---

## 4. UPDATE STRATEGY

### 4.1 Phase 1: Remove Broken Tests

**Decision System Tests**:
```bash
# Remove tests for removed functionality
rm tests/unit/test_bayesian_decision.php
# Remove any other decision-related tests
```

**Edge Model Tests**:
- Update tests to use only `lupo_edges`
- Remove tests for removed edge tables
- Add tests for consolidated edge model

### 4.2 Phase 2: Add New Tests

**Channel-Based Decision Tests**:
- Test decision reconstruction from channel threads
- Test ROSE decision analysis capabilities
- Test channel-based decision tracking

**Consolidated Edge Model Tests**:
- Test single `lupo_edges` table functionality
- Test edge type handling
- Test edge creation/retrieval with type field

### 4.3 Phase 3: Update Existing Tests

**Schema Validation Tests**:
- Update expected schema to match current state
- Remove validation for removed tables
- Add validation for consolidated structures

**Integration Tests**:
- Update any integration tests using removed components
- Ensure all tests use current schema
- Verify no references to removed tables remain

---

## 5. TESTING FRAMEWORK

### 5.1 Test Categories

**Unit Tests** (`tests/unit/`):
- Individual component testing
- Schema validation
- Basic functionality tests

**Integration Tests** (`tests/integration/`):
- Component interaction testing
- End-to-end workflow testing
- API endpoint testing

**Regression Tests** (`tests/regression/`):
- Historical functionality verification
- Performance regression testing
- Compatibility testing

### 5.2 Test Execution

**Run All Tests**:
```bash
# Execute full test suite
sh scripts/run_tests.sh .

# Run specific test categories
sh scripts/run_unit_tests.sh .
sh scripts/run_regression_tests.sh .
```

**CI Integration**:
- Ensure CI pipeline passes after updates
- Verify no test failures in automated runs
- Check coverage metrics maintained

---

## 6. SUCCESS CRITERIA

### 6.1 Must Complete
- [ ] All tests referencing removed tables updated/removed
- [ ] No test failures due to schema changes
- [ ] CI pipeline passes completely
- [ ] Test suite accurately reflects current system state

### 6.2 Should Complete
- [ ] New tests added for consolidated edge model
- [ ] Channel-based decision tests implemented
- [ ] Test coverage maintained or improved
- [ ] Documentation updated for test changes

---

## 7. VERIFICATION PROCESS

### 7.1 Pre-Update Verification
- Document current test failures
- Identify all tests needing updates
- Plan test removal/addition strategy

### 7.2 Post-Update Verification
- Run full test suite
- Verify all tests pass
- Check CI pipeline status
- Validate test coverage

---

## 8. RISKS

**Low Risk**:
- Clear understanding of required changes
- No complex logic modifications needed
- Straightforward test updates

**Mitigation**:
- Thorough testing after updates
- Careful removal of broken tests
- Comprehensive verification

---

## 9. STATUS

**Status**: WAITING FOR DEPENDENCIES  
**Priority**: MEDIUM  
**Estimated Effort**: 3-4 hours (after dependencies complete)

**Next Action**: Wait for Workstreams 1-2 completion, then begin test suite updates.

---

**LILITH Assessment**: Test suite update is straightforward cleanup following schema changes. No major logic changes required, just updating tests to match current reality.

**Implementation Priority**: MEDIUM - Quality assurance depends on this work.
