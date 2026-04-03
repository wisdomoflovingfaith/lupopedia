---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260403022545"
  file_path_from_root: "lupo-docs/versions/4.0.94/answers/20260403_022545_ANSWER_prd33_traceability_location.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.94/answers/20260403_022545_ANSWER_prd33_traceability_location.md"
  last_modified_utc: "20260403022545"
  federation_node_id: 0
  channel_id: 42
  thread_id: "version-4.0.94-answers"
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "documentation"
  artifact_kind: "answer_record"
  purpose: "Resolve PRD 33 §12 traceability anchoring for 4.0.94"
  tags:
    - "4.0.94"
    - "prd_33"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/versions/4.0.94/questions/20260403_022544_QUESTION_prd33_traceability_location.md"
      type: answers
      weight: 1.0
      reason: "Question ref"
    - to: "lupo-docs/versions/4.0.94/decisions/20260403_022543_DECISION_APPROVED_prd33_softaculous_gate_documentation.md"
      type: references
      weight: 1.0
      reason: "APPROVED doc state for PRD 33 + version sync"
lupopedia.footer:
  last_verified: "20260403022545"
  verified_by:
    identity_type: "actor"
    actor_id: 102
  orchestrator: "cursor:root"
---

# ANSWER: PRD 33 §12 — traceability for 4.0.94

**Primary backlog:** **`lupo-docs/versions/4.0.94/TODO.md`** — use a dedicated subsection (e.g. **PRD 33 / Softaculous / 4.1.0 gate**) so each checklist line can cite **§** references, **`actor_id`** owner, and **evidence** paths with **BIGINT UTC** stamps per PRD **§12**.

**Implementation hub:** **`lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/`** — holds **`status/`** (audit imports), typed **`decisions/`** / **`questions/`** / **`answers/`** / **`comments/`** per scaffold; use for **ratified** artifacts and long-form evidence **in addition to** the version **`TODO.md`** lines (not instead of them, unless an **APPROVED** decision explicitly moves the canonical pointer).

**PRD:** **`lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md`** remains the **normative** gate text; **`status: approved`** does not replace per-row completion in **`TODO.md`**.

This output complies with Lupopedia Constitutional Root Rules.
