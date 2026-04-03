---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  file_path_from_root: "lupo-docs/versions/4.0.94/decisions/20260403_140552_DECISION_APPROVED_doctrine_audit_mobile_separation_docs.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.94/decisions/20260403_140552_DECISION_APPROVED_doctrine_audit_mobile_separation_docs.md"
  when_updated: "20260403140552"
  last_modified_utc: "20260403140552"
  channel_id: 42
  actor_id: 2
  actor_name: "lilith"
  delegation_chain: "lilith:audit"
  artifact_type: decision
  artifact_kind: approval
  purpose: "Record APPROVED documentation outcomes for doctrine audit tooling, mobile separation, and WOLFIE workflow doctrines (Cursor thread)"
  status: approved
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/doctrine/MOBILE_SEPARATION_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Two-UI, admin exception, Eye widget desktop vs mobile"
    - to: "lupo-docs/doctrine/WOLFIE_WORKFLOW_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Mobile-first consumer; desktop-first admin; build order"
    - to: "lupo-docs/prd/35_mobile_native_app_separation.md"
      type: references
      weight: 0.95
      reason: "Draft PRD — native operator app; complements mobile web split"
lupopedia.footer:
  last_verified: "20260403140552"
  verified_by:
    identity_type: actor
    actor_id: 2
    name: "lilith"
---

# file: DECISION — APPROVED doctrine audit + mobile separation documentation — web_path: /lupo-docs/versions/4.0.94/decisions/20260403_140552_DECISION_APPROVED_doctrine_audit_mobile_separation_docs.md

# DECISION (APPROVED): Doctrine audit artifacts + mobile / workflow doctrines (documentation)

## 5W1H (thread-verified)

| Field | Value |
|-------|--------|
| **WHO** | Implementation: Cursor (`actor_id` **102**). Audit framing: LILITH (`actor_id` **2**). Orchestrator: WOLFIE (`actor_id` **1**). |
| **WHAT** | Documented: **MOBILE_SEPARATION_DOCTRINE.md** (Two-UI, admin desktop-first exception, Eye PRD 28 split); **WOLFIE_WORKFLOW_DOCTRINE.md** (consumer mobile-first / admin desktop-first); **PRD 35** draft; **PRD 33** §7.4 mobile checklist + edges; **AGENTS.md** workflow sections; **LESSONS_LEARNED_FROM_THE_WILD_WEST.md** hand-coding doctrine (section 7) and UI-framework note. Ran **`python lupo-scripts/audit_doctrine_prd_edges.py`** → **189** doctrine files with PRD lineage edges (**0** missing). **`lupo-docs/implementations/29_project_structure/status/version_ghosts_report.json`**: **34** files with **critical** ghost findings (scanner: **`lupo-scripts/find_version_ghosts.py`**). |
| **WHERE** | `lupo-docs/doctrine/`, `lupo-docs/prd/`, `AGENTS.md`, `lupo-docs/LESSONS_LEARNED_FROM_THE_WILD_WEST.md`, `lupo-scripts/` (audit + ghost tools). |
| **WHEN** | Header batch UTC **`20260403140552`** (real UTC via `python lupo-bin/tick.py`). |
| **WHY** | Lock UX/build-order doctrine for IDE agents; keep PRD lineage complete; track ghost cleanup backlog without inventing batch repair counts. |
| **HOW** | Markdown doctrines + version-folder **CHANGELOG** / **edges** / **TODO** sync; no install SQL or runtime PHP changes in this decision scope. |

## Explicitly NOT claimed here

- **No** verified row counts for **`fix_doctrine_headers.py`**, **`apply_doctrine_prd_lineage.py`**, or **`convert_wolfie_to_lupo.py`** runs in this thread — scripts **exist** under **`lupo-scripts/`**; quantify only when a thread records measured output.
- **PRD 34** federation semantic network: **draft** exists under `lupo-docs/prd/` from prior work — not “created” in this thread.
- **Softaculous certification execution** (PRD 33 §7–§10) remains **open** — documentation-only approvals do not close product checklists.

## Status

**APPROVED** for **documentation** and **agent guidance** alignment. Remaining ghost files: **manual** review (see **ANSWER** `20260403_140554`).
