---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  file_path_from_root: "lupo-docs/versions/4.0.94/answers/20260403_140554_ANSWER_version_ghost_cleanup_manual_review.md"
  when_updated: "20260403140552"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: answer
  status: answered
  purpose: "Resolve ghost cleanup QUESTION — manual review per file"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/versions/4.0.94/questions/20260403_140553_QUESTION_version_ghost_cleanup_policy.md"
      type: answers
      weight: 1.0
      reason: "Answers QUESTION 20260403_140553"
---

# file: ANSWER — version ghost cleanup

# ANSWER: Manual review per file (no single batch policy)

## Decision

Each of the **34** files in the critical set requires **manual** review. **No** repo-wide automated rewrite without WOLFIE approval per file.

## Guidelines (non-exhaustive)

| Finding | Action |
|---------|--------|
| Phantom **`/docs/`**-style path in prose | Replace with **`lupo-docs/...`** or archive pointer |
| **`3.0.x`** reference | Keep if historical; otherwise rephrase or move under `lupo-docs/versions/3.0.x/` |
| Deprecated header block | Migrate per **LUPOPEDIA HEADERS** doctrine |

## Owner

Product/orchestrator (**WOLFIE**, `actor_id` **1**) prioritizes fixes; IDE agents **integrate** edits when directed.
