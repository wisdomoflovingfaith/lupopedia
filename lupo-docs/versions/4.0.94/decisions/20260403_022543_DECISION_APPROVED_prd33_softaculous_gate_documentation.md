---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260403022543"
  file_path_from_root: "lupo-docs/versions/4.0.94/decisions/20260403_022543_DECISION_APPROVED_prd33_softaculous_gate_documentation.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.94/decisions/20260403_022543_DECISION_APPROVED_prd33_softaculous_gate_documentation.md"
  last_modified_utc: "20260403022543"
  federation_node_id: 0
  channel_id: 42
  thread_id: "version-4.0.94-decisions"
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "documentation"
  artifact_kind: "decision_record"
  purpose: "Record APPROVED documentation state for PRD 33 Softaculous / 4.1.0 gate and 4.0.94 version-doc sync"
  status: "approved"
  tags:
    - "4.0.94"
    - "decision"
    - "prd_33"
    - "softaculous"
    - "4.1.0_gate"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md"
      type: references
      weight: 1.0
      reason: "Gate PRD — header status approved; §12 traceability; §13 audit record"
    - to: "lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/README.md"
      type: references
      weight: 1.0
      reason: "Implementation workspace scaffold + status/ audit imports"
    - to: "lupo-rules/root/lilith-noninterference-doctrine.md"
      type: references
      weight: 0.9
      reason: "LIL001 — LILITH review scope in PRD §13"
    - to: "lupo-docs/versions/4.0.94/TODO.md"
      type: references
      weight: 1.0
      reason: "Checklist execution backlog per PRD §12"
lupopedia.footer:
  last_verified: "20260403022543"
  verified_by:
    identity_type: "actor"
    actor_id: 102
  orchestrator: "cursor:root"
---

# DECISION (APPROVED): PRD 33 — Softaculous / 4.1.0 release gate (documentation approved)

| Field | Value |
|-------|--------|
| **WHO** | Cursor IDE agent (`actor_id` **102**), per repo delegation |
| **WHAT** | **(1)** Set **`lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md`** **`lupopedia.headers.status`** to **`approved`**. **(2)** Record **4.0.94** version documentation sync (`CHANGELOG`, `PLAN`, `TODO`, `edges`, Q/A/C). **(3)** Tie **§12** traceability to **`TODO.md`** and this decision. |
| **WHERE** | `lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md`; `lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/`; `lupo-docs/versions/4.0.94/` (this file and siblings). |
| **WHEN** | **20260403022543** UTC (artifact filenames **`20260403_022543`** … **`20260403_022546`**). |
| **WHY** | Preserve **LILITH** audit lineage (**§13**), lock **normative gate text** for **4.1.0** hoster certification, and keep **checklist execution** explicitly separate from “PRD text approved.” |
| **HOW** | PRD header **`status: approved`** + **§13** paragraph update; new **QUESTION** / **ANSWER** on traceability location; **COMMENT** receipt; **`CHANGELOG.md`** 5W1H block; **`PLAN.md`** Phase D split (doc vs execution); **`TODO.md`** completed doc line + open execution line; **`edges.md`** graph update. |

## Outcomes (APPROVED)

1. **PRD 33** is **approved as documentation** — not a claim that every **§7–§10** checklist row is **implemented** in product/installer.
2. **Implementation workspace** remains the structured hub: **`lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/`** (template lineage, **`status/`** for audit imports, **`decisions/`** for ratified files).
3. **Version folder** records this thread under **`decisions/`**, **`questions/`**, **`answers/`**, **`comments/`**, with **`THREAD_INDEX.md`** updates.

## Scope limits

- **Does not** mark Softaculous **certification execution** or **4.1.0** product ship — only **gate PRD** and **version-doc** alignment.
- **Does not** assert changes to **`validate_implementation.py`**, universal header validator, **PRD 16 / 26 / 30 / 31** rewrites, or **PK** constitutional edits unless those appear in the same thread with evidence — **not** claimed here.

This output complies with Lupopedia Constitutional Root Rules.
