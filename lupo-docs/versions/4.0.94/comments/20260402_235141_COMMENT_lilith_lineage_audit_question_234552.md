---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260402235141"
  file_path_from_root: "lupo-docs/versions/4.0.94/comments/20260402_235141_COMMENT_lilith_lineage_audit_question_234552.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.94/comments/20260402_235141_COMMENT_lilith_lineage_audit_question_234552.md"
  last_modified_utc: "20260402235141"
  federation_node_id: 0
  channel_id: 42
  thread_id: "version-4.0.94-comments"
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "documentation"
  artifact_kind: "comment"
  purpose: "Receipt — LILITH audit noted missing QUESTION→ANSWER header edge; corrected to match PRD 17 sibling pair pattern (has_answer / answers)"
lupopedia.edges:
  outbound_edges:
    - to: "../questions/20260402_234552_QUESTION_ide_facet_version_doc_scope.md"
      type: references
      weight: 1.0
      reason: "Question under audit"
    - to: "../answers/20260402_234553_ANSWER_ide_facet_version_doc_scope.md"
      type: references
      weight: 1.0
      reason: "Resolving answer artifact"
lupopedia.footer:
  last_verified: "20260402235141"
  verified_by:
    identity_type: "actor"
    actor_id: 102
  orchestrator: "cursor:root"
---

# COMMENT: LILITH lineage audit — QUESTION `20260402_234552`

**Posted (UTC):** `20260402_235141`

**LILITH audit finding (actor_id 2, reviewer):** Resolved QUESTION lacked an explicit **`lupopedia.edges`** outbound link of type **`has_answer`** to the ANSWER file; resolution was therefore not machine-traceable in the same way as [20260402_225224_QUESTION_version_doc_thread_scope.md](../questions/20260402_225224_QUESTION_version_doc_thread_scope.md).

**Corrective action (Cursor, actor_id 102):**

- Added **`has_answer`** on the QUESTION → [../answers/20260402_234553_ANSWER_ide_facet_version_doc_scope.md](../answers/20260402_234553_ANSWER_ide_facet_version_doc_scope.md).
- Normalized the ANSWER reverse edge to **`answers`** with a **relative** `../questions/...` path (same pattern as `225225`).
- Linked the ANSWER to the APPROVED decision [../decisions/20260402_234551_DECISION_APPROVED_ide_facet_packs_vscode_propagation.md](../decisions/20260402_234551_DECISION_APPROVED_ide_facet_packs_vscode_propagation.md).

**Note:** This repository uses **in-file** `lupopedia.edges` blocks for Q→A lineage under `questions/` and `answers/`; there is **no** separate `versions/4.0.94/edges/` directory in the established layout. A standalone edge markdown file was **not** added to avoid parallel lineage systems.

**Authorship:** The ANSWER text remains authored by **Cursor** (`actor_id` **102**) in-thread; this comment records **LILITH’s** audit only (LIL001 — non-interfering review).

This file complies with Lupopedia Constitutional Root Rules.
