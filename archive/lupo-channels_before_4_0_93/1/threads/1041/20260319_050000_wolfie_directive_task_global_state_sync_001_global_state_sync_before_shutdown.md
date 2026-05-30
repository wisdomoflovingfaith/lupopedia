---
lupopedia.headers:
  lupopedia.version: "4.0.82"
  lupopedia.schema: "thread"
  system_version: "4.0.82"
  questions_toon: null
  project_id: 0
  project_slug: "lupopedia-core"
  channel_id: 1
  thread_id: 1041
  task_id: "task_global_state_sync_001"
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "thread"
  artifact_kind: "directive"
  file_path_from_root: "lupo-channels/1/threads/1041/20260319_050000_wolfie_directive_task_global_state_sync_001_global_state_sync_before_shutdown.md"
  web_path: "http://www.lupopedia.com/lupo-channels/1/threads/1041/20260319_050000_wolfie_directive_task_global_state_sync_001_global_state_sync_before_shutdown.md"
  purpose: "Force all actors to synchronize system state into CHANGELOG.md, TODO.md, and plan.md before shutdown"
  message_type: "directive"
lupopedia.interpretation:
  whoami:
    facet: "cursor"
    runtime_context: "system"
    session_mode: "checkpoint_shutdown"
    project_id: 0
    project_slug: "lupopedia-core"
    channel_id: 1
    thread_id: 1041
  whoareyou:
    actor_id: 1
    actor_name: "wolfie"
    identity_source: "canonical_registry"
    state: "active"
    authority_level: "canonical_orchestrator"
  whoopposesyou: "lilith"
---

# 🐺 WOLFIE DIRECTIVE — GLOBAL STATE SYNC BEFORE SHUTDOWN

## 🚨 IMMEDIATE ORDER

All actors MUST:

- stop new development work
- stop new doctrine work
- stop new implementation

This is a **mandatory state synchronization checkpoint**.

---

## 🎯 OBJECTIVE

Ensure the entire system has:

- one consistent record of what was done
- one consistent record of what remains
- one clear next execution plan

No drift between actors.

---

## 📋 REQUIRED ACTIONS (ALL ACTORS)

Each actor MUST contribute to the SAME shared files:

---

## 1. CHANGELOG.md (WHAT WAS DONE)

Append only. Do NOT rewrite history.

Each actor must add:

- what they implemented
- what artifacts were created or modified
- validator / doctrine / header / limiter work completed
- reference channel_id + thread_id + task_id

Focus on **completed work only**.

---

## 2. TODO.md (WHAT REMAINS)

Each actor must:

- move any incomplete work here
- include:
  - remaining validator work
  - repo-wide web_path migration (if not complete)
  - identity mismatch cleanup
  - filename normalization
  - limiter phase 1 completion gaps (if any)

Rules:
- no completed tasks
- no vague items
- everything actionable

---

## 3. plan.md (WHAT HAPPENS NEXT)

Define the exact next execution order.

Must include:

### Phase 1
- repo-wide web_path normalization (if pending)
- identity + filename cleanup

### Phase 2
- RequestLimiter implementation (login, posting, uploads)

### Phase 3
- global request guard (front controller)
- pagination/search limits

Each step must be:
- ordered
- dependency-aware
- executable

---

## 🔒 GLOBAL RULE

All actors MUST write to:

- the SAME files
- the SAME version (4.0.82)
- the SAME interpretation model

No forks.  
No parallel versions.  
No agent-specific documents.

---

## 🧠 CONSISTENCY REQUIREMENT

The following must match across all files:

- interpretation model (whoareyou / whoami / whoopposesyou)
- strict-mode enforcement status
- canonical limits (199 tables, etc.)
- web_path deterministic rule

---

## 💤 SHUTDOWN CONDITION

After all updates are complete:

- no further file changes
- no new artifacts
- no new doctrine

System enters:

> **sleep / paused state**

---

## 🐺 FINAL ORDER

Synchronize state.  
Align all records.  
Stop execution.  

**Leave the system in a clean, deterministic state.**

