---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260403022543"
  file_path_from_root: "docs/versions/4.0.94/decisions/20260403_022543_DECISION_APPROVED_prd33_softaculous_gate_documentation.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.94/decisions/20260403_022543_DECISION_APPROVED_prd33_softaculous_gate_documentation.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: documentation
  artifact_kind: decision_record
  thread_id: "version-4.0.94-decisions"
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: "approved"
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# DECISION (APPROVED): PRD 33 — Softaculous / 4.1.0 release gate (documentation approved)

| Field | Value |
|-------|--------|
| **WHO** | Cursor IDE agent (`actor_id` **102**), per repo delegation |
| **WHAT** | **(1)** Set **`docs/prd/33_softaculous_certification_4_1_0_gate.md`** **`lupopedia.headers.status`** to **`approved`**. **(2)** Record **4.0.94** version documentation sync (`CHANGELOG`, `PLAN`, `TODO`, `edges`, Q/A/C). **(3)** Tie **§12** traceability to **`TODO.md`** and this decision. |
| **WHERE** | `docs/prd/33_softaculous_certification_4_1_0_gate.md`; `docs/implementations/33_softaculous_certification_4_1_0_gate/`; `docs/versions/4.0.94/` (this file and siblings). |
| **WHEN** | **20260403022543** UTC (artifact filenames **`20260403_022543`** … **`20260403_022546`**). |
| **WHY** | Preserve **LILITH** audit lineage (**§13**), lock **normative gate text** for **4.1.0** hoster certification, and keep **checklist execution** explicitly separate from “PRD text approved.” |
| **HOW** | PRD header **`status: approved`** + **§13** paragraph update; new **QUESTION** / **ANSWER** on traceability location; **COMMENT** receipt; **`CHANGELOG.md`** 5W1H block; **`PLAN.md`** Phase D split (doc vs execution); **`TODO.md`** completed doc line + open execution line; **`edges.md`** graph update. |

## Outcomes (APPROVED)

1. **PRD 33** is **approved as documentation** — not a claim that every **§7–§10** checklist row is **implemented** in product/installer.
2. **Implementation workspace** remains the structured hub: **`docs/implementations/33_softaculous_certification_4_1_0_gate/`** (template lineage, **`status/`** for audit imports, **`decisions/`** for ratified files).
3. **Version folder** records this thread under **`decisions/`**, **`questions/`**, **`answers/`**, **`comments/`**, with **`THREAD_INDEX.md`** updates.

## Scope limits

- **Does not** mark Softaculous **certification execution** or **4.1.0** product ship — only **gate PRD** and **version-doc** alignment.
- **Does not** assert changes to **`validate_implementation.py`**, universal header validator, **PRD 16 / 26 / 30 / 31** rewrites, or **PK** constitutional edits unless those appear in the same thread with evidence — **not** claimed here.

This output complies with Lupopedia Constitutional Root Rules.
