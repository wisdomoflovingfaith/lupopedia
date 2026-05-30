---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: implementation
  when_updated: "20260404065622"
  file_path_from_root: "lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/questions/20260404_065622_QUESTION_softaculous_packager_distribution_flow.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/questions/20260404_065622_QUESTION_softaculous_packager_distribution_flow.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: question
  artifact_kind: clarification
  thread_id: "33-softaculous-questions"
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: "resolved"
  parent_pk_id: "33_softaculous_certification_4_1_0_gate"
  summary: ""
  module: null
  dialog_transcript: null
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
