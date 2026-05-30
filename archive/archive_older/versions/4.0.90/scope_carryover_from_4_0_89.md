---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: report
  when_updated: "20260328250000"
  file_path_from_root: "docs/versions/4.0.90/SCOPE_CARRYOVER_FROM_4_0_89.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.90/SCOPE_CARRYOVER_FROM_4_0_89.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: report
  artifact_kind: status
  thread_id: "4-0-90-scope-carryover"
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# Scope carryover — 4.0.89 → 4.0.90

**Date:** 2026-03-28  

Version **4.0.89** is **refocused** for release on **LUPOPEDIA HEADERS** only: validation (Python + PHP + eventual admin UX), **import/regenerate** into `lupo_contents` / `lupo_metadata` / `lupo_edges`, **`*` tree rules**, **`rules/root`** alignment with IDE rule packs, and **schema/docs** needed for that pipeline.

Everything below moved to **4.0.90** as the active product backlog (see **`README.md`**, **`TODO.md`**, **`PLAN.md`** in this folder):

| Area | Former home (4.0.89) | Now |
|------|----------------------|-----|
| Context model DB (`lupo_contexts`, edges, automation) | TODO P1, PLAN Phases 1–2 | 4.0.90 TODO / PLAN |
| Crafty Syntax parity execution | TODO P2, PLAN Phase 3 | 4.0.90 TODO / PLAN |
| System optimization / QA metrics (non-header) | TODO P3–P4, PLAN Phase 4 | 4.0.90 TODO / PLAN |
| Documentation clarity 5.1–5.4 (nav, FLARE cleanup, IMPLEMENTATION_GETTING_STARTED) | TODO P5.1–5.4 | 4.0.90 TODO |
| Week-style execution detail + resource/risk sections | `4.0.89/PLAN.md` (pre-refocus body) | Preserved in git history; 4.0.90 `PLAN.md` uses **dependency order** only |

**Unchanged:** Header pipeline deliverables (binding doctrine, scripts, validators) remain **4.0.89** artifacts; see `docs/versions/4.0.89/README.md` and `HEADER_DB_FIRST_AND_DOCTRINE_CONSOLIDATION_4.0.89.md`.
