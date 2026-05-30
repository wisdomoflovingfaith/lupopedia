---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260403222043"
  file_path_from_root: "docs/versions/4.0.94/answers/20260403_222043_ANSWER_department_model_visitor_chat_docs_synthesis.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.94/answers/20260403_222043_ANSWER_department_model_visitor_chat_docs_synthesis.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: answer
  artifact_kind: synthesis
  thread_id: "version-4-0-94-answers"
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
# file: ANSWER — department model + visitor chat docs synthesis — web_path: /docs/versions/4.0.94/answers/20260403_222043_ANSWER_department_model_visitor_chat_docs_synthesis.md

# ANSWER: Department-first model + visitor chat — documentation synthesis

## Decision (thread-verified)

The **department-first actor model** and **actor-first visitor chat** documentation set are **APPROVED** for **4.0.x** normative prose (LILITH audits on listed PRDs; decision **`20260403_222041_DECISION_APPROVED_department_first_actor_model_prd_alignment.md`**).

## Mapping to implementation questions (Crafty → Lupopedia)

| Topic | Implementation thread | Outcome |
|-------|------------------------|---------|
| Backing **`auth_user`**, LLM, fallback | [Q1](../../../implementations/13_crafty_integration/questions/20260403214146_QUESTION_visitor_chat_backing_auth_user.md) | Answered — hybrid baseline |
| Code **`actor_id`** first | [Q2](../../../implementations/13_crafty_integration/questions/20260403215011_QUESTION_visitor_chat_code_actor_first.md) | Answered — runtime audit may continue |
| PRD alignment | [Q3](../../../implementations/13_crafty_integration/questions/20260403215024_QUESTION_visitor_chat_prd_updates.md) | Answered |

## Canonical sources (repo)

- **`docs/doctrine/ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE.md`** — joins, eligibility, Crafty contrast, illustrative SQL (PHP is source of truth for lists).
- **PRD 05, 13, 15, 18, 25, 32** — plus **PRD 02**, **PRD 07** edges/prose aligned in the same documentation pass.

## Remaining (not documentation)

- **Product/runtime:** verify all visitor/chat POST paths resolve **`actor_id`** server-side and match **`channels-api`** behavior (**Q2** audit note).
- **Federation compiler:** open **[QUESTION](../questions/20260403_222042_QUESTION_federation_navigation_compiler.md)**.

This output complies with Lupopedia Constitutional Root Rules.
