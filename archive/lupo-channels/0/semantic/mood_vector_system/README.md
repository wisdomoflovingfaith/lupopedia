---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: thread
  when_updated: "20260404190000"
  file_path_from_root: "lupo-channels/0/semantic/mood_vector_system/README.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-channels/0/semantic/mood_vector_system/README.md"
  questions_toon: null
  federation_node_id: 0
  channel_id: 0
  thread_id: "mood-vector-system"
  actor_id: 102
  actor_name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "thread"
  artifact_kind: "coordination"
  purpose: "Mood Vector system — semantic state vector, evidence, integration with truth/knowledge and semantic engine"
  tags:
    - "mood_vector"
    - "semantic"
    - "channels"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/doctrine/MOOD_VECTOR_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Root doctrine summary"
    - to: "lupo-docs/doctrine/COUNTING_IN_LIGHT.md"
      type: references
      weight: 0.95
      reason: "Axis vocabulary for mood_vector"
lupopedia.footer:
  last_verified: "20260404190000"
  verified_by:
    identity_type: "actor"
    actor_id: 102
  orchestrator: "cursor:root"
---

# file: mood_vector_system — delegation: cursor:root

# Thread: Mood Vector system

**Channel:** `semantic` (Semantic & Knowledge Systems) · **federation_node_id:** `0` · **Header `thread_id`:** `mood-vector-system` (validator-safe) · **On-disk folder / `thread_key`:** `mood_vector_system`

This thread is the **canonical on-disk home** for Mood Vector work: mapping, evidence, integration with the semantic engine / truth graph / TOON schema, and decision history.

- **`decisions/`** — approved decisions (evidence sources, color/token definitions)
- **`questions/`** — open questions
- **`answers/`** — answers
- **`comments/`** — notes (e.g. archive cross-references)

The short **root doctrine** lives at **`lupo-docs/doctrine/MOOD_VECTOR_DOCTRINE.md`** (summary + pointers here).

Filename pattern (UTC): **`YYYYMMDD_HHIISS_TYPE_TITLE.md`**; under **`decisions/`** use **`DECISION_STATUS_TITLE`**. See **`lupo-docs/prd/17_decisions_format.md`**.
