---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: implementation
  when_updated: "20260403215024"
  file_path_from_root: "lupo-docs/implementations/13_crafty_integration/questions/20260403215011_QUESTION_visitor_chat_code_actor_first.md"
  last_modified_utc: "20260403215024"
  channel_id: 42
  actor_id: 102
  artifact_type: implementation
  artifact_kind: question
  purpose: "Q2 answered: visitor chat resolves actor_id first; auth_user is fallback/accountability not sole identity"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/13_crafty_integration.md"
      type: references
      weight: 1.0
    - to: "lupo-docs/prd/15_actors.md"
      type: references
      weight: 1.0
    - to: "lupo-docs/implementations/13_crafty_integration/questions/20260403214146_QUESTION_visitor_chat_backing_auth_user.md"
      type: references
      weight: 1.0
      reason: "Q1 human baseline + LLM policy"
lupopedia.footer:
  last_verified: "20260403215024"
  verified_by:
    actor_id: 102
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
