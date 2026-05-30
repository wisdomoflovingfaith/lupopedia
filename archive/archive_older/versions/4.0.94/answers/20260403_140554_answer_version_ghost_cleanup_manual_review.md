---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260403140552"
  file_path_from_root: "docs/versions/4.0.94/answers/20260403_140554_ANSWER_version_ghost_cleanup_manual_review.md"
  web_path: null
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: answer
  artifact_kind: null
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: "answered"
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# file: ANSWER — version ghost cleanup

# ANSWER: Manual review per file (no single batch policy)

## Decision

Each of the **34** files in the critical set requires **manual** review. **No** repo-wide automated rewrite without WOLFIE approval per file.

## Guidelines (non-exhaustive)

| Finding | Action |
|---------|--------|
| Phantom **`/docs/`**-style path in prose | Replace with **`docs/...`** or archive pointer |
| **`3.0.x`** reference | Keep if historical; otherwise rephrase or move under `docs/versions/3.0.x/` |
| Deprecated header block | Migrate per **LUPOPEDIA HEADERS** doctrine |

## Owner

Product/orchestrator (**WOLFIE**, `actor_id` **1**) prioritizes fixes; IDE agents **integrate** edits when directed.
