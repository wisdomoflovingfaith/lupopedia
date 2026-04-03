---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: thread
  when_updated: "20260404190000"
  file_path_from_root: "lupo-channels/0/semantic/mood_rgb_system/README.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-channels/0/semantic/mood_rgb_system/README.md"
  last_modified_utc: "20260404190000"
  federation_node_id: 0
  channel_id: 0
  thread_id: "mood-rgb-system"
  actor_id: 102
  actor_name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "thread"
  artifact_kind: "coordination"
  purpose: "Mood RGB system — semantic state vector, evidence, integration with truth/knowledge and semantic engine"
  tags:
    - "mood_rgb"
    - "semantic"
    - "channels"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/doctrine/MOOD_RGB_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Root doctrine summary"
    - to: "lupo-docs/doctrine/COUNTING_IN_LIGHT.md"
      type: references
      weight: 0.95
      reason: "Axis vocabulary for mood_rgb"
lupopedia.footer:
  last_verified: "20260404190000"
  verified_by:
    identity_type: "actor"
    actor_id: 102
  orchestrator: "cursor:root"
---

# file: mood_rgb_system — delegation: cursor:root

# Thread: Mood RGB system

**Channel:** `semantic` (Semantic & Knowledge Systems) · **federation_node_id:** `0` · **Header `thread_id`:** `mood-rgb-system` (validator-safe) · **On-disk folder / `thread_key`:** `mood_rgb_system`

This thread is the **canonical on-disk home** for Mood RGB work: mapping, evidence, integration with the semantic engine / truth graph / TOON schema, and decision history.

- **`decisions/`** — approved decisions (evidence sources, color/token definitions)
- **`questions/`** — open questions
- **`answers/`** — answers
- **`comments/`** — notes (e.g. archive cross-references)

The short **root doctrine** lives at **`lupo-docs/doctrine/MOOD_RGB_DOCTRINE.md`** (summary + pointers here).

Filename pattern (UTC): **`YYYYMMDD_HHIISS_TYPE_TITLE.md`**; under **`decisions/`** use **`DECISION_STATUS_TITLE`**. See **`lupo-docs/prd/17_decisions_format.md`**.
