---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: implementation
  when_updated: "20260403215630"
  file_path_from_root: "docs/implementations/13_crafty_integration/questions/20260403215024_QUESTION_visitor_chat_prd_updates.md"
  web_path: null
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: implementation
  artifact_kind: question
  thread_id: ""
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
# QUESTION 3: PRD documentation pass — visitor chat identity (Crafty → Lupopedia)

**Status:** **ANSWERED** (20260403215630 UTC) — PRD 05, 15, 25, and 13 carry explicit visitor-chat and migration narrative blocks consistent with Q1 and Q2.

## Question

Which PRDs must be updated so **normative text** matches:

- **Q1** — human-in-department baseline, optional LLM, keys/tokens/fallback ([backing auth_user](20260403214146_QUESTION_visitor_chat_backing_auth_user.md))  
- **Q2** — **`actor_id`** first in chat identity, not `auth_user`-only ([code model](20260403215011_QUESTION_visitor_chat_code_actor_first.md))  

## Checklist (not done until edited)

| PRD | Update |
|-----|--------|
| [PRD 05](../../prd/05_auth_user_actor_agent_transformation.md) | Done — §Visitor-facing chat identity chain |
| [PRD 15](../../prd/15_actors.md) | Done — actors belong to departments; `actor_type` vocabulary note |
| [PRD 25](../../prd/25_departments_system.md) | Done — visitor chat routing (actor-first) |
| [PRD 13](../../prd/13_crafty_integration.md) | Done — identity model after import |

## Completion criteria

- [x] Each listed PRD has an explicit subsection or bullet block for **visitor chat identity** (or migration narrative where PRD 13) consistent with Q1 + Q2.  
- [x] No intentional contradiction with [IDENTITY_LAYERS_DOCTRINE.md](../../../doctrine/IDENTITY_LAYERS_DOCTRINE.md); department-scoped actors match identity layers.

## Related

- **Q1:** [20260403214146_QUESTION_visitor_chat_backing_auth_user.md](20260403214146_QUESTION_visitor_chat_backing_auth_user.md)  
- **Q2:** [20260403215011_QUESTION_visitor_chat_code_actor_first.md](20260403215011_QUESTION_visitor_chat_code_actor_first.md)  

## Suggested owner

Cursor / HEPHAESTUS for drafts; **WOLFIE** approves PRD merges.
