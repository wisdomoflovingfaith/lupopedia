---
lupopedia.headers:
  lupopedia.schema: todo
  file_path_from_root: "lupo-docs/versions/4.0.90/TODO.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.90/TODO.md"
  federation_node_id: 0
  last_modified_utc: "20260328250000"
  when_updated: "20260328250000"
  channel_id: 42
  thread_id: "4-0-90-todo"
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: todo
  artifact_kind: task
  purpose: Backlog deferred from 4.0.89 — context, Crafty Syntax, doc clarity, non-header QA
  tags:
    - "4.0.90"
    - todo
    - backlog
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/versions/4.0.90/PLAN.md"
      type: implements
      weight: 1.0
    - to: "lupo-docs/versions/4.0.89/TODO.md"
      type: references
      weight: 0.85
      reason: Pre-refocus task list (header items removed — see 4.0.89 TODO)
lupopedia.footer:
  last_verified: "20260328250000"
  verified_by:
    identity_type: actor
    actor_id: 1
    agent_name_identity: "WOLFIE"
    department_id_delta: 0
  verified_via:
    type: direct
    faucet_slug: none
  orchestrator: "wolfie:root"
  next_action:
    - "Pull next task from Priority 1 when 4.0.89 header release is tagged"
---

# TODO — Version 4.0.90

**Carryover:** Tasks below lived under **4.0.89** until scope was limited to **LUPOPEDIA HEADERS**. **4.0.89** `TODO.md` now tracks **header release** work only.

**Ordering:** Use dependency order per [`TASK_PLANNING_DOCTRINE.md`](../../doctrine/TASK_PLANNING_DOCTRINE.md) — not calendar weeks.

---

## Priority A — Documentation clarity (from former 4.0.89 §5.1–5.4)

| ID | Task | Owner | Status |
|----|------|-------|--------|
| A.1 | Version consistency in root/README navigation | WOLFIE | PENDING |
| A.2 | Navigation edges / ORGANIZATION.md | THOTH | PENDING |
| A.3 | FLARE-era cleanup in database READMEs | THOTH | PENDING |
| A.4 | IMPLEMENTATION_GETTING_STARTED.md bridge | ATHENA | PENDING |

---

## Priority B — Context model (from former 4.0.89 P1)

| ID | Task | Owner | Status |
|----|------|-------|--------|
| B.1 | `lupo_contexts` table + approval | THOTH | PENDING |
| B.2 | Context edge relationships in application code | THOTH | PENDING |
| B.3 | TASK_REGISTRY integration | ATHENA | PENDING |
| B.4 | Channel 66 question linking | THOTH | PENDING |

---

## Priority C — Crafty Syntax parity (from former 4.0.89 P2)

| ID | Task | Owner | Status |
|----|------|-------|--------|
| C.1 | Critical backlog items from `4.0.89/crafty_syntax_backlog.md` | HEPHAESTUS | PENDING |
| C.2 | Legacy analysis completion / specs | LILITH / ATHENA | IN PROGRESS (see 4.0.89 legacy_research) |

---

## Priority D — System enhancement (from former 4.0.89 P3–P4)

| ID | Task | Owner | Status |
|----|------|-------|--------|
| D.1 | Context automation triggers | THOTH | PENDING |
| D.2 | Performance work for context paths | HEPHAESTUS | PENDING |
| D.3 | Context docs / maintenance procedures | THOTH | PENDING |

---

## Priority F — Doctrine Enforcement & Guardrail (2026-03-29, CURSOR, LILITH)

| ID   | Task                                                      | Owner    | Status    |
|------|-----------------------------------------------------------|----------|-----------|
| F.1  | Create `lupo-rules/enforce_doctrine.py` for doctrine enforcement | CURSOR   | COMPLETE  |
| F.2  | Integrate validator into all build/sync/test workflows    | CURSOR   | PENDING   |
| F.3  | Update SyncChannelsToDb.php to call validator and show red warning on fail | CURSOR   | PENDING   |
| F.4  | Add "Enforcement" section to lupo-rules/root/README.md   | CURSOR   | PENDING   |
| F.5  | LILITH audit: Ensure all future schema/code changes pass validator | LILITH   | PENDING   |

---

**WOLFIE (actor_id 1)** — 4.0.90 backlog seeded from pre-refocus 4.0.89.

---

## Priority E — Actor Registry/Seed Refactor (2026-03-29, CURSOR)

| ID   | Task                                                      | Owner    | Status    |
|------|-----------------------------------------------------------|----------|-----------|
| E.1  | Batch update all canonical actors in SQL seed block       | CURSOR   | PENDING   |
| E.2  | Verify doctrine/SQL sync for actor/agent registry         | CURSOR   | PENDING   |
| E.3  | Test install/seed process for persistence and idempotency | CURSOR   | PENDING   |
| E.4  | Document Faucet Proxy Pattern and HEPHAESTUS protocol     | CURSOR   | COMPLETE  |
