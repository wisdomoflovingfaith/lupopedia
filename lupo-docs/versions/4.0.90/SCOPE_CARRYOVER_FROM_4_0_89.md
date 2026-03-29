---
lupopedia.headers:
  lupopedia.schema: report
  file_path_from_root: "lupo-docs/versions/4.0.90/SCOPE_CARRYOVER_FROM_4_0_89.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.90/SCOPE_CARRYOVER_FROM_4_0_89.md"
  federation_node_id: 0
  last_modified_utc: "20260328250000"
  when_updated: "20260328250000"
  channel_id: 42
  thread_id: "4-0-90-scope-carryover"
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: report
  artifact_kind: status
  purpose: Record what moved out of 4.0.89 when release scope was limited to LUPOPEDIA HEADERS
  tags:
    - "4.0.90"
    - scope
    - carryover
lupopedia.footer:
  last_verified: "20260328250000"
  verified_by:
    identity_type: actor
    actor_id: 102
    agent_name_identity: "Cursor IDE Agent (Lead Orchestration)"
    department_id_delta: 0
  verified_via:
    type: faucet
    faucet_slug: cursor
  orchestrator: "cursor:root"
  next_action:
    - "Keep 4.0.90 README/TODO/PLAN as live backlog for non-header work"
---

# Scope carryover — 4.0.89 → 4.0.90

**Date:** 2026-03-28  

Version **4.0.89** is **refocused** for release on **LUPOPEDIA HEADERS** only: validation (Python + PHP + eventual admin UX), **import/regenerate** into `lupo_contents` / `lupo_metadata` / `lupo_edges`, **`lupo-*` tree rules**, **`lupo-rules/root`** alignment with IDE rule packs, and **schema/docs** needed for that pipeline.

Everything below moved to **4.0.90** as the active product backlog (see **`README.md`**, **`TODO.md`**, **`PLAN.md`** in this folder):

| Area | Former home (4.0.89) | Now |
|------|----------------------|-----|
| Context model DB (`lupo_contexts`, edges, automation) | TODO P1, PLAN Phases 1–2 | 4.0.90 TODO / PLAN |
| Crafty Syntax parity execution | TODO P2, PLAN Phase 3 | 4.0.90 TODO / PLAN |
| System optimization / QA metrics (non-header) | TODO P3–P4, PLAN Phase 4 | 4.0.90 TODO / PLAN |
| Documentation clarity 5.1–5.4 (nav, FLARE cleanup, IMPLEMENTATION_GETTING_STARTED) | TODO P5.1–5.4 | 4.0.90 TODO |
| Week-style execution detail + resource/risk sections | `4.0.89/PLAN.md` (pre-refocus body) | Preserved in git history; 4.0.90 `PLAN.md` uses **dependency order** only |

**Unchanged:** Header pipeline deliverables (binding doctrine, scripts, validators) remain **4.0.89** artifacts; see `lupo-docs/versions/4.0.89/README.md` and `HEADER_DB_FIRST_AND_DOCTRINE_CONSOLIDATION_4.0.89.md`.
