---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: implementation
  when_updated: "20260403215024"
  file_path_from_root: "docs/implementations/13_crafty_integration/questions/20260403215011_QUESTION_visitor_chat_code_actor_first.md"
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
# QUESTION 2: Visitor chat — single source of truth (`actor_id` vs operator = `auth_user` only)

**Status:** **ANSWERED** (direction); **implementation audit OPEN**

## Question

Should visitor chat paths resolve **effective identity** to **`actor_id`** first (then derive display / human / LLM per [Q1](20260403214146_QUESTION_visitor_chat_backing_auth_user.md)), or keep a Crafty-style **operator = `auth_user` only** fast path as the long-term model?

## Answer (accepted)

- Resolve **visitor-facing “who is chatting”** to **`actor_id`** first (**department-scoped**).
- Apply **automation vs human** per **Q1** (LLM when allowed, else human `auth_user` in department).
- **Do not** treat **operator ≡ `auth_user` only** as the sole long-term model: **`auth_user`** is **human fallback / accountability**, not a replacement for **actor** identity.

## Deferred audit (runtime)

- Chat / operator modules assuming **operator ≡ `auth_user`** without **`actor_id`**.  
- Session and channel handlers: visitor message **attribution** path.  
- Doctrine still describing **human-only** counterparty without **actor**.  

Record findings here or under `decisions/` / channel threads; link **DECISION** when closed.

## Related

- **Q1:** [20260403214146_QUESTION_visitor_chat_backing_auth_user.md](20260403214146_QUESTION_visitor_chat_backing_auth_user.md)  
- **Q3:** [20260403215024_QUESTION_visitor_chat_prd_updates.md](20260403215024_QUESTION_visitor_chat_prd_updates.md)  

## Suggested owner

HEPHAESTUS / channel stack + WOLFIE sign-off on refactors.
