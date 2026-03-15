---
lupopedia.headers:
  lupopedia.version: "4.0.75"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/doctrine/TASK_PLANNING_DOCTRINE.md"
  system_version: "4.0.75"
  last_modified_utc: "20260315"
  channel_id: 42
  artifact_type: "doctrine"
  artifact_kind: "documentation"
  purpose: "Implementation planning must use dependency ordering, not time estimates."

lupopedia.footer:
  version: "4.0.75"
  last_verified: "20260315"
  next_action: ["Keep prompt fragment and one-sentence rule in sync with root rule"]
---

# Task Planning Doctrine

## One-Sentence Rule

**Use dependency order, not duration estimates.**

---

## Principle

Structure phases by **what must be done before what**, never by how long they might take. Lupopedia uses asynchronous multi-agent execution; a task may start with Cursor, continue with Windsurf, and be validated by Antigravity. Time estimates are meaningless in this model; dependencies are not.

---

## Task Planning Doctrine (Required)

All implementation plans MUST:

1. **Use dependency ordering, not time estimates** — Phases are ordered by prerequisites, not by calendar or duration.
2. **Specify completion criteria for each phase** — So any agent can determine when a phase is done.
3. **Identify concurrent opportunities** — Where phases can run in parallel (e.g. Phase 3A and 3B both require Phase 2; Phase 4 requires 3A; Phase 5 requires 3B and 4).
4. **Never include** `Days X-Y`, week estimates, `estimated N days`, or other time-based estimates in plans.

### Rationale

Lupopedia uses multiple IDE agents working asynchronously. Agents work in parallel, with unknown availability, and tasks may be handed off between agents without notice. Time estimates are architectural noise; dependency order is the single source of truth for sequencing.

---

## Planning Format Standard

### Correct format

- Phase 1: Research — No dependencies
- Phase 2: Schema & Format Design — Requires Phase 1 complete
- Phase 3: Implementation — Requires Phase 2 complete
- Phase 4: Instrumentation — Requires Phase 3 complete
- Phase 5: Reporting — Requires Phases 3 and 4 complete

With concurrency:

- Phase 3A: Core Service — Requires Phase 2 complete
- Phase 3B: Search Instrumentation — Requires Phase 2 complete; can run concurrently with Phase 3A
- Phase 4: Channel Enforcement — Requires Phase 3A complete
- Phase 5: Collections Engine — Requires Phases 3B and 4 complete

### Wrong format (forbidden)

- Phase 1 (Days 1-3)
- Phase 2 (Days 4-5)
- Phase 3 (Days 6-10)
- Phase 1: Research (Weeks 1-2)
- Estimated 2 weeks

Use a **dependency matrix**, not a calendar.

---

## Reusable Prompt Fragment

Insert the following into directives when planning format must be enforced:

```text
⚠️ CRITICAL FORMATTING REQUIREMENT

Do NOT include time estimates (e.g. "(Days 16-20)", "2 weeks", "estimated 3 days") in any plan.

Lupopedia uses asynchronous multi-agent execution. Agents work in parallel, with unknown availability, and tasks may be handed off between agents without notice.

Instead, structure phases by required dependencies only:

✅ CORRECT FORMAT:
- Phase 2 requires Phase 1 to be complete
- Phase 3 requires Phase 2 to be complete
- Phase 4 requires Phase 2 to be complete and can run concurrently with Phase 3
- Phase 5 requires Phases 3 and 4 to be complete

❌ WRONG FORMAT:
- Phase 1 (Days 1-3)
- Phase 2 (Days 4-5)
- Phase 3 (Days 6-10)

Use a dependency matrix, not a calendar.
```

---

**Canonical root rule:** `lupo-rules/root/task-planning-doctrine.md` (PLAN001). All IDE agents (Cursor, Antigravity, Windsurf, Kiro, Warp, Trae, etc.) must follow this doctrine when producing or editing implementation plans.
