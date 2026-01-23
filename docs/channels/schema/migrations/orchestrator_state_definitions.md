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
  message: "Created conceptual definitions for 8 orchestrator states. Defines the 'soul' of the migration lifecycle - what each state represents, why it exists, and how transitions work. This is the philosophical foundation before implementation."
  mood: "FF6600"
tags:
  categories: ["migration", "orchestration", "concepts"]
  collections: ["core-docs"]
  channels: ["dev"]
file:
  title: "Migration Orchestrator State Definitions"
  description: "Conceptual definitions of the 8 migration orchestrator states and their transition logic"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# Migration Orchestrator State Definitions

**Purpose:** Define the conceptual "soul" of the migration lifecycle - what each state represents, why it exists, and how transitions work.

**Created:** 2026-01-15  
**Status:** Conceptual Foundation  
**Version:** 4.0.35

---

## The 8 States: Conceptual Definitions

### 1. **IDLE** (State ID: 1)
**Conceptual Meaning:** The migration exists but is dormant. No active processing.

**What It Represents:**
- Migration file has been created/loaded
- No batch assigned yet (or batch is idle)
- Waiting for human or system trigger to begin
- The "resting state" of migrations

**Why It Exists:**
- Provides a clear "not running" state
- Allows migrations to exist without being processed
- Starting point for all migration lifecycles
- Recovery point after rollback completes

**Transition Logic:**
- **Can Enter From:** Initial creation (no previous state), `rolling_back` (after rollback completes), `failed` (for retry)
- **Can Transition To:** `preparing` (when migration is triggered)
- **Cannot Skip:** All migrations start here

**Doctrine Safeguards:**
- No database changes allowed in this state
- Migration file must exist and be readable
- No validation required (migration hasn't started)

---

### 2. **PREPARING** (State ID: 2)
**Conceptual Meaning:** The migration is being prepared for execution - dependencies resolved, files validated, batch created.

**What It Represents:**
- Dependency graph resolution
- File existence validation
- Batch assignment and metadata preparation
- Prerequisites checking

**Why It Exists:**
- Separates "ready to start" from "actually starting"
- Allows dependency resolution before execution
- Validates file existence before loading SQL
- Prepares batch context for execution

**Transition Logic:**
- **Can Enter From:** `idle` (when migration is triggered)
- **Can Transition To:** `validating_pre` (if preparation succeeds), `failed` (if dependency check fails or file missing)
- **Cannot Skip:** Must resolve dependencies before execution

**Doctrine Safeguards:**
- Must verify migration file exists
- Must resolve all required dependencies
- Must validate batch context exists
- Cannot proceed if dependencies are missing

**Critical Question:** What happens if dependency check fails?
- **Answer:** Transition to `failed` (nothing to rollback yet)

---

### 3. **VALIDATING_PRE** (State ID: 3)
**Conceptual Meaning:** Pre-execution validation - checking migration SQL against doctrine before execution.

**What It Represents:**
- Doctrine compliance checking (no FKs, triggers, stored procedures)
- SQL syntax validation
- Schema conflict detection
- Safety checks before touching database

**Why It Exists:**
- Prevents bad migrations from executing
- Validates doctrine compliance before database changes
- Catches errors before they cause damage
- Separates "preparation" from "validation"

**Transition Logic:**
- **Can Enter From:** `preparing` (after dependencies resolved)
- **Can Transition To:** `migrating` (if validation passes), `failed` (if validation fails - nothing to rollback)
- **Cannot Skip:** Must validate before execution

**Doctrine Safeguards:**
- Must check for forbidden patterns (FKs, triggers, SPs)
- Must validate SQL syntax
- Must check for schema conflicts
- Cannot proceed if doctrine violations detected

**Critical Question:** What happens if validation fails?
- **Answer:** Transition to `failed` (nothing executed yet, nothing to rollback)

---

### 4. **MIGRATING** (State ID: 4)
**Conceptual Meaning:** The migration SQL is being executed against the database.

**What It Represents:**
- Actual database changes happening
- SQL execution in progress
- The "dangerous" state where things can break
- Point of no return (changes are being made)

**Why It Exists:**
- Separates validation from execution
- Allows progress tracking during execution
- Provides checkpoint for rollback if failure occurs
- The core state where migration "work" happens

**Transition Logic:**
- **Can Enter From:** `validating_pre` (after validation passes)
- **Can Transition To:** `validating_post` (if execution succeeds), `rolling_back` (if execution fails - partial changes made)
- **Cannot Skip:** This is where the migration actually happens

**Doctrine Safeguards:**
- Must execute SQL atomically (transaction)
- Must track execution progress
- Must detect failures immediately
- Cannot proceed if SQL execution fails

**Critical Question:** What happens if execution fails mid-way?
- **Answer:** Transition to `rolling_back` (partial execution occurred, must rollback)

**Recovery Policy:** On system restart, if migration is in `migrating` state → always transition to `rolling_back` (safer than resuming)

---

### 5. **VALIDATING_POST** (State ID: 5)
**Conceptual Meaning:** Post-execution validation - verifying that migration succeeded and data is consistent.

**What It Represents:**
- Verification that expected changes occurred
- Data integrity checks
- Schema consistency validation
- Confirmation that migration achieved its goal

**Why It Exists:**
- Catches execution failures that didn't throw exceptions
- Validates data integrity after changes
- Confirms migration success before committing
- Separates "execution" from "completion"

**Transition Logic:**
- **Can Enter From:** `migrating` (after execution completes)
- **Can Transition To:** `completing` (if validation passes), `rolling_back` (if validation fails - execution completed but results wrong)
- **Cannot Skip:** Must verify success before committing

**Doctrine Safeguards:**
- Must verify expected schema changes occurred
- Must check data integrity
- Must validate no orphaned records
- Cannot proceed if validation fails

**Critical Question:** What happens if post-validation fails?
- **Answer:** Transition to `rolling_back` (execution completed but results are wrong, must rollback)

---

### 6. **COMPLETING** (State ID: 6)
**Conceptual Meaning:** Finalizing the migration - updating state, recording completion, cleaning up.

**What It Represents:**
- Migration succeeded, finalizing records
- Updating batch progress
- Recording completion timestamp
- Transitioning to permanent state

**Why It Exists:**
- Separates "successful execution" from "permanent record"
- Allows final cleanup and state updates
- Provides checkpoint before marking as complete
- The "victory lap" state

**Transition Logic:**
- **Can Enter From:** `validating_post` (after post-validation passes)
- **Can Transition To:** `idle` (completion recorded, migration done)
- **Cannot Skip:** Must finalize before marking complete

**Doctrine Safeguards:**
- Must update all state records
- Must record completion timestamp
- Must update batch progress
- Cannot skip finalization

**Critical Question:** What happens after completing?
- **Answer:** Migration returns to `idle` (or could transition to an `archived` state if that exists, but currently returns to idle)

---

### 7. **ROLLING_BACK** (State ID: 7)
**Conceptual Meaning:** Executing rollback - undoing changes made by a failed migration.

**What It Represents:**
- Undoing partial or failed execution
- Executing rollback SQL (if exists)
- Restoring database to pre-migration state
- The "undo" operation

**Why It Exists:**
- Provides recovery mechanism for failures
- Allows clean rollback of partial executions
- Separates "failure" from "rollback execution"
- Critical for system safety

**Transition Logic:**
- **Can Enter From:** `migrating` (if execution fails), `validating_post` (if post-validation fails)
- **Can Transition To:** `idle` (if rollback succeeds), `failed` (if rollback fails - cannot recover)
- **Cannot Skip:** Must attempt rollback if execution occurred

**Doctrine Safeguards:**
- Must execute rollback SQL if available
- Must verify rollback succeeded
- Must restore database state
- Cannot proceed if rollback fails

**Critical Question:** What happens if rollback fails?
- **Answer:** Transition to `failed` (cannot recover, requires manual intervention)

**Critical Question:** What if migration has no rollback SQL?
- **Answer:** Still transition to `rolling_back`, but mark as "no rollback available" → then to `failed` (cannot rollback)

---

### 8. **FAILED** (State ID: 8) - Guardrail State
**Conceptual Meaning:** Migration has failed and cannot proceed. Requires manual intervention or retry.

**What It Represents:**
- Terminal failure state
- Migration cannot proceed automatically
- Requires human or system intervention
- The "stop and think" state

**Why It Exists:**
- Explicit failure handling (not just "stuck")
- Clear distinction between "waiting" and "failed"
- Allows retry after fixing issues
- Prevents ambiguous states

**Transition Logic:**
- **Can Enter From:** `validating_pre` (validation failed), `preparing` (dependency failure), `rolling_back` (rollback failed)
- **Can Transition To:** `idle` (for retry after fixing error)
- **Cannot Skip:** Must explicitly handle failures

**Doctrine Safeguards:**
- Must have error message recorded
- Must create alert for manual intervention
- Cannot proceed automatically
- Requires explicit retry or fix

**Critical Question:** How do we recover from failed?
- **Answer:** Fix the error, then transition to `idle` for retry

---

## State Transition Diagram (Conceptual)

```
                    [IDLE]
                      ↓
                 [PREPARING]
                 ↙        ↘
        [VALIDATING_PRE]  [FAILED] ← (dependency failure)
              ↙      ↘
      [MIGRATING]  [FAILED] ← (validation failure)
         ↙      ↘
[VALIDATING_POST] [ROLLING_BACK] ← (execution failure)
     ↙      ↘            ↙    ↘
[COMPLETING] [ROLLING_BACK] [IDLE] [FAILED] ← (rollback failure)
     ↓
  [IDLE] ← (completion)
```

---

## Batch vs File State Relationship

### **Batch State:**
A batch aggregates the states of its files. Batch state is derived from file states:

- **Batch PENDING:** All files are `idle` or `preparing`
- **Batch RUNNING:** At least one file is `validating_pre`, `migrating`, or `validating_post`
- **Batch COMPLETED:** All files are `completing` or `idle` (completed)
- **Batch FAILED:** At least one file is `failed` or `rolling_back` failed
- **Batch ROLLED_BACK:** All files are `idle` after rollback

### **File State Independence:**
Files can move through states independently within a batch. A batch can be "running" even if some files are still `idle` (waiting for dependencies).

---

## Doctrine Integration: How Doctrine Prevents Terminal States

### **Doctrine as Gatekeeper:**

1. **PREPARING State:**
   - Doctrine Check: "No migrations can proceed without resolving dependencies"
   - Safeguard: Blocks transition to `validating_pre` if dependencies missing

2. **VALIDATING_PRE State:**
   - Doctrine Check: "No FKs, triggers, or stored procedures allowed"
   - Safeguard: Blocks transition to `migrating` if doctrine violations detected

3. **MIGRATING State:**
   - Doctrine Check: "All SQL must be idempotent"
   - Safeguard: Validates SQL patterns before execution

4. **VALIDATING_POST State:**
   - Doctrine Check: "System state must be consistent after migration"
   - Safeguard: Blocks transition to `completing` if data integrity fails

5. **ROLLING_BACK State:**
   - Doctrine Check: "Rollback must restore system to pre-migration state"
   - Safeguard: Validates rollback success before returning to `idle`

### **Doctrine Violation Handling:**

- **Pre-execution violation:** → `failed` (nothing to rollback)
- **Post-execution violation:** → `rolling_back` (must undo changes)
- **Rollback violation:** → `failed` (cannot recover)

---

## Conceptual Questions Answered

### **Q: What triggers a transition from State A to State B?**

**A:** Each state has explicit transition conditions:

- **IDLE → PREPARING:** Human/system trigger (e.g., "run migration batch")
- **PREPARING → VALIDATING_PRE:** Dependencies resolved, files validated
- **VALIDATING_PRE → MIGRATING:** Validation passes, no doctrine violations
- **MIGRATING → VALIDATING_POST:** SQL execution completes without errors
- **VALIDATING_POST → COMPLETING:** Post-validation passes, data integrity confirmed
- **COMPLETING → IDLE:** Finalization complete, migration recorded

**Failure transitions:**
- **Any state → FAILED:** Unrecoverable error (no rollback needed)
- **MIGRATING/VALIDATING_POST → ROLLING_BACK:** Execution occurred but failed
- **ROLLING_BACK → FAILED:** Rollback itself failed

### **Q: How does Doctrine prevent a batch from entering a terminal state if system state is inconsistent?**

**A:** Doctrine checks occur at multiple gates:

1. **VALIDATING_PRE:** Prevents bad migrations from executing
2. **VALIDATING_POST:** Prevents inconsistent migrations from completing
3. **ROLLING_BACK:** Ensures rollback restores consistency

If system state is inconsistent:
- Post-validation will fail → transition to `rolling_back`
- Rollback will attempt to restore consistency
- If rollback fails → `failed` state (requires manual intervention)

### **Q: Can files move independently through states within a batch?**

**A:** Yes. Files have independent state machines. A batch's state is an aggregate of its files' states. This allows:
- Parallel processing of independent files
- Dependency-ordered execution
- Partial batch completion

---

## The "Soul" of the System

**The migration lifecycle is a journey from intention to permanence:**

1. **IDLE** - The migration exists as potential
2. **PREPARING** - The migration prepares for its journey
3. **VALIDATING_PRE** - The migration proves it's safe to proceed
4. **MIGRATING** - The migration does its work (the dangerous part)
5. **VALIDATING_POST** - The migration proves it succeeded
6. **COMPLETING** - The migration becomes permanent
7. **ROLLING_BACK** - The migration undoes itself (if needed)
8. **FAILED** - The migration stops and asks for help

**Each state has a purpose. Each transition has a reason. Each failure has a path.**

This is not just a state machine—it's a **philosophy of safe change**.

---

**Created:** 2026-01-15  
**Last Updated:** 2026-01-15  
**Status:** Conceptual foundation complete
