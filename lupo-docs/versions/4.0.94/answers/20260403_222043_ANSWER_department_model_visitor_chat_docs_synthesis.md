---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  file_path_from_root: "lupo-docs/versions/4.0.94/answers/20260403_222043_ANSWER_department_model_visitor_chat_docs_synthesis.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.94/answers/20260403_222043_ANSWER_department_model_visitor_chat_docs_synthesis.md"
  when_updated: "20260403222043"
  last_modified_utc: "20260403222043"
  channel_id: 42
  federation_node_id: 0
  thread_id: "version-4-0-94-answers"
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: answer
  artifact_kind: synthesis
  purpose: "Synthesize implementation visitor-chat Q1–Q3 outcomes with canonical doctrine + PRD pass"
  status: answered
  tags:
    - "4.0.94"
    - "answer"
    - "department_model"
    - "visitor_chat"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/implementations/13_crafty_integration/questions/20260403214146_QUESTION_visitor_chat_backing_auth_user.md"
      type: answers
      weight: 1.0
      reason: "Q1 backing auth / LLM / fallback"
    - to: "lupo-docs/implementations/13_crafty_integration/questions/20260403215011_QUESTION_visitor_chat_code_actor_first.md"
      type: answers
      weight: 1.0
      reason: "Q2 actor_id first in code"
    - to: "lupo-docs/implementations/13_crafty_integration/questions/20260403215024_QUESTION_visitor_chat_prd_updates.md"
      type: answers
      weight: 1.0
      reason: "Q3 PRD doc pass — answered"
    - to: "lupo-docs/doctrine/ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Canonical mental model"
    - to: "lupo-docs/versions/4.0.94/decisions/20260403_222041_DECISION_APPROVED_department_first_actor_model_prd_alignment.md"
      type: references
      weight: 1.0
      reason: "Version-folder APPROVED decision"
lupopedia.footer:
  last_verified: "20260403222043"
  verified_by:
    identity_type: actor
    actor_id: 102
  orchestrator: "cursor:root"
---

# file: ANSWER — department model + visitor chat docs synthesis — web_path: /lupo-docs/versions/4.0.94/answers/20260403_222043_ANSWER_department_model_visitor_chat_docs_synthesis.md

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

- **`lupo-docs/doctrine/ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE.md`** — joins, eligibility, Crafty contrast, illustrative SQL (PHP is source of truth for lists).
- **PRD 05, 13, 15, 18, 25, 32** — plus **PRD 02**, **PRD 07** edges/prose aligned in the same documentation pass.

## Remaining (not documentation)

- **Product/runtime:** verify all visitor/chat POST paths resolve **`actor_id`** server-side and match **`channels-api`** behavior (**Q2** audit note).
- **Federation compiler:** open **[QUESTION](../questions/20260403_222042_QUESTION_federation_navigation_compiler.md)**.

This output complies with Lupopedia Constitutional Root Rules.
