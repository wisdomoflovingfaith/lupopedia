---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260402235141"
  file_path_from_root: "lupo-docs/versions/4.0.94/answers/20260402_234553_ANSWER_ide_facet_version_doc_scope.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.94/answers/20260402_234553_ANSWER_ide_facet_version_doc_scope.md"
  last_modified_utc: "20260402235141"
  federation_node_id: 0
  channel_id: 42
  thread_id: "version-4.0.94-answers"
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "documentation"
  artifact_kind: "answer_record"
  purpose: "Answer — version 4.0.94 changelog and indexes must not claim work from a template that was not executed in-thread"
  tags:
    - "4.0.94"
    - "answers"
lupopedia.edges:
  outbound_edges:
    - to: "../questions/20260402_234552_QUESTION_ide_facet_version_doc_scope.md"
      type: answers
      weight: 1.0
      reason: "Resolves IDE facet version-doc scope question"
    - to: "../decisions/20260402_234551_DECISION_APPROVED_ide_facet_packs_vscode_propagation.md"
      type: references
      weight: 1.0
      reason: "APPROVED record for same thread scope boundary"
lupopedia.footer:
  last_verified: "20260402235141"
  verified_by:
    identity_type: "actor"
    actor_id: 102
  orchestrator: "cursor:root"
---

# ANSWER: Yes — thread-verified scope only

**Answered (UTC):** `20260402_234553`

**Yes.** The `4.0.94` version documentation must list **only** outcomes verified for the thread that performed them. A directive template listing PRD 16/26/30/31, validator enhancements, or new constitutional PK rules must **not** be copied into `CHANGELOG.md` / `PLAN.md` / `TODO.md` unless those edits exist in the repo **from that thread**.

For this thread, the verifiable scope is: **IDE facet packs**, **actor hubs**, **`--target=vscode`**, **`AGENTS.md` / `AGENT_REGISTRY.md` / `_shared/README.md`**, **`validate_actor_identity.py`**, and **registry JSON** alignment as already applied.

This file complies with Lupopedia Constitutional Root Rules.
