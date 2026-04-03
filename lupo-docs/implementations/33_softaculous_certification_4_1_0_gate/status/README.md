---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: implementation
  when_updated: "20260403022300"
  file_path_from_root: "lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/status/README.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/status/README.md"
  federation_node_id: 0
  channel_id: 42
  thread_id: "33-softaculous-status-readme"
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  parent_prd: "33_softaculous_certification_4_1_0_gate"
  artifact_type: "implementation"
  artifact_kind: "documentation"
  purpose: "Explain status/ vs decisions/ for PRD 33 implementation folder"
  tags:
    - "implementation"
    - "status"
    - "prd33"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/decisions/DECISION_INDEX.md"
      type: references
      weight: 0.9
      reason: "Formal decisions index"
---

# `status/` — review snapshots and operational state

## When to use **`status/`**

Place material here when it is:

- A **reviewer report** or **audit import** (e.g. LILITH PRD review — accuracy score, findings, APPROVED verdict).
- A **rolling status** note (what is blocked, what is next) that is **not** yet a formal **`DECISION_*`** artifact.
- **Snapshots** copied from chat or tools for traceability, with pointers to the canonical PRD section.

## When to use **`../decisions/`** instead

Use **`decisions/`** when the orchestrator publishes a **formal, approved** artifact that **governs** behavior (architecture choice, APPROVED deferral, WOLFIE ratification of the gate). Those files follow **`YYYYMMDD_HHIISS_DECISION_*`** naming and are listed in **`DECISION_INDEX.md`**.

**Example:** LILITH’s “PRD text is APPROVED” lives in **`status/`** (and **PRD Section 13**). A future **“WOLFIE APPROVED: adopt PRD 33 as 4.1.0 gate”** could be filed as **`decisions/2026…_DECISION_…md`** if product wants a separate decision file in addition to changing the PRD header `status`.

## Files

| File | Description |
|------|-------------|
| [20260403_022238_STATUS_LILITH_PRD33_audit_approved.md](20260403_022238_STATUS_LILITH_PRD33_audit_approved.md) | LILITH audit record (import); canonical summary remains in PRD **Section 13** |

---

This file complies with Lupopedia Constitutional Root Rules.
