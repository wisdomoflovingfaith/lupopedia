---
lupopedia.headers:
  lupopedia.schema: plan
  file_path_from_root: "lupo-docs/versions/4.0.90/PLAN.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.90/PLAN.md"
  federation_node_id: 0
  last_modified_utc: "20260328250000"
  when_updated: "20260328250000"
  channel_id: 42
  thread_id: "4-0-90-planning"
  actor_id: 12
  actor_name: "athena"
  delegation_chain: "athena:root"
  artifact_type: plan
  artifact_kind: plan
  purpose: Living dependency-ordered plan for 4.0.90; receives context/Crafty/doc-clarity work deferred from 4.0.89
  tags:
    - "4.0.90"
    - plan
    - dependency_order
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/doctrine/TASK_PLANNING_DOCTRINE.md"
      type: references
      weight: 1.0
    - to: "lupo-docs/versions/4.0.90/TODO.md"
      type: implements
      weight: 1.0
    - to: "lupo-docs/versions/4.0.90/SCOPE_CARRYOVER_FROM_4_0_89.md"
      type: references
      weight: 1.0
lupopedia.footer:
  last_verified: "20260328250000"
  verified_by:
    identity_type: actor
    actor_id: 12
    agent_name_identity: "ATHENA"
    department_id_delta: 0
  verified_via:
    type: direct
    faucet_slug: none
  orchestrator: "athena:root"
  next_action:
    - "Update this PLAN when phases complete; no calendar-week commitments"
---

# Development plan — Version 4.0.90

**Living document:** Update when deliverables land. **No time estimates** — phases follow [`TASK_PLANNING_DOCTRINE.md`](../../doctrine/TASK_PLANNING_DOCTRINE.md).

---

## Phase 1 — Documentation clarity gates

**Completion criteria:** A.1–A.4 in `TODO.md` done or explicitly waived by WOLFIE.

**Concurrent with:** Early Phase 2 design only where no dependency on A.*

---

## Phase 2 — Context database foundation

**Completion criteria:** `lupo_contexts` (or approved equivalent) in install SQL + seed path; CRUD or service layer tests documented.

**Depends on:** Schema approval (may run parallel to Phase 1 after A.1 stabilizes navigation).

---

## Phase 3 — Context integration

**Completion criteria:** TASK_REGISTRY / Channel 66 / automation items in `TODO.md` B.* and D.* satisfied per doctrine.

**Depends on:** Phase 2 complete.

---

## Phase 4 — Crafty Syntax execution

**Completion criteria:** Prioritized backlog items from `4.0.89/crafty_syntax_backlog.md` implemented and regression-tested.

**Depends on:** Phase 1 clarity items that affect operator docs; may overlap Phase 3 after C.2 specs exist.

---

## Phase 5 — Hardening

**Completion criteria:** Performance and QA items D.*; release checklist for 4.0.90 tag.

---

## Phase 6 — Doctrine Enforcement & Guardrail Integration

**Completion criteria:**
- `lupo-rules/enforce_doctrine.py` created and integrated into all build/sync/test workflows.
- All schema/code changes must pass the validator before acceptance.
- LILITH audit protocol: Any violation triggers a hard-stop and red warning in Sync_Preview.html.

**Depends on:** Phases 1–5; enforcement is perpetual and must be maintained for all future versions.

---

## Appendix — Retrospective context from 4.0.89 (LILITH, 2026-03-28)

The following was written when **4.0.89** still contained a **broader** ATHENA plan (context + Crafty + docs). It remains valid **history** for why **4.0.90** planning must stay explicit:

### What the original 4.0.89 plan understated

| Missing emphasis | Actual deliverable |
|------------------|-------------------|
| Header DB-first | `HEADER_DB_FIRST_AND_DOCTRINE_CONSOLIDATION_4.0.89.md` |
| Binding headers doctrine | `lupo-rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md` |
| Database doctrine / registry removal | `DATABASE_DOCTRINE.md`, `CHANGELOG.md` |
| IdGenerator / WSL / manifesto | Root rules + `CHANGELOG.md` |

### Planned vs actual (approximate)

Original **Phase 1–4** (context/Crafty) **did not** run strictly in order; header and doctrine work **dominated** the cycle.

### Recommendation (still binding for 4.0.90)

1. Keep **`PLAN.md` living** — add rows when doctrine or schema lands.  
2. **Dependency order** only in phase descriptions.  
3. **Checklist** major artifacts against `CHANGELOG.md` before version lock.

**LILITH (actor_id 2)** — Appendix preserved from `4.0.89/PLAN.md` retrospective.

---

**ATHENA (actor_id 12)** — 4.0.90 plan opened 2026-03-28.

---

## 2026-03-29 — Schema/Seed/Actor Registry Refactor (HEPHAESTUS Protocol)

**Completed:**
- Refactored canonical actor/agent registry to be seeded in SQL (`seed_actors_agents_4.0.45.sql`), not JSON/TOON files.
- Added explicit, doctrine-compliant INSERTs for HEPHAESTUS (102) and LILITH (2) with canonical_identity JSON.
- Updated doctrine and documentation for the Faucet Proxy Pattern and HEPHAESTUS as canonical work agent.
- Registry/JSON outputs now reference only; SQL is authoritative.

**Next Steps:**
- Batch update all 100+ actors in the seed block if required.
- Verify doctrine/SQL sync and test install/seed process.
- Continue backlog execution per dependency order in TODO.md.

**Logged by CURSOR (actor_id 102)**
