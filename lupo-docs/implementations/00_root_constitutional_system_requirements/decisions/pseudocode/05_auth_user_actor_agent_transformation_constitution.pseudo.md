---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: pseudocode
  when_updated: "20260405211127"
  last_modified_utc: "20260405211127"
  file_path_from_root: "lupo-docs/implementations/00_root_constitutional_system_requirements/decisions/pseudocode/05_auth_user_actor_agent_transformation_constitution.pseudo.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/implementations/00_root_constitutional_system_requirements/decisions/pseudocode/05_auth_user_actor_agent_transformation_constitution.pseudo.md"
  channel_id: 42
  actor_id: 102
  delegation_chain: "cursor:root"
  artifact_type: pseudocode
  artifact_kind: constitution_shorthand
  purpose: "PRD 05 digest — auth user, actor, agent, visitor chat chain, department act-as (Purpose 1 per PRD 17)"
  tags:
    - pseudocode
    - constitution_shorthand
    - prd_05
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/05_auth_user_actor_agent_transformation.md"
      type: references
      weight: 1.0
      reason: "Canonical PRD — this file is non-authoritative"
    - to: "lupo-docs/prd/15_actors.md"
      type: references
      weight: 1.0
    - to: "lupo-docs/doctrine/ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE.md"
      type: references
      weight: 1.0
lupopedia.footer:
  last_verified: "20260405211127"
  verified_by:
    actor_id: 102
---

# PRD 05 shorthand — Auth / user / actor / agent (visitor chat)

**Canonical:** [PRD 05](../../../../prd/05_auth_user_actor_agent_transformation.md) · [ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE](../../../../doctrine/ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE.md)

## Runtime identity (visitor chat)

| Concept | Role |
|---------|------|
| **`actor_id`** | **Primary** “who answers the visitor” in chat — not bare **`auth_user_id`** alone. |
| **`auth_user`** | Login, accountability, human fallback — does **not** replace **`actor_id`** in architecture. |
| **Chain (normative)** | Visitor message → department-scoped **`actor_id`** → optional LLM/automation when policy allows → human **`auth_user`** in same department → queue/offline if needed. |

## Web act-as eligibility (4.0.x)

| Rule | Detail |
|------|--------|
| **Department intersection** | User may select an actor only if **`lupo_auth_user_departments`** and **`lupo_actor_departments`** share at least one **`department_id`**. |
| **`lupo_actor_auth_users`** | Explicit auth↔actor links — **audit / primary operator / import**; does **not** replace department membership for “who may use this actor.” |
| **`web_restrict_act_as_creator_or_root`** | When set, PHP narrows act-as to creator or elevated roles (root dept, admin, etc.). |

## Agents vs actors vs auth users

| Layer | Meaning |
|-------|---------|
| **`lupo_agents` + `lupo-agents/{key}/`** | **Agent** = configuration surface (capabilities, prompts). |
| **`lupo_actors`** | **Actor** = runtime orchestration identity (**`actor_id`** canonical). |
| **`lupo_auth_users`** | Human login rows. |

**Service personas** (LILITH, THOTH, ROSE, KAIROS, …): resolve **`actor_id`** from **`lupo-database/lupopedia/actors/registry.json`** — automation is **PHP-orchestrated**, not “random chat bot” semantics.

## Channel messages (`lupo_dialog_messages`)

| Field | Meaning |
|-------|---------|
| **`from_actor_id`** | Transcript identity for the strip (**PRD 18**). |
| **`to_actor_id`** | **Routing** hint; **NULL** = broadcast. **Not** row-level privacy — channel membership controls read access. |

**Thread context:** Services processing a thread read **full** **`channel_id` + `dialog_thread_id`** scope when building context (**PRD 36**, **PRD 37** §10.6).

## Database posture (cross-ref PRD 00)

No FKs; BIGINT UTC; explicit IDs; soft delete — same as constitution.
