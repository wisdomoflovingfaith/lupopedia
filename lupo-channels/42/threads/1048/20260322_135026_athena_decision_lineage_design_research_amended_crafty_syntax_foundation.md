---
lupopedia.headers:
  version_when_written: "4.0.85"
  file_path_from_root: "lupo-channels/42/threads/1048/20260322_135026_athena_decision_lineage_design_research_amended_crafty_syntax_foundation.md"
  last_modified_utc: "20260322_135026"
  channel_id: 42
  thread_id: 1048
  task_id: "task_ch42_th1048"
  actor_id: 12
  actor_name: "athena"
  delegation_chain: "athena:research"
  artifact_type: "research_report"
  artifact_kind: "decision_lineage_design_amendment"
  purpose: "Amended decision-lineage and choice-logging design grounded in Crafty Syntax dialog foundation, channel screen model, human-actor distinction, constitutional constraints, and BMAD/Doom evaluation framework."

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/versions/4.0.85/TASK_REGISTRY.md", type: "registered_in", weight: 1.0, reason: "Authoritative lifecycle and ownership surface" }
    - { to: "lupo-channels/42/threads/1048/20260321_160000_thoth_task_registry_construction_status.md", type: "preserves_prior_context", weight: 0.7, reason: "Thread historical context retained" }
    - { to: "lupo-channels/42/threads/2003/20260322_134115_athena_decision_lineage_and_choice_logging_research.md", type: "extends", weight: 1.0, reason: "Original decision-lineage design preserved and amended" }
    - { to: "lupo-channels/66/THREAD_INDEX.md", type: "integrates_with", weight: 0.8, reason: "Question graph linkage for decision records" }
---

# ATHENA Design Research - Decision Lineage and Choice Logging System (Amended)

**Thread:** Channel 42, Thread 1048  
**Actor:** ATHENA (actor_id 12)  
**Date:** 2026-03-22  
**Status:** Design Research - Amended to Include Crafty Syntax Foundation  
**Previous Version:** Original decision lineage design (preserved)  
**Amendment Purpose:** Ground design in operational reality of Crafty Syntax dialog as human-AI collaboration foundation

---

## 0. CORE SYSTEM REALITY: CRAFTY SYNTAX FOUNDATION

### 0.1 Why Crafty Syntax Matters

Lupopedia is built on Crafty Syntax because humans need a dialog layer for AI work.

This is not optional. This is the operational core.

Crafty Syntax provides:
- Human-to-human dialog in channels
- Human-to-actor dialog (AI assistance)
- Message attribution (who said what)
- Threaded conversations
- A web interface humans already understand

Lupopedia extends this foundation with:
- Projects - scope of work
- Channels - areas of coordination
- Threads - focused conversations (one per task)
- Actors - operational identities (AI or human)
- Auth Users - physical human identities
- Edges - typed relationships between entities
- Status Artifacts - structured work records
- Task/Decision/Contradiction Lineage - traceability

The dialog layer is not replaced. It is extended.

### 0.2 How the Decision Lineage System Fits

Decision lineage must be visible within the dialog interface, not hidden in a separate system.

When a human user opens a channel, they should see:
- Dialog messages (who said what, when)
- Embedded decision records (what choices were made)
- Edges to related work (what depends on this)
- Actor attribution (which AI actor participated)

The decision lineage system exists to support human understanding of AI choices, not to replace human judgment.

---

## 1. CHANNEL SCREEN MODEL (AMENDED)

### 1.1 What a Human Should See

When a human opens a channel in the web interface:

```
+----------------------------------------------------------------+
| Channel 42 - Protocol Development                              |
| Auth User: wolfie (logged in)                                  |
+----------------------------------------------------------------+
| [Threads] [Decisions] [Contradictions] [Tasks]                 |
+----------------------------------------------------------------+
| Thread: 1048 - Decision Lineage Design                         |
| Status: active                                                 |
+----------------------------------------------------------------+
| DIALOG (reverse chronological)                                 |
|                                                                |
| [2026-03-22 08:15:32] ATHENA (actor_id 12):                   |
|   Proposed decision record structure with options_considered   |
|   field. Awaiting review.                                      |
|                                                                |
| [2026-03-22 08:30:15] wolfie (auth_user_id 1000):             |
|   Need to see how this integrates with Crafty Syntax dialog.   |
|   The decision record should be visible inline.                |
|                                                                |
| [2026-03-22 09:00:00] ATHENA (actor_id 12):                   |
|   Amended design to show decision records as embedded blocks   |
|   within thread dialog. See below:                             |
|                                                                |
| +------------------------------------------------------------+ |
| | DECISION RECORD: dec_1048_001                              | |
| | Question: Where should decision lineage live?              | |
| | Selected: Hybrid (lupo_decisions + thread artifacts)       | |
| | Rationale: Keep canonical source in DB, human-readable in  | |
| |            thread for visibility.                           | |
| | [View Full Decision] [See Downstream Edges]                | |
| +------------------------------------------------------------+ |
|                                                                |
| [2026-03-22 09:30:00] wolfie (auth_user_id 1000):             |
|   Approved. This makes decisions part of the dialog.          |
|   Add edge to TASK_REGISTRY.md update task.                   |
|                                                                |
+----------------------------------------------------------------+
| [Reply as auth_user] [Reply as actor] [Create Decision Record] |
+----------------------------------------------------------------+
```

### 1.2 Required UI Components

| Component | Purpose | Data Source |
|-----------|---------|-------------|
| Dialog Messages | Human-actor conversation | `lupo_dialog_messages` |
| Embedded Decision Blocks | Decision records in context | `lupo_decisions` + thread artifact |
| Actor Badges | Show which actor is speaking | `lupo_actors` |
| Auth User Badges | Show which human is speaking | `lupo_auth_users` |
| Edge Navigation | Links to related threads/tasks | `lupo_edges` |
| Decision Inline Response | Human approval/rejection of AI choices | Web UI form -> `lupo_decisions` |

---

## 2. DIALOG MODEL (AMENDED)

### 2.1 Who Talks to Whom

| Sender | Receiver | Purpose | Recorded As |
|--------|----------|---------|-------------|
| Human (auth_user) | Human (auth_user) | Coordination | `lupo_dialog_messages` (auth_user attribution) |
| Human (auth_user) | Actor | Request work, approve decisions | `lupo_dialog_messages` (auth_user -> actor_id) |
| Actor | Human (auth_user) | Report status, request clarification | `lupo_dialog_messages` (actor_id -> auth_user) |
| Actor | Actor | Coordination, handoff | `lupo_dialog_messages` (actor_id -> actor_id) |

### 2.2 Dialog as Evidence

Dialog messages that contain decisions, clarifications, or approvals become evidence for decision records:

```yaml
evidence_paths:
  - "lupo-channels/42/threads/1048/20260322_083015_dialog.md#message_3"
```

### 2.3 Dialog as Lineage

Dialog messages are part of thread/task lineage:

```
Thread 1048
  - Dialog Message 1 (ATHENA): Proposed design
  - Dialog Message 2 (wolfie): Requested amendment
  - Dialog Message 3 (ATHENA): Amended design with decision block
  - Decision Record (embedded)
  - Dialog Message 4 (wolfie): Approved
```

### 2.4 Dialog Refinement Workflow (Stream 7 Integration)

Prompt refinement occurs through dialog before major directives are issued:

```
1. WOLFIE drafts directive in thread
2. LILITH reviews in thread (dialog)
3. Dialog refinement (multiple messages)
4. Final directive issued
5. Decision record created capturing:
   - options considered during refinement
   - rationale from dialog
   - evidence links to dialog messages
```

---

## 3. EDGE MODEL (AMENDED)

### 3.1 What Edges Connect

| From | To | Edge Type | Purpose |
|------|----|-----------|---------|
| Thread | Thread | `parent_of`, `child_of` | Thread hierarchy |
| Thread | Task | `contains` | Work assignment |
| Thread | Decision | `contains` | Decision record |
| Decision | Task | `governs` | Decision affects task |
| Task | Contradiction | `causes` | Task created contradiction |
| Decision | Contradiction | `causes` | Decision led to contradiction |
| Question | Decision | `resolves` | Question answered by decision |
| Dialog Message | Decision | `evidence` | Dialog supports decision |

### 3.2 Navigation Through Edges

A user viewing a decision record should be able to navigate to:

- The thread where it was discussed (via `contains` edge)
- The task it governs (via `governs` edge)
- The dialog messages that provided evidence (via `evidence` edge)
- Contradictions it caused (via `causes` edge)
- Questions it resolves (via `resolves` edge)

This makes the system navigable as a graph, not just a set of isolated artifacts.

---

## 4. HUMAN + ACTOR DISTINCTION (AMENDED)

### 4.1 Identity Layers

| Layer | Table | Role | Can Send Dialog |
|-------|-------|------|-----------------|
| Auth User | `lupo_auth_users` | Physical human with login | Yes |
| Actor | `lupo_actors` | Operational identity (AI or human) | Yes |
| Faucet | `lupo_agent_faucets` | Execution surface | No |

### 4.2 Human in the Loop (Thread 1038)

Humans are not outside the system. They are represented as:
- Auth users (login, identity)
- Supporting actors (operational roles they adopt)

When a human responds to a decision request, they:
1. Authenticate as auth_user
2. Select which supporting actor to use (for example, WOLFIE, LILITH)
3. Response is recorded with both `auth_user_id` and `actor_id`

This preserves audit trail without collapsing identity layers.

### 4.3 Actor Behavior Constraints

Actors (including those operated by humans) must respect constitutional constraints:

| Constraint | Enforcement |
|------------|-------------|
| No foreign keys | LILITH flags in audit; schema rejected if proposed |
| No triggers | LILITH flags; schema rejected |
| No DATETIME columns | LILITH flags; schema rejected |
| BIGINT UTC only | LILITH flags; schema rejected |
| No calendar-based planning | LILITH flags in plan review |
| No business logic in DB | LILITH flags in code review |

These are not preferences. They are constitutional rules that actors cannot bypass.

---

## 5. BMAD AND DOOM EMACS EVALUATION FRAMEWORK (AMENDED)

### 5.1 Evaluation Question

When studying BMAD and Doom Emacs, Lupopedia must ask:

> How do these systems help Lupopedia improve channels, threads, dialog, edge relationships, and human+actor collaboration?

Not:

> How do we copy their internal structures directly?

### 5.2 BMAD Evaluation (Workflow System)

| BMAD Pattern | What Lupopedia Can Learn | How to Adapt |
|--------------|--------------------------|--------------|
| Decision gates | Work cannot proceed without approval | Decision records with `requires_approval` field; edge to approver |
| Role-based workflow | Different actors handle different stages | Actor roles in `lupo_actor_projects` |
| Evidence requirements | Decisions require supporting artifacts | `evidence_paths` field in decision record |
| Audit trail | Complete record of who did what | `actor_id` + `auth_user_id` on all actions |

Not copying: BMAD's internal task structure. Adapting: the concept that decisions should be gated and traceable.

### 5.3 Doom Emacs Evaluation (Graph/Pattern System)

| Doom Pattern | What Lupopedia Can Learn | How to Adapt |
|--------------|--------------------------|--------------|
| Configuration as graph | Modules depend on other modules | Edge model: `task -> task` dependencies |
| Pattern composition | Small patterns compose into larger ones | Decision records can reference other decisions |
| Discoverability | Users can navigate configuration | Edge-based navigation in UI |
| Declarative structure | State is declared, not imperative | Decision records as declarative choices |

Not copying: Doom's Emacs Lisp implementation. Adapting: the concept that system state can be understood as a navigable graph of decisions and dependencies.

### 5.4 Channel 66 Integration

Channel 66 questions should connect to decisions:

```
Channel 66 Question Thread
  - Question: "Should decision records be stored in DB or filesystem?"
  - Decision Record: dec_1048_001 (Hybrid model)
  - Edge: resolves (question -> decision)
```

This makes the question graph executable - questions lead to decisions that change the system.

---

## 6. CONSTITUTIONAL CONSTRAINTS (EXPLICIT)

### 6.1 The Invariants

These are non-negotiable. Any actor (human or AI) that violates them must be corrected.

| Invariant | Doctrine Source | Enforcement |
|-----------|-----------------|-------------|
| No foreign keys | `lupo-rules/root/database-logic-prohibition-doctrine.md` | LILITH audit; schema rejected |
| No triggers | Same | LILITH audit; schema rejected |
| No stored procedures/functions | Same | LILITH audit; schema rejected |
| No DATETIME/TIMESTAMP vendor types | `lupo-rules/root/timestamp-doctrine.md` | LILITH audit; schema rejected |
| BIGINT UTC only | Same | LILITH audit; schema rejected |
| No calendar-based planning | `lupo-rules/root/task-planning-doctrine.md` | LILITH audit; plan rejected |
| No business logic in database | `lupo-rules/root/database-logic-prohibition-doctrine.md` | Code review; LILITH audit |
| Explicit INSERT column lists | `lupo-rules/root/safe-database-operations-doctrine.md` | Code review; LILITH audit |

### 6.2 How Decision Lineage Supports Invariants

When a decision is made, the decision record should capture:
- Whether the decision respects invariants
- If it intentionally violates an invariant (requires WOLFIE override)
- What the invariant was and why it was considered

This makes invariant violations visible and auditable, not hidden.

---

## 7. REVISED PHASED IMPLEMENTATION PATH

### Phase 1: Structure (4.0.85/4.0.86)
- [ ] Extend `lupo_decisions` table with new columns (decision_question, options_considered, rationale, etc.)
- [ ] Create decision record format documentation
- [ ] Add decision record display in thread dialog (embedded blocks)
- [ ] Add edge types for decision relationships
- [ ] Ensure all constitutional constraints are enforced in schema review

### Phase 2: Integration (4.0.86)
- [ ] Add UI for inline decision creation from dialog
- [ ] Add decision approval workflow (human in the loop)
- [ ] Link decisions to questions (Channel 66)
- [ ] Add edge-based navigation from decisions to downstream work

### Phase 3: Query and Audit (4.0.87)
- [ ] Build decision lineage viewer
- [ ] Add invariant compliance tracking
- [ ] Create decision impact analysis (what depends on this decision)
- [ ] Add human-readable decision summary page

### Phase 4: Bayesian (Future)
- [ ] Collect decision outcome data
- [ ] Implement probabilistic confidence updates
- [ ] Add decision recommendation based on historical outcomes

---

## 8. REVISED DESIGN ARTIFACT STRUCTURE

The final design artifact (this document) now includes:

| Section | Purpose |
|---------|---------|
| 0. Core System Reality | Crafty Syntax foundation; why dialog matters |
| 1. Channel Screen Model | What humans see; how decisions appear in UI |
| 2. Dialog Model | Who talks to whom; dialog as evidence and lineage |
| 3. Edge Model | What connects to what; navigation through graph |
| 4. Human + Actor Distinction | Identity layers; human in the loop |
| 5. BMAD/Doom Evaluation | How to learn from external systems without copying |
| 6. Constitutional Constraints | Non-negotiable invariants; how they are enforced |
| 7. Implementation Path | Phased rollout |
| 8. Summary | How this strengthens Lupopedia |

---

## 9. SUMMARY: HOW THIS STRENGTHENS LUPOPEDIA

| Lupopedia Concept | How Decision Lineage Strengthens It |
|-------------------|-------------------------------------|
| Crafty Syntax Dialog | Decisions become visible in dialog; dialog becomes evidence for decisions |
| Channels | Channel screen shows decisions inline; users navigate through edges |
| Threads | Decisions are part of thread lineage; thread contains decision record |
| Actors | Actor decisions are attributed and auditable; human and AI actors are distinguished |
| Auth Users | Humans can approve/reject decisions; response recorded with dual attribution |
| Edges | Decisions connect to tasks, contradictions, questions, dialog |
| Constitutional Constraints | Invariant violations are visible in decision records |
| BMAD/Doom Learning | Patterns adapted to Lupopedia concepts, not copied blindly |

---

ATHENA (actor_id 12) - Decision lineage design amended to incorporate Crafty Syntax foundation, channel screen model, dialog integration, human-actor distinction, and constitutional constraints. Ready for review and Phase 1 implementation planning.
