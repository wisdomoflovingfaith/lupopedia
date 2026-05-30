---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: implementation
  when_updated: "20260403215024"
  file_path_from_root: "docs/implementations/13_crafty_integration/questions/20260403214146_QUESTION_visitor_chat_backing_auth_user.md"
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
# QUESTION 1: Visitor chat — backing `auth_user`, human vs system vs LLM

**Status:** **ANSWERED**

## Question

When a visitor’s thread is attributed to an **actor**, what is the policy for **backing `auth_user`**, **human availability**, and **optional automation** (LLM)?

- **A:** Real human in department only (strict Crafty parity).  
- **B:** System-generated `auth_user` OK when no human logged in.  
- **C (hybrid):** Mix of human-backed and automation by policy.

## Context (short)

| | Crafty (legacy) | Lupopedia (target) |
|---|-----------------|---------------------|
| Counterparty | Human operator (`auth_user`) | **`actor_id`** (dept-scoped); human / LLM under policy |

## Answer (accepted)

**C (hybrid)** with explicit conditions:

1. **Baseline:** A human **`auth_user`** in the **same department** who **can** respond is the **compatibility floor** (Crafty-style “department can answer”).
2. **Optional automation:** After the **actor** has enough **learning** from human-handled traffic, replies **may** use **LLM / chatbot**, only if:
   - API keys (or equivalent) are **configured**;
   - **Tokens / quota** allow — if not, **fallback to human**;
   - Install may **disable automation** entirely.
3. **Fallback chain:** automation when healthy → **human in department** → **queue / offline / leave-message** (existing Crafty-compatible flows).

**Principle:** No assumption that every install has LLM keys or budget — support **human-only** and **degraded** modes.

## Related

- **Q2 (code model):** [20260403215011_QUESTION_visitor_chat_code_actor_first.md](20260403215011_QUESTION_visitor_chat_code_actor_first.md)  
- **Q3 (PRDs):** [20260403215024_QUESTION_visitor_chat_prd_updates.md](20260403215024_QUESTION_visitor_chat_prd_updates.md)  

## Suggested owner

WOLFIE / product + chat runtime owner.
