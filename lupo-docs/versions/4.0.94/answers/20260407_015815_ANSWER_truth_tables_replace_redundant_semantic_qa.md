---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  version_when_written: "4.0.94"
  when_updated: "20260407015813"
  file_path_from_root: "lupo-docs/versions/4.0.94/answers/20260407_015815_ANSWER_truth_tables_replace_redundant_semantic_qa.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.94/answers/20260407_015815_ANSWER_truth_tables_replace_redundant_semantic_qa.md"
  last_modified_utc: "20260407015813"
  federation_node_id: 0
  channel_id: 42
  thread_id: "version-4.0.94-answers"
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "documentation"
  artifact_kind: "answer"
  purpose: "Answer — truth_* tables are canonical; old questions/answers were redundant"
  tags: ["answers", "4.0.94", "schema", "truth"]
lupopedia.footer:
  last_verified: "20260407015813"
  verified_by:
    identity_type: actor
    actor_id: 102
  orchestrator: "cursor:root"
---

# file: lupo-docs/versions/4.0.94/answers/20260407_015815_ANSWER_truth_tables_replace_redundant_semantic_qa.md — delegation: cursor:root

# ANSWER — `truth_*` tables replace the removed semantic Q/A trio

**Question ref:** [20260407_015814_QUESTION_what_replaced_lupo_questions_answers.md](../questions/20260407_015814_QUESTION_what_replaced_lupo_questions_answers.md)

**Answered (UTC):** `20260407_015815` (batch anchor `20260407015813`)

## HOW (short)

- **`schema_corrected_core.sql` SECTION 20** states that `lupo_questions`, `lupo_answers`, and `lupo_question_map` are redundant and subsumed by the **truth / assertion** model.
- The canonical install **already defined** (before and after merge) these tables with `{{prefix}}`:
  - `truth_questions`
  - `truth_answers`
  - `truth_evidence`
  - `truth_context_map`
  - `truth_followers`
- The merge **removed** the parallel **`questions` / `answers` / `question_map`** `CREATE TABLE` block; it did **not** introduce the `truth_*` set for the first time.

## WHY

One Q/A stack avoids duplicate semantics and drift between “semantic Q/A” and “truth” tables.

## Follow-up

Any code or SQL still targeting `questions` / `answers` / `question_map` must be migrated to **`truth_*`** column semantics or explicitly reintroduce the old tables (not recommended per schema review).
