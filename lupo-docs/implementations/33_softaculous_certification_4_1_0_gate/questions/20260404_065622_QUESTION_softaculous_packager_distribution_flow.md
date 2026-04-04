---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: implementation
  when_updated: "20260404065622"
  file_path_from_root: "lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/questions/20260404_065622_QUESTION_softaculous_packager_distribution_flow.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/questions/20260404_065622_QUESTION_softaculous_packager_distribution_flow.md"
  last_modified_utc: "20260404065622"
  federation_node_id: 0
  channel_id: 42
  thread_id: "33-softaculous-questions"
  actor_id: 1
  actor_name: wolfie
  parent_prd: "33_softaculous_certification_4_1_0_gate"
  artifact_type: "question"
  artifact_kind: "clarification"
  purpose: "Confirm Softaculous packager output, zip submission flow, and 4.1.0 readiness vs full PRD 33 gate"
  status: resolved
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/answers/20260404_065622_ANSWER_softaculous_packager_distribution_flow_lilith.md"
      type: resolved_by
      weight: 1.0
      reason: "LILITH (actor_id 2) — packager behavior and submission flow"
    - to: "lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md"
      type: references
      weight: 1.0
      reason: "PRD 33 — §9 Softaculous checklist, §10 completion criteria"
    - to: "lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/SOFTACULOUS_PACKAGE_BUILD.md"
      type: references
      weight: 1.0
      reason: "Packaging doctrine and validation commands"
    - to: "lupo-scripts/build_softaculous_package.sh"
      type: references
      weight: 1.0
      reason: "Canonical packager implementation"
---

# file: QUESTION — Softaculous packager and distribution flow — PRD 33

# QUESTION: Softaculous packager — what gets copied, and does that equal 4.1.0?

**Asked UTC:** `20260404065622` (**WOLFIE**, **actor_id 1**).

To test the hosting story, is the following understanding correct?

1. Run the **packager** so it turns the Lupopedia project into a **folder**, then **zips** it.
2. That **zip** goes to **Softaculous** (or similar); they **unzip**, run **install**, and if they **accept** the package and it runs on **shared hosts** they test, we can **green-light 4.1.0**.

More specifically: does the packager make a **full copy** of the **entire** Lupopedia tree **minus** installer-generated artifacts, **dotfiles**, and other non-runtime files — i.e. a **distribution** tree, not a git checkout?

## Resolution

**Answer:** `../answers/20260404_065622_ANSWER_softaculous_packager_distribution_flow_lilith.md`

This file complies with Lupopedia Constitutional Root Rules.
