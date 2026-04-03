---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: implementation
  when_updated: "20260403215024"
  file_path_from_root: "lupo-docs/implementations/13_crafty_integration/questions/20260403214146_QUESTION_visitor_chat_backing_auth_user.md"
  last_modified_utc: "20260403215024"
  channel_id: 42
  actor_id: 102
  artifact_type: implementation
  artifact_kind: question
  purpose: "Q1 answered: human auth_user baseline in dept + optional LLM + graceful degradation"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/13_crafty_integration.md"
      type: references
      weight: 1.0
    - to: "lupo-docs/prd/05_auth_user_actor_agent_transformation.md"
      type: references
      weight: 1.0
    - to: "lupo-docs/implementations/13_crafty_integration/questions/20260403215011_QUESTION_visitor_chat_code_actor_first.md"
      type: references
      weight: 0.95
      reason: "Related Q2: code resolves actor first"
    - to: "lupo-docs/implementations/13_crafty_integration/questions/20260403215024_QUESTION_visitor_chat_prd_updates.md"
      type: references
      weight: 0.9
      reason: "Related Q3: PRD documentation pass"
lupopedia.footer:
  last_verified: "20260403215024"
  verified_by:
    actor_id: 102
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
