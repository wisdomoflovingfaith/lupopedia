---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  version_when_written: "4.0.94"
  when_updated: "20260407015813"
  file_path_from_root: "lupo-docs/versions/4.0.94/questions/20260407_015814_QUESTION_what_replaced_lupo_questions_answers.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.94/questions/20260407_015814_QUESTION_what_replaced_lupo_questions_answers.md"
  last_modified_utc: "20260407015813"
  federation_node_id: 0
  channel_id: 42
  thread_id: "version-4.0.94-questions"
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "documentation"
  artifact_kind: "question"
  purpose: "Clarify replacement for removed questions/answers/question_map tables after install merge"
  tags: ["questions", "4.0.94", "schema", "truth"]
lupopedia.footer:
  last_verified: "20260407015813"
  verified_by:
    identity_type: actor
    actor_id: 102
  orchestrator: "cursor:root"
---

# file: lupo-docs/versions/4.0.94/questions/20260407_015814_QUESTION_what_replaced_lupo_questions_answers.md — delegation: cursor:root

# QUESTION — What replaced `lupo_questions` / `lupo_answers` / `lupo_question_map`?

**Opened (UTC):** `20260407_015814` (batch anchor `20260407015813`)

**WHO:** Human orchestrator via Cursor thread.

**WHERE:** `install_new_lupopedia.sql` after merge with `schema_corrected_core.sql` SECTION 20 (deprecated remove).

**WHAT:** The install no longer creates `{{prefix}}questions`, `{{prefix}}answers`, or `{{prefix}}question_map`. What is the supported replacement surface for Q/A–style semantics?

**WHY:** Avoid silent breakage when searching for old table names in code or docs.

**Status:** Resolved — see paired ANSWER.
