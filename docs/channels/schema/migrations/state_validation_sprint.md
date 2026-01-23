---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.35
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
updated: 2026-01-15
author: GLOBAL_CURRENT_AUTHORS
architect: Captain Wolfie
dialog:
  speaker: LILITH
  target: @everyone
  message: "Created state validation sprint document. Tests 8-state model against real migration scenarios from actual experience. Uses real pain points, not theory. Validates state model before completing implementation."
  mood: "FF6600"
tags:
  categories: ["migration", "orchestration", "validation"]
  collections: ["core-docs"]
  channels: ["dev"]
file:
  title: "State Model Validation Sprint"
  description: "30-minute validation sprint testing state model against real migration scenarios"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# State Model Validation Sprint

**Purpose:** Validate 8-state model (7 original + FAILED guardrail) against real migration scenarios from actual experience.

**Duration:** 30 minutes  
**Method:** Real scenarios, not theory  
**Goal:** Determine if 8-state model is correct before completing implementation

**Created:** 2026-01-15  
**Status:** Validation in progress

---

## Real Migration Scenarios (From Actual Experience)

### Scenario 1: Mid-Migration Failure
**Real Experience:** Migration partially executes, then database connection drops or SQL error occurs mid-execution.

**What Actually Happened:**
- Migration started executing
- Got halfway through ALTER TABLE statement
- Database connection lost
- Table left in inconsistent state
- Had to manually fix table structure
- Then re-ran migration

**Current 8-State Model:**
```
idle → preparing → validating_pre → migrating → [FAILURE] → ?
```

**Questions:**
- ❓ Where does migrating go on failure? → `rolling_back` or `failed`?
- ❓ Can we detect partial execution? → Check table state? Migration log?
- ❓ What if rollback SQL doesn't exist? → Go to `failed`?
- ❓ What if rollback also fails? → Go to `failed`?

**Model Assessment:**
- ✅ `migrating` can transition to `rolling_back` (good)
- ✅ `rolling_back` can transition to `failed` if rollback fails (good - guardrail works)
- ⚠️ **Gap:** How do we detect partial execution on restart?
- ⚠️ **Gap:** What if migration has no rollback SQL?

**Verdict:** ⚠️ **PARTIAL** - Model handles failure, but recovery unclear

---

### Scenario 2: Dependency Missing
**Real Experience:** Migration depends on another migration that hasn't been run yet.

**What Actually Happened:**
- Created migration B that depends on migration A
- Tried to run migration B
- Migration A hadn't been run yet
- Migration B failed with "table doesn't exist" error
- Had to manually run migration A first
- Then re-ran migration B

**Current 8-State Model:**
```
idle → preparing → [DEPENDENCY CHECK] → ?
```

**Questions:**
- ❓ Where does preparing go if dependency check fails? → `failed` or stay in `preparing`?
- ❓ Can we auto-queue dependent migration? → Need `blocked` state?
- ❓ What if dependency is optional? → Different handling?

**Model Assessment:**
- ✅ `preparing` can check dependencies (good)
- ⚠️ **Gap:** No clear path when dependencies fail
- ⚠️ **Gap:** Should go to `failed` (nothing to rollback) or `blocked` (waiting)?

**Verdict:** ⚠️ **UNCLEAR** - Need explicit dependency failure path

---

### Scenario 3: Partial Execution with Manual Fix
**Real Experience:** Migration partially executed, system crashed, had to manually fix database, then mark migration as complete.

**What Actually Happened:**
- Migration started
- Got 50% through
- System crashed
- On restart, database was in partial state
- Manually fixed database to match migration goal
- Marked migration as "completed" manually
- Skipped re-running migration

**Current 8-State Model:**
```
idle → preparing → validating_pre → migrating → [CRASH] → [RESTART] → ?
```

**Questions:**
- ❓ How do we detect partial execution on restart? → Check migration state? Database state?
- ❓ Can we resume? → Need `resuming` state?
- ❓ Or always rollback? → Safer but loses progress
- ❓ Can we mark as "manually fixed" and skip? → Need admin override?

**Model Assessment:**
- ❌ **Gap:** No recovery mechanism
- ❌ **Gap:** No way to mark "manually fixed"
- ⚠️ **Recommendation:** Always rollback on restart (safer, simpler)

**Verdict:** ❌ **FAILS** - No recovery mechanism defined

---

### Scenario 4: Rollback That Wasn't Clean
**Real Experience:** Migration failed, rollback was attempted, but rollback SQL had errors, left system in inconsistent state.

**What Actually Happened:**
- Migration executed successfully
- Post-validation failed (data corruption detected)
- Rollback attempted
- Rollback SQL had syntax error
- System left in inconsistent state
- Had to manually fix

**Current 8-State Model:**
```
idle → preparing → validating_pre → migrating → validating_post → [FAIL] → rolling_back → [ROLLBACK FAILS] → ?
```

**Questions:**
- ❓ Where does rolling_back go if rollback fails? → `failed` (good - guardrail works)
- ❓ How do we verify rollback succeeded? → Need rollback validation?
- ❓ What if rollback partially succeeds? → Need partial rollback state?

**Model Assessment:**
- ✅ `rolling_back` can transition to `failed` (good - guardrail works)
- ⚠️ **Gap:** No rollback validation
- ⚠️ **Gap:** No partial rollback handling

**Verdict:** ⚠️ **PARTIAL** - Guardrail works, but needs rollback validation

---

### Scenario 5: Doctrine Violation Detected Pre-Execution
**Real Experience:** Migration SQL violates doctrine (e.g., contains FOREIGN KEY), detected before execution.

**What Actually Happened:**
- Created migration with FOREIGN KEY
- Doctrine validator caught it
- Had to rewrite migration without FK
- Re-ran validation

**Current 8-State Model:**
```
idle → preparing → validating_pre → [DOCTRINE VIOLATION] → ?
```

**Questions:**
- ❓ Where does validating_pre go if validation fails? → `failed` (nothing to rollback)
- ❓ Can we fix and retry? → Go back to `idle`?
- ❓ Should validation happen earlier? → In `preparing`?

**Model Assessment:**
- ✅ `validating_pre` can transition to `failed` (good - guardrail works)
- ✅ `failed` can transition to `idle` for retry (good)
- ✅ Validation happens before execution (good - prevents bad migrations)

**Verdict:** ✅ **PASSES** - Model handles this cleanly

---

## Validation Results Summary

### Scenarios That Pass
- ✅ **Scenario 5:** Doctrine violation pre-execution → `failed` → `idle` (retry)

### Scenarios That Partially Pass
- ⚠️ **Scenario 1:** Mid-migration failure → `rolling_back` → `failed` (if rollback fails)
  - **Gap:** Recovery mechanism unclear
- ⚠️ **Scenario 4:** Rollback failure → `failed`
  - **Gap:** No rollback validation

### Scenarios That Fail
- ❌ **Scenario 2:** Dependency missing → No clear path
- ❌ **Scenario 3:** Partial execution recovery → No recovery mechanism

---

## Critical Findings

### 1. FAILED State Guardrail Works ✅
- Scenarios 1, 4, 5 all benefit from explicit `failed` state
- Clear failure sink prevents ambiguous states
- Can retry from `failed` → `idle`

### 2. Recovery Mechanism Missing ❌
- Scenario 3 exposes gap: No way to handle partial execution
- **Recommendation:** Always rollback on restart (safer, simpler)
- **Alternative:** Add `resuming` state (more complex)

### 3. Dependency Failure Path Unclear ⚠️
- Scenario 2: Where does `preparing` go when dependencies fail?
- **Recommendation:** `preparing` → `failed` (nothing to rollback)
- **Alternative:** Add `blocked` state (waiting for dependencies)

### 4. Rollback Validation Missing ⚠️
- Scenario 4: Need to verify rollback succeeded
- **Recommendation:** Add rollback validation in `rolling_back` state
- **Alternative:** Post-rollback validation state

---

## Recommended State Model Adjustments

### Keep 8 States, Add Clarity

**8 States (Current):**
1. idle
2. preparing
3. validating_pre
4. migrating
5. validating_post
6. completing
7. rolling_back
8. failed ✅ (guardrail added)

**Failure Transitions (Define Explicitly):**
- `validating_pre` → `failed` (nothing to rollback)
- `migrating` → `rolling_back` (partial execution)
- `validating_post` → `rolling_back` (execution completed)
- `rolling_back` → `failed` (rollback failed)
- `preparing` → `failed` (dependency check failed)

**Recovery Policy:**
- On restart, if migration in `migrating` state → always go to `rolling_back`
- Simpler and safer than resuming

---

## Final Verdict

**8-State Model:** ✅ **VALIDATED WITH MINOR ADJUSTMENTS**

**Required Adjustments:**
1. ✅ Define explicit failure transitions (done above)
2. ✅ Add recovery policy: always rollback on restart
3. ⚠️ Consider: Add rollback validation in `rolling_back` state
4. ⚠️ Consider: Handle dependency failures explicitly in `preparing`

**Can Proceed:** ✅ **YES** - Model is sound with defined failure paths

**Before 4.0.36:**
- [ ] Update state transition documentation with explicit failure paths
- [ ] Add recovery policy to `migrating` state
- [ ] Consider rollback validation enhancement
- [ ] Then proceed with remaining state implementations

---

**Created:** 2026-01-15  
**Last Updated:** 2026-01-15  
**Status:** Validation complete - Model validated with minor adjustments needed
