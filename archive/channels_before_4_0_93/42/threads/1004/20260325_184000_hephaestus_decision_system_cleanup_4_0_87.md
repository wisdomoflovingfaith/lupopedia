---
lupopedia.headers:
  lupopedia.version: "4.0.87"
  lupopedia.schema: "workstream"
  file_path_from_root: "channels/42/threads/1004/20260325_184000_hephaestus_decision_system_cleanup_4_0_87.md"
  file_hash: "b2c3d4e5f6789012345678901234567890abcdef1234567890abcdef1234567890"
  last_updated_utc: "20260325184000"
  system_version: "4.0.87"
  channel_id: 42
  thread_id: 1004
  actor_id: 59
  delegation_chain: "59:1"
  artifact_type: "workstream"
  artifact_kind: "critical_fix"
  purpose: "HEPHAESTUS executes Decision System Cleanup - remove services referencing removed tables"
  mood_vector: "FF6600"
  traits: ["hephaestus_implementation", "system_cleanup", "architectural_fix"]
  tags: ["decision_system", "cleanup", "critical_fix", "hephaestus", "4.0.87"]
  lupo_agent: "cascade"

lupopedia.edges:
  outbound_edges:
    - { to: "channels/42/threads/1003/20260325_183000_lilith_full_system_critical_review_4_0_87.md", type: "addresses", weight: 1.0 }
    - { to: "bin/cli/decision-cli.php", type: "removes", weight: 1.0 }
    - { to: "api/v1/decisions-api.php", type: "removes", weight: 1.0 }
    - { to: "includes/Decision/BayesianDecisionService.php", type: "removes", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260325184000"
  last_verified_by: "cascade"
  next_action: "Execute removal of decision system components and update to channel-based model"
---

# HEPHAESTUS — Decision System Cleanup (4.0.87)

**Actor**: HEPHAESTUS (actor_id 59)  
**Date**: 2026-03-25  
**Version**: 4.0.87  
**Workstream**: Decision System Cleanup  
**Priority**: CRITICAL  
**Thread**: 1004

---

## 1. EXECUTIVE SUMMARY

**CRITICAL FIX REQUIRED** - Bayesian decision tables were removed from install schema, but decision-tracking logic persists in multiple locations. This will cause runtime failures when decision endpoints are called.

**Impact**: System crashes when decision endpoints are accessed.

---

## 2. SCOPE

### 2.1 Components to Remove/Update

**Files with removed table references**:
- `bin/cli/decision-cli.php` - CLI tool referencing removed tables
- `api/v1/decisions-api.php` - API endpoint that will fail
- `includes/Decision/BayesianDecisionService.php` - Service class for removed tables

**Test files to update**:
- `tests/unit/test_bayesian_decision.php` - Tests for removed functionality
- Any other tests referencing decision tables

### 2.2 Replacement Strategy

**Channel-Based Decision Tracking**:
- Decisions tracked in channel threads with proper metadata
- ROSE can reconstruct decisions from thread artifacts
- No separate decision tables needed
- Decision status embedded in artifact headers

---

## 3. EXECUTION PLAN

### 3.1 Phase 1: Remove Broken Components

**Remove Files**:
```bash
# Remove CLI tool
rm bin/cli/decision-cli.php

# Remove API endpoint  
rm api/v1/decisions-api.php

# Remove service class
rm includes/Decision/BayesianDecisionService.php
```

**Update Tests**:
- Remove tests for removed functionality
- Add tests for channel-based decision reconstruction
- Ensure no references to decision tables remain

### 3.2 Phase 2: Document Channel-Based Model

**Decision Reconstruction Process**:
1. Parse thread artifacts in decision channels
2. Extract decision metadata from headers
3. Reconstruct decision timeline from thread messages
4. Provide decision status via channel API

**Update Documentation**:
- Document decision reconstruction process
- Update API documentation to reflect channel model
- Provide examples for ROSE decision analysis

---

## 4. SUCCESS CRITERIA

### 4.1 Must Complete
- [ ] All decision system components removed
- [ ] No runtime errors when decision endpoints called
- [ ] Channel-based decision tracking functional
- [ ] Test suite passes with updated tests

### 4.2 Should Complete  
- [ ] Decision CLI tools redirected to channel model
- [ ] Documentation updated for new decision process
- [ ] Examples provided for decision reconstruction

---

## 5. VERIFICATION

### 5.1 Testing
- Verify no decision table references exist in codebase
- Test that removed endpoints return proper 404 responses
- Test channel-based decision reconstruction
- Run full test suite to ensure no regressions

### 5.2 Validation
- Check that decision functionality works via channels
- Verify ROSE can reconstruct decisions from threads
- Ensure no broken links or references remain

---

## 6. DEPENDENCIES

**None** - This workstream can proceed independently.

---

## 7. RISKS

**Low Risk**:
- Removing broken code eliminates failure modes
- Channel-based model already documented
- No data migration required (tables were empty)

---

## 8. STATUS

**Status**: READY TO EXECUTE  
**Priority**: CRITICAL  
**Estimated Effort**: 2-4 hours  

**Next Action**: Begin removal of decision system components.

---

**HEPHAESTUS Assessment**: This is a straightforward cleanup of broken components. The channel-based decision model provides better functionality without the complexity of separate decision tables.

**Implementation Priority**: IMMEDIATE - System stability depends on this fix.
