---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260407015813"
  file_path_from_root: "docs/versions/4.0.94/questions/20260407_015814_QUESTION_what_replaced_lupo_questions_answers.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.94/questions/20260407_015814_QUESTION_what_replaced_lupo_questions_answers.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: documentation
  artifact_kind: question
  thread_id: "version-4.0.94-questions"
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# file: docs/versions/4.0.94/questions/20260407_015814_QUESTION_what_replaced_lupo_questions_answers.md — delegation: cursor:root

# QUESTION — What replaced `lupo_questions` / `lupo_answers` / `lupo_question_map`?

**Opened (UTC):** `20260407_015814` (batch anchor `20260407015813`)

**WHO:** Human orchestrator via Cursor thread.

**WHERE:** `install_new_lupopedia.sql` after merge with `schema_corrected_core.sql` SECTION 20 (deprecated remove).

**WHAT:** The install no longer creates `{{prefix}}questions`, `{{prefix}}answers`, or `{{prefix}}question_map`. What is the supported replacement surface for Q/A–style semantics?

**WHY:** Avoid silent breakage when searching for old table names in code or docs.

**Status:** Resolved — see paired ANSWER.
