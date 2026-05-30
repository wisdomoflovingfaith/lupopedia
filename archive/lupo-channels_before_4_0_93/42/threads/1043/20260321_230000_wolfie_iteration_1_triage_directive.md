---
lupopedia.headers:
  version_when_written: "4.0.85"
  lupopedia.schema: "triage_directive"
  file_path_from_root: "lupo-channels/42/threads/1043/20260321_230000_wolfie_iteration_1_triage_directive.md"
  web_path: "http://www.lupopedia.com/lupo-channels/42/threads/1043/iteration_1_triage"
  questions_toon: null
  channel_id: 42
  thread_id: 1043
  task_id: "task_upgrade_triage_001"
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "triage_directive"
  artifact_kind: "triage_directive"
  purpose: "WOLFIE triage directive for iteration 1 failures in Crafty 3.7.5 → Lupopedia 4.0.85 upgrade loop"
  mood_vector: "8B0000"
  traits: ["4.0.85", "triage", "directive", "iteration_1", "failures", "wolfie"]
  tags: ["wolfie", "4.0.85", "triage", "directive", "iteration_1", "upgrade", "failures"]

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-channels/42/threads/1043/20260321_220000_thoth_iteration_1_findings.md", type: "triages", weight: 1.0, reason: "Triage of THOTH iteration 1 findings" }
    - { to: "lupo-channels/42/threads/1032/", type: "directs", weight: 1.0, reason: "Schema fixes directed to Thread 1032" }
    - { to: "lupo-channels/42/threads/1030/", type: "directs", weight: 1.0, reason: "Code/UI fixes directed to Thread 1030" }
    - { to: "lupo-channels/42/threads/1043/", type: "triggers", weight: 0.9, reason: "Triggers iteration 2 after fixes complete" }

lupopedia.footer:
  iteration: 1
  triage_type: "failure_triage"
  next_action:
    - "HEPHAESTUS: Execute schema fixes in Thread 1032"
    - "HEPHAESTUS: Execute code/UI fixes in Thread 1030"
    - "HEPHAESTUS: Rerun upgrade loop iteration 2 in Thread 1043"
    - "THOTH: Validate iteration 2 results"
    - "LILITH: Independent verification of PASS verdict"
---

# WOLFIE Triage Directive — Iteration 1 Failures

**Iteration**: 1  
**Triage Date UTC**: 20260321_230000  
**Triage Officer**: WOLFIE (actor_id 1)  
**Source**: THOTH iteration 1 findings artifact  
**Scope**: Complete triage of all iteration 1 failures  
**Status**: TRIAGE COMPLETE - EXECUTION DIRECTIVES ISSUED

---

## EXECUTIVE SUMMARY

THOTH iteration 1 validation identified **FAIL** status with **9 total gaps** requiring immediate resolution. This directive converts all failures, schema gaps, code gaps, and UI gaps into canonical tasks with explicit ownership and execution order.

**Critical Priority**: Schema fixes are BLOCKING for all other work.

---

## TASK CREATION

### SCHEMA TASKS → Thread 1032

#### task_schema_missing_table_001
- **task_id**: task_schema_missing_table_001
- **owner_actor_id**: 1:wolfie
- **target_thread_id**: 1032
- **description**: Create missing lupo_thread_metadata table per install_new_lupopedia.sql
- **dependency**: None
- **priority**: P0 (BLOCKING)

#### task_schema_missing_column_001
- **task_id**: task_schema_missing_column_001
- **owner_actor_id**: 1:wolfie
- **target_thread_id**: 1032
- **description**: Add missing lupo_channels.channel_config column per install_new_lupopedia.sql
- **dependency**: None
- **priority**: P0 (BLOCKING)

#### task_schema_missing_column_002
- **task_id**: task_schema_missing_column_002
- **owner_actor_id**: 1:wolfie
- **target_thread_id**: 1032
- **description**: Add missing lupo_threads.thread_lineage column per install_new_lupopedia.sql
- **dependency**: None
- **priority**: P0 (BLOCKING)

#### task_schema_type_correction_001
- **task_id**: task_schema_type_correction_001
- **owner_actor_id**: 8:hephaestus
- **target_thread_id**: 1032
- **description**: Fix lupo_tasks.task_priority data type from VARCHAR(10) to ENUM
- **dependency**: None
- **priority**: P0 (BLOCKING)

#### task_schema_nullability_001
- **task_id**: task_schema_nullability_001
- **owner_actor_id**: 8:hephaestus
- **target_thread_id**: 1032
- **description**: Fix lupo_actors.actor_config nullability from NULL to NOT NULL
- **dependency**: None
- **priority**: P0 (BLOCKING)

### CODE/UI TASKS → Thread 1030

#### task_thread_php_fix_001
- **task_id**: task_thread_php_fix_001
- **owner_actor_id**: 8:hephaestus
- **target_thread_id**: 1030
- **description**: Fix undefined index error in thread.php line 142 (thread_metadata)
- **dependency**: task_schema_missing_table_001 (Thread 1032)
- **priority**: P1

#### task_task_php_fix_001
- **task_id**: task_task_php_fix_001
- **owner_actor_id**: 8:hephaestus
- **target_thread_id**: 1030
- **description**: Fix SQL error in task_manager.php (unknown column 'task_lineage')
- **dependency**: task_schema_missing_column_002 (Thread 1032)
- **priority**: P1

#### task_thread_routing_fix_001
- **task_id**: task_thread_routing_fix_001
- **owner_actor_id**: 8:hephaestus
- **target_thread_id**: 1030
- **description**: Fix thread routing 404 errors in thread detail pages
- **dependency**: task_thread_php_fix_001
- **priority**: P1

#### task_task_visibility_fix_001
- **task_id**: task_task_visibility_fix_001
- **owner_actor_id**: 8:hephaestus
- **target_thread_id**: 1030
- **description**: Fix incomplete task list visibility in task management UI
- **dependency**: task_task_php_fix_001
- **priority**: P1

---

## ROUTING DIRECTIVES

### TO THREAD 1032 (SCHEMA AUTHORITY)

**IMMEDIATE EXECUTION REQUIRED**

1. **Implement Missing Tables**
   - Execute CREATE TABLE for lupo_thread_metadata
   - Follow install_new_lupopedia.sql specification exactly
   - Include all indexes and constraints

2. **Add Missing Columns**
   - ALTER TABLE lupo_channels ADD COLUMN channel_config
   - ALTER TABLE lupo_threads ADD COLUMN thread_lineage
   - Use exact definitions from install_new_lupopedia.sql

3. **Correct Data Types**
   - ALTER TABLE lupo_tasks MODIFY COLUMN task_priority ENUM
   - Include all valid enum values
   - Preserve existing data if possible

4. **Fix Nullability**
   - ALTER TABLE lupo_actors MODIFY COLUMN actor_config NOT NULL
   - Set default values where required
   - Update existing NULL records

**Execution Authority**: WOLFIE (schema) + HEPHAESTUS (implementation)
**Completion Criteria**: All schema gaps resolved, install script updated

### TO THREAD 1030 (VISIBILITY/UI)

**EXECUTION AFTER SCHEMA FIXES COMPLETE**

1. **Fix Thread Loading**
   - Resolve undefined index: thread_metadata in thread.php line 142
   - Add proper null checks and error handling
   - Test with missing metadata scenarios

2. **Fix Task Queries**
   - Resolve SQL error: unknown column 'task_lineage'
   - Update task_manager.php queries
   - Add fallback for missing columns

3. **Fix Thread Routing**
   - Resolve 404 errors in thread detail pages
   - Update routing logic to handle missing lineage
   - Test all thread URL patterns

4. **Fix Task Visibility**
   - Resolve incomplete task list in management UI
   - Update task loading logic
   - Ensure all tasks display correctly

**Execution Authority**: HEPHAESTUS
**Completion Criteria**: All PHP errors resolved, UI functionality restored

---

## EXECUTION ORDER

### PHASE 1: SCHEMA FIXES (BLOCKING)
**Priority**: P0 - Must complete first

1. **task_schema_missing_table_001** (WOLFIE)
2. **task_schema_missing_column_001** (WOLFIE)
3. **task_schema_missing_column_002** (WOLFIE)
4. **task_schema_type_correction_001** (HEPHAESTUS)
5. **task_schema_nullability_001** (HEPHAESTUS)

**Blocking Status**: All other tasks blocked until schema complete

### PHASE 2: CODE FIXES (DEPENDENT)
**Priority**: P1 - After schema complete

6. **task_thread_php_fix_001** (HEPHAESTUS)
7. **task_task_php_fix_001** (HEPHAESTUS)

**Dependencies**: Schema tasks must complete first

### PHASE 3: UI FIXES (FINAL)
**Priority**: P1 - After code fixes

8. **task_thread_routing_fix_001** (HEPHAESTUS)
9. **task_task_visibility_fix_001** (HEPHAESTUS)

**Dependencies**: Code fixes must complete first

---

## TASK DEPENDENCY MAP

```
Phase 1 (Schema):
+-- task_schema_missing_table_001 (WOLFIE)
+-- task_schema_missing_column_001 (WOLFIE)
+-- task_schema_missing_column_002 (WOLFIE)
+-- task_schema_type_correction_001 (HEPHAESTUS)
+-- task_schema_nullability_001 (HEPHAESTUS)

Phase 2 (Code):
+-- task_thread_php_fix_001 (HEPHAESTUS) → depends on: task_schema_missing_table_001
+-- task_task_php_fix_001 (HEPHAESTUS) → depends on: task_schema_missing_column_002

Phase 3 (UI):
+-- task_thread_routing_fix_001 (HEPHAESTUS) → depends on: task_thread_php_fix_001
+-- task_task_visibility_fix_001 (HEPHAESTUS) → depends on: task_task_php_fix_001
```

---

## NEXT ITERATION TRIGGER

### ITERATION 2 EXECUTION CONDITION

**AFTER ALL TASKS COMPLETE**:
→ HEPHAESTUS reruns upgrade loop iteration 2 in Thread 1043

**Trigger Conditions**:
1. All 9 triage tasks marked COMPLETE
2. Thread 1032 schema fixes verified
3. Thread 1030 code/UI fixes verified
4. No remaining P0 blockers

**Iteration 2 Execution**:
- HEPHAESTUS executes drop-install-validate loop
- THOTH validates iteration 2 results
- Target: 100% step pass rate
- Goal: PASS verdict

---

## SUCCESS METRICS

### ITERATION 1 TRIAGE SUCCESS
- **Total Gaps**: 9
- **Tasks Created**: 9
- **Tasks Assigned**: 9
- **Coverage**: 100%

### OWNERSHIP DISTRIBUTION
- **WOLFIE**: 3 tasks (schema authority)
- **HEPHAESTUS**: 6 tasks (implementation)

### THREAD ROUTING
- **Thread 1032**: 5 tasks (schema)
- **Thread 1030**: 4 tasks (code/UI)

### PRIORITY DISTRIBUTION
- **P0 (Blocking)**: 5 tasks (schema)
- **P1 (Dependent)**: 4 tasks (code/UI)

---

## QUALITY ASSURANCE

### VALIDATION REQUIREMENTS
1. **Schema Changes**: Must match install_new_lupopedia.sql exactly
2. **Code Changes**: Must handle missing data gracefully
3. **UI Changes**: Must restore full functionality
4. **Testing**: Must verify all step_results show PASS

### COMPLETION VERIFICATION
1. **Thread 1032**: All schema tasks marked COMPLETE
2. **Thread 1030**: All code/UI tasks marked COMPLETE
3. **Integration**: No PHP errors in error logs
4. **Functionality**: All UI elements working correctly

---

## RISK MITIGATION

### HIGH RISK AREAS
1. **Schema Changes**: Risk of data loss during ALTER operations
2. **Dependencies**: Risk of breaking existing functionality
3. **Testing**: Risk of incomplete validation

### MITIGATION STRATEGIES
1. **Backup**: Create database backup before schema changes
2. **Staging**: Test changes in non-production environment
3. **Rollback**: Prepare rollback procedures for each change
4. **Validation**: Comprehensive testing after each fix

---

## EXECUTION AUTHORITY

### WOLFIE (actor_id 1)
- **Schema Authority**: Final approval for schema changes
- **Triage Authority**: Task assignment and prioritization
- **Coordination**: Cross-thread execution oversight

### HEPHAESTUS (actor_id 8)
- **Implementation Authority**: Execute all code and schema changes
- **Testing Authority**: Verify fixes work correctly
- **Iteration Authority**: Execute iteration 2 validation loop

---

## NEXT ACTIONS

### IMMEDIATE (This Session)
1. **WOLFIE**: Begin executing schema tasks in Thread 1032
2. **HEPHAESTUS**: Prepare for code/UI tasks in Thread 1030
3. **SYSTEM**: Monitor task completion status

### SHORT-TERM (Next Session)
1. **Complete Phase 1**: All schema tasks
2. **Complete Phase 2**: All code fixes
3. **Complete Phase 3**: All UI fixes
4. **Trigger Iteration 2**: HEPHAESTUS reruns validation loop

### LONG-TERM (Future Iterations)
1. **Automate**: Schema validation in install script
2. **Enhance**: Error reporting for missing elements
3. **Document**: Lessons learned for future upgrades

---

## CONCLUSION

All 9 iteration 1 failures have been converted into canonical tasks with explicit ownership and execution order. Schema fixes are blocking and must complete first. After all tasks complete, HEPHAESTUS will rerun the upgrade loop for iteration 2 validation.

**Status**: TRIAGE COMPLETE - EXECUTION DIRECTIVES ISSUED  
**Next Action**: HEPHAESTUS executes schema fixes in Thread 1032  
**Goal**: Iteration 2 PASS verdict

---

**WOLFIE (actor_id 1) — Iteration 1 triage complete. 9 tasks created and routed. Schema fixes blocking for iteration 2.**
